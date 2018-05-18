<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <title>后台导航管理</title>
  <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
  <style type="text/css">
    .tree-list .col-1{ width:6%; text-align: center;}
    .tree-list .col-2{ width:13%; text-align: center;}
    .tree-list .col-3{ width:12%; text-align:center; }
    .tree-list .col-4{ width:15%; align:left;}
    .tree-list .col-5{ width:8%; text-align:center; }
    .tree-list .col-6{ width:8%; }
    .tree-list .col-7{ width:12%; text-align:center; }
  </style>
  <script type="text/javascript">       //单击修改的ajax地址
	 var pajax_url="<?php echo U('Admin/Ajax/doubleClickModify');?>";
	 function doPostBack(objId){
		 $('#navForm').submit();
	 }
  </script>
  <script type="text/javascript">
      $(function () {
          //初始化分类的结构
          initCategoryHtml('.tree-list', 1);
          //初始化分类的事件
          $('.tree-list').initCategoryTree(true);
      });
  </script>
</head>

<body class="mainbody">
<form name="listForm" id="navForm" method="post" action="<?php echo U('Setting/del');?>" >
<!--导航栏-->
<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
  <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
  <i class="arrow iconfont icon-arrow-right"></i>
  <span>后台导航管理</span>
</div>
<!--/导航栏-->

<!--工具栏-->
<div id="floatHead" class="toolbar-wrap">
  <div class="toolbar">
    <div class="box-wrap">
      <a class="menu-btn"></a>
      <div class="l-list">
        <ul class="icon-list">
          <li><a href="<?php echo U('Setting/add');?>"><i class="iconfont icon-close"></i><span>新增</span></a></li>
          <li><a href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
          <li><a href="javascript:void(0);" onclick="clearCache('Data/Admin/Nav','<?php echo U('Admin/Ajax/clearSystemCache');?>');"><i class="iconfont icon-build"></i><span>清除缓存</span></a></li>
          <li><a onclick="ExePostBack('btnDelete','本操作会删除本导航及下属子导航，是否继续？')" id="btnDelete" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!--/工具栏-->

<!--列表-->
  <div class="table-container">
    <div class="tree-list">
      <div class="thead">
        <div class="col col-1">选择</div>
        <div class="col col-4">调用名称</div>
        <div class="col col-4" >标题</div>
        <div class="col col-2">是否是系统菜单</div>
        <div class="col col-2">是否隐藏</div>
        <div class="col col-2">栏目ID(不为0的是栏目)</div>
        <div class="col col-2" align="center">排序</div>
        <div class="col col-3">操作</div>
      </div>
      <ul>
        <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li class="layer-<?php echo ($vo["count"]); ?>">
            <div class="tbody">
              <div class="col col-1 checkall">
                <input type="checkbox" name="ids[]" value="<?php echo ($vo["id"]); ?>" <?php if($vo["channel_id"] != 0 OR $vo["parent_id"] == 0): ?>disabled="disabled"<?php endif; ?>/>
              </div>
              <div class="col col-4" <?php if($vo["channel_id"] == 0): ?>onclick="editField(this,'system_nav','id','name','<?php echo ($vo["id"]); ?>',pajax_url,0,'<?php echo ($vo["name"]); ?>',0)" class="focusSpan"<?php endif; ?>><span ><?php echo ($vo["name"]); ?></span></div>
              <div class="col index col-4">
                <span onclick="editField(this,'system_nav','id','title','<?php echo ($vo["id"]); ?>',pajax_url,0,'<?php echo ($vo["title"]); ?>',0)" class="focusSpan"><?php echo ($vo["title"]); ?></span>
              </div>
            <div class="col col-2">
              <?php if(($vo["is_sys"] == 1)): ?>是<?php else: ?>否<?php endif; ?>
            </div>
            <div class="col col-2">
              <span boolvalue="<?php echo ($vo["is_lock"]); ?>" onclick="editFieldIs(this,'system_nav','id','is_lock','<?php echo ($vo["id"]); ?>',pajax_url,0,1)"><?php if(($vo["is_lock"] == 1)): ?>是<?php else: ?>否<?php endif; ?></span>
            </div>
              <div class="col col-2">
                <?php echo ($vo["channel_id"]); ?>
              </div>
              <div class="col col-2">
                <span onclick="editField(this,'system_nav','id','sort_num','<?php echo ($vo["id"]); ?>',pajax_url,0,'<?php echo ($vo["sort_num"]); ?>',0)" class="focusSpan"><?php echo ($vo["sort_num"]); ?></span>
              </div>
          <div class="col col-3">
            <a href="<?php echo U('Setting/add');?>?action=add&id=<?php echo ($vo["id"]); ?>">添加子级</a>
            &nbsp;|&nbsp;
            <a href="<?php echo U('Setting/add');?>?action=edit&id=<?php echo ($vo["id"]); ?>">修改</a>
          </div>
            </div>
          </li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
    </div>
  </div>
<!--/列表-->
</form>
</body>
</html>