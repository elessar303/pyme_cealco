<?php
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
include ("../header.php");

$url="requisiciones_compras";
$modulo="Emisión de Requisiciones Compras";
$tabla="requisiciones";
$titulos=array("Situación","Número","Fecha","Concepto");
$indices=array("5","0","9","8");

$conexion=conexion();
$cod_unidad=@$_GET['codigo'];
$cod_centro=@$_GET['cod_centro'];
$id=@$_GET['id'];
$rsac=@$_GET['rsac'];
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$busqueda = $_POST['busqueda'];
	}
	$consulta="where cod_requi=''";
	switch($tipob){
		case "exacta":
			$consul=buscar_exacta_join($tabla,$des,$busqueda,$consulta,$orden);
			break;
		case "todas":
			$consul=buscar_todas($tabla,$des,$busqueda);
			break;
		case "cualquiera":
			$consul=buscar_cualquiera($tabla,$des,$busqueda);
			break;
	}
	$consulta=$consul." ORDER BY codigo ";
	//ECHO $consulta;
}else{
	

	//echo "cod: ".$id;
	if ($rsac == '4') 
	{
		$var_sql="update requisiciones set situacion='Revisar' WHERE cod_requisicion = $id";
		$rs = query($var_sql,$conexion);
	}
	
	if ($rsac == '5') 
	{
		$var_sql="update requisiciones set situacion='Registrada' WHERE cod_requisicion = $id";
		$rs = query($var_sql,$conexion);
	}
	
	if ($rsac == '6') 
	{
		$var_sql="update requisiciones set situacion='Anulado' WHERE cod_requisicion = $id";
		$rs = query($var_sql,$conexion);
		
		$var_sql="update requisiciones set situacion='Revisar',req_compra='' WHERE req_compra = $id";
		$rs = query($var_sql,$conexion);

	}
	//$consulta="select * from ".$tabla." where unidad='".$cod_unidad."' and TRIM(cod_centro)='".trim($cod_centro)."'";
		//echo $consulta;

	$consulta="select * from ".$tabla." where  tipo=1 and req_compra='P'  ORDER BY cod_requisicion";
	

}
//echo $consulta." este es el valor que muestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>

<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<TABLE width="100%">
	<TR class="row-br">
		<TD><?titulo($modulo,"requisiciones_compras_add.php","../modulos/principal/?opt_menu=84","160");?></TD>
	</TR>
</TABLE>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><SELECT name="busqueda">
		<option value="concepto">Concepto</option>
		<option value="cod_requisicion">N&uacute;mero</option>
     </SELECT></td>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?pagina=".$pagina); ?></td>
	<td width="120"><input onclick="javascript:actualizar(this);" checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"></td>
	<td width="150"></td>
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
		if($nom_tabla=="fecha")
		{
			$fech=$fila[$nom_tabla];
			$fecha=fecha($fech);
			echo "<td>$fecha</td>";
		}else{
		//$var=$fila[$nom_tabla];
		//echo "<td>$var</td>";
		$var=$fila[$nom_tabla];
		echo "<td width=\"\" title=\"$title\">$var</td>";
		}
	}
	//$situacion=$fila["situacion"];
	$cod_unidad=$fila["unidad"];
	$cod_centro=$fila["cod_centro"];
	$id=$fila["cod_requisicion"];
	$situacion=$fila["situacion"];
	switch($situacion)
	{
		case "Anulado":
			iconoNuevopdf("requisicionespdf.php?id=".$id."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");
			?>
			<td><img width="16" height="16" align="left" border="0" title="Requisición Anulada" src="../imagenes/ico_est6.gif"/></td>
			<td><img width="16" height="16" align="left" border="0" title="Requisición Anulada" src="../imagenes/ico_est6.gif"/></td>
			<td><img width="16" height="16" align="left" border="0" title="Requisición Anulada" src="../imagenes/ico_est6.gif"/></td>
			<?
			
			break;
		case "Registrada":
			iconoNuevopdf("requisicionespdf.php?id=".$id."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");
			icono("requisiciones_edit.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina, "Editar Requisicion", "ico_edit.gif");
			icono("javascript:confirmar('Desea Anular Esta Requisicion?','requisiciones_compras.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina."&rsac=6')", "Anular Requisicion", "ico_cancel.gif");
			$consulta2="select * from requisiciones_det where cod_requisicion=".$id;
			$resultado2=query($consulta2,$conexion);
			$num=mysql_num_rows($resultado2);
			if($num==0)
			{
			?>
				<td><img title="No tiene Materiales" src="../imagenes/ico_ok.gif"/></td>
			<?
			}else
			{
				
				icono("requisiciones_compras.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina."&rsac=4", "Enviar a Compras", "ico_ok.gif");
			}

			break;

		case "Revisar":
			iconoNuevopdf("requisicionespdf.php?id=".$id."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");
			icono("requisiciones_ver.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina, "Ver Requisicion", "ico_view.gif");
			?>
			<td><img width="16" height="16" align="left" border="0" title="En Proceso" src="../imagenes/ico_est4.gif"/></td>
			<?
			$consulta2="select count(codigo) as valor from cotizaciones where cod_requisicion=".$id;
			$resultado2 = query($consulta2,$conexion);
			$fila2=fetch_array($resultado2);
			if($fila2['valor']==0)
			{			
				icono("requisiciones_compras.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina."&rsac=5", "Enviada a Compras/Regresar", "ico_propiedades.gif");
			}else{
				?>
				<td><img width="16" height="16" align="left" border="0" title="En Proceso" src="../imagenes/ico_est4.gif"/></td>
				<?
			}
			break;

		case "Administracion":
			iconoNuevopdf("requisicionespdf.php?id=".$id."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");
			icono("requisiciones_ver.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina, "Ver Requisicion", "ico_view.gif");
			?>
			<td><img width="16" height="16" align="left" border="0" title="En Proceso" src="../imagenes/ico_est4.gif"/></td>
			<?
			icono("requisiciones_compras.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina."&rsac=5", "Enviada a Compras/Regresar", "ico_propiedades.gif");
			
			break;
	
		case "Adjudicada":
			iconoNuevopdf("requisicionespdf.php?id=".$id."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");
			icono("requisiciones_ver.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina, "Ver Requisicion", "ico_view.gif");
			?>
			<td><img width="16" height="16" align="left" border="0" title="Adjudicada" src="../imagenes/ico_est2.gif"/></td>
			<td><img width="16" height="16" align="left" border="0" title="Adjudicada" src="../imagenes/ico_est2.gif"/></td>
			<?

			break;		
	}
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