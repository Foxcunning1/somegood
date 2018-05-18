<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>店铺申请管理</title>
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
            if(objId == 'btnDelete') {
                $('#goodsForm').submit();
            }
            if(objId == 'btnSearch'){
                $('#searchForm').submit();
            }
            if (objId == 'btnPass') {
                //调用ajax来更新状态
                $('#goodsForm').submit();
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
            <form action="<?php echo U('Admin/Seller/check');?>" method="post" id="searchForm">
                <div class="l-list">
                    <ul class="icon-list">
                        <li><a class="all" href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
                        <li><a onclick="return ExePostBack('btnPass','本操作会通过审核，是否继续？');" id="btnDelete" class="del" href="javascript:void(0);"><i class="iconfont icon-pass"></i><span>通过</span></a></li>
                    </ul>
                </div>
                <div class="rule-single-select">
                <select name="is_seller" onchange="doPostBack('btnSearch')" id="categoryId">
                    <?php if(is_array($status)): $i = 0; $__LIST__ = $status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($is_seller == $key): ?>selected="selected"<?php endif; ?>><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                </div>
                <div class="r-list" >
                    <input name="keywords" placeholder="手机号|企业名|卖家姓名" type="text" id="txtKeywords" class="keyword" style="width:150px;"/>
                    <a id="btnSearch" class="btn-search" onclick="doPostBack('btnSearch');"><i class="iconfont icon-search"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--/工具栏-->

<!--列表-->
<div class="table-container">
    <form method="post" action="<?php echo U('Admin/Seller/pass');?>" id="goodsForm">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
            <tr>
                <th width="10%">选择</th>
                <th align="center" width="10%">卖家ID</th>
                <th align="center" width="10%">真实姓名</th>
                <th align="center" width="10%">联系方式</th>
                <th align="center" width="10%">公司名称</th>
                <th align="center" width="10%">状态</th>
                <th width="20%">操作</th>
            </tr>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                    <td align="center">
                            <span class="checkall" style="vertical-align:middle;">
                                <input type="checkbox" name="ids[]" value="<?php echo ($item["user_id"]); ?>" />
                            </span>
                    </td>
                    <td align="center"><?php echo ($item["user_id"]); ?></td>
                    <td align="center"><?php echo ($item["name"]); ?></td>
                    <td align="center"><?php echo ($item["mobile"]); ?></td>
                    <td align="center"><?php echo ($item["company_name"]); ?></td>
                    <td align="center"><?php if($item['is_seller'] == 1): ?>已通过<?php elseif($item['is_seller'] == 2): ?>已拒绝<?php else: ?>未审核<?php endif; ?></td>
                    <td align="center">
                        <?php if(($item["is_seller"]) == "1"): ?><a href="<?php echo U('Admin/SellerDelivery/delivery',array('user_id' => $item['user_id'],'type'=>'add'));?>">发布商品</a>
                            <a href="<?php echo U('Admin/Adv/list',array('user_id' => $item['user_id']));?>">线上广告</a>
                            <a href="<?php echo U('Admin/Adv/advList',array('user_id' => $item['user_id']));?>">店铺广告</a><?php endif; ?>
                        <a href="<?php echo U('Admin/Seller/detail',array('id' => $item['user_id']));?>">详情</a>
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