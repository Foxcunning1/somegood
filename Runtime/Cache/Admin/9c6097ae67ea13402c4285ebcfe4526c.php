<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>编辑商品类型</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/webuploader/webuploader.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/admin/js/uploader.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript">
        var uploadType = 'image';
        $(function () {
            //初始化表单验证
            $("#typeForm").initValidform();
            $(".upload-img").InitUploader({modeltype:'goods_type', sendurl: "<?php echo U('Admin/Base/uploadFile');?>", swf: "/somegood/Public/scripts/webuploader/uploader.swf"});
        })
    </script>
</head>

<body class="mainbody">

    <!--导航栏-->
    <div class="location">
        <a href="<?php echo U('GoodsType/index');?>" class="back"><i></i><span>返回列表页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i></i><span>首页</span></a>
        <i class="arrow"></i>
        <a href="<?php echo U('GoodsType/goodsTypeList');?>"><span>栏目列表</span></a>
        <i class="arrow"></i>
        <span>编辑栏目</span>
    </div>
    <div class="line10"></div>
    <!--/导航栏-->

    <!--内容-->
    <div id="floatHead" class="content-tab-wrap">
        <div class="content-tab">
            <div class="content-tab-ul-wrap">
                <ul>
                    <li><a class="selected" href="javascript:void(0);">基本选项</a></li>
                </ul>
            </div>
        </div>
    </div>

<form method="post" id="typeForm" action="<?php echo U('Admin/GoodsType/add');?>">
    <div class="tab-content">
        <dl>
            <dt>类型名称</dt>
            <dd><input name="data[title]" required  type="text" value="<?php echo ($info["title"]); ?>" maxlength="100" id="txtTitle" class="input normal" datatype="*1-100"/> <span class="Validform_checktip  txtTitle">*类型中文名称，100字符内</span></dd>
        </dl>
        <dl>
            <dt>调用别名</dt>
            <dd>
                <input name="data[name]" required type="text" value="<?php echo ($info["name"]); ?>" id="txtCallIndex" class="input normal" datatype="/^\s*$|^[a-zA-Z0-9\-\_]{2,50}$/" errormsg="请填写正确的别名" ajaxurl="<?php echo U('GoodsType/nameValidate',array('old_name'=>$info['name']));?>"/>
                <span class="Validform_checktip">类别的调用别名，只允许字母、数字、下划线</span>
            </dd>
        </dl>
        <dl>
            <dt>是否隐藏</dt>
            <dd>
                <div class="rule-multi-radio">
                    <input id="is_hidden_1" type="radio" name="data[is_hidden]" value="1" <?php if(($info["is_hidden"]) == "1"): ?>checked="checked"<?php endif; ?> checked="checked"/><label for="is_hidden_1">是</label>
                    <input id="is_hidden_0" type="radio" name="data[is_hidden]" value="0" <?php if(($info["is_hidden"]) == "0"): ?>checked="checked"<?php endif; ?> /><label for="is_hidden_0">否</label>
                </div>
            </dd>
        </dl>
        <dl>
            <dt>图标</dt>
            <dd>
                <input name="data[img_url]" type="text" id="txtImgUrl" value="<?php echo ($info["img_url"]); ?>" class="input normal upload-path" />
                <div class="upload-box upload-img"></div>
            </dd>
        </dl>
        <dl>
            <dt>排序数字</dt>
            <dd>
                <input name="data[sort_num]" type="text" value="<?php echo ($info["sort_num"]); ?>" id="txtSortId" class="input small" datatype="n" sucmsg=" " required/>
                <span class="Validform_checktip">*数字，越小越向前</span>
            </dd>
        </dl>
    </div>
    <!--/内容-->
    <!--工具栏-->
    <div class="page-footer">
        <div class="btn-wrap">
            <input type="hidden" name="action" value="<?php echo ($action); ?>" />
            <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
            <input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>" />
            <p id="yincang" style="color:red;"></p>
            <input type="submit" name="btnSubmit" value="提交保存" id="btnSubmit" class="btn" />
            <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back();" />
        </div>
    </div>
    <!--/工具栏-->
</form>
</body>
</html>