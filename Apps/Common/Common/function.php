<?php


/** 后台用户登录日志通用方法
 *  parm       $action_type    用户操作类型
 *  parm       $note           用户操作类型说明
 */
function add_admin_log($action_type, $note)
{
    if(C('LOG_STATUS')=='on'){//后台是否开启记录日志
        $adminLogModel = M('Admin_log');
        $log['admin_id'] = session('admin.admin_id');
        $log['admin_name'] = session('admin.admin_name');
        $log['action_type'] = $action_type;
        $log['note'] = $note;
        $log['login_ip'] = get_client_ip();
        $log['login_time'] = time();
        if ($adminLogModel->add($log) > 0) {
            return true;
        } else {
            return false;
        }
    }

}

//秒数转换时分秒
function secondsToHour($seconds){
   if(intval($seconds) < 60)
        $tt ="00:00:".sprintf("%02d",intval($seconds%60));
   if(intval($seconds) >=60){
        $h =sprintf("%02d",intval($seconds/60));
        $s =sprintf("%02d",intval($seconds%60));
        if($s == 60){
            $s = sprintf("%02d",0);
            ++$h;
        }
        $t = "00";
       if($h == 60){
            $h = sprintf("%02d",0);
            ++$t;
       }
       if($t){
            $t  = sprintf("%02d",$t);
       }
       $tt= $t.":".$h.":".$s;
    }
    if(intval($seconds)>=60*60){
       $t= sprintf("%02d",intval($seconds/3600));
       $h =sprintf("%02d",intval($seconds/60)-$t*60);
       $s =sprintf("%02d",intval($seconds%60));
      if($s == 60){
         $s = sprintf("%02d",0);
         ++$h;
       }
       if($h == 60){
            $h = sprintf("%02d",0);
            ++$t;
       }
       if($t){
            $t  = sprintf("%02d",$t);
       }
       $tt= $t.":".$h.":".$s;
    }
    $aa['t']=$t;
    $aa['h']=$h;
    $aa['s']=$s;
      return $aa;
}


/*加密/解密
 * $string： 明文 或 密文
 * $operation：DECODE表示解密,其它表示加密
 * $key： 密匙
 * $expiry：密文有效期
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckey_length = 4;

    // 密匙
    $key = md5($key ? $key : '116cs');

    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        // substr($result, 0, 10) == 0 验证数据有效性
        // substr($result, 0, 10) - time() > 0 验证数据有效性
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
        // 验证数据有效性，请看未加密明文的格式
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

      /*生成随机字符串*/
      function getRandom($param){
          $str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
          $key = "";
          for($i=0;$i<$param;$i++)
           {
               $key .= $str{mt_rand(0,32)};    //生成php随机数
           }
           return $key;
       }

/** 模板中递归字符串增加长度
 * parm       $str              string            要填充的字符串
 * parm       $str_length       int               要填充的字符串数量
 */
function fill_up_string($str_length = 0, $str = ' ')
{
    $str_fill = '';
    for ($i = 0; $i < $str_length; $i++) {
        $str_fill .= $str;
    }
    return $str_fill;
}

/* 类别或导航层级关系查询
 * parm    $table_name        string            表名
 * parm    $id                int               表ID,自增
 */
function get_deep_table_class_list($table_name, $id)
{
    if ($table_name != '') {
        $map = M($table_name);
        return $map->where("id=$id")->getfield('class_list');
    } else {
        return '';
    }
}

/* 类别或导航层级层数查询
 * parm    $table_name        string            表名
 * parm    $id                int               表ID,自增
 * parm    $class_layer       int               层级数量
 */
function get_deep_table_class_layer($table_name, $id)
{
    $class_layer = 0;
    if ($table_name != '') {
        $map = M($table_name);
        $class_layer = $map->where("id=$id")->getfield('class_layer');
        if ($class_layer > 0) {
            return $class_layer;
        } else {
            return 0;
        }
    } else {
        return $class_layer;
    }
}

/** JS弹出提示层
 * parm       $tips_text        string            提示文本
 * parm       $return_url       string            返回地址
 */
function jscript_msg($tips_text, $return_url,$isHeadlines=false)
{
    if($isHeadlines){
        echo "<script type=\"text/javascript\">parent.hljsprint(\"" . $tips_text . "\", \"" . $return_url . "\")</script>";
    }else{
        echo "<script type=\"text/javascript\">parent.jsprint(\"" . $tips_text . "\", \"" . $return_url . "\")</script>";
    }
    die();
}

/** JS弹出提示层 待回调函数
 * parm       $tips_text        string            提示文本
 * parm       $return_url       string            返回地址
 * parm       $callback         string            JS回调函数
 */
function jcscript_msg($tips_text, $return_url, $callback)
{
    echo "<script type=\"text/javascript\">parent.jsprint(\"" . $tips_text . "\", \"" . $return_url . "\", " . $callback . ")</script>";
    die();
}

/** JS弹出提示层,
 * parm       $tips_text        string            提示文本
 * parm       $return_url       string            返回地址
 */
function jscript_msg_tips($tips_text)
{
    echo "<script type=\"text/javascript\">parent.jsprint(\"" . $tips_text . "\", \"\"); history.go(-1);</script>";
    die();
}

/** 前端JS弹出提示层
 * parm       $tips_text        string            提示文本
 * parm       $return_url       string            返回地址
 */
function jscript_tips($tips_text, $return_url)
{
    echo "<script type=\"text/javascript\">jsprint(\"" . $tips_text . "\", \"" . $return_url . "\")</script>";
    die();
}

/** JS弹出提示层,
 * parm       $tips_text        string            提示文本
 * parm       $return_url       string            返回地址
 */
function close_msg_tips($tips_text)
{
    $str_js = "<script type=\"text/javascript\">parent.jsprint(\"" . $tips_text . "\", \"\"); ";
    $str_js .= 'setTimeout("closeDialog()",500);';
    $str_js .= 'function closeDialog(){';
    $str_js .= 'var dialog = top.dialog.get(window);';
    $str_js .= 'dialog.close();';
    $str_js .= 'dialog.remove();';
    $str_js .= '}';
    $str_js .= "parent.settime(\"#sendBtn\")";
    $str_js .= '</script>';
    echo $str_js;
    die();
}

//生成随机订单号
function StrOrderOne(){
    /* 选择一个随机的方案 */
    //mt_srand((double) microtime() * 1000000);

    return date('Ymd') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT).mt_rand(1,9999);
}

/*提交完成后关闭父窗口的dialog
 *
 */
function closeDialog()
{
    $str_js = '';
    $str_js .= '<script type="text/javascript">';
    $str_js .= 'setTimeout("closeDialog()",500);';
    $str_js .= 'function closeDialog(){';
    $str_js .= 'var dialog = top.dialog.get(window);';
    $str_js .= 'dialog.close();';
    $str_js .= 'dialog.remove();';
    $str_js .= '}';
    $str_js .= '</script>';
    return $str_js;
}
/*生成指定长度的随机数
 * @Param                  string           $type                类型
 * @Param                  int              $length              生成的长度
 * @Return                 string           返回结果为字符串
 * */
function get_rand_str($length=6, $type='char' ){
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz0123456789';//去除O,o,I,i
    $numberStr = '0123456789';
    mt_srand((double)microtime()*1000000*getmypid());
    $randStr='';
    while(strlen($randStr)<$length){
        if($type=='number'){//只生成纯数字的字符串
            $chars = $numberStr;
        }
        $randStr.=substr($chars,(mt_rand()%strlen($chars)),1);
    }
    return $randStr;
}

/** 无限极分类数据排序并重新组合成为数组
 * parm       $data             array             所有分类数据
 * parm       $parent_id        int               父类ID
 * parm       $count            int               第几级分类
 */

function tree_category($data,$pid){
    $treeList = array();//每次都声明一个新数组用来放子元素
    foreach($data as $val){
        if($val['parent_id'] == $pid){//匹配子记录
            $val['children'] = tree_category($data,$val['id']);//递归获取子记录
            if($val['children'] == null){
                unset($val['children']);//如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
            }
            $treeList[$val['id']] = $val;//将记录存入新数组
        }
    }
    return $treeList;//返回新数组
}
/**非递归形式，排序分类数据
 * @Param      array             $data          所有分类数据
 * @Return     array             排序分类数据
 */
function tree_category1($data)
{
    //第一步，将分类id作为数组key,并创建children单元
    $treeList = array();
    foreach($data as $key=>$val){
        $treeList[$val['id']] = $val;
        $treeList[$val['id']]['children'] = array();
    }
    //第二步，利用引用，将每个分类添加到父类children数组中，这样一次遍历即可形成树形结构。
    foreach($treeList as $key=>$item){
        if($item['parent_id'] != 0){
            $treeList[$item['parent_id']]['children'][] = &$treeList[$key];//注意：此处必须传引用否则结果不对
            if($treeList[$key]['children'] == null){
                unset($treeList[$key]['children']); //如果children为空，则删除该children元素（可选）
            }
        }
    }
    ////第三步，删除无用的非根节点数据
    foreach($treeList as $key=>$category){
        if($category['parent_id'] != 0){
            unset($treeList[$key]);
        }
    }
    return $treeList;
}


/** 无限极分类数据排序
 * parm       $data             array             所有分类数据
 * parm       $parent_id        int               父类ID
 * parm       $count            int               第几级分类
 */
function treeAction($data, $parent_id = 0, $count = 1)
{
    static $treeList = array();
    foreach ($data as $key => $value) {
        if ($value['parent_id'] == $parent_id) {
            $value['count'] = $count;
            $treeList [] = $value;
            unset($data[$key]);
            treeAction($data, $value['id'], $count + 1);
        }
    }
    return $treeList;
}

//生成唯一标识符   //sha1()函数， "安全散列算法（SHA1）"
function create_unique(){
    $data = $_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].time().rand();
    return sha1($data);//return md5(time().$data);   //return $data;
}


/** 发送手机短信
 *  parm       $type     value             发送短信类型
 *  parm       $mobile           手机号
 *  parm       $code             随机验证码
 *  parm       $parm             随机验证码
 *  return     $arr              返回接口返回的数据
 */

function send_sms_message($type, $mobile, $code, $parm = array())
{
    $appId = C('SMS_APPID');//appid
    $sms = new Org\Com\Ucpaas();//调用Ucpass类库
    if ($parm) {
        $arr = $sms->templateSMS($appId, $mobile, C('SMS_TPL.'.$type), implode(',', $parm));
    } else {
        $arr = $sms->templateSMS($appId, $mobile, C('SMS_TPL.'.$type), $code);
    }
    if (substr($arr, 21, 6) == 000000) {
        return true;
    } else {
        return false;
    }
}

/**判断是否在微信端打开
 * @return bool
 */
function is_weixin(){
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }
    return false;
}

/*
 **隐藏字符串的其中几位字符，并以*号代替
 * */
function hide_str_replace_star($str)
{
    if (strpos($str, '@')) {
        $email_array = explode("@", $str);
        $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
        $count = 0;
        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
        $rs = $prevfix . $str;
    } else {
        $pattern = '/(1[3458]{1}[0-9])[0-9]{4}([0-9]{4})/i';
        if (preg_match($pattern, $str)) {
            $rs = preg_replace($pattern, '$1****$2', $str); // substr_replace($name,'****',3,4);
        } else {
            $rs = substr($str, 0, 3) . "***" . substr($str, -1);
        }
    }
    return $rs;
}

//JSON转换
if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
    function json_encode_ex($var)
    {
        return json_encode($var, JSON_UNESCAPED_UNICODE);
    }
} else {
    function json_encode_ex($var)
    {
        if ($var === null)
            return 'null';

        if ($var === true)
            return 'true';

        if ($var === false)
            return 'false';

        static $reps = array(
            array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"',),
            array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"',),
        );

        if (is_scalar($var))
            return '"' . str_replace($reps[0], $reps[1], (string)$var) . '"';

        if (!is_array($var))
            throw new Exception('JSON encoder error!');

        $isMap = false;
        $i = 0;
        foreach (array_keys($var) as $k) {
            if (!is_int($k) || $i++ != $k) {
                $isMap = true;
                break;
            }
        }

        $s = array();

        if ($isMap) {
            foreach ($var as $k => $v)
                $s[] = '"' . $k . '":' . call_user_func(__FUNCTION__, $v);

            return '{' . implode(',', $s) . '}';
        } else {
            foreach ($var as $v)
                $s[] = call_user_func(__FUNCTION__, $v);

            return '[' . implode(',', $s) . ']';
        }
    }
}

/*根据IP地址获取用户的经纬度信息
*@Param        string          $ip         用户的IP信息
*/
function get_user_map_info($ip){
    $ak = C("BAIDU_MAP_AK");
    $apiUrl = "http://api.map.baidu.com/location/ip?ak=".$ak."&ip={$ip}&coor=bd09ll";
    $info=array(
        'lng' => 114.000709,
        'lat' => 22.598102,
        'city_name' => "深圳市",
    );
    if($ip){
        $ch = curl_init();//利用curl_init()获取百度地图接口返回的数据
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出
        $result = curl_exec($ch);//返回json数据格式

        curl_close($ch);
        $infoList = json_decode($result);
        if(isset($infoList->content->point) && !empty($infoList->content->point)){
            $info = array(
                'lng' => $infoList->content->point->x,
                'lat' => $infoList->content->point->y,
                'city_name' => $infoList->content->address_detail->city,
            );
            session("lng",$infoList->content->point->x);//把用户的经纬度信息存入session
            session("lat",$infoList->content->point->y);
            session("city_name",$infoList->content->address_detail->city);
        }else{
            session("lng",114.000709);//把用户的经纬度信息存入session,默认
            session("lat",22.598102);
            session("city_name","深圳市");
        }
    }else{
        session("lng",114.000709);//把用户的经纬度信息存入session
        session("lat",22.598102);
        session("city_name","深圳市");
    }
    return $info;
}
/*根据经纬度信息判断当前位置是否有店铺
* return      bool
*/
function isExistStore(){
    $storeModel = M("store");
    //首先先根据用户session经纬度判断是否存在店铺
    $condition = array();
    $count = $storeModel->where($condition)->count();
}
/*
 * 生成唯一商品SN
 * */
function get_goods_sn()
{    /* 选择一个随机的方案 */
    mt_srand((double)microtime() * 1000000);
    return str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/** 支付状态确认
 * @param int    $orderSn 订单号
 * @param int    $transaction_id 交易号
 * @param int    $payType 支付类型 1为余额支付，2为微信支付，3为支付宝支付
 */
function pay($orderSn = 0,$transaction_id = 0, $payType = 2)
{
    $orderModule = M('market_order');
    $order = $orderModule->where(array('order_sn' => $orderSn, 'status' => array('in','0')))->find();
    if (!empty($order)) {
        //更新订单状态
        if($order['delivery_way']==0){
            $data['status'] = 3;//订单状态分为：0为已提交未支付，1为已付款待发货，2为已发货待确认，3为确认，99为退货
        }else{
            $data['status'] = 1;
            D('Common/Notice')->insertNotice('waitSend',"订单".$orderSn."待发货",$order['store_id']);
        }
        $data['pay_time'] = time();
        $data['pay_type'] = $payType;
        $data['out_trade_no'] = $transaction_id;
        $condition['order_sn'] = $orderSn;
        $condition['parent_order_sn'] = $orderSn;
        $condition['_logic'] = 'or';
        $result = $orderModule->where($condition)->data($data)->save();
        return $result;
    }else{
        return false;
    }
}
/**
 * 图片旋转处理
 * @param  string  $oldFile 源图片路径
 * @param  string  $newFile 保存路径
 * @param  integer $degrees 旋转角度
 * @return boolean 图片是否旋转成功
 */
function pictureFlip($oldFile,$newFile,$degrees){
    if(!empty($degrees)) {
        //读取源图片
        $data = @getimagesize($oldFile);//@是忽略报错
        if ($data == false) return false;
        //根据源图片创建保存文件格式
        switch ($data[2]) {
            case 1:
                $src_f = imagecreatefromgif($oldFile);
                break;
            case 2:
                $src_f = imagecreatefromjpeg($oldFile);
                break;
            case 3:
                $src_f = imagecreatefrompng($oldFile);
                break;
        }
        if ($src_f == "") {//图片格式
            return false;
        }
        $rotate = @imagerotate($src_f, $degrees, 0);//旋转图片
        if (!imagejpeg($rotate, $newFile, 100)) {//保存图片
            return false;
        }
        @imagedestroy($rotate);
    }
    return true;
}



/**
 * 统计访问信息
 *
 * @access  public
 * @return  void
 */
function visit_stats(){
    if (C('VISIT_STATUS')!='on') {//判断是否开启统计
        return;
    }
    $time = time();
    /* 检查客户端是否存在访问统计的cookie */
    $visit_times = cookie('sg_visit_times');
    if($visit_times){
        $visit_times = intval(cookie('sg_visit_times')) + 1;
    }else{
        $visit_times = 1;
    }
    cookie('visit_times',$visit_times ,'expire='.($time + 86400 * 365).'&prefix=sg_&path=/');
    vendor('ClientTrack\ClientTrack');
    $ctClass = new \ClientTrack($_SERVER['HTTP_USER_AGENT']);
    $browser  = $ctClass->getUserBrowser();//获取浏览器信息
    $os       = $ctClass->getOs();//获取用户的操作系统
    $ip       = get_client_ip();//获取用户的IP
    $IpClass = new \Org\Net\IpLocation('UTFWry.dat');
    $regionInfo = $IpClass->getlocation($ip);//获得所在的区域
    $data['area'] = $area     = $regionInfo['area'];//获得所在的区域
    $data['access_time'] = $time;
    $data['visit_times'] = $visit_times;
    $data['browser'] = $browser;
    $data['system'] = $os ;
    $data['ip_address'] = $ip ;


    /* 语言 */
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $pos  = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ';');
        $lang = addslashes(($pos !== false) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, $pos) : $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    } else {
        $lang = '';
    }
    $data['lang'] = $lang;
    /* 来源 */
    if (!empty($_SERVER['HTTP_REFERER']) && strlen($_SERVER['HTTP_REFERER']) > 9)
    {
        $pos = strpos($_SERVER['HTTP_REFERER'], '/', 9);
        if ($pos !== false) {
            $domain = substr($_SERVER['HTTP_REFERER'], 0, $pos);
            $path   = substr($_SERVER['HTTP_REFERER'], $pos);
            /* 来源关键字 */
            /*if (!empty($domain) && !empty($path))
            {
                save_searchengine_keyword($domain, $path);
            }*/
        } else {
            $domain = $path = '';
        }
    } else {
        $domain = $path = '';
    }
    $data['referer_domain'] = addslashes($domain);
    $data['referer_path'] = addslashes($path);
    $data['access_url'] = addslashes(PHP_SELF);
    //把用户的信息写入统计表
    $statsModel = D('Common/Stats');
    $statsModel->add($data);
}

/**
* @desc 根据两点间的经纬度计算距离
* @param float $lat 纬度值
* @param float $lng 经度值
*/
function getDistance($lat1, $lng1, $lat2, $lng2)
{
$earthRadius = 6367000; //approximate radius of earth in meters

/*
Convert these degrees to radians
to work with the formula
*/

$lat1 = ($lat1 * pi() ) / 180;
$lng1 = ($lng1 * pi() ) / 180;

$lat2 = ($lat2 * pi() ) / 180;
$lng2 = ($lng2 * pi() ) / 180;

/*
Using the
Haversine formula

http://en.wikipedia.org/wiki/Haversine_formula

calculate the distance
*/

$calcLongitude = $lng2 - $lng1;
$calcLatitude = $lat2 - $lat1;
$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
$stepTwo = 2 * asin(min(1, sqrt($stepOne)));
$calculatedDistance = $earthRadius * $stepTwo;

return round($calculatedDistance);
}
/**
 * 快递100
 * @param  string  $typeCom     快递公司
 * @param  string  $typeNu      快递单号
 * @return mixed
 */
function get_logistics_info($typeCom,$typeNu){
    $express = new \Org\Util\Express();
    $result = $express->getorder($typeCom,$typeNu);
    return $result;
}

/**正则验证字符串
 * @Param      string         $type        正则验证类型
 * @Param      string         $str         被验证的值
 * */
function regexp_str($type,$str){
    switch ($type){
        case 'mobile'://正则验证手机号码格式是否正确
            $reg = '/^1[34578]\d{9}$/';
            if (preg_match($reg, $str)) {
                return true;
            } else {
                return false;
            }
            break;
        case 'email'://正则验证邮箱格式是否正确
//            $reg = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[0-9a-z]+(\\.[a-z]{2,3})?)$/i";
            $reg="/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";
            if (preg_match($reg, $str)) {
                return true;
            } else {
                return false;
            }
            break;
    }
}


/**阿里大于发送短信
 * @param $templateCode     string     短信模版ID
 * @param $mobile           int        接收手机号
 * @param $param            array      参数
 * @return mixed
 * */
function alidayu_sms_send($templateCode,$mobile,$param=array()){
    vendor('Aliyun\Aliyun');
    $demo = new Aliyun();
    $response = $demo->sendSms($templateCode,$mobile,$param);
    return $response;
}

/**
 * 邮件发送
 * parm           string            $address             收件人地址
 * parm           string            $title               邮件标题
 * parm           string            $message             邮件内容
 * parm           string            $fromname            发送人名字
 * */
function SendMail($address, $title, $message, $fromname = 'NONE')
{
    import('ORG.Util.PHPMailer');
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet = C('MAIL_CHARSET');
    $mail->AddAddress($address);
    $mail->Body = $message;
    $mail->From = C('EMAIL_FROM');
    $mail->SMTPSecure = C('EMAILSSL');
    $mail->Port = C('EMAIL_PORT');
    $mail->FromName = $fromname;
    $mail->Subject = $title;
    $mail->Host = C('EMAIL_SMTP');
    $mail->SMTPAuth = C('MAIL_AUTH');
    $mail->Username = C('EMAIL_USER_NAME');
    $mail->Password = C('EMAIL_PASSWORD');
    $mail->IsHTML(C('MAIL_HTML'));
    return ($mail->Send());
}

/**新增通知
 * @param $to              string              被通知对象,'admin'管理员,'store'店铺,'seller'卖家
 * @param $type            string              类型,auditStore待审核店铺,auditSeller待审核卖家,waitDelivery待投放商品
 * @param $content         string              通知内容
 * @param $s_id            int                 店铺ID或卖家ID
 * @return bool
 */
function insert_notice($to,$type,$content,$s_id=0){
    $noticeModel=M('notice');
    $data['to']=$to;
    $data['type']=$type;
    $data['content']=$content;
    $data['s_id']=$s_id;
    $data['time']=time();
    $id=$noticeModel->add($data);
    if ($id!==false){
        return true;
    }else{
        return false;
    }
}
/**设为已读
 * @param $to              string              被通知对象,'admin'管理员,'store'店铺,'seller'卖家
 * @param $type            string              类型,auditStore待审核店铺,auditSeller待审核卖家,waitDelivery待投放商品
 * @return bool
 */
function set_readed($to,$type){
    $condition['to']=$to;
    $condition['type']=$type;
    $condition['is_readed']=0;
    M('notice')->where($condition)->setField('is_readed',1);
}
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
function check_verify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 * 可以统计中文字符串长度的函数
 * @param string $str 要计算长度的字符串
 * @return int        字符串长度
 */
function abslength($str)
{
    if(empty($str)){
        return 0;
    }
    if(function_exists('mb_strlen')){
        return mb_strlen($str,'utf-8');
    }
    else {
        preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param int $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param bool $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    $str_len=abslength($str);
    if ($str_len>$length){
        return $suffix ? $slice.'...' : $slice;
    }else{
        return $slice.'&nbsp;';
    }

}

/**
     * 获取主机IPv4
     */
    function get_ipv4(){
        if (isset($_ENV["HOSTNAME"])){
            $MachineName = $_ENV["HOSTNAME"];
        } else if(isset($_ENV["COMPUTERNAME"])){
            $MachineName = $_ENV["COMPUTERNAME"];
        }else{
            $MachineName = "";
        }
        return gethostbyname($MachineName);
    }

    /**
     *文字识别OCR
     * @param $img string   图片路径
     * @return array        图片信息
     */
    function aip_ocr($img){
        header("Content-type: text/html; charset=utf-8");
        // 引入文字识别OCR SDK
        vendor('AipOCR\AipOcr');

        $APP_ID = '10558302';
        $API_KEY = '83fn5u9wpTBjvttlU8bWCdz8';
        $SECRET_KEY = 'GsZpQQUqekAyAWmxpf0szjA8kzGT3hPS';

        // 初始化
        $aipOcr = new AipOcr($APP_ID, $API_KEY, $SECRET_KEY);
        // 通用文字识别(含文字位置信息)
        echo "<pre>";
        return $aipOcr->general(file_get_contents($img));
        // 身份证识别
        // echo json_encode($aipOcr->idcard(file_get_contents('idcard.jpg'), true), JSON_PRETTY_PRINT);

        // 银行卡识别
        // echo json_encode($aipOcr->bankcard(file_get_contents('bankcard.jpg')));

        // 通用文字识别(含文字位置信息)
        // echo json_encode($aipOcr->general(file_get_contents('general.png')), JSON_PRETTY_PRINT);

        // 通用文字识别(不含文字位置信息)
        // echo json_encode($aipOcr->basicGeneral(file_get_contents('general.png')), JSON_PRETTY_PRINT);

        // 网图OCR识别
        // echo json_encode($aipOcr->webImage(file_get_contents('general.png')), JSON_PRETTY_PRINT);

        // 生僻字OCR识别
        // echo json_encode($aipOcr->enhancedGeneral(file_get_contents('general.png')), JSON_PRETTY_PRINT);

        // 行驶证识别
        // echo json_encode($aipOcr->vehicleLicense(file_get_contents('vehicleLicense.jpg')), JSON_PRETTY_PRINT);

        // 驾驶证
        // echo json_encode($aipOcr->drivingLicense(file_get_contents('drivingLicense.jpg')), JSON_PRETTY_PRINT);

        // 车牌
        //echo json_encode($aipOcr->licensePlate(file_get_contents('licensePlate.jpg')), JSON_PRETTY_PRINT);
    }

    /*生成商品二维码
    *@param             $id                 商品id
    */
    function createQRCode($id){
        $url ="http://m.somegood.com.cn/Mobile/Goods/detail/id/$id.html";
        $url=urlencode($url);
        $QRCodeAPI="http://qr.liantu.com/api.php?&w=200&text=$url";   //生成二维码api    200x200
        return $QRCodeAPI;
    }
