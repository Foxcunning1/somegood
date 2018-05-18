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
        <div class="change-city fl"><span>深圳</span><span>[切换城市]</span>
            <div class="city-list-box">
                <ul>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                    <li>上海</li>
                </ul>
            </div>
        </div>
        <div class="fr clearfix">
            <?php if(empty($_SESSION['shop']['id'])): ?><a href="<?php echo U('Shop/Login/index');?>" class="m">登录</a>
                <a href="<?php echo U('Shop/Login/register');?>" class="m">注册</a>
                <?php else: ?>
                <a href="javascript:;">我的订单</a>
                <span class="line">/</span>
                <a href="<?php echo U('Shop/Users/index');?>">个人中心<span class="pointer"></span></a><?php endif; ?>
            <span class="line">/</span>
            <a href="<?php echo U('Shop/Cart/index');?>">购物车</a>
            <span class="line">/</span>
            <a href="javascript:;">客服服务<span class="pointer"></span></a>
            <span class="line">/</span>
            <a href="javascript:;">手机逛3好</a>
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
      <script type="text/javascript">
	    $(function(){
			//初始化表单
			AjaxInitForm('#mobileForm', '#btnSubmit', 1,"#tUrl");
		});
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
			          <a href="<?php echo U('Shop/Users/contact',array('type'=>'email'));?>">更换邮箱</a>
			          <a class="selected" href="<?php echo U('Shop/Users/contact',array('type'=>'mobile'));?>">更换手机</a>
			        </p>
			      </div>
			      <div class="u-tab-content">
			        <div class="title-div">
			          <strong>更换手机</strong>
			        </div>
				        <form url="<?php echo U('Shop/Users/mobile');?>" id="mobileForm" name="mobileForm">
				        <div class="form-box">
				          <dl>
				            <dt>已绑定手机号：</dt>
				            <dd><?php echo ($info['mobile']); ?></dd>
				          </dl>
							<dl>
								<dt>验证码：</dt>
								<dd>
									<img class="code-img" id="code_img" onclick="this.src='<?php echo U('Shop/Code/verify');?>?r='+Math.random();" src="<?php echo U('Shop/Code/verify');?>" alt="图片验证码" />
									<input type="text" name="vcode" id="vcode" dataType="*" maxlength="4" placeholder="请输入验证码" class="input" />
								</dd>
							</dl>
				          <dl>
				            <dt>解绑验证类型：</dt>
				            <dd>
				            	<select name="data[codeType]" id="codeType" class="select" datatype="*" sucmsg=" " style="float:left;">
				            	    <option value="1">手机</option>
				            		<?php if($info['email'] != ''): ?><option value="0">邮箱</option>
										<input type="hidden" name="data[old_email]" value="<?php echo ($info['email']); ?>"><?php endif; ?>
				            	</select>
				            	<input type="button" class="bBtn" id="sendBtn" value="发送解绑验证码" />
				            </dd>
				          </dl>
				          <dl>
				            <dt>解绑验证码：</dt>
				            <dd>
				              <input name="data[sendCode]" id="txtSendCode" type="text" class="input txt small" style="float:left;" datatype="*" maxlength="6"  nullmsg="请输入解绑码" errormsg="解绑码不能为空" sucmsg=" " />
				            </dd>
				          </dl>
				          <dl>
				            <dt>新手机：</dt>
				            <dd>
				              <input name="data[new]" id="txtSend" type="text" class="input txt" style="float:left" datatype="*"  ajaxurl="<?php echo U('Shop/Login/checkMobile');?>" nullmsg="请输入新的手机号码" errormsg="手机号码不能为空" sucmsg=" " />
				              <input type="button" class="bBtn"  id="sendBtn1" value="获取绑定验证码" onclick="sendType('Mobile')" style="margin-top: -3px;"/>
								<input type="hidden" name="data[old_mobile]" value="<?php echo ($info["mobile"]); ?>">
				            </dd>
				          </dl>
				          <dl>
				            <dt>绑定验证码：</dt>
				            <dd>
				              <input name="data[bindCode]" id="txtBindCode" type="text" class="input txt small" style="float:left;" datatype="*" maxlength="6" nullmsg="请输入绑定验证码" errormsg="绑定验证码不能为空" sucmsg=" " />
				            </dd>
				          </dl>
				          <dl>
				            <dt></dt>
				            <dd>
				              <input name="btnSubmit" id="btnSubmit" type="submit" class="btn btn-success" value="确认修改" />
				              <input type="hidden" id="tUrl" name="tUrl" value="<?php echo U('Shop/Users/mobile');?>" />
				            </dd>
				          </dl>
				        </div>
				        </form>
			      </div>
			      <!--/修改资料-->
			</div>
		</div>
	</div>
	<!--end vipCenterContent-->
	<div class="clear"></div>
	<script type="text/javascript">
	//点击弹出发送短信验证码
	<?php if($info['mobile'] != ''): ?>$("#sendBtn").click(function(){
            var vcode=$("#vcode").val();
            var codeType=$("#codeType").val();
            if(!$(this).hasClass("disabled")&&vcode!=""){
                if(codeType==1){
                    var mobile = "<?php echo ($info["mobile"]); ?>";
                    sendSmsCode(mobile,0);
				}else{
                    var email ="<?php echo ($info["email"]); ?>";
                    sendEmailCode(email);
				}

            }else{
                var d = dialog({content:"请输入验证码！"}).show();
                setTimeout(function () {
                    d.close().remove();
                }, 1000);
			}
        })


		function sendType(type){
			var vcode=$("#vcode").val();
			var value = $("#txtSend").val()
			if(!$(this).hasClass("disabled")&&vcode!=""&&value!=""){
			    if(type=="Mobile"){
                    sendSmsCode(value,1);
				}else{
                    sendEmailCode(value);
				}
			}else{
                if(type=="Mobile"){
                    var d = dialog({content:"验证码或手机号不可为空！"}).show();
                }else{
                    var d = dialog({content:"验证码或邮箱不可为空！"}).show();
                }
				setTimeout(function () {
					d.close().remove();
				}, 1000);
			}
		}

		function sendSmsCode(mobile,codeType){
			var type   = 'bind';
			var vcode  = $("#vcode").val();
			if(mobile!=""){
				$.ajax({
					type: 'POST',
	     			url: "<?php echo U('Shop/Ajax/checkMobile');?>" ,
					data: 'param='+mobile,
					dataType: 'json',
					success: function(data) {
			          if(codeType == 0){
			              if(data.status =="n"){
                              sendMobileCode(mobile,type,vcode,codeType);
						  }else{
                              var d = dialog({content:"该手机号未注册！"}).show();
                              setTimeout(function () {
                                  d.close().remove();
                              }, 1000);
						  }
			         	}else{
                          if(data.status =="y"){
                              sendMobileCode(mobile,type,vcode,codeType);
                          }else{
                              var d = dialog({content:"该手机号已被绑定！"}).show();
                              setTimeout(function () {
                                  d.close().remove();
                              }, 1000);
                          }
					  }
			        }
				})
			}
		}

		function sendMobileCode(mobile,type,vcode,codeType){
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
                        if(codeType==1){
                            var btnObj = "#sendBtn1";
                            var btnObjTxt = "获取绑定验证码";
                        }else{
                            var btnObj = "#sendBtn";
                            var btnObjTxt = "发送解绑验证码";
                        }
                        setCountDowntime(btnObj,btnObjTxt);
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

		}<?php endif; ?>
		//点击发送邮箱验证码
		function sendEmailCode(email){
			var email = email;
            var vcode  = $("#vcode").val();
			if(email!=""){
				$.ajax({
					type: 'POST',
	     			url: "<?php echo U('Shop/Ajax/sendEmailVerify');?>",
					data: {
					    email:email,
						type:3,
						vcode:vcode
					},
					dataType: 'json',
					success: function(data) {
			          var d = dialog({content:data.info}).show();
			          setTimeout(function () {
					  d.close().remove();
					  }, 1500); 
			        }
				})
			}
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
                $(ob).val("发送解绑验证码");
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
	</script>

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