<?php
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
include ("../header.php");

$url="emision_orden_servivios";
$modulo="Emisión de Ordenes de Servicio";
$tabla="requisiciones";
$titulos=array("Situación","Número","Fecha","Tipo","Concepto");
$indices=array("5","0","9","10","8");

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
	$consulta=" as r INNER JOIN cotizaciones as c on r.cod_requisicion = c.cod_requisicion and c.estatus='Revisar' ";
	switch($tipob){
		case "exacta":
			$consul=buscar_exacta($tabla,$des,$busqueda);
			break;
		case "todas":
			$consul=buscar_todas($tabla,$des,$busqueda);
			break;
		case "cualquiera":
			$consul=buscar_cualquiera($tabla,$des,$busqueda);
			break;
	}
	$consulta=$consul."and tipo <> 3 and tipo <> 1 and situacion='Revisar'";
}else{
	if($accion=='Anular')
	{
		$var_sql="update cotizaciones set estatus='Seleccionada' WHERE cod_requisicion =".$cod_requisicion." and estatus='Revisar'";
		$rs = query($var_sql,$conexion);
		$consulta="select * from cotizaciones where cod_requisicion=$cod_requisicion";
		$rcot=query($consulta,$conexion);
		while($valcot=fetch_array($rcot)){
			$cod_cotiza=$valcot['codigo'];
			$consulta="delete from analisis_cotizaciones where cod_cotizacion=$cod_cotiza";
			$con=query($consulta,$conexion);
			$var_sql="update cotizaciones_detalles set estatus='Creada' WHERE cod_cotizacion=".$cod_cotiza." and estatus='Revisar'";
			$rs = query($var_sql,$conexion);
		}

		
	}
	if($accion=='AnularCompra')
	{
		$var_sql="update ordenes set estado='Anulado' WHERE codigo=".$cod_ord;
		$rs = query($var_sql,$conexion);
	}
	if ($accion=='Emitir')
	{
		$rs = query("SELECT monto_orden FROM ordenes where cod_requi='$codigo' AND  codigo = '$cod_ord' and estado = 'Revisar'",$conexion);
		while ($row_rs = fetch_array($rs)) 
		{
			$monto_orden_adj=$row_rs['monto_orden'];		
		}

		$var_sql="update ordenes set estado = 'Emitida' where codigo = ".$cod_ord." and estado <> 'Anulado'";			
		$result = query($var_sql, $conexion); 
	
		$var_sql="update requisiciones set situacion = 'Adjudicada' where cod_requisicion=$cod_requisicion";
		$result = query($var_sql, $conexion); 	
	}

$consulta="select * from ".$tabla." as r INNER JOIN cotizaciones as c on r.cod_requisicion = c.cod_requisicion and c.estatus='Revisar' where tipo <> 3 and tipo <> 1 and situacion='Revisar'";
}
//echo $consulta." este es el valor que muestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>

<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<TABLE width="100%">
	<TR class="row-br">
		<TD><?titulo($modulo,"","../menu_int.php?cod=2","28");?></TD>
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
		}
		else if($nom_tabla=="concepto")
		{
			$consulp="select codigo,estado from ordenes where cod_requi=".$fila["cod_requisicion"]." and estado<>'Anulado' and cod_cotizacion=".$fila["codigo"];
			//echo $consulp."<br>";
			$resultp=query($consulp,$conexion);
			$fil=fetch_array($resultp);
			$cod_ord=$fil["codigo"];
			$estado=$fil["estado"];
			
			if($fila["cod_proveedor"]<>0)
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
				echo "<td width=\"700\" title=\"$title\">$var</td>";
			}else
			{
				$var=$fila[$nom_tabla];
				echo "<td width=\"700\" title=\"$title\">$var</td>";
			}
		}else{
		$var=$fila[$nom_tabla];
		echo "<td>$var</td>";
		}
	}
	$situacion=$fila["situacion"];
	$codigo=$fila["cod_requisicion"];
	$tipo = $fila["tipo"];
	$conTipo = "SELECT * FROM ordenes_tipos WHERE cod_orden_tipo='".$tipo."'";
	$resTipo = query($conTipo, $conexion);
	$filaTipo = fetch_array($resTipo);
	$desTipo = $filaTipo['descripcion'];
	
	$consul="select * from cotizaciones where cod_requisicion='".$codigo."' and estatus='Revisar'";
	$resul=query($consul,$conexion);
	$numero_cotizaciones=num_rows($resul);
	
	$consul="select * from ordenes where cod_requi='".$codigo."' and estado='Revisar'";
	$resul=query($consul,$conexion);
	$numero_ordenes=num_rows($resul);

	$consul="select * from ordenes where cod_requi='".$codigo."' and cod_cotizacion='".$fila['codigo']."' and estado='Revisar'";
	$resul=query($consul,$conexion);
	$fila2=fetch_array($resul);
	$cod_ord=$fila2["codigo"];
	//icono("comparar_cotizaciones.php?cod_requisicion=$codigo&desTipo=$desTipo", "Comparar Cotizaciones","16.png");
	if (mysql_num_rows($resul))
	{

			iconoNuevopdf("ordenesprintpdf3.php?id=$cod_ord&desTipo=$desTipo", "Imprimir Orden","ico_print.gif");
			//iconoNuevo("ordenesprint3_f.php?id=$codigo&desTipo=$desTipo", "Imprimir Orden en Formato","ico_print.gif");
			icono("javascript:confirmar('Seguro desea devolver Esta Orden para Editar? ','emision_orden_servicios.php?cod_requisicion=".$codigo."&cod_orden=".$cod_ord."&accion=AnularCompra')", "Devolver Orden para Editar", "ico_previous.gif");
			if($numero_cotizaciones==$numero_ordenes)
			{
				icono("emision_orden_servicios.php?cod_requisicion=".$codigo."&cod_orden=".$cod_ord."&accion=Emitir","Enviar Las Ordenes a Presupuesto","ico_back.gif");
			}

	}else
	{	if($situacion=='Revisar')
		{
			icono("emision_orden_servicios.php?cod_requisicion=".$codigo."&cod_cotizacion=".$fila['codigo']."&accion=Anular","Anular Asignacion de Proveedor","ico_cancel.gif");
		}
		?>
		<td><img src="../imagenes/ico_est6.gif"/></td>
		<?
		//icono("ordenesprint3.php?codigo=$codigo", "Imprimir Orden","ico_print.gif");
		icono("adjudicar_orden_servicios.php?cod_requisicion=".$codigo."&codigo=".$fila["codigo"], "Adjudicar Orden", "ico_ok.gif");	
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