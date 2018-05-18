<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>手机号登录</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/public.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/mobile/css/user.css" />
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/zepto/zepto.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/zepto/zepto.validateForm.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/zepto/zepto.miniDialog.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="50" fu-max="100"></script>
</head>
<body style="min-height: 100%; background-color: #fff">
<div class="page-title">
    <!--返回按钮-->
    <div class="to-back-box">
        <a href="javascript:history.back();" class="icons icon-back"></a>
    </div>
    <h2 class="title">登录</h2>
    <!--返回按钮-->
</div>
<div class="login-form-box">
    <form action="" class="form login-form" id="loginForm" name="smsLoginForm" style="display: block;" url="<?php echo U('Mobile/Login/login');?>" method="post">
        <dl>
            <dt>手机号</dt>
            <dd>
                <input type="text" placeholder="请输入手机号" class="required mobile" maxlength="11" id="mobile"  sucmsg="通过验证" name="mobile">
            </dd>
        </dl>
        <dl>
            <dt>验证码</dt>
            <dd class="form-dd">
                <input type="text" placeholder="请输入验证码" class="required num" maxlength="6" sucmsg="通过验证" name="verifyCodeGet">
                <button type="button" class="get-code" id="getCode" onclick="smsCode();">获取验证码</button>
            </dd>
        </dl>
        <input type="hidden" id="returnUrl" value="<?php echo ($returnUrl); ?>">
        <button type="submit" class="login-btn" id="phoneLoginBtn">登录</button>
        <p class="txt">点击验证，表示您已阅读并同意<span>《三好连锁服务协议》</span></p>
    </form>
</div>
</body>
<script>
    $(function(){
        /*表单验证*/
        var returnUrl = $("#returnUrl").val();
        $("#loginForm").validateForm({return_url:returnUrl,isBindDel:true});
    })

    /*点击发送手机验证码*/
    function smsCode() {
        var type = 'bind';
        var mobile = $("#mobile").val();
        var w = $(window).width() - 50;
        if (checkPhone()) {
            $.ajax({
                type: 'POST',
                url: "<?php echo U('Mobile/Login/checkMobileExist');?>",
                data: {'mobile':mobile,'type':type},
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        $("body").minDialog({
                            isAutoClose: true,
                            closeTime: 2000,
                            content: data.info,
                        });
                        settime("#getCode");
                    }else{
                        $("body").minDialog({
                            isAutoClose: true,
                            closeTime: 2000,
                            content: data.info,
                        });
                    }
                }
            });
        }
    }
    var countdown=60;
    function settime(obj) {
        if (countdown == 0) {
            $(obj).removeClass("codeTime");
            $(obj).attr("disabled",false);
            $(obj).val("获取验证码");
            $(obj).html("获取验证码");
            countdown = 60;
            return;
        } else {
            $(obj).addClass("codeTime");
            $(obj).attr("disabled", true);
            $(obj).val("重新发送(" + countdown + ")");
            $(obj).html("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function() {
                settime(obj) }
            ,1000)
    }
    /*验证手机号*/
    function checkPhone(){
        var mobile = document.getElementById('mobile').value;
        if(!(/^1[34578]{1}[0-9]{9}$/.test(mobile))){
            return false;
        }else{
            return true;
        }
    }
</script>
</html>