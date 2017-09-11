<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
$cod_unidades=@$_GET['codigo'];

$url="unidades_list";
$modulo="Unidades";
$tabla="unidades";
$titulos=array("Descripción");
$indices=array("1");

if(isset($_POST['aceptar']))
{
	if (($_POST['descripcion'] == '') )
	{
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Datos imcompletos, no se puede realizar la operacion\")
		location.href=\"".$url.".php?pagina=1\"";
		echo "</SCRIPT>";
		//
	}
	else
	{	
		$consulta="select * from ".$tabla;
		$resultado=mysql_query($consulta);
	//if((!$_POST['nombre_banco'])||(!$_POST['numero_cuenta'])||(!$_POST['firstinput'])){
	//	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	//	alert(\"Datos imcompletos, no se puede realizar la operacion\")";
	//	echo "<//SCRIPT>";
	//	
	//}
	$indices=array("0","1");
	foreach($indices as $valor)
	{
		$campo=mysql_field_name($resultado,$valor);
		if($cadena_campos=="" && $cadena_valores=="")
		{
		
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
	location.href=\"".$url.".php?pagina=1\"
	</SCRIPT>";
	}

}
else
{

	$consulta="select * from ".$tabla;
	$resultado=query($consulta,$conexion);
}
?>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>
<? 
$consulta="select max(cod_unidad) as valor from unidades";
$resultado_unidades=query($consulta,$conexion);
//echo $consulta;
$fila_unidades=fetch_array($resultado_unidades);
$max_unidades=$fila_unidades['valor'];
$valor=$max_unidades+1;

?>

<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Agregar Nuevo Registro de <? echo $modulo?></strong></td>
    </tr>
		<TR><td class=tb-head colspan="2" align="center" width="180">COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE</td></tr>

	<td class="tb-head">C&oacute;digo Unidad</td>
      	<td colspan="6"><? echo "$valor";?> <INPUT type="hidden" name="cod_unidad" value="<? echo "$valor";?>"></td>
	<?
	$i=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
		echo "<TR>";?><td class=tb-head ><? echo "$nombre&nbsp;**</td>";
		echo "<td colspan=\"3\"><INPUT type=\"text\" name=\"$campo\" size=\"100\"></td> </tr>";
		$i++;
		
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
