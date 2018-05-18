<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>3好连锁店商城首页</title>
    <meta name="keywords" content="<?php echo ($web_keywords); ?>"/>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/shop/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/shop/css/style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
</head>
<body>
<div class="header">
    <div class="h-top">
    <div class="w1200">
        <div class="fr clearfix">
            <?php if(empty($_SESSION['shop']['id'])): ?><a href="<?php echo U('Shop/Login/index');?>" class="m">登录</a>
                <a href="<?php echo U('Shop/Login/register');?>" class="m">注册</a>
                <?php else: ?>
                <a href="<?php echo U('Shop/UserOrder/orderlist');?>">我的订单</a>
                <span class="line">/</span>
                <a href="<?php echo U('Shop/Users/index');?>">个人中心<span class="pointer"></span></a><?php endif; ?>
            <span class="line">/</span>
            <a href="<?php echo U('Shop/Cart/index');?>">购物车</a>
            <span class="line">/</span>
            <a href="javascript:;">客服服务<span class="pointer"></span></a>
            <span class="line">/</span>
            <a href="<?php echo U('Mobile/Index/index');?>">手机逛3好</a>
            <span class="line">/</span>
            <a href="javascript:;">更多<span class="pointer"></span></a>
        </div>
    </div>
</div>

    <div class="w1200">
        <div class="h-middle clearfix">
            <a href="<?php echo U('Shop/Index/index');?>" class="fl"><img src="/somegood/Public/statics/shop/images/logo_market.png" alt="logo" class="logo"/></a>
            <div class="search-box fl">
                <form class="search-form" method="post" action="<?php echo U('Shop/Goods/goodsList');?>">
                    <i class="icons icon-search"></i>
                    <input type="text"  name="keywords" placeholder="搜好物">
                    <button type="submit" class="submit">搜索</button>
                </form>
                <div class="hot-search">
                    <a href="javascript:;">女装</a>
                    <a href="javascript:;">衣柜</a>
                    <a href="javascript:;">双十一</a>
                </div>
            </div>
            <div class="my-cart fr">
                <a href="javascript:;" class="mini-cart-open">
                    <i class="icons icon-cart"></i>
                    <span class="txt">我的购物车</span>
                    <span class="goods-num">0</span>
                </a>
                <div class="my-cart-line"></div>
                <div class="mini-cart-box">
                    <h4>全部商品</h4>
                    <div class="items-list">
                    </div>
                    <div class="cart-to-box">
                        <div class="total-box">
                            共<span class="cart-num">0</span>件商品　共计<span class="cart-total-money">¥ 0</span>
                        </div>
                        <a href="<?php echo U('Shop/Cart/index');?>" class="to-cart-a">去购物车</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-bottom">
            <div class="all-category">
                <div class="title">全部分类</div>
                <div class="category-box goods-category-box">
                    <ul class="category-box-ul">
                        <li>
                            <a href="javascript:;">手机</a>
                            <span class="line">/</span>
                            <a href="javascript:;">运营商</a>
                            <span class="line">/</span>
                            <a href="javascript:;">智能数码</a>
                            <div class="category-box-content">
                                <div class="item clearfix">
                                    <div class="part1 fl">
                                        <div class="cannel clearfix">
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                        </div>
                                        <div class="detial">
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="part2 fl">
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <ul class="nav-ul">
                <li><a href="<?php echo U('Shop/Index/index');?>">首页</a></li>
                <li><a href="javascript:;">今日上新</a></li>
                <li><a href="javascript:;">全网秒杀</a></li>
                <li><a href="javascript:;">品牌投放</a></li>
            </ul>
        </div>
    </div>
</div>


<!--商品列表-->
<div class="list-container">
    <div class="w1200">
        <div class="bread-crumbs">您现在的位置：<a href="<?php echo C('MARKET_DOMAIN');?>">首页</a>&nbsp; &gt;&nbsp;
            <?php if($catId > 0): if(is_array($cinfo["breadCrumbs"])): $ke = 0; $__LIST__ = $cinfo["breadCrumbs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ca): $mod = ($ke % 2 );++$ke;?><a href="<?php echo C('MARKET_DOMAIN'); echo U('Goods/goodsList',array('category_id'=>$ca['id']));?>"><?php echo ($ca["title"]); ?></a>
                    <?php if(($ke != count($cinfo['breadCrumbs'])) OR (count($cinfo['breadCrumbs']) == 0)): ?>&nbsp;&gt;&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
        </div>
        <div class="classification">
            <dl class="service">
                <dt>分类：</dt>
                <dd>
                    <a <?php if(($category) == "0"): ?>class="select"<?php endif; ?> href="<?php echo C('MARKET_DOMAIN'); echo U('Goods/goodsList' , array('category_id' => $parent_id));?>">全部</a>
                    <?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><a <?php if(($item["id"]) == $catid): ?>class="select"<?php endif; ?> href="<?php echo U('Shop/Goods/goodsList' , array('catid' => $item['id']));?>"><?php echo ($item["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                </dd>
            </dl>
        </div>
        <div class="items-container">
            <ul>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
                        <a href="<?php echo U('Shop/Goods/goods' , array('id' => $item['id']));?>">
                            <img src="/somegood/Public/Uploads/<?php echo ($item["goods_thumb"]); ?>" alt="<?php echo ($item["goods_title"]); ?>">
                        </a>
                        <div class="desc">
                            <a href="<?php echo U('Shop/Goods/goods' , array('id' => $item['id']));?>" class="s-name"><?php echo ($item["goods_title"]); ?></a>
                            <span class="s-price">￥<?php echo ($item["price"]); ?></span>
                        </div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="line20 clear"></div>
            <div class="pagelist">
                <?php echo ($page); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<!--底部-->
<script>
    /*分类选择*/
    $(".classification dl").find("a").click(function(){
        $(this).addClass("select").siblings().removeClass("select");
    })
    /*菜单显示*/
    $(".all-category").hover(function(){
        $(".category-box").show();
    },function(){
        $(".category-box").hide();
    })
    /**/
    $(".category-box").hover(function(){
        $(this).show();
    },function(){
        $(this).hide();
    })
    /*二级菜单显示*/
    $(".category-box-ul").find("li").hover(function(){
        $(this).find(".category-box-content").show().siblings("li").find(".category-box-content").hide();
    },function(){
        $(this).find(".category-box-content").hide();
    })
</script>


<div class="footer">
    <div class="f-top">
        <div class="w1200">
            <div class="f-top-t clearfix">
                <div class="item">
                    <i class="icons icon-item icon-item1 fl"></i>
                    <div class="text">
                        <span class="big">100%正品</span>
                        <span>正品保障 假一赔十</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item2 fl"></i>
                    <div class="text">
                        <span class="big">低价保障</span>
                        <span>缩减中间环节 确保低价</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item3 fl"></i>
                    <div class="text">
                        <span class="big">无忧退货</span>
                        <span>严格按照消法处理</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item4 fl"></i>
                    <div class="text">
                        <span class="big">发票保障</span>
                        <span>售卖商品可提供发票</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item5 fl"></i>
                    <div class="text">
                        <span class="big">满188包邮</span>
                        <span>部分特殊商品除外</span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="f-top-b clearfix">
                <div class="box-l fl">
                    <dl>
                        <dt>购物指南</dt>
                        <dd><a href="javasctipt:;">账号注册</a></dd>
                        <dd><a href="javasctipt:;">购物流程</a></dd>
                        <dd><a href="javasctipt:;">付款方式</a></dd>
                    </dl>
                    <dl>
                        <dt>售后服务</dt>
                        <dd><a href="javasctipt:;">先行赔付</a></dd>
                        <dd><a href="javasctipt:;">退款退货流程</a></dd>
                        <dd><a href="javasctipt:;">投诉建议</a></dd>
                    </dl>
                    <dl>
                        <dt>商城保障</dt>
                        <dd><a href="javasctipt:;">正品保障</a></dd>
                        <dd><a href="javasctipt:;">物流配送</a></dd>
                        <dd><a href="javasctipt:;">发票保障</a></dd>
                    </dl>
                    <dl>
                        <dt>商家服务</dt>
                        <dd><a href="<?php echo U('Store/Index/index');?>">店铺中心</a></dd>
                        <dd><a href="<?php echo U('Seller/Index/index');?>">卖家中心</a></dd>
                    </dl>
                </div>
                <div class="box-c fl">
                    <i class="icons icon-tel fl"></i>
                    <div class="tell">
                        <span class="phone-num">0755-23999161</span>
                        <span>工作时间：9:00 - 18:00</span>
                    </div>
                </div>
                <div class="box-r fr">
                    <i class="icons icon-coopration fl"></i>
                    <dl>
                        <dt>商家合作</dt>
                        <dd>错过天猫别再错过我们</dd>
                        <dd>三好连锁开启全新旅程！</dd>
                        <dd><a href="javascript:;" class="join-us">关于入驻 >> </a></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <div class="f-bottom">
        <div class="w1200">
            <div class="f-bottom-l fl">
                <p>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                </p>
                <p class="copy-right">备案号XXXXXXXXXXXXXXXXXXXXXXXXX号   Copyright © 2006 - 2017  三好连锁 版权所有</p>
                <p>
                    <img src="/somegood/Public/statics/shop/images/b_img_1.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_2.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_3.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_4.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_5.png" alt="" />
                </p>
            </div>
            <div class="f-bottom-r fr clearfix">
                <span class="fl">扫一扫二维码</span>
                <img src="/somegood/Public/statics/shop/images/erweima.png" alt="" class="erwerma fl"/>
            </div>
        </div>
    </div>
</div>
<script>
    /*迷你购物车出现隐藏*/
    $(".mini-cart-open").hover(function(){
        $(".mini-cart-box").show();
        $(".my-cart-line").show();
    },function(){
        $(".mini-cart-box").hide();
        $(".my-cart-line").hide();
    })
    $(".mini-cart-box").hover(function(){
        $(this).show();
        $(".my-cart-line").show();
    },function(){
        $(this).hide();
        $(".my-cart-line").hide();
    })
    $(".my-cart-line").hover(function(){
        $(this).show();
        $(".mini-cart-box").show();
    },function(){
        $(this).hide();
        $(".mini-cart-box").hide();
    })
    /*隐藏菜单显示*/
    $(".category-box-ul").find("li").hover(function(){
        $(this).find(".category-box-content").show().siblings("li").find(".category-box-content").hide();
    },function(){
        $(this).find(".category-box-content").hide();
    })
    //获取购物车商品
    function getCartGoods() {
        $.ajax({
            type: 'POST',
            url: "<?php echo U('Shop/Cart/getCartGoods');?>",
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    $(".cart-num").text(data.data.cartNum);
                    $(".goods-num").text(data.data.cartNum);
                    var totalMoney = 0;
                    if(data.data.cartNum>0){
                        var list = data.data.list;
                        var str = '';
                        $.each(list,function(index,v){
                            str +="<div class=\"item-box\" id=\"cartGoods_"+v.cart_id+"\">";
                            str +=" <div class=\"cart-img\"><a href=\"<?php echo U('Shop/Goods/goods');?>?id="+v.real_goods_id+"\"><img src=\"/somegood/Public/uploads/"+v.goods_thumb+"\" alt=\""+v.goods_title+"\" /></a></div>";
                            str +="<div class=\"cart-name\"><a href=\"<?php echo U('Shop/Goods/goods');?>?id="+v.real_goods_id+"\" title=\""+v.goods_title+"\">"+v.goods_title+"</a></div>";
                            str +="<div class=\"cart-info\">";
                            str +="<div class=\"cart-price\">￥"+v.goods_price+" * "+v.counts+"件</div>";
                            str +="<a href=\"javascript:;\" class=\"cart-delete\"  id=\"delCart_"+v.cart_id+"\" onclick='delCart("+v.cart_id+","+v.goods_price+","+v.counts+");'>删除</a>";
                            str +="</div>";
                            str +=" <div class=\"clear\"></div>";
                            str +=" </div>";
                            totalMoney += v.goods_price*v.counts;
                        })
                        $(".items-list").html(str);
                        $(".cart-total-money").text(totalMoney);
                    }
                } else {
                    $(".cart-num").text("0");
                    $(".goods-num").text("0");
                    $(".cart-total-money").text('0');
                }
            }
        })
    }
    $(function () {
        getCartGoods();
    })
    //删除购物车
    function delCart(cartId,price,counts) {
        var curtTotalMoney=$(".cart-total-money").text()-price*counts;
        var curtCounts=$(".goods-num").text()-counts;
        $.ajax({
            type:"POST",
            dataType:'json',
            url:"<?php echo U('Mobile/Cart/delCartGoods');?>",
            data:{
                cart_id:cartId
            },
            success: function(data){
                $("#delCart_"+cartId).text("正在删除");
                window.setTimeout(function(){
                    $("#cartGoods_"+cartId).remove();
                    $(".cart-num").text(curtCounts);
                    $(".goods-num").text(curtCounts);
                    $(".cart-total-money").text(curtTotalMoney);
                },1000);
            }
        });
    }
</script>

</body>
</html>