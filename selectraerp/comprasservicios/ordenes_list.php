<?php
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
include ("../header.php");

$url="ordenes_list";
$modulo="Emision de Ordenes";
$tabla="ordenes";
$titulos=array("Codigo","Codigo Ref.","Req.","Fecha","Tipo","Proveedor","Estado","Monto Orden","Saldo");
$indices=array("0","18","15","1","8","13","16","4","17");

$codigo=$_GET['codigo'];
$conexion=conexion();
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$rsac=@$_GET['rsac'];
$pagina=@$_GET['pagina'];
$busqueda = $_GET['busqueda'];

if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$busqueda = $_POST['busqueda'];
		//$busqueda2 = $_POST['busqueda2'];
		
		if($busqueda == 'tipo')
		{
			$busqueda="descripcion";
			$consulta=buscar_exacta("ordenes_tipos",$des,$busqueda);
			$resultado=query($consulta,$conexion);
			$fila=fetch_array($resultado);
			$cod=$fila['cod_orden_tipo'];
			$des=$cod;
			$busqueda="tipo";
			//echo $des." - ".$busqueda;
			
		}else 
		if (($busqueda != 'codigo')&&($busqueda != 'codigo_ref')&&($busqueda != 'cod_requi')&&($busqueda != 'cod_provee')){
			$des = $busqueda;
			$busqueda = 'estado';
		}
		/*
		if ($busqueda2 != ''){
			$des = $busqueda;
			$busqueda = 'tipo';
		}
		*/
	}
	switch($tipob){
		case "exacta":
			//echo $consulta=buscar_exacta($tabla,$des,$busqueda);
			$consulta="select * from ".$tabla." where $busqueda='$des'";
			//$consulta=$consul."and tipo <> 3 and situacion='Revisar'";
			break;
		case "todas":
			$consulta=buscar_todas($tabla,$des,$busqueda);
			//$consulta=$consul."and tipo <> 3 and situacion='Revisar'";
			break;
		case "cualquiera":
			$consulta=buscar_cualquiera($tabla,$des,$busqueda);
			//$consulta=$consul."and tipo <> 3 and situacion='Revisar'";
			break;
	}
}else{
	if ($rsac == '4') 
	{	
		$var_sql="update ordenes set estado = 'Anulado' where codigo = $codigo";			
		$result = query($var_sql, $conexion); 	
		$rs = query("SELECT cod_requi FROM ordenes where codigo = $codigo",$conexion);
		$row_rs = fetch_array($rs); 
		$x_cod_riqui=$row_rs['cod_requi'];
		$var_sql="update requisiciones set situacion='Revisar' WHERE cod_requisicion = $x_cod_riqui";
		$rs = query($var_sql,$conexion);

	}
	if ($rsac == '5')
	{
		//$rs = query("SELECT cod_requi FROM ordenes where codigo = $codigo",$conexion);
		//$row_rs = fetch_array($rs); 
		//$x_cod_riqui=$row_rs['cod_requi'];
		
		//$var_sql="update requisiciones set situacion='Registrada' WHERE cod_requisicion = $x_cod_riqui";
		//$rs = query($var_sql,$conexion);
		$var_sql="update ordenes set estado = 'Revisar' where codigo = $codigo";			
		$result = query($var_sql, $conexion); 
		$rs = query("SELECT cod_requi FROM ordenes where codigo = $codigo",$conexion);
		$row_rs = fetch_array($rs); 
		$x_cod_riqui=$row_rs['cod_requi'];
		$var_sql="update requisiciones set situacion='Revisar' WHERE cod_requisicion = $x_cod_riqui";
		$rs = query($var_sql,$conexion);
	}
		
		
	$consulta="select * from ".$tabla;
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>

<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<TABLE width="100%">
	<TR class="row-br">
		<TD><?titulo("Consulta General de Ordenes","","../menu_int.php?cod=2","86");?></TD>
	</TR>
</TABLE>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><SELECT name="busqueda">
		<option value="codigo">C&oacute;digo</option>
		<option value="codigo_ref">C&oacute;digo Ref.</option>
        <option value="cod_requi">C&oacute;digo Req.</option>
		<option value="tipo">Tipo</option>
		<option value="cod_provee">Cod. Proveedor</option>
		<option value="0" disabled="disabled">--- Estado ---</option>
		<option value="Revisar">Revisar</option>
		<option value="Emitida">Emitida</option>
		<option value="Comprometida">Comprometida</option>
		<option value="Pagada">Pagada</option>
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
		}else if($nom_tabla=="cod_provee")
		{
			//echo "pajue";
			$cod=$fila[$nom_tabla];
			//echo $cod."  - ";
			$consul="select * from proveedores where cod_proveedor='".$cod."'";
			$resul=query($consul,$conexion);
			$fila2=fetch_array($resul);
			$compania=$fila2["compania"];
			$rif=$fila2["rif"];

			echo"<td width=\"300\">$compania  $rif</td>";

		}else if($nom_tabla=="tipo")
		{
			$sit=$fila[$nom_tabla];
			$consul="select descripcion from ordenes_tipos where cod_orden_tipo=".$sit;
			$resul=query($consul,$conexion);
			$fila2=fetch_array($resul);
			$descripcion=$fila2["descripcion"];
			echo "<td>$descripcion</td>";
		}else if($nom_tabla=="monto_orden")
		{
			$monto=$fila[$nom_tabla];
			$monto_format=number_format($monto,2,',','.');
			echo "<td>$monto_format</td>";
		}else if($nom_tabla=="saldo")
		{
			$saldo=$fila[$nom_tabla];
			$saldo_format=number_format($saldo,2,',','.');
			echo "<td>$saldo_format</td>";
		}
		else{
		$var=$fila[$nom_tabla];
		echo "<td>$var</td>";
		}
	}
	//$situacion=$fila["situacion"];
	$cod_ord=$fila["codigo"];
	$tipo = $fila["tipo"];
	$estado=$fila["estado"];
	$cotizacion=$fila["cod_cotizacion"];
	$conTipo = "SELECT * FROM ordenes_tipos WHERE cod_orden_tipo='".$tipo."'";
	$resTipo = query($conTipo, $conexion);
	$filaTipo = fetch_array($resTipo);
	$desTipo = $filaTipo['descripcion'];
	$cod_requisicion=$fila["cod_requi"];
	
	//$descripcion=$fila["descripcion"];
	//echo $descripcion;
	
	switch($tipo)
	{
		case 1:
			
			if($estado!="Revisar"){
			iconoNuevopdf("analisispdf.php?cod_requisicion=$cod_requisicion&des_tipo=$desTipo", "Imprimir Analisis","ico_print.gif");
			}else{ ?><td><img src="../imagenes/ico_est6.gif"/></td><?}
			
			
			iconoNuevopdf("ordenesprintpdf3.php?id=$cod_ord&desTipo=$desTipo", "Imprimir Orden","ico_print.gif");
			iconoNuevopdf("ordenesprintpdf3_puntos_compras.php?id=$cod_ord&desTipo=$desTipo", "Imprimir Orden","ico_print.gif");
			break;
		case 2:
			if($estado!="Revisar"){
			iconoNuevopdf("analisispdf.php?cod_requisicion=$cod_requisicion&des_tipo=$desTipo", "Imprimir Analisis","ico_print.gif");
			}else{ ?><td><img src="../imagenes/ico_est6.gif"/></td><?}
			
			
			iconoNuevopdf("ordenesprintpdf3.php?id=$cod_ord&desTipo=$desTipo", "Imprimir Orden","ico_print.gif");
	
			iconoNuevopdf("ordenesprintpdf3_puntos_servicios.php?id=$cod_ord&desTipo=$desTipo", "Imprimir Orden Preforma","ico_print.gif");
		break;
		case 8:
			?><td><img src="../imagenes/ico_est6.gif"/></td><?
			iconoNuevo("ordenesprint5.php?id=$cod_ord&desTipo=$desTipo", "Imprimir Orden","ico_print.gif");
			break;
		default:
			if($estado!="Revisar"){
			iconoNuevopdf("analisispdf.php?cod_requisicion=$cod_requisicion&des_tipo=$desTipo", "Imprimir Analisis","ico_print.gif");
			}else{ ?><td><img src="../imagenes/ico_est6.gif"/></td><?}
			
			
			iconoNuevopdf("ordenesprintpdf3.php?id=$cod_ord&desTipo=$desTipo", "Imprimir Orden","ico_print.gif");
			iconoNuevopdf("ordenesprintpdf3_puntos_otros.php?id=$cod_ord&desTipo=$desTipo", "Imprimir Orden Preforma","ico_print.gif");
			
			break;
	}
	
	//echo $estado;
	if($estado=="Revisar"||$estado=="REVISAR")
	{
		//echo "pajuo";
		
		icono("ordenes_list.php?codigo=".$cod_ord."&pagina=".$pagina."&rsac=4", "Anular", "ico_cancel.gif");
		
	}else
	{
		
		?><td><img src="../imagenes/ico_est6.gif"/></td><?
	}
	if(($estado=="Emitida"||$estado=="EMITIDA")&&($cotizacion==0))
	{
		
		icono("ordenes_list.php?codigo=".$cod_ord."&pagina=".$pagina."&rsac=5", "Regresar a Compras", "ico_ok.gif");
	}else if (($estado=="Emitida"||$estado=="EMITIDA")&&($cotizacion<>0))
	{
		icono("javascript:confirmar('Desea Anular Esta Orden ?','ordenes_list.php?codigo=".$cod_ord."&pagina=".$pagina."&rsac=4')", "Anular Orden", "ico_cancel.gif");
	}else
	{
		?><td><img src="../imagenes/ico_est6.gif"/></td><?
	}
	
	//iconoNuevo("ordenesprint3_f.php?id=$codigo&desTipo=$desTipo", "Imprimir Orden en Formato","ico_print.gif");
	//icono("adjudicar_eo.php?cod_riqui=$codigo"."&acc=adj", "Enviar a presupuesto", "ico_back.gif");
	//icono("adjudicar_eo.php?cod_riqui=$codigo","Editar Orden", "ico_edit.gif");
		/*?>
		<td><img src="../imagenes/ico_est6.gif"/></td>
		<td><img src="../imagenes/ico_est6.gif"/></td>
		<td><img src="../imagenes/ico_est6.gif"/></td>
		<?*/
		//icono("ordenesprint3.php?codigo=$codigo", "Imprimir Orden","ico_print.gif");
		//icono("adjudicar_eo.php?cod_riqui=$codigo", "Adjudicar Orden", "ico_ok.gif");	
	}
	echo "</tr>";

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

</BODY>
</HTML>