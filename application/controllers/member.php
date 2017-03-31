<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/11/12
 * Time: 17:15
 */
class member extends BaseController
{

    protected $merchantInfo;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->model('MemberModel');
        $this->load->model('MerchantModel');
        $this->load->model('MemberModel');
        if (empty($_COOKIE['merInfo_0'])) {
            redirect('login/index');
            die();
        }
        $this->merchantInfo = json_decode($_COOKIE['merInfo_0'], true);
    }

    public function index()
    {
        $merchant_code = $this->merchantInfo['merchant_code'];
        $base['merchant_code'] = $this->merchantInfo['merchant_code'];
        $base['photo_name'] = $this->merchantInfo['simple_word'];
        $base['member_list'] = $this->MemberModel->getMemberList($merchant_code);
        $base['member_list'] = $this->transInfo($base['member_list']);
        $base['member_page'] = ceil($this->MemberModel->getMemberNum($merchant_code) / 10);
        $data = array(
            array('template' => 'naviga_bar', 'data' => $base),
            array('template' => 'member', 'data' => $base),
        );
        $this->loadBlockView($data);
    }

    /**
     * @author 汤圆
     * @function 搜索会员信息
     */
    public function searchMember()
    {
        $merchant_code = $this->merchantInfo['merchant_code'];
        $memberInfo = $this->MemberModel->searchMember($merchant_code, $_POST['keyword']);
        $memberInfo = $this->transInfo($memberInfo);
        if (!empty($memberInfo)) {
            $this->rspsJson(true, '', $memberInfo);
        } else {
            $this->rspsJson(false, '');
        }
    }

    /**
     * @author 汤圆
     * @function 新建会员
     */
    public function addMember()
    {
        $merchant_code = $this->merchantInfo['merchant_code'];
        if ($this->MemberModel->getMemeberInfo($merchant_code, $_POST['card_no'])) $this->rspsJson(false, '卡号已存在，请重新输入');
        if ($this->MemberModel->getMemeberInfo($merchant_code, $_POST['mobile'])) $this->rspsJson(false, '手机号已存在，请重新输入');
        $data = [
            'merchant_id' => $this->merchantInfo['merchant_id'],
            'card_no' => $_POST['card_no'],
            'member_name' => $_POST['member_name'],
            'mobile' => $_POST['mobile'],
            'gender' => $_POST['gender'],
            'birthday' => $_POST['birthday'],
            'emp_no' => $this->merchantInfo['emp_no']
        ];
        $flag = $this->MemberModel->addMember($this->merchantInfo['merchant_code'], $data);
        if ($flag) {
            $this->rspsJson(true);
        } else {
            $this->rspsJson(false);
        }
    }

    /**
     * @author 汤圆
     * @function 删除会员
     */
    public function deleteMember()
    {
        $flag = $this->MemberModel->deleteMember($this->merchantInfo['merchant_code'], $_POST['card_no']);
        if ($flag) {
            $this->rspsJson(true);
        } else {
            $this->rspsJson(false);
        }
    }

    /**
     * @author 汤圆
     * @function 分页获取会员列表
     */
    public function getPageMemberList()
    {
        $data = $this->MemberModel->getMemberList($this->merchantInfo['merchant_code'], $_POST['page']);
        $data = $this->transInfo($data);
        if (!empty($data)) {
            $this->rspsJson(true, '', $data);
        } else {
            $this->rspsJson(false, '信息获取失败，请重试');
        }
    }

    public function updateMember(){
        $data = [
            'member_name' => $_POST['member_name'],
            'mobile' => $_POST['mobile'],
            'gender' => $_POST['gender'],
            'birthday' => $_POST['birthday'],
        ];
        $flag=$this->MemberModel->updateMember($this->merchantInfo['merchant_code'],$_POST['card_no'],$data);
        if($flag) $this->rspsJson(true,'');
        else $this->rspsJson(false,'会员更新失败，请重试');
    }

    /**
     * @author 汤圆
     * @function 转换会员名
     * @param $data
     * @return mixed
     */
    public function transInfo($data)
    {
        if (count($data) != count($data, 1)) {
            foreach ($data as $key => $value) {
                $data[$key]['emp_name'] = $this->MerchantModel->getEmpName($value['merchant_code'], $value['emp_no']);
            }
        } else {
            $data['emp_name'] = $this->MerchantModel->getEmpName($data['merchant_code'], $data['emp_no']);
        }

        return $data;
    }

}

?>