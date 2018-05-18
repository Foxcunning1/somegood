<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <title>查看订单</title>
  <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
  <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
  <script type="text/javascript">
    $(function () {
      // $("#btnAudit").click(function () { OrderAudit(); });   //审核订单
      // $("#btnAuditFail").click(function () { OrderAuditFail(); });   //审核失败
      // $("#btnPayment").click(function () { OrderPayment(); });   //确认付款
      // $("#btnComplete").click(function () { OrderComplete(); }); //完成订单
      // $("#btnInstalment").click(function () { OrderInstalment(); }); //分期付款
      // $("#btnCancel").click(function () { OrderCancel(); });     //取消订单
      // $("#btnInvalid").click(function () { OrderInvalid(); });   //作废订单
      // $("#btnPrint").click(function () { OrderPrint(); });       //打印订单
      // $("#btnReceive").click(function () { OrderReceive(); });   //确认收到订单
      //
      // $("#btnEditAcceptInfo").click(function () { EditAcceptInfo(); }); //修改收货信息
      // $("#btnEditRemark").click(function () { EditOrderRemark(); });    //修改订单备注
      $("#btnEditOriginalPrice").click(function () { EditOriginalPrice(); }); //修改商品总价
      // $("#btnEditOriginalAddress").click(function () { EditOriginalAddress(); }); //修改地址
      // $("#btnEditExpressFee").click(function () { EditExpressFee(); }); //修改配送费用
      // $("#btnEditPaymentFee").click(function () { EditPaymentFee(); }); //修改支付手续费
      // $("#btnEditInvoiceTaxes").click(function () { EditInvoiceTaxes(); }); //修改发票税金
      $("#btnEditLogisticsSn").click(function () { EditLogisticsSn(); }); //修改物流信息
      $("#btnEditLogisticsType").click(function () {
        var edit_type='logistics_type';
         updateData(edit_type);
       }); //修改物流信息

    });
    //审核完成
    function OrderAudit() {
      var winDialog = top.dialog({
        title: '确认审核',
        content: '操作提示信息：<br/><br/>确认审核后，订单进入待付款状态<br/><br/><textarea id="txtOrderAuditRemark" name="order_audit_remark" rows="2" cols="20" class="input"></textarea>',
        okValue: '确定',
        width: 300,
        ok: function () {
          var remark = $("#txtOrderAuditRemark", parent.document).val();
          var payType = $("input:radio[name='pay_type']:checked",parent.document).val();
          if(remark == '') {
            top.dialog({
              title: '提示',
              content: '对不起，请输入备注',
              okValue: '确定',
              ok: function () {
              }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_audit",'remark' : remark,'pay_type':payType};
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }
    //审核失败
    function OrderAuditFail() {
      var winDialog = top.dialog({
        title: '审核失败',
        content: '操作提示信息：<br />审核失败后，需要填写审核失败原因<br/><br/><textarea id="txtOrderAuditFailRemark" name="order_audit_fail_remark" rows="2" cols="20" class="input"></textarea>',
        okValue: '确定',
        width: 300,
        ok: function () {
          var remark = $("#txtOrderAuditFailRemark", parent.document).val();
          if(remark == '') {
            top.dialog({
              title: '提示',
              content: '对不起，请输入失败原因！',
              okValue: '确定',
              ok: function () {
              }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_audit_fail",'remark' : remark};
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }

    //完成订单
    function OrderComplete() {
      var winDialog = top.dialog({
        title: '完成订单',
        content: '操作提示信息：<br />确认收到款项后，确认订单完成？<br/><br/><textarea id="txtOrderCompleteRemark" name="order_complete_remark" rows="2" cols="20" class="input"></textarea>',
        okValue: '确定',
        width: 300,
        ok: function () {
          var remark = $("#txtOrderCompleteRemark", parent.document).val();
          if (remark == '') {
            top.dialog({
              title: '提示',
              content: '对不起，请输入备注',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var orderSn = "<?php echo ($list["order_sn"]); ?>";
          var postData = { "order_no": orderSn, "edit_type": "order_complete",'remark' : remark};
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }
    //账单付款
    function billPay(periods) {
      var winDialog = top.dialog({
        title: '确认付款',
        content: '操作提示信息：<br />1、确认公司账户中已经收到付款之后，再确认，注意！！！<br />2、确认支付后无法修改金额，确认要继续吗？<br/><br/><textarea id="txtBillPayRemark" name="order_payment_remark" rows="2" cols="20" class="input"></textarea>',
        okValue: '确定',
        width: 300,
        ok: function () {
          var remark = $("#txtBillPayRemark", parent.document).val();
          if (remark == '') {
            top.dialog({
              title: '提示',
              content: '对不起，请输入备注',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "bill_pay",'remark' : remark,"periods":periods};
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }


    //确认收到订单
    function OrderReceive() {
      var winDialog = top.dialog({
        title: '提示',
        content: '点击后，订单会置为未审核状态！',
        okValue: '确定',
        ok: function () {
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_receive" };
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData,"<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }

    //取消订单
    function OrderCancel() {
      var winDialog = top.dialog({
        title: '取消订单',
        content: '操作提示信息：<br />必须线下与客户沟通后，再进行操作，确认已经与客户沟通过了吗？<br/><br/><textarea id="txtOrderCancelRemark" name="order_cancel_remark" rows="2" cols="20" class="input"></textarea>',
        okValue: '确定',
        width: 300,
        ok: function () {
          var remark = $("#txtOrderCancelRemark", parent.document).val();
          if (remark == '') {
            top.dialog({
              title: '提示',
              content: '对不起，请输入备注',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_cancel",'remark' : remark};
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }
    //作废订单
    function OrderInvalid() {
      var winDialog = top.dialog({
        title: '取消订单',
        content: '操作提示信息：<br />必须线下与客户沟通后，再进行操作，确认已经与客户沟通过了吗？<br/><br/><textarea id="txtOrderInvalidRemark" name="order_invalid_remark" rows="2" cols="20" class="input"></textarea>',
        okValue: '确定',
        width: 300,
        ok: function () {
          var remark = $("#txtOrderInvalidRemark", parent.document).val();
          if (remark == '') {
            top.dialog({
              title: '提示',
              content: '对不起，请输入备注',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_invalid",'remark' : remark};
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
       /* content: '操作提示信息：<br />1、匿名用户，请线下与客户沟通；<br />2、会员用户，自动检测退还金额或积分到账户；<br />3、请单击相应按钮继续下一步操作！',
        button: [{
          value: '检测退还',
          callback: function () {
            var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_invalid", "check_revert": 1 };
            //发送AJAX请求
            sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
            return false;
          },
          autoFocus: true
        }, {
          value: '直接作废',
          callback: function () {
            var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_invalid", "check_revert": 0 };
            //发送AJAX请求
            sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
            return false;
          }
        }, {
          value: '关闭',
          callback: function () { }
        }]
      }).showModal();*/
    }
    //打印订单
    function OrderPrint() {
      var winDialog = top.dialog({
        title: '打印订单',
        url: 'dialog/dialog_print.aspx?order_no=' + $("#spanOrderNo").text(),
        width: 850
      }).showModal();
    }
    //修改收货信息
    function EditAcceptInfo() {
      var winDialog = top.dialog({
        title: '修改收货信息',
        url: 'dialog/dialog_accept.aspx',
        width: 550,
        height: 320,
        data: window //传入当前窗口
      }).showModal();
    }
    //修改订单备注
    function EditOrderRemark() {
      var winDialog = top.dialog({
        title: '订单备注',
        content: '<textarea id="txtOrderRemark" name="txtOrderRemark" rows="2" cols="20" class="input">' + $("#divRemark").html() + '</textarea>',
        okValue: '确定',
        ok: function () {
          var remark = $("#txtOrderRemark", parent.document).val();
          if (remark == "") {
            top.dialog({
              title: '提示',
              content: '对不起，请输入订单备注内容！',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "edit_order_remark", "remark": remark };
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }
    //修改订单物流单号
    function EditLogisticsSn() {
      var winDialog = top.dialog({
        title: '订单备注',
        content: '<textarea id="txtOrderRemark" name="txtOrderRemark" rows="2" cols="20" class="input">' + $("#divRemark").html() + '</textarea>',
        okValue: '确定',
        ok: function () {
          var remark = $("#txtOrderRemark", parent.document).val();
          if (remark == "") {
            top.dialog({
              title: '提示',
              content: '对不起，请输入订单备注内容！',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "edit_order_logistics_sn", "remark": remark };
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }

    //修改商品总金额
    function EditOriginalPrice() {
      var winDialog = top.dialog({
        title: '请修改订单商品总价',
        content: '修改价格：<input id="txtDialogAmount" type="text" value="' + $("#tureMoney").text() + '" class="input" /><br/><br/>备注信息：<br/><br/><textarea id="txtOrderEditOriginalPrice" rows="2" cols="20" class="input"></textarea>',
        okValue: '确定',
        ok: function () {
          var totalMoney = $("#txtDialogAmount", parent.document).val();
          var remark = $('#txtOrderEditOriginalPrice', parent.document).val();
          //alert(totalMoney);
          if (!checkIsMoney(totalMoney)) {
            top.dialog({
              title: '提示',
              content: '对不起，请输入正确的金额！',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var orderSn = "<?php echo ($list["order_sn"]); ?>";
          var postData = { "order_no": orderSn, "edit_type": "edit_original_price", "total_money": totalMoney ,'remark': remark};
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }
    //修改地址
    function EditOriginalAddress() {
        var winDialog = top.dialog({
            title: '请修改收获地址',
            content: '修改地址：<input id="txtDialogAddress" type="text" value="' + $("#txtOriginalAddress").text() + '" class="input" /><br/><br/>备注信息：<br/><br/><textarea id="txtOrderEditOriginalAddress" rows="2" cols="20" class="input"></textarea>',
            okValue: '确定',
            ok: function () {
                var address = $("#txtDialogAddress", parent.document).val();
                var remark = $('#txtOrderEditOriginalAddress', parent.document).val();
                if (!checkIsMoney(address)) {
                    top.dialog({
                        title: '提示',
                        content: '对不起，请输入正确的地址！',
                        okValue: '确定',
                        ok: function () { }
                    }).showModal(winDialog);
                    return false;
                }
                var orderSn = "<?php echo ($list["order_sn"]); ?>";
                var postData = { "order_no": orderSn, "edit_type": "edit_original_address", "_address": address ,'remark': remark};
                //发送AJAX请求
                sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
                return false;
            },
            cancelValue: '取消',
            cancel: function () { }
        }).showModal();
    }
    //修改配送费用
    function EditExpressFee() {
      var winDialog = top.dialog({
        title: '请修改配送费用',
        content: '<input id="txtDialogAmount" type="text" value="' + $("#spanExpressFeeValue").text() + '" class="input" />',
        okValue: '确定',
        ok: function () {
          var amount = $("#txtDialogAmount", parent.document).val();
          if (!checkIsMoney(amount)) {
            top.dialog({
              title: '提示',
              content: '对不起，请输入正确的配送金额！',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "edit_express_fee", "express_fee": amount };
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "../../tools/admin_ajax.ashx?action=updateOrder");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }
    //修改手续费用
    function EditPaymentFee() {
      var winDialog = top.dialog({
        title: '请修改支付手续费用',
        content: '<input id="txtDialogAmount" type="text" value="' + $("#spanPaymentFeeValue").text() + '" class="input" />',
        okValue: '确定',
        ok: function () {
          var amount = $("#txtDialogAmount", parent.document).val();
          if (!checkIsMoney(amount)) {
            top.dialog({
              title: '提示',
              content: '对不起，请输入正确的手续费用！',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "edit_payment_fee", "payment_fee": amount };
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "../../tools/admin_ajax.ashx?action=updateOrder");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }
    //修改税金费用
    function EditInvoiceTaxes() {
      var winDialog = top.dialog({
        title: '请修改发票税金费用',
        content: '<input id="txtDialogAmount" type="text" value="' + $("#spanInvoiceTaxesValue").text() + '" class="input" />',
        okValue: '确定',
        ok: function () {
          var amount = $("#txtDialogAmount", parent.document).val();
          if (!checkIsMoney(amount)) {
            top.dialog({
              title: '提示',
              content: '对不起，请输入正确的税金费用！',
              okValue: '确定',
              ok: function () { }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "edit_invoice_taxes", "invoice_taxes": amount };
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "../../tools/admin_ajax.ashx?action=updateOrder");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }

    function service(data,type) {
      var uid = <?php echo ($list["uid"]); ?>;
      var orderSn = <?php echo ($list["order_sn"]); ?>;
      $.ajax({
        type: "post",
        url: "<?php echo U('Order/service');?>",
        data:{'on':$(data).attr('value') , 'uid':uid , 'order_sn':orderSn , 'type':type},
        dataType: "json",
        success:function (item) {
          var d = dialog({ content: item.msg }).show();
          setTimeout(function () {
            d.close().remove();
            location.reload(); //刷新页面
          }, 2000);
        }
      })
    }

    function sendBill(periods) {
      var winDialog = top.dialog({
        title: '发送账单',
        content: '发送账单后，会员在前台会看到未付款的账单<br/><br/>'+
        '<textarea id="txtSendBill" name="order_send_bill" rows="2" cols="20" class="input"></textarea><br>',
        okValue: '确定',
        width: 300,
        ok: function () {
          var remark = $("#txtSendBill", parent.document).val();
          if(remark == '') {
            top.dialog({
              title: '提示',
              content: '对不起，请输入备注',
              okValue: '确定',
              ok: function () {
              }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_sn": $("#spanOrderNo").text(),'remark' : remark,'periods':periods};
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/sendBill');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }

    function editPayType(payType) {
      var winDialog = top.dialog({
        title: '确定付款方式',
        content: '确认付款方式后，将产生账单<br/><br/>'+
        '<textarea id="txtPayType" name="order_pay_type_remark" rows="2" cols="20" class="input"></textarea><br>',
        okValue: '确定',
        width: 300,
        ok: function () {
          var remark = $("#txtPayType", parent.document).val();
          if(remark == '') {
            top.dialog({
              title: '提示',
              content: '对不起，请输入备注',
              okValue: '确定',
              ok: function () {
              }
            }).showModal(winDialog);
            return false;
          }
          var postData = { "order_no": $("#spanOrderNo").text(),'remark' : remark,'pay_type':payType};
          //发送AJAX请求
          sendAjaxUrl(winDialog, postData, "<?php echo U('Order/editPayType');?>");
          return false;
        },
        cancelValue: '取消',
        cancel: function () { }
      }).showModal();
    }


    function editDate(element,goodsId,type){
      var orderSn = "<?php echo ($list["order_sn"]); ?>";
      var onpicked =function(){
        var date = $dp.cal.getNewDateStr();
        var postData = {"order_no": orderSn, "date": date,'type': type,'edit_type': 'edit_date','goods_id': goodsId};
        sendAjaxUrl('',postData,"<?php echo U('Order/updateOrder');?>");
      };
      WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',el:element,onpicked:onpicked})
    }


    //=================================工具类的JS函数====================================
    //检查是否货币格式
    function checkIsMoney(val) {
      var regTxt = /^\d+(\.\d{2})?$/;
      if (!regTxt.test(val)) {
        return false;
      }
      return true;
    }
    //发送AJAX请求
    function sendAjaxUrl(winObj, postData, sendUrl) {
      $.ajax({
        type: "post",
        url: sendUrl,
        data: postData,
        dataType: "json",
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          top.dialog({
            title: '提示',
            content: '尝试发送失败，错误信息：' + errorThrown,
            okValue: '确定',
            ok: function () { }
          }).showModal(winObj);
        },
        success: function (data) {
          if (data.status == 1) {
            winObj.close().remove();
            var d = dialog({ content: data.msg }).show();
            setTimeout(function () {
              d.close().remove();
              location.reload(); //刷新页面
            }, 2000);
          } else {
            top.dialog({
              title: '提示',
              content: '错误提示：' + data.msg,
              okValue: '确定',
              ok: function () { }
            }).showModal(winObj);
          }
        }
      });
    }
    $(function () {
      var status = <?php echo ($list["status"]); ?>;
      $('.order-flow div').each(function (index,data) {
        if(status >= index){
          switch (index) {
            case 0:
              $(data).find('.time').removeClass('hide');
              break;
            case 1:
              $(data).find('.name').text('已付款');
              $(data).find('.time').removeClass('hide');
              $(data).removeClass('order-flow-wait').addClass('order-flow-arrive');
              break;
            case 2:
              $(data).removeClass('order-flow-wait').addClass('order-flow-arrive');
              break;
            case 3:
              $(data).find('.name').text('已完成');
              $(data).removeClass('order-flow-right-wait').addClass('order-flow-right-arrive');
              $(data).find('.time').removeClass('hide');
              break;
            default:
              break;
          }
        }
      })
    })
  </script>
</head>

<body class="mainbody">
<!--<form method="post" action="" id="form1">-->
  <!--导航栏-->
  <div class="location">
    <a href="<?php echo U('Admin/Order/index');?>" class="back"><i class="iconfont icon-up"></i><span>返回列表</span></a>
    <a href="<?php echo U('Admin/Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
    <i class="arrow iconfont icon-arrow-right"></i>
    <span>订单详情</span>
  </div>
  <div class="line10"></div>
  <!--/导航栏-->

  <!--内容-->
  <div id="floatHead" class="content-tab-wrap">
    <div class="content-tab">
      <div class="content-tab-ul-wrap">
        <ul>
          <li><a class="selected" href="javascript:;">订单详细信息</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="tab-content">
    <?php if(($info["status"]) <= "4"): ?><dl>
        <dd style="margin-left:50px;text-align:center;">
          <div class="order-flow" style="width:560px">

            <div class="item-box left arrive">
              <div class="line"></div>
              <div class="icon"><i class="iconfont icon-confirm-full"></i></div>
              <div class="txt">
                <b>订单已生成</b>
                <p><?php echo (date('Y-m-d H:i:s',$list["add_time"])); ?></p>
              </div>
            </div>

            <div class="item-box <?php if($list['status'] > 0): ?>arrive<?php endif; ?>">
              <div class="line"></div>
              <div class="icon"><i class="iconfont icon-confirm-full"></i></div>
              <div class="txt">
                <?php if($list['status'] == 0): ?><b>待付款</b><?php else: ?><b>已付款</b><p><?php echo (date('Y-m-d H:i:s',$list["pay_time"])); ?></p><?php endif; ?>
              </div>
            </div>

            <div class="item-box <?php if($list['status'] > 1): ?>arrive<?php endif; ?>">
              <div class="line"></div>
              <div class="icon"><i class="iconfont icon-confirm-full"></i></div>
              <div class="txt">
                <?php if($list['status'] > 1): ?><b>已发货</b><p><?php echo (date('Y-m-d H:i:s',$list["ship_time"])); ?></p><?php else: ?><b>等待发货</b><?php endif; ?>

              </div>
            </div>

            <div class="item-box right <?php if($list['status'] == 3 or $list['status'] == 6): ?>arrive<?php endif; ?>">
              <div class="line"></div>
              <div class="icon"><i class="iconfont icon-confirm-full"></i></div>
              <div class="txt">
                <?php if($list['status'] == 3 or $list['status'] == 6): ?><b>已完成</b><p><?php echo (date('Y-m-d H:i:s',$list["complete_time"])); ?></p><?php else: ?><b>等待完成</b><?php endif; ?>
              </div>
            </div>
          </div>
        </dd>
      </dl>
        <!--<dl>-->
          <!--<dd style="margin-left:50px;text-align:center;">-->
            <!--<div class="order-flow" style="width:560px">-->

              <!--<div class="order-flow-left">-->
                <!--<a class="order-flow-input"></a>-->
                <!--<span><p class="name">订单生成</p><p class="time hide"><?php echo (date('Y-m-d H:i:s',$list["add_time"])); ?></p></span>-->
              <!--</div>-->

              <!--<div class="order-flow-wait" >-->
                <!--<a class="order-flow-input"></a>-->
                <!--<?php if($list['status'] > 0 and $list['status'] < 4): ?>-->
                <!--<span><p class="name">已付款</p><p class="time hide"><?php echo (date('Y-m-d H:i:s',$list["pay_time"])); ?></p></span>-->
                <!--<?php else: ?>-->
                <!--<span><p class="name">等待付款</p><p class="time hide">................</p></span>-->
                <!--<?php endif; ?>-->
              <!--</div>-->

              <!--<div class="order-flow-right-wait">-->
                <!--<a class="order-flow-input"></a>-->
                <!--<?php if($list['status'] == 3 ): ?>-->
                <!--<span><p class="name">已完成</p><p class="time hide"><?php echo (date('Y-m-d H:i:s',$list["complete_time"])); ?></p></span>-->
                <!--<?php else: ?>-->
                <!--<span><p class="name">等待完成</p><p class="time hide">................</p></span>-->
                <!--<?php endif; ?>-->
              <!--</div>-->

            <!--</div>-->
          <!--</dd>-->
        <!--</dl>-->
    <?php else: ?>
      <dl>
        <dd style="margin-left:50px;text-align:center;">
          订单状态：
          <?php if(is_array($status_list)): $i = 0; $__LIST__ = $status_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if($list['status'] == $key): echo ($item); endif; endforeach; endif; else: echo "" ;endif; ?>
          <br/>
          <?php switch($info["status"]): case "4": ?>取消时间：<?php echo (date('Y-m-d H:i:s',$list["cancel_time"])); break;?>
            <?php case "6": ?>作废时间：<?php echo (date('Y-m-d H:i:s',$list["invalid_time"])); break; endswitch;?>
        </dd>
      </dl><?php endif; ?>

    <dl>
      <dt>订单号</dt>
      <dd><span id="spanOrderNo"><?php echo ($list["order_sn"]); ?></span></dd>
    </dl>

    <dl>
      <dt>商品列表</dt>
      <dd>
        <div class="table-container">
          <table border="0" cellspacing="0" cellpadding="0" class="border-table" width="100%">
            <thead>
            <tr>
              <th style="text-align:left;">商品信息</th>
              <th width="33%">销售价</th>
              <th width="33%">数量</th>
              <th width="12%">备注</th>

            </tr>
            </thead>
            <tbody>
                <?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><tr class="td_c">
              <td style="text-align:left;white-space:inherit;word-break:break-all;line-height:20px;">
                <!--图片在这里-->
                <div style="display:table-cell;width: 50px; height: 50px; float:left;margin-right: 10px;"><img src="/somegood/Public/uploads<?php echo ($vs["goods_thumb"]); ?>_c200x200" alt="" style="width: 50px; height: 50px;"></div>
                <!--图片在这里-->
                <span><?php echo ($vs["goods_title"]); ?></span>
              </td>
              <td><?php echo ($vs["want_price"]); ?></td>
              <td><?php echo ($vs["counts"]); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table>
        </div>
      </dd>
    </dl>
    <dl>
      <dt>收货信息</dt>
      <dd>
        <div class="table-container">
          <table border="0" cellspacing="0" cellpadding="0" class="border-table" width="100%">
            <tr>
              <th width="20%">收件人</th>
              <td>
                <div class="position">
                  <span id="spanAcceptName"><?php echo ($list["consignee"]); ?></span>
                  <!-- <input name="btnEditAcceptInfo" type="button" id="btnEditAcceptInfo" class="ibtn" value="修改" /> -->
                </div>
              </td>
            </tr>
            <tr>
              <th>收货地址</th>
              <td><span id="spanArea"><?php echo ($list['dizhi']); ?></span> </td>
            </tr>
            <tr>
              <th>手机</th>
              <td><span id="spanMobile"><?php echo ($list["mobile"]); ?></span></td>
            </tr>
          </table>
        </div>
      </dd>
    </dl>
    <!--<dl id="dlUserInfo"> 改成了上面的收货信息-->
      <!--<dt>会员信息</dt>-->
      <!--<dd>-->
        <!--<div class="table-container">-->
          <!--<table border="0" cellspacing="0" cellpadding="0" class="border-table" width="100%">-->
            <!--<tr>-->
              <!--<th width="20%">收货人：</th>-->
              <!--<td><span id="lbUserName"><?php echo ($list["consignee"]); ?></span></td>-->
            <!--</tr>-->
            <!--<tr>-->
              <!--<th>收货地址：</th>-->
              <!--<td>-->
                <!--<div class="position">-->
                    <!--<span><?php echo ($list['dizhi']); ?></span>-->
                    <!--&lt;!&ndash; <span id="txtOrderEditOriginalAddress"><input name="btnEditOriginalAddress" type="button" id="btnEditOriginalAddress" class="ibtn" value="修改" /></span> &ndash;&gt;-->
              <!--</div>-->
            <!--<tr>-->
              <!--<th>电话：</th>-->
              <!--<td><span id="lbUserPhone"><?php echo ($list["mobile"]); ?></span></td>-->
            <!--</tr>-->

          <!--</table>-->
        <!--</div>-->
      <!--</dd>-->
    <!--</dl>-->
    <dl>
      <dt>物流信息</dt>
      <dd>
        <div class="table-container">
          <table border="0" cellspacing="0" cellpadding="0" class="border-table" width="100%">
            <tr>
              <th width="20%">物流单号:</th>
              <td>
                <div class="position">
                  <span><?php echo ($list["logistics_sn"]); ?></span>
                  <?php if($list['is_son'] != 0): ?><input name="btnEditLogisticsSn" type="button" id="btnEditLogisticsSn" class="ibtn" value="修改" />
                  <?php else: ?>
                  <span style="float:right;">不能修改物流信息</span><?php endif; ?>
                </div>
              </td>
            </tr>
            <tr>
              <th width="20%">物流类型</th>
              <td>
                <div class="position">
                  <span ><?php echo ($list["name"]); ?></span>
                  <?php if($list['is_son'] != 0): ?><input name="btnEditLogisticsType" type="button" id="btnEditLogisticsType" class="ibtn" value="修改" />
                  <?php else: ?>
                  <span style="float:right;">不能修改物流信息</span><?php endif; ?>
                </div>
              </td>
            </tr>
            <tr>
              <th width="20%">订单备注</th>
              <td>
                <div class="position">
                  <span >物流类型修改:  0:德邦物流    4:国通快递  35:申通快递  36:顺丰快递  41:天天   54:圆通  64:中通</span>
                </div>
              </td>
            </tr>
          </table>
        </dd>
    </dl>

    <dl>

    <dl>
      <dt>订单相关信息</dt>
      <dd>
        <div class="table-container">
          <table border="0" cellspacing="0" cellpadding="0" class="border-table" width="100%">
            <tr>
              <th width="20%">订单商品总价(原价)</th>
              <td>
                <div class="position">
                  <span id="txtOriginalPrice"><?php if(($info["type"]) == "1"): echo ($list['original_price']*12); else: echo ($list["original_price"]); endif; ?></span> 元
                </div>
              </td>
            </tr>
            <tr>
              <th width="20%">订单金额</th>
              <td>
                <div class="position">
                  <span id="tureMoney"><?php echo ($list["money"]); ?></span> 元
                  <?php if($list['status'] == 0 and $list['is_son'] == 2): ?><input name="btnEditOriginalPrice" type="button" id="btnEditOriginalPrice" class="ibtn" value="修改" />
                  <?php else: ?>
                  <span style="float:right;">该订单不能修改价格,请查看订单状态和是否为拆单</span><?php endif; ?>
                </div>
              </td>
            </tr>
            <tr>
              <th width="20%">订单类型</th>
              <td>
                <div class="position">
                  <?php if(($list['is_son']) == "2"): ?><span>未拆单主单</span><?php endif; ?>
                  <?php if(($list['is_son']) == "1"): ?><span>子单</span><?php endif; ?>
                  <?php if(($list['is_son']) == "0"): ?><span>已拆单主单</span><?php endif; ?>
                  <span></span>
                </div>
              </td>
            </tr>
            <tr>
              <th width="20%">订单状态</th>
              <td>
                <div class="position">
                  <?php if(($list['status']) == "0"): ?><span>待付款</span><?php endif; ?>
                  <?php if(($list['status']) == "1"): ?><span>待发货</span><?php endif; ?>
                  <?php if(($list['status']) == "2"): ?><span>已发货</span><?php endif; ?>
                  <?php if(($list['status']) == "3"): ?><span>已完成</span><?php endif; ?>
                  <?php if(($list['status']) == "4"): ?><span>已删除</span><?php endif; ?>
                  <?php if(($list['status']) == "5"): ?><span>已取消</span><?php endif; ?>
                  <?php if(($list['status']) == "99"): ?><span>已退货</span><?php endif; ?>
                  <!-- <span><?php if(!empty($info["discount_info"])): else: ?>子订单<?php endif; ?></span> -->
                  <span></span>
                </div>
              </td>
            </tr>
            <tr>
              <th width="20%">优惠劵抵扣</th>
              <td>
                <div class="position">
                  <span><?php if($list['type_money'] == NULL): ?>0<?php else: echo ($list["type_money"]); endif; ?></span>
                </div>
              </td>
            </tr>
            <tr>
              <th width="20%">分享币抵扣</th>
              <td>
                <div class="position">
                  <span><?php echo ($list["share_bill"]); ?></span>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </dd>
    </dl>

<!--<dl>-->
  <!--<dt>商品信息</dt>已经放到最上面了-->
  <!--<dd>-->
    <!--<div class="table-container">-->
      <!--<table border="0" cellspacing="0" cellpadding="0" class="border-table" width="100%">-->
        <!--<tr>-->
          <!--<th width="20%"></th>-->
          <!--<td>商品名</td>-->
          <!--<td>数量</td>-->
          <!--<td>价格</td>-->
          <!--<td>备注</td>-->
        <!--</tr>-->
        <!--<?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?>-->
        <!--<tr>-->
          <!--<th width="20%">商品</th>-->
          <!--<td>-->
            <!--<div class="position">-->
              <!--<span><?php echo ($vs["goods_title"]); ?></span>-->
            <!--</div>-->
          <!--</td>-->
          <!--<td>-->
            <!--<div class="position">-->
              <!--<span><?php echo ($vs["counts"]); ?></span>件-->
            <!--</div>-->
          <!--</td>-->
          <!--<td>-->
            <!--<div class="position">-->
              <!--<span><?php echo ($vs["want_price"]); ?></span>元-->
            <!--</div>-->
          <!--</td>-->
          <!--<td>-->
            <!--<div class="position">-->
              <!--<span>暂无</span>-->
            <!--</div>-->
          <!--</td>-->
        <!--</tr>-->
      <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
      <!--</table>-->
    <!--</div>-->
  <!--</dd>-->
<!--</dl>-->
    <dl>
      <dt>支付</dt>
      <dd>
        <div class="table-container">
          <table border="0" cellspacing="0" cellpadding="0" class="border-table" width="100%">
            <tr>
              <th width="20%">是否开具发票</th>
              <td>暂无</td>
            </tr>
            <tr>
              <th width="20%">支付类型</th>
              <td>
                <?php switch($list["delivery_way"]): case "1": ?>微信<?php break;?>
                  <?php case "0": ?>到店购买<?php break; endswitch;?>
              </td>
            </tr>

            <tr>
              <th valign="top">订单备注</th>
              <td>
                <div class="position">
                  <div id="divRemark"><?php echo ($list["remark"]); ?></div>
                  <!-- <input name="btnEditRemark" type="button" id="btnEditRemark" class="ibtn" value="修改" /> -->
                </div>
              </td>
            </tr>
          </table>
        </div>
      </dd>
    </dl>
  </div>
  <!--/内容-->


  <!--工具栏-->
  <div class="page-footer">
    <div class="btn-wrap">
      <?php switch($info["status"]): case "1": ?><input name="btnComplete" type="button" id="btnComplete" value="订单完成" class="btn" /><?php break;?>
        <?php case "3": ?><input name="btnInvalid" type="button" id="btnInvalid" value="作废订单" class="btn" /><?php break;?>
        <?php default: endswitch;?>

      <?php if(($info["status"]) < "3"): ?><input name="btnCancel" type="button" id="btnCancel" value="取消订单" class="btn green" /><?php endif; ?>

      <!--<input id="btnPrint" type="button" value="打印订单" class="btn violet" />-->
      <input id="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back();" />
    </div>
  </div>
  <div class="logistics_list">
    <select class="testSelect"><?php if(is_array($logistics)): $i = 0; $__LIST__ = $logistics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['id']); ?>"><?php echo ($vo['name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>
  </div>
  <!--/工具栏-->
<div id="pop-image"></div>
<style type="text/css">
  .pop-image{ position: absolute; border-radius:8px; padding:5px; background:#f6f6f6; border:#c8c8c8 2px solid; box-shadow:3px 3px 5px #333; overflow: hidden;}
</style>
<!--</form>-->
</body>
<script type="text/javascript">
  $(function () {
    $('.preview').hover(function (e) {
      var img_url = $(this).attr('img_url');
      var img =$("<img style='width:200px;' "+" src="+img_url+">");
      $('#pop-image').html(img);
      $('#pop-image').addClass('pop-image');
      $('#pop-image').css({"left":e.pageX+"px","top":e.pageY+"px"});
    },function(){
      $('#pop-image').html('');
      $('#pop-image').removeClass('pop-image');
    });
  });

  function updateData(edit_type){
    var logistics_list=$(".logistics_list").html();
    var winDialog = top.dialog({
      title: '物流地址',
      content: logistics_list,
      okValue: '确定',
      ok: function () {
        var remark = $('.testSelect option:selected').eq(1).val();
        if (remark == "") {
          top.dialog({
            title: '提示',
            content: '对不起，请输入订单备注内容！',
            okValue: '确定',
            ok: function () { }
          }).showModal(winDialog);
          return false;
        }
        var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": edit_type, "remark": remark };
        //发送AJAX请求
        sendAjaxUrl(winDialog, postData, "<?php echo U('Order/updateOrder');?>");
        return false;
      },
      cancelValue: '取消',
      cancel: function () { }
    }).showModal();
  }
</script>
</html>