<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" href="/somegood/Public/dist/dropload.css">
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <title>我的收藏</title>
    <script type="text/javascript">
        var usersLng = <?php echo (session('lng')); ?>;//用户所在的经纬度
        var usersLat = <?php echo (session('lat')); ?>;
        if(isWeixin()) {
            usersLng = parseFloat(localStorage.getItem("longitude"));
            usersLat = parseFloat(localStorage.getItem("latitude"));
        }
    </script>
</head>
<body style="height: auto;">
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="javascript:history.back();" class="icons icon-back"></a>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <h2 class="title">我的收藏</h2>
    <!--标题-->
</div>
<div class="already-title">
    <ul class="tab">
        <li class="item sel"><a href="javascript:void(0)">新品</a></li>
        <li class="item"><a  href="javascript:void(0)">二手</a></li>
    </ul>
</div>

<!---->
<div class="already-content-box">
    <div class="my-collection-list">
        <ul class="collection-list" id="sellerGoods"></ul>
        <ul class="collection-list" id="erGoods"></ul>
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
<!-- jQuery1.7以上 或者 Zepto 二选一，不要同时都引用 -->
<script src="/somegood/Public/dist/dropload.min.js"></script>
<script>
    $(function(){
        /*公用底部点击样式变换 放再公用的layout里面*/
        $(".footer ul").find("li").on("touchstart",function(){
            $(this).addClass("sel").siblings().removeClass("sel");
        })
    })
    var page0=1; //商品的页码
    var page1=1; //店铺的页码
    var allpage0=<?php echo ($pageCount0); ?>; //商品页的总页码，会从后台获
    var allpage1=<?php echo ($pageCount1); ?>; //店铺页的总页码，会从后台获
    var ajaxUrl="<?php echo U('Mobile/Users/favorites');?>";//ajax请求地址
    var imgPath = "/somegood/Public/";
    $(function(){
        var itemIndex = 0;
        var tab1LoadEnd = false,tab2LoadEnd = false,tabLoadEnd =false;
        // tab
        $('.tab .item').on('click',function(){
            var $this = $(this);
            itemIndex = $this.index();
            $this.addClass('sel').siblings('.item').removeClass('sel');
            $('.my-collection-list ul').eq(itemIndex).show().siblings('ul').hide();

            // 如果选中菜单一
            if(itemIndex == '0'){
                // 如果数据没有加载完
                if(!tab1LoadEnd){
                    // 解锁
                    dropload.unlock();
                    dropload.noData(false);
                }else{
                    // 锁定
                    dropload.lock('down');
                    dropload.noData();
                }
                // 如果选中菜单二
            }else if(itemIndex == '1'){
                if(!tab2LoadEnd){
                    // 解锁
                    dropload.unlock();
                    dropload.noData(false);
                }else{
                    // 锁定
                    dropload.lock('down');
                    dropload.noData();
                }
            }
            // 重置
            dropload.resetload();
        });
        // dropload
        var dropload = $('.my-collection-list').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                // 加载菜单一的数据
                if(itemIndex == '0'){
                    ajaxGetDataList(imgPath,"#sellerGoods",page0,allpage0,ajaxUrl,0,me,"tab1LoadEnd","<?php echo U('Mobile/Goods/detail');?>");
                    page0++;
                    calcDistance(".distance");
                    $(".g-img a img").lazyload({effect : "fadeIn"});
                    // 加载菜单二的数据
                }else if(itemIndex == '1'){
                    ajaxGetDataList(imgPath,"#erGoods",page1,allpage1,ajaxUrl,1,me,"tab2LoadEnd","<?php echo U('Ershou/Goods/detail');?>");
                    page1++
                    calcDistance(".distance");
                    $(".g-img a img").lazyload({effect : "fadeIn"});
                }
            }
        });
    });
    $(function(){
        /*公用底部点击样式变换 放再公用的layout里面*/
        $(".footer ul").find("li").on("touchstart",function(){
            $(this).addClass("sel").siblings().removeClass("sel");
        })
    })
    //关注/取消
    function cancelFavorite(id,favorite_type){
        $.ajax({
            method:'post',
            dataType:'json',
            url:"<?php echo U('Mobile/Goods/collect');?>",
            data:{
                'goods_id':id,
                'favorite_type':favorite_type,
            },
            success:function(data){
                if(data.status==1){
                    //操作成功
                        //取消关注
                        $('body').minDialog({
                            content:data.info,
                            isMask:true,
                            isAutoClose:true,
                            isCloseAll:true,
                            closeTime:700,
                        })
                        $("#favorite_"+favorite_type+"_"+id).remove();
                }else{
                    //操作失败
                    $('body').minDialog({
                        content:data.info,
                        isMask:true,
                        isAutoClose:true,
                        isCloseAll:true,
                        closeTime:700,
                    })
                }
            }
        });
    }
</script>
</body>
</html>