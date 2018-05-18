<?php

/**商城系统登录验证
 *
 */

namespace Shop\Controller;
use Think\Controller;

class BaseController extends Controller {
        public function __construct() {
            parent::__construct();
            $this->checkStoreSessionAction();
            C('TOKEN_ON',false);

        }

        //验证用户是否登录
        public function checkStoreSessionAction() {
            if(!session('shop.id')||!session('shop.mobile')||!session('shop.gid')){
                if(!$this->checkCookiesAction()){
                    session(null);
                    cookie('user_id',null);
                    cookie('user_pw',null);
                    cookie('mobile',null);
                    $this->error('当前用户未登录或登录超时，请重新登录', U('Shop/Login/index'));
                }
            }
        }
    /* session超时，获取本地cookies提交服务器验证
     * $id         会员id
     * $password   会员密码
     * $user_name   会员名字
     */
    public function checkCookiesAction(){
        if(cookie('user_id')!=null&&cookie('?user_pw')!=null&&cookie('?mobile')!=null&&cookie('?gid')!=null){
            $id               =  authcode(cookie('user_id'), 'DECODE', C('AU_KEY'), 0);
            $password         =  authcode(cookie('user_pw'), 'DECODE', C('AU_KEY'), 0);
            $mobile       =  authcode(cookie('mobile'), 'DECODE', C('AU_KEY'), 0);
            $gid       =  authcode(cookie('gid'), 'DECODE', C('AU_KEY'), 0);
            $usersModel = M('Users');
            if($usersModel->where("uid=$id")->count()>0){
                $user = $usersModel->alias('u')->join('LEFT JOIN '.C('DB_PREFIX').'users_group as ug ON ug.gid = u.group_id')->where("uid=$id")->find();
                //验证cookies是否正确
                if(md5(md5($password).$user['salt'])==$user['password']&&$mobile==$user['mobile']){
                    session('shop.id',$user['uid']);
                    session('shop.mobile',$user['mobile']);
                    session('shop.gid',$user['gid']);
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
}
