<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title><?php echo ($page_title); ?></title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body>
    <div class="user-top">
        <div class="img-box">
            <a href="<?php echo U('Users/setting',array('type'=>'user_img'));?>">
            <img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="/somegood/Public/uploads/<?php echo ($user["avatar"]); ?>_c200x200" style="border-radius: 50%;" alt="会员头像" />
            </a>
        </div>
        <h3><?php echo ($user["nick_name"]); ?></h3>
        <a href="<?php echo U('Mobile/Users/setting');?>" class="setting-box"><i class="icons icon-setting"></i></a>
    </div>
    <div class="user-section">
        <div class="sec-title">
            <h3 class="fl">我的订单</h3>
            <a href="<?php echo U('Mobile/Users/orderIndex');?>" class="fr"><span class="check-order fl">查看全部订单</span><i class="icons icon-more fl"></i></a>
        </div>
        <div class="sec-content">
            <ul class="already">
                <li><a href="<?php echo U('Mobile/Users/orderIndex',array('status'=>'0'));?>"><i class="icons icon-to-be-payed"></i><span>待付款</span></a></li>
                <li><a href="<?php echo U('Mobile/Users/orderIndex',array('status'=>'2'));?>"><i class="icons icon-to-be-received"></i><span>待收货</span></a></li>
                <li><a href="<?php echo U('Mobile/Users/orderIndex',array('status'=>'3'));?>"><i class="icons icon-shipped"></i><span>已完成</span></a></li>
                <li><a href="<?php echo U('Mobile/Users/orderIndex');?>"><i class="icons icon-customer-service"></i><span>全部</span></a></li>
            </ul>
        </div>
    </div>
    <div class="user-section">
        <div class="sec-content">
            <ul class="items">
                <li>
                    <i class="icons icon-my-collection"></i>
                    <a href="<?php echo U('Mobile/Users/favorites');?>">
                        <span class="fl">我的收藏</span><i class="icons icon-more fr"></i><span class="num fr" id="collectionNum"><?php echo ($favorite_num); ?></span>
                    </a>
                </li>
                <li>
                    <i class="icons icon-my-authentication"></i>
                    <?php if($auth == 1): ?><a href="<?php echo U('Mobile/Users/setting',array('type'=>'id_confirm'));?>"><span class="fl">我的认证</span><i class="icons icon-more fr"></i><span class="num fr" id="Authenticationing">审核中</span></a>
                        <?php elseif($auth == 2): ?>
                        <a href="<?php echo U('Mobile/Users/cer');?>"/><span class="fl">我的认证</span><i class="icons icon-more fr"></i><span class="num fr" id="Authentication">已认证</span></a>
                    <?php else: ?>
                        <a href="<?php echo U('Mobile/Users/setting',array('type'=>'id_confirm'));?>"><span class="fl">我的认证</span><i class="icons icon-more fr"></i><span class="num fr" id="notAuthentication">点击认证</span></a><?php endif; ?>

                </li>
                <li>
                    <i class="icons icon-my-address"></i>
                    <a href="<?php echo U('Mobile/UserOrder/receipt');?>"><span class="fl">我的收货地址</span><i class="icons icon-more fr"></i><span class="num fr" id="address"><?php echo ($address_info); ?></span>
                    </a>
                </li>
                <li>
                    <i class="icons icon-my-coupon"></i>
                    <a href="<?php echo U('Mobile/Users/coupon');?>?pageType=can_use"><span class="fl">我的优惠券</span><i class="icons icon-more fr"></i><span class="num fr" id="couponNum"><?php echo ($coupon_num); ?></span>
                    </a>
                </li>
                <li>
                    <i class="icons icon-virtual-currency"></i>
                    <a href="<?php echo U('Mobile/Users/virtualCash');?>"><span class="fl">我的购物币</span><i class="icons icon-more fr"></i></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="user-section page-box">
        <div class="sec-content">
            <ul class="items">
                <li>
                    <i class="icons icon-help"></i>
                    <a href="<?php echo U('Mobile/Help/center');?>"><span class="fl">帮助中心</span><i class="icons icon-more fr"></i></a>
                </li>
                <li style="display: none;">
                    <i class="icons icon-customer"></i>
                    <a href="<?php echo U('Mobile/Contact/index');?>"><span class="fl">联系客服</span><i class="icons icon-more fr"></i></a>
                </li>
                <li>
                    <i class="icons icon-join"></i>
                    <?php if($store['status'] == 1): ?><a href="<?php echo U('Mobile/Store/index');?>"><span class="fl">我的店铺</span><i class="icons icon-more fr"></i></a>
                        <?php elseif($store['status'] == 2): ?>
                        <a href="<?php echo U('Mobile/Store/apply');?>?action=edit"><span class="fl">加盟店申请</span><i class="icons icon-more fr"></i><span class="num fr" id="store">审核中</span></a>
                        <?php elseif($store['status'] == 3): ?>
                        <a href="<?php echo U('Mobile/Contact/index');?>"><span class="fl">我的店铺</span><i class="icons icon-more fr"></i><span class="num fr" id="store1">暂停使用,请联系客服</span></a>
                        <?php else: ?>
                        <a href="<?php echo U('Mobile/Store/apply');?>?action=add"><span class="fl">加盟店申请</span><i class="icons icon-more fr"></i></a><?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
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
</body>
<script>

    $(function(){
        /*公用底部点击样式变换 放再公用的layout里面*/
        $(".img-box a img").lazyload({effect : "fadeIn"});
        $(".footer ul").find("li").on("touchstart",function(){
            $(this).addClass("sel").siblings().removeClass("sel");
        })
    })


</script>
</html>