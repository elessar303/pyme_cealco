<script>
function validar()
{
	var_tipo=window.document.form1.tipo_orden.value
	if(var_tipo=="Nota de Entrega")
	{
		document.form1.proveedor.disabled=true;
		document.form1.proveedor.value="TODOS"
	}
	else
	{
		document.form1.proveedor.disabled=false;
	}
}
</script>
<?
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../header.php';
//include("../config_bd.php"); // archivo que llama a la base de datos
$Conn = conexion();//new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
if($_GET[acc])
{
	$var_accion=$_GET[acc];
}

if (!isset($var_accion))
{
	$var_tipo_orden=$_POST[cod_orden_tipo];
	$var_tipo_orden2=$_POST[cod_orden_tipo];
	$var_proveedor=$_POST["proveedor"];
	$var_proveedor2=$_POST["proveedor"];
	$var_estado=$_POST["estado"];
	$var_estado2=$_POST["estado"];	
	$var_centro=$_POST["centro"];
	$var_centro2=$_POST["centro"];
	
}
else
{
	$var_tipo_orden=$_GET[tipo];
	$var_tipo_orden2=$_GET[tipo];
	$var_proveedor=$_GET[proveedor];
	$var_proveedor2=$_GET[proveedor];
	$var_estado=$_GET[estado];
	$var_estado2=$_GET[estado];
	$var_centro=$_GET[centro];
	$var_centro2=$_GET[centro];
	$var_cod_ord=$_GET[id];
}

//$var_accion=$_GET[];
/*
if (!isset($var_accion))
{
	$var_tipo_orden=$_POST[cod_orden_tipo];
	$var_tipo_orden2=$_POST[cod_orden_tipo];
	$var_proveedor=$_POST[proveedor];
	$var_proveedor2=$_POST[proveedor];
	$var_estado=$_POST[estado];
	$var_estado2=$_POST[estado];	
	$var_centro=$_POST[centro];
	$var_centro2=$_POST[centro];
	
}
else
{
	$var_tipo_orden=$_GET[tipo];
	$var_tipo_orden2=$_GET[tipo];
	$var_proveedor=$_GET[proveedor];
	$var_proveedor2=$_GET[proveedor];
	$var_estado=$_GET[estado];
	$var_estado2=$_GET[estado];
	$var_centro=$_GET[centro];
	$var_centro2=$_GET[centro];
	$var_cod_ord=$_GET[id];
}
*/
$var_bandera = false;
//echo "Proveedor".$var_proveedor;
//echo "1: ".$var_centro."2: ".$var_centro2;
//echo "Orden: ".$var_tipo_orden."Orden2: ".$var_tipo_orden2;
if ($var_proveedor<>'' && $var_proveedor== 'Todos')
{
	$rs = query("SELECT compania FROM proveedores where cod_proveedor = '$var_proveedor'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_proveedor=$row_rs['compania'];		
		$var_proveedor2=$row_rs['compania'];
	}
	//$rs->close();	
}
//echo "Tipo de orden".$var_tipo_orden;
if ($var_tipo_orden=="3")
{
	$var_bandera = true;
}

if ($var_centro<>'')
{
	$rs = query("SELECT descripcion FROM centros where cod_centro = '$var_centro'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_centro=$row_rs['descripcion'];		
	}
	//$rs->close();
}

if($var_accion=='anuo')
{
	$rs = query("SELECT cod_requi FROM ordenes  where codigo = $var_cod_ord",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$x_cod_riqui=$row_rs['cod_requi'];
	}
	//$rs->close();
	//echo "Pase aqui el codigo de la orden es: ".$var_cod_ord." y la req es: ".$x_cod_riqui;
	//exit(0);
	

	$var_sql="update ordenes set estado = 'Anulado' where codigo = $var_cod_ord";			
	$result = query($var_sql, $Conn); 
	
	$var_sql="update requisiciones set situacion = 'Revisar' where cod_requisicion=$x_cod_riqui";
	$result = query($var_sql, $Conn); 
}
if($var_accion=='anun')
{
	$rs = query("SELECT cod_req FROM ordenes_ne  where codigo = $var_cod_ord",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$x_cod_riqui=$row_rs['cod_req'];
	}
	//$rs->close();
		
   $rso = query("SELECT cantidad_des,cod_pro FROM ordenes_ne WHERE cod_req = '$x_cod_riqui' and estado <> 'Anulado' and estado = 'Emitida'",$Conn);
   while ($row_rso = fetch_array($rso)) 
   {
		$var_cantidad_des=0;
		$var_cantidad_des=$row_rso['cantidad_des'];
		$var_cod_pro=$row_rso['cod_pro'];	
		
		$rsm = query("SELECT existencia FROM materiales WHERE cod_material = $var_cod_pro",$Conn);
		while ($row_rsm = fetch_array($rsm)) 
		{
			$var_total_exis=0;
			$var_existencia=0;
			$var_existencia=$row_rsm['existencia'];
			$var_total_exis=$var_existencia+$var_cantidad_des;
			$var_sql="update materiales set existencia = '$var_total_exis' where cod_material=$var_cod_pro";
			$result = query($var_sql, $Conn); 
		}			
		//$rsm->close();	
   }
   //$rso->close();
   
	$var_sql="update ordenes_ne set estado = 'Anulado' where codigo = $var_cod_ord";			
	$result = query($var_sql, $Conn); 	

	$var_sql="update requisiciones set situacion = 'Revisar' where cod_requisicion=$x_cod_riqui";
	$result = query($var_sql, $Conn); 
	//$var_bandera = true;
}
if($var_accion=='camo')
{
	$rs = query("SELECT cod_requi FROM ordenes  where codigo = $var_cod_ord",$Conn);
	
	while ($row_rs = fetch_array($rs)) 
	{
		$x_cod_riqui=$row_rs['cod_requi'];
	}
	$var_sql="update ordenes set estado = 'Revisar' where codigo = $var_cod_ord";			
	$result = query($var_sql, $Conn); 	
	$var_sql="update requisiciones set situacion = 'Revisar' where cod_requisicion=$x_cod_riqui";
	$result = query($var_sql, $Conn); 
	//$var_bandera = true;
	
}
if($var_accion=='camne')
{

	$var_sql="update ordenes_ne set estado = 'Revisar' where codigo = $var_cod_ord";			
	$result = query($var_sql, $Conn); 	
}
//$tipo_orden=@$_POST["cod_orden_tipo"];
//echo "Var Tipo Orden: ".$var_tipo_orden2." Tipo Orden: ".$tipo_orden;	
//echo "<br> Var Proveedor: ".$var_proveedor;  

if ($var_tipo_orden <> "3")
{
	

	if ($var_proveedor=="0" && $var_estado=="Todas")
	{				
		$var_sql="select * from ordenes where tipo = '$var_tipo_orden2' order by codigo";
		
	}	
	
	if ($var_proveedor<>"0" && $var_estado=="Todas")
	{
		$rs = query("SELECT cod_proveedor,compania FROM proveedores  where cod_proveedor = '$var_proveedor' ",$Conn);
		while ($row_rs = fetch_array($rs)) 
		{
			$var_nom_proveedor=$row_rs['compania'];
		}
		//$rs->close();
					
		$var_sql="select * from ordenes where cod_provee = '$var_proveedor' and tipo = '$var_tipo_orden' order by codigo";				
	}
	if ($var_proveedor<>"0" && $var_estado<>"Todas")
	{				
		$rs = query("SELECT cod_proveedor,compania FROM proveedores  where cod_proveedor = '$var_proveedor' ",$Conn);
		while ($row_rs = fetch_array($rs)) 
		{
			$var_nom_proveedor=$row_rs['compania'];
		}
		//$rs->close();
		
		$var_sql="select * from ordenes where estado = '$var_estado' and cod_provee = '$var_proveedor' and tipo = '$var_tipo_orden' order by codigo";
	}
	if ($var_proveedor=="0" && $var_estado<>"Todas")
	{				
		$var_sql="select * from ordenes where estado = '$var_estado' and tipo = '$var_tipo_orden' order by codigo";
	}
}

if ($var_tipo_orden == "3")
{

	if ($var_centro=="0" && $var_estado=="Todas")
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne   order by codigo";		
		$var_bandera=true;
	}
	if ($var_centro<>"0" && $var_estado=="Todas")
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne where centro_costo = '$var_centro' order by codigo";		
		$var_bandera=true;
	}
	if ($var_centro<>"0" && $var_estado<>"Todas")
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne where estado = '$var_estado' and centro_costo = '$var_centro' order by codigo";		
		$var_bandera=true;
	}
	if ($var_centro=="0" && $var_estado<>"Todas")
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne where estado = '$var_estado' order by codigo";		
		$var_bandera=true;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:spry="http://ns.adobe.com/spry" class="fondo">
<head>
<title>SEIC</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../lib/common.js"></script>
<script type="text/javascript" src="../lib/Prototype/prototype.js"></script>
<style type="text/css">
<!--
body {
	background-image: url();
}
-->
</style></head>
<body>
<table width="100%">
  <tr>
    <td class="row-br"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
      <tr>
        <td>Consultas de Ordenes de Compra / Servicios y Notas de Entrega<span style="float:left"></span>          </td>
        <td><table border="0" align="right" cellpadding="0" cellspacing="0" onclick="javascript:window.location='reporte_ord.php';" class="btn_bg" style="cursor: pointer;">
            <tr>
              <td style="padding: 0px;" align="right"><div align="center"><img src="../imagenes/bt_left.gif" alt="" width="5" height="20" style="border-width: 0px;" /></div></td>
              <td class="btn_bg"><div align="center"><img src="../imagenes/ico_up.gif" width="16" height="16" /></div></td>
              <td class="btn_bg" style="padding: 0px 4px;"><div align="center">Regresar</div></td>
              <td style="padding: 0px;" align="left"><div align="center"><img src="../imagenes/bt_right.gif" alt="" width="5" height="20" style="border-width: 0px;" /></div></td>
            </tr>
            <table border="0" cellspacing="0" cellpadding="0" onclick="javascript:window.location='proveedores_add.php';">
              <tr>
                <td>
                  <? //btn('add','proveedores_list.php') ?>
                </td>
              </tr>
            </table>
        </table></td></tr>
    </table></td>
  </tr>
  <tr>
    <td><div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>		  <p>&nbsp;</p>
            <form name="form1" id="form1" method="post"  action=""><div align="center">
              <table width="499" border="1" align="center">
<!--                   <tr> -->
<!--                     <th class="tb-head" width="489" scope="col"><?php echo $var_tipo_orden; ?></th> -->
<!--                   </tr> -->
                  <tr>
				  <?php if($var_tipo_orden<>"3")
				  {?>
                    <th class="tb-head" scope="col">Proveedor</th>
                    </tr>
                  <tr>
                    <th class="tb-fila" scope="col"><p><span><?php if($var_proveedor=="0"){echo "TODOS";} else{echo $var_nom_proveedor;}?>
                    </span></p>                      </th>
                    </tr>
                  <tr><?php } else {?>
                    <th class="tb-head" scope="col">Centro de Costo</th>
                  </tr>
                  <tr>
                    <th class="tb-fila" scope="col"><span><?php if($var_centro==0){echo "Todos";}else {echo $var_centro; echo " - ";}?>   <?php echo $var_nom_centro; ?></span></th>
                  </tr>
                  <tr><?php } ?>
                    <th class="tb-head" scope="col">Tipo de Orden </th>
                    </tr>
                  <tr>
                    <th class="tb-fila" scope="col"><span>
                      <?php $conexion=conexion(); $consulta="SELECT * FROM ordenes_tipos WHERE cod_orden_tipo='".$var_tipo_orden."'";
										$resultado = query($consulta, $conexion);
										$fila = fetch_array($resultado);
										$descripcion=$fila['descripcion']; echo $descripcion; ?>
                    </span></th>
                  </tr>
                  <tr>
                    <th class="tb-head" scope="col">Estado</th>
                  </tr>
                  <tr>
                    <th class="tb-fila" scope="col"><span>
                      <?php if ($var_estado=="-"){echo "Todas";}else{echo $var_estado;} ?>
                    </span></th>
                  </tr>
                </table>
                <?php 
	  $contador=0;
	//echo "SQL: ".$var_sql." Centro:".$var_centro;
	 $rs = query($var_sql,$Conn);	

   	  while ($row_rs = fetch_array($rs)) 
	  { 
	  	$contador++;
	  }
	  //$rs->close();
	  if ($contador<>0 && $var_bandera == false)
	 {?>
                <table width="200" border="1" class="tb-fila">
                  <tr>
                    <td><div align="center" class="tb-head"><strong>Impresi&oacute;n General</strong> </div></td>
                  </tr>
                  <tr>
                    <td><div align="center"><a href="reporte_ord_general.php?cod_orden_tipo=<?php echo $var_tipo_orden2; ?>&proveedor=<?php echo $var_proveedor2; ?>&estado=<?php echo $var_estado2; ?>&centro=<?php echo $var_centro2; ?>" target="_blank" ><img src="../imagenes/ico_print.gif" width="16" height="16" border="0" /></a></div></td>
                  </tr>
                </table>
                <div align="center">
                <tr>
                  <table width="100%" border="1" align="center" cellpadding="2" cellspacing="2" class="tb-fila">
                    <tr class="tb-head">
                      <td width="47"><div align="center" class="tb-head">
                        <div align="left"><strong>N&uacute;mero</strong></div>
                      </div></td>
			<td width="47"><div align="center" class="tb-head">
                        <div align="left"><strong>Ref.</strong></div>
                      </div></td>
			<td width="47"><div align="center" class="tb-head">
                        <div align="left"><strong>Req.</strong></div>
                      </div></td>
                      <td width="47"><div align="center" class="tb-head">
                        <div align="left"><strong>Fecha</strong></div>
                      </div></td>
                      <td width="217"><div align="left"><span class="ewGroupAggregate"><strong>Proveedor</strong></span></div></td>
                      <td width="173"><div align="center" class="tb-head">
                        <div align="left"><strong>Unidad</strong></div>
                      </div></td>
                      <td width="182"><div align="center" class="tb-head">
                        <div align="left"><strong>Centro de Costo</strong> </div>
                      </div></td>
                      <td width="85"><div align="center" class="tb-head">
                        <div align="left"><strong>Monto Orden Bs. F.</strong> </div>
                      </div></td>
                      <td width="66"><div align="center" class="tb-head"><strong>Cambiar</strong></div></td>
                      <td width="66"><div align="center" class="tb-head">
                        <div align="left"><strong>Estado</strong></div>
                      </div></td>
                      <td width="50"><div align="center" class="tb-head">
                        <div align="left"><strong>Tipo</strong></div>
                      </div></td>
                      <td width="56"><div align="center" class="tb-head">
                        <div align="left"><strong>Anular</strong></div>
                      </div></td>
                      <td width="48"><p align="left" class="tb-head"><strong>Imprimir Tipo Compra</strong></p></td>
		      <td width="48"><p align="left" class="tb-head"><strong>Imprimir Tipo Servicios</strong></p></td>
				<td width="48"><p align="left" class="tb-head"><strong>Imprimir Otras Tipos Ordenes</strong></p></td>
		            </tr>
                    <?php

	  $rso = query($var_sql,$Conn);
		while ($row_rso = fetch_array($rso)) 
	  {
	  		$var_codigo=$row_rso['codigo'];
			$var_fecha=fecha($row_rso['fecha']);
			$var_unidad=$row_rso['unidad'];
			$var_centro_costo=$row_rso['centro_costo'];
			$var_monto_orden=$row_rso['monto_orden'];
			$var_estado=$row_rso['estado'];
			$var_cod_provee=$row_rso['cod_provee'];
			$var_cod_requi=$row_rso['cod_requi'];
			$var_codigo_ref=$row_rso['codigo_ref'];
			$var_tipo=$row_rso['tipo'];
	
			$conTipo = "SELECT * FROM ordenes_tipos WHERE cod_orden_tipo='".$var_tipo."'";
			$resTipo = query($conTipo, $conexion);
			$filaTipo = fetch_array($resTipo);
			$desTipo = $filaTipo['descripcion'];
			
			$rs = query("SELECT compania FROM proveedores where cod_proveedor = '$var_cod_provee'",$Conn);
			while ($row_rs = fetch_array($rs)) 
			{
				$var_cod_provee=$row_rs['compania'];		
			}
			//$rs->close();	
			
			$rs = query("SELECT descripcion FROM unidades where cod_unidad = $var_unidad",$Conn);
			while ($row_rs = fetch_array($rs)) 
			{		
				$var_nom_unidad=$row_rs['descripcion'];		
			}
			//$rs->close();
			
			$rs = query("SELECT descripcion FROM centros where cod_centro = '$var_centro_costo'",$Conn);
			while ($row_rs = fetch_array($rs)) 
			{		
				$var_nom_centro=$row_rs['descripcion'];		
			}
			//$rs->close();
	  ?>
                    <tr class="tb-fila">
                      <td><? echo $var_codigo; ?>
                          <div align="left"></div></td>
			<td><? echo $var_codigo_ref; ?>
                          <div align="left"></div></td>
			<td><? echo $var_cod_requi; ?>
                          <div align="left"></div></td>
                      <td><? echo $var_fecha; ?>
                          <div align="left"></div></td>
                      <td><? echo $var_cod_provee; ?></td>
                      <td><? echo $var_unidad; ?> - <?php echo $var_nom_unidad; ?>
  							<div align="left"></div></td>
                      <td><? echo $var_centro_costo; ?>- <?php echo $var_nom_centro; ?>  <div align="left"></div></td>
                      <td align="right"><? echo number_format($var_monto_orden, 2, ',', '.'); ?>
                          <div align="left"></div></td>
                      <td><div align="center">
                        <?php if($var_estado<>"Emitida"){}else{echo "<a href='buscar_reporte_ord.php?id=$var_codigo&acc=camo&tipo=$var_tipo_orden2&proveedor=$var_proveedor2&estado=$var_estado2&centro=$var_centro2'>"; }?>
                        <img title="Cambiar Estatus" src="<?php if($var_estado<>"Emitida"){echo "../imagenes/ico_est6.gif";}else{echo "../imagenes/ico_next.gif"; }?>" width="16" height="16" border="0" /></div></td>
                      <td><? echo $var_estado; ?>
                        <div align="left"></div></td>
							<td>
                        <div align="center"><?echo $var_tipo;?></div>
		      			
                      <td><div align="center"><?php if($var_estado=="Revisar"){echo "<a href='buscar_reporte_ord.php?id=$var_codigo&acc=anuo&tipo=$var_tipo_orden2&proveedor=$var_proveedor2&estado=$var_estado2&centro=$var_centro2'>"; }?><img title="Anular" src="<?php if($var_estado=="Revisar"){echo "../imagenes/ico_cancel.gif";}else{echo "../imagenes/ico_est6.gif"; }?>" width="16" height="16" border="0" /></a></div></td>
							<td>
                        <div align="center"><a href="ordenesprint3.php?id=<?php echo $var_codigo; ?>&desTipo=<?echo $desTipo?>" target="_blank"><img src="../imagenes/ico_print.gif" title="Imprimir Orden" width="16" height="16" border="0"/></a></div></td>
		    				<td>
                        <div align="center"><a href="ordenesprint4.php?id=<?php echo $var_codigo; ?>&desTipo=<?echo $desTipo?>" target="_blank" ><img src="../imagenes/ico_print.gif" width="16" height="16" border="0" /></a></div></td>
							<td>
                        <div align="center"><a href="ordenesprint5.php?id=<?php echo $var_codigo; ?>&desTipo=<?echo $desTipo?>" target="_blank" ><img src="../imagenes/ico_print.gif" width="16" height="16" border="0" /></a></div></td>
                    </tr>
      <?php } }?>
     <?php  if ($contador<>0 && $var_bandera == true){?>            </table>
                  <table width="200" border="1" align="center" class="tb-fila">
                    <tr>
                      <td><div align="center">Impresion General </div></td>
                    </tr>
                    <tr>
                      <td><div align="center"><a href="reporte_ord_general.php?tipo_orden=<?php echo $var_tipo_orden2; ?>&proveedor=<?php echo $var_proveedor2; ?>&estado=<?php echo $var_estado2; ?>&centro=<?php echo $var_centro2; ?>" target="_blank" ><img src="../imagenes/ico_print.gif" width="16" height="16" border="0" /></a></div></td>
                    </tr>
                  </table>
                  <table width="100%" border="1" align="center" cellpadding="2" cellspacing="2" class="">
                    <tr class="">
                      <td width="48"><div align="center" class="">
                        <div align="left">Numero</div>
                      </div></td>
                      <td width="53"><div align="center" class="">
                        <div align="left">Fecha</div>
                      </div></td>
                      <td width="232"><div align="center" class="">
                        <div align="left">Unidad</div>
                      </div></td>
                      <td width="286"><div align="center" class="">
                        <div align="left">Centro de Costo </div>
                      </div></td>
                      <td width="91"><div align="center" class="">
                        <div align="left">Estado</div>
                      </div></td>
                      <td width="100"><div align="left"><span class="">Cambiar</span></div></td>
                      <td width="100"><div align="center" class="">
                        <div align="left">Anular</div>
                      </div></td>
                      <td width="111"><p align="left" class="">Imprimir1</p></td>
		      <td width="111"><p align="left" class="">Imprimir2</p></td>
                    </tr>
                    <?php 

	  $rso = query($var_sql,$Conn);
   	  while ($row_rso = fetch_array($rso)) 
	  { 
		

			$var_codigo=$row_rso['codigo'];
			$var_fecha=fecha($row_rso['fecha']);
			$var_unidad=$row_rso['unidad'];
			$var_centro_costo=$row_rso['centro_costo'];
			$var_estado=$row_rso['estado'];
			$var_cod_requi=$row_rso['cod_req'];
			$rs = query("SELECT descripcion FROM unidades where cod_unidad = $var_unidad",$Conn);
			while ($row_rs = fetch_array($rs)) 
			{		
				$var_nom_unidad=$row_rs['descripcion'];
			}
			//$rs->close();
			
			$rs = query("SELECT descripcion FROM centros where cod_centro = '$var_centro_costo'",$Conn);
			while ($row_rs = fetch_array($rs)) 
			{		
				$var_nom_centro=$row_rs['descripcion'];		
			}
			//$rs->close();
	  
	  ?>
                    <tr class="tb-bg-in">
                      <td><? echo $var_codigo; ?>
                          <div align="left"></div></td>
                      <td><? echo $var_fecha; ?>
                          <div align="left"></div></td>
                      <td><? echo $var_unidad; ?> - <?php echo $var_nom_unidad; ?>                          <div align="left"></div></td>
                      <td><? echo $var_centro_costo; ?> - <?php echo $var_nom_centro; ?>                          <div align="left"></div></td>
                      	<td><? echo $var_estado; ?>
                          <div align="left"></div></td>
                      	<td><div align="center">
                        <?php if($var_estado<>"Emitida"){}else{echo "<a href='buscar_reporte_ord.php?id=$var_codigo&acc=camne&tipo=$var_tipo_orden2&proveedor=$var_proveedor2&estado=$var_estado2&centro=$var_centro2'>"; }?>
                        <img title="Cambiar Estado" src="<?php if($var_estado<>"Emitida"){echo "../imagenes/ico_est6.gif";}else{echo "../imagenes/ico_next.gif"; }?>" width="16" height="16" border="0" /></div></td>
                      	<td><div align="center">
                        <?php if($var_estado=="Emitida"){echo "<a href='buscar_reporte_ord.php?id=$var_codigo&acc=anun&tipo=$var_tipo_orden2&proveedor=$var_proveedor2&estado=$var_estado2&centro=$var_centro2'>"; }?>
                        <img title="Anular" src="<?php if($var_estado=="Emitida"){echo "../imagenes/ico_cancel.gif";}else{echo "../imagenes/ico_est6.gif"; }?>" width="16" height="16" border="0" /></div></td>
                      	<td>
                        <div align="center"><a href="ordenesneprint.php?id=<?php echo $var_cod_requi;?>" target="_blank" ><img src="../imagenes/ico_print.gif" width="16" height="16" border="0" /></a></div></td>
		      				<td>
                        <div align="center"><a href="ordenesneprint2.php?id=<?php echo $var_cod_requi; ?>" target="_blank" ><img src="../imagenes/ico_print.gif" width="16" height="16" border="0" /></a></div></td>
                      <!--    <td width="16"><a href="javascript:;" onclick="confirmar('Seguro de Borrar?','<? echo $filename ?>?rsac=del&amp;id=<? echo $id; ?>'); return self.rValue"><img src="imagenes/ico_basket.gif" alt="Borrar" width="15" height="15" border="0" /></a></td> -->
                    </tr>
                    <?php } }?>
                  </table>
                  <p>&nbsp;</p>
                </div>
                <p>&nbsp;</p>
            </div>
            </form>            </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>