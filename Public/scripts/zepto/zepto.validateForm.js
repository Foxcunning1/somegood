// JavaScript Document
;
(function(root, factory) {
    //amd
    if (typeof define === 'function' && define.amd) {
        define(['$'], factory);
    } else if (typeof exports === 'object') { //umd
        module.exports = factory();
    } else {
        root.Dialog = factory(window.Zepto || window.jQuery || $);
    }
})(this,function($, window, document,undefined) {
	//定义一个对象
	var validate = function(ele,opt){
		this.$element = ele,
		this.defaults = {
			tips_required: '不能为空',
			tips_email: '邮箱地址格式有误',
			tips_num: '请填写数字',
			tips_chinese: '请填写中文',
			tips_mobile: '手机号码格式有误',
			tips_idcard: '身份证号码格式有误',
			tips_pwdequal: '两次密码不一致',
			tips_ajax: '信息已经存在',
			tips_numletter: '请输入数字和字母的组合字符',
			tips_length: '',
			tips_passport: '护照格式有误',
			//正则
			reg_email: /^\w+\@[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$/i,  //验证邮箱
			reg_num: /^\d+$/,                                  //验证数字
			reg_chinese: /^[\u4E00-\u9FA5]+$/,                 //验证中文
			reg_mobile: /^1[34578]{1}[0-9]{9}$/,                //验证手机
			reg_idcard: /^\d{14}\d{3}?\w$/,                    //验证身份证
			reg_numletter: /[^\d|chun]/,                       //验证数字和字母
			reg_passport: /^1[45][0-9]{7}|G[0-9]{8}|P[0-9]{7}|S[0-9]{7,8}|D[0-9]+$/, //验证护照
			//是否Ajax提交
			isAjax:true,
			return_url:'',//验证成功跳转地址
			/*短信发送验证码倒计时调取*/
			isCountDown:false,//是否执行发送验证码倒计时操作,短信发送验证调用
			bObj:'mobile',//倒计时显示文本按钮
			bObjText:'免费获取验证码',//倒计时显示文字
			isBindDel:false,//是否绑定一键删除input[type='text']值的按钮
			autoCloseTime:3000,
		},
		this.options = $.extend({}, this.defaults, opt)
	}
	//创建方法
	validate.prototype = {
		 //遍历所有form元素,并验证
		 validateForm:function(form){
			_this = this;
			$(form).find("input,select,textarea").each(function(){
				var elem = $(this);
				$(this).focus(function(){_this.focusInValidate(elem);});
				$(this).blur(function(){_this.focusOutValidate(elem);});
			});
			$(form).on("submit", function(){
				if(_this.validater(form)){
					if(_this.options.isAjax){
						_this.submitForm(form,_this.options.return_url);
						return false;
					}else{
						return true;
					}
				}else{
					return false;
				}
			 })
		 },
		 //表单元验证
		 validater:function(form){
			 var isValidate = true;
			 var _this = this;
			 $(form).find("input,select,textarea").each(function(){
				var _obj = $(this);
				var _validate = _obj.attr("class");
				if (_validate) {
					var arr = _validate.split(' ');
					for (var i = 0; i < arr.length; i++) {
						if (!_this.rules(_obj, arr[i])) {
							 //验证不通过阻止表单提交，开关false
							isValidate = false;
							return false;
						}
					}
				}
				return isValidate;
			});
			return isValidate;
		 },
		 //验证成功
		 submitForm:function(form,return_url){
			 var op = this.options;
			 var url = $(form).attr("url");
			 /*var params = new Object();*/
			 //组合参数
			/*var items = $(form).find("input[type=hidden],"+
					"input[type=text],"+
					"input[type=password],"+
				 	"input[type=email],"+
				 	"textarea,"+
					"select,"+
					"input[type=radio]:checked,"+
					"input[type=checkbox]:checked");
			 items.each(function(index){
				var _obj = this;
				params[_obj.name] = _obj.value;
			 });*/
			 if(url!=""){
				$.ajax({
					type: "POST",
					url: url,
					data: $(form).serializeArray(),
					dataType: 'json',
					success: function(data){
						//成功执行方法
						if(data.status==1){
							//此处调用minDialog弹出层插件
							parent.$("body").minDialog({isAutoClose:true,isCloseAll:true,closeTime:op.autoCloseTime,content:data.info});
							if(return_url!=""&&!op.isCountDown){//执行跳转
								setTimeout(function(){location.href=return_url;},3000);
							}
							//手机发送后，显示倒计时，需配合common.js里的setCountDowntime()函数使用
							if(op.isCountDown){
								parent.setCountDowntime(op.bObj,op.bObjText);
							}
						}else{
							parent.$("body").minDialog({isAutoClose:true,content:data.info});
						}
					}
				});
				//$(form).ajaxSubmit(ajaxOptions);
			 }
			 return false;
		 },
		 //获取当前元素焦点
		 focusInValidate:function(obj){
			//绑定一键删除文本框内容按钮
			if(($(obj).attr("type")=="text"||$(obj).attr("type")=="password")&&this.options.isBindDel){
				$(obj).parent().append("<i class=\"clearBtn\"></i>");
				$("i.clearBtn").on("touchstart",function(){
					$(obj).val("");
				})
			}
		 },
		 //失去焦点
		 focusOutValidate:function(obj){
			//移除绑定一键删除文本框内容按钮
			if(($(obj).attr("type")=="text"||$(obj).attr("type")=="password"||$(obj).attr("type")=="email")&&this.options.isBindDel){
				$(obj).parent().find("i.clearBtn").remove();
			}
			 //验证
			 var isValidate = true;
			 var isValidated = false;//是否已Ajax提交验证过,防止重复提交
			 var _validate = $(obj).attr("class");
			 if (_validate) {
				var arr = _validate.split(' ');
				for (var i = 0; i < arr.length; i++) {
					if (!this.rules(obj,arr[i])) {
						isValidate = false; //验证不通过阻止表单提交，开关false
						return isValidate;
					}else{
						if(!isValidated){
							isValidated = true;
							if(!this.ajaxValidate(obj))
							{
								isValidate = false;
								return isValidate;
							}else{
								this.showSuccessMsgTips(obj);
							}
						}
						isValidate = true;
					}
				}
				return isValidate;
			 }
		 },//Ajax验证当前元素
		 ajaxValidate:function(obj){
			 var t = this;
			 var ajax_url = $(obj).attr("ajaxurl");
			 if(typeof(ajax_url)!="undefined"){
			 	$.ajax({
					type: "POST",
					url: ajax_url,
					data: {"name":$(obj).attr("name"),"param":$(obj).val()},
					dataType: 'json',
					success: function(data){
						//成功执行方法
						if(data.status==1){
							t.showSuccessMsgTips(obj);
							return true;
						}else{
							t.showErrorMsgTips(obj,data.info);
							return false;
						}
					}
				})
			 }
			 return true;
		 },
		 //验证正则
		 /*
		  *elem       选择对象
		  *_match     正则值
		  *reg_tips   正则错误提示
		 */
		 rules:function(elem,_match){
		    var _this = this;
			var _val = $.trim($(elem).val());
            //根据验证情况，显示相应提示信息，返回相应的值
            switch (_match) {
                case 'required':
                    return _val ? true : _this.showErrorMsgTips(elem, _this.options.tips_required);
                case 'email':
                    return _this.regmatch(_val, _this.options.reg_email) ? true : _this.showErrorMsgTips(elem, _this.options.tips_email);
                case 'num':
                    return _this.regmatch(_val, _this.options.reg_num) ? true : _this.showErrorMsgTips(elem, _this.options.tips_num);
                case 'chinese':
                    return _this.chk(_val, _this.options.reg_chinese) ? true : _this.showErrorMsgTips(elem, _this.options.tips_chinese);
                case 'mobile':
                    return _this.regmatch(_val, _this.options.reg_mobile) ? true : _this.showErrorMsgTips(elem, _this.options.tips_mobile);
                case 'idcard':
                    return _this.regmatch(_val, _this.options.reg_idcard) ? true : _this.showErrorMsgTips(elem, _this.options.tips_idcard);
                case 'numletter':
                    return _this.regmatch(_val, _this.options.reg_numletter) ? true : _this.showErrorMsgTips(elem, _this.options.tips_numletter);
                case 'passport':
                    return _this.regmatch(_val, _this.options.reg_passport) ? true : _this.showErrorMsgTips(elem, _this.options.tips_passport);
                case 'pwd1':
                    pwd1 = _val; //实时获取存储pwd1值
                    return true;
                case 'pwd2':
                    //return pwdEqual(_val, pwd1) ? true : _this.showErrorMsg(obj, this.opt.tips_pwdequal);
                case 'ajaxvalid':
                    //return ajaxValidate(_val, $(elem).attr("ajaxurl")) ? true : _this.showErrorMsg(obj, this.opt.tips_ajax);
                case 'length':
                    //return pwdEqual(_val, elem) ? true : _this.showErrorMsg(obj, this.opt.tips_length);
                default:
                    return true;
            };
		 },//密码二次验证
		 pwdEqual: function (val1, val2) {
            return val1 === val2 ? true : false;
         },//正则匹配
         regmatch: function (value, regExp) {
            if (value !== "") {
                return regExp.test(value);
            }
            return true;
         },
		 //显示正确提示
		 showSuccessMsgTips:function(obj){
			 $(obj).parent().find("span").remove();
			 if(typeof($(obj).attr("sucmsg"))!="undefined"){
				  $(obj).parent().append("<span class=\"validateSuccess\">"+$(obj).attr("sucmsg")+"</span>");
			 }else{
				 $(obj).parent().append("<span class=\"validateSuccess\">&nbsp;</span>");
			 }
			 return true;
		 },//显示错误提示
		 showErrorMsgTips:function(obj,tips){
			 $(obj).parent().find("span").remove();
			 $(obj).parent().append("<span class=\"validateError\">"+tips+"</span>");
		     return false;
		 },//清除提示
		 clearMsgTips:function(obj){
			 $(obj).parent().find("span").remove();
		 }
	}
	//初始化插件
	$.fn.validateForm = function(options){
		var validate_form = new validate(this,options);
		return validate_form.validateForm(this);
	}
});
