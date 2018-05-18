<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <title><?php echo ($web_title); ?></title>
    <script type="text/javascript">
        //获取是否支付成功
        function ajaxGetStatus() {
            $.ajax({
                method:'post',
                dataType:'json',
                url: "<?php echo U('Mobile/Wxpay/getOrderStatus');?>",
                async:false,
                success:function(data){
                    if (data.status == 1) {
                        //支付成功后跳转
                        window.location.href="<?php echo ($complete_url); ?>";
                    }
                }
            });
        }
        setInterval("ajaxGetStatus()",3000);
    </script>
</head>
<body>

            <div class="p-w-box">
            <div class="pw-box-hd">
                <img alt="模式二扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo ($url1); ?>" style="width:300px;height:300px;"/>
            </div>
            <div class="pw-retry j_weixiRetry" style="display: none;">
                <a class="ui-button ui-button-gray j_weixiRetryButton" href="javascript:getWeixinImage2();">获取失败 点击重新获取二维码  </a>
            </div>
            <div class="pw-box-ft">
                <p>请使用微信扫一扫</p>
                <p>扫描二维码支付</p>
            </div>
            </div>
</body>
</html>