<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
$cod_unidad=@$_GET['codigo'];
//echo $cod_unidad;
$url="centros_list";
$modulo="Centro de costo";
$tabla="centros";
$titulos=array("Descripción");
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

	
	$val=$_POST['cod_unidad'];
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

<SCRIPT language="JavaScript" type="text/javascript">
function cerrarlocal(retorno){
	parent.cont.location.href="centros_list.php?codigo="+retorno
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
?>
<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Agregar Nuevo Registro de <?echo $modulo?></strong></td>
    </tr>

	<td class="tb-head">Descripcion Unidad</td>
      	<td colspan="6"><?echo "$descripcion_unidad";?> <INPUT type="hidden" name="cod_unidad" value="<?echo "$cod_unidad";?>"></td>
	<tr><td class="tb-head">C&oacute;digo Centro</td>
      	<td colspan="6"><?=$valor?> <INPUT type="hidden" name="cod_centro" value="<?echo "$valor";?>"></td></tr>
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
      <td align="right"><INPUT type="submit" name="aceptar" value="Aceptar">&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onclick="javascript:cerrarlocal('<?echo $cod_unidad?>');"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);

?>