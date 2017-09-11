<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
$cod_estado=@$_GET['codigo'];
//$cod_municipio=@$_GET['codigo'];
$url="municipios_list";
$modulo="Municipios";
$tabla="municipios";
$titulos=array("Nombre");
$indices=array("2");

if(isset($_POST['aceptar'])){
	
	$consulta="select * from ".$tabla;
	//echo $consulta;
	$resultado=mysql_query($consulta);
	//if((!$_POST['nombre_banco'])||(!$_POST['numero_cuenta'])||(!$_POST['firstinput'])){
	//	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	//	alert(\"Datos imcompletos, no se puede realizar la operacion\")";
	//	echo "</SCRIPT>";
	//	
	//}
$indices=array("0","1","2");
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

$val=$_POST['cod_estado'];
	echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?codigo=".$val."\"
	</SCRIPT>";
	

}else{

	$consulta="select * from ".$tabla;
	$resultado=query($consulta,$conexion);
	//echo $consulta;
	//echo $conexion;
}
?>



<html class="fondo">

<head>
  <title></title>
  <link href="../estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
  </SCRIPT>
<script language="javascript" src="cal2.js"></script>
<script language="javascript" src="cal_conf2.js"></script>
<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	parent.cont.location.href=retorno+".php?codigo=<?=$cod_estado?>"
}

</SCRIPT>

</head>
<body>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>
<?
$consulta1="select nombre from estados where cod_estado=".$cod_estado;
$resultado_estado=query($consulta1,$conexion);
$fila_estado=fetch_array($resultado_estado);
$descripcion_estado=$fila_estado['nombre'];

$consulta="select max(cod_municipio) as valor from municipios where cod_estado=".$cod_estado;
//echo $consulta;
$resultado_municipio=query($consulta,$conexion);
$fila_municipio=fetch_array($resultado_municipio);
$max_municipio=$fila_municipio['valor'];

$valor=$max_municipio+1;
?>
<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Agregar Nuevo Registro de <?echo $modulo?></strong></td>
    </tr>
      	<td class="tb-head">Nombre Estado</td>
      	<td colspan="6"><?echo "$descripcion_estado";?> <INPUT type="hidden" name="cod_estado" value="<?echo "$cod_estado";?>"></td>
	<tr><td class="tb-head">C&oacute;digo Municipio</td>
      	<td colspan="6"><?=$valor?> <INPUT type="hidden" name="cod_municipio" value="<?echo "$valor";?>"></td></tr>
      	
	<?
	$i=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
		echo "<TR>";?><td class=tb-head ><?echo "$nombre</td>";
		echo "<td colspan=\"3\"><INPUT type=\"text\" name=\"$campo\" size=\"100\"></td> </tr>";
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