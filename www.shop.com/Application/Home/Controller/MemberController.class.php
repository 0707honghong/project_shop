<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/17 0017
 * Time: 18:21
 */

namespace Home\Controller;


use Think\Controller;

class MemberController extends Controller
{
    /**
     * @var \Home\Model\MemberModel
     */
    private $_model;
    protected function _initialize(){
        $this->_model = D('Member');
    }

    /**
     * 注册
     */
    public function reg(){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create('','reg') === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //注册用户
            if ($this->_model->addMember() === false) {
                $this->error(dealErrorStr($this->_model));
            }
            //跳转
            $this->success('注册成功',U('Index/index'));
        }else{
            $this->assign('title','用户注册');
            $this->display();
        }
    }

    /**
     * 登录
     */
    public function login(){
        $this->display();
    }

    /**
     * 激活账号
     * @param $email
     * @param $token
     */
    public function active($email,$token){
        //修改数据中对应的账号
        if ($this->_model->where(['email'=>$email,'active_token'=>$token,'status'=>0])->setField('status',1)) {
            $this->success('激活成功',U('Index/index'));
        }else{
            $this->error('激活失败',U('Index/index'));
        }
    }

    /**
     * 验证是否已被注册
     */
    public function checkByParam(){
        $cond = I('get.');
        if ($this->_model->where($cond)->count()) {
            $this->ajaxReturn(false);
        }else{
            $this->ajaxReturn(true);
        }
    }

    /**
     * 发送手机验证码
     * @param $tel
     */
    public function sms($tel){
        if (IS_AJAX) {
            vendor('Alidayu.TopSdk');
            date_default_timezone_set('Asia/Shanghai');
            $c = new \TopClient;
            $c ->appkey = '23533640' ;
            $c ->secretKey = 'ffc45b86f7e8ee6a5708c69fc87e5c1a' ;
            $req = new \AlibabaAliqinFcSmsNumSendRequest;
            $req ->setExtend( "" );
            $req ->setSmsType( "normal" );
            $req ->setSmsFreeSignName( "注册接口测试" );
            $data = [
                'product'   =>  '健康饮食传播有限公司',
                'code'      =>  \Org\Util\String::randNumber(1000,9999),
            ];
            //将验证码存入session中
            $code = [
                'tel'=>$tel,
                'code'=>$data['code'],
            ];
            session('TEL_CODE',$code);
            $data = json_encode($data);
            $req ->setSmsParam( $data );
            $req ->setRecNum( $tel );
            $req ->setSmsTemplateCode( "SMS_25890386" );
            $resp = $c ->execute( $req );
            if (isset($resp->result->success)) {
                $this->ajaxReturn(true);
            }
        }
        //代表发送失败，可能是接口速度限制、没有钱、或者是非ajax调用
        $this->ajaxReturn(false);
    }

    public function mail($address){
        vendor('PhpMailer.PHPMailerAutoload');
        $mail = new \PHPMailer;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'hong_x94@163.com';                 // SMTP username
        $mail->Password = 'xh20140226tw';                           // SMTP password    授权码
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('hong_x94@163.com', 'hong');
        $mail->addAddress($address);     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->CharSet='utf-8';

        $mail->Subject = '健康饮食传播有限公司';
        $mail->Body    = '请点击以下链接激活<b></b>';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }

}