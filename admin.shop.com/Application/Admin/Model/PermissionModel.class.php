<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/14 0014
 * Time: 11:06
 */

namespace Admin\Model;


use Admin\Logic\MySQLORM;
use Admin\Logic\NestedSets;
use Think\Model;
use Think\Page;

class PermissionModel extends Model
{
    /**
     * 批量验证
     * @var bool
     */
    protected $patchValidate = true;
    protected $_validate = array(
        array('name','require','权限名称不能为空'),
        array('parent_id','require','父级权限不能为空'),
    );

    /**
     * 获取权限列表
     * @return mixed
     */
    public function getPageResult(){
        $count = $this->count();
        $page = new Page($count,C('PAGE.SIZE'));
        $page->setConfig('theme',C('PAGE.THEME'));
        $page_html = $page->show();
        $rows = $this->page(I('get.p'),C('PAGE.SIZE'))->order('lft')->select();
        $data = [
            'page_html'=>$page_html,
            'rows'=>$rows,
        ];
        return $data;
    }

    /**
     * 获取已有的权限
     * @return mixed
     */
    public function getList(){
        return $this->order('lft')->select();
    }

    /**
     * 添加权限
     * @return bool
     */
    public function addPermission(){
        $orm = new MySQLORM();
        $nestedSets = new NestedSets($orm,$this->getTableName(),'lft','rght','parent_id','id','level');
        if ($nestedSets->insert($this->data['parent_id'],$this->data,'bottom') === false) {
            $this->error='添加失败';
            return false;
        }
        return true;
    }

    /**
     * 修改权限
     * @return bool
     */
    public function savePermission(){
        //修改左右节点和层级
        //判断是否需要修改左右节点和层级
        //获取db中的父级分类
        $parent_id = $this->where(['id'=>$this->data['id']])->getField('parent_id');
        if ($parent_id !=$this->data['parent_id']) {
            //使用nestedSets完成左右节点和层级的计算
            $orm = new MySQLORM();
            $nestedSets = new NestedSets($orm,$this->getTableName(),'lft','rght','parent_id','id','level');
            if ($nestedSets->moveUnder($this->data['id'],$this->data['parent_id'],'bottom') === false) {
                $this->error = '不能移动到自己或后代分类中';
                return false;
            }
        }
        //修改信息
        return $this->save();
    }

    public function removePermission($id){
        $this->startTrans();
        $orm = new MySQLORM();
        $nestedSets = new NestedSets($orm,$this->getTableName(),'lft','rght','parent_id','id','level');
        if ($nestedSets->delete($id) === false) {
            $this->rollback();
            $this->error = '删除失败';
            return false;
        }
//        删除关联
        if (M('RolePermission')->where(['permission_id'=>$id])->delete() === false) {
            $this->rollback();
            $this->error = '删除失败';
            return false;
        }
        $this->commit();
        return true;
    }
}