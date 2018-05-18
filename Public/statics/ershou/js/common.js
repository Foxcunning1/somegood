//判断是否为微信浏览器
function isWeixin(){
	var ua = window.navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i) == 'micromessenger'){
		return true;
	}else{
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
function ajaxServers(type,serversUrl,areaId) {
    var areaId = arguments[2] ? arguments[2] : '';
    $.ajax({
        type: 'POST',
        url: serversUrl,
        dataType: 'html',
        data: {'area_id':areaId},
        success:function (item) {
            $("#"+type+"Page").html(item);
            //重新绑定Js
            var id = $("."+type+"Box").find('div.lawyerItems').eq(0).addClass('select').find('#serId').val();
            $('#'+type+"Id").text(id);
        }
    });
}

//子页面调取
function ajaxServerInfo(id,type,serverInfoUrl) {
    $('#'+type+"Id").text(id);
    $.ajax({
        type: 'POST',
        url: serverInfoUrl,
        data: {'id':id},
        dataType: 'html',
        success:function (item) {
            $(".lawyerInfoBox").html(item).animate({"left":"0"},200);
            $("body").css("overflow","hidden");
        }
    });
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
                    $("body").minDialog({
                        isMask : false,
                        isAutoClose: true,
                        content:item.info//弹出窗口内容
                    });
                    break;
                case 1:
                    var temp = '';
                    $.each(obj,function (key,val) {
                        temp += key + "=" + val + "&";
                    });
                    temp = temp.substr(0,temp.length-1);
                    location.href = orderUrl+"?"+temp;
                    break;
                case 2:
                    var temp = companyUrl;
                    break;
                case 3:
                    $("body").minDialog({
                        title: '提示',
                        content: "<p>"+item.info+"</P>"+"<div><button onclick=\"parent.location.href='"+loginUrl+"'\">登录</button>"+
                        "<button onclick=\"parent.location.href='"+registerUrl+"'\">注册</button></div>",
                    });
                    break;
                case 4:
                    location.href = companyApplyUrl+"?content_id="+obj['content_id'] +'&server_id=' + obj['server_id']
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


/**
 * Ajax滚动分页加载
 * @param  showType   页面显示类型
 * @param  ajaxUrl    ajaxUrl地址
 * @param  totalpage  全局变量，调用该函数前需定义
 * @param  p    全局变量，调用该函数前需定义
 */
function scrollLoadDataList(showType,ajaxUrl){
	var winH = $(window).height()+150; //页面可视区域高度
    $(window).scroll(function() {
        if (p <= totalpage) { // 当滚动的页数小于总页数的时候，继续加载
            var pageH = $(document.body).height();
            var scrollT = $(window).scrollTop(); //滚动条top
            var rate = (pageH - winH - scrollT) / winH;
            if (rate < 0.01) {
            	getAjaxPageData(p,showType,ajaxUrl);
            }
        }else{ //否则显示无数据
            showEmpty();
        }
    });
}
/**
 * 获取分页数据
 * @param   page   当前页码
 * @param   showType 页面显示类型配合scrollLoadDataList方法使用
 * @param  ajaxUrl    ajaxUrl地址
 * */
function getAjaxPageData(page,showType,ajaxUrl) {
    $.ajax({
    	type:'GET',
    	url:ajaxUrl,
    	dataType:'json',
    	data:"p="+page,
    	success:function(data){
			if (data&&data.status==1) {
                var str = "";
        		$.each(data.data,function(index, array) {
        			switch(showType){
        				case "coupon":
		                    str += "<li class=\'clearfix\'>";
		                    str += "<span class=\"id\">"+array['bid']+"</span>";
		                    str += "<span class=\"num\">"+array['user_money']+"</span>";
		                    str += "<span class=\"from\">"+array['user_name']+"</span>";
		                    str += "<span class=\"time\">"+array['time']+"</span>";
		                    str += " </li>";
		                    break;
		                case "commission":
		                	  str += "<li class=\'clearfix\'>";
		                    str += "<span class=\"id\">"+array['bid']+"</span>";
		                    str += "<span class=\"num\">"+array['user_money']+"</span>";
		                    str += "<span class=\"rate\">"+array['user_level']+"</span>";
		                    str += "<span class=\"applyTime\">"+array['add_time']+"</span>";
		                    str += "<span class=\"doneTime\">"+array['used_time']+"</span>";
		                    str += "<span class=\"status\">"+array['status']+"</span>";
		                    str += " </li>";
		                   	break;
		                case "goods":
		                	str += "<li><div class=\"proItemBox\">";
		                	str += "<a href=\""+details_url+"?id="+array['pid']+"\">";
		                	str += "<img src=\""+base_path+array['thumb'].replace("/","/thumb_")+"\" alt=\""+array['title']+"\" width=\"100%\" />";
		                	str += "<span class=\"colleges\">"+array['price']+"</span>";
		                	var title = array['title'];
		                	if(title.length>12){
		                		title = title.substr(0,12)+"...";
		                	}
		                	str += "<span class=\"name\">"+title+"</span>";
		                	str += "</a></div></li>";
		                	break;
		                case "buy":
		                	str += "<li>";
		                	str += "<a href=\""+details_url+"?id="+array['bid']+"\">";
		                	var title = array['title'];
		                	if(title.length>13){
		                		title = title.substr(0,13)+"...";
		                	}
		                	str += title;
		                	str += "<span class=\"date fr\">"+ date('Y-m-d', ""+array['inputtime']+"")+"</span>";
		                	str += "</a></li>";
		                	break;
		                case "company":
		                	str += "<li>";
		                	str += "<a href=\""+details_url+"?id="+array['user_id']+"\">";
		                	var title = array['company_name'];
		                	if(title.length>12){
		                		title = title.substr(0,12)+"...";
		                	}
		                	str += title;
		                	str += "<span class=\"date fr\">"+ date('Y-m-d', ""+array['add_time']+"")+"</span>";
		                	str += "</a></li>";
		                	break;
						case "patent":
							str += "<li>";
							str += "<a href=\""+details_url+"?id="+array['id']+"\">";
							var title = array['title'];
							if(title.length>18){
								title = title.substr(0,18)+"...";
							}
							str += title;
							str += "<span class=\"date fr\">"+ date('Y-m-d', ""+array['inputtime']+"")+"</span>";
							str += "</a></li>";
							break;
						case "patent1":
							str += "<li>";
							str += "<a href=\""+details_url+"?id="+array['pid']+"\">";
							var title = array['title'];
							if(title.length>18){
								title = title.substr(0,18)+"...";
							}
							str += title;
							str += "<span class=\"date fr\">"+ date('Y-m-d', ""+array['inputtime']+"")+"</span>";
							str += "</a></li>";
							break;
						case "patents":
							str += "<li>";
							str += "<div class=\'li-box\'>";
							str += "<a href=\""+details_url+"?id="+array['id']+"\">";
							str += "<img src=\""+base_path+array['thumb']+"\"  width=\"100%\" />";
							var title = array['title'];
							if(title.length>18){
								title = title.substr(0,18)+"...";
							}
							str += title;
							str += "<span >"+ title +"</span>";
							str += "</a></div></li>";
							break;
						case "patents1":
							str += "<li>";
							str += "<div class=\'li-box\'>";
							str += "<a href=\""+details_url+"?id="+array['pid']+"\">";
							str += "<img src=\""+base_path+array['thumb']+"\"  width=\"100%\" />";
							var title = array['title'];
							if(title.length>18){
								title = title.substr(0,18)+"...";
							}
							str += title;
							str += "<span >"+ title +"</span>";
							str += "</a></div></li>";
							break;
						case "cgoods"://企业站中站产品列表
			                str += "<li><div class=\"pro-con-box\">";
							str += "<a href=\""+details_url+"?id="+array['pid']+"\">";
							str += "<img src=\""+base_path+array['thumb'].replace("/","/thumb_")+"\" alt=\""+array['title']+"\" width=\"100%\" />";
							var title = array['title'];
							if(title.length>14){
								title = title.substr(0,14)+"...";
							}
							str += "<span class=\"pos-span\">" + title + "</span>";
							str += "</a></div></li>";
							break;
                        case 'headlines':
                            str += "<li>";
                            str += "	<a href=\""+details_url+"?id="+array['id']+"&cat_id="+array['catid']+"\">";
                            str += "		<div class=\"left fr\">";
                            str += " 			<span>"+array['title']+"</span>";
                            str += "			<p>"+array['description']+"</p>";
                            str += "		</div>";
                            str += "		<img src=\""+base_path+array['thumb']+"\" alt=\"\" class=\"fl\">";
                            str += "	</a>";
                            str += "</li>";
                            break;
                    }
                });
                $("#jsonList").append(str);
                if(showType=="goods"){
                	var w = $(".proContent ul li img").width();
                    $(".proContent ul li img").height(w);
                }
                if(showType=="cgoods"){
                	var w = $(".goodsList li img").width();
                    $(".goodsList li img").height(w);
                }
            } else {
                showEmpty(); //数据为空的时候
            }
		},beforeSend:function(xhr){
			$("#jsonList").parent().append("<div id=\"loadingBox\"><div id=\"loadingCell\"><div class=\"animateLoading\"></div></div></div>");
			$("#loadingBox").show();
		}/*,error:function (XMLHttpRequest, textStatus, errorThrown) {
			console.log("sorry error\r\n");
		}*/,complete:function(xhr,status){
			$("#loadingBox").remove();
		}
	});
    if(p<=totalpage){
    	p++;
    }
}
/**
 * Ajax无数据显示
 * */
function showEmpty() {
	$("#loadingBox").remove();
	$("#jsonList").parent().append("<div id=\"loadingBox\"><div id=\"loadingCell\"><div class=\"animateLoading\"></div></div></div>");
    $("#loadingCell").html("亲，别推我了，已经到底了...");
    $("#loadingBox").show();
}
/**
 *通用JS调取手机号码
 *@param   obj                选择对象
 *@param   type               短信发送类型
 *@param   ajax_url           ajaxUrl地址
 *@param   dialog_url         弹出窗口地址
 */
/*点击发送手机验证码*/
function sendSmsCode(bObj,bObjText,obj,type,ajax_url,dialog_url) {
    var type = type;
    var mobile = $("#"+obj).val();
    var w = $(window).width() - 50;
    if (mobile != "") {
		if(isMobile(mobile)){
			$.ajax({
				type: 'POST',
				url: ajax_url,
				data: 'param=' + mobile,
				dataType: 'json',
				success: function (data) {
					if (data.status == 1) {
						$("body").minDialog({
							title: '手机验证码',
							url: dialog_url+"?mobile=" + mobile + "&type=" + type+"&obj="+bObj+"&otext="+bObjText,
							width: "330px"
						});
					}
				}
			});
		}else{
			$("body").minDialog({isMask:true,content:'手机号码格式有误！',isAutoClose:true});
		}
    }else{
		$("body").minDialog({isMask:true,content:'请输入手机号码！',isAutoClose:true});
	}
}
/**
 *通用JS绑定调取手机号码
 *@param   obj                选择对象
 *@param   type               短信发送类型
 *@param   ajax_url           ajaxUrl地址
 *@param   dialog_url         弹出窗口地址
 */
/*点击发送手机验证码*/
function sendBindSmsCode(bObj,bObjText,obj,type,dialog_url) {
	var type = type;
	var mobile = $("#"+obj).val();
	var w = $(window).width() - 50;
	if (mobile != "") {
		if(isMobile(mobile)) {
			$("body").minDialog({
				title: '手机验证码',
				url: dialog_url + "?mobile=" + mobile + "&type=" + type + "&obj=" + bObj + "&otext=" + bObjText,
				width: "330px"
			});
		}else{
			$("body").minDialog({isMask:true,content:'手机号码格式有误！',isAutoClose:true});
		}
	}else{
		$("body").minDialog({isMask:true,content:'请输入手机号码！',isAutoClose:true});
	}
}

/**
 *验证码倒计时
 *@param  countdown    倒计时设置时间
 *@param  obj          点击按钮
 *@param  objValue     按钮显示文本
 * */
var countdown=120;
function setCountDowntime(obj,objValue) {
	var ob = "#"+obj;
    if (countdown == 0) {
    	$(ob).removeClass("disabled");
    	$(ob).removeAttr("disabled");
        if(objValue!=""){
        	$(ob).val("免费获取验证码");
        }else{
        	$(ob).val(objValue);
        }
        countdown = 120;
        return;
    } else {
    	$(ob).addClass("disabled");
    	$(ob).attr("disabled", true);
    	$(ob).val("重新发送(" + countdown + ")");
        countdown--;
    }
    setTimeout(function() {
    	setCountDowntime(obj,objValue)
    },1000)
}
/**
 * 发送验证码
 * @param     obj         对象
 * @param     type        发送类型
 * @param     ajax_url    ajax地址
 * */
//点击发送邮箱验证码
function sendEmailCode(obj,type,ajax_url){
	var email = $("#"+obj).val();
	var type  = type;
	if(email!=""){
		$.ajax({
			type: 'POST',
 			url: ajax_url,
			data: {email:email,type:type},
			dataType: 'json',
			success: function(data) {
				$("body").minDialog({isMask:true,content:data.info,isAutoClose:true});
	        }
		})
	}
}
//显示右侧筛选
function showSelectBox(){
	$(".area-done-box").show();
	$(".kinds-areas-box").animate({"left":"0%","width":"100%"},300);
}
//关闭右侧筛选
function closeSelectBox(){
	$(".kinds-areas-box").animate({"left":"100%","width":"100%"},300);
	$(".area-done-box").hide();
}

//顶部菜单打开
function showMenu(){
    $(".animate-page").animate({"right":"70%","top":"10%"},300);
    $(".topTitleMenu").animate({"right":0},300);
    $(".page-overlay").show();
    $(".topTitleMenu ul li").each(function(){
        $(this).removeClass("menu-in-animate");
        $(this).addClass("menu-out-animate");
    })
}
//顶部菜单关闭
  function hideMenu(){
      $(".page-overlay").hide();
      $(".topTitleMenu ul li").each(function(){
          $(this).removeClass("menu-out-animate");
          $(this).addClass("menu-in-animate");
      })
      $(".topTitleMenu").animate({"right":"40%"},300);
      $(".animate-page").animate({"right":0,"top":0},300);
  }

//初始化区域
/**
* JS动态加载地区信息
* @param   obj          被追加BOX
* @param   value        被选中值
*/
function initRegionDataList(obj,value){
	if(cityJson){
		var str = "";
		$.each(cityJson,function(i, item){
			str += "<div class=\"list-provs\"><div class=\"list-prov\">";
			str += "<span data-value=\""+item['id']+"\" class=\"pro-name\">"+item['name']+"</span><i class=\"pro-icon\"></i></div>";
			var cityStr = "";
			if(item['child']){
				$.each(item['child'],function(si, sItem){
					cityStr += "<div class=\"list-citys\"><div class=\"list-city\">";
					cityStr += "<span data-value=\""+sItem['id']+"\" class=\"city-name\">"+sItem['name']+"</span><i class=\"pro-icon\"></i></div>";
					var areaStr = "";
					if(sItem['child']){
						cityStr += "<div class=\"list-areas\">";
						$.each(sItem['child'],function(ci,cItem){
							areaStr += "<div class=\"list-area\"><span data-value=\""+cItem['id']+"\" class=\"pro-name\">"+cItem['name']+"</span>";
							if(cItem['id'] == value){
								areaStr += "<i class=\"selected\"></i>";
							}else{
								areaStr += "<i></i>";
							}
							areaStr += "</div>";
						})
						cityStr += areaStr+"</div>";
					}
					cityStr += "</div>";
				})
				str += cityStr+"</div>";
			}
		})
		$(obj).append(str);
		$("#choosed").text($("#region-list").find("i.selected").siblings("span").text());
		/*选择区域3级分类*/
		//省直辖市展开
	    $(".list-prov").on("click",function(){
			$(".list-citys").hide();
			$(".list-areas").hide();
	    	if($(this).hasClass("current")){
				$(this).removeClass("current");
	    		$(this).parent().find(".list-citys").hide();
	    	}else{
				$(this).addClass("current");
	      		$(this).parent().find(".list-citys").show();
	    	}
	    });
		//城市展开
	    $(".list-city").on("click",function(){
			$(".list-areas").hide();
			if($(this).hasClass("current")){
				$(this).removeClass("current");
				$(this).parent().find(".list-areas").hide();
			}else{
				$(this).addClass("current");
				$(this).parent().find(".list-areas").show();
			}
	    });
		//区域选择
	    $(".list-area").on("click",function(){
			//设置当前选中，并把值传给指定的box
			$(this).parent().find(".list-area").removeClass("curSelected");
			$(this).addClass("curSelected");
	        var value = $(this).find("span.pro-name").text();
	        $("#choosed").text(value);
	        $("#areaId").val($(this).find("span").attr("data-value"));
			$(this).parent().parent().parent().parent().parent().parent("dl").removeClass("filter-open");
	    })
	    $("#allRegion").on("click",function(){
			$(".list-prov").removeClass("current");
			$(".list-city").removeClass("current");
			$(".list-citys").hide();
			$(".list-areas").hide();
			$(".list-area").removeClass("curSelected");
			$(this).addClass("current");
	        var value = $(this).find("span.pro-name").text();
	        $("#choosed").text(value);
	        $("#areaId").val($(this).find("span").attr("data-value"));
	        $(".list-citys").hide();
	        $(".region-title").find("i").removeClass("touch");
	    })
	}
}

//获取url中的参数
function getUrlParam(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
	var r = window.location.search.substr(1).match(reg);  //匹配目标参数
	if (r != null) return unescape(r[2]); return 0; //返回参数值
}

/*验证是否是手机号码*/
function isMobile(mobile){
	var myreg = /^(((13[0-9]{1})|(15[0-9]{1})||(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
	if(!myreg.test(mobile)){
		return false;
	}
	return true;
}

/**

 * 计算两个坐标之间的距离

 * @param {Object} lat1

 * @param {Object} lng1

 * @param {Object} lat2

 * @param {Object} lng2

 */

var EARTH_RADIUS = 6378.137; //单位KM
var PI = Math.PI;

function getRad(d){
	return d*PI/180.0;
}

function getFlatternDistance(lat1,lng1,lat2,lng2){
	var f = getRad((lat1 + lat2)/2);
	var g = getRad((lat1 - lat2)/2);
	var l = getRad((lng1 - lng2)/2);
	var sg = Math.sin(g);
	var sl = Math.sin(l);
	var sf = Math.sin(f);
	var s,c,w,r,d,h1,h2;
	var a = EARTH_RADIUS;
	var fl = 1/298.257;
		sg = sg*sg;
		sl = sl*sl;
		sf = sf*sf;
		s = sg*(1-sl) + (1-sf)*sl;
		c = (1-sg)*(1-sl) + sf*sl;
		w = Math.atan(Math.sqrt(s/c));
		r = Math.sqrt(s*c)/w;
		d = 2*w*a;
		h1 = (3*r -1)/2/c;
		h2 = (3*r +1)/2/s;
	return (d*(1 + fl*(h1*sf*(1-sg) - h2*(1-sf)*sg))).toFixed(2);
}

/*ajax获取分页数据
 * @Param        string          imgPath                  图片目录路径
 * @Param        obj             targetObj                被填充的对象(盒子)
 * @Param        int             page                     下一页的页码
 * @Param        int             totalPage                总页码
 * @Param        url             ajaxUrl                  ajax后台接收地址
 * @Param        url             detailUrl                商品/店铺详情地址
 * @Param        url             collectUrl               收藏地址
 * @Param        int             showType                 页面数据显示类型
 * @Param        obj             me                       对象
 * */
function ajaxGetDataList(imgPath,targetBox,page,totalPage,ajaxUrl,showType,me,tabLoadName,detailUrl,collectUrl){
    if(page<=totalPage){
        $.ajax({
            url:ajaxUrl,
            type:"post",
            dataType:"json",
            data:{
                'is_ajax':1,
                'p':page,
                'favorite_type':showType,
            },
            success:function(data){
                var str='';
                switch (showType){
                    case 0:
                        $.each(data.data.list,function(index, vo) {
                            str+="<li id='favorite'"+vo['goods_id']+">";
                            str+="<div class=\"g-img\"><a href=\""+detailUrl+"?id="+vo['goods_id']+"\"><img src=\""+imgPath+"statics/mobile/images/default_img.png\" data-original=\""+imgPath+"uploads/"+vo['goods_img']+"_c200x200\" alt=\"\"></a></div>";
                            str+="<div class=\"g-info\">";
                            str+="<div class=\"product-name\">";
                            str+="<a href=\""+detailUrl+"?id="+vo['goods_id']+"\">"+vo['goods_title']+"</a>";
                            str+="</div>";
                            str+="<div class=\"product-price-m\">";
                            str+="<em>¥<span class=\"big-price\">"+vo['want_price']+"</span></em>";
                            str+="</div>";
                            str+="<div class=\"location-and-time\">";
                            str+="<span class=\"location fl\"><i class=\"icons icon-location fl\"></i><span class=\"address fl\">距您<span class=\"distance apart\" data-lng=\""+vo['lng']+"\" data-lat=\""+vo['lat']+"\">0</span>Km</span></span>";
                            str+="<a href=\"javascriot:void(0);\"  onclick='cancelFavorite("+vo['goods_id']+");'class=\"collection-cancel fr\"><i class=\"icons icon-like-yet fl\"></i><span class=\"fr\">取消收藏</span></a>";
                            str+="</div>";
                            str+="</div>";
                            str+="</li>";
                        })
                        break;
                    case 1:
                        $.each(data.data.list,function(index, vo) {
                            str+="<li id='favorite"+vo['store_id']+"'>";
                            str+="<div class=\"items-user\">";
                            str+="<a href=\""+detailUrl+"?shopid="+vo['store_id']+"\" class=\"user-img fl\"><img src=\""+imgPath+"statics/mobile/images/default_img.png\" data-original=\""+imgPath+vo['logo']+"_c200x200\" alt=\"\"></a>";
                            str+="<div class=\"name-and-location fl\">";
                            str+="<a href=\""+detailUrl+"?shopid="+vo['store_id']+"\" class=\"name\">"+vo['store_name']+"</a>";
                            str+="<span class=\"location\"><i class=\"icons icon-location fl\"></i><span class=\"address fl\">距您<span class=\"distance apart\" data-lng=\""+vo['lng']+"\" data-lat=\""+vo['lat']+"\">0</span>Km</span></span>";
                            str+="</div>";
                            str+="<div class=\"my-collection fr\">";
                            str+="<a href=\"javascriot:void(0);\" onclick='cancelFavorite("+vo['store_id']+",1);' class=\"collection-cancel fr\"><i class=\"icons icon-like-yet fl\"></i><span class=\"fr\">取消收藏</span></a>";
                            str+="</div>";
                            str+="</div>";
                            str+="</li>";
                        })
                        break;
                }
                $(targetBox).append(str);
                // 每次数据加载完，必须重置
                me.resetload();
            },
            error: function(xhr, type){
//                                alert('Ajax error!');
                // 即使加载出错，也得重置
                me.resetload();
            }
        });
    }else{
        // 数据加载完
        tabLoadName = true;
        // 锁定
        me.lock();
        // 无数据
        me.noData();
        me.resetload();
    }
}
/*
* 根据经纬度计算距离
* @param      obj          obj      被选择的对象
* */
function calcDistance(obj) {
    $(obj).each(function(){
        var lng = parseFloat($(this).attr("data-lng"));
        var lat = parseFloat($(this).attr("data-lat"));
        var distance = getFlatternDistance(usersLat,usersLng,lat,lng);
        $(this).text(distance);
    })
}

/**筛选分类选项卡切换，带Ajax
 * @Param        int      selectId           URL中的分类ID的值
 * @Param        int      dataId             Ajax传值的一级分类ID
 * @Param        obj      objBox             Ajax获取数据后要append的box
 * @Param        url      ajaxUrl            后台要接收ajax值的url地址
 * */
function menuTabChangeAjax(selectId,dataId,objBox,ajaxUrl,isConfirm){
	var isConfirm = arguments[4] ? arguments[4] : false;
	$.ajax({
		url:ajaxUrl,
		type:"post",
		dataType:"json",
		data: {
			'id': dataId
		},
		cache: true,
		beforeSend:function(){
			//获取数居前，加载loading
			$(".right-show-box").append("<div id=\"loadingBox\" class=\"loading-box\"><div class=\"loadEffect\"><span></span> <span></span> <span></span><span></span> <span></span> <span></span> <span></span> <span></span> </div> </div>");
		},
		success:function(data){
			if(data.status==1){
				//把ajax获取的json数据遍历出来，并组成html字符串append到objBox里
				$(objBox).html("");
				var strHtml = "";
				$.each(data.data,function(index,val){
					strHtml += "<dl class=\"filter-option-dl\">";
					strHtml += "<dt class=\"filter-option-dt\">" +val['title']+ "</dt>";
					strHtml += " <dd class=\"filter-option-dd\">";
					var tempHtml = "";
					if(typeof(val['children'])!="undefined") {
						$.each(val['children'], function (i, sub) {
							tempHtml += "<span data-value=\"" + sub['id'] + "\" class=\"filter-option ";
							if (sub['id'] == selectId) {
								tempHtml += " select";
							}
							tempHtml += "\">" + sub['title'] + "</span>";
						});
					}
					strHtml += tempHtml;
					strHtml += "</dd>";
					strHtml += "</dl>";
				});
				$(objBox).html(strHtml);
				if(!isConfirm) {
					$(".filter-option-dl").find(".filter-option").click(function () {
						$(this).addClass("select").siblings().removeClass("select");
						$(this).parent().parent().siblings().find(".filter-option").removeClass("select");
						$("#categoryId").val($(this).attr("data-value"));
					})
				}else{
					//商品分类选择
					$("#goods-type-box .filter-option").on("click",function(){
						var curVal = $(this).attr("data-value");
						var curText = $(this).text();
						$("#goods-type").val(curVal);
						$("#goods-type").parent().find(".focusBtn").text(curText);
						$("#goods-type-box .filter-option").removeClass("select");
						$(this).addClass("select");
						closeOverLay($("#goods-type-box"));
					})
				}
			}
		},
		complete:function(){
			//ajax获取数据后移除loading
			$("#loadingBox").remove();
		}
	});
}
/**移动端浮层内滚动窗体不滚动的JS处理
*  @Param        container表示委托的浮层容器元素（$包装器对象），或者页面其他比较祖先的元素
**/
$.smartScroll = function(container, selectorScrollable) {
    // 如果没有滚动容器选择器，或者已经绑定了滚动时间，忽略
    if (!selectorScrollable || container.data('isBindScroll')) {
        return;
    }

    // 是否是搓浏览器
    // 自己在这里添加判断和筛选
    var isSBBrowser;

    var data = {
        posY: 0,
        maxscroll: 0
    };

    // 事件处理
    container.on({
        touchstart: function (event) {
            var events = event.touches[0] || event;

            // 先求得是不是滚动元素或者滚动元素的子元素
            var elTarget = $(event.target);

            if (!elTarget.length) {
                return;
            }

            var elScroll;

            // 获取标记的滚动元素，自身或子元素皆可
            if (elTarget.is(selectorScrollable)) {
                elScroll = elTarget;
            } else if ((elScroll = elTarget.parents(selectorScrollable)).length == 0) {
                elScroll = null;
            }

            if (!elScroll) {
                return;
            }

            // 当前滚动元素标记
            data.elScroll = elScroll;

            // 垂直位置标记
            data.posY = events.pageY;
            data.scrollY = elScroll.scrollTop();
            // 是否可以滚动
            data.maxscroll = elScroll[0].scrollHeight - elScroll[0].clientHeight;
        },
        touchmove: function () {
            // 如果不足于滚动，则禁止触发整个窗体元素的滚动
            if (data.maxscroll <= 0 || isSBBrowser) {
                // 禁止滚动
                event.preventDefault();
            }
            // 滚动元素
            var elScroll = data.elScroll;
            // 当前的滚动高度
            var scrollTop = elScroll.scrollTop();

            // 现在移动的垂直位置，用来判断是往上移动还是往下
            var events = event.touches[0] || event;
            // 移动距离
            var distanceY = events.pageY - data.posY;

            if (isSBBrowser) {
                elScroll.scrollTop(data.scrollY - distanceY);
                elScroll.trigger('scroll');
                return;
            }

            // 上下边缘检测
            if (distanceY > 0 && scrollTop == 0) {
                // 往上滑，并且到头
                // 禁止滚动的默认行为
                event.preventDefault();
                return;
            }

            // 下边缘检测
            if (distanceY < 0 && (scrollTop + 1 >= data.maxscroll)) {
                // 往下滑，并且到头
                // 禁止滚动的默认行为
                event.preventDefault();
                return;
            }
        },
        touchend: function () {
            data.maxscroll = 0;
        }
    });

    // 防止多次重复绑定
    container.data('isBindScroll', true);
};