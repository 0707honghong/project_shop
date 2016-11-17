<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/14 0014
 * Time: 11:04
 */

namespace Admin\Controller;


use Think\Controller;

class PermissionController extends Controller
{
    /**
     * @var \Admin\Model\PermissionModel
     */
    private $_model;
    protected function _initialize(){
        $this->_model = D('Permission');
    }

    //权限列表
    public function index(){
        $data = $this->_model->getPageResult();
        $this->assign($data);
        $this->display();
    }

    //添加权限
    public function add(){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //添加
            if ($this->_model->addPermission() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //跳转
            $this->success('添加成功',U('index'));
        }else{
            //获取已有的权限列表
            $this->_before_view();
            $this->display();
        }
    }

    //修改权限
    public function edit($id){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //修改
            if ($this->_model->savePermission() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //跳转
            $this->success('修改成功',U('index'));
        }else{
            $row = $this->_model->find($id);
            $this->assign('row',$row);
            //获取已有的权限列表
            $this->_before_view();
            $this->display('add');
        }
    }

    //删除权限
    public function remove($id){
        //删除
        if ($this->_model->removePermission($id) === false) {
            $this->error(dealErrorStr($this->_model));
        }
        $this->success('删除成功',U('index'));
    }

    //获取已有的权限列表
    protected function _before_view(){
        //获取已有的权限列表
        $permissions = $this->_model->getList();
        array_unshift($permissions,['id'=>0,'name'=>'顶级分类']);
        $this->assign('permissions',json_encode($permissions));
    }
}