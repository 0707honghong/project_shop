<?php
/**
 * Created by PhpStorm.
 * Admin: Administrator
 * Date: 2016/11/8 0008
 * Time: 18:20
 */

namespace Admin\Logic;


class MySQLORM implements Orm
{

    public function connect()
    {
        // TODO: Implement connect() method.
        echo '<pre>';
        echo __METHOD__.'<br/>';
        var_dump(func_get_args());
        echo '<hr/>';
    }

    public function disconnect()
    {
        // TODO: Implement disconnect() method.
        echo '<pre>';
        echo __METHOD__.'<br/>';
        var_dump(func_get_args());
        echo '<hr/>';
    }

    public function free($result)
    {
        // TODO: Implement free() method.
        echo '<pre>';
        echo __METHOD__.'<br/>';
        var_dump(func_get_args());
        echo '<hr/>';
    }

    /**
     * 执行写操作
     * @param string $sql
     * @param array $args
     * @return false|int
     */
    public function query($sql, array $args = array())
    {
        // TODO: Implement query() method.
        $args = func_get_args();
        $sql = $this->_buildSql($args);
        return M()->execute($sql);       //execute   ：执行
    }

    public function insert($sql, array $args = array())
    {
        // TODO: Implement insert() method.
        $table = func_get_arg(1);      //下标为1的
        $data = func_get_arg(2);
        return M()->table($table)->add($data);
    }

    public function update($sql, array $args = array())
    {
        // TODO: Implement update() method.
        echo '<pre>';
        echo __METHOD__.'<br/>';
        var_dump(func_get_args());
        echo '<hr/>';
    }

    public function getAll($sql, array $args = array())
    {
        // TODO: Implement getAll() method.
        echo '<pre>';
        echo __METHOD__.'<br/>';
        var_dump(func_get_args());
        echo '<hr/>';
    }

    public function getAssoc($sql, array $args = array())
    {
        // TODO: Implement getAssoc() method.
        echo '<pre>';
        echo __METHOD__.'<br/>';
        var_dump(func_get_args());
        echo '<hr/>';
    }

    /**
     * 获取一行记录
     * @param type $sql
     * @param array $args
     * @return array 一行记录的关联数组
     */
    public function getRow($sql, array $args = array())
    {
        // TODO: Implement getRow() method.
        $args = func_get_args();
        $sql = $this->_buildSql($args);
        return array_pop(M()->query($sql));
    }

    public function getCol($sql, array $args = array())
    {
        // TODO: Implement getCol() method.
        echo '<pre>';
        echo __METHOD__.'<br/>';
        var_dump(func_get_args());
        echo '<hr/>';
    }

    public function getOne($sql, array $args = array())
    {
        $args = func_get_args();
        $sql = $this->_buildSql($args);
        $rows = M()->query($sql);       //结果集  二维数组
        $row = array_pop($rows);        //第一行
        $field = array_pop($row);       //第一个字段
        return $field;
    }

    /**
     * @param $args
     * @return mixed|string
     * 构造sql语句
     *
     */
    private function _buildSql($args){
        //array_shift  删除数组中第一个元素，并返回被删除的值  即获取sql结构
        $sql = array_shift($args);
        //preg_split() 用正则表达式将对象分割成字符串，并以数组方式存储
        $sqls = preg_split('/\?[FTN]/',$sql);
        array_pop($sqls);       //弹出空元素
        $sql = '';
        foreach($sqls as $key=>$value){
            $sql .=$value.$args[$key];
        }
        return $sql;
    }
}