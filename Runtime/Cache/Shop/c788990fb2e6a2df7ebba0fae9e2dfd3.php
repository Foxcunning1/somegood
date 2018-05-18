<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>3好连锁店商城首页</title>
    <meta name="keywords" content="<?php echo ($web_keywords); ?>"/>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <link rel="stylesheet" href="/somegood/Public/statics/shop/css/cart.css">
    <link rel="stylesheet" href="/somegood/Public/statics/shop/css/public.css">
    <link rel="stylesheet" href="/somegood/Public/scripts/artdialog/ui-dialog.css">
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/shop/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/shop/js/cart.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
</head>
<body>
<div class="header cart-header">
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
            <a href="<?php echo U("Index/index");?>" class="fl"><img src="/somegood/Public/statics/shop/images/logo_market.png" alt="logo" class="logo"/></a>
            <div class="search-box fr">
                <form class="search-form">
                    <i class="icons icon-search"></i>
                    <input type="text" placeholder="搜好物">
                    <button type="submit" class="submit">搜索</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="w1200 container mt30">
    <!--<?php if($info == null): ?>-->
        <div class="cart-msg-box">
            <div class="cart-msg-icon fl"></div>
            <div class="cart-msg-tips fr">
                    <p>购物车空空的哦~，赶快去选择您想的要的商品吧~ </p>
                    <p><a href="<?php echo U('Shop/Index/index');?>">去选择商品&gt;&gt;</a></p>
            </div>
        </div>
        <!--<?php else: ?>-->
        <div class="cart-title">
            <h3 class="fl">全部商品</h3>
            <div class="right-tools-box fr">
                已选商品 <span class="total-price">¥0.00</span><a href="javascript:;" class="submit-btn">结算</a>
            </div>
        </div>
        <div class="cart-table">
            <div class="th">
                <div class="chk th-chk fl">
                    <div class="th-inner"><label class="chk-box allChk" onclick="checkAllCart(this,0);">全选</label></div>
                </div>
                <div class="service th-service fl"><div class="th-inner">商品信息</div></div>
                <div class="price th-price fl"><div class="th-inner">价格</div></div>
                <div class="amount th-amount fl"><div class="th-inner">数量</div></div>
                <div class="pay-amount th-pay-amount fl"><div class="th-inner">金额</div></div>
                <div class="operate th-operate fl"><div class="th-inner">操作</div></div>
            </div>
            <div class="cart-list-box">
                <form name="cartForm" id="cartForm" action="<?php echo U('Shop/Order/settlement');?>" method="post" >
                    <?php if(is_array($shop)): $i = 0; $__LIST__ = $shop;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><div class="show-title">
                              <span class="shop-location">商家:<?php echo ($vs[user_name]); ?></span>
                          </div>
                    <?php if(!empty($info)): ?><div class="service-list mt10" id="normalList">
                            <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if($item['seller_id'] == $vs['seller_id']): ?><div class="item-box" id="item<?php echo ($item["cart_id"]); ?>">
                                    <?php if(($item['stock'] == 0 && $item['online_stock'] == 0) || $item['is_on_sale'] == 0): ?><div class="chk td-chk fl">失效</div>
                                    <?php else: ?>
                                    <div class="chk td-chk fl"><div class="td-inner"><input type="checkbox" class="chkBox" name="cartIds[]" id="chkBox<?php echo ($item["cart_id"]); ?>" value="<?php echo ($item["cart_id"]); ?>" /><label isNormal="1" class="chk-box"></label></div></div><?php endif; ?>
                                    <div class="service td-service fl"><div class="td-inner"><a target="_blank" href="<?php echo U('Goods/goods',array('id'=>$item['real_goods_id']));?>"><?php echo ($item["goods_title"]); ?>(<?php echo ($item["store_name"]); ?>)</a></div></div>
                                    <div class="price td-price fl"><div class="td-inner goods-price">¥<?php echo ($item["goods_price"]); ?></div></div>
                                    <div class="amount td-amount fl">
                                        <div class="td-inner">
                                            <div class="amount-box">
                                                <a class="subtract-icon" href="javascript:;" onclick="updateNumAjaxUrl($(this),<?php echo ($item["goods_id"]); ?>,<?php echo ($item["cart_id"]); ?>)">-</a>
                                                <input type="text" name="itemAmount" disabled="disabled" class="item-amount" value="<?php echo ($item["counts"]); ?>" onKeyUp="updateNumAjaxUrl($(this),<?php echo ($item["goods_id"]); ?>)" />
                                                <a class="add-icon" href="javascript:;" onclick="updateNumAjaxUrl($(this),<?php echo ($item["goods_id"]); ?>,<?php echo ($item["cart_id"]); ?>)">+</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pay-amount td-pay-amount fl"><div class="td-inner"><span class="amount-money">¥<?php echo number_format(floatval($item['goods_price']*$item['counts']),2,'.','');?></span></div></div>
                                    <div class="operate td-operate fl">
                                        <div class="td-inner">
                                            <a href="javascript:;" data-action="move" goodsid="<?php echo ($item["goods_id"]); ?>" cartId="<?php echo ($item["cart_id"]); ?>">移入收藏夹</a>
                                            <a href="javascript:;" data-action="del" goodsid="<?php echo ($item["cart_id"]); ?>" cartId="<?php echo ($item["cart_id"]); ?>">删除</a>
                                        </div>
                                    </div>
                                </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </div><?php endif; ?>
                        <div class="line20"></div><?php endforeach; endif; else: echo "" ;endif; ?>
                </form>
            </div>
        </div>
        <div class="cart-footer-tools">
            <div class="smart-float-box">
                <div class="left-tools fl">
                    <label class="chk-box allChk" onclick="checkAllCart(this,0)">全选</label>
                    <a data-action="del" class="operateBtn" href="javascript:;">删除</a>
                    <a data-action="move" class="operateBtn" href="javascript:;">移入收藏夹</a>
                </div>
                <div class="right-tools fr">
                    <div class="selected-tips fl">
                        已选商品<span class="total-num">0</span>类
                    </div>
                    <div class="total-price-box fr">
                        合计：<span class="total-price">¥0.00</span>
                        <a href="javascript:;" class="submit-btn">结算</a>
                    </div>
                </div>
            </div>
        </div>
    <!--<?php endif; ?>-->
</div>
<div class="line40"></div>
<script type="text/javascript">
    /*-----------------购物车js End-----------------*/
    $(function(){
        //初始化所有checkbox为false状态
        //$("input.chkBox").prop("checked",false);
    })
    calcCurrentPrice();   //执行价格计算
    var ajaxUrlOne = "<?php echo U('Shop/Cart/ajaxUpdateGoodsNum');?>";
    //单选操作
    $(".service-list").find(".chk-box").click(function(){
        var isNormal = $(this).attr("isNormal");
        singleCheckBox(this,isNormal);
    });
    //移入收藏夹和删除操作
    $(".td-operate a").click(function(){
        var goodsid = $(this).attr("goodsid");
        var cartId = $(this).attr("cartId");
        var action = $(this).attr("data-action");
        var is_dialog = true;
        var ajaxUrl1="<?php echo U('Shop/Cart/delCartGoods');?>";
        if(action=='move'){
            //把当前商品移入收藏夹
            //商品移入ajax
            removeCartToFavorites(cartId,"<?php echo U('Shop/Cart/addFavorites');?>","<?php echo U('Shop/Cart/delCartGoods');?>","<?php echo U('Home/Login/login');?>","#item",ajaxUrl1);
        }else{
            delCartGoodsa(ajaxUrl1,goodsid);
            dialogContent('删除成功!');
        }
    });
    //dialog插件弹出层提示
    function dialogContent(content){
        //弹出框
        var d = dialog({content:content}).show();
        window.setTimeout(function(){
            //删除弹出框
            d.close().remove()},1000);
    }

    //底部工具条按钮操作
    $(".operateBtn").click(function(){
        var action = $(this).attr("data-action");
        var is_dialog = true;
        //获取所有选中的checbox，组合goodsids组合
        var cartids = "";
        $("input[type=checkbox]:checked").each(function(){
            cartids += $(this).val()+",";
        });
        if(cartids!=""){
            cartids = cartids.substring(0, cartids.length-1);
            //alert(cartids);
            if(action=='move'){
                var ajaxUrl1="<?php echo U('Shop/Cart/delCartGoods');?>";
                //把当前商品移入收藏夹
                    removeCartToFavorites(cartids,"<?php echo U('Shop/Cart/addFavorites');?>","<?php echo U('Shop/Cart/delCartGoods');?>","<?php echo U('Home/Login/login');?>","#item",ajaxUrl1);
            }else{
                //ajax删除商品并移除当前行的html
                var ajaxUrl="<?php echo U('Shop/Cart/delCartGoods');?>";
                delCartGoodsa(ajaxUrl,cartids);
                dialogContent('删除成功!');
            }
        }else{
            //未选择任何checkbox，dialog弹出提示选择
            var d = dialog({content:"亲，您还没选择任何一种商品呢！"}).show();
            window.setInterval(function(){d.close().remove()},1000);
        }
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