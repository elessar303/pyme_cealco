<?php 

//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$url="proveedores_list";
$modulo="Proveedores";
$tabla="proveedores";
$titulos=array("Código","Compañia","Siglas","R.I.F");
$indices=array("0","1","2","3");
$codigo=$_GET['codigo'];
$conexion=conexion();
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = $_GET['busqueda'];
if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$busqueda = $_POST['busqueda'];
	}
	switch($tipob){
		case "exacta":
			$consulta=buscar_exacta($tabla,$des,$busqueda);
			break;
		case "todas":
			$consulta=buscar_todas($tabla,$des,$busqueda);
			break;
		case "cualquiera":
			$consulta=buscar_cualquiera($tabla,$des,$busqueda);
			break;
	}
}else{
$consulta="select * from ".$tabla;
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>
<SCRIPT language="JavaScript" type="text/javascript" src="mostrar_detalles.js"></SCRIPT>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<?titulo($modulo,"proveedores_add.php","../menu_int.php?cod=2","28","reporte_pro.php");?>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><SELECT name="busqueda">
		<option value="compania">Compania / Nombre</option>
		<option value="rif">R.I.F. / C.I.</option>
     </SELECT></td>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?pagina=".$pagina."&codigo=".$codigo); ?></td>
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
		$var=$fila[$nom_tabla];
		echo "<td>$var</td>";
	}
	$codigo=$fila["cod_proveedor"];
	$nombrePro=$fila["compania"];
	//$descripcion=$fila["descripcion"];
	//echo $descripcion;
	icono("javascript:detalles('codigo=".$codigo."&compania=".$nombrePro."')", "Mostrar Detalles", "view.gif");
	icono("proveedores_cuentas_list.php?codigo=$codigo", "Cuentas Bancarias", "ico_propiedades.gif");
	icono("proveedores_telef_list.php?codigo=$codigo", "Teléfonos Secundarios", "ico_cel.gif");
	icono("proveedores_clasif_list.php?codigo=$codigo", "Clasificación Múltiple", "list.gif");
	icono("proveedores_add_da.php?codigo=$codigo&compania=$nombrePro", "Datos Adicionales", "ir_a.gif");
	icono("proveedores_edit.php?codigo=".$codigo, "Editar", "edit.gif");
	//icono("proveedores_delete.php?codigo=".$codigo, "Eliminar", "delete.gif");
	iconoNuevo("../fpdf/reporte_proveedores_constancia.php?codigo=".$codigo, "Contancia de Inscripción", "imprimir.png");
	iconoNuevo("proveedores_reporte_registro.php?codigo=$codigo", "Registro Interno de Empresas", "imprimir.png");
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
	pie_pagina($url,$pagina,"&tipo=".$tipob."&des=".$des."&busqueda=".$busqueda."&codigo=".$codigo,$num_paginas);
?>
</FORM>
<TABLE width="100%">
	<TR>
		<TD id="detalles_proveedor"></TD>
	</TR>
</TABLE>
<TABLE width="100%">
	<TR>
		<TD id="detalles_orden_proveedor"></TD>
	</TR>
</TABLE>
<TABLE width="100%">
	<TR>
		<TD id="detalles_odp_proveedor"></TD>
	</TR>
</TABLE>

</BODY>
</HTML>