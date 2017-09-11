<?php
require_once("../../general.config.inc.php");
require_once("../libs/php/adodb5/adodb.inc.php");
require_once("../libs/php/configuracion/config.php");

include("../libs/php/clases/ConexionComun.php");
include("../libs/php/clases/cxp.php");
require_once '../lib/common.php';
include ("../header.php");


$conexion=conexion();


$facturas=query("SELECT * from cxc_edocuenta",$conexion);

while($fila_fac=fetch_array($facturas)){
	$monto=($fila_fac["monto"]*1.01538461538462);
	$monto=round($monto,2);
	$consulta="update cxp_factura_medico set monto='".$monto."' where cxp_factura_medico_pk='".$fila_iva["cxp_factura_medico_pk"]."'";
	$update=query($consulta,$conexion);
	
	
}




?>

