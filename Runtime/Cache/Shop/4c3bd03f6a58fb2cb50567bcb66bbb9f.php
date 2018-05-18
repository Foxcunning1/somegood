<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>3好连锁店商城首页</title>
    <meta name="keywords" content="<?php echo ($web_keywords); ?>"/>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/public.css">
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/user.css">
    <link type="text/css" rel="stylesheet" href="/somegood/Public/scripts/jquery/jquery.selectlist.css" >
    <link type="text/css" rel="stylesheet" href="/somegood/Public/scripts/artdialog/ui-dialog.css">
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/shop/js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/js/share-code.js"></script>
</head>
<body>
<div class="header">
    <div class="h-top">
    <div class="w1200">
        <div class="fr clearfix">
            <?php if(empty($_SESSION['shop']['id'])): ?><a href="<?php echo U('Shop/Login/index');?>" class="m">登录</a>
                <a href="<?php echo U('Shop/Login/register');?>" class="m">注册</a>
                <?php else: ?>
                <a href="<?php echo U('Shop/UserOrder/orderlist');?>">我的订单</a>
                <span class="line">/</span>
                <a href="<?php echo U('Shop/Users/index');?>">个人中心<span class="pointer"></span></a><?php endif; ?>
            <span class="line">/</span>
            <a href="<?php echo U('Shop/Cart/index');?>">购物车</a>
            <span class="line">/</span>
            <a href="javascript:;">客服服务<span class="pointer"></span></a>
            <span class="line">/</span>
            <a href="<?php echo U('Mobile/Index/index');?>">手机逛3好</a>
            <span class="line">/</span>
            <a href="javascript:;">更多<span class="pointer"></span></a>
        </div>
    </div>
</div>

    <div class="bg-box">
        <div class="w1200">
            <div class="h-middle clearfix">
                <a href="<?php echo U('Shop/Index/index');?>" class="fl"><img src="/somegood/Public/statics/shop/images/logo.png" alt="logo" class="logo"/></a>
                <div class="user-nav fr">
                    <ul>
                        <li><a href="<?php echo U('Shop/Index/index');?>">首页</a></li>
                        <li><a href="javascript:;">今日上新</a></li>
                        <li><a href="javascript:;">全网秒杀</a></li>
                        <li><a href="javascript:;">品牌投放</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<!--vipCenterContent start-->
<script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/js/region.js"></script>
<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/shop/js/WdatePicker.js"></script>
<script type="text/javascript">
    $(function () {
        //初始化表单
        AjaxInitForm('#infoForm', '#btnSubmit', 1);
    });
    var url = "<?php echo U('Shop/Users/getRegionList');?>";
    /*
     *城市三级联动
     */
    function getRegionList(objBox,typId,url){
        if(typId!=''){
            $.ajax({
                url:url,
                type:"post",
                dataType:'json',
                data:{id:typId},
                success:function(data){
                    $("#"+objBox).html('<option value="" selected>请选择...</option>');
                    if(objBox=="selCities"){
                        $("#selDistricts").html('<option value="" selected>请选择...</option>');
                    }
                    $.each(data.data,function(idx,item){
                        $("<option value="+item.id+">"+item.name+"</option>").appendTo($("#"+objBox));
                    });
                }
            });
        }
    }
</script>
<div id="vipCenterContent" class="user-center">
    <div class="w1200">
        <div class="left-menu-list fl" id="leftMenu">
            				<ul>
					<li class="title">个人中心</li>
					<li><a <?php if($mnav == 1): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/setting',array('action'=>'info'));?>">个人资料</a></li>
					<li><a <?php if($mnav == 2): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/setting',array('action'=>'edit'));?>">资料修改</a></li>
				</ul>
				<ul>
					<li class="title">我的订单</li>
					<li><a <?php if($mnav == 17): ?>class="cur"<?php endif; ?> href="<?php echo U('userOrder/orderlist');?>">订单管理</a></li>
				</ul>
				<ul>
					<li class="title">我的资产</li>
					<?php if($info['is_salesman'] == 1): ?><li><a <?php if($mnav == 12): ?>class="cur"<?php endif; ?> href="<?php echo U('Account/commission');?>">提成明细</a></li>
					<?php else: ?>
					<li><a <?php if($mnav == 13): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/amount');?>">资金查看</a></li>
					<li><a <?php if($mnav == 14): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/coupon',array('pageType'=>'can_use'));?>">优惠券</a></li>
					<li><a <?php if($mnav == 12): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/commission');?>">提成明细</a></li><?php endif; ?>
				</ul>
				<ul>
					<li class="title">我的收藏</li>
					<li><a <?php if($mnav == 16): ?>class="cur"<?php endif; ?> href="<?php echo U('Shop/Users/favorites');?>">我的收藏夹</a></li>
				</ul>
        </div>
        <div class="right-show fr">
            <div class="u-tab-head">
                <p>
                    <a class="selected" href="<?php echo U('Shop/Users/setting');?>">个人资料</a>
                    <a href="<?php echo U('Shop/Users/password');?>">修改密码</a>
                </p>
            </div>
            <div class="u-tab-content">
                <div class="title-div">
                    <strong>个人资料</strong>
                </div>
                <?php if(($action) == "edit"): ?><form name="infoForm" id="infoForm" url="<?php echo U('Shop/Users/setting');?>">
                        <div class="form-box">
                            <?php if($info["user_name"] != ''): ?><dl>
                                    <dt>用户名：</dt>
                                    <dd><?php echo ($info["user_name"]); ?></dd>
                                </dl>
                                <?php else: ?>
                                <dl>
                                    <dt><i>*</i>用户名：</dt>
                                    <dd><input name="data[user_name]" id="txtUserName" type="text" class="input txt"
                                               value="" sucmsg=" " datatype="*5-20" ajaxurl="<?php echo U('Login/checkUserName');?>"/>
                                    </dd>
                                </dl><?php endif; ?>
                            <dl>
                                <dt>昵称：</dt>
                                <dd><input name="data[nick_name]" id="txtNickName" type="text" class="input txt"
                                           value="<?php echo ($info["nick_name"]); ?>" sucmsg=" "/></dd>
                            </dl>
                            <dl>
                                <dt><i>*</i>性别：</dt>
                                <dd>

                                    <label class="radio"><input name="data[sex]" type="radio" value="1"
                                        <?php if($info['sex'] == 1): ?>checked="checked"<?php endif; ?>
                                        />男</label>
                                    <label class="radio"><input name="data[sex]" type="radio" value="2"
                                        <?php if($info['sex'] == 2): ?>checked="checked"<?php endif; ?>
                                        />女</label>
                                    <label class="radio"><input name="data[sex]" type="radio" value="0"
                                        <?php if($info['sex'] == 0): ?>checked="checked"<?php endif; ?>
                                        datatype="*" sucmsg=" " />保密</label>

                                </dd>
                            </dl>
                            <dl>
                                <dt>生日：</dt>
                                <dd>
                                    <input name="data[birthday]" id="txtBirthday" type="text" class="input txt"
                                           maxlength="30" value="<?php if( $info['birthday'] != 0): echo (date('Y-m-d',$info["birthday"])); endif; ?>"
                                    onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" />
                                </dd>
                            </dl>
                            <dl>
                                <dt><i>*</i>邮箱：</dt>
                                <dd><?php if($info['email'] != ''): echo hide_str_replace_star($info['email']);?><a class="aBtn" href="<?php echo U('Shop/Users/contact',array('type'=>'email'));?>">更换邮箱</a><?php else: ?><input name="data[email]" id="txtEmail" type="text" class="input txt" value="<?php echo ($info["email"]); ?>" datatype="e" ajaxurl="<?php echo U('Shop/Login/checkEmail');?>" sucmsg=" " style="float:left;" /><?php endif; ?></dd>
                            </dl>
                            <?php if($info['mobile'] == ''): ?><dl>
                                    <dt>验证码：</dt>
                                    <dd>
                                        <img class="code-img" id="code_img" onclick="this.src='<?php echo U('Shop/Code/verify');?>?r='+Math.random();" src="<?php echo U('Shop/Code/verify');?>" alt="图片验证码" />
                                        <input type="text" name="vcode" id="vcode" dataType="*" maxlength="4" placeholder="请输入验证码" class="input" />
                                    </dd>
                                </dl><?php endif; ?>
                            <dl>
                                <dt><i>*</i>手机：</dt>
                                <dd>
                                    <?php if($info['mobile'] != ''): echo hide_str_replace_star($info['mobile']);?><a
                                            class="aBtn" href="<?php echo U('Shop/Users/contact',array('type'=>'mobile'));?>">更换手机</a>
                                        <?php else: ?>
                                        <input name="data[mobile]" id="txtMobile" type="text" class="input txt"
                                               value="<?php echo ($info["mobile"]); ?>" ajaxurl="<?php echo U('Shop/Login/checkMobile');?>" datatype="*"
                                               sucmsg=" "/><input type="button" class="bBtn" id="sendBtn" value="绑定手机"
                                                                  onclick="sendSmsCode();"/><?php endif; ?>
                                </dd>
                            </dl>
                            <?php if($info['mobile'] == ''): ?><dl>
                                    <dt>绑定验证码：</dt>
                                    <dd><input name="data[bindCode]" id="txtBindCode" type="text" class="input txt small"
                                               value="" datatype="*" maxlength="6" sucmsg=" "/></dd>
                                </dl><?php endif; ?>
                            <dl>
                                <dt><i>*</i>所属地区：</dt>
                                <dd>
                                    <select name="province" id="province"
                                            onchange="getRegionList('selCities',this.value,url)"
                                            class="select">
                                        <option value="0">请选择</option>
                                        <?php if(is_array($provinceList)): $i = 0; $__LIST__ = $provinceList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"
                                            <?php if($area_info[0] == $v['id']): ?>selected="selected"<?php endif; ?>
                                            ><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    <select name="city" id="selCities" class="select"
                                            onchange="getRegionList('selDistricts',this.value,url)">
                                        <option value="0">请选择</option>
                                    </select>
                                    <select name="data[area_id]" id="selDistricts" class="select" datatype="*">
                                        <option value="0">请选择</option>
                                    </select>
                                </dd>
                            </dl>
                            <dl>
                                <dt>详细地址：</dt>
                                <dd><input name="data[address]" id="txtAddress" type="text" class="input wide"
                                           maxlength="250" value="<?php echo ($info["address"]); ?>"/></dd>
                            </dl>
                            <dl>
                                <dt>在线QQ：</dt>
                                <dd><input name="data[qq]" id="txtQQ" type="text" class="input txt" maxlength="20"
                                           value="<?php echo ($info["qq"]); ?>"/></dd>
                            </dl>

                            <dl>
                                <dt></dt>
                                <dd><input name="btnSubmit" id="btnSubmit" type="submit" class="btn btn-success"
                                           value="确认修改"/></dd>
                            </dl>
                        </div>
                    </form>
                    <?php else: ?>
                    <div class="form-box">

                            <dl>
                                <dt>用户名：</dt>
                                <dd><?php echo ($info["user_name"]); ?></dd>
                            </dl>

                        <dl>
                            <dt>昵称：</dt>
                            <dd><?php echo ($info["nick_name"]); ?></dd>
                        </dl>
                        <dl>
                            <dt>性别：</dt>
                            <dd>
                                    <?php if($info['sex'] == 1): ?>男<?php endif; ?>
                                    <?php if($info['sex'] == 2): ?>女<?php endif; ?>
                                    <?php if($info['sex'] == 0): ?>保密<?php endif; ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>生日：</dt>
                            <dd>
                                <?php echo (date('Y-m-d',$info["birthday"])); ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>邮箱：</dt>
                            <dd><?php echo hide_str_replace_star($info['email']);?></dd>
                        </dl>
                        <dl>
                            <dt>手机：</dt>
                            <dd><?php echo hide_str_replace_star($info['mobile']);?></dd>
                        </dl>
                        <dl>
                            <dt>所属地区：</dt>
                            <dd><?php echo ($area["2"]); echo ($area["3"]); ?></dd>
                        </dl>
                        <dl>
                            <dt>详细地址：</dt>
                            <dd><?php echo ($info["address"]); ?></dd>
                        </dl>
                        <dl>
                            <dt>在线QQ：</dt>
                            <dd><?php echo ($info["qq"]); ?></dd>
                        </dl>
                    </div><?php endif; ?>
            </div>
            <!--/修改资料-->
        </div>
    </div>
</div>
<!--end vipCenterContent-->
<div class="clear"></div>
<?php if(($action) == "edit"): ?><script type="text/javascript">
        //点击弹出发送短信验证码
        function sendSmsCode(){
            var type   = 'bind';
            var vcode  = $("#vcode").val();
            var mobile = $("#txtMobile").val();
            if(mobile!=""){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo U('Shop/Ajax/checkMobile');?>" ,
                    data: 'param='+mobile,
                    dataType: 'json',
                    success: function(data) {
                        if(data.status =="y"){
                            sendMobileCode(mobile,type,vcode);
                        }else{
                            var d = dialog({content:"该手机号已被绑定！"}).show();
                            setTimeout(function () {
                                d.close().remove();
                            }, 1000);
                        }
                    }
                })
            }
        }

        function sendMobileCode(mobile,type,vcode){
            $.ajax({
                type: 'POST',
                url: "<?php echo U('Shop/Ajax/sendSms');?>" ,
                data: {
                    'mobile':mobile,
                    'type':type,
                    'vcode':vcode
                },
                dataType: 'json',
                success: function(data) {
                    if(data.status==1){
                        setCountDowntime("#sendBtn","发送验证码");
                    }
                    var d = dialog({
                        width: 450,
                        height: 130,
                        title: '手机验证码',
                        lock: true,
                        backdropOpacity:0.4,
                        content:data.info,
                        onclose: function (){

                        }
                    }).showModal();
                    d.addEventListener('cancelBtn', function(){
                        d.close()
                    });
                }
            })

        }

        /**
         *验证码倒计时
         *@param  countdown    倒计时设置时间
         *@param  obj          点击按钮
         *@param  objValue     按钮显示文本
         * */
        var countdown=120;
        function setCountDowntime(obj,objValue) {
            var ob = obj;
            if (countdown == 0) {
                $(ob).removeClass("disabled");
                $(ob).removeAttr("disabled");
                if(objValue!=""){
                    $(ob).val("发送验证码");
                }else{
                    $(ob).val(objValue);
                }
                countdown = 120;
                return;
            } else {
                $(ob).addClass("disabled");
                $(ob).attr("disabled", true);
                $(ob).val("重新发送(" + countdown + ")");
                countdown--;
            }
            setTimeout(function() {
                setCountDowntime(obj,objValue)
            },1000)
        }
    </script><?php endif; ?>

<div class="footer">
    <div class="f-top">
        <div class="w1200">
            <div class="f-top-t clearfix">
                <div class="item">
                    <i class="icons icon-item icon-item1 fl"></i>
                    <div class="text">
                        <span class="big">100%正品</span>
                        <span>正品保障 假一赔十</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item2 fl"></i>
                    <div class="text">
                        <span class="big">低价保障</span>
                        <span>缩减中间环节 确保低价</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item3 fl"></i>
                    <div class="text">
                        <span class="big">无忧退货</span>
                        <span>严格按照消法处理</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item4 fl"></i>
                    <div class="text">
                        <span class="big">发票保障</span>
                        <span>售卖商品可提供发票</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item5 fl"></i>
                    <div class="text">
                        <span class="big">满188包邮</span>
                        <span>部分特殊商品除外</span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="f-top-b clearfix">
                <div class="box-l fl">
                    <dl>
                        <dt>购物指南</dt>
                        <dd><a href="javasctipt:;">账号注册</a></dd>
                        <dd><a href="javasctipt:;">购物流程</a></dd>
                        <dd><a href="javasctipt:;">付款方式</a></dd>
                    </dl>
                    <dl>
                        <dt>售后服务</dt>
                        <dd><a href="javasctipt:;">先行赔付</a></dd>
                        <dd><a href="javasctipt:;">退款退货流程</a></dd>
                        <dd><a href="javasctipt:;">投诉建议</a></dd>
                    </dl>
                    <dl>
                        <dt>商城保障</dt>
                        <dd><a href="javasctipt:;">正品保障</a></dd>
                        <dd><a href="javasctipt:;">物流配送</a></dd>
                        <dd><a href="javasctipt:;">发票保障</a></dd>
                    </dl>
                    <dl>
                        <dt>商家服务</dt>
                        <dd><a href="<?php echo U('Store/Index/index');?>">店铺中心</a></dd>
                        <dd><a href="<?php echo U('Seller/Index/index');?>">卖家中心</a></dd>
                    </dl>
                </div>
                <div class="box-c fl">
                    <i class="icons icon-tel fl"></i>
                    <div class="tell">
                        <span class="phone-num">0755-23999161</span>
                        <span>工作时间：9:00 - 18:00</span>
                    </div>
                </div>
                <div class="box-r fr">
                    <i class="icons icon-coopration fl"></i>
                    <dl>
                        <dt>商家合作</dt>
                        <dd>错过天猫别再错过我们</dd>
                        <dd>三好连锁开启全新旅程！</dd>
                        <dd><a href="javascript:;" class="join-us">关于入驻 >> </a></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <div class="f-bottom">
        <div class="w1200">
            <div class="f-bottom-l fl">
                <p>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                </p>
                <p class="copy-right">备案号XXXXXXXXXXXXXXXXXXXXXXXXX号   Copyright © 2006 - 2017  三好连锁 版权所有</p>
                <p>
                    <img src="/somegood/Public/statics/shop/images/b_img_1.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_2.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_3.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_4.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_5.png" alt="" />
                </p>
            </div>
            <div class="f-bottom-r fr clearfix">
                <span class="fl">扫一扫二维码</span>
                <img src="/somegood/Public/statics/shop/images/erweima.png" alt="" class="erwerma fl"/>
            </div>
        </div>
    </div>
</div>
<script>
    /*迷你购物车出现隐藏*/
    $(".mini-cart-open").hover(function(){
        $(".mini-cart-box").show();
        $(".my-cart-line").show();
    },function(){
        $(".mini-cart-box").hide();
        $(".my-cart-line").hide();
    })
    $(".mini-cart-box").hover(function(){
        $(this).show();
        $(".my-cart-line").show();
    },function(){
        $(this).hide();
        $(".my-cart-line").hide();
    })
    $(".my-cart-line").hover(function(){
        $(this).show();
        $(".mini-cart-box").show();
    },function(){
        $(this).hide();
        $(".mini-cart-box").hide();
    })
    /*隐藏菜单显示*/
    $(".category-box-ul").find("li").hover(function(){
        $(this).find(".category-box-content").show().siblings("li").find(".category-box-content").hide();
    },function(){
        $(this).find(".category-box-content").hide();
    })
    //获取购物车商品
    function getCartGoods() {
        $.ajax({
            type: 'POST',
            url: "<?php echo U('Shop/Cart/getCartGoods');?>",
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    $(".cart-num").text(data.data.cartNum);
                    $(".goods-num").text(data.data.cartNum);
                    var totalMoney = 0;
                    if(data.data.cartNum>0){
                        var list = data.data.list;
                        var str = '';
                        $.each(list,function(index,v){
                            str +="<div class=\"item-box\" id=\"cartGoods_"+v.cart_id+"\">";
                            str +=" <div class=\"cart-img\"><a href=\"<?php echo U('Shop/Goods/goods');?>?id="+v.real_goods_id+"\"><img src=\"/somegood/Public/uploads/"+v.goods_thumb+"\" alt=\""+v.goods_title+"\" /></a></div>";
                            str +="<div class=\"cart-name\"><a href=\"<?php echo U('Shop/Goods/goods');?>?id="+v.real_goods_id+"\" title=\""+v.goods_title+"\">"+v.goods_title+"</a></div>";
                            str +="<div class=\"cart-info\">";
                            str +="<div class=\"cart-price\">￥"+v.goods_price+" * "+v.counts+"件</div>";
                            str +="<a href=\"javascript:;\" class=\"cart-delete\"  id=\"delCart_"+v.cart_id+"\" onclick='delCart("+v.cart_id+","+v.goods_price+","+v.counts+");'>删除</a>";
                            str +="</div>";
                            str +=" <div class=\"clear\"></div>";
                            str +=" </div>";
                            totalMoney += v.goods_price*v.counts;
                        })
                        $(".items-list").html(str);
                        $(".cart-total-money").text(totalMoney);
                    }
                } else {
                    $(".cart-num").text("0");
                    $(".goods-num").text("0");
                    $(".cart-total-money").text('0');
                }
            }
        })
    }
    $(function () {
        getCartGoods();
    })
    //删除购物车
    function delCart(cartId,price,counts) {
        var curtTotalMoney=$(".cart-total-money").text()-price*counts;
        var curtCounts=$(".goods-num").text()-counts;
        $.ajax({
            type:"POST",
            dataType:'json',
            url:"<?php echo U('Mobile/Cart/delCartGoods');?>",
            data:{
                cart_id:cartId
            },
            success: function(data){
                $("#delCart_"+cartId).text("正在删除");
                window.setTimeout(function(){
                    $("#cartGoods_"+cartId).remove();
                    $(".cart-num").text(curtCounts);
                    $(".goods-num").text(curtCounts);
                    $(".cart-total-money").text(curtTotalMoney);
                },1000);
            }
        });
    }
</script>
</body>
</html>