<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/20
 * Time: 21:17
 */
class MemberModel extends BaseModel{
    public function __construct(){
        parent::__construct();
        $this->tableName='tbl_member';
    }

    /**
     * @function 获取会员信息
     * @param $merchant_code
     * @param $member_id
     * @return mixed
     */
    public function getMemeberInfo($merchant_code,$member_id){
        $model=new BaseModel($this->tableName);
        $where="merchant_code='$merchant_code' and(card_no='$member_id' or mobile='$member_id')";
        $row=$model->getRow($where);
        return $row;
    }

    /**
     * @author 汤圆
     * @function 获取会员列表
     * @param $merchant_code
     * @return mixed
     */
    public function getMemberList($merchant_code,$page=1){
        return $this->getList(['merchant_code'=>$merchant_code],'*','id desc','array',$page,10);
    }

    /**
     * @author 汤圆
     * @function 搜索会员信息
     * @param $merchant_code
     * @param $keyword
     * @return mixed
     */
    public function searchMember($merchant_code,$keyword){
        $where="merchant_code='$merchant_code' AND(card_no='$keyword' or mobile='$keyword' or member_name='$keyword')";
        return $this->getRow($where);
    }

    /**
     * @author 汤圆
     * @function 新建会员
     * @param $merchant_code
     * @param $input
     * @return int
     */
    public function addMember($merchant_code,$input){
        $this->db->trans_start();
        $this->insert(array_merge(['merchant_code' => $merchant_code],$input));
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * @author 汤圆
     * @function 删除会员
     * @param $merchant_code
     * @param $card_no
     * @return mixed
     */
    public function deleteMember($merchant_code,$card_no){
        $this->db->trans_start();
        $this->db->trans_complete();
        $where="merchant_code='$merchant_code' and card_no='$card_no'";
        $this->delete($where);
        return $this->trans_status();

    }

    /**
     * @author 汤圆
     * @function 获取商家会员总数
     * @param $merchant_code
     * @return mixed
     */
    public function getMemberNum($merchant_code){
        $where='merchant_code='.$merchant_code;
        return $this->getCount($where);
    }

    /**
     * @author 汤圆
     * @function 更新会员信息
     * @param $merchant_code
     * @param $card_no
     * @param $input
     * @return mixed
     */
    public function updateMember($merchant_code,$card_no,$input){
        $this->db->trans_start();
        $this->db->trans_complete();
        $where="merchant_code='$merchant_code' and card_no='$card_no'";
        $this->trans_status();
        return $this->update($input,$where);
    }

    /**
     * @author 汤圆
     * @function 统计每天新增会员数
     *
     * @param $merchant_code
     * @param $start_time
     * @param $end_time
     * @return mixed
     */
    public function countMemberDaily($merchant_code,$start_time,$end_time,$emp_no){
        $where="merchant_code='$merchant_code' and create_time >='$start_time' and create_time<='$end_time' and check_flag=0 and emp_no='$emp_no'";
        return $this->getCount($where);
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
        $this->update(['check_flag'=>1],"merchant_code='$merchant_code' and create_time >='$start_time' and create_time<='$end_time' and emp_no='$emp_no'");
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * @author 汤圆
     * @function 增加会员积分
     * @param $merchant_code
     * @param $card_no
     * @param $point
     * @return mixed
     */
    public function addMemberPoint($merchant_code,$card_no,$point){
        $this->db->trans_start();
        $sql="update tbl_member set point_total=point_total+'$point',consume_times=consume_times+'1' 
              WHERE merchant_code='$merchant_code'and card_no='$card_no'";
        $this->runSql($sql);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * @author 汤圆
     * @function 统计会员增加量
     * @param $merchant_code
     * @param $start_time
     * @param $end_time
     * @return mixed
     */
    public function countMemberNum($merchant_code,$start_time,$end_time){
        $where="merchant_code='$merchant_code' and create_time >='$start_time' and create_time<='$end_time'";
        return $this->getCount($where);
    }
}