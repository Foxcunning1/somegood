// JavaScript Document
/*-----------------结算页js-----------------*/

/*支付方式选择
* @Param            obj                obj                  操作按钮
*/
function payTypeSel() {
	$(".payment-item").on('click', '.selector', function(event) {
		event.preventDefault();
		/* Act on the event */
		if ($(this).text() == '到店购买') {
			var payType = 0;
		}else {
			var payType = 1;
		}
		$("#payType").val(payType);
	});
}



/*选择收货地址id
* @Param            addressId                obj                  收货地址id
*/
function selAddressId(ajax_url) {
	$("#consigneeList").on('click',".consignee-item",function(event) {
		event.preventDefault();
		/* Act on the event */
		if (!$(this).hasClass('item-selected')) {
			var addressId=$(this).data('addressId');
			$("#selAddressId").val(addressId);    //收货地址id
			//局部刷新配送方式div块
			$(".loading-box").show();
			var d=refreshDeliveryWay(addressId,ajax_url);
			if (d == true) {
					$(".loading-box").hide();
			}
		}

	});
}
	/**局部刷新配送方式div块
	*@params     address_id        收货地址id            int
	*@params     ajax_url           url地址                   str
	**/
	function refreshDeliveryWay(address_id,ajax_url) {
		$(".dis-modes").children().remove();  //移除收货地址列表
		//ajax获取新的列表
		$.ajax({
			data:{
				address_id:address_id,
			},
			type: 'POST',
			url: ajax_url,
			dataType:"html",
			success: function (data) {
					$(".dis-modes").append(data);
					showOrhideDiv();
			}
		})
		return true;
	}
	/**判断div块是否需要移除
	*
	**/
	function showOrhideDiv(){
		var length =$(".dis-modes").length;
		for (var s = 0; s < length; s++) {
			for (var i = 0; i < $(".dis-modes").eq(s).children().length; i++) {
				if ($(".dis-modes").eq(s).data("sid") == $(".dis-modes").eq(s).children().eq(i).data("sid")) {
					//$(".dis-modes").eq(s).children().eq(i).show();
				}else {
					$(".dis-modes").eq(s).children().eq(i).remove();
				}
			}
		}


	}

	console.log("母鸡对公鸡说：“亲爱的，我怀了你的孩子。");
	console.log("公鸡不耐烦地甩出十块钱说：");
	console.log("“这钱拿去找个靠谱的超市，买个打蛋器把孩子打了吧。”");
