<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/7 0007
 * Time: 21:26
 */

namespace Admin\Model;


use Think\Model;

class ArticleCategoryModel extends Model
{
    /**
     * @param array $cond
     * @return mixed
     * 根据条件查询数据
     */
    public function getData(array $cond=[])
    {
        $rows = $this->where($cond)->select();
        return $rows;
    }
}