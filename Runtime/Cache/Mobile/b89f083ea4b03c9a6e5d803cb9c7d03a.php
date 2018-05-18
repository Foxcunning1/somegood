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
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <style type="text/css">
        .right-show-box{ background-color: #fff;}
    </style>
</head>
<body>
    <div class="page-title position-title">
        <!--返回按钮-->
        <div class="back-box">
            <a href="javascript:history.back();" class="icons icon-back"></a>
        </div>
        <h2 class="title">商品分类</h2>
        <!--返回按钮-->
    </div>
    <!--左边分类-->
    <div class="left-classify-box">
        <div class="left-over-box">
            <ul>
                <li <?php if(($pid) == "0"): ?>class="sel"<?php endif; ?> data-id="0"><a href="javascript:void(0);">热门推荐</a></li>
                <?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($vo["id"] == $catId): ?>class="sel"<?php endif; ?> data-id="<?php echo ($vo["id"]); ?>"><a href="javascript:void(0);"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "$empty" ;endif; ?>
            </ul>
        </div>
    </div>
    <!--左边分类-->
    <!--右边分类展示-->
    <div class="right-show-box">
        <div class="right-show-box-content">
            <div class="category-lists">
                <div class="right-section">
                    <div class="section-top"><span>猜你喜欢</span></div>
                    <div class="section-bottom">
                        <ul>
                            <?php if(is_array($recCategoryList)): $i = 0; $__LIST__ = $recCategoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Mobile/Goods/list');?>?catid=<?php echo ($item["id"]); ?>"><img src="/somegood/Public/uploads/<?php echo ($item["img_url"]); ?>" alt=""/><span><?php echo ($item["title"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="right-section">
                    <div class="section-top"><span>热门分类</span></div>
                    <div class="section-bottom">
                        <ul>
                            <?php if(is_array($hotCategoryList)): $i = 0; $__LIST__ = $hotCategoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Mobile/Goods/list');?>?catid=<?php echo ($item["id"]); ?>"><img src="/somegood/Public/uploads/<?php echo ($item["img_url"]); ?>" alt=""/><span><?php echo ($item["title"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-show-box" style="display: none;">
        <div class="right-show-box-content" id="categoryBox"></div>
    </div>
    <!--公用底部-->
    	<div class="footer">
        <ul>
            <li><a href="<?php echo U('Mobile/Index/index');?>"><i class="icons icon-home"></i><span>首页</span></a></li>
            <li><a href="<?php echo U('Mobile/Goods/category');?>"><i class="icons icon-classify"></i><span>分类</span></a></li>
            <li><a href="<?php echo U('Mobile/Cart/index');?>"><i class="icons icon-cart"></i><span class="icon-num" id="cartNum">0</span><span>购物车</span></a></li>
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
        $("#shopKeepper").on("click",function(event){
            $(".shopkeeper-box").toggleClass("click");
            event.stopPropagation();
        })
        /*点击其他地方关闭右下角可选入口*/
        $(document).bind('click',function(e){
            var e = e || window.event; //浏览器兼容性
            var elem = e.target || e.srcElement;
            while (elem) { //循环判断至跟节点，防止点击的是div子元素
                if (elem.id && elem.id=='test') {
                    return;
                }
                elem = elem.parentNode;
            }
            $(".shopkeeper-box").addClass("click");
            $(".shopkeeper-box").removeClass("click");
        })
        /*公用底部点击样式变换*/
        $(".footer ul").find("li").on("click",function(){
            $(this).addClass("sel").siblings().removeClass("sel");

        })


        $(function() {
			//获取购物车商品的数量
            $.ajax({
                type: 'POST',
                url: "<?php echo U('Mobile/Cart/getCartNum');?>",
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        $("#cartNum").text(data.data);
                    } else {
                        $("#cartNum").text("0");
                    }
                }
            })
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
						}
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
            //左侧菜单点击
            $(".left-classify-box").find("li").on("click",function(){
                $(this).addClass("sel").siblings().removeClass("sel");
                if(typeof($(this).attr("data-id"))!="undefined"){
                    var ajaxUrl = "<?php echo U('Mobile/Goods/category');?>";
                    var id = $(this).attr("data-id");
                    $(".right-show-box").hide();
                    if(id=="0"){
                        $(".right-show-box").eq(0).show();
                    }else{
                        $(".right-show-box").eq(1).show();
                        $.ajax({
                            url:ajaxUrl,
                            type:"post",
                            dataType:"json",
                            data: {
                                'id': id
                            },
                            cache: true,
                            beforeSend:function(){
                                $(".right-show-box-content").append("<div id=\"loadingBox\" class=\"loading-box\"><div class=\"loadEffect\"><span></span> <span></span> <span></span><span></span> <span></span> <span></span> <span></span> <span></span> </div> </div>");
                            },
                            success:function(data){
                                if(data.status==1){
                                    $("#categoryBox").html("");
                                    var strHtml = "";
                                    $.each(data.data,function(index,val){
                                        strHtml += "<div class=\"right-section\">";
                                        strHtml += "<div class=\"section-top\"><span>" +val['title']+ "</span></div>";
                                        strHtml += "<div class=\"section-bottom\">";
                                        strHtml += "<ul>";
                                        var tempHtml = "";
                                        if(typeof(val['children'])!="undefined") {
                                            $.each(val['children'],function(i,sub){
                                                tempHtml += "<li><a href=\"<?php echo U('Mobile/Goods/list');?>?catid="+sub['id']+"\"><img src=\"/somegood/Public/uploads/"+sub['img_url']+"\" alt=\""+sub['title']+"\"/><span>"+sub['title']+"</span></a></li>"
                                            })
                                        }
                                        strHtml += tempHtml;
                                        strHtml += "</ul>";
                                        strHtml += "</div>";
                                        strHtml += "</div>";

                                    });
                                    $("#categoryBox").html(strHtml);
                                }
                            },
                            complete:function(){
                                $("#loadingBox").remove();
                            }
                        });
                    }
                }
            })
        })
        /*设置分类盒子高度*/
        $(function(){
            var H = $(window).height();
            var headH = $(".position-title").height();
            var footerH = $(".footer").height();
            $(".left-over-box").height(H-headH-footerH);
            $(".right-show-box-content").height(H-headH-footerH);
        })
    </script>
</body>
</html>