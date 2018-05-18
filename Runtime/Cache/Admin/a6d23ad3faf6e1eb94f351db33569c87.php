<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>二手商品管理</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript">
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
        <a href="javascript:history.back();" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <a href="<?php echo U('Admin/GoodsType/goods_type_list');?>"><span>栏目列表</span></a>
    </div>
    <!--/导航栏-->

    <!--工具栏-->
    <div id="floatHead" class="toolbar-wrap">
        <div class="toolbar">
        	<div class="box-wrap">
                <a class="menu-btn"></a>
                <form action="<?php echo U('Admin/ShGoods/goodsList');?>" method="post" id="searchForm">
                    <div class="l-list">
                        <ul class="icon-list">

	                        <li><a class="all" href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
	                        <li><a onclick="return ExePostBack('btnDelete','本操作会删除商品，是否继续？');" id="btnDelete" class="del" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
	                    </ul>
                        <div class="menu-list">
                            <div class="rule-single-select" style="display:none;">
                                <select name="move_id" onchange="ExePostBack('moveId','批量移动后，所有选中的商品的类别会变成选中移动的类型，确定继续吗？')" id="moveId">
                                    <option value="">批量移动...</option>
                                    <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item["id"]); ?>"><?php if(($item["count"]) != "1"): echo (fill_up_string($item['count'],"&nbsp;&nbsp;")); ?>├<?php endif; ?> <?php echo ($item["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                            <div class="rule-single-select">
                                <select name="category_id" onchange="doPostBack('categoryId')" id="categoryId">
                                    <option value="">所有类别</option>
                                    <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($item['id'] == $category_id): ?>selected="selected"<?php endif; ?> value="<?php echo ($item["id"]); ?>"><?php if(($item["count"]) != "1"): echo (fill_up_string($item['count'],"&nbsp;&nbsp;")); ?>├<?php endif; ?> <?php echo ($item["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>

                            <div class="rule-single-select">
                                <select name="property" onchange="doPostBack('property')" id="property">
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
        <form method="post" action="<?php echo U('Admin/ShGoods/del');?>" id="goodsForm">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
            <tr>
                <th width="6%">选择</th>
                <th align="left" width="6%">ID</th>
                <th align="left" width="15%">商品名字</th>
                <th align="left" width="6%">类别</th>
                <th align="center" width="6%">置顶</th>
                <th align="left" width="8%">更新时间</th>
                <th align="left" width="6%">状态</th>
                <th align="left" width="6%">排序</th>
                <th width="12%">操作</th>
            </tr>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                        <td align="center">
                            <span class="checkall" style="vertical-align:middle;">
                                <input type="checkbox" name="ids[]" value="<?php echo ($item["id"]); ?>" />
                            </span>
                        </td>
                        <td><?php echo ($item["id"]); ?></td>
                        <td><span class="focusSpan" onclick="editField(this,'sh_goods','id','goods_title','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["goods_title"]); ?>',0)"><?php echo ($item["goods_title"]); ?></td>
                        <td><?php echo ($item["category_name"]); ?></td>
                        <td align="center">
                            <span boolvalue="<?php echo ($item["is_top"]); ?>" onclick="editFieldIs(this,'sh_goods','id','is_top','<?php echo ($item["id"]); ?>',pajax_url,0,1)"><?php if(($item["is_top"] == 1)): ?>是<?php else: ?>否<?php endif; ?></span>
                        </td>
                        <td><?php echo (date('Y-m-d H:i:s',$item["update_time"])); ?></td>
                        <td>
                            <?php if(($item["status"]) == "0"): ?>未审核<?php endif; ?>
                            <?php if(($item["status"]) == "1"): ?>已审核<?php endif; ?>
                            <?php if(($item["status"]) == "2"): ?>审核失败<?php endif; ?>
                            <?php if(($item["status"]) == "3"): ?>已下架<?php endif; ?>
                        </td>
                        <td onclick="editField(this,'sh_goods','id','sort_num','<?php echo ($item["id"]); ?>',pajax_url,0,'<?php echo ($item["sort_num"]); ?>',0)"><?php echo ($item["sort_num"]); ?></td>
                        <td align="center">
                            <a href="<?php echo U('Admin/ShGoods/edit',array('action'=>'edit','id' => $item['id']));?>">编辑</a>
                            <a target="_blank" href="<?php echo U('Ershou/ShGoods/detail',array('id' => $item['id']));?>">详情</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>

        </table>
            <input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>" />
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