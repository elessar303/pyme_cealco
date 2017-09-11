<?php
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);

require_once("../../../generalp.config.inc.php");
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");

require_once("../../config.ini.php");
require_once('../../../includes/clases/BDControlador.php');


// $bdpyme= new BDControlador();
// $bdpyme->setBd(DB_SELECTRA_FAC);
// $bdpyme->conectar();

// $bdpyme->setQuery("SELECT * FROM control_sincronizacion_central
//             WHERE fecha_cierre_debido = ( 
//             SELECT MAX( `fecha_cierre_debido` ) 
//             FROM control_sincronizacion_central )"
// );
// $resultadopyme = $bdpyme->ejecutaInstruccion();
// $fetch= $bdpyme->fetch($resultadopyme);
// $fechaDebido = $fetch["fecha_cierre_debido"];
// // $fechaDebido = date ( 'Y-m-d' );
// $fechaCierre= $fetch["fecha_dia_cierre"];
// $smarty->assign("fechaDebido",$fechaDebido);
// $smarty->assign("fechaCierre",$fechaCierre);

// //  
// // Cargando fecha hasta donde se sincronizara en combo select
// $arraySelectOption = "";
// $fechaAyer=strftime( "%Y-%m-%d",strtotime("-1 day"));
// $nuevafecha=$fechaDebido;
// while ($nuevafecha <= $fechaAyer) {   
// 	$arraySelectOption[] = $nuevafecha; 
//     $nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
//     $nuevafecha = date ( 'Y-m-d' , $nuevafecha );

// }

// $smarty->assign("option_values_fechaSincro", $arraySelectOption);



if ($_GET["generar"] == "si")
{

	require_once("../../config.ini.php");
	require_once('../../../generalp.config.inc.php');
	
	
	$bdprecios =  new BDControlador();
	$bddat =  new BDControlador();	

	//$pos = POS;
	//exit;
	try{

		$bddat->conexionRemota(DB_HOSTP,DB_USUARIOP,DB_CLAVEP,DB_SELECTRA_PYMEP);
		$bddat->conectar();
		

		//$bdp = DB_SELECTRA_PYMEP;
		
		 // $bddat->setQuery("SELECT codigo_siga FROM parametros_generales");
	  //  	$resultadodat = $bddat->ejecutaInstruccion();	   
	  //  	$fetch= $bddat->fetch($resultadodat);
	  //  	$siga = $fetch[codigo_siga];

	   
	   	 $inicio=date("Y-m-d")." 00:00:00";
	   	$final=date("Y-m-d")." 23:00:00";
	   	$fecha=date("Y-m-d");
	  
		$inicio="2015-03-13 00:00:00";
	   	$final="2015-03-13 23:00:00";
	 	$fecha="2015-03-13";
		
		//$siga = "233";
		
	 	$bdprecios->conexionRemota(DB_HOSTPP,DB_USUARIOPP,DB_CLAVEPP,DB_SELECTRA_PYMEPP);
		$bdprecios->conectar();
		$bdprecios->autocommit(FALSE);


		$bddat->setQuery("SELECT fecha, siga, referencia, nombre, codigo_barra, precio, iva, unidad FROM vproducto where fecha='$fecha' ");
		
	   	$resultadodat = $bddat->ejecutaInstruccion();	
		$cont=0;
	   	while($rowdat = $bddat->fetch($resultadodat))
	   	{
	   		$cont++;

			$bdprecios->setQuery("INSERT INTO vproducto (fecha, siga, referencia, nombre, codigo_barra, precio, iva, unidad) VALUES ('".$rowdat[fecha]."', '".$rowdat[siga]."', '".$rowdat[referencia]."', '".$rowdat[nombre]."', '".$rowdat[codigo_barra]."', '".$rowdat[precio]."', '".$rowdat[iva]."', '".$rowdat[unidad]."')");
	   		$bdprecios->ejecutaInstruccion();
	   	}
		
	echo $cont;
	   	$bddat->setQuery("SELECT * FROM vcajero where fecha='$fecha' ");
		$resultadodat = $bddat->ejecutaInstruccion();
		while($rowdat = $bddat->fetch($resultadodat))
	   {
			$bdprecios->setQuery("INSERT INTO vcajero (fecha, siga, nombre, tipo_pago, monto) VALUES ('".$rowdat[fecha]."', '".$rowdat[siga]."', '".$rowdat[nombre]."', '".$rowdat[tipo_pago]."', '".$rowdat[monto]."')");
	   	$bdprecios->ejecutaInstruccion();
	   }
	   
	   
	   $bdprecios->commit();
	   
		//return  Array('success' => true,'mensaje' =>'OK','certificado_regalo_id' => $lastId);
	}
	catch(Exception $ex){
		echo "error en la base de datos";
		// $bdprecios->rollback();
		// $bdprecios->desconectar();
		//return  Array('success' => false,'mensaje' =>'NO_OK','error'=>$ex->getMessage());
	}
	
	
	// if(isset($_GET["directo"])!="si")
	// 	header("Location: index.php?opt_menu=106");
	echo "fin";
	exit;
}
?>
