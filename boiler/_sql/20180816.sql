DROP TABLE IF EXISTS `boiler_project_visitlog_comment`;
CREATE TABLE IF NOT EXISTS `boiler_project_visitlog_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `comment_visitlog_id` int(11) DEFAULT NULL COMMENT '拜访记录ID',
  `comment_comuser` int(11) DEFAULT NULL COMMENT '评论人ID',
  `comment_content` text COMMENT '评论内容',
  `comment_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='拜访记录评论表' AUTO_INCREMENT=1 ;




INSERT INTO boiler_project_visitlog_comment
 (comment_visitlog_id, comment_comuser, comment_content)
 SELECT visitlog_id, visitlog_comuser, visitlog_comment 
 FROM boiler_project_visitlog  
 WHERE visitlog_comuser is not null or visitlog_comment is not null;