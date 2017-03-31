<?php
/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/11
 * Time: 22:39
 */
class invoice extends BaseController{

    protected $merchantInfo=array();
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('InvoiceModel');
        $this->load->model('OrderModel');
        $this->load->model('GoodsModel');
        $this->load->model('MemberModel');
        $this->load->model('MerchantModel');
        if(empty($_COOKIE['merInfo_0'])){
            redirect('login/index');
            die();
        }
        $this->merchantInfo=json_decode($_COOKIE['merInfo_0'],true);
    }

    public function index(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $base['photo_name']=$this->merchantInfo['simple_word'];
        $count=$this->InvoiceModel->getInvoiceCount($merchant_code);
        $count2=$this->GoodsModel->getGoodsSoldCount($merchant_code);
        $base['goods_count']=ceil($count2/10);
        $base['in_count']=ceil($count/10);
        $base['invoice']=$this->InvoiceModel->getInvoiceList($merchant_code,1);
        $base['invoice']=$this->transInfo($base['invoice']);
        $data=array(
            array('template'=>'naviga_bar','data'=>$base),
            array('template'=>'invoice','data'=>$base)
        );
        $this->loadBlockView($data);
    }

    /**
     * 支付订单
     */
    public function genInvoice(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $order_info=$this->OrderModel->getOrderInfo($merchant_code,$_POST['order_no']);
        $invoice=array(
            'merchant_id'=> $order_info['merchant_id'],
            'merchant_code'=>$order_info['merchant_code'],
            'emp_no'=>$order_info['emp_no'],
            'ip'=>$_SERVER['REMOTE_ADDR'],
            'order_id'=>$order_info['order_no'],
            'member_id'=>$order_info['member_id'],
            'card_no'=>$order_info['card_no'],
            'paid'=>$order_info['total'],
            'total'=>$order_info['total']
        );
        switch($_POST['pay_way']){
            case 'pay_cash':
                $invoice['cash']=$order_info['total'];
                break;
            case 'pay_zfb':
                $invoice['bank_type']=3;
                $invoice['bank']=$order_info['total'];
                break;
        }
        $this->OrderModel->completeOrder($merchant_code,$_POST['order_no']);
        $flag=$this->InvoiceModel->genInvoice($invoice);
        //增加会员积分
        $merchantConfig=$this->MerchantModel->getMerchantConfig($merchant_code);
        $this->MemberModel->addMemberPoint($merchant_code,$invoice['card_no'],$invoice['paid']*$merchantConfig['point_rate']);
        if($flag){
            $this->rspsJson(true);
        }else{
            $this->rspsJson(false);
        }
    }

    /**
     * 分页查询商户下的流水表
     */
    public function getInvoiceList(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $invoiceList=$this->InvoiceModel->getInvoiceList($merchant_code,$_POST['page']);
        $invoiceList=$this->transInfo($invoiceList);
        $this->rspsJson('true','',$invoiceList);
    }

    /**
     * 撤销交易流水
     */
    public function revokeInvoice(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $invoice=$this->InvoiceModel->getInvoiceInfo($merchant_code,$_POST['invoice_id']);
        $flag=$this->InvoiceModel->revokeInvoice($merchant_code,$_POST['invoice_id'],$invoice['order_id']);
        if($flag){
            $this->rspsJson('true');
        }else{
            $this->rspsJson('false');
        }
    }

    /**
     * 分页查询商户的已售商品
     */
    public function getGoodsSoldList(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $goodsList=$this->GoodsModel->getGoodsSoldList($merchant_code,$_POST['page']);
        $goodsList=$this->transInfo($goodsList);
        $this->rspsJson(true,'',$goodsList);
    }

    /**
     * @author 汤圆
     * @function 搜索交易流水
     */
    public function searchInvoice(){
        $data=$this->InvoiceModel->searchInvoice($this->merchantInfo['merchant_code'],$_POST['keyword']);
        $data=$this->transInfo($data);
        if(!empty($data)){
            $this->rspsJson(true,'',$data);
        }else{
            $this->rspsJson(false);
        }
    }

    /**
     * @author 汤圆
     * @function 搜索已售商品
     */
    public function searchGoodsSold(){
        $data=$this->GoodsModel->searchGoodsSold($this->merchantInfo['merchant_code'],$_POST['keyword']);
        $data=$this->transInfo($data);
        if(!empty($data)){
            $this->rspsJson(true,'',$data);
        }else{
            $this->rspsJson(false);
        }
    }

    /**
     * @author 汤圆
     * @function 转换交易机构和操作员名
     * @param $data
     */
    public function transInfo($data){
        foreach($data as $key=>$value){
            $data[$key]['merchant_name']=$this->MerchantModel->getMerchantName($value['merchant_code']);
            $data[$key]['emp_name']=$this->MerchantModel->getEmpName($value['merchant_code'],$value['emp_no']);
        }
        return $data;
    }
}
?>