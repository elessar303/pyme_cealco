<?php

include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");
include("../../libs/php/clases/producto.php");
require_once("../../libs/php/clases/ConexionComun.php");
include_once("../../libs/php/clases/correlativos.php");
$almacen = new Almacen();
$login = new Login();
$comun = new Comunes();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());


//servicios asociados al cargo
$sql="select id_tipo_movimiento_almacen from tipo_movimiento_almacen where descripcion='Traslado'";
$id_movimiento=$almacen->ObtenerFilasBySqlSelect($sql);
$checkbox="";
foreach($id_movimiento as $id_movimientos)
{
    $sql="select id_movimiento_almacen, id_servicio from movimiento_almacen_servicio where id_movimiento_almacen = ".$id_movimientos['id_tipo_movimiento_almacen'];
    $buscarservicios=$almacen->ObtenerFilasBySqlSelect($sql);
    foreach ($buscarservicios as $servicios) 
    {
        $sql="select id_item, cod_item, precio1, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
        $contarservicio=$almacen->ObtenerFilasBySqlSelect($sql);
        $checkbox[]=['id' => $contarservicio[0]['id_item'], 'nombre' => $contarservicio[0]['descripcion1'],];
    }
}
$smarty->assign("checkbox", $checkbox);
//fin
$arraySelectOption = "";
$arraySelectoutPut = "";


$campos_comunes= $almacen->ObtenerFilasBySqlSelect("SELECT * FROM clientes inner join item_existencia_almacen as b on clientes.id_cliente=b.id_proveedor group by clientes.nombre ORDER BY clientes.nombre");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id_cliente"];
    $nombre_proveedor=$item["cod_cliente"]."-".$item["nombre"];
    $arraySelectoutPut[] = utf8_encode($nombre_proveedor);
}
$smarty->assign("option_values_proveedor", $arraySelectOption);
$smarty->assign("option_output_proveedor", $arraySelectoutPut);

if (isset($_POST["input_cantidad_items"]))
{ // si el usuario hizo post
    
    for ($j = 0; $j < (int) $_POST["input_cantidad_items"]; $j++)
    {
        $cadena_item[$j]=$_POST["_id_item"][$j];
        $cadena_cantidad[$j]=$_POST["_cantidad"][$j];
        if($cadena_cantidad[$j]<0)
        {
            echo "Traslado con Cantidad en Negativo, Verificar Datos";
            exit();
        }
    }

    /*$res = array_diff($cadena_item, array_diff(array_unique($cadena_item), array_diff_assoc($cadena_item, array_unique($cadena_item))));
    if($res[0]!='')
    {
       foreach(array_unique($res) as $v) 
       {
          $sql="SELECT * FROM item WHERE id_item=".$v."";
          $item = $almacen->ObtenerFilasBySqlSelect($sql);
           echo "Producto Duplicado Durante la Operacion: ".$v." ".$item[0]['descripcion1']."<br/>";
       }
       exit();  
    }
    */

    $almacen->BeginTrans();
    $pos=POS;
    $kardex_almacen_instruccion = "
    INSERT INTO kardex_almacen (
    `id_transaccion` ,
    `tipo_movimiento_almacen` ,
    `autorizado_por` ,
    `observacion` ,
    `fecha` ,
    `usuario_creacion`,
    `fecha_creacion`,
    `id_proveedor`,
    `id_cliente`
    )
    VALUES (
    NULL ,
    '5',
    '" . $_POST["autorizado_por"] . "',
    '" . $_POST["observaciones"] . "',
    '" . $_POST["input_fechacompra"] . "',
    '" . $login->getUsuario() . "',
    CURRENT_TIMESTAMP,
    '" . $_POST["id_proveedor"] . "',
    '" . $_POST["id_proveedor"] . "'
    );";

    $almacen->ExecuteTrans($kardex_almacen_instruccion);

    $id_transaccion = $almacen->getInsertID();
    $correlativos = new Correlativos();
    for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++)
    {
        //busco primero el costo, este costo sale da la ubicacion de la salida.//
        $costo=NULL;
        $sql="select a.costo_declarado from kardex_almacen_detalle as a inner join kardex_almacen as c on a.id_transaccion=c.id_transaccion, item_existencia_almacen as b where
              a.id_ubi_entrada=b.id_ubicacion
              and a.id_item='" . $_POST["_id_item"][$i] . "'
              and c.id_cliente=b.id_proveedor
              and b.id_item='" . $_POST["_id_item"][$i] . "'
              and b.id_ubicacion  = '" . $_POST["_ubicacion_salida"][$i] . "' 
              and b.cod_almacen = '" . $_POST["_id_almacen_salida"][$i] . "' 
              and b.lote='" . $_POST["_nlote"][$i] . "' 
              and b.id_marca='" . $_POST["_marca"][$i] . "' 
              and b.id_proveedor='{$_POST["id_proveedor"]}' order by a.id_transaccion_detalle desc limit 1";
              
        $costo=$almacen->ObtenerFilasBySqlSelect($sql);
        $sql="SELECT coniva1 FROM item WHERE id_item  = '{$_POST["_id_item"][$i]}'";
        $precio_actual=$almacen->ObtenerFilasBySqlSelect($sql);

        $kardex_almacen_detalle_instruccion = "
        INSERT INTO kardex_almacen_detalle (
        `id_transaccion_detalle` ,
        `id_transaccion` ,
        `id_almacen_entrada` ,
        `id_almacen_salida` ,
        `id_item` ,
        `cantidad`,
        `peso`,
         `id_ubi_entrada` ,
        `id_ubi_salida`, 
        `precio`,
        `lote`,
        `id_marca`,
        `costo_declarado`
        )
        VALUES (
        NULL ,
        '" . $id_transaccion . "',
        '" . $_POST["_id_almacen_entrada"][$i] . "',
        '" . $_POST["_id_almacen_salida"][$i] . "',
        '" . $_POST["_id_item"][$i] . "',
        '" . $_POST["_cantidad"][$i] . "',
        '" . $_POST["_peso"][$i] . "',
        '" . $_POST["_id_ubicacion_entrada"][$i]. "',
        '" . $_POST["_id_ubicacion_salida"][$i]. "',
        ".$precio_actual[0]['coniva1'].",
        '" . $_POST["_nlote"][$i]. "',
        '" . $_POST["_marca"][$i]. "',
        '" . $costo[0]['costo_declarado']. "'
        );";

        //echo $kardex_almacen_detalle_instruccion; exit();

        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

        //Registro de salidas de almacen.
        $campos = $almacen->ObtenerFilasBySqlSelect("
                        select * from item_existencia_almacen
                        where
                        id_item  = '" . $_POST["_id_item"][$i] . "' 
                        and id_ubicacion  = '" . $_POST["_id_ubicacion_salida"][$i] . "' 
                        and cod_almacen = '" . $_POST["_id_almacen_salida"][$i] . "' 
                        and lote='" . $_POST["_nlote"][$i] . "' 
                        and id_marca='" . $_POST["_marca"][$i] . "' 
                        and id_proveedor='{$_POST["id_proveedor"]}'");
        if (count($campos) > 0) 
        {
            $cantidadExistente = $campos[0]["cantidad"];
            $pesoExistente = $campos[0]["peso"];
            $almacen->ExecuteTrans("update item_existencia_almacen set 
                cantidad = '" . ($cantidadExistente - $_POST["_cantidad"][$i]) . "',
                peso = '" . ($pesoExistente - $_POST["_peso"][$i]) . "'
                where 
                id_item  = '" . $_POST["_id_item"][$i] . "' 
                and cod_almacen = '" . $_POST["_id_almacen_salida"][$i] . "' 
                and id_ubicacion='". $_POST["_id_ubicacion_salida"][$i]."' 
                and lote='" . $_POST["_nlote"][$i] . "'
                and id_marca='" . $_POST["_marca"][$i] . "'
                and id_proveedor='{$_POST["id_proveedor"]}'");
        }
        //si cantidad es 0 o negativo debe borrarse de item existencia
        if($cantidadExistente - $_POST["_cantidad"][$i]<=0)
        {
            $almacen->ExecuteTrans("
                delete from 
                item_existencia_almacen where 
                id_item  = '" . $_POST["_id_item"][$i] . "' 
                and cod_almacen = '" . $_POST["_id_almacen_salida"][$i] . "' 
                and id_ubicacion='". $_POST["_id_ubicacion_salida"][$i] . "' 
                and lote='" . $_POST["_nlote"][$i] . "'
                and id_marca='" . $_POST["_marca"][$i] . "' 
                and id_proveedor='{$_POST["id_proveedor"]}'"
            );
            //se habilita la ubicacion
            $almacen->ExecuteTrans("
                    update ubicacion set ocupado=0 where id='". $_POST["_ubicacion"][$i] . "'
                ");
        }
        //Entrada de Items en almacen seleccionado...
        $campos = $almacen->ObtenerFilasBySqlSelect("
                        select * from item_existencia_almacen
                        where
                        id_item  = '" . $_POST["_id_item"][$i] . "' 
                        and cod_almacen = '" . $_POST["_id_almacen_entrada"][$i]. "' 
                        and id_ubicacion = '" . $_POST["_id_ubicacion_entrada"][$i]. "' 
                        and lote='" . $_POST["_nlote"][$i] . "'
                        and id_marca='" . $_POST["_marca"][$i] . "' 
                        and id_proveedor='{$_POST["id_proveedor"]}'");

        
        if (count($campos) > 0) 
        {
            $cantidadExistente = $campos[0]["cantidad"];
            $pesoExistente = $campos[0]["peso"];
            $almacen->ExecuteTrans("update item_existencia_almacen set 
                cantidad = '" . ($cantidadExistente + $_POST["_cantidad"][$i]) . "',
                peso = '" . ($pesoExistente - $_POST["_peso"][$i]) . "'
                where 
                id_item  = '" . $_POST["_id_item"][$i] . "' 
                and cod_almacen = '" . $_POST["_id_almacen_entrada"][$i] . "' 
                and id_ubicacion = '" . $_POST["_id_ubicacion_entrada"][$i]. "' 
                and lote='" . $_POST["_nlote"][$i] . "' 
                and id_marca='" . $_POST["_marca"][$i] . "' 
                and id_proveedor='{$_POST["id_proveedor"]}'");
        } else {
            $instruccion = "
                    INSERT INTO item_existencia_almacen(
                    `cod_almacen` ,
                    `id_item` ,
                    `cantidad`,
                    `peso`,
                    `id_ubicacion`,
                    `lote`,
                    `id_marca`,
                    `id_proveedor`
                    )
                    VALUES (
                        '" . $_POST["_id_almacen_entrada"][$i] . "',
                        '" . $_POST["_id_item"][$i] . "',
                        '" . $_POST["_cantidad"][$i] . "',
                        '" . $_POST["_peso"][$i] . "',
                        '" .$_POST["_id_ubicacion_entrada"][$i]. "',
                        '" . $_POST["_nlote"][$i] . "',
                        '" . $_POST["_marca"][$i] . "',
                        '" . $_POST["id_proveedor"] . "'
                    );";
            $almacen->ExecuteTrans($instruccion);
        }
        //se habilita la ubicacion
        $almacen->ExecuteTrans(
        "
            update ubicacion set ocupado=1 where id='". $_POST["_id_ubicacion_entrada"][$i]. "'
        ");

        //se comienza a cobrar
        //tipo cliente
        $sql="select cod_tipo_cliente from clientes where id_cliente={$_POST["id_proveedor"]}";
        $clienteseguro=0;
        $tipocliente=$almacen->ObtenerFilasBySqlSelect($sql);
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
        //busco el id del movimiento cargo
        $sql="select id_tipo_movimiento_almacen from tipo_movimiento_almacen where descripcion='Traslado'";
        $id_movimiento=$almacen->ObtenerFilasBySqlSelect($sql);
        //primero se buscar el movimiento "Traslado" y se agrega el costo de los servicios de dicho movimiento
        $iva[]="";
        $base[]="";
        $total[]="";
        $idservicios[]="";
        $codservicio[]="";
        $nombreservicio[]="";
        $contador=0;
        $cobrar="";
        $cajas=$_POST['cajas'];
        foreach($cajas as $key => $valueser)
        {
            $cobrar.="'".$valueser."', ";
        }
        $cobrar=substr($cobrar, 0, -2);
        foreach ($id_movimiento as $key => $idmovimiento)
        {
            $sql="select id_movimiento_almacen, id_servicio from movimiento_almacen_servicio where id_movimiento_almacen = ".$idmovimiento['id_tipo_movimiento_almacen']." and id_servicio in (".$cobrar.")";
            $buscarservicios=$almacen->ObtenerFilasBySqlSelect($sql);
            foreach($buscarservicios as $key2 => $servicios)
            {
                $sql="select id_item, cod_item, precio1, precio2, precio3, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
                $contarservicio=$almacen->ObtenerFilasBySqlSelect($sql);
                $iva[$contador] = $contarservicio[0]['iva'];
                $base[$contador] = $contarservicio[0][$precio];
                $total[$contador] = $contarservicio[0][$precio]+(($contarservicio[0][$precio]*$contarservicio[0]['iva']) / 100);
                $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                $idservicios[$contador]= $contarservicio[0]['id_item'];
                $codservicio[$contador]= $contarservicio[0]['cod_item'];
                $contador++;
            }
            //echo $sql; exit();
        }
        /*************************************************/
        /**********COBRANDO SEGURO ***************/
        /*
        //busco primero el costo, este costo sale da la ubicacion de la salida.//
        $sql="select a.costo_declarado from kardex_almacen_detalle as a inner join kardex_almacen as c on a.id_transaccion=c.id_transaccion, item_existencia_almacen as b where
              a.id_ubi_entrada=b.id_ubicacion
              and a.id_item='" . $_POST["_id_item"][$i] . "'
              and c.id_cliente=b.id_proveedor
              and b.id_item='" . $_POST["_id_item"][$i] . "'
              and b.id_ubicacion  = '" . $_POST["_ubicacion"][$i] . "' 
              and b.cod_almacen = '" . $_POST["_id_almacen"][$i] . "' 
              and b.lote='" . $_POST["_nlote"][$i] . "' 
              and b.id_marca='" . $_POST["_marca"][$i] . "' 
              and b.id_proveedor='{$_POST["id_proveedor"]}' order by a.id_transaccion_detalle desc limit 1";
        $costo=$almacen->ObtenerFilasBySqlSelect($sql);
        
        if(isset($costo[0]['costo_declarado']))
        {
            $sql="select id_item, cod_item, iva, descripcion1 from item where precio1=-1";
            $contarservicio=$almacen->ObtenerFilasBySqlSelect($sql);
            if($clienteseguro==0)
            {
                if($contarservicio!=null)
                {
                    $iva[$contador] = $contarservicio[0]['iva'];
                    $base[$contador] = (($_POST["_peso"][0]*$costo[0]['costo_declarado']) * 0.005); //$contarservicio[0]['precio1'];
                    $total[$contador] =( (($_POST["_peso"][0]*$costo[0]['costo_declarado']) * 0.005) + (((($_POST["_peso"][0]*$costo[0]['costo_declarado']) * 0.005)*$contarservicio[0]['iva']) / 100) ) ;
                    $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                    $idservicios[$contador]= $contarservicio[0]['id_item'];
                    $codservicio[$contador]= $contarservicio[0]['cod_item'];
                    $contador++;
                }
            }
            else
            {
                if($contarservicio!=null)
                {
                    $iva[$contador] = $contarservicio[0]['iva'];
                    $base[$contador] = (($_POST["_peso"][0]*$datospadre[0]['costo_declarado']) * 0.0075); //$contarservicio[0]['precio1'];
                    $total[$contador] =( (($_POST["_peso"][0]*$datospadre[0]['costo_declarado']) * 0.0075) + (((($_POST["_peso"][0]*$datospadre[0]['costo_declarado']) * 0.0075)*$contarservicio[0]['iva']) / 100) ) ;
                    $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                    $idservicios[$contador]= $contarservicio[0]['id_item'];
                    $codservicio[$contador]= $contarservicio[0]['cod_item'];
                    $contador++;
                }
            }
        }*/
        /**************************************************/
        //fin del cobro de movimiento, ahora se cobra la ubicacion 
        $sql="select id_servicio from ubicacion_servicio where id_ubicacion in ('".$_POST['_id_ubicacion_entrada'][$i]."')";
        $buscarserviciosubicacion=$almacen->ObtenerFilasBySqlSelect($sql);
        foreach($buscarserviciosubicacion as $key => $servicios)
        {
            $sql="select id_item, cod_item, precio1, precio2, precio3, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
            $contarservicio=$almacen->ObtenerFilasBySqlSelect($sql);
            $iva[$contador] = $contarservicio[0]['iva'];
            $base[$contador] = $contarservicio[0][$precio];
            $total[$contador] = $contarservicio[0][$precio]+(($contarservicio[0][$precio]*$contarservicio[0]['iva']) / 100);
            $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
            $idservicios[$contador]= $contarservicio[0]['id_item'];
            $codservicio[$contador]= $contarservicio[0]['cod_item'];
            $contador++;
        }
        //***********************PEDIDO
        $nro_pedido = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "si");
        $formateo_nro_factura = $nro_pedido;
        $subtotal=0;
        $ivatotal=0;
        $itemstotal=count($total);
        $totaltotal=0;
        //se guarda los totales del resultado de los arreglos
        for($ii=0; $ii<count($total); $ii++)
        {
            $subtotal+=$base[$ii];
            $ivatotal+=(($base[$ii]*$iva[$ii]) / 100);
            $totaltotal+=$total[$ii];
        }
        $_POST['cliente']=$_POST["id_proveedor"];
        // debemos ver si hay pedido pendiente, para eso verificamos la fecha de pago
        $sql="select * from despacho_new where fecha_pago='0000-00-00' and id_cliente='{$_POST['cliente']}' limit 1";
        $cargosoriginal=$almacen->ObtenerFilasBySqlSelect($sql);
        if($cargosoriginal==null)
        { 
            $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 1, "si");
            $formateo_nro_factura = $nro_factura;
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
                '{$_POST["id_proveedor"]}', '{$nro_factura}', '{$login->getUsuario()}', now(),
                ". $subtotal . ", 0 ," . $subtotal . ","
                    . $ivatotal . "," . $totaltotal . "," . $itemstotal . ","
                    . $subtotal . ", 0," . $totaltotal . ", 0 ,  
                    0," . $subtotal . ","
                    . $ivatotal . ", " . $totaltotal . ",0 ,CURRENT_TIMESTAMP,'" . $login->getUsuario() . "',
                    '" . $cod_estatus = 1 . "', 'contado', '".impresora_serial."' , '".$money[0]['money']."',''
                );";
            $almacen->ExecuteTrans($sql);
            $id_facturaTrans = $almacen->getInsertID();
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
                        '{$_POST["id_proveedor"]}',
                        '{$nro_factura}'
                        );";
        
            $almacen->ExecuteTrans($kardex_almacen_instruccion);
            $id_transaccion = $almacen->getInsertID();
            for($ii=0; $ii<count($total); $ii++)
            {
                if($total[$ii]!=null)
                {
                    $descripcion =  $nombreservicio[$ii];
                    $detalle_item_instruccion = "
                    INSERT INTO despacho_new_detalle (
                    `id_factura`, `id_item`,
                    `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                    `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                    `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                    `fecha_creacion`, `_item_almacen`
                    )
                    VALUES (
                    '{$id_facturaTrans}', '{$idservicios[$ii]}',
                    '{$descripcion}', '1', '{$base[$ii]}',
                    0, 0, '{$iva[$ii]}',
                    '{$base[$ii]}', '{$total[$ii]}', '{$login->getUsuario()}',
                    CURRENT_TIMESTAMP, '1'
                    );";
                    $almacen->ExecuteTrans($detalle_item_instruccion);
                    
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
                    '',
                    '{$_POST["_ubicacion"][$ii]}',
                    '" . $idservicios[$ii] . "',
                    '" . $base[$ii] . "',
                    1
                    );";
                    $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
                }
            }
            
            $nro_facturaOLD = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "no");
            $nro_pedido = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "no");
            $almacen->ExecuteTrans("update correlativos set contador = '" . $nro_pedido . "' where campo = 'cod_pedido'");
            $almacen->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_factura}' WHERE campo = 'cod_factura';");
        }
        else
        {
             // cuando existe pedido pendiente
            $kardexoriginal=$almacen->ObtenerFilasBySqlSelect("select id_transaccion from kardex_almacen where nro_factura='".$cargosoriginal[0]['cod_factura']."'");
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
                $almacen->ExecuteTrans($sql);

                 for($ii=0; $ii<count($total); $ii++)
                {
                    if($total[$ii]!=null)
                    {
                        $descripcion =  $nombreservicio[$ii];
                        $detalle_item_instruccion = "
                        INSERT INTO despacho_new_detalle (
                        `id_factura`, `id_item`,
                        `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                        `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                        `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                        `fecha_creacion`, `_item_almacen`
                        )
                        VALUES (
                        '".$cargosoriginal[0]['id_factura'] ."', '{$idservicios[$ii]}',
                        '{$descripcion}', '1', '{$base[$ii]}',
                        0, 0, '{$iva[$ii]}',
                        '{$base[$ii]}', '{$total[$ii]}', '{$usuario}',
                        CURRENT_TIMESTAMP, '1'
                        );";
                        $almacen->ExecuteTrans($detalle_item_instruccion);
                        
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
                        '',
                        '{$_POST["_ubicacion"][$ii]}',
                        '" . $idservicios[$ii] . "',
                        '" . $base[$ii] . "',
                        1
                        );";
                        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
                    }
                }
        }

    }// Fin del For
    if ($almacen->errorTransaccion == 1)
    {    
        //echo "paso";   
        Msg::setMessage("<span style=\"color:#62875f;\">Traslado Generado Exitosamente </span>");
    }
    elseif($almacen->errorTransaccion == 0)
    {
        //echo "no paso";
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de cargar el traslado, por favor intente nuevamente.</span>");
    }
    $almacen->CommitTrans($almacen->errorTransaccion);

    //sexit;
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}

$datos_almacen = $almacen->ObtenerFilasBySqlSelect("select * from almacen");
$valueSELECT = "";
$outputSELECT = "";
foreach ($datos_almacen as $key => $item) {
    $valueSELECT[] = $item["cod_almacen"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_almacen", $valueSELECT);
$smarty->assign("option_output_almacen", $outputSELECT);
?>
