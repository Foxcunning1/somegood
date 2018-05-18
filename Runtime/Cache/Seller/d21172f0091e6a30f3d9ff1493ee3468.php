<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>卖家管理中心</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
	<script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
</head>
<body style="background-color: #edf1f2;">
<div class="all-money-box">
	<div class="left fl">
		<div class="top"><?php echo ($info["goods_title"]); ?></div>
		<div class="center"><?php echo ($info["content"]); ?></div>
		<div class="bottom">￥<span class="get-in"><?php echo ($info["price"]); ?>元</span></div>
	</div>
	<div class="right fl">
		<dl>
			<dt>销售/库存状况</dt>
			<dd>线下总销量:<?php echo ($info["total_sales_volume"]); ?><span class="today-in"></span></dd>
			<dd>线上销量:<?php echo ($info["online_sales_volume"]); ?><span class="today-in"></span></dd>
			<dd>线上库存:<?php echo ($info["online_stock"]); ?><span class="today-in"></span></dd>
			<dd></dd>
		</dl>
	</div>
</div>

<div class="info-box">
	<table class="info-table">
		<thead>
		<tr>
			<th>序号</th>
			<th>卖家名称</th>
			<th>所在区域</th>
			<th>卖家地址</th>
			<th>库存</th>
			<th>销量</th>
			<th>过期时间</th>
			<th>状态</th>
			<!-- <th>操作</th> -->
		</tr>
		</thead>
		<tbody>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php echo ($vo["store_name"]); ?></td>
				<td><?php echo ($vo["area_name"]); ?></td>
				<td><?php echo ($vo["address"]); ?></td>
				<td><?php echo ($vo["stock"]); ?></td>
				<td><?php echo ($vo["sales_volume"]); ?></td>
				<td>
					<?php if(($vo["is_delivery"]) != "0"): echo (date("Y-m-d ",$vo['end_time'])); endif; ?>
				</td>
				<td>
					<?php if(($vo["is_delivery"]) == "0"): ?>未投放
						<?php else: ?>
						<?php if(($vo["end_time"]) > ""): ?>在售
							<?php else: ?>
							已过期<?php endif; endif; ?>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
</div>
<div class="pagelist">
	<div id="PageContent" class="default"><?php echo ($list['page']); ?></div>
</div>
</body>
</html>