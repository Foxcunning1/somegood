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
	        <div class="th th-info">快递/物流公司</div>
			<div class="th th-status">备注</div>
	        <div class="th th-do">操作</div>
	    </div>
	    <div class="list-box">
			<!--提供4个样式-->
			<div class="item" id="logistics_list">
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="item-content">
						<div class="th th-info">
							<?php echo ($vo["logistics_name"]); ?>
						</div>
						<div class="th th-status">
							<span class="order-status" onclick="editRemarks(this,'<?php echo ($vo["id"]); ?>','<?php echo ($vo["remarks"]); ?>')"><?php if(empty($vo["remarks"])): ?>无<?php else: echo ($vo["remarks"]); endif; ?></span>
						</div>
						<div class="th th-do"  id="planStatus_<?php echo ($vo["id"]); ?>">
							<?php if(($vo["is_on"]) == "0"): ?><a href="javascript:void(0);" onclick="startPlan('<?php echo ($vo["id"]); ?>',1)" class="btn">启用</a>
								<?php else: ?>
								<a href="javascript:void(0);" onclick="startPlan('<?php echo ($vo["id"]); ?>',0)" class="btn">禁用</a>
								<a href="<?php echo U('Seller/Logistics/list');?>?id=<?php echo ($vo["id"]); ?>" class="btn">编辑配送区域</a><?php endif; ?>

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
	function startPlan(id,is_on) {
		var ajax_url="<?php echo U('Seller/Logistics/start');?>"
		$.ajax({
			type: "POST",
			url: ajax_url,
			dataType: 'json',
			data: {
				id:id,
				is_on:is_on,
			},
			success: function(data){
				//判断返回状态
				if(data.status==0){
					parent.jsprint(data.info,"");
				}else{
					var str='';
					switch(is_on){
						case 1:
							str+="<a href=\"javascript:void(0);\" onclick=\"startPlan('"+data.data+"',0)\" class=\"btn\">禁用</a> <a href=\"<?php echo U('Seller/Logistics/list');?>?id="+data.data+"\" class='btn'>编辑配送区域</a>";
							break;
						case 0:
							str+="<a href=\"javascript:void(0);\" onclick=\"startPlan('"+data.data+"',1)\" class=\"btn\">启用</a>";
							break;
					}
					$("#planStatus_"+data.data+"").html(str);
				}
			}
		});
	}
	/*新增*/
	$(".info-box").find(".all-select-box").click(function() {
		var d = dialog({
			title:"新增配送方式",
			content: "<div class='set-out-form'>" +
			"<form method='post' id='deliverForm' url='<?php echo U('Seller/Logistics/addLogistics');?>'>" +
			"<dl>" +
			"<dt>选择物流</dt>" +
			"<dd>" +
			"<select name='data[logistics_id]' id='logistics_id'>" +
			<?php if(is_array($logistics_list)): $i = 0; $__LIST__ = $logistics_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>"<option value='<?php echo ($item["id"]); ?>'><?php echo ($item["name"]); ?></option>" +<?php endforeach; endif; else: echo "" ;endif; ?>
			"</select>" +
			"</dd>" +
			"</dl>" +
			"<dl>" +
			"<dt>备注</dt>" +
			"<dd>" +
			"<input type='text' id='remarks' name='data[remarks]' placeholder='备注,如地址电话等...'></dd>" +
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
		var logistics_id = $("#logistics_id").val();
		var remarks = $("#remarks").val();
		var logistics_name=$("#logistics_id").find("option:selected").text();
			$.ajax({
				type: "POST",
				url: "<?php echo U('Seller/Logistics/addLogistics');?>",
				data: {"data[logistics_id]":logistics_id,"data[remarks]":remarks},
				dataType: 'json',
				success: function(data){
					if(data.status==1){
						var d = dialog({content:"添加成功"}).show();
						setTimeout(function () {
							d.close().remove();
						}, 1500);
						var str="";
						str +="<div class=\"item-content\">"+
						"  <div class=\"th th-info\">"+logistics_name+"</div>"+
						" <div class=\"th th-status\">"+
						"  <span class=\"order-status\">"+remarks+"</span>"+
						"</div>"+
						"<div class=\"th th-do\"  id=\"planStatus_"+data.info+"\">"+
						"  <a href=\"javascript:void(0);\" onclick=\"startPlan('"+data.info+"',1)\" class=\"btn\">启用</a>"+

						" </div>"+
						"<div class=\"clear\"></div>"+
						" </div>";
						$('#logistics_list').append(str);
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