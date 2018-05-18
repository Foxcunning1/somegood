<?php
namespace Mobile\Controller;
use Think\Controller;
/*微信分享
 */
class WxShareController extends Controller{
    private $APPID;
    private $APPSECRET;
    //构造器
    public function __construct()
    {
        $this->APPID = C("wxconfig.app_id");
        $this->APPSECRET = C("wxconfig.app_secret");
        parent::__construct();
    }

    //获取微信token,并缓存token
    private function wxGetAccessToken(){
        $token = S('access_token');
        if (!$token) {
            $res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->APPID.'&secret='.$this->APPSECRET);
            $res = json_decode($res, true);
            $token = $res['access_token'];
            // 注意：这里需要将获取到的token缓存起来（或写到数据库中）
            // 不能频繁的访问https://api.weixin.qq.com/cgi-bin/token，每日有次数限制
            // 通过此接口返回的token的有效期目前为2小时。令牌失效后，JS-SDK也就不能用了。
            // 因此，这里将token值缓存1小时，比2小时小。缓存失效后，再从接口获取新的token，这样
            // 就可以避免token失效。
            // S()是ThinkPhp的缓存函数，如果使用的是不ThinkPhp框架，可以使用你的缓存函数，或使用数据库来保存。
            S('access_token', $token, 3600);
        }
        return $token;
    }
    //获取ticket
    function wxGetJsApiTicketAction(){
        $ticket = "";
        do{
            $ticket = S('wx_ticket');
            if (!empty($ticket)) {
                break;
            }
            $token = S('access_token');
            if (empty($token)){
                $this->wxGetAccessToken();
            }
            $token = S('access_token');
            if (empty($token)) {
                echo "get access token error.";
                break;
            }
            $url = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi",$token);
            $res = file_get_contents($url);
            $res = json_decode($res, true);
            $ticket = $res['ticket'];
            // 注意：这里需要将获取到的ticket缓存起来（或写到数据库中）
            // ticket和token一样，不能频繁的访问接口来获取，在每次获取后，我们把它保存起来。
            S('wx_ticket', $ticket, 3600);
        }while(0);
        return $ticket;
    }
    //微信分享获取签名信息
    public function  getWxShareApiInfoAction(){
        $url = I('url');
        $url = urldecode($url);
        if(session('mobile.id')) {
            $shareUrl = $url . "?uid=" . session('mobile.id');
        }else{
            $shareUrl = $url;
        }
        $data['timestamp'] = time();
        $data['nonceStr'] = "SOMEGOOD";
        $data['wx_ticket'] = $this->wxGetJsApiTicketAction();
        $wxOri = sprintf("jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s",$data['wx_ticket'], $data['nonceStr'], $data['timestamp'],$url);
        $data['signature'] = sha1($wxOri);
        $data['app_id'] = $this->APPID;
        $data['share_url'] = $shareUrl;
        $this->ajaxInfoReturn($data,'',0);
    }
}
?>
