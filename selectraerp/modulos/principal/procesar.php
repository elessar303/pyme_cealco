<?php
//capturar datos	
	$fecha = @$_POST["fecha"];
	$fecha_hasta = @$_POST["fecha_hasta"];
 	$formato=$_POST['radio'];
	
	if ($formato==0){ header ("location:../../reportes/rpt_libroDeVentas.php?fecha=".$fecha);}
	
	if($formato==1){ header ("location:../../reportes/rpt_libroventas_pdf.php?fecha=".$fecha);}
?>
