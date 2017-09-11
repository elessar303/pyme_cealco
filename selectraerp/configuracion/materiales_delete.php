<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
$pagina=@$_GET['pagina'];
$paginam=@$_GET['paginam'];
$pagina=@$_GET['pagina'];
$codigo_snc=@$_GET['codigo_snc'];

$url="materiales";
$modulo="Materiales";
$tabla="materiales";
$titulos=array("Descripción","Unidad","Parámetro","Cádigo","Codccos","Existencia","Mínimo","Máximo","Último Costo","Fecha Último Costo","Cantidad de Unidad","Unidad de Salida","I.V.A.");
$indices=array("1","2","3","4","5","6","7","8","9","10","11","12","13");

if(isset($_POST['aceptar'])){
	
	$consulta="DELETE FROM ".$tabla." where cod_material='".$_POST["cod_material"]."'and codigo_snc='".$_POST["codigo_snc"]."'";
	//echo $consulta;
	//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	$codigo_snc=@$_POST["codigo_snc"];
	$pagina=@$_POST['pagina'];
	$paginam=@$_POST['paginam'];
	
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?pagina=".$pagina."&codigo_snc=".$codigo_snc."&paginam=".$paginam."\"
	</SCRIPT>";
	

}
	$codigo = @$_GET['codigo'];
	$pagina=@$_GET['pagina'];
	//$fecha = @$_GET['fecha'];
	//$numero = @$_GET['numero'];
	$consulta="select * from ".$tabla." where cod_material='".$codigo."'";
	//echo $consulta;
	$resultado=query($consulta, $conexion);
	$fila=fetch_array($resultado);

?>

<html class="fondo">

<head>
  <title></title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="lib/common.js">
  </SCRIPT>
<script language="javascript" src="cal2.js"></script>
<script language="javascript" src="cal_conf2.js"></script>
<SCRIPT language="JavaScript" type="text/javascript">

function cerrar(retorno){
	parent.cont.location.href=retorno+".php?codigo_snc=<?=$codigo_snc?>&pagina=<?=$pagina?>&paginam=<?=$paginam?>"
}

</SCRIPT>

</head>
<body>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>

<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Eliminar Registro de <?echo $modulo?></strong></td>

    </tr>
<TR><td class=tb-head width="130" >C&oacute;digo Material</td><td><INPUT type="text" name="cod_material" size="100" readonly="true" value="<?echo $codigo?>"></td> </tr>
<TR><td class=tb-head >C&oacute;digo de SNC</td><td><INPUT type="text" name="codigo_snc" size="100" readonly="true" value="<?echo $codigo_snc?>"></td> </tr>

<INPUT type="hidden" name="pagina" value="<?echo "$pagina";?>"></td>
<INPUT type="hidden" name="paginam" value="<?echo "$paginam";?>"></td>

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