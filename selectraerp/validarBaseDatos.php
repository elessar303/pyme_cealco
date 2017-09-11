<?php

include("../menu_sistemas/lib/common.php") ;

$baseDato=new bd("information_schema");

$query = "SELECT schema_name FROM SCHEMATA where schema_name='".$_GET['nombreBD']."'";

$resultado= $baseDato->query($query);


$aux=0;
while ($fila = $resultado->fetch_assoc())
{
$aux++;
}


if ($aux==0){
    if($_GET['resul']=='resultado2')
    {
        echo "
        var cadena = document.getElementById('resultado2');
        cadena.innerHTML = '<li style=\'color:blue; font-size:10px\'> Nombre Disponible </li>';
        document.bdcorrecta= true ;
        mostrarBotonAgregar();
        ";
    }elseif($_GET['resul']=='resultado3')
    {
        echo "
        var cadena = document.getElementById('resultado3');
        cadena.innerHTML = '<li style=\'color:blue; font-size:10px\'> Nombre Disponible </li>';
        document.bdcorrecta2= true ;
        mostrarBotonAgregar();
        ";
    }elseif($_GET['resul']=='resultado4')
    {
        echo "
        var cadena = document.getElementById('resultado4');
        cadena.innerHTML = '<li style=\'color:blue; font-size:10px\'> Nombre Disponible </li>';
        document.bdcorrecta3= true ;
        mostrarBotonAgregar();
        ";
    }

}else
{
    if($_GET['resul']=='resultado2')
    {
        echo "
        var cadena = document.getElementById('resultado2');
        cadena.innerHTML = '<li style=\'color:red; font-size:10px\'> Ya existe esta Base de Datos </li>';
        document.bdcorrecta= false ;
        mostrarBotonAgregar();
        ";

    }else if($_GET['resul']=='resultado3')
    {
        echo "
        var cadena = document.getElementById('resultado3');
        cadena.innerHTML = '<li style=\'color:red; font-size:10px\'> Ya existe esta Base de Datos </li>';
        document.bdcorrecta2= false ;
        mostrarBotonAgregar();
        ";

    }else if($_GET['resul']=='resultado4')
    {
        echo "
        var cadena = document.getElementById('resultado4');
        cadena.innerHTML = '<li style=\'color:red; font-size:10px\'> Ya existe esta Base de Datos </li>';
        document.bdcorrecta3= false ;
        mostrarBotonAgregar();
        ";
    }

}



?>
