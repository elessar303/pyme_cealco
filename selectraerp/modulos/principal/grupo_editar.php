<?php
include("../../libs/php/clases/departamento.php");
include("../../../generalp.config.inc.php");
$departamento = new Departamento();
$pos=POS;
if(isset($_POST["aceptar"]))
{
	$instruccion = "UPDATE grupo set
	`descripcion` = '".$_POST["descripcion_grupo"]."' , `id_rubro` = '".$_POST["rubro"]."' , `restringido` = '".$_POST["restringido_grupo"]."' , `cantidad_rest` = '".$_POST["cantidad_grupo"]."' , `dias_rest` = '".$_POST["dias_grupo"]."' WHERE cod_grupo = ".$_GET["cod"];
	$departamento->Execute2($instruccion);
	
	$campos = $departamento->ObtenerFilasBySqlSelect("select * from grupo where cod_grupo = ".$_GET["cod"]);
	
	if($pos!="")
	{
		if($_POST["restringido_grupo"]==1) 
		{
			$instruccion = "UPDATE $pos.categories set NAME='".$_POST["descripcion_grupo"]."', QUANTITYMAX='".$_POST["cantidad_grupo"]."', TIMEFORTRY='".$_POST["dias_grupo"]."' where ID='".$campos[0][grupopos]."'";
		}
		else
		{
			$instruccion = "UPDATE $pos.categories set NAME='".$_POST["descripcion_grupo"]."' where ID='".$campos[0][grupopos]."'";
		}
		$departamento->Execute2($instruccion);
	}
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