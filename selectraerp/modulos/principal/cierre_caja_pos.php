<?php
include("../../../general.config.inc.php");
include("../../../generalp.config.inc.php");
session_start();
$bd_pyme=DB_SELECTRA_FAC;
$bd_pos=POS;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;
$fecha=date('Y-m-d');
$host_caja=$_POST['b'];
$con = mysql_connect($host,$user,$pass);
$band=0;
$i=1;
$total_cash=0;
$total_paperin=0;
$total_card=0;
//Vacio la tabla temporal de control de ingresos de la sesion desde la que se realiza el proceso
$sql_crt="DELETE FROM $bd_pyme.control_ingresos_temp WHERE id_sessionactual='".$_SESSION["idSession"]."'";
$trunc_crt = mysql_query($sql_crt);

/*/Actualizo la tabla de control de secuencia, esto no debe ir aca sino un boton en caso de q se necesite de manera especifica ya que esto es automatico//
$sql="TRUNCATE TABLE $bd_pyme.secuencia_cierre_host";
$trunc_sec = mysql_query($sql);

$sql="INSERT INTO $bd_pyme.secuencia_cierre_host(nombre_host,secuencia_host) SELECT host, max(hostsequence)
FROM $bd_pos.closedcash WHERE DATEEND IS NOT NULL GROUP BY HOST ORDER BY MAX( HOSTSEQUENCE)";
$insert_sec = mysql_query($sql);
//Fin de actulizacion de las secuencias de cierre/*/

//Ubico la secuencia en la tabla de control
$sql="SELECT * FROM $bd_pos.secuencia_cierre_host WHERE nombre_host='".$host_caja."' AND status_cierre_pyme=1 AND status_cierre_pos=0 order by secuencia_host";
//echo $sql; exit();
$contador=0;
$consulta = mysql_query($sql);
while($regs_secuencia=mysql_fetch_array($consulta)){

		$sec_cierre=$regs_secuencia['secuencia_host'];
		echo "
		<table border=1 width='50%'>
		";
		while($band==0){
			//Consulto la ultima secuencia de la tabla de control cuando exista cierre de caja
			$sql="SELECT * FROM $bd_pos.closedcash WHERE host = '".$host_caja."' AND HOSTSEQUENCE=".$sec_cierre." AND DATEEND is not null";
			$sql_verificar="SELECT * FROM $bd_pos.secuencia_cierre_host WHERE nombre_host='".$host_caja."' AND secuencia_host=".$sec_cierre." AND status_cierre_pyme=1 AND status_cierre_pos=0 order by secuencia_host";
			//echo $sql_verificar; 
			$consulta_verificar = mysql_query($sql_verificar);
			$cont_verificar=mysql_num_rows($consulta_verificar);

			$consulta = mysql_query($sql);
			$cont=mysql_num_rows($consulta);
			//mientras exista cierre de caja entro en el if
			if($cont_verificar>0){
				echo "
				<thead class='tb-head'>
				<tr>
				<th colspan=6>Secuencia Nro: ".$sec_cierre."</th>
				</tr>
				</thead>
				<!-- <tr>
				<th>ID</th><th>Fecha</th><th>Caja</th><th>Cajero</th><th>Monto</th><th>Forma de Pago</th>
				</tr> -->
				";
				$band=0; //Esta bandera en 0 mientras exista cierre de caja
				$regs=mysql_fetch_array($consulta);
				
				//Busco los receipts del closedcash 
				$sql="SELECT * from $bd_pos.receipts WHERE money='".$regs['MONEY']."'";
				$result=mysql_query($sql);
					//Por cada receipts busco los tikets y su detalle para comenzar con el procedimiento de cierre
					while($regs_recibos=mysql_fetch_array($result)) {
					//Ubico los Payments del recibo
					$sql="SELECT c.NAME as empleado, a.TOTAL as TOTAL, a.PAYMENT as PAYMENT, b.PERSON as id_empleado
								from $bd_pos.payments as a, $bd_pos.tickets as b, $bd_pos.people as c, $bd_pos.customers as d
								WHERE a.receipt='".$regs_recibos['ID']."'
								AND a.receipt=b.ID
								AND b.PERSON=c.ID
								AND d.ID=b.customer";
					
					$result2=mysql_query($sql);
						//por cada payment ubico el detalle de la compra

						while($regs_cierre=mysql_fetch_array($result2)){
							$monto_format = number_format($regs_cierre['TOTAL'],2,'.', '');
							/* echo "<tr>
							<td>".$i."</td><td>".$regs['DATEEND']."</td><td>".$regs['HOST']."</td><td>".$regs_cierre['empleado']."</td><td>".$monto_format."</td><td>".$regs_cierre['PAYMENT']."</td>
							</tr>";*/
							$sql_ctrl_temp="INSERT INTO $bd_pyme.control_ingresos_temp(fecha,host,id_cajero,monto,forma_pago, secuencia_cierre, id_sessionactual) 
							VALUES ('".$regs['DATEEND']."', '".$regs['HOST']."', '".$regs_cierre['id_empleado']."','".$monto_format."','".$regs_cierre['PAYMENT']."',".$sec_cierre.", '".$_SESSION["idSession"]."')";
							$insert_contr_temp = mysql_query($sql_ctrl_temp);

							//Acumulo los totales de cada forma de pago 
							if($regs_cierre['PAYMENT']=='cash'){
								$total_cash=$total_cash+$monto_format;
							}elseif($regs_cierre['PAYMENT']=='paperin') {
								$total_paperin=$total_paperin+$monto_format;
							}elseif ($regs_cierre['PAYMENT']=='magcard') {
								$total_card=$total_card+$monto_format;
							}
							$i++;
						} //Fin while $regs_cierre
					}//Fin while $regs_recibos
					$sec_cierre=$sec_cierre+1; //Paso a la siguiente secuencia para la siguiente iteracion del while
				}else{
				$band=1; //Cambio la bandera a 1 para terminar el proceso
				$total=$total_cash+$total_paperin+$total_card;

				$total_cash_format=number_format($total_cash,2,'.', '');
				$total_paperin_format=number_format($total_paperin,2,'.', '');
				$total_card_format=number_format($total_card,2,'.', '');
				$total_format=number_format($total,2,'.', '');
				echo "
				</table>";
				//Imprimo los totales de cada forma de pago
				echo "
				<br>
				<table border=1 width='25%'>
				<thead class='tb-head' align='center'>
				<tr>
				<td align='center' colspan=2><b>Total Cierre<b></td>
				</tr>
				</thead>
				<tr>
				<td align='center'><b>Tipo de Pago</b></td><td align='center'><b>Total</b></td>
				</tr>
				<tr>
				<td align='right'>Efectivo</td><td align='right'>".$total_cash_format."</td>
				</tr>
				<tr>
				<td align='right'>Cesta Tickets</td><td align='right'>".$total_paperin_format."</td>
				</tr>
				<tr>
				<td align='right'>Tarjeta</td><td align='right'>".$total_card_format."</td>
				</tr>
				<tr>
				<td align='right'>TOTAL</td><td align='right'>".$total_format."</td>
				</tr>
				</table>
				<br>
				<table align='center'>
        		<tr>
        		
                <td align='center'><button type='button' onClick='enviar()'>Confirmar</button></td>
        		
        		</tr>
        		</table>
				";
				echo $sec_cierre;
			}
			//echo $contador=$contador+1;
		}//Fin While $band==0

}//Fin While $regs_secuencia
?>

<?php
if (!empty($_POST["boton"])) {

	echo "cierro caja, copio tabla temp a real y vacio temp, guardo secuencia y le coloco estatus de cerrado en la tabla de secuencia cierre host";
}
mysql_close();
?>