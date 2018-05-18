<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>购物车</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/cart.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/ui-dialog.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/cart.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script>
</head>
<body>
    <div class="page-title" >
        <!--返回按钮-->
        <div class="to-back-box">
            <a href="<?php if( $returnUrl == 'order.html' ): echo U('Mobile/index/index'); else: ?>javascript:history.back(-1);<?php endif; ?>" class="icons icon-back"></a>
        </div>
        <h2 class="title">购物车</h2>
        <!--返回按钮-->
    </div>
    <!--购物车是空的-->
    <?php if($counts == 0 ): ?><div class="car-is-empty">
        <div class="empty-img"></div>
        <p>购物车是空的</p>
        <div class="empty-a-box"><a href="<?php echo U('Goods/category');?>" id="hereAndThere">到处逛逛</a></div>
        <!--公用底部-->
        	<div class="footer">
        <ul>
            <li><a href="<?php echo U('Mobile/Index/index');?>"><i class="icons icon-home"></i><span>首页</span></a></li>
            <li><a href="<?php echo U('Mobile/Goods/category');?>"><i class="icons icon-classify"></i><span>分类</span></a></li>
            <li><a href="<?php echo U('Mobile/Cart/index');?>"><i class="icons icon-cart"></i><span class="icon-num" id="cartNum">0</span><span>购物车</span></a></li>
            <li id="shopKeepper"><a href="<?php if(($$Think["session"]["mobile"]["store_id"]) == "0"): echo U('Mobile/Users/index'); else: ?>javascript:void(0);<?php endif; ?>"><i class="icons icon-mine"></i><span>我的</span></a></li>
        </ul>
    </div>
    <div style="height: 1rem;width: 100%; position: fixed; bottom: 0; right:0;z-index: 1">
        <?php if(($$Think["session"]["mobile"]["store_id"]) != "0"): ?><div class="shopkeeper-box products-menu">
                <a href="<?php echo U('Mobile/Users/index');?>">会员中心</a>
                <a href="<?php echo U('Mobile/SecondHand/index');?>">二手管理</a>
                <a href="<?php echo U('Mobile/Store/index');?>">店铺管理</a>
				<a href="<?php echo U('Mobile/Seller/index');?>">商家管理</a>
            </div><?php endif; ?>
    </div>
    <script type="text/javascript">

        /*店铺管理动画*/
        $("#shopKeepper").on("click",function(event){
            $(".shopkeeper-box").toggleClass("click");
            event.stopPropagation();
        })
        /*点击其他地方关闭右下角可选入口*/
        $(document).bind('click',function(e){
            var e = e || window.event; //浏览器兼容性
            var elem = e.target || e.srcElement;
            while (elem) { //循环判断至跟节点，防止点击的是div子元素
                if (elem.id && elem.id=='test') {
                    return;
                }
                elem = elem.parentNode;
            }
            $(".shopkeeper-box").addClass("click");
            $(".shopkeeper-box").removeClass("click");
        })
        /*公用底部点击样式变换*/
        $(".footer ul").find("li").on("click",function(){
            $(this).addClass("sel").siblings().removeClass("sel");

        })


        $(function() {
			//获取购物车商品的数量
            $.ajax({
                type: 'POST',
                url: "<?php echo U('Mobile/Cart/getCartNum');?>",
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        $("#cartNum").text(data.data);
                    } else {
                        $("#cartNum").text("0");
                    }
                }
            })
			//获取是否是商家或店铺
			$.ajax({
                type: 'POST',
                url: "<?php echo U('Mobile/Cart/isWho');?>",
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
					//alert(data.data.isStore);
						if (data.data.isSeller == 0) {
							$(".shopkeeper-box.products-menu").children('a').eq(3).text("品牌推广");
						}
						if (data.data.isStore == 0) {
							$(".shopkeeper-box.products-menu").children('a').eq(2).text("加盟店申请");
						}
                    }
                }
            })
        })
    </script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    /**把经纬度信息写入session
     * @Param     decimal          lng           当前用户经度信息
     * @Param     decimal          lat           当前用户纬度信息
     * */
    function writeLocationInfoToSession(lng,lat){
        $.ajax({
            type: "post",
            url: "<?php echo U('Ershou/Ajax/writeLocationInfoToSession');?>",
            data:{'lng':lng,'lat':lat},
            dataType: "json",
            success: function(){
                //数据提交成功！
            }
        });
    }
    $(function () {
        if(isWeixin()){
            //是微信浏览器则ajax获取
            $.ajax({
                type: "post",
                url: "<?php echo U('Mobile/WxShare/getWxShareApiInfo');?>",
                data:{'url':window.location.href},
                dataType: "json",
                success: function(item){
                    res = item.data;
                    wx.config({
                        debug: false,
                        appId: res.app_id,
                        timestamp: res.timestamp,
                        nonceStr: res.nonceStr,
                        signature: res.signature,
                        jsApiList: [
                            'onMenuShareTimeline',
                            'onMenuShareAppMessage',
                            'getLocation'
                        ]
                    });
                    var dataForWeixin = {
                        title: "<?php echo ($web_title); ?>",
                        desc: "<?php echo C('SEO_DESCRIPTION');?>",
                        imgUrl: "<?php echo C('MOBILE_URL');?>/somegood/Public/statics/mobile/images/share_img.jpg",
                        link: res.share_url
                    };
                    wx.ready(function () {
                        console.log('wx ready');
                        wx.onMenuShareTimeline({
                            title: dataForWeixin.title,
                            link: dataForWeixin.link,
                            imgUrl: dataForWeixin.imgUrl,
                        });
                        wx.onMenuShareAppMessage({
                            title: dataForWeixin.title,
                            desc: dataForWeixin.desc,
                            link: dataForWeixin.link,
                            imgUrl: dataForWeixin.imgUrl,
                        });
                        wx.getLocation({
                            success: function (res) {
                                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                                var speed = res.speed; // 速度，以米/每秒计
                                var accuracy = res.accuracy; // 位置精度
                                //把经纬度写入localStorage
                                localStorage.setItem("latitude", latitude);
                                localStorage.setItem("longitude", longitude);
                                //同时把经纬度信息ajax给后台，以session形式存储
                                writeLocationInfoToSession(longitude,latitude);
                                var data = {
                                    latitude: latitude,
                                    longitude: longitude
                                };
                                if (typeof callback == "function") {
                                    callback(data);
                                }
                            },
                            cancel: function () {
                                //这个地方是用户拒绝获取地理位置
                                if (typeof error == "function") {
                                    error();
                                }
                            }
                        });
                    });

                },
            });
        }
    });
</script>



        <!--公用底部-->
    </div>
  <?php else: ?>
  <div class="page-content-pd pd-t08">
      <div class="safe-mode-box">
          <div class="safe-mode-msg">
                  <span>
                      <i class="icons icon-relax"></i>
                      <span class="msg-safe">您正在安全购物环境中，请放心购物</span>
                  </span>
          </div>
      </div>
      <form class="cart-form" id="cartForm" url="ajax_url" method="post" action="<?php if($user_id == 0): echo U('Users/index'); else: echo U('Mobile/Cart/cookieNull'); endif; ?>">
        <?php if(is_array($shop)): $i = 0; $__LIST__ = $shop;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><div class="show-group" id="storeItem<?php echo ($vs["store_id"]); ?>">
              <div class="show-title">
                  <i class="icons icon-location fl"></i>
                  <span class="shop-location fl">卖家:<?php if(empty($$vs['company_name'])): echo ($vs["company_name"]); else: ?>三好连锁<?php endif; ?></span>
              </div>

              <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if($item['seller_id'] == $vs['seller_id']): ?><div class="shop-car-list">
                  <ul>
                      <li id="cartItem<?php echo ($item["cart_id"]); ?>">
                          <div class="items">
                              <div class="check-wrapper"><i class="icons icon-checkbox"></i>
                                <input type="checkbox" style="display:none" name="cartIds[]" style="z-index: -1"  value="<?php echo ($item["cart_id"]); ?>">
                                <input type="hidden" name="" value="<?php echo ($item["goods_id"]); ?>">
                              </div>
                          </div>
                          <div class="shop-cart-item-core">
                              <a href="javascript:void(0);" class="cart-product-cell"><img src="/somegood/Public/uploads/<?php echo ($item["goods_thumb"]); ?>_c200x200" alt="商品图" /></a>
                              <div class="item-info">
                                  <div class="item-name">
                                      <a href="<?php echo U('Goods/detail',array('id'=>$item['real_goods_id']));?>">
                                          <span><?php echo ($item['goods_title']); ?></span>
                                          <span style="height: 0.3rem; line-height:0.3rem;font-size: 0.24rem">(<?php echo ($item['store_name']); ?>)</span>
                                      </a>
                                  </div>
                                  <div class="item-price"><span class="good-price fl"><?php echo ($item[goods_price]); ?></span>
                                      <div class="num-change-box fr" >
                                          <input type="hidden" name="" value="<?php echo ($item["cart_id"]); ?>">
                                          <a href="javascript:void(0);" class="num-plus fl" onclick="">-</a>
                                          <input type="text" disabled="disabled" class="num fl" name="" value="<?php echo ($item["counts"]); ?>">
                                          <a href="javascript:void(0);" class="num-reduce fl" onclick="">+</a>
                                      </div>
                                  </div>
                                  <div class="option-btns">
                                      <!-- <a class="cart-new-btn atten-move-btn" data-action="move" goodsid="<?php echo ($item["cart_id"]); ?>" href="javascript:;">移入收藏</a> -->
                                      <input type="hidden" name="cart_id" value="<?php echo ($item['cart_id']); ?>">
                                      <a class="cart-new-btn del-prod-btn fr" data-action="del" data-index="0" goodsid="<?php echo ($item["cart_id"]); ?>" storeid="<?php echo ($vs["store_id"]); ?>" href="javascript:;">删除</a>

                                  </div>
                              </div>
                              <?php if(($item['store_id'] != 0 && $item['stock'] == 0 && $item['online_stock'] == 0) || $item['store_id'] != 0 && $item['is_on_sale'] == 0 || $item['store_id'] == 0 && $item['online_stock'] == 0): ?><div class="invalid-box"><p>无货</p><p>Sold Out</p></div><?php endif; ?>
                          </div>
                      </li>

                  </ul>
              </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
          </div><?php endforeach; endif; else: echo "" ;endif; ?>



          <div class="payment-total-bar box-flex-f">
              <div class="shop-chk-new">
                  <label for="checkIcon-1" style="display: block;">
                      <span class="icons icon-checkboxall" id="checkIcon-1"></span>
                      <span class="car-checkbox-text">全选</span>
                  </label>
              </div>
              <div class="shop-car-info box-flex-c">
                  <strong class="shop-car-total">
                      合计:
                      <span class="bottom-bar-price"> 0.00</span>
                  </strong>
                  <span class="bottom-total-price">
                      总额:
                      <span class="money-unit-bf"> 0.00</span>
                  </span>
              </div>
              <a href="javascript:void(0);" class="btn-right-block box-flex-c">去结算(<span id="checkedNum">0</span>)</a>
          </div>
          <!-- <input type="submit" style="display:none;" name="" value=""> -->
      </form>
  </div><?php endif; ?>
    <!--购物车是空的-->

</body>
<script>
      /*激活结算按钮*/
      $(".btn-right-block").on("touchstart",function(){
          //绑定表单提交事件
          submitCartGoods();
      })

      //移入收藏
      $(".atten-move-btn").on('touchstart',function(){
        var goods_id =$(this).attr("goodsid");
        //alert(goods_id);
        $.ajax({
          method:'post',
          dataType:'json',
          url:"<?php echo U('Mobile/Goods/collect');?>",
          data:{goods_id:goods_id},
          async:false,
          error:function(request){
            var info ='加入收藏夹失败';
            popupa(info);
          },
          success:function(data){
            if (data == 'no') {
              popupa('用户未登录,不能加入收藏夹');
            }else {
              //$(".icon-like").toggleClass("like");
              popupa(data);
            }
          }
        });
      })
      //删除购物车
    $('.del-prod-btn').on('touchstart',function(){
        var cart_id=$(this).attr("goodsid");
        var parentObjId = $(this).attr("storeid");
        //alert(cart_id);
        delCart(cart_id, "<?php echo U('Mobile/Cart/delCartGoods');?>", false);
        var cartItem='#cartItem'+cart_id;
        $(cartItem).remove();
        var parentObj = $("#storeItem"+parentObjId).find("li");
        if(parentObj.length==0){
            $("#storeItem"+parentObjId).remove();
        }
        if($(".show-group").length==0){
            window.location.href = "<?php echo U('Mobile/Cart/index');?>";
        }
    })

    //更新购物车数量
    $('.num-change-box a').on('touchstart',function(){
        if($(this).hasClass('num-reduce')){
            plusOrReduce($(this),1);
        }else{
            plusOrReduce($(this),0);
        }
        var num =$(this).parent().children('.num').val();
        var goods_id =$(this).parent().children().eq(0).val();
        updateCartGoods($(this),"<?php echo U('Mobile/Cart/ajaxUpdateGoodsNum');?>", goods_id, num);
    })
    //商品数量输入失去焦点
    $(".num").on("blur",function(){
        var num =$(this).val();
        var goods_id =$(this).parent().children().eq(0).val();
        updateCartGoods($(this),"<?php echo U('Mobile/Cart/ajaxUpdateGoodsNum');?>", goods_id, num);
    })
    function popupa(info){
      $("body").minDialog({
          title: '提示',
          content: "<p>"+info+"</p>",
      });
    }

    function delete2(){
      $("body").minDialog({
          title: '提示',
          content: "<p>删除成功</p>",
      });
    }

    function delete1(){
      $("body").minDialog({
          title: '提示',
          content: "<p>删除失败</p>",
      });
    }
    /*单选加计算*/
    $(".shop-car-list").find(".icon-checkbox").on("touchstart",function(){
        singleChecked(this);
        cartCount(this);
    });
    /*全选和计算*/
    $(".shop-chk-new").find(".icon-checkboxall").on("touchstart",function(){
        allChecked(this);
        cartCount(this);
    })



</script>


</html>