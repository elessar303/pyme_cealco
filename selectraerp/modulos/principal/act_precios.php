<?php
session_start();

if ($_GET["generar"] == "si")
{
	require_once("../../config.ini.php");
	require_once('../../../generalp.config.inc.php');
	require_once('../../../includes/clases/BDControlador.php');
	
	$bdprecios =  new BDControlador();
	$bddat =  new BDControlador();	
	$bdpos =  new BDControlador();
	$pos = POS;
	try{
		$bddat->conexionRemota(DB_HOST,DB_USUARIO,DB_CLAVE,BASEDEDATOS);
		$bddat->conectar();
		$bddat->autocommit(FALSE);
		$bddat->setQuery("SELECT estado_region.id_region FROM localidad INNER JOIN almacen ON localidad.id = almacen.id_localidad INNER JOIN estado_region ON localidad.estado = estado_region.id_estado");
	   $resultadodat = $bddat->ejecutaInstruccion();
	   $fetch= $bddat->fetch($resultadodat);
	   $region = $fetch[id_region];
	   
		
		
	 	$bdprecios->conexionRemota(DB_HOSTP,DB_USUARIOP,DB_CLAVEP,DB_SELECTRA_PYMEP);
		$bdprecios->conectar();
		
		
		//echo "select codigo_barras, tipo_precio, precio from item_precio ip join item ii on (ii.id_item=ip.id_producto) where id_region='$region'";
		
		$bdprecios->setQuery("select codigo_barras, tipo_precio, precio, descripcion1, iva, itempos from item_precio ip join item ii on (ii.id_item=ip.id_producto) where id_region='$region'");
		
	   $resultadoprecios = $bdprecios->ejecutaInstruccion();
	   
	   while($rowprecios = $bdprecios->fetch($resultadoprecios))
	   {
	   	//echo $rowprecios[precio];
	   	//echo "<br>";
	   	$precio = $rowprecios[precio];
	   	$tipo = $rowprecios[tipo_precio];
	   	$cb = $rowprecios[codigo_barras];
	   	$descripcion = $rowprecios[descripcion1];
	   	$iva = $rowprecios[iva];
	   	$itemposp = $rowprecios[itempos];
	   	
	   	
	   	$sql="SELECT * FROM item WHERE codigo_barras='$cb'";
			$bddat->setQuery($sql);
	   	$resultadoitem = $bddat->ejecutaInstruccion();
	   	$rowitem=$bddat->fetch($resultadoitem);
			$itempos=$rowitem[itempos];
			
	   	$coniva=(($precio*$rowitem[iva])/100)+$precio;
	   	/*
	   	if($rowitem[id_item]=='')
	   	{
	   		$sql="INSERT INTO item (cod_item, descripcion1, iva) VALUES ()";
				$bddat->setQuery($sql);
		   	$bddat->ejecutaInstruccion();
	   	}
	   	*/
	   	if($tipo==1)
	   	{
				$sql="UPDATE item_precio ip join item ii on (ii.id_item=ip.id_producto)  SET ip.precio='$precio', ii.precio1='$precio',  ii.coniva1='$coniva', ii.precio_referencial1='$precio' WHERE ip.tipo_precio='$tipo' and ii.codigo_barras='$cb'";
				$bddat->setQuery($sql);
		   	$bddat->ejecutaInstruccion();
		   }
			elseif($tipo==2)
	   	{
				$sql="UPDATE item_precio ip join item ii on (ii.id_item=ip.id_producto)  SET ip.precio='$precio', ii.precio2='$precio',  ii.coniva2='$coniva', ii.precio_referencial2='$precio' WHERE ip.tipo_precio='$tipo' and ii.codigo_barras='$cb'";
				$bddat->setQuery($sql);
		   	$bddat->ejecutaInstruccion();
		   }
			elseif($tipo==3)
	   	{
				$sql="UPDATE item_precio ip join item ii on (ii.id_item=ip.id_producto)  SET ip.precio='$precio', ii.precio3='$precio',  ii.coniva3='$coniva', ii.precio_referencial3='$precio' WHERE ip.tipo_precio='$tipo' and ii.codigo_barras='$cb'";
				$bddat->setQuery($sql);
		   	$bddat->ejecutaInstruccion();
		   }
	   	
	   	if(($tipo==1)&&($pos!=''))
	   	{
		   	$sql="UPDATE $pos.products SET PRICEBUY='$precio', PRICESELL='$precio' WHERE ID='$itempos'";
				$bddat->setQuery($sql);
		   	$bddat->ejecutaInstruccion();
		   }
	   }
	   
	   $bddat->commit();
	   
		//return  Array('success' => true,'mensaje' =>'OK','certificado_regalo_id' => $lastId);
	}
	catch(Exception $ex){
		$bddat->rollback();
		$bddat->desconectar();
		//return  Array('success' => false,'mensaje' =>'NO_OK','error'=>$ex->getMessage());
	}
	
	header("Location: index.php?opt_menu=106");
	exit;
}
?>
