<?php
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;

$url="aporte_social";
$modulo="Aporte Social";
$tabla="aporte_social";
$titulos=array("Descripción","% Aporte");
$indices=array("1","2");
 $codigo = @$_GET['codigo'];
if(isset($_POST['aceptar'])){
	
	$consulta="select * from ".$tabla;
	$resultado= query($consulta,$conexion);

	
	$cadena="";
	foreach($indices as $valor){
		$campo=mysql_field_name($resultado,$valor);
		if($cadena==""){
			if($valor==2){
			
			$cadena=$cadena.$campo."=".$_POST[$campo]."";
			}else{$cadena=$cadena.$campo."='".$_POST[$campo]."'";}
		}
		else{
			if($valor==2){
				$cadena=$cadena.",".$campo."=".$_POST[$campo]."";
			}else{	$cadena=$cadena.",".$campo."='".$_POST[$campo]."'";}
		}
	}
	
	$consulta="update ".$tabla." set ".$cadena." where codigo=".$codigo;
	//echo $consulta;
//exit(0);
	$resultado=query($consulta,$conexion) or die("no se actualizo el movimiento");
	
	cerrar_conexion($conexion);
	?>
	<SCRIPT language="JavaScript" type="text/javascript">
			alert("Se Modifico el Aporte Social!!");
		</SCRIPT>
		
	<?php
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
			
		     location.href=\"aporte_social.php?codigo=$codigo	\"
		</SCRIPT>";
	

}
	
	//$descripcion= @$_GET['descripcion'];
	$consulta="select * from ".$tabla." where codigo=".$codigo;
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
	parent.cont.location.href=retorno+".php?pagina=1"
}

</SCRIPT>

</head>
<body>

<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']."?codigo=".$codigo; ?>">

<TABLE  width="100%" >
<TBODY>
	<tr><td  class="tb-tit"><strong>Editar Registro de <?echo $modulo?></strong></td></tr>
</tbody>
</table>
<TABLE  width="100%" >
<TR><td class=tb-head >C&oacute;digo</td><td><?echo $codigo?></td> </tr>

	<?
	$i=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
	//echo $campo;
		echo "<TR>";?><td class=tb-head width="300"><?echo "$nombre"?></td>
		<td><INPUT type="text" name="<?echo $campo?>" size="80" value='<?echo "$fila[$campo]";?>'></td> </tr><?
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