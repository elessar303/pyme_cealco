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
$cod_centro=@$_GET['cod_centro'];
//echo $cod_unidad;
$url="centros_asociar_presupuesto";
$modulo="Centro de costo";
$tabla="centrosypresupuesto";
$titulos=array("Unidad Administrativa","Centro de Costo","Proyecto o Accion Centralizada");
$indices=array("cod_unidad","cod_centro","sel_programa");

$val=$_POST['cod_unidad'];

if(isset($_POST['aceptar']))
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

	$indices=array("0","1","2","3","4");
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
	$val=$_POST['cod_unidad'];
	$val2=$_POST['cod_centro'];
	$consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.")";
	$resultado=query($consulta,$conexion);

	//echo $consulta;
	//exit(0);
	cerrar_conexion($conexion);

	
	
	echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?codigo=".$val."&cod_centro=".$val2."\"
	</SCRIPT>";
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
	parent.cont.location.href="centros_asociar_presupuesto.php?codigo="+retorno
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

$consulta="select descripcion from centros where cod_unidad=".$cod_unidad." and cod_centro=".$cod_centro;
//echo $consulta;
$resultado_centro=query($consulta,$conexion);
$fila_centro=fetch_array($resultado_centro);
$descripcion_centro=$fila_centro['descripcion'];
/*
//echo "Centro maximo".$max_centro;
if($max_centro==0){
$valor=$cod_unidad+0.1;
}else{
$valor=$max_centro+0.1;
}*/

$consulta1 = "SELECT * FROM cwprogra where RecNoSec=99 ORDER BY Programa";
$resultado1 = query($consulta1, $conexion);


$consulta2 = "SELECT * FROM cwsector ORDER BY RecNo";
$resultado2 = query($consulta2, $conexion);
	

?>
<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Agregar Nuevo Registro de <?echo $modulo?></strong></td>
    </tr>
	<TR><td class=tb-head colspan="2" align="center" width="180">COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE</td></tr>

	<?
//	if ($tipo_presupuesto == 'Programa')
//	{
	$i=0;
	$cont=0;
	foreach($titulos as $nombre)
	{
		$campo=$indices[$i];
		echo "<TR>";?><td class=tb-head ><? echo "$nombre&nbsp;**</td>";
		switch ($campo)
		{
			case 'cod_unidad':
				$consulta="select descripcion from unidades where cod_unidad=".$cod_unidad;
				$resul=query($consulta,$conexion);
				$fila_x=fetch_array($resul);
				?>
				<input size="10" type="hidden" name="cod_unidad" id="cod_unidad" value="<? echo $cod_unidad;?>">      
				<td><INPUT type="text" value="<? echo $fila_x["descripcion"];?>" size="100"></td>
				<?
			break;
			case 'cod_centro':
				$consulta="select descripcion from centros where cod_unidad=".$cod_unidad." and cod_centro='".$cod_centro."'";
				$resul=query($consulta,$conexion);
				$fila_x=fetch_array($resul);
				?>
				<input size="10" type="hidden" name="cod_centro" id="cod_centro" value="<? echo $cod_centro;?>" >
				<td><INPUT type="text" value="<? echo $fila_x["descripcion"];?>" size="100"></td>
				<?
			break;
			case 'sel_programa':
				?>
				<input type="hidden" name="sel_sector" value="99">
				
				<TD>  
				<SELECT name="sel_programa" id="sel_programa" onchange="javascript:cargar_actividad_2()">
				<OPTION value="0" class="tb-fila">Seleccione un Proyecto o Acción Centralizada</OPTION>
				<? 
				while($fila1 = fetch_array($resultado1)) 
				{
					$codPrograma = $fila1['RecNo'];
					$desPrograma = $fila1['Denominacion'];
					echo "<option value=\"$codPrograma\">$desPrograma</option>";
				}
				?>
				</SELECT>
				</TD>
				</TR>
				<TR>
				<TD width="150" class="tb-head">Acción Especifica</TD>
				<TD>  
				<SELECT name="sel_actividad" id="sel_actividad">
				<OPTION class="tb-fila" value="0">Seleccione una Acción Especifica</OPTION>
				</SELECT>  
				</TD>
				</TR>
				<?
		}$i++;echo "</tr>";
	}
?>
    <tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Aceptar">&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onclick="javascript:cerrarlocal('<?echo $cod_unidad?>&cod_centro=<?echo $cod_centro?>');"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);

?>