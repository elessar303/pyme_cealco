<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/siscol_pyme";
ini_set("display_errors", 1);

//Includes
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
//ruta windows
$ruta="c:/wamp/www/siscolp_pyme/selectraerp/uploads/libro_venta";
//ruta linux
//$ruta="/var/www/siscolp_pyme/selectraerp/uploads/libro_venta";
$comunes = new ConexionComun();

$id=$comunes->ObtenerFilasBySqlSelect("select contador from correlativos where campo = 'libroventa_xenviar'");

$maxid=$comunes->ObtenerFilasBySqlSelect("select max(id) as maximo from libroventas_xenviar");

$hoy = getdate();
$fecha=$hoy['year'].$hoy['mon'].$hoy['mday'].$hoy['hours'].$hoy['minutes'].$hoy['seconds'];
$sql="SELECT * FROM libroventas_xenviar 
where id between ".$id[0]['contador']." and  ".$maxid[0]['maximo']."
INTO OUTFILE 
'".$ruta."/libro_venta_".$fecha.".csv' 
FIELDS TERMINATED BY ',' 
ENCLOSED BY \"'\" LINES TERMINATED BY '\r\n' ";
$ejecutar_csv=$comunes->Execute2($sql);
$modificar=$comunes->Execute2("update correlativos set contador='".($maxid[0]['maximo']+1)."' where campo='libroventa_xenviar'");
?>