<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/9 0009
 * Time: 14:48
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class GoodsModel extends Model
{
    /**
     * 自动完成,在插入的时候将商品状态按位或
     * 添加的时间是当前事件
     * @param array $cond
     * @return array
     */
    protected $_auto = array(
        array('goods_status','array_sum',self::MODEL_INSERT,'function'),
        array('inputtime',NOW_TIME,self::MODEL_INSERT),
    );
    public function getPageResult(array $cond=[]){
        $cond = array_merge(['status'=>['neq',0]],$cond);
        //获取总条数
        $count = $this->where($cond)->count();
        //创建page模型
        $page = new Page($count,'PAGE.SIZE');
        $page->setConfig('theme',C('PAGE.THEME'));
        $page_html = $page->show();
        //获取商品信息列表
        $rows = $this->where($cond)->page(I('get.p'),C('PAGE.SIZE'))->order('sort')->select();
        foreach($rows as $key=>$value){
            $rows[$key]['is_best'] = $value['goods_status'] & 1?1:0;
            $rows[$key]['is_new'] = $value['goods_status'] & 2?2:0;
            $rows[$key]['is_hot'] = $value['goods_status'] & 4?4:0;
        }
        return [
            'page_html'=>$page_html,
            'rows'=>$rows,
        ];
    }
    /**
     * 添加商品信息
     * @return bool
     */
    public function addGoods(){
        $this->startTrans();
        $_POST['sn'] = $this->_sn();
        if (($id = $this->add()) === false) {
            $this->rollback();
            return false;
        }
        //保存商品内容信息
        $data = [
            'goods_id'=>$id,
            'content'=>I('post.content','',false),
        ];
        if (M('GoodsIntro')->add($data) === false) {
            $this->rollback();
            $this->error = '保存详细内容失败';
            return false;
        }
        //保存相册信息
        $path = I('post.path');
        $gallery = [];
        foreach($path as $val){
            $data = [
                'goods_id' =>$id,
                'path'=>$val,
            ];
            $gallery[]=$data;
        }
        if (M('GoodsGallery')->addAll($gallery) === false) {
            $this->rollback();
            $this->error = '保存相册路径失败';
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 获取商品信息
     * @param $id
     * @return bool|mixed
     */
    public function getGoodsInfo($id){
        $cond = array(
            'status'=>1,
        );
        $row = $this->where($cond)->find($id);
        if (empty($row)) {
            $this->error='商品不存在';
            return false;
        }
        $tmp_goods_status = $row['goods_status'];
        $row['goods_status']=array();
        if ($tmp_goods_status & 1) {
            $row['goods_status'][] = 1;
        }
        if($tmp_goods_status & 2){
            $row['goods_status'][] = 2;
        }
        if($tmp_goods_status & 4){
            $row['goods_status'][] = 4;
        }
        $row['goods_status']=json_encode($row['goods_status']);
        return $row;
    }

    /**
     * 编辑商品信息
     * @param $id
     * @return bool
     */
    public function saveGoods($id){
        if ($this->save() === false) {
            $this->error='修改失败';
            return false;
        }
        $data = [
            'goods_id'=>$id,
            'content'=>I('post.content','',false),
        ];
        if (M('GoodsIntro')->save($data) === false) {
            $this->error="修改失败";
            return false;
        }
        //保存相册信息
        $path = I('post.path');
        $gallery = [];
        foreach($path as $val){
            $data = [
                'goods_id' =>$id,
                'path'=>$val,
            ];
            $gallery[]=$data;
        }
        if (M('GoodsGallery')->where(['goods_id'=>$id])->delete() !== false) {
            if (M('GoodsGallery')->addAll($gallery) === false) {
                $this->error = '修改相册路径失败';
                return false;
            }
        }
    }

    /**
     * 获取货号
     * @return string
     */
    private function _sn(){
        $day = date('Ymd');
        //我们需要知道当天已经创建了多少个商品
        $goods_count_model = M('GoodsDayCount');
        //如果今天一条商品都没有，就插入一条记录
        if (!($count = $goods_count_model->getFieldByDay($day,'count'))) {
            $count = 1;
            $data = array(
                'day'=>$day,
                'count'=>$count,
            );
            $goods_count_model->add($data);
        }else{
            $count++;
            //用于统计字段的跟新  模型->where(条件)->setInc('统计字段',需要加多少)前面必须加where
            //模型->setDec('统计字段','条件','需要加多少')
            $goods_count_model->where(array('day'=>$day))->setInc('count',1);
        }
        return $this->data['sn'] = 'SN'.$day.str_pad($count,5,'0',STR_PAD_LEFT);
    }
}