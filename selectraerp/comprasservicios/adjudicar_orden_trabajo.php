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

$url="adjudicar_orden_trabajo";
$modulo="Emision de Ordenes de Trabajo";
$tabla="ordenes";
$titulos=array("Codigo","(%) Descuento ","Tipo de Pago","Días de Pago","Tiempo de Entrega (días)","Tiempo de Garantia (dias)","Total Cotización (Bs)");
$indices=array("0","7","9","10","5","6","8");
$codigo=$_GET['codigo'];
$req=$_GET['cod_requisicion'];

if(isset($_POST["guardardatos"])) 
{
	$proveedor=$_POST['cod_proveedor'];
	$total_orden=$_POST['montoorden'];
	$total_iva=$_POST['montoiva'];
	$monto_excento=$_POST['monto_excento'];
	$imponible=$_POST['imponible'];
	$unidad_administrativa=$_POST['unidad'];
	$centrocosto=$_POST['centro_costo'];
	$fecha=$_POST['fecha_o'];
	$plazo_ejecucion=$_POST['plazo_ejecucion'];
	$tipo_orden=$_POST['tipo_orden'];
	$fecha_inicio=$_POST['fecha_inicio'];
	$fecha_terminacion=$_POST['fecha_terminacion'];
	$fecha_paralizacion=$_POST['fecha_paralizacion'];
	$fecha_reinicio=$_POST['fecha_reinicio'];
	$prorroga=$_POST['prorroga'];
	$fecha_firma=$_POST['fecha_firma'];
	$concepto=$_POST['concepto'];
	$observacion=$_POST['obser'];
	$numero_contrato=$_POST['numero_contrato'];
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
	
	$campos="codigo,codigo_ref, cod_cotizacion,cod_requi,cod_provee,monto_orden,monto_iva,monto_excento,imponible, fecha,unidad,centro_costo,diasentrega,concepto,obser,usuario,estado,fecha_inicio,fecha_terminacion,fecha_paralizacion,fecha_reinicio,prorroga,fecha_firma,numero_contrato";
	
	$valores="'".$cod_orden."','".$codigo_ref."','". $cod_cotizacion."','".$cod_requisicion."','".$proveedor."','".$total_orden."','".$total_iva."','".$monto_excento."','".$imponible."', '".fecha_sql($fecha)."','".$unidad_administrativa."','".$centrocosto."','".$plazo_ejecucion."','".$concepto."','".$observacion."','".$log_usr."','Revisar','".fecha_sql($fecha_inicio)."','".fecha_sql($fecha_terminacion)."','".fecha_sql($fecha_paralizacion)."','".fecha_sql($fecha_reinicio)."','".$prorroga."','".fecha_sql($fecha_firma)."','".$numero_contrato."'";
		
	$consulta="insert into ".$tabla." (".$campos.") values (".$valores.")";
	//echo $consulta."<br>";
		
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
			$consulta=" insert into ordenes_detalles values ('".$cod_orden."','".$codigoo."','".$cantidadd."','".$cantidadd."','".$precio."','".$ivaa."','".$sub_total."','".$total_producto."','".$cod_requisicion."','".$codigo_ref."','".$cod_cotizacion."')";
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
			alert("Se guardo con exito la Orden de Compra!!");
			
		</SCRIPT>
		
		<?php
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		   	location.href=\"emision_orden_contratos.php\" </SCRIPT>";
	
	
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
</SCRIPT>
</head>
<body>
<FORM name="sampleform" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']."?cod_requisicion=$req&codigo=$codigo"; ?>">
<TABLE  width="100%">
<TBODY>
<?

titulo("Emisión de Ordenes de Trabajo","","emision_orden_trabajo.php","1");

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
   <td width="15%" class="tb-fila" align="center"><strong>Fecha de Requisici&oacute;n</strong> </td>
   <td width="15%" class="tb-fila" align="center"><strong>Fecha de Firma de Contrato</strong></td>
   <td width="15%" class="tb-fila" align="center"><strong>Fecha de Emisión de Orden de Contrato</strong></td>
   <td width="29%" class="tb-fila" align="center"><strong>Unidad Solicitante</strong></td>
   <td width="26%" class="tb-fila" align="center"><strong>Centro de Costo</strong></td>
 </tr>
 <tr>
   <td align="center"><span><?php echo fecha($fila["fecha"]);?><input name="fecha" type="hidden" id="fecha" value="<?php echo $fila["fecha"];?>"></span></td>
   <td><table>
     <TR>
       <td width="90" align="center"><input readonly name="fecha_firma" id="fecha_firma" type="text"  value="<?php if(isset($var_cod_orden)) {echo $var_fecha;}else{echo date('d/m/Y');} ?>" size="12" maxlength="12" /></td>
       <td width="32"><input  name="f_fecha" type="image" id="f_fecha" src="../lib/jscalendar/cal.gif" />
       <script type="text/javascript"> 
       Calendar.setup( {inputField:"fecha_firma",ifFormat:"%d/%m/%Y",button:"f_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
       </script></td>
     </TR>
   </table></td>
   <td><table>
     <TR>
       <td width="90" align="center"><input readonly name="fecha_o" id="fecha_o" type="text"  value="<?php if(isset($var_cod_orden)) {echo $var_fecha;}else{echo date('d/m/Y');} ?>" size="12" maxlength="12" /></td>
       <td width="32"><input  name="b_fecha" type="image" id="b_fecha" src="../lib/jscalendar/cal.gif" />
       <script type="text/javascript"> 
       Calendar.setup( {inputField:"fecha_o",ifFormat:"%d/%m/%Y",button:"b_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
       </script></td>
     </TR>
   </table></td>
   <td align="center"><span><?php  echo $fila["cod_unidad"]; ?> - <?php echo $fila["descripcion_unidad"]; ?><input name="unidad" type="hidden" id="unidad" value="<?php echo $fila["cod_unidad"]; ?>"></span></td>
   <td align="center"><p><?php  echo $fila["cod_centro"]; ?> - <?php echo $fila["descripcion_centro"]; ?><input name="centro_costo" type="hidden" id="centro_costo" value="<?php echo $fila["cod_centro"]; ?>"></p></td>
 </tr>
 <tr class="tb-fila">
          <td align="center"><strong>Nro De Contrato</strong></td>
          <td align="center"><strong>Imponible</strong></td>
          <td align="center"><strong>Monto I.V.A</strong></td>
          <td align="center"><strong>Monto Exento</strong></td>
	  <td align="center"><strong>Monto de Contrato</strong></td>
 </tr>
 <tr>
   <td align="center"><input name="numero_contrato" type="text" id="numero_contrato" size="20"  value="" /></td>
   <td align="center">Bs.<input name="imponible" type="text" id="imponible" size="15" readonly="true" value="<?echo $imponible?>"/></td>
   <td align="center">Bs.<input name="montoiva" type="text" id="montoiva" size="15" readonly="true" value="<?php echo $total_iva;?>"/></td>
   <td align="center">Bs.<input name="montoexcento" type="text" id="montoexcento" size="15" readonly="true" value="<?php echo $monto_excento;?>"/></td>
   <td align="center">Bs.<input name="montoorden" type="text" id="montoorden" size="15" readonly="true" value="<?php echo $total_orden; ?>" /></td>
  </tr>
</table>

<table width="100%" border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;">
  <tr class="tb-fila">
    <td width="12%" align="center"><strong>Plazo de Ejecucion</strong></td>
    <td width="12%" align="center"><strong>Fecha Acta de Inicio</strong></td>
    <td  width="18%" align="center"><strong>Fecha Acta Terminación</strong></td>
    <td width="18%" align="center"><strong>Fecha Acta Paralización</strong></td>
    <td width="12%" align="center"><strong>Fecha Acta Reinicio</strong></td>
    <td width="12%" align="center"><strong>Porroga</strong></td>
    <td width="16%" align="center"><strong>Tipo Orden</strong></td>
  </tr>
  <tr>
     <td align="center"><input name="plazo_ejecucion" type="text" id="plazo_ejecucion" size="20"  value="" /></td>
      <td><table>
       <TR>
       <td width="90" align="center"><input readonly name="fecha_inicio" id="fecha_inicio" type="text"  value="<?php if(isset($var_cod_orden)) {echo $var_fecha;}else{echo date('d/m/Y');} ?>" size="12" maxlength="12" /></td>
       <td width="32"><input  name="i_fecha" type="image" id="i_fecha" src="../lib/jscalendar/cal.gif" />
       <script type="text/javascript"> 
       Calendar.setup( {inputField:"fecha_inicio",ifFormat:"%d/%m/%Y",button:"i_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
       </script></td>
       </TR>
     </table></td>
     <td><table>
      <TR>
       <td width="90" align="center"><input readonly name="fecha_terminacion" id="fecha_terminacion" type="text"  value="<?php if(isset($var_cod_orden)) {echo $var_fecha;}else{echo date('d/m/Y');} ?>" size="12" maxlength="12" /></td>
       <td width="32"><input  name="t_fecha" type="image" id="t_fecha" src="../lib/jscalendar/cal.gif" />
       <script type="text/javascript"> 
       Calendar.setup( {inputField:"fecha_terminacion",ifFormat:"%d/%m/%Y",button:"t_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
       </script></td>
      </TR>
     </table>
      </td>
      <td><table>
     <TR>
       <td width="90" align="center"><input readonly name="fecha_paralizacion" id="fecha_paralizacion" type="text"  value="<?php if(isset($var_cod_orden)) {echo $var_fecha;}else{echo date('d/m/Y');} ?>" size="12" maxlength="12" /></td>
       <td width="32"><input  name="p_fecha" type="image" id="p_fecha" src="../lib/jscalendar/cal.gif" />
       <script type="text/javascript"> 
       Calendar.setup( {inputField:"fecha_paralizacion",ifFormat:"%d/%m/%Y",button:"p_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
       </script></td>
     </TR>
   </table></td>
      <td><table>
     <TR>
       <td width="90" align="center"><input readonly name="fecha_reinicio" id="fecha_reinicio" type="text"  value="<?php if(isset($var_cod_orden)) {echo $var_fecha;}else{echo date('d/m/Y');} ?>" size="12" maxlength="12" /></td>
       <td width="32"><input  name="r_fecha" type="image" id="r_fecha" src="../lib/jscalendar/cal.gif" />
       <script type="text/javascript"> 
       Calendar.setup( {inputField:"fecha_reinicio",ifFormat:"%d/%m/%Y",button:"r_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
       </script></td>
     </TR>
   </table></td>
      <td align="center"><input name="prorroga" type="text" id="prorroga" size="20"  value="" /></td>
      <td align="center"><span class="ewTableHeader"><?php echo $fila["tipo_orden"];?></span><input id="tipo_orden" name="tipo_orden" type="hidden" value="<?php echo $fila["tipo"];?>"></td>
  </tr>
</table>

<br>
<table width="100%"  border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;" >
  <tr class="tb-fila">
    <td width="50%" height="25" align="center"><strong>Concepto</strong></td>
    <td width="50%" align="center"><strong>Observaci&oacute;n</strong></td>
  </tr>
  <tr>
    <td width="50%" height="25"><textarea name="concepto"  cols=60 id="concepto"><?php if($var_concepto2<>''){echo $var_concepto2;}else{echo $fila['concepto'];} ?></textarea></td>
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

<TABLE  width="100%" height="50">
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
	$codproducto=$canmateriales['cod_producto'];
	$materialesiva = "SELECT iva,descripcion,codigo_cuenta FROM materiales WHERE cod_material='".$codproducto."'";
				
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
	<input  type="button" name="guardar"  id="guardar" value="Guardar" onclick="javascript:validar()" />&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onclick="javascript:self.history.back();">
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