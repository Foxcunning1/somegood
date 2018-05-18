<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>订单结算</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/cart.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/ershou/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/ershou/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="javascript:void(0);" class="icons icon-back"></a>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <h2 class="title">订单结算</h2>
    <!--标题-->
</div>
<form method="post" class="order-form" id="orderForm" style="padding-top: 0.86rem;">
    <div class="order-wrapper">
        <div class="my-address" >
            <a href="<?php echo U('Ershou/Order/receipt');?>" class="my-address-a">
                <div class="name">
                    <div class="fl"><span><?php echo ($userDefaultAddress[consignee]); ?></span></div>
                    <div class="fl"><span><?php echo ($userDefaultAddress[mobile]); ?></span></div>
                    <?php if($userDefaultAddress['is_default'] == 1): ?><div class="fl">默认</div><?php endif; ?>
                </div>
                <div class="address">
                    <i class="icons icon-more"></i>
                    <i class="icons icon-location fl"></i>
                    <p class="address-where"><?php echo ($userDefaultAddress["iname"]); echo ($userDefaultAddress["oname"]); echo ($userDefaultAddress["rname"]); echo ($userDefaultAddress["address"]); ?></p>
                </div>
            </a>
            <span class="s1-borderB"></span>
        </div>
    </div>
    <div class="order-wrapper">
        <div class="items">
            <div class="item-l">
                <div class="list-title">订单列表</div>
            </div>
            <div class="item-r" style="border-bottom: 1px solid #f1f1f1">
                <div class="item-r">
                    <ul class="order-list-box">
                        <li>
                            <div class="goods-img-box">
                                <img alt="<?php echo ($goodsInfo["goods_title"]); ?>" src="/somegood/Public/uploads/<?php echo ($goodsInfo["goods_thumb"]); ?>_c200x200" />
                            </div>
                            <div class="goods-desc-info">
                                <h3><?php echo ($goodsInfo["goods_title"]); ?></h3>
                                <span>￥ <?php echo ($goodsInfo["goods_price"]); ?></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="order-wrapper" style="margin-bottom: 1.3rem">
        <div class="items">
            <div class="item-l fl"><span>运费：</span></div><div class="item-r fr"><span class="money" style="color:#323232;">￥<?php echo ($goodsInfo["logistics_price"]); ?></span></div>
        </div>
    </div>
    <div class="info-noteMsg">
        送至：<?php echo ($userDefaultAddress["iname"]); echo ($userDefaultAddress["oname"]); echo ($userDefaultAddress["rname"]); echo ($userDefaultAddress["address"]); ?>
    </div>
    <div class="order-bottom-bar">
        <div class="left">实付款：￥<span class="pay-money" id="pauMoney"><?php echo ($applyMoney); ?></span></div>
        <a href="javascript:void(0);" class="order-submit-btn" id="orderSubmitBtn">提交订单</a>
    </div>
</form>
<!--加载-->
<div class="is-loading" style="height: 35px; padding-top: 2px; display: none;">
    <em></em>
    <span>加载中...</span>
</div>
<script>
    //提交订单
    $(".order-submit-btn").on('touchstart',function(){
        window.location.href="<?php echo U('Ershou/Order/submitOrder');?>";
    })

    //跳转清空cookie
    $('.icon-back').on("touchstart",function(){
        var url="<?php echo U('Ershou/Order/returnPre');?>";
        $("body").minDialog({content:'便宜不等人,请三思而行~',isConfirm:true,btnConfirm:'去意已决',btnCancal:'我再想想'},function(){
            $(".confirmBtn").click(function(){
                window.location.href=url;
            })
        })
    })
</script>
</body>
</html>