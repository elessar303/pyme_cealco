-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 06-02-2012 a las 16:27:09
-- Versión del servidor: 5.1.58
-- Versión de PHP: 5.3.6-13ubuntu3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `SELECTRA_CONF_PYME`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomempresa`
--

CREATE TABLE IF NOT EXISTS `nomempresa` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `bd` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `bd_contabilidad` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `bd_nomina` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `nomempresa`
--

INSERT INTO `nomempresa` (`codigo`, `nombre`, `bd`, `bd_contabilidad`, `bd_nomina`) VALUES
(1, 'LICORERIA LAS FLORES S.R.L.', 'pyme_administrativo', 'pyme_contabilidad', 'pyme_nomina');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
