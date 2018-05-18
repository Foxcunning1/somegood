<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>3好连锁店商城首页</title>
    <meta name="keywords" content="<?php echo ($web_keywords); ?>"/>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <!--vipCenterContent start-->
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/public.css">
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/user.css">
    <link type="text/css" rel="stylesheet" href="/somegood/Public/kindeditor/themes/default/default.css">
    <link type="text/css" rel="stylesheet" href="/somegood/Public/scripts/jquery/jquery.selectlist.css" >
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/shop/js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/js/share-code.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery.cookie.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/shop/js/webuploader.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/kindeditor/kindeditor-all.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/shop/js/uploader.js"></script>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript">
        var uploadType = 'image';
        $(function(){
            //初始化上传控件
            $(".upload-img").InitUploader({modeltype:'goods_type', sendurl: "<?php echo U('Seller/Base/uploadFile');?>", swf: "/somegood/Public/scripts/webuploader/uploader.swf"});
            $(".upload-album").InitUploader({ modeltype:'goods_type',btntext: "批量上传", multiple: true, sendurl: "<?php echo U('Seller/Base/uploadFile');?>", swf: "/somegood/Public/scripts/webuploader/uploader.swf" });
            //设置封面图片的样式
            $(".photo-list ul li .img-box img").each(function () {
                if ($(this).attr("src") == $("#hidFocusPhoto").val()) {
                    $(this).parent().addClass("selected");
                }
            });
            //初始化编辑器
            var editorMini = KindEditor.create('.txtContent', {
                width: '100%',
                height: '250px',
                filterMode: false, //默认不过滤HTML
                resizeType: 1,
                uploadJson: "<?php echo U('Base/uploadFile',array('action' => 'EditorFile'));?>",
                fileManagerJson: '/somegood/Public/kindeditor/php/file_manager_json.php',
                allowFileManager: true,
                items: [
                    'source', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'image', 'link', 'fullscreen']
            });
        });
    </script>
    <script type="text/javascript">
        $(function () {
            //初始化表单
            AjaxInitForm('#infoForm', '#btnSubmit', 1);
        });
    </script>

</head>
<body>
<div class="u-tab-content">
    <div class="title-div">
        <strong>发布产品</strong>
    </div>
    <div class="form-box">
        <form name="infoForm" id="infoForm" url="<?php echo U('Seller/Goods/publish');?>">
            <!--商品信息-->
            <dl class="required">
                <dt><i>*</i>商品名称：</dt>
                <dd>
                    <input name="data[goods_title]" id="txtTitle" type="text" class="input txt"  datatype="*1-150" nullmsg="请输入商品名称" value="<?php echo ($info["goods_title"]); ?>" />
                </dd>
            </dl>
            <dl class="required">
                <dt><i>*</i>所属分类：</dt>
                <dd>
                    <select name="data[category_id]" id="goodsNameStyle" datatype="*" class="input normal" datatype="*" style="padding:0; height: 32px; line-height: 32px;">
                        <option value="">请选择类别</option>
                        <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($item['id'] == $info['category_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($item["id"]); ?>"><?php if(($item["count"]) != "1"): echo (fill_up_string($item['count'],"")); ?>├<?php endif; ?> <?php echo ($item["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt><i>*</i>价格：</dt>
                <dd>
                    <input name="data[price]" id="txtPrice" type="text" class="input txt small"  datatype="*" nullmsg="请输入商品价格" value="<?php echo ($info["price"]); ?>" />
                </dd>
            </dl>
            <dl>
                <dt><i>*</i>市场价：</dt>
                <dd>
                    <input name="data[market_price]" id="market_price" type="text" class="input txt small"  datatype="*" nullmsg="请输入市场价" value="<?php echo ($info["market_price"]); ?>" />
                </dd>
            </dl>
            <dl>
                <dt><i>*</i>库存：</dt>
                <dd>
                    <input name="data[online_stock]" id="online_stock" type="text" class="input txt small"  datatype="*" nullmsg="请输入库存" value="<?php echo ($info["online_stock"]); ?>" />
                </dd>
            </dl>
            <dl>
                <dt><i>*</i>重量：</dt>
                <dd>
                    <input name="data[heavy]" id="heavy" type="text" class="input txt small"  datatype="*" nullmsg="请输入商品重量" value="<?php echo ($info["heavy"]); ?>" />Kg
                </dd>
            </dl>
            <!--
            <dl>
                <dt><i>*</i>长：</dt>
                <dd>
                    <input name="data[length]" id="length" type="text" class="input txt small"  datatype="*" nullmsg="请输入商品长度" value="<?php echo ($info["length"]); ?>" />mm
                </dd>
            </dl>
            <dl>
                <dt><i>*</i>宽：</dt>
                <dd>
                    <input name="data[width]" id="width" type="text" class="input txt small"  datatype="*" nullmsg="请输入商品宽度" value="<?php echo ($info["width"]); ?>" />mm
                </dd>
            </dl>
            <dl>
                <dt><i>*</i>高：</dt>
                <dd>
                    <input name="data[height]" id="height" type="text" class="input txt small"  datatype="*" nullmsg="请输入商品高度" value="<?php echo ($info["height"]); ?>" />mm
                </dd>
            </dl>-->

            <dl class="required">
                <dt><i>*</i>缩略图：</dt>
                <dd>
                    <input name="data[goods_thumb]" id="txtThumb" type="text" class="input txt upload-path" value="<?php echo ($info["goods_thumb"]); ?>" />
                    <div class="upload-box upload-img"></div>
                </dd>
            </dl>
            <dl id="div_albums_container">
                <dt>图片相册：</dt>
                <dd>
                    <div class="upload-box upload-album"></div>
                    <input name="hidFocusPhoto" type="hidden" id="hidFocusPhoto" class="focus-photo" value="<?php echo ($info["thumb"]); ?>" />
                    <div class="photo-list">
                        <ul>
                            <?php if(is_array($info["plist"])): $i = 0; $__LIST__ = $info["plist"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                                    <input type="hidden" name="photo[]" value="<?php echo ($vo); ?>" />
                                    <input type="hidden" name="rphoto[]" value="<?php echo ($info["rlist"]["$key"]); ?>" />
                                    <div class="img-box" onClick="setFocusImg(this);">
                                        <img src="/somegood/Public/uploads/<?php echo ($vo); ?>_c120x120" bigsrc="/somegood/Public/uploads/<?php echo ($vo); ?>" realpath="<?php echo ($vo); ?>" />
                                        <span class="remark"><i><?php if($info.rlist.$key == ''): ?>暂无描述<?php else: echo ($info["rlist"]["$key"]); endif; ?></i></span>
                                    </div>
                                    <a href="javascript:;" onClick="setRemark(this);">描述</a>
                                    <a href="javascript:;" onClick="delImg(this);">删除</a>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                </dd>
            </dl>

            <dl>
                <dt>商品简介：</dt>
                <dd>
                    <textarea name="data[content]" class="input" style="width:300px;height:80px;" id="content"><?php echo ($info["content"]); ?></textarea>
                </dd>
            </dl>
            <dl>
                <dt>商品参数：</dt>
                <dd>
                    <textarea name="data[params]" class="txtContent" style="width:300px;height:80px;" id="params"><?php echo (htmlspecialchars_decode($info["params"])); ?></textarea>
                </dd>
            </dl>
            <dl class="required">
                <dt><i>*</i>商品详情：</dt>
                <dd>
                    <textarea name="data[details]" class="txtContent" style="visibility:hidden;" id="details"><?php echo (htmlspecialchars_decode($info["details"])); ?></textarea>
                </dd>
            </dl>

            <dl>
                <dt></dt>
                <dd>
                    <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
                    <input type="hidden" name="action" value="<?php echo ($action); ?>">
                    <input name="btnSubmit" id="btnSubmit" type="submit" class="btn btn-success" value="提交" />
                </dd>
            </dl>
        </form>
    </div>
    <!--/商品信息-->
</div>
</body>