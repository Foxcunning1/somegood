<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
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
        <a href="javascript:history.back(-1);" class="back"><i></i><span>返回上一页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i></i><span>首页</span></a>
        <i class="arrow"></i>
        <span>关键词统计</span>
    </div>
    <!--/导航栏-->
    <!--工具栏-->
    <div id="floatHead" class="toolbar-wrap">
        <div class="toolbar">
            <div class="box-wrap">
                <a class="menu-btn"></a>

                <form action="<?php echo U('Keywords/index');?>" method="post" id="searchForm">
                    <div class="l-list">
                        <ul class="icon-list">
                            <li><a class="all" href="javascript:void(0);" onclick="checkAll(this)"><i></i><span>全选</span></a></li>
                            <li><a onclick="ExePostBack('btnDelete')" id="btnDelete" class="del" href="javascript:void(0);"><i></i><span>删除</span></a></li>
                        </ul>
                        <div class="menu-list">
                            <div class="rule-single-select">


                                <select name="category_id" onchange="doPostBack('categoryId') " id="categoryId">
                                    <option selected="selected" value="">所有类别</option>
                                    <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($item['id'] == $category_id): ?>selected="selected"<?php endif; ?> value="<?php echo ($item["id"]); ?>"><?php if(($item["count"]) != "1"): echo (fill_up_string($item['count'],"&nbsp;&nbsp;")); ?>├<?php endif; ?> <?php echo ($item["id"]); echo ($item["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="r-list">
                        <input name="keywords" type="text" id="txtKeywords" class="keyword" />
                        <a id="btnSearch" class="btn-search" onclick="doPostBack('btnSearch');">查询</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/工具栏-->
    <!--列表-->
    <div class="table-container">
        <form action="<?php echo U('Keywords/del');?>" id="orderForm" method="post">
            <input type="hidden" name="type" id="type" value=""/>
            <input type="hidden" name="move" id="move" value="">
            <!--文字列表-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
                <tr>
                    <th width="6%">选择</th>
                    <th width="6%" align="left">所属类型</th>
                    <th width="6%" align="left">是否热搜</th>
                    <th align="left">关键词</th>
                    <th width="6%"  align="left">次数</th>
                    <th width="10%" align="left">加入时间</th>
                    <!-- <th width="6%" >操作</th> -->
                </tr>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                        <td align="center">
                            <span class="checkall" style="vertical-align:middle;">
                                <input type="checkbox" name="ids[]" value="<?php echo ($item["id"]); ?>" />
                            </span>
                        </td>
                        <td>
                            <?php if($item["search_type"] == 3 ): ?>产品
                            <?php elseif($item["search_type"] == 2 ): ?>供求
                            <?php else: ?> 企业<?php endif; ?>
                        </td>
                        <td>
                        	<?php if($item["hot_search"] == 2 ): ?>是
							<?php else: ?>否<?php endif; ?>
                        </td>
                        <td><?php echo ($item["keyword"]); ?></td>
                        <td><?php echo ($item["count"]); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$item["date"])); ?></td>
                        <!-- <td align="center">
                            <a href="<?php echo U('Keywords/add' , array('id' => $item['id'] , 'action' => 'edit'));?>" title="修改" class="edit">修改</a>
                        </td> -->
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            <!--/文字列表-->
        </form>
    </div>
    <!--/列表-->
    <!--内容底部-->
    <div class="line20"></div>
    <div class="pagelist">
        <div class="l-btns">
            <span>显示</span><span><?php echo C('PAGE_SIZE');?></span><span>条/页</span>
        </div>
        <div id="PageContent" class="default"><?php echo ($page); ?></div>
    </div>
    <!--/内容底部-->

</body>
</html>