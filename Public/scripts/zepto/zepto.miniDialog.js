// JavaScript Document
//MinDialog For zepto || Jquery
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
})(this, function($){
    $.fn.extend({
        minDialog:function(options,callback){
            var defaults = {
                isMask: true,//是否启用底层遮罩
                isAutoClose: false,//是否自动关闭，设置为true与关闭时间同时使用
                title:'系统提示',//弹出窗口标题
                content:'',//弹出窗口内容
                closeTime: 1500,//自动关闭时间
                url:'',//iframe
                width: '300px',//iframe弹出窗口宽度
                height: '200px',
                isCloseAll: false,//是否关闭所有dialog
                isConfirm: false, //是否添加确定取消按钮
                btnCancal: '取消', //取消按钮
                btnConfirm: '确认' //确认按钮
            };
            var options = $.extend(defaults, options);
            //创建插件
            var r = Math.ceil(Math.random()*10);//随机数,防止窗口ID重复
            var maskId = "dialogMask"+r,mainId = "dialogMainBox"+r,boxId = "dialogBox"+r,titleId = "dialogTitle"+r,btnId = "dialogCloseBtn"+r,cId = "dialogContent"+r,toolsId = "dialogTools"+r;
            var str_mask = "<div class=\"dialogMask\" id=\""+maskId+"\"></div>";
            var str_box = "<div class=\"dialogMainBox\" id=\""+mainId+"\"><div class=\"dialogBox\" id=\""+boxId+"\"></div></div>";
            var str_title = "<div class=\"dialogTitle\" id=\""+titleId+"\"></div>";
            var str_title_h3 = "<h3>"+options.title+"</h3>";
            var str_close_btn = "<div class=\"dialogCloseBtn\" id=\""+btnId+"\">×</div>";
            var str_box_content = "<div class=\"dialogContent\" id=\""+cId+"\"></div>";
            var str_tools_bar = "<div class=\"dialogTools\" id=\""+toolsId+"\"></div>";
            if(options.isMask){
                $("body").append(str_mask);//添加遮罩蒙板
            }
            $("body").append(str_box);//添加弹出窗口内容box
            if(!options.isAutoClose){
                $("#"+boxId).append(str_title);//内容box添加内容
                $("#"+titleId).append(str_title_h3);//添加标题内容
                $("#"+titleId).append(str_close_btn);//标题内容添加关闭按钮
            }
            $("#"+boxId).append(str_box_content);//内容box添加内容

            if(options.isConfirm){
                var str_btn = "<a href=\"javascript:;\" class=\"btn cancelBtn\">"+options.btnCancal+"</a><a href=\"javascript:;\" class=\"btn confirmBtn\">"+options.btnConfirm+"</a>";
                $("#"+boxId).append(str_tools_bar);//添加底部
                $("#"+toolsId).append(str_btn);//内容确定和取消按钮
                $(".cancelBtn").on("click",function(){
                    closeAllDialog();
                })
                $(".confirmBtn").on("click",function(){
                    closeAllDialog();
                })
            }
            if(options.url!=""){
                var str_iframe = "<iframe width=\""+options.width+"\" height=\""+options.height+"\" id=\"dialogIframe"+r+"\" name=\"dialogIframe"+r+"\"";
                str_iframe += "frameborder=\"no\"  marginheight=\"0\" marginwidth=\"0\" allowTransparency=\"true\" </iframe>";
                $("#"+cId).append(str_iframe);//内容box添加内容
                $("#dialogIframe"+r).attr("src",options.url)
            }else{
                $("#"+cId).append(options.content);//内容box添加内容
            }
            var w = $("#"+boxId).width();
            var h = $("#"+boxId).height();

            // $("#"+mainId).css({"margin-left":"-"+(w/2)+"px","margin-top":"-"+(h/2)+"px"});
            if(!options.isAutoClose){
                if(options.isCloseAll){
                    $("#"+btnId).on("click",function(){
                        closeAllDialog();
                    })
                }else{
                    $("#"+btnId).on("click",function(){
                        closeDialog(r);
                    })
                }
            }else{
                if(options.isCloseAll) {
                    setTimeout(function () {
                        closeAllDialog();
                    }, options.closeTime);
                }else{
                    setTimeout(function () {
                        closeDialog(r);
                    }, options.closeTime);
                }
            }
            if(callback){
                callback();
            }
        }
    });
    function closeDialog(r){//关闭窗口
        $("#dialogMainBox"+r).remove();
        $("#dialogMask"+r).remove();
    };
    function closeAllDialog(){
        parent.$("body").find(".dialogMainBox").remove();
        parent.$("body").find(".dialogMask").remove();
    }
});
