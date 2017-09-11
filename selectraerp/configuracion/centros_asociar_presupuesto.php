<?php 
require_once '../lib/common.php';
include ("../header.php");

$conexion_conf=conexion_conf();
$consulta_conf="select tipo_presupuesto,tipo_compromiso from parametros";
$resultado_conf=query($consulta_conf,$conexion_conf);
$fila_conf=fetch_array($resultado_conf);
//$tipo_compromiso=$fila_conf['tipo_compromiso'];
$tipo_presupuesto = $fila_conf['tipo_presupuesto'];
$tipo_compromiso = $fila_conf['tipo_compromiso'];
cerrar_conexion($conexion_conf);


$conexion=conexion();
//echo $conexion;
$url="centros_asociar_presupuesto";
$modulo="Centros de Costos Asociados a Acciones Centralizadas o Proyectos";
$tabla="centrosypresupuesto";
$titulos=array("Unidad Administrativa","Centro de Costo","Proyecto","Accion Centralizada");
$indices=array("0","1","3","4");

$cod_unidad=@$_GET['codigo'];
$cod_centro=@$_GET['cod_centro'];
$programa=@$_GET['programa'];
$actividad=@$_GET['actividad'];
//echo $cod_unidad;
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$borrar=@$_GET['borrar'];
if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$cod_unidad=$_POST['codigo'];
	}
	switch($tipob){
		case "exacta": 
			$consulta=buscar_exacta($tabla,$des,"descripcion");
			break;
		case "todas":
			$consulta=buscar_todas($tabla,$des,"descripcion");
			break;
		case "cualquiera":
			$consulta=buscar_cualquiera($tabla,$des,"descripcion");
			break;
	}
	$consulta=$consulta." AND cod_unidad='".$cod_unidad."' order by cod_centro";

}else{
	if ($borrar == '1') 
	{
		$var_sql="delete from ".$tabla." WHERE cod_unidad='".$cod_unidad."' and cod_centro='".$cod_centro."' and sel_programa='".$programa."' and sel_actividad='".$actividad."'";
		$rs = query($var_sql,$conexion);
	}
$consulta="select * from ".$tabla." where cod_unidad='".$cod_unidad."' and cod_centro='".$cod_centro."'";
//echo $consulta;
}
//echo $consulta." este es el valor quemuestra ";
$num_paginas=obtener_num_paginas($consulta);
$pagina=obtener_pagina_actual($pagina, $num_paginas);
$resultado=paginacion($pagina, $consulta);

include ("../header.php");
?>
<FORM name="<?echo $url?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" target="_self">
<?

	titulo($modulo,"centros_asociar_presupuesto_add.php?codigo=".$cod_unidad."&cod_centro=".$cod_centro,"centros_list.php?codigo=".$cod_unidad,"12");

?>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?pagina=".$pagina."&codigo=".$cod_unidad); ?></td>
	<td width="120"><input onclick="javascript:actualizar(this);" checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="todas">Todas las palabras</td>
	<td width="150"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>
	<td colspan="3" width="386"></td>
	<td><INPUT type="hidden" name="codigo" value="<?echo $cod_unidad?>"></td>
	
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
		switch ($nom_tabla)
		{
			case 'cod_unidad':
				$consulta="select descripcion from unidades where cod_unidad=".$fila["cod_unidad"];
				$resul=query($consulta,$conexion);
				$fila_x=fetch_array($resul);
				?>
				<td><? echo $fila_x["descripcion"];?></td>
				<?
				
			break;
			case 'cod_centro':
				$consulta="select descripcion from centros where cod_unidad=".$fila["cod_unidad"]." and cod_centro='".$fila["cod_centro"]."'";
				$resul=query($consulta,$conexion);
				$fila_x=fetch_array($resul);
				?>
				<td><? echo $fila_x["descripcion"];?></td>
				<?
			break;
			case 'sel_programa':
				$consulta="select * from cwprogra where Programa='".$fila["sel_programa"]."'";
				$resul=query($consulta,$conexion);
				$fila_x=fetch_array($resul);
				?>
				<td><? echo $fila_x["Denominacion"];?></td>
				<?
			break;
			case 'sel_actividad':
				$consulta="select * from cwpreact where RecNoPro=".$fila_x["RecNo"]." and Obr='".$fila["sel_actividad"]."'";
				$resul=query($consulta,$conexion);
				$fila_x=fetch_array($resul);
				?>
				<td><? echo $fila_x["Denominacion"];?></td>
				<?
			break;

			
		}
		//$var=$fila[$nom_tabla];
		//echo"<td>$var</td>";
	}
	$cod_unidad=$fila["cod_unidad"];
	$cod_centro=$fila["cod_centro"];
	$programa=$fila["sel_programa"];
	$actividad=$fila["sel_actividad"];
	
	
	icono("javascript:confirmar('Desea Eliminar esta Accion Especifica o Pryecto Asociado a Este Centro de Costo? ?','centros_asociar_presupuesto.php?codigo=".$cod_unidad."&cod_centro=".$cod_centro."&programa=".$programa."&actividad=".$actividad."&borrar=1')", "Eliminar", "delete.gif");
	//icono("centros_delete.php?codigo=".$cod_unidad."&cod_centro=".$cod_centro, "Eliminar", "delete.gif");
	//icono("centros_asociar_presupuesto.php?codigo=".$cod_unidad."&cod_centro=".$cod_centro, "Asociar Presupuesto", "view.gif");

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
pie_pagina($url,$pagina,"&tipo=".$tipob."&des=".$des."&codigo=".$cod_centro,$num_paginas);
?>
</FORM>
</BODY>
</html>