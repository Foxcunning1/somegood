<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>3好连锁商品列表页</title>
    <!-- <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css"/>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" /> -->
    <!-- <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/mobile/js/zepto.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/mobile/js/zepto.cookie.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/mobile/js/zepto.fx.js"></script> -->
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>

    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/TouchSlide/TouchSlide.1.1.js"></script>
</head>
<body>
<!--头部-->
<div class="header">
    <img src="/somegood/Public/statics/mobile/images/logo.png" alt="" class="logo fl">
    <div class="search-wrap fl">
        <form id="searchForm">
            <input type="text" class="search-input" placeholder="搜一搜你所需的物品">
            <i class="icons icon-search-small"></i>
        </form>
    </div>
    <div class="release-wrap fr">
        <i class="icons icon-release"></i>
        <span>发布</span>
    </div>
</div>
<!--商品筛选部分-->
<div class="goods-filter">
    <ul class="filter-ul">
        <li class="area"><span>区域</span></li>
        <li class="distance"><span>距离</span></li>
        <li class="price" data-status="0"><span>价格</span></li>
        <li class="filter"><span>筛选</span></li>
    </ul>
    <!--商品区域-->
    <div class="area-choose">
        <div class="choose-box">
            <ul>
                <li class="sel" data-status="1"><span>福田区</span><i class="icons icon-select"></i></li>
                <li><span>南山区</span><i class="icons icon-select"></i></li>
                <li><span>龙华新区</span><i class="icons icon-select"></i></li>
                <li><span>罗湖区</span><i class="icons icon-select"></i></li>
                <li><span>宝安区</span><i class="icons icon-select"></i></li>
            </ul>
        </div>
    </div>
    <!--商品区域-->
</div>
<!--商品筛选部分-->
<!--商品列表-->
<ul class="search-list">
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
        <a href="javascript:void(0);">
            <div class="g-img"><img src="/somegood/Public/statics/mobile/images/img2.png" alt="商品图"></div>
            <div class="g-info">
                <div class="product-name">
                    <span><?php echo ($vo["goods_title"]); ?></span>
                </div>
                <div class="product-price-m">
                    <em>¥<span class="big-price"><?php echo ($vo["goods_price"]); ?></span><span class="small-price">.00</span></em>
                </div>
                <div class="location-and-time">
                    <span class="location fl">深圳<em>|</em>福田</span>
                    <span class="time fr"><i class="icons icon-clock"></i>2分钟前来过</span>
                </div>
            </div>
            <!--已下架的商品有这个价格阴影层,没有下架就没有-->
            <!--<span class="opcity-white"></span>-->
        </a>
    </li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
<!--商品列表-->

<div class="over-lay"></div>
<div class="over-lay-small"></div>
<!--商品筛选-->
<div class="filter-box">

</div>
<!--商品筛选-->
<!--隐藏搜索部分 主页和产品列表页可以公用-->
<div class="hide-search-page">
    <i id="cancelBtn" class="icons icon-close"></i>
    <div class="hide-search-wrap">
        <div class="search-box">
            <form class="form fl" name="searchForm" id="searchForm" method="get" action="<?php echo U('Search/index');?>">
                <input type="text" name="keyword" id="search-keyword" placeholder="请输入关键词" />
                <input type="hidden" name="type" id="type" value="products" />
            </form>
            <a href="javascript:void(0);" id="searchBtn" class="search-big-box"><i class="icons icon-search-big"></i></a>
        </div>
    </div>
    <div class="search-history">
        <h4>最近搜索：</h4>
        <div class="history-word hot-word">
            <!--volist here-->
            <span>3好连锁</span>
            <span>3好fdsf连锁</span>
            <span>3好fdsffdf连锁</span>
            <span>3好fdffg连锁</span>
        </div>
    </div>
    <div class="search-interest">
        <h4>热搜：</h4>
        <div class="hot-word">
            <!--volist here-->
            <span>3好连锁</span>
            <span>3好fdsf连锁</span>
            <span>3好fdsffdf连锁</span>
            <span>3好fdffg连锁</span>
        </div>
    </div>
</div>
<!--隐藏搜索部分-->
<!--公用底部-->

<!--公用底部-->
</body>
<script>
    /*价格筛选 状态1为升 0为降*/
    $(".goods-filter li.price").on("touchstart",function(){
        if($(this).hasClass("click")){
            $(this).removeClass("click");
            $(this).attr("data-status",0);
        }else{
            $(this).addClass("click");
            $(this).attr("data-status",1);
        }
    })
    /*区域下拉*/
    $(".goods-filter li.area").on("touchstart",function(){
        if($(this).hasClass("done")){
            $(".over-lay-small").hide();
            $(this).removeClass("done");
            $(".area-choose").hide();
            $("body").css("overflow","auto");
        }else{
            $(".over-lay-small").show();
            $(this).addClass("done");
            $(".area-choose").show();
            $("body").css("overflow","hidden");
        }
    })
    /*区域收起*/
    $(".over-lay-small").on("touchstart",function(e){
        $(this).hide();
        $(".area-choose").hide();
        $(".goods-filter li.area").removeClass("done");
        $("body").css("overflow","auto");
        e.preventDefault();
    })
    /*筛选滑出*/
    $(".goods-filter li.filter").on("touchstart",function(){
        $(".over-lay").show();
        $(".filter-box").show().animate({"left":"15%"},300);
        $("body").css("overflow","hidden");
    })
    /*筛选滑出*/
    $(".goods-filter li").on("touchstart",function(){
       $(this).addClass("sel").siblings().removeClass("sel");
    })
    /*筛选隐藏*/
    $(".over-lay").on("touchstart",function(){
        $(".filter-box").animate({"left":"100%"},300,function(){
            $(".filter-box").hide();
            $(".over-lay").hide();
        });
        $("body").css("overflow","auto");
    })
    /*区域选择*/
    $(".area-choose li").on("touchstart",function(e){
        var value = $(this).find("span").html();
        if($(this).hasClass("sel")){
            $(this).attr("data-status",1);
        }else{
            $(this).addClass("sel").siblings().removeClass("sel");
            $(this).attr("data-status",1).siblings().attr("data-status",0);
        }
        $(".goods-filter li.area").find("span").html(value);
        $(".area-choose").hide();
        $(".over-lay-small").hide();
        $(".goods-filter li.area").find("span").css("color","#00b190");
        $(".goods-filter li.area").removeClass("done");
        $("body").css("overflow","auto");
        e.preventDefault();
    })
    /*以下js主页和产品列表页可以公用 建议放到layout.html*/
    /*搜索获取焦点时隐藏搜索出现*/
    $(".search-input").focus(function(){
        $(".hide-search-page").show();
    })
    /*关闭隐藏搜索*/
    $("i.icon-close").on("touchstart",function(){
        $(".hide-search-page").hide();
    })

</script>
</html>