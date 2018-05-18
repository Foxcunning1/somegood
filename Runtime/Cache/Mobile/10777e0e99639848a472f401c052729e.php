<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>收货地址管理</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" />
    <!-- <link rel="stylesheet" type="text/css" href="../css/cart.css" />
    <script type="text/javascript" charset="utf-8" src="../js/zepto.min.js"></script>
    <script type="text/javascript" src="../js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>  -->

    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/cart.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/TouchSlide/TouchSlide.1.1.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <?php if(!empty($cart_id)): ?><a href="<?php echo U('Mobile/UserOrder/order');?>" class="icons icon-back"></a><?php else: ?><a href="<?php echo U('Mobile/Users/index');?>" class="icons icon-back"></a><?php endif; ?>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <h2 class="title">收货地址</h2>
    <!--标题-->
</div>
<form method="post" class="order-adderss-form" id="orderAddressForm" style="padding-top: 0.86rem !important">
    <div class="order-address-box">
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="address-items <?php if($vo['is_default'] == 1): ?>select<?php endif; ?>">
              <div class="address-center-box fl">
                  <?php if(!empty($cart_id)): ?><a href="<?php echo U('Mobile/UserOrder/selectOrderAddress');?>?id=<?php echo ($vo["address_id"]); ?>">
                      <?php else: ?><a href="javascript:void(0);"><?php endif; ?>
                      <div class="name-and-num">
                          <span class="name fl"><?php echo ($vo['consignee']); ?></span>
                          <!--  -->
                          <div class="fr defaultAddressIcon" style="margin-right:0.2rem;font-weight: bold;color:#f00;">默认</div>
                          <!--  -->
                          <strong><?php echo ($vo['mobile']); ?></strong>
                      </div>
                      <p class="address-info"><?php echo ($vo['iname']); echo ($vo['oname']); echo ($vo['rname']); echo ($vo['address']); ?></p>
                  </a>
              </div>
            <div class="opera-box fr">
                <a href="<?php echo U('Mobile/UserOrder/editUserAddress',array('address_id'=>$vo['address_id']));?>" class="address-editor fl"><i class="icons icon-address-editor"></i></a>
                <input type="hidden" name="" value="<?php echo ($vo["address_id"]); ?>">
                <a href="javascript:void(0);" class="address-delete fr"><i class="icons icon-address-delete"></i></a>
            </div>
            <div class="clear"></div>
            <input type="hidden" name="" value="<?php echo ($vo['address_id']); ?>">
            <div class="setting-default" style="font-size: 0.22rem;">设为默认地址</div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <div class="add-address-box">
        <a href="<?php echo U('Mobile/UserOrder/add');?>" class="add-address-btn"><i class="plus">+</i>新建地址</a>
    </div>
</form>

<script>
      //删除收货地址
      $('.address-delete').on("touchstart",function(){
        var address_id=$(this).prev().val();
        var fh =$(this);
        $.ajax({
            data:{address_id:address_id},
            type: 'POST',
            url: "<?php echo U('Mobile/UserOrder/delOrderAddress');?>",
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    fh.parent().parent().remove();
                }
            }
        })
      })

    $(function(){
        /*改变默认地址*/
       $(".setting-default").on("touchstart",function(){
            var address_id=$(this).prev().val();
            var fh=$(this);
           //$(this).parent().addClass("select").siblings(".address-items").removeClass("select");
           $.ajax({
               data:{address_id:address_id},
               type: 'POST',
               url: "<?php echo U('Mobile/UserOrder/updateDefaultAddress');?>",
               dataType: 'json',
               success: function (data) {
                   if (data.status == 1) {
                       fh.parent().addClass("select").siblings().removeClass("select");
                   }
               }
           })
       })
    })
</script>
</body>
</html>