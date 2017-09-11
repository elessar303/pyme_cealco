<?php
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
$cod_proveedor=@$_GET['codigo'];
$url="proveedores_clasif_add";
$modulo="Proveedores";
$tabla="proveedores_cla";

$consult = "SELECT * FROM clasificacion_multiple";
$result = query($consult, $conexion);

$conitm = "SELECT MAX(cod_proveedor_cla) AS maxitem FROM proveedores_cla";
$resitm = query($conitm, $conexion);
$filaitm = fetch_array($resitm);
$cod_clasificacion = $filaitm['maxitem'];
$cod_clasificacion = $cod_clasificacion + 1;

if(isset($_POST['aceptar']))
{
	$consulta1="SELECT * FROM clasificacion_multiple WHERE descripcion LIKE '%".$_POST['sel_clasif']."%'";
	$resultado1=query($consulta1, $conexion);
	$fila1=fetch_array($resultado1);
	$cod_clasif = $fila1['codigo'];
	
	$consulta2="SELECT * FROM proveedores_cla WHERE cod_proveedor_cla='".$_POST['sel_clasif']."'";
	$resultado2=query($consulta2, $conexion);
	$num_filas=num_rows($resultado2);
	
	if($num_filas==0)
	{
		$consulta="INSERT INTO ".$tabla." VALUES ('".$_POST['cod_clasificacion']."', '".$_POST['cod_proveedor']."', '".$cod_clasif."')";
		$resultado=query($consulta,$conexion);
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		alert(\"Se ha insertado una nueva clasificación múltiple del proveedor\")
		parent.cont.location.href=\"proveedores_clasif_list.php?codigo=".$_POST['cod_proveedor']."\"
		</SCRIPT>";
	}else{
		echo "El codigo introducido ya esta siendo usado por otro registro";
	}
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
   	<td colspan="2" class="row-br"><?titulo("Agregar un Nueva Clasificaci&oacute;n M&uacute;ltiple del Proveedor","","proveedores_clasif_list.php?codigo=$cod_proveedor","28");?></td>
   </tr>
	<TR>
		<td class="tb-fila" width="200">C&oacute;digo Proveedor: </td>
   	<td><INPUT type="text" readonly="true" size="20" name="cod_proveedor" value="<?echo $cod_proveedor;?>"></td>
	</tr>
	<TR>
		<td class="tb-fila" width="200">C&oacute;digo Clasificaci&oacute;n: </td>
   	<td><INPUT type="text" readonly="false" size="20" name="cod_clasificacion" value="<?echo $cod_clasificacion;?>"></td>
	</tr>
	<TR>
		<td class="tb-fila" width="200">Tipo: </td>
   	<td><SELECT name="sel_clasif">
				<option class="tb-fila">Seleccione</option>
				 <?while($fila=fetch_array($result)){ 
					$codigo=$fila['codigo'];
					$descripcion=$fila['descripcion'];	?>
	            <option><?echo "$descripcion"?></option>
					<?}?>
          </SELECT></td>
	</tr>
	<tr class="tb-tit">
      <td></td>
      <td align="right"><INPUT type="submit" name="aceptar" value="Guardar"></td>
    </tr>
  </tbody>
</table>
</FORM>
</body>
</HTML>
<?cerrar_conexion($conexion);?>