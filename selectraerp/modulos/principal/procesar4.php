<?php
//capturar datos	
	$fecha=$_POST['fecha'];
	$fecha2=$_POST['fecha_hasta'];
 	$formato=$_POST['radio'];
	
	if ($formato==0){header ("location:../../reportes/rpt_libroDeCompras.php?fecha=".$fecha."&fecha2=".$fecha2);}
	
	if($formato==1){header ("location:../../reportes/rpt_relacion_cxc.php?fecha=".$fecha."&fecha2=".$fecha2);}
?>
