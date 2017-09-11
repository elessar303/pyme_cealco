<?php 
if (!isset($_SESSION)) {
  session_start();
}
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");
include("../config_bd.php"); // archivo que llama a la base de datos


$Conn = conexion();//new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);

$codigo=$_GET['cod_ordenes'];

if(isset ($_GET['des'])){
	$id=$_GET['id'];
	$rs = query("SELECT * FROM ordenes where codigo = $codigo ",$Conn);
	$row_rs = fetch_array($rs);
	$unidad=$row_rs['unidad'];
	$centro=$row_rs['centro_costo'];
	$concepto=$row_rs['concepto'];
	$codigorequi=$row_rs['cod_requi'];
	
	$consulta="select max(cod_requisicion) as codigo from requisiciones";
	$max=query($consulta,$Conn);
	$ne=fetch_array($max);
	$indice=$ne['codigo']+1;
	$fecha=fecha_sql(date('d/m/Y'));
	$hora=date('h:i');
	$consulta="insert into requisiciones values ($indice,'$fecha','$hora','','','Revisar',$unidad,'$centro','$concepto','$fecha','3','')";
	$requisicion=query($consulta,$Conn);
	

	//cambio estatus al despachar
	$actas="update ordenes_ne set estado='Recibido' where codigo=$id";
	$almacen=query($actas,$Conn);
	//
	$actas="select * from ordenes_ne where codigo=$id";
	$almacen=query($actas,$Conn);
	$i=1;
	while ($material=fetch_array($almacen)){
		//datos del material
		
		$cod_material=$material['cod_pro'];
		$datos="select * from materiales where cod_material='$cod_material'";
		$datosmaterial=query($datos,$Conn);
		$dama=fetch_array($datosmaterial);
		$descripcion=$dama['descripcion'];
		$medida=$dama['unidad'];
	
		$cod_material=$material['cod_pro'];
		$cantidad=$material['cantidad_des'];
		$consulta="insert into requisiciones_det values($i,$indice,'$cod_material','$descripcion',$cantidad,$unidad,'$centro','$medida')";
		$requisiciones_det=query($consulta,$Conn);
		$i+=1;
	}
	
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		     	alert('Se ha guardado una nueva Requisicion de Materiales!!');
			location.href=\"actas_almacen.php?codigo=$codigo	\"
		</SCRIPT>";

}

if ($var_acion=='')
{

	$rs = query("SELECT * FROM ordenes where codigo = $codigo ",$Conn);
	while ($row_rs = fetch_array($rs)) 
	{
		$var_cod_orden=$row_rs['codigo'];
		$var_fecha=fecha($row_rs['fecha']);
		$var_tipo=$row_rs['tipo'];
		$var_cod_req=$row_rs['cod_requi'];
		$estado=$row_rs['estado'];
		$unidad=$row_rs['unidad'];
		$centro=$row_rs['centro_costo'];
	}
	//$rs->close();

	$consulta="select * from ordenes_tipos where cod_orden_tipo=$var_tipo";
	$ti=query($consulta,$Conn);
	$tip=fetch_array($ti);
	$var_tipo=$tip['descripcion'];
}
if(isset($_POST["guardardatos"])) 
{
	$registros=$_GET['registros'];
	$codigorequi=$_GET['cod_req'];
	//consulta orden
	$consulta="select * from ordenes where codigo=$codigo";
	$or=query($consulta,$Conn);
	$orden=fetch_array($or);
	$tipo=$orden['tipo'];
	$unid=$orden['unidad'];
	$centro=$orden['centro_costo'];
	$concepto=$orden['concepto'];
	
	// maximo valor de la tabla ordenes_ne
	$consulta="select max(codigo) as codigo from ordenes_ne";
	$max=query($consulta,$Conn);
	$ne=fetch_array($max);
	$indice=$ne['codigo']+1;
				
	for($i=1;$i<=$registros;$i++){
				$cod_material=$_POST['cod_material_o'.$i];
			//if (($cantidad_rec=$_POST['cantidad_rec'.$i])!=0){
				if (($cantidad_rec=$_POST['cantidad_rec'.$i])!=0){
					$consulta="select existencia from materiales where cod_material='$cod_material'";
					$sel_mate=query($consulta,$Conn);
					$sel_material=fetch_array($sel_mate);
					$cantidad_rec+=$sel_material['existencia'];
					$consulta="update materiales set existencia=$cantidad_rec where cod_material= '$cod_material'";
					$update=query($consulta,$Conn);
				}
				$fecha=fecha_sql($_POST['fechaa']);
				$cantidad_rec=$_POST['cantidad_rec'.$i];
			
				//consulta orden
				$consulta="select * from requisiciones_det where cod_requisicion= $codigorequi and cod_material='$cod_material'";
				$ma=query($consulta,$Conn);
				$mate=fetch_array($ma);
				$cantped=$mate['cantidad'];
				
				
				$usuario=$_SESSION['nombre'];
				 $consulta="insert into ordenes_ne  values ($indice,$codigorequi,'$fecha',$tipo,'$cod_material',$cantped,$cantidad_rec,$unid,'$centro','$concepto','Revisar',$codigo,'$usuario')";
				
				$resultado=query($consulta,$Conn);
			//}
		}

		?>
		<SCRIPT language="JavaScript" type="text/javascript">
			alert("Se ha guardado una nueva Acta de Almacen!!");
			
		</SCRIPT>
		
		<?php
		echo "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
		     location.href=\"actas_almacen.php?codigo=$codigo	\"
		</SCRIPT>";
}
$rregis = query("SELECT * FROM ordenes_detalles WHERE cod_ord=$codigo",$Conn);
$registros=0;
while ($row_rss = fetch_array($rregis)) 
{
	$registros+=1;
}
?>

<?php titulo("Acta de Almacen","", "actas_almacen.php?codigo=$codigo","30") ?>

<script>
function validar(valor){
	
	var cadena="cantidad_rec"+valor
	var cadena_rec=document.getElementById(cadena)
	valor_rec = parseFloat(cadena_rec.value)

	var cadena="cantidad_pedida"+valor
	var cadena_ped=document.getElementById(cadena)
	valor_ped = parseFloat(cadena_ped.value)

	var cadena="cantidad_entregada"+valor
	var cadena_ent=document.getElementById(cadena)
	valor_ent = parseFloat(cadena_ent.value)
	
	valor = valor_rec + valor_ent

	if(valor_rec>valor_ped){
		alert("La cantidad es mayor a la Pedida!! Vuelva a Ingresar la Cantidad!");
		cadena_rec.value=0;
	}

	if(valor>valor_ped){
		alert("La suma de lo entregado y por entregar es mayor a lo comprado!! Vuelva a Ingresar la Cantidad!");
		cadena_rec.value=valor_ped-valor_ent;
	}
	
}

</script>
<form name="fproveedoresadd" id="fproveedoresadd" action="<?php echo $_SERVER['PHP_SELF']; ?>?cod_ordenes=<?php echo $codigo."&cod_req="?><?php echo $var_cod_req."&registros="?><?php echo $registros?>"  method="post" >
</p>
<table width="100%" border="0">
	<tr class="tb-head" >
	  <td  width="200" class="tb-head"><strong>Numero de la Orden: <?php  echo $codigo;?></strong>  </td>
	  <td   class="tb-head"> <strong>Tipo de Orden: <?php  echo $var_tipo;?></strong> </td>
	 
	</tr>
</table>
<BR>
<table class="" width="100%" border="0" cellspacing="0" cellpadding="0" >
  <tr>
    <td><div align="center">
      <table width="100%" border="1" style="border-bottom-color : #b45d15; border-left-color : #b45d15; border-right-color : #b45d15; border-top-color : #b45d15;">
        <tr class="tb-fila">
          <td width="10%" height="25"><div align="center"><strong>Situaci&oacute;n</strong></div></td>
          <td width="11%"><div align="center"><strong>Fecha de la Orden</strong> </div></td>
          <td width="14%"><div align="center"><strong>Fecha del Acta de Almacen</strong> </div></td>
          <td width="36%"><div align="center"><strong>Unidad Solicitante</strong> </div></td>
          <td width="29%"><div align="center"><strong>Centro de Costo</strong> </div></td>
          </tr>
        <tr>
	
          <td align="center"><?php  echo $estado;?></td>
          <td><div align="center"><span class=""><?php   echo $var_fecha; ?></span></div></td>
          <td><table>
		<TR> <td>
			<INPUT type="text" name="fechaa" id="fechaa" size="10" maxlength="12" value="<?php echo date("d/m/Y")?>"/>
		     </td>
		     <td ><input name="d_fechaa" type="image" id="d_fechaa" src="../lib/jscalendar/cal.gif">
			<script type="text/javascript">
				Calendar.setup({inputField:"fechaa",ifFormat:"%d/%m/%Y",button:"d_fechaa"});
			</script>
			</td>
		</tr>
		</table>
	 </td>
          <?php  $consulta="select * from unidades where cod_unidad=$unidad";
		 $un=query($consulta,$Conn);
		 $uni=fetch_array($un);
		 echo "<td align='center'>".$uni['descripcion']."</td>";
		 $consulta="select * from centros where cod_unidad=$unidad and cod_centro='$centro'";
		 
		 $ce=query($consulta,$Conn);
		 $cen=fetch_array($ce);
		 echo "<td align='center'>".$cen['descripcion']."</td>";
		 ?>

         
          </tr>
	
      </table>
		<BR>
  
      <table width="100%"  cellpadding="2" cellspacing="1" class="">
        <tr class="tb-head">
          <td width="10%" align="center"><strong>C&oacute;digo</strong></td>
          <td width="40%"><strong>Descripci&oacute;n</strong></td>
          <td width="20%" align="center"><strong>Cantidad Pedida</strong></td>
          <td width="15%" align="center"><strong>Cantidad Ya Recibida</strong></td>
	  <td width="15%" align="center"><strong>Cantidad Por Recibir</strong></td>
          </tr>
        <?php 
 
  $con2=1;
  cerrar_conexion($Conn);
  $conexion=conexion();
  $rs = query("SELECT * FROM ordenes_detalles WHERE cod_ord=$codigo",$conexion);
   while ($row_rs = fetch_array($rs)) 
   {
  		$var_cod_pro=$row_rs['cod_pro'];
		$consulta="select * from materiales where cod_material='".$row_rs['cod_pro']."'";
		$requ=query($consulta,$conexion);
		$reque=fetch_array($requ);

		$consulta="SELECT codigo,cantidad_des from ordenes_ne where cod_ordenes=".$codigo." AND cod_pro='".$var_cod_pro."'";
		$result=query($consulta,$conexion);
		while  ($fila=fetch_array($result))
		{
			$cantidadentregada=$cantidadentregada+$fila['cantidad_des'];
			//echo "Req: ".$codigo." Pro: ".$var_cod_pro." Cod_NE: ".$fila['codigo']." Desp: ".$fila['cantidad_des']."<br>";
			//echo $cantidadentregada."<br>";
			$faltaentregar=$row_rs['cantidad_pedida']-$cantidadentregada;	
		}		

   ?>
        <tr>
          <td><input  disabled="true"  id="cod_material<?php echo $con2; ?>" name="cod_material<?php echo $con2; ?>" type="text" style="width:100%" size="10" value="<? echo trim($row_rs['cod_pro']); ?>"/>
            <input type="hidden" name="cod_material_o<?php echo $con2; ?>" value="<? echo trim($row_rs['cod_pro']); ?>"></td>
       
          <td><input  disabled="true" name="descripcion" type="text" class="form-txt" id="descripcion" value="<? echo trim($reque['descripcion']); ?>" size="50" maxlength="200" style="width:100%" /></td>

          <td><input  disabled="true" name="cantidad_pedida<?php echo $con2; ?>" type="text" id="cantidad_pedida<?php echo $con2; ?>" style="width:100%" value="<? echo trim($row_rs['cantidad_pedida']); ?>" size="10" />
            </td>
	  <td><input  disabled="true" name="cantidad_entrega<?php echo $con2; ?>" type="text" id="cantidad_entregada<?php echo $con2; ?>" style="width:100%" value="<? echo trim($cantidadentregada); ?>" size="10" />
            </td>
          <td><input name="cantidad_rec<?php echo $con2; ?>" type="text" id="cantidad_rec<?php echo $con2; ?>" style="width:100%" value="<?php if(!isset($_POST["guardardatos"])) { echo "$faltaentregar";}?>" onBlur="validar(<?php echo $con2 ?>);" size="10" onkeypress="javascript:return numeros(event)"/>
	
            </td>
				
          </tr>
        <? $con2=$con2+1;
	$cantidadentregada=0;}
	?>
      </table>
      
    </div>
      <TABLE  width="100%" >
	     		<tr>
				
      				<td  class="tb-fila" align="right">
				<input  type="hidden" name="guardardatos"  id="guardardatos" value="Guardar" />
				<input  type="button" name="guardar"  id="guardar" value="Guardar" onclick="javascript:submit()" />&nbsp;<INPUT type="button" name="cancelar" value="Cancelar" onclick="javascript:self.history.back();"></td>
				
			</tr>
   	</table></td></tr>
</table>
</form>
<?cerrar_conexion($conexion);?>
<?php include ("../footer.php") ?>
<?php
//phpmkr_db_close($conn);
?>
