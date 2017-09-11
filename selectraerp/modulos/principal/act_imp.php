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

$codigo_base = $itemCat->ObtenerFilasBySqlSelect("SELECT * FROM imp");
foreach ($codigo_base as $ho)
{
	if($ho[B]=='F')
	{
		echo $instruccion="update item set iva='0' where codigo_barras='$ho[A]'";
	   echo "<br>";
	}
	elseif($ho[B]=='T')
	{
		echo $instruccion="update item set iva='12' where codigo_barras='$ho[A]'";
	   echo "<br>";
	}  
	
	$itemCat->ExecuteTrans($instruccion);
}


?>