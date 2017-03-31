<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/14
 * Time: 21:19
 */
class My_Controller extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }
}

class BaseController extends My_Controller{

    protected $merchantInfo = [];
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        if (!empty($_COOKIE['merInfo_1'])) {
            $this->merchantInfo = json_decode($_COOKIE['merInfo_1'], true);
        }
    }

    /**
     * 加载单个view
     * @param $template
     * @param array $data
     */
    public function loadView($template,$data=array()){
        $this->load->view('header');
        $this->load->view($template,$data);
        $this->load->view('footer');
    }

    /**
     * @author 汤圆
     * @function 后台加载单个view
     * @param $template
     * @param array $data
     */
    public function loadAdminView($template,$data=[]){
        //基础数据
        $basic['merchant_name']=$this->merchantInfo['merchant_name'];
        $basic['emp_mobile']=$this->merchantInfo['emp_info']['mobile'];
        $basic['photo_name']=$this->merchantInfo['simple_word'];
        $this->load->view('admin/header');
        $this->load->view('admin/home',$basic);
        $this->load->view($template,$data);
        $this->load->view('admin/footer');
    }

    /**
     * 加载多个view
     * @param $viewArr
     */
    public function loadBlockView($viewArr){
        $this->load->view('header');
        foreach($viewArr as $value){
            $this->load->view($value['template'],$value['data']);
        }
        $this->load->view('footer');
    }

    /**
     * @author 汤圆
     * @function 后台加载多个view
     * @param $viewArr
     */
    public function loadAdminBlockView($viewArr){
        $this->load->view('admin/header');
        foreach ($viewArr as $value){
            $this->load->view($value['template'],$value['data']);
        }
        $this->load->view('admin/footer');
    }

    /**
     * @author 汤圆
     * @function 新建会员
     * @param $result
     * @param string $msg
     * @param array $data
     */
    public function rspsJson($result,$msg='',$data=array()){
        $data=array('result'=>$result,'msg'=>$msg,'data'=>$data);
        echo json_encode($data);
        die();
    }
}