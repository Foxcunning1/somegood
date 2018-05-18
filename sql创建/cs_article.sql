# Host: localhost  (Version: 5.5.53)
# Date: 2017-08-07 20:00:21
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "cs_article"
#

DROP TABLE IF EXISTS `cs_article`;
CREATE TABLE `cs_article` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `a_cid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID（column/id）',
  `a_title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `a_content` longtext COMMENT '内容',
  `a_addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `a_updatetime` int(11) DEFAULT NULL COMMENT '最后更新时间',
  `a_click` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `sort_num` int(11) DEFAULT NULL COMMENT '排序',
  `is_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐，默认为0，1为推荐',
  `a_recid` int(11) DEFAULT NULL COMMENT '推荐位ID',
  `a_aid` int(11) NOT NULL DEFAULT '0' COMMENT '发布人ID（admin/id）',
  `a_img` varchar(255) DEFAULT NULL COMMENT '封面图',
  `a_keywords` varchar(255) DEFAULT NULL COMMENT '文章关键词',
  `a_seo_title` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `a_seo_keywords` varchar(255) DEFAULT NULL COMMENT 'SEO关键词',
  `a_seo_description` varchar(255) DEFAULT NULL COMMENT 'SEO描述',
  PRIMARY KEY (`a_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文章表';

#
# Data for table "cs_article"
#

/*!40000 ALTER TABLE `cs_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_article` ENABLE KEYS */;
