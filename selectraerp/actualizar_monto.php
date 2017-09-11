<?php
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/cxp.php");

include("../../../menu_sistemas/lib/common.php");
$cxp=new cxp();

$facturas_medicos=$cxp->ObtenerFilasBySqlSelect("SELECT * from cxp_factura_medico where medico_fk=1915 and fecha_fac>='2011-07-01' ");

foreach($facturas_medicos as $key => $item){

	$monto=($item["monto"]*50)/80;
	$update="update cxp_factura_medico set monto='".$monto."' where cxp_factura_medico_pk='".$item["cxp_factura_medico_pk"]."'";
	$cxp->ExecuteTrans($update);
	
}



?>
