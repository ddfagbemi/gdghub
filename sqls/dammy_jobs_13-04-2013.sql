-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 13, 2013 at 07:53 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gdg_devplatform`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(36) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `slug` varchar(200) NOT NULL,
  `registration_link` varchar(200) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `user_id`, `name`, `description`, `created`, `modified`, `slug`, `registration_link`, `published`, `views`) VALUES
(1, '51429359-5964-4404-87c8-0bf2ae34c9d9', 'Software Engineer @ Verbum networks', 'A software engineer is needed urgently at Verbum networks. Qualifications are: BSc, MSc.', '2013-03-21 05:44:16', '2013-03-21 05:44:16', 'software-engineer-verbum-networks', '#', 1, 1),
(2, '51429359-5964-4404-87c8-0bf2ae34c9d9', 'Web Designer @ SleekSoft', 'A web designer proficient in the use of graphics design tools is needed at Sleek Soft technologies', '2013-03-21 06:18:05', '2013-03-21 06:18:05', 'web-designer-sleeksoft', NULL, 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
