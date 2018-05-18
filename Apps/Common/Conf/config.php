<?php
return array(
	//'配置项'=>'配置值'
    'SESSION_AUTO_START'      => true, //是否开启session
    'DEFAULT_MODULE'          => 'Home', //默认模块
    'URL_CASE_INSENSITIVE'    => true,//URL不区分大小写

    'URL_ROUTER_ON'           => true, //路由模式开启
    'URL_MODEL'               => '2', //URL模式
    'ACTION_SUFFIX'           => 'Action', // 操作方法后缀
    'PAGE_SIZE'               => '15',
    'ORDER_PAGE_SIZE'         => '1000',
    /*数据库配置信息*/
    'DB_TYPE'                 => 'mysql', // 数据库类型
    'DB_HOST'                 => '192.168.1.250', // 服务器地址
    'DB_NAME'                 => 'somegood', // 数据库名
    'DB_USER'                 => 'root', // 用户名
    'DB_PWD'                  => '', // 密码
    'DB_PORT'                 => 3306, // 端口
    'DB_PREFIX'               => 'sg_', // 数据库表前缀
    'DB_CHARSET'              => 'utf8', // 字符集
    'DB_DEBUG'                =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增

    'DATA_CACHE_KEY'          => 'think', //缓存文件名字符串，防猜测

    //表单自动验证
    'TOKEN_ON'                => true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'              => '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'              => 'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'             => true,  //令牌验证出错后是否重置令牌 默认为true

    'MODEL_TYPE'              => array(
                                    'system_nav'       => 1, //系统导航
                                    'adv_position'     => 2, //广告位类型
                                    'adv'              => 3, //广告位
                                    'article'          => 4, //文章
                                    'user'             => 5, //用户
                                    'goods_type'       => 6, //商品类型图标
                                    'category'         => 7, //分类图片
                                 ),
    /* 自动运行配置 */
    'CRON_ORDER_TIME' =>'86400',   //检测订单任务超时时间  s
    'CRON_CONFIG_ON' => false, // 是否开启自动运行

    /*上传设置*/
    'IMAGE_SERVER_DOMAIN'        => 'http://wx.somegood.com.cn',//图片服务器地址
    'STATIC_STATUS_LIST'         => array('关闭','伪URL重写'),
    'FILE_SAVE_LIST'             => array('按年月日每天一个目录','按年月/日/存入不同目录'),
    'FILE_REMOTE_LIST'           => array('关闭下载','自动下载'),
    'THUMB_NAIL_MODEL_LIST'      => array('裁剪','补白'),
    'WATER_MARK_TYPE_LIST'       => array('关闭水印','文字水印','图片水印'),
    'WATER_MARK_POSITION_LIST'   => array('左上','中上','右上','左中','居中','右中','左下','中下','右下'),
    'WATER_MARK_FONT_LIST'       => array('Arial','Arial Black','仿宋_GB2312','楷体_GB2312'),
    'SUBTYPE_LIST'               => array('custom','hash','date'),
    'INFORM_LIST'                => array('关闭通知','短信通知','邮箱通知'),

    'LOAD_EXT_CONFIG'         => 'system_config,users_config',//加载扩展配置
    'AU_KEY'                  => 'SgOd3H0YdiywCsmP',//Cookie本地保存加密干扰码

    'CACHE_DIRECTORY'         => 'Cache-Data-Temp-Logs-Cache/Home-Cache/Admin-Cache/Mobile-Cache/Store'.
                                 '-Data-Data/_fields-Data/Admin/Nav-Data/Mobile/Category-Logs/Admin'.
                                 '-Logs/Admin-Logs/Common-Logs/Home-Logs/Mobile-Logs/Store',//系统缓存对应的目录

    /*短信接口相关配置*/
    'SMS_APPID'               => '76883cd1b4fb45abad9c74a8d9262207',//短信配置appid
    'SMS_TOKEN'               => '3f80c1eccc3299f5e5f2dc69f7eff733',//短信配置token
    'ACCOUNTSID'              => 'a33e91f161404a1069c4f05c87132e7c',//短信配置accountid
    'SMS_TPL'                 => array(
                                    'login'           => 0,//登录对应的模版ID
                                    'register'        => 191513,//注册对应的模版ID
                                    'password'        => 0,//密码设置成功
                                    'code'            => 0,//短信验证码
                                    'bind'            => 118767,//绑定验证码
                                    'success'         => 118772,//手机绑定成功通知密码

                                    'application'     => 118785,//店铺审核通过通知
                                    'failed'          => 118804,//店铺审核失败通知

                                    'audit'           => 118779,//店铺商品待审核通知
                                    'audit_on'        => 149330,//店铺商品审核通过，通知发布者送商品到店铺(商品名,店铺，地址，店铺电话)
                                    'audit_off'       => 118805,//店铺商品审核失败,通知发布者失败原因（失败原因）

                                    'on_shelf'        => 118790,//商品上架通知
                                    'off_shelf'       => 149325,//商品下架通知

                                    'sell'            => 118787,//店铺商品卖出通知
                                    'shipping'        => 118783,//发货通知
                                    'settlement'      => 118788,//商品结算通知
                                    's_settlement'    => 118789,//店铺商品结算通知

                                    'soon_over_goods' => 191517,//卖家商品即将过期


                              ),
    /*微信接口配置信息*/
    'wxconfig' => array(
        'app_id'      => 'wx769c82b4c536a7f2',
        'app_secret'  => 'de1d0f13d3620be9d7b3555d001a57aa',
    ),
    'wxapi_url' => 'http://m.somegood.com.cn/Mobile/WxApi/index.html',
    'wxnotify_url' => 'http://m.somegood.com.cn/Mobile/Wxpay/notify.html',
    'wxnotify_url_shop' => 'http://m.somegood.com.cn/Shop/Wxpay/notify.html',
    /*手机版域名URL地址*/
    'MOBILE_URL' => 'http://m.somegood.com.cn',
    /*已开通站点城市列表*/
    'CITY_LIST' => array(
        'sz' => 1988, //深圳
    ),
    //数据拉取为空，显示的数据
    'NOT_DATA' => "<p class=\"notData\">亲，暂时没有相关数据！</p>",
    'BAIDU_MAP_AK' => "2WhEHd4ECTVTsz006GBOVCTPbE5ZMONG",//百度地图API接口ak

    //工作日结算
    'SETTLEMENT_DAY' =>'7',
    /*用户积分、金额、虚拟金币相关配置*/
    'users' => array(
        'gift_money'           => '1',//用户分享其他用户绑定（注册）获取的虚拟金币，可抵扣现金
        'change_note'          => '用户“%s”通过分享链接成功注册！',//用户获取虚拟金币说明
        'seller_percent'       => 0.8, //卖家商品卖完后最后获取得商品最终卖出的总金额的比例
        'store_percent'        => 0.1, //商家商品卖完后获得订单总金额的比例
        'sh_seller_percent'    => 1, //卖家之后得到的金额比例
    ),
    'MAIL_CHARSET'          =>'UTF-8',//邮件编码
    'MAIL_AUTH'             => true,//邮箱认证
    'MAIL_HTML'             => true,//true HTML格式 false TXT格式
    /*购物币最大使用数量*/
    'SHARE_BILL' => '6',
    'FRONT_ORDER_STATUS_LIST' =>array(
        '100'=>'全部',
        '0' => '待付款',
        '1' => '待发货',
        '2' => '待收货',
        '3' => '已完成',
    ),
    'GOODS_CONDITION'=>array(
        1=>'一成新',
        2=>'二成新',
        3=>'三成新',
        4=>'四成新',           //商品成色
        5=>'五成新',
        6=>'六成新',
        7=>'七成新',
        8=>'八成新',
        9=>'九成新',
        95=>'九五新',
        99=>'九九新',
        10=>'全新',
    ),
    'REASON_LIST'=>array(
        '不好看','不合适'
    ),
    'GOODS_PAGE_SIZE'=>10,//商品列表每页条数
    'ORDER_PAGE_SIZE'=>8,//订单列表每页条数
    'NOTICE_PAGE_SIZE'=>8,//订单通知列表每页条数
    'LOCK_TIME' => 15,//登录次数达到ERROR_NUM后，该用户在时间内禁止登录，注：以分钟计算
    'ERROR_NUM' => 5,//密码错误次数达到该值，系统LOCK_TIME分钟之内禁止登录
    'SMS_SEND_INTERVAL'=>120,//验证码发送间隔
    //阿里大于
    'ALIDAYU_API_CONFIG'=>array(
        'app_key'    => 'LTAImWg5toBhOeJ2',//KeyID
        'app_secret' => 'k3CL8CHQSTgHrD98nwo31IymmHEyTh',//secret_key
        'sign_name'   => '阿里云短信测试专用',//签名
    ),
    'SELLER_INDEX_MAX_NUM' => 4,
);
