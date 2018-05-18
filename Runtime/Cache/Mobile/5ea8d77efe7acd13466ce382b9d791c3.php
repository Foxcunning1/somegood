<?php if (!defined('THINK_PATH')) exit(); if(($isAjax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><div class="item">
        <div class="only-position">
            <a href="<?php echo U('Users/orderDetail',array('order_sn'=>$vs['order_sn']));?>" class="shop-a fl" >订单号:<?php echo ($vs['order_sn']); ?></a>
            <input type="hidden" name="order_sn" value="<?php echo ($vs['order_sn']); ?>">
            <?php if($vs['status'] == 3 or $vs['status'] == 6): ?><a  href="javascript:void(0);" class="icons icon-delete del fr" ></a><?php endif; ?>
            <?php if($vs['status'] == 0): ?><span class="fr" style="font-size:0.20rem; color:red;">等待付款</span><?php endif; ?>
            <?php if($vs['status'] == 1): ?><span class="fr" style="font-size:0.20rem; color:red;">等待发货</span><?php endif; ?>
            <?php if($vs['status'] == 2): ?><span class="fr" style="font-size:0.20rem; color:red;">等待收货</span><?php endif; ?>
            <div style="clear:both"></div>
            <?php if($vs['status'] == 3 or $vs['status'] == 6): ?><div class="signet"></div><?php endif; ?>
        </div>
        <div class="imgs-box">
            <?php if(is_array($vs['goods_thumb'])): $i = 0; $__LIST__ = $vs['goods_thumb'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vd): $mod = ($i % 2 );++$i;?><img src="/somegood/Public/uploads/<?php echo ($vd); ?>_c200x200" alt=""  onerror=" javascript:this.src='/somegood/Public/uploads/20171025/59f03428401cb.png_c200x200' " style="margin-left:10px;"><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="split-row">
           <div class="tr-operate">
               <?php if($vs['is_son'] == 0 ): ?><span class="ftx-13 fl">订单状态:已拆分</span><?php endif; ?>
              <span class="fr">订单金额:<?php echo ($vs['money']); ?></span>
          </div>
        </div>
        <div class="bottom-bar">
            <div class="imb-btn-box fr" >
              <?php if($vs['status'] == 0 and ($vs['is_son'] == 0 or $vs['is_son'] == 2)): ?><a href="<?php echo U('Mobile/Wxpay/index');?>?order_sn=<?php echo ($vs["order_sn"]); ?>" class="go-to-pay fr">去支付</a><?php endif; ?>
              <?php if($vs['status'] == 0): ?><a href="javascript:void(0);" class="go-to-pay fr cancelOrder" >取消订单</a><?php endif; ?>
              <?php if(($vs['status'] == 1) and ($vs['delivery_way'] == 1)): ?><a href="https://m.kuaidi100.com/index_all.html?type=$vs['code']&postid=$vs['logistics_sn']&callbackurl=$callbackurl" class="view-logistics fr">查看物流</a><?php endif; ?>
              <?php if(($vs['status'] == 2) and ($vs['delivery_way'] == 1)): ?><input type="hidden" name="" value="<?php echo ($vs['id']); ?>">
              <a href="javascript:void(0);" class="view-logistics fr">确认收货</a>
              <a href="https://m.kuaidi100.com/index_all.html?type=$vs['code']&postid=$vs['logistics_sn']&callbackurl=$callbackurl" class="view-logistics fr">查看物流</a><?php endif; ?>
              <?php if($vs['status'] == 3 or $vs['status'] == 6): ?><a href="javascript:void(0);" class="evaluation fr" >立即评价</a><?php endif; ?>
            </div>
        </div>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>
<?php else: ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>订单管理</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/dist/dropload.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/dist/dropload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="<?php echo U('Mobile/Users/index');?>" class="icons icon-back"></a>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <h2 class="title">订单管理</h2>
    <!--标题-->
</div>
<div class="already-title pd-t08">
    <ul>

        <li <?php if($status == 100): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Users/orderIndex',array('status'=>'100'));?>">全部</a></li>
        <li <?php if($status == 0): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Users/orderIndex',array('status'=>'0'));?>">待付款</a></li>
        <li <?php if($status == 1): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Users/orderIndex',array('status'=>'1'));?>">待发货</a></li>
        <li <?php if($status == 2): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Users/orderIndex',array('status'=>'2'));?>">待收货</a></li>
        <li <?php if($status == 3): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Users/orderIndex',array('status'=>'3'));?>">已完成</a></li>
    </ul>
</div>
<!--点击选项卡控制near-content显示和隐藏-->
<!--全部-->
<div class="div_orderlist  pd-t18" id="orderListBox">
    <div class="allOrders">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><div class="item">
              <div class="only-position">
                  <a href="<?php echo U('Mobile/Users/orderDetail',array('order_sn'=>$vs['order_sn']));?>" class="shop-a fl" >订单号:<?php echo ($vs['order_sn']); ?></a>
                  <input type="hidden" name="order_sn" value="<?php echo ($vs['order_sn']); ?>">
                  <?php if($vs['status'] == 3 or $vs['status'] == 6): ?><a  href="javascript:void(0);" class="icons icon-delete del fr" ></a><?php endif; ?>
                  <?php if($vs['status'] == 0): ?><span class="fr" style="font-size:0.20rem; color:red;">等待付款</span><?php endif; ?>
                  <?php if($vs['status'] == 1): ?><span class="fr" style="font-size:0.20rem; color:red;">等待发货</span><?php endif; ?>
                  <?php if($vs['status'] == 2): ?><span class="fr" style="font-size:0.20rem; color:red;">等待收货</span><?php endif; ?>
                  <div style="clear:both"></div>
                  <?php if($vs['status'] == 3 or $vs['status'] == 6): ?><div class="signet"></div><?php endif; ?>
              </div>
              <div class="imgs-box">
                  <?php if(is_array($vs['goods_thumb'])): $i = 0; $__LIST__ = $vs['goods_thumb'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vd): $mod = ($i % 2 );++$i;?><img src="/somegood/Public/uploads/<?php echo ($vd); ?>_c200x200" alt="" style="margin-left:10px;"><?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
              <div class="split-row">
                 <div class="tr-operate">
                     <?php if($vs['is_son'] == 0 ): ?><span class="ftx-13 fl">订单状态:已拆分</span><?php endif; ?>
                    <span class="fr">订单金额:<?php echo ($vs['money']); ?></span>
                </div>
              </div>
              <div class="bottom-bar">
                  <div class="imb-btn-box fr" >
                    <?php if($vs['status'] == 0 and ($vs['is_son'] == 0 or $vs['is_son'] == 2)): ?><a href="<?php echo U('Mobile/Wxpay/index');?>?order_sn=<?php echo ($vs["order_sn"]); ?>" class="go-to-pay fr">去支付</a><?php endif; ?>
                    <input type="hidden" name="" value="<?php echo ($vs['order_sn']); ?>">
                    <?php if($vs['status'] == 0): ?><a href="javascript:void(0);" class="go-to-pay fr cancelOrder" >取消订单</a><?php endif; ?>
                    <?php if(($vs['status'] == 1) and ($vs['delivery_way'] == 1)): ?><a href="https://m.kuaidi100.com/index_all.html?type=$vs['code']&postid=$vs['logistics_sn']&callbackurl=$callbackurl" class="view-logistics fr">查看物流</a><?php endif; ?>
                    <?php if(($vs['status'] == 2) and ($vs['delivery_way'] == 1)): ?><input type="hidden" name="" value="<?php echo ($vs['id']); ?>">
                    <a href="javascript:void(0);" class="view-logistics fr">确认收货</a>
                    <a href="https://m.kuaidi100.com/index_all.html?type=$vs['code']&postid=$vs['logistics_sn']&callbackurl=$callbackurl" class="view-logistics fr">查看物流</a><?php endif; ?>
                    <?php if($vs['status'] == 3 or $vs['status'] == 6): ?><a href="javascript:void(0);" class="evaluation fr" >立即评价</a><?php endif; ?>
                  </div>
              </div>
          </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<!--公用底部-->
	<div class="footer ufooter">
        <ul>
            <li><a href="<?php echo U('Mobile/Index/index');?>"><i class="icons icon-home"></i><span>新品</span></a></li>
            <li><a href="<?php echo U('Ershou/Index/index');?>"><i class="icons icon-classify"></i><span>二手</span></a></li>
            <li><a href="<?php echo U('Mobile/Users/favorites');?>"><i class="icons icon-cart"></i><span>收藏</span></a></li>
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
        $("#shopKeepper").on("click",function(){
            $(".shopkeeper-box").toggleClass("click");
        })
        /*公用底部点击样式变换*/
        $(".footer ul").find("li").on("touchstart",function(){
            $(this).addClass("sel").siblings().removeClass("sel");

        })

        $(function() {
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
						};
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
<!--加载-->
<div class="is-loading" style="height: 35px; padding-top: 2px; display: none;">
    <em></em>
    <span>加载中...</span>
</div>
</body>
<script>
    $(function(){
      $('.imb-btn-box a').css("margin-left","5px");
    })

    //取消订单 回滚各种币
    $('.cancelOrder').on('touchstart',function(){
      var order_sn=$(this).prev().val();
      var fh=$(this);
      $("body").minDialog({isConfirm:true,content:'真的要取消这个订单?',btnConfirm:'当然',btnCancal:'等一下'},function(){
          $(".confirmBtn").click(function(){
              $.ajax({
                  url:"<?php echo U('Mobile/UserOrder/cancelOrder');?>",
                  type:"post",
                  dataType:"json",
                  data:{
                      'order_sn':order_sn,
                  },
                  success:function(data,textStatus){
                    if (data.status==1) {
                        fh.parent().parent().parent().remove();
                    }else {
                        alert('error!');
                    }
                  },
                  error: function(xhr, type){
                      alert('Ajax error!');
                      // 即使加载出错，也得重置
                  }
              });
          })
      })
    })

    //确认收货
    $(".view-logistics").on('touchstart',function(){
      //订单id
      var id=$(this).prev().val();
      var fh = $(this);
      $("body").minDialog({isConfirm:true,content:'您确定收到货了吗?'},function(){
          $(".confirmBtn").click(function(){
              $.ajax({
                  url:"<?php echo U('Mobile/Users/comfirm');?>",
                  type:"post",
                  dataType:"json",
                  data:{
                      'id':id,
                  },
                  success:function(data,textStatus){
                    if (data.status==1) {
                        fh.parent().parent().parent().remove();
                    }else {
                        alert('error!');
                    }
                  },
                  error: function(xhr, type){
                      alert('Ajax error!');
                      // 即使加载出错，也得重置
                  }
              });
          })
      })
    })

    //滚动页面ajax自动获取下一页内容
    var page=2; //有效求购的页码
    var totalPage = <?php echo ($pageCount); ?>; //有效求购页的总页码，会从后台获
    var ajaxUrl="<?php echo U('Mobile/Users/orderIndex');?>";//ajax请求地址
    $(function(){
        var tabLoadEnd = false;
        // dropload
        var dropload = $('#orderListBox').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                // 加载菜单一的数据
                    if(page <= totalPage){
                        $.ajax({
                            url:ajaxUrl,
                            type:"post",
                            dataType:"html",
                            data:{
                                'p':page,
                                'status':<?php echo ($status); ?>
                            },
                            success:function(data){
                                var str = data;
                                // 为了测试，延迟1秒加载
                                $('.allOrders').append(str);
                                $(function(){
                                  $('.imb-btn-box a').css("margin-left","5px");
                                })
                                // 每次数据加载完，必须重置
                                me.resetload();
                                totalPage = <?php echo ($pageCount); ?>;
                            },
                            error: function(xhr, type){
                                alert('Ajax error!');
                                // 即使加载出错，也得重置
                                me.resetload();
                            }
                        });
                        page++;
                    }else{
                        // 数据加载完
                        tabLoadEnd = true;
                        // 锁定
                        me.lock();
                        // 无数据
                        me.noData();
                        me.resetload();
                    }
                    // 加载菜单二的数据
            }
        });
        // 如果数据没有加载完
        if(!tabLoadEnd){
            // 解锁
            dropload.unlock();
            dropload.noData(false);
        }else{
            // 锁定
            dropload.lock('down');
            dropload.noData();
        }
        // 重置
        dropload.resetload();
    });

      //删除订单
      $(".icon-delete").on('touchstart',function(){
        var order_sn = $(this).prev().val();
        var fh=$(this);
        $("body").minDialog({content:'确认删除此订单?',isConfirm:true,btnConfirm:'删除'},function(){
          $(".confirmBtn").click(function(){
            deleteMyGoods(fh);
            ajaxDelOrder(order_sn);
          })
        });
        })

        //删除订单函数
      function ajaxDelOrder(order_sn){
        $.ajax({
          method:'post',
          dataType:'json',
          url:"<?php echo U('Users/delOrder');?>",
          data:{order_sn:order_sn},
          async:false,
          error:function(request){
            var info ='服务器有点问题!';
            //popupa(info);
          },
          success:function(data){
            if (data.status == 1) {

            }else {
              alert('error');
            }

          }
      })
    }

    $(".already-title").find("li").on("touchstart",function(){
        var index = $(this).index();
        $(this).addClass("sel").siblings().removeClass("sel");
        $(".div_orderlist").find(".allOrders").eq(index).show().siblings().hide();
    })
    /*删除订单函数*/
    function deleteMyGoods(obj){
        $(obj).parent().parent().remove();
    }
</script>
</html><?php endif; ?>