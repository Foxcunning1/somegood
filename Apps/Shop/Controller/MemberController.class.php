<?php
namespace Mobile\Controller;
use Think\Controller;
/**后台系统登录验证类
 *
 */
class MemberController extends Controller
{
    private $APPID;
    private $APPSECRET;

    // public function __construct()
    // {
    //      $this->APPID = C('wxconfig.app_id');
    //      $this->APPSECRET = C('wxconfig.app_secret');
    //      parent::__construct();
    //      $this->checkUsersSession();
    // }

    //验证用户是否登录
    public function checkUsersSession()
    {
        if (is_weixin()&&!session('?mobile.id')) {//微信浏览器，session不存在
            Vendor('WeChat.Wechat');
            Vendor('WeChat.JSSDK');
            //获取openid
            $wechat = new \Wechat($this->APPID,$this->APPSECRET, 'http://'.$_SERVER['HTTP_HOST']. $_SERVER["REQUEST_URI"]);
            $wxCode = I('get.code');
            //print_r($wxCode);die;
            if (!$wxCode) {
                $authUrl = $wechat->getAuthorizeUrl(1,'snsapi_base');
                header("Location: $authUrl");
                die();
            }
            $at = $wechat->getAccessToken($wxCode);//根据code获取accessToken
            //$wxUsersInfo = $wechat->getUserInfo($at['access_token'], $at['openid']);//根据ACCESS_TOKEN获取用户信息
            //在新的微信表里通过openid查找是否存在，如果存在，看对应uid是否存在，存在查看mobile是否存在，存在的话登录，不存在的话，绑定手机号；如果uid不存在，提示绑定。
            //根据用户openid查询微信用户表
            $openid = $at['openid'];
            //把openid以session形式存储，方便绑定时调用
            session('mobile.openid',$openid);
            //$openid = $wxUsersInfo['openid'];
            if($openid!=""){
                $usersWeixinModel = D('UsersWeixin');
                $nowTime = time();
                if(!$usersWeixinModel->isOpenidExits($openid)){//查询当前用户是否在微信用户表中存在，不存在则添加
                    $wxData['openid'] = $openid;
                    $wxData['add_time'] = $wxData['last_time'] = $nowTime;
                    $usersWeixinModel->add($wxData);
                }else{//更新当前微信用户最后一次登录时间
                    $wxData['last_time'] = $nowTime;
                    $usersWeixinModel->save($wxData,$openid);
                }
                $wxInfo = $usersWeixinModel->getWeixinUsersInfo($openid);
                if($wxInfo['uid']>0){//用户已绑定会员
                    //通过uid获取用户信息
                    $usersModel = D('Common/Users');
                    $userInfo = $usersModel->getUsersInfo($wxInfo['uid']);
                    $last_time = time();
                    $last_ip = get_client_ip();
                    $usersModel->setLoginCookie($userInfo['uid'],$userInfo['group_id'],$last_time,$last_ip,$userInfo['mobile'],$userInfo['user_name']);
                    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
                    header("Location: $url");
                    die;
                }else{//未绑定提醒用户绑定
                    $this->redirect('Login/bindMobile');
                    die;
                }
            }
        }else{
            if (!session('?mobile.id') || !session('?mobile.mobile') || !session('?mobile.gid')) {
                if (cookie("auth")) {
                    $check = D("Common/Users");
                    $is_long = $check->checkIsLogin();
                    if ($is_long === false) {
                        $this->error('当前用户未登录或登录超时，请重新登录！', U('login/login'));
                    } else {
                        session("username", $is_long['user_name']);
                        session("id", $is_long['uid']);
                    }
                }else{
                    $this->error('当前用户未登录，请重新登录！', U('login/login'));
                }
            }
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

    /** 图片删除
     */
    public function del()
    {
        $src = str_replace(__ROOT__ . '/', '', str_replace('//', '/', $_GET['src']));
        if (file_exists($src)) {
            unlink($src);
        }
        print_r($_GET['src']);
        exit();
    }

    //上传头像
    public function uploadImg()
    {
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();                        // 实例化上传类
        $upload->maxSize = 1 * 1024 * 1024;                    //设置上传图片的大小
        $upload->allowExts = array('jpg', 'png', 'gif');    //设置上传图片的后缀
        $upload->uploadReplace = true;                    //同名则替换
        $upload->saveRule = 'avatar' . session('id');                    //设置上传头像命名规则(临时图片),修改了UploadFile上传类
        //完整的头像路径
        $path = C('FILE_PATH') . 'avatar/';
        //$path = substr($path,1);
        $upload->savePath = $path;
        if (!$upload->upload()) {                        // 上传错误提示错误信息
            $this->ajaxReturn('', $upload->getErrorMsg(), 0, 'json');
        } else {                                            // 上传成功 获取上传文件信息
            $info = $upload->getUploadFileInfo();
            $info[0]['savepath'] = substr($info[0]['savepath'], 1);
            $temp_size = getimagesize($path . 'avatar' . session('id') . $info[0]['extension']);
            /*if($temp_size[0] < 550 || $temp_size[1] < 550){//判断宽和高是否符合头像要求
                $this->ajaxReturn(0,'图片宽或高不得小于350px！',0);
            }*/
            $this->ajaxReturn(__ROOT__ . substr(C('FILE_PATH'), 1) . 'avatar/avatar' . session('id') . '.' . $info[0]['extension'], $info, 1);
        }
    }

    //裁剪并保存用户头像
    public function avatarCrop()
    {
        //图片裁剪数据
        $params = $this->_post();                        //裁剪参数
        if (!isset($params) && empty($params)) {
            die($this->ajaxValidReturn('', '非法上传！', 'n'));
        }
        //头像目录地址
        $path = C('FILE_PATH') . 'avatar/';
        //要保存的图片
        $real_path = $path . 'avatar' . session('id') . '.' . $params['hideFileExtension'];
        //临时图片地址
        $pic_path = $path . 'avatar' . session('id') . '.' . $params['hideFileExtension'];
        import('ORG.ThinkImage.ThinkImage');
        $Think_img = new ThinkImage(THINKIMAGE_GD);
        //裁剪原图
        $Think_img->open($pic_path)->crop($params['hideWidth'], $params['hideWidth'], $params['hideX1'], $params['hideY1'])->save($real_path);
        //生成缩略图
        $Think_img->open($real_path)->thumb(180, 180, 1)->save($path . '180_avatar' . session('id') . '.' . $params['hideFileExtension']);
        $Think_img->open($real_path)->thumb(60, 60, 1)->save($path . '60_avatar' . session('id') . '.' . $params['hideFileExtension']);
        $Think_img->open($real_path)->thumb(30, 30, 1)->save($path . '30_avatar' . session('id') . '.' . $params['hideFileExtension']);
        $user = D('Common/Users');
        if ($user->updateField('avatar', 'avatar' . session('id') . '.' . $params['hideFileExtension'])) {
            die($this->ajaxValidReturn('', '上传头像成功', 'y'));
        } else {
            die($this->ajaxValidReturn('', '上传头像失败', 'n'));
        }
    }

    /**判断字段值除了本身以外是否存在
     * parm       int           $id                    字段中ID
     * parm       string        $field_name            字段名称
     * parm       string        $field_value           字段值
     * */
    public function isExist($table_name, $id_name, $id, $field_name, $field_value)
    {
        if ($id > 0) {
            $arr[$id_name] = array('neq', $id);
        }
        $arr[$field_name] = $field_value;
        $Map = M($table_name);
        if (!is_null($Map->where($arr)->select())) {
            return true;
        } else {
            return false;
        }
    }

    public function getRegion()
    {
        $Region = M("Region");
        $map['pid'] = $_REQUEST["pid"];
        $map['type'] = $_REQUEST["type"];
        $list = $Region->where($map)->select();
        echo json_encode($list);
    }

    /** 无限极分类数据排序
     * parm       $data             array             所有分类数据
     * parm       $parent_id              int               父类ID
     * parm       $count            int               第几级分类
     */
    public function tree1(&$data, $pid = 0, $count = 1)
    {
        foreach ($data as $key => $value) {
            if ($value['pid'] == $pid) {
                $value['count'] = $count;
                self::$treeList [] = $value;
                unset($data[$key]);
                self::tree($data, $value['id'], $count + 1);
            }
        }
        return self::$treeList;
    }

}

?>
