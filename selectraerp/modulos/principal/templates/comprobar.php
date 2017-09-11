<?php
//include("../../../libs/php/clases/almacen.php");
//include("../../../../generalp.config.inc.php");
      $cedula = $_POST['b'];
      $almacen = new Almacen();
      echo $cedula;

      if(!empty($cedula)) {
            evaluar($cedula);
      }
       
      function evaluar($b) {
       
            $sql ="SELECT * FROM conductores WHERE cedula_conductor = '".$b."'";
            $consulta = $almacen->ObtenerFilasBySqlSelect($sql);
             
            if($consulta == 0){
                  echo "<tr>
                        <td>
                            <!--img align="middle" width="17" height="17" src="../../../includes/imagenes/8.png"-->
                            <span style="font-family:'Verdana';"><b>Conductor</b></span>
                        </td>
                        <td>
                            <input type="text" name="conductor" maxlength="100" id="conductor" size="30" maxlength="70" class="form-text"/>
                        </td>
                        </tr>";
            }else{
                  echo "<span style='font-weight:bold;color:red;'>El Conductor ya se ecuentra Registrado.</span>";
            }
      }     
?>