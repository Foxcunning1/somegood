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
        <span>店铺信息审核</span>
    </div>
    <div class="line10"></div>
    <!--/导航栏-->

    <!--内容-->
    <div id="floatHead" class="content-tab-wrap">
        <div class="content-tab">
            <div class="content-tab-ul-wrap">
                <ul>
                    <li><a class="selected" href="javascript:void(0);">店铺信息</a></li>
                    <li><a href="javascript:void(0);">认证信息</a></li>
                    <li><a href="javascript:void(0);">坐标拾取</a></li>
                    <li><a href="javascript:void(0);">货柜格子</a></li>
                    <li><a href="javascript:void(0);">广告位</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <dl>
            <dt>店铺名称</dt>
            <input type="hidden" name="sid" value="<?php echo ($info["sid"]); ?>"/>
            <dd><input name="data[store_name]" type="text" value="<?php echo ($info["store_name"]); ?>" id="txtTitle" class="input normal" datatype="*1-100"/> <span class="Validform_checktip">*店铺中文名称</span></dd>
        </dl>
        <dl>
            <dt>店铺别名</dt>
            <dd><input name="data[alias_name]" type="text" value="<?php echo ($info["alias_name"]); ?>"  class="input normal" datatype="*1-100"/></dd>
        </dl>
        <dl>
            <dt>LOGO</dt>
            <dd>
                <?php if($info['logo']): ?><img width="64" height="64" src="/somegood/Public/uploads/<?php echo ($info['logo']); ?>_c100x100" /><?php else: ?>
                    <i class="iconfont icon-user-full" style="font-size: 34px; color:#ddd;"></i><?php endif; ?>
            </dd>
        </dl>
        <dl>
            <dt>店铺手机号</dt>
            <dd><input name="data[mobile]" type="text" value="<?php echo ($info["mobile"]); ?>"  class="input normal" datatype="*1-100"/></dd>
        </dl>
        <dl>
            <dt>QQ</dt>
            <dd><input name="data[qq]" type="text" value="<?php echo ($info["qq"]); ?>"  class="input normal" datatype="*1-100"/></dd>
        </dl>
        <dl>
            <dt>Email</dt>
            <dd><input name="data[email]" type="text" value="<?php echo ($info["email"]); ?>"  class="input normal" datatype="*1-100"/></dd>
        </dl>
        <?php if(($action) == "edit"): ?><dl>
                <dt>申请时间</dt>
                <dd><?php echo (date('Y-m-d H:i:s',$info["reg_time"])); ?></dd>
            </dl><?php endif; ?>
        <dl>
            <dt>店铺类型</dt>
            <dd>
                <div class="rule-multi-radio">
                    <span id="rblStatus1">
                        <?php if(is_array($storeTypeList)): $i = 0; $__LIST__ = $storeTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$st): $mod = ($i % 2 );++$i;?><input id="rblStatus_<?php echo ($key); ?>"  type="radio" name="data[store_type]" value="<?php echo ($st["id"]); ?>" <?php if($info["store_type"] == $st['id']): ?>checked="checked"<?php endif; ?> /><label for="rblStatus_<?php echo ($key); ?>"><?php echo ($st["type_name"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
                    </span>
                </div>
            </dd>
        </dl>
        <dl>
            <dt>所属地区：</dt>
            <dd>
                <div class="rule-single-select">
                    <select name="province_id" id="province" onchange="loadRegion('province',2,'city','<?php echo U('Admin/Ajax/getRegionList');?>');"  class="select">
                        <option value="0">省份/直辖市</option>
                        <?php if(is_array($provinceList)): $i = 0; $__LIST__ = $provinceList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if($provinceId == $v['id']): ?>selected="selected"<?php endif; ?> ><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <div class="rule-single-select">
                    <select name="city" id="city" class="select" onchange="loadRegion('city',3,'town','<?php echo U('Admin/Ajax/getRegionList');?>');">
                        <option value="0">请选择</option>
                        <?php if(is_array($cityList)): $i = 0; $__LIST__ = $cityList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($cityId == $vo['id']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <div class="rule-single-select">
                    <select name="data[area_id]" id="town" class="select" datatype="*">
                        <option value="0">请选择</option>
                        <?php if(is_array($townList)): $i = 0; $__LIST__ = $townList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voo["id"]); ?>" <?php if($info['area_id'] == $voo['id']): ?>selected="selected"<?php endif; ?>><?php echo ($voo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </dd>
        </dl>
        <dl>
            <dt>地址</dt>
            <dd><input name="data[address]" type="text" value="<?php echo ($info["address"]); ?>"  class="input normal" datatype="*1-100"/> <span class="Validform_checktip">*详细地址</span></dd>
        </dl>
        <dl>
            <dt>状态</dt>
            <dd>
                <div class="rule-multi-radio">
                    <span id="rblStatus">
                        <?php if(is_array($status_list)): $i = 0; $__LIST__ = $status_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><input id="rblStatus_<?php echo ($key); ?>"  type="radio" name="data[status]" value="<?php echo ($key); ?>" onclick="checkStatus(this.value);" <?php if($info["status"] == $key): ?>checked="checked"<?php endif; ?> /><label for="rblStatus_<?php echo ($key); ?>"><?php echo ($item); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
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
        <dl>
            <dt>是否推荐</dt>
            <dd>
                <div class="rule-multi-radio">
                    <input id="is_rec_1" type="radio" name="data[is_rec]" value="1" <?php if(($info["is_rec"]) == "1"): ?>checked="checked"<?php endif; ?> /><label for="is_rec_1">是</label>
                    <input id="is_rec_0" type="radio" name="data[is_rec]" value="0" <?php if(($info["is_rec"]) == "0"): ?>checked="checked"<?php endif; ?> /><label for="is_recs_0">否</label>
                </div>
            </dd>
        </dl>
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
            <dt>店主姓名</dt>
            <dd><?php echo ($info["owner"]); ?>【<?php echo ($info["user_mobile"]); ?>】</dd>
        </dl>
        <dl>
            <dt>手机号</dt>
            <dd><?php echo ($info["mobile"]); ?></dd>
        </dl>
        <dl>
            <dt>身份证号</dt>
            <dd><?php echo ($info["idcard"]); ?></dd>
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
    <div class="tab-content" style="display:none">
        <dl>
            <dt>申请数量</dt>
            <dd><input type="number" id="box_num" class="input small" name="data[box_num]" value="<?php echo ($info["box_num"]); ?>">个</dd>
        </dl>
        <dl>
            <dt>当前已设置</dt>
            <dd id="box_count"><?php echo ($box["count"]); ?>个</dd>
        </dl>
        <dl id="boxAddButton">
            <dt>添加</dt>
            <dd>
                <div class="rule-single-select">
                    <select id="boxSize">
                        <?php if(is_array($boxSize)): $i = 0; $__LIST__ = $boxSize;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bs): $mod = ($i % 2 );++$i;?><option value="<?php echo ($bs["id"]); ?>"><?php echo ($bs["l"]); ?>*<?php echo ($bs["w"]); ?>*<?php echo ($bs["h"]); ?>mm³</option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <input type="button" class="btn" value="添加" onclick="addBox();">
            </dd>
        </dl>
        <dl>
            <dt>当前格子</dt>
            <dd>
                <table width="40%" border="0" cellspacing="0" cellpadding="0" class="ltable">
                    <tbody>
                    <tr>
                        <th width="10%">编码</th>
                        <th width="20%">规格</th>
                        <th width="10%">操作</th>

                    </tr>
                    </tbody>
                    <tbody id="box_list">
                    <?php if(is_array($box["list"])): $i = 0; $__LIST__ = $box["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bl): $mod = ($i % 2 );++$i;?><tr id="box_<?php echo ($bl["id"]); ?>">
                            <th width="10%"><?php echo ($bl["sn"]); ?></th>
                            <th width="20%"><?php echo ($bl["l"]); ?>*<?php echo ($bl["w"]); ?>*<?php echo ($bl["h"]); ?>mm³</th>
                            <th width="10%"><?php if(($bl["is_lock"]) == "0"): ?><a href="javascrit:;" onclick="delBox(<?php echo ($bl["id"]); ?>)">删除</a><?php else: ?>已锁定<?php endif; ?></th>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </dd>
        </dl>

    </div>
    <div class="tab-content" style="display:none">
        <dl>
            <dt>当前已设置</dt>
            <dd id="pos_count"><?php echo ($pos["count"]); ?>个</dd>
        </dl>
        <dl id="posAddButton">
            <dt>添加</dt>
            <dd>
                <div class="rule-single-select">
                    <select id="storePos">
                        <?php if(is_array($storePos)): $i = 0; $__LIST__ = $storePos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?><option value="<?php echo ($p["id"]); ?>"><?php echo ($p["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <input type="button" class="btn" value="添加" onclick="addPos();">
            </dd>
        </dl>
        <dl>
            <dt>当前广告位</dt>
            <dd>
                <table width="40%" border="0" cellspacing="0" cellpadding="0" class="ltable">
                    <tbody>
                        <tr>
                            <th width="10%">ID</th>
                            <th width="20%">规格</th>
                            <th width="10%">操作</th>
                            
                        </tr>
                    </tbody>
                    <tbody id="pos_list">
                    <?php if(is_array($pos["list"])): $i = 0; $__LIST__ = $pos["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pl): $mod = ($i % 2 );++$i;?><tr id="pos_<?php echo ($pl["id"]); ?>">
                            <th width="10%"><?php echo ($pl["id"]); ?></th>
                            <th width="20%"><?php echo ($pl["title"]); ?>---<?php echo ($pl["width"]); ?>*<?php echo ($pl["height"]); ?>mm</th>
                            <th width="10%"><?php if(($pl["is_lock"]) == "0"): ?><a href="javascrit:;" onclick="delPos(<?php echo ($pl["id"]); ?>)">删除</a><?php else: ?>已锁定<?php endif; ?></th>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </dd>
        </dl>

    </div>

    <!--/内容-->

    <!--工具栏-->
    <div class="page-footer">
        <div class="btn-wrap">
            <input type="hidden" name="id" value="<?php echo ($info["sid"]); ?>" />
            <input type="hidden" name="user_id" value="<?php echo ($info["user_id"]); ?>" />
            <input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>">
            <input type="hidden" name="old_status" value="<?php echo ($info["status"]); ?>">
            <input type="hidden" name="mobile" value="<?php echo ($info["mobile"]); ?>">
            <input type="hidden" name="owner" value="<?php echo ($info["owner"]); ?>">
            <input type="hidden" name="action" value="<?php echo ($action); ?>">
            <input type="hidden" name="delIds" id="delIds">
            <input type="hidden" name="delPosIds" id="delPosIds">
            <input type="submit" name="btnSubmit" value="提交保存" id="btnSubmit" class="btn" />
            <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back();" />
        </div>
    </div>
    <!--/工具栏-->

</form>
<script type="text/javascript">
    $(function(){
        /*修复百度地图在不可视的选项卡中无法显示*/
        $("#mapIframe").load(function(){
            $(".tab-content").eq(2).hide();
        });
    })
    var delIds="";
    function delBox(id) {
         $("#box_"+id).remove();
         delIds +=","+id;
         $("#delIds").val(delIds)
    }
    var num=1;
    function addBox() {
        var box_num=$("#box_num").val();
        var chked = $("#box_list tr").length;
        if(chked<box_num){
            var id=$("#boxSize option:selected").val();
            var v=$("#boxSize option:selected").text();
            $("#box_list").append("<tr> <th width='10%'>新增格子" + num + "<input type='hidden' name='box_size_list[]' value='" + id + "' /> </th> <th width='20%'>" + v + "</th> <th width='10%'><a href='javascrit:;' onclick='del(this)'>删除</a></th> </tr>");
            num++;
        }else{
            alert("已达到该店铺申请格子数量")
        }
    }


    /***店铺广告位操作*****/

    var delPosIds="";
    function delPos(id) {
        $("#pos_"+id).remove();
        delPosIds +=","+id;
        $("#delPosIds").val(delPosIds)
    }

    var posNum=1;
    function addPos() {
        var id=$("#storePos option:selected").val();
        var v=$("#storePos option:selected").text();
        $("#pos_list").append("<tr> <th width='10%'>新增广告位" + posNum + "<input type='hidden' name='adv_pos_list[]' value='" + id + "' /> </th> <th width='20%'>" + v + "</th> <th width='10%'><a href='javascrit:;' onclick='del(this)'>删除</a></th> </tr>");
        posNum ++;
    }
    function del(obj){
        obj.parentNode.parentNode.remove();
    }
</script>
</body>
</html>