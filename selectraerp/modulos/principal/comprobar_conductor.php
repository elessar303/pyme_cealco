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
       	$sql="SELECT * FROM conductores WHERE cedula_conductor = '".$b."'";

            $consulta = mysql_query($sql,$con);
            $regs=mysql_fetch_array($consulta);
            $regs;
		$contar = mysql_num_rows($consulta);

             
            if($contar == 0){
                  echo '<input type="text" name="conductor" maxlength="100" id="conductor" size="30" class="form-text"/>';
            }else{

                  echo '<input type="text" name="conductor" maxlength="100" id="conductor" size="30" class="form-text" value="'.$regs[nombre_conductor].'">';
                  echo '<input hidden="hidden" type="text" name="id_conductor" maxlength="100" id="id_conductor" size="30" class="form-text" value="'.$regs[id_conductor].'">';
            }
      } 
?>