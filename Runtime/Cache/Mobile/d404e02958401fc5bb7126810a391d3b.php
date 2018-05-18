<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>加盟店<?php if(($action) == "edit"): ?>信息更新<?php else: ?>申请<?php endif; ?></title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/zepto.select.dialog.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/zepto.select.area.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/webuploader.css" />
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/jquery-1.10.2.min.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/webuploader.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/uploader.js"></script><script type="text/javascript" src="/somegood/Public/scripts/js/region.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.select.dialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.select.area.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/zepto.validateForm.js"></script><script type="text/javascript" src="/somegood/Public/statics/mobile/js/common.js"></script>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/mobileSelect.css">
    <script src="/somegood/Public/scripts/js/mobileSelect.js" type="text/javascript"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
    <script type="text/javascript">
        var uploadType = 'image';
        $(function(){
            $(".upload-album").InitUploader({ btntext: "批量上传", multiple: true, sendurl: "<?php echo U('Mobile/Member/uploadFile');?>", swf: "/116cs/Public/JS/webuploader/uploader.swf" });
        })
    </script>
</head>
<body>
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="javascript:history.back();" class="icons icon-back"></a>
    </div>
    <h2 class="title">加盟店<?php if(($action) == "add"): ?>申请<?php else: ?>信息查看<?php endif; ?></h2>
    <!--返回按钮-->
</div>
<!--商品筛选-->
<div style="padding-top: 0.86rem;">
    <?php if(empty($info)): ?><div class="welcome-join">
            <div class="title">欢迎加入三好连锁</div>
            <p>线上提交加入申请，三好连锁平台审核成功后即有您的店铺信息</p>
        </div><?php endif; ?>
    <form url="<?php echo U('Mobile/Store/apply');?>" method="post" class="shop-apply-form form-box page-box" id="shopApplyForm">
        <div class="examine-status-box animate <?php if($isStore == 1): ?>examined<?php elseif($info): ?>examining<?php else: endif; ?>"></div>
        <div class="title">基本信息</div>
        <dl>
            <dt>店铺名称</dt>
            <dd><?php if(empty($info)): ?><input type="text" placeholder="输入店铺名称" class="required" name="data[store_name]" value="<?php echo ($info["store_name"]); ?>" required><?php else: ?><span class="txt"><?php echo ($info["store_name"]); ?></span><?php endif; ?></dd>
        </dl>
        <dl>
            <dt>所在位置</dt>
            <dd>
                <?php if(empty($info)): ?><input type="text" name="area_text" id="areaText" data-value="<?php echo ($info["area_list"]); ?>" class="required" placeholder="点击选择区域 "
                           value="<?php if($info['area_id'] > 1): echo ($info["region"]); endif; ?>" />
                    <input type="hidden" name="data[area_list]"  class="required" value="<?php echo ($info["area_list"]); ?>" />
                    <?php else: ?><span class="txt"><?php echo ($info["region"]); ?></span><?php endif; ?>
            </dd>
        </dl>
        <script>
            //初始化所有城市JS
            var selectArea = new MobileSelectArea();
            var defaultArea = "<?php echo ($info["area_list"]); ?>";
            var areaName = "<?php echo ($info["region"]); ?>";
            var areaArr = ['','',''];
            if(areaName!=""){
                areaArr = areaName.split(' ');
            }
            selectArea.init({trigger:'#areaText',data:cityJson,default:0,text:areaArr,value:defaultArea,position:"bottom"});
        </script>

        <dl>
            <dt>详细地址</dt>
            <dd><?php if(empty($info)): ?><input type="text" placeholder="输入店铺详细地址" name="data[address]" class="required" value="<?php echo ($info["address"]); ?>" required><?php else: ?><span class="txt"><?php echo ($info["address"]); ?></span><?php endif; ?></dd>
        </dl>
        <dl>
            <dt>店铺类型</dt>
            <dd>
                <div id="trigger">
                    <?php if(empty($info)): ?><span class="trigger-span" obj-id="goods-category-box" id="goods-category-span"><?php if($info["store_type"] != null): ?><span class="txt"><?php echo ($info["type_name"]); ?></span><?php else: ?>请选择店铺类型<?php endif; ?></span></div>
                <input type="hidden" name="data[store_type]" id="storeType" value="<?php echo ($info["store_type"]); ?>">
                <?php else: ?><span class="txt"><?php echo ($info["type_name"]); ?></span><?php endif; ?>
            </dd>
        </dl>
        <dl>
            <dt>主营产品</dt>
            <dd><?php if(empty($info)): ?><input type="text" placeholder="输入店铺主营产品" name="data[products]" class="required" value="<?php echo ($info["products"]); ?>" required><?php else: ?><span class="txt"><?php echo ($info["products"]); ?></span><?php endif; ?></dd>
        </dl>
        <dl>
            <dt>店铺电话</dt>
            <dd><?php if(empty($info)): ?><input type="text" placeholder="输入店铺电话" name="data[phone]" value="<?php echo ($info["phone"]); ?>" required><?php else: ?><span class="txt"><?php echo ($info["phone"]); ?></span><?php endif; ?></dd>
        </dl>
        <dl>
            <dt>营业时间</dt>
            <dd><?php if(empty($info)): ?><input type="text" placeholder="营业时间,如8:00-16:00" name="data[business_hours]" value="<?php echo ($info["business_hours"]); ?>" required><?php else: ?><span class="txt"><?php echo ($info["business_hours"]); ?></span><?php endif; ?></dd>
        </dl>
        <dl>
            <dt>手机号</dt>
            <dd><?php if(empty($info)): ?><input type="text" title="手机号格式不正确" pattern="^1[3-9]\d{9}$" required placeholder="输入你的手机号" class="required mobile" name="data[mobile]" value="<?php echo ($info["mobile"]); ?>"><?php else: ?><span class="txt"><?php echo ($info["mobile"]); ?></span><?php endif; ?></dd>
        </dl>
        <dl>
            <dt>真实姓名</dt>
            <dd><?php if(empty($info)): ?><input type="text" placeholder="输入你的真实姓名" class="required" name="data[owner]" value="<?php echo ($info["owner"]); ?>"><?php else: ?><span class="txt"><?php echo ($info["owner"]); ?></span><?php endif; ?></dd>
        </dl>
        <script type="text/javascript">
            // 添加全局站点信息
            var BASE_URL = '/webuploader';
        </script>
        <input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>">
        <input type="hidden" name="action" value="<?php echo ($action); ?>">
        <?php if(empty($info)): ?><button type="submit" class="apply-btn" id="phoneLoginBtn"><?php if(($action) == "edit"): ?>更新<?php else: ?>申请<?php endif; ?></button>
            <p class="txt">点击申请，表示您已阅读并同意<a class="agreement" id="agreement" href="<?php echo U('Mobile/Help/agreement');?>">《用户注册协议》</a></p>
            <?php else: ?>
            <dl>
                <dt>相册</dt>
                <dd>
                    <ul class="tip-ul">
                        <?php if(is_array($plist)): $i = 0; $__LIST__ = $plist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?><li><a href="javascript:;"><img data-original="/somegood/Public/uploads/<?php echo ($p); ?>_c200x200" src="/somegood/Public/statics/mobile/images/default_img.png" alt="<?php echo ($rlist["$key"]); ?>"/></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </dd>
            </dl><?php endif; ?>
    </form>
</div>
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
    $("#shopApplyForm").validateForm({isAutoClose: true,closeTime: 1500,return_url:"<?php echo ($returnUrl); ?>"});
    <?php if(empty($info)): ?>/*加盟店申请 分类选择js*/
        $(function(){
            //给Id绑定事件
            var Idname="#trigger";
            //弹出插件
            selectOption(Idname);
            //点击弹出插件
            function selectOption(Idname){
                $(Idname).on('touchstart',function(){
                    var mobileSelect2 = new MobileSelect({
                        trigger: Idname,
                        title: '店铺分类',
                        wheels: [
                            {
                                data:
                                    eval(<?php echo ($storeTypeList); ?>)
                            },
                        ],
                        position:[0], //初始化定位 打开时默认选中的哪个 如果不填默认为0
                        transitionEnd:function(indexArr, data){
                            console.log(data);
                        },
                        cancel:function(indexArr, data){
                            $(".mobileSelect").remove();
                            if ($(Idname).next().hasClass('select')) {
                                $(Idname).children("a").removeClass('select');
                            }
                        },
                        callback:function(indexArr, data){
                            $("#storeType").val((data[0].id));
                            $(".mobileSelect.mobileSelect-show").remove();
                        }
                    });
                })
            }
        })<?php endif; ?>
</script>
</body>
</html>