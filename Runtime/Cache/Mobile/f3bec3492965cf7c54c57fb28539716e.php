<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>3好连锁店</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body>
<!--店铺主页-->
<div class="seller-shop-inner">
    <div class="name fl">
        <span><?php echo ($info["nick_name"]); ?></span>
    </div>
    <div class="head-portrait fr">
        <a href="<?php echo U('Users/setting',array('type'=>'user_img'));?>">
            <img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="/somegood/Public/uploads/<?php echo ($info["avatar"]); ?>_c200x200" alt="卖家头像" class="user-img fl"/>
        </a>
    </div>
    <!--返回按钮和分享-->
    <!--<div class="to-back-box">-->
    <!--<a href="javascript:history.go(-1);" class="icons icon-back"></a>-->
    <!--</div>-->
    <!--<h2 class="title"><?php echo ($info["company_name"]); ?></h2>-->
    <!--<div class="to-menu-box">-->
    <!--<i class="icons icon-share"></i>-->
    <!--</div>-->
    <!--&lt;!&ndash;返回按钮和分享&ndash;&gt;-->

</div>
<!--店铺主页-->
<div class="inner-store belong-store my-store">
    <div class="impression">
        <ul>
            <li>
                <a href="<?php echo U('Mobile/SecondHand/order');?>?pageType=1">
                    <span class="red"><?php echo ($wait_send_num); ?>件</span>
                    <span>待发货</span>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Mobile/SecondHand/goodsManage');?>?pageType=sale">
                    <span class="red"><?php echo ($on_sale_num); ?>件</span>
                    <span>在售</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--所属店铺-->
<div class="user-section">
    <div class="sec-content">
        <ul class="items">
            <li>
                <i class="icons icon-my-order"></i>
                <a href="<?php echo U('Mobile/SecondHand/order');?>?pageType=1">
                    <span class="fl">我的订单</span><i class="icons icon-more fr"></i><span class="num fr" id="buyNum"><!--0--></span>
                </a>
            </li>
            <li>
                <i class="icons icon-selled"></i>
                <a href="<?php echo U('Mobile/SecondHand/goodsManage');?>?pageType=success">
                    <span class="fl">我卖出的</span><i class="icons icon-more fr"></i><span class="num fr" id="mySellNum"><?php echo ($success_num); ?></span>
                </a>
            </li>
            <li>
                <i class="icons icon-my-release"></i>
                <a href="<?php echo U('Mobile/SecondHand/goodsManage');?>?pageType=sale">
                    <span class="fl">我的发布</span><i class="icons icon-more fr"></i><span class="num fr" id="releaseNum"><?php echo ($goods_num); ?></span>
                </a>
            </li>
            <li>
                <i class="icons icon-settlement"></i>
                <a href="<?php echo U('Mobile/SecondHand/settlement');?>">
                    <span class="fl">我的结算</span><i class="icons icon-more fr"></i>
                </a>
            </li>

            <li>
                <i class="icons icon-fabu"></i>
                <a href="<?php echo U('Mobile/SecondHand/publish');?>">
                    <span class="fl">发布商品</span><i class="icons icon-more fr"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--商品筛选部分-->
<!--公用底部-->
	<div class="footer ufooter">
        <ul>
            <li><a href="<?php echo U('Mobile/Index/index');?>"><i class="icons icon-home"></i><span>新品</span></a></li>
            <li><a href="<?php echo U('Ershou/Index/index');?>"><i class="icons icon-classify"></i><span>二手</span></a></li>
            <li><a href="<?php echo U('Mobile/Users/favorites');?>"><i class="icons icon-cart"></i><span>收藏</span></a></li>
            <li id="shopKeepper"><a href="<?php if(($$Think["session"]["mobile"]["store_id"]) == "0"): echo U('Mobile/Users/index'); else: ?>javascript:void(0);<?php endif; ?>"><i class="icons icon-mine"></i><span>我的</span></a></li>
        </ul>
    </div>
    <div style="height: 1rem;width: 100%; position: fixed; bottom: 0; right:0;z-index: 1">
        <?php if(($$Think["session"]["mobile"]["store_id"]) != "0"): ?><div class="shopkeeper-box products-menu">
                <a href="<?php echo U('Mobile/Users/index');?>">会员中心</a>
                <a href="<?php echo U('Mobile/SecondHand/index');?>">二手管理</a>
                <a href="<?php echo U('Mobile/Store/index');?>">店铺管理</a>
				<a href="<?php echo U('Mobile/Seller/index');?>">商家管理</a>
            </div><?php endif; ?>
    </div>
    <script type="text/javascript">

        /*店铺管理动画*/
        $("#shopKeepper").on("click",function(){
            $(".shopkeeper-box").toggleClass("click");
        })
        /*公用底部点击样式变换*/
        $(".footer ul").find("li").on("touchstart",function(){
            $(this).addClass("sel").siblings().removeClass("sel");

        })

        $(function() {
			//获取是否是商家或店铺
			$.ajax({
                type: 'POST',
                url: "<?php echo U('Mobile/Cart/isWho');?>",
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
					//alert(data.data.isStore);
						if (data.data.isSeller == 0) {
							$(".shopkeeper-box.products-menu").children('a').eq(3).text("品牌推广");
						};
						if (data.data.isStore == 0) {
							$(".shopkeeper-box.products-menu").children('a').eq(2).text("加盟店申请");
						}
                    }
                }
            })
        })
    </script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    /**把经纬度信息写入session
     * @Param     decimal          lng           当前用户经度信息
     * @Param     decimal          lat           当前用户纬度信息
     * */
    function writeLocationInfoToSession(lng,lat){
        $.ajax({
            type: "post",
            url: "<?php echo U('Ershou/Ajax/writeLocationInfoToSession');?>",
            data:{'lng':lng,'lat':lat},
            dataType: "json",
            success: function(){
                //数据提交成功！
            }
        });
    }
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
                                //把经纬度写入localStorage
                                localStorage.setItem("latitude", latitude);
                                localStorage.setItem("longitude", longitude);
                                //同时把经纬度信息ajax给后台，以session形式存储
                                writeLocationInfoToSession(longitude,latitude);
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
<script>
    $(function(){
        //图片延迟加载
        $(".head-portrait a img").lazyload({effect : "fadeIn"});
    })
    /*分享引导弹出*/
    $(".icon-share").on("touchstart",function(){
        $(".over-lay-share").show();
        $("body").css("overflow","hidden");
    })
    $("img.know").on("touchstart",function(e){
        $(".over-lay-share").hide();
        $("body").css("overflow","auto");
        e.preventDefault();

    })
</script>
</body>
</html>