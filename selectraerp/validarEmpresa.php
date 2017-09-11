<?php

include("../generalp.config.inc.php") ;

include("../menu_sistemas/lib/common.php") ;

$baseDato=new bd(SELECTRA_CONF_PYME);

$query = "SELECT nombre FROM nomempresa where nombre='".$_GET['nombreBD']."'";

$resultado= $baseDato->query($query);


$aux=0;
while ($fila = $resultado->fetch_assoc())
{
$aux++;
}


if ($aux==0){

echo " 
var cadena = document.getElementById('resultado1'); 
cadena.innerHTML = '<li style=\'color:blue; font-size:10px\'> Nombre Disponible </li>';


document.empresacorrecta= true ;

mostrarBotonAgregar();



";
}else{
echo " 
var cadena = document.getElementById('resultado1'); 
cadena.innerHTML = '<li style=\'color:red; font-size:10px; background:#ddcccc;\' > Empresa ya Existe  </li>';




document.empresacorrecta= false ;


mostrarBotonAgregar();


";
}



?>
