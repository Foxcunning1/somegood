<?php
namespace Mobile\Controller;
use Think\Controller;
/*微信支付
 */
class WxpayController extends Controller{
    //在类初始化方法中，引入相关类库
    public function __construct() {
         parent::__construct();
        vendor('Weixinpay.WxPayJsApiPay');
    }
    public function indexAction(){
        header("Content-type:text/html;charset=utf-8");
        $orderSn = I('order_sn','');
        $orderInfo = M('order')->where(array('order_sn' => $orderSn))->find();
        $orderMoney = 0;
            $random = get_goods_sn();  //生成随机字符串
            $money = $orderMoney>0?(100*$orderMoney):(100*$orderInfo['money']);
            //统一下单
            $tools = new \JsApiPay();
            $openId = $tools->GetOpenid();
            $input = new \WxPayUnifiedOrder();
            $input->SetBody("微信支付-账单支付"); //商品简述
            $input->SetAttach("");//商品附加信息
            $input->SetOut_trade_no($orderSn.$random);//商户订单号
            $input->SetTotal_fee($money);//订单总价
            $input->SetTime_start(date("YmdHis"));//交易开始时间
            $input->SetTime_expire(date("YmdHis", time() + 600));//交易结束时间
            $input->SetNotify_url(C('wxnotify_url'));   //支付回调地址，这里改成你自己的回调地址。
            $input->SetTrade_type("JSAPI");//公众号支付
            $input->SetOpenid($openId);//公众号支付必须参数！
            $order = \WxPayApi::unifiedOrder($input);
            $jsApiParameters = $tools->GetJsApiParameters($order);
            $this->assign('real_pay', $money/100);
            $this->assign('order_sn', $orderSn);
            $this->assign('jsApiParameters', $jsApiParameters);
            $this->assign("web_title", '微信支付');
            $this->assign("page_title", '微信支付');
            $this->assign('complete_url', U('Shop/UserOrder/index', array('status' => 1)));
            $this->display();
    }

    //支付成功回调
    public function notifyAction(){
        //这里没有去做回调的判断，可以参考手机做一个判断。
        $xmlString= $GLOBALS['HTTP_RAW_POST_DATA'];//旧版本
        //$xmlString = file_get_contents('php://input');
        $xmlObj = simplexml_load_string($xmlString); //解析回调数据

        /**************************回调参数**************************/
        $appid=$xmlObj->appid;//微信appid
        $mch_id=$xmlObj->mch_id;  //商户号
        $nonce_str=$xmlObj->nonce_str;//随机字符串
        $sign=$xmlObj->sign;//签名
        $result_code=$xmlObj->result_code;//业务结果
        $openid=$xmlObj->openid;//用户标识
        $is_subscribe=$xmlObj->is_subscribe;//是否关注公众帐号
        $trace_type=$xmlObj->trade_type;//交易类型，JSAPI,NATIVE,APP
        $bank_type=$xmlObj->bank_type;//付款银行，银行类型采用字符串类型的银行标识。
        $total_fee=$xmlObj->total_fee;//订单总金额，单位为分
        $fee_type=$xmlObj->fee_type;//货币类型，符合ISO4217的标准三位字母代码，默认为人民币：CNY。
        $transaction_id=$xmlObj->transaction_id;//微信支付订单号
        $out_trade_no = substr($xmlObj->out_trade_no,0,18);//商户订单号
        $attach=$xmlObj->attach;//商家数据包，原样返回
        $time_end=$xmlObj->time_end;//支付完成时间
        $cash_fee=$xmlObj->cash_fee;
        $return_code=$xmlObj->return_code;
        /***************************回调参数结束**********************/
        $this->createFolder('pay_log/'.date('Ymd'));
        foreach ($xmlObj as $key => $value){
            file_put_contents('pay_log/'.date('Ymd')."/"."$out_trade_no.txt",$key.":".$value."\r\n",FILE_APPEND);
        }
        if($result_code == 'SUCCESS') {
            //下面开始你可以把回调的数据存入数据库，或者和你的支付前生成的订单进行对应了。
            pay($out_trade_no, $transaction_id,2);
            vendor('Weixinpay.WxPayNotify');
            $notify = new \WxPayNotify();
            $notify->handle(false);
            //需要记住一点，就是最后在输出一个success.要不然微信会一直发送回调包的，只有需出了succcess微信才确认已接收到信息不会再发包.
        }
    }

    public function createFolder($path){
        if (!file_exists($path)){
            $this->createFolder(dirname($path));
            mkdir($path);
            chmod($path,0777);
        }
    }
}
?>
