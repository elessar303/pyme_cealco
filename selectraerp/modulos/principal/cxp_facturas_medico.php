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

if((isset($_POST["montoPago"]))||($_POST["montoPago"])!='')
{
	$nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 1, "si");
	$cxp->BeginTrans();
	$SQL_cxp = "INSERT INTO cxp_edocuenta (
	`cod_edocuenta` ,
	`id_proveedor` ,
	`documento` ,
	`numero` ,
	`monto` ,
	`fecha_emision` ,
	`observacion` ,
	`vencimiento_fecha` ,
	`vencimiento_persona_contacto` ,
	`vencimiento_telefono` ,
	`vencimiento_descripcion` ,
	`usuario_creacion` ,
	`fecha_creacion`,
	`marca`
	)
	VALUES (
	NULL ,
	'".$_POST["id_proveedor"]."',
	'FACxMED',
	'$nro_compra',
	'".$_POST["montoPago"]."',
	'".date("Y-m-d")."',
	'Pago a medicos',
	'',
	'' ,
	'' ,
	'' ,
	'".$login->getUsuario()."',
	CURRENT_TIMESTAMP,
	'".$marca."'
	);";
	
	$cxp->ExecuteTrans($SQL_cxp);
	$id_cxp = $cxp->getInsertID();
	
	$SQL_medicos="UPDATE cxp_factura_medico set cxp_edocta_fk=$id_cxp where cxp_factura_medico_pk in ($_POST[idFacturas]);";
	$cxp->ExecuteTrans($SQL_medicos);

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
	`fecha_emision_edodet`
	)
	VALUES (
	NULL ,
	'".$id_cxp."',
	'PAGxMED',
	'".$nro_compra.'R'."',
	'FACTURAS DE MEDICO',
	'c', 
	'".$_POST["montoPago"]."',
	'".$login->getUsuario()."',
	CURRENT_TIMESTAMP,
	'".date("Y-m-d")."'
	);";
	/**
	* Se inserta el detalle de la cxp en este caso el asiento del DEBITO.
	*/


	$cxp->ExecuteTrans($SQL_cxp_DET);


	if($cxp->errorTransaccion==1)
	{
		Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Cuenta por pagar a medico fue generado exitosamente</b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total: ".number_format($_POST["montoPago"], 2, ",", ".") ." </b><br></span>");
	}
	if($cxp->errorTransaccion==0)
	{
		Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la cuenta por pagar.</span>");
	}
	$cxp->ExecuteTrans("update correlativos set contador = '".$nro_compra."' where campo = 'cod_compra'");
	$cxp->CommitTrans($cxp->errorTransaccion);

	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);

} // si el usuario iso post


if(!isset($_GET["cod"]))
{
	header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
	exit;
}

$dataproveedor = $proveedores->ObtenerFilasBySqlSelect("select * from proveedores where id_proveedor = ".$_GET["cod"]);
$smarty->assign("dataproveedor",$dataproveedor);
$cod=$_GET['cod'];
$mostrar=$dataproveedor[0]["mostrar"];

if($mostrar==0){
	$facs = $cxp->ObtenerFilasBySqlSelect("select * from cxp_factura_medico where medico_fk=$cod and estatus=0 and cxp_edocta_fk=0");
}else{
	$facs = $cxp->ObtenerFilasBySqlSelect("select * from cxp_factura_medico where medico_fk=$cod and cxp_edocta_fk=0");
}
$smarty->assign("cod",$cod);
$smarty->assign("facs",$facs);
$smarty->assign("hoy",date("d/m/Y"));
?>
