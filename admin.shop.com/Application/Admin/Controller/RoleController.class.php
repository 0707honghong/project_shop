<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/14 0014
 * Time: 10:12
 */

namespace Admin\Controller;


use Think\Controller;

class RoleController extends Controller
{
    /**
     * @var \Admin\Model\RoleModel
     */
    private $_model;
    protected function _initialize(){
        $this->_model = D('Role');
    }

    /**
     * 角色列表
     */
    public function index(){
        //获取搜索条件
        $keyword = I('get.keyword');
        $cond = [];
        if ($keyword) {
            $cond['name'] = ['like','%'.$keyword.'%'];
        }
        //查询数据
        $data = $this->_model->getPageResult($cond);
        $this->assign($data);
        //传递数据
        $this->display();
    }

    /**
     * 添加角色
     */
    public function add(){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //添加数据
            if ($this->_model->addRole() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //跳转
            $this->success('添加成功',U('index'));
        }else{
            //获取权限列表
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 修改角色
     */
    public function edit($id){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //修改数据
            if ($this->_model->saveRole($id) === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //跳转
            $this->success('修改成功',U('index'));
        }else{
            //获取信息
            $row = $this->_model->getRoleInfo($id);
            $this->assign('row',$row);
            $this->_before_view();
            $this->display('add');
        }

    }

    /**
     * 删除角色
     */
    public function remove($id){
        if ($this->_model->removeRole($id) === false) {
            $this->error(dealErrorStr($this->_model));
        }
        $this->success('删除成功',U('index'));
    }

    /**
     * 获取权限列表
     */
    protected function _before_view(){
        //获取权限列表
        $permissions = D('Permission')->getList();
        $this->assign('permissions',json_encode($permissions));
    }
}