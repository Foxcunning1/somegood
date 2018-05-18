<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>店铺列表</title>
  <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery.lazyload.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
</head>

<body class="mainbody">
<script type="text/javascript">
    //菜单获取
    var ajax_url="<?php echo U('Index/ajax');?>";
    //站点列表获取
    var ajax_site_url = "<?php echo U('Site/sajax');?>";

    function doPostBack(obj){
        if(obj=='btnEnable'){
          $("#status").attr("value",'1')
          $("#listForm2").submit();
        }else if(obj=='btnDisable'){
          $("#status").attr("value",'3');
          $("#listForm2").submit();
        }else{
          $("#listForm1").submit();
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

<!--导航栏-->
<div class="location">
  <a href="javascript:history.back();" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
  <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
  <i class="arrow iconfont icon-arrow-right"></i>
  <span>店铺列表</span>
</div>
<!--/导航栏-->

<!--工具栏-->
<form method="post" action="<?php echo U('Store/index');?>" id="listForm1">
<div id="floatHead" class="toolbar-wrap">
      <div class="toolbar">
        <div class="box-wrap">
          <a class="menu-btn"></a>
          <div class="l-list">
            <div class="menu-list">
              <div class="rule-single-select">
                <select name="area_id" id="area_id" onchange="javascript:doPostBack('area_id')">
                  	<option value="" <?php if($area == 0): ?>selected="selected"<?php endif; ?> >所有区域</option>
                    <?php if(is_array($area_list)): $i = 0; $__LIST__ = $area_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['id']); ?>" <?php if($vo['id'] == $area_id): ?>selected="selected"<?php endif; ?>><?php echo ($vo['name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </div>
            </div>
            <div class="menu-list">
              <div class="rule-single-select">
                <select name="property" onchange="doPostBack('property')" id="property">
                  <?php if(is_array($property_list)): $i = 0; $__LIST__ = $property_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($key == $property): ?>selected="selected"<?php endif; ?> value="<?php echo ($key); ?>" ><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </div>
            </div>
            <div class="r-list">
              <input name="keywords" type="text" id="txtKeywords" class="keyword" value="<?php echo ($keywords); ?>" />
              <a id="btnSearch" class="btn-search" onclick="doPostBack('btnSearch');"><i class="iconfont icon-search"></i></a>
            </div>
          </div>
        </div>
      </div>
</div>
</form>
<!--/工具栏-->

<!--列表-->
<div class="table-container">
<form method="post" action="" id="">
    <input type="hidden" id="status" name="status" value=""/>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
  <tr>
    <th align="left"  colspan="2" style="padding-left: 30px">店铺</th>
    <th width="12%">区域</th>
    <th width="25%">地址</th>
    <th width="12%">状态</th>
    <th width="12%">操作</th>
  </tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
    <td width="64">
      <a href="<?php echo U('Store/add');?>?action=edit&id=<?php echo ($vo['sid']); ?>"  class="user-avatar">
        <?php if($vo['logo']): ?><img width="64" height="64" src="/somegood/Public/uploads/<?php echo ($vo['logo']); ?>_c100x100" /><?php else: ?>
          <i class="iconfont icon-user-full"></i><?php endif; ?>
      </a>
    </td>
    <td>
      <div class="user-box">
        <h4><b><?php if(!empty($vo["user_name"])): echo ($vo["user_name"]); ?>&nbsp;-&nbsp;<?php endif; if(!empty($vo["store_name"])): echo ($vo["store_name"]); ?>&nbsp;-&nbsp;<?php endif; echo ($vo["mobile"]); ?></b></h4>
        <i>注册时间：<?php if(($vo["reg_time"]) == "0"): ?>-<?php else: echo (date('Y/m/d h:i',$vo['reg_time'])); endif; ?></i>
      </div>
    </td>
    <td align="center"><?php echo ($vo['region']); ?></td>
    <td align="center"><?php echo ($vo['address']); ?></td>
    <td align="center">
    <?php if($vo['status'] == 1): ?>正常<?php endif; ?>
    <?php if($vo['status'] == 2): ?>待审核<?php endif; ?>
    <?php if($vo['status'] == 3): ?>禁用<?php endif; ?>
    </td>
    <td align="center"><a href="<?php echo U('Store/add');?>?action=edit&id=<?php echo ($vo['sid']); ?>">查看</a></td>
  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
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