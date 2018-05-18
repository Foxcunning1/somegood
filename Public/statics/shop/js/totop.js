//回到顶部的隐藏和显示,返回顶部按钮自动隐藏
$(function(){
	$(window).scroll(function(){
	    if ($(window).scrollTop()>360){
	    	$("#totop").fadeIn();
	    }else if($(window).scrollTop()<360){
	    	$("#totop").fadeOut();
	    }
	});
	// 返回顶部按钮
	$('#totop').click(function() {
	    $("html, body").animate({scrollTop:0}, 200);
	});
})