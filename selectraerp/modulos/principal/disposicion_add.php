<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
include_once("../../libs/php/clases/correlativos.php");
//////////almacen///////////
$almacen = new Proveedores();
$campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM almacen;");
foreach ($campos as $key => $item) 
{
    $arraySelectOption[] = $item["cod_almacen"];
    $arraySelectoutPut[] = $item["descripcion"];
}
//$smarty->assign("name_form", "reporte");
$smarty->assign("option_values_almacen", $arraySelectOption);
$smarty->assign("option_output_almacen", $arraySelectoutPut);
///////ubicacion///////////
$arraySelectOption="";
$arraySelectoutPut="";
$ubica= new Proveedores();
$conn = new ConexionComun();
$login = new Login();
$usuario = $login->getIdUsuario();

$campos=$ubica->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion where descripcion<>'PISO DE VENTA'");
foreach($campos as $key=>$item)
{
    $arraySelectOption[]=$item["id"];
    $arraySelectOutPut[]=$item["descripcion"];
}
$smarty->assign("option_values_ubicacion1", $arraySelectOption); 
$smarty->assign("option_values_ubicacion2", $arraySelectOutPut);


$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

$cliente = new Clientes();
$campos = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM clientes ORDER BY nombre");
foreach ($campos as $key => $item) {
    $arraySelectOption2[] = $item["id_cliente"];
    $arraySelectOutPut2[] = $item["nombre"]." - ".$item["rif"];
}
$smarty->assign("option_values_cliente", $arraySelectOption2);
$smarty->assign("option_output_cliente", $arraySelectOutPut2);

$campos = $ubica->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE cod_item_forma=1 ORDER BY codigo_barras");
foreach ($campos as $key => $item) {
    $arraySelectOption3[] = $item["id_item"];
    $arraySelectOutPut3[] = $item["codigo_barras"]." - ".$item["descripcion1"];
}
$smarty->assign("option_values_producto", $arraySelectOption3);
$smarty->assign("option_output_producto", $arraySelectOutPut3);

$fecha = new DateTime();
$fecha->modify('first day of this month');
$smarty->assign("firstday", $fecha->format('Y-m-d'));
$fecha->modify('last day of this month');
$smarty->assign("lastday", $fecha->format('Y-m-d'));
#$smarty->assign("fecha_mes_anio", $fecha->format('F Y'));

if(isset($_POST['aceptar']))
{
    $almacen=$_POST['almacen_entrada'];
    $ubicacion=$_POST['ubicacion'];
    $cliente=$_POST['cliente'];
    $item=$_POST['item'];
    $fecha_inicio=$_POST['fecha'];
    $fecha_fin=$_POST['fecha2'];
    //se procede a realizar el commit(quedaría pendiente realizar el pedido por entrada)
    //se comienza hacer la facturacion
    //tipo cliente
    $sql="select cod_tipo_cliente from clientes where id_cliente={$cliente}";
    $clienteseguro=0;
    $conn->BeginTrans();
    $tipocliente=$conn->ObtenerFilasBySqlSelect($sql);
    if($tipocliente[0]['cod_tipo_cliente']==1)
    {
        //privada
        $precio='precio1';
        $clienteseguro=1;
    }
    elseif ($tipocliente[0]['cod_tipo_cliente']==2) 
    {
        //publica
        $precio='precio2';
    }
    else
    {
        //minpal
        $precio='precio3';
    }
    $iva[]="";
    $base[]="";
    $total[]="";
    $idservicios[]="";
    $codservicio[]="";
    $nombreservicio[]="";
    $contador=0;
    $cobrar="";
    //guardando los servicios
    //fin del cobro de movimiento, ahora se cobra la ubicacion
    //si selecciona todo debe buscarse solo las ubicaciones que esten vacias.
    $bandera=0;
    foreach($ubicacion as $key => $ubicaciones)
    {
        if($ubicaciones==0)
        {
            $bandera=1;    
        }
    }
    if($bandera==1)
    {
        $sql="select id from ubicacion where ocupado=0 and id_almacen={$almacen}";
        $ubicaciones=$conn->ObtenerFilasBySqlSelect($sql);
        $ubicacion="";
        foreach($ubicaciones as $key => $buscar)
        {
            
            $ubicacion[]=$buscar['id'];
        }
        
    }
    
    foreach($ubicacion as $key => $buscar)
    {
        $sql="select id_servicio from ubicacion_servicio where id_ubicacion=".$buscar;
        $buscarserviciosubicacion=$conn->ObtenerFilasBySqlSelect($sql);
        foreach($buscarserviciosubicacion as $key => $servicios)
        {
            $sql="select id_item, cod_item, precio1, precio2, precio3, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
            $contarservicio=$conn->ObtenerFilasBySqlSelect($sql);
            $iva[$contador] = $contarservicio[0]['iva'];
            $base[$contador] = $contarservicio[0][$precio];
            $total[$contador] = $contarservicio[0][$precio]+(($contarservicio[0][$precio]*$contarservicio[0]['iva']) / 100);
            $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
            $idservicios[$contador]= $contarservicio[0]['id_item'];
            $codservicio[$contador]= $contarservicio[0]['cod_item'];
            $contador++;
        }
        
        $sql="INSERT INTO `disposicion` (`idcliente`, `idalmacen`, `idubicacion`, `fecha_inicio`, `fecha_fin`, `usuario`) 
        VALUES ({$cliente}, {$almacen}, {$buscar}, '{$fecha_inicio}', '{$fecha_fin}', {$usuario})";
        $conn->ExecuteTrans($sql);
        $sql="update ubicacion set ocupado=2 where id=".$buscar;
        $conn->ExecuteTrans($sql);
    }
    
    
    
    $correlativos = new Correlativos();
    $nro_pedido = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "si");
    $formateo_nro_factura = $nro_pedido;
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
    
   // debemos ver si hay pedido pendiente, para eso verificamos la fecha de pago
    $sql="select * from despacho_new where fecha_pago='0000-00-00' and id_cliente='{$cliente}' limit 1";
    $cargosoriginal=$conn->ObtenerFilasBySqlSelect($sql);
    if($cargosoriginal==null)
    {
        //es su primer cargo por lo que hay que crear el despacho new padre.
        $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 1, "si");
        $formateo_nro_factura = $nro_factura;
        #obtenemos el money actual
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
            {$cliente}, '{$nro_factura}', '{$login->getUsuario()}', now(),
            ". $subtotal . ", 0 ," . $subtotal . ","
                . $ivatotal . "," . $totaltotal . "," . $itemstotal . ","
                . $subtotal . ", 0," . $totaltotal . ", 0 ,  
                0," . $subtotal . ","
                . $ivatotal . ", " . $totaltotal . ",0 ,CURRENT_TIMESTAMP,'" . $login->getUsuario() . "',
                '" . $cod_estatus = 1 . "', 'contado', '".impresora_serial."' , '".$money[0]['money']."',''
            );";
            $conn->ExecuteTrans($sql);
        $id_facturaTrans = $conn->getInsertID();
        $kardex_almacen_instruccion = "
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
                    {$cliente},
                    '{$nro_factura}'
                    );";
    
        $conn->ExecuteTrans($kardex_almacen_instruccion);
        $id_transaccion = $conn->getInsertID();
        for($i=0; $i<count($total); $i++)
        {
            if($total[$i]!=null)
            {
                $descripcion =  $nombreservicio[$i];
               
                $detalle_item_instruccion = "
                INSERT INTO despacho_new_detalle (
                `id_factura`, `id_item`,
                `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                `fecha_creacion`, `_item_almacen`
                )
                VALUES (
                '{$id_facturaTrans}', '{$idservicios[$i]}',
                '{$descripcion}', '1', '{$base[$i]}',
                0, 0, '{$iva[$i]}',
                '{$base[$i]}', '{$total[$i]}', '{$usuario}',
                CURRENT_TIMESTAMP, '1'
                );";
                $conn->ExecuteTrans($detalle_item_instruccion);
                
                $kardex_almacen_detalle_instruccion = "
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
                '" . $id_transaccion . "',
                '{$almacen}',
                '',
                '" . $idservicios[$i] . "',
                '" . $base[$i] . "',
                1
                );";
                $conn->ExecuteTrans($kardex_almacen_detalle_instruccion);
            }
        }
        $nro_facturaOLD = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "no");
        $nro_pedido = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "no");
        $conn->ExecuteTrans("update correlativos set contador = '" . $nro_pedido . "' where campo = 'cod_pedido'");
        $conn->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_factura}' WHERE campo = 'cod_factura';");
        //Fin pedido
    }
    else
    {
        // cuando existe pedido pendiente
        $kardexoriginal=$conn->ObtenerFilasBySqlSelect("select id_transaccion from kardex_almacen where nro_factura='".$cargosoriginal[0]['cod_factura']."'");
        if($kardexoriginal==null)
        { 
            echo "Error Interno, el Kardex no se ha podido localizar contacte al administrador"; exit();
        }
        $sql="UPDATE
                `despacho_new`
            SET
                `subtotal` =  (subtotal + ". $subtotal . "),
                `descuentosItemFactura` = 0,
                `montoItemsFactura` = (montoItemsFactura + ". $subtotal . "),
                `ivaTotalFactura` = (ivaTotalFactura + ".$ivatotal."),
                `TotalTotalFactura` =(TotalTotalFactura + ".$totaltotal."),
                `cantidad_items` = (cantidad_items + ".$itemstotal."),
                `totalizar_sub_total` = (totalizar_sub_total + ". $subtotal . "),
                `totalizar_descuento_parcial` = totalizar_descuento_parcial,
                `totalizar_total_operacion` = (totalizar_total_operacion + ".$totaltotal.") ,
                `totalizar_pdescuento_global` = totalizar_pdescuento_global ,
                `totalizar_descuento_global` = totalizar_descuento_global,
                `totalizar_base_imponible` = (totalizar_base_imponible + ".$subtotal."),
                `totalizar_monto_iva` = (totalizar_monto_iva + ".$ivatotal."),
                `totalizar_total_general` = (totalizar_total_general + ".$totaltotal."),
                `totalizar_total_retencion` = totalizar_total_retencion
            WHERE
                id_factura='".$cargosoriginal[0]['id_factura'] ."'";
        $conn->ExecuteTrans($sql);
        for($i=0; $i<count($total); $i++)
        {
            if($total[$i]!=null)
            {
                $descripcion =  $nombreservicio[$i];
               
                $detalle_item_instruccion = "
                INSERT INTO despacho_new_detalle (
                `id_factura`, `id_item`,
                `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                `fecha_creacion`, `_item_almacen`
                )
                VALUES (
                '".$cargosoriginal[0]['id_factura'] ."', '{$idservicios[$i]}',
                '{$descripcion}', '1', '{$base[$i]}',
                0, 0, '{$iva[$i]}',
                '{$base[$i]}', '{$total[$i]}', '{$usuario}',
                CURRENT_TIMESTAMP, '1'
                );";
                $conn->ExecuteTrans($detalle_item_instruccion);
                
                $kardex_almacen_detalle_instruccion = "
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
                '" . $kardexoriginal[0]['id_transaccion'] . "',
                '{$almacen}',
                '',
                '" . $idservicios[$i] . "',
                '" . $base[$i] . "',
                1
                );";
                $conn->ExecuteTrans($kardex_almacen_detalle_instruccion);
            }
        }
    }
    
    
    
    if ($conn->errorTransaccion == 1)
    {    
        echo
        '
            <script language="JavaScript" type="text/JavaScript">
            alert("Disposiciones registradas con exito");
            </script>
            
        ';
        //echo "1";
    }
    elseif($conn->errorTransaccion == 0)
    {
        echo
        '
            <script language="JavaScript" type="text/JavaScript">
            alert("Error al registrar Disposiciones, consulte al administrador");
            </script>
            
        ';
        //echo "-1";
    }
    $conn->CommitTrans($conn->errorTransaccion);
    
}
?>
