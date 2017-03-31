<?php

class home extends BaseController
{
    protected $merchantInfo = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->Model('MemberModel');
        $this->load->Model('InvoiceModel');
        $this->load->Model('StatisticModel');
        if (empty($_COOKIE['merInfo_1'])) {
            redirect('login/index');
            die();
        }
        $this->merchantInfo = json_decode($_COOKIE['merInfo_1'], true);
    }

    public function index()
    {
        //今日数据
        $today = $this->getDataToday();
        //历史数据
        $history = $this->getDataHistory();
        //趋势数据
        $data = $this->getInvoiceStatic();
        $this->loadAdminView('admin/dashboard', compact('today', 'history', 'data','basic'));
    }

    /**
     * @author 汤圆
     * @function 获取今日数据
     */
    public function getDataToday()
    {
        $start_time = date('Y-m-d') . ' 00:00:00';
        $end_time = date("Y-m-d H:i:s");
        //获取会员增长量
        $member_add_today = $this->MemberModel->countMemberNum($this->merchantInfo['merchant_code'], $start_time, $end_time);
        //获取交易额
        $invoice = $this->InvoiceModel->countInvoiceData($this->merchantInfo['merchant_code'], $start_time, $end_time);
        return compact('member_add_today', 'invoice');
    }

    /**
     * @author 汤圆
     * @function 获取历史数据
     */
    public function getDataHistory()
    {
        return $this->StatisticModel->getMerchantStatisticSum($this->merchantInfo['merchant_code'], '2001-1-1', date('Y-m-d'));
    }

    /**
     * @author 汤圆
     * @function 获取增长情况
     */
    public function getInvoiceStatic()
    {
        $date = empty($_POST['date']) ? '' : $_POST['date'];
        $date_op = empty($_POST['date_op']) ? '' : $_POST['date_op'];
        $date_type = empty($_POST['date_type']) ? 'week' : $_POST['date_type'];
        $dateArr = $this->handleDate($date, $date_op, $date_type);
        $dateList = $this->getDateList($dateArr['start_date'], $dateArr['end_date']);
        $invoiceList = $this->StatisticModel->getStatisticList($this->merchantInfo['merchant_code'], $dateList);
        $data = $this->dealDateList($invoiceList, $dateList);
        return compact('dateList', 'data');
    }

    /**
     * @author 汤圆
     * @function 获取数据接口
     */
    public function getInStatisAction()
    {
        $data = $this->getInvoiceStatic();
        $this->rspsJson(true, '', $data);
    }

    /**
     * @author 汤圆
     * @function 获取日期列表
     * @param $start_time
     * @param $end_time
     * @return array
     *
     */
    public function getDateList($start_time, $end_time)
    {
        $dateList = [];
        for ($i = strtotime($start_time); $i <= strtotime($end_time); $i += 86400) {
            $dateList[] = date('Y-m-d', $i);
        }
        return $dateList;
    }

    /**
     * @author 汤圆
     * @function 处理时间函数
     * @param $date
     * @param $date_op
     * @param $date_type
     * @return array
     */
    public function handleDate($date, $date_op, $date_type)
    {
        switch ($date_type) {
            case 'week':
                $date_num = 7;
                break;
            case 'month':
                $date_num = 30;
                break;
        }

        switch ($date_op) {
            case 'prev':
                $start_date = date('Y-m-d', strtotime($date) - $date_num * 86400);
                $end_date = date('Y-m-d', strtotime($date) - 86400);
                break;
            case 'next':
                $start_date = date('Y-m-d', strtotime($date) + $date_num * 86400);
                $end_date = date('Y-m-d', strtotime($start_date) + ($date_num - 1) * 86400);
                break;
            default:
                $start_date = date('Y-m-d', time() - $date_num * 86400);
                $end_date = date('Y-m-d', time() - 86400);
        }

        return compact('start_date', 'end_date');
    }

    /**
     * @author 汤圆
     * @function 序列化列表
     * @param $list
     * @param $dateList
     * @return array
     */
    public function dealDateList($list, $dateList)
    {
        function intType(&$value, $key){
            $value = (int)$value;
        }
        foreach ($list as $key => $detail) {
            $list[$detail['statistic_day']] = $detail;
        }
        $invoiceList = [];
        foreach ($dateList as $key => $date) {
            if (array_key_exists($date, $list)) {
                $invoiceList[] = $list[$date];
            } else {
                $invoiceList[] = ['member_add_count' => 0, 'zfb_sum' => 0, 'cash_sum' => 0];
            }
        }
        $memberList=array_column($invoiceList, 'member_add_count');
        array_walk($memberList,'intType');
        $zfbList = array_column($invoiceList, 'zfb_sum');
        array_walk($zfbList,'intType');
        $cashList = array_column($invoiceList, 'cash_sum');
        array_walk($cashList,'intType');
        return compact('memberList', 'zfbList', 'cashList');
    }

}