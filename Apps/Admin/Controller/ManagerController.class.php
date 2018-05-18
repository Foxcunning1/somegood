<?php
/*后台管理员角色权限类
 *
 * */
namespace Admin\Controller;
use Think\Controller;
class ManagerController extends BaseController {
    //角色列表
    public function managerRoleListAction(){
        //获取角色列表数据
        if(!parent::checkAdminRoleAction('manager_role_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $page = I('get.p',1);

        $keywords = I('keywords');
        if($keywords) {
            $condition['role_name'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }else{
            $condition['role_id'] = array('gt',0);
        }
        $managerModel = M('admin_role');
        $pageSize = C('MANAGER_PAGE_NUM');
        $list = $managerModel->where($condition)->page($page,$pageSize)->select();
        $count      = $managerModel->where($condition)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,$pageSize,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $this->assign('list',$list);//管理员列表数据
        $this->assign('page',$show);//分页字符串
        $this->assign('keywords',$keywords);//搜索关键字
        $this->display();
    }

    //添加角色
    public function managerRoleAddAction(){
        //获取角色列表数据
        $action = I('action','add');//操作类型
        $id = I("id", 0);//角色ID
        if(!parent::checkAdminRoleAction('manager_role_manage',$action)){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $adminRoleModel = M('admin_role');
        $systemNavModel = M('system_nav');
        $adminRoleValueModel  = M('admin_role_value');
        if(IS_POST){
            $jumpUrl = I('post.returnUrl');
            $dataArr = I("data");
            $list = parent::treeAction($systemNavModel->select(),0,1);
            $val = array();
            foreach ($list as $key => $value) {
                $str = "action_type_".$value['name'];
                $strValue = I($str);
                if($strValue){
                    $val[$value['name']]= $strValue;
                }
            }
            if($action == 'add'){
                if($dataArr['role_type']==1){
                    $dataArr['is_sys'] = 1;
                }
                if(false !== $r_id =$adminRoleModel->add($dataArr)){
                    if($dataArr['role_type']==2){
                        $res = array();
                        foreach ($val as $k1 => $v1) {
                            foreach ($v1 as $k2 => $v2) {
                                $res['role_id'] = $r_id;
                                $res['nav_name'] = $k1;
                                $res['action_type'] = $v2;
                                $adminRoleValueModel->add($res);
                            }
                        }
                    }
                    //写入系统日志
                    add_admin_log('add',session('admin.admin_name').'成功添加了ID为'.$r_id.'的角色!');
                    jscript_msg("添加成功! ",$jumpUrl);
                }else{
                    jscript_msg("添加失败! ",$jumpUrl);
                }
            }else{
                if(false !== $role_id = I('role_id')){
                    //超级管理员不可编辑
                    if($role_id!=1){
                        //修改角色名
                        if(false!==$adminRoleModel->data($dataArr)->where("role_id=$role_id")->save()){
                            //删除
                            if(false !== $adminRoleValueModel->where("role_id=$role_id")->delete()){
                                //添加
                                $res = array();
                                foreach ($val as $k1 => $v1) {
                                    foreach ($v1 as $k2 => $v2) {
                                        $res['role_id'] = $role_id;
                                        $res['nav_name'] = $k1;
                                        $res['action_type'] = $v2;
                                        $adminRoleValueModel->add($res);
                                    }
                                }
                                //写入系统日志
                                add_admin_log('edit',session('admin.admin_name').'成功修改了ID为'.$role_id.'的角色!');
                                jscript_msg("修改成功! ",$jumpUrl);
                            }else{
                                jscript_msg("修改出现异常! ",$jumpUrl);
                            }
                        }else{
                            jscript_msg("修改失败! ",$jumpUrl);
                        }
                    }
                    jscript_msg("超级管理员不可修改! ",$jumpUrl);
                }else{
                    jscript_msg("修改失败! ",$jumpUrl);
                }
            }
        }else {
            if($action == 'edit'){
                //超级管理员不可编辑
                if($id==1){
                    jscript_msg("超级管理员不可修改! ",$returnUrl);
                }
                $roleInfo = $adminRoleModel->where("role_id=$id")->find();
                $list = parent::treeAction($systemNavModel->select(),0,1);
                $list_value = $adminRoleValueModel->where("role_id=$id")->select();
                foreach ($list as $key => $value) {
                    if($value['action_type']){
                        $list[$key]['action_type'] = explode(',',$value['action_type']);
                    }
                }
                foreach ($list as $key1 => $value1) {
                    foreach ($list_value as $key2 => $value2) {
                        if($value1['name']==$value2['nav_name']){
                            $list[$key1][$value2['action_type']] = '1';
                        }
                    }
                }
                $this->assign('info',$roleInfo);
                $this->assign('role_id',$id);
                $this->assign('list',$list);
            }
            if($action =='add'){
                $list = parent::treeAction($systemNavModel->select(),0,1);
                foreach ($list as $key => $value) {
                    if($value['action_type']){
                        $list[$key]['action_type'] = explode(',',$value['action_type']);
                    }
                }
                $this->assign('list',$list);
            }
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->display();
        }
    }
    /*删除角色*/
    public function managerRoleDelAction(){
        $action = 'delete';
        //判断权限
        if(!parent::checkAdminRoleAction('manager_role_manage',$action)){
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $idArr = I('ids');
        if(is_array($idArr)){
            if($idArr['0']!=1){
                $admin = M('admin');
                $adminRol = M('admin_role');
                $adminVal  = M('admin_role_value');
                foreach ($idArr as $key => $value) {
                    if($value==1){
                        jscript_msg("超级管理员不能被删除!",$returnUrl);
                    }
                    $adminRol->where("role_id=$value")->delete();
                    $admin->where("r_id=$value")->delete();
                    $adminVal->where("role_id=$value")->delete();
                    add_admin_log('del',session('admin.admin_name').'成功删除了ID为'.$value.'的角色!');
                }

                jscript_msg("删除成功! ",$returnUrl);
            }else{
                jscript_msg("超级管理员不能被删除!",$returnUrl);
            }
        }else{
            jscript_msg("删除失败!",$returnUrl);
        }
    }

    //管理员列表
    public function managerListAction(){
        //获取管理员列表数据
        if(!parent::checkAdminRoleAction('manager_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $page = I('get.p',1);
        $keywords = I('keywords');
        if($keywords) {
            $condition['admin_name|real_name|mobile'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }else{
            $condition['id'] = array('gt',0);
        }
        $adminModel = M('admin');
        $pageSize = C('MANAGER_PAGE_NUM');
        $list = $adminModel->alias('a')->join('LEFT JOIN __ADMIN_ROLE__ AS ar ON ar.role_id = a.r_id')->where($condition)->page($page,$pageSize)->select();
        $count      = $adminModel->where($condition)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,$pageSize,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $this->assign('list',$list);//管理员列表数据
        $this->assign('page',$show);//分页字符串
        $this->assign('keywords',$keywords);//搜索关键字
        $this->display();
    }

    //添加管理员
    public function managerAddAction(){
        //获取角色列表数据
        $action = I('action','add');//操作类型
        $id = I("id", 0);//角色ID
        if(!parent::checkAdminRoleAction('manager_manage',$action)){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $adminModel = M('admin');
        if(IS_POST) {//表单提交
            $data = I('data');
            $jumpUrl = I('returnUrl');
            if($data['password']=='123ee456'){
                $data['password'] = I('password');
            }else{
                $salt = get_rand_str();
                $data['salt'] = $salt;
                $data['password'] = md5(md5($data['password']).$salt);
            }
            $data['is_lock']         = $data['is_lock'] ? 1 : 0;
            //修改操作
            if($action == 'edit'){
                if($id){
                    if(false !== $adminModel->where("id=$id")->save($data)){
                        //写入系统日志
                        add_admin_log('edit',session('admin.admin_name').'成功更新了ID为'.$id.'的管理员信息!');
                        jscript_msg("修改成功 ",$jumpUrl);
                    }else{
                        jscript_msg("修改失败!",$jumpUrl);
                    }
                }else{
                    jscript_msg("修改失败!",$jumpUrl);
                }
            }else{
                $data['add_time']   = time();
                $sid = $adminModel->add($data);
                if($sid>0){
                    //写入系统日志
                    add_admin_log('add',session('admin.admin_name').'成功添加了ID为'.$id.'的管理员!');
                    jscript_msg("添加成功! ", $jumpUrl);
                }else{
                    jscript_msg("添加失败! ", $jumpUrl);
                }
            }
        }else{
            //初始化后台系统导航列表
            $roleList  = M('admin_role')->select();
            if($action == 'edit'){
                $info   = $adminModel->alias('a')->join('LEFT JOIN __ADMIN_ROLE__ AS ar ON a.r_id = ar.role_id')->where("id=$id")->find();
            }
            if($action =='add'){
                $info = array();
            }
            //获取系统所有分类
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);//提交表单后跳转回上一个页面
            $this->assign('info',$info);
            $this->assign('roleList',$roleList);
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->display();
        }
    }
    /*删除管理员*/
    public function managerDelAction()
    {
        //判断权限
        if (!parent::checkAdminRoleAction('manager_manage', 'delete')) {
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $idArr = I('ids');
        $condition['id'] = array('in', $idArr);
        $adminModel = M('admin');
        $result = $adminModel->where($condition)->delete();
        if ($result !== false) {
            add_admin_log('del', session('admin.admin_name') . '成功删除了' . $result . '个管理员，管理员ID分别为：' . implode(',', $idArr) . '!');
            jscript_msg("删除成功！", $returnUrl);
        } else {
            jscript_msg_tips("删除失败！");
        }
    }
    /*后台日志列表*/
    public function managerNoteListAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('manager_note_manage','view')){
            $this->error('亲，您无权进行操作！');
        }
        $page = I('get.p',1);
        $keywords = I('keywords');
        if($keywords) {
            $condition['admin_name|action_type'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }else{
            $condition['id'] = array('gt',0);
        }
        /*根据日期时间查找*/
        $startTime = I('startTime');//开始时间
        $endTime = I('endTime');//结束时间
        if($startTime){
            $sTime = strtotime($startTime);
            $condition['login_time'] = array('gt',$sTime);
            $pageCondition['startTime'] = $startTime;
        }
        if($endTime){
            $eTime = strtotime($endTime);
            $condition['login_time'] = array('lt',$eTime);
            $pageCondition['startTime'] = $endTime;
        }
        $adminLogModel = M('admin_log');
        $pageSize = C('ADMIN_PAGE_NUM');
        $list = $adminLogModel->where($condition)->page($page,$pageSize)->select();
        $count      = $adminLogModel->where($condition)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,$pageSize,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $this->assign('list',$list);//管理员列表数据
        $this->assign('page',$show);//分页字符串
        $this->assign('keywords',$keywords);//搜索关键字
        $this->assign('startTime',$startTime);//结束时间
        $this->assign('endTime',$endTime);//开始时间
        $this->assign('empty',"<tr><td colspan=\"5\" align=\"center\">暂时没有相关数据!</td></tr>");
        $this->display();
    }
    /*删除管理员操作日志*/
    public function managerNoteDelAction()
    {
        //判断权限
        if (!parent::checkAdminRoleAction('manager_note_manage', 'delete')) {
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $startTime = I('txtStartTime');
        $endTime = I('txtEndTime');
        $adminLogModel = M('admin_log');
        if($startTime!=""&&$endTime!=""){
            $startTime = strtotime($startTime);
            $endTime = strtotime($endTime);
            $condition['login_time'] = array('between',array($startTime,$endTime));
        }else{
            $curTime = time();
            $condition['login_time'] = array('between',array(array('exp','UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL 7 DAY))'),$curTime));
        }
        $delCount = $adminLogModel->where($condition)->delete();
        if($delCount>0){
            add_admin_log('del',session('admin.admin_name').'成功删除了'.$delCount.'条后台操作日志!');
            jscript_msg("删除成功! ",$returnUrl);
        }else{
            jscript_msg("删除失败! ",$returnUrl);
        }

    }
}