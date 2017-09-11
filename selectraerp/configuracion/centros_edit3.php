<?php
require_once '../lib/common.php';
include ("../header.php");

$conexion_conf=conexion_conf();
$consulta_conf="select tipo_compromiso from parametros";
$resultado_conf=query($consulta_conf,$conexion_conf);
$fila_conf=fetch_array($resultado_conf);
$tipo_compromiso=$fila_conf['tipo_compromiso'];
cerrar_conexion($conexion_conf);

$conexion=conexion();
//echo $conexion;
//$codigo = @$_GET['codigo'];
$url="centros_list";
$modulo="Centro de Costo";
$tabla="centros";
$titulos=array("Descripción");
$indices=array("2","3","4","5");
$val=$_POST['cod_unidad'];
if(isset($_POST['aceptar']))
{
	
	if (($_POST['descripcion'] == '') )
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Datos imcompletos, no se puede realizar la operacion\")
		parent.cont.location.href=\"".$url.".php?codigo=".$val."\"";
		echo "</SCRIPT>";
		//
	}
	else
	{	
	$consulta="select * from ".$tabla;
	$resultado= query($consulta,$conexion);

	
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
	
	
	$cadena="";
	foreach($indices as $valor){
		$campo=mysql_field_name($resultado,$valor);
		if($cadena==""){
			
			$cadena=$cadena.$campo."='".$_POST[$campo]."'";
		}
		else{
			$cadena=$cadena.",".$campo."='".$_POST[$campo]."'";
		}
	}
	$cadena = $cadena.",sel_sector=99";
	$codigo = @$_GET['codigo'];
	$cod_centro = @$_GET['cod_centro'];
	//$consulta="update ".$tabla." set ".$cadena." where cod_centro=".$_POST["codigo"];
	$consulta="update ".$tabla." set ".$cadena." where cod_centro='".$_POST["cod_centro"]."'and cod_unidad='".$_POST["cod_unidad"]."'";
	//echo $consulta;
	//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	
	
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?codigo=".$val."\"
	</SCRIPT>";
	}

}
	$codigo = @$_GET['codigo'];
	$cod_centro = @$_GET['cod_centro'];
	//$descripcion= @$_GET['descripcion'];
	//$consulta="select * from ".$tabla." where cod_unidad=".$codigo;
	$consulta="select * from ".$tabla." where cod_unidad='".$codigo."'and cod_centro='".$cod_centro."'";
	//echo $consulta;
	$resultado=query($consulta, $conexion);
	//echo $resultado;
	$fila=fetch_array($resultado);
	//echo $fila;
?>

<html class="fondo">

<head>
  <title></title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
  </SCRIPT>
<script language="javascript" src="cal2.js"></script>
<script language="javascript" src="cal_conf2.js"></script>
<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	parent.cont.location.href=retorno+".php?codigo=<?=$codigo?>"
}

</SCRIPT>

</head>
<body>
<?
$consulta1="select descripcion from unidades where cod_unidad=".$codigo;
$resultado_unidad=query($consulta1,$conexion);
$fila_unidad=fetch_array($resultado_unidad);
$descripcion_unidad=$fila_unidad['descripcion'];

$consulta1 = "SELECT * FROM cwprogra where RecNoSec=99 ORDER BY Programa";
$resultado1 = query($consulta1, $conexion);
?>
<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>

	<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Editar Registro de <?echo $modulo?></strong></td>
    </tr>
				<TR><td class=tb-head colspan="2" align="center" width="180">COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE</td></tr>

	<tr>
	<td class=tb-head >Unidad</td><td><INPUT type="text" name="cod_unidad" size="100" readonly="true" value="<?echo $codigo?>"></td>
	</tr>
	<td class=tb-head >Centro de Costo</td><td><INPUT type="text" name="cod_centro" size="100" readonly="true" value="<?echo $cod_centro?>"></td>
	</tr>
	
	<?
	$i=0;
	$cont=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
	//echo $campo;
		echo "<TR>";?><td class=tb-head ><? echo "$nombre&nbsp;**"?></td>
		<td><INPUT type="text" name="<?echo $campo?>" size="100" value='<?echo "$fila[$campo]";?>'></td> </tr><?
		$i++;
		$cont++;
		if($cont==1)
		{
		?>
		<input type="hidden" name="sel_sector" value="99">
		<TR>
		<TD width="150" class="tb-head">Proyecto o Acción Centralizada:</TD>
		<TD>  
		<SELECT name="sel_programa" id="sel_programa" onchange="javascript:cargar_actividad_2()">
		<OPTION value="<?echo $fila['sel_programa'];?>" class="tb-fila"><?echo $fila['sel_programa'];?></OPTION>
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
		<OPTION value="<?echo $fila['sel_programa'];?>" class="tb-fila"><?echo $fila['sel_actividad'];?></OPTION>
		<OPTION class="tb-fila" value="0">Seleccione una Acción Especifica</OPTION>
		</SELECT>  
		</TD>
		</TR>
		<?
		
		}
	}
?>
    <tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Aceptar">&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onClick="javascript:cerrar('<?echo $url?>');"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);

?>