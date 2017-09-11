<?php
include("../../../general.config.inc.php");
$bd=DB_SELECTRA_FAC;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;

      $cedula = $_POST['b'];
      $cedula;
      if(!empty($cedula)) {
            evaluar($cedula, $bd, $pass, $user, $host);
      }

      function evaluar($b, $bd1, $pass1, $user1, $host1) {
            $con = mysql_connect($host1,$user1,$pass1);
            mysql_select_db($bd1, $con);
       	$sql="SELECT producto_vencimiento FROM item WHERE id_item = '".$b."'";

            $consulta = mysql_query($sql,$con);
            $regs=mysql_fetch_array($consulta);
            $regs;
		$contar = mysql_num_rows($consulta);

        echo '<input type="text" name="fecha_vencimiento" id="fecha_vencimiento" value="'.$regs[producto_vencimiento].'" readonly="readonly"/>';

      } 
?>