<?php
/*后台登录控制器
 *
 * */
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {

    public function indexAction(){
        if(!session('?admin.admin_id')||!session('?admin.admin_name')||!session('?admin.admin_role_name')){
            if(!$this->checkCookies()){
                session(null);
                cookie('admin_id',null);
                cookie('admin_pw',null);
                cookie('admin_name',null);
                cookie('admin_role_name',null);
                $this->display();
            }else{
                $this->success('您已经登录，系统将在3秒后自动跳转至后台管理页面！', U('Index/index'));
            }
        }else{
            $this->success('您已经登录，系统将在3秒后自动跳转至后台管理页面！', U('Index/index'));
        }
    }
    /* session超时，获取本地cookies提交服务器验证
     * $id         管理员id
     * $password   管理员密码
     * $admin_name 管理员名字
     */
    public function checkCookies(){
        if(cookie('admin_id')!=null&&cookie('?admin_pw')!=null&&cookie('?admin_name')!=null&&cookie('?admin_role_name')!=null){
            $id               =  authcode(cookie('admin_id'), 'DECODE', C('AU_KEY'), 0);
            $password         =  authcode(cookie('admin_pw'), 'DECODE', C('AU_KEY'), 0);
            $admin_name       =  authcode(cookie('admin_name'), 'DECODE', C('AU_KEY'), 0);
            $admin_role_name  =  authcode(cookie('admin_role_name'), 'DECODE', C('AU_KEY'), 0);
            $adminModel = M('Admin');
            if($adminModel->where("id=$id")->count()>0){
                $admin = $adminModel->alias('a')->join('LEFT JOIN __ADMIN_ROLE__ as ar ON ar.role_id = a.r_id')->where("id=$id")->find();
                //验证cookies是否正确
                if($password==md5($admin['password'].$admin['salt'])&&$admin_name==$admin['admin_name']){
                    session('admin.admin_id',$admin['id']);
                    session('admin.admin_name',$admin['admin_name']);
                    session('admin.role_id',$admin['r_id']);
                    session('admin.admin_role_name',$admin['role_name']);
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
    /*
     * 后台登录
     */
    public function loginAction(){
        $admin_name = I('post.txtUserName');
        $password = I('post.txtPassword','','md5');
        if(empty($admin_name))
        {
            $this->ajaxInfoReturn(0,"用户名不能为空！",0);
            return false;
        }
        if(empty($password))
        {
            $this->ajaxInfoReturn(0,"密码不能为空！",0);
            return false;
        }
        //验证用户
        $adminModel = M("Admin");
        if($adminModel->alias("a")->join('LEFT JOIN __ADMIN_ROLE__ AS ar ON a.r_id = ar.role_id ')->where(array("admin_name"=>$admin_name))->count()>0)
        {
            $UserInfo = $adminModel->alias("a")->join('LEFT JOIN __ADMIN_ROLE__ AS ar ON a.r_id = ar.role_id ')->where(array("admin_name"=>$admin_name))->find();
            $password = md5($password.$UserInfo['salt']);
            if($UserInfo['password']!=$password){
                $this->ajaxInfoReturn(0,'当前账户密码错误 !',0);
                die();
            }
            //验证登录账户是否锁定
            if($UserInfo[0]['is_lock']==1)
            {
                $this->ajaxInfoReturn(0,'当前账户已被锁定 !',0);
                die();
            }
            //写入Session
            session('admin.admin_id',$UserInfo['id']);
            session('admin.admin_name',$UserInfo['admin_name']);
            session('admin.role_id',$UserInfo['r_id']);
            session('admin.admin_role_name',$UserInfo['role_name']);
            session('admin.logintime',time());
            cookie('admin_id',authcode($UserInfo['id'], 'ENCODE', C('AU_KEY'), 0));
            cookie('admin_name',authcode($UserInfo['admin_name'], 'ENCODE', C('AU_KEY'), 0));
            cookie('password',authcode(md5($UserInfo['password'].$UserInfo['salt']), 'ENCODE', C('AU_KEY'), 0));
            cookie('admin_role_name',authcode($UserInfo['role_name'], 'ENCODE', C('AU_KEY'), 0));
            //更新登录时间和IP地址
            $data = array();
            $data['id']           = $UserInfo['id'];
            $data['last_ip']     = get_client_ip();
            $data['add_time']     = time();
            $adminModel->save($data);
            //后期加入登录cookies
            //写入后台用户操作日志
            if(add_admin_log('login','用户登录'))
            {
                $this->ajaxInfoReturn(U('Index/index'),'登录成功 !',1);
            }else{
                $this->ajaxInfoReturn(U('Index/index'),'日志写入异常 !',1);
            }
        }else{
            $this->ajaxInfoReturn(0,'管理员不存在 !',0);
        }

    }

    /*后台退出
    */
    public function logoutAction(){
        session(null);
        cookie('admin_id',null);
        cookie('password',null);
        cookie('admin_name',null);
        cookie('admin_role_name',null);
        $this->success('注销成功',U('Admin/Login/index'));
    }
}