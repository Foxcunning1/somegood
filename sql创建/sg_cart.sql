CREATE TABLE `sg_cart` (
 `cart_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '购物车自增ID',
 `join_id` varchar(255) DEFAULT NULL COMMENT '组合ID',
 `user_id` int(10) DEFAULT '0' COMMENT '用户ID',
 `goods_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品ID',
 `goods_title` varchar(255) DEFAULT NULL COMMENT '商品名字',
 `goods_img` varchar(255) DEFAULT NULL COMMENT '商品对应的图片',
 `city_id` int(10) NOT NULL DEFAULT '0' COMMENT '城市ID',
 `market_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品市场价格',
 `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
 `pay_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品付款方式,0为一次性，1为分期',
 `goods_attr_id` varchar(255) DEFAULT NULL COMMENT '商品自定义属性',
 `services_id` int(10) NOT NULL DEFAULT '0' COMMENT '服务人员ID',
 `counts` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '数量',
 `services_name` varchar(100) DEFAULT NULL COMMENT '服务人员名称',
 `services_img` varchar(255) DEFAULT NULL COMMENT '服务人员照片',
 `phpsessid` char(32) NOT NULL,
 PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='购物车'
