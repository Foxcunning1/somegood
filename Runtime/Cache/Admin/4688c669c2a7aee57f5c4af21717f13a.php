<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>编辑内容</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="stylesheet" type="text/css" href="../css/base.css" />
    <link rel="stylesheet" type="text/css" href="../css/layout.css" />
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="../css/iconfont.css" />
    <script type="text/javascript" charset="utf-8" src="../js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="../js/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" charset="utf-8" src="../js/WdatePicker.js"></script>
    <script type="text/javascript" charset="utf-8" src="../js/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="../js/webuploader.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="../js/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="../js/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="../js/zh-cn.js"></script>
    <script type="text/javascript" charset="utf-8" src="../js/uploader.js"></script>
    <script type="text/javascript" charset="utf-8" src="../js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="../js/laymain.js"></script>
    <script type="text/javascript">
        $(function () {
            //初始化表单验证
            $("#form1").initValidform();

            //初始化编辑器
            $(".editor").each(function (i) {
                var objId = $(this).attr("id");
                if (objId != "undefined") {
                    var editor = UE.getEditor(objId, {
                        serverUrl: '../../../tools/upload_ajax.ashx',
                        initialFrameWidth: '100%',
                        initialFrameHeight: 350
                    });
                }
            });
            $(".editor-mini").each(function (i) {
                var objId = $(this).attr("id");
                if (objId != "undefined") {
                    var editorMini = UE.getEditor(objId, {
                        serverUrl: '../../../tools/upload_ajax.ashx',
                        initialFrameWidth: '100%',
                        initialFrameHeight: 250,
                        toolbars: [[
                            'fullscreen', 'source', '|', 'undo', 'redo', '|',
                            'bold', 'italic', 'underline', 'strikethrough', 'removeformat', 'pasteplain', '|', 'forecolor', 'insertorderedlist', 'insertunorderedlist', '|',
                            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                            'link', 'unlink', 'anchor', '|',
                            'simpleupload', 'insertimage', 'scrawl', 'insertvideo'
                        ]]
                    });
                }
            });

            //初始化上传控件
            $(".upload-img").InitUploader({ filesize: "10240", sendurl: "../../tools/upload_ajax.ashx", swf: "../../scripts/webuploader/uploader.swf", filetypes: "gif,jpg,jpeg,png,bmp,rar,zip,doc,xls,txt" });
            $(".upload-video").InitUploader({ filesize: "102400", sendurl: "../../tools/upload_ajax.ashx", swf: "../../scripts/webuploader/uploader.swf", filetypes: "flv,mp3,mp4,avi" });
            $(".upload-album").InitUploader({ btntext: "批量上传", multiple: true, water: true, thumbnail: true, filesize: "10240", sendurl: "../../tools/upload_ajax.ashx", swf: "../../scripts/webuploader/uploader.swf" });

            //设置封面图片的样式
            $(".photo-list ul li .img-box img").each(function () {
                if ($(this).attr("src") == $("#hidFocusPhoto").val()) {
                    $(this).parent().addClass("selected");
                }
            });

            //创建上传附件
            $(".attach-btn").click(function () {
                showAttachDialog();
            });

            //创建商品规格
            $(".spec-btn").click(function () {
                showSpecDialog();
            });

            //赋值规格市场价格
            $("#field_control_market_price").blur(function () {
                $("#div_spec__container").find("input[name='spec_market_price']").val($(this).val());
            });
            //赋值规格销售价格
            $("#field_control_sell_price").blur(function () {
                $("#div_spec__container").find("input[name='spec_sell_price']").val($(this).val());
            });
        });

        //初始化附件窗口
        function showAttachDialog(obj) {
            var objNum = arguments.length;
            var attachDialog = top.dialog({
                id: 'attachDialogId',
                title: "上传附件",
                url: 'dialog/dialog_article_attach.aspx',
                width: 500,
                height: 180,
                onclose: function () {
                    var liHtml = this.returnValue; //获取返回值
                    if (liHtml.length > 0) {
                        $("#showAttachList").children("ul").append(liHtml);
                    }
                }
            }).showModal();
            //如果是修改状态，将对象传进去
            if (objNum == 1) {
                attachDialog.data = obj;
            }
        }
        //删除附件节点
        function delAttachNode(obj) {
            $(obj).parent().remove();
        }

        //初始化规格窗口
        function showSpecDialog() {
            var d = top.dialog({
                id: 'specDialogId',
                padding: 0,
                title: "商品规格",
                url: 'dialog/dialog_article_spec.aspx?channel_id=2'
            }).showModal();
            //将容器对象传进去
            d.data = $("#item_box");
        }
        //初始化会员组价格窗口
        function showPriceDialog(obj) {
            var d = top.dialog({
                padding: 0,
                title: "会员组价格",
                url: 'dialog/dialog_group_price.aspx',
                width: 400
            }).showModal();
            //将对象传进去
            d.data = obj;
        }
        //计算出最小的市场价格
        function countMarketPrice(obj) {
            var objName = $(obj).attr("name");
            var minValue = parseFloat($(obj).val());
            $("input[name='" + objName + "']").each(function () {
                if ($(this).val().length > 0) {
                    if (parseFloat($(this).val()) < minValue) {
                        minValue = parseFloat($(this).val());
                    }
                }
            });
            $("#field_control_market_price").val(minValue);
        }
        //计算最小的销售价格
        function countSellPrice(obj) {
            var objName = $(obj).attr("name");
            var minValue = parseFloat($(obj).val());
            $("input[name='" + objName + "']").each(function () {
                if ($(this).val().length > 0) {
                    if (parseFloat($(this).val()) < minValue) {
                        minValue = parseFloat($(this).val());
                    }
                }
            });
            $("#field_control_sell_price").val(minValue);
        }
        //计算库存总数量
        function countStockQuantity(obj) {
            var objName = $(obj).attr("name");
            var countValue = 0;
            $("input[name='" + objName + "']").each(function () {
                if ($(this).val().length > 0) {
                    countValue += parseFloat($(this).val());
                }
            });
            $("#field_control_stock_quantity").val(countValue);
        }
    </script>
</head>

<body class="mainbody">
    <!--导航栏-->
    <div class="location">
        <a href="article_list.aspx?channel_id=2" class="back"><i class="iconfont icon-up"></i><span>返回列表页</span></a>
        <a href="../center.aspx"><i class="iconfont icon-home"></i><span>首页</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <a href="article_list.aspx?channel_id=2"><span>内容管理</span></a>
        <i class="arrow iconfont icon-arrow-right"></i>
        <span>编辑内容</span>
    </div>
    <div class="line10"></div>
    <!--/导航栏-->

    <!--内容-->
    <div id="floatHead" class="content-tab-wrap">
        <div class="content-tab">
            <div class="content-tab-ul-wrap">
                <ul>
                    <li><a class="selected" href="javascript:;">基本信息</a></li>

                    <li><a href="javascript:;">详细描述</a></li>
                    <li><a href="javascript:;">SEO选项</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <dl>
            <dt>所属类别</dt>
            <dd>
                <div class="rule-single-select">
                    <select name="ddlCategoryId" id="ddlCategoryId" datatype="*" sucmsg=" ">
                        <option value="">请选择类别...</option>
                        <option value="40">手机数码</option>
                        <option value="43">　├ 手机通讯</option>
                        <option value="44">　├ 摄影摄像</option>
                        <option value="45">　├ 存储设备</option>
                        <option value="41">电脑办公</option>
                        <option value="46">　├ 电脑整机</option>
                        <option selected="selected" value="47">　├ 外设产品</option>
                        <option value="48">　├ 办公打印</option>
                        <option value="42">影音娱乐</option>
                        <option value="49">　├ 平板电视</option>
                        <option value="50">　├ 音响DVD</option>
                        <option value="51">　├ 影音配件</option>

                    </select>
                </div>
            </dd>
        </dl>

        <dl>
            <dt>是否发布</dt>
            <dd>
                <div class="rule-single-checkbox">
                    <input id="cbStatus" type="checkbox" name="cbStatus" checked="checked" />
                </div>
                <span class="Validform_checktip">*不发布前台则无法显示</span>
            </dd>
        </dl>
        <dl>
            <dt>推荐类型</dt>
            <dd>
                <div class="rule-multi-checkbox">
                    <span id="cblItem"><input id="cblItem_0" type="checkbox" name="cblItem$0" checked="checked" value="1" /><label for="cblItem_0">允许评论</label><input id="cblItem_1" type="checkbox" name="cblItem$1" value="1" /><label for="cblItem_1">置顶</label><input id="cblItem_2" type="checkbox" name="cblItem$2" checked="checked" value="1" /><label for="cblItem_2">推荐</label><input id="cblItem_3" type="checkbox" name="cblItem$3" value="1" /><label for="cblItem_3">热门</label><input id="cblItem_4" type="checkbox" name="cblItem$4" value="1" /><label for="cblItem_4">幻灯片</label></span>
                </div>
            </dd>
        </dl>
        <dl>
            <dt>内容标题</dt>
            <dd>
                <input name="txtTitle" type="text" value="金士顿（Kingston） DataTraveler SE9 32GB 金属U盘" id="txtTitle" class="input normal" datatype="*2-100" sucmsg=" " />
                <span class="Validform_checktip">*标题最多100个字符</span>
            </dd>
        </dl>
        <dl id="div_sub_title">
            <dt><span id="div_sub_title_title">副标题</span></dt>
            <dd>
                <input name="field_control_sub_title" type="text" value="足够自信，欢迎比价后购买！" id="field_control_sub_title" class="input normal" datatype="*0-255" sucmsg=" " />
                <span id="div_sub_title_tip" class="Validform_checktip">可为空，最多255个字符</span>
            </dd>
        </dl>
        <dl>
            <dt>Tags标签</dt>
            <dd>
                <input name="txtTags" type="text" id="txtTags" class="input normal" datatype="*0-500" sucmsg=" " />
                <span class="Validform_checktip">多个可用英文逗号分隔开，如：a,b</span>
            </dd>
        </dl>
        <dl>
            <dt>封面图片</dt>
            <dd>
                <input name="txtImgUrl" type="text" id="txtImgUrl" class="input normal upload-path" />
                <div class="upload-box upload-img"></div>
            </dd>
        </dl>
        <dl id="div_goods_no">
            <dt><span id="div_goods_no_title">商品货号</span></dt>
            <dd>
                <input name="field_control_goods_no" type="text" value="SD3720019286" id="field_control_goods_no" class="input normal" datatype="*0-100" sucmsg=" " />
                <span id="div_goods_no_tip" class="Validform_checktip">允许字母、下划线，100个字符内</span>
            </dd>
        </dl>
        <dl id="div_stock_quantity">
            <dt><span id="div_stock_quantity_title">库存数量</span></dt>
            <dd>
                <input name="field_control_stock_quantity" type="text" value="83" id="field_control_stock_quantity" class="input small" datatype="n" sucmsg=" " />
                <span id="div_stock_quantity_tip" class="Validform_checktip">库存数量为0时显示缺货状态</span>
            </dd>
        </dl>
        <dl id="div_market_price">
            <dt><span id="div_market_price_title">市场价格</span></dt>
            <dd>
                <input name="field_control_market_price" type="text" value="99.00" id="field_control_market_price" class="input small" datatype="/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/" sucmsg=" " /> 元
                <span id="div_market_price_tip" class="Validform_checktip">市场的参与价格，不参与计算</span>
            </dd>
        </dl>
        <dl id="div_sell_price">
            <dt><span id="div_sell_price_title">销售价格</span></dt>
            <dd>
                <input name="field_control_sell_price" type="text" value="79.00" id="field_control_sell_price" class="input small" datatype="/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/" sucmsg=" " /> 元
                <span id="div_sell_price_tip" class="Validform_checktip">*出售的实际交易价格</span>
            </dd>
        </dl>
        <dl id="div_point">
            <dt><span id="div_point_title">交易积分</span></dt>
            <dd>
                <input name="field_control_point" type="text" value="0" id="field_control_point" class="input small" datatype="/^-?\d+$/" sucmsg=" " />
                <span id="div_point_tip" class="Validform_checktip">*正为返还，负为消费积分</span>
            </dd>
        </dl>
        <dl id="div_spec_container">
            <dt>商品规格</dt>
            <dd>
                <a class="icon-btn add spec-btn"><span>设置规格</span></a>
                <div class="table-container" style="padding-top:10px;">
                    <input type="hidden" name="hide_goods_spec_list" id="hide_goods_spec_list" value="[{&quot;channel_id&quot;:2,&quot;article_id&quot;:94,&quot;spec_id&quot;:8,&quot;parent_id&quot;:0,&quot;title&quot;:&quot;颜色&quot;,&quot;img_url&quot;:&quot;&quot;},{&quot;channel_id&quot;:2,&quot;article_id&quot;:94,&quot;spec_id&quot;:9,&quot;parent_id&quot;:8,&quot;title&quot;:&quot;白色&quot;,&quot;img_url&quot;:&quot;/upload/201503/25/201503251553231051.jpg&quot;}]" />
                    <table border="0" cellspacing="0" cellpadding="0" class="border-table" width="82%">
                        <thead>
                        <tr>
                            <th align="center" width="15%">货号</th>
                            <th width="8%">市场价(元)</th>
                            <th width="8%">销售价(元)</th>
                            <th width="8%">库存(件)</th>
                            <th width="35%">规格</th>
                            <th width="8%">会员价</th>
                        </tr>
                        </thead>
                        <tbody id="item_box">

                        <tr>
                            <td align="center">
                                <input type="hidden" name="hide_goods_id" value="86" />
                                <input type="text" name="spec_goods_no" class="td-input" value="SD3720019286-1" />
                            </td>
                            <td align="center"><input type="text" name="spec_market_price" maxlength="10" class="td-input" value="99.00" onkeydown="return checkForFloat(this,event);" onblur="countMarketPrice(this);" /></td>
                            <td align="center"><input type="text" name="spec_sell_price" maxlength="10" class="td-input" value="79.00" onkeydown="return checkForFloat(this,event);" onblur="countSellPrice(this);" /></td>
                            <td align="center"><input type="text" name="spec_stock_quantity" maxlength="10" class="td-input" value="83" onkeydown="return checkNumber(event);" onblur="countStockQuantity(this);" /></td>
                            <td style="white-space:inherit;word-break:break-all;">
                                <input type="hidden" name="hide_spec_ids" value=",9," />
                                <input type="hidden" name="hide_spec_text" value="颜色：白色" />
                                <p>颜色：白色</p>
                            </td>
                            <td align="center">
                                <input name="hide_group_price" type="hidden" value='null' />
                                <a href="javascript:;" onclick="showPriceDialog(this);">设置</a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </dd>
        </dl>
        <dl>
            <dt>排序数字</dt>
            <dd>
                <input name="txtSortId" type="text" value="99" id="txtSortId" class="input small" datatype="n" sucmsg=" " />
                <span class="Validform_checktip">*数字，越小越向前</span>
            </dd>
        </dl>
        <dl>
            <dt>浏览次数</dt>
            <dd>
                <input name="txtClick" type="text" value="227" id="txtClick" class="input small" datatype="n" sucmsg=" " />
                <span class="Validform_checktip">点击浏览该信息自动+1</span>
            </dd>
        </dl>
        <dl>
            <dt>发布时间</dt>
            <dd>
                <input name="txtAddTime" type="text" value="2015-04-20 02:39:32" id="txtAddTime" class="input rule-date-input" onfocus="WdatePicker({dateFmt:&#39;yyyy-MM-dd HH:mm:ss&#39;})" datatype="/^\s*$|^\d{4}\-\d{1,2}\-\d{1,2}\s{1}(\d{1,2}:){2}\d{1,2}$/" errormsg="请选择正确的日期" sucmsg=" " />
                <span class="Validform_checktip">不选择默认当前发布时间</span>
            </dd>
        </dl>
        <dl id="div_albums_container">
            <dt>图片相册</dt>
            <dd>
                <div class="upload-box upload-album"></div>
                <input name="hidFocusPhoto" type="hidden" id="hidFocusPhoto" class="focus-photo" value="/upload/201504/20/thumb_201504200239192345.jpg" />
                <div class="photo-list">
                    <ul>

                        <li>
                            <input type="hidden" name="hid_photo_name" value="72|/upload/201504/20/201504200239192345.jpg|/upload/201504/20/thumb_201504200239192345.jpg" />
                            <input type="hidden" name="hid_photo_remark" value="" />
                            <div class="img-box" onclick="setFocusImg(this);">
                                <img src="/upload/201504/20/thumb_201504200239192345.jpg" bigsrc="/upload/201504/20/201504200239192345.jpg" />
                                <span class="remark"><i>暂无描述...</i></span>
                            </div>
                            <a href="javascript:;" onclick="setRemark(this);">描述</a>
                            <a href="javascript:;" onclick="delImg(this);">删除</a>
                        </li>

                        <li>
                            <input type="hidden" name="hid_photo_name" value="73|/upload/201504/20/201504200239197670.jpg|/upload/201504/20/thumb_201504200239197670.jpg" />
                            <input type="hidden" name="hid_photo_remark" value="" />
                            <div class="img-box" onclick="setFocusImg(this);">
                                <img src="/upload/201504/20/thumb_201504200239197670.jpg" bigsrc="/upload/201504/20/201504200239197670.jpg" />
                                <span class="remark"><i>暂无描述...</i></span>
                            </div>
                            <a href="javascript:;" onclick="setRemark(this);">描述</a>
                            <a href="javascript:;" onclick="delImg(this);">删除</a>
                        </li>

                    </ul>
                </div>
            </dd>
        </dl>

    </div>



    <div class="tab-content" style="display:none">
        <dl>
            <dt>调用别名</dt>
            <dd>
                <input name="txtCallIndex" type="text" id="txtCallIndex" class="input normal" datatype="/^\s*$|^[a-zA-Z0-9\-\_]{2,50}$/" sucmsg=" " />
                <span class="Validform_checktip">*别名访问，非必填，不可重复</span>
            </dd>
        </dl>
        <dl>
            <dt>URL链接</dt>
            <dd>
                <input name="txtLinkUrl" type="text" maxlength="255" id="txtLinkUrl" class="input normal" />
                <span class="Validform_checktip">填写后直接跳转到该网址</span>
            </dd>
        </dl>


        <dl>
            <dt>内容摘要</dt>
            <dd>
      <textarea name="txtZhaiyao" rows="2" cols="20" id="txtZhaiyao" class="input" datatype="*0-255" sucmsg=" ">
品名：金士顿颜色：银色容量：32GB USB2.0尺寸：1.535＂0.486＂0.179＂（39.00mm12.35mm4.55mm）携带方便：小巧的无盖帽设计袖珍型，携带方便质量保证：5年保固，免费技术支持工作温度：32℉到140℉（0℃到60℃）保存温度：-4℉到185℉（-20℃到85℃）</textarea>
                <span class="Validform_checktip">不填写则自动截取内容前255字符</span>
            </dd>
        </dl>
        <dl>
            <dt>内容描述</dt>
            <dd>
      <textarea name="txtContent" id="txtContent" class="editor">&lt;p&gt;
	&lt;span class=&quot;yhbb&quot;&gt;品名：&lt;/span&gt;金士顿&lt;br /&gt;
&lt;span class=&quot;yhbb&quot;&gt;颜色：&lt;/span&gt;银色&lt;br /&gt;
&lt;span class=&quot;yhbb&quot;&gt;容量：&lt;/span&gt;32GB USB2.0&lt;br /&gt;
&lt;span class=&quot;yhbb&quot;&gt;尺寸：&lt;/span&gt;1.535＂&#215;0.486＂&#215;0.179＂&lt;br /&gt;
（39.00mm&#215;12.35mm&#215;4.55mm）&lt;br /&gt;
&lt;span class=&quot;yhbb&quot;&gt;携带方便：&lt;/span&gt;小巧的无盖帽设计袖珍型，携带方便&lt;br /&gt;
&lt;span class=&quot;yhbb&quot;&gt;质量保证：&lt;/span&gt;5年保固，免费技术支持&lt;br /&gt;
&lt;span class=&quot;yhbb&quot;&gt;工作温度：&lt;/span&gt;32℉到140℉（0℃到60℃）&lt;br /&gt;
&lt;span class=&quot;yhbb&quot;&gt;保存温度：&lt;/span&gt;-4℉到185℉（-20℃到85℃）
&lt;/p&gt;
&lt;table width=&quot;750&quot; class=&quot;dingwei&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;
	&lt;tbody&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;198&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img6.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_06.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;199&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img5.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_07.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;198&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img6.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_08.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;198&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img6.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_09.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;199&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img5.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_10.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;198&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img6.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_11.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;199&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img6.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_12.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;198&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img6.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_13.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;198&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img6.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_14.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
			&lt;td&gt;
				&lt;img width=&quot;750&quot; height=&quot;199&quot; class=&quot;gomeImgLoad&quot; alt=&quot;&quot; src=&quot;http://img6.gomein.net.cn/image/prodimg/productDesc/descImg/201410/desc7907/9132573660/1_15.jpg&quot; /&gt;
			&lt;/td&gt;
		&lt;/tr&gt;
	&lt;/tbody&gt;
&lt;/table&gt;</textarea>
            </dd>
        </dl>
    </div>

    <div class="tab-content" style="display:none">
        <dl>
            <dt>SEO标题</dt>
            <dd>
                <input name="txtSeoTitle" type="text" value="金士顿（Kingston） DataTraveler SE9 32GB 金属U盘 银色" maxlength="255" id="txtSeoTitle" class="input normal" datatype="*0-100" sucmsg=" " />
                <span class="Validform_checktip">255个字符以内</span>
            </dd>
        </dl>
        <dl>
            <dt>SEO关健字</dt>
            <dd>
      <textarea name="txtSeoKeywords" rows="2" cols="20" id="txtSeoKeywords" class="input" datatype="*0-255" sucmsg=" ">
金士顿,金属U盘</textarea>
                <span class="Validform_checktip">以“,”逗号区分开，255个字符以内</span>
            </dd>
        </dl>
        <dl>
            <dt>SEO描述</dt>
            <dd>
      <textarea name="txtSeoDescription" rows="2" cols="20" id="txtSeoDescription" class="input" datatype="*0-255" sucmsg=" ">
金士顿（Kingston） DataTraveler SE9 32GB 金属U盘 银色</textarea>
                <span class="Validform_checktip">255个字符以内</span>
            </dd>
        </dl>
    </div>
    <!--/内容-->

    <!--工具栏-->
    <div class="page-footer">
        <div class="btn-wrap">
            <input type="submit" name="btnSubmit" value="提交保存" id="btnSubmit" class="btn" />
            <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back(-1);" />
        </div>
    </div>
    <!--/工具栏-->
</body>
</html>