-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-18 16:59:58
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
-- 表的结构 `boiler_selection_build`
--

DROP TABLE IF EXISTS `boiler_selection_build`;
CREATE TABLE IF NOT EXISTS `boiler_selection_build` (
  `build_id` int(11) NOT NULL AUTO_INCREMENT,
  `build_name` varchar(64) DEFAULT NULL COMMENT '属性的名字',
  `build_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常 0禁用',
  `build_parent` int(11) DEFAULT '0' COMMENT '属性的父级id',
  `build_addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`build_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=131 ;

--
-- 转存表中的数据 `boiler_selection_build`
--

INSERT INTO `boiler_selection_build` (`build_id`, `build_name`, `build_status`, `build_parent`, `build_addtime`) VALUES
(1, '采暖', 1, 0, NULL),
(2, '住宅', 1, 1, NULL),
(3, '办公楼或学校', 1, 1, NULL),
(4, '医院或幼儿园', 1, 1, NULL),
(5, '旅馆', 1, 1, NULL),
(6, '图书馆', 1, 1, NULL),
(7, '商店', 1, 1, NULL),
(8, '单层住宅', 1, 1, NULL),
(9, '食堂或餐厅', 1, 1, NULL),
(10, '影剧院', 1, 1, NULL),
(11, '大礼堂或体育馆', 1, 1, NULL),
(12, '热水', 1, 0, NULL),
(13, '住宅', 1, 31, NULL),
(14, '住宅别墅', 1, 31, NULL),
(15, '酒店式公寓', 1, 31, NULL),
(16, '宿舍', 1, 31, NULL),
(17, '招待所或培训中心', 1, 31, NULL),
(18, '宾馆', 1, 31, NULL),
(19, '医院住院部', 1, 31, NULL),
(20, '疗养院', 1, 31, NULL),
(21, '养老院', 1, 31, NULL),
(22, '幼儿园', 1, 31, NULL),
(23, '公共浴室', 1, 31, NULL),
(24, '理发室或美容院', 1, 31, NULL),
(25, '洗衣房', 1, 31, NULL),
(26, '餐饮厅', 1, 31, NULL),
(27, '办公楼', 1, 31, NULL),
(28, '健身中心', 1, 31, NULL),
(29, '体育馆（场）淋浴', 1, 31, NULL),
(30, '会议厅', 1, 31, NULL),
(31, '全日供水', 1, 12, NULL),
(32, '定时供水', 1, 12, NULL),
(33, '住宅、旅馆、别墅、酒店式公寓', 1, 32, NULL),
(34, '宿舍、招待所、培训中心', 1, 32, NULL),
(35, '餐饮业', 1, 32, NULL),
(36, '幼儿园', 1, 32, NULL),
(37, '托儿所', 1, 32, NULL),
(38, '医院、疗养院', 1, 32, NULL),
(39, '公共浴室', 1, 32, NULL),
(40, '办公楼', 1, 32, NULL),
(41, '理发室、美容院', 1, 32, NULL),
(42, '实验室', 1, 32, NULL),
(43, '剧场', 1, 32, NULL),
(44, '体育场馆', 1, 32, NULL),
(45, '工业企业生活间', 1, 32, NULL),
(46, '带有淋浴器的浴盆', 1, 33, NULL),
(47, '无淋浴器的浴盆', 1, 33, NULL),
(48, '淋浴器', 1, 33, NULL),
(49, '洗脸盆（池）', 1, 33, NULL),
(50, '盥洗槽水嘴', 1, 33, NULL),
(51, '有淋浴小间', 1, 34, NULL),
(52, ' 无淋浴小间 ', 1, 34, NULL),
(53, ' 盥洗槽水嘴 ', 1, 34, NULL),
(54, ' 洗涤盆（池） ', 1, 35, NULL),
(55, ' 洗脸盆（工作人员） ', 1, 35, NULL),
(56, ' 淋浴器 ', 1, 35, NULL),
(57, ' 洗脸盆（顾客） ', 1, 35, NULL),
(58, ' 浴盆 ', 1, 36, NULL),
(59, ' 淋浴器 ', 1, 36, NULL),
(60, ' 盥洗槽水嘴 ', 1, 36, NULL),
(61, ' 洗涤盆（池） ', 1, 36, NULL),
(62, ' 浴盆 ', 1, 37, NULL),
(63, ' 淋浴器 ', 1, 37, NULL),
(64, ' 盥洗槽水嘴 ', 1, 37, NULL),
(65, ' 洗涤盆（池） ', 1, 37, NULL),
(66, ' 洗手盆 ', 1, 38, NULL),
(67, ' 洗涤盆（池） ', 1, 38, NULL),
(68, ' 淋浴器 ', 1, 38, NULL),
(69, ' 浴盆 ', 1, 38, NULL),
(70, ' 浴盆 ', 1, 39, NULL),
(71, ' 有淋浴小间 ', 1, 39, NULL),
(72, ' 无淋浴小间 ', 1, 39, NULL),
(73, ' 洗脸盆 ', 1, 39, NULL),
(74, ' 洗手盆 ', 1, 40, NULL),
(75, ' 洗脸盆 ', 1, 41, NULL),
(76, ' 洗脸盆 ', 1, 42, NULL),
(77, ' 洗手盆 ', 1, 42, NULL),
(78, ' 淋浴器 ', 1, 43, NULL),
(79, ' 演员用洗脸盆 ', 1, 43, NULL),
(80, ' 淋浴器 ', 1, 44, NULL),
(81, ' 一般车间淋浴器 ', 1, 45, NULL),
(82, ' 脏车间淋浴器 ', 1, 45, NULL),
(83, ' 一般车间水龙头 ', 1, 45, NULL),
(84, ' 脏车间水龙头 ', 1, 45, NULL),
(85, ' 使用人数 ', 1, 13, NULL),
(86, ' 热水供应方式 ', 1, 13, NULL),
(87, '自备热水供应', 1, 86, NULL),
(88, '集中热水供应', 1, 86, NULL),
(89, ' 使用人数 ', 1, 14, NULL),
(90, ' 使用人数 ', 1, 15, NULL),
(91, ' 使用人数 ', 1, 16, NULL),
(92, '宿舍类型', 1, 16, NULL),
(93, '一类、二类', 1, 92, NULL),
(94, '三类、四类', 1, 92, NULL),
(95, ' 使用人数 ', 1, 17, NULL),
(96, '主要用水房间情况', 1, 17, NULL),
(97, '设公用盥洗室', 1, 96, NULL),
(98, '设公用盥洗室、淋浴室', 1, 96, NULL),
(99, '设公用盥洗室、淋浴室、洗衣室', 1, 96, NULL),
(100, '设单独卫生间、公用洗衣室', 1, 96, NULL),
(101, '总床位数', 1, 18, NULL),
(102, '每日员工数', 1, 18, NULL),
(103, '总床位数', 1, 19, NULL),
(104, '每日员工数', 1, 19, NULL),
(105, '主要用水房间情况', 1, 19, NULL),
(106, '设公用盥洗室', 1, 105, NULL),
(107, '设公用盥洗室、淋浴室', 1, 105, NULL),
(108, '设独立卫生间', 1, 105, NULL),
(109, '总床位数', 1, 20, NULL),
(110, '总床位数', 1, 21, NULL),
(111, '使用人数', 1, 22, NULL),
(112, '有无住宿', 1, 22, NULL),
(113, '有住宿', 1, 112, NULL),
(114, '无住宿', 1, 112, NULL),
(115, '每日顾客数', 1, 23, NULL),
(116, '洗浴设施', 1, 23, NULL),
(117, '淋浴', 1, 116, NULL),
(118, '淋浴、盆浴', 1, 116, NULL),
(119, '淋浴、桑拿浴', 1, 116, NULL),
(120, '每日顾客数', 1, 24, NULL),
(121, '每日干衣重量', 1, 25, NULL),
(122, '每日顾客数', 1, 26, NULL),
(123, '餐厅类别', 1, 26, NULL),
(124, '营业餐厅', 1, 123, NULL),
(125, '快餐店、职工及学生餐厅', 1, 123, NULL),
(126, '酒吧、咖啡厅、茶座、卡拉OK房', 1, 123, NULL),
(127, '使用人数', 1, 27, NULL),
(128, '使用人数', 1, 28, NULL),
(129, '使用人数', 1, 29, NULL),
(130, '使用人数', 1, 30, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `boiler_selection_heating_attr`
--

DROP TABLE IF EXISTS `boiler_selection_heating_attr`;
CREATE TABLE IF NOT EXISTS `boiler_selection_heating_attr` (
  `heating_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `heating_history_id` int(11) DEFAULT NULL COMMENT '选型历史id',
  `heating_build_type` tinyint(1) DEFAULT NULL COMMENT '建筑类别',
  `heating_floor_low` int(3) DEFAULT NULL COMMENT '采暖楼层低',
  `heating_floor_high` int(3) DEFAULT NULL COMMENT '采暖楼层高',
  `heating_floor_height` decimal(3,2) DEFAULT NULL COMMENT '单层高度',
  `heating_area` decimal(10,2) DEFAULT NULL COMMENT '采暖面积',
  `heating_type` tinyint(1) DEFAULT NULL COMMENT '采暖形式',
  `heating_usetime_type` tinyint(1) DEFAULT NULL COMMENT '使用时间',
  `heating_addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`heating_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='选型采暖参数' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `boiler_selection_history`
--

DROP TABLE IF EXISTS `boiler_selection_history`;
CREATE TABLE IF NOT EXISTS `boiler_selection_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `history_customer` varchar(300) DEFAULT NULL COMMENT '客户名称',
  `history_guolu_position` tinyint(1) DEFAULT NULL COMMENT '锅炉房预留位置',
  `history_guolu_height` int(3) DEFAULT NULL COMMENT '预计锅炉房高度',
  `history_is_condensate` int(11) DEFAULT NULL COMMENT '是否冷凝',
  `history_is_lownitrogen` int(11) DEFAULT NULL COMMENT '是否低氮',
  `history_application` tinyint(1) DEFAULT NULL COMMENT '锅炉用途',
  `history_type` int(3) DEFAULT NULL COMMENT '锅炉形式',
  `history_guolu_id` int(11) DEFAULT NULL COMMENT '选择的锅炉id',
  `history_guolu_num` int(3) DEFAULT NULL COMMENT '选择的锅炉台数',
  `history_remark` text COMMENT '备注',
  `history_user` int(11) DEFAULT NULL COMMENT '所属人ID',
  `history_status` tinyint(1) DEFAULT '0' COMMENT '状态 1有效 0无效',
  `history_addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `history_lastupdate` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='选型历史' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `boiler_selection_hotwater_attr`
--

DROP TABLE IF EXISTS `boiler_selection_hotwater_attr`;
CREATE TABLE IF NOT EXISTS `boiler_selection_hotwater_attr` (
  `hotwater_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `hotwater_history_id` int(11) DEFAULT NULL COMMENT '选型历史ID',
  `hotwater_use_type` varchar(1) DEFAULT NULL COMMENT '使用类型',
  `hotwater_build_type` int(11) DEFAULT NULL COMMENT '建筑类型',
  `hotwater_buildattr_id` int(11) DEFAULT NULL COMMENT '属性ID',
  `hotwater_attr_num` int(11) DEFAULT NULL COMMENT '属性数量',
  `hotwater_addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`hotwater_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='选型热水参数' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE `boiler_selection_history` CHANGE `history_guolu_height` `history_guolu_height` DECIMAL(3,2) NULL DEFAULT NULL COMMENT '预计锅炉房高度';
ALTER TABLE `boiler_selection_hotwater_attr` CHANGE `hotwater_use_type` `hotwater_use_type` INT(5) NULL DEFAULT NULL COMMENT '使用类型';