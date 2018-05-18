<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <title>系统参数设置</title>
  <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
  <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
  <script type="text/javascript">
    var ajax_site_url = "<?php echo U('Site/sajax');?>";
    $(function () {
      //初始化表单验证
      $("#form1").initValidform();
    });
  </script>
</head>

<body class="mainbody">
<form method="post" action="<?php echo U('Order/config');?>" id="form1">
  <!--导航栏-->
  <div class="location">
    <a href="javascript:history.back(-1);" class="back"><i></i><span>返回上一页</span></a>
    <a href="<?php echo U('Index/center');?>" class="home"><i></i><span>首页</span></a>
    <i class="arrow"></i>
    <span>系统参数设置</span>
  </div>
  <div class="line10"></div>
  <!--/导航栏-->

  <!--内容-->
  <div id="floatHead" class="content-tab-wrap">
    <div class="content-tab">
      <div class="content-tab-ul-wrap">
        <ul>
          <li><a class="selected" href="javascript:;">基本参数设置</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!--订单参数设置-->
  <div class="tab-content">
    <dl>
      <dt>发票税费类型</dt>
      <dd>
        <div class="rule-multi-radio">
          <span id="tax-type">
            <input id="tax-type-0" type="radio" name="data[order_type]" value="1" <?php if($status["order_type"] == 1): ?>checked="checked"<?php endif; ?> /><label for="tax-type-0">百分比</label>
            <input id="tax-type-1" type="radio" name="data[order_type]" value="2" <?php if($status["order_type"] == 2): ?>checked="checked"<?php endif; ?> /><label for="tax-type-1">固定金额</label>
          </span>
        </div>
        <span class="Validform_checktip">*百分比的计算公式：商品总金额+(商品总金额*百分比)+配送+支付费用=订单总金额</span>
      </dd>
    </dl>
    <dl>
      <dt>发票税金费用</dt>
      <dd>
        <input name="data[tax_amount]" type="text" value="<?php echo ($status["tax_amount"]); ?>" id="amount" class="input small" datatype="/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/" sucmsg=" " />
        <span class="Validform_checktip">*注意：百分比取值范围：0-100，固定金额单位为“元”</span>
      </dd>
    </dl>
    <dl>
      <dt>订单确认通知</dt>
      <dd>
        <div class="rule-multi-radio">
          <span id="confirm-msg">
            <?php if(is_array($inform_list)): $i = 0; $__LIST__ = $inform_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><input id="confirm-msg-<?php echo ($key); ?>" type="radio" name="data[confirm_msg]" value="<?php echo ($key); ?>" <?php if($status["confirm_msg"] == $key): ?>checked="checked"<?php endif; ?> /><label for="confirm-msg-<?php echo ($key); ?>"><?php echo ($item); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
          </span>
        </div>
      </dd>
    </dl>
    <dl>
      <dt>确认模板别名</dt>
      <dd>
        <input name="data[confirm_call_index]" type="text" value="<?php echo ($status["confirm_call_index"]); ?>" id="confirm-call-index" class="input normal" datatype="*" sucmsg=" " />
        <span class="Validform_checktip">*订单确认通知模板调用别名</span>
      </dd>
    </dl>
    <dl>
      <dt>订单发货通知</dt>
      <dd>
        <div class="rule-multi-radio">
          <span id="express-msg">
            <?php if(is_array($inform_list)): $i = 0; $__LIST__ = $inform_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><input id="express-msg-<?php echo ($key); ?>" type="radio" name="data[express_msg]" value="<?php echo ($key); ?>"<?php if($status["express_msg"] == $key): ?>checked="checked"<?php endif; ?>  /><label for="express-msg-<?php echo ($key); ?>"><?php echo ($item); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
          </span>
        </div>
      </dd>
    </dl>
    <dl>
      <dt>发货模板别名</dt>
      <dd>
        <input name="data[express_call_index]" type="text" value="<?php echo ($status["express_call_index"]); ?>" id="express-call-index" class="input normal" datatype="*" sucmsg=" " />
        <span class="Validform_checktip">*订单发货通知模板调用别名</span>
      </dd>
    </dl>
    <dl>
      <dt>订单完成通知</dt>
      <dd>
        <div class="rule-multi-radio">
          <span id="complete-msg">
            <?php if(is_array($inform_list)): $i = 0; $__LIST__ = $inform_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><input id="complete-msg-<?php echo ($key); ?>" type="radio" name="data[complete_msg]" value="<?php echo ($key); ?>" <?php if($status["complete_msg"] == $key): ?>checked="checked"<?php endif; ?>  /><label for="complete-msg-<?php echo ($key); ?>"><?php echo ($item); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
          </span>
        </div>
      </dd>
    </dl>
    <dl>
      <dt>完成模板别名</dt>
      <dd>
        <input name="data[complete_call_index]" type="text" value="<?php echo ($status["complete_call_index"]); ?>" id="complete-call-index" class="input normal" datatype="*" sucmsg=" " />
        <span class="Validform_checktip">*订单完成通知模板调用别名</span>
      </dd>
    </dl>
    <dl>
      <dt>服务金币抵用比例</dt>
      <dd>
        <input name="data[offset_rate]" type="text" value="<?php echo ($status["offset_rate"]); ?>" class="input small" datatype="*" sucmsg=" " />%
        <span class="Validform_checktip">*服务金币的使用比例</span>
      </dd>
    </dl>
    <dl>
      <dt>金币兑换人民币比例</dt>
      <dd>
        1元&nbsp;=&nbsp;<input name="data[exchange_rate]" type="text" value="<?php echo ($status["exchange_rate"]); ?>" class="input small" datatype="*" sucmsg=" " />金币
        <span class="Validform_checktip">*服务金币与人民币兑换比例</span>
      </dd>
    </dl>
  </div>
  <!--/订单参数设置-->

  <!--工具栏-->
  <div class="page-footer">
    <div class="btn-wrap">
      <input type="submit" name="btnSubmit" value="提交保存" id="btnSubmit" class="btn" />
      <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back();" />
    </div>
  </div>
  <!--/工具栏-->
</form>
</body>
</html>