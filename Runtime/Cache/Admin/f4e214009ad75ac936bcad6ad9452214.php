<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>订单管理</title>
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
        //单击修改的ajax地址
        var pajax_url="<?php echo U('Order/ajax');?>";

        function doPostBack(objId) {
            if(objId == 'btnDelete') {
                $('#orderForm').submit();
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
    <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
    <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
    <i class="arrow iconfont icon-arrow-right"></i>
    <span>订单列表</span>
</div>
<!--/导航栏-->

<!--工具栏-->
<div id="floatHead" class="toolbar-wrap">
    <div class="toolbar">
        <div class="box-wrap">
            <a class="menu-btn"></a>
            <form action="<?php echo U('Order/index');?>" method="post" id="searchForm">
                <div class="l-list">
                    <ul class="icon-list">
                        <li><a class="all" href="javascript:void(0);" onclick="checkAll(this)"><i class="iconfont icon-check"></i><span>全选</span></a></li>
                        <li><a onclick="ExePostBack('btnDelete')" id="btnDelete" class="del" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
                    </ul>
                    <div class="menu-list">
                        <div class="rule-single-select">
                            <select name="status" onchange="doPostBack('status')" id="property">
                                <option <?php if($status == 100): ?>selected="selected"<?php endif; ?> value=100 >全部订单</option>
                                <?php if(is_array($status_list)): $i = 0; $__LIST__ = $status_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($key == $status): ?>selected="selected"<?php endif; ?> value="<?php echo ($key); ?>" ><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="menu-list">
                        <div class="rule-single-select">
                            <select name="isUsed" onchange="doPostBack('status')" id="property">
                                <option <?php if($isUsed == 2): ?>selected="selected"<?php endif; ?> value=2 >全部</option>
                                <option <?php if($isUsed == 1): ?>selected="selected"<?php endif; ?> value=1 >二手</option>
                                <option <?php if($isUsed == 0): ?>selected="selected"<?php endif; ?> value=0 >一手</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="r-list" >
                    <input name="keywords" type="text" id="txtKeywords" class="keyword" placeholder="订单号|手机号|用户名"/>
                    <a id="btnSearch" class="btn-search" onclick="doPostBack('btnSearch');"><i class="iconfont icon-search"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--/工具栏-->

<!--列表-->
<div class="table-container">
    <form action="<?php echo U('Admin/Order/del');?>" id="orderForm" method="post">
        <input type="hidden" name="type" id="type" value=""/>
        <input type="hidden" name="move" id="move" value="">
        <input type="hidden" name="" value="">
        <!--文字列表-->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
            <tr>
                <th width="6%">选择</th>
                <th align="left">订单号</th>
                <th align="left" >会员用户名(买家)</th>
                <th align="left">订单状态</th>
                <th align="left" >金额</th>
                <th align="left" >下单时间</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if(($item["type"]) == "3"): ?><tr>
                        <td align="center">
                                <span class="checkall" style="vertical-align:middle;">
                                    <input type="checkbox" name="ids[]" value="<?php echo ($item["id"]); ?>" />
                                </span>
                        </td>
                        <td><?php echo ($item["order_sn"]); if(($item["type"]) == "1"): ?><div class="split-icon-box"><span class="split-icon"></span></div><?php endif; ?></td>
                        <td><?php if(!empty($item["user"]["user_name"])): echo ($item["user"]["user_name"]); else: echo ($item["user"]["mobile"]); endif; ?></td>
                        <td>
                            <?php if(($item["is_instalment"]) == "1"): $_result=C('BACK_LOAN_STATUS_LIST');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item2): $mod = ($i % 2 );++$i; if($item["status"] == $key): echo ($item2); endif; endforeach; endif; else: echo "" ;endif; ?>
                                <?php else: ?>
                                <?php if(is_array($status_list)): $i = 0; $__LIST__ = $status_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item2): $mod = ($i % 2 );++$i; if($item["status"] == $key): echo ($item2); endif; endforeach; endif; else: echo "" ;endif; endif; ?>
                        </td>
                        <td><?php echo ($item["money"]); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$item["add_time"])); ?></td>
                        <td align="center">
                            <a href="<?php echo U('Order/detail' , array('id' => $item['id']));?>" title="详情" class="edit">详情</a>
                        </td>
                    </tr>
                    <?php if(is_array($item["order_children"])): $i = 0; $__LIST__ = $item["order_children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item2): $mod = ($i % 2 );++$i;?><tr>
                            <td align="center">
                                <span class="checkall" style="vertical-align:middle;">
                                    <input type="checkbox" name="ids[]" value="<?php echo ($item2["id"]); ?>" />
                                </span>
                            </td>
                            <td><span class="folder-line"></span><?php echo ($item2["order_sn"]); if(($item2["type"]) == "1"): ?><div class="split-icon-box"><span class="split-icon"></span></div><?php endif; ?></td>
                            <td></td>
                            <td></td>
                            <td><?php echo ($item2["money"]); ?></td>
                            <td><?php echo (date('Y-m-d H:i:s',$item2["add_time"])); ?></td>
                            <td align="center">
                                <a href="<?php echo U('Order/detail' , array('id' => $item2['id']));?>" title="详情" class="edit">详情</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php else: ?>
                    <tr>
                        <td align="center">
                                <span class="checkall" style="vertical-align:middle;">
                                    <input type="checkbox" name="ids[]" value="<?php echo ($item["id"]); ?>" />
                                </span>
                        </td>
                        <td><?php echo ($item["order_sn"]); if(($item["type"]) == "1"): ?><div class="split-icon-box"><span class="split-icon"></span></div><?php endif; ?></td>
                        <td><?php if(!empty($item["user"]["user_name"])): echo ($item["user"]["user_name"]); else: echo ($item["user"]["mobile"]); endif; ?></td>
                        <td>
                            <?php if(($item["is_instalment"]) == "1"): $_result=C('BACK_LOAN_STATUS_LIST');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item2): $mod = ($i % 2 );++$i; if($item["status"] == $key): echo ($item2); endif; endforeach; endif; else: echo "" ;endif; ?>
                                <?php if(($item["status"]) == "1"): ?>已还<?php echo ($item["current"]); ?>期<?php endif; ?>
                                <?php else: ?>
                                <?php if(is_array($status_list)): $i = 0; $__LIST__ = $status_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item2): $mod = ($i % 2 );++$i; if($item["status"] == $key): echo ($item2); endif; endforeach; endif; else: echo "" ;endif; endif; ?>
                        </td>
                        <td><?php echo ($item["money"]); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$item["add_time"])); ?></td>
                        <td align="center">
                            <!-- <a href="<?php echo U('Order/detail' , array('id' => $item['id']));?>" title="编辑" class="edit">编辑</a> -->
                            <a href="<?php echo U('Order/orderDetail' , array('id' => $item['id']));?>" title="详情" class="edit">查看|编辑</a>
                        </td>
                    </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
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