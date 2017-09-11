<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
$pagina = @$_GET['pagina'];
$url="impuestos_islr_list";
$modulo="Retencion I.S.R.L";
$tabla="impuestos_islr";
$titulos=array("Descripción","Porcentaje Imponible","Aplica a Residente Domiciliado","Porcentaje Imponible","Aplica a No Residente No domiciliado","Alicuota","Monto de Sustraccion","Pago mayores a","Alicuota","Retención Acumulativa","Alicuota","Monto de Sustracción","Pago mayores a","Alicuota","Retención Acumulativa");
$indices=array("1","2","3","5","4","6","7","8","9","14","10","11","12","13","15");


if(isset($_POST['aceptar'])){
	
	$consulta="DELETE FROM ".$tabla." where cod_impuesto_isrl=".$_POST["codigo"];
	//echo $consulta;
//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	$pagina = @$_POST['pagina'];
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?pagina=".$pagina."\"
	</SCRIPT>";
	

}
	$codigo = @$_GET['codigo'];
	//$fecha = @$_GET['fecha'];
	//$numero = @$_GET['numero'];
	$consulta="select * from ".$tabla." where cod_impuesto_isrl=".$codigo;
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
	parent.cont.location.href=retorno+".php?pagina=<?=$pagina?>"
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
<TR><td class=tb-head >C&oacute;digo</td><td><INPUT type="text" name="codigo" size="100" readonly="true" value="<?echo $codigo?>"></td> </tr>
<INPUT type="hidden" name="pagina" value="<?echo "$pagina";?>"></td>

	<?
	$i=0;
	$cont=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
	//echo $campo;
		echo "<TR>";?><td class=tb-head ><?echo "$nombre"?></td>
		<td><INPUT type="text" name="<?echo $campo?>" size="100" value='<?echo "$fila[$campo]";?>' readonly="true"></td> </tr><?
		$i++;
		$cont++;
		if($cont==1)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Base Imponible Residente/Domiciliado</strong></td>";
		}
		if($cont==3)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Base Imponible No Residente/No Domiciliado</strong></td>";
		}
		if($cont==5)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Persona Natural Residente</strong></td>";
		}
		if($cont==8)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Persona Natural No Residente</strong></td>";
		}
		if($cont==10)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Persona Juridica </strong></td>";
		}
		if($cont==13)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>PERSONA JURIDICA NO DOMICILIADA</strong></td>";
		}
		
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