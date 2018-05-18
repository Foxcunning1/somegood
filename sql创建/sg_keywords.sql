CREATE TABLE `sg_keywords` (
 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
 `search_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '搜索类型：1企业，2新闻，3产品，4商机',
 `category_id` int(10) NOT NULL DEFAULT '0' COMMENT '分类ID',
 `keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '被搜索的关键词',
 `alias_name` varchar(100) DEFAULT NULL COMMENT '英文别名',
 `prod_key` varchar(20) DEFAULT NULL COMMENT '关键词首字母',
 `count` mediumint(8) NOT NULL DEFAULT '0' COMMENT '关键词在当前日期搜索的次数',
 `date` int(10) NOT NULL DEFAULT '0' COMMENT '搜索时间',
 `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是热搜类',
 `is_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
 PRIMARY KEY (`id`),
 KEY `keyword` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='关键词搜索记录统计(按天累加)'



CREATE TABLE `sg_keywords_user` (
 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID自增',
 `user_id` int(10) DEFAULT '0' COMMENT '用户ID，0为匿名用户',
 `user_ip` varchar(16) DEFAULT NULL,
 `kid` int(11) DEFAULT NULL,
 `first_time` int(10) DEFAULT NULL,
 `last_time` int(10) DEFAULT NULL,
 `user_count` mediumint(8) DEFAULT NULL COMMENT 'IP用户搜索的次数',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8
