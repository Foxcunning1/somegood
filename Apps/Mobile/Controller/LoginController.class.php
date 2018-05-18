<?php
namespace Mobile\Controller;
use Think\Controller;
class LoginController extends Controller {

    public function indexAction(){
        if(!session('?mobile.id')||!session('?mobile.user_name')||!session('?mobile.group_id')||!session('?mobile.user_group_name')){
            if(!$this->checkCookies()){
                session(null);
                cookie('user_id',null);
                cookie('user_pw',null);
                cookie('user_name',null);
                cookie('user_group_name',null);
                $this->display();
            }else{
                $this->tips("您已经登录，系统将在2秒后自动跳转主页面",1,2000,U("Mobile/Users/index"));
            }
        }else{
            $this->tips("您已经登录，系统将在2秒后自动跳转主页面",1,2000,U("Mobile/Users/index"));
        }
    }
    /* session超时，获取本地cookies提交服务器验证
     * $id         会员id
     * $password   会员密码
     * $user_name   会员名字
     */
    public function checkCookies(){
        if(cookie('user_id')!=null&&cookie('?user_pw')!=null&&cookie('?user_name')!=null&&cookie('?user_group_name')!=null){
            $id               =  authcode(cookie('user_id'), 'DECODE', C('AU_KEY'), 0);
            $password         =  authcode(cookie('user_pw'), 'DECODE', C('AU_KEY'), 0);
            $user_name       =  authcode(cookie('user_name'), 'DECODE', C('AU_KEY'), 0);
            $user_group_name  =  authcode(cookie('user_group_name'), 'DECODE', C('AU_KEY'), 0);
            $usersModel = M('Users');
            if($usersModel->where("uid=$id")->count()>0){
                $user = $usersModel->alias('u')->join('LEFT JOIN '.C('DB_PREFIX').'users_group as ug ON ug.gid = u.group_id')->where("id=$id")->find();
                //验证cookies是否正确
                if($password==md5($user['password'].$user['salt'])&&$user_name==$user['user_name']){
                    session('mobile.id',$user['uid']);
                    session('mobile.user_name',$user['user_name']);
                    session('mobile.group_id',$user['gid']);
                    session('mobile.user_group_name',$user['group_name']);
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
    //登录同步到用户的购物车
      public function updateUserId(){
        $user_id=session('mobile.id');
        $phpsessid=cookie('equipment_id');          //用户设备id
        if (!empty($phpsessid)) {
            $map['phpsessid']   =   $phpsessid;
            $map['user_id']     =   0;
            $cartModel = M('cart');
            $counts=$cartModel->where($map)->count();          //设备id存的购物车商品数量
            if ($counts != 0) {
                //筛选去重,如果goods_id已存在,先删除
                $condition['user_id'] = $user_id;
                $condition['phpsessid'] = $phpsessid;
                $condition['_logic'] = 'or';
                $cartList = $cartModel->field("group_concat(cart_id) as cart_id,goods_id")->where($condition)->group('goods_id')->select();    //这个用户的购物车数组
                foreach ($cartList as $key => $v) {
                    if (!is_numeric($v['cart_id'])) {
                        $cartModel->where(array('user_id'=>$user_id,'goods_id'=>$v['goods_id']))->delete();
                    }
                }
                //同步到购物车
              $data['user_id']=$user_id;
              $cartModel->where($map)->save($data);
          }
        }
      }
    /*
     *手机绑定页
     */
    public function bindMobileAction(){
        $returnUrl=$_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:U('Mobile/Index/index');
        if(IS_AJAX){
            $userModel = M('users');
            $mobile = I('mobile');
            $user_name = session('mobile.user_name');
            $verifyCode = I('verifyCodeGet');
            $codeInfo = M('verification')->where(array('mobile'=>$mobile,'type'=>'bind'))->find();
            if ($verifyCode == $codeInfo['code']){
                $curTime = time();
                if(strtotime('+2 minute', $codeInfo['time']) > $curTime){
                    //写入Users表
                    $data['mobile']=$mobile;
                    //根据是否存在user_name判断是微信还是其他
                    if ($user_name){
                        //存在则为其他
                        $re = $userModel->where(array('user_name'=>$user_name))->save($data);
                    }else{
                        //不存在为微信
                        $usersWeixinModel = D('UsersWeixin');
                        $wxUser=$usersWeixinModel->where(array('openid'=>session('mobile.openid')))->find();
                        $data['avatar']=$wxUser['headimgurl'];
                        $data['nick_name']=$wxUser['nickname'];
                        $data['reg_time']=$data['last_time']=time();
                        $data['reg_ip']=$data['last_ip']=get_client_ip();
                        //生成用户密码
                        $salt = get_rand_str();//生成密码干扰码
                        $data['salt'] = $salt;
                        $password = get_rand_str(6,"number");//生成6位的随机密码
                        $data['password'] = md5(md5($password).$salt);
                        $user = $userModel->where(array('mobile'=>$mobile))->find();
                        if ($user){
                            //如果已存在
                            $uid=$user['uid'];
                        }else{
                            $uid = $userModel->add($data);
                            if($uid > 0){
                                //发送密码短信给用户
                                send_sms_message('success',$data['mobile'],$password);
                            }
                        }
                        //将UID绑定到users_weixin表
                        $wxUser['uid'] = $uid;
                        $re = $usersWeixinModel->bindWxUsersInfo($wxUser,session('mobile.openid'));//更新用户信息
                        $usersModel = D("Common/Users");
                        $userInfo = $usersModel->getUsersInfo($uid);
                        $lastTime = time();
                        $lastIp = get_client_ip();
                        //写入用户登录信息
                        $userModel = D("Common/Users");
                        $userModel->setLoginCookie($userInfo['uid'],'mobile',$userInfo['group_id'],$lastTime,$lastIp,$userInfo['mobile'],$userInfo['user_name']);
                        // 更新购物车user_id
                        $this->updateUserId();
                    }
                    if ($re){
                        $this->ajaxInfoReturn(1,'绑定成功',1);
                    }else{
                        $this->ajaxInfoReturn('','绑定失败',0);
                    }
                }else{
                    $this->ajaxInfoReturn('','验证码超时，请重新获取！',0);
                }
            }else{
                $this->ajaxInfoReturn('','验证码错误',0);
            }
        }else{
            $this->assign('returnUrl',$returnUrl);//获取上一操作页面
            $this->display();
        }
    }

    /*
     * 验证手机号是否存在
     */
    public function checkMobileExistAction(){
          if (I('mobile')){
                $mobile=I('mobile');
                if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
//                    $userModel = M('users');
//                    $user = $userModel->where(array('mobile'=>$mobile))->find();
                    /*if ($user){
                        $this->ajaxInfoReturn('','该手机号已被绑定',0);
                    }else{*/
                        $this->mobileSmsCodeSendAction();
                    //}
                }else{
                    $this->ajaxInfoReturn('','手机号格式不正确！',0);
                }
          }else{
              $this->ajaxInfoReturn('','请填写手机号！',0);
          }
    }

    /*
     * 确定绑定
     */
    public function mobileSmsCodeSendAction(){
            $data['mobile'] = I('mobile');
            $data['type'] = I('type');
            $data['code'] = rand(000000,999999);
            $data['ip'] = get_client_ip();
            $data['time']=time();
            $info=M('verification')->where(array('mobile'=>$data['mobile'],'type'=>$data['type']))->find();
            if($info){
                if((time()-$info['time'])<C('SMS_SEND_INTERVAL')){
                    $this->ajaxInfoReturn('','你的验证码刚刚发送，请歇一歇吧！',0);
                }else{
                    $data['second']=$info['second']+1;
                    $re = M('verification')->where(array('mobile'=>$data['mobile'],'type'=>$data['type']))->save($data);
                }
            }else{
                $re=M('verification')->add($data);
            }
            if ($re){
                if(send_sms_message($data['type'],$data['mobile'],$data['code'])){
                    $this->ajaxInfoReturn('','发送成功',1);
                }else{
                    $this->ajaxInfoReturn('','发送失败',0);
                }
            }else{
                $this->ajaxInfoReturn('','发送失败',0);
            }
    }

    /*
     * 手机短信登录
     */
    public function loginAction(){
        $returnUrl=$_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:U('Mobile/Index/index');
        if(IS_AJAX) {
            $userModel = M('users');
            $mobile = I('mobile');
            $verifyCode = I('verifyCodeGet');
            $codeInfo = M('verification')->where(array('mobile' => $mobile, 'type' => 'bind'))->find();
            $flag = false;//信息标识，不存在则为false
            if ($verifyCode == $codeInfo['code']) {
                $curTime = time();
                if (strtotime('+2 minute', $codeInfo['time']) > $curTime) {
                    //写入Users表
                    //不存在为微信
                    $data['reg_time'] = $data['last_time'] = time();
                    $data['reg_ip'] = $data['last_ip'] = get_client_ip();
                    $data['mobile'] = $mobile;
                    $userInfo = $userModel->where(array('mobile' => $mobile))->find();
                    if (!$userInfo) {//用户不存在，登录即为注册
                        //生成用户密码
                        $salt = get_rand_str();//生成密码干扰码
                        $data['salt'] = $salt;
                        $password = get_rand_str(6,"number");//生成6位的随机密码
                        $data['password'] = md5(md5($password).$salt);
                        $uid = $userModel->data($data)->add();
                        if ($uid > 0) {
                            //发送密码给用户
                            send_sms_message('success',$data['mobile'],$password);
                            $userInfo = $userModel->where(array('uid' => $uid))->find();
                            $flag = true;
                        }
                    } else {
                        $flag = true;
                    }
                    //写入登录信息
                    $usersModel = D("Common/Users");
                    if($userInfo['is_store']==1)$sid=M('store')->where(array('user_id'=>$userInfo['uid']))->getField('sid');
                    $usersModel->setLoginCookie($userInfo['uid'],'mobile', $userInfo['group_id'], $data['reg_time'], $data['reg_ip'], $userInfo['mobile'], $userInfo['user_name'],$sid);
                    //再次获取用户信息
                    if ($flag) {
                        //更新购物车user_id
                        $this->updateUserId();
                        $this->ajaxInfoReturn('', '登录成功！', 1);
                    } else {
                        $this->ajaxInfoReturn('', '登录失败', 0);
                    }
                } else {
                    $this->ajaxInfoReturn('', '验证码超时，请重新获取！', 0);
                }
            } else {
                $this->ajaxInfoReturn('', '验证码错误', 0);
            }
        }else{
            $this->assign('returnUrl',$returnUrl);//获取上一操作页面
            $this->display();
        }
    }
}
