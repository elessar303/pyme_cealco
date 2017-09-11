<?
if (!isset($_SESSION)) 
{
  session_start();
}
?>
<?php

require_once '../lib/common.php';
include ("../header.php");
$conexion=conexion();
//echo $conexion;

$url="adjudicar_orden_servicios";
$modulo="Emision de Ordenes de Servicios";
$tabla="ordenes";
$titulos=array("Codigo","(%) Descuento ","Tipo de Pago","Días de Pago","Tiempo de Entrega (días)","Tiempo de Garantia (dias)","Total Cotización (Bs)");
$indices=array("0","7","9","10","5","6","8");
$codigo=$_GET['codigo'];
$req=$_GET['cod_requisicion'];

    
    
if(isset($_POST["guardardatos"])) 
{
	$proveedor=$_POST['cod_proveedor'];
	$total_orden=$_POST['montoorden'];
        $saldo =$_POST['montoorden'];
	$total_iva=$_POST['montoiva'];
	$monto_excento=$_POST['monto_excento'];
	$imponible=$_POST['imponible'];
	$unidad_administrativa=$_POST['unidad'];
	$centrocosto=$_POST['centro_costo'];
	$fecha=$_POST['fecha_o'];
	$diascredito=$_POST['diascredito'];
	$tipo_orden=$_POST['tipo_orden'];
	$formapago=$_POST['formapago'];
	$condicioncompra=$_POST['condicioncompra'];
	$tipomoneda=$_POST['tipomoneda'];
	$montodivisa=$_POST['montodivisa'];
	$tasacambio=$_POST['tasacambio'];
	$diasentrega=$_POST['diasentrega'];
	$concepto=$_POST['concepto'];
	$observacion=$_POST['obser'];
	$log_usr=$_SESSION['nombre'];
	
	$consulta1="select MAX(codigo) as cod_orden from ordenes";
	$resultado1=query($consulta1,$conexion);
	$fila1=fetch_array($resultado1);

	$consulta2="select MAX(codigo) as codigo_ref from ordenes_tipos where cod_orden_tipo=".$tipo_orden;
	$resultado2=query($consulta2,$conexion);
	$fila2=fetch_array($resultado2);
			
	$cod_requisicion=$_GET['cod_requisicion'];
	$cod_cotizacion=$_GET['codigo'];
	$cod_orden=$fila1['cod_orden']+1;
	$codigo_ref=$fila2['codigo_ref']+1;
	
	$campos="codigo,codigo_ref, cod_cotizacion,cod_requi,cod_provee,monto_orden,monto_iva,monto_excento,imponible, fecha,unidad,centro_costo,dias_credito,diasentrega,tipomoneda, tipo,formapago,condicioncompra,montodivisa,tasacambio,concepto,obser,usuario,estado,saldo";
	
	$valores="'".$cod_orden."','".$codigo_ref."','". $cod_cotizacion."','".$cod_requisicion."','".$proveedor."','".$total_orden."','".$total_iva."','".$monto_excento."','".$imponible."', '".fecha_sql($fecha)."','".$unidad_administrativa."','".$centrocosto."','".$diascredito."','".$diasentrega."','".$tipomoneda."', '".$tipo_orden."','".$formapago."','".$condicioncompra."','".$montodivisa."','".$tasacambio."','".$concepto."','".$observacion."','".$log_usr."','Revisar','".$saldo."'";
	
		
	$consulta="insert into ".$tabla." (".$campos.") values (".$valores.")";
	//echo $consulta;
		
	$resultado=query($consulta,$conexion);
		
	//Guardar los detalles cotizacion
		
	$requi = "SELECT * FROM cotizaciones_detalles WHERE (cod_cotizacion = $cod_cotizacion and estatus='Revisar') ORDER BY cod_producto ";
	$materiales=query($requi,$conexion);
		
	$cantidad=num_rows($materiales);
		
	for($i=1;$i<=$cantidad;$i++){
		//if (($precio=$_POST['precio'.$i])!=0){
			$codigoo=$_POST['codigo'.$i];
			$cantidadd=$_POST['cantidad'.$i];
			//$descuentoo=$_POST['descuento'.$i];
			$precio=$_POST['precio'.$i];
			$ivaa=$_POST['iva'.$i];
			$sub_total=($cantidadd*$precio);
			$total_producto=$_POST['totalfinal'.$i];
			$consulta=" insert into ordenes_detalles values ('".$cod_orden."','".$codigoo."','".$cantidadd."','".$cantidadd."','".$precio."','".$ivaa."','".$sub_total."','".$total_producto."','".$cod_requisicion."','".$codigo_ref."','".$cod_cotizacion."','".$correl."')";
			//echo "<br>";
			//echo $consulta;
			$resultado=query($consulta,$conexion);
		//}
	}
	$consulta3="update ordenes_tipos set codigo=".$codigo_ref." where cod_orden_tipo=".$tipo_orden;
	$resultado2=query($consulta3,$conexion);
	
	//exit(0);
		?>
		<SCRIPT language="JavaScript" type="text/javascript">
			alert("Se guardo con exito la Orden de Servicio!!");
			
		</SCRIPT>
		
		<?php
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		     location.href=\"emision_orden_servicios.php\" </SCRIPT>";
	
	
	
	
}
//$consulta="select * from ".$tabla." where codigo=".$codigo;
$consulta="select r.cod_requisicion,r.unidad,r.cod_centro,r.concepto,r.descripcion as observacion,r.fecha,r.tipo,c.codigo,c.cod_requisicion,c.cod_proveedor,c.total,c.tiempo_entrega,p.cod_proveedor,p.compania,p.rif,p.direccion1,u.cod_unidad,u.descripcion as descripcion_unidad,ce.cod_centro,ce.descripcion as descripcion_centro,ce.sel_sector,ce.sel_programa,ce.sel_actividad,t.cod_orden_tipo,t.descripcion as tipo_orden  from requisiciones as r 
INNER JOIN cotizaciones as c on r.cod_requisicion = c.cod_requisicion
LEFT JOIN proveedores as p on c.cod_proveedor = p.cod_proveedor
LEFT JOIN unidades as u on r.unidad = u.cod_unidad
LEFT JOIN centros as ce on r.cod_centro = ce.cod_centro
LEFT JOIN ordenes_tipos as t on r.tipo = t.cod_orden_tipo
where r.cod_requisicion = ".$req."  and c.codigo=".$codigo;
$resultado=query($consulta,$conexion);
$fila=fetch_array($resultado);

?>
<html class="fondo">
<head>
  <title></title>
  <link href="../estilos.css" rel="stylesheet" type="text/css">
  <SCRIPT language="JavaScript" type="text/javascript" src="../lib/common.js">
  </SCRIPT>
<script language="javascript" src="cal2.js"></script>
<script language="javascript" src="cal_conf2.js"></script>
<SCRIPT language="JavaScript" type="text/javascript">


function validar(){
	var bandera=false
	if(document.sampleform.cod_proveedor.value==""){
		alert("Debe seleccionar un proveedor!!!")
		return
	}else{
		document.sampleform.submit();
	}
}

function actualizar_formulario(valor,num_reg,num_reg)
	{
	
	total_gen = parseFloat(window.document.sampleform.total.value)	
	
	//capturamos el valor del iva
	var cadena="iva"+valor
	var cadena_temp=document.getElementById(cadena)
	valor_iva = parseFloat(cadena_temp.value)
	
	//capturamos el valor del precio
	cadena="precio"+valor
	
	cadena_temp=document.getElementById(cadena)
	textoCampo =parseFloat(cadena_temp.value)

	//valor de la cantidad
	cadena="cantidad"+valor
	cadena_temp=document.getElementById(cadena)
	cantidad =parseFloat(cadena_temp.value)
	
	//alert(total_gen+" "+textoCampo+" "+cantidad)
	
	var total=(textoCampo*cantidad)+valor_iva
	
	cadena="totalfinal"+valor
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=total
	
	cadena="totalfinall"+valor
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=total

	total=0
	for(i=1;i<=num_reg;i++){
		var cadena="totalfinal"+i
		var temp=document.getElementById(cadena)
		total=total+parseFloat(temp.value)
	}
	totaltotal=total
	
		if (window.document.sampleform.descuento.value==0){
			window.document.sampleform.total.value = total.toFixed(2)
		}else{
			
			if (window.document.sampleform.tipodescuento.value=="(%)"){
				total=total-((total*window.document.sampleform.descuento.value)/100)
			}else{
				if (window.document.sampleform.tipodescuento.value=="Monto Bs."){
					
					total=total-window.document.sampleform.descuento.value
				}
				else{	
					alert("Recuerde que debe seleccionar un tipo de Descuento!!!")
				}
			}
			
		}
		if (total>=0){
			window.document.sampleform.total.value = total.toFixed(2)
		}
		else{
			alert("El descuento es mayor al monto total de la Cotizacion!!!")
			window.document.sampleform.descuento.value=0
			window.document.sampleform.total.value =totaltotal
		}
	
	}
	function checkMyForm(valor,num_reg,var_por_iva) 
	{
	
		var cadena,temp, i,total=0,cad_iva,temp_iva,cad_cantidad,temp_cantidad,sub_total=0
		var cad_iva_sino,temp_iva_sino,cad_iva_sino_o,temp_iva_sino_o,cadena_total,temp_total,cadena_imponible,temp_imponible
	
	//cadena de cantidad
		cad_cantidad="cantidad"+valor
		temp_cantidad=document.getElementById(cad_cantidad)
	
	//cadena de iva
		cad_iva_sino="iva"+valor
		temp_iva_sino=document.getElementById(cad_iva_sino)
		cad_iva_sino_o="iva_sino_o"+valor
		temp_iva_sino_o=document.getElementById(cad_iva_sino_o)
	
	//cadena total
		cadena_total="totalfinal"+valor
		temp_total=document.getElementById(cadena_total)
		cadena_total_o="totalfinall"+valor
		temp_total_o=document.getElementById(cadena_total_o)
	
	
		cadena="precio"+valor
		temp_precio=document.getElementById(cadena)
		var_pre=parseFloat(temp_precio.value)
		
		if (!var_pre)
		{
			temp_iva_sino.value = 0
			temp_iva_sino_o.value = 0	
		}else{
			
			cad_iva="iva_sino_opt"+valor
			temp_iva=document.getElementById(cad_iva)
				
				if (temp_iva.checked)
				{
					
					temp_iva.value=1
					textoCampo1 = parseFloat(temp_precio.value)
					
					cantidad = parseFloat(temp_cantidad.value)
					textoCampo=textoCampo1*cantidad
				
					temp_iva_sino.value=parseFloat(textoCampo*var_por_iva/100)
					temp_iva_sino_o.value=parseFloat(textoCampo*var_por_iva/100)
					
					sub_total=parseFloat(textoCampo)
					textoCampo_iva=parseFloat(temp_iva_sino_o.value)
					temp_total.value=textoCampo_iva+sub_total
					temp_total_o.value=textoCampo_iva+sub_total
				
					
					
					
				}else
				{
					temp_iva.value=1
					var_monto_por_iva=parseFloat(temp_iva_sino.value)
					var_monto_sub_total=parseFloat(temp_total.value)
					temp_total.value= var_monto_sub_total-var_monto_por_iva
					temp_total_o.value= var_monto_sub_total-var_monto_por_iva
				//var_monto_total=parseFloat(window.document.fproveedoresadd.montoorden.value)
					temp_iva_sino.value=0
					temp_iva_sino_o.value=0
					
				}
			}
	//codigo nuevo
	
		
		
		
		
		total=0
		for(i=1;i<=num_reg;i++){
			var cadena="totalfinal"+i
			var temp=document.getElementById(cadena)
			total=total+parseFloat(temp.value)
		}
		totaltotal=total
		if (window.document.sampleform.descuento.value==0){
			window.document.sampleform.total.value = total.toFixed(2)
		}else{
			
			if (window.document.sampleform.tipodescuento.value=="(%)"){
				total=total-((total*window.document.sampleform.descuento.value)/100)
			}else{
				if (window.document.sampleform.tipodescuento.value=="Monto Bs."){
					
					total=total-window.document.sampleform.descuento.value
				}
				else{	
					alert("Recuerde que debe seleccionar un tipo de Descuento!!!")
				}
			}
			
		}
		if (total>=0){
			window.document.sampleform.total.value = total.toFixed(2)
		}
		else{
			alert("El descuento es mayor al monto total de la Cotizacion!!!")
			window.document.sampleform.descuento.value=0
			window.document.sampleform.total.value =totaltotal
		}
	//********************************************************************
	
	
		
		
	}
	function actualizar_descuento(num_reg){
		var total,totaltotal
		total=0
		for(i=1;i<=num_reg;i++){
			var cadena="totalfinal"+i
			var temp=document.getElementById(cadena)
			total=total+parseFloat(temp.value)
			
		}
		totaltotal=total
		
		var cadena="descuento"
		var temp=document.getElementById(cadena)
		descuento=parseFloat(temp.value)
		if (window.document.sampleform.descuento.value==0){
			window.document.sampleform.total.value = total.toFixed(2)
		}else{
			
			
			if (window.document.sampleform.tipodescuento.value=="(%)"){
				total2=((total*descuento)/100)
				total=parseFloat(total)-total2
				if (total>0){
					window.document.sampleform.total.value =total
				}
				else{
					alert("El descuento es mayor al monto total de la Cotizacion!!!")
					window.document.sampleform.descuento.value=0
					window.document.sampleform.total.value =totaltotal
				}
				
				
			}else{
				if (window.document.sampleform.tipodescuento.value=="Monto Bs."){
					total=total-window.document.sampleform.descuento.value
					if (total>0){
						window.document.sampleform.total.value =total
					}
					else{
						alert("El descuento es mayor al monto total de la Cotizacion!!!")
						window.document.sampleform.descuento.value=0
						window.document.sampleform.total.value =totaltotal
					}
				}
				else{	
					alert("Recuerde que debe seleccionar un tipo de Descuento!!!")
				}
			}
			
		}
	}
</SCRIPT>
</head>
<body>
<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']."?cod_requisicion=$req&codigo=$codigo"; ?>">
<TABLE  width="100%">
<TBODY>
<?
/*
$consulta="select max(codigo) as valor from cwprogra";
$resultado_progra=query($consulta,$conexion);
//echo $consulta;
$fila_progra=fetch_array($resultado_progra);
$max_progra=$fila_progra['valor'];
$valor=$max_progra+1;
*/

titulo("Emisión de Ordenes de Servicios","","emision_orden_servicios.php","1");

$i=0;
$cont=0;
foreach($titulos as $nombre){
	$campo=mysql_field_name($resultado,$indices[$i]);
	//$codigo=$_GET['codigo'];
}
$requi = "SELECT * FROM cotizaciones_detalles WHERE cod_cotizacion =".$codigo." AND estatus='Revisar' ";
$materiales2=query($requi,$conexion);
while($canmateriales=fetch_array($materiales2)){
	$totalregistros+=1;
	if($canmateriales['iva']<>0){
	$total_iva=$total_iva+$canmateriales['iva'];
	$imponible=$imponible+($canmateriales['cantidad']*$canmateriales['precio']);
	}else{
	$monto_excento=$monto_excento+($canmateriales['cantidad']*$canmateriales['precio']);
	}
	$sub_total=$sub_total+($canmateriales['cantidad']*$canmateriales['precio']);
}
$total_orden=$total_iva+$sub_total;
?>

<table width="100%" border="0">
 <tr class="tb-head">
  <td width="15%" class="tb-head"><strong>N&uacute;mero de Requisici&oacute;n:</strong> </td>
  <td width="5%" align="left" ><?php  echo $req;?></td>
  <td width="10%" align="left"><strong>Se&ntilde;ores:</strong></td>
  <td width="45%" colspan="2" ><span><?php echo $fila["compania"]?></span><input name="cod_proveedor" id="cod_proveedor" type="hidden" value="<?php echo $fila["cod_proveedor"];?>"></td>
  <td width="15%"><strong>N&uacute;mero de Cotizaci&oacute;n:</strong></td>
  <td width="10%" align="left"><?echo $codigo;?></td>
 </tr>
</table>

<BR>

<table width="100%" border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;">
 <tr>
   <td width="7%" class="tb-fila" align="center"><strong>Situaci&oacute;n</strong></td>
   <td width="8%" class="tb-fila" align="center"><strong>Fecha de Requisici&oacute;n</strong> </td>
   <td width="15%" class="tb-fila" align="center"><strong>Fecha de Orden</strong></td>
   <td width="29%" class="tb-fila" align="center"><strong>Unidad Solicitante</strong></td>
   <td width="26%" class="tb-fila" align="center"><strong>Centro de Costo</strong></td>
   <td width="15%" class="tb-fila" align="center"><strong>Monto Orden</strong></td>
 </tr>
 <tr>
   <td align="center"><a href="<?php echo "adjudicar_orden_compras.php?cod_requisicion=";?><? echo $fila["cod_requisicion"]; ?>&acc=cam" ><img src="../img_sis/ico_edit.gif" width="16" height="16" border="0" /></a></td>
   <td align="center"><span><?php echo fecha($fila["fecha"]);?><input name="fecha" type="hidden" id="fecha" value="<?php echo $fila["fecha"];?>"></span></td>
   <td><table>
     <TR>
       <td width="90" align="center"><input readonly name="fecha_o" id="fecha_o" type="text"  value="<?php if(isset($var_cod_orden)) {echo $var_fecha;}else{echo date('d/m/Y');} ?>" size="12" maxlength="12" /></td>
       <td width="32"><input  name="image" type="image" id="b_fecha" src="../lib/jscalendar/cal.gif" />
       <script type="text/javascript"> 
       Calendar.setup( {inputField:"fecha_o",ifFormat:"%d/%m/%Y",button:"b_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
       </script></td>
     </TR>
   </table></td>
   <td align="center"><span><?php  echo $fila["cod_unidad"]; ?> - <?php echo $fila["descripcion_unidad"]; ?><input name="unidad" type="hidden" id="unidad" value="<?php echo $fila["cod_unidad"]; ?>"></span></td>
   <td align="center"><p><?php  echo $fila["cod_centro"]; ?> - <?php echo $fila["descripcion_centro"]; ?><input name="centro_costo" type="hidden" id="centro_costo" value="<?php echo $fila["cod_centro"]; ?>"></p></td>
   <td align="center">Bs.<input name="montoorden" type="text" id="montoorden" size="10" readonly="true" value="<?php echo $total_orden; ?>" /></td>
 </tr>
 <tr class="tb-fila">
          <td align="center"><strong>D&iacute;as de Cr&eacute;dito</strong></td>
          <td colspan="2" align="center"><p><strong>Imponible</strong></p></td>
          <td align="center"><div ><strong>Monto I.V.A</strong></td>
          <td align="center"><strong>Monto Exento</strong></td>
          <td align="center"><strong>Tipo de Orden</strong></td>
 </tr>
 <tr>
   <td height="25" align="center"><input name="diascredito" type="text" id="diascredito" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" size="10"  width="50" <?php if(!isset($cod_orden)){ ?> value="0.00"<?php } else {echo "value='$dias_credito'";}?>/></td>
   <td colspan="2" align="center">Bs.<input name="imponible" type="text" id="imponible" readonly="true" value="<?echo $imponible?>"/></td>
   <td align="center">Bs.<input name="montoiva" type="text" id="montoiva" readonly="true" value="<?php echo $total_iva;?>"/></td>
   <td align="center">Bs.<input name="monto_excento" type="text" id="monto_excento" readonly="true" value="<?php echo $monto_excento;?>"/></td>
   <td align="center"><span class="ewTableHeader"><?php echo $fila["tipo_orden"];?></span><input id="tipo_orden" name="tipo_orden" type="hidden" value="<?php echo $fila["tipo"];?>"></td>
  </tr>
</table>

<table width="100%" border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;">
  <tr class="tb-fila">
    <td width="30" align="center"><strong>Forma de Pago </strong></td>
    <td colspan="2" width="35" align="center"><p><strong>Condiciones de Compra </strong></p>
    </td>
    <td align="center"><strong>Tipo Moneda</strong></td>
    <td width="30" align="center"><strong>Tasa de Cambio</strong></td>
    <td width="30" align="center"><strong>Monto Divisa</strong></td>
    <td width="20%" align="center"><strong>Plazo de Entrega en dias</strong></td>
  </tr>
  <tr>
     <td align="center">
	<select name="formapago" id="formapago">
	<?php if ($formapag==''){ ?>
		<option value="Abono en cuenta">Abono en cuenta </option>
		<option value="Carta de Crédito">Carta de Crédito </option>
		<option  selected="selected" value="Cheque">Cheque</option>
	<?php }else{
		if ($formapag=='Abono en cuenta'){?>
			<option value="Abono en cuenta" selected="selected">Abono en cuenta </option>
		<? } else{ ?>
			<option value="Abono en cuenta" >Abono en cuenta </option>
		<?} 
		if ($formapag=='Carta de Crédito'){ ?>
			<option value="Carta de Crédito" selected="selected">Carta de Crédito </option>
		<? } else{?>
			<option value="Carta de Crédito" >Carta de Crédito </option>
		<?} if ($formapag=='Carta de Crédito'){ ?>
			<option  selected="selected" value="Cheque" >Cheque</option>
		<? } else{ ?>
			<option   value="Cheque">Cheque</option>
	<? } } ?>
	</select>
      </td>
      <td colspan="2" width="35" align="center">
	<select name="condicioncompra" id="condicioncompra">
	<?php if ($condicioncompr==''){ ?>
		<option value="C.I.F.">C.I.F. </option>
		<option value="F.A.S.">F.A.S. </option>
		<option value="F.O.B.">F.O.B. </option>
		<option  selected="selected" value="Otros">Otros </option>
	<?php }else{	
		if ($condicioncompr=='C.I.F.'){?>
			<option value="C.I.F." selected="selected">C.I.F. </option>
		<? }else{ ?>
			<option value="C.I.F.">C.I.F. </option>
		<? } if ($condicioncompr=='F.A.S.'){ ?>
			<option value="F.A.S." selected="selected">F.A.S. </option>
		<?}else { ?>
			<option value="F.A.S.">F.A.S. </option>
		<?} if ($condicioncompr=="F.O.B."){ ?>
			<option value="F.O.B." selected="selected">F.O.B. </option>
		<?} else{ ?>
			<option value="F.O.B.">F.O.B. </option>
		<? } if ($condicioncompr=='Otros'){ ?>
			<option  value="Otros" selected="selected">Otros </option>
		<?} else {?>
			<option value="Otros">Otros </option>
	<? } }?>
	</select>
     </td>
     <td width="30" align="center">
	<select name="tipomoneda" id="tipomoneda">
	<? if ($tipomoned==''){ ?>
		<option  selected="selected" value="Bolivares (Bs)">Bolivares (Bs.F)</option>
		<option value="Dolares ($)">Dolares ($) </option>
	<?} else { 
		if ($tipomoned=='Bolivares (Bs)'){?>
			<option  selected="selected" value="Bolivares (Bs.)">Bolivares (Bs.F)</option>
		<? } else { ?>
			<option  value="Bolivares (Bs)">Bolivares (Bs)</option>
		<? } if ($tipomoned=='Dolares ($)'){ ?>
			<option value="Dolares ($)" selected="selected">Dolares ($) </option>
		<?} else { ?>
			<option value="Dolares ($)">Dolares ($) </option>
	<? } } ?>
	</select>
      </td>
      <td width="30" align="center"><input name="tasacambio" type="text" id="tasacambio" value="<?php if ($tasacambi!=''){ echo $tasacambi; } ?>" /></td>
      <td width="30" align="center"><input name="montodivisa" type="text" id="montodivisa" value="<?php if ($montodivis!=''){ echo $montodivis;}?> " /></td>
      <td width="30" align="center"><input name="diasentrega" type="text" id="diasentrega" value="<?php echo $fila['tiempo_entrega'];?> " /></td>
  </tr>
</table>

<br>

<table width="100%" border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;">
  <tr class="tb-fila"> 
    <td align="center"><strong>Lugar de Entrega</strong></td>
    <td align="center"><strong>Lugar de Compra</strong></td>
  </tr>
  <tr>
    <td width="50%" height="25"><textarea name="entrega"  cols=60 id="entrega"><?php if ($fila!=''){ echo $entreg; } ?></textarea></td>
    <td width="50%"><textarea name="compra"  cols=60 id="compra"><?php if ($compr!=''){ echo $compr;} ?></textarea></td>
  </tr>
</table>
<br>
<table width="100%"  border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;" >
  <tr class="tb-fila">
    <td width="50%" height="25" align="center"><strong>Concepto</strong></td>
    <td width="50%" align="center"><strong>Observaci&oacute;n</strong></td>
  </tr>
  <tr>
    <td width="50%"><textarea name="concepto"  cols=60 id="concepto"><?php if($var_concepto2<>''){echo $var_concepto2;}else{echo $fila['concepto'];} ?></textarea></td>
    <td width="50%"><textarea name="obser" cols="60" id="obser"><?php echo $fila['observacion']; ?>
    </textarea></td>
  </tr>
</table>

<br>
<table width="100%">
  <tr>
   <td height="30" class="tb-tit" align="center"><strong>Detalles de Materiales <?echo $modulo?></strong></td>
  </tr>
</table>

<TABLE  width="100%" height="100">
<tr class="tb-head">
    <td  align="center"><strong>C&oacute;digo</strong></td>
    <td align="center"><strong>Descripci&oacute;n</strong></td>
    <td align="center"><strong>Cantidad</strong></td>
    <td align="center"><strong>Precio Unitario</strong></td>
    <td align="center"><strong>I.V.A.</strong></td>
	<!--<td align="center"><strong>Descuento</strong></td>-->
    <td align="center"><strong>Total</strong></td>
    <td align="center"><strong>I.V.A.</strong></td>
</tr><?php

	$materiales=query($requi,$conexion);
	$cantidad=1;
	while($canmateriales=fetch_array($materiales)){
		$iva=$canmateriales['cod_producto'];
		$materialesiva = "SELECT iva,descripcion FROM materiales WHERE cod_material='".$iva."'";
				
		$ivamaterial=query($materialesiva,$conexion);
		$ivamaterial1=fetch_array($ivamaterial);
		$var_por_iva=$ivamaterial1['iva'];
		$var_por_iva= number_format($var_por_iva, 0);
		
	?>
<tr>
  <td width="10" ><input  disabled="true"  type="text"  size="10"  value="<?php echo  $canmateriales['cod_producto'];?>"/>	<INPUT type="hidden" name="codigo<?php echo $cantidad?>" id="codigo<?php echo $cantidad?>"  value="<?php echo $canmateriales['cod_producto'];?>"/></td>
  <td ><input  disabled="true" name="descrip" id="descrip" type="text"  size="60"  value="<?php echo $ivamaterial1['descripcion'];?>" style="width:100%"></td>
  <td ><input  disabled="true" type="text"  size="15"  value="<?php echo $canmateriales['cantidad'];?>" style="width:100%"/><INPUT type="hidden" name="cantidad<?php echo $cantidad?>" id="cantidad<?php echo $cantidad?>" value="<?php echo $canmateriales['cantidad'];?>"/></td>
  <td ><input disabled="true" name="precio<?php echo $cantidad?>" id="precio<?php echo $cantidad?>" type="text"  size="10" value= "<?php echo $canmateriales['precio'];?>" style="width:100%"/><input name="precio<?php echo $cantidad?>" id="precio<?php echo $cantidad?>" type="hidden" value= "<?php echo $canmateriales['precio'];?>" /></td>
  <td ><input  name="iva_sino_o<?php echo $cantidad?>" id="iva_sino_o<?php echo $cantidad?>" disabled="true" type="text"  size="10" style="width:100%"  value= "<?php echo $canmateriales['iva'];?>"/><input name="iva<?php echo $cantidad?>" id="iva<?php echo $cantidad?>"  type="hidden"  value= "<?php echo $canmateriales['iva'];?>" /></td>
<!--<td ><input name="descuento<?php echo $cantidad?>" id="descuento<?php echo $cantidad?>" type="text" style="width:100%" size="10" value="0.00 "/></td> -->
  <td  ><input disabled='disable' name="totalfinall<?php echo $cantidad?>" id="totalfinall<?php echo $cantidad?>" type="text" style="width:100%" size="10"  value= "<?php echo ($canmateriales['precio']*$canmateriales['cantidad'])+$canmateriales['iva'];?>"/><input type="hidden" name="totalfinal<?php echo $cantidad?>" id="totalfinal<?php echo $cantidad?>" value= "<?php echo ($canmateriales['precio']*$canmateriales['cantidad'])+$canmateriales['iva'];?>"/></td>
  <td><input   name="iva_sino_opt<?php echo $cantidad?>" type="checkbox" id="iva_sino_opt<?php echo $cantidad?>" value="checkbox" <?if ($canmateriales['iva']!=0){ echo 'checked="checked"';}?>  onchange="checkMyForm(<?php echo $cantidad?>,<?php echo $totalregistros; ?>,<?php echo $var_por_iva; ?>);" ></td>	
</tr>
<?php
	$cantidad+=1;
}
?>
</tr>
</table>
<tr >
<TABLE  width="100%" height="100">
  <tr>
    <td class="" align="right">
        <input  type="hidden" name="guardardatos"  id="guardardatos" value="Guardar" />
	<input  type="button" name="guardar"  id="guardar" value="Guardar" onClick="javascript:validar()" />&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onClick="javascript:self.history.back();">
    </td>
  </tr>
</table>
</tr>
</tbody>
</table>
</FORM>
</body>
</html>
<?
cerrar_conexion($conexion);
?>