<?php
$url="materiales_list";
$modulo="Materiales";
$tabla="materiales";
$titulos=array("Código","Descripción","Unidad","I.V.A.");
$indices=array("0","1","2","13");

require_once '../lib/common.php';
$conexion=conexion();
$consulta="select * from ".$tabla;
$resultado=query($consulta,$conexion);
$codigo_snc = $_GET['codigo_snc'];
//cerrar_conexion($conexion);
include ("../header.php");

$numero=mysql_num_rows($resultado);
$Conn = conexion_conf();//new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);

$Conn=conexion_conf();
$var_sql = "SELECT encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der FROM parametros";
$rs = query($var_sql, $Conn);
$row_rs = fetch_array($rs);
$var_encabezado1 = $row_rs['encabezado1'];
$var_encabezado2 = $row_rs['encabezado2'];
$var_encabezado3 = $row_rs['encabezado3'];
$var_encabezado4 = $row_rs['encabezado4'];
$var_imagen_izq = $row_rs['imagen_izq'];
$var_imagen_der = $row_rs['imagen_der'];
cerrar_conexion($Conn);

$var_sql="select codigo,nomemp,departamento,presidente,periodo,cargo,nivel,desislr,ctaisrl,desiva,ctaiva,por_isv,compra,servicio,rif,nit,direccion,telefono,por_im,por_bomberos,lugar,sobregirop,autorizacionodp,claveodp,contrato,gas_dir from parametros";
	$rsu = query($var_sql,$Conn);		
	$row_rsu = fetch_array($rsu);
	$var_nomemp=$row_rsu['nomemp'];
	$var_direccion=$row_rsu['direccion'];
	cerrar_conexion($Conn);
	$pagina=1;
	encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
?>
<?titulo("Listado de Materiales","","materiales.php?codigo_snc=".$codigo_snc, "70")?>
<br>
<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
<div id="impresion">
<BR>
<table width="800" border="0" align="center">
  <tbody>
    <tr>
      <td rowspan="4" valign="middle"><img src="<?echo $var_imagen_izq?>" width="150" height="40"></td>
      <td width="500"><p align="center"><strong><?echo $var_encabezado1?></strong></p></td>
      <td rowspan="4" valign="middle"><img src="<?echo $var_imagen_der?>" width="150" height="60"></td>
    </tr>
    <tr>
      <td width="400"><p align="center"><strong><?echo $var_encabezado2?></strong></p></td>
    </tr>
    <tr>
      <td width="400"><p align="center"><strong><?echo $var_encabezado3?></strong></p></td>
    </tr>
    <tr>
      <td width="400"><p align="center"><strong><?echo $var_encabezado4?></strong></p></td>
    </tr>
  </tbody>
</table>

<table width="800" cellspacing="0" border="0" align="center">
  <tbody>
	<TR><TD height="20"></TD></TR>
	<TR><TD colspan="4" align="center"><h3>LISTADO DE MATERIALES</h3></TD></TR>
	<TR><TD align="right" colspan="4"><strong>Fecha:</strong> <?echo date('d/m/Y')?></TD></TR>
    <tr class="tb-head" >
<?
foreach($titulos as $nombre){
	echo "<td><STRONG>$nombre</STRONG></td>";
}
?>
    </tr>
<?
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
		
		$var=$fila[$nom_tabla];
		echo"<td>$var</td>";
	}
    echo "</tr>";
if($i==50){
$i=0;
?>
</tbody>
</table>
<br class="saltopagina">
<table width="800" border="0" align="center">
  <tbody>
    <tr>
      <td rowspan="4" valign="middle"><img src="<?echo $var_imagen_izq?>" width="150" height="40"></td>
      <td width="500"><p align="center"><strong><?echo $var_encabezado1?></strong></p></td>
      <td rowspan="4" valign="middle"><img src="<?echo $var_imagen_der?>" width="150" height="60"></td>
    </tr>
    <tr>
      <td width="400"><p align="center"><strong><?echo $var_encabezado2?></strong></p></td>
    </tr>
    <tr>
      <td width="400"><p align="center"><strong><?echo $var_encabezado3?></strong></p></td>
    </tr>
    <tr>
      <td width="400"><p align="center"><strong><?echo $var_encabezado4?></strong></p></td>
    </tr>
  </tbody>
</table>
<table width="800" cellspacing="0" border="0" cellpadding="1" align="center">
  <tbody>
	<TR><TD height="20"></TD></TR>
	<TR><TD colspan="4" align="center"><h3>LISTADO DE MATERIALES</h3></TD></TR>
	<TR><TD align="right" colspan="4"><strong>Fecha:</strong> <?echo date('d/m/Y')?></TD></TR>
    <tr class="tb-head" >
<?
foreach($titulos as $nombre){
	echo "<td><STRONG>$nombre</STRONG></td>";
}
?>
    </tr>
<?
	}
	}
?>
</div>
</body>
</html>
