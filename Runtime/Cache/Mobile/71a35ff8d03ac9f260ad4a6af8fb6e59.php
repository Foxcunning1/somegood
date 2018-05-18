<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>订单结算</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/cart.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/TouchSlide/TouchSlide.1.1.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="javascript:void(0);" class="icons icon-back"></a>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <h2 class="title">订单结算</h2>
    <!--标题-->
</div>
<form method="post" class="order-form" id="orderForm" style="padding-top: 0.86rem;">

    <div class="order-wrapper" <?php if($delivery_way == '0' ): ?>style="display:none;"<?php endif; ?>>
        <div class="my-address" >
            <a href="<?php echo U('UserOrder/receipt');?>" class="my-address-a">
                <div class="name">
                    <div class="fl"><span><?php echo ($userDefaultAddress[consignee]); ?></span></div>
                    <div class="fl"><span><?php echo ($userDefaultAddress[mobile]); ?></span></div>
                    <?php if($userDefaultAddress['is_default'] == 1): ?><div class="fl">默认</div><?php endif; ?>
                </div>
                <div class="address">
                    <i class="icons icon-more"></i>
                    <i class="icons icon-location fl"></i>
                    <p class="address-where"><?php echo ($userDefaultAddress["iname"]); echo ($userDefaultAddress["oname"]); echo ($userDefaultAddress["rname"]); echo ($userDefaultAddress["address"]); ?></p>
                </div>
            </a>
            <span class="s1-borderB"></span>
        </div>
    </div>
    <div class="order-wrapper">
        <div class="items">
            <div class="item-l">
                <div class="list-title">订单列表</div>
            </div>
            <div class="item-r" style="border-bottom: 1px solid #f1f1f1">
                <div class="item-r fl">
                    <ul class="order-list-box">
                        <?php if(is_array($orderInfo)): $i = 0; $__LIST__ = $orderInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if(($vo['store_id'] != 0 && $vo['stock'] == 0 && $vo['online_stock'] == 0) || $vo['store_id'] != 0 && $vo['is_on_sale'] == 0 || $vo['store_id'] == 0 && $vo['online_stock'] == 0): ?>class="invalid"<?php endif; ?>>
                              <a href="javascript:void(0);"><img class="img-length" src="/somegood/Public/uploads/<?php echo ($vo[goods_thumb]); ?>_c200x200" alt="" /><span>×<?php echo ($vo[counts]); ?></span></a>
                              <?php if(($vo['store_id'] != 0 && $vo['stock'] == 0 && $vo['online_stock'] == 0) || $vo['store_id'] != 0 && $vo['is_on_sale'] == 0 || $vo['store_id'] == 0 && $vo['online_stock'] == 0): ?><div class="invalid-box"><p>无货</p><p>Sold Out</p></div><?php endif; ?>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
                <div class="list-right fr">
                    <span>共<?php echo ($orderCounts); ?>件</span>
                </div>
            </div>
            <div class="item-r" id="payDeliverWay">
                <div class="l-box fl">支付配送</div>
                <div class="r-box">
                    <p><span>微信支付</span></p>
                    <p><span style="color:red;" id="yunshu"><?php if($delivery_way == 0): ?>到店购买<?php else: ?>接受快递<?php endif; ?></span></p>
                    <p><span>工作日、双休日、节假日均可配送</span></p>
                    <i class="icons icon-more"></i>
                </div>

            </div>
        </div>
    </div>
    <div class="order-wrapper">
        <div class="items">
            <div class="item-l">
                <div class="list-title">取货方式</div>
            </div>
            <div class="item-r" id="adelivery_way">
                <input type="radio" name="delivery_way" value="0" style="display:none;">
                <a href="javascript:void(0);" id="buySelfBtn" class="btn <?php if($delivery_way == 0): ?>select<?php endif; ?>"  >到店购买</a>
                <input type="radio" name="delivery_way" value="1" style="display:none;">
                <a href="javascript:void(0);" class="btn <?php if($delivery_way == 1): ?>select<?php endif; ?>"  >接受快递</a>
            </div>
            <div class="item-r"><span class="attention">由于三好连锁店的所有物品都是超低价格出售，如选择接受快递，快递费均由买家自付！</span></div>
        </div>
    </div>
      <div class="order-wrapper">
          <div class="items">
              <a href="<?php echo U('userOrder/coupon');?>">
              <div class="item-l fl" style="margin-top: 0.1rem"><span class="fl">优惠券</span>
                <i class="can-use-tips fl"><?php echo ($couponNum); ?>张可用</i>
              </div>
              <i class="icons icon-more fr"></i>
              </a>
          </div>
      </div>
      <div class="order-wrapper">
          <div class="items">
              <a href="<?php echo U('userOrder/shareBill');?>">
                  <div class="item-l fl" style="margin-top: 0.1rem"><span class="fl">购物币</span>
                    <i class="can-use-tips fl">￥<?php echo ($userShallBill); ?>可用</i>
                  </div>
                  <i class="icons icon-more fr"></i>
              </a>
          </div>
      </div>

    <div class="order-wrapper" style="margin-bottom: 1.3rem">
        <div class="items">
            <div class="item-l fl"><span>商品金额</span></div><div class="item-r fr"><span class="money">￥<?php echo ($orderMoney); ?></span></div>
        </div>
        <?php if(!empty($coupon_id)): ?><div class="items">
            <div class="item-l fl"><span>优惠券抵扣</span></div><div class="item-r fr"><span class="money">￥<?php echo ($ctype_money); ?></span></div>
        </div><?php endif; ?>
        <?php if(!empty($share_bill)): ?><div class="items">
            <div class="item-l fl"><span>购物币抵扣</span></div><div class="item-r fr"><span class="money">￥<?php echo ($share_bill); ?></span></div>
        </div><?php endif; ?>
            <div class="items">
                <div class="item-l fl"><span>运费</span></div><div class="item-r fr"><span class="money" style="color:black;"><?php if(($yunfei) == "0"): ?>包邮<?php else: ?>￥<?php echo ($yunfei); endif; ?></span></div>
            </div>

    </div>
    <div class="info-noteMsg">
        送至：<?php echo ($userDefaultAddress["iname"]); echo ($userDefaultAddress["oname"]); echo ($userDefaultAddress["rname"]); echo ($userDefaultAddress["address"]); ?>
    </div>
    <div class="order-bottom-bar">
        <div class="left">实付款：￥<span class="pay-money" id="pauMoney"><?php echo number_format((float)$applyMoney+(float)$yunfei,2); ?></span></div>
        <a href="javascript:void(0);" class="order-submit-btn" id="orderSubmitBtn">提交订单</a>
    </div>
</form>
<!--加载-->
<div class="is-loading" style="height: 35px; padding-top: 2px; display: none;">
    <em></em>
    <span>加载中...</span>
</div>
<script>

    $(".order-submit-btn").on('touchstart',function(){
        //点击提交验证是否有选中的商品无货
        var length=$(".img-length").length;
        var a=true;
        for (var i = 0; i < length; i++) {
          var bool=!$(".img-length").eq(i).parent().parent().hasClass("invalid");
          var a= Boolean(a) && Boolean(bool);
        }
        if (a == true) {
                var deliveryWay =<?php echo ($delivery_way); ?>?<?php echo ($delivery_way); ?>:0;  //配送方式
                if (deliveryWay == 1) {
                    //判断是否填写了配送方式
                    var cartWayInfo =<?php echo ($cart_way_info); ?>;
                    if (cartWayInfo) {
                        //填写了跳转
                        window.location.href="<?php echo U('Mobile/userOrder/submitOrder');?>";
                    }else {
                        $("body").minDialog({
                            title: '提示',
                            content: "<p>您没有选择快递!</p>",
                            closeTime:1500,
                            isAutoClose:true
                        });
                    }
                }else {
                    window.location.href="<?php echo U('Mobile/userOrder/submitOrder');?>";
                }

        }else {
            $("body").minDialog({
                title: '提示',
                content: "<p>您选择的商品无货!</p>",
                closeTime:1500,
                isAutoClose:true
            });
        }
    })

    /*取货方式*/
    $("#adelivery_way a").on("click",function(){
      var delivery_way=$(this).prev().val();
      var addressCounts=<?php echo ($addressCounts); ?>;
      var fh =$(this);
        $(this).addClass('select').siblings("a").removeClass('select');
            if( delivery_way == 1){
                $("#yunshu").text('接受快递');
                $("#orderForm").find(".order-wrapper").eq(0).show();
                $(".info-noteMsg").show();
            }else{
                $("#yunshu").text('到店购买');
                $("#orderForm").find(".order-wrapper").eq(0).hide();
                $(".info-noteMsg").hide();
            }

        //更新配送方式
      $.ajax({
      type: "POST",
      dataType:'json',
      url:"<?php echo U('Mobile/UserOrder/updateWayCookie');?>",
      data:{delivery_way:delivery_way},
      async: false,
      error: function(request) {
          alert('cuowu');
      },
      success: function(data) {
        if ( fh.text()=='接受快递') {
            if (addressCounts == 0) {
                window.location.href="<?php echo U('Mobile/UserOrder/add');?>";
            }else {
              window.location.href="<?php echo U('Mobile/UserOrder/deliveryWay');?>";
            }
        }
        if ( fh.text()=='到店购买') {
            $(".info-noteMsg").hide();
        }
      }
      });
    })

          //跳转清空cookie
      $('.icon-back').on("touchstart",function(){
          var url="<?php echo U('Mobile/UserOrder/redirectCookieNull');?>";
          $("body").minDialog({content:'便宜不等人,请三思而行~',isConfirm:true,btnConfirm:'去意已决',btnCancal:'我再想想'},function(){
                $(".confirmBtn").click(function(){

                    window.location.href=url;
                })
          })
      })

      $("#payDeliverWay").on('click',function(){
          //更新配送方式

          if ($('#adelivery_way').find('a').eq(1).hasClass('select')) {
              window.location.href="<?php echo U('Mobile/UserOrder/deliveryWay');?>";
          }
      })

    $(function(){
        //如果购物车中的商品在店铺无货,则隐藏到店购买,并显示收货地址
        var checkStock = <?php echo ($checkStock); ?>;
        if (checkStock == 0) {
                $("#buySelfBtn").hide(); //隐藏到店购买
                $(".order-wrapper").eq(0).show();  //显示收货地址
                var cartWayInfo = <?php echo ($daliverArr); ?>;
                if (cartWayInfo == 0) {
                        $("#adelivery_way").find('a').eq(1).click();
                }
        }
      $(window).scroll(function(){
          if(!$("#buySelfBtn").hasClass("select")){
              var delivery_way=<?php echo ($delivery_way); ?>;
              var sTop = $(window).scrollTop();
              var titleHeight = $(".page-title").height();
              if(sTop>=titleHeight){
                  if(delivery_way == 1){
                      $(".info-noteMsg").show();
                  }else{
                      $(".info-noteMsg").hide();
                  }
              }else{
                  $(".info-noteMsg").hide();
              }
          }
      })
    })



        //移除函数的方法
    function removeEvent( node, type, listener) {
        if( node.addEventListener ){
            node.removeEventListener( type, listener, false );
            return true;
        } else if( node.detachEvent) {
            node.detachEvent('on' + type, listener);
            return true;
        }
        //如两种方法都不具备则返回false
        return false;
    }
</script>
</body>
</html>