<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>推荐位列表</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery.lazyload.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript">
        //菜单获取
        var ajax_url="<?php echo U('Index/ajax');?>";
        //站点列表获取
        var ajax_site_url = "<?php echo U('Site/sajax');?>";
        //单击修改的ajax地址
        var pajax_url="<?php echo U('Ajax/doubleClickModify');?>";

        function doPostBack(objId) {
            if(objId == 'btnDelete') {
                $('#recForm').submit();
            }else{
                $('#searchForm').submit();
            }
        }
        $(function () {
            //图片延迟加载
            $(".pic img").lazyload({ effect: "fadeIn" });
            //点击图片链接
            $(".pic img").click(function () {
                var linkUrl = $(this).parent().parent().find(".foot a").attr("href");
                if (linkUrl != "") {
                    location.href = linkUrl; //跳转到修改页面
                }
            });
        });
    </script>
</head>

<body class="mainbody">
    </div>
    <!--导航栏-->
    <div class="location">
        <a href="javascript:history.back();" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <span>推荐位列表</span>
    </div>
    <!--/导航栏-->

    <!--工具栏-->
    <div id="floatHead" class="toolbar-wrap">
        <div class="toolbar">
            <div class="box-wrap">
                <a class="menu-btn"></a>
                <form action="<?php echo U('Article/rec');?>" method="post" id="searchForm">
                    <div class="l-list">
                        <ul class="icon-list">
                            <li><a class="add" href="<?php echo U('recAdd');?>"><i></i><span>新增</span></a></li>
                            <li><a class="all" href="javascript:void(0);" onclick="checkAll(this)"><i></i><span>全选</span></a></li>
                            <li><a onclick="ExePostBack('btnDelete')" id="btnDelete" class="del" href="javascript:void(0);"><i></i><span>删除</span></a></li>
                        </ul>
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
        <form action="<?php echo U('Article/recDel');?>" id="recForm" method="post">
            <input type="hidden" name="type" id="type" value=""/>
            <input type="hidden" name="move" id="move" value="">
            <!--文字列表-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
                <tr>
                    <th width="6%">选择</th>
                    <th align="left" >ID</th>
                    <th align="left">推荐位名称</th>
                    <th align="left">推荐位别名</th>
                    <th align="left" width="6%">最大条数</th>
                    <th align="left" width="6%">排序</th>
                    <th>操作</th>
                </tr>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                        <td align="center">
                            <span class="checkall" style="vertical-align:middle;">
                                <input type="checkbox" name="ids[]" value="<?php echo ($item["id"]); ?>" />
                            </span>
                        </td>
                        <td><?php echo ($item["id"]); ?></td>
                        <td onclick="editField(this,'article_rec','id','title','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["title"]); ?>',0)"><span ><?php echo ($item["title"]); ?></td>
                        <td onclick="editField(this,'article_rec','id','name','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["name"]); ?>',0)"><?php echo ($item["name"]); ?></td>
                        <td onclick="editField(this,'article_rec','id','max_num','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["max_num"]); ?>',0)"><?php echo ($item["max_num"]); ?></td>
                        <td onclick="editField(this,'article_rec','id','sort_num','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["sort_num"]); ?>',0)"><?php echo ($item["sort_num"]); ?></td>
                        <td align="center">
                            <a href="<?php echo U('Article/recAdd' , array('id' => $item['id'] , 'action' => 'edit'));?>" title="修改" class="edit">修改</a>&nbsp;|
                            <a href="<?php echo U('Article/recData' , array('id' => $item['id']));?>" title="推荐位内容管理" class="edit">推荐位内容管理</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            <!--/文字列表-->
        </form>
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