-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2013 at 10:26 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eldercare`
--

-- --------------------------------------------------------

--
-- Table structure for table `health_care_providers`
--

CREATE TABLE IF NOT EXISTS `health_care_providers` (
  `hcp_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `contact_person_name` varchar(100) DEFAULT NULL,
  `contact_person_position` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `telephone` varchar(25) DEFAULT NULL,
  `zipcode` int(11) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `description` text,
  `price_from` varchar(25) DEFAULT NULL,
  `price_to` varchar(25) DEFAULT NULL,
  `pricing` enum('conservative','moderate','expensive') DEFAULT NULL,
  `accommodation_type` enum('Small Assisted Living/ Group Homes','Large Assisted Living Home','Senior Apartments/ Independent Living','Senior Communities','Hospice Units','In Home Non-Medical Care Giving') DEFAULT NULL,
  `number_can_accommodate` int(11) NOT NULL,
  `number_of_bedrooms` int(11) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `approved` tinyint(1) DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `date_updated` timestamp NULL DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`hcp_id`),
  UNIQUE KEY `email_unique` (`email`),
  KEY `fk1` (`zipcode`),
  KEY `fk2` (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `health_care_providers`
--

INSERT INTO `health_care_providers` (`hcp_id`, `name`, `contact_person_name`, `contact_person_position`, `email`, `password`, `telephone`, `zipcode`, `location`, `description`, `price_from`, `price_to`, `pricing`, `accommodation_type`, `number_can_accommodate`, `number_of_bedrooms`, `image_id`, `published`, `approved`, `suspended`, `date_updated`, `date_created`) VALUES
(4, 'Grayscalish Houses', 'raymond baldonado', 'ceo', 'this@starfi.sh', 'fe703d258c7ef5f50b71e06565a65aa07194907f', '', 345, 'San Juan s', 'This is a sample description, right?s', '3000', '6000', 'conservative', 'In Home Non-Medical Care Giving', 12, 17, 4, 1, 1, 0, '2012-11-20 06:50:04', '2012-12-31 16:00:00'),
(5, 'IMAT CARE', 'raymart marasigan', 'manager', 'raymart.marasigan@starfi.sh', '67a74306b06d0c01624fe0d0249a570f4d093747', '12345', NULL, NULL, NULL, NULL, NULL, 'moderate', 'Small Assisted Living/ Group Homes', 0, 0, NULL, 0, NULL, 0, '2012-11-20 06:07:05', '2013-01-01 16:00:00'),
(12, 'Gertrude Elder', 'Gertrude Lugtu', 'owner', 'gert.lugtu@starfi.sh', 'fe703d258c7ef5f50b71e06565a65aa07194907f', '867856456', 36310, 'Cubao', 'This is description', '1000', '2000', 'expensive', 'Large Assisted Living Home', 0, 0, 11, 1, 0, 0, '2012-11-29 11:01:51', '2013-01-05 16:00:00'),
(13, 'Deni', 'Monching', 'Developer', 'denileanifernandez@gmail.com', 'ff39784590051424e86581e88e12bb2ebaad302e', '09287332547', 1, 'chicago', 'kndjwgdjwb', '500', '1000', 'conservative', 'Senior Apartments/ Independent Living', 0, 0, NULL, 10, NULL, 0, NULL, '2013-01-06 16:00:00'),
(15, 'Monskii', 'Raymohnd', 'CEO', 'raymond.baldonado@starfoot.sh', 'fe703d258c7ef5f50b71e06565a65aa07194907f', '09062702875', 29280, '2623 San Jose Street', NULL, '123', '123123', NULL, 'Hospice Units', 0, 0, NULL, NULL, 1, 0, NULL, '2012-11-19 06:53:13'),
(16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3000', '5000', NULL, 'Senior Communities', 0, 0, NULL, 1, 1, 0, '2012-11-20 10:07:50', '2012-11-20 10:07:50');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `health_care_providers`
--
ALTER TABLE `health_care_providers`
  ADD CONSTRAINT `health_care_providers_ibfk_1` FOREIGN KEY (`zipcode`) REFERENCES `zipcodes` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `health_care_providers_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `hcp_images` (`image_id`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
