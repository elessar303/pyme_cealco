-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2018 at 07:34 PM
-- Server version: 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `selectra_conf_pyme`
--

-- --------------------------------------------------------

--
-- Table structure for table `nomempresa`
--

CREATE TABLE `nomempresa` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `bd` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `bd_contabilidad` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `bd_nomina` varchar(250) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `nomempresa`
--

INSERT INTO `nomempresa` (`codigo`, `nombre`, `bd`, `bd_contabilidad`, `bd_nomina`) VALUES
(1, 'PYME PRUEBA', 'pyme_prueba_standar_cealco', 'pyme_contabilidad', 'pyme_nomina');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nomempresa`
--
ALTER TABLE `nomempresa`
  ADD PRIMARY KEY (`codigo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nomempresa`
--
ALTER TABLE `nomempresa`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
