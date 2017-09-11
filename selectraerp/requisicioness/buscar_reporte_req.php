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
require_once '../lib/pdfcommon.php';
include("../config_bd.php"); // archivo que llama a la base de datos
include("../header.php"); 
$Conn = conexion();//new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);

$var_accion=$_GET[acc];
if ($var_accion=='')
{
	$var_tipo_orden=$_POST[tipo_orden];
	$var_tipo_orden2=$_POST[tipo_orden];
	$var_estado=$_POST[estado];
	$var_estado2=$_POST[estado];
	$var_centro=$_POST[centro];	
	$var_centro2=$_POST[centro];	
}
else
{
	$var_tipo_orden=$_GET[tipo_orden];
	$var_tipo_orden2=$_GET[tipo_orden];
	$var_estado=$_GET[estado];
	$var_estado2=$_GET[estado];
	$var_centro=$_GET[centro];	
	$var_centro2=$_GET[centro];	
	$x_cod_riqui=$_GET[id];	
}
//echo $var_tipo_orden;
//echo $var_estado;
//echo $var_centro;
	
if ($var_centro<>'0')
{
	$rs = query("SELECT descripcion FROM centros where cod_centro = '".$var_centro."'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_centro=$row_rs['descripcion'];		
	}
	//$rs->close();
}

if ($var_tipo_orden<>'TODOS')
{
	$rs = query("SELECT descripcion as nom_orden FROM ordenes_tipos where cod_orden_tipo = '$var_tipo_orden'",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_orden=$row_rs['nom_orden'];		
	}
	//$rs->close();
}
	  
if ($var_accion=='anu')
{
	$var_sql="update requisiciones set situacion = 'Anulado' where cod_requisicion=$x_cod_riqui";
	$result = query($var_sql, $conectar); 
	mysql_close($conectar);
	
}
if ($var_accion=='cam')
{
	$var_sql="update requisiciones set situacion = 'Registrada' where cod_requisicion=$x_cod_riqui";
	$result = mysql_query($var_sql, $conectar); 
	mysql_close($conectar);
	
}

if ($var_tipo_orden<>"0" && $var_estado=="Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones where tipo = '$var_tipo_orden' order by cod_requisicion";	
}
if ($var_tipo_orden<>"0" && $var_estado<>"Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones where situacion = '$var_estado' and tipo = '$var_tipo_orden' order by cod_requisicion";	
}
if ($var_tipo_orden<>"0" && $var_estado<>"Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where cod_centro = '$var_centro' and situacion = '$var_estado' and tipo = '$var_tipo_orden' order by cod_requisicion";	
}

if ($var_tipo_orden<>"0" && $var_estado=="Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where cod_centro = '$var_centro' and tipo = '$var_tipo_orden' order by cod_requisicion";	
}

if ($var_tipo_orden=="0" && $var_estado=="Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones order by cod_requisicion";		
}

if ($var_tipo_orden=="0" && $var_estado=="Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where cod_centro = '$var_centro' order by cod_requisicion";		
}

if ($var_tipo_orden=="0" && $var_estado<>"Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones where situacion = '$var_estado' order by cod_requisicion";		
}

if ($var_tipo_orden=="0" && $var_estado<>"Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where situacion = '$var_estado' and cod_centro = '$var_centro' order by cod_requisicion";		
}

//echo $var_sql;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:spry="http://ns.adobe.com/spry" class="fondo">
<head>
<title>Consulta de Requisicionestitle>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
<table width="100%" class="tb-head" >
  <tr>
    <td class="tb-fila"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
      <tr>
        <td>Consulta de Requisiciones <span style="float:left"></span>          </td>
        <td><table border="0" align="right" cellpadding="0" cellspacing="0" onclick="javascript:window.location='consultas_req.php';" class="btn_bg" style="cursor: pointer;">
            <tr>
              <td style="padding: 0px;" align="right"><div align="center"><img src="../img_sis/bt_left.gif" alt="" width="5" height="20" style="border-width: 0px;" /></div></td>
              <td class="btn_bg"><div align="center"><img src="../img_sis/ico_up.gif" width="16" height="16" /></div></td>
              <td class="btn_bg" style="padding: 0px 4px;"><div align="center">Regresar</div></td>
              <td style="padding: 0px;" align="left"><div align="center"><img src="../img_sis/bt_right.gif" alt="" width="5" height="20" style="border-width: 0px;" /></div></td>
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
              <table width="499" border="1" align="center" class="tb-head">
                  <tr>
                    <th width="489" scope="col">Centro de Costo</th>
                  </tr>
                  <tr>
                    <th scope="col" class="tb-fila"><span ><?php if($var_centro==0){echo "TODOS";}else {echo $var_centro; echo " - ";}?>   <?php echo $var_nom_centro; ?></span></th>
                  </tr>
                  <tr>
                    <th scope="col">Tipo de Requisici&oacute;n </th>
                    </tr>
                  <tr>
                    <th scope="col" class="tb-fila"><span>
                      <?php if($var_tipo_orden<>"0"){echo $var_nom_orden;}else{echo "TODOS";} ?>
                    </span></th>
                  </tr>
                  <tr>
                    <th scope="col">Estado</th>
                  </tr>
                  <tr>
                    <th scope="col" class="tb-fila"><span>
                      <?php if ($var_estado=="-"){echo "Todas";}else{echo $var_estado;} ?>
                    </span></th>
                  </tr>
                </table>
					<br>
                <?php 
	  $contador=0;

	 $rs = query($var_sql,$Conn);	
	
   	  while ($row_rs = fetch_array($rs)) 
	  { 
	  	$contador++;
	  }
	  //$rs->close();

	if ($contador<>0)
	 {?>
                <table width="200" border="1"  >
                  <tr>
                    <td><div class="tb-head" align="center" ><strong>Impresi&oacute;n General</strong></div></td>
                  </tr>
                  <tr>
                    <td><div class="tb-fila" align="center"><a href="../fpdf/reporte_req_generalpdf.php?tipo_orden=<?php echo $var_tipo_orden2; ?>&estado=<?php echo $var_estado2; ?>&centro=<?php echo $var_centro2; ?>" target="_blank" ><img title="Imprimir" src="../img_sis/ico_print.gif" width="16" height="16" border="0" /></a></div></td>
                  </tr>
                </table>
					<br>
                <div align="center">
                  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2" class="">
                    <tr class="tb-head">
                      <td width="50"><div align="center" class="ewGroupAggregate">
                        <div align="left">Numero</div>
                      </div></td>
                      <td width="50"><div align="center" class="ewGroupAggregate">
                        <div align="left">Fecha</div>
                      </div></td>
                      <td width="128"><span class="ewGroupAggregate">Tipo de Requisici&oacute;n </span></td>
                      <td width="242"><div align="center" class="ewGroupAggregate">
                        <div align="left">Unidad</div>
                      </div></td>
                      <td width="177"><div align="center" class="ewGroupAggregate">
                        <div align="left">Centro de Costo </div>
                      </div></td>
                        <td width="71"><div align="center" class="ewGroupAggregate">
                        <div align="left">Estado</div>
                      </div></td>
                      <td></td>
			<td></td>
			<td></td>
			<td></td>
                    </tr>
                    <?php 

	  $rso = query($var_sql,$Conn);
	  $con=1;
   	  while ($row_rso = fetch_array($rso)) 
	  { 
		
	  		$var_codigo=$row_rso['cod_requisicion'];
			$var_fecha=fecha($row_rso['agregada_fecha']);
			$var_unidad=$row_rso['unidad'];
			$var_centro_costo=$row_rso['cod_centro'];
			$var_situacion=$row_rso['situacion'];
			$var_cod_provee=$row_rso['cod_provee'];
			$var_cod_requi=$row_rso['cod_requi'];
			$var_tipo=$row_rso['tipo'];
			
			$rs = query("SELECT descripcion FROM unidades where cod_unidad = '".$var_unidad."'",$Conn);
			while ($row_rs = fetch_array($rs)) 
			{		
				$var_nom_unidad=$row_rs['descripcion'];		
			}
			//$rs->close();
			
			$rs = query("SELECT descripcion FROM centros where cod_centro = '".$var_centro_costo."'",$Conn);
			while ($row_rs = fetch_array($rs)) 
			{		
				$var_nom_centro=$row_rs['descripcion'];		
			}
			//$rs->close();
			
			$var_sql="SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = '".$var_tipo."'";
			$rsu = query($var_sql,$Conn);		
			$row_rsu = fetch_array($rsu);
			$var_nom_tipo=$row_rsu['descripcion'];
			//$rsu->close();
			
		
	  	if(($con%2)==0){
			echo "<tr class=\"tb-fila\">";
		}else{
			echo "<tr class=\"tb-bg-in\">"; 
		}
	
	  
	  ?>
		
                    
                      <td><? echo $var_codigo; ?>
                          <div align="left"></div></td>
                      <td><? echo $var_fecha; ?>
                          <div align="left"></div></td>
                      <td><? echo $var_nom_tipo; ?></td>
                      <td><? echo $var_unidad; ?>
- <?php echo $var_nom_unidad; ?>                         
  <div align="left"></div></td>
                      <td><? echo $var_centro_costo; ?>- <?php echo $var_nom_centro; ?>  <div align="left"></div></td>
			<td><? echo $var_situacion; ?>
                        <div align="left"></div></td>
                      
                      
                      <?php
			if($var_situacion=="Revisar"){
				icono("javascript:confirmar('Desea Regresar  Esta Requisicion?','buscar_reporte_req.php?id=$var_codigo&acc=cam&tipo_orden=$var_tipo_orden2&estado=$var_estado2&centro=$var_centro2')", "Regresar a Origen", "back.gif");
			}else{ echo "<td><img width=\"16\" height=\"16\" align=\"left\" border=\"0\" title=\"En Proceso\" src=\"../imagenes/ico_est6.gif\"/></td>";}
			 if($var_situacion=="Registrada"){
				icono("javascript:confirmar('Desea Anular Esta Requisicion?','buscar_reporte_req.php?id=$var_codigo&acc=anu&tipo_orden=$var_tipo_orden2&estado=$var_estado2&centro=$var_centro2')", "Anular Requisicion", "ico_cancel.gif");

			}else{ echo "<td><img width=\"16\" height=\"16\" align=\"left\" border=\"0\" title=\"En Proceso\" src=\"../imagenes/ico_est6.gif\"/></td>";}
			iconoNuevopdf("requisicionespdf.php?id=$var_codigo&cod_centro=$var_centro_costo","Imprimir Reporte","ico_print.gif");

			 
		      if ($var_situacion=="Adjudicada"){
			icono("ver_proveedores.php?id=".$var_codigo."&codigo=".$var_codigo."&cod_centro=".$$var_centro_costo."&pagina=".$pagina.'&tipo_orden='.$var_tipo_orden.'&estado='.$var_estado.'&centro='.$var_centro, "Ver Proveedor", "ico_view.gif");}else{ echo "<td><img width=\"16\" height=\"16\" align=\"left\" border=\"0\" title=\"En Proceso\" src=\"../imagenes/ico_est6.gif\"/></td>";}
			$con++;
			 ?> 
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
