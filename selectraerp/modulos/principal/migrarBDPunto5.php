<?php
ini_set("memory_limit","1024M");


session_start();

$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");

require_once("../../libs/php/clases/ConexionComun.php");

require_once("../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
$nombreBDI="selectrapos_diana_286_2014";
$nombreBD="selectrapos_diana_286";
$migrar = new Producto();
$sql="SELECT * FROM $nombreBD.receipts WHERE DATENEW between '2014-05-01 00:00:00' and '2014-05-31 23:00:00'";
$obtRecipts=$migrar->ObtenerFilasBySqlSelect($sql);


foreach ($obtRecipts as $lista) {
	//echo $atributo=addslashes($lista["ATTRIBUTES"]);exit;
	//RECEIPTS
	$instruccion = "
		INSERT INTO $nombreBDI.receipts (
			`ID`,
			`MONEY`,
			`DATENEW`,
			`ATTRIBUTES`
		)
		VALUES (
 		'".$lista["ID"]."', 
 		'".$lista["MONEY"]."',
 		'".$lista["DATENEW"]."', 		  
 		'".addslashes($lista["ATTRIBUTES"])."'
		);";
	$migrar->Execute2($instruccion);

	//PAYMENTS
	$sql1="SELECT * FROM $nombreBD.payments WHERE RECEIPT='".$lista["ID"]."'";
	$obtPaymes=$migrar->ObtenerFilasBySqlSelect($sql1);
	foreach ($obtPaymes as $value) {
		$instruccion2 = "
		INSERT INTO $nombreBDI.payments (
			`ID`,
			`RECEIPT`,
			`PAYMENT`,
			`TOTAL`,
			`TRANSID`,
			`RETURNMSG`
		)
		VALUES (
 		'".$value["ID"]."', 
 		'".$value["RECEIPT"]."',
 		'".$value["PAYMENT"]."', 	
 		'".$value["TOTAL"]."',
 		'".$value["TRANSID"]."', 	  
 		'".$value["RETURNMSG"]."'
		);";
		$migrar->Execute2($instruccion2);
	}
	
	
//TICKETS
	$sql2="SELECT * FROM $nombreBD.tickets WHERE ID='".$lista["ID"]."'";
	$obtTicktes=$migrar->ObtenerFilasBySqlSelect($sql2);

	$instruccion3 = "
		INSERT INTO $nombreBDI.tickets (
			`ID`,
			`TICKETTYPE`,
			`TICKETID`,
			`PERSON`,
			`CUSTOMER`,
			`STATUS`
		)
		VALUES (
 		'".$obtTicktes[0]["ID"]."', 
 		'".$obtTicktes[0]["TICKETTYPE"]."',
 		'".$obtTicktes[0]["TICKETID"]."', 	
 		'".$obtTicktes[0]["PERSON"]."',
 		'".$obtTicktes[0]["CUSTOMER"]."', 	  
 		'".$obtTicktes[0]["STATUS"]."'
		);";
	$migrar->Execute2($instruccion3);
	
//TAXLINES
	$sql3="SELECT * FROM $nombreBD.taxlines WHERE RECEIPT='".$lista["ID"]."'";
	$obtTaxlines=$migrar->ObtenerFilasBySqlSelect($sql3);

	foreach ($obtTaxlines as  $value) {
		$instruccion4 = "
		INSERT INTO $nombreBDI.taxlines (
			`ID`,
			`RECEIPT`,
			`TAXID`,
			`BASE`,
			`AMOUNT`		
		)
		VALUES (
 		'".$value["ID"]."', 
 		'".$value["RECEIPT"]."',
 		'".$value["TAXID"]."', 	
 		'".$value["BASE"]."',
 		'".$value["AMOUNT"]."'	  		
		);";
		$migrar->Execute2($instruccion4);
	}

}

$sql8="SELECT  DISTINCT MONEY  FROM $nombreBD.receipts WHERE `DATENEW` BETWEEN '2014-05-01 00:00:00' AND '2014-05-31 23:00:00'";
	$tickteRecipsD=$migrar->ObtenerFilasBySqlSelect($sql8);
foreach ($tickteRecipsD as $value) {
	//CLOSECASH
	$sql9="SELECT * FROM $nombreBD.closedcash WHERE MONEY='".$value["MONEY"]."'";
	$obtCloseC=$migrar->ObtenerFilasBySqlSelect($sql9);

	$instruccion5 = "
		INSERT INTO $nombreBDI.closedcash (
			`MONEY`,
			`HOST`,
			`HOSTSEQUENCE`,
			`DATESTART`,
			`DATEEND`		
		)
		VALUES (
 		'".$obtCloseC[0]["MONEY"]."', 
 		'".$obtCloseC[0]["HOST"]."',
 		'".$obtCloseC[0]["HOSTSEQUENCE"]."', 	
 		'".$obtCloseC[0]["DATESTART"]."',
 		'".$obtCloseC[0]["DATEEND"]."'	  		
		);";
	$migrar->Execute2($instruccion5);

}
//TICKETLINES
$sql4="SELECT * FROM $nombreBD.ticketlines WHERE DATENEW between '2014-05-01 00:00:00' and '2014-05-31 23:00:00' ";
	$ticktelines=$migrar->ObtenerFilasBySqlSelect($sql4);
	foreach ($ticktelines as $lista) {

		$instruccion6 = "
		INSERT INTO $nombreBDI.ticketlines (
			`TICKET`,
			`LINE`,
			`PRODUCT`,
			`ATTRIBUTESETINSTANCE_ID`,
			`UNITS`,
			`PRICE`,
			`TAXID`,
			`ATTRIBUTES`,
			`DATENEW`		
		)
		VALUES (
 		'".$lista["TICKET"]."', 
 		'".$lista["LINE"]."',
 		'".$lista["PRODUCT"]."', 	
 		'".$lista["ATTRIBUTESETINSTANCE_ID"]."',
 		'".$lista["UNITS"]."', 
 		'".$lista["PRICE"]."',
 		'".$lista["TAXID"]."', 	
 		'".$lista["ATTRIBUTES"]."',
 		'".$lista["DATENEW"]."'	  		
		);";
		$migrar->Execute2($instruccion6);
	}
//STOCKDIARY
	$sql5="SELECT * FROM $nombreBD.stockdiary WHERE DATENEW between '2014-05-01 00:00:00' and '2014-05-31 23:00:00' ";
	$dary=$migrar->ObtenerFilasBySqlSelect($sql5);
	foreach ($dary as $lista) {

		$instruccion7 = "
		INSERT INTO $nombreBDI.stockdiary (
			`ID`,
			`DATENEW`,
			`REASON`,
			`LOCATION`,
			`PRODUCT`,
			`ATTRIBUTESETINSTANCE_ID`,
			`UNITS`,
			`PRICE`		
		)
		VALUES (
 		'".$lista["ID"]."', 
 		'".$lista["DATENEW"]."',
 		'".$lista["REASON"]."', 	
 		'".$lista["LOCATION"]."',
 		'".$lista["PRODUCT"]."', 
 		'".$lista["ATTRIBUTESETINSTANCE_ID"]."',
 		'".$lista["UNITS"]."', 	
 		'".$lista["PRICE"]."'	  		
		);";
		$migrar->Execute2($instruccion7);
	}



echo "termino";
