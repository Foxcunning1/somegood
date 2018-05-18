<?php if (!defined('THINK_PATH')) exit(); if(($isAjax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="item">
            <div class="only-position">
                <!--<span class="icons icon-location fl"></span>-->
                <a href="<?php echo U('Mobile/Users/orderdetail');?>?order_sn=<?php echo ($vo["order_sn"]); ?>" class="shop-a fl">订单号:<?php echo ($vo["order_sn"]); ?></a>
                <div style="clear:both"></div>
              <?php if($vo['status'] == 3): ?><div class="signet"></div><?php endif; ?>
            </div>
            <div class="imgs-box">
                <?php if(is_array($vo['goods_thumb'])): foreach($vo['goods_thumb'] as $k=>$gp): ?><a href="<?php echo U('Mobile/Users/orderDetail');?>?order_sn=<?php echo ($vo["order_sn"]); ?>"><img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="/somegood/Public/uploads/<?php echo ($gp); ?>_c200x200" alt="<?php echo ($vo["goods_title"]["$k"]); ?>" /></a><?php endforeach; endif; ?>
            </div>
            <div class="split-row">
                <span class="true-payed fl">订单总价：</span><span class="imb-num fl">¥<?php echo ($vo["money"]); ?></span>
                <div class="fr">
                    <?php switch($pageType): case "1": ?><span class="wait-pay">买家<time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$vo["pay_time"])); ?>"></time>已支付,请尽快发货</span><?php break;?>
                        <?php case "2": ?><span class="wait-pay gray"><time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$vo["ship_time"])); ?>"></time>已发货,等待买家收货</span><?php break;?>
                        <?php case "3": ?><span class="wait-pay green">买家<time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$vo["complete_time"])); ?>"></time>确认收货,订单完成</span><?php break;?>
                        <?php case "0": ?><span class="wait-pay">买家暂未付款,订单于<time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$vo["add_time"])); ?>"></time>提交</span><?php break; endswitch;?>
                </div>
            </div>
            <div class="bottom-bar">

                <div class="imb-btn-box fr">
                    <?php switch($pageType): case "1": ?><a href="<?php echo U('Mobile/SecondHand/deliverGoods');?>?order_sn=<?php echo ($vo["order_sn"]); ?>">去发货</a><?php break;?>
                        <?php case "2": ?><a href="https://m.kuaidi100.com/index_all.html?type=<?php echo ($vo["type"]); ?>&postid=<?php echo ($vo["logistics_sn"]); ?>&callbackurl=<?php echo (C("MOBILE_URL")); ?>/somegood/Mobile/SecondHand/order.html?pageType=1">查看物流</a><?php break;?>
                        <?php case "3": ?><a href="https://m.kuaidi100.com/index_all.html?type=<?php echo ($vo["type"]); ?>&postid=<?php echo ($vo["logistics_sn"]); ?>&callbackurl=<?php echo (C("MOBILE_URL")); ?>/somegood/Mobile/SecondHand/order.html?pageType=1">查看物流</a><?php break;?>
                        <?php case "0": if($vo['delivery_way'] == 0 and $vo['is_son'] == 2): ?><a href="javascript:void(0);" class="refuse-btn fr" data-value="<?php echo ($vo["order_sn"]); ?>">改价</a><?php endif; break; endswitch;?>
                </div>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
<?php else: ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>订单管理</title>
    <link rel="stylesheet" href="/somegood/Public/dist/dropload.css">
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.validateForm.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="<?php echo U('Mobile/SecondHand/index');?>" class="icons icon-back"></a>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <h2 class="title">订单管理</h2>
    <!--标题-->
</div>
<div class="already-title">
    <ul>
        <li class="<?php if($pageType == 1): ?>sel<?php endif; ?>"><a href="<?php echo U('Mobile/SecondHand/order');?>?pageType=1">待发货</a></li>
        <li class="<?php if($pageType == 2): ?>sel<?php endif; ?>"><a href="<?php echo U('Mobile/SecondHand/order');?>?pageType=2">待完成</a></li>
        <li class="<?php if($pageType == 3): ?>sel<?php endif; ?>"><a href="<?php echo U('Mobile/SecondHand/order');?>?pageType=3">已完成</a></li>
        <li class="<?php if($pageType == 0): ?>sel<?php endif; ?>"><a href="<?php echo U('Mobile/SecondHand/order');?>?pageType=0">待付款</a></li>
    </ul>
</div>
<!--点击选项卡控制near-content显示和隐藏-->
<!--订单-->
<div class="div_orderlist pd-t18" >
    <div class="allOrders"></div>
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
<!-- jQuery1.7以上 或者 Zepto 二选一，不要同时都引用 -->
<script src="/somegood/Public/dist/dropload.min.js"></script>
<script>
    $(function(){
        $(".imgs-box a img").lazyload({effect : "fadeIn"});
        $(".timeago").timeago();
    })
    var page=1; //当前页的页码
    var allpage=<?php echo ($pageCount); ?>; //总页码，会从后台获
    var pageType='<?php echo ($pageType); ?>'; //页面类型，会从后台获
    var ajaxUrl="<?php echo U('Mobile/SecondHand/order');?>?pageType=<?php echo ($pageType); ?>";//ajax请求地址
    $(function(){
        var dropload = $('.div_orderlist').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                if(page<=allpage){
                    $.ajax({
                        url:ajaxUrl,
                        type:"post",
                        dataType:"html",
                        data:{
                            'p':page,
                            'pageType':pageType,
                        },
                        success:function(data){
                            var str = data;
                            $('.allOrders').append(str);
                            $(".timeago").timeago();
                            $(".imgs-box a img").lazyload({effect : "fadeIn"});
                            bindRefusBtn();
                            // 每次数据加载完，必须重置
                            me.resetload();
                            allpage = <?php echo ($pageCount); ?>;
                        },
                        error: function(xhr, type){
                            alert('Ajax error!');
                            // 即使加载出错，也得重置
                            me.resetload();
                        }
                    });
                    page++;
                }else{
                    // 锁定
                    me.lock();
                    // 无数据
                    me.noData();
                    me.resetload();
                }
            }
        });
    });
    function bindRefusBtn(){
        $("a.refuse-btn").on("touchstart",function(){
            $("body").minDialog({
                title: '更改订单价格',
                content: "<div class='refuse-reasons'>"+
                "<form class='refuse-form' id='refuseForm' url='<?php echo U('Mobile/Seller/editPrice');?>'>"+
                "<dl>" +
                "<dd><span>￥<input type='number' name='edit_price' id='edit_price' class=\"edit-price\" placeholder='请输入价格' required/></span></dd>" +
                "</dl>" +
                "<input type='hidden' id='order_sn' name='order_sn' value='"+$(this).attr("data-value")+"' />"+
                "<input type='button' class='edit-submit-btn' id='submitBtn' value='提交' />"+
                "</form>"+
                "</div>",
            });
            bindSubmitBtn();
        })
    }
    function bindSubmitBtn() {
        $("#submitBtn").on("touchstart",function(){
            var edit_price = $("#edit_price").val();
            var order_sn =$('#order_sn').val();
            $.ajax({
                type: "POST",
                url: "<?php echo U('Mobile/Seller/editPrice');?>",
                data: {"order_sn":order_sn,"edit_price":edit_price},
                dataType: 'json',
                success: function(data){
                    if(data.status==1){
                        $("body").minDialog({
                            isMask:true,
                            isAutoClose:true,
                            isCloseAll:true,
                            closeTime:800,
                            content:data.info,
                        });
                        setTimeout(function(){
                            window.location.href="/somegood/Mobile/SecondHand/order.html?pageType=1";
                        },800)
                    }else{
                        $("body").minDialog({
                            isMask:true,
                            isAutoClose:true,
                            isCloseAll:true,
                            closeTime:1000,
                            content:data.info,
                        });
                    }
                },
                error:function (data) {
                }
            });
        })
    }
</script>
</body>
</html><?php endif; ?>