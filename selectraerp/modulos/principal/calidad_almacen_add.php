<?php

################################################################################
# Modificado por: Charli Vivenes
# Correo-e: cvivenes@asys.com.ve - cjvrinf@gmail.com
# Objetivos:
# Agregar productos al inventario despues de autorizar una compra pendiente
# Observaciones:
# Modificaciones afectaron cÃÂŗdigo de la plantilla (.tpl) correspondiente
# 
# Modificado por: Charli Vivenes (2013-04-21)
# Objetivos:
# Actualizar. Obtener datos de la factura de compra (si fueron introducidos) 
################################################################################
include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");


$almacen = new Almacen();
$pendiente = false;
$pos=POS;
if (isset($_GET["cod"]) /* && isset($_GET["cod2"]) */) {
	
    $sql = "SELECT kd.id_item, kd.cantidad, kd.id_almacen_entrada, kd.id_ubi_entrada, i.descripcion1, i.codigo_barras FROM calidad_almacen_detalle AS kd, item AS i WHERE i.id_item = kd.id_item AND kd.id_transaccion = {$_GET["cod"]};";
    $productos_pendientes_entrada = $almacen->ObtenerFilasBySqlSelect($sql);
    $detalles_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT autorizado_por, observacion, id_documento FROM calidad_almacen WHERE id_transaccion = {$_GET["cod"]};");
    $detalles_compra_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT num_factura_compra, num_cont_factura FROM compra WHERE id_compra = {$detalles_pendiente[0]["id_documento"]};");
    $smarty->assign("detalles_pendiente", $detalles_pendiente);
    $smarty->assign("productos_pendientes_entrada", $productos_pendientes_entrada);
    $smarty->assign("datos_factura", $detalles_compra_pendiente);
    $smarty->assign("cod", $_GET["cod"]);
    #$smarty->assign("cod2", $_GET["cod2"]);
    $pendiente = !$pendiente;
}

$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());
//cargando select de proveedores
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $almacen->ObtenerFilasBySqlSelect("SELECT * FROM clientes order by nombre");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id_cliente"];
    $nombre_proveedor=$item["nombre"]."-".$item["rif"];
    $arraySelectoutPut[] = utf8_encode($nombre_proveedor);
}
$smarty->assign("option_values_proveedor", $arraySelectOption);
$smarty->assign("option_output_proveedor", $arraySelectoutPut);

//Ingreso de SELECT para la nueva seccion
//Estados
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from estados");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = utf8_encode($item["nombre"]);
    $arrayubi[] = $item["id"];
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

//marcas
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from marca");

foreach ($ubica as $key => $item) {
    $arraymarca[] = utf8_encode($item["marca"]);
    $arrayid[] = $item["id"];
}
$smarty->assign("option_values_nombre_marca", $arraymarca);
$smarty->assign("option_values_id_marca", $arrayid);
//fin de marcas;
// punto de ventas
$arraySelectOption = "";
$arraySelectoutPut1 = "";
$cliente = new Almacen();
mysql_set_charset('utf8');
$punto = $cliente->ObtenerFilasBySqlSelect("SELECT `nombre_punto`,codigo_siga_punto as siga  from puntos_venta where estatus='A'");
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["siga"];
    $arraySelectOutPut1[] = $puntos["nombre_punto"];
}

$smarty->assign("option_values_punto", $arraySelectOption);
$smarty->assign("option_output_punto", $arraySelectOutPut1);

// Tickets pendientes por entradas
$arraySelectOption2 = "";
$arraySelectoutPut2 = "";
$tickets = new Almacen();
mysql_set_charset('utf8');
$punto = $tickets->ObtenerFilasBySqlSelect("SELECT *, a.id as id_ticket from tickets_entrada_salida a, transporte_conductores b where a.id_conductor=b.id and a.hora_salida='0000-00-00 00:00:00' and a.tipo_ticket = 1 and a.id not in (select distinct(id_ticket_entrada) from calidad_almacen)");
foreach ($punto as $key => $puntos) {
    $arraySelectOption2[] = $puntos["id_ticket"];
    $arraySelectoutPut2[] = "Ticket N°: ".$puntos["id_ticket"]." Conductor: ".$puntos["nombres"]." ".$puntos["apellidos"];
}

$smarty->assign("option_values_ticket", $arraySelectOption2);
$smarty->assign("option_output_ticket", $arraySelectoutPut2);

//Fin de los SELECTS para la nueva seccion

#################################################################################
if (isset($_POST["input_cantidad_items"])) { 

    for ($j = 0; $j < (int) $_POST["input_cantidad_items"]; $j++){

    $cadena_item[$j]=$_POST["_id_item"][$j];
    $cadena_cantidad[$j]=$_POST["_cantidad"][$j];
        if($cadena_cantidad[$j]<0){
            echo "Entrada con Cantidad en Negativo, Verificar Datos";
            exit();
        }

    }

    
// si el usuario hizo post
    if (!$pendiente) 
    {
    # Verificar que no se trata de una compra con estatus de entrega de productos "Pendiente"
        #list($dia, $mes, $anio) = explode('-', $_POST["input_fechacompra"]);
        $codigo_siga=$almacen->ObtenerFilasBySqlSelect("select codigo_siga from parametros_generales");
        //Validamos si el conductor existe en la BD, de no existir lo insertamos
        $id_conductor = $almacen->ObtenerFilasBySqlSelect("SELECT id_conductor FROM conductores WHERE
                    cedula_conductor  = '{$_POST["nacionalidad_conductor"]}{$_POST["cedula_conductor"]}';");
        $conductor = $almacen->getFilas($id_conductor);

        if ($conductor == 0) {
            $instruccion = "INSERT INTO conductores ( `nombre_conductor`,`cedula_conductor`)
                    VALUES ('{$_POST["conductor"]}','{$_POST["nacionalidad_conductor"]}{$_POST["cedula_conductor"]}');";
            $almacen->ExecuteTrans($instruccion); 
            //Luego de insertar el nuevo conductor en la BD capturo su ID par la tabla de Kardex Almacen
            $id_conductor=$almacen->ObtenerFilasBySqlSelect("SELECT id_conductor FROM conductores WHERE
                    cedula_conductor  = '{$_POST["nacionalidad_conductor"]}{$_POST["cedula_conductor"]}';");
        }

        $almacen->BeginTrans();
        $kardex_almacen_instruccion = "INSERT INTO calidad_almacen (
            `id_transaccion` , `tipo_movimiento_almacen`, `autorizado_por`,
            `observacion`, `fecha`, `usuario_creacion`,
            `fecha_creacion`, `estado`, `fecha_ejecucion`, `id_documento`, `empresa_transporte`, `id_conductor`, `placa`, `guia_sunagro`, `orden_despacho`, `almacen_procedencia`, `id_proveedor`,`id_ticket_entrada` )
        VALUES (
            NULL , '3', '{$_POST["autorizado_por"]}',
            '{$_POST["observaciones"]}', '{$_POST["input_fechacompra"]}', '{$login->getUsuario()}', 
            CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP, '{$_POST["nro_documento"]}', '{$_POST["empresa_transporte"]}', '{$id_conductor[0]["id_conductor"]}', '{$_POST["placa"]}', '{$_POST["codigo_sica"]}', '{$_POST["orden_despacho"]}', '{$_POST["puntodeventa"]}', '{$_POST["id_proveedor"]}','{$_POST["id_ticket"]}');";
        
        $almacen->ExecuteTrans($kardex_almacen_instruccion);
        
        
        $id_transaccion = $almacen->getInsertID();
        $cod_calidad="update calidad_almacen set cod_acta_calidad='C-".$codigo_siga[0]['codigo_siga']."-{$id_transaccion}' where id_transaccion={$id_transaccion}";
        $almacen->ExecuteTrans($cod_calidad);
        $id_proveedor=$_POST["id_proveedor"];
        $observacion1=$_POST["observacion1"];
        $tipo='E';
        $status='R';
        if ($id_proveedor==1){
            $tipo='I';
        }
        if ($observacion1!=''){
            $status='RD';
        }

       
        for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) 
        {
        
            $kardex_almacen_detalle_instruccion = "INSERT INTO calidad_almacen_detalle (
                     `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`,`id_ubi_entrada`, `vencimiento`,`lote`, `observacion`, `estatus`, `tipo_uso`, `costo_declarado`, `id_marca` )
                VALUES (
                    '{$id_transaccion}', '{$_POST["_id_almacen"][$i]}',
                    '', '{$_POST["_id_item"][$i]}', '{$_POST["_cantidad"][$i]}','{$_POST["_ubicacion"][$i]}','{$_POST["_vencimineto"][$i]}','{$_POST["_lote"][$i]}','{$_POST["_observacion1"][$i]}','{$_POST["_estatus_producto"][$i]}', '{$_POST["_tipo_uso"][$i]}', '{$_POST["_costo_declarado"][$i]}', '{$_POST["_marca"][$i]}');";

            $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

            //verificamos si es necesario cambiar el costo declarado
            $sql="select costo_declarado from item where id_item='{$_POST["_id_item"][$i]}'";
            $resultado=$almacen->ObtenerFilasBySqlSelect($sql);
            if($resultado[0]['costo_declarado']!=$_POST["_costo_declarado"][$i])
            {
                $sql="update item set costo_declarado={$_POST["_costo_declarado"][$i]} where id_item='{$_POST["_id_item"][$i]}'";
                $almacen->ExecuteTrans($sql);
            }
        }				
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
}
//$almacen->ExecuteTrans("truncate table item_serial_temp"); 
//forma vieja de borrar la tabla, ahora se le agrega que solo borre la que el usuario creo
$almacen->ExecuteTrans("delete from item_serial_temp where usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."'");
?>
