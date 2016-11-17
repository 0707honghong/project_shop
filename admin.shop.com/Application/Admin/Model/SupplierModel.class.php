<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/9 0009
 * Time: 12:33
 */

namespace Admin\Model;


use Think\Model;

class SupplierModel extends Model
{
    public function getList($id,$name){
        return $this->field($id,$name)->select();
    }
}