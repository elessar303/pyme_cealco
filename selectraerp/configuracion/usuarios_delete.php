<?php
require_once '../lib/common.php';
$conexion=conexion_conf();

$url="usuarios_list";
$modulo="Usuarios";
$tabla="usuarios";
$titulos=array("Nombre","Nombre de Usuario");
$indices=array("2","1");


if(isset($_POST['aceptar'])){
	
	$consulta="DELETE FROM ".$tabla." where cod_usuario='".$_POST["codigo"]."'";
	//echo $consulta;
//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?pagina=1\"
	</SCRIPT>";
}
$codigo = @$_GET['codigo'];
	
	
	$consulta="select * from ".$tabla." where cod_usuario='".$codigo."'";
	//echo $consulta;
	$resultado=query($consulta,$conexion);
	$fila=fetch_array($resultado);

?>

<html class="fondo">

<head>
  <title></title>
  <link href="../estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
  </SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	parent.cont.location.href=retorno+".php?pagina=1"
}

</SCRIPT>

</head>
<body>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>

<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Eliminar un Registro de <?echo $modulo?></strong></td>

    </tr>
<INPUT type="hidden" name="codigo" value="<?echo $codigo?>">
	<?
	$i=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
	//echo $campo;
		echo "<TR>";?><td class=tb-head ><?echo "$nombre"?></td>
		<td><INPUT type="text" name="<?echo $campo?>" size="100" value='<?echo "$fila[$campo]";?>' readonly="true"></td> </tr><?
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