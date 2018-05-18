<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>注册帐号</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/shop/css/user.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>

</head>
<body>
	<div class="container">
    	<div class="reg-wrap">
        	<div class="reg-logo"><a href="#"><img alt="3好连锁店logo" src="/somegood/Public/statics/shop/images/logo.png" /></a></div>
        	<div class="reg-box">
                <form name="regForm" id="regForm" url="<?php echo U('Shop/Login/register');?>">
                    <div class="reg-items-box">
                        <div class="reg-items-option">
                            <input type="text" name="data[mobile]" id="mobile" dataType="/^1[3-9]\d{9}$/" maxlength="11" placeholder="请输入手机号" class="input" ajaxurl="<?php echo U('Shop/Ajax/checkMobile');?>" />
                            <i class="option-title">手机号码</i>
                        </div>
                        <div class="reg-items-option">
                            <input type="text" name="vcode" id="vcode" dataType="*" maxlength="4" placeholder="请输入验证码" class="input" />
                            <i class="option-title">验证码</i>
                            <img class="code-img" id="code_img" onclick="this.src='<?php echo U('Shop/Code/verify');?>?r='+Math.random();" src="<?php echo U('Shop/Code/verify');?>" alt="图片验证码" />
                        </div>
                        <div class="reg-items-option">
                            <input type="text" name="code" dataType="n" maxlength="4" placeholder="请输入短信验证码" class="input" />
                            <i class="option-title">短信验证码</i>
                            <a class="smsBtn" id="sendBtn" href="javascript:;">获取短信验证码</a>
                        </div>
                        <div class="reg-items-option">
                            <input type="submit" class="submitBtn" id="btnSubmit" value="立即注册" />
                        </div>
                        <div class="reg-items-option">
                            <p>点击“立即注册”，即表示您同意并愿意遵守《<a class="agreeBtn" id="agreeBtn" href="javascript:;">注册许可协议</a>》</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //初始化验证表单
        $(function(){
            $("#regForm").Validform({
                tiptype:3,
                callback:function(form){
                    //AJAX提交表单
                    $(form).ajaxSubmit({
                        beforeSubmit: showRequest,
                        success: showResponse,
                        error: showError,
                        url: $("#regForm").attr("url"),
                        type: "post",
                        dataType: "json",
                        timeout: 60000
                    });
                    return false;
                }
            });
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
                    window.setInterval(function(){
                        d.close().remove();
                        if(type=='seller'){
                            location.href = "<?php echo U('Seller/Index/apply');?>";
                        }else if(type=='store'){
                            location.href = "<?php echo U('Store/Index/apply');?>";
                        }

                    },1000);
                    $("#btnSubmit").val("立即注册")
                    $("#btnSubmit").prop("disabled", false);
                }else{
                    //失败
                    var d = dialog({content:data.info}).show();
                }
            }
            //表单提交出错
            function showError(XMLHttpRequest, textStatus, errorThrown) {
                dialog({title:'提示', content:"状态：" + textStatus + "；出错提示：" + errorThrown, okValue:'确定', ok:function (){}}).showModal();
                $("#btnSubmit").val("重新提交");
                $("#btnSubmit").prop("disabled", false);
            };
            //注册协议弹出事件
            $("#agreeBtn").click(function(){
                var d = top.dialog({
                    width: 950,
                    title: '会员注册协议',
                    lock: true,
                    url: "<?php echo U('Shop/Login/agreement');?>"
                }).showModal();
                d.addEventListener('agreement', function(){
                    d.close();
                });
            })
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
                                    var d = dialog({content:"短信发送失败！"}).show();
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
                    sendSms("#mobile",'bind',"<?php echo U('Shop/Ajax/sendSms');?>","#sendBtn","获取短信验证码",0,"<?php echo U('Shop/Ajax/checkMobile');?>");
                }
            })
        })
    </script>
</body>
</html>