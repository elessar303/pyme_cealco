<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
//$codigo = @$_GET['codigo'];
$url="centros_list";
$modulo="Centro de Costo";
$tabla="centros";
$titulos=array("Descripción");
$indices=array("2");

if(isset($_POST['aceptar'])){
	
	$consulta="select * from ".$tabla;
	$resultado= query($consulta,$conexion);

	
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
	$codigo = @$_GET['codigo'];
	$cod_centro = @$_GET['cod_centro'];
	//$consulta="update ".$tabla." set ".$cadena." where cod_centro=".$_POST["codigo"];
	$consulta="update ".$tabla." set ".$cadena." where cod_centro='".$_POST["cod_centro"]."'and cod_unidad='".$_POST["cod_unidad"]."'";
	//echo $consulta;
//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	
	$val=$_POST['cod_unidad'];
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?codigo=".$val."\"
	</SCRIPT>";
	

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

?>
<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>

	<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Editar Registro de <?echo $modulo?></strong></td>
    </tr>
	<tr>
	<td class=tb-head >Unidad</td><td><INPUT type="text" name="cod_unidad" size="100" readonly="true" value="<?echo $codigo?>"></td>
	</tr>
	<td class=tb-head >Centro de Costo</td><td><INPUT type="text" name="cod_centro" size="100" readonly="true" value="<?echo $cod_centro?>"></td>
	</tr>
	
	<?
	$i=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
	//echo $campo;
		echo "<TR>";?><td class=tb-head ><?echo "$nombre"?></td>
		<td><INPUT type="text" name="<?echo $campo?>" size="100" value='<?echo "$fila[$campo]";?>'></td> </tr><?
		$i++;
		
	}
?>
    <tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Aceptar">&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onclick="javascript:cerrar('<?echo $url?>');"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);

?>