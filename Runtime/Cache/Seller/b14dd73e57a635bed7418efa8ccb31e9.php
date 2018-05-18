<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>商品列表</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
	<script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery.lazyload.min.js"></script>
</head>
<body style="background-color: #edf1f2;overflow-y: auto;">
<div class="tab-box">
	<ul class="tab-ul fl">
		<li <?php if(($type) == "online"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Adv/index',array('type'=>'online'));?>">线上推广</a><div class="move"></div></li>
		<li <?php if(($type) == "store"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Adv/index',array('type'=>'store'));?>">线下店铺推广</a><div class="move"></div></li>
	</ul>
</div>
<div class="info-box">
	<table class="info-table">
		<?php switch($type): case "store": ?><thead>
				<tr>
					<th>序号</th>
					<th>广告名称</th>
					<th>店铺名称</th>
					<th>所在区域</th>
					<th>地址</th>
					<th>广告位类型</th>
					<th>广告位ID</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>状态</th>
					<!-- <th>操作</th> -->
				</tr>
				</thead>
				<tbody>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						<td><?php echo ($vo["id"]); ?></td>
						<td><?php echo ($vo["title"]); ?></td>
						<td><?php echo ($vo["store_name"]); ?></td>
						<td><?php echo ($vo["area"]); ?></td>
						<td><?php echo ($vo["address"]); ?></td>
						<td><?php echo ($vo["pos_title"]); ?></td>
						<td><?php echo ($vo["adv_id"]); ?></td>
						<td align="center"><?php echo (date('Y-m-d  H:i:s',$vo["begin_time"])); ?></td>
						<td align="center"><?php echo (date('Y-m-d  H:i:s',$vo["end_time"])); ?></td>
						<td align="center">
					<span>
						<?php if(($vo["is_delivery"]) == "1"): ?><b style="color: green">已投放</b>
							<?php else: ?>
							待投放<?php endif; ?>
					</span>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody><?php break;?>
			<?php case "online": ?><thead>
				<tr>
					<th>序号</th>
					<th>广告名称</th>
					<th>所属广告位</th>
					<th>链接URL</th>
					<th>开始时间</th>
					<th>结束时间</th>
				</tr>
				</thead>
				<tbody>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						<td><?php echo ($vo["id"]); ?></td>
						<td><?php echo ($vo["title"]); ?></td>
						<td><?php echo ($vo["pos_title"]); ?></td>
						<td><?php echo ($vo["url"]); ?></td>
						<td align="center"><?php echo (date('Y-m-d  H:i:s',$vo["begin_time"])); ?></td>
						<td align="center"><?php echo (date('Y-m-d  H:i:s',$vo["end_time"])); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody><?php break; endswitch;?>
	</table>
</div>
<div class="pagelist">
	<div id="PageContent" class="default"><?php echo ($pageShow); ?></div>
</div>
</body>
</html>