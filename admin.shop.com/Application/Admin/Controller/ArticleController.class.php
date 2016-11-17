<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/6 0006
 * Time: 14:00
 */

namespace Admin\Controller;


use Think\Controller;

class ArticleController extends Controller
{
    /**
     * @var \Admin\Model\ArticleModel
     */
    protected $_model;
    public function _initialize(){
        $this->_model = D('Article');
    }
    /**
     * 显示文章列表
     */
    public function index(){
        //设置条件
        $cond = [];
        //获取关键字
        $keyword = I('get.name');
        if ($keyword) {
            $cond['name'] = ['like','%'.$keyword.'%'];
        }
        //查询数据列表
        $data = $this->_model->getPageResult($cond,$keyword);
        //传送数据
        $this->assign($data);
        $this->display();
    }

    /**
     * 添加文章
     */
    public function add(){
        if (IS_POST) {
            $_POST['inputtime'] = NOW_TIME;
            //收集数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //添加数据
            if ($this->_model->addArticle() === false) {
                $this->error($this->_model->getError());
            }
            //跳转
            $this->success('添加文章成功',U('index'));
        }else{
            $rows = $this->_before_view();
            $this->assign('rows',$rows);
            $this->display();
        }
    }

    /**
     * 编辑文章
     *$id  主键  通过id 编辑
     */
    public function edit($id){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            //修改数据
            if ($this->_model->editArticle($id) === false) {
                $this->_model($this->_model->getError());
            }
            //跳转
            $this->success('修改成功',U('index'));
        }else{
            //获取文章分类
            $rows = $this->_before_view();
            //查询数据列表
            $row = $this->_model->getList($id);
            $data = [
                'rows'=>$rows,
                'row'=>$row[0],
            ];
            $this->assign($data);
            $this->display('add');
        }
    }

    /**
     * 删除文章
     * $id  主键  通过id 删除文章
     */
    public function remove($id){
        //删除数据
        if ($this->_model->delData($id) === false) {
            $this->error($this->_model->getError());
        }
        $this->success('删除文章成功',U('index'));
    }

    /**
     * 查看文章详细信息
     * $id 主键  通过id 查看文章详细信息
     */
    public function view($id)
    {
        //查询数据
        $row=$this->_model->getData($id);
        //传送数据
        $this->assign('row',$row[0]);
        $this->display();
    }

    /**
     * 查询文章分类
     */
    public function _before_view(){
        //查询文章分类
        $articleCategory_Model = D('ArticleCategory');
        return $articleCategory_Model->select();
    }
}