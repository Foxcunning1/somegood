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
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/region.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/dist/dropload.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script><script src=""></script>
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
<div class="list-header">
    <a class="home-logo fl" href="/Mobile"><img src="/somegood/Public/statics/mobile/images/logo.png" alt="" class="logo fl"></a>
    <div class="search-wrap">
        <i id="cancelBtn" class="icons icon-close fl"></i>
        <form class="search-form" id="searchForm" name="searchForm" method="get" action="<?php echo U('Mobile/Search/list');?>">
            <input type="text" class="search-input" name="keywords" id="keywords"  placeholder="请输入关键词" value="<?php echo ($keywords); ?>">
            <i class="icons icon-search-small"></i>
            <a href="javascript:void(0);" id="searchBtn" class="search-big-box"><i class="icons icon-search-big"></i></a>
        </form>
    </div>
    <div class="category-menu fr">
        <a href="<?php echo U('Mobile/Users/publish');?>"></a>
    </div>
</div>
<!--导航-->
<div class="fixed-box">
    <div class="hide-nav">
        <ul>
            <li><a href="<?php echo U('Mobile/Index/index');?>"><i class="icons icon-home"></i><span>首页</span></a></li>
            <li><a href="<?php echo U('Mobile/Goods/category');?>"><i class="icons icon-classify"></i><span>分类</span></a></li>
            <li><a href="<?php echo U('Mobile/Cart/index');?>"><i class="icons icon-cart"></i><span class="icon-num" id="cartNum" style="display: none">0</span><span>购物车</span></a></li>
            <li><a href="<?php echo U('Mobile/Users/index');?>"><i class="icons icon-mine"></i><span>我的</span></a></li>
        </ul>
    </div>
    <!--导航-->
    <!--商品筛选部分-->
    <div class="goods-filter">
        <ul class="filter-ul">
            <li class="orderBtn sell <?php if(($order) == "0"): ?>selected<?php endif; ?>" data-status="0"><span>综合</span></li>
            <li class="orderBtn sell <?php if(($order) == "1"): ?>selected<?php endif; ?>" data-status="1"><span>销量</span></li>
            <li class="orderBtn price <?php if(($order == '2') OR ($order == '3')): ?>selected<?php endif; ?> <?php if(($order) == "3"): ?>click<?php endif; ?>" data-status="<?php if(($order) == "2"): echo ($order); endif; if(($order) == "3"): ?>3<?php endif; ?>"><span>价格</span></li>
            <li class="filter"><span>筛选</span></li>
        </ul>
    </div>
    <!--商品筛选部分-->
</div>
<!--商品列表-->
<div class="page-box list-page-box">
    <ul class="search-list">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                <a href="<?php echo U('Mobile/Goods/detail',array('id'=>$vo['id']));?>">
                    <div class="g-img"><img data-original="/somegood/Public/uploads/<?php echo ($vo["goods_thumb"]); ?>_c200x200" src="/somegood/Public/statics/mobile/images/default_img.png" alt="<?php echo ($vo["goods_title"]); ?>" /></div>
                    <div class="g-info">
                        <div class="product-name">
                            <span><?php echo ($vo["goods_title"]); ?></span>
                        </div>
                        <div class="product-price-m">
                            <em>¥<span class="small-price"><?php echo ($vo["price"]); ?></span></em>
                        </div>
                        <div class="location-and-time">
                            <span class="location fl">在售商家：<?php echo ($vo["store_count"]); ?></span>
                            <span class="time fr"><i class="icons icon-clock"></i><time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$vo["audit_time"])); ?>"></time></span>
                        </div>
                    </div>
                </a>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
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
                <div class="category-lists" id="categoryBox" ></div>
            </div>
        </div>
    </div>
    <div class="tools-btn-box">
        <a class="cancleBtn" href="javascirpt:;">取消</a>
        <a class="submit-btn" id="submitBtn" href="javascript:;">确定</a>
    </div>
</div>
<!--商品筛选-->
<!--隐藏搜索部分 主页和产品列表页可以公用-->
<div class="search-mask">
    <div class="prompt-box">
        <div class="current-search-box" id="searchOptionBox">
        </div>
        <?php if(!empty($searchedKeywordsList)): ?><div class="search-history">
                <h4>最近搜索：</h4>
                <div class="history-word hot-word">
                    <?php if(is_array($searchedKeywordsList)): $i = 0; $__LIST__ = $searchedKeywordsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><span><a href="<?php echo U('Mobile/Search/list');?>?keywords=<?php echo ($item["keyword"]); ?>"><?php echo ($item["keyword"]); ?></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div><?php endif; ?>
        <div class="search-interest">
            <h4>热搜：</h4>
            <div class="hot-word">
                <?php if(is_array($hotKeywords)): $i = 0; $__LIST__ = $hotKeywords;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span><a href="<?php echo U('Mobile/Search/list');?>?keywords=<?php echo ($item["keyword"]); ?>"><?php echo ($vo["keyword"]); ?></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function(){
        /*懒加载*/
        $(".fy-content li img").lazyload({effect : "fadeIn"});
        /*关闭搜索*/
        $("i.icon-close").on("click",function(){
            $(".icon-search-small").show();
            $(".list-header .icon-close").hide();
            $(".search-mask").hide();
            $(".search-big-box").hide();
            $(".list-header").removeClass("on-focus");
            $("body").css("overflow","auto");
        })
        /*搜索提交*/
        $("#searchBtn").on("touchstart",function(){
            var keywords = $("#keywords").val();
            if(keywords!=""){
                $("#searchForm").submit();
            }
        })
        /*点击搜索框获取焦点*/
        $(".search-input").on("click",function(){
            $(window).scrollTop(0);
            $(".icon-search-small").hide();
            $(".list-header .icon-close").show();
            $(".search-mask").show();
            $(".search-big-box").show();
            $(".list-header").addClass("on-focus");
            $("body").css("overflow","hidden");
        })
        /*头部搜索框动画*/
        $(window).scroll(function(){
            var sTop = $(window).scrollTop();
            if(sTop >= 100){
                $(".a-wrap").hide();
                $(".header-bottom").addClass("scroll-style");
            }else{
                $(".a-wrap").show();
                $(".header-bottom").removeClass("scroll-style");
            }
        })
    });
</script>
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
                    searchOptionStr += "<span><a href=\"<?php echo U('Mobile/Search/list');?>?keywords="+keywords
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
<script type="text/javascript">
    //initRegionDataList("#region-list",<?php echo ($area_id); ?>);
    //筛选菜单点击及默认效果
    var ajaxCategoryUrl = "<?php echo U('Mobile/Goods/category');?>";//Ajax获取分类地址
    $(function(){
        //给头部设置默认颜色
        $("#header").css("background","#00b190");
        //图片延迟加载
        $(".search-list li img").lazyload({effect : "fadeIn"});
        //点击排序
        $(".orderBtn").on("touchstart",function(){
            var orderType = $(this).attr("data-status");
            var jumpUrl = "<?php echo U('Mobile/Search/list');?>?catid=<?php echo ($catid); ?>&order="+orderType+"&keywords=<?php echo ($keywords); ?>";
            if($(this).hasClass("price")){
                if(orderType=="3"){
                    jumpUrl = "<?php echo U('Mobile/Search/list');?>?catid=<?php echo ($catid); ?>&order=2&keywords=<?php echo ($keywords); ?>";
                }else{
                    jumpUrl = "<?php echo U('Mobile/Search/list');?>?catid=<?php echo ($catid); ?>&order=3&keywords=<?php echo ($keywords); ?>";
                }
            }
            window.location.href = jumpUrl;
        })
        //筛选提交
        $("#submitBtn").on("touchstart",function(){
            var catid = $("#categoryId").val();
            if(catid!=""){
                jumpUrl = "<?php echo U('Mobile/Search/list');?>?catid="+catid+"&order=<?php echo ($order); ?>"+"&keywords=<?php echo ($keywords); ?>";
                window.location.href = jumpUrl;
            }
        })
        /*筛选取消*/
        $(".cancleBtn").on("touchstart",function(){
            $(".kinds-box").animate({"left":"100%"},300,function(){
                $(".kinds-box").hide();
                $(".over-lay").hide();
                $("body").css({"overflow":"auto"});
            });
        })
        //发布的日期与现在间隔的时间
        $(".timeago").timeago();
        //经纬度计算
        $(".distance").each(function(){
            var lng = parseFloat($(this).attr("data-lng"));
            var lat = parseFloat($(this).attr("data-lat"));
            var distance = getFlatternDistance(usersLat,usersLng,lat,lng);
            $(this).text(distance);
        })
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
    /*筛选滑出*/
    $(".goods-filter li.filter").on("click",function(){
        $(".over-lay").show();
        $(".kinds-box").show().animate({"left":"15%"},300,function(){
            $(".tools-btn-box").addClass("tools-btn-box-show");
            $('html').addClass('noscroll');
            $("body").css("overflow-y","hidden");
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
    /*筛选隐藏*/
    $(".kind-filter .icon-close-overlay").on("click",function(){
        $(".kinds-box").animate({"left":"100%"},300,function(){
            $(".kinds-box").hide();
            $(".over-lay").hide();
            $('html').removeClass('noscroll');
            $("body").css({"overflow-y":"auto"});
        });
    })
    /*顶部隐藏导航显示*/
    $(".category-menu").on("click",function(){
        if($(this).hasClass("open")){
            $(this).removeClass("open");
            $(".hide-nav").hide();
            $(".list-page-box").removeClass("up");
        }else{
            $(this).addClass("open");
            $(".hide-nav").show();
            $(".list-page-box").addClass("up");
        }
    })
    //获取购物车商品的数量
    $(function() {
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
    })

    //滚动页面ajax自动获取下一页内容
    var page=2; //有效求购的页码
    var totalPage = <?php echo ($pageCount); ?>; //有效求购页的总页码，会从后台获
    var ajaxUrl="<?php echo U('Mobile/Search/list');?>?catid=<?php echo ($catid); ?>&order=<?php echo ($order); ?>&isajax=1&keywords=<?php echo ($keywords); ?>";//ajax请求地址
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
                                str += "<a href=\"<?php echo U('Mobile/Goods/detail');?>?id="+vo['id']+"\">";
                                str += "<div class=\"g-img\"><img data-original=\"/somegood/Public/uploads/"+ vo['goods_thumb']+"_c200x200\" src=\"/somegood/Public/statics/mobile/images/default_img.png\" alt=\""+vo['goods_title']+"\" /></div>";
                                str += "<div class=\"g-info\">";
                                str += "<div class=\"product-name\">";
                                str += "<span>"+vo['goods_title']+"</span>";
                                str += "</div>";
                                str += "<div class=\"product-price-m\">";
                                str += "<em>¥<span class=\"small-price\">"+vo['price']+"</span></em>";
                                str += "</div>";
                                str += "<div class=\"location-and-time\">";
                                str += "<span class=\"location fl\">在售商家："+vo['store_count']+"</span>";
                                str += "<span class=\"time fr\"><i class=\"icons icon-clock\"></i><time class=\"timeago\" datetime=\""+date('Y-m-d',""+vo['audit_time']+"")+"\"></time></span>";
                                str += "</div>";
                                str += "</div>";
                                str += "</a>";
                                str += "</li>";
                            })
                            // 为了测试，延迟1秒加载
                            $('.search-list').append(str);
                            //图片延迟加载
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


</body>
</html>