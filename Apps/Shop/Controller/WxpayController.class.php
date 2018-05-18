<?php
namespace Shop\Controller;
use Think\Controller;
/*微信支付
 */
class WxpayController extends Controller{
    //在类初始化方法中，引入相关类库
    public function __construct() {
         parent::__construct();
        //Vendor('WxpayPC.WxPay');
        Vendor('Weixinpay.WxPayApi');
    }
    public function indexAction(){
            header("Content-type:text/html;charset=utf-8");
            $orderSn = I('order_sn','');
            $orderSn = $orderSn;
            $orderInfo = M('order')->where(array('order_sn' => $orderSn))->find();
            $orderMoney = 0;
            $random = get_goods_sn();  //生成随机字符串
            $money = $orderMoney>0?(100*$orderMoney):(100*$orderInfo['money']);
            //统一下单
            $notify = new \WxPayApi();
            /**
             * 流程：
             * 1、调用统一下单，取得code_url，生成二维码
             * 2、用户扫描二维码，进行支付
             * 3、支付完成之后，微信服务器会通知支付成功
             * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
             */
            $input = new \WxPayUnifiedOrder();
            $input->SetBody("微信支付-账单支付"); //设置商品或支付单简要描述
            $input->SetAttach(""); //设置附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
            $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));   //设置商户系统内部的订单号
            $input->SetTotal_fee($money);    //设置订单总金额
            $input->SetTime_start(date("YmdHis"));   //设置订单生成时间
            $input->SetTime_expire(date("YmdHis", time() + 600));   //设置订单失效时间
            $input->SetGoods_tag("");   //设置商品标记
            $input->SetNotify_url(C('wxnotify_url'));    //设置接收微信支付异步通知回调地址
            $input->SetTrade_type("NATIVE");      //设置取值如下：JSAPI，NATIVE，APP，详细说明见参数规定
            $input->SetProduct_id($orderSn);   //设置trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。
            $urlArr = \WxPayApi::unifiedOrder($input);         //二维码地址
            //dump($input);exit;
            $url=urlencode($urlArr["code_url"]);
            $this->assign("url1",$url);
            $this->assign("web_title", '微信支付');
            $this->assign('complete_url', U('UserOrder/orderlist', array('status' => 1)));
            $this->display();
    }

    //获取订单状态是否改变
    public function getOrderStatusAction(){
        $orderSn = I('orderSn','');
        $status=M('order')->where("order_sn=$orderSn")->getField('status');
        $this->ajaxInfoReturn("","获取成功!",$status);
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
