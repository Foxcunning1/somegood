//========================基于artdialog插件========================
//可以自动关闭的提示，基于artdialog插件
function jsprint(msgtitle, url, callback) {
    var d = dialog({ content: msgtitle }).show();
    setTimeout(function () {
        d.close().remove();
    }, 2000);
    if (url == "back") {
        frames["mainframe"].history.back(-1);
    } else if (url != "") {
        frames["mainframe"].location.href = url;
    }
    //执行回调函数
    if (arguments.length == 3) {
        callback();
    }
}


// JavaScript Document
//全选取消按钮函数
function checkAll(chkobj){
	if($(chkobj).text()=="全选"){
	    $(chkobj).text("取消");
		$(".checkall").prop("checked", true);
	}else{
    	$(chkobj).text("全选");
		$(".checkall").prop("checked", false);
	}
}

//执行删除操作
function ExecDelete(sendUrl, checkValue, urlObj, delTips, isCheck){
	//检查传输的值
    var tips = arguments[3] ? arguments[3] : '删除记录后不可恢复，您确定吗？';
	//检查传输的值
	var isVerify = arguments[4] ? arguments[4] : false;
	if (!checkValue) {
		dialog({title:'提示', content:'对不起，请选中您要操作的记录！', okValue:'确定', ok:function (){}}).showModal();
        return false;
	}
	//提现操作，检测当前存款是否满30天的倍数
	if(isVerify){
		var d = 0;
		$.ajax({
			type: "POST",
			url: sendUrl,
			dataType: "json",
			async: false,
			data: {
				"checkId": checkValue,
				"type": 1
			},
			timeout: 20000,
			success: function(data) {
				if (data.status == 1){
					d = data.data;
				}
			}
		})
		if(d>0&&d%30>0){
			tips = "<p style=\"line-height:24px;\">您当前的这笔抗风险基金还差 <b style=\"color:#f00;\">"+(30-d%30)+'</b> 天就满 <b style=\"color:#f00;\">'+(parseInt(d/30)+1)+"</b> 个有息周期，<br/>确定要提现吗？若继续，多出的 <b style=\"color:#f00;\">"+(d%30)+"</b> 天将不在产生任何利息！</p>";
		}
	}
	dialog({
        title: '提示',
        content: tips,
        okValue: '确定',
        ok: function () {
            $.ajax({
				type: "POST",
				url: sendUrl,
				dataType: "json",
				data: {
					"checkId": checkValue
				},
				timeout: 20000,
				success: function(data, textStatus) {
					if (data.status == 1){
						var tipdialog = dialog({content:data.msg}).show();
						setTimeout(function () {
							tipdialog.close().remove();
							if($(urlObj)){
								location.href = $(urlObj).val();
							}else{
								location.reload();
							}
						}, 2000);
					} else {
						dialog({title:'提示', content:data.msg, okValue:'确定', ok:function (){}}).showModal();
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					dialog({title:'提示', content:'状态：' + textStatus + '；出错提示：' + errorThrown, okValue:'确定', ok:function (){}}).showModal();
				}
			});
        },
        cancelValue: '取消',
        cancel: function () { }
    }).showModal();
}

//执行设置操作
function ExecSetting(sendUrl, checkValue, urlObj){
	//检查传输的值
	if (!checkValue) {
		dialog({title:'提示', content:'对不起，请选中您要操作的记录！', okValue:'确定', ok:function (){}}).showModal();
        return false;
	}
	dialog({
        title: '提示',
        content: '您确定要执行当前操作吗？',
        okValue: '确定',
        ok: function () {
            $.ajax({
				type: "POST",
				url: sendUrl,
				dataType: "json",
				data: {
					"checkId": checkValue
				},
				timeout: 20000,
				success: function(data, textStatus) {
					if (data.status == 1){
						var tipdialog = dialog({content:data.msg}).show();
						setTimeout(function () {
							tipdialog.close().remove();
							if($(urlObj)){
								location.href = $(urlObj).val();
							}else{
								location.reload();
							}
						}, 2000);
					} else {
						dialog({title:'提示', content:data.msg, okValue:'确定', ok:function (){}}).showModal();
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					dialog({title:'提示', content:'状态：' + textStatus + '；出错提示：' + errorThrown, okValue:'确定', ok:function (){}}).showModal();
				}
			});
        },
        cancelValue: '取消',
        cancel: function () { }
    }).showModal();
}

/*表单AJAX提交封装(包含验证)
------------------------------------------------*/
function AjaxInitForm(formObj, btnObj, isDialog, urlObj, callback){
	var argNum = arguments.length; //参数个数
	$(formObj).Validform({
		tiptype:3,
		callback:function(form){
			//AJAX提交表单
            $(form).ajaxSubmit({
                beforeSubmit: formRequest,
                success: formResponse,
                error: formError,
                url: $(formObj).attr("url"),
                type: "post",
                dataType: "json",
                timeout: 60000
            });
            return false;
		}
	});
    
    //表单提交前
    function formRequest(formData, jqForm, options) {
        $(btnObj).prop("disabled", true);
        $(btnObj).val("提交中...");
    }

    //表单提交后
    function formResponse(data, textStatus) {
		if (data.status == 'y') {
			//是否提示，默认不提示
			if(isDialog == 1){
				var d = dialog({content:data.info}).show();
				setTimeout(function () {
					d.close().remove();
					if (argNum == 5) {
						callback();
					}else if(data.data){
						location.href = data.data;
					}else if($(urlObj).length > 0 && $(urlObj).val() != ""){
						location.href = $(urlObj).val();
					}else{
						location.reload();
					}
					$(btnObj).removeAttr("disabled");
				}, 2000);
			}else{
				if (argNum == 5) {
					callback();
				}else if(data.data){
					location.href = data.data;
				}else if($(urlObj)){
					location.href = $(urlObj).val();
				}else{
					location.reload();
				}
			}
        } else {
			dialog({title:'提示', content:data.info, okValue:'确定', ok:function (){}}).showModal();
            //$(btnObj).prop("disabled", false);
            $(btnObj).removeAttr("disabled");
            $(btnObj).val("再次提交");
        }
    }
    //表单提交出错
    function formError(XMLHttpRequest, textStatus, errorThrown) {
		dialog({title:'提示', content:'状态：'+textStatus+'；出错提示：'+errorThrown, okValue:'确定', ok:function (){}}).showModal();
        //$(btnObj).prop("disabled", false);
        $(btnObj).removeAttr("disabled");
        $(btnObj).val("再次提交");
    }
}

//法律用户会添加 num，普通用户默认为5
function addSelectItem(update,fromfiled,tofiled, num) {
	if(update=='delete') {
		var fieldvalue = $('#'+tofiled+' option:selected').val();
		$("#"+tofiled+" option").each(function() {
		   if($(this).val() == fieldvalue){
			   $(this).remove();
		   }
		});
		getCompanyCategory(tofiled,"#catids");
	}else {
		var fieldvalue = $('#'+fromfiled+' option:selected').val();
		if(fieldvalue==null){
			alert("请选择行业");
			return false;
		}
		var have_exists = 0;
		var len = $("#"+tofiled+" option").size();
		if(len>=num) {
			alert('最多添加 '+num+' 项');
			return false;
		}
		$("#"+tofiled+" option").each(function() {
		   if($(this).val() == fieldvalue){
			have_exists = 1;
			alert('已经添加到列表中');
			return false;
		   }
		});
		if(have_exists==0) {
			obj = $('#'+fromfiled+' option:selected');
			text = obj.text();
			text = text.replace('│', '');
			text = text.replace('├ ', '');
			text = text.replace('└ ', '');
			text = $.trim(text);
			fieldvalue = "<option selected='selected' value='"+fieldvalue+"'>"+text+"</option>"
			$('#'+tofiled).append(fieldvalue);
			$('#deletebutton').removeAttr('disabled','false');
		}
		getCompanyCategory(tofiled,"#catids");
	}
}
//获取公司分类
function getCompanyCategory(objSelect,obj){
	var catValue ="";
	$("#"+objSelect+" option").each(function() {
		catValue +=","+ $(this).val();
	})
	catValue = catValue.substring(1,catValue.length);
	$(obj).val(catValue);
}

/**双击文字进行 Ajax提交
 * string         obj                 要提交的对象
 * string         field_name          字段名称
 * string         objId               动态创建input的id
 * string         ajax_url            Ajax提交的地址
 * int            is_exist            字段值是否做重复验证 1为是，0为否
 */
function editField(obj,field_name,objId,ajax_url){
   var value=$(obj).text();
   if(value){
       $(obj).html("<input class='focusInput cinput small' type='text' id="+objId+" name="+field_name+" value="+value+">"); 
       $(obj).children("input").focus().blur(function(){
    	  if($(obj).children("input").val()!=""){
		      $.ajax({  
		            type: "POST",  
		            url: ajax_url,
		            dataType: 'json',
		            data: "id="+objId+"&value="+ $(obj).children("input").val(),  
		            success: function(data){ 
		            	//判断返回状态
		            	switch (data.status){
		            		case 0:
		            			$(obj).html(data.data);
		            			parent.jsprint(data.info,"");
		            			break;
		            		case 1:
		            			$(obj).html(data.data); 
		            			break;
		            		case -1:
		            			$(obj).html(data.data); 
		            			break;
		            	}
		            }  
		      });
    	  }else{
    		  $(obj).html(old_value);
    	  }
       });
   }
}

//弹出选择分类窗口
function openCatDialog(ajax_url,mid){
	var d=dialog({
	title:"选择分类",
	id:'catSelect',
	width: 600,
	url: ajax_url+"?mid="+mid,
	okValue:'确定', 
	ok:function (){
	
	}}).showModal();
}
//obj dialog创建的对象
function closeDialog(){
 	dialog.get('catSelect').close().remove();
}

/*设置信息
 * checkValue  选择的值
 * ajax_url    提交的页面
 * #turl       跳转页面的id
 * */
function ajaxSetting(checkValue,ajax_url) {
	if (arguments.length == 2) {
		ExecSetting(ajax_url, checkValue, '#turl');
	}
}

//设置页面分类值
//catid  分类id,catname分类名称
function setCategoryValue(catid,catname){
	$("#categoryName").html(catname);
	$("#txtCatid").val(catid);
}

//获取当前用户登录状态
function getUserIsLogin(url){
	var d;
	$.ajax({
		type: 'POST',
		url: url+"?r="+Math.random(),
		dataType: 'json',
		async: false,
		success:function (data) {
			d = data;//返回当前状态
		}
	});
	return d;
}

//通用弹出提示
function  msgTipsDialog(data,url){
	parent.dialog({title:'提示', content:data.info, okValue:'确定', ok:function (){
		if(url != undefined){
			location.href = url;
		}
	}}).showModal();
}

function ajaxServers(type,serversUrl,serverInfoUrl,areaId) {
	var areaId = arguments[3] ? arguments[3] : '';
	$.ajax({
		type: 'POST',
		url: serversUrl,
		dataType: 'html',
		data: {'area_id':areaId},
		beforeSubmit: loadingBefore("#"+type+"Page"),
		success:function (item) {
			$("#"+type+"Page").html(item);
			//重新绑定Js
			var id = $("."+type+"Box").find('ul.severList li').eq(0).addClass('sel').find('input').val();
			ajaxServerInfo(id,type,serverInfoUrl);
		},
		complete: loadingComplete("#"+type+"Page")
	});
}

//子页面服务人员列表
function ajaxServerInfo(id,type,serverInfoUrl) {
	//默认设置serviceId
	$("#serviceId").val(id);
	var tabType = type+"_info";
	$.ajax({
		type: 'POST',
		url: serverInfoUrl,
		data: {'id':id},
		dataType: 'html',
		beforeSubmit: loadingBefore("."+tabType),
		success:function (item) {
			$("."+tabType).html(item);
		},
		complete: loadingComplete("."+tabType)
		
	});
}
/*获取服务人员对应的评论列表
* Param        int             id                    服务人员对应的ID
* Param        obj             obj                   数据加载对应的box
* Param        url             commentAjaxUrl        获取评论列表的ajax地址
*
* */
function ajaxServerCommentList(id, obj, commentAjaxUrl){
	$.ajax({
		type: 'POST',
		url: commentAjaxUrl,
		data: {'id':id},
		dataType: 'html',
		beforeSubmit: loadingBefore(obj),
		success:function (item) {
			$(obj).html(item);
		},
		complete: loadingComplete(obj)
	});
	//解绑并重新绑定点击加载更多

}
/*解绑并重新绑定点击加载更多
*
* */
/*点击加载更多数据
* Param          int        p                 页码
* Param          int        totalNum          页面总数
* Param          string     showType          页面显示类型
* Param          url        getDataUrl        获得数据列表ajax地址
* Param          obj        obj               当前操作的对象
* Param          obj        targetObj         获取数据被填充的对象
* */
function ajaxLoadingDataList(p, totalNum, showType, getDataUrl, obj, targetObj){
	$.ajax({
		url: getDataUrl,
		//ajax地址
		type:"GET",
		//post的数据
		dataType:'json',
		success:function(data){
			if (data&&data.status==1) {
				var str = "";
				$.each(data.data,function(index, array) {
					switch(showType){
						case 'goods':
							str += "<div class=\"comment-item\">";
							str += "<div class=\"user-avatar\">";
							str += "<p><img src=\""+base_path+array['avatar']+"\"></p>";
							str += "<p>"+array['user_name']+"</p>";
							str += "</div>";
							str += "<div class=\"user-comment-box\">";
							str += "<div class=\"user-comment-title\">";
							str += "<div class=\"comment-time\">"+ date('Y/m/d H:i:s', ""+array['add_time']+"")+"</div>";
							str += "</div>";
							str += "<div class=\"user-comment-content clear\">"+array['goods_content']+"</div>";
							str += "<div class=\"user-comment-icon\"></div>";
							str += "</div>";
							str += "</div>";
							break;
						case 'server':
							break;
					}

				});
				$(targetObj).append(str);
			}else {
				//数据为空的时候
				obj.parent().html("<span>亲，别点我了，已经木有了！</span>");
			}
		},beforeSend:function(xhr){
			$(targetObj).append("<div class=\"loading-box\">正在加载...</div>");
			$(".loading-box").show();
		},complete:function(xhr,status){
			$(".loading-box").remove();
		}
	})
	if(p==totalNum){
		//数据为空的时候
		obj.parent().html("<span>亲，别点我了，已经木有了！</span>");
	}
}
/*Ajax提交加载信息之前loading效果
 * Param        obj       obj         被选择显示loading的对象
 * */
function loadingBefore(obj){
	$(obj).addClass("loading");
}
/*Ajax加在完成后移除loading效果
 * Param        obj       obj         被选择显示loading的对象
 * */
function loadingComplete(obj){
	$(obj).removeClass("loading");
}
/*获取服务人员的详情
 * Param       obj         obj               当前选择的box
 * Param       url         url               获取ajax的地址
 * Param       tType       tType             服务人员类型
 * */
function detail(obj,url,tType) {
	$(obj).addClass('sel').siblings('li').removeClass('sel');
	var id = $(obj).find('a input').val();
	ajaxServerInfo(id,tType,url);
}
//删除左右两端的空格
function trim(str){ 
   	return str.replace(/(^\s*)|(\s*$)/g, "");
}


/**
 * 订单提交函数
 * @param obj 对象数组
 * @param orderUrl 订单确认地址
 * @param companyUrl 完善公司信息地址
 * @param loginUrl 登录地址
 * @param registerUrl 注册地址
 * @param companyApplyUrl 公司注册申请地址
 */
function orderSubmit(obj,orderUrl,companyUrl,loginUrl,registerUrl,companyApplyUrl) {
	$.ajax({
		type: 'POST',
		data:{'content_id':obj['content_id']},
		dataType:"json",
		url: orderUrl+"?r="+Math.random(),
		success:function (item) {
			switch (item.status) {
				case 0:
					msgTipsDialog(item);
					break;
				case 1:
					var temp = '';
					$.each(obj,function (key,val) {
						temp += key + "=" + val + "&";
					});
					temp = temp.substr(0,temp.length-1)
					top.dialog({
						title: "确认信息",
						url: orderUrl+"?"+temp
					}).showModal();
					break;
				case 2:
					msgTipsDialog(item,companyUrl);
					break;
				case 3:
					top.dialog({
						title: '提示',
						content: item.info,
						okValue: '取消',
						width: 300,
						button: [
							{
								value: '登录',
								callback: function () {
									location.href = loginUrl;
									return false;
								},
								autofocus: true
							},
							{
								value: '注册',
								callback: function () {
									location.href = registerUrl;
									return false;
								}
							},
							{
								value: '取消',
								callback: function () {}
							}
						]
					}).showModal();
					break;
				case 4:
					top.dialog({
						width: 600,
						height: 250,
						lock: true,
						title: '联系信息',
						url: companyApplyUrl+"?content_id="+obj['content_id'] + '&type=commerce' + '&server_id=' + obj['server_id']
					}).showModal();
					break;
				default:
					break;
			}
		}
	});
}

/**
 * 支付操作
 * @param orderSn 订单号
 * @param uploadUrl 上传操作地址
 */
function choose(orderSn,chooseUrl) {
	top.dialog({
		title: '支付方式选择',
		width: 800,
		url:chooseUrl+"?order_sn="+orderSn
	}).showModal();
}
/*
*城市三级联动
*/
function loadRegion(sel,type_id,selName,url,objInput,isReset){
	var obj = arguments[4] ? arguments[4] : '';
	var reset = arguments[5] ? arguments[5] : false;
	if(selName!=""){
		jQuery("#"+selName+" option").each(function(){
			jQuery(this).remove();
		});
		jQuery("<option value=0>请选择</option>").appendTo(jQuery("#"+selName));
	}
	if(jQuery("#"+sel).val()==0){
		return;
	}
	if(obj!=""){
		var address = $(obj).val();
		if(reset){
			$(obj).val("");
			address = "";
		}
		var temp = $("#"+sel).find("option:selected").text();
		if(type_id==2){
			switch (temp){
				case "北京":
					temp = "";
					break;
				case "上海":
					temp = "";
					break;
				case "天津":
					temp = "";
					break;
				case "重庆":
					temp = "";
					break;
				case "香港":
					temp = "香港特别行政区";
					break;
				case "澳门":
					temp = "澳门特别行政区";
					break;
				case "台湾":
					temp = "台湾省";
					break;
				default:
					temp += "省";
					break;
			}
		}
		if(type_id==3){
			switch (temp){
				case "香港":
					temp = "";
					break;
				case "澳门":
					temp = "";
					break;
				case "台湾":
					temp = "";
					break;
				default:
					temp += "市";
					break;
			}
		}
		address += temp;
		$(obj).val(address);
	}
	jQuery.getJSON(url,{pid:jQuery("#"+sel).val(),type:type_id},
		function(data){
			if(selName!=""){
				if(data){
					jQuery.each(data,function(idx,item){
						jQuery("<option value="+item.id+">"+item.name+"</option>").appendTo(jQuery("#"+selName));
					});
				}else{
					jQuery("<option value='0'>请选择</option>").appendTo(jQuery("#"+selName));
				}
			}
		}
	);
}
//发送短信
function sendSmsCode(ajaxUrl,type,btnObj){
	if(ajaxUrl!=""){
		$.ajax({
			type: 'POST',
			url: ajaxUrl ,
			data: 'type='+type,
			dataType: 'json',
			success: function(data) {
			  if(data.status ==1 ){
				settime(btnObj);
			  }
			}
		})
	}
}
var countdown=60;
function settime(obj) {
	if (countdown == 0) {
		$(obj).removeClass("disabled");
		$(obj).html("重新发送");
		countdown = 60;
		return;
	} else {
		$(obj).addClass("disabled");
		$(obj).html("重新发送(" + countdown + ")");
		countdown--;
	}
	setTimeout(function() {
			settime(obj) }
		,1000)
}

/*获取购物车的商品数量
 * Param       url          ajaxUrl            获取购物车商品数量的ajax地址
 * Param       obj          objBox             商品数量对应的box
 */
function getCartCount(ajaxUrl,objBox){
	$.ajax({
		type: 'POST',
		url: ajaxUrl ,
		dataType: 'json',
		success: function(data) {
		  if(data.status ==1 ){
			  $(objBox).text(data.data);
		  }
		}
	})
}
/*关注服务(加入收藏夹)
 * Param         int            goods_id           商品ID
 * Param         string         ajaxUrl           加入收藏夹后台地址
 */
function addFavorites(goods_id,ajaxUrl){
	if(goods_id > 0){
		$.ajax({
			type: 'POST',
			url: ajaxUrl ,
			data:{goodsid:goods_id},
			dataType: 'json',
			success: function(data) {
			  if(data.status ==1 ){
				  var d = dialog({content:"关注成功！"}).show();
                  window.setInterval(function(){
                      d.close().remove()},1000);
			  }
			  if(data.status==-1){
				  //用户未登录，提示用户去登录
				  top.dialog({
						title:'提示',
						content: "亲，请先登录！",
						width: 300,
						button:[
							{
								value : '登录',
								callback:function () {
									location.href = "/login/login.html";
									return false;
								},
								autofocus: true
							},
							{
								value:'取消',
								callback:function () {

								}
							}
						]
					}).showModal();
			  }
			}
		})
	}
}
/*-------------------添加商品到购物车 JS-----------------------*/
/*添加购物车
* Param           string               ajax_url               Ajax执行的URL地址
* Param           string               goods_ids              商品ID组合
* Param           string               pay_types              支付组合
* Param           string               service_ids            服务人员组合
* Param           bool                 is_dialog              是否显示弹出窗口
* Param           bool                 is_jump                是否跳转，如果是true,需设置jump_to_url
* Param           string               jump_top_url           跳转链接地址
* Param           string               account_url            结算地址
*/
function addCart(ajax_url, goods_ids, pay_types, service_ids, is_dialog, is_jump, jump_to_url, account_url){
	$.ajax({
		url:ajax_url,//ajax地址
		type:"POST",
		data:{goodsids:goods_ids,paytypes:pay_types, services_ids:service_ids},//post的数据
		dataType:'json',
		success:function(data,textStatus){
			//提交成功
			if(data.status==1){
				//直接跳转
				if(is_jump){
					window.location.href = jump_to_url;
				}
			}
		}
	})
}
/*-------------------添加商品到购物车 JS End-----------------------*/

/*选择组合数量与价格计算*/
function calcJoinGoods(goodsPrice){
	var seletNum = 0;
	var seletPrice = 0;
	var pay_types = $("#payMethod").val();
	if(pay_types=="1"){
		goodsPrice = parseFloat(goodsPrice/12);
	}
	$("input[name='chkGoods'][checked]").each(function(){
		var curObj = $(this);
		var curPrice = parseFloat(curObj.parent().siblings("div.l-link").find(".item-price").text());//目前选中商品的价格
		//获取当前商品是否分期
		var isInstallment = curObj.siblings("input.payType").val();
		if(isInstallment=="1"){
			seletPrice += parseFloat(curPrice/12);
		}else{
			seletPrice += curPrice;
		}
		seletNum++;
	})
	$("#goodsCount").text(seletNum);
	$("#goodsTotalPrice").text("¥"+parseFloat((seletPrice+goodsPrice)).toFixed(2));
}
/*选择支付方式
 * Param           string       obj            执行的对象
 * Param           float        priceObj       价格显示的box
 * Param           float        unit_price     如果选择月付，则以月付的价格显示
 * */
 function choosePayWay(obj,priceObj,unit_price){
 	$(obj).addClass("select").siblings().removeClass("select");
 	var pay_way = $(obj).attr("data");
 	if(pay_way=="1"){
 		var price = parseFloat(unit_price/12).toFixed(2);
 		$(priceObj).text(price);
 	}else{
 		$(priceObj).text(unit_price);
 	}
 	$(obj).parent().parent("dl").siblings("input.payMethod").val(pay_way);
 	calcJoinGoods(goodsPrice);//推荐组合计算
 }
 /*搜索检测*/
 function checkSearchForm(){
	var keyword=$("#keyword").val();
	if(keyword!=""){
		return true;
	}else{
		alert('请输入搜索关键词');
		return false;
	}
}
 
 /** 
  * 时间戳格式化函数 
  * @param {string} format 格式 
  * @param {int} timestamp 要格式化的时间 默认为当前时间 
  * @return {string}   格式化的时间字符串 
  */
 function date(format, timestamp){ 
 	var a, jsdate=((timestamp) ? new Date(timestamp*1000) : new Date()); 
 	var pad = function(n, c){ 
 		if((n = n + "").length < c){ 
 			return new Array(++c - n.length).join("0") + n; 
 		}else{ 
 			return n; 
 		} 
 	}; 
 	var txt_weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]; 
 	var txt_ordin = {1:"st", 2:"nd", 3:"rd", 21:"st", 22:"nd", 23:"rd", 31:"st"}; 
 	var txt_months = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]; 
 	var f = { 
 	// Day 
 		d: function(){return pad(f.j(), 2)}, 
 		D: function(){return f.l().substr(0,3)}, 
 		j: function(){return jsdate.getDate()}, 
 		l: function(){return txt_weekdays[f.w()]}, 
 		N: function(){return f.w() + 1}, 
 		S: function(){return txt_ordin[f.j()] ? txt_ordin[f.j()] : 'th'}, 
 		w: function(){return jsdate.getDay()}, 
 		z: function(){return (jsdate - new Date(jsdate.getFullYear() + "/1/1")) / 864e5 >> 0
 	}, 
 	// Week 
 	W: function(){ 
 	    var a = f.z(), b = 364 + f.L() - a; 
 	    var nd2, nd = (new Date(jsdate.getFullYear() + "/1/1").getDay() || 7) - 1; 
 	    if(b <= 2 && ((jsdate.getDay() || 7) - 1) <= 2 - b){ 
 	    	return 1; 
 	    }else{ 
 		    if(a <= 2 && nd >= 4 && a >= (6 - nd)){ 
 			    nd2 = new Date(jsdate.getFullYear() - 1 + "/12/31"); 
 			    return date("W", Math.round(nd2.getTime()/1000)); 
 		    }else{ 
 		    	return (1 + (nd <= 3 ? ((a + nd) / 7) : (a - (7 - nd)) / 7) >> 0); 
 		    } 
 	    } 
 	}, 
 	// Month 
 	F: function(){return txt_months[f.n()]}, 
 	m: function(){return pad(f.n(), 2)}, 
 	M: function(){return f.F().substr(0,3)}, 
 	n: function(){return jsdate.getMonth() + 1}, 
 	t: function(){ 
 		var n; 
 		if( (n = jsdate.getMonth() + 1) == 2 ){ 
 			return 28 + f.L(); 
 		}else{ 
 			if( n & 1 && n < 8 || !(n & 1) && n > 7 ){ 
 				return 31; 
 			}else{ 
 				return 30; 
 			} 
 		} 
 	}, 
 	// Year 
 	L: function(){var y = f.Y();return (!(y & 3) && (y % 1e2 || !(y % 4e2))) ? 1 : 0}, 
 	//o not supported yet 
 	Y: function(){return jsdate.getFullYear()}, 
 	y: function(){return (jsdate.getFullYear() + "").slice(2)}, 
 	// Time 
 	a: function(){return jsdate.getHours() > 11 ? "pm" : "am"}, 
 	A: function(){return f.a().toUpperCase()}, 
 	B: function(){ 
 		// peter paul koch: 
 		var off = (jsdate.getTimezoneOffset() + 60)*60; 
 		var theSeconds = (jsdate.getHours() * 3600) + (jsdate.getMinutes() * 60) + jsdate.getSeconds() + off; 
 		var beat = Math.floor(theSeconds/86.4); 
 		if (beat > 1000) beat -= 1000; 
 		if (beat < 0) beat += 1000; 
 		if ((String(beat)).length == 1) beat = "00"+beat; 
 		if ((String(beat)).length == 2) beat = "0"+beat; 
 		return beat; 
 	}, 
 	g: function(){return jsdate.getHours() % 12 || 12}, 
 	G: function(){return jsdate.getHours()}, 
 	h: function(){return pad(f.g(), 2)}, 
 	H: function(){return pad(jsdate.getHours(), 2)}, 
 	i: function(){return pad(jsdate.getMinutes(), 2)}, 
 	s: function(){return pad(jsdate.getSeconds(), 2)}, 
 	//u not supported yet 
 	// Timezone 
 	//e not supported yet 
 	//I not supported yet 
 	O: function(){ 
 		var t = pad(Math.abs(jsdate.getTimezoneOffset()/60*100), 4); 
 		if (jsdate.getTimezoneOffset() > 0){ 
 			t = "-" + t;
 		}else{
 			t = "+" + t
 		}; 
 		return t; 
 	}, 
 	P: function(){var O = f.O();return (O.substr(0, 3) + ":" + O.substr(3, 2))}, 
 	//T not supported yet 
 	//Z not supported yet 
 	   
 	// Full Date/Time 
 	c: function(){return f.Y() + "-" + f.m() + "-" + f.d() + "T" + f.h() + ":" + f.i() + ":" + f.s() + f.P()}, 
 	//r not supported yet 
 	U: function(){return Math.round(jsdate.getTime()/1000)}
 	}; 
 	return format.replace(/[\/]?([a-zA-Z])/g, function(t, s){ 
 		if( t!=s ){ 
 			// escaped 
 			ret = s; 
 		}else if( f[s] ){ 
 			// a date function exists 
 			ret = f[s](); 
 		}else{ 
 			// nothing special 
 			ret = s; 
 		} 
 		return ret; 
 	})
 }