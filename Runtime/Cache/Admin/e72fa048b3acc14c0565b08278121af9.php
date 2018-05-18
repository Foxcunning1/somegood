<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>店铺信息审核</title>
    <link href="/somegood/Public/scripts/artdialog/ui-dialog.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link href="/somegood/Public/statics/admin/css/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
</head>

<body class="mainbody">
<form method="post" action="<?php echo U('add');?>" id="wantForm">
    <!--导航栏-->
    <div class="location">
        <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <span>卖家信息审核</span>
    </div>
    <div class="line10"></div>
    <!--/导航栏-->

    <!--内容-->
    <div id="floatHead" class="content-tab-wrap">
        <div class="content-tab">
            <div class="content-tab-ul-wrap">
                <ul>
                    <li><a class="selected" href="javascript:void(0);">卖家信息</a></li>
                    <li><a href="javascript:void(0);">认证信息</a></li>
                    <!-- <li><a href="javascript:void(0);">坐标拾取</a></li> -->
                </ul>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <dl>
            <dt>公司名称</dt>
            <input type="hidden" name="sid" value="<?php echo ($info["sid"]); ?>"/>
            <dd><input name="data[store_name]" type="text" value="<?php echo ($info["company_name"]); ?>" id="txtTitle" class="input normal" datatype="*1-100"/> <span class="Validform_checktip">*公司中文名称</span></dd>
        </dl>
        <dl>
            <dt>推广方式</dt>
            <input type="hidden" name="sid" value="<?php echo ($info["counts"]); ?>"/>
            <dd><input name="data[spread_way]" type="text" value="<?php if($info['spread_way'] == '0' ): ?>广告牌推广<?php elseif($info['spread_way'] == '1' ): ?>产品上架推广<?php elseif((string)$info['spread_way'] == (string)'2' ): ?>广告牌推广+产品上架推广<?php else: endif; ?>" id="txtTitle" class="input normal" datatype="*1-100"/> </dd>
        </dl>
        <dl>
            <dt>联系人</dt>
            <dd><input name="data[store_name]" type="text" value="<?php echo ($info["name"]); ?>" id="txtTitle" class="input normal" datatype="*1-100"/> </dd>
        </dl>
        <dl>
            <dt>联系方式</dt>
            <dd><input name="data[mobile]" type="text" value="<?php echo ($info["mobile"]); ?>"  class="input normal" datatype="*1-100"/></dd>
        </dl>
        <dl>
            <dt>Email</dt>
            <dd><input name="data[email]" type="text" value="<?php echo ($info["email"]); ?>"  class="input normal" datatype="*1-100"/></dd>
        </dl>
        <dl>
            <dt>品牌类别</dt>
            <dd>
                <div >
                <span id="rblCate"><?php echo ($info["category"]); ?></span>
                </div>
            </dd>
        </dl>

        <!-- <div class="rule-multi-radio class="rule-multi-porp""> -->
        <dl>
            <dt>详细地址</dt>
            <dd><input name="data[address]" type="text" value="<?php echo ($region); echo ($info["address"]); ?>"  class="input normal" datatype="*1-100"/> <span class="Validform_checktip">*详细地址</span></dd>
        </dl>
        <dl>
            <dt>状态</dt>
            <dd>
                <div >
                    <span id="rblStatus">
                        <?php if($info['is_seller'] == 1): ?>已通过<?php elseif($info['is_seller'] == 2): ?>已拒绝<?php else: ?>未审核<?php endif; ?>
                    </span>
                </div>
            </dd>
        </dl>
        <script>
            function checkStatus(status){
                if(status==3){
                    $('#error_reason').show();
                }else{
                    $('#error_reason').hide();
                }
            }
        </script>
        <dl id="error_reason" style="display: none">
            <dt>原因</dt>
            <dd>
                <textarea name="error_reason"  cols="50" rows="10"><?php echo ($info["error_reason"]); ?></textarea>
                <span class="Validform_checktip">*审核失败原因</span>
            </dd>
        </dl>
    </div>
    <div class="tab-content" style="display:none">
        <dl>
            <dt>商家姓名</dt>
            <dd><?php echo ($info["name"]); ?></dd>
        </dl>
        <dl>
            <dt>手机号</dt>
            <dd><?php echo ($info["mobile"]); ?></dd>
        </dl>
        <dl>
            <dt>身份证号</dt>
            <dd><?php echo ($info["id_cardnum"]); ?></dd>
        </dl>
        <dl>
            <dt>身份证正面</dt>
            <dd>
                <?php if($info['idcard_z']): ?><img width="80" height="65" src="/somegood/Public/uploads/<?php echo ($info['idcard_z']); ?>_c100x100" /><?php else: ?>
                    <i class="iconfont icon-pic" style="font-size: 44px; color:#ddd;"></i><?php endif; ?>
            </dd>
        </dl>
        <dl>
            <dt>身份证反面</dt>
            <dd>
                <?php if($info['idcard_f']): ?><img width="80" height="65" src="/somegood/Public/uploads/<?php echo ($info['idcard_f']); ?>_c100x100" /><?php else: ?>
                    <i class="iconfont icon-pic" style="font-size: 44px; color:#ddd;"></i><?php endif; ?>
            </dd>
        </dl>
    </div>
    <div class="tab-content">
        <dl>
            <dt>地图拾取</dt>
            <dd>
                <input name="coordinate" type="text" id="txtCoordinate" value="<?php if($info['lng'] != '' and $info['lat'] != ''): echo ($info["lng"]); ?>,<?php echo ($info["lat"]); endif; ?>" class="input normal" />
            </dd>
        </dl>
        <dl>
            <dd>
                <iframe height="960" width="100%" id="mapIframe" src="http://api.map.baidu.com/lbsapi/getpoint/index.html?qq-pf-to=pcqq.group"></iframe>
            </dd>
        </dl>
    </div>
    <!--/内容-->

    <!--工具栏-->
    <div class="page-footer">
        <div class="btn-wrap">
            <input type="hidden" name="user_id" value="<?php echo ($info["user_id"]); ?>" />
            <input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>">
            <input type="button"  value="通过" id="btnSubmit1" class="btn"  onclick="doAjaxEvent($(this))" />
            <input type="button"  value="拒绝" id="btnSubmit2" class="btn"  onclick="doAjaxEvent($(this))" />
            <input type="button"  value="禁用" id="btnSubmit3" class="btn"  onclick="doAjaxEvent($(this))" />
            <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back();" />
        </div>
    </div>
    <!--/工具栏-->

</form>
<script type="text/javascript">
        var user_id =$(".btn-wrap input").eq(0).val();
        function doAjaxEvent(obj) {
          if (obj.val() == '通过') {
              var pass = '1';
          }else if (obj.val() == '拒绝') {
              var pass = '2';
          }else {
              var pass = '3';
          }
          $.ajax({
          type: "POST",
          dataType:'json',
          url:"<?php echo U('Admin/Seller/detail');?>",
          data:{
              pass:pass,
              id:user_id
          },
          error: function(request) {
              alert('ajax error!');
          },
          success: function(data) {
              alert(data.info);
              window.location.href="<?php echo ($returnUrl); ?>";
          }
          });
        }



    $(function(){
        /*修复百度地图在不可视的选项卡中无法显示*/
        $("#mapIframe").load(function(){
            $(".tab-content").eq(2).hide();
        });
    })
</script>
</body>
</html>