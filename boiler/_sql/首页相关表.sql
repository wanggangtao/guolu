-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-11-22 01:14:42
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `case_boiler`
--

-- --------------------------------------------------------

--
-- 表的结构 `boiler_web_distribution`
--

CREATE TABLE IF NOT EXISTS `boiler_web_distribution` (
  `distribution_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `distribution_title` varchar(200) DEFAULT NULL COMMENT '标题',
  `distribution_detail` text COMMENT '详情',
  `distribution_address` varchar(200) DEFAULT NULL COMMENT '地址',
  `distribution_picurl` varchar(100) DEFAULT NULL,
  `distribution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`distribution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='渠道分销' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `boiler_web_projectcase`
--

CREATE TABLE IF NOT EXISTS `boiler_web_projectcase` (
  `projectcase_id` int(10) NOT NULL AUTO_INCREMENT,
  `projectcase_title` varchar(200) DEFAULT NULL COMMENT '标题',
  `projectcase_type` tinyint(1) DEFAULT NULL COMMENT '类型',
  `projectcase_picurl` varchar(100) DEFAULT NULL COMMENT '图片',
  `projectcase_content` text COMMENT '内容',
  `projectcase_http` varchar(100) DEFAULT NULL COMMENT '链接地址',
  `projectcase_order` int(10) DEFAULT NULL COMMENT '排序',
  `projectcase_count` int(11) DEFAULT NULL COMMENT '浏览次数',
  `projectcase_addtime` int(11) DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`projectcase_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='项目案例' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `boiler_webcontent` ADD `content_subtitle` VARCHAR(100) NULL COMMENT '副标题' AFTER `content_title`;