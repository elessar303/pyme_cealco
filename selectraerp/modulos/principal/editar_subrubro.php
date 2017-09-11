<?php
include("../../libs/php/clases/departamento.php");
include("../../../generalp.config.inc.php");
$departamento = new Departamento();
$pos=POS;
if(isset($_POST["aceptar"]))
{
	$instruccion = "UPDATE sub_grupo set
	`descripcion` = '".$_POST["descripcion_subgrupo"]."' , `cod_grupo` = ".$_POST["rubro"]." WHERE id_sub_grupo = ".$_GET["cod"];
	$departamento->Execute2($instruccion);
	
	$campos = $departamento->ObtenerFilasBySqlSelect("select * from grupo where id_sub_grupo = ".$_GET["cod"]);

	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}


if(isset($_GET["cod"])){
$campos = $departamento->ObtenerFilasBySqlSelect("select * from sub_grupo where id_sub_grupo = ".$_GET["cod"]);
$smarty->assign("datos_grupo",$campos);
}

// Cargando rubro en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $departamento->ObtenerFilasBySqlSelect("SELECT * FROM grupo");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_grupo"];
}
$smarty->assign("option_output_rubro", $arraySelectOption);
$smarty->assign("option_values_rubro", $arraySelectoutPut);
$smarty->assign("option_selected_rubro", $campos[0]["cod_grupo"]);


?>