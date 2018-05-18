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
        <div class="change-city fl"><span>深圳</span><span>[切换城市]</span>
            <div class="city-list-box">
                <ul>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                </ul>
            </div>
        </div>
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


<div id="vipCenterContent" class="user-center">
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
                <?php if(($info["status"]) <= "3"): ?><div class="step-box mini">
                        <ul>
                            <!--下单-->
                            <li class="first done">
                                <div class="progress">
                                    <span class="text">已提交</span>
                                </div>
                                <div class="info hide">
                                    <?php echo (date('Y-m-d H:i:s',$info["add_time"])); ?>
                                </div>
                            </li>
                            <!--/下单-->
                            <!--待付款-->

                            <!--/待付款-->

                            <!--已付款-->

                            <li <?php if($info['status'] > 1): ?>class="done"<?php endif; ?>>
                                <div class="progress">
                                    <span class="text"><?php if($info['status'] == 0): ?><b>待付款</b><?php else: ?><b>已付款</b><?php endif; ?></span>
                                </div>
                                <div class="info hide">
                                    <?php echo (date('Y-m-d H:i:s',$info["pay_time"])); ?>
                                </div>
                            </li>
                            <!--/已付款-->
                            <li <?php if($info['status'] > 2): ?>class="done"<?php endif; ?>>
                                <div class="progress">
                                    <span class="text"><?php if($info['status'] > 1): ?><b>已发货</b><?php else: ?><b>等待发货</b><?php endif; ?></span>
                                </div>
                                <div class="info hide">
                                    <?php echo (date('Y-m-d H:i:s',$info["complete_time"])); ?>
                                </div>
                            </li>
                            <!--完成-->
                            <li class="last <?php if($info['status'] == 3 or $info['status'] == 6): ?>done<?php endif; ?>">
                                <div class="progress">
                                    <span class="text"><?php if($info['status'] == 3 or $info['status'] == 6): ?><b>已完成</b><?php else: ?><b>等待完成</b><?php endif; ?></span>
                                </div>
                                <div class="info hide">
                                    <?php echo (date('Y-m-d H:i:s',$info["complete_time"])); ?>
                                </div>
                            </li>
                            <!--完成-->
                        </ul>
                    </div>
                    <div class="line20"></div><?php endif; ?>

                <div class="form-box accept-box">
                    <dl class="head">
                        <dd>订单信息</dd>
                    </dl>
                    <dl>
                        <dt>订单号：</dt>
                        <dd> <?php echo ($info["order_sn"]); ?></dd>
                    </dl>
                    <dl>
                        <dt>订单状态：</dt>
                        <dd>
                                <?php $_result=C('FRONT_ORDER_STATUS_LIST');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if($info["status"] == $key): echo ($item); endif; endforeach; endif; else: echo "" ;endif; ?>
                        </dd>
                    </dl>
                    <dl>
                        <dt>价格：</dt>
                        <dd>￥  <?php echo ($info["price"]); ?></dd>
                    </dl>
                    <dl>
                        <dd>
                            <button class="btn backBtn" onclick="history.back()">返回</button>
                            <?php if($info["status"] == 0): ?><button class="btn backBtn" onclick="ExecPostBack('<?php echo ($info["id"]); ?>')">取消</button><?php endif; ?>
                        </dd>
                    </dl>
                    <dl class="head">
                        <dt>三好连锁</dt>
                    </dl>
                    <thead>
                <tr>
                    <th class="grap"></th>
                    <th>商品图</th>
                    <th>商品标题</th>
                    <th>商品数量</th>
                    <th>三好连锁价</th>
                </tr>
                </thead>
                <br>
                <?php if(is_array($info[info])): $i = 0; $__LIST__ = $info[info];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tbody>
                    <tr>
                        <td class="grap"></td>
                        <td ><img src="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>" alt=""></td>
                        <td ><?php echo ($item["goods_title"]); ?></td>
                        <td ><?php echo ($item["counts"]); ?></td>
                        <td ><?php echo ($item["price"]); ?></td>
                    </tr>
                </tbody><?php endforeach; endif; else: echo "" ;endif; ?>
                <div class="line20"></div>
            </div>
        </div>
    </div>
</div>
<input id="turl" value="<?php echo U('UserOrder/index');?>" type="hidden"><!--存在跳转的URL值-->
<div class="clear"></div>
<script type="text/javascript">
    $.ajax({
        type: 'get',
        url:"<?php echo U('Users/menu');?>?mnav=<?php echo ($mnav); ?>&r="+Math.random(),
        dataType: 'html',
        success:function(data){
            $("#leftMenu").html(data);
        }
    });
    function uploadCertificate() {
        var orderSn = <?php echo ($info["order_sn"]); ?>;
        var current = "<?php echo ($info["current"]); ?>";
        top.dialog({
            width: 800,
            height: 200,
            title: '确认信息',
            cancelValue: '取消',
            lock: true,
            url: "<?php echo U('UserOrder/upload');?>?"+"order_sn="+orderSn+'&current='+current
        }).showModal();
    }
    //删除信息
    function ExecPostBack(checkValue) {
        ExecDelete("<?php echo U('UserOrder/cancelOrder');?>", checkValue, '#turl','取消记录后不可恢复，您确定吗？');
    }
    $(function () {
        $('.step-box ul li').each(function (index,data) {
            if(<?php echo ($info["status"]); ?> >= index){
                switch (index) {
                    case 1:
                        $(data).find('.text').text('已审核');
                        break;
                    case 2:
                        $(data).find('.text').text('已付款');
                        break;
                    case 3:
                        $(data).find('.text').text('已完成');
                        break;
                    default:
                        break;
                }
                $(data).addClass('done');
                $(data).children('.info').removeClass('hide');
            }
        })
    });
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