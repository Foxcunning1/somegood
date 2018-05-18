// JavaScript Document
/*-----------------购物车js-----------------*/
	/*购物车数量加减函数
	* Param            obj                obj                  操作按钮
	* Param            string             calcType             计算类型
	* Param            bool               isInstallment        是否分期
	* Param            string             ajax_url             填写数字后，后台更新数量的地址
	* Param            int                goodsId              商品ID
	*/
	function calcAmount(obj,calcType,isInstallment,ajax_url,goodsId){
		var num = parseInt($(obj).parent("div.amount-box").find(".item-amount").val());
		if(isNumber(num)){
			if(calcType=='subtract'){//减法操作
				if(num>1){
					num--;
				}else{
					num=1;
				}
			}else{//加法操作
				num++
			}
		}else{
			num = 1;
		}
		$(obj).parent("div.amount-box").find(".item-amount").val(num);
		/*Ajax更新购物车数量*/
		updateCartGoods(ajax_url,goodsId,num);
		updateItemMoney(obj,num,isInstallment);//执行单行价格运算
	}
	 /*输入框填写数字进行数量更新
	 * Param         obj               obj                 当前选择的对象
	 * Param         bool              isInstallment       是否分期
	 * Param         string            ajax_url            填写数字后，后台更新数量的地址
	 * Param         int               goodsId             商品ID
	 * Param         int               advisorId           服务人员ID
	 */
	function keyUpAmount(obj, isInstallment, ajax_url, goodsId){
		var num = $(obj).val();
		if(!isNumber(num)||num<1){
			num = 1;
		}
		$(obj).val(num);
		/*Ajax更新购物车数量*/
		updateCartGoods(ajax_url,goodsId,num);
		updateItemMoney(obj,num,isInstallment);//执行单行价格运算
	}
	/*金额更新
	*Param        obj          obj                     当前操作的对象
	*Param        int          amount                  数量
	*Param        bool         isInstallment           计算类型：0为加法，1为减法
	*/
	function updateItemMoney(obj,amount,isInstallment){
		var tObj = $(obj).parent().parent().parent().siblings(".price").find(".goods-price");//获取当前行的单价
		var totalMoney = tObj.text().replace(/¥/,'');
		var unitPrice = 0;
		if(chkIntOrFloat(totalMoney)){//验证价格是否合法
			if(isInstallment){
				unitPrice = parseFloat(totalMoney/12).toFixed(2);
			}else{
				unitPrice = totalMoney;
			}
			unitPrice = unitPrice*amount;//计算总价格
			unitPrice = unitPrice.toFixed(2);
			$(obj).parent().parent().parent().siblings(".pay-amount").find(".amount-money").text("¥"+unitPrice);
			//执行总价格计算
			cartCount();
		}else{
			alert(totalMoney);
			alert("亲，系统检测到您在非法操作价格计算！");
		}
	}
	/*更新购物车信息
	*Param         string            goods_id                    商品ID与服务人员ID组合
	*/
	function updateCartGoods(ajax_url, goods_id, num,cartId){
		if(goods_id!=""){
			$.ajax({
				url:ajax_url,//ajax地址
				type:"POST",
				data:{goodsid:goods_id,counts:num,cartId:cartId},//post的数据
				dataType:'json',
				success:function(data,textStatus){//提交成功
					if(data.status==1){//更新成功
						//不需要提示更新成功！
						$.each(data.data,function(i, item){

						})
					}
				}
			})
		}
	}
	//删除购物车商品
	function delCartGoodsa(ajaxUrl,cartId){
		var box_element ='#item';
		//ajax删除商品并移除当前行的html
		$.ajax({
			url:ajaxUrl,//ajax地址
			type:"POST",
			data:{goodsid:cartId},//post的数据
			dataType:'json',
			success:function(data,textStatus){
				//提交成功
				if(data.status==1){
					//删除成功
					delCartItem(cartId, box_element);
					//删除节点
					cartCount();
				 //执行价格计算
					//删除当前商品对应的item
					//如果当前的购物车为空,刷新页面
					if ($(".item-box").length == 0) {
							window.location.reload();
					}
				}else{
					alert('删除失败!');
					// if(is_dialog){
					// 	//调取dialog插件提示用户
					// }
				}
			},
		});
	}
    /*购物车全选函数
     *Param       obj             被点击的对象
     *Param       type            全选类型 0为选择所有，包括分期与不分期, 1为分期，2为不分期
     */
    function checkAllCart(obj,type){
    	var o = $(obj);
        //判断当前全选的类型
        var targetChkBox,itemBox;
        switch (type){
            case 0://全选所有
                targetChkBox = $(".chk-box");
                itemBox =  $(".item-box");
                break;
            case 1:
                targetChkBox = $("#installmentList").find(".chk-box");
                itemBox =  $("#installmentList").find(".item-box");
                break;
            case 2:
                targetChkBox = $("#normalList").find(".chk-box");
                itemBox =  $("#normalList").find(".item-box");
                break;
        }
        //判断当前选择框是否已选择
        if(o.hasClass('chk-selected-box')){//已选择，执行取消操作
            o.removeClass('chk-selected-box');
            targetChkBox.removeClass('chk-selected-box');
            itemBox.find("input.chkBox").prop("checked",false);
        }else{//未全选，执行全选操作
            //if(type==1||type==2){
                //o.addClass('chk-selected-box');
            //}
            targetChkBox.addClass('chk-selected-box');
            itemBox.find("input.chkBox").prop("checked",true);
        }
        checkCheckedAll();//检测是否要把对应的全选按钮选中
        //遍历所有的checkbox执行价格计算
		cartCount();
    }
	/*更新购物车商品数量
	*Param       obj             被点击的对象
	*Param       str             jquery节点
	**/
    function updateNumAjaxUrl(obj,goods_id,cart_id){
		console.log(obj);
            if(obj.hasClass('subtract-icon')){
                plusOrReduce(obj,1);
				var num =obj.next().val();
            }else{
                plusOrReduce(obj,0);
				var num =obj.prev().val();
            }
            var goods_id =goods_id;
			var cartId  =  cart_id;
            updateCartGoods(ajaxUrlOne, goods_id, num,cartId);
			obj.unbind('click');

    }
	/*数量加减
	 *Param        obj          obj                     当前操作的对象
	 *Param        int         status           计算类型：0为加法，1为减法
	  */
	function plusOrReduce(obj,status){
	    if(status ==0){
			var itemNum = obj.prev().val();
	        itemNum++;
	        obj.prev().val(itemNum);
	    }else{
			var itemNum = obj.next().val();
	        itemNum--;
	        if(itemNum>=1){
	          obj.next().val(itemNum);
	        }
	    }
		cartCount();
	}
    /*检测是否已全选
    * */
    function checkCheckedAll(){
        //首先检测是否已全选
        var imCount = $("#installmentList").find("input[type='checkbox']").length;//分期列表中checkbox的个数
        var normalCount = $("#normalList").find("input[type='checkbox']").length;//非分期列表中checkbox的个数
        var checkedImCount = $("#installmentList").find("input:checkbox:checked").length;//分期列表中已选择的checkbox的数量
        var checkedNormalCount = $("#normalList").find("input:checkbox:checked").length;//非分期列表中已选择的checkbox的数量
        if(checkedImCount==imCount){//分期是否全选
            $(".chkInstallment").addClass("chk-selected-box");
        }else{
            $(".chkInstallment").removeClass("chk-selected-box");
        }
        if(checkedNormalCount==normalCount){//非分期是否全选
            $(".chkNormal").addClass("chk-selected-box");
        }else{
            $(".chkNormal").removeClass("chk-selected-box");
        }
        if(imCount+normalCount == checkedImCount+checkedNormalCount){
            $(".allChk").addClass("chk-selected-box");
        }else{
            $(".allChk").removeClass("chk-selected-box");
        }

    }
    /*单个选择操作
     *Param         obj                  被选择的对象
     *Param         isInstallment        是否分期
     */
    function singleCheckBox(obj,isNormal){
        if($(obj).hasClass('chk-selected-box')){
            $(obj).removeClass('chk-selected-box');
            $(obj).parent("div.td-inner").find("input.chkBox").prop("checked",false);
			cartCount();
        }else{
            $(obj).addClass('chk-selected-box');
            $(obj).parent("div.td-inner").find("input.chkBox").prop("checked",true);
			cartCount();
        }
        //判断当前选中的input个数，当数量等于全部数量的个数，选中分期、非分期、全选按钮
        checkCheckedAll();
    }
    /*购物车总价格结算
    *
    * */
    function cartCount(){
		//获取已选中的checkbox
		var totalNum = 0; //被选中的数量
		var totalPrice = 0.00;//总价
		checkCartIsNull();
		$("input:checkbox:checked").each(function(){
			var curObj = $(this);
			var pObj = curObj.parent().parent().siblings(".price").find(".goods-price");//获取当前行的单价
			var nObj = curObj.parent().parent().siblings(".amount").find(".item-amount");//获取当前行的数量
			var totalMoney = pObj.text().replace(/¥/,'');//替换价格中的¥符号
			var amount = nObj.val();
			//获取当前行是否是分期商品
			//判断当前checkbox的name
			var isInstallment = false;
			if(curObj.attr('name')=='installmentCart[]'){
				isInstallment = true;
			}
			var unitPrice = 0;
			if(chkIntOrFloat(totalMoney)){//验证价格是否合法
				if(isInstallment){
					unitPrice = parseFloat(totalMoney/12).toFixed(2);
				}else{
					unitPrice = totalMoney;
				}
				unitPrice = unitPrice*amount;//计算总价格
			}
			totalNum++;
			totalPrice = totalPrice+unitPrice;
		});
        //选择服务的种类
		//改变结算按钮样式及执行
	    $(".total-num").text(totalNum);
		if(totalPrice==0&&totalNum==0){
			$(".total-price").text("¥0.00");
			$(".submit-btn").removeClass("active-btn");
		}else{
			$(".submit-btn").addClass("active-btn");
			$(".total-price").text("¥"+totalPrice);
			bindActiveSubmit();//结算按钮绑定提交事件
		}
    }
    /*判断购物车页面中是否有商品，如果没有则执行页面刷新
     *
     * */
    function checkCartIsNull(){
    	var num = 0;
    	$("input[type=checkbox]").each(function(){
    		num++;
    	})
    	if(num==0){
    		window.location.reload();
    	}
    }
    /*购物车删除商品
    * param        string         goodsid            商品id组合与服务专员id
    * param        string         ajax_url           ajax地址
    * param        int            is_dialog          是否弹出dialog，ture为弹出，false为不弹出；注：此参数主要防止移入收藏夹操作时重复弹出dialog
    * param        string         box_element        商品对应行的id前缀
    * */
    function delCart(goods_id, ajax_url, is_dialog, box_element){
        $.ajax({
            url:ajax_url,
			//ajax地址
            type:"POST",
            data:{goodsid:goods_id},
			//post的数据
            dataType:'json',
            success:function(data,textStatus){
				//提交成功
                if(data.status==1){
					//删除成功
                	delCartItem(goods_id, box_element);
					//删除节点
    				 cartCount();
					 //执行价格计算
                    if(is_dialog){
						//调取dialog插件提示用户删除成功
                        var d = dialog({content:"删除成功！"}).show();
                        window.setTimeout(function(){
                            d.close().remove()},1000);
						if ($(".item-box").length == 0) {
                                window.location.reload();
                        }
                    }
                    //删除当前商品对应的item
                }else{
                    if(is_dialog){
                        //调取dialog插件提示用户
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if(is_dialog==1){
                    //调取dialog插件
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            }
        });
    }
    /*商品移入收藏夹
    * param        string          goodsid          购物车ids，组合格式如：1-2，多个组合之间以“,”号进行分割
    * param        string          ajax_url         ajax地址
    * param        string          del_ajax_url     删除商品对应的ajax地址
    * param        string          jump_url         调转地址
    * param        string          box_element      商品对应行的id前缀；注：此参数是为了兼容mini购物车的操作
    * */
    function removeCartToFavorites(goods_id,ajax_url, del_ajax_url, jump_url,box_element,ajaxUrl1){
        $.ajax({
            url: ajax_url,
			//ajax地址，后台先执行移入收藏夹操作，然后再进行购物车删除操作
            type: "POST",
            data: {goodsid:goods_id},
			//post的数据
            dataType: "json",
            success:function(data,textStatus){
				//提交成功
                //根据返回状态执行对应操作
                if(data.status==-1){
					//状态根据后台返回数据获取
                    //调取dialog提示用户未登录，并跳转至登录页面
                	var d = dialog({content:"亲，您还没登录，请先登录！"}).show();
                    window.setTimeout(function(){
                        d.close().remove();jumpToUrl(jump_url);},1000);
                }
                if(data.status==1){
					//ajax删除商品并移除当前行的html
                	var d = dialog({content:"移入成功！"}).show();
                    window.setTimeout(function(){
                        d.close().remove()},1000);
						delCartGoodsa(ajaxUrl1,goods_id); //删除购物车商品
					//delCart(goods_id,del_ajax_url, false, box_element);
                }
                if(data.status==0){
                    //调取dialog提示错误信息
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        })
    }
	/*统计当前行的价格
     *
     * */
	 function calcCurrentPrice(){
		 $(".amount-box").on('click',function(){
			 var price = $(this).parent().parent().prev().find(".td-inner.goods-price").text();		//当前行价格
			 var num = $(this).children('.item-amount').val();		//数量
			 price = price.replace(/¥/,'');
			 console.log(price);
			 var currentMoney = parseFloat(price) * parseFloat(num);
			 $(this).parent().parent().next().find(".amount-money").text('¥'+currentMoney.toFixed(2));
		 })
	 }
    /*删除item
	 * Param         string        goodsid           商品ID组合与服务人员ID组合，组合格式如：1-2，多个组合之间以“,”号进行分割
     * Param         string        itemObj           被删除的item元素
     * */
    function delCartItem(goodsid, obj){
		//判断goodsid是多个组合还是一个组合
		var goodsArr = goodsid.split(",");
		for(var i=0; i<goodsArr.length; i++){
			var itemObj = obj + goodsArr[i];
			//判断当前元素是否为item最后一个元素,如果是,则要给上一个元素添加last-item类
			if($(itemObj).hasClass('last-item')){
				var preItem = $(itemObj).pre("div.item");
				//获取当前元素的上一个元素
				preItem.addClass("last-item");
			}
			$(itemObj).remove();

		}
    }
    //页面跳转
    function jumpToUrl(url){
        location.href = url;
    }
	/*验证输入的数字是否合法
	* Param         int           num               数量
	* Return        bool                            返回bool值
	*/
	function isNumber(num)
	{
	   var validChars = "0123456789.";
	   var isNumber=true;
	   var Char;
	   for (i = 0; i < num.length && isNumber == true; i++){
		  Char = num.charAt(i);
		  if (validChars.indexOf(Char) == -1){
			 isNumber = false;
		  }
	   }
	   return isNumber;
	};
	/*验证输入的是否为数字
	* Param        float        num                  数字
	* Return       bool                              返回bool值
	*/
	function chkIntOrFloat(num){
		var patrn = /^\d+(\.\d+)?$/;
		var result = true;
		if (!patrn.exec(num)) {
			alert("亲，系统检测到你在非法操作！");
			result = false;
		}
		return result;
	}
	/*购物车结算
	*/
	function submitCartGoods(){
		var goodsids = "";
		$("input:checkbox:checked").each(function(){
			goodsids += $(this).val()+",";
		})
		if(goodsids!=""){//Ajax提交选中的商品到
			$("#cartForm").submit();
		}else{//未选择任何checkbox，dialog弹出提示选择
			var d = dialog({content:"亲，您还没选择任何一种服务呢！"}).show();
			window.setInterval(function(){d.close().remove()},1000);
		}
	}
	/*激活结算按钮*/
	function bindActiveSubmit(){
		$(".active-btn").click(function(){
			//绑定表单提交事件
			submitCartGoods();
		})
	}
