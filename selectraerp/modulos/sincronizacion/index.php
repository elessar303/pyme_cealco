<?php

require_once("../../config.ini.php");
require_once("../../../generalp.config.inc.php");
require_once(RAIZ_PROYECTO . "/libs/php/clases/producto.php");


$login = new Login();
$productos = new Producto();

$smarty->assign("acceso", -1);
$cod_usuario=$login->getIdUsuario(); 
$campos_usuario =$productos->ObtenerFilasBySqlSelect("SELECT a.cod_usuario, b.cod_modulo
    FROM modulo_usuario AS b, usuarios AS a
    WHERE a.cod_usuario = b.cod_usuario
    AND b.cod_modulo =  '106'
    AND a.cod_usuario ='".$cod_usuario."'");
$fila=$productos->getFilas();
if($fila==0)
    $usuarioB=0;
else
    $usuarioB=1;


$smarty->assign("usuarioB",$usuarioB);
$smarty->display("sincronizacion.tpl");

?>
