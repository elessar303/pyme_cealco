<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
$cod_telf=@$_GET['codigo'];
$url="proveedores_telef_add";
$modulo="Proveedores";
$tabla="proveedores_tel";

$consulta1="SELECT * FROM proveedores_tel WHERE cod_proveedor_tel='".$cod_telf."'";
$resultado1=query($consulta1, $conexion);
$fila=fetch_array($resultado1);
$cod_proveedor = $fila['cod_proveedor'];
$numero = $fila['numero'];
$tipo = $fila['tipo'];
$descripcion = $fila['descripcion'];

if(isset($_POST['aceptar']))
{
	$consulta="UPDATE proveedores_tel SET tipo='".$_POST['tipo']."', numero='".$_POST['numero']."', descripcion='".$_POST['descripcion']."' WHERE cod_proveedor_tel='".$_POST['cod_telef']."'";
	$resultado=query($consulta,$conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Se ha editado una cuenta bancaria del proveedor\")
	parent.cont.location.href=\"proveedores_telef_list.php?codigo=".$_POST['cod_proveedor']."\"
	</SCRIPT>";
}
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

<FORM name="<?echo $url?>" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>
	<tr>
   	<td colspan="2" class="row-br"><?titulo("Editar un N&uacute;mero Telef&oacute;nico del Proveedor","","proveedores_telef_list.php?codigo=$cod_proveedor","28","");?></td>
   </tr>
		<td class="tb-fila" width="200">C&oacute;digo Proveedor: </td>
   	<td><INPUT type="text" readonly="true" size="20" name="cod_proveedor" value="<?echo $cod_proveedor;?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">C&oacute;digo Tel&eacute;fono del Proveedor: </td>
		<td><INPUT type="text" name="cod_telef" size="20" readonly="true" value="<?echo $cod_telf?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">N&uacute;mero Telef&oacute;nico: </td>
   	<td><INPUT type="text" name="numero" size="20" maxlength="15" value="<?echo $numero?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">Tipo: </td>
   	<td><SELECT name="tipo">
			<option class="tb-fila"><?echo $tipo?></option>
			<option>General</option>
			<option>Habitaci&oacute;n</option>
			<option>Celular</option>
			<option>Fax</option>
        </SELECT></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">Descripci&oacute;n: </td>
   	<td><INPUT type="text" size="60" maxlength="50" name="descripcion" value="<?echo $descripcion?>"></td>
	</tr>
    <tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Actualizar"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?cerrar_conexion($conexion);?>