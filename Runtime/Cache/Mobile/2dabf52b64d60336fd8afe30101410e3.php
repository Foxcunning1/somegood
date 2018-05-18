<?php if (!defined('THINK_PATH')) exit(); if(($is_ajax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><div class="shop-item" onclick="bindToggleClass($(this))">
      <div class="shop-ctbox">
          <span class="shop-name fl"><?php echo ($vs["store_name"]); ?></span>
          <span class="shop-apart fr"><span class="distance apart" data-lng="<?php echo ($vs["lng"]); ?>" data-lat="<?php echo ($vs["lat"]); ?>"><?php echo ($vs["distance"]); ?></span>Km</span>
      </div>
      <div class="shop-ctbox" style="font-size: initial;">
          <i class="icons icon-location fl"></i>
          <span class="address fl"><?php echo ($vs["shortname"]); ?>区</span>
          <a href="<?php echo U('Mobile/Store/detail');?>?shopid=<?php echo ($vs["sid"]); ?>" class="to-shop fr">进入店铺</a>
      </div>
      <input type="hidden" name="shopid" id="shopId" value="<?php echo ($vs["id"]); ?>" />
      <input type="hidden" value="<?php echo ($vs["stock"]); ?>" />
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
    <title><?php echo ($web_title); ?></title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.fx.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/TouchSlide/TouchSlide.1.1.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/zepto.fly.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script><script type="text/javascript" src="/somegood/Public/dist/dropload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <script type="text/javascript">
        var usersLng = <?php echo (session('lng')); ?>;//用户所在的经纬度
        var usersLat = <?php echo (session('lat')); ?>;
        if(isWeixin()) {
            usersLng = parseFloat(localStorage.getItem("longitude"));
            usersLat = parseFloat(localStorage.getItem("latitude"));
        }
    </script>
    <style type="text/css">
        @-webkit-keyframes txtGradient{
            0%{ background-color:#ff5a00; transform: translate3d(-1px, 0, 0);}
            25%{ background-color:#ff5a00; transform: translate3d(3px, 0, 0);}
            50%{ background-color:#ff5a00; transform: translate3d(-3px, 0, 0);}
            75%{ background-color:#08abe7; transform: translate3d(0px, 0, 0);}
            100%{ background-color:#08abe7; translate3d(1px, 0, 0);}
        }
        @keyframes txtGradient {
            0%{ background-color:#ff5a00; transform: translate3d(-1px, 0, 0);}
            25%{ background-color:#ff5a00; transform: translate3d(3px, 0, 0);}
            50%{ background-color:#ff5a00; transform: translate3d(-3px, 0, 0);}
            75%{ background-color:#08abe7; transform: translate3d(0px, 0, 0);}
            100%{ background-color:#08abe7; translate3d(1px, 0, 0);}
        }
        @-webkit-keyframes borderGradient {
            0%{ border-color:transparent transparent #ff5a00 transparent;}
            25%{ border-color:transparent transparent #ff5a00 transparent;}
            50%{ border-color:transparent transparent #ff5a00 transparent;}
            75%{ border-color:transparent transparent #08abe7 transparent;}
            100%{ border-color:transparent transparent #08abe7 transparent;}
        }
        @keyframes borderGradient {
            0%{ border-color:transparent transparent #ff5a00 transparent;}
            25%{ border-color:transparent transparent #ff5a00 transparent;}
            50%{ border-color:transparent transparent #ff5a00 transparent;}
            75%{ border-color:transparent transparent #08abe7 transparent;}
            100%{ border-color:transparent transparent #08abe7 transparent;}
        }
        .dropload-refresh,.dropload-update,.dropload-load,.dropload-noData {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 14px;
        }
        .goods-detail .location{ overflow: visible;}
        .txt-tips-animate{ padding:2px 5px; left:10px; bottom:-0.18rem; font-size:12px; border-radius:3px; background-color:#00af8e; -webkit-animation:txtGradient 3s infinite; animation:txtGradient 3s infinite; color:#fff;  position: absolute;}
        .txt-tips-icon{ width:0; height:0; font-size:0; -webkit-animation:borderGradient 3s infinite; animation:borderGradient 3s infinite; border-width:5px; border-style:solid; border-color:transparent transparent #f3961c transparent; _border-color:#f3961c white white; overflow:hidden; position:absolute; left:20px; bottom:0.16rem;}
    </style>
</head>
<body>
<div class="page-title shop-page-title">
    <!--返回按钮-->
    <div class="back-box">
        <a href="javascript:history.go(-1);" class="icons icon-back"></a>
    </div>
    <!--返回按钮-->
    <!--标题-->
    <div class="li-box">
        <ul>
            <li class="sel">商品</li>
            <li>详情</li>
        </ul>
    </div>
    <!--标题-->
</div>
<div class="tab-page-box">
    <div class="tab-page" style="display: block">
        <!--详情图滚动图-->
        <div class="goods-info-slider" id="goodsInfoSlider">
            <div class="hd">
                <ul>
                    <?php if(is_array($pic)): $i = 0; $__LIST__ = $pic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if(($i) == "1"): ?>class="on"<?php endif; ?>></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            <div class="bd">
                <ul>
                    <?php if(is_array($pic)): $i = 0; $__LIST__ = $pic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Mobile/Goods/preview');?>?id=<?php echo ($goodsId); ?>"><img src="/somegood/Public/uploads/<?php echo ($vo); ?>" class="js-smartPhoto" alt="详情图片1"/></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
        <script>TouchSlide({slideCell:"#goodsInfoSlider",mainCell:".bd ul",autoPlay:true});</script>
        <!--详情图滚动图-->
        <!--分享引导-->
        <div class="over-lay-share">
            <img src="/somegood/Public/statics/mobile/images/know.png" alt="我知道了" class="know">
            <img src="/somegood/Public/statics/mobile/images/pointer.png" alt="点击右上角分享按钮分享产品" class="pointer">
        </div>
        <!--分享引导-->
        <div class="over-lay-small"></div>
        <div class="goods-detail">
            <div class="g-info">
                <div class="product-name">
                    <span><?php echo ($list["goods_title"]); ?></span>
                    <input type="hidden" name="goods_id" value="<?php echo ($info["id"]); ?>">
                    <div class="collection"><i class="icons icon-like <?php if(($favNum) == "1"): ?>like<?php endif; ?>"></i><span>收藏</span></div>
                </div>
                <div class="product-price-m" style="display:flex;">

                    <em class="fl" style="flex:1;">¥<span class="big-price" ><?php echo (number_format($list["price"],2)); ?></span><span class="small-price" style="text-decoration: line-through;color:black;"><?php echo ($list["market_price"]); ?></span></em>
                    <span  style="flex:1; text-align: center; font-size:0.22rem; color:#999;">在售商家：<?php echo ($counts); ?>家</span>
                    <span class="view fr"  style="flex:1; text-align: right;"><span id="viewNum"><?php echo ($list["browse_num"]); ?></span>次浏览</span>
                </div>
            </div>
        </div>
        <div class="shop-information">
            <div class="shop-info-t">
                <a href="javascript:;"><img src="/somegood/Public/uploads/<?php echo ($sellerInfo["avatar"]); ?>_c200x200" alt="" /><span><?php echo ($company_name); ?></span></a>
            </div>
            <div class="shop-info-m">
                <div><a href="javascript:;"><span><?php echo ($num); ?></span><span>全部宝贝</span></a></div>
                <div><a href="javascript:;"><span><?php echo ($volume); ?></span><span>商家销量</span></a></div>
            </div>
            <div class="shop-info-b">
                <a href="<?php echo U('Mobile/Goods/sellerGoods');?>?id=<?php echo ($seller_id); ?>">逛逛商家</a>
            </div>
        </div>
        <!--商品详情筛选部分-->
        <div class="shop-filter">
            <ul class="filter-ul">
                <li class="li-btn area" id="area"><span><?php if($area_id == '' ): ?>全城<?php else: echo ($sel_area); endif; ?></span></li>
                <li class="orderBtn li-btn distaceBtn <?php if(($order) == "2"): ?>selected<?php endif; ?>" data-status="2"><span>离我最近</span></li>
                <!-- <li class="orderBtn li-btn price <?php if(($order) != "2"): ?>selected<?php endif; ?> <?php if(($order) == "1"): ?>click<?php endif; ?>" data-status="<?php if($order != '2'): echo ($order); else: ?>0<?php endif; ?>"><span>价格</span></li> -->
            </ul>
            <div class="area-choose" id="areaChoose">
              <div class="choose-box">
                  <ul>
                    <li class="selected-li"  data-areaid="" ><a href="javascript:void(0);"><span>全城</span><i class="icons icon-select"></i></a></li>
                    <?php if(is_array($district)): $i = 0; $__LIST__ = $district;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vb): $mod = ($i % 2 );++$i;?><li class="selected-li"  data-areaid="<?php echo ($vb["id"]); ?>"><a href="javascript:void(0);"><span><?php echo ($vb["name"]); ?></span><i class="icons icon-select"></i></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                  </ul>
              </div>
          </div>
        </div>
        <!--商品详情筛选部分-->
        <!--商品在售店铺列表-->
        <div class="sale-shop-list" id="sale-shop-list">
            <div class="store-shop-list">

            </div>
        </div>
    </div>
    <!--商品详情页-->
    <div class="tab-page detail-page fl">
        <ul class="goods-info-ul">
            <li>商品介绍</li>
            <li>规格参数</li>
        </ul>
        <div class="info-tab detail-tab">
            <!-- 商品介绍 -->
            <div class="tab-change" style="display: block;">
                <!-- <img src="<?php echo ($QRCode); ?>" alt="" style="width:200px;height:200px;"> -->
                <!--商品介绍基本都是图片-->
                <?php echo (html_entity_decode($list["details"])); ?>
            </div>
            <!-- 规格参数 -->
            <div class="tab-change">
            <table class="parameter-table">
            	<tbody>
                <tr>
                <?php echo (html_entity_decode($list["params"])); ?>
                </tr>
            	</tbody>
            </table>
            </div>
        </div>
    </div>
 </div>
<!--商品在售店铺列表-->
<div class="add-to-cart">
    <a href="<?php echo U('Index/index');?>" class="to-home"><i class="icons icon-home-bottom"></i><span>首页</span></a>
    <a href="<?php echo U('Cart/index');?>" class="to-cart"><i class="icons icon-cart-bottom"></i><span>购物车</span><span class="icon-num" id="cartNum">0</span></a>
    <a style="display:none;" href="<?php echo U('Cart/index');?>" class="buy-now">立即购买</a>
    <a href="javascript:;" class="add-cart ">加入购物车</a>
    <!-- disable -->
</div>
<form id="cartform" action="" method="post" >
  <input type="hidden" name="goods_count" id="goodsCount" value="<?php echo ($info[goods_num]); ?>" />
  <input type="hidden" name="goods_num" id="goodsNum" value="1" />
</form>

<!--公用底部-->
<!--公用底部-->
<script>
//滚动页面ajax自动获取下一页内容

  var totalPage = <?php echo ($pageCount); ?>; //总页码，会从后台获取
  var ajaxUrl="<?php echo U('Mobile/Goods/detail');?>";  //ajax请求地址
  function getAjaxPrintData(area_id,page) {
        var tabLoadEnd = false;
        // dropload
        var dropload = $('#sale-shop-list').dropload({
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
                                'id':<?php echo ($goodsId); ?>,
                                'area_id':area_id
                            },
                            success:function(data){
                                var str = data;
                                // 为了测试，延迟1秒加载
                                $(".store-shop-list").append(str);
                                // $(".shop-item").eq(0).addClass("select");
                                // 每次数据加载完，必须重置
                                me.resetload();
                                var totalPage = <?php echo ($pageCount); ?>;
                                //$(".dropload-down").remove();
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
  }
        /*区域选中*/
        $(".selected-li").on("click",function(){
            $(".shop-filter").find("li.area").removeClass("done");
            var page=1; //当前页码
            $(".store-shop-list").children().remove();  //重置div
            $(".dropload-down").remove();  //重置div
            //调教ajax分页输出
            var area_id = $(this).data("areaid"); //获取区域id
            if (!area_id) {
                var area_id = '';
            }
            $(".area-choose.showBox").css("max-height","0px");
            $(".over-lay-small").hide();
            $(".shop-filter").removeClass("fixed");
            getAjaxPrintData(area_id,page);   //获取分页输出数据
            $(".tab-page-box").scrollTop(0);
            var read=$(this).children().eq(0).text();  //替换选择span内容
            $("#area").text(read);
        });

    $(function() {
        $(".selected-li").eq(0).click(); //第一次进入页面默认加载
        $(".shop-item").eq(0).addClass('select');
        $(".sale-shop-list").find(".shop-item").on("click", function () {
            $(this).addClass("select").siblings().removeClass("select");
        })
        $(".li-box").find("li").on("click", function () {
            $(this).addClass("sel").siblings().removeClass("sel");
            $(".tab-page-box").find(".tab-page").eq($(this).index()).show().siblings().hide();
        })

        /*详情里面的切换*/
        $(".goods-info-ul").find("li").on("click",function(){
            $(".detail-tab").find(".tab-change").eq($(this).index()).show().siblings().hide();
        })


        /*点击筛选各选项  筛选条浮动*/
        var h = $(".area-choose").height();
        $(".shop-filter").find("li.area").on("click",function(){
            if($(this).hasClass("done")){
                $(".shop-filter").removeClass("fixed");
                $(".area-choose").css("max-height",h);
                $(".over-lay-small").hide();
                $(this).removeClass("done");
                $(".area-choose").css("max-height",0);
                //$(".area-choose").removeClass("showBox");
                $(".area-choose").css("max-height",0);
                $("body").css("overflow","auto");
            }else {
               $(".shop-filter").addClass("fixed");
               $(".area-choose").css("max-height", 0);
               $(".over-lay-small").show();
               $(this).addClass("done");
               $(".area-choose").css("max-height", h);
               $(".area-choose").addClass("showBox");
               //$("body").css("overflow","hidden");
            }
        })
    })

    //点击选择样式,给append的元素绑定事件
    function bindToggleClass(obj) {
        if (obj.hasClass('select')) {
            obj.removeClass("select");
        }else {
            obj.siblings().removeClass("select");
            obj.addClass("select");
        }
    }
</script>
<script>
    //获取购物车商品的数量
    $(function() {
        /*点击距离和价格排序*/
        $(".orderBtn").on("touchstart",function(){
            var orderType = $(this).attr("data-status");
            //
        })
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
    })
    $(function(){
        calcDistance(".distance");//绑定计算距离
    })

    function addToCartAnimation(obj){
        var st = $(window).scrollTop();
        var offset = $(".to-cart").offset();
        var addBtn = $(obj);
        //执行添加购物车动画
        var img = "/somegood/Public/uploads/<?php echo ($info["goods_thumb"]); ?>_r200x200";
        var flyer = $('<img class="u-flyer" src=""+img+"" />');
        flyer.fly({
            start: {
                left: addBtn.offset().left+40,
                top: addBtn.offset().top-st
            },
            end: {
                left: offset.left+30,
                top: offset.top+10-st,
                width: 0,
                height: 0
            },
            onEnd: function(){
                this.destory();
            }
        });
    }

    function popupa(info){
        $("body").minDialog({
            isAutoClose:true,
            closeTime:500,
            content: "<p>"+info+"</p>"
        });
    }
    //收藏商品
    $(".collection").on('touchstart',function(){
        var goods_id =<?php echo ($goodsId); ?>;
        $.ajax({
            method:'post',
            dataType:'json',
            url: "<?php echo U('Mobile/Goods/collect');?>",
            data:{"goods_id":goods_id},
            async:false,
            success:function(data){
                if (data.status == 1) {
                    $(".icon-like").toggleClass("like");
                }
                popupa(data.info);
            }
        });
    });
    /*分享引导弹出*/
    $(".icon-share").on("touchstart",function(){
        $(".over-lay-share").show();
        $("body").css("overflow","hidden");
    });
    $("img.know").on("click",function(){
        $(".over-lay-share").hide();
        $("body").css("overflow","auto");
    });
    /*加入购物车*/
    $(".add-cart").on("touchstart",function(){
        var goods_id = <?php echo ($goodsId); ?>;//商品ID
        var shop_id = $(".shop-item.select").find("input").eq(0).val(); //seller_delivery的id
        var goods_num = $("#goodsNum").val();//添加购物车数量
        var goods_count = $(".shop-item.select").find("input").eq(1).val();//商品库存
        // if(goods_num <= goods_count){
            $.ajax({
                type: "POST",
                dataType:'json',
                url:"<?php echo U('Mobile/Cart/addGoods');?>",
                data:{"goods_id":goods_id,"shop_id":shop_id,"goods_num":goods_num,"goods_count":goods_count},
                success: function(data) {
                    //接收后台返回的结果
                    if (data.status == 1) {
                        var num=$('.icon-num').text();
                        var d = parseInt(num)+parseInt(1);
                        addToCartAnimation($(".add-cart"));
                        $('.icon-num').text(d).animate({"transform":"scale(1.2)"},200,function(){
                            $(this).animate({"transform":"scale(1)"},200);
                        });
                    }
                    popupa(data.info);
                }
            });
        // }
        //return false;
    })
</script>
</body>
</html><?php endif; ?>