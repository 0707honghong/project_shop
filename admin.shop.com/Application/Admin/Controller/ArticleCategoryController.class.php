<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/6 0006
 * Time: 12:30
 */

namespace Admin\Controller;


use Think\Controller;

class ArticleCategoryController extends Controller
{
    /**
     * 创建模型
     * @var \Admin\Model\ArticleCategoryModel
     */
    protected $_model;
    public function _initialize(){
        $this->_model = D('ArticleCategory');
    }
    /**
     *分类列表(分类一般不多，所以不需要分页显示)
     */
    public function index()
    {
        $cond = [];
        //关键字
        $keyword = I('get.name');
        if ($keyword) {
            $cond['name'] = ['like','%'.$keyword.'%'];
        }
        //查询列表
        $rows = $this->_model->getData($cond);
        if ($rows) {
            $this->assign('rows', $rows);
        }
        //传送数据
        $this->display();
    }

    /**
     * 添加分类
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
            $this->success('添加成功', U('index'));
        } else {
            $this->display();
        }
    }

    /**
     * 修改分类
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
            //跳转
            $this->success('修改成功', U('index'));
        } else {
            //根据id查询数据
            $row = $this->_model->find($id);
            //传送数据
            $this->assign('row', $row);
            $this->display('add');
        }
    }

    /**
     * 移出分类
     */
    public function remove($id)
    {
        //删除数据
        if (!$this->_model->where(['id'=>$id])->setField('status',-1)) {
            $this->error($this->_model->getError());
        }
        $this->success('删除成功',U('index'));
    }
}