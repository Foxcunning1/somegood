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
    <header>
        <a href="javascript:history.go(-1);" class="back-to"><</a>
        <h2>三好连锁传媒</h2>
        <div class="menu-icon"></div>
    </header>
    <article class="containter">
        <section class="title"><span>平台介绍</span></section>
        <section class="content">
            <p class="txt-box">
            <img src="/somegood/Public/statics/wap/images/peitu.png" alt="" class="txt-img" width="180" height="100"/>
            <span>
                三好连锁传媒是深圳比尔肯集团旗下单独成立的互联网+线下实体体验连锁平台，
                三好连锁传媒集产品推广销售，品牌推广为一体，结合线下体验和线上推广等多个手段，以达到多管齐下的推广销售效果！
                为中小企业的起步，大公司大平台的品牌建设搭建了一个强有力的推广平台！
            </span>
            <span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;三好连锁传媒秉承“公平，公正，公开”的核心理念，为每一个客户提供了一个公平的环境，公正的对待，公开资源。
                三好连锁传媒目前拥有线下实体点1000余个，铺设9大城市，合作客户超过100家。
            </span>
            <span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;线上注册用户流量超过10万个，线下流量超过100万（根据线下实体店流量预估）。
                平台产品直接销售额超过1000万RMB/月。
            </span>
            </p>
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