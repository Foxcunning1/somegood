<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title><?php echo ($web_title); ?></title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/smartphoto.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/ershou/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/ershou/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <script type="text/javascript">
        var usersLng = <?php echo (session('lng')); ?>;//用户所在的经纬度
        var usersLat = <?php echo (session('lat')); ?>;
    </script>
</head>
<body>
<!--详情图滚动图-->
<div class="page-title">
    <!--返回按钮-->
    <div class="back-box">
        <a href="javascript:history.back();" class="icons icon-back"></a>
    </div>
    <h2 class="title">寻宝详情</h2>
    <!--返回按钮-->
</div>
<!--商品详情-->
<div class="goods-detail" style="padding-top: 1rem">
    <div class="g-info">
        <div class="product-name">
            <span><?php echo ($info["w_title"]); ?></span>
        </div>
        <div class="product-price-m">
            <em class="fl">¥<span class="big-price"><?php echo ($info["w_min"]); ?></span> - ¥<span class="big-price"><?php echo ($info["w_max"]); ?></span></em>
            <span class="view fr"><span id="viewNum"><?php echo ($info["browser_num"]); ?></span>次浏览</span>
        </div>
    </div>
</div>

<!--商品详情-->
<!--商品描述-->
<div class="goods-desc">
    <div class="title">寻宝详情</div>
    <div class="desc">
        <div class="goods-desc-box"><?php echo ($info["w_content"]); ?></div>
    </div>
</div>
<!--所属店铺-->
<!--公用底部-->
	<div class="footer">
        <ul>
            <li><a href="<?php echo U('Ershou/Index/index');?>"><i class="icons icon-home"></i><span>首页</span></a></li>
            <li><a href="<?php echo U('Ershou/Goods/category');?>"><i class="icons icon-classify"></i><span>分类</span></a></li>
            <li class="plus"><i class="icons icon-plus"></i></li>
            <li><a href="<?php echo U('Mobile/Users/favorites');?>"><i class="icons icon-favorites"></i><span>收藏</span></a></li>
            <li><a href="<?php echo U('Mobile/Users/index');?>"><i class="icons icon-mine"></i><span>我的</span></a></li>
        </ul>
    </div>
    <div class="rotate-box">
        <a href="<?php echo U('Mobile/SecondHand/publish');?>" class="before">宝贝</a>
        <a href="<?php echo U('Mobile/Users/addWant');?>" class="after">寻宝</a>
    </div>
    <script type="text/javascript">

        /*公用底部点击样式变换*/
        $(".footer ul").find("li").on("touchstart",function(){
            $(this).addClass("sel").siblings().removeClass("sel");

        })
        $(".icon-plus").on("touchstart",function(){
            if($(".rotate-box").hasClass("rotate")){
                $(this).removeClass("rotate");
                $(".rotate-box").removeClass("rotate");
            }else{
                $(this).addClass("rotate");
                $(".rotate-box").addClass("rotate");
            }
        });
    </script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    $(function () {
        if(isWeixin()){
            //是微信浏览器则ajax获取
            $.ajax({
                type: "post",
                url: "<?php echo U('Mobile/WxShare/getWxShareApiInfo');?>",
                data:{'url':window.location.href},
                dataType: "json",
                success: function(item){
                    res = item.data;
                    wx.config({
                        debug: false,
                        appId: res.app_id,
                        timestamp: res.timestamp,
                        nonceStr: res.nonceStr,
                        signature: res.signature,
                        jsApiList: [
                            'translateVoice',
                            'onMenuShareTimeline',
                            'onMenuShareAppMessage',
                            'getLocation'
                        ]
                    });
                    var dataForWeixin = {
                        title: "<?php echo ($web_title); ?>",
                        desc: "<?php echo C('SEO_DESCRIPTION');?>",
                        imgUrl: "<?php echo C('MOBILE_URL');?>/somegood/Public/statics/mobile/images/share_img.jpg",
                        link: res.share_url
                    };
                    wx.ready(function () {
                        console.log('wx ready');
                        wx.onMenuShareTimeline({
                            title: dataForWeixin.title,
                            link: dataForWeixin.link,
                            imgUrl: dataForWeixin.imgUrl,
                        });
                        wx.onMenuShareAppMessage({
                            title: dataForWeixin.title,
                            desc: dataForWeixin.desc,
                            link: dataForWeixin.link,
                            imgUrl: dataForWeixin.imgUrl,
                        });
                        wx.getLocation({
                            success: function (res) {
                                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                                var speed = res.speed; // 速度，以米/每秒计
                                var accuracy = res.accuracy; // 位置精度
                                localStorage.setItem("latitude", latitude);
                                localStorage.setItem("longitude", longitude);
                                var data = {
                                    latitude: latitude,
                                    longitude: longitude
                                };
                                if (typeof callback == "function") {
                                    callback(data);
                                }
                            },
                            cancel: function () {
                                //这个地方是用户拒绝获取地理位置
                                if (typeof error == "function") {
                                    error();
                                }
                            }
                        });
                    });

                },
            });
        }
    });
</script>



<!--公用底部-->
</body>
</html>