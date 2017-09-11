<?php
include("../../libs/php/clases/departamento.php");
$departamento = new Departamento();
if(isset($_POST["aceptar"])){
$instruccion = "
delete from  grupo where cod_grupo = ".$_GET["cod"];
$departamento->Execute2($instruccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["cod"])){
$campos = $departamento->ObtenerFilasBySqlSelect("select * from grupo where cod_grupo = ".$_GET["cod"]);
$smarty->assign("datos_grupo",$campos);
}

// Cargando rubro en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $departamento->ObtenerFilasBySqlSelect("SELECT * FROM departamentos");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_departamento"];
}
$smarty->assign("option_output_rubro", $arraySelectOption);
$smarty->assign("option_values_rubro", $arraySelectoutPut);
$smarty->assign("option_selected_rubro", $campos[0]["id_rubro"]);


?>