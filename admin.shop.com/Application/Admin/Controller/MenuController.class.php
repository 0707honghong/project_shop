<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/15 0015
 * Time: 18:59
 */

namespace Admin\Controller;


use Think\Controller;

class MenuController extends Controller
{
    /**
     * @var \Admin\Model\MenuModel
     */
    private $_model = null;
    protected function _initialize(){
        $this->_model = D('Menu');
    }

    /**
     * 菜单列表
     */
    public function index(){
        $rows = $this->_model->getList();
        $this->assign('rows',$rows);
        $this->display();
    }

    /**
     * 添加菜单
     */
    public function add(){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //添加数据
            if ($this->_model->addMenu() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //跳转
            $this->success('添加成功',U('index'));
        }else{
            //获取已有的菜单列表，以便设置父级
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 修改菜单
     */
    public function edit($id){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //修改
            if ($this->_model->saveMenu($id) === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //跳转
            $this->success('修改成功',U('index'));
        }else{
            //获取已有的菜单列表，以便设置父级
            $this->_before_view();
            $row = $this->_model->getMenuInfo($id);
            $this->assign('row',$row);
            $this->display('add');
        }
    }

    /**
     * 删除菜单
     */
    public function remove($id){
        if ($this->_model->removeMenu($id) === false) {
            $this->error(dealErrorStr());
        }
        $this->success('删除成功',U('index'));
    }

    /**
     *获取已有的菜单列表，以便设置父级
     */
    protected function _before_view(){
//        获取已有的菜单列表，以便设置父级
        $rows = $this->_model->getList();
        array_unshift($rows,['id'=>0,'name'=>'顶级分类']);
        $this->assign('menus',json_encode($rows));
        //获取权限列表
        $permissions = D('Permission')->getList();
        $this->assign('permissions',json_encode($permissions));
    }
}