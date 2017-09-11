<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
$cod_proveedor=@$_GET['codigo'];
$url="proveedores_cuentas_add";
$modulo="Proveedores";
$tabla="proveedores_cta";

$conitm = "SELECT MAX(cod_proveedor_cta) AS maxitem FROM proveedores_cta";
$resitm = query($conitm, $conexion);
$filaitm = fetch_array($resitm);
$cod_cuenta = $filaitm['maxitem'];
$cod_cuenta = $cod_cuenta + 1;

if(isset($_POST['aceptar']))
{
	$consulta1="SELECT * FROM proveedores_cta WHERE cod_proveedor_cta='".$_POST['cod_cuenta']."'";
	$resultado1=query($consulta1, $conexion);
	$num_filas=num_rows($resultado1);
	
	if($num_filas==0)
	{
		$consulta="INSERT INTO ".$tabla." VALUES ('".$_POST['cod_cuenta']."', '".$_POST['cod_proveedor']."', '".$_POST['nro_cuenta']."', '".$_POST['banco']."')";
		$resultado=query($consulta,$conexion);
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Se ha insertado un nueva cuenta bancaria del proveedor\")
		parent.cont.location.href=\"proveedores_cuentas_list.php?codigo=".$_POST['cod_proveedor']."\"
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
   	<td colspan="2" class="row-br"><?titulo("Agregar una Nueva Cuenta Bancaria del Proveedor","","proveedores_cuentas_list.php?codigo=$cod_proveedor","28");?></td>
   </tr>
		<td class="tb-fila" width="200">C&oacute;digo Proveedor: </td>
   	<td><INPUT type="text" readonly="true" size="20" name="cod_proveedor" value="<?echo $cod_proveedor;?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">C&oacute;digo Cuenta del Proveedor: </td>
   	<td><INPUT type="text" name="cod_cuenta" size="20" value="<?echo $cod_cuenta?>"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">Banco: </td>
   	<td><INPUT type="text" name="banco" size="30" maxlength="25"></td>
	</tr>
	</tr>
		<td class="tb-fila" width="200">N&uacute;mero de Cuenta: </td>
   	<td><INPUT type="text" size="30" maxlength="20" name="nro_cuenta"></td>
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