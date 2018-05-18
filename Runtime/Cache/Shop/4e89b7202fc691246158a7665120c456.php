<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="mode-item mode-tab" data-sid="<?php echo ($key); ?>"  >
	<div class="mode-item-tit">
		<h4>配送方式</h4>
	</div>
	<div class="mode-tab-nav">
		<select class="mode-tab-item" name="delivery[<?php echo ($key); ?>][id]" style="width:108px;height:25px;" >
			<option value="" style="width:108px;height:25px;" >--请选择--</option>
			<?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vs["id"]); ?>" style="width:108px;height:25px;" ><?php echo ($vs["value"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		</select>
		<!-- <ul> -->
			<!-- <li class="mode-tab-item curr">快递运输</li> -->
			<!-- <li class="mode-tab-item" data-logisticsid="<?php echo ($vo["id"]); ?>"><?php echo ($vo["value"]); ?></li>
		</ul> -->
	</div>
	<!-- <input type="checkbox" name="delivery[<?php echo ($k); ?>][<?php echo ($vo["seller_id"]); ?>]" checked="checked" value="" style="display:none;">
	<input type="checkbox" name="delivery[<?php echo ($k); ?>][<?php echo ($vo["id"]); ?>]" checked="checked" value="" style="display:none;">
	<input type="checkbox" name="delivery[<?php echo ($k); ?>][<?php echo ($vo["value"]); ?>]" checked="checked" value="" style="display:none;"> -->
</div><?php endforeach; endif; else: echo "" ;endif; ?>