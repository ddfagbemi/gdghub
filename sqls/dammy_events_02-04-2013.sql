-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 02, 2013 at 08:36 PM
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
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(36) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text,
  `venue` varchar(300) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `slug` varchar(200) NOT NULL,
  `registration_link` varchar(200) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `published` tinyint(4) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `user_id`, `name`, `description`, `venue`, `start`, `end`, `slug`, `registration_link`, `views`, `published`, `created`) VALUES
(1, '51429359-5964-4404-87c8-0bf2ae34c9d9', 'QualComm Android Development Summit', 'This event is designed to teach Android developers the ways by which they could utilize QualComms sophisticated processor for building image matching applications.', 'C0-Creation Hub, Lagos, Nigeria', '2013-04-25 11:40:00', '2013-04-26 16:40:00', 'qualcomm-android-development-summit', '#', 6, 1, '2013-03-22 15:23:42'),
(6, '51429359-5964-4404-87c8-0bf2ae34c9d9', 'Information Security in Government Institutions', 'This event is focused on the importance of information security in Government Institutions.', 'GDG Hall, Garki, Abuja, Nigeria', '2013-03-30 16:00:00', '2013-03-31 18:00:00', 'information-security-in-government-institutions', '#', 1, 1, '2013-03-24 09:29:11');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
