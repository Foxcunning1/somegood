<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>商品推广</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/shop/css/public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/shop/css/user.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/admin/js/common.js"></script>
   <!-- <script type="text/javaScript">
        var url = "<?php echo U('Store/Index/getRegionList');?>";
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
    </script>-->
</head>
<body style="background-color: #f5f5f5;">
    <div class="login-header">
        <div class="w1200">
            <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/login_logo.png" alt="login_logo" /></a>
        </div>
    </div>
    <div class="container">
        <div class="reg-wrap apply-wrap">
            <div class="form-content-box">
                <form name="regForm" id="regForm" url="<?php echo U('Seller/Index/apply');?>">
                <div class="login-items-box">
                    <div class="login-title">
                        <h2>基本信息</h2>
                    </div>
                    <div class="login-items-option" style="color: #499e39">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;感谢您选择和关注三好连锁品牌推广平台！请您留下您的基本信息，
                        我们专业服务人员会马上与您联系！
                    </div>
                    <div class="login-items-option">
                        <input type="text" name="data[company_name]" id="company_name" placeholder="输入企业名称" dataType="*" class="input" value="<?php echo ($info["company_name"]); ?>"/>
                        <i class="option-title">公司名称</i>
                    </div>
                    <div class="login-items-option">
                        <input type="text" name="data[category]" id="category" dataType="s" placeholder="输入您品牌所属类别" class="input" value="<?php echo ($info["category"]); ?>"/>
                        <i class="option-title">品牌类别</i>
                    </div>
                   <!-- <div class="reg-items-option">
                        <div class="select-box provinces-box ml100">
                            <select name="province" id="selProvinces" class="input small">
                                <option value="" selected>请选择...</option>
                                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item["id"]); ?>"><?php echo ($item["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                        <div class="select-box city-box">
                            <select name="city" id="selCities" class="input small">
                                <option value="" selected>请选择...</option>
                            </select>
                        </div>
                        <div class="select-box district-box">
                            <select name="district" id="selDistricts" class="input small">
                                <option value="" selected>请选择...</option>
                            </select>
                        </div>
                        <i class="option-title">所在位置</i>
                        <div class="clear"></div>
                    </div>-->
                    <div class="login-items-option" style="border: 1px solid #e3e3e3;border-radius: 3px;padding-bottom: 10px" >
                        <div style="margin-left: 100px;padding-top: 10px;">
                            <input type="checkbox" name="data[spread_way][]" value="1">广告牌&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="data[spread_way][]" value="2">产品上架推广
                        </div>
                        <i class="option-title">推广方式</i>
                    </div>
                    <div class="login-items-option">
                        <input type="text" name="data[name]" id="owner" dataType="*"  placeholder="输入您的真实姓名" class="input" value="<?php echo ($info["name"]); ?>" />
                        <i class="option-title">联系人</i>
                    </div>
                    <div class="login-items-option">
                        <input type="text" name="data[mobile]" id="mobile" dataType="/^1[3-9]\d{9}$/" maxlength="11" placeholder="请输入手机号" class="input"  value="<?php echo ($info["mobile"]); ?>" />
                        <i class="option-title">手机号码</i>
                    </div>
                    <div class="login-items-option">
                        <input type="text" name="data[email]" id="email" dataType="/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/" placeholder="请输入邮箱" class="input"  value="<?php echo ($info["email"]); ?>" />
                        <i class="option-title">邮箱</i>
                    </div>
                    <div class="login-items-option">
                        <input type="text" name="data[address]" id="address" dataType="s" placeholder="输入企业详细地址" class="input" value="<?php echo ($info["address"]); ?>"/>
                        <i class="option-title">公司地址</i>
                    </div>
                   <!-- <div class="reg-items-option">
                        <select name="type" id="goods_list" class="input select fl mr10" style="width:160px;height:300px;margin-left: 80px;" size="7">
                            <?php if(is_array($industrysTypeList)): $i = 0; $__LIST__ = $industrysTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option>
                                <?php if(($vo["children"]) != ""): if(is_array($vo["children"])): $i = 0; $__LIST__ = $vo["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vc): $mod = ($i % 2 );++$i; if($i != count($vo[children])): ?><option value="<?php echo ($vc["id"]); ?>">├ <?php echo ($vc["title"]); ?></option>
                                            <?php else: ?>
                                            <option value="<?php echo ($vc["id"]); ?>">└ <?php echo ($vc["title"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <div class="fl mr10">
                            <div class="mul-select-box">
                                <input value="添加到列表 &gt;&gt;" class="btn gBtn" id="addPackageBtn" onclick="manageSelectItem('update', 'goods_list', 'package_list', 'addPackageBtn', 'relatedGoodsId',0);" type="button">
                                <input value="从列表删除 &lt;&lt;" class="btn gBtn mt20" id="delPackageBtn" onclick="manageSelectItem('delete', 'goods_list', 'package_list', 'delPackageBtn', 'relatedGoodsId');" type="button">
                            </div>
                        </div>
                        <select name="pagekage_list" id="package_list" class="input select" style="width:160px;height:300px;" multiple="multiple"></select>
                        <input type="hidden" name="data[category]" id="relatedGoodsId" value="<?php echo ($info["industrys_type_list"]); ?>"/>
                        <i class="option-title">企业类型</i>
                        <div class="clear"></div>
                    </div>
                    <div class="reg-items-option">
                        <input type="text" name="data[counts]" id="counts" required  placeholder="输入您预计推广的产品数" class="input" value="<?php echo ($info["counts"]); ?>" />
                        <i class="option-title">产品数量</i>
                    </div>-->
                    <div class="login-items-option">
                        <input type="submit" class="submitBtn" id="btnSubmit" value="立即申请" />
                    </div>
                    <div class="login-items-option">
                        <p>点击“立即申请”，即表示您同意并愿意遵守《<a class="agreeBtn" id="agreeBtn" href="javascript:;">注册许可协议</a>》</p>
                    </div>
                </div>
            </form>
            </div>
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
                    location.href = "<?php echo U('Home/Index/index');?>";
                },1000);
                $("#btnSubmit").val("立即申请")
                $("#btnSubmit").prop("disabled", false);
            }else{
                //失败
                var d = dialog({content:data.info}).show();
                window.setInterval(function(){
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
    })
</script>
</body>
</html>