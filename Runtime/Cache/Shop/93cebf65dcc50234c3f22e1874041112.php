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
<?php if(($isAjax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="ui-switchable-panel ui-switchable-panel-selected" id="address1" >
            <div class="consignee-item item-selected" data-address-id="<?php echo ($vo["address_id"]); ?>"><span><?php echo ($vo["consignee"]); ?></span><b></b></div>
            <div class="addr-detail">
                <span class="addr-name"><?php echo ($vo["consignee"]); ?></span>
                <span class="addr-info"><?php echo ($vo["iname"]); echo ($vo["oname"]); echo ($vo["rname"]); echo ($vo["address"]); ?></span>
                <span class="addr-tel"><?php echo ($vo["mobile"]); ?></span>
                <?php if($vo['is_default'] == 1): ?><span class="addr-default">默认地址</span><?php endif; ?>
            </div>
            <div class="op-btns">
                <?php if($vo['is_default'] == 0): ?><a href="javascript:;" class="ftx-05 setdefault-consignee">设为默认地址</a><?php endif; ?>
                <a href="javascript:;" class="ftx-05 edit-consignee" data-edit-addressid="<?php echo ($vo["address_id"]); ?>">编辑</a>
                <a href="javascript:;" class="ftx-05 del-consignee" onclick="delAddress(<?php echo ($vo["address_id"]); ?>,delajaxUrl,$(this))">删除</a>
            </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
        <input type="hidden" name="data[address_id]" value="<?php echo ($defaultId); ?>" id="selAddressId">
<?php else: ?>

<script type="text/javascript" src="/somegood/Public/statics/shop/js/address.js"></script>
<script type="text/javascript" src="/somegood/Public/statics/shop/js/settlement.js"></script>
<div class="order-confirm-box">
    <div class="w1200">
        <div class="confirm-title">
            <h2>填写并核对订单信息</h2>
        </div>
        <div class="checkout-steps">
            <div class="step-tit">
                <h3 class="fl">收货人信息</h3>
                <div class="extra-r fr">
                    <a href="javascript:;" class="add-address ftx-05">+新增收货地址</a>
                </div>
            </div>
            <form class="" action="<?php echo U('Shop/Order/submitOrder');?>" method="post">
            <div class="step-cont">
                <div class="consignee-content" id="consigneeAddr">
                    <div class="ui-scrollbar-wrap">
                        <div class="consignee-scrollbar" style="left:0; top:0; overflow: hidden;">
                        <div class="ui-scrollbar-main">
                            <div class="consignee-scroll">
                                <div class="consignee-cont">
                                    <ul id="consigneeList">
                                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="ui-switchable-panel <?php if($vo['is_default'] == 1): ?>ui-switchable-panel-selected<?php endif; ?>" id="address1" >
                                            <div class="consignee-item <?php if($vo['is_default'] == 1): ?>item-selected<?php endif; ?>" data-address-id="<?php echo ($vo["address_id"]); ?>"><span><?php echo ($vo["consignee"]); ?></span><b></b></div>
                                            <div class="addr-detail">
                                                <span class="addr-name"><?php echo ($vo["consignee"]); ?></span>
                                                <span class="addr-info"><?php echo ($vo["iname"]); echo ($vo["oname"]); echo ($vo["rname"]); echo ($vo["address"]); ?></span>
                                                <span class="addr-tel"><?php echo ($vo["mobile"]); ?></span>
                                                <span class="addr-default">默认地址</span>
                                            </div>
                                            <div class="op-btns">
                                                <?php if($vo['is_default'] == 0): ?><a href="javascript:;" class="ftx-05 setdefault-consignee">设为默认地址</a><?php endif; ?>
                                                <a href="javascript:;" class="ftx-05 edit-consignee" data-editid="<?php echo ($vo["address_id"]); ?>">编辑</a>
                                                <a href="javascript:;" class="ftx-05 del-consignee" onclick="delAddress(<?php echo ($vo["address_id"]); ?>,delajaxUrl,$(this))">删除</a>
                                            </div>
                                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                        <input type="hidden" name="data[address_id]" value="<?php echo ($defaultId); ?>" id="selAddressId">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="addr-switch switch-on">
                    <span>更多地址</span>
                    <b></b>
                </div>
                <div class="addr-switch switch-off hide">
                    <span>收起地址</span>
                    <b></b>
                </div>
            </div>
            <div class="hr"></div>
            <div class="step-tit">
                <h3 class="fl">支付方式</h3>
            </div>
            <div class="step-cont">
                <div class="payment-list clearfix" >
                    <div class="list-cont">
                        <ul id="payment-list">
                            <li payType="0" >
                                <div class="payment-item">到店购买</div>
                            </li>
                            <li payType="1" class="cur">
                                <div class="payment-item">快递运输</div>
                            </li>
                        </ul>
                        <input type="hidden" id="payType" value="1" name="data[delivery_way]">
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="hr" id="zitihr"></div>
            <div class="step-tit">
                <h3 class="fl">送货清单</h3>
                <div class="extra-r fr">
                    <a href="<?php echo U('Shop/Cart/index');?>" class="back-to-cart ftx-05">返回修改购物车</a>
                </div>
            </div>
            <!--送货清单-->
            <div class="step-cont">
                <div class="shopping-lists" >
                    <?php if(is_array($sellerInfo)): $i = 0; $__LIST__ = $sellerInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><div class="shopping-list">
                        <!--商品列表-->
                        <div class="goods-list">
                            <div class="goods-tit">
                                <h4 class="vendor_name_h">商家：<?php echo ($vs['user_name']); ?></h4>
                            </div>
                            <?php if(is_array($goodslist)): $i = 0; $__LIST__ = $goodslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['seller_id'] == $vs['seller_id']): ?><div class="goods-items clearfix">
                                <div class="goods-item goods-item-extra">
                                    <div class="p-img"><a href="javascript:;"><img src="/somegood/Public/uploads/<?php echo ($vo['goods_thumb']); ?>" alt="商品图" /></a></div>
                                    <div class="goods-msg">
                                        <div class="goods-msg-gel">
                                            <div class="p-name"><a href="javascript:;"><?php echo ($vo['goods_title']); ?><br>-----店铺:<?php echo ($vo['store_name']); ?></a></div>
                                            <div class="p-price">
                                                <strong class="sh-price"><?php echo ($vo['goods_price']); ?></strong>
                                                <span class="p-num"><?php echo ($vo['counts']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                        <!--快递方式列表-->
                        <div class="dis-modes" data-sid="<?php echo ($vs['seller_id']); ?>"></div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>

            <div class="hr"></div>

            <!--优惠券-->

            <div class="step-tit">
                <h3 class="fl">优惠券</h3>
            </div>
            <?php if( $couponList['num'] == 0 ): ?><div class="no-coupon">
                  很遗憾您未有可使用的优惠券！
              </div>
              <?php else: ?>
            <div class="step-cont">
                <div class="coupon-wrap clearfix">
                    <ul>
                        <?php if(is_array($couponList)): $i = 0; $__LIST__ = $couponList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                            <div class="coupon-item coupon-item-new">
                                <div class="c-detail">
                                    <div class="c-msg c-dong">
                                        <div class="c-top-dong"></div>
                                        <div class="item-selected-cancel hide">取消勾选</div>
                                        <div class="c-price">
                                            <em>￥<?php echo ($vo[type_money]); ?></em>
                                        </div>
                                        <div class="c-limit">
                                    <span>
                                    满<?php echo ($vo[min_goods_amount]); ?>
                                    </span>
                                        </div>
                                        <div class="c-time c-time-dong">
                                            <span>有效期至<?php echo (date("Y.m.d",$vo['use_end_date'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="c-type c-type-dong">
                                        <span class="c-type-l">[平台券]</span>
                                        <span class="c-type-r" id="24996803677">[限品类]</span>
                                    </div>
                                </div>
                                <div class="c-info"></div>
                            </div>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <div class="clear"></div>
                </div>
                <input type="hidden" id="canUseCoupon" value="33">
                <input type="hidden" name="data[coupon_id]" value="0">
            </div><?php endif; ?>
            <div class="hr"></div>

            <!--购物币-->

            <div class="step-tit">
                <h3 class="fl">购物币</h3>
                <div class="bill-info fr">
                    <span class="fl">购物币使用规则：</span>
                    <span class="bill-desc"> 购物币每满1可以抵扣1元, 一个订单最多可以使用<?php echo (C("share_bill")); ?>元</span>
                </div>
            </div>
            <div class="step-cont clearfix">
                <div class="bill-item clearfix">
                    <div class="fl"><span class="left fl">购物币总额：</span><span class="bill all-bill fl" id="fwbTotal"><?php echo ($userShallBill); ?></span></div>
                    <div class="fr">
                        <span class="left fl">可使用：</span>
                        <input type="number"  id="billInput" name="billInput" class="bill use-bill fl" placeholder="可使用抵扣金额<?php if($userShallBill >= $shareBillMax): echo ($shareBillMax); else: echo ($userShallBill); endif; ?>元"/>
                        <input type="hidden" id="canUseBill" name="canUseBill" value="">
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <!-- <div class="hr"></div> -->

            <!--余额-->

            <!-- <div class="step-tit">
                <h3>余额</h3>
            </div>
            <div class="step-cont clearfix">
                <div class="bill-item clearfix">
                    <div class="fl"><span class="left fl">余额：</span><span class="bill all-ye fl" id="yeTotal">300</span></div>
                    <div class="fr">
                        <span class="left fl">可使用：</span>
                        <input type="number" id="yeInput" name="yeInput" class="bill use-ye fl" placeholder="可使用抵扣金额<?php if($userShallBill >= $shareBill): echo (C("share_bill")); else: echo ($userShallBill); endif; ?>元"/>
                        <input type="hidden" id="yeCanUse" name="yeCanUse" value="">
                    </div>
                </div>
            </div> -->
        </div>
        <div class="order-summary">
            <div class="statistic fr">
                <div class="list">
                    <span><em class="ftx-01"><?php echo ($couponInfo["num"]); ?></em> 件商品，总商品金额：</span>
                    <span class="price">￥<em id="GoodsMoney"><?php echo ($couponInfo["money"]); ?></em></span>
                </div>
                <div class="list">
                    <span>购物币抵扣：</span>
                    <em class="price" id="fwbDiKou">0.00</em>
                </div>
                <div class="list">
                    <span>优惠券抵扣：</span>
                    <em class="price" id="yhqDiKou">0.00</em>
                </div>
                <div class="list">
                    <span>余额抵扣：</span>
                    <em class="price" id="yeDiKou">0.00</em>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="trade-foot">
            <div class="trade-foot-detail-com">
                <div class="fc-price-info">
                    <span class="price-tit">应付总额：</span>
                    <span class="price-num" id="payTotal"></span>
                </div>
                <div class="fc-consignee-info">
                    <span class="address" id="sendAddr">寄送至： 广东 深圳市 龙华新区   深圳龙华新区民治街道上塘社区</span>
                    <span id="sendMobile">收货人：蒋建 135****1127</span>
                </div>
            </div>

            <div class="group">
                <div class="ui-ceilinglamp checkout-buttons">
                    <button type="submit" class="checkout-submit">提交订单<b></b></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="loading-box" style='width:200px; position:absolute; top: 50%; left: 50%; margin-left:-100px; display: none; '>
        <img src="/somegood/Public/statics/shop/images/profile_load.gif" alt="loading" />
</div>
<script>
    $(function () {
        window.clickCurrentUrl = "<?php echo U('Shop/Order/updateDefaultAddress');?>";
        window.delajaxUrl  =  "<?php echo U('Shop/Order/delOrderAddress');?>";
        refreshDeliveryWay("<?php echo ($defaultId); ?>","<?php echo U('Shop/Order/logistics');?>");
        /*编辑地址弹出框*/
        $("#consigneeList").on('click', '.edit-consignee', function(){
            var address_id =$(this).data("editid");
            var d = dialog({
                title:'编辑收货人信息',
                width:600,
                url:"<?php echo U('Shop/Order/editaddress');?>?address_id="+address_id,
            }).showModal();
        });
        /*新增收货地址*/
        $(document).on('click', '.add-address', function(){
            var d = dialog({
                title:'新增收货地址',
                id:'newAddress',
                width:600,
                url:"<?php echo U('Order/newaddress');?>"
            }).showModal();

        })
        /*订单计算*/
        var goods_total = parseFloat($("#GoodsMoney").html()).toFixed(2);//商品总金额
        var ye_total = parseFloat($("#yeTotal").html()).toFixed(2); //余额总额
        var fwb_total = parseFloat($("#fwbTotal").html()).toFixed(2); //服务币总额

        $("#payTotal").html(goods_total);

        /*********服务币********/
        fwb_total = 0;//初始化服务币总额
        if($(".all-bill").text()!=""&&$(".all-bill").text()!="0"){
            fwb_total = Number(parseFloat($(".all-bill").text()).toFixed(2));//用户可用的虚拟币
        }
        //判断是否超过最大的可用虚拟币
        if (fwb_total >= <?php echo (C("share_bill")); ?>) {
            fwb_total = parseFloat(<?php echo (C("share_bill")); ?>);
        }
        /*输入购物币验证是否可用*/
        $("#billInput").blur(function(){
            if($(this).val()!=""){
                var billVal = Number(parseFloat($(this).val()).toFixed(2));
                if( billVal<0 || billVal>fwb_total){
                    $(this).val("0");
                    var d = dialog({
                        title:'温馨提示',
                        content:'亲,您最多能使用'+<?php if($userShallBill >= $shareBillMax): echo ($shareBillMax); else: echo ($userShallBill); endif; ?>+'个金币抵扣!'
                    }).showModal();
                }else{
                    $(this).val(billVal);
                    $("#fwbDiKou").html(parseFloat(billVal).toFixed(2));
                    var fwb_dk = $("#fwbDiKou").html();
                    goods_total-=fwb_dk;
                    $("#payTotal").html(parseFloat(goods_total).toFixed(2));
                }
            }
        })
        /*********余额********/
        ye_total = 0;//初始化余额总额
        if($(".all-ye").text()!=""&&$(".all-ye").text()!="0"){
            ye_total = Number(parseFloat($(".all-ye").text()).toFixed(2));//用户可用的虚拟币
        }
        /*输入购物币*/
        $("#yeInput").blur(function(){
            if($(this).val()!=""){
                var yeVal = Number(parseFloat($(this).val()).toFixed(2));
                if( yeVal<0 || yeVal>ye_total ){
                    $(this).val("0");
                    var d = dialog({
                        title:'温馨提示',
                        content:'亲,您输入的余额不足抵扣!'
                    }).showModal();
                }else{
                    $(this).val(yeVal);
                    $("#yeDiKou").html(parseFloat(yeVal).toFixed(2));
                    var ye_dk = $("#yeDiKou").html();
                    goods_total-=ye_dk;
                    $("#payTotal").html(parseFloat(goods_total).toFixed(2));
                }
            }
        })
    });
    /*删除收货地址
    * @Param            addressId                obj                  收货地址id
    * @Param            ajax_url                  str                    ajax地址
    * @Param            fh                  		obj                    点击的对象
    */
    //删除收货地址
function delAddress(address_id,ajax_url,fh) {
          $.ajax({
              data:{
                  address_id:address_id
              },
              type: 'POST',
              url: ajax_url,
              dataType: 'json',
              success: function (data) {
                  if (data.status == 1) {
                      fh.parent().parent().remove();
                  }else {
                    alert(data.info);
                  }
              }
          })
}
    payTypeSel();   //支付方式选择
    selAddressId("<?php echo U('Shop/Order/logistics');?>");   //收货地址id选择

    //删除dropload加载的iframe与遮罩层
    function delDroploadDiv() {
        $(".ui-popup.ui-popup-modal.ui-popup-show.ui-popup-focus").next().hide();
        $(".ui-popup.ui-popup-modal.ui-popup-show.ui-popup-focus").hide();
    }

    //点击一次第一个
    function clickFirstButton(){
        $(".consignee-item").eq(0).removeClass('item-selected');
        $(".consignee-item").eq(0).click();
        $(".consignee-item").eq(0).addClass('item-selected');
    }


    //局部刷新收货地址div块
    function refreshReceipt() {
        $("#consigneeList").children().remove();  //移除收货地址列表
        //ajax获取新的列表
        $.ajax({
            type: 'POST',
            url: "<?php echo U('Shop/Order/receipt');?>",
            dataType:"html",
            success: function (data) {
                    $("#consigneeList").append(data);
            }
        })
    }

</script><?php endif; ?>

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