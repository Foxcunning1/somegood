<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>文章管理</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript">
        //菜单获取
        var ajax_url="<?php echo U('Index/ajax');?>";
        //站点列表获取
        var ajax_site_url = "<?php echo U('Site/sajax');?>";


        function doPostBack(objId) {
            if(objId == 'btnDelete') {
                $('#goodsForm').submit();
            }
            if(objId == 'btnSearch'){
                $('#searchForm').submit();
            }
            if(objId == 'columnId'){
                $('#searchForm').submit();
            }
        }
    </script>
</head>
<body class="mainbody">

    <!--导航栏-->
    <div class="location">
        <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <span>文章列表</span>
    </div>
    <!--/导航栏-->

    <!--工具栏-->
    <div id="floatHead" class="toolbar-wrap">
        <div class="toolbar">
        	<div class="box-wrap">
                <a class="menu-btn"></a>
                <form action="<?php echo U('Article/index');?>" method="post" id="searchForm">
                    <div class="l-list">
                        <ul class="icon-list">
                            <li><a href="<?php echo U('Article/add');?>"><i class="iconfont icon-close"></i><span>新增</span></a></li>
                            <li><a href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
                            <li><a onclick="ExePostBack('btnDelete','本操作会删除文章，是否继续？')" id="btnDelete" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
	                    </ul>
                        <div class="menu-list">

                            <div class="rule-single-select">
                                <select name="columnId" onchange="doPostBack('columnId')" id="columnId">
                                    <option value="">所有栏目</option>
                                    <?php if(is_array($column_list)): $i = 0; $__LIST__ = $column_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($item['id'] == $columnId): ?>selected="selected"<?php endif; ?> value="<?php echo ($item["id"]); ?>"><?php if(($item["count"]) != "1"): echo (fill_up_string($item['count'],"&nbsp;&nbsp;")); ?>├<?php endif; ?> <?php echo ($item["c_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
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
                <th width="6%">选择</th>
                <th align="left" width="6%">发布人</th>
                <th align="left" width="14%">标题</th>
                <th align="left" width="6%">栏目</th>
                <th align="left" width="6%">发布时间</th>
                <th align="left" width="6%">更新时间</th>
                <th align="left" width="3%">排序</th>
                <th width="6%">操作</th>
            </tr>
            <form method="post" action="<?php echo U('Article/del');?>" id="goodsForm">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                        <td align="center">
                            <span class="checkall" style="vertical-align:middle;">
                                <input type="checkbox" name="ids[]" value="<?php echo ($item["aid"]); ?>" />
                            </span>
                        </td>
                        <td><?php echo ($item["admin_name"]); ?></td>
                        <td><?php echo ($item["title"]); ?></td>
                        <td><?php echo ($item["column_name"]); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$item["add_time"])); ?></td>
                        <td><?php if($item["update_time"] == null): ?>未更新<?php else: echo (date('Y-m-d H:i:s',$item["update_time"])); endif; ?></td>
                        <td onclick="editField(this,'article','aid','sort_num','<?php echo ($item["aid"]); ?>',pajax_url,0,'<?php echo ($item["sort_num"]); ?>',0)"><?php echo ($item["sort_num"]); ?></td>
                        <td align="center">
                            <a href="<?php echo U('Article/add');?>?action=edit&id=<?php echo ($item["aid"]); ?>">修改</a> |
                            <a href="<?php echo U('Article/del');?>?action=edit&ids=<?php echo ($item["aid"]); ?>">删除</a>
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