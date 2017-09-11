<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;

$url="impuestos_islr_list";
$modulo="Retencion I.S.R.L";
$tabla="impuestos_islr";
$titulos=array("Descripción","Porcentaje Imponible","Aplica a Residente Domiciliado","Porcentaje Imponible","Aplica a No Residente No domiciliado","Alicuota","Monto de Sustracción","Pago mayores a","Alicuota","Retención Acumulativa","Alicuota","Monto de Sustracción","Pago mayores a","Alicuota","Retención Acumulativa");
$indices=array("1","2","3","5","4","6","7","8","9","14","10","11","12","13","15");

if(isset($_POST['aceptar'])){
	
	$consulta="select * from ".$tabla;
	$resultado=mysql_query($consulta);
	//if((!$_POST['nombre_banco'])||(!$_POST['numero_cuenta'])||(!$_POST['firstinput'])){
	//	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	//	alert(\"Datos imcompletos, no se puede realizar la operacion\")";
	//	echo "</SCRIPT>";
	//	
	//}
$indices=array("0","1","2","3","5","4","6","7","8","9","14","10","11","12","13","15");
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
$val=$_POST['codigo'];
	echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	parent.cont.location.href=\"".$url.".php?pagina=1\"
	</SCRIPT>";
	

}else{

	$consulta="select * from ".$tabla;
	//echo $consulta;
	$resultado=query($consulta,$conexion);
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
	parent.cont.location.href=retorno+".php?pagina=1"
}

</SCRIPT>

</head>
<body>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>
<?$consulta="select max(cod_impuesto_isrl) as valor from impuestos_islr";
$resultado_islr=query($consulta,$conexion);
//echo $consulta;
$fila_islr=fetch_array($resultado_islr);
$max_islr=$fila_islr['valor'];
$valor=$max_islr+1;
?>
<tr>
      <td colspan="2" height="30" class="tb-tit"><strong>Agregar Nuevo Registro de <?echo $modulo?></strong></td>
    </tr>
	<td class="tb-head" width="100">C&oacute;digo Impuesto I.S.L.R.</td>
      	<td colspan="6"><?echo $valor;?> <INPUT type="hidden" name="cod_impuesto_isrl" value="<?=$valor?>"></td>

	<?
	$i=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
		echo "<TR>";?><td class=tb-head ><?echo "$nombre</td>";
		echo "<td colspan=\"3\"><INPUT type=\"text\" name=\"$campo\" size=\"100\"></td> </tr>";
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