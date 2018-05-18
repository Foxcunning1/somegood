/*单选 */
function singleChecked(obj){
    if($(obj).hasClass('chk-selected-box')){
        $(obj).removeClass('chk-selected-box');
        $(obj).parent().find("input[type='checkbox']").removeAttr("checked");
    }else{
        $(obj).addClass('chk-selected-box');
        $(obj).parent().find("input[type='checkbox']").attr("checked","checked");
    }
}
/*全选*/
function allChecked(obj){
    if($(obj).hasClass("chk-selectedall-box")){
        $(obj).removeClass("chk-selectedall-box");
        $(".show-group li").find(".icon-checkbox").removeClass("chk-selected-box");
        $(".show-group").find("input[type='checkbox']").removeAttr("checked");
    }else{
        $(obj).addClass("chk-selectedall-box");
        $(".show-group li").find(".icon-checkbox").addClass("chk-selected-box");
          $(".show-group").find("input[type='checkbox']").attr("checked","checked");
    }
}
/*数量加减
 *Param        obj          obj                     当前操作的对象
 *Param        int         status           计算类型：0为加法，1为减法
  */
function plusOrReduce(obj,status){
    var itemNum = obj.parent().find(".num").val();
    if(status){
        itemNum++;
        obj.parent().find(".num").val(itemNum);
        cartCount();
    }else{
        itemNum--;
        if(itemNum>=1){
          obj.parent().find(".num").val(itemNum);
          cartCount();
        }

    }
}
/*计算*/
function cartCount(){
    var totalPrice = 0;//总价
	  var totalSelectNum = $(".chk-selected-box").length; //选中的个数
	  var totalNumber = $(".icon-checkbox").length; // 加入购物车的商品个数
    var totalAmount = 0; //结算按钮的数量
    var curTotalPrice = 0;
    $(".icon-checkbox").each(function(){
      if($(this).hasClass("chk-selected-box")){
        var curObj = $(this);
        var curPrice = parseFloat(curObj.parent().parent().siblings(".shop-cart-item-core").find(".good-price").text()).toFixed(2);//获取当前行的单价
        var curNum = curObj.parent().parent().siblings(".shop-cart-item-core").find("input.num").val(); //商品加减后的数量
        curTotalPrice = curPrice * curNum;
        totalPrice += curTotalPrice;
        //totalPrice =totalPrice.toFixed(2);
        totalAmount += parseInt(curNum);
        $(".bottom-bar-price").text(totalPrice.toFixed(2));
        $(".money-unit-bf").text(totalPrice.toFixed(2));
      }else{
        $(".bottom-bar-price").text(totalPrice.toFixed(2));
        $(".money-unit-bf").text(totalPrice.toFixed(2));
      }

    })
    if(totalSelectNum==0){
        $("#checkIcon-1").removeClass("chk-selectedall-box");
        $(".btn-right-block").removeClass("active");
        $("#checkedNum").text(totalAmount);
    }else{
    	if(totalSelectNum==totalNumber){
    		  $(".icon-checkboxall").addClass("chk-selectedall-box");
  		}else{
          $(".icon-checkboxall").removeClass("chk-selectedall-box");
  		}
      $(".btn-right-block").addClass("active");
      $("#checkedNum").text(totalAmount);
    }
}
/*更新购物车信息
 *Param         string            goods_id                    商品ID与服务人员ID组合
 */
function updateCartGoods(obj, ajax_url, goods_id, num){
    if(goods_id!=""){
        $.ajax({
            url:ajax_url,//ajax地址
            type:"POST",
            data:{goodsid:goods_id,counts:num},//post的数据
            dataType:'json',
            success:function(data){//提交成功
                if (data.status != 1) {
                      $("body").minDialog({isAutoClose:true,isCloseAll:true,content:data.info});
                      var goods_num = obj.parent().find(".num").val();
                      var default_num = parseInt(goods_num);
                      if (default_num != 1) {
                          var c = default_num-1;
                          obj.parent().find(".num").val(c);
                      }
                }
            }
        })
    }
}
/*判断购物车页面中是否有商品，如果没有则执行页面刷新
 *
 * */
function checkCartIsNull(){
    var num = 0;
    $(".icon-checkbox").each(function(){
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
        url:ajax_url,//ajax地址
        type:"POST",
        data:{cart_id:goods_id},//post的数据
        dataType:'json',
        success:function(data,textStatus){//提交成功
            if(data.status==1){//删除成功
                //delCartItem(goods_id, box_element);//删除节点
                cartCount();//执行价格计算
                // if(is_dialog){//调取dialog插件提示用户删除成功
                //     var d = dialog({content:"删除成功！"}).show();
                //     window.setInterval(function(){
                //         d.close().remove()},1000);
                // }
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
 * param        string          goodsid          商品ID组合与服务人员ID组合，组合格式如：1-2，多个组合之间以“,”号进行分割
 * param        string          ajax_url         ajax地址
 * param        string          del_ajax_url     删除商品对应的ajax地址
 * param        string          jump_url         调转地址
 * param        string          box_element      商品对应行的id前缀；注：此参数是为了兼容mini购物车的操作
 * */
function removeCartToFavorites(goods_id,ajax_url, del_ajax_url, jump_url,box_element){
    $.ajax({
        url: ajax_url,//ajax地址，后台先执行移入收藏夹操作，然后再进行购物车删除操作
        type: "POST",
        data: {goodsid:goods_id},//post的数据
        dataType: "json",
        success:function(data,textStatus){//提交成功
            //根据返回状态执行对应操作
            if(data.status==-1){//状态根据后台返回数据获取
                //调取dialog提示用户未登录，并跳转至登录页面
                var d = dialog({content:"亲，您还没登录，请先登录！"}).show();
                window.setInterval(function(){
                    d.close().remove();jumpToUrl(jump_url);},1000);
            }
            if(data.status==1){
                //ajax删除商品并移除当前行的html
                var d = dialog({content:"移入成功！"}).show();
                window.setInterval(function(){
                    d.close().remove()},1000);
                delCart(goods_id,del_ajax_url, false, box_element);
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
            var preItem = $(itemObj).pre("div.item");//获取当前元素的上一个元素
            preItem.addClass("last-item");
        }
        $(itemObj).remove();

    }
}
//页面跳转
function jumpToUrl(url){
    location.href = url;
}
/*购物车结算
 */
function submitCartGoods(){
    var goodsids = "";
    $(".chk-selected-box").each(function(){
        goodsids += $(this).next().val()+",";
    })

    if(goodsids!=""){//Ajax提交选中的商品到
        $("#cartForm").submit();
    }else{//未选择任何checkbox，dialog弹出提示选择
        $("body").minDialog({isCloseAll:true,closeTime:'800',isAutoClose:true,content:'您还没有选择宝贝哦!'});
    }
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
