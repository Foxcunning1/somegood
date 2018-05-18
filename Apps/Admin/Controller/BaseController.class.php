<?php

/**后台系统登录验证,ajax菜单获取类
 *
 */

namespace Admin\Controller;
use Think\Controller;
use Think\UploadFile;

class BaseController extends Controller {
        static public $treeList = array();
        public function __construct() {
            parent::__construct();
            $this->checkAdminSessionAction();
        }

        //验证用户是否登录
        public function checkAdminSessionAction() {
            if(C('WEB_STATUS'!='on')){//判断网站是否关闭
                $this->error(C('WEB_CLOSE_REASON'), '');
            }
            if(!session('?admin.admin_id')||!session('?admin.admin_name')||!session('?admin.admin_role_name')){
                if(!$this->checkCookiesAction()||(time() - session('admin.logintime')) > 3600){
                    session(null);
                    cookie('admin_id',null);
                    cookie('password',null);
                    cookie('admin_name',null);
                    cookie('admin_role_name',null);
                    $this->error('当前用户未登录或登录超时，请重新登录', U('login/index'));
                }
            }
        }
        /* session超时，获取本地cookies提交服务器验证
         * $id         管理员id
         * $password   管理员密码
         * $admin_name 管理员名字
         */
        public function checkCookiesAction(){
            if(cookie('id')!=null&&cookie('?password')!=null&&cookie('?admin_name')!=null/*&&cookie('?site_id')!=null*/){
                $id                    =  authcode(cookie('admin_id'), 'DECODE', C('AU_KEY'), 0);
                $password              =  authcode(cookie('password'), 'DECODE', C('AU_KEY'), 0);
                $admin_name            =  authcode(cookie('admin_name'), 'DECODE', C('AU_KEY'), 0);
                $admin_role_name       =  authcode(cookie('admin_role_name'), 'DECODE', C('AU_KEY'), 0);
                $adminModel = M('Admin');
                if($adminModel->where("id=$id")->count()>0){
                    $admin = $adminModel->where("id=$id")->find();
                    //验证cookies是否正确
                    if($password==md5($admin['password'].$admin['salt'])&&$admin_name==$admin['admin_name']){
                        session('admin.admin_id',$id);
                        session('admin.admin_name',$admin_name);
                        session('admin.admin_role_name',$admin_role_name);
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
        /* 获得二级分类
         *
         */
        public function getSystemSubChildsHtmlAction($list,$class_layer=2){
            if($list){
                $sub_html='';
                foreach($list as $key=>$val){
                    $sub_html.="<h2>".$val['title']."<i></i></h2>";
                    $childs = $this->getSystemNavChildsHtmlAction($val['childs']);
                    if($val['childs']){
                        $sub_html.= $this->getSystemNavChildsHtmlAction($val['childs']);
                    }
                }
                return $sub_html;
            }else{
                return '';
            }
        }
        /* 子类菜单列表
         * parm      $list        子类数组
         */
        public function getSystemNavChildsHtmlAction($list){

            if($list){
                $sub_html.="\r\n<ul>\r\n";
                $childs_html='';
                foreach($list as $key=>$val){
                    if($val['icon']!='')
                        $childs_html .= "<li><a navid=\"" . $val['name'] . "\" target=\"mainframe\" href=\"" . $val['link_url'] . "\" icon=\"" .PROJECT_PATH. substr(C("FILE_PATH"),1) . $val['icon'] . "\"";
                    else
                        $childs_html .= "<li><a navid=\"" . $val['name'] . "\" target=\"mainframe\" href=\"" . $val['link_url'] . "\"";
                    $childs_html.=" class=\"item\">\r\n";
                    $childs_html.="<span>".$val['title']."</span></a>\r\n";
                    $childs_html.= $this->getSystemNavChildsHtmlAction($val['childs']);
                    $childs_html.="</li>\r\n";
                }
                $sub_html.=$childs_html."</ul>\r\n";
                return $sub_html;
            }else{
                return '';
            }
        }

        /* 获得系统导航
          * parm     $parent_id                      父级ID
          *
          */
        public function getSystemNavListAction($parent_id=0){
            //获得父级系统菜单
            $SystemNav = M('system_nav');
            $result = $SystemNav->where(array('parent_id' => $parent_id , 'is_lock'=> 0/*, 'site_id' => $this->site_id*/))->order('sort_num ASC, id DESC')->select();
            $list = array();
            //遍历导航，判断导航是否有权限
            foreach($result AS $key=>$v){
                if($this->checkAdminRoleAction($v['name'],'show')){
                    array_push($list,$result[$key]);
                }
            }
            if($list){
                foreach($list AS $k=>$val){
                    if($childs = $this->getSystemNavListAction($val['id'])){
                        //判断当前用户的权限
                        if($this->checkAdminRoleAction($val['name'],'show')){
                            $list[$k]['childs'] = $childs;
                        }
                    }
                }
            }
            return $list;
        }
        /* 检查用户操作权限
         * parm     $nav_name                       当前操作的导航菜单名称
         * parm     $action_type                    当前操作的类型
         *
         */
        public function checkAdminRoleAction($nav_name,$action_type){
            //获得管理员所属角色ID

            $r_id = $this->getAdminRoleTypeIdAction();
            $is_role = false;//设置返回默认值
            if($r_id>0)
            {
                //获得管理员角色
                $Role = M("admin_role");
                $roles = $Role->where('role_id='.$r_id)->find();
                //获得管理员角色组的权限
                $RoleValue = M("admin_role_value");
                $role_values = $RoleValue->where(array('role_id' => $r_id /*, 'site_id' => $this->site_id*/))->select();
                if($role_values){
                    foreach($role_values AS $k=>$val){//遍历当前角色的权限，并判断当前类的操作权限
                        if($val['nav_name']==$nav_name&&$val['action_type']==$action_type){
                            $is_role = true;
                            break;
                        }
                    }
                }else{
                    if($roles['role_type']==1){
                        $is_role = true;
                    }
                }
            }
            return $is_role;
        }
        /*获取管理员的角色组id
         */
        public function getAdminRoleTypeIdAction(){
            if(session('admin.admin_id')){
                //获取管理员权限组类型id
                $Admin = M("admin");
                $where['id'] = session('admin.admin_id');
                $where['is_lock'] = 0;
                $r_id = $Admin->where($where)->getField("r_id");
                return $r_id;
            }else{//管理员未登录
                return 0;
            }
        }

        /** 无限极分类数据排序
         * parm       $data             array             所有分类数据
         * parm       $parent_id        int               父类ID
         * parm       $count            int               第几级分类
         */
        static function treeAction(&$data,$parent_id = 0,$count = 1){
            foreach ($data as $key => $value){
                if($value['parent_id']==$parent_id){
                    $value['count'] = $count;
                    self::$treeList []=$value;
                    unset($data[$key]);
                    self::treeAction($data,$value['id'],$count+1);
                }
            }
            return self::$treeList ;
        }

        /**
         * 文件上传
         */
        /*public function uploadFileAction() {
            $action = I('action','strip_tags','');
            import('Vendor.UploadFile.UploadFile');
            $upload = new UploadFile();// 实例化上传类
            if(I('uploadType') == 'attach') $upload->maxSize   = C('ATTACH_SIZE') * 1024;// 设置附件上传大小
            elseif(I('uploadType') == 'video') $upload->maxSize   = C('VIDEO_SIZE') * 1024;
            else $upload->maxSize   = C('IMG_SIZE') * 1024;
            $upload->allowExts      = explode(',',C('FILE_EXTENSION'));// 设置附件上传类型
            $upload->saveRule       = 'uniqid';//这个是改变图片名称的，可同时改变多张图片的名称，实现图片的不同名 这样也就不会出现覆盖的现象了
            //$upload->saveRule='mytime';
            $upload->thumb          = C('PHOTOTHUMB');// 设置是否生成缩略图
            $upload->thumbMaxWidth  = C('THUMB_NAIL_WIDTH');// 缩略图宽度
            $upload->thumbMaxHeight = C('THUMB_NAIL_HEIGHT');// 缩略图高度
            $upload->hashLevel      = C('HASH_LEVEL');//
            $upload->savePath       = C('FILE_PATH');// 设置附件上传目录
            $upload->autoSub        = C('AUTO_SUB');// 子目录启用
            $upload->subType        = C('SUBTYPE');// 设置子路上传类型
            if(!$upload->upload()) {// 上传错误提示错误信息
                $this->error($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
                $info =  $upload->getUploadFileInfo();
            }
            if($action == 'EditorFile'){
                $data['error'] = 0;
                $data['url'] = PROJECT_PATH.substr(C('FILE_PATH'),1).$info[0]['savename'];
                echo json_encode($data);
            }else{
                $data['path'] = "/uploads/".$info[0]['savename'];
                $data['upfile'] = PROJECT_PATH.substr(C('FILE_PATH'),1);
                $data['status'] = 1;
                $this->ajaxInfoReturn($data);
            }
        }*/
    /**
     * 文件上传
     */
    public function uploadFileAction() {
        $action = I('get.action','');//上传入口，默认上传按钮，当值为EditorFile时，编辑器上传
        $isthumb = I('get.IsThumbnail',0);//是否生成缩略图
        $modelType = I('get.modeltype','article');//模型名字
        $fileType = I('get.uploadType','');
        $fileMaxSize = 1048756;
        $isimage = 0;
        switch($fileType){
            case 'video':
                $fileMaxSize = C('VIDEO_SIZE');
                break;
            case 'file':
                $fileMaxSize = C('ATTACH_SIZE');
                break;
            default:
                $fileMaxSize = C('IMG_SIZE');
                $isimage = 1;
                break;
        }
        $extsArr = explode(',', C('FILE_EXTENSION'));
        if(C('AUTO_SUB')=='on'){
            $autoSub = true;
        }else{
            $autoSub = false;
        }
        $uploadConfig = array(
            'maxSize'    =>    $fileMaxSize,
            'rootPath'   =>    C('FILE_PATH'),
            'savePath'   =>    '',
            'saveName'   =>    array('uniqid',''),
            'exts'       =>    $extsArr,
            'autoSub'    =>    $autoSub,
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($uploadConfig);
        $info   =   $upload->upload();

        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            foreach($info as $attach){
                $fileInfo['name'] = $attach['name'];
                $fileInfo['savename'] = $attach['savename'];
                $fileInfo['size'] = $attach['size'];
                $fileInfo['ext'] = $attach['ext'];
                $fileInfo['md5'] = $attach['md5'];
                $fileInfo['savepath'] = $attach['savepath'];
            }
            $attachementModel = M('attachment');
            $file['modelid'] = C('MODEL_TYPE.'.$modelType);
            $file['catid'] = 0;
            $file['id'] = 0;
            $file['filename'] = $fileInfo['name'];
            $file['filepath'] = $fileInfo['savepath'].$fileInfo['savename'];
            $file['filesize'] = $fileInfo['size'];
            $file['fileext'] = $fileInfo['ext'];
            $file['isimage'] = $isimage;
            $file['isthumb'] = $isthumb;
            $file['userid'] = session('admin.admin_id');
            $file['createtime'] = time();
            $file['uploadip'] = get_client_ip();
            $file['status'] = 1;
            $file['authcode'] = $fileInfo['md5'];
            $aid = $attachementModel->data($file)->add();
            if($action == 'EditorFile'){
                $data['error'] = 0;
                $data['url'] = substr(C('FILE_PATH'),1).$fileInfo['savepath'].$fileInfo['savename'];
                $data['aid'] = $aid;
                echo json_encode($data);
            }else{
                $data['path'] = $fileInfo['savepath'].$fileInfo['savename'];
                $data['upfile'] = PROJECT_PATH.substr(C('FILE_PATH'),1);
                $data['status'] = 1;
                $data['aid'] = $aid;
                $this->ajaxInfoReturn($data);
            }
        }
    }

        /** 后台图片删除
         */
        public function del() {
            $src=str_replace(__ROOT__.'/', '', str_replace('//', '/', $_GET['src']));
            if (file_exists($src)){
                unlink($src);
            }
            print_r($_GET['src']);
            exit();
        }
        
        public function getRegionAction(){
            $pid = $this->_param('pid','strip_tags',0);
            $type = $this->_param('type','strip_tags',0);
            $region=D("Home/Region");
            $list = $region->getRegionList($pid,$type);
            echo json_encode($list);
        }

        /** 无限极分类数据排序
         * parm       $data             array             所有分类数据
         * parm       $parent_id              int         父类ID
         * parm       $count            int               第几级分类
         */
        public function tree1Action(&$data,$pid = 0,$count = 1){
            foreach ($data as $key => $value){
                if($value['pid']==$pid){
                    $value['count'] = $count;
                    self::$treeList []=$value;
                    unset($data[$key]);
                    self::tree1Action($data,$value['id'],$count+1);
                }
            }
            return self::$treeList ;
        }

    }