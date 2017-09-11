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


$val = $_POST['val'];

if ($val=='limpiar'){
$sql="DELETE FROM $bd_pyme.control_cataporte_temp";
$delete = mysql_query($sql);
}elseif($val!='limpiar'){

		$sql = mysql_query("SELECT * FROM $bd_pyme.cataporte WHERE nro_cataporte = '".$val."'");
            
        $contar = mysql_num_rows($sql);
             
        if($contar == 0){
        		echo "<span style='font-weight:bold;color:green;'>Nro de Cataporte Disponible.</span>
        		<input hidden='hidden' type='text' name='cat_val' value=2 >";
            }else{
                echo "<span style='font-weight:bold;color:red;'>El Nro de Cataporte ya existe.</span>
                <input hidden='hidden' type='text' name='cat_val' value=1 >";
            }
}
?>