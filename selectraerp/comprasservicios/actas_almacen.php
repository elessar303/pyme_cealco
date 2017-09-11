<?php 
$url="actas_almacen";
$modulo="Actas de Recepcion de Materiales";
$tabla="ordenes_ne";
$titulos=array("Situación","Número","Fecha","Concepto");
$indices=array("10","0","2","9");

//DECLARACION DE LIBRERIAS
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");

$conexion=conexion();
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$codigo=$_GET['codigo'];
if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
	}
	switch($tipob){
		case "exacta":
			$consul=buscar_exacta($tabla,$des,"concepto");
			break;
		case "todas":
			$consul=buscar_todas($tabla,$des,"concepto");
			break;
		case "cualquiera":
			$consul=buscar_cualquiera($tabla,$des,"concepto");
			break;
	}
	$consulta=$consul." and estado<>'Anulado'";
	
	
	}
	else{
$consulta="select * from ".$tabla." where  cod_ordenes=$codigo  GROUP BY codigo";
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);
?>
<HTML>
<HEAD><TITLE></TITLE>
<SCRIPT language="JavaScript" type="text/javascript" src="mostrar_detalles.js">
</SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
</SCRIPT>
</HEAD>
<BODY>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<TABLE width="100%">
	<TR class="row-br">
		<?php 
			// validar que no pueda agregar mas si ya cumplio con lo que tenia que llegar
			$consulta="select * from ordenes_detalles where cod_ord=$codigo";
			$detalles=query($consulta,$conexion);
			$can_mat=num_rows($detalles);
			$true=0;
			while ($ord_det=fetch_array($detalles)){
				$mate=$ord_det['cod_pro'];
				$cantidad=$ord_det['cantidad_pedida'];
				
				$consulta="select * from ordenes_ne where cod_ordenes=$codigo and cod_pro ='$mate'";
				$orde_ne=query($consulta,$conexion);
				$can_ne=0;
				while($ordenes_det=fetch_array($orde_ne)){
						$can_ne+=$ordenes_det['cantidad_des'];
				}
				if($can_ne>=$cantidad){
					$true+=1;
				}
			}
			
			if ($true==$can_mat){
				echo "<TD>".titulo($modulo." Para Orden  Nro: ".$codigo,"","actas_almacen_list.php","30")."</TD>";
			}else{ 
				echo "<TD>".titulo($modulo,"adjudicar_aa.php?cod_ordenes=$codigo","actas_almacen_list.php","30")."</TD>";
			 }
		?>
	</TR>
</TABLE>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?pagina=".$pagina); ?></td>
	<td width="120"><input onClick="javascript:actualizar(this);" checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"><input onClick="javascript:actualizar(this);" type="radio" name="palabra" value="todas">Todas las palabras</td>
	<td width="150"><input onClick="javascript:actualizar(this);" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>
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
      
    </tr>
<? 
	if($num_paginas!=0){
	$i=0; 
	while($fila=fetch_array($resultado)){
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
			echo"<td>$fecha</td>";
		}else{
		$var=$fila[$nom_tabla];
		echo "<td>$var</td>";
		}
	}
	
	$resul=query($consulta,$conexion);
	$fila2=fetch_array($resul);
	if (num_rows($resul))
	{
		$codigo_ne=$fila['codigo'];
		iconoNuevo("../fpdf/actasalmacen_pdf.php?id=$codigo&codigo=$codigo_ne", "Imprimir Acta de Almacen","ico_print.gif");
		if($fila['estado']!='Recibido'){
			icono("adjudicar_aa.php?id=$codigo_ne&cod_ordenes=$codigo"."&des=1", "Despachar", "ico_back.gif");
			//icono("adjudicar_aa_edit.php?cod_ordenes=$codigo","Editar Acta de Almacen", "ico_edit.gif");
		}else{
		 	
			echo "<td></td>";
		}
		
	}else
	{	
		?>
		<td><img width="16" height="16" align="left" border="0" src="../imagenes/ico_est6.gif"/></td>
		<td><img width="16" height="16" align="left" border="0" src="../imagenes/ico_est6.gif"/></td>
		<?
		//icono("ordenesprint3.php?codigo=$codigo", "Imprimir Orden","ico_print.gif");
		icono("adjudicar_aa.php?cod_ordenes=$codigo", "Generar Acta de Almacen", "ico_ok.gif");	
	}
	echo "</tr>";
	}
}else{
	echo "<tr><td>No existen Actas de Almacen en este momento</td></tr>";
}
cerrar_conexion($conexion);
?>
  </tbody>
</table>
<?
	pie_pagina($url,$pagina,"&tipo=".$tipob."&des=".$des,$num_paginas);
?>
</FORM>

</BODY>
</HTML>