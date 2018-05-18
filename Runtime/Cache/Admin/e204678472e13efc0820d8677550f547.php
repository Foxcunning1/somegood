<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>求购信息管理</title>
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
        var ajax_url="<?php echo U('Admin/Index/ajax');?>";
        //站点列表获取
        var ajax_site_url = "<?php echo U('Site/sajax');?>";
        //单击修改的ajax地址
        var pajax_url="<?php echo U('Admin/Ajax/doubleClickModify');?>";

        function doPostBack(objId) {
            if(objId == 'btnDelete') {
                $('#goodsForm').submit();
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
        <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <span>求购信息列表</span>
    </div>
    <!--/导航栏-->

    <!--工具栏-->
    <div id="floatHead" class="toolbar-wrap">
        <div class="toolbar">
        	<div class="box-wrap">
                <a class="menu-btn"></a>
                <form action="<?php echo U('Want/index');?>" method="post" id="searchForm">
                    <div class="l-list">
                        <ul class="icon-list">
                            <li><a href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
                            <li><a onclick="ExePostBack('btnDelete','本操作会删除求购信息，是否继续？')" id="btnDelete" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
	                    </ul>
                        <div class="menu-list">

                            <div class="rule-single-select">
                                <select name="cate_id" onchange="doPostBack('btnSearch')" id="categoryId">
                                    <option value="">所有类别</option>
                                    <?php if(is_array($cate_list)): $i = 0; $__LIST__ = $cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($item['id'] == $cate_id): ?>selected="selected"<?php endif; ?> value="<?php echo ($item["id"]); ?>"><?php if(($item["count"]) != "1"): echo (fill_up_string($item['count'],"&nbsp;&nbsp;")); ?>├<?php endif; ?> <?php echo ($item["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                            <div class="rule-single-select">
                                <select name="property" onchange="doPostBack('btnSearch')" id="property">
                                    <?php if(is_array($property_list)): $i = 0; $__LIST__ = $property_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($key == $property): ?>selected="selected"<?php endif; ?> value="<?php echo ($key); ?>" ><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
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
                <th align="left" width="14%">发布人【ID】</th>
                <th align="left" width="6%">求购类别</th>
                <th align="left" width="12%">标题</th>
                <th align="left" width="12%">联系人【电话】</th>
                <th align="left" width="6%">价格</th>
                <th align="left" width="6%">地区</th>
                <th align="left" width="6%">发布时间</th>
                <th align="left" width="6%">更新时间</th>
                <th align="left" width="6%">状态</th>
                <th align="left" width="3%">排序</th>
                <th width="6%">操作</th>
            </tr>
            <form method="post" action="<?php echo U('Want/del');?>" id="goodsForm">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                        <td align="center">
                            <span class="checkall" style="vertical-align:middle;">
                                <input type="checkbox" name="w_id[]" value="<?php echo ($item["w_id"]); ?>" />
                            </span>
                        </td>
                        <td><?php echo ($item["user_name"]); ?>【<?php echo ($item["w_uid"]); ?>】</td>
                        <td><?php echo ($item["cate_title"]); ?></td>
                        <td><?php echo ($item["w_title"]); ?></td>
                        <td ><?php echo ($item["user_name"]); ?>【<?php echo ($item["mobile"]); ?>】</td>
                        <td><?php if($item["w_min"] == null && $item["w_max"] == null): ?>面议<?php elseif($item["w_min"] == null): echo ($item["w_max"]); ?>元以内<?php else: echo ($item["w_min"]); ?>—<?php echo ($item["w_max"]); endif; ?></td>
                        <td><?php echo ($item["region"]); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$item["w_addtime"])); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$item["w_updatetime"])); ?></td>
                        <td><?php if($item["status"] == 0): ?>待审核<?php elseif($item["status"] == 1): ?>已审核<?php else: ?>不显示<?php endif; ?></td>
                        <td onclick="editField(this,'want','w_id','sort_num','<?php echo ($item["w_id"]); ?>',pajax_url,0,'<?php echo ($item["sort_num"]); ?>',0)"><?php echo ($item["sort_num"]); ?></td>
                        <td align="center">
                            <a href="<?php echo U('Want/detail',array('w_id' => $item['w_id']));?>">查看</a>|
                            <a href="<?php echo U('Want/del',array('w_id' => $item['w_id']));?>">删除</a>
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