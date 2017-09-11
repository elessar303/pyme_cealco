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
$pos=POS;
$codigo_base = $itemCat->ObtenerFilasBySqlSelect("SELECT * FROM grupo");
foreach ($codigo_base as $ho)
{
	if($ho["descripcion"]!='')
	{
		$idpos=$ho[grupopos];
		if($_POST["restringido"]==1) 
		{
			$instruccion = "INSERT INTO $pos.categories (ID, NAME, QUANTITYMAX, TIMEFORTRY) VALUES ('$idpos','".$ho["descripcion"]."','".$ho["cantidad_rest"]."','".$ho["dias_rest"]."')";
		}
		else
		{
			$instruccion = "INSERT INTO $pos.categories (ID, NAME) VALUES ('$idpos','".$ho["descripcion"]."')";
		}
		echo $instruccion;
		echo "<br>";
		$itemCat->Execute2($instruccion);
	}

}


?>