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
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/dist/dropload.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/TouchSlide/TouchSlide.1.1.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/region.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/dist/dropload.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <script type="text/javascript">
        var usersLng = <?php echo (session('lng')); ?>;//用户所在的经纬度
        var usersLat = <?php echo (session('lat')); ?>;
        if(isWeixin()) {
            usersLng = parseFloat(localStorage.getItem("longitude"));
            usersLat = parseFloat(localStorage.getItem("latitude"));
        }
    </script>
</head>
<body>
<!--店铺主页-->
<div class="page-title">
    <!--返回按钮-->
    <div class="back-box">
        <a href="<?php echo U('Mobile/Store/detail');?>?shopid=<?php echo ($shop_id); ?>" class="icons icon-back"></a>
    </div>
    <h2 class="title">店铺列表</h2>
    <!--返回按钮-->
</div>
<!--店铺主页-->
<!--所属店铺-->
<div class="goods-filter">
    <ul class="filter-ul">
        <li class="area" id="area"><span>区域</span></li>
        <li class="orderBtn distaceBtn <?php if(($order) == "2"): ?>selected<?php endif; ?>" data-status="2"><span>距离</span></li>
        <li class="orderBtn price <?php if(($order) != "2"): ?>selected<?php endif; ?> <?php if(($order) == "1"): ?>click<?php endif; ?>" data-status="<?php if($order != '2'): echo ($order); else: ?>0<?php endif; ?>"><span>销售</span></li>
    </ul>
    <!--商品区域-->
    <div class="area-choose" id="areaChoose">
        <div class="choose-box">
            <ul>
                <li <?php if(($area_id) == "0"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Mobile/Store/list');?>?area_id=0&order=<?php echo ($order); ?>"><span>全部区域</span><i class="icons icon-select"></i></a></li>
                <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area): $mod = ($i % 2 );++$i;?><li <?php if(($area_id) == $area['id']): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Mobile/Store/list');?>?area_id=<?php echo ($area["id"]); ?>&order=<?php echo ($order); ?>"><span><?php echo ($area["name"]); ?></span><i class="icons icon-select"></i></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
    <!--商品区域-->
</div>
<!--商品筛选部分-->
<!--店铺列表-->
<div class="page-box shop-list pd-t18">
    <ul class="collection-shop-list">
        <?php if(is_array($storeList)): $i = 0; $__LIST__ = $storeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
            <div class="items-user">
                <a href="<?php echo U('Mobile/Store/detail',array('shopid'=>$item['sid']));?>" class="user-img fl">
                    <img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="<?php if(empty($item["logo"])): ?>/somegood/Public/statics/mobile/images/default_img.png<?php else: ?>/somegood/Public/uploads/<?php echo ($item["logo"]); ?>_c200x200<?php endif; ?>" alt="<?php echo ($item["store_name"]); ?>" />
                </a>
                <div class="name-and-location fl">
                    <a href="<?php echo U('Mobile/Store/detail',array('shopid'=>$item['sid']));?>" class="name"><?php echo ($item["store_name"]); ?></a>
                    <div style="padding:3px 0; font-size:0.22rem; color:#666;line-height:0.26rem;"><?php echo (msubstr($item["products"],0,30)); ?></div>
                    <a href="http://api.map.baidu.com/marker?location=<?php echo ($item["lat"]); ?>,<?php echo ($item["lng"]); ?>&title=<?php echo ($item["store_name"]); ?>&content=<?php echo ($item["address"]); ?>&output=html " class="location-a">
                        <span class="location"><i class="icons icon-location fl"></i><em data-lng="<?php echo ($item["lng"]); ?>" data-lat="<?php echo ($item["lat"]); ?>" class="distance address fl">0</em>Km</span>
                    </a>
                </div>
            </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<!--店铺列表-->
<div class="over-lay"></div>
<div class="over-lay-small"></div>
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
        $(".collection-shop-list li img").lazyload({effect : "fadeIn"});
        //点击排序
        $(".orderBtn").on("touchstart",function(){
            var orderType = $(this).attr("data-status");
            var jumpUrl = "<?php echo U('Mobile/Store/list');?>?area_id=<?php echo ($area_id); ?>&order=2";
            if($(this).hasClass("price")){
                if(orderType=="0"){
                    jumpUrl = "<?php echo U('Mobile/Store/list');?>?area_id=<?php echo ($area_id); ?>&order=1";
                }else{
                    jumpUrl = "<?php echo U('Mobile/Store/list');?>?area_id=<?php echo ($area_id); ?>&order=0";
                }
            }
            window.location.href = jumpUrl;
        })
        //区域被选中信息
        var areaName = "区域";
        var selectText = $("#areaChoose").find("li.sel").eq(0).find("span").text();
        if(selectText){
            areaName = selectText;
        }
        $("#area").text(areaName);
        /*区域下拉*/
        var h = $(".area-choose").height();
        var wh = $(window).height();
        $(".over-lay-small").height(wh);
        /*区域选择*/
        $(".goods-filter li.area").on("touchstart",function(){
            if($(this).hasClass("done")){
                $(".area-choose").css("max-height",h);
                $(".over-lay-small").hide();
                $(this).removeClass("done");
                $(".area-choose").css("max-height",0);
                //$(".area-choose").removeClass("showBox");
                $(".area-choose").css("max-height",0);
                $("body").css("overflow","auto");
            }else{
                $(".area-choose").css("max-height",0);
                $(".over-lay-small").show();
                $(this).addClass("done");
                $(".area-choose").css("max-height",h);
                $(".area-choose").addClass("showBox");
                $("body").css("overflow","hidden");
            }
        })
        /*区域收起*/
        $(document).on('click',function(e){
            var e = e || window.event; //浏览器兼容性
            var elem = e.target || e.srcElement;
            while (elem) {
                //循环判断至跟节点，防止点击的是div子元素
                if (elem.id && elem.id=='area') {
                    return;
                }
                elem = elem.parentNode;
            }
            $(".area-choose").css("max-height",h);
            $(".over-lay-small").hide();
            $(this).removeClass("done");
            $(".area-choose").css("max-height",0);
            //$(".area-choose").removeClass("showBox");
            $(".area-choose").css("max-height",0);
            $("body").css("overflow","auto");
        });
        calcDistance(".distance");//绑定计算距离
    })
    //滚动页面ajax自动获取下一页内容
    var page=2; //有效求购的页码
    var totalPage = <?php echo ($pageCount); ?>; //有效求购页的总页码，会从后台获
    var ajaxUrl="<?php echo U('Mobile/Store/list');?>?area_id=<?php echo ($area_id); ?>&order=<?php echo ($order); ?>&isajax=1";//ajax请求地址
    $(function(){
        var tabLoadEnd = false;
        // dropload
        var dropload = $('.page-box').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                // 加载菜单一的数据
                if(page <= totalPage){
                    $.ajax({
                        url:ajaxUrl,
                        type:"post",
                        dataType:"json",
                        data:{
                            'p':page
                        },
                        success:function(data){
                            var str='';
                            $.each(data.data.list,function(index, vo) {
                                str += "<li> ";
                                str += "<div class=\"items-user\">";
                                str += "<a href=\"<?php echo U('Mobile/Store/detail');?>?shopid="+vo['sid']+"\" class=\"user-img fl\">";
                                str += "<img data-original=\"";
                                if(vo['logo']==""||vo['logo']=="NULL"){
                                    str += "/somegood/Public/statics/mobile/images/default_img.png";
                                }else{
                                    str += "/somegood/Public/uploads/"+ vo['logo']+"_c200x200";
                                }
                                str += "\"";
                                str += "src=\"/somegood/Public/statics/mobile/images/default_img.png\" alt=\""+vo['store_name']+"\" />";
                                str += "</a>";
                                str += "<div class=\"name-and-location fl\">";
                                str += "<a href=\"<?php echo U('Mobile/Store/detail');?>?shopid="+vo['sid']+"\" class=\"name\">";
                                str += vo['store_name']+"</a>";
                                str += "<a href=\"http://api.map.baidu.com/marker?location="+vo['lat']+","+vo['lng']+"&title="+vo['store_name']+"&content="+vo['address']+"&output=html \">";
                                str += "<span class=\"location\"><i class=\"icons icon-location fl\"></i><em data-lng=\""+vo['lng']+"\" data-lat=\""+vo['lat']+"\" class=\"distance address fl\">0</em>Km</span>";
                                str += "</a>";
                                str += "</div>";
                                str += "</div>";
                                str += "</li>";
                            })
                            $('.collection-shop-list').append(str);
                            calcDistance(".distance");//绑定计算距离
                            //图片延迟加载
                            $(".collection-shop-list li img").lazyload({effect : "fadeIn"});
                            // 每次数据加载完，必须重置
                            me.resetload();
                            totalPage = data.data.pageCount;
                        },
                        error: function(xhr, type){
                            alert('Ajax error!');F
                            // 即使加载出错，也得重置
                            me.resetload();
                        }
                    });
                    page++;
                }else{
                    // 数据加载完
                    tabLoadEnd = true;
                    // 锁定
                    me.lock();
                    // 无数据
                    me.noData();
                    me.resetload();
                }
                // 加载菜单二的数据
            }
        });
        // 如果数据没有加载完
        if(!tabLoadEnd){
            // 解锁
            dropload.unlock();
            dropload.noData(false);
        }else{
            // 锁定
            dropload.lock('down');
            dropload.noData();
        }
        // 重置
        dropload.resetload();
    });
</script>
</body>
</html>