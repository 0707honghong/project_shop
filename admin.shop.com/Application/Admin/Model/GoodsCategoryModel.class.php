<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/8 0008
 * Time: 16:50
 */

namespace Admin\Model;


use Admin\Logic\NestedSets;
use Think\Model;

class GoodsCategoryModel extends Model
{
    /**
     * @return mixed
     * 获取分类列表 array
     */
    public function getList($id,$name,$parent_id){
        return $this->field($id,$name,$parent_id)->order('lft')->select();
    }

    /**
     * 添加分类
     */
    public function addCategory(){
        //创建orm
        $orm = new \Admin\Logic\MySQLORM();
        //创建nestedSets
        $NestedSets = new \Admin\Logic\NestedSets($orm,$this->getTableName(),'lft','rght','parent_id','id','level');
        //创建添加
        if ($NestedSets->insert($this->data['parent_id'],$this->data,'bottom') === false) {
            return false;
        }
        return true;
    }

    /**
     *
     * 修改分类
     */
    public function saveCategory(){
        //判断是否修改了父级分类
        //获取原来的父级分类     不要用find
        $old_parent_id = $this->where(['id'=>$this->data['id']])->getField('parent_id');
        if ($old_parent_id !=$this->data['parent_id']) {
            //需要计算左右节点和层级，所以需要用nestedsets
            //创建orm
            $orm = new \Admin\Logic\MySQLORM();
            //创建nestedSets
            $NestedSets = new \Admin\Logic\NestedSets($orm,$this->getTableName(),'lft','rght','parent_id','id','level');
            //修改
            if ($NestedSets->moveUnder($this->data['id'],$this->data['parent_id'],'bottom') === false) {
                $this->error='不能移动到后代分类或者本分类中';
                return false;
            }
        }
        return $this->save();
    }

    /**
     * @param $id
     * @return bool
     * 删除分类及其后代分类
     */
    public function deleteCategory($id){
        //创建orm
        $orm = new \Admin\Logic\MySQLORM();
        //创建nestedSets
        $NestedSets = new \Admin\Logic\NestedSets($orm,$this->getTableName(),'lft','rght','parent_id','id','level');
        //删除
        if ($NestedSets->delete($id) === false) {
            $this->error='删除失败';
            return false;
        }
        return true;
    }
}