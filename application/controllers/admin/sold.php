<?php

class sold extends BaseController
{

    protected $merchantInfo = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('MerchantModel');
        $this->load->model('OrderModel');
        if (empty($_COOKIE['merInfo_1'])) {
            redirect('login/index');
            die();
        }
        $this->merchantInfo = json_decode($_COOKIE['merInfo_1'], true);
    }

    public function index()
    {
        $this->loadAdminView('admin/goodAnalysis');
    }

    public function getData($start_time, $end_time)
    {
        $class = $this->OrderModel->getClassSold($merchant_code = $this->merchantInfo['merchant_code'], $start_time, $end_time);
        $total = [
            'count' => array_sum(array_column($class, 'count')),
            'money' => array_sum(array_column($class, 'money'))
        ];
        $class1 = [];
        $class2 = [];
        foreach ($class as $k => $v) {
            $class1[$k]['name'] = $v['class_name'];
            $class1[$k]['y'] = empty($total['count']) ? '0' : $v['count'] / $total['count'] * 100;
            $class2[$k]['name'] = $v['class_name'];
            $class2[$k]['y'] = empty($total['money']) ? '0' : $v['money'] / $total['money'] * 100;
        }
        $goodPrice = $this->OrderModel->getHotGoodsByMoney($merchant_code, $start_time, $end_time);
        $totalPrice = array_sum(array_column($goodPrice, 'money'));
        foreach ($goodPrice as $k => $v) {
            $goodPrice[$k]['rate'] = empty($totalPrice) ? '0' : $v['money'] / $totalPrice * 100;
        }
        $goodsNum = $this->OrderModel->getHotGoodsByNum($merchant_code, $start_time, $end_time);
        $totalCount = array_sum(array_column($goodsNum, 'count'));
        foreach ($goodsNum as $k => $v) {
            $goodsNum[$k]['rate'] = empty($totalCount) ? '0' : $v['count'] / $totalCount * 100;
        }
        return compact('class1', 'class2', 'goodPrice', 'goodsNum');
    }

    public function callData()
    {
        $start = empty($_POST['start_time']) ? date('Y-m-1 00:00:00', time()) : $_POST['start_time'];
        $end = empty($_POST['end_time']) ? date('Y-m-' . date('t', time()) . ' 23:59:59', time()) : $_POST['end_time'];
        if ($data = $this->getData($start, $end))
            $this->rspsJson(true, '', $data);
        else
            $this->rspsJson(false);
    }
}