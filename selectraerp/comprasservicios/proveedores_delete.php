<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;

$cod_proveedores=@$_GET['codigo'];
$url="proveedores_list";
$modulo="Proveedores";
$tabla="proveedores";
$titulos=array("Compañia","Siglas","R.I,F.","N.I.T.","Direccion","Direccion2","e_mail","web","Nombres","Apellidos","Cedula","Dias de credito","Fecha registro","Fecha vencimiento","Tipo de retencion","Declara","Cuenta Contable","Cuenta bancaria","Registro","Fecha","Numero","Tomo","Capital suscrito","Capital pagado","Observaciones","Contraloria","Contraloria fecha","Status");
$indices=array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","18","19","20","21","22","23","24","25","26","27","28","29","30","33");

if(isset($_POST['aceptar'])){
	
	$consulta="DELETE FROM ".$tabla." where cod_proveedor=".$_POST["codigo"];
	//echo $consulta;
//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	
	cerrar_conexion($conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Se ha eliminado un registro\")
	parent.cont.location.href=\"".$url.".php?pagina=1\"
	</SCRIPT>";
	

}
	$codigo = @$_GET['codigo'];
	//$fecha = @$_GET['fecha'];
	//$numero = @$_GET['numero'];
	$consulta="select * from ".$tabla." where cod_proveedor=".$codigo;
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
	parent.cont.location.href=retorno+".php?pagina=1"
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
<TR><td class=tb-head >Codigo Proveedor</td><td><INPUT type="text" name="codigo" size="100" readonly="true" value="<?echo $codigo?>"></td> </tr>

	<?
	$i=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
	//echo $campo;
		echo "<TR>";?><td class=tb-head ><?echo "$nombre"?></td>
		<td><INPUT type="text" name="<?echo $campo?>" size="100" value='<?echo "$fila[$campo]";?>' readonly="true"></td> </tr><?
		$i++;
		$cont++;
		if($cont==14)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Datos SENIAT</strong></td>";
		}
		if($cont==18)
		{
		echo "<td class=\"tb-head\" colspan=\"2\" align=\"center\"><strong>Datos Técnicos</strong></td>";
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