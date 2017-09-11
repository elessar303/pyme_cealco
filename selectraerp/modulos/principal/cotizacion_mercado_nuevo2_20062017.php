<?php

################################################################################
# Modificado por: Humberto Zapata
# Correo-e: humbertojzapatag@gmail.com
# Objetivos:
# Agregar productos al inventario despues de autorizar una compra pendiente
# Observaciones:
# Modificaciones afectaron cÃÂŗdigo de la plantilla (.tpl) correspondiente
# 
# Modificado por: Humberto Zapata (2017-05-29)
# Objetivos:
# Actualizar. Obtener datos de la factura de compra (si fueron introducidos) 
################################################################################
include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");
include("../../../general.config.inc.php");

$pyme='selectrapyme_central';
$selectrapyme = 'selectrapyme';

$almacen = new Almacen();
$pendiente = false;
$pos=POS;
if (isset($_GET["cod"]) /* && isset($_GET["cod2"]) */) {
	
    // Prueba para tomar los productos
    $sql2 = "SELECT cm.*,i.* FROM cotizacion_mercado cm, item i";
    $productos_cotizacion_mercado = $almacen->ObtenerFilasBySqlSelect($sql2);
    $smarty->assign("productos_cotizacion_mercado",$productos_cotizacion_mercado);
    $smarty->assign("cod", $_GET["cod"]);
    $pendiente = !$pendiente;
    // fin de las pruebas

}

$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());
//cargando select de proveedores
/*$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $almacen->ObtenerFilasBySqlSelect("SELECT * FROM proveedores order by descripcion");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $nombre_proveedor=$item["descripcion"]."-".$item["rif"];
    $arraySelectoutPut[] = utf8_encode($nombre_proveedor);
}
$smarty->assign("option_values_proveedor", $arraySelectOption);
$smarty->assign("option_output_proveedor", $arraySelectoutPut);*/

//Ingreso de SELECT para la nueva seccion
//Estados
$arraySelectOption = "";
$arraySelectOutPut1 = "";
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $pyme.estados");

foreach ($ubica as $key => $item) {
    $arraySelectOption[] = $item["codigo_estado"];
    $arraySelectOutPut1[] = $item["nombre_estado"];
}
$smarty->assign("option_values_id_estado", $arraySelectOption);
$smarty->assign("option_output_nombre_estado", $arraySelectOutPut1);

//Seleccion de Tabla ITEM - SELECTRAPYME
$arraySelectOption = "";
$arraySelectOutPut1 = "";
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select i.id_item AS codigo_item, CONCAT(i.descripcion1,' ',m.marca) AS nombre_producto
    from $selectrapyme.item i
    INNER JOIN $selectrapyme.marca m ON m.id=i.id_marca
    WHERE i.estatus = 'A'
    ORDER BY i.descripcion1
    ");
/*$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $selectrapyme.item");*/
foreach ($ubica as $key => $item) {
    $arraySelectOption[] = $item["codigo_item"];
    $arraySelectOutPut1[] = $item["nombre_producto"];
}
$smarty->assign("option_values_id_item", $arraySelectOption);
$smarty->assign("option_output_nombre_producto", $arraySelectOutPut1);

//Fin de los SELECTS para la nueva seccion

// Establecimientos //
$arraySelectOption = "";
$arraySelectOutPut1 = "";
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $pyme.establecimientos");
foreach ($ubica as $key => $item) {
    $arraySelectOption[] = $item["id_establecimiento"];
    $arraySelectOutPut1[] = $item["nombre_establecimiento"];
}
$smarty->assign("option_values_id_establecimiento", $arraySelectOption);
$smarty->assign("option_output_nombre_establecimiento", $arraySelectOutPut1);

// Productos de estudio de mercado //
$arraySelectOption = "";
$arraySelectOutPut1 = "";
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $pyme.rubros_estudio_mercado");
foreach ($ubica as $key => $item) {
    $arraySelectOption[] = $item["id_rubro"];
    $arraySelectOutPut1[] = $item["nombre_rubro"];
}
$smarty->assign("option_values_id_rubro", $arraySelectOption);
$smarty->assign("option_output_nombre_rubro", $arraySelectOutPut1);

//PROVEEDORES
$arraySelectOption = "";
$arraySelectOutPut1 = "";
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $selectrapyme.proveedores");
foreach ($ubica as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $arraySelectOutPut1[] = $item["descripcion"];
}
$smarty->assign("option_values_id_proveedor", $arraySelectOption);
$smarty->assign("option_output_descripcion", $arraySelectOutPut1);

//TIPO DE TRANSPORTE
$arraySelectOption = "";
$arraySelectOutPut1 = "";
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $pyme.tipo_transporte
");
foreach ($ubica as $key => $item) {
    $arraySelectOption[] = $item["id_transporte"];
    $arraySelectOutPut1[] = $item["transporte_name"];
}
$smarty->assign("option_values_id_transporte", $arraySelectOption);
$smarty->assign("option_output_transporte_name", $arraySelectOutPut1);

///Prueba para probar la fecha 
$fecha = new DateTime();
$fecha->modify('first day of this month');
$smarty->assign("firstday", $fecha->format('Y-m-d'));
$fecha->modify('last day of this month');
$smarty->assign("lastday", $fecha->format('Y-m-d'));
#$smarty->assign("fecha_mes_anio", $fecha->format('F Y'));

###############################################################################################################

if (isset($_POST["input_cantidad_items"])) { 
// si el usuario hizo post
    
    $almacen->BeginTrans();
    
    $productos = $_POST["_producto"];
    $precios = $_POST["_precio"];
    $costoiva = $_POST["_costo_iva"];
    $precio_sug = $_POST["_precio_sugerido"];
    $margenganancia = $_POST["_margen_ganancia"];

    $preciounitario = $_POST["_precio_unitario"];
    $porcentajeoperatividad = $_POST["_porcentaje_operatividad"];
    $operatividad = $_POST["_operatividad"];
    $porcentajeganancia = $_POST["_porcentaje_ganancia"];

    //$pvp = $_POS["_pvp"];
    //echo $floatcostoiva = floatval($costoiva);
    //echo $floatmargenganancia = floatval($margenganancia); exit();

    $ingreso_productos_estudio = "INSERT INTO $pyme.cotizacion_mercado (
        `autorizado_por`, `fecha`,`fecha_cotizacion`, `validez_fecha`, `observacion`, `nro_cotizacion`, `tipo_transporte`, `proveedores`, `estatus_cotizacion`)
        VALUES('{$_POST["creado_por"]}', '{$_POST["input_fechacotiza"]}', '{$_POST["fecha_cotizacion"]}',
            '{$_POST["validez_fecha"]}', '{$_POST["observaciones"]}', '{$_POST["nro_cotizacion"]}', '{$_POST["tipo_transporte"]}', '{$_POST["proveedores"]}', '{$_POST["estatus_cotizacion"]}');"; 

    $almacen->ExecuteTrans($ingreso_productos_estudio);
    $id_estudio = $almacen->getInsertID();

    /* EL INSERT DEL DETALLE NO LO USARE, PUESTO QUE SOLO SERA EL REGISTRO Y/O DETALLE DE LA CABECERA
    DE LA COTIZACION
    
    LUEGO VERE SI LO BORRO
    HZ */

    for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) {

        $floatcostoiva = floatval($costoiva[$i]);
        $floatmargenganancia = floatval($margenganancia[$i]);
        $sumapvp = ($floatcostoiva + $floatmargenganancia);

        $ingreso_detalle_estudio = "INSERT INTO $pyme.cotizacion_mercado_detalle (
            `_precio_sugerido`,`id_estudio`, `id_producto`, `costo_sin_iva`, `_costo_iva`,`_margen_ganancia`,`_pvp`,
            `_precio_unitario`,`_porcentaje_operatividad`,`_operatividad`,`_porcentaje_ganancia`)
            VALUES('{$precio_sug[$i]}','{$id_estudio}', '{$productos[$i]}', '{$precios[$i]}', '{$costoiva[$i]}',
                '{$margenganancia[$i]}', '{$sumapvp}', '{$preciounitario[$i]}', '{$porcentajeoperatividad[$i]}',
                '{$operatividad[$i]}', '{$porcentajeganancia[$i]}');
            ";
        //echo "<br>";
        //echo $ingreso_detalle_estudio; exit();
        $almacen->ExecuteTrans($ingreso_detalle_estudio);
    }
    
    //$almacen->CommitTrans();

    //$almacen->ExecuteTrans($ingreso_productos_estudio);
    //$id_transaccion = $almacen->getInsertID();

    /*if(!$pendiente){
        $almacen->BeginTrans();
        $ingreso_productos_estudio = "INSERT INTO investigacion_mercado (
        `autorizado_por`, `fecha`, `establecimiento`, `producto`, `precio`, `observacion`, `estado`)
        VALUES('{$_POST["creado_por"]}', '{$_POST["input_fechacompra"]}', '{$_POST["establecimiento"]}', '{$_POST["rubros"]}',
         '{$_POST["precio"]}', '{$_POST["observaciones"]}', '{$_POST["estado"]}');";
    }else{
        echo "No se registraron los datos...!!!"
    }*/

	
    /*$sql = "INSERT INTO item_serial (id_producto,serial,estado) (select id_producto,serial,estado from item_serial_temp where usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."')";  
	$almacen->ExecuteTrans($sql);
    
	$almacen->ExecuteTrans("delete from item_serial_temp where usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."'");*/

    if ($almacen->errorTransaccion == 1)
    {    
        //echo "paso";   
        Msg::setMessage("<span style=\"color:#62875f;\">Entrada Generada Exitosamente </span>");
    }
    elseif($almacen->errorTransaccion == 0)
    {
        //echo "no paso";
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de cargar la entrada, por favor intente nuevamente.</span>");
    }
    $almacen->CommitTrans($almacen->errorTransaccion);

   header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
//$almacen->ExecuteTrans("truncate table item_serial_temp"); 
//forma vieja de borrar la tabla, ahora se le agrega que solo borre la que el usuario creo
/*$almacen->ExecuteTrans("delete from item_serial_temp where usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."'");*/
?>
