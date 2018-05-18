<?php if (!defined('THINK_PATH')) exit();?><!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>卖家管理中心</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-public.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/statics/store/css/store-style.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/scripts/artdialog/ui-dialog.css" /><link rel="stylesheet" type="text/css" href="/somegood/Public/css/pagination.css" />
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
</head>
<body style="background-color: #edf1f2;">
    <div class="all-money-box">
        <div class="left fl">
            <div class="top">我的总收入（元）</div>
            <div class="center"><?php echo ($list["allGetMoney"]); ?></div>
            <div class="bottom">昨日进账：<span class="get-in"><?php echo ($countsNum["yesterday"]); ?>元</span></div>
        </div>
        <div class="right fl">
            <dl>
                <dt>收入预览</dt>
                <dd>今日进账:<span class="today-in"><?php echo ($countsNum["now"]); ?>元</span><span>订单数:<em><?php echo ($countsNum["nowNum"]); ?></em></span></dd>
                <dd>昨日进账:<span class="yesterday-in"><?php echo ($countsNum["yesterday"]); ?>元</span><span>订单数:<em><?php echo ($countsNum["yesNum"]); ?></em></span></dd>
            </dl>
        </div>
    </div>
    <div class="tab-box">
        <ul class="tab-ul fl">
            <li <?php if($action == all): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Order/settlement',array('action'=>'all'));?>">我的结算</a><div class="move"></div></li>
            <li <?php if($action == ing): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Order/settlement',array('action'=>'ing'));?>">正在结算</a><div class="move"></div></li>
            <li <?php if($action == ed): ?>class="sel"<?php endif; ?>><a href="<?php echo U('Seller/Order/settlement',array('action'=>'ed'));?>">结算完成</a><div class="move"></div></li>
        </ul>
        <div class="search-box fr">
          <form id="searchForm" class="order-search-form" action="<?php echo U('Seller/Order/settlement');?>" method="post">
            <input name="keywords" type="text" id="txtKeywords" class="search-input keyword" placeholder="请输入你要搜索的订单号">
            <a id="btnSearch" class="search-btn" >查询</a>
          </form>
        </div>
    </div>
    <div class="info-box">
        <div class="all-select-box">
            <label for="allSelect" id="allSelectLabel"><input type="checkbox" id="allSelect"/>全选</label>
            <!-- <a href="javascript:;" class="delete-a"><i class="icons icon-delete"></i><span>删除</span></a> -->
        </div>

        <table class="info-table">
            <thead>
            <tr>
                <th>序号</th>
                <th>订单号</th>
                <th>订单总额</th>
                <th>分成</th>
                <th>订单状态</th>
                <th>交易时间</th>
                <!-- <th>操作</th> -->
            </tr>
            </thead>
              <tbody>
                <?php if(is_array($list['list'])): $i = 0; $__LIST__ = $list['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><input type="checkbox"/></td>
                    <td><?php echo ($vo["order_sn"]); ?></td>
                    <td class="price">￥<?php echo ($vo["original_price"]); ?></td>
                    <td class="get-price">￥<?php echo ($vo["store_money"]); ?></td>
                    <td>
                      <?php if($vo['status'] == 3 ): ?>正在结算<?php else: ?>结算完成<?php endif; ?>
                    </td>
                    <td><?php echo (date("Y-m-d ",$vo['complete_time'])); ?></td>
                    <!-- <td><a href="javascript:;" class="operate-btn settlement-btn">结算</a></td> -->
                  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
              </tbody>
        </table>
    </div>
    <div class="pagelist">
        <div id="PageContent" class="default"><?php echo ($list['page']); ?></div>
    </div>
<script>
    $("#btnSearch").click(function(){
        $('#searchForm').submit();
    })

    /*批量删除*/
    $(".delete-a").click(function(){
        if($(".info-table").find("input:checkbox:checked").length){
            $(".info-table").find("input:checkbox:checked").parent().parent().remove();
            $("#allSelectLabel").find("input:checkbox").prop("checked",false);
        }else{
            var d = dialog({title:"温馨提示",content:"请选择要删除的订单！！！"}).show();
            setTimeout(function () {
                d.close().remove();
            }, 1500);
        }

    })
    /*全选*/
    function allChecked(obj){
        if($(obj).find("input").is(":checked")){
            $(".info-table tr").find("input[type='checkbox']").prop("checked",true);
        }else{
            $(".info-table tr").find("input[type='checkbox']").prop("checked",false);

        }
    }
    /*单选*/
    function allsingle(obj){
        var Len = $(".info-table").find("input:checkbox:checked").length;
        var len = $(".info-table").find("input:checkbox").length;
        if($(obj).is(":checked")){
            $(obj).prop("checked",true);
            if(Len==len){
                $("#allSelectLabel").find("input").prop("checked",true);
            }
        }else{
            $(obj).prop("checked",false);
            $("#allSelectLabel").find("input").prop("checked",false);
        }

    }
    /*点击全选*/
    $("#allSelectLabel").click(function(){
        allChecked(this);
    })
    /*点击单选*/
    $(".info-table tr").find("input[type='checkbox']").click(function(){
        allsingle(this);
    })
</script>
</body>
</html>