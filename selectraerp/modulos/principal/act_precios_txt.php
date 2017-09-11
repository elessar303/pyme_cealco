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
	   
	   //$directorio="txt/nomina".date("Y_m_d_H_i_s");
	   $directorio="txt";
		//if(mkdir($directorio))
		//{
			$ruta=$directorio."/precios".date("Y_m_d_H_i_s").".txt";
			$archivo= fopen($ruta,"w");
			chmod($directorio,0777);
			chmod($ruta,0777);
		//}
		//else
		//{
		//	echo "No se pudo crear el directorio";
		//}
		
		
	 	$bdprecios->conexionRemota(DB_HOSTP,DB_USUARIOP,DB_CLAVEP,DB_SELECTRA_PYMEP);
		$bdprecios->conectar();
		
		
		//echo "select codigo_barras, tipo_precio, precio from item_precio ip join item ii on (ii.id_item=ip.id_producto) where id_region='$region'";
		
		$bdprecios->setQuery("select codigo_barras, tipo_precio, precio from item_precio ip join item ii on (ii.id_item=ip.id_producto) where id_region='$region'");
	   $resultadoprecios = $bdprecios->ejecutaInstruccion();
	   
	   while($rowprecios = $bdprecios->fetch($resultadoprecios))
	   {
	   	//echo $rowprecios[precio];
	   	//echo "<br>";
	   	$precio = $rowprecios[precio];
	   	$tipo = $rowprecios[tipo_precio];
	   	$cb = $rowprecios[codigo_barras];
	   	
	   	$detalles=$cb."	".$tipo."	".$precio;
	   	$detalles.="\r\n";
			fwrite($archivo,$detalles);
	   }
	   $nom_arch="precios".date("Y_m_d_H_i_s").".txt";
		fclose($archivo);
		//echo "Content-Disposition: attachment; filename=".$ruta;
		header("Content-type: application/octet-stream");
		readfile($ruta); 
		header("Content-Disposition: attachment; filename=$nom_arch");
	   $bddat->commit();
	   
		//return  Array('success' => true,'mensaje' =>'OK','certificado_regalo_id' => $lastId);
	}
	catch(Exception $ex){
		$bddat->rollback();
		$bddat->desconectar();
		//return  Array('success' => false,'mensaje' =>'NO_OK','error'=>$ex->getMessage());
	}
	
	//header("Location: index.php?opt_menu=106");
	//exit;
}
?>
