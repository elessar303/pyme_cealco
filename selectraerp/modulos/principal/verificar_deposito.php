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
$sql="DELETE FROM $bd_pyme.control_deposito_temp";
$delete = mysql_query($sql);
?>