<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>商品列表</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.lazyload.min.js"></script>
</head>
<body style="background-color: #edf1f2;overflow-y: auto;">
<div class="tab-box">
    <ul class="tab-ul fl">
        <li <?php if(($pageType) == "all"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Goods/index',array('type'=>'all'));?>">全部</a><div class="move"></div></li>
        <li <?php if(($pageType) == "wait"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Goods/index',array('type'=>'wait'));?>">待投放</a><div class="move"></div></li>
        <li <?php if(($pageType) == "ing"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Goods/index',array('type'=>'ing'));?>">投放中</a><div class="move"></div></li>
    </ul>
</div>
<div class="info-box">
    <div class="goods-list-box">
        <!--<div class="all-select-box">-->
            <!--<label for="allSelect" id="allSelectLabel"><input type="checkbox" id="allSelect"/>全选</label>-->
            <!--<a href="javascript:;"  class="print-a">批量打印</a>-->
        <!--</div>-->
        <form action="<?php echo U('Store/Goods/printList');?>" class="goods-form" id="goodsForm" method="post" target="_blank">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><div class="goods-item" id="item<?php echo ($item["id"]); ?>">
                    <!--<input type="checkbox" name="ids[]" value="<?php echo ($item["id"]); ?>"/>-->
                    <a href="<?php echo U('Seller/Goods/sellerDelivery');?>?id=<?php echo ($item["id"]); ?>" class="img-a-box btn-info"><img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>_c200x200" alt="<?php echo ($item["goods_title"]); ?>"></a>
                    <a href="<?php echo U('Seller/Goods/sellerDelivery');?>?id=<?php echo ($item["id"]); ?>" class="desc btn-info"><?php echo ($item["goods_title"]); ?></a>
                    <div class="price">￥<span><?php echo ($item["price"]); ?></span></div>
                    <div class="price"><span><?php if(($item["status"]) == "0"): ?>(待投放)<?php endif; ?> <?php if(($item["status"]) == "1"): ?>(投放中)<?php endif; ?>目前<?php echo ($item["store_count"]); ?>家店在售</span></div>
                    <div class="price">线下总销量<span><?php if($item["sales_volume"] == 0): ?>0<?php else: echo ($item["sales_volume"]); endif; ?></span></div>
                    <div class="price">线上销量<span><?php echo ($item["online_sales_volume"]); ?></span></div>
                    <div class="goods-btn-box">
                        <!--待发货的商品的按钮-->
                        <a href="<?php echo U('Seller/Goods/sellerDelivery');?>?id=<?php echo ($item["id"]); ?>"  class="btn-info">详情</a>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </form>
    </div>
</div>
<div class="pagelist">
    <div id="PageContent" class="default"><?php echo ($pageShow); ?></div>
</div>
</body>
</html>