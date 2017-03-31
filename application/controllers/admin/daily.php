<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2017/1/4
 * Time: 16:12
 */
class daily extends BaseController
{

    protected $merchantInfo = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->Model('StatisticModel');
        if (empty($_COOKIE['merInfo_1'])) {
            redirect('login/index');
            die();
        }
        $this->merchantInfo = json_decode($_COOKIE['merInfo_1'], true);
    }

    /**
     * @author 汤圆
     * @function 显示每日数据统计
     */
    public function index()
    {
        $statsList = $this->StatisticModel->getStatsList($this->merchantInfo['merchant_code']);
        $statsList = $this->addMerchantName($statsList);
        $this->loadAdminView('admin/daily', [
            'list' => $statsList,
            'sum'=>array_sum(array_column($statsList,'cash_sum'))+array_sum(array_column($statsList,'zfb_sum'))
        ]);
    }

    /**
     * @author 汤圆
     * @function 分页获取列表
     * @param $page
     */
    public function getListByPage($page)
    {
        $list = $this->StatisticModel->getStatsList($this->merchantInfo['merchant_code']);
        $list = $this->addMerchantName($list);
        if (!empty($list)){
            $this->rspsJson(true,'',$list);
        }
        $this->rspsJson(false, '服务器出错，请重试');
    }

    /**
     * @author 汤圆
     * @function 添加商户信息
     * @param $list
     * @return mixed
     */
    public function addMerchantName($list)
    {
        foreach ($list as $key => $value) {
            $list[$key]['merchant_name'] = $this->merchantInfo['merchant_name'];
        }
        return $list;
    }
}