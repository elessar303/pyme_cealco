<?php

include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");
include_once("../../libs/php/clases/correlativos.php");
$almacen = new Almacen();
$pos = POS;

$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());

$datos_almacen = $almacen->ObtenerFilasBySqlSelect("select * from almacen");
$valueSELECT = "";
$outputSELECT = "";
foreach ($datos_almacen as $key => $item) {
    $valueSELECT[] = $item["cod_almacen"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_almacen", $valueSELECT);
$smarty->assign("option_output_almacen", $outputSELECT);


$datos_almacen = $almacen->ObtenerFilasBySqlSelect("select * from clientes");
$valueSELECT = "";
$outputSELECT = "";
foreach ($datos_almacen as $key => $item) {
    $valueSELECT[] = $item["id_cliente"];
    $outputSELECT[] = $item["nombre"];
}
$smarty->assign("option_values_id_cliente", $valueSELECT);
$smarty->assign("option_output_nombre_cliente", $outputSELECT);


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


$punto = $cliente->ObtenerFilasBySqlSelect("SELECT * from roles_firma where descripcion_rol=5");
foreach ($punto as $key => $puntos) {
    $arraySelectOption2[] = $puntos["id_rol"];
    $arraySelectOutPut2[] = "C.I: ".$puntos["cedula_persona"]." - ".$puntos["nombre_persona"];
}

$smarty->assign("option_values_aprobado", $arraySelectOption2);
$smarty->assign("option_output_aprobado", $arraySelectOutPut2);

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT * from roles_firma where descripcion_rol=2");
foreach ($punto as $key => $puntos) {
    $arraySelectOption3[] = $puntos["id_rol"];
    $arraySelectOutPut3[] = "C.I: ".$puntos["cedula_persona"]." - ".$puntos["nombre_persona"];
}

$smarty->assign("option_values_receptor", $arraySelectOption3);
$smarty->assign("option_output_receptor", $arraySelectOutPut3);

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT * from roles_firma where descripcion_rol=4");
foreach ($punto as $key => $puntos) {
    $arraySelectOption4[] = $puntos["id_rol"];
    $arraySelectOutPut4[] = "C.I: ".$puntos["cedula_persona"]." - ".$puntos["nombre_persona"];
}

$smarty->assign("option_values_seguridad", $arraySelectOption4);
$smarty->assign("option_output_seguridad", $arraySelectOutPut4);


if (isset($_POST["input_cantidad_items"])) { // si el usuario iso post

    $sql="SELECT codigo_siga from parametros_generales limit 1";
    $codigosiga= $almacen->ObtenerFilasBySqlSelect($sql);
    $sucursal=$codigosiga[0]['codigo_siga'];
    
    for ($j = 0; $j < (int) $_POST["input_cantidad_items"]; $j++)
    {

        $cadena_item[$j]=$_POST["_id_item"][$j];
        $cadena_cantidad[$j]=$_POST["_cantidad"][$j];
        if($cadena_cantidad[$j]<0)
        {
            echo "Salida con Cantidad en Negativo, Verificar Datos";
            exit();
        }

    }

    $res = array_diff($cadena_item, array_diff(array_unique($cadena_item), array_diff_assoc($cadena_item, array_unique($cadena_item))));
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


    /*//Validamos si el conductor existe en la BD, de no existir lo insertamos
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

    $update_conductor=$almacen->ExecuteTrans("UPDATE conductores SET nombre_conductor='{$_POST["conductor"]}' WHERE cedula_conductor  = '{$_POST["nacionalidad_conductor"]}{$_POST["cedula_conductor"]}';");
    */

    $kardex_almacen_instruccion = "
        INSERT INTO kardex_almacen (
        `id_transaccion`, `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
        `fecha`, `usuario_creacion`, `fecha_creacion`,
        `estado`, `fecha_ejecucion`,  `almacen_destino`,`id_conductor`,`placa`,`color`,`marca`, prescintos, `id_seguridad`, `id_aprobado`, `id_despachador`, `id_cliente`)
        VALUES (
        NULL, '4', '" . $_POST["autorizado_por"] . "', '" . $_POST["observaciones"] . "',
        '" . $_POST["input_fechacompra"] . "', '" . $login->getUsuario() . "', CURRENT_TIMESTAMP,
        'Entregado', '" . $_POST["input_fechacompra"] . "', '" . $_POST["puntodeventa"] . "', '{$id_conductor[0]["id_conductor"]}','" . $_POST["placa"] . "','" . $_POST["color"] . "','" . $_POST["marca"] . "', '" . $_POST["prescintos"] . "','{$_POST["id_seguridad"]}','{$_POST["id_aprobado"]}','{$_POST["id_despachador"]}', '{$_POST["cliente"]}' );";

    $almacen->ExecuteTrans($kardex_almacen_instruccion);

    $id_transaccion = $almacen->getInsertID();
    
    $update_codmov="UPDATE kardex_almacen SET cod_movimiento='S-".$sucursal."-".$id_transaccion."' WHERE `id_transaccion`=".$id_transaccion."";

    $almacen->ExecuteTrans($update_codmov);

    for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) {
      
        //Se consulta el precio actual para dejar el historico en kardex (Junior)
            $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$_POST["_id_item"][$i]}'";
            $precio_actual=$almacen->ObtenerFilasBySqlSelect($sql);

            $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);

        $kardex_almacen_detalle_instruccion = "
        INSERT INTO kardex_almacen_detalle (
        `id_transaccion_detalle`, `id_transaccion`, `id_almacen_entrada`, `id_almacen_salida`, `id_item`, `cantidad`,`id_ubi_salida`, `precio`, lote)
        VALUES (
        NULL, '" . $id_transaccion . "', '', '" . $_POST["_id_almacen"][$i] . "', '" . $_POST["_id_item"][$i] . "', '" . $_POST["_cantidad"][$i] . "', '" . $_POST["_ubicacion"][$i] . "', ".$precioconiva.",  '" . $_POST["_nlote"][$i] . "');";

        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
         //REALIZAR LA DISMINUCION DEL POS EN ESTE BLOQUE EL SOLO REALIZA LA DEL PYME 
        //---------------------OJO-----------------
        //VERIFICAMOS SI LA SALIDAD ES EN EL POS
        //PROCESO DE AGREGAR EL CODIGO DE SEGURIDAD DEL KARDEX
        if($i==0 && $_POST['codigo_kardex']!=NULL)
        {
            $almacen->ExecuteTrans("INSERT INTO `codigo_kardex`( `codigo`, `id_movimiento`, `tipo_moviento`, `usuario`, `fecha`) VALUES ('".$_POST['codigo_kardex']."',".$id_transaccion.",'4','".$login->getUsuario()."', now());");
        }
    
        $pvender = $almacen->ObtenerFilasBySqlSelect("
                    select puede_vender from ubicacion
                    where id  = '".$_POST["_ubicacion"][$i]."'");
        //si se puede vender entonces debemos restar en piso de venta esa salida
     
        if($pvender[0]["puede_vender"]==1){
            if(POS) {
                //obtenemos el itempos
                $campoitempos = $almacen->ObtenerFilasBySqlSelect("
                           select itempos from item
                           where id_item  = '" . $_POST["_id_item"][$i] . "'");
                if(count($campoitempos)>0){
                   // echo $campoitempos[0]["itempos"]; exit();
                    $campopos = $almacen->ObtenerFilasBySqlSelect("
                           select * from $pos.stockcurrent
                           where product  = '" .$campoitempos[0]["itempos"]. "'");
                    if(count($campopos)>0){
                        $campomodpos= $almacen->ExecuteTrans("update $pos.stockcurrent set UNITS=UNITS-{$_POST["_cantidad"][$i]} where product='".$campoitempos[0]["itempos"]."'");
                        
                    }
                    
                }
            
            }
            
        }
       

        //-------------------------------- FIN OJO---------------------
        
        
        
        $campos = $almacen->ObtenerFilasBySqlSelect("
                    select * from item_existencia_almacen
                    where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $_POST["_id_almacen"][$i] . "' and id_ubicacion ='". $_POST["_ubicacion"][$i] . "'  and lote='" . $_POST["_nlote"][$i] . "'");
        if (count($campos) > 0) {
            
            $cantidadExistente = $campos[0]["cantidad"];
            
            $almacen->ExecuteTrans("update item_existencia_almacen set cantidad = '" . ($cantidadExistente - $_POST["_cantidad"][$i]) . "'
                                    where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $_POST["_id_almacen"][$i] . "' and id_ubicacion='". $_POST["_ubicacion"][$i] . "' and lote='" . $_POST["_nlote"][$i] . "'");

            //si cantidad es 0 o negativo debe borrarse de item existencia
            
            if($cantidadExistente - $_POST["_cantidad"][$i]<=0)
            {
                $almacen->ExecuteTrans("
                    delete from item_existencia_almacen where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $_POST["_id_almacen"][$i] . "' and id_ubicacion='". $_POST["_ubicacion"][$i] . "' and lote='" . $_POST["_nlote"][$i] . "'"
                );
                //se habilita la ubicacion
                $almacen->ExecuteTrans("
                        update ubicacion set ocupado=0 where id='". $_POST["_ubicacion"][$i] . "'
                    ");
            }
            //se comienza a cobrar
            //busco el id del movimiento cargo
            $sql="select id_tipo_movimiento_almacen from tipo_movimiento_almacen where descripcion= 'Descargo'";
            $id_movimiento=$almacen->ObtenerFilasBySqlSelect($sql);
            //primero se buscar el movimiento "descargo" y se agrega el costo de los servicios de dicho movimiento
            $iva[]="";
            $base[]="";
            $total[]="";
            $idservicios[]="";
            $codservicio[]="";
            $nombreservicio[]="";
            $contador=0;
            foreach ($id_movimiento as $key => $idmovimiento)
            {
                $sql="select id_movimiento_almacen, id_servicio from movimiento_almacen_servicio where id_movimiento_almacen = ".$idmovimiento['id_tipo_movimiento_almacen'];
                $buscarservicios=$almacen->ObtenerFilasBySqlSelect($sql);
                foreach($buscarservicios as $key2 => $servicios)
                {
                    $sql="select id_item, cod_item, precio1, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
                    //echo $sql; exit();
                    $contarservicio=$almacen->ObtenerFilasBySqlSelect($sql);
                    $iva[$contador] = $contarservicio[0]['iva'];
                    $base[$contador] = $contarservicio[0]['precio1'];
                    $total[$contador] = $contarservicio[0]['precio1']+(($contarservicio[0]['precio1']*$contarservicio[0]['iva']) / 100);
                    $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                    $idservicios[$contador]= $contarservicio[0]['id_item'];
                    $codservicio[$contador]= $contarservicio[0]['cod_item'];
                    $contador++;
                }
                //echo $sql; exit();
            }
            //***********************PEDIDO
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
                '{$_POST["cliente"]}', '{$nro_factura}', '{$login->getUsuario()}', now(),
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
                        '{$_POST["cliente"]}',
                        '{$nro_factura}'
                        );";
        
            $almacen->ExecuteTrans($kardex_almacen_instruccion);
            $id_transaccion = $almacen->getInsertID();
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
                    '{$id_facturaTrans}', '{$codservicio[$i]}',
                    '{$descripcion}', '1', '{$base[$i]}',
                    0, 0, '{$iva[$i]}',
                    '{$base[$i]}', '{$total[$i]}', '{$usuario}',
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
                    '{$_POST["_ubicacion"][$i]}',
                    '" . $idservicios[$i] . "',
                    '" . $base[$i] . "',
                    1
                    );";
                    $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
                }
            }
            
                $nro_facturaOLD = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "no");
                $nro_pedido = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "no");
                $almacen->ExecuteTrans("update correlativos set contador = '" . $nro_pedido . "' where campo = 'cod_pedido'");
                $almacen->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_factura}' WHERE campo = 'cod_factura';");
            if ($almacen->errorTransaccion == 1)
            {    
                echo "1";
            }
            elseif($almacen->errorTransaccion == 0)
            {
                echo "-1";
            }
            $almacen->CommitTrans($almacen->errorTransaccion);
            //Fin ********************+PEDIDO

        
        } else {
            $instruccion = "
		INSERT INTO item_existencia_almacen(
		`cod_almacen`, `id_item`, `cantidad`,`id_ubicacion`, lote)
		VALUES (
                    '" . $_POST["_id_almacen"][$i] . "', '" . $_POST["_id_item"][$i] . "', '" . $_POST["_cantidad"][$i] . "', '" . $_POST["_ubicacion"][$i] . "', '" . $_POST["_nlote"][$i] . "');";
            $almacen->ExecuteTrans($instruccion);
        }
    } // Fin del For

    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
    exit;
}
?>
