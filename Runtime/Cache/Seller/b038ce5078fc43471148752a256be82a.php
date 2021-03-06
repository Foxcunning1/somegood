<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>店铺管理中心</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <script type="text/javascript" src="/somegood/Public/statics/mobile/js/rem.js" fu-psd="720" fu-min="100" fu-max="100"></script>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/sitelogo/sitelogo.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/ui-dialog.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
</head>
<body style="background-color: #edf1f2;">

    <div class="setting-tab-box">
        <ul class="setting-tab-ul">
            <!--<li class="one sel"><a href="javascript:void(0);"><span class="pic"></span><span class="txt">店铺信息</span></a><div class="move"></div></li>-->
            <li class="three sel"><a href="javascript:void(0);"><span class="pic"></span><span class="txt">修改密码</span></a><div class="move"></div></li>
        </ul>
    </div>
    <!--头像上传-->
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/bootstrap/css/bootstrap.min.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/cropper/cropper.min.css" />
    <!--<script type="text/javascript" src="/somegood/Public/scripts/cropper/cropper.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/sitelogo/sitelogo.js"></script><script type="text/javascript" src="/somegood/Public/scripts/bootstrap/js/bootstrap.min.js"></script>-->
    <div class="setting-info-box">
        <!--<div class="setting-item" style="display: block;">
            <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form class="avatar-form" action="<?php echo U('Mobile/Users/editAvatar');?>?is_store=1" enctype="multipart/form-data" method="post">
                            <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title" id="avatar-modal-label">修改</h4>
                            </div>
                            <div class="modal-body">
                                <div class="avatar-body">
                                    <div class="avatar-upload">
                                        <input class="avatar-src" name="avatar_src" type="hidden" value="/somegood/Public/uploads/">
                                        <input class="avatar-data" name="avatar_data" type="hidden">
                                        <label for="avatarInput">图片上传<input class="avatar-input" id="avatarInput" name="avatar_file" type="file"></label>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9" style="overflow: hidden; width: 320px; height: 320px;float:left;padding: 0;margin: 20px">
                                            <div class="avatar-wrapper" style="width: 320px; height: 320px; margin: 0;"></div>
                                        </div>
                                        <div class="col-md-3" style="overflow: hidden; padding: 0; padding:20px 0">
                                            <div class="avatar-preview preview-lg" style="margin:0 10px 10px 0"></div>
                                            <div class="avatar-preview preview-md" style="margin:0 10px 10px 0"></div>
                                            <div class="avatar-preview preview-sm" style="margin:0 10px 10px 0"></div>
                                        </div>
                                    </div>
                                    <div class="row avatar-btns" style="margin:0">
                                        <div class="col-md-9" style="margin-bottom: 20px;">
                                            <div class="btn-group">
                                                <button class="btn" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees"><i class="fa fa-undo"></i> 向左旋转</button>
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees"><i class="fa fa-repeat"></i> 向右旋转</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-success btn-block avatar-save" type="submit"><i class="fa fa-save"></i> 保存修改</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
            <form action="<?php echo U('Store/Index/setting');?>" class="data-form form" id="data-form" method="post">
                <div class="dl">
                    <div class="dt" style="line-height: 60px;">LOGO：</div>
                    <div class="dd">
                        <div id="crop-avatar" class="col-md-6">
                            <div class="avatar-view" style="width: 60px; height: 60px;">
                                <img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="/somegood/Public/uploads/<?php echo ($info["logo"]); ?>_c200x200" alt="头像/LOGO">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dl">
                    <div class="dt">店铺名称：</div>
                    <div class="dd"><input type="text" datatype="*" name="store_name" value="<?php echo ($info["store_name"]); ?>" id="store_name"/></div>
                </div>

                <div class="dl">
                    <div class="dt">电话号码：</div>
                    <div class="dd"><input type="text"  title="手机号格式不正确" pattern="^1[3-9]\d{9}$" name="mobile" value="<?php echo ($info["mobile"]); ?>" id="mobile"/></div>
                </div>
                <input type="hidden" name="type" value="store">
                <input type="submit" class="submit-btn" value="确认修改"/>
            </form>
        </div>-->
        <div class="setting-item" style="display: block;">
            <form action="<?php echo U('Store/Index/setting');?>" class="password-form form" name="settingForm" id="settingForm" method="post">
                <div class="dl">
                    <div class="dt">原密码：</div>
                    <div class="dd"><input type="password" name="oldPassword" id="oldPassword"/></div>
                </div>
                <div class="dl">
                    <div class="dt">新密码：</div>
                    <div class="dd"><input type="password" name="newPassword" id="newPassword"/></div>
                </div>
                <div class="dl">
                    <div class="dt">确认密码：</div>
                    <div class="dd"><input type="password" id="rePassword"/></div>
                </div>
                <input type="hidden" name="type" value="pw">
                <input type="submit" class="submit-btn" value="确认修改"/>
            </form>
        </div>
    </div>
<script>
    $(".setting-tab-ul li").click(function(){
        var index =$(this).index();
        $(this).addClass("sel").siblings().removeClass("sel");
        $(this).find(".move").show().parent().siblings().find(".move").hide();
        $(".setting-info-box").find(".setting-item").eq(index).show().siblings().hide();
    })
    $(function(){
        //图片延迟加载
        $(".avatar-view img").lazyload({effect : "fadeIn"});
        $('#data-form').ajaxForm({
            beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
            success: complete, // 这是提交后的方法
            dataType: 'json'
        });
        function checkForm(){
            if( '' == $("#store_name").val()){
                var d = dialog({content:"店铺名不能为空"}).show();
                setTimeout(function () {
                    d.close().remove();
                    $('#store_name').focus();
                }, 800);
                return false;
            }
        }
        function complete(data){
            if(data.status==1){
                var d = dialog({content:data.info}).show();
                setTimeout(function () {
                    d.close().remove();
                }, 800);
            }else{
                var d = dialog({content:data.info}).show();
                setTimeout(function () {
                    d.close().remove();
                }, 800);
                return false;
            }
        }
    });
    $(function(){
        $('#settingForm').ajaxForm({
            beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
            success: complete, // 这是提交后的方法
            dataType: 'json'
        });
        function checkForm(){
            if( '' == $("#oldPassword").val()){
                var d = dialog({content:"原密码不能为空"}).show();
                setTimeout(function () {
                    d.close().remove();
                    $('#oldPassword').focus();
                }, 800);
                return false;
            }
            if( '' == $("#newPassword").val()){
                var d = dialog({content:"新密码不能为空"}).show();
                setTimeout(function () {
                    d.close().remove();
                    $('#newPassword').focus();
                }, 800);
                return false;
            }
            if( $("#rePassword").val() !== $("#newPassword").val()){
                var d = dialog({content:"两次输入密码不一致,请确认"}).show();
                setTimeout(function () {
                    d.close().remove();
                    $('#rePassword').focus();
                }, 800);
                return false;
            }
        }
        function complete(data){
            if(data.status==1){
                var d = dialog({content:data.info}).show();
                setTimeout(function () {
                    d.close().remove();
                }, 800);
            }else{
                var d = dialog({content:data.info}).show();
                setTimeout(function () {
                    d.close().remove();
                    $('#oldPassword').focus();
                }, 800);
                return false;
            }
        }
    });
</script>
</body>
</html>