<?php 
$url="materiales";
$modulo="Materiales";
$tabla="materiales";
$titulos=array("Código","Descripción","Unidad","I.V.A.");
$indices=array("0","1","2","13");

//DECLARACION DE LIBRERIAS
require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
$codigo_snc=@$_GET['codigo_snc'];
$codigo=@$_GET['codigo'];
$paginam=@$_GET['paginam'];
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$codigo_snc=@$_POST['codigo_snc'];
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
$consulta="select * from ".$tabla." where codigo_snc='".$codigo_snc."' order by correlativo,cod_material";
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);


?>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<?
	titulo($modulo,"materiales_add.php?codigo_snc=".$codigo_snc."&paginam=".$paginam,"materiales_list.php?pagina=".$paginam,"13","imprimir_material.php?codigo_snc=".$codigo_snc,"cambiar_iva.php?codigo_snc=".$codigo_snc);
?>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?codigo_snc=".$codigo_snc); ?></td>
	<td width="120"><input onclick="javascript:actualizar(this);" checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="todas">Todas las palabras</td>
	<td width="150"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>
	<td colspan="3" width="386"><INPUT type="hidden" name="codigo_snc" value="<?echo $codigo_snc?>"></td>
  </tr>
</table>
<BR>
<table width="100%" cellspacing="0" border="0" cellpadding="1" align="center">
  <tbody>
    <tr class="tb-head" >
<?
foreach($titulos as $nombre){
	echo "<td><STRONG>$nombre</STRONG></td>";
}
?>
      <td></td>
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
		echo"<tr>";
	}
	foreach($indices as $campo){
		$nom_tabla=mysql_field_name($resultado,$campo);
		
		$var=$fila[$nom_tabla];
		echo"<td>$var</td>";
	}
	$codigo=$fila["cod_material"];
	icono("materiales_edit.php?codigo_snc=".$codigo_snc."&paginam=".$paginam."&codigo=".$codigo."&pagina=".$pagina, "Editar", "edit.gif");
	icono("materiales_delete.php?codigo_snc=".$codigo_snc."&paginam=".$paginam."&codigo=".$codigo."&pagina=".$pagina, "Eliminar", "delete.gif");

    echo"</tr>";
	}
}else{
	echo"<tr><td>No existen registro con la busqueda especificada</td></tr>";
}
cerrar_conexion($conexion);
?>
  </tbody>
</table>
<?
pie_pagina($url,$pagina,"&tipo=".$tipob."&des=".$des."&codigo_snc=".$codigo_snc,$num_paginas);
?>
</FORM>
</BODY>
</html>
