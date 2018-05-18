<?php
/*后台默认页类
 *
 * */
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class IndexController extends BaseController {
    //首页默认
    public function indexAction(){
        $this->display();
    }

    /*后台默认管理中心
     *
     * */
    public function centerAction(){
        //获取管理员登录信息
        $adminModel = M('admin');
        $admin_id = session('admin.admin_id');
        $login_info = $adminModel->where("id=$admin_id")->find();
        $login_info['current_ip'] = get_client_ip();
        $this->assign('login_info',$login_info);
        $db       = new Model();
        $db_version     = $db->query('select version()');
        $db_version     = $db_version? $db_version[0]['version()']: @mysql_get_server_info();
        $db_version    = explode('-', $db_version);
        $db_version    = $db_version[0]; //输出: 5.0.45
        /*系统信息*/
        $info = array(
            'os'                     => PHP_OS,//服务器操作系统
            'web_server'             => $_SERVER["SERVER_SOFTWARE"],//Web运行环境
            'web_path'               => __ROOT__,//软件安装路径
            'php_version'            => PHP_VERSION,//PHP版本
            'php_sapi'               => php_sapi_name(),//PHP运行方式
            'service_date'           => date("Y年n月j日 H:i:s"),//服务器时间
            'beijing_gt'             => gmdate("Y年n月j日 H:i:s",time()+8*3600),//北京时间
            'ip'                     => $_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',//服务器域名和IP
            'register_globals'       => get_cfg_var("register_globals")=="1" ? "ON" : "OFF",//是否启用注册全局变量
            'magic_quotes_gpc'       => (1===get_magic_quotes_gpc())?'YES':'NO',//PHP环境变量是否开启，特殊字符是否转义（GET,POST方法）
            'magic_quotes_runtime'   => (1===get_magic_quotes_runtime())?'YES':'NO',//
            'zlib'                   => function_exists('gzclose')?'支持':'不支持',//是否开启压缩方式
            'safe_mode'              => (boolean) ini_get('safe_mode')?'支持':'不支持',//是否启用安全模式
            'safe_mode_gid'          => (boolean) ini_get('safe_mode_gid')?'支持':'不支持',//是否启用用户组安全
            'socket'                 => function_exists('fsockopen') ?'支持':'不支持',//是否支持Socket
            'timezone'               => function_exists("date_default_timezone_get")?date_default_timezone_get():'暂无时区信息',//时区
            'language'               => $_SERVER['HTTP_ACCEPT_LANGUAGE'],//服务区语言
            'port'                   => $_SERVER['SERVER_PORT'], //获取服务器Web端口
            'max_upload'             => ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled", //最大上传
            'max_ex_time'            => ini_get("max_execution_time")."秒", //脚本最大执行时间
            'mysql_version'          => $db_version,//MYSQL版本
        );
        $this->assign('info',$info);
        $this->display();
    }

    /*
     * ajax获取系统菜单
     */
    public function ajaxAction(){
        parent::ajaxAction();
    }

    /*
     * 获取管理员通知
     */
    public function getNoticeAction(){
        if (IS_AJAX){
            $noticeModel=M('notice');
            //本次查询时间
            $condition['time']=array('lt',time());
            $condition['to']='admin';
            $condition['is_readed']=0;
            //待审核店铺
            $condition['type']='auditStore';
            $result['audit_num']=$noticeModel->where($condition)->count();
            //卖家申请
            $condition['type']='auditSeller';
            $result['seller_num']=$noticeModel->where($condition)->count();
            //商品待投放
            $condition['type']='waitDelivery';
            $result['wait_num']=$noticeModel->where($condition)->count();
            $result['totalCount']=$result['audit_num']+$result['seller_num']+$result['wait_num'];
            $this->ajaxInfoReturn($result,'',1);
        }else{
            $this->tips('提交方式错误',0,3,$_SERVER['HTTP_REFERER']);
        }
    }
}