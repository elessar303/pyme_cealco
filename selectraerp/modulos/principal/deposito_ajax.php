<?php
include("../../../general.config.inc.php");
include("../../../generalp.config.inc.php");
session_start();
$bd_pyme=DB_SELECTRA_FAC;
$bd_pos=POS;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;
$con = mysql_connect($host,$user,$pass);

$id=$_POST['b'];
$dat = explode(",", $id);
$total=0;

if($dat[2]==1){
$sql="INSERT INTO $bd_pyme.control_deposito_temp (host, secuencia, monto, id_sessionactual, id_control_temp) SELECT host, secuencia_cierre, monto, '".$_SESSION["idSession"]."', ".$dat[3]."  FROM
$bd_pyme.control_ingresos WHERE host='".$dat[0]."' and secuencia_cierre=".$dat[1]." and forma_pago='cash' and id=".$dat[3].""; 
$insert = mysql_query($sql);
}elseif ($dat[2]==2){
$sql="DELETE FROM $bd_pyme.control_deposito_temp WHERE host='".$dat[0]."' AND secuencia=".$dat[1]." AND id_sessionactual='".$_SESSION["idSession"]."' AND id_control_temp=".$dat[3]."";
$delete = mysql_query($sql);
}



$sql="SELECT * FROM $bd_pyme.control_deposito_temp where id_sessionactual='".$_SESSION["idSession"]."' order by host, secuencia";
$consulta = mysql_query($sql);
echo "<table border=1 width='25%'>
		<thead class='tb-head'>
		<tr>
			<th colspan=3><center>Deposito</center></th>
		</tr>
		</thead>
		<tr>
			<td width='40%' align='center'><b>Caja</b></td>
			<td width='20%' align='center'><b>Secuencia de Caja</b></td>
			<td width='40%' align='center'><b>Monto</b></td>
		</tr>
		";
while($regs=mysql_fetch_array($consulta)){
	$total=$total+$regs['monto'];

	$monto_format=number_format($regs['monto'],2,'.', '');
	$total_format=number_format($total,2,'.', '');
	echo "
		<tr>
			<td width='40%' align='center'>".$regs['host']."</td>
			<td width='20%' align='center'>".$regs['secuencia']."</td>
			<td width='40%' align='right'>".$monto_format."</td>
		</tr>
		";
}
	echo"
		<tr>
		<th colspan=2><center><b>TOTAL</b><center></th>
		<td align='right'><b>".$total_format."</b></th>
		</tr>
		</table>
		<br>";
$sql="SELECT * FROM $bd_pyme.banco";
$consulta = mysql_query($sql);

		echo "
		<table cellspacing=2>
		<tr>
		<th><center><b>SELECCIONE EL BANCO</b></center></th>
		</tr>
		<td>
			<select name='banco' id='banco'>
  				<option value='000'>...</option>";
  				while($regs=mysql_fetch_array($consulta)){
  					echo "<option value=".$regs['cod_banco'].">".$regs['descripcion']."</option>";
  				}
  		echo "		
			</select>
		</td>
		<tr>
		<td></td>
		</tr>
		</table>
		<br>
		";

		echo "
		<table border=0 width='25%' cellspacing=2>
		<tr>		
        <td align='center' colspan=2><button type='button' onClick='enviar()'>Confirmar Deposito</button></td>	
        </tr>
        </table>";
?>