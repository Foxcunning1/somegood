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
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/smartphoto.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/ershou/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/js/smartphoto.min.js"></script>
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
<!--详情图滚动图-->
<div class="page-title">
    <!--返回按钮-->
    <div class="back-box">
        <a href="javascript:history.back();" class="icons icon-back"></a>
    </div>
    <h2 class="title">商品详情</h2>
    <!--返回按钮-->
</div>
<!--商品详情-->
<div class="goods-detail" style="padding-top: 1rem">
    <div class="g-info">
        <div class="product-name">
            <span><?php echo ($info["goods_title"]); ?></span>
            <input type="hidden" name="goods_id" value="<?php echo ($info["id"]); ?>" id="goodsIdInput">
            <div class="collection"><i class="icons icon-like <?php if(($favNum) == "1"): ?>like<?php endif; ?>"></i><span>收藏</span></div>
        </div>
        <div class="product-price-m" style="display:flex;">
            <em class="fl" style="flex:1;">¥<span class="big-price"><?php echo ($info["goods_price"]); ?></span><span class="small-price"></span></em>
            <span  style="flex:1; text-align: center; font-size:0.22rem; color:#999;">数量：<?php echo ($info["goods_num"]); ?>件</span>
            <span class="view fr"  style="flex:1; text-align: right;"><span id="viewNum"><?php echo ($info["browse_num"]); ?></span>次浏览</span>
        </div>
        <div class="location">
            <a href="javascript:void(0);" class="location-a">
            <i class="icons icon-location fl"></i>
            <span>距离您约<em data-lng="<?php echo ($info["lng"]); ?>" data-lat="<?php echo ($info["lat"]); ?>" class="distance address">0</em> km </span>
            </a>
        </div>
    </div>
</div>

<!--商品详情-->
<!--商品描述-->
<div class="goods-desc">
    <div class="title">商品描述</div>
    <div class="desc">
        <div class="goods-desc-box"><?php echo ($info["goods_desc"]); ?></div>
        <div class="goods-detail-img">
            <?php $__FOR_START_21005__=0;$__FOR_END_21005__=count($photoList);for($i=$__FOR_START_21005__;$i < $__FOR_END_21005__;$i+=1){ ?><a href="/somegood/Public/uploads/<?php echo $photoList[$i];?>" class="js-img-viwer"><img data-original="/somegood/Public/uploads/<?php echo $photoList[$i];?>_c720x-" src="/somegood/Public/statics/ershou/images/default_img.png" alt="<?php echo ($remarkList[$i]); ?>" /></a><?php } ?>
        </div>
    </div>
</div>
<!--商品描述-->
<script>
    window.addEventListener('load',function(){
        new smartPhoto(".js-img-viwer");
        new smartPhoto(".js-img-viwer-fit");
    });
</script>
<script>

    $(function(){
        $(".goods-detail-img img").lazyload({effect : "fadeIn"});
    });
</script>
<!--所属店铺-->
<div class="belong-store">
    <div class="items-user">
        <a href="<?php echo U('Ershou/Store/myShop',array('shopid'=>$info['user_id']));?>">
            <img src="<?php if(empty($info["avatar"])): ?>/somegood/Public/statics/ershou/images/default_img.png<?php else: ?>/somegood/Public/uploads/<?php echo ($info["avatar"]); ?>_c200x200<?php endif; ?>" alt="<?php echo ($info["nick_name"]); ?>" class="user-img fl" />
            <div class="name-and-time fl">
                <span class="name"><?php echo ($info["nick_name"]); ?><i class="icons icon-reputation"></i></span>
            </div>
        </a>
    </div>
    <div class="impression">
        <ul>
            <li>
                <span><?php echo ($info["goods_count"]); ?>件</span>
                <span>在售宝贝</span>
            </li>
            <li>
                <span><?php echo ($info["sale_counts"]); ?>件</span>
                <span>成功卖出</span>
            </li>
            <li>
              <?php if($info['store_status'] == 1): ?><span><i class="icons icon-identification"></i></span>
                <span>已认证</span>
                <?php else: ?>
                <span><i class="icons icon-identification-not-yet"></i></span>
                <span>未认证</span><?php endif; ?>
            </li>
            <!-- class="icons icon-identification" -->
        </ul>
    </div>
    <div class="check-store-box"><a href="<?php echo U('Ershou/Store/myShop',array('shopid'=>$info['user_id']));?>" class="check-store">查看店铺</a></div>
</div>
<!--所属店铺-->
<!--推荐-->
<div class="for-you-recommend page-box">
    <div class="title">推荐</div>
    <div class="fy-content">
        <ul>
            <?php if(is_array($userRecGoodsList)): $i = 0; $__LIST__ = $userRecGoodsList;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
                <a href="<?php echo U('Ershou/Goods/detail',array('id'=>$item['id']));?>">
                    <img src="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>" alt="<?php echo ($item["goods_title"]); ?>" />
                    <div class="product-name">
                        <span><?php echo ($item["goods_title"]); ?></span>
                    </div>
                    <div class="product-price-m">
                        <em class="fl">¥<span class="big-price"><?php echo ($item["goods_price"]); ?></span></em>
                    </div>
                </a>
            </li><?php endforeach; endif; else: echo "$empty" ;endif; ?>
        </ul>
    </div>
</div>
<!--推荐-->
<div class="add-to-cart">
    <a href="<?php echo U('Index/index');?>" class="to-home"><i class="icons icon-home-bottom"></i><span>首页</span></a>
    <a href="javascript:void(0);" class="to-cart"><i class="icons icon-like <?php if(($favNum) == "1"): ?>like<?php endif; ?>"></i><span>收藏</span> </a>
    <a href="<?php if($info['goods_num'] == 0): ?>javascript:;<?php else: echo U('Ershou/Order/confirmOrder');?>?id=<?php echo ($info["id"]); endif; ?>" class="add-cart <?php if($info['goods_num'] == 0): ?>disable<?php endif; ?>">立即购买</a>
</div>
<form id="cartform" action="" method="post" >
  <input type="hidden" name="goods_id" id="goodsId" value="<?php echo ($info[id]); ?>" />
</form>
<!--公用底部-->
<!--公用底部-->
<script>
    //获取购物车商品的数量
    $(function(){
        calcDistance(".distance");//绑定计算距离
    })
    //收藏商品
    function popupa(info){
        $("body").minDialog({
            isAutoClose:true,
            closeTime:500,
            content: "<p>"+info+"</p>"
        });
    }
    $(".collection").on('touchstart',function(){
        var goods_id =$(this).prev().val();
        $.ajax({
            method:'post',
            dataType:'json',
            url: "<?php echo U('Mobile/Goods/collect');?>",
            data:{
                    "goods_id":goods_id,
                    "favorite_type":1
                 },
            async:false,
            success:function(data){
                if (data.status == 1) {
                    $(".icon-like").toggleClass("like");
                }
                popupa(data.info);
            }
        });
    })
    $("a.to-cart").on('touchstart',function(){
        var goods_id =$("#goodsIdInput").val();
        $.ajax({
            method:'post',
            dataType:'json',
            url: "<?php echo U('Mobile/Goods/collect');?>",
            data:{
                    "goods_id":goods_id,
                    "favorite_type":1
                 },
            async:false,
            success:function(data){
                if (data.status == 1) {
                    $(".icon-like").toggleClass("like");
                }
                popupa(data.info);
            }
        });
    })
    /*分享引导弹出*/
    $(".icon-share").on("touchstart",function(){
        $(".over-lay-share").show();
        $("body").css("overflow","hidden");
    })
    $("img.know").on("touchstart",function(){
        $(".over-lay-share").hide();
        $("body").css("overflow","auto");
    })
</script>
</body>
</html>