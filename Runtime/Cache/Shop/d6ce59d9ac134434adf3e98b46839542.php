<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/shop/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/shop/css/user.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>

</head>
<body>
    <div class="login-header">
        <div class="w1200">
            <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/login_logo.png" alt="login_logo" /></a>
        </div>
    </div>
	<div class="container">
        <div class="login-silder">
            <div class="login-silder-wrap">
                <div class="login-wrap">
                    <div class="login-title"><ul><li class="curr"><a href="javascript:;">手机动态码登录</a></li><li><a href="javascript:;">账号密码登录</a></li></ul></div>
                    <div class="login-box">
                        <form name="loginForm" id="loginForm" url="<?php echo U('Shop/Login/mobileLogin');?>" style="display: block">
                            <div class="login-items-box">
                                <div class="login-items-option">
                                    <input type="text" name="mobile" id="mobile" dataType="/^1[3-9]\d{9}$/" maxlength="11" placeholder="请输入手机号" class="input" />
                                    <i class="option-title">手机号码</i>
                                </div>
                                <div class="login-items-option">
                                    <input type="text" name="vcode" id="vcode" dataType="*" maxlength="4" placeholder="请输入验证码" class="input" />
                                    <i class="option-title">验证码</i>
                                    <img class="code-img" id="code_img" onclick="this.src='<?php echo U('Shop/Code/verify');?>?r='+Math.random();" src="<?php echo U('Shop/Code/verify');?>" alt="图片验证码" />
                                </div>
                                <div class="login-items-option">
                                    <input type="text" name="verifyCodeGet" dataType="n" maxlength="6" placeholder="请输入短信验证码" class="input" />
                                    <i class="option-title">短信验证码</i>
                                    <a class="smsBtn" id="sendBtn" href="javascript:;">获取短信验证码</a>
                                </div>
                                <div class="login-items-option" style="margin-bottom: 0;">
                                    <input type="submit" class="submitBtn" id="btnSubmit" value="立即登录" />
                                </div>
                            </div>
                        </form>
                        <form id="accountForm" class="account-login-form" url="<?php echo U('Shop/Login/login');?>" method="post">
                            <div class="login-items-box">
                                <div class="login-items-option">
                                    <input type="text" name="mobile" id="userName" dataType="s" class="input" placeholder="请输入用户名"/>
                                    <i class="user-name-icon icons"></i>
                                </div>
                                <div class="login-items-option">
                                    <input type="password" dataType="s" id="password" name="password" class="input" placeholder="请输入密码"/>
                                    <i class="lock-icon icons"></i>
                                </div>
                                <div class="login-items-option clearfix">
                                    <label for="remember" class="fl"><input type="checkbox" id="remember" name="remember"/>记住密码</label>
                                    <span class="fr"><a href="<?php echo U('Shop/Login/register');?>" class="forget-code">立即注册</a></span>
                                    <div class="clear"></div>
                                </div>
                                <div class="login-items-option" style="margin-bottom: 0;">
                                    <input type="submit" class="submitBtn" id="accountBtnSubmit" value="立即登录" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="login-footer">
        <div class="w1200">
            <div class="friend-links">
                <a href="javascript:;">友情链接</a>
                <span>|</span>
                <a href="javascript:;">友情链接</a>
                <span>|</span>
                <a href="javascript:;">友情链接</a>
                <span>|</span>
                <a href="javascript:;">友情链接</a>
                <span>|</span>
                <a href="javascript:;">友情链接</a>
            </div>
            <div class="copy-right">Copyright © 2006 - 2017  三好连锁 版权所有</div>
        </div>
    </div>
    <script type="text/javascript">
        //初始化验证表单
        $(function(){
            $(".login-title").find("li").click(function(){
                $(this).addClass("curr").siblings().removeClass("curr");
                $(".login-box").find("form").eq($(this).index()).show().siblings().hide();
            })
            $("#loginForm").Validform({
                tiptype:3,
                callback:function(form){
                    //AJAX提交表单
                    $(form).ajaxSubmit({
                        beforeSubmit: showRequest,
                        success: showResponse,
                        error: showError,
                        url: $("#loginForm").attr("url"),
                        type: "post",
                        dataType: "json",
                        timeout: 60000
                    });
                    return false;
                }
            });
            $("#accountForm").Validform({
                tiptype:3,
                callback:function(form){
                    //AJAX提交表单
                    $(form).ajaxSubmit({
                        beforeSubmit: showRequest,
                        success: showResponse,
                        error: showError,
                        url: $("#accountForm").attr("url"),
                        type: "post",
                        dataType: "json",
                        timeout: 60000
                    });
                    return false;
                }
            });
            $("#accountForm").Validform({tiptype:4});
            //表单提交前
            function showRequest(formData, jqForm, options) {
                $("#btnSubmit").val("正在提交...")
                $("#btnSubmit").prop("disabled", true);
            }
            //表单提交后
            //表单提交后(传统登录)
            function showResponse(data, textStatus) {
                if (data.status == 1) {
                    //成功
                    var d = dialog({content:data.info}).show();
                    var type="<?php echo ($type); ?>";
                    window.setTimeout(function(){
                        d.close().remove();
                        location.href = "<?php echo U('Shop/Index/index');?>";
                    },1000);
                    $("#btnSubmit").val("立即登录")
                    $("#btnSubmit").prop("disabled", false);
                }else{
                    //失败
                    var d = dialog({content:data.info}).show();
                    window.setTimeout(function(){
                        d.close().remove();
                    },1000);
                }
            }
            //表单提交出错
            function showError(XMLHttpRequest, textStatus, errorThrown) {
                dialog({title:'提示', content:"状态：" + textStatus + "；出错提示：" + errorThrown, okValue:'确定', ok:function (){}}).showModal();
                $("#btnSubmit").val("重新提交");
                $("#btnSubmit").prop("disabled", false);
            };

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
                        $(ob).val("获取短信验证码");
                    }else{
                        $(ob).val(objValue);
                    }
                    countdown = 120;
                    return;
                } else {
                    $(ob).addClass("disabled");
                    $(ob).attr("disabled", true);
                    $(ob).text("重新发送(" + countdown + ")");
                    countdown--;
                }
                setTimeout(function() {
                    setCountDowntime(obj,objValue)
                },1000)
            }
            /**ajax发送短信
             * @Param         int        mobile       手机号
             * @Param         string     type         短信类型
             * @Param         url        ajaxUrl      发送短信的ajaxurl
             * @Param         obj        btnObj       被绑定对象
             * @Param         string     btnObjTxt    绑定对象默认文本
             * */
            function ajaxSendSms(mobile,type,ajaxUrl,btnObj,btnObjTxt){
                $.ajax({
                    type: 'POST',
                    url: ajaxUrl,
                    dataType: 'json',
                    data:{
                        'mobile':mobile,
                        'type':type,
                        'vcode':$("#vcode").val(),
                    },
                    success:function(data){
                        if(data.status==1){
                            setCountDowntime(btnObj,btnObjTxt);
                            var d = dialog({content:data.info}).show();
                            setTimeout(function () {
                                d.close().remove();
                            }, 2000);
                        }else{
                            var d = dialog({content:data.info}).show();
                            setTimeout(function () {
                                d.close().remove();
                            }, 2000);
                        }
                    }
                })
            }
            /**发送短信
             * @Param         obj        obj          目标对象
             * @Param         string     type         短信类型
             * @Param         url        sendSmsUrl   发送短信的url
             * @Param         obj        btnObj       被绑定对象
             * @Param         string     btnObjTxt    绑定对象默认文本
             * @Param         bool       checkType    验证类型，0为不能重复(注册)，1为重复(即：登录),2为前两种条件都可以(即：用户手机号绑定第三方登录)
             * @Param         url        checkUrl     如果需要验证，则为验证的url
             */
            function sendSms(obj,type,sendSmsUrl,btnObj,btnObjTxt,checkType,checkUrl){
                if(typeof($(obj))!="undefined"){
                    var mobile = $(obj).val();
                    //正则验证手机号码
                    var myreg = /^1[3-9]\d{9}$/;
                    if(!myreg.test(mobile)){
                        var d = dialog({content:"手机号码格式有误！"}).show();
                        setTimeout(function () {
                            d.close().remove();
                        }, 2000);
                        return false;
                    }
                    if(checkType!=2){
                        //需要验证手机号是否已重复
                        $.ajax({
                            type: 'POST',
                            url: checkUrl,
                            dataType: 'json',
                            data:{
                                'param':mobile
                            },
                            success:function(data){
                                var flag = false;
                                if((checkType==0)&&(data.status=='y')){
                                    //验证手机号没被使用，此处用在注册
                                    flag = true;
                                }else if((checkType==1)&&(data.status=='n')){
                                    //验证手机号是否存在，此处用在短信登录，用户手机号必须存在
                                    flag = true;
                                }
                                if(flag){
                                    //调用发送短信方法
                                    ajaxSendSms(mobile,type,sendSmsUrl,btnObj,btnObjTxt);
                                }else{
                                    var d = dialog({content:"该手机号未注册！"}).show();
                                    setTimeout(function () {
                                        d.close().remove();
                                    }, 2000);
                                }
                            },
                            error:function(){
                                alert('error');
                            }
                        })
                    }else{
                        //不验证,此处用在绑定手机号,存在则绑定，不存在则注册
                        //调用发送短信方法
                        ajaxSendSms(mobile,type,sendSmsUrl,btnObj,btnObjTxt);
                    }
                }
            }
            $("#sendBtn").click(function(){
                var vcode=$("#vcode").val();
                if(!$(this).hasClass("disabled")&&vcode!=""){
                    sendSms("#mobile",'bind',"<?php echo U('Shop/Ajax/sendSms');?>","#sendBtn","获取短信验证码",1,"<?php echo U('Shop/Ajax/checkMobile');?>");
                }
            })
        })
    </script>
</body>
</html>