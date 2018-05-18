<?php
namespace Crm\Controller;
use Think\Controller;
/**手机版网站基类
 *
 */
class BaseController extends Controller
{
    private $APPID;
    private $APPSECRET;

     public function __construct()
     {
         $this->APPID = C('wxconfig.app_id');
         $this->APPSECRET = C('wxconfig.app_secret');
         parent::__construct();
         $this->checkUsersSessionAction();//检测是否微信登录

     }
    //验证用户是否微信登录
    public function checkUsersSessionAction()
    {
        if (is_weixin()&&!session('mobile.id')) {//微信浏览器，session不存在
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
            $openid = $at['openid'];//用户微信openid
            $headimgurl = $at['headimgurl'];//用户微信头像地址
            $nickname = $at['nickname'];//用户微信昵称
            //把openid以session形式存储，方便绑定时调用
            session('mobile.openid',$openid);
            //$openid = $wxUsersInfo['openid'];
            if($openid!=""){
                $usersWeixinModel = D('Common/UsersWeixin');
                $nowTime = time();
                if(!$usersWeixinModel->isOpenidExits($openid)){//查询当前用户是否在微信用户表中存在，不存在则添加
                    //判断浏览器是否带有用户id信息
                    $fromUid = I("uid",0);
                    if($fromUid>0){
                        $wxData['from_uid'] = $fromUid;
                    }
                    $wxData['openid'] = $openid;
                    $wxData['headimgurl'] = $headimgurl;
                    $wxData['nickname'] = $nickname;
                    $wxData['add_time'] = $wxData['last_time'] = $nowTime;
                    $usersWeixinModel->add($wxData);
                }else{//更新当前微信用户最后一次登录时间
                    $wxData['last_time'] = $nowTime;
                    $wxData['headimgurl'] = $headimgurl;
                    $wxData['nickname'] = $nickname;
                    $usersWeixinModel->save($wxData,$openid);
                }
                $wxInfo = $usersWeixinModel->getWeixinUsersInfo($openid);
                if($wxInfo['uid']>0){//用户已绑定会员
                    //通过uid获取用户信息
                    $usersModel = D("Common/Users");
                    $userInfo = $usersModel->getUsersInfo($wxInfo['uid']);
                    $last_time = time();
                    $last_ip = get_client_ip();
                    //写入用户登录信息
                    $usersModel->setLoginCookie($userInfo['uid'],'mobile',$userInfo['group_id'],$last_time,$last_ip,$userInfo['mobile'],$userInfo['user_name'],$userInfo['sid']);
                    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
                    header("Location: $url");
                    die;
                }
            }
        }
    }
}

?>
