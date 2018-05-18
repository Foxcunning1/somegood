<?php if (!defined('THINK_PATH')) exit(); if(($isAjax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
            <a href="<?php echo U('Mobile/Goods/detail');?>?id=<?php echo ($item["sg_id"]); ?>">
                <div class="g-img"><img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>" alt="商品图"></div>
                <div class="g-info">
                    <div class="product-name">
                        <span><?php echo ($item["goods_title"]); ?></span>
                    </div>
                    <div class="product-price-m">
                        <em>¥<span class="big-price"><?php echo ($item["price"]); ?></span><span class="small-price">.00</span></em>
                    </div>
                    <!--<div class="location-and-time">
                        <span class="time fl"><i class="icons icon-clock"></i><time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$item["audit_time"])); ?>"></time>发布</span>
                    </div>-->
                </div>
                <!--已下架的商品有这个价格阴影层,没有下架就没有-->
                <!--<span class="opcity-white"></span>-->
            </a>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
<?php else: ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title><?php echo ($pageTitle); ?></title>
    <link rel="stylesheet" href="/somegood/Public/dist/dropload.css">
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>

</head>
<body>
    <div class="page-title" >
        <!--返回按钮-->
        <div class="back-box">
            <a href="javascript:history.back();" class="icons icon-back"></a>
        </div>
        <h2 class="title"><?php echo ($pageTitle); ?></h2>
        <!--返回按钮-->
    </div>
    <!--商品列表-->
    <div class="page-box pd-t08">
        <ul class="search-list shop-goods-list" id="shopGoodsList"></ul>
    </div>
    <!--商品列表-->
    <!--公用底部-->
    <div class="shop-menu-bar">
        <a href="<?php echo U('Mobile/Store/detail');?>?shopid=<?php echo ($shopid); ?>"><i class="icons icon-shop-home"></i><span>店铺首页</span></a>
        <a href="tel:<?php echo ($info["mobile"]); ?>"><i class="icons icon-shop-contact"></i><span>联系商家</span></a>
        <a href="javascript:void(0);" class="shop-products"><i class="icons icon-shop-products"></i><span>产品</span></a>
    </div>
    <div style="height: 1rem;width: 100%; position: fixed; bottom: 0; right:0;z-index: 1">
        <div class="products-menu">
            <?php if(is_array($store_cate)): $i = 0; $__LIST__ = $store_cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sc): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Mobile/Store/storeGoods');?>?shopid=<?php echo ($shopid); ?>&category_id=<?php echo ($sc["goods_cate_id"]); ?>"><?php echo ($sc["goods_cate_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>

    <!--公用底部-->
    <!-- jQuery1.7以上 或者 Zepto 二选一，不要同时都引用 -->
    <script src="/somegood/Public/dist/dropload.min.js"></script>
    <script>
        /*店铺管理动画*/
        $(".shop-products").on("click",function(){
            $(".products-menu").toggleClass("click");
        })
        var page=1; //当前页的页码
        var allpage=<?php echo ($pageCount); ?>; //总页码，会从后台获
        var ajaxUrl="<?php echo U('Mobile/Store/storeGoods');?>";//ajax请求地址
        $(function(){
            $(".g-img img").lazyload({effect : "fadeIn"});
            var dropload = $('.page-box').dropload({
                scrollArea : window,
                loadDownFn : function(me){
                    if(page<=allpage){
                        $.ajax({
                            url:ajaxUrl,
                            type:"post",
                            dataType:"html",
                            data:{
                                'p':page,
                                'shopid':'<?php echo ($shopid); ?>',
                                'pageType':'<?php echo ($pageType); ?>',
                                'category_id':"<?php echo ($category_id); ?>"
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
</script>
</body>
</html><?php endif; ?>