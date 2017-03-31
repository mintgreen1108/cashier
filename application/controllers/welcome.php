<?php
/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/11
 * Time: 20:57
 */
class welcome extends BaseController{

    protected $merchantInfo=array();
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');

        if(empty($_COOKIE['merInfo_0'])){
            redirect('login/index');
            die();
        }
        $this->merchantInfo=json_decode($_COOKIE['merInfo_0'],true);
    }

    public function index(){
        $base['photo_name']=$this->merchantInfo['simple_word'];
        $data=array(
            array('template'=>'naviga_bar','data'=>$base),
            array('template'=>'welcome','data'=>$base)
        );
        $this->loadBlockView($data);
    }
}
?>