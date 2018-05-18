# Host: localhost  (Version: 5.5.53)
# Date: 2017-08-08 18:44:28
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "cs_article_rec_data"
#

DROP TABLE IF EXISTS `cs_article_rec_data`;
CREATE TABLE `cs_article_rec_data` (
  `article_id` int(11) NOT NULL DEFAULT '0' COMMENT '文章id',
  `title` varchar(255) DEFAULT NULL COMMENT '文章标题',
  `rec_id` int(11) NOT NULL DEFAULT '0' COMMENT '推荐位id',
  `data` text COMMENT '推荐位需要的内容数据',
  `rec_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `sort_num` int(11) NOT NULL DEFAULT '999' COMMENT '排序数字'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "cs_article_rec_data"
#

/*!40000 ALTER TABLE `cs_article_rec_data` DISABLE KEYS */;
INSERT INTO `cs_article_rec_data` VALUES (16,'22',4,'a:4:{s:7:\"a_title\";s:2:\"22\";s:5:\"a_img\";s:0:\"\";s:9:\"a_addtime\";s:19:\"1970-01-01 08:32:50\";s:8:\"sort_num\";s:1:\"1\";}',1502187899,1),(15,NULL,7,'a:4:{s:7:\"a_title\";s:2:\"11\";s:5:\"a_img\";s:0:\"\";s:9:\"a_addtime\";s:19:\"1970-01-01 08:33:37\";s:8:\"sort_num\";s:1:\"2\";}',1502183058,999),(15,NULL,4,'a:4:{s:7:\"a_title\";s:2:\"11\";s:5:\"a_img\";s:0:\"\";s:9:\"a_addtime\";s:19:\"1970-01-01 08:33:37\";s:8:\"sort_num\";s:1:\"2\";}',1502183058,999),(17,NULL,4,'a:4:{s:7:\"a_title\";s:12:\"测试一下\";s:5:\"a_img\";s:0:\"\";s:9:\"a_addtime\";s:19:\"1970-01-01 08:32:50\";s:8:\"sort_num\";s:1:\"4\";}',1502183860,14),(17,NULL,7,'a:4:{s:7:\"a_title\";s:12:\"测试一下\";s:5:\"a_img\";s:0:\"\";s:9:\"a_addtime\";s:19:\"1970-01-01 08:32:50\";s:8:\"sort_num\";s:1:\"4\";}',1502183860,14),(18,'eee',4,'a:4:{s:7:\"a_title\";s:3:\"eee\";s:5:\"a_img\";s:0:\"\";s:9:\"a_addtime\";s:19:\"2017-08-08 18:23:31\";s:8:\"sort_num\";s:3:\"999\";}',1502187824,999),(18,'eee',7,'a:4:{s:7:\"a_title\";s:3:\"eee\";s:5:\"a_img\";s:0:\"\";s:9:\"a_addtime\";s:19:\"2017-08-08 18:23:31\";s:8:\"sort_num\";s:3:\"999\";}',1502187824,999),(16,'22',7,'a:4:{s:7:\"a_title\";s:2:\"22\";s:5:\"a_img\";s:0:\"\";s:9:\"a_addtime\";s:19:\"1970-01-01 08:32:50\";s:8:\"sort_num\";s:1:\"1\";}',1502187899,1);
/*!40000 ALTER TABLE `cs_article_rec_data` ENABLE KEYS */;
