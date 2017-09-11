<?php
	//capturar datos	
	$fecha = @$_POST["fecha"];
	$fecha_hasta = @$_POST["fecha_hasta"];
 	$formato=$_POST['radio'];
	
	if ($formato==0){ header ("location:../../reportes/rpt_libroDeVentas.php?fecha=".$fecha);}
	
	if($formato==1){ header ("location:../../reportes/listado_medicos_por_pagar.php?fecha=".$fecha.'&fecha_hasta='.$fecha_hasta);}
?>
