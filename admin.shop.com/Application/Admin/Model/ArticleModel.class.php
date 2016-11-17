<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/6 0006
 * Time: 14:03
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class ArticleModel extends Model
{
    public function getPageResult(array $cond=[],$keyword)
    {
        $count = $this->where($cond)->count();
        $page = new Page($count,'PAGE.SIZE');
        $page->setConfig('theme',C('PAGE.THEME'));
        $page_html = $page->show();
        $rows = $this->table('shop_article as A')->join('shop_article_category as C on A.article_category_id = C.id')
            ->field('A.*,C.name as cname')->where(['A.name'=>array('like','%'.$keyword.'%')])->page(I('get.p'),C('PAGE.SIZE'))->order('sort')->select();
        foreach($rows as $key=>$val){
            $rows[$key]['inputtime']=date('Y-m-d H:i:s',$val['inputtime']);
        }
        return [
            'page_html'=>$page_html,
            'rows'=>$rows,
        ];
    }

    /**
     * 添加文章
     * @return bool|mixed
     */
    public function addArticle(){
        $this->startTrans();
        $_POST['inputtime'] = NOW_TIME;
        //添加基本信息
        if (($id = $this->add($_POST)) === false) {
            $this->rollback();
            $this->error='添加失败';
            return false;
        }
        //添加内容信息
        $data = [
            'article_id'=>$id,
            'content'=>I('post.content','',false),
        ];
        if (M('ArticleContent')->add($data) === false) {
            $this->rollback();
            $this->error='添加文章内容失败';
            return false;
        }
        $this->commit();
        return $id;
    }

    public function editArticle($id){
        $this->startTrans();
        if ($this->where(['id'=>$id])->save() === false) {
            $this->rollback();
            $this->error='修改失败';
            return false;
        }
        $data = [
            'article_id'=>$id,
            'content'=>$_POST['content'],
        ];
        if (M('ArticleContent')->where(['article_id'=>$id])->save($data) === false) {
            $this->rollback();
            $this->error = "修改失败";
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * @param $id
     * @return mixed
     * 获取数据
     */
    public function getList($id)
    {
        $row = $this->field('A.*,AC.content,C.name as cname')->table('shop_article as A')
            ->join('shop_article_content as AC on A.id = AC.article_id')
            ->join('shop_article_category as C on A.article_category_id = C.id')
            ->where(['A.id'=>$id])->select();
        foreach($row as $key=>$value){
            $row[$key]['inputtime']=date('Y-m-d H:i:s',$value['inputtime']);
        }
        return $row;
    }

    /**
     * @param $id
     * 删除数据
     */
    public function delData($id)
    {
        $this->startTrans();
        //判断id是否为空
        if (empty($id)) {
            $this->rollback();
            return false;
        }
        //删除基本信息
        if ($this->delete($id) === false) {
            $this->rollback();
            $this->error = '删除失败';
            return false;
        }
        //删除内容信息
        if (M('ArticleContent')->where(['article_id'=>$id])->delete() === false) {
            $this->rollback();
            $this->error='删除失败';
            return false;
        }
        $this->commit();
        return true;
    }

}