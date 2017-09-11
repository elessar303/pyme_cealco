<?php
	//capturar datos	
	$codigo = @$_POST["cod_tipo_cliente"];
	//$fecha_hasta = @$_POST["fecha_hasta"];
 	$formato=$_POST['radio'];
	
	if ($formato==0){ header ("location:../../reportes/rpt_libroDeVentas.php?fecha=".$fecha);}
	
	if($formato==1){ header ("location:../../reportes/listado_de_clientes.php?cod_tipo_cliente=".$codigo);}
?>
