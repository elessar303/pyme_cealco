<?php 
$url="cotizaciones_list";
$modulo="Cotizaciones";
$tabla="cotizaciones";
$titulos=array("Codigo","Proveedor","Fecha","Tiempo de Entrega","Dias de Pago","Total Compra");
$indices=array("0","1","2","5","10","8");

//DECLARACION DE LIBRERIAS
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
$cod_requisicion=@$_GET['cod_requisicion'];
$conexion=conexion();
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$codigo=@$_GET['codigo'];
$borrar=@$_GET['borrar'];

if (isset($_GET['esta'])){
	$codigo=$_GET['codigo'];
	$cod_requisicion=$_GET['cod_requisicion'];
	$conexion=conexion();
	if ($_GET['esta']==1){ //estoy seleccionando
		$var_sql="update cotizaciones set estatus='Seleccionada' where codigo=$codigo";
		$rs = query($var_sql,$conexion);
	}
	else{ //estoy anulando seleccion
		$var_sql="update cotizaciones set estatus='Creada'  where codigo=$codigo";
		$rs = query($var_sql,$conexion);
	}
}
if (isset($_GET['dire'])){
	$codigo=$_GET['codigo'];
	$cod_requisicion=$_GET['cod_requisicion'];
	$consulta="update  cotizaciones set estatus='Revisar' where codigo=".$codigo;
	$cotiza=query($consulta,$conexion);
	$consulta="update  cotizaciones_detalles set estatus='Revisar' where cod_cotizacion=".$codigo;
	$cotiza=query($consulta,$conexion);
	echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		     alert(\"Se Adjudico de forma directa la Cotización Nº$codigo\");
		     location.href=\"cotizaciones_list.php?codigo=$codigo&cod_requisicion=$cod_requisicion\" </SCRIPT>";
}

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
	if ($borrar == '1') 
	{
		$var_sql="delete from cotizaciones_detalles WHERE cod_cotizacion = $codigo";
		$rs = query($var_sql,$conexion);
		$var_sql="delete from ".$tabla." WHERE codigo = $codigo";
		$rs = query($var_sql,$conexion);
		
	}
$consulta="select * from ".$tabla." where cod_requisicion=".$cod_requisicion;
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);

include ("../header.php");
?>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<?
	$consulta2="select count(codigo) as cantidad from cotizaciones where cod_requisicion=".$cod_requisicion." and estatus='Revisar'";
	$resultado2=query($consulta2,$conexion);
	$fila2=fetch_array($resultado2);
	
	//if($fila2['cantidad']==0)
	//{
	titulo($modulo." Asociadas a Requisicion Nro ".$cod_requisicion,"cotizaciones_add.php?cod_requisicion=".$cod_requisicion,"analisis_cotizaciones.php","53");
	//}else{
	//titulo($modulo." Asociadas a Requisicion Nro ".$cod_requisicion,"","analisis_cotizaciones.php","53");
	//}

?>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<!--<td><SELECT name="busqueda">
		<option value="Programa">C&oacute;digo Proyecto</option>
		<option value="Denominacion">Nombre Proyecto</option>
        </SELECT></td>-->
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
		if($nom_tabla=='Tipo')
		{	
			if($var==0)
			{
			?>
			<td><?echo "Proyecto";?></td>
			<?
			}else if ($var==1)
			{
			?>
			<td><?echo "Acción Centralizada";?></td>
			<?
			}
		}else
		{
			$var=$fila[$nom_tabla];
			if($nom_tabla=="cod_proveedor"){		
				$consultaproveedor="select * from proveedores where cod_proveedor=$var";
				$resultado_proveedor=query($consultaproveedor,$conexion);
				$fila_proveedor=fetch_array($resultado_proveedor);
				echo"<td>".$fila_proveedor['compania']."</td>";
			}else{
				if ($nom_tabla=="fecha"){
				echo"<td>".fecha($var)."</td>";
				}else{
					echo"<td align=\"center\">$var</td>";
				}
			}
		}

	}
	$codigo=$fila["codigo"];
	$cod_requisicion=$fila["cod_requisicion"];
	
	$consulta2="select count(codigo) as cantidad from cotizaciones where cod_requisicion=".$cod_requisicion." and estatus='Revisar'";
	$resultado2=query($consulta2,$conexion);
	$fila2=fetch_array($resultado2);
	
	iconoNuevopdf("cotizaciones_pdf.php?codigo=".$codigo."&cod_requisicion=".$cod_requisicion, "Imprimir", "ico_print.gif");

	switch ($fila['estatus'])
	{
		case "Creada":
			icono("cotizaciones_edit.php?codigo=".$codigo."&cod_requisicion=".$cod_requisicion."&pagina=".$pagina, "Editar", "edit.gif");
			icono("javascript:confirmar('Desea Eliminar esta Cotización? ?','cotizaciones_list.php?codigo=".$codigo."&cod_requisicion=".$cod_requisicion."&pagina=".$pagina."&borrar=1')", "Eliminar", "delete.gif");
			icono("cotizaciones_list.php?codigo=".$codigo."&cod_requisicion=".$cod_requisicion."&esta=1", "Seleccionar Cotización", "ico_ok.gif");
			icono("cotizaciones_list.php?codigo=".$codigo."&cod_requisicion=".$cod_requisicion."&dire=1", "Adjudicacion de Directa de Cotización", "038.png");
			break;
	
		case "Seleccionada":

			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			if($fila2['cantidad']==0){
			icono("cotizaciones_list.php?codigo=".$codigo."&cod_requisicion=".$cod_requisicion."&esta=2", "Anular Selección", "ico_cancel.gif");
			}else{
			echo "<td></td>";
			}
			break;

		case "Revisar":
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			break;
	}
	
    echo "</tr>";
	}
}else{
	echo"<tr><td>No existen registro con la busqueda especificada</td></tr>";
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
</html>
