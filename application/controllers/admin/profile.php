<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2017/1/4
 * Time: 15:33
 */
class profile extends BaseController
{

    protected $merchantInfo = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('MerchantModel');
        if (empty($_COOKIE['merInfo_1'])) {
            redirect('login/index');
            die();
        }
        $this->merchantInfo = json_decode($_COOKIE['merInfo_1'], true);
    }

    public function index()
    {
        $data['emp_info']=$this->MerchantModel->getMerchantOp($this->merchantInfo['merchant_code'],$this->merchantInfo['emp_no']);
        $this->loadAdminView('admin/profile',$data);
    }

    public function modifyEmpInfo()
    {
        $data = [
            'emp_name' => $_POST['nickname'],
            'email' => $_POST['email'],
            'mobile' => $_POST['mobile'],
            'address' => $_POST['address'],
        ];
        if(!empty($_POST['password'])) $data['emp_passwd']=md5(md5($_POST['password']) . $this->merchantInfo['emp_info']['create_time']);
        $flag = $this->MerchantModel->modifyEmpInfo($this->merchantInfo['emp_no'], $data);
        if($flag){
            return $this->rspsJson(true);
        }
        else return $this->rspsJson(false);
    }

}