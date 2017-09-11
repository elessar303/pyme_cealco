<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
$bdCentral= "selectrapyme_central";
$bdSelectrapyme = "selectrapyme";
$almacen = new Almacen();
mysql_set_charset('utf8');
$campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM almacen;");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["cod_almacen"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("name_form", "reporte");
$smarty->assign("option_values_almacen", $arraySelectOption);
$smarty->assign("option_output_almacen", $arraySelectoutPut);

$arraySelectOption = "";
$arraySelectoutPut = "";
$provee = new Proveedores();
mysql_set_charset('utf8');
$campos = $provee->ObtenerFilasBySqlSelect("SELECT * FROM proveedores;");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_provee", $arraySelectOption);
$smarty->assign("option_output_provee", $arraySelectoutPut);

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

$arraySelectOption = "";
$arraySelectoutPut = "";
$producto = new Producto();
mysql_set_charset('utf8');
//$campos = $producto->ObtenerFilasBySqlSelect("SELECT * FROM $bdSelectrapyme.item ORDER BY descripcion1"); //apunte a la DB para realizar pruebas
$campos = $producto->ObtenerFilasBySqlSelect("SELECT * FROM item");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id_item"];
    $arraySelectOutPut[] = $item["descripcion1"];
}
$smarty->assign("option_values_producto", $arraySelectOption);
$smarty->assign("option_output_producto", $arraySelectOutPut);
//FILTRO PARA EL CODIGO SIGA
//$bdpp = DB_SELECTRA_PYMEPP;
$bdpp=DB_REPORTE_CENTRAL;
$filtro_siga_id="";
$siga=new Almacen();
mysql_set_charset('utf8');
$cod_sig=$siga->ObtenerFilasBySqlSelect("select distinct siga from $bdpp.vproducto");
foreach ($cod_sig as $key => $item) {
    $arraycod_sigainput[] = $item["siga"];
    
}
$smarty->assign("option_values_siga", $arraycod_sigainput);
//FIN DEL FILTRO PARA EL CODIGO SIGA


// punto de ventas
$arraySelectOption = "";
$arraySelectoutPut1 = "";
$cliente = new Clientes();
mysql_set_charset('utf8');
$punto = $cliente->ObtenerFilasBySqlSelect("SELECT `nombre_punto`,codigo_siga_punto as siga  from $bdCentral.puntos_venta where estatus='A'");
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["siga"];
    $arraySelectOutPut1[] = $puntos["nombre_punto"];
}

$smarty->assign("option_values_punto", $arraySelectOption);
$smarty->assign("option_output_punto", $arraySelectOutPut1);

//estados
$arraySelectOption2 = "";
$arraySelectoutPut2 = "";
$cliente = new Clientes();
mysql_set_charset('utf8');
$campos = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM $bdCentral.estados");
foreach ($campos as $key => $item) {
    $arraySelectOption2[] = $item["codigo_estado"];
    $arraySelectOutPut2[] = $item["nombre_estado"];
}
$smarty->assign("option_values_estado", $arraySelectOption2);
$smarty->assign("option_output_estado", $arraySelectOutPut2);

//Tipos de Punto
$campos = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM $bdCentral.puntos_tipo");
foreach ($campos as $key => $item) {
    $arraySelectOption3[] = $item["id_tipo"];
    $arraySelectOutPut3[] = $item["descripcion_tipo"];
}
$smarty->assign("option_values_tipo_punto", $arraySelectOption3);
$smarty->assign("option_output_tipo_punto", $arraySelectOutPut3);

//Marca
$campos = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM $bdSelectrapyme.marca");
foreach ($campos as $key => $item) {
    $arraySelectOption4[] = $item["id"];
    $arraySelectOutPut4[] = $item["marca"];
}
$smarty->assign("option_values_marca", $arraySelectOption4);
$smarty->assign("option_output_marca", $arraySelectOutPut4);

//categoria
$arraySelectOption5 = "";
$arraySelectoutPut5 = "";
$cliente = new Clientes();
mysql_set_charset('utf8');
$campos3 = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM grupo");
foreach ($campos3 as $key => $item) {
    $arraySelectOption5[] = $item["cod_grupo"];
    $arraySelectOutPut5[] = $item["descripcion"];
}
$smarty->assign("option_values_categoria", $arraySelectOption5);
$smarty->assign("option_output_categoria", $arraySelectOutPut5);

//producto
$arraySelectOption4 = "";
$arraySelectoutPut4 = "";
$cliente = new Clientes();
mysql_set_charset('utf8');
/*$campos3 = $cliente->ObtenerFilasBySqlSelect("SELECT TRIM(descripcion1) as descripcion1,codigo_barras 
    FROM $bdCentral.productos order by descripcion1"); ORIGINAL...!!! */
$campos3 = $cliente->ObtenerFilasBySqlSelect("SELECT TRIM(descripcion1) as descripcion1,codigo_barras 
    FROM $bdSelectrapyme.item order by descripcion1");//Modifique la tabla y base de datos que llama
foreach ($campos3 as $key => $item) {
    $arraySelectOption6[] = $item["codigo_barras"];
    $arraySelectOutPut6[] = $item["descripcion1"];
}
$smarty->assign("option_values_productos", $arraySelectOption6);
$smarty->assign("option_output_productos", $arraySelectOutPut6);

/*// Cargando departamentos en combo select
$arraySelectOption7 = "";
$arraySelectoutPut7 = "";
$campos_comun = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM $bdSelectrapyme.departamentos");
foreach ($campos_comun as $key => $item) {
    $arraySelectOption7[] = $item["descripcion"];
    $arraySelectOutPut7[] = $item["cod_departamento"];
}
$smarty->assign("option_values_departamentos", $arraySelectOption7);
$smarty->assign("option_output_departamentos", $arraySelectOutPut7);*/

$fecha = new DateTime();
$fecha->modify('first day of this month');
$smarty->assign("firstday", $fecha->format('Y-m-d'));
$fecha->modify('last day of this month');
$smarty->assign("lastday", $fecha->format('Y-m-d'));
#$smarty->assign("fecha_mes_anio", $fecha->format('F Y'));
?>
