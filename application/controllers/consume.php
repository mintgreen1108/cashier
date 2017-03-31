<?php
/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/10
 * Time: 21:22
 */
class consume extends BaseController{

    protected $merchantInfo=array();
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('MerchantModel');
        $this->load->model('GoodsModel');
        $this->load->model('MemberModel');
        $this->load->model('OrderModel');
        $this->load->helper('cookie');

        if(empty($_COOKIE['merInfo_0'])){
            redirect('login/index');
            die();
        }
        $this->merchantInfo=json_decode($_COOKIE['merInfo_0'],true);
    }

    public function index(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $dataC['category']=$this->GoodsModel->getGoodsClass($merchant_code);
        $base['merchant_code']=$this->merchantInfo['merchant_code'];
        $base['photo_name']=$this->merchantInfo['simple_word'];
        $data=array(
            array('template'=>'naviga_bar','data'=>$base),
            array('template'=>'consume_class','data'=>array_merge($dataC,$base)),
            array('template'=>'consume_prodlist','data'=>array()),
            array('template'=>'consume_cart','data'=>$base),
        );
        $this->loadBlockView($data);
    }

    /**
     * 会员登陆
     */
    public function memberLogin(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $member_id=!empty($_POST['member_id'])?$_POST['member_id']:'';
        $memberInfo=$this->MemberModel->getMemeberInfo($merchant_code,$member_id);
        if(!empty($memberInfo)){
            $this->rspsJson(true,'登陆成功',$memberInfo);
        }
    }

    /**
     * 获取分类下的商品列表
     */
    public function productsList(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $products=$this->GoodsModel->getProducts($_POST['classId'],$merchant_code);
        $this->rspsJson(true,'',$products);
    }

    /**
     * 获取所查询的商品列表
     */
    public function productsQuery(){
        $merchant_code=$this->merchantInfo['merchant_code'];
        $products=$this->GoodsModel->queryProducts($merchant_code,$_POST['key']);
        $this->rspsJson(true,'',$products);
    }

    /**
     * 生成订单
     */
    public function createOrder(){
        $orderInfo=array(
            'merchant_code'=>$this->merchantInfo['merchant_code'],
            'merchant_id'=>$this->merchantInfo['merchant_id'],
            'emp_no'=>$this->merchantInfo['emp_no'],
            'card_no'=>$_POST['card_no'],
            'member_id'=>(int)$_POST['member_id'],
            'phone'=>$_POST['mobile'],
            'total'=>0,
            'total_num'=>0
        );
        $goods_list=array();
        foreach($_POST['goods_list'] as $item){
            $orderInfo['total']+=$item['gprice']*$item['gnum'];
            $orderInfo{'total_num'}+=$item['gnum'];
            $goods_list[]=array(
                'goods_id'=>$item['gid'],
                'goods_name'=>$item['gname'],
                'sold_price'=>$item['gprice'],
                'sold_total'=>$item['gnum']
            );
        }
        $order=$this->OrderModel->genOrder($orderInfo,$goods_list);
        if($order){
            $this->rspsJson(true,'添加成功',$order);
        }else{
            $this->rspsJson(false,'添加失败');
        }
    }

    /**
     * 修改订单
     */
    public function modifyOrder(){
        $orderInfo=array(
            'merchant_code'=>$this->merchantInfo['merchant_code'],
            'merchant_id'=>$this->merchantInfo['merchant_id'],
            'emp_no'=>$this->merchantInfo['emp_no'],
            'card_no'=>$_POST['card_no'],
            'order_no'=>$_POST['order_no'],
            'member_id'=>(int)$_POST['member_id'],
            'phone'=>$_POST['mobile'],
            'total'=>0,
            'total_num'=>0
        );
        $goods_list=array();
        foreach($_POST['goods_list'] as $item){
            $orderInfo['total']+=$item['gprice']*$item['gnum'];
            $orderInfo{'total_num'}+=$item['gnum'];
            $goods_list[]=array(
                'goods_id'=>$item['gid'],
                'goods_name'=>$item['gname'],
                'sold_price'=>$item['gprice'],
                'sold_total'=>$item['gnum']
            );
        }
        $order=$this->OrderModel->modifyOrder($this->merchantInfo['merchant_code'],$orderInfo,$goods_list);
        if($order){
            $this->rspsJson(true,'添加成功',$order);
        }else{
            $this->rspsJson(false,'添加失败');
        }
    }
}
?>