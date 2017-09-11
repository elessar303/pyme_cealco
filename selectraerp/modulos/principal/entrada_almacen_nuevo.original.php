<?php

################################################################################
# Modificado por: Charli Vivenes
# Correo-e: cvivenes@asys.com.ve - cjvrinf@gmail.com
# Objetivos:
# Agregar productos al inventario despues de autorizar una compra pendiente
# Observaciones:
# Modificaciones afectaron código de la plantilla (.tpl) correspondiente
################################################################################
include("../../libs/php/clases/almacen.php");

$almacen = new Almacen();

$pendiente = false;
if (isset($_GET["cod"]) /* && isset($_GET["cod2"]) */) {
    $sql = "SELECT kd.id_item, kd.cantidad, i.descripcion1, i.codigo_barras, kd.cantidad, kd.id_almacen_entrada FROM kardex_almacen_detalle as kd, item as i WHERE i.id_item = kd.id_item AND kd.id_transaccion = " . $_GET["cod"];
    $productos_pendientes_entrada = $almacen->ObtenerFilasBySqlSelect($sql);
    $detalles_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT autorizado_por, observacion FROM kardex_almacen WHERE id_transaccion = " . $_GET["cod"]);
    $smarty->assign("detalles_pendiente", $detalles_pendiente);
    $smarty->assign("productos_pendientes_entrada", $productos_pendientes_entrada);
    $smarty->assign("cod", $_GET["cod"]);
    #$smarty->assign("cod2", $_GET["cod2"]);
    $pendiente = !$pendiente;
}

$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());
#################################################################################
if (isset($_POST["input_cantidad_items"])) { // si el usuario hizo post
# Verificar que no se trata de una compra con estatus de entrega de productos "Pendiente"
    if (!$pendiente) {
        list($dia, $mes, $anio) = explode('-', $_POST["input_fechacompra"]);
        $kardex_almacen_instruccion = "INSERT INTO kardex_almacen (
            `id_transaccion` , `tipo_movimiento_almacen`, `autorizado_por`,
            `observacion`, `fecha`, `usuario_creacion`,
            `fecha_creacion`, `estado`, `fecha_ejecucion`)
        VALUES (
            NULL , '3', '" . $_POST["autorizado_por"] . "',
            '" . $_POST["observaciones"] . "', '" . $anio . "-" . $mes . "-" . $dia . "',
            '" . $login->getUsuario() . "', CURRENT_TIMESTAMP,
            'Entregado', '" . $anio . "-" . $mes . "-" . $dia . "');";

        $almacen->ExecuteTrans($kardex_almacen_instruccion);
        $id_transaccion = $almacen->getInsertID();

        for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) {
            $kardex_almacen_detalle_instruccion = "INSERT INTO kardex_almacen_detalle (
                    `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`)
                VALUES (
                    NULL, '" . $id_transaccion . "', '" . $_POST["_id_almacen"][$i] . "',
                    '', '" . $_POST["_id_item"][$i] . "', '" . $_POST["_cantidad"][$i] . "');";

            $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

            $campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM item_existencia_almacen WHERE
                    id_item  = '" . $_POST["_id_item"][$i] . "' AND cod_almacen = '" . $_POST["_id_almacen"][$i] . "'");

            if (count($campos) > 0) {
                $cantidadExistente = $campos[0]["cantidad"];
                $almacen->ExecuteTrans("update item_existencia_almacen set cantidad = '" . ($cantidadExistente + $_POST["_cantidad"][$i]) . "'
                    where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $_POST["_id_almacen"][$i] . "'");
            } else {
                $instruccion = "INSERT INTO item_existencia_almacen(`cod_almacen`, `id_item`, `cantidad`)
                    VALUES ('" . $_POST["_id_almacen"][$i] . "', '" . $_POST["_id_item"][$i] . "', '" . $_POST["_cantidad"][$i] . "');";
                $almacen->ExecuteTrans($instruccion);
            }
        } // Fin del For
    } else {
        #################################################################################
        # Se cambia el estado del movimiento a 'Entregado'
        $almacen->ExecuteTrans("UPDATE kardex_almacen SET estado = 'Entregado', autorizado_por = '" . $_POST["autorizado_por"] . "', observacion = 'Entrada por Compra" . /* $_POST["observaciones"] . */ "' WHERE id_transaccion = " . $_GET["cod"]);

        $cant_productos_pendientes = count($productos_pendientes_entrada);

        $producto_x = new Almacen();
        $producto_aux = new Almacen();

        for ($i = 0; $i < $cant_productos_pendientes; $i++) {
            $existente = $producto_aux->ObtenerFilasBySqlSelect("SELECT cantidad FROM item_existencia_almacen WHERE id_item = '" . $productos_pendientes_entrada[$i]["id_item"] . "';");
            $cantidad_aditar = $producto_x->ObtenerFilasBySqlSelect("SELECT id_item, cantidad, id_almacen_entrada FROM kardex_almacen_detalle WHERE id_transaccion = '" . $_GET["cod"] . "' AND id_item = '" . $productos_pendientes_entrada[$i]["id_item"] . "';");
            if (count($existente) > 0)
                $almacen->ExecuteTrans("UPDATE item_existencia_almacen SET cantidad = " . ($existente[0]['cantidad'] + $cantidad_aditar[0]['cantidad']) . " WHERE id_item = " . $productos_pendientes_entrada[$i]["id_item"] . " AND cod_almacen = " . $productos_pendientes_entrada[$i]["id_almacen_entrada"]);
            else {
                $almacen->ExecuteTrans("INSERT INTO item_existencia_almacen (`cantidad`, `id_item`, `cod_almacen`) VALUES ('" . $cantidad_aditar[0]['cantidad'] . "', '" . $cantidad_aditar[0]['id_item'] . "', '" . $cantidad_aditar[0]['id_almacen_entrada'] . "');");
            }
        }
        #################################################################################
    }
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
?>