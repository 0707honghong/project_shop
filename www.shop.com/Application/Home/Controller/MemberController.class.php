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
}