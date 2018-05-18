<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo ($web_title); ?></title>
    <meta name="keywords" content="<?php echo C('MARKET_SEO_KEYWORD');?>" />
    <meta name="description" content="<?php echo C('MARKET_SEO_DESCRIPTION');?>" />
    <link rel="stylesheet" href="/somegood/Public/statics/shop/css/cart.css" type="text/css">
    <link rel="stylesheet" href="/somegood/Public/scripts/artdialog/ui-dialog.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/shop/js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/shop/js/totop.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
</head>
<body>
<div class="w1200 container mt30">
    <?php if(($info1 == null) and ($info == null)): ?><div class="cart-msg-box">
            <div class="cart-msg-icon fl"></div>
            <div class="cart-msg-tips fr">
                <?php if($_SESSION['id']> 0): ?><p>服务车空空的哦~，赶快去选择您想的要的服务吧~ </p>
                    <p><a href="<?php echo U('Index/index');?>">去选择服务&gt;&gt;</a></p>
                    <?php else: ?>
                    <p>服务车内暂时没有任何服务，登录后将显示您之前加入的服务！</p>
                    <p><a class="tips-btn" href="<?php echo C('HOME_DOMAIN'); echo U('Home/Login/login');?>">登录</a><a href="<?php echo U('Index/index');?>">去选择服务&gt;&gt;</a></p><?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="cart-title">
            <h3 class="fl">全部服务</h3>
            <div class="right-tools-box fr">
                已选服务 <span class="total-price">¥0.00</span><a href="javascript:;" class="submit-btn">结算</a>
            </div>
        </div>
        <div class="cart-table">
            <div class="th">
                <div class="chk th-chk fl">
                    <div class="th-inner"><label class="chk-box allChk" onclick="checkAllCart(this,0);">全选</label></div>
                </div>
                <div class="service th-service fl"><div class="th-inner">服务信息</div></div>
                <div class="memo th-memo fl"><div class="th-inner">备注</div></div>
                <div class="price th-price fl"><div class="th-inner">价格</div></div>
                <div class="amount th-amount fl"><div class="th-inner">数量</div></div>
                <div class="pay-method th-pay-method fl"><div class="th-inner">付款方式</div></div>
                <div class="pay-amount th-pay-amount fl"><div class="th-inner">金额</div></div>
                <div class="operate th-operate fl"><div class="th-inner">操作</div></div>
            </div>
            <div class="cart-list-box">
                <form name="cartForm" id="cartForm" action="<?php echo U('Order/settlement');?>" method="post" >
                    <?php if(!empty($info)): ?><div class="service-list mt20" id="normalList">
                            <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><div class="item-box" id="item<?php echo ($item["cart_id"]); ?>">
                                    <div class="chk td-chk fl"><div class="td-inner"><input type="checkbox" class="chkBox" name="normalCart[]" id="chkBox<?php echo ($item["cart_id"]); ?>" value="<?php echo ($item["cart_id"]); ?>" /><label isNormal="1" class="chk-box"></label></div></div>
                                    <div class="service td-service fl"><div class="td-inner"><a target="_blank" href="<?php echo U('Goods/goods',array('id'=>$item['goods_id']));?>"><?php echo ($item["goods_title"]); ?></a></div></div>
                                    <div class="memo td-memo fl"><div class="td-inner">&nbsp;</div></div>
                                    <div class="price td-price fl"><div class="td-inner goods-price">¥<?php echo ($item["goods_price"]); ?></div></div>
                                    <div class="amount td-amount fl">
                                        <div class="td-inner">
                                            <div class="amount-box">
                                                <a class="subtract-icon" href="javascript:;" onclick="calcAmount(this,'subtract',false,updateNumAjaxUrl,'<?php echo ($item["cart_id"]); ?>');">-</a>
                                                <input type="text" name="itemAmount" class="item-amount" value="<?php echo ($item["counts"]); ?>" onKeyUp="keyUpAmount(this,false,updateNumAjaxUrl,'<?php echo ($item["cart_id"]); ?>');" />
                                                <a class="add-icon" href="javascript:;" onclick="calcAmount(this,'add',false,updateNumAjaxUrl,'<?php echo ($item["cart_id"]); ?>');">+</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pay-method td-pay-method fl"><div class="td-inner">-</div></div>
                                    <div class="pay-amount td-pay-amount fl"><div class="td-inner"><span class="amount-money">¥<?php echo number_format(floatval($item['goods_price']*$item['counts']),2,'.','');?></span></div></div>
                                    <div class="operate td-operate fl">
                                        <div class="td-inner">
                                            <a href="javascript:;" data-action="move" goodsid="<?php echo ($item["cart_id"]); ?>">移入收藏夹</a>
                                            <a href="javascript:;" data-action="del" goodsid="<?php echo ($item["cart_id"]); ?>">删除</a>
                                        </div>
                                    </div>
                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div><?php endif; ?>
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
                        已选服务<span class="total-num">0</span>类
                    </div>
                    <div class="total-price-box fr">
                        合计：<span class="total-price">¥0.00</span>
                        <a href="javascript:;" class="submit-btn">结算</a>
                    </div>
                </div>
            </div>
        </div><?php endif; ?>
</div>
<div class="line40"></div>
</body>
<script type="text/javascript" src="__TMPL__js/cart.js"></script>
<script type="text/javascript">
    /*-----------------购物车js End-----------------*/
    $(function(){
        //初始化所有checkbox为false状态
        //$("input.chkBox").prop("checked",false);
    })
    $(".service-list").find(".chk-box").click(function(){
        var isNormal = $(this).attr("isNormal");
        singleCheckBox(this,isNormal);
    });
    //移入收藏夹和删除操作
    $(".td-operate a").click(function(){
        var goodsid = $(this).attr("goodsid");
        var action = $(this).attr("data-action");
        var is_dialog = true;
        if(action=='move'){
            //把当前商品移入收藏夹
            //商品移入ajax
            removeCartToFavorites(goodsid,"<?php echo U('Cart/addFavorites');?>","<?php echo U('Cart/delCarGoods');?>","<?php echo U('Home/Login/login');?>","#item");
        }else{
            //ajax删除商品并移除当前行的html
            delCart(goodsid, "<?php echo U('Cart/delCarGoods');?>", is_dialog, "#item");
        }
    });
    //底部工具条按钮操作
    $(".operateBtn").click(function(){
        var action = $(this).attr("data-action");
        var is_dialog = true;
        //获取所有选中的checbox，组合goodsids组合
        var goodsids = "";
        $("input[type=checkbox]:checked").each(function(){
            goodsids += $(this).val()+",";
        });
        if(goodsids!=""){
            goodsids = goodsids.substring(0, goodsids.length-1);
            if(action=='move'){
                //把当前商品移入收藏夹
                //商品移入ajax
                removeCartToFavorites(goodsids,"<?php echo U('Cart/addFavorites');?>","<?php echo U('Cart/delCarGoods');?>","<?php echo U('Home/Login/login');?>","#item");
            }else{
                //ajax删除商品并移除当前行的html
                delCart(goodsids,"<?php echo U('Cart/delCarGoods');?>", is_dialog, "#item");
            }
        }else{
            //未选择任何checkbox，dialog弹出提示选择
            var d = dialog({content:"亲，您还没选择任何一种服务呢！"}).show();
            window.setInterval(function(){d.close().remove()},1000);
        }
    })
</script>