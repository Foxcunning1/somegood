<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>会员管理</title>
<link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
<link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
<script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
<script type="text/javascript" src="/somegood/Public/scripts/datepicker/WdatePicker.js"></script>
<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
<script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
<script type="text/javascript">
    //发送短信
    function PostSMS(mobile) {
        var mobiles = "";
        if (arguments.length == 1) { //如果有传入值
            mobiles = mobile;
        } else {
            lenNum = $(".checkall input:checked").length;
            $(".checkall input:checked").each(function (i) {
                if ($(this).parent().siblings('input[name="hidMobile"]').val() != "") {
                    mobiles += $(this).parent().siblings('input[name="hidMobile"]').val();
                    if (i < lenNum - 1) {
                        mobiles += ',';
                    }
                }
            });
        }
        if (mobiles == "") {
            top.dialog({
                title: '提示',
                content: '对不起，手机号码不能为空！',
                okValue: '确定',
                ok: function () { }
            }).showModal();
            return false;
        }
        var smsdialog = parent.dialog({
            title: '发送手机短信',
            content: '<textarea id="txtSmsContent" name="txtSmsContent" rows="2" cols="20" class="input"></textarea>',
            okValue: '确定',
            ok: function () {
                var remark = $("#txtSmsContent", parent.document).val();
                if (remark == "") {
                    top.dialog({
                        title: '提示',
                        content: '对不起，请输入手机短信内容！',
                        okValue: '确定',
                        ok: function () { }
                    }).showModal(smsdialog);
                    return false;
                }
                var postData = { "mobiles": mobiles, "content": remark };
                //发送AJAX请求
                $.ajax({
                    type: "post",
                    url: "../../tools/admin_ajax.ashx?action=sms_message_post",
                    data: postData,
                    dataType: "json",
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        top.dialog({
                            title: '提示',
                            content: '尝试发送失败，错误信息：' + errorThrown,
                            okValue: '确定',
                            ok: function () { }
                        }).showModal(smsdialog);
                    },
                    success: function (data, textStatus) {
                        if (data.status == 1) {
                            smsdialog.close().remove();
                            var d = top.dialog({ content: data.msg }).show();
                            setTimeout(function () {
                                d.close().remove();
                                location.reload();
                            }, 2000);
                        } else {
                            top.dialog({
                                title: '提示',
                                content: '错误提示：' + data.msg,
                                okValue: '确定',
                                ok: function () { }
                            }).showModal(smsdialog);
                        }
                    }
                });
                return false;
            },
            cancelValue: '取消',
            cancel: function () { }
        }).showModal();
    }
</script>
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
        case 'btnAuth':
            $("#action").val('auth');
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
  <span>会员列表</span>
</div>
<!--/导航栏-->

<!--工具栏-->
<div id="floatHead" class="toolbar-wrap">
      <div class="toolbar">
        <div class="box-wrap">
          <a class="menu-btn"></a>
          <div class="l-list">
            <ul class="icon-list">
              <li><a class="add" href="<?php echo U('Admin/Users/usersAdd');?>?action=add"><i class="iconfont icon-close"></i><span>新增</span></a></li>
              <li><a class="msg" href="javascript:;" onclick="PostSMS();"><i class="iconfont icon-mail"></i><span>短信</span></a></li>
              <li><a class="all" href="javascript:;" onclick="checkAll(this);"><i class="iconfont icon-check"></i><span>全选</span></a></li>
                <li><a onclick="return ExePostBack('btnAuth','认证当前会员，您确定吗？');" id="btnAuth"  class="enable" href="javascript:doPostBack('btnAuth')"><i class="iconfont icon-account"></i><span>认证</span></a></li>
              <li><a onclick="return ExePostBack('btnDelete');" id="btnDelete" class="del" href="javascript:doPostBack('btnDelete')"><i class="iconfont icon-delete"></i><span>删除</span></a></li>
              <li><a onclick="return ExePostBack('btnEnable','启用当前会员，您确定吗？');" id="btnEnable"  class="enable" href="javascript:doPostBack('btnEnable')"><i class="iconfont icon-success"></i><span>启用</span></a></li>
              <li><a onclick="return ExePostBack('btnDisable','禁用当前会员，您确定吗？');" id="btnDisable"  class="disable" href="javascript:doPostBack('btnDisable')"><i class="iconfont icon-key"></i><span>禁用</span></a></li>
            </ul>
            <form name="searchForm" id="searchForm" method="get" action="<?php echo U('Admin/Users/usersList');?>">
                <div class="menu-list">
                  <div class="rule-single-select">
                    <select name="group" id="ddlGroupId" onchange="javascript:doPostBack('ddlGroupId')">
                        <option value="0" <?php if($group == 0): ?>selected="selected"<?php endif; ?> >所有会员组</option>
                        <?php if(is_array($groupList)): $i = 0; $__LIST__ = $groupList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['gid']); ?>" <?php if($vo['gid'] == $group): ?>selected="selected"<?php endif; ?>><?php echo ($vo['group_name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                  </div>
                </div>
                <div class="menu-list">
                  <div class="rule-single-select">
                    <select name="status" id="status" onchange="javascript:doPostBack('status')">
                        <option value="0" <?php if($status == 0): ?>selected="selected"<?php endif; ?>>所有属性</option>
                        <option value="1" <?php if($status == 1): ?>selected="selected"<?php endif; ?>>正常</option>
                        <option value="2" <?php if($status == 2): ?>selected="selected"<?php endif; ?>>待审核</option>
                        <option value="3" <?php if($status == 3): ?>selected="selected"<?php endif; ?>>已禁用</option>
                    </select>
                  </div>
                    <input name="startTime" type="text" id="txtStartTime" class="input rule-date-input" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($startTime); ?>" />
                    -
                    <input name="endTime" type="text" id="txtEndTime" class="input rule-date-input" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($endTime); ?>" />
                </div>
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
<form method="post" action="<?php echo U('Admin/Users/usersDel');?>" id="usersForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
  <tr>
    <th width="8%">选择</th>
    <th align="left" colspan="2">用户名</th>
    <th align="left" width="8%">会员组</th>
    <th align="left" width="15%">手机号</th>
    <th align="left" width="8%">抵扣币</th>
    <th width="8%">积分</th>
    <th width="8%">认证状态</th>
    <th width="8%">状态</th>
    <th width="8%">操作</th>
  </tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
    <td align="center">
      <span class="checkall" style="vertical-align:middle;"><input id="ids" type="checkbox" name="ids[]" value="<?php echo ($vo['uid']); ?>" /></span>
    </td>
    <td width="64">
      <a href="<?php echo U('Admin/Users/usersAdd');?>?action=edit&id=<?php echo ($vo['uid']); ?>" class="user-avatar">
        <?php if($vo['avatar']): ?><img width="64" height="64" src="/somegood/Public/uploads/<?php echo ($vo['avatar']); ?>_c100x100" /><?php else: ?>
            <i class="iconfont icon-user-full"></i><?php endif; ?>
      </a>
    </td>
    <td>
      <div class="user-box">
        <h4><b><?php if(!empty($vo["user_name"])): echo ($vo["user_name"]); ?>&nbsp;-&nbsp;<?php endif; echo ($vo["mobile"]); ?></b></h4>
        <i>注册时间：<?php if(($vo["reg_time"]) == "0"): ?>-<?php else: echo (date('Y/m/d h:i',$vo['reg_time'])); endif; ?></i>
        <span style="display:none;">
          <a class="amount" href="javascript:void(0);" title="消费记录"><i class="iconfont icon-count"></i>余额</a>
          <a class="point" href="javascript:void(0);" title="积分记录"><i class="iconfont icon-star"></i>积分</a>
          <a class="sms" href="javascript:void(0);" title="发送手机短信通知"><i class="iconfont icon-mail"></i>短信通知</a>
        </span>
      </div>
    </td>
    <td><a href="<?php echo U('Admin/Users/usersList',array('group'=>$vo['group_id']));?>"><?php echo ($vo['group_name']); ?></a></td>
    <td><a href="<?php echo U('Admin/Users/virtualCash',array('user_id'=>$vo['uid']));?>"><?php echo ($vo['share_account']); ?></a></td>
    <td><?php echo ($vo['mobile']); ?></td>
    <td align="center"><?php echo ($vo["integral"]); ?></td>
    <td align="center">
         <?php if(($vo["auth_status"]) == "0"): ?><span>未认证</span><?php endif; ?>
         <?php if(($vo["auth_status"]) == "1"): ?><span class="preview" data-id="<?php echo ($vo["uid"]); ?>">待认证</span><?php endif; ?>
         <?php if(($vo["auth_status"]) == "2"): ?><span class="preview" data-id="<?php echo ($vo["uid"]); ?>">已认证</span><?php endif; ?>
    </td>
    <td align="center">
        <?php if(($vo["is_lock"]) == "0"): if(($vo["status"]) == "1"): ?>已审核<?php endif; ?>
            <?php if(($vo["status"]) == "2"): ?>待审核<?php endif; ?>
        <?php else: ?>
            已禁用<?php endif; ?>
    </td>
    <td align="center">
        <a href="<?php echo U('Admin/Users/usersAdd');?>?action=edit&id=<?php echo ($vo['uid']); ?>">修改</a>
        <?php if(($vo["is_lock"]) == "0"): if(($vo["status"]) == "1"): ?>&nbsp;&nbsp;|&nbsp;&nbsp;
                <?php if(($vo["is_store"]) == "0"): ?><a href="<?php echo U('Admin/Store/add');?>?action=add&uid=<?php echo ($vo['uid']); ?>">添加店铺</a>
                    <?php else: ?>
                    已有店铺<?php endif; endif; endif; ?>
    </td>
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
<style type="text/css">
    .pop-box-default{ display: none;}
    .pop-box{ width: auto; height:auto; left:50%; top:50%; background-color: rgba(255,255,255,1); margin-left:-25%; margin-top:-10%; position: fixed;  border-radius:8px; padding:5px; background:#fff; border:#c8c8c8 2px solid; box-shadow:3px 3px 5px #333; overflow: hidden;}
</style>
<div id="pop-box" class="pop-box-default"></div>
<script type="text/javascript">
    $(function () {
        $('.preview').hover(function () {
            var uid = $(this).attr("data-id");
            $.ajax({
                type: 'POST',
                url: "<?php echo U('Admin/Users/getAuthInfo');?>",
                dataType: 'json',
                data: {'uid':uid},
                success:function (data) {
                    if(data.status==1){
                        var strHtml = "";
                        strHtml += "<div class=\"tab-content\">";
                        strHtml += "<dl><dt>姓名：</dt>";
                        strHtml += "<dd>"+data.data['real_name']+"</dd>";
                        strHtml += "</dl>";
                        strHtml += "<dl><dt>身份证号：</dt>";
                        strHtml += "<dd>"+data.data['id_cardnum']+"</dd>";
                        strHtml += "</dl>";
                        strHtml += "<dl><dt>身份证扫描件：</dt>";
                        strHtml += "<dd>";
                        if(data.data['photoArr'].length>0){
                            var tempStr = "";
                            for(var i=0; i<data.data['photoArr'].length; i++){
                                tempStr += "<img stle=\"margin-right:15px;\" width=\"300\" height=\"300\" src=\"/somegood/Public/uploads/"+data.data['photoArr'][i]+"_r300x300\" />";
                            }
                            strHtml += tempStr;
                        }
                        strHtml += "</dd>";
                        strHtml += "</dl>";
                        strHtml += "</div>";
                        $('#pop-box').append(strHtml);
                        $('#pop-box').addClass('pop-box');
                        $('#pop-box').stop().fadeIn(500);
                    }
                }
            });
        },function(){
            $('#pop-box').fadeOut(500);
            $('#pop-box').html('');
            $('#pop-box').removeClass('pop-box');
        });
    });
</script>
</body>
</html>