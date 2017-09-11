<?php

require_once("../../config.ini.php");
include("../../libs/php/clases/banco.php");
$banco = new Banco();

echo 'Palabra Buscada: '.$_GET['cadena'].'<br>';
$i=0;
if ($_GET['cadena']!='')
{
$campos = $banco->ObtenerFilasBySqlSelect("select cod_item, descripcion1  from item where descripcion1 like '%".$_GET['cadena']."%' limit 10");


foreach ($campos as $fila)
{

echo '<li style="cursor:pointer; color:white; " onclick="agregarItem(\''.$fila['cod_item'].'\')">  '.$fila['descripcion1'].' </li>';
$i++;

} 
}


if ($i==0) echo "Inserte palabras clave del producto a seleccionar";

?>
