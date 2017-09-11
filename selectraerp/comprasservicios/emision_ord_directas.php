<?php
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
include ("../header.php");

$url="emision_ord_directas";
$modulo="Emisión de Ordenes Directas";
$tabla="ordenes";
$titulos=array("Situación","Número","Fecha","Tipo","Concepto");
$indices=array("16","0","1","8","6");

$conexion=conexion();
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = $_GET['busqueda'];

$codigo = $_GET['cod_cotizacion'];
$cod_requisicion = $_GET['cod_requisicion'];
$accion = $_GET['accion'];
$cod_ord = $_GET['cod_orden'];

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
	
	if($accion=='AnularCompra')
	{
		$var_sql="update ordenes set estado='Anulado' WHERE codigo=".$cod_ord;
		$rs = query($var_sql,$conexion);
	}
	if ($accion=='Emitir')
	{
		$rs = query("SELECT monto_orden,codigo FROM ordenes where codigo = '$cod_ord' and estado = 'Revisar'",$conexion);
		while ($row_rs = fetch_array($rs)) 
		{
			$monto_orden_adj=$row_rs['monto_orden'];
			//$cod_ord=$row_rs["codigo"];
			
			$var_sql="update ordenes set estado = 'Emitida',saldo='$monto_orden_adj' where codigo = ".$cod_ord." and cod_requi='".$cod_requisicion."' and estado <> 'Anulado'";			
			$result = query($var_sql, $conexion);		
		}

		$var_sql="update ordenes set estado = 'Emitida',saldo='$monto_orden_adj' where codigo = ".$cod_ord." and estado <> 'Anulado'";			
		$result = query($var_sql, $conexion); 
	}
	$consulta="select * from ".$tabla." where  (cod_requi='' or cod_requi=0 and cod_requi<>'2147483647') and estado='Revisar'  ORDER BY codigo";
	

}
//echo $consulta." este es el valor que muestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>

<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<TABLE width="100%">
	<TR class="row-br">
		<TD><?titulo($modulo,"adjudicar_directas.php","../menu_int.php?cod=161","28");?></TD>
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
		}else if($nom_tabla=="tipo")
		{
			$sit=$fila[$nom_tabla];
			$consul="select descripcion from ordenes_tipos where cod_orden_tipo=".$sit;
			$resul=query($consul,$conexion);
			$fila2=fetch_array($resul);
			$descripcion=$fila2["descripcion"];
			echo "<td>$descripcion</td>";
		}else if($fila["cod_proveedor"]<>0)
			{
				$consultap="select compania,rif from proveedores where cod_proveedor=".$fila["cod_proveedor"];
				$resultadop=query($consultap,$conexion);
				$filap=fetch_array($resultadop);
				if($estado=="Anulado")
				{
					$title="Anulada. Cotizacion Nro: ".$fila["codigo"]." Adjudicada a: ".$filap["compania"]." ".$filap["rif"];
				}else if ($estado=="Revisar")
				{
					$title="Orden Nro: ".$cod_ord." Asociada a Cotizacion Nro: ".$fila["codigo"]." Adjudicada a: ".$filap["compania"]." ".$filap["rif"];
				}else
				{
					$title="Cotizacion Nro: ".$fila["codigo"]." Adjudicada a: ".$filap["compania"]." ".$filap["rif"];
				}
				$var=$fila[$nom_tabla];
				echo "<td width=\"\" title=\"$title\">$var</td>";
			
		}else{
		//$var=$fila[$nom_tabla];
		//echo "<td>$var</td>";
		$var=$fila[$nom_tabla];
		echo "<td width=\"\" title=\"$title\">$var</td>";
		}
	}
	//$situacion=$fila["situacion"];
	$codigo=$fila["codigo"];
	$tipo = $fila["tipo"];
	$situacion=$fila["estado"];
	
	iconoNuevopdf("ordenesprintdirectaspdf.php?id=$codigo&desTipo=$tipo", "Imprimir Orden","ico_print.gif");
	if($situacion!='Anulado'){
		icono("adjudicar_directas.php?cod_orden=".$codigo."&accion=Modificar", "Modificar de Orden ", "edit.gif");	
		icono("javascript:confirmar('Desea Anular Esta Orden ?','emision_ord_directas.php?cod_orden=".$codigo."&accion=AnularCompra')", "Anular la Orden", "ico_cancel.gif");
		icono("emision_ord_directas.php?cod_orden=".$codigo."&accion=Emitir","Enviar La Orden a Presupuesto","ico_back.gif");
	}else{
		echo "<td></td><td></td>";
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
