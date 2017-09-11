<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
if(isset($_POST["aceptar"])){


$instruccion = "
INSERT INTO `roles_firma`(`cedula_persona`, `nombre_persona`, `descripcion_rol`, `cargo`) VALUES ('".$_POST['cedula']."','".$_POST['nombre']."',".$_POST['id_rol'].", '".$_POST['cargo']."')
";
$almacen->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

// Cargando region en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM roles");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] =utf8_encode($item["descripcion"]) ;
    $arraySelectoutPut[] = $item["id"];
}
$smarty->assign("option_values_region", $arraySelectOption);
$smarty->assign("option_output_region", $arraySelectoutPut);

?>
