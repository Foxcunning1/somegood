<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>后台管理中心</title>
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/css/toastr.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/ui-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/somegood/Public/statics/admin/css/icon/iconfont.css" />
    <script type="text/javascript">
        var getSystemNavAjaxUrl = "<?php echo U('Admin/Ajax/getSystemNav');?>";
    </script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/jquery/jquery.nicescroll.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/artdialog/dialog-plus-min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/layindex.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/statics/admin/js/common.js"></script>
    <script type="text/javascript" charset="utf-8" src="/somegood/Public/scripts/toastr/toastr.js"></script>
    <script type="text/javascript">
        //页面加载完成时
        $(function () {
            //检测IE
            if ('undefined' == typeof (document.body.style.maxHeight)) {
                window.location.href = 'ie6update.html';
            }
        });
        var cacheType = "<?php echo C('CACHE_DIRECTORY');?>";
    </script>
</head>

<body class="indexbody">
    <!--全局菜单-->
    <a class="btn-paograms" href="javascript:;" onclick="togglePopMenu();">
        <i class="iconfont icon-list-fill"></i>
    </a>
    <div id="pop-menu" class="pop-menu">
        <div class="pop-box">
            <h1 class="title"><i class="iconfont icon-setting"></i>导航菜单</h1>
            <i class="close iconfont icon-remove" onclick="togglePopMenu();"></i>
            <div class="list-box"></div>
        </div>
    </div>
    <!--/全局菜单-->

    <div class="main-top">
        <a class="icon-menu"><i class="iconfont icon-nav"></i></a>
        <div id="main-nav" class="main-nav"></div>
        <div class="nav-right">
            <div class="info">
                <h4>

                    <i class="iconfont icon-user"></i>

                </h4>
                <span>
         您好，<?php echo ($_SESSION['admin']['admin_name']); ?><br>
          <?php echo ($_SESSION['admin']['admin_role_name']); ?>
        </span>
            </div>
            <div class="option">
                <i class="iconfont icon-arrow-down"></i>
                <div class="drop-wrap">
                    <ul class="item">
                        <li>
                            <a href="/" target="_blank">预览网站</a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="clearCache(cacheType,'<?php echo U('Admin/Ajax/clearSystemCache');?>');">更新缓存</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Admin/Index/center');?>" target="mainframe">管理中心</a>
                        </li>
                        <li>
                            <a href="<?php echo U('Admin/Manager/managerAdd');?>?action=edit&id=<?php echo ($_SESSION['admin']['admin_id']); ?>" onclick="linkMenuTree(false, '');" target="mainframe">修改密码</a>
                        </li>
                        <li>
                            <a  href="<?php echo U('Admin/Login/logout');?>">注销登录</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="main-left">
        <div class="logo-box"><h1 class="logo"></h1></div>
        <div id="sidebar-nav" class="sidebar-nav"></div>
    </div>

    <div class="main-container">
        <!--内容-->
            <!--用一个form表单然后嵌入一个iframe框架-->
        <iframe id="mainframe" name="mainframe" frameborder="0" src="<?php echo U('Admin/Index/center');?>"></iframe>
        <!--/内容-->
    </div>
    <script>
        function playSound()
        {
            var borswer = window.navigator.userAgent.toLowerCase();
            if ( borswer.indexOf( "ie" ) >= 0 )
            {
                //IE内核浏览器
                var strEmbed = '<embed name="embedPlay" src="/somegood/Public/statics/store/audio/notice.wav" autostart="true" hidden="true" loop="false"></embed>';
                if ( $( "body" ).find( "embed" ).length <= 0 )
                    $( "body" ).append( strEmbed );
                var embed = document.embedPlay;
                //浏览器不支持 audion，则使用 embed 播放
                embed.volume = 100;
            } else
            {
                //非IE内核浏览器
                var strAudio = "<audio id='audioPlay' src='/somegood/Public/statics/store/audio/notice.wav' hidden='true'>";
                if ( $( "body" ).find( "audio" ).length <= 0 )
                    $( "body" ).append( strAudio );
                var audio = document.getElementById( "audioPlay" );
                //浏览器支持 audion
                audio.play();
            }
        }
        //通知框设置
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        function getting(){
            $.ajax({
                url:"<?php echo U('Admin/Index/getNotice');?>",
                dataType:'json',
                success:function(data) {
                    //待审核店铺
                    if (data.data.audit_num >0){
                        toastr.info("<a href=\"<?php echo U('Admin/Store/index',array('property'=>'lock','notice'=>1));?>\" target='mainframe'>您有"+data.data.audit_num+"个店铺申请待审核,请尽快处理</a>")
                        playSound();
                    }
                    //待审核卖家
                    if (data.data.seller_num >0){
                        toastr.warning("<a href=\"<?php echo U('Admin/Seller/check',array('is_seller'=>0,'notice'=>1));?>\" target='mainframe'>有"+data.data.seller_num+"个卖家申请,请尽快处理</a>")
                    }
                    //待投放商品
                    if (data.data.wait_num >0){
                        toastr.success("<a href=\"<?php echo U('Admin/SellerDelivery/goods',array('property'=>'wait','notice'=>1));?>\" target='mainframe'>有"+data.data.wait_num+"个新增商品待投放,请尽快处理</a>")
                    }
                    //通知总数
                    if (data.data.totalCount >0){
                        playSound();
                    }
                    $("#noticeNum").html(data.data.totalCount);
                }
            })
        };
        window.setInterval(function(){getting();},300000);
        $(function () {
            getting();
        })
    </script>
</body>
</html>