<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/9 0009
 * Time: 10:58
 */

namespace Admin\Controller;


use Think\Controller;

class GoodsController extends Controller
{
    /**
     * @var \Admin\Model\GoodsModel
     */
    private $_model;
    public function _initialize(){
        $this->_model = D('Goods');
    }
    /**
     * 查询商品列表
     */
    public function index(){
        $cond = [];
        $keyword = I('get.keyword');
        $goods_category_id = I('get.goods_category_id');
        $brand_id = I('get.brand_id');
        if ($keyword) {
            $cond['name'] = ['like','%'.$keyword.'%'];
        }
        if ($goods_category_id) {
            $cond['goods_category_id'] = ['eq',$goods_category_id];
        }
        if ($brand_id) {
            $cond['brand_id'] = ['eq',$brand_id];
        }
        $rows = $this->_model->getPageResult($cond);
        $this->assign($rows);
        //获取商品分类列表
        $goodsCategory = D('GoodsCategory')->getList('id,name,parent_id');
        $this->assign('goodsCategory',$goodsCategory);
        //获取品牌列表
        $brand= D('Brand')->getList('id,name');
        $this->assign('brand',$brand);
        $this->display();
    }

    /**
     * 添加商品信息
     */
    public function add(){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //添加数据
            if ($this->_model->addGoods() === false) {
                $this->error($this->_model->getError());
            }
            //跳转
            $this->success('添加成功',U('index'));
        }else{
            $this->_befor_view();
            $this->display();
        }
    }

    /**
     * @param $id
     * 修改商品信息
     */
    public function edit($id){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //修改数据
            if ($this->_model->saveGoods($id) === false) {
                $this->error($this->_model->getError());
            }
            //跳转
            $this->success('修改成功',U('index'));
        }else{
            $this->_befor_view();
            //获取商品信息
            $this->_goods_info($id);
            $this->display('add');
        }
    }

    public function view($id){
        $this->_befor_view();
        //获取商品信息
        $this->_goods_info($id);
        $this->display();
    }

    /**
     * @param $id
     * 删除商品信息
     */
    public function remove($id){
        if (!$this->_model->where(['id'=>$id])->setField('status',0)) {
            $this->error($this->_model->getError());
        }else{
            $this->success('删除成功',U('index'));
        }
    }

    /**
     * 准备分类,为了让goodsCategory使用ztree，所以传json_encode
     */
    private function _befor_view(){
        //获取商品分类列表
        $goodsCategory = D('GoodsCategory')->getList('id,name,parent_id');
        $this->assign('goodsCategory',json_encode($goodsCategory));
        //获取品牌列表
        $brand= D('Brand')->getList('id,name');
        $this->assign('brand',$brand);
        //获取供货商列表
        $supplier = D('Supplier')->getList('id,name');
        $this->assign('supplier',$supplier);
    }

    protected function _goods_info($id){
        $goods_id = $id;
        //获取商品内容信息
        $intro = M('GoodsIntro')->find($goods_id);
        //获取商品基本信息
        $info = D('Goods')->getGoodsInfo($id);
        $info['inputtime'] = date('Y-m-d H:i:s',$info['inputtime']);
        //获取商品相册列表
        $gallery = M('GoodsGallery')->where(['goods_id'=>$id])->select();
        $data =[
            'info'=>$info,
            'intro'=>$intro
        ];
        $this->assign('gallery',$gallery);
        $this->assign($data);
    }
}