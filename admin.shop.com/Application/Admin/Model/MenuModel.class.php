<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/15 0015
 * Time: 19:00
 */

namespace Admin\Model;


use Admin\Logic\MySQLORM;
use Admin\Logic\NestedSets;
use Think\Model;

class MenuModel extends Model
{
    /**
     * 获取菜单列表
     * @return mixed
     */
    public function getList(){
        return $this->order('lft')->select();
    }

    /**
     * 添加数据
     * @return bool
     */
    public function addMenu(){
        $this->startTrans();
        //创建orm
        $orm = new MySQLORM();
        //创建nestedsets
        $nestedsets = new NestedSets($orm,$this->getTableName(),'lft','rght','parent_id','id','level');
        //添加数据
        if (($menu_id = $nestedsets->insert($this->data['parent_id'],$this->data,'bottom')) === false) {
            $this->error = '添加数据失败';
            $this->rollback();
            return false;
        }

        $permission_ids = I('post.permission_id');
        if (empty($permission_ids)) {
            $this->commit();
            return true;
        }
        //添加权限关联
        $data = [];
        foreach($permission_ids as $permission_id){
            $data[] = [
                'menu_id'=>$menu_id,
                'permission_id'=>$permission_id,
            ];
        }
        if (M('MenuPermission')->addAll($data) === false) {
            $this->error='添加关联权限数据失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 修改菜单
     * @param $id
     * @return bool
     */
    public function saveMenu($id){
        //获取db中的父级菜单
        $parent_id = $this->where(['id'=>$id])->getField('parent_id');
        if ($parent_id !=$this->data['parent_id']) {
            //创建orm
            $orm = new MySQLORM();
            //创建nestedsets
            $nestedsets = new NestedSets($orm,$this->getTableName(),'lft','rght','parent_id','id','level');
            //修改数据
            if ($nestedsets->moveUnder($this->data['id'],$this->data['parent_id'],'bottom') === false) {
                $this->error='不能移动到自身或者后代上面';
                return false;
            }
        }
        //修改基本信息
        if ($this->save() === false) {
            $this->error = '修改基本信息失败';
            $this->rollback();
            return false;
        }
        $menuPermission_Model = M('MenuPermission');
        //删除旧的权限关联
        if ($menuPermission_Model->where(['menu_id'=>$id])->delete() === false) {
            $this->error='删除旧的权限关联失败';
            $this->rollback();
            return false;
        }
        $permission_ids = I('post.permission_id');
        if (empty($permission_ids)) {
            $this->commit();
            return true;
        }
        //添加权限关联
        $data = [];
        foreach($permission_ids as $permission_id){
            $data[] = [
                'menu_id'=>$id,
                'permission_id'=>$permission_id,
            ];
        }
        if ($menuPermission_Model->addAll($data) === false) {
            $this->error='添加关联权限数据失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 删除菜单
     * @param $id
     * @return bool
     */
    public function removeMenu($id){
        $this->startTrans();
        $menu_son_ids = $this->field('id')->where(['parent_id'=>$id])->find();
        //创建orm
        $orm = new MySQLORM();
        //创建nestedsets
        $nestedsets = new NestedSets($orm,$this->getTableName(),'lft','rght','parent_id','id','level');
        //删除菜单数据
        if ($nestedsets->delete($id) === false) {
            $this->rollback();
            $this->error='删除菜单失败';
            return false;
        }
        //删除关联表父级信息
        if ( M('MenuPermission')->where(['menu_id'=>$id])->delete() === false) {
            $this->error='删除关联表数据失败';
            $this->rollback();
            return false;
        }
        //删除关联表儿子信息
        if (empty($menu_son_ids)) {
            $this->commit();
            return true;
        }
        foreach($menu_son_ids as $menu_son_id){
            if (($result = M('MenuPermission')->where(['menu_id'=>$menu_son_id])->delete()) === false) {
                $this->error='删除关联表儿子数据失败';
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return true;
    }

    /**
     * 获取菜单信息
     * @param $id
     * @return mixed
     */
    public function getMenuInfo($id){
        $row = $this->find($id);
        $row['permission_ids'] = json_encode(M('MenuPermission')->where(['menu_id'=>$id])->getField('permission_id',true));
        return $row;
    }

    /**
     * 获取可见的菜单
     */
    public function getVisableMenu(){
        //获取权限列表
        $adminInfo = session('ADMIN_INFO');
        if ($adminInfo['username'] != 'admin') {
            $ids = session('ADMIN_IDS');
            return $this->distinct(true)->field('m.`id`,m.`name`,m.`parent_id`,m.`path`')->alias('m')
                ->join('shop_menu_permission as mp on m.`id` = mp.`menu_id`')
                ->where(['permission_id'=>['in',$ids]])->select();
        }else{
            return $this->distinct(true)->field('m.`id`,m.`name`,m.`parent_id`,m.`path`')->alias('m')->select();
        }

    }
}