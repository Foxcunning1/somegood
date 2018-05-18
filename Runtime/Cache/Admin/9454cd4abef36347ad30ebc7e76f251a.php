<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>编辑商品类别</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/webuploader/webuploader.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/kindeditor/kindeditor-all.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/admin/js/uploader.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript">
        var uploadType = 'image';
        $(function () {
            //初始化表单验证
            $("#categoryForm").initValidform();
            $(".upload-img").InitUploader({modeltype:'category', sendurl: "<?php echo U('Admin/Base/uploadFile');?>", swf: "/somegood/Public/scripts/webuploader/uploader.swf"});
            //初始化编辑器
            var editor = KindEditor.create('#txtContent', {
                width: '100%',
                height: '350px',
                filterMode: false, //默认不过滤HTML
                resizeType: 1,
                uploadJson: "<?php echo U('Admin/Base/uploadFile');?>?action=EditorFile&modeltype=category",
                fileManagerJson: '/somegood/Public/kindeditor/php/file_manager_json.php',
                allowFileManager: true
            });
        });
    </script>
</head>

<body class="mainbody">
<form method="post" action="<?php echo U('GoodsCategory/add');?>" id="categoryForm">
    <!--导航栏-->
    <div class="location">
        <a href="<?php echo U('GoodsCategory/index');?>" class="back"><i class="iconfont icon-up"></i><span>返回列表页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <a href="<?php echo U('GoodsCategory/index');?>"><span>商品类别</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <span>编辑类别</span>
    </div>
    <div class="line10"></div>
    <!--/导航栏-->

    <!--内容-->
    <div id="floatHead" class="content-tab-wrap">
        <div class="content-tab">
            <div class="content-tab-ul-wrap">
                <ul>
                    <li><a class="selected" href="javascript:void(0);">基本选项</a></li>
                    <li><a href="javascript:void(0);">SEO设置</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <dl>
            <dt>所属父类别</dt>
            <dd>
                <div class="rule-single-select">
                    <select name="data[parent_id]" id="ddlParentId">
                        <option value="0">无父级分类</option>
                        <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($item['id'] == $info['parent_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($item["id"]); ?>"><?php if(($item["count"]) != "1"): echo (fill_up_string($item['count'],"&nbsp;&nbsp;")); ?>├<?php endif; ?> <?php echo ($item["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </dd>
        </dl>
        <dl>
            <dt>排序数字</dt>
            <dd>
                <input name="data[sort_num]" type="text" value="<?php echo ($info["sort_num"]); ?>" id="txtSortId" class="input small" datatype="n" sucmsg=" " />
                <span class="Validform_checktip">*数字，越小越向前</span>
            </dd>
        </dl>
        <dl>
            <dt>类别名称</dt>
            <dd><input name="data[title]" type="text" value="<?php echo ($info["title"]); ?>" id="txtTitle" class="input normal" datatype="*1-100"/> <span class="Validform_checktip">*类别中文名称，100字符内</span></dd>
        </dl>
        <dl>
            <dt>调用别名</dt>
            <dd>
                <input name="data[name]" type="text" value="<?php echo ($info["name"]); ?>" id="txtCallIndex" class="input normal" datatype="*" errormsg="请填写正确的别名" ajaxurl="<?php echo U('GoodsCategory/nameValidate',array('old_name'=>$info['name']));?>"/>
                <span class="Validform_checktip">类别的调用别名，只允许字母、数字、下划线</span>
            </dd>
        </dl>
        <dl>
            <dt>URL链接</dt>
            <dd>
                <input name="data[link_url]" type="text" maxlength="255" id="txtLinkUrl" class="input normal" value="<?php echo ($info["link_url"]); ?>"/>
                <span class="Validform_checktip">填写后直接跳转到该网址</span>
            </dd>
        </dl>
        <dl>
            <dt>图片</dt>
            <dd>
                <input name="data[img_url]" type="text" id="txtImgUrl" class="input normal upload-path" value="<?php echo ($info["img_url"]); ?>" />
                <div class="upload-box upload-img"></div>
            </dd>
        </dl>

        <dl>
            <dt>是否在导航显示</dt>
            <dd>
                <div class="rule-multi-radio">
                    <input id="is_nav_1" type="radio" name="data[is_nav]" value="1" <?php if(($info["is_nav"]) == "1"): ?>checked="checked"<?php endif; ?> /><label for="is_nav_1">是</label>
                    <input id="is_nav_0" type="radio" name="data[is_nav]" value="0" <?php if(($info["is_nav"]) == "0"): ?>checked="checked"<?php endif; ?> /><label for="is_nav_0">否</label>
                </div>
            </dd>
        </dl>
        <dl>
            <dt>是否在手机端首页显示</dt>
            <dd>
                <div class="rule-multi-radio">
                    <input id="is_index_1" type="radio" name="data[is_index]" value="1" <?php if(($info["is_index"]) == "1"): ?>checked="checked"<?php endif; ?> /><label for="is_index_1">是</label>
                    <input id="is_index_0" type="radio" name="data[is_index]" value="0" <?php if(($info["is_index"]) == "0"): ?>checked="checked"<?php endif; ?> /><label for="is_index_0">否</label>
                </div>
            </dd>
        </dl>
        <dl>
            <dt>是否隐藏</dt>
            <dd>
                <div class="rule-multi-radio">
                    <input id="is_hidden_1" type="radio" name="data[is_hidden]" value="1" <?php if(($info["is_hidden"]) == "1"): ?>checked="checked"<?php endif; ?> /><label for="is_hidden_1">是</label>
                    <input id="is_hidden_0" type="radio" name="data[is_hidden]" value="0" <?php if(($info["is_hidden"]) == "0"): ?>checked="checked"<?php endif; ?> /><label for="is_hidden_0">否</label>
                </div>
            </dd>
        </dl>

        <dl>
            <dt>栏目介绍</dt>
            <dd>
                <textarea name="data[content]" id="txtContent" class="editor" style="visibility:hidden;"><?php echo ($info["content"]); ?></textarea>
            </dd>
        </dl>
    </div>
    <div class="tab-content" style="display:none">
        <dl>
            <dt>SEO标题</dt>
            <dd>
                <input name="data[seo_title]" type="text" value="<?php echo ($info["seo_title"]); ?>" maxlength="255" id="txtSeoTitle" class="input normal" datatype="s0-100" sucmsg=" " />
                <span class="Validform_checktip">255个字符以内</span>
            </dd>
        </dl>
        <dl>
            <dt>SEO关健字</dt>
            <dd>
                <textarea name="data[seo_keywords]" rows="2" cols="20" id="txtSeoKeywords" class="input"><?php echo ($info["seo_keywords"]); ?></textarea>
                <span class="Validform_checktip">以“,”逗号区分开，255个字符以内</span>
            </dd>
        </dl>
        <dl>
            <dt>SEO描述</dt>
            <dd>
                <textarea name="data[seo_description]" rows="2" cols="20" id="txtSeoDescription" class="input"><?php echo ($info["seo_description"]); ?></textarea>
                <span class="Validform_checktip">255个字符以内</span>
            </dd>
        </dl>
    </div>

    <!--/内容-->

    <!--工具栏-->
    <div class="page-footer">
        <div class="btn-wrap">
            <input type="hidden" name="action" value="<?php echo ($action); ?>" />
            <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
            <input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>"/>
            <input type="submit" name="btnSubmit" value="提交保存" id="btnSubmit" class="btn" />
            <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back();" />
        </div>
    </div>
    <!--/工具栏-->

</form>
<script type="text/javascript">
    $(function(){
        /*简称失去焦点中文转英文*/
        $("#txtTitle").blur(function(){
            setPartWordsByString("#txtTitle","#txtCallIndex","<?php echo U('Admin/Ajax/getPinyinByChinses');?>");
        });
    })
</script>
</body>
</html>