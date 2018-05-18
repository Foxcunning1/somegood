<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>3好连锁店商城首页</title>
    <meta name="keywords" content="<?php echo ($web_keywords); ?>"/>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/public.css">
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/style.css">
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript"  src="/somegood/Public/statics/shop/js/jquery.SuperSlide.2.1.1.js"></script>
    <style>
        .w900 { width: 900px; height: 100%; margin: 0 auto}
        .s-h-middle {border-bottom: 1px solid #e5e5e5;}
        .s-h-middle .logo { margin-right: 200px;}
        .s-h-middle .hot-search a { padding: 0 10px; color: #666; }
        .s-h-middle .search-form {width: 450px; float:left;margin-right: 10px;}
        .s-h-middle .search-form input { width: 300px; float:left;}
        .s-h-bottom { position: relative; background-color: #e5e5e5;}
        .seller-header { height: 100px; }
        .seller-header .s-logo { display: block; width: 80px; height: 80px;margin: 10px 0}
        .s-h-bottom .all-goods { width: 190px; background-color: #499e39; color: #fff; text-align: center; position: relative;}
        .s-h-bottom .all-goods .triangle { position: absolute; right: 15px; top: 16px; width: 0; height: 0; font-size: 0; line-height: 0; border-width: 4px; border-style: solid; border-color: #fff #499e39 #499e39; transition: all .3s ease}
        .s-h-bottom .all-goods-box { width: 190px; position: absolute; top: 36px; left:0; background-color: #fff; z-index: 5; display: none;}
        .s-h-bottom .all-goods:hover .all-goods-box { display: block}
        .s-h-bottom .all-goods:hover .triangle { transform: rotate(180deg);}
        .s-h-bottom .all-goods-box li {position: relative; }
        .s-h-bottom .all-goods-box li .disc { display: block; float:left; width: 3px; height: 3px; background-color: #000; margin: 13px 0 14px 20px;}
        .s-h-bottom .all-goods-box li a { display: block; height: 30px; line-height: 30px; padding-left: 30px; border-bottom: 1px solid #eee; color: #222; text-align: left; }
        .s-h-bottom .all-goods-box li span { position: absolute; right: 5px; top: 10px; font-family: "宋体"; color: #666; line-height: 1;transition: all .3s ease}
        .s-h-bottom .all-goods-box li:hover { background-color: #eee;}
        .s-h-bottom .all-goods-box li:hover span { transform: rotate(90deg);}
        .s-h-bottom .list-2 { width: 190px; position: absolute; left: 190px;top: 0; background-color: #fff; display: none;}
        .s-h-bottom .all-goods-box li:hover .list-2 { display: block;}
        .s-h-bottom .list-2 li { background-color: #fff}
        .s-h-bottom .list-2 li .disc { background-color: #222;}
        .s-h-bottom .list-2 li a { color: #222;}
        .s-h-bottom .list-2 li:hover a { color: #333;}
        .s-h-bottom .list-2 li:hover .disc { background-color: #333}
        .s-h-bottom .nav-ul { padding-left: 0; border-bottom: 1px solid #f5f5f5; overflow: hidden;}
        .s-h-bottom .nav-ul li a { font-weight: normal; font-size: 14px; color: #222;}
        .s-h-bottom .nav-ul li.list:hover a { background-color: #499e39; color: #fff;}
        .s-slider { height: 400px; width: 100%; position: relative; overflow: hidden; }
        .s-slider .bd li {  width: 100%; height: 400px; }
        .s-good-box .goods-box { padding: 20px 0; overflow: hidden; display: none;}
        .s-good-box .goods-box li { padding: 0 5px; background-color: #fff; width: 290px; float:left; text-align: center;font-size: 14px;}
        .s-good-box .goods-box li img { display: block; width: 160px; height: 130px; margin: 15px auto; transition: all .4s ease;}
        .s-good-box .goods-box li .txt { display: block; height: 36px; line-height: 18px; color: #333; word-break: break-all; overflow: hidden; }
        .s-good-box .goods-box .price { display: block; height: 24px; line-height: 24px; color: #e9424b;}
        .s-good-box .goods-box li:hover .txt { color: #e9424b;}
        .s-good-box .goods-box li:hover img { transform: translateX(3px)}
        /*店铺样式*/
        .seller-submit { display: block; width: 100px; height: 38px; line-height: 38px; background-color: #499e39; color: #fff; font-size: 16px; border: none; cursor: pointer;}
        .seller-title { width: 600px; height: 100px; line-height: 100px; font-size: 30px; text-align: center;  margin: 0 auto;}
        .seller-title li { width: 200px;float:left; }
        .seller-title li a {display: block;height: 40px; line-height: 40px;border-right: 1px solid #e5e5e5; color: #999; margin: 30px 0;}
        .seller-title li.select a { color: #333; }
    </style>
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

        <div class="h-middle s-h-middle clearfix">
            <div class="w1200">
                <a href="<?php echo U('Shop/Index/index');?>" class="fl"><img src="/somegood/Public/statics/shop/images/logo_market.png" alt="logo" class="logo"/></a>
                <div class="search-box fl">
                    <form class="search-form" method="post" action="<?php echo U('Shop/Goods/sellerDetail');?>">
                        <i class="icons icon-search"></i>
                        <input type="text" name="keywords" placeholder="搜好物" value="<?php echo ($keywords); ?>">
                        <input type="hidden" name="id" value="<?php echo ($seller_id); ?>">
                        <button type="submit" class="seller-submit fl">搜本店</button>
                    </form>
                    <div class="hot-search">
                        <a href="javascript:;">女装</a>
                        <a href="javascript:;">衣柜</a>
                        <a href="javascript:;">双十一</a>
                    </div>
                </div>
                <div class="my-cart fr">
                    <a href="<?php echo U('Shop/Cart/index');?>" class="mini-cart-open">
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
        </div>
        <div class="seller-header">
            <div class="w900">
                <a href="<?php echo U('Seller/Goods/enter_shop');?>" class="fl"><img src="" alt="店铺logo" class="logo s-logo"/></a>
            </div>
        </div>
        <div class="h-bottom s-h-bottom">
            <div class="w900">
                <div class="all-goods fl">
                    本店所有商品
                    <span class="triangle"></span>
                    <div class="all-goods-box">
                        <ul>
                            <?php if(is_array($column_list)): $i = 0; $__LIST__ = $column_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cl): $mod = ($i % 2 );++$i; if(($cl["parent_id"]) == "0"): ?><li>
                                        <i class="disc"></i>
                                        <a href="<?php echo U('Shop/Goods/sellerDetail');?>?id=<?php echo ($seller_id); ?>&column=<?php echo ($cl["id"]); ?>"><?php echo ($cl["name"]); ?></a>
                                        <span>&gt;</span>
                                        <div class="list-2">
                                            <ul>
                                                <?php if(is_array($column_list)): $i = 0; $__LIST__ = $column_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sl): $mod = ($i % 2 );++$i; if(($sl["parent_id"]) == $cl["id"]): ?><li><i class="disc"></i><a href="<?php echo U('Shop/Goods/sellerDetail');?>?id=<?php echo ($seller_id); ?>&column=<?php echo ($sl["id"]); ?>"><?php echo ($sl["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                            </ul>
                                        </div>
                                    </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                </div>
                <ul class="nav-ul fl">
                    <li><a href="<?php echo U('Shop/Goods/sellerDetail');?>?id=<?php echo ($seller_id); ?>">首页</a></li>
                    <?php if(is_array($column_list)): $i = 0; $__LIST__ = $column_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$col): $mod = ($i % 2 );++$i; if(($col["is_index"]) == "1"): ?><li class="list"><a href="<?php echo U('Shop/Goods/sellerDetail');?>?id=<?php echo ($seller_id); ?>&column=<?php echo ($col["id"]); ?>"><?php echo ($col["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="s-slider">
        <div class="bd">
            <ul>
                <li style="background:url('/somegood/Public/statics/shop/images/banner1.jpg') no-repeat center center;"></li>
            </ul>
        </div>
    </div>
</div>
<!--<div class="seller-title">
    <ul>
        <li class="select"><a href="javascript:;">全部</a></li>
        <li><a href="javascript:;">新品</a></li>
        <li><a href="javascript:;">热卖</a></li>
    </ul>
</div>-->
<div class="s-good-box">
    <div class="goods-box" style="display: block;">
        <div class="w900">
            <ul>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
                        <a href="<?php echo U('Shop/Goods/goods' , array('id' => $item['id']));?>">
                            <img src="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>" alt="" />
                            <span class="txt"><?php echo ($item["goods_title"]); ?></span>
                            <span class="price">￥<?php echo ($item["price"]); ?></span>
                        </a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
   <!-- <div class="goods-box">
        <div class="w900">
            <ul>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
                        <a href="<?php echo U('Shop/Goods/goods' , array('id' => $item['id']));?>">
                            <img src="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>" alt="" />
                            <span class="txt"><?php echo ($item["goods_title"]); ?></span>
                            <span class="price">￥<?php echo ($item["price"]); ?></span>
                        </a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
    <div class="goods-box">
        <div class="w900">
            <ul>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
                        <a href="<?php echo U('Shop/Goods/goods' , array('id' => $item['id']));?>">
                            <img src="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>" alt="" />
                            <span class="txt"><?php echo ($item["goods_title"]); ?></span>
                            <span class="price">￥<?php echo ($item["price"]); ?></span>
                        </a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>-->
</div>
<div class="line40"></div>
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
<script>
    /*全部新品热卖切换*/
    $(".seller-title").find("li").click(function(){
        $(this).addClass("select").siblings().removeClass("select");
        $(".s-good-box").find(".goods-box").eq($(this).index()).show().siblings().hide();
    })
</script>
</body>
</html>