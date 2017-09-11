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


if(isset($_POST['aceptar'])){
	
	$consulta="select * from ".$tabla;
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
			
			if($valor!=1){
				$cadena_valores=$cadena_valores."".$_POST[$campo]."";
			}
			else{
				$cadena_valores=$cadena_valores."'".$_POST[$campo]."'";
			}
		}
		else{
			$cadena_campos=$cadena_campos.",".$campo;
			if($valor!=1){
				$cadena_valores=$cadena_valores.",".$_POST[$campo]."";
			}
			else{
				$cadena_valores=$cadena_valores.",'".$_POST[$campo]."'";
			}
		}
	}
	//echo $cadena_campos." ";
	//echo $cadena_valores;
	
	 $consulta="insert into ".$tabla." (".$cadena_campos.") values (".$cadena_valores.")";
	$resultado=query($consulta,$conexion);
	//echo $consulta;
	//exit(0);
	?>
	<SCRIPT language="JavaScript" type="text/javascript">
			alert("Se guardo un Nuevo Aporte Social!!");
		</SCRIPT>
		
	<?php
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
			
		     location.href=\"aporte_social.php?codigo=$codigo	\"
		</SCRIPT>";
	$val=$_POST['codigo'];
	
	

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

<TABLE  width="100%" >
<TBODY>
	<tr><td  class="tb-tit"><strong>Agregar Nuevo Registro de <?echo $modulo?></strong></td></tr>
</tbody>
</table>
<?
$consulta="select max(codigo) as valor from aporte_social";
$resultado_iva=query($consulta,$conexion);
//echo $consulta;
$fila_iva=fetch_array($resultado_iva);
$max_iva=$fila_iva['valor'];
$valor=$max_iva+1;
?>
<TABLE  width="100%" >
<tr>
	<td class="tb-head" >C&oacute;digo Aporte Social</td>
      	<td ><?echo $valor;?> <INPUT type="hidden" name="codigo" value="<?=$valor?>"></td>
</tr>
	<?
	$i=0;
	foreach($titulos as $nombre){
		$campo=mysql_field_name($resultado,$indices[$i]);
		echo "<TR>";?><td class=tb-head  width="300"><?echo "$nombre</td>";
		echo "<td  ><INPUT  type=\"text\" name=\"$campo\" size=\"80\"";
		if($indices[$i]==2){
			echo "onkeypress=\"javascript:return numeros(event)\"";
		}
		echo "></td> </tr>";
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