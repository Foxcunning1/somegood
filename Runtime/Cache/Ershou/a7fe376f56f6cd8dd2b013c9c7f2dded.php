<?php if (!defined('THINK_PATH')) exit(); if(($isAjax) == "1"): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><div class="con-items">
            <p class="items-title"><a href="<?php echo U('Ershou/Goods/detail');?>?id=<?php echo ($item["id"]); ?>"><?php echo ($item["goods_title"]); ?></a></p>
            <div class="items-img-box">
                <a href="<?php echo U('Ershou/Goods/detail');?>?id=<?php echo ($item["id"]); ?>">
                <ul>
                    <?php $photoList = array(); $photoRemark = array(); if($item['original_img']){ $tempArr = explode("||",$item['original_img']); $photoList = unserialize($tempArr[0]); $photoRemark = unserialize($tempArr[1]); } ?>
                    <?php if(is_array($photoList)): $key = 0; $__LIST__ = $photoList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$photo): $mod = ($key % 2 );++$key; if($key < 4): ?><li>
                        <img data-original="/somegood/Public/uploads/<?php echo ($photo); ?>_c200x200" src="/somegood/Public/statics/mobile/images/default_img.png" />
                    </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                </a>
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
    <title><?php echo ($info["nick_name"]); ?></title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/ershou/css/style.css" />
    <link rel="stylesheet" href="/somegood/Public/dist/dropload.css">
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/statics/ershou/js/common.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/ershou/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
     <script type="text/javascript">
        var usersLng = <?php echo (session('lng')); ?>;//用户所在的经纬度
        var usersLat = <?php echo (session('lat')); ?>;
        if(isWeixin()) {
            usersLng = parseFloat(localStorage.getItem("longitude"));
            usersLat = parseFloat(localStorage.getItem("latitude"));
        }
    </script>
</head>
<body>
<!--分享引导-->
<div class="over-lay-share">
    <img src="/somegood/Public/statics/Ershou/images/know.png" alt="我知道了" class="know" />
    <img src="/somegood/Public/statics/Ershou/images/pointer.png" alt="点击右上角分享按钮分享产品" class="pointer" />
</div>
<!--分享引导-->

<div class="shop-inner my-shop-inner">
    <!--返回按钮和分享-->
    <div class="top-tips">
        <a href="javascript:history.go(-1);" class="icons icon-back fl"></a>
        <i class="icons icon-share fr"></i>
    </div>
    <!--返回按钮和分享-->
    <a href="<?php echo U('Ershou/Store/myShop',array('shopid'=>$info['sid']));?>" class="portrait-box">
        <img src="<?php if(empty($info["avatar"])): ?>/somegood/Public/statics/ershou/images/default_img.png<?php else: ?>/somegood/Public/uploads/<?php echo ($info["avatar"]); ?>_c200x200<?php endif; ?>" alt="<?php echo ($info["nick_name"]); ?>" class="user-img" />
    </a>
</div>
<div class="my-shop-containter">
    <div class="top-box">
        <div class="my-txt-box">
            <h3><?php echo ($info["nick_name"]); ?></h3>
            <div class="more-box fl">
                <ul>
                    <li><span class="item-num"><?php echo ($info["goods_counts"]); ?></span><span>在售宝贝</span></li>
                    <li><span class="item-num"><?php echo ($info["sale_counts"]); ?></span><span>成功卖出</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bottom-box">
        <ul class="tab-nav-ul">
            <li <?php if(($pageType) == "all"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Ershou/Store/myShop');?>?shopid=<?php echo ($shop_id); ?>&pageType=all"><span>全部</span></a></li>
            <li <?php if(($pageType) == "rec"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Ershou/Store/myShop');?>?shopid=<?php echo ($shop_id); ?>&pageType=rec"><span>推荐</span></a></li>
            <li <?php if(($pageType) == "new"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Ershou/Store/myShop');?>?shopid=<?php echo ($shop_id); ?>&pageType=new"><span>最新</span></a></li>
            <li <?php if(($pageType) == "hot"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Ershou/Store/myShop');?>?shopid=<?php echo ($shop_id); ?>&pageType=hot"><span>热卖</span></a></li>
        </ul>
        <div class="tab-page-box" style="position: inherit;">
            <div class="tab-items">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/somegood/Public/dist/dropload.min.js"></script>
<script type="text/javascript">
    var page=1; //当前页的页码
    var allpage=<?php echo ($pageCount); ?>; //总页码，会从后台获
    var ajaxUrl="<?php echo U('Ershou/Store/myShop');?>";//ajax请求地址
    $(function(){
        /*分享引导弹出*/
        $(".icon-share").on("touchstart",function(){
            $(".over-lay-share").show();
            $("body").css("overflow","hidden");
        })
        $("img.know").on("touchstart",function(e){
            $(".over-lay-share").hide();
            $("body").css("overflow","auto");
            e.preventDefault();

        })

        $(window).scroll(function(){
            var sTop = $(window).scrollTop();
            var H = $(".my-shop-inner").offset().height;
            if(sTop>=H/2){
                $(".my-shop-inner-box").css("background-color","#00b190");
            }else{
                $(".my-shop-inner-box").css("background-color","rgba(0,0,0,0)");
            }
        })
        var dropload = $('.tab-page-box').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                if(page<=allpage){
                    $.ajax({
                        url:ajaxUrl,
                        type:"post",
                        dataType:"html",
                        data:{
                            'p':page,
                            'shopid':<?php echo ($shop_id); ?>3,
                            'pageType': '<?php echo ($pageType); ?>'
                        },
                        success:function(data){
                            var str = data;
                            $('.tab-items').append(str);
                            $(".timeago").timeago();
                            //图片延迟加载
                            $(".g-img img").lazyload({effect : "fadeIn"});
                            // 每次数据加载完，必须重置
                            me.resetload();
                        },
                        error: function(xhr, type){
                            alert('Ajax error!');
                            // 即使加载出错，也得重置
                            me.resetload();
                        }
                    });
                    page++;
                }else{
                    // 锁定
                    me.lock();
                    // 无数据
                    me.noData();
                    me.resetload();
                }
            }
        });
    });
</script>
</body>
</html><?php endif; ?>