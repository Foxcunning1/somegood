<?php if (!defined('THINK_PATH')) exit(); if(($isAjax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$co): $mod = ($i % 2 );++$i;?><div class="new-coupon">
            <div class="new-bdcolor nj-new-none bd-bd">
                <div class="newCou-bg dq-bg"></div>
                <div class="newCou-item dq-icon-color">
                    <div class="newCou-title "><?php echo ($co["type_name"]); ?></div>
                    <div class="newCou-content cf">
                        <div class="fl">
                            <div class="newCou-l">
                                <div class="newCou-pri-content nj-content">
                                    <em>￥</em>
                                    <span class="newCou-price"><?php echo ($co["type_money"]); ?></span>
                                </div>
                                <span class="newCou-info nnewCou-info">满<?php echo ($co["min"]); ?>可用</span>
                            </div>
                        </div>
                        <div class="newCou-r nj-r">
                            <span class="newCou-date-name"><?php if($co['is_all'] == 1): ?>全场通用<?php else: ?>仅可购买<?php echo (substr($co["goods_category"],0,-1)); ?>时使用<?php endif; ?></span>
                            <span class="newCou-date-content"><?php echo (date("Y.m.d",$co["use_start_date"])); ?>-<?php echo (date("Y.m.d",$co["use_end_date"])); ?></span>
                        </div>
                    </div>
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
    <title>我的优惠券</title>
    <link rel="stylesheet" href="/somegood/Public/dist/dropload.css">
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="<?php echo U('Mobile/Users/index');?>" class="icons icon-back"></a>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <h2 class="title">我的优惠券</h2>
    <!--标题-->
</div>
<div class="already-title">
    <ul class="tab">
        <li class="item <?php if($pageType == can_use): ?>sel<?php endif; ?>"><a href="<?php echo U('Mobile/Users/coupon');?>?pageType=can_use" class="available">可用<em id="available">(<?php echo ($can_used); ?>)</em></a></li>
        <li class="item <?php if($pageType == used): ?>sel<?php endif; ?>"><a href="<?php echo U('Mobile/Users/coupon');?>?pageType=used" class="unavailable">使用记录<em id="unavailable1">(<?php echo ($used_num); ?>)</em></a></li>
        <li class="item <?php if($pageType == over): ?>sel<?php endif; ?>"><a href="<?php echo U('Mobile/Users/coupon');?>?pageType=over" class="unavailable">过期<em id="unavailable2">(<?php echo ($cant_used); ?>)</em></a></li>
    </ul>
</div>
<!--点击选项卡控制near-content显示和隐藏-->
<div class="already-content-box">
    <div class="already-content available-coupon"></div>
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
    var page=1; //当前页的页码
    var allpage=<?php echo ($pageCount); ?>; //总页码，会从后台获
    var pageType='<?php echo ($pageType); ?>'; //页面类型，会从后台获
    var ajaxUrl="<?php echo U('Mobile/Users/coupon');?>?pageType=<?php echo ($pageType); ?>";//ajax请求地址
    $(function(){
        var dropload = $('.already-content-box').dropload({
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
                            $('.already-content').append(str);
                            // 每次数据加载完，必须重置
                            me.resetload();
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
</script>
</body>
</html><?php endif; ?>