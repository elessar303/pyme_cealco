<?php
	//capturar datos	
	$fecha = @$_POST["fecha"];
	$fecha_hasta = @$_POST["fecha_hasta"];
 	$formato=$_POST['radio'];
	
	if ($formato==0){ header ("location:../../reportes/rpt_compras_efectuadasXls.php?fecha=".$fecha.'&fecha_hasta='.$fecha_hasta);}
	
	if($formato==1){ header ("location:../../reportes/rpt_compras_efectuadas.php?fecha=".$fecha.'&fecha_hasta='.$fecha_hasta);}
?>
<!-- <script type="text/javascript">

function valida_envia(){

	
	var fecha_val=document.formulario.fecha.value;
	var fecha_val2=document.formulario.fecha2.value;
	if (document.formulario.radio[1].checked){
			window.open('../../reportes/rpt_chequesEmitidos.php?fecha='+fecha_val+'&fecha2='+fecha_val2);
	}else{
			window.open('../../reportes/rpt_chequesEmitidosXls.php?fecha='+fecha_val+'&fecha2='+fecha_val2);
	}
    document.formulario.submit();
}
</script> -->
