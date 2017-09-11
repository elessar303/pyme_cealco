<?php

include("../../libs/php/clases/almacen.php");

$almacen = new Almacen();

$name_form = "departamento_nuevo";

if (isset($_POST["aceptar"])) {
    $instruccion = "INSERT INTO departamentos (`cod_departamento`,`descripcion`)
                VALUES (NULL , '{$_POST["descripcion_departamento"]}');";
    $almacen->Execute2($instruccion);
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}

$smarty->assign("name_form", $name_form);
?>