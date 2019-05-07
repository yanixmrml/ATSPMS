-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 16, 2018 at 06:35 PM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ats_pms`
--
DROP DATABASE IF EXISTS `ats_pms`;
CREATE DATABASE IF NOT EXISTS `ats_pms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ats_pms`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `change_password`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `change_password` (IN `old_password` TEXT, IN `new_password` TEXT, IN `user_id` INT, OUT `process_status` TINYINT)  NO SQL
BEGIN
	DECLARE valid_counter INT DEFAULT 0;
    
    SELECT COUNT(u.user_id) INTO valid_counter FROM user u WHERE u.user_id = user_id AND u.password = 		PASSWORD(old_password);
    
    IF valid_counter > 0 THEN
        UPDATE user u SET u.password = PASSWORD(new_password)
        WHERE u.user_id = user_id AND u.password = PASSWORD(old_password);

        SELECT COUNT(u.user_id) INTO process_status FROM user u WHERE u.user_id = user_id AND u.password = PASSWORD(new_password);
    ELSE  
    	SET process_status = 0;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `connected_load`
--

DROP TABLE IF EXISTS `connected_load`;
CREATE TABLE IF NOT EXISTS `connected_load` (
  `con_load_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `priority` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `schedule_on` timestamp NULL DEFAULT NULL,
  `schedule_off` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image_on` text NOT NULL,
  `image_off` text NOT NULL,
  PRIMARY KEY (`con_load_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `connected_load`
--

INSERT INTO `connected_load` (`con_load_id`, `description`, `priority`, `status`, `schedule_on`, `schedule_off`, `last_updated`, `user_id`, `image_on`, `image_off`) VALUES
(1, 'Lighting Load for Room 1', 3, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-04-16 18:30:27', 1, '', ''),
(2, 'Equipment Loads', 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-04-16 18:27:48', 1, '', ''),
(3, 'Aircon Unit for Room 3', 3, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-04-16 18:21:48', 1, '', ''),
(4, 'Lighting Loads for Living Room', 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-04-16 18:21:24', 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `load_side`
--

DROP TABLE IF EXISTS `load_side`;
CREATE TABLE IF NOT EXISTS `load_side` (
  `source` int(11) NOT NULL,
  `voltage` float NOT NULL,
  `current` float NOT NULL,
  `power` float NOT NULL,
  `frequency` float NOT NULL,
  `status` int(11) NOT NULL,
  `datetime` timestamp NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `publish_by` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `posted_on` timestamp NOT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `voltage_max` float NOT NULL,
  `voltage_min` float NOT NULL,
  `frequency_critical_max` float NOT NULL,
  `frequency_critical_min` float NOT NULL,
  `current_max` float NOT NULL,
  `current_min` float NOT NULL,
  `power_max` float NOT NULL,
  `power_min` float NOT NULL,
  `power_goal` float NOT NULL,
  `power_factor_goal` float NOT NULL,
  `battery_level` float NOT NULL,
  `temperature_level` float NOT NULL,
  `cost_pkwh` float NOT NULL,
  `cost_goal` float NOT NULL,
  `nominal_voltage` float NOT NULL,
  `nominal_frequency` float NOT NULL,
  `start_effectivity_date` date DEFAULT NULL,
  `last_updated_on` timestamp NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `voltage_max`, `voltage_min`, `frequency_critical_max`, `frequency_critical_min`, `current_max`, `current_min`, `power_max`, `power_min`, `power_goal`, `power_factor_goal`, `battery_level`, `temperature_level`, `cost_pkwh`, `cost_goal`, `nominal_voltage`, `nominal_frequency`, `start_effectivity_date`, `last_updated_on`, `user_id`) VALUES
(1, 240, 220, 65, 55, 70, 50, 7, 8, 9, 10, 11, 12, 13, 14, 230, 60, '2018-04-10', '2018-04-15 16:17:29', 1),
(2, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 230, 60, '2018-04-06', '2018-04-11 17:02:32', 1),
(3, 240, 220, 65.5, 54.5, 100, 50, 100000, 50000, 500, 0.85, 11.5, 60, 0.785, 1000, 230, 60, '2018-04-04', '2018-04-13 16:53:01', 1),
(4, 240, 220, 65, 55, 70, 30, 100, 30, 30, 0.85, 10.5, 60, 0.75, 300, 230, 60, '2018-04-12', '2018-04-16 16:37:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

DROP TABLE IF EXISTS `source`;
CREATE TABLE IF NOT EXISTS `source` (
  `source_id` int(11) NOT NULL AUTO_INCREMENT,
  `voltage` float NOT NULL,
  `current` float NOT NULL,
  `power` float NOT NULL,
  `frequency` float NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_selected` tinyint(4) NOT NULL,
  `ambient_temperature` float NOT NULL,
  `last_updated` timestamp NOT NULL,
  PRIMARY KEY (`source_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `source`
--

INSERT INTO `source` (`source_id`, `voltage`, `current`, `power`, `frequency`, `status`, `is_selected`, `ambient_temperature`, `last_updated`) VALUES
(1, 231.421, 52.7205, 9101.48, 61.7205, 1, 1, 53, '2018-04-12 16:00:00'),
(2, 1.42051, 1.42051, 1.42051, 1.42051, 0, 0, 53, '2018-04-12 16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(60) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` text NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `contact_number` text NOT NULL,
  `status` int(11) NOT NULL,
  `picture` text NOT NULL,
  `access_level` smallint(6) NOT NULL,
  `last_login` timestamp NOT NULL,
  `date_created` timestamp NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `lastname`, `firstname`, `username`, `password`, `email_address`, `contact_number`, `status`, `picture`, `access_level`, `last_login`, `date_created`) VALUES
(1, 'Longhas', 'Mark Ryan', 'markryan.longhas', '*7726D027DCAAE0DE719D4937EFDB8F598A22B458', 'yanixmrml@gmail.com', '+63995772230', 1, '', 1, '2018-04-16 18:23:50', '2018-04-02 05:10:18'),
(2, 'Pame', 'Rey Kevin', 'reykevin.pame', '*FEEDEEAD500378B5D89BCECBD9B86556238FA21F', 'reykevin@gmail.com', '+639091905781', 1, '', 0, '2018-04-10 16:59:22', '2018-04-07 16:15:22'),
(4, 'Alindong', 'John Ryan', 'johnryan.alindong', '*CC67043C7BCFF5EEA5566BD9B1F3C74FD9A5CF5D', '', '', 0, '', 0, '2018-04-10 16:59:22', '2018-04-07 17:07:54'),
(3, 'Damao', 'Jahid Bin', 'jahidbin.damao', '*889F209F19B77556B778BC7994C4A7BF5C26C429', 'jahidbin@gmail.com', '+639167779703', 1, '', 0, '2018-04-10 16:59:22', '2018-04-07 16:27:32'),
(5, 'Manasan', 'Kent Lloyd', 'kentlloyd.manasan', '*874C8B7A1DCD86ECC3065F34A76A0E0D67ABC626', '', '', 0, '', 0, '2018-04-10 16:59:22', '2018-04-07 17:17:21'),
(6, 'Minoza Sr', 'Arthur', 'arthur.minozasr', '*58A1D88E305F07B86D61AF4D4DC547BA16B37870', '', '', 1, '', 1, '2018-04-10 16:59:22', '2018-04-09 08:22:22'),
(7, 'Amores', 'Allen', 'allen.amores', '*5CE59D183925AF99A2D2058DB9EBE0EA06B3AF02', '', '', 1, '', 0, '2018-04-16 03:54:32', '2018-04-10 03:58:50'),
(8, 'Cabilogan', 'Eryl Kean', 'erylkean.cabilogan', '*CC67043C7BCFF5EEA5566BD9B1F3C74FD9A5CF5D', '', '', 1, '', 0, '0000-00-00 00:00:00', '2018-04-12 08:46:07');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
