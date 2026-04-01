-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 13, 2026 at 10:28 PM
-- Server version: 5.7.44
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `downtown_wp`
--

-- --------------------------------------------------------

--
-- Table structure for table `sn_listing_category`
--

CREATE TABLE `sn_listing_category` (
  `categoryId` int(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `categoryTitle` varchar(80) DEFAULT NULL,
  `categoryURL` varchar(255) DEFAULT NULL,
  `categoryShortCode` varchar(100) NOT NULL,
  `categoryStatus` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sn_listing_category`
--

INSERT INTO `sn_listing_category` (`categoryId`, `parent`, `categoryTitle`, `categoryURL`, `categoryShortCode`, `categoryStatus`) VALUES
(1, 0, 'Food & Drink', 'food-drink', '', '1'),
(2, 0, 'Shopping', 'shopping', '', '1'),
(3, 0, 'Hotel & Lodging', 'hotel-lodging', '', '1'),
(13, 0, 'Parking', 'parking', '', '1'),
(14, 0, 'Cafeteria', 'cafeteria', '', '0'),
(15, 0, 'Professional Services', 'professional-services', '', '1'),
(16, 0, 'Beauty & Salon Services', 'beauty-salon-services', '', '1'),
(17, 0, 'Health & Fitness', 'health-fitness', '', '1'),
(18, 0, 'Performance & Events', 'performance-events', '', '1'),
(19, 0, 'City & Government', 'city-government', '', '1'),
(23, 0, 'Things To Do', 'things-to-do', '', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sn_listing_category`
--
ALTER TABLE `sn_listing_category`
  ADD PRIMARY KEY (`categoryId`),
  ADD UNIQUE KEY `categoryTitle` (`categoryTitle`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sn_listing_category`
--
ALTER TABLE `sn_listing_category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
