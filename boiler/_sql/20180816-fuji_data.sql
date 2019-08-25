-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-08-16 11:54:16
-- 服务器版本： 8.0.11
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boiler`
--

--
-- 转存表中的数据 `boiler_burner_attr`
--

INSERT INTO `boiler_burner_attr` (`burner_id`, `burner_vender`, `burner_version`, `burner_is_lownitrogen`, `burner_power`, `burner_boilerpower`, `burner_proid`) VALUES
(1, 22, '燃烧机型号', 1, 500, 500, 3);

--
-- 转存表中的数据 `boiler_dirt_separator_attr`
--

INSERT INTO `boiler_dirt_separator_attr` (`separator_id`, `separator_version`, `separator_diameter`, `separator_proid`) VALUES
(1, '除污器型号', 65, 3);

--
-- 转存表中的数据 `boiler_guolu_attr`
--

INSERT INTO `boiler_guolu_attr` (`guolu_id`, `guolu_version`, `guolu_vender`, `guolu_type`, `guolu_is_condensate`, `guolu_is_lownitrogen`, `guolu_ratedpower`, `guolu_inwater_t`, `guolu_outwater_t`, `guolu_pressure`, `guolu_fueltype`, `guolu_gas_consumption`, `guolu_fuel_consumption`, `guolu_flue_caliber`, `guolu_hauled_weight`, `guolu_hot_flow`, `guolu_interface_diameter`, `guolu_pressure_drop`, `guolu_length`, `guolu_width`, `guolu_height`, `guolu_smoke_height`, `guolu_water`, `guolu_proid`) VALUES
(3, '123', 9, 11, 15, 17, 350, 22, 34, 43, 'hj', 65, 56, 56, 1, 66, 76, 676, 88, 88, 88, 88, 88, 3),
(123, '1234', 12, 16, 15, 17, 700, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `boiler_hdys_attr`
--

CREATE TABLE `boiler_hdys_attr` (
  `hdys_id` int(11) NOT NULL,
  `hdys_vender` int(11) DEFAULT NULL COMMENT '厂家',
  `hdys_version` varchar(255) DEFAULT NULL COMMENT '型号',
  `hdys_outwater` int(11) DEFAULT NULL COMMENT '额定出水量',
  `hdys_proid` int(11) DEFAULT NULL,
  `hdys_weight` int(11) NOT NULL COMMENT '软水机重量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='全自动软水器';

--
-- 转存表中的数据 `boiler_hdys_attr`
--

INSERT INTO `boiler_hdys_attr` (`hdys_id`, `hdys_vender`, `hdys_version`, `hdys_outwater`, `hdys_proid`, `hdys_weight`) VALUES
(1, 21, '软水机型号', 500, 3, 1400);

--
-- 转存表中的数据 `boiler_pipeline_attr`
--

INSERT INTO `boiler_pipeline_attr` (`pipeline_id`, `pipeline_vender`, `pipeline_version`, `pipeline_flow`, `pipeline_lift`, `pipeline_speed`, `pipeline_motorpower`, `pipeline_npsh`, `pipeline_weight`, `pipeline_diameter`, `pipeline_proid`) VALUES
(1, 23, '型号啊啊', 120, 32, 100, 5000, 121, 2000, 5, 3);

--
-- 转存表中的数据 `boiler_syswater_pump_attr`
--

INSERT INTO `boiler_syswater_pump_attr` (`pump_id`, `pump_vender`, `pump_version`, `pump_flow`, `pump_lift`, `pump_speed`, `pump_motorpower`, `pump_npsh`, `pump_weight`, `pump_proid`) VALUES
(1, 21, '补水泵型号', 30, 113, 23, 2000, 123, 300, 3);

--
-- 转存表中的数据 `boiler_water_box_attr`
--

INSERT INTO `boiler_water_box_attr` (`box_id`, `box_version`, `box_nominal_capacity`, `box_available_capacity`, `box_length`, `box_width`, `box_height`, `box_weight`, `box_proid`) VALUES
(1, '水箱型号啊啊啊啊', 12, 12, 5, 6, 10, 1400, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boiler_hdys_attr`
--
ALTER TABLE `boiler_hdys_attr`
  ADD PRIMARY KEY (`hdys_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `boiler_hdys_attr`
--
ALTER TABLE `boiler_hdys_attr`
  MODIFY `hdys_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
