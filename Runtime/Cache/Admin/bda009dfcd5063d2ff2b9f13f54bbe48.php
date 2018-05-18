<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<title>编辑搜索关键词</title>
		<link href="/somegood/Public/statics/admin/css/ui-dialog.css" rel="stylesheet" type="text/css" />
    <link href="/somegood/Public/statics/admin/css/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/webuploader.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/kindeditor-all.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/uploader.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
		<script type="text/javascript">
			var ajax_site_url = "<?php echo U('Site/sajax');?>";
			$(function() {
				//初始化表单验证
				$("#form1").initValidform();
				//是否启用权限
				if ($("#KeywordType").find("option:selected").attr("value") == 1) {
					$(".border-table").find("input[type='checkbox']").prop("disabled", true);
				}
				$("#KeywordType").change(function() {
					if ($(this).find("option:selected").attr("value") == 1) {
						$(".border-table").find("input[type='checkbox']").prop("checked", false);
						$(".border-table").find("input[type='checkbox']").prop("disabled", true);
					} else {
						$(".border-table").find("input[type='checkbox']").prop("disabled", false);
					}
				});
				if ($("#Hot_KeywordType").find("option:selected").attr("value") == 1) {
					$(".border-table").find("input[type='checkbox']").prop("disabled", true);
				}
				$("#Hot_KeywordType").change(function() {
					if ($(this).find("option:selected").attr("value") == 1) {
						$(".border-table").find("input[type='checkbox']").prop("checked", false);
						$(".border-table").find("input[type='checkbox']").prop("disabled", true);
					} else {
						$(".border-table").find("input[type='checkbox']").prop("disabled", false);
					}
				});
				//权限全选
				$("input[name='checkAll']").click(function() {
					if ($(this).prop("checked") == true) {
						$(this).parent().siblings("td").find("input[type='checkbox']").prop("checked", true);
					} else {
						$(this).parent().siblings("td").find("input[type='checkbox']").prop("checked", false);
					}
				});
			});
		</script>
	</head>
	<body class="mainbody">
		<form method="post" action="<?php echo U('Keywords/add',array('id' => $_GET['id'] , 'action' => $_GET['action']));?>" id="form1">
			<!--导航栏-->
			<div class="location">
				<a href="role_list.aspx" class="back"><i></i><span>返回列表页</span></a>
				<a href="../center.aspx" class="home"><i></i><span>首页</span></a>
				<i class="arrow"></i>
				<a href="role_list.aspx"><span>关键词管理</span></a>
				<i class="arrow"></i>
				<span>编辑关键词</span>
			</div>
			<div class="line10"></div>
			<!--/导航栏-->
			<!--内容-->
			<div id="floatHead" class="content-tab-wrap">
				<div class="content-tab">
					<div class="content-tab-ul-wrap">
						<ul>
							<li><a class="selected" href="javascript:;">编辑关键词</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="tab-content">
				<dl>
					<dt>关键词类型</dt>
					<dd>
						<div class="rule-single-select">
							<select name="data[search_type]" id="keywordType" datatype="*" errormsg="请选择搜索类型！" onchange="getTypeCategoryList(this,'#categoryId','#categoryIdSelect','<?php echo U("Admin/Keywords/ajaxGetCategoryList");?>');" sucmsg=" ">
								<option value="">请选择类型...</option>
							<option value="3" <?php if($keyword['search_type'] == 3): ?>selected="selected"<?php endif; ?>>产品</option>
								<option value="1" <?php if($keyword['search_type'] == 1): ?>selected="selected"<?php endif; ?>>企业</option>
								<option value="2" <?php if($keyword['search_type'] == 2): ?>selected="selected"<?php endif; ?>>供求</option>
							</select>
						</div>
					</dd>
				</dl>
				<dl>
					<dt>关键所属分类</dt>
					<dd>
						<div class="rule-single-select" id="categoryIdSelect">
							<select name="data[category_id]"  id="categoryId" datatype="*" errormsg="请选择关键词分类！" sucmsg=" ">
								<option value="">请选择类别</option>
								<option <?php if($keyword['category_id'] == 0): ?>selected="selected"<?php endif; ?> value="0">无分类</option>
								<?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($item['id'] == $keyword['category_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($item["id"]); ?>"><?php if(($item["count"]) != "1"): echo (fill_up_string($item['count'],"&nbsp;&nbsp;")); ?>├<?php endif; ?> <?php echo ($item["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</div>
					</dd>
				</dl>
				<dl>
					<dt>是否热搜</dt>
					<dd>
						<div class="rule-single-checkbox">
							<input id="cbIsHot" type="checkbox" name="data[is_hot]" value="1" <?php if($keyword["is_hot"] == 1): ?>checked="checked"<?php endif; ?>/>
						</div>
					</dd>
				</dl>
				<dl>
					<dt>是否推荐</dt>
					<dd>
						<div class="rule-single-checkbox">
							<input id="cbIsRec" type="checkbox" name="data[is_rec]" value="1" <?php if($keyword["is_rec"] == 1): ?>checked="checked"<?php endif; ?>/>
						</div>
					</dd>
				</dl>
				<dl>
					<dt>关键词</dt>
					<dd><input style="width: 200px;" name="data[keyword]" type="text" id="keywords" class="input normal" datatype="*" sucmsg=" " value="<?php echo ($keyword["keyword"]); ?>" /> <span class="Validform_checktip">*</span></dd>
				</dl>
				<dl>
					<dt>英文别名</dt>
					<dd><input style="width: 200px;" name="data[alias_name]" type="text" id="alias_name" class="input normal" datatype="*"  sucmsg=" " ajaxurl="<?php echo U('Admin/Keywords/aliasNameValidate');?>?old_name=<?php echo ($keyword["alias_name"]); ?>"  value="<?php echo ($keyword["alias_name"]); ?>" /></dd>
				</dl>
				<dl>
					<dt>点击次数</dt>
					<dd><input style="width: 200px;" name="data[count]" type="text" class="input normal" datatype="n" sucmsg=" " value="<?php echo ($keyword["count"]); ?>" /> <span class="Validform_checktip">*</span></dd>
				</dl>
			</div>
			<!--/内容-->
			<!--工具栏-->
			<div class="page-footer">
				<div class="btn-wrap">
					<input type="submit" name="btnSubmit" value="提交保存" id="btnSubmit" class="btn" />
					<input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back(-1);" />
				</div>
			</div>
			<!--/工具栏-->
		</form>
		<script type="text/javascript">
			$(function(){
				$("#keywords").blur(function(){
					setPartWordsByString("#keywords","#alias_name","<?php echo U('Index/getPinyinByChinses');?>");
				});
			})
		</script>
	</body>

</html>