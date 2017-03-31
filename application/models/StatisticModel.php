<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/12/30
 * Time: 9:28
 */
class StatisticModel extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_merchant_statistic_daily';
    }

    /**
     * @author 汤圆
     * @function 统计每日信息
     * @param $data
     * @return mixed
     */
    public function insertStatisticInfo($data)
    {
        $this->db->trans_start();
        $this->insert($data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * @author 汤圆
     * @function 更新每日信息
     * @param $merchant_code
     * @param $statistic_day
     * @param $data
     * @return mixed
     */
    public function updateStatisticInfo($merchant_code, $statistic_day, $data)
    {
        $this->db->trans_start();
        $member_add = $data['member_add_count'];
        $zfb_total = $data['zfb_sum'];
        $zfb_count = $data['zfb_count'];
        $cash_count = $data['cash_count'];
        $cash_total = $data['cash_sum'];
        $remark = $data['remark'];
        $sql = "update tbl_merchant_statistic_daily 
              set member_add_count=member_add_count+'$member_add',zfb_sum=zfb_sum+'$zfb_total',zfb_count=zfb_count+'$zfb_count'
              ,cash_sum=cash_sum+'$cash_total',cash_count=cash_count+'$cash_count',remark=CONCAT(remark,'$remark')
              WHERE merchant_code='$merchant_code' and statistic_day='$statistic_day'";
        $this->runSql($sql);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * @author 汤圆
     * @function 验证是否已统计
     * @param $merchant_code
     * @param $statistic_day
     * @return mixed
     */
    public function checkStatisticInfoExit($merchant_code, $statistic_day)
    {
        return $this->getRow(['merchant_code' => $merchant_code, 'statistic_day' => $statistic_day]);
    }

    /**
     * @author 汤圆
     * @function 统计时间段内数据
     * @param $merchant_code
     * @param $start_time
     * @param $end_time
     * @return mixed
     */
    public function getMerchantStatisticSum($merchant_code, $start_time, $end_time)
    {
        $filed = "SUM(member_add_count) AS member_count,SUM(zfb_sum) AS zfb_total,SUM(cash_sum) AS cash_total";
        $where = "merchant_code='$merchant_code' AND statistic_day>='$start_time' AND statistic_day<='$end_time'";
        return $this->getRow($where, $filed);
    }

    /**
     * @author 汤圆
     * @function 获取日期列表数据
     * @param $merchant_code
     * @param $dateList
     * @return mixed
     */
    public function getStatisticList($merchant_code, $dateList)
    {
        $filed = "member_add_count,zfb_sum,cash_sum,statistic_day";
        $where = "merchant_code='$merchant_code'";
        return $this->whereIn($where, $filed, 'statistic_day', $dateList);
    }

    /**
     * @author 汤圆
     * @function 获取统计数据
     * @param $merchant_code
     * @return mixed
     */
    public function getStatsList($merchant_code, $page = 1)
    {
        $model = new BaseModel('tbl_merchant_statistic_daily');
        return $model->getList(['merchant_code' => $merchant_code],'*','id desc','array',$page);
    }
}