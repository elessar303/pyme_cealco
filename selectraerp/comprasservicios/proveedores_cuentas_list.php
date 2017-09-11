<?php 
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");

$url="proveedores_cuentas_list";
$modulo="Proveedores";
$tabla="proveedores_cta";
$titulos=array("C&oacute;digo Cuenta","Banco","N&uacute;mero de Cuenta");
$indices=array("0","3","2");

$conexion=conexion();
$codigo = $_GET['codigo'];
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
	}
	switch($tipob){
		case "exacta":
			$consulta=buscar_exacta($tabla,$des,"num_cta");
			break;
		case "todas":
			$consulta=buscar_todas($tabla,$des,"num_cta");
			break;
		case "cualquiera":
			$consulta=buscar_cualquiera($tabla,$des,"num_cta");
			break;
	}
}else{
$consulta="SELECT * FROM proveedores_cta WHERE cod_proveedor='".$codigo."' ORDER BY cod_proveedor_cta";
$resultado=query($consulta, $conexion);
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>

<HTML>
<HEAD><TITLE>Proveedores Cuentas Bancarias</TITLE>
<link href="../estilos.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
  <tr>
    <td class="row-br"><?titulo("Cuentas Bancarias del Proveedor","proveedores_cuentas_add.php?codigo=$codigo","proveedores_list.php","28");?>
	</td>
  </tr>
</table>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?pagina=".$pagina); ?></td>
	<td width="120"><input onclick="javascript:actualizar(this);" checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="todas">Todas las palabras</td>
	<td width="150"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>
	<td colspan="3" width="386"></td>
  </tr>
</table>
<BR>
<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
  <tbody>
    <tr class="tb-head" >
		<?	foreach($titulos as $nombre){
				echo "<td><STRONG>$nombre</STRONG></td>";
			}
		?>
      <td></td>
		<td></td>
    </tr>
	<? 
	if($num_paginas!=0){
	$i=0; 
	while($fila=mysql_fetch_array($resultado)){
   	$i++;
	if($i%2==0){
	?>
   	<tr class="tb-fila">
	<?
	}else{
		echo "<tr>";
	}
	foreach($indices as $campo){
		$nom_tabla=mysql_field_name($resultado,$campo);
		$var=$fila[$nom_tabla];
		echo "<td>$var</td>";
	}
	$codigo_cuenta=$fila["cod_proveedor_cta"];
	$nroCuenta=$fila["num_cta"];
	$banco=$fila["cod_banco"];
	//$descripcion=$fila["descripcion"];
	//echo $descripcion;
	icono("proveedores_cuentas_edit.php?codigo=".$codigo_cuenta, "Editar", "edit.gif");
	icono("proveedores_cuentas_delete.php?codigo=".$codigo_cuenta, "Eliminar", "delete.gif");
   echo "</tr>";
	}
}else{
	echo "<tr><td>No existen registro con la busqueda especificada</td></tr>";
}
cerrar_conexion($conexion);
?>
  </tbody>
</table>
<?
	pie_pagina($url,$pagina,"&tipo=".$tipob."&des=".$des,$num_paginas);
?>
</FORM>
</BODY>
</HTML>
