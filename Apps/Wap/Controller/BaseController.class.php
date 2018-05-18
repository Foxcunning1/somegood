<?php
namespace Wap\Controller;
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
         $this->checkUsersCityAction();//获取用户所在位置，站点进行自动切换
         $this->checkUsersSessionAction();//检测是否微信登录

     }
    //验证用户是否微信登录
    public function checkUsersSessionAction()
    {
        if (is_weixin()&&!session('mobile.id')&&!session('mobile.openid')) {//微信浏览器，session不存在
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
                $usersWeixinModel = D('UsersWeixin');
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
                    $usersModel->setLoginCookie($userInfo['uid'],'shop',$userInfo['group_id'],$last_time,$last_ip,$userInfo['mobile'],$userInfo['user_name'],$userInfo['sid']);
                    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
                    header("Location: $url");
                    die;
                }/*else{//未绑定提醒用户绑定
                    $this->redirect('Mobile/Login/bindMobile');
                    die;
                }*/
            }
        }/*else{
            if (!session('mobile.id') || !session('?mobile') || !session('?gid')) {
                if (cookie("auth")) {
                    $check = D("Mobile/Users");
                    $is_long = $check->checkIsLogin();
                    if ($is_long === false) {
                        $this->error('当前用户未登录或登录超时，请重新登录！', U('login/index'));
                    } else {
                        session("username", $is_long['user_name']);
                        session("id", $is_long['uid']);
                    }
                } else {
                    $this->error('当前用户未登录，请重新登录！', U('login/index'));
                }
            }
        }*/

    }
    /*获取用户所在城市*/
    public function checkUsersCityAction(){
        $cityId = cookie("city_id");//当前城市id
        if($cityId){//判断cookie中是否存在城市id
            //根据ip获取的当前城市的id
            $IpClass = new \Org\Net\IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
            $ip = get_client_ip();
            if(!session("lng")||!session("lat")||!session("city_name")){
                $mapInfo = get_user_map_info($ip);//获取用户的经纬度信息
            }
            $result = $IpClass->getlocation('183.39.158.206');
            $province = $result['province'];
            $city = $result['city'];
            if (in_array($province, array('北京市', '上海市', '天津市', '重庆市'))) {
                $cityName = mb_substr($province, 0, -1, 'utf-8');
            } else {
                if (in_array($city, array('香港', '澳门'))) {
                    $cityName = $city;
                } else {
                    $cityName = mb_substr($city, 0, -1, 'utf-8');
                }
            }
            $regionModel = D('Region');
            $regionInfo = $regionModel->getRegionInfoByName($cityName);
            if($regionInfo){
                if($regionInfo['is_open']){
                    $cityId = $regionInfo['id'];
                }else{
                    $cityId = 1988;
                }
            }else{
                $cityId = 1988;
            }
        }
        //根据cookie判断当前城市是否已开通城市站，如果没开通，则自动切换至默认站点
        $openCityList = C("CITY_LIST");
        if(!in_array($cityId,$openCityList)){
            $cityId = 1988;//默认城市深圳
            cookie("city_id",$cityId);//把深圳的城市id写入cookie
        }
    }
}

?>
