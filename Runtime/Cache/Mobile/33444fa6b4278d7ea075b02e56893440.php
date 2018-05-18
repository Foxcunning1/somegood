<?php if (!defined('THINK_PATH')) exit(); if(($isAjax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
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
                        <span class="time fl">销量:<?php echo ($vo["online_sales_volume"]); ?></span>
                    </div>
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
    <title><?php echo ($info["store_name"]); ?></title>
    <link rel="stylesheet" href="/somegood/Public/dist/dropload.css">
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <script type="text/javascript">
        var usersLng = <?php echo (session('lng')); ?>;//用户所在的经纬度
        var usersLat = <?php echo (session('lat')); ?>;
    </script>
</head>
<body>
<!--店铺主页-->
<div class="seller-inner">
    <!--返回按钮和分享-->
    <div class="to-back-box">
        <a href="javascript:history.go(-1)" class="icons icon-back"></a>
    </div>
    <div class="seller-search">
        <div class="div-input" id="searchInput">请输入要搜索的商品...</div>
    </div>
    <div class="to-menu-box">
        <i class="icons icon-share"></i>
    </div>
    <!--返回按钮和分享-->
    <div class="seller-info">
        <img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="<?php if(empty($info["logo"])): ?>/somegood/Public/statics/mobile/images/default_img.png<?php else: ?>/somegood/Public/uploads/<?php echo ($sellerInfo["avatar"]); ?>_c200x200<?php endif; ?>" alt="<?php echo ($info["store_name"]); ?>" class="user-img" />
        <h2><?php echo ($info["company_name"]); ?></h2>
        <!--<a href="javascript:void(0);" class="attention-to"> <?php if(($favoriteStatus) == "1"): ?>取消<?php endif; ?>关注</a>-->
    </div>

</div>
<!--店铺主页-->
<!--分享引导-->
<div class="over-lay-share">
    <img src="/somegood/Public/statics/mobile/images/know.png" alt="我知道了" class="know" />
    <img src="/somegood/Public/statics/mobile/images/pointer.png" alt="点击右上角分享按钮分享产品" class="pointer" />
</div>
<!--分享引导-->
<!--所属店铺-->
<div class="belong-store shop-store">
    <div class="impression">
        <ul>
            <!-- <li><a href="<?php echo U('Mobile/Goods/sellerGoods');?>?id=<?php echo ($seller_id); ?>"><span class="num"></span><span>首页</span></a></li> -->
            <li><a href="<?php echo U('Mobile/Goods/sellerGoods');?>?id=<?php echo ($seller_id); ?>"><span class="num"></span><span>全部宝贝</span></a></li>
            <li><a href="<?php echo U('Mobile/Goods/sellerGoods');?>?id=<?php echo ($seller_id); ?>&pageType=new&seller_column_id=<?php echo ($seller_column_id); ?>&keywords=<?php echo ($keywords); ?>"><span class="num"></span><span>新品</span></a></li>
            <li><a href="<?php echo U('Mobile/Goods/sellerGoods');?>?id=<?php echo ($seller_id); ?>&pageType=hot&seller_column_id=<?php echo ($seller_column_id); ?>&keywords=<?php echo ($keywords); ?>"><span class="num"></span><span>热门</span></a></li>
        </ul>
    </div>
    <div class="main-sell-box">
        <div class="location-and-time">
            <a href="http://api.map.baidu.com/marker?location=<?php echo ($info["lat"]); ?>,<?php echo ($info["lng"]); ?>&title=<?php echo ($info["store_name"]); ?>&content=<?php echo ($info["address"]); ?>&output=html " class="location-a fl">
                <span class="location"><i class="icons icon-location fl" style="margin-top:3px;"></i></span>
            </a>
            <p class="main-sell"><?php echo ($info["address"]); ?></p>
        </div>
    </div>
</div>
<!--所属店铺-->
<!--店铺置顶商品列表-->
<div  class="other-shop-goods-list" style="padding-bottom: 0.8rem;">
    <ul class="search-list shop-goods-list" id="shopGoodsList">
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
                            <span class="time fl">销量:<?php echo ($vo["online_sales_volume"]); ?></span>
                        </div>
                    </div>
                </a>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<!--店铺置顶商品列表-->
<!--公用底部-->
 <div class="shop-menu-bar">
     <a href="<?php echo U('Mobile/Index/index');?>"><i class="icons icon-shop-home"></i><span>首页</span></a>
     <a href="tel:<?php echo ($info["mobile"]); ?>" id="mobile_contact"><i class="icons icon-shop-contact"></i><span>联系商家</span></a>
     <a href="javascript:void(0);" class="shop-products">
         <i class="icons icon-shop-products"></i>
         <span>分类</span>
     </a>
 </div>
<div class="over-lay"></div>
<!--商品筛选-->
<div class="kinds-box">
    <div class="kind-filter">
        <form class="seller-search-form" action="<?php echo U('Goods/sellerGoods');?>?id=<?php echo ($seller_id); ?>&pageType=<?php echo ($pageType); ?>&keywords=<?php echo ($keywords); ?>" method="post">
            <input type="text" class="seller-search fl" id="sellerInput" placeholder="请输入你要搜索的商品..." name="keywords">
            <button type="submit" class="icons icon-search-seller fr"></button>
        </form>
    </div>
    <div class="filter-box" style="bottom:0;">
        <div class="category-title">
            <span class="fl" id="choosed" style="margin-right:20px;">所有分类</span>
            <input type="hidden" name="catid" id="categoryId" value="<?php echo ($catid); ?>" />
        </div>
        <div class="left-classify-box" style="position: absolute; top: 0.70rem; bottom: 0; overflow: auto;">
             <div class="left-over-box" style="padding: 0; height: 100%; ">
                <ul>
                    <?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['count'] == 1): ?><li  class="sel" data-id="<?php echo ($vo["id"]); ?>" onclick="loadTwoCategory('<?php echo ($vo["name"]); ?>',<?php echo ($vo["id"]); ?>)"><a href="javascirpt:void(0);"><?php echo ($vo["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "$empty" ;endif; ?>
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
    <!--<div class="tools-btn-box">-->
        <!--<a class="cancleBtn" href="javascirpt:;">取消</a>-->
        <!--<a class="submit-btn" id="submitBtn" href="javascript:;">确定</a>-->
    <!--</div>-->
</div>
<!--商品筛选-->
<?php if(!empty($info["store_cate"])): ?><div class="shop-menu-box">
        <?php if(is_array($info["store_cate"])): $i = 0; $__LIST__ = $info["store_cate"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sc): $mod = ($i % 2 );++$i;?><a href=<?php echo U('Mobile/Store/storeGoods');?>?shopid=<?php echo ($info["sid"]); ?>&category_id=<?php echo ($sc["goods_cate_id"]); ?>><?php echo ($sc["goods_cate_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
    </div><?php endif; ?>
<!--公用底部-->
</body>
<script>
    $("#mobile_contact").on('click', function() {
        $("body").minDialog({
            title: '提示',
            content: "<p style=\"font-size:18px;\">联系电话:<?php echo ($info["mobile"]); ?></p>",
        });
    });
    //设置cookie
    function setCookie(name, value, seconds) {
    seconds = seconds || 0;   //seconds有值就直接赋值，没有为0，这个根php不一样。
    var expires = "";
    if (seconds != 0 ) {      //设置cookie生存时间
    var date = new Date();
    date.setTime(date.getTime()+(seconds*1000));
    expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+escape(value)+expires+"; path=/";   //转码并赋值
    }
    $(".shop-products").on("click",function(){
        $(".shop-menu-box").toggleClass("click");
    })
    /*分享引导弹出*/
    $(".icon-share").on("click",function(){
        $(".over-lay-share").show();
        $("body").css("overflow","hidden");
    })
    $("img.know").on("click",function(){
        $(".over-lay-share").hide();
        $("body").css("overflow","auto");
    })
    function screenOut(){
        $(".over-lay").show();
        $(".kinds-box").show().animate({"left":"15%"},300,function(){
            $('html').addClass('noscroll');
            $("body").css("overflow-y","hidden");
        });
    }
    function screenIn(){
        $(".kinds-box").animate({"left":"100%"},300,function(){
            $(".kinds-box").hide();
            $(".over-lay").hide();
            $('html').removeClass('noscroll');
            $("body").css({"overflow-y":"auto"});
        });
    }
    /*点击加载二级分类
    *@params    c_id                二级分类id
    *@params    c_name       一级分类名字
    */
    function loadTwoCategory(c_name,c_id) {
        $.ajax({
            url:"<?php echo U('Mobile/Goods/getSecondCategory');?>",
            type:"post",
            dataType:"json",
            data:{
                'c_id':c_id,
                'seller_id':<?php echo ($seller_id); ?>,
            },
            success:function(data){
                if (data.status == 1) {
                    console.log(data.data);
                    $("#categoryBox").children().remove();
                    var str = "";
                    str += "<dl class=\"filter-option-dl\">";
                    str +="<a class=\"filter-option-dt\" href=\"<?php echo U('Goods/sellerGoods');?>?id=<?php echo ($seller_id); ?>&pageType=<?php echo ($pageType); ?>&keywords=<?php echo ($keywords); ?>\">"+c_name+"</a>";
                    $.each($.parseJSON(data.data),function(index, el) {
                        str +="<dd data-value=\""+el.parent_id+"\" class=\"filter-option-dd\">";
                        str +="<a data-value=\""+el.id+"\" class=\"filter-option \" href=\"<?php echo U('Goods/sellerGoods');?>?id=<?php echo ($seller_id); ?>&pageType=<?php echo ($pageType); ?>&keywords=<?php echo ($keywords); ?>&seller_column_id="+el.id+"\">"+el.name+"</a>";
                        str +="</dd>";
                    });
                    str +="</dl>";
                $("#categoryBox").append(str);
                        }
                    },
                    error: function(xhr, type){
                        alert('Ajax error!');
                    }
        });
    }
    /*分类筛选滑出*/
    $(".shop-products").on("click",function(){
        screenOut();
    })
    /*点击阴影层筛选隐藏*/
    $(".over-lay").on("click",function(){
        $(".tools-btn-box").removeClass("tools-btn-box-show");
        screenIn();

    })
    /*筛选取消*/
    $(".cancleBtn").on("touchstart",function(){
        screenIn();
        $("#sellerInput").blur();
    })
    $("#searchInput").on("click",function(){
        screenOut();
        $("#sellerInput").focus();
    })
    $(function(){
        //图片延迟加载
        $(".shop-inner img").lazyload({effect : "fadeIn"});
        /*公用底部点击样式变换 放再公用的layout里面*/
        $(".footer ul").find("li").on("touchstart",function(){
            $(this).addClass("sel").siblings().removeClass("sel");
        })
        $(".timeago").timeago();
        calcDistance(".distance");//绑定计算距离
    })
</script>
<!-- jQuery1.7以上 或者 Zepto 二选一，不要同时都引用 -->
<script src="/somegood/Public/dist/dropload.min.js"></script>
<script>
    var page=2; //当前页的页码
    var allpage=<?php echo ($pageCount); ?>; //总页码，会从后台获
    var ajaxUrl="<?php echo U('Mobile/Goods/sellerGoods');?>";//ajax请求地址
    $(function(){
        var dropload = $('.other-shop-goods-list').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                if(page<=allpage){
                    $.ajax({
                        url:ajaxUrl,
                        type:"post",
                        dataType:"html",
                        data:{
                            'p':page,
                            'id':<?php echo ($seller_id); ?>,
                            'pageType':"<?php echo ($pageType); ?>",
                        },
                        success:function(data){
                            var str = data;
                            $('.shop-goods-list').append(str);
                            $(".timeago").timeago();
                            //图片延迟加载
                            $(".g-img img").lazyload({effect : "fadeIn"});
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
    //店铺关注/取消
    $(".attention-to").on('touchstart',function(){
        $.ajax({
            method:'post',
            dataType:'json',
            url:"<?php echo U('Mobile/Goods/collect');?>",
            data:{
                'goods_id':'<?php echo ($info["sid"]); ?>',
                'favorite_type':1,
            },
            success:function(data){
                if(data.status==1){
                    //操作成功
                    if(data.info==1){
                        //关注成功
                        $('.attention-to').html('取消关注');
                    }else{
                        //取消关注
                        $('.attention-to').html('关注');
                    }
                }else{
                    //操作失败
                    $('body').minDialog({
                        content:date.info,
                    })
                }
            }
        });
    })
</script>
</html><?php endif; ?>