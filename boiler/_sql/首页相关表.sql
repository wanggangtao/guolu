-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-11-22 01:14:42
-- �������汾�� 5.6.17
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
-- ��Ľṹ `boiler_web_distribution`
--

CREATE TABLE IF NOT EXISTS `boiler_web_distribution` (
  `distribution_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `distribution_title` varchar(200) DEFAULT NULL COMMENT '����',
  `distribution_detail` text COMMENT '����',
  `distribution_address` varchar(200) DEFAULT NULL COMMENT '��ַ',
  `distribution_picurl` varchar(100) DEFAULT NULL,
  `distribution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`distribution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='��������' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- ��Ľṹ `boiler_web_projectcase`
--

CREATE TABLE IF NOT EXISTS `boiler_web_projectcase` (
  `projectcase_id` int(10) NOT NULL AUTO_INCREMENT,
  `projectcase_title` varchar(200) DEFAULT NULL COMMENT '����',
  `projectcase_type` tinyint(1) DEFAULT NULL COMMENT '����',
  `projectcase_picurl` varchar(100) DEFAULT NULL COMMENT 'ͼƬ',
  `projectcase_content` text COMMENT '����',
  `projectcase_http` varchar(100) DEFAULT NULL COMMENT '���ӵ�ַ',
  `projectcase_order` int(10) DEFAULT NULL COMMENT '����',
  `projectcase_count` int(11) DEFAULT NULL COMMENT '�������',
  `projectcase_addtime` int(11) DEFAULT NULL COMMENT '����ʱ��',
  PRIMARY KEY (`projectcase_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='��Ŀ����' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `boiler_webcontent` ADD `content_subtitle` VARCHAR(100) NULL COMMENT '������' AFTER `content_title`;