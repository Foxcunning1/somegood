<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>3好连锁店商城首页</title>
    <meta name="keywords" content="<?php echo ($web_keywords); ?>"/>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/public.css">
    <link type="text/css" rel="stylesheet" href="/somegood/Public/statics/shop/css/style.css">
    <script type="text/javascript" src="/somegood/Public/scripts/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript"  src="/somegood/Public/statics/shop/js/jquery.SuperSlide.2.1.1.js"></script>
</head>
<body>
<div class="header">
    <div class="h-top">
    <div class="w1200">
        <div class="fr clearfix">
            <?php if(empty($_SESSION['shop']['id'])): ?><a href="<?php echo U('Shop/Login/index');?>" class="m">登录</a>
                <a href="<?php echo U('Shop/Login/register');?>" class="m">注册</a>
                <?php else: ?>
                <a href="<?php echo U('Shop/UserOrder/orderlist');?>">我的订单</a>
                <span class="line">/</span>
                <a href="<?php echo U('Shop/Users/index');?>">个人中心<span class="pointer"></span></a><?php endif; ?>
            <span class="line">/</span>
            <a href="<?php echo U('Shop/Cart/index');?>">购物车</a>
            <span class="line">/</span>
            <a href="javascript:;">客服服务<span class="pointer"></span></a>
            <span class="line">/</span>
            <a href="<?php echo U('Mobile/Index/index');?>">手机逛3好</a>
            <span class="line">/</span>
            <a href="javascript:;">更多<span class="pointer"></span></a>
        </div>
    </div>
</div>

    <div class="w1200">
        <div class="h-middle clearfix">
            <a href="<?php echo U('Shop/Index/index');?>" class="fl"><img src="/somegood/Public/statics/shop/images/logo_market.png" alt="logo" class="logo"/></a>
            <div class="search-box fl">
                <form class="search-form" method="post" action="<?php echo U('Shop/Goods/goodsList');?>">
                    <i class="icons icon-search"></i>
                    <input type="text"  name="keywords" placeholder="搜好物">
                    <button type="submit" class="submit">搜索</button>
                </form>
                <div class="hot-search">
                    <a href="javascript:;">女装</a>
                    <a href="javascript:;">衣柜</a>
                    <a href="javascript:;">双十一</a>
                </div>
            </div>
            <div class="my-cart fr">
                <a href="<?php echo U('Shop/Cart/index');?>" class="mini-cart-open">
                    <i class="icons icon-cart"></i>
                    <span class="txt">我的购物车</span>
                    <span class="goods-num">0</span>
                </a>
                <div class="my-cart-line"></div>
                <div class="mini-cart-box">
                    <h4>全部商品</h4>
                    <div class="items-list">
                    </div>
                    <div class="cart-to-box">
                        <div class="total-box">
                            共<span class="cart-num">0</span>件商品　共计<span class="cart-total-money">¥ 0</span>
                        </div>
                        <a href="<?php echo U('Shop/Cart/index');?>" class="to-cart-a">去购物车</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-bottom">
            <div class="all-category">
                <div class="title">全部分类</div>
                <div class="category-box">
                    <ul class="category-box-ul">
                        <li>
                            <a href="javascript:;">手机</a>
                            <span class="line">/</span>
                            <a href="javascript:;">运营商</a>
                            <span class="line">/</span>
                            <a href="javascript:;">智能数码</a>
                            <div class="category-box-content">
                                <div class="item clearfix">
                                    <div class="part1 fl">
                                        <div class="cannel clearfix">
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                        </div>
                                        <div class="detial">
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="part2 fl">
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="javascript:;">电脑办公</a>
                            <span class="line">/</span>
                            <a href="javascript:;">相机</a>
                            <span class="line">/</span>
                            <a href="javascript:;">DIY</a>
                            <div class="category-box-content">
                                <div class="item clearfix">
                                    <div class="part1 fl">
                                        <div class="cannel clearfix">
                                            <a href="javascript:;">女fsdfsdf装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                        </div>
                                        <div class="detial">
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="part2 fl">
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="javascript:;">食品</a>
                            <span class="line">/</span>
                            <a href="javascript:;">酒水</a>
                            <span class="line">/</span>
                            <a href="javascript:;">生鲜</a>
                            <span class="line">/</span>
                            <a href="javascript:;">特产</a>
                            <div class="category-box-content">
                                <div class="item clearfix">
                                    <div class="part1 fl">
                                        <div class="cannel clearfix">
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                            <a href="javascript:;">女装<span>&gt;</span></a>
                                        </div>
                                        <div class="detial">
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt> <a href="javascript:;">女装<span>&gt;</span></a></dt>
                                                <dd>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                    <a href="javascript:;">男士西装</a>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="part2 fl">
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                        <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/floor_bg1.jpg" alt="" /></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="javascript:;">个护化妆</a>
                            <span class="line">/</span>
                            <a href="javascript:;">纸品清洁</a>
                            <span class="line">/</span>
                            <a href="javascript:;">宠物</a>
                        </li>
                        <li>
                            <a href="javascript:;">母婴</a>
                            <span class="line">/</span>
                            <a href="javascript:;">玩具</a>
                            <span class="line">/</span>
                            <a href="javascript:;">车床</a>
                            <span class="line">/</span>
                            <a href="javascript:;">童装</a>
                        </li>
                        <li>
                            <a href="javascript:;">运动</a>
                            <span class="line">/</span>
                            <a href="javascript:;">户外</a>
                            <span class="line">/</span>
                            <a href="javascript:;">足球</a>
                            <span class="line">/</span>
                            <a href="javascript:;">骑行</a>
                        </li>
                        <li>
                            <a href="javascript:;">男装</a>
                            <span class="line">/</span>
                            <a href="javascript:;">女装</a>
                            <span class="line">/</span>
                            <a href="javascript:;">内衣</a>
                        </li>
                        <li>
                            <a href="javascript:;">鞋靴</a>
                            <span class="line">/</span>
                            <a href="javascript:;">箱包</a>
                            <span class="line">/</span>
                            <a href="javascript:;">钟表</a>
                            <span class="line">/</span>
                            <a href="javascript:;">珠宝</a>
                        </li>
                        <li>
                            <a href="javascript:;">汽车摩托车</a>
                            <span class="line">/</span>
                            <a href="javascript:;">汽车精品</a>
                            <span class="line">/</span>
                            <a href="javascript:;">服务</a>
                        </li>
                        <li>
                            <a href="javascript:;">图书</a>
                            <span class="line">/</span>
                            <a href="javascript:;">童书</a>
                            <span class="line">/</span>
                            <a href="javascript:;">电子书</a>
                        </li>
                    </ul>
                </div>
            </div>
            <ul class="nav-ul">
                <li><a href="<?php echo U('Shop/Index/index');?>">首页</a></li>
                <li><a href="javascript:;">今日上新</a></li>
                <li><a href="javascript:;">全网秒杀</a></li>
                <li><a href="javascript:;">品牌投放</a></li>
            </ul>
        </div>
    </div>
</div>


    <!--焦点图-->
    <div class="slider">
        <div class="slider-box">
            <div class="hd">
                <ul>
                    <li class="on">1</li>
                    <li>2</li>
                    <li>3</li>
                </ul>
            </div>
            <div class="bd">
                <ul>
                    <li style="background: url(/somegood/Public/statics/shop/images/banner1.jpg) no-repeat center center;"></li>
                    <li style="background: url(/somegood/Public/statics/shop/images/banner1.jpg) no-repeat center center;"></li>
                    <li style="background: url(/somegood/Public/statics/shop/images/banner1.jpg) no-repeat center center;"></li>
                </ul>
            </div>
        </div>
        <script>jQuery(".slider-box").slide({mainCell:".bd ul",autoPlay:true,trigger:"click"});</script>
    </div>
    <!--活动精选-->
    <div class="boutique">
        <div class="w1200">
            <div class="title1 clearfix">
                <i class="icons icon-clock fl"></i>
                <h3>活动精选</h3>
            </div>
            <div class="content">
                <div class="b-content clearfix">
                    <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/img_1.jpg" alt="" /></a>
                    <a href="javascript:;" class="big"><img src="/somegood/Public/statics/shop/images/img_2.jpg" alt="" /></a>
                    <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/img_3.jpg" alt="" /></a>
                    <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/img_4.jpg" alt="" /></a>
                    <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/img_5.jpg" alt="" /></a>
                    <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/img_6.jpg" alt="" /></a>
                    <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/img_7.jpg" alt="" /></a>
                    <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/img_8.jpg" alt="" /></a>
                    <a href="javascript:;" class="big"><img src="/somegood/Public/statics/shop/images/img_9.jpg" alt="" /></a>
                    <a href="javascript:;"><img src="/somegood/Public/statics/shop/images/img_10.jpg" alt="" /></a>
                </div>
                <div class="adv-box"></div>
            </div>
        </div>
    </div>
    <!--1楼-->
    <div class="floor floor1">
        <div class="w1200">
            <div class="title1 clearfix">
                <span class="floor-num fl">1F</span>
                <h3>潮流数码</h3>
            </div>
            <div class="f-content clearfix">
                <div class="f-content-l fl">
                    <a href="javascript:;">
                        <div class="a-top"><i class="icons icon-camera fl"></i><span>潮流数码</span></div>
                        <div class="a-middle"><img src="/somegood/Public/statics/shop/images/floor_img1.jpg" alt="" /></div>
                        <div class="a-bottom"><h4>爆品抢购</h4><h5>品质好货、热卖精选</h5></div>
                    </a>
                </div>
                <div class="f-content-r fr">
                    <div class="a-box clearfix">
                        <div class="t-right fr">
                            <a href="javascript:;">网络路由</a>
                            <span class="line">/</span>
                            <a href="javascript:;">摄影摄像</a>
                            <span class="line">/</span>
                            <a href="javascript:;">平板电脑</a>
                        </div>
                    </div>
                    <div class="goods-box">
                        <ul>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img1.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img2.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img3.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img4.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img5.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img6.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img7.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img8.jpg" alt="" />
                                    <span class="txt">fsfsdffsfsdfsdfsdffsfsdfsdfsdffsfsdfsdfsdffsfsdfsdfsdffsfsdfsdfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img9.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img10.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!--2楼-->
    <div class="floor floor2">
        <div class="w1200">
            <div class="title1 clearfix">
                <span class="floor-num fl">2F</span>
                <h3>生活百货</h3>
            </div>
            <div class="f-content clearfix">
                <div class="f-content-l fl">
                    <a href="javascript:;">
                        <div class="a-top fl"><i class="icons icon-store fl"></i><span>生活百货</span></div>
                        <div class="a-middle"> <img src="/somegood/Public/statics/shop/images/floor_img2.jpg" alt="" /></div>
                        <div class="a-bottom"><h4>品质生活</h4><h5>让生活更简单点</h5></div>
                    </a>
                </div>
                <div class="f-content-r fr">
                    <div class="a-box clearfix">
                        <div class="t-right fr">
                            <a href="javascript:;">网络路由</a>
                            <span class="line">/</span>
                            <a href="javascript:;">摄影摄像</a>
                            <span class="line">/</span>
                            <a href="javascript:;">平板电脑</a>
                        </div>
                    </div>
                    <div class="goods-box">
                        <ul>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img1.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img2.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img3.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img4.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img5.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img6.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img7.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img8.jpg" alt="" />
                                    <span class="txt">fsfsdffsfsdfsdfsdffsfsdfsdfsdffsfsdfsdfsdffsfsdfsdfsdffsfsdfsdfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img9.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img10.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!--3楼-->
    <div class="floor floor3">
        <div class="w1200">
            <div class="title1 clearfix">
                <span class="floor-num fl">3F</span>
                <h3>女装盛宴</h3>
            </div>
            <div class="f-content clearfix">
                <div class="f-content-l fl">
                    <a href="javascript:;">
                        <div class="a-top fl"><i class="icons icon-ladies fl"></i><span>女装盛宴</span></div>
                        <div class="a-middle"> <img src="/somegood/Public/statics/shop/images/floor_img3.jpg" alt="" /></div>
                        <div class="a-bottom"><h4>性感风向标</h4><h5>秋冬新品5折首发</h5></div>
                    </a>
                </div>
                <div class="f-content-r fr">
                    <div class="a-box clearfix">
                        <div class="t-right fr">
                            <a href="javascript:;">网络路由</a>
                            <span class="line">/</span>
                            <a href="javascript:;">摄影摄像</a>
                            <span class="line">/</span>
                            <a href="javascript:;">平板电脑</a>
                        </div>
                    </div>
                    <div class="goods-box">
                        <ul>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img1.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img2.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img3.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img4.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li class="bor-b">
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img5.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img6.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img7.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img8.jpg" alt="" />
                                    <span class="txt">fsfsdffsfsdfsdfsdffsfsdfsdfsdffsfsdfsdfsdffsfsdfsdfsdffsfsdfsdfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img9.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="/somegood/Public/statics/shop/images/goods_img10.jpg" alt="" />
                                    <span class="txt">fsfsdfsdfsdf</span>
                                    <span class="price">￥300</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!--热卖-->
    <div class="hot-selling">
        <div class="w1200">
            <div class="dash-title"><span class="left"></span>本周热销<span class="right"></span></div>
            <div class="h-content clearfix">
                <ul>
                    <li>
                        <a href="javascript:;">
                            <img src="/somegood/Public/statics/shop/images/goods_img1.jpg" alt="" />
                            <span class="txt">fsfsdfsdfsdf</span>
                            <span class="price">￥300</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <img src="/somegood/Public/statics/shop/images/goods_img1.jpg" alt="" />
                            <span class="txt">fsfsdfsdfsdf</span>
                            <span class="price">￥300</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <img src="/somegood/Public/statics/shop/images/goods_img1.jpg" alt="" />
                            <span class="txt">fsfsdfsdfsdf</span>
                            <span class="price">￥300</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <img src="/somegood/Public/statics/shop/images/goods_img1.jpg" alt="" />
                            <span class="txt">fsfsdfsdfsdf</span>
                            <span class="price">￥300</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <img src="/somegood/Public/statics/shop/images/goods_img1.jpg" alt="" />
                            <span class="txt">fsfsdfsdfsdf</span>
                            <span class="price">￥300</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <img src="/somegood/Public/statics/shop/images/goods_img1.jpg" alt="" />
                            <span class="txt">fsfsdfsdfsdf</span>
                            <span class="price">￥300</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--底部-->

<div class="footer">
    <div class="f-top">
        <div class="w1200">
            <div class="f-top-t clearfix">
                <div class="item">
                    <i class="icons icon-item icon-item1 fl"></i>
                    <div class="text">
                        <span class="big">100%正品</span>
                        <span>正品保障 假一赔十</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item2 fl"></i>
                    <div class="text">
                        <span class="big">低价保障</span>
                        <span>缩减中间环节 确保低价</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item3 fl"></i>
                    <div class="text">
                        <span class="big">无忧退货</span>
                        <span>严格按照消法处理</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item4 fl"></i>
                    <div class="text">
                        <span class="big">发票保障</span>
                        <span>售卖商品可提供发票</span>
                    </div>
                </div>
                <div class="item">
                    <i class="icons icon-item icon-item5 fl"></i>
                    <div class="text">
                        <span class="big">满188包邮</span>
                        <span>部分特殊商品除外</span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="f-top-b clearfix">
                <div class="box-l fl">
                    <dl>
                        <dt>购物指南</dt>
                        <dd><a href="javasctipt:;">账号注册</a></dd>
                        <dd><a href="javasctipt:;">购物流程</a></dd>
                        <dd><a href="javasctipt:;">付款方式</a></dd>
                    </dl>
                    <dl>
                        <dt>售后服务</dt>
                        <dd><a href="javasctipt:;">先行赔付</a></dd>
                        <dd><a href="javasctipt:;">退款退货流程</a></dd>
                        <dd><a href="javasctipt:;">投诉建议</a></dd>
                    </dl>
                    <dl>
                        <dt>商城保障</dt>
                        <dd><a href="javasctipt:;">正品保障</a></dd>
                        <dd><a href="javasctipt:;">物流配送</a></dd>
                        <dd><a href="javasctipt:;">发票保障</a></dd>
                    </dl>
                    <dl>
                        <dt>商家服务</dt>
                        <dd><a href="<?php echo U('Store/Index/index');?>">店铺中心</a></dd>
                        <dd><a href="<?php echo U('Seller/Index/index');?>">卖家中心</a></dd>
                    </dl>
                </div>
                <div class="box-c fl">
                    <i class="icons icon-tel fl"></i>
                    <div class="tell">
                        <span class="phone-num">0755-23999161</span>
                        <span>工作时间：9:00 - 18:00</span>
                    </div>
                </div>
                <div class="box-r fr">
                    <i class="icons icon-coopration fl"></i>
                    <dl>
                        <dt>商家合作</dt>
                        <dd>错过天猫别再错过我们</dd>
                        <dd>三好连锁开启全新旅程！</dd>
                        <dd><a href="javascript:;" class="join-us">关于入驻 >> </a></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <div class="f-bottom">
        <div class="w1200">
            <div class="f-bottom-l fl">
                <p>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                    <span>|</span>
                    <a href="javascript:;">友情链接</a>
                </p>
                <p class="copy-right">备案号XXXXXXXXXXXXXXXXXXXXXXXXX号   Copyright © 2006 - 2017  三好连锁 版权所有</p>
                <p>
                    <img src="/somegood/Public/statics/shop/images/b_img_1.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_2.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_3.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_4.png" alt="" />
                    <img src="/somegood/Public/statics/shop/images/b_img_5.png" alt="" />
                </p>
            </div>
            <div class="f-bottom-r fr clearfix">
                <span class="fl">扫一扫二维码</span>
                <img src="/somegood/Public/statics/shop/images/erweima.png" alt="" class="erwerma fl"/>
            </div>
        </div>
    </div>
</div>
<script>
    /*迷你购物车出现隐藏*/
    $(".mini-cart-open").hover(function(){
        $(".mini-cart-box").show();
        $(".my-cart-line").show();
    },function(){
        $(".mini-cart-box").hide();
        $(".my-cart-line").hide();
    })
    $(".mini-cart-box").hover(function(){
        $(this).show();
        $(".my-cart-line").show();
    },function(){
        $(this).hide();
        $(".my-cart-line").hide();
    })
    $(".my-cart-line").hover(function(){
        $(this).show();
        $(".mini-cart-box").show();
    },function(){
        $(this).hide();
        $(".mini-cart-box").hide();
    })
    /*隐藏菜单显示*/
    $(".category-box-ul").find("li").hover(function(){
        $(this).find(".category-box-content").show().siblings("li").find(".category-box-content").hide();
    },function(){
        $(this).find(".category-box-content").hide();
    })
    //获取购物车商品
    function getCartGoods() {
        $.ajax({
            type: 'POST',
            url: "<?php echo U('Shop/Cart/getCartGoods');?>",
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    $(".cart-num").text(data.data.cartNum);
                    $(".goods-num").text(data.data.cartNum);
                    var totalMoney = 0;
                    if(data.data.cartNum>0){
                        var list = data.data.list;
                        var str = '';
                        $.each(list,function(index,v){
                            str +="<div class=\"item-box\" id=\"cartGoods_"+v.cart_id+"\">";
                            str +=" <div class=\"cart-img\"><a href=\"<?php echo U('Shop/Goods/goods');?>?id="+v.real_goods_id+"\"><img src=\"/somegood/Public/uploads/"+v.goods_thumb+"\" alt=\""+v.goods_title+"\" /></a></div>";
                            str +="<div class=\"cart-name\"><a href=\"<?php echo U('Shop/Goods/goods');?>?id="+v.real_goods_id+"\" title=\""+v.goods_title+"\">"+v.goods_title+"</a></div>";
                            str +="<div class=\"cart-info\">";
                            str +="<div class=\"cart-price\">￥"+v.goods_price+" * "+v.counts+"件</div>";
                            str +="<a href=\"javascript:;\" class=\"cart-delete\"  id=\"delCart_"+v.cart_id+"\" onclick='delCart("+v.cart_id+","+v.goods_price+","+v.counts+");'>删除</a>";
                            str +="</div>";
                            str +=" <div class=\"clear\"></div>";
                            str +=" </div>";
                            totalMoney += v.goods_price*v.counts;
                        })
                        $(".items-list").html(str);
                        $(".cart-total-money").text(totalMoney);
                    }
                } else {
                    $(".cart-num").text("0");
                    $(".goods-num").text("0");
                    $(".cart-total-money").text('0');
                }
            }
        })
    }
    $(function () {
        getCartGoods();
    })
    //删除购物车
    function delCart(cartId,price,counts) {
        var curtTotalMoney=$(".cart-total-money").text()-price*counts;
        var curtCounts=$(".goods-num").text()-counts;
        $.ajax({
            type:"POST",
            dataType:'json',
            url:"<?php echo U('Mobile/Cart/delCartGoods');?>",
            data:{
                cart_id:cartId
            },
            success: function(data){
                $("#delCart_"+cartId).text("正在删除");
                window.setTimeout(function(){
                    $("#cartGoods_"+cartId).remove();
                    $(".cart-num").text(curtCounts);
                    $(".goods-num").text(curtCounts);
                    $(".cart-total-money").text(curtTotalMoney);
                },1000);
            }
        });
    }
</script>
</body>
</html>