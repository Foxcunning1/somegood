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
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/ershou/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/ershou/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
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
                            <?php if(is_array($recCategoryList)): $i = 0; $__LIST__ = $recCategoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Ershou/Goods/list');?>?catid=<?php echo ($item["id"]); ?>"><img src="/somegood/Public/uploads/<?php echo ($item["img_url"]); ?>" alt=""/><span><?php echo ($item["title"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="right-section">
                    <div class="section-top"><span>热门分类</span></div>
                    <div class="section-bottom">
                        <ul>
                            <?php if(is_array($hotCategoryList)): $i = 0; $__LIST__ = $hotCategoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Ershou/Goods/list');?>?catid=<?php echo ($item["id"]); ?>"><img src="/somegood/Public/uploads/<?php echo ($item["img_url"]); ?>" alt=""/><span><?php echo ($item["title"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
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
    <script>
        $(function(){
            //左侧菜单点击
            $(".left-classify-box").find("li").on("click",function(){
                $(this).addClass("sel").siblings().removeClass("sel");
                if(typeof($(this).attr("data-id"))!="undefined"){
                    var ajaxUrl = "<?php echo U('Ershou/Goods/category');?>";
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
                                                tempHtml += "<li><a href=\"<?php echo U('Ershou/Goods/list');?>?catid="+sub['id']+"\"><img src=\"/somegood/Public/uploads/"+sub['img_url']+"\" alt=\""+sub['title']+"\"/><span>"+sub['title']+"</span></a></li>"
                                            })
                                        }
                                        strHtml += tempHtml;
                                        strHtml += "</ul>";
                                        strHtml += "</div>";
                                        strHtml += "</div>"

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