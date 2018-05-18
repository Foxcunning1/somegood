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
        <li <?php if(($pageType) == "sale"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Goods/index',array('type'=>'sale'));?>">在售</a><div class="move"></div></li>
        <li <?php if(($pageType) == "off"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Goods/index',array('type'=>'off'));?>">未上架</a><div class="move"></div></li>
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
                    <a href="<?php echo U('Shop/Goods/goods' , array('id' => $item['id']));?>" target="_blank" class="img-a-box btn-info"><img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>_c200x200" alt="<?php echo ($item["goods_title"]); ?>"></a>
                    <a href="<?php echo U('Shop/Goods/goods' , array('id' => $item['id']));?>" target="_blank" class="desc btn-info"><?php echo ($item["goods_title"]); ?></a>
                    <div class="price">￥<span><?php echo ($item["price"]); ?></span></div>
                    <div class="price">库存<span><?php echo ($item["online_stock"]); ?></span></div>
                    <div class="price">销量<span><?php echo ($item["online_sales_volume"]); ?></span></div>
                    <div class="goods-btn-box">
                        <!--待发货的商品的按钮-->
                        <a href="<?php echo U('Shop/Goods/goods' , array('id' => $item['id']));?>" target="_blank" class="btn-info">详情</a>
                        <a href="<?php echo U('Seller/Goods/publish');?>?id=<?php echo ($item["id"]); ?>&action=edit"  class="btn-info">修改</a>
                        <?php if(($item["is_onsale"]) == "0"): ?><a href="javascript:void(0);"  onclick="updateGoodsSaleStatus('<?php echo ($item["id"]); ?>','on')" class="btn-info" >上架</a>
                            <?php else: ?>
                            <a href="javascript:void(0);"   onclick="updateGoodsSaleStatus('<?php echo ($item["id"]); ?>','off')" class="btn-info">下架</a>
                            <?php if(($item["is_extension"]) == "0"): ?><a href="javascript:;"  class="apply-extension btn-info" data-value="<?php echo ($item["id"]); ?>" data-l="<?php echo ($item["length"]); ?>" data-w="<?php echo ($item["width"]); ?>" data-h="<?php echo ($item["height"]); ?>">申请推广</a>
                                <?php else: ?>
                                <a href="javascript:;"  class="btn-info">已申请</a><?php endif; endif; ?>

                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </form>
    </div>
</div>
<div class="pagelist">
    <div id="PageContent" class="default"><?php echo ($pageShow); ?></div>
</div>
<script>
    /*变更商品上架状态*/
    function updateGoodsSaleStatus(id,status) {
        $.ajax({
            url:"<?php echo U('Seller/Goods/updateGoodsSale');?>",
            type:"post",
            dataType:"json",
            data:{
                'id':id,
                'status':status,
            },
            success:function(data){
                if(data.status==1){
                    var d = dialog({content:data.info}).show();
                    setTimeout(function () {
                        d.close().remove();
                        window.location.href="/somegood/Seller/Goods/index/type/off.html";
                    }, 1500);
                }else{
                    var d = dialog({content:data.info}).show();
                    setTimeout(function () {
                        d.close().remove();
                    }, 1500);
                }
            }
        });
    }

    $(".apply-extension").click(function(){
        var d = top.dialog({
            title:"申请推广",
            content: "<div class='apply-extension-form'>" +
            "<form action='' id='applyExtensionForm'>" +
            "<dl>" +
            "<dt>长</dt>" +
            "<dd>" +
            "<input type='text' id='length' value='"+$(this).attr("data-l")+"'/>mm</dd>" +
            "</dl>" +
            "<dl>" +
            "<dt>宽</dt>" +
            "<dd>" +
            "<input type='text' id='width' value='"+$(this).attr("data-w")+"'/>mm</dd>" +
            "</dl>" +
            "<dl>" +
            "<dt>高</dt>" +
            "<dd>" +
            "<input type='text' id='height' value='"+$(this).attr("data-h")+"'/>mm</dd>" +
            "<input type='hidden' id='goods_id' value='"+$(this).attr("data-value")+"'></dd>" +
            "</dl>" +
            "</form>" +
            "</div>",
            okValue: '确定',
            ok: function () {
                parent.bindSubmitBtn1('/somegood/Seller/Goods/index/type/off.html');
                this.title('提交中…');
                return true;
            },
            cancelValue: '取消',
            cancel: function () {}
        }).width(300).height(90).showModal();
    })

</script>
</body>
</html>