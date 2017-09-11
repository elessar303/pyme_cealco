<?php 
	  	require_once '../lib/config.php';
		require_once '../lib/common.php';
		include ("../header.php");
		$Conn = conexion();//new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
		
	  	$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro']; 		
	  	$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
	  	$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];
			  
		$var_sql="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,r.estacion,
		r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro,r.concepto FROM requisiciones r,centros c 
		WHERE r.cod_requisicion=$id and r.cod_centro=c.cod_centro";
		$rs = query($var_sql,$Conn);
		$row_rs = fetch_array($rs);
		$var_fecha=$row_rs['fecha'];
		$var_nom_centro=$row_rs['des_centro'];
		$var_des=$row_rs['descripcion'];
		$var_unidad=$row_rs['unidad'];
		$var_centro=$row_rs['cod_centro'];
		$var_concepto=$row_rs['concepto'];			
		//$rs->close();
		
		$var_sql="SELECT descripcion FROM unidades WHERE cod_unidad=$var_unidad";
		$rsu = query($var_sql,$Conn);		
		$row_rsu = fetch_array($rsu);
		$var_nom_und=$row_rsu['descripcion'];
		//$rsu->close();
		$rs = query("SELECT codigo,cod_req,fecha,tipo,cod_pro,cantidad_ped,cantidad_des,concepto,estado FROM ordenes_ne where cod_req = $id", $Conn);
		while ($row_rs = fetch_array($rs)) 
		{
			$var_cod_orden=$row_rs['codigo'];
			$var_fecha=$row_rs['fecha'];
			$var_tipo=$row_rs['tipo'];
			$var_cod_req=$row_rs['cod_req'];			
			$var_estado=$row_rs['estado'];			
		}
		//$rs->close();
		cerrar_conexion($Conn);
	  ?>

<?php
	$Conn = conexion_conf();//new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
	$var_sql="select encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der from parametros";
	$rs = query($var_sql,$Conn);
	$row_rs = fetch_array($rs);
	$var_encabezado1=$row_rs['encabezado1'];
	$var_encabezado2=$row_rs['encabezado2'];
	$var_encabezado3=$row_rs['encabezado3'];
	$var_encabezado4=$row_rs['encabezado4'];
	$var_imagen_izq=$row_rs['imagen_izq'];
	$var_imagen_der=$row_rs['imagen_der'];		
	//$rs->close();

	?>
<div style="text-align: center;">
  <p align="center">&nbsp;</p>
  <div align="center">
<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
	<div id="impresion">
<?
$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der);
echo $encabezado."<br><br>";
?>

<br>
<TABLE width="700" align="center" border="0">
		<TR>
			<TD align="right"><strong>Fecha:</strong> <?echo date('d/m/Y');?></TD>
		</TR>
		<TR>
			<TD align="right"><strong>Hora:</strong> <?echo date('h:i a');?></TD>
		</TR>
</TABLE>
<table width="700" border="0" align="center">
    <tr>
          <td colspan="3"><div align="center" class="ewGroupAggregate"><h3>*** NOTA DE ENTREGA ***</h3> </div></td>
        </tr>
        <tr>
          <td colspan="3"><div align="center">
            <p><strong>Especificaci&oacute;n de la Nota de Entrega</strong></p>
            <p><?php echo $var_concepto; ?>
            </p>
            </div>
          </td>
        </tr>
        <tr>
          <td width="25%" align="center"><strong>Fecha de Emisi&oacute;n</strong><br><?php echo fecha($var_fecha); ?></td>
          <td width="37%" align="center"><strong>Unidad Solicitante</strong><br><?php echo $var_unidad; ?> - <?php echo $var_nom_und; ?></td>
          <td width="38%" align="center"><strong>Centro de Costo</strong><br><?php echo $var_centro; ?> - <?php echo $var_nom_centro; ?> </td>
        </tr>
	<tr><TD colspan="3" height="10"></TD></tr>
    <tr>
      <td width="25%"><div align="center"><strong>N&uacute;mero de Orden</strong><br><?php echo $var_cod_orden; ?></div>
      </td>
      <td width="37%"><div align="center"><strong>N&uacute;mero de Requisici&oacute;n</strong><br><?php echo $id; ?></div>
      </td>
      <td width="38%"><div align="center"><strong>Estado</strong><br><?php echo $var_estado; ?></div>
      </td>
    </tr>
  </table>
  	<?php 
	cerrar_conexion($Conn);
		$contador=0;
	$Conn=conexion();
	  $rs = query("SELECT * FROM requisiciones_det  WHERE cod_requisicion = $id  ORDER BY cod_requisicion_det ",$Conn);
   	  while ($row_rs = fetch_array($rs)) 
	  { 
	  	$contador++;
	  }
	  //$rs->close();
	  if ($contador<>0)
	 {?>
  <div style="text-align: left;">
    <div align="center">
      <table width="700" border="1" align="center" cellpadding="2" cellspacing="2" class="">
		<tr><TD align="center" valign="middle" height="30" colspan="4"><strong>Datos de los Materiales</strong></TD></tr>
        <tr>
          <td width="15%"><div align="center"><strong>C&oacute;digo</strong></div></td>
          <td width="45%"><div align="center"><strong>Descripci&oacute;n</strong></div></td>
          <td width="20%"><div align="center"><strong>Cantidad Pedida</strong></div></td>
          <td width="20%"><div align="center"><strong>Cantidad Despachada</strong></div></td>
        </tr>
        <?php 
	  $rs = query("SELECT * FROM requisiciones_det  WHERE cod_requisicion = $id  ORDER BY cod_requisicion_det ",$Conn);
   	  while ($row_rs = fetch_array($rs)) 
	  { 
	  $var_cod_prod=$row_rs['cod_material'];
	  
	  $rso = query("SELECT cantidad_des FROM ordenes_ne  WHERE cod_req = $id and cod_pro='$var_cod_prod'",$Conn);
   	  while ($row_rso = fetch_array($rso)) 
	  { 
	  	$var_cantidad_des=$row_rso['cantidad_des'];				
	  }

	  ?>
        <tr class="tb-bg-in">
          <form method="post" id="form<? echo $row_rs['id_foto'] ?>" name="form<? echo $row_rs['id_foto'] ?>" action="<? echo $filename ?>?rsac=edit&amp;id=<? echo $row_rs['id_foto'] ?>">
            <td align="center"><? echo $row_rs['cod_material'] ?>
                <div align="center"></div></td><td><? echo $row_rs['descripcion'] ?>
                  <div align="center"></div></td>
            <td align="center"><? echo number_format($row_rs['cantidad'], 2, ',', '.'); ?>
              <div align="center"></div></td>
            <td align="center"><?php echo number_format($var_cantidad_des, 2, ',', '.'); ?>
              <div align="center"></div></td>
          </form>
        </tr>
        <? } // cierra el if ?>
        <?  //$rs->close(); $rso->close();
cerrar_conexion($Conn);
?>
      </table><?php } ?>
    </div>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <p align="center">&nbsp;</p>
    <div align="center">
      <table width="700" border="1" align="center" style="text-align: left; width: 741px; height: 97px;">
        <tbody>
          <tr>
            <td align="center" valign="top" style="text-align: center;"><small>SOLICITADO POR:<br>
                  <br>
                  <br>
          ___________________________<br>
          GERENTE</small></td>
            <td align="center" valign="top"><div align="center"><small>RECIBIDO POR:<br>
            NOMBRE:__________________ <br>
&nbsp; &nbsp; FIRMA:__________________<br>
&nbsp; &nbsp;FECHA:__________________</small></div></td>
          </tr>
        </tbody>
      </table>
    </div>
    <p align="center"><br>
      <span style="font-family: serif;"></span></p>
  </div>
    <div align="center"><span style="font-family: serif;"> </span></div>
</div>
</body>
</html>