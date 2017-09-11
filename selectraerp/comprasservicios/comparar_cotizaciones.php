<?php
session_start();
ob_start();

//echo $_SESSION['nombre'];
$usr = $_SESSION['nombre'];
$des_tipo=$_GET['desTipo'];
require_once '../lib/config.php';
require_once '../lib/common.php';
include("../config_bd.php"); // archivo que llama a la base de datos
include ("../header.php");

	$Conn=conexion_conf();
	$var_sql = "SELECT * FROM parametros";
	$rs = query($var_sql, $Conn);
	$row_rs = fetch_array($rs);
	$var_encabezado1 = $row_rs['encabezado1'];
	$var_encabezado2 = $row_rs['encabezado2'];
	$var_encabezado3 = $row_rs['encabezado3'];
	$var_encabezado4 = $row_rs['encabezado4'];
	$var_imagen_izq = $row_rs['imagen_izq'];
	$var_imagen_der = $row_rs['imagen_der'];
	cerrar_conexion($Conn);
	$cod_requisicion=$_GET["cod_requisicion"];
function guardar_analisis($codigo){
		$conexion=conexion();
		$observacion=$_POST['observacion'];
		$consulta="select * from analisis_cotizaciones where cod_cotizacion=$codigo";
		$coti=query($consulta,$conexion);
		$cant=num_rows($coti);
		$fecha=fecha_sql($_POST['fecha_o']);
		$usuario=$_SESSION['nombre'];
		if($cant==0){
			$consulta="select max(cod_analisis) from analisis_cotizaciones";
			$max=query($consulta,$conexion);
			$can_max=fetch_array($max);
			$canti_max=$can_max[0]+1;
			$consulta="insert into analisis_cotizaciones values($canti_max,$codigo,'$usuario','$observacion','$fecha','','')";
			$insert=query($consulta,$conexion);
		}
		else{
			$cod_analisis=$cant['cod_analisis'];
			$consulta="update analisis_cotizaciones set usuario='$usuario',observaciones='$observacion',fecha='$fecha' where cod_analisis=$cod_analisis";
			$insert=query($consulta,$conexion);
		}
}
	// Guarda el analisis
	//echo isset($_POST["guardardatos"]);
	if(isset($_POST["guardardatos"])) 
	{
		$conexion=conexion();
		$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and estatus='Seleccionada'";
		$cotiza=query($consulta,$conexion);
		$contador=num_rows($cotiza);
		for ($i=1;$i<=$contador;$i++){
			$cot=fetch_array($cotiza);
			$consulta="update cotizaciones_detalles set estatus='Creada' where cod_cotizacion=".$cot['codigo'];
			$resultado=query($consulta,$conexion);
		}
		$contmat= $_POST["guardardatos"];
		$guardar=false;
		$usuario=$_SESSION['nombre'];

		$codigo=0;
		if (isset($_POST['prove_cot1'])){
			$codigo=$_POST['prove_cot1'];
			$consulta="update  cotizaciones set estatus='Revisar' where codigo=".$codigo;
			$cotiza=query($consulta,$conexion);
			$consulta="update  cotizaciones_detalles set estatus='Revisar' where cod_cotizacion=".$codigo;
			$cotiza=query($consulta,$conexion);
			$guardar=true;
			guardar_analisis($codigo);
			
		}elseif (isset($_POST['prove_cot2'])){
			$codigo=$_POST['prove_cot2'];
			$consulta="update  cotizaciones set estatus='Revisar' where codigo=".$codigo;
			$cotiza=query($consulta,$conexion);
			$consulta="update  cotizaciones_detalles set estatus='Revisar' where cod_cotizacion=".$codigo;
			$cotiza=query($consulta,$conexion);
			$guardar=true;
			guardar_analisis($codigo);
			}elseif (isset($_POST['prove_cot3'])){
				$codigo=$_POST['prove_cot3'];
				$consulta="update  cotizaciones set estatus='Revisar' where codigo=".$codigo;
				$cotiza=query($consulta,$conexion);
				$consulta="update  cotizaciones_detalles set estatus='Revisar' where cod_cotizacion=".$codigo;
				$cotiza=query($consulta,$conexion);
				$guardar=true;
				guardar_analisis($codigo);
			} else{
				$true1=false;
				$true2=false;
				$true3=false;
				for ($i=0;$i<=$contmat;$i++){
					
	;				
					if(isset($_POST['mat_cott'.$i])){
						$codigo=$_POST['mat_cott'.$i];
						$temp=split('-',$codigo);
						$cod_cotizacion=$temp[0];
						$cod_producto=$temp[1];
						$consulta="update  cotizaciones set estatus='Revisar' where codigo=".$cod_cotizacion;
						$cotiza=query($consulta,$conexion);
						$consulta="update  cotizaciones_detalles set estatus='Revisar' where cod_cotizacion=".$cod_cotizacion." and cod_producto='".$cod_producto."'";
						$cotiza=query($consulta,$conexion);
						$guardar=true;
						if($true1==false){
							guardar_analisis($cod_cotizacion);
							$true1=true;
						}
					}elseif(isset($_POST['mat_coto'.$i])){
						$codigo=$_POST['mat_coto'.$i];
						$temp=split('-',$codigo);
						$cod_cotizacion=$temp[0];
						$cod_producto=$temp[1];
						$consulta="update  cotizaciones set estatus='Revisar' where codigo=".$cod_cotizacion;
						$cotiza=query($consulta,$conexion);
						$consulta="update  cotizaciones_detalles set estatus='Revisar' where cod_cotizacion=".$cod_cotizacion." and cod_producto='".$cod_producto."'";
						$cotiza=query($consulta,$conexion);
						$guardar=true;
						if($true2==false){
							guardar_analisis($cod_cotizacion);
							$true2=true;
						}
						}elseif(isset($_POST['mat_cotc'.$i])){
							$codigo=$_POST['mat_cotc'.$i];
							$temp=split('-',$codigo);
							$cod_cotizacion=$temp[0];
							$cod_producto=$temp[1];
							$consulta="update  cotizaciones set estatus='Revisar' where codigo=".$cod_cotizacion;
							$cotiza=query($consulta,$conexion);
							$consulta="update  cotizaciones_detalles set estatus='Revisar' where cod_cotizacion=".$cod_cotizacion." and cod_producto='".$cod_producto."'";
							$cotiza=query($consulta,$conexion);
							$guardar=true;
							if($true3==false){
								guardar_analisis($cod_cotizacion);
								$true3=true;
							}
						}
				}
			}
		
		
		
		if($guardar==true){
		     echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		     alert(\"Se guardo la selección!!\");
		     location.href=\"analisis_cotizaciones.php\" </SCRIPT>";
		}
		
	}

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
		document.comparar.submit();
	}
	function actualizar_proveedor1(){
		if(document.comparar.prove_cot1.disabled==true || document.comparar.prove_cot2.disabled==true || document.comparar.prove_cot3.disabled==true){
			document.comparar.prove_cot1.checked=false;
			alert("Ya tiene Seleccionado un Proveedor!!!");
		} else{

			document.comparar.prove_cot2.checked=false;
			document.comparar.prove_cot3.checked=false;
		}
	}
	function actualizar_proveedor2(){
		if(document.comparar.prove_cot1.disabled==true || document.comparar.prove_cot2.disabled==true || document.comparar.prove_cot3.disabled==true){
			document.comparar.prove_cot2.checked=false;
			alert("Ya tiene Seleccionado un Proveedor!!!");
		} else{

			document.comparar.prove_cot1.checked=false;
			document.comparar.prove_cot3.checked=false;
		}
	}
	function actualizar_proveedor3(){
		if(document.comparar.prove_cot1.disabled==true || document.comparar.prove_cot2.disabled==true || document.comparar.prove_cot3.disabled==true){
			document.comparar.prove_cot3.checked=false;
			alert("Ya tiene Seleccionado un Proveedor!!!");
		} else{

			document.comparar.prove_cot2.checked=false;
			document.comparar.prove_cot1.checked=false;
		}
	}
	function actualizar_cott(pos){
		var ko=document.getElementById('mat_coto'+pos)
		var kc=document.getElementById('mat_cotc'+pos)
		var kt=document.getElementById('mat_cott'+pos)
		if((kt.disabled==true )|| (kc.disabled==true) || (ko.disabled==true) ){
			alert("Ya tiene Seleccionado un Proveedor!!!");
			kt.checked=false;
		}else{
		
			ko.checked=false
			kc.checked=false
		}
	}
	function actualizar_coto(pos){
		var ko=document.getElementById('mat_coto'+pos)
		var kc=document.getElementById('mat_cotc'+pos)
		var kt=document.getElementById('mat_cott'+pos)
		
		if((kt.disabled==true )|| (kc.disabled==true) || (ko.disabled==true) ){
			alert("Ya tiene Seleccionado un Proveedor!!!");
			ko.checked=false;
		}else{
		
			kt.checked=false
			kc.checked=false
		}
	}
	function actualizar_cotc(pos){
		var ko=document.getElementById('mat_coto'+pos)
		var kc=document.getElementById('mat_cotc'+pos)
		var kt=document.getElementById('mat_cott'+pos)
		if((kt.disabled==true )|| (kc.disabled==true) || (ko.disabled==true) ){
			alert("Ya tiene Seleccionado un Proveedor!!!");
			kc.checked=false;
		}else{
		        
			ko.checked=false
			kt.checked=false
			
		}
	}

</script>
</head>
<body>
<FORM name="comparar" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']."?cod_requisicion=$cod_requisicion"; ?>">
<TABLE  width="100%" height="100" >
<TBODY>
	<tr >
      		<td colspan="7" height="30" class="tb-tit"><strong><?
		$conexion=conexion();
		$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and (estatus='Revisar' or estatus ='Seleccionada') ORDER BY estatus";
		$cotiza=query($consulta,$conexion);
		
		$pronum=num_rows($cotiza);
		if($pronum>=1){
			titulo("ANALISIS DE COTIZACIONES","","analisis_cotizaciones.php","28","../fpdf/analisispdf.php?cod_requisicion=$cod_requisicion&des_tipo=$des_tipo");
		}else{
			titulo("ANALISIS DE COTIZACIONES","","analisis_cotizaciones.php","28");
		}?></strong></td>
		
	</tr>
	<tr valign="top">
		<td>
		<TABLE  width="100%"  >
		<tr>
			<td  width="10%" ><img src="<?echo $var_imagen_izq?>" width="55" height="60"></td>
			<td colspan="2" width="70%"  align="center"><strong>ANALISIS DE COTIZACIONES</strong></td>
			<td colspan="2"  >
				<TABLE  width="100%"   >
					<?php 
					
					$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and estatus='Revisar'";
					$cotiza1=query($consulta,$conexion);
					$observacion="";
					$fecha="";
					$pronum=num_rows($cotiza1);
					if($pronum!=0){
						$rc=fetch_array($cotiza1);
						$consulta="select * from  analisis_cotizaciones where cod_cotizacion=".$rc['codigo']."";
						$cotiza2=query($consulta,$conexion);
						$rc2=fetch_array($cotiza2);
						$observacion=$rc2['observaciones'];
						$fecha=fecha($rc2['fecha_analisis']);
					}
					?>
					<tr>
					<TD width="15%"><div>Fecha:</div></td><td> <input readonly name="fecha_o" id="fecha_o" type="text"   size="12" maxlength="12" value="<?php echo $fecha; ?>" /></td>
					<td width="32"><input  name="image" type="image" id="b_fecha" src="../lib/jscalendar/cal.gif" />
					<script type="text/javascript"> 
					Calendar.setup( {inputField:"fecha_o",ifFormat:"%d/%m/%Y",button:"b_fecha",firstDay:1,weekNumbers:false,showOthers:true} );
					</script>    </TD>
					</tr>
				</table>
			</td>
		</tr>
		</table>
		</td>


    	</tr>
	<tr><TD>
		<?php 
			$conexion=conexion();
			
			$consulta="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,
		r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro,r.tipo FROM requisiciones r,centros c WHERE r.cod_requisicion=".$cod_requisicion." and r.cod_centro=c.cod_centro";
			$req=query($consulta,$conexion);
			$requisicion=fetch_array($req);
			
		?>
		<TABLE  width="100%"   >
			<tr >
			<TD height="20"></TD>
			<TD></TD>
			</tr>
			<tr class="tb-head" >
				<TD width="40%"><strong>Material Requerido por: </strong></TD>
				<TD width="70%" colspan="7" align="left"><input  disabled="true"  type="text"  size="70"  value="<?php echo $requisicion['des_centro'];?>"/></TD>
			</tr>
			<tr  >
				<TD width="40%"><strong>Requisición Nº: <?php echo $requisicion['cod_requisicion'];?> </strong></TD>
				<TD width="70%" colspan="7"></TD>
			</tr>
		</table>
	</TD></tr>
	<tr><TD>
		<TABLE  width="1000"     >
			<!-- consulta de cotizaciones  de esta requisicion -->
			<?php 
				
				$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and (estatus='Revisar' or estatus ='Seleccionada') ORDER BY estatus";
				$cotiza=query($consulta,$conexion);
				
			?>
			<tr>
				<TD class="tb-head"></TD>
				<TD class="tb-head"></TD>
				<TD class="tb-head"></TD>
				<TD class="tb-head" align="center" width="200"><strong>Provedor Nº 1 </strong><?php if(num_rows($cotiza)!=0){$cot=fetch_array($cotiza);?><input   name="prove_cot1" type="checkbox" id="prove_cot1" <?php if ($cot['estatus']=='Revisar'){ echo "checked=\"true\""; echo "disabled=\"true\"";}?> value="<?php echo $cot['codigo']; ?>" onclick="javascript:actualizar_proveedor1();"  /><?}?></TD>
				<td width="5" class="tb-head"></td>
				<TD class="tb-head" align="center" width="200"><strong>Provedor Nº 2 </strong><?php if(num_rows($cotiza)!=0){ $cot=fetch_array($cotiza);
				if ($cot!=null){ ?><input   name="prove_cot2" type="checkbox" id="prove_cot2" value="<?php echo $cot['codigo']; ?>" <?php if ($cot['estatus']=='Revisar'){ echo "checked=\"true\""; echo "disabled=\"true\"";}?> onclick="javascript:actualizar_proveedor2();" /><?}}?></TD>
				<td width="5" class="tb-head"></td>
				<TD class="tb-head" align="center" width="200"><strong>Provedor Nº 3</strong><?php if(num_rows($cotiza)!=0){ $cot=fetch_array($cotiza);
				if ($cot!=null){?><input   name="prove_cot3" type="checkbox" id="prove_cot3" value="<?php echo $cot['codigo']; ?>" <?php if ($cot['estatus']=='Revisar'){ echo "checked=\"true\""; echo "disabled=\"true\"";}?> onclick="javascript:actualizar_proveedor3();"/><?}}?></TD>
				<td width="5" class="tb-head"></td>
			</tr>
			<?php ?>
			<tr>
				<!-- consulta de cotizaciones  de esta requisicion -->
			<?php 
				
				$cotiza=query($consulta,$conexion);
				
				
			?>
				<TD class="tb-head"></TD>
				<TD class="tb-head"></TD>
				<TD class="tb-head"></TD>
				<TD class="tb-head" align="center" width="200"><?php if(num_rows($cotiza)!=0){ $cot=fetch_array($cotiza);
				$cod_provee=$cot['cod_proveedor'];
				$consulta="select * from proveedores where cod_proveedor=$cod_provee";
				$provee=query($consulta,$conexion);
				$proveedor=fetch_array($provee);
				 echo $proveedor['compania']; 
				 } ?></TD>
				<td width="5" class="tb-head"></td>
				<TD class="tb-head" align="center" width="200"><?php if(num_rows($cotiza)!=0){ $cot=fetch_array($cotiza);
				if ($cot!=null){
				$cod_provee=$cot['cod_proveedor'];
				$consulta="select * from proveedores where cod_proveedor=$cod_provee";
				$provee=query($consulta,$conexion);
				$proveedor=fetch_array($provee);
				 echo $proveedor['compania']; 
				 }} ?></TD>
				<td width="5" class="tb-head"></td>
				<TD class="tb-head" align="center" width="200"><?php if(num_rows($cotiza)!=0){ $cot=fetch_array($cotiza);
				if($cot!=null){
				$cod_provee=$cot['cod_proveedor'];
				$consulta="select * from proveedores where cod_proveedor=$cod_provee";
				$provee=query($consulta,$conexion);
				$proveedor=fetch_array($provee);
				 echo $proveedor['compania']; 
				 }} ?></TD>
				<td width="5" class="tb-head"></td>
			</tr>
			<tr>
				<TD class="tb-head" align="center" width="50"><strong>Código </strong></TD>
				<TD class="tb-head" align="center" width="40"><strong>Cantidad </strong></TD>
				<TD class="tb-head" align="center" width="200"><strong>Descripción </strong></TD>
				<TD>	
					<table   width="100%"   border="1">
						<Tr><TD  class="tb-fila"  align="center"><strong>Precio Unitario</strong></Td>
						<TD  class="tb-fila" align="center"><strong>Precio Total</strong></TD></tr>
					</table>
				</Td>
				<td width="5" class="tb-head"></td>
				<TD >
					<table   width="100%" border="1" >
						<TR><TD  class="tb-fila" align="center"><strong>Precio Unitario</strong></TD>
						<TD  class="tb-fila" align="center"><strong>Precio Total</strong></TD>
					</table>
				</TD>
				<td width="5" class="tb-head"></td>
				<TD >
					<table  width="100%" border="1">
						<TR><TD  class="tb-fila" align="center"><strong>Precio Unitario</strong></TD>
						<TD  class="tb-fila" align="center"><strong>Precio Total</strong></TD>
					</table>
				</TD>
				<td width="5" class="tb-head"></td>
			</tr>
			
			<!-- consulta de cotizaciones  de esta requisicion -->
			<?php 
				$conexion=conexion();
				$cod_requisicion=$_GET["cod_requisicion"];
				
				
			?>
			<!-- consulta de los materiales de esta requisicion-->
			<?php 
				$consulta="select * from  requisiciones_det where cod_requisicion=".$cod_requisicion." ORDER BY cod_requisicion_det ";
				$matereq=query($consulta,$conexion);
				
			?>
			<!-- comparar material cotizado con material requisicion e imprimir-->
			<?php
				$materiales=num_rows($matereq);
				$boolmaterial=false;
				for($i=0; $i<$materiales;$i++){
					$materialreq=fetch_array($matereq);
					$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and (estatus='Seleccionada' or estatus='Revisar') ORDER BY estatus";
					$cotiza=query($consulta,$conexion);
					$contproveedor=0;
					if($i%2==0){
					?>
							<tr class="tb-fila">
					<?
						}else{
							echo"<tr>";
						}
					$estatus="";
					$disable=false;
					?>
					
						<TD  align="left" width="50"><?php echo $materialreq['cod_material'];?></TD>
						<TD  align="center" width="40"><?php echo $materialreq['cantidad'];?></TD>
						<TD   align="left" width="200"><?php echo $materialreq['descripcion'];?></TD>
						<TD>	
							<?php $cot=fetch_array($cotiza);
							$cod_cot=$cot['codigo'];
							
							if($cod_cot!=null){
								$consulta="select * from cotizaciones_detalles where cod_cotizacion=$cod_cot and cod_producto='".$materialreq['cod_material']."' and consecutivo=$i";
								$cotiza_deta=query($consulta,$conexion);
								if (num_rows($cotiza_deta)!=0){
									$boolmaterial=true;
									$cotiza_detalles=fetch_array($cotiza_deta);
									$contproveedor+=1;
								?>
								<table   width="100%"  >
									<TR><TD   align="center"><?php echo $cotiza_detalles['precio']; ?></TD>
									<TD  align="center"><?php echo $cotiza_detalles['precio']*$cotiza_detalles['cantidad']; ?></TD>
								</table>
								<?php } else{ ?>
									<table   width="100%"   >
									<Td><TD   align="center"></TD>
									<TD  align="center"></TD>
									</table>
								<?php } 
								if($cotiza_detalles['estatus']=='Revisar'){		$estatus="Revisar";
								}
								$disable=false;
								if($cot['estatus']=='Revisar'){				$disable=true;
								}
							}?>
						</Td>
						
						<td width="5">
						<?php  if ($contproveedor!=0){ if($boolmaterial==true){ $boolmaterial=false;?><input  name="mat_cott<? echo $i;?>"  type="checkbox" id="mat_cott<? echo $i;?>" value="<?php echo $cotiza_detalles['cod_cotizacion']."-".$cotiza_detalles['cod_producto']; ?>" <?php if($estatus=='Revisar'){ echo "checked=\"true\""; $estatus="";}  if ($disable==true){  echo "disabled=\"true\""; $disable=false;}?> onclick="javascript:actualizar_cott('<?php echo $i;?>');"  />
						<?php }  else{  ?> <input  readonly="true"  name="mat_cott<? echo $i;?>" type="checkbox" id="mat_cott<? echo $i;?>" /> <?php } }  else{  ?> <input  readonly="true"  name="mat_cott<? echo $i;?>" type="checkbox" id="mat_cott<? echo $i;?>" /> <?php }  ?></td>
							
						<TD >
							<?php $cot=fetch_array($cotiza);
							$cod_cot=$cot['codigo'];
							if ($cod_cot==null){?>
								<table   width="100%"   >
								<TR><TD   align="center"></TD>
								<TD  align="center"></TD>
								</table>
							<?php }else{
							$consulta="select * from cotizaciones_detalles where cod_cotizacion=$cod_cot and cod_producto='".$materialreq['cod_material']."' and consecutivo=$i";
							$cotiza_deta=query($consulta,$conexion);
							if (num_rows($cotiza_deta)!=0){
								$boolmaterial=true;
								$cotiza_detalles=fetch_array($cotiza_deta);
								$contproveedor+=1;
							?>
							<table   width="100%"  >
								<TR><TD   align="center"><?php echo $cotiza_detalles['precio']; ?></TD>
								<TD  align="center"><?php echo $cotiza_detalles['precio']*$cotiza_detalles['cantidad']; ?></TD>
							</table>
							<?php } else{ ?>
								<table   width="100%"   >
								<TR><TD   align="center"></TD>
								<TD  align="center"></TD>
								</table>
							<?php }
							if($cotiza_detalles['estatus']=='Revisar'){			$estatus="Revisar";
							}
							$disable=false;
							if($cot['estatus']=='Revisar'){					$disable=true;
							}
							}?>
						</TD>
						<td width="5">
						<?php if ($contproveedor!=0){ if($boolmaterial==true){ $boolmaterial=false;?><input   name="mat_coto<? echo $i;?>" type="checkbox" id="mat_coto<? echo $i;?>" value="<?php echo $cotiza_detalles['cod_cotizacion']."-".$cotiza_detalles['cod_producto']; ?>" <?php if($estatus=='Revisar'){ echo "checked=\"true\""; $estatus="";if ($disable==true){  echo "disabled=\"true\""; $disable=false;}} ?> onclick="javascript:actualizar_coto('<?php echo $i;?>');" />
						
						<?php }  else{ ?> <input  readonly="true"  name="mat_coto<? echo $i;?>" type="checkbox" id="mat_coto<? echo $i;?>" /> <?php } } else{ ?> <input  readonly="true"  name="mat_coto<? echo $i;?>" type="checkbox" id="mat_coto<? echo $i;?>" /> <?php }?></td>
						<TD >
							<?php $cot=fetch_array($cotiza);
							$cod_cot=$cot['codigo'];
							if ($cod_cot==null){?>
								<table   width="100%"   >
								<TR><TD   align="center"></TD>
								<TD  align="center"></TD>
								</table>
							<?php }else{
								$consulta="select * from cotizaciones_detalles where cod_cotizacion=$cod_cot and cod_producto='".$materialreq['cod_material']."'  and consecutivo=$i";
							$cotiza_deta=query($consulta,$conexion);
							if (num_rows($cotiza_deta)!=0){
								$boolmaterial=true;
								$contproveedor+=1;
								$cotiza_detalles=fetch_array($cotiza_deta);
							?>
							<table   width="100%"  >
								<TR><TD   align="center"><?php echo $cotiza_detalles['precio']; ?></TD>
								<TD  align="center"><?php echo $cotiza_detalles['precio']*$cotiza_detalles['cantidad']; ?></TD>
							</table>
							<?php } else{ ?>
								<table   width="100%"   >
								<TR><TD   align="center"></TD>
								<TD  align="center"></TD>
								</table>
							<?php }
							if($cotiza_detalles['estatus']=='Revisar'){			$estatus="Revisar";
							}
							$disable=false;
							if($cot['estatus']=='Revisar'){				$disable=true;
								}
							}?>
						</TD>
						<td width="5">
						<?php ?>
						<?php if ($contproveedor!=0){ if($boolmaterial==true){ $boolmaterial=false;?><input   name="mat_cotc<? echo $i;?>" type="checkbox" id="mat_cotc<? echo $i;?>" value="<?php echo $cotiza_detalles['cod_cotizacion']."-".$cotiza_detalles['cod_producto']; ?>" <?php if($estatus=='Revisar'){ echo "checked=\"true\""; $estatus="";} if ($disable==true){  echo "disabled=\"true\""; $disable=false;}?> onclick="javascript:actualizar_cotc('<?php echo $i;?>');" />
						<?php } else{ ?> <input  readonly="true"  name="mat_cotc<? echo $i;?>" type="checkbox" id="mat_cotc<? echo $i;?>" /> <?php } } else{ ?> <input  readonly="true"  name="mat_cotc<? echo $i;?>" type="checkbox" id="mat_cotc<? echo $i;?>" /> <?php } ?></td>
						
					</tr>
					<?php
				}
		
				$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and (estatus='Seleccionada' or estatus ='Revisar')ORDER BY estatus";
				$cotiza=query($consulta,$conexion);
				$cantidad=num_rows($cotiza);
				
			?>
			<tr>
				<TD ></TD>
				<TD ></TD>
				<TD ></TD>
			<?	for($i=1; $i<=$cantidad;$i++){
			?>
				<TD >
				<table width="100%" >
					<tr><TD align="right"><strong>Sub- Total: </strong></td>
					<TD align="right"><?php if (num_rows($cotiza)!=0){ $cot=fetch_array($cotiza);
					$consulta="select * from  cotizaciones_detalles where cod_cotizacion=".$cot['codigo']." ";
					$cotizaa=query($consulta,$conexion);
					$sub=0;
					$iva=0;
					while ($resulcotiza=fetch_array($cotizaa)){
						$sub+=$resulcotiza['precio']*$resulcotiza['cantidad'];
						$iva+=$resulcotiza['iva'];
					}
					echo $sub;} ?> </TD>
					</tr>
					<tr>
					<TD align="right"><strong> IVA: </strong></TD>
					<td align="right"><?php echo $iva;?></td>
					</tr>
				</table>
				</TD>
				<TD ></TD>
			<? }?>
			</tr>

		
			<?php 
				$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and (estatus='Seleccionada' or estatus ='Revisar') ORDER BY estatus";
				$cotiza=query($consulta,$conexion);
				$cantidad=num_rows($cotiza);
			?>
			<tr>
				<TD ></TD>
				<TD ></TD>
				<TD ></TD>
			<?	for($i=1; $i<=$cantidad;$i++){
			?>
				<TD align="right"> <strong>Total: <?php if (num_rows($cotiza)!=0){ $cot=fetch_array($cotiza);  echo $cot['total'];} ?> </strong></TD>
				<TD ></TD>
			<? }?>
			</tr>
				
			
			<tr>
				<TD ></TD>
				<TD ></TD>
				<TD ></TD>
			<?	for($i=1; $i<=$cantidad;$i++){
			?>
				<TD   align="center" width="200">
					<table  width="100%" border="1">
						<TR><TD  class="tb-fila" align="center"><strong>Tiemp.Entrega</strong></TD>
						<TD  class="tb-fila" align="center"><strong>Cond. Pago</strong></TD>
					</table>
				</TD>
				<TD ></TD>
			<? }?>
			</tr>
			
			<tr>
				<TD ></TD>
				<TD ></TD>
				<TD ></TD>
			<?	$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and (estatus='Seleccionada' or estatus ='Revisar') ORDER BY estatus";
				$cotiza=query($consulta,$conexion);
				$cantidad=num_rows($cotiza);
				for($i=1; $i<=$cantidad;$i++){
			?>
				<TD   align="center" width="200">
					<table  width="100%" >
						<?php 
						if (num_rows($cotiza)!=0){
							$cot=fetch_array($cotiza);
							if($cot!=null){ ?>
								<TR><TD  class="tb-fila" align="center"><?php  echo $cot['tiempo_entrega']; ?></TD>
								<TD  class="tb-fila" align="center"><?php echo $cot['tiempo_pago']; ?></TD></TR>
							<?php } else{ ?>
								<TR><TD  class="tb-fila" align="center">0</TD>
								<TD  class="tb-fila" align="center"></TD></TR>
							<?php } } else{ ?>
								<Td><TD  class="tb-fila" align="center">0</TD>
								<TD  class="tb-fila" align="center"></TD></tr>
							<?php }?>
								
					</table>
				</Td>
				<TD ></TD>
			<? }?>
			</tr>

			
			
		</table>
	</TD></tr>
	<tr><TD></TD></tr>
	<tr ><TD class="tb-head" >
	<div><strong> Observaciones: </strong></div>
	</TD></tr>
	<tr ><TD class="tb-head" height="50" align="center">
	
	<textarea name="observacion"  cols=120 id="observacion"><?php echo $observacion; ?></textarea>
	</TD></tr>
	<tr><TD>
		<TABLE  width="100%" height="100">
	     		<tr>
				
      				<td class="" align="right"> 
				<?php $consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and estatus='Revisar'";
				$cotiza=query($consulta,$conexion);
				$pronum=num_rows($cotiza);
				if($pronum==0){?>
					<input  type="hidden" name="guardardatos"  id="guardardatos" value="<?php echo $materiales ?>" />
					<input  type="button" name="guardar"  id="guardar" value="Guardar" onclick="javascript:validar()" />
				<?php } ?>
				&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onclick="javascript:self.history.back();"></td>
				
			</tr>
		</table>
	</TD></tr>
</TBODY>
</TABLE>
</FORM>
</body>
