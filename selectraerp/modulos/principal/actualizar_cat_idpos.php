<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", -1);

require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");

require_once("../../libs/php/clases/ConexionComun.php");

require_once("../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/comunes.php");

$almacen = new Almacen();

$comun = new Comunes();
$codigo_base = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM grupo");
foreach ($codigo_base as $ho)
{
		$idpos=$comun->codigo_pos($ho["descripcion"]);
		$instruccion="UPDATE grupo set grupopos='".$idpos."' where descripcion='".$ho["descripcion"]."'";
		echo $instruccion;
		echo "<br>";
		$almacen->Execute2($instruccion);
}
exit();

?>