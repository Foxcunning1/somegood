<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <title>优惠券类型管理</title>
  <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
  <script type="text/javascript">
      //菜单获取
      var ajax_url="<?php echo U('Index/ajax');?>";
      //站点列表获取
      var ajax_site_url = "<?php echo U('Site/sajax');?>";


      function doPostBack(objId) {
          if(objId == 'btnDelete') {
              $('#couponForm').submit();
          }
          if(objId == 'btnSearch'){
              $('#searchForm').submit();
          }
      }
      function confirmAct()
      {
          if(confirm('此操作无法恢复，删除优惠券可能造成不必要的纠纷，确定要删除么？？？？'))
          {
              return true;
          }
          return false;
      }

  </script>
</head>
<body class="mainbody">

<!--导航栏-->
<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
  <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
  <i class="arrow iconfont icon-arrow-right"></i>
  <span><?php if($status == 'reduction'): ?>优惠券类型回收站<?php else: ?>优惠券类型列表<?php endif; ?></span>
</div>
<!--/导航栏-->

<!--工具栏-->
<div id="floatHead" class="toolbar-wrap">
  <div class="toolbar">
    <div class="box-wrap">
      <a class="menu-btn"></a>
      <form action="<?php echo U('Coupon/type');?>" method="post" id="searchForm">
        <div class="l-list">
          <ul class="icon-list">
            <li><a href="<?php echo U('Coupon/typeAdd');?>"><i class="iconfont icon-close"></i><span>新增</span></a></li>
            <li><a href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
            <li><a onclick="ExePostBack('btnDelete','删除优惠券类型可能会造成纠纷，确定删除？')" id="btnDelete" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
          <?php if($status == 'list'): ?><li><a href="<?php echo U('Coupon/type',array('status'=>'reduction'));?>"><i class="iconfont icon-build"></i><span>回收站</span></a></li>
          <?php else: ?>
            <li><a href="<?php echo U('Coupon/type',array('status'=>'list'));?>"><i class="iconfont icon-up"></i><span>返回列表</span></a></li><?php endif; ?>
          </ul>
          <div class="menu-list">

            <div class="rule-single-select">
              <select name="sendType" onchange="doPostBack('btnSearch')" id="sendType">
                <option value="" selected="selected">所有类型</option>
                <?php if(is_array($type_list)): $i = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($key == $sendType): ?>selected="selected"<?php endif; ?> value="<?php echo ($key); ?>"> <?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="r-list">
          <input name="keywords" type="text" id="txtKeywords" class="keyword" />
          <a id="btnSearch" class="btn-search" onclick="doPostBack('btnSearch');"><i class="iconfont icon-search"></i></a>
        </div>
      </form>
    </div>
  </div>
</div>
<!--/工具栏-->

<!--列表-->
<div class="table-container">

  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
    <tr>
      <th width="3%">选择</th>
      <th width="6%">优惠券类型名称</th>
      <th width="14%">发放类型</th>
      <th width="6%">优惠券金额</th>
      <th width="6%">订单下限</th>
      <th width="6%">发放数量</th>
      <th width="3%">使用数量</th>
      <th width="6%">操作</th>
    </tr>
    <form method="post" action="<?php echo U('Coupon/typeDel',array('action'=>'deleted'));?>" id="couponForm">
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
          <td align="center">
            <span class="checkall" style="vertical-align:middle;">
                <input type="checkbox" name="ids[]" value="<?php echo ($item["type_id"]); ?>" />
            </span>
          </td>
          <td align="center"><?php echo ($item["type_name"]); ?></td>
          <td align="center">
            <?php if(is_array($type_list)): $i = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($key == $item['send_type']): echo ($vo); endif; endforeach; endif; else: echo "" ;endif; ?>
          </td>
          <td align="center"><?php echo ($item["type_money"]); ?></td>
          <td align="center"><?php echo ($item["min_goods_amount"]); ?></td>
          <td align="center"><?php echo ($item["send_num"]); ?></td>
          <td align="center"><?php echo ($item["used_num"]); ?></td>
          <td align="center">
          <?php if($status == 'reduction'): ?><a href="<?php echo U('Coupon/typeDel');?>?action=reduction&ids=<?php echo ($item["type_id"]); ?>">还原</a>|
            <a href="<?php echo U('Coupon/typeDel');?>?action=deleted&ids=<?php echo ($item["type_id"]); ?>" onclick="return confirmAct();">彻底删除</a>
            <?php else: ?>
              <?php if($item['send_type'] != 3): ?><a href="<?php echo U('Coupon/typeSend');?>?action=send&type_id=<?php echo ($item["type_id"]); ?>&send_type=<?php echo ($item["send_type"]); ?>">发放</a> |<?php endif; ?>
              <a href="<?php echo U('Coupon/list');?>?coupon_type_id=<?php echo ($item["type_id"]); ?>&send_type=<?php echo ($item["send_type"]); ?>&status=list">查看</a> |
              <a href="<?php echo U('Coupon/typeAdd');?>?action=edit&type_id=<?php echo ($item["type_id"]); ?>">修改</a> |
              <a href="<?php echo U('Coupon/typeDel');?>?action=del&ids=<?php echo ($item["type_id"]); ?>" onclick="return confirmAct();">删除</a><?php endif; ?>
          </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </form>

  </table>

</div>
<!--/列表-->
<!--内容底部-->
<div class="line20"></div>
<div class="pagelist">
  <div id="PageContent" class="default"><?php echo ($page); ?></div>
</div>
<!--/内容底部-->

</body>
</html>