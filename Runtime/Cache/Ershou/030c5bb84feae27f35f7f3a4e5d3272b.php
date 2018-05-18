<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title><?php echo C('MOBILE_SEO_TITLE');?></title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/TouchSlide/TouchSlide.1.1.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script><script type="text/javascript" src="/somegood/Public/statics/ershou/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/ershou/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
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
    <!--头部-->
        <script type="text/javascript" src="/somegood/Public/scripts/zepto/event.js"></script>
	<header class="header" id="header">
        <a class="home-logo fl" href="<?php echo U('Mobile/Index/index');?>"><img src="/somegood/Public/statics/mobile/images/logo.png" alt="" class="logo fl" /></a>
        <div class="search-wrap fl">
            <div class="search-input" id="skeywords" placeholder="搜一搜你所需的物品"><?php if(empty($keywords)): ?>搜一搜你所需的物品<?php else: echo ($keywords); endif; ?></div>
            <i class="icons icon-search-small"></i>
        </div>
        <div class="release-wrap fr">
            <a href="<?php echo U('Mobile/SecondHand/publish');?>">
                <i class="icons icon-release"></i>
                <span>发布</span>
            </a>
        </div>
    </header>
    <!--隐藏搜索部分 主页和产品列表页可以公用-->
    <div class="hide-search-page" id="hideSearchPage">
        <div class="search-inter-box">
            <i id="cancelBtn" class="icons icon-close fl"></i>
            <div class="hide-search-wrap fr">
                <div class="search-box">
                    <form class="form fl" name="searchForm" id="searchForm" method="get" action="<?php echo U('Ershou/Search/list');?>">
                        <input type="text" name="keywords" id="keywords"  placeholder="请输入关键词" value="<?php echo ($keywords); ?>" />
                    </form>
                    <a href="javascript:void(0);" id="searchBtn" class="search-big-box"><i class="icons icon-search-big"></i></a>
                </div>
            </div>
        </div>
        <div class="current-search-box" id="searchOptionBox">
        </div>
        <script type="text/javascript">
            var inputChange = {
                input:function(obj){
                    var obj = document.getElementById(obj);
                    var inputLock = false;
                    function doing(obj){
                        $("#searchOptionBox").html("");
                        var keywords = $("#keywords").val();
                        if(keywords!=""){
                            //组合搜索下拉
                            var searchOptionStr = "";
                            searchOptionStr += "<div class=\"search-item-option\">";
                            var typeStr = "";
                            var defaultText = "的商品";
                            searchOptionStr += "<span><a href=\"<?php echo U('Ershou/Search/list');?>?keywords="+keywords
                                    + typeStr + "\">搜索<em>“"+keywords+"”</em>" + defaultText + "</a></span>";
                            searchOptionStr += "</div>";
                            $("#searchOptionBox").append(searchOptionStr);
                        }
                    }
                    obj.addEventListener('compositionstart', function() {
                        inputLock = true;
                    });
                    obj.addEventListener('compositionend', function(event) {
                        inputLock = false;
                        doing(event.target);
                    })
                    obj.addEventListener('input', function(event) {
                        if (!inputLock) {
                            doing(event.target);
                            event.returnValue = false;
                        }
                    });
                }
            }
            $(function(){
                inputChange.input("keywords");
            })
        </script>
        <?php if(!empty($searchedKeywordsList)): ?><div class="search-history">
            <h4>最近搜索：</h4>
            <div class="history-word hot-word">
                <?php if(is_array($searchedKeywordsList)): $i = 0; $__LIST__ = $searchedKeywordsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><span><a href="<?php echo U('Ershou/Search/list');?>?keywords=<?php echo ($item["keyword"]); ?>"><?php echo ($item["keyword"]); ?></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div><?php endif; ?>
        <div class="search-interest">
            <h4>热搜：</h4>
            <div class="hot-word">
                <?php if(is_array($hotKeywords)): $i = 0; $__LIST__ = $hotKeywords;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span><a href="<?php echo U('Ershou/Search/list');?>?keywords=<?php echo ($item["keyword"]); ?>"><?php echo ($vo["keyword"]); ?></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    /*以下js头部带搜索框的都可以通用 当输入框获取焦点时 转到搜索页面*/
    /*搜索获取焦点时隐藏搜索出现*/
    var button = document.getElementById("skeywords");
    var inputElem = document.getElementById("keywords");
    var hideSearchPage = document.getElementById("hideSearchPage");
    button.addEventListener("click", function(e){
        hideSearchPage.style.visibility = "visible";
        inputElem.focus();
    });
    /*关闭隐藏搜索*/
    $("i.icon-close").on("touchstart",function(){
        $(".hide-search-page").css("visibility", "hidden");
    })
    $("#searchBtn").on("touchstart",function(){
        var keywords = $("#keywords").val();
        if(keywords!=""){
           $("#searchForm").submit();
        }
    })
    </script>

    <!--焦点图-->
    <div class="slider" id="slider">
        <div class="hd">
            <ul>
                <li class="on"></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div class="bd">
            <ul>
                <li><img src="/somegood/Public/statics/mobile/images/banner1.jpg" alt="" /></li>
                <li><img src="/somegood/Public/statics/mobile/images/banner2.jpg" alt="" /></li>
                <li><img src="/somegood/Public/statics/mobile/images/banner3.jpg" alt="" /></li>
            </ul>
        </div>
        <!--焦点图-->
    </div>
    <!--头部-->
    <script>TouchSlide({slideCell:"#slider",mainCell:".bd ul",autoPlay:true});</script>
    <!--导航-->
    <div class="nav">
        <ul>
          <?php if(is_array($hotCategoryList)): $i = 0; $__LIST__ = $hotCategoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Ershou/Goods/list',array('catid'=>$vo['id']));?>"><img src="/somegood/Public/uploads/<?php echo ($vo[img_url]); ?>" alt="<?php echo ($vo[title]); ?>" /><span><?php echo ($vo[title]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <!--导航-->
    <!--同城求购-->
    <div class="recommend">
        <div class="rec-title"><h2 class="fl">同城寻宝</h2><a href="<?php echo U('Ershou/Want/list');?>" class="to-shop fr">更多</a></div>
        <div class="my-apply-list">
            <ul class="apply-list">
                <?php if(is_array($newWantList)): $i = 0; $__LIST__ = $newWantList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                        <div class="title"><a href="<?php echo U('Ershou/Want/detail');?>?id=<?php echo ($vo["w_id"]); ?>"><?php echo ($vo["w_title"]); ?></a></div>
                        <div class="location">
                            <i class="icons icon-location fl"></i><span class="fl"><?php echo ($vo["area"]); ?></span>
                        </div>
                        <div class="price-and-time"><span class="price fl"><?php if($vo["w_min"] == 0 && $vo["w_max"] == 0): ?>面议<?php elseif($vo["w_min"] == 0 && $vo["w_max"] != 0): echo ($item["w_max"]); ?>元以内<?php else: echo ($vo["w_min"]); ?>到<?php echo ($vo["w_max"]); ?>元<?php endif; ?></span><span class="time fr"><?php echo (date('Y-m-d',$vo["w_updatetime"])); ?></span></div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
    <!--同城求购结束-->
    <!--附近好物-->
    <div class="good-nearby page-box">
        <div class="near-title"><h2 class="fl">好货推荐</h2></div>
        <!--点击选项卡控制near-content显示和隐藏-->
        <div class="near-content-box">
            <div class="near-content">
                <!--volist here-->
                <?php if(is_array($recGoodsList)): $i = 0; $__LIST__ = $recGoodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><div class="con-items">
                    <div class="items-user">
                        <div class="user-img fl"><img src="<?php if(empty($item["avatar"])): ?>/somegood/Public/statics/mobile/images/default_img.png<?php else: ?>/somegood/Public/uploads/<?php echo ($item["avatar"]); ?>_c200x200<?php endif; ?>" alt="<?php echo ($item["nick_name"]); ?>"/></div>
                        <div class="name-and-time fl">
                            <a href="<?php echo U('Ershou/Store/detail',array('shopid'=>$item['store_id']));?>" class="name"><?php echo ($item["nick_name"]); ?></a>
                            <span class="time"><i class="icons icon-clock"></i><time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$item["updates_time"])); ?>"></time></span>
                        </div>
                        <span class="apart fr">距你<em class="distance" data-lng="<?php echo ($item["lng"]); ?>" data-lat="<?php echo ($item["lat"]); ?>"></em>km</span>
                    </div>
                    <div class="items-img-box">
                        <ul>
                            <?php $photoList = array(); $photoRemark = array(); if($item['original_img']){ $tempArr = explode("||",$item['original_img']); $photoList = unserialize($tempArr[0]); $photoRemark = unserialize($tempArr[1]); } ?>
                            <?php if(is_array($photoList)): $key = 0; $__LIST__ = $photoList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$photo): $mod = ($key % 2 );++$key; if($key < 4): ?><li><a href="<?php echo U('Ershou/Goods/detail',array('id'=>$item['id']));?>"><img data-original="/somegood/Public/uploads/<?php echo ($photo); ?>_c200x200" src="/somegood/Public/statics/mobile/images/default_img.png" /></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                    <p class="items-title"><a href="<?php echo U('Ershou/Goods/detail',array('id'=>$item['id']));?>"><?php echo ($item["goods_title"]); ?></a></p>
                    <div class="price-and-other">
                        <div class="price fl">
                            ￥<em><?php echo ($item["want_price"]); ?></em><span class="per-new"><?php if(($item["goods_condition"]) == "10"): ?>全新<?php else: ?><em id="perNewNum"><?php echo ($item["goods_condition"]); ?></em>成新<?php endif; ?></span>
                        </div>
                        <a href="javascript:void(0);" class="favBtn fr"><i class="icons icon-like-index"></i></a>
                        <a href="javascript:void(0);" class="fr" style="display:none;"><i class="icons icon-chat"></i></a>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <!--附近好物-->
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
        $(".icon-plus").on("click",function(event){
            if($(".rotate-box").hasClass("rotate")){
                $(this).removeClass("rotate");
                $(".rotate-box").removeClass("rotate");
            }else{
                $(this).addClass("rotate");
                $(".rotate-box").addClass("rotate");
            }
            event.stopPropagation();
        });
        $(document).bind('click',function(e){
            var e = e || window.event; //浏览器兼容性
            var elem = e.target || e.srcElement;
            while (elem) { //循环判断至跟节点，防止点击的是div子元素
                if (elem.id && elem.id=='test') {
                    return;
                }
                elem = elem.parentNode;
            }
            $(".rotate-box").addClass("rotate");
            $(".rotate-box").removeClass("rotate");
        })
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
            //延迟加载图片
            $(".rec-content li img").lazyload({effect : "fadeIn"});
            $(".items-img-box li img").lazyload({effect : "fadeIn"});
            //滚动后，顶部导航浮动
            $(window).scroll(function() {
                var sTop = $(window).scrollTop();
                var bannerH = $("#slider").offset().height;
                if (sTop >= bannerH/2) {
                    $("#header").css({"background": "#00b190"});
                } else {
                    $("#header").css({"background": "rgba(0,0,0,0)"});
                }
            })

            /*公用底部点击样式变换 放再公用的layout里面*/
            $(".footer ul").find("li").on("touchstart",function(){
                $(this).addClass("sel").siblings().removeClass("sel");
            })
            $(".timeago").timeago();
            $(".distance").each(function(){
                var lng = parseFloat($(this).attr("data-lng"));
                var lat = parseFloat($(this).attr("data-lat"));
                var distance = getFlatternDistance(usersLat,usersLng,lat,lng);
                $(this).text(distance);
            })
        })
    </script>
</body>
</html>