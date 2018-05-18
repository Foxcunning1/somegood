<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>编辑商品</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" src="/somegood/Public/scripts/webuploader/webuploader.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/laymain.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/kindeditor/kindeditor-all.js"></script>
    <script type="text/javascript" src="/somegood/Public/statics/admin/js/uploader.js"></script>
    <script type="text/javaScript">
        var url = "<?php echo U('Admin/SellerDelivery/getRegionList');?>";
        /*
         *城市三级联动
         */
        function getRegionList(objBox,typId,url){
            if(typId!=''){
                $.ajax({
                    url:url,
                    type:"post",
                    dataType:'json',
                    data:{id:typId},
                    success:function(data){
                        $("#"+objBox).html('<option value="" selected>请选择...</option>');
                        if(objBox=="selCities"){
                            $("#selDistricts").html('<option value="" selected>请选择...</option>');
                        }
                        $.each(data.data,function(idx,item){
                           $("<option value="+item.id+">"+item.name+"</option>").appendTo($("#"+objBox));
                        });
                    }
                });
            }
        }
        var uploadType = 'image';
        $(function(){
            //初始化编辑器
            var editorMini = KindEditor.create('.editor', {
                width: '100%',
                height: '250px',
                filterMode: false, //默认不过滤HTML
                resizeType: 1,
                uploadJson: "<?php echo U('Base/uploadFile',array('action' => 'EditorFile'));?>",
                fileManagerJson: '/somegood/Public/kindeditor/php/file_manager_json.php',
                allowFileManager: true
            });
            //初始化表单验证
            $("#theForm").initValidform();
            //初始化上传控件
            $(".upload-img").InitUploader({modeltype:'goods_type', sendurl: "<?php echo U('Admin/Base/uploadFile');?>", swf: "/somegood/Public/scripts/webuploader/uploader.swf"});
            $(".upload-album").InitUploader({ modeltype:'goods_type',btntext: "批量上传", multiple: true, sendurl: "<?php echo U('Admin/Base/uploadFile');?>", swf: "/somegood/Public/scripts/webuploader/uploader.swf" });
            //设置封面图片的样式
            $(".photo-list ul li .img-box img").each(function () {
                if ($(this).attr("src") == $("#hidFocusPhoto").val()) {
                    $(this).parent().addClass("selected");
                }
            });

        })
    </script>
</head>

<body class="mainbody">

    <!--导航栏-->
    <div class="location">
        <a href="javascript:history.back(-1);" class="back"><i class="iconfont icon-up"></i><span>返回上一页</span></a>
        <a href="<?php echo U('Index/center');?>" class="home"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <span><?php if(($type) == "delivery"): ?>新增投放店铺<?php else: ?>详情<?php endif; ?></span>
    </div>
    <div class="line10"></div>
    <!--/导航栏-->

    <!--内容-->
    <div id="floatHead" class="content-tab-wrap">
        <div class="content-tab">
            <div class="content-tab-ul-wrap">
                <ul>
                    <?php switch($type): case "edit": ?><li><a class="selected" href="javascript:void(0);">基本选项</a></li>
                            <li><a href="javascript:void(0);">详细</a></li><?php break;?>
                        <?php case "add": ?><li><a class="selected" href="javascript:void(0);">基本选项</a></li>
                            <li><a href="javascript:void(0);">详细</a></li><?php break;?>
                        <?php case "delivery": ?><li><a href="javascript:void(0);">增加投放店铺</a></li><?php break; endswitch;?>
                </ul>
            </div>
        </div>
    </div>
    <form method="post" action="<?php echo U('Admin/SellerDelivery/addDelivery');?>" id="theForm">
        <input type="hidden" name="sg_id" value="<?php echo ($id); ?>" />
        <?php if(($type) != "delivery"): ?><div class="tab-content">
                <dl>
                    <dt>商品名称</dt>
                    <dd><input name="data[goods_title]" type="text" value="<?php echo ($info["goods_title"]); ?>" id="txtGoodsName" class="input normal" datatype="*"/> <span class="Validform_checktip">*商品名称，100字符内</span></dd>
                </dl>
                <dl>
                    <dt>所属卖家</dt>
                    <dd>
                        <?php echo ($info["company_name"]); ?>
                        <input type="hidden" name="data[user_id]" value="<?php echo ($user_id); ?>">
                    </dd>
                </dl>
                <dl>
                    <dt>商品类别</dt>
                    <dd>
                        <div class="rule-single-select">
                        <select name="data[category_id]" id="goodsNameStyle" datatype="*" class="input normal" datatype="*">
                            <option value="">请选择类别</option>
                            <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($item['id'] == $info['category_id']): ?>selected="selected"<?php endif; ?> value="<?php echo ($item["id"]); ?>"><?php if(($item["count"]) != "1"): echo (fill_up_string($item['count'],"")); ?>├<?php endif; ?> <?php echo ($item["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        </div>
                    </dd>
                </dl>
                <dl>
                    <dt>商品价</dt>
                    <dd><input type="text" name="data[price]" value="<?php echo ($info["price"]); ?>"  datatype="/(^[1-9]\d*(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/" errormsg="请填写正确的价格" class="input small" />元</dd>
                </dl>
                <dl>
                    <dt>市场价</dt>
                    <dd><input type="text" name="data[market_price]" value="<?php echo ($info["market_price"]); ?>"  datatype="/(^[1-9]\d*(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/" errormsg="请填写正确的价格" class="input small" />元</dd>
                </dl>
                <dl>
                    <dt>重量</dt>
                    <dd><?php if(($info["status"]) < "1"): ?><input type="text" name="data[heavy]" value="<?php echo ($info["heavy"]); ?>"  datatype="/(^[1-9]\d*(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/" errormsg="请输入正确的重量" class="input small" /><?php else: echo ($info["heavy"]); endif; ?>Kg</dd>
                </dl>
                <dl>
                    <dt>长</dt>
                    <dd><?php if(($info["status"]) < "1"): ?><input type="text" name="data[length]" value="<?php echo ($info["length"]); ?>"  datatype="/(^[1-9]\d*(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/" errormsg="请填写正确的长度" class="input small" /><?php else: echo ($info["length"]); endif; ?>mm</dd>
                </dl>
                <dl>
                    <dt>宽</dt>
                    <dd><?php if(($info["status"]) < "1"): ?><input type="text" name="data[width]" value="<?php echo ($info["width"]); ?>"  datatype="/(^[1-9]\d*(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/" errormsg="请填写正确的宽度" class="input small" /><?php else: echo ($info["width"]); endif; ?>mm</dd>
                </dl>
                <dl>
                    <dt>高</dt>
                    <dd><?php if(($info["status"]) < "1"): ?><input type="text" name="data[height]" value="<?php echo ($info["height"]); ?>"  datatype="/(^[1-9]\d*(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/" errormsg="请填写正确的高度" class="input small" /><?php else: echo ($info["height"]); endif; ?>mm</dd>
                </dl>
                <dl>
                    <dt>状态</dt>
                    <dd>
                        <div class="rule-multi-radio">
                            <?php switch($info["status"]): case "0": ?>待投放<?php break;?>
                                <?php case "1": ?>投放中<?php break;?>
                                <?php case "2": ?>投放完成<?php break;?>
                                <?php default: ?>新增<?php endswitch;?>
                        </div>
                    </dd>
                </dl>
            </div>
            <div class="tab-content" style="display:none">
                <dl>
                    <dt>商品缩略图：</dt>
                    <dd>
                        <input name="data[goods_thumb]" type="text" id="txtGoodsThumb" class="input normal upload-path" value="<?php echo ($info["goods_thumb"]); ?>" />
                        <div class="upload-box upload-img"></div>
                    </dd>
                </dl>
                <dl id="div_albums_container">
                    <dt>图片相册</dt>
                    <dd>
                        <div class="upload-box upload-album"></div>
                        <input name="hidFocusPhoto" type="hidden" id="hidFocusPhoto" class="focus-photo" value="<?php echo ($info["goods_thumb"]); ?>" />
                        <div class="photo-list">
                            <ul>
                                <?php if(is_array($info["plist"])): $i = 0; $__LIST__ = $info["plist"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                                        <input type="hidden" name="photo[]" value="<?php echo ($vo); ?>" />
                                        <input type="hidden" name="rphoto[]" value="<?php echo ($info["rlist"]["$k"]); ?>" />
                                        <div class="img-box" onClick="setFocusImg(this);">
                                            <img src="/somegood/Public/uploads/<?php echo ($vo); ?>_c120x120" bigsrc="/somegood/Public/uploads/<?php echo ($vo); ?>" realpath="<?php echo ($vo); ?>" />
                                            <span class="remark"><i><?php if($info.rlist.$key == ''): ?>暂无描述<?php else: echo ($info["rlist"]["$key"]); endif; ?></i></span>
                                        </div>
                                        <a href="javascript:;" onClick="setRemark(this);">描述</a>
                                        <a href="javascript:;" onClick="delImg(this);">删除</a>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </div>
                    </dd>
                </dl>
                <dl>
                    <dt>商品简介</dt>
                    <dd>
                        <textarea name="data[content]" id="txtBrief"  class="input"><?php echo ($info["content"]); ?></textarea>
                    </dd>
                </dl>
                <dl>
                    <dt>商品参数</dt>
                    <dd>
                        <textarea name="data[params]" id="txtParams" class="editor" style="visibility:hidden;"><?php echo ($info["params"]); ?></textarea>
                    </dd>
                </dl>
                <dl>
                    <dt>商品详情</dt>
                    <dd>
                        <textarea name="data[details]" id="txtContent" class="editor" style="visibility:hidden;"><?php echo ($info["details"]); ?></textarea>
                    </dd>
                </dl>
            </div><?php endif; ?>
        <?php if(($type) == "delivery"): ?><div class="tab-content">
                <dl>
                    <dt>商品类别</dt>
                    <dd>
                        <div class="rule-mule-select" style="float:left;margin-left: 5px;">
                        <span>国家： </span>
                        <select name="country" id="selCountries" size="10" class="input small">
                            <option selected="selected" value="0">中国</option>
                        </select>
                        </div>
                        
                        <div class="rule-mule-select" style="float:left;margin-left: 5px;">
                        <span>省份： </span>
                        <select name="province" id="selProvinces" onchange="getRegionList('selCities',this.value,url)" size="10" class="input small">
                            <option value="" selected>请选择...</option>
                            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option value="<?php echo ($item["id"]); ?>"><?php echo ($item["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        </div>
                        
                        <div class="rule-mule-select" style="float:left;margin-left: 5px;">
                        <span>城市： </span>
                        <select name="city" id="selCities" onchange="getRegionList('selDistricts',this.value,url)" size="10" class="input small">
                            <option value="" selected>请选择...</option>
                        </select>
                        </div>
                        
                        <div class="rule-mule-select" style="float:left;margin-left: 5px;">
                        <span>区/县：</span>
                        <select name="district" id="selDistricts" size="10" class="input small">
                            <option value="" selected>请选择...</option>
                        </select>
                        </div>

                        <div class="rule-mule-select" style="float:left;margin-left: 5px;">
                        <span>店铺分类：</span>
                        <select name="store_cate" id="store_cate" size="10" class="input small">
                            <option value="" selected>请选择...</option>
                            <?php if(is_array($type_list)): $i = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($tl["id"]); ?>"><?php echo ($tl["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        </div>

                        <span style="float:left;margin-left: 5px;"><input type="button" value="查询店铺" class="btn" onclick="selectStore()" /></span>
                    </dd>
                </dl>
                <dl>
                    <dt></dt>
                    <dd>
                        <table id="goodsList" class="tableList">
                            <tbody>
                            <tr>
                                <td>
                                    <select name="type" id="goods_list" class="input select" style="width:300px;height:300px;" size="7"></select>
                                </td>
                                <td>
                                    投放数量
                                    <input name="delivery_num" type="text" value="10"  datatype="n" errormsg="请填写正确的投放数量" class="input small" />
                                    <br><br>
                                    有效期
                                    <input name="month" type="text" value="1" datatype="n" errormsg="请填写有效期" class="input small" />个月
                                    <input type="hidden" name="data[price]" value="<?php echo ($info["price"]); ?>">
                                    <input type="hidden" name="data[seller_id]" value="<?php echo ($info["user_id"]); ?>">
                                    <input type="hidden" name="data[market_price]" value="<?php echo ($info["market_price"]); ?>">
                                    <input type="hidden" name="data[category_id]" value="<?php echo ($info["category_id"]); ?>">
                                    <input type="hidden" name="data[goods_title]" value="<?php echo ($info["goods_title"]); ?>">
                                    <input type="hidden" name="data[goods_thumb]" value="<?php echo ($info["goods_thumb"]); ?>">
                                    <br><br>
                                    <input value="添加到列表 &gt;&gt;" class="btn gBtn" id="addPackageBtn" onclick="manageSelectItem('update', 'goods_list', 'package_list', 'addPackageBtn', 'relatedGoodsId',1,this.form.elements['delivery_num'].value,this.form.elements['month'].value);" type="button">
                                    <br><br>
                                    <input value="从列表删除 &lt;&lt;" class="btn gBtn" id="delPackageBtn" onclick="manageSelectItem('delete', 'goods_list', 'package_list', 'delPackageBtn', 'relatedGoodsId');" type="button">
                                </td>
                                <td>
                                    <select name="pagekage_list" id="package_list" class="input select" style="width:300px;height:300px;" multiple="multiple"></select>
                                    <input type="hidden" name="storeId_month_deliveryNum" id="relatedGoodsId" value="<?php echo ($info["related_goods_ids"]); ?>"/>
                                </td>
                            </tr>
                            <tbody>
                        </table>
                    </dd>
                </dl>
            </div><?php endif; ?>



    <!--/内容-->

    <!--工具栏-->
    <div class="page-footer">
        <div class="btn-wrap">
            <input type="hidden" name="type" value="<?php echo ($type); ?>">
            <?php if(($type) == "add"): ?><input type="hidden" name="data[user_id]" value="<?php echo ($user_id); ?>"><?php endif; ?>
            <?php if(($type) == "delivery"): ?><input type="hidden" name="seller_id" value="<?php echo ($info["user_id"]); ?>"><?php endif; ?>
            <input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>">
            <input type="submit" name="btnSubmit" value="提交保存" id="btnSubmit" class="btn" />
            <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back();" />
        </div>
    </div>
    <!--/工具栏-->

</form>

    <script>
        /**
         * 查询店铺
         */
        function selectStore()
        {
            var selCountry  = document.forms['theForm'].elements['country'];
            var selProvince = document.forms['theForm'].elements['province'];
            var selCity     = document.forms['theForm'].elements['city'];
            var selDistrict = document.forms['theForm'].elements['district'];
            var selStore = document.forms['theForm'].elements['store_cate'];
            var regionCell  = document.getElementById("regionCell");

            if (selDistrict.selectedIndex > 0)
            {
                regionId = selDistrict.options[selDistrict.selectedIndex].value;
                regionName = selDistrict.options[selDistrict.selectedIndex].text;
            }
            else
            {
                if (selCity.selectedIndex > 0)
                {
                    regionId = selCity.options[selCity.selectedIndex].value;
                    regionName = selCity.options[selCity.selectedIndex].text;
                }
                else
                {
                    if (selProvince.selectedIndex > 0)
                    {
                        regionId = selProvince.options[selProvince.selectedIndex].value;
                        regionName = selProvince.options[selProvince.selectedIndex].text;
                    }
                    else
                    {
                        if (selCountry.selectedIndex >= 0)
                        {
                            regionId = selCountry.options[selCountry.selectedIndex].value;
                            regionName = selCountry.options[selCountry.selectedIndex].text;
                        }
                        else
                        {
                            return;
                        }
                    }
                }
            }

            if (selStore.selectedIndex >= 0)
            {
                storeTypeId = selStore.options[selStore.selectedIndex].value;
            }

            $.ajax({
                type: "POST",
                url: "<?php echo U('Admin/SellerDelivery/getStore');?>",
                data: {
                    "id":<?php echo ($id); ?>,
                    "region":regionId,
                    "storeType":storeTypeId,
                    "length":<?php echo ($info['length']); ?>,
                    "width":<?php echo ($info['width']); ?>,
                    "height":<?php echo ($info['height']); ?>,
                },
                dataType: 'json',
                success: function(data){
                    if(data.status==1){
                        var str='';
                        $.each(data.data,function(index, vo) {
                            str +="<option value='"+vo.sid+"' data-value='"+vo.code+"' data-box='"+vo.box_id+"'>"+vo.store_name+"</option>";
                        })
                        $("#goods_list").html(str);
                    }
                }
            });
        }
    </script>

</body>
</html>