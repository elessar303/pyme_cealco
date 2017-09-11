-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-09-2010 a las 10:39:08
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.2-1ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `onuvacolcon`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activosfijos`
--

CREATE TABLE IF NOT EXISTS `activosfijos` (
  `CODACT` int(10) NOT NULL,
  `TIPO` int(10) NOT NULL,
  `GRUZON` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `DECRIPAF` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `SEDEPRECIA` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `CODUBI` tinyint(4) NOT NULL,
  `COSTOCOMPRA` decimal(11,2) NOT NULL,
  `FECCOMPRA` date NOT NULL,
  `COSTOACTUAL` decimal(11,2) NOT NULL,
  `VIDAUTIL` int(11) NOT NULL,
  `FECINIDPR` date NOT NULL,
  `FECFIN` date NOT NULL,
  `VALRESI` decimal(11,2) NOT NULL,
  `DPRMENSUAL` decimal(11,2) NOT NULL,
  `DPRACUM` decimal(11,2) NOT NULL,
  `CTAGASTOS` varchar(27) COLLATE utf8_spanish_ci NOT NULL,
  `CTADPRACUM` varchar(27) COLLATE utf8_spanish_ci NOT NULL,
  `COMPLEMENTO` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `ESTADOAF` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `CTAREAL` varchar(27) COLLATE utf8_spanish_ci NOT NULL,
  `SERIAL1` varchar(21) COLLATE utf8_spanish_ci NOT NULL,
  `SERIAL2` varchar(21) COLLATE utf8_spanish_ci NOT NULL,
  `SERIAL3` varchar(21) COLLATE utf8_spanish_ci NOT NULL,
  `CODCCOS` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`CODACT`),
  KEY `Tipo` (`TIPO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcar la base de datos para la tabla `activosfijos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activosfijos_movimientos`
--

CREATE TABLE IF NOT EXISTS `activosfijos_movimientos` (
  `CODACT` int(10) NOT NULL,
  `TIPOACT` int(10) NOT NULL,
  `TIPOMOV` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `FECMOV` date NOT NULL,
  `MONMOV` decimal(11,2) NOT NULL,
  PRIMARY KEY (`CODACT`,`FECMOV`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcar la base de datos para la tabla `activosfijos_movimientos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activosfijos_tipos`
--

CREATE TABLE IF NOT EXISTS `activosfijos_tipos` (
  `CODIGOTA` int(10) NOT NULL,
  `DESCRIP` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `CUENTAREAL` varchar(27) COLLATE utf8_spanish_ci NOT NULL,
  `DEPRECIABLE` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `CUENTAGASTOS` varchar(27) COLLATE utf8_spanish_ci NOT NULL,
  `CUENTAACUM` varchar(27) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`CODIGOTA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcar la base de datos para la tabla `activosfijos_tipos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconaux`
--

CREATE TABLE IF NOT EXISTS `cwconaux` (
  `Cuenta` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Descrip` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Salant` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Salmes` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Salactu` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Debito` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Credito` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Nivel` int(11) NOT NULL DEFAULT '0',
  `Tipo` char(2) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para generar reporte balance';

--
-- Volcar la base de datos para la tabla `cwconaux`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconcco`
--

CREATE TABLE IF NOT EXISTS `cwconcco` (
  `Codccos` int(11) NOT NULL AUTO_INCREMENT,
  `Descrip` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Codccos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cwconcco`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconcue`
--

CREATE TABLE IF NOT EXISTS `cwconcue` (
  `Cuenta` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Nivel` int(11) NOT NULL DEFAULT '0',
  `Tipo` char(10) COLLATE utf8_spanish_ci NOT NULL,
  `Descrip` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Bancos` int(11) NOT NULL DEFAULT '0',
  `MonPre` float NOT NULL DEFAULT '0',
  `MonModif` float NOT NULL DEFAULT '0',
  `FechaNuevo` date NOT NULL DEFAULT '0000-00-00',
  `CtaNueva` int(11) NOT NULL DEFAULT '0',
  `Auxunico` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `Monetaria` int(11) NOT NULL DEFAULT '0',
  `Ctaajuste` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Marca` int(11) NOT NULL DEFAULT '0',
  `MonPreu` float NOT NULL DEFAULT '0',
  `MonModify` float NOT NULL DEFAULT '0',
  `Ccostos` int(11) NOT NULL DEFAULT '0',
  `Terceros` int(11) NOT NULL DEFAULT '0',
  `Cuentalt` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Descripalt` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Fiscaltipo` int(11) NOT NULL DEFAULT '0',
  `Tipocosto` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Maestro en Cuentas Contables';

--
-- Volcar la base de datos para la tabla `cwconcue`
--

INSERT INTO `cwconcue` (`Cuenta`, `Nivel`, `Tipo`, `Descrip`, `Bancos`, `MonPre`, `MonModif`, `FechaNuevo`, `CtaNueva`, `Auxunico`, `Monetaria`, `Ctaajuste`, `Marca`, `MonPreu`, `MonModify`, `Ccostos`, `Terceros`, `Cuentalt`, `Descripalt`, `Fiscaltipo`, `Tipocosto`) VALUES
('1.', 1, 'T', 'ACTIVO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.', 2, 'T', 'ACTIVO CIRCULANTE', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.', 3, 'T', 'CAJA Y BANCOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.01.', 4, 'T', 'CAJA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.01.01.', 5, 'P', 'CAJA PRINCIPAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.01.02.', 5, 'P', 'CAJA CHICA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.', 4, 'T', 'BANCOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.', 5, 'T', 'NACIONALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.01.', 6, 'P', 'BANCO CORP BANCA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.02.', 6, 'P', 'BANCO INDUSTRIAL DE VENEZUELA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.03.', 6, 'P', 'BANCO OCCIDENTAL DE DESCUENTO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.04.', 6, 'P', 'BANCO BANESCO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.05.', 6, 'P', 'BANCO DE VENEZUELA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.07.', 6, 'P', 'BANCO MERCANTIL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.08.', 6, 'P', 'BANCO EXTERIOR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.09.', 6, 'P', 'BANCO FEDERAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.10.', 6, 'P', 'BANCO BANFOANDES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.01.11.', 6, 'P', 'BANCO PROVINCIAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.01.02.02.', 5, 'P', 'MONEDA EXTRANJERA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.03.', 3, 'T', 'CUENTAS POR COBRAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.03.01.', 4, 'P', 'CLIENTES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.03.02.', 4, 'P', 'EMPLEADOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.03.03.', 4, 'T', 'ACCIONISTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.03.03.01.', 5, 'P', 'IRIS SEGOVIA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.03.03.02.', 5, 'P', 'ANALIS RODRIGUEZ', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.03.05.', 4, 'P', 'OTRAS CUENTAS A COBRAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.03.06.', 4, 'P', 'CHEQUES DEVUELTOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.03.07.', 4, 'P', 'ANTICIPOS A PROVEEDORES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.04.', 3, 'T', 'INVENTARIOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.04.01.', 4, 'P', 'MERCANCIAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.', 3, 'T', 'GASTOS PAGADOS POR ANTICIPADO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.01.', 4, 'T', 'IMPUESTOS PREPAGADOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.01.01.', 5, 'P', 'RETENCIONES ISLR CORRIENTES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.01.02.', 5, 'P', 'ESTIMADA DE ISLR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.01.04.', 5, 'P', 'ISLR ANTERIOR NO COMPENSADO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.01.05.', 5, 'P', 'RETENCION IVA CLIENTES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.01.06.', 5, 'P', 'CREDITOS FISCALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.02.', 4, 'T', 'IMPTOS.MUNICIPALES PREPAGADOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.02.01.', 5, 'P', 'RETENCIONES ALCALDIA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.02.02.', 5, 'P', 'ESTIMADA ALCALDIA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.1.05.03.', 4, 'P', 'SEGUROS PREPAGADOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.', 2, 'T', 'ACTIVO FIJO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.01.', 3, 'T', 'PROPIEDAD MOBILIARIO Y EQUIPO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.01.01.', 4, 'P', 'TERRENOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.01.02.', 4, 'P', 'INMUEBLES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.01.03.', 4, 'P', 'INSTALACIONES Y MEJORAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.01.04.', 4, 'P', 'MOBILIARIO Y EQUIPOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.01.05.', 4, 'P', 'VEHICULOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.02.', 3, 'T', 'DEPREC. ACUMULADA PROPIEDAD MOBILIARIO Y EQUIPO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.02.01.', 4, 'P', 'DEPREC. ACUMULADA TERRENOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.02.02.', 4, 'P', 'DEPREC. ACUMULADA INMUEBLES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.02.03.', 4, 'P', 'DEPREC. ACUMULADA INSTAL. Y MEJORAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.02.04.', 4, 'P', 'DEPREC. ACUMULADA MOBILIARIO Y EQUIPOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.3.02.05.', 4, 'P', 'DEPREC. ACUMULADA VEHICULOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.4.', 2, 'T', 'CARGOS DIFERIDOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.4.01.', 3, 'P', 'ADELANTO DE DIVIDENDOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.5.', 2, 'T', 'OTROS ACTIVOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('1.5.01.', 3, 'P', 'DEPOSITO EN GARANTIA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.', 1, 'T', 'PASIVO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.', 2, 'T', 'PASIVO A CORTO PLAZO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.01.', 3, 'T', 'PRESTAMOS BANCARIOS POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.01.01.', 4, 'P', 'PRESTAMO B.O.D.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.01.02.', 4, 'P', 'PRESTAMO BANCO EXTERIOR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.01.03.', 4, 'P', 'PRESTAMO B.VZLA (4080)', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.01.04.', 4, 'P', 'PRESTAMO BANFOANDES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.', 3, 'T', 'CUENTAS POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.01.', 4, 'T', 'PROVEEDORES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.01.01.', 5, 'T', 'PROVEEDORES DE MOBILIARIO Y EQUIPOS DE OFICINA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.01.01.01.', 6, 'P', 'MEGA COMPUTACION C.A.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.01.01.02.', 6, 'P', 'CENCOMUN DE VENEZUELA C.A.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.02.', 4, 'P', 'ACCIONISTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.03.', 4, 'T', 'SERVICIOS BASICOS POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.03.01.', 5, 'T', 'TELECOMUNICACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.03.01.01.', 6, 'P', 'CANTV', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.03.01.02.', 6, 'P', 'INTER', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.03.02.', 5, 'T', 'ENERGIA ELECTRICA POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.03.02.01.', 6, 'P', 'CADAFE', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.02.04.', 4, 'T', 'OTRAS CUENTAS A PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.03.', 3, 'T', 'IMPUESTOS A PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.03.01.', 4, 'T', 'I.V.A', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.03.01.01.', 5, 'P', 'IVA POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.03.01.02.', 5, 'P', 'RETENCIONES DE IVA PROVEEDORES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.03.02.', 4, 'T', 'I.S.L.R.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.03.02.01.', 5, 'P', 'DEFINITIVA DE ISLR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.03.02.02.', 5, 'P', 'ESTIMADA DE ISLR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.', 3, 'T', 'GASTOS ACUMULADOS A PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.01.', 4, 'T', 'RETENCIONES AL PERSONAL POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.01.01.', 5, 'P', 'I.S.L.R.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.01.02.', 5, 'P', 'CAJA DE AHORRO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.01.03.', 5, 'P', 'I.V.S.S.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.01.04.', 5, 'P', 'F.A.O.V.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.02.', 4, 'T', 'CONTRIBUCIONES POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.02.01.', 5, 'P', 'I.V.S.S.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.02.02.', 5, 'P', 'INCE', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.02.03.', 5, 'P', 'F.A.O.V.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.03.', 4, 'T', 'GASTOS VARIOS POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.03.01.', 5, 'P', 'COMISIONES POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.03.02.', 5, 'P', 'GASTOS VARIOS POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.03.03.', 5, 'T', 'DIVIDENDOS POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.03.03.01.', 6, 'P', 'IRIS SEGOVIA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.03.03.02.', 6, 'P', 'ANALIS RODRIGUEZ', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.03.04.', 5, 'P', 'ADELANTOS POR DISTRIBUIR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.04.03.05.', 5, 'P', 'UTILIDADES POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.05.', 3, 'T', 'ANTICIPOS RECIBIDOS POR PAGAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.05.01.', 4, 'T', 'CLIENTES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.1.05.01.01.', 5, 'P', 'INVERSIONES COIMPRO C.A.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.2.', 2, 'T', 'ACUMULACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.2.01.', 3, 'T', 'PRESTACIONES SOCIALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.2.01.01.', 4, 'P', 'ACUMULACION PRESTACIONES SOCIALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.2.01.02.', 4, 'P', 'ADELANTOS DE PRESTACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.2.02.', 3, 'P', 'INTERESES S/PRESTACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.4.', 2, 'T', 'CREDITOS DIFERIDOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('2.4.01.', 3, 'P', 'IVA DIFERIDO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.', 1, 'T', 'PATRIMONIO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.1.', 2, 'T', 'CAPITAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.1.01.', 3, 'P', 'CAPITAL SUSCRITO PAGADO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.1.02.', 3, 'P', 'CAPITAL SUSCRITO NO PAGADO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.1.03.', 3, 'P', 'APORTACIONES PARA FUTUROS AUMENTOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.2.', 2, 'T', 'CAPITAL GANADO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.2.01.', 3, 'T', 'UTILIDADES RETENIDAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.2.01.01.', 4, 'P', 'RESERVA LEGAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.2.01.02.', 4, 'T', 'UTILIDAD NO DISTRIBUIDA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.2.01.02.01.', 5, 'P', 'UTILIDAD AÃ‘O 2010', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('3.2.01.03.', 4, 'P', 'RESULTADO DEL EJERCICIO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.', 1, 'T', 'INGRESOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.1.', 2, 'T', 'INGRESOS POR VENTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.1.01.', 3, 'P', 'VENTAS PRINCIPAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.1.02.', 3, 'T', 'COMPLEMENTOS DE VENTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.1.02.01.', 4, 'P', 'FLETES SOBRE VENTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.1.02.02.', 4, 'P', 'DESCUENTO EN VENTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.1.02.03.', 4, 'P', 'DEVOLUCIONES EN VENTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.2.', 2, 'T', 'OTROS INGRESOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.2.01.', 3, 'T', 'INGRESOS FINANCIEROS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.2.01.01.', 4, 'P', 'INGRESOS POR INTERESES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.2.01.02.', 4, 'P', 'INTERESES SOBRE PRESTAMOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('4.2.02.', 3, 'P', 'OTROS INGRESOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.', 1, 'T', 'EGRESOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.', 2, 'T', 'COSTOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.01.', 3, 'T', 'MERCANCIAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.01.01.', 4, 'P', 'COMPRAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.02.', 3, 'T', 'COMPLEMENTO DE COMPRAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.02.01.', 4, 'P', 'FLETES SOBRE COMPRAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.02.02.', 4, 'P', 'DEVOLUCIONES S/COMPRAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.02.03.', 4, 'P', 'DESCUENTOS S/COMPRAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.02.04.', 4, 'P', 'GASTOS DE IMPORTACION', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.03.', 3, 'T', 'VARIACIONES DE INVENTARIOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.03.01.', 4, 'P', 'INVENTARIO INICIAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.03.02.', 4, 'P', 'INVENTARIO FINAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.1.03.03.', 4, 'P', 'DESCARGOS/CARGOS INVENTARIO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.', 2, 'T', 'GASTOS DE OPERACION', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.', 3, 'T', 'GASTOS DE VENTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.', 4, 'T', 'GASTOS DE PERSONAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.01.', 5, 'P', 'SUELDOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.02.', 5, 'P', 'COMISIONES VENDEDORES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.03.', 5, 'P', 'ARRENDAMIENTO VEHICULOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.04.', 5, 'P', 'BONIFICACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.05.', 5, 'P', 'VACACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.06.', 5, 'P', 'PRESTACIONES SOCIALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.07.', 5, 'P', 'UTILIDADES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.08.', 5, 'P', 'INTERESES S/PRESTACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.09.', 5, 'P', 'ATENCION EMPLEADOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.10.', 5, 'P', 'ENTRENAMIENTO DE PERSONAL-VENTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.11.', 5, 'P', 'HORAS EXTRAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.12.', 5, 'P', 'PRIMA DE VEHICULO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.01.13.', 5, 'P', 'TRABAJOS EVENTUALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.', 4, 'T', 'OTROS GASTOS DE VENTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.01.', 5, 'P', 'FLETES S/VENTAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.02.', 5, 'T', 'GASTOS DE TRANSPORTE', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.02.01.', 6, 'P', 'MANTENIMIENTO Y REPARAC. VEHICULOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.02.02.', 6, 'P', 'VIATICOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.02.03.', 6, 'P', 'COMBUSTIBLES Y LUBRICANTES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.02.04.', 6, 'P', 'PRIMAS DE SEGUROS-VEHICULOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.02.05.', 6, 'P', 'DEPRECIACION VEHICULOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.03.', 5, 'T', 'GASTOS DE VIAJES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.03.01.', 6, 'P', 'HOSPEDAJES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.03.02.', 6, 'P', 'VIATICOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.03.03.', 6, 'P', 'PASAJES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.04.', 5, 'P', 'PUBLICIDAD Y MERCADEO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.05.', 5, 'P', 'RELACIONES PUBLICAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.06.', 5, 'P', 'DONACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.07.', 5, 'P', 'OTRAS COMISIONES (TERCEROS)', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.01.02.09.', 5, 'P', 'GASTOS DE DECORACION', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.', 3, 'T', 'GASTOS GENERALES Y ADMON.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.', 4, 'T', 'PERSONAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.01.', 5, 'P', 'SUELDOS DIRECTORES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.02.', 5, 'P', 'SUELDOS EMPLEADOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.03.', 5, 'P', 'VACACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.04.', 5, 'P', 'BONIFICACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.05.', 5, 'P', 'UTILIDADES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.06.', 5, 'P', 'PRESTACIONES SOCIALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.07.', 5, 'P', 'INTERESES S/PRESTACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.08.', 5, 'P', 'BONO DE ALIMENTACION', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.09.', 5, 'P', 'TRABAJOS EVENTUALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.10.', 5, 'P', 'PRIMA POR VEHICULO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.11.', 5, 'P', 'PRIMA POR TIEMPO DE VIAJE', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.12.', 5, 'P', 'GASTOS MEDICOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.13.', 5, 'P', 'UNIFORMES EMPLEADOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.14.', 5, 'P', 'ADIESTRAMIENTO PERSONAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.15.', 5, 'P', 'HORAS EXTRAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.01.16.', 5, 'P', 'GASTOS DE REPRESENTACION', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.02.', 4, 'T', 'COSTOS FINANCIEROS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.02.01.', 5, 'T', 'INTERESES PAGADOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.02.01.01.', 6, 'P', 'BANCO MERCANTIL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.02.02.', 5, 'P', 'COMISIONES BANCARIAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.02.03.', 5, 'P', 'COMISIONES TARJETA DE CREDITO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.02.04.', 5, 'P', 'INTERESES S/PRESTAMOS PERSONALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.03.', 4, 'T', 'CONTRIBUCIONES OFICIALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.03.01.', 5, 'P', 'I.V.S.S.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.03.02.', 5, 'P', 'AHORRO HABITACIONAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.03.03.', 5, 'P', 'INCE', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.03.04.', 5, 'P', 'IMPUESTOS MUNICIPALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.03.05.', 5, 'P', 'DEBITO BANCARIO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.03.06.', 5, 'P', 'PRORRATEO DE I.V.A.', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.04.', 4, 'T', 'CONSERVACION Y MANTENIMIENTOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.04.01.', 5, 'P', 'MANTENIMIENTO LOCAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.04.02.', 5, 'P', 'MANTENIMIENTO MOB.Y EQUIPOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.04.03.', 5, 'P', 'MANTENIMIENTO VEHICULOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.04.04.', 5, 'P', 'MANTENIMIENTO EQUIPO DE INCENDIO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.04.05.', 5, 'P', 'ASEO Y LIMPIEZA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.', 4, 'T', 'SERVICIOS GENERALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.01.', 5, 'P', 'ENERGIA ELECTRICA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.02.', 5, 'T', 'TELEFONO Y COMUNICACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.02.01.', 6, 'P', 'CANTV', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.02.02.', 6, 'P', 'CELULAR', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.02.03.', 6, 'P', 'TELECOMUNICACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.03.', 5, 'P', 'HIDROLAGO', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.04.', 5, 'P', 'VIGILANCIA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.05.', 5, 'T', 'HONORARIOS PROFESIONALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.05.01.', 6, 'P', 'HONORARIOS CONTADORES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.05.02.', 6, 'P', 'HONORARIOS ABOGADOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.05.03.', 6, 'P', 'HONORARIOS AUDITORES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.05.04.', 6, 'P', 'HONORARIOS INFORMATICA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.05.05.', 6, 'P', 'HONORARIOS ASESORES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.05.06.', 5, 'P', 'SERVICIO DE MENSAJERIA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.06.', 4, 'T', 'DEPRECIACION Y AMORTIZACION', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.06.01.', 5, 'P', 'GASTOS DE DEPREC. TERRENOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.06.02.', 5, 'P', 'GASTOS DE DEPREC. INMUEBLES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.06.03.', 5, 'P', 'GASTOS DE DEPREC. INSTAL. Y MEJORAS ', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.06.04.', 5, 'P', 'GASTOS DE DEPREC. MOBILIARIO Y EQUIPOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.06.05.', 5, 'P', 'GASTOS DE DEPREC. VEHICULOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.', 4, 'T', 'GASTOS GENERALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.01.', 5, 'T', 'GASTOS DE OFICINA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.01.01.', 6, 'P', 'ARTICULOS  Y MATERIALES DE OFICINA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.01.02.', 6, 'P', 'COMIDAS Y REFRIGERIOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.01.03.', 6, 'P', 'MISCELANEOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.01.04.', 6, 'P', 'ESTAMPLILLAS FISCALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.01.05.', 6, 'P', 'GASTOS POR CAJA CHICA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.02.', 5, 'T', 'ALQUILERES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.02.01.', 6, 'P', 'ALQUILER DE INMUEBLE', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.02.02.', 6, 'P', 'ALQUILER DE VEHICULOS Y MAQUINARIA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.03.', 5, 'T', 'GASTOS DE VIAJES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.03.01.', 6, 'P', 'PASAJES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.03.02.', 6, 'P', 'COMIDA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.03.03.', 6, 'P', 'TAXIS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.03.04.', 6, 'P', 'HOTELES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.04.', 5, 'P', 'PRIMAS DE SEGUROS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.05.', 5, 'P', 'RELACIONES PUBLICAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.06.', 5, 'P', 'DONACIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.07.', 5, 'P', 'GASTOS LEGALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.08.', 5, 'P', 'GASTOS NO DEDUCIBLES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.09.', 5, 'P', 'PERDIDA POR INCOBRABLES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.15.', 5, 'P', 'OTROS EGRESOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.16.', 5, 'P', 'GASTOS DE DECORACION', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.17.', 5, 'P', 'AMBIENTE MUSICAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.2.02.07.18.', 5, 'P', 'SUSCRIPCIONES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.3.', 2, 'T', 'IMPUESTOS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.3.01.', 3, 'P', 'IMPUESTO SOBRE LA RENTA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.3.02.', 3, 'P', 'ACTIVOS EMPRESARIALES', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.4.', 2, 'T', 'RESERVAS', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('5.4.01.', 3, 'P', 'RESERVA LEGAL', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('6.', 1, 'T', 'CUENTAS DE ORDEN', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('6.1.', 2, 'T', 'CUENTAS DE ORDEN', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('6.1.01.', 3, 'P', 'BANCO MONEDA EXTRANJERA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('6.2.', 2, 'T', 'CUENTA DE ORDEN PER CONTRA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0),
('6.2.01.', 3, 'P', 'BANCO MONEDA EXTRANJERA PER CONTRA', 0, 0, 0, '0000-00-00', 0, '', 0, '', 0, 0, 0, 0, 0, '', '', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconcuetemp`
--

CREATE TABLE IF NOT EXISTS `cwconcuetemp` (
  `Cuenta` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Nivel` int(11) NOT NULL DEFAULT '0',
  `Tipo` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `Descrip` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Bancos` int(11) NOT NULL DEFAULT '0',
  `MonPre` float NOT NULL DEFAULT '0',
  `MonModif` float NOT NULL DEFAULT '0',
  `FechaNuevo` date NOT NULL DEFAULT '0000-00-00',
  `CtaNueva` int(11) NOT NULL DEFAULT '0',
  `Auxunico` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `Monetaria` int(11) NOT NULL DEFAULT '0',
  `Ctaajuste` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Marca` int(11) NOT NULL DEFAULT '0',
  `MonPreu` float NOT NULL DEFAULT '0',
  `MonModify` float NOT NULL DEFAULT '0',
  `Ccostos` int(11) NOT NULL DEFAULT '0',
  `Terceros` int(11) NOT NULL DEFAULT '0',
  `Cuentalt` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Descripalt` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Fiscaltipo` int(11) NOT NULL DEFAULT '0',
  `Tipocosto` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Maestro en Cuentas Contables';

--
-- Volcar la base de datos para la tabla `cwconcuetemp`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwcondco`
--

CREATE TABLE IF NOT EXISTS `cwcondco` (
  `RecNo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Numcom` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Clave foranea',
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `Cuenta` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Referen` varchar(11) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `Tiporef` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Descrip` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Debito` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Credito` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Descriplar` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Debitou` float NOT NULL DEFAULT '0',
  `Creditou` float NOT NULL DEFAULT '0',
  `Codccos` int(11) NOT NULL DEFAULT '0',
  `Numlim` double NOT NULL DEFAULT '0',
  `Control` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Codtipo` int(11) NOT NULL DEFAULT '0',
  `Concil` int(11) NOT NULL DEFAULT '0',
  `FechaD` date NOT NULL,
  PRIMARY KEY (`RecNo`),
  KEY `Numcom` (`Numcom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Detalle de Comprobante' AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cwcondco`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwcondcohis`
--

CREATE TABLE IF NOT EXISTS `cwcondcohis` (
  `RecNo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Numcom` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Clave foranea',
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `Cuenta` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Referen` int(11) NOT NULL DEFAULT '0',
  `Tiporef` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Descrip` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Debito` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Credito` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Descriplar` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Debitou` float NOT NULL DEFAULT '0',
  `Creditou` float NOT NULL DEFAULT '0',
  `Codccos` int(11) NOT NULL DEFAULT '0',
  `Numlim` double NOT NULL DEFAULT '0',
  `Control` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Codtipo` int(11) NOT NULL DEFAULT '0',
  `Concil` int(11) NOT NULL DEFAULT '0',
  `FechaD` date NOT NULL,
  PRIMARY KEY (`RecNo`),
  KEY `Numcom` (`Numcom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Detalle de Comprobante' AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cwcondcohis`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwcondes`
--

CREATE TABLE IF NOT EXISTS `cwcondes` (
  `Codtipo` int(11) NOT NULL AUTO_INCREMENT,
  `Descrip` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Codtipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `cwcondes`
--

INSERT INTO `cwcondes` (`Codtipo`, `Descrip`) VALUES
(1, 'ASIENTOS DE AJUSTES');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwcondli`
--

CREATE TABLE IF NOT EXISTS `cwcondli` (
  `RecNo` int(11) NOT NULL AUTO_INCREMENT,
  `Descrip` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`RecNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cwcondli`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconemp`
--

CREATE TABLE IF NOT EXISTS `cwconemp` (
  `Codemp` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nomemp` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Nrifemp` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Nnitemp` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Direcc1` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Direcc2` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Fecini` date NOT NULL DEFAULT '0000-00-00',
  `Fecfin` date NOT NULL DEFAULT '0000-00-00',
  `Numcom` int(11) NOT NULL DEFAULT '0',
  `Mescie1` date NOT NULL DEFAULT '0000-00-00',
  `Mescie2` date NOT NULL DEFAULT '0000-00-00',
  `Mescie3` date NOT NULL DEFAULT '0000-00-00',
  `Mescie4` date NOT NULL DEFAULT '0000-00-00',
  `Mescie5` date NOT NULL DEFAULT '0000-00-00',
  `Mescie6` date NOT NULL DEFAULT '0000-00-00',
  `Mescie7` date NOT NULL DEFAULT '0000-00-00',
  `Mescie8` date NOT NULL DEFAULT '0000-00-00',
  `Mescie9` date NOT NULL DEFAULT '0000-00-00',
  `Mescie10` date NOT NULL DEFAULT '0000-00-00',
  `Mescie11` date NOT NULL DEFAULT '0000-00-00',
  `Mescie12` date NOT NULL DEFAULT '0000-00-00',
  `Estcie1` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie2` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie3` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie4` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie5` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie6` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie7` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie8` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie9` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie10` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie11` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estcie12` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Aprupre` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Codsuc` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Precierre` int(11) NOT NULL DEFAULT '0',
  `Ajusuni` int(11) NOT NULL DEFAULT '0',
  `Ctaajuni` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Numaf` int(11) NOT NULL DEFAULT '0',
  `Comene` int(11) NOT NULL DEFAULT '0',
  `Comfeb` int(11) NOT NULL DEFAULT '0',
  `Commar` int(11) NOT NULL DEFAULT '0',
  `Comabr` int(11) NOT NULL DEFAULT '0',
  `Commay` int(11) NOT NULL DEFAULT '0',
  `Comjun` int(11) NOT NULL DEFAULT '0',
  `Comjul` int(11) NOT NULL DEFAULT '0',
  `Comago` int(11) NOT NULL DEFAULT '0',
  `Comsep` int(11) NOT NULL DEFAULT '0',
  `Comoct` int(11) NOT NULL DEFAULT '0',
  `Comnov` int(11) NOT NULL DEFAULT '0',
  `Comdic` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Codemp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Empresa' AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `cwconemp`
--

INSERT INTO `cwconemp` (`Codemp`, `Nomemp`, `Nrifemp`, `Nnitemp`, `Direcc1`, `Direcc2`, `Fecini`, `Fecfin`, `Numcom`, `Mescie1`, `Mescie2`, `Mescie3`, `Mescie4`, `Mescie5`, `Mescie6`, `Mescie7`, `Mescie8`, `Mescie9`, `Mescie10`, `Mescie11`, `Mescie12`, `Estcie1`, `Estcie2`, `Estcie3`, `Estcie4`, `Estcie5`, `Estcie6`, `Estcie7`, `Estcie8`, `Estcie9`, `Estcie10`, `Estcie11`, `Estcie12`, `Aprupre`, `Codsuc`, `Precierre`, `Ajusuni`, `Ctaajuni`, `Numaf`, `Comene`, `Comfeb`, `Commar`, `Comabr`, `Commay`, `Comjun`, `Comjul`, `Comago`, `Comsep`, `Comoct`, `Comnov`, `Comdic`) VALUES
(1, 'VENEZOLANA DE TELEFERICOS VENTEL C.A', 'G-20008550-9', '', 'AV CESAR AUGUSTO SANDINO CON AV. BOYACA EDIF. 2 PISO PB OF S/N URB MARIPERES CARACAS -DTTO CAPITAL', '', '2008-01-01', '2008-12-31', 0, '2008-01-01', '2008-02-01', '2008-03-01', '2008-04-01', '2008-05-01', '2008-06-01', '2008-07-01', '2008-08-01', '2008-09-01', '2008-10-01', '2008-11-01', '2008-12-01', 'ABIERTO', 'ABIERTO', 'ABIERTO', 'ABIERTO', 'ABIERTO', 'ABIERTO', 'ABIERTO', 'ABIERTO', 'ABIERTO', 'ABIERTO', 'ABIERTO', 'ABIERTO', '', '', 0, 0, '', 0, 1, 1, 1, 1, 1, 9, 23, 1, 1, 1, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconfig`
--

CREATE TABLE IF NOT EXISTS `cwconfig` (
  `Codigo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Sistema` int(11) NOT NULL DEFAULT '0',
  `Sepacta` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `Balact` int(11) NOT NULL DEFAULT '0',
  `Balpas` int(11) NOT NULL DEFAULT '0',
  `Balgan` int(11) NOT NULL DEFAULT '0',
  `Baling` int(11) NOT NULL DEFAULT '0',
  `Baleng` int(11) NOT NULL DEFAULT '0',
  `Balord` int(11) NOT NULL DEFAULT '0',
  `Nromax` int(11) NOT NULL DEFAULT '0',
  `Niv1` int(11) NOT NULL DEFAULT '0',
  `Niv2` int(11) NOT NULL DEFAULT '0',
  `Niv3` int(11) NOT NULL DEFAULT '0',
  `Niv4` int(11) NOT NULL DEFAULT '0',
  `Niv5` int(11) NOT NULL DEFAULT '0',
  `Niv6` int(11) NOT NULL DEFAULT '0',
  `Niv7` int(11) NOT NULL DEFAULT '0',
  `Niv8` int(11) NOT NULL DEFAULT '0',
  `Niv9` int(11) NOT NULL DEFAULT '0',
  `Nomniv1` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Nomniv2` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Nomniv3` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Nomniv4` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Nomniv5` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Nomniv6` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Nomniv7` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Nomniv8` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Nomniv9` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Confis` int(11) NOT NULL DEFAULT '0',
  `Nivacca` int(11) NOT NULL DEFAULT '0',
  `Nivaccp` int(11) NOT NULL DEFAULT '0',
  `Nivaccc` int(11) NOT NULL DEFAULT '0',
  `Nivacci` int(11) NOT NULL DEFAULT '0',
  `Siscxc` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Siscpx` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Planunico` int(11) NOT NULL DEFAULT '0',
  `Ctalargo` int(11) NOT NULL DEFAULT '0',
  `Novlargo` int(11) NOT NULL DEFAULT '0',
  `Unipre` int(11) NOT NULL DEFAULT '0',
  `Nromaximo` int(11) NOT NULL DEFAULT '0',
  `Nroauto` int(11) NOT NULL DEFAULT '0',
  `Ccosto` int(11) NOT NULL DEFAULT '0',
  `Nroautoaf` int(11) NOT NULL DEFAULT '0',
  `Nromaxaf` int(11) NOT NULL DEFAULT '0',
  `Niv1af` int(11) NOT NULL DEFAULT '0',
  `Niv2af` int(11) NOT NULL DEFAULT '0',
  `Niv3af` int(11) NOT NULL DEFAULT '0',
  `Niv4af` int(11) NOT NULL DEFAULT '0',
  `Niv5af` int(11) NOT NULL DEFAULT '0',
  `Niv6af` int(11) NOT NULL DEFAULT '0',
  `Niv7af` int(11) NOT NULL DEFAULT '0',
  `Niv8af` int(11) NOT NULL DEFAULT '0',
  `Niv9af` int(11) NOT NULL DEFAULT '0',
  `Sepactaaf` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `Tipoplandecta` int(11) NOT NULL DEFAULT '0',
  `cuenta_cierre_mes` varchar(27) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Configuracion' AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `cwconfig`
--

INSERT INTO `cwconfig` (`Codigo`, `Sistema`, `Sepacta`, `Balact`, `Balpas`, `Balgan`, `Baling`, `Baleng`, `Balord`, `Nromax`, `Niv1`, `Niv2`, `Niv3`, `Niv4`, `Niv5`, `Niv6`, `Niv7`, `Niv8`, `Niv9`, `Nomniv1`, `Nomniv2`, `Nomniv3`, `Nomniv4`, `Nomniv5`, `Nomniv6`, `Nomniv7`, `Nomniv8`, `Nomniv9`, `Confis`, `Nivacca`, `Nivaccp`, `Nivaccc`, `Nivacci`, `Siscxc`, `Siscpx`, `Planunico`, `Ctalargo`, `Novlargo`, `Unipre`, `Nromaximo`, `Nroauto`, `Ccosto`, `Nroautoaf`, `Nromaxaf`, `Niv1af`, `Niv2af`, `Niv3af`, `Niv4af`, `Niv5af`, `Niv6af`, `Niv7af`, `Niv8af`, `Niv9af`, `Sepactaaf`, `Tipoplandecta`, `cuenta_cierre_mes`) VALUES
(1, 1, '.', 1, 2, 3, 5, 6, 4, 6, 1, 1, 2, 2, 2, 2, 0, 0, 0, 'GRUPO', 'SUB-GRUPO', 'RUBRO', 'CUENTA', 'SUB-CUENTA 1', 'AUXILIAR', '', '', '', 0, 0, 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 0, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, '.', 0, '1.1.00.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconhco`
--

CREATE TABLE IF NOT EXISTS `cwconhco` (
  `Numcom` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codtipo` int(10) unsigned NOT NULL DEFAULT '0',
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `Descrip` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` int(10) unsigned NOT NULL DEFAULT '0',
  `Alterno` int(10) unsigned NOT NULL DEFAULT '0',
  `Enuso` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Numcom`,`Fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Cabecera de Comprobante' AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cwconhco`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconhcohis`
--

CREATE TABLE IF NOT EXISTS `cwconhcohis` (
  `Numcom` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codtipo` int(10) unsigned NOT NULL DEFAULT '0',
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `Descrip` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` int(10) unsigned NOT NULL DEFAULT '0',
  `Alterno` int(10) unsigned NOT NULL DEFAULT '0',
  `Enuso` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Numcom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Cabecera de Comprobante' AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cwconhcohis`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconhis`
--

CREATE TABLE IF NOT EXISTS `cwconhis` (
  `RecNo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Anio` int(11) NOT NULL DEFAULT '0',
  `Mes` int(11) NOT NULL DEFAULT '0',
  `Cuenta` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Clave foranea',
  `Desmes` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Salanth` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Debitoh` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Salamesh` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Salacth` decimal(25,2) NOT NULL DEFAULT '0.00',
  `MonMod` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Salantu` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Debitou` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Creditou` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Salamesu` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Salactu` decimal(25,2) NOT NULL DEFAULT '0.00',
  `Mondu` decimal(25,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`RecNo`),
  KEY `Cuenta` (`Cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Historia de la cuenta' AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cwconhis`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwcontas`
--

CREATE TABLE IF NOT EXISTS `cwcontas` (
  `cod_tipo` int(10) NOT NULL,
  `Descrip` varchar(500) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcar la base de datos para la tabla `cwcontas`
--

INSERT INTO `cwcontas` (`cod_tipo`, `Descrip`) VALUES
(1, 'Asientos de Ingresos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwcontco`
--

CREATE TABLE IF NOT EXISTS `cwcontco` (
  `Codtipo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Descrip` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Codtipo`),
  UNIQUE KEY `Codtipo` (`Codtipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tipo de comprobante' AUTO_INCREMENT=14 ;

--
-- Volcar la base de datos para la tabla `cwcontco`
--

INSERT INTO `cwcontco` (`Codtipo`, `Descrip`) VALUES
(1, 'COMPROBANTE DE EGRESOS'),
(2, 'COMPROBANTE DE AJUSTES'),
(3, 'COMPROBANTE DE COSTOS'),
(4, 'COMPROBANTE DE INGRESOS'),
(7, 'COMPROBANTE DE NOMINA'),
(8, 'COMPROBANTE DE APERTURA'),
(9, 'COMPROBANTE DE CIERRES'),
(10, 'COMPROBANTE DE DEPRECIACIONES Y AMORTIZACIONES'),
(11, 'COMPROBANTE DE CUENTAS POR PAGAR'),
(12, 'INGRESOS POR CUENTAS POR COBRAR'),
(13, 'COMPROBANTE DE CUENTAS POR COBRAR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconter`
--

CREATE TABLE IF NOT EXISTS `cwconter` (
  `Codtipo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Descrip` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `Rif` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `Nit` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`Codtipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cwconter`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwconusu`
--

CREATE TABLE IF NOT EXISTS `cwconusu` (
  `RecNo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codusu` varchar(46) COLLATE utf8_spanish_ci NOT NULL,
  `Nomusu` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Es el nombre',
  `Admin` int(11) NOT NULL DEFAULT '0',
  `Claveusu` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `Nivelusu` int(11) NOT NULL DEFAULT '0',
  `Cractas` int(11) NOT NULL DEFAULT '0',
  `Contabiliza` int(11) NOT NULL DEFAULT '0',
  `Anula` int(11) NOT NULL DEFAULT '0',
  `Repctas` int(11) NOT NULL DEFAULT '0',
  `Repcomp` int(11) NOT NULL DEFAULT '0',
  `Repanali` int(11) NOT NULL DEFAULT '0',
  `Repbalcom` int(11) NOT NULL DEFAULT '0',
  `Repbalgen` int(11) NOT NULL DEFAULT '0',
  `Repganper` int(11) NOT NULL DEFAULT '0',
  `Repotros` int(11) NOT NULL DEFAULT '0',
  `Presupuesto` int(11) NOT NULL DEFAULT '0',
  `Conciliacion` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`RecNo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Usuarios' AUTO_INCREMENT=37 ;

--
-- Volcar la base de datos para la tabla `cwconusu`
--

INSERT INTO `cwconusu` (`RecNo`, `Codusu`, `Nomusu`, `Admin`, `Claveusu`, `Nivelusu`, `Cractas`, `Contabiliza`, `Anula`, `Repctas`, `Repcomp`, `Repanali`, `Repbalcom`, `Repbalgen`, `Repganper`, `Repotros`, `Presupuesto`, `Conciliacion`) VALUES
(36, 'ONUVA', 'ONUVA COLOMBIA', 1, '2a97516c354b68848cdbd8f54a226a0a55b21ed138e207ad6c5cbb9c00aa5aea', 1, 1, 1, 1, 0, 0, 1, 1, 0, 1, 1, 1, 1);

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `activosfijos`
--
ALTER TABLE `activosfijos`
  ADD CONSTRAINT `activosfijos_ibfk_1` FOREIGN KEY (`TIPO`) REFERENCES `activosfijos_tipos` (`CODIGOTA`);

--
-- Filtros para la tabla `activosfijos_movimientos`
--
ALTER TABLE `activosfijos_movimientos`
  ADD CONSTRAINT `activosfijos_movimientos_ibfk_1` FOREIGN KEY (`CODACT`) REFERENCES `activosfijos` (`CODACT`) ON UPDATE CASCADE;
