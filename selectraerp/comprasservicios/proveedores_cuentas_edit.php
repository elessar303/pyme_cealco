<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
$cod_cuenta=@$_GET['codigo'];
$url="proveedores_cuentas_edit";
$modulo="Proveedores";
$tabla="proveedores_cta";

$consulta1="SELECT * FROM proveedores_cta WHERE cod_proveedor_cta='".$cod_cuenta."'";
$resultado1=query($consulta1, $conexion);
$fila=fetch_array($resultado1);
$cod_proveedor = $fila['cod_proveedor'];
$banco = $fila['banco'];
$cuenta = $fila['num_cta'];

if(isset($_POST['aceptar']))
{
	$consulta="UPDATE proveedores_cta SET num_cta='".$_POST['nro_cuenta']."', banco='".$_POST['banco']."' WHERE cod_proveedor_cta='".$_POST['cod_cuenta']."'";
	$resultado=query($consulta,$conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Se ha editado una cuenta bancaria del proveedor\")
	parent.cont.location.href=\"proveedores_cuentas_list.php?codigo=".$_POST['cod_proveedor']."\"
	</SCRIPT>";
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

<FORM name="<?echo $url?>" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<TABLE  width="100%" height="100">
<TBODY>
	<tr>
   	<td colspan="2" class="row-br"><?titulo("Editar una Cuenta Bancaria del Proveedor","","proveedores_cuentas_list.php?codigo=$cod_proveedor","28","");?></td>
   </tr>
		<td class="tb-fila" width="200">C&oacute;digo Proveedor: </td>
   	<td><INPUT type="text" readonly="true" size="20" name="cod_proveedor" value="<?echo $cod_proveedor;?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">C&oacute;digo Cuenta del Proveedor: </td>
   	<td><INPUT type="text" name="cod_cuenta" readonly="true" size="20" value="<?echo $cod_cuenta?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">Banco: </td>
   	<td><INPUT type="text" name="banco" size="30" maxlength="25" value="<?echo $banco?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">N&uacute;mero de Cuenta: </td>
   	<td><INPUT type="text" size="30" maxlength="20" name="nro_cuenta" value="<?echo $cuenta?>"></td>
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