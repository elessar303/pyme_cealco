<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["eliminar"])){


$instruccion = "delete from distrito_escolar WHERE id = ".$_POST["id_distrito"];
$almacen->Execute2($instruccion);

	
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
	
}


if(isset($_GET["id"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from distrito_escolar where id = ".$_GET["id"]);
$smarty->assign("datos_distrito",$campos);
}
// Cargando ministerio en combo select
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
?>