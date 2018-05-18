# Host: localhost  (Version: 5.5.53)
# Date: 2017-08-07 20:00:33
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "cs_column"
#

DROP TABLE IF EXISTS `cs_column`;
CREATE TABLE `cs_column` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` varchar(255) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `c_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '栏目类型,0为单页面,1为列表',
  `c_content` longtext COMMENT '单页面内容',
  `c_img` varchar(255) DEFAULT NULL COMMENT '栏目缩略图',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级栏目c_id',
  `c_is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏，默认为0，1为隐藏',
  `sort_num` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `c_seo_title` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `c_seo_keywords` varchar(255) DEFAULT NULL COMMENT 'SEO关键词',
  `c_seo_description` varchar(255) DEFAULT NULL COMMENT 'SEO描述',
  `class_list` varchar(255) DEFAULT NULL COMMENT '栏目层次',
  `class_layer` int(11) DEFAULT NULL COMMENT '栏目层数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='文章栏目表';

#
# Data for table "cs_column"
#

/*!40000 ALTER TABLE `cs_column` DISABLE KEYS */;
INSERT INTO `cs_column` VALUES (1,'栏目11',1,'11&lt;br /&gt;',NULL,0,0,11,'SEO标题1','SEO关键词1','SEO描述1',',1,',1);
/*!40000 ALTER TABLE `cs_column` ENABLE KEYS */;
