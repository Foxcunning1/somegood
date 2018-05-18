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
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/dist/dropload.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/timeago.js"></script><script type="text/javascript" src="/somegood/Public/dist/dropload.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body>
<div class="shop-inner">
    <div class="top-tips">
        <a href="<?php echo U('Mobile/Users/index');?>" class="icons icon-back fl"></a>
        <h2>我的购物币</h2>
        <a href="<?php echo U('Mobile/Users/setting');?>" class="setting-box fr"><i class="icons icon-setting"></i></a>
    </div>
    <div class="head-portrait">
        <a href="<?php echo U('Users/setting',array('type'=>'user_img'));?>" class="fl">
            <img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="<?php if(empty($user["avatar"])): ?>/somegood/Public/statics/mobile/images/default_img.png<?php else: ?>/somegood/Public/uploads/<?php echo ($user["avatar"]); ?>_c200x200<?php endif; ?>" alt="<?php echo ($session["mobile"]["mobile"]); ?>" style="border-radius: 50%;" />
        </a>
    </div>
    <div class="name-funs">
        <h3>购物币合计：￥<?php echo ($countInfo["total_money"]); ?></h3>
    </div>
</div>
<div class="already-title" style="padding: 0; position: absolute;">
    <ul class="tab">
        <li class="item <?php if(($type) == "0"): ?>sel<?php endif; ?>"><a href="<?php echo U('Mobile/Users/virtualCash');?>">已获得<em id="hasGet">(<?php echo ($countInfo["f_counts"]); ?>)</em></a></li>
        <li class="item <?php if(($type) == "1"): ?>sel<?php endif; ?>"><a href="<?php echo U('Mobile/Users/virtualCash',array('type'=>1));?>">已抵扣<em id="hasDivided">(<?php echo ($countInfo["t_counts"]); ?>)</em></a></li>
    </ul>
</div>
<div class="user-section" style="padding-top:0.78rem">
    <div class="sec-content">
        <div class="virtual-currency-box">
            <div class="result-list">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><div class="list-item">
                    <div class="share fl">
                        <?php if(($item["change_type"]) == "0"): if(empty($item["user_name"])): echo ($item["mobile"]); else: echo ($item["user_name"]); endif; else: ?>抵扣<?php endif; ?>
                    </div>
                    <div class="info fl">
                        <div class="title"><?php echo ($item["change_note"]); ?></div>
                        <div class="time"><time class="timeago" datetime="<?php echo (date("Y-m-d H:i:s",$vo["change_time"])); ?>"></time></div>
                    </div>
                    <div class="number fl"><?php echo ($item["user_money"]); ?></div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
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
<script>
    $(function(){
        //发布的日期与现在间隔的时间
        $(".timeago").timeago();
    })
    //滚动页面ajax自动获取下一页内容
    var page=2; //有效求购的页码
    var totalPage = <?php echo ($pageCount); ?>; //有效求购页的总页码，会从后台获
    var ajaxUrl="<?php echo U('Mobile/Users/virtualCash');?>?type=1";//ajax请求地址
    $(function(){
        var tabLoadEnd = false;
        // dropload
        var dropload = $('.virtual-currency-box').dropload({
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

                                str += "<div class=\"list-item\">";
                                str += "<div class=\"share fl\">";
                                if(vo['change_type']==1){
                                    str += "抵扣";
                                }else{
                                    str += vo['mobile'];
                                }
                                str += "</div>";
                                str += "<div class=\"info fl\">";
                                str += "<div class=\"title\">"+vo['change_note']+"</div>";
                                str += "<div class=\"time\"><time class=\"timeago\" datetime=\""+date('Y-m-d',""+vo['change_time']+"")+"\"></time></div>";
                                str += "</div>";
                                str += "<div class=\"number fl\">"+vo['user_money']+"</div>";
                                str += "</div>";
                            })
                            // 为了测试，延迟1秒加载
                            $('.result-list').append(str);
                            // 每次数据加载完，必须重置
                            me.resetload();
                            $(".timeago").timeago();
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
        /*头部背景改变*/
        $(window).scroll(function(){
            var sTop = $(window).scrollTop();
            if(sTop >= 20){
                $(".top-tips").addClass("change-bgcolor");
                $(".top-tips h2").show();
            }else{
                $(".top-tips").removeClass("change-bgcolor");
                $(".top-tips h2").hide();
            }
        })
    });
</script>
</body>
</html>