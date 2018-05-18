<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
   <meta charset="utf-8">
    <title>订单</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" href="/somegood/Public/statics/store/css/store-public.css" type="text/css">
    <link rel="stylesheet" href="/somegood/Public/statics/store/css/store-style.css" type="text/css">
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
</head>
<body style="background-color: #edf1f2;">
<div class="tab-box">
    <ul class="tab-ul fl">
        <li class="sel"><a href="javascript:void(0);">物流</a><div class="move"></div></li>
    </ul>
</div>
<div class="info-box">
    <div class="dl-box logistics-dl-box">
        <dl>
            <dt>货运信息</dt>
            <dd><span class="left">送货方式：</span><span>普通快递</span></dd>
            <dd><span class="left">承运公司：</span><span><?php echo ($logistics_info["name"]); ?></span></dd>
            <dd><span class="left">货运单号：</span><span><?php echo ($logistics_info["logistics_sn"]); ?></span></dd>
        </dl>
    </div>
    <div class="logistics">
        <div class="dt">物流跟踪</div>
        <div class="content-box">
            <!--最顶上的一条加上class on 显示红色小点 其他的都为灰色-->
            <ul class="new-of-storey">
                <?php if(is_array($logistics_info["data"])): $i = 0; $__LIST__ = $logistics_info["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lo): $mod = ($i % 2 );++$i;?><li>
                        <span class="icon"></span>
                        <span><?php echo ($lo["context"]); ?></span>
                        <span><?php echo ($lo["time"]); ?></span>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("span.icon:first").addClass("on");
    })
</script>
</body>
</html>