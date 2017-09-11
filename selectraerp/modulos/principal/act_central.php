<?php
session_start();

if ($_GET["generar"] == "si")
{
	require_once("../../config.ini.php");
	require_once('../../../generalp.config.inc.php');
	require_once('../../../includes/clases/BDControlador.php');
	
	$bdprecios =  new BDControlador();
	$bddat =  new BDControlador();	

	$pos = POS;
	//exit;
	try{
		$bddat->conexionRemota(DB_HOST,DB_USUARIO,DB_CLAVE,BASEDEDATOS);
		$bddat->conectar();
		
		$bddat->setQuery("SELECT codigo_siga FROM parametros_generales");
	   $resultadodat = $bddat->ejecutaInstruccion();
	   $fetch= $bddat->fetch($resultadodat);
	   $siga = $fetch[codigo_siga];
	   
	    $inicio=date("Y-m-d")."00:00:00";
	   $final=date("Y-m-d")."23:00:00";
	   $fecha=date("Y-m-d");
	   // $inicio="2014-11-10"." 00:00:00";
 	  //  $final="2014-11-10"." 23:00:00";
	   // $fecha="2014-11-10";
	   /*
		$inicio="2014-02-26 00:00:00";
	   $final="2014-02-26 23:00:00";
	   $fecha="2014-02-26";
		*/
		//$siga = "233";
		
	 	$bdprecios->conexionRemota(DB_HOSTP,DB_USUARIOP,DB_CLAVEP,DB_SELECTRA_PYMEP);
		$bdprecios->conectar();
		$bdprecios->autocommit(FALSE);
		
		//echo "select codigo_barras, tipo_precio, precio from item_precio ip join item ii on (ii.id_item=ip.id_producto) where id_region='$region'";
		
		$bddat->setQuery("SELECT products.REFERENCE,
		products.NAME,
		products.CODE,
		products.PRICESELL,
		taxes.RATE,
		sum(ticketlines.UNITS) as UNITS
		FROM $pos.products INNER JOIN
		$pos.ticketlines ON products.ID = ticketlines.PRODUCT INNER JOIN
		$pos.tickets ON ticketlines.TICKET = tickets.ID 
		INNER JOIN $pos.taxes ON ticketlines.TAXID = taxes.ID 
		WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
		GROUP BY products.NAME 
		ORDER BY NAME");
		
	   $resultadodat = $bddat->ejecutaInstruccion();
	   
	   while($rowdat = $bddat->fetch($resultadodat))
	   {
			$bdprecios->setQuery("INSERT INTO vproducto (fecha, siga, referencia, nombre, codigo_barra, precio, iva, unidad) VALUES ('$fecha', '$siga', '".$rowdat[REFERENCE]."', '".$rowdat[NAME]."', '".$rowdat[CODE]."', '".$rowdat[PRICESELL]."', '".$rowdat[RATE]."', '".$rowdat[UNITS]."')");
	   	$bdprecios->ejecutaInstruccion();
	   }

		$bddat->setQuery("SELECT people.NAME,
	  payments.PAYMENT,
	  sum(payments.TOTAL) as TOTAL
		FROM $pos.payments INNER JOIN
	  $pos.receipts ON receipts.ID = payments.RECEIPT INNER JOIN
	  $pos.tickets ON tickets.ID = receipts.ID INNER JOIN
	  $pos.people ON tickets.PERSON = people.ID       
		WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
		GROUP BY people.NAME, payments.PAYMENT  
		ORDER BY NAME");
		
	   $resultadodat = $bddat->ejecutaInstruccion();
	   
	   while($rowdat = $bddat->fetch($resultadodat))
	   {
			$bdprecios->setQuery("INSERT INTO vcajero (fecha, siga, nombre, tipo_pago, monto) VALUES ('$fecha', '$siga', '".$rowdat[NAME]."', '".$rowdat[PAYMENT]."', '".$rowdat[TOTAL]."')");
	   	$bdprecios->ejecutaInstruccion();
	   }

	   
	   $bdprecios->commit();
	   
		//return  Array('success' => true,'mensaje' =>'OK','certificado_regalo_id' => $lastId);
	}
	catch(Exception $ex){
		$bdprecios->rollback();
		$bdprecios->desconectar();
		//return  Array('success' => false,'mensaje' =>'NO_OK','error'=>$ex->getMessage());
	}
	
	header("Location: index.php?opt_menu=106");
	exit;
}
?>
