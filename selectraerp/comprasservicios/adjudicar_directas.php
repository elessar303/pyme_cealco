<?php
session_start();
ob_start();

//echo $_SESSION['nombre'];
$usr = $_SESSION['nombre'];
require_once '../lib/config.php';
require_once '../lib/common.php';
include("../config_bd.php"); // archivo que llama a la base de datos
include ("../header.php");

$conexion_conf=conexion_conf();
$consulta_conf="select sobregirop,tipo_compromiso from parametros";
$resultado_conf=query($consulta_conf,$conexion_conf);
$fila_conf=fetch_array($resultado_conf);
$sobregirop=$fila_conf['sobregirop'];
$compromiso = $fila_conf['tipo_compromiso'];
//$tipo_presupuesto = $fila_conf['tipo_presupuesto'];
cerrar_conexion($conexion_conf);




$conexion=conexion();
$accion=$_GET['accion'];
$cod_orden=$_GET['cod_orden'];
$rsac=$_GET['rsac'];
$cod_pro=$_GET['cod_pro'];
$correl=$_GET['correl'];

if($accion=='Modificar'){

if($rsac=='del')
{
	
	$rs = query("delete from ordenes_detalles where cod_ord=$cod_orden and cod_pro='".$cod_pro."' and correl=".$correl,$conexion);

	$consulta_det="select * from ordenes_detalles INNER JOIN materiales ON ordenes_detalles.cod_pro=materiales.cod_material where cod_ord=".$cod_orden;		
	$resultado_det=query($consulta_det,$conexion);
	while( $fila_det=fetch_array($resultado_det))
	{
		$consulta="select * from materiales where cod_material='".$fila_det[1]."'";
		$rm=query($consulta,$conexion);
	
		$material=fetch_array($rm);
		
		if($fila_det[5]>0){
			$imponible=$imponible+$fila_det[6];
		}else{
			$exento=$exento+$fila_det[6];
		}
		$total_general=$total_general+$fila_det[7];
		$total_iva=$total_iva+$fila_det[5];
	}
	if($imponible=='')
	{
		$imponible=0;
	}
	if($exento=='')
	{
		$exento=0;
	}
	$update_sql="update ordenes set monto_orden=".$total_general.", monto_iva=".$total_iva.", monto_excento=".$exento.", imponible=".$imponible." where codigo=".$cod_orden;
	//echo $update_sql;
	$update_result=query($update_sql,$conexion);
	
}

if (isset($_POST['guardar'])){
	//echo "codigo orden";
	$var_codigo=$cod_orden;		
		
	
	$x_fecha=fecha_sql($_POST['fecha_o']);//fecha
	$x_unidad=$_POST['unidad']; //unidad
	$x_centro_cos=$_POST['centro_cos']; //centro de costo
	$x_montoorden=$_POST['montoorden1']; //monto de la orden total
	$x_diascredito=$_POST['diascredito']; //dias de credito
	$x_concepto=$_POST['concepto'];
	$x_obser=$_POST['obser'];
	$x_tipo=$_POST['tipo'];
	$x_imponible=$_POST['imponible'];
	$x_montoiva=$_POST['montoiva'];
	$x_montoexcento=$_POST['montoexcento'];
	$x_cod_proveedor=$_POST['proveedor'];
	$formapago=$_POST['formapago'];
	$codicioncompra=$_POST['condicioncompra'];
	$tipomoneda=$_POST['tipomoneda'];
	$tasacambio=$_POST['tasacambio'];
	$montodivisa=$_POST['montodivisa'];
	$entrega=$_POST['entrega'];
	$compra=$_POST['compra'];
	$diasentrega=$_POST['diasentrega'];
	

	$var_sql="update ordenes set  fecha='$x_fecha', unidad='$x_unidad',centro_costo='$x_centro_cos',monto_orden='$x_montoorden',dias_credito=$x_diascredito,concepto='$x_concepto',obser='$x_obser',tipo=$x_tipo,imponible=$x_imponible,monto_iva=$x_montoiva,monto_excento=$x_montoexcento,cod_provee='$x_cod_proveedor',formapago='$formapago',condicioncompra='$condicioncompra',tipomoneda='$tipomoneda',tasacambio='$tasacambio',montodivisa='$montodivisa',entrega='$entrega',compra='$compra',diasentrega='$diasentrega',usuario='".$_SESSION['nombre']."' where codigo=$var_codigo";
	$update=query($var_sql,$conexion);

	$var_total_reg=$_POST['contador'];
	
	$consult="select codigo_ref from ordenes where codigo=".$var_codigo;
	$result=query($consult,$conexion);
	$fila_cons=fetch_array($result);
	$var_codigo_cons=$fila_cons['codigo_ref'];

	for($i=1; $i<=$var_total_reg; $i++){
		$conexion=conexion();

		$cad_mat="codigo".$i;
		$cad_cant="cantidad".$i;
		$cad_precio="precio".$i;
		$cad_iva_sino="iva_sino_opt".$i;
		$cad_correl="correl".$i;
		$x_cod_material = $_POST[$cad_mat];	
		$x_cantidad = $_POST[$cad_cant];	
		$x_precio = $_POST[$cad_precio];	
		$x_iva_sino = $_POST[$cad_iva_sino];	
		$x_correl = $_POST[$cad_correl];
		//buscar el material 
		$consulta="select * from materiales where cod_material='$x_cod_material'";
		$rm=query($consulta,$conexion);
		$material=fetch_array($rm);


		$total = $x_precio*$x_cantidad;
		if($x_iva_sino==true){
			$montoiva=($total*$material['iva'])/100;
			$sub_total = $montoiva + $total;
			
		}else{
			$montoiva=0;
			$sub_total = $montoiva + $total;
		}
		$valida="select * from ordenes_detalles where cod_ord=$var_codigo and cod_pro='".$x_cod_material."' and correl=".$x_correl;
		//echo "Valida: ".$valida."<br>";
		$query=query($valida,$conexion);
		if($x_cantidad!=0 || $x_precio!=0)
		{
			if(num_rows($query)!=0){
				$var_sql="update ordenes_detalles set cantidad_pedida='$x_cantidad',precio='$x_precio',iva='$montoiva',total='$total',total_gen='$sub_total' where cod_ord=$var_codigo and cod_pro='$x_cod_material' and correl=".$x_correl;
				$update=query($var_sql,$conexion);
			}else{
				$b=$var_total_reg+1;
				$var_sql="insert into ordenes_detalles values('$var_codigo','$x_cod_material','$x_cantidad','','$x_precio','$montoiva','$total','$sub_total','','$var_codigo_cons','','$b')";
				//echo "var_sql: ".$var_sql."<br>";
				$insert=query($var_sql,$conexion);
			}
		}
		
	}
	//exit(0);
	
	echo "<script language=\"javascript\" >alert ('Se modifico la Orden Directa!!!') 
	location.href='emision_ord_directas.php'</script>";
	
	}

}

if($accion!='Modificar'){

if (isset($_POST['guardar'])){
	//codigo orden
	$rs = query("SELECT max(codigo) as codigo FROM ordenes",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_codigo=$row_rs['codigo'];		
	}	
	

	$var_codigo+=1;
	$x_fecha=fecha_sql($_POST['fecha_o']);//fecha
	$x_unidad=$_POST['unidad']; //unidad
	$x_centro_cos=$_POST['centro_cos']; //centro de costo
	$x_montoorden=$_POST['montoorden1']; //monto de la orden total
	$x_diascredito=$_POST['diascredito']; //dias de credito
	$x_concepto=$_POST['concepto'];
	$x_obser=$_POST['obser'];
	$x_tipo=$_POST['tipo'];
	$x_imponible=$_POST['imponible'];
	$x_montoiva=$_POST['montoiva'];
	$x_montoexcento=$_POST['montoexcento'];
	$x_cod_proveedor=$_POST['proveedor'];
	$formapago=$_POST['formapago'];
	//$codicioncompra=$_POST['condicioncompra'];
	//$tipomoneda=$_POST['tipomoneda'];
	//$tasacambio=$_POST['tasacambio'];
	//$montodivisa=$_POST['montodivisa'];
	//$entrega=$_POST['entrega'];
	//$compra=$_POST['compra'];
	//$diasentrega=$_POST['diasentrega'];

	
	$consulta2="select MAX(codigo) as codigo_ref from ordenes_tipos where cod_orden_tipo=".$x_tipo;
	$resultado2=query($consulta2,$conexion);
	$fila2=fetch_array($resultado2);
	$codigo_ref=$fila2['codigo_ref']+1;
	
	$cambio="update ordenes_tipos set codigo=$codigo_ref where cod_orden_tipo=$x_tipo";
	$upcambio=query($cambio,$conexion);

	$var_sql="insert into ordenes values($var_codigo,'$x_fecha','$x_unidad','$x_centro_cos','$x_montoorden',$x_diascredito,'$x_concepto','$x_obser',$x_tipo,$x_imponible,$x_montoiva,$x_montoexcento,'-','$x_cod_proveedor',0,'','Revisar','$x_montoorden','$codigo_ref','','$formapago','','$tipomoneda','','','','','','','".$_SESSION['nombre']."','','','','','','','','','','')";
	$insert=query($var_sql,$conexion);

	$var_total_reg=$_POST['contador'];

	for($i=1; $i<=$var_total_reg; $i++){


		$cad_mat="codigo".$i;
		$cad_cant="cantidad".$i;
		$cad_precio="precio".$i;
		$cad_iva_sino="iva_sino_opt".$i;
		$x_cod_material = $_POST[$cad_mat];	
		$x_cantidad = $_POST[$cad_cant];	
		$x_precio = $_POST[$cad_precio];	
		$x_iva_sino = $_POST[$cad_iva_sino];	
		
		//buscar el material 
		$consulta="select * from materiales where cod_material='$x_cod_material'";
		$rm=query($consulta,$conexion);
		$material=fetch_array($rm);


		$total = $x_precio*$x_cantidad;
		if($x_iva_sino==true){
			$montoiva=($total*$material['iva'])/100;
			$sub_total = $montoiva + $total;
			
		}else{
			$montoiva=0;
			$sub_total = $montoiva + $total;
		}
		if ($x_cantidad>0 && $x_precio>0)
		{
			$var_sql="insert into ordenes_detalles values('$var_codigo','$x_cod_material','$x_cantidad','','$x_precio','$montoiva','$total','$sub_total','','$codigo_ref','',$i)";
			$insert=query($var_sql,$conexion);
		}
	}
	
	echo "<script language=\"javascript\" >alert ('Se guardo la Orden Directa!!!') 
	location.href='emision_ord_directas.php'</script>";
	
	}

}
if($accion=="Modificar" ){
	$conexion=conexion();
	$consulta="select * from ordenes where  codigo=$cod_orden and estado<>'Anulado'";
	$query=query($consulta,$conexion);
	$orden=fetch_array($query);
}


?>
<?php include ("../ewconfig.php") ?>
<?php //include ("db.php") ?>
<?php include ("proveedores_info.php") ?>
<?php include ("../advsecu.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php include ("../ewupload.php") ?>
<?php 

?>


<script language="javascript" src="../lib/common.js"></script>

<script>


function actualizar_formulario(valor)
{
	var cadena="correl"+valor
	var correl=document.getElementById(cadena)
	
	//capturamos el valor del ivaopt
	var cadena="iva_sino_opt"+valor
	var valor_iva_opt=document.getElementById(cadena)
	
	
	//capturamos el valor del iva
	var cadena="iva_sino"+valor
	var cadena_temp=document.getElementById(cadena)
	valor_iva = (parseFloat(cadena_temp.value)).toFixed(2)
	
	//capturamos el valor del iva del material
	var cadena="iva_sino_mate"+valor
	var cadena_temp=document.getElementById(cadena)
	valor_iva_mate = (parseFloat(cadena_temp.value)).toFixed(2)
	
	//capturamos el valor del precio
	cadena="precio"+valor
	cadena_temp=document.getElementById(cadena)
	textoCampo =(parseFloat(cadena_temp.value)).toFixed(2)
	
	//valor de la cantidad
	cadena="cantidad"+valor
	cadena_temp=document.getElementById(cadena)
	cantidad =(parseFloat(cadena_temp.value)).toFixed(2)
	
	//valor de la cantidad
	cadena="contador"
	cadena_temp=document.getElementById(cadena)
	num_reg=(parseFloat(cadena_temp.value)).toFixed(2)

	
	
	var total=((textoCampo*cantidad)).toFixed(2)

	var total_iva=0
	if(valor_iva_opt.checked){
		total_iva=(total*valor_iva_mate)/100

	}
	
	//individual
	cadena="iva_sino"+valor
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=parseFloat(total_iva).toFixed(2)

	cadena="total"+valor
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=parseFloat(total+total_iva).toFixed(2)
	//alert(cadena_temp)
	totalf=0
	for(i=1;i<=num_reg;i++){
		var cadena="total"+i
		var temp=document.getElementById(cadena)
		totalf=totalf+parseFloat(temp.value)
	}


	cadena="montoorden"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)
		
	cadena="montoorden1"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)

	totalf=0
	for(i=1;i<=num_reg;i++){
		var cadena="precio"+i
		var temp=document.getElementById(cadena)
		var cadena="cantidad"+i
		var temp1=document.getElementById(cadena)
		var cadena="iva_sino_opt"+i
		var valor_iva_opt=document.getElementById(cadena)

		if(valor_iva_opt.checked){
			totalf=totalf+(parseFloat(temp.value)*parseFloat(temp1.value))	
		}
	}
	cadena="imponible"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)

	cadena="imponible1"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)

		
	totalf=0
	//totalf=parseFloat(totalf.value).toFixed(2)
	for(i=1;i<=num_reg;i++){
		var cadena="iva_sino"+i
		var temp=document.getElementById(cadena)
		totalf=totalf+parseFloat(temp.value)
	}
	cadena="montoiva"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)
	cadena="montoiva1"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)
	
	totalf=0
	for(i=1;i<=num_reg;i++){
		var cadena="iva_sino_opt"+i
		var valor_iva_opt=document.getElementById(cadena)
		
		if(!valor_iva_opt.checked){
			var cadena="total"+i
			var temp=document.getElementById(cadena)
			totalf=(totalf+parseFloat(temp.value)).toFixed(2)
		}
	}
	cadena="montoexcento"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=parseFloat(totalf).toFixed(2)
		
	cadena="montoexcento1"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=parseFloat(totalf).toFixed(2)
	


	
	
}

function actualizar_iva(valor){
	//capturamos el valor del ivaopt
	var cadena="iva_sino_opt"+valor
	var valor_iva_opt=document.getElementById(cadena)
	
	//capturamos el valor del iva del material
	var cadena="iva_sino_mate"+valor
	var cadena_temp=document.getElementById(cadena)
	valor_iva_mate = (parseFloat(cadena_temp.value)).toFixed(2)
	
	//capturamos el valor del precio
	cadena="precio"+valor
	cadena_temp=document.getElementById(cadena)
	textoCampo =(parseFloat(cadena_temp.value)).toFixed(2)

	//valor de la cantidad
	cadena="cantidad"+valor
	cadena_temp=document.getElementById(cadena)
	cantidad =(parseFloat(cadena_temp.value)).toFixed(2)


	//valor de la cantidad
	cadena="contador"
	cadena_temp=document.getElementById(cadena)
	num_reg=(parseFloat(cadena_temp.value)).toFixed(2)
	
	var total=((textoCampo*cantidad)).toFixed(2)
	//alert(total)
	if(valor_iva_opt.checked){
		total_iva=(total*valor_iva_mate)/100
	//alert (valor_iva_mate)
	cadena="iva_sino"+valor
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=total_iva.toFixed(2)
	cadena="total"+valor
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=(parseFloat(total)+parseFloat(total_iva)).toFixed(2)

	}
	if(!valor_iva_opt.checked){
		total_iva=0

		cadena="iva_sino"+valor
		cadena_temp=document.getElementById(cadena)
		cadena_temp.value=0

		cadena="total"+valor
		cadena_temp=document.getElementById(cadena)
		cadena_temp.value=(parseFloat(total)+parseFloat(total_iva)).toFixed(2)

	}


	totalf=0
	for(i=1;i<=num_reg;i++){
		var cadena="total"+i
		var temp=document.getElementById(cadena)
		totalf=totalf+parseFloat(temp.value)
	}


	cadena="montoorden"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)
		
	cadena="montoorden1"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)

	totalf=0
	for(i=1;i<=num_reg;i++){
		var cadena="precio"+i
		var temp=document.getElementById(cadena)
		var cadena="cantidad"+i
		var temp1=document.getElementById(cadena)
		var cadena="iva_sino_opt"+i
		var valor_iva_opt=document.getElementById(cadena)

		if(valor_iva_opt.checked){
			totalf=totalf+(parseFloat(temp.value)*parseFloat(temp1.value))	
		}
	}
	cadena="imponible"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)

	cadena="imponible1"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)

		
	totalf=0
	for(i=1;i<=num_reg;i++){
		var cadena="iva_sino"+i
		var temp=document.getElementById(cadena)
		totalf=totalf+parseFloat(temp.value)
	}
	cadena="montoiva"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)
	cadena="montoiva1"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)
	
	totalf=0
	for(i=1;i<=num_reg;i++){
		var cadena="iva_sino_opt"+i
		var valor_iva_opt=document.getElementById(cadena)
		
		if(!valor_iva_opt.checked){
			var cadena="total"+i
			var temp=document.getElementById(cadena)
			totalf=totalf+parseFloat(temp.value)
		}
	}
	cadena="montoexcento"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)
		
	cadena="montoexcento1"
	cadena_temp=document.getElementById(cadena)
	cadena_temp.value=totalf.toFixed(2)
	
}



</script>


<? titulo("Emisión de ordenes","","emision_ord_directas.php","1");?>

<form name="sampleform" id="sampleform" action="adjudicar_directas.php?accion=<? if($accion=="Modificar"){ echo "Modificar&cod_orden=".$cod_orden;}?>" method="post"  onSubmit="return validar(<?php echo $con; ?>);">
</p>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
	<tr class="tb-head">
	  <td width="12%" class="tb-head"><strong></strong> </td>
	  <td width="5%" align="left" >
        <div align="center"></div>
      <div align="center"></div></td>
	  <td width="15%" align="right"><strong>Se&ntilde;ores:</strong><input type="hidden" name="bandera" id="bandera" value="0"></td>
	
      <td width="50%" colspan="2" ><span >

		<?php 
			$conexion=conexion();
			$consulta_t="select * from proveedores order by compania";
			$resultado_tipo=query($consulta_t,$conexion);
		?>
		<SELECT name="proveedor" id="proveedor">
			<?while($fila_tipo=fetch_array($resultado_tipo)){
				$cod_tipo=$fila_tipo['cod_proveedor'];
				echo $cod_tipo;
				$descripcion_tipo=$fila_tipo['compania'];
				if($accion=="Modificar" && $orden['cod_provee']==$cod_tipo){
					echo "<option value=\"$cod_tipo\" selected='true'>$descripcion_tipo</option>";
				}else{
					echo "<option value=\"$cod_tipo\">$descripcion_tipo</option>";
				}	
			}?>
		</SELECT>
     
     
</span></td>
      <td width="30%" ><?php if(isset($var_cod_orden)){ ?>
        <table width="22" border="0">
          <tr>
            
          </tr>
        </table>        <?php } ?>      </td>
	</tr>
</table>
<br>
<table  width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div align="center">
      <table width="100%" border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;">
        <tr>
          <td width="7%" class="tb-fila"><div align="center"><strong>Situaci&oacute;n</strong></div></td>
          <td width="15%" class="tb-fila"><div align="center"><strong>Fecha de Orden</strong> </div></td>
          <td width="29%" class="tb-fila"><div align="center"><strong>Unidad Solicitante</strong> </div></td>
          <td width="26%" class="tb-fila"><div align="center"><strong>Centro de Costo</strong> </div></td>
          <td width="15%" class="tb-fila"><div align="center"><strong>Monto Orden</strong> </div></td>
          </tr>
        <tr>
          <td><div align="center">
            <p >
                <?php  echo 'Revisar';?>
</p>
            
            <p >
			 
                  </p>
          </div></td>
          
          <td><table style="border:none" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table>
   	  <TR>
		<td width="90" align="center"><input readonly name="fecha_o" id="fecha_o" type="text"  value="<?php if(isset($_GET['cod_orden'])) {echo fecha($orden['fecha']);}else{echo date('d/m/Y');} ?>" size="12" maxlength="12" /></td>
		<td width="32"><input  name="image" type="image" id="b_fecha" src="../lib/jscalendar/cal.gif" />
		<script type="text/javascript"> 
		Calendar.setup( {inputField:"fecha_o",ifFormat:"%d/%m/%Y",button:"b_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
		</script></td>
		</TR>
		</table></td>
              
            </tr>
          </table></td>
		

	<td ><div align="center" ><span>
		<?php 
			$conexion=conexion();
			$consulta_t="SELECT * FROM unidades";
			$resultado_tipo=query($consulta_t,$conexion);
		?>
		<SELECT name="unidad" id="unidad"  style="width:300px" >
			<?while($fila_tipo=fetch_array($resultado_tipo)){
				$cod_tipo=$fila_tipo['cod_unidad'];
				$descripcion_tipo=$fila_tipo['descripcion'];
				if($accion=="Modificar" && $orden['unidad']==$cod_tipo){
					echo "<option value=\"$cod_tipo\" selected='true'> $cod_tipo - $descripcion_tipo</option>";
				}else{	
					echo "<option value=\"$cod_tipo\"> $cod_tipo - $descripcion_tipo</option>";
				}
			}?>
			
		</SELECT>
	</span></div></td>

	 <td><div align="center">
		
		<?php 
			$conexion=conexion();
			$consulta_t="SELECT * FROM centros";
			$resultado_tipo=query($consulta_t,$conexion);
		?>
		<SELECT name="centro_cos" id="centro_cos" style="width:300px">
			<?while($fila_tipo=fetch_array($resultado_tipo)){
				$cod_tipo=$fila_tipo['cod_centro'];
				$descripcion_tipo=$fila_tipo['descripcion'];
				if($accion=="Modificar" && $orden['centro_costo']==$cod_tipo){
					echo "<option value=\"$cod_tipo\" selected='true'>$descripcion_tipo</option>";
				}else{
					echo "<option value=\"$cod_tipo\">$descripcion_tipo</option>";
				}
			}?>
		</SELECT>
	</div></td>
         
      

          <td>
            <div align="center">
              Bs. F. 
                <input name="montoorden" type="text" id="montoorden" size="10" readonly  disabled="true" value="<? if($accion=="Modificar"){echo $orden['monto_orden'];}else{ echo '0.00';} ?>" />
		<input name="montoorden1"  type="hidden" id="montoorden1"  value="<? if($accion=="Modificar"){echo $orden['monto_orden'];}else{ echo '0.00';} ?>"  />
            
	</div></td>
          </tr>
        <tr class="tb-fila">
          <td><div align="center" class="tb-fila"><strong>D&iacute;as de Cr&eacute;dito</strong> </div></td>
          <td colspan=""><div align="center">
            <p><strong>Imponible</strong></p>
            </div></td>
          <td><div align="center"><strong>Monto I.V.A</strong></div></td>
          <td><div align="center"><strong>Monto Exento</strong> </div></td>
          <td><div align="center"><strong>Tipo de Orden</strong></div></td>
        </tr>
        <tr>
          <td height="25"><div align="center">
            <input name="diascredito" type="text" id="diascredito" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" size="10"  width="50" value="<? if($accion=="Modificar"){echo $orden['dias_credito'];}else{ echo '0.00';} ?>"/>
          </div></td>
          <td colspan=""><div align="center">
            Bs. 
              <input name="imponible" type="text" id="imponible1"   onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"  disabled="true" size="20" width="100"  value="<? if($accion=="Modificar"){echo $orden['imponible'];}else{ echo '0.00';} ?>"/>
		<input name="imponible"  type="hidden" id="imponible"   size="20" width="100"  value="<? if($accion=="Modificar"){echo $orden['imponible'];}else{ echo '0.00';} ?>"/>
          </div></td>
          <td><div align="center">
            Bs.
              <input name="montoiva" type="text" id="montoiva1"   name="montoiva1" disabled="true" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" size="20"  value="<? if($accion=="Modificar"){echo $orden['monto_iva'];}else{ echo '0.00';} ?>"/>
		<input name="montoiva"  type="hidden" id="montoiva" name="montoiva"  size="20"  value="<? if($accion=="Modificar"){echo $orden['monto_iva'];}else{ echo '0.00';} ?>"/>
          </div></td>
          <td><div align="center">
            Bs.
		<input name="montoexcento" type="text" id="montoexcento1"   disabled="true" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" size="20"  value="<? if($accion=="Modificar"){echo $orden['monto_excento'];}else{ echo '0.00';} ?>"/>

              <input name="montoexcento"  type="hidden" id="montoexcento"  size="20" value="0" />
          </div></td>
          <td><div align="center"><span class="ewTableHeader"><span class="ewTableAltRow">
		<?php 
			$conexion=conexion();
			$consulta_t="select * from ordenes_tipos where descripcion<>'Compras' and descripcion<>'Servicios' order by descripcion";
			$resultado_tipo=query($consulta_t,$conexion);
		?>
		<SELECT name="tipo" id="tipo">
			<?while($fila_tipo=fetch_array($resultado_tipo)){
				$cod_tipo=$fila_tipo['cod_orden_tipo'];
				$descripcion_tipo=$fila_tipo['descripcion'];
				if($accion=="Modificar" && $orden['tipo']==$cod_tipo){
					echo "<option value=\"$cod_tipo\" selected='true'>$descripcion_tipo</option>";
				}else{
					echo "<option value=\"$cod_tipo\">$descripcion_tipo</option>";
				}
			}?>
		</SELECT>


             
		
          </span></span></div></td>
        </tr>
	
      </table>


	
<table width="100%" border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;">

	<tr class="tb-fila">
          <td width="30"><div align="center" ><strong>Forma de Pago </strong> </div></td>
         
	 <td><div align="center"><strong>Tipo Moneda</strong></div></td>
        
   
	 
          
        </tr>
	<tr >
          <td ><div align="center" > 
		
		<select name="formapago" id="formapago">
			<?php 
				$formapag=$orden['formapago'];
				if ($formapag==''){ ?>
				
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
		</div>
	</td>
       
          <td width="30"><div align="center">
		<select name="tipomoneda" id="tipomoneda">
			<? 	$tipomoneda=$orden['tipomoneda'];
				if ($tipomoned==''){ ?>
			<option  selected="selected" value="Bolivares (Bs.)">Bolivares (Bs.)</option>
			<option value="Dolares ($)">Dolares ($) </option>
			<?} else { 
				if ($tipomoned=='Bolivares (Bs.)'){?>
					<option  selected="selected" value="Bolivares (Bs.)">Bolivares (Bs.)</option>
				<? } else { ?>
					<option  value="Bolivares (Bs.)">Bolivares (Bs.)</option>
				<? } if ($tipomoned=='Dolares ($)'){ ?>
					<option value="Dolares ($)" selected="selected">Dolares ($) </option>
				<?} else { ?>
					<option value="Dolares ($)">Dolares ($) </option>
				<? }} ?>
				
		</select>
		</td>
          
	
        </tr>
	
</table>
<table width="100%" border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;">
	
	
</table>


			<br>
      <table width="100%"  border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;" >
        <tr class="tb-fila">
          <td width="50%" height="25"><strong>Concepto</strong></td>
			<td width="50%"><strong>Observaci&oacute;n</strong></td>
        </tr>
        <tr>
			<td width="50%"><textarea name="concepto"  cols=60 id="concepto"><?php
				$var_concepto2=$orden['concepto'];
			 if($var_concepto2<>''){echo $var_concepto2;}else{echo $var_concepto;} ?>
</textarea></td>
          <td width="50%"><textarea name="obser" cols="60" id="obser"><?php $var_descripcion=$orden['obser']; echo $var_descripcion; ?>
</textarea></td>
		
        </tr>
      </table>
	  
	  			<BR>

		<table width="100%"><tr class="tb-tit"><td>Agregar Materiales a la ORDEN</td><td align="right" width="30"><? btn("add","window.open('materiales_search.php','Listado de Materiales','width=720, height=550')",2)?></td><td id="grabar" align="right" width="30"><input id="guardar"  type="hidden" name="guardar" ><? btn("grabar","document.sampleform.guardar.value=1; document.sampleform.submit();",2)?></td></tr></table>
	<br>
	<? $t=0;
		if($accion=="Modificar"){
			$consulta_det="select * from ordenes_detalles INNER JOIN materiales ON ordenes_detalles.cod_pro=materiales.cod_material where cod_ord=".$cod_orden;
			
			$resultado_det=query($consulta_det,$conexion);
			$t=num_rows($resultado_det);
		}
		
	?>
		<input id="contador" name="contador" type="hidden" value="<?if($accion=="Modificar"){ echo $t; }else{ echo "0"; } ?>">
		
	<table width="100%" border="0" cellpadding="2" cellspacing="1" class="menu-bg" id="tabla_materiales" name="prueba de tabla">
	<tr class="tb-head">
		<td width="100"><strong>C&oacute;digo</strong></td>
		<td width="600"><strong>Descripci&oacute;n</strong></td>
		<td width="256"><strong>Cantidad Pedida</strong> </td>
		<td width="256"><strong>Precio Unitario</strong></td>
		<td width="256"><p align="center"><strong>I.V.A.</strong></p></td>
		<td width="256"><strong>Sub - Total</strong></td>
		<td width="256"><strong>I.V.A</strong></td>
		
	</tr>
	<?
		if($accion=="Modificar"){
			$consulta_det="select * from ordenes_detalles INNER JOIN materiales ON ordenes_detalles.cod_pro=materiales.cod_material where cod_ord=".$cod_orden;
			
			$resultado_det=query($consulta_det,$conexion);
			?>
			<tr>
			<?
			$cont=0;
			while( $fila_det=fetch_array($resultado_det))
			{
				$cont++;?><td>
				<input name="cod_material[]" disabled="true" id="cod_material[]" type="text" readonly="true" size="15" value="<? echo $fila_det['cod_pro'];?>">
				<input name="codigo<?echo $cont?>"  id="codigo<?echo $cont?>" type="hidden"  value=<?echo $fila_det['cod_pro']; ?> >
				</td>
				
				<td>	<input name="descrip[]" type="text" class="form-txt" id="descrip" size="100" maxlength="200" disabled="true" style="width:100%" readonly="true" value='<? echo $fila_det['descripcion'];?>'> </td>
				<td>
					<input name="cantidad<?echo $cont?>"  type="text" id="cantidad<?echo $cont?>" style="width:100%" size="10" onBlur="actualizar_formulario(<?echo $cont?>)" value="<? echo $fila_det[2];?>" />
				</td>
				<td>
					<input name="precio<?echo $cont?>" type="text" id="precio<?echo $cont?>" style="width:100%" size="10" value="<? echo number_format($fila_det[4],2,".","");?>" onBlur="actualizar_formulario(<?echo $cont?>) " />
				</td>

				
				<td>
					<input name="iva_sino<?echo $cont?>" disabled="true" id="iva_sino<?echo $cont?>" type="text" readonly="true" style="width:100%" size="12" value="<? echo number_format($fila_det[5],2,".","");?>" ><input name="iva_sino_mate<?echo $cont?>"  id="iva_sino_mate<?echo $cont?>" type="hidden"  value="<? echo number_format($fila_det[25],2,".","");?>" style="width:100%" size="12" value="0" >
				
				</td>
				<td>
					<input name="total<?echo $cont?>"  disabled="true" id="total<?echo $cont?>" type="text" readonly="true" style="width:100%" size="12" value="<? echo number_format($fila_det[7],2,".","");?>" >
				</td>
				
				<td><?$iva=$fila_det[5];?> 
					<input name="iva_sino_opt<?echo $cont?>" type="checkbox" id="iva_sino_opt<?echo $cont?>"  value="checkbox" <? if($iva>0){ echo 'checked="checked"';}?> onchange="actualizar_iva(<?echo $cont?>);">
				</td>
				<td>
					<input name="correl<?echo $cont?>"  id="correl<?echo $cont?>" type="hidden" readonly="true" style="width:100%" size="5" value="<? if($fila_det[11]>0){ echo $fila_det[11];}else {echo $b=0;}?>" >
				</td>
				<td width="16"><a href="javascript:;" onclick="confirmar('Seguro de Borrar?','<?php if($orden['estado']=='Revisar'){echo "adjudicar_directas.php?cod_orden="?> <?php echo $fila_det[0]; ?>&accion=<?php echo $accion; ?>&correl=<?php echo $fila_det[11]; ?>&rsac=del&cod_pro=<?php echo $fila_det[1];}?>');  return self.rValue"><img title="Borrar" src="../img_sis/ico_basket.gif" title="Eliminar" width="15" height="15" border="0" /></a></td> 
			    </tr>
				<?

			}
			
			
	}
?>
	
	</table>


</form>

<?php include ("../footer.php") ?>
<?php
//phpmkr_db_close($conn);
?>
