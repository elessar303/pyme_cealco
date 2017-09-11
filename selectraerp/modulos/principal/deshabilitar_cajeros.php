<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");

$comunes= new Producto();
$pyme=DB_SELECTRA_FAC;
$pos=POS;

$ultimaFecha = $comunes->ObtenerFilasBySqlSelect("SELECT fecha_cierre from control_sincronizacion ORDER BY id DESC LIMIT 1");
$dteStart = $ultimaFecha[0]['fecha_cierre'];
$dteEnd = date('Y-m-d H:i:s');

function dateDiff($start, $end) {

$start_ts = strtotime($start);

$end_ts = strtotime($end);

$diff = $end_ts - $start_ts;

return round($diff / 3600);

}

echo $interval = dateDiff($dteStart, $dteEnd);

if($interval>=24){
$sql="INSERT INTO $pos.people_caja (id_people) SELECT ID FROM $pos.people WHERE VISIBLE=1 AND ROLE>=2 AND ID NOT IN (SELECT id_people FROM $pos.people_caja)";
$array_sucursal=$comunes->Execute2($sql);

$sql="UPDATE $pos.people SET VISIBLE=0 WHERE ROLE>=2 AND ID IN (SELECT id_people FROM $pos.people_caja)";
$array_sucursal=$comunes->Execute2($sql);
}

?>