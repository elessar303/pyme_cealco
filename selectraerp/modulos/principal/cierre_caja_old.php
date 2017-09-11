

<?php
include("../../../general.config.inc.php");
include("../../../generalp.config.inc.php");

//$ubicacion=new Almacen(); Esto no esta en este sitio o carpeta raiz...!!!

$bd=DB_SELECTRA_FAC;
$bd2=POS;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;

        $conexion = mysql_connect($host, $user, $pass, $bd2)or die("Error en la conexion");
            echo "CONEXION CON EXITO <br>";

        $sql = "SELECT HOST,DATESTART,DATEEND FROM $bd2.CLOSEDCASH ORDER BY HOSTSEQUENCE DESC";
        //$consulta = mysql_query($conexion, "SELECT * FROM closedcash")
        $consulta = mysql_query($sql,$conexion)or die("Error al traer los datos de la tabla closedcash");
        
        /*$smarty->assign("sql", $sql);    
        $query=$ubicacion->ObtenerFilasBySqlSelect($consulta);
        $i=0;
        $resultado=$ubicacion->getFilas($query);
        while($i<$resultado){
        $datos[$i]=$query[$i]; //se guardan los datos en un arreglo
        $i++;
        }*/

        $i = 0;
        while ($extraido = mysql_fetch_array($consulta)) {
            
            //$datos[$i] = array($extraido['money'], $extraido['host'], $extraido['hostsequence'],$extraido['datestart'], $extraido['dateend']);
            $datos[$i] = array($extraido);
            $i++;

           
        }
        /*Para poder mostrar los datos en el TPL debemos mandarlos por ciertas variables por el PHP y poder mostrar las consultas
        entre otras cosas*/
        $aceptar=$_POST['aceptar']; //es lo que viene por el POST del formulario
        $smarty->assign('aceptar', $aceptar);
        //$smarty->assign("query", $query);
        $smarty->assign('datos', $datos); // la variable en la que guardamos el array de la consulta

        mysql_close($conexion);
        //echo "</table>";
?>
                
