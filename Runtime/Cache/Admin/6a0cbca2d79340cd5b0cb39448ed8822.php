<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>广告位管理</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript">
        function doPostBack(objId) {
            if(objId == 'btnDelete') $('#advPositionForm').submit();
            if(objId == 'btnSearch') $('#searchForm').submit();
            if(objId == 'Status') $('#statusForm').submit();
        }
        var pajax_url = "<?php echo U('Admin/Ajax/doubleClickModify');?>";
    </script>
</head>

<body class="mainbody">
    <!--导航栏-->
    <div class="location">
        <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <span>广告位列表</span>
    </div>
    <!--/导航栏-->

    <!--工具栏-->
    <div id="floatHead" class="toolbar-wrap">
        <div class="toolbar">
            <div class="box-wrap">
                <a class="menu-btn"></a>
                <div class="l-list">
                    <ul class="icon-list">
                        <li><a class="add" href="<?php echo U('Admin/Adv/advPositionAdd');?>"><i class="iconfont icon-close"></i><span>新增</span></a></li>
                        <li><a class="all" href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
                        <li><a onclick="return ExePostBack('btnDelete','删除广告位后，广告位上的广告也会删除，是否继续？');" id="btnDelete" class="del" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
                    </ul>
                    <form action="<?php echo U('Admin/Adv/advPositionList');?>" method="get" id="statusForm">
                        <div class="menu-list">
                            <div class="rule-single-select">
                                <select name="status" onchange="doPostBack('Status');" id="Status">
                                    <option value="4" <?php if($status == 4): ?>selected="selected"<?php endif; ?> >所有状态</option>
                                    <option value="1" <?php if($status == 1): ?>selected="selected"<?php endif; ?> >已过期</option>
                                    <option value="2" <?php if($status == 2): ?>selected="selected"<?php endif; ?> >正在进行</option>
                                    <option value="3" <?php if($status == 3): ?>selected="selected"<?php endif; ?> >还未开始</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <form action="<?php echo U('Admin/Adv/advPositionList');?>" method="get" id="searchForm">
                    <div class="r-list">
                        <input name="keywords" type="text" id="txtKeywords" class="keyword" value="<?php echo ($keywords); ?>" />
                        <a id="btnSearch" class="btn-search" href="javascript:void(0);" onclick="doPostBack('btnSearch');"><i class="iconfont icon-search"></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/工具栏-->

    <!--列表-->
    <div class="table-container">
        <!--文字列表-->
        <form action="<?php echo U('Admin/Adv/advPositionDel');?>" id="advPositionForm" method="post">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
            <tr>
                <th width="6%">选择</th>
                <th align="center">ID</th>
                <th align="left">广告位名称</th>
                <th align="left" width="12%">广告位宽度和高度</th>
                <th align="center" width="8%">动画类型</th>
                <th align="center" width="20%">  开始时间--结束时间  </th>
                <th align="left" width="65">排序</th>
                <th width="10%">操作</th>
            </tr>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                        <td align="center">
                            <span class="checkall" style="vertical-align:middle;">
                                <input type="checkbox" name="ids[]" value="<?php echo ($item["id"]); ?>" />
                            </span>
                        </td>
                        <td align="center">
                            <?php echo ($item["id"]); ?>
                        </td>
                        <td><span onclick="editField(this,'adv_position','id','title','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["title"]); ?>',0)"><?php echo ($item["title"]); ?></span></td></td>
                        <td><span onclick="editField(this,'adv_position','id','width','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["width"]); ?>',0)"><b><?php echo ($item["width"]); ?></b></span>*<span onclick="editField(this,'adv_position','id','height','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["height"]); ?>',0)"><b><?php echo ($item["height"]); ?></b></span>px</td>
                        <td align="center"><?php echo ($item["animation"]); ?></td>
                        <td align="center"><?php echo (date('Y-m-d H:i:s',$item["begin_time"])); ?>--<?php echo (date('Y-m-d H:i:s',$item["end_time"])); ?></td>
                        <td><span onclick="editField(this,'adv_position','id','sort_num','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["sort_num"]); ?>',0)"><?php echo ($item["sort_num"]); ?></span></td>
                        <td align="center">
                            <a href="<?php echo U('Admin/Adv/advAdd',array('posid' => $item['id']));?>">添加广告</a> |
                            <a href="<?php echo U('Admin/Adv/advPositionAdd',array('action' => 'edit' , 'id' => $item['id']));?>">修改</a> |
                            <a href="<?php echo U('Admin/Adv/list',array('posid' => $item['id']));?>">广告管理</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
        </form>
        <!--/文字列表-->
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