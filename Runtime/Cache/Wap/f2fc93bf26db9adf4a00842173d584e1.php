<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title><?php echo ($web_title); ?></title>
    <meta name="keywords" content="<?php echo ($web_keywords); ?>"/>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/wap/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/wap/css/style.css" />
    <!--[if lt IE9]>
    <script type="text/javascript" src="/somegood/Public/scripts/js/jshtml5shiv.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/somegood/Public/statics/wap/js/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/wap/js/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/statics/wap/js/rem.js"></script>
</head>
<body>
    <!--include header-->
    <header>
        <a href="javascript:history.go(-1);" class="back-to"><</a>
        <h2>案例展示</h2>
        <div class="menu-icon"></div>
    </header>
    <article class="containter case-containter">
        <section class="title"><span>案例展示</span></section>
        <section class="content">
            <ul>
                <?php if(is_array($caseList)): $i = 0; $__LIST__ = $caseList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
                    <a href="<?php echo U('Wap/Case/show');?>?id=<?php echo ($item["aid"]); ?>"><img src="/somegood/Public/uploads/<?php echo ($item["img_url"]); ?>" alt="<?php echo ($item["title"]); ?>" /><span><?php echo ($item["title"]); ?></span></a>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </section>
        <section class="copy-right">
	<div class="fixed-to-top"></div>
	<div class="p-box">
		<p>Copyright © 2017 somegood.com.cn <br/>3好连锁传媒 版权所有</p>
	</div>
</section>
<a href="javascript:void(0);" id="totop" rexScrollTop="true"><img src="/somegood/Public/statics/wap/images/to_top_icon.png" alt="回到顶部" /></a>

    </article>
    <aside class="menu-aside">
	<ul>
		<li class="fadeInRightFast"><a href="/Wap"><i class="icons icons-home"></i><span>首页</span></a></li>
		<li class="fadeInRightFast"><a href="<?php echo U('Wap/About/profile');?>"><i class="icons icons-profile"></i><span>平台介绍</span></a></li>
		<li class="fadeInRightFast"><a href="<?php echo U('Wap/Case/list');?>"><i class="icons icons-case"></i><span>案例展示</span></a></li>
		<li class="fadeInRightFast" style="display: none;"><a href="<?php echo U('Wap/About/team');?>"><i class="icons icons-team"></i><span>团队支持</span></a></li>
		<li class="fadeInRightFast"><a href="<?php echo U('Wap/News/list');?>"><i class="icons icons-news"></i><span>新闻资讯</span></a></li>
		<li class="fadeInRightFast"><a href="<?php echo U('Wap/About/contact');?>"><i class="icons icons-contact"></i><span>联系我们</span></a></li>
	</ul>
</aside>
<div class="over-lay"></div>
<!--scrollanim.min.js一定要放在后面加载才可以 放在footer里面-->
<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/wap/js/scrollanim.min.js"></script>
<!--以下内容作为footer-->
<script>
	$(function(){
		/*右侧菜单出现*/
		$(".menu-icon").on("click",function(){
			if($("aside").hasClass("animate")){
				$("aside").removeClass("animate");
				$(".over-lay").removeClass("show");
				$(".containter").removeClass("animate");
			}else{
				$("aside").addClass("animate");
				$(".over-lay").addClass("show");
				$(".containter").addClass("animate");
			}
		})
		/*右侧菜单隐藏*/
		$(".over-lay").on("click",function(){
			$("aside").removeClass("animate");
			$(this).removeClass("show");
			$(".containter").removeClass("animate");
		})

	})
    //回到顶部的隐藏和显示,返回顶部按钮自动隐藏
    $(function(){
        $(window).scroll(function(){
            if ($(window).scrollTop()>360){
                $("#totop").show();
            }else if($(window).scrollTop()<360){
                $("#totop").hide();
            }
        });
        // 返回顶部按钮
        $('#totop').on("click",function(){
           $(window).scrollTop(0).stop(true,true);
        });
    })
</script>
</body>
</html>