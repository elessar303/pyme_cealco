<?php

require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
$url="municipios_list";
$modulo="Municipios";
$tabla="municipios";
$titulos=array("Código Estado","Código","Nombre");
$indices=array("1","0","2");

$conexion=conexion();
$cod_estado=@$_GET['codigo'];
$cod=1; 
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$cod_estado=$_POST['codigo'];
	}
	switch($tipob){
		case "exacta": 
			$consulta=buscar_exacta($tabla,$des,"nombre");
			break;
		case "todas":
			$consulta=buscar_todas($tabla,$des,"nombre");
			break;
		case "cualquiera":
			$consulta=buscar_cualquiera($tabla,$des,"nombre");
			break;
	}
	$consulta=$consulta." AND cod_estado='".$cod_estado."'";
}else{
$consulta="select * from ".$tabla." where cod_estado='".$cod_estado."'";
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);

include ("../header.php");
?>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<?
	titulo($modulo,"municipios_add.php?codigo=".$cod_estado,"estados_list.php?","15");
?>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?pagina=".$pagina); ?></td>
	<td width="120"><input onclick="javascript:actualizar(this);" checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="todas">Todas las palabras</td>
	<td width="150"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>
	<td colspan="3" width="386"></td>
	<td><INPUT type="hidden" name="codigo" value="<?echo $cod_estado?>"></td>
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
	$codigo=$fila["cod_estado"];
	$cod_municipio=$fila["cod_municipio"];
	icono("municipios_edit.php?codigo=".$codigo."&cod_municipio=".$cod_municipio."&pagina=".$pagina, "Editar", "edit.gif");
	icono("municipios_delete.php?codigo=".$codigo."&cod_municipio=".$cod_municipio."&pagina=".$pagina, "Eliminar", "delete.gif");
	//icono("municipios_list.php?codigo=".$codigo, "Municipios", "view.gif");

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
pie_pagina($url,$pagina,"&tipo=".$tipob."&des=".$des."&codigo=".$cod_estado,$num_paginas);
?>
</FORM>
</BODY>
</html>
