<?php
error_reporting(E_ALL);
include("../../../general.config.inc.php");
include("../../../generalp.config.inc.php");
$bd_pyme=DB_SELECTRA_FAC;
$bd_pos=POS;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;
$con = mysql_connect($host,$user,$pass);

session_start();

$sql_insert="INSERT INTO $bd_pyme.control_ingresos(fecha,host,id_cajero,monto,forma_pago, secuencia_cierre, id_usuario) 
	  SELECT fecha,host,id_cajero,SUM( monto ) AS monto,forma_pago, secuencia_cierre, ".$_SESSION['cod_usuario']."
	  FROM $bd_pyme.control_ingresos_temp WHERE id_sessionactual='".$_SESSION["idSession"]."' GROUP BY secuencia_cierre, forma_pago";
$sql1_update_control="UPDATE $bd_pos.secuencia_cierre_host SET status_cierre_pyme=0 WHERE secuencia_host in (SELECT secuencia_cierre FROM $bd_pyme.control_ingresos_temp WHERE id_sessionactual='".$_SESSION["idSession"]."')";

$sql1_delete_temp="DELETE FROM $bd_pyme.control_ingresos_temp";

mysql_query("SET AUTOCOMMIT=0;");
mysql_query("BEGIN;");

$resultado1 = mysql_query($sql_insert);
$resultado2 = mysql_query($sql1_update_control);
$resultado3 = mysql_query($sql1_delete_temp);
if ($resultado1 && $resultado2 && $resultado3){

	mysql_query("COMMIT;");
	echo "
	<script language='javascript'>
	alert('Cierre de Caja Exitoso!');
	window.close() 
	</script> 
	";
}else{
	mysql_query("ROLLBACK;");
	echo "
	<script language='javascript'>
	alert('Cierre de Caja Errado!');
	window.close() 
	</script> 
	";
}
?>
                
