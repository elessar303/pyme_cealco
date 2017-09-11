<?php
//capturar datos	
	$fecha=$_POST['fecha'];
 	$formato=$_POST['radio'];
	
	if ($formato==0){header ("location:../../reportes/rpt_libroDeCompras.php?fecha=".$fecha);}
	
	if($formato==1){header ("location:../../reportes/rpt_librocompras_pdf.php?fecha=".$fecha);}
?>