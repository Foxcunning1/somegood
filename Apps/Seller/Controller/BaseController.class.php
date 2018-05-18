<?php

/**卖家系统登录验证
 *
 */

namespace Seller\Controller;
use Think\Controller;

class BaseController extends Controller {
        public function __construct() {
            parent::__construct();
            $this->checkStoreSessionAction();
            C('TOKEN_ON',false);

        }

        //验证用户是否登录
        public function checkStoreSessionAction() {
            if(!session('seller.id')||!session('seller.mobile')||!session('seller.gid')){
                if(!$this->checkCookiesAction()){
                    session(null);
                    cookie('user_id',null);
                    cookie('user_pw',null);
                    cookie('mobile',null);
                    $this->error('当前用户未登录或登录超时，请重新登录', U('Seller/Login/index'));
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
                    session('seller.id',$user['uid']);
                    session('seller.mobile',$user['mobile']);
                    session('seller.gid',$user['gid']);
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
            $file['userid'] = 1;
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
}
