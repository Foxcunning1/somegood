<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="ui-switchable-panel <?php if($vo['is_default'] == 1): ?>ui-switchable-panel-selected<?php endif; ?>" id="address1" >
	<div class="consignee-item <?php if($vo['is_default'] == 1): ?>item-selected<?php endif; ?>" data-address-id="<?php echo ($vo["address_id"]); ?>"><span><?php echo ($vo["consignee"]); ?></span><b></b></div>
	<div class="addr-detail">
		<span class="addr-name"><?php echo ($vo["consignee"]); ?></span>
		<span class="addr-info"><?php echo ($vo["iname"]); echo ($vo["oname"]); echo ($vo["rname"]); echo ($vo["address"]); ?></span>
		<span class="addr-tel"><?php echo ($vo["mobile"]); ?></span>
		<span class="addr-default">默认地址</span>
	</div>
	<div class="op-btns">
		<?php if($vo['is_default'] == 0): ?><a href="javascript:;" class="ftx-05 setdefault-consignee">设为默认地址</a><?php endif; ?>
		<a href="javascript:;" class="ftx-05 edit-consignee" data-editid="<?php echo ($vo["address_id"]); ?>">编辑</a>
		<a href="javascript:;" class="ftx-05 del-consignee" onclick="delAddress(<?php echo ($vo["address_id"]); ?>,delajaxUrl,$(this))">删除</a>
	</div>
</li><?php endforeach; endif; else: echo "" ;endif; ?>
<input type="hidden" name="data[address_id]" value="<?php echo ($defaultId); ?>" id="selAddressId">