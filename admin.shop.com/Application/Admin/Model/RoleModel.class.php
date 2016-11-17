<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/14 0014
 * Time: 10:16
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class RoleModel extends Model
{
    /**
     * 获取分页数据
     * @param array $cond
     * @return array
     */
    public function getPageResult(array $cond = []){
        //获取分页工具条
        $count = $this->where($cond)->count();
        $page = new Page($count,C('PAGE.SIZE'));
        $page->setConfig('theme',C('PAGE.THEME'));
        $page_html = $page->show();
        //获取角色数据
        $rows = $this->where($cond)->page(I('get.p'),C('PAGE.SIZE'))->order('sort')->select();
        $data = [
            'page_html'=>$page_html,
            'rows'=>$rows,
        ];
        return $data;
    }

    /**
     * 添加角色
     * @return bool
     */
    public function addRole(){
        $this->startTrans();
        //添加基本信息
        if (($id = $this->add()) === false) {
            $this->rollback();
            return false;
        }
        $permissions = I('post.permission_id');
        if (empty($permissions)) {
            $this->commit();
            return true;
        }
        $data = [];
        foreach($permissions as $permission){
            $data[] = [
                'role_id'=>$id,
                'permission_id'=>$permission,
            ];
        }
        //添加角色权限关联信息
        if (M('RolePermission')->addAll($data) === false) {
            $this->error = '添加失败';
            $this->rollback();
            return false;
        }
        //添加角色权限关联信息
        $this->commit();
        return true;
    }

    /**
     * 获取编辑数据
     * @param $id
     * @return mixed
     */
    public function getRoleInfo($id){
        //获取角色信息
        $row = $this->find($id);
        //因为permission_ids要在js中用，所以必须要转成json字符串
        $row['permission_ids'] = json_encode(M('RolePermission')->where(['role_id'=>$id])->getField('permission_id',true));
        return $row;
    }

    /**
     * 修改角色信息
     * @param $id
     * @return bool
     */
    public function saveRole($id){
        $this->startTrans();
        //修改基本信息
        if ($this->save() === false) {
            $this->rollback();
            return false;
        }
        $rolePermission = M('RolePermission');
        //删除原有的角色权限关联
        if ($rolePermission->where(['role_id'=>$id])->delete() === false) {
            $this->error='删除旧关联失败';
            return false;
        }
        //添加新的角色权限关联
        $permission_ids = I('post.permission_id');
        if (empty($permission_ids)) {
            $this->commit();
            return true;
        }
        $data = [];
        foreach($permission_ids as $permission_id){
            $data[] = [
                'role_id'=>$id,
                'permission_id'=>$permission_id,
            ];
        }
        if ($rolePermission->addAll($data) === false) {
            $this->error='添加新的角色关联失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 删除角色
     * @param $id
     * @return bool
     */
    public function removeRole($id){
        $this->startTrans();
        if ($this->delete($id) === false) {
            $this->rollback();
            return false;
        }
        //删除角色-权限关联
        if (M('RolePermission')->where(['role_id'=>$id])->delete() === false) {
            $this->rollback();
            return false;
        }
        //删除管理员-角色关联
        if (M('AdminRole')->where(['role_id'=>$id])->delete() === false) {
            $this->error='删除失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 获取角色列表
     * @return mixed
     */
    public function getList(){
        return $this->order('sort')->select();
    }
}