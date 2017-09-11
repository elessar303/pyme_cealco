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

$i=591;

$codigo_base = $itemCat->ObtenerFilasBySqlSelect("SELECT * FROM item where id_item>2081");
foreach ($codigo_base as $ho) {
   if(strlen($i)==1)
   {
   	$cod="P0000".$i;	
   } 
   elseif(strlen($i)==2)
   {
   	$cod="P000".$i;	
   }
   elseif(strlen($i)==3)
   {
   	$cod="P00".$i;	
   }
   elseif(strlen($i)==4)
   {
   	$cod="P0".$i;	
   }
   elseif(strlen($i)==5)
   {
   	$cod="P".$i;	
   }
	
    $instruccion="update item set cod_item='$cod' where id_item='$ho[id_item]'";
    $itemCat->ExecuteTrans($instruccion);
    
   $i++; 
}


?>