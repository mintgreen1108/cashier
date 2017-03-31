<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/14
 * Time: 8:53
 */
class InvoiceModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_invoice';
    }

    /**
     * 生成流水记录
     * @param $order_info
     * @return int
     */
    public function genInvoice($order_info)
    {
        $this->db->trans_start();
        $invoice_id = $this->insert($order_info);
        //更改订单信息
        $model = new BaseModel('tbl_ordered');
        $model->update(array('paid' => $order_info['paid'], 'pay_time' => date('Y-m-d H:i:s', time())), array('merchant_code' => $order_info['merchant_code'], 'order_no' => $order_info['order_id']));

        //更改已售商品信息
        $models = new BaseModel('tbl_goods_sold');
        $models->update(array('invoice_id' => $invoice_id), array('merchant_code' => $order_info['merchant_code'], 'order_id' => $order_info['order_id']));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * 获取流水列表
     * @param $merchant_code
     * @param $page
     * @return mixed
     */
    public function getInvoiceList($merchant_code, $page)
    {
        $invoiceList = $this->getList(array('merchant_code' => $merchant_code), '*', 'id desc', 'array', $page, 10);
        return $invoiceList;
    }

    /**
     * 获取商户下交易流水总条数
     * @param $merchant_code
     * @return mixed
     */
    public function getInvoiceCount($merchant_code)
    {
        $count = $this->getCount(array('merchant_code' => $merchant_code));
        return $count;
    }

    /**
     * 撤销交易流水
     * @param $merchant_code
     * @param $invoice_id
     * @param $order_no
     * @return mixed
     */
    public function revokeInvoice($merchant_code, $invoice_id, $order_no)
    {
        $this->db->trans_start();
        $this->update(array('reverse_flag' => 1), array('merchant_code' => $merchant_code, 'id' => $invoice_id));
        //更新已售商品列表
        $model = new BaseModel('tbl_goods_sold');
        $model->update(array('reverse_flag' => 1), array('merchant_code' => $merchant_code, 'invoice_id' => $invoice_id));
        //更新订单信息
        $models = new BaseModel('tbl_ordered');
        $models->update(array('reverse_flag' => 1), array('merchant_code' => $merchant_code, 'order_no' => $order_no));
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * 获取单条交易流水详情
     * @param $merchant_code
     * @param $invoice_id
     * @return mixed
     */
    public function getInvoiceInfo($merchant_code, $invoice_id)
    {
        $invoiceInfo = $this->getRow(array('merchant_code' => $merchant_code, 'id' => $invoice_id));
        return $invoiceInfo;
    }

    /**
     * @author 汤圆
     * @function 搜索交易流水
     * @param $merchant_code
     * @param $keyword
     * @return mixed
     */
    public function searchInvoice($merchant_code, $keyword)
    {
        $where = "merchant_code='$merchant_code' and (card_no='$keyword' or order_id='$keyword')";
        return $this->getList($where);
    }

    /**
     * @author 汤圆
     * @function 获取对账信息
     * @param $merchant_code
     * @param $start_time
     * @param $end_time
     * @return array
     */
    public function statisticInvoice($merchant_code, $start_time, $end_time,$emp_no)
    {
        //统计支付宝交易金额
        $zfb = $this->sumWhere('bank', "merchant_code='$merchant_code' and bank_type=3 and create_time>='$start_time' and create_time<='$end_time' and emp_no='$emp_no' and check_flag=0 and reverse_flag=0");
        $zfb_total = !empty($zfb['bank']) ? $zfb['bank'] : 0;
        //统计现金交易金额
        $cash =$this->sumWhere('cash', "merchant_code='$merchant_code' and create_time>='$start_time' and create_time<='$end_time' and emp_no='$emp_no' and check_flag=0 and reverse_flag=0");
        $cash_total = !empty($cash['cash']) ? $cash['cash'] : 0;
        //统计支付宝交易笔数
        $zfb_count = $this->getCount("merchant_code='$merchant_code' and bank_type=3 and create_time>='$start_time' and create_time<='$end_time' and emp_no='$emp_no' and check_flag=0 and reverse_flag=0");
        //统计现金交易笔数
        $cash_count = $this->getCount("merchant_code='$merchant_code' and cash>0 and create_time>='$start_time' and create_time<='$end_time' and emp_no='$emp_no' and check_flag=0 and reverse_flag=0");
        return compact('zfb_total', 'cash_total', 'zfb_count', 'cash_count');
    }

    /**
     * @author 汤圆
     * @function 更新已对帐记录
     * @param $merchant_code
     * @param $start_time
     * @param $end_time
     * @param $emp_no
     * @return mixed
     */
    public function updateCheckFlag($merchant_code,$start_time,$end_time,$emp_no){
        $this->db->trans_start();
        $this->update(['check_flag'=>1],"merchant_code='$merchant_code' and bank_type=3 and create_time>='$start_time' and create_time<='$end_time' and emp_no='$emp_no and check_flag=0'");
        $this->update(['check_flag'=>1],"merchant_code='$merchant_code' and create_time>='$start_time' and create_time<='$end_time' and emp_no='$emp_no' and check_flag=0");
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * @author 汤圆
     * @function 统计交易数据
     * @param $merchant_code
     * @param $start_time
     * @param $end_time
     * @return array
     */
    public function countInvoiceData($merchant_code, $start_time, $end_time){
        //统计支付宝交易金额
        $zfb_total = $this->sumWhere('bank', "merchant_code='$merchant_code' and bank_type=3 and create_time>='$start_time' and create_time<='$end_time' and reverse_flag=0");
        //统计现金交易金额
        $cash_total =$this->sumWhere('cash', "merchant_code='$merchant_code' and create_time>='$start_time' and create_time<='$end_time' and reverse_flag=0");
        return compact('zfb_total', 'cash_total');
    }
}

?>