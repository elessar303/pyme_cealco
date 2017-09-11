<?php
session_start();
ob_start();
$url="requisiciones_compras_add";
$modulo="Requisiciones de Compras";
$tabla="requisiciones";
$titulos=array("","Número","Fecha","Concepto");
$indices=array("0","9","8");


require_once '../lib/pdfcommon.php';
include('../header.php');
require_once '../lib/config.php';
require_once '../lib/common.php';

$nombreBanco=@$_GET["nombre_banco"];
$codigo=@$_GET["banco"];
//$cuenta=@$_GET["cuenta"];
$fDesde = fecha_sql($_GET['fechaDesde']);
$fHasta = fecha_sql($_GET['fechaHasta']);

$conexion=conexion();

$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$sele=$_GET['sele'];

function validar_seleccion($registro){
	$valida=false;
	for ($i=0;$i<$registro;$i++){
		if(isset($_POST['total'.$i])){
			$valida=true;
		}
	}
	return $valida;


}


if($_POST['guardar']=='Guardar'){
	$consulta="select * from ".$tabla." where req_compra<>'P' and req_compra='' and tipo=1 and situacion='Revisar'";
	$query=query($consulta,$conexion);
	$registro=num_rows($query);
	$valida=validar_seleccion($registro);
	if($valida==true){
		$consulta="select max(cod_requisicion) as valor from requisiciones";
		$resultado_req=query($consulta,$conexion);
		$fila_req=fetch_array($resultado_req);
		$max_req=$fila_req['valor'];
		$val=$max_req+1;

		

		for ($i=0;$i<$registro;$i++){
			if(isset($_POST['total'.$i])){
				$cod=$_POST['total'.$i];
				list($fecha,$codigo)=explode("/",$cod);
				$update="update requisiciones set situacion='Procesado',fecha='$fecha',req_compra=$val where  cod_requisicion=$codigo";
				$query_up=query($update,$conexion);
			}
		}
		$consulta_materiales="select D.cod_item,D.descripcion,sum(D.cantidad) as cantidad,D.medida from requisiciones_det as D inner join requisiciones as R on R.req_compra=$val and R.cod_requisicion=D.cod_requisicion group by cod_item";
		
		$query_materiales=query($consulta_materiales,$conexion);
		
		
		$insert="insert into requisiciones values($val,'".date('Y-m-d')."','".date('H:m:s')."','".$_SESSION['nombre']."','','Registrada',$_POST[unidad],$_POST[centro],'COMPRAS POR VARIAS REQUISICIONES','".date('Y-m-d')."',1,'p')";
		$query_insert=query($insert,$conexion);
		$cont=1;
		while ($fila_material=fetch_array($query_materiales)){
			$insert="insert into requisiciones_det values('".$cont."','".$val."','".$fila_material['cod_item']."','".$fila_material['descripcion']."','".$fila_material['cantidad']."','".$_POST['unidad']."','".$_POST['centro']."','".$fila_material['medida']."')";
			$query_insert=query($insert,$conexion);

		}
	
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
			alert('Se realizo la Requisicion!!!')
			location.href='requisiciones_edit.php?id=$val&codigo=$_POST[unidad]&cod_centro=$_POST[centro]'
		</SCRIPT>";
	}else{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
			alert('No puede realizar la requisicion sin seleccionar alguna requisicion!!!')
		</SCRIPT>";
	}

}



if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$nombreBanco=@$_POST["nombre_banco"];
		$cuenta=@$_POST["cuenta"];
		$codigo=@$_POST["banco"];
	}
	switch($tipob){
		case "exacta":
			$consulta=buscar_exacta($tabla,$des,"concepto");
			break;
		case "todas":
			$consulta=buscar_todas($tabla,$des,"concepto");
			break;
		case "cualquiera":
			$consulta=buscar_cualquiera($tabla,$des,"concepto");
			break;
	}
	//$consulta="select * from ".$tabla." where codigo='".$codigo."'";
}else{
	$consulta="select * from ".$tabla." where req_compra<>'P' and req_compra='' and tipo=1 and situacion='Revisar'";

	list($anio,$mes,$dia)=explode("-",$fDesde);

	
	

}
//echo $consulta." este es el valor que muestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=query($consulta,$conexion);
?>
<script>
function marcartodos(check_global,reg){
	var cadena="marcar_todos"
	var cadena_temp=document.getElementById(cadena)
	valor_total1 = cadena_temp
	
	if(valor_total1.checked==true){
	
		for(valor=0; valor< reg; valor++){
			var cadena="total"+valor
			var cadena_temp=document.getElementById(cadena)
			valor_total = cadena_temp
			valor_total.checked =true

			
		//capturamo  saldo conciliacion
				
		}

	
		
	}else{
		for(valor=0; valor< reg; valor++){
			var cadena="total"+valor
			var cadena_temp=document.getElementById(cadena)
			valor_total = cadena_temp
			valor_total.checked =false;
			
		}
		

	}	
	
}
function guardar2(url){
	var guardar=document.getElementById("guardar")
	guardar.value='Guardar'
	document.requisiciones_compras_add.submit();

}

</script>

<FORM name="requisiciones_compras_add" action="<?php echo $_SERVER['PHP_SELF'].'?banco='.$codigo.'&fechaDesde='.$_GET['fechaDesde'].'&fechaHasta='.$_GET['fechaHasta']; ?>" method="POST" target="_self">
<?
	titulo_mejorada("Requisiciones Compras","156","btn('grabarnormal','guardar2($url)',2);","requisiciones_compras.php");
?>

<input type="hidden" id='guardar' name="guardar">

<BR>
<TABLE  width="100%" height="100" border="0">
	<tr>	<td class="tb-head" ><strong>Unidad Administrativa:</strong></td>
      		<td colspan="2" class="tb-head">
			<select id="unidad" name="unidad" >
				<?	$consulta="select * from unidades";
					$conexion=conexion();
					$query=query($consulta,$conexion);
					while($fila_uni=fetch_array($query)){
					?>
						<option  onclick="cargar_unidad(this.value)" value="<? echo $fila_uni['cod_unidad']?>"><strong><?echo "$fila_uni[cod_unidad] -"."$fila_uni[descripcion]";?></strong></option>
					<?}
					?>
	
			</select>
		</td>
	</tr>
	<tr>
		<td class="tb-head"><strong>Centro de Costo:</strong></td>
      		<td colspan="2" class="tb-head">
			<select id="centro" name="centro">
				<?	$consulta="select * from centros order by cod_centro";
					$conexion=conexion();
					$query=query($consulta,$conexion);
					$fila_centro=fetch_array($query);
					?>
						<option value="<? echo $fila_centro['cod_centro']?>"><strong><?echo "$fila_centro[cod_centro] -"."$fila_centro[descripcion]";?></strong></option>
					<?
					?>-->
	
			</select>
		</td>
	</tr>
	
</TABLE>



<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
  <tbody>
	<?php $to=num_rows($resultado);?>
    <tr class="tb-head" >
	<td align="center"><strong>Marcar Todos</strong> <INPUT type="checkbox" id="marcar_todos" name="marcar_todos"  title="Marcar/Desmarcar Todos" onclick="marcartodos('marcar_todos',<?php echo $to; ?>)"></td>
	<td align="center"><STRONG>Numero</STRONG></td>
	<td align="center"><STRONG>Fecha</STRONG></td>
	<td align="center"><STRONG>Concepto</STRONG></td>
	<td></td>
    </tr>
<?
	if($num_paginas!=0){
	$i=0;
	
	while($fila=mysql_fetch_array($resultado)){
	if($i%2==0){
?>
    		<tr class="tb-fila">
<?
	}else{
		?><tr><?
	}
		$codigo=$fila["cod_requisicion"];
	
	 	$fecha=$fila["fecha"];
	
		
	
		$concepto=$fila["concepto"];
	
	
	?>
	<td align="center"><input  name="total<?php echo $i;?>" type="checkbox" id="total<?php echo $i;?>" value="<?echo $fecha.'/'.$codigo;?>"  > <input type="hidden" name="oculto<?php echo $i;?>"  id="oculto<?php echo $i;?>" value="<?echo $fecha.'/'.$codigo;?>"></td>
	<td align="center"><?echo $codigo;?></td>
	<td align="center"><?echo fecha($fecha);?></td>
	<td align="left"><?echo $concepto;?></td>
	<?iconoNuevopdf("requisicionespdf.php?id=".$codigo."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");?>
	
	</tr>
	<?
	$i++;
	

    ?></tr><?
	}
	
}else{
	echo "<tr><td>No existen registro con la busqueda especificada</td></tr>";
}
	cerrar_conexion($conexion);
?>

  </tbody>
</table>
<?
	//pie_pagina($url,$pagina,"cuenta=$cuenta&nombre=$nombreBanco&codigo=$codigo&tipo=".$tipob."&des=".$des,$num_paginas);
?>
</FORM>
</BODY>
</html>
