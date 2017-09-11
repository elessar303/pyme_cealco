<?
session_start();
ob_start();
?>

<?php 
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;
$url="requisiciones2";
$modulo="Requisiciones";
$tabla="requisiciones";
$titulos=array("Número","Fecha","Situación","Tipo","Concepto");
$indices=array("0","9","5","10","8");

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
		$cod_unidad=$_POST['codigo'];
		$cod_centro=$_POST['cod_centro'];
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
		$consulta=$consulta." and unidad='".$cod_unidad."' and cod_centro='".$cod_centro."'";
}else{
		//echo "cod: ".$id;
		if ($rsac == '4') 
		{
			$var_sql="update requisiciones set situacion='Administracion' WHERE cod_requisicion = $id";
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
		}
	$consulta="select * from ".$tabla." where unidad='".$cod_unidad."' and TRIM(cod_centro)='".trim($cod_centro)."'";
		//echo $consulta;
}
//echo $consulta." este es el valor que muestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);

?>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<?
	$consulta_u="select descripcion from unidades where cod_unidad='".$cod_unidad."'";
	$resultado_u=query($consulta_u,$conexion);
	$fila_u=fetch_array($resultado_u);
	$des_unidad=$fila_u['descripcion'];
	$descripcion_u=$des_unidad;
	
	$consulta_c="select descripcion from centros where cod_centro='".$cod_centro."'";
	$resultado_c=query($consulta_c,$conexion);
	$fila_c=fetch_array($resultado_c);
	$des_centro=$fila_c['descripcion'];
	$descripcion_c=$des_centro;

	titulo($modulo.": ".$cod_unidad.". ".$descripcion_u." - ".$cod_centro.": ".$descripcion_c,"requisiciones_add2.php?codigo=".$cod_unidad."&cod_centro=".$cod_centro,"centros_list2.php?codigo=".$cod_unidad,"12");
?>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<TD><SELECT name="busqueda">
       <option value="concepto">Concepto</option>
		 <option value="cod_requisicion">N&uacute;mero</option>
     </SELECT></TD>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?pagina=".$pagina."&codigo=".$cod_unidad."&cod_centro=".$cod_centro);?></td>
	<td width="120"><input onclick="javascript:actualizar(this);" checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="todas">Todas las palabras</td>
	<td width="150"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>
	<td colspan="3" width="386"></td>
	<td><INPUT type="hidden" name="codigo" value="<?echo $cod_unidad?>"></td>
	<td><INPUT type="hidden" name="cod_centro" value="<?echo $cod_centro?>"></td>
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
      <td></td><td></td><td></td><td></td>


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
		if($nom_tabla=="fecha")
		{
			$fech=$fila[$nom_tabla];
			$fecha=fecha($fech);
			echo"<td>$fecha</td>";
		}
		else if($nom_tabla=="tipo")
		{
			$sit=$fila[$nom_tabla];
			$consul="select descripcion from ordenes_tipos where cod_orden_tipo=".$sit;
			$resul=query($consul,$conexion);
			$fila2=fetch_array($resul);
			$descripcion=$fila2["descripcion"];
			echo"<td>$descripcion</td>";
		}
		else if($nom_tabla=="concepto")
		{
			
			$consulp="select id_proveedor ,id_compra from compra where cod_requi=".$fila["cod_requisicion"];
			$resultp=query($consulp,$conexion);
			$fil=fetch_array($resultp);
			$cod_proveedor=$fil["cod_proveedor"];
			if($cod_proveedor<>0){
			$consultap="select compania,rif from proveedores where cod_proveedor=".$cod_proveedor;
			$resultadop=query($consultap,$conexion);
			$filap=fetch_array($resultadop);
			
			$title="Orden Nro: ".$fil["codigo"]." Adjudicada a: ".$filap["compania"]." ".$filap["rif"];
			}
			
			$var=$fila[$nom_tabla];
			echo"<td width=\"700\" title=\"$title\">$var</td>";
		}
		else
		{	
			$var=$fila[$nom_tabla];
			echo"<td>$var</td>";
		}
		
	}
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
			icono("requisiciones_edit2.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina, "Editar Requisicion", "ico_edit.gif");
			icono("javascript:confirmar('Desea Anular Esta Requisicion?','requisiciones2.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina."&rsac=6')", "Anular Requisicion", "ico_cancel.gif");
			$consulta2="select * from requisiciones_det where cod_requisicion=".$id;
			$resultado2=query($consulta2,$conexion);
			$num=mysql_num_rows($resultado2);
			if($num==0)
			{
			?>
			<td><img title="Agregar Materiales o Servicios para enviar a compra" src="../imagenes/ico_ok.gif"/></td>
			
			<?
			}else
			{
				icono("requisiciones2.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina."&rsac=4", "Enviar a Compras", "ico_ok.gif");
			}

			break;

		case "Revisar":
			iconoNuevopdf("requisicionespdf.php?id=".$id."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");
			icono("requisiciones_ver2.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina, "Ver Requisicion", "ico_view.gif");
			?>
			<td><img width="16" height="16" align="left" border="0" title="En Proceso" src="../imagenes/ico_est4.gif"/></td>
			<?
			$consulta2="select count(codigo) as valor from cotizaciones where cod_requisicion=".$id;
			$resultado2 = query($consulta2,$conexion);
			$fila2=fetch_array($resultado2);
			if($fila2['valor']==0)
			{			
				//icono("requisiciones2.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina."&rsac=5", "Enviada a Compras/Regresar", "ico_propiedades.gif");?>
			<td><img width="16" height="16" align="left" border="0" title="En Proceso" src="../imagenes/ico_est4.gif"/></td>
			<? }else{ ?>
				
				<td><img width="16" height="16" align="left" border="0" title="En Proceso" src="../imagenes/ico_est4.gif"/></td>
				<?
			}
			break;

		case "Administracion":
			iconoNuevopdf("requisicionespdf.php?id=".$id."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");
			icono("requisiciones_ver2.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina, "Ver Requisicion", "ico_view.gif");
			?>
			<td><img width="16" height="16" align="left" border="0" title="En Proceso" src="../imagenes/ico_est4.gif"/></td>
			<?
			icono("requisiciones2.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina."&rsac=5", "Enviada a Compras/Regresar", "ico_propiedades.gif");
			
			break;
	
		case "Adjudicada":
			iconoNuevopdf("requisicionespdf.php?id=".$id."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");
			icono("requisiciones_ver2.php?id=".$id."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&pagina=".$pagina, "Ver Requisicion", "ico_view.gif");
			?>
			<td><img width="16" height="16" align="left" border="0" title="Adjudicada" src="../imagenes/ico_est2.gif"/></td>
			<td><img width="16" height="16" align="left" border="0" title="Adjudicada" src="../imagenes/ico_est2.gif"/></td>
			<?

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
pie_pagina($url,$pagina,"&tipo=".$tipob."&des=".$des."&codigo=".$cod_unidad."&cod_centro=".$cod_centro."&busqueda=".$busqueda,$num_paginas);
?>
</FORM>
</BODY>
</html>
