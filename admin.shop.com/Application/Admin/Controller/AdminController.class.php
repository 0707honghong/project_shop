<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/10 0010
 * Time: 16:53
 */

namespace Admin\Controller;


use Think\Controller;
use Think\Verify;

class AdminController extends Controller
{
    /**
     * @var \Admin\Model\AdminModel
     */
    private $_model;
    public function _initialize(){
        $this->_model = D('Admin');
    }

    public function login(){
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            if ($this->_model->login() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            $this->success('登陆成功',U('Index/index'));
        }else{
            $this->display();
        }
    }

    /**
     * 添加用户
     *  密码：加盐加密
     *  注册时间：添加时的时间
     *  最后登录时间：要更新last_login_time字段
     *  最后登录ip:要更新last_login_ip字段
     */
    public function add(){
        if (IS_POST) {
            if ($this->_model->create('','reg') === false) {
                $this->error('错误提醒'.dealErrorStr($this->_model));
            }
            if ($this->_model->addAdmin() === false) {
                $this->error($this->_model->getError());
            }
            $this->success('添加成功',U('login'));
        }else{
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 显示管理员列表
     */
    public function index(){
        $keyword = I('get.keyword');
        $cond = [];
        if ($keyword) {
            $cond['username'] = [
                'like','%'.$keyword.'%',
            ];
        }
        $rows = $this->_model->getPageResult($cond);
        $this->assign($rows);
        $this->display();
    }

    /**
     * 修改管理员信息
     * @param $id
     */
    public function edit($id){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //修改数据
            if ($this->_model->saveAdmin($id) === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //跳转
            $this->success('修改成功',U('index'));
        }else{
            $row = $this->_model->getOne($id);
            $this->assign('row',$row);
            $this->_before_view();
            $this->display('add');
        }
    }

    /**
     * 删除管理员信息
     * @param $id
     */
    public function remove($id){
        if ($this->_model->delAdmin($id) === false) {
            $this->error($this->getError());
        }
        $this->success('删除成功',U('index'));
    }

    /**
     * 退出
     */
    public function logout(){
        session(null);
        cookie('ADMIN_LOGIN_INFO',null);
        $this->success('退出成功',U('login'));
    }

    /**
     * 获取角色列表
     */
    private function _before_view(){
        $role_ids = D('Role')->getList();
        $this->assign('role_ids',json_encode($role_ids));
    }
}