<?php
include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");
$almacen = new Almacen();
$comun = new Comunes();
if(isset($_POST["aceptar"])){
$pos=POS;
$idpos=$comun->codigo_pos($_POST["descripcion_grupo"]);
$instruccion = "
INSERT INTO grupo (
`descripcion`,
`id_rubro`,
`restringido`,
`cantidad_rest`,
`dias_rest`,
grupopos
)
VALUES (
 '".$_POST["descripcion_grupo"]."','".$_POST["rubro"]."' ,'".$_POST["restringido_grupo"]."','".$_POST["cantidad_grupo"]."','".$_POST["dias_grupo"]."', '$idpos'
);
";
$almacen->Execute2($instruccion);

if($pos!="")
{
	if($_POST["restringido_grupo"]==1) 
	{
		$instruccion = "INSERT INTO $pos.categories (ID, NAME, QUANTITYMAX, TIMEFORTRY) VALUES ('$idpos','".$_POST["descripcion_grupo"]."','".$_POST["cantidad_grupo"]."','".$_POST["dias_grupo"]."')";
	}
	else
	{
		$instruccion = "INSERT INTO $pos.categories (ID, NAME) VALUES ('$idpos','".$_POST["descripcion_grupo"]."')";
	}
	$almacen->Execute2($instruccion);
}

header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

// Cargando rubro en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM departamentos");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_departamento"];
}
$smarty->assign("option_output_rubro", $arraySelectOption);
$smarty->assign("option_values_rubro", $arraySelectoutPut);


?>