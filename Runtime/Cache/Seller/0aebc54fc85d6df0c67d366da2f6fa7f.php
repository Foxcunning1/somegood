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
	<div class="tab-box">
	    <ul class="tab-ul fl">

	        <li <?php if(($pageType) == "1"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Order/index');?>?pageType=1">待发货</a><div class="move"></div></li>
	        <li <?php if(($pageType) == "2"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Order/index');?>?pageType=2">待完成</a><div class="move"></div></li>
	        <li <?php if(($pageType) == "3"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Order/index');?>?pageType=3">已完成</a><div class="move"></div></li>
	        <li <?php if(($pageType) == "0"): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Order/index');?>?pageType=0">待付款</a><div class="move"></div></li>
	    </ul>
	    <div class="search-box fr">
			<form action="" method="post">
				<input type="text" class="search-input" placeholder="订单号|收货人|收货人手机号" name="keywords" value="<?php echo ($keywords); ?>"/>
				<input type="hidden" name="pageType" value="<?php echo ($pageType); ?>">
				<input type="submit" class="search-btn" value="搜索"/>
			</form>
	    </div>
	</div>
	<div class="info-box">
	    <div class="box-title">
	        <div class="th th-info">订单详情</div>
	        <div class="th th-people">收货人</div>
	        <div class="th th-pay">订单金额</div>
	        <div class="th th-status">订单状态</div>
	        <div class="th th-do">操作</div>
	    </div>
	   <!-- <div class="all-select-box">
	        <label for="allSelect" id="allSelectLabel"><input type="checkbox" id="allSelect"/>全选</label>
	    </div>-->
	    <div class="list-box">
			<!--提供4个样式-->
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><div class="item" id="item<?php echo ($item["order_sn"]); ?>">
					<div class="item-title-bg">
						<!--<input type="checkbox" class="fl"/>-->
						<div class="order-num fl">订单号：<span><?php echo ($item["order_sn"]); ?></span></div>
						<?php switch($pageType): case "1": ?><div class="time fr">付款时间：<span><?php echo (date("Y-m-d H:i:s",$item["pay_time"])); ?></span></div><?php break;?>
							<?php case "2": ?><div class="time fr">发货时间：<span><?php echo (date("Y-m-d H:i:s",$item["ship_time"])); ?></span></div><?php break;?>
							<?php case "3": ?><div class="time fr">完成时间：<span><?php echo (date("Y-m-d H:i:s",$item["complete_time"])); ?></span></div><?php break;?>
							<?php case "0": ?><div class="time fr">下单时间：<span><?php echo (date("Y-m-d H:i:s",$item["add_time"])); ?></span></div><?php break; endswitch;?>
					</div>
					<div class="item-content">
						<div class="th th-info">
							<?php if(is_array($item["goods_thumb"])): $i = 0; $__LIST__ = $item["goods_thumb"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gt): $mod = ($i % 2 );++$i;?><div class="order-goods-list">
									<img src="/somegood/Public/statics/mobile/images/default_img.png" data-original="/somegood/Public/uploads/<?php echo ($gt); ?>_c200x200" alt="商品图" height="70" height="70">
									<a href="javascript:void(0);" class="good-name fl"><?php echo ($item["goods_title"]["$key"]); ?></a>
								</div><?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
						<div class="th th-people">
							<span><?php if(($item["delivery_way"]) == "0"): ?>到店购买<?php else: echo ($item["consignee"]); endif; ?></span>
							<!--详细信息-->
							<div class="consignee-more">
								<span class="pointer"></span>
								<dl>
									<dt><?php echo ($item["consignee"]); ?></dt>
									<dd><?php echo ($item["address"]); ?></dd>
									<dd><?php echo ($item["mobile"]); ?></dd>
								</dl>
							</div>
						</div>
						<div class="th th-pay"><span>￥<?php echo ($item["money"]); ?></span></div>
						<div class="th th-status">
							<?php switch($pageType): case "1": ?><span class="order-status">买家已付款</span>
									<a href="<?php echo U('Seller/Order/orderDetail');?>?order_sn=<?php echo ($item["order_sn"]); ?>" target="StoreContent" class="order-info-a">订单详情</a><?php break;?>
								<?php case "2": ?><span class="order-status">已发货</span>
									<a href="<?php echo U('Seller/Order/orderDetail');?>?order_sn=<?php echo ($item["order_sn"]); ?>" target="StoreContent" class="order-info-a">订单详情</a><?php break;?>
								<?php case "3": ?><span class="order-status">买家已确认收货</span>
									<a href="<?php echo U('Seller/Order/orderDetail');?>?order_sn=<?php echo ($item["order_sn"]); ?>" target="StoreContent" class="order-info-a">订单详情</a><?php break;?>
								<?php case "0": ?><span class="order-status">买家未付款</span>
									<a href="<?php echo U('Seller/Order/orderDetail');?>?order_sn=<?php echo ($item["order_sn"]); ?>" target="StoreContent" class="order-info-a">订单详情</a><?php break; endswitch;?>
						</div>
						<div class="th th-do">
							<?php switch($pageType): case "1": ?><a href="javascript:;" class="btn set-out" data-id="<?php echo ($item["order_sn"]); ?>">发货</a><?php break;?>
								<?php case "2": ?><a href="<?php echo U('Seller/Order/orderLogistics');?>?nu=<?php echo ($item["order_sn"]); ?>" class="btn">查看物流</a><?php break;?>
								<?php case "3": ?><a href="<?php echo U('Seller/Order/orderLogistics');?>?nu=<?php echo ($item["order_sn"]); ?>" class="btn">查看物流</a><?php break;?>
								<?php case "0": if($item['delivery_way'] == 1 and $item['is_son'] == 2): ?><a href="javascript:;" class="btn change-price" data-value="<?php echo ($item["order_sn"]); ?>">改价</a><?php endif; break; endswitch;?>

						</div>
						<div class="clear"></div>
					</div>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
	    </div>
	</div>
	<div class="pagelist">
		<div id="PageContent" class="default"><?php echo ($pageShow); ?></div>
	</div>

</div>
<script>
    $(function(){
        $(".order-goods-list img").lazyload({effect : "fadeIn"});
    })
	/*发货*/
    $(".item-content").find(".set-out").click(function() {
        var orderSn=$(this).attr("data-id");
        $.ajax({
        type: "POST",
        dataType:'json',
        url:'<?php echo Seller/Order/index;?>',
        data: {
            orderSn: orderSn
        },
        async: false,
        error: function(request) {

        },
        success: function(data) {
            //接收后台返回的结果
            window.logisticsName=data.data[0].name;
            window.logisticsId=data.data[0].id;
        }
        });
        var d = dialog({
            title:"填写发货信息",
            content: "<div class='set-out-form'>" +
            "<form method='post' id='deliverForm' url='<?php echo U('Mobile/Seller/deliverGoods');?>'>" +
            "<dl>" +
            "<dt>选择物流</dt>" +
            "<dd>" +
            "<select name='data[type]' id='delive_type'>" +
            "<option value='"+logisticsId+"'>"+logisticsName+"</option>" +
            "</select>" +
            "</dd>" +
            "</dl>" +
            "<dl>" +
            "<dt>订单号</dt>" +
            "<dd>" +
            "<input type='text' id='logistics_sn' name='data[logistics_sn]' placeholder='请输入物流单号...'></dd>" +
            "<input type='hidden' id='order_sn' name='data[order_sn]' value='"+$(this).attr("data-id")+"'></dd>" +
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
        var delive_type = $("#delive_type").val();
        var logistics_sn = $("#logistics_sn").val();
        var order_sn = $("#order_sn").val();
        if(logistics_sn==""){
            var d = dialog({content:"请输入快递单号！"}).show();
            setTimeout(function () {
                d.close().remove();
            }, 1500);
        }else{
            $.ajax({
                type: "POST",
                url: "<?php echo U('Mobile/Seller/deliverGoods');?>",
                data: {"data[type]":delive_type,"data[logistics_sn]":logistics_sn,"data[order_sn]":order_sn},
                dataType: 'json',
                success: function(data){
                    if(data.status==1){
                        var d = dialog({content:data.info}).show();
                        setTimeout(function () {
                            d.close().remove();
                        }, 1500);
                        $("#item"+order_sn).remove();
                    }else{
                        var d = dialog({content:data.info}).show();
                        setTimeout(function () {
                            d.close().remove();
                        }, 1500);
                    }
                }
            });
        }
    }
	/*改价*/
    $(".item-content").find(".change-price").click(function() {
        var d = top.dialog({
            title:"价格修改",
            content: "<div class='change-price-form'>" +
            "<form action=''>" +
            "<dl>" +
            "<dt>新价格</dt>" +
            "<dd>" +
            "<input type='text' id='edit_price' ></dd>" +
            "<input type='hidden' id='order_sn' value='"+$(this).attr("data-value")+"'></dd>" +
            "</dl>" +
            "</form>" +
            "</div>",
            okValue: '确定',
            ok: function () {
                bindSubmitBtn1();
                this.title('提交中…');
                return true;
            },
            cancelValue: '取消',
            cancel: function () {}
        }).width(300).height(90).showModal();
    })
    function bindSubmitBtn1() {
            var edit_price = $("#edit_price").val();
            var order_sn =$('#order_sn').val();
            $.ajax({
                type: "POST",
                url: "<?php echo U('Seller/Order/editPrice');?>",
                data: {"order_sn":order_sn,"edit_price":edit_price},
                dataType: 'json',
                success: function(data){
                    if(data.status==1){
                        var d = dialog({content:data.info}).show();
                        setTimeout(function () {
                            d.close().remove();
                        }, 800);
                        setTimeout(function(){
                            window.location.href="/somegood/Seller/Order/index.html?pageType=0";
                        },800)
                    }else{
                        var d = dialog({content:data.info}).show();
                        setTimeout(function () {
                            d.close().remove();
                        }, 800);
                    }
                },
                error:function (data) {
                }
            });
    }
    $(".tab-ul li").click(function(){
     $(this).addClass("sel").siblings().removeClass("sel");
    })
    /*全选*/
    function allChecked(obj){
        if($(obj).find("input").is(":checked")){
            $(".list-box").find("input[type='checkbox']").prop("checked",true);
        }else{
            $(".list-box").find("input[type='checkbox']").prop("checked",false);

        }
    }
    /*单选*/
    function allsingle(obj){
        var Len = $(".list-box").find(".item input:checkbox:checked").length;
        var len = $(".list-box").find(".item input:checkbox").length;
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
    $(".list-box").find("input[type='checkbox']").click(function(){
        allsingle(this);
    })
</script>
</body>
</html>