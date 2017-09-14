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
require_once("../../libs/php/clases/login.php");
require_once("../../libs/php/configuracion/config.php");
include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");
include("../../libs/php/clases/factura.php");
include("../../libs/php/clases/correlativos.php");
require_once("../../libs/php/clases/parametrosgenerales.php");

$login = new Login();
$almacen = new Almacen();
$conn = new Almacen();
$factura = new Factura();
$correlativos = new Correlativos();
$pendiente = false;
$pos=POS;

$login = new Login();
if (isset($_GET["cod"]) /* && isset($_GET["cod2"]) */) 
{
	
    /*$sql = "SELECT kd.id_item, kd.cantidad, kd.id_almacen_entrada, kd.id_ubi_entrada, i.descripcion1, i.codigo_barras FROM calidad_almacen_detalle AS kd, item AS i WHERE i.id_item = kd.id_item AND kd.id_transaccion = {$_GET["cod"]};";
    $productos_pendientes_entrada = $almacen->ObtenerFilasBySqlSelect($sql);
    $detalles_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT autorizado_por, observacion, id_documento FROM calidad_almacen WHERE id_transaccion = {$_GET["cod"]};");
    $detalles_compra_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT num_factura_compra, num_cont_factura FROM compra WHERE id_compra = {$detalles_pendiente[0]["id_documento"]};");
    $smarty->assign("detalles_pendiente", $detalles_pendiente);
    $smarty->assign("productos_pendientes_entrada", $productos_pendientes_entrada);
    $smarty->assign("datos_factura", $detalles_compra_pendiente);
    $smarty->assign("cod", $_GET["cod"]);
    #$smarty->assign("cod2", $_GET["cod2"]);*/
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

//rubro a cliente
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes= $almacen->ObtenerFilasBySqlSelect("SELECT id_cliente, concat(cod_cliente, ' - ', nombre ) as nombre FROM clientes where id_cliente='".$_GET['cod']."'");
    $id_cliente = $campos_comunes[0]["id_cliente"];
    $nombre_proveedor=utf8_encode($campos_comunes[0]["nombre"]);
    
    $smarty->assign("id_cliente", $id_cliente);
    $smarty->assign("nombre_cliente", $nombre_proveedor);
//fin rubro

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
if (isset($_POST["input_cantidad_items"])) 
{

    $total =0; $totaliva = 0; $iva=0; $cantidad=0;
    for ($j = 0; $j < (int) $_POST["input_cantidad_items"]; $j++)
    {
        $cantidad++;
        $cadena_item[$j]=$_POST["_id_item"][$j];
        $precios=$conn->ObtenerFilasBySqlSelect("select precio1, iva from item where id_item=".$_POST['_id_item'][$j]);
        $total+=$precios[0]['precio1'];
        $iva+=($precios[0]['precio1']*$precios[0]['iva']/100);
        $totaliva+=(($precios[0]['precio1']*$precios[0]['iva']/100)+$precios[0]['precio1']);
        $cadena_cantidad[$j]=$_POST["_cantidad"][$j];
        if($cadena_cantidad[$j]<0)
        {
            echo "Entrada con Cantidad en Negativo, Verificar Datos";
            exit();
        }

    }
    
    // si el usuario hizo post
    if ($pendiente==1) 
    {
        


        $ubicacion= $conn->ObtenerFilasBySqlSelect("Select cod_almacen, id_ubicacion from parametros_generales");
        # Verificar que no se trata de una compra con estatus de entrega de productos "Pendiente"
        #list($dia, $mes, $anio) = explode('-', $_POST["input_fechacompra"]);
        $codigo_siga=$almacen->ObtenerFilasBySqlSelect("select codigo_siga from parametros_generales");
        $sucursal=$codigo_siga[0]['codigo_siga'];
        //comienza a realizar el pedido
        $factura->BeginTrans();
        $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 0, "si");
        $formateo_nro_factura = $nro_factura;

        //haciendo el pedido
        $money=$conn->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");
        
        
        $sql = "INSERT INTO `despacho_new` (
            `id_cliente`,`cod_factura`,`cod_vendedor`,`fechaFactura`,
            `subtotal`,`descuentosItemFactura`,`montoItemsFactura`,
            `ivaTotalFactura`,`TotalTotalFactura`,`cantidad_items`,
            `totalizar_sub_total`,`totalizar_descuento_parcial`,`totalizar_total_operacion`,
            `totalizar_pdescuento_global`,`totalizar_descuento_global`,
            `totalizar_base_imponible`,`totalizar_monto_iva`,
            `totalizar_total_general`,`totalizar_total_retencion`,`fecha_creacion`,
            `usuario_creacion`,`cod_estatus`,`formapago`, `impresora_serial`, `money`, `facturacion`
            )
        VALUES(
            {$_POST["id_cliente"]}, '{$nro_factura}', '{$login->getIdUsuario()}', now(),
            {$total}, 0, {$totaliva},
            {$iva}, {$totaliva}, {$cantidad},
            {$total}, 0, {$totaliva},
            0, 0,
            {$total}, {$iva},
            {$totaliva}, 0, CURRENT_TIMESTAMP,
            '{$login->getnombreApellidoUSuario()}', '1', 'contado', '".impresora_serial."' , '".$money[0]['money']."','siscol'
            );";
        $factura->ExecuteTrans($sql);
        $id_facturaTrans = $factura->getInsertID();

        $tienePedido = $factura->ObtenerFilasBySqlSelect("SELECT * FROM pedido WHERE id_cliente= {$_POS["id_cliente"]} AND cod_estatus = 1 AND id_factura = 0 AND id_pedido = {$_POST['pedido_seleccionado']};");
        $tieneNotaEntrega = $factura->ObtenerFilasBySqlSelect("SELECT * FROM nota_entrega WHERE id_cliente= {$_POS["id_cliente"]} AND cod_estatus = 1 AND id_factura = 0 AND id_nota_entrega = {$_POST['nota_entrega_seleccionada']};");
        $tieneCotizacion = $factura->ObtenerFilasBySqlSelect("SELECT * FROM cotizacion_presupuesto WHERE id_cliente= {$_POS["id_cliente"]} AND cod_estatus = 1 AND id_factura = 0 AND id_cotizacion = {$_POST['cotizacion_seleccionada']};");

        if ($tienePedido) {
            $factura->ExecuteTrans("UPDATE pedido SET cod_estatus = 2, id_factura = {$id_facturaTrans} WHERE id_cliente = {$_POS["id_cliente"]} AND id_pedido = {$_POST['pedido_seleccionado']};");
        }
        if ($tieneNotaEntrega) {
            $factura->ExecuteTrans("UPDATE nota_entrega SET cod_estatus = 2, id_factura = {$id_facturaTrans} WHERE id_cliente = {$_POS["id_cliente"]} AND id_nota_entrega = {$_POST['nota_entrega_seleccionada']};");
        }
        if ($tieneCotizacion) {
            $factura->ExecuteTrans("UPDATE cotizacion_presupuesto SET cod_estatus = 2, id_factura = {$id_facturaTrans} WHERE id_cliente = {$_POS["id_cliente"]} AND id_cotizacion = {$_POST['cotizacion_seleccionada']};");
        }
        if($_POST["fecha_vencimiento"] == "")
            $_POST["fecha_vencimiento"] = "NULL";
        else
            $_POST["fecha_vencimiento"] = "'".$_POST["fecha_vencimiento"]."'";
         $_POST["fecha_vencimiento"];   
        $SQLdetalle_formapago = "INSERT INTO despacho_new_detalle_formapago (
            `id_factura` ,`totalizar_monto_cancelar` ,
            `totalizar_saldo_pendiente` ,`totalizar_cambio` ,`totalizar_monto_efectivo` ,
            `opt_cheque` ,`totalizar_monto_cheque` ,`totalizar_nro_cheque` ,
            `totalizar_nombre_banco` ,`opt_tarjeta` ,`totalizar_monto_tarjeta` ,
            `totalizar_nro_tarjeta` ,`totalizar_tipo_tarjeta` ,`opt_deposito` ,
            `totalizar_monto_deposito` ,`totalizar_nro_deposito` ,
            `totalizar_banco_deposito` ,`opt_otrodocumento` ,`totalizar_monto_otrodocumento` ,
            `totalizar_nro_otrodocumento` ,`totalizar_banco_otrodocumento` ,`fecha_vencimiento` ,
            `observacion` ,`persona_contacto` ,`telefono` ,`fecha_creacion` ,`usuario_creacion`)
        VALUES ({$id_facturaTrans}, '{$totaiva}',
                '{$totaiva}', '{$totaliva}', '{$_POST["input_totalizar_monto_efectivo"]}',
                '{$_POST["opt_cheque"]}', '{$_POST["input_totalizar_monto_cheque"]}', '{$_POST["input_totalizar_nro_cheque"]}',
                '{$_POST["input_totalizar_nombre_banco"]}', '{$_POST["opt_tarjeta"]}', '{$_POST["input_totalizar_monto_tarjeta"]}',
                '{$_POST["input_totalizar_nro_tarjeta"]}', '{$_POST["input_totalizar_tipo_tarjeta"]}', '{$_POST["opt_deposito"]}',
                '{$_POST["input_totalizar_monto_deposito"]}', '{$_POST["input_totalizar_nro_deposito"]}',
                '{$_POST["input_totalizar_banco_deposito"]}', '{$_POST["opt_otrodocumento"]}',
                '{$_POST["totalizar_monto_otrodocumento"]}', '{$_POST["totalizar_nro_otrodocumento"]}',
                '{$_POST["totalizar_banco_otrodocumento"]}', '{$_POST["fecha_vencimiento"]}',
                '{$_POST["observacion"]}', '{$_POST["persona_contacto"]}', '{$_POST["telefono"]}', CURRENT_TIMESTAMP, '{$usuario}');";

        $factura->ExecuteTrans($SQLdetalle_formapago);

        $consulta = "INSERT INTO factura_impuestos (
                `id_factura` ,`totalizar_base_retencion` ,
                `totalizar_pbase_retencion` ,`totalizar_descripcion_base_retencion` ,
                `cod_impuesto_iva` , `totalizar_monto_iva2` ,`totalizar_monto_1x1000`,`usuario_creacion`,`fecha_creacion`)
            VALUES (
                '{$id_facturaTrans}', '{$_POST["totalizar_base_retencion"]}', '{$_POST["totalizar_pbase_retencion"]}',
                '{$_POST["totalizar_descripcion_base_retencion"]}', '{$_POST["cod_impuesto_iva"]}',
                '{$_POST["totalizar_monto_iva2"]}', '{$_POST["totalizar_monto_1x1000"]}', '{$usuario}', CURRENT_TIMESTAMP);";

        $factura->ExecuteTrans($consulta);
        if(!isset($_POST["puntodeventa"]))
        {
            $kardex_almacen_instruccion = "INSERT INTO kardex_almacen (
                    `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
                    `fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`, id_cliente, nro_factura)
               VALUES (
                    '8', '{$login->getusuario()}', 'Pedido',
                    now(), '{$login->getusuario()}', CURRENT_TIMESTAMP,
                    'Pendiente', now(), {$_POST["id_cliente"]},'{$nro_factura}');";
            }elseif($_POST["puntodeventa"]!=''){
                $kardex_almacen_instruccion = "INSERT INTO kardex_almacen (
                    `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
                    `fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`, id_cliente, nro_factura, almacen_destino)
               VALUES (
                    '8', '{$usuario}', 'Pedido',
                    '{$_POST["input_fechaFactura"]}', '{$usuario}', CURRENT_TIMESTAMP,
                    '{$_POST["estado_entrega"]}', '{$_POST["input_fechaFactura"]}', {$_POST["id_cliente"]},'{$nro_factura}', '{$_POST["puntodeventa"]}');";
            }
            $almacen->ExecuteTrans($kardex_almacen_instruccion);
            $id_transaccion = $almacen->getInsertID();


        $i=-1;
        
        foreach ($_POST['_id_item'] as $key => $value) 
        {
            $i++;
            $producto=$conn->ObtenerFilasBySqlSelect("select * from item where id_item=".$value);
            $_POST['_item_codigo'][$i]=$producto[0]['id_item'];
            $_POST['_item_descripcion'][$i]=$producto[0]['descripcion1'];
            $_POST['_item_preciosiniva'][$i]=$producto[0]['precio1'];
            $_POST['_item_descuento'][$i]=0;
            $_POST['_item_montodescuent'][$i]=0;
            $_POST['_item_piva'][$i]=$producto[0]['iva'];
            $_POST['_item_totalsiniva'][$i]=$producto[0]['precio1'];
            $_POST['_item_totalconiva'][$i]=((($producto[0]['precio1']*$producto[0]['iva'])/100) + $producto[0]['precio1']);
            $_POST['_item_almacen'][$i]=1;
            //$_POST['_fechainicio'][$i]=date_format(date_create($_POST['_fechainicio'][$i]), 'Y-m-d');
            $_POST['_fechainicio'][$i]=date_create($_POST['_fechainicio'][$i]);
            $_POST['_fechainicio'][$i]=date_format($_POST['_fechainicio'][$i], 'Y-m-d');
            $_POST['_fechafin'][$i]=date_create($_POST['_fechafin'][$i]);
            $_POST['_fechafin'][$i]=date_format($_POST['_fechafin'][$i], 'Y-m-d');
            $sql="insert into cliente_cargos (id_cliente, id_servicio_material, costo, fecha_inicio, fecha_fin, id_usuario, fecha_creacion, observacion) values ({$_POST['id_cliente']}, {$id_transaccion}, {$totaliva}, '{$_POST['_fechainicio'][$i]}', '{$_POST['_fechafin'][$i]}', {$login->getIdUsuario()}, now(), '')";
            $factura->ExecuteTrans($sql);    
            $detalle_item_instruccion = "
            INSERT INTO despacho_new_detalle (
                `id_factura`, `id_item`,
                `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                `fecha_creacion`, `_item_almacen`
                )
            VALUES (
                '{$id_facturaTrans}', '{$_POST["_item_codigo"][$i]}',
                '{$_POST["_item_descripcion"][$i]}', '1', '{$_POST["_item_preciosiniva"][$i]}',
                '{$_POST["_item_descuento"][$i]}', '{$_POST["_item_montodescuento"][$i]}', '{$_POST["_item_piva"][$i]}',
                '{$_POST["_item_totalsiniva"][$i]}', '{$_POST["_item_totalconiva"][$i]}', '{$login->getusuario()}',
                CURRENT_TIMESTAMP, '{$_POST["_item_almacen"][$i]}'
                );";
        $factura->ExecuteTrans($detalle_item_instruccion);

        $descrip_producto = strlen($_POST["_item_descripcion"][$i]) < 39 ? str_pad($_POST["_item_descripcion"][$i], 39) : substr($_POST["_item_descripcion"][$i], 0, 39);
        $item = $factura->ObtenerFilasBySqlSelect("SELECT cod_item, descripcion2 , seriales FROM item WHERE id_item = {$_POST["_item_codigo"][$i]}");
        $codigo_item = $item[0]['cod_item'];
        $cantidad = number_format($_POST["_item_cantidad"][$i], 2, ",", "");
        $precio = number_format($_POST["_item_preciosiniva"][$i], 2, ",", "");
        $iva = number_format($_POST["_item_piva"][$i], 2, ",", "");
        $descuento_item = number_format($_POST["_item_descuento"][$i], 2, ",", "");
        $seriales = $item[0]['seriales'];
        /* $espacios = 30 - (strlen($codigo_item) + strlen($cantidad));
          for ($j = 0; $j < $espacios; $j++) {
          $codigo_item .= " ";
          } */
        $codigo_item = str_pad($codigo_item, 30 - strlen($cantidad), " ", STR_PAD_RIGHT);
        #$linea_producto.=$descrip_producto . " " . $codigo_item . $cantidad . str_pad($precio, 12, ' ', STR_PAD_LEFT) . str_pad($iva, 7, ' ', STR_PAD_LEFT) . "\n";
        $lineas[$i] = array("descripcion" => $descrip_producto, "codigo" => $codigo_item, "cantidad" => $cantidad, "precio" => $precio, "iva" => $iva, "descuento_item" => $descuento_item);

        if ($item[0]['descripcion2'] != "") {# antes $descrip_producto[0]['descripcion2']
            #$linea_producto.=$item[0]['descripcion2'] . "\n"; # antes $descrip_producto[0]['descripcion2']
            #$lineas["descripcion2"] = $item[0]['descripcion2'];
        }
        #fwrite($archivo_spooler, $linea_producto);
        if (!$tienePedido && !$tieneNotaEntrega) {
            $ubicacion_venta= $id_ubicacion[0]['id_ubicacion'];

            //Se consulta el precio actual para dejar el historico en kardex (Junior)
            $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$_POST["_item_codigo"][$i]}'";
            $precio_actual=$almacen->ObtenerFilasBySqlSelect($sql);

            $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);
            $almacenlocal=$almacen->ObtenerFilasBySqlSelect("select a.cod_almacen as almacen, b.id as ubicacion from almacen as a inner join ubicacion as b on a.cod_almacen=b.id_almacen  limit 1");
            $kardex_almacen_detalle_instruccion = "
            INSERT INTO kardex_almacen_detalle (
                `id_transaccion` , `id_almacen_entrada` ,
                `id_almacen_salida` , `id_item` , `cantidad`, `id_ubi_salida`, precio, elaboracion)
            VALUES (
                $id_transaccion, '', ".$almacenlocal[0]['almacen'].",
                '{$_POST["_item_codigo"][$i]}', '1', ".$almacenlocal[0]['ubicacion'].", $precioconiva, now());";
            
            $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

            if ($_POST["estado_entrega"] == 'Entregado' || $_POST["estado_entrega"] =='Pendiente') {
                $campos = $factura->ObtenerFilasBySqlSelect("
                        SELECT cantidad FROM item_existencia_almacen
                        WHERE id_item  = '{$_POST["_item_codigo"][$i]}' AND cod_almacen = '{$_POST["_item_almacen"][$i]}' AND id_ubicacion='{$_POST["_item_ubicacion"][$i]}';");
                # Verificar que se esta facturando para no descontar de la existencia,
                # ya que el propio pedido desconto
                #if (!$tienePedido) {
                $factura->ExecuteTrans("
                    UPDATE item_existencia_almacen
                    SET cantidad = '" . ($campos[0]["cantidad"] - $_POST["_item_cantidad"][$i]) . "'
                    WHERE id_item  = '{$_POST["_item_codigo"][$i]}' AND cod_almacen = '{$_POST["_item_almacen"][$i]}' AND id_ubicacion='{$_POST["_item_ubicacion"][$i]}';");
                #}
                $factura->ExecuteTrans("DELETE FROM item_precompromiso WHERE cod_item_precompromiso = '{$_POST["_cod_item_precompromiso"][$i]}';");
            }
        }
        /* Funcionalidad adicionada como requisito especifico del CCP */
        if($seriales==1)
        {
            $cantt=$_POST["_item_cantidad"][$i];
            
            if($kk==0)
            {
                //nuevo 17
                $correlativos = new Correlativos();
                $nro_correlativo = $correlativos->getUltimoCorrelativo("cod_despacho", 1, "si");
                 $factura->ExecuteTrans("insert into despacho (cod_despacho, id_factura, fecha_creacion, usuario, cantidad_item, estatus) values ('".$nro_correlativo."', '$id_facturaTrans', CURRENT_TIMESTAMP, '{$usuario}','$cantt',0)");
                    $id_despacho = $factura->getInsertID();              
                 $kk=1;
                 
            }
            else 
            {
                 $factura->ExecuteTrans("update despacho set cantidad_item=cantidad_item+$cantt where id='$id_despacho'");
            }
            
            for($ii=1; $ii<=$cantt; $ii++) 
            {
             $factura->ExecuteTrans("insert into despacho_detalle (id_despacho, id_item, item_descripcion) values ('$id_despacho', '{$_POST["_item_codigo"][$i]}', '{$_POST["_item_descripcion"][$i]}')");
            }   
        }
    }

        
        $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 1, "no");
        $factura->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_factura}' WHERE campo = 'cod_factura';");
        $nro_despacho1 = $correlativos->getUltimoCorrelativo("cod_factura", 1, "no");
        $factura->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_despacho1}' WHERE campo = 'cod_despacho';");
        if ($factura->errorTransaccion == 0) {
            Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la factura.</span>");
        }
        $valorImpresora= 0;
        if($erroImpresora==1){
            $valorImpresora= 0;
        }else{
            $valorImpresora= 1;    
             $factura->CommitTrans($factura->errorTransaccion);
        }
        /******************************************+fin factura**********************************************/
        Msg::setMessage("<span style=\"color:#62875f;\">Entrada Generada Exitosamente </span>");
        header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
    }
}


?>
