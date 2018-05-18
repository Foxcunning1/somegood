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
				var d = dialog({content:data.msg}).show();
				setTimeout(function () {
					d.close().remove();
					if (argNum == 5) {
						callback();
					}else if(data.url){
						location.href = data.url;
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
				}else if(data.url){
					location.href = data.url;
				}else if($(urlObj)){
					location.href = $(urlObj).val();
				}else{
					location.reload();
				}
			}
        } else {
			dialog({title:'提示', content:data.msg, okValue:'确定', ok:function (){}}).showModal();
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

//子页面调取
function ajaxServerInfo(id,type,serverInfoUrl) {
	$('#'+type+"Id").text(id);
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
function detail(data,url) {
	$(data).addClass('sel').siblings('li').removeClass('sel');
	var id = $(data).find('a input').val();
	var tType = $("#changeType").val();
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
		url:chooseUrl+"?order_sn="+orderSn,
		cancel: function(){
			parent.location.reload();  
		},
		cancelDisplay: false
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