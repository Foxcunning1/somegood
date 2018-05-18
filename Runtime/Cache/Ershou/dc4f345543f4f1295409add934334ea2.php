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
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/dist/dropload.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/TouchSlide/TouchSlide.1.1.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/statics/ershou/js/region.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script><script type="text/javascript" src="/somegood/Public/statics/ershou/js/common.js"></script><script type="text/javascript" src="/somegood/Public/dist/dropload.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/ershou/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <script type="text/javascript">
        var usersLng = <?php echo (session('lng')); ?>;//用户所在的经纬度
        var usersLat = <?php echo (session('lat')); ?>;
        if(isWeixin()) {
            usersLng = parseFloat(localStorage.getItem("longitude"));
            usersLat = parseFloat(localStorage.getItem("latitude"));
        }
        var defaultCatId = "<?php echo ($catid); ?>";
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

<!--商品筛选部分-->
<div class="goods-filter">
    <ul class="filter-ul">
        <li class="area" id="area"><span>区域</span></li>
        <li class="orderBtn distaceBtn <?php if(($order) == "2"): ?>selected<?php endif; ?>" data-status="2"><span>距离</span></li>
        <li class="orderBtn price <?php if(($order) != "2"): ?>selected<?php endif; ?> <?php if(($order) == "1"): ?>click<?php endif; ?>" data-status="<?php if($order != '2'): echo ($order); else: ?>0<?php endif; ?>"><span>价格</span></li>
        <li class="filter"><span>筛选</span></li>
    </ul>
    <!--商品区域-->
    <div class="area-choose" id="areaChoose">
        <div class="choose-box">
            <ul>
                <li <?php if(($area_id) == "0"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Ershou/Search/list');?>?catid=<?php echo ($catid); ?>&area_id=0&order=<?php echo ($order); ?>&keywords=<?php echo ($keywords); ?>"><span>全部区域</span><i class="icons icon-select"></i></a></li>
                <?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area): $mod = ($i % 2 );++$i;?><li <?php if(($area_id) == $area['id']): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Ershou/Search/list');?>?catid=<?php echo ($catid); ?>&area_id=<?php echo ($area["id"]); ?>&order=<?php echo ($order); ?>&keywords=<?php echo ($keywords); ?>"><span><?php echo ($area["name"]); ?></span><i class="icons icon-select"></i></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
    <!--商品区域-->
</div>
<!--商品筛选部分-->
<!--商品列表-->
<div class="page-box" style="padding-top: 1.64rem;">
    <ul class="search-list">
        <!--<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
        <!--<li>-->
            <!--<a href="<?php echo U('Ershou/Goods/detail',array('id'=>$vo['id']));?>">-->
                <!--<div class="g-img"><img data-original="/somegood/Public/uploads/<?php echo ($vo["goods_thumb"]); ?>_c200x200" src="/somegood/Public/statics/mobile/images/default_img.png" alt="<?php echo ($vo["goods_title"]); ?>"></div>-->
                <!--<div class="g-info">-->
                    <!--<div class="product-name">-->
                        <!--<span><?php echo ($vo["goods_title"]); ?></span>-->
                    <!--</div>-->
                    <!--<div class="product-price-m">-->
                        <!--<em>¥<span class="small-price"><?php echo ($vo["want_price"]); ?></span></em>-->
                    <!--</div>-->
                    <!--<div class="location-and-time">-->
                        <!--<span class="location fl"><?php echo ($vo["area_name"]); ?></span>-->
                        <!--<span class="time fr"><i class="icons icon-clock"></i><time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$vo["audit_time"])); ?>"></time></span>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</a>-->
        <!--</li>-->
        <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
        <!--volist here-->
        <div class="con-items">
            <div class="items-user">
                <div class="user-img fl"><img src="<?php if(empty($item["avatar"])): ?>/somegood/Public/statics/mobile/images/default_img.png<?php else: ?>/somegood/Public/uploads/<?php echo ($item["avatar"]); ?>_c200x200<?php endif; ?>" alt="<?php echo ($item["nick_name"]); ?>"/></div>
                <div class="name-and-time fl">
                    <a href="<?php echo U('Ershou/Store/myShop',array('shopid'=>$item['store_id']));?>" class="name"><?php echo ($item["store_name"]); ?>herherherh</a>
                    <span class="time"><i class="icons icon-clock"></i><time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$item["updates_time"])); ?>"></time></span>
                </div>
                <div class="price fr">
                    ￥<em><?php echo ($item["want_price"]); ?>300</em><span class="per-new"><?php if(($item["goods_condition"]) == "10"): ?>全新<?php else: ?><em id="perNewNum"><?php echo ($item["goods_condition"]); ?></em>成新<?php endif; ?></span>
                </div>
            </div>
            <div class="items-img-box">
                <ul>
                    <?php $photoList = array(); $photoRemark = array(); if($item['original_img']){ $tempArr = explode("||",$item['original_img']); $photoList = unserialize($tempArr[0]); $photoRemark = unserialize($tempArr[1]); } ?>
                    <?php if(is_array($photoList)): $key = 0; $__LIST__ = $photoList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$photo): $mod = ($key % 2 );++$key; if($key < 4): ?><li><a href="<?php echo U('Ershou/Goods/detail',array('id'=>$item['id']));?>"><img data-original="/somegood/Public/uploads/<?php echo ($photo); ?>_c200x200" src="/somegood/Public/statics/mobile/images/default_img.png" /></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    <li><a href="<?php echo U('Ershou/Goods/detail',array('id'=>$item['id']));?>"><img data-original="/somegood/Public/uploads/<?php echo ($photo); ?>_c200x200" src="/somegood/Public/images/img1.png" /></a></li>
                    <li><a href="<?php echo U('Ershou/Goods/detail',array('id'=>$item['id']));?>"><img data-original="/somegood/Public/uploads/<?php echo ($photo); ?>_c200x200" src="/somegood/Public/images/img2.png" /></a></li>
                    <li><a href="<?php echo U('Ershou/Goods/detail',array('id'=>$item['id']));?>"><img data-original="/somegood/Public/uploads/<?php echo ($photo); ?>_c200x200" src="/somegood/Public/images/img3.png" /></a></li>
                </ul>
            </div>
            <p class="items-title"><a href="<?php echo U('Ershou/Goods/detail',array('id'=>$item['id']));?>"><?php echo ($item["goods_title"]); ?>的身份给你问of较为频繁魏鹏飞</a></p>
            <div class="price-and-other">
                <span class="apart fl">距你<em class="distance" data-lng="<?php echo ($item["lng"]); ?>" data-lat="<?php echo ($item["lat"]); ?>"></em>km</span>
            </div>
        </div>
        <!--volist here-->
    </ul>
</div>
<!--商品列表-->

<div class="over-lay"></div>
<div class="over-lay-small"></div>
<!--商品筛选-->
<div class="kinds-box">
    <div class="kind-filter">
        <i class="icons icon-close-overlay fl"></i>
        <span class="fl">筛选</span>
    </div>
    <div class="filter-box">
        <div class="category-title">
            <span class="fl">商品分类</span>
            <span class="fr" id="choosed" style="margin-right:20px;">所有分类</span>
            <i class="icon-pointer"></i>
            <input type="hidden" name="catid" id="categoryId" value="<?php echo ($catid); ?>" />
        </div>
        <div class="left-classify-box" style="position: absolute; top: 0.71rem; bottom: 0; overflow: auto;">
            <div class="left-over-box" style="padding: 0; height: 100%; ">
                <ul>
                    <?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($vo["id"] == $bigCatId): ?>class="sel"<?php endif; ?> data-id="<?php echo ($vo["id"]); ?>"><a href="javascript:;"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "$empty" ;endif; ?>
                </ul>
            </div>
        </div>
        <!--右边分类展示-->
        <div class="right-show-box" style="position: absolute; top: 0.71rem; bottom:0; overflow: auto;">
            <div class="right-show-box-content" style="padding:0; height: 100%;">
                <div class="category-lists" id="categoryBox"></div>
            </div>
        </div>
    </div>
    <div class="tools-btn-box">
        <a class="cancleBtn" href="javascirpt:;">取消</a>
        <a class="submit-btn" id="submitBtn" href="javascript:;">确定</a>
    </div>
</div>
<!--商品筛选-->
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
<script type="text/javascript">
    //initRegionDataList("#region-list",<?php echo ($area_id); ?>);
    //筛选菜单点击及默认效果
    var ajaxCategoryUrl = "<?php echo U('Ershou/Goods/category');?>";//Ajax获取分类地址
    $(function(){
        $(".search-list li img").lazyload({effect : "fadeIn"});
        $("#header").css("background-color", "rgb(0, 177, 144)");
        //点击排序
        $(".orderBtn").on("touchstart",function(){
            var orderType = $(this).attr("data-status");
            var jumpUrl = "<?php echo U('Ershou/Search/list');?>?catid=<?php echo ($catid); ?>&area_id=<?php echo ($area_id); ?>&order=2&keywords=<?php echo ($keywords); ?>";
            if($(this).hasClass("price")){
                if(orderType=="0"){
                    jumpUrl = "<?php echo U('Ershou/Search/list');?>?catid=<?php echo ($catid); ?>&area_id=<?php echo ($area_id); ?>&order=1&keywords=<?php echo ($keywords); ?>";
                }else{
                    jumpUrl = "<?php echo U('Ershou/Search/list');?>?catid=<?php echo ($catid); ?>&area_id=<?php echo ($area_id); ?>&order=0&keywords=<?php echo ($keywords); ?>";
                }
            }
            window.location.href = jumpUrl;
        })
        //筛选提交
        var defaultCatId = "<?php echo ($catid); ?>";
        $("#submitBtn").on("touchstart",function(){
            var catid = $("#categoryId").val();
            if(catid!=""){
                jumpUrl = "<?php echo U('Ershou/Search/list');?>?catid="+catid+"&area_id=<?php echo ($area_id); ?>&order=<?php echo ($order); ?>&keywords=<?php echo ($keywords); ?>";
                window.location.href = jumpUrl;
            }
        })

        //发布的日期与现在间隔的时间
        $(".timeago").timeago();
        //经纬度计算
        calcDistance(".distance");//绑定计算距离
        //区域被选中信息
        var areaName = "区域";
        var selectText = $("#areaChoose").find("li.sel").eq(0).find("span").text();
        if(selectText){
            areaName = selectText;
        }
        $("#area").text(areaName);
        //筛选菜单选项卡点击效果
        //默认第一个选项卡被选中
        var defaultId = 0;
        if($(".left-classify-box").find("li.sel").length>0){
            defaultId = $(".left-classify-box").find("li.sel").eq(0).attr("data-id");
        }else{
            $(".left-classify-box").find("li").eq(0).addClass("sel");
            defaultId = $(".left-classify-box").find("li").eq(0).attr("data-id");
        }
        if(typeof(defaultId!="undefined")) {
            menuTabChangeAjax(defaultCatId,defaultId, "#categoryBox", ajaxCategoryUrl);
        }
        $(".left-classify-box").find("li").on("click",function(){
            $(this).addClass("sel").siblings().removeClass("sel");
            if(typeof($(this).attr("data-id"))!="undefined"){
                var id = $(this).attr("data-id");
                menuTabChangeAjax(defaultCatId,id,"#categoryBox",ajaxCategoryUrl);
            }
        })
    })
    /*区域下拉*/
    var h = $(".area-choose").height();
    var wh = $(window).height();
    $(".over-lay-small").height(wh);
    /*区域选择*/
    $(".goods-filter li.area").on("click",function(){
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
    });
    /*筛选滑出*/
    $(".goods-filter li.filter").on("click",function(){
        $(".over-lay").show();
        $(".kinds-box").show().animate({"left":"15%"},300,function(){
            $(".tools-btn-box").addClass("tools-btn-box-show");
            $('html').addClass('noscroll');
            $("body").css({"overflow-y":"hidden"});
        });

    })
    /*筛选隐藏*/
    $(".over-lay").on("click",function(){
        $(".tools-btn-box").removeClass("tools-btn-box-show");
        $(".kinds-box").animate({"left":"100%"},300,function(){
            $(".kinds-box").hide();
            $(".over-lay").hide();
            $('html').removeClass('noscroll');
            $("body").css({"overflow-y":"auto"});
        });

    })
    /*筛选取消*/
    $(".cancleBtn").on("touchstart",function(){
        $(".kinds-box").animate({"left":"100%"},300,function(){
            $(".kinds-box").hide();
            $(".over-lay").hide();
            $("body").css({"overflow-y":"auto"});
        });
    })
    /*筛选隐藏*/
    $(".kind-filter .icon-close-overlay").on("click",function(){
        $(".kinds-box").animate({"left":"100%"},300,function(){
            $(".kinds-box").hide();
            $(".over-lay").hide();
        });
        $("body").css({"overflow-y":"auto"});
    })
    $(".filter-option-dl").find(".filter-option").click(function(){
        $(this).addClass("select").siblings().removeClass("select");
        $(this).parent().parent().siblings().find(".filter-option").removeClass("select");
    })
    $(".left-over-box").find("li").click(function(){
        $(this).addClass("sel").siblings().removeClass("sel");
    })
    //滚动页面ajax自动获取下一页内容
    var page=2; //有效求购的页码
    var totalPage = <?php echo ($pageCount); ?>; //有效求购页的总页码，会从后台获
    var ajaxUrl="<?php echo U('Ershou/Search/list');?>?catid=<?php echo ($catid); ?>&area_id=<?php echo ($area_id); ?>&order=<?php echo ($order); ?>&isajax=1";//ajax请求地址
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
                                str += "<a href=\"<?php echo U('Ershou/Goods/detail');?>?id="+vo['id']+"\">";
                                str += "<div class=\"g-img\"><img data-original=\"/somegood/Public/uploads/"+ vo['goods_thumb']+"_c200x200\" src=\"/somegood/Public/statics/mobile/images/default_img.png\" alt=\""+vo['goods_title']+"\" /></div>";
                                str += "<div class=\"g-info\">";
                                str += "<div class=\"product-name\">";
                                str += "<span>"+vo['goods_title']+"</span>";
                                str += "</div>";
                                str += "<div class=\"product-price-m\">";
                                str += "<em>¥<span class=\"small-price\">"+vo['want_price']+"</span></em>";
                                str += "</div>";
                                str += "<div class=\"location-and-time\">";
                                str += "<span class=\"location fl\">"+vo['area_name']+"</span>";
                                str += "<span class=\"time fr\"><i class=\"icons icon-clock\"></i><time class=\"timeago\" datetime=\""+date('Y-m-d',""+vo['audit_time']+"")+"\"></time></span>";
                                str += "</div>";
                                str += "</div>";
                                str += "</a>";
                                str += "</li>";
                            })
                            // 为了测试，延迟1秒加载
                            $('.search-list').append(str);
                            calcDistance(".distance");//绑定计算距离
                            $(".search-list li img").lazyload({effect : "fadeIn"});
                            // 每次数据加载完，必须重置
                            me.resetload();
                            totalPage = data.data.pageCount;
                        },
                        error: function(xhr, type){
                            alert('Ajax error!');
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