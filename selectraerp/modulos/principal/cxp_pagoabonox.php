<?php
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/cxp.php");
include("../../libs/php/clases/correlativos.php");
include("../../libs/php/clases/factura.php");
include("../../../menu_sistemas/lib/common.php");

$proveedores = new Proveedores();
$cxp = new cxp();
$correlativos = new Correlativos();
$factura = new Factura();

if(isset($_POST["montoPago"])){ // si el usuario iso post
//echo "Cancelar: ".$_POST["totalizar_monto_cancelar"];
//echo "Cancelar: ".$_POST["totalizar_monto_cancelar"];
//echo "General: ".$_POST["totalizar_total_general"];
//exit(0);
	if($_POST["montoPago"]>0)
	{
		$cxp->BeginTrans();
		$id_cxp = $_POST["cod_edocuenta"];
		/**
			* Verificamos si la factura fue pagada completa.
			*/
	
		if($_POST["montoPendiente"]==0)
		{
			$marca = "X"; // indicamos con esto en el campo <marca> de la tabla cxp_edocuenta que fue pagada
			$cod_estatus =  "2"; // cod_estatus = 2 indicada que esta pagada
			$instruccion = "update compra f right join cxp_edocuenta cxp
			on cxp.numero = f.cod_compra
			set f.cod_estatus = ".$cod_estatus.",
			f.fecha_pago= '".date("Y-m-d")."',
			cxp.marca = 'X'
			where cxp.cod_edocuenta = ".$_POST["cod_edocuenta"];
			$cxp->ExecuteTrans($instruccion);
		}

		$cod_pago_o_abono = $correlativos->getUltimoCorrelativo("cod_pago_o_abonoCXP", 0, "si","");

		if($_POST["cod_compra"]=='')
		{
			$nro_comp = $cxp->ObtenerFilasBySqlSelect("select numero from cxp_edocuenta where cod_edocuenta=".$_POST["cod_edocuenta"]);
			$_POST["cod_compra"]=$nro_comp[0][numero];
		}

		$SQL_cxp_DET = "INSERT INTO cxp_edocuenta_detalle (
		`cod_edocuenta_detalle` ,
		`cod_edocuenta` ,
		`documento` ,
		`numero` ,
		`descripcion` ,
		`tipo` ,
		`monto` ,
		`usuario_creacion` ,
		`fecha_creacion`,
		`fecha_emision_edodet`,
		`marca`
		)
		VALUES (
		NULL , '".$id_cxp."', 'ABO/PAGOxFACxCOM', '".$cod_pago_o_abono."', '".$_POST["descripcion"]."',
		'd', '".$_POST["montoPago"]."', '".$login->getUsuario()."',
		CURRENT_TIMESTAMP, '".fecha_sql($_POST["fecha"])."', 'P'
		);";
		/**
		* Se inserta el detalle de la cxp en este caso el asiento del CREDITO.
		*/
		$cxp->ExecuteTrans($SQL_cxp_DET);
		$cod_edocuenta_detalle = $cxp->getInsertID();

		// AQUI HACER LA INSERCION EN LA NUEVA TABLA DE LAS FACTURAS CONTRA EL PAGO

		$facturas=explode(",",$_POST[idFacturas]);
		$j=0;
		while($facturas[$j])
		{
			$SQL_cxp_factura_pago = "
			INSERT INTO cxp_factura_pago (
			cxp_factura_pago_pk ,
			cxp_edocuenta_detalle_fk,
			cxp_factura_fk)
			VALUES (
			NULL ,
			'".$cod_edocuenta_detalle."',
			'".$facturas[$j]."');
			";
			$cxp->ExecuteTrans($SQL_cxp_factura_pago);
			$j++;
		}
	
		/**
		* SQL para generar el detalle de forma pago en la tabla de cxp_edocuenta_formapago.
		*/
		$SQL_cxp_formapago = "
		INSERT INTO cxp_edocuenta_formapago (
		`cod_cxp_edocuenta_formapago` ,
		`cod_edocuenta_detalle` ,
		`totalizar_monto_cancelar` ,
		`totalizar_saldo_pendiente` ,
		`totalizar_cambio` ,
		`totalizar_monto_efectivo` ,
		`opt_cheque` ,
		`totalizar_monto_cheque` ,
		`totalizar_nro_cheque` ,
		`totalizar_nombre_banco` ,
		`opt_tarjeta` ,
		`totalizar_monto_tarjeta` ,
		`totalizar_nro_tarjeta` ,
		`totalizar_tipo_tarjeta` ,
		`opt_deposito` ,
		`totalizar_monto_deposito` ,
		`totalizar_nro_deposito` ,
		`totalizar_banco_deposito` ,
	
		`opt_otrodocumento` ,
		`totalizar_monto_otrodocumento` ,
		`totalizar_nro_otrodocumento` ,
		`totalizar_banco_otrodocumento` ,
	
		`fecha_creacion` ,
		`usuario_creacion`
		)
		VALUES (
		NULL ,
		'".$cod_edocuenta_detalle."',
		'".$_POST["montoPago"]."',
		'".$_POST["montoPendiente"]."',
		'".$_POST["totalizar_cambio"]."',
		'".$_POST["totalizar_monto_efectivo"]."',
		'".$_POST["opt_cheque"]."',
		'".$_POST["totalizar_monto_tarjeta"]."',
		'".$_POST["totalizar_nro_cheque"]."',
		'".$_POST["totalizar_nombre_banco"]."',
		'".$_POST["opt_tarjeta"]."',
		'".$_POST["totalizar_monto_tarjeta"]."',
		'".$_POST["totalizar_nro_tarjeta"]."',
		'".$_POST["totalizar_tipo_tarjeta"]."',
		'".$_POST["opt_deposito"]."',
		'".$_POST["totalizar_banco_deposito"]."',
		'".$_POST["totalizar_nro_deposito"]."',
		'".$_POST["totalizar_banco_deposito"]."',
	
		'".$_POST["opt_otrodocumento"]."',
		'".$_POST["totalizar_banco_otrodocumento"]."',
		'".$_POST["totalizar_nro_otrodocumento"]."',
		'".$_POST["totalizar_banco_otrodocumento"]."',
		CURRENT_TIMESTAMP , '".$login->getUsuario()."'
		);
		";
		$cxp->ExecuteTrans($SQL_cxp_formapago);
	
		$nro_facturaOLD = $correlativos->getUltimoCorrelativo("cod_pago_o_abonoCXP", 1, "no");
		$nro_factura = $correlativos->getUltimoCorrelativo("cod_pago_o_abonoCXP", 1, "no");
		$cxp->ExecuteTrans("update correlativos set contador = '".$nro_factura."' where campo = 'cod_pago_o_abonoCXP'");
		$nro_factura -= 1;
		if($cxp->errorTransaccion==1)
		{
			Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Pago/Abono por Compra fue generado exitosamente bajo el <b>Nro. ".$cod_pago_o_abono."</b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total: ".number_format($_POST["montoPago"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/cancelar.png\"> <b>Monto Cancelado: ".number_format($_POST["montoPago"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total Retencion: ".number_format($_POST["totalRet"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/ico_view.gif\"> <b><span style=\"color:red;\">Monto Pendiente: ".number_format($_POST["montoPendiente"], 2, ",", ".") ." </span></b><br></span>");
		}
		if($cxp->errorTransaccion==0)
		{
			Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la factura de compra.</span>");
		}
        	//$cxp->CommitTrans($cxp->errorTransaccion);
	
	
		//$cxp->BeginTrans();
	
		$x=0;
		while($x<$_POST[facturas])
		{
			if($_POST["tipoDoc$x"]=="NC")
			{
				$SQL_CXC_DET2 = "INSERT INTO cxp_edocuenta_detalle (
				`cod_edocuenta_detalle` ,
				`cod_edocuenta` ,
				`documento` ,
				`numero` ,
				`descripcion` ,
				`tipo` ,
				`monto` ,
				`usuario_creacion` ,
				`fecha_creacion` ,
				`fecha_emision_edodet`
				)
				VALUES (
				NULL ,
				'".$id_cxp."',
				'ABO/PAGOxFACxCOM-NC',
				'".$_POST["cod_compra"]."',
				'POR NOTA DE CREDITO N: ".$_POST["cod_factura$x"]."',
				'd',
				'".$_POST["monto$x"]."',
				'".$login->getUsuario()."',
				CURRENT_TIMESTAMP, 
				'".fecha_sql($_POST["fecha"])."');";
				/**
				* Se inserta el detalle de la CxC en este caso el asiento de lDEBITO.
				*/
				$factura->ExecuteTrans($SQL_CXC_DET2);
			}
			$x++;
		}
		
		for($i=0;$i<(int)$_POST["cantidadImp"];$i++)
		{
			$detalle_tabla_impuesto = "
			INSERT INTO tabla_impuestos (
			`id_tabla_impuestos` ,
			`id_documento` ,
			`tipo_documento` ,
			`numero_control_factura` ,
			`id_fiscal` ,
			`id_cliente` ,
			`cod_tipo_impuesto` ,
			`cod_impuesto` ,
			`totalizar_pbase_retencion` ,
			`totalizar_monto_retencion` ,
			`totalizar_base_imponible` ,
			`totalizar_monto_exento` ,
			`usuario_creacion` ,
			`fecha_creacion`
			)
			VALUES (
			NULL ,
			'".$_POST["id_compra"]."',
			'c',
			'".$_POST["cod_compra"]."',
			'".$_POST["id_fiscal"]."',
			'".$_POST["id_proveedor"]."',
			'".$_POST["tipoImp$i"]."',
			'".$_POST["codImp$i"]."',
			'".$_POST["porcen$i"]."', '".$_POST["montoRet$i"]."',
			'".$_POST["base$i"]."', '".$_POST["exento$i"]."',
			'".$login->getUsuario()."',CURRENT_TIMESTAMP);";
			$factura->ExecuteTrans($detalle_tabla_impuesto);
	
			$codImpuesto = $_POST["codImp$i"];
			$impuesto_ = $factura->ObtenerFilasBySqlSelect("select  * from lista_impuestos where cod_impuesto = ".$codImpuesto);
	
			$SQL_CXC_DET2 = "INSERT INTO cxp_edocuenta_detalle (
			`cod_edocuenta_detalle` ,
			`cod_edocuenta` ,
			`documento` ,
			`numero` ,
			`descripcion` ,
			`tipo` ,
			`monto` ,
			`usuario_creacion` ,
			`fecha_creacion` ,
			`fecha_emision_edodet`
			)
			VALUES (
			NULL ,
			'".$id_cxp."',
			'ABO/PAGOxFACxCOM',
			'".$_POST["cod_compra"]."',
			'".$impuesto_[0]["descripcion"]."',
			'd',
			'".$_POST["montoRet$i"]."',
			'".$login->getUsuario()."',
			CURRENT_TIMESTAMP, 
			'".fecha_sql($_POST["fecha"])."');";
			/**
			* Se inserta el detalle de la CxC en este caso el asiento de lDEBITO.
			*/
			$factura->ExecuteTrans($SQL_CXC_DET2);
			//}// FIN DEL IF DE NSERTAR DETALLE DE IMPUESTOS EN ESTADO DE CUENTA
	
			// FIN DEL IF DE INSERTAR IMPUESTOS EN LA TABLA IMPUESTOS
		}
		$cxp->CommitTrans($cxp->errorTransaccion);
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=edocuenta&cod=".$_POST["cod"]);
		exit;
	}
} // si el usuario iso post


if(!isset($_GET["cod"]))
{
	header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
	exit;
}

if(!isset($_GET["cod2"]))
{
	header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
	exit;
}

$edoCta=$_GET['cod2'];
/**
 * Cabecera del Estado de Cuenta.
 */
$cabecera_estadodecuenta = $cxp->ObtenerFilasBySqlSelect('
select 
ifnull(sum(debito),0.00) as debito,
ifnull(sum(credito),0.00) as credito,
ifnull(sum(credito) - sum(debito) ,0.00)  as saldo_pendiente,
(
select  count(cod_edocuenta) from cxp_edocuenta c where
c.marca = "X" and c.documento = "FAC" and c.id_proveedor = vw_cxp.id_proveedor
) as facturas_pagadas,
(
select  count(cod_edocuenta) from cxp_edocuenta c where c.marca <> "X" and c.documento = "FAC" and c.id_proveedor = vw_cxp.id_proveedor
) as facturas_pendientes,
(select  count(cod_edocuenta) from cxp_edocuenta c where
c.documento = "FAC" and c.id_proveedor = vw_cxp.id_proveedor) as total_facturas
 FROM vw_cxp where id_proveedor =  '.$_GET['cod'].' and cod_edocuenta = '.$_GET['cod2'].' group by id_proveedor');
$smarty->assign("cabecera_estadodecuenta",$cabecera_estadodecuenta);


$dataproveedor = $proveedores->ObtenerFilasBySqlSelect("select * from proveedores where id_proveedor = ".$_GET["cod"]);
if(count($dataproveedor)==0){

    $pagina .= "<html>";
    $pagina .= "<body style=\"background-color:#f8f8f8\">";
    $pagina .= "<div  style=\"background-color:#dcdedb; border: 1px solid black;-moz-border-radius: 8px;padding:5px; margin-left: 20%;margin-right: 20%;margin-top:5%;   font-size: 13px; \">";
    $pagina .= "<img src=\"../../libs/imagenes/configuracion.png\"> <b>Disculpe esta operacion esta permitida:</b> <br>
        <span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> Verifique que el cliente al que desea facturar exista.</span><br>";
    $pagina .= "<hr><span style=\"color:#1e6602\">Para mas información contacte al administrador.</span>";
if(count($campos)>0) $pagina .= "<br><span style=\"color:red\"><img style=\"border:none;\" src=\"../../libs/imagenes/ico_list.gif\"> Detalle del error:</span><br><b style=\"padding-left:30px;\"><img src=\"../../libs/imagenes/ico_search.gif\"> Modulo:</b> ".$campos[0]["descripcion_optmenu"]." - <b>Sección:</b> ".$campos[0]["descripcion_optseccion"]." >> <b>".$campos[0]["opt_subseccion"].":</b> ".$campos[0]["descripcion"];
    $pagina .= "<hr><br><br><a style=\"text-decoration:none;\" href='?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]."'><img style=\"border:none;\" src=\"../../libs/imagenes/ico_back.gif\"> Volver</a>";
    $pagina .= "</div>";
    $pagina .= "</body>";
    $pagina .= "</html>";
    echo utf8_decode($pagina);
    exit;
}


$smarty->assign("dataproveedor",$dataproveedor);

$filas_estadodecuenta = $cxp->ObtenerFilasBySqlSelect("
   select
cod_edocuenta,
id_proveedor,
documento,
numero,
monto,
case marca when 'X' then 'Pagada' else 'Pendiente' end as estado,
observacion,
fecha_emision,
vencimiento_fecha,
vencimiento_persona_contacto,
vencimiento_telefono,
vencimiento_descripcion
 from cxp_edocuenta
 where id_proveedor  = ".$_GET["cod"]." order by numero, estado");
$smarty->assign("registros",$filas_estadodecuenta);


$datos_banco = $proveedores->ObtenerFilasBySqlSelect("select * from banco order by descripcion");
$valueSELECT="";
$outputSELECT="";
foreach($datos_banco as $key => $item){
    $valueSELECT[] = $item["cod_banco"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_banco",$valueSELECT);
$smarty->assign("option_output_banco",$outputSELECT);

$datos_instrumento_pago = $proveedores->ObtenerFilasBySqlSelect("select * from instrumentopago_formapago where cod_funcioninstrumento in ( 1,2) order by descripcion");
$valueSELECT="";
$outputSELECT="";
foreach($datos_instrumento_pago as $key => $item){
    $valueSELECT[] = $item["cod_formapago"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_instrumento_pago_tarjeta",$valueSELECT);
$smarty->assign("option_output_instrumento_pago_tarjeta",$outputSELECT);


$datos_tipodocumento = $proveedores->ObtenerFilasBySqlSelect("select * from instrumentopago_formapago  order by descripcion");
$valueSELECT="";
$outputSELECT="";
foreach($datos_tipodocumento as $key => $item){
    $valueSELECT[] = $item["cod_formapago"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_otrodocumento",$valueSELECT);
$smarty->assign("option_output_tipo_otrodocumento",$outputSELECT);

$factura = $factura->ObtenerFilasBySqlSelect("SELECT *
FROM compra
LEFT JOIN cxp_edocuenta ON cod_compra = numero
WHERE `cod_edocuenta` ='".$_GET['cod2']."'");
$smarty->assign("factura",$factura);

$impuesto = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_impuesto");
$smarty->assign("tipo_impuesto",$impuesto);
$cantidadimpuesto = $proveedores->ObtenerFilasBySqlSelect("select count(cod_tipo_impuesto) as cantidad_impuesto from tipo_impuesto");
$smarty->assign("numero_impuesto",$cantidadimpuesto);

$consulta="select li.descripcion as descripcion,li.cod_impuesto as cod_impuesto,
        li.cod_tipo_impuesto as cod_tipo_impuesto
        from lista_impuestos as li
        left join tipo_impuesto as ti on li.cod_tipo_impuesto=ti.cod_tipo_impuesto where li.cod_entidad=".$dataproveedor[0]["cod_entidad"];
$datos_impuesto = $proveedores->ObtenerFilasBySqlSelect($consulta);
$smarty->assign("dato_impuesto",$datos_impuesto);

$facs = $cxp->ObtenerFilasBySqlSelect("select * from cxp_factura where id_factura not in (select cxp_factura_fk from cxp_factura_pago) and id_cxp_edocta ='$edoCta' and cod_estatus=1");
$i=0;
while($facs[$i])
{
	$fac[$i][0]=$facs["id_factura"];
	$fac[$i][1]=$facs["cod_factura"];
	$fac[$i][2]=$facs["cod_cont_factura"];
	$fac[$i][3]=$facs["fecha_factura"];
	$i++;
}
$cod = @$_GET['cod'];
$smarty->assign("cod",$cod);
$smarty->assign("edoCta",$edoCta);
$smarty->assign("fac",$facs);
$smarty->assign("hoy",date("d/m/Y"));
?>