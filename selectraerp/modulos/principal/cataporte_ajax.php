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

if($dat[1]==1){
$sql="INSERT INTO $bd_pyme.control_cataporte_temp (nro_deposito, id_sessionactual) SELECT nro_deposito, '".$_SESSION["idSession"]."'  FROM
$bd_pyme.deposito WHERE nro_deposito='".$dat[0]."'"; 
$insert = mysql_query($sql);
}elseif ($dat[1]==2){
$sql="DELETE FROM $bd_pyme.control_cataporte_temp WHERE nro_deposito='".$dat[0]."'";
$delete = mysql_query($sql);
}


$sql="SELECT * FROM $bd_pyme.control_cataporte_temp, $bd_pyme.deposito where control_cataporte_temp.id_sessionactual='".$_SESSION["idSession"]."' AND deposito.nro_deposito=control_cataporte_temp.nro_deposito";
$consulta = mysql_query($sql);
echo "<table border=1 width='25%'>
		<thead class='tb-head'>
		<tr>
			<th colspan=2><center>Cataporte</center></th>
		</tr>
		</thead>
		<tr>
			<td width='40%' align='center'><b>Nro Deposito</b></td>
			<td width='40%' align='center'><b>Monto</b></td>
		</tr>
		";
while($regs=mysql_fetch_array($consulta)){
	$total=$total+$regs['monto'];

	$monto_format=number_format($regs['monto'],2,'.', '');
	$total_format=number_format($total,2,'.', '');
	echo "
		<tr>
			<td width='40%' align='center'>".$regs['nro_deposito']."</td>
			<td width='40%' align='right'>".$monto_format."</td>
		</tr>
		";
}
	echo"
		<tr>
		<th><center><b>TOTAL</b><center></th>
		<td align='right'><b>".$total_format."</b></th>
		</tr>
		</table>
		<br>
		<table cellspacing=2>
		<tr>
		<td align='right'><b>Nro Cataporte:</b></td><td><input type='text' name='nro_cataporte' id='nro_cataporte' size=20 onkeyup='aMays(event, this)' onBlur='comprobar(this.id)'/></td>
		<div id='resultado2' width='50%' align='center'></div>
		</tr>
		<tr>
		<td colspan=2><p>&nbsp;</p></td>
		</tr>
		<br>
		<tr>
		<td align='right'><b>Bolsas Cataporte:</b></td><td> <input type='text' name='cantidad' id='cantidad' value='' onkeyup='crearCampos(this.value);' size=3/></center></td>
		</tr>
		</table>
		<br><div id='campos_dinamicos'></div>
		<br>
		<table border=0 width='25%' cellspacing=2>
		<tr>		
		<td align='center' colspan=2><button type='button' onClick='enviar()' id='boton_env'>Confirmar Cataporte</button></td>	
		</tr>
		</table>";
?>