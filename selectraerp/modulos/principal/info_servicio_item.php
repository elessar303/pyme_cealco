<?php

include("../../config.ini.php");
include("../../libs/php/clases/producto.php");
$parametrosgenerales = new ParametrosGenerales();

$item= new Producto();
$id = @$_GET["cod"];
if(is_numeric($id)){
	$campos = $item->ObtenerFilasBySqlSelect("SELECT * FROM vw_info_item where id_item = ".$id);
	if(count($campos)>0){
		$smarty->assign("campos_item",$campos);
		$campos = $parametrosgenerales->ObtenerFilasBySqlSelect("select * from parametros_generales");
		$smarty->assign("DatosGenerales",$campos);
		$smarty->display("info_servicio_item.tpl");
	}
}
?>