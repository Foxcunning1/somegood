<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>品牌推广</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/zepto.select.dialog.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/zepto.select.area.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/webuploader.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" />
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/jquery-1.10.2.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/webuploader.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/uploader.js"></script><script type="text/javascript" src="/somegood/Public/scripts/js/region.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.select.dialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.select.area.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.validateForm.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/mobileSelect.css">
    <script src="/somegood/Public/scripts/js/mobileSelect.js" type="text/javascript"></script>
    <!-- <script type="text/javascript">
        var uploadType = 'image';
        $(function(){
            $(".upload-album").InitUploader({ btntext: "批量上传", multiple: true, sendurl: "<?php echo U('Mobile/Member/uploadFile');?>", swf: "/116cs/Public/JS/webuploader/uploader.swf" });
        })
    </script> -->
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="javascript:history.back();" class="icons icon-back"></a>
    </div>
    <h2 class="title">品牌推广</h2>
    <!--返回按钮-->
</div>
<div class="welcome-join">
    <div class="title">欢迎加入三好连锁</div>
    <p>感谢您选择和关注三好连锁品牌推广平台！请您留下您的基本信息，我们专业服务人员会马上与您联系！</p>
    <div class="examine-status-box animate <?php if($is_seller == 1): ?>examined<?php elseif($info): ?>examining<?php else: endif; ?>"></div>
</div>
<form url="<?php echo U('Mobile/Users/apply');?>" method="post" class="shop-apply-form form-box page-box" id="shopApplyForm">
    <div class="title">基本信息</div>

    <dl>
        <dt>公司名称</dt>
        <dd><?php if(empty($info)): ?><input type="text" placeholder="输入您的公司名称" class="required" name="data[company_name]" value="<?php echo ($info["company_name"]); ?>" ><?php else: ?>
            <span class="txt"><?php echo ($info["company_name"]); ?></span><?php endif; ?></dd>
    </dl>
    <dl>
        <dt>品牌分类</dt>
        <dd><?php if(empty($info)): ?><input type="text" placeholder="输入您要推广品牌的分类" name="data[category]" class="required" value="<?php echo ($info["category"]); ?>" required><?php else: ?><span class="txt"><?php echo ($info["category"]); ?></span><?php endif; ?></dd>
    </dl>
    <dl>
        <dt>推广方式</dt>
        <dd class="fl">
            <div class="item-r" id="adelivery_way">
                <?php if(empty($info)): ?><a href="javascript:void(0);"  class="btn <?php if($info['spread_way'] != 1): ?>select<?php endif; ?>" >广告牌推广</a>
                    <input type="checkbox" name="data[spread_way][]" value="0" style="display:none;"  <?php if($info['spread_way'] != 1): ?>checked="checked"<?php endif; ?>>
                    <a href="javascript:void(0);" class="btn <?php if($info['spread_way'] != 0): ?>select<?php endif; ?>">产品上架推广</a>
                    <input type="checkbox" name="data[spread_way][]" value="1" style="display:none;" <?php if($info['spread_way'] != 0): ?>checked="checked"<?php endif; ?>>
                    <?php else: ?>
                    <a href="javascript:void(0);"  class="btn <?php if($info['spread_way'] != 1): ?>select<?php endif; ?>" >广告牌推广</a>
                    <a href="javascript:void(0);" class="btn <?php if($info['spread_way'] != 0): ?>select<?php endif; ?>">产品上架推广</a><?php endif; ?>
                <!-- 隐藏的checkbox -->
            </div>
        </dd>
    </dl>
    <dl>
        <dt>联系人</dt>
        <dd><?php if(empty($info)): ?><input type="text" placeholder="输入你的联系人信息" class="required" name="data[name]" value="<?php echo ($info["name"]); ?>"><?php else: ?><span class="txt"><?php echo ($info["name"]); ?></span><?php endif; ?></dd>
    </dl>
    <dl>
        <dt>联系方式</dt>
        <dd><?php if(empty($info)): ?><input type="text"  required placeholder="输入您的手机号" pattern="^1[3,4,5,7,8]\d{9}$" class="required" name="data[mobile]" value="<?php echo ($info["mobile"]); ?>"><?php else: ?><span class="txt"><?php echo ($info["mobile"]); ?></span><?php endif; ?></dd>
    </dl>
    <dl>
        <dt>邮箱</dt>
        <dd><?php if(empty($info)): ?><input type="text" placeholder="输入你的邮箱" pattern="^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$" class="required" name="data[email]" value="<?php echo ($info["email"]); ?>"><?php else: ?><span class="txt"><?php echo ($info["email"]); ?></span><?php endif; ?></dd>
    </dl>
    <dl>
        <dt>详细地址</dt>
        <dd><?php if(empty($info)): ?><input type="text" placeholder="输入公司详细地址" name="data[address]" class="required" value="<?php echo ($info["address"]); ?>" required><?php else: ?><span class="txt"><?php echo ($info["address"]); ?></span><?php endif; ?></dd>
    </dl>

    <script type="text/javascript">
        // 添加全局站点信息
        var BASE_URL = '/webuploader';
    </script>
    <?php if(empty($info)): ?><input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>">
        <button type="submit" class="apply-btn" id="phoneLoginBtn"><?php if(($_GET['action']) == "edit"): ?>更新<?php else: ?>提交<?php endif; ?></button>
        <p class="txt">点击申请，表示您已阅读并同意<a class="agreement" id="agreement" href="<?php echo U('Mobile/Help/agreement');?>">《用户注册协议》</a></p><?php endif; ?>
</form>
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
    /*推广方式选择*/
    <?php if(empty($info)): ?>$("#adelivery_way").children('a').on('click', function(event) {
            $(this).toggleClass('select');
            if ($(this).hasClass('select')) {
                $(this).next().prop('checked', true);
            }else {
                $(this).next().prop('checked', false);
            }
        });<?php endif; ?>
    /*加盟店申请 分类选择js*/
    $(function(){
        $("#shopApplyForm").validateForm({isAutoClose: true,closeTime: 1500,return_url:"<?php echo U("Mobile/Users/index");?>"});
        $(".cancleBtn").on("touchstart",function(){
            $("#goods-type-box .filter-option").removeClass("select");
            $(".tools-btn-box").css("visibility","hidden");
            $(".kinds-box").animate({"left":"100%"},300,function(){
                $(".kinds-box").hide();
                $(".over-lay").hide();
            });
            $('html').removeClass('noscroll');
            $("body").css("overflow-y","auto");
        })

        $("#submitBtn").on("touchstart",function(){
            $(".tools-btn-box").css("visibility","hidden");
            $(".kinds-box").animate({"left":"100%"},300,function(){
                $(".kinds-box").hide();
                $(".over-lay").hide();
            });
            $("body").css("overflow","auto");
        })

        $("#submitBtn").on("touchstart");

    })
</script>
</body>
</html>