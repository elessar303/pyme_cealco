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
$campos_comunes= $almacen->ObtenerFilasBySqlSelect("SELECT * FROM proveedores order by descripcion");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $nombre_proveedor=$item["descripcion"]."-".$item["rif"];
    $arraySelectoutPut[] = utf8_encode($nombre_proveedor);
}
$smarty->assign("option_values_proveedor", $arraySelectOption);
$smarty->assign("option_output_proveedor", $arraySelectoutPut);

//Ingreso de SELECT para la nueva seccion
//Estados
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from estados_puntos");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["nombre_estado"];
    $arrayubi[] = $item["codigo_estado"];
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

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

//Fin de los SELECTS para la nueva seccion

#################################################################################
if (isset($_POST["input_cantidad_items"])) { 

    for ($j = 0; $j < (int) $_POST["input_cantidad_items"]; $j++)
    {
        $cadena_item[$j]=$_POST["_id_item"][$j];
        $cadena_cantidad[$j]=$_POST["_cantidad"][$j];
        if($cadena_cantidad[$j]<0)
        {
            echo "Entrada con Cantidad en Negativo, Verificar Datos";
            exit();
        }

    }
    
// si el usuario hizo post
    if (!$pendiente) 
    {
        if($_POST['nombre']=="")
        {
            echo "Error, nombre no puede estar vacio";
            exit();
        }
        # Verificar que no se trata de una compra con estatus de entrega de productos "Pendiente"
        #list($dia, $mes, $anio) = explode('-', $_POST["input_fechacompra"]);
        $codigo_siga=$almacen->ObtenerFilasBySqlSelect("select codigo_siga from parametros_generales");
        
        $almacen->BeginTrans();
        $kardex_almacen_instruccion = "INSERT INTO cesta_clap (`nombre`, `cantidad`, `usuario_creacion`, `codigo_siga`)
        VALUES (
           '{$_POST["nombre"]}',
            '{$_POST["cantidad"]}', '{$login->getUsuario()}', '".$codigo_siga[0]['codigo_siga']."');";
        
        $almacen->ExecuteTrans($kardex_almacen_instruccion);
        
        
        $id_transaccion = $almacen->getInsertID();
        
        for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) 
        {
            $kardex_almacen_detalle_instruccion = "INSERT INTO cesta_clap_detalle
                    (`id_cesta`, `id_item`, `cantidad`)
                VALUES 
                (
                    '".$id_transaccion."', '{$_POST["_id_item"][$i]}', '{$_POST["_cantidad"][$i]}'  
                );";

            $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
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

    Msg::setMessage("<span style=\"color:#62875f;\">Entrada Generada Exitosamente </span>");
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
}

$almacen->ExecuteTrans("delete from item_serial_temp where usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."'");
?>
