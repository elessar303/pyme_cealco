
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
$almacen = new Almacen();
$login = new Login();
//cliente
$despacho=$_GET['despacho'];
//impresora
$impresora_serial=impresora_serial;
//usuario que la realiza
$id_usuario=$login->getIdUsuario();
//money
$money=$almacen->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");
if (empty($money)) 
{

    $sql="INSERT INTO closedcash_pyme(serial_caja, money, fecha_inicio, fecha_fin) VALUES ('".impresora_serial."', '".$_POST['serial'].date('Y-m-d_H:i:s')."',  now(), null)";
    $insert_money=$almacen->Execute2($sql);
}

$money=$almacen->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");

//primero vemos que sea un cliente o todos
if($despacho!='all') //proceso para un solo cliente
{
    //obtenemos lo pedidos pendientes por el cliente
    $sql="SELECT *,kardex_almacen.estado as estatus from kardex_almacen, clientes WHERE kardex_almacen.id_cliente=clientes.id_cliente and kardex_almacen.facturado=0 and kardex_almacen.nro_factura is not null and kardex_almacen.nro_factura<>'' and clientes.id_cliente=".$despacho."";
    $datos_cliente=$almacen->ObtenerFilasBySqlSelect($sql);
    //obtenemos todas las facturas asociadas
    $facturas="";
    if($datos_cliente==NULL)
    {
        echo 
        "
            <script type='text/javascript'>
            alert('El cliente no tiene Cargos pendientes');
            window.close();
            </script>
        ";
        exit();
    }
    foreach ($datos_cliente as $key => $value) 
    {
        if($value['nro_factura']!="")
        {
            $facturas.="'".$value['nro_factura']."', ";
        }
    }
    $facturas=substr($facturas, 0, -2);
    //
    $sql="SELECT * FROM kardex_almacen a, despacho_new b,  despacho_new_detalle c where a.nro_factura=b.cod_factura and b.id_factura=c.id_factura and b.cod_factura in (".$facturas.")";
    
    //pedidos 
    $regs_pedido=$almacen->ObtenerFilasBySqlSelect($sql);

    $sql="SELECT clientes.nombre, clientes.rif, clientes.direccion , clientes.telefonos  FROM  clientes where id_cliente=".$despacho;
    //cliente
    $array_cliente=$almacen->ObtenerFilasBySqlSelect($sql);
    $almacen->BeginTrans();
    
    $sql =
    "
        SELECT id_factura, cod_factura, cod_factura, nroz, '".$impresora_serial."', id_cliente, ".$id_usuario.", `fechaFactura`, `subtotal`, `descuentosItemFactura`, `montoItemsFactura`, `ivaTotalFactura`, `TotalTotalFactura`, `cantidad_items`, `totalizar_sub_total`, `totalizar_descuento_parcial`, `totalizar_total_operacion`, `totalizar_pdescuento_global`, `totalizar_descuento_global`, `totalizar_base_imponible`, `totalizar_monto_iva`, `totalizar_total_general`, `totalizar_total_retencion`, `formapago`, `cod_estatus`, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, `usuario_creacion`, '".$money[0]['money']."', `cesta_clap`, `facturacion`  FROM `despacho_new`
            WHERE fecha_pago='0000-00-00' and  id_cliente={$despacho} and cod_factura in (".$facturas.");
    ";
    $arraymaestro=$almacen->ObtenerFilasBySqlSelect($sql);
    //cambiamos el estatus de las facturas
    $sql="UPDATE kardex_almacen SET estado='Facturado', facturado=1  where nro_factura in (".$facturas.")";
    $array_pago_ticket=$almacen->ExecuteTrans($sql);
    foreach ($arraymaestro as $key => $maestro) 
    {
        
        $sql=
        "
            INSERT INTO `factura`(`cod_factura`, `cod_factura_fiscal`, `nroz`, `impresora_serial`, `id_cliente`, `cod_vendedor`, `fechaFactura`, `subtotal`, `descuentosItemFactura`, `montoItemsFactura`, `ivaTotalFactura`, `TotalTotalFactura`, `cantidad_items`, `totalizar_sub_total`, `totalizar_descuento_parcial`, `totalizar_total_operacion`, `totalizar_pdescuento_global`, `totalizar_descuento_global`, `totalizar_base_imponible`, `totalizar_monto_iva`, `totalizar_total_general`, `totalizar_total_retencion`, `formapago`, `cod_estatus`, `fecha_pago`, `fecha_creacion`, `usuario_creacion`, `money`, `cesta_clap`, `facturacion`) 
            values
            (
                 '{$maestro['cod_factura']}', '{$maestro['cod_factura']}',  '', '".$impresora_serial."',  {$maestro['id_cliente']},  ".$id_usuario.", '{$maestro['fechaFactura']}',  {$maestro['subtotal']},  {$maestro['descuentosItemFactura']},   {$maestro['montoItemsFactura']},  {$maestro['ivaTotalFactura']},  {$maestro['TotalTotalFactura']},  {$maestro['cantidad_items']},  {$maestro['totalizar_sub_total']},  {$maestro['totalizar_descuento_parcial']},  {$maestro['totalizar_total_operacion']},  {$maestro['totalizar_pdescuento_global']},  {$maestro['totalizar_descuento_global']},  {$maestro['totalizar_base_imponible']},   {$maestro['totalizar_monto_iva']},  {$maestro['totalizar_total_general']},  {$maestro['totalizar_total_retencion']},  '{$maestro['formapago']}',  {$maestro['cod_estatus']},  CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '{$maestro['usuario_creacion']}', '".$money[0]['money']."', '{$maestro['cesta_clap']}', ''
            );
        ";
        //se procede a guardar en factura los pedidos que no se han facturado. fecha_pago=0000-00-00
        $insertar_factura=$almacen->ExecuteTrans($sql);
        $id_facturaTrans = $almacen->getInsertID();
        $sql=
        "
            SELECT ".$id_facturaTrans.", `id_item`, `_item_almacen`, `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva`, `_item_descuento`, `_item_montodescuento`, `_item_piva`, `_item_totalsiniva`, `_item_totalconiva`, ".$id_usuario.", c.fecha_creacion FROM `despacho_new_detalle` c, `despacho_new` b
            WHERE b.id_factura=c.id_factura and b.id_factura={$maestro['id_factura']};
            
        ";
        $detalles=$almacen->ObtenerFilasBySqlSelect($sql);
        foreach ($detalles as $key => $value) 
        {
            $sql=
            "
                INSERT INTO `factura_detalle`(`id_factura`, `id_item`, `_item_almacen`, `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva`, `_item_descuento`, `_item_montodescuento`, `_item_piva`, `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion`, `fecha_creacion`) 
                values ( ".$id_facturaTrans.", ".$value['id_item'].", ".$value['_item_almacen'].", '".$value['_item_descripcion']."', ".$value['_item_cantidad'].", ".$value['_item_preciosiniva'].", ".$value['_item_descuento'].", ".$value['_item_montodescuento'].", ".$value['_item_piva'].", ".$value['_item_totalsiniva'].", ".$value['_item_totalconiva'].", ".$id_usuario.", '".$value['fecha_creacion']."')
            ";
            $insertar_factura_detalle=$almacen->ExecuteTrans($sql);
        }
    }
    //se busca las ubicaciones de este cliente que esten ocupadas
    $sql=
    "
        select a.*, c.id as ubicacion from kardex_almacen a inner join kardex_almacen_detalle b on a.id_transaccion=b.id_transaccion
        inner join ubicacion c on b.id_ubi_entrada=c.id where c.ocupado=1 and id_cliente='".$despacho."'
    ";
    $ubicaciones=$almacen->ObtenerFilasBySqlSelect($sql);
    if($ubicaciones!=NULL)
    {
        foreach ($ubicaciones as $key => $value3)
        {
            $iva="";
            $base="";
            $total="";
            $idservicios="";
            $codservicio="";
            $nombreservicio="";
            $contador=0;
            $sql="select id_servicio from ubicacion_servicio where id_ubicacion=".$value3['ubicacion'];
            $buscarserviciosubicacion=$almacen->ObtenerFilasBySqlSelect($sql);
            foreach($buscarserviciosubicacion as $key => $servicios)
            {
                $sql="select id_item, cod_item, precio1, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
                $contarservicio=$almacen->ObtenerFilasBySqlSelect($sql);
                $iva[$contador] = $contarservicio[0]['iva'];
                $base[$contador] = $contarservicio[0]['precio1'];
                $total[$contador] = $contarservicio[0]['precio1']+(($contarservicio[0]['precio1']*$contarservicio[0]['iva']) / 100);
                $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                $idservicios[$contador]= $contarservicio[0]['id_item'];
                $codservicio[$contador]= $contarservicio[0]['cod_item'];
                $contador++;
            }

            $sql="Select concat(formato, (contador + 1)) as cod_factura from correlativos where campo='cod_factura'";
            $correlativo=$almacen->ObtenerFilasBySqlSelect($sql);
            $nro_factura4 = $correlativo[0]['cod_factura'];//$correlativos->getUltimoCorrelativo("cod_factura", 1, "si");
            $sql="UPDATE correlativos SET contador = ({$nro_factura4}) WHERE campo = 'cod_factura';";
            $almacen->ExecuteTrans($sql);
            $subtotal=0;
            $ivatotal=0;
            $itemstotal=count($total);
            $totaltotal=0;
            //se guarda los totales del resultado de los arreglos
            for($i=0; $i<count($total); $i++)
            {
                $subtotal+=$base[$i];
                $ivatotal+=(($base[$i]*$iva[$i]) / 100);
                $totaltotal+=$total[$i];
            }
            
            #obtenemos el money actual
            $money=$almacen->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");

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
                {$despacho}, '{$nro_factura4}', '{$login->getUsuario()}', now(),
                ". $subtotal . ", 0 ," . $subtotal . ","
                    . $ivatotal . "," . $totaltotal . "," . $itemstotal . ","
                    . $subtotal . ", 0," . $totaltotal . ", 0 ,  
                    0," . $subtotal . ","
                    . $ivatotal . ", " . $totaltotal . ",0 ,CURRENT_TIMESTAMP,'" . $login->getUsuario() . "',
                    '1', 'contado', '".impresora_serial."' , '".$money[0]['money']."',''
                );";
            $almacen->ExecuteTrans($sql);
            $id_facturaTrans2 = $almacen->getInsertID();
            $kardex_almacen_instruccion = 
            "
                INSERT INTO kardex_almacen (
                `id_transaccion` ,
                `tipo_movimiento_almacen` ,
                `autorizado_por` ,
                `observacion` ,
                `fecha` ,
                `usuario_creacion`,
                `fecha_creacion`,
                `estado`,
                `fecha_ejecucion`,
                id_cliente, 
                nro_factura
                )
                VALUES (
                NULL ,
                '8',
                '" . $login->getUsuario() . "',
                'Salida por Ventas',
                now(),
                '" . $login->getUsuario() . "',
                CURRENT_TIMESTAMP,
                'Pendiente',
                now(),
                {$despacho},
                '{$nro_factura4}'
                );
            ";
            $almacen->ExecuteTrans($kardex_almacen_instruccion);
            $id_transaccion3 = $almacen->getInsertID();
            for($i=0; $i<count($total); $i++)
            {
                if($total[$i]!=null)
                {
                    $descripcion =  $nombreservicio[$i];
                    
                    $detalle_item_instruccion = 
                    "
                        INSERT INTO despacho_new_detalle (
                        `id_factura`, `id_item`,
                        `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                        `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                        `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                        `fecha_creacion`, `_item_almacen`
                        )
                        VALUES (
                        '{$id_facturaTrans2}', '{$idservicios[$i]}',
                        '{$descripcion}', '1', '{$base[$i]}',
                        0, 0, '{$iva[$i]}',
                        '{$base[$i]}', '{$total[$i]}', '{$login->getUsuario()}',
                        CURRENT_TIMESTAMP, '1'
                        );
                    ";
                    $almacen->ExecuteTrans($detalle_item_instruccion);
                    $kardex_almacen_detalle_instruccion = 
                    "
                        INSERT INTO kardex_almacen_detalle (
                        `id_transaccion_detalle` ,
                        `id_transaccion` ,
                        `id_almacen_entrada` ,
                        `id_almacen_salida` ,
                        `id_item` ,
                        `precio` ,
                        `cantidad`
                        )
                        VALUES (
                        NULL ,
                        '" . $id_transaccion3 . "',
                        '{$value3['ubicacion']}',
                        '',
                        '" . $idservicios[$i] . "',
                        '" . $base[$i] . "',
                        1);
                    ";
                    $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
                }
            }
                
                
        }
    }
    if ($almacen->errorTransaccion == 1)
    {    
        echo "asdasd"; 
        //echo "paso";   
        //Msg::setMessage("<span style=\"color:#62875f;\">Salida Generada Exitosamente </span>");
    }
    elseif($almacen->errorTransaccion == 0)
    {
        //Msg::setMessage("<span style=\"color:red;\">Error al tratar de cargar la Salida, por favor intente nuevamente.</span>");
    }
    $almacen->CommitTrans($almacen->errorTransaccion);
    //header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
else
{

}


?>