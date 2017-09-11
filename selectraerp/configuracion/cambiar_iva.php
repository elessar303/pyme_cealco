<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;

$url="materiales";
$modulo="Materiales";
$tabla="materiales";
$codigo_snc=$_GET['codigo_snc'];

if(isset($_POST['aceptar']))
{
	$ivaviejo=$_POST['ivaviejo'];
	$ivanuevo=$_POST['ivanuevo'];;
	
	$consulta="select iva from ".$tabla;
	$resultado= query($consulta,$conexion);
	$numero_filas =mysql_num_rows($resultado);
	if($_POST['ivaviejo']=="")
	{	
		$consulta="update ".$tabla." set iva='".$ivanuevo."' where iva is NULL";
	}else
	{
		$consulta="update ".$tabla." set iva='".$ivanuevo."' where iva='".$ivaviejo."'";
	}
	$consulta=$consulta." and codigo_snc='".$_POST['codigo_snc']."'";
	$resultado=query($consulta,$conexion);
	
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Se ha editado el Correctamente el registro\")
	parent.cont.location.href=\"".$url.".php?codigo_snc=".$_POST['codigo_snc']."\"
	</SCRIPT>";
}
	//$codigo = @$_GET['codigo'];
	//$descripcion= @$_GET['descripcion'];
	//$consulta="select * from ".$tabla." where cod_material=".$codigo;
	//echo $consulta;
	//$resultado=query($consulta, $conexion);
	//echo $resultado;
	//$fila=fetch_array($resultado);
	//echo $fila;
?>

<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	parent.cont.location.href=retorno+".php?pagina=1"
}

</SCRIPT>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>

<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Editar Registro de <?echo $modulo?></strong></td>

    </tr>

	<?
	echo "<TR>";?><td class=tb-head >I.V.A A Cambiar</td>
	<td><INPUT type="text" name="ivaviejo" size="100" value='<?$ivaviejo;?>'></td> </tr><?
	echo "<TR>";?><td class=tb-head >I.V.A. Nuevo</td>
	<td><INPUT type="hidden" name="codigo_snc" value="<?echo $codigo_snc?>"><INPUT type="text" name="ivanuevo" size="100" value='<?$ivanuevo;?>'></td> </tr><?
		
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