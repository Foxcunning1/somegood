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
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/wap/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/wap/css/style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/wap/css/animate.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/wap/css/scrollanim.min.css" />
    <!--[if lt IE9]>
    <script type="text/javascript" src="/somegood/Public/scripts/js/jshtml5shiv.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/somegood/Public/statics/wap/js/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/wap/js/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/statics/wap/js/TouchSlide.1.1.js"></script><script type="text/javascript" src="/somegood/Public/statics/wap/js/rem.js"></script>
</head>
<body>
    <header>
        <h2>三好连锁传媒</h2>
        <div class="menu-icon"></div>
    </header>
    <article class="containter">
        <section class="slider" id="slider">
            <div class="hd">
                <ul>
                    <li class="on"></li>
                    <li></li>
                </ul>
            </div>
            <div class="bd">
                <ul>
                    <li><img src="/somegood/Public/statics/wap/images/banner1.jpg" alt="banner1" /></li>
                    <li><img src="/somegood/Public/statics/wap/images/banner2.jpg" alt="banner2" /></li>
                </ul>
            </div>
        </section>
        <script>TouchSlide({slideCell:"#slider",mainCell:".bd ul",autoPlay:true});</script>
        <nav>
            <ul>
                <li class="selected"><a href="/Wap">首页</a></li>
                <li><a href="<?php echo U('Wap/About/profile');?>">平台介绍</a></li>
                <li><a href="<?php echo U('Wap/Case/list');?>">案例展示</a></li>
                <li style="display: none;"><a href="<?php echo U('Wap/About/team');?>">团队支持</a></li>
                <li><a href="<?php echo U('Wap/News/list');?>">新闻资讯</a></li>
                <li><a href="<?php echo U('Wap/About/contact');?>">联系我们</a></li>
                <li><a href="<?php echo U('Mobile/Index/index');?>">3好平台</a></li>
            </ul>
        </nav>
        <section class="floor floor-1">
            <h2>现在的<span>推广现状</span></h2>
            <div class="line"></div>
            <section class="bg-section"></section>
        </section>
        <section class="floor floor-2">
            <h2 data-kui-anim="fadeInRightFast">让推广回归市场本质！</h2>
            <h3 data-kui-anim="fadeInRightNormal">公平---在这里，没有贵胄平民之分，</h3>
            <h3 data-kui-anim="fadeInRightNormal">没有竞价排位之说，也没有昂贵的开户费！</h3>
            <h3 data-kui-anim="fadeInRightNormal">公正---在这里，平台把你的产品放到</h3>
            <h3 data-kui-anim="fadeInRightNormal">特定的消费群体且人流量大的地方，做到有的放矢！</h3>
            <h3 data-kui-anim="fadeInRightNormal">公开---在这里，用户不但能看到您的公司介绍，</h3>
            <h3 data-kui-anim="fadeInRightNormal">还能体验到您的产品带给他们的实体体验！</h3>
            <a href="javascript:;" class="contact-us-a" data-kui-anim="fadeInUpSlow">联系我们</a>
        </section>
        <section class="floor floor-3">
            <h2 data-kui-anim="fadeInRightFast">三好连锁传媒的<span>三大优势</span></h2>
            <div class="line" data-kui-anim="fadeInRightSlow"></div>
            <section class="bg-section" data-kui-anim="fadeInRightNormal"></section>
        </section>
        <section class="floor floor-4">
            <h2 data-kui-anim="fadeInRightFast">三好连锁传媒的合作方向（类型,行业?）</h2>
            <section class="bg-section" data-kui-anim="fadeInRightNormal"></section>
        </section>
        <section class="floor floor-5">
            <h2 data-kui-anim="fadeInRightFast">我们的-<span>新闻中心</span></h2>
            <div class="line" data-kui-anim="fadeInRightSlow"></div>
            <section class="img-section" data-kui-anim="fadeInRightNormal">
                <?php if(is_array($topNewsList)): $i = 0; $__LIST__ = $topNewsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$news): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Wap/News/show');?>?id=<?php echo ($news["aid"]); ?>">
                        <img src="/somegood/Public/uploads/<?php echo ($news["img_url"]); ?>" alt="<?php echo ($news["title"]); ?>" width="375" height="147"/>
                        <figcaption><a href="<?php echo U('Wap/News/show');?>?id=<?php echo ($news["aid"]); ?>"><?php echo (msubstr($news["title"],0,20)); ?></a></figcaption>
                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
            </section>
            <section class="news-list-section">
                <ul>
                    <!--显示3个好看一些-->
                    <?php if(is_array($newsList)): $i = 0; $__LIST__ = $newsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
                            <a href="<?php echo U('Wap/News/show');?>?id=<?php echo ($item["aid"]); ?>"><?php echo (msubstr($item["title"],0,30)); ?></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </section>
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