<?php if (!defined('THINK_PATH')) exit(); if(($isAjax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
            <a href="<?php echo U('Mobile/Goods/detail');?>?id=<?php echo ($item["id"]); ?>">
                <div class="g-img"><img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>_c200x200" alt="商品图" /></div>
                <div class="g-info">
                    <div class="product-name">
                        <span><?php echo ($item["goods_title"]); ?></span>
                    </div>
                    <div class="product-price-m">
                        <em>¥<span class="big-price"><?php echo ($item["price"]); ?></span><span class="small-price">.00</span></em>
                    </div>
                    <div class="location-and-time">
                        <span class="time fl"><i class="icons icon-clock"></i><time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$item["add_time"])); ?>"></time>发布</span>
                    </div>
                </div>
                <!--已下架的商品有这个价格阴影层,没有下架就没有-->
                <!--<span class="opcity-white"></span>-->
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
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <script type="text/javascript">
        var usersLng = <?php echo (session('lng')); ?>;//用户所在的经纬度
        var usersLat = <?php echo (session('lat')); ?>;
    </script>
</head>
<body>
<!--店铺主页-->
<div class="shop-inner">
    <!--返回按钮和分享-->
    <div class="to-back-box">
        <a href="<?php echo U('Mobile/Index/index');?>" class="icons icon-back"></a>
    </div>
    <h2 class="title"><?php echo ($info["store_name"]); ?></h2>
    <div class="to-menu-box">
        <i class="icons icon-share"></i>
    </div>
    <!--返回按钮和分享-->
    <img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="<?php if(empty($info["logo"])): ?>/somegood/Public/statics/mobile/images/default_img.png<?php else: ?>/somegood/Public/uploads/<?php echo ($info["logo"]); ?>_c200x200<?php endif; ?>" alt="<?php echo ($info["store_name"]); ?>" class="user-img" />
    <!--<a href="javascript:void(0);" class="attention-to"> <?php if(($favoriteStatus) == "1"): ?>取消<?php endif; ?>关注</a>-->
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
            <li><a href="<?php echo U('Mobile/Store/storeGoods');?>?shopid=<?php echo ($info["sid"]); ?>&pageType=new"><span class="num"></span><span>最新</span></a></li>
            <li><a href="<?php echo U('Mobile/Store/storeGoods');?>?shopid=<?php echo ($info["sid"]); ?>&pageType=hot"><span class="num"></span><span>热卖</span></a></li>
            <li><a href="<?php echo U('Mobile/Store/storeGoods');?>?shopid=<?php echo ($info["sid"]); ?>&pageType=rec"><span class="num"></span><span>推荐</span></a></li>
            <li><a href="<?php echo U('Mobile/Store/storeGoods');?>?shopid=<?php echo ($info["sid"]); ?>&pageType=top"><span class="num"></span><span>置顶</span></a></li>
        </ul>
    </div>
    <div class="main-sell-box">
        <div class="location-and-time">
            <a href="http://api.map.baidu.com/marker?location=<?php echo ($info["lat"]); ?>,<?php echo ($info["lng"]); ?>&title=<?php echo ($info["store_name"]); ?>&content=<?php echo ($info["address"]); ?>&output=html " class="location-a">
                <span class="location"><i class="icons icon-location fl" style="margin-top:3px;"></i><em data-lng="<?php echo ($info["lng"]); ?>" data-lat="<?php echo ($info["lat"]); ?>" class="distance address fl">0</em>Km</span>
            </a>
            <p class="main-sell"><?php echo ($info["region"]); ?></p>
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
                            <span class="time fl">总销量:<?php echo ($vo["total_sales_volume"]); ?></span>
                        </div>
                    </div>
                </a>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<!--店铺置顶商品列表-->
<!--公用底部-->
 <div class="shop-menu-bar">
     <a href="<?php echo U('Mobile/Store/detail');?>?shopid=<?php echo ($shop_id); ?>"><i class="icons icon-shop-home"></i><span>店铺首页</span></a>
     <a href="tel:<?php echo ($info["mobile"]); ?>"><i class="icons icon-shop-contact"></i><span>联系商家</span></a>
     <!-- <a href="javascript:void(0);" class="shop-products">
         <i class="icons icon-shop-products"></i>
         <span>产品</span>
     </a> -->
 </div>
<?php if(!empty($info["store_cate"])): ?><div class="shop-menu-box">
        <?php if(is_array($info["store_cate"])): $i = 0; $__LIST__ = $info["store_cate"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sc): $mod = ($i % 2 );++$i;?><a href=<?php echo U('Mobile/Store/storeGoods');?>?shopid=<?php echo ($info["sid"]); ?>&category_id=<?php echo ($sc["goods_cate_id"]); ?>><?php echo ($sc["goods_cate_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
    </div><?php endif; ?>
<!--公用底部-->
</body>
<script>
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
    var page=1; //当前页的页码
    var allpage=<?php echo ($pageCount); ?>; //总页码，会从后台获
    var ajaxUrl="<?php echo U('Mobile/Store/detail');?>";//ajax请求地址
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
                            'shopid':<?php echo ($shop_id); ?>,
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