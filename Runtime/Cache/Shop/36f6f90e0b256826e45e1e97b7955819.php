<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>3好连锁店商城首页</title>
    <meta name="keywords" content="<?php echo ($web_keywords); ?>"/>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/public.css">
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/user.css">
    <link type="text/css" rel="stylesheet" href="/somegood/Public/scripts/jquery/jquery.selectlist.css" >
    <link type="text/css" rel="stylesheet" href="/somegood/Public/scripts/artdialog/ui-dialog.css">
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/shop/js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/js/share-code.js"></script>
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

    <div class="bg-box">
        <div class="w1200">
            <div class="h-middle clearfix">
                <a href="<?php echo U('Shop/Index/index');?>" class="fl"><img src="/somegood/Public/statics/shop/images/logo.png" alt="logo" class="logo"/></a>
                <div class="user-nav fr">
                    <ul>
                        <li><a href="<?php echo U('Shop/Index/index');?>">首页</a></li>
                        <li><a href="javascript:;">今日上新</a></li>
                        <li><a href="javascript:;">全网秒杀</a></li>
                        <li><a href="javascript:;">品牌投放</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="user-center">
	<div class="w1200">
		<div class="left-menu-list fl" id="leftMenu">
							<ul>
					<li class="title">个人中心</li>
					<li><a <?php if($mnav == 1): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/setting',array('action'=>'info'));?>">个人资料</a></li>
					<li><a <?php if($mnav == 2): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/setting',array('action'=>'edit'));?>">资料修改</a></li>
				</ul>
				<ul>
					<li class="title">我的订单</li>
					<li><a <?php if($mnav == 17): ?>class="cur"<?php endif; ?> href="<?php echo U('userOrder/orderlist');?>">订单管理</a></li>
				</ul>
				<ul>
					<li class="title">我的资产</li>
					<?php if($info['is_salesman'] == 1): ?><li><a <?php if($mnav == 12): ?>class="cur"<?php endif; ?> href="<?php echo U('Account/commission');?>">提成明细</a></li>
					<?php else: ?>
					<li><a <?php if($mnav == 13): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/amount');?>">资金查看</a></li>
					<li><a <?php if($mnav == 14): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/coupon',array('pageType'=>'can_use'));?>">优惠券</a></li>
					<li><a <?php if($mnav == 12): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/commission');?>">提成明细</a></li><?php endif; ?>
				</ul>
				<ul>
					<li class="title">我的收藏</li>
					<li><a <?php if($mnav == 16): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/favorites');?>">我的收藏夹</a></li>
				</ul>
		</div>
		<div class="right-show fr">
			<div class="my-info clearfix">
				<div class="info" style=" position:relative; z-index:1;">
					<div class="left fl">
						<img src="/somegood/Public/<?php if(empty($user["avatar"])): ?>statics/mobile/images/default_img.png<?php else: ?>uploads/<?php echo ($user["avatar"]); endif; ?>" width="150" height="150" />
						<?php if(($auth) == "2"): ?><img src="/somegood/Public/statics/shop/images/already_certified.png" alt="已认证" class="img-status yes">
							<?php else: ?>
							<img src="/somegood/Public/statics/shop/images/pending_audit.png" alt="待审核" class="img-status no"><?php endif; ?>
					</div>
					<div class="center fl">
						<h3 class="user-name"><?php echo ($_SESSION['mobile']['username']); ?></h3>
						<div class="info-div"><a href="<?php echo U('Shop/Users/avatar');?>">设置头像</a></div>
						<div class="info-div"><a href="<?php echo U('Shop/Users/password');?>">修改密码</a></div>
					</div>
					<div class="right fl">
						<ul class="ul-1 fl">
							<li><a href="javascript:;"><i class="icon-wait-pay"></i><span>待付款<em id="waitPay">2</em></span></a></li>
							<li><a href="javascript:;"><i class="icon-wait-deliver"></i><span>待发货<em id="waitDeliver">2</em></span></a></li>
							<li><a href="javascript:;"><i class="icon-wait-receipt"></i><span>待收货<em id="waitReceipt">2</em></span></a></li>
							<li><a href="javascript:;"><i class="icon-complete"></i><span>已完成</span></a></li>
						</ul>
						<ul class="ul-2 fl">
							<li><a href="javascript:;"><span class="bold">余额</span><span class="text" id="balance">2</span></a></li>
							<li><a href="javascript:;"><span class="bold">优惠券</span><span class="text" id="coupon">2</span></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<div class="title"><h3 class="fl">我的订单</h3><a href="<?php echo U('UserOrder/index');?>" class="fr">查看全部订单</a></div>
			
			<table class="shop-order-table">
				<thead>
				<tr>
					<th>订单详情</th>
					<th>收货人</th>
					<th>金额</th>
					<th>订单状态</th>
					<th>操作</th>
				</tr>
				</thead>
				<!--订单-->
				<tbody>
					<tr class="sep-row">
						<td colspan="5"></td>
					</tr>
					<tr class="tr-th">
						<td colspan="5">
							<span class="gap"></span>
							<span class="dealtime" title="2017-11-19 12:33:22">2017-11-19 12:33:22</span>
							<span class="number">
								订单号：<a href="javascript:;">69058717693</a>
							</span>
						</td>
					</tr>
					<tr class="tr-bd">
						<td>
							<div class="goods-item p-3235700">
								<div class="p-img"><a href="javascript:;"><img src="" alt="商品图" /></a></div>
								<div class="p-msg">
									<div class="p-name">fsdfsdfsdfsdf</div>
								</div>
								<div class="goods-number">X1</div>
							</div>
						</td>
						<td rowspan="1">
							<div class="consignee tooltip">
								<span class="txt">老蒋</span>
							</div>
						</td>
						<td rowspan="1">
							<div class="amount">
								<span>总额 ¥31.90</span>
							</div>
						</td>
						<td rowspan="1">
							<div class="status">
								<!--订单的三个状态-->
								<!--1-->
								<span class="order-status ftx-01">待付款</span>
								<br/>
								<!--2-->
								<span class="order-status ftx-02">请上门自提</span>
								<br/>
								<div class="tooltip">
									<i class="auto-icon"></i>
									跟踪
									<i class="circle-icon"></i>
									<div class="prompt-01">
										<div class="pc">
											<div class="p-tit">普通快递</div>
											<div class="logistics-cont">
												<ul>
													<li class="first">
														<i class="node-icon"></i>
														<a href="">【长沙市】 您的订单在京东【长沙师范学院南校区京东派】上架完成，请上门自提</a>
														<br>您的订单预计2017-11-20送达您手中
														<div class="ftx-13">2017-11-20 08:47:31</div>
													</li>
												</ul>
											</div>
										</div>
										<div class="p-arrow"></div>
									</div>
								</div>
								<!--3-->
								<span class="order-status ftx-03">已完成</span>
								<br/>
								<a href="javascript:;">订单详情</a>
							</div>
						</td>
						<td rowspan="1">
							<a href="javascript:;" class="a-link order-cancel">取消订单</a>
						</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="clear"></div>


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