<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["eliminar"])){


$instruccion = "delete from localidad WHERE id = ".$_GET["id"];
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["id"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from localidad where id = ".$_GET["id"]);
$smarty->assign("datos_localidad",$campos);

// Cargando region en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM estados");

foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];;
    $arraySelectoutPut[] = $item["nombre"];
}
$smarty->assign("option_values_localidad", $arraySelectOption);
$smarty->assign("option_output_localidad", $arraySelectoutPut);
$smarty->assign("option_selected_estado",$campos[0]["estado"]);
$smarty->assign("option_selected_estadoA",$campos[0]["estado_atiende"]);
}

?>