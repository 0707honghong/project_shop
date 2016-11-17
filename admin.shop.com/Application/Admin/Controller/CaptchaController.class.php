<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/12 0012
 * Time: 23:11
 */

namespace Admin\Controller;


use Think\Controller;
use Think\Verify;

class CaptchaController extends Controller
{
    /**
     * 验证码
     */
    public function show(){
        $config = array(
            'fontSize'    =>    18,
            'length'      =>    4,
        );
        $captcha = new Verify($config);
        $varify = $captcha->entry();
    }
}