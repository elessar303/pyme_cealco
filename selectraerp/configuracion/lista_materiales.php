<?php 
$url="lista_materiales";
$modulo="Listado de Materiales";
$tabla="materiales";
$titulos=array("Código SNC", "Código","Descripción","Cántidad Existente","Monto Min.","Monto Máx.");
$indices=array("4", "0","1","6","7","8");

//DECLARACION DE LIBRERIAS
require_once '../lib/common.php';
include ("../header.php");
$conexion = conexion();
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$paginam = @$_GET['paginam'];
$buscar = $_GET['busqueda'];
$pagina = @$_GET['pagina'];

if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$buscar =$_POST['busqueda'];
	}
	switch($tipob){
		case "exacta":
			$consulta=buscar_exacta($tabla,$des,$buscar);
			break;
		case "todas":
			$consulta=buscar_todas($tabla,$des,$buscar);
			break;
		case "cualquiera":
			$consulta=buscar_cualquiera($tabla,$des,$buscar);
			break;
	}
}else{
	$consulta="select * from ".$tabla." order by codigo_snc,correlativo";
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);

?>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<?
	titulo($modulo,"","../menu_int.php?cod=10","83","../fpdf/imprimir_materialpdf.php?codigo_snc=".$codigo_snc);
?>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
		<TD><SELECT name="busqueda">
       <option value="descripcion">Descripci&oacute;n</option>
		 <option value="cod_material">C&oacute;digo</option>
     </SELECT></TD>
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
    <tr class="tb-head">
<?
foreach($titulos as $nombre){
	echo "<td><STRONG>$nombre</STRONG></td>";
}
?>
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
	$codigo_snc=$fila["codigo_snc"];
	$codigo=$fila["cod_material"];
	icono("lista_materiales_edit.php?codigo_snc=".$codigo_snc."&codigo=".$codigo."&pagina=".$pagina, "Editar", "edit.gif");
	
	//icono("materiales_delete.php?codigo=".$codigo."&pagina=".$pagina, "Eliminar", "delete.gif");

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
	pie_pagina($url,$pagina,"tipo=".$tipob."&des=".$des."&busqueda=".$buscar,$num_paginas);
?>
</FORM>
</BODY>
</html>