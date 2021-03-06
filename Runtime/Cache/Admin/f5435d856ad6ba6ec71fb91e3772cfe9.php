<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>客户信息列表</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery.form.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js" ></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript">
        function doPostBack(objId) {
            if(objId == 'btnDelete') {
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
    <span>客户信息列表</span>
</div>
<!--/导航栏-->

<!--工具栏-->

<div id="floatHead" class="content-tab-wrap">

    <div class="content-tab">
        <div class="content-tab-ul-wrap">
            <ul>
                <li><a class="selected" href="javascript:void(0);">基本信息</a></li>
            </ul>
        </div>
    </div>
</div>
<!--/工具栏-->
<div class="tab-content">
    <dl>
        <dt>EXCEL导入</dt>
        <dd>
            <form method="post" enctype="multipart/form-data" id="excelForm" url="<?php echo U('Admin/Customer/excelAdd');?>">
                <input  type="file" name="excel_updata" />
                <input type="button" name="btnSubmit" value="导入" onclick="excelAddCustomer();" id="btnSubmit" class="btn" />
                <a href="/somegood/Public/Excel/规范.xlsx">下载范例</a>
            </form>
        </dd>
    </dl>
    <dl>
        <dt>新增客户信息</dt>
        <dd>
            <form method="post" enctype="multipart/form-data" id="addForm" url="<?php echo U('Admin/Customer/add');?>">
                <input name="data[company_name]" type="text"id="conpany_name" placeholder="公司名" class="input " />
                <input name="data[products]" type="text"id="products" placeholder="主营产品" class="input " />
                <input name="data[address]" type="text"  id="address" placeholder="地址" class="input" />
                <input name="data[consignee]" type="text" id="consignee" placeholder="联系人" class="input small" />
                <input name="data[mobile]" type="text" id="mobile" placeholder="电话" class="input small" />
                <input name="data[delivery_type]" type="text" id="delivery_type" placeholder="现有投放方式" class="input " />
                <input type="button" name="btnSubmit" value="新增" onclick="addCustomer();" class="btn" />
            </form>
        </dd>
    </dl>
</div>
<div style="height:10px; clear:both;"></div>
<!--列表-->
<div class="table-container">
    <div class="toolbar">
        <div class="l-list">
            <ul class="icon-list">
                <li><a href="javascript:void(0);" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
                <li><a onclick="ExePostBack('btnDelete','本操作会删除客户信息，是否继续？')" id="btnDelete" href="javascript:void(0);"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
            </ul>
        </div>
    </div>
    <form method="post" action="<?php echo U('Admin/Customer/del');?>" id="goodsForm">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
            <tr>
                <th align="center" width="3%">ID</th>
                <th align="left" width="15%">公司名</th>
                <!--<th align="left" width="6%">主营产品</th>-->
                <th align="left" width="30%">地址</th>
                <!--<th align="left" width="5%">联系人</th>-->
                <th align="left" width="6%">联系方式</th>
                <!--<th align="left" width="8%">现有投放方式</th>-->
                <th align="left" width="3%">添加人</th>
                <th align="left" width="6%">添加时间</th>
                <th align="left" width="4%">操作人</th>
                <th align="left" width="6%">操作时间</th>
                <th align="left" width="4%">状态</th>
                <th align="left" width="4%">操作</th>
            </tr>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                    <td align="center">
                        <span class="checkall" style="vertical-align:middle;">
                                <input type="checkbox" name="ids[]" value="<?php echo ($item["id"]); ?>" />
                            </span>
                    </td>
                    <td><?php echo ($item["company_name"]); ?></td>
                    <!--<td><?php echo ($item["products"]); ?></td>-->
                    <td><?php echo ($item["address"]); ?></td>
                    <!--<td><?php echo ($item["consignee"]); ?></td>-->
                    <td><?php echo ($item["mobile"]); ?></td>
                    <!--<td><?php echo ($item["delivery_type"]); ?></td>-->
                    <td><?php echo ($item["add_admin"]); ?></td>
                    <td><?php echo (date("Y-m-d H:i:s",$item["add_time"])); ?></td>
                    <td><?php echo ($item["operator"]); ?></td>
                    <td>
                        <?php if(($item["status"]) == "0"): ?>未联系
                            <?php else: ?>
                            <?php echo (date("Y-m-d H:i:s",$item["do_time"])); endif; ?>
                    </td>
                    <td>
                        <?php switch($item["status"]): case "0": ?>未联系<?php break;?>
                            <?php case "1": ?>有意向<?php break;?>
                            <?php case "2": ?>无意向<?php break; endswitch;?>
                    </td>
                    <td>
                        <a href="<?php echo U('Admin/Customer/del',array('ids'=>$item[id]));?>" onclick="return confirm('确定删除？')">删除</a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
    </form>

</div>
<!--/列表-->
<!--内容底部-->
<div class="line20"></div>
<div class="pagelist">
    <div id="PageContent" class="default"><?php echo ($page); ?></div>
</div>
<!--/内容底部-->
<script>
    function excelAddCustomer() {
        $("#excelForm").ajaxSubmit({
            success: showResponse,
            url: $("#excelForm").attr("url"),
            type: "post",
            dataType: "json",
            timeout: 60000
        });
    }
    function addCustomer() {
        $("#addForm").ajaxSubmit({
            success: showResponse,
            url: $("#addForm").attr("url"),
            type: "post",
            dataType: "json",
            timeout: 60000
        });
    }
    //表单提交后
    function showResponse(data, textStatus) {
        if (data.status == 1) { //成功
            var d = dialog({
                title:'提示',
                content:data.info
            }).show();
            window.setInterval(function(){
                window.location="/somegood/Admin/Customer/index/p/1.html";
                },10000);
        }else{ //失败
            var d = dialog({content:data.info}).show();
            window.setInterval(function(){
                d.close().remove();
            },1000);
        }
    }
</script>
</body>
</html>