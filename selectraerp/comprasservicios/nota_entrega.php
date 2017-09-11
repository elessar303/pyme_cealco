<?php 
$url="nota_entrega";
$modulo="Nota de Entrega";
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
		$codigo=$_POST['codigo'];
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
	$consulta=$consul." and estado<>'Anulado' and cod_req=".$codigo." GROUP BY codigo";
	
}else{
	$consulta="select * from ".$tabla." where  cod_req=".$codigo."  GROUP BY codigo";
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
			$consulta="select * from requisiciones_det where cod_requisicion=$codigo";
			$detalles=query($consulta,$conexion);
			$can_mat=num_rows($detalles);
			$true=0;
			while ($req_det=fetch_array($detalles)){
				$mate=$req_det['cod_material'];
				$cantidad=$req_det['cantidad'];
				
				$consulta="select * from ordenes_ne where cod_req=$codigo and cod_pro ='$mate'";
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
				echo "<TD>".titulo($modulo,"","nota_entrega_list.php","30")."</TD>";
			}else{ 
				echo "<TD>".titulo($modulo,"adjudicar_ne.php?cod_req=$codigo","nota_entrega_list.php?codigo=$codigo","30")."</TD>";
			 }
		?>
	</TR>
</TABLE>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?pagina=".$pagina."&codigo=".$codigo); ?></td>
	<td width="120"><input onClick="javascript:actualizar(this);" checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"><input onClick="javascript:actualizar(this);" type="radio" name="palabra" value="todas">Todas las palabras</td>
	<td width="150"><input onClick="javascript:actualizar(this);" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>
	<td colspan="3" width="386"></td>
	<td><INPUT type="hidden" name="codigo" value="<?echo $codigo?>"></td>
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
		iconoNuevo("../fpdf/notaentrega_pdf.php?id=$codigo&codigo=$codigo_ne", "Imprimir Nota de Entrega","ico_print.gif");
		if($fila['estado']!='Entregado'){
			//icono("adjudicar_ne.php?id=$codigo_ne&cod_req=$codigo"."&des=1", "Entregar", "ico_back.gif");
			
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
		//icono("adjudicar_ne.php?cod_ordenes=$codigo", "Generar Acta de Almacen", "ico_ok.gif");	
	}
	echo "</tr>";
	}
}else{
	echo "<tr><td>No existen Notas de Entrega en este momento</td></tr>";
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