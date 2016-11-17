<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/9 0009
 * Time: 12:32
 */

namespace Admin\Controller;


use Think\Controller;

class SupplierController extends Controller
{
    /**
     * 供货商列表
     */
    public function index(){
        $this->display();
    }

    /**
     * 添加供货商信息
     */
    public function add(){
        $this->display();
    }

    /**
     * 修改供货商信息
     */
    public function edit(){
        $this->display();
    }

    /**
     * 删除供货商信息
     */
    public function remove(){
        $this->display();
    }
}