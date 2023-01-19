-- phpMyAdmin SQL Dump
-- version 5.0.4deb2
-- https://www.phpmyadmin.net/
--
-- Host: mysql.info.unicaen.fr:3306
-- Generation Time: Nov 28, 2022 at 03:06 AM
-- Server version: 10.5.11-MariaDB-1
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `21914304_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `egyptologists`
--

CREATE TABLE `egyptologists` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `discovery` varchar(2043) DEFAULT NULL,
  `birth_year` int(11) DEFAULT NULL,
  `death_year` int(11) DEFAULT NULL,
  `image` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `egyptologists`
--

INSERT INTO `egyptologists` (`id`, `name`, `discovery`, `birth_year`, `death_year`, `image`) VALUES
(30, 'Howard Carter', 'the Tomb of Tutankhamun', 1874, 1939, 0),
(31, 'Jean-François Champollion', 'a working method to translate hieroglyphs.', 1790, 1832, 1),
(32, 'Auguste Mariette', 'the Serapeum of Saqqara.', 1808, 1894, 0),
(33, 'Hussein Bassir', 'the valley of Golden Mummies', 1973, 3001, 0),
(34, 'Sarah Parcak', '17 new pyramids using satellite imaging.', 1979, 3001, 0),
(35, 'Charles Edwin Wilbour', 'the Elephantine Papyri', 1833, 1896, 0),
(36, 'Günter Dreyer', 'the burial site of the kin (U-j), the earliest known large royal tomb of old Egypt.', 1943, 2019, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `egyptologists`
--
ALTER TABLE `egyptologists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `egyptologists`
--
ALTER TABLE `egyptologists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
