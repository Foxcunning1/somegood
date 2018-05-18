<?php
namespace Shop\Controller;
use Think\Controller;
//前端数据ajax类
class AjaxController extends Controller
{

    //验证用户手机号是否已注册
    public function checkMobileAction()
    {
        $param = I("param");
        $userModel = D("Common/Users");
        if($userModel->isExits($param,'mobile')){
            $this->ajaxInfoReturn("","该手机号已使用","n");
        }
        $this->ajaxInfoReturn("","可以使用","y");
    }

    //发送短信
    public function sendSmsAction(){
        if (check_verify(I('vcode'))){
            if (I('mobile')){
                $mobile=I('mobile');
                if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
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
                }else{
                    $this->ajaxInfoReturn('','手机号格式不正确！',0);
                }
            }else{
                $this->ajaxInfoReturn('','请填写手机号！',0);
            }
        }else{
            $this->ajaxInfoReturn('','验证码错误！',0);
        }
    }

    /**
     * 发送邮件验证码(登录、密码操作)
     * parm           string            $email           手机
     * parm           int               $type             1注册,3密码操作
     * */
    public function sendEmailVerifyAction()
    {
        if (check_verify(I('vcode'))){
            $email = I('email');
            $type = I('type');
            //验证码
            if (regexp_str('email',$email)) {
                $num = M('Users')->where(array('email' => $email))->count();
                //注册
                if ($type == 1) {
                    if ($num==0) {
                        $this->sendEmailMsgAction($email, 'regist');
                    } else {
                        $this->ajaxInfoReturn(0, "邮箱已存在！", 0);
                    }
                } else {
                    //登录、密码操作
                    if ($num>0) {
                        //发送短信
                        $this->sendEmailMsgAction($email, 'login');
                    } else {
                        //用户不存在
                        $this->ajaxInfoReturn(0, "用户不存在！", 0);
                    }
                }
            } else {
                //邮箱格式错误
                $this->ajaxInfoReturn(0, "邮箱格式错误！", 0);
            }
        }else{
            $this->ajaxInfoReturn('','验证码错误！',0);
        }
    }

    /**
     * 发送邮箱验证码
     * parm           string            $email             邮箱
     * parm           int               $type             1注册,2登录,3密码操作
     * */
    public function sendEmailMsgAction($email,$type)
    {

        $verification=M('verification');
        $result = $verification->where(array('email'=>$email,'type'=>$type))->find();
        $param = array();
        $param['type'] = $type;
        $param['time'] = time();
        $param['code'] = rand(100, 999) . rand(100, 999);
        $param['ip'] = get_client_ip();
        if (!$result) {
            $param['email'] = $email;
            $sid = $verification->add($param);
        } else {
            $param['second']=$result['second']+1;
            $sid = $verification->where(array('email' => $email))->save($param);
        }
        if ($sid) {
            //发送验证码
            $mail = D('Common/MailTemplate');
            $mInfo = $mail->getSendMailTemplate('register');
            $title = $mInfo['maill_title'];
            $content = htmlspecialchars_decode($mInfo['content']);
            $content = str_replace("{webname}", C('WEB_NAME'), $content);//替换网站名称
            $content = str_replace("{webtel}", C('WEB_TEL'), $content);//网站电话
            $content = str_replace("{weburl}", C('WEB_URL'), $content);//网址
            $content = str_replace("{code}", $param['code'], $content);//激活码
            //发送邮件
            $result_temp = SendMail($email, $title, $content, '邮件信使【3好连锁】');
            if ($result_temp) {
                $this->ajaxInfoReturn(0, "邮件发送成功！", 1);
            } else {
                $this->ajaxInfoReturn(0, "邮件发送失败！", 0);
            }
        }

    }
}