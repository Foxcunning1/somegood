<?php
 
/**
 * 微信授权相关接口
 * 
 */
 
class Wechat {
    private $app_id;
    private $app_secret;
    private $url;

    
    //高级功能-》开发者模式-》获取
    public function __construct($app_id, $app_secret, $url) {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->url = $url;
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
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);//SSL类型
 
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
