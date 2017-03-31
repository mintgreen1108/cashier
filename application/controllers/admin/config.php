<?php
/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2017/1/4
 * Time: 17:30
 */
class config extends BaseController{

    protected $merchantInfo=[];
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        if(empty($_COOKIE['merInfo_1'])){
            redirect('login/index');
            die();
        }
        $this->merchantInfo=json_decode($_COOKIE['merInfo_1'],true);
    }

    public function index(){
        $this->loadAdminView('admin/config',[]);
    }
}