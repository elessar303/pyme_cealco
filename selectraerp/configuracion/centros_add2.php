<?php
require_once '../lib/common.php';
include ("../header.php");

$conexion_conf=conexion_conf();
$consulta_conf="select tipo_compromiso from parametros";
$resultado_conf=query($consulta_conf,$conexion_conf);
$fila_conf=fetch_array($resultado_conf);
$tipo_compromiso=$fila_conf['tipo_compromiso'];
//$tipo_presupuesto = $fila_conf['tipo_presupuesto'];
cerrar_conexion($conexion_conf);

$conexion=conexion();
//echo $conexion;
$cod_unidad=@$_GET['codigo'];
//echo $cod_unidad;
$url="centros_list";
$modulo="Centro de costo";
$tabla="centros";
$titulos=array("Descripción");
$indices=array("2");
	$val=$_POST['cod_unidad'];
	if(isset($_POST['aceptar']))
	{
	if (($_POST['descripcion'] == '') )
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Datos imcompletos, no se puede realizar la operacion\")
		location.href=\"".$url.".php?codigo=".$val."\"";
		echo "</SCRIPT>";
		//
	}
	else
	{	
	$consulta="select * from ".$tabla;
	//echo $consulta;
	$resultado=mysql_query($consulta);
	//if((!$_POST['nombre_banco'])||(!$_POST['numero_cuenta'])||(!$_POST['firstinput'])){
	//	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	//	alert(\"Datos imcompletos, no se puede realizar la operacion\")";
	//	echo "<//SCRIPT>";
	//	
	//}
/*
	$conSector = "SELECT * FROM cwsector WHERE RecNo= '".$_POST['sel_sector']."'";
	$resSector = query($conSector, $conexion);
	$filaSector = fetch_array($resSector);
	$_POST['sel_sector'] = $filaSector['Sec'];
			
	$conPrograma = "SELECT * FROM cwprogra WHERE RecNo='".$_POST['sel_programa']."'";
	$resPrograma = query($conPrograma, $conexion);
	$filaPrograma = fetch_array($resPrograma);
	$_POST['sel_programa'] = $filaPrograma['Programa'];
			
	$conActividad = "SELECT * FROM cwpreact WHERE RecNo='".$_POST['sel_actividad']."'";
	$resActividad = query($conActividad, $conexion);
	$filaActividad = fetch_array($resActividad);
	$_POST['sel_actividad'] = $filaActividad['Obr'];

*/
	$indices=array("0","1","2","3","4","5");
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
	
	$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.")";
	$resultado=query($consulta,$conexion);
	//echo $consulta;
	//exit(0);
	cerrar_conexion($conexion);

	
	
	echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	location.href=\"".$url.".php?codigo=".$val."\"
	</SCRIPT>";
	}

}
else
{

	$consulta="select * from ".$tabla;
	$resultado=query($consulta,$conexion);
	//echo $consulta;
	//echo $conexion;
}
?>

<SCRIPT language="JavaScript" type="text/javascript">
function cerrarlocal(retorno){
	location.href="centros_list.php?codigo="+retorno
}
</SCRIPT>
<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<TABLE  width="100%" height="100">
<TBODY>
<?
$consulta1="select descripcion from unidades where cod_unidad=".$cod_unidad;
$resultado_unidad=query($consulta1,$conexion);
$fila_unidad=fetch_array($resultado_unidad);
$descripcion_unidad=$fila_unidad['descripcion'];

$consulta="select max(cod_centro) as valor from centros where cod_unidad=".$cod_unidad;
//echo $consulta;
$resultado_centro=query($consulta,$conexion);
$fila_centro=fetch_array($resultado_centro);
$max_centro=$fila_centro['valor'];
//echo "Centro maximo".$max_centro;
if($max_centro==0){
$valor=$cod_unidad+0.1;
}else{
$valor=$max_centro+0.1;
}

$consulta1 = "SELECT * FROM cwsector where RecNo <> 99 ORDER BY RecNo";
$resultado1 = query($consulta1, $conexion);
	

?>
<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Agregar Nuevo Registro de <?echo $modulo?></strong></td>
    </tr>
			<TR><td class=tb-head colspan="2" align="center" width="180">COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE</td></tr>

	<td class="tb-head">Descripcion Unidad</td>
      	<td colspan="6"><?echo "$descripcion_unidad";?> <INPUT type="hidden" name="cod_unidad" value="<?echo "$cod_unidad";?>"></td>
	<tr><td class="tb-head">C&oacute;digo Centro</td>
      	<td colspan="6"><?=$valor?> <INPUT type="hidden" name="cod_centro" value="<?echo "$valor";?>"></td></tr>
	<?
//	if ($tipo_presupuesto == 'Programa')
//	{
	$i=0;
	$cont=0;
	foreach($titulos as $nombre)
	{
		$campo=mysql_field_name($resultado,$indices[$i]);
		echo "<TR>";?><td class=tb-head ><? echo "$nombre&nbsp;**</td>";
		echo "<td colspan=\"3\"><INPUT type=\"text\" name=\"$campo\" size=\"100\"></td> </tr>";
		$i++;
		$cont++;
		if($cont==1)
		{/*
		echo "<TR>";
		echo "	<TD width=\"150\" class=\"tb-head\">Sector:</TD>
			<TD>  <SELECT name='sel_sector' id='sel_sector' onchange=\"javascript:cargar_programa()\">
					<OPTION value=\"0\" class=\"tb-fila\">Seleccione un sector</OPTION>";
					while($fila1 = fetch_array($resultado1)) 
					{
						$codSector = $fila1['RecNo'];
						$desSector = $fila1['Denominacion'];
						echo "<option value=\"$codSector\">$desSector</option>";
					} 
		echo "</SELECT></td> </tr>";
		echo "<TR><TD width=\"150\" class=\"tb-head\">Programa:</TD>";
		echo "<TD>  <SELECT name='sel_programa' id='sel_programa'>
						<OPTION class=\"tb-fila\" value=\"0\">Seleccione un programa</OPTION>
					</SELECT>  </TD>
		</TR>
		<TR>
			<TD width=\"150\" class=\"tb-head\">Actividad:</TD>
			<TD>  <SELECT name='sel_actividad' id='sel_actividad'>
					<OPTION class=\"tb-fila\" value=\"0\">Seleccione una actividad</OPTION>
					</SELECT>  </TD>
		</TR>"; */
		

		}
	}
?>
    <tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Aceptar">&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onclick="javascript:history.go(-1);"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);

?>
