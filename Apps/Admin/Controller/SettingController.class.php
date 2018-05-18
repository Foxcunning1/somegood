<?php
/** 系统导航菜单管理类，此类需继承BaseController类,用户是否登录验证
 *
 */

namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class SettingController extends BaseController{
    public function indexAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('system_nav_manage','view')){
            $this->error('亲，您无权进行操作！');
        }
        //初始化后台系统导航列表
        $systemNav = M('system_nav');
        $list = parent::treeAction($systemNav->order('parent_id ASC,sort_num ASC, id DESC')->select(),0,1);
        $this->assign('list',$list);
        $this->display();
    }
    /**修改或添加系统菜单
     * parm     string        $action               操作类型，默认add（添加）
     */
    public function addAction(){
        $action         = I('action','add','strip_tags');//操作类型
        //判断权限
        if(!parent::checkAdminRoleAction('system_nav_manage','add')){
            $this->error('亲，您无权进行操作！');
        }
        $id         = I('id',0,'strip_tags');//导航ID
        //初始化后台系统导航列表
        $systemNav = M('system_nav');
        $nav_list = parent::treeAction($systemNav->order('parent_id ASC, id DESC')->select(),0,1);
        $this->assign('nav_list',$nav_list);
        if($action == 'edit'){
            //实例化数据
            $info   = $systemNav->find($id);
        }
        if($action =='add'){
            $info['parent_id'] = $id;
            $info['sort_num'] = 99;
        }
        //获取系统所有分类
        $this->assign('action',$action);
        $this->assign('info',$info);
        $this->display();
    }
    /**修改或添加系统菜单
     * parm     string        $action       操作类型，默认add（添加）
     */
    public function doneAction(){

        $action         = I('action','add','strip_tags');//操作类型
        //判断权限
        if(!parent::checkAdminRoleAction('system_nav_manage',$action)){
            $this->error('亲，您无权进行操作！');
        }
        if(IS_POST){
            $data['site_id']         = $this->site_id;
            $data['nav_type']        = 'System';//导航类型
            $data['name']            = I('name');//导航英文名称
            $data['title']           = I('title');//导航中问名称
            $data['sub_title']       = I('sub_title');//导航别名
            $data['link_url']        = I('link_url');//导航对应的链接
            $data['sort_num']        = I('sort_num');//导航排序
            $data['is_lock']         = (I('is_lock')==false)?0:1;//是否锁定导航
            $data['remark']          = I('remark');//备注
            $data['parent_id']       = I('parent_id','strip_tags',0);//父类ID
            $data['icon']            = I('txtIconUrl');
            //获得导航层级关系
            $class_list              = get_deep_table_class_list('system_nav',$data['parent_id']);
            $data['class_list']      = $class_list;//导航层级关系
            //获得父类的层级数量
            $class_layer             = get_deep_table_class_layer('system_nav',$data['parent_id']);
            $data['class_layer']     = $class_layer + 1;//导航层级数量
            $data['channel_id']      = I('channel_id',0,'strip_tags');//频道ID
            $data['action_type']     = implode(',',I('action_type'));//导航操作类型
            $data['is_sys']          = 0;//是否系统导航
            //实例化模型
            $systemNav = M('system_nav');
            //修改操作
            if($action == 'edit'){
                $id              = I('id');
                if(false !== $systemNav->where("id=$id")->save($data)){
                    if($data['class_list']==""){
                        $class_list = ",".$id.",";
                    }else{
                        $class_list = $class_list.$id.",";
                    }
                    $systemNav->where("id=$id")->setField('class_list',$class_list);
                    //写入系统日志
                    add_admin_log('edit',session('admin_name').'成功更新了ID为'.$id.'的导航信息!');
                    jcscript_msg("修改成功 ",U("Setting/index"),"parent.loadMenuTree");
                    //echo $this->success("修改成功!",U('Setting/index'));
                }else{
                    jscript_msg("修改失败 ",U("Setting/add" , array('action' => 'edit' , 'id' => $id)));
                }

            }else{
                $sid=$systemNav->add($data);
                if($sid>0){
                    if($data['class_list']==""){
                        $class_list = ",".$sid.",";
                    }else{
                        $class_list = $class_list.$sid.",";
                    }
                    $systemNav->where("id=$sid")->setField('class_list',$class_list);
                    //写入系统日志
                    add_admin_log('add',session('admin.admin_name').'成功添加了ID为'.$sid.'的导航信息!');
                    jcscript_msg("增加成功 ",U("Setting/index"),"parent.loadMenuTree");
                }else{
                    jscript_msg("增加失败 ",U("Setting/add"));
                }
            }

        }
    }
    /**批量删除导航信息
     * parm     string        $ids               导航ID字符串
     */
    public function delAction(){
//     	//判断权限
        if(!parent::checkAdminRoleAction('system_nav_manage','delete')){
            $this->error('亲，您无权进行操作！');
        }
        $ids        = I('ids');
        $systemNav  = M('system_nav');
        //判断id是数组还是一个数值
        $count_record = 0;
        if(is_array($ids)){
            //$where = 'id in('.implode(',',$ids).')';
            for($i=0;$i<count($ids);$i++){
                $condition["class_list"] = array("like", "%,".$ids[$i].",%");
                $del_count = $systemNav->where($condition)->delete();
                if($del_count!==false){
                    $count_record += $del_count;
                }else{
                    $count_record = false;
                }
            }
        }else{
            $condition["class_list"] = array("like", "%,".$ids.",%");
            $count_record = $systemNav->where($condition)->delete();
        }
        if($count_record!==false) {
            add_admin_log('del',session('admin.admin_name').'成功删除了'.$count_record.'条系统导航信息!');
            jscript_msg("删除成功 ",U("Setting/index"));
        }else{
            jscript_msg("删除失败 ",U("Setting/index"));
        }
    }

    /**
     * 系统设置包括系统基本信息、功能权限设置、短信平台设置、邮件发送设置、留言接受设置、文件上传设置
     */
    public function systemConfigAction(){
        if(IS_POST){
            if(!parent::checkAdminRoleAction('system_config_manage','edit')){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $dataList = array();
            $arr_keys = array_keys(I());
            $interval = I('interval');
            cookie('interval',$interval);
            //索引值小写转大写
            for($i = 0; $i < count(I()); $i++){
                if($arr_keys[$i] != 'btnSubmit') {
                    $dataList[strtoupper($arr_keys[$i])] = $_POST[$arr_keys[$i]];
                }
            }
            //格式化配置数组
            foreach($dataList as $key => $vo){
                $str .= "'".$key."'".'=>'."'".$vo."'".','."\r\n\t";
            }
            $str = '<?php return array('."\r\n\t".$str.');'."\r\n".'?>';
            file_put_contents(APP_PATH.'/Common/Conf/system_config.php',$str);
            //写入系统日志
            add_admin_log('edit',session('admin_name').'成功修改了系统配置!');
            jscript_msg("修改成功 ",U("Admin/Setting/systemConfig"));
            //F('data',$str,APP_PATH.'/Conf/');
        }else {
            if(!parent::checkAdminRoleAction('system_config','view')){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $status = array(
                'staticStatus' => C('STATIC_STATUS'),
                'memberStatus' => C('MEMBER_STATUS'),
                'commentStatus' => C('COMMENT_STATUS'),
                'logStatus' => C('LOG_STATUS'),
                'webStatus' => C('WEB_STATUS'),
                'visitStatus' => C('VISIT_STATUS'),
                'sslStatus' => C('EMAIL_SSL'),
                'fileSave' => C('FILE_SAVE'),
                'fileRemote' => C('FILE_REMOTE'),
                'thumbNailMode' => C('THUMB_NAIL_MODE'),
                'waterMarkType' => C('WATER_MARK_TYPE'),
                'waterMarkPosition' => C('WATER_MARK_POSITION'),
                'waterMarkFont' => C('WATER_MARK_FONT'),
                'subType' => C('SUBTYPE'),
                'autoSub' => C('AUTO_SUB'),
            );
            $this->assign('status',$status);
            $this->assign('static_status_list',C('STATIC_STATUS_LIST'));
            $this->assign('file_save_list',C('FILE_SAVE_LIST'));
            $this->assign('file_remote_list',C('FILE_REMOTE_LIST'));
            $this->assign('thumb_nail_model_list',C('THUMB_NAIL_MODEL_LIST'));
            $this->assign('water_mark_type_list',C('WATER_MARK_TYPE_LIST'));
            $this->assign('water_mark_position_list',C('WATER_MARK_POSITION_LIST'));
            $this->assign('water_mark_font_list',C('WATER_MARK_FONT_LIST'));
            $this->assign('subtype_list',C('SUBTYPE_LIST'));
            $this->display();
        }
    }

    /*后台设置轮询提醒时间
     * */
    public function informAction(){
        if(IS_POST){
            $interval = I('interval');
            $noInform = I('no_inform');
            cookie('interval',$interval*60*1000);
            cookie('noInform',$noInform);
            jscript_msg("修改成功 ",U("Setting/inform"));
        }else{
            $interval = cookie('interval')/60/1000;
            $noInform = cookie('noInform');
            if($noInform == null) $noInform = 0;
            $this->assign('interval',$interval);
            $this->assign('noInform',$noInform);
            $this->display();
        }
    }
}