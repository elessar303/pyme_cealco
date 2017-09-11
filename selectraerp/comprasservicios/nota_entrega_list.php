<?php
//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdfcommon.php';
include ("../header.php");

$url="nota_entrega_list";
$modulo="Nota de Entrega";
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
	}
	switch($tipob){
		case "exacta":
			$consul=buscar_exacta($tabla,$des,$busqueda);
			//$consulta="select * from ".$tabla." where tipo <> 3 and situacion='Revisar' and concepto LIKE '%".$des."%'";
			$consulta=$consul."and  (situacion='Revisar' or situacion='Adjudicada') and tipo= 3 ";
			break;
		case "todas":
			$consul=buscar_todas($tabla,$des,$busqueda);
			$consulta=$consul."and (situacion='Revisar' or situacion='Adjudicada') and tipo= 3";
			break;
		case "cualquiera":
			$consul=buscar_cualquiera($tabla,$des,$busqueda);
			$consulta=$consul."and (situacion='Revisar' or situacion='Adjudicada') and tipo= 3";
			break;
	}
}else{
$consulta="select * from ".$tabla." where  (situacion='Revisar' or situacion='Adjudicada') and tipo= 3";
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>

<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<TABLE width="100%">
	<TR class="row-br">
		<TD><?titulo($modulo,"","../menu_int.php?cod=2","30");?></TD>
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
		
		}else{
		$var=$fila[$nom_tabla];
		echo "<td>$var</td>";
		}
	}
	
	$codigo=$fila["cod_requisicion"];
	icono("nota_entrega.php?codigo=$codigo", "Listar Notas","ico_add.gif");
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