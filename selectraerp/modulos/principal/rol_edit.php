<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
$sql="SELECT * FROM roles_firma, roles WHERE roles_firma.descripcion_rol=roles.id and id_rol=".$_GET['cod']."";
$campos = $almacen->ObtenerFilasBySqlSelect($sql);
$smarty->assign("campos", $campos);
if(isset($_POST["aceptar"])){


$instruccion = "
UPDATE `roles_firma` SET `cedula_persona`='".$_POST['cedula']."',`nombre_persona`='".$_POST['nombre']."',`descripcion_rol`=".$_POST['id_rol'].",`cargo`='".$_POST['cargo']."' WHERE id_rol=".$_GET['cod']."
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
