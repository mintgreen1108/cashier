<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2017/1/4
 * Time: 16:26
 */
class operator extends BaseController
{
    protected $merchantInfo = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
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
        //员工列表
        $data['opList'] = $this->MerchantModel->getEmpList($merchant_code = $this->merchantInfo['merchant_code']);
        for ($i = 0; $i < count($data['opList']); $i++) {
            $data['opList'][$i]['merchant_name'] = $this->MerchantModel->getMerchantName($data['opList'][$i]['merchant_code']);
        }
        //员工绩效
        $data['performance'] = $this->OrderModel->getOpPerformance($merchant_code, date('Y-m-1 00:00:00', time()), date('Y-m-' . date('t', time()) . ' 23:59:59', time()));
        $data['sold_money'] = 0.00;
        $data['time'] = date('Y-m', time());
        foreach ($data['performance'] as $key => $value) {
            $data['performance'][$key]['money'] = empty($value['money']) ? '0.00' : $value['money'];
            $data['performance'][$key]['count'] = empty($value['count']) ? '0' : $value['count'];
            $data['sold_money'] += $value['money'];
        }
        $this->loadAdminView('admin/operator', $data);
    }

    /**
     * @author 汤圆
     * @function 添加会员
     */
    public function addOperator()
    {
        $data = [
            'merchant_id' => $this->merchantInfo['merchant_id'],
            'merchant_code' => $this->merchantInfo['merchant_code'],
            'emp_no' => $_POST['no'],
            'emp_name' => $_POST['name'],
            'emp_passwd' => md5(md5($_POST['password']) . date('Y-m-d H:i:s', time())),
            'mobile' => $_POST['mobile'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'operate_type' => 0,
            'create_time' => date('Y-m-d H:i:s', time())
        ];
        if ($flag = $this->MerchantModel->addEmp($data))
            $this->rspsJson(true, '注册成功');
        else
            $this->rspsJson(false, '注册失败');
    }

    /**
     * @author 汤圆
     * @function 更新操作员信息
     */
    public function updateOperator()
    {
        $data = [
            'emp_name' => $_POST['name'],
            'mobile' => $_POST['mobile'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
        ];
        if (!empty($_POST['password'])) {
            $data['password'] = md5(md5($_POST['password']) . date('Y-m-d H:i:s', time()));
            $data['create_time'] = date('Y-m-d H:i:s', time());
        }
        if ($this->MerchantModel->updateEmp($_POST['no'], $data))
            $this->rspsJson(true, '更新成功');
        else
            $this->rspsJson(false, '更新失败');
    }

    /**
     * @author 汤圆
     * @function 删除会员
     */
    public function deletOperator()
    {
        if ($this->MerchantModel->deleteEmp($_POST['no']))
            $this->rspsJson(true, '删除成功');
        else
            $this->rspsJson(false, '删除失败');
    }

    public function getPerformance()
    {
        //处理时间
        if ($_POST['op'] == -1) {
            $startTime = date('Y-m-01', strtotime(date('Y', strtotime($_POST['time'])) . '-' . (date('m', strtotime($_POST['time'])) - 1) . '-01'));
            $endTime = date('Y-m-d', strtotime("$startTime +1 month -1 day"));
        } else {
            $arr = getdate($timestamp = strtotime($_POST['time']));
            if ($arr['mon'] == 12) {
                $year = $arr['year'] + 1;
                $month = $arr['mon'] - 11;
                $startTime = $year . '-0' . $month . '-01';
                $endTime = date('Y-m-d', strtotime("$startTime +1 month -1 day"));
            } else {
                $startTime = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) + 1) . '-01'));
                $endTime = date('Y-m-d', strtotime("$startTime +1 month -1 day"));
            }
        }
        //获取数据
        if ($data = $this->OrderModel->getOpPerformance($this->merchantInfo['merchant_code'], $startTime, $endTime)) {
            $sold_total = array_sum(array_column($data, 'money'));
            foreach ($data as $key => $v) {
                $data[$key]['rate'] = empty($sold_total) ? '0' : $v['money'] / $sold_total * 100;
                $data[$key]['money'] = empty($v['money']) ? '0.00' : $v['money'];
                $data[$key]['count'] = empty($v['count']) ? '0' : $v['count'];
            }
            $this->rspsJson(true, date('Y-m', strtotime($startTime)), $data);
        } else
            $this->rspsJson(false);
    }
}