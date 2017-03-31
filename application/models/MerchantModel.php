<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/14
 * Time: 8:52
 */
class MerchantModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_merchant';
    }

    /**
     * 获取商户信息
     * @param $enToken 登陆令牌
     * @return mixed
     */
    public function getMerchantInfor($enToken)
    {
        $model = new BaseModel('tbl_merchant');
        $row = $model->getRow(array('token' => $enToken));
        return $row;
    }

    /**
     * 获取操作员信息
     * @param $merchant_code
     * @param $emp_no
     * @return mixed
     */
    public function getMerchantOp($merchant_code, $emp_no)
    {
        $model = new BaseModel('tbl_merchant_operator');
        $row = $model->getRow(array('merchant_code' => $merchant_code, 'emp_no' => $emp_no));
        return $row;
    }

    /**
     * 获取商户名
     * @param $merchant_code
     * @return mixed
     */
    public function getMerchantName($merchant_code)
    {
        $merchant = $this->getRow(array('merchant_code' => $merchant_code));
        return $merchant['merchant_name'];
    }

    /**
     * 获取操作员姓名
     * @param $merchant_code
     * @param $emp_no
     * @return mixed
     */
    public function getEmpName($merchant_code, $emp_no)
    {
        $model = new BaseModel('tbl_merchant_operator');
        $emp = $model->getRow(array('merchant_code' => $merchant_code, 'emp_no' => $emp_no));
        return $emp['emp_name'];
    }

    /**
     * @function 获取商家配置信息
     * @param $merchant_code
     * @return mixed
     */
    public function getMerchantConfig($merchant_code)
    {
        $model = new BaseModel('tbl_merchant_config');
        return $model->getRow(['merchant_code' => $merchant_code]);
    }

    /**
     * @function 更改操作员基本信息
     * @param $emp_no
     * @param $data
     * @return mixed
     */
    public function modifyEmpInfo($emp_no, $data)
    {
        $model = new BaseModel('tbl_merchant_operator');
        return $model->update($data, ['emp_no' => $emp_no]);
    }

    /**
     * @function 获取操作员列表
     * @param $merchant_code
     * @return mixed
     */
    public function getEmpList($merchant_code)
    {
        $model = new BaseModel('tbl_merchant_operator');
        return $model->getList(['merchant_code' => $merchant_code, 'operate_type' => 0]);
    }

    /**
     * @function 注册操作员
     * @param array $data
     * @return int
     */
    public function addEmp(array $data)
    {
        $model = new BaseModel('tbl_merchant_operator');
        return $model->insert($data);
    }

    /**
     * @author 汤圆
     * @function
     * @param array $data
     * @return mixed
     */
    public function updateEmp($emp_no, array $data)
    {
        $model = new BaseModel('tbl_merchant_operator');
        return $model->update($data, ['emp_no' => $emp_no]);
    }

    public function deleteEmp($emp_no)
    {
        $model = new BaseModel('tbl_merchant_operator');
        return $model->delete(['emp_no' => $emp_no]);
    }

    public function getConfig($merchant_code)
    {
        $model = new BaseModel('tbl_merchant_config');
        return $model->getRow(['merchant_code' => $merchant_code]);
    }

    public function updateConfig($merchant_code, $data)
    {
        $model = new BaseModel('tbl_merchant_config');
        return $model->update($data, ['merchant_code' => $merchant_code]);
    }
}

?>