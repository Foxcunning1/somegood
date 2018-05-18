<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>会员组管理</title>
  <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
  <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
</head>
<body class="mainbody">
<script type="text/javascript">
  var pajax_url="<?php echo U('Admin/Ajax/doubleClickModify');?>";
  function doPostBack(obj){
    if(obj=='btnDelete'){
      $("#listForm").submit();
    }else{
      $("#searchForm").submit();
    }
  }
</script>

<!--导航栏-->
<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
  <a href="<?php echo U('Admin/Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
  <i class="arrow iconfont icon-arrow-right"></i>
  <span>会员组别</span>
</div>
<!--/导航栏-->

<!--工具栏-->
<div id="floatHead" class="toolbar-wrap">
  <div class="toolbar">
    <div class="box-wrap">
      <a class="menu-btn"></a>
      <div class="l-list">
        <ul class="icon-list">
          <li><a class="add" href="<?php echo U('Admin/Users/groupAdd');?>"><i class="iconfont icon-close"></i><span>新增</span></a></li>
          <li><a class="all" href="javascript:;" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
          <li><a onclick="return ExePostBack('btnDelete');" id="btnDelete" class="del" href="javascript:doPostBack('btnDelete','')"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
        </ul>
      </div>
      <form name="searchForm" id="searchForm" method="get" action="<?php echo U('Admin/Users/groupList');?>">
      <div class="r-list">
        <input name="keywords" type="text" id="txtKeywords" class="keyword" value="<?php echo ($keywords); ?>" />
        <a id="lbtnSearch" class="btn-search" href="javascript:doPostBack('lbtnSearch')"><i class="iconfont icon-search"></i></a>
      </div>
      </form>
    </div>
  </div>
</div>
<!--/工具栏-->

<!--列表-->
<div class="table-container">
  <form method="post" action="<?php echo U('Admin/Users/groupDel');?>" id="listForm">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
      <tr>
        <th width="8%">选择</th>
        <th align="left">组别名称</th>
        <th width="8%">等级值</th>
        <th width="12%">升级积分</th>
        <th width="12%">初始积分</th>
        <th width="9%">购物折扣</th>
        <th width="8%">是否默认</th>
        <th width="8%">自动升级</th>
        <th width="6%">状态</th>
        <th width="10%">操作</th>
      </tr>
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td align="center">
          <span class="checkall"><input id="rptList_chkId_0" type="checkbox" name="ids[]" value="<?php echo ($vo["gid"]); ?>" /></span>
        </td>
        <td>
          <a href="<?php echo U('Admin/Users/usersList');?>?group=<?php echo ($vo["gid"]); ?>"><?php echo ($vo["group_name"]); ?></a>
        </td>
        <td align="center"><span onclick="editField(this,'users_group','gid','grade','<?php echo ($vo["gid"]); ?>',pajax_url,0,'<?php echo ($vo["grade"]); ?>',0)" class="focusSpan"><?php echo ($vo["grade"]); ?></span></td>
        <td align="center"><span onclick="editField(this,'users_group','gid','upgrade_point','<?php echo ($vo["gid"]); ?>',pajax_url,0,'<?php echo ($vo["upgrade_point"]); ?>',0)" class="focusSpan"><?php echo ($vo["upgrade_point"]); ?></span></td>
        <td align="center"><span onclick="editField(this,'users_group','gid','default_point','<?php echo ($vo["gid"]); ?>',pajax_url,0,'<?php echo ($vo["default_point"]); ?>',0)" class="focusSpan"><?php echo ($vo["default_point"]); ?></span></td>
        <td align="center"><span onclick="editField(this,'users_group','gid','discount','<?php echo ($vo["gid"]); ?>',pajax_url,0,'<?php echo ($vo["discount"]); ?>',0)" class="focusSpan"><?php echo ($vo["discount"]); ?></span>%</td>
        <td align="center"><span boolvalue="<?php echo ($vo["is_default"]); ?>" onclick="editFieldIs(this,'users_group','gid','is_default','<?php echo ($vo["gid"]); ?>',pajax_url,0,1)"><?php if(($vo["is_default"]) == "1"): ?>是<?php else: ?>否<?php endif; ?></span></td>
        <td align="center"><span boolvalue="<?php echo ($vo["is_upgrade"]); ?>" onclick="editFieldIs(this,'users_group','gid','is_upgrade','<?php echo ($vo["gid"]); ?>',pajax_url,0,1)"><?php if(($vo["is_upgrade"]) == "1"): ?>是<?php else: ?>否<?php endif; ?></span></td>
        <td align="center"><span boolvalue="<?php echo ($vo["is_lock"]); ?>" onclick="editFieldIs(this,'users_group','gid','is_lock','<?php echo ($vo["gid"]); ?>',pajax_url,1,1)"><?php if(($vo["is_lock"]) == "1"): ?>√<?php else: ?>×<?php endif; ?></span></td>
        <td align="center">
          <a href="<?php echo U('Admin/Users/groupAdd');?>?action=edit&id=<?php echo ($vo['gid']); ?>">修改</a>
        </td>
      </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
      </table>
  </form>
</div>
<!--/列表-->
</body>
</html>