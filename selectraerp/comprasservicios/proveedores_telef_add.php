<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
$cod_proveedor=@$_GET['codigo'];
$url="proveedores_telef_add";
$modulo="Proveedores";
$tabla="proveedores_tel";

$conitm = "SELECT MAX(cod_proveedor_tel) AS maxitem FROM proveedores_tel";
$resitm = query($conitm, $conexion);
$filaitm = fetch_array($resitm);
$cod_telf = $filaitm['maxitem'];
$cod_telf = $cod_telf + 1;

if(isset($_POST['aceptar']))
{
	$consulta1="SELECT * FROM proveedores_tel WHERE cod_proveedor_tel='".$_POST['cod_telef']."'";
	$resultado1=query($consulta1, $conexion);
	$num_filas=num_rows($resultado1);
	
	if($num_filas==0)
	{
		$consulta="INSERT INTO ".$tabla." VALUES ('".$_POST['cod_telef']."', '".$_POST['cod_proveedor']."', '".$_POST['tipo']."', '".$_POST['numero']."', '".$_POST['descripcion']."')";
		$resultado=query($consulta,$conexion);
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Se ha insertado un nuevo número telefónico del proveedor\")
		parent.cont.location.href=\"proveedores_telef_list.php?codigo=".$_POST['cod_proveedor']."\"
		</SCRIPT>";
	}else{
		echo "El codigo introducido ya esta siendo usado por otro registro";
	}
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
   	<td colspan="2" class="row-br"><?titulo("Agregar un Nuevo N&uacute;mero Telef&oacute;nico del Proveedor","","proveedores_telef_list.php?codigo=$cod_proveedor","28");?></td>
   </tr>
		<td class="tb-fila" width="200">C&oacute;digo Proveedor: </td>
   	<td><INPUT type="text" readonly="true" size="20" name="cod_proveedor" value="<?echo $cod_proveedor;?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">C&oacute;digo Tel&eacute;fono del Proveedor: </td>
		<td><INPUT type="text" name="cod_telef" size="20" value="<?echo $cod_telf?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">N&uacute;mero Telef&oacute;nico: </td>
   	<td><INPUT type="text" name="numero" size="20" maxlength="15"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">Tipo: </td>
   	<td><SELECT name="tipo">
				<option class="tb-fila">Seleccione</option>
				<option>General</option>
				<option>Habitaci&oacute;n</option>
				<option>Celular</option>
				<option>Fax</option>
        </SELECT></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">Descripci&oacute;n: </td>
   	<td><INPUT type="text" size="60" maxlength="50" name="descripcion"></td>
	</tr>
    <tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Guardar"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</html>
<?cerrar_conexion($conexion);?>