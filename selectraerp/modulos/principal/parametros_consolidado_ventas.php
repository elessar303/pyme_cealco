<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

//FILTRO PARA EL CODIGO SIGA
//$bdpp = DB_SELECTRA_PYMEPP;

$anioActual = date("Y");
for ($i=$anioActual; $i >= ($anioActual-5);$i--) {
    $arrayanioN[] = $i;
    $arrayanio[] = $i;
    
}
$smarty->assign("option_values_nombre_anio", $arrayanioN);
$smarty->assign("option_values_id_anio", $arrayanio);

$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from estados");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["nombre"];
    $arrayubi[] = $item["nombre"];
    
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);


$smarty->assign("tipo_reporte", "consolidado_ventas");
$smarty->assign("url_reporte", "../../reportes/excel/consolidado_ventas.php");
?>
