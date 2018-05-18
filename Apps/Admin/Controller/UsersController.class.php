<?php
/*后台会员管理控制器
 *
 * */
namespace Admin\Controller;
use Think\Controller;
class UsersController extends BaseController {
    //获取会员信息
    public function getAuthInfoAction(){
        if(!parent::checkAdminRoleAction('users_manage','view')){
            die();
        }
        $id = I('uid');
        $usersModel = M("users");
        $info = $usersModel->where("uid=$id")->find();
        if($info){
            $photoArr = unserialize($info['id_cardphoto']);
            $info['photoArr'] = $photoArr;
        }
        $this->ajaxInfoReturn($info,"获取成功!",1);
    }
    //会员列表
    public function usersListAction(){
        if(!parent::checkAdminRoleAction('users_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $p= I('get.p',1);
        $status = I('status', 0);//会员状态
        $group = I('group',0);//会员群组
        $startTime = I('startTime','');//开始时间
        $endTime = I('endTime','');//结束时间
        $keywords = I('keywords');//关键词
        if($keywords) {
            $condition['user_name|mobile'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        if($group){
            $condition['group_id'] = array("eq",$group);
            $pageCondition['group'] = $group;
        }
        if($status>0){
            $condition['status'] = array("eq",$status);
            $pageCondition['status'] = $status;
        }
        if($startTime){
            $sTime = strtotime($startTime);
            $condition['reg_time'] = array('gt',$sTime);
            $pageCondition['startTime'] = $startTime;
        }
        if($endTime){
            $eTime = strtotime($endTime);
            $condition['reg_time'] = array('lt',$eTime);
            $pageCondition['startTime'] = $endTime;
        }
        $users = M('users');
        $groupLlist = M('users_group')->select();//获取会员分组
        $count = $users->alias("a")->where($condition)->count();
        $page = new \Think\Page($count,C('COMMON_PAGE_NUM'),$pageCondition);
        $show = $page->show();
        $list = $users->alias("a")->join(C('DB_PREFIX').'users_group AS u ON a.group_id=u.gid')->field('a.*,u.group_name')
                      ->where($condition)->order('uid DESC')->page($p,C('COMMON_PAGE_NUM'))->select();
        $this->assign('groupList',$groupLlist);
        $this->assign('group',$group);
        $this->assign('status',$status);
        $this->assign('startTime',$startTime);
        $this->assign('endTime',$endTime);
        $this->assign('keywords',$keywords);
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->assign('empty',"<tr><td colspan=\"8\" align=\"center\">暂时没有相关数据!</td></tr>");
        $this->display();
    }

    //添加会员
    public function usersAddAction(){
        $action = I('action','add');//操作类型
        $id = I("id", 0);//会员ID
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        if(!parent::checkAdminRoleAction('users_manage',$action)){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $usersModel = M('users');
        $nowTime = time();
        if(IS_POST){
            $dataArr = I("data");
            $jumpUrl = I('returnUrl');
            if($dataArr['password']=='123ee456'){
                $dataArr['password'] = I('password');
            }else{
                $salt = get_rand_str();
                $dataArr['salt'] = $salt;
                $dataArr['password'] = md5(md5(I('password')).$salt);
            }
            if(date("yyyy-mm-dd",$nowTime)!=$dataArr['birthday']){
                $dataArr['birthday'] = strtotime($dataArr['birthday']);
            }else{
                $dataArr['birthday'] = 0;
            }
            if($action == 'edit') {
                $status = $usersModel->where(array('uid' => $id))->save($dataArr);
                if ($status !== false) {
                    add_admin_log('edit', session('admin.admin_name') . '成功更新了ID为' . $id . '的会员信息!');
                    jscript_msg("修改成功 ", $jumpUrl);
                }else{
                    jscript_msg_tips("修改失败！");
                }
            }else{
                $dataArr['reg_time'] = time();
                $dataArr['reg_ip'] = get_client_ip();
                $id = $usersModel->add($dataArr);
                if ($id > 0 ) {
                    add_admin_log('add', session('admin.admin_name') . '成功增加了ID为' . $id . '的会员信息!');
                    jscript_msg("添加成功 ", $jumpUrl);
                } else {
                    jscript_msg_tips("添加失败！");
                }
            }
        }else {
            $regionModel = D('Common/Region');
            if(F('Admin/City/province_list')){
                $provinceList = F('Admin/City/province_list');
            }else{
                $provinceList = $regionModel->getAreaList();
            }
            if ($action == 'edit') {
                $info = $usersModel->where("uid=$id")->find();
                $cityList = array();
                $townList = array();
                if($info['area_id']){
                    $areaInfo = $regionModel->getRegionInfo($info['area_id']);
                    if($areaInfo['pid']>0){
                        $cityInfo = $regionModel->getRegionInfo($areaInfo['pid']);
                        $townList = $regionModel->getAreaList($areaInfo['pid']);
                        $cityList = $regionModel->getAreaList($cityInfo['pid']);
                    }
                }
                $this->assign('provinceId',$cityInfo['pid']);
                $this->assign('cityId',$areaInfo['pid']);
                $this->assign('cityList',$cityList);
                $this->assign('townList',$townList);
            }
            $groupList = M('users_group')->order("gid asc")->select();
            $this->assign('provinceList',$provinceList);
            $this->assign('groupList',$groupList);
            $this->assign('info', $info);
            $this->assign('now_time', $nowTime);
            //具体操作类型
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->display();
        }
    }

    /*会员删除、启用、锁定*/
    public function usersDelAction(){
        $action = I('action','del');
        switch ($action){
            case 'enabled':
                $actionType = 'edit';
                break;
            case 'diabled':
                $actionType = 'edit';
                break;
            case 'auth':
                $actionType = 'edit';
                break;
            default:
                $actionType = 'delete';
                break;
        }
        //判断权限
        if(!parent::checkAdminRoleAction('users_manage', $actionType)){
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $idArr = I('ids');
        $condition['uid'] = array('in', $idArr);
        $usersModel = M('users');
        switch ($action){
            case 'enabled':
                $result = $usersModel->where($condition)->setField('is_lock','0');
                break;
            case 'diabled':
                $result = $usersModel->where($condition)->setField('is_lock','1');
                break;
            case 'auth':
                $result = $usersModel->where($condition)->setField('auth_status','2');
                break;
            default:
                $result = $usersModel->where($condition)->delete();
                break;
        }
        if($action=="enabled"||$action=="diabled"||$action=="auth"){
            $actionType = "edit";
            $errTips = "修改失败!";
            $successTips = "修改成功！";
            $msgInfo = '成功修改了' . $result . '个会员的状态，会员ID分别为：' . implode(',', $idArr) . '!';
        }else{
            $actionType = "del";
            $errTips = "删除失败!";
            $successTips = "删除成功！";
            $msgInfo = '成功删除了' . $result . '个会员，会员ID分别为：' . implode(',', $idArr) . '!';
        }
        if($result!==false){
            add_admin_log($actionType, session('admin.admin_name') . $msgInfo);
            jscript_msg($successTips, $returnUrl);
        }else{
            jscript_msg_tips($errTips);
        }
    }

    /*会员组别列表*/
    public function groupListAction(){
        if(!parent::checkAdminRoleAction('users_group_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $keywords = I('keywords');//关键词
        $p = I('get.p',1);
        if($keywords) {
            $condition['group_name'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }else{
            $condition['gid'] = array('gt',0);
        }
        $usersGroupModel = M('users_group');
        $count = $usersGroupModel->where($condition)->count();
        $page = new \Think\Page($count,C('COMMON_PAGE_NUM'),$pageCondition);
        $show = $page->show();
        $list = $usersGroupModel->where($condition)->order("gid ASC")->page($p,C('COMMON_PAGE_NUM'))->select();
        $this->assign("list",$list);
        $this->assign("show",$show);
        $this->assign('empty',"<tr><td colspan=\"10\" align=\"center\">暂时没有相关数据!</td></tr>");
        $this->display();
    }
    //添加会员组
    public function groupAddAction(){
        $action = I('action','add');//操作类型
        $id = I("id", 0);//会员组ID
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        if(!parent::checkAdminRoleAction('users_group_manage',$action)){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $usersGroupModel = M('users_group');
        if(IS_POST){
            $dataArr = I("data");
            $dataArr["is_lock"] = ($dataArr["is_lock"]=="on")?1:0;
            $dataArr['is_default'] = ($dataArr["is_default"]=="on")?1:0;
            $dataArr['is_upgrade'] = ($dataArr['is_upgrade']=="on")?1:0;
            $jumpUrl = I('returnUrl');
            if($action == 'edit') {
                $status = $usersGroupModel->where(array('gid' => $id))->save($dataArr);
                if ($status !== false) {
                    add_admin_log('edit', session('admin.admin_name') . '成功更新了ID为' . $id . '的会员组!');
                    jscript_msg("修改成功 ", $jumpUrl);
                }else{
                    jscript_msg_tips("修改失败！");
                }
            }else{
                $dataArr['add_time'] = time();
                $advId = $usersGroupModel->add($dataArr);
                if ($advId > 0 ) {
                    add_admin_log('add', session('admin.admin_name') . '成功增加了ID为' . $advId . '的会员组!');
                    jscript_msg("添加成功 ", $jumpUrl);
                } else {
                    jscript_msg_tips("添加失败！");
                }
            }
        }else {
            if ($action == 'edit') {
                $info = $usersGroupModel->where("gid=$id")->find();
            }
            $this->assign('info', $info);
            //具体操作类型
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->display();
        }
    }
    /*会员组删除*/
    public function groupDelAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('users_manage', 'delete')){
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $idArr = I('ids');
        $condition['gid'] = array('in', $idArr);
        $usersGroupModel = M('users_group');
        $result = $usersGroupModel->where($condition)->delete();
        if($result!==false){
            add_admin_log('del', session('admin.admin_name') . '成功删除了' . $result . '个会员组，会员组ID分别为：' . implode(',', $idArr) . '!');
            jscript_msg("删除成功", $returnUrl);
        }else{
            jscript_msg_tips("删除失败");
        }
    }

    /*会员日志列表*/
    public function noteListAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('users_note_manage','view')){
            $this->error('亲，您无权进行操作！');
        }
        $page = I('get.p',1);
        $keywords = I('keywords');
        $type = I('type',0);
        if($keywords) {
            switch($type){
                case 1:
                    $condition['user_name'] = array('like', '%' . $keywords . '%');
                    break;
                case 2:
                    $condition['user_id'] = $keywords;
                    break;
                default:
                    $condition['user_name|action_type|user_id'] = array('like', '%' . $keywords . '%');
                    break;
            }
            $pageCondition['keywords'] = $keywords;
        }else{
            $condition['id'] = array('gt',0);
        }
        $pageCondition['type'] = $type;
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
        $usersLogModel = M('users_log');
        $pageSize = C('COMMON_PAGE_NUM');
        $list = $usersLogModel->where($condition)->page($page,$pageSize)->select();
        $count      = $usersLogModel->where($condition)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,$pageSize,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $this->assign('list',$list);//管理员列表数据
        $this->assign('page',$show);//分页字符串
        $this->assign('keywords',$keywords);//搜索关键字
        $this->assign('type',$type);//搜索类型
        $this->assign('startTime',$startTime);//结束时间
        $this->assign('endTime',$endTime);//开始时间
        $this->assign('empty',"<tr><td colspan=\"5\" align=\"center\">暂时没有相关数据!</td></tr>");
        $this->display();
    }
    /*删除会员操作日志*/
    public function noteDelAction()
    {
        //判断权限
        if (!parent::checkAdminRoleAction('users_note_manage', 'delete')) {
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $startTime = I('txtStartTime');
        $endTime = I('txtEndTime');
        $usersLogModel = M('users_log');
        if($startTime!=""&&$endTime!=""){
            $startTime = strtotime($startTime);
            $endTime = strtotime($endTime);
            $condition['login_time'] = array('between',array($startTime,$endTime));
        }else{
            $curTime = time();
            $condition['login_time'] = array('between',array(array('exp','UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL 7 DAY))'),$curTime));
        }
        $delCount = $usersLogModel->where($condition)->delete();
        if($delCount>0){
            add_admin_log('del',session('admin.admin_name').'成功删除了'.$delCount.'条会员登录日志!');
            jscript_msg("删除成功! ",$returnUrl);
        }else{
            jscript_msg("删除失败! ",$returnUrl);
        }

    }

    /*会员消费日志列表*/
    public function purchaseListAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('users_purchase_manage','view')){
            $this->error('亲，您无权进行操作！');
        }
        $page = I('get.p',1);
        $keywords = I('keywords');
        $type = I('type',0);
        $ctype = I('ctype',-1);
        if($keywords) {
            switch($type){
                case 1:
                    $condition['user_name'] = array('like', '%' . $keywords . '%');
                    break;
                case 2:
                    $condition['user_id'] = $keywords;
                    break;
                default:
                    $condition['user_name|user_id'] = array('like', '%' . $keywords . '%');
                    break;
            }
            $pageCondition['keywords'] = $keywords;
        }else{
            $condition['log_id'] = array('gt',0);
        }
        //消费类型
        if($ctype!=-1&&is_numeric($ctype)){
            $condition['change_type'] = $ctype;
            $pageCondition['ctype'] = $ctype;
        }
        $pageCondition['type'] = $type;
        /*根据日期时间查找*/
        $startTime = I('startTime');//开始时间
        $endTime = I('endTime');//结束时间
        if($startTime){
            $sTime = strtotime($startTime);
            $condition['change_time'] = array('gt',$sTime);
            $pageCondition['startTime'] = $startTime;
        }
        if($endTime){
            $eTime = strtotime($endTime);
            $condition['change_time'] = array('lt',$eTime);
            $pageCondition['startTime'] = $endTime;
        }
        $accountLogModel = M('account_log');
        $pageSize = C('COMMON_PAGE_NUM');
        $list = $accountLogModel->alias("a")->field("a.*,u.user_name")->join("LEFT JOIN __USERS__ AS u ON a.user_id=u.uid")->where($condition)->page($page,$pageSize)->order("log_id desc")->select();
        echo $accountLogModel;
        did;
        $count      = $accountLogModel->alias("a")->field("a.*,u.user_name")->join("LEFT JOIN __USERS__ AS u ON a.user_id=u.uid")->where($condition)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,$pageSize,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $this->assign('list',$list);//管理员列表数据
        $this->assign('page',$show);//分页字符串
        $this->assign('keywords',$keywords);//搜索关键字
        $this->assign('type',$type);//搜索类型
        $this->assign('ctype',$ctype);//搜索类型
        $this->assign('startTime',$startTime);//结束时间
        $this->assign('endTime',$endTime);//开始时间
        $this->assign('empty',"<tr><td colspan=\"9\" align=\"center\">暂时没有相关数据!</td></tr>");
        $this->display();
    }

    /*会员组删除*/
    public function purchaseDelAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('users_purchase_manage', 'delete')){
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $idArr = I('ids');
        $condition['log_id'] = array('in', $idArr);
        $accountLogModel = M('account_log');
        $result = $accountLogModel->where($condition)->delete();
        if($result!==false){
            add_admin_log('del', session('admin.admin_name') . '成功删除了' . $result . '条消费日志，日志ID分别为：' . implode(',', $idArr) . '!');
            jscript_msg("删除成功", $returnUrl);
        }else{
            jscript_msg_tips("删除失败");
        }
    }

    /*会员设置
	 *
	 * */
    public function usersSettingAction(){
        if(IS_POST){
            if(!parent::checkAdminRoleAction('users_setting_manage','edit')){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $dataList = array();
            $data = I("data");
            $arr_keys = array_keys($data);
            //索引值小写转大写
            for($i = 0; $i < count($data); $i++){
                if($arr_keys[$i] != 'btnSubmit') {
                    $dataList[strtoupper($arr_keys[$i])] = $data[$arr_keys[$i]];
                }
            }
            //格式化配置数组
            foreach($dataList as $key => $vo){
                $str .= "'".$key."'".'=>'."'".$vo."'".','."\r\n\t";
            }
            $str = '<?php return array('."\r\n\t".$str.');'."\r\n".'?>';
            file_put_contents(APP_PATH.'/Common/Conf/users_config.php',$str);
            //写入系统日志
            add_admin_log('edit',session('admin.admin_name').'成功修改了会员配置!');
            jscript_msg("修改成功 ",U("Admin/Users/usersSetting"));
        }else {
            if(!parent::checkAdminRoleAction('users_setting_manage','view')){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $this->display();
        }
    }
}