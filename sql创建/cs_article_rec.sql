# Host: localhost  (Version: 5.5.53)
# Date: 2017-08-08 18:44:20
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "cs_article_rec"
#

DROP TABLE IF EXISTS `cs_article_rec`;
CREATE TABLE `cs_article_rec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '中文标题',
  `name` varchar(255) DEFAULT NULL COMMENT '英文名字',
  `max_num` int(11) NOT NULL DEFAULT '0' COMMENT '最大数量',
  `sort_num` int(11) NOT NULL DEFAULT '999' COMMENT '排序数字',
  `thumb` varchar(255) DEFAULT NULL COMMENT '缩略图',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Data for table "cs_article_rec"
#

/*!40000 ALTER TABLE `cs_article_rec` DISABLE KEYS */;
INSERT INTO `cs_article_rec` VALUES (4,'首页热门活动11','hot12311',101,1,NULL),(7,'asd','asda',10,999,NULL);
/*!40000 ALTER TABLE `cs_article_rec` ENABLE KEYS */;
