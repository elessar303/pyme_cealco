<?php

session_start();
ini_set("display_errors", 1);

require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
$itemCat = new Producto();

$codigo_base = $itemCat->ObtenerFilasBySqlSelect("SELECT * FROM grupo");
foreach ($codigo_base as $ho)
{
	$md5=md5($ho[descripcion]);
	$codigo="";
	$codigo=substr($md5,0,8).'-'.substr($md5,8,4).'-'.substr($md5,12,4).'-'.substr($md5,16,4).'-'.substr($md5,20,12);
   echo $instruccion="update grupo set grupopos='$codigo' where cod_grupo='$ho[cod_grupo]'";
   echo "<br>";
   $itemCat->ExecuteTrans($instruccion);
   $i++; 
}


?>