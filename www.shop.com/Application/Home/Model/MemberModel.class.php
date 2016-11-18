<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/17 0017
 * Time: 18:22
 */

namespace Home\Model;


use Think\Model;
use Think\Verify;

class MemberModel extends Model
{
    /**
     * 自动验证
     * @var bool
     */
    protected $patchValidate = true;
    protected $_validate = [
        ['username','require','用户名不能为空'],
        ['username','3,16','用户名必须为3到16位',self::EXISTS_VALIDATE,'length','reg'],
        ['username','','用户名已存在',self::EXISTS_VALIDATE,'unique','reg'],
        ['password','require','密码不能为空'],
        ['password','6,16','密码必须为6到16为',self::EXISTS_VALIDATE,'length','reg'],
        ['repassword','password','两次密码必须一致',self::EXISTS_VALIDATE,'confirm','reg'],
        ['email','require','邮箱不能为空'],
        ['email','email','邮箱格式不正确'],
        ['email','','邮箱已存在',self::EXISTS_VALIDATE,'unique','reg'],
        ['tel','require','电话号码不能为空'],
        ['tel','/^1[34578]\d{9}$/','电话号码格式不正确',self::EXISTS_VALIDATE,'regex','reg'],
        ['tel','','电话号码已存在',self::EXISTS_VALIDATE,'unique','reg'],
//        ['checkcode','require','验证码不能为空'],
//        ['checkcode','checkCheckcode','验证码不正确',self::EXISTS_VALIDATE,'callback'.'reg'],
        ['captcha','checkTelcode','手机验证码不合格',self::MUST_VALIDATE ,'callback','reg'],
    ];

    /**
     * 验证 验证码
     * @param $code
     * @return bool
     */
    protected function checkCheckcode($code){
        $verify = new Verify();
        return $verify->check($code);
    }

    protected function checkTelcode($code){
        //获取session数据
        $sess_code = session('TEL_CODE');
        if (empty($sess_code)) {
            return false;
        }
        return $code == $sess_code['code'] && I('post.tel') == $sess_code['tel'];
    }
    /**
     * 自动验证
     * @var array
     */
    protected $_auto = [
        ['add_time',NOW_TIME,'reg'],
        ['salt','\Org\Util\String::randString','reg','function']
    ];

    /**
     * 用户注册
     * @return mixed
     */
    public function addMember(){
        $this->data['password'] = salt_mcrypt($this->data['password'],$this->data['salt']);
        //发送邮件
        //注册中带有一个激活连接，点击就验证是否正确(通过一个随机字符串)
        $address = $this->data['email'];
        $subject = '欢迎注册健康饮食传播有限公司';
        $token = \Org\Util\String::randString(32);
        $url = U('Member/active',['token'=>$token,'email'=>$address],'',true);
        $content = '<h2>欢迎<b>'.$this->data['username'].'</b>注册</h2>。<p>感谢您注册'.$subject.
            '，账号需激活才能使用,请单击<a href="'.$url.'">激活连接</a></p>
            <p>如无法点击，请复制下面的地址在浏览器中粘贴打开'.$url.'</p>';
        send_mail($address,$subject,$content);
        $this->data['active_token'] = $token;
        return $this->add();
    }


}