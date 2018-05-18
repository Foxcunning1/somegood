<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php echo ($web_title); ?></title>
	<meta name="keywords" content="<?php echo C('MARKET_SEO_KEYWORD');?>" />
	<meta name="description" content="<?php echo C('MARKET_SEO_DESCRIPTION');?>" />
	<link rel="stylesheet" href="/somegood/Public/statics/shop/css/cart.css" type="text/css">
	<link rel="stylesheet" href="/somegood/Public/statics/shop/css/public.css" type="text/css">
	<link rel="stylesheet" href="/somegood/Public/scripts/artdialog/ui-dialog.css" type="text/css">
	<script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/shop/js/common.js"></script>
	<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/shop/js/totop.js"></script>
	<script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
</head>
<body>

<div class="w1200 container mt30">
	<div class="cart-msg-box">
		<div class="cart-msg-icon success-msg-icon fl"></div>
		<div class="cart-msg-tips fr">
			<h3>订单提交成功！</h3>
			<p>系统将在<span class="time-box" id="time-box">3</span>秒后跳转至订单中心！未跳转&nbsp;<a id="href" href="<?php echo C('SHOP_DOMAIN'); echo U('Shop/UserOrder/index');?>">点击这里&gt;&gt;</a></p>
		</div>
	</div>
</div>
<div class="line40"></div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('time-box'),href = document.getElementById('href').href;
var interval = setInterval(function(){
    var time = --wait.innerHTML;
    if(time == 0) {
        location.href = href;
        clearInterval(interval);
    };
}, 1000);
})();
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
<script>
    $(function () {
        var modifyPrice = $('#modify_price').val();
        var totalMoney = "<?php echo ($total_money); ?>";
        if(modifyPrice == 1 || totalMoney == 0){
            $("#submitBtn").removeAttr('disabled');
        }
    });
	/*支付方式选择*/
    $(".step-cont").find("a.pay").click(function(){
        $(this).addClass("cur").siblings().removeClass("cur");
        var payType = $(this).attr('id');
        $('#payType').val(payType);
        $("#submitBtn").removeAttr('disabled');
    });
    $("#edit").click(function(){
        alert(1);
        $("ticket-show").show();
        // top.dialog({
        //     title:'发票信息',
        //     content: ,
        //     width:600
        // }).showModal();
    });

	/*订单计算*/
    var order_total = (parseFloat($("#total_1").html())+parseFloat($("#total_2").html())).toFixed(2);//订单总额
    var yhq = parseFloat($("#money_yhq").html()).toFixed(2); //优惠券
    var fwb = parseFloat($("#money_fwb").html()).toFixed(2); //服务金币
    var ye = parseFloat($("#can_use_ye").val()).toFixed(2); //余额

    var yf_total = order_total-fwb; //应付

    //订单总额
    $(".order_total").html(order_total);
    //服务金币抵扣总额
    $("#result_fwb").html(fwb);
    //优惠券抵扣总额
    //$("#result_yhq").html(yhq);
    //应付总额
    $("#pay_total").html(yf_total);
    //结算余额抵扣初始化
    $("#result_ye").html(0);
    //可抵扣余额初始化
    $("#money_ye").html(ye);

	/*输入可抵扣的余额，并判断*/
    $("#can_use_ye").blur(function(){
        var value = parseFloat($(this).val()).toFixed(2);//获取输入的值
        ye = value;
        var ye_total = parseFloat($("#ye_total").html()).toFixed(2); //总余额
        var can_use_ye = parseFloat($("#can_use_ye").val()).toFixed(2); //可用余额
		/*判断余额不足*/
        if(ye_total >= can_use_ye){
            $(".error").hide();
            $("#money_ye").html(can_use_ye); //可抵扣为输入的值
            $("#result_ye").html(can_use_ye); //结算时可抵扣为输入的值
            yf_total = order_total-yhq-fwb-ye; //计算应付总额
            $("#pay_total").html(yf_total);
        }else{
            $(".error").show();//错误提示给用户
            $("#money_ye").html(0); //输入错误余额抵用值为零
            $("#result_ye").html(0); //结算时可抵扣为0
            yf_total = order_total-yhq-fwb; //计算应付总额
            $("#pay_total").html(yf_total);
        }

    })
    function checkForm() {
        var payType = $('#payType').val();
        if(payType == '' ){
            return false;
        }else{
            return true;
        }
    }
</script>
</body>
</html>