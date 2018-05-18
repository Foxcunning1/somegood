<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>购物车信息管理</title>
<link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
<link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
<script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
<script type="text/javascript" src="/somegood/Public/scripts/datepicker/WdatePicker.js"></script>
<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>

</head>

<body class="mainbody">
<script type="text/javascript">
function doPostBack(obj){
    switch (obj){
        case 'btnEnable':
            $("#action").val('enabled');
            $("#usersForm").submit();
            break;
        case 'btnDisable':
            $("#action").val('diabled');
            $("#usersForm").submit();
            break;
        case 'btnDelete':
            $("#action").val('del');
            $("#usersForm").submit();
            break;
        default:
            checkFormInputIsEmpty("#txtStartTime");
            checkFormInputIsEmpty("#txtEndTime");
            checkFormInputIsEmpty("#txtKeywords");
            $("#searchForm").submit();
            break;
    }
}
</script>

<!--导航栏-->
<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
  <a href="<?php echo U('Admin/Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
  <i class="arrow iconfont icon-arrow-right"></i>
  <span>购物车信息管理</span>
</div>
<!--/导航栏-->

<!--工具栏-->
<div id="floatHead" class="toolbar-wrap">
      <div class="toolbar">
        <div class="box-wrap">
          <a class="menu-btn"></a>
          <div class="l-list">
            <ul class="icon-list">
              <li><a class="all" href="javascript:;" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
            </ul>
            <form name="searchForm" id="searchForm" method="get" action="<?php echo U('Admin/Cart/index');?>">


                <div class="r-list">
                  <input name="keywords" type="text" id="txtKeywords" class="keyword" value="<?php echo ($keywords); ?>" />
                  <a id="lbtnSearch" class="btn-search" href="javascript:doPostBack('lbtnSearch')"><i class="iconfont icon-search"></i></a>
                </div>
            </form>
          </div>
        </div>
      </div>
</div>
<!--/工具栏-->

<!--列表-->
<div class="table-container">
<form method="post" action="<?php echo U('Admin/Cart/del');?>" id="usersForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
  <tr>
    <th width="8%">选择</th>
    <th align="left" colspan="2">商品名</th>
    <th align="left" width="8%">商品市场价格</th>
    <th align="left" width="8%">商品价格</th>
    <th align="left" width="15%">商品购物车总数量</th>
    <th width="8%">商品加入购物车总用户数</th>
    <th width="8%">操作</th>
  </tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
    <td align="center">
      <span class="checkall" style="vertical-align:middle;"><input id="ids" type="checkbox" name="ids[]" value="<?php echo ($vo['uid']); ?>" /></span>
    </td>
    <td width="64">
      <a href="<?php echo U('Admin/Users/add');?>?action=edit&id=<?php echo ($vo['uid']); ?>" class="user-avatar">
        <!-- <?php if($vo['avatar']): ?><img width="64" height="64" src="/somegood/Public/Uploads/avatar/<?php echo ($vo['avatar']); ?>" /><?php else: ?>
            <i class="iconfont icon-user-full"></i><?php endif; ?> -->
      </a>
    </td>
    <!-- <td>
      <div class="user-box">
        <h4><b><?php if(!empty($vo["user_name"])): echo ($vo["user_name"]); ?>&nbsp;-&nbsp;<?php endif; echo ($vo["mobile"]); ?></b></h4>
        <i>注册时间：<?php if(($vo["reg_time"]) == "0"): ?>-<?php else: echo (date('Y/m/d h:i',$vo['reg_time'])); endif; ?></i>
        <span style="display:none;">
          <a class="amount" href="javascript:void(0);" title="消费记录"><i class="iconfont icon-count"></i>余额</a>
          <a class="point" href="javascript:void(0);" title="积分记录"><i class="iconfont icon-star"></i>积分</a>
          <a class="sms" href="javascript:void(0);" title="发送手机短信通知"><i class="iconfont icon-mail"></i>短信通知</a>
        </span>
      </div>
    </td> -->
    <td><?php echo ($vo['goods_title']); ?></a></td>
    <td><?php echo ($vo['market_price']); ?></a></td>
    <td><?php echo ($vo['goods_price']); ?></td>
    <td align="center"><?php echo ($vo["counts"]); ?></td>
    <td align="center"><?php echo ($vo["count_num"]); ?></td>
    <td align="center"><a href="<?php echo U('Admin/Users/usersAdd');?>?action=edit&id=<?php echo ($vo['uid']); ?>">商品详情</a></td>
  </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
</table>
    <input type="hidden" name="action" id="action" value="del" />
</form>
</div>
<!--/列表-->
<div class="line20"></div>
<!--内容底部-->
<div class="pagelist">
    <div id="PageContent" class="default"><?php echo ($page); ?></div>
</div>
<!--/内容底部-->
</body>
</html>