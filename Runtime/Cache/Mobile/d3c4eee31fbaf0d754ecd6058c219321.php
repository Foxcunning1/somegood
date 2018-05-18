<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>订单详情</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.fnTimeCountDown.js">

    </script>
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="javascript:history.back();" class="icons icon-back"></a>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <h2 class="title">订单详情</h2>
    <!--标题-->
</div>
<div class="step1 step">
    <div class="step-in">
        <div class="num fl">订单号：<span><?php echo ($order_sn); ?></span></div>
        <!--还没有完成的订单有这个等待收货-->
        <?php if($list2['status'] == 0 ): ?><div class="wait fr">待付款</div>
        <?php elseif($list2['status'] == 1 ): ?>
        <div class="wait fr">等待发货</div>
        <?php elseif($list2['status'] == 2 ): ?>
        <div class="wait fr">等待收货</div>
        <?php else: ?>
        <div class="wait fr">订单已完成</div><?php endif; ?>
    </div>
    <?php if($list2['status'] == 0): ?><div class="fnTimeCountDown step-in" data-end="<?php echo (date("Y/m/d H:i:s",$list2["cancel_time"])); ?>" style="border-bottom:none; color: #f15353;text-align:left">剩余:</div><?php endif; ?>
      <?php if($list2['status'] != 0 and $list2['status'] != 1 and $list2['delivery_way'] != 0 and $list2['is_son'] != 0): ?><!-- and $list2['delivery_way'] neq 0 and $list2['is_son'] neq 0 -->
      <a href="<?php echo ($url); ?>" class="thanks-a">
      <div class="thanks fl"><i class="icons icon-wuliu" ></i><span>查看物流信息</span></div>
      </a>
      <?php else: ?>
      <div class="thanks fl"><i class="icons icon-wuliu"></i><span>感谢你在3好连锁购物!此订单暂无物流信息!</span></div><?php endif; ?>
        <i class="icons icon-more fr"></i>
        <!--完成的订单有这个div-->
        <?php if($list2['status'] == 3): ?><div class="signet"></div><?php endif; ?>
</div>
<?php if($list2['delivery_way'] == 1 && $isWho != 'seller' ): ?><div class="step2 step">
    <div class="step-in">
        <div class="s-top">
            <div class="fl"><i class="icons icon-step-name"></i><span><?php echo ($list2["consignee"]); ?></span></div>
            <div class="fr"><i class="icons icon-step-phone"></i><span><?php echo ($list2["mobile"]); ?></span></div>
        </div>
        <div class="s-bot">
            <?php echo ($list2["dizhi"]); ?>
        </div>
    </div>
</div>
<?php else: endif; ?>
<div class="step3 step">
        <div class="step-title">
            <div class="fl"><i class="icons icon-step-shop"></i><span>
              <?php if($list2['is_son'] == 0 ): ?>三好连锁(订单状态:已拆单)<?php else: ?>商家名:<?php echo ($list2["user_name"]); endif; ?>
            </span></div>
        </div>
        <div class="step-content">
          <?php if(is_array($list2['info'])): $i = 0; $__LIST__ = $list2['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Mobile/Goods/detail',array('id'=>$vo['goods_id']));?>">
                <div class="step-item">
                    <div class="pdiv" >
                        <div class="item-l" ><img src="/somegood/Public/uploads/<?php echo ($vo['goods_thumb']); ?>_c200x200" alt="" /></div>
                        <div class="item-c">
                            <p class="step-item-name"><?php echo ($vo['goods_title']); ?></p>
                            <p class="step-item-num">X<?php echo ($vo['counts']); ?></p>
                        </div>
                        <div class="item-r">￥<?php echo ($vo['price']); ?></div>
                    </div>
                </div>
            </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
<div class="step4 step">
    <div class="step-in"><span class="fl">支付方式</span><span class="fr">
        <?php if($list2["delivery_way"] == 0): ?>到付<?php else: ?>微信支付<?php endif; ?>
    </span></div>
    <div class="step-in"><span class="fl">配送信息</span>
        <span class="fr"><?php if($list2["status"] > 1 and $list2["is_son"] != 0): echo ($list2["name"]); else: ?>暂无<?php endif; ?></span>
    </div>
</div>
<div class="step5 step">
    <div class="step-in"><span class="fl">商品总额</span><span class="fr">￥<?php echo ($list2["original_price"]); ?></span></div>
    <!-- <div class="step-in"><span class="fl">+运费</span><span class="fr">0</span></div> -->
    <div class="step-in"><span class="fl">-优惠币抵扣</span><span class="fr">
      <?php if(!empty($list2['type_money'])): echo ($list2["type_money"]); else: ?>0<?php endif; ?>
    </span></div>
    <div class="step-in"><span class="fl">-购物币抵扣</span><span class="fr"><?php echo ($list2["share_bill"]); ?></span></div>
    <?php if($list2["delivery_way"] == 1): ?><div class="step-in"><span class="fl">-运费</span><span class="fr"><?php echo ($list2["pay_money"]); ?></span></div><?php endif; ?>
    <div class="step-in"><span class="fl">备注</span><span class="fr" style="color:#ffcc99;">订单结算按商品总额结算</span></div>
    <div class="step-result">
        <div class="fr">
            <div class="pay">实付款：<span class="money">￥<?php echo ($list2["money"]); ?></span></div>
            <p class="pay-time">下单时间：<span><?php echo (date("Y-m-d H:i",$list2["add_time"])); ?></span></p>
        </div>
    </div>
    <!-- <div class="step-in" ><span class="fr">
        <?php if($list2['status'] == 0): ?><div class="bottom-but" ><a href="javascript:;" class="bb-btn1-box" id="deleteOrderBtn"><div class="bb-btn1" style="color:black;">取消订单</div></a></div><?php endif; ?>
    </span></div> -->

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
<!-- <div class="btn-bar" id="btnBar" style="width:25%;float:right;">
    <div class="bb-info" id="bbInfo">
        <div class="bottom-but"><a href="javascript:;" class="bb-btn1-box" id="publishBtn"><div class="bb-btn1">满意度评价</div></a></div>
        <div class="bottom-but"><a href="javascript:;" id="buyAgain" name="2" status="5"class="bb-btn1-red">再次购买</a></div>
        <div class="bottom-but"><a href="javascript:;" id="aftersale" href="" class="bb-btn1-box"><div class="bb-btn1">申请售后</div></a></div>
        <?php if($list2['status'] == 0): ?><div class="bottom-but" ><a href="javascript:;" class="bb-btn1-box" id="deleteOrderBtn"><div class="bb-btn1">取消订单</div></a></div><?php endif; ?>
    </div>
</div> -->
  <script type="text/javascript">
        $(".fnTimeCountDown").fnTimeCountDown({endTipsTxt:'已取消'})

        $("#deleteOrderBtn").on("touchstart",function(){
            var order_sn=$('.num').val();
            $.ajax()
        })
  </script>
</body>
</html>