<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["aceptar"])){


$instruccion = "UPDATE distrito_escolar set
`descripcion` = '".$_POST["descripcion_distrito"]."', `id_ministerio` = '".$_POST["id_ministerio"]."' , `estado` = '".$_POST["id_estado"]."', `municipio` = '".$_POST["municipio"]."' WHERE id = ".$_GET["id"];
$almacen->Execute2($instruccion);

	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);

}


if(isset($_GET["id"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from distrito_escolar where id = ".$_GET["id"]);
$smarty->assign("datos_distrito",$campos);
}
// Cargando localidad en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM ministerio" );
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_ministerio", $arraySelectOption);
$smarty->assign("option_output_ministerio", $arraySelectoutPut);
$smarty->assign("option_selected_ministerio", $campos[0]["id_ministerio"]);

// Cargando estados en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM estados");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] =utf8_encode($item["nombre"]) ;
    $arraySelectoutPut[] = $item["id"];
}
$smarty->assign("option_values_region", $arraySelectOption);
$smarty->assign("option_output_region", $arraySelectoutPut);
$smarty->assign("option_selected_estado", $campos[0]["estado"]);


?>