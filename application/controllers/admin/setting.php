<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2017/3/25
 * Time: 23:45
 */
class setting extends BaseController
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
        $this->loadAdminView('admin/setting', [
            'config' => $this->MerchantModel->getConfig($this->merchantInfo['merchant_code'])
        ]);
    }

    public function update()
    {
        $data = [
            'bus_start_time' => $_POST['start_time'],
            'bus_end_time' => $_POST['end_time'],
            'point_expire_days' => $_POST['expire_days'],
            'point_rate' => $_POST['rate']
        ];

        if ($this->MerchantModel->updateConfig($this->merchantInfo['merchant_code'], $data))
            $this->rspsJson(true);
        else
            $this->rspsJson(false);
    }
}