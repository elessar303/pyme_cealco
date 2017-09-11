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
$codigo_base = $itemCat->ObtenerFilasBySqlSelect("SELECT * FROM item join grupo on (item.cod_grupo=grupo.cod_grupo)");
foreach ($codigo_base as $ho)
{
	
	$itempos=$ho["itempos"];
	$grupopos=$ho["grupopos"];
	$itemmpos = $itemCat->ObtenerFilasBySqlSelect("SELECT * FROM $pos.products WHERE ID='$itempos' ");
  	if($itemmpos[0]["ID"]!="")
  	{
	  	$instruccion = "update $pos.stockcurrent set UNITS=0 WHERE PRODUCT='$itempos'";
		$itemCat->ExecuteTrans($instruccion);
   }
  	else
  	{
  			$iva=($ho[iva])/100;
  			$imp = $itemCat->ObtenerFilasBySqlSelect("SELECT * FROM $pos.taxes WHERE RATE='$iva' ");
  			$instruccion = "INSERT INTO  $pos.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISCOM, ISSCALE, ATTRIBUTES, QUANTITY_MAX, TIME_FOR_TRY) VALUES ('$itempos', '".$ho["referencia"]."', '".$ho["codigo_barras"]."', '".$ho["descripcion1"]."', '".$ho["coniva1"]."', '".$ho["coniva1"]."', '".$grupopos."', '".$imp[0]["CATEGORY"]."', 0, 0, null, '".$ho["cantidad_rest"]."', '".$ho["dias_rest"]."')";
   	$itemCat->ExecuteTrans($instruccion);
   	
   	$instruccion = "INSERT INTO  $pos.stockcurrent (LOCATION, PRODUCT, UNITS) VALUES ('579c039e-f8e7-4661-9607-ebdfd329ee61','$itempos', '0')";
   	$itemCat->ExecuteTrans($instruccion);
   	
   	$instruccion = "INSERT INTO  $pos.product_cat ( PRODUCT) VALUES ('$itempos')";
   	$itemCat->ExecuteTrans($instruccion);
	} 

}


?>