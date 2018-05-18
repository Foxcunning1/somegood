<?php
namespace Seller\Controller;
use Think\Controller;
class LoginController extends Controller {

    public function indexAction(){
       $this->display();
    }

    /*登录*/
    public function loginAction(){
        $mobile = I('mobile');
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
                session('seller.id',$userInfo['uid']);
                session('seller.mobile',$userInfo['mobile']);
                session('seller.gid',$userInfo['gid']);
                session('seller.is_seller',$userInfo['is_seller']);
                session('seller.is_store',$userInfo['is_store']);
                if ($_POST['remeber']=='on'){
                    authcode(cookie('user_id'), 'ENCODE', C('AU_KEY'), 0);
                    authcode(cookie('user_pw'), 'ENCODE', C('AU_KEY'), 0);
                    authcode(cookie('mobile'), 'ENCODE', C('AU_KEY'), 0);
                    authcode(cookie('gid'), 'ENCODE', C('AU_KEY'), 0);
                }
                if($info['is_seller']==1){
                    $this->ajaxInfoReturn("",U('Seller/Index/index'),1);
                }else{
                    $this->ajaxInfoReturn("apply","登录失败,您还未申请成为卖家或申请正在审核",0);
                }

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
    /*登出*/
    public function loginOutAction(){
        session_unset();
        cookie(null);
        header("Location:".$_SERVER['HTTP_REFERER']);
    }

    /*
     * 手机短信登录
     */
    public function mobileLoginAction(){
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
                    if ($userInfo['is_seller']!=1) {//用户非店主或卖家未审核
                        $this->ajaxInfoReturn('', '登录失败,您还未申请成为卖家', 0);
                    } else {
                        $flag = true;
                    }
                    //写入登录信息
                    $usersModel = D("Common/Users");
                    $usersModel->setLoginCookie($userInfo['uid'],'seller', $userInfo['group_id'], $data['reg_time'], $data['reg_ip'], $userInfo['mobile'], $userInfo['user_name']);
                    //再次获取用户信息
                    if ($flag) {
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
        }
    }
}
