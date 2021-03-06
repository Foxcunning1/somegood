<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>卖家管理中心</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" href="/somegood/Public/statics/store/css/store-public.css" type="text/css">
    <link rel="stylesheet" href="/somegood/Public/statics/store/css/store-style.css" type="text/css">
    <link rel="stylesheet" href="/somegood/Public/statics/store/css/ui-dialog.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js" ></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/store/js/common.js"></script>
</head>
<body>

    <div class="login-top">
        <div class="w1200">
            <a href="javascript:;" class="logo"><img src="/somegood/Public/statics/store/images/logo.png" alt="logo" /></a>
        </div>
    </div>
    <style>
        .stage {position:absolute;left:0;top:0; width:100%;height:100%; overflow: hidden;}
    </style>
    <div class="stage" id="stage"></div>
    <div class="login-content">
        <div class="store-login-box">
            <div class="login-title"><ul><li class="curr"><a href="javascript:;">账号密码登录</a></li><li><a href="javascript:;">手机动态码登录</a></li></ul></div>
            <div class="forms-box">
                <form id="storeLoginForm" class="store-login-form" url="<?php echo U('Seller/Login/login');?>" style="display: block" method="post">
                    <dl>
                        <dd>
                            <input type="text"  title="手机号格式不正确" pattern="^1[3-9]\d{9}$" required name="mobile" class="user-name txt" placeholder="请输入手机号"/>
                            <i class="user-name-icon icons"></i>
                        </dd>
                    </dl>
                    <dl>
                        <dd>
                            <input type="password" required name="password" class="psw txt" placeholder="请输入密码" style="width: 278px; *width: 274px;"/>
                            <i class="lock-icon icons"></i>
                        </dd>
                    </dl>
                    <dl>
                        <label for="remember" class="fl"><input type="checkbox" id="remember" name="remember"/>记住密码</label>
                        <span class="fr"><a href="<?php echo U('Shop/Login/register',array('type'=>'seller'));?>" >立即注册</a></span>
                    </dl>
                    <input type="hidden" id="returnUrl" value="<?php echo ($returnUrl); ?>">
                    <dl><input type="submit" class="btn-submit" value="登录"/></dl>
                </form>
                <form id="dynamicCodeForm" class="dynamic-code-form" url="<?php echo U('Seller/Login/mobileLogin');?>" method="post">
                    <dl>
                        <dd>
                            <input type="text"  title="手机号格式不正确" pattern="^1[3-9]\d{9}$" required name="mobile" id="mobile" class="user-name txt" placeholder="请输入手机号"/>
                            <i class="user-name-icon icons"></i>
                        </dd>
                    </dl>
                    <dl>
                        <dd>
                            <input type="text" required name="verifyCodeGet" class="psw txt" placeholder="请输入动态验证码"/>
                            <i class="lock-icon icons"></i>
                            <button type="button" class="get-code" id="getCode" onclick="smsCode();">获取验证码</button>
                        </dd>
                    </dl>
                    <dl><input type="submit" class="btn-submit" value="登录"/></dl>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/js/canvas_bg.js"></script>
    <script>

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
                    var d = dialog({content:data.info}).show();
                    window.setInterval(function(){
                        d.close().remove();},1000);
                    }
                });
            }
            settime("#getCode");
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
        $(function(){
            /*登录方式切换*/
            $(".login-title").find("li").click(function(){
                var index = $(this).index();
                $(this).addClass("curr").siblings().removeClass("curr");
                $(".forms-box").find("form").eq(index).show().siblings().hide();
            })
            /*表单验证*/
            $(".store-login-form").Validform({
                tiptype:4,
                callback:function(form){
                    $(form).ajaxSubmit({
                        success: showResponse,
                        url: $("#storeLoginForm").attr("url"),
                        type: "post",
                        dataType: "json",
                        timeout: 60000
                    });
                    return false;
                }
            });
            /*表单验证*/
            $("#dynamicCodeForm").Validform({
                tiptype:4,
                callback:function(form){
                    $(form).ajaxSubmit({
                        success: showResponse,
                        url: $("#dynamicCodeForm").attr("url"),
                        type: "post",
                        dataType: "json",
                    });
                    return false;
                }
            });
            //表单提交后
            function showResponse(data, textStatus) {
                if (data.status == 1) { //成功
                    var d = dialog({content:"登录成功！"}).show();
                    window.setInterval(function(){
                        d.close().remove();
                        jumpToUrl("<?php echo U('Seller/Index/index');?>")},1000);
                }else{ //失败
                    var d = dialog({content:data.info}).show();
                    window.setInterval(function(){
                        d.close().remove();
                        if(data.data=='apply')jumpToUrl("<?php echo U('Seller/Index/apply');?>");
                        if(data.data=='sign')jumpToUrl("<?php echo U('Shop/Login/register',array('type'=>'seller'));?>");
                    },1000);
                }
            }
            function jumpToUrl(url){
                location.href = url;
            }
        })

    </script>
</body>
</html>