
DROP TABLE IF EXISTS `boiler_project_advice`;
CREATE TABLE IF NOT EXISTS `boiler_project_advice` (
  `advice_id` int(11) NOT NULL AUTO_INCREMENT,
  `advice_projectid` int(11) DEFAULT NULL COMMENT '???id',
  `advice_user` int(11) DEFAULT NULL COMMENT '??????',
  `advice_content` text COMMENT '????',
  `advice_addtime` int(11) DEFAULT NULL COMMENT '???',
  PRIMARY KEY (`advice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='????????' AUTO_INCREMENT=1 ;

ALTER TABLE `boiler_project` ADD `project_del_flag` TINYINT(1) NULL DEFAULT '0' COMMENT '????????0????1???' , ADD `project_del_reason` TEXT NULL COMMENT '??????' ; 
ALTER TABLE `boiler_project` ADD `project_notsame_id` VARCHAR(300) NULL COMMENT '??????????id' ; 
