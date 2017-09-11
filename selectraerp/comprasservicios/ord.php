<?php
if (!isset($_SESSION)) {
  session_start();
}

include("../lib/common.php");

$conectar = conexion();

$con_o="select c.RecNoOrders,o.codigo as codigo,o.estado from cwpreejc as c LEFT JOIN ordenes as o ON o.codigo=c.RecNoOrders where estado='Comprometida'";
$res_o=query($con_o,$conectar);
while($fila=fetch_array($res_o))
{
	$update="update cwpreejc set Marca='X' where RecNoOrders=".$fila['codigo'];
	$rs=query($update,$conectar);
	echo $update."<br>";	
}

?>