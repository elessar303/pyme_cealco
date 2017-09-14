<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);

require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
require_once("../../libs/php/clases/clase_db.inc.php");
include("../../libs/php/clases/correlativos.php");
include("../../libs/php/clases/factura.php");
include("../../libs/php/clases/almacen.php");

$conex1=$conn = new ConexionComun();
$correlativos = new Correlativos();
$factura = new Factura();
$clientes = new Almacen();
//se buscan los servicios pendientes

$sql="select * from cliente_cargos where  estatus=0 and fecha_fin<> '0000-00-00 00:00:00' and fecha_fin >= now()  group by id_servicio_material order by id_servicio_material";
$pendientes=$conn->ObtenerFilasBySqlSelect($sql);

//recorremos para generarr los cargos automaticos
if($pendientes!=null)
{
    $factura->BeginTrans();
    $conn->BeginTrans();
    foreach($pendientes as $key => $value)
    {
        
        //comenzamos a generar el pedido
        # obtenemos el correlativo de la factura
        
        $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 0, "si");
        $formateo_nro_factura = $nro_factura;
        #obtenemos el money actual
        $money=$clientes->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");
        
        $sql="INSERT INTO kardex_almacen (
            `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
            `fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`, id_cliente, nro_factura)
            (select `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
            `fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`, id_cliente, '".$nro_factura."' from kardex_almacen
            where id_transaccion='".$value['id_servicio_material']."'
            )";
        
        $conn->ExecuteTrans($sql);
        $id_facturaTrans = $conn->getInsertID();
        
        $sql="select * from kardex_almacen_detalle where id_transaccion='".$value['id_servicio_material']."'";
        $detalle_kardex=$conn->ObtenerFilasBySqlSelect($sql);
            foreach($detalle_kardex as $key2 => $value2)
            {
                $sql="insert into kardex_almacen_detalle (`id_transaccion` , `id_almacen_entrada` ,
                `id_almacen_salida` , `id_item` , `cantidad`, `id_ubi_salida`, precio)
                (select '".$id_facturaTrans."' , `id_almacen_entrada` ,
                `id_almacen_salida` , `id_item` , `cantidad`, `id_ubi_salida`,
                precio from kardex_almacen_detalle where id_transaccion_detalle='".$value2['id_transaccion_detalle']."')
                ";
                
                $conn->ExecuteTrans($sql);
            }
            
        $sql = "select nro_factura from kardex_almacen where id_transaccion='".$value['id_servicio_material']."'";
        $nro_factura2=$conn->ObtenerFilasBySqlSelect($sql);
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
            (
            select 
                `id_cliente`, '".$nro_factura."',`cod_vendedor`,`fechaFactura`,
                `subtotal`,`descuentosItemFactura`,`montoItemsFactura`,
                `ivaTotalFactura`,`TotalTotalFactura`,`cantidad_items`,
                `totalizar_sub_total`,`totalizar_descuento_parcial`,`totalizar_total_operacion`,
                `totalizar_pdescuento_global`,`totalizar_descuento_global`,
                `totalizar_base_imponible`,`totalizar_monto_iva`,
                `totalizar_total_general`,`totalizar_total_retencion`,`fecha_creacion`,
                `usuario_creacion`,`cod_estatus`,`formapago`, `impresora_serial`, `money`, `facturacion`
                from despacho_new
                where cod_factura='".$nro_factura2[0]['nro_factura']."')"
            ;
        $factura->ExecuteTrans($sql);
        $id_facturadespachoTrans = $factura->getInsertID();
        $sql="select b.*  from despacho_new as a inner join despacho_new_detalle as b on a.id_factura=b.id_factura  where cod_factura='".$nro_factura2[0]['nro_factura']."'";
        //echo $sql; exit();
        $despachoDetalle=$conn->ObtenerFilasBySqlSelect($sql);
        foreach($despachoDetalle as $key3 => $value3)
        {
            $sql=
            "
                insert into despacho_new_detalle (`id_factura`, `id_item`,
                `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                `fecha_creacion`, `_item_almacen`) 
                
                (select  '".$id_facturadespachoTrans."',  `id_item`,
                `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                `fecha_creacion`, `_item_almacen` 
                from despacho_new_detalle where id_detalle_factura=".$value3['id_detalle_factura'].") 
                ";
            //echo $sql; exit();
            $conn->ExecuteTrans($sql);
            
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
            (select `id_factura` ,`totalizar_monto_cancelar` ,
            `totalizar_saldo_pendiente` ,`totalizar_cambio` ,`totalizar_monto_efectivo` ,
            `opt_cheque` ,`totalizar_monto_cheque` ,`totalizar_nro_cheque` ,
            `totalizar_nombre_banco` ,`opt_tarjeta` ,`totalizar_monto_tarjeta` ,
            `totalizar_nro_tarjeta` ,`totalizar_tipo_tarjeta` ,`opt_deposito` ,
            `totalizar_monto_deposito` ,`totalizar_nro_deposito` ,
            `totalizar_banco_deposito` ,`opt_otrodocumento` ,`totalizar_monto_otrodocumento` ,
            `totalizar_nro_otrodocumento` ,`totalizar_banco_otrodocumento` ,`fecha_vencimiento` ,
            `observacion` ,`persona_contacto` ,`telefono` ,`fecha_creacion` ,`usuario_creacion`
            from despacho_new_detalle_formapago where id_factura='".$value3['id_factura']."'
            );";
            //echo $SQLdetalle_formapago; exit();
            $conn->ExecuteTrans($SQLdetalle_formapago);
            
            $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 1, "no");
            $factura->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_factura}' WHERE campo = 'cod_factura';");
            
            $nro_despacho1 = $correlativos->getUltimoCorrelativo("cod_factura", 1, "no");
            $factura->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_despacho1}' WHERE campo = 'cod_despacho';");
        
            if ($factura->errorTransaccion != null) 
            {
                
                //Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la factura.</span>");
                //Msg::setMessage("<span style=\"color:#62875f;\">Entrada Generada Exitosamente </span>");
                //header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
            }
        }
        
    }
}

//cambiando los estatus
$sql="select id from cliente_cargos where  estatus=0 and fecha_fin<> '0000-00-00 00:00:00' and fecha_fin < now()  group by id_servicio_material order by id_servicio_material";
$pendientes=$conn->ObtenerFilasBySqlSelect($sql);

//recorremos para generarr los cargos automaticos
if($pendientes!=null)
{
    foreach($pendientes as $key => $value)
    {
        $sql="update cliente_cargos set estatus=1 where id=".$value['id'];
        $conn->ExecuteTrans($sql);
    }
}

$factura->CommitTrans($factura->errorTransaccion);
$conn->CommitTrans($factura->errorTransaccion);
?>