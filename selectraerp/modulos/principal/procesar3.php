<?php
//capturar datos	
	$fecha=$_POST['fecha'];
	$fecha2=$_POST['fecha_hasta'];
 	$formato=$_POST['radio'];
        $cod_tipo_cliente=$_POST['cod_tipo_cliente'];	

	if ($formato==0){header ("location:../../reportes/rpt_libroDeCompras.php?fecha=".$fecha."&fecha2=".$fecha2);}
	
	if($formato==1){header ("location:../../reportes/rpt_cxc_estado_cuenta.php?fecha=".$fecha."&fecha2=".$fecha2."&cod_tipo_cliente=".$cod_tipo_cliente);}
?>
