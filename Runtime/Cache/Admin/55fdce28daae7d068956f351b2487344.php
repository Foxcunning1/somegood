<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>频道管理</title>
<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/js/artdialog/ui-dialog.css" />
<link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
<script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
<link href="/somegood/Public/statics/admin/css/ui-dialog.css" rel="stylesheet" type="text/css" />
<link href="/somegood/Public/statics/admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="/somegood/Public/statics/admin/css/pagination.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/jquery-2.1.4.min.js"></script>

<script type="text/javascript">
	function doPostBack(objId) {
		if(objId == 'btnDelete') {
			$('#advForm').submit();
		}
		if(objId == 'btnSearch'){
			$('#searchForm').submit();
		}
	}
</script>
</head>

<body class="mainbody">
<!--导航栏-->
<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
  <a href="<?php echo U('Admin/Index/index');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
  <i class="arrow iconfont icon-arrow-right"></i>
  <span>频道管理</span>
</div>
<!--/导航栏-->

<!--工具栏-->
<div id="floatHead" class="toolbar-wrap">
  <div class="toolbar">
    <div class="box-wrap">
      <a class="menu-btn"><i class="iconfont icon-more"></i></a>
      <div class="l-list">
        <ul class="icon-list">
          <li><a href="<?php echo U('Admin/GoodsType/goodsTypeEdit');?>"><span>新增</span></a></li>
          <li><a href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
          <li><a onclick="return ExePostBack('btnDelete');" id="btnDelete" class="del" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
        </ul>
      </div>

      <form action="<?php echo U('Admin/GoodsType/goodsTypeList');?>" method="post" id="searchForm">
      <div class="r-list" style="margin-right:10px;">
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
      <th width="6%">选择</th>
      <th align="left" width="3%">ID</th>
      <th align="left" width="6%">调用别名</th>
      <th align="left" width="6%">标题</th>
      <th align="left" width="3%">是否隐藏</th>
      <th align="left" width="3%">排序</th>
      <th align="center" width="12%">操作</th>
    </tr>

  	<form action="<?php echo U('Admin/GoodsType/del');?>" id="advForm" method="post">
			<input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>" />
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
      <td align="center">
        <span class="checkall" style="vertical-align:middle;">
            <input type="checkbox" name="ids[]" value="<?php echo ($item["id"]); ?>" />
        </span>
      </td>
      <td align="left"><?php echo ($item["id"]); ?></td>
      <td><?php echo ($item["name"]); ?></td>
      <td><?php echo ($item["title"]); ?></td>
      <td align="left"><span boolvalue="<?php echo ($item["is_hidden"]); ?>" onclick="editFieldIs(this,'goods_type','id','is_hidden','<?php echo ($item["id"]); ?>',pajax_url,0,1)"><?php if(($item["is_hidden"] == 1)): ?>是<?php else: ?>否<?php endif; ?></span></td>
      <td><?php echo ($item["sort_num"]); ?></td>
      <td align="center"><a href="<?php echo U('Admin/GoodsType/goodsTypeEdit',array('id' => $item['id']));?>">修改</a></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </form>

  </table>
</div>
<!--/列表-->
<!--内容底部-->
<div class="line20"></div>
<div class="pagelist">
<!--  <div class="l-btns">
    <span>显示</span><input name="txtPageNum" type="text" value="10" onchange="javascript:setTimeout(&#39;__doPostBack(\&#39;txtPageNum\&#39;,\&#39;\&#39;)&#39;, 0)" onkeypress="if (WebForm_TextBoxKeyHandler(event) == false) return false;" id="txtPageNum" class="pagenum" onkeydown="return checkNumber(event);" /><span>条/页</span>
  </div>-->
  <div id="PageContent" class="default"><?php echo ($page); ?></div>
</div>
<!--/内容底部-->
</form>
</body>
</html>