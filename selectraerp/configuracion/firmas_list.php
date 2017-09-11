<?php
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
include ("../header.php");

$url="firmas_list";
$modulo="Firmas Reportes";
$tabla="firmas";
$titulos=array("Número","Reporte","Descripción","Cargo Firmante","Nombre Firmante","Orden Reporte");
$indices=array("0","5","1","2","3","4");

$conexion=conexion_conf();
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = $_GET['busqueda'];
if (isset($_GET['codigo'])){
	$codigo=$_GET['codigo'];
	$consulta="DELETE FROM $tabla WHERE cod_firmas=$codigo";
	$resultado=query($consulta,$conexion);
}
if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$busqueda = $_POST['busqueda'];
	}
	switch($tipob){
		case "exacta":
			$consul=buscar_exacta($tabla,$des,$busqueda);
			//$consulta="select * from ".$tabla." where tipo <> 3 and situacion='Revisar' and concepto LIKE '%".$des."%'";
			$consulta=$consul."";
			break;
		case "todas":
			$consul=buscar_todas($tabla,$des,$busqueda);
			$consulta=$consul."";
			break;
		case "cualquiera":
			$consul=buscar_cualquiera($tabla,$des,$busqueda);
			$consulta=$consul."";
			break;
	}
	$consulta=$consulta.' and modulo="Administrativo"';
}else{
$consulta="select * from ".$tabla." WHERE modulo='Administrativo' ORDER BY cod_firmas";
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>
<script>
function eliminar(url){
	if (confirm("Esta seguro que desea eliminar esta Firma ?"))
	{					
		location.href=url;
	}
}
</script>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina; ?>" method="POST" target="_self">
<TABLE width="100%">
	<TR class="row-br">
		<TD><?titulo($modulo,"firmas_add.php","../menu_int.php?cod=10","28");?></TD>
	</TR>
</TABLE>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><SELECT name="busqueda">
		<option value="cargo_persona">Cargo Firmante</option>
		<option value="cod_firmas">N&uacute;mero</option>
     </SELECT></td>
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
<?
foreach($titulos as $nombre){
	echo "<td><STRONG>$nombre</STRONG></td>";
}
?>
      <td></td>
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
		echo "<tr>";
	}
	foreach($indices as $campo){
		$nom_tabla=mysql_field_name($resultado,$campo);
		if($nom_tabla=="cod_firmas")
		{
			$codigo=$fila[$nom_tabla];;
			echo "<td>$codigo</td>";
		}else{
		$var=$fila[$nom_tabla];
		echo "<td>$var</td>";
		}
	}
	
	
	icono("firmas_edit.php?codigo=".$codigo."&pagina=".$pagina, "Editar", "edit.gif");
	icono("javascript:eliminar('firmas_list.php?codigo=$codigo&pagina=$pagina')", "Eliminar", "delete.gif");
	

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
	pie_pagina($url,$pagina,"&tipo=".$tipob."&des=".$des."&busqueda=".$busqueda,$num_paginas);
?>
</FORM>

</BODY>
</HTML>