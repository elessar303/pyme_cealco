<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
$cod_clasif=@$_GET['codigo'];
$url="proveedores_clasif_delete";
$modulo="Proveedores";
$tabla="proveedores_cla";

$consulta1="SELECT * FROM proveedores_cla WHERE cod_proveedor_cla='".$cod_clasif."'";
$resultado1=query($consulta1, $conexion);
$fila1=fetch_array($resultado1);
$cod_proveedor = $fila1['cod_proveedor'];
$cod_clasificacion = $fila1['cod_proveedor_cla'];
$codClasif = $fila1['cod_clasificacion'];

$consulta2="SELECT * FROM clasificacion_multiple WHERE codigo='".$codClasif."'";
$resultado2=query($consulta2, $conexion);
$fila2=fetch_array($resultado2);
$descripcion = $fila2['descripcion'];

if(isset($_POST['aceptar']))
{
	$consulta="DELETE FROM proveedores_cla WHERE cod_proveedor_cla='".$_POST['cod_clasificacion']."'";
	$resultado=query($consulta,$conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Se ha eliminado una clasificación múltiple del proveedor\")
	parent.cont.location.href=\"proveedores_clasif_list.php?codigo=".$_POST['cod_proveedor']."\"
	</SCRIPT>";
}
?>

<HTML class="fondo">
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
<FORM name="<?echo $url?>" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<TABLE  width="100%" height="100">
<TBODY>
	<tr>
   	<td colspan="2" class="row-br"><?titulo("Eliminar una Clasificaci&oacute;n M&uacute;ltiple del Proveedor","","proveedores_clasif_list.php?codigo=$cod_proveedor","28","");?></td>
   </tr>
	<TR>
		<td class="tb-fila" width="200">C&oacute;digo Proveedor: </td>
   	<td><INPUT type="text" readonly="true" size="20" name="cod_proveedor" value="<?echo $cod_proveedor;?>"></td>
	</tr>
	<TR>
		<td class="tb-fila" width="200">C&oacute;digo Clasificaci&oacute;n: </td>
   	<td><INPUT type="text" readonly="true" size="20" name="cod_clasificacion" value="<?echo $cod_clasificacion;?>"></td>
	</tr>
	<TR>
		<td class="tb-fila" width="200">Tipo: </td>
   	<td><INPUT type="text" readonly="true" size="65" name="tipo" value="<?echo $descripcion;?>"></td>
	</tr>
	<tr class="tb-tit">
      <td></td>
      <td align="right">&#191;Confirma que desea eliminar esta clasificaci&oacute;n m&uacute;ltiple&#063;&nbsp;&nbsp;&nbsp;<INPUT type="submit" name="aceptar" value="Eliminar"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</HTML>
<?cerrar_conexion($conexion);?>