<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2017/1/4
 * Time: 17:32
 */
class goods extends BaseController
{

    protected $merchantInfo = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('GoodsModel');
        $this->load->helper(array('form', 'url'));
        if (empty($_COOKIE['merInfo_1'])) {
            redirect('login/index');
            die();
        }
        $this->merchantInfo = json_decode($_COOKIE['merInfo_1'], true);
    }

    /**
     * @author 汤圆
     * @function 商品管理界面
     */
    public function index()
    {
        //商品分类
        $classList = $this->GoodsModel->getGoodsClass($merchant_code = $this->merchantInfo['merchant_code']);
        //分类下商品个数
        $classList = $this->addGoodsNum($classList);

        $this->loadAdminView('admin/goods', ['class' => $classList]);
    }

    /**
     * @author 汤圆
     * @function 添加分类下商品数目
     * @param $classList
     * @return mixed
     */
    public function addGoodsNum($classList)
    {
        foreach ($classList as $key => $value) {
            $classList[$key]['goodsNum'] = $this->GoodsModel->getGoodsNumByClass($value['id'], $this->merchantInfo['merchant_code']);
        }
        return $classList;
    }

    /**
     * @author 汤圆
     * @function 添加商品分类
     */
    public function addGoodsClass()
    {
        $data = [
            'merchant_id' => $this->merchantInfo['merchant_id'],
            'merchant_code' => $this->merchantInfo['merchant_code'],
            'class_name' => $_POST['class_name']
        ];
        if ($this->GoodsModel->addClass($data)) {
            $this->rspsJson(true);
        } else {
            $this->rspsJson(false);
        }
    }

    /**
     * @author 汤圆
     * @function 更新分类名
     */
    public function updateGoodsClass()
    {
        if ($this->GoodsModel->updateClass($_POST['id'], $_POST['class_name'])) {
            $this->rspsJson(true);
        } else {
            $this->rspsJson(false);
        }
    }

    /**
     * @author 汤圆
     * @function 删除商品分类
     */
    public function deletGoodsClass()
    {
        if ($this->GoodsModel->deletClass($_POST['id'])) {
            $this->rspsJson(true);
        } else {
            $this->rspsJson(false);
        }
    }

    /**
     * @author 汤圆
     * @function 获取分类下商品列表
     */
    public function classDetail()
    {
        $data = $this->GoodsModel->getProducts($_POST['class_id'], $merchant_code = $this->merchantInfo['merchant_code'], $_POST['page']);
        $class = array_column($this->GoodsModel->getGoodsClass($merchant_code), null, 'id');
        foreach ($data as $key => $value) {
            $array = $class[$value['class_id']];
            $data[$key]['class_name'] = $array['class_name'];
        }
        $this->rspsJson(true, '', $data);
    }

    /**
     * @author 汤圆
     * @function 上传图片
     * @return bool
     * @throws Exception
     */
    public function uploadImage()
    {
        $config['upload_path'] = 'assets/img/goods/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '100';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image')) throw new Exception($this->upload->display_errors());
        $data['upload_data'] = $this->upload->data();  //文件的一些信息
        $img = $data['upload_data']['file_name'];  //取得文件名
        return true;
    }

    /**
     * @author 汤圆
     * @function 更新
     */
    public function updateGoodsInfo()
    {
        $data = [
            'merchant_id' => $this->merchantInfo['merchant_id'],
            'merchant_code' => $this->merchantInfo['merchant_code'],
            'goods_name' => $_POST['goods_name'],
            'simple_word' => $_POST['simple_word'],
            'full_word' => $_POST['full_word'],
            'goods_code' => $_POST['goods_code'],
            'class_id' => $_POST['class_id'],
            'price' => $_POST['price'],
            'discount' => $_POST['discount']
        ];
        if (!empty($_FILES['image'])) {
            $data['img_path'] = $_FILES['image']['name'];
            $this->uploadImage();
        }

        if ($this->GoodsModel->updateGoods($_POST['goods_id'], $data))
            $this->rspsJson(true);
        else
            $this->rspsJson(false);
    }

    /**
     * @author 汤圆
     * @function 添加商品
     */
    public function addGoods()
    {
        try {
            $data = [
                'merchant_id' => $this->merchantInfo['merchant_id'],
                'merchant_code' => $this->merchantInfo['merchant_code'],
                'goods_name' => $_POST['goods_name'],
                'simple_word' => $_POST['simple_word'],
                'full_word' => $_POST['full_word'],
                'goods_code' => $_POST['goods_code'],
                'img_path' => $_FILES['image']['name'],
                'class_id' => $_POST['class_id'],
                'price' => $_POST['price'],
                'discount' => $_POST['discount']
            ];
            $this->uploadImage();
            if ($this->GoodsModel->insertGoods($data))
                $this->rspsJson(true);
            else
                $this->rspsJson(false);
        } catch (Exception $e) {
            $this->rspsJson(false, $e->getMessage());
        }
    }
}