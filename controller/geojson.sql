-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2026 at 12:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `geojson`
--

-- --------------------------------------------------------

--
-- Table structure for table `community_health_reports`
--

CREATE TABLE `community_health_reports` (
  `id` int(11) NOT NULL,
  `barangay_name` varchar(100) DEFAULT NULL,
  `flood_risk` varchar(50) DEFAULT NULL,
  `health_impact_score` decimal(3,2) DEFAULT NULL,
  `polygon_coordinates` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_health_reports`
--

INSERT INTO `community_health_reports` (`id`, `barangay_name`, `flood_risk`, `health_impact_score`, `polygon_coordinates`) VALUES
(1, 'Singcang-Airpot', 'High', 3.65, '[[[122.9280, 10.6620], [122.9380, 10.6620], [122.9380, 10.6520], [122.9280, 10.6520], [122.9280, 10.6620]]]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `community_health_reports`
--
ALTER TABLE `community_health_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `community_health_reports`
--
ALTER TABLE `community_health_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
