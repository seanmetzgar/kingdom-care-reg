-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 11, 2017 at 05:00 PM
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
-- Database: `kcs_web_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `kcs_temp_users`
--

DROP TABLE IF EXISTS `kcs_temp_users`;
CREATE TABLE IF NOT EXISTS `kcs_temp_users` (
  `id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `password` text CHARACTER SET latin1 NOT NULL,
  `user_type` text NOT NULL,
  `first_name` text,
  `last_name` text,
  `street_address_1` text,
  `street_address_2` text,
  `zip_code` text,
  `remember` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`email`(40))
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kcs_temp_users`
--

INSERT INTO `kcs_temp_users` (`id`, `email`, `password`, `user_type`, `first_name`, `last_name`, `street_address_1`, `street_address_2`, `zip_code`, `remember`, `created`, `modified`) VALUES
(15, 'sean.metzgar@gmail.com2', 'sadfsdf', 'sitter', 'Sean', 'Metzgar', '1616 Millard St', '', '18017', 0, '2017-11-29 01:02:08', '2017-11-29 01:02:08');

--
-- Triggers `kcs_temp_users`
--
DROP TRIGGER IF EXISTS `sitter_UUID`;
DELIMITER $$
CREATE TRIGGER `sitter_UUID` BEFORE INSERT ON `kcs_temp_users` FOR EACH ROW begin
 SET new.created = now();
 SET new.modified = now();
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `kcs_temp_users_meta`
--

DROP TABLE IF EXISTS `kcs_temp_users_meta`;
CREATE TABLE IF NOT EXISTS `kcs_temp_users_meta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
