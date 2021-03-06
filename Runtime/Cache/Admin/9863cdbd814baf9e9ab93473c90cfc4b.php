<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>管理员登录</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/base.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/layout.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript">
        $(function () {
            //检测IE
            if ('undefined' == typeof (document.body.style.maxHeight)) {
                window.location.href = '/ie6update.html';
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(e) {
            $(".input").focus(function(){
                $(this).addClass("focus");
            }).blur(function(){
                $(this).removeClass("focus");
            });
            $("#btnSubmit").click(function(){
                checkForm('#msgtip');
            })
        });
        function checkForm(tipsBox){
            var flag=true;
            $('input.login-input').each(function(){
                if(($(this).attr('type')=='text'||$(this).attr('type')=='password')&&$(this).val()=='')
                {
                    $(this).focus();
                    $(tipsBox).html($(this).attr('tipsText'));
                    showTipsBox(tipsBox);
                    flag=false;
                    return false;
                }
            })
            //提交表单
            if(flag)
            {
                $.ajax({
                    url:"<?php echo U('Login/login');?>",
                    dataType:"json",
                    data:'txtUserName='+$('#txtUserName').val()+'&txtPassword='+$("#txtPassword").val()+'&r='+Math.random(),
                    type:"POST",
                    success:function(data){
                        if(data.status==0)
                        {
                            $(tipsBox).html(data.info);
                            showTipsBox(tipsBox);
                            return false;
                        }else{
                            $(tipsBox).html(data.info);
                            showTipsBox(tipsBox);
                            setTimeout(function () {
                                goToUrl(data.data);
                            }, 800);
                            return false;
                        }
                    }
                })
            }
        }
        //跳转页面
        function goToUrl(url)
        {
            window.location.href=url;
        }
        //显示提示
        function showTipsBox(tipsBox)
        {
            $(".icon-info").animate({"opacity":100},100).delay(800).animate({"opacity":0},100);
            $(tipsBox).show(100).delay(900).hide(300);
        }
    </script>
</head>

<body class="loginbody">
<form action="<?php echo U('Admin/Login/login');?>" method="post" id="loginForm" name="myform" onSubmit="return false;" >
    <div style="width:100%; height:100%; min-width:300px; min-height:260px;"></div>
    <div class="login-wrap">
        <div class="login-logo">LOGO</div>
        <div class="login-form">
            <div class="col">
                <input name="txtUserName" type="text" id="txtUserName" class="login-input" placeholder="管理员账号" tipsText="请输入用户名" title="管理员账号" />
                <label class="icon" for="txtUserName"><i class="iconfont icon-user"></i></label>
            </div>
            <div class="col">
                <input name="txtPassword" type="password" id="txtPassword" class="login-input" placeholder="管理员密码" tipsText="请输入密码" title="管理员密码" />
                <label class="icon" for="txtPassword"><i class="iconfont icon-key"></i></label>
            </div>
            <div class="col">
                <input type="submit" name="btnSubmit" value="登 录" id="btnSubmit" class="login-btn" />
            </div>
        </div>
        <div class="login-tips"><i class="iconfont icon-info"></i><p id="msgtip">请输入用户名和密码</p></div>
    </div>

    <div class="copy-right">
        <p>版权所有 深圳市比尔肯信息服务有限公司 Copyright © 2015 - 2017 somegood.com.cn Inc. All Rights Reserved.</p>
    </div>
</form>
</body>
</html>