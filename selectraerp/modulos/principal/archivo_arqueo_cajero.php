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
$ruta="c:/wamp/www/siscolp_pyme/selectraerp/uploads/arqueo_cajero";
//ruta linux
//$ruta="/var/www/siscolp_pyme/selectraerp/uploads/arqueo_cajero";
$comunes = new ConexionComun();

$id=$comunes->ObtenerFilasBySqlSelect("select contador from correlativos where campo = 'arqueocajero_xenviar'");
$siga=$comunes->ObtenerFilasBySqlSelect("select codigo_siga from parametros_generales");

$maxid=$comunes->ObtenerFilasBySqlSelect("select max(id) as maximo from arqueo_cajero");

$hoy = getdate();
$fecha=$hoy['year'].$hoy['mon'].$hoy['mday'].$hoy['hours'].$hoy['minutes'].$hoy['seconds'];
$sql="SELECT * FROM arqueo_cajero 
where id between ".$id[0]['contador']." and  ".$maxid[0]['maximo']."
INTO OUTFILE 
'".$ruta."/".$siga[0]['codigo_siga']."_arqueo_cajero_".$fecha.".csv' 
FIELDS TERMINATED BY ',' 
ENCLOSED BY \"'\" LINES TERMINATED BY '\r\n' ";
$ejecutar_csv=$comunes->Execute2($sql);
$modificar=$comunes->Execute2("update correlativos set contador='".($maxid[0]['maximo']+1)."' where campo='arqueocajero_xenviar'");
?>