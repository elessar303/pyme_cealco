-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-01-2018 a las 21:15:34
-- Versión del servidor: 5.7.19
-- Versión de PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pyme_cealco`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actas_inventario`
--

DROP TABLE IF EXISTS `actas_inventario`;
CREATE TABLE IF NOT EXISTS `actas_inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `fecha_acta` date NOT NULL,
  `establecimiento` varchar(255) NOT NULL,
  `almacen` varchar(255) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actas_inventario_detalle`
--

DROP TABLE IF EXISTS `actas_inventario_detalle`;
CREATE TABLE IF NOT EXISTS `actas_inventario_detalle` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_acta` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `procedencia` int(11) NOT NULL,
  PRIMARY KEY (`id_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

DROP TABLE IF EXISTS `almacen`;
CREATE TABLE IF NOT EXISTS `almacen` (
  `cod_almacen` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  `id_localidad` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`cod_almacen`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `almacen`
--

INSERT INTO `almacen` (`cod_almacen`, `descripcion`, `id_localidad`) VALUES
(1, 'CARACAS', 1),
(2, 'COMEDOR', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacenes_siga`
--

DROP TABLE IF EXISTS `almacenes_siga`;
CREATE TABLE IF NOT EXISTS `almacenes_siga` (
  `id_almacen_siga` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_almacen_siga` varchar(20) NOT NULL,
  `descripcion` varchar(120) NOT NULL,
  PRIMARY KEY (`id_almacen_siga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacenes ubicados en SIGA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `analisis_cotizaciones`
--

DROP TABLE IF EXISTS `analisis_cotizaciones`;
CREATE TABLE IF NOT EXISTS `analisis_cotizaciones` (
  `cod_analisis` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_cotizacion` int(11) NOT NULL,
  `usuario` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `observaciones` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_analisis` date NOT NULL,
  `actividad` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `modalidad_contratacion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_analisis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apertura_tienda`
--

DROP TABLE IF EXISTS `apertura_tienda`;
CREATE TABLE IF NOT EXISTS `apertura_tienda` (
  `id_apertura` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `apertura` datetime NOT NULL COMMENT 'Fecha de Apertura de Operaciones',
  `apertura_date` date NOT NULL,
  `cierre` datetime NOT NULL COMMENT 'Fecha de Cierre de Operaciones',
  `cierre_date` date NOT NULL,
  `id_user_apertura` varchar(255) NOT NULL COMMENT 'Usuario que realiza la Apertura',
  `id_user_cierre` varchar(255) NOT NULL COMMENT 'Usuario que realiza el Cierre',
  `fecha` datetime NOT NULL COMMENT 'Fecha de Finalizacion del Proceso',
  PRIMARY KEY (`id_apertura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena la informacion del proceso de Apertura y Cierre de Tienda';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arqueo_cajero`
--

DROP TABLE IF EXISTS `arqueo_cajero`;
CREATE TABLE IF NOT EXISTS `arqueo_cajero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cajero` varchar(255) NOT NULL,
  `monedas` decimal(10,2) NOT NULL,
  `efectivo` decimal(10,2) NOT NULL,
  `tarjeta` decimal(10,2) NOT NULL,
  `credito` decimal(10,2) NOT NULL,
  `deposito` decimal(11,2) NOT NULL COMMENT 'total deposito por cajero',
  `cheque` decimal(11,2) NOT NULL DEFAULT '0.00',
  `tickets` decimal(10,2) NOT NULL,
  `total_sistema` decimal(10,2) NOT NULL,
  `total_efectivo_sistema` decimal(10,2) NOT NULL,
  `total_tj_sistema` decimal(10,2) NOT NULL,
  `total_credito_sistema` decimal(10,2) NOT NULL,
  `total_deposito_sistema` decimal(11,2) NOT NULL,
  `total_cheque_sistema` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total_tickets_sistema` decimal(10,2) NOT NULL,
  `total_devolucion` decimal(10,2) NOT NULL,
  `iva1` decimal(11,2) NOT NULL DEFAULT '0.00',
  `iva2` decimal(11,2) NOT NULL DEFAULT '0.00',
  `iva3` decimal(11,2) NOT NULL DEFAULT '0.00',
  `fecha_arqueo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_venta_ini` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_venta_fin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `observaciones` text NOT NULL,
  `cod_usuario` int(32) NOT NULL COMMENT 'usuario que realiza el proceso',
  `id_deposito` varchar(50) NOT NULL DEFAULT '-1',
  `id_deposito2` varchar(50) NOT NULL DEFAULT '-1',
  `id_apertura` int(11) NOT NULL,
  `tipo_venta` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pyme=0 pos=1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arqueo_cheques`
--

DROP TABLE IF EXISTS `arqueo_cheques`;
CREATE TABLE IF NOT EXISTS `arqueo_cheques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_arqueo` int(11) NOT NULL,
  `monto_cheques` decimal(11,2) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arqueo_depositos`
--

DROP TABLE IF EXISTS `arqueo_depositos`;
CREATE TABLE IF NOT EXISTS `arqueo_depositos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_arqueo` int(11) NOT NULL,
  `monto_deposito` decimal(11,2) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

DROP TABLE IF EXISTS `banco`;
CREATE TABLE IF NOT EXISTS `banco` (
  `cod_banco` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(80) NOT NULL,
  PRIMARY KEY (`cod_banco`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `banco`
--

INSERT INTO `banco` (`cod_banco`, `descripcion`) VALUES
(001, 'BANCO DE VENEZUELA1'),
(002, 'BANESCO'),
(003, 'B.O.D.'),
(004, 'BANCO MERCANTIL'),
(005, 'BANCO BICENTENARIO'),
(006, 'BANCO DEL TESORO'),
(007, 'BANCO PROVINCIAL'),
(008, 'BANCO INDUSTRIAL DE VENEZUELA'),
(009, 'BANCO CARONI'),
(010, 'BANCARIBE'),
(011, 'BANCO EXTERIOR'),
(012, 'BANPLUS'),
(013, 'BANCO NACIONAL DE CREDITO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billetes`
--

DROP TABLE IF EXISTS `billetes`;
CREATE TABLE IF NOT EXISTS `billetes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `denominacion` varchar(200) NOT NULL,
  `valor` double NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `usuario_creacion` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `billetes`
--

INSERT INTO `billetes` (`id`, `denominacion`, `valor`, `estatus`, `usuario_creacion`, `created_at`, `update_at`) VALUES
(1, 'Bs.', 2, 1, 'asys', '2017-01-18 22:43:37', '2017-01-21 22:47:52'),
(2, 'Bs.', 5, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:33'),
(3, 'Bs.', 10, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:30'),
(4, 'Bs.', 20, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:28'),
(5, 'Bs.', 50, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:25'),
(6, 'Bs.', 100, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:23'),
(7, 'Bs.', 500, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:20'),
(8, 'Bs.', 1000, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:18'),
(9, 'Bs.', 2000, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:15'),
(10, 'Bs.', 5000, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:12'),
(11, 'Bs.', 10000, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:00'),
(12, 'Bs.', 20000, 1, 'asys', '2017-01-18 22:43:37', '2017-01-18 22:47:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billetes_arqueo`
--

DROP TABLE IF EXISTS `billetes_arqueo`;
CREATE TABLE IF NOT EXISTS `billetes_arqueo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_arqueo` int(11) NOT NULL,
  `id_billete` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_arqueo` (`id_arqueo`),
  KEY `id_billete` (`id_billete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billetes_retiro_efectivo`
--

DROP TABLE IF EXISTS `billetes_retiro_efectivo`;
CREATE TABLE IF NOT EXISTS `billetes_retiro_efectivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_retiro_efectivo` int(11) NOT NULL,
  `id_billete` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
CREATE TABLE IF NOT EXISTS `bitacora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` varchar(255) CHARACTER SET latin1 NOT NULL,
  `query` text CHARACTER SET latin1 NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `observacion` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleto_codebar`
--

DROP TABLE IF EXISTS `boleto_codebar`;
CREATE TABLE IF NOT EXISTS `boleto_codebar` (
  `cod_boleto_codebar_` int(32) NOT NULL AUTO_INCREMENT,
  `id_factura_boleto` int(32) UNSIGNED NOT NULL,
  `boleto_descripcion` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_cliente` int(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(80) NOT NULL,
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `codebar` varchar(20) NOT NULL DEFAULT '000',
  PRIMARY KEY (`cod_boleto_codebar_`),
  KEY `id_factura_boleto` (`id_factura_boleto`),
  KEY `id_cliente` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleto_factura`
--

DROP TABLE IF EXISTS `boleto_factura`;
CREATE TABLE IF NOT EXISTS `boleto_factura` (
  `id_factura_boleto` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_factura_boleto` varchar(32) NOT NULL DEFAULT 'S/I',
  `id_cliente` int(32) NOT NULL,
  `cod_vendedor` int(32) NOT NULL,
  `fechaFactura` date NOT NULL DEFAULT '0000-00-00',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuentosItemFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montoItemsFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ivaTotalFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalTotalFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_items` int(32) NOT NULL DEFAULT '0',
  `totalizar_sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_parcial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_operacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pdescuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_base_imponible` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_general` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cod_estatus` int(32) UNSIGNED DEFAULT NULL,
  `fecha_pago` date NOT NULL DEFAULT '0000-00-00',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(40) NOT NULL,
  PRIMARY KEY (`id_factura_boleto`),
  KEY `fk_cod_vendedor2` (`cod_vendedor`),
  KEY `fk_usuario` (`usuario_creacion`),
  KEY `fk_id_cliente` (`id_cliente`),
  KEY `fk_cod_estatus` (`cod_estatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleto_factura_detalle`
--

DROP TABLE IF EXISTS `boleto_factura_detalle`;
CREATE TABLE IF NOT EXISTS `boleto_factura_detalle` (
  `id_detalle_factura_boleto` int(32) NOT NULL AUTO_INCREMENT,
  `id_factura_boleto` int(32) UNSIGNED DEFAULT NULL,
  `id_item` int(32) UNSIGNED DEFAULT NULL,
  `_item_almacen` int(32) DEFAULT NULL,
  `_item_descripcion` varchar(32) NOT NULL,
  `_item_cantidad` decimal(32,0) NOT NULL DEFAULT '0',
  `_item_preciosiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_montodescuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_piva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalsiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalconiva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detalle_factura_boleto`),
  KEY `fk_id_factura` (`id_factura_boleto`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleto_factura_detalle_formapago`
--

DROP TABLE IF EXISTS `boleto_factura_detalle_formapago`;
CREATE TABLE IF NOT EXISTS `boleto_factura_detalle_formapago` (
  `cod_factura_detalle_formapago_boleto` int(32) NOT NULL AUTO_INCREMENT,
  `id_factura_boleto` int(32) UNSIGNED NOT NULL DEFAULT '0',
  `totalizar_monto_cancelar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_saldo_pendiente` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_efectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `opt_cheque` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nombre_banco` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_tarjeta` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_tipo_tarjeta` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_formapago',
  `opt_deposito` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_banco_deposito` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `fecha_vencimiento` date NOT NULL,
  `observacion` varchar(600) NOT NULL,
  `persona_contacto` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `opt_otrodocumento` tinyint(1) NOT NULL,
  `totalizar_tipo_otrodocumento` int(32) NOT NULL COMMENT 'Tipo de documento',
  `totalizar_monto_otrodocumento` decimal(10,2) NOT NULL,
  `totalizar_nro_otrodocumento` int(32) NOT NULL,
  `totalizar_banco_otrodocumento` int(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(60) NOT NULL,
  PRIMARY KEY (`cod_factura_detalle_formapago_boleto`),
  KEY `id_factura_boleto` (`id_factura_boleto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bolsas_cataporte`
--

DROP TABLE IF EXISTS `bolsas_cataporte`;
CREATE TABLE IF NOT EXISTS `bolsas_cataporte` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `nro_cataporte` varchar(100) NOT NULL COMMENT 'Nro Cataporte',
  `nro_bolsa` varchar(100) NOT NULL COMMENT 'nro de Bolsa',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_impresora`
--

DROP TABLE IF EXISTS `caja_impresora`;
CREATE TABLE IF NOT EXISTS `caja_impresora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial_impresora` varchar(60) NOT NULL,
  `caja_host` varchar(45) NOT NULL,
  `caja_tipo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pyme=0, pos=1',
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `serial_impresora` (`serial_impresora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_principal`
--

DROP TABLE IF EXISTS `caja_principal`;
CREATE TABLE IF NOT EXISTS `caja_principal` (
  `id_deposito` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `nro_deposito` varchar(100) NOT NULL COMMENT 'Nro del Deposito',
  `monto` decimal(11,2) NOT NULL COMMENT 'Monto Total del Deposito',
  `id_cataporte` varchar(50) NOT NULL COMMENT 'Nro de Cataporte donde se ubican los depositos',
  `fecha_deposito` datetime NOT NULL COMMENT 'Fecha del Deposito',
  `cod_banco` varchar(60) NOT NULL COMMENT 'Codigo del Banco',
  `usuario_creacion` varchar(255) NOT NULL COMMENT 'Usuario que Crea el Deposito',
  `nro_deposito_usuario` varchar(25) DEFAULT NULL,
  `monto_usuario` decimal(11,2) DEFAULT NULL,
  `usuario_correccion` varchar(255) DEFAULT NULL,
  `fecha_correccion` datetime DEFAULT NULL,
  `observacion` varchar(255) NOT NULL,
  `monto_acumulado` decimal(11,2) NOT NULL,
  `monto_tarjeta` decimal(11,2) NOT NULL,
  `monto_acumulado_tarjeta` decimal(11,2) NOT NULL,
  `monto_deposito` decimal(11,2) NOT NULL,
  `monto_acumulado_deposito` decimal(11,2) NOT NULL,
  `monto_tickets` decimal(11,2) NOT NULL,
  `monto_acumulado_tickets` decimal(11,2) NOT NULL,
  `monto_cheque` decimal(11,2) NOT NULL,
  `monto_acumulado_cheque` decimal(11,2) NOT NULL,
  `monto_credito` decimal(11,2) NOT NULL,
  `monto_acumulado_credito` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id_deposito`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_principal_cheques`
--

DROP TABLE IF EXISTS `caja_principal_cheques`;
CREATE TABLE IF NOT EXISTS `caja_principal_cheques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caja_principal` varchar(100) NOT NULL,
  `monto` decimal(11,2) NOT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=no fue retirado, 1= retirado',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_principal_depositos`
--

DROP TABLE IF EXISTS `caja_principal_depositos`;
CREATE TABLE IF NOT EXISTS `caja_principal_depositos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_caja_principal` varchar(100) NOT NULL,
  `monto` decimal(11,2) NOT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=no fue retirado, 1= retirado',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calidad_almacen`
--

DROP TABLE IF EXISTS `calidad_almacen`;
CREATE TABLE IF NOT EXISTS `calidad_almacen` (
  `id_transaccion` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_movimiento_almacen` int(11) NOT NULL,
  `autorizado_por` varchar(100) NOT NULL,
  `observacion` varchar(200) NOT NULL,
  `fecha` date NOT NULL,
  `usuario_creacion` varchar(100) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` varchar(20) NOT NULL,
  `fecha_ejecucion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_documento` varchar(100) NOT NULL COMMENT 'id Factura y/o Compra',
  `id_proveedor` int(11) NOT NULL COMMENT 'Proveedor Asociado a la entrada',
  `empresa_transporte` varchar(50) NOT NULL,
  `id_conductor` int(11) NOT NULL,
  `placa` varchar(15) NOT NULL,
  `guia_sunagro` varchar(20) NOT NULL,
  `orden_despacho` varchar(50) NOT NULL COMMENT 'Orden de Despacho Vehicular opcional para mas adelante',
  `almacen_procedencia` varchar(66) NOT NULL COMMENT 'Procedencia de los rubros opcional para mas adelante',
  `almacen_destino` varchar(6) NOT NULL COMMENT 'Destino de los rubros opcional para mas adelante',
  `tipo_acta` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=entrada, 2=retiro calidad',
  `cod_acta_calidad` varchar(60) DEFAULT NULL,
  `prescintos` varchar(255) DEFAULT NULL,
  `id_seguridad` int(11) NOT NULL,
  `id_aprobado` int(11) NOT NULL,
  `id_receptor` int(11) NOT NULL,
  `nro_contenedor` varchar(50) NOT NULL,
  `id_ticket_entrada` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_transaccion`),
  KEY `fk_id_tipo_movimiento_almacen` (`tipo_movimiento_almacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calidad_almacen_detalle`
--

DROP TABLE IF EXISTS `calidad_almacen_detalle`;
CREATE TABLE IF NOT EXISTS `calidad_almacen_detalle` (
  `id_transaccion_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaccion` int(11) NOT NULL,
  `id_almacen_entrada` int(11) NOT NULL,
  `id_almacen_salida` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `cantidad` decimal(11,2) NOT NULL,
  `id_ubi_entrada` varchar(60) NOT NULL,
  `id_ubi_salida` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `vencimiento` date DEFAULT NULL,
  `elaboracion` date DEFAULT NULL,
  `lote` int(10) UNSIGNED DEFAULT NULL,
  `c_esperada` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `observacion` varchar(55) NOT NULL DEFAULT '',
  `precio` decimal(13,2) NOT NULL,
  `estatus` tinyint(1) NOT NULL COMMENT 'Aprobado por calidad',
  `tipo_uso` int(11) NOT NULL COMMENT 'relacion_tipo_usos',
  PRIMARY KEY (`id_transaccion_detalle`),
  KEY `fk_id_transaccion` (`id_transaccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calidad_visitas`
--

DROP TABLE IF EXISTS `calidad_visitas`;
CREATE TABLE IF NOT EXISTS `calidad_visitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_acta_visita` varchar(20) NOT NULL,
  `tipo_visita` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `cedula_persona_visita` varchar(20) NOT NULL,
  `persona_visita` varchar(100) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `almacen_visita` int(11) NOT NULL,
  `ubicacion_visita` int(11) NOT NULL,
  `fecha_visita` datetime NOT NULL,
  `observacion` text NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cataporte`
--

DROP TABLE IF EXISTS `cataporte`;
CREATE TABLE IF NOT EXISTS `cataporte` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `nro_cataporte` varchar(100) NOT NULL COMMENT 'Nro del Cataporte',
  `tipo_cataporte` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=fectivo, 1=ticket',
  `cant_bolsas` int(11) NOT NULL COMMENT 'cantidad de bolsas',
  `monto` double NOT NULL,
  `fecha` datetime NOT NULL COMMENT 'fecha de creacion',
  `cod_usuario` int(11) NOT NULL COMMENT 'usuario que realizo el cataporte',
  `retirado` datetime DEFAULT NULL,
  `nro_cataporte_usuario` varchar(25) NOT NULL,
  `monto_usuario` double NOT NULL,
  `usuario_correccion` varchar(50) NOT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `cuenta` varchar(60) CHARACTER SET utf16 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_conversor`
--

DROP TABLE IF EXISTS `categoria_conversor`;
CREATE TABLE IF NOT EXISTS `categoria_conversor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria_pos` varchar(80) NOT NULL,
  `cod_categoria` char(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categoria_conversor`
--

INSERT INTO `categoria_conversor` (`id`, `id_categoria_pos`, `cod_categoria`) VALUES
(1, '7a180edd-6692-3d49-0b4b-6d72f281a949', '001'),
(2, '5f02e577-13b1-1196-dca9-2a0acb2ccea5', '002'),
(3, 'e8f4bc04-d388-d524-c9d1-188f3469f051', '003'),
(4, '034407b6-0c67-82cb-8a00-31ab4fb6d576', '004'),
(5, 'fe42e474-f7b8-3d6f-4810-8213ceb4c8ea', '005'),
(6, '41655fd3-c469-cc1b-e871-22bd1134290a', '007'),
(7, 'a98d145d-0915-bff2-574f-131d3cf82943', '008'),
(8, '19fd0237-a8f3-e732-308a-543037a10e4d', '009'),
(9, '211e34f5-e0ef-5761-fc06-fc449026ac5b', '010'),
(10, '6cb0334a-78d2-0acb-3697-4d93529513c8', '011'),
(11, '9201184c-5c2f-f605-07f7-0f60330d8649', '012'),
(12, '19fd0237-a8f3-e732-308a-543037a10e4d', '013'),
(13, '81c8cc87-9351-0781-bf75-3c9fe95a361a', '014'),
(14, 'c1d3d61b-01c1-25a7-ca0e-e424ac0c4c35', '015'),
(15, 'ce522ce9-35dd-ab7b-7eb8-75fba1fedb18', '016'),
(16, 'dc285c66-b48d-30ca-3bfd-e4a22dd36b0a', '017'),
(17, 'f63bb547-c039-4188-3857-1c495dcb1446', '019'),
(18, '98606c6b-73d2-9a45-c4ea-7202eb344c75', '020');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centros`
--

DROP TABLE IF EXISTS `centros`;
CREATE TABLE IF NOT EXISTS `centros` (
  `cod_centro` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `cod_unidad` int(32) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `sel_sector` varchar(3) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sel_programa` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sel_actividad` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`cod_centro`,`cod_unidad`),
  KEY `cod_unidad` (`cod_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cesta_clap`
--

DROP TABLE IF EXISTS `cesta_clap`;
CREATE TABLE IF NOT EXISTS `cesta_clap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `codigo_siga` varchar(10) NOT NULL,
  `usuario_creacion` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cesta_clap_detalle`
--

DROP TABLE IF EXISTS `cesta_clap_detalle`;
CREATE TABLE IF NOT EXISTS `cesta_clap_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cesta` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_cesta` (`id_cesta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque`
--

DROP TABLE IF EXISTS `cheque`;
CREATE TABLE IF NOT EXISTS `cheque` (
  `cod_cheque` int(32) NOT NULL AUTO_INCREMENT,
  `nro_cheque` varchar(50) NOT NULL,
  `cheque` int(32) NOT NULL,
  `cod_chequera` int(32) NOT NULL,
  `ref` varchar(500) NOT NULL DEFAULT '0' COMMENT 'Numero de Orden de CxP',
  `id_proveedor` int(32) DEFAULT NULL,
  `situacion` varchar(3) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `concepto` varchar(200) DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `fecha_anulacion` date NOT NULL DEFAULT '0000-00-00',
  `observacion_anulado` varchar(200) NOT NULL,
  `fecha_danado` date NOT NULL DEFAULT '0000-00-00',
  `observacion_danado` varchar(200) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(70) NOT NULL,
  `cod_correlativo_iva` bigint(32) NOT NULL,
  `cod_correlativo_islr` bigint(32) NOT NULL,
  PRIMARY KEY (`cod_cheque`),
  KEY `id_proveedor` (`id_proveedor`),
  KEY `cod_chequera` (`cod_chequera`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chequera`
--

DROP TABLE IF EXISTS `chequera`;
CREATE TABLE IF NOT EXISTS `chequera` (
  `cod_chequera` int(32) NOT NULL AUTO_INCREMENT,
  `cantidad` int(10) NOT NULL,
  `inicio` int(40) NOT NULL,
  `situacion` char(1) NOT NULL,
  `cod_tesor_bandodet` int(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(80) NOT NULL,
  PRIMARY KEY (`cod_chequera`),
  KEY `cod_tesor_bandodet` (`cod_tesor_bandodet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque_bache_det`
--

DROP TABLE IF EXISTS `cheque_bache_det`;
CREATE TABLE IF NOT EXISTS `cheque_bache_det` (
  `cod_cheque_bauchedet` int(32) NOT NULL AUTO_INCREMENT,
  `cod_cheque` int(32) NOT NULL,
  `monto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tipo` char(1) DEFAULT NULL COMMENT 'tipo: d (debito), c (credito)',
  `cuenta_contable` varchar(32) DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `descripcion` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(90) NOT NULL,
  PRIMARY KEY (`cod_cheque_bauchedet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque_tipo_situacion`
--

DROP TABLE IF EXISTS `cheque_tipo_situacion`;
CREATE TABLE IF NOT EXISTS `cheque_tipo_situacion` (
  `cod_tipo_situacion` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_tipo_situacion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cheque_tipo_situacion`
--

INSERT INTO `cheque_tipo_situacion` (`cod_tipo_situacion`, `descripcion`) VALUES
(1, 'Activa'),
(2, 'Anulada'),
(3, 'Dañada'),
(4, 'Deposito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_caja_control`
--

DROP TABLE IF EXISTS `cierre_caja_control`;
CREATE TABLE IF NOT EXISTS `cierre_caja_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cajas` varchar(60) NOT NULL,
  `secuencia` int(11) NOT NULL,
  `estatus_cierre` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `secuencia` (`secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_caja_control_pyme`
--

DROP TABLE IF EXISTS `cierre_caja_control_pyme`;
CREATE TABLE IF NOT EXISTS `cierre_caja_control_pyme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial_cajas` varchar(60) NOT NULL,
  `nombre_cajas` varchar(60) NOT NULL,
  `secuencia` int(11) NOT NULL,
  `estatus_cierre` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `secuencia` (`secuencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_pos`
--

DROP TABLE IF EXISTS `cierre_pos`;
CREATE TABLE IF NOT EXISTS `cierre_pos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siga` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `banco` int(3) UNSIGNED ZEROFILL NOT NULL,
  `cuenta` int(11) NOT NULL,
  `afiliacion_credito` varchar(45) NOT NULL,
  `terminal_credito` varchar(45) NOT NULL,
  `lote_credito` varchar(45) NOT NULL,
  `visa` decimal(10,2) NOT NULL,
  `master` decimal(10,2) NOT NULL,
  `total_credito` decimal(10,2) NOT NULL,
  `afiliacion_debito` varchar(45) NOT NULL,
  `terminal_debito` varchar(45) NOT NULL,
  `lote_debito` varchar(45) NOT NULL,
  `debito` decimal(10,2) NOT NULL,
  `alimentacion` decimal(10,2) NOT NULL,
  `total_debito` decimal(10,2) NOT NULL,
  `total_deposito` decimal(11,2) NOT NULL,
  `monto_acumulado_sistema` decimal(11,2) NOT NULL,
  `usuario` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_pos_xenviar`
--

DROP TABLE IF EXISTS `cierre_pos_xenviar`;
CREATE TABLE IF NOT EXISTS `cierre_pos_xenviar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_original` int(11) NOT NULL,
  `siga` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `banco` int(3) UNSIGNED ZEROFILL NOT NULL,
  `cuenta` int(11) NOT NULL,
  `afiliacion_credito` varchar(45) NOT NULL,
  `terminal_credito` varchar(45) NOT NULL,
  `lote_credito` varchar(45) NOT NULL,
  `visa` decimal(10,2) NOT NULL,
  `master` decimal(10,2) NOT NULL,
  `total_credito` decimal(10,2) NOT NULL,
  `afiliacion_debito` varchar(45) NOT NULL,
  `terminal_debito` varchar(45) NOT NULL,
  `lote_debito` varchar(45) NOT NULL,
  `debito` decimal(10,2) NOT NULL,
  `alimentacion` decimal(10,2) NOT NULL,
  `total_debito` decimal(10,2) NOT NULL,
  `total_deposito` decimal(11,2) NOT NULL,
  `monto_acumulado_sistema` decimal(11,2) NOT NULL,
  `usuario` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(32) NOT NULL AUTO_INCREMENT,
  `cod_cliente` varchar(80) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `fnacimiento` date NOT NULL,
  `representante` varchar(80) NOT NULL DEFAULT '',
  `direccion` varchar(200) NOT NULL,
  `altena` varchar(200) NOT NULL,
  `alterna2` varchar(200) NOT NULL,
  `telefonos` varchar(50) NOT NULL,
  `fax` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `permitecredito` tinyint(1) NOT NULL,
  `limite` double(10,2) NOT NULL,
  `dias` int(11) NOT NULL,
  `tolerancia` int(32) NOT NULL,
  `porc_parcial` decimal(10,2) NOT NULL,
  `porc_descuento_global` decimal(10,2) NOT NULL,
  `calc_reten_impuesto_islr` tinyint(1) NOT NULL,
  `calc_reten_impuesto_iva` tinyint(1) NOT NULL,
  `cod_vendedor` int(32) NOT NULL,
  `cod_zona` int(32) NOT NULL,
  `rif` varchar(50) NOT NULL,
  `nit` varchar(50) NOT NULL,
  `contribuyente_especial` tinyint(1) NOT NULL,
  `retenido_por_cliente` decimal(10,2) NOT NULL,
  `cod_tipo_cliente` int(32) NOT NULL,
  `cod_entidad` int(11) NOT NULL,
  `cod_tipo_precio` int(32) NOT NULL,
  `clase` varchar(50) NOT NULL,
  `calc_reten_impuesto_1x1000` tinyint(1) NOT NULL,
  `estado` varchar(1) NOT NULL,
  `cuenta_contable` varchar(25) NOT NULL,
  `cod_mediq` int(11) NOT NULL,
  `foto` varchar(60) NOT NULL,
  `id_distrito` int(10) UNSIGNED DEFAULT NULL,
  `parroquia` varchar(60) NOT NULL,
  `subsistema` int(11) NOT NULL,
  `dependencia` int(11) NOT NULL,
  `modalidad_ingesta` int(11) NOT NULL,
  `turno` int(11) NOT NULL,
  `matricula` varchar(60) NOT NULL,
  `director_plantel` varchar(60) NOT NULL,
  `tipo_cliente` int(10) NOT NULL COMMENT '0 =pdvalH 1=PAE 2=Cliente Credito',
  PRIMARY KEY (`id_cliente`),
  KEY `fk_zona` (`cod_zona`),
  KEY `fk_cod_tipo_precio` (`cod_tipo_precio`),
  KEY `fk_cod_tipo_cliente` (`cod_tipo_cliente`),
  KEY `fk_cod_vendedor` (`cod_vendedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_cargos`
--

DROP TABLE IF EXISTS `cliente_cargos`;
CREATE TABLE IF NOT EXISTS `cliente_cargos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_servicio_material` int(11) NOT NULL,
  `costo` decimal(11,2) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `observacion` text,
  `estatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0= no facturado, 1= facturado',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `closedcash_pyme`
--

DROP TABLE IF EXISTS `closedcash_pyme`;
CREATE TABLE IF NOT EXISTS `closedcash_pyme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_caja` varchar(100) NOT NULL,
  `serial_caja` varchar(100) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `secuencia` int(11) NOT NULL,
  `money` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigo_kardex`
--

DROP TABLE IF EXISTS `codigo_kardex`;
CREATE TABLE IF NOT EXISTS `codigo_kardex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) NOT NULL,
  `id_movimiento` int(11) NOT NULL,
  `tipo_moviento` int(11) NOT NULL,
  `usuario` varchar(150) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

DROP TABLE IF EXISTS `compra`;
CREATE TABLE IF NOT EXISTS `compra` (
  `id_compra` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_compra` varchar(32) NOT NULL DEFAULT 'S/I',
  `id_proveedor` int(32) NOT NULL,
  `cod_vendedor` int(32) NOT NULL,
  `fechacompra` date NOT NULL DEFAULT '0000-00-00',
  `montoItemscompra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ivaTotalcompra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalTotalcompra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `monto_excento` decimal(11,0) NOT NULL,
  `cantidad_items` int(32) NOT NULL DEFAULT '0',
  `cod_estatus` int(32) UNSIGNED DEFAULT NULL,
  `fecha_pago` date NOT NULL DEFAULT '0000-00-00',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(40) NOT NULL,
  `responsable` varchar(80) NOT NULL,
  `centrocosto` varchar(100) NOT NULL,
  `num_factura_compra` varchar(80) NOT NULL,
  `num_cont_factura` int(10) DEFAULT NULL,
  `cod_requi` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `cod_cotizacion` int(11) DEFAULT NULL,
  `codigo_ref` int(11) NOT NULL,
  `dias_credito` int(11) NOT NULL,
  `unidad` varchar(45) NOT NULL,
  `centro_costo` varchar(45) NOT NULL,
  `diasentrega` varchar(20) NOT NULL,
  `tipomoneda` varchar(20) NOT NULL,
  `tipo` int(11) NOT NULL,
  `formapago` varchar(20) NOT NULL,
  `concepto` text NOT NULL,
  `condicioncompra` varchar(20) NOT NULL,
  `montodivisa` varchar(20) NOT NULL,
  `tasacambio` varchar(20) NOT NULL,
  `obser` varchar(200) NOT NULL,
  `entrega` varchar(100) NOT NULL,
  `compra` varchar(100) NOT NULL,
  PRIMARY KEY (`id_compra`),
  KEY `fk_cod_vendedor2` (`cod_vendedor`),
  KEY `fk_usuario` (`usuario_creacion`),
  KEY `fk_cod_estatus` (`cod_estatus`),
  KEY `id_proveedor` (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_detalle`
--

DROP TABLE IF EXISTS `compra_detalle`;
CREATE TABLE IF NOT EXISTS `compra_detalle` (
  `id_detalle_compra` int(32) NOT NULL AUTO_INCREMENT,
  `id_compra` int(32) UNSIGNED DEFAULT NULL,
  `id_item` int(32) UNSIGNED DEFAULT NULL,
  `_item_almacen` int(32) DEFAULT NULL,
  `_item_cantidad` decimal(32,0) NOT NULL DEFAULT '0',
  `_item_preciosiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalsiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalconiva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `codfabricante` varchar(40) NOT NULL,
  `piva` varchar(10) NOT NULL,
  `_tiva` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detalle_compra`),
  KEY `fk_id_compra` (`id_compra`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_detalle_formapago`
--

DROP TABLE IF EXISTS `compra_detalle_formapago`;
CREATE TABLE IF NOT EXISTS `compra_detalle_formapago` (
  `cod_compra_detalle_formapago` int(32) NOT NULL AUTO_INCREMENT,
  `id_compra` int(32) UNSIGNED NOT NULL DEFAULT '0',
  `totalizar_monto_cancelar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_saldo_pendiente` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_efectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `opt_cheque` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nombre_banco` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_tarjeta` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_tipo_tarjeta` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_formapago',
  `opt_deposito` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_banco_deposito` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `fecha_vencimiento` date NOT NULL DEFAULT '0000-00-00',
  `observacion` varchar(600) NOT NULL,
  `persona_contacto` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `opt_otrodocumento` tinyint(1) NOT NULL,
  `totalizar_tipo_otrodocumento` int(32) NOT NULL DEFAULT '0' COMMENT 'Tipo de documento',
  `totalizar_monto_otrodocumento` decimal(10,2) NOT NULL,
  `totalizar_nro_otrodocumento` int(32) NOT NULL,
  `totalizar_banco_otrodocumento` int(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(60) NOT NULL,
  PRIMARY KEY (`cod_compra_detalle_formapago`),
  KEY `id_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_impuestos`
--

DROP TABLE IF EXISTS `compra_impuestos`;
CREATE TABLE IF NOT EXISTS `compra_impuestos` (
  `id_compra_impuestos` int(32) NOT NULL AUTO_INCREMENT,
  `id_compra` int(32) UNSIGNED NOT NULL,
  `totalizar_base_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pbase_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descripcion_base_retencion` int(32) NOT NULL DEFAULT '0' COMMENT 'fk_cod_impuesto_iva',
  `cod_impuesto_iva` int(32) NOT NULL,
  `totalizar_monto_iva2` decimal(10,2) NOT NULL,
  `totalizar_monto_1x1000` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(50) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_compra_impuestos`),
  KEY `id_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante`
--

DROP TABLE IF EXISTS `comprobante`;
CREATE TABLE IF NOT EXISTS `comprobante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ingreso` decimal(11,2) NOT NULL,
  `iva1` decimal(11,2) NOT NULL,
  `iva2` decimal(11,2) NOT NULL,
  `iva3` decimal(11,2) NOT NULL,
  `perdida` decimal(11,2) NOT NULL,
  `cxc` decimal(11,2) NOT NULL,
  `otros_ingresos` decimal(11,2) NOT NULL,
  `caja` decimal(11,2) NOT NULL,
  `banco` decimal(11,2) NOT NULL,
  `tipo_mov` varchar(60) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observacion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante_control`
--

DROP TABLE IF EXISTS `comprobante_control`;
CREATE TABLE IF NOT EXISTS `comprobante_control` (
  `id_comprobante` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `comprobante_control`
--

INSERT INTO `comprobante_control` (`id_comprobante`) VALUES
(0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante_detalle`
--

DROP TABLE IF EXISTS `comprobante_detalle`;
CREATE TABLE IF NOT EXISTS `comprobante_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_comprobante` int(11) NOT NULL,
  `ingreso` decimal(11,2) NOT NULL,
  `cajero` varchar(255) NOT NULL,
  `iva1` decimal(11,2) NOT NULL,
  `iva2` decimal(11,2) NOT NULL,
  `iva3` decimal(11,2) NOT NULL,
  `perdida` decimal(11,2) NOT NULL,
  `cxc` decimal(11,2) NOT NULL,
  `otros_ingresos` decimal(11,2) NOT NULL,
  `caja` decimal(11,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo_venta` tinyint(4) NOT NULL COMMENT '0=pyme, 1=pos',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conciliacion_bancaria`
--

DROP TABLE IF EXISTS `conciliacion_bancaria`;
CREATE TABLE IF NOT EXISTS `conciliacion_bancaria` (
  `cod_conciliacion` int(32) NOT NULL AUTO_INCREMENT,
  `fecha_inicial` date NOT NULL,
  `fecha_final` date NOT NULL,
  `saldo_inicial` decimal(10,2) NOT NULL,
  `saldo_final` decimal(10,2) NOT NULL,
  `saldo_libros` decimal(10,2) NOT NULL,
  `mon_xcon_depo` decimal(10,2) NOT NULL,
  `mon_xcon_cheque` decimal(10,2) NOT NULL,
  `mon_xcon_nc` decimal(10,2) NOT NULL,
  `mon_xcon_nd` decimal(10,2) NOT NULL,
  `cant_tran_cheque_` int(32) NOT NULL,
  `cant_tran_depo_` int(32) NOT NULL,
  `cant_tran_nc_` int(32) NOT NULL,
  `cant_tran_nd_` int(32) NOT NULL,
  `mon_tran_cheque_` decimal(10,2) NOT NULL,
  `mon_tran_depo_` decimal(10,2) NOT NULL,
  `mon_tran_nc_` decimal(10,2) NOT NULL,
  `mon_tran_nd_` decimal(10,2) NOT NULL,
  `cant_xcon_cheque` int(32) NOT NULL,
  `cant_xcon_depo` int(32) NOT NULL,
  `cant_xcon_nc` int(32) NOT NULL,
  `cant_xcon_nd` int(32) NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `cod_tesor_bancodet` bigint(32) NOT NULL,
  `usuario` varchar(32) NOT NULL,
  `fecha_realizado` date NOT NULL,
  `estado` varchar(32) NOT NULL,
  PRIMARY KEY (`cod_conciliacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductores`
--

DROP TABLE IF EXISTS `conductores`;
CREATE TABLE IF NOT EXISTS `conductores` (
  `id_conductor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_conductor` varchar(50) NOT NULL,
  `cedula_conductor` varchar(15) NOT NULL,
  PRIMARY KEY (`id_conductor`,`cedula_conductor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_cataporte_temp`
--

DROP TABLE IF EXISTS `control_cataporte_temp`;
CREATE TABLE IF NOT EXISTS `control_cataporte_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_deposito` varchar(50) NOT NULL,
  `id_sessionactual` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_cierre_cajero`
--

DROP TABLE IF EXISTS `control_cierre_cajero`;
CREATE TABLE IF NOT EXISTS `control_cierre_cajero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cajero` varchar(255) NOT NULL,
  `secuencia` int(11) NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_fin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tipo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pyme=0, pos =1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_deposito_temp`
--

DROP TABLE IF EXISTS `control_deposito_temp`;
CREATE TABLE IF NOT EXISTS `control_deposito_temp` (
  `id_control` int(11) NOT NULL AUTO_INCREMENT,
  `host` varchar(50) NOT NULL,
  `secuencia` int(11) NOT NULL,
  `monto` double NOT NULL,
  `id_sessionactual` varchar(255) NOT NULL COMMENT 'Sesion desde la cual se esta realizando el proceso',
  `id_control_temp` int(11) NOT NULL,
  KEY `id_control` (`id_control`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_fecha_archivo`
--

DROP TABLE IF EXISTS `control_fecha_archivo`;
CREATE TABLE IF NOT EXISTS `control_fecha_archivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` int(11) NOT NULL,
  `minutos` int(11) NOT NULL,
  `segundos` int(11) NOT NULL,
  `ticket_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `control_fecha_archivo`
--

INSERT INTO `control_fecha_archivo` (`id`, `fecha`, `hora`, `minutos`, `segundos`, `ticket_id`) VALUES
(1, '2015-07-01', 7, 0, 0, ''),
(2, '2015-11-30', 16, 34, 36, ''),
(24, '2016-01-28', 14, 12, 36, ''),
(26, '2016-02-01', 11, 13, 6, ''),
(29, '2016-02-04', 16, 55, 26, '162'),
(30, '2016-02-04', 16, 56, 16, '165'),
(31, '2016-02-04', 16, 57, 18, '166'),
(32, '2016-02-05', 14, 39, 47, '166'),
(33, '2016-02-17', 16, 49, 5, ''),
(34, '2016-03-08', 11, 15, 21, ''),
(35, '2016-03-14', 10, 43, 1, ''),
(36, '2016-03-17', 15, 43, 32, ''),
(37, '2016-04-06', 10, 23, 37, ''),
(56, '2016-04-15', 10, 9, 43, ''),
(58, '2016-04-18', 9, 47, 16, '252'),
(59, '2016-04-18', 13, 10, 27, '259'),
(60, '2016-04-18', 14, 50, 34, '260'),
(61, '2016-04-26', 8, 46, 39, '263'),
(62, '2016-04-26', 14, 48, 19, '264'),
(63, '2016-04-26', 15, 41, 48, '264'),
(64, '2016-05-05', 8, 59, 27, '266'),
(65, '2016-06-09', 15, 25, 12, '268'),
(66, '2016-06-14', 13, 28, 31, '269'),
(67, '2016-06-16', 16, 37, 17, '272'),
(68, '2016-07-12', 15, 39, 14, '274'),
(69, '2016-07-18', 10, 57, 35, '280'),
(70, '2016-08-02', 14, 34, 10, '281'),
(71, '2016-10-05', 12, 45, 59, '286'),
(72, '2017-02-07', 16, 14, 29, '288');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_fecha_archivo_pyme`
--

DROP TABLE IF EXISTS `control_fecha_archivo_pyme`;
CREATE TABLE IF NOT EXISTS `control_fecha_archivo_pyme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` int(11) NOT NULL,
  `minutos` int(11) NOT NULL,
  `segundos` int(11) NOT NULL,
  `ticket_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `control_fecha_archivo_pyme`
--

INSERT INTO `control_fecha_archivo_pyme` (`id`, `fecha`, `hora`, `minutos`, `segundos`, `ticket_id`) VALUES
(1, '0000-00-00', 0, 0, 0, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_ingresos`
--

DROP TABLE IF EXISTS `control_ingresos`;
CREATE TABLE IF NOT EXISTS `control_ingresos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de Cierre',
  `host` varchar(50) NOT NULL COMMENT 'Nombre de Caja',
  `id_cajero` varchar(255) NOT NULL COMMENT 'id Cajero o Person',
  `monto` double NOT NULL COMMENT 'monto del pago',
  `forma_pago` varchar(50) NOT NULL COMMENT 'forma de pago',
  `secuencia_cierre` int(11) NOT NULL COMMENT 'Secuencia de cierre',
  `id_usuario` int(11) NOT NULL COMMENT 'Id del usuario que realizo el cierre de caja',
  `id_deposito` varchar(50) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de los ingresos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_ingresos_temp`
--

DROP TABLE IF EXISTS `control_ingresos_temp`;
CREATE TABLE IF NOT EXISTS `control_ingresos_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de Cierre',
  `host` varchar(50) NOT NULL COMMENT 'Nombre de Caja',
  `id_cajero` varchar(255) NOT NULL COMMENT 'id Cajero o Person',
  `monto` double NOT NULL COMMENT 'monto del pago',
  `forma_pago` varchar(50) NOT NULL COMMENT 'forma de pago',
  `secuencia_cierre` int(11) NOT NULL COMMENT 'Secuencia de cierre',
  `id_sessionactual` varchar(255) NOT NULL COMMENT 'Sesion desde la cual se esta realizando el proceso',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla temporal para validar el control de los ingresos antes de registrarlos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_sincronizacion`
--

DROP TABLE IF EXISTS `control_sincronizacion`;
CREATE TABLE IF NOT EXISTS `control_sincronizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_cierre_debido` date NOT NULL,
  `fecha_cierre` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correlativos`
--

DROP TABLE IF EXISTS `correlativos`;
CREATE TABLE IF NOT EXISTS `correlativos` (
  `id_corre` int(11) NOT NULL AUTO_INCREMENT,
  `campo` varchar(32) NOT NULL,
  `formato` varchar(32) NOT NULL,
  `contador` int(32) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  PRIMARY KEY (`id_corre`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `correlativos`
--

INSERT INTO `correlativos` (`id_corre`, `campo`, `formato`, `contador`, `descripcion`) VALUES
(1, 'cod_factura', '00000', 292, 'Correlativo de Factura'),
(2, 'cod_boleto', '00000', 0, 'Correlativo de Boleto'),
(3, 'cod_producto', '00000', 12641, 'Correlativo de Producto'),
(4, 'cod_servicio', '00000', 0, 'Correlativo de Servicio'),
(5, 'cod_factura_boleto', '00000', 0, 'Correlativo de Factura de Boleto'),
(6, 'cod_pago_o_abono', '00000', 0, 'Correlativo de Pago o Abono'),
(7, 'cod_codebar', '0000000000000000', 0, 'Correlativo de Codigo de Barra (Boleto)'),
(8, 'cod_cliente', '00000', 21, 'Correlativo de Cliente'),
(9, 'cod_proveedor', '00000', 11, 'Codigo del Proveedor'),
(10, 'cod_compra', '00000', 0, 'Cod. de Compra'),
(11, 'cod_pago_o_abonoCXP', '00000', 0, 'Cod. de Compra Pago o Abono'),
(12, 'cod_correlativo_islr', '00000000', 2, 'Correlativo Impuesto Sobre la Renta'),
(13, 'cod_correlativo_iva', '00000000', 3, 'Correlativo I.V.A.'),
(14, 'cod_cotizacion', '00000', 1, 'Correlativo de Presupuesto/Cotizacion'),
(15, 'cod_devolucion', '00000000', 21, 'Correlativo de Devoluciones'),
(16, 'cod_nota_entrega', '000000', 3, 'cod_nota_entrega'),
(17, 'cod_pedido', '000000', 57, 'cod_pedido'),
(18, 'cod_despacho', '00000', 263, 'codigo de despacho'),
(19, 'arqueocajero_xenviar', '000000', 0, 'control de los arqueos de cajeros por enviar'),
(20, 'libroventa_xenviar', '000000', 0, 'control de los libros de ventas que se envian a csv'),
(21, 'data_generada', '0000', 1, 'Contador de Archivo _data'),
(22, 'id_ticket', '000000000', 45, 'Correlativo de id de ticket');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
--

DROP TABLE IF EXISTS `cotizaciones`;
CREATE TABLE IF NOT EXISTS `cotizaciones` (
  `codigo` int(11) NOT NULL,
  `cod_proveedor` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `fecha_recibida` date NOT NULL,
  `estatus` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tiempo_entrega` int(11) NOT NULL,
  `tiempo_garantia` int(11) NOT NULL,
  `porcentaje_descuento` decimal(10,0) NOT NULL,
  `total` decimal(17,2) NOT NULL,
  `tipo_pago` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `dias_pago` int(11) NOT NULL,
  `cod_requisicion` int(11) NOT NULL,
  `tipodescuento` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `observaciones` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones_detalles`
--

DROP TABLE IF EXISTS `cotizaciones_detalles`;
CREATE TABLE IF NOT EXISTS `cotizaciones_detalles` (
  `cod_cotizacion` int(11) NOT NULL,
  `cod_producto` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `cantidad` decimal(11,6) NOT NULL,
  `precio` decimal(17,6) NOT NULL,
  `descuento` decimal(17,2) NOT NULL,
  `iva` decimal(17,2) NOT NULL,
  `estatus` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `consecutivo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_detalle_formapago`
--

DROP TABLE IF EXISTS `cotizacion_detalle_formapago`;
CREATE TABLE IF NOT EXISTS `cotizacion_detalle_formapago` (
  `cod_cotizacion_detalle_formapago` int(32) NOT NULL AUTO_INCREMENT,
  `id_cotizacion` int(32) UNSIGNED NOT NULL DEFAULT '0',
  `totalizar_monto_cancelar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_saldo_pendiente` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_efectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `opt_cheque` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nombre_banco` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_tarjeta` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_tipo_tarjeta` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_formapago',
  `opt_deposito` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_banco_deposito` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `fecha_vencimiento` date NOT NULL,
  `observacion` varchar(600) NOT NULL,
  `persona_contacto` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `opt_otrodocumento` tinyint(1) NOT NULL,
  `totalizar_tipo_otrodocumento` int(32) NOT NULL COMMENT 'Tipo de documento',
  `totalizar_monto_otrodocumento` decimal(10,2) NOT NULL,
  `totalizar_nro_otrodocumento` int(32) NOT NULL,
  `totalizar_banco_otrodocumento` int(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(60) NOT NULL,
  PRIMARY KEY (`cod_cotizacion_detalle_formapago`),
  KEY `id_cotizacion` (`id_cotizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_impuestos`
--

DROP TABLE IF EXISTS `cotizacion_impuestos`;
CREATE TABLE IF NOT EXISTS `cotizacion_impuestos` (
  `id_cotizacion_impuestos` int(32) NOT NULL AUTO_INCREMENT,
  `id_cotizacion` int(32) UNSIGNED NOT NULL,
  `totalizar_base_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pbase_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descripcion_base_retencion` int(32) NOT NULL DEFAULT '0' COMMENT 'fk_cod_impuesto_iva',
  `cod_impuesto_iva` int(32) NOT NULL,
  `totalizar_monto_iva2` decimal(10,2) NOT NULL,
  `totalizar_monto_1x1000` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(50) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cotizacion_impuestos`),
  KEY `id_factura` (`id_cotizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_presupuesto`
--

DROP TABLE IF EXISTS `cotizacion_presupuesto`;
CREATE TABLE IF NOT EXISTS `cotizacion_presupuesto` (
  `id_cotizacion` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_cotizacion` varchar(32) NOT NULL DEFAULT 'S/I',
  `id_cliente` int(32) NOT NULL,
  `cod_vendedor` int(32) NOT NULL,
  `id_factura` int(32) UNSIGNED NOT NULL DEFAULT '0',
  `fecha_cotizacion` date NOT NULL DEFAULT '0000-00-00',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuentosItemCotizacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montoItemsCotizacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ivaTotalCotizacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalTotalCotizacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_items` int(32) NOT NULL DEFAULT '0',
  `totalizar_sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_parcial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_operacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pdescuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_base_imponible` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_general` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_retencion` decimal(10,2) NOT NULL,
  `cod_estatus` int(32) UNSIGNED DEFAULT NULL,
  `fecha_pago` date NOT NULL DEFAULT '0000-00-00',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(40) NOT NULL,
  PRIMARY KEY (`id_cotizacion`),
  KEY `fk_cod_vendedor2` (`cod_vendedor`),
  KEY `fk_usuario` (`usuario_creacion`),
  KEY `fk_id_cliente` (`id_cliente`),
  KEY `fk_cod_estatus` (`cod_estatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_presupuesto_detalle`
--

DROP TABLE IF EXISTS `cotizacion_presupuesto_detalle`;
CREATE TABLE IF NOT EXISTS `cotizacion_presupuesto_detalle` (
  `id_cotizacion_presupuesto_detalle` int(32) NOT NULL AUTO_INCREMENT,
  `id_cotizacion` int(32) UNSIGNED DEFAULT NULL,
  `id_item` int(32) UNSIGNED DEFAULT NULL,
  `_item_almacen` int(32) DEFAULT NULL,
  `_item_descripcion` varchar(50) NOT NULL,
  `_item_cantidad` decimal(32,0) NOT NULL DEFAULT '0',
  `_item_preciosiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_montodescuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_piva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalsiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalconiva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cotizacion_presupuesto_detalle`),
  KEY `fk_id_cotizacion` (`id_cotizacion`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_contables`
--

DROP TABLE IF EXISTS `cuentas_contables`;
CREATE TABLE IF NOT EXISTS `cuentas_contables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_cuenta` varchar(60) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `tipo` int(32) DEFAULT NULL,
  `banco` int(3) UNSIGNED ZEROFILL NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo` (`tipo`),
  KEY `banco` (`banco`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Cuentas Contables maestros';

--
-- Volcado de datos para la tabla `cuentas_contables`
--

INSERT INTO `cuentas_contables` (`id`, `nro_cuenta`, `descripcion`, `tipo`, `banco`) VALUES
(1, '01020552220000023032', 'Cuenta Recaudadora', NULL, 001),
(2, '01020552280000037251', 'Cuenta de Sobrantes', NULL, 001);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_presupuestaria`
--

DROP TABLE IF EXISTS `cuenta_presupuestaria`;
CREATE TABLE IF NOT EXISTS `cuenta_presupuestaria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta` varchar(30) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `tipo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cuenta_presupuestaria`
--

INSERT INTO `cuenta_presupuestaria` (`id`, `cuenta`, `descripcion`, `tipo`) VALUES
(1, '111111', 'Cuenta Ingreso', 1),
(2, '222222', 'Impuesto 1', 2),
(3, '333333', 'Impuesto 2', 3),
(4, '444444', 'Impuesto 3', 4),
(5, '555555', 'cuenta ingresos adicionales', 5),
(6, '666666', 'Cuenta Perdida', 6),
(7, '777777', 'Cuenta por Cobrar', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwpreejc`
--

DROP TABLE IF EXISTS `cwpreejc`;
CREATE TABLE IF NOT EXISTS `cwpreejc` (
  `RecNo` int(11) NOT NULL AUTO_INCREMENT,
  `RecPrePar` int(11) NOT NULL DEFAULT '0',
  `Monto` decimal(22,2) NOT NULL DEFAULT '0.00',
  `saldo` decimal(20,2) NOT NULL,
  `Fecha` date NOT NULL DEFAULT '0000-00-00',
  `RecNoOrders` int(11) NOT NULL DEFAULT '0',
  `Partida` varchar(13) COLLATE utf8_spanish_ci NOT NULL,
  `Sector` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `Programa` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Actividad` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `ordinal` varchar(12) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Marca` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`RecNo`),
  KEY `RecNoOrders` (`RecNoOrders`),
  KEY `Partida` (`Partida`),
  KEY `RecPrePar` (`RecPrePar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cwsector`
--

DROP TABLE IF EXISTS `cwsector`;
CREATE TABLE IF NOT EXISTS `cwsector` (
  `RecNo` int(11) NOT NULL AUTO_INCREMENT,
  `Sec` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `Denominacion` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`RecNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxc_edocuenta`
--

DROP TABLE IF EXISTS `cxc_edocuenta`;
CREATE TABLE IF NOT EXISTS `cxc_edocuenta` (
  `cod_edocuenta` int(32) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(32) NOT NULL,
  `documento` varchar(32) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `control` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `monto_base` float(10,2) NOT NULL,
  `monto_iva` float(10,2) NOT NULL,
  `fecha_emision` date NOT NULL DEFAULT '0000-00-00',
  `fecha_autorizado` date NOT NULL,
  `observacion` varchar(600) NOT NULL,
  `vencimiento_fecha` date NOT NULL DEFAULT '0000-00-00',
  `vencimiento_persona_contacto` varchar(100) DEFAULT NULL,
  `vencimiento_telefono` varchar(100) DEFAULT NULL,
  `vencimiento_descripcion` varchar(100) DEFAULT NULL,
  `marca` char(1) DEFAULT NULL COMMENT 'X: Pagada',
  `usuario_creacion` varchar(90) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `unidad` int(11) NOT NULL,
  `serie` varchar(25) NOT NULL,
  `clave` varchar(25) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cod_edocuenta`),
  KEY `id_cliente` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxc_edocuenta_detalle`
--

DROP TABLE IF EXISTS `cxc_edocuenta_detalle`;
CREATE TABLE IF NOT EXISTS `cxc_edocuenta_detalle` (
  `cod_edocuenta_detalle` int(32) NOT NULL AUTO_INCREMENT,
  `cod_edocuenta` int(32) NOT NULL,
  `documento` varchar(32) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `fecha_emision_edodet` date NOT NULL,
  `tipo` char(1) NOT NULL COMMENT 'd:Debito;c:Credito',
  `monto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1: Si esta Activa, 0: asiento anulado',
  `usuario_creacion` varchar(90) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `marca` varchar(1) NOT NULL COMMENT 'X: Pagada, P:Por Pagar',
  PRIMARY KEY (`cod_edocuenta_detalle`),
  KEY `cod_edocuenta` (`cod_edocuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxc_edocuenta_formapago`
--

DROP TABLE IF EXISTS `cxc_edocuenta_formapago`;
CREATE TABLE IF NOT EXISTS `cxc_edocuenta_formapago` (
  `cod_cxc_edocuenta_formapago` int(32) NOT NULL AUTO_INCREMENT,
  `cod_edocuenta_detalle` int(32) NOT NULL,
  `totalizar_monto_cancelar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_saldo_pendiente` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_efectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `opt_cheque` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nombre_banco` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_tarjeta` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_tipo_tarjeta` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_formapago',
  `opt_deposito` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_banco_deposito` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_otrodocumento` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_tipo_otrodocumento` int(32) NOT NULL DEFAULT '0',
  `totalizar_monto_otrodocumento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_otrodocumento` int(32) NOT NULL DEFAULT '0',
  `totalizar_banco_otrodocumento` int(32) NOT NULL DEFAULT '0',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(90) NOT NULL,
  PRIMARY KEY (`cod_cxc_edocuenta_formapago`),
  KEY `cod_edocuenta_detalle` (`cod_edocuenta_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxc_edocuenta_pago`
--

DROP TABLE IF EXISTS `cxc_edocuenta_pago`;
CREATE TABLE IF NOT EXISTS `cxc_edocuenta_pago` (
  `id_edocuenta` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL,
  `retiva_f` varchar(10) NOT NULL,
  `retislr_f` varchar(10) NOT NULL,
  `im_f` float(10,2) NOT NULL,
  PRIMARY KEY (`id_edocuenta`,`id_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxc_pago`
--

DROP TABLE IF EXISTS `cxc_pago`;
CREATE TABLE IF NOT EXISTS `cxc_pago` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT,
  `mbase_p` float(10,2) NOT NULL,
  `miva_p` float(10,2) NOT NULL,
  `retiva_p` float(10,2) NOT NULL,
  `retislr_p` float(10,2) NOT NULL,
  `im_p` float(10,2) NOT NULL,
  `total_p` float(10,2) NOT NULL,
  `id_banco` int(11) NOT NULL,
  `forma_p` varchar(2) NOT NULL,
  `trans_p` varchar(50) NOT NULL,
  `fecha_p` date NOT NULL,
  PRIMARY KEY (`id_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxp_edocuenta`
--

DROP TABLE IF EXISTS `cxp_edocuenta`;
CREATE TABLE IF NOT EXISTS `cxp_edocuenta` (
  `cod_edocuenta` int(32) NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(32) NOT NULL,
  `documento` varchar(32) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_emision` date NOT NULL DEFAULT '0000-00-00',
  `observacion` varchar(600) NOT NULL,
  `vencimiento_fecha` date NOT NULL DEFAULT '0000-00-00',
  `vencimiento_persona_contacto` varchar(100) DEFAULT NULL,
  `vencimiento_telefono` varchar(100) DEFAULT NULL,
  `vencimiento_descripcion` varchar(100) DEFAULT NULL,
  `marca` char(1) DEFAULT NULL COMMENT 'X: Pagada',
  `usuario_creacion` varchar(90) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_anulacion` varchar(25) NOT NULL,
  `observacion_anulado` varchar(250) NOT NULL,
  PRIMARY KEY (`cod_edocuenta`),
  KEY `id_proveedor` (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxp_edocuenta_detalle`
--

DROP TABLE IF EXISTS `cxp_edocuenta_detalle`;
CREATE TABLE IF NOT EXISTS `cxp_edocuenta_detalle` (
  `cod_edocuenta_detalle` int(32) NOT NULL AUTO_INCREMENT,
  `cod_edocuenta` int(32) NOT NULL,
  `documento` varchar(32) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `fecha_emision_edodet` date NOT NULL DEFAULT '0000-00-00',
  `tipo` char(1) NOT NULL COMMENT 'd:Debito;c:Credito',
  `monto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1: Si esta Activa, 0: asiento anulado',
  `usuario_creacion` varchar(90) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `marca` varchar(1) NOT NULL COMMENT 'X: Pagada, P:Por Pagar',
  `fecha_anulacion` varchar(25) NOT NULL,
  `observacion_anulado` varchar(250) NOT NULL,
  PRIMARY KEY (`cod_edocuenta_detalle`),
  KEY `cod_edocuenta` (`cod_edocuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxp_edocuenta_formapago`
--

DROP TABLE IF EXISTS `cxp_edocuenta_formapago`;
CREATE TABLE IF NOT EXISTS `cxp_edocuenta_formapago` (
  `cod_cxp_edocuenta_formapago` int(32) NOT NULL AUTO_INCREMENT,
  `cod_edocuenta_detalle` int(32) NOT NULL,
  `totalizar_monto_cancelar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_saldo_pendiente` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_efectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `opt_cheque` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nombre_banco` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_tarjeta` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_tipo_tarjeta` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_formapago',
  `opt_deposito` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_banco_deposito` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_otrodocumento` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_tipo_otrodocumento` int(32) NOT NULL DEFAULT '0',
  `totalizar_monto_otrodocumento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_otrodocumento` int(32) NOT NULL DEFAULT '0',
  `totalizar_banco_otrodocumento` int(32) NOT NULL DEFAULT '0',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(90) NOT NULL,
  PRIMARY KEY (`cod_cxp_edocuenta_formapago`),
  KEY `cod_edocuenta_detalle` (`cod_edocuenta_detalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxp_factura`
--

DROP TABLE IF EXISTS `cxp_factura`;
CREATE TABLE IF NOT EXISTS `cxp_factura` (
  `id_factura` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_factura` varchar(32) NOT NULL DEFAULT 'S/I',
  `cod_cont_factura` varchar(32) NOT NULL,
  `id_cxp_edocta` int(32) NOT NULL,
  `fecha_factura` date NOT NULL DEFAULT '0000-00-00',
  `fecha_recepcion` date NOT NULL DEFAULT '0000-00-00',
  `monto_base` decimal(10,2) NOT NULL DEFAULT '0.00',
  `monto_exento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `anticipo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `monto_total_con_iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `monto_total_sin_iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cod_impuesto` int(32) NOT NULL DEFAULT '0',
  `porcentaje_iva_mayor` decimal(10,2) NOT NULL DEFAULT '0.00',
  `monto_iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `porcentaje_iva_retenido` decimal(10,2) NOT NULL DEFAULT '0.00',
  `monto_retenido` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_a_pagar` decimal(10,2) NOT NULL,
  `cod_estatus` int(32) UNSIGNED DEFAULT NULL,
  `fecha_pago` date NOT NULL DEFAULT '0000-00-00',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(40) NOT NULL,
  `tipo` varchar(5) NOT NULL COMMENT 'Factura o Nota de credit',
  `factura_afectada` varchar(32) NOT NULL,
  `libro_compras` int(1) NOT NULL DEFAULT '0',
  `cod_correlativo_iva` bigint(32) NOT NULL,
  `cod_correlativo_islr` bigint(32) NOT NULL,
  `id_proveedor` int(32) NOT NULL,
  PRIMARY KEY (`id_factura`),
  KEY `fk_usuario` (`usuario_creacion`),
  KEY `fk_id_cliente` (`id_cxp_edocta`),
  KEY `fk_cod_estatus` (`cod_estatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxp_factura_detalle`
--

DROP TABLE IF EXISTS `cxp_factura_detalle`;
CREATE TABLE IF NOT EXISTS `cxp_factura_detalle` (
  `id_factura_detalle` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_factura_fk` int(32) NOT NULL,
  `monto_base` decimal(10,2) NOT NULL DEFAULT '0.00',
  `porcentaje_retenido` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cod_impuesto` int(32) NOT NULL DEFAULT '0',
  `monto_retenido` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_item` int(32) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id_factura_detalle`),
  KEY `fk_cod_estatus` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxp_factura_medico`
--

DROP TABLE IF EXISTS `cxp_factura_medico`;
CREATE TABLE IF NOT EXISTS `cxp_factura_medico` (
  `cxp_factura_medico_pk` int(11) NOT NULL AUTO_INCREMENT,
  `medico_fk` int(9) NOT NULL,
  `factura_fk` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_fac` date NOT NULL,
  `monto` decimal(13,2) NOT NULL,
  `estatus` int(1) NOT NULL,
  `cxp_edocta_fk` int(11) NOT NULL,
  `paciente` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `serie` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `servicio` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `id_cxp_mediq` int(11) NOT NULL,
  PRIMARY KEY (`cxp_factura_medico_pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='facturas a pagar a los medicos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cxp_factura_pago`
--

DROP TABLE IF EXISTS `cxp_factura_pago`;
CREATE TABLE IF NOT EXISTS `cxp_factura_pago` (
  `cxp_factura_pago_pk` int(11) NOT NULL AUTO_INCREMENT,
  `cxp_edocuenta_detalle_fk` int(11) NOT NULL COMMENT 'pago  realizado cargado en cxp_edocuenta_detalle',
  `cxp_factura_fk` int(11) NOT NULL COMMENT 'factura cargada en  cxp_factura',
  PRIMARY KEY (`cxp_factura_pago_pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='tabla que contiene la relacion entre pagos y facturas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
CREATE TABLE IF NOT EXISTS `departamentos` (
  `cod_departamento` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_departamento`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Rubros PYME';

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`cod_departamento`, `descripcion`) VALUES
(1, 'Alimentos'),
(2, 'No Alimentos'),
(3, 'PDVAL Hogar'),
(4, 'MCBE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposito`
--

DROP TABLE IF EXISTS `deposito`;
CREATE TABLE IF NOT EXISTS `deposito` (
  `id_deposito` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `nro_deposito` varchar(25) NOT NULL COMMENT 'Nro del Deposito',
  `monto` double NOT NULL COMMENT 'Monto Total del Deposito',
  `id_cataporte` varchar(50) NOT NULL COMMENT 'Nro de Cataporte donde se ubican los depositos',
  `fecha_deposito` datetime NOT NULL COMMENT 'Fecha del Deposito',
  `cod_banco` varchar(60) NOT NULL COMMENT 'Codigo del Banco',
  `usuario_creacion` varchar(255) NOT NULL COMMENT 'Usuario que Crea el Deposito',
  `nro_deposito_usuario` varchar(25) CHARACTER SET utf32 DEFAULT NULL,
  `monto_usuario` double DEFAULT NULL,
  `usuario_correccion` varchar(100) DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `fecha_correccion` date DEFAULT NULL,
  PRIMARY KEY (`id_deposito`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despacho`
--

DROP TABLE IF EXISTS `despacho`;
CREATE TABLE IF NOT EXISTS `despacho` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_despacho` varchar(45) NOT NULL DEFAULT '',
  `id_factura` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fecha_creacion` date NOT NULL DEFAULT '0000-00-00',
  `fecha_despacho` date NOT NULL DEFAULT '0000-00-00',
  `usuario` varchar(45) NOT NULL DEFAULT '',
  `cantidad_item` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `estatus` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 es actibo 0 inactivo',
  `tipo_despacho` int(10) NOT NULL COMMENT '0 si espor venta 1 si es por salida',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despacho_detalle`
--

DROP TABLE IF EXISTS `despacho_detalle`;
CREATE TABLE IF NOT EXISTS `despacho_detalle` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_despacho` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_item` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `item_descripcion` varchar(60) NOT NULL DEFAULT '',
  `serial` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despacho_new`
--

DROP TABLE IF EXISTS `despacho_new`;
CREATE TABLE IF NOT EXISTS `despacho_new` (
  `id_factura` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_factura` varchar(32) NOT NULL DEFAULT 'S/I',
  `cod_factura_fiscal` varchar(10) NOT NULL,
  `nroz` varchar(4) NOT NULL,
  `impresora_serial` varchar(50) NOT NULL,
  `id_cliente` int(32) NOT NULL,
  `cod_vendedor` int(32) NOT NULL,
  `fechaFactura` date NOT NULL DEFAULT '0000-00-00',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuentosItemFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montoItemsFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ivaTotalFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalTotalFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_items` int(32) NOT NULL DEFAULT '0',
  `totalizar_sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_parcial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_operacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pdescuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_base_imponible` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_general` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_retencion` decimal(10,2) NOT NULL,
  `formapago` varchar(20) NOT NULL,
  `cod_estatus` int(32) UNSIGNED DEFAULT NULL,
  `fecha_pago` date NOT NULL DEFAULT '0000-00-00',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(40) NOT NULL,
  `cesta_clap` int(11) NOT NULL COMMENT 'id de la cesta_clap',
  `money` varchar(100) NOT NULL,
  `facturacion` varchar(50) NOT NULL COMMENT 'Tipo de Facturacion',
  `id_pagos_consolidados` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_factura`),
  KEY `fk_cod_vendedor2` (`cod_vendedor`),
  KEY `fk_usuario` (`usuario_creacion`),
  KEY `fk_id_cliente` (`id_cliente`),
  KEY `fk_cod_estatus` (`cod_estatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despacho_new_detalle`
--

DROP TABLE IF EXISTS `despacho_new_detalle`;
CREATE TABLE IF NOT EXISTS `despacho_new_detalle` (
  `id_detalle_factura` int(32) NOT NULL AUTO_INCREMENT,
  `id_factura` int(32) UNSIGNED DEFAULT NULL,
  `id_item` int(32) UNSIGNED DEFAULT NULL,
  `_item_almacen` int(32) DEFAULT NULL,
  `_item_descripcion` varchar(32) NOT NULL,
  `_item_cantidad` decimal(32,2) NOT NULL DEFAULT '0.00',
  `_item_preciosiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_montodescuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_piva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalsiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalconiva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detalle_factura`),
  KEY `fk_id_factura` (`id_factura`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despacho_new_detalle_formapago`
--

DROP TABLE IF EXISTS `despacho_new_detalle_formapago`;
CREATE TABLE IF NOT EXISTS `despacho_new_detalle_formapago` (
  `cod_factura_detalle_formapago` int(32) NOT NULL AUTO_INCREMENT,
  `id_factura` int(32) UNSIGNED NOT NULL DEFAULT '0',
  `totalizar_monto_cancelar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_saldo_pendiente` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_efectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `opt_cheque` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nombre_banco` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_tarjeta` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_tipo_tarjeta` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_formapago',
  `opt_deposito` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_banco_deposito` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `fecha_vencimiento` date NOT NULL,
  `observacion` varchar(600) NOT NULL,
  `persona_contacto` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `opt_otrodocumento` tinyint(1) NOT NULL,
  `totalizar_tipo_otrodocumento` int(32) NOT NULL COMMENT 'Tipo de documento',
  `totalizar_monto_otrodocumento` decimal(10,2) NOT NULL,
  `totalizar_nro_otrodocumento` int(32) NOT NULL,
  `totalizar_banco_otrodocumento` int(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(60) NOT NULL,
  PRIMARY KEY (`cod_factura_detalle_formapago`),
  KEY `id_factura` (`id_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distrito_escolar`
--

DROP TABLE IF EXISTS `distrito_escolar`;
CREATE TABLE IF NOT EXISTS `distrito_escolar` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL DEFAULT '',
  `id_ministerio` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `estado` int(10) NOT NULL,
  `municipio` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `distrito_escolar`
--

INSERT INTO `distrito_escolar` (`id`, `descripcion`, `id_ministerio`, `estado`, `municipio`) VALUES
(2, 'escuela libertador', 1, 24, 'libertador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `divisas`
--

DROP TABLE IF EXISTS `divisas`;
CREATE TABLE IF NOT EXISTS `divisas` (
  `id_divisa` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(30) DEFAULT NULL,
  `Abreviatura` varchar(10) DEFAULT NULL,
  `Cambio_unico` float DEFAULT NULL,
  PRIMARY KEY (`id_divisa`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `divisas`
--

INSERT INTO `divisas` (`id_divisa`, `Nombre`, `Abreviatura`, `Cambio_unico`) VALUES
(14, 'Bolivar', 'Bs.', 1),
(15, 'Dolar', '$', 4.33),
(16, 'Euros', 'eur', 0),
(17, 'Pesos', '$', 1890),
(18, 'Balboa', '$', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidades`
--

DROP TABLE IF EXISTS `entidades`;
CREATE TABLE IF NOT EXISTS `entidades` (
  `cod_entidad` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) NOT NULL,
  PRIMARY KEY (`cod_entidad`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `entidades`
--

INSERT INTO `entidades` (`cod_entidad`, `descripcion`) VALUES
(1, 'Persona Natural Residente'),
(2, 'Persona Natural No Residente'),
(3, 'Persona Juridica Domiciliada'),
(4, 'Persona Juridica No Domiciliada'),
(5, 'Persona Natural Residente (MEDICOS/ABOGADOS)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades_proveedor`
--

DROP TABLE IF EXISTS `especialidades_proveedor`;
CREATE TABLE IF NOT EXISTS `especialidades_proveedor` (
  `cod_especialidad` int(32) NOT NULL AUTO_INCREMENT,
  `especialidad` varchar(250) NOT NULL,
  `id_pclasif` int(11) NOT NULL,
  PRIMARY KEY (`cod_especialidad`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `especialidades_proveedor`
--

INSERT INTO `especialidades_proveedor` (`cod_especialidad`, `especialidad`, `id_pclasif`) VALUES
(1, 'TECNOLOGIA', 1),
(4, 'MATERIAL EMPAQUE', 1),
(5, 'MAT. OFICINA Y ACCESORIOS', 1),
(6, 'SERVICIOS BASICOS', 1),
(7, 'MANTENIMIENTO Y REPARACIONES', 1),
(8, 'HONORARIOS PROFESIONALES', 1),
(9, 'TRANSPORTE', 1),
(10, 'SEGURIDAD Y VIGILANCIA', 1),
(11, 'PUBLICIDAD Y PROPAGANDA', 1),
(12, 'REMODELACION Y DECORACIONES', 1),
(13, 'ALQUILERES Y CONDOMINIOS', 1),
(14, 'LIMPIEZA Y ART. DE LIMPIEZA', 1),
(15, 'EMPLEADOS', 1),
(16, 'PROVEEDOR CAJA CHICA', 1),
(17, 'GUARDERIAS', 1),
(18, 'OTROS PROVEEDORES NACIONALES', 1),
(19, 'INSUMOS MEDICOS', 1),
(22, 'CIRUJANO BUCAL Y MAXILOFACIAL', 2),
(23, 'GUIA DIAGNOSTICA', 2),
(24, 'MEDICINA INTERNA', 2),
(25, 'GASTROENTEROLOGIA PEDIATRICA', 2),
(26, 'PEDIATRIA', 2),
(27, 'GASTROPEDIATRIA', 2),
(28, 'OTORRINOLARINGOLOGIA', 2),
(29, 'GINECOLOGIA', 2),
(30, 'UROLOGIA', 2),
(31, 'ODONTOLOGIA', 2),
(32, 'TRAUMATOLOGIA', 2),
(33, 'RADIOLOGIA', 2),
(34, 'GASTROENTEROLOGIA', 2),
(35, 'CARDIOLOGIA', 2),
(36, 'RESIDENTE', 2),
(37, 'CIRUGIA GENERAL', 2),
(38, 'CIRUJANO ONCOLOGO', 2),
(39, 'FISIATRIA', 2),
(40, 'MASTOLOGIA', 2),
(41, 'ANESTESIOLOGO', 2),
(42, 'GINECO_OBSTETRA', 2),
(43, 'NEFROLOGIA', 2),
(44, 'CIRUGÃA PLASTICA', 2),
(45, 'CIRUJANO UROLOGO', 2),
(46, 'MEDICINA FAMILIAR', 2),
(47, 'NEUROLOGÃA', 2),
(48, 'ENDOCRINOLOGIA', 2),
(49, 'CIRUGÃA PEDIATRICA', 2),
(50, 'LABORATORIO', 2),
(51, 'NEUROCIRUGÃA', 2),
(52, 'NEUMONOLOGIA', 2),
(53, 'ONCOLOGIA', 2),
(54, 'TECNICO AUDIOLOGO', 2),
(55, 'FISIOTERAPIA', 2),
(56, 'DERMATOLOGIA', 2),
(57, 'OFTALMOLOGIA', 2),
(58, 'ALERGOLOGIA', 2),
(59, 'PSICOLOGIA', 2),
(60, 'TECNICO CARDIOPULMONAR', 2),
(61, 'ANATOMIA PATOLOGICA', 2),
(62, 'BIOANALISIS', 2),
(63, 'CIRUJANO DE MANO', 2),
(64, 'MEDICO GENERAL', 2),
(65, 'MEDICINA OCUPACIONAL', 2),
(66, 'MEDICO DE PLANTA', 2),
(67, 'TRAUMATOLOGIA Y ORTOPEDIA', 2),
(68, 'ODONTOPEDIATRICA', 2),
(69, 'ESTETICA Y COSMETOLOGIA', 2),
(70, 'CIRUGIA Y UROLOGIA PEDIATRICA', 2),
(71, 'RADIOTERAPIA', 2),
(72, 'HEMATOLOGO', 2),
(73, 'PSIQUIATRA', 2),
(74, 'TECNICO RADIOLOGO', 2),
(75, 'PEDIATRA Y PUERICULTURA', 2),
(76, 'CARDIOLOGIA PEDIATRICA', 2),
(78, 'NUTRICIONISTA', 2),
(79, ' SELECCIONE...', 0),
(80, 'OTROS GASTOS AL PERSONAL', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

DROP TABLE IF EXISTS `estados`;
CREATE TABLE IF NOT EXISTS `estados` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL DEFAULT '',
  `estatus` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1=utilizado region 0=no utilizado',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='los estados que perteneceran a regiones';

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id`, `nombre`, `estatus`) VALUES
(1, 'Amazonas', 0),
(2, 'Anzoátegui', 0),
(3, 'Apure', 0),
(4, 'Aragua', 0),
(5, 'Barinas', 0),
(6, 'Bolivar', 0),
(7, 'Carabobo', 0),
(8, 'Cojedes', 0),
(9, 'Delta Amacuro', 0),
(10, 'Falcón', 0),
(11, 'Guárico', 0),
(12, 'Lara', 0),
(13, 'Mérida', 0),
(14, 'Miranda', 0),
(15, 'Monagas', 0),
(16, 'Nueva Esparta', 0),
(17, 'Portuguesa', 0),
(18, 'Sucre', 0),
(19, 'Tachira', 0),
(20, 'Trujillo', 0),
(21, 'Vargas', 0),
(22, 'Yaracuy', 0),
(23, 'Zulia', 0),
(24, 'Distrito Capital', 1),
(25, 'Dependencias Federales', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_puntos`
--

DROP TABLE IF EXISTS `estados_puntos`;
CREATE TABLE IF NOT EXISTS `estados_puntos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_estado` int(45) DEFAULT NULL,
  `nombre_estado` varchar(45) DEFAULT NULL,
  `id_region` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codigo_estado` (`codigo_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estados_puntos`
--

INSERT INTO `estados_puntos` (`id`, `codigo_estado`, `nombre_estado`, `id_region`) VALUES
(2, 1, 'DISTRITO CAPITAL', 1),
(3, 2, 'AMAZONAS', 8),
(4, 3, 'ANZOATEGUI ', 5),
(5, 4, 'APURE', 7),
(6, 5, 'ARAGUA ', 3),
(7, 6, 'BARINAS', 6),
(8, 7, 'BOLIVAR ', 8),
(9, 8, 'CARABOBO', 3),
(10, 9, 'COJEDES', 3),
(11, 10, 'DELTA AMACURO', 8),
(12, 11, 'FALCON ', 2),
(13, 12, 'GUARICO', 7),
(14, 13, 'LARA', 2),
(15, 14, 'MERIDA', 6),
(16, 15, 'MIRANDA', 1),
(17, 16, 'MONAGAS', 5),
(18, 17, 'NUEVA ESPARTA', 9),
(19, 18, 'PORTUGUESA', 2),
(20, 19, 'SUCRE', 5),
(21, 20, 'TACHIRA', 6),
(22, 21, 'TRUJILLO', 6),
(23, 22, 'VARGAS', 1),
(24, 23, 'YARACUY', 2),
(25, 24, 'ZULIA', 4),
(27, 25, 'OTROS', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_region`
--

DROP TABLE IF EXISTS `estado_region`;
CREATE TABLE IF NOT EXISTS `estado_region` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_region` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_estado` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='tabla de union de region con estados';

--
-- Volcado de datos para la tabla `estado_region`
--

INSERT INTO `estado_region` (`id`, `id_region`, `id_estado`) VALUES
(1, 1, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatus`
--

DROP TABLE IF EXISTS `estatus`;
CREATE TABLE IF NOT EXISTS `estatus` (
  `cod_estatus` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(40) NOT NULL,
  `detalle_descripcion` text,
  PRIMARY KEY (`cod_estatus`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estatus`
--

INSERT INTO `estatus` (`cod_estatus`, `descripcion`, `detalle_descripcion`) VALUES
(1, 'En proceso', 'Su estatus inicial es \"En Proceso\" si:\r\n1.Cuando la factura es creada.\r\n2.Cuando la factura es creada y su pago es menor al total de la factura (queda con saldo pendiente por cancelar).'),
(2, 'Pagada', 'Su estatus inicial es \"Pagada\" si:\r\n1.Cuando la factura es Pagada pagada (con saldo pendiente cero(0)).'),
(3, 'Anulada', 'Su estatus inicial es \"Anulada\" si:\r\n1.Cuando la factura presenta fallas o se ha cometido un error al momento de generar.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

DROP TABLE IF EXISTS `factura`;
CREATE TABLE IF NOT EXISTS `factura` (
  `id_factura` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_factura` varchar(32) NOT NULL DEFAULT 'S/I',
  `cod_factura_fiscal` varchar(10) NOT NULL,
  `nroz` varchar(4) NOT NULL,
  `impresora_serial` varchar(50) NOT NULL,
  `id_cliente` int(32) NOT NULL,
  `cod_vendedor` int(32) NOT NULL,
  `fechaFactura` date NOT NULL DEFAULT '0000-00-00',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuentosItemFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montoItemsFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ivaTotalFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalTotalFactura` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_items` int(32) NOT NULL DEFAULT '0',
  `totalizar_sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_parcial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_operacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pdescuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_base_imponible` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_general` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_retencion` decimal(10,2) NOT NULL,
  `formapago` varchar(20) NOT NULL,
  `cod_estatus` int(32) UNSIGNED DEFAULT NULL,
  `fecha_pago` date NOT NULL DEFAULT '0000-00-00',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(40) NOT NULL,
  `cesta_clap` int(11) NOT NULL,
  `money` varchar(100) NOT NULL,
  `facturacion` varchar(50) NOT NULL COMMENT 'Tipo de Facturacion',
  PRIMARY KEY (`id_factura`),
  KEY `fk_cod_vendedor2` (`cod_vendedor`),
  KEY `fk_usuario` (`usuario_creacion`),
  KEY `fk_id_cliente` (`id_cliente`),
  KEY `fk_cod_estatus` (`cod_estatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_detalle`
--

DROP TABLE IF EXISTS `factura_detalle`;
CREATE TABLE IF NOT EXISTS `factura_detalle` (
  `id_detalle_factura` int(32) NOT NULL AUTO_INCREMENT,
  `id_factura` int(32) UNSIGNED DEFAULT NULL,
  `id_item` int(32) UNSIGNED DEFAULT NULL,
  `_item_almacen` int(32) DEFAULT NULL,
  `_item_descripcion` varchar(32) NOT NULL,
  `_item_cantidad` decimal(32,2) NOT NULL DEFAULT '0.00',
  `_item_preciosiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_montodescuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_piva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalsiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalconiva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detalle_factura`),
  KEY `fk_id_factura` (`id_factura`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_detalle_formapago`
--

DROP TABLE IF EXISTS `factura_detalle_formapago`;
CREATE TABLE IF NOT EXISTS `factura_detalle_formapago` (
  `cod_factura_detalle_formapago` int(32) NOT NULL AUTO_INCREMENT,
  `id_factura` int(32) UNSIGNED NOT NULL DEFAULT '0',
  `totalizar_monto_cancelar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_saldo_pendiente` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_efectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `opt_cheque` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nombre_banco` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_tarjeta` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_tipo_tarjeta` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_formapago',
  `opt_deposito` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_banco_deposito` int(3) UNSIGNED ZEROFILL DEFAULT NULL COMMENT 'cod_banco',
  `totalizar_banco_deposito_cuenta` varchar(60) DEFAULT NULL COMMENT 'cuenta asociada al banco',
  `fecha_vencimiento` date NOT NULL,
  `observacion` varchar(600) NOT NULL,
  `persona_contacto` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `opt_otrodocumento` tinyint(1) NOT NULL,
  `opt_retencion_iva1x1000` tinyint(1) NOT NULL,
  `totalizar_monto_retencion_iva1x1000` decimal(10,2) NOT NULL,
  `opt_retencion_iva` tinyint(1) NOT NULL,
  `totalizar_monto_retencion_iva` decimal(10,2) NOT NULL,
  `totalizar_nro_retencion1x1000` int(11) NOT NULL,
  `totalizar_nro_retencion` int(11) NOT NULL,
  `totalizar_tipo_otrodocumento` int(32) NOT NULL COMMENT 'Tipo de documento',
  `totalizar_monto_otrodocumento` decimal(10,2) NOT NULL,
  `totalizar_nro_otrodocumento` int(32) NOT NULL,
  `totalizar_banco_otrodocumento` int(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(60) NOT NULL,
  `opt_credito2` tinyint(1) NOT NULL,
  `totalizar_monto_credito2` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_factura_detalle_formapago`),
  KEY `id_factura` (`id_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_devolucion`
--

DROP TABLE IF EXISTS `factura_devolucion`;
CREATE TABLE IF NOT EXISTS `factura_devolucion` (
  `id_devolucion` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_devolucion` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `cod_factura` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_devolucion` date NOT NULL DEFAULT '0000-00-00',
  `cod_devolucion_fiscal` int(6) NOT NULL,
  `nroz` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `impresora_serial` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_devolucion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_impuestos`
--

DROP TABLE IF EXISTS `factura_impuestos`;
CREATE TABLE IF NOT EXISTS `factura_impuestos` (
  `id_factura_impuestos` int(32) NOT NULL AUTO_INCREMENT,
  `id_factura` int(32) UNSIGNED NOT NULL,
  `totalizar_base_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pbase_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descripcion_base_retencion` int(32) NOT NULL DEFAULT '0' COMMENT 'fk_cod_impuesto_iva',
  `cod_impuesto_iva` int(32) NOT NULL,
  `totalizar_monto_iva2` decimal(10,2) NOT NULL,
  `totalizar_monto_1x1000` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(50) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_factura_impuestos`),
  KEY `id_factura` (`id_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `fechas_minimas`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `fechas_minimas`;
CREATE TABLE IF NOT EXISTS `fechas_minimas` (
`fecha` date
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `firmas`
--

DROP TABLE IF EXISTS `firmas`;
CREATE TABLE IF NOT EXISTS `firmas` (
  `cod_firmas` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cargo_persona` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre_persona` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `orden_reporte` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `cod_reporte` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `modulo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_firmas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulacion_impuestos`
--

DROP TABLE IF EXISTS `formulacion_impuestos`;
CREATE TABLE IF NOT EXISTS `formulacion_impuestos` (
  `cod_formula` int(11) NOT NULL AUTO_INCREMENT,
  `formula` mediumtext NOT NULL,
  `cod_entidad` int(11) NOT NULL,
  `cod_tipo_impuesto` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `fecha_aplicacion` date NOT NULL,
  `estado` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(100) NOT NULL,
  PRIMARY KEY (`cod_formula`),
  KEY `fk_cod_tipo_impuesto` (`cod_tipo_impuesto`),
  KEY `fk_cod_entidad` (`cod_entidad`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `formulacion_impuestos`
--

INSERT INTO `formulacion_impuestos` (`cod_formula`, `formula`, `cod_entidad`, `cod_tipo_impuesto`, `descripcion`, `fecha_aplicacion`, `estado`, `fecha_creacion`, `usuario_creacion`) VALUES
(1, '$MONTO=($MONTOBASE*$PORCENTAJE)/100;', 3, 1, 'Retencion de Impuesto al Valor Agregado 75% Persona Juridica Domiciliada', '2010-01-01', 0, '2010-08-27 11:15:16', 'asys'),
(2, '$MONTO=($MONTOBASE*$PORCENTAJE)/100;', 3, 1, 'Retencion de Impuesto al Valor Agregado 100% Persona Juridica Domiciliada', '2010-01-01', 0, '2010-08-27 11:45:47', 'asys'),
(3, '$MONTO=($MONTOBASE*$PORCENTAJE)/100;', 1, 1, 'Retencion de Impuesto al Valor Agregado 75% Persona Natural Residente', '0000-00-00', 0, '2011-06-28 13:11:58', ''),
(4, '$MONTO=($MONTOBASE*$PORCENTAJE)/100;', 1, 1, 'Retencion de Impuesto al Valor Agregado 100% Persona Natural Residente', '0000-00-00', 0, '2011-06-28 13:32:58', ''),
(5, '$MONTO=($MONTOBASE*$PORCENTAJE)/100;', 2, 1, 'Retencion de Impuesto al Valor Agregado 75%	Persona Natural No Residente', '0000-00-00', 0, '2011-06-28 13:32:58', ''),
(6, '$MONTO=($MONTOBASE*$PORCENTAJE)/100;', 2, 1, 'Retencion de Impuesto al Valor Agregado 100% Persona Natural No Residente', '0000-00-00', 0, '2011-06-28 13:33:08', ''),
(7, '$MONTO=($MONTOBASE*$PORCENTAJE)/100;', 4, 1, 'Retencion de Impuesto al Valor Agregado 75% Persona Juridica No Domiciliada', '0000-00-00', 0, '2011-06-28 13:33:08', ''),
(8, '$MONTO=($MONTOBASE*$PORCENTAJE)/100;', 4, 1, 'Retencion de Impuesto al Valor Agregado 100% Persona Juridica No Domiciliada', '0000-00-00', 0, '2011-06-28 13:33:31', ''),
(9, '$MAYORA=$UT*$FACTORM;\r\n$SUSTRACCION=($UT*$FACTORSUST*$PORCENTAJE)/100;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTOBASE>=$MAYORA\",$MONTO,0);\r\n$MONTO=SI(\"$MONTO>0\",$MONTO-$SUSTRACCION,0);', 1, 2, 'Retencion ISLR Honorarios Profesionales Persona Natural Residente *****', '0000-00-00', 0, '2011-06-28 15:02:00', 'asys'),
(11, '$SUSTRACCION=$FACTORSUST;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTO>=$SUSTRACCION\",$MONTO,0);', 3, 2, 'Retencion ISLR Honorarios Profesionales Persona Juridica *****', '0000-00-00', 0, '2011-06-28 15:12:21', 'asys'),
(14, '$MAYORA=$UT*$FACTORM;\r\n$SUSTRACCION=($UT*$FACTORSUST*$PORCENTAJE)/100;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTOBASE>=$MAYORA\",$MONTO,0);\r\n$MONTO=SI(\"$MONTO>0\",$MONTO-$SUSTRACCION,0);', 5, 2, 'Retencion ISLR Honorarios Profesionales Persona Natural Residenciada MEDICOS / ABOGADOS', '0000-00-00', 0, '2011-07-07 12:21:55', 'asys'),
(15, '$MAYORA=$UT*$FACTORM;\r\n$SUSTRACCION=($UT*$FACTORSUST*$PORCENTAJE)/100;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTOBASE>=$MAYORA\",$MONTO,0);\r\n$MONTO=SI(\"$MONTO>0\",$MONTO-$SUSTRACCION,0);', 1, 2, 'Retencion ISLR Comiciones Mercantiles PN', '0000-00-00', 0, '2011-07-07 12:31:12', 'asys'),
(16, '$MAYORA=$FACTOM;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTO>=$MAYORA\",$MONTO,0);', 3, 2, 'Retencion ISLR Comisiones Mercantiles PJ', '0000-00-00', 0, '2011-07-07 12:31:57', 'asys'),
(17, '$MAYORA=$UT*$FACTORM;\r\n$SUSTRACCION=($UT*$FACTORSUST*$PORCENTAJE)/100;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTOBASE>=$MAYORA\",$MONTO,0);\r\n$MONTO=SI(\"$MONTO>0\",$MONTO-$SUSTRACCION,0);', 1, 2, 'Retencion ISLR Interes PN', '0000-00-00', 0, '2011-07-07 12:32:29', 'asys'),
(18, '$SUSTRACCION=$FACTORSUST;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTO>=$SUSTRACCION\",$MONTO,0);', 3, 2, 'Retencion ISLR Intereses PJ', '0000-00-00', 0, '2011-07-07 12:32:46', 'asys'),
(19, '$MAYORA=$UT*$FACTORM;\r\n$SUSTRACCION=($UT*$FACTORSUST*$PORCENTAJE)/100;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTOBASE>=$MAYORA\",$MONTO,0);\r\n$MONTO=SI(\"$MONTO>0\",$MONTO-$SUSTRACCION,0);', 1, 2, 'Retencion ISLR Servicios PN', '0000-00-00', 0, '2011-07-07 12:33:11', 'asys'),
(20, '$SUSTRACCION=$FACTORSUST;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTO>=$SUSTRACCION\",$MONTO,0);', 3, 2, 'Retencion ISLR Servicios PJ', '0000-00-00', 0, '2011-07-07 12:33:22', 'asys'),
(21, '$MAYORA=$UT*$FACTORM;\r\n$SUSTRACCION=($UT*$FACTORSUST*$PORCENTAJE)/100;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTOBASE>=$MAYORA\",$MONTO,0);\r\n$MONTO=SI(\"$MONTO>0\",$MONTO-$SUSTRACCION,0);', 1, 2, 'Retencion ISLR Arrendamiento de Bienes Inmuebles PN', '0000-00-00', 0, '2011-07-07 12:34:20', 'asys'),
(22, '$SUSTRACCION=$FACTORSUST;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTO>=$SUSTRACCION\",$MONTO,0);', 3, 2, 'Retencion ISLR Arrendamiento de Bienes Inmuebles PJ', '0000-00-00', 0, '2011-07-07 12:35:40', 'asys'),
(23, '$MAYORA=$UT*$FACTORM;\r\n$SUSTRACCION=($UT*$FACTORSUST*$PORCENTAJE)/100;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTOBASE>=$MAYORA\",$MONTO,0);\r\n$MONTO=SI(\"$MONTO>0\",$MONTO-$SUSTRACCION,0);', 1, 2, 'Retencion ISLR Arrendamiento de Bienes Muebles PN', '0000-00-00', 0, '2011-07-07 12:36:57', 'asys'),
(24, '$SUSTRACCION=$FACTORSUST;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTO>=$SUSTRACCION\",$MONTO,0);', 3, 2, 'Retencion ISLR Arrendamiento de Bienes Muebles PJ', '0000-00-00', 0, '2011-07-07 12:37:20', 'asys'),
(25, '$MAYORA=$UT*$FACTORM;\r\n$SUSTRACCION=($UT*$FACTORSUST*$PORCENTAJE)/100;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTOBASE>=$MAYORA\",$MONTO,0);\r\n$MONTO=SI(\"$MONTO>0\",$MONTO-$SUSTRACCION,0);', 1, 2, 'Retencion ISLR Gastos de Transporte Nacional y Fletes PN', '0000-00-00', 0, '2011-07-07 12:41:07', 'asys'),
(26, '$SUSTRACCION=$FACTORSUST;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTO>=$SUSTRACCION\",$MONTO,0);', 3, 2, 'Retencion ISLR Gastos de Transporte Nacional y Fletes PJ', '0000-00-00', 0, '2011-07-07 12:41:49', 'asys'),
(27, '$MAYORA=$UT*$FACTORM;\r\n$SUSTRACCION=($UT*$FACTORSUST*$PORCENTAJE)/100;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTOBASE>=$MAYORA\",$MONTO,0);\r\n$MONTO=SI(\"$MONTO>0\",$MONTO-$SUSTRACCION,0);', 1, 2, 'Retencion ISLR Propaganda y Publicidad PN', '0000-00-00', 0, '2011-07-07 12:42:36', 'asys'),
(28, '$SUSTRACCION=$FACTORSUST;\r\n$MONTO=($MONTOBASE*$PORCENTAJE)/100;\r\n$MONTO=SI(\"$MONTO>=$SUSTRACCION\",$MONTO,0);', 3, 2, 'Retencion ISLR Propaganda y Publicidad PJ', '0000-00-00', 0, '2011-07-07 12:42:52', 'asys');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE IF NOT EXISTS `grupo` (
  `cod_grupo` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL DEFAULT '',
  `id_rubro` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `restringido` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 si es restringido 0 si no',
  `cantidad_rest` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'la cantidad numerica de rubros restringido',
  `dias_rest` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'la cantidad de dias en el que el producto  restringido',
  `grupopos` varchar(255) NOT NULL,
  PRIMARY KEY (`cod_grupo`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`cod_grupo`, `descripcion`, `id_rubro`, `restringido`, `cantidad_rest`, `dias_rest`, `grupopos`) VALUES
(1, 'AGUA', 1, 0, 1000, 7, 'db8a2149-847d-8c84-e918-cecfceda57ed'),
(2, 'ALIMENTOS CONGELADOS', 1, 0, 1000, 7, '1dd9c8d0-e6c6-5cc4-475e-3b12718cc908'),
(3, 'ALIMENTOS INFANTILES', 1, 0, 1000, 7, '3f27dddd-0a18-3f38-300b-2308f0f65230'),
(5, 'ALIMENTOS PREPARADOS', 1, 0, 1000, 7, '723caa18-2116-5e89-825f-25ed28039f26'),
(6, 'PAPEL DE ALUMINIO', 2, 0, 1000, 7, '634d5587-9ed5-4173-d7ed-d7764da38e28'),
(7, 'ASEO Y LIMPIEZA', 2, 0, 1000, 7, 'd36530be-dcf8-4d04-7c62-fb484acd54d7'),
(8, 'AVES', 1, 0, 1000, 7, 'd9651bdc-0063-29fa-3ea0-f0aadc6ff580'),
(9, 'BATERIAS', 2, 0, 1000, 7, '7c38c195-6f65-b9ad-e00b-18925686cdea'),
(10, 'BEBIDAS EN POLVO Y/O MERENGADAS', 1, 0, 1000, 7, 'b1e58514-3763-4201-9ef9-68fd5bada08e'),
(11, 'BEBIDAS GASEOSAS', 1, 0, 1000, 7, 'a1d4120b-171b-8dcd-3720-7f5e9f13ccbd'),
(12, 'BOLSAS', 2, 0, 1000, 7, 'e333f867-c9ee-21ce-eefc-abf713bfc940'),
(14, 'CARNE DE RES', 1, 0, 1000, 7, '48161d21-7358-f3ea-938a-db9cbb9d6d51'),
(15, 'CASABE', 1, 0, 1000, 7, 'ecb3136b-7529-af31-a7e6-7f67d8309c52'),
(16, 'CEREALES', 1, 0, 1000, 7, '40e7739c-665e-3161-e105-64e423b314fe'),
(17, 'CHARCUTERIA Y EMBUTIDOS', 1, 0, 1000, 7, '7cd8520b-4e9e-dd3d-48b9-66b8d619d0f3'),
(18, 'CHOCOLATE', 1, 0, 1000, 7, '901bf43d-e8ba-73e9-284d-3866724d6096'),
(19, 'CONCENTRADOS DE TOMATE', 1, 0, 1000, 7, '8d474aeb-e25a-cd1b-c9ce-01708179428b'),
(20, 'CONDIMENTOS Y ESPECIAS', 1, 0, 1000, 7, '33ed0838-e812-1752-76c4-0189dd6cc9dd'),
(21, 'CONFITES', 1, 0, 1000, 7, '14b08305-db7d-4018-e30e-1332cd57d20b'),
(22, 'CONSERVAS Y DULCES', 1, 0, 1000, 7, '9b1e8f1c-c5e1-b4ed-f999-2ea1f60549fe'),
(23, 'CUIDADO PERSONAL', 2, 0, 1000, 7, 'e6629536-ad0d-7861-c47d-5d99fe98b1bc'),
(24, 'DERIVADOS DE CACAO', 1, 0, 1000, 7, 'db24420e-c18b-98e5-6a92-f00e7460e03b'),
(25, 'DERIVADOS LACTEOS', 1, 1, 20, 7, '9407a50a-179f-5a38-cefe-f9759c53614d'),
(26, 'EDULCORANTE', 1, 0, 1000, 7, '0cb3b718-19f5-1ba2-1a06-e12137f05525'),
(27, 'ELECTRODOMESTICOS Y LINEA BLANCA', 2, 0, 1000, 7, '5fa91496-236c-1294-d6b7-59a7b39e6be4'),
(28, 'ENCURTIDOS', 1, 0, 1000, 7, '45cb010b-d8f5-222f-636c-d0d1198672c2'),
(29, 'ENLATADOS', 1, 1, 100, 1, 'f452b31d-32ca-cebe-c3cf-604787ed0309'),
(30, 'ESENCIAS Y SABORES ARTIFICIALES', 1, 0, 1000, 7, '213110e8-9506-a289-20cc-785094860a28'),
(31, 'FRUTAS, HORTALIZAS Y VERDURAS', 1, 0, 1000, 7, '939e51f1-651e-5c17-846c-980b828fb0ed'),
(33, 'GALLETAS', 1, 0, 1000, 7, '821b04a3-dd93-fb8e-5d55-9a84517a6fb8'),
(34, 'GRASAS Y ACEITES COMESTIBLES', 1, 0, 1000, 7, '502d9377-3ba6-d78a-608c-22e4113a14e1'),
(35, 'HARINAS', 1, 0, 1000, 7, '06807b67-08c6-7266-cb01-7a5fba74062a'),
(36, 'HILOS', 2, 0, 1000, 7, '45bc6792-9133-bc91-d160-8e260e6b874b'),
(37, 'HUEVOS', 1, 0, 1000, 7, '8c72894b-7ed0-475a-874f-9e9ab29d55ae'),
(38, 'INFUSIONES', 1, 0, 1000, 7, '3be250fc-fd8d-ff45-b9e7-e71b19e43e35'),
(39, 'JUGOS DE FRUTAS Y NECTARES', 1, 0, 1000, 7, 'fdfe1df9-2896-e7ef-07cc-c8c72b0ad1c1'),
(40, 'LEGUMINOSAS', 1, 0, 1000, 7, '71fe7396-beea-5122-7378-42e079e257e9'),
(41, 'LENCERIA', 2, 0, 1000, 7, 'a4d91cda-18f3-2f07-5b80-56a4def3f77d'),
(43, 'DETERGENTES Y DESINFECTANTES', 2, 0, 1000, 7, '3ab30d89-4979-643d-72c4-ba279c37608a'),
(44, 'MATERIALES ELECTRICOS', 2, 0, 1000, 7, '429bd878-e23c-7819-21ed-66eb979f47cb'),
(45, 'MEZCLAS PARA POSTRES', 1, 0, 1000, 7, '3fe9fb08-248b-2cfc-4c01-54c3326b07a0'),
(46, 'OTRAS BEBIDAS', 1, 0, 1000, 7, 'fefa7715-13e5-7a12-f6df-eb78d974907d'),
(47, 'PAN Y DERIVADOS', 1, 0, 1000, 7, 'b7ffae8f-6d49-f565-f61c-e0d7a8cbef2d'),
(48, 'PANALES DESECHABLES, TOALLAS SANITARIAS, PROTECTOR', 2, 0, 1000, 7, '45fa333c-8d8c-6519-71e1-268cc63d0c97'),
(49, 'PAPELERIA Y ARTICULOS ESCOLARES', 2, 0, 1000, 7, '1f1e0b08-9aef-e666-5e27-9880145f8c17'),
(51, 'PASTAS ALIMENTICIAS', 1, 0, 1000, 7, '0cbcb252-8604-a808-905d-5779963fc893'),
(53, 'PESCADOS, MOLUSCOS, MARISCOS Y FRUTOS DEL MAR', 1, 0, 1000, 7, '3158fcc8-f88c-f336-249d-6414cd50a19d'),
(54, 'PORCINOS', 1, 0, 1000, 7, '978a7885-7309-034c-5bd1-276192b3e842'),
(55, 'POSTRES PREPARADOS', 1, 0, 1000, 7, '4ac0c3e9-4144-2408-d2db-9df39ca4b173'),
(56, 'PRODUCTOS DE PAPEL, PLASTICO Y/O CARTON', 2, 0, 1000, 7, 'eaf69bb5-2323-5704-7cc4-7ee52eb36e94'),
(57, 'PULPA DE FRUTAS', 1, 0, 1000, 7, '52ea2e27-ae24-846d-5722-c93651d4ffb1'),
(58, 'SALSAS Y ADEREZOS', 1, 0, 1000, 7, '0680ace8-89f9-7acf-887e-251cecc368b9'),
(59, 'SEMILLAS', 1, 0, 1000, 7, '5792a665-84fa-bc8d-4715-264527db0b73'),
(60, 'SERVILLETAS Y PAPEL HIGIENICO', 2, 0, 1000, 7, '542e07a8-87d4-abc6-a813-c594c97d2fa1'),
(62, 'VELAS Y VELONES', 2, 0, 1000, 7, '50dc8338-5a24-49e6-db6e-4d9bf0d56f07'),
(65, 'ARTICULOS DEL HOGAR', 2, 0, 1000, 7, '0f62e56d-e2ce-5ea8-dd47-078686b9bdd2'),
(66, 'JUEGOS Y JUGUETES', 2, 0, 1000, 7, 'f510c6de-3a9d-afd7-c9fb-52d2d36055cd'),
(78, 'HOGAR', 2, 0, 1000, 7, 'e7156a27-47f2-4e3b-8ec3-dd0ee94bfde6'),
(82, 'ARTICULOS PARA EL HOGAR', 2, 0, 1000, 7, '2079aa30-6f21-feb9-8b99-bd1021fc723b'),
(83, 'ARTICULOS DEPORTIVOS', 2, 0, 1000, 7, '9d1edfa3-9b39-660e-c337-0711955e8c62'),
(84, 'ARTICULOS DE COCINA', 2, 0, 1000, 7, '8baf9cfc-90c5-95e2-8d9f-c3b540416bb7'),
(85, 'EMBUTIDOS', 1, 0, 1000, 7, '01b16fee-0fec-3847-adb0-40499a853345'),
(86, 'PASTA DE TOMATE', 1, 0, 1000, 7, 'bd3cff3a-bd4b-00cc-f3ee-f6fac098817f'),
(88, 'HUEVOS DE GALLINA', 1, 0, 1000, 7, '2cabfca6-ab07-e1b5-8684-36c09442f039'),
(91, 'PRODUCTOS DE MADERA', 2, 0, 1000, 7, 'abbcb427-31e0-7ecd-77da-1a1504bed0ea'),
(93, 'ACEITES COMESTIBLES', 1, 0, 1000, 7, '44baa18c-f1fb-aa47-f6ff-57f15187b6da'),
(94, 'GRASAS SOLIDAS COMESTIBLES', 1, 0, 1000, 7, 'da2bb04e-1029-dab2-e081-6fe7ba19fee7'),
(95, 'SNACKS', 1, 0, 1000, 7, 'f42f0244-15c9-83c0-91fd-ef3d4bdc27da'),
(96, 'MEZCLA PARA PREPARAR SALSA', 1, 0, 1000, 7, 'c1ec0f84-6a39-db0d-fe94-c9c4caa40285'),
(100, 'MEZCLA  PARA  PREPARAR  ALIMENTOS', 1, 0, 1000, 7, '0c2fc1f6-11d5-70e0-a471-38b9cd7b08af'),
(106, 'ACCESORIOS DEL CABELLO', 2, 0, 1000, 7, '2b900436-58e8-dc96-f0bb-515c61cc5dfe'),
(107, 'LEVADURAS', 1, 0, 1000, 7, '38a89175-3b02-59b7-4f91-b0c56dea55ca'),
(108, 'CONCENTRADO', 1, 0, 1000, 7, '8d4860e8-09a1-56d3-9794-087cd82ed55d'),
(110, 'JUEGOS DE MESA', 2, 0, 1000, 7, '858b91ed-b4f3-2fad-43cb-1e5a77b37ecd'),
(111, 'BICICLETAS, MOTOS Y TRICICLOS', 2, 0, 1000, 7, 'f630e422-3f17-998b-ac90-fe35088b9084'),
(114, 'ARTICULOS Y UTILES  ESCOLARES', 2, 0, 1000, 7, '8b2211ce-26c7-1dc1-3f3f-0ff2efce18fa'),
(115, 'PRENDAS DE VESTIR', 2, 0, 1000, 7, '144997dd-da53-8bea-6afb-fe4a638af3b0'),
(117, 'CREMAS Y LIQUIDOS PARA CALZADOS', 2, 0, 1000, 7, 'c87cc305-ba9d-82e2-65b4-f9cb14e097e3'),
(120, 'ALUMINIO', 2, 0, 1000, 7, '634d5587-9ed5-4173-d7ed-d7764da38e28'),
(121, 'VINOS', 1, 0, 1000, 7, 'f2b81e07-44d6-0915-31af-e2909022efc1'),
(122, 'CUIDADO DEL CABELLO', 2, 0, 1000, 7, '6d75eb4d-6164-9488-0255-3496d030a106'),
(124, 'CALZADO', 2, 0, 1000, 7, 'f1804c58-e34f-1760-2ee1-59d3fe25097f'),
(127, 'LINEA  MARRON', 2, 0, 1000, 7, 'ee3e66dc-bc7e-08c0-6024-2f57b2b126e7'),
(129, 'COLCHONES Y ALMOHADAS', 2, 0, 1000, 7, '32d9a258-5feb-c678-9191-7ad50c05c982'),
(130, 'MERCERIA', 2, 0, 1000, 7, '5abb66bb-88bf-ccd6-e467-bff656646da6'),
(132, 'UTENSILIOS DE COCINA', 2, 0, 1000, 7, '2ab04788-097e-d5f2-df7b-4ba80063462a'),
(133, 'ARTICULOS PARA BEBES', 2, 0, 1000, 7, '3d3ec64e-73fc-37f6-3bd6-349f7b1e2093'),
(134, 'CARNE DE SOYA', 1, 0, 1000, 7, 'a13589f9-9a72-9e58-376b-edf7fe2de7d6'),
(135, 'FRUVERH', 1, 0, 1000, 7, 'f771d6f9-7c33-9a2c-46b6-0e34377b0c51'),
(136, 'ALIMENTOS VARIOS', 1, 0, 1000, 7, 'e33d4438-9090-8d72-bbb3-dff1a561374c'),
(137, 'CAUCHOS Y TRIPAS', 2, 0, 1000, 7, '12258dab-1694-f2ae-5a3c-236993703c93'),
(138, 'COMPLEMENTO VITAMINICO', 1, 0, 1000, 7, '66fbdf74-397c-fd1d-eca0-80498351515f'),
(139, 'MEDICAMENTOS', 2, 0, 1000, 7, '7ce8a88e-7d64-20b0-9d78-faf85f4b8d97'),
(140, 'LINEA BLANCA', 2, 0, 1000, 7, 'b89e68b9-92a7-7de2-5716-74b66bfbaa27'),
(141, 'ARTICULOS PARA CHARCUTERIA', 2, 0, 1000, 7, 'f747490f-4f2b-b63c-b48b-77091ae8dc5f'),
(142, 'CRISTALERIA Y VAJILLA', 2, 0, 1000, 7, '6e422dc1-aeb5-6430-b668-26fe5e96dc0e'),
(143, 'OTROS', 2, 0, 1000, 7, '12035002-5a55-3d8b-9b69-2d6f27257297'),
(144, 'ROPA DE DAMA', 2, 0, 1000, 7, 'd979e1c7-8ac9-991a-9452-363cc04e4f04'),
(145, 'HOGAR Y MUEBLES', 2, 0, 1000, 7, '21005bb0-0594-952d-1dbb-9c347c8aa9a8'),
(146, 'ROPA DE CABALLERO', 2, 0, 1000, 7, '90f32bac-dd94-5680-33e4-89493991267f'),
(147, 'BOLSOS Y CARTERAS', 2, 0, 1000, 7, '4e7e33b1-5c99-d023-80db-de5e26364774'),
(149, 'BILLETERAS Y MONEDEROS', 2, 0, 1000, 7, '96aa419f-3a0a-b41d-71e7-7f8124839096'),
(150, 'CALZADOS', 2, 0, 1000, 7, '661aee70-6da1-8506-9aa1-cfcf700b482d'),
(153, 'ARTICULOS DE PAPELERIA', 2, 0, 1000, 7, 'd7d8c31d-7cc2-a674-4c65-3e524833d2fc'),
(154, 'ROPA DE NINOS', 2, 0, 1000, 7, '484f67f9-c870-78ef-dac2-926049d727c7'),
(155, 'REPUESTOS PARA CARROS', 2, 0, 1000, 7, '4c8ba618-4d82-e78c-ebb2-283cd629e7ec'),
(158, 'ROPA DE NINA', 2, 0, 1000, 7, '759924bf-e0f5-51fd-1dc4-dc5705070341'),
(161, 'PASTA ALIMENTICIAS RESTRINGIDAS', 1, 1, 3, 7, '285d2e3c-df25-a1b5-9b21-262a1b14a99b'),
(163, 'GRASAS SOLIDAS COMESTIBLES RESTRINGIDAS', 1, 0, 1000, 7, 'b741fbd4-eba3-214b-c42f-03364c1db02b'),
(165, 'MASA PREPARADA DE HARINA DE TRIGO', 1, 0, 1000, 7, '481ac04e-d6f5-dcd2-a964-1090f7c77cee'),
(166, 'COMEDOR', 1, 0, 1000, 7, '816a5c77-0f89-665c-1e54-d4b79bb710e8'),
(167, 'COMBOS AHORRO FAMILIAR, HOGAR Y PERSONAL', 2, 0, 1000, 7, '86c7b46e-83ae-9c12-7b89-05486ac1c74c'),
(168, 'COMBOS VARIADOS', 1, 0, 1000, 7, 'a5d34a7b-8700-26e3-5340-c363e0e5683f'),
(169, 'TIENDA VIRTUAL', 1, 0, 1000, 7, 'cac5bba4-92a8-502c-975b-4ea020a55ac0'),
(170, 'CEBADA', 1, 0, 1000, 7, 'f7f1d6c4-ebca-1c28-3211-10f1e0822629'),
(171, 'ALPISTE', 1, 0, 1000, 7, '37334c8a-ad1b-2ec1-b167-d2c45dba4d72'),
(172, 'SERVICIOS', 2, 0, 1000, 7, 'd1cd0a60-5cfa-6b16-c5b9-4b4eb5650fd5'),
(173, 'PILAS', 2, 0, 1000, 7, '6fc6d646-10c2-4888-506b-19657d51e27d');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestos_islr`
--

DROP TABLE IF EXISTS `impuestos_islr`;
CREATE TABLE IF NOT EXISTS `impuestos_islr` (
  `cod_impuesto_islr` int(16) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `imponibleresidente` smallint(16) NOT NULL,
  `aplicaresidente` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `aplicanoresidente` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `imponiblenoresidente` int(16) NOT NULL,
  `alicuotanaturalde` decimal(5,2) NOT NULL,
  `sustraccionnaturalde` decimal(7,2) DEFAULT NULL,
  `pagomayoranaturalde` decimal(9,2) DEFAULT NULL,
  `alicuotanaturalnode` decimal(5,2) NOT NULL,
  `sustraccionnaturalnode` decimal(7,2) NOT NULL,
  `pagomayoranaturalnode` decimal(9,2) NOT NULL,
  `alicuotanaturalno` decimal(5,2) NOT NULL,
  `alicuotajuridica` decimal(5,2) NOT NULL,
  `sustraccionjuridica` decimal(9,2) DEFAULT NULL,
  `pagomayorajuridica` decimal(9,2) DEFAULT NULL,
  `alicuotajuridicano` decimal(5,2) NOT NULL,
  `retencionacumuladanaturalno` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `retencionacumuladajuridicano` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `usuario_creacion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cod_impuesto_islr`)
) ENGINE=InnoDB AUTO_INCREMENT=890 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `impuestos_islr`
--

INSERT INTO `impuestos_islr` (`cod_impuesto_islr`, `descripcion`, `imponibleresidente`, `aplicaresidente`, `aplicanoresidente`, `imponiblenoresidente`, `alicuotanaturalde`, `sustraccionnaturalde`, `pagomayoranaturalde`, `alicuotanaturalnode`, `sustraccionnaturalnode`, `pagomayoranaturalnode`, `alicuotanaturalno`, `alicuotajuridica`, `sustraccionjuridica`, `pagomayorajuridica`, `alicuotajuridicano`, `retencionacumuladanaturalno`, `retencionacumuladajuridicano`, `usuario_creacion`, `fecha_creacion`) VALUES
(1, 'Honorarios Profesionales', 100, 'S', 'N', 90, '3.00', '162.50', '200.00', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '0.00', '34.00', 'S', 'N', '', '2010-07-13 15:20:14'),
(2, 'Pago a Contratista y Subcontratista', 100, 'S', 'S', 100, '1.00', '54.17', '4583.33', '0.00', '0.00', '0.00', '34.00', '2.00', '0.00', '0.00', '34.00', 'N', '', '', '0000-00-00 00:00:00'),
(3, 'Arrendamientos Inmuebles', 100, 'S', 'S', 100, '3.00', '115.00', '3833.00', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '25.00', '34.00', 'S', '', '', '0000-00-00 00:00:00'),
(4, 'Arrendamiento Bienes Muebles', 100, 'S', 'S', 100, '3.00', '115.00', '3833.00', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '25.00', '5.00', 'N', '', '', '0000-00-00 00:00:00'),
(5, 'Fletes a Empresas Nacionales', 100, 'S', 'N', 0, '1.00', '38.00', '3833.00', '0.00', '0.00', '0.00', '1.00', '3.00', '0.00', '25.00', '0.00', '\r', '', '', '0000-00-00 00:00:00'),
(6, 'Publicidad y Propaganda', 100, 'S', 'S', 100, '3.00', '115.00', '3833.00', '0.00', '0.00', '0.00', '0.00', '5.00', '0.00', '25.00', '5.00', 'N', '', '', '0000-00-00 00:00:00'),
(7, 'Fletes Exterior a Emp. Transp. Int.', 0, 'N', 'S', 50, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'S', '', '', '0000-00-00 00:00:00'),
(8, 'Fletes Nacionales  Emp. Transp. Int', 0, 'N', 'S', 90, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 'S', '', '', '0000-00-00 00:00:00'),
(9, 'ExhibiciÃƒÆ’Ã‚Â³n de PelÃƒÆ’Ã‚Â­culas', 0, 'N', 'S', 25, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '34.00', '0.00', '0.00', '0.00', '25.00', 'S', '', '', '0000-00-00 00:00:00'),
(10, 'Regalias o AnÃƒÆ’Ã‚Â¡logas', 0, 'N', 'S', 90, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '34.00', '0.00', '0.00', '0.00', '34.00', 'S', '', '', '0000-00-00 00:00:00'),
(11, 'Asistencia TÃƒÆ’Ã‚Â©cnica', 0, 'N', 'S', 30, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '34.00', '0.00', '0.00', '0.00', '34.00', 'S', '', '', '0000-00-00 00:00:00'),
(12, 'Servicios TecnolÃƒÆ’Ã‚Â³gicos', 0, 'N', 'S', 50, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '34.00', '0.00', '0.00', '0.00', '34.00', 'S', '', '', '0000-00-00 00:00:00'),
(13, 'Primas de Seg. y Reaseg. a Emp. Ext', 0, 'N', 'S', 30, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '10.00', 'N', '', '', '0000-00-00 00:00:00'),
(14, 'Ganacias P/Juegos y Apuestas', 100, 'S', 'S', 100, '34.00', '0.00', '0.00', '0.00', '0.00', '0.00', '34.00', '34.00', '0.00', '0.00', '34.00', 'N', '', '', '0000-00-00 00:00:00'),
(15, 'Premios de Loterias y de Hipodromos', 100, 'S', 'S', 100, '16.00', '0.00', '0.00', '0.00', '0.00', '0.00', '16.00', '16.00', '0.00', '0.00', '16.00', 'N', '', '', '0000-00-00 00:00:00'),
(16, 'Premios Propietarios Anima. Carrera', 100, 'S', 'S', 100, '3.00', '48.50', '1616.67', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '25.00', '5.00', 'N', '', '', '0000-00-00 00:00:00'),
(17, 'Comisiones por Venta de Inmuebles', 100, 'S', 'S', 100, '3.00', '48.50', '1616.67', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '25.00', '5.00', 'N', '', '', '0000-00-00 00:00:00'),
(20, 'Pago Emp.Emisoras de Tarj. de CrÃƒÆ’Ã‚Â©d', 100, 'S', 'S', 100, '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '0.00', '5.00', '', '', '', '0000-00-00 00:00:00'),
(22, 'Pago de Empresas de Seg. a Agentes', 100, 'S', 'N', 0, '3.00', '48.50', '1616.67', '0.00', '0.00', '0.00', '0.00', '5.00', '0.00', '25.00', '0.00', '\r', '', '', '0000-00-00 00:00:00'),
(23, 'Pago a Emp. de Seg. para rep. y Ser', 100, 'S', 'N', 0, '3.00', '48.50', '1616.67', '0.00', '0.00', '0.00', '0.00', '5.00', '0.00', '25.00', '0.00', '\r', '', '', '0000-00-00 00:00:00'),
(24, 'Adquisicion de Fondos de Comercio', 100, 'S', 'S', 100, '3.00', '48.50', '1616.67', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '25.00', '5.00', 'N', '', '', '0000-00-00 00:00:00'),
(26, 'Venta de Acciones en la Bolsa de V.', 100, 'S', 'S', 100, '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '1.00', '1.00', '0.00', '0.00', '1.00', 'N', '', '', '0000-00-00 00:00:00'),
(27, 'Venta de Acciones o Cuotas de Part.', 100, 'S', 'S', 100, '3.00', '48.50', '1616.67', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '25.00', '5.00', '\r', '', '', '0000-00-00 00:00:00'),
(28, 'Viaticos / Ayudas / Donaciones', 100, '', '', 100, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '\r', '', '', '0000-00-00 00:00:00'),
(29, 'Venta de Gasolina', 100, 'S', '', 100, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '1.00', '0.00', '25.00', '1.00', '\r', '', '', '0000-00-00 00:00:00'),
(30, 'Publicidad Radial', 100, 'S', 'S', 100, '3.00', '48.50', '1616.67', '0.00', '0.00', '0.00', '0.00', '3.00', '0.00', '25.00', '5.00', 'S', '', '', '0000-00-00 00:00:00'),
(32, 'Int. S/Prestamos a Inst. Financiera', 0, 'N', 'S', 100, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '4.95', '\r', '', '', '0000-00-00 00:00:00'),
(33, 'Comisiones Mercantiles', 100, 'S', 'S', 100, '3.00', '48.50', '1616.67', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '25.00', '5.00', '\r', '', '', '0000-00-00 00:00:00'),
(34, 'Intereses S/Prestamos', 100, 'S', 'S', 95, '3.00', '48.50', '1616.67', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '25.00', '34.00', 'S', '', '', '0000-00-00 00:00:00'),
(35, 'Agencias de Noticias Internacionale', 0, 'N', 'S', 90, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '15.00', 'N', '', '', '0000-00-00 00:00:00'),
(36, 'Materiales de construccion', 0, 'S', '', 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '\r', '', '', '0000-00-00 00:00:00'),
(37, 'Honorarios Profesionales (S.A.)', 100, 'S', 'S', 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '3.00', '115.00', '3833.00', '8.00', '\r', '', '', '0000-00-00 00:00:00'),
(887, 'Retencion', 0, 'S', 'N', 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '\r', '', '', '0000-00-00 00:00:00'),
(888, 'Exento', 100, '', '', 100, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '\r', '', '', '0000-00-00 00:00:00'),
(889, 'Pago a Contratista y Subcontratista (5%)', 100, 'S', 'S', 100, '1.00', '54.17', '4583.33', '0.00', '0.00', '0.00', '34.00', '5.00', '0.00', '0.00', '34.00', 'N', '', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_ica`
--

DROP TABLE IF EXISTS `impuesto_ica`;
CREATE TABLE IF NOT EXISTS `impuesto_ica` (
  `cod_impuesto_ica` int(11) NOT NULL AUTO_INCREMENT,
  `actividad` varchar(30) NOT NULL,
  `agrupacion` int(11) NOT NULL,
  `cod_actividad_ciu` int(11) NOT NULL,
  `tarifa` decimal(5,2) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(100) NOT NULL,
  PRIMARY KEY (`cod_impuesto_ica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto_iva`
--

DROP TABLE IF EXISTS `impuesto_iva`;
CREATE TABLE IF NOT EXISTS `impuesto_iva` (
  `cod_impuesto_iva` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(80) NOT NULL,
  `iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `porcentaje` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(100) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cod_impuesto_iva`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `impuesto_iva`
--

INSERT INTO `impuesto_iva` (`cod_impuesto_iva`, `descripcion`, `iva`, `porcentaje`, `usuario_creacion`, `fecha_creacion`) VALUES
(4, 'IVA 1.6% Servicios de Vigilancia', '1.60', '50.00', 'asys', '2010-07-13 16:53:08'),
(5, 'IVA 3%', '3.00', '0.00', 'asys', '2010-07-13 16:53:34'),
(6, 'IVA 5% Arrendamientos', '5.00', '0.00', 'asys', '2010-07-13 16:55:18'),
(7, 'IVA 10% Servicios Profesionales', '10.00', '0.00', 'asys', '2010-07-13 16:58:30'),
(8, 'IVA 16% IVA General', '16.00', '0.00', 'asys', '2010-07-13 16:59:29'),
(9, 'IVA %20 ', '20.00', '0.00', 'asys', '2010-07-13 18:58:51'),
(10, 'IVA 25% Vehiculos', '25.00', '0.00', 'asys', '2010-07-13 18:59:12'),
(11, 'IVA 35% Licores y Tabaco', '35.00', '0.00', 'asys', '2010-07-13 18:59:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos_detallados`
--

DROP TABLE IF EXISTS `ingresos_detallados`;
CREATE TABLE IF NOT EXISTS `ingresos_detallados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_maestro` int(11) NOT NULL,
  `id_tipo_rubro` int(11) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `id_arqueo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos_detalles`
--

DROP TABLE IF EXISTS `ingresos_detalles`;
CREATE TABLE IF NOT EXISTS `ingresos_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_comprobante_detalle` int(11) NOT NULL,
  `tipo_ingreso` tinyint(4) NOT NULL,
  `monto` decimal(11,2) NOT NULL,
  `id_comprobante` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos_xenviar`
--

DROP TABLE IF EXISTS `ingresos_xenviar`;
CREATE TABLE IF NOT EXISTS `ingresos_xenviar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_deposito` varchar(25) NOT NULL,
  `fecha_deposito` date NOT NULL,
  `monto_deposito` decimal(10,2) NOT NULL,
  `cuenta_banco` varchar(60) NOT NULL,
  `usuario_creacion` varchar(100) NOT NULL,
  `nro_cataporte` varchar(100) NOT NULL,
  `fecha_cataporte` date NOT NULL,
  `fecha_retiro` date NOT NULL,
  `usuario_creacion_cataporte` varchar(255) NOT NULL,
  `nro_cataporte_usuario` varchar(25) DEFAULT NULL,
  `monto_usuario_cataporte` double DEFAULT NULL,
  `usuario_correccion` varchar(255) DEFAULT NULL,
  `fecha_correccion` datetime DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instrumentopago_formapago`
--

DROP TABLE IF EXISTS `instrumentopago_formapago`;
CREATE TABLE IF NOT EXISTS `instrumentopago_formapago` (
  `cod_formapago` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(80) NOT NULL,
  `cod_funcioninstrumento` int(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(80) NOT NULL,
  PRIMARY KEY (`cod_formapago`),
  KEY `new_fk_constraint` (`cod_funcioninstrumento`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `instrumentopago_formapago`
--

INSERT INTO `instrumentopago_formapago` (`cod_formapago`, `descripcion`, `cod_funcioninstrumento`, `fecha_creacion`, `usuario_creacion`) VALUES
(1, 'Master Card', 1, '2009-12-27 00:31:00', 'asys'),
(2, 'American Express', 1, '2009-12-27 00:33:03', 'asys'),
(3, 'Visa', 1, '2009-12-27 00:52:47', 'asys'),
(4, 'Cesta Ticket', 5, '2010-01-18 09:44:09', 'asys'),
(5, 'Transferencias', 5, '2010-01-18 09:44:21', 'asys'),
(6, 'Deposito', 4, '2010-01-18 09:44:33', 'asys'),
(7, 'Debito', 2, '2010-01-18 09:44:47', 'asys');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instrumentopago_funcioninstrumento`
--

DROP TABLE IF EXISTS `instrumentopago_funcioninstrumento`;
CREATE TABLE IF NOT EXISTS `instrumentopago_funcioninstrumento` (
  `cod_funcioninstrumento` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(80) NOT NULL,
  PRIMARY KEY (`cod_funcioninstrumento`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `instrumentopago_funcioninstrumento`
--

INSERT INTO `instrumentopago_funcioninstrumento` (`cod_funcioninstrumento`, `descripcion`) VALUES
(1, 'T. Credito'),
(2, 'T. Debito'),
(3, 'Cheque'),
(4, 'Deposito'),
(5, 'Otros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `id_item` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_item` varchar(20) NOT NULL,
  `codigo_barras` varchar(25) NOT NULL,
  `codigo_cpe` varchar(50) NOT NULL COMMENT 'Codigo CPE del Producto',
  `descripcion1` varchar(50) NOT NULL,
  `descripcion2` varchar(50) DEFAULT NULL,
  `descripcion3` varchar(50) DEFAULT NULL,
  `referencia` varchar(50) DEFAULT NULL,
  `presentacion` varchar(100) NOT NULL COMMENT 'presentacion del producto',
  `talla` varchar(50) NOT NULL COMMENT 'talla del producto',
  `codigo_fabricante` varchar(50) DEFAULT NULL,
  `unidad_empaque` varchar(40) DEFAULT NULL,
  `cantidad` int(32) NOT NULL DEFAULT '0',
  `seriales` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Â¿Producto con seriales?',
  `garantia` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Â¿Seriales con garantia?',
  `tipo_item` varchar(50) DEFAULT NULL COMMENT 'items(Producto): ''Nacional'',''Importado''',
  `factor_cambio` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Si o solo si Tipo de Producto = Importado',
  `ultimo_costo` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Si o solo si Tipo de Producto = Importado',
  `precio_x_escala` tinyint(1) NOT NULL DEFAULT '0',
  `comision_x_item` tinyint(1) NOT NULL DEFAULT '0',
  `tipo_comision_x_item` varchar(50) DEFAULT NULL,
  `desdeA1` int(32) NOT NULL DEFAULT '0' COMMENT 'Sin son precios por Escala',
  `desdeA2` int(32) NOT NULL DEFAULT '0' COMMENT 'Sin son precios por Escala',
  `desdeB1` int(32) NOT NULL DEFAULT '0' COMMENT 'Sin son precios por Escala',
  `comisiones1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `comisiones2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `comisiones3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `desdeB2` int(32) NOT NULL DEFAULT '0' COMMENT 'Sin son precios por Escala',
  `desdeC1` int(32) NOT NULL DEFAULT '0' COMMENT 'Sin son precios por Escala',
  `desdeC2` int(32) NOT NULL DEFAULT '0' COMMENT 'Sin son precios por Escala',
  `precio1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `utilidad1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `coniva1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `utilidad2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `coniva2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `utilidad3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `coniva3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio_referencial1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Si o solo si Tipo de Producto = Importado',
  `precio_referencial2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Si o solo si Tipo de Producto = Importado',
  `precio_referencial3` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Si o solo si Tipo de Producto = Importado',
  `costo_actual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costo_promedio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costo_anterior` decimal(10,2) NOT NULL DEFAULT '0.00',
  `existencia_total` int(32) NOT NULL DEFAULT '0',
  `existencia_min` int(32) NOT NULL DEFAULT '0',
  `existencia_max` int(32) NOT NULL DEFAULT '0',
  `monto_exento` tinyint(1) NOT NULL DEFAULT '0',
  `iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ubicacion1` varchar(50) DEFAULT NULL,
  `ubicacion2` varchar(50) DEFAULT NULL,
  `ubicacion3` varchar(50) DEFAULT NULL,
  `ubicacion4` varchar(50) DEFAULT NULL,
  `ubicacion5` varchar(50) DEFAULT NULL,
  `cod_departamento` int(32) NOT NULL DEFAULT '0',
  `cod_siga` varchar(10) NOT NULL COMMENT 'Tendra los valores B-50 para los alimentos y B-99 para los no alimentos',
  `cod_grupo` int(32) NOT NULL DEFAULT '0',
  `sub_categoria` int(11) NOT NULL COMMENT 'Codigo de la Subcategoria',
  `id_marca` int(11) NOT NULL COMMENT 'Id Marca del Producto',
  `cod_linea` int(32) NOT NULL DEFAULT '0',
  `estatus` varchar(1) NOT NULL,
  `usuario_creacion` varchar(60) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `cod_item_forma` int(32) NOT NULL,
  `tipo_prod` tinyint(1) NOT NULL DEFAULT '2' COMMENT 'Activo Fijo=0,Consumo=1,Venta=2,Otro=3',
  `cuenta_contable1` varchar(25) NOT NULL,
  `cuenta_contable2` varchar(25) NOT NULL,
  `codigo_cuenta` varchar(15) NOT NULL,
  `serial1` varchar(25) NOT NULL,
  `foto` varchar(60) NOT NULL,
  `cantidad_bulto` decimal(9,2) NOT NULL,
  `kilos_bulto` decimal(9,2) NOT NULL,
  `proveedor` int(4) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `origen` varchar(45) NOT NULL,
  `costo_cif` decimal(9,2) NOT NULL,
  `costo_origen` decimal(9,2) NOT NULL,
  `temporada` varchar(45) NOT NULL,
  `mate_compo_clase` varchar(45) NOT NULL,
  `punto_pedido` decimal(9,2) NOT NULL,
  `tejido` varchar(45) NOT NULL,
  `reg_sanit` varchar(45) NOT NULL,
  `cod_barra_bulto` varchar(45) NOT NULL,
  `observacion` text NOT NULL,
  `foto1` char(10) NOT NULL,
  `foto2` char(10) NOT NULL,
  `foto3` char(10) NOT NULL,
  `foto4` char(10) NOT NULL,
  `cont_licen_nro` varchar(45) NOT NULL,
  `precio_cont` decimal(9,2) NOT NULL,
  `aprob_arte` char(10) NOT NULL,
  `propiedad` char(10) NOT NULL,
  `regulado` tinyint(4) NOT NULL,
  `cestack_basica` tinyint(4) NOT NULL,
  `bcv` tinyint(4) NOT NULL,
  `clap` tinyint(4) NOT NULL,
  `unidadxpeso` varchar(10) NOT NULL DEFAULT '2',
  `pesoxunidad` decimal(11,2) NOT NULL COMMENT 'Peso de la Unidad de Venta',
  `unidad_venta` varchar(20) NOT NULL COMMENT 'Unidad de Venta',
  `itempos` varchar(255) NOT NULL,
  `producto_vencimiento` varchar(2) NOT NULL COMMENT 'Indica si el producto posee fecha de vencimiento',
  `tipo_almacenamiento` varchar(255) NOT NULL COMMENT 'Tipo de Alamcenamiento del Producto',
  `sae` varchar(1) NOT NULL,
  `central` varchar(11) NOT NULL DEFAULT 'C' COMMENT 'C=central, R=regional',
  `nacional` varchar(1) NOT NULL DEFAULT 'N' COMMENT 'N=nacional, I=importado',
  `precio_bulto` decimal(10,2) NOT NULL,
  `impresion` tinyint(4) DEFAULT NULL COMMENT 'impresion_entrega_producto',
  `id_arancel` int(255) NOT NULL,
  `produccion` int(11) NOT NULL,
  `unidad_paleta` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_item`),
  UNIQUE KEY `codigo_barras` (`codigo_barras`),
  KEY `cod_item_forma` (`cod_item_forma`),
  KEY `FK_item_2` (`usuario_creacion`),
  KEY `fk_cod_departamento2` (`cod_departamento`),
  KEY `fk_cod_grupo2` (`cod_grupo`),
  KEY `fk_cod_linea2` (`cod_linea`),
  KEY `id_marca` (`id_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=9405 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `item`
--

INSERT INTO `item` (`id_item`, `cod_item`, `codigo_barras`, `codigo_cpe`, `descripcion1`, `descripcion2`, `descripcion3`, `referencia`, `presentacion`, `talla`, `codigo_fabricante`, `unidad_empaque`, `cantidad`, `seriales`, `garantia`, `tipo_item`, `factor_cambio`, `ultimo_costo`, `precio_x_escala`, `comision_x_item`, `tipo_comision_x_item`, `desdeA1`, `desdeA2`, `desdeB1`, `comisiones1`, `comisiones2`, `comisiones3`, `desdeB2`, `desdeC1`, `desdeC2`, `precio1`, `utilidad1`, `coniva1`, `precio2`, `utilidad2`, `coniva2`, `precio3`, `utilidad3`, `coniva3`, `precio_referencial1`, `precio_referencial2`, `precio_referencial3`, `costo_actual`, `costo_promedio`, `costo_anterior`, `existencia_total`, `existencia_min`, `existencia_max`, `monto_exento`, `iva`, `ubicacion1`, `ubicacion2`, `ubicacion3`, `ubicacion4`, `ubicacion5`, `cod_departamento`, `cod_siga`, `cod_grupo`, `sub_categoria`, `id_marca`, `cod_linea`, `estatus`, `usuario_creacion`, `fecha_creacion`, `cod_item_forma`, `tipo_prod`, `cuenta_contable1`, `cuenta_contable2`, `codigo_cuenta`, `serial1`, `foto`, `cantidad_bulto`, `kilos_bulto`, `proveedor`, `fecha_ingreso`, `origen`, `costo_cif`, `costo_origen`, `temporada`, `mate_compo_clase`, `punto_pedido`, `tejido`, `reg_sanit`, `cod_barra_bulto`, `observacion`, `foto1`, `foto2`, `foto3`, `foto4`, `cont_licen_nro`, `precio_cont`, `aprob_arte`, `propiedad`, `regulado`, `cestack_basica`, `bcv`, `clap`, `unidadxpeso`, `pesoxunidad`, `unidad_venta`, `itempos`, `producto_vencimiento`, `tipo_almacenamiento`, `sae`, `central`, `nacional`, `precio_bulto`, `impresion`, `id_arancel`, `produccion`, `unidad_paleta`) VALUES
(9399, 'P12636', '01122', 'CPE123', 'Descripcion', '', '', 'P12636', '', '', '', '1', 0, 0, 0, '', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, '0.00', '0.00', '0.00', 0, 0, 0, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, '12.00', NULL, NULL, NULL, NULL, NULL, 1, 'B-50', 1, 81, 1571, 0, 'A', 'asys', '2017-10-04 11:23:52', 1, 0, '', '', '', '', '', '5.00', '0.00', 0, '0000-00-00', '', '0.00', '0.00', '', '', '0.00', '', 'REG123', '98764654', '    \r\n  \r\n  \r\n  ', '', '', '', '', '', '0.00', '', '', 1, 0, 0, 0, '1', '50.00', '1', 'd8e4acc6-0c0a-d136-c56a-26678da1deb4', 'Si', 'SECO', '', 'C', 'N', '50000.00', 0, 0, 1, '55.00'),
(9400, 'P12637', '654321', 'CPE3123', 'Descripcion 2', '', '', 'P12637', '', '', '', '1', 0, 0, 0, '', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, '0.00', '0.00', '0.00', 0, 0, 0, '500.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, '12.00', NULL, NULL, NULL, NULL, NULL, 1, 'B-50', 2, 379, 1578, 0, 'A', 'asys', '2017-10-04 12:59:10', 1, 0, '', '', '', '', '', '50.00', '0.00', 0, '0000-00-00', '', '0.00', '0.00', '', '', '0.00', '', '123453', '654321', '\r\n  ', '', '', '', '', '', '0.00', '', '', 0, 0, 0, 0, '1', '12.00', '1', 'b1257d88-dc9d-6675-dd03-ed71a5c3d55c', 'Si', 'SECO', '', 'C', 'N', '500.00', NULL, 0, 1, '900.00'),
(9401, 'P12638', '00001111', '00001111', 'ALMUERZO', '', '', 'P12638', '', '', '', '3', 0, 0, 0, '', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, '0.00', '0.00', '0.00', 0, 0, 0, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, '12.00', NULL, NULL, NULL, NULL, NULL, 1, 'B-50', 5, 387, 1, 0, 'A', 'wjimenez', '2017-11-21 17:24:41', 1, 1, '', '', '', '', '', '24.00', '0.00', 0, '0000-00-00', '', '0.00', '0.00', '', '', '0.00', '', '00001111', '00001111', '  \r\n  \r\n  ', '', '', '', '', '', '0.00', '', '', 0, 0, 0, 0, '1', '1.00', '1', 'a098c452-ed57-3a11-6919-9c88845b6c9e', 'No', 'SECO', '', 'C', 'N', '100.00', 0, 0, 1, '1000.00'),
(9402, 'P12639', '0000000001', '0000000001', 'Entrada Paleta', '', '', 'P12639', '', '', '', '3', 0, 0, 0, '', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, '0.00', '0.00', '0.00', 0, 0, 0, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, '12.00', NULL, NULL, NULL, NULL, NULL, 2, 'B-99', 172, 922, 1, 0, 'A', 'wjimenez', '2017-12-17 02:03:19', 1, 3, '', '', '', '', '', '0.00', '0.00', 0, '0000-00-00', '', '0.00', '0.00', '', '', '0.00', '', '0000000001', '', '\r\n  ', '', '', '', '', '', '0.00', '', '', 0, 0, 0, 0, '5', '1.00', '1', 'dc88962b-ccec-d37c-5ee4-f0a8314091e6', 'No', 'SECO', '', 'C', 'N', '0.00', NULL, 0, 0, '1.00'),
(9403, 'P12640', '0000000002', '0000000002', 'Salida Paleta', '', '', 'P12640', '', '', '', '3', 0, 0, 0, '', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, '0.00', '0.00', '0.00', 0, 0, 0, '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, '12.00', NULL, NULL, NULL, NULL, NULL, 2, 'B-99', 172, 922, 1, 0, 'A', 'wjimenez', '2017-12-17 02:14:40', 1, 3, '', '', '', '', '', '0.00', '0.00', 0, '0000-00-00', '', '0.00', '0.00', '', '', '0.00', '', '0000000002', '', '\r\n  ', '', '', '', '', '', '0.00', '', '', 0, 0, 0, 0, '5', '1.00', '2', 'a20cbe6e-6ae5-5a46-3957-00627672280f', 'No', 'SECO', '', 'C', 'N', '0.00', NULL, 0, 0, '1.00'),
(9404, 'P12641', '0000000003', '0000000003', 'Cobro por movimiento', '', '', 'P12641', '', '', '', '3', 0, 0, 0, '', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, '0.00', '0.00', '0.00', 0, 0, 0, '50.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, '12.00', NULL, NULL, NULL, NULL, NULL, 2, 'B-99', 172, 922, 1, 0, 'A', 'wjimenez', '2017-12-17 02:27:22', 1, 3, '', '', '', '', '', '1.00', '0.00', 0, '0000-00-00', '', '0.00', '0.00', '', '', '0.00', '', '0000000003', '', '  \r\n  \r\n  ', '', '', '', '', '', '0.00', '', '', 0, 0, 0, 0, '5', '1.00', '1', '36dc1706-7491-c0d1-d219-f68affe5a2bc', 'No', 'SECO', '', 'C', 'N', '0.00', 0, 0, 0, '1.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_existencia_almacen`
--

DROP TABLE IF EXISTS `item_existencia_almacen`;
CREATE TABLE IF NOT EXISTS `item_existencia_almacen` (
  `cod_item_existencia_almacen` int(32) NOT NULL AUTO_INCREMENT,
  `cod_almacen` int(32) NOT NULL,
  `id_item` int(32) UNSIGNED DEFAULT NULL,
  `cantidad` decimal(11,2) NOT NULL DEFAULT '0.00',
  `peso` decimal(10,2) NOT NULL,
  `id_ubicacion` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `lote` int(11) DEFAULT NULL COMMENT 'lote del item',
  `id_proveedor` int(11) NOT NULL COMMENT 'Existencia del proveedor',
  PRIMARY KEY (`cod_item_existencia_almacen`),
  KEY `FK_item_existencia_almacen_1` (`cod_almacen`),
  KEY `FK_item_existencia_almacen_2` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_existencia_almacen_comedor`
--

DROP TABLE IF EXISTS `item_existencia_almacen_comedor`;
CREATE TABLE IF NOT EXISTS `item_existencia_almacen_comedor` (
  `cod_item_existencia_almacen` int(32) NOT NULL AUTO_INCREMENT,
  `cod_almacen` int(32) NOT NULL,
  `id_item` int(32) UNSIGNED DEFAULT NULL,
  `cantidad` decimal(11,2) NOT NULL DEFAULT '0.00',
  `id_ubicacion` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `lote` int(11) DEFAULT NULL COMMENT 'lote del item',
  `id_proveedor` int(11) NOT NULL COMMENT 'Existencia del proveedor',
  PRIMARY KEY (`cod_item_existencia_almacen`),
  KEY `FK_item_existencia_almacen_1` (`cod_almacen`),
  KEY `FK_item_existencia_almacen_2` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_forma`
--

DROP TABLE IF EXISTS `item_forma`;
CREATE TABLE IF NOT EXISTS `item_forma` (
  `cod_item_forma` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_item_forma`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `item_forma`
--

INSERT INTO `item_forma` (`cod_item_forma`, `descripcion`) VALUES
(1, 'Productos'),
(2, 'Servicios'),
(3, 'Boleto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_precio`
--

DROP TABLE IF EXISTS `item_precio`;
CREATE TABLE IF NOT EXISTS `item_precio` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_region` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_producto` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tipo_precio` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1=red comercial 2=pae 3=pdvalito',
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_precompromiso`
--

DROP TABLE IF EXISTS `item_precompromiso`;
CREATE TABLE IF NOT EXISTS `item_precompromiso` (
  `id_item_precomiso` int(32) NOT NULL AUTO_INCREMENT,
  `id_item` int(32) NOT NULL,
  `cod_item_precompromiso` int(32) NOT NULL,
  `cantidad` int(32) NOT NULL,
  `almacen` int(32) NOT NULL,
  `usuario_creacion` varchar(40) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idSessionActualphp` varchar(200) NOT NULL,
  `ubicacion` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_item_precomiso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_serial`
--

DROP TABLE IF EXISTS `item_serial`;
CREATE TABLE IF NOT EXISTS `item_serial` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_producto` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `serial` varchar(60) NOT NULL DEFAULT '',
  `estado` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0=inactivo 1=activo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='contiene los seriales de los productos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_serial_comedor`
--

DROP TABLE IF EXISTS `item_serial_comedor`;
CREATE TABLE IF NOT EXISTS `item_serial_comedor` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_producto` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `serial` varchar(60) NOT NULL DEFAULT '',
  `estado` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0=inactivo 1=activo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='contiene los seriales de los productos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_serial_temp`
--

DROP TABLE IF EXISTS `item_serial_temp`;
CREATE TABLE IF NOT EXISTS `item_serial_temp` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_creacion` varchar(255) NOT NULL,
  `idsessionactual` varchar(255) NOT NULL,
  `id_producto` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `serial` varchar(60) NOT NULL DEFAULT '',
  `estado` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0=inactivo 1=activo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='contiene los seriales de los productos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_traslado_temp`
--

DROP TABLE IF EXISTS `item_traslado_temp`;
CREATE TABLE IF NOT EXISTS `item_traslado_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `id_item` varchar(255) NOT NULL COMMENT 'id item',
  `id_sessionactual` varchar(255) NOT NULL COMMENT 'sesion activa del usuario',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla temporal para el traslado de productos ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_variacion_precio`
--

DROP TABLE IF EXISTS `item_variacion_precio`;
CREATE TABLE IF NOT EXISTS `item_variacion_precio` (
  `id_var_precio` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_barra` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `precio_sin_iva` decimal(10,2) NOT NULL,
  `id_var_precio_cab` int(11) NOT NULL,
  `id_estado` int(2) NOT NULL DEFAULT '0',
  `id_motivo` int(11) NOT NULL COMMENT 'motivo de la actualizacion',
  `observacion` varchar(255) COLLATE utf8_spanish_ci NOT NULL COMMENT 'comentarios sobre el motivo de la actualizacion',
  PRIMARY KEY (`id_var_precio`),
  KEY `id_var_precio_cab` (`id_var_precio_cab`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla de variacion de precios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_almacen`
--

DROP TABLE IF EXISTS `kardex_almacen`;
CREATE TABLE IF NOT EXISTS `kardex_almacen` (
  `id_transaccion` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaccion_calidad` int(11) DEFAULT NULL,
  `tipo_movimiento_almacen` int(11) NOT NULL,
  `autorizado_por` varchar(100) NOT NULL,
  `observacion` varchar(200) NOT NULL,
  `fecha` date NOT NULL,
  `usuario_creacion` varchar(100) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` varchar(20) NOT NULL,
  `fecha_ejecucion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_documento` varchar(100) NOT NULL COMMENT 'id Factura y/o Compra',
  `id_proveedor` int(11) NOT NULL COMMENT 'Proveedor Asociado a la entrada',
  `empresa_transporte` varchar(50) NOT NULL,
  `id_conductor` int(11) NOT NULL,
  `placa` varchar(15) NOT NULL,
  `color` varchar(15) DEFAULT NULL,
  `marca` varchar(15) DEFAULT NULL,
  `guia_sunagro` varchar(20) NOT NULL,
  `orden_despacho` varchar(50) NOT NULL COMMENT 'Orden de Despacho Vehicular',
  `almacen_procedencia` varchar(66) NOT NULL COMMENT 'Procedencia de los rubros',
  `almacen_destino` varchar(6) NOT NULL COMMENT 'Destino de los rubros',
  `prescintos` varchar(255) DEFAULT NULL,
  `cod_movimiento` varchar(255) NOT NULL COMMENT 'Codigo del Movimiento',
  `usuario_despacho` varchar(20) NOT NULL,
  `observacion_despacho` varchar(150) NOT NULL,
  `id_cliente` int(11) NOT NULL DEFAULT '0',
  `nro_factura` varchar(32) NOT NULL,
  `usuario_anulacion` varchar(50) NOT NULL,
  `id_seguridad` int(11) NOT NULL,
  `id_aprobado` int(11) NOT NULL,
  `id_receptor` int(11) NOT NULL,
  `id_despachador` int(11) NOT NULL,
  `id_tipo_despacho` int(11) DEFAULT NULL,
  `id_jornada` varchar(40) DEFAULT NULL,
  `referencia_salida` int(11) NOT NULL COMMENT 'cuando se hace transformacion',
  `nro_contenedor` varchar(50) NOT NULL,
  `facturado` int(11) NOT NULL DEFAULT '0' COMMENT '1 si se encuentra facturado',
  `ticket_entrada` varchar(10) NOT NULL,
  PRIMARY KEY (`id_transaccion`,`guia_sunagro`),
  KEY `fk_id_tipo_movimiento_almacen` (`tipo_movimiento_almacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_almacen_comedor`
--

DROP TABLE IF EXISTS `kardex_almacen_comedor`;
CREATE TABLE IF NOT EXISTS `kardex_almacen_comedor` (
  `id_transaccion` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_movimiento_almacen` int(11) NOT NULL,
  `autorizado_por` varchar(100) NOT NULL,
  `observacion` varchar(200) NOT NULL,
  `fecha` date NOT NULL,
  `usuario_creacion` varchar(100) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` varchar(20) NOT NULL,
  `fecha_ejecucion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_documento` varchar(100) NOT NULL COMMENT 'id Factura y/o Compra',
  `id_proveedor` int(11) NOT NULL COMMENT 'Proveedor Asociado a la entrada',
  `empresa_transporte` varchar(50) NOT NULL,
  `id_conductor` int(11) NOT NULL,
  `placa` varchar(15) NOT NULL,
  `color` varchar(15) DEFAULT NULL,
  `marca` varchar(15) DEFAULT NULL,
  `guia_sunagro` varchar(20) NOT NULL,
  `orden_despacho` varchar(50) NOT NULL COMMENT 'Orden de Despacho Vehicular',
  `almacen_procedencia` varchar(66) NOT NULL COMMENT 'Procedencia de los rubros',
  `almacen_destino` varchar(6) NOT NULL COMMENT 'Destino de los rubros',
  `prescintos` varchar(255) DEFAULT NULL,
  `cod_movimiento` varchar(255) NOT NULL COMMENT 'Codigo del Movimiento',
  `usuario_despacho` varchar(20) NOT NULL,
  `observacion_despacho` varchar(150) NOT NULL,
  `id_cliente` int(11) NOT NULL DEFAULT '0',
  `nro_factura` varchar(32) NOT NULL,
  `usuario_anulacion` varchar(50) NOT NULL,
  `id_seguridad` int(11) NOT NULL,
  `id_aprobado` int(11) NOT NULL,
  `id_receptor` int(11) NOT NULL,
  `id_despachador` int(11) NOT NULL,
  `id_tipo_despacho` int(11) DEFAULT NULL,
  `id_jornada` varchar(40) DEFAULT NULL,
  `referencia_salida` int(11) NOT NULL COMMENT 'cuando se hace transformacion',
  `nro_contenedor` varchar(50) NOT NULL,
  `facturado` int(11) NOT NULL DEFAULT '0' COMMENT '1 si se encuentra facturado',
  PRIMARY KEY (`id_transaccion`,`guia_sunagro`),
  KEY `fk_id_tipo_movimiento_almacen` (`tipo_movimiento_almacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_almacen_detalle`
--

DROP TABLE IF EXISTS `kardex_almacen_detalle`;
CREATE TABLE IF NOT EXISTS `kardex_almacen_detalle` (
  `id_transaccion_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaccion` int(11) NOT NULL,
  `id_almacen_entrada` int(11) NOT NULL,
  `id_almacen_salida` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `cantidad` decimal(11,2) NOT NULL,
  `peso` decimal(10,2) NOT NULL,
  `id_ubi_entrada` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_ubi_salida` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `vencimiento` date DEFAULT NULL,
  `elaboracion` date NOT NULL,
  `lote` int(10) UNSIGNED DEFAULT NULL,
  `c_esperada` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `observacion` varchar(55) NOT NULL DEFAULT '',
  `precio` decimal(13,2) NOT NULL,
  `etiqueta` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_transaccion_detalle`),
  KEY `fk_id_transaccion` (`id_transaccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_almacen_detalle_comedor`
--

DROP TABLE IF EXISTS `kardex_almacen_detalle_comedor`;
CREATE TABLE IF NOT EXISTS `kardex_almacen_detalle_comedor` (
  `id_transaccion_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaccion` int(11) NOT NULL,
  `id_almacen_entrada` int(11) NOT NULL,
  `id_almacen_salida` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `cantidad` decimal(11,2) NOT NULL,
  `id_ubi_entrada` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_ubi_salida` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `vencimiento` date DEFAULT NULL,
  `elaboracion` date NOT NULL,
  `lote` int(10) UNSIGNED DEFAULT NULL,
  `c_esperada` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `observacion` varchar(55) NOT NULL DEFAULT '',
  `precio` decimal(13,2) NOT NULL,
  PRIMARY KEY (`id_transaccion_detalle`),
  KEY `fk_id_transaccion` (`id_transaccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_control`
--

DROP TABLE IF EXISTS `kardex_control`;
CREATE TABLE IF NOT EXISTS `kardex_control` (
  `id_kardex` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `kardex_control`
--

INSERT INTO `kardex_control` (`id_kardex`) VALUES
('99');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libroventas_xenviar`
--

DROP TABLE IF EXISTS `libroventas_xenviar`;
CREATE TABLE IF NOT EXISTS `libroventas_xenviar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial_impresora` varchar(60) NOT NULL,
  `numero_z` int(11) NOT NULL,
  `ultima_factura` int(11) NOT NULL,
  `numeros_facturas` int(11) NOT NULL,
  `ultima_nc` int(11) DEFAULT NULL,
  `numeros_ncs` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `monto_bruto` decimal(12,2) NOT NULL,
  `monto_exento` decimal(12,2) NOT NULL,
  `base_imponible` decimal(12,2) NOT NULL,
  `iva` decimal(12,2) NOT NULL,
  `fecha_emision` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_usuario_creacion` varchar(255) NOT NULL,
  `secuencia` int(11) NOT NULL,
  `numero_z_usuario` int(11) NOT NULL,
  `monto_bruto_usuario` decimal(12,2) NOT NULL,
  `monto_exento_usuario` decimal(12,2) NOT NULL,
  `base_imponible_usuario` decimal(12,2) NOT NULL,
  `iva_usuario` decimal(12,2) NOT NULL,
  `money` varchar(255) NOT NULL,
  `tipo_venta` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pyme=0, pos=1',
  PRIMARY KEY (`id`),
  KEY `serial_impresora` (`serial_impresora`),
  KEY `serial_impresora_3` (`serial_impresora`,`ultima_nc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_ventas`
--

DROP TABLE IF EXISTS `libro_ventas`;
CREATE TABLE IF NOT EXISTS `libro_ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial_impresora` varchar(60) NOT NULL,
  `numero_z` int(11) NOT NULL,
  `ultima_factura` int(11) NOT NULL,
  `numeros_facturas` int(11) NOT NULL,
  `ultima_nc` int(11) DEFAULT NULL,
  `numeros_ncs` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `monto_bruto` decimal(12,2) NOT NULL,
  `monto_exento` decimal(12,2) NOT NULL,
  `base_imponible` decimal(12,2) NOT NULL,
  `iva` decimal(12,2) NOT NULL,
  `fecha_emision` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_usuario_creacion` int(11) NOT NULL,
  `secuencia` int(11) NOT NULL,
  `numero_z_usuario` int(11) NOT NULL,
  `monto_bruto_usuario` decimal(12,2) NOT NULL,
  `monto_exento_usuario` decimal(12,2) NOT NULL,
  `base_imponible_usuario` decimal(12,2) NOT NULL,
  `iva_usuario` decimal(12,2) NOT NULL,
  `money` varchar(255) NOT NULL,
  `tipo_venta` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pyme=0, pos=1',
  PRIMARY KEY (`id`),
  KEY `serial_impresora` (`serial_impresora`),
  KEY `serial_impresora_3` (`serial_impresora`,`ultima_nc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea`
--

DROP TABLE IF EXISTS `linea`;
CREATE TABLE IF NOT EXISTS `linea` (
  `cod_linea` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_linea`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `linea`
--

INSERT INTO `linea` (`cod_linea`, `descripcion`) VALUES
(1, 'Linea unica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_impuestos`
--

DROP TABLE IF EXISTS `lista_impuestos`;
CREATE TABLE IF NOT EXISTS `lista_impuestos` (
  `cod_impuesto` int(11) NOT NULL AUTO_INCREMENT,
  `cod_formula` int(11) NOT NULL,
  `cod_entidad` int(11) NOT NULL,
  `cod_tipo_impuesto` int(11) NOT NULL,
  `codificacion_impuesto` varchar(20) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `alicuota` decimal(5,2) NOT NULL,
  `pago_mayor_a` decimal(7,4) NOT NULL,
  `monto_sustraccion` decimal(7,4) NOT NULL,
  `fecha_aplicacion` date NOT NULL,
  `estado` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(100) NOT NULL,
  `siglas` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_impuesto`),
  KEY `fk_cod_formula` (`cod_formula`),
  KEY `fk_cod_entidad` (`cod_entidad`),
  KEY `fk_cod_tipo_impuesto` (`cod_tipo_impuesto`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `lista_impuestos`
--

INSERT INTO `lista_impuestos` (`cod_impuesto`, `cod_formula`, `cod_entidad`, `cod_tipo_impuesto`, `codificacion_impuesto`, `descripcion`, `alicuota`, `pago_mayor_a`, `monto_sustraccion`, `fecha_aplicacion`, `estado`, `fecha_creacion`, `usuario_creacion`, `siglas`) VALUES
(1, 1, 3, 1, '', 'Retencion de IVA  75% Persona Juridica Domiciliada', '75.00', '0.0000', '0.0000', '2010-01-01', 0, '2010-08-27 11:28:37', 'asys', ''),
(2, 2, 3, 1, '', 'Retencion de IVA 100% Persona Juridica Domiciliada', '100.00', '0.0000', '0.0000', '2010-01-01', 0, '2010-08-27 11:30:33', 'asys', ''),
(3, 1, 1, 1, '', 'Retencion de IVA  75% Persona Natural Residente', '75.00', '0.0000', '0.0000', '0000-00-00', 0, '2011-06-28 13:13:43', 'asys', ''),
(4, 4, 1, 1, '', 'Retencion de IVA 100% Persona Natural Residente', '100.00', '0.0000', '0.0000', '0000-00-00', 0, '2011-06-28 13:59:38', 'asys', ''),
(5, 5, 2, 1, '', 'Retencion de IVA 75% Persona Natural No Residente', '75.00', '0.0000', '0.0000', '0000-00-00', 0, '2011-06-28 14:01:25', 'asys', ''),
(6, 6, 2, 1, '', 'Retencion de IVA 100% Persona Natural No Residente', '100.00', '0.0000', '0.0000', '0000-00-00', 0, '2011-06-28 14:02:10', 'asys', ''),
(7, 7, 4, 1, '', 'Retencion de IVA 75% Persona Juridica No Domiciliada', '75.00', '0.0000', '0.0000', '0000-00-00', 0, '2011-06-28 14:02:43', 'asys', ''),
(8, 8, 4, 1, '', 'Retencion de IVA 100% Persona Juridica No Domiciliada', '100.00', '0.0000', '0.0000', '0000-00-00', 0, '2011-06-28 14:03:01', 'asys', ''),
(9, 9, 1, 2, '002', 'Retencion ISLR Honorarios Profesionales Persona Natural Residente ', '3.00', '83.3334', '83.3334', '0000-00-00', 0, '2011-06-28 15:05:03', 'asys', 'Honorarios Profesionales '),
(11, 11, 3, 2, '004', 'Retencion ISLR Honorarios Profesionales Persona Juridica ', '5.00', '25.0000', '0.0000', '0000-00-00', 0, '2011-06-28 15:18:14', 'asys', 'Honorarios Profesionales'),
(14, 14, 5, 2, '012', 'Retencion ISLR  Honorarios Profesionales MEDICOS / ABOGADOS', '3.00', '83.3334', '83.3334', '0000-00-00', 0, '2011-07-07 12:23:56', 'asys', 'Honorarios Profesionales'),
(15, 15, 1, 2, '018', 'Retension ISLR Comisiones Mercantiles Persona Natural Residente', '3.00', '6.3330', '83.3334', '0000-00-00', 0, '2011-07-07 12:48:26', 'asys', 'Comisiones Mercantiles'),
(16, 16, 3, 2, '020', 'Retension ISLR Comisiones Mercantiles Persona Juridica Domiciliada', '5.00', '25.0000', '0.0000', '0000-00-00', 0, '2011-07-07 12:59:32', 'asys', 'Comisiones Mercantiles'),
(17, 17, 1, 2, '025', 'Retencion ISLR Intereses 	Persona Natural Residente', '3.00', '6.3330', '83.3334', '0000-00-00', 0, '2011-07-07 13:03:33', 'asys', 'Intereses'),
(18, 18, 3, 2, '027', 'Retencion ISLR Intereses 	Persona Juridica Domiciliada', '5.00', '25.0000', '0.0000', '0000-00-00', 0, '2011-07-07 13:04:40', 'asys', 'Intereses'),
(19, 19, 1, 2, '053', 'Retencion ISLR Servicios 	Persona Natural Residente', '1.00', '6.3330', '83.3334', '0000-00-00', 0, '2011-07-07 13:15:32', 'asys', 'Servicios'),
(20, 20, 3, 2, '055', 'Retencion ISLR Servicios 	Persona Juridica Domiciliada', '2.00', '25.0000', '0.0000', '0000-00-00', 0, '2011-07-07 13:16:15', 'asys', 'Servicios'),
(21, 21, 1, 2, '057', 'Retension ISLR Arrendamiento de Bienes Inmuebles Persona Natural Residente', '3.00', '6.3330', '83.3334', '0000-00-00', 0, '2011-07-07 13:18:08', 'asys', 'Arrendamiento de Bienes  Inmuebles'),
(22, 22, 3, 2, '059', 'Retension ISLR Arrendamiento de Bienes Inmuebles Persona Juridica Domiciliada', '5.00', '25.0000', '0.0000', '0000-00-00', 0, '2011-07-07 13:18:46', 'asys', 'Arrendamiento de Bienes Inmuebles'),
(23, 23, 1, 2, '061', 'Retension ISLR Arrendamiento de Bienes Muebles Persona Natural Residente', '3.00', '6.3330', '83.3334', '0000-00-00', 0, '2011-07-07 13:24:03', 'asys', 'Arrendamiento de Bienes Muebles'),
(24, 22, 3, 2, '063', 'Retension ISLR Arrendamiento de Bienes Muebles Persona Juridica Domiciliada', '5.00', '25.0000', '0.0000', '0000-00-00', 0, '2011-07-07 13:30:56', 'asys', 'Arrendamiento de Bienes Muebles'),
(25, 25, 1, 2, '071', 'Retencion ISLR Gastos de Transporte Nacional y Fletes Persona Natural Residente', '1.00', '6.3330', '83.3334', '0000-00-00', 0, '2011-07-07 13:37:54', 'asys', 'Gastos de Transporte Nacional y Fletes'),
(26, 26, 3, 2, '072', 'Retencion ISLR Gastos de Transporte Nacional y Fletes Persona Juridica Domiciliada', '3.00', '25.0000', '0.0000', '0000-00-00', 0, '2011-07-07 13:38:24', 'asys', 'Gastos de Transporte Nacional y Fletes'),
(27, 27, 1, 2, '083', 'Retencion ISLR Propaganda y Publicidad Persona Natural Residente', '3.00', '6.3330', '83.3334', '0000-00-00', 0, '2011-07-07 13:44:21', 'asys', 'Propaganda y Publicidad'),
(28, 28, 3, 2, '084', 'Retencion ISLR Propaganda y Publicidad Persona Juridica Domiciliada', '5.00', '25.0000', '0.0000', '0000-00-00', 0, '2011-07-07 13:44:49', 'asys', 'Propaganda y Publicidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidad`
--

DROP TABLE IF EXISTS `localidad`;
CREATE TABLE IF NOT EXISTS `localidad` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL DEFAULT '',
  `estado` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `estado_atiende` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `codigo_SIGA` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='segundo nivelicadion despues de la ub';

--
-- Volcado de datos para la tabla `localidad`
--

INSERT INTO `localidad` (`id`, `descripcion`, `estado`, `estado_atiende`, `codigo_SIGA`) VALUES
(1, 'SIMON', 24, 24, '118');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

DROP TABLE IF EXISTS `marca`;
CREATE TABLE IF NOT EXISTS `marca` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `marca` varchar(150) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1587 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id`, `marca`) VALUES
(1, 'SIN MARCA'),
(2, 'LOS ANDES'),
(3, 'MONTANA ALTA'),
(4, 'NEVADA'),
(5, 'VISTA'),
(6, 'BENAVES'),
(7, 'DEL MONTE'),
(8, 'DEL CORRAL '),
(9, 'CERELAC'),
(10, 'VITAL BOYS'),
(11, 'CHEPELCA'),
(12, 'NESTUM'),
(13, 'NESTLE'),
(14, 'KRECER'),
(15, 'GERBER'),
(18, 'MARY'),
(19, 'POLLY'),
(20, 'PRIMOR'),
(21, 'LUMALAC'),
(27, 'NAN'),
(28, 'NUTRAMIGEN'),
(31, 'COLONA'),
(33, 'SAN SIMON'),
(34, 'KIANA'),
(41, 'DER CONDE'),
(42, 'PLUMROSE'),
(45, 'CHOCOCAO'),
(46, 'CHOCOCOOL'),
(47, 'CHOCOMAX'),
(48, 'TODDY'),
(50, 'NESFRUTA'),
(53, '3P'),
(54, 'RACAMAR'),
(56, 'LECHECAO'),
(57, 'NUTRICHICHA   '),
(58, 'CHICHALAC'),
(59, 'CAMPESTRE'),
(61, 'NESTEA'),
(62, 'LIPTON'),
(70, 'SEBUCAN'),
(71, 'EL CHICHE ARZOLAY'),
(73, 'SANTONI '),
(74, 'LLANO VERDE '),
(75, 'AGUA BLANCA'),
(76, 'CAMPIVERDE'),
(78, 'VENALCASA'),
(80, 'DEL ALBA'),
(83, 'CRISTAL'),
(84, 'AVELINA'),
(85, 'ASOCOFE'),
(86, 'LA LUCHA'),
(87, 'LASSIE'),
(88, 'PANTERA'),
(90, 'QUAKER '),
(91, 'MAIZORITOS'),
(98, 'FROOT LOOPS'),
(100, 'KELLOGGS'),
(101, 'KRAKIN FLAKES'),
(102, 'ZUCARITAS'),
(103, 'CORN FLAKES'),
(104, 'SPECIAL K'),
(106, 'NUTRICEREAL'),
(108, 'SERVIPORK '),
(109, 'CHACICA '),
(111, 'EMBUTIDOS ORIENTE'),
(112, 'EUROSPAR'),
(115, 'GIACOMELO'),
(116, 'MONSERRATINA'),
(122, 'FIESTA '),
(123, 'CVA'),
(125, 'VENEZUELA SOCIALISTA'),
(126, 'EL PAO'),
(129, 'HERMO'),
(131, 'CASTELO BRANCO'),
(133, 'ITALVECA'),
(134, 'STEFANUTTI'),
(136, 'CORDILLERA'),
(137, 'OSCAR MAYER'),
(138, 'CHARVENCA'),
(145, 'SIN MARCA'),
(146, 'VENEZUELA'),
(147, 'CARBONERO'),
(148, 'CAISA'),
(149, 'COMA'),
(150, 'D-WILLIAM'),
(151, 'EUREKA'),
(153, 'KAMPESTRE'),
(155, 'LA GIRALDA'),
(156, 'MACARENA'),
(157, 'NATURALYST'),
(160, 'SPANIA'),
(161, 'TAF'),
(165, 'ANDURINA'),
(166, 'LA ALHAMBRA'),
(169, 'INDOPECA '),
(170, 'LA COMADRE'),
(172, 'MAGGI'),
(173, 'DON PEDRO'),
(174, 'GUT'),
(176, 'MAGGIS'),
(177, 'LA LOMBARDA'),
(180, 'LE BISCUIT '),
(183, 'OREO'),
(188, 'MC LAWS'),
(189, 'TROPICAL'),
(191, 'TRIGO DE ORO'),
(196, 'AITANA '),
(200, 'LA BRUJA'),
(207, 'LA CHINITA'),
(208, 'LESMI'),
(209, 'TRES SOLES'),
(211, 'AREL'),
(212, 'BIEN STAR'),
(213, 'DORAL'),
(214, 'KRISOL'),
(215, 'MONACO'),
(216, 'NAVARRA'),
(218, 'SARCE'),
(219, 'KAMPIST'),
(220, 'LA DELICIA'),
(221, 'VIENESA'),
(222, 'SIBONEY'),
(223, 'MINA'),
(230, 'SAN MIGUEL '),
(231, 'ELLA'),
(237, 'LA PERIJANERA'),
(243, 'LA PASTORA'),
(246, 'LA PERFECTA'),
(248, 'SAVORY'),
(249, 'LA PASTORENA'),
(251, 'CENTROLAC'),
(252, 'MIGURT'),
(253, 'RIKESA CHEDDAR'),
(255, 'FRITZ '),
(256, 'GALLO NEGRO'),
(259, 'PURO LOMO'),
(260, 'PILI COLONIA'),
(261, 'QUENACA'),
(274, 'CEBU'),
(277, 'YEJU'),
(278, 'VICTORIA'),
(285, 'IDEAL'),
(287, 'DON NIKO'),
(288, 'MIGURT'),
(290, 'MONTALBAN'),
(291, 'DONA ILIA'),
(295, 'LA ESPANOLA'),
(297, 'LORETO'),
(298, 'LA PEDRIZA'),
(299, 'TORRENT'),
(300, 'GRANJA FLOR'),
(304, 'SARASA'),
(305, 'MARRUECOS'),
(307, 'NINA'),
(313, 'MARGARITA'),
(321, 'ANTOXO'),
(327, 'SNOB'),
(328, 'UNDERWOOD'),
(329, 'EVEBA'),
(330, 'DITORIS'),
(332, 'VERA'),
(333, 'BAHIA MARA'),
(337, 'YARECITO'),
(340, 'ARRECIFE'),
(341, 'ELIZA'),
(343, 'ALFAMA'),
(344, 'REINA DEL CARIBE'),
(345, 'EL REY'),
(348, 'CARABOBO'),
(349, 'HANSF '),
(350, 'PREMIUM'),
(351, 'CLUB SOCIAL'),
(352, 'CHARMY'),
(355, 'KRAKER BRAN'),
(357, 'RIFEL'),
(363, 'FENIX'),
(367, 'ITALIA'),
(369, 'INAICA'),
(370, 'TAKY'),
(372, 'SAFARI'),
(374, 'BUONA'),
(376, 'PANKY'),
(379, 'SAN MICHEL'),
(380, 'DIANA'),
(384, 'CASA '),
(385, 'PERIJANERA'),
(387, 'LA ESTANCIA'),
(392, 'JUANA'),
(393, 'HARINA PAN'),
(395, 'ANDINITO'),
(398, 'BLANCAFLOR'),
(399, 'ROBIN HOOD'),
(409, 'IMPERIAL'),
(413, 'SAN ANTONIO'),
(414, 'EL PE?ON'),
(416, 'MADRID'),
(419, 'YUKERY'),
(422, 'FRUTEL'),
(428, 'NATULAC'),
(431, 'LA AURORA '),
(432, 'MI REINA'),
(436, 'KIKO'),
(437, 'SWEET JELLY'),
(438, 'ROYAL'),
(440, 'GATORADE'),
(442, 'PARMALAT'),
(446, 'MELEGATTI '),
(448, 'CHICHOS'),
(454, 'EDUARDO'),
(455, 'LA SIRENA'),
(457, 'DONNA VERA'),
(458, 'MI CASA'),
(459, 'RONCO'),
(460, 'CAPRI'),
(461, 'MI MESA'),
(462, 'S/N'),
(465, 'IMPORTADO'),
(467, 'COPPELIA '),
(468, 'LA CREMERIA'),
(472, 'ALBECA'),
(473, 'MAYOTROPIC'),
(476, 'DEL AVILA'),
(477, 'FERGOS'),
(479, 'QUIDY'),
(480, 'AMAPOLA'),
(481, 'D-MAY'),
(483, 'HEINZ'),
(484, 'INDIAN '),
(485, 'D-WILLIAMS'),
(488, 'TIQUIRE FLORES'),
(489, 'LA YAYA'),
(491, 'PAMPERO'),
(492, 'HEINZ?'),
(493, 'MCCORMICK'),
(494, 'MR. TUNA'),
(497, 'RIOJAVINA'),
(500, 'MAVESA'),
(501, 'ASTILLA'),
(504, 'U.S.A'),
(506, 'CELESTIAL'),
(510, 'ESKIMO'),
(512, 'DELLA NONNA '),
(514, 'CORONA'),
(517, 'DIAMANTE'),
(522, 'ORIENTE'),
(523, 'VALLES DEL TURBIO'),
(527, 'INCOSA'),
(529, 'LINA'),
(531, 'NAGUANA'),
(532, 'VEENFOOD'),
(536, 'CASTILLO DE ORO'),
(537, 'KRAFT'),
(538, 'KRATF'),
(539, 'LA RENDIDORA'),
(540, 'NACIONAL'),
(541, 'ANACOCO'),
(543, 'BLANQUITO'),
(545, 'COGOYAL'),
(547, 'DO?A EMILIA'),
(548, 'EL CHIMITO'),
(552, 'GRAN MARQUES'),
(554, 'LA CHINITA'),
(556, 'LA MOLINERA'),
(558, 'LUISANA'),
(559, 'MOLINCA'),
(560, 'MONICA'),
(561, 'MO?ITO'),
(563, 'VIGOR'),
(567, 'PALDI'),
(568, 'PANORAMA'),
(573, 'LA SABANA '),
(575, 'MI QUERENCIA'),
(576, 'SAN ONOFRE'),
(577, 'VALLE HONDO'),
(579, 'SUR DEL LAGO '),
(585, 'DOÑA ELENA'),
(590, 'KONFIT'),
(592, 'ACARIGUA'),
(593, 'CAMPO DEL SOL'),
(597, 'DEL SUR'),
(598, 'DON AMABLE'),
(627, 'OCCIDENTE'),
(629, 'PORTUGUESA'),
(632, 'SANTA BARBARA'),
(642, 'MARBELLA'),
(645, 'TODASANA'),
(647, 'DEL MAR'),
(648, 'CHEF'),
(649, 'ORISOL'),
(650, 'MAZEITE'),
(651, 'LA JOYA'),
(652, 'AMBAR '),
(654, 'BUNGE'),
(656, 'ENABAS'),
(657, 'FRIGI'),
(659, 'NATUROIL'),
(661, 'ORIENTAL'),
(662, 'PORTUMESA'),
(663, 'SANTA LUCIA'),
(664, 'SIGLO DE ORO'),
(666, 'VATEL'),
(668, 'MARISOL'),
(669, 'SADIA'),
(671, 'CRAVO'),
(673, 'MAZORCA'),
(674, 'DEMASA'),
(676, 'LOS ANGELES'),
(680, 'EL TUNAL '),
(681, 'RIO REAL'),
(684, 'SAN BLAS'),
(686, 'AROMA'),
(690, 'FAMA DE AMERICA'),
(694, 'REDIAL'),
(698, 'EL MAIZAL'),
(700, 'GRANAL'),
(704, 'ALLEGRI'),
(705, 'GRAN SE?ORA'),
(706, 'LA ESPECIAL'),
(707, 'SINDONI'),
(708, 'GALO '),
(709, 'LA COMISANA'),
(713, 'REGAL'),
(714, 'ARBELLA '),
(715, 'LA REINA'),
(723, 'ALNAFOL'),
(727, 'SMART HOME'),
(728, 'ADICORA'),
(730, 'RINDEX'),
(732, 'EASYCLEAN '),
(739, 'DURACELL'),
(740, 'IZY PACK'),
(742, 'SOFRUVER'),
(748, 'EVOL'),
(750, 'KANNY HOGAR'),
(751, 'DOVE'),
(752, 'DIOXOGEN'),
(753, 'COLGATE '),
(755, 'HEAD & SHOULDERS'),
(756, 'PANTENE'),
(757, 'HERBAL ESSENCES'),
(759, 'SEDAL'),
(760, 'NUTRIHAIR'),
(761, 'GILLETE BLUE  '),
(762, 'GILLETTE '),
(765, 'AXE'),
(766, 'VALMY'),
(770, 'CAREY'),
(774, 'BODY MILK'),
(778, 'CREST'),
(781, 'REXONA'),
(784, 'GARNIER'),
(785, 'BRUT'),
(789, 'GILLETE'),
(790, 'MUM'),
(793, 'X-TREME'),
(799, 'FAX KPEM'),
(800, 'PROTEX'),
(801, 'CLARITY'),
(802, 'LIRIO'),
(803, 'FAX '),
(805, 'PALMOLIVE'),
(806, 'LAS LLAVES'),
(807, 'PRESTOBARBA'),
(813, 'SECRET '),
(816, 'OKI'),
(817, 'HAIER'),
(821, 'SOMA'),
(823, 'CHEMCO'),
(824, 'ATAKO'),
(829, 'BLIN'),
(832, 'ACE'),
(833, 'ARIEL'),
(834, 'ABC'),
(837, 'SOFTY'),
(838, 'VALE'),
(839, 'CARICIA '),
(840, 'GRASSOF'),
(841, 'AXION '),
(844, 'GRASSOFF '),
(845, 'DOWNY'),
(847, 'SOFLAN '),
(848, 'VIETVEN'),
(849, 'FRESKESITO'),
(850, 'PAMPERS '),
(857, 'DONCELLA'),
(858, 'NATURELLA'),
(859, 'ALWAYS'),
(860, 'TESS'),
(861, 'ERES'),
(864, 'SELVA'),
(873, 'POINTER'),
(874, 'BAMBOO'),
(877, 'FANDEC'),
(878, 'GRANCO'),
(880, 'GUAYANA'),
(881, 'NATUVEN '),
(882, 'PLAST KIN'),
(885, 'ROSAL'),
(887, 'FLORAL'),
(891, 'SUTIL '),
(897, 'HOUSEHOLD NAPKINS'),
(906, 'SAN ANDRES'),
(909, 'TECNO AVICOLAS'),
(910, 'SINCOL'),
(911, 'NAGUANA'),
(915, 'MAH'),
(916, 'WAMPOLE '),
(917, 'PORCINOS DEL ALBA'),
(918, 'CICLON '),
(919, 'SUPREMA'),
(921, 'LA ROJERA'),
(922, 'LLANO VERDE'),
(924, 'LA ESTANCIA'),
(925, 'CHICCO'),
(926, 'EVERY NIGHT'),
(927, 'CROMA PLUS'),
(929, 'NEVEX'),
(930, 'MISTOLIN'),
(931, 'MOTTA'),
(932, 'PANDORA'),
(933, 'BAULI'),
(934, 'TREPARRISCOS'),
(935, '777'),
(936, 'MEGA-AQUAT'),
(937, 'TORONDOY'),
(938, 'S/M'),
(939, 'SUCREAM'),
(940, 'PAIMOSA'),
(943, 'ROLDA'),
(944, 'SMART HOME'),
(945, 'CARDENAL'),
(946, 'DE LA VIUDA'),
(947, 'ALFIN '),
(948, 'BLANKYS'),
(949, 'FRUTEXSA'),
(950, 'ROBERTI'),
(951, 'PERIJANERA \r\n'),
(952, 'SOM PINE'),
(953, 'INPA'),
(954, 'LA NIÑITA'),
(956, 'AREF'),
(957, 'CVAL'),
(958, 'LA MARCA'),
(959, 'CVAL'),
(960, 'ALBERTO VO5'),
(962, 'LATERRE DELLAGRO '),
(963, 'DIPOMORO'),
(964, 'EL QUENIQUE'),
(965, 'AMY SEC'),
(966, 'SAN FELIPE'),
(967, 'LADY SPEED STICK'),
(968, 'MARILU'),
(969, 'ALLEGRI'),
(970, 'RICE CURTIRIOSO'),
(971, 'ZUPLA'),
(973, 'MEGA-SOAP'),
(974, 'SPEED STICK'),
(975, 'CRESPO'),
(976, 'MANEIRO'),
(977, 'EMOC'),
(978, 'ROCOFRUT'),
(979, 'ENMANUEL'),
(980, 'FRICHIS'),
(981, 'SUSY'),
(982, 'TE AMO'),
(983, 'CHERRY'),
(984, 'OVERSKIN'),
(987, 'STARKIDS'),
(988, 'SECUREZZA'),
(989, 'MINALBA'),
(990, 'EL CHICHE ARZOLAY'),
(991, 'TEXAS'),
(992, 'POMODORO'),
(993, 'BEVILACQUA'),
(994, 'PIRULIN'),
(995, 'UREÑA'),
(996, 'LA MARACUCHA'),
(997, 'KING FRUIT'),
(998, 'SARCE'),
(999, 'LOS ROSALES'),
(1000, 'MARINA'),
(1001, 'FRAGAMAR'),
(1002, 'MARIO'),
(1003, 'MIRAGUA'),
(1004, 'KARN'),
(1005, 'ESPIGA DE ORO'),
(1006, 'EL FOGONCITO'),
(1007, 'EL ALBA'),
(1008, 'PALERMO'),
(1009, 'GUILLETT'),
(1010, 'ALPINA'),
(1011, 'FULLER'),
(1013, 'OH'),
(1016, 'SAMUERA'),
(1017, 'OLIVEIRA DA SERRA '),
(1019, 'VIRGEN DOLY '),
(1020, 'AMPARITO '),
(1021, 'ALISA'),
(1023, 'SAN MIGUEL'),
(1024, 'PAWI'),
(1025, 'FINA ARROZ'),
(1026, 'EL FARO'),
(1027, 'FRICAJITA'),
(1029, 'FRICA SLIM'),
(1030, 'FRICA'),
(1031, 'BLANQUITA'),
(1032, 'LLANO ALEGRE'),
(1033, 'HOLLAND MILL'),
(1034, 'CORPORACION CARNICA'),
(1035, 'LOS ROQUES'),
(1036, 'EL CHICHE ARZOLAY'),
(1037, 'SUPREMO'),
(1038, 'PRIMULA'),
(1039, 'KING FIESTA'),
(1040, 'FOURSENSONS'),
(1042, 'VEL ROSA'),
(1043, 'AXION'),
(1044, 'BURAGO'),
(1045, 'CHILDS PARK'),
(1046, 'TOYS IBROS'),
(1047, 'HOT WHEELS'),
(1048, 'BABY ALIVE'),
(1049, 'WIN FUN'),
(1051, 'PEPPA PIG'),
(1052, 'FUN TRUCK'),
(1053, 'SUPER WORLD'),
(1054, 'PANTOMA'),
(1055, 'NODDING HEAD'),
(1056, 'ROAD RIPPERS'),
(1058, 'TRANSFORMERS'),
(1059, 'BUMBLEBEE'),
(1060, 'FANTASY & CO'),
(1061, 'MI LITTLE PONY'),
(1062, 'TOYS IBROS'),
(1063, 'CLEAR CHESS'),
(1064, 'SUPER WHEELS'),
(1065, 'MI HOT ROD'),
(1066, 'IDEAL'),
(1067, 'RANCYN'),
(1068, 'ITALDORO'),
(1069, 'RACING'),
(1070, 'PLAY-DOH'),
(1071, 'BARBIE'),
(1072, 'SMART TRAIN'),
(1073, 'FISHER-PRICE'),
(1074, 'MONSTER WHEELS'),
(1075, 'DISNEY'),
(1076, 'TIC TAC'),
(1077, 'HASBRO GRAMING\r\n'),
(1079, 'MY MONOPOLY\r\n'),
(1080, 'MUSIC FAIRY'),
(1081, 'DIE CAST METAL'),
(1082, 'MAXIPLAST'),
(1083, 'CASTILLO'),
(1084, 'GRANDES'),
(1086, 'MEGA-B-KLOR'),
(1090, 'AMY   SOFTY'),
(1091, 'MEGA-SPOT'),
(1096, 'FRIENDS  TS  TELA  REGULAR  '),
(1098, 'DON  PEDRO'),
(1099, 'POMI'),
(1103, 'PLATINUM'),
(1104, 'PIAZZA'),
(1106, 'LA CEIBA'),
(1107, 'FLOR DE CAÑA'),
(1108, 'FLOR DE CANA'),
(1109, 'DON DAVID'),
(1110, 'GUANARITO'),
(1111, 'BLANKING'),
(1112, 'ANGOPA'),
(1114, 'BUFALO'),
(1115, 'SORBETICO'),
(1116, 'DOS CABALLOS'),
(1117, 'CASA DE FRUTAS'),
(1118, 'COPADRE PRIMOL'),
(1119, 'PUIG'),
(1120, 'TORONTO'),
(1121, 'FRIENDS'),
(1122, 'LUCHAREPA'),
(1123, 'DONA EMILIA'),
(1124, 'BAHIA'),
(1125, 'DONA DELIA'),
(1126, 'GILDA'),
(1127, 'LIMPIACLEAN'),
(1128, 'OH! LIMPIO'),
(1129, 'BONNA'),
(1130, 'MAIZINA AMARICANA'),
(1131, 'EURO'),
(1132, 'SALIMAR'),
(1133, 'ROLI'),
(1134, 'CHIMORE'),
(1135, 'CASTELLO'),
(1136, 'CRUZ DE ORO'),
(1137, 'YORK PRIMOL'),
(1138, 'LA BELINDA'),
(1140, 'MONACA'),
(1141, 'ARO'),
(1143, 'CRISTALOE'),
(1145, 'CHIPS AHOY'),
(1147, 'ANELLA '),
(1148, 'MOCITOS'),
(1149, 'KARDAMILI'),
(1150, 'TIA TERE'),
(1151, 'PELICANO'),
(1152, 'LAS TINIEBLAS'),
(1153, 'EL GUAYANES'),
(1154, 'HANS'),
(1156, 'SAN SEBASTIAN '),
(1157, 'ENVOPLAST'),
(1158, 'RESCA'),
(1159, 'MILANI'),
(1160, 'LA MOLINASA'),
(1161, 'MIMADITO'),
(1162, 'BRESH'),
(1163, 'CAROLA'),
(1164, 'GEMA'),
(1165, 'GOLDEN FOODS'),
(1166, 'ABSORBO'),
(1168, 'LARK'),
(1169, 'BEST 3000 PLUS'),
(1171, 'MOLVA'),
(1172, 'REY DEL GOLFO'),
(1173, 'SUAVITEL'),
(1174, 'EXPLENDOR '),
(1175, 'TITAN'),
(1176, 'AJAX   SCOURERS'),
(1177, 'TAPA  AMARILLA'),
(1178, 'COLISEO'),
(1179, 'NORVI'),
(1180, 'DULCITOS'),
(1181, 'PONTO'),
(1182, 'IWE'),
(1183, 'HELIOS'),
(1184, 'PONCE & BENZO'),
(1185, 'JABOOM'),
(1186, 'THE SMURFS'),
(1187, 'GRAN SASSO'),
(1188, 'SPRING'),
(1190, 'LINA'),
(1191, 'FOILPAC'),
(1192, 'ENV-O-ALUM'),
(1193, 'MAGIPLAST'),
(1194, ' EVAPRIMOL '),
(1195, 'LIDER FOIL'),
(1197, 'LA EXTRAFINA'),
(1198, 'EL COCINERITO'),
(1199, 'MOLANCA'),
(1200, 'CANAIMA'),
(1201, 'FAVEP'),
(1202, 'PALMIN'),
(1203, 'HERREPLAST'),
(1204, 'CARGILL'),
(1205, 'DARNEL'),
(1206, 'STAR PACK'),
(1208, 'MAUPARIN'),
(1209, 'SUCCESS'),
(1210, 'MELAWS'),
(1211, 'LA  CARABOBENA'),
(1213, 'CAMPO VIVO '),
(1214, 'PACO'),
(1215, 'VIRGEN EXTRA'),
(1216, 'COOTAP'),
(1217, 'PINTO  OCHOA '),
(1218, 'MACRO FOOD'),
(1221, 'IMPIA'),
(1222, ' CARLIN '),
(1224, 'ARCO REX'),
(1225, 'MONTORO'),
(1226, 'EL GLOBO'),
(1227, 'FRAYMOND'),
(1228, 'LA PASTELERA'),
(1229, 'GRISCO'),
(1230, 'PHARSANA'),
(1231, 'MARACAY'),
(1232, 'ROSAL PLUS'),
(1233, 'SPRING SOFT'),
(1234, 'ZZZ'),
(1235, 'MELODY'),
(1236, 'AVICOLA  DEL  ALBA'),
(1237, 'NUREX'),
(1238, 'MIA'),
(1239, 'MAR   DEL   NORTE'),
(1240, 'EZEQUIEL  ZAMORA'),
(1241, 'CONTROLE'),
(1242, 'LA  VIENESA'),
(1243, 'AJAX'),
(1245, ' BRISS'),
(1246, 'DURALINM'),
(1247, 'Z'),
(1248, 'PIEDRAS  BLANCAS'),
(1250, 'SALSECA'),
(1251, 'MAYORIKA'),
(1252, 'BIO CLEAN'),
(1253, 'SUPER  BLANKO'),
(1254, 'NUBE  EXTRAGRANDE'),
(1255, 'INTIMAS'),
(1257, 'NA  LAURA'),
(1258, 'LAVALIN'),
(1260, 'ISLAMAR'),
(1262, 'MEGA  QUAT  CITRON'),
(1263, 'MEGA  QUAT  CARICIAS  '),
(1264, 'MEGA  DISHES'),
(1265, 'MEGA  ACRILICK'),
(1266, 'DIVINA  VICTORIA'),
(1267, 'BIG'),
(1268, 'PAMPA'),
(1269, 'FRUTIVO'),
(1270, 'PAPPYER'),
(1271, 'ROLLON'),
(1272, 'ARTESCO  KIDS'),
(1273, 'MI  TRENSITO'),
(1274, 'CROMOBOOK'),
(1275, 'FLECHA'),
(1276, 'FAPARCA'),
(1277, 'ALGODONES  DEL  ORINOCO'),
(1278, 'FRUTEL LIGHT'),
(1279, 'FABLI'),
(1280, 'HALMITON  BEACH'),
(1281, 'DAEWOO'),
(1282, 'PESQUERA DJ, C.A'),
(1283, 'LISTA\r\n'),
(1284, 'GRANO  DORADO'),
(1285, 'SAPIO'),
(1286, 'PUNTA  BRAVA'),
(1287, 'MEGA  QUAT'),
(1289, 'VISTA  AL RIO'),
(1291, 'SAGRADA FAMILIA'),
(1292, 'GENEROSO'),
(1294, 'DARNEL'),
(1295, 'DONA ALICIA'),
(1296, 'LIDER'),
(1297, 'FANDEC'),
(1298, 'MULTIPLAS'),
(1300, 'BIC COMFORT 3'),
(1302, 'NEVEX ULTRA'),
(1303, 'DIGI'),
(1305, 'B&D'),
(1306, 'LG'),
(1307, 'GTRONIC'),
(1308, 'PANASONIC'),
(1309, 'DIXIE'),
(1310, 'GIRALDA'),
(1311, 'MAR  BONITA'),
(1312, 'SALETINA'),
(1313, 'EDMAG'),
(1314, 'LA CARIDAD'),
(1316, 'LEVAL'),
(1317, 'DEVAL'),
(1318, 'SCHICK'),
(1319, 'HONEY BRAN'),
(1321, 'LA CAMPINA'),
(1322, 'RICA CHICHA'),
(1323, 'BEIA'),
(1324, 'CVA  AZUCAR'),
(1325, 'AQUAMAR'),
(1326, 'RIO TURBIO'),
(1327, 'MEGA GLASS'),
(1329, 'EL ORDENO'),
(1330, 'VENECIANA'),
(1331, 'NEVEX  ULTRA\r\n'),
(1332, 'BON BRIL'),
(1333, 'REGAL'),
(1335, 'PDC GRAN  SASSO'),
(1336, 'SUE Y CHIA'),
(1337, 'PRINCESA'),
(1338, 'SUTIX'),
(1339, 'FLORENTINA'),
(1340, 'ALBA'),
(1341, 'HOT PICK'),
(1342, 'PESCADORES DEL ALBA'),
(1344, 'MERCAL'),
(1345, 'QUIMAVEN'),
(1346, 'JAZMIN SUPER'),
(1347, 'JAZMIN  COLOR'),
(1348, 'JAZMIN  GARDEN'),
(1349, 'MARACAY  ROSADO'),
(1350, 'GARDENIA'),
(1351, 'INDUSTRIAL'),
(1353, 'GEDAYLACA'),
(1354, 'LEBANESE  MOONEH'),
(1357, 'SABANA'),
(1358, 'CONSENTIDO'),
(1359, 'NUBES'),
(1360, 'PEDONE'),
(1361, 'TERRANTICAS'),
(1362, 'FREEZER FLAKES'),
(1363, 'CHOCO KONG'),
(1364, 'KELLOGGS SPECIAL'),
(1365, 'AMBRA'),
(1367, 'SELECTA'),
(1368, 'LA   NIEVE'),
(1369, 'BATALLA  MATA  DE LA MIEL'),
(1370, 'BUENA AVENTURA'),
(1371, 'TIKKO'),
(1372, 'GOSOTOSO ESTRELLA'),
(1373, 'LOTUS LEMOND'),
(1374, 'DISCOVERY LEM OND'),
(1375, 'HAPPY BARON'),
(1376, 'OASIS'),
(1377, 'TURCUMANI'),
(1379, 'WAKYDS'),
(1380, 'LEMOND LOTUS'),
(1381, 'CANA BLANCA'),
(1382, 'RIZADITAS'),
(1383, 'KROCA LOCA'),
(1384, 'TOTO CHIPS'),
(1385, 'TUTTI FRUTTI'),
(1386, 'LAS FIERAS DE LA GLORIA'),
(1387, 'ROSO GARGANO'),
(1388, 'CALEDONIA'),
(1389, 'INVERSIONES DNIS'),
(1390, 'OLEOS'),
(1391, 'PRIMAVERA'),
(1392, 'CRISTINA'),
(1393, 'ROMA'),
(1394, 'SOY-TEX'),
(1395, 'LIRI-ANGERY'),
(1396, 'THERMO 5FIVE'),
(1397, 'HUXOL'),
(1398, 'NAFF'),
(1399, 'LANCARE'),
(1400, 'PADOVA'),
(1401, 'SANITA PREMIUM'),
(1402, 'COMEDOR PDVAL'),
(1403, 'DUBON TOMI TIPI'),
(1404, 'DONA CRISTINA'),
(1405, 'HORIZONTE EXTRA ESPECIAL'),
(1406, 'SWEETIES HOME'),
(1407, 'MAESTRO'),
(1410, 'DONA GOYA'),
(1411, 'PREMISE  PEGOLD'),
(1412, 'MONTEVENETO'),
(1413, 'DALVITO'),
(1414, 'CONDE'),
(1415, 'ROMERO'),
(1416, 'COMERSO'),
(1417, 'MONARCH'),
(1418, 'FONTANA'),
(1419, 'SOMA  BLUE'),
(1420, 'MILY'),
(1421, 'ARAURIGUA'),
(1422, 'KIANA  GUAYANES'),
(1423, 'LA BERACA'),
(1424, 'ENASAL'),
(1425, 'VISCIANO'),
(1426, 'GALLETINA'),
(1427, 'EL COMANDANTE'),
(1428, 'SURTINAVEN Y-O POALARIS'),
(1429, 'ROSANA'),
(1430, 'LA EXQUISITA'),
(1431, 'SUVINCA'),
(1432, 'LETONIA'),
(1433, 'CARRIER'),
(1434, 'NORDIK'),
(1435, 'BYMAX'),
(1436, 'OXFORD'),
(1438, 'EL SEMBRADOR'),
(1439, 'CARIPITO OASIS'),
(1441, 'MAGNU SAN'),
(1443, 'POWERS'),
(1444, 'HAPPY KITCHEN'),
(1445, 'LA  FONTANA'),
(1446, 'GOLDEN'),
(1447, 'MINALBA'),
(1448, 'BEAUTIFUL GIRL'),
(1449, 'TODESCHINI'),
(1450, 'DELICIA'),
(1451, 'PEPSI'),
(1452, 'PEPSI  MAX'),
(1453, 'PEPSI LIGHT'),
(1454, '7UP'),
(1455, 'VAL CLEAN'),
(1456, '7UP LGHT'),
(1457, 'DORAYA'),
(1458, 'TODO LIMPIO'),
(1459, 'GAYON '),
(1461, 'HAPPY ART'),
(1462, 'TIKKOTIN'),
(1464, 'FABIS'),
(1465, 'L-BAR'),
(1466, 'CALI'),
(1467, 'DENCORUB'),
(1468, 'MUCUCHIRE'),
(1470, 'PRODUMIX'),
(1471, 'MALTIN POLAR'),
(1472, 'CLAP'),
(1473, 'ZAMORA'),
(1474, 'AMALFI'),
(1475, 'MUNDOFLEX'),
(1476, 'FALKAWARE'),
(1477, 'VENEZUELA PRODUCTIVA'),
(1478, 'GAVCA'),
(1479, 'RISCOSSA'),
(1480, 'CRISTALES VENEZOLANO VZLA PRODUC'),
(1481, 'MEPROVEN - VENEZUELA PRODUCTIVA'),
(1482, 'INOPLAS - VENEZUELA PRODUCTIVA'),
(1484, ' CONGLO  VENEZUELA PRODUCTIVA'),
(1485, ' INV. TRADING -VZLA PRODUCTIVA'),
(1487, 'CONGLO VZLA PRODUC'),
(1488, 'CONGLO - VENEZUELA PRODUCTIVA'),
(1489, 'INVERSIONES TRADING - VENEZUELA PRODUCTIVA'),
(1490, 'KAREN - VENEZUELA PRODUCTIVA'),
(1491, 'IBERIA DECO - VENEZUELA PRODUCTIVA'),
(1492, 'FALUPA - VENEZUELA PRODUCTIVA'),
(1493, 'RIALCA - VENEZUELA PRODUCTIVA'),
(1495, 'BRANDINI'),
(1496, 'PUEBLO HONDO'),
(1497, 'COVEPLAST'),
(1498, 'MIZ'),
(1499, 'CONGO'),
(1500, 'CHEEZ  WHIZ'),
(1501, 'ROSO GARGANO '),
(1502, 'MINI CHIPS'),
(1503, 'FRUTASRICA'),
(1504, 'MEGATEXTIL - VZLA PRODUC.'),
(1505, 'PENI - VZLA PRODUC'),
(1506, 'LISBOA'),
(1507, 'DONA NELLY'),
(1508, 'SABANA MAR'),
(1510, 'EXITO'),
(1511, 'ANTODRAN'),
(1512, 'CHILCHOTA'),
(1513, 'MEGA-SOFT'),
(1514, 'EL SABINO'),
(1515, 'QUIMEX'),
(1516, 'BELFIORE'),
(1517, 'INTYFEM'),
(1518, 'ENVAPRIMOLCA'),
(1519, 'EL COMPADRE'),
(1520, 'COMBO EVAPRIMOLCA'),
(1521, 'PRIMOL'),
(1522, 'CLIC'),
(1523, 'COOP LAS VIII ESTRELLAS'),
(1524, 'MACKABI'),
(1525, 'DE LA BUENA COSECHA'),
(1526, 'RUNCORP'),
(1527, 'GILITEY PLUS '),
(1528, 'CAMELOT'),
(1529, 'ORCHARD'),
(1530, 'MEPROVEN'),
(1531, 'FENIX'),
(1532, 'CONTACTO CERO'),
(1533, 'CANA BLANCA'),
(1534, 'RONDON'),
(1535, 'STORKS CHOICE'),
(1536, 'TAMANACO'),
(1537, 'CANAIMA'),
(1538, 'MOLINERA'),
(1539, 'FELT CREATIONS'),
(1540, 'SIMOPREF'),
(1541, 'REINA DEL CARIBE'),
(1542, 'OCEAN WHICE'),
(1543, 'LALAS GOURMET'),
(1544, 'DON BITUTE'),
(1545, 'SALSESA'),
(1546, 'MI CAMPO'),
(1547, 'CLIC '),
(1548, 'FULL'),
(1549, 'AMY'),
(1550, 'LA CALABOZENA'),
(1552, 'CHOCOCACAO'),
(1553, 'GRIMAR'),
(1554, 'MIVAL'),
(1556, 'EVERVESS'),
(1557, 'VARI'),
(1558, 'FRESCARINI'),
(1559, 'AGRIMER'),
(1561, '4 MEN'),
(1562, 'THERMO FIVE'),
(1563, 'EMPOMACA '),
(1567, 'CHIQUITINTEX'),
(1568, 'RF'),
(1570, 'FEMININA'),
(1571, 'AAAY'),
(1572, 'AAAY'),
(1574, 'BABY ROGER'),
(1575, 'ZOBEIDA'),
(1576, 'DON TOALLIN'),
(1578, 'AGROAMERICA'),
(1579, 'ULTRA SOFT'),
(1580, 'BELVITA'),
(1581, 'PAN'),
(1582, 'RIKESA'),
(1583, 'ORINOKIA'),
(1584, 'ASOCOFE'),
(1585, 'FLOR DEL VALLE'),
(1586, 'XTRACELL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ministerio`
--

DROP TABLE IF EXISTS `ministerio`;
CREATE TABLE IF NOT EXISTS `ministerio` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ministerio`
--

INSERT INTO `ministerio` (`id`, `descripcion`) VALUES
(1, 'ministerio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

DROP TABLE IF EXISTS `modulos`;
CREATE TABLE IF NOT EXISTS `modulos` (
  `cod_modulo` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_modulo_padre` int(11) DEFAULT NULL,
  `nom_menu` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `archivo_php` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `archivo_tpl` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `img_ruta` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`cod_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=279 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`cod_modulo`, `cod_modulo_padre`, `nom_menu`, `archivo_php`, `archivo_tpl`, `orden`, `img_ruta`, `visible`) VALUES
(0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(1, NULL, 'Configuraci&oacute;n', NULL, NULL, 0, '../../libs/imagenes/system.png', 1),
(2, NULL, 'Clientes', NULL, NULL, 3, '../../libs/imagenes/icons/2.png', 0),
(3, NULL, 'Stock', NULL, NULL, 1, '../../libs/imagenes/icons/13.png', 1),
(5, NULL, 'Ventas', NULL, NULL, 5, '../../libs/imagenes/icons/8.png', 1),
(6, NULL, 'Ctas. por Cobrar', NULL, NULL, 6, '../../libs/imagenes/icons/4.png', 0),
(7, NULL, 'Reportes', NULL, NULL, 7, '../../libs/imagenes/68.png', 1),
(8, 5, 'Clientes', 'cliente.php', 'cliente.tpl', 1, '../../libs/imagenes/icons/28.png', 1),
(13, 7, 'Reporte Historico de Precios', 'seleccionarFecha14.php', 'rpt_venta_productos14.tpl', NULL, '../../libs/imagenes/68.png', 1),
(31, 1, 'Par&aacute;metros Generales', 'parametros_generales.php', 'parametros_generales.tpl', 1, '../../libs/imagenes/11.png', 1),
(54, NULL, 'P&aacute;gina de Inicio', 'pagina_inicio.php', 'pagina_inicio.tpl', NULL, '../../libs/imagenes/icons/12.png', 1),
(55, 3, 'Almac&eacute;n', 'almacen.php', 'almacen.tpl', 4, '../../libs/imagenes/11.png', 1),
(56, 5, 'Zonas', 'zona.php', 'zona.tpl', 3, '../../libs/imagenes/11.png', 0),
(57, 5, 'Vendedor', 'vendedor.php', 'vendedor.tpl', 4, '../../libs/imagenes/21.png', 0),
(58, 5, 'Gestionar Facturas (Productos y/o Servicios)', 'factura_lista_clientes.php', 'factura_lista_clientes.tpl', 5, '../../libs/imagenes/65.png', 1),
(59, 5, 'Estado de Cuentas de Clientes', 'cxc_lista_clientes.php', 'cxc_lista_clientes.tpl', 1, '../../libs/imagenes/55.png', 0),
(60, 7, 'Relaci&oacute;n de Compra por Proveedor', 'relacion_compra_proveedores.php', 'relacion_compra_proveedores.tpl', 6, '../../libs/imagenes/4.png', 0),
(61, 3, 'Productos', 'producto.php', 'producto.tpl', 8, '../../libs/imagenes/13.png', 1),
(64, 3, 'Rubro', 'departamento.php', 'departamento.tpl', 6, '../../libs/imagenes/11.png', 1),
(65, 3, 'Subrubro', 'grupo.php', 'grupo.tpl', 7, '../../libs/imagenes/55.png', 1),
(66, 3, 'Marca', 'linea.php', 'linea.tpl', 24, '../../libs/imagenes/37.png', 0),
(67, 3, 'Servicios', 'servicio.php', 'servicio.tpl', 9, '../../libs/imagenes/13.png', 0),
(68, 1, 'Usuarios', 'usuarios.php', 'usuarios.tpl', 5, '../../libs/imagenes/21.png', 1),
(69, 3, 'Existencia de Producto por Almac&eacute;n', 'producto_existencia_almacen.php', 'producto_existencia_almacen.tpl', 5, '../../libs/imagenes/13.png', 0),
(70, 1, 'Retenci&oacute;n I.S.L.R', 'islr.php', 'islr.tpl', 9, '../../libs/imagenes/18.png', 0),
(71, 7, 'Relación de Facturas por Clientes (Productos y/o Servicios)', 'relacion_factura_clientes.php', 'relacion_factura_clientes.tpl', 4, '../../libs/imagenes/4.png', 0),
(72, 3, 'Boletos', 'boletos.php', 'boletos.tpl', 3, '../../libs/imagenes/13.png', 0),
(73, 5, 'Gestionar Facturas (Boletos)', 'factura_lista_clientes_boletos.php', 'factura_lista_clientes_boletos.tpl', 4, '../../libs/imagenes/11.png', 0),
(74, 7, 'Relaci&oacute;n de Facturas por Clientes (Boletos)', 'relacion_factura_clientes_boletos.php', 'relacion_factura_clientes_boletos.tpl', 5, '../../libs/imagenes/4.png', 0),
(75, NULL, 'Consulta', NULL, NULL, 6, '../../libs/imagenes/59.png', 0),
(76, 1, 'Responsable', 'responsable.php', 'responsable.tpl', 7, '../../libs/imagenes/28.png', 0),
(77, 1, 'Banco', 'banco.php', 'banco.tpl', 8, '../../libs/imagenes/55.png', 0),
(78, 1, 'Instrumento de Forma Pago', 'instrumentoformapago.php', 'instrumentoformapago.tpl', 10, '../../libs/imagenes/023.png', 0),
(79, 5, 'Devoluci&oacute;n Facturas (Productos y/o Servicios)', 'devolucion_venta_lista.php', 'devolucion_venta_lista.tpl', 6, '../../libs/imagenes/tipo.png', 1),
(80, 1, 'Retenci&oacute;n I.V.A', 'retencioniva.php', 'retencioniva.tpl', 8, '../../libs/imagenes/19.png', 0),
(81, 1, 'Correlativos', 'correlativos.php', 'correlativos.tpl', 20, '../../libs/imagenes/023.png', 0),
(83, NULL, 'Proveedores', 'proveedores.php', 'proveedores.tpl', 3, '../../libs/imagenes/28.png', 0),
(84, NULL, 'Compras', NULL, NULL, 2, '../../libs/imagenes/29.png', 0),
(85, NULL, 'Ctas. por Pagar', NULL, NULL, 6, '../../libs/imagenes/41.png', 0),
(86, 3, 'Proveedores', 'proveedores.php', 'proveedores.tpl', 1, '../../libs/imagenes/proveedor.png', 1),
(87, 84, 'Gestionar Compra', 'proveedores_compra_lista.php', 'proveedores_compra_lista.tpl', 5, '../../libs/imagenes/4.png', 1),
(88, 89, 'Estado de Cuentas de Proveedores', 'cxp_lista_proveedores.php', 'cxp_lista_proveedores.tpl', 1, '../../libs/imagenes/55.png', 0),
(89, NULL, 'Caja y Bancos', NULL, NULL, 6, '../../libs/imagenes/05.png', 1),
(90, 1, 'Bancos', 'tesoreria_banco.php', 'tesoreria_banco.tpl', 1, '../../libs/imagenes/55.png', 1),
(91, 89, 'Cheques', 'tesoreria_bancoSeleccion.php', 'tesoreria_bancoSeleccion.tpl', 2, '../../libs/imagenes/49.png', 0),
(92, 89, 'Consulta / Impresi&oacute;n de Cheques', 'tesoreria_impresioncheque.php', 'tesoreria_impresioncheque.tpl', 3, '../../libs/imagenes/03.png', 0),
(93, 89, 'Movimientos Bancarios', 'tesoreria_movimientos_bancarios.php', 'tesoreria_movimientos_bancarios.tpl', 4, '../../libs/imagenes/01.png', 0),
(94, 89, 'Conciliaci&oacute;n Bancaria', 'tesoreria_conciliacion_seleccion_cuenta.php', 'tesoreria_conciliacion_seleccion_cuenta.tpl', 5, '../../libs/imagenes/41.png', 0),
(95, 89, 'Reportes', NULL, NULL, 6, '../../libs/imagenes/66.png', 0),
(96, 89, 'Tipos de Movimientos Bancarios', 'tipos_movimientos_bancarios.php', 'tipos_movimientos_bancarios.tpl', 11, '../../libs/imagenes/55.png', 0),
(97, 1, 'Impuesto Municipal ICA', 'impuesto_municipal_ica.php', 'impuesto_municipal_ica.tpl', 9, '../../libs/imagenes/20.png', 0),
(98, 1, 'Tipos de Impuestos', 'tipo_impuesto.php', 'tipo_impuesto.tpl', 12, '../../libs/imagenes/20.png', 0),
(99, 1, 'Entidades', 'entidad.php', 'entidad.tpl', 13, '../../libs/imagenes/20.png', 0),
(100, 1, 'Formulaci&oacute;n de Impuestos', 'formulacion_impuestos.php', 'formulacion_impuestos.tpl', 14, '../../libs/imagenes/20.png', 0),
(101, 1, 'Lista de Impuestos', 'lista_impuestos.php', 'lista_impuestos.tpl', 15, '../../libs/imagenes/11.png', 0),
(102, 5, 'Tipo de Clientes', 'tipo_cliente.php', 'tipo_cliente.tpl', 1, '../../libs/imagenes/icons/28.png', 0),
(103, 1, 'Moneda', 'multi_moneda.php', 'multi_moneda.tpl', 20, '../../libs/imagenes/moneda.png', 0),
(104, 1, 'Divisas', 'divisas.php', 'divisas.tpl', NULL, '../../libs/imagenes/moneda.png', 0),
(105, 1, 'Tasas de Cambio', 'tasas_de_cambio.php', 'tasas_de_cambio.tpl', 0, '../../libs/imagenes/moneda.png', 0),
(106, NULL, 'Operaciones', NULL, NULL, 10, '../../libs/imagenes/icons/10.png', 1),
(107, 106, 'Cargar Archivo POS', 'cargar_post.php', 'cargar_post.tpl', 1, '../../libs/imagenes/icons/10.png', 0),
(108, 3, 'Kardex Almac&eacute;n', 'kardex_almacen.php', 'kardex_almacen.tpl', 13, '../../libs/imagenes/icons/10.png', 0),
(109, 3, 'Entradas Almac&eacute;n', 'entrada_almacen.php', 'entrada_almacen.tpl', 10, '../../libs/imagenes/traslados.png', 1),
(110, 3, 'Salidas Almac&eacute;n', 'salida_almacen.php', 'salida_almacen.tpl', 12, '../../libs/imagenes/traslados.png', 1),
(111, 3, 'Traslados entre Almacenes', 'traslados_almacen.php', 'traslados_almacen.tpl', 11, '../../libs/imagenes/traslados.png', 1),
(112, 3, 'Tipos de Movimientos de Inventario', 'tipo_movimientos_almacen.php', 'tipo_movimientos_almacen.tpl', 14, '../../libs/imagenes/icons/10.png', 0),
(113, 106, 'Contabilizar cheques', 'contabilizar_cheque.php', 'contabilizar_cheque.tpl', 2, '../../libs/imagenes/icons/10.png', 0),
(114, 106, 'Contabilizar Facturas', 'contabilizar_facturacion.php', 'contabilizar_facturacion.tpl', 3, '../../libs/imagenes/icons/10.png', 0),
(115, 106, 'Contabilizar ND', 'contabilizar_nota_debito.php', 'contabilizar_nota_debito.tpl', 5, '../../libs/imagenes/icons/10.png', 0),
(116, 106, 'Contabilizar NC', 'contabilizar_nota_credito.php', 'contabilizar_nota_credito.tpl', 4, '../../libs/imagenes/icons/10.png', 0),
(117, 89, 'Imprimir de Conciliaci&oacute;n Bancaria', 'tesoreria_vista_conciliaciones.php', 'tesoreria_vista_conciliaciones.tpl', 5, '../../libs/imagenes/03.png', 0),
(118, NULL, 'Requisiciones', NULL, NULL, 2, '../../libs/imagenes/41.png', 0),
(119, 118, 'Requisiciones compras/materiales 	', 'requisiciones.php', 'requisiciones.tpl', 1, '../../libs/imagenes/41.png', 1),
(120, 1, 'Unidades Administrativas / Departamentos', 'unidades_list.php', 'unidades_list.tpl', 22, '../../libs/imagenes/12.png', 0),
(121, 118, 'Requisiciones Servicios', 'unidades_list3.php', 'unidades_list3.tpl', 2, '../../libs/imagenes/41.png', 1),
(122, 84, 'Requisiciones Administraci&oacute;n', 'requisiciones_administracion_list.php', 'requisiciones_administracion_list.tpl', 3, '../../libs/imagenes/41.png', 0),
(123, 118, 'An&aacute;lisis de Cotizaciones', 'analisiscotizaciones.php', 'analisiscotizaciones.tpl', 4, '../../libs/imagenes/67.png', 1),
(124, 84, 'Reporte de Listado de Proveedores', 'listadoproveedores.php', 'listadoproveedores.tpl', 2, '../../libs/imagenes/66.png', 1),
(125, 3, 'Reporte de Listado de Materiales', 'listadomateriales.php', 'listadomateriales.tpl', 20, '../../libs/imagenes/68.png', 1),
(126, 3, 'Reporte de Productos en Existencia', 'productosexistencia.php', 'productosexistencia.tpl', 17, '../../libs/imagenes/68.png', 1),
(127, 84, 'Analisis de Cotizaciones', 'analisiscotizaciones.php', 'analisiscotizaciones.tpl', 2, '../../libs/imagenes/67.png', 0),
(128, 84, 'Requisiciones Compras', 'requisicionescompras.php', 'requisicionescompras.tpl', 1, '../../libs/imagenes/61.png', 0),
(129, 84, 'Emisi&oacute;n de Ordenes de Compra ', 'ordendecompra.php', 'ordendecompra.tpl', 3, '../../libs/imagenes/4.png', 0),
(130, 7, 'Libro de Compras', 'seleccionarFecha1.php', 'seleccionarFecha7.tpl', 7, '../../libs/imagenes/56.png', 0),
(131, 7, 'Libro de Ventas', 'seleccionarFecha1.php', 'seleccionarFecha6.tpl', 8, '../../libs/imagenes/56.png', 0),
(132, 1, 'Especialidades de Proveedores', 'proveedores_especialidad.php', 'proveedores_especialidad.tpl', 21, '../../libs/imagenes/28.png', 0),
(133, 5, 'Cuentas x Cobrar Pendiente por Presentar', 'cxc_lista_clientes_pendiente.php', 'cxc_lista_clientes_pendiente.tpl', 2, '../../libs/imagenes/25.png', 0),
(134, 5, 'Cuentas x Cobrar Pendiente por Autorizar', 'cxc_lista_clientes_autorizar.php', 'cxc_lista_clientes_autorizar.tpl', 3, '../../libs/imagenes/26.png', 0),
(135, 5, 'Cuentas x Cobrar Pendiente Pago', 'cxc_lista_clientes_pago.php', 'cxc_lista_clientes_pago.tpl', 4, '../../libs/imagenes/23.png', 0),
(136, 5, 'Reporte de Cobranzas Realizadas', 'seleccionarFecha1.php', 'cobranzas_realizadas.tpl', 5, '../../libs/imagenes/icons/7.png', 0),
(137, 5, 'Reporte de Estado de Cuenta', 'resumen_cxc_clasificacion.php', 'rpt_estado_de_cuenta.tpl', 7, '../../libs/imagenes/icons/7.png', 0),
(138, 5, 'Relaci&oacute;n de Cuentas por Cobrar', 'seleccionarFecha1.php', 'rpt_relaciones_cxc.tpl', 8, '../../libs/imagenes/icons/7.png', 0),
(139, 5, 'Reporte Detalles de Pagos de Mas', 'seleccionarFecha1.php', 'rpt_pagos_demas.tpl', 9, '../../libs/imagenes/icons/7.png', 0),
(140, 89, 'M&eacute;dicos', 'proveedores_medicos.php', 'proveedores_medicos.tpl', 2, '../../libs/imagenes/28.png', 0),
(141, 1, 'Tipo de Proveedor', 'tipo_proveedor.php', 'tipo_proveedor.tpl', 20, '../../libs/imagenes/28.png', 0),
(142, 5, 'Reporte Detalle de Pago', 'rpt_detalle_pago.php', 'rpt_detalle_pago.tpl', 10, '../../libs/imagenes/icons/7.png', 0),
(143, 5, 'Reporte Resumen CXP Clasificado', 'resumen_cxc_clasificacion.php', 'resumen_cxc_clasificacion.tpl', 11, '../../libs/imagenes/icons/7.png', 0),
(144, 5, 'Listado de Clientes', 'listado_clientes.php', 'listado_clientes.tpl', 9, '../../libs/imagenes/icons/7.png', 0),
(145, 5, 'Listado CXP M&eacute;dico', 'cxp_listado_medico.php', 'cxp_listado_medico.tpl', 12, '../../libs/imagenes/icons/7.png', 0),
(146, 89, 'Listado de M&eacute;dicos por Pagar', 'seleccionarFecha1.php', 'listado_cxp_medicos.tpl', 3, '../../libs/imagenes/icons/7.png', 0),
(147, 7, 'IVA Retenido', 'seleccionarFecha1.php', 'seleccionarFecha8.tpl', 9, '../../libs/imagenes/56.png', 0),
(148, 7, 'Cheques Emitidos', 'seleccionarFecha1.php', 'seleccionarFecha9.tpl', 10, '../../libs/imagenes/56.png', 0),
(149, 89, 'Listado Anal&iacute;ticos', 'seleccionarFecha1.php', 'analiticos.tpl', 4, '../../libs/imagenes/icons/7.png', 0),
(150, 5, 'Anal&iacute;ticos Facturas', 'seleccionarFecha1.php', 'analiticoscxc.tpl', 13, '../../libs/imagenes/icons/7.png', 0),
(151, 89, 'Transferencias/Cheques de gerencia', 'tesoreria_bancoSeleccionTransf.php', 'tesoreria_bancoSeleccionTransf.tpl', 10, '../../libs/imagenes/preview_f2.png', 0),
(152, 89, 'Facturas/Cheque', 'facturasxCheque.php', 'facturasxCheque.tpl', 11, '../../libs/imagenes/03.png', 0),
(153, 5, 'Estado de Cta. Cliente', 'edo_cta_xcliente.php', 'edo_cta_xcliente.tpl', 13, '../../libs/imagenes/icons/7.png', 0),
(154, 5, 'Notas de Entrega', 'lista_clientes.php', 'lista_clientes.tpl', 3, '../../libs/imagenes/9.png', 0),
(155, 5, 'Pedidos', 'lista_clientes.php', 'lista_clientes.tpl', 2, '../../libs/imagenes/02.png', 0),
(156, 5, 'Presupuesto/Cotizaci&oacute;n', 'lista_clientes.php', 'lista_clientes.tpl', 1, '../../libs/imagenes/4.png', 0),
(157, 7, 'Relaci&oacute;n de Cotizaciones por Clientes', 'relacion_cotizacion_clientes.php', 'relacion_cotizacion_clientes.tpl', 1, '../../libs/imagenes/4.png', 0),
(158, 5, 'Relaci&oacute;n de Notas de Entrega por Clientes', 'relacion_notas_entrega_clientes.php', 'relacion_notas_entrega_clientes.tpl', 3, '../../libs/imagenes/4.png', 0),
(159, 7, 'Relaci&oacute;n de Pedidos por Clientes', 'relacion_pedidos_clientes.php', 'relacion_pedidos_clientes.tpl', 3, '../../libs/imagenes/4.png', 0),
(160, 106, 'Corte X<br>(Impresora Fiscal)', 'corte_x.php', 'corte_x.tpl', 6, '../../libs/imagenes/icons/10.png', 1),
(161, 106, 'Corte Z<br>(Impresora Fiscal)', 'corte_z.php', 'corte_z.tpl', 7, '../../libs/imagenes/icons/10.png', 1),
(162, 5, 'Productos (Precios)', 'producto_precios.php', 'producto_precios.tpl', 7, '../../libs/imagenes/13.png', 0),
(163, 7, 'Compras por Cliente (PYME)', 'seleccionarFecha1.php', 'rpt_ventas_diarias.tpl', 26, '../../libs/imagenes/68.png', 1),
(164, 7, 'Devoluciones Diarias', 'seleccionarFecha1.php', 'rpt_devolucion_diaria_ventas.tpl', 12, '../../libs/imagenes/56.png', 0),
(165, 3, 'Reporte Movimientos de Inventario', 'seleccionarFecha1.php', 'movimientos_inventario.tpl', 15, '../../libs/imagenes/68.png', 0),
(166, 3, 'Reporte Toma de Inventario F&iacute;sico', 'toma_inventario_fisico.php', 'toma_inventario_fisico.tpl', 16, '../../libs/imagenes/68.png', 0),
(167, 106, 'Borrar Precompromisos de Inventarios', 'precompromisos.php', 'precompromisos.tpl', 8, '../../libs/imagenes/icons/10.png', 0),
(168, 7, 'Ventas por Productos', 'seleccionarFecha1.php', 'rpt_venta_productos.tpl', 13, '../../libs/imagenes/56.png', 0),
(169, 7, 'Ventas por Vendedor', 'seleccionarFecha1.php', 'rpt_vendedor_ventas.tpl', 14, '../../libs/imagenes/56.png', 0),
(170, 3, 'Region', 'region.php', 'region.tpl', 2, '../../libs/imagenes/sitios.png', 1),
(171, 3, 'Localidad', 'localidad.php', 'localidad.tpl', 3, '../../libs/imagenes/sitios.png', 1),
(172, 5, 'Despacho', 'despacho.php', 'despacho.tpl', 26, '../../libs/imagenes/37.png', 1),
(173, 3, 'Cambio de Precios', 'cambio_precio.php', 'cambio_precio.tpl', 22, '../../libs/imagenes/50.png', 0),
(174, 3, 'Reporte Localidad', 'rpt_localidades.php', 'rpt_localidades.php', 21, '../../libs/imagenes/68.png', 0),
(175, 3, 'Almac&eacute;n Localidad', 'almacen_localidad.php', 'almacen_localidad.tpl', 4, '../../libs/imagenes/patentes_vivienda.png', 1),
(176, 5, 'Despachado', 'despachado.php', 'despachado.tpl', 27, '../../libs/imagenes/37.png', 1),
(177, 3, 'Cambio de Precio', 'precio_cambio.php', 'precio_cambio.tpl', 23, '../../libs/imagenes/37.png', 0),
(178, 7, 'Ventas por Productos (POS)', 'seleccionarFecha3.php', 'rpt_venta_productos3.tpl', 18, '../../libs/imagenes/68.png', 1),
(179, 5, 'Distrito Escolar', 'Distrito_escolar.php', 'Distrito_escolar.tpl', 29, '../../libs/imagenes/56.png', 0),
(180, 5, 'Ministerios', 'ministerio.php', 'ministerio.tpl', 28, '../../libs/imagenes/56.png', 0),
(181, 106, 'Ventas Pos->Pyme', 'sincro_pos_pyme.php', 'sincro_pos_pyme.tpl', 9, '../../libs/imagenes/icons/10.png', 0),
(182, 106, 'Actualizacion de Precios', 'act_precios.php', 'act_precios.tpl', 10, '../../libs/imagenes/icons/10.png', 0),
(183, 106, 'Actualizacion de Precios TXT', 'act_precios_txt.php', 'act_precios_txt.tpl', 11, '../../libs/imagenes/icons/10.png', 0),
(184, 106, 'Sincronizacion Pyme central', 'act_central.php', 'act_central.tpl', 12, '../../libs/imagenes/icons/10.png', 0),
(185, 7, 'Notas de entrega por DE (POS)', 'seleccionarFecha3.php', 'rpt_nota_entrega_de.tpl', 20, '../../libs/imagenes/56.png', 0),
(186, 7, 'Ventas por Vendedor (POS)', 'seleccionarFecha3.php', 'rpt_venta_vendedor.tpl', 19, '../../libs/imagenes/56.png', 0),
(187, 106, 'Sincronizacion Pyme central CCS', 'act_central_ccs.php', 'act_central_ccs.tpl', 13, '../../libs/imagenes/icons/10.png', 0),
(188, 7, 'Ventas por Productos (CENTRAL)', 'seleccionarFecha4.php', 'rpt_venta_productos4.tpl', 22, '../../libs/imagenes/56.png', 0),
(189, 7, 'Ventas por Vendedor (CENTRAL)', 'seleccionarFecha4.php', 'rpt_venta_vendedor4.tpl', 23, '../../libs/imagenes/56.png', 0),
(190, 7, 'Compras por Cliente (POS)', 'seleccionarFecha4.php', 'rpt_compras_x_cliente_pos.tpl', 24, '../../libs/imagenes/68.png', 1),
(191, 3, 'Reporte Movimientos de Invetario', 'seleccionarFecha1_traslado.php', 'rpt_movimiento_inventario.tpl', 18, '../../libs/imagenes/68.png', 1),
(192, 3, 'Reporte Existencia Inventario (POS)', 'existenciaPos.php', 'existenciaPos.tpl', 19, '../../libs/imagenes/68.png', 1),
(196, 1, 'Restricciones Cedula/Dia', 'cedula_dia.php', 'cedula_dia.tpl', 5, '../../libs/imagenes/21.png', 1),
(197, 7, 'Clientes registrados Bio', 'seleccionarFecha4.php', 'clientes_biometrico.tpl', 25, '../../libs/imagenes/56.png', 0),
(198, 106, 'Subir Ventas', 'file_upload.php', 'file_upload.tpl', 20, '../../libs/imagenes/icons/4.png', 1),
(199, 106, 'Descarga De Ventas', 'files_dowload_send.php', 'files_dowload.tpl', 11, '../../libs/imagenes/icons/4.png', 0),
(200, 106, 'Sincronizacion Categorias y Productos Centrales', 'sincronizar_prod_cat.php', 'sincronizar_prod_cat.tpl', 20, '../../libs/imagenes/icons/50.png', 0),
(201, 3, 'Almacenes SIGA', 'almacen_siga.php', 'almacen_siga.tpl', 5, '../../libs/imagenes/patentes_vivienda.png', 0),
(202, 3, 'Ubicaciones SIGA', 'ubicaciones_siga.php', 'ubicaciones_siga.tpl', 5, '../../libs/imagenes/ubicacion.png', 0),
(203, 89, 'Cierre de Caja', 'cierre_caja.php', 'cierre_caja.tpl', 2, '../../libs/imagenes/icons/054.png', 0),
(204, 89, 'Depositos de Efectivo', 'deposito.php', 'deposito.tpl', 2, '../../libs/imagenes/icons/054.png', 0),
(205, 89, 'Generar Cataportes', 'cataporte.php', 'cataporte.tpl', 5, '../../libs/imagenes/icons/054.png', 0),
(206, 89, 'Transferencia a Caja Principal', 'depositos.php', 'depositos.tpl', 2, '../../libs/imagenes/icons/054.png', 1),
(207, 89, 'Cierre Efectivo', 'cataportes.php', 'cataportes.tpl', 3, '../../libs/imagenes/icons/054.png', 1),
(208, 89, 'Libro Ventas', 'libro_ventas.php', 'libro_ventas.tpl', 10, '../../libs/imagenes/66.png', 1),
(209, 1, 'Cajas Impresora', 'caja_impresora.php', 'caja_impresora.tpl', 5, '../../libs/imagenes/11.png', 1),
(210, 89, 'Cerrar Cajero', 'cierre_cajero.php', 'cierre_cajero.tpl', 1, '../../libs/imagenes/icons/054.png', 1),
(211, 106, 'Descarga De Inventario Actual', 'inventario_dowload_send.php', 'inventario_dowload.tpl', 21, '../../libs/imagenes/document-save.png', 0),
(212, 106, 'Descarga De Movimientos de Inventario', 'kardex_dowload_send.php', 'kardex_dowload.tpl', 21, '../../libs/imagenes/document-save.png', 0),
(217, 7, 'Reporte Libro Ventas Totales', 'reporte_libro_venta.php', 'reporte_libro_venta.tpl', 12, '../../libs/imagenes/68.png', 1),
(218, 106, 'Actualizar Productos y Precios', 'precios_productos.php', 'precios_productos.tpl', 20, '../../libs/imagenes/icons/4.png', 1),
(219, 1, 'Cuentas Bancarias', 'cuentas_contables.php', 'cuentas_contables.tpl', 6, '../../libs/imagenes/26.png', 1),
(220, 1, 'Operaciones de Apertura', 'operaciones_apertura.php', 'operaciones_apertura.tpl', 1, '../../libs/imagenes/113.png', 1),
(221, 106, 'Estabilizaci&oacute;n Productos', 'estabilizacion_productos.php', 'estabilizacion_productos.tpl', 20, '../../libs/imagenes/icons/50.png', 1),
(222, 106, 'Descargar Sincronizacion Data', 'descargar_sincronizacion.php', 'descargar_sincronizacion.tpl', 22, '../../libs/imagenes/document-save.png', 1),
(223, 89, 'Retiro Efectivo Cajero', 'retiro_efectivo.php', 'retiro_efectivo.tpl', 13, '../../libs/imagenes/65.png', 1),
(224, 7, 'Reporte Consolidado Arqueos', 'reporte_arqueos.php', 'reporte_arqueos.tpl', 12, '../../libs/imagenes/68.png', 1),
(225, NULL, 'Calidad', '', '', 5, '../../libs/imagenes/113.png', 1),
(226, 225, 'Evaluar Productos', 'calidad_entrada.php', 'calidad_entrada.tpl', 1, '../../libs/imagenes/113.png', 1),
(227, 225, 'Revision Almacen', 'calidad_retiro.php', 'calidad_retiro.tpl', 2, '../../libs/imagenes/113.png', 1),
(228, 225, 'Acta De Visitas', 'calidad_visita.php', 'calidad_visita.tpl', 1, '../../libs/imagenes/88.png', 1),
(229, 1, 'Tipo Uso', 'tipo_uso.php', 'tipo_uso.tpl', 1, '../../libs/imagenes/11.png', 1),
(230, 1, 'Tipo Visita', 'tipo_visita.php', 'tipo_visita.tpl', 1, '../../libs/imagenes/11.png', 1),
(231, 7, 'Reporte Calidad', 'seleccionarFecha1.php', 'rpt_calidad.tpl', 1, '../../libs/imagenes/68.png', 1),
(232, 3, 'Tomas F&iacute;sica de Inventario', 'tomas_fisicas.php', 'tomas_fisicas.tpl', 13, '../../libs/imagenes/46.png', 1),
(233, 3, 'Planilla Toma Fisica Inventario', 'toma_fisica_manual.php', 'toma_fisica_manual.tpl', 16, '../../libs/imagenes/68.png', 1),
(234, 7, 'Ventas por Productos (PYME)', 'rpt_ventas_productos_pyme.php', 'rpt_ventas_productos_pyme.tpl', 50, '../../libs/imagenes/68.png', 1),
(235, 3, 'Actas de Inventario', 'actas_inventario.php', 'actas_inventario.tpl', 15, '../../libs/imagenes/68.png', 0),
(236, 3, 'Planilla Toma Fisica Inventario (etiqueta)', 'toma_fisica_manual_etiqueta.php', 'toma_fisica_manual_etiqueta.tpl', 16, '../../libs/imagenes/68.png', 1),
(237, 225, 'Modulo nuevo', 'calidad_nuevo.php', 'calidad_nuevo.tpl', 1, '../../libs/imagenes/88.png', 0),
(238, 239, 'Gestionar Pedidos (Productos y/o Servicios)', 'pedidos_lista_clientes.php', 'pedidos_lista_clientes.tpl', 4, '../../libs/imagenes/64.png', 1),
(239, NULL, 'Pedido', NULL, NULL, 5, '../../libs/imagenes/02.png', 1),
(240, 5, 'Facturar Pedidos', 'salida_almacen_pedido_facturar.php', 'salida_almacen_pedido_facturar.tpl', 5, '../../libs/imagenes/64.png', 1),
(241, 239, 'Gestionar Despacho desde Pedido', 'salida_almacen_pedido.php', 'salida_almacen_pedido.tpl', 12, '../../libs/imagenes/traslados.png', 1),
(242, 1, 'Roles', 'roles.php', 'roles.tpl', 5, '../../libs/imagenes/82.png', 1),
(243, 7, 'Ventas por Productos (PYME por RUBRO)', 'toma_fisica_manual_2.php', 'toma_fisica_manual_2.tpl', 50, '../../libs/imagenes/68.png', 0),
(244, 7, 'Ventas por Productos (PYME por Categoria)', 'rpt_ventas_productos_pyme_grupo.php', 'rpt_ventas_productos_pyme_grupo.tpl', 50, '../../libs/imagenes/68.png', 1),
(245, 5, 'Relaci&oacute;n de Facturas por Clientes (Productos y/o Servicios)', 'relacion_factura_clientes.php', 'relacion_factura_clientes.tpl', 4, '../../libs/imagenes/4.png', 1),
(246, 3, 'Ajustes de Almacen', 'ajustes_almacen.php', 'ajustes_almacen.tpl', 12, '../../libs/imagenes/traslados.png', 1),
(247, 239, 'Crear Cesta Clap', 'cesta_clap_index.php', 'cesta_clap_index.tpl', 4, '../../libs/imagenes/64.png', 1),
(248, 89, 'Billetes', 'billetes_index.php', 'billetes_index.tpl', 12, '../../libs/imagenes/05.png', 1),
(249, 89, 'Cierre POS', 'cierre_pos.php', 'cierre_pos.tpl', 4, '../../libs/imagenes/icons/054.png', 1),
(250, 106, 'Reprocesar Sincronizacion Data', 'reprocesar_descargar_sincronizacion.php', 'reprocesar_descargar_sincronizacion.tpl', 22, '../../libs/imagenes/traslados.png', 1),
(251, 106, 'Cambio Precio Historico', 'cambiar_historico_precio.php', 'cambiar_historico_precio.tpl', 21, '../../libs/imagenes/icons/4.png', 1),
(252, 3, 'Producci&oacute;n', 'transformacion_index.php', 'transformacion_index.tpl', 11, '../../libs/imagenes/traslados.png', 1),
(253, 3, 'Reporte Transformación', 'seleccionarFecha1_traslado.php', 'rpt_movimiento_inv_produccion.tpl', 25, '../../libs/imagenes/68.png', 1),
(254, 89, 'Correcci&oacute;n Cataporte', 'correccion_deposito.php', 'correccion_deposito.tpl', 12, '../../libs/imagenes/icons/054.png', 1),
(255, 89, 'Cierre Tickets', 'cataporte_ticket.php', 'cataporte_ticket.tpl', 5, '../../libs/imagenes/icons/054.png', 1),
(256, 89, 'Depositos en Efectivo', 'depositos_old.php', 'depositos_old.tpl', 15, '../../libs/imagenes/icons/054.png', 1),
(257, 89, 'Generar Comprobante', 'comprobante_generar.php', 'comprobante_generar.tpl', 11, '../../libs/imagenes/64.png', 1),
(258, 7, 'Reporte Consolidado Comprobantes', 'seleccionarFecha14.php', 'rpt_consolidado.tpl', 11, '../../libs/imagenes/68.png', 1),
(259, 7, 'Reporte Movimientos Banco', 'seleccionarFecha14.php', 'rpt_consolidado_banco.tpl', 12, '../../libs/imagenes/68.png', 1),
(260, 1, 'Tipo Cuenta Presupuesto', 'tipo_cuenta_presupuesto.php', 'tipo_cuenta_presupuesto.tpl', 15, '../../libs/imagenes/26.png', 1),
(261, 1, 'Cuenta Presupuestaría', 'cuenta_presupuesto.php', 'cuenta_presupuesto.tpl', 16, '../../libs/imagenes/26.png', 1),
(262, 89, 'Cierre Tarjeta', 'cataporte_tarjeta.php', 'cataporte_tarjeta.tpl', 6, '../../libs/imagenes/icons/054.png', 0),
(263, 89, 'Cierre Deposito', 'cataporte_deposito.php', 'cataporte_deposito.tpl', 6, '../../libs/imagenes/icons/054.png', 1),
(264, 89, 'Cierre Cheque', 'cataporte_cheque.php', 'cataporte_cheque.tpl', 7, '../../libs/imagenes/icons/054.png', 1),
(265, 89, 'Cierre Credito', 'cataporte_credito.php', 'cataporte_credito.tpl', 8, '../../libs/imagenes/icons/054.png', 0),
(266, 3, 'Movimientos Servicios', 'movimientos_servicios.php', 'movimientos_servicios.tpl', 5, '../../libs/imagenes/11.png', 1),
(267, NULL, 'StockComedor', NULL, NULL, 2, '../../libs/imagenes/traslados.png', 1),
(268, 267, 'Entradas Almac&eacute;n', 'entrada_almacenoriginal.php', 'entrada_almacenoriginal.tpl', 10, '../../libs/imagenes/traslados.png', 1),
(269, 267, 'Productos', 'producto.php', 'producto.tpl', 8, '../../libs/imagenes/13.png', 1),
(270, 267, 'Salidas Almac&eacute;n', 'salida_almacen.php', 'salida_almacen.tpl', 12, '../../libs/imagenes/traslados.png', 1),
(271, 267, 'Reporte de Productos en Existencia', 'productosexistencia.php', 'productosexistencia.tpl', 17, '../../libs/imagenes/68.png', 1),
(272, 267, 'Reporte Movimientos de Inventario', 'seleccionarFecha1_traslado.php', 'rpt_movimiento_inventario.tpl', 18, '../../libs/imagenes/68.png', 1),
(273, 267, 'Producci&oacute;n', 'transformacion_index.php', 'transformacion_index.tpl', 11, '../../libs/imagenes/traslados.png', 1),
(274, 267, 'Reporte Transformaci&oacute;n', 'seleccionarFecha1_traslado.php', 'rpt_movimiento_inv_produccion.tpl', 25, '../../libs/imagenes/68.png', 1),
(275, 267, 'Planilla Toma Fisica Inventario', 'toma_fisica_manual.php', 'toma_fisica_manual.tpl', 16, '../../libs/imagenes/68.png', 1),
(276, NULL, 'Transporte', NULL, NULL, 8, '../../libs/imagenes/camion.png', 1),
(277, 276, 'Conductores', 'conductores.php', 'conductores.tpl', 1, '../../libs/imagenes/conductor.png', 1),
(278, 276, 'Tickets Entrada/Salida', 'tickets_entrada_salida.php', 'tickets_entrada_salida.tpl', 2, '../../libs/imagenes/traslados.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo_usuario`
--

DROP TABLE IF EXISTS `modulo_usuario`;
CREATE TABLE IF NOT EXISTS `modulo_usuario` (
  `cod_modulo_usuario` int(32) NOT NULL AUTO_INCREMENT,
  `cod_usuario` int(32) NOT NULL,
  `cod_modulo` int(32) NOT NULL,
  PRIMARY KEY (`cod_modulo_usuario`),
  KEY `FK_modulo_usuario_1` (`cod_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=867 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `modulo_usuario`
--

INSERT INTO `modulo_usuario` (`cod_modulo_usuario`, `cod_usuario`, `cod_modulo`) VALUES
(185, 12, 1),
(186, 12, 3),
(187, 12, 84),
(188, 12, 5),
(189, 12, 89),
(190, 12, 7),
(191, 12, 106),
(192, 8, 1),
(193, 8, 3),
(194, 8, 84),
(195, 8, 5),
(196, 8, 89),
(197, 8, 7),
(198, 8, 106),
(199, 13, 7),
(223, 19, 3),
(224, 19, 7),
(225, 20, 3),
(226, 20, 7),
(227, 14, 3),
(228, 14, 7),
(229, 14, 106),
(230, 16, 3),
(231, 16, 7),
(232, 16, 106),
(233, 17, 3),
(234, 17, 7),
(235, 17, 106),
(266, 20, 1),
(267, 20, 3),
(268, 20, 5),
(269, 20, 89),
(270, 20, 7),
(271, 20, 106),
(321, 23, 1),
(322, 23, 3),
(323, 23, 5),
(324, 23, 225),
(325, 23, 89),
(326, 23, 7),
(327, 23, 106),
(335, 24, 1),
(336, 24, 3),
(337, 24, 5),
(338, 24, 225),
(339, 24, 89),
(340, 24, 7),
(341, 24, 106),
(357, 26, 1),
(358, 26, 3),
(359, 26, 5),
(360, 26, 225),
(361, 26, 89),
(362, 26, 7),
(363, 26, 106),
(548, 27, 1),
(549, 27, 3),
(550, 27, 5),
(551, 27, 225),
(552, 27, 239),
(553, 27, 89),
(554, 27, 7),
(555, 27, 106),
(572, 29, 1),
(573, 29, 3),
(574, 29, 5),
(575, 29, 225),
(576, 29, 239),
(577, 29, 89),
(578, 29, 7),
(579, 29, 106),
(580, 32, 3),
(581, 32, 5),
(582, 32, 7),
(583, 32, 106),
(608, 25, 1),
(609, 25, 3),
(610, 25, 5),
(611, 25, 225),
(612, 25, 239),
(613, 25, 89),
(614, 25, 7),
(615, 25, 106),
(616, 28, 1),
(617, 28, 3),
(618, 28, 5),
(619, 28, 225),
(620, 28, 239),
(621, 28, 89),
(622, 28, 7),
(623, 28, 106),
(648, 22, 1),
(649, 22, 3),
(650, 22, 5),
(651, 22, 225),
(652, 22, 239),
(653, 22, 89),
(654, 22, 7),
(655, 22, 106),
(656, 33, 1),
(657, 33, 3),
(658, 33, 5),
(659, 33, 225),
(660, 33, 239),
(661, 33, 89),
(662, 33, 7),
(663, 33, 106),
(664, 36, 3),
(665, 36, 7),
(666, 36, 106),
(667, 34, 1),
(668, 34, 3),
(669, 34, 5),
(670, 34, 225),
(671, 34, 239),
(672, 34, 89),
(673, 34, 7),
(674, 34, 106),
(675, 21, 1),
(676, 21, 3),
(677, 21, 5),
(678, 21, 225),
(679, 21, 239),
(680, 21, 89),
(681, 21, 7),
(682, 21, 106),
(691, 31, 1),
(692, 31, 3),
(693, 31, 5),
(694, 31, 225),
(695, 31, 239),
(696, 31, 89),
(697, 31, 7),
(698, 31, 106),
(699, 37, 1),
(700, 37, 3),
(701, 37, 5),
(702, 37, 225),
(703, 37, 239),
(704, 37, 89),
(705, 37, 7),
(706, 37, 106),
(771, 7, 1),
(772, 7, 3),
(773, 7, 5),
(774, 7, 225),
(775, 7, 239),
(776, 7, 89),
(777, 7, 7),
(778, 7, 106),
(779, 8, 1),
(780, 8, 3),
(781, 8, 5),
(782, 8, 225),
(783, 8, 239),
(784, 8, 89),
(785, 8, 7),
(786, 8, 106),
(787, 5, 1),
(788, 5, 3),
(789, 5, 5),
(790, 5, 225),
(791, 5, 239),
(792, 5, 89),
(793, 5, 7),
(794, 5, 106),
(795, 9, 1),
(796, 9, 3),
(797, 9, 5),
(798, 9, 225),
(799, 9, 239),
(800, 9, 89),
(801, 9, 7),
(802, 9, 106),
(803, 3, 1),
(804, 3, 3),
(805, 3, 5),
(806, 3, 225),
(807, 3, 239),
(808, 3, 89),
(809, 3, 7),
(810, 3, 106),
(811, 6, 1),
(812, 6, 3),
(813, 6, 5),
(814, 6, 225),
(815, 6, 239),
(816, 6, 89),
(817, 6, 7),
(818, 6, 106),
(820, 10, 1),
(821, 10, 3),
(822, 10, 5),
(823, 10, 225),
(824, 10, 239),
(825, 10, 89),
(826, 10, 7),
(827, 10, 106),
(828, 4, 1),
(829, 4, 3),
(830, 4, 267),
(831, 4, 5),
(832, 4, 225),
(833, 4, 239),
(834, 4, 89),
(835, 4, 7),
(836, 4, 106),
(847, 2, 1),
(848, 2, 3),
(849, 2, 267),
(850, 2, 5),
(851, 2, 225),
(852, 2, 239),
(853, 2, 89),
(854, 2, 7),
(855, 2, 276),
(856, 2, 106),
(857, 1, 1),
(858, 1, 3),
(859, 1, 267),
(860, 1, 5),
(861, 1, 225),
(862, 1, 239),
(863, 1, 89),
(864, 1, 7),
(865, 1, 276),
(866, 1, 106);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moneda`
--

DROP TABLE IF EXISTS `moneda`;
CREATE TABLE IF NOT EXISTS `moneda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relacion` float DEFAULT NULL,
  `cambio_unico` varchar(10) DEFAULT '1',
  `moneda_actual` int(11) DEFAULT NULL,
  `moneda_base` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `moneda`
--

INSERT INTO `moneda` (`id`, `relacion`, `cambio_unico`, `moneda_actual`, `moneda_base`) VALUES
(2, NULL, '1', 14, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_bancarios`
--

DROP TABLE IF EXISTS `movimientos_bancarios`;
CREATE TABLE IF NOT EXISTS `movimientos_bancarios` (
  `cod_movimiento_ban` int(32) NOT NULL AUTO_INCREMENT,
  `cod_tesor_bancodet` int(32) NOT NULL,
  `fecha_movimiento` date NOT NULL,
  `tipo_movimiento` int(32) NOT NULL,
  `numero_movimiento` varchar(25) NOT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `concepto` varchar(300) DEFAULT NULL,
  `contab` varchar(2) NOT NULL,
  `estado` varchar(60) DEFAULT NULL,
  `cod_conciliacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(70) NOT NULL,
  PRIMARY KEY (`cod_movimiento_ban`),
  KEY `fk_tipo_movimiento` (`tipo_movimiento`),
  KEY `fk_cod_tesor_bancodet` (`cod_tesor_bancodet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_almacen_servicio`
--

DROP TABLE IF EXISTS `movimiento_almacen_servicio`;
CREATE TABLE IF NOT EXISTS `movimiento_almacen_servicio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_movimiento_almacen` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT '1',
  `fechacreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_movimiento_almacen` (`id_movimiento_almacen`,`id_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `movimiento_almacen_servicio`
--

INSERT INTO `movimiento_almacen_servicio` (`id`, `id_movimiento_almacen`, `id_servicio`, `estatus`, `fechacreacion`, `usuario_creacion`) VALUES
(1, 1, 1, 1, '2017-09-25 14:23:35', 4),
(2, 2, 2, 1, '2017-09-25 14:26:20', 4),
(3, 3, 9402, 1, '2017-12-17 02:46:26', 4),
(4, 4, 9403, 1, '2017-12-17 02:46:47', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_entrega`
--

DROP TABLE IF EXISTS `nota_entrega`;
CREATE TABLE IF NOT EXISTS `nota_entrega` (
  `id_nota_entrega` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_nota_entrega` varchar(32) NOT NULL DEFAULT 'S/I',
  `id_cliente` int(32) NOT NULL,
  `id_factura` int(32) UNSIGNED NOT NULL DEFAULT '0',
  `cod_vendedor` int(32) NOT NULL,
  `fechaNotaEntrega` date NOT NULL DEFAULT '0000-00-00',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuentosItemNotaEntrega` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montoItemsNotaEntrega` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ivaTotalNotaEntrega` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalTotalNotaEntrega` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_items` int(32) NOT NULL DEFAULT '0',
  `totalizar_sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_parcial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_operacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pdescuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_base_imponible` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_general` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_retencion` decimal(10,2) NOT NULL,
  `cod_estatus` int(32) UNSIGNED DEFAULT NULL,
  `fecha_pago` date NOT NULL DEFAULT '0000-00-00',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(40) NOT NULL,
  PRIMARY KEY (`id_nota_entrega`),
  KEY `fk_cod_vendedor2` (`cod_vendedor`),
  KEY `fk_usuario` (`usuario_creacion`),
  KEY `fk_id_cliente` (`id_cliente`),
  KEY `fk_cod_estatus` (`cod_estatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_entrega_detalle`
--

DROP TABLE IF EXISTS `nota_entrega_detalle`;
CREATE TABLE IF NOT EXISTS `nota_entrega_detalle` (
  `id_detalle_nota_entrega` int(32) NOT NULL AUTO_INCREMENT,
  `id_nota_entrega` int(32) UNSIGNED DEFAULT NULL,
  `id_item` int(32) NOT NULL,
  `_item_almacen` int(32) DEFAULT NULL,
  `_item_descripcion` varchar(32) NOT NULL,
  `_item_cantidad` decimal(32,0) NOT NULL DEFAULT '0',
  `_item_preciosiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_montodescuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_piva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalsiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalconiva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_ubicacion` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_detalle_nota_entrega`),
  KEY `fk_id_factura` (`id_nota_entrega`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones`
--

DROP TABLE IF EXISTS `operaciones`;
CREATE TABLE IF NOT EXISTS `operaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `libro_venta` tinyint(1) NOT NULL,
  `cierre_cajero` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones_apertura`
--

DROP TABLE IF EXISTS `operaciones_apertura`;
CREATE TABLE IF NOT EXISTS `operaciones_apertura` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `operacion` varchar(50) NOT NULL COMMENT 'Operacion a Verificar',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Indica las operaciones a verificar en la apertura de la tienda';

--
-- Volcado de datos para la tabla `operaciones_apertura`
--

INSERT INTO `operaciones_apertura` (`id`, `operacion`, `status`) VALUES
(2, 'Cierre de Cajero', 0),
(3, 'Libro de Venta', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_detalles`
--

DROP TABLE IF EXISTS `ordenes_detalles`;
CREATE TABLE IF NOT EXISTS `ordenes_detalles` (
  `cod_ord` int(11) NOT NULL,
  `cod_pro` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad_pedida` int(10) UNSIGNED NOT NULL,
  `cantidad_des` int(10) UNSIGNED NOT NULL,
  `precio` decimal(13,2) NOT NULL,
  `iva` decimal(13,2) NOT NULL,
  `total` decimal(13,2) NOT NULL,
  `total_gen` decimal(13,2) NOT NULL,
  `cod_requisicion` int(10) UNSIGNED NOT NULL,
  `cod_ord_ref` int(10) UNSIGNED NOT NULL,
  `cod_cotizacion` int(11) NOT NULL,
  `correl` int(11) NOT NULL,
  KEY `foranea` (`cod_ord`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_tipos`
--

DROP TABLE IF EXISTS `ordenes_tipos`;
CREATE TABLE IF NOT EXISTS `ordenes_tipos` (
  `cod_orden_tipo` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `descripcion` varchar(35) COLLATE utf8_spanish_ci DEFAULT NULL,
  `borrarble` int(11) DEFAULT NULL,
  `codigo` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`cod_orden_tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ordenes_tipos`
--

INSERT INTO `ordenes_tipos` (`cod_orden_tipo`, `descripcion`, `borrarble`, `codigo`) VALUES
(1, 'Compras', NULL, 487),
(2, 'Servicios', NULL, 692),
(3, 'Materiales', NULL, 0),
(7, 'Contrato', NULL, 50),
(10, 'Caja Chica', NULL, 2),
(13, 'Transferencia', NULL, 75),
(17, 'COMBUSTIBLES', NULL, 0),
(18, 'Servicios Profesionales', NULL, 46),
(19, 'Servicios de Consumo', NULL, 15),
(20, 'ARRENDAMIENTO', NULL, 31),
(21, 'SERV. RELACIONES SOCIALES', NULL, 3),
(22, 'SERVICIOS NO PERSONALES', NULL, 22),
(24, 'SERV. CAPACITACION Y ADIESTRAMIENTO', NULL, 2),
(25, 'SERVICIO DE AVISO', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

DROP TABLE IF EXISTS `parametros`;
CREATE TABLE IF NOT EXISTS `parametros` (
  `codigo` smallint(16) NOT NULL,
  `nomemp` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `departamento` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `presidente` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `periodo` varchar(9) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cargo` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nivel` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `desislr` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `ctaisrl` varchar(27) COLLATE utf8_spanish_ci DEFAULT NULL,
  `desiva` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `ctaiva` varchar(27) COLLATE utf8_spanish_ci DEFAULT NULL,
  `por_isv` decimal(7,2) DEFAULT NULL,
  `compra` int(32) DEFAULT NULL,
  `servicio` int(32) DEFAULT NULL,
  `rif` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nit` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `ciudad` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `estado` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `por_im` decimal(7,2) DEFAULT NULL,
  `por_bomberos` decimal(7,2) DEFAULT NULL,
  `lugar` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sobregirop` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `sobregirof` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `autorizacionodp` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
  `claveodp` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `contrato` int(10) UNSIGNED NOT NULL,
  `gas_dir` int(10) UNSIGNED NOT NULL,
  `encabezado1` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `encabezado2` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `encabezado3` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `encabezado4` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `imagen_izq` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `imagen_der` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `cod_asig_materiales` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `validar_materiales` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `cta_tf` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_tf` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cta_fiel` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_fiel` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cta_laboral` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_laboral` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cta_anticipo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_anticipo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cta_701` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cta_702` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cta_ret_iva` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_ret_iva` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cta_im` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_im` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `cta_bombero` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_bombero` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `moneda` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `cta_multa` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_multa` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_presupuesto` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_compromiso` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_causado` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `version` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `serial` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `consecutivo_iva` int(8) NOT NULL,
  `precompromisos` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `cta_aporte` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `des_aporte` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `consecutivo_RRS` int(8) NOT NULL,
  `consecutivo_ISLR` int(8) NOT NULL,
  `consecutivo_TF` int(8) NOT NULL,
  `consecutivo_IM` int(8) NOT NULL,
  `consecutivo_RP` int(8) NOT NULL,
  `consecutivo_MP` int(11) NOT NULL,
  `pers_adm` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefonofax` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`codigo`, `nomemp`, `departamento`, `presidente`, `periodo`, `cargo`, `nivel`, `desislr`, `ctaisrl`, `desiva`, `ctaiva`, `por_isv`, `compra`, `servicio`, `rif`, `nit`, `direccion`, `ciudad`, `estado`, `telefono`, `por_im`, `por_bomberos`, `lugar`, `sobregirop`, `sobregirof`, `autorizacionodp`, `claveodp`, `contrato`, `gas_dir`, `encabezado1`, `encabezado2`, `encabezado3`, `encabezado4`, `imagen_izq`, `imagen_der`, `cod_asig_materiales`, `validar_materiales`, `cta_tf`, `des_tf`, `cta_fiel`, `des_fiel`, `cta_laboral`, `des_laboral`, `cta_anticipo`, `des_anticipo`, `cta_701`, `cta_702`, `cta_ret_iva`, `des_ret_iva`, `cta_im`, `des_im`, `cta_bombero`, `des_bombero`, `moneda`, `cta_multa`, `des_multa`, `tipo_presupuesto`, `tipo_compromiso`, `tipo_causado`, `version`, `serial`, `consecutivo_iva`, `precompromisos`, `cta_aporte`, `des_aporte`, `consecutivo_RRS`, `consecutivo_ISLR`, `consecutivo_TF`, `consecutivo_IM`, `consecutivo_RP`, `consecutivo_MP`, `pers_adm`, `telefonofax`) VALUES
(1, 'ASYS CONSULTORES DE SISTEMAS C.A.', '', '', '2011', '', '', '', '2.1.1.03.09.01.01.', '', '4.03.18.01.00', '12.00', 1, 1, 'J-304985339', '', 'Av. Principal de Los Ruices, Edificio SiSalud, Caracas.', 'Caracas', 'Miranda', '', '2.00', '10.00', NULL, 'N', 'N', NULL, NULL, 1, 1, '', 'ASYS CONSULTORES DE SISTEMAS C.A.', '', ' ', '../imagenes/pdval-logo.gif', '../imagenes/pdval-logo.gif', '', '', '2.1.1.03.09.01.03.', 'RetenciÃ³n Timbre Fiscal', '2.1.1.03.01.01.08.', 'RetenciÃ³n Fianza Fiel Cumplimiento', '2.1.1.03.01.01.09.', 'RetenciÃ³n Garantia Laboral', '', '', '', '', '2.1.1.03.09.01.02.', 'RetenciÃ³n Impuesto al Valor Agregrado (IVA)', '', 'RetenciÃ³n Impuesto Municipal', '', '', 'Bs.', '2.1.1.03.09.01.0005.', 'RetenciÃ³n Empresa de Produccion Social . EPS', 'Programa', 'SI', 'SI', '', '20110101', 0, 'N', '', '', 0, 0, 0, 0, 0, 0, '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros_generales`
--

DROP TABLE IF EXISTS `parametros_generales`;
CREATE TABLE IF NOT EXISTS `parametros_generales` (
  `cod_empresa` int(32) NOT NULL AUTO_INCREMENT,
  `nombre_empresa` varchar(80) NOT NULL,
  `direccion` varchar(400) NOT NULL,
  `ciudad` varchar(32) NOT NULL,
  `telefonos` varchar(100) NOT NULL,
  `id_fiscal` varchar(20) NOT NULL,
  `rif` varchar(50) NOT NULL,
  `id_fiscal2` varchar(50) NOT NULL,
  `nit` varchar(50) NOT NULL,
  `img_izq` varchar(100) NOT NULL DEFAULT 'logo_selectra.jpg',
  `img_der` varchar(100) DEFAULT NULL,
  `moneda` varchar(50) NOT NULL,
  `contribuyente_formal` tinyint(1) NOT NULL,
  `cantidad_copias` int(32) NOT NULL,
  `dias_vencimiento` int(30) NOT NULL,
  `titulo_precio1` varchar(50) NOT NULL,
  `titulo_precio2` varchar(50) NOT NULL,
  `titulo_precio3` varchar(50) NOT NULL,
  `fecha_ultimo_cierre_mensual` date NOT NULL,
  `precio_menor` int(10) NOT NULL COMMENT 'Debe indicar cual de los tres precios es el menor, Precio 1, Precio 2 o Precio 3',
  `unidad_tributaria` int(3) NOT NULL,
  `clasificador_de_documentos` tinyint(1) NOT NULL COMMENT 'Ã‚Â¿Usar clasificador de Documentos?',
  `nombre_impuesto_principal` varchar(50) NOT NULL,
  `porcentaje_impuesto_principal` decimal(10,2) NOT NULL COMMENT 'Porcentaje de I.V.A.',
  `iva_a` decimal(10,2) NOT NULL,
  `iva_b` decimal(10,2) NOT NULL,
  `iva_c` decimal(10,2) NOT NULL,
  `activar_impuesto2` tinyint(1) NOT NULL,
  `string_impuesto2` varchar(50) NOT NULL,
  `porcentaje_impuesto2` decimal(10,2) NOT NULL,
  `activar_impuesto3` tinyint(1) NOT NULL,
  `string_impuesto3` varchar(50) NOT NULL,
  `porcentaje_impuesto3` decimal(10,2) NOT NULL,
  `contribuyente_especial` tinyint(1) NOT NULL,
  `pprovee_sobr_impu_princ` double(10,2) NOT NULL COMMENT 'procentaje proveedores sobre impuesto principal',
  `pclient_sobr_impu_princ` decimal(10,2) NOT NULL COMMENT 'procentaje clientes sobre impuesto principal',
  `string_clasificador_inventario1` varchar(50) NOT NULL,
  `string_clasificador_inventario2` varchar(50) NOT NULL,
  `string_clasificador_inventario3` varchar(50) NOT NULL,
  `tipo_facturacion` int(1) NOT NULL DEFAULT '0' COMMENT '0->PDF, 1->FISCAL, 2->FORMA LIBRE',
  `swterceroimp` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0->No,1->Si:Spooler Fiscal',
  `impresora_marca` varchar(50) DEFAULT NULL,
  `impresora_modelo` varchar(50) DEFAULT NULL,
  `impresora_serial` varchar(50) DEFAULT NULL,
  `moneda_base` int(11) DEFAULT '1',
  `servicio_fk` int(11) DEFAULT NULL,
  `cuenta_credito_fiscal` varchar(20) NOT NULL COMMENT 'Cuenta presupuesto otros ingresos',
  `cuenta_debito_fiscal` varchar(20) NOT NULL COMMENT 'cuenta presupuesto iva1',
  `cuenta_retencion_iva` varchar(20) NOT NULL COMMENT 'cuenta presupuesto iva2',
  `cuenta_retencion_islr` varchar(20) NOT NULL COMMENT 'cuenta presupuesto iva3',
  `cuenta_retencion_tf` varchar(20) NOT NULL COMMENT 'cuenta presupuesto otros ingresos',
  `cuenta_retencion_im` varchar(20) NOT NULL COMMENT 'cuenta presupuesto perdida',
  `cxc` varchar(20) NOT NULL COMMENT 'cuenta presupuseto cxc',
  `cod_almacen` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `id_tipo_regla_existecia` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `telefonos_fax` varchar(45) NOT NULL DEFAULT '',
  `correo` varchar(45) NOT NULL DEFAULT '',
  `id_ubicacion` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `codigo_siga` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sincronizacion_inv` int(11) NOT NULL DEFAULT '0' COMMENT '1 = activa 0=no activa',
  `fortinet` varchar(11) NOT NULL,
  `cta_captadora` varchar(60) NOT NULL,
  `cta_sobrante` varchar(60) NOT NULL,
  `venta_pyme` tinyint(4) NOT NULL COMMENT 'si el punto vende por el pyme. 0 vende, 1 vende por pos, 2 ambas',
  `estado` int(4) NOT NULL COMMENT 'Estado al que pertenece el punto de venta',
  `servidor` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Tipo de Servidor, 1=Linux, 0=Windows',
  `codigo_kardex` tinyint(1) NOT NULL COMMENT '1=activa el pedido de codigo; 0=inactiva el pedido',
  `max_caja_principal` decimal(11,3) NOT NULL DEFAULT '0.000' COMMENT 'maxima cantidad que puede permanecer en la caja principal',
  `fina_limite_max` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`cod_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `parametros_generales`
--

INSERT INTO `parametros_generales` (`cod_empresa`, `nombre_empresa`, `direccion`, `ciudad`, `telefonos`, `id_fiscal`, `rif`, `id_fiscal2`, `nit`, `img_izq`, `img_der`, `moneda`, `contribuyente_formal`, `cantidad_copias`, `dias_vencimiento`, `titulo_precio1`, `titulo_precio2`, `titulo_precio3`, `fecha_ultimo_cierre_mensual`, `precio_menor`, `unidad_tributaria`, `clasificador_de_documentos`, `nombre_impuesto_principal`, `porcentaje_impuesto_principal`, `iva_a`, `iva_b`, `iva_c`, `activar_impuesto2`, `string_impuesto2`, `porcentaje_impuesto2`, `activar_impuesto3`, `string_impuesto3`, `porcentaje_impuesto3`, `contribuyente_especial`, `pprovee_sobr_impu_princ`, `pclient_sobr_impu_princ`, `string_clasificador_inventario1`, `string_clasificador_inventario2`, `string_clasificador_inventario3`, `tipo_facturacion`, `swterceroimp`, `impresora_marca`, `impresora_modelo`, `impresora_serial`, `moneda_base`, `servicio_fk`, `cuenta_credito_fiscal`, `cuenta_debito_fiscal`, `cuenta_retencion_iva`, `cuenta_retencion_islr`, `cuenta_retencion_tf`, `cuenta_retencion_im`, `cxc`, `cod_almacen`, `id_tipo_regla_existecia`, `telefonos_fax`, `correo`, `id_ubicacion`, `codigo_siga`, `sincronizacion_inv`, `fortinet`, `cta_captadora`, `cta_sobrante`, `venta_pyme`, `estado`, `servidor`, `codigo_kardex`, `max_caja_principal`, `fina_limite_max`) VALUES
(1, 'CEALCO', 'LOS SAMANES', 'BOLIVAR', '0212-5557156', 'RIF', 'G-200100249', 'NIT', '00000000', 'pdval-logo.gif', 'pdval-logo.gif', 'Bs.', 0, 10, 30, 'Precio Detal', 'Precio Empleado', 'Precio al Mayor', '2010-12-31', 1, 170, 0, 'IVA', '12.00', '8.00', '24.00', '0.00', 1, 'ISLR', '0.00', 1, 'ICA', '0.00', 0, 50.00, '50.00', 'Departamento', 'Grupo', 'Linea', 1, 0, 'bixolon', 'SRP350', 'Z188046405', 14, 0, '1', '2', '3', '4', '5', '6', '7', 2, 0, '', '', 6, 890, 0, '', '01020552220000023032', '01020552280000037251', 2, 7, 0, 0, '150.000', '8.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

DROP TABLE IF EXISTS `pedido`;
CREATE TABLE IF NOT EXISTS `pedido` (
  `id_pedido` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_pedido` varchar(32) NOT NULL DEFAULT 'S/I',
  `id_cliente` int(32) NOT NULL,
  `cod_vendedor` int(32) NOT NULL,
  `id_factura` int(32) NOT NULL,
  `fechaPedido` date NOT NULL DEFAULT '0000-00-00',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuentosItemPedido` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montoItemsPedido` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ivaTotalPedido` decimal(10,2) NOT NULL DEFAULT '0.00',
  `TotalTotalPedido` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad_items` int(32) NOT NULL DEFAULT '0',
  `totalizar_sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_parcial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_operacion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pdescuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descuento_global` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_base_imponible` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_general` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_total_retencion` decimal(10,2) NOT NULL,
  `cod_estatus` int(32) UNSIGNED DEFAULT NULL,
  `fecha_pago` date NOT NULL DEFAULT '0000-00-00',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(40) NOT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `fk_cod_vendedor2` (`cod_vendedor`),
  KEY `fk_usuario` (`usuario_creacion`),
  KEY `fk_id_cliente` (`id_cliente`),
  KEY `fk_cod_estatus` (`cod_estatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_pagos`
--

DROP TABLE IF EXISTS `pedidos_pagos`;
CREATE TABLE IF NOT EXISTS `pedidos_pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_pedido` int(11) NOT NULL,
  `forma_pago` varchar(20) NOT NULL,
  `nro_tarjeta` varchar(20) NOT NULL,
  `tipo_tarjeta` varchar(20) NOT NULL,
  `id_banco` int(11) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `nro_1x1000` int(11) NOT NULL,
  `nro_retencion` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `id_pagos_consolidados` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

DROP TABLE IF EXISTS `pedido_detalle`;
CREATE TABLE IF NOT EXISTS `pedido_detalle` (
  `id_detalle_pedido` int(32) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(32) UNSIGNED DEFAULT NULL,
  `id_item` int(32) UNSIGNED DEFAULT NULL,
  `cod_item` varchar(20) NOT NULL,
  `_item_almacen` int(32) DEFAULT NULL,
  `_item_descripcion` varchar(32) NOT NULL,
  `_item_cantidad` decimal(32,0) NOT NULL DEFAULT '0',
  `_item_preciosiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_montodescuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_piva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalsiniva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `_item_totalconiva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detalle_pedido`),
  KEY `fk_id_factura` (`id_pedido`),
  KEY `id_item` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle_formapago`
--

DROP TABLE IF EXISTS `pedido_detalle_formapago`;
CREATE TABLE IF NOT EXISTS `pedido_detalle_formapago` (
  `cod_pedido_detalle_formapago` int(32) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(32) UNSIGNED NOT NULL DEFAULT '0',
  `totalizar_monto_cancelar` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_saldo_pendiente` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_cambio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_efectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `opt_cheque` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_cheque` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nombre_banco` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `opt_tarjeta` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_tarjeta` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_tipo_tarjeta` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_formapago',
  `opt_deposito` tinyint(1) NOT NULL DEFAULT '0',
  `totalizar_monto_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_nro_deposito` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_banco_deposito` int(32) NOT NULL DEFAULT '0' COMMENT 'cod_banco',
  `fecha_vencimiento` date NOT NULL,
  `observacion` varchar(600) NOT NULL,
  `persona_contacto` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `opt_otrodocumento` tinyint(1) NOT NULL,
  `totalizar_tipo_otrodocumento` int(32) NOT NULL COMMENT 'Tipo de documento',
  `totalizar_monto_otrodocumento` decimal(10,2) NOT NULL,
  `totalizar_nro_otrodocumento` int(32) NOT NULL,
  `totalizar_banco_otrodocumento` int(32) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(60) NOT NULL,
  PRIMARY KEY (`cod_pedido_detalle_formapago`),
  KEY `id_factura` (`id_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_facturas_consolidadas`
--

DROP TABLE IF EXISTS `pedido_facturas_consolidadas`;
CREATE TABLE IF NOT EXISTS `pedido_facturas_consolidadas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_maestro` int(11) DEFAULT NULL,
  `total_monto` decimal(11,2) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_facturas_consolidadas_maestra`
--

DROP TABLE IF EXISTS `pedido_facturas_consolidadas_maestra`;
CREATE TABLE IF NOT EXISTS `pedido_facturas_consolidadas_maestra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_monto` decimal(11,2) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_impuestos`
--

DROP TABLE IF EXISTS `pedido_impuestos`;
CREATE TABLE IF NOT EXISTS `pedido_impuestos` (
  `id_pedido_impuestos` int(32) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(32) UNSIGNED NOT NULL,
  `totalizar_base_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_pbase_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_descripcion_base_retencion` int(32) NOT NULL DEFAULT '0' COMMENT 'fk_cod_impuesto_iva',
  `cod_impuesto_iva` int(32) NOT NULL,
  `totalizar_monto_iva2` decimal(10,2) NOT NULL,
  `totalizar_monto_1x1000` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_creacion` varchar(50) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pedido_impuestos`),
  KEY `id_factura` (`id_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_centrales`
--

DROP TABLE IF EXISTS `productos_centrales`;
CREATE TABLE IF NOT EXISTS `productos_centrales` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `codigo_barras` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_kit`
--

DROP TABLE IF EXISTS `productos_kit`;
CREATE TABLE IF NOT EXISTS `productos_kit` (
  `id_item_padre` varchar(10) NOT NULL,
  `id_item_hijo` varchar(10) NOT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_item_padre`,`id_item_hijo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `productos_kit`
--

INSERT INTO `productos_kit` (`id_item_padre`, `id_item_hijo`, `cantidad`) VALUES
('27', '8', '1.00'),
('27', '9', '1.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id_proveedor` int(32) NOT NULL AUTO_INCREMENT,
  `cod_proveedor` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `direccion` varchar(300) DEFAULT NULL,
  `telefonos` varchar(100) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `rif` varchar(20) DEFAULT NULL,
  `nit` varchar(20) DEFAULT NULL,
  `estatus` varchar(10) DEFAULT NULL,
  `cod_tipo_proveedor` varchar(25) DEFAULT NULL,
  `clase_proveedor` varchar(25) DEFAULT NULL,
  `cod_entidad` int(11) DEFAULT NULL,
  `cod_especialidad` int(4) DEFAULT NULL,
  `fecha_creacion` date DEFAULT '0000-00-00',
  `usuario_creacion` varchar(50) DEFAULT NULL,
  `cuenta_contable` varchar(25) DEFAULT NULL,
  `compania` varchar(200) DEFAULT NULL,
  `mostrar` int(11) DEFAULT NULL,
  `cod_impuesto_proveedor` int(11) DEFAULT NULL,
  `foto` varchar(60) DEFAULT NULL,
  `registrado_siga` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no registrado, 1=registrado',
  UNIQUE KEY `id_proveedor` (`id_proveedor`),
  UNIQUE KEY `rif` (`rif`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `cod_proveedor`, `descripcion`, `direccion`, `telefonos`, `fax`, `email`, `rif`, `nit`, `estatus`, `cod_tipo_proveedor`, `clase_proveedor`, `cod_entidad`, `cod_especialidad`, `fecha_creacion`, `usuario_creacion`, `cuenta_contable`, `compania`, `mostrar`, `cod_impuesto_proveedor`, `foto`, `registrado_siga`) VALUES
(1, 00010, 'CEALCO', '', '0244-3954251 ', '', '', 'G-20009883-0', ' ', 'A', '', '1', 1, 79, '0000-00-00', 'asys', '', '', 0, 1, '', 0),
(2, 00011, 'PDVAL ', '', '0212-5557001', '', '', 'G-20010024-9', '', 'A', '', '1', 1, 0, '0000-00-00', 'asys', '', '', NULL, 1, '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores_siga`
--

DROP TABLE IF EXISTS `proveedores_siga`;
CREATE TABLE IF NOT EXISTS `proveedores_siga` (
  `rifpro` varchar(19) DEFAULT NULL,
  `nompro` varchar(116) DEFAULT NULL,
  `nitpro` varchar(1) DEFAULT NULL,
  `dirpro` varchar(100) DEFAULT NULL,
  `telpro` varchar(30) DEFAULT NULL,
  `faxpro` varchar(15) DEFAULT NULL,
  `email` varchar(49) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos_venta`
--

DROP TABLE IF EXISTS `puntos_venta`;
CREATE TABLE IF NOT EXISTS `puntos_venta` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id autoincrementable',
  `codigo_siga_punto` varchar(6) NOT NULL COMMENT 'Codigo Siga del Punto de Venta',
  `nombre_punto` varchar(120) NOT NULL COMMENT 'Nombre del Punto de Venta',
  `direccion_punto` varchar(500) NOT NULL COMMENT 'Direccion del Punto de Venta',
  `codigo_estado_punto` varchar(4) NOT NULL COMMENT 'Codigo del Estado',
  `tipo_sincro` int(1) NOT NULL COMMENT 'Tipo de Sincronizacion al servidor central del punto de venta',
  `estatus` varchar(1) NOT NULL DEFAULT 'A',
  `ip_punto_venta` varchar(50) NOT NULL COMMENT 'Ip punto de Venta',
  `capacidad_frio` int(11) NOT NULL COMMENT 'Capacidad de Alamcenaje en Frio',
  `capacidad_seco` int(11) NOT NULL COMMENT 'Capacidad de Alamcenaje en SECO',
  `id_tipo` int(11) NOT NULL DEFAULT '6',
  `numero_cajas` int(11) NOT NULL,
  `numero_servidores` int(11) NOT NULL,
  `tipo_servidor` varchar(100) NOT NULL,
  `tipo_conexion` varchar(100) NOT NULL,
  `velocidad_conexion` varchar(100) NOT NULL,
  `proveedor_conexion` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codigo_siga_index` (`codigo_siga_punto`),
  KEY `codigo_estado_punto` (`codigo_estado_punto`),
  KEY `cod_sig_pto` (`codigo_siga_punto`)
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8 COMMENT='Datos del los Punto de Venta';

--
-- Volcado de datos para la tabla `puntos_venta`
--

INSERT INTO `puntos_venta` (`id`, `codigo_siga_punto`, `nombre_punto`, `direccion_punto`, `codigo_estado_punto`, `tipo_sincro`, `estatus`, `ip_punto_venta`, `capacidad_frio`, `capacidad_seco`, `id_tipo`, `numero_cajas`, `numero_servidores`, `tipo_servidor`, `tipo_conexion`, `velocidad_conexion`, `proveedor_conexion`) VALUES
(1, '000561', 'PUNTO DE VENTA -CEMENTO ANDINO - TRUJILLO', '', '0021', 2, 'A', '10.249.0.26', 0, 0, 1, 1, 0, '1', '1', '1', '1'),
(2, '000681', 'PUNTO DE VENTA - PLAZA DE TOROS - CARABOBO', 'PROLONGACIÃ“N AVENIDA LA FERIA. SECTOR PLAZA DE TOROS. FRENTE A LA MONUMENTAL DE VALENCIA. MUNICIPIO VALENCIA. PARROQUIA SANTA ROSA. ESTADO CARABOBO', '0008', 1, 'A', '173.8.7.20', 0, 0, 2, 2, 1, '1', '1', '1', '1'),
(3, '000682', 'PUNTO DE VENTA - CIUDAD CHAVEZ - CARABOBO', 'SECTOR SANTA INES. DIAGONAL AL HIPÃ“DROMO DE VALENCIA. MUNICIPIO VALENCIA. PARROQUIA MIGUEL PEÃ‘A. ESTADO CARABOBO', '0008', 2, 'A', '173.8.6.20', 0, 0, 1, 4, 1, '1', '1', '1', '1'),
(4, '000624', 'PUNTO DE VENTA - EL NAZARENO-ESTADO ZULIA', 'Avda. Urdaneta c/c calle sucre Montalban Edo Carabobo', '0008', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(5, '000118', 'PUNTO DE VENTA - KANAIMO - BOLIVAR', 'Casco Central Frente A La Escuela Tecnica, Canaima', '0007', 1, 'A', '173.7.4.1', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(6, '000564', 'PUNTO DE VENTA - LOS SAMANES - BOLIVAR', '', '0007', 1, 'A', '173.7.3.1', 0, 0, 2, 6, 1, '1', '1', '1', '1'),
(7, '000122', 'PUNTO DE VENTA - INDUSTRIAS DIANA - CARABOBO', 'AV. HENRRI FORT. ZONA INDUSTRIAL 2 FRENTE A LA FORD, DIAGONAL A LA FAIZER. VALENCIA, EDO CARABOBO.', '0008', 2, 'I', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(8, '000274', 'PUNTO DE VENTA - MICHELENA - TACHIRA', 'CARRERA  19 CON CALLE 24 Y 25', '0020', 2, 'A', '173.20.3.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(9, '000270', 'PUNTO DE VENTA - BOSQUE CUA - MIRANDA', 'AV. PRINCIPAL DE QUEBRADA DE CÃšA RES. ARAGUANEY M 1 LOCAL 24', '0015', 2, 'A', '173.15.3.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(10, '000109', 'PUNTO DE VENTA - JOSE ANTONIO ANZOATEGUI - ANZOATEGUI', 'AV. JOSÃ‰ ANTONIO ANZOATEGUI ANACO', '0003', 2, 'A', '173.8.2.40', 0, 0, 2, 2, 1, '1', '1', '1', '1'),
(11, '000115', 'PUNTO DE VENTA - LA TIENDITA - BARINAS', 'CLUB RECREACIONAL DE PDVSA - BARINAS EDO. BARINAS', '0006', 1, 'I', '173.6.9.9', 0, 0, 1, 0, 0, '', '', '', ''),
(12, '000556', 'PUNTO DE VENTA - LA FAPET - TACHIRA ', 'BARRIO LA  FAPET 23 DE ENERO PARTE BAJA', '0020', 1, 'A', '173.20.6.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(13, '000199', 'PUNTO DE VENTA - LA CONCEPCIÃ“N - ZULIA', 'KM 8, SEDE DE ENELVEN, LA CONCEPCIÃ“N. MCPIO J.E LOSSADA. CEDE DE ENELVEN', '0024', 2, 'A', '173.24.6.30', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(14, '000300', 'PUNTO DE VENTA - CHUSPA - VARGAS', 'CHUSPA', '0022', 2, 'A', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(15, '000648', 'PUNTO DE VENTA - BRISAS DEL AREOPUERTO- VARGAS', 'AV. INTERCOMUNAL DEL AEROPUERTO INTERNACIONAL SIMÃ“N BOLÃVAR, URB. BRISAS DEL AEROPUERTO, AL FRENTE DEL AEROPUERTO INTERNACIONAL, PARROQUIA URIMARE, EDO. VARGAS.', '0022', 1, 'A', '173.22.4.4', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(16, '000107', 'PUNTO DE VENTA -  ANACO - ANZOATEGUI', 'CAMPO NORTE, ANTIGUO COMISARIATO  PDVSA, ANACO', '0003', 1, 'A', '173.3.8.30', 0, 0, 4, 2, 1, '1', '1', '1', '1'),
(17, '000282', 'PUNTO DE VENTA - CPS LA PERIQUERA - APURE', 'AV.NEPTALI QUINTERO, CENTRO DE PARTICIPACIÃ“N SOCIALISTA  LA PERIQUERA', '0004', 1, 'A', '173.4.6.5', 0, 0, 1, 2, 1, '1', '2', '1', '5'),
(18, '000562', 'PUNTO DE VENTA - INDEPENDENCIA - BARINAS', '', '0006', 1, 'A', '173.6.3.9', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(19, '000506', 'PUNTO DE VENTA - SAN TOME - ANZOATEGUI', '', '0003', 1, 'A', '173.3.12.210', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(20, '000544', 'PUNTO DE VENTA - LA ALGODONERA - APURE', 'ESTADAO APURE', '0004', 1, 'A', '173.4.2.15', 0, 0, 1, 3, 1, '1', '2', '1', '3'),
(21, '000111', 'PUNTO DE VENTA - EL TIGRITO - ANZOATEGUI', 'AV. FERNANDEZ PADILLA CRUCE CON AV. MARIÃ‘O TIENDA DE LA ESTACION DE SERVICIO TIGRITO', '0003', 2, 'I', '173.8.3.200', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(23, '000301', 'PUNTO DE VENTA - GUARACARUMBO - VARGAS', '', '0022', 1, 'A', '173.22.1.7', 0, 0, 1, 4, 1, '1', '1', '1', '1'),
(24, '000551', 'PUNTO DE VENTA - PDVAL CEALCO - ARAGUA', 'ZONA INDUSTRIAL CORINZA, AV. GRAN MARISCAL DE AYACUCHO, PARCELA 1, ALMACEN CEALCO CORINZA ZONA INDUSTRIAL LAS VEGAS CAGUA ESTADO ARAGUA', '0005', 2, 'A', '', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(25, '000529', 'PUNTO DE VENTA - PDVAL BAILADORES - MERIDA', 'MUNICIPIO RIVAS DAVILA DEL ESTADO MERIDA, ESPECIFICAMENTE EN EL COMPLEJO CAMPESINO ANDINO LA GRANJA', '0014', 1, 'A', '173.14.5.10', 0, 0, 1, 2, 2, '1', '3', '2', '3'),
(26, '000156', 'PUNTO DE VENTA - LAGUNILLAS - MERIDA', 'AV. AGUA DE URAO DENTRO DEL MERCADO MUNICIPAL AL LADO DEL TERMINAL DE PASAJERO', '0014', 2, 'A', '173.14.12.10', 0, 0, 1, 3, 1, '1', '3', '2', '3'),
(27, '000553', 'PUNTO DE VENTA - BARINITAS - BARINAS', 'SECTOR BELLA VISTA, CARRERA 3 Y 4, ENTRE CALLES 17 Y 18. BARINAS', '0006', 1, 'A', '173.6.2.9', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(28, '000534', 'PUNTO DE VENTA  VISTA AL SOL - BOLIVAR', 'AV MANUEL PIAR CENTRO COMERCIAL LA ECONOMICA, FRENTE AL MERCADO DE CHIRICA, SECTOR VISTA AL SOL', '0007', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(29, '000147', 'PUNTO DE VENTA - LOS COCOS - GUARICO', 'AV. ROMULO GALLEGOS. CLUB MILITAR LOS COCOS. A CUADRA Y MEDIA DESPUES DE LA REDOMA. AL LADO DE LA VILLA OLIMPICA 0246-432-29-33', '0012', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(30, '000280', 'PUNTO DE VENTA - TARIBA - TACHIRA', 'CALLE PRINCIPAL FLOR DE PATRIA, MUNICIPIO PAMPAN, ESTADO TRUJILLO', '0020', 2, 'A', '173.20.12.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(31, '000509', 'PUNTO DE VENTA - SAN LORENZO - BOLIVAR', '', '0007', 1, 'A', '173.7.2.1', 0, 0, 2, 4, 1, '1', '1', '1', '1'),
(32, '000403', 'PUNTO DE VENTA - COLONCITO - TACHIRA', 'PUNTO DE VENTA - COLONCITO - TACHIRA', '0020', 1, 'A', '173.20.4.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(33, '000281', 'PUNTO DE VENTA - BARRIO EL CARMEN - TACHIRA', 'SECTOR LA CONCORDIA CARRERA 10 NUMERO 1-24 BARIO EL CARMEN', '0020', 1, 'A', '173.20.1.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(34, '000400', 'PUNTO DE VENTA - SEBORUCO - TACHIRA', 'PUNTO DE VENTA - SEBORUCO - TACHIRA', '0020', 1, 'A', '173.20.9.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(35, '000405', 'PUNTO DE VENTA - BOROTA - TACHIRA', 'PUNTO DE VENTA - BOROTA - TACHIRA', '0020', 2, 'A', '173.20.8.10', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(36, '000214', 'PUNTO DE VENTA - GURI - BOLIVAR', 'PUNTO DE VENTA - GURI - BOLIVAR', '0007', 1, 'A', '173.7.1.1', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(38, '000217', 'PUNTO DE VENTA - TIA JUANA - ZULIA', 'CAMPO VENEZUELA AV. 3 ANTIGUO COMISARIATO LAGOVEN, MUNICIPIO SIMON BOLIVAR', '0024', 2, 'A', '173.24.2.54', 0, 0, 4, 5, 1, '1', '1', '1', '1'),
(39, '000099', 'PUNTO DE VENTA - CVG BAUXILUM LOS PIJIGUAOS - AMAZONAS', 'MUNICIPIO CEDEÃ‘O, CVG BAUXILUM LOS PIJIGUAOS.', '0002', 2, 'A', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(40, '000543', 'PUNTO DE VENTA - SIMON BOLIVAR - APURE', 'CALLE SUCRE FRENTE AL GRUPO ESCOLAR ARAMENDI PARROQUIA GUASDALITO ESTADO APURE', '0004', 2, 'A', '173.4.7.5', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(41, '000125', 'PUNTO DE VENTA - FLOR AMARILLO - CARABOBO', 'SECTOR FLOR AMARRILLO URB EL RECERO. MUNICIPIO RAFAEL URDANETA. VALENCIA', '0008', 1, 'A', '173.8.1.20', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(42, '000110', 'PUNTO DE VENTA -  SAN MATEO - ANZOATEGUI', 'AV EL BOULEVAR, FRENTE AL MIRADOR TURISTICO SIMON BOLIVAR.', '0003', 2, 'A', '', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(43, '000124', 'PUNTO DE VENTA - CIRCULO MILITAR - CARABOBO', 'AV. UNIVERSIDAD NAGUANAGUA AL LADO DEL FUERTE PARAMACAY DIAGONAL A LA VILLA OLIMPICA', '0008', 2, 'A', '173.8.4.20', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(44, '000121', 'PUNTO DE VENTA - UNICENTRO GUACARA - CARABOBO', 'CALLE 1 NARANJILLO CENTRO COMERCIAL UNICENTRO GUACARA. CARRETERRA NACIONAL VIA LOS GUALLOS. TELF. 0245-5642813', '0008', 2, 'I', '', 0, 0, 4, 0, 0, '1', '1', '1', '1'),
(45, '000120', 'PUNTO DE VENTA - MI VIEJO MERCADO - CARABOBO', 'ANTIGUO SUPERMERCADO FAMOSO. CALLE GIRARDOT CON AV. MONTES DE OCA. FRENTE A MI VIEJO MERCADO. CENTRO VALENCIA', '0008', 1, 'A', '173.8.5.20', 0, 0, 4, 10, 1, '1', '1', '1', '1'),
(46, '000327', 'PUNTO DE VENTA - REFINERIA EL PALITO - CARABOBO', 'REFINERIA EL PALITO', '0008', 2, 'A', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(47, '000256', 'PUNTO DE VENTA - BOQUERON - MONAGAS', 'AVENIDA PRINCIPAL DE BOQUERON SECTOR LOS MACOS DIAGONAL AL MODULO POLICIAL.', '0016', 1, 'A', '173.16.5.10', 0, 0, 3, 5, 1, '1', '2', '1', '3'),
(48, '000263', 'PUNTO DE VENTA - BELLO MONTE - MONAGAS', 'CALLE PRINCIPAL EN LAS COLINAS DE BELLO MONTE DETRÃS DEL MODULO DE BARRIO ADENTRO, CARIPITO', '0016', 1, 'A', '173.16.4.10', 0, 0, 1, 2, 1, '1', '2', '1', '3'),
(49, '000264', 'PUNTO DE VENTA - EL RINCON - MONAGAS', 'AV. MIRANDA CRUCE CON CALLE MARIÃ‘O DIAGONAL A LA IGLESIA SAN PEDRO APÃ“STOL', '0016', 1, 'A', '173.16.9.10', 0, 0, 1, 3, 1, '1', '1', '3', '3'),
(50, '000171', 'PUNTO DE VENTA - PUNTA DE MATA I - MONAGAS', 'CALLE PROGRESO,TRANSVERSAL PROGRESO', '0016', 1, 'A', '173.16.1.10', 0, 0, 4, 5, 1, '1', '2', '1', '3'),
(51, '000583', 'PUNTO DE VENTA - EL FURRIAL - MONAGAS', 'Brisas del morichal, julio camino, san jose de la ceiba, patriotas del centro y el furrial ', '0016', 1, 'A', '173.16.7.10', 0, 0, 3, 7, 1, '1', '2', '1', '5'),
(52, '000548', 'PUNTO DE VENTA - SIMON BOLIVAR - PORTUGUESA', 'COMPLEJO HABITACIONAL SIMON BOLIVAR,  SECTOR BOCA DE MONTE ,  ACARIGUA, MUNICIPIO PAEZ', '0018', 1, 'A', '173.18.2.37', 0, 0, 1, 3, 1, '1', '3', '2', '3'),
(53, '000183', 'PUNTO DE VENTA - BATALLON RIVAS DAVILA - TRUJILLO', 'A.V. Cristobal Mendoza dentro de las Instalaciones del batallon de infanteria Rivas Davila TELF CERCANO 0272 - 2364092', '0021', 2, 'A', '173.21.1.5', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(54, '000210', 'PUNTO DE VENTA - LA MORA- ARAGUA', 'LA VICTORIA', '0005', 1, 'A', '173.5.3.10', 0, 0, 3, 8, 1, '1', '1', '1', '1'),
(55, '000538', 'PUNTO DE VENTA - SUPER PDVAL LA VILLA - ARAGUA', 'CALLE DOCTOR MANZO CRUCE CON CALLE LISANDRO HERNANDEZ, LOCAL 19, VILLA DE CURA, MUNICIPIO ZAMORA, PARROQUIA VILLA DE CURA, ESTADO ARAGUA.\r\n\r\n', '0005', 2, 'A', '173.50.0.10', 0, 0, 3, 10, 1, '1', '1', '1', '1'),
(56, '000257', 'PUNTO DE VENTA - ESEM - MONAGAS', 'AVENIDA ALIRIO UGARTE PELAYO. EDIFICIO ESEM.PDVSA', '0016', 2, 'I', '', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(57, '000672', 'PUNTO DE VENTA - ELIECER OTAIZA - PORTUGUESA', 'AVENIDA PRINCIPAL DE DURIGUA CENTRO. SECTOR DURIGUA. ENTRE CALLES 6 Y 7. MUNICIPIO PÃEZ. ACARIGUA. ESTADO PORTUGUESA.', '0018', 1, 'A', '173.18.5.10', 0, 0, 3, 4, 1, '1', '2', '1', '5'),
(58, '000225', 'PUNTO DE VENTA - BASE LOGISTICA - ARAGUA', 'AV. BOLIVAR FRENTE AL IPSFA', '0005', 1, 'A', '173.5.5.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(59, '000226', 'PUNTO DE VENTA - LOMAS DE NIQUEL - ARAGUA', 'AUTOPISTA REGIONAL DEL CENTRO K.M.54, NUEVA VIA A TIARA K.M. 54 ESTADO MIRANDA.', '0005', 2, 'A', '', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(60, '000519', 'PUNTO DE VENTA - TINAQUILLO - COJEDES', 'TINAQUILLLO ESTADO COJEDES', '0009', 1, 'A', '173.9.3.10', 0, 0, 3, 6, 1, '1', '1', '1', '1'),
(61, '000158', 'PUNTO DE VENTA - JOSE LAURENCIO SILVA - COJEDES', 'CALLE SILVA CRUCE CON AV. JOSE LAURENCIO SILVA FRENTE INSTITUTO NACIONAL DE NUTRICIÃ’N SAN CARLOS ESTADO COJEDES.', '0009', 2, 'I', '', 0, 0, 1, 0, 0, '', '', '', ''),
(62, '000144', 'PUNTO DE VENTA - BASE NAVAL - FALCON', 'Av Gonzalez Base Naval: Mariscal Juan Crisostomo Falcon. Tlf. 0269 2450780', '0011', 1, 'A', '173.11.3.2', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(63, '000235', 'PUNTO DE VENTA - BUENA VISTA - LARA', 'CALLE LA QUIBOREÃ‘A. ESQUINA  LIZCANO BUENA VISTA', '0013', 1, 'A', '173.13.10.4', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(64, '000232', 'PUNTO DE VENTA - 14 BRIGADA - LARA', 'AV. LOS LEONES ENTRE AV. VENEZUELA Y AV. LIBERTADOR', '0013', 1, 'A', '173.13.1.15', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(65, '000234', 'PUNTO DE VENTA -  BASE AEREA - LARA', 'AV. HORCONES', '0013', 1, 'A', '173.13.9.4', 0, 0, 3, 6, 1, '1', '1', '1', '1'),
(66, '000265', 'PUNTO DE VENTA - BOCA DEL RIO - NUEVA ESPARTA', 'CALLE SECTOR LOS CARACAS, JUNTO AL CEMENTERIO BOCA DEL RIO.', '0017', 1, 'A', '173.17.3.12', 0, 0, 3, 5, 1, '1', '1', '1', '1'),
(67, '000299', 'PUNTO DE VENTA - SANTA ANA - NUEVA ESPARTA', 'CALLE SUCRE ANTIGUO MERCADO MUNICIPAL SANTA ANA', '0017', 1, 'A', '173.17.4.10', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(68, '000505', 'PUNTO DE VENTA - SAN DIEGO DE CABRUTICA - ANZOATEGUI', 'PUNTO DE VENTA - SAN DIEGO DE CABRUTICA - ANZOATEGUI', '0003', 2, 'A', '173.3.2.10', 0, 0, 2, 0, 0, '1', '1', '1', '1'),
(69, '000108', 'PUNTO DE VENTA - 1ERO DE MAYO - ANZOATEGUI', 'CALLE 1ERO DE MAYO, CRUCE CON VARGAS Y CABALLERO SARMIENTO FRENTE A PELUQUERIA UNISEX ANACO', '0003', 1, 'A', '173.3.5.1', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(70, '000254', 'PUNTO DE VENTA - PASEO COLON - ANZOATEGUI', 'PROLONGACION DE AV. PASEO COLON, FRENTE DE FORD MOTORS, PLC.', '0003', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(71, '000105', 'PUNTO DE VENTA - INTI BARCELONA - ANZOATEGUI', 'AV. FINAL CARACAS, EDIF. DEL INSTITUTO NACIONAL DE TIERRAS. 1ERA CARRETERA ANT SEDE IAN VIA CIUDAD BOLIVAR. BARCELONA', '0003', 2, 'I', '', 0, 0, 1, 1, 1, '1', '1', '1', '1'),
(72, '000104', 'PUNTO DE VENTA - MERCADO CAMPESINO - ANZOATEGUI', 'AV. RAUL LEONI, ZONA INDUSTRIAL LOS MENTONES, CENTRO COMERCIAL MERCADO CAMPESINO. FRENTE A LA TIENDA PRECA.', '0003', 1, 'A', '173.3.10.1', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(73, '000288', 'PUNTO DE VENTA - PASEO CATIA - DISTRITO CAPITAL', 'ENTRE AV. BOLIVAR Y CALLE EL CUARTEL (ANTIGUA FABRICA DE CALZADOS PASEO), CATIA, PARROQUIA CATIA', '0001', 1, 'I', '173.1.3.100', 0, 0, 6, 0, 0, '', '', '', ''),
(74, '000287', 'PUNTO DE VENTA - FUERTE TIUNA - DISTRITO CAPITAL', 'FUERTE TIUNA, PARROQUI EL VALLE', '0001', 2, 'A', '173.1.2.100', 0, 0, 3, 0, 0, '', '', '', ''),
(75, '000547', 'PUNTO DE VENTA - BASE AEREA LA CARLOTA - DISTRITO CAPITAL', 'AUTOPISTA FRANCISCO FAJARDO, LUEGO DEL DISTRIBUIDOR ALTAMIRA, PORTON NORTE DE LA BASE AEREA GENERALISIMO FRANCISCO DE MIRANDA - CARACAS', '0001', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(76, '000162', 'PUNTO DE VENTA - ALI PRIMERA EL TAMBOR - MIRANDA', 'CENTRO DE ECONOMIA POPULAR ALI PRIMERA EL TAMBOR LOS TEQUES ESTADO MIRANDA.', '0015', 1, 'A', '173.15.1.21', 0, 0, 1, 5, 1, '1', '1', '1', '1'),
(77, '000161', 'PUNTO DE VENTA - INTEVEP - MIRANDA', 'ENTRADA CARRETERA VIEJA CARACAS LOS TEQUES URB STA ROSA', '0015', 2, 'A', '129.90.70.130', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(78, '000537', 'PUNTO DE VENTA - CARPA DE CAUCAGUA - MIRANDA', 'CALLE AREVALO GONZALEZ- FRENTE A LA PLAZA BOLIVAR DE CAUCAGUA MUNICIPIO ACEVEDO', '0015', 2, 'I', '', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(79, '000192', 'PUNTO DE VENTA - BACHAQUERO - ZULIA', 'URB. CAMPO PROGRESO CLUB RECREATIVO DE PDVSA. PTO DE REF. COMISARIATO BACHAQUERO', '0024', 1, 'I', '173.24.2.10', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(80, '000058', 'PUNTO DE VENTA - LAGUNILLAS - ZULIA', 'CARRETERA ANTIGUO COMISARIATO DE PDVSA. AL LADO SUPERMERCADO CADA. LAGUNILLAS. EDO. ZULIA.', '0024', 2, 'A', '', 0, 0, 4, 5, 1, '1', '1', '1', '1'),
(81, '000196', 'PUNTO DE VENTA - CORPOZULIA - ZULIA', 'AV. SANTA RITA FRENTA A LA CLÃNICA SAN LUIS. MARCAIBO', '0024', 1, 'A', '173.24.4.100', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(82, '000207', 'PUNTO DE VENTA - NUEVA LUCHA - ZULIA', 'MUNICIPIO MARA, SECTOR NUEVA LUCHA, AL LADO DEL COMANDO DE LA GUERDIA NACIONAL', '0024', 2, 'A', '192.168.0.100', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(83, '000304', 'PUNTO DE VENTA - FUERTE MARA - ZULIA', 'AV PRINCIPAL CAMPO MARA SECTOR FUERTE MARA DENTRO DEL FUERTE MARA', '0024', 2, 'A', '192.168.1.200', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(84, '000201', 'PUNTO DE VENTA  - SANTA BARBARA - MERIDA', 'MUNICIPIO COLÃ“N, PARROQUIA SANTA BARBARA, MERCADO MUNICIPAL DE SANTA BÃRBARA. AVENIDA BOLÃVAR SANTA BARBARA', '0014', 2, 'I', '', 0, 0, 6, 0, 0, '1', '3', '1', '3'),
(85, '000634', 'PUNTO DE VENTA - MONTALBAN - CARABOBO', '', '0008', 1, 'A', '173.8.8.20', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(86, '000292', 'PUNTO DE VENTA - BOSQUE VALLE - DISTRITO CAPITAL', 'AUTOPSTA REGIONAL DEL CENTRO SECTOR TAZON KM. 7  URB. MONTE VALLE, PARROQUIA COCHE', '0001', 2, 'A', '173.1.8.33', 0, 0, 1, 1, 1, '1', '1', '1', '1'),
(87, '000296', 'PUNTO DE VENTA - NUEVO CIRCO - DISTRITO CAPITAL', 'METRO DE CARACAS , ESTACION NUEVO CIRCO', '0001', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(88, '000294', 'PUNTO DE VENTA - PARQUE CENTRAL - DISTRITO CAPITAL', 'METRO DE CARACAS ESTACION PARQUE CENTRAL', '0001', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(89, '000295', 'PUNTO DE VENTA - EL SILENCIO - DISTRITO CAPITAL', 'METRO DE CARACAS . ESTACION SILENCIO', '0001', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(90, '000616', 'PUNTO DE VENTA - SARRIA - DISTRITO CAPITAL', '', '0001', 1, 'A', '173.1.10.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(91, '000646', 'PUNTO DE VENTA - JUAN PABLO SEGUNDO MONTALBAN - DISTRITO CAPITAL', 'ZONA 6, PARCELA â€œDâ€ ENTRE CALLE 11 Y 12 DEL COMPLEJO RESIDENCIAL INTEGRAL â€œPADRE JUAN VIVES SURIAâ€, PARROQUIA LA VEGA MUNICIPIO LIBERTADOR\r\n', '0001', 1, 'A', '173.1.5.100', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(92, '000194', 'PUNTO DE VENTA  - LA SALINAS - ZULIA', 'CALLE CONCORDIA URB. LA VICTORIA PTO REF. DISTRIBUIDOR EL NUEVO JUAN, Y UN MONUMENTO AL TRABAJADOR ANTIGUO CLUB DEPORTIVO LA SALINA . CABIMAS', '0024', 1, 'A', '173.24.10.30', 0, 0, 2, 4, 1, '1', '1', '1', '1'),
(93, '000665', 'PUNTO DE VENTA - LA POLICIA - COJEDES', 'Av. RÃ³mulo Gallegos,  vÃ­a al LimÃ³n. sede del Instituto AutÃ³nomo del Cuerpo de PolicÃ­a del Estado Cojedes (IACPEC)  San Carlos- Edo Cojedes\r\n', '0009', 2, 'A', '173.9.4.15', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(94, '000655', 'PUNTO DE VENTA - AGUASAY - MONAGAS', 'CARRETERA NACIONAL MATURIN EL TIGRE ESTADO MONAGAS', '0016', 2, 'A', '173.16.12.10', 0, 0, 2, 3, 1, '1', '2', '1', '3'),
(95, '000641', 'PUNTO DE VENTA - CORE4 - LARA', 'CORE 4 AVENIDA FLORENCIO JIMENEZ SECTOR PUEBLO NUEVO COMANDO REGIONAL NRO 4. PARROQUIA JUAN DE VILLEGAS. MUNICIPIO IRIBARREN. EDO LARA', '0013', 1, 'A', '173.13.4.22', 0, 0, 6, 4, 1, '1', '1', '1', '1'),
(96, '000628', 'PUNTO DE VENTA -SUPER PDVAL VALERA - TRUJILLO', 'MUNICIPIO VALERA, PARROQUIA MERCEDEZ DIAZ  ENTRE AVENIDAS 6 Y BOLÃVAR, ANTIGUO SUPERMERCADO VICTORIA, FRENTE AL BANESCO.', '0021', 1, 'A', '173.21.4.2', 0, 0, 3, 9, 1, '1', '1', '1', '1'),
(97, '000539', 'PUNTO DE VENTA - PDVAL SIDOR - BOLIVAR', 'AVENIDA\r\nGUAYANA, ZONA INDUSTRIAL MATANZAS, SIDERÃšRGICA DEL ORINOCO\r\nALFREDO MANEIRO (SIDOR), FRENTE AL ÃREA CORRESPONDIENTES A LA ZONA\r\nBANCARIA. PUERTO ORDAZ, CIUDAD GUAYANA, MUNICIPIO CARONI, PARROQUIA UNARE\r\nESTADO BOLÃVAR.', '0007', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(98, '000640', 'PUNTO DE VENTA - CABUDARE - LARA', 'TARABANA III SECTOR COLINAS DEL SUR  FRENTE AL COMANDO REGIONAL DE LA GUARDIA NACIONAL. PARROQUIA CABUDARE MUNICIPIO PALAVECINO. EDO LARA', '0013', 2, 'A', '173.13.7.4', 0, 0, 3, 6, 1, '1', '1', '1', '1'),
(99, '000652', 'PUNTO DE VENTA - QUIBOR - LARA', 'Urb. Pepe Coloma, Quibor, Municipio JimÃ©nez Estado Lara', '0013', 2, 'A', '173.13.5.9', 0, 0, 3, 6, 1, '1', '1', '1', '1'),
(100, '000675', 'PUNTO DE VENTA - CONCRIPTO - COJEDES', 'AV. UNIVERSIDAD SECTOR LA MAPORA FRENTE A LOS SILOS DE REUNELLEZ, SAN CARLOS ESTADO COJEDES.', '0009', 2, 'A', '173.9.1.3', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(101, '000676', 'PUNTO DE VENTA - EL AMPARO - COJEDES', 'AV. PRINCIPAL, PARROQUIA EL AMPARO RICAUTE, FRENTE A LA PLAZA BOLIVAR', '0009', 2, 'A', '', 0, 0, 1, 2, 0, '2', '1', '1', '1'),
(102, '000507', 'PUNTO DE VENTA - ALCALDIA ANACO - ANZOATEGUI', '', '0003', 1, 'I', '173.3.8.30', 0, 0, 6, 0, 0, '', '', '', ''),
(103, '000252', 'PUNTO DE VENTA - ARGIMIRO GABALDON - ANZOATEGUI', 'AV. ARGIMIRO GABALDON A 100 MTS DISTRIBUIDOR RAZZETTI', '0003', 1, 'A', '173.3.4.21', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(104, '000645', 'PUNTO DE VENTA - RIO CHICO - MIRANDA', 'SAN JOSE DE RIO DE CHICO, SECTOR EL DELIRIO, FRENTE A LA NUEVA SEDE DE LA PTJ MUNICIPIO ANDRES BELLO, ESTADO BOLIVARIANO DE MIRANDA.', '0015', 2, 'A', '', 0, 0, 6, 0, 0, '', '', '', ''),
(105, '000262', 'PUNTO DE VENTA - LOS BLOQUES - MONAGAS', 'SECTOR LOS BLOQUES, FRENTE AL MERCADO MUNICIPAL DE MATURIN', '0016', 2, 'I', '', 0, 0, 6, 0, 0, '1', '2', '1', '3'),
(106, '000670', 'PUNTO DE VENTA - LA AZULITA - MERIDA', 'LA AZULITA, FRENTE A LA ALCALDIA, MUNICIPIO ANDRES BELLO, EDO. MERIDA.\r\n', '0014', 1, 'A', '173.14.14.10', 0, 0, 1, 2, 1, '1', '2', '1', '3'),
(107, '000643', 'PUNTO DE VENTA - TUCANI - MERIDA', 'CARRETERA PANAMERICANA, POBLACIÃ“N TUCANIZON , CALLE VIA LA CHINCA, AL LADO DEL CDI, MÃ‰RIDA. ESTADO MÃ‰RIDA.\r\n', '0014', 1, 'A', '173.14.9.9', 0, 0, 2, 3, 1, '1', '2', '1', '5'),
(108, '000625', 'PUNTO DE VENTA - EL VIGIA - MERIDA', 'AV. 15 ENTRE CALLES 8 Y 9, DIAGONAL AL COMANDO DE LA POLICIA, AL LADO DE LA CLINICA DR. JOSE GREGORIO HERNANDEZ.', '0014', 1, 'A', '173.14.8.10', 0, 0, 3, 6, 1, '1', '1', '3', '3'),
(109, '000660', 'PUNTO DE VENTA - YARE - MIRANDA', 'AVENIDA BOLÃVAR CRUCE CON AVENIDA RIBAS. A UNA CUADRA DE LA PLAZA BOLÃVAR.  SAN FRANCISCO DE YARE. ESTADO MIRANDA.', '0015', 2, 'A', '173.15.3.10', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(110, '000635', 'PUNTO DE VENTA - JOSE FRANCISCO BERMUDEZ - SUCRE', '', '0019', 1, 'A', '173.19.2.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(111, '000555', 'PUNTO DE VENTA - CUESTA DEL TRAPICHE - TACHIRA', 'BARRIO CUESTA  DEL TRAPICHE   CALLE PRINCIPAL DEL BARRIO CUESTA DEL TRAPICHE', '0020', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(112, '000614', 'PUNTO DE VENTA LA GRITA', 'SECTOR LA QUINTA ENTRADA A LA GRITA\r\n', '0020', 2, 'A', '190.204.42.30', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(113, '000661', 'PUNTO DE VENTA - LAS PIÃ‘AS - MONAGAS', 'CALLE PRINCIPAL. SECTOR SANTA ELENA DE LAS PIÃ‘AS. MATURIN. ESTADO MONAGAS.\r\n', '0016', 1, 'A', '173.16.15.10', 0, 0, 2, 3, 1, '1', '1', '3', '3'),
(114, '000671', 'PUNTO DE VENTA - HUGO CHAVEZ FRIAS - PORTUGUESA', 'AVENIDA CIRCUNVALACIÃ“N SUR. FRENTE AL CEMENTERIO VIEJO. ACARIGUA. ESTADO PORTUGUESA.', '0018', 1, 'A', '173.18.4.10', 0, 0, 4, 8, 1, '1', '2', '4', '3'),
(115, '000678', 'PUNTO DE VENTA - INDEPENDENCIA - YARACUY', 'CARRETERA PANAMERICANA SENTIDO SAN FELIPE COCOROTE, SECTOR BRISAS DEL TERMINAL MUNICIPIO INDEPENDENCIA, ESTADO YARACUY.', '0023', 1, 'A', '173.23.3.30', 0, 0, 3, 6, 1, '1', '1', '1', '1'),
(116, '000216', 'PUNTO DE VENTA - MUELLE DEKO CHE GUEVARA - ZULIA', 'AV. INTERCOMUNAL CON CALLE INDEPENDENCIA ENTRANDO CASA ELECTRICA SECTOR LAS PLAYAS, MUNC. LAGUNILLAS', '0024', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(117, '000059', 'PUNTO DE VENTA - HIPER PDVAL - ZULIA', 'AVENIDA 2 EL MILAGRO CON AVENIDA LIBERTADOR  FRENTE A TRAKY ANTIGUA SEDE COMERCIAL CHICHILO MARACAIBO ESTADO ZULIA', '0024', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(118, '000516', 'PUNTO DE VENTA  - MOLINETE - ZULIA', 'CARRETERA VIA GUANA, SECTOR MOLINETE, DIAGONAL A LA IGLESIA CATOLICA, MOLINTE - EDO. ZULIA', '0024', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(119, '000618', 'PUNTO DE VENTA - LA CAÃ‘ADA-ESTADO ZULIA', 'DIRECCIÃ“N: MUNICIPIO LA CAÃ‘ADA DE URDANETA, SECTOR: LOS POZOS, AVENIDA PRINCIPAL', '0024', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(120, '000627', 'PUNTO DE VENTA - MACUTO - VARGAS', 'MACUTO ESTADO VARGAS', '0022', 1, 'A', '173.22.2.6', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(121, '000639', 'PUNTO DE VENTA - EDC- VARGAS', ' AV.SOUBLETTE SECTOR GUANAPE PARROQUIA LA GUAIRA', '0022', 2, 'A', '', 0, 0, 1, 1, 1, '1', '1', '1', '1'),
(122, '000524', 'PUNTO DE VENTA - LA ESTACADA - APURE', 'DIAONAL AL GRUPO ESCOLAR JUAN VICENTE TORRES DEL VALLE', '0004', 2, 'A', '172.17.155.121', 0, 0, 2, 3, 1, '1', '3', '1', '3'),
(123, '000657', 'PUNTO DE VENTA - URICA - ANZOATEGUI', 'Calle Principal del Sector Guayabal, al lado de la cancha, Parroquia Urica, Municipio Freites,  estado AnzoÃ¡tegui.', '0003', 2, 'A', '', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(124, '000656', 'PUNTO DE VENTA POTRERITO - ZULIA', 'AV. PRINCIPAL DE POTRERITO, SECTOR EL POTRERITO, PARROQUIA LA CAÃ‘ADA DE URDANETA, MUNICIPIO URDANETA,  ESTADO ZULIA.', '0024', 2, 'A', '172.18.6.150', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(125, '000659', 'PUNTO DE VENTA - SANTA INES - APURE', 'AV. INTERCOMUNAL SAN FERNANDO BIRUACA, URB. SANTA INES SAN FERNANDO ESTADO APURE.', '0004', 1, 'A', '173.4.4.40', 0, 0, 1, 2, 1, '1', '2', '3', '3'),
(126, '000654', 'PUNTO DE VENTA - CLARINES - ANZOATEGUI', '', '0003', 2, 'A', '173.3.11.10', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(127, '000662', 'PUNTO DE VENTA - SAN DIEGO - ANZOATEGUI', 'CALLE PRINCIPAL DE SAN DIEGO. FRENTE AL CEMENTERIO. MUNICIPIO SOTILLO. PARROQUIA POZUELOS. ANZOATEGUI', '0003', 2, 'A', '', 0, 0, 2, 2, 1, '1', '1', '1', '1'),
(128, '000113', 'PUNTO DE VENTA - 42 BRIGADA - ARAGUA', 'Av. Bolivar en el cuartel 42 Brigada paracaidista frente al ITFA. Municipio Giraldot Parroquia: Madre Maria. Sector San Jasinto Araugo. Maracay', '0005', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(129, '000623', 'PUNTO DE VENTA - LOS PROCERES-ESTADO BARINAS', 'SECTOR LOS PR0CERES DETRÃS DEL SEGURO SOCIAL,, \r\nMUNICIPIO BARINAS', '0006', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(130, '000621', 'PUNTO DE VENTA - EL NAZARENO-ESTADO BARINAS', 'CALLE GUZMAN BLANCO CON CALLE PAEZ, PARROQUIA LA LIBERTAD, MUNICIPIO ROJAS', '0006', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(131, '000305', 'PUNTO DE VENTA - PABASTO - BOLIVAR', 'CIUDAD BOLIVAR', '0007', 1, 'A', '173.7.6.1', 0, 0, 1, 4, 1, '1', '1', '1', '1'),
(132, '000307', 'PUNTO DE VENTA -  CVG BAUXILUM - BOLIVAR', 'AV. FUERZAS ARMADAS ZONA INDUSTRIAL MATANZAS', '0007', 2, 'A', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(133, '000644', 'PUNTO DE VENTA - LIBERTAD - BARINAS', 'FINAL DE LA CALLE GUZMAN BLANCO FRENTE AL LICEO IGNACIO MENDEZ, MUNICIPIO ROJAS PARROQUIA LIBERTAD ESTADO BARINAS.', '0006', 1, 'A', '173.6.8.9', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(134, '000613', 'PUNTO DE VENTA - LA VEGUITA - BARINAS', 'AVENIDA PRINCIPAL LA VEGUITA, LAS VEGUITAS, MUNICIPIO ALBERTO ARVELO TORREALBA, PARROQUIA RODRIGUEZ DOMÃNGUEZ, BARINAS - BARINAS', '0006', 1, 'A', '173.6.1.2', 0, 0, 2, 4, 1, '1', '1', '1', '1'),
(135, '000619', 'PUNTO DE VENTA-SABANETA-ESTADO BARINAS', 'AV. OBISPO CON CALLE 4 Y 5 SABANETA, PARROQUIA ALBERTO ARVELO TORREALBA MUNICIPIO SABANETA.', '0006', 2, 'A', '173.6.11.9', 0, 0, 2, 4, 1, '1', '1', '1', '1'),
(136, '000622', 'PUNTO DE VENTA - OBISPO-ESTADO BARINAS', 'AV. BOLIVAR CON CALLE RICAUTE, CASCO CENTRAL DE OBISPOS', '0006', 1, 'A', '173.6.7.9', 0, 0, 3, 6, 1, '1', '1', '1', '1'),
(137, '000642', 'PUNTO DE VENTA - HIPER PDVAL TAVACARE - BARINAS', 'AV. PRINCIPAL DE CIUDAD TAVACARE SECTOR C, BARINAS, MUNICIPIO BARINAS, PARROQUIA ALTO BARINAS.', '0006', 1, 'A', '173.6.6.9', 0, 0, 4, 8, 1, '1', '1', '1', '1'),
(138, '000620', 'PUNTO DE VENTA - BARRANCA-ESTADO BARINAS', 'CALLE CAMPO ELIAS CON AV.5 DE JULIO, DETRAS DEL PARQUE FERIAL, SECTOR LA MANGA, BARRANCAS. MUNICIPIO CRUZ PAREDES, EDO BARINAS.- BARRANCAS', '0006', 1, 'A', '173.6.4.9', 0, 0, 2, 4, 1, '1', '1', '1', '1'),
(139, '000536', 'PUNTO DE VENTA - BUENA VISTA ANACO - ANZOATEGUI', 'BUENA VISTA, SECTOR RANCHO GRANDE, MUNICIPIO ANACO - ESTADO ANZOATEGUI', '0003', 2, 'A', '173.3.4.2', 0, 0, 2, 2, 1, '1', '1', '1', '1'),
(140, '000290', 'PUNTO DE VENTA - COLON - TACHIRA', 'COLON, ESTADO TACHIRA', '0020', 2, 'A', '173.20.1.101', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(141, '000298', 'PUNTO DE VENTA - LA RINCONADA - DISTRITO CAPITAL', 'METRO DE CARACAS , ESTACION LA RINCONADA', '0001', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(142, '000674', 'PUNTO DE VENTA - OMAR TORRIJO - DISTRITO CAPITAL', 'AV. BOLIVAR  ESTE 6 ENTRE ESQUINA Ã‘O PASTOR A PUENTE VICTORIA EDIFICIO OMAR TORRIJOS, MUNICIPIO LIBERTADOR  PARROQUIA  LA CANDELARIA, DISTRITO CAPITAL .\r\n', '0001', 1, 'A', '173.1.9.32', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(143, '000402', 'PUNTO DE VENTA -  DELICIAS - TACHIRA', 'PUNTO DE VENTA -  DELICIAS - TACHIRA', '0020', 2, 'A', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(144, '000098', 'PUNTO DE VENTA - CORE 7 - ANZOATEGUI', 'CALLE MONTES,SECTOR EL PENSIL COMANDO REGIONAL NO. 7', '0003', 2, 'I', '173.3.9.1', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(145, '000253', 'PUNTO DE VENTA - EL VIÃ‘EDO - ANZOATEGUI', 'CALLE PRINCIPAL DEL VIÃ‘EDO ENTRE LAS CALLES 20 Y21 BARCELONA.', '0003', 2, 'I', '173.3.9.1', 0, 0, 6, 0, 0, '', '', '', ''),
(146, '000101', 'PUNTO DE VENTA - GUARAGUAO - ANZOATEGUI', '', '0003', 1, 'I', '173.3.11.20', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(147, '000404', 'PUNTO DE VENTA - LOBATERA - TACHIRA', 'PUNTO DE VENTA - LOBATERA - TACHIRA', '0020', 2, 'A', '173.20.1.103', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(148, '000173', 'PUNTO DE VENTA  - LOS MILLANES - NUEVA ESPARTA', 'CALLE GUIRIGUIRI, SECTOR LOS MILLANES ANTIGUO AUTOMERCADO ANVACA (VIRGEN DE LOS ANGELES) JUÃN GRIEGO- MUNICIPIO MARCANO', '0017', 1, 'A', '173.17.2.1', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(149, '000174', 'PUNTO DE VENTA - PLAYA LA GUARDIA - NUEVA ESPARTA', 'CALLE CARABOBO SECTOR GUIRI GUIRE LA GUARDIA MUNICIPIO DÃAZ', '0017', 2, 'A', '', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(150, '000531', 'PUNTO DE VENTA - ALI PRIMERA - SUCRE', 'CALLE NURUCUAL DEL PARCELAMIENTO MIRANDA, SECTOR EL BARBUDO,SUCRE.', '0019', 1, 'A', '173.19.5.30', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(151, '000546', 'PUNTO DE VENTA - JOSE TADEO MONAGAS - MONAGAS', 'AVENIDA ALIRIO UGARTE PELAYO, ANTIGUO J-GASPARD, FRENTE AL CONSECIONARIO FORD, MATURIN ESTADO MONAGAS.', '0016', 1, 'A', '173.16.3.18', 0, 0, 4, 10, 1, '1', '2', '1', '3'),
(152, '000249', 'PUNTO DE VENTA - GRAN MARISCAL DE AYACUCHO - SUCRE', 'AV PERIMETRAL CON CALLE JUVENTUD (FRENTE A LA UNEFA) SECTOR EL MERCADO', '0019', 1, 'A', '173.19.6.30', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(153, '000399', 'PUNTO DE VENTA - LAS MESAS - TACHIRA', 'PUNTO DE VENTA - LAS MESAS - TACHIRA', '0020', 1, 'A', '173.20.5.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(154, '000401', 'PUNTO DE VENTA - LA TENDIDA - TACHIRA', 'PUNTO DE VENTA - LA TENDIDA - TACHIRA', '0020', 2, 'A', '192.168.0.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(155, '000559', 'PUNTO DE VENTA - BOCONO - TRUJILLO', 'CALLE INDEPENDENCIA. ENTRE BOLIVAR Y JOSE MARIA VARGAS. BOCONO. ESTDO TRUJILLO', '0021', 2, 'I', '', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(156, '000651', 'PUNTO DE VENTA - CAFE VENEZUELA - TRUJILLO', 'ESTADO TRUJILLO, MUNICIPIO PAMPAN, SECTOR LA GUACA, DENTRO DE LAS INSTALACIONES DE CAFE VENEZUELA\r\nENCARGADA:MAHOLY MEJIAS TLF: 0426-3716390', '0021', 2, 'A', '127.0.0.1', 0, 0, 1, 1, 1, '1', '1', '1', '1'),
(157, '000279', 'PUNTO DE VENTA - CAMPO ELIAS - TRUJILLO', 'CALLE MIRANDA ENTRE COMERCIO HE INDEPENDENCIA, PARROQUIA CAMPO ELIAS, MPIO JUAN VICENTE CAMPO ELIA, ESTADO TRUJILLO.', '0018', 1, 'A', '173.21.3.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(158, '000322', 'PUNTO DE VENTA - FLOR DE PATRIA - TRUJILLO', 'FLOR DE PATRIA TRUJILLO', '0021', 1, 'A', '173.21.2.40', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(159, '000611', 'PUNTO DE VENTA - MOTATAN - TRUJILLO', '', '0021', 2, 'A', '173.21.5.10', 0, 0, 1, 1, 0, '1', '1', '1', '1'),
(160, '000560', 'PUNTO DE VENTA - SABANA DE MENDOZA - TRUJILLO', 'UBICADO EN MERCADO VALMORE RODRIGUEZ. ENTRE CALLE 8 Y 9. SABANA DE MENDOZA. ESTADO TRUJILLO', '0021', 2, 'A', ' 192.168.1.2', 0, 0, 1, 1, 1, '1', '1', '1', '1'),
(161, '000540', 'PUNTO DE VENTA - VALERA - TRUJILLO', 'AVENIDA 11 Y 12 CON CALLE 7 MUNICIPIO VALERA PARROQUIA MERCEDES DIAZ', '0021', 1, 'I', '', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(162, '000189', 'PUNTO DE VENTA - CHIVACOA - YARACUY', 'AV. SORTE CON LOS LEONES. ZONA INDUSTRIAL DE CHIVACOA TLF. 0251-8830634', '0023', 2, 'A', '173.23.1.30', 0, 0, 2, 4, 1, '1', '1', '1', '1'),
(163, '000197', 'PUNTO DE VENTA - CORE 3 - ZULIA', 'MILAGRO NORTE VÃA SANTA CRUZ COMANDO REGIONAL 3', '0024', 1, 'I', '173.24.9.10', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(164, '000215', 'PUNTO DE VENTA - LANCHA ZULIANA - ZULIA', 'CALLE INDEPENDENCIA SECTOR LAS MOROCHAS III, MUNICIPIO LAGUNILLAS', '0024', 1, 'A', '173.24.7.30', 0, 0, 1, 4, 1, '1', '1', '1', '1'),
(165, '000219', 'PUNTO DE VENTA - SAN LORENZO - ZULIA', 'SECTOR LAS CATORCE ENTRANDO AL MUELLE DE PDVSA MUNICIPIO BARALT', '0024', 2, 'A', '173.24.4.110', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(166, '000683', 'PUNTO DE VENTA - TUCUPIDO - GUARICO', 'AVENIDA SIGLO 21. TUCUPIDO. MUNICIPIO JOSE FELIX RIVAS. PARROQUIA TUCUPIDO. AL LADO DEL GIMNASIO DE BOXEO. ESTADO GUARICO', '0012', 2, 'A', '173.12.8.10', 0, 0, 1, 4, 1, '1', '1', '1', '1'),
(167, '000687', 'PUNTO DE VENTA - DESTACAMENTO 77 - MONAGAS', 'AVENIDA JOSÃ‰ TADEO MONAGAS. ALA LADO DEL DESTACAMENTO 51 DE LA GUARDIA NACIONAL. MUNICIPIO MATURÃN. ESTADO MONAGAS', '0016', 1, 'A', '173.16.14.10', 0, 0, 6, 4, 1, '1', '1', '1', '1'),
(168, '000684', 'PUNTO DE VENTA - CIUDAD BELEN II - MIRANDA', 'CARRETERA NACIONAL PETARE-GUARENAS. URBANIZACIÃ“N CIUDAD BELÃ‰N. TERRAZA 32-A. CARACAS. ESTADO MIRANDA', '0015', 2, 'A', '173.15.9.110', 0, 0, 1, 4, 1, '1', '1', '1', '1'),
(169, '000685', 'PUNTO DE VENTA - CIUDAD BELEN I - MIRANDA', 'CARRETERA NACIONAL PETARE-GUARENAS. URBANIZACIÃ“N CIUDAD BELÃ‰N. TERRAZA T-11. CARACAS. ESTADO MIRANDA', '0015', 2, 'A', '173.15.8.10', 0, 0, 1, 4, 1, '1', '1', '1', '1'),
(170, '000617', 'PUNTO DE VENTA - SIMON - DISTRITO CAPITAL', '', '0001', 1, 'A', '173.1.12.2', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(171, '000647', 'PUNTO DE VENTA - LA CAï¿½ADA - FALCON', 'AV. CHEMA TAE, SECTOR LA CAÑADA, ANTIGUA FUNDACION ALKON. CORO - EDO. FALCON.', '0011', 1, 'A', '173.11.2.2', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(172, '000303', 'PUNTO DE VENTA - MODELO  CATIA LA MAR - VARGAS', '', '0022', 2, 'A', '173.22.3.10', 0, 0, 2, 3, 1, '1', '1', '1', '1'),
(173, '000679', 'PUNTO DE VENTA - PARAMACONI - MONAGAS', 'CALLE PRINCIPAL ALTO PARAMACONI,DIAGONAL A LA LICORERIA LA BOMBA, PARROQUIA SANTA CRUZ MUNICIPIO MATURIN ESTADO MONAGAS', '0016', 1, 'A', '173.16.11.10', 0, 0, 2, 4, 1, '1', '2', '1', '3'),
(174, '000668', 'PUNTO DE VENTA ZARAZA - GUARICO', 'AV. ANDRES ELOY BLANCO CON CALLE LOS LAURELES, SECTOR EL TERMINAL MUNICIPIO PEDRO ZARAZA, ESTADO GUARICO.', '0012', 1, 'A', '173.12.6.10', 0, 0, 3, 6, 1, '1', '1', '1', '1'),
(175, '000567', 'PUNTO DE VENTA - PDVAL ROMULO GALLEGOS - GUARICO', '', '0012', 1, 'I', '173.12.4.31', 0, 0, 1, 1, 1, '1', '1', '1', '1'),
(176, '000615', 'PUNTO DE VENTA - BARARIDA - LARA', 'AV LIBERTADOR CON ENTRADA PRINCIPAL DE LA URB. LA CONCORDIA DIAGONAL AL HOSPITAL CENTRAL', '0013', 1, 'A', '173.13.8.4', 0, 0, 3, 6, 1, '1', '1', '1', '1'),
(177, '000626', 'PUNTO DE VENTA - GUAITOITO - GUARICO', 'CIUDAD CALABOZO ESTADO GUARICO', '0012', 1, 'A', '173.12.5.5', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(178, '000198', 'PUNTO DE VENTA - LA BARRACA - ZULIA', 'MILAGRO NORTE, AL LADO DEL DESTACAMENTO # 1, LA BARRACA. MARACAIBO', '0024', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(179, '000315', 'PUNTO DE VENTA - LOS COCOS CASIGUA EL CUBO - ZULIA', '', '0024', 2, 'A', '10.58.64.245', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(180, '000231', 'PUNTO DE VENTA - ALTAVISTA - BOLIVAR', 'CARRERA  19 CON CALLE 24 Y 25', '0007', 2, 'I', '', 0, 0, 6, 0, 0, '', '', '', ''),
(181, '000580', 'PUNTO DE VENTA - RIALCA - CARABOBO', 'ZONA INDUSTRIAL, AV HENRY FORD VALENCIA ESTADO CARABOBO- VALENCIA', '0008', 2, 'A', '173.8.3.20', 0, 0, 1, 3, 1, '1', '2', '3', '3'),
(182, '000258', 'PUNTO DE VENTA - SANTA ELENA DE ARENALES - MERIDA', 'PLAZA BOLIVAR STA. ELENA DE ARENALES', '0014', 1, 'A', '173.14.4.10', 0, 0, 1, 2, 1, '1', '2', '1', '3'),
(183, '000260', 'PUNTO DE VENTA - SANTO DOMINGO - MERIDA', 'CARRETTERATRANSANDINA BARINAS-MERIDA, CON CALLE SAN JERONIMO SANTO DOMINGO', '0014', 2, 'A', '', 0, 0, 1, 2, 1, '1', '3', '2', '3'),
(184, '000221', 'PUNTO DE VENTA -LIBERTADOR - MERIDA', 'LAS AMERICAS SECTOR SANTA BARBARA CLUB MILITAR RIVAS DAVILA DEL MUNICIPIO LIBERTADOR, PARROQUIA CARACCIOLO PARRA EDO. MERIDA.', '0014', 1, 'A', '173.14.6.6', 0, 0, 1, 3, 1, '1', '2', '1', '3'),
(185, '000271', 'PUNTO DE VENTA - TOCOME - MIRANDA', 'AV. FRANCISCO DE MIRANDA NUCLEO ENDOGENO FRANCISCO DE MIRANDA', '0015', 1, 'A', '173.15.10.10', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(186, '000163', 'PUNTO DE VENTA - MAMPORAL - MIRANDA', 'CALLE LA LAGUNA CON CALLE GRACIANO LEON MERCADO MUNICIPAL DE MAMPORAL', '0015', 2, 'I', '', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(187, '000293', 'PUNTO DE VENTA - FABRICIO OJEDA - DISTRITO CAPITAL', 'CORTADA DE CATIA AL LADO DE LA COCA COLA DENTRO DE LAS INSTALACIONES DEL NUCLEO ENDOGENO DE DESARROLLO FABRICIO OJEDA (N.U.D.E.F.O.)', '0001', 2, 'A', '173.1.6.34', 0, 0, 1, 1, 1, '1', '1', '1', '1'),
(188, '000528', 'PUNTO DE VENTA - EL DORADO - DISTRITO CAPITAL', 'PUNTO DE VENTA - EL DORADO - DISTRITO CAPITAL', '0001', 1, 'A', '172.16.4.21', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(189, '000286', 'PUNTO DE VENTA - DIANA SAN JOSE COTIZA  - DISTRITO CAPITAL', 'ESQUINA TELARES A SAN GRABIEL, A UNA CUADRA DE AV. PANTEON, PARROQUIA SAN JOSE', '0001', 1, 'A', '172.16.16.90', 0, 0, 4, 10, 1, '1', '1', '1', '1'),
(190, '000222', 'PUNTO DE VENTA - SANTA CRUZ DE MORA - MERIDA', 'AVENIDA PRINCIPAL ANTONIO PINTO SALINAS', '0014', 2, 'A', '173.14.11.10', 0, 0, 1, 2, 2, '1', '3', '2', '3'),
(191, '000557', 'PUNTO DE VENTA - CHAGUARAMAS - GUARICO', 'CHAGUARAMAS ESTADO GUARICO', '0012', 1, 'A', '173.12.7.2', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(192, '000314', 'PUNTO DE VENTA - 19 - LARA', '', '0013', 2, 'I', '', 0, 0, 1, 0, 0, '1', '1', '1', '1'),
(193, '000143', 'PUNTO DE VENTA - PUNTA CARDON - FALCON', 'AV. 20 DE LA COMUNIDAD CARDON, DIAGONAL AL COLEGIO INES FUGUEP DE PEÃ‘A.INTERCOMUNAL. DETRÃS DEL EDIFICIO DE LA SEDE DE REFINERIA CARDON ESTADO FALCON', '0011', 1, 'I', '173.11.1.2', 0, 0, 1, 6, 1, '1', '1', '1', '1'),
(194, '000520', 'PUNTO DE VENTA - EL CERCADO - LARA', 'CALL PRINCIPAL DEL CERCADO SECTOR LOMA VERDE MUNICIPIO IRRIBAREN PARROQUIA SANTA ROSA ESTADO LARA', '0013', 2, 'A', '173.13.11.4', 0, 0, 1, 4, 1, '1', '1', '1', '1'),
(195, '000230', 'PUNTO DE VENTA - HIPERPDVAL 20 - LARA', 'AV. 20 ENTRE CALLE 31 Y 32', '0013', 1, 'A', '173.13.2.41', 0, 0, 4, 3, 1, '1', '1', '1', '1'),
(196, '000269', 'PUNTO DE VENTA - ZAMORA PLAZA - MIRANDA', 'AV. INTERCOMUNAL GUATIRE-GUARENAS', '0015', 1, 'A', '173.15.2.100', 0, 0, 4, 10, 1, '1', '1', '1', '1'),
(197, '000168', 'PUNTO DE VENTA - INN - MONAGAS', 'AV RIVAS CON CALLE SIMBORA, DIAGONAL AL SUPERMERCADO CARIPITO (ANTIGUO COMEDOR POPULAR), AL LADO DEL INN TLF. 0291-808 3237', '0016', 2, 'I', '', 0, 0, 2, 0, 0, '1', '2', '1', '3'),
(198, '000530', 'PUNTO DE VENTA - MODELO PARARICITO - MONAGAS', 'AVENIDA PRINCIPAL LA PICA, CRUCE CON CALLE SUCRE, SECTOR PARARI.', '0016', 2, 'A', '173.16.10.10', 0, 0, 2, 3, 1, '1', '3', '1', '3'),
(199, '000398', 'PUNTO DE VENTA - LA FRIA - TACHIRA', 'PUNTO DE VENTA - LA FRIA - TACHIRA', '0020', 2, 'A', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(200, '000297', 'PUNTO DE VENTA - MENPET - DISTRITO CAPITAL', 'AV. LIBERTADOR EDF. TORRE ESTE , PDVSA LA CAMPIÃ‘A, PARROQUIA EL RECREO', '0001', 2, 'A', '173.1.7.36', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(201, '000291', 'PUNTO DE VENTA - MUSEO HISTORICO - DISTRITO CAPITAL', 'SECTOR MONTE PIEDAD, LA PLANICIE FRENTE A LOS CAMPOS DEPORTIVOS, ANTIGUO MUSEO HISTORICO MILITAR, PARROQUIA 23 DE ENERO', '0001', 2, 'A', '173.1.13.5', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(202, '000237', 'PUNTO DE VENTA - PLANTA CANTINA - DISTRITO CAPITAL', 'DISTRITO CAPITAL', '0001', 1, 'A', '173.1.1.30', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(203, '000157', 'PUNTO DE VENTA - TOVAR - MERIDA', 'AV. PERIMETRAL VÃA TOVAR ANTERIOR TERMINAL DE PASAJERO GALPON LUAN PLANTA BAJA. TELF 0275-8731777', '0014', 1, 'A', '173.14.10.10', 0, 0, 1, 3, 2, '1', '3', '2', '3'),
(204, '000170', 'PUNTO DE VENTA -MODELO PUNTA DE MATA - MONAGAS', 'CALLE EL PROGRESO. PUNTA DE MATA Tlf. 0292-8033610', '0016', 1, 'A', '173.16.2.10', 0, 0, 2, 3, 1, '1', '2', '1', '3'),
(205, '000688', 'PUNTO DE VENTA- CUMANACOA-SUCRE', 'SECTOR LA GRANJA II, DIAGONAL A LOS BOMBEROS MUNICIPALES, CUMANACOA , MUNICIPIO MONTES,ESTADO SUCRE', '0019', 2, 'A', '173.19.9.20', 0, 0, 2, 4, 1, '1', '1', '1', '1'),
(207, '000250', 'PUNTO DE VENTA - TIENDA PDVAL MERCADO (ALMACEN FRIO) - SUCRE', 'CALLE CAJIGAL C/C CALLE JUVENTUD, SECTOR EL MERCADO', '0019', 1, 'A', '173.19.1.30', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(208, '000698', 'PUNTO DE VENTA-OBRERO CANTV-EDO. DISTRITO CAPITAL', 'CANTV SEDE PRINCIPAL AV. LIBERTADOR EDIFICIO NEA URB. MARIPEREZ, PARROQUIA SANTA ROSALIA, MUNICIPIO LIBERTADOR', '0001', 1, 'A', '173.1.11.30', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(210, '000638', 'PUNTO DE VENTA - NIRGUA - YARACUY', 'YARACUY', '0023', 2, 'A', '173.23.2.30', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(211, '000690', 'PUNTO DE VENTA - BOLIVAR - TRUJILLO', 'CARRETERA PANAMERICANA SABANA GRANDE SECTOR BOULEVAR VIA LA VICTORIA DE CAUS MUNICIPIO Y PARROQUIA BOLIVAR, ESTADO TRUJILLO', '0021', 2, 'A', '', 0, 0, 1, 1, 0, '1', '1', '1', '1'),
(212, '000696', 'PUNTO DE VENTA-MAISANTA-AMAZONAS', 'PUNTO DE VENTA MAISANTA, PARROQUA PARHUEÑA MUNICIPIO ATURES ESTADO AMAZONAS', '0002', 2, 'A', '', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(213, '000697', 'PUNTO DE VENTA-SABILVEN-FALCON', 'ZONA INDUSTRIAL DE CORO, CALLE F, ENTRE CALLE 01 Y 02, CORO, PARROQUIA SAN ANTONIO MUNICIPIO MIRANDA ESTADO FALCON', '0011', 2, 'A', '172.16.1.2', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(214, '000703', 'PUNTO DE VENTA- IND', 'AV. THERAN URB. MONTALBAN A 200 MTS. DE LA REDOMA DE LA INDIA INSTITUTO NACIONAL DE DEPORTE I.N.D', '0001', 2, 'A', '', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(215, '000706', 'PUNTO DE VENTA- LA PASTORA', 'AVENIDA PRINCIPAL PUERTA CARACAS. AL LADO DE LA ADUANA. LA PASTORA. MUNICIPIO LIBERTADOR. DISTRITO', '0001', 2, 'A', '', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(216, '000702', 'PUNTO DE VENTA- LOGICASA', 'INSTALACIONES DE LOGICASA. SECTOR CABO BLANCA. LA GUARIA. ESTADO VARGAS', '0022', 2, 'A', '173.22.5.10', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(217, '000707', 'PUNTO DE VENTA- VETELCA', 'INSTALACIONES DE LA EMPRESA SOCIALISTA VTELCA, UBICADO EN LA ZONA FRANCA DE PARAGUANA, MUNICIPIO CARIR', '0011', 2, 'A', '', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(219, '000714', 'PUNTO DEVENTA - VENCEMOS - ANZOATEGUI', '', '0003', 2, 'A', '', 0, 0, 2, 2, 1, '1', '1', '1', '1'),
(220, '000721', 'PUNTO DE VENTA - EL PLAYON - PORTUGUESA', '', '0018', 1, 'A', '173.18.6.10', 0, 0, 2, 8, 1, '1', '2', '1', '3'),
(221, '000710', 'PUNTO DE VENTA - ANTIGUO AEREOPUERTO - FALCON', '', '0011', 2, 'A', '', 0, 0, 3, 8, 1, '1', '1', '1', '1'),
(222, '000718', 'PUNTO DE VENTA  - SUPER ABASTO SAN CRISTOBAL - TACHIRA', '', '0020', 2, 'A', '', 0, 0, 3, 6, 1, '1', '1', '1', '1'),
(223, '000713', 'PUNTO DE VENTA - PEQUIVEN - ZULIA', '', '0024', 2, 'A', '173.24.11.100', 0, 0, 1, 4, 1, '1', '1', '1', '1'),
(224, '000716', 'PUNTO DE VENTA  - PDVAL OBRERO IFE - MIRANDA', '', '0015', 2, 'A', '', 0, 0, 1, 3, 1, '1', '1', '1', '1'),
(225, '000717', 'PUNTO DE VENTA  - CORPOELEC EL MARQUEZ - MIRANDA', '', '0015', 2, 'A', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(226, '000720', 'PUNTO DEVENTA - QUIZANDA - CARABOBO', '', '0008', 2, 'A', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(227, '000002', 'CENTRO DE DISTRIBUCION - JOSE ANTONIO ANZOATEGUI - ANZOATEGUI', '', '0003', 1, 'A', '173.3.7.3', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(228, '000213', 'CENTRO DE DISTRIBUCION - INN - AMAZONAS', '', '0002', 1, 'A', '', 0, 0, 7, 0, 0, '', '', '', ''),
(229, '000178', 'CENTRO DE DISTRIBUCION - PDVAL ANACO - ANZOATEGUI', '', '0003', 1, 'A', '173.3.8.56', 0, 0, 7, 2, 1, '1', '1', '1', '1'),
(230, '000283', 'CENTRO DE DISTRIBUCION - LA ALGODONERA RIOS DEL SUR, C.A - APURE', '', '0004', 1, 'A', '173.4.5.10', 0, 0, 7, 3, 1, '1', '2', '1', '3'),
(231, '000284', 'CENTRO DE DISTRIBUCION -  MERCADO  - APURE', '', '0004', 1, 'A', '10.4.54.227', 0, 0, 7, 0, 0, '1', '3', '1', '3'),
(232, '000542', 'LA ESTACADA', '', '0004', 1, 'I', '172.17.155.121', 0, 0, 7, 0, 0, '1', '3', '1', '3'),
(233, '000582', 'CENTRO DE DISTRIBUCION - TURMERO - ARAGUA', '', '0005', 1, 'A', '173.5.1.60', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(234, '000013', 'CENTRO DE DISTRIBUCION -  ALGODONES LA MARQUESEÃ‘A (C.A. SABANETA) - BARINAS', '', '0006', 1, 'A', '173.6.5.10', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(235, '000525', 'CENTRO DE DISTRIBUCION - LOS SAMANES - BOLIVAR', '', '0007', 1, 'A', '173.7.3.1', 0, 0, 7, 6, 1, '1', '1', '1', '1'),
(237, '000572', 'CENTRO DE DISTRIBUCION - LA YAGUARA - DISTRITO CAPITAL', '', '0001', 1, 'A', '173.1.4.8', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(238, '000212', 'CENTRO DE DISTRIBUCION - PUNTA CARDON - FALCON', '', '0011', 1, 'A', '173.11.1.47', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(239, '000027', 'CENTRO DE DISTRIBUCION - AGRO PARTS IMPORT I  - GUARICO', '', '0012', 1, 'A', '173.12.3.57', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(240, '000255', 'CENTRO DE DISTRIBUCION - FUNDAMERCADO - GUARICO', '', '0012', 1, 'A', '173.12.1.45', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(241, '000236', 'CENTRO DE DISTRIBUCION - FLOR DE PATRIA - LARA', '', '0013', 1, 'A', '173.13.3.100', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(242, '000259', 'CENTRO DE DISTRIBUCION - EL MARQUES - MIRANDA', '', '0015', 1, 'A', '173.15.3.91', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(243, '000632', 'CENTRO DE DISTRIBUCION NACIONAL- SANTA TERESA DEL TUY SIRVENCA- MIRANDA', '', '0015', 1, 'A', '173.15.5.10', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(244, '000535', 'CENTRO DE DISTRIBUCION - SILOS EL ZORRO - MONAGAS', '', '0016', 1, 'A', '173.16.6.10', 0, 0, 7, 0, 0, '1', '2', '1', '3'),
(245, '000308', 'OBRAS PÚBLICAS ', '', '0000', 1, 'I', '173.19.7.254', 0, 0, 1, 0, 0, '', '', '', ''),
(246, '000558', 'CENTRO DE DISTRIBUCION - SANTA BARBARA - SUCRE', '', '0019', 1, 'A', '173.19.2.254', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(247, '000106', 'CENTRO DE DISTRIBUCION - BARRIO EL CARMEN - TACHIRA', '', '0020', 1, 'A', '173.20.1.83', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(248, '000035', 'CENTRO DE DISTRIBUCION - FLOR DE PATRIA - TRUJILLO', '', '0021', 1, 'A', '173.21.2.43', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(249, '000677', 'CENTRO DE DISTRIBUCIÃ“N - PAE - SECO- VALERA - TRUJILLO', '', '0021', 1, 'A', '173.21.4.31', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(250, '000200', 'CENTRO DE DISTRIBUCION - C.A. PRINCIPAL (SAN FRANCISCO) - ZULIA', '', '0024', 1, 'A', '173.24.5.35', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(251, '000719', 'PUNTO DE VENTA - TIMOTES - MERIDA', '', '0014', 2, 'A', '', 0, 0, 1, 2, 1, '1', '1', '1', '1'),
(254, '000021', 'CENTRO DE DISTRIBUCION -  FLOR AMARILLO EL RECREO- CARABOBO', '', '0008', 1, 'A', '173.8.1.30', 0, 0, 7, 0, 1, '1', '1', '2', '3'),
(255, '000023', 'CENTRO DE DISTRIBUCION - GRUPO SAHECT TINAQUILLO - COJEDES', '', '0009', 1, 'A', '173.9.2.200', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(256, '000272', 'CENTRO DE DISTRIBUCION - LA AZULITA- MERIDA', '', '0014', 1, 'A', '', 0, 0, 7, 0, 0, '1', '1', '1', '3'),
(257, '000563', 'CENTRO DE DISTRIBUCION -  LOS OLIVOS - NUEVA ESPARTA', '', '0017', 1, 'A', '173.17.1.77', 0, 0, 7, 0, 0, '1', '1', '1', '3'),
(258, '000245', 'CENTRO DE DISTRIBUCION - GALPONES CASA ARAURE - PORTUGUESA', '', '0018', 1, 'A', '173.18.1.10', 0, 0, 7, 0, 0, '1', '2', '1', '1'),
(259, '000542', 'CENTRO DE DISTRIBUCION - LA ESTACADA - APURE', '', '0004', 1, 'A', '', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(260, '000708', 'FUERTE TIUNA EDF 22', '', '0001', 2, 'A', '', 0, 0, 2, 0, 0, '1', '1', '1', '1'),
(261, '000552', 'CENTRO DE DISTRIBUCION - EL ESPEDITO DE BARRANCAS - DELTA AMACURO', '', '0010', 1, 'A', '', 0, 0, 7, 0, 0, '1', '1', '1', '1'),
(262, '000309', 'CENTRO DE DISTRIBUCION - GUARACARUMBO - VARGAS', '', '0022', 1, 'A', '173.22.1.8', 0, 0, 7, 0, 1, '1', '1', '1', '3'),
(263, '000612', 'CENTRO DE DISTRIBUCION NACIONAL- PEDRO CAMEJO - COJEDES', '', '0009', 1, 'A', '173.9.2.100', 0, 0, 7, 0, 1, '1', '1', '1', '3'),
(264, '000048', 'CENTRO DE DISTRIBUCION - FRIO GRAN MARISCAL DE AYACUCHO - SUCRE', '', '0019', 1, 'A', '173.19.1.30', 0, 0, 7, 0, 1, '1', '2', '1', '3'),
(265, '000566', 'CENTRO DE DISTRIBUCION - CHIVACOA CASA - YARACUY', '', '0024', 2, 'A', '192.168.0.222', 0, 0, 7, 0, 1, '1', '3', '1', '3'),
(266, '000320', 'CENTRO DE DISTRIBUCION - PUERTO DE SUCRE GUIRIA - SUCRE', '', '0019', 2, 'A', '192.168.0.2', 0, 0, 7, 0, 1, '1', '2', '1', '3'),
(267, '000308', 'CENTRO DE DISTRIBUCION - OBRAS PUBLICAS CARUPANO - SUCRE', '', '0019', 2, 'A', '173.19.10.30', 0, 0, 7, 0, 1, '1', '1', '1', '1'),
(269, '000566', 'CENTRO DE DISTRIBUCION - CHIVACOA CASA - YARACUY', '', '0023', 2, 'A', '', 0, 0, 7, 0, 1, '1', '2', '1', '1'),
(270, '000995', 'JORNADAS', '', '0025', 0, 'A', '', 0, 0, 6, 0, 0, '', '', '', ''),
(271, '000996', 'PDVALITOS', '', '0025', 0, 'A', '', 0, 0, 6, 0, 0, '', '', '', ''),
(272, '000997', 'CONVENIOS', '', '0025', 0, 'A', '', 0, 0, 6, 0, 0, '', '', '', ''),
(273, '000998', 'COMEDOR', '', '0025', 0, 'A', '', 0, 0, 6, 0, 0, '', '', '', ''),
(274, '000999', 'OTRO', '', '0025', 0, 'A', '', 0, 0, 6, 0, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `region`
--

DROP TABLE IF EXISTS `region`;
CREATE TABLE IF NOT EXISTS `region` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL DEFAULT '' COMMENT 'nombre de la region',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='regiones donde estaran los almacenes';

--
-- Volcado de datos para la tabla `region`
--

INSERT INTO `region` (`id`, `descripcion`) VALUES
(1, 'CARACAS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requisiciones`
--

DROP TABLE IF EXISTS `requisiciones`;
CREATE TABLE IF NOT EXISTS `requisiciones` (
  `cod_requisicion` int(11) NOT NULL DEFAULT '0',
  `agregada_fecha` date DEFAULT NULL,
  `agregada_hora` time DEFAULT NULL,
  `estacion` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `situacion` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `unidad` int(32) DEFAULT NULL,
  `cod_centro` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `concepto` text COLLATE utf8_spanish_ci,
  `fecha` date DEFAULT NULL,
  `tipo` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `req_compra` varchar(11) COLLATE utf8_spanish_ci DEFAULT NULL,
  `codigo_cuenta` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_requisicion`),
  KEY `unidad` (`unidad`,`cod_centro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requisiciones_det`
--

DROP TABLE IF EXISTS `requisiciones_det`;
CREATE TABLE IF NOT EXISTS `requisiciones_det` (
  `cod_requisicion_det` int(32) NOT NULL DEFAULT '0',
  `cod_requisicion` int(32) NOT NULL,
  `cod_item` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cantidad` decimal(20,2) DEFAULT NULL,
  `unidad` int(11) NOT NULL DEFAULT '0',
  `cod_centro` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `medida` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_requisicion_det`,`cod_requisicion`,`cod_item`,`unidad`,`cod_centro`),
  KEY `cod_requisicion` (`cod_requisicion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requisiciones_detalles_temp`
--

DROP TABLE IF EXISTS `requisiciones_detalles_temp`;
CREATE TABLE IF NOT EXISTS `requisiciones_detalles_temp` (
  `cod_material` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad_solicitada` int(11) NOT NULL,
  `cantidad_disponible` int(11) NOT NULL,
  `cantidad_despachar` int(11) NOT NULL,
  `cantidad_comprar` int(11) NOT NULL,
  `tipo_requisicion` int(11) NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `medida` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla que sirve para separar las requisiciones de compras y ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsable`
--

DROP TABLE IF EXISTS `responsable`;
CREATE TABLE IF NOT EXISTS `responsable` (
  `cod_responsable` int(32) NOT NULL AUTO_INCREMENT,
  `responsable` varchar(70) NOT NULL,
  `usuario_creacion` varchar(70) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cod_responsable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retiro_efectivo`
--

DROP TABLE IF EXISTS `retiro_efectivo`;
CREATE TABLE IF NOT EXISTS `retiro_efectivo` (
  `id` int(11) NOT NULL,
  `id_cajero` varchar(255) NOT NULL,
  `billetes_20000` decimal(10,2) NOT NULL,
  `billetes_10000` decimal(10,2) NOT NULL,
  `billetes_5000` decimal(10,2) NOT NULL,
  `billetes_2000` decimal(10,2) NOT NULL,
  `billetes_1000` decimal(10,2) NOT NULL,
  `billetes_500` decimal(10,2) NOT NULL,
  `billetes_100` decimal(10,2) NOT NULL,
  `billetes_50` decimal(10,2) NOT NULL,
  `billetes_20` decimal(10,2) NOT NULL,
  `billetes_10` decimal(10,2) NOT NULL,
  `billetes_5` decimal(10,2) NOT NULL,
  `billetes_2` decimal(10,2) NOT NULL,
  `monedas` decimal(10,2) NOT NULL,
  `efectivo` decimal(10,2) NOT NULL,
  `estatus_retiro` tinyint(1) NOT NULL COMMENT '0= sin procesar, 1=procesado',
  `tipo_venta` tinyint(1) NOT NULL COMMENT '0 = pyme, 1=pos',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de creacion y update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `descripcion`) VALUES
(1, 'Ninguno'),
(2, 'Despachador'),
(3, 'Receptor'),
(4, 'Seguridad'),
(5, 'Autorizador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_firma`
--

DROP TABLE IF EXISTS `roles_firma`;
CREATE TABLE IF NOT EXISTS `roles_firma` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_persona` varchar(15) NOT NULL,
  `nombre_persona` varchar(255) NOT NULL,
  `descripcion_rol` int(11) NOT NULL,
  `cargo` varchar(255) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Roles para las firmas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secuencia_cierre_host`
--

DROP TABLE IF EXISTS `secuencia_cierre_host`;
CREATE TABLE IF NOT EXISTS `secuencia_cierre_host` (
  `id_cierre_host` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Calve Primaria',
  `nombre_host` varchar(50) NOT NULL COMMENT 'Nombre de Host',
  `secuencia_host` bigint(20) NOT NULL COMMENT 'Secuencia Host',
  `status_cierre` int(11) NOT NULL COMMENT 'Indica si esta secuencia cerro en el pyme, 0 cerro, 1 sin cerrar',
  PRIMARY KEY (`id_cierre_host`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_islr`
--

DROP TABLE IF EXISTS `servicios_islr`;
CREATE TABLE IF NOT EXISTS `servicios_islr` (
  `id_servicio_islr` int(32) NOT NULL AUTO_INCREMENT,
  `cod_item` int(32) NOT NULL,
  `cod_lista_impuesto` int(32) NOT NULL,
  PRIMARY KEY (`id_servicio_islr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sincronizacion_productos`
--

DROP TABLE IF EXISTS `sincronizacion_productos`;
CREATE TABLE IF NOT EXISTS `sincronizacion_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `nombre_archivo` varchar(50) NOT NULL COMMENT 'Nombre del Archivo Cargado',
  `fecha` datetime NOT NULL COMMENT 'Fecha de Carga del Archivo',
  `cod_usuario` int(11) NOT NULL COMMENT 'Id del usuario que realiza la operacion',
  `tipo` varchar(5) NOT NULL COMMENT 'Tipo de Archivo (Individual o Consolidado)',
  `version` int(11) NOT NULL COMMENT 'Version de la Actualizacion',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Guarda los datos de los archivos de sincronizacion de productos ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sincronizacion_productos_detalle`
--

DROP TABLE IF EXISTS `sincronizacion_productos_detalle`;
CREATE TABLE IF NOT EXISTS `sincronizacion_productos_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `id_sincro` int(11) NOT NULL COMMENT 'Id del Archivo',
  `codigo_barra` varchar(50) NOT NULL COMMENT 'Codigo de Barras del Producto',
  `precio` decimal(10,2) NOT NULL COMMENT 'Precio',
  `estado` varchar(4) NOT NULL COMMENT 'Estado al que aplica el cambio de Precio',
  `estatus` int(11) NOT NULL,
  `usuario_ejecucion` varchar(255) NOT NULL COMMENT 'Usuario que ejecuta el cambio',
  `fecha_ejecucion` datetime NOT NULL COMMENT 'Fecha en que se ejecuta el cambio',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Guarda los productos cargados en los archivos de sincronizacion de productos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subseccion`
--

DROP TABLE IF EXISTS `subseccion`;
CREATE TABLE IF NOT EXISTS `subseccion` (
  `opt_subseccion` varchar(80) NOT NULL COMMENT 'add, edit, delete',
  `archivo_tpl` varchar(100) NOT NULL,
  `archivo_php` varchar(100) NOT NULL,
  `cod_seccion` int(32) UNSIGNED DEFAULT NULL,
  `descripcion` varchar(100) NOT NULL,
  KEY `FK_subseccion_1` (`cod_seccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `subseccion`
--

INSERT INTO `subseccion` (`opt_subseccion`, `archivo_tpl`, `archivo_php`, `cod_seccion`, `descripcion`) VALUES
('edit', 'almacen_editar.tpl', 'almacen_editar.php', 55, 'Editar Almacen'),
('delete', 'almacen_eliminar.tpl', 'almacen_eliminar.php', 55, 'Eliminar Almacen'),
('add', 'zona_nuevo.tpl', 'zona_nuevo.php', 56, 'Agregrando Zona'),
('edit', 'zona_editar.tpl', 'zona_editar.php', 56, 'Editar Zona'),
('delete', 'zona_eliminar.tpl', 'zona_eliminar.php', 56, 'Eliminar Zona'),
('add', 'vendedor_nuevo.tpl', 'vendedor_nuevo.php', 57, 'Incluyendo Vendedor'),
('edit', 'vendedor_editar.tpl', 'vendedor_editar.php', 57, 'Editar Informacion del Vendedor'),
('delete', 'vendedor_eliminar.tpl', 'vendedor_eliminar.php', 57, 'Eliminar Vendedor'),
('add', 'departamento_nuevo.tpl', 'departamento_nuevo.php', 64, 'Agregando Departamento'),
('edit', 'departamento_editar.tpl', 'departamento_editar.php', 64, 'Editar Departamento'),
('delete', 'departamento_eliminar.tpl', 'departamento_eliminar.php', 64, 'Eliminar Departamento'),
('add1', 'grupo_nuevo.tpl', 'grupo_nuevo.php', 65, 'Agregando Grupo'),
('edit1', 'grupo_editar.tpl', 'grupo_editar.php', 65, 'Editar Grupo'),
('delete', 'grupo_eliminar.tpl', 'grupo_eliminar.php', 65, 'Eliminar Grupo'),
('add', 'linea_nuevo.tpl', 'linea_nuevo.php', 66, 'Agregando Linea'),
('edit', 'linea_editar.tpl', 'linea_editar.php', 66, 'Editar Linea'),
('delete', 'linea_eliminar.tpl', 'linea_eliminar.php', 66, 'Eliminar Linea'),
('edit', 'cliente_editar.tpl', 'cliente_editar.php', 8, 'Editar Informacion del Cliente'),
('delete', 'cliente_eliminar.tpl', 'cliente_eliminar.php', 8, 'Eliminar Cliente'),
('delete', 'producto_eliminar.tpl', 'producto_eliminar.php', 61, 'Eliminar Producto'),
('add', 'cliente_nuevo.tpl', 'cliente_nuevo.php', 8, 'Nuevo Cliente'),
('add', 'servicio_nuevo.tpl', 'servicio_nuevo.php', 67, 'Incluyendo Nuevo Servicio'),
('edit', 'servicio_editar.tpl', 'servicio_editar.php', 67, 'Editar Servicio'),
('delete', 'servicio_eliminar.tpl', 'servicio_eliminar.php', 67, 'Eliminar Servicio'),
('add', 'usuarios_nuevo.tpl', 'usuarios_nuevo.php', 68, 'Nuevo Usuario'),
('edit', 'usuarios_editar.tpl', 'usuarios_editar.php', 68, 'Editar Usuario'),
('delete', 'usuarios_eliminar.tpl', 'usuarios_eliminar.php', 68, 'Eliminar Usuario'),
('add', 'cliente_nuevo.tpl', 'cliente_nuevo.php', 58, 'Incluyendo Cliente'),
('edit', 'cliente_editar.tpl', 'cliente_editar.php', 58, 'Editar Informacion del Cliente'),
('newfactura', 'factura_nueva.tpl', 'factura_nueva.php', 58, 'Nueva Factura'),
('viewProductos', 'producto_existencia_almacen_viewProductos.tpl', 'producto_existencia_almacen_viewProductos.php', 69, 'Lista de Productos por Almacen'),
('add', 'producto_existencia_almacen_viewProductosAgregar.tpl', 'producto_existencia_almacen_viewProductosAgregar.php', 69, 'Incluyendo Producto al Almacen'),
('edit', 'producto_existencia_almacen_viewProductosEditar.tpl', 'producto_existencia_almacen_viewProductosEditar.php', 69, 'Editar Cantidad Existente del Producto'),
('delete', 'producto_existencia_almacen_viewProductosEliminar.tpl', 'producto_existencia_almacen_viewProductosEliminar.php', 69, 'Eliminar Existencia del Producto'),
('add', 'almacen_nuevo.tpl', 'almacen_nuevo.php', 55, 'Agregando Almacen'),
('add', 'almacen_nuevo.tpl', 'almacen_nuevo.php', 55, 'Agregando Almacen'),
('add', 'islr_nuevo.tpl', 'islr_nuevo.php', 70, 'Nuevo ISLR'),
('edit', 'islr_editar.tpl', 'islr_editar.php', 70, 'Editar ISLR'),
('delete', 'islr_eliminar.tpl', 'islr_eliminar.php', 70, 'Eliminar ISLR'),
('add', 'boletos_nuevo.tpl', 'boletos_nuevo.php', 72, 'AÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â±adir Boleto'),
('edit', 'boletos_editar.tpl', 'boletos_editar.php', 72, 'Editar Boleto'),
('delete', 'boletos_eliminar.tpl', 'boletos_eliminar.php', 72, 'Eliminar Boleto'),
('add', 'cliente_nuevo.tpl', 'cliente_nuevo.php', 73, 'Incluyendo Cliente'),
('edit', 'cliente_editar.tpl', 'cliente_editar.php', 73, 'Editar Informacion del Cliente'),
('newfactura', 'factura_nueva_boleto.tpl', 'factura_nueva_boleto.php', 73, 'Nueva Factura (Boletos)'),
('add', 'responsable_nuevo.tpl', 'responsable_nuevo.php', 76, 'Incluyendo Responsable'),
('edit', 'responsable_editar.tpl', 'responsable_editar.php', 76, 'Editar Responsable'),
('delete', 'responsable_eliminar.tpl', 'responsable_eliminar.php', 76, 'Eliminar Responsable'),
('add', 'banco_nuevo.tpl', 'banco_nuevo.php', 77, 'Incluir Banco'),
('edit', 'banco_editar.tpl', 'banco_editar.php', 77, 'Editar Banco'),
('delete', 'banco_eliminar.tpl', 'banco_eliminar.php', 77, 'Eliminar Banco'),
('add', 'instrumentoformapago_nuevo.tpl', 'instrumentoformapago_nuevo.php', 78, 'Incluir Forma Pago'),
('edit', 'instrumentoformapago_editar.tpl', 'instrumentoformapago_editar.php', 78, 'Editar Forma Pago'),
('delete', 'instrumentoformapago_eliminar.tpl', 'instrumentoformapago_eliminar.php', 78, 'Eliminar Forma Pago'),
('edocuenta', 'cxc_estadodecuenta.tpl', 'cxc_estadodecuenta.php', 59, 'Estado de Cuenta'),
('newfactura', 'factura_nueva.tpl', 'factura_nueva.php', 59, 'Nueva Factura'),
('pagooabono', 'cxc_pagooabono.tpl', 'cxc_pagooabono.php', 59, 'Nuevo Pago/Abono'),
('add', 'retencioniva_nuevo.tpl', 'retencioniva_nuevo.php', 80, 'Agregar Nuevo Registro de Retencion I.V.A.'),
('edit', 'retencioniva_editar.tpl', 'retencioniva_editar.php', 80, 'Editar Registro de Retencion I.V.A.'),
('delete', 'retencioniva_eliminar.tpl', 'retencioniva_eliminar.php', 80, 'Eliminar Registro de Retencion I.V.A.'),
('devolver_ps', 'devolucion_venta.tpl', 'devolucion_venta.php', 79, 'DevoluciÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³n de Venta'),
('add', 'proveedores_nuevo.tpl', 'proveedores_nuevo.php', 86, 'Incluir Nuevo Proveedor'),
('edit', 'proveedores_editar.tpl', 'proveedores_editar.php', 86, 'Editar Informacion del proveedor'),
('delete', 'proveedores_eliminar.tpl', 'proveedores_eliminar.php', 86, 'Eliminar Proveedor'),
('newCompra', 'proveedores_compra_nuevo.tpl', 'proveedores_compra_nuevo.php', 87, 'Generar Nueva Compra'),
('add', 'proveedores_nuevo.tpl', 'proveedores_nuevo.php', 87, 'Incluir Nuevo Proveedor'),
('edit', 'proveedores_editar.tpl', 'proveedores_editar.php', 87, 'Editar Informacion del proveedor'),
('delete', 'proveedores_eliminar.tpl', 'proveedores_eliminar.php', 87, 'Eliminar Proveedor'),
('edocuenta', 'cxp_estadodecuenta.tpl', 'cxp_estadodecuenta.php', 88, 'Cuenta por pagar'),
('pagoabonoCXP', 'cxp_pagoabono.tpl', 'cxp_pagoabono.php', 88, 'Agregar Abono de compra'),
('add', 'banco_nuevo.tpl', 'banco_nuevo.php', 90, 'Incluir Banco'),
('edit', 'banco_editar.tpl', 'banco_editar.php', 90, 'Editar Banco'),
('viewcuentasByBanco', 'tesoreria_banco_cuentas.tpl', 'tesoreria_banco_cuentas.php', 90, 'Cuentas'),
('addCuentaByBanco', 'tesoreria_banco_cuentas_agregar.tpl', 'tesoreria_banco_cuentas_agregar.php', 90, 'Incluir Cuenta Bancaria'),
('editCuentaByBanco', 'tesoreria_banco_cuentas_editar.tpl', 'tesoreria_banco_cuentas_editar.php', 90, 'Editar Cuenta'),
('deleteCuentaByBanco', 'tesoreria_banco_cuentas_eliminar.tpl', 'tesoreria_banco_cuentas_eliminar.php', 90, 'Eliminar Cuenta'),
('listaChequeraCuentaB', 'listaChequeraCuentaByBanco.tpl', 'listaChequeraCuentaByBanco.php', 90, 'Lista de Chequeras'),
('listaChequeraCuentaByBanco', 'tesoreria_listaChequeraCuentaByBanco.tpl', 'tesoreria_listaChequeraCuentaByBanco.php', 90, 'Lista de Chequeras'),
('addChequeraCuentaByBanco', 'tesoreria_addChequeraCuentaByBanco.tpl', 'tesoreria_addChequeraCuentaByBanco.php', 90, 'Nuevo Cheque'),
('editChequeraCuentaByBanco', 'tesoreria_editChequeraCuentaByBanco.tpl', 'tesoreria_editChequeraCuentaByBanco.php', 90, 'Editar Chequera'),
('deleteChequeraCuentaByBanco', 'tesoreria_deleteChequeraCuentaByBanco.tpl', 'tesoreria_deleteChequeraCuentaByBanco.php', 90, 'Eliminar Chequera'),
('generarChequeraCuentaByBanco', 'tesoreria_generarChequeraCuentaByBanco.tpl', 'tesoreria_generarChequeraCuentaByBanco.php', 90, 'Generar Chequera'),
('activarChequeraCuentaByBanco', 'tesoreria_activarChequeraCuentaByBanco.tpl', 'tesoreria_activarChequeraCuentaByBanco.php', 90, 'Activar Cheques'),
('consumirChequeraCuentaByBanco', 'tesoreria_consumirChequeraCuentaByBanco.tpl', 'tesoreria_consumirChequeraCuentaByBanco.php', 90, 'Consumir Chequera'),
('depositoChequeraCuentaByBanco', 'tesoreria_depositoChequeraCuentaByBanco.tpl', 'tesoreria_depositoChequeraCuentaByBanco.php', 90, 'Cambiar a Estatus Deposito'),
('ver_chequesChequeraCuentaByBanco', 'tesoreria_ver_chequesChequeraCuentaByBanco.tpl', 'tesoreria_ver_chequesChequeraCuentaByBanco.php', 90, 'Cheques'),
('verChequerasByBanco', 'tesoreria_banco_cuentasSeleccioneParaCuenta.tpl', 'tesoreria_banco_cuentasSeleccioneParaCuenta.php', 91, 'Cuentas'),
('SeleccionlistaChequeraCuentaByBanco', 'tesoreria_SeleccionlistaChequeraCuentaByBanco.tpl', 'tesoreria_SeleccionlistaChequeraCuentaByBanco.php', 91, 'Chequeras Activas'),
('hacerCheque', 'tesoreria_hacerCheque.tpl', 'tesoreria_hacerCheque.php', 91, 'Cheque por CxP / Cheque por Beneficiario'),
('viewmovimientosByBanco', 'tesoreria_banco_movimientos.tpl', 'tesoreria_banco_movimientos.php', 93, 'Cuentas'),
('movimientosCuentaByBanco', 'tesoreria_lista_movimientos_bancarios.tpl', 'tesoreria_lista_movimientos_bancarios.php', 93, 'Lista de Movimientos Bancarios'),
('addMovimientoCuentaByBanco', 'tesoreria_addMovimientoCuentaByBanco.tpl', 'tesoreria_addMovimientoCuentaByBanco.php', 93, 'Agregar Movimientos Bancarios'),
('editMovimientoCuentaByBanco', 'tesoreria_editMovimientoCuentaByBanco.tpl', 'tesoreria_editMovimientoCuentaByBanco.php', 93, 'Editar Movimientos Bancarios'),
('deleteMovimientoCuentaByBanco', 'tesoreria_deleteMovimientoCuentaByBanco.tpl', 'tesoreria_deleteMovimientoCuentaByBanco.php', 93, 'Eliminar Movimientos Bancarios'),
('edit', 'tipos_movimientos_bancarios_edit.tpl', 'tipos_movimientos_bancarios_edit.php', 96, 'Editar Tipo de Movimiento'),
('delete', 'tipos_movimientos_bancarios_delete.tpl', 'tipos_movimientos_bancarios_delete.php', 96, 'Eliminar Tipo Movimiento'),
('add', 'tipos_movimientos_bancarios_add.tpl', 'tipos_movimientos_bancarios_add.php', 96, 'Agregar Tipo de Movimiento'),
('edit', 'impuesto_municipal_ica_edit.tpl', 'impuesto_municipal_ica_edit.php', 97, 'Editar Impuesto ICA'),
('delete', 'impuesto_municipal_ica_delete.tpl', 'impuesto_municipal_ica_delete.php', 97, 'Eliminar Impuesto ICA'),
('add', 'impuesto_municipal_ica_add.tpl', 'impuesto_municipal_ica_add.php', 97, 'Agregar Impuesto ICA'),
('edit', 'tipo_impuesto_editar.tpl', 'tipo_impuesto_editar.php', 98, 'Editar Tipo de Impuesto'),
('delete', 'tipo_impuesto_eliminar.tpl', 'tipo_impuesto_eliminar.php', 98, 'Eliminar Tipo de Impuesto'),
('add', 'tipo_impuesto_nuevo.tpl', 'tipo_impuesto_nuevo.php', 98, 'Agregar Tipo de Impuesto'),
('edit', 'entidad_editar.tpl', 'entidad_editar.php', 99, 'Editar Entidad'),
('delete', 'entidad_eliminar.tpl', 'entidad_eliminar.php', 99, 'Eliminar Entidad'),
('add', 'entidad_nuevo.tpl', 'entidad_nuevo.php', 99, 'Agregar Entidad'),
('edit', 'formulacion_impuestos_editar.tpl', 'formulacion_impuestos_editar.php', 100, 'Editar Formulacion de Impuesto'),
('delete', 'formulacion_impuestos_eliminar.tpl', 'formulacion_impuestos_eliminar.php', 100, 'Eliminar Formulacion de Impuesto'),
('add', 'formulacion_impuestos_nuevo.tpl', 'formulacion_impuestos_nuevo.php', 100, 'Agregar Formulacion de Impuesto'),
('edit', 'lista_impuestos_editar.tpl', 'lista_impuestos_editar.php', 101, 'Editar Impuesto'),
('delete', 'lista_impuestos_eliminar.tpl', 'lista_impuestos_eliminar.php', 101, 'Eliminar Impuesto'),
('add', 'lista_impuestos_nuevo.tpl', 'lista_impuestos_nuevo.php', 101, 'Agregar Impuesto'),
('add', 'tipo_cliente.tpl', 'tipo_cliente_nuevo.php', 102, 'Agregandar Tipo de Cliente'),
('edit', 'tipo_cliente_editar.tpl', 'tipo_cliente_editar.php', 102, 'Editar Tipo de Cliente'),
('delete', 'tipo_cliente_eliminar.tpl', 'tipo_cliente_eliminar.php', 102, 'Eliminar Tipo de Cliente'),
('edit', 'divisas_editar.tpl', 'divisas_editar.php', 104, 'Editar Divisas'),
('add', 'divisas_agregar.tpl', 'divisas_agregar.php', 104, 'Agregar Divisa'),
('add', 'divisas_agregar2.tpl', 'divisas_agregar2.php', 105, 'Agregar Tasa Cambio'),
('edit', 'tasa_editar.tpl', 'tasa_editar.php', 105, 'Editar Tasa de Cambio'),
('add', 'tipo_movimientos_almacen_nuevo.tpl', 'tipo_movimientos_almacen_nuevo.php', 112, 'Agregar Tipo de Movimiento de Almacen'),
('edit', 'tipo_movimientos_almacen_editar.tpl', 'tipo_movimientos_almacen_editar.php', 112, 'Editar Tipo de Movimiento de Almacen'),
('delete', 'tipo_movimientos_almacen_eliminar.tpl', 'tipo_movimientos_almacen_eliminar.php', 112, 'Eliminar Tipo de Movimiento de Almacen'),
('add', 'entrada_almacen_nuevo.tpl', 'entrada_almacen_nuevo.php', 109, 'Agregar Entrada de Almacen'),
('edit', 'entrada_almacen_editar.tpl', 'entrada_almacen_editar.php', 109, 'Editar Entrada de Almacen'),
('delete', 'entrada_almacen_eliminar.tpl', 'entrada_almacen_eliminar.php', 109, 'Eliminar Entrada de Almacen'),
('add', 'salida_almacen_nuevo.tpl', 'salida_almacen_nuevo.php', 110, 'Agregar Salida de Almacen'),
('edit', 'salida_almacen_editar.tpl', 'salida_almacen_editar.php', 110, 'Editar Salida de Almacen'),
('delete', 'salida_almacen_eliminar.tpl', 'salida_almacen_eliminar.php', 110, 'Eliminar Salida de Almacen'),
('add', 'traslado_almacen_nuevo.tpl', 'traslado_almacen_nuevo.php', 111, 'Agregar Traslado de Almacen'),
('edit', 'traslado_almacen_editar.tpl', 'traslado_almacen_editar.php', 111, 'Editar Traslado de Almacen'),
('delete', 'traslado_almacen_eliminar.tpl', 'traslado_almacen_eliminar.php', 111, 'Eliminar Traslado de Almacen'),
('CuentaByBancoConciliacion', 'tesoreria_banco_movimientos_conciliacion.tpl', 'tesoreria_banco_movimientos_conciliacion.php', 94, 'Cuentas'),
('seleccionFechaAconciliar', 'tesoreria_fechas_concilar.tpl', 'tesoreria_fechas_concilar.php', 94, 'Especifique el mes a conciliar'),
('tesoreria_conciliar', 'tesoreria_concilar.tpl', 'tesoreria_concilar.php', 94, 'Conciliar'),
('add', 'proveedores_especialidad_add.tpl', 'proveedores_especialidad_add.php', 132, 'Agregar Especialidad Proveedor'),
('edit', 'proveedores_especialidad_edit.tpl', 'proveedores_especialidad_edit.php', 132, 'Editar Especialidad'),
('delete', 'proveedores_especialidad_delete.tpl', 'proveedores_especialidad_delete.php', 132, 'Eliminar Especialidad'),
('facturasCXP', 'cxp_facturas.tpl', 'cxp_facturas.php', 88, 'Facturas de compra'),
('addFac', 'cxp_facturas_nuevo.tpl', 'cxp_facturas_nuevo.php', 88, 'Agregar Factura'),
('pendienteFactura', 'cxc_pendiente.tpl', 'cxc_pendiente.php', 133, 'Cuenta por cobrar Pendiente'),
('autorizarFactura', 'cxc_autorizar.tpl', 'cxc_autorizar.php', 134, 'Cuenta por Cobrar Enviadas'),
('pagarFactura', 'cxc_pagar.tpl', 'cxc_pagar.php', 135, 'Cuenta por Cobrar '),
('cxpFacturasMedico', 'cxp_facturas_medico.tpl', 'cxp_facturas_medico.php', 140, 'Facturas por pagar medico'),
('add', 'tipo_proveedor_agregar.tpl', 'tipo_proveedor_agregar.php', 141, 'Agregar Tipo de Proveedor'),
('edit', 'tipo_proveedor_editar.tpl', 'tipo_proveedor_editar.php', 141, 'Editar Tipo de Proveedor'),
('delete', 'tipo_proveedor_eliminar.tpl', 'tipo_proveedor_eliminar.php', 141, 'Eliminar Tipo de Proveedor'),
('imprimirFacturas', 'facturasxmedico.tpl', 'facturasxmedico.php', 140, 'Facturas por Medico'),
('view', 'cxp_facturas_ver.tpl', 'cxp_facturas_ver.php', 88, 'Ver factura'),
('verCuentasPorBanco', 'tesoreria_banco_cuentasSeleccioneParaCuentaTransf.tpl', 'tesoreria_banco_cuentasSeleccioneParaCuentaTransf.php', 151, 'Cuentas'),
('transferencias', 'tesoreria_transferencia.tpl', 'tesoreria_transferencia.php', 151, 'Ver Transferencias'),
('hacerTransferencia', 'tesoreria_hacerTransferencia.tpl', 'tesoreria_hacerTransferencia.php', 151, 'Hacer transferencia'),
('imprimirFactxCheque', 'listaFactxCheque.tpl', 'listaFactxCheque.php', 152, 'Lista Factura x Cheque'),
('new', 'presupuesto_nuevo.tpl', 'presupuesto_nuevo.php', 156, 'Nuevo Presupuesto/Cotizacion'),
('add', 'cliente_nuevo.tpl', 'cliente_nuevo.php', 156, 'Agregrando Cliente'),
('edit', 'cliente_editar.tpl', 'cliente_editar.php', 156, 'Editar Cliente'),
('new', 'pedido_nuevo.tpl', 'pedido_nuevo.php', 155, 'Nuevo Pedido'),
('add', 'cliente_nuevo.tpl', 'cliente_nuevo.php', 155, 'Agregrando Cliente'),
('edit', 'cliente_editar.tpl', 'cliente_editar.php', 155, 'Editar Cliente'),
('new', 'nota_entrega_nueva.tpl', 'nota_entrega_nueva.php', 154, 'Nueva Nota de Entrega'),
('add', 'cliente_nuevo.tpl', 'cliente_nuevo.php', 154, 'Agregrando Cliente'),
('edit', 'cliente_editar.tpl', 'cliente_editar.php', 154, 'Editar Cliente'),
('delete', 'anular_pedido.tpl', 'anular_pedido.php', 159, 'Anular Pedido'),
('delete', 'anular_nota_entrega.tpl', 'anular_nota_entrega.php', 158, 'Anular Notas de Entrega'),
('newfactura_rapida_pedido', 'factura_rapida_nueva_pedido.tpl', 'factura_rapida_nueva_pedido.php', 238, 'Nuevo Pedido'),
('delete', 'anular_compra.tpl', 'anular_compra.php', 60, 'Anular Compra'),
('add', 'region_nuevo.tpl', 'region_nuevo.php', 170, 'region donde se ubican los almacenes'),
('edit', 'region_editar.tpl', 'region_editar.php', 170, 'editar las regiones'),
('delete', 'region_eliminar.tpl', 'region_eliminar.php', 170, 'eliminar region'),
('add', 'localidad_nuevo.tpl', 'localidad_nuevo.php', 171, 'localidad de los almacenes'),
('edit', 'localidad_editar.tpl', 'localidad_editar.php', 171, 'Editar las localidades'),
('serial', 'serial.tpl', 'serial.php', 61, 'seriales'),
('listserial', 'serial_nuevo.tpl', 'serial_nuevo.php', 61, 'agregar serial '),
('editserial', 'serial_editar.tpl', 'serial_editar.php', 61, 'editar serial'),
('deleteserial', 'serial_eliminar.tpl', 'serial_eliminar.php', 61, 'eliminar serial'),
('listEst', 'estados.tpl', 'estados.php', 170, 'estados que conforma la region'),
('addEstado', 'estados_nuevo.tpl', 'estados_nuevo.php', 170, 'estado en la region'),
('addDesp', 'detalleDesp_nuevo.tpl', 'detalleDesp_nuevo.php', 172, 'nuevo despacho detalle'),
('editEstados', 'estados_editar.tpl', 'estados_editar.php', 170, 'editar estados en la region'),
('deleteEstado', 'estados_eliminar.tpl', 'estados_eliminar.php', 170, 'eliminar estados en la region'),
('addUbicacion', 'ubicacion_nuevo.tpl', 'ubicacion_nuevo.php', 55, 'nueva ubicacion'),
('editUbicacion', 'ubicacion_editar.tpl', 'ubicacion_editar.php', 55, 'editar ubicacion'),
('deleteUbicacion', 'ubicacion_eliminar.tpl', 'ubicacion_eliminar.php', 55, 'eliminar ubicacion'),
('ubicacion', 'ubicacion.tpl', 'ubicacion.php', 55, 'Ubicacion'),
('delete', 'localidad_eliminar.tpl', 'localidad_eliminar.php', 171, 'eliminar localidad'),
('add', 'ministerio_nuevo.tpl', 'ministerio_nuevo.php', 180, 'Agregar Ministerios'),
('edit', 'ministerio_editar.tpl', 'ministerio_editar.php', 180, 'Editar Ministerio'),
('delete', 'ministerio_eliminar.tpl', 'ministerio_eliminar.php', 180, 'Eliminar Ministerios'),
('add', 'distrito_escolar_nuevo.tpl', 'distrito_escolar_nuevo.php', 179, 'Agregar Distrito Escolar'),
('edit', 'distrito_escolar_editar.tpl', 'distrito_escolar_editar.php', 179, 'Editar Distrito Escolar'),
('delete', 'distrito_escolar_eliminar.tpl', 'distrito_escolar_eliminar.php', 179, 'Eliminar Distrito Escolar'),
('edit', 'cedula_dia_editar.tpl', 'cedula_dia_editar.php', 196, 'Editar Restriccion Cedula/Dia'),
('add', 'deposito.tpl', 'deposito.php', 206, 'Editar Restriccion Cedula/Dia'),
('add', 'cataporte.tpl', 'cataporte.php', 207, 'Editar Restriccion Cedula/Dia'),
('add', 'agre_libro_venta.tpl', 'agre_libro_venta.php', 208, 'Agregar Libro de Venta'),
('add', 'caja_impresora_agregar.tpl', 'caja_impresora_agregar.php', 209, 'Agregar Impresora y Caja'),
('edit', 'caja_impresora_editar.tpl', 'caja_impresora_editar.php', 209, 'Editar Impresora y Caja'),
('delete', 'caja_impresora_borrar.tpl', 'caja_impresora_borrar.php', 209, 'Borrar Impresora y Caja'),
('add', 'agregar_cuentas_contables.tpl', 'agregar_cuentas_contables.php', 219, 'Agregar Cuentas Bancarias'),
('edit', 'editar_cuentas_contables.tpl', 'editar_cuentas_contables.php', 219, 'Editar Las Cuentas Bancarias'),
('delete', 'borrar_cuentas_contables.tpl', 'borrar_cuentas_contables.php', 219, 'Borrar Las Cuentas Bancarias'),
('add', 'agre_cierre_cajero.tpl', 'agre_cierre_cajero.php', 210, 'Agregar un cierre de cajero'),
('add', 'file_upload_productos.tpl', 'file_upload_productos.php', 218, 'Actualizar Productos y Precios'),
('add', 'file_upload_estabilizacion.tpl', 'file_upload_estabilizacion.php', 221, 'Estabilizaci&oacute;n Productos'),
('add', 'retiro_efectivo_agregar.tpl', 'retiro_efectivo_agregar.php', 223, 'Editar un retiro de efectivo'),
('edit', 'retiro_efectivo_editar.tpl', 'retiro_efectivo_editar.php', 223, 'Agregar un retiro de efectivo'),
('edit', 'operaciones_apertura_editar.tpl', 'operaciones_apertura_editar.php', 220, 'Editar Operaci&oacute;n Apertura'),
('add', 'calidad_almacen_add.tpl', 'calidad_almacen_add.php', 226, 'Agregar Nuevos Entradas De Productos'),
('edit', 'calidad_almacen_edit.tpl', 'calidad_almacen_edit.php', 226, 'Editar Entrada De Calidad'),
('delete', 'calidad_almacen_delete.tpl', 'calidad_almacen_delete.php', 225, 'Borrar Registros'),
('add', 'calidad_almacen_retiro_add.tpl', 'calidad_almacen_retiro_add.php', 227, 'Retirar productos de almacen que esten dañados'),
('add', 'tipo_uso_agregar.tpl', 'tipo_uso_agregar.php', 229, 'Agregar Tipo Uso'),
('edit', 'tipo_uso_editar.tpl', 'tipo_uso_editar.php', 229, 'Modificar Tipo Uso'),
('add', 'tipo_visita_agregar.tpl', 'tipo_visita_agregar.php', 230, 'Agregar Tipo Visita'),
('delete', 'tipo_uso_borrar.tpl', 'tipo_uso_borrar.php', 229, 'Borrar Tipo Uso'),
('edit', 'tipo_visita_editar.tpl', 'tipo_visita_editar.php', 230, 'Modificar Tipo visita'),
('delete', 'tipo_visita_borrar.tpl', 'tipo_visita_borrar.php', 230, 'Borrar Tipo Visita'),
('add', 'calidad_visita_add.tpl', 'calidad_visita_add.php', 228, 'Agregar Visita'),
('add', 'tomas_fisicas_add.tpl', 'tomas_fisicas_add.php', 232, 'Agregar Toma F&iacute;sica'),
('add', 'acta_inventario_nuevo.tpl', 'acta_inventario_nuevo.php', 235, 'Agregando Acta de Inventario'),
('newfactura_rapida', 'factura_rapida_nueva.tpl', 'factura_rapida_nueva.php', 58, 'Nuevo Pedido'),
('edit', 'salida_almacen_nuevo_pedido.tpl', 'salida_almacen_nuevo_pedido.php', 241, ''),
('add', 'factura_rapida_nueva_pedido.tpl', 'factura_rapida_nueva_pedido.php', 238, 'Nuevo Pedido'),
('add', 'rol_add.tpl', 'rol_add.php', 242, 'Agregar Rol'),
('edit', 'rol_edit.tpl', 'rol_edit.php', 242, 'Editar Rol'),
('ajuste', 'tomas_fisicas_ajuste.tpl', 'tomas_fisicas_ajuste.php', 232, 'Ajuste de Inventario según Tomas Físicas'),
('add', 'cesta_clap_add.tpl', 'cesta_clap_add.php', 247, 'Crear Cesta Clap'),
('add', 'billetes_add.tpl', 'billetes_add.php', 248, 'Agregar Nuevo Billete'),
('edit', 'billetes_update.tpl', 'billetes_update.php', 248, 'Editar Billete'),
('add', 'cierre_pos_add.tpl', 'cierre_pos_add.php', 249, 'Agregar Cierre POS'),
('tipodespacho', 'salida_almacen_update_pedido.tpl', 'salida_almacen_update_pedido.php', 241, 'Agregar Tipo Despacho'),
('add', 'transformacion_add.tpl', 'transformacion_add.php', 252, 'Agregar Nueva Producci&oacute;n'),
('correccion', 'correccion_deposito_crear.tpl', 'correccion_deposito_crear.php', 254, 'Correcci&oacute;n Cataporte'),
('add', 'cataporte_ticket_add.tpl', 'cataporte_ticket_add.php', 255, 'Agregar un nuevo cataporte'),
('add', 'tipo_cuenta_presupuesto_add.tpl', 'tipo_cuenta_presupuesto_add.php', 259, 'Agregar tipo cuenta presupuesto'),
('edit', 'tipo_cuenta_presupuesto_edit.tpl', 'tipo_cuenta_presupuesto_edit.php', 259, 'Editar tipo cuenta presupuesto'),
('delete', 'tipo_cuenta_presupuesto_delete.tpl', 'tipo_cuenta_presupuesto_delete.php', 259, 'Borrar Cuenta Tipo Presupuesto'),
('add', 'cuenta_presupuesto_add.tpl', 'cuenta_presupuesto_add.php', 260, 'Agregar Cuenta Presupuestaría'),
('edit', 'cuenta_presupuesto_edit.tpl', 'cuenta_presupuesto_edit.php', 260, 'Editar Cuenta Presupuestaría'),
('delete', 'cuenta_presupuesto_delete.tpl', 'cuenta_presupuesto_delete.php', 260, 'Borrar Cuenta Presupuestaría'),
('add', 'cataporte_tarjeta_add.tpl', 'cataporte_tarjeta_add.php', 261, 'Cierre Tarjeta'),
('add', 'cataporte_deposito_add.tpl', 'cataporte_deposito_add.php', 263, 'Cierre Deposito'),
('add', 'cataporte_cheque_add.tpl', 'cataporte_cheque_add.php', 262, 'Cierre Cheque'),
('add', 'cataporte_credito_add.tpl', 'cataporte_credito_add.php', 264, 'Cierre Credito'),
('addcargo', 'add_cargo_cliente.tpl', 'add_cargo_cliente.php', 8, 'Agregar Cargo Al Cliente'),
('propiedadesUbicacion', 'propiedadesUbicacion.tpl', 'propiedadesUbicacion.php', 55, 'Servicios Ubicacion'),
('addpropiedadesUbicacion', 'addpropiedadesUbicacion.tpl', 'addpropiedadesUbicacion.php', 55, 'Agregar Servicios Ubicacion'),
('movimientoservicio', 'movimientos_servicios_lista.tpl', 'movimientos_servicios_lista.php', 266, 'Lista de los servicios del movimiento'),
('movimientoservicioadd', 'movimientos_servicios_add.tpl', 'movimientos_servicios_add.php', 266, 'Agregar un Servicio al movimiento'),
('add', 'producto_nuevo.tpl', 'producto_nuevo.php', 61, 'Agregar Producto o Servicio'),
('edit', 'producto_editar.tpl', 'producto_editar.php', 61, 'Editar Producto o Servicio'),
('entradapaleta', 'entradapaleta_nuevo.tpl', 'entradapaleta_nuevo.php', 109, 'Nueva Entrada Almacen'),
('entradapaleta', 'entradapaleta_nuevo.tpl', 'entradapaleta_nuevo.php', 109, 'Nueva Entrada Almacen'),
('entradapaleta', 'entradapaleta_nuevo.tpl', 'entradapaleta_nuevo.php', 109, 'Nueva Entrada Almacen'),
('entradapaleta', 'entradapaleta_nuevo.tpl', 'entradapaleta_nuevo.php', 109, 'Nueva Entrada Almacen'),
('entradapaleta', 'entradapaleta_nuevo.tpl', 'entradapaleta_nuevo.php', 109, 'Nueva Entrada Almacen'),
('entradapaleta', 'entradapaleta_nuevo.tpl', 'entradapaleta_nuevo.php', 109, 'Nueva Entrada Almacen'),
('entradapaleta', 'entradapaleta_nuevo.tpl', 'entradapaleta_nuevo.php', 109, 'Nueva Entrada Almacen'),
('add', 'conductores_agregar.tpl', 'conductores_agregar.php', 277, 'Agregar Conductor'),
('edit', 'conductores_editar.tpl', 'conductores_editar.php', 277, 'Editar Conductor'),
('add', 'ticket_agregar.tpl', 'ticket_agregar.php', 278, 'Agregar Ticket de Entrada'),
('edit', 'ticket_editar.tpl', 'ticket_editar.php', 278, 'Editar Ticket'),
('view', 'ticket_ver.tpl', 'ticket_ver.php', 278, 'Ver Ticket');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_grupo`
--

DROP TABLE IF EXISTS `sub_grupo`;
CREATE TABLE IF NOT EXISTS `sub_grupo` (
  `id_sub_grupo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `cod_grupo` int(11) NOT NULL COMMENT 'Codigo Grupo',
  `descripcion` varchar(255) NOT NULL COMMENT 'Descripcion sub grupo',
  PRIMARY KEY (`id_sub_grupo`)
) ENGINE=InnoDB AUTO_INCREMENT=932 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sub_grupo`
--

INSERT INTO `sub_grupo` (`id_sub_grupo`, `cod_grupo`, `descripcion`) VALUES
(1, 1, 'No Aplica'),
(2, 2, 'No Aplica'),
(3, 3, 'No Aplica'),
(4, 4, 'No Aplica'),
(5, 5, 'No Aplica'),
(6, 6, 'No Aplica'),
(7, 7, 'No Aplica'),
(8, 8, 'No Aplica'),
(9, 9, 'No Aplica'),
(10, 10, 'No Aplica'),
(11, 11, 'No Aplica'),
(12, 12, 'No Aplica'),
(13, 13, 'No Aplica'),
(14, 14, 'No Aplica'),
(15, 15, 'No Aplica'),
(16, 16, 'MAICENA'),
(17, 17, 'No aplica'),
(18, 18, 'No Aplica'),
(19, 19, 'No Aplica'),
(20, 20, 'No Aplica'),
(21, 21, 'No Aplica'),
(22, 22, 'No Aplica'),
(23, 23, 'No Aplica'),
(24, 24, 'No Aplica'),
(25, 25, 'No Aplica'),
(26, 26, 'No Aplica'),
(27, 27, 'No Aplica'),
(28, 28, 'No Aplica'),
(29, 29, 'No Aplica'),
(30, 30, 'No Aplica'),
(31, 31, 'No Aplica'),
(32, 32, 'No Aplica'),
(33, 33, 'No Aplica'),
(34, 34, 'No Aplica'),
(35, 35, 'No Aplica'),
(36, 36, 'No Aplica'),
(37, 37, 'No Aplica'),
(38, 38, 'No Aplica'),
(39, 39, 'No Aplica'),
(40, 40, 'No Aplica'),
(41, 41, 'No Aplica'),
(42, 42, 'No Aplica'),
(43, 43, 'No Aplica'),
(44, 44, 'No Aplica'),
(45, 45, 'No Aplica'),
(46, 46, 'No Aplica'),
(47, 47, 'No Aplica'),
(48, 48, 'No Aplica'),
(49, 49, 'No Aplica'),
(50, 50, 'No Aplica'),
(51, 51, 'No Aplica'),
(52, 52, 'No Aplica'),
(53, 53, 'No Aplica'),
(54, 54, 'No Aplica'),
(55, 55, 'No Aplica'),
(56, 56, 'No Aplica'),
(57, 57, 'No Aplica'),
(58, 58, 'No Aplica'),
(59, 59, 'No Aplica'),
(60, 60, 'No Aplica'),
(61, 115, 'UNIFORME ESCOLAR'),
(62, 62, 'No Aplica'),
(69, 16, 'ARROZ'),
(70, 16, 'AVENA EN HOJUELA'),
(71, 35, 'HARINA DE MAIZ PRECOCIDA'),
(72, 35, 'HARINA DE TRIGO LEUDANTE'),
(73, 35, 'HARINA DE TRIGO TODO USO'),
(74, 63, 'ELECTRICOS'),
(75, 10, 'LECHE EN POLVO COMPLETA '),
(76, 29, 'SARDINAS'),
(77, 29, 'ATUN'),
(78, 25, 'LECHE EN POLVO'),
(79, 10, 'CHOCOLATE EN POLVO'),
(80, 51, 'PASTA LARGA'),
(81, 1, 'SABORIZADA'),
(82, 51, 'PASTAS DE TRIGO'),
(83, 51, 'PASTAS CORTAS'),
(84, 64, 'SHAMPOO'),
(85, 64, 'SHAMPOO'),
(86, 64, 'SHAMPOO'),
(87, 64, 'SHAMPOO'),
(88, 64, 'SHAMPOO'),
(89, 29, 'CARAOTAS'),
(90, 17, 'SALCHICHAS'),
(91, 17, 'JAMON DE PIERNA'),
(92, 17, 'JAMON DE ESPALDA'),
(93, 17, 'FIAMBRE'),
(94, 1, 'No aplica'),
(95, 17, 'JAMON AHUMADO'),
(96, 43, 'JABON PANELA'),
(97, 17, 'TOCINETA'),
(98, 43, 'HOGAR'),
(99, 43, 'LAVAPLATOS EN CREMA '),
(100, 43, 'LAVAPLATOS LIQUIDO'),
(101, 43, 'LIMPIADORES LIQUIDOS'),
(102, 43, 'VIDRIOS'),
(103, 58, 'SALSA DE TOMATE'),
(104, 58, 'PASTA DE TOMATE'),
(105, 1, 'No aplica'),
(106, 58, 'SALSAS DE AJO'),
(107, 58, 'SALSAS INGLESA'),
(108, 58, 'SALSA DE SOYA'),
(109, 43, 'ROPA'),
(110, 58, 'CONCENTRADOS DE TOMATE'),
(111, 20, 'ADOBO'),
(112, 20, 'COMINO'),
(113, 43, 'SUAVISANTES'),
(114, 17, 'BOLOGNA'),
(115, 44, 'BOMBILLOS'),
(116, 7, 'ESPONJAS DE ALAMBRE'),
(117, 7, 'ESPONJAS DE FIBRA'),
(118, 7, 'ESPONJAS DOBLE USO'),
(119, 22, 'MERMELADAS'),
(120, 22, 'DULCES EN ALMIBAR'),
(121, 48, 'PANALES'),
(122, 48, 'PANALES'),
(123, 25, 'CHICHAS'),
(124, 25, 'BEBIDAS ACHOCOLATADAS'),
(125, 25, 'LECHE CONDENSADA'),
(126, 25, 'LECHE LIQUIDA'),
(127, 48, 'PROTECTORES DIARIOS'),
(128, 35, 'HARINA DE ARROZ'),
(129, 20, 'OREGANO'),
(130, 23, 'TOALLITAS HUMEDAS'),
(131, 20, 'CURRY'),
(132, 28, 'ACEITUNAS'),
(133, 64, 'CREMA DE PEINAR'),
(134, 35, 'HARINA DE MAIZ TOSTADO \"FORORO\"'),
(135, 20, 'CALDO DE POLLO'),
(136, 43, 'CLORO'),
(137, 43, 'DESINFECTANTES'),
(138, 54, 'CERDO'),
(139, 53, 'PESCADO CONGELADO DE MAR'),
(140, 17, 'CHARCUTERIA'),
(141, 48, 'TOALLAS SANITARIAS'),
(142, 41, 'MANTEL'),
(143, 20, 'CONDIMENTOS'),
(144, 65, 'RALLADOR'),
(145, 64, 'DESODORANTE'),
(146, 66, 'HELICOPTEROS'),
(147, 66, 'PELUCHES'),
(148, 20, 'ESPECIAS'),
(149, 33, 'DULCES'),
(150, 19, 'TOMATE PELADO'),
(151, 21, 'SIROPE'),
(152, 20, 'SAL'),
(153, 21, 'PANETTON'),
(154, 43, 'DESMANCHADOR'),
(155, 56, 'ENVOLTURA PLASTICA'),
(156, 29, 'PALMITO'),
(157, 25, 'LACTOVISOY'),
(158, 40, 'CARAOTAS'),
(159, 25, 'QUESO'),
(160, 20, 'CALDO DE COSTILLA'),
(161, 14, 'CARNE DE TERCERA'),
(162, 3, 'LACTOVISOY'),
(163, 22, 'CREMA CHANTILLY'),
(164, 58, 'SALSAS'),
(165, 3, 'CEREALES INFANTILES'),
(166, 43, 'DESINFECTANTES'),
(167, 10, 'BEBIDAS EN POLVO'),
(168, 20, 'CONDIMENTOS'),
(169, 43, 'DETERGENTE'),
(170, 58, 'ADEREZO'),
(171, 25, 'LECHE CONDENSADA'),
(172, 64, 'JABON DE TOCADOR'),
(173, 7, 'CREMA LIQUIDA  ZAPATOS'),
(174, 64, 'ACONDICIONADOR'),
(175, 17, 'JAMON TENDER'),
(176, 25, 'BEBIDAS LACTEAS'),
(177, 7, 'ESPONJAS'),
(178, 43, 'LIMPIADORES, DETERGENTES Y DESINFECTANTES'),
(179, 39, 'NECTAR DE JUGO'),
(180, 35, 'FORORO'),
(181, 21, 'COMPOTAS'),
(182, 22, 'TURRON'),
(183, 23, 'GEL FIJADOR PARA EL CABELLO'),
(184, 34, 'ACEITE'),
(185, 51, 'PASTA'),
(186, 64, 'TOALLITAS HUMEDAS'),
(187, 23, 'ANTIBACTERIAL'),
(188, 73, 'TOALLAS HUMEDAS'),
(189, 21, 'UVAS'),
(190, 73, 'COLONIA'),
(191, 17, 'EMBUTIDOS'),
(192, 17, 'EMBUTIDOS'),
(193, 23, 'GEL FIJADOR'),
(194, 23, 'GEL FIJADOR'),
(195, 23, 'GEL FIJADOR'),
(196, 23, ' DESINFECTANTES'),
(197, 36, 'HILO'),
(198, 23, 'GEL FIJADOR'),
(199, 23, 'DETERGENTE'),
(200, 43, 'REMOVEDOR QUITA MANCHAS'),
(201, 43, 'DETERGENTE LIQUIDO'),
(202, 53, 'MARISCOS'),
(203, 33, 'GALLETAS'),
(204, 28, 'ALCAPARRAS'),
(205, 21, 'CEREZAS'),
(206, 23, 'CREMA PARA EL CABELLO'),
(207, 58, 'MAYONESA'),
(208, 17, 'JAMON PLANCHADO'),
(209, 23, 'ACONDICIONADOR'),
(210, 58, 'VINAGRE'),
(211, 77, 'DESINFECTANTES'),
(212, 23, 'CREMA DENTAL'),
(213, 14, 'MISCELANOS (HIGADO,PANZA,RABO,CORAZON, HUESO ROJO)'),
(214, 26, 'AZUCAR'),
(215, 53, 'PESCADO CONGELADO DE AGUA DULCE'),
(216, 20, 'CUBITOS'),
(217, 26, 'PAPELON'),
(218, 78, 'MOLDE PARA HORNOS'),
(219, 28, 'ENCURTIDOS EN VINAGRE'),
(220, 23, 'DESODORANTE'),
(221, 66, 'CAMION'),
(222, 20, 'CANELA'),
(223, 66, 'MUÃ‘ECAS'),
(224, 62, 'VELAS Y VELONES'),
(225, 54, 'CHULETA FRESCA '),
(226, 20, 'BICARBONATO DE SODIO'),
(227, 56, 'VASOS PLASTICOS'),
(228, 60, 'TOALLIN'),
(229, 22, 'DULCES'),
(230, 14, 'CARNE'),
(231, 19, 'PURE DE TOMATE'),
(232, 18, 'CHOCOLATE'),
(233, 66, 'JUEGO BEBE'),
(234, 78, 'JABONERA'),
(235, 65, 'KIT DE COCINA'),
(236, 23, 'AFEITADORA'),
(237, 21, 'COTUFAS'),
(238, 66, 'MINI AUTOPISTAS'),
(239, 49, 'CARTUCHERAS'),
(240, 65, 'GUANTES DE COCINA'),
(241, 66, 'PELOTAS'),
(242, 49, 'LIBRETA DE DIBUJO'),
(243, 58, 'SALSA PICANTE'),
(244, 66, 'JUEGOS DIDACTICOS Y EDUCATIVOS'),
(245, 30, 'ESENCIA'),
(246, 56, 'PLASTICO'),
(247, 23, 'SHAMPOO'),
(248, 56, 'BOLSA PLASTICA'),
(249, 14, 'MISCELANEOS'),
(250, 66, 'LEARNING MAT'),
(251, 54, 'LOMO DE PORCINO'),
(252, 23, 'CREMA'),
(253, 54, 'PERNIL CON PIEL'),
(254, 54, 'CHULETA'),
(255, 14, 'DE SEGUNDA - SOLOMO ABIERTO, PALETA O CODILLO, PAPELON, COGOTE, LAGARTO SIN HUESO, FALDA'),
(256, 14, 'DE PRIMERA - CHOCOZUELA, POLLO DE RES, PULPA NEGRA, GANSO, MUCHACHO REDONDO, MUCHACHO CUADRADO'),
(257, 14, 'DE TERCERA - LAGARTO CON HUESO OSOBUCO, PECHO, COSTILLA'),
(258, 1, 'AGUA MINERAL'),
(259, 10, 'FORORO'),
(260, 15, 'CASABE'),
(261, 21, 'BARQUILLAS'),
(262, 21, 'TURRO'),
(263, 22, 'BOCADILLO'),
(264, 29, 'GUISANTES'),
(265, 29, 'PALMITO'),
(266, 29, 'MAIZ'),
(267, 29, 'CHAMPIÃ‘ONES'),
(268, 8, 'POLLO'),
(269, 38, 'CAFE'),
(270, 51, 'FIDEO'),
(271, 23, 'AFEITADORA'),
(272, 66, 'COOLER'),
(273, 78, 'COLADOR'),
(274, 66, 'JOCUND'),
(275, 78, 'CABLE DE EXTENSION ELECTRICA'),
(276, 25, 'YOGURT'),
(277, 43, 'FULLER'),
(278, 23, 'BAÃ‘O DE CREMA '),
(279, 7, 'LAVAPLATOS'),
(280, 39, 'JUGOS'),
(281, 54, 'TROZOS DE CERDO'),
(282, 14, 'TRASTE'),
(283, 34, 'MARGARINAS'),
(284, 66, 'CARROS Y CAMIONES'),
(285, 66, 'CARRO'),
(286, 66, 'MESA'),
(287, 66, 'PIANO'),
(288, 66, 'MASILLA'),
(289, 82, 'CHUPAS'),
(290, 65, 'CHUPAS'),
(291, 83, 'BATES'),
(292, 65, 'REGLAS'),
(293, 84, 'PLATOS'),
(294, 7, 'LIMPIA VIDRIOS'),
(295, 25, 'CREMA DE LECHE'),
(296, 29, 'JAMON ENDIABLADO'),
(297, 35, 'MEZCLA MAIZ BLANCO Y ARROZ'),
(298, 29, 'PEPITONA PICANTE'),
(299, 39, 'TE'),
(300, 23, 'PAPEL HIGIENICO'),
(301, 58, 'SALSA SURTIDA'),
(302, 48, 'PANALES DESECHABLES'),
(303, 35, 'HARINA DE YUCA'),
(304, 66, 'COMPUTADORA'),
(305, 35, 'HARINA DE MAIZ TOSTADO'),
(306, 66, 'AVION'),
(307, 20, 'COLORANTE'),
(308, 27, 'PREPARAR ALIMENTOS'),
(309, 29, 'SALSA'),
(310, 20, 'ESPECIES ARTIFICIALES'),
(311, 23, 'HIGIENE BUCAL'),
(312, 78, 'CUBIERTOS'),
(313, 23, 'HIGIENE CORPORAL'),
(314, 78, 'CESTAS'),
(315, 122, 'GEL FIJADOR PARA EL CABELLO'),
(316, 23, 'GEL FIJADOR PARA EL CABELLO'),
(317, 29, 'EMBUTIDO'),
(318, 64, 'ANTISEPTICO'),
(319, 64, 'ANTISEPTICO'),
(320, 86, 'PASTA'),
(321, 3, 'PRODUCTOS A BASE DE CEREALES'),
(322, 25, 'CONCENTRADOS DE LECHE'),
(323, 43, 'DESENGRASANTE'),
(324, 46, 'CAFÃ‰'),
(325, 39, 'JUGO DE FRUTAS'),
(326, 10, 'MERENGADAS'),
(327, 35, 'HARINA DE TRIGO'),
(328, 60, 'PAPEL HIGIENICO'),
(329, 23, 'MAQUINAS DE AFEITAR'),
(330, 23, 'COSMETICOS PARA BEBE'),
(331, 25, 'YOGURT LIQUIDO'),
(332, 87, 'CARNES PROCESADAS'),
(333, 66, 'PISTA DE CARRO'),
(334, 66, 'SONAJERO'),
(335, 66, 'KIT MEDICO'),
(336, 66, 'CABALLOS '),
(337, 66, 'TRANSFORMERS'),
(338, 66, 'CUERDA DE SALTAR'),
(339, 66, 'CARTERAS'),
(340, 53, 'AGUA DULCE'),
(341, 53, 'PESCADO PRE-EMPACADO DE AGUA DULCE'),
(342, 56, 'TENEDOR PLASTICO'),
(343, 66, 'BATE DE BEISBOL'),
(344, 25, 'SUERO DE LECHE'),
(345, 85, 'CARNES PROCESADAS'),
(346, 78, 'ELECTRONICOS'),
(347, 66, 'TREN'),
(348, 66, 'JUEGOS DE MESA'),
(349, 66, 'PROYECTOR'),
(350, 78, 'RALLO MULTI'),
(351, 78, 'UTENSILIOS DE COCINA'),
(352, 66, 'TABLERO'),
(353, 19, 'PULPAS'),
(354, 66, 'CAMION'),
(355, 66, 'ROMPECABEZAS'),
(356, 66, 'BICICLETAS'),
(357, 66, 'CASTILLO MAGICO'),
(358, 66, 'ESTUCHE'),
(359, 66, 'MAQUINA DE HELADO'),
(360, 66, 'SILLA MESEDORA'),
(361, 66, 'GUITARRA'),
(362, 66, 'YOYO'),
(363, 78, 'PARAGUAS'),
(364, 78, 'LENCERIA'),
(365, 88, 'HUEVOS'),
(366, 16, 'HOJUELAS DE MAIZ TOSTADA'),
(367, 23, 'COSMÃ‰TICO PARA LA PIEL'),
(368, 66, 'JUEGOS DIDACTICOS Y EDUCATIVOS'),
(369, 53, 'PESCADO SALADO'),
(370, 43, 'JABON LIQUIDO'),
(371, 44, 'REMOVEDOR DE GRASA'),
(372, 43, 'REMOVEDOR DE GRASA'),
(373, 58, 'MOSTAZA'),
(374, 23, 'TALCO  '),
(375, 23, 'TALCO'),
(376, 90, 'DETERGENTES'),
(377, 90, 'DESINFECTANTES'),
(378, 1, 'AGUA SABORIZADA'),
(379, 2, 'GRANOS'),
(380, 2, 'NUGGETS'),
(381, 2, 'PAPAS'),
(382, 2, 'POLLO'),
(383, 3, 'COMPOTAS'),
(384, 3, 'DERIVADOS LACTEOS'),
(385, 3, 'PRODUCTOS A BASE DE CEREALES'),
(386, 3, 'VITAMINAS'),
(387, 5, 'ALMUERZOS'),
(388, 6, 'AREPAS'),
(389, 5, 'ARROZ'),
(390, 5, 'AVENA'),
(391, 5, 'CACHAPAS'),
(392, 5, 'CAFÃ‰'),
(393, 5, 'CHOCOLATE'),
(394, 5, 'COMBO 1'),
(395, 5, 'CREMA DE ARROZ'),
(396, 5, 'EMPANADAS'),
(397, 5, 'ENSALADAS'),
(398, 5, 'FORORO'),
(399, 5, 'HALLAQUITAS'),
(400, 5, 'JUGOS '),
(401, 5, 'PANQUECAS'),
(402, 5, 'RACIONES'),
(403, 8, 'GALLINA'),
(404, 8, 'PAVO'),
(405, 8, 'POLLO'),
(406, 10, 'BEBIDAS EN POLVO'),
(407, 10, 'BEBIDAS GRANULADAS'),
(408, 10, 'MERENGADAS'),
(409, 11, 'MALTA'),
(410, 14, 'HUESOS: PATA, RABO, COSTILLA, HUESO ROJO O BLANCO'),
(411, 14, 'MISCELANEOS (HIGADO, PANZA, RABO, CORAZON, HUESO ROJO)'),
(412, 14, 'RIÃ‘ON DE RES'),
(413, 14, 'VISCERAS - BOFE, RIÃ‘ONES, CORAZON, HIGADO, LENGUA '),
(414, 16, 'ARROZ'),
(415, 16, 'AVENA'),
(416, 16, 'GRANOLA'),
(417, 16, 'HOJUELAS DE ARROZ TOSTADA'),
(418, 16, 'HOJUELAS DE MAIZ TOSTADA'),
(419, 16, 'HOJUELAS DE MAIZ, TRIGO Y AVENAS'),
(420, 16, 'HOJUELAS DE TRIGO TOSTADA'),
(421, 19, 'PULPAS'),
(422, 20, 'CONDIMENTOS'),
(423, 20, 'ESPECIAS'),
(424, 20, 'ESPECIAS ARTIFICIALES'),
(425, 21, 'ARROZ INFLADO'),
(426, 21, 'FRUTAS CONFITADAS'),
(427, 21, 'FRUTOS DESHIDRATADOS'),
(428, 21, 'MANI CONFITADO'),
(429, 22, 'CONSERVAS'),
(430, 22, 'DULCES'),
(431, 22, 'JARABES'),
(432, 22, 'MERMELADA'),
(433, 22, 'TURRON'),
(434, 25, 'AREQUIPE'),
(435, 25, 'AVENA PASTEURIZADA'),
(436, 25, 'CHICHA PASTEURIZADA'),
(437, 25, 'CONCENTRADOS DE LECHE'),
(438, 25, 'LECHE LIQUIDA'),
(439, 25, 'LECHE SABORIZADA'),
(440, 25, 'QUESOS FUNDIDOS'),
(441, 25, 'QUESOS'),
(442, 25, 'SUERO DE LECHE'),
(443, 25, 'YOGURT CON CEREALES'),
(444, 25, 'YOGURT FIRME'),
(445, 25, 'YOGURT LIQUIDO'),
(446, 26, 'PANELA'),
(447, 26, 'GRANULADO'),
(448, 87, 'CARNES PROCESADAS'),
(449, 28, 'BERENJENAS'),
(450, 28, 'ENCURTIDOS'),
(451, 28, 'OLIVAS'),
(452, 28, 'PEPINILLOS'),
(453, 29, 'ALBONDIGAS'),
(454, 29, 'ANCHOA'),
(455, 29, 'CARNES'),
(456, 29, 'CARAOTAS'),
(457, 29, 'CHAMPIGNONES'),
(458, 29, 'EMBUTIDOS'),
(459, 29, 'ENSALADAS'),
(460, 29, 'ESPARRAGOS'),
(461, 29, 'GUISANTES'),
(462, 29, 'JUREL'),
(463, 29, 'LENTEJAS'),
(464, 29, 'MAIZ'),
(465, 29, 'MARISCOS'),
(466, 29, 'NAVAJUELAS'),
(467, 29, 'PALMITOS'),
(468, 29, 'PEPITONAS'),
(469, 29, 'PIMIENTOS'),
(470, 29, 'TOMATES'),
(471, 29, 'VEGETALES'),
(472, 30, 'ESENCIAS'),
(473, 31, 'BULBO'),
(474, 31, 'COLES'),
(475, 31, 'ESENCIAS'),
(476, 31, 'ESPECIAS'),
(477, 31, 'FRUTOS'),
(478, 31, 'FRUTOS SECOS'),
(479, 31, 'HOJAS Y TALLOS TIERNOS'),
(480, 31, 'HOJAS DE PLATANO'),
(481, 31, 'LEGUMBRES'),
(482, 31, 'RAICES Y TUBERCULOS'),
(483, 31, 'VERDURAS SURTIDAS'),
(484, 33, 'GALLETAS INTEGRALES'),
(485, 33, 'GALLETAS SALADAS'),
(486, 34, 'MANTEQUILLA'),
(487, 34, 'MARGARINA'),
(488, 34, 'MANTECA'),
(489, 35, 'HARINA DE MAIZ TOSTADO'),
(490, 35, 'HARINA DE MAIZ Y ARROZ'),
(491, 35, 'HARINA DE YUCA'),
(492, 40, 'GARBANZOS'),
(493, 40, 'FRIJOLES'),
(494, 45, 'GELATINA'),
(495, 45, 'PUDIN'),
(496, 46, 'BEBIDAS'),
(497, 46, 'CAFE'),
(498, 46, 'GASEOSAS'),
(499, 46, 'JUGOS'),
(500, 46, 'JUGOS DE FRUTAS'),
(501, 46, 'LIMONADAS'),
(502, 46, 'PAPELON'),
(503, 46, 'LIMONADA'),
(504, 47, 'GALLETAS DULCES'),
(505, 47, 'PAN'),
(506, 47, 'PAN DULCE'),
(507, 47, 'TORTAS'),
(508, 50, 'PAPAS'),
(509, 51, 'PASTA LARGA'),
(510, 51, 'PASTA CORTA'),
(511, 53, 'CABEZA DE PESCADO'),
(512, 53, 'COLAS DE PESCADOS'),
(513, 53, 'PESCADO FRESCO DE AGUA DULCE'),
(514, 53, 'ESCAMA'),
(515, 53, 'CRUSTACEOS, MOLUSCOS Y MARISCOS'),
(516, 53, 'PESCADO FRESCO DE MAR'),
(517, 53, 'PESCADO SALADO'),
(518, 55, 'GELATINA'),
(519, 55, 'ARROZ CON LECHE'),
(520, 55, 'HELADOS'),
(521, 57, 'CONCENTRADOS DE FRUTAS'),
(522, 57, 'FRUTAS CONGELADAS'),
(523, 57, 'PULPAS DE FRUTAS'),
(524, 58, 'ADEREZOS'),
(525, 58, 'SALSAS'),
(526, 59, 'SNACK'),
(527, 78, 'RAQUETA  ELECTRICA'),
(528, 44, 'RAQUETA  ELECTRICA'),
(529, 25, 'NÃ‰CTAR  DE  GUAYABA'),
(530, 25, 'JUGO  '),
(531, 115, 'CALZADO CLASICO COLEGIAL'),
(532, 66, 'GUANTES DE BOXEO'),
(533, 65, 'GANCHOS PARA ROPA'),
(534, 26, 'PIAZZA'),
(535, 7, 'CREMAS'),
(536, 7, 'ASEO'),
(537, 7, 'LIMPIEZA'),
(538, 78, 'CONTROL UNIVERSAL'),
(539, 78, 'MONEDERO'),
(540, 91, 'PORTA CALIENTE'),
(541, 78, 'BABEROS'),
(542, 66, 'MOTO'),
(543, 43, 'DESINFECTANTES'),
(544, 92, 'CARNES PROCESADAS'),
(545, 33, 'GALLETAS DULCES'),
(546, 93, 'ACEITES VEGETALES'),
(547, 94, 'MARGARINA'),
(548, 24, 'CHOCOLATE'),
(549, 78, 'PEINES'),
(550, 91, 'CESTA DE MADERA'),
(551, 53, 'PESCADO PRE-EMPACADO DE MAR'),
(552, 95, 'MAIZ INFLADO'),
(553, 35, 'HARINAS DE MAIZ FINA'),
(554, 43, 'DETERGENTES'),
(555, 94, 'MANTEQUILLA'),
(556, 96, 'EN POLVO'),
(557, 29, 'ALCACHOFAS'),
(558, 66, 'ALFOMBRAS'),
(559, 66, 'BILLAR'),
(560, 66, 'SET PARA COLOREAR'),
(561, 43, 'MULTIUSO'),
(562, 43, 'GENTIMICIDA'),
(563, 82, 'CONTROL  UNIVERSAL  DE  AIRE'),
(564, 35, 'HARINAS  DE  MAIZ'),
(565, 29, 'ATÃšN   NATURAL'),
(566, 12, 'BOLSAS  DE  ASA'),
(567, 43, 'LIMPIADOR  EN POLVO'),
(568, 43, 'DESGRASADOR'),
(569, 43, 'DESMANCHADOR'),
(570, 43, ' JABONOSA'),
(571, 66, 'CAJA DE HERRAMIENTA'),
(572, 100, 'MEZCLA PARA PURE DE PAPA INSTANTANEO'),
(573, 43, 'MULTILIMPIADOR'),
(574, 66, 'CASA DE FAMILIA'),
(575, 29, 'CARNE  DE ALMUERZO'),
(576, 10, 'MERENGADAS'),
(577, 1, 'MERENGADAS'),
(578, 10, 'MERENGADAS'),
(579, 60, 'SEVILLETAS '),
(580, 93, 'ACEITE'),
(581, 104, 'ALUMINIO'),
(582, 106, 'PLANCHA PARA EL CABELLO'),
(583, 66, 'muneco'),
(584, 66, 'MUÃ‘ECO'),
(585, 21, 'PASAS'),
(586, 65, 'TERMOS'),
(587, 107, 'POLVO DE HORNEAR'),
(588, 35, 'ALMIDON'),
(589, 22, 'GALLETAS'),
(590, 41, 'COBIJA'),
(591, 41, 'EDREDON'),
(592, 108, 'PULPAS'),
(593, 56, 'ENVASE CONTENEDOR'),
(594, 56, 'KIT DE CUBIERTOS'),
(595, 23, 'TOALLAS SANITARIAS'),
(596, 41, 'MOTAS'),
(597, 23, 'JABON'),
(598, 48, 'TOALLAS  CLINICA   POST  PARTO'),
(599, 92, 'QUESO'),
(600, 23, 'HISOPOS'),
(601, 48, 'CENTRO  DE  CAMA'),
(602, 23, 'CEPILLO  DENTAL'),
(603, 53, 'SARDINA'),
(604, 78, 'ARBOL'),
(605, 78, 'ADORNOS  '),
(606, 78, 'ADORNOS, ARREGLOS Y ARTICULOS DE NAVIDAD'),
(607, 17, 'PECHUGA  DE  PAVO'),
(608, 23, 'PROTECTORES DIARIOS'),
(609, 10, 'FLAN'),
(610, 55, 'PUDIN'),
(611, 108, 'CONCENTRADOS DE TOMATE'),
(612, 66, 'MUNECAS'),
(613, 78, 'SILLA'),
(614, 78, 'GANCHO'),
(615, 23, 'CEPILLO  PARA  EL  CABELLO'),
(616, 23, 'COMBO   BEAUTICIAN'),
(617, 22, 'CORTA  CUTICULA  '),
(618, 23, 'CORTA  CUTICULA'),
(619, 78, 'LANPARA'),
(620, 78, 'SET  DE 6  CUCHARILLAS'),
(621, 78, 'PORTA  CD  PEQUENO'),
(622, 78, 'ROMPECABEZA  DE  MADERA'),
(623, 78, 'PORTA  CD'),
(624, 23, 'PINTURA  '),
(625, 106, 'CARTERA'),
(626, 66, 'BOTE'),
(627, 66, 'CALCULADORA'),
(628, 66, 'BANERA'),
(629, 66, 'JUGUETES  DE COCINA'),
(630, 66, 'JUEGO  DE    BOLICHE'),
(631, 66, 'ROPA DE MUNECAS'),
(632, 110, 'JUEGOS DE MESA'),
(633, 66, 'JUGUETES'),
(634, 66, 'MUNECAS Y ACCESORIOS'),
(635, 66, 'POOL'),
(636, 111, 'BICICLETAS'),
(637, 111, 'MOTOS PARA NINOS'),
(638, 111, 'TRICICLOS'),
(639, 112, 'MONOPATIN'),
(640, 112, 'MONOPATIN, PATINES Y PATINETAS'),
(641, 13, 'P'),
(642, 23, 'PINTURA   DE  UNAS'),
(643, 113, 'ESCOLARES'),
(644, 113, 'ESCOLARES'),
(645, 114, 'PAPELERIA, ARTICULOS Y UTILES ESCOLARES'),
(646, 113, 'CARPETA  MARRON'),
(647, 115, 'CHEMIS  ESCOLAR'),
(648, 115, 'PANTALON  ESCOLAR'),
(649, 116, 'CENTRO DE CAMA DESECHABLES'),
(650, 117, 'CREMAS Y LIQUIDOS PARA LIMPIAR ZAPATOS'),
(651, 118, 'PLATOS, VASOS, CUBIERTOS Y BOLSAS '),
(652, 6, 'ALUMINIO'),
(653, 118, 'BOLSAS PARA CONSERVAR ALIMENTOS'),
(654, 115, 'FRANELAS'),
(655, 7, 'PASTILLA  DE  BANO'),
(656, 23, 'GEL   DUCHA'),
(657, 23, 'CUIDADO  DE  BEBE'),
(658, 65, 'MICROONDAS'),
(659, 27, 'LINEA  BLANCA '),
(660, 82, 'BATIDOR'),
(661, 56, 'CUBIERTOS, PLATOS, VASOS Y BOLSAS'),
(662, 56, 'PLASTICOS '),
(663, 120, 'ENVASES'),
(664, 121, 'DE COCINA'),
(665, 43, 'BLANQUEADOR DE ROPA'),
(666, 23, 'JABON TOCADOR LIQUIDO'),
(667, 74, 'GEL FIJADOR'),
(668, 39, 'JUGOS DE FRUTAS Y NECTARES'),
(669, 46, 'TE'),
(670, 46, 'PAPELON CON LIMON'),
(671, 55, 'POSTRES DE FRUTAS'),
(672, 10, 'BEBIDAS GRANULADAS'),
(673, 10, 'BEBIDAS GRANULADAS'),
(674, 29, 'PECHUGA DE POLLO'),
(675, 56, 'CARTON'),
(676, 23, 'BALSAMO'),
(677, 23, 'CUIDADO DEL CABELLO'),
(678, 107, 'LEVADURAS'),
(679, 78, 'EDREDON'),
(680, 127, 'COLCHON'),
(681, 5, 'PAPAS'),
(682, 35, 'MEZCLA PARA HACER CACHAPAS'),
(683, 95, 'PAPAS FRITAS PARA PERROS CALIENTES Y HAMBURGUESAS'),
(684, 95, 'PAPAS  FRITAS'),
(685, 78, 'BASE PORTA CALIENTE PARA OLLA'),
(686, 78, 'TAZAS Y VASOS'),
(687, 120, 'PAPEL DE ALUMINIO'),
(688, 128, 'PULPAS Y FRUTAS'),
(689, 129, 'ACOLCHADOS'),
(690, 130, 'COSTURA Y MANUALIDADES'),
(691, 25, 'POSTRES TIPICOS Y CASEROS'),
(692, 78, 'ARTICULOS DEL HOGAR'),
(693, 78, 'AGUJAS'),
(694, 55, 'POSTRE DE PARCHITA'),
(695, 119, 'VENTILADORES'),
(696, 27, 'VENTILADORES'),
(697, 131, 'OLLAS Y SARTENES'),
(698, 132, 'BATIDORES'),
(699, 129, 'POLTRONA'),
(700, 129, 'BOX PRINT'),
(701, 129, 'PATAS DE MADERA'),
(702, 41, 'CUBRECAMAS'),
(703, 27, 'ARTICULOS DEL HOGAR'),
(704, 66, 'JUEGOS Y JUGUETES'),
(705, 133, 'ACCESORIOS Y ARTICULOS PARA BEBES'),
(706, 134, 'INTEGRALES'),
(707, 5, 'AREPAS'),
(708, 5, 'PAN Y DERIVADOS'),
(709, 65, 'PRODUCTOS PLASTICOS '),
(710, 135, 'FRUVERH'),
(711, 129, 'COLCHONES'),
(712, 65, 'POTE TERMICO'),
(713, 41, 'ZAPATERA'),
(714, 41, 'SABANAS Y COBIJAS'),
(715, 136, 'CAJA/BOLSA CLAP'),
(716, 31, 'HORTALIZAS'),
(717, 40, 'LENTEJA'),
(718, 40, 'ARVEJA AMARILLA'),
(719, 40, 'ARVEJA VERDE'),
(720, 40, 'CAROTA ROJA '),
(721, 137, 'CAUCHOS Y TRIPAS'),
(722, 78, 'SABANAS Y COBIJAS'),
(723, 27, 'LICUADORA'),
(724, 27, 'CAFETERA'),
(725, 82, 'SECADOR'),
(726, 82, 'TOSTADORA'),
(727, 82, 'SANDWICHERA'),
(728, 82, 'PLANCHA'),
(729, 65, 'SANDWICHERA'),
(730, 83, 'BALON DE FUTBOL'),
(731, 29, 'GARBANZOS'),
(732, 17, 'PASTA DE HIGADO'),
(733, 11, 'REFRESCO'),
(734, 35, 'HARINA DE TRIGO INTEGRAL'),
(735, 66, 'SILLA INFANTIL'),
(736, 49, 'ACUARELA'),
(737, 1, 'TRICICLO'),
(738, 66, 'TRICICLO'),
(739, 138, 'JARABE'),
(740, 139, 'ANALGESICOS'),
(741, 7, 'PALO DE MADERA (ESCOBA)'),
(742, 114, 'CARPETA'),
(743, 5, 'POSTRES'),
(744, 140, 'NEVERA'),
(745, 127, 'AIRE ACONDICIONADO'),
(746, 140, 'REFRIGERADOR'),
(747, 140, 'COCINA'),
(748, 140, 'CONGELADOR HORIZONTAL'),
(749, 140, 'LAVADORA'),
(750, 43, 'SUAVIZANTES'),
(751, 43, 'CERA'),
(752, 141, 'BANDEJAS DE ANIME'),
(753, 65, 'COPA'),
(754, 78, 'JARRA'),
(755, 140, 'TELEVISOR'),
(756, 84, 'TENEDOR'),
(757, 84, 'CUCHILLO'),
(758, 84, 'CUCHARA GRANDE'),
(759, 84, 'CUCHARA PEQUEÃ‘A'),
(760, 115, 'OTROS'),
(761, 132, 'EXPRIMIDOR DE NARANJA'),
(762, 142, 'CRISTALERIA'),
(763, 78, 'VASOS'),
(764, 143, 'INOPLAS'),
(765, 78, 'JARRAS'),
(766, 144, 'JEANS'),
(767, 145, 'MUEBLES'),
(768, 146, 'JEANS'),
(769, 147, 'BOLSOS'),
(770, 147, 'CARTERAS'),
(771, 147, 'CARTERAS'),
(772, 153, 'BOLSAS'),
(773, 144, 'JEANS'),
(774, 146, 'JEANS'),
(775, 147, 'BOLSOS'),
(776, 144, 'PIJAMA'),
(777, 144, 'BERMUDAS'),
(778, 149, 'BILLETERAS Y MONEDEROS'),
(779, 144, 'SHORT'),
(780, 144, 'BLUSAS'),
(781, 150, 'ZAPATOS DE CABALLERO'),
(782, 78, 'CAMAS'),
(783, 146, 'CAMISAS'),
(784, 145, 'SILLAS'),
(785, 150, 'ZAPATILLAS PARA DAMAS'),
(786, 144, 'PANTALONES'),
(787, 146, 'CHAQUETAS'),
(788, 154, 'CHEMISSE'),
(789, 146, 'CHEMISSE'),
(790, 147, 'CARTERAS'),
(791, 7, 'ASEO'),
(792, 41, 'PANOS Y TOALLAS'),
(793, 145, 'MUEBLES'),
(794, 147, 'BOLSOS'),
(795, 41, 'CORTINAS'),
(796, 146, 'FRANELAS'),
(797, 145, 'GAVETEROS'),
(798, 149, 'BILLETERAS'),
(799, 41, 'PANOS Y TOALLAS'),
(800, 144, 'LEGGINS'),
(801, 124, 'ZAPATILLAS PARA DAMA'),
(802, 147, 'BOLSO TERMICO'),
(803, 144, 'MONO'),
(804, 78, 'UTENSILIOS DE COCINA'),
(805, 78, 'OLLAS'),
(806, 149, 'PISA BILLETES'),
(807, 155, 'RINES'),
(808, 124, 'SANDALIAS DE DAMA'),
(809, 124, 'SANDALIAS DE NINAS'),
(810, 153, 'BOLSAS'),
(811, 144, 'SHORT'),
(812, 145, 'SILLAS'),
(813, 144, 'LEGGIS'),
(814, 78, 'VASOS'),
(815, 144, 'VESTIDOS'),
(816, 150, 'ZAPATOS'),
(817, 41, 'TOALLAS'),
(818, 145, 'CAMA'),
(819, 145, 'MESA'),
(820, 144, 'BODYS'),
(821, 124, 'ZAPATO DE CABALLERO'),
(822, 156, 'VESTIDOS'),
(823, 158, 'VESTIDOS'),
(824, 150, 'ZAPATO'),
(825, 146, 'PANTALONES'),
(826, 154, 'PANTALONES'),
(827, 154, 'JEANS'),
(828, 150, 'SANDALIA DE DAMAS'),
(829, 150, 'SANDALIA DE NINAS'),
(830, 127, 'LITERAS'),
(831, 127, 'JUEGO DE RECIBO'),
(832, 78, 'COJIN'),
(833, 78, 'PROTECTOR DE ALMOHADA'),
(834, 78, 'PROTECTOR DE COLCHON'),
(835, 161, 'PASTA LARGA CUSPAL'),
(836, 145, 'CORTINEROS'),
(837, 65, 'PARAGUAS'),
(838, 144, 'TRAJE DE BANOS'),
(839, 158, 'TRAJE DE BANOS'),
(840, 140, 'SECADORA'),
(841, 140, 'AIRE ACONDICIONADO'),
(842, 127, 'TELEVISORES'),
(843, 127, 'VENTILADOR'),
(844, 140, 'HORNOS'),
(845, 163, 'MARGARINA'),
(846, 25, 'SIN LACTOSA'),
(847, 78, 'SACOS'),
(848, 20, 'PIMIENTA'),
(849, 164, 'ALMIDON'),
(850, 65, 'EXPRIMIDOR DE LIMON'),
(851, 65, 'EXPRIMIDOR DE NARANJA'),
(852, 65, 'JARRAS / JARROS'),
(853, 65, 'JUEGO DE OLLAS'),
(854, 65, 'PLATON DE ALUMINIO'),
(855, 65, 'CALDEROS'),
(856, 65, 'ESCUDILLAS'),
(857, 65, 'JUEGO DE POTES DE COCINA'),
(858, 65, 'JUEGO DE TORTERAS'),
(859, 65, 'QUESILLERA'),
(860, 65, 'PORTACOLADOR DE CAFE'),
(861, 65, 'VASOS'),
(862, 65, 'BALDES'),
(863, 65, 'CALDEROS'),
(864, 65, 'ESCUDILLAS'),
(865, 65, 'CUCHARON'),
(866, 26, 'MIEL DE ABEJA'),
(867, 39, 'TE NEGRO '),
(868, 40, 'QUINCHONCHO'),
(869, 58, 'PURE'),
(870, 115, 'PANTALONES'),
(871, 154, 'JUMPER'),
(872, 154, 'LEGGIINS'),
(873, 158, 'CAMISETA'),
(874, 158, 'BLUSA'),
(875, 154, 'CAMISETA'),
(876, 154, 'SHOR'),
(877, 154, 'CONJUNTOS'),
(878, 146, 'CAMISETAS'),
(879, 144, 'CHAQUETAS'),
(880, 144, 'PRENDA PRENATAL'),
(881, 154, 'MEDIAS'),
(882, 41, 'SET DE CAMAS'),
(883, 154, 'CAMISAS'),
(884, 146, 'PIJAMA'),
(885, 146, 'SHORS'),
(886, 154, 'TRAJE DE BANO'),
(887, 144, 'BIKINIS'),
(888, 154, 'INTERIORES'),
(889, 114, 'CUADERNOS'),
(890, 158, 'BATICAS'),
(891, 144, 'CONJUNTOS'),
(892, 165, 'MASA, TEQUENOS, PASTELITOS, PAN DE BONO, PASAPALOS, EMPANADAS'),
(893, 20, 'CEBOLLIN'),
(894, 20, 'CEBOLLIN EN POLVO'),
(895, 20, 'CILANTRO EN POLVO'),
(896, 23, 'ALGODON'),
(897, 136, 'COMBO DESAYUNO'),
(898, 166, 'CAMBO DESAYUNO'),
(899, 167, 'COMBOS AHORRO FAMILIAR, HOGAR Y PERSONAL'),
(900, 59, 'GIRASOL'),
(901, 59, 'LINAZA'),
(902, 11, 'SODA'),
(903, 168, 'COMBO 1'),
(904, 169, 'MATERIA PRIMA MAIZ BLANCO'),
(905, 170, 'CEBADA'),
(906, 171, 'ALPISTE'),
(907, 168, 'COMBOS VARIADOS'),
(908, 172, 'ARRENDAMIENTO'),
(909, 173, 'PILAS'),
(910, 23, 'LOCION'),
(911, 23, 'ASEO  PERSONAL'),
(912, 179, 'COMBO '),
(913, 178, 'COMBO '),
(914, 65, 'TANQUE  PARA AGUA'),
(915, 150, 'CHOLAS'),
(916, 150, 'CHOLAS PLASTICAS NINAS Y NINOS'),
(917, 156, 'FALDAS'),
(918, 158, 'FALDAS'),
(919, 158, 'FRANELA'),
(920, 154, 'FRANELA'),
(921, 144, 'CAMISETA'),
(922, 172, 'EMPAQUETADO'),
(923, 65, 'PAELLERA'),
(924, 65, 'SARTEN'),
(925, 146, 'SWETER'),
(926, 144, 'SWETER'),
(927, 144, 'CHEMISE'),
(928, 78, 'SABANA COBIJAS TOALLA'),
(929, 65, 'CINTA ADHESIVA'),
(930, 20, 'AJOPORRO'),
(931, 181, 'ACCESORIOS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabla_impuestos`
--

DROP TABLE IF EXISTS `tabla_impuestos`;
CREATE TABLE IF NOT EXISTS `tabla_impuestos` (
  `id_tabla_impuestos` int(32) NOT NULL AUTO_INCREMENT,
  `id_documento` int(32) UNSIGNED NOT NULL COMMENT 'id Factura y/o Compra',
  `tipo_documento` varchar(2) NOT NULL,
  `numero_control_factura` varchar(20) NOT NULL COMMENT 'Numero de Control de Factura o Compra',
  `id_fiscal` varchar(20) NOT NULL DEFAULT '0.00',
  `id_cliente` int(11) NOT NULL COMMENT 'Id Cliente o Proveedor',
  `cod_tipo_impuesto` int(11) NOT NULL,
  `cod_impuesto` int(11) NOT NULL,
  `totalizar_pbase_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_monto_retencion` decimal(10,2) NOT NULL DEFAULT '0.00',
  `totalizar_base_imponible` decimal(10,2) NOT NULL,
  `totalizar_monto_exento` decimal(10,2) NOT NULL,
  `usuario_creacion` varchar(50) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tabla_impuestos`),
  KEY `totalizar_monto_retencion` (`totalizar_monto_retencion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasas_cambio`
--

DROP TABLE IF EXISTS `tasas_cambio`;
CREATE TABLE IF NOT EXISTS `tasas_cambio` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `divisa` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tasa` float DEFAULT NULL,
  `monedabase` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tesor_bancodet`
--

DROP TABLE IF EXISTS `tesor_bancodet`;
CREATE TABLE IF NOT EXISTS `tesor_bancodet` (
  `cod_tesor_bandodet` int(32) NOT NULL AUTO_INCREMENT,
  `cod_banco` int(32) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `nro_cuenta` varchar(50) NOT NULL,
  `cuenta_contable` varchar(32) NOT NULL,
  `comision_tarjeta_debito` int(11) NOT NULL,
  `comision_tarjeta_credito` int(11) NOT NULL,
  `comision_idb` int(11) NOT NULL,
  `retencion_islr` int(11) NOT NULL,
  `cod_tipo_cuenta_banco` int(32) NOT NULL,
  `monto_apertura` decimal(10,2) NOT NULL,
  `monto_disponible` decimal(10,2) NOT NULL,
  `fecha_apertura` date NOT NULL DEFAULT '0000-00-00',
  `usuario_creacion` varchar(50) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`cod_tesor_bandodet`),
  KEY `cod_banco` (`cod_banco`),
  KEY `cod_tipo_cuenta_banco` (`cod_tipo_cuenta_banco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets_entrada_salida`
--

DROP TABLE IF EXISTS `tickets_entrada_salida`;
CREATE TABLE IF NOT EXISTS `tickets_entrada_salida` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `id_conductor` int(11) NOT NULL COMMENT 'Clave Foranea Condutor',
  `hora_entrada` datetime NOT NULL,
  `hora_salida` datetime NOT NULL,
  `peso_entrada` decimal(15,2) NOT NULL,
  `peso_salida` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_conductor` (`id_conductor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_control_pyme`
--

DROP TABLE IF EXISTS `ticket_control_pyme`;
CREATE TABLE IF NOT EXISTS `ticket_control_pyme` (
  `ticket_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ticket_control_pyme`
--

INSERT INTO `ticket_control_pyme` (`ticket_id`) VALUES
('0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cliente`
--

DROP TABLE IF EXISTS `tipo_cliente`;
CREATE TABLE IF NOT EXISTS `tipo_cliente` (
  `cod_tipo_cliente` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_tipo_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_cliente`
--

INSERT INTO `tipo_cliente` (`cod_tipo_cliente`, `descripcion`) VALUES
(0, 'PDVAL Ventas'),
(2, 'PAE'),
(3, 'Clientes Credito'),
(4, 'PDVALITO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_comercio`
--

DROP TABLE IF EXISTS `tipo_comercio`;
CREATE TABLE IF NOT EXISTS `tipo_comercio` (
  `cod_tipo_comercio` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`cod_tipo_comercio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_comercio`
--

INSERT INTO `tipo_comercio` (`cod_tipo_comercio`, `descripcion`) VALUES
(1, 'COMERCIO UNICO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cuenta`
--

DROP TABLE IF EXISTS `tipo_cuenta`;
CREATE TABLE IF NOT EXISTS `tipo_cuenta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_cuenta` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_cuenta`
--

INSERT INTO `tipo_cuenta` (`id`, `tipo_cuenta`) VALUES
(1, 'Ingreso'),
(2, 'IVA1'),
(3, 'IVA2'),
(4, 'IVA3'),
(5, 'Otros Ingresos'),
(6, 'Perdida'),
(7, 'CXC');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cuenta_banco`
--

DROP TABLE IF EXISTS `tipo_cuenta_banco`;
CREATE TABLE IF NOT EXISTS `tipo_cuenta_banco` (
  `cod_tipo_cuenta_banco` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`cod_tipo_cuenta_banco`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_cuenta_banco`
--

INSERT INTO `tipo_cuenta_banco` (`cod_tipo_cuenta_banco`, `descripcion`) VALUES
(1, 'Corriente'),
(2, 'Activos Liquidos'),
(3, 'Ahorro'),
(4, 'Participaciones'),
(5, 'Fideicomisos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_despacho`
--

DROP TABLE IF EXISTS `tipo_despacho`;
CREATE TABLE IF NOT EXISTS `tipo_despacho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_despacho`
--

INSERT INTO `tipo_despacho` (`id`, `descripcion`) VALUES
(1, 'Ventas Especiales'),
(2, 'Ventas a Terceros'),
(5, 'Casa x Casa'),
(6, 'Punto y Circulo'),
(7, 'Traspaso'),
(8, 'Salida por Inutilizacion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_impuesto`
--

DROP TABLE IF EXISTS `tipo_impuesto`;
CREATE TABLE IF NOT EXISTS `tipo_impuesto` (
  `cod_tipo_impuesto` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(80) NOT NULL,
  `cuenta_contable` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`cod_tipo_impuesto`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_impuesto`
--

INSERT INTO `tipo_impuesto` (`cod_tipo_impuesto`, `descripcion`, `cuenta_contable`) VALUES
(1, 'Impuesto al Valor Agregado (IVA)', '2.1.04.002.'),
(2, 'Impuesto Sobre La Renta  (ISLR)', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_movimientos_ban`
--

DROP TABLE IF EXISTS `tipo_movimientos_ban`;
CREATE TABLE IF NOT EXISTS `tipo_movimientos_ban` (
  `cod_tipo_movimientos_ban` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`cod_tipo_movimientos_ban`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_movimientos_ban`
--

INSERT INTO `tipo_movimientos_ban` (`cod_tipo_movimientos_ban`, `descripcion`) VALUES
(1, 'Cheque'),
(2, 'Deposito'),
(3, 'Tarjeta de Debito'),
(4, 'Tarjeta de Credito'),
(5, 'Transferencias  Otros Bancos'),
(6, 'Transferencias Internas'),
(7, 'Comision Tarjeta de Debito'),
(8, 'Comision Tarjeta de Credito'),
(9, 'Retencion I.S.l.R.'),
(10, 'Retencion 4*1000'),
(11, 'Impuesto al Debito Bancario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_movimiento_almacen`
--

DROP TABLE IF EXISTS `tipo_movimiento_almacen`;
CREATE TABLE IF NOT EXISTS `tipo_movimiento_almacen` (
  `id_tipo_movimiento_almacen` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  `operacion` varchar(1) NOT NULL,
  `movimiento_servicio` int(11) DEFAULT '0',
  PRIMARY KEY (`id_tipo_movimiento_almacen`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_movimiento_almacen`
--

INSERT INTO `tipo_movimiento_almacen` (`id_tipo_movimiento_almacen`, `descripcion`, `operacion`, `movimiento_servicio`) VALUES
(1, 'Compras', '+', 1),
(2, 'Ventas', '-', 2),
(3, 'Cargo', '+', 3),
(4, 'Descargo', '-', 4),
(5, 'Traslado', '+', 5),
(6, 'Traslado', '-', 5),
(7, 'Pedido', '+', 6),
(8, 'Pedido', '-', 6),
(9, 'Ajuste', '+', 7),
(10, 'Ajuste', '-', 7),
(11, 'Trasnformacion Entrada', '+', 8),
(12, 'Transformacion Salida', '-', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_origen_proveedor`
--

DROP TABLE IF EXISTS `tipo_origen_proveedor`;
CREATE TABLE IF NOT EXISTS `tipo_origen_proveedor` (
  `cod_tipo_origen_proveedor` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(60) NOT NULL,
  PRIMARY KEY (`cod_tipo_origen_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_origen_proveedor`
--

INSERT INTO `tipo_origen_proveedor` (`cod_tipo_origen_proveedor`, `descripcion`) VALUES
(1, 'Natural'),
(2, 'Extranjero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_precio`
--

DROP TABLE IF EXISTS `tipo_precio`;
CREATE TABLE IF NOT EXISTS `tipo_precio` (
  `cod_tipo_precio` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_tipo_precio`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_precio`
--

INSERT INTO `tipo_precio` (`cod_tipo_precio`, `descripcion`) VALUES
(1, 'Libre'),
(2, 'Precio Detal'),
(3, 'Precio Empleado'),
(4, 'Precio al Mayor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_precio_item`
--

DROP TABLE IF EXISTS `tipo_precio_item`;
CREATE TABLE IF NOT EXISTS `tipo_precio_item` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_precio_item`
--

INSERT INTO `tipo_precio_item` (`id`, `descripcion`) VALUES
(1, 'Red Comercial'),
(2, 'PAE'),
(3, 'Pdvalito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_proveedor`
--

DROP TABLE IF EXISTS `tipo_proveedor`;
CREATE TABLE IF NOT EXISTS `tipo_proveedor` (
  `cod_tipo_proveedor` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(60) NOT NULL,
  PRIMARY KEY (`cod_tipo_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_proveedor`
--

INSERT INTO `tipo_proveedor` (`cod_tipo_proveedor`, `descripcion`) VALUES
(1, 'Normal'),
(2, 'No Residenciado'),
(3, 'No Domiciliado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_proveedor_clasif`
--

DROP TABLE IF EXISTS `tipo_proveedor_clasif`;
CREATE TABLE IF NOT EXISTS `tipo_proveedor_clasif` (
  `id_pclasif` int(11) NOT NULL AUTO_INCREMENT,
  `clasificacion` varchar(60) NOT NULL,
  PRIMARY KEY (`id_pclasif`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_proveedor_clasif`
--

INSERT INTO `tipo_proveedor_clasif` (`id_pclasif`, `clasificacion`) VALUES
(1, 'Comercial'),
(2, 'Medico'),
(3, 'Empleado'),
(4, 'Tercero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_toma`
--

DROP TABLE IF EXISTS `tipo_toma`;
CREATE TABLE IF NOT EXISTS `tipo_toma` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Tipos de tomas de inventario';

--
-- Volcado de datos para la tabla `tipo_toma`
--

INSERT INTO `tipo_toma` (`id`, `descripcion`) VALUES
(1, 'Rapida'),
(2, 'Completa'),
(3, 'Aleatorio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_transaccion`
--

DROP TABLE IF EXISTS `tipo_transaccion`;
CREATE TABLE IF NOT EXISTS `tipo_transaccion` (
  `cod_tipo_transaccion` int(32) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`cod_tipo_transaccion`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_transaccion`
--

INSERT INTO `tipo_transaccion` (`cod_tipo_transaccion`, `codigo`, `descripcion`) VALUES
(1, 'AJU', 'AJUSTE AL INVENTARIO'),
(2, 'CAR', 'CARGOS AL INVENTARIO'),
(3, 'DES', 'DESCARGOS DEL INVENTARIO'),
(4, 'FAC', 'FACTURAS COMPRA - VENTA'),
(5, 'FACxESP', 'FACTURAS EN ESPERA SIN RESERVAR'),
(6, 'FACxRES', 'FACTURA EN ESPERA CON RESERVA'),
(7, 'GIRO', 'GIRO'),
(8, 'NxC', 'NOTA DE CREDITO'),
(9, 'N/CxP/A', 'NOTA DE CREDITO POR PAGO ANTICIPADO'),
(10, 'N/D', 'NOTA DE DEBITO'),
(11, 'DEVxFAC', 'DEVOLUCION FACTURA COMPRA - VENTA'),
(12, 'DEVxN/E', 'DEVOLUCION NOTAS DE ENTREGA COMPRA - VEN'),
(13, 'N/CxFAC', 'NOTA DE CREDITO A FACTURA'),
(14, 'N/CxGIRO', 'NOTA DE CREDITO A GIRO'),
(15, 'N/CxIMP', 'NOTA DE CREDITO RETENCION IMPUESTO'),
(16, 'N/CxN/D', 'NOTA DE CREDITO A NOTA DE DEBITO'),
(17, 'N/CxRETIMP', 'NOTA DE CREDITO RET. SOBRE IMPUESTO'),
(18, 'N/DxFAC', 'NOTA DE DEBITO A FACTURA'),
(19, 'N/DxGIRO', 'NOTA DE DEBITO A GIRO'),
(20, 'N/DxIMP', 'NOTA DE DEBITO RETENCION IMPUESTO'),
(21, 'N/DxN/C', 'NOTA DE DEBITO A NOTA DE CREDITO'),
(22, 'N/DxRETIMP', 'NOTA DE DEBITO RET. SOBRE IMPUESTO'),
(23, 'PAGxGIRO', 'PAGO GIRO'),
(24, 'PAGxN/C', 'PAGO A NOTA DE CREDITO'),
(25, 'PAGxN/D', 'PAGO NOTA DE DEBITO'),
(26, 'PAGxFAC', 'PAGO O ABONO FACTURA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_uso`
--

DROP TABLE IF EXISTS `tipo_uso`;
CREATE TABLE IF NOT EXISTS `tipo_uso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_uso`
--

INSERT INTO `tipo_uso` (`id`, `tipo`) VALUES
(1, 'Consumo Humano'),
(2, 'Consumo ABA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_visitas`
--

DROP TABLE IF EXISTS `tipo_visitas`;
CREATE TABLE IF NOT EXISTS `tipo_visitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_visita` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_visitas`
--

INSERT INTO `tipo_visitas` (`id`, `descripcion_visita`) VALUES
(1, 'Fumigacion por insectos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tomas_fisicas`
--

DROP TABLE IF EXISTS `tomas_fisicas`;
CREATE TABLE IF NOT EXISTS `tomas_fisicas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `fecha_apertura` datetime NOT NULL COMMENT 'Fecha en que se comienza la Toma Fisica',
  `fecha_cierre` datetime NOT NULL COMMENT 'Fecha en que se termina la Toma Fisica',
  `tipo_toma` int(11) NOT NULL COMMENT 'Tipo de Toma 0= , 1=',
  `id_ubicacion` int(11) NOT NULL,
  `id_almacen` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL COMMENT 'Usuario que realiza la Toma',
  `toma` tinyint(1) NOT NULL DEFAULT '1',
  `estatus_toma` int(11) NOT NULL COMMENT '1=terminada',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tomas de Fisicas Inventario';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tomas_fisicas_detalle`
--

DROP TABLE IF EXISTS `tomas_fisicas_detalle`;
CREATE TABLE IF NOT EXISTS `tomas_fisicas_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `id_mov` int(11) NOT NULL COMMENT 'Clave Foranea de la Cebecera',
  `cod_bar` varchar(50) NOT NULL COMMENT 'Producto',
  `inv_sistema` decimal(11,2) NOT NULL COMMENT 'Inventario segun sistema',
  `toma1` decimal(11,2) DEFAULT NULL COMMENT 'Toma Fisica 1',
  `toma2` decimal(11,2) DEFAULT NULL COMMENT 'Toma Fisica 2',
  `tomadef` decimal(11,2) DEFAULT NULL COMMENT 'Toma Definitiva',
  `mov_sugerido` decimal(10,0) NOT NULL COMMENT 'Movimiento Sugerido para Ajuste de Inventario',
  `id_llenado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Detalle de las Tomas Fisicas de Inventario';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferencia`
--

DROP TABLE IF EXISTS `transferencia`;
CREATE TABLE IF NOT EXISTS `transferencia` (
  `transferencia_pk` int(32) NOT NULL AUTO_INCREMENT,
  `transferencia_numero` varchar(50) NOT NULL,
  `tesor_bancodet_fk` int(32) NOT NULL,
  `tipo_transaccion` int(1) NOT NULL COMMENT '1= transferencia 2=cheque de gerencia',
  `ref` int(32) NOT NULL DEFAULT '0' COMMENT 'Numero de Orden de CxP',
  `proveedor_fk` int(32) DEFAULT NULL,
  `estatus` varchar(3) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `concepto` varchar(200) DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `cxp_edocta_det_fk` varchar(200) NOT NULL,
  `fecha_anulacion` date NOT NULL DEFAULT '0000-00-00',
  `observacion_anulado` varchar(200) NOT NULL,
  `fecha_danado` date NOT NULL DEFAULT '0000-00-00',
  `observacion_danado` varchar(200) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(70) NOT NULL,
  `cod_correlativo_iva` bigint(32) NOT NULL,
  `cod_correlativo_islr` bigint(32) NOT NULL,
  PRIMARY KEY (`transferencia_pk`),
  KEY `id_proveedor` (`proveedor_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transformaciones`
--

DROP TABLE IF EXISTS `transformaciones`;
CREATE TABLE IF NOT EXISTS `transformaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `usuario_id` varchar(45) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `codigo_siga` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transformacion_kardex`
--

DROP TABLE IF EXISTS `transformacion_kardex`;
CREATE TABLE IF NOT EXISTS `transformacion_kardex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kardex_id` int(11) NOT NULL,
  `transformacion_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transf_bauche_det`
--

DROP TABLE IF EXISTS `transf_bauche_det`;
CREATE TABLE IF NOT EXISTS `transf_bauche_det` (
  `transf_bauchedet_pk` int(32) NOT NULL AUTO_INCREMENT,
  `transferencia_fk` int(32) NOT NULL,
  `transferencia_numero` int(32) NOT NULL,
  `monto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tipo` char(1) DEFAULT NULL COMMENT 'tipo: d (debito), c (credito)',
  `cuenta_contable` varchar(32) DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `descripcion` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` varchar(90) NOT NULL,
  PRIMARY KEY (`transf_bauchedet_pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transporte_conductores`
--

DROP TABLE IF EXISTS `transporte_conductores`;
CREATE TABLE IF NOT EXISTS `transporte_conductores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

DROP TABLE IF EXISTS `ubicacion`;
CREATE TABLE IF NOT EXISTS `ubicacion` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL DEFAULT '',
  `id_almacen` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'el almacen al que pertenece',
  `puede_vender` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 si vende 0 si no',
  `devolucion` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ocupado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='ubicaciones de los almacenes';

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`id`, `descripcion`, `id_almacen`, `puede_vender`, `devolucion`, `ocupado`) VALUES
(1, 'SECO', 1, 0, 0, 1),
(2, 'PISO DE VENTA', 1, 1, 0, 0),
(3, 'DEVOLUCION', 1, 0, 1, 0),
(4, 'FRIO', 1, 0, 0, 0),
(5, 'PNC', 1, 0, 0, 0),
(6, 'SECO', 2, 0, 0, 0),
(7, 'AJUSTE POR ANALIZAR', 0, 0, 0, 0),
(11, 'NO ALIMENTO', 1, 0, 0, 0),
(12, 'MCBE', 1, 0, 0, 0),
(37, 'SECO 2', 1, 0, 0, 1),
(38, 'UBICACION 1', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones_siga`
--

DROP TABLE IF EXISTS `ubicaciones_siga`;
CREATE TABLE IF NOT EXISTS `ubicaciones_siga` (
  `id_ubicacion_siga` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_ubicacion_siga` varchar(20) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id_ubicacion_siga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ubicaciones SIGA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion_servicio`
--

DROP TABLE IF EXISTS `ubicacion_servicio`;
CREATE TABLE IF NOT EXISTS `ubicacion_servicio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ubicacion` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_ubicacion` (`id_ubicacion`,`id_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ubicacion_servicio`
--

INSERT INTO `ubicacion_servicio` (`id`, `id_ubicacion`, `id_servicio`, `estatus`, `fecha_creacion`, `usuario_creacion`) VALUES
(17, 1, 9404, 1, '2017-12-17 02:43:11', 4),
(18, 37, 9404, 1, '2017-12-17 02:43:39', 4),
(19, 38, 9404, 1, '2017-12-17 05:30:34', 4),
(20, 2, 9404, 1, '2017-12-17 15:45:46', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

DROP TABLE IF EXISTS `unidades`;
CREATE TABLE IF NOT EXISTS `unidades` (
  `cod_unidad` int(32) NOT NULL,
  `descripcion` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`cod_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_empaque`
--

DROP TABLE IF EXISTS `unidad_empaque`;
CREATE TABLE IF NOT EXISTS `unidad_empaque` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_unidad` varchar(100) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unidad_empaque`
--

INSERT INTO `unidad_empaque` (`id`, `nombre_unidad`) VALUES
(1, 'CAJA'),
(2, 'BULTO'),
(3, 'UNIDAD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medida`
--

DROP TABLE IF EXISTS `unidad_medida`;
CREATE TABLE IF NOT EXISTS `unidad_medida` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_unidad` varchar(10) NOT NULL,
  `transformar` int(4) NOT NULL COMMENT 'transforma kilos a gramos',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unidad_medida`
--

INSERT INTO `unidad_medida` (`id`, `nombre_unidad`, `transformar`) VALUES
(1, 'KG', 1000),
(2, 'GR', 1),
(3, 'LTS', 1000),
(4, 'ML', 1),
(5, 'sin unidad', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_venta`
--

DROP TABLE IF EXISTS `unidad_venta`;
CREATE TABLE IF NOT EXISTS `unidad_venta` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `unidad_venta` varchar(20) NOT NULL COMMENT 'Unidad de Venta del Producto',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unidad_venta`
--

INSERT INTO `unidad_venta` (`id`, `unidad_venta`) VALUES
(1, 'Unidad'),
(2, 'Peso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unir_grupo`
--

DROP TABLE IF EXISTS `unir_grupo`;
CREATE TABLE IF NOT EXISTS `unir_grupo` (
  `cod_grupo` int(11) NOT NULL,
  `id_producto` varchar(255) NOT NULL,
  `id_categories` varchar(255) NOT NULL,
  `iva` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `cod_usuario` int(32) NOT NULL AUTO_INCREMENT,
  `nombreyapellido` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(60) NOT NULL,
  `departamento` varchar(25) NOT NULL,
  `ultima_sesion` varchar(30) NOT NULL DEFAULT '',
  `vendedor` tinyint(1) DEFAULT NULL,
  `visible_vendedor` tinyint(1) NOT NULL DEFAULT '1',
  `estatus` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Estatus del Usuario: 1=Activo, 0=Inactivo',
  `anular_pedido` tinyint(1) NOT NULL DEFAULT '0',
  `ajuste_inventario` int(11) NOT NULL COMMENT '1=El usuario puede ajustar inventario',
  PRIMARY KEY (`cod_usuario`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`cod_usuario`, `nombreyapellido`, `usuario`, `clave`, `departamento`, `ultima_sesion`, `vendedor`, `visible_vendedor`, `estatus`, `anular_pedido`, `ajuste_inventario`) VALUES
(1, 'Administrador', 'asys', '3d912cc13c8044e37419783068288e00', '', '2018-01-06 21:12:53', 0, 1, 1, 1, 1),
(2, 'Junior Ayala', 'jayala', '90e856a97cf784ba03c1722ecbf0f918', '', '2018-01-06 20:43:18', 0, 1, 1, 0, 0),
(3, 'Humberto Zapata', 'hzapata', '010f295086d29524deacf73811ada6be', '', '', 0, 1, 0, 0, 0),
(4, 'Walter Jimenez', 'wjimenez', 'e10adc3949ba59abbe56e057f20f883e', '', '2018-01-06 01:05:20', 1, 1, 1, 0, 1),
(5, 'Oriana Romero', 'oromero', 'a685b81d642eb191c6724b3ae6d28f6f', '', '2017-08-21 14:05:05', 1, 1, 1, 1, 1),
(6, 'Ayuramy Martinez', 'aymartinez', '60fd08c6dc8fc2e5a174688282cb0329', '', '2017-09-11 10:13:54', 0, 1, 1, 0, 0),
(7, 'Alexandra Machado', 'Amachado', 'ae68e11b3cd02148a9a837c4732c59d9', 'Asigne un Departamento', '2017-08-24 14:01:49', 0, 1, 1, 0, 0),
(8, 'Joel Mendoza', 'jmendoza', '63ddf5e496a35d5a25222a95f18600b5', 'Asigne un Departamento', '', 1, 1, 1, 0, 0),
(9, 'YEXSIBEL MENDOZA', 'Ymendoza', '0812283c0f556c63f61f08e22b40f609', 'Asigne un Departamento', '2017-09-11 10:13:05', 1, 1, 1, 0, 0),
(10, 'LUBIESKA MAYORA', 'lmayora', '846dc827478e262351069c54d4627faa', '', '', 0, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedor`
--

DROP TABLE IF EXISTS `vendedor`;
CREATE TABLE IF NOT EXISTS `vendedor` (
  `cod_vendedor` int(32) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `direccion1` varchar(50) NOT NULL,
  `direccion2` varchar(50) NOT NULL,
  `telefonos` varchar(50) NOT NULL,
  `fax` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `clase` varchar(50) NOT NULL,
  `venta_x_debajo_minimo` decimal(10,2) NOT NULL,
  `venta_a_precio1` decimal(10,2) NOT NULL,
  `venta_a_precio2` decimal(10,2) NOT NULL,
  `venta_a_precio3` decimal(10,2) NOT NULL,
  `venta_x_servicio` decimal(10,2) NOT NULL,
  `venta_gerericos` decimal(10,2) NOT NULL,
  `comision_x_debajo_minimo` decimal(10,2) NOT NULL,
  `comision_a_precio1` decimal(10,2) NOT NULL,
  `comision_a_precio2` decimal(10,2) NOT NULL,
  `comision_a_precio3` decimal(10,2) NOT NULL,
  `comision_x_servicio` decimal(10,2) NOT NULL,
  `comision_gerericos` decimal(10,2) NOT NULL,
  `comision_tabla_de_cobros` tinyint(1) NOT NULL,
  `tipo_comision` varchar(50) NOT NULL COMMENT 'Monto, Porcentaje',
  `rancoshasta1` decimal(10,2) NOT NULL,
  `rancosdesde1` decimal(10,2) NOT NULL,
  `rancosdesde2` decimal(10,2) NOT NULL,
  `rancoshasta2` decimal(10,2) NOT NULL,
  `rancosdesde3` decimal(10,2) NOT NULL,
  `rancoshasta3` decimal(10,2) NOT NULL,
  `rancosdesde4` decimal(10,2) NOT NULL,
  `rancoshasta4` decimal(10,2) NOT NULL,
  `rancosdesde5` decimal(10,2) NOT NULL,
  `rancoshasta5` decimal(10,2) NOT NULL,
  `factor1` decimal(10,2) NOT NULL,
  `factor2` decimal(10,2) NOT NULL,
  `factor3` decimal(10,2) NOT NULL,
  `factor4` decimal(10,2) NOT NULL,
  `factor5` decimal(10,2) NOT NULL,
  `comision_tabla_de_cobrosven` tinyint(1) NOT NULL,
  `tipo_comisionven` varchar(50) NOT NULL COMMENT 'Monto, Porcentaje',
  `ranvenhasta1` decimal(10,2) NOT NULL,
  `ranvendesde1` decimal(10,2) NOT NULL,
  `ranvendesde2` decimal(10,2) NOT NULL,
  `ranvenhasta2` decimal(10,2) NOT NULL,
  `ranvendesde3` decimal(10,2) NOT NULL,
  `ranvenhasta3` decimal(10,2) NOT NULL,
  `ranvendesde4` decimal(10,2) NOT NULL,
  `ranvenhasta4` decimal(10,2) NOT NULL,
  `ranvendesde5` decimal(10,2) NOT NULL,
  `ranvenhasta5` decimal(10,2) NOT NULL,
  `factor1ven` decimal(10,2) NOT NULL,
  `factor2ven` decimal(10,2) NOT NULL,
  `factor3ven` decimal(10,2) NOT NULL,
  `factor4ven` decimal(10,2) NOT NULL,
  `factor5ven` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cod_vendedor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `vendedor`
--

INSERT INTO `vendedor` (`cod_vendedor`, `nombre`, `direccion1`, `direccion2`, `telefonos`, `fax`, `email`, `clase`, `venta_x_debajo_minimo`, `venta_a_precio1`, `venta_a_precio2`, `venta_a_precio3`, `venta_x_servicio`, `venta_gerericos`, `comision_x_debajo_minimo`, `comision_a_precio1`, `comision_a_precio2`, `comision_a_precio3`, `comision_x_servicio`, `comision_gerericos`, `comision_tabla_de_cobros`, `tipo_comision`, `rancoshasta1`, `rancosdesde1`, `rancosdesde2`, `rancoshasta2`, `rancosdesde3`, `rancoshasta3`, `rancosdesde4`, `rancoshasta4`, `rancosdesde5`, `rancoshasta5`, `factor1`, `factor2`, `factor3`, `factor4`, `factor5`, `comision_tabla_de_cobrosven`, `tipo_comisionven`, `ranvenhasta1`, `ranvendesde1`, `ranvendesde2`, `ranvenhasta2`, `ranvendesde3`, `ranvenhasta3`, `ranvendesde4`, `ranvenhasta4`, `ranvendesde5`, `ranvenhasta5`, `factor1ven`, `factor2ven`, `factor3ven`, `factor4ven`, `factor5ven`) VALUES
(1, 'daniel', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'Monto', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'Monto', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(2, 'pedro', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'Monto', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'Monto', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(3, 'marta', 'xxccx', 'cxxc', '0101215', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'Monto', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'Monto', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `version_pyme`
--

DROP TABLE IF EXISTS `version_pyme`;
CREATE TABLE IF NOT EXISTS `version_pyme` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Primaria',
  `numero_version` varchar(10) NOT NULL COMMENT 'Numero de Version',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `version_pyme`
--

INSERT INTO `version_pyme` (`id`, `numero_version`) VALUES
(1, '1.0'),
(2, '2.0'),
(3, '2.2'),
(4, '2.3'),
(5, '2.4'),
(6, '2.8'),
(7, '3.0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visita_observaciones`
--

DROP TABLE IF EXISTS `visita_observaciones`;
CREATE TABLE IF NOT EXISTS `visita_observaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_visita` int(11) NOT NULL,
  `observacion` text NOT NULL,
  `recomendacion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_chequera_lista`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_chequera_lista`;
CREATE TABLE IF NOT EXISTS `vista_chequera_lista` (
`cod_chequera` int(32)
,`cod_tesor_bandodet` int(32)
,`cod_banco` int(32)
,`nro_cuenta` varchar(50)
,`descripcion_banco` varchar(80)
,`descripcion_cuenta` varchar(100)
,`cantidad` int(10)
,`inicio` int(40)
,`fin` bigint(42)
,`situacion` char(1)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_chequebycuentabeneficiario`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_chequebycuentabeneficiario`;
CREATE TABLE IF NOT EXISTS `vw_chequebycuentabeneficiario` (
`cod_cheque` int(32)
,`nro_cheque` varchar(50)
,`cheque` int(32)
,`monto` decimal(10,2)
,`cod_chequera` int(32)
,`ref` varchar(500)
,`id_proveedor` int(32)
,`situacion` varchar(3)
,`fecha` date
,`fecha_anulacion` date
,`observacion_anulado` varchar(200)
,`observacion_danado` varchar(200)
,`fecha_danado` date
,`fecha_creacion` timestamp
,`usuario_creacion` varchar(70)
,`descripcion_proveedor` varchar(200)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_consulimprecheque`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_consulimprecheque`;
CREATE TABLE IF NOT EXISTS `vw_consulimprecheque` (
`cod_cheque` int(32)
,`situacion` varchar(3)
,`nro_cuenta` varchar(50)
,`cheque` int(32)
,`ref` varchar(500)
,`beneficiario` varchar(200)
,`fecha` varchar(10)
,`monto` decimal(10,2)
,`cod_chequera` int(32)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_cxc`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_cxc`;
CREATE TABLE IF NOT EXISTS `vw_cxc` (
`cod_edocuenta` int(32)
,`cod_edocuenta_detalle` int(32)
,`documento_cdet` varchar(32)
,`documento_cc` varchar(32)
,`id_cliente` int(32)
,`fecha_emision` date
,`numero_cc` varchar(20)
,`vencimiento_fecha` date
,`numero` varchar(20)
,`descripcion` varchar(100)
,`debito` decimal(10,2)
,`credito` decimal(10,2)
,`marca` char(1)
,`fecha_emision_edodet` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_cxp`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_cxp`;
CREATE TABLE IF NOT EXISTS `vw_cxp` (
`cod_edocuenta` int(32)
,`cod_edocuenta_detalle` int(32)
,`documento_cdet` varchar(32)
,`documento_cc` varchar(32)
,`id_proveedor` int(32)
,`fecha_emision` date
,`numero_cc` varchar(20)
,`vencimiento_fecha` date
,`numero` varchar(20)
,`descripcion` varchar(100)
,`debito` decimal(10,2)
,`credito` decimal(10,2)
,`marca` char(1)
,`estado` tinyint(1)
,`fecha_emision_edodet` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_existenciabyalmacen`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_existenciabyalmacen`;
CREATE TABLE IF NOT EXISTS `vw_existenciabyalmacen` (
`cod_almacen` int(32)
,`id_ubicacion` int(10) unsigned
,`lote` int(11)
,`id_proveedor` int(11)
,`id_item` mediumint(6) unsigned
,`cod_item` varchar(20)
,`descripcion1` varchar(225)
,`cantidad` decimal(11,2)
,`peso` decimal(10,2)
,`descripcion` varchar(50)
,`ubicacion` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_facturasxmedicos`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_facturasxmedicos`;
CREATE TABLE IF NOT EXISTS `vw_facturasxmedicos` (
`cxp_factura_medico_pk` int(11)
,`medico_fk` int(9)
,`factura_fk` varchar(15)
,`fecha_fac` date
,`monto` decimal(13,2)
,`estatus` int(1)
,`cxp_edocta_fk` int(11)
,`paciente` varchar(100)
,`serie` varchar(100)
,`servicio` varchar(100)
,`id_cxp_mediq` int(11)
,`cod_edocuenta` int(32)
,`id_proveedor` int(32)
,`documento` varchar(32)
,`numero` varchar(20)
,`fecha_emision` date
,`observacion` varchar(600)
,`marca` char(1)
,`usuario_creacion` varchar(90)
,`fecha_creacion` timestamp
,`descripcion` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_info_item`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_info_item`;
CREATE TABLE IF NOT EXISTS `vw_info_item` (
`id_item` mediumint(6) unsigned
,`cod_item` varchar(20)
,`descripcion1` varchar(50)
,`descripcion2` varchar(50)
,`descripcion3` varchar(50)
,`referencia` varchar(50)
,`codigo_fabricante` varchar(50)
,`precio1` decimal(10,2)
,`precio2` decimal(10,2)
,`precio3` decimal(10,2)
,`utilidad1` decimal(10,2)
,`utilidad2` decimal(10,2)
,`utilidad3` decimal(10,2)
,`coniva1` decimal(10,2)
,`coniva2` decimal(10,2)
,`coniva3` decimal(10,2)
,`monto_exento` tinyint(1)
,`iva` decimal(10,2)
,`existencia_min` int(32)
,`existencia_max` int(32)
,`estatus` varchar(1)
,`nom_almacen` varchar(50)
,`cantidad_almacen` decimal(11,2)
,`departamento` varchar(50)
,`grupo` varchar(50)
,`linea` varchar(50)
,`descripcion_item_forma` varchar(50)
,`existencia_total` varbinary(35)
,`foto` varchar(60)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_item_precomprometidos`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_item_precomprometidos`;
CREATE TABLE IF NOT EXISTS `vw_item_precomprometidos` (
`cod_almacen` int(32)
,`id_item` int(32) unsigned
,`cod_item` varchar(20)
,`descripcion1` varchar(50)
,`cantidad` decimal(56,2)
,`descripcion` varchar(50)
,`ubicacion` varchar(45)
,`id_ubicacion` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_lista_conciliacion`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_lista_conciliacion`;
CREATE TABLE IF NOT EXISTS `vw_lista_conciliacion` (
`cod_conciliacion` int(32)
,`fecha_inicial` date
,`fecha_final` date
,`saldo_inicial` decimal(10,2)
,`saldo_final` decimal(10,2)
,`saldo_libros` decimal(10,2)
,`descripcion` varchar(100)
,`tipo_cuenta` varchar(100)
,`nro_cuenta` varchar(50)
,`banco` varchar(80)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_proveedores`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_proveedores`;
CREATE TABLE IF NOT EXISTS `vw_proveedores` (
`id_proveedor` int(32)
,`foto` varchar(60)
,`cod_proveedor` int(5) unsigned zerofill
,`descripcion` varchar(200)
,`direccion` varchar(300)
,`telefonos` varchar(100)
,`fax` varchar(20)
,`email` varchar(200)
,`rif` varchar(20)
,`nit` varchar(20)
,`estatus` varchar(10)
,`cod_tipo_proveedor` varchar(25)
,`clase_proveedor` varchar(25)
,`cod_entidad` int(11)
,`cod_especialidad` int(4)
,`fecha_creacion` date
,`usuario_creacion` varchar(50)
,`cuenta_contable` varchar(25)
,`compania` varchar(200)
,`id_pclasif` int(11)
,`clasificacion` varchar(60)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_relacion_cotizacion_cliente`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_relacion_cotizacion_cliente`;
CREATE TABLE IF NOT EXISTS `vw_relacion_cotizacion_cliente` (
`id_cliente` int(32)
,`cod_cliente` varchar(80)
,`nombre` varchar(80)
,`rif` varchar(50)
,`id_cotizacion` int(32) unsigned
,`cod_cotizacion` varchar(32)
,`fecha_cotizacion` date
,`cantidad_items` int(32)
,`totalizar_total_general` decimal(10,2)
,`usuario_creacion` varchar(40)
,`cod_estatus` int(32) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_relacion_factura_cliente`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_relacion_factura_cliente`;
CREATE TABLE IF NOT EXISTS `vw_relacion_factura_cliente` (
`id_cliente` int(32)
,`cod_cliente` varchar(80)
,`nombre` varchar(80)
,`rif` varchar(50)
,`id_factura` int(32) unsigned
,`cod_factura` varchar(32)
,`fechaFactura` date
,`cod_estatus` int(32) unsigned
,`cantidad_items` int(32)
,`totalizar_total_general` decimal(10,2)
,`usuario_creacion` varchar(40)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_relacion_factura_cliente_boletos`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_relacion_factura_cliente_boletos`;
CREATE TABLE IF NOT EXISTS `vw_relacion_factura_cliente_boletos` (
`id_cliente` int(32)
,`cod_cliente` varchar(80)
,`nombre` varchar(80)
,`id_factura` int(32) unsigned
,`cod_factura` varchar(32)
,`fechaFactura` date
,`cantidad_items` int(32)
,`totalizar_total_general` decimal(10,2)
,`usuario_creacion` varchar(40)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_relacion_notas_entrega_cliente`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_relacion_notas_entrega_cliente`;
CREATE TABLE IF NOT EXISTS `vw_relacion_notas_entrega_cliente` (
`id_cliente` int(32)
,`cod_cliente` varchar(80)
,`nombre` varchar(80)
,`rif` varchar(50)
,`id_nota_entrega` int(32) unsigned
,`cod_nota_entrega` varchar(32)
,`cod_estatus` int(32) unsigned
,`fechaNotaEntrega` date
,`cantidad_items` int(32)
,`totalizar_total_general` decimal(10,2)
,`usuario_creacion` varchar(40)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_relacion_pedidos_cliente`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_relacion_pedidos_cliente`;
CREATE TABLE IF NOT EXISTS `vw_relacion_pedidos_cliente` (
`id_cliente` int(32)
,`cod_cliente` varchar(80)
,`nombre` varchar(80)
,`rif` varchar(50)
,`id_pedido` int(32) unsigned
,`cod_pedido` varchar(32)
,`fechaPedido` date
,`cod_estatus` int(32) unsigned
,`cantidad_items` int(32)
,`totalizar_total_general` decimal(10,2)
,`usuario_creacion` varchar(40)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_tesor_bancodet`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vw_tesor_bancodet`;
CREATE TABLE IF NOT EXISTS `vw_tesor_bancodet` (
`cod_tesor_bandodet` int(32)
,`cuenta_contable` varchar(32)
,`cod_banco` int(32)
,`descripcion` varchar(100)
,`nro_cuenta` varchar(50)
,`tipo_cuenta` varchar(100)
,`cod_tipo_cuenta_banco` int(32)
,`monto_apertura` decimal(10,2)
,`monto_disponible` decimal(10,2)
,`fecha_apertura` date
,`usuario_creacion` varchar(50)
,`fecha_creacion` datetime
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas`
--

DROP TABLE IF EXISTS `zonas`;
CREATE TABLE IF NOT EXISTS `zonas` (
  `cod_zona` int(32) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(32) NOT NULL,
  PRIMARY KEY (`cod_zona`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `zonas`
--

INSERT INTO `zonas` (`cod_zona`, `descripcion`) VALUES
(1, 'Zona 1'),
(2, 'Zona 2'),
(3, 'Zona 3');

-- --------------------------------------------------------

--
-- Estructura para la vista `fechas_minimas`
--
DROP TABLE IF EXISTS `fechas_minimas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `fechas_minimas`  AS  (select max(`a`.`fecha`) AS `fecha` from `libro_ventas` `a` group by `a`.`serial_impresora`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_chequera_lista`
--
DROP TABLE IF EXISTS `vista_chequera_lista`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_chequera_lista`  AS  select `che`.`cod_chequera` AS `cod_chequera`,`tb`.`cod_tesor_bandodet` AS `cod_tesor_bandodet`,`tb`.`cod_banco` AS `cod_banco`,`tb`.`nro_cuenta` AS `nro_cuenta`,`b`.`descripcion` AS `descripcion_banco`,`tb`.`descripcion` AS `descripcion_cuenta`,`che`.`cantidad` AS `cantidad`,`che`.`inicio` AS `inicio`,((`che`.`inicio` + `che`.`cantidad`) - 1) AS `fin`,`che`.`situacion` AS `situacion` from ((`tesor_bancodet` `tb` join `banco` `b` on((`b`.`cod_banco` = `tb`.`cod_banco`))) join `chequera` `che` on((`che`.`cod_tesor_bandodet` = `tb`.`cod_tesor_bandodet`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_chequebycuentabeneficiario`
--
DROP TABLE IF EXISTS `vw_chequebycuentabeneficiario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_chequebycuentabeneficiario`  AS  select `che`.`cod_cheque` AS `cod_cheque`,`che`.`nro_cheque` AS `nro_cheque`,`che`.`cheque` AS `cheque`,`che`.`monto` AS `monto`,`che`.`cod_chequera` AS `cod_chequera`,`che`.`ref` AS `ref`,`che`.`id_proveedor` AS `id_proveedor`,`che`.`situacion` AS `situacion`,`che`.`fecha` AS `fecha`,`che`.`fecha_anulacion` AS `fecha_anulacion`,`che`.`observacion_anulado` AS `observacion_anulado`,`che`.`observacion_danado` AS `observacion_danado`,`che`.`fecha_danado` AS `fecha_danado`,`che`.`fecha_creacion` AS `fecha_creacion`,`che`.`usuario_creacion` AS `usuario_creacion`,ifnull(`pro`.`descripcion`,'') AS `descripcion_proveedor` from (`cheque` `che` left join `proveedores` `pro` on((`pro`.`id_proveedor` = `che`.`id_proveedor`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_consulimprecheque`
--
DROP TABLE IF EXISTS `vw_consulimprecheque`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_consulimprecheque`  AS  select `che`.`cod_cheque` AS `cod_cheque`,`che`.`situacion` AS `situacion`,`tesor_bancodet`.`nro_cuenta` AS `nro_cuenta`,`che`.`cheque` AS `cheque`,`che`.`ref` AS `ref`,`proveedores`.`descripcion` AS `beneficiario`,date_format(`che`.`fecha`,_utf8'%d-%m-%Y') AS `fecha`,`che`.`monto` AS `monto`,`che`.`cod_chequera` AS `cod_chequera` from (((`cheque` `che` join `chequera` on((`chequera`.`cod_chequera` = `che`.`cod_chequera`))) join `tesor_bancodet` on((`tesor_bancodet`.`cod_tesor_bandodet` = `chequera`.`cod_tesor_bandodet`))) join `proveedores` on((`proveedores`.`id_proveedor` = `che`.`id_proveedor`))) where (`che`.`situacion` = 'Ac') ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_cxc`
--
DROP TABLE IF EXISTS `vw_cxc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_cxc`  AS  select `cdet`.`cod_edocuenta` AS `cod_edocuenta`,`cdet`.`cod_edocuenta_detalle` AS `cod_edocuenta_detalle`,`cdet`.`documento` AS `documento_cdet`,`cc`.`documento` AS `documento_cc`,`cc`.`id_cliente` AS `id_cliente`,`cc`.`fecha_emision` AS `fecha_emision`,`cc`.`numero` AS `numero_cc`,(case `cc`.`vencimiento_fecha` when _utf8'0000-00-00' then `cc`.`fecha_emision` else `cc`.`vencimiento_fecha` end) AS `vencimiento_fecha`,`cdet`.`numero` AS `numero`,`cdet`.`descripcion` AS `descripcion`,(case `cdet`.`tipo` when 'd' then `cdet`.`monto` else 0.00 end) AS `debito`,(case `cdet`.`tipo` when 'c' then `cdet`.`monto` else 0.00 end) AS `credito`,`cc`.`marca` AS `marca`,`cdet`.`fecha_emision_edodet` AS `fecha_emision_edodet` from ((`cxc_edocuenta` `cc` join `cxc_edocuenta_detalle` `cdet` on((`cc`.`cod_edocuenta` = `cdet`.`cod_edocuenta`))) left join `cxc_edocuenta_formapago` `cdforma` on((`cdforma`.`cod_edocuenta_detalle` = `cdet`.`cod_edocuenta_detalle`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_cxp`
--
DROP TABLE IF EXISTS `vw_cxp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_cxp`  AS  select `cdet`.`cod_edocuenta` AS `cod_edocuenta`,`cdet`.`cod_edocuenta_detalle` AS `cod_edocuenta_detalle`,`cdet`.`documento` AS `documento_cdet`,`cc`.`documento` AS `documento_cc`,`cc`.`id_proveedor` AS `id_proveedor`,`cc`.`fecha_emision` AS `fecha_emision`,`cc`.`numero` AS `numero_cc`,(case `cc`.`vencimiento_fecha` when _utf8'0000-00-00' then `cc`.`fecha_emision` else `cc`.`vencimiento_fecha` end) AS `vencimiento_fecha`,`cdet`.`numero` AS `numero`,`cdet`.`descripcion` AS `descripcion`,(case `cdet`.`tipo` when 'd' then `cdet`.`monto` else 0.00 end) AS `debito`,(case `cdet`.`tipo` when 'c' then `cdet`.`monto` else 0.00 end) AS `credito`,`cc`.`marca` AS `marca`,`cdet`.`estado` AS `estado`,`cdet`.`fecha_emision_edodet` AS `fecha_emision_edodet` from ((`cxp_edocuenta` `cc` join `cxp_edocuenta_detalle` `cdet` on((`cc`.`cod_edocuenta` = `cdet`.`cod_edocuenta`))) left join `cxp_edocuenta_formapago` `cdforma` on((`cdforma`.`cod_edocuenta_detalle` = `cdet`.`cod_edocuenta_detalle`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_existenciabyalmacen`
--
DROP TABLE IF EXISTS `vw_existenciabyalmacen`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_existenciabyalmacen`  AS  select `e`.`cod_almacen` AS `cod_almacen`,`e`.`id_ubicacion` AS `id_ubicacion`,`e`.`lote` AS `lote`,`e`.`id_proveedor` AS `id_proveedor`,`i`.`id_item` AS `id_item`,`i`.`cod_item` AS `cod_item`,concat(`i`.`descripcion1`,' ',`m`.`marca`,' ',`i`.`pesoxunidad`,`um`.`nombre_unidad`) AS `descripcion1`,`e`.`cantidad` AS `cantidad`,`e`.`peso` AS `peso`,`a`.`descripcion` AS `descripcion`,`u`.`descripcion` AS `ubicacion` from (((((`item_existencia_almacen` `e` join `item` `i` on((`i`.`id_item` = `e`.`id_item`))) join `marca` `m` on((`m`.`id` = `i`.`id_marca`))) join `unidad_medida` `um` on((`um`.`id` = `i`.`unidadxpeso`))) join `almacen` `a` on((`a`.`cod_almacen` = `e`.`cod_almacen`))) join `ubicacion` `u` on((`u`.`id` = `e`.`id_ubicacion`))) where (`i`.`cod_item_forma` = 1) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_facturasxmedicos`
--
DROP TABLE IF EXISTS `vw_facturasxmedicos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_facturasxmedicos`  AS  select `facmedicos`.`cxp_factura_medico_pk` AS `cxp_factura_medico_pk`,`facmedicos`.`medico_fk` AS `medico_fk`,`facmedicos`.`factura_fk` AS `factura_fk`,`facmedicos`.`fecha_fac` AS `fecha_fac`,`facmedicos`.`monto` AS `monto`,`facmedicos`.`estatus` AS `estatus`,`facmedicos`.`cxp_edocta_fk` AS `cxp_edocta_fk`,`facmedicos`.`paciente` AS `paciente`,`facmedicos`.`serie` AS `serie`,`facmedicos`.`servicio` AS `servicio`,`facmedicos`.`id_cxp_mediq` AS `id_cxp_mediq`,`edo`.`cod_edocuenta` AS `cod_edocuenta`,`edo`.`id_proveedor` AS `id_proveedor`,`edo`.`documento` AS `documento`,`edo`.`numero` AS `numero`,`edo`.`fecha_emision` AS `fecha_emision`,`edo`.`observacion` AS `observacion`,`edo`.`marca` AS `marca`,`edo`.`usuario_creacion` AS `usuario_creacion`,`edo`.`fecha_creacion` AS `fecha_creacion`,`edodet`.`descripcion` AS `descripcion` from ((`cxp_factura_medico` `facmedicos` join `cxp_edocuenta` `edo` on((`facmedicos`.`cxp_edocta_fk` = `edo`.`cod_edocuenta`))) join `cxp_edocuenta_detalle` `edodet` on((`edodet`.`cod_edocuenta` = `edo`.`cod_edocuenta`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_info_item`
--
DROP TABLE IF EXISTS `vw_info_item`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_info_item`  AS  select `i`.`id_item` AS `id_item`,`i`.`cod_item` AS `cod_item`,`i`.`descripcion1` AS `descripcion1`,`i`.`descripcion2` AS `descripcion2`,`i`.`descripcion3` AS `descripcion3`,`i`.`referencia` AS `referencia`,`i`.`codigo_fabricante` AS `codigo_fabricante`,`i`.`precio1` AS `precio1`,`i`.`precio2` AS `precio2`,`i`.`precio3` AS `precio3`,`i`.`utilidad1` AS `utilidad1`,`i`.`utilidad2` AS `utilidad2`,`i`.`utilidad3` AS `utilidad3`,`i`.`coniva1` AS `coniva1`,`i`.`coniva2` AS `coniva2`,`i`.`coniva3` AS `coniva3`,`i`.`monto_exento` AS `monto_exento`,`i`.`iva` AS `iva`,`i`.`existencia_min` AS `existencia_min`,`i`.`existencia_max` AS `existencia_max`,`i`.`estatus` AS `estatus`,ifnull(`a`.`descripcion`,'Sin Ubicacion') AS `nom_almacen`,ifnull(`ia`.`cantidad`,0) AS `cantidad_almacen`,`d`.`descripcion` AS `departamento`,`g`.`descripcion` AS `grupo`,`l`.`descripcion` AS `linea`,`_if`.`descripcion` AS `descripcion_item_forma`,ifnull((select sum(`item_existencia_almacen`.`cantidad`) AS `sum(cantidad)` from `item_existencia_almacen` where (`item_existencia_almacen`.`id_item` = `i`.`id_item`)),_utf8'0') AS `existencia_total`,`i`.`foto` AS `foto` from ((((((`item` `i` left join `item_existencia_almacen` `ia` on((`ia`.`id_item` = `i`.`id_item`))) left join `almacen` `a` on((`a`.`cod_almacen` = `ia`.`cod_almacen`))) join `departamentos` `d` on((`d`.`cod_departamento` = `i`.`cod_departamento`))) join `grupo` `g` on((`g`.`cod_grupo` = `i`.`cod_grupo`))) join `linea` `l` on((`l`.`cod_linea` = `i`.`cod_linea`))) join `item_forma` `_if` on((`_if`.`cod_item_forma` = `i`.`cod_item_forma`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_item_precomprometidos`
--
DROP TABLE IF EXISTS `vw_item_precomprometidos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_item_precomprometidos`  AS  select `ia`.`cod_almacen` AS `cod_almacen`,`ia`.`id_item` AS `id_item`,`i`.`cod_item` AS `cod_item`,`i`.`descripcion1` AS `descripcion1`,(`ia`.`cantidad` - (select ifnull(sum(`item_precompromiso`.`cantidad`),0) AS `ifnull(sum(cantidad),0)` from `item_precompromiso` where ((`item_precompromiso`.`id_item` = `ia`.`id_item`) and (`item_precompromiso`.`almacen` = `ia`.`cod_almacen`)))) AS `cantidad`,`a`.`descripcion` AS `descripcion`,`u`.`descripcion` AS `ubicacion`,`ia`.`id_ubicacion` AS `id_ubicacion` from (((`item_existencia_almacen` `ia` join `item` `i` on((`i`.`id_item` = `ia`.`id_item`))) join `almacen` `a` on((`a`.`cod_almacen` = `ia`.`cod_almacen`))) join `ubicacion` `u` on((`u`.`id` = `ia`.`id_ubicacion`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_lista_conciliacion`
--
DROP TABLE IF EXISTS `vw_lista_conciliacion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_lista_conciliacion`  AS  select `conciliacion_bancaria`.`cod_conciliacion` AS `cod_conciliacion`,`conciliacion_bancaria`.`fecha_inicial` AS `fecha_inicial`,`conciliacion_bancaria`.`fecha_final` AS `fecha_final`,`conciliacion_bancaria`.`saldo_inicial` AS `saldo_inicial`,`conciliacion_bancaria`.`saldo_final` AS `saldo_final`,`conciliacion_bancaria`.`saldo_libros` AS `saldo_libros`,`vw_tesor_bancodet`.`descripcion` AS `descripcion`,`vw_tesor_bancodet`.`tipo_cuenta` AS `tipo_cuenta`,`vw_tesor_bancodet`.`nro_cuenta` AS `nro_cuenta`,`banco`.`descripcion` AS `banco` from ((`conciliacion_bancaria` join `vw_tesor_bancodet` on((`vw_tesor_bancodet`.`cod_tesor_bandodet` = `conciliacion_bancaria`.`cod_tesor_bancodet`))) join `banco` on((`vw_tesor_bancodet`.`cod_banco` = `banco`.`cod_banco`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_proveedores`
--
DROP TABLE IF EXISTS `vw_proveedores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_proveedores`  AS  select `prov`.`id_proveedor` AS `id_proveedor`,`prov`.`foto` AS `foto`,`prov`.`cod_proveedor` AS `cod_proveedor`,`prov`.`descripcion` AS `descripcion`,`prov`.`direccion` AS `direccion`,`prov`.`telefonos` AS `telefonos`,`prov`.`fax` AS `fax`,`prov`.`email` AS `email`,`prov`.`rif` AS `rif`,`prov`.`nit` AS `nit`,`prov`.`estatus` AS `estatus`,`prov`.`cod_tipo_proveedor` AS `cod_tipo_proveedor`,`prov`.`clase_proveedor` AS `clase_proveedor`,`prov`.`cod_entidad` AS `cod_entidad`,`prov`.`cod_especialidad` AS `cod_especialidad`,`prov`.`fecha_creacion` AS `fecha_creacion`,`prov`.`usuario_creacion` AS `usuario_creacion`,`prov`.`cuenta_contable` AS `cuenta_contable`,`prov`.`compania` AS `compania`,`ti`.`id_pclasif` AS `id_pclasif`,`ti`.`clasificacion` AS `clasificacion` from (`proveedores` `prov` join `tipo_proveedor_clasif` `ti` on((`prov`.`clase_proveedor` = `ti`.`id_pclasif`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_relacion_cotizacion_cliente`
--
DROP TABLE IF EXISTS `vw_relacion_cotizacion_cliente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_relacion_cotizacion_cliente`  AS  select `c`.`id_cliente` AS `id_cliente`,`c`.`cod_cliente` AS `cod_cliente`,`c`.`nombre` AS `nombre`,`c`.`rif` AS `rif`,`f`.`id_cotizacion` AS `id_cotizacion`,`f`.`cod_cotizacion` AS `cod_cotizacion`,`f`.`fecha_cotizacion` AS `fecha_cotizacion`,`f`.`cantidad_items` AS `cantidad_items`,`f`.`totalizar_total_general` AS `totalizar_total_general`,`f`.`usuario_creacion` AS `usuario_creacion`,`f`.`cod_estatus` AS `cod_estatus` from (`cotizacion_presupuesto` `f` join `clientes` `c` on((`c`.`id_cliente` = `f`.`id_cliente`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_relacion_factura_cliente`
--
DROP TABLE IF EXISTS `vw_relacion_factura_cliente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_relacion_factura_cliente`  AS  select `c`.`id_cliente` AS `id_cliente`,`c`.`cod_cliente` AS `cod_cliente`,`c`.`nombre` AS `nombre`,`c`.`rif` AS `rif`,`f`.`id_factura` AS `id_factura`,`f`.`cod_factura` AS `cod_factura`,`f`.`fechaFactura` AS `fechaFactura`,`f`.`cod_estatus` AS `cod_estatus`,`f`.`cantidad_items` AS `cantidad_items`,`f`.`totalizar_total_general` AS `totalizar_total_general`,`f`.`usuario_creacion` AS `usuario_creacion` from (`factura` `f` join `clientes` `c` on((`c`.`id_cliente` = `f`.`id_cliente`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_relacion_factura_cliente_boletos`
--
DROP TABLE IF EXISTS `vw_relacion_factura_cliente_boletos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_relacion_factura_cliente_boletos`  AS  select `c`.`id_cliente` AS `id_cliente`,`c`.`cod_cliente` AS `cod_cliente`,`c`.`nombre` AS `nombre`,`f`.`id_factura_boleto` AS `id_factura`,`f`.`cod_factura_boleto` AS `cod_factura`,`f`.`fechaFactura` AS `fechaFactura`,`f`.`cantidad_items` AS `cantidad_items`,`f`.`totalizar_total_general` AS `totalizar_total_general`,`f`.`usuario_creacion` AS `usuario_creacion` from (`boleto_factura` `f` join `clientes` `c` on((`c`.`id_cliente` = `f`.`id_cliente`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_relacion_notas_entrega_cliente`
--
DROP TABLE IF EXISTS `vw_relacion_notas_entrega_cliente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_relacion_notas_entrega_cliente`  AS  select `c`.`id_cliente` AS `id_cliente`,`c`.`cod_cliente` AS `cod_cliente`,`c`.`nombre` AS `nombre`,`c`.`rif` AS `rif`,`f`.`id_nota_entrega` AS `id_nota_entrega`,`f`.`cod_nota_entrega` AS `cod_nota_entrega`,`f`.`cod_estatus` AS `cod_estatus`,`f`.`fechaNotaEntrega` AS `fechaNotaEntrega`,`f`.`cantidad_items` AS `cantidad_items`,`f`.`totalizar_total_general` AS `totalizar_total_general`,`f`.`usuario_creacion` AS `usuario_creacion` from (`nota_entrega` `f` join `clientes` `c` on((`c`.`id_cliente` = `f`.`id_cliente`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_relacion_pedidos_cliente`
--
DROP TABLE IF EXISTS `vw_relacion_pedidos_cliente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_relacion_pedidos_cliente`  AS  select `c`.`id_cliente` AS `id_cliente`,`c`.`cod_cliente` AS `cod_cliente`,`c`.`nombre` AS `nombre`,`c`.`rif` AS `rif`,`f`.`id_pedido` AS `id_pedido`,`f`.`cod_pedido` AS `cod_pedido`,`f`.`fechaPedido` AS `fechaPedido`,`f`.`cod_estatus` AS `cod_estatus`,`f`.`cantidad_items` AS `cantidad_items`,`f`.`totalizar_total_general` AS `totalizar_total_general`,`f`.`usuario_creacion` AS `usuario_creacion` from (`pedido` `f` join `clientes` `c` on((`c`.`id_cliente` = `f`.`id_cliente`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_tesor_bancodet`
--
DROP TABLE IF EXISTS `vw_tesor_bancodet`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_tesor_bancodet`  AS  select `bdet`.`cod_tesor_bandodet` AS `cod_tesor_bandodet`,`bdet`.`cuenta_contable` AS `cuenta_contable`,`bdet`.`cod_banco` AS `cod_banco`,`bdet`.`descripcion` AS `descripcion`,`bdet`.`nro_cuenta` AS `nro_cuenta`,`tcb`.`descripcion` AS `tipo_cuenta`,`tcb`.`cod_tipo_cuenta_banco` AS `cod_tipo_cuenta_banco`,`bdet`.`monto_apertura` AS `monto_apertura`,`bdet`.`monto_disponible` AS `monto_disponible`,`bdet`.`fecha_apertura` AS `fecha_apertura`,`bdet`.`usuario_creacion` AS `usuario_creacion`,`bdet`.`fecha_creacion` AS `fecha_creacion` from (`tesor_bancodet` `bdet` join `tipo_cuenta_banco` `tcb` on((`tcb`.`cod_tipo_cuenta_banco` = `bdet`.`cod_tipo_cuenta_banco`))) ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calidad_almacen_detalle`
--
ALTER TABLE `calidad_almacen_detalle`
  ADD CONSTRAINT `frk_calidadmaestro_calidadhijo` FOREIGN KEY (`id_transaccion`) REFERENCES `calidad_almacen` (`id_transaccion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cesta_clap_detalle`
--
ALTER TABLE `cesta_clap_detalle`
  ADD CONSTRAINT `cesta_clap_detalle_ibfk_1` FOREIGN KEY (`id_cesta`) REFERENCES `cesta_clap` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
