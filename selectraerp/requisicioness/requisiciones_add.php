<?
if (!isset($_SESSION)) 
{
  session_start();
}
?>
<?php

include ("../header.php");
require_once '../lib/common.php';

$url="requisiciones";
$url2="requisiciones_add";
$modulo="Requisiciones";
$tabla="requisiciones";
$titulos=array("Concepto:","Observaciones:");
$indices=array("8","4");

$conexion=conexion();
$cod_unidad=@$_GET['codigo'];
$cod_centro=@$_GET['cod_centro'];
$paginam=@$_GET['paginam'];


if(isset($_POST['guardar'])){
	
	
	$consulta="select * from ".$tabla;
	$resultado=mysql_query($consulta);
	//if((!$_POST['nombre_banco'])||(!$_POST['numero_cuenta'])||(!$_POST['firstinput'])){
	//	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	//	alert(\"Datos imcompletos, no se puede realizar la operacion\")";
	//	echo "<//SCRIPT>";
	//	
	//}
	$indices=array("6","7","10","8","4");
	foreach($indices as $valor){
		$campo=mysql_field_name($resultado,$valor);
		if($cadena_campos=="" && $cadena_valores==""){
		
			$cadena_campos=$cadena_campos.$campo;
			$cadena_valores=$cadena_valores."'".$_POST[$campo]."'";
		}
		else{
			$cadena_campos=$cadena_campos.",".$campo;
			$cadena_valores=$cadena_valores.",'".$_POST[$campo]."'";
		}
	}
	//echo $cadena_campos." ";
	//echo $cadena_valores;
	
$consulta="select max(cod_requisicion) as valor from requisiciones";
$resultado_req=query($consulta,$conexion);

$fila_req=fetch_array($resultado_req);
$max_req=$fila_req['valor'];
$cod_requisicion=$max_req+1;



	$log_usr=$_SESSION['nombre'];
	$fecha_agregada=date('Y/m/d');
	$hora_agregada=date('h:i');
	$cadena_campos=$cadena_campos.",fecha";
	$cadena_valores=$cadena_valores.",'".fecha_sql($_POST['fecha'])."'";
	$cadena_campos=$cadena_campos.",situacion";
	$cadena_valores=$cadena_valores.",'Registrada'";
	$cadena_campos=$cadena_campos.",estacion";
	$cadena_valores=$cadena_valores.",'".$log_usr."'";
	$cadena_campos=$cadena_campos.",agregada_fecha";
	$cadena_valores=$cadena_valores.",'".$fecha_agregada."'";
	$cadena_campos=$cadena_campos.",agregada_hora";
	$cadena_valores=$cadena_valores.",'".$hora_agregada."'";
	
	$cadena_campos=$cadena_campos.",cod_requisicion";
	$cadena_valores=$cadena_valores.",'".$cod_requisicion."'";

	
	$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.")";
	$resultado=query($consulta,$conexion);
	//echo $consulta;
	//exit(0);
	$val=$_POST['unidad'];
	$val2=$_POST['cod_centro'];
	$id=$cod_requisicion;
	


	$selectra=new bd("selectra");
	$consulta="select * from requisiciones_det where cod_requisicion='".$id."'";
	$resultado=$selectra->query($consulta);
	

	if (isset($_POST['cod_material']))
	{
	//if (isset($_POST['guardar']))
		//{
		//$i=$_POST['inicio'];
		$i=$resultado->num_rows+1;
		
		$desc_arreglo;
		$contador=0;
		foreach ($_POST['descrip'] as $desc)
		{
		$desc_arreglo[$contador]=$desc;
		$contador++;
		//echo $desc_arreglo[$contador-1]."<br />";
		}
		$contador=0;
		foreach ($_POST['cantidad'] as $cant)
		{
		$cant_arreglo[$contador]=$cant;
		$contador++;
		//echo $desc_arreglo[$contador-1]."<br />";
		}
		$contador=0;
		foreach ($_POST['medida'] as $cant)
		{
		$uni_arreglo[$contador]=$cant;
		$contador++;
		//echo $desc_arreglo[$contador-1]."<br />";
		}
		//exit(0);
		$contador=0;
		foreach ($_POST['cod_material'] as $valor)
		{
			//$cadena_des="descripcion".$i;
			//echo "Cadena_des: ".$cadena_des."<br>";
			//$cadena_cant="cantidad".$i;
	//		echo "Cadena_cant: ".$cadena_cant."<br>";
			if($cant_arreglo[$contador]!=""){
				$consulta="insert into requisiciones_det values('".$i."','".$id."','".$valor."','".$desc_arreglo[$contador]."','".$cant_arreglo[$contador]."','".$val."','".$val2."','".$uni_arreglo[$contador]."')";
				//echo "<br />".$consulta;
				query($consulta,$conexion);
			}	
			$i++;
			$contador++;
			$cadena="cod_material".$i;	
		}
		//header("Location: requisiciones.php?id=$id&codigo=$cod_unidad&cod_centro=$cod_centro");
		//}
	}

	//$paginam=$_POST['paginam'];
	cerrar_conexion($conexion);
	echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?codigo=".$val."&cod_centro=".$val2."\"
	</SCRIPT>";
	

}else{

	$consulta="select * from ".$tabla;
	//echo $consulta;
	$resultado=query($consulta,$conexion);
}
?>
<SCRIPT language="JavaScript" type="text/javascript">

function cerrarloc(retorno){
	parent.cont.location.href=retorno+".php?codigo=<?=$cod_unidad?>&cod_centro=<?=$cod_centro?>"
}
</SCRIPT>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?
	titulo("Agregar nuevo registro de ".$modulo,"","requisiciones.php?codigo=".$cod_unidad."&cod_centro=".$cod_centro,"12");
?>

<TABLE  width="100%" height="100" border="0">
<TBODY>
<?
$consulta="select max(cod_requisicion) as valor from requisiciones";
$resultado_req=query($consulta,$conexion);

$fila_req=fetch_array($resultado_req);
$max_req=$fila_req['valor'];
$val=$max_req+1;

$consulta_u="select descripcion from unidades where cod_unidad=".$cod_unidad;
$resultado_u=query($consulta_u,$conexion);
$fila_u=fetch_array($resultado_u);
$des_unidad=$fila_u['descripcion'];
$descripcion_u=$des_unidad;

$consulta_c="select descripcion from centros where cod_centro=".$cod_centro;
$resultado_c=query($consulta_c,$conexion);
$fila_c=fetch_array($resultado_c);
$des_centro=$fila_c['descripcion'];
$descripcion_c=$des_centro;

$consulta_t="select * from ordenes_tipos";
$resultado_tipo=query($consulta_t,$conexion);

?>
	
	<tr><td class="tb-head" width="200"><strong>Unidad Administrativa:</strong></td>
      	<td colspan="6" class="tb-head"><strong><?echo "$cod_unidad -"."$descripcion_u";?></strong> <INPUT type="hidden" name="unidad" value="<?echo "$cod_unidad";?>"></td>
	</tr>
	<tr>
	<td class="tb-head"><strong>Centro de Costo:</strong></td>
      	<td colspan="6" class="tb-head"><strong><?echo "$cod_centro -"." $descripcion_c";?></strong> <INPUT type="hidden" name="cod_centro" value="<?echo "$cod_centro";?>"></td>
	</tr>
	<tr>
	<td class="tb-head">N&uacute;mero:</td>
      	<td colspan="6"><INPUT type="text" size="15" name="cod_requisicion" value="<?echo "$val";?>"></td>
	</tr>

	<?
	$i=0;
	$cont=0;
	foreach($titulos as $nombre){
		if($cont==0){
		echo "<TR>";?><TD class="tb-head"><?echo "Fecha Requisición:</TD>";
		echo "<TD width=30><INPUT type=\"text\" name=\"fecha\" id=\"fecha\" size=\"15\" maxlength=\"12\" value=\"".date("d/m/Y")."\"></td><td>&nbsp;<input name=\"d_fecha\" type=\"image\" id=\"d_fecha\" src=\"../lib/jscalendar/cal.gif\">";
  		?>
		<script type="text/javascript">Calendar.setup({inputField:"fecha",ifFormat:"%d/%m/%Y",button:"d_fecha"});</script></TD>
		</TR><?
		}

		if($cont==0){
		echo "<TR>";?><td class=tb-head ><? echo "Tipo:</td>";
		echo "<td colspan=\"3\"><SELECT name=\"tipo\" id=\"tipo\">";
		while($fila_tipo=fetch_array($resultado_tipo)){
				$cod_tipo=$fila_tipo['cod_orden_tipo'];
				$descripcion_tipo=$fila_tipo['descripcion'];
				echo "<option value=\"$cod_tipo\">$descripcion_tipo</option>";
			}
		echo "</SELECT></td> </tr>";
		}
		
		$campo=mysql_field_name($resultado,$indices[$i]);
		echo "<TR>";?><td class=tb-head ><? echo "$nombre</td>";
		if($campo=="concepto")
		{
			//echo "Ca: ".$campo;
			echo "<td colspan=\"3\"><textarea name=\"".$campo."\" style=\"width:61%\"></textarea></td> </tr>";
		}else
		{
			echo "<td colspan=\"3\"><INPUT type=\"text\" name=\"$campo\" size=\"100\"></td> </tr>";
		}
		$i++;
		$cont++;
	}
?>
	<input type="hidden" name="guardar" value="guardar" />
	<table width="100%"><tr class="tb-tit"><td>Agregar Materiales a la Requisici&oacute;n</td><td align="right" width="30"><? btn("add","window.open('materiales_search.php','Listado de Materiales','width=720, height=550')",2)?></td><td id="grabar" align="right" width="30"><? btn("grabar","document.sampleform.submit()",2)?></td></tr></table>
	<br>
	<input type="hidden" id="contador" name="contador" value="<? echo ($fila['maximo']);?>">
	<input type="hidden" id="inicio" name="inicio" value="<? echo ($fila['maximo']+1);?>">
	<table width="100%" border="0" cellpadding="2" cellspacing="1" class="menu-bg" id="tabla_materiales" name="prueba de tabla">
	<tr class="tb-head">
		<td width="100"><strong>C&oacute;digo</strong></td>
		<td><strong>Descripci&oacute;n</strong></td>
		<td width="60"><strong>Cantidad</strong></td>
		<td width="16">&nbsp;</td>
		<td width="16">&nbsp;</td>
	</tr>
	
 </tbody>
</table>
<div id="xxx"></div>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);
?>