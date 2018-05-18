<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
   <meta charset="utf-8">
    <title>订单</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" href="/somegood/Public/statics/store/css/store-public.css" type="text/css">
    <link rel="stylesheet" href="/somegood/Public/statics/store/css/store-style.css" type="text/css">
</head>
<body style="background-color: #edf1f2;">
<div class="tab-box">
    <ul class="tab-ul fl">
        <li class="sel"><a href="javascript:void(0);">订单信息</a><div class="move"></div></li>
    </ul>
</div>
<div class="info-box">
    <div class="dl-box" style="height: 400px; overflow: auto">
        <dl>
            <dt>订单信息</dt>
            <dd><span class="left">订单号：</span><span><?php echo ($list2["order_sn"]); ?></span></dd>
            <dd><span class="left">订单状态：</span><span>
                <?php switch($list2["status"]): case "0": ?>未付款<?php break;?>
                    <?php case "1": ?>待发货<?php break;?>
                    <?php case "2": ?>已发货<?php break;?>
                    <?php case "3": ?>订单已完成<?php break; endswitch;?>
            </span></dd>
        </dl>
        <?php if(($list2["delivery_way"]) == "0"): ?><dl>
                <dt>配送信息</dt>
                <dd><span class="left">配送方式：</span><span>到店购买</span></dd>
            </dl>
            <dl>
                <dt>购买人信息</dt>
                <dd><span class="left">购 买 人：</span><span><?php echo ($list2["consignee"]); ?></span></dd>
                <dd><span class="left">手机号码：</span><span><?php echo ($list2["mobile"]); ?></span></dd>
            </dl>
            <?php if($list2["status"] == 3): ?><dl>
                    <dt>付款信息</dt>
                    <dd><span class="left">支付方式：</span><span>到店支付</span></dd>
                    <dd><span class="left">付款时间：</span><span><?php echo (date('Y-m-d H:i:s',$list2["pay_time"])); ?></span></dd>
                    <dd><span class="left">付款总额：</span><span>￥<?php echo ($list2["money"]); ?></span></dd>
                </dl><?php endif; endif; ?>
        <?php if($list2["delivery_way"] == 1 and ($list2["status"] == 2 or $list2["status"] == 3)): ?><dl>
                <dt>配送信息</dt>
                <dd><span class="left">配送方式：</span><span><?php echo ($list2["name"]); ?></span></dd>
                <!--<dd><span class="left">运    费：</span><span>10元</span></dd>-->
            </dl>
            <dl>
                <dt>收货人信息</dt>
                <dd><span class="left">收 货 人：</span><span><?php echo ($list2["consignee"]); ?></span></dd>
                <dd><span class="left">地    址：</span><span><?php echo ($list2["dizhi"]); ?></span></dd>
                <dd><span class="left">手机号码：</span><span><?php echo ($list2["mobile"]); ?></span></dd>
            </dl>
            <dl>
                <dt>付款信息</dt>
                <dd><span class="left">支付方式：</span><span>在线支付</span></dd>
                <dd><span class="left">付款时间：</span><span><?php echo (date('Y-m-d H:i:s',$list2["pay_time"])); ?></span></dd>
                <dd><span class="left">付款总额：</span><span>￥<?php echo ($list2["money"]); ?></span></dd>
            </dl><?php endif; ?>
    </div>
</div>
</body>
</html>