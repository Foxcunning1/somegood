<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>物流配送</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/cart.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/TouchSlide/TouchSlide.1.1.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/mobileSelect.css">
    <script src="/somegood/Public/scripts/js/mobileSelect.js" type="text/javascript"></script>
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="<?php echo U('Mobile/UserOrder/order');?>" class="icons icon-back"></a>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <h2 class="title">物流配送</h2>
    <!--标题-->
</div>
<form action="<?php echo U('Mobile/UserOrder/updataDeliveryWay');?>" class="confirm-wuliu-form" id="confirmWuliuForm">
    <div class="order-wrapper" style="padding: 0.86rem 0 1rem 0; margin: 0;">
        <div class="items">
            <div class="item-l">
                <div class="list-title"><i class="icons icon-wuliu"></i>支付方式</div>
            </div>
            <div class="item-r">
                <a href="javascript:void(0);" class="select btn">微信</a>
            </div>
        </div>
        <div class="items">
            <div class="item-l">
                <div class="list-title"><i class="icons icon-wuliu"></i>配送方式</div>
            </div>

            <!--一个单独的订单-->
            <?php if(is_array($orderInfo)): $i = 0; $__LIST__ = $orderInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="list-item">
                <div class="item-r">
                    <ul>
                          <?php if(is_array($vo['goods_img'])): $i = 0; $__LIST__ = $vo['goods_img'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><li><a href="javascript:void(0);"><img src="/somegood/Public/uploads/<?php echo ($vs); ?>_c200x200" alt="" /></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>

                <input type="hidden" name="" value="<?php echo ($vo['store_id']); ?>">
                <div class="item-r">
                    <div  id="trigger<?php echo ($vo['seller_id']); ?>"  ><a href="javascript:void(0);"  class="btn select paytai"  >
                      <span class="xuanze">选择快递</span>
                    </a></div>  <!--页面中别漏了这个trigger-->
                    <!-- <a href="javascript:void(0);"  class="ziti btn <?php if($delivery_way == 0): ?>select<?php endif; ?>"  >到店购买</a> -->
                    <input type="hidden" name="" value="<?php echo ($vo['seller_id']); ?>">
                    <input type="checkbox" style="display:none" checked="checked" name="delivery[<?php echo ($vo['seller_id']); ?>][seller_id]" style="z-index: -1"  value="<?php echo ($vo['seller_id']); ?>">
                    <input type="checkbox" style="display:none" checked="checked" name="delivery[<?php echo ($vo['seller_id']); ?>][delivery_way]" style="z-index: -1"  value="1">
                    <input class="logId" type="checkbox" style="display:none" checked="checked" name="delivery[<?php echo ($vo['seller_id']); ?>][id]" style="z-index: -1"  value="0">
                    <input type="checkbox" style="display:none" checked="checked" name="delivery[<?php echo ($vo['seller_id']); ?>][price]" style="z-index: -1" value="0" >
                    <input type="checkbox" style="display:none" checked="checked" name="delivery[<?php echo ($vo['seller_id']); ?>][value]" style="z-index: -1"  value="0" >
                    <!-- store_id,delivery_way 配送方式,快递id -->
                </div>
                <div class="item-r">工作日、双休日、节假日均可送货</div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>

            <!--一个单独的订单-->
        </div>
    </div>
    <div class="add-address-box">
        <!-- <button id="submitButton" type="submit" class="save-use-btn confirm-wuliu-btn" name="" value="">确定</button> -->
        <i id="submitButton" type="submit" class="save-use-btn confirm-wuliu-btn" name="" value="">确定</i>
    </div>

</form>

<script>
            //检测快递是否都选择
          $("#submitButton").on('touchstart',function(){
            var length=$(".list-item").length;
              var ids=true;
              for (var i = 0; i < length; i++) {
                  var id=Boolean(parseInt($(".logId").eq(i).val()));
                  var ids=id && ids;
              }
              if (ids) {
                $("#confirmWuliuForm").submit();
              }else {
                $("body").minDialog({
                    content: "<p>请选择快递方式,或重新下单!</p>",
                    isMask:true,
                    isAutoClose:true,
                    closeTime:1000
                });
              }
          })

        $(function(){


          var xuanze=$(".xuanze").text();
          if ( xuanze == null) {
                $(".paytai").text('选择快递');
          }

          //ajax获取input的隐藏值
          $.each(storeIds,function(index1,value1){
                  var Idname="#trigger"+index1;
                  var store_id=$(Idname).next().val();
                  for (var i = 0; i < 3; i++) {
                              $.ajax({
                              type: "POST",
                              dataType:'json',
                              url:"<?php echo U('Mobile/UserOrder/getDeliverValue');?>",
                              data:{
                                  store_id:store_id,
                                  index:i
                              },
                              async: false,
                              error: function(request) {
                                  alert('ajaxError');
                              },
                              success: function(data) {
                                if (i == 2 && data.data !=null) {
                                    $(Idname).children().children().text(data.data);
                                }
                                $(Idname).parent().children().eq(parseInt(i)+parseInt(4)).val(data.data);   //id
                              }
                              });
                  }
          })

        })




        // $(".ziti").on('touchstart',function(){
        //   var store_id=$(this).next().val();
        //   var str = store_id+'0,0';
        //     $(this).prev().children("a").removeClass('select');
        //     $(this).addClass('select');
        //     $(this).next().val(str);
        // })

        var storeIds=<?php echo ($store_id_arr); ?>;
        //给Id绑定事件
        $.each(storeIds,function(index1,value1){
                var Idname="#trigger"+index1;
                if (storeIds[index1].length ==0) {
                    $(Idname).css('display','none').next().addClass('select').parent().append("<div><a href=\"javascript:void(0);\" class=\"btn\">无物流配送,请单独下单!</a></div>");
                }else {
                  var dataArr=JSON.stringify(value1);
                  //alert(dataArr); 弹出插件
                  selectOption(Idname,dataArr);
                }
        })

        //点击弹出插件
      function selectOption(Idname,dataArr){
          $(Idname).on('touchstart',function(){
                var mobileSelect2 = new MobileSelect({
                    trigger: Idname,
                    title: '物流选择',
                    wheels: [
                         {data:
                           eval(dataArr)
               },
                      ],
                    position:[0], //初始化定位 打开时默认选中的哪个 如果不填默认为0
                    transitionEnd:function(indexArr, data){
                      //$(".mobileSelect").remove();
                        console.log(data);
                    },
                    cancel:function(indexArr, data){
                        $(".mobileSelect").remove();
                        if ($(Idname).next().hasClass('select')) {
                            $(Idname).children("a").removeClass('select');
                        }
                    },
                    callback:function(indexArr, data){
                      //alert(data[0].id);
                        $(".mobileSelect").remove();
                        $(Idname).children("a").addClass('select');
                        $(Idname).next().removeClass('select');
                        var store_id=$(Idname).next().next().val();
                        $(Idname).next().next().next().next().val(data[0].id);
                        $(Idname).next().next().next().next().next().val(data[0].price);
                        $(Idname).next().next().next().next().next().next().val(data[0].value);
                        console.log(data);
                    }
                });
          })
        }








    $(function(){
       $(".list-item .item-r").find("a.btn").on("click",function(){
           $(this).addClass('select').siblings("a").removeClass('select');
       })
    })
</script>

</body>
</html>