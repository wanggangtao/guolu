CREATE TABLE IF NOT EXISTS `boiler_version_iosreview` (
  `iosreview_id` int(3) NOT NULL AUTO_INCREMENT,
  `iosreview_version` varchar(32) NOT NULL COMMENT '°æ±¾ºÅ',
  `iosreview_isregister` tinyint(1) NOT NULL COMMENT 'ÊÇ·ñ×¢²á',
  PRIMARY KEY (`iosreview_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='iosÌáÉó×´Ì¬±í' AUTO_INCREMENT=2 ;

INSERT INTO `boiler_version_iosreview` (`iosreview_id`, `iosreview_version`, `iosreview_isregister`) VALUES
(1, '1.0.0', 1);
