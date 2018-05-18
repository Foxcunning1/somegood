<?php
namespace Shop\Controller;
use Think\Controller;
//商城用户登录类
class LoginController extends Controller {

    public function indexAction(){
       $this->display();
    }

    /*注册*/
	public function registerAction(){
        if (IS_POST){
            $data=I('data');
            $mobile=$data['mobile'];
            if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
                $code_info=M('verification')->where(array('mobile'=>$mobile,'type'=>'bind'))->find();
                if((time()-$code_info['time'])<C('SMS_SEND_INTERVAL')){
                    if(I('code')==$code_info['code']){
                        //写入Users表
                        $usersModel = D("Common/Users");
                        //生成用户密码
                        $salt = get_rand_str();//生成密码干扰码
                        $password = get_rand_str(6,"number");//生成6位的随机密码
                        $data['reg_time'] = $data['last_time'] = time();
                        $data['reg_ip'] = $data['last_ip'] = get_client_ip();
                        $data['salt'] = $salt;
                        $data['password'] = md5(md5($password).$salt);
                        $uid = $usersModel->data($data)->add();
                        if($uid>0){
                            $userInfo = $usersModel->where(array('uid' => $uid))->find();
                            //写入登录信息
                            $usersModel->setLoginCookie($userInfo['uid'],'shop', $userInfo['group_id'], $data['reg_time'], $data['reg_ip'], $userInfo['mobile']);
                            //更新购物车user_id
                            $this->updateUserId();
                            //发送密码给用户
                            send_sms_message('success',$data['mobile'],$password);
                            $this->ajaxInfoReturn('','注册成功！',1);
                        }else{
                            $this->ajaxInfoReturn('','注册失败,请重试！',0);
                        }
                    }else{
                        $this->ajaxInfoReturn('','验证码错误！',0);
                    }
                }else{
                    $this->ajaxInfoReturn('','验证码已过期！',0);
                }
            }else{
                $this->ajaxInfoReturn('','手机号格式不正确！',0);
            }
        }else{
            $this->assign('type',I('type'));
            $this->display();
        }
    }

    /*登录*/
    public function loginAction(){
        $mobile = I('mobile',0);
        $pw = I('password');
        $usersModel = M('Users');
        $info=$usersModel->where(array('mobile'=>$mobile))->find();
        if ($info){
            $userInfo = $usersModel->alias('u')->field('u.uid,password,salt,u.mobile as mobile,u.error_num,u.error_time,ug.*')->join('LEFT JOIN __USERS_GROUP__ as ug ON ug.gid = u.group_id')->where(array('u.mobile'=>$mobile))->find();
            if($userInfo["error_num"] == 5 && strtotime("+".C("LOCK_TIME")." minute",$userInfo["error_time"])){
                $this->ajaxInfoReturn("","您输入密码错误的次数已超过<b style='color:red;'>".C("ERROR_NUM")."</b>次，请<b style='color:red;'>".C("LOCK_TIME")."</b>分钟后，再重新尝试！",0);
                die;
            }
            if(md5(md5($pw).$userInfo['salt'])==$userInfo['password']&&$mobile==$userInfo['mobile']){
                $dataArr['error_num'] = 0;
                $dataArr['error_time'] = 0;
                $usersModel->where(array("mobile"=>$mobile))->data($dataArr)->save();//重置用户登录错误次数和时间
                session('shop.id',$userInfo['uid']);
                session('shop.mobile',$userInfo['mobile']);
                session('shop.gid',$userInfo['gid']);
                if ($_POST['remeber']=='on'){
                    authcode(cookie('user_id'), 'ENCODE', C('AU_KEY'), 0);
                    authcode(cookie('user_pw'), 'ENCODE', C('AU_KEY'), 0);
                    authcode(cookie('mobile'), 'ENCODE', C('AU_KEY'), 0);
                    authcode(cookie('gid'), 'ENCODE', C('AU_KEY'), 0);
                }
                $this->ajaxInfoReturn("","登录成功",1);
            }else{
                $dataArr['error_num'] = $userInfo['error_num']==5?1:$userInfo['error_num']+1;
                $dataArr['error_time'] = time();
                $usersModel->where(array("mobile"=>$mobile))->data($dataArr)->save();//记录当前用户输入密码错误的时间和
                $this->ajaxInfoReturn("","密码错误！您还可以尝试<b style='color:red;'>".(C("ERROR_NUM")-$dataArr['error_num'])."</b>次",0);
            }
        }else{
            $this->ajaxInfoReturn("sign","用户不存在,请先注册",0);
        }
    }
    /*
     * 手机短信登录
     */
    public function mobileLoginAction(){
        $returnUrl=$_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:U('Shop/Index/index');
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
                    $usersModel->setLoginCookie($userInfo['uid'],'shop', $userInfo['group_id'], $data['reg_time'], $data['reg_ip'], $userInfo['mobile'], $userInfo['user_name']);
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

    /*
     *手机绑定页
     */
    public function bindMobileAction(){
        if(IS_AJAX){
            $userModel = M('users');
            $mobile = I('mobile');
            $user_name = session('shop.user_name');
            $verifyCode = I('verifyCodeGet');
            $codeInfo = M('verification')->where(array('mobile'=>$mobile,'type'=>'bind'))->find();
            if ($verifyCode == $codeInfo['code']){
                $curTime = time();
                if(strtotime('+2 minute', $codeInfo['time']) > $curTime){
                    //写入Users表
                    $data['mobile']=$mobile;
                    $re = $userModel->where(array('user_name'=>$user_name))->save($data);
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
        }
    }

    /**验证手机*/
    public function checkMobileAction(){
        $mobile=I('param');
        if(!$mobile){
            $this->ajaxInfoReturn('手机号不可为空','','n');
        } elseif (regexp_str('mobile',$mobile)){
            $usersMobile=M('Users');
            $count=$usersMobile->where(array('mobile'=>$mobile))->count();
            if ($count==0){
                $this->ajaxInfoReturn('','可以使用','y');
            }else{
                $this->ajaxInfoReturn('','该手机号已被绑定','n');
            }
        }else{
            $this->ajaxInfoReturn('','手机号格式不正确','n');
        }
    }

    /**验证邮箱*/
    public function checkEmailAction(){
        $email=I('param');
        if(!$email){
            $this->ajaxInfoReturn('邮箱不可为空','','n');
        } elseif (regexp_str('email',$email)){
            $usersMobile=M('Users');
            $count=$usersMobile->where(array('email'=>$email))->count();
            if ($count==0){
                $this->ajaxInfoReturn('','可以使用','y');
            }else{
                $this->ajaxInfoReturn('','该邮箱已被绑定','n');
            }
        }else{
            $this->ajaxInfoReturn('','邮箱格式不正确','n');
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
                $condition['_string'] = "user_id=$user_id OR phpsessid=$phpsessid";
                $cartList = $cartModel->field("group_concat('cart_id'),goods_id")->where($condition)->group('c.goods_id')->select();    //这个用户的购物车数组
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

    /*登出*/
    public function loginOutAction(){
        session_unset();
        cookie(null);
        header("Location:".$_SERVER['HTTP_REFERER']);
    }
}
