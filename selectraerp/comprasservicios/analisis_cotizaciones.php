<?
if (!isset($_SESSION)) 
{
  session_start();
}
?>
<?php
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
include ("../header.php");

$url="analisis_cotizaciones";
$modulo="Analisis de Cotizaciones";
$tabla="requisiciones";
$titulos=array("Situación","Número","Fecha","Tipo","Concepto");
$indices=array("5","0","9","10","8");

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
		}
	}

	
	switch($tipob){
		case "exacta":
			$consul=buscar_exacta($tabla,$des,$busqueda);
			//$consulta="select * from ".$tabla." where tipo <> 3 and situacion='Revisar' and concepto LIKE '%".$des."%'";
			$consulta=$consul."and tipo <> 3 and situacion='Revisar'";
			break;
		case "todas":
			$consul=buscar_todas($tabla,$des,$busqueda);
			$consulta=$consul."and tipo <> 3 and situacion='Revisar'";
			break;
		case "cualquiera":
			$consul=buscar_cualquiera($tabla,$des,$busqueda);
			$consulta=$consul."and tipo <> 3 and situacion='Revisar'";
			break;
	}
	
}else{
$consulta="select * from ".$tabla." where tipo <> 3 and situacion='Revisar'";
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>

<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<TABLE width="100%">
	<TR class="row-br">
		<TD><?titulo($modulo,"","../modulos/principal/?opt_menu=84","28");?></TD>
	</TR>
</TABLE>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><SELECT name="busqueda">
		<option value="concepto">Concepto</option>
		<option value="cod_requisicion">N&uacute;mero</option>
		<option value="tipo">Tipo de Requisición</option>
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
			$consulp="select id_proveedor,id_compra,estado from compra where cod_requi=".$fila["cod_requisicion"];
			$resultp=query($consulp,$conexion);
			$fil=fetch_array($resultp);
			$cod_proveedor=$fil["cod_provee"];
			$estado=$fil["estado"];
			//echo $estado;
			if($cod_proveedor<>0)
			{
				$consultap="select compania,rif from proveedores where cod_proveedor=".$cod_proveedor;
				$resultadop=query($consultap,$conexion);
				$filap=fetch_array($resultadop);
				if($estado=="Anulada")
				{
					$title="Anulada. Orden Nro: ".$fil["codigo"]." Adjudicada a: ".$filap["compania"]." ".$filap["rif"];
				}else
				{
					$title="Orden Nro: ".$fil["codigo"]." Adjudicada a: ".$filap["compania"]." ".$filap["rif"];
				}
			}
			
			$var=$fila[$nom_tabla];
			echo"<td width=\"700\" title=\"$title\">$var</td>";
		}else{
		$var=$fila[$nom_tabla];
		echo "<td>$var</td>";
		}
	}
	//$situacion=$fila["situacion"];
	$codigo=$fila["cod_requisicion"];
	$cod_centro=$fila["cod_centro"];
	$tipo = $fila["tipo"];
	$conTipo = "SELECT * FROM ordenes_tipos WHERE cod_orden_tipo='".$tipo."'";
	$resTipo = query($conTipo, $conexion);
	$filaTipo = fetch_array($resTipo);
	$desTipo = $filaTipo['descripcion'];
	
	iconoNuevopdf("requisicionespdf.php?id=".$codigo."&cod_centro=".$cod_centro, "Imprimir Requisicion","ico_print.gif");
	icono("cotizaciones_list.php?cod_requisicion=$codigo&desTipo=$desTipo", "Listar Cotizaciones","ico_add.gif");
	icono("comparar_cotizaciones.php?cod_requisicion=$codigo&desTipo=$desTipo", "Comparar Cotizaciones","16.png");

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
