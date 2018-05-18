<?php if (!defined('THINK_PATH')) exit(); if(($isAjax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
            <a href="<?php echo U('Mobile/Index/storeDetail',array('shopid'=>$item['sid']));?>">
                <img data-original="<?php if(empty($item["logo"])): ?>/somegood/Public/statics/mobile/images/default_img.png<?php else: ?>/somegood/Public/uploads/<?php echo ($item["logo"]); ?>_c200x200<?php endif; ?>" src="/somegood/Public/statics/mobile/images/default_img.png" alt="<?php echo ($item["store_name"]); ?>" />
                <div class="info-box">
                    <div class="line-item"><span class="name"><?php echo ($item["store_name"]); ?></span></div>
                    <div class="line-item span-item"><span><?php echo (msubstr($item["products"],0,30)); ?></span></div>
                    <div class="line-item span-item"><em data-lng="<?php echo ($item["lng"]); ?>" data-lat="<?php echo ($item["lat"]); ?>" class="distance address"></em>KM</div>
                </div>
            </a>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
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
    <title>3好连锁主页</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/dist/dropload.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/scripts/TouchSlide/TouchSlide.1.1.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/region.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/dist/dropload.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
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
<body style="height: 100%;">
<!--头部-->
<div class="header">
    <div class="header-top">
        <div class="category-menu"><a href="<?php echo U('Mobile/Goods/category');?>">分类</a></div>
        <div class="a-wrap">
            <a href="javascript:;" class="home-logo"><img src="/somegood/Public/statics/mobile/images/logo.png" alt="" class="logo"></a>
            <a href="javascript:;" class="area-tab"><span id="cityChange">深圳</span><i class="icon-allow-down"></i></a>
        </div>
        <div class="my-box"><a href="<?php echo U('Mobile/Users/index');?>">我的</a></div>
    </div>
    <div class="header-bottom">
        <div class="search-wrap">
            <i id="cancelBtn" class="icons icon-close fl"></i>
            <form class="search-form" id="searchForm" name="searchForm" method="get" action="<?php echo U('Mobile/Search/list');?>">
                <input type="text" class="search-input" name="keywords" id="keywords"  placeholder="请输入关键词" value="<?php echo ($keywords); ?>">
                <i class="icons icon-search-small"></i>
                <a href="javascript:void(0);" id="searchBtn" class="search-big-box"><i class="icons icon-search-big"></i></a>
            </form>
        </div>
    </div>
</div>
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
<div class="new-containter">
    <!--焦点图-->
    <div class="slider" id="slider">
        <div class="hd">
            <ul>
                <!--小圆点 当前的加class="on"-->
                <li class="on"></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div class="bd">
            <ul>
                <li><img src="/somegood/Public/statics/mobile/images/new_banner1.jpg" alt=""></li>
                <li><img src="/somegood/Public/statics/mobile/images/new_banner2.jpg" alt=""></li>
                <li><img src="/somegood/Public/statics/mobile/images/new_banner3.jpg" alt=""></li>
            </ul>
        </div>
        <!--焦点图-->
    </div>
    <!--头部-->
    <script>TouchSlide({slideCell:"#slider",mainCell:".bd ul",autoPlay:true});</script>
    <!--导航-->
    <div class="new-nav">
        <ul>
            <?php if(is_array($indexCategoryList)): $i = 0; $__LIST__ = $indexCategoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Mobile/Goods/list',array('catid'=>$vo['id']));?>"><img src="/somegood/Public/uploads/<?php echo ($vo[img_url]); ?>" alt="<?php echo ($vo[title]); ?>" /><span><?php echo ($vo[title]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <!--导航-->
    <!--广告位-->
    <div class="adv-box">
        <a href="javascript:;"><img src="/somegood/Public/statics/mobile/images/adv-bg-1.jpg" alt="广告图"/></a>
    </div>
    <!--广告位-->
    <!--独立部分-->
    <div class="part-independent">
        <div class="small-div part-div b-r b-b">
            <a href="http://www.zhengbenedu.com/mobile/index">
                <img src="/somegood/Public/statics/mobile/images/part_1.jpg" alt="" />
            </a>
        </div>
        <div class="small-div part-div b-r b-b">
            <a href="http://www.caojinghua.cn/">
                <img src="/somegood/Public/statics/mobile/images/part_2.jpg" alt="" />
            </a>
        </div>
        <div class="small-div part-div b-r b-b">
            <a href="http://www.xiaojukeji.com/index/index">
                <img src="/somegood/Public/statics/mobile/images/part_3.jpg" alt="" />
            </a>
        </div>
        <div class="small-div part-div b-b">
            <a href="http://m.1688.com/market/-CBC4CCD8BEC6BCDBB8F1B1ED.html">
                <img src="/somegood/Public/statics/mobile/images/part_4.jpg" alt="" />
            </a>
        </div>
        <div class="small-div part-div b-r">
            <a href="http://m.1688.com/winport/dgzuoqidz88.html">
                <img src="/somegood/Public/statics/mobile/images/part_5.jpg" alt="" />
            </a>
        </div>
        <div class="small-div part-div b-r">
            <a href="https://h5.ele.me/msite/">
                <img src="/somegood/Public/statics/mobile/images/part_6.jpg" alt="" />
            </a>
        </div>
        <div class="small-div part-div b-r">
            <a href="http://m.osrzh.com/">
                <img src="/somegood/Public/statics/mobile/images/part_7.jpg" alt="" />
            </a>
        </div>
        <div class="small-div part-div">
            <a href="http://m.meilele.com">
                <img src="/somegood/Public/statics/mobile/images/part_8.jpg" alt="" />
            </a>
        </div>
    </div>
    <!--独立部分-->
    <!--广告位-->
    <div class="adv-box">
        <a href="javascript:;"><img src="/somegood/Public/statics/mobile/images/adv-bg-2.jpg" alt="广告图"/></a>
    </div>
    <!--广告位-->
    <!--好店推荐-->
    <div class="recommend">
        <div class="rec-title"><h2 class="fl">合作店铺</h2><a href="<?php echo U('Mobile/Store/list');?>" class="to-shop fr">更多店铺</a></div>
        <div class="goods-filter recommend-filter">
            <ul class="filter-ul">
                <li class="orderBtn sell all <?php if(($order) == "1"): ?>selected<?php endif; ?>" data-status="0" id="distance1"><span>综合</span></li>
                <li class="orderBtn sell type <?php if(($order) == "2"): ?>selected<?php endif; ?>" data-status="1" id="distance2"><span>距离</span></li>
                <li class="filter"><span>筛选</span></li>
            </ul>
<!--            <div class="nearby-box">
                <ul>
                    <li>1KM</li>
                    <li>2KM</li>
                    <li>3KM</li>
                </ul>
            </div>-->
        </div>
        <div class="rec-content-wrap" id="recContentWrap">
            <div class="rec-content">
                <ul>

                </ul>
            </div>
        </div>
    </div>
    <!--好店推荐-->

    <div class="over-lay"></div>
    <div class="over-lay-small"></div>
    <!--商品筛选-->
    <div class="kinds-box cooperation-kinds-box">
        <div class="kind-filter">
            <i class="icons icon-close-overlay fl"></i>
            <span class="fl">筛选</span>
        </div>
        <div class="filter-box">
            <div class="filter-item">
                <div class="item-tips">类别筛选：</div>
                <div class="item-tips">
                    <select id="typeId">
                        <option value="0">--请选择类别--</option>
                        <?php if(is_array($typeList)): $i = 0; $__LIST__ = $typeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($tl["id"]); ?>"<?php if(($tl["id"]) == $type): ?>selected<?php endif; ?> ><?php echo ($tl["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="tools-btn-box">
            <a class="cancleBtn" href="javascirpt:;">取消</a>
            <a class="submit-btn" id="submitBtn" href="javascript:;">确定</a>
        </div>
    </div>
    <!--推荐-->
    <div style="height:75px;"></div>

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
<!--隐藏搜索部分 主页和产品列表页可以公用-->
<script type="text/javascript">
    /*搜索提示*/
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
    $(function(){
        /*懒加载*/
        $(".rec-content li img").lazyload({effect : "fadeIn"});
        /*关闭搜索*/
        $("i.icon-close").on("click",function(){
            $(".icon-search-small").show();
            $(".header-top").show();
            $(".header .icon-close").hide();
            $(".search-mask").hide();
            $(".search-big-box").hide();
            $(".header").removeClass("on-focus");
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
            $(".header-top").hide();
            $(".header .icon-close").show();
            $(".search-mask").show();
            $(".search-big-box").show();
            $(".header").addClass("on-focus");
            $("body").css("overflow","hidden");
        })
        /*头部搜索框动画*/
        $(window).scroll(function(){
            var sTop = $(window).scrollTop();
            if(sTop >= 20){
                $(".a-wrap").hide();
                $(".header-bottom").addClass("scroll-style");
                $(".header").addClass("change-color");
            }else{
                $(".a-wrap").show();
                $(".header-bottom").removeClass("scroll-style");
                $(".header").removeClass("change-color");
            }
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
        /*类型筛选*/
        $(".goods-filter li.type").on("click",function(){
            if($(".nearby-box").hasClass("open")){
                $(".nearby-box").removeClass("open");
                $("body").css({"overflow-y":"auto"});
            }else{
                $(".nearby-box").addClass("open");
                $("body").css({"overflow-y":"hidden"});
            }
        })
        //筛选提交
        $("#submitBtn").on("touchstart",function(){
            var type = $("#typeId").val();
            if(type!=""){
                jumpUrl = "<?php echo U('Mobile/Index/index');?>?type="+type+"&order=<?php echo ($order); ?>&area_id=<?php echo ($area); ?>";
                window.location.href = jumpUrl;
            }
        })
        //距离排序
        $("#distance1").on("touchstart",function(){
            window.location.href = "<?php echo U('Mobile/Index/index');?>?type=<?php echo ($type); ?>&order=1&area_id=<?php echo ($area); ?>";
        })
        $("#distance2").on("touchstart",function(){
            window.location.href = "<?php echo U('Mobile/Index/index');?>?type=<?php echo ($type); ?>&order=2&area_id=<?php echo ($area); ?>";
        })

        /*筛选取消*/
        $(".cancleBtn").on("touchstart",function(){
            $(".kinds-box").animate({"left":"100%"},300,function(){
                $(".kinds-box").hide();
                $(".over-lay").hide();
                $("body").css({"overflow":"auto"});
            });
        })
    });
    //滚动页面ajax自动获取下一页内容
    var page=1; //有效求购的页码
    var totalPage = <?php echo ($totalPage); ?>; //有效求购页的总页码，会从后台获
    var ajaxUrl="<?php echo U('Mobile/Index/storeList');?>";//ajax请求地址
    $(function(){
        var tabLoadEnd = false;
        var dropload = $('.rec-content-wrap').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                // 加载菜单一的数据
                if(page <= totalPage){
                    $.ajax({
                        url:ajaxUrl,
                        type:"post",
                        dataType:"html",
                        data:{
                            'p':page,
                            'type':<?php echo ($type); ?>,
                            'area_id':<?php echo ($area); ?>,
                        },
                        success:function(data){
                            $('.rec-content-wrap ul').append(data);
                            //绑定计算距离
                            calcDistance(".distance");
                            //图片延迟加载
                            $(".rec-content li img").lazyload({effect : "fadeIn"});
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
</html><?php endif; ?>