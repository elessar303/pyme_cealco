-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-01-2018 a las 22:42:56
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
-- Base de datos: `pos_cealco`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `applications`
--

DROP TABLE IF EXISTS `applications`;
CREATE TABLE IF NOT EXISTS `applications` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `VERSION` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attribute`
--

DROP TABLE IF EXISTS `attribute`;
CREATE TABLE IF NOT EXISTS `attribute` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attributeinstance`
--

DROP TABLE IF EXISTS `attributeinstance`;
CREATE TABLE IF NOT EXISTS `attributeinstance` (
  `ID` varchar(255) NOT NULL,
  `ATTRIBUTESETINSTANCE_ID` varchar(255) NOT NULL,
  `ATTRIBUTE_ID` varchar(255) NOT NULL,
  `VALUE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ATTINST_SET` (`ATTRIBUTESETINSTANCE_ID`),
  KEY `ATTINST_ATT` (`ATTRIBUTE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attributeset`
--

DROP TABLE IF EXISTS `attributeset`;
CREATE TABLE IF NOT EXISTS `attributeset` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attributesetinstance`
--

DROP TABLE IF EXISTS `attributesetinstance`;
CREATE TABLE IF NOT EXISTS `attributesetinstance` (
  `ID` varchar(255) NOT NULL,
  `ATTRIBUTESET_ID` varchar(255) NOT NULL,
  `DESCRIPTION` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ATTSETINST_SET` (`ATTRIBUTESET_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attributeuse`
--

DROP TABLE IF EXISTS `attributeuse`;
CREATE TABLE IF NOT EXISTS `attributeuse` (
  `ID` varchar(255) NOT NULL,
  `ATTRIBUTESET_ID` varchar(255) NOT NULL,
  `ATTRIBUTE_ID` varchar(255) NOT NULL,
  `LINENO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ATTUSE_LINE` (`ATTRIBUTESET_ID`,`LINENO`),
  KEY `ATTUSE_ATT` (`ATTRIBUTE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attributevalue`
--

DROP TABLE IF EXISTS `attributevalue`;
CREATE TABLE IF NOT EXISTS `attributevalue` (
  `ID` varchar(255) NOT NULL,
  `ATTRIBUTE_ID` varchar(255) NOT NULL,
  `VALUE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ATTVAL_ATT` (`ATTRIBUTE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank`
--

DROP TABLE IF EXISTS `bank`;
CREATE TABLE IF NOT EXISTS `bank` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `PARENTID` varchar(255) DEFAULT NULL,
  `IMAGE` mediumblob,
  `QUANTITYMAX` int(11) NOT NULL,
  `TIMEFORTRY` int(11) NOT NULL,
  `PROTECTED` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ID`),
  KEY `CATEGORIES_FK_1` (`PARENTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `closedcash`
--

DROP TABLE IF EXISTS `closedcash`;
CREATE TABLE IF NOT EXISTS `closedcash` (
  `MONEY` varchar(255) NOT NULL,
  `HOST` varchar(255) NOT NULL,
  `HOSTSEQUENCE` int(11) NOT NULL,
  `DATESTART` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DATEEND` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MONEY`),
  UNIQUE KEY `CLOSEDCASH_INX_SEQ` (`HOST`,`HOSTSEQUENCE`),
  KEY `CLOSEDCASH_INX_1` (`DATESTART`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `closedcash`
--
DROP TRIGGER IF EXISTS `dateend_insert`;
DELIMITER $$
CREATE TRIGGER `dateend_insert` AFTER INSERT ON `closedcash` FOR EACH ROW BEGIN INSERT INTO secuencia_cierre_host(
nombre_host,
secuencia_host,
status_cierre_pyme,
status_cierre_pos
)
VALUES (
new.HOST, new.HOSTSEQUENCE, 1, 1
);
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `dateend_update`;
DELIMITER $$
CREATE TRIGGER `dateend_update` AFTER UPDATE ON `closedcash` FOR EACH ROW BEGIN UPDATE secuencia_cierre_host SET status_cierre_pos=0
WHERE secuencia_host=new.HOSTSEQUENCE 
AND nombre_host=new.HOST;
END
$$
DELIMITER ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `ID` varchar(255) NOT NULL,
  `SEARCHKEY` varchar(255) NOT NULL,
  `TAXID` varchar(255) DEFAULT NULL,
  `NAME` varchar(255) NOT NULL,
  `TAXCATEGORY` varchar(255) DEFAULT NULL,
  `CARD` varchar(255) DEFAULT NULL,
  `MAXDEBT` double NOT NULL DEFAULT '0',
  `ADDRESS` varchar(255) DEFAULT NULL,
  `ADDRESS2` varchar(255) DEFAULT NULL,
  `POSTAL` varchar(255) DEFAULT NULL,
  `CITY` varchar(255) DEFAULT NULL,
  `REGION` varchar(255) DEFAULT NULL,
  `COUNTRY` varchar(255) DEFAULT NULL,
  `FIRSTNAME` varchar(255) DEFAULT NULL,
  `LASTNAME` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `PHONE` varchar(255) DEFAULT NULL,
  `PHONE2` varchar(255) DEFAULT NULL,
  `FAX` varchar(255) DEFAULT NULL,
  `NOTES` varchar(255) DEFAULT NULL,
  `VISIBLE` bit(1) NOT NULL DEFAULT b'1',
  `CURDATE` datetime DEFAULT NULL,
  `CURDEBT` double DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `CUSTOMERS_SKEY_INX` (`SEARCHKEY`),
  KEY `CUSTOMERS_TAXCAT` (`TAXCATEGORY`),
  KEY `CUSTOMERS_TAXID_INX` (`TAXID`),
  KEY `CUSTOMERS_NAME_INX` (`NAME`),
  KEY `CUSTOMERS_CARD_INX` (`CARD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `days_id`
--

DROP TABLE IF EXISTS `days_id`;
CREATE TABLE IF NOT EXISTS `days_id` (
  `DAY` tinyint(4) NOT NULL DEFAULT '0',
  `MIN` tinyint(4) NOT NULL,
  `MAX` tinyint(4) NOT NULL,
  `NAME` varchar(10) NOT NULL,
  PRIMARY KEY (`DAY`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `external_sales`
--

DROP TABLE IF EXISTS `external_sales`;
CREATE TABLE IF NOT EXISTS `external_sales` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `CODE` varchar(255) CHARACTER SET utf8 NOT NULL,
  `UNITS` double NOT NULL,
  `TAXID` varchar(255) CHARACTER SET utf8 NOT NULL,
  `DATE` date NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `floors`
--

DROP TABLE IF EXISTS `floors`;
CREATE TABLE IF NOT EXISTS `floors` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `IMAGE` mediumblob,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `FLOORS_NAME_INX` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hicc_event`
--

DROP TABLE IF EXISTS `hicc_event`;
CREATE TABLE IF NOT EXISTS `hicc_event` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) CHARACTER SET utf8 NOT NULL,
  `LASTNAME` varchar(255) CHARACTER SET utf8 NOT NULL,
  `TAXID` varchar(255) CHARACTER SET utf8 NOT NULL,
  `TYPEID` char(1) CHARACTER SET utf8 NOT NULL,
  `EVENT_TYPE` int(2) NOT NULL,
  `INVOICE_NUMBER` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `HICC_ID` varchar(255) CHARACTER SET utf8 NOT NULL,
  `DATETRX` datetime DEFAULT NULL,
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `ADDRESS` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LOCATIONS_NAME_INX` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_trans`
--

DROP TABLE IF EXISTS `log_trans`;
CREATE TABLE IF NOT EXISTS `log_trans` (
  `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TYPE` int(11) NOT NULL COMMENT '0 -> Ingreso, 1-> Venta, 2-> Devolucion, 3-> Autorizacion, 4-> Eliminacion, 5-> Cierre de caja, 6-> Cierre Z',
  `DESCRIPTION` varchar(500) NOT NULL,
  `DB_TABLE` varchar(50) DEFAULT NULL,
  `USER` varchar(255) NOT NULL,
  `HOSTNAME` varchar(50) NOT NULL,
  `HOSTIP` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `magcard`
--

DROP TABLE IF EXISTS `magcard`;
CREATE TABLE IF NOT EXISTS `magcard` (
  `ID` varchar(255) NOT NULL,
  `MAGCARD_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `BANK_FROM` varchar(100) NOT NULL,
  `CARD_NUMBER` varchar(100) DEFAULT NULL,
  `BANK_TO` varchar(100) DEFAULT NULL,
  `TYPE` varchar(100) NOT NULL,
  `AMOUNT` double(11,2) NOT NULL,
  PRIMARY KEY (`ID`,`MAGCARD_ID`),
  UNIQUE KEY `MAGCARD_ID` (`MAGCARD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `ID` varchar(255) NOT NULL,
  `RECEIPT` varchar(255) NOT NULL,
  `PAYMENT` varchar(255) NOT NULL,
  `TOTAL` double NOT NULL,
  `TRANSID` varchar(255) DEFAULT NULL,
  `RETURNMSG` mediumblob,
  PRIMARY KEY (`ID`),
  KEY `PAYMENTS_FK_RECEIPT` (`RECEIPT`),
  KEY `PAYMENTS_INX_1` (`PAYMENT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `people`
--

DROP TABLE IF EXISTS `people`;
CREATE TABLE IF NOT EXISTS `people` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `APPPASSWORD` varchar(255) DEFAULT NULL,
  `CARD` varchar(255) DEFAULT NULL,
  `ROLE` varchar(255) NOT NULL,
  `VISIBLE` bit(1) NOT NULL,
  `IMAGE` mediumblob,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PEOPLE_NAME_INX` (`NAME`),
  KEY `PEOPLE_FK_1` (`ROLE`),
  KEY `PEOPLE_CARD_INX` (`CARD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `people_caja`
--

DROP TABLE IF EXISTS `people_caja`;
CREATE TABLE IF NOT EXISTS `people_caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_people` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `places`
--

DROP TABLE IF EXISTS `places`;
CREATE TABLE IF NOT EXISTS `places` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `X` int(11) NOT NULL,
  `Y` int(11) NOT NULL,
  `FLOOR` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PLACES_NAME_INX` (`NAME`),
  KEY `PLACES_FK_1` (`FLOOR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `ID` varchar(255) NOT NULL,
  `REFERENCE` varchar(255) NOT NULL,
  `CODE` varchar(255) NOT NULL,
  `CODETYPE` varchar(255) DEFAULT NULL,
  `NAME` varchar(255) NOT NULL,
  `PRICEBUY` double NOT NULL,
  `PRICESELL` double NOT NULL,
  `CATEGORY` varchar(255) NOT NULL,
  `TAXCAT` varchar(255) NOT NULL,
  `ATTRIBUTESET_ID` varchar(255) DEFAULT NULL,
  `STOCKCOST` double DEFAULT NULL,
  `STOCKVOLUME` double DEFAULT NULL,
  `IMAGE` mediumblob,
  `ISCOM` bit(1) NOT NULL DEFAULT b'0',
  `ISSCALE` bit(1) NOT NULL DEFAULT b'0',
  `ATTRIBUTES` mediumblob,
  `QUANTITY_MAX` int(5) NOT NULL,
  `TIME_FOR_TRY` int(5) NOT NULL,
  `PRODUCTION_TYPE` int(1) DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PRODUCTS_INX_1` (`CODE`),
  KEY `PRODUCTS_FK_1` (`CATEGORY`),
  KEY `PRODUCTS_TAXCAT_FK` (`TAXCAT`),
  KEY `PRODUCTS_ATTRSET_FK` (`ATTRIBUTESET_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products_cat`
--

DROP TABLE IF EXISTS `products_cat`;
CREATE TABLE IF NOT EXISTS `products_cat` (
  `PRODUCT` varchar(255) NOT NULL,
  `CATORDER` int(11) DEFAULT NULL,
  PRIMARY KEY (`PRODUCT`),
  KEY `PRODUCTS_CAT_INX_1` (`CATORDER`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products_com`
--

DROP TABLE IF EXISTS `products_com`;
CREATE TABLE IF NOT EXISTS `products_com` (
  `ID` varchar(255) NOT NULL,
  `PRODUCT` varchar(255) NOT NULL,
  `PRODUCT2` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PCOM_INX_PROD` (`PRODUCT`,`PRODUCT2`),
  KEY `PRODUCTS_COM_FK_2` (`PRODUCT2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receipts`
--

DROP TABLE IF EXISTS `receipts`;
CREATE TABLE IF NOT EXISTS `receipts` (
  `ID` varchar(255) NOT NULL,
  `MONEY` varchar(255) NOT NULL,
  `DATENEW` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ATTRIBUTES` mediumblob,
  PRIMARY KEY (`ID`),
  KEY `RECEIPTS_FK_MONEY` (`MONEY`),
  KEY `RECEIPTS_INX_1` (`DATENEW`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `ID` varchar(255) NOT NULL,
  `CREATED` datetime NOT NULL,
  `DATENEW` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `TITLE` varchar(255) NOT NULL,
  `CHAIRS` int(11) NOT NULL,
  `ISDONE` bit(1) NOT NULL,
  `DESCRIPTION` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `RESERVATIONS_INX_1` (`DATENEW`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservation_customers`
--

DROP TABLE IF EXISTS `reservation_customers`;
CREATE TABLE IF NOT EXISTS `reservation_customers` (
  `ID` varchar(255) NOT NULL,
  `CUSTOMER` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `RES_CUST_FK_2` (`CUSTOMER`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE IF NOT EXISTS `resources` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `RESTYPE` int(11) NOT NULL,
  `CONTENT` mediumblob,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `RESOURCES_NAME_INX` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `PERMISSIONS` mediumblob,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ROLES_NAME_INX` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secuencia_cierre_host`
--

DROP TABLE IF EXISTS `secuencia_cierre_host`;
CREATE TABLE IF NOT EXISTS `secuencia_cierre_host` (
  `id_cierre_host` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Calve Primaria',
  `nombre_host` varchar(50) NOT NULL COMMENT 'Nombre de Host',
  `secuencia_host` bigint(20) NOT NULL COMMENT 'Secuencia Host',
  `status_cierre_pyme` int(11) NOT NULL COMMENT 'Indica si esta secuencia cerro en el pyme, 0 cerro, 1 sin cerrar',
  `status_cierre_pos` int(11) NOT NULL COMMENT 'Indica si esta secuencia cerro en el pos, 0 cerro, 1 sin cerrar',
  PRIMARY KEY (`id_cierre_host`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sharedtickets`
--

DROP TABLE IF EXISTS `sharedtickets`;
CREATE TABLE IF NOT EXISTS `sharedtickets` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `CONTENT` mediumblob,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stockcurrent`
--

DROP TABLE IF EXISTS `stockcurrent`;
CREATE TABLE IF NOT EXISTS `stockcurrent` (
  `LOCATION` varchar(255) NOT NULL,
  `PRODUCT` varchar(255) NOT NULL,
  `ATTRIBUTESETINSTANCE_ID` varchar(255) DEFAULT NULL,
  `UNITS` double(11,2) NOT NULL,
  UNIQUE KEY `STOCKCURRENT_INX` (`LOCATION`,`PRODUCT`,`ATTRIBUTESETINSTANCE_ID`),
  KEY `STOCKCURRENT_FK_1` (`PRODUCT`),
  KEY `STOCKCURRENT_ATTSETINST` (`ATTRIBUTESETINSTANCE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stockdiary`
--

DROP TABLE IF EXISTS `stockdiary`;
CREATE TABLE IF NOT EXISTS `stockdiary` (
  `ID` varchar(255) NOT NULL,
  `DATENEW` datetime NOT NULL,
  `REASON` int(11) NOT NULL,
  `LOCATION` varchar(255) NOT NULL,
  `PRODUCT` varchar(255) NOT NULL,
  `ATTRIBUTESETINSTANCE_ID` varchar(255) DEFAULT NULL,
  `UNITS` double NOT NULL,
  `PRICE` double NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `STOCKDIARY_FK_1` (`PRODUCT`),
  KEY `STOCKDIARY_ATTSETINST` (`ATTRIBUTESETINSTANCE_ID`),
  KEY `STOCKDIARY_FK_2` (`LOCATION`),
  KEY `STOCKDIARY_INX_1` (`DATENEW`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stocklevel`
--

DROP TABLE IF EXISTS `stocklevel`;
CREATE TABLE IF NOT EXISTS `stocklevel` (
  `ID` varchar(255) NOT NULL,
  `LOCATION` varchar(255) NOT NULL,
  `PRODUCT` varchar(255) NOT NULL,
  `STOCKSECURITY` double DEFAULT NULL,
  `STOCKMAXIMUM` double DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `STOCKLEVEL_PRODUCT` (`PRODUCT`),
  KEY `STOCKLEVEL_LOCATION` (`LOCATION`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `store`
--

DROP TABLE IF EXISTS `store`;
CREATE TABLE IF NOT EXISTS `store` (
  `ID` varchar(60) NOT NULL,
  `CODE` int(3) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `OPENING_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LOCATION` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `store`
--

INSERT INTO `store` (`ID`, `CODE`, `NAME`, `OPENING_DATE`, `LOCATION`) VALUES
('23456789', 123, 'TIENDA PRUEBA', '2018-01-07 01:12:41', 'BARQUISIMETO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxcategories`
--

DROP TABLE IF EXISTS `taxcategories`;
CREATE TABLE IF NOT EXISTS `taxcategories` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `TAXCAT_NAME_INX` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxcustcategories`
--

DROP TABLE IF EXISTS `taxcustcategories`;
CREATE TABLE IF NOT EXISTS `taxcustcategories` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `TAXCUSTCAT_NAME_INX` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxes`
--

DROP TABLE IF EXISTS `taxes`;
CREATE TABLE IF NOT EXISTS `taxes` (
  `ID` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `VALIDFROM` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `CATEGORY` varchar(255) NOT NULL,
  `CUSTCATEGORY` varchar(255) DEFAULT NULL,
  `PARENTID` varchar(255) DEFAULT NULL,
  `RATE` double NOT NULL,
  `RATECASCADE` bit(1) NOT NULL DEFAULT b'0',
  `RATEORDER` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `TAXES_NAME_INX` (`NAME`),
  KEY `TAXES_CAT_FK` (`CATEGORY`),
  KEY `TAXES_CUSTCAT_FK` (`CUSTCATEGORY`),
  KEY `TAXES_TAXES_FK` (`PARENTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxlines`
--

DROP TABLE IF EXISTS `taxlines`;
CREATE TABLE IF NOT EXISTS `taxlines` (
  `ID` varchar(255) NOT NULL,
  `RECEIPT` varchar(255) NOT NULL,
  `TAXID` varchar(255) NOT NULL,
  `BASE` double NOT NULL,
  `AMOUNT` double NOT NULL,
  `SOLD` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `TAXLINES_TAX` (`TAXID`),
  KEY `TAXLINES_RECEIPT` (`RECEIPT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `thirdparties`
--

DROP TABLE IF EXISTS `thirdparties`;
CREATE TABLE IF NOT EXISTS `thirdparties` (
  `ID` varchar(255) NOT NULL,
  `CIF` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `ADDRESS` varchar(255) DEFAULT NULL,
  `CONTACTCOMM` varchar(255) DEFAULT NULL,
  `CONTACTFACT` varchar(255) DEFAULT NULL,
  `PAYRULE` varchar(255) DEFAULT NULL,
  `FAXNUMBER` varchar(255) DEFAULT NULL,
  `PHONENUMBER` varchar(255) DEFAULT NULL,
  `MOBILENUMBER` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `WEBPAGE` varchar(255) DEFAULT NULL,
  `NOTES` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `THIRDPARTIES_CIF_INX` (`CIF`),
  UNIQUE KEY `THIRDPARTIES_NAME_INX` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticketlines`
--

DROP TABLE IF EXISTS `ticketlines`;
CREATE TABLE IF NOT EXISTS `ticketlines` (
  `TICKET` varchar(255) NOT NULL,
  `LINE` int(11) NOT NULL,
  `PRODUCT` varchar(255) DEFAULT NULL,
  `ATTRIBUTESETINSTANCE_ID` varchar(255) DEFAULT NULL,
  `UNITS` double NOT NULL,
  `PRICE` double NOT NULL,
  `TAXID` varchar(255) NOT NULL,
  `ATTRIBUTES` mediumblob,
  `DATENEW` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SOLD` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`TICKET`,`LINE`),
  KEY `TICKETLINES_FK_2` (`PRODUCT`),
  KEY `TICKETLINES_ATTSETINST` (`ATTRIBUTESETINSTANCE_ID`),
  KEY `TICKETLINES_FK_3` (`TAXID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `ID` varchar(255) NOT NULL,
  `TICKETTYPE` int(11) NOT NULL DEFAULT '0',
  `TICKETID` int(11) UNSIGNED NOT NULL,
  `FISCALNUMBER` varchar(20) DEFAULT NULL,
  `FISCALSERIAL` varchar(50) DEFAULT NULL,
  `PERSON` varchar(255) NOT NULL,
  `CUSTOMER` varchar(255) DEFAULT NULL,
  `STATUS` int(11) NOT NULL DEFAULT '0',
  `PRINTED` tinyint(1) DEFAULT NULL,
  `PAYED` tinyint(1) DEFAULT NULL,
  `ELECTRONIC_PAY` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `TICKETS_FK_2` (`PERSON`),
  KEY `TICKETS_CUSTOMERS_FK` (`CUSTOMER`),
  KEY `TICKETS_TICKETID` (`TICKETTYPE`,`TICKETID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticketsnum`
--

DROP TABLE IF EXISTS `ticketsnum`;
CREATE TABLE IF NOT EXISTS `ticketsnum` (
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticketsnum_payment`
--

DROP TABLE IF EXISTS `ticketsnum_payment`;
CREATE TABLE IF NOT EXISTS `ticketsnum_payment` (
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticketsnum_refund`
--

DROP TABLE IF EXISTS `ticketsnum_refund`;
CREATE TABLE IF NOT EXISTS `ticketsnum_refund` (
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_control`
--

DROP TABLE IF EXISTS `ticket_control`;
CREATE TABLE IF NOT EXISTS `ticket_control` (
  `ticket_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ticket_control`
--

INSERT INTO `ticket_control` (`ticket_id`) VALUES
('');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vclientes`
--

DROP TABLE IF EXISTS `vclientes`;
CREATE TABLE IF NOT EXISTS `vclientes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo_barra` varchar(255) NOT NULL,
  `cantidad_prod` decimal(5,2) NOT NULL,
  `cedula` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `attributeinstance`
--
ALTER TABLE `attributeinstance`
  ADD CONSTRAINT `ATTINST_ATT` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attribute` (`ID`),
  ADD CONSTRAINT `ATTINST_SET` FOREIGN KEY (`ATTRIBUTESETINSTANCE_ID`) REFERENCES `attributesetinstance` (`ID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `attributesetinstance`
--
ALTER TABLE `attributesetinstance`
  ADD CONSTRAINT `ATTSETINST_SET` FOREIGN KEY (`ATTRIBUTESET_ID`) REFERENCES `attributeset` (`ID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `attributeuse`
--
ALTER TABLE `attributeuse`
  ADD CONSTRAINT `ATTUSE_ATT` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attribute` (`ID`),
  ADD CONSTRAINT `ATTUSE_SET` FOREIGN KEY (`ATTRIBUTESET_ID`) REFERENCES `attributeset` (`ID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `attributevalue`
--
ALTER TABLE `attributevalue`
  ADD CONSTRAINT `ATTVAL_ATT` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attribute` (`ID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `CATEGORIES_FK_1` FOREIGN KEY (`PARENTID`) REFERENCES `categories` (`ID`);

--
-- Filtros para la tabla `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `CUSTOMERS_TAXCAT` FOREIGN KEY (`TAXCATEGORY`) REFERENCES `taxcustcategories` (`ID`);

--
-- Filtros para la tabla `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `PAYMENTS_FK_RECEIPT` FOREIGN KEY (`RECEIPT`) REFERENCES `receipts` (`ID`);

--
-- Filtros para la tabla `people`
--
ALTER TABLE `people`
  ADD CONSTRAINT `people_ibfk_1` FOREIGN KEY (`ROLE`) REFERENCES `roles` (`ID`);

--
-- Filtros para la tabla `places`
--
ALTER TABLE `places`
  ADD CONSTRAINT `PLACES_FK_1` FOREIGN KEY (`FLOOR`) REFERENCES `floors` (`ID`);

--
-- Filtros para la tabla `products_cat`
--
ALTER TABLE `products_cat`
  ADD CONSTRAINT `PRODUCTS_CAT_FK_1` FOREIGN KEY (`PRODUCT`) REFERENCES `products` (`ID`);

--
-- Filtros para la tabla `products_com`
--
ALTER TABLE `products_com`
  ADD CONSTRAINT `PRODUCTS_COM_FK_1` FOREIGN KEY (`PRODUCT`) REFERENCES `products` (`ID`),
  ADD CONSTRAINT `PRODUCTS_COM_FK_2` FOREIGN KEY (`PRODUCT2`) REFERENCES `products` (`ID`);

--
-- Filtros para la tabla `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `RECEIPTS_FK_MONEY` FOREIGN KEY (`MONEY`) REFERENCES `closedcash` (`MONEY`);

--
-- Filtros para la tabla `reservation_customers`
--
ALTER TABLE `reservation_customers`
  ADD CONSTRAINT `RES_CUST_FK_1` FOREIGN KEY (`ID`) REFERENCES `reservations` (`ID`),
  ADD CONSTRAINT `RES_CUST_FK_2` FOREIGN KEY (`CUSTOMER`) REFERENCES `customers` (`ID`);

--
-- Filtros para la tabla `stockcurrent`
--
ALTER TABLE `stockcurrent`
  ADD CONSTRAINT `STOCKCURRENT_ATTSETINST` FOREIGN KEY (`ATTRIBUTESETINSTANCE_ID`) REFERENCES `attributesetinstance` (`ID`),
  ADD CONSTRAINT `STOCKCURRENT_FK_1` FOREIGN KEY (`PRODUCT`) REFERENCES `products` (`ID`),
  ADD CONSTRAINT `STOCKCURRENT_FK_2` FOREIGN KEY (`LOCATION`) REFERENCES `locations` (`ID`);

--
-- Filtros para la tabla `stockdiary`
--
ALTER TABLE `stockdiary`
  ADD CONSTRAINT `STOCKDIARY_ATTSETINST` FOREIGN KEY (`ATTRIBUTESETINSTANCE_ID`) REFERENCES `attributesetinstance` (`ID`),
  ADD CONSTRAINT `STOCKDIARY_FK_1` FOREIGN KEY (`PRODUCT`) REFERENCES `products` (`ID`),
  ADD CONSTRAINT `STOCKDIARY_FK_2` FOREIGN KEY (`LOCATION`) REFERENCES `locations` (`ID`);

--
-- Filtros para la tabla `stocklevel`
--
ALTER TABLE `stocklevel`
  ADD CONSTRAINT `STOCKLEVEL_LOCATION` FOREIGN KEY (`LOCATION`) REFERENCES `locations` (`ID`),
  ADD CONSTRAINT `STOCKLEVEL_PRODUCT` FOREIGN KEY (`PRODUCT`) REFERENCES `products` (`ID`);

--
-- Filtros para la tabla `taxes`
--
ALTER TABLE `taxes`
  ADD CONSTRAINT `TAXES_CAT_FK` FOREIGN KEY (`CATEGORY`) REFERENCES `taxcategories` (`ID`),
  ADD CONSTRAINT `TAXES_CUSTCAT_FK` FOREIGN KEY (`CUSTCATEGORY`) REFERENCES `taxcustcategories` (`ID`),
  ADD CONSTRAINT `TAXES_TAXES_FK` FOREIGN KEY (`PARENTID`) REFERENCES `taxes` (`ID`);

--
-- Filtros para la tabla `taxlines`
--
ALTER TABLE `taxlines`
  ADD CONSTRAINT `TAXLINES_RECEIPT` FOREIGN KEY (`RECEIPT`) REFERENCES `receipts` (`ID`),
  ADD CONSTRAINT `TAXLINES_TAX` FOREIGN KEY (`TAXID`) REFERENCES `taxes` (`ID`);

--
-- Filtros para la tabla `ticketlines`
--
ALTER TABLE `ticketlines`
  ADD CONSTRAINT `TICKETLINES_ATTSETINST` FOREIGN KEY (`ATTRIBUTESETINSTANCE_ID`) REFERENCES `attributesetinstance` (`ID`),
  ADD CONSTRAINT `TICKETLINES_FK_2` FOREIGN KEY (`PRODUCT`) REFERENCES `products` (`ID`),
  ADD CONSTRAINT `TICKETLINES_FK_3` FOREIGN KEY (`TAXID`) REFERENCES `taxes` (`ID`),
  ADD CONSTRAINT `TICKETLINES_FK_TICKET` FOREIGN KEY (`TICKET`) REFERENCES `tickets` (`ID`);

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `TICKETS_CUSTOMERS_FK` FOREIGN KEY (`CUSTOMER`) REFERENCES `customers` (`ID`),
  ADD CONSTRAINT `TICKETS_FK_2` FOREIGN KEY (`PERSON`) REFERENCES `people` (`ID`),
  ADD CONSTRAINT `TICKETS_FK_ID` FOREIGN KEY (`ID`) REFERENCES `receipts` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
