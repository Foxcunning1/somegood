<?php
 
/**微信接口
 * 微信公众号接口
 * 微信公众号菜单接口
 * 微信公众号关注回复接口
 * User:John.Song
 * Email:web59i@vip.qq.com
 * Date:2017/3/2
 * 
 */
 
class WxApi {
	private $token ='';//令牌，微信公众号开发者中心配置
    private $appid = '';//微信公众号appid
    private $appsecret = '';//
    private $url ='';
    private $data = array();
    const   MSG_TYPE_TEXT = 'text';
    
    //高级功能-》开发者模式-》获取
    public function __construct($token, $appid='', $appsecret='', $url) {
        $this->token = $token;
        $this->appid = $appid;
        $this->appsecret = $appsecret;
        $this->url = $url;
        if($_GET["echostr"]){
        	$this->checkToken();
        }
        $this->getApiData();
    }
	
	/** 微信公众号接口配置信息，验证服务器地址的有效性，验证错误，公众平台将显示配置失败
	 * @param     string        signature                  微信GET请求，微信加密签名
	 * @param     string        timestamp                  微信GET请求，时间戳
	 * @param     string        nonce                      微信GET请求，随机数
	 * @param     string        echostr                    微信GET请求，随机字符串 
	 * */
	 private  function checkToken(){
	 	$signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET["echostr"];
        $tmpArr = array($this->token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);//数组排序，微信开发要求
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1( $tmpStr );//sha1加密
		if( $tmpStr == $signature ){//加密签名对比
			echo $echostr;
		}
	 }

	/**接口消息封装,获取消息
	 * @param          String           php://input       获取    
	 * */
	public function getApiData(){
		ini_set('always_populate_raw_post_data',-1);
		//$xmlString = $GLOBALS['always_populate_raw_post_data'];//5.6版本已废弃always_populate_raw_post_data
		$xmlString = file_get_contents('php://input');//5.6版本以上
		libxml_disable_entity_loader(true);
		$xmlObj = simplexml_load_string($xmlString,'SimpleXMLElement',LIBXML_NOCDATA);//转换字符串为xml格式
		foreach($xmlObj as $key => $value){
			$this->data[$key] = strval($value);
		}
		//用户发送信息
		/*if($postObj->MsgType=='text'){
			echo $postObj->content;
		}*/
	}
	/**接口消息封装,返回消息
	 * @param        String            $msgtype           //信息类型
	 * @param        String            $content           //消息内容
	 * echo          xml                                  //输出返回消息xml
	 * */
	public function replyMessage($content, $msgtype = self::MSG_TYPE_TEXT){
		$data = array(
			'ToUserName'    => $this->data['FromUserName'],
			'FromUserName'  => $this->data['ToUserName'],
			'CreateTime'    => time(),
			'MsgType'       => $msgtype
		);
		$content = call_user_func(array(self,$msgtype),$content);//回调本类方法中的$msgtype方法
		if($msgtype = self::MSG_TYPE_TEXT){
			
		}
		$data = array_merge($data,$content);
		return $data;
	}
	
	/**数据转xml
	 * 
	 * **/
	private static function dataXml($xml,$data){
		foreach($data as $key => $value){
			$child = $xml->addChild($key);
			$node = dom_import_simplexml($child);
			$cData = $node->ownerDocument->crateCDATASection($value);
			$node->appendChild($cData);
		}
	}
	/**发送文本回复消息
	 * @param    string         $content            回复消息的内容
	 * return    array          $data               以数组的形式返回          
	 * */
	private static function text($content){
		$data['Content'] = $content;
		return $data;
	} 
	
	
    /**
     * @param string $state
     * @return string
     */
    public function getAuthorizeUrl($state = '',$scope='snsapi_userinfo')
    {
        $redirect_uri = urlencode($this->url);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
    }

    /**
     * @param string $code
     * @return bool|mixed
     */
    public function getAccessToken($code = '')
    {
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$code}&grant_type=authorization_code";
        $token_data = $this->http($token_url);
        
        if($token_data[0] == 200)
        {
            return json_decode($token_data[1], TRUE);
        }
        
        return FALSE;
    }

    /**
     * @param string $access_token
     * @param string $open_id
     * @return bool|mixed
     */
    public function getUserInfo($access_token = '', $open_id = '')
    {
        if($access_token && $open_id)
        {
            $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
            $info_data = $this->http($info_url);
            
            if($info_data[0] == 200)
            {
                return json_decode($info_data[1], TRUE);
            }
        }
        
        return FALSE;
    }
    
    public function http($url, $method='POST', $postfields = null, $headers = array(), $debug = false)
    {
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
 
        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
 
        $response = curl_exec($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
 
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
 
            echo '=====info=====' . "\r\n";
            print_r(curl_getinfo($ci));
 
            echo '=====$response=====' . "\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response);
    }
 
}
