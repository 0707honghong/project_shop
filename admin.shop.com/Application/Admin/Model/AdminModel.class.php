<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/10 0010
 * Time: 18:01
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;
use Think\Verify;

class AdminModel extends Model
{
    /**
     * 自动验证的规则
     * @var array
     */
    protected $_validate = array(
        array('username','require','用户名不能为空'),
        array('username','','用户名不能重复',self::EXISTS_VALIDATE,'unique','reg'),
        array('password','require','密码不能为空'),
        array('password','6,12','密码必须在6到12位之间',self::EXISTS_VALIDATE,'length'),
        array('repassword','password','两次密码不一致',self::EXISTS_VALIDATE,'confirm'),
        array('email','require','邮箱不能为空'),
        array('email','email','邮箱格式不对'),
        array('email','','邮箱已经被占用',self::EXISTS_VALIDATE,'unique','reg'),
        array('captcha','require','验证码不能为空'),
        array('captcha','validateCaptcha','验证码不正确',self::EXISTS_VALIDATE,'callback'),
    );
    /**
     * 是否批量处理验证
     * @var bool
     */
    protected $patchValidate = true;

    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array(
        array('add_time',NOW_TIME,'reg'),
        array('salt','\Org\Util\String::randString','reg','function'),
    );

//    验证码
    public function validateCaptcha($captcha){
        $verify = new Verify($captcha);
        $verify->check($captcha);
    }
    /**
     * 添加管理员
     * @return bool
     */
    public function addAdmin(){
        $this->startTrans();
        //添加基本信息
        $this->data['password'] = salt_mcrypt($this->data['password'],$this->data['salt']);
        if (($admin_id = $this->add()) === false) {
            $this->rollback();
            return false;
        }
        $role_ids = I('post.role_id');
        if (empty($role_ids)) {
            $this->commit();
            return true;
        }
        //添加管理员角色关联信息
        $adminRole = M('AdminRole');
        //添加管理员角色关联
        $this->_admin_role($adminRole,$admin_id,$role_ids);
        //返回数据
        $this->commit();
        return true;
    }

    /**
     * 管理员登录
     * @return int
     */
    public function login(){
        //>>1 获取用户信息
        $name = $this->data['username'];
        $password = $this->data['password'];     //调用find查找数据，所以之后的data数据都是数据库中的数据，所以我们需将提交的密码用变量保存起来
        $adminInfo = $this->getByUsername($name);
        if (empty($adminInfo)) {
            $this->error='用户名或密码不匹配';
            return false;
        }
        //>>2 获取盐
        $salt = $adminInfo['salt'];
        //>>3 给用户提交的密码加盐
        $password = salt_mcrypt($password,$salt);
        //>>4 判断用户提交的密码和数据库中的密码是否匹配
        if ($password == $adminInfo['password']) {
            $data = [
                'last_login_time'=>NOW_TIME,
                'last_login_ip'=>get_client_ip(1),      //ThinkPHP封装的类，获取ip地址，并将ip转换成long型   ip2long转换的
                'id'=>$adminInfo['id'],
            ];
            $this->save($data);
            //保存用户信息到session中
            session('ADMIN_INFO',$adminInfo);
            //保存用户权限
            $this->_saveAdmin();
            //保存用户信息到cookie中
            $this->_saveToken($adminInfo,I('post.remember'));
            return true ;
        }else{
            $this->error='用户名和密码不匹配';
            return false;
        }
    }

    /**
     * 获取所有管理员信息
     * @param array $cond
     * @return array
     */
    public function getPageResult(array $cond=[]){
        $count = $this->where($cond)->count();
        $page = new Page($count,'PAGE.SIZE');
        $page->setConfig('theme',C('PAGE.THEME'));
        $page_html = $page->show();
        $rows = $this->where($cond)->page(I('get.p'),C('PAGE.SIZE'))->order('id')->select();
        foreach ($rows as $key=>$val) {
            $rows[$key]['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
            $rows[$key]['last_login_time'] = date('Y-m-d H:i:s',$val['last_login_time']);
            $rows[$key]['last_login_ip'] = long2ip($val['last_login_ip']);
        }
        return [
            'rows'=>$rows,
            'page_html'=>$page_html,
        ];
    }

    /**
     * 获取特定用户的信息
     * @param $id
     * @return mixed
     */
    public function getOne($id){
        $row = $this->find($id);
        $row['role_ids'] = json_encode(M('AdminRole')->where(['admin_id'=>$id])->getField('role_id',true));
        return $row;
    }

    public function saveAdmin($id){
        $this->startTrans();
        if ($this->where(['id'=>$id])->save() === false) {
            $this->error='修改基本信息错误';
            $this->rollback();
            return false;
        }
        $role_ids = I('post.role_id');
        if (empty($role_ids)) {
            $this->commit();
            return true;
        }
        //删除已有的管理员角色关联
        $adminRole = M('AdminRole');
        $admin_id = $id;
        if ($adminRole->where(['admin_id'=>$id])->delete() === false) {
            $this->error='删除旧数据失败';
        }
        //添加新的管理员角色关联
        $this->_admin_role($adminRole,$admin_id,$role_ids);
        $this->commit();
        return true;
    }

    /**
     * 删除管理员信息
     * @param $id
     * @return mixed
     */
    public function delAdmin($id){
        $this->startTrans();
        if ($this->delete($id) === false) {
            $this->rollback();
            return false;
        }
        if (M('AdminRole')->where(['admin_id'=>$id])->delete() === false) {
            $this->error='删除失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 生成令牌，保存到cookie和db中
     * @param $adminInfo
     */
    protected function _saveToken($adminInfo,$is_remember=false){
        if ($is_remember) {
            //生成随机字符串，通常我们称之为令牌
            $token =\Org\Util\String::randString(32);
            //保存一份到cookie中
            $data=[
                'id'=>$adminInfo['id'],
                'token'=>$token,
            ];
            cookie('ADMIN_LOGIN_INFO',$data,604800);        //604800指cookie保存时间
            //保存一份在db中
            $this->save($data);
        }
    }

    /**
     * 完成自动登录
     * @return array|mixed
     */
    public function autoLogin(){
        //获取cookie的值
        $cookie = cookie('ADMIN_LOGIN_INFO');
        //如果没有cookie，就返回空
        if (empty($cookie)) {
            return [];
        }
        //查询是否与数据库中的cookie值匹配
        if ($adminInfo = $this->where($cookie)->find()) {
            //更新令牌
            $this->_saveToken($adminInfo);
            //将信息保存到session中
            session('ADMIN_INFO',$adminInfo);
            //保存用户权限
            $this->_saveAdmin();
            return $adminInfo;
        }else{
            return [];
        }
    }

    /**
     * 添加管理员角色关联
     * @param $adminRole   管理员角色关联表
     * @param $admin_id     管理员id
     * @param $role_ids     角色id
     * @return bool
     */
    private function _admin_role($adminRole,$admin_id,$role_ids){
        $data = [];
        foreach($role_ids as $role_id){
            $data[] = [
                'admin_id'=>$admin_id,
                'role_id'=>$role_id,
            ];
        }
        if ($adminRole->addAll($data) ===false) {
            $this->error='添加管理员失败';
            $this->rollback();
            return false;
        }
    }

    /**
     * 在用户登录的时候保存用户权限列表，以便检查授权
     */
    private function _saveAdmin(){
        $adminInfo = session('ADMIN_INFO');
        //检查RBAC权限
        //检查当前登录的用户权限
        $permissions = M('AdminRole')->alias('ar')->field('p.id,path')->join('shop_role_permission as rp using(`role_id`)')
            ->join('shop_permission as p on rp.permission_id = p.id')
            ->where(['admin_id'=>$adminInfo['id']])->select();
        $pathes = $permission_ids = [];
        foreach($permissions as $permission){
            $pathes[] = $permission['path'];
            $permission_ids[] = $permission['id'];
        }
        session('ADMIN_PATH',$pathes);
        session('ADMIN_IDS',$permission_ids);
    }
}