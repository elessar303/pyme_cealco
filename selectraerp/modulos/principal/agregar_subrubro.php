<?php
include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");
$almacen = new Almacen();
$comun = new Comunes();
if(isset($_POST["aceptar"])){
$pos=POS;
$idpos=$comun->codigo_pos($_POST["descripcion_grupo"]);
$instruccion = "
INSERT INTO `sub_grupo`( `cod_grupo`, `descripcion`) 
VALUES (
 ".$_POST["rubro"].",'".$_POST["descripcion_subgrupo"]."');
";
$almacen->Execute2($instruccion);

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

// Cargando rubro en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM grupo");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_grupo"];
}
$smarty->assign("option_output_rubro", $arraySelectOption);
$smarty->assign("option_values_rubro", $arraySelectoutPut);
?>