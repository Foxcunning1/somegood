<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>格子规格列表</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
</head>
<body class="mainbody">

    <!--导航栏-->
    <div class="location">
        <a href="javascript:history.back();" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <span>格子规格列表</span>
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
            <dt>规格</dt>
            <dd>
                <input name="length" type="text"id="length" placeholder="长度" class="input small" />&nbsp;mm&nbsp;&nbsp;
                <input name="width" type="text"  id="width" placeholder="宽度" class="input small" />&nbsp;mm&nbsp;&nbsp;
                <input name="height" type="text" id="height" placeholder="高度" class="input small" />&nbsp;mm&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" name="btnSubmit" value="新增" onclick="addSize()" id="btnSubmit" class="btn" />
            </dd>
        </dl>
    </div>
    <!--列表-->
    <div class="table-container">
        <form method="post" action="<?php echo U('Admin/Goods/del');?>" id="goodsForm">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
            <tr>
                <th align="center" width="6%">ID</th>
                <th align="left" width="3%">长</th>
                <th align="left" width="3%">宽</th>
                <th align="left" width="6%">高</th>
                <th align="left" width="6%">总数</th>
                <th align="left" width="6%">已使用</th>
                <th width="12%">操作</th>
            </tr>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                        <td align="center"><?php echo ($item["id"]); ?></td>
                        <td><?php echo ($item["l"]); ?>mm</td>
                        <td><?php echo ($item["w"]); ?>mm</td>
                        <td><?php echo ($item["h"]); ?>mm</td>
                        <td><?php echo ($item["count"]); ?>个</td>
                        <td><?php if(($item["used"]) == ""): ?>0<?php else: echo ($item["used"]); endif; ?>个</td>
                        <td align="center">
                            <?php if(($item["count"]) == "0"): ?><a href="<?php echo U('Admin/Store/delBoxSize',array('id' => $item['id']));?>">删除</a>
                                <?php else: ?>
                                不可删除<?php endif; ?>
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
        function addSize() {
            var length=$("#length").val();
            var width=$("#width").val();
            var height=$("#height").val();
            if(length*width*height!=0){
                var url="<?php echo U('Admin/Store/addSize');?>?data[l]="+length+"&data[w]="+width+"&data[h]="+height;
                window.location.href=url;
            }else{
                alert("请输入长宽高");
            }
        }
    </script>
</body>
</html>