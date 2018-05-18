<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title><?php echo ($web_title); ?></title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/dist/dropload.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.cookie.min.js"></script><script type="text/javascript" src="/somegood/Public/dist/dropload.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <style type="text/css">
        .left-classify-box{ background-color:#f3f3f3;}
        .article-box{ height: 100%; overflow:auto;}
        .article-list{ overflow:hidden;}
        .article-list li{ padding:0 0.15rem; height:0.7rem; line-height:0.7rem; border-bottom:1px solid #e3e3e3;}
        .article-list li a{ font-size:0.24rem; color:#666; display: block;}
        .notData{ margin:0; padding:0; line-height:0.7rem; text-align: center; font-size: 0.24rem; color:#666;}
    </style>
</head>
<body style="background:#fff;">
    <div class="page-title position-title">
        <!--返回按钮-->
        <div class="back-box">
            <a href="<?php echo U('Mobile/Users/index');?>" class="icons icon-back"></a>
        </div>
        <h2 class="title">帮助中心</h2>
        <!--返回按钮-->
    </div>
    <!--左边分类-->
    <div class="category-containter">
        <div class="left-classify-box">
            <div class="left-over-box">
              <ul>
                  <?php if(is_array($categorylist)): $i = 0; $__LIST__ = $categorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($vo["id"] == $catId): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Mobile/Help/center',array('cat_id'=>$vo['id']));?>"><?php echo ($vo["c_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
              </ul>
            </div>
        </div>
        <!--左边分类-->
        <!--右边分类展示-->
        <div class="right-show-box">
            <div class="right-show-box-content">
                <div class="article-box">
                    <ul class="article-list">
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Mobile/Help/detail',array('id'=>$item['aid']));?>"><?php echo ($item["title"]); ?></a></li><?php endforeach; endif; else: echo "$empty" ;endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--右边分类展示-->
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
    <script>
        $(".left-classify-box").find("li").on("touchstart",function(){
            $(this).addClass("sel").siblings().removeClass("sel");
        })
        //滚动页面ajax自动获取下一页内容
        var page=2; //有效求购的页码
        var totalPage = <?php echo ($pageCount); ?>; //有效求购页的总页码，会从后台获
        var ajaxUrl="<?php echo U('Mobile/Help/center');?>?cat_id=<?php echo ($catId); ?>";//ajax请求地址
        $(function(){
            var tabLoadEnd = false;
            // dropload
            var dropload = $('.article-box').dropload({
                scrollArea : window,
                loadDownFn : function(me){
                    // 加载菜单一的数据
                    if(page <= totalPage){
                        $.ajax({
                            url:ajaxUrl,
                            type:"post",
                            dataType:"json",
                            data:{
                                'p':page
                            },
                            success:function(data){
                                var str='';
                                $.each(data.data.list,function(index, vo) {
                                    str += "<li> ";
                                    str += "<a href=\"<?php echo U('Mobile/Help/detail');?>?id="+vo['aid']+"\">";
                                    str += vo['title'];
                                    str += "</a>";
                                    str += "</li>";
                                })
                                // 为了测试，延迟1秒加载
                                $('.article-list').append(str);
                                // 每次数据加载完，必须重置
                                me.resetload();
                                totalPage = data.data.pageCount;
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
        /*设置分类盒子高度*/
        $(function(){
            var H = $(window).height();
            var headH = $(".position-title").height();
            var footerH = $(".footer").height();
            $(".left-over-box").height(H-headH-footerH);
            $(".right-show-box").height(H-headH-footerH);
        })
    </script>
</body>
</html>