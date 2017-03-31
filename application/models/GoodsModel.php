<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/11/10
 * Time: 19:58
 */
class GoodsModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_goods';
    }

    /**
     * 获取商户的全部商品分类
     * @param $merchant_code
     * @return mixed
     */
    public function getGoodsClass($merchant_code)
    {
        $model = new BaseModel('tbl_goods_class');
        $list = $model->getList(array('merchant_code' => $merchant_code), 'id,class_name');
        return $list;
    }

    /**
     * 获取商户某分类下的全部商品
     * @param $classId
     * @param $merchant_code
     * @return mixed
     */
    public function getProducts($classId, $merchant_code,$page=1)
    {
        $products = $this->getList(array('class_id' => $classId, 'merchant_code' => $merchant_code), '*', 'id asc', 'array', $page);
        return $products;
    }

    /**
     * 获取搜索的商品列表
     * @param $merchant_code
     * @param $key
     * @return mixed
     */
    public function queryProducts($merchant_code, $key)
    {
        $where = "merchant_code=$merchant_code and (goods_name like '%$key%' or simple_word like '%$key%' or goods_code like '%$key%')";
        $products = $this->getList($where, 'id,goods_name,price', 'id asc');
        return $products;
    }

    /**
     * 获取商品详情
     * @param $merchant_code
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsInfo($merchant_code, $goods_id)
    {
        $goodsInfo = $this->getRow(array('merchant_code' => $merchant_code, 'id' => $goods_id));
        return $goodsInfo;
    }

    /**
     * 获取商户下所有的已售商品
     * @param $merchant_code
     * @param $page
     * @return mixed
     */
    public function getGoodsSoldList($merchant_code, $page)
    {
        $model = new BaseModel('tbl_goods_sold');
        $goodsList = $model->getList(array('merchant_code' => $merchant_code), '*', 'id desc', 'array', $page, 10);
        return $goodsList;
    }

    /**
     * 获取商户下已售商品的总数量
     * @param $merchant_code
     * @return mixed
     */
    public function getGoodsSoldCount($merchant_code)
    {
        $model = new BaseModel('tbl_goods_sold');
        $count = $model->getCount(array('merchant_code' => $merchant_code));
        return $count;
    }

    /**
     * @author 汤圆
     * @function 搜索已售商品
     * @param $merchant_code
     * @param $keyword
     * @return mixed
     */
    public function searchGoodsSold($merchant_code, $keyword)
    {
        $model = new BaseModel('tbl_goods_sold');
        $where = "merchant_code=$merchant_code and (goods_name like '%$keyword%' or order_id='$keyword')";
        return $model->getList($where);
    }

    /**
     * @author 汤圆
     * @function 获取分类下商品个数
     * @param $goods_id
     * @param $merchant_code
     * @return mixed
     */
    public function getGoodsNumByClass($goods_id, $merchant_code)
    {
        $model = new BaseModel('tbl_goods');
        return $model->getCount(['merchant_code' => $merchant_code, 'class_id' => $goods_id]);
    }

    /**
     * @author 汤圆
     * @function 添加分类
     * @param $data
     * @return int
     */
    public function addClass($data)
    {
        $model = new BaseModel('tbl_goods_class');
        return $model->insert($data);
    }

    /**
     * @author 汤圆
     * @function 更新分类名称
     * @param $classId
     * @param $className
     * @return mixed
     */
    public function updateClass($classId, $className)
    {
        $model = new BaseModel('tbl_goods_class');
        return $model->update(['class_name' => $className], ['id' => $classId]);
    }

    /**
     * @author 汤圆
     * @function 删除分类
     * @param $classId
     * @return mixed
     */
    public function deletClass($classId)
    {
        $model = new BaseModel('tbl_goods_class');
        return $model->delete(['id' => $classId]);
    }

    /**
     * @author 汤圆
     * @function 更新商品信息
     * @param $goods_id
     * @param $data
     * @return mixed
     */
    public function updateGoods($goods_id, $data)
    {
        return $this->update($data, ['id' => $goods_id]);
    }

    /**
     * @author 汤圆
     * @function 添加商品
     * @param $data
     * @return int
     */
    public function insertGoods($data)
    {
        return $this->insert($data);
    }
}

?>