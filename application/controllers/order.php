<?php
/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/11
 * Time: 11:59
 */
class order extends BaseController{

    protected $merchantInfo=array();
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('OrderModel');
        $this->load->model('GoodsModel');
        if(empty($_COOKIE['merInfo_0'])){
            redirect('login/index');
            die();
        }
        $this->merchantInfo=json_decode($_COOKIE['merInfo_0'],true);
    }

    public function index(){
        $cmo=!empty($_GET['cmo'])?$_GET['cmo']:'';
        $where=array('merchant_code'=>$this->merchantInfo['merchant_code'],'order_status'=>1,'cmo'=>$cmo);
        $order['cmo']=$cmo;
        $order['order_list']=$this->OrderModel->getMerchantOrders($where);
        $order['photo_name']=$this->merchantInfo['simple_word'];
        $data=array(
            array('template'=>'naviga_bar','data'=>$order),
            array('template'=>'order','data'=>$order)
        );
        $this->loadBlockView($data);
    }

    /**
     * 根据订单号查看订单详情
     * @param $order_id
     */
    public function detail($order_id){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $order=$this->OrderModel->getRow(array('merchant_code'=>$merchant_code,'order_no'=>$order_id));
        if(empty($order)) die('订单不存在');
        $dataC['category']=$this->GoodsModel->getGoodsClass($this->merchantInfo['merchant_code']);
        $base=array('order'=>$order,'order_no'=>$order_id);
        $base['goods_list']=$this->OrderModel->getGoodsSold($merchant_code,$order_id);
        $base['merchant_code']=$this->merchantInfo['merchant_code'];
        $base['photo_name']=$this->merchantInfo['simple_word'];
        $data=array(
            array('template'=>'naviga_bar','data'=>$base),
            array('template'=>'consume_class','data'=>array_merge($dataC,$base)),
            array('template'=>'consume_prodlist','data'=>array()),
            array('template'=>'order_detail','data'=>$base),
        );
        $this->loadBlockView($data);
    }

    /**
     * 根据订单号查询订单详情
     */
    public function orderDetail(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $goods=$this->OrderModel->getGoodsSold($merchant_code,$_POST['order_no']);
        foreach($goods as $item=>$value){
            $goodsInfo=$this->GoodsModel->getGoodsInfo($merchant_code,$value['goods_id']);
            $goods[$item]['goods_code']=$goodsInfo['goods_code'];
            $goods[$item]['price']=$goodsInfo['price'];
            $goods[$item]['discount']=intval(floatval($value['sold_price'])/floatval($goodsInfo['price'])*100);
        }
        if(!empty($goods)){
            $this->rspsJson(true,'',$goods);
        }
    }

    /**
     * 取消订单
     */
    public function cancelOrder(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $flag=$this->OrderModel->cancelOrder($_POST['order_no'],$merchant_code);
        if($flag){
            $this->rspsJson(true,'');
        }
    }
}
?>