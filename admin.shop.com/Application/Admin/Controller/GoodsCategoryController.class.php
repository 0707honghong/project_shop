<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/8 0008
 * Time: 16:32
 */

namespace Admin\Controller;


use Think\Controller;

class GoodsCategoryController extends Controller
{
    /**
     * @var \Admin\Model\GoodsCategoryModel
     */
    private $_model;
    public function _initialize(){
        $this->_model = D('GoodsCategory');
    }

    /**
     * 获取分类
     */
    public function index(){
        $rows = $this->_model->getList();
        $this->assign('rows',$rows);
        $this->display();
    }

    /**
     * 添加数据
     */
    public function add(){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //添加数据
            if($this->_model->addCategory() === false){
                $this->error($this->_model->getError());
            }
            //跳转
            $this->success('添加成功',U('index'));
        }else{
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 修改数据
     */
    public function edit($id){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //修改数据
            if($this->_model->saveCategory() === false){
                $this->error($this->_model->getError());
            }
            //跳转
            $this->success('修改成功',U('index'));
        }else{
            $row = $this->_model->find($id);
            $this->_before_view();
            $this->assign('row',$row);
            $this->display('add');
        }
    }

    /**
     * 移除数据
     * 移除分类及其子分类
     */
    public function remove($id){
        //删除数据
        if ($this->_model->deleteCategory($id) === false) {
            $this->error($this->_model->getError());
        }
        $this->success('移除成功',U('index'));
    }

    /**
     * 获取分类列表，传递json字符串，以便ztree使用
     */
    private function _before_view(){
        //获取分类列表
        $rows = $this->_model->getList();
        array_unshift($rows,['id'=>'0','name'=>'顶级分类']);
        $categories = json_encode($rows);
        $this->assign('categories',$categories);
    }
}