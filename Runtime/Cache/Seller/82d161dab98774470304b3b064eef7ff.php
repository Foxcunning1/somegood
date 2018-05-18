<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>卖家管理中心</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
</head>
<body style="background-color: #edf1f2;">
    <div class="setting-tab-box">
        <ul class="setting-tab-ul">
            <li class="two sel"><a href="javascript:void(0);"><span class="pic"></span><span class="txt">消息通知</span></a><div class="move"></div></li>
        </ul>
    </div>
    <div class="setting-info-box" >
        <a href="<?php echo U('Seller/Index/read');?>?id=all" class="all-readed">全部已读</a>
        <div class="setting-item" style="display: block">
            <ul class="massage-ul">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li  <?php if(($vo["is_readed"]) == "1"): ?>class="sel"<?php endif; ?>>
                        <h3><span class="order-push">【待发货】</span>
                            <a href="<?php echo U('Seller/Index/read');?>?id=<?php echo ($vo["id"]); ?>"><?php echo ($vo["content"]); ?></a></h3>
                        <div class="time"><?php echo (date("Y-m-d H:i:s",$vo["time"])); ?></div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
    <div class="pagelist">
        <div id="PageContent" class="default"><?php echo ($pageShow); ?></div>
    </div>
    <script>
        $(".all-readed").click(function(){
            $(".massage-ul").find("li").addClass("sel");
        })
    </script>
</body>
</html>