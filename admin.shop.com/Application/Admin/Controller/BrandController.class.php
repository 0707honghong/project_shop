<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/5 0005
 * Time: 16:02
 */

namespace Admin\Controller;


use Think\Controller;

class BrandController extends Controller
{
    /**
     * @var \Admin\Model\BrandModel
     */
    protected $_model;
    public function _initialize(){
        $this->_model=D('Brand');
    }
    /**
     * 品牌列表
     */
    public function index()
    {
        //获取搜索条件
        $keyword = I('get.name');
        $cond = [];
        if ($keyword) {
            $cond['name']=['like','%'.$keyword.'%'];
        }
        //查询列表
        $data = $this->_model->getPageResult($cond);
        //传送数据
        $this->assign($data);
        $this->display();
    }

    /**
     * 添加数据
     */
    public function add()
    {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //添加数据
            if ($this->_model->add() === false) {
                $this->error($this->_model->getError());
            }
            //跳转
            $this->success('添加成功',U('index'));
        }else{
            $this->display();
        }
    }

    /**
     * 修改数据
     */
    public function edit($id)
    {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //修改数据
            if ($this->_model->save() === false) {
                $this->error($this->_model->getError());
            }
            //返回
            $this->success("修改成功",U('index'));
        }else{
            //获取数据表中的数据
            $row = $this->_model->find($id);
            //传递数据
            $this->assign('row',$row);
            $this->display('add');
        }
    }

    /**
     * 删除数据(逻辑删除)
     *
     */
    public function remove($id){
        if (!$this->_model->where(['id'=>$id])->setField('status',-1)){
            $this->error($this->_model->getError());
        }else{
            $this->success('删除成功',U('index'));
        }
    }
}