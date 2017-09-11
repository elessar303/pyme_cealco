<?php
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");

$url="proveedores_clasif_list";
$modulo="Proveedores";
$tabla="proveedores_cla";
$titulos=array("C&oacute;digo Clasificaci&oacute;n", "Descripci&oacute;n");
$indices=array("0","2");

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
			$consulta=buscar_exacta($tabla,$des,"descripcion");
			break;
		case "todas":
			$consulta=buscar_todas($tabla,$des,"descripcion");
			break;
		case "cualquiera":
			$consulta=buscar_cualquiera($tabla,$des,"descripcion");
			break;
	}
}else{
$consulta="SELECT * FROM proveedores_cla WHERE cod_proveedor='".$codigo."' ORDER BY cod_proveedor_cla";
$resultado=query($consulta, $conexion);
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>

<HTML>
<HEAD><TITLE>Proveedores Números Telefónicos</TITLE>
<link href="../estilos.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
  <tr>
    <td class="row-br"><?titulo("Clasificaci&oacute;n M&uacute;ltiple del Proveedor","proveedores_clasif_add.php?codigo=$codigo","proveedores_list.php","28");?>
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
	while($fila=fetch_array($resultado)){
   	$i++;
	if($i%2==0){
	?>
   	<tr class="tb-fila">
	<?
	}else{
		echo "<tr>";
	}
	/*foreach($indices as $campo){
		$nom_tabla=mysql_field_name($resultado,$campo);
		$var=$fila[$nom_tabla];
		echo "<td>$var</td>";*/
	$codigo_clasif=$fila["cod_proveedor_cla"];
	$cod_descripcion = $fila["cod_clasificacion"];
	$consulta1="SELECT * FROM clasificacion_multiple WHERE codigo='".$cod_descripcion."'";
	$resultado1=query($consulta1, $conexion);
	$fila1=fetch_array($resultado1);
	$descripcion = $fila1['descripcion'];
	
	echo "<td>$codigo_clasif</td>";
   echo "<td>$descripcion</td>";
	
	//}
	icono("proveedores_clasif_edit.php?codigo=".$codigo_clasif, "Editar", "edit.gif");
	icono("proveedores_clasif_delete.php?codigo=".$codigo_clasif, "Eliminar", "delete.gif");
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
