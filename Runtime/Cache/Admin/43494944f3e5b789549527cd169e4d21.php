<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>商品类别管理</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <style type="text/css">
        .tree-list .col-1{ width:3%; text-align: center;}
        .tree-list .col-2{ width:14%; text-align: center;}
        .tree-list .col-3{ width:14%; text-align: center; }
        .tree-list .col-4{ width:15%; text-align:left;}
    </style>
    <script type="text/javascript">
        //菜单获取
        var ajax_url="<?php echo U('Index/ajax');?>";
        //站点列表获取
        var ajax_site_url = "<?php echo U('Site/sajax');?>";
        //单击修改的ajax地址
        var pajax_url="<?php echo U('Ajax/doubleClickModify');?>";

        function doPostBack(objId) {
            if(objId == 'btnDelete') {
                $('#categoryForm').submit();
            }
            if(objId == 'btnSearch'){
                $('#searchForm').submit();
            }
        }
    </script>
    <script type="text/javascript">
        $(function () {
            //初始化表单验证
            $("#managerRoleForm").initValidform();
            //初始化分类的结构
            initCategoryHtml('.tree-list', 1);
            //初始化分类的事件
            $('.tree-list').initCategoryTree(true);
            //是否启用权限
            if ($("#ddlRoleType").find("option:selected").attr("value") == 1) {
                $(".tree-list").find("input[type='checkbox']").prop("disabled", true);
            }
            $("#ddlRoleType").change(function () {
                if ($(this).find("option:selected").attr("value") == 1) {
                    $(".tree-list").find("input[type='checkbox']").prop("checked", false);
                    $(".tree-list").find("input[type='checkbox']").prop("disabled", true);
                } else {
                    $(".tree-list").find("input[type='checkbox']").prop("disabled", false);
                }
            });
            //全选
            $("input[name='checkAll']").click(function () {
                if ($(this).prop("checked") == true) {
                    $(this).parent().siblings(".col").find("input[type='checkbox']").prop("checked", true);
                } else {
                    $(this).parent().siblings(".col").find("input[type='checkbox']").prop("checked", false);
                }
            });
        });
    </script>
</head>
<body class="mainbody">

<!--导航栏-->
<div class="location">
    <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
    <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
    <i class="arrow iconfont icon-arrow-right"></i>
    <span>文章栏目</span>
</div>
<!--/导航栏-->

<!--工具栏-->
<div id="floatHead" class="toolbar-wrap">
    <div class="toolbar">
        <div class="box-wrap">
            <a class="menu-btn"></a>
            <div class="l-list">
                <ul class="icon-list">
                    <li><a href="<?php echo U('Article/columnAdd');?>"><i class="iconfont icon-close"></i><span>新增</span></a></li>
                    <li><a href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
                    <li><a onclick="ExePostBack('btnDelete','本操作会删除本类别及下属子栏目和该栏目下所以文章，是否继续？')" id="btnDelete" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
                </ul>
            </div>
            <div class="r-list">
                <form action="<?php echo U('Article/column');?>" method="post" id="searchForm">
                    <input name="keywords" type="text" id="txtKeywords" class="keyword" />
                    <a id="btnSearch" class="btn-search" onclick="doPostBack('btnSearch');"><i class="iconfont icon-search"></i></a>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/工具栏-->

<!--列表-->
<form name="categoryForm" id="categoryForm" method="post" action="<?php echo U('Article/ColumnDel');?>" >
<div class="table-container">
    <div class="tree-list">
        <div class="thead">
            <div class="col col-2">选择</div>
            <div class="col col-2">ID</div>
            <div class="col col-4">标题</div>
            <div class="col col-3">栏目类型</div>
            <div class="col col-3">是否隐藏</div>
            <div class="col col-2" align="center">排序</div>
            <div class="col col-3">操作</div>
        </div>


        <ul>
            <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li class="layer-<?php echo ($vo["count"]); ?>">
                    <div class="tbody">

                    <div class="col col-2 checkall">
                        <input type="checkbox" name="ids[]" value="<?php echo ($vo["id"]); ?>" />
                    </div>

                    <div  class="col col-2"><?php echo ($vo["id"]); ?></div>

                    <div class="col index col-4">
                        <span onclick="editField(this,'column','id','c_name','<?php echo ($vo["id"]); ?>',pajax_url,0,'<?php echo ($vo["c_name"]); ?>',0)" class="focusSpan"><?php echo ($vo["c_name"]); ?></span>
                    </div>

                    <div class="col col-3">
                        <span boolvalue="<?php echo ($vo["c_type"]); ?>"><?php if(($vo["c_type"] == 1)): ?>列表<?php else: ?>单页面<?php endif; ?></span>
                    </div>

                    <div class="col col-3">
                        <span boolvalue="<?php echo ($vo["c_is_hidden"]); ?>" onclick="editFieldIs(this,'column','id','c_is_hidden','<?php echo ($vo["id"]); ?>',pajax_url,0,1)"><?php if(($vo["c_is_hidden"] == 1)): ?>是<?php else: ?>否<?php endif; ?></span>
                    </div>

                    <div class="col col-2">
                        <span onclick="editField(this,'column','id','sort_num','<?php echo ($vo["id"]); ?>',pajax_url,0,'<?php echo ($vo["sort_num"]); ?>',0)" class="focusSpan"><?php echo ($vo["sort_num"]); ?></span>
                    </div>

                    <div class="col col-3">
                        <?php if(($vo["c_type"] == 1)): ?><a href="<?php echo U('Article/ColumnAdd');?>?action=add&id=<?php echo ($vo["id"]); ?>">添加子级</a>&nbsp;|&nbsp;
                        <?php else: ?>
                        添加子级&nbsp;|&nbsp;<?php endif; ?>
                        <a href="<?php echo U('Article/ColumnAdd');?>?action=edit&id=<?php echo ($vo["id"]); ?>">修改</a>
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