<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/12 0012
 * Time: 23:24
 */

namespace Common\Behaviors;


use Think\Behavior;

class checkPermissionBehavior extends Behavior
{
    public function run(&$params){
        //执行逻辑
        //增加排除列表    login   captcha
        $ignores = C('RBAC.IGNORES');
        //当前URL地址
        $url = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
        if (in_array($url,$ignores)) {
            return true;
        }
        //判断是否登录
        if (!$adminInfo = session('ADMIN_INFO')) {
            //尝试自动登录
            if (!$adminInfo = D('Admin')->autoLogin()) {     //调用AdminModel中的autoLogin方法
                //如果没有登录就跳转到登录界面
                $url = U('Admin/login');
                redirect($url);
            }
        }

        //已登录用户的忽略列表
        $user_ignores = C('RBAC.USER_IGNORES');
        if (in_array($url,$user_ignores)) {
            return true;
        }
        //admin管理员具备所有的权限
        if ($adminInfo['username'] == 'admin') {
            return true;
        }
        //检查RBAC权限
        //检查当前登录的用户权限
        $permission = session('ADMIN_PATH');
        if (in_array($url,$permission)) {
            return true;
        }else{
            echo '<script type="text/javascript">alert("无权访问");history.back()</script>';
            exit;
        }
    }
}