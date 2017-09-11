<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["aceptar"])){

$campos2 = $almacen->ObtenerFilasBySqlSelect("select * from estado_region where id = ".$_POST["id"]);

$instruccion = "UPDATE estado_region set
`id_estado` = '".$_POST["id_estado"]."' WHERE id = ".$_POST["id"];
$almacen->Execute2($instruccion);


if ($campos2[0]["id_estado"] != $_POST["id_estado"]) {
	// si el estado guardado es diferente al del formulario se cambian los estatus de los estados correspondiente para q no salgan en region
	$instruccion2 = "UPDATE estados set
   `estatus` = 1 WHERE id = ".$_POST["id_estado"];
    $almacen->Execute2($instruccion2);
    $instruccion3 = "UPDATE estados set
   `estatus` = 0 WHERE id = ".$campos2[0]["id_estado"];
    $almacen->Execute2($instruccion3);
}

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=listEst&id=".$_GET["id"]);
}


if(isset($_GET["id"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select * from estado_region where id = ".$_GET["cod"]);

$smarty->assign("datos_region",$campos);
$campos1 = $almacen->ObtenerFilasBySqlSelect("select a.id_estado, b.nombre from estado_region a, estados b where a.id_estado=b.id and a.id=".$_GET["cod"]);
}
// Cargando estado en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
    $arraySelectOption[] = $campos1[0]["nombre"];
    $arraySelectoutPut[] = $campos1[0]["id_estado"];
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM estados where estatus=0 ORDER BY nombre ASC");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = utf8_encode($item["nombre"]);
    $arraySelectoutPut[] = $item["id"];
}
$smarty->assign("option_values_estados", $arraySelectOption);
$smarty->assign("option_output_estados", $arraySelectoutPut);
$smarty->assign("option_selected_estados",$campos[0]["id_estado"]);


?>