<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/14
 * Time: 8:53
 */
class OrderModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_ordered';
    }

    /**
     * 生成订单号
     * @return string
     */
    public function genOrderNo()
    {
        $order_no = time() . rand(100, 999);
        $item = $this->getRow(array('order_no' => $order_no), 'id');
        if (!empty($item['id'])) {
            $this->genOrderNo();
        }
        return $order_no;
    }

    /**
     * 生成订单
     * @param $order
     * @param $goods_list
     * @return array|bool
     */
    public function genOrder($order, $goods_list)
    {
        $this->db->trans_start();

        //生成订单
        $order_no = $this->genOrderNo();
        $order['order_no'] = $order_no;
        $order['order_status'] = 1;
        $this->insert($order);

        //生成已售商品
        $data['order_id'] = $order_no;
        $data['merchant_id'] = $order['merchant_id'];
        $data['merchant_code'] = $order['merchant_code'];
        $data['emp_no'] = $order['emp_no'];
        $data['card_no'] = $order['card_no'];
        $data['member_id'] = empty($order['member_id']) ? '0' : $order['member_id'];

        foreach ($goods_list as $list) {
            $goods_sold = array_merge($list, $data);
            $model = new BaseModel('tbl_goods_sold');
            $model->insert($goods_sold);
        }
        $flag = $this->db->trans_complete();
        if ($flag) {
            $order['order_id'] = $order_no;
            return $order;
        } else {
            return false;
        }
    }

    /**
     * 修改订单
     * @param $order_id
     * @param $data
     * @return mixed
     */
    public function modifyOrder($merchant_code, $order, $goods_list)
    {
        $this->db->trans_start();
        $this->update($order, array('merchant_code' => $merchant_code, 'order_no' => $order['order_no']));
        //删除原有商品，生成新的商品
        $model = new BaseModel('tbl_goods_sold');
        $model->delete(array('merchant_code' => $merchant_code, 'order_id' => $order['order_no']));
        //生成已售商品
        $data['order_id'] = $order['order_no'];
        $data['merchant_id'] = $order['merchant_id'];
        $data['merchant_code'] = $order['merchant_code'];
        $data['emp_no'] = $order['emp_no'];
        $data['card_no'] = $order['card_no'];
        $data['member_id'] = empty($order['member_id']) ? '0' : $order['member_id'];

        foreach ($goods_list as $list) {
            $goods_sold = array_merge($list, $data);
            $model = new BaseModel('tbl_goods_sold');
            $model->insert($goods_sold);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            $order['order_id'] = $order['order_no'];
            return $order;
        } else {
            return false;
        }
    }

    /**
     * 删除订单
     * @param $order_id
     * @param string $merchant_code
     * @return mixed
     */
    public function delOrder($order_id, $merchant_code = '')
    {
        $this->db->trans_start();

        //删除订单
        $where = array('merchant_code' => $merchant_code, 'id' => $order_id);
        $flag = $this->delete($where);
        //删除商品
        if ($flag) {
            $model = new BaseModel('tbl_goods_sold');
            $model->delete(array('order_id' => $order_id));
        }
        $this->db->complete();
        return $this->db->trans_status();
    }

    /**
     * 取消订单
     * @param $order_id
     * @param string $merchant_code
     * @return mixed
     */
    public function cancelOrder($order_id, $merchant_code)
    {
        $this->db->trans_start();
        //更改订单状态
        $where = array('merchant_code' => $merchant_code, 'order_no' => $order_id);
        $this->update(array('order_status' => 0), $where);
        //删除该订单的已售商品
        $wheres = array('merchant_code' => $merchant_code, 'order_id' => $order_id);
        $model = new BaseModel('tbl_goods_sold');
        $model->delete($wheres);
        $this->db->trans_complete();
        return $this->db->trans_status();

    }

    /**
     * 获取商户的所有订单
     * @param $where
     * @param int $page
     * @param int $pagesize
     * @param string $fields
     * @return mixed|null
     */
    public function getMerchantOrders($where, $page = 1, $pagesize = 100, $fields = '*')
    {
        if (empty($where['merchant_code'])) return null;
        $sqlwhere = "merchant_code ={$where['merchant_code']} AND order_status={$where['order_status']}";
        if (!empty($where['cmo'])) {
            $sqlwhere .= " AND(order_no={$where['cmo']} OR card_no={$where['cmo']} OR phone={$where['cmo']})";
        }
        return $this->getList($sqlwhere, $fields, 'id desc', 'array', $page, $pagesize);
    }

    /**
     * 获取订单详情
     * @param $merchant_code
     * @param $order_no
     * @return mixed
     */
    public function getGoodsSold($merchant_code, $order_no)
    {
        $model = new BaseModel('tbl_goods_sold');
        $goods = $model->getList(array('merchant_code' => $merchant_code, 'order_id' => $order_no));
        return $goods;
    }

    /**
     * 获取订单信息
     * @param $merchant_code
     * @param $order_no
     * @return mixed
     */
    public function getOrderInfo($merchant_code, $order_no)
    {
        $order_info = $this->getRow(array('merchant_code' => $merchant_code, 'order_no' => $order_no));
        return $order_info;
    }

    /**
     * 完成订单
     * @param $merchant_code
     * @param $order_no
     */
    public function completeOrder($merchant_code, $order_no)
    {
        $this->update(array('order_status' => 9), array('merchant_code' => $merchant_code, 'order_no' => $order_no));
    }

    /**
     * @author 汤圆
     * @function 员工绩效
     * @param $merchant_code
     * @param $start_time
     * @param $end_time
     * @return mixed
     */
    public function getOpPerformance($merchant_code, $start_time, $end_time)
    {
        $sql = "SELECT a.emp_no,emp_name,b.money,b.count
                FROM tbl_merchant_operator a
                LEFT JOIN(
                SELECT c.emp_no,sum(c.total) AS money,count(*) AS count
                FROM	tbl_ordered c
                WHERE c.merchant_code = {$merchant_code} AND c.order_status = 9 AND c.reverse_flag = 0 AND c.create_time >= '{$start_time}' AND c.create_time <= '{$end_time}'
                GROUP By c.emp_no
                ORDER BY money
                ) b on a.emp_no=b.emp_no
                WHERE operate_type=0";
        return $this->getSqlResult($sql);
    }


    public function getClassSold($merchant_code, $start_time, $end_time)
    {
        $sql = "select a.class_name,b.count,b.money
              from tbl_goods_class a
              left join(
              select m.class_id,sum(n.sold_total) as count,sum(n.sold_price) as money
              from tbl_goods_sold n,tbl_goods m
              where m.id=n.goods_id and m.merchant_code={$merchant_code} and n.create_time >= '{$start_time}' and n.create_time <= '{$end_time}'
              group by m.class_id
              ) b on a.id=b.class_id";
        return $this->getSqlResult($sql);
    }

    public function getHotGoodsByMoney($merchant_code,$start_time,$end_time)
    {
        $sql="select sum(sold_price) money,goods_name
              from tbl_goods_sold
              where merchant_code={$merchant_code} and create_time >= '{$start_time}' and create_time <= '{$end_time}'
              group by goods_id
              order by money DESC 
              limit 10";
        return $this->getSqlResult($sql);
    }

    public function getHotGoodsByNum($merchant_code,$start_time,$end_time)
    {
        $sql="select sum(sold_total) count,goods_name
              from tbl_goods_sold
              where merchant_code={$merchant_code} and create_time >= '{$start_time}' and create_time <= '{$end_time}'
              group by goods_id
              order by count DESC 
              limit 10";
        return $this->getSqlResult($sql);
    }
}

?>