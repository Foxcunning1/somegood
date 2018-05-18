<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>卖家管理中心</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
	<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/css/ui-dialog.css" />
	<script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/zepto/lazyload.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script>
</head>
<body>
	<div class="info-box distribution-box">
		<div class="all-select-box">
			<a href="<?php echo U('Seller/Logistics/editRegion',array('lid'=>$logistics_id,'action'=>'add'));?>" class="add-logistics-btn">+ 新增</a>
			<a href="javascript:;"  class="print-a">批量删除</a>
		</div>
	    <div class="box-title">
	        <div class="th th-info">
				<label for="allSelect" id="allSelectLabel"><input type="checkbox" id="allSelect"/>全选</label>
			</div>
			<div class="th th-status">配送区域</div>
	        <div class="th th-do">操作</div>
	    </div>
	    <div class="list-box">
			<!--提供4个样式-->
			<form action="<?php echo U('Seller/Logistics/delRegion');?>" method="post" id="regionForm">
			<div class="item" id="logistics_list">
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="item-content" id="region_<?php echo ($vo["id"]); ?>">
						<div class="th th-status">
							<input type="checkbox" name="ids[]" value="<?php echo ($vo["id"]); ?>"/>
							<?php echo ($vo["name"]); ?>
						</div>
						<div class="th th-info">
							<?php echo ($vo["logistics_region_name"]); ?>
						</div>
						<div class="th th-do" >
							<a href="javascript:void(0);" onclick="delRegion('<?php echo ($vo["id"]); ?>')" class="btn">删除</a>
							<a href="<?php echo U('Seller/Logistics/editRegion',array('id'=>$vo[id],'lid'=>$logistics_id,'action'=>'edit'));?>" class="btn">修改</a>
						</div>
						<div class="clear"></div>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			</form>
	    </div>
	</div>
	<div class="pagelist">
		<div id="PageContent" class="default"><?php echo ($pageShow); ?></div>
	</div>
</div>
	<script>
		function delRegion(ids) {
            $.ajax({
                url:"<?php echo U('Seller/Logistics/delRegion');?>",
                type:"post",
                dataType:"json",
                data:{
                    'ids':ids,
                },
                success:function(data){
                    if(data.status==1){
                        var d = dialog({content:data.info}).show();
                        setTimeout(function () {
                            d.close().remove();
                        }, 1500);
                        $("#region_"+ids).remove();
                    }else{
                        var d = dialog({content:data.info}).show();
                        setTimeout(function () {
                            d.close().remove();
                        }, 1500);
                    }
                }
            });
        }
        $(".print-a").click(function(){
                if($(".item-content").find("input:checkbox:checked").length){
                    $('#regionForm').ajaxSubmit({
                        url: $("#regionForm").attr("url"),
                        type: "post",
                        dataType: "json",
                        success: function(data){
                            var d = dialog({content:data.info}).show();
                            setTimeout(function () {
                                d.close().remove();
                            }, 1500);
                            window.location.reload();
						}
                    });
                }else{
                    var d = dialog({title:"温馨提示",content:"请选择要删除的配送区域！！！"}).show();
                    setTimeout(function () {
                        d.close().remove();
                    }, 1500);
                }
        })
		/*全选*/
        function allChecked(obj){
            if($(obj).find("input").is(":checked")){
                $(".item-content").find("input[type='checkbox']").prop("checked",true);
            }else{
                $(".item-content").find("input[type='checkbox']").prop("checked",false);

            }
        }
		/*单选*/
        function allsingle(obj){
            var Len = $(".item-content").find("input:checkbox:checked").length;
            var len = $(".item-content").find("input:checkbox").length;
            if($(obj).is(":checked")){
                $(obj).prop("checked",true);
                if(Len==len){
                    $("#allSelectLabel").find("input").prop("checked",true);
                }
            }else{
                $(obj).prop("checked",false);
                $("#allSelectLabel").find("input").prop("checked",false);
            }

        }
        $("#allSelectLabel").click(function(){
            allChecked(this);
        })
        $(".item-content").find("input[type='checkbox']").click(function(){
            allsingle(this);
        })
	</script>
</body>
</html>