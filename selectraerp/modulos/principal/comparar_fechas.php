<?php

$fecha_actual = date("d-m-Y H:i:00",time());
$fecha_bd=date('2014-10-10');


if($fecha_bd>$fecha_actual){
    echo "esta mal";
}else{
    echo("comparo bien");
    
}

?>
