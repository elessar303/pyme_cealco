<?php

include("../../libs/php/clases/usuarios.php");
$usuarios = new Usuarios();

$modulos = $usuarios->ObtenerFilasBySqlSelect("SELECT cod_modulo, nom_menu FROM modulos WHERE cod_modulo_padre IS NULL AND visible = 1 AND cod_modulo != 54 ORDER BY orden");
$smarty->assign("modulos", $modulos);

$modulos_usuario = $usuarios->ObtenerFilasBySqlSelect("SELECT cod_modulo FROM modulo_usuario WHERE cod_usuario = {$_GET["cod"]}");
$smarty->assign("modulos_usuario", $modulos_usuario);

$cajero_usuario = $usuarios->ObtenerFilasBySqlSelect("SELECT vendedor, anular_pedido, ajuste_inventario FROM usuarios WHERE cod_usuario = {$_GET["cod"]}");


if (isset($_GET["cod"])) {
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT * FROM usuarios WHERE cod_usuario = " . $_GET["cod"]);
    $smarty->assign("datos_usuarios", $campos);
}

//DEPARTAMENTO
$valueSELECT = "";
$outputSELECT = "";
$tprecio = $usuarios->ObtenerFilasBySqlSelect("select * from unidades");
foreach ($tprecio as $key => $item) {
    $valueSELECT[] = $item["cod_unidad"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_centro", $valueSELECT);
$smarty->assign("option_output_centro", $outputSELECT);
$smarty->assign("option_selected_centro", $campos[0]["departamento"]);
$smarty->assign("option_check_cajero", $cajero_usuario[0]['vendedor']);
$smarty->assign("option_check_pedido", $cajero_usuario[0]['anular_pedido']);
$smarty->assign("option_check_ajuste_inventario", $cajero_usuario[0]['ajuste_inventario']);
if (isset($_POST["aceptar"])) {

if(!isset($_POST['cajero']))
$cajero=0;
else
$cajero=1;

if(!isset($_POST['pedido']))
$pedido=0;
else
$pedido=1;

if(!isset($_POST['ajuste_inventario']))
$ajuste_inventario=0;
else
$ajuste_inventario=1;

    if ($_POST["clave1"] != "" && $_POST["clave2"] != "") {
        $instruccion = "update usuarios set clave = '" . $_POST["clave1"] . "', departamento = '" . $_POST["cod_unidad"] . "',
    nombreyapellido = '" . $_POST["nombreyapellido"] . "', vendedor=".$cajero.", estatus=".$_POST["estatus"].", anular_pedido = " . $pedido . ", ajuste_inventario = " . $ajuste_inventario . " where cod_usuario = " . $_POST["cod_usuario"];
    } else {
        $instruccion = "update usuarios set  vendedor=".$cajero.", nombreyapellido = '" . $_POST["nombreyapellido"] . "', departamento = '" . $_POST["cod_unidad"] . "', estatus=".$_POST["estatus"].", anular_pedido = " . $pedido . ", ajuste_inventario = " . $ajuste_inventario . " where cod_usuario = " . $_POST["cod_usuario"];
    }
    $usuarios->Execute2($instruccion);
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Se cambia el perfil del usuario elimnando el perfil anterior (DELETE) y estableciendo el nuevo (INSERT).
    $usuarios->Execute2("DELETE FROM `modulo_usuario` WHERE cod_usuario = {$_POST["cod_usuario"]};");
    $modulo = $_POST["valor_modulo"];
    foreach ($modulo as $valor_modulo){
        $usuarios->Execute2("INSERT INTO `modulo_usuario` (`cod_usuario`, `cod_modulo`) VALUES ({$_POST["cod_usuario"]},  {$valor_modulo});");
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
?>
