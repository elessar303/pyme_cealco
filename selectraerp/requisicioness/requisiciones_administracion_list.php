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
$url="requisiciones_administracion_list";
$modulo="Requisiciones";
$tabla="requisiciones";
$titulos=array("Número","Fecha","Situación","Tipo","Concepto","Unidad Administrativa","Centro de Costo");
$indices=array("0","9","5","10","8","6","7");

$conexion=conexion();

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
		$consulta=$consulta." and situacion='Administracion'";
}else{
		//echo "cod: ".$id;
		if ($rsac == '1') 
		{
			
			?>
			<script type="text/javascript">	
				window.open('observaciones.php?id=<?php echo $id?>&url=<?php echo $_SERVER['PHP_SELF'];?>','Enviada a Administración/Regresar','width=420, height=120')
				
			</script>
			<?
		}
		
		if ($rsac == '2') 
		{
			$var_sql="update requisiciones set situacion='Revisar' WHERE cod_requisicion = $id";
			$rs = query($var_sql,$conexion);
			
		}
		
	//$consulta="select r.cod_requisicion,r.concepto,r.unidad,r.situacion,r.fecha,r.cod_centro,r.tipo,c.descripcion as descrip_centro,u.descripcion as descrip_centro from ".$tabla." AS r LEFT JOIN unidades as u on r.unidad=u.cod_unidad LEFT JOIN centros as c on r.cod_centro=c.cod_centro  where situacion='Administracion'";
	$consulta="select * from ".$tabla." where situacion='Administracion'";
		//echo $consulta;
}
//echo $consulta." este es el valor que muestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);

?>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<?
	

	titulo($modulo." Administración:","","../modulos/principal/?opt_menu=84","12");
?>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<TD><SELECT name="busqueda">
       <option value="concepto">Concepto</option>
		 <option value="cod_requisicion">N&uacute;mero</option>
     </SELECT></TD>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url,1);?></td>
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
			
			$consulp="select id_proveedor,id_compra from compra where cod_requi=".$fila["cod_requisicion"];
			$resultp=query($consulp,$conexion);
			$fil=fetch_array($resultp);
			$cod_proveedor=$fil["cod_provee"];
			if($cod_proveedor<>0){
			$consultap="select compania,rif from proveedores where cod_proveedor=".$cod_proveedor;
			$resultadop=query($consultap,$conexion);
			$filap=fetch_array($resultadop);
			
			$title="Orden Nro: ".$fil["codigo"]." Adjudicada a: ".$filap["compania"]." ".$filap["rif"];
			}
			
			$var=$fila[$nom_tabla];
			echo"<td width=\"400\" title=\"$title\">$var</td>";
		}else if($nom_tabla=="unidad")
		{
			$consulta_u="select descripcion from unidades where cod_unidad='".$fila['unidad']."'";
			$resultado_u=query($consulta_u,$conexion);
			$fila_u=fetch_array($resultado_u);
			$des_unidad=$fila_u['descripcion'];
			$descripcion_u=$des_unidad;
			echo"<td>$descripcion_u</td>";
		}
		else if($nom_tabla=="cod_centro")
		{
			$consulta_c="select descripcion from centros where cod_centro='".$fila['cod_centro']."'";
			$resultado_c=query($consulta_c,$conexion);
			$fila_c=fetch_array($resultado_c);
			$des_centro=$fila_c['descripcion'];
			$descripcion_c=$des_centro;	
			echo"<td>$descripcion_c</td>";
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
	$tipo=$fila['tipo'];
	$contipo="select * from ordenes_tipos where cod_orden_tipo=$tipo";
	$resultip=query($contipo,$conexion);
	$contipo=fetch_array($resultip);
	if($situacion!="Anulado")
	{
		iconoNuevopdf("requisicionespdf.php?id=".$id."&cod_centro=".$cod_centro, "Imprimir Orden","ico_print.gif");

		
	}else{
		echo '<td></td>';
	}
	icono("requisiciones_administracion_list.php?id=".$id."&rsac=1&pagina=".$pagina, "Regresar a Procedencia", "ico_back.gif");
	icono("requisiciones_administracion_list.php?id=".$id."&rsac=2&pagina=".$pagina, "Enviar a ".$contipo['descripcion'], "ico_ok.gif");
	//icono("centros_delete.php?codigo=".$codigo."&cod_centro=".$cod_centro, "Eliminar", "delete.gif");
	//icono("municipios_list.php?codigo=".$codigo, "Municipios", "view.gif");

    echo"</tr>";
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
