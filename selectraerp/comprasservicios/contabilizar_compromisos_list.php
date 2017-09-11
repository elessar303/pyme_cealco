<?
require_once "../lib/common.php";
include("../header.php");
$url="contabilizar_cheques";
$conexion=conexion();

if(isset($_POST['enviar'])){
	//$lines = file('texto');
	

	$consulta="select * from cwconemp";
	$resultado=query($consulta,$conexion);
	$fila=fetch_array($resultado);
	$ultimo_comprobante=$fila['Numcom']+1;
	$resulatdo=query("update cwconemp set Numcom='".$ultimo_comprobante."'",$conexion);
	
	$consulta="insert into cwconhco (Numcom,Codtipo,Fecha,Descrip,Estado) values ('".$ultimo_comprobante."','7','".fecha_sql($_POST['hasta'])."','Movimientos de Cuentas por Pagar a Proveedores Periodo Desde: ".$_POST['desde']." Hasta: ".$_POST['hasta']."','1')";
	$resultado=query($consulta,$conexion);
	
	$consulta_ordenes="SELECT ORD.codigo, ORD.fecha AS fecha_orden, ORD.monto_orden AS monto_orden, ORD.cod_provee, ORD.estado,ORD.tipo, PRO.cod_proveedor, PRO.cuenta_contable AS cuenta_contable, PRO.compania AS proveedor
	FROM ordenes AS ORD
	LEFT JOIN proveedores AS PRO ON PRO.cod_proveedor = ORD.cod_provee
	WHERE ORD.estado = 'Comprometida' and fecha>='".fecha_sql($_POST['desde'])."' and fecha<='".fecha_sql($_POST['hasta'])."' and ORD.tipo<>4 order by fecha,codigo";

	$resultado_ordenes=query($consulta_ordenes,$conexion);

	$i=1;
	while($fila_ordenes=fetch_array($resultado_ordenes)){
		
		$consulta_codificacion="SELECT ORD.codigo, ORD.fecha AS fecha_orden, EJC.RecNo, EJC.RecPrePar, EJC.Monto AS monto_compromiso, EJC.RecNoOrders, EJC.Partida, EJC.Sector, EJC.Programa, EJC.Actividad, PAR.Codigo, PAR.RecNo, PAR.Cuenta as Cuenta_Contable, PAR.Sector, PAR.Programa, PAR.Actividad, CUE.Denominacion as Denominacion,CUE.CodCue FROM ordenes AS ORD
		LEFT JOIN cwpreejc AS EJC ON ORD.codigo = EJC.RecNoOrders
		LEFT JOIN cwprepar AS PAR ON EJC.partida = PAR.Codigo AND EJC.Sector = PAR.Sector
		AND EJC.Programa = PAR.Programa AND EJC.Actividad = PAR.Actividad
		LEFT JOIN cwprecue as CUE ON PAR.Codigo=CUE.CodCue
		WHERE ORD.codigo =".$fila_ordenes['codigo'];
		$resultado_codificacion=query($consulta_codificacion,$conexion);
	
		while($fila_codificacion=fetch_array($resultado_codificacion)){
		$descripcion=$fila_codificacion['Denominacion']." Orden Nro: ".$fila_ordenes['codigo'];
		$consulta_cwcondco="insert into cwcondco (Numcom,Fecha,Cuenta,Referen,Tiporef,Descrip,Debito,Credito,Numlim,FechaD) values ('".$ultimo_comprobante."','".fecha_sql($_POST['hasta'])."','".$fila_codificacion['Cuenta_Contable']."','".$fila_ordenes['codigo']."','OT','".$descripcion."','".$fila_codificacion['monto_compromiso']."','','".$i."','".$fila_ordenes['fecha_orden']."')";
		$resultado_cwcondco=query($consulta_cwcondco,$conexion);
		$i++;
		}
		$descripcion=$fila_ordenes['proveedor']." Orden Nro: ".$fila_ordenes['codigo'];
		$consulta_cwcondco="insert into cwcondco (Numcom,Fecha,Cuenta,Referen,Tiporef,Descrip,Debito,Credito,Numlim,FechaD) values ('".$ultimo_comprobante."','".fecha_sql($_POST['hasta'])."','".$fila_ordenes['cuenta_contable']."','".$fila_ordenes['codigo']."','OT','".$descripcion."','','".$fila_ordenes['monto_orden']."','".$i."','".$fila_ordenes['fecha_orden']."')";
		$resultado_cwcondco=query($consulta_cwcondco,$conexion);
		$i++;
	}
	//exit(0);
	cerrar_conexion($conexion);
	echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"Archivo Contabilizado\")
	parent.cont.location.href=\"../menu_int.php?cod=2\"
	</SCRIPT>";
}

titulo("Contabilización de Compromisos","","../menu_int.php?cod=2");
?>
<FORM name="<?echo $url;?>" method="POST" target="_self" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table width="100%" border="0">
  <tbody>
    <tr>
      <td class="tb-head" width="8%"><strong>Desde:</strong></td>
		<TD width="160"> <input name="desde" type="text" id="desde" style="width:100px" value=" <?php echo $_POST['desde']; ?>" maxlength="60">&nbsp; <input name="d_fecha" type="image" id="d_fecha" src="../lib/jscalendar/cal.gif" />
  		<script type="text/javascript">Calendar.setup({inputField:"desde",ifFormat:"%d/%m/%Y",button:"d_fecha"});</script></td>
	</TR>
	<TR>
      <td class="tb-head"><strong>Hasta: </strong></td>
		<TD><input name="hasta" type="text" id="hasta" style="width:100px" value="<?php echo $_POST['hasta']; ?>" maxlength="60" >&nbsp;
          <input name="h_fecha" type="image" id="h_fecha" src="../lib/jscalendar/cal.gif" />
  			<script type="text/javascript">Calendar.setup({inputField:"hasta",ifFormat:"%d/%m/%Y",button:"h_fecha"});</script></td>
    </tr>
<tr><TD colspan="2" class="tb-tit" align="right"><INPUT type="submit" name="enviar" value="Enviar"></TD></tr>
  </tbody>
</table>

</FORM>
</body>

</html>