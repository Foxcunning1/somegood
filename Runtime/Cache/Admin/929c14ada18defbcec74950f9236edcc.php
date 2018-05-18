<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
<title>管理首页</title>
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
  <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/layindex.js"></script>
  <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
</head>

<body class="mainbody">
<!--导航栏-->
<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
  <a class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
  <i class="arrow iconfont icon-arrow-right"></i>
  <span>管理中心</span>
</div>
<!--/导航栏-->

<!--内容-->
<div class="line10"></div>
<!--<div class="static-box">-->
  <!--<ul class="static-list">-->
    <!--<li>-->
      <!--<div class="static-title"><h3>店铺审核</h3></div>-->
      <!--<div class="static-bum"><a href="javascript:;">10</a></div>-->
    <!--</li>-->
  <!--</ul>-->
<!--</div>-->
<!--<div class="line10"></div>-->
<div class="nlist-1">
  <ul>
    <li>本次登录IP：<?php echo ($login_info["current_ip"]); ?></li>
    <li>上次登录IP：<?php echo ($login_info["last_ip"]); ?></li>
    <li>上次登录时间：<?php echo (date('Y-m-d h:m:s',$login_info["add_time"])); ?></li>
  </ul>
</div>
<div class="nlist-2">
  <h3><i class="iconfont icon-setting"></i>系统信息</h3>
  <ul>
    <li>服务器操作系统：<?php echo ($info["os"]); ?></li>
    <li>Web服务器：<?php echo ($info["web_server"]); ?></li>
    <li>服务器Web端口：<?php echo ($info["port"]); ?></li>
    <li>PHP版本：PHP5.3</li>
    <li>MySQL版本：<?php echo ($info["mysql_version"]); ?></li>
    <li>MySQL版本：<?php echo ($info["mysql_version"]); ?></li>
    <li>安全模式GID：<?php echo ($info["safe_mode_gid"]); ?></li>
    <li>Zlib支持：<?php echo ($info["zlib"]); ?></li>
    <li>程序安装目录：<?php echo ($info["web_path"]); ?></li>
    <li>系统名称：<?php echo C('WEB_NAME');?></li>
    <li>程序版本：<?php echo C('VERSION');?></li>
    <li>程序开发者：<?php echo C('AUTHOR');?></li>
  </ul>
</div>
<div class="line20"></div>

<div class="nlist-3">
  <ul>
    <li><a onclick="parent.linkMenuTree(true, 'sys_config');" href="javascript:;"><i class="iconfont icon-setting"></i></a><span>系统设置</span></li>
    <li><a onclick="parent.linkMenuTree(true, 'sys_site_manage');" href="javascript:;"><i class="iconfont icon-site"></i></a><span>站点管理</span></li>
    <li><a onclick="parent.linkMenuTree(true, 'sys_site_templet');" href="javascript:;"><i class="iconfont icon-templet"></i></a><span>模板管理</span></li>
    <li><a onclick="parent.linkMenuTree(true, 'sys_builder_html');" href="javascript:;"><i class="iconfont icon-build"></i></a><span>生成静态</span></li>
    <li><a onclick="parent.linkMenuTree(true, ' sys_plugin_config ');" href="javascript:;"><i class="iconfont icon-app"></i></a><span>插件配置</span></li>
    <li><a onclick="parent.linkMenuTree(true, 'user_list');" href="javascript:;"><i class="iconfont icon-user"></i></a><span>会员管理</span></li>
    <li><a onclick="parent.linkMenuTree(true, 'manager_list');" href="javascript:;"><i class="iconfont icon-manager"></i></a><span>管理员</span></li>
    <li><a onclick="parent.linkMenuTree(true, 'manager_log');" href="javascript:;"><i class="iconfont icon-log"></i></a><span>系统日志</span></li>
  </ul>
</div>

<!--/内容-->
</body>
</html>