/*更多地址展开*/
$(function() {
    $(".addr-switch").on('click', function() {
        /* Act on the event */
        if ($(this).hasClass("switch-on")) {
            if ($(this).hasClass("hide")) {
                $(this).removeClass("hide");
                $(".switch-off").addClass("hide");
                $(".ui-scrollbar-wrap").removeClass("auto");
            } else {
                $(this).addClass("hide");
                $(".switch-off").removeClass("hide");
                $(".ui-scrollbar-wrap").addClass("auto");
            }
        } else {
            if ($(this).hasClass("hide")) {
                $(this).removeClass("hide");
                $(".switch-on").addClass("hide");
                $(".ui-scrollbar-wrap").addClass("auto");
            } else {
                $(this).addClass("hide");
                $(".switch-on").removeClass("hide");
                $(".ui-scrollbar-wrap").removeClass("auto");
            }
        }
    });
    $(document).on("mouseover mouseout",".ui-switchable-panel",function(event){
         if(event.type == "mouseover"){
          //鼠标悬浮
          $(this).addClass("li-hover");
          $(this).find(".op-btns").css("visibility", "visible");
          if ($(this).find(".consignee-item").hasClass('item-selected')) {
              $(this).find(".del-consignee").css("visibility", "hidden");
          }
         }else if(event.type == "mouseout"){
          //鼠标离开
          $(this).removeClass("li-hover");
          $(this).find(".op-btns").css("visibility", "hidden");
          $(this).find(".del-consignee").css("visibility", "");
         }
        })
    /*默认地址选中*/
    $("#consigneeList").on('click', '.consignee-item', function(){
        $(this).addClass("item-selected").parent().siblings("li").find(".consignee-item").removeClass("item-selected");
    })
    /*设为默认地址*/
    $("#consigneeList").on('click', '.setdefault-consignee', function(){
        var address_id = $(this).next().data("editid");
        clickCurrentObj($(this),address_id);
        $(this).parent().parent("li").addClass("ui-switchable-panel-selected").siblings().removeClass("ui-switchable-panel-selected");
        $(this).parent().parent("li").find(".consignee-item").addClass("item-selected").parent().siblings("li").find(".consignee-item").removeClass("item-selected");
    })
    function clickCurrentObj(obj,address_id) {
        obj.parent().parent().find(".consignee-item").click();      //点击一次ajax加载
        //改变默认地址
        $.ajax({
            data:{address_id:address_id},
            type: 'POST',
            url: clickCurrentUrl,
            dataType: 'json',
            success: function (data) {

            }
        })
    }
    /*支付方式选择*/
    $("#payment-list").find("li").click(function () {
        $(this).addClass("cur").siblings().removeClass("cur");
        var payType = $(this).attr('payType');
        $('#payType').val(payType);
        if ($(this).children().text() == "到店购买") {
            $(".dis-modes").hide();
        }else {
            $(".dis-modes").show();
        }
    });
    // /*配送方式*/
    // $(".mode-tab-nav").find("li").click(function () {
    //     $(this).addClass("curr").siblings().removeClass("curr");
    // })
    /*优惠券选中*/
    $(".c-msg").click(function(){
        $(this).parent(".c-detail").addClass("selected");
    })
    /*取消勾选*/
    $(".item-selected-cancel").click(function(){
        $(this).parent().parent(".c-detail").removeClass("selected");
        event.stopPropagation();
    })
})
