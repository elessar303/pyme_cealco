<?if (!isset($_SESSION)) {
  session_start();
}
?>

<?php

require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
$cantidad_registros=25;
$Conn=conexion_conf(); //= new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);

	$var_sql="select encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der from parametros";
	$rs = query($var_sql,$Conn);
	$row_rs = fetch_array($rs);
	$var_encabezado1=$row_rs['encabezado1'];
	$var_encabezado2=$row_rs['encabezado2'];
	$var_encabezado3=$row_rs['encabezado3'];
	$var_encabezado4=$row_rs['encabezado4'];
	$var_imagen_izq=$row_rs['imagen_izq'];
	$var_imagen_der=$row_rs['imagen_der'];	
	$var_sql="select codigo,nomemp,departamento,presidente,periodo,cargo,nivel,desislr,ctaisrl,desiva,ctaiva,por_isv,compra,servicio,rif,nit,direccion,telefono,por_im,por_bomberos,lugar,sobregirop,autorizacionodp,claveodp,contrato,gas_dir from parametros";
	$rsu = query($var_sql,$Conn);
	$row_rsu = fetch_array($rsu);
	$var_nomemp=$row_rsu['nomemp'];

	cerrar_conexion($Conn);
?>

<? 
$conexion=conexion();
$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro'];
$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];

$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
$rs = query($consulta_req,$conexion);
$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);

function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows)
{
		$conexion=conexion();
		$var_sql="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,r.estacion,
		r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro,r.concepto,r.tipo FROM requisiciones r,centros c 
		WHERE r.cod_requisicion=$id and r.cod_centro=c.cod_centro";
		$rs = query($var_sql,$conexion);
		$row_rs = fetch_array($rs);
		$var_fecha=$row_rs['fecha'];
		$var_nom_centro=$row_rs['des_centro'];
		$var_des=$row_rs['descripcion'];
		$var_unidad=$row_rs['unidad'];
		$var_centro=$row_rs['cod_centro'];
		$var_concepto_req=$row_rs['concepto'];
		$var_tipo=$row_rs['tipo'];
		$var_situacion=$row_rs['situacion'];
		//$rs->close();
		
		$var_sql="SELECT descripcion FROM unidades WHERE cod_unidad=$var_unidad";
		$rsu = query($var_sql,$conexion);
		$row_rsu = fetch_array($rsu);
		$var_nom_und=$row_rsu['descripcion'];
		
		$var_sql="SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = $var_tipo";
		$rsu = query($var_sql,$conexion);
		$row_rsu = fetch_array($rsu);
		$var_nom_tipo=$row_rsu['descripcion'];
		//$rsu->close();
		
	$fech=fecha($var_fecha);
	$datos_requisicion='<table width="700" border="0" align="center">
  <tbody>
    <tr>
	<td width="100" align="right"></td>
      <td align="center"><h3><strong>REQUISICIÓN '.$var_nom_tipo.' Número: '.$id.'</strong></h3></td>
      <td width="100" align="right"><h3><strong>Pág.: '.$pagina.'/'.$num_paginas.'</strong></h3></td>
    </tr>
<TR>
   <TD colspan="3"><br></TD>
 </TR>
    <tr>
      <td width="200"><p align="center"><strong>Fecha de Emisión</strong></p>
          <p align="center">'.$fech.'</p></td>
      <td width="250"><p align="center"><strong>Unidad Solicitante</strong></p>
          <p align="center">'.$var_unidad.' - '.$var_nom_und.'</p></td>
      <td width="250"><p align="center"><strong>Centro de Costo</strong></p>
          <p align="center">'.$var_centro.' - '.$var_nom_centro.'</p></td>
    </tr>
	<TR>
   <TD colspan="3"><br></TD>
 </TR>
    <tr>
      <td colspan="3" style="border-bottom-style : outset; border-bottom-width : 1px; border-left-style : outset; border-left-width : 1px; border-right-style : outset; border-right-width : 1px; border-top-style : outset; border-top-width : 1px;"><div align="center"><strong>Concepto de la Requisici&oacute;n</strong></div>
      <p align="justify">'.$var_concepto_req.'</p></td>
    </tr>
<TR>
   <TD colspan="3"><br></TD>
 </TR>
    
  </tbody>
</table>';
echo $datos_requisicion;
}
$pie=pie();
?>

<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
<div id="impresion">

<?
$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
echo $encabezado."<br><br>";
imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows);
?>

<table width="700"  border="0" align="center" cellpadding="2" cellspacing="2" class="">
<tr style="border-bottom-style : outset; border-bottom-width : 1px; border-left-style : outset; border-left-width : 1px; border-right-style : outset; border-right-width : 1px; border-top-style : outset; border-top-width : 1px;">
          <td width="150" align="center"><strong>Cantidad</strong></td>  
          <td width="150" align="center"><strong>Unidad</strong></td>
		  <td width="400" align="left"><strong>Descripci&oacute;n</strong></td>
        </tr>
<?
$cont=1;
while ($row_rs = fetch_array($rs)) 
{?>

	    <tr class="tb-bg-in" >
            <td width="150" ><div align="center"><? echo $row_rs['cantidad']; ?></div></td>
            <td width="150" align="center"><? echo $row_rs['medida'] ?></td>
			<td width="400" align="left"><? echo $row_rs['descripcion'] ?></td>
            <!--    <td width="16"><a href="javascript:;" onclick="confirmar('Seguro de Borrar?','<? echo $filename ?>?rsac=del&amp;id=<? echo $id; ?>'); return self.rValue"><img src="img_sis/ico_basket.gif" alt="Borrar" width="15" height="15" border="0" /></a></td> -->
          	</form>
        </tr>
	<?

	if($cont==$cantidad_registros)
	{
		echo "</table>".$pie."<br class=\"saltopagina\">";

		echo $encabezado.'<br><br>';
		imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows);

		echo '<table width="700" border="0" align="center" cellpadding="2" cellspacing="2" class=""><tr style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
          <td width="200" align="center"><strong>Cantidad</strong></td>
          <td width="100" align="center"><strong>Unidad</strong></td>
		  <td width="400" align="left"><strong>Descripci&oacute;n</strong></td>
        </tr>';
		$cont=1;
	}else{$cont++;}
}
if($cont<=$cantidad_registros)
{
	echo "</table>".$pie;
}// cierra el if 
	?>
    
<?cerrar_conexion($conexion);?>
 
</div>

</body>
</html>
