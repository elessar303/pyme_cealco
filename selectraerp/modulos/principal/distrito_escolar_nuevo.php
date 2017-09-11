<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO `distrito_escolar` (

`descripcion`,
`id_ministerio`,
`estado`,
`municipio`
)
VALUES (
 '".$_POST["descripcion_distrito"]."', '".$_POST["id_ministerio"]."' , '".$_POST["id_estado"]."' , '".$_POST["municipio"]."'
);
";
$almacen->Execute2($instruccion);
	
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
	

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

?>
