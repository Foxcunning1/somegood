<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>卖家管理中心</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
	<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/css/ui-dialog.css" />
	<script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
</head>
<body>
	<div class="info-box logistics-box">
		<div class="all-select-box">
			<a href="javascript:;" class="add-logistics-btn">+ 新增</a>
		</div>
	    <div class="box-title">
	        <div class="th th-info">名称</div>
	        <div class="th th-do">操作</div>
	    </div>
	    <div class="list-box">
			<!--提供4个样式-->
			<div class="item" id="logistics_list">
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="item-content">
						<div class="th th-info" style="text-align: left;padding-left: 50px;">
							<?php if(($vo["count"]) == "2"): ?>——<?php endif; ?>
							<?php echo ($vo["name"]); ?>
						</div>
						<div class="th th-do"  id="planStatus_<?php echo ($vo["id"]); ?>">
							<?php if(($vo["count"]) == "1"): ?><a href="javascript:void(0);" class="btn all-select-box">添加子栏目</a>
								<?php else: endif; ?>
								<a href="javascript:void(0);" class="btn">删除</a>

						</div>
						<div class="clear"></div>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
	    </div>
	</div>
	<div class="pagelist">
		<div id="PageContent" class="default"><?php echo ($pageShow); ?></div>
	</div>
</div>
<script>
    function editRemarks(obj,objId,old_value){
        var value=$(obj).text();
        if(value){
            $(obj).html("<input class='focusInput' type='text' id="+objId+" name='remarks' value="+value+">");
            $(obj).children("input").focus().blur(function(){
                if($(obj).children("input").val()!=old_value){
                    $.ajax({
                        type: "POST",
                        url: "<?php echo U('Seller/Logistics/editRemarks');?>",
                        dataType: 'json',
                        data: "id="+objId+"&value="+ $(obj).children("input").val()+"&old_value="+old_value,
                    });
                }else{
                    $(obj).html(old_value);
                }
            });
        }
    }

	/*新增*/
	$(".info-box").find(".all-select-box").click(function() {
		var d = dialog({
			title:"新增",
			content: "<div class='set-out-form'>" +
			"<form method='post' id='deliverForm' url='<?php echo U('Seller/Index/addColumn');?>'>" +
			"<dl>" +
			"<dt>选择父栏目</dt>" +
			"<dd>" +
			"<select name='data[parent_id]' id='parent_id'>" +
            "<option value='0'>顶级栏目</option>" +
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if(($item["parent_id"]) == "0"): ?>"<option value='<?php echo ($item["id"]); ?>'><?php echo ($item["name"]); ?></option>" +<?php endif; endforeach; endif; else: echo "" ;endif; ?>
			"</select>" +
			"</dd>" +
			"</dl>" +
			"<dl>" +
			"<dt>名称</dt>" +
			"<dd>" +
			"<input type='text' id='name' name='data[name]' placeholder='栏目名'></dd>" +
			"</dl>" +
			"</form>" +
			"</div>",
			okValue: '确定',
			ok: function () {
				bindSubmitBtn();
				this.title('提交中…');
				return true;
			},
			cancelValue: '取消',
			cancel: function () {}
		}).width(300).height(130).show();
	})
	function bindSubmitBtn() {
		var parent_id = $("#parent_id").val();
		var name = $("#name").val();
			$.ajax({
				type: "POST",
				url: "<?php echo U('Seller/Index/addColumn');?>",
				data: {"data[parent_id]":parent_id,"data[name]":name},
				dataType: 'json',
				success: function(data){
					if(data.status==1){
						var d = dialog({content:data.info}).show();
						setTimeout(function () {
							d.close().remove();
							window.location.href="<?php echo U('Seller/Index/column');?>";
						}, 1500);
					}else{
						var d = dialog({content:data.info}).show();
						setTimeout(function () {
							d.close().remove();
						}, 1500);
					}
				}
			});
	}
</script>
</body>
</html>