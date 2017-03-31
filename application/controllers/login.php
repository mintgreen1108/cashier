<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/8
 * Time: 19:26
 */
class login extends BaseController
{

    protected $merchantInfo = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('MerchantModel');
        $this->load->model('MemberModel');
        $this->load->model('InvoiceModel');
        $this->load->model('StatisticModel');
        $this->load->library('cryption/TCryption');
        $this->load->helper('cookie');
        if (!empty($_COOKIE['merInfo_0'])) {
            $this->merchantInfo = json_decode($_COOKIE['merInfo_0'], true);
        } else if (!empty($_COOKIE['merInfo_1'])) {
            $this->merchantInfo = json_encode($_COOKIE['merInfo_1'], true);
        }
    }

    public function index()
    {
        if (empty($this->merchantInfo)) {
            $this->loadView('login');
        } else if (!empty($_COOKIE['merInfo_0'])) {
            redirect('welcome/index');
        } else if (!empty($_COOKIE['merInfo_1'])) {
            redirect('admin/home');
        }
    }

    /**
     * 登陆API
     * 密码加密方式：MD5（注册时间+MD5(PWD))
     */
    public function loginAction()
    {
        $en = new TCryption();
        $enToken = $en->enCryption($_GET['token']);
        $merchantInfo = $this->MerchantModel->getMerchantInfor($enToken);
        $pwd = md5($_GET['pwd']);
        $merchantOp = $this->MerchantModel->getMerchantOp($merchantInfo['merchant_code'], $_GET['uname']);
        if ($merchantOp['emp_passwd'] == md5($pwd . $merchantOp['create_time'])) {
            $merchantInfo['emp_no'] = $merchantOp['emp_no'];
            $merchantInfo['emp_info'] = $merchantOp;
            if (!$merchantOp['operate_type']) {
                setcookie('merInfo_0', json_encode($merchantInfo), time() + 3600 * 24, '/');
            } else {
                setcookie('merInfo_1', json_encode($merchantInfo), time() + 3600 * 24, '/');
            }
            $this->rspsJson(true, '', $merchantOp['operate_type']);
        } else {
            $this->rspsJson(false);
        }
    }

    /**
     * @author 汤圆
     * @function 获取对账信息
     */
    public function getlogoutInfo()
    {
        $start_time = date('Y-m-d') . ' 00:00:00';
        $end_time = date("Y-m-d H:i:s");
        $emp_name = $this->MerchantModel->getEmpName($this->merchantInfo['merchant_code'], $this->merchantInfo['emp_no']);
        $member_add = $this->MemberModel->countMemberDaily($this->merchantInfo['merchant_code'], $start_time, $end_time, $this->merchantInfo['emp_no']);
        $invoice = $this->InvoiceModel->statisticInvoice($this->merchantInfo['merchant_code'], $start_time, $end_time, $this->merchantInfo['emp_no']);
        $this->rspsJson(true, '', compact('emp_name', 'member_add', 'invoice'));
    }

    /**
     * @author 汤圆
     * @function 退出清cookie
     */
    public function logoutAction()
    {
        $data = [
            'merchant_id' => $this->merchantInfo['merchant_id'],
            'merchant_code' => $this->merchantInfo['merchant_code'],
            'member_add_count' => $_POST['member_add'],
            'zfb_sum' => $_POST['zfb_total'],
            'zfb_count' => $_POST['zfb_count'],
            'cash_sum' => $_POST['cash_total'],
            'cash_count' => $_POST['cash_count'],
            'remark' => $_POST['remark'],
            'statistic_day' => date('Y-m-d')
        ];

        if (empty($this->StatisticModel->checkStatisticInfoExit($data['merchant_code'], $data['statistic_day'])))
            $flag = $this->StatisticModel->insertStatisticInfo($data);
        else
            $flag = $this->StatisticModel->updateStatisticInfo($data['merchant_code'], $data['statistic_day'], $data);

        if ($flag) {
            $start_time = date('Y-m-d') . ' 00:00:00';
            $end_time = date("Y-m-d H:i:s");
            $this->MemberModel->updateCheckFlag($this->merchantInfo['merchant_code'], $start_time, $end_time, $this->merchantInfo['emp_no']);
            $this->InvoiceModel->updateCheckFlag($this->merchantInfo['merchant_code'], $start_time, $end_time, $this->merchantInfo['emp_no']);
            setcookie('merInfo_0', '', time() - 3600, '/');
            $this->rspsJson(true);
        } else {
            $this->rspsJson(false);
        }

    }

    public function logoutAdmin()
    {
        setcookie('merInfo_1', '', time() - 3600, '/');
        $this->rspsJson(true);
    }
}

?>
