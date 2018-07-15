<?php
session_start();
ini_set("display_errors", 1);

require_once("../../../libs/php/adodb5/adodb.inc.php");
require_once("../../../libs/php/configuracion/config.php");
require_once("../../../libs/php/clases/ConexionComun.php");
require_once("../../../libs/php/clases/comunes.php");
require_once("../../../libs/php/clases/login.php");
include ("../../../libs/php/clases/tfhka/TfhkaPHP.php");
//include_once("../../../libs/php/clases/compra.php");
include_once("../../../libs/php/clases/correlativos.php");
require_once "../../../libs/php/clases/numerosALetras.class.php";
include("../../../../menu_sistemas/lib/common.php");
include("../../../../generalp.config.inc.php");


if (isset($_GET["opt"]) == true || isset($_POST["opt"]) == true) {
    $conn = new ConexionComun();
    $login = new Login();
    $opt = (isset($_GET["opt"])) ? $_GET["opt"] : $_POST["opt"];

    switch ($opt) {

        case "getPredespacho":
            $sql="SELECT * FROM calidad_almacen WHERE id_transaccion=".$_GET['predespacho']."";
            $campos=$conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
        break;

        case "getPredespachoDetalle":

        $sql="SELECT cod_acta_calidad FROM calidad_almacen WHERE id_transaccion=".$_GET['predespacho']."";
        $campos_cabecera=$conn->ObtenerFilasBySqlSelect($sql);


        $sql="SELECT a.cantidad, b.codigo_barras, b.descripcion1 FROM calidad_almacen_detalle a 
        LEFT JOIN item b ON a.id_item=b.id_item
        WHERE id_transaccion=".$_GET['predespacho']."";
        $campos=$conn->ObtenerFilasBySqlSelect($sql);
        echo '
        <table>
        <thead>
        <tr><th style="font-weight: bold; text-align: center;" colspan="3">Datos del Predespacho: '.$campos_cabecera[0]["cod_acta_calidad"].'</th></tr>
        <tr>
        <th style="width:200px; font-weight: bold; text-align: center;">Item</th>
        <th style="width:200px; font-weight: bold; text-align: center;">Codigo de Barras </th>
        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
        </tr>
        </thead>
        <tbody>
        ';
        $total=0;
        foreach ($campos as $key => $item) {
            echo '
            <tr>
            <td style="width:200px; text-align: center;">' . $item["descripcion1"] . '</td>
            <td style="width:200px; text-align: center;">' . $item["codigo_barras"] . '</td>
            <td style="width:150px; text-align: center;">' . $item["cantidad"] . '</td>
            </tr>';
            $total=$total+$item["cantidad"];
        }

        echo '
        <tr>
        <td colspan="2" style="width:200px; text-align: right;"> <b>Total:</b> </td>
        <td style="width:110px; text-align: center;">' . number_format($total,2,'.', ''). '</td>
        </tbody></table>';
        break;

        case "EntradaTunelNuevaAlmacen":
            //buscamos los datos básicos en calidad y a su vez nos aseguramos  que no se ha registrado en kardex almacen
            $sql=
            "
                Select 
                a.id_transaccion, a.tipo_movimiento_almacen, a.autorizado_por, a.observacion, a.fecha, a.usuario_creacion,
                a.fecha_creacion, a.estado, a.fecha_ejecucion, a.id_documento, a.empresa_transporte, a.id_conductor,
                a.placa, a.guia_sunagro, a.prescintos, a.orden_despacho, a.almacen_procedencia, a.id_proveedor, a.id_seguridad, a.id_aprobado,
                a.id_receptor, a.nro_contenedor, b.elaboracion, b.id_item, b.vencimiento, b.lote, b.c_esperada, b.observacion, b.costo_declarado, b.id_marca, b.id_presentacion
                from calidad_almacen as a 
                inner join calidad_almacen_detalle as b on a.id_transaccion=b.id_transaccion
                where b.id_transaccion_detalle=".$_POST['id_movimiento']." 
                AND  a.id_transaccion not in (select id_transaccion_calidad from kardex_almacen where tipo_movimiento_almacen=3)
            ";
            $datospadre=$conn->ObtenerFilasBySqlSelect($sql);
            
            if($datospadre==null)
            {
                //si es null es por que ya existe
                $sql=
                "
                    select 
                    a.id_transaccion, a.tipo_movimiento_almacen, a.autorizado_por, a.observacion, a.fecha, a.usuario_creacion,
                    a.fecha_creacion, a.estado, a.fecha_ejecucion, a.id_documento, a.empresa_transporte, a.id_conductor,
                    a.placa, a.guia_sunagro, a.prescintos, a.orden_despacho, a.almacen_procedencia, a.id_proveedor, a.id_seguridad, a.id_aprobado,
                    a.id_receptor, a.nro_contenedor, b.elaboracion, b.id_item, b.vencimiento, b.lote, b.c_esperada, b.observacion, b.costo_declarado,  b.id_marca, b.id_presentacion
                    from kardex_almacen as a
                    inner join calidad_almacen_detalle as b on a.id_transaccion_calidad=b.id_transaccion
                    where b.id_transaccion_detalle=".$_POST['id_movimiento']." and a.tipo_movimiento_almacen=3";
                $maestro=$conn->ObtenerFilasBySqlSelect($sql);
                if($maestro==null)
                {
                    //error
                    echo "-1"; exit();
                }
                else
                {
                    $datospadre=$maestro;
                    $conn->BeginTrans();
                    //Se consulta el precio actual para dejar el historico en kardex (Junior)
                    $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$datospadre[0]['id_item']}'";
                    $precio_actual=$conn->ObtenerFilasBySqlSelect($sql);
                    $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);
                    //correlativo de ticket
                    $sql="Select contador from correlativos where campo='id_ticket'";
                    $idticket=$conn->ObtenerFilasBySqlSelect($sql);
                    //sql kardex_detalle
                    $kardex_almacen_detalle_instruccion = 
                    "
                        INSERT INTO kardex_almacen_detalle (
                        `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                        `id_almacen_salida`, `id_item`, `cantidad`, `peso`, `peso_bruto`, `peso_estiva`, `peso_empaque`, `id_ubi_entrada`, `vencimiento`,`elaboracion`,`lote`, `c_esperada`,`observacion`, `precio`, `etiqueta`, `costo_declarado`, `id_marca`, `id_presentacion`, `observacion_limite`)
                        VALUES (
                        NULL, '{$datospadre[0]['id_transaccion']}', '{$_POST["ubicacion_principal"]}',
                        '', '{$datospadre[0]['id_item']}', '{$_POST["cantidad"]}', '{$_POST["peso"]}', '{$_POST["peso_bruto"]}', '{$_POST["peso_estiva"]}', '{$_POST["peso_empaque"]}', '{$_POST["ubicacion_detalle"]}','{$datospadre[0]['vencimiento']}',
                        '{$datospadre[0]['elaboracion']}','{$datospadre[0]['lote']}','{$datospadre[0]['c_esperada']}','{$datospadre[0]['observacion']}', ".$precioconiva.", ".$idticket[0]['contador'] .", ".$datospadre[0]['costo_declarado'] .", ".$datospadre[0]['id_marca'] .", ".$datospadre[0]['id_presentacion'] .", '{$_POST["observacion_limite"]}');
                    ";
                    $conn->ExecuteTrans($kardex_almacen_detalle_instruccion);
                    //actualizo correlativo
                    $sql="update correlativos set contador=(contador+1) where campo='id_ticket'";
                    $conn->ExecuteTrans($sql);
                    //bloqueando las ubicaciones
                    $sql="select ocupado from ubicacion where id=".$_POST['ubicacion_detalle'];
                    $ocupado=$conn->ObtenerFilasBySqlSelect($sql);
                    if($ocupado[0]['ocupado']==0)
                    {
                        $sql=
                        "
                            update ubicacion set ocupado=1 where id=".$_POST['ubicacion_detalle'];
                        $conn->ExecuteTrans($sql);
                    }
                    else
                    {
                        echo "-1"; exit();
                    }
        
                    $sql=
                    "
                        SELECT * FROM item_existencia_almacen WHERE
                        id_item  = '{$datospadre[0]['id_item']}' 
                        AND id_ubicacion = '{$_POST['ubicacion_detalle']}'
                        AND lote='{$datospadre[0]['lote']}' 
                        AND id_marca='{$datospadre[0]['id_marca']}' 
                        AND id_proveedor='{$datospadre[0]['id_proveedor']}';
                    ";
                    $campos = $conn->ObtenerFilasBySqlSelect($sql);
        
                    if (count($campos) > 0) 
                    {
                        $cantidadExistente = $campos[0]["cantidad"];
                        $pesoExistente = $campos[0]["peso"];
                        $conn->ExecuteTrans(
                        "
                            UPDATE item_existencia_almacen 
                            SET cantidad = '" . ($cantidadExistente + $POST["cantidad"]) . "', peso = '" . ($pesoExistente + $POST["peso"]) . "'
                            WHERE id_item  = '{$datospadre[0]['id_item']}' 
                            AND id_ubicacion = '{$_POST['ubicacion_detalle']}' 
                            AND lote='{$datospadre[0]['lote']}' 
                            AND id_marca='{$datospadre[0]['id_marca']}' 
                            AND id_proveedor='{$datospadre[0]['id_proveedor']}';
                        ");
                    } 
                    else 
                    {
                        $instruccion = 
                        "
                            INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`, `peso`, `id_ubicacion`, `lote`, `id_proveedor`, `id_marca`)
                            VALUES 
                            ('{$_POST['ubicacion_principal']}',
                            '{$datospadre[0]['id_item']}', '{$_POST['cantidad']}' , '{$_POST['peso']}',  '{$_POST['ubicacion_detalle']}',
                            '{$datospadre[0]['lote']}', '{$datospadre[0]['id_proveedor']}', '{$datospadre[0]['id_marca']}');
                        ";
                        $conn->ExecuteTrans($instruccion);
                    }    
                }//fin del else
            }
            else
            {
                //se comienza a realizar el insert maestro(kardex_almacen=calidad_almacen)
                $conn->BeginTrans();
                $kardex_almacen_instruccion = 
                "
                    INSERT INTO kardex_almacen (
                    `id_transaccion_calidad` , `tipo_movimiento_almacen`, `autorizado_por`,
                    `observacion`, `fecha`, `usuario_creacion`,
                    `fecha_creacion`, `estado`, `fecha_ejecucion`, `id_documento`, `empresa_transporte`, `id_conductor`, `placa`, 
                    `guia_sunagro`, `prescintos`, `orden_despacho`, `almacen_procedencia`, `id_proveedor`,  `id_seguridad`, `id_aprobado`,
                    `id_receptor`, `nro_contenedor`, `ticket_entrada`, `id_cliente`)
                    VALUES (
                    {$datospadre[0]['id_transaccion']} , '13', '{$datospadre[0]['autorizado_por']}',
                    '{$datospadre[0]['observacion']}', '{$datospadre[0]['fecha']}', '{$login->getUsuario()}', 
                    CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP, '{$datospadre[0]['id_documento']}', '{$datospadre[0]['empresa_transporte']}',
                    '{$datospadre[0]['id_conductor']}', '{$datospadre[0]['placa']}', '{$datospadre[0]['guia_sunagro']}', '{$datospadre[0]['prescintos']}',
                    '{$datospadre[0]['orden_despacho']}',
                    '{$datospadre[0]['almacen_procedencia']}', '{$datospadre[0]['id_proveedor']}','{$datospadre[0]['id_seguridad']}',
                    '{$datospadre[0]['id_aprobado']}',
                    '{$datospadre[0]['id_receptor']}', '{$datospadre[0]['nro_contenedor']}', '{$_POST['ticket']}', '{$datospadre[0]['id_proveedor']}');
                ";
                
                //print_r($kardex_almacen_instruccion); exit();
                $conn->ExecuteTrans($kardex_almacen_instruccion);
                $id_transaccion = $conn->getInsertID();
                //Se consulta el precio actual para dejar el historico en kardex (Junior)
                $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$datospadre[0]['id_item']}'";
                $precio_actual=$conn->ObtenerFilasBySqlSelect($sql);
                $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);
                //correlativo de ticket
                $sql="Select contador from correlativos where campo='id_ticket'";
                $idticket=$conn->ObtenerFilasBySqlSelect($sql);
                //sql kardex_detalle
                $kardex_almacen_detalle_instruccion = 
                "
                    INSERT INTO kardex_almacen_detalle (
                    `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`, `peso`, `peso_bruto`, `peso_estiva`, `peso_empaque`, `id_ubi_entrada`, `vencimiento`,`elaboracion`,`lote`, `c_esperada`,`observacion`, `precio`, `etiqueta`, `costo_declarado`, `id_marca`, `id_presentacion`, `observacion_limite`)
                    VALUES (
                    NULL, '{$id_transaccion}', '{$_POST["ubicacion_principal"]}',
                    '', '{$datospadre[0]['id_item']}', '{$_POST["cantidad"]}', '{$_POST["peso"]}', '{$_POST["peso_bruto"]}', '{$_POST["peso_estiva"]}', '{$_POST["peso_empaque"]}', '{$_POST["ubicacion_detalle"]}','{$datospadre[0]['vencimiento']}',
                    '{$datospadre[0]['elaboracion']}','{$datospadre[0]['lote']}','{$datospadre[0]['c_esperada']}','{$datospadre[0]['observacion']}', ".$precioconiva.", ".$idticket[0]['contador'].", '{$datospadre[0]['costo_declarado']}', '{$datospadre[0]['id_marca']}', '{$datospadre[0]['id_presentacion']}', '{$_POST["observacion_limite"]}');
                ";
                $conn->ExecuteTrans($kardex_almacen_detalle_instruccion);
                //actualizo correlativo
                $sql="update correlativos set contador=(contador+1) where campo='id_ticket'";
                $conn->ExecuteTrans($sql);
                //bloqueando las ubicaciones
                $sql="select ocupado from ubicacion where id=".$_POST['ubicacion_detalle'];
                $ocupado=$conn->ObtenerFilasBySqlSelect($sql);
                if($ocupado[0]['ocupado']==0)
                {
                    $sql=
                    "
                        update ubicacion set ocupado=1 where id=".$_POST['ubicacion_detalle'];
                    $conn->ExecuteTrans($sql);
                }
                else
                {
                    echo "-1"; exit();
                }
                $sql=
                "
                    SELECT * FROM item_existencia_almacen WHERE
                    id_item  = '{$datospadre[0]['id_item']}' 
                    AND id_ubicacion = '{$_POST['ubicacion_detalle']}'
                    AND lote='{$datospadre[0]['lote']}' 
                    AND id_marca='{$datospadre[0]['id_marca']}' 
                    AND id_proveedor='{$datospadre[0]['id_proveedor']}';
                ";
                $campos = $conn->ObtenerFilasBySqlSelect($sql);
    
                if (count($campos) > 0) 
                {
                    $cantidadExistente = $campos[0]["cantidad"];
                    $pesoExistente = $campos[0]["peso"];
                    $conn->ExecuteTrans(
                    "
                        UPDATE item_existencia_almacen 
                        SET cantidad = '" . ($cantidadExistente + $POST["cantidad"]) . "', peso = '" . ($pesoExistente + $POST["peso"]) . "'
                        WHERE id_item  = '{$datospadre[0]['id_item']}' 
                        AND id_ubicacion = '{$_POST['ubicacion_detalle']}' 
                        AND lote='{$datospadre[0]['lote']}' 
                        AND id_marca='{$datospadre[0]['id_marca']}' 
                        AND id_proveedor='{$datospadre[0]['id_proveedor']}';
                    ");
                } 
                else 
                {
                    $instruccion = 
                    "
                        INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`, `peso`, `id_ubicacion`, `lote`, `id_proveedor`,`id_marca`)
                        VALUES 
                        ('{$_POST['ubicacion_principal']}',
                        '{$datospadre[0]['id_item']}', '{$_POST['cantidad']}' , '{$_POST['peso']}',  '{$_POST['ubicacion_detalle']}',
                        '{$datospadre[0]['lote']}', '{$datospadre[0]['id_proveedor']}', '{$datospadre[0]['id_marca']}');
                    ";
                    $conn->ExecuteTrans($instruccion);
                }
            }
            //se procede a realizar el commit(quedaría pendiente realizar el pedido por entrada)
            //se comienza hacer la facturacion
            
            //tipo cliente
            $sql="select cod_tipo_cliente from clientes where id_cliente={$datospadre[0]['id_proveedor']}";
            $clienteseguro=0;
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
            
            //busco el id del movimiento cargo
            $sql="select id_tipo_movimiento_almacen from tipo_movimiento_almacen where descripcion= 'Cargo Tunel'";
            $id_movimiento=$conn->ObtenerFilasBySqlSelect($sql);
            //primero se buscar el movimiento "cargo" y se agrega el costo de los servicios de dicho movimiento
            $iva[]="";
            $base[]="";
            $total[]="";
            $idservicios[]="";
            $codservicio[]="";
            $nombreservicio[]="";
            $contador=0;
            $cobrar="";
            //guardando los servicios
            $cajas=json_decode($_POST['cajas']);
            foreach($cajas as $key => $valueser)
            {
                $cobrar.="'".$valueser->value."', ";
            }
            $cobrar=substr($cobrar, 0, -2);
            foreach ($id_movimiento as $key => $idmovimiento)
            {
                $sql="select id_movimiento_almacen, id_servicio from movimiento_almacen_servicio where id_movimiento_almacen = ".$idmovimiento['id_tipo_movimiento_almacen']." and id_servicio in (".$cobrar.")";
                $buscarservicios=$conn->ObtenerFilasBySqlSelect($sql);
                foreach($buscarservicios as $key2 => $servicios)
                {
                    
                    $sql="select id_item, cod_item, precio1, precio2, precio3, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
                    //echo $sql; exit();
                    $contarservicio=$conn->ObtenerFilasBySqlSelect($sql);
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
            
            //fin del cobro de movimiento, ahora se cobra la ubicacion
            $sql="select id_servicio from ubicacion_servicio where id_ubicacion=".$_POST['ubicacion_detalle'];
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
            //ahora a realizar el cobro del seguro******
            $sql="select id_item, cod_item, iva, descripcion1 from item where precio1=-1";
            $contarservicio=$conn->ObtenerFilasBySqlSelect($sql);
            if($clienteseguro==0)
            {
                if($contarservicio!=null)
                {
                    $iva[$contador] = $contarservicio[0]['iva'];
                    $base[$contador] = (($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.005); //$contarservicio[0]['precio1'];
                    $total[$contador] =( (($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.005) + (((($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.005)*$contarservicio[0]['iva']) / 100) ) ;
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
                    $base[$contador] = (($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.0075); //$contarservicio[0]['precio1'];
                    $total[$contador] =( (($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.0075) + (((($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.0075)*$contarservicio[0]['iva']) / 100) ) ;
                    $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                    $idservicios[$contador]= $contarservicio[0]['id_item'];
                    $codservicio[$contador]= $contarservicio[0]['cod_item'];
                    $contador++;
                }
            }
            //se comienza hacer el pedido por la entrada.
            //obtener correlativo:
            //obtenemos el correlativo de la factura
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
            $sql="select * from despacho_new where fecha_pago='0000-00-00' and id_cliente='{$datospadre[0]['id_proveedor']}' limit 1";
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
                    {$datospadre[0]['id_proveedor']}, '{$nro_factura}', '{$login->getUsuario()}', now(),
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
                            {$datospadre[0]['id_proveedor']},
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
                        '{$_POST['ubicacion_principal']}',
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
            { // cuando existe pedido pendiente
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
                        '{$_POST['ubicacion_principal']}',
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
                echo "1";
            }
            elseif($conn->errorTransaccion == 0)
            {
                echo "-1";
            }
            $conn->CommitTrans($conn->errorTransaccion);
            
        break;

        case "cargaUbicacion3":
            $almacen=$_POST["idAlmacen"];
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id_almacen='".$almacen."' and descripcion<>'PISO DE VENTA'" );
             if (count($campos) == 0) {
                 echo "[{band:'-1'}]";
             } else {
                 echo json_encode($campos);
             }
        break;

        case "cargaUbicacionsalidasTraslado":
            $almacen=$_POST["idAlmacen"];
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id_almacen='".$almacen."' and ocupado = 0 ");
            if (count($campos) == 0) 
            {
                echo "[{band:'-1'}]";
            } else {
                echo json_encode($campos);
            }
        break;
        case "cargasalidasTraslado":
           $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM almacen");
            if (count($campos) == 0) 
            {
                echo "[{band:'-1'}]";
            } else {
                echo json_encode($campos);
            }
        break;
        case "EliminarPaleta" :
            $sql="select * from kardex_almacen_detalle as a where a.id_transaccion_detalle='".$_POST["v1"]."'";
            $maestro= $conn->ObtenerFilasBySqlSelect($sql);
            //echo $sql; exit();
            $sql="select count(*) as total from kardex_almacen_detalle as a where a.id_transaccion='".$maestro[0]['id_transaccion']."'";
            $total=$conn->ObtenerFilasBySqlSelect($sql);
            if($total[0]['total']>1)
            {
                $conn->BeginTrans();
                $sql="delete from kardex_almacen_detalle where id_transaccion_detalle='".$_POST["v1"]."'";
                $conn->ExecuteTrans($sql);
                //las ubicaciones son unicas, por que lo se puede eliminar directamen solo con el id de la ubicacion
                $sql="delete from item_existencia_almacen where id_ubicacion='".$maestro[0]["id_ubi_entrada"]."'";
                $conn->ExecuteTrans($sql);
                $sql="update ubicacion set ocupado=0 where id_almacen='".$maestro[0]['id_almacen_entrada']."' and id='".$maestro[0]['id_ubi_entrada']."'";
                $conn->ExecuteTrans($sql);
                if ($conn->errorTransaccion == 1)
                {    
                    echo "1";
                }
                elseif($conn->errorTransaccion == 0)
                {
                    echo "2";
                }
                $conn->CommitTrans($conn->errorTransaccion);
                
            }
            else
            {
                echo 2; exit();
            }
        break;
        case "CerrarEntrada" :
            //cierro el detalle de calidad
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT id_transaccion FROM calidad_almacen_detalle  WHERE id_transaccion_detalle = '" . $_GET["v1"] . "'");
            
            if($campos!=null)
            {
                $sql="update calidad_almacen_detalle set cierre_entrada=1 where id_transaccion_detalle='".$_GET["v1"]."'";
                $conn->BeginTrans();
                $conn->ExecuteTrans($sql);
                //verificar si todos los hijos estan cerrados de ser asi, se debe avisar al kardex y cerrarlo
                $sql="select count(*) as total from calidad_almacen_detalle where id_transaccion='". $campos[0]['id_transaccion'] ."' and cierre_entrada=0";
                $datos=$conn->ObtenerFilasBySqlSelect($sql);
                if($datos[0]['total']==0)
                {
                    $campos2 = $conn->ObtenerFilasBySqlSelect("SELECT id_transaccion FROM kardex_almacen  WHERE id_transaccion_calidad = '" . $campos[0]['id_transaccion'] . "'");
                    if($campos2!=null)
                    {
                        $sql="update kardex_almacen set cierre_entrada=1 where id_transaccion='".$campos2[0]['id_transaccion']."'";
                        $conn->ExecuteTrans($sql);
                        if ($conn->errorTransaccion == 1)
                        {    
                            echo "1";
                        }
                        elseif($conn->errorTransaccion == 0)
                        {
                            echo "-1";
                        }
                        
                        $conn->CommitTrans($conn->errorTransaccion);
                    }
                    else
                    {
                        echo "-1";
                    }
                }
                else
                {
                    //si todavia existen cierres pendientes no se hace nada mas
                    if ($conn->errorTransaccion == 1)
                    {    
                        echo "1";
                    }
                    elseif($conn->errorTransaccion == 0)
                    {
                        echo "-1";
                    }
                }
                $conn->CommitTrans($conn->errorTransaccion);
            }
            else
            {
                echo "-1";
            }
           
            
        break;
        case "EliminarFacturaPedido":
            $sql="delete from despacho_new_detalle where id_detalle_factura = '".$_POST["id"]."'";
            $conn->BeginTrans();
            $conn->ExecuteTrans($sql);
            //$sql="delete from despacho_new where cod_factura='".$_POST["factura"]."'";
            //$conn->ExecuteTrans($sql);
            //$sql="delete from kardex_almacen_detalle where id_transaccion = (select id_transaccion from kardex_almacen where nro_factura='".$_POST["factura"]."')";
            //$conn->ExecuteTrans($sql);
            //$sql="delete from kardex_almacen where nro_factura='".$_POST["factura"]."'";*/
            //$sql="delete from kardex_almacen_detalle where id_transaccion_detalle=".$_POST["id"];
            //$conn->ExecuteTrans($sql);
            //verificamos si quedan detalles de no se asi se elimina los maestros
            $sql="select * from despacho_new  as a inner join despacho_new_detalle as b on a.id_factura=b.id_factura where cod_factura='".$_POST["factura"]."'";
            $total=$conn->ObtenerFilasBySqlSelect($sql);
            if(count($total)>0)
            {
                $iva=0;
                $subtotal=0;
                $totaltotal=0;
                $itemstotal=0;
                
                foreach($total as $key => $value)
                {
                    
                    $ivatotal+=($value['_item_piva']*$value['_item_preciosiniva'])/100;
                    $subtotal+=$value['_item_preciosiniva'];
                    $totaltotal+=$value['_item_preciosiniva']+(($value['_item_piva']*$value['_item_preciosiniva'])/100);
                    $itemstotal++;
                    
                }
                
                $sql = "update `despacho_new` set 
                `subtotal` = ". $subtotal . ", `descuentosItemFactura`= 0,`montoItemsFactura`=" . $subtotal . ",
                `ivaTotalFactura`= ". $ivatotal . ", `TotalTotalFactura` = " . $totaltotal . ", `cantidad_items` = ".$itemstotal.",
                `totalizar_sub_total` =".$subtotal.",`totalizar_descuento_parcial`=0,`totalizar_total_operacion`= ".$totaltotal.",
                `totalizar_pdescuento_global`= 0,`totalizar_descuento_global`= 0,
                `totalizar_base_imponible`= ".$subtotal.",`totalizar_monto_iva`=".$ivatotal.",
                `totalizar_total_general`=".$totaltotal.",`totalizar_total_retencion`=0
                where cod_factura='".$_POST["factura"]."'
                ";
                $conn->ExecuteTrans($sql);
                
                //eliminamos los hijos del kardex maestro
                $sql="delete from kardex_almacen_detalle where id_transaccion=(select id_transaccion from kardex_almacen where nro_factura='".$_POST['factura']."')";
                $conn->ExecuteTrans($sql);
                //obtenemos el id del maestro
                $sql="select * from kardex_almacen where nro_factura='".$_POST['factura']."'";
                $datospadre=$conn->ObtenerFilasBySqlSelect($sql);
                foreach($total as $key => $value2)
                {
                    $sql = "
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
                    '" . $datospadre[0]['id_transaccion'] . "',
                    '',
                    '1',
                    '" . $value2['id_item'] . "',
                    '" . $value2['_item_preciosiniva'] . "',
                    1
                    );";
                    $conn->ExecuteTrans($sql);
                }
           
                
            }
            else
            {   //se elimina toda la factura
                $sql="delete from despacho_new where cod_factura='".$_POST["factura"]."'";
                $conn->ExecuteTrans($sql);
                $sql="delete from kardex_almacen_detalle where id_transaccion=(select id_transaccion from kardex_almacen where nro_factura='".$_POST['factura']."')";
                $conn->ExecuteTrans($sql);
                $sql="delete from kardex_almacen where nro_factura='".$_POST["factura"]."'";
                $conn->ExecuteTrans($sql);
            }
            $conn->ExecuteTrans($sql);
            if ($conn->errorTransaccion == 1)
            {    
                echo "1";
            }
            elseif($conn->errorTransaccion == 0)
            {
                echo "-1";
            }
            $conn->CommitTrans($conn->errorTransaccion);
            
        break;
        case "ValidarOrdenUbicacion":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT id FROM ubicacion  WHERE id_almacen = '" . $_GET["v2"] . "' and orden = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        break;

        case "eliminarConductor" :
        $id=isset($_POST['id']) ? $_POST['id'] : '';
        if($conn->Execute2("delete from transporte_conductores where id=".$id))
        {
            echo 1;
        }
        else
        {
            echo 2;
        }

        break;

         case "ValidarCedulaConductor":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT cedula FROM transporte_conductores  WHERE cedula = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        
        break;

        case "ValidarTicketEntrada":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT id_transaccion FROM kardex_almacen WHERE ticket_entrada = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        break;
        case "EntradaNuevaAlmacen" :
            
            //buscamos los datos básicos en calidad y a su vez nos aseguramos  que no se ha registrado en kardex almacen
            $sql=
            "
                Select 
                a.id_transaccion, a.tipo_movimiento_almacen, a.autorizado_por, a.observacion, a.fecha, a.usuario_creacion,
                a.fecha_creacion, a.estado, a.fecha_ejecucion, a.id_documento, a.empresa_transporte, a.id_conductor,
                a.placa, a.guia_sunagro, a.orden_despacho, a.almacen_procedencia, a.id_proveedor, a.id_seguridad, a.id_aprobado,
                a.id_receptor, a.nro_contenedor, a.prescintos,  b.elaboracion, b.id_item, b.vencimiento, b.lote, b.c_esperada, b.observacion, b.costo_declarado, b.id_marca,  b.id_presentacion
                from calidad_almacen as a 
                inner join calidad_almacen_detalle as b on a.id_transaccion=b.id_transaccion
                where b.id_transaccion_detalle=".$_POST['id_movimiento']." 
                AND  a.id_transaccion not in (select id_transaccion_calidad from kardex_almacen where tipo_movimiento_almacen=3)
            ";
            $datospadre=$conn->ObtenerFilasBySqlSelect($sql);
            
            if($datospadre==null)
            {
                //si es null es por que ya existe
                $sql=
                "
                    select 
                    a.id_transaccion, a.tipo_movimiento_almacen, a.autorizado_por, a.observacion, a.fecha, a.usuario_creacion,
                    a.fecha_creacion, a.estado, a.fecha_ejecucion, a.id_documento, a.empresa_transporte, a.id_conductor,
                    a.placa, a.guia_sunagro, a.prescintos, a.orden_despacho, a.almacen_procedencia, a.id_proveedor, a.id_seguridad, a.id_aprobado,
                    a.id_receptor, a.nro_contenedor, b.elaboracion, b.id_item, b.vencimiento, b.lote, b.c_esperada, b.observacion, b.costo_declarado,  b.id_marca,  b.id_presentacion
                    from kardex_almacen as a
                    inner join calidad_almacen_detalle as b on a.id_transaccion_calidad=b.id_transaccion
                    where b.id_transaccion_detalle=".$_POST['id_movimiento']." and a.tipo_movimiento_almacen=3";
                $maestro=$conn->ObtenerFilasBySqlSelect($sql);
                if($maestro==null)
                {
                    //error
                    echo "-1"; exit();
                }
                else
                {
                    $datospadre=$maestro;
                    $conn->BeginTrans();
                    //Se consulta el precio actual para dejar el historico en kardex (Junior)
                    $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$datospadre[0]['id_item']}'";
                    $precio_actual=$conn->ObtenerFilasBySqlSelect($sql);
                    $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);
                    //correlativo de ticket
                    $sql="Select contador from correlativos where campo='id_ticket'";
                    $idticket=$conn->ObtenerFilasBySqlSelect($sql);
                    //sql kardex_detalle
                    $kardex_almacen_detalle_instruccion = 
                    "
                        INSERT INTO kardex_almacen_detalle (
                        `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                        `id_almacen_salida`, `id_item`, `cantidad`, `peso`, `peso_bruto`, `peso_estiva`, `peso_empaque`, `id_ubi_entrada`, `vencimiento`,`elaboracion`,`lote`, `c_esperada`,`observacion`, `precio`, `etiqueta`, `costo_declarado`, `id_marca`, `id_presentacion`,  `observacion_limite`, `temperatura`)
                        VALUES (
                        NULL, '{$datospadre[0]['id_transaccion']}', '{$_POST["ubicacion_principal"]}',
                        '', '{$datospadre[0]['id_item']}', '{$_POST["cantidad"]}', '{$_POST["peso"]}', '{$_POST["peso_bruto"]}', '{$_POST["peso_estiva"]}', '{$_POST["peso_empaque"]}','{$_POST["ubicacion_detalle"]}','{$datospadre[0]['vencimiento']}',
                        '{$datospadre[0]['elaboracion']}','{$datospadre[0]['lote']}','{$datospadre[0]['c_esperada']}','{$datospadre[0]['observacion']}', ".$precioconiva.", ".$idticket[0]['contador'] .", ".$datospadre[0]['costo_declarado'] .", ".$datospadre[0]['id_marca'] .", ".$datospadre[0]['id_presentacion'] .", '{$_POST["observacion_limite"]}', '{$_POST["temperatura"]}');
                    ";
                    $conn->ExecuteTrans($kardex_almacen_detalle_instruccion);
                    //actualizo correlativo
                    $sql="update correlativos set contador=(contador+1) where campo='id_ticket'";
                    $conn->ExecuteTrans($sql);
                    //bloqueando las ubicaciones
                    $sql="select ocupado from ubicacion where id=".$_POST['ubicacion_detalle'];
                    $ocupado=$conn->ObtenerFilasBySqlSelect($sql);
                    if($ocupado[0]['ocupado']==0)
                    {
                        $sql=
                        "
                            update ubicacion set ocupado=1 where id=".$_POST['ubicacion_detalle'];
                        $conn->ExecuteTrans($sql);
                    }
                    else
                    {
                        echo "-1"; exit();
                    }
        
                    $sql=
                    "
                        SELECT * FROM item_existencia_almacen WHERE
                        id_item  = '{$datospadre[0]['id_item']}' 
                        AND id_ubicacion = '{$_POST['ubicacion_detalle']}'
                        AND lote='{$datospadre[0]['lote']}' 
                        AND id_marca='{$datospadre[0]['id_marca']}' 
                        AND id_proveedor='{$datospadre[0]['id_proveedor']}';
                    ";
                    $campos = $conn->ObtenerFilasBySqlSelect($sql);
        
                    if (count($campos) > 0) 
                    {
                        $cantidadExistente = $campos[0]["cantidad"];
                        $pesoExistente = $campos[0]["peso"];
                        $conn->ExecuteTrans(
                        "
                            UPDATE item_existencia_almacen 
                            SET cantidad = '" . ($cantidadExistente + $POST["cantidad"]) . "', peso = '" . ($pesoExistente + $POST["peso"]) . "'
                            WHERE id_item  = '{$datospadre[0]['id_item']}' 
                            AND id_ubicacion = '{$_POST['ubicacion_detalle']}' 
                            AND lote='{$datospadre[0]['lote']}' 
                            AND id_marca='{$datospadre[0]['id_marca']}' 
                            AND id_proveedor='{$datospadre[0]['id_proveedor']}';
                        ");
                    } 
                    else 
                    {
                        $instruccion = 
                        "
                            INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`, `peso`, `id_ubicacion`, `lote`, `id_proveedor`, `id_marca`)
                            VALUES 
                            ('{$_POST['ubicacion_principal']}',
                            '{$datospadre[0]['id_item']}', '{$_POST['cantidad']}' , '{$_POST['peso']}',  '{$_POST['ubicacion_detalle']}',
                            '{$datospadre[0]['lote']}', '{$datospadre[0]['id_proveedor']}', '{$datospadre[0]['id_marca']}');
                        ";
                        $conn->ExecuteTrans($instruccion);
                    }    
                }//fin del else
            }
            else
            {
                //se comienza a realizar el insert maestro(kardex_almacen=calidad_almacen)
                $conn->BeginTrans();
                $kardex_almacen_instruccion = 
                "
                    INSERT INTO kardex_almacen (
                    `id_transaccion_calidad` , `tipo_movimiento_almacen`, `autorizado_por`,
                    `observacion`, `fecha`, `usuario_creacion`,
                    `fecha_creacion`, `estado`, `fecha_ejecucion`, `id_documento`, `empresa_transporte`, `id_conductor`, `placa`, 
                    `guia_sunagro`, `orden_despacho`, `almacen_procedencia`, `id_proveedor`,  `id_seguridad`, `id_aprobado`,
                    `id_receptor`, `nro_contenedor`, `ticket_entrada`, `id_cliente`, `prescintos`)
                    VALUES (
                    {$datospadre[0]['id_transaccion']} , '3', '{$datospadre[0]['autorizado_por']}',
                    '{$datospadre[0]['observacion']}', '{$datospadre[0]['fecha']}', '{$login->getUsuario()}', 
                    CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP, '{$datospadre[0]['id_documento']}', '{$datospadre[0]['empresa_transporte']}',
                    '{$datospadre[0]['id_conductor']}', '{$datospadre[0]['placa']}', '{$datospadre[0]['guia_sunagro']}',
                    '{$datospadre[0]['orden_despacho']}',
                    '{$datospadre[0]['almacen_procedencia']}', '{$datospadre[0]['id_proveedor']}','{$datospadre[0]['id_seguridad']}',
                    '{$datospadre[0]['id_aprobado']}',
                    '{$datospadre[0]['id_receptor']}', '{$datospadre[0]['nro_contenedor']}', '{$_POST['ticket']}', '{$datospadre[0]['id_proveedor']}', '{$datospadre[0]['prescintos']}');
                ";
                
                //print_r($kardex_almacen_instruccion); exit();
                $conn->ExecuteTrans($kardex_almacen_instruccion);
                $id_transaccion = $conn->getInsertID();
                //Se consulta el precio actual para dejar el historico en kardex (Junior)
                $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$datospadre[0]['id_item']}'";
                $precio_actual=$conn->ObtenerFilasBySqlSelect($sql);
                $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);
                //correlativo de ticket
                $sql="Select contador from correlativos where campo='id_ticket'";
                $idticket=$conn->ObtenerFilasBySqlSelect($sql);
                //sql kardex_detalle
                $kardex_almacen_detalle_instruccion = 
                "
                    INSERT INTO kardex_almacen_detalle (
                    `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`, `peso`, `peso_bruto`, `peso_estiva`, `peso_empaque`, `id_ubi_entrada`, `vencimiento`,`elaboracion`,`lote`, `c_esperada`,`observacion`, `precio`, `etiqueta`, `costo_declarado`, `id_marca`, `id_presentacion`, `observacion_limite`, `temperatura`)
                    VALUES (
                    NULL, '{$id_transaccion}', '{$_POST["ubicacion_principal"]}',
                    '', '{$datospadre[0]['id_item']}', '{$_POST["cantidad"]}', '{$_POST["peso"]}', '{$_POST["peso_bruto"]}', '{$_POST["peso_estiva"]}', '{$_POST["peso_empaque"]}','{$_POST["ubicacion_detalle"]}','{$datospadre[0]['vencimiento']}',
                    '{$datospadre[0]['elaboracion']}','{$datospadre[0]['lote']}','{$datospadre[0]['c_esperada']}','{$datospadre[0]['observacion']}', ".$precioconiva.", ".$idticket[0]['contador'].", '{$datospadre[0]['costo_declarado']}', '{$datospadre[0]['id_marca']}', '{$datospadre[0]['id_presentacion']}', '{$_POST["observacion_limite"]}','{$_POST["temperatura"]}');
                ";
                $conn->ExecuteTrans($kardex_almacen_detalle_instruccion);
                //actualizo correlativo
                $sql="update correlativos set contador=(contador+1) where campo='id_ticket'";
                $conn->ExecuteTrans($sql);
                //bloqueando las ubicaciones
                $sql="select ocupado from ubicacion where id=".$_POST['ubicacion_detalle'];
                $ocupado=$conn->ObtenerFilasBySqlSelect($sql);
                if($ocupado[0]['ocupado']==0)
                {
                    $sql=
                    "
                        update ubicacion set ocupado=1 where id=".$_POST['ubicacion_detalle'];
                    $conn->ExecuteTrans($sql);
                }
                else
                {
                    echo "-1"; exit();
                }
                $sql=
                "
                    SELECT * FROM item_existencia_almacen WHERE
                    id_item  = '{$datospadre[0]['id_item']}' 
                    AND id_ubicacion = '{$_POST['ubicacion_detalle']}'
                    AND lote='{$datospadre[0]['lote']}' 
                    AND id_marca='{$datospadre[0]['id_marca']}' 
                    AND id_proveedor='{$datospadre[0]['id_proveedor']}';
                ";
                $campos = $conn->ObtenerFilasBySqlSelect($sql);
    
                if (count($campos) > 0) 
                {
                    $cantidadExistente = $campos[0]["cantidad"];
                    $pesoExistente = $campos[0]["peso"];
                    $conn->ExecuteTrans(
                    "
                        UPDATE item_existencia_almacen 
                        SET cantidad = '" . ($cantidadExistente + $POST["cantidad"]) . "', peso = '" . ($pesoExistente + $POST["peso"]) . "'
                        WHERE id_item  = '{$datospadre[0]['id_item']}' 
                        AND id_ubicacion = '{$_POST['ubicacion_detalle']}' 
                        AND lote='{$datospadre[0]['lote']}' 
                        AND id_marca='{$datospadre[0]['id_marca']}' 
                        AND id_proveedor='{$datospadre[0]['id_proveedor']}';
                    ");
                } 
                else 
                {
                    $instruccion = 
                    "
                        INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`, `peso`, `id_ubicacion`, `lote`, `id_proveedor`,`id_marca`)
                        VALUES 
                        ('{$_POST['ubicacion_principal']}',
                        '{$datospadre[0]['id_item']}', '{$_POST['cantidad']}' , '{$_POST['peso']}',  '{$_POST['ubicacion_detalle']}',
                        '{$datospadre[0]['lote']}', '{$datospadre[0]['id_proveedor']}', '{$datospadre[0]['id_marca']}');
                    ";
                    $conn->ExecuteTrans($instruccion);
                }
            }
            //se procede a realizar el commit(quedaría pendiente realizar el pedido por entrada)
            //se comienza hacer la facturacion
            
            //tipo cliente
            $sql="select cod_tipo_cliente from clientes where id_cliente={$datospadre[0]['id_proveedor']}";
            $clienteseguro=0;
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
            
            //busco el id del movimiento cargo
            $sql="select id_tipo_movimiento_almacen from tipo_movimiento_almacen where descripcion= 'Cargo'";
            $id_movimiento=$conn->ObtenerFilasBySqlSelect($sql);
            //primero se buscar el movimiento "cargo" y se agrega el costo de los servicios de dicho movimiento
            $iva[]="";
            $base[]="";
            $total[]="";
            $idservicios[]="";
            $codservicio[]="";
            $nombreservicio[]="";
            $contador=0;
            $cobrar="";
            //guardando los servicios
            $cajas=json_decode($_POST['cajas']);
            foreach($cajas as $key => $valueser)
            {
                $cobrar.="'".$valueser->value."', ";
            }
            $cobrar=substr($cobrar, 0, -2);
            foreach ($id_movimiento as $key => $idmovimiento)
            {
                $sql="select id_movimiento_almacen, id_servicio from movimiento_almacen_servicio where id_movimiento_almacen = ".$idmovimiento['id_tipo_movimiento_almacen']." and id_servicio in (".$cobrar.")";
                $buscarservicios=$conn->ObtenerFilasBySqlSelect($sql);
                foreach($buscarservicios as $key2 => $servicios)
                {
                    
                    $sql="select id_item, cod_item, precio1, precio2, precio3, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
                    //echo $sql; exit();
                    $contarservicio=$conn->ObtenerFilasBySqlSelect($sql);
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
            
            //fin del cobro de movimiento, ahora se cobra la ubicacion
            $sql="select id_servicio from ubicacion_servicio where id_ubicacion=".$_POST['ubicacion_detalle'];
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
            //ahora a realizar el cobro del seguro******
            $sql="select id_item, cod_item, iva, descripcion1 from item where precio1=-1";
            $contarservicio=$conn->ObtenerFilasBySqlSelect($sql);
            if($clienteseguro==0)
            {
                if($contarservicio!=null)
                {
                    $iva[$contador] = $contarservicio[0]['iva'];
                    $base[$contador] = (($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.005); //$contarservicio[0]['precio1'];
                    $total[$contador] =( (($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.005) + (((($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.005)*$contarservicio[0]['iva']) / 100) ) ;
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
                    $base[$contador] = (($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.0075); //$contarservicio[0]['precio1'];
                    $total[$contador] =( (($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.0075) + (((($_POST["peso"]*$datospadre[0]['costo_declarado']) * 0.0075)*$contarservicio[0]['iva']) / 100) ) ;
                    $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                    $idservicios[$contador]= $contarservicio[0]['id_item'];
                    $codservicio[$contador]= $contarservicio[0]['cod_item'];
                    $contador++;
                }
            }
            //se comienza hacer el pedido por la entrada.
            //obtener correlativo:
            //obtenemos el correlativo de la factura
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
            $sql="select * from despacho_new where fecha_pago='0000-00-00' and id_cliente='{$datospadre[0]['id_proveedor']}' limit 1";
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
                    {$datospadre[0]['id_proveedor']}, '{$nro_factura}', '{$login->getUsuario()}', now(),
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
                            {$datospadre[0]['id_proveedor']},
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
                        '{$_POST['ubicacion_principal']}',
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
            { // cuando existe pedido pendiente
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
                        '{$_POST['ubicacion_principal']}',
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
                echo "1";
            }
            elseif($conn->errorTransaccion == 0)
            {
                echo "-1";
            }
            $conn->CommitTrans($conn->errorTransaccion);
            
        break;
        case "cargaUbicacionNuevo":
           $almacen=$_POST["idUbicacion"];
           $campos = $conn->ObtenerFilasBySqlSelect("SELECT id, concat(descripcion, ' - ', orden) as descripcion FROM ubicacion WHERE id_almacen='".$almacen."' and descripcion<>'PISO DE VENTA' and ocupado=0 order by  orden asc ");
            if (count($campos) == 0) 
            {
                echo "[{band:'-1'}]";
            } 
            else 
            {
                foreach ($campos as $filas)
                {
                ?>
                    <option value= "<?php echo $filas['id']; ?>"><?php echo $filas['descripcion']; ?> </option>
    
                <?php 
                }
            }
        break;
        case "det_items_calidad":
            if ($_GET["id_tipo_movimiento_almacen"] == '3' || $_GET["id_tipo_movimiento_almacen"] == '1') 
            {
                $operacion = "Entrada";

                $campos = $conn->ObtenerFilasBySqlSelect
                (
                    "SELECT ite.*,m.*, k.id_transaccion as id_calidad2, kad.id_transaccion_detalle, kad.cantidad AS cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad, date_format(k.fecha_creacion, '%d/%m/%Y %h:%i:%s ') as fecha_creacion
                    FROM calidad_almacen_detalle AS kad
                    LEFT JOIN marca m ON m.id = kad.id_marca
                    JOIN calidad_almacen AS k ON kad.id_transaccion=k.id_transaccion
                    LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen
                    LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id
                    LEFT JOIN item AS ite ON kad.id_item=ite.id_item
                    LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
                    WHERE kad.id_transaccion =" . $_GET["id_transaccion"]." order by k.fecha_creacion"
                );
            } 
            else if ($_GET["id_tipo_movimiento_almacen"] == '4' || $_GET["id_tipo_movimiento_almacen"] == '2' || $_GET["id_tipo_movimiento_almacen"] == '8') 
            {
                $operacion = "Salida";
                $campos = $conn->ObtenerFilasBySqlSelect
                (
                    "SELECT *, kad.id_transaccion_detalle, kad.cantidad as cantidad_item, ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
                    FROM calidad_almacen_detalle AS kad
                    join calidad_almacen AS k ON kad.id_transaccion=k.id_transaccion
                    LEFT JOIN almacen AS alm ON kad.id_almacen_salida=alm.cod_almacen
                    LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id
                    LEFT JOIN item AS ite ON kad.id_item=ite.id_item
                    LEFT JOIN marca m ON m.id = ite.id_marca
                    LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
                    WHERE kad.id_transaccion =" . $_GET["id_transaccion"]
                );
            } 
            else if ($_GET["id_tipo_movimiento_almacen"] == '5') 
            {
                $operacion = "Traslado";
                $campos = $conn->ObtenerFilasBySqlSelect
                (
                    "SELECT *, kad.id_transaccion_detalle, kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
                    FROM calidad_almacen_detalle AS kad
                    LEFT JOIN calidad_almacen AS k ON kad.id_transaccion=k.id_transaccion
                    LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen
                    LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id
                    LEFT JOIN item AS ite ON kad.id_item=ite.id_item
                    LEFT JOIN marca m ON m.id = ite.id_marca
                    LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
                    WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
                    
                $campos1 = $conn->ObtenerFilasBySqlSelect
                (
                    "SELECT *, kad.id_transaccion_detalle, kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
                    from calidad_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            }
            else if ($_GET["id_tipo_movimiento_almacen"] == '9') 
            {
                $operacion = " Ajuste +";
                $campos = $conn->ObtenerFilasBySqlSelect
                (
                    "SELECT *, kad.id_transaccion_detalle, kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
                    FROM calidad_almacen_detalle AS kad
                    LEFT JOIN calidad_almacen AS k ON kad.id_transaccion=k.id_transaccion
                    LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen
                    LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id
                    LEFT JOIN item AS ite ON kad.id_item=ite.id_item
                    LEFT JOIN marca m ON m.id = ite.id_marca
                    LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
                    WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
                $campos1 = $conn->ObtenerFilasBySqlSelect
                (
                    "SELECT *, kad.id_transaccion_detalle, kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
                    from calidad_almacen_detalle as kad join calidad_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            }
            else if ($_GET["id_tipo_movimiento_almacen"] == '10') 
            {
                $operacion = " Ajuste -";
                $campos = $conn->ObtenerFilasBySqlSelect
                (
                    "SELECT *, kad.id_transaccion_detalle, kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
                    FROM calidad_almacen_detalle AS kad
                    LEFT JOIN calidad_almacen AS k ON kad.id_transaccion=k.id_transaccion
                    LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen
                    LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id
                    LEFT JOIN item AS ite ON kad.id_item=ite.id_item
                    LEFT JOIN marca m ON m.id = ite.id_marca
                    LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
                    WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
                $campos1 = $conn->ObtenerFilasBySqlSelect
                (
                    "SELECT *, kad.id_transaccion_detalle, kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
                    from calidad_almacen_detalle as kad join calidad_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            }
            if (count($campos) == 0) 
            {
                exit;
            }
            if ($_GET["id_tipo_movimiento_almacen"] == '5') 
            {
                echo 
                '
                    <tr class="detalle_items">
                        <input type="hidden" name="desplegado" value="true"/>
                        <td colspan="8">
                            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                                <table >
                                    <thead>
                                        <th style="width:110px; font-weight: bold; text-align: center;">C&oacute;digo</th>
                                        <th style="width:150px; font-weight: bold;">Almac&eacute;n Entrada</th>
                                        <th style="width:150px; font-weight: bold;">Ubicaci&oacute;n Entrada</th>
                                        <th style="width:150px; font-weight: bold;">Almac&eacute;n Salida</th>
                                         <th style="width:150px; font-weight: bold;">Ubicaci&oacute;n Salida</th>
                                        <th style="width:300px; font-weight: bold;">Item</th>
                                        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                                        <th style="width:110px; font-weight: bold; text-align: center;">Operación</th>
                                    </thead>
                                    <tbody>';
            } 
            else 
            {
                echo 
                '
                    <tr class="detalle_items">
                        <input type="hidden" name="desplegado" value="true"/>
                        <td colspan="8">
                            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                                <table >
                                    <thead>
                                        <th style="width:110px; font-weight: bold; text-align: center;">C&oacute;digo</th>
                                        <th style="width:150px; font-weight: bold;">Fecha Creaci&oacute;n</th>
                                        
                                        <th style="width:300px; font-weight: bold;">Item</th>
                                        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                                        <th style="width:110px; font-weight: bold; text-align: center;">Operación</th>
                                    </thead>
                                    <tbody>';
            }
            foreach ($campos as $key => $item) 
            {
                if ($_GET["id_tipo_movimiento_almacen"] == '5') 
                {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["codigo_barras"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["almacen"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["ubicacion"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["almacen"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["ubicacion"] . '</td>
                            <td style="width:300px;">' . $item["descripcion1"] ." - ". $item["marca"]." ". $item["pesoxunidad"]."". $item["nombre_unidad"]. '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                            <td style="text-align: right; padding-right:10px;"><img id="'.$item['id_transaccion_detalle'].'" title="Realizar Entrada" src="../../../includes/imagenes/ico_last.gif"/ onclick=entradapaleta(this.id)></td>
                            
                        </tr>';
                } else {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["codigo_barras"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["fecha_creacion"] . '</td>
                            
                            <td style="width:300px; padding-left:10px;">' . $item["descripcion1"] ." - ". $item["marca"] . " ". $item["pesoxunidad"]."". $item["nombre_unidad"].'</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                            <td style="text-align: center; padding-right:10px;"><img id="'.$item['id_transaccion_detalle'].'" alt="'.$item['id_calidad2'].'" title="Realizar Entrada" src="../../../includes/imagenes/ico_last.gif" onclick="entradapaleta(this.id, this.alt);"/></td>
                        </tr>';
                }
            }

            if ($campos[0]["estado"] == "Pendiente") 
            {
                echo 
                '   
                    <tr>
                        <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;width:110px;">
                            <br/>
                            <!--form>
                                <label for="fecha">Fecha</label><input type="text" name="fecha">
                                <label for="control">Nro. Control</label><input type="text" name="control">
                                <label for="factura">Nro. Factura</label><input type="text" name="factura"-->
                                <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=3&opt_seccion=109&opt_subseccion=add&cod=' . $_GET["id_transaccion"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <!--<td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                        <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                         <td class="btn_bg" nowrap style="padding: 0px 1px;">Realizar Entrada</td> -->
                                        <!-- <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>-->
                                    </tr>
                                </table>
                            <!--/form-->
                        </td>
                    </tr>';
            }
            echo
            '</tbody>
                    </table>
                    </div>
                    </td>
                    </tr>';
            break;
        case "ValidarCodigoBarrasItem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE codigo_barras = '" . $_GET["v1"] . "'");
                        
            if (count($campos)!=0) {
                                
                $campos2 = $conn->ObtenerFilasBySqlSelect("SELECT cmd._pvp,cmd._estatus_producto,cme.estatus_name
                    FROM selectrapyme_central.cotizacion_mercado_detalle cmd
                    INNER JOIN selectrapyme.item i ON i.id_item = cmd.id_producto
                    INNER JOIN selectrapyme_central.cotizacion_mercado_estatus cme ON cme.id_estatus = cmd._estatus_producto
                    WHERE i.codigo_barras = '" . $_GET["v1"] . "'");

                if (count($campos2)!=0) {
                    echo "[{'rc':'-1','mensaje1':'".$campos2[0]["_pvp"]."','mensaje2':'".$campos2[0]["estatus_name"]."'}]";
                }else{
                    echo "[{'rc':'-1','mensaje1':'0','mensaje2':'Rubro no cotizado'}]";
                }

            }else{

                $campos2 = $conn->ObtenerFilasBySqlSelect("SELECT cmd._pvp,cmd._estatus_producto,cme.estatus_name
                    FROM selectrapyme_central.cotizacion_mercado_detalle cmd
                    INNER JOIN selectrapyme.item i ON i.id_item = cmd.id_producto
                    INNER JOIN selectrapyme_central.cotizacion_mercado_estatus cme ON cme.id_estatus = cmd._estatus_producto
                    WHERE i.codigo_barras = '" . $_GET["v1"] . "'");

                if (count($campos2)!=0) {
                    echo "[{'rc':'0','mensaje1':'".$campos2[0]["_pvp"]."','mensaje2':'".$campos2[0]["estatus_name"]."'}]";
                }else{
                    echo "[{'rc':'0','mensaje1':'0','mensaje2':'Rubro no cotizado'}]";
                }
            };

            break;

        case "eliminarServicioMovimiento":
            $delete="delete from movimiento_almacen_servicio where id=".$_GET['v1'];
            if($conn->Execute2($delete))
            {
                echo "1";
            }
            else
            {
                echo "-1";
            }
        break;
        
        case "eliminarServicioUbicacion":
            $delete="delete from ubicacion_servicio where id=".$_GET['v1'];
            if($conn->Execute2($delete))
            {
                echo "1";
            }
            else
            {
                echo "-1";
            }
        break;
        case "EstadoCuenta" : 

            if ($_GET["id_tipo_movimiento_almacen"] == '8' || $_GET["id_tipo_movimiento_almacen"] == '4')
            {
                /*
                $sql="SELECT *,kad.cantidad as cantidad_item, b.id_detalle_factura as id_detalle_f, ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad, kad.precio, DATE_FORMAT(k.fecha_creacion, '%d/%m/%Y %h:%i:%s') as fecha_creacion
                FROM kardex_almacen_detalle AS kad
                join kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion
                Inner Join clientes as cli on k.id_cliente=cli.id_cliente
                INNER JOIN despacho_new as a on a.cod_factura=k.nro_factura
                INNER JOIN despacho_new_detalle as b on a.id_factura=b.id_factura
                LEFT JOIN almacen AS alm ON kad.id_almacen_salida=alm.cod_almacen
                LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id
                LEFT JOIN item AS ite ON kad.id_item=ite.id_item
                LEFT JOIN marca m ON m.id = ite.id_marca
                LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
                WHERE 
                cli.id_cliente='".$_GET['id_cliente']."' 
                and 
                k.estado='".$_GET['estatus']."'
                order by k.fecha_creacion, kad.id_transaccion asc 
                ";
                */
                $operacion = "Salida";
                $sql="SELECT *,a.cantidad_items as cantidad_item, b.id_detalle_factura as id_detalle_f, ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad, b._item_totalsiniva as precio, DATE_FORMAT(b.fecha_creacion, '%d/%m/%Y %h:%i:%s') as fecha_creacion
                FROM despacho_new as a
                INNER JOIN despacho_new_detalle as b on a.id_factura=b.id_factura
                Inner Join clientes as cli on a.id_cliente=cli.id_cliente
                INNER JOIN kardex_almacen as k on a.cod_factura=k.nro_factura
                
                LEFT JOIN almacen AS alm ON b._item_almacen=alm.cod_almacen
                LEFT JOIN ubicacion AS ubi ON b._item_almacen=ubi.id
                LEFT JOIN item AS ite ON b.id_item=ite.id_item
                LEFT JOIN marca m ON m.id = ite.id_marca
                LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
                WHERE 
                cli.id_cliente='".$_GET['id_cliente']."' 
                and 
                k.estado='".$_GET['estatus']."'
                order by k.fecha_creacion, b.id_detalle_factura asc 
                ";
                //echo $sql; exit();
                $campos = $conn->ObtenerFilasBySqlSelect($sql);
                //kad.id_transaccion =" . $_GET["id_transaccion"]
            }

            if (count($campos) == 0) {
                exit;
            }

            echo '
                    <tr class="detalle_items">
                        <input type="hidden" name="desplegado" value="true"/>
                        <td colspan="8">
                            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                            <table >
                                <thead>
                                    <th style="width:110px; font-weight: bold; text-align: center;">Factura</th>
                                    <th style="width:110px; font-weight: bold; text-align: center;">C&oacute;digo</th>
                                    <th style="width:150px; font-weight: bold;">Fecha Creación </th>
                                    
                                    <th style="width:300px; font-weight: bold;">Item</th>
                                    <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                                    <th style="width:110px; font-weight: bold; text-align: center;">Precio</th>
                                    <th style="width:110px; font-weight: bold; text-align: center;">Total</th>
                                    <th style="width:110px; font-weight: bold; text-align: center;">Acci&oacute;n</th>
                                </thead>
                            <tbody>
                ';
            foreach ($campos as $key => $item) 
            {

                echo 
                '
                    <tr>
                        <td style="width:110px; text-align: right; padding-right:10px;">' . $item["nro_factura"] . '</td>
                        <td style="width:110px; text-align: right; padding-right:10px;">' . $item["codigo_barras"] . '</td>
                        <td style="width:150px; padding-left:10px;">' . $item["fecha_creacion"] . '</td>
                        <td style="width:300px; padding-left:10px;">' . $item["descripcion1"] ." - ". $item["marca"] . " ". $item["pesoxunidad"]."". $item["nombre_unidad"].'</td>
                        <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                        <td style="text-align: right; padding-right:10px;">' . number_format($item['precio'], '2', ',', '.') . '</td>
                        <td style="text-align: right; padding-right:10px;">' . number_format(($item['_item_totalconiva']), '2', ',', '.') . '</td>
                        <td style="padding: 0px;" align="center"><img  src="../../libs/imagenes/delete.gif" title="Eliminar" alt="Eliminar" width="20" height="20" style="border-width: 0px;"  onclick=\'eliminar("'.$item["nro_factura"].'", '.$item["id_detalle_f"].' );\'/></td>
                    </tr>
                ';
            }

            if ($campos[0]["estado"] == "Pendiente") 
            {
                echo 
                '
                    <tr>
                        <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;width:110px;">
                            <br/>
                            <!--form>
                                <label for="fecha">Fecha</label><input type="text" name="fecha">
                                <label for="control">Nro. Control</label><input type="text" name="control">
                                <label for="factura">Nro. Factura</label><input type="text" name="factura"-->
                                <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=3&opt_seccion=109&opt_subseccion=add&cod=' . $_GET["id_transaccion"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <!--<td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                        <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                         <td class="btn_bg" nowrap style="padding: 0px 1px;">Realizar Entrada</td> -->
                                        <!-- <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>-->
                                    </tr>
                                </table>
                            <!--/form-->
                        </td>
                    </tr>';
            }
            echo
            '
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            ';
        break;

        case "MontoAcumulado": 
            //efectivo
            if($_POST['tipo']==0)
            {
                $tipo='monto_acumulado';
            }
            elseif ($_POST['tipo']==1) //tickets
            {
                $tipo='monto_acumulado_tickets';
            }
            elseif ($_POST['tipo']==2) //tarjeta
            {
                $tipo='monto_acumulado_tarjeta';
            }
            elseif ($_POST['tipo']==3) //deposito
            {
                $tipo='monto_acumulado_deposito';
            }
            else
            {
                $tipo='monto_acumulado_cheque';
            }
            $monto=$conn->ObtenerFilasBySqlSelect("Select ".$tipo." from caja_principal where fecha_deposito!='0000-00-00 00:00:00' order by id_deposito desc limit 1");

            echo $monto[0][$tipo];
        break;

        case "TarjetaAcumulado" :
            $sql="Select monto_acumulado_tarjeta from $bd_pyme.caja_principal where fecha_deposito!='0000-00-00 00:00:00' order by id_deposito desc limit 1";
            $tarjeta=$conn->ObtenerFilasBySqlSelect($sql);
            if(isset($tarjeta[0]['monto_acumulado_tarjeta']) || $tarjeta[0]['monto_acumulado_tarjeta']!='')
            {
                echo $tarjeta[0]['monto_acumulado_tarjeta'];
            }
            else
            {
                echo 0;
            }
        break;
        case "calcular_monto_cataporte_credito":
            $_POST['arreglo']=str_replace("\\", "", $_POST['arreglo']);
            $deposito=$conn->ObtenerFilasBySqlSelect("Select * from caja_principal where nro_deposito in (".$_POST['arreglo'].")");
            //creando formulario
            echo "<table border=1 width='25%'>
            <thead class='tb-head'>
            <tr>
                <th colspan=2><center>Cataporte</center></th>
            </tr>
            </thead>
            <tr>
                <td width='40%' align='center'><b>Nro Deposito</b></td>
                <td width='40%' align='center'><b>Monto</b></td>
            </tr>
            ";
            $total=0;
            //$total_format=0;
            foreach ($deposito as $key ) {
                if(isset($key['monto_acumulado_credito'])){
                $total+=$key['monto_acumulado_credito'];
                $monto_format=number_format($key['monto_acumulado_credito'],2,'.', '');//se cambio monto acumulado
                $total_format=number_format($total,2,'.', '');
                
           echo "
            <tr>
                <td width='40%' align='center'><input type='hidden' name=nro_depositos[] value=".$key['nro_deposito']." />".$key['nro_deposito']."</td>
                <td width='40%' align='right'>".$monto_format."</td>
            </tr>";
           }} echo"
                <tr>
            <th><center><b>TOTAL</b><center></th>
            <td align='right'><b>".$total_format."</b></th>
            </tr>
            </table>
            <br>
            <table cellspacing=2>
            <tr>
            <td align='right'><b>Nro Cataporte:</b></td><td><input type='text' name='nro_cataporte' id='nro_cataporte' size=20 onkeyup='aMays(event, this)' onBlur='comprobar(this.id)'/></td>
            <div id='resultado2' width='50%' align='center'></div>
            </tr>
            <tr>
            <td colspan=2><p>&nbsp;</p></td>
            </tr>
            <br>
            <tr>
            <td align='right'><b>Bolsas Cataporte:</b></td><td> <input type='text' name='cantidad' id='cantidad' value='' onkeyup='crearCampos(this.value);' size=3/></center></td>
            </tr>
            <tr id='monto_usuario_tr22' style='visibility:hidden'>
                <td align='center' colspan='2'>&nbsp;</td>
                
            </tr>
            <tr id='monto_usuario_tr' >
                <td align='center'><b>Monto Usuario </b></td>
                <td align='center'><input type='text'  name='monto_usuario' id='monto_usuario' /> </td>
                
            </tr>
            <tr id='monto_usuario_tr1' >
                <td align='center'><b>Observacion </b></td>
                <td align='center'><input type='text'  name='observacion' id='observacion' /> </td>
            </tr>
            
            </table>
            <br><div id='campos_dinamicos'></div>
            <br>
            <table border=0 width='25%' cellspacing=2>
            <tr>
            <!--<td align='center' colspan=2><button type='button' onClick='modificar()' id='boton_env'>Modificar Monto</button></td>-->
            <td align='center' colspan=2><button type='button' onClick='enviar()' id='boton_env'>Confirmar Cataporte</button></td>  
            </tr>
            </table>";
        break;
        
        case "calcular_monto_cataporte_cheque":
            $_POST['arreglo']=str_replace("\\", "", $_POST['arreglo']);
            $deposito=$conn->ObtenerFilasBySqlSelect("Select * from caja_principal where nro_deposito in (".$_POST['arreglo'].")");
            //creando formulario
            echo "<table border=1 width='25%'>
            <thead class='tb-head'>
            <tr>
                <th colspan=2><center>Cataporte</center></th>
            </tr>
            </thead>
            <tr>
                <td width='40%' align='center'><b>Nro Deposito</b></td>
                <td width='40%' align='center'><b>Monto</b></td>
            </tr>
            ";
            $total=0;
            //$total_format=0;
            foreach ($deposito as $key ) {
                if(isset($key['monto_acumulado_cheque'])){
                $total+=$key['monto_acumulado_cheque'];
                $monto_format=number_format($key['monto_acumulado_cheque'],2,'.', '');//se cambio monto acumulado
                $total_format=number_format($total,2,'.', '');
                
           echo "
            <tr>
                <td width='40%' align='center'><input type='hidden' name=nro_depositos[] value=".$key['nro_deposito']." />".$key['nro_deposito']."</td>
                <td width='40%' align='right'>".$monto_format."</td>
            </tr>";
           }} 
           $bancos=$conn->ObtenerFilasBySqlSelect("SELECT b.nro_cuenta, b.descripcion as cuenta, a.descripcion as banco FROM banco as a inner join  cuentas_contables as b on a.cod_banco=b.banco");
           echo"
            <tr>
                <th><center><b>TOTAL</b><center></th>
                <td align='right'><b>".$total_format."</b></th>
            </tr>
            <tr>
                <th colspan=2><center><b>Seleccione Banco-Cuenta</b></center></th>
            </tr>
            <td colspan='2'>
                <select name='banco' id='banco' >
                <option value=''>Seleccione Banco - Cuenta </option>";
                foreach ($bancos as $key ) {
                    echo "             
                    <option value=".$key['nro_cuenta'].">".$key['cuenta']."- Banco: ".$key['banco']."</option>
                   ";}
                    echo"
                     </select>
            </td>
            </tr>
            <tr>
            </table>";

           echo"
                
            <br>
            <table cellspacing=2>
            <tr>
            <td align='right'><b>Nro. Referencia:</b></td><td><input type='text' name='nro_cataporte' id='nro_cataporte' size=20 onkeyup='aMays(event, this)' onBlur='comprobar(this.id)'/></td>
            <div id='resultado2' width='50%' align='center'></div>
            </tr>
            <tr>
            <td colspan=2><p>&nbsp;</p></td>
            </tr>
            <br>
            <tr id='monto_usuario_tr22' style='visibility:hidden'>
                <td align='center' colspan='2'>&nbsp;</td>
                
            </tr>
            <tr id='monto_usuario_tr' >
                <td align='center'><b>Monto Usuario </b></td>
                <td align='center'><input type='text'  name='monto_usuario' id='monto_usuario' /> </td>
                
            </tr>
            <tr id='monto_usuario_tr1' >
                <td align='center'><b>Observacion </b></td>
                <td align='center'><input type='text'  name='observacion' id='observacion' /> </td>
            </tr>
            
            </table>
            <br><div id='campos_dinamicos'></div>
            <br>
            <table border=0 width='25%' cellspacing=2>
            <tr>
            <!--<td align='center' colspan=2><button type='button' onClick='modificar()' id='boton_env'>Modificar Monto</button></td>-->
            <td align='center' colspan=2><button type='button' onClick='enviar()' id='boton_env'>Confirmar Cataporte</button></td>  
            </tr>
            </table>";
        break;

        case "calcular_monto_cataporte_deposito":
            $_POST['arreglo']=str_replace("\\", "", $_POST['arreglo']);
            $deposito=$conn->ObtenerFilasBySqlSelect("Select b.monto, a.nro_deposito, b.id as id_detalle_deposito  from caja_principal as a inner join caja_principal_depositos as b on a.nro_deposito=b.id_caja_principal where b.id in (".$_POST['arreglo'].")");
            //creando formulario
            echo "<table border=1 width='25%'>
            <thead class='tb-head'>
            <tr>
                <th colspan=2><center>Cierre Deposito</center></th>
            </tr>
            </thead>
            <tr>
                <td width='40%' align='center'><b>Nro Deposito</b></td>
                <td width='40%' align='center'><b>Monto</b></td>
            </tr>
            ";
            
            $total=0;
            //$total_format=0;
            foreach ($deposito as $key ) {
                if(isset($key['monto'])){
                $total+=$key['monto'];
                $monto_format=number_format($key['monto'],2,'.', '');//se cambio monto acumulado
                $total_format=number_format($total,2,'.', '');
                
           echo "
            <tr>
                <td width='40%' align='center'><input type='hidden' name=nro_depositos[] value=".$key['nro_deposito']." />".$key['nro_deposito']."</td>
                <td width='40%' align='right'>".$monto_format."</td>
            </tr>";
           }} 
           $bancos=$conn->ObtenerFilasBySqlSelect("SELECT b.nro_cuenta, b.descripcion as cuenta, a.descripcion as banco FROM banco as a inner join  cuentas_contables as b on a.cod_banco=b.banco");
           echo"
            <tr>
                <th><center><b>TOTAL</b><center></th>
                <td align='right'><b>".$total_format."</b></th>
            </tr>
            <tr>
                <th colspan=2><center><b>Seleccione Banco-Cuenta</b></center></th>
            </tr>
            <td colspan='2'>
                <select name='banco' id='banco' >
                <option value=''>Seleccione Banco - Cuenta </option>";
                foreach ($bancos as $key ) {
                    echo "             
                    <option value=".$key['nro_cuenta'].">".$key['cuenta']."- Banco: ".$key['banco']."</option>
                   ";}
                    echo"
                     </select>
            </td>
            </tr>
            <tr>
            </table>
            <br>
            <table cellspacing=2>
            <tr>
            <td align='right'><b>Nro Referencia:</b></td><td><input type='text' name='nro_cataporte' id='nro_cataporte' size=20 onkeyup='aMays(event, this)' onBlur='comprobar(this.id)'/></td>
            <div id='resultado2' width='50%' align='center'></div>
            </tr>
            
            <tr id='monto_usuario_tr'>
                <td align='center'><b>Monto Usuario </b></td>
                <td align='center'><input type='text'  name='monto_usuario' id='monto_usuario' readonly value='".$total_format."' /> </td>
                
            </tr>
            <tr id='monto_usuario_tr1'>
                <td align='center'><b>Observacion </b></td>
                <td align='center'>
                    <input type='text'  name='observacion' id='observacion' /> 
                    <input type='hidden'  name='id_principal' id='id_principal' value='".$deposito[0]['nro_deposito']."' />
                    <input type='hidden'  name='id_detalle_deposito' id='id_detalle_deposito' value='".$deposito[0]['id_detalle_deposito']."' />
                </td>
            </tr>
            <br>
            <tr>
            <td align='right' style= 'visibility: hidden'><b>Bolsas Cataporte:</b></td>
            <td> <input type='hidden' name='cantidad' id='cantidad' value='' onkeyup='crearCampos(this.value);'  size=3/></center></td>
            </tr>
            <tr id='monto_usuario_tr22' style='visibility:hidden'>
                <td align='center' colspan='2'>&nbsp;</td>
            </tr>
            </table>
            <br><div id='campos_dinamicos'></div>
            <br>
            <table border=0 width='25%' cellspacing=2>
            <tr>
            <!--<td align='center' colspan=2><button type='button' onClick='modificar()' id='boton_env'>Modificar Monto</button></td>-->
            <td align='center' colspan=2><button type='button' onClick='enviar()' id='boton_env'>Confirmar Cataporte</button></td>  
            </tr>
            </table>";
        break;
        
        case "calcular_monto_cataporte_tarjeta":
            $_POST['arreglo']=str_replace("\\", "", $_POST['arreglo']);
            $deposito=$conn->ObtenerFilasBySqlSelect("Select * from caja_principal where nro_deposito in (".$_POST['arreglo'].")");
            //creando formulario
            echo "<table border=1 width='25%'>
            <thead class='tb-head'>
            <tr>
                <th colspan=2><center>Cataporte</center></th>
            </tr>
            </thead>
            <tr>
                <td width='40%' align='center'><b>Nro Deposito</b></td>
                <td width='40%' align='center'><b>Monto</b></td>
            </tr>
            ";
            $total=0;
            //$total_format=0;
            foreach ($deposito as $key ) {
                if(isset($key['monto_acumulado_tarjeta'])){
                $total+=$key['monto_acumulado_tarjeta'];
                $monto_format=number_format($key['monto_acumulado_tarjeta'],2,'.', '');//se cambio monto acumulado
                $total_format=number_format($total,2,'.', '');
                
           echo "
            <tr>
                <td width='40%' align='center'><input type='hidden' name=nro_depositos[] value=".$key['nro_deposito']." />".$key['nro_deposito']."</td>
                <td width='40%' align='right'>".$monto_format."</td>
            </tr>";
           }} echo"
                <tr>
            <th><center><b>TOTAL</b><center></th>
            <td align='right'><b>".$total_format."</b></th>
            </tr>
            </table>
            <br>
            <table cellspacing=2>
            <tr>
            <td align='right'><b>Nro Cataporte:</b></td><td><input type='text' name='nro_cataporte' id='nro_cataporte' size=20 onkeyup='aMays(event, this)' onBlur='comprobar(this.id)'/></td>
            <div id='resultado2' width='50%' align='center'></div>
            </tr>
            <tr>
            <td colspan=2><p>&nbsp;</p></td>
            </tr>
            <br>
            <tr>
            <td align='right'><b>Bolsas Cataporte:</b></td><td> <input type='text' name='cantidad' id='cantidad' value='' onkeyup='crearCampos(this.value);' size=3/></center></td>
            </tr>
            <tr id='monto_usuario_tr22' style='visibility:hidden'>
                <td align='center' colspan='2'>&nbsp;</td>
                
            </tr>
            <tr id='monto_usuario_tr' >
                <td align='center'><b>Monto Usuario </b></td>
                <td align='center'><input type='text'  name='monto_usuario' id='monto_usuario' /> </td>
                
            </tr>
            <tr id='monto_usuario_tr1' >
                <td align='center'><b>Observacion </b></td>
                <td align='center'><input type='text'  name='observacion' id='observacion' /> </td>
            </tr>
            
            </table>
            <br><div id='campos_dinamicos'></div>
            <br>
            <table border=0 width='25%' cellspacing=2>
            <tr>
            <!--<td align='center' colspan=2><button type='button' onClick='modificar()' id='boton_env'>Modificar Monto</button></td>-->
            <td align='center' colspan=2><button type='button' onClick='enviar()' id='boton_env'>Confirmar Cataporte</button></td>  
            </tr>
            </table>";
        break;
        
        case "CuentaPresupuesto":
            $verificar=$conn->ObtenerFilasBySqlSelect("select count(id) as existe from cuenta_presupuestaria where cuenta='".$_GET['v1']."'");
            if($verificar[0]['existe']==0)
            {
                echo 1;
            }
            else
            {
                echo -1;
            }
        break;
        case "tipoCuentaPresupuestoa":
            $verificar=$conn->ObtenerFilasBySqlSelect("select count(id) as existe from tipo_cuenta where tipo_cuenta='".$_GET['v1']."'");
            if($verificar[0]['existe']==0)
            {
                echo 1;
            }
            else
            {
                echo -1;
            }
        break;

case "generarComprobanteMaestro" :

            $login = new Login();
            //variables de suma
            $ingreso=0; $iva1=0;$iva2=0; $iva3=0; $perdida=0; $cxc=0; $otros_ingresos=0; $caja=0;
            //obtener el codigo siga del punto
            $nombre=$conn->ObtenerFilasBySqlSelect("select concat(codigo_siga, '-' , nombre_empresa) as nombre from parametros_generales limit 1");
            //obtener la fecha de apertura
            $fecha=$conn->ObtenerFilasBySqlSelect("select apertura_date as fecha from apertura_tienda  order by id_apertura desc limit 1");
            //buscamos si ya existe el comprobante
            $verificar=$conn->ObtenerFilasBySqlSelect("select id, observacion, ingreso from comprobante where date(fecha)='".$fecha[0]['fecha']."' and (banco='0.00' || banco is null) ");
            //proceso para verificar si hay cajeros pendientes*************************************************
            $operacion_cajero=$conn->ObtenerFilasBySqlSelect("SELECT * FROM `operaciones_apertura` WHERE `operacion`='Cierre de Cajero'");
            $verificar_cajeros_pos="SELECT a.ID, a.NAME FROM $pos.people as a, $pos.log_trans as b  where a.VISIBLE=1 and a.id=b.user and date(DATE)=(select fecha from operaciones order by fecha desc limit 1) and b.DESCRIPTION like '%VENTA RECIBO%' group by b.user order by NAME";

            $array_verificar_cajeros_pos=$conn->ObtenerFilasBySqlSelect($verificar_cajeros_pos);
            $filas_verificar_cajeros_pos=$conn->getFilas($array_verificar_cajeros_pos);

            $verificar_cajeros_pyme="select cod_usuario, usuario from $pyme.usuarios as a, $pyme.factura as b where a.cod_usuario=b.cod_vendedor  and a.vendedor=1 and a.visible_vendedor=1 and date(b.fecha_creacion)=(select fecha from $pyme.operaciones order by fecha desc limit 1) group by cod_usuario";
            $array_verificar_cajeros_pyme=$conn->ObtenerFilasBySqlSelect($verificar_cajeros_pyme);
            $filas_verificar_cajeros_pyme=$conn->getFilas($array_verificar_cajeros_pyme);
            //quedan cajeros pendientes
            if(($filas_verificar_cajeros_pyme>0 || $filas_verificar_cajeros_pos>0) && $operacion_cajero[0]['status']=='1')
            {
                echo -1;
                exit();
            }

                //*************************************************************
            $conn->BeginTrans();
            $mensajecxc="";
            $bandera=0;
            //si existe debe esperar la apertura
            if(isset($verificar[0]['id']) && $verificar[0]['id']!=null)
            {   
                echo -2;
                exit();
                /*$contable_detalle=$conn->ObtenerFilasBySqlSelect("select a.*, b.nombreyapellido from comprobante_detalle as a inner join usuarios b on a.id_usuario=b.cod_usuario   where date(fecha)='".$fecha[0]['fecha']."' and id_comprobante=0");

                if(isset($contable_detalle[0]['id']) && $contable_detalle[0]['id']!=null)
                {
                    
                    foreach ($contable_detalle as $key => $value) 
                    {
                        $ingreso+=$value['ingreso'];
                        $iva1+=$value['iva1'];
                        $iva2+=$value['iva2'];
                        $iva3+=$value['iva3'];
                        $perdida+=$value['perdida'];
                        $cxc+=$value['cxc'];
                        $otros_ingresos+=$value['otros_ingresos'];
                        $caja+=$value['caja'];
                        // si existe cxc se debe saber que cajero fue
                        if($value['cxc']!=0.00)
                        {
                            //para evitar error en el update se previene que el nombre del cajero venga con el simbolo "'"
                            $cajero=$bodytag = str_replace("'", "", $value['nombreyapellido']);
                            $mensajecxc.= $cajero." : ".$value['cxc'].", ";
                        }
                        
                        

                        $updatedetalle=$conn->Execute2("update comprobante_detalle set id_comprobante=".$verificar[0]['id']." where id=".$value['id']);
                    }
                    //buscar mensajes de cxc anteriores 
                    $pos = strrpos($verificar[0]['observacion'], ">");
                    if ($pos != false) 
                    {

                     $mensajecxc.=substr($verificar[0]['observacion'], ($pos+1));
                     
                    }
                    else
                    {
                        //se elimina el ultimo caracter
                        $mensajecxc = substr ($mensajecxc, 0, -2);
                    }
                    //mensaje de observacion
                    $observacion="Ingreso de tienda ".$nombre[0]['nombre']. " la cantidad de ".($ingreso+$verificar[0]['ingreso'])."<br>".$mensajecxc;
                    //se hace el update
                    $update=$conn->Execute2("update comprobante set ingreso=ingreso+".$ingreso.", 
                        iva1=iva1+".$iva1.", iva2=iva2+".$iva2.", iva3=iva3+".$iva3.", perdida=perdida+".$perdida.", 
                        cxc=cxc+".$cxc.", otros_ingresos=otros_ingresos+".$otros_ingresos.", caja=caja+".$caja.", observacion='".$observacion."' where id=".$verificar[0]['id']);
                    $bandera=1;
                }*/
                
                
            }
            else
            {
                
                //insert sino existe
                $sql="select a.*, b.nombreyapellido from comprobante_detalle as a inner join usuarios b on a.cajero=b.cod_usuario where date(a.fecha)>='".$fecha[0]['fecha']."' and a.id_comprobante=0 and a.tipo_venta=0";
                $contable_detalle=$conn->ObtenerFilasBySqlSelect($sql);
                //pos
                $sql="select a.*, b.NAME as nombreyapellido from comprobante_detalle as a inner join ".POS.".people b on a.cajero=b.ID where date(a.fecha)>='".$fecha[0]['fecha']."' and a.id_comprobante=0 and a.tipo_venta=1";
                $contable_detalle_pos=$conn->ObtenerFilasBySqlSelect($sql);
                //unimos los array;

                if(isset($contable_detalle_pos[0]['nombreyapellido']) && $contable_detalle_pos[0]['nombreyapellido']!=null)
                {
                    if(isset($contable_detalle[0]['id']) && $contable_detalle[0]['id']!=null)
                    {
                        $contable_detalle=array_merge($contable_detalle, $contable_detalle_pos);
                    }
                    else
                    {
                        $contable_detalle=$contable_detalle_pos;
                    }
                }
                
                //verificamos que exista comprobantes detalles
                if(isset($contable_detalle[0]['id']) && $contable_detalle[0]['id']!=null)
                {
                    foreach ($contable_detalle as $key => $value) 
                    {
                        $ingreso+=$value['ingreso'];
                        $iva1+=$value['iva1'];
                        $iva2+=$value['iva2'];
                        $iva3+=$value['iva3'];
                        $perdida+=$value['perdida'];
                        $cxc+=$value['cxc'];
                        $otros_ingresos+=$value['otros_ingresos'];
                        $caja+=$value['caja'];
                        // si existe cxc se debe saber que cajero fue
                        if($value['cxc']!=0.00)
                        {
                            //para evitar error en el insert se previene que el nombre del cajero venga con el simbolo "'"
                            $cajero=$bodytag = str_replace("'", "", $value['nombreyapellido']);
                            $mensajecxc.= $cajero." : ".$value['cxc'].", ";
                        }
                    }
                    //se elimina el ultimo caracter
                    $mensajecxc = substr ($mensajecxc, 0, -2);
                    //mensaje de observacion
                    $observacion="Ingreso de tienda ".$nombre[0]['nombre']. " la cantidad de ".$ingreso."<br>".$mensajecxc;
                    $insert=$conn->Execute2("INSERT INTO `comprobante` (`ingreso`,  `iva1`, `iva2`, `iva3`, `perdida`, `cxc`, `otros_ingresos`, `caja`, `id_usuario`, `observacion`, `fecha` ) VALUES ( ".$ingreso.", ".$iva1.", ".$iva2.", ".$iva3.", ".$perdida.", ".$cxc.", ".$otros_ingresos.", ".$caja.", '".$login->getIdUsuario()."', '".$observacion."', '".$fecha[0]['fecha']."' ) ");

                    $id=$conn->ObtenerFilasBySqlSelect("SELECT LAST_INSERT_ID() as id");
                    $totalingresos=0;
                    foreach ($contable_detalle as $key => $value)
                    {
                        $total=$conn->ObtenerFilasBySqlSelect("select sum(monto) as monto from ingresos_detalles where id_comprobante_detalle='".$value['id']."'");
                        $totalingresos+=$total[0]['monto'];
                        $updatedetalle=$conn->Execute2("update comprobante_detalle set id_comprobante=".$id[0]['id']." where id='".$value['id']."'");
                        $updateingresodetalle=$conn->Execute2("update ingresos_detalles set id_comprobante=".$id[0]['id']." where id_comprobante_detalle='".$value['id']."'");
                    }
                    $updatecomprobante=$conn->Execute2("update comprobante set ingreso=".$totalingresos." where id='".$id[0]['id']."'");
                    $bandera=1;
                    //deshabilitando Cajeros POS
                    $sql="UPDATE ".POS.".people SET VISIBLE=0 WHERE ID IN (SELECT id_people FROM ".POS.".people_caja)";
                    $conn->Execute2($sql);

                    //deshabilitando Cajeros PYME
                    $sql="UPDATE usuarios SET visible_vendedor=0 WHERE visible_vendedor=1";
                    $conn->Execute2($sql);
                }//fin del if

            }
            if($bandera==0)
            {
               $conn->Execute2("INSERT INTO `comprobante` (`id_usuario`, `observacion` ) VALUES ( '".$login->getIdUsuario()."', 'Sin ventas' ) ");
            }
            $id=$conn->ObtenerFilasBySqlSelect("select id_apertura from apertura_tienda where cierre='0000-00-00 00:00:00' or cierre is null order by id_apertura desc limit 1");
            $conn->Execute2("update apertura_tienda set cierre=now() where id_apertura='".$id[0]['id_apertura']."'");
            $conn->CommitTrans(1);
            $l=1;
            if($l==1)
            {
                echo "1";
            }
            else
            {
                echo "2";
            }

        break;

       case "calcular_monto_cataporte_tickets":
        $_POST['arreglo']=str_replace("\\", "", $_POST['arreglo']);

        //$deposito=$conn->ObtenerFilasBySqlSelect("Select * from deposito where nro_deposito in (".$_POST['arreglo'].")");
        $deposito=$conn->ObtenerFilasBySqlSelect("Select * from caja_principal where nro_deposito in (".$_POST['arreglo'].")");
//echo "Select * from deposito where nro_deposito in (".$_POST['arreglo'].")"; 

        //creando formulario
        echo "<table border=1 width='25%'>
        <thead class='tb-head'>
        <tr>
            <th colspan=2><center>Cataporte</center></th>
        </tr>
        </thead>
        <tr>
            <td width='40%' align='center'><b>Nro Deposito</b></td>
            <td width='40%' align='center'><b>Monto</b></td>
        </tr>
        ";
        $total=0;
        //$total_format=0;
        foreach ($deposito as $key ) 
        {
            if(isset($key['monto_acumulado_tickets'])){
            $total+=$key['monto_acumulado_tickets'];
            $monto_format=number_format($key['monto_acumulado_tickets'],2,'.', '');//se cambio monto acumulado
            $total_format=number_format($total,2,'.', '');
            
       echo "
        <tr>
            <td width='40%' align='center'><input type='hidden' name=nro_depositos[] value=".$key['nro_deposito']." />".$key['nro_deposito']."</td>
            <td width='40%' align='right'>".$monto_format."</td>
        </tr>";
       }} 
       $bancos=$conn->ObtenerFilasBySqlSelect("SELECT b.nro_cuenta, b.descripcion as cuenta, a.descripcion as banco FROM banco as a inner join  cuentas_contables as b on a.cod_banco=b.banco");
           echo"
            <tr>
                <th><center><b>TOTAL</b><center></th>
                <td align='right'><b>".$total_format."</b></th>
            </tr>
            <tr>
                <th colspan=2><center><b>Seleccione Banco-Cuenta</b></center></th>
            </tr>
            <td colspan='2'>
                <select name='banco' id='banco' >
                <option value=''>Seleccione Banco - Cuenta </option>";
                foreach ($bancos as $key ) {
                    echo "             
                    <option value=".$key['nro_cuenta'].">".$key['cuenta']."- Banco: ".$key['banco']."</option>
                   ";}
                    echo"
                     </select>
            </td>
            </tr>
            <tr>
            </table>";

       echo"
        
        
        <br>
        <table cellspacing=2>
        <tr>
        <td align='right'><b>Nro Referencia:</b></td><td><input type='text' name='nro_cataporte' id='nro_cataporte' size=20 onkeyup='aMays(event, this)' onBlur='comprobar(this.id)'/></td>
        <div id='resultado2' width='50%' align='center'></div>
        </tr>
        <tr>
        <td colspan=2><p>&nbsp;</p></td>
        </tr>
        <br>
        <tr>
        <td align='right'><b>Bolsas Cataporte:</b></td><td> <input type='text' name='cantidad' id='cantidad' value='' onkeyup='crearCampos(this.value);' size=3/></center></td>
        </tr>
        <tr id='monto_usuario_tr22' style='visibility:hidden'>
            <td align='center' colspan='2'>&nbsp;</td>
            
        </tr>
        <tr id='monto_usuario_tr' >
            <td align='center'><b>Monto Usuario </b></td>
            <td align='center'>
                <input type='text'  name='monto_usuario' id='monto_usuario' /> 
                <input type='hiddem'  name='montosistema' id='montosistema' value=".$total_format."/>
            </td>
            
        </tr>
        <tr id='monto_usuario_tr1' >
            <td align='center'><b>Observacion </b></td>
            <td align='center'><input type='text'  name='observacion' id='observacion' /> </td>
        </tr>
        
        </table>
        <br><div id='campos_dinamicos'></div>
        <br>
        <table border=0 width='25%' cellspacing=2>
        <tr>
        <!--<td align='center' colspan=2><button type='button' onClick='modificar()' id='boton_env'>Modificar Monto</button></td>-->
        <td align='center' colspan=2><button type='button' onClick='enviar()' id='boton_env'>Confirmar Cataporte</button></td>  
        </tr>
        </table>";

        

        break;


        case "Validarnrodeposito" :

        $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM deposito WHERE nro_deposito_usuario = '" . $_GET["v1"] . "'");
        echo (count($campos) == 0) ? "1" : "-1";

        break;


       case "transformacionproductos":
            
            if(($_POST['arregloproducto']=='[object Object]' || $_POST['arregloproducto']=='undefined' || $_POST['arregloproducto']==null) && $_POST['cantidad']==2)
            {
                $arreglorubro[0]=$_POST['rubro'];
                $arreglocantidad[0]=$_POST['cantidades'];
                $arreglonombre[0]=$_POST['rubro_nombre'];
            }
            else
            {
                $arreglorubro=explode(",",$_POST['arregloproducto']);
                $arreglocantidad=explode(",",$_POST['arreglocantidad']);
                $arreglonombre=explode(",",$_POST['arreglonombre']);
                $arreglorubro[]=$_POST['rubro'];
                $arreglocantidad[]=$_POST['cantidades'];
                $arreglonombre[]=$_POST['rubro_nombre'];
            }

            $campos_comunes= $conn->ObtenerFilasBySqlSelect("SELECT * FROM item where produccion not in (0,'') and id_item not in ('" . implode($arreglorubro, "', '") . "') order by descripcion1");

            foreach ($campos_comunes as $key => $item) 
            {
                $arraySelectOption[] = $item["id_item"];
                $nombre_proveedor=$item["descripcion1"];
                $arraySelectoutPut[] = utf8_encode($nombre_proveedor);
            }
            for ($i=0;$i<$_POST['cantidad'];$i++)
            {
                echo 
                    "<table >
                        <tr>
                            <td>
                                <!--img align='absmiddle' width='17' height='17' src='../../../includes/imagenes/28.png'-->
                                <span style='font-family:'Verdana';font-weight:bold;'><b>Producto (*)</b></span>
                            </td>
                            <td width='60px'>
                                <select name='rubro1' id='rubro1' class='form-text' onChange='verificar()'>
                                <option value=''> Seleccione una opcion</option>
                                    "; 
                                    foreach ($campos_comunes as $key => $item) 
                                    {
                                       echo "<option value=".$item['id_item']." > ".utf8_encode($item['descripcion1']). "</option>";
                                    }

                                echo "
                                    
                                </select>
                                ";
                                $i=0;
                                foreach ($arreglorubro as $key => $value) 
                                {

                                    echo 
                                    "
                                        <input type='hidden' name='rubro[]' id='rubro[]' value='".$value."'/>
                                        <input type='hidden' name='cantidad[]' id='cantidad[]' value='".$arreglocantidad[$i]."'/>
                                        <input type='hidden' name='nombre[]' id='nombre[]' value='".$arreglonombre[$i]."'/>
                                    ";
                                    $i++;
                                }
                            echo 
                            "
                            </td>
                            <td></td>
                            <td colspan='2' width='600px' align='right'>
                                <table align='right'>
                                    <tr>
                                        <td width='500px' align='center'>
                                            <b>Producto</b>
                                        </td>
                                        <td width ='100px' align='center'>
                                        <b>Cantidad</b>
                                        </td>
                                    </tr>
                                    
                                    ";
                                    $i=0;
                                    foreach ($arreglonombre as $key => $value) 
                                    {
                                        echo "
                                        <tr>
                                            <td width='500px' align='center'>
                                                ".$value." 
                                            </td>
                                            <td width ='100px' align='center'>
                                                ".$arreglocantidad[$i]."
                                            </td>
                                        </tr>
                                            ";
                                        $i++;
                                    };
                                    echo
                                   "
                                </table>
                            </td>
                        <tr>
                            <td>
                                <!--img align='absmiddle' width='17' height='17' src='../../../includes/imagenes/28.png'-->
                                <span style='font-family:'Verdana';font-weight:bold;'><b>Cantidad (*)</b></span>
                            </td>
                            <td>
                                <input type='numeric' name='cantidad1' maxlength='100' id='cantidad1' size='30' maxlength='70' class='form-text' onkeyup='verificar()'/>
                            </td>
                        </tr>
                    </table>";
            }
        break;

        case "actualizar_precio_producto2_codigo":
            $comunes = new Comunes();
            $pos=POS;
            $prod=$_GET[prod];
            $precio=$_GET[precio];
            $trans=$_GET[trans];
            $option=$_GET['option'];
            $codigo=$_GET['codigo'];
            $hoy = getdate();
            $fecha=$hoy['year']."-".$hoy['mon']."-".$hoy['mday']." ".$hoy['hours'].":".$hoy['minutes'].":".$hoy['seconds'];
            //$comunes=$_GET[id_cone];
            if($option==1 || $option==3)
            {
                $sql="UPDATE item SET precio1=".$precio.", coniva1=".$precio.", precio2=".$precio.", coniva2=".$precio.", precio3=".$precio.", coniva3=".$precio." WHERE codigo_barras='".$prod."'";
                $comunes->Execute2($sql);
            }

            if($option==2 || $option==3)
            {
                $sql="UPDATE $pos.products SET PRICEBUY=".$precio.", PRICESELL=".$precio." WHERE CODE='".$prod."'";
                $comunes->Execute2($sql);
            }

            
            $sql_estatus="UPDATE sincronizacion_productos_detalle SET estatus=0, usuario_ejecucion='".$login->getUsuario()."', fecha_ejecucion=CURRENT_TIMESTAMP WHERE codigo_barra='".$prod."' and id_sincro=".$trans."";
            $comunes->Execute2($sql_estatus);

            $id=$comunes->Execute2("SELECT LAST_INSERT_ID()");
            
            $sql_codigo="insert into codigo_kardex (codigo, id_movimiento, tipo_moviento, usuario, fecha) values ('".$codigo."','".$id[0]['LAST_INSERT_ID()']."', '11',  '".$login->getUsuario()."', '".$fecha."' )";

            $comunes->Execute2($sql_codigo);
            //print_r($comunes);

        break;
        

        case "getCuentas" :
            
            $sql="SELECT id, concat(nro_cuenta,'-', descripcion) as cuenta  from cuentas_contables where banco='".$_GET["banco"]."'";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);

            if (count($campos) == 0) 
            {
                echo "-1"; exit();
            } else 
            {
                echo json_encode($campos);
            }
        break;

         case "generarCodigoKardex" :
            
            $mes=date('m');
            $dia=date('d');
            $arraynumero=str_split($_POST['siga']);
            //generar codigo siga con suma;
            $puntosuma=($arraynumero[3]+$arraynumero[4]+$arraynumero[5])+$_POST['siga'];
            $i=0;
            $login = new Login();
            while($i!=1)
            {
                $primer=rand(10, 99);
                $hexa=dechex($primer+$puntosuma);
                $codigo=$mes.$primer.$dia.$puntosuma.$hexa;
                $consultar=$conn->ObtenerFilasBySqlSelect("select codigo from codigo_autorizacion where codigo='".$codigo."'");
                if($consultar==null)
                {
                    $insertar=$conn->Execute2('insert into codigo_autorizacion (codigo, punto, fecha, usuario) values ("'.$codigo.'","'.$_POST['siga'].'", now(), "'.$login->getNombreApellidoUSuario().'" )');
                    $i=1;
                }
            }
            echo $codigo; exit();
        
        break;

        case "getCodigoKardex" :

            $resultado=$conn->ObtenerFilasBySqlSelect("select codigo_kardex from parametros_generales");
            if($resultado[0]['codigo_kardex']==1)
            {
                echo "1";
            }
            else
            {
                echo "0";
            }

        break;

       case "getverificacionCodigo" :
            if(isset($_GET['codigo']) && $_GET['codigo']!="")
            {
                $arraynumero=str_split($_GET['codigo']);
                $tamanio=count($arraynumero);
                $mes=$arraynumero[0].$arraynumero[1];
                $dia=$arraynumero[4].$arraynumero[5];
                $dia_real=date('d');
                $mes_real=date('m');
                $datetime1 = new DateTime(date('Y')."-".$mes."-".$dia);
                $datetime2 = new DateTime(date('Y')."-".$mes_real."-".$dia_real);
                $interval = $datetime1->diff($datetime2);
                $dias=$interval->d;
                if($mes!=$mes_real)
                {
                    echo "-4"; exit();
                }
                elseif($dias>3 || $dia>$dia_real)
                {
                    echo "-4"; exit();
                }
                else
                {
                    //proceso de la suma del siga
                    $siga=$conn->ObtenerFilasBySqlSelect("select codigo_siga from parametros_generales");
                    $arraysiga=str_split($siga[0]['codigo_siga']);
                    $resultadosum=$arraysiga[0]+$arraysiga[1]+$arraysiga[2]+$siga[0]['codigo_siga'];
                    if($arraynumero[6].$arraynumero[7].$arraynumero[8]!=$resultadosum)
                    {
                        echo "-3"; exit();
                    }
                    
                    $sumaaleatoriosiga=($arraynumero[2].$arraynumero[3])+$resultadosum;
                    $hexa_sistema=dechex($sumaaleatoriosiga);
                    $cantidad=$tamanio-8;
                    $hexa_archivo="";
                    for($i=1; $i<=$cantidad; $i++)
                    {
                        $hexa_archivo.=$arraynumero[8+$i];
                    }
                    //proceso de comparar hexadecimal
                    if($hexa_archivo!=$hexa_sistema)
                    {
                        echo "-3"; exit();
                    }
                    
                    $comprobar=$conn->ObtenerFilasBySqlSelect("select id from codigo_kardex where codigo='".$_GET['codigo']."'");
                    if($comprobar!=null)
                    {
                        echo "-1"; exit();//ya usado
                    }
                    else
                    {
                        echo "1"; exit();//codigo aceptado
                    }
                }
            }
            else
            {
                echo "-2"; //vacio
            }
        break;

        case "obtCantidadClap":
            $consulta=$conn->ObtenerFilasBySqlSelect();
        break;
        case "filtroItemByRCCBCesta":
            /**
             * Procedimiento de busqueda de productos/servicios
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      lviera@armadillotec.com
             *      levieraf@gmail.com
             *
             */
            /**
             * Procedimiento de busqueda de productos/servicios
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      lviera@armadillotec.com
             *      levieraf@gmail.com
             *
             */
            $tipo_item = (isset($_POST["cmb_tipo_item"])) ? $_POST["cmb_tipo_item"] : 1;
                
            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {
                //filtro para productos
                if ($tipo_item == 1) {
                    $codigo = (isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "";
                    $codigo_barras = (isset($_POST["codigoBarrasProducto"])) ? $_POST["codigoBarrasProducto"] : "";
                    $descripcion = (!isset($_POST["descripcionProducto"])) ? "" : $_POST["descripcionProducto"];
                    $referencia = (!isset($_POST["referencia"])) ? "" : $_POST["referencia"];

                    $andWHERE = " and ";
                    if ($codigo != "") {
                        $andWHERE .= " ( cod_item = '" . $codigo . "' or id_item  = '".$codigo."') ";
                        $entrada_codigo=true;
                    }

################################################################################
                    if ($codigo_barras != "") {
                        if ($codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }

                        $andWHERE .= " upper(codigo_barras) like upper('%" . $codigo_barras . "%')";
                    }

                    if ($referencia != "") {
                        if ($codigo_barras != "" || $codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(referencia) like upper('%" . $referencia . "%')";
                    }

################################################################################
                    if ($descripcion != "") {
                        if ($codigo_barras != "" || $referencia != "" || $codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(descripcion1) like upper('%" . $descripcion . "%')";
                    }
                    if ($codigo == "" && $descripcion == "" && $codigo_barras == "" && $referencia == "") {
                        $andWHERE = "";
                    }
                    //echo "HOLA".$andWHERE;
                    //exit;
                }

                //filtro para productos
                if ($tipo_item == 2) {
                    $codigo = (isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "";
                    $descripcion = (!isset($_POST["descripcionProducto"])) ? "" : $_POST["descripcionProducto"];

                    $andWHERE = " and ";
                    if ($codigo != "") {
                        $andWHERE .= " upper(cod_item) = upper('" . $codigo . "')";
                    }
                    if ($descripcion != "") {
                        if ($codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(descripcion1) like upper('%" . $descripcion . "%')";
                    }
                    if ($codigo == "" && $descripcion == "") {
                        $andWHERE = "";
                    }
                }
                    
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                    
                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item . " " . $andWHERE . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            } else {
                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item;
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            }
            $cantidad=$conn->ObtenerFilasBySqlSelect("select (a.cantidad*b.cantidad) as cantidad from cesta_clap as a inner join cesta_clap_detalle b on a.id=b.id_cesta where b.id_item=".$campos_comunes[0]["id_item"]. " and a.id=".$_POST['cestamaestro']);

            $campos_comunes[0]['desdeb1']=$cantidad[0]['cantidad'];
            echo json_encode(
                $campos_comunes
            );

            break;

        case "cestadatosfactura":
            $id=$_GET["maestroid"];
            $sql="SELECT c.id_item, c.descripcion1, (b.cantidad*a.cantidad) as cantidad from  cesta_clap a inner join cesta_clap_detalle b on a.id=b.id_cesta  inner join item c on b.id_item=c.id_item where b.id_cesta=".$id." group by b.id_item"; 
           
            $campos = $conn->ObtenerFilasBySqlSelect($sql);

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }

        break;
        case "eliminarClapDetalle" :
            $result=$conn->Execute2("delete from cesta_clap_detalle where id=".$_POST['id']);
            print_r($result);
        break;

        case "det_items_cesta_clap":
            $campos= $conn->ObtenerFilasBySqlSelect("select b.id, c.codigo_barras, c.descripcion1, m.marca, c.pesoxunidad, d.nombre_unidad, b.cantidad from cesta_clap as a, cesta_clap_detalle as b, item as c LEFT JOIN marca m ON c.id_marca= m.id, unidad_medida as d where b.id_item=c.id_item and a.id=b.id_cesta and c.unidadxpeso=d.id and a.id=".$_GET['id_transaccion']);
 
            echo 
                '<tr class="detalle_items">
                    <td colspan="8">
                        <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                            <table >
                                <thead>
                        
                                    <th style="width:150px; font-weight: bold; text-align: center;">Codigo Barras</th>
                                    <th style="width:350px; font-weight: bold; text-align: center;">Nombre</th>
                                    <th style="width:150px; font-weight: bold; text-align: center;">Cantidad</th>
                                    <th style="width:110px; font-weight: bold; text-align: center;">Acciones</th>
                                    
                                </thead>
                                <tbody>';
                                foreach ($campos as $key => $item) {
                
                                    echo '
                                        <tr>
                                            <td style="width:150px; padding-left:10px;">
                                            ' . $item["codigo_barras"] . '
                                            </td>

                                            <td style="width:110px; text-align: center; padding-right:10px;">
                                            ' .$item["descripcion1"].' - '.$item["marca"].' '.$item["pesoxunidad"].' '.$item["nombre_unidad"].
                                            '</td>

                                            <td style="width:150px; padding-left:10px; text-align: center;">
                                                ' . $item["cantidad"] . '
                                            </td>

                                            <td style="width:150px; padding-left:10px; text-align: center;">
                                                <img class="editar" id='.$item['id'].' width="20" height="20" onclick="eliminarDetalleClap(this)" title="Eliminar"src="../../../includes/imagenes/delete.gif" />
                             
                                            </td>

                                            
                                        </tr>';
                                }
                               echo '
                                </tbody
                            </table>
                        </div>
                    </td>
                </tr>
            ';
        break;


        case "GetDisponibleClap" :
            //si los numeros no son mayores a 0 debe indicar un error
            if($_GET['cantidadCombo'] > 0 && $_GET['cantidadunitaria'] > 0)
            {
                $ubicacion= $conn->ObtenerFilasBySqlSelect("Select cod_almacen, id_ubicacion from parametros_generales");

                $campos = $conn->ObtenerFilasBySqlSelect("SELECT cantidad from item_existencia_almacen where cod_almacen='".$ubicacion[0]['cod_almacen']. "' and id_ubicacion='".$ubicacion[0]['id_ubicacion']."' and id_item='".$_GET['idItem']."'");

                //si el pedido es mayor a la cantidad existente debe indicar un error
                if(($_GET['cantidadCombo']*$_GET['cantidadunitaria'])>$campos[0]['cantidad'])
                {
                    echo 0;
                }
                else
                {
                    echo 1;
                }
            }
            else
            {
                echo 3;
            }

        break;

        case "actualizarTomaFile" :
            
            
            if($_POST['boton']==1)
            {
                $toma_vacia="toma1";
                $toma='toma1';
                $buton1="";
                $buton2="disabled";
                $buton3="disabled";
                //verificar si existe archivo en la toma 1
                if(isset($_FILES['archivo_productos1']['tmp_name']) && !empty($_FILES['archivo_productos1']['tmp_name']))
                {
                    //Verificar que sea la extension correcta
                    $string=".txt";
                    $respuesta="";
                    if(strnatcasecmp($string,substr($_FILES['archivo_productos1']['name'],-4))!=0)
                    {
                        // retorno 1 es error de extension
                        echo "1";
                        exit();
                    }

                    
                    $fp = fopen($_FILES['archivo_productos1']['tmp_name'], 'rb');
                        
                        while ( ($line = fgets($fp)) !== false) {
                        $array=explode(",", $line);

                            //verificamos que las dos primeras variables sean numeros
                            if(is_numeric($array[0]) && is_numeric($array[1]))
                            {   //verificamos que si existe la tercera variable, ya que puede darse el caso que no exista
                                if(isset($array[2]))
                                {
                                    $array[2]=trim($array[2]);
                                   //si existe verificamos que sea numero
                                    if(is_numeric($array[2]))
                                    {
                                        $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'],$toma_vacia);
                                    }
                                    else
                                    {   
                                        //estructura de archivo incorrecto no es numero
                                        echo "2"; exit();
                                    }
                                }
                                else
                                {
                                    $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'], $toma_vacia);
                                }
                            }
                            else
                            {
                                //estructura de archivo incorrecto, no es numero
                                echo "2"; exit();
                            }

                            if($respuesta==3)
                            {   //codigo de producto no existe
                                echo "3"; exit();
                            }
                          
                        } // fin del while

                
                }//fin del if de archivo *************************************************

            }//fin del toma 1
            else
            {
                if($_POST['boton']==2)
                {   
                    $toma='toma2';
                    $buton1="disabled";
                    $buton2="";
                    $buton3="disabled";
                    $toma_vacia="toma2";
                    //verificar si existe archivo en la toma 1
                if(isset($_FILES['archivo_productos2']['tmp_name']) && !empty($_FILES['archivo_productos2']['tmp_name']))
                {
                    //Verificar que sea la extension correcta
                    
                    $string=".txt";
                    $respuesta="";
                    if(strnatcasecmp($string,substr($_FILES['archivo_productos2']['name'],-4))!=0)
                    {
                        // retorno 1 es error de extension
                        echo "1";
                        exit();
                    }

                    
                    $fp = fopen($_FILES['archivo_productos2']['tmp_name'], 'rb');
                        
                        while ( ($line = fgets($fp)) !== false) {
                        $array=explode(",", $line);

                            //verificamos que las dos primeras variables sean numeros
                            if(is_numeric($array[0]) && is_numeric($array[1]))
                            {   //verificamos que si existe la tercera variable, ya que puede darse el caso que no exista
                                if(isset($array[2]))
                                {
                                    $array[2]=trim($array[2]);
                                   //si existe verificamos que sea numero
                                    if(is_numeric($array[2]))
                                    {
                                        $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'],$toma_vacia);
                                    }
                                    else
                                    {   
                                        //estructura de archivo incorrecto no es numero
                                        echo "2"; exit();
                                    }
                                }
                                else
                                {
                                    $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'], $toma_vacia);
                                }
                            }
                            else
                            {
                                //estructura de archivo incorrecto, no es numero
                                echo "2"; exit();
                            }

                            if($respuesta==3)
                            {   //codigo de producto no existe
                                echo "3"; exit();
                            }
                          
                        } // fin del while

                
                }//fin del if de archivo *************************************************
                }
                else
                {
                    $toma_vacia="tomadef";
                    $toma='tomadef';
                    $buton1="disabled";
                    $buton2="disabled";
                    $buton3="";
                    //verificar si existe archivo en la toma 1
                if(isset($_FILES['archivo_productos3']['tmp_name']) && !empty($_FILES['archivo_productos3']['tmp_name']))
                {
                    //Verificar que sea la extension correcta
                    $string=".txt";
                    $respuesta="";
                    if(strnatcasecmp($string,substr($_FILES['archivo_productos3']['name'],-4))!=0)
                    {
                        // retorno 1 es error de extension
                        echo "1";
                        exit();
                    }

                    
                    $fp = fopen($_FILES['archivo_productos3']['tmp_name'], 'rb');
                        
                        while ( ($line = fgets($fp)) !== false) {
                        $array=explode(",", $line);

                            //verificamos que las dos primeras variables sean numeros
                            if(is_numeric($array[0]) && is_numeric($array[1]))
                            {   //verificamos que si existe la tercera variable, ya que puede darse el caso que no exista
                                if(isset($array[2]))
                                {
                                    $array[2]=trim($array[2]);
                                   //si existe verificamos que sea numero
                                    if(is_numeric($array[2]))
                                    {
                                        $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'],$toma_vacia);
                                    }
                                    else
                                    {   
                                        //estructura de archivo incorrecto no es numero
                                        echo "2"; exit();
                                    }
                                }
                                else
                                {
                                    $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'], $toma_vacia);
                                }
                            }
                            else
                            {
                                //estructura de archivo incorrecto, no es numero
                                echo "2"; exit();
                            }

                            if($respuesta==3)
                            {   //codigo de producto no existe
                                echo "3"; exit();
                            }
                            
                          
                        } // fin del while

                
                }//fin del if de archivo *************************************************
                }
            }

            $formulario="";
            if($toma_fisica[0]['toma']!=4)
            {
                $formulario="
                <table   width='100%' border='0' align='center'>
                <tr>
                <td class='btn' style='float:center; padding-right: 15px;' colspan='1' border=0>
                
                <div id=boton_agregar> 
                <table class='btn_bg' onclick='agregarProducto()' align='center' border='0'>
                    <tr style='border-width: 0px; cursor: pointer;'>
                        <td><img src='../../../includes/imagenes/bt_left.gif' style='border-width: 0px; width: 4px; height: 21px;' />
                        </td>
                        <td><img src='../../../includes/imagenes/add.gif' width='16' height='16' />
                        </td>
                        <td style='padding: 0px 6px;'>Agregar Producto
                        </td>
                        <td><img src='../../../includes/imagenes/bt_right.gif' style='border-width: 0px; width: 4px; height: 21px;' />
                        </td>
                    </tr>
                </table>
                </div>
                
                </td>
                </tr>
                </table>";
            }
            $formulario.="
            <form name='formulario2' id='formulario2' method='post' enctype='multipart/form-data'>
            <table   width='100%' border='0' >
                <thead>
                    <tr class='tb-head'>
                        <th style='width:220px;' >Codigo de Barras</th>
                        <th style='width:200px;'>Producto</th>
                        <th style='text-align: center;'>Toma 1</th>
                        <th style='text-align: center;'>Toma 2</th>
                        <th style='text-align: center;'>Toma Def.</th>
                    </tr>
                </thead>

                <tbody>
                    ";

            $consulta=$conn->ObtenerFilasBySqlSelect("SELECT a.*,b.descripcion1 as nombre_producto from tomas_fisicas_detalle a, item b WHERE a.cod_bar=b.codigo_barras and a.id_llenado=1 and id_mov=".$_POST['id_mov']."");
            foreach ($consulta as $key => $value) {

                $formulario.=
                "
                <input type='hidden' name='tiene_filtro' id='tiene_filtro' value='1'/>
                <input type='hidden' name='ubicacion' id='tiene_filtro' value=''/>
                <input type='hidden' name='fecha_apertura' id='fecha_apertura' value=''/>
                <input type='hidden' name='tipo_toma' id='tipo_toma' value=''/>
                
                
                <tr>
                    <td style='padding-top:2px; padding-bottom: 2px;'>
                        
                        <input type='text' name='codigo_barras[]' id='codigo_barras[]' style='float: left;' class='form-text' value='".$value['cod_bar']."' readonly='readonly'/>
                    </td>
                    <td style='padding-top:2px; padding-bottom: 2px;'>
                        <input class='form-text' type='text' name='precio{counter}' size='50' id='precio{counter}' value='".$value['nombre_producto']."' readonly='readonly'>
                    </td>            

                    <td style='padding-top:2px; padding-bottom: 2px; text-align: center;'>
                        <input class='form-text' type='text' name='toma1[]' size='10' id='toma1[]' value='".$value['toma1']."' readonly>
                    </td>

                    <td style='text-align: center;'>
                        <input class='form-text' type='text' name='toma2[]' size='10' id='toma2[]' value='".$value['toma2']."' readonly='readonly'>
                    </td>
                    <td style='text-align: center;'>
                        <input class='form-text' type='text' name='tomadef[]' size='10' id='tomadef[]' value='".$value['tomadef']."' readonly='readonly'>
                    </td>
                    
                </tr>
                ";
            }


            $formulario.= "    
                <tr class='tb-head'>
                    <td colspan=2>
                        <input type='hidden' name='id_mov' id='id_mov' value='".$_POST['id_mov']."'/>
                    </td>
                    
            <!--botones primera opcions -->
            <td align='left'>
                <div id='ejecutar1' style='display: none;' >
                    <img src='../../../includes/imagenes/ok.gif' onclick='cargarArchivo(1)' style='width: 15px; heigth:15px; margin-left: 50px;' alt='Cargar Archivo' title='Cargar Archivo'>
                    <img src='../../../includes/imagenes/ico_delete.gif' style='width: 15px; heigth:15px; margin-left: 50px;' onclick='cancelar(1)' alt='Cancelar' title='Cancelar'>
                    <br><br>
                    
                </div>
                <div id='file1'>
                    <input type='file' name='archivo_productos1' id='archivo_productos1' style='margin-left: 50px; width:80px'   ".$buton1." accept='txt/*' onchange='carga(1)'></input>
                    <br><br>
                </div>
                
                <input type='button'  style='margin-right:80px;' class='form-text' id='enviarajax' name='toma1_submit' value='Cerrar' align='center' ".$buton1." onclick='recargar(1)'/>
            </td>

            <!--botones segunda opcions -->
            <td>
                <div id='ejecutar2' style='display: none;' >
                    <img src='../../../includes/imagenes/ok.gif' onclick='cargarArchivo(2)' style='width: 15px; heigth:15px; margin-left: 50px;'/>
                    <img src='../../../includes/imagenes/ico_delete.gif' style='width: 15px; heigth:15px; margin-left: 50px;' onclick='cancelar(2)' />
                    <br><br>
                </div>

                <div id='file2'>
                    <input type='file' name='archivo_productos2' id='archivo_productos2' style='margin-left: 50px; width:80px'   ".$buton2." accept='txt/*' onchange='carga(2)'></input>
                    <br><br>
                </div>
                
                    <input type='button' id='enviarajax'   style='margin-right: 80px;' class='form-text' name='toma2_submit' value='Cerrar' ".$buton2." onclick='recargar(2)'/>
                
            </td>

            <!--botones tercera opcions -->
            <td align='left'>
                <div id='ejecutar3' style='display: none;' >
                    <img src='../../../includes/imagenes/ok.gif' onclick='cargarArchivo(3)' style='width: 15px; heigth:15px; margin-left: 50px;'/>
                    <img src='../../../includes/imagenes/ico_delete.gif' style='width: 15px; heigth:15px; margin-left: 50px;' onclick='cancelar(3)' />
                    <br><br>
                </div>
                <div id='file3'>
                    <input type='file' name='archivo_productos3' id='archivo_productos3' style='margin-left: 50px; width:80px'   ".$buton3." accept='txt/*' onchange='carga(3)'></input>
                    <br><br>
                </div>
                <input type='button' id='enviarajax' style='margin-right: 80px;' class='form-text' name='toma3_submit' value='Cerrar' ".$buton3." onclick='recargar(3)'/>
            </td>
                                        
                </tr>
            </form>
            ";

            echo $formulario;

        break;

        case "calidadObservacion" :
        if($_POST['cantidad']==0)
        {
        echo "
        <table width='100%' align='left' style='margin-left: -265px;' border='0'>
            <tr >
                <td align='right'>
                Observacion**&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                <input type='text' size='50' name='observacion[]' class='form-text' id=observacion[]/>
                </td>
            </tr>
            <tr>
                <td align='right'>
                Recomendacion**&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                <input type='text' size='50' name='recomendacion[]'  class='form-text' id=recomendacion[]/>
                </td>
            </tr>
            <input type='hidden' name='cantidad_ob' id='cantidad_ob' value='".($_POST['cantidad']+1)."' />
        </table>
        ";
        }
        else
        {
            
            
                
            $i=0;
            $tabla=
                "               
                <table width='100%' align='left' style='margin-left: -265px;' border='0'>
                ";
            while($i<$_POST['cantidad']){
                $tabla.="<tr>
                        <td align='right'>
                        Observacion**&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td>
                        <input type='text' size='50' name='observacion[]' class='form-text' id=observacion[] value=''/>
                        </td>
                    </tr>
                    <tr>
                        <td align='right'>
                        Recomendacion**&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td>
                        <input type='text' size='50' name='recomendacion[]'  class='form-text' id=recomendacion[] value=''/>
                        </td>
                    </tr>
                    ";
            $i++;
            }
            $tabla.=
                "
                    <input type='hidden' name='cantidad_ob' id='cantidad_ob' value='".($_POST['cantidad']+1)."' />
                </table>
                ";
                
            echo $tabla;
            
        }


        break;

        
         case "actualizar_toma" :
        
    if($_POST['boton']==1)
    {
        $toma_vacia="toma1";
        //verificar si existe archivo en la toma 1
        if(isset($_FILES['archivo_productos1']['tmp_name']) && !empty($_FILES['archivo_productos1']['tmp_name']))
        {
            //Verificar que sea la extension correcta
            $string=".txt";
            $respuesta="";
            if(strnatcasecmp($string,substr($_FILES['archivo_productos1']['name'],-4))!=0)
            {
                // retorno 1 es error de extension
                echo "1";
                exit();
            }

            
            $fp = fopen($_FILES['archivo_productos1']['tmp_name'], 'rb');
                
                while ( ($line = fgets($fp)) !== false) {
                $array=explode(",", $line);

                    //verificamos que las dos primeras variables sean numeros
                    if(is_numeric($array[0]) && is_numeric($array[1]))
                    {   //verificamos que si existe la tercera variable, ya que puede darse el caso que no exista
                        if(isset($array[2]))
                        {
                            $array[2]=trim($array[2]);
                           //si existe verificamos que sea numero
                            if(is_numeric($array[2]))
                            {
                                $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'],$toma_vacia);
                            }
                            else
                            {   
                                //estructura de archivo incorrecto no es numero
                                echo "2"; exit();
                            }
                        }
                        else
                        {
                            $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'], $toma_vacia);
                        }
                    }
                    else
                    {
                        //estructura de archivo incorrecto, no es numero
                        echo "2"; exit();
                    }

                    if($respuesta==3)
                    {   //codigo de producto no existe
                        echo "3"; exit();
                    }
                  
                } // fin del while

        
        }//fin del if de archivo *************************************************

    }//fin del toma 1
    else
    {
        if($_POST['boton']==2)
        {
            $toma_vacia="toma2";
            //verificar si existe archivo en la toma 1
        if(isset($_FILES['archivo_productos2']['tmp_name']) && !empty($_FILES['archivo_productos2']['tmp_name']))
        {
            //Verificar que sea la extension correcta
            $string=".txt";
            $respuesta="";
            if(strnatcasecmp($string,substr($_FILES['archivo_productos2']['name'],-4))!=0)
            {
                // retorno 1 es error de extension
                echo "1";
                exit();
            }

            
            $fp = fopen($_FILES['archivo_productos2']['tmp_name'], 'rb');
                
                while ( ($line = fgets($fp)) !== false) {
                $array=explode(",", $line);

                    //verificamos que las dos primeras variables sean numeros
                    if(is_numeric($array[0]) && is_numeric($array[1]))
                    {   //verificamos que si existe la tercera variable, ya que puede darse el caso que no exista
                        if(isset($array[2]))
                        {
                            $array[2]=trim($array[2]);
                           //si existe verificamos que sea numero
                            if(is_numeric($array[2]))
                            {
                                $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'],$toma_vacia);
                            }
                            else
                            {   
                                //estructura de archivo incorrecto no es numero
                                echo "2"; exit();
                            }
                        }
                        else
                        {
                            $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'], $toma_vacia);
                        }
                    }
                    else
                    {
                        //estructura de archivo incorrecto, no es numero
                        echo "2"; exit();
                    }

                    if($respuesta==3)
                    {   //codigo de producto no existe
                        echo "3"; exit();
                    }
                  
                } // fin del while

        
        }//fin del if de archivo *************************************************
        }
        else
        {
            $toma_vacia="tomadef";
            //verificar si existe archivo en la toma 1
        if(isset($_FILES['archivo_productos3']['tmp_name']) && !empty($_FILES['archivo_productos3']['tmp_name']))
        {
            //Verificar que sea la extension correcta
            $string=".txt";
            $respuesta="";
            if(strnatcasecmp($string,substr($_FILES['archivo_productos3']['name'],-4))!=0)
            {
                // retorno 1 es error de extension
                echo "1";
                exit();
            }

            
            $fp = fopen($_FILES['archivo_productos3']['tmp_name'], 'rb');
                
                while ( ($line = fgets($fp)) !== false) {
                $array=explode(",", $line);

                    //verificamos que las dos primeras variables sean numeros
                    if(is_numeric($array[0]) && is_numeric($array[1]))
                    {   //verificamos que si existe la tercera variable, ya que puede darse el caso que no exista
                        if(isset($array[2]))
                        {
                            $array[2]=trim($array[2]);
                           //si existe verificamos que sea numero
                            if(is_numeric($array[2]))
                            {
                                $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'],$toma_vacia);
                            }
                            else
                            {   
                                //estructura de archivo incorrecto no es numero
                                echo "2"; exit();
                            }
                        }
                        else
                        {
                            $respuesta = InsertarDatoArchivo($array, $_POST['id_mov'], $toma_vacia);
                        }
                    }
                    else
                    {
                        //estructura de archivo incorrecto, no es numero
                        echo "2"; exit();
                    }

                    if($respuesta==3)
                    {   //codigo de producto no existe
                        echo "3"; exit();
                    }
                    
                  
                } // fin del while

        
        }//fin del if de archivo *************************************************
        }
    }

        $actualizar=$conn->Execute2("update tomas_fisicas set toma=".($_POST['boton']+1)." where id=".$_POST['id_mov']);

        
        //toma que se esta trabajando
        $toma_fisica=$conn->ObtenerFilasBySqlSelect("select toma from tomas_fisicas where id=".$_POST['id_mov']);
        
                
        
        if($toma_fisica[0]['toma']==1)
        {
            $toma='toma1';
            $buton1="";
            $buton2="disabled";
            $buton3="disabled";
        }
        if($toma_fisica[0]['toma']==2)
        {
            $toma='toma2';
            $buton1="disabled";
            $buton2="";
            $buton3="disabled";
        }
        if($toma_fisica[0]['toma']==3)
        {
            $toma='tomadef';
            $buton1="disabled";
            $buton2="disabled";
            $buton3="";

        }

        if($toma_fisica[0]['toma']==4)
        {
            $toma='';
            $buton1="disabled";
            $buton2="disabled";
            $buton3="disabled";
            //ver inv en caso de mov_sug
        
        $inv_tomas_vacias=$conn->ObtenerFilasBySqlSelect("select inv_sistema, id from tomas_fisicas_detalle where id_mov=".$_POST['id_mov']." and tomadef is null");

        foreach ($inv_tomas_vacias as $key => $value) 
        {
        $update=$conn->Execute2("update tomas_fisicas_detalle set mov_sugerido=".($value['inv_sistema']*-1)."  where id=".$value['id']);
        }
        }
        
        $actualizar_detalles=$conn->Execute2("update tomas_fisicas_detalle set ".$toma_vacia."=0 where ".$toma_vacia." is null and id_mov=".$_POST['id_mov']);
        
        //realizar chequeo de tomas iguales
        if($toma=='toma2')
        {
            
            $buscar=$conn->ObtenerFilasBySqlSelect("select toma1, inv_sistema, id from tomas_fisicas_detalle where id_mov=".$_POST['id_mov']);
            foreach ($buscar as $key => $value) 
            {
                
            if($value['toma1']==$value['inv_sistema'])
            {
            $insert=$conn->Execute2("update tomas_fisicas_detalle set toma2=".$value['toma1']." where id=".$value['id']);       
            }
            }//end foreach
            
        }//end if toma1

        //realizar chequeo de tomas iguales
        if($toma=='tomadef')
        {

            $buscar=$conn->ObtenerFilasBySqlSelect("select toma1, toma2, inv_sistema, id from tomas_fisicas_detalle where id_mov=".$_POST['id_mov']);

            foreach ($buscar as $key => $value) 
            {
                
            if($value['toma1']==$value['toma2'])
            {
            $insert=$conn->Execute2("update tomas_fisicas_detalle set tomadef=".$value['toma1'].", mov_sugerido=".($value['toma1']-$value['inv_sistema'])." where id=".$value['id']);       
            }
            }//end foreach
            
        }//end if toma2

        $formulario="";
        if($toma_fisica[0]['toma']!=4)
        {
        $formulario="
        <table   width='100%' border='0' align='center'>
        <tr>
        <td class='btn' style='float:center; padding-right: 15px;' colspan='1' border=0>
        
        <div id=boton_agregar> 
        <table class='btn_bg' onclick='agregarProducto()' align='center' border='0'>
            <tr style='border-width: 0px; cursor: pointer;'>
                <td><img src='../../../includes/imagenes/bt_left.gif' style='border-width: 0px; width: 4px; height: 21px;' />
                </td>
                <td><img src='../../../includes/imagenes/add.gif' width='16' height='16' />
                </td>
                <td style='padding: 0px 6px;'>Agregar Producto
                </td>
                <td><img src='../../../includes/imagenes/bt_right.gif' style='border-width: 0px; width: 4px; height: 21px;' />
                </td>
            </tr>
        </table>
        </div>
        
        </td>
        </tr>
        </table>";
        }
        $formulario.="
        <form name='formulario2' id='formulario2' method='post' enctype='multipart/form-data'>
        <table   width='100%' border='0' >
            <thead>
                <tr class='tb-head'>
                    <th style='width:220px;' >Codigo de Barras</th>
                    <th style='width:200px;'>Producto</th>
                    <th>Inventario Ini.</th>
                    <th>Toma 1</th>
                    <th>Toma 2</th>
                    <th>Toma Def.</th>
                    <th>Mov. Sugerido</th>
                </tr>
            </thead>

            <tbody>
                ";

        $consulta=$conn->ObtenerFilasBySqlSelect("SELECT a.*,b.descripcion1 as nombre_producto from tomas_fisicas_detalle a, item b WHERE a.cod_bar=b.codigo_barras and a.id_llenado=1 and id_mov=".$_POST['id_mov']."");

        foreach ($consulta as $key => $value) {
            if($value['mov_sugerido']>0){
                $value['mov_sugerido']='+'.$value['mov_sugerido'];
            }
            $formulario.=
            "
            <input type='hidden' name='tiene_filtro' id='tiene_filtro' value='1'/>
            <input type='hidden' name='ubicacion' id='tiene_filtro' value=''/>
            <input type='hidden' name='fecha_apertura' id='fecha_apertura' value=''/>
            <input type='hidden' name='tipo_toma' id='tipo_toma' value=''/>
            
            
            <tr>
                <td style='padding-top:2px; padding-bottom: 2px;'>
                    
                    <input type='text' name='codigo_barras[]' id='codigo_barras[]' style='float: left;' class='form-text' value='".$value['cod_bar']."' readonly='readonly'/>
                </td>
                <td style='padding-top:2px; padding-bottom: 2px;'>
                    <input class='form-text' type='text' name='precio{counter}' size='50' id='precio{counter}' value='".$value['nombre_producto']."' readonly='readonly'>
                </td>            
                <td style='padding-top:2px; padding-bottom: 2px;'>
                    <input class='form-text' type='text' name='inv_ini[]' size='10' id='inv_ini[]' value='".$value['inv_sistema']."' readonly='readonly'>
                </td>

                <td style='padding-top:2px; padding-bottom: 2px;'>
                    <input class='form-text' type='text' name='toma1[]' size='10' id='toma1[]' value='".$value['toma1']."' readonly>
                </td>

                <td>
                    <input class='form-text' type='text' name='toma2[]' size='10' id='toma2[]' value='".$value['toma2']."' readonly='readonly'>
                </td>
                <td>
                    <input class='form-text' type='text' name='tomadef[]' size='10' id='tomadef[]' value='".$value['tomadef']."' readonly='readonly'>
                </td>
                <td>
                    <input class='form-text' type='text' name='mov_sug[]' size='10' id='mov_sug[]' value='".$value['mov_sugerido']."'>
                </td>
            </tr>
        ";
        }


        $formulario.= "    
            
            <tr class='tb-head'>
                <td colspan='3'>
                    <input type='hidden' name='id_mov' id='id_mov' value='".$_POST['id_mov']."'/>
                </td>
                
                <td align='left'>
                    <input type='file' name='archivo_productos1' id='archivo_productos1' style='margin-left: 50px; width:80px' ".$buton1." accept='txt/*'></input>
                    <br><br>
                    <input type='button'  style='margin-right: 40px;' class='form-text' id='enviarajax' name='toma1_submit' value='Cerrar' ".$buton1." align='center'  onclick='recargar(1)'/>
                </td>
                <td align='center'>
                    <input type='file' name='archivo_productos2' id='archivo_productos2' style='margin-left: 50px; width:80px' ".$buton2." accept='txt/*'></input>
                    <br><br>
                    <input type='button' id='enviarajax'   style='margin-right: 40px;' class='form-text' name='toma2_submit'  value='Cerrar'  ".$buton2." onclick='recargar(2)'/>
                </td>
                <td align='center'>
                    <input type='file' name='archivo_productos3' id='archivo_productos3' style='margin-left: 50px; width:80px' ".$buton3." accept='txt/*'></input>
                    <br><br>
                    <input type='button' id='enviarajax' style='margin-right: 40px;' class='form-text' name='toma3_submit'   value='Cerrar'  ".$buton3." onclick='recargar(3)'/>
                </td>
                                    
            </tr>
        </form>
        ";

        echo $formulario;

        break;


        case "toma_fisica_actualizar" :

    //toma que se esta trabajando
    $toma_fisica=$conn->ObtenerFilasBySqlSelect("select toma from tomas_fisicas where id=".$_POST['id_mov']);
    
    if($toma_fisica[0]['toma']==1)
    {
        $toma='toma1';
        $buton1="";
        $buton2="disabled";
        $buton3="disabled";
    }
    if($toma_fisica[0]['toma']==2)
    {
        $toma='toma2';
        $buton1="disabled";
        $buton2="";
        $buton3="disabled";
    }
    if($toma_fisica[0]['toma']==3)
    {
        $toma='tomadef';
        $buton1="disabled";
        $buton2="disabled";
        $buton3="";
    }
    
        //seleccionamos los almacenes
    $almacenes=$conn->ObtenerFilasBySqlSelect("select id_ubicacion, id_almacen from tomas_fisicas where id=".$_POST['id_mov']);

    //buscamos los datos de los productos en el almacen
    $producto_almacen=$conn->ObtenerFilasBySqlSelect("select a.descripcion1, a.codigo_barras, b.cantidad from item as a, item_existencia_almacen as b where a.id_item=b.id_item and cod_almacen=".$almacenes[0]['id_almacen']." and id_ubicacion=".$almacenes[0]['id_ubicacion']." and a.codigo_barras='".$_POST['codigo_barras']."'");

    $codigo_barras_item=$producto_almacen[0]['codigo_barras'];
    $cantidad_item=$producto_almacen[0]['cantidad'];
    //si el producto no existe se debe insertar
    if($producto_almacen[0]['codigo_barras']=="")
    {
    $sql_insert=$conn->Execute2("insert into item_existencia_almacen (cod_almacen, id_item, cantidad, id_ubicacion) values (".$almacenes[0]['id_almacen'].", ".$_POST['id_producto'].", 0, ".$almacenes[0]['id_ubicacion'].")");
    
    $codigo_barras_item=$_POST['codigo_barras'];
    $descripcion=$conn->ObtenerFilasBySqlSelect("select descripcion1 from item where id_item=".$_POST['id_producto']);
    $descripcion1_item=$descrupcion[0]['descripcion1'];
    $cantidad_item=0;
    
    }
    //actualizamos o insertamos las tomas de acuerdo al caso
    //ver si existe en detalle
    $existencia=$conn->ObtenerFilasBySqlSelect("select id from tomas_fisicas_detalle where id_mov=".$_POST['id_mov']." and cod_bar='".$_POST['codigo_barras']."'");    
    if($existencia[0]['id']!="")
    {
    $filtrosuma="";
        if($toma=='tomadef')
        { 
            $filtrosuma=",mov_sugerido=".($_POST['cantidad']-$cantidad_item);
        }

    $insert=$conn->Execute2("update tomas_fisicas_detalle set id_llenado=1, ".$toma."=".$_POST['cantidad'].$filtrosuma." where id_mov=".$_POST['id_mov']." and cod_bar='".$_POST['codigo_barras']."'");
    }
    else
    {
        //insertar el detalle de producto
        $filtro="";
        $filtro1="";
        if($toma=='toma2')
        {
            $filtro=",toma1";
            $filtro1=" ,0";
        }
        if($toma=='tomadef')
        {
            $filtro=",toma1,toma2";
            $filtro1=" ,0,0";
        }

    $insert=$conn->Execute2("insert into tomas_fisicas_detalle(id_mov,cod_bar,inv_sistema,".$toma.$filtro.", mov_sugerido, id_llenado) values (".$_POST['id_mov'].", '".$codigo_barras_item."', ".$cantidad_item.", ".$_POST['cantidad'].$filtro1.",0, 1)");
    }

    
    $formulario="";
    if($toma_fisica[0]['toma']!=4)
        {
        $formulario="
        <table   width='100%' border='0' align='center'>
        <tr>
        <td class='btn' style='float:center; padding-right: 15px;' colspan='1' border=0>
        
        <div id=boton_agregar> 
        <table class='btn_bg' onclick='agregarProducto()' align='center' border='0'>
            <tr style='border-width: 0px; cursor: pointer;'>
                <td><img src='../../../includes/imagenes/bt_left.gif' style='border-width: 0px; width: 4px; height: 21px;' />
                </td>
                <td><img src='../../../includes/imagenes/add.gif' width='16' height='16' />
                </td>
                <td style='padding: 0px 6px;'>Agregar Producto
                </td>
                <td><img src='../../../includes/imagenes/bt_right.gif' style='border-width: 0px; width: 4px; height: 21px;' />
                </td>
            </tr>
        </table>
        </div>
        
        </td>
        </tr>
        </table>";
        }
    $formulario.="
    <form name='formulario2' id='formulario2' method='post' enctype='multipart/form-data'>
    <table   width='100%' border='0' >
        <thead>
            <tr class='tb-head'>
                <th style='width:220px;' >Codigo de Barras</th>
                <th style='width:200px;'>Producto</th>
                <th>Inventario Ini.</th>
                <th>Toma 1</th>
                <th>Toma 2</th>
                <th>Toma Def.</th>
                <th>Mov. Sugerido</th>
            </tr>
        </thead>

        <tbody>
            ";

    $consulta=$conn->ObtenerFilasBySqlSelect("SELECT a.*,b.descripcion1 as nombre_producto from tomas_fisicas_detalle a, item b WHERE a.cod_bar=b.codigo_barras and a.id_llenado=1 and id_mov=".$_POST['id_mov']."");
    foreach ($consulta as $key => $value) {

        $formulario.=
        "
        <input type='hidden' name='tiene_filtro' id='tiene_filtro' value='1'/>
        <input type='hidden' name='ubicacion' id='tiene_filtro' value=''/>
        <input type='hidden' name='fecha_apertura' id='fecha_apertura' value=''/>
        <input type='hidden' name='tipo_toma' id='tipo_toma' value=''/>
        
        
        <tr>
            <td style='padding-top:2px; padding-bottom: 2px;'>
                
                <input type='text' name='codigo_barras[]' id='codigo_barras[]' style='float: left;' class='form-text' value='".$value['cod_bar']."' readonly='readonly'/>
            </td>
            <td style='padding-top:2px; padding-bottom: 2px;'>
                <input class='form-text' type='text' name='precio{counter}' size='50' id='precio{counter}' value='".$value['nombre_producto']."' readonly='readonly'>
            </td>            
            <td style='padding-top:2px; padding-bottom: 2px;'>
                <input class='form-text' type='text' name='inv_ini[]' size='10' id='inv_ini[]' value='".$value['inv_sistema']."' readonly='readonly'>
            </td>

            <td style='padding-top:2px; padding-bottom: 2px;'>
                <input class='form-text' type='text' name='toma1[]' size='10' id='toma1[]' value='".$value['toma1']."' readonly>
            </td>

            <td>
                <input class='form-text' type='text' name='toma2[]' size='10' id='toma2[]' value='".$value['toma2']."' readonly='readonly'>
            </td>
            <td>
                <input class='form-text' type='text' name='tomadef[]' size='10' id='tomadef[]' value='".$value['tomadef']."' readonly='readonly'>
            </td>
            <td>
                <input class='form-text' type='text' name='mov_sug[]' size='10' id='mov_sug[]' value='".$value['mov_sugerido']."' readonly='readonly'>
            </td>
        </tr>
    ";
    }


    $formulario.= "    
        <tr class='tb-head'>
            <td colspan='3'>
                <input type='hidden' name='id_mov' id='id_mov' value='".$_POST['id_mov']."'/>
            </td>
            
            <td align='left'>
                <input type='file' name='archivo_productos1' id='archivo_productos1' style='margin-left: 50px; width:80px' ".$buton1." accept='txt/*'></input>
                <input type='button'  style='margin-right: 80px;' class='form-text' id='enviarajax' name='toma1_submit' value='Cerrar' ".$buton1." align='center'  onclick='recargar(1)'/>
            </td>
            <td align='center'>
                <input type='file' name='archivo_productos2' id='archivo_productos2' style='margin-left: 50px; width:80px' ".$buton2." accept='txt/*'></input>
                <input type='button' id='enviarajax'   style='margin-right: 40px;' class='form-text' name='toma2_submit'  value='Cerrar'  ".$buton2." onclick='recargar(2)'/>
            </td>
            <td align='center'>
                <input type='file' name='archivo_productos3' id='archivo_productos3' style='margin-left: 50px; width:80px' ".$buton3." accept='txt/*'></input>
                <input type='button' id='enviarajax' style='margin-right: 40px;' class='form-text' name='toma3_submit'   value='Cerrar'  ".$buton3." onclick='recargar(3)'/>
            </td>
                                
        </tr>
    </form>
    ";

    echo $formulario;
        break;
        
        case "toma_fisica_update" : 
        $bandera1=0;
        $bandera2=0;
        $bandera3=0;        
        $i=0;
        $cantidad_items=isset($_POST['cantidad_items']) ? $_POST['cantidad_items'] : '';
        
        $items=isset($_POST['items']) ? $_POST['items'] : '';
        $arrtoma1=isset($_POST['toma1']) ? $_POST['toma1'] : '';
        $arrtoma2=isset($_POST['toma2']) ? $_POST['toma2'] : '';
        $arrtoma3=isset($_POST['toma3']) ? $_POST['toma3'] : '';
        
        

       if(isset($arrtoma3) && $_POST['boton']==3){
        while($i<$cantidad_items){
        if($arrtoma3[$i]==""){
            $arrtoma3[$i]=0;
           }
            if(!is_numeric($arrtoma3[$i])){
            $bandera3=1;     
            }

        $i++;
        }
        $i=0;
        if($bandera3==0){
        while($i<$cantidad_items){
       $sql="UPDATE tomas_fisicas_detalle SET tomadef=".$arrtoma3[$i]." WHERE cod_bar='".$items[$i]."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$conn->Execute2($sql);

        $i++;
        } //fin del while
    }
    }
    $i=0;



        if(isset($arrtoma2)  && $_POST['boton']==2){
        while($i<$cantidad_items){
        if($arrtoma2[$i]=="" ){
        $arrtoma2[$i]=0;
        }
         if(!is_numeric($arrtoma2[$i])){
            $bandera2=1;     
            }
        $i++;
        }
        $i=0;
        if($bandera2==0){
        $iguales=0;
        $toma1_igual=$conn->ObtenerFilasBySqlSelect("select toma1 from tomas_fisicas_detalle where id_mov=".$_POST['id_mov']."");

        while($i<$cantidad_items){
        $sql="UPDATE tomas_fisicas_detalle SET toma2=".$arrtoma2[$i]." WHERE cod_bar='".$items[$i]."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$conn->Execute2($sql);
        
        
        if($toma1_igual[$i]['toma1']>$arrtoma2[$i] || $toma1_igual[$i]['toma1']<$arrtoma2[$i]){
        $iguales=1;
        
        }// iguales tiene que actualizarse la tercera columna
        else{
        $sql="UPDATE tomas_fisicas_detalle SET tomadef=".$arrtoma2[$i]." WHERE cod_bar='".$items[$i]."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$conn->Execute2($sql);
        }
        $i++;

    }// fin del while
   /* if($iguales==0){
        $i=0;
        while($i<$cantidad_items){

        $sql="UPDATE tomas_fisicas_detalle SET tomadef=".$arrtoma2[$i]." WHERE cod_bar='".$items[$i]."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$conn->Execute2($sql);
        $i++;
        }//fin while
    }//fin del if banderaiguales*/
    }//fin del if bandera
    }   

$i=0;
       if(isset($arrtoma1) && $_POST['boton']==1){

        while($i<$cantidad_items){
        if($arrtoma1[$i]=="" || !is_numeric($arrtoma1[$i]) ){
            $arrtoma1[$i]=0;
        }
        if(!is_numeric($arrtoma1[$i])){
            $bandera1=1;     
            }
        $i++;
        }
        $i=0;
        if($bandera1==0){
        while($i<$cantidad_items){
        $sql="UPDATE tomas_fisicas_detalle SET toma1=".$arrtoma1[$i]." WHERE cod_bar='".$items[$i]."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$conn->Execute2($sql);
        $i++;
        }// fin del while
    }//fin del if de bandera
    }
    
    $query=$conn->ObtenerFilasBySqlSelect("SELECT a.*,b.descripcion1 as nombre_producto from tomas_fisicas_detalle a, item b WHERE a.cod_bar=b.codigo_barras and id_mov=".$_POST['id_mov']."");

    $i=0;
    $resultado=$conn->getFilas($query);
    

    while($i<$resultado){
    $datos[$i]=$query[$i]; //se guardan los datos en un arreglo
    $i++;
    }
    $consulta=$datos;
    
        $nulltoma1="0";
        $nulltoma2="0";
        $nulltoma3="0";
        
        $sql="SELECT * FROM tomas_fisicas_detalle WHERE toma1 is NULL and id_mov=".$_POST['id_mov'];
        $query=$conn->ObtenerFilasBySqlSelect($sql);
        $nulltoma1=$conn->getFilas($query);


        if($nulltoma1==0){
            $sql="SELECT * FROM tomas_fisicas_detalle WHERE toma2 is NULL and id_mov=".$_POST['id_mov'];
            $query=$conn->ObtenerFilasBySqlSelect($sql);
            $nulltoma2=$conn->getFilas($query);
            
            if($nulltoma2==0){
                $sql="SELECT * FROM tomas_fisicas_detalle WHERE tomadef is NULL and id_mov=".$_POST['id_mov'];
                $query=$conn->ObtenerFilasBySqlSelect($sql);
                $nulltoma3=$conn->getFilas($query);
            }
            


            }
        if($nulltoma1=="0")
        {
        $toma1="readonly='readonly'";
        $toma1_boton="disabled='disabled'";
    }
        if($nulltoma2=="0")
        {
    $toma2="readonly='readonly'";
    $toma2_boton="disabled='disabled'";

        }
        if($nulltoma3=="0")
        {
        $toma3="readonly='readonly'";
        $toma3_boton="disabled='disabled'";

        }


        //creando input para saber si falta algun campo por cargar
        $valor_vacio="";
        if($bandera1!=0 || $bandera2!=0 || $bandera3!=0)
        {
           $valor_vacio=1;

        }        
         $campo="<input type='hidden' name='falta_campo' id='falta_campo' value=".$valor_vacio." />";

        $formulario="
        <table   width='100%' border='0' >
    <thead>
        <tr class='tb-head'>
            <th style='width:220px;' >Codigo de Barras</th>
            <th style='width:200px;'>Producto</th>
            <th>Inventario Ini.</th>
            <th>Toma 1</th>
            <th>Toma 2</th>
            <th>Toma Def.</th>
            <th>Mov. Sugerido</th>
        </tr>
    </thead>

    <tbody>
        <form name='formulario2' id='formulario2' method='post' >";
    foreach ($consulta as $key => $value) {
        # code...

    if(isset($value['tomadef']) && $value['tomadef']!=""){
        
        $suma=$value['tomadef']-$value['inv_sistema'];

        if($suma>0)
        $value['mov_sugerido']="+".$suma;
        else
        $value['mov_sugerido']=$suma;
         
         $sql="UPDATE tomas_fisicas_detalle SET mov_sugerido=".$suma." WHERE cod_bar='".$value['cod_bar']."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$conn->Execute2($sql);
    }

        $formulario.=
        "
        <input type='hidden' name='tiene_filtro' id='tiene_filtro' value='1'/>
        <input type='hidden' name='ubicacion' id='tiene_filtro' value=''/>
        <input type='hidden' name='fecha_apertura' id='fecha_apertura' value=''/>
        <input type='hidden' name='tipo_toma' id='tipo_toma' value=''/>
        ".$campo."

        
        <tr>
            <td style='padding-top:2px; padding-bottom: 2px;'>
                
                <input type='text' name='codigo_barras[]' id='codigo_barras[]' style='float: left;'   class='form-text' value='".$value['cod_bar']."' readonly='readonly' />
            </td>
            <td style='padding-top:2px; padding-bottom: 2px;'>
                <input class='form-text' type='text' name='precio{counter}' size='50' id='precio{counter}' value='".$value['nombre_producto']."' readonly='readonly'>
            </td>            
            <td style='padding-top:2px; padding-bottom: 2px;'>
                <input class='form-text' type='text' name='inv_ini[]' size='10' id='inv_ini[]' value='".$value['inv_sistema']."' readonly='readonly'>
            </td>

            <td style='padding-top:2px; padding-bottom: 2px;'>
                <input class='form-text' type='text' name='toma1[]' size='10' id='toma1[]' value='".$value['toma1']."' ".$toma1.">
            </td>

            <td>
                <input class='form-text' type='text' name='toma2[]' size='10' id='toma2[]' value='".$value['toma2']."' ".$toma2.">
            </td>
            <td>
                <input class='form-text' type='text' name='tomadef[]' size='10' id='tomadef[]' value='".$value['tomadef']."' ".$toma3.">
            </td>
            <td>
                <input class='form-text' type='text' name='mov_sug[]' size='10' id='mov_sug[]' value='".$value['mov_sugerido']."'>
            </td>
        </tr>
    ";
    }

    $formulario.= "    
        <tr class='tb-head'>
                                <td colspan='3'>
                                <input type='hidden' name='cantidad_items' id='cantidad_items' value='".$resultado."' />
                                <input type='hidden' name='id_mov' id='id_mov' value='".$_POST['id_mov']."'/>
                                </td>

                                
                                <td align='left'>
                                
                                
                                  <input type='button'  style='margin-right: 40px;' class='form-text' id='enviarajax' name='toma1_submit' ".$toma1_boton." value='Cerrar' align='center'  onclick='recargar(1)'/>
                                
                                
                                </td>


                                <td>
                                  <input type='button' id='enviarajax'   style='margin-right: 40px;' class='form-text' name='toma2_submit'  ".$toma2_boton." value='Cerrar'  onclick='recargar(2)'/>
                                </td>

                                <td align='left'>
                                  <input type='button' id='enviarajax' style='margin-right: 40px;' class='form-text' name='toma3_submit'  ".$toma3_boton."value='Cerrar'  onclick='recargar(3)'/>
                                </td>
                                
        </tr>
        </form>
        ";

        echo $formulario;
        exit();

        break;


        case "det_items_calidad_estatus":
       $campos= $conn->ObtenerFilasBySqlSelect("select b.estatus as estatus, c.codigo_barras, c.descripcion1, m.marca, c.pesoxunidad, d.nombre_unidad, b.cantidad, b.observacion from calidad_almacen as a, calidad_almacen_detalle as b LEFT JOIN marca m ON b.id_marca= m.id, item as c, unidad_medida as d where b.id_item=c.id_item and a.id_transaccion=b.id_transaccion and c.unidadxpeso=d.id and a.id_transaccion=".$_GET['id_transaccion']);

 
        echo '<tr class="detalle_items">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
               <table >
                    <thead>
                        
                       <th style="width:150px; font-weight: bold;">Codigo Barras</th>
                       <th style="width:350px; font-weight: bold;">Nombre</th>
                        <th style="width:150px; font-weight: bold;">Cantidad</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Observación</th>
                        <!--<th style="width:150px; font-weight: bold;">Estatus</th>-->
                    </thead>
                   <tbody>';
          foreach ($campos as $key => $item) {
                //if(empty($item["marca"])
                  //      $item["marca"]='SIN MARCA';
                  echo '
                       <tr>
                        <td style="width:150px; padding-left:10px;">' . $item["codigo_barras"] . '</td>
                        <td style="width:110px; text-align: center; padding-right:10px;">' .$item["descripcion1"].'- '.$item["marca"].' '.$item["pesoxunidad"].' '.$item["nombre_unidad"].'</td>
                        <td style="width:150px; padding-left:10px;">' . $item["cantidad"] . '</td>
                        <td style="width:150px; padding-left:10px;">' . $item["observacion"] . '</td>
                        <!--<td style="width:150px; padding-left:10px;">' . $item["estatus"] . '</td>-->
                        </tr>';
                   }
      break;


      case "itempendiente" : 
        $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM calidad_almacen_detalle WHERE id_item = '" . $_GET["v2"] . "' and id_almacen_salida = '" . $_GET["v1"] . "' and id_ubi_salida='". $_GET["ubicacion"]."' and estatus=3");
        //echo "SELECT * FROM calidad_almacen_detalle WHERE id_item = '" . $_GET["v2"] . "' and id_almacen_salida = '" . $_GET["v1"] . "' and id_ubi_salida='". $_GET["ubicacion"]."' and estatus=2"; exit();
        if (count($campos) == 0 || empty($campos)) {
                echo "[{id:'-1'}]";
            } else {

                foreach ($campos as $key => $value) {
                $total+= $value['cantidad'];
                }

                echo "[{id:".$total."}]";//json_encode($total);
            }
        break;

case "calcular_arqueo":

            $sql="Select fecha_fin from control_cierre_cajero where id_cajero='".$_POST['cajero']."' order by secuencia desc limit 1";

            $fecha_inicio=$conn->ObtenerFilasBySqlSelect($sql);

            //ventas por el pyme
            if(isset($_POST['tipo_venta_pyme']) && $_POST['tipo_venta_pyme']!="1" )
            {
                $retiros_pendiente=$conn->ObtenerFilasBySqlSelect("Select id, 
                sum(monedas) as monedas
                 from retiro_efectivo where id_cajero='".$_POST['cajero']."' and estatus_retiro=0 and tipo_venta=0");

                $totalbilletesretiro=$conn->ObtenerFilasBySqlSelect("select sum(a.valor*b.cantidad) as total from billetes a inner join billetes_retiro_efectivo b on a.id=b.id_billete where b.id_retiro_efectivo='".$retiros_pendiente[0]["id"]."'");

                $_POST['total_monedas']=$_POST['total_monedas']+($retiros_pendiente[0]["monedas"]);

            
                $total_cajero="";
                //calculamos el total de todo lo que hizo el cajero
                for($i=0; $i<count($_POST['valoresarray']); $i++)
                {
                    $total_cajero+=($_POST['valoresarray'][$i]*$_POST['billetesarray'][$i]);
                }
                $total_cajero+=($_POST['total_monedas']+$_POST['total_tarjeta']+$_POST['total_ticket']+$_POST['total_credito']+$_POST['total_deposito']+$_POST['total_cheque']+$totalbilletesretiro[0]['total']);

                if(!empty($fecha_inicio[0]['fecha_fin']))
                {
                    //sum(totalizar_monto_credito2) as total_credito_sistema,
                    $sql="SELECT 
                        sum(totalizar_monto_efectivo) as total_efectivo_sistema, 
                        sum(totalizar_monto_tarjeta) as total_tj_sistema,
                        sum(totalizar_monto_deposito) as total_deposito_sistema,
                        sum(totalizar_monto_cheque) as total_cheque_sistema
                        FROM `factura_detalle_formapago` as a, factura as b WHERE a.id_factura=b.id_factura 
                        and a.totalizar_monto_credito2=0
                        and b.cod_vendedor='".$_POST['cajero']."'
                        and b.fecha_creacion BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now()";
                    $resultado=$conn->ObtenerFilasBySqlSelect($sql);
                    /********************Nuevo***************************
                    /Se busca las ventas por productos, para el ingreso
                    */
                    $sql="select c.cod_departamento as coddepartamento, SUM( (c.precio1 * b._item_cantidad) ) AS totalingreso FROM  factura as a, factura_detalle as b, item as c, factura_detalle_formapago as d where 
                        a.id_factura=b.id_factura 
                        and b.id_item=c.id_item 
                        and a.id_factura=d.id_factura
                        and a.cod_vendedor='".$_POST['cajero']."'
                        and d.totalizar_monto_credito2=0
                        and a.fecha_creacion BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now()
                        group by c.cod_departamento ";

                    $tipo_ingresos=json_encode($conn->ObtenerFilasBySqlSelect($sql));
                    
                    if($tipo_ingresos!='')
                    {
                        $tipoarray=$tipo_ingresos;
                    }
                    else
                    {
                        $tipoarray=0;
                    }
                    //se buscan los depositos del sistema solo si se hicieron depositos
                    if($resultado[0]['total_deposito_sistema']>0)
                    {
                        $sql1="Select 
                            totalizar_monto_deposito as depositos from `factura_detalle_formapago` as a, factura as b WHERE a.id_factura=b.id_factura and totalizar_monto_deposito > 0
                        and b.cod_vendedor='".$_POST['cajero']."'
                        and a.totalizar_monto_credito2=0
                        and b.fecha_creacion BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now()";
                        $arraydepositos=json_encode($conn->ObtenerFilasBySqlSelect($sql1));
                        
                    }
                    else
                    {
                        $arraydepositos=0;
                    }

                    //se buscan los cheques del sistema solo si se hicieron cheques
                    if($resultado[0]['total_cheque_sistema']>0)
                    {
                        $sql1="Select 
                            totalizar_monto_cheque as cheques from `factura_detalle_formapago` as a, factura as b WHERE a.id_factura=b.id_factura and totalizar_monto_cheque > 0
                        and b.cod_vendedor='".$_POST['cajero']."'
                        and a.totalizar_monto_credito2=0
                        and b.fecha_creacion BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now()";
                        $arraycheques=json_encode($conn->ObtenerFilasBySqlSelect($sql1));
                    }
                    else
                    {
                        $arraycheques=0;
                    }


                    
                    // se calculan los ivas generados por las ventas
                    $sql2="SELECT 
                            _item_piva, 
                            sum(_item_totalconiva - _item_totalsiniva) as iva 
                            FROM 
                            `factura_detalle_formapago` as a, 
                            factura as b, factura_detalle as c 
                            WHERE 
                            a.id_factura=b.id_factura 
                            and a.id_factura=c.id_factura 
                            and a.totalizar_monto_credito2=0
                            and b.cod_vendedor='".$_POST['cajero']."'
                            and b.fecha_creacion BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now() 
                            group by _item_piva";
                    $resultado_iva=$conn->ObtenerFilasBySqlSelect($sql2);
                    //si es diferente de null podemos hacer el  foreach
                    $iva8=0;
                    $iva12=0;
                    $ivaotro=0;
                    if($resultado_iva!=null)
                    {
                        foreach ($resultado_iva as $key => $value) 
                        {
                            if($value['_item_piva']=='8.00')
                            {
                                $iva8+=$value['iva'];
                            }
                            if($value['_item_piva']=='12.00')
                            {
                                $iva12+=$value['iva'];
                            }
                            if($value['_item_piva']!='12.00' && $value['_item_piva']!='8.00' && $value['_item_piva']!='0.00')
                            {
                                $ivaotro+=$value['iva'];
                            }
                        }
                    };
                    //fin de agregar el iva
                    //quitando credito +$resultado[0]['total_credito_sistema'] 02-07-2017
                    $resultado[0]['total_cajero']= number_format($total_cajero,2,',','');
                    $resultado[0]['TOTAL_SISTEMA']= $resultado[0]['total_efectivo_sistema']+$resultado[0]['total_tj_sistema']+$resultado[0]['total_deposito_sistema']+$resultado[0]['total_cheque_sistema']+$resultado[0]['total_tickets_sistema'];
                    $resta=$total_cajero-$resultado[0]['TOTAL_SISTEMA'];
                    $devolucion= 0;
                    $total_sistema_formato=$resultado[0]['TOTAL_SISTEMA'];
                    $resultado[0]['TOTAL_SISTEMA']= number_format($resultado[0]['TOTAL_SISTEMA']-$devolucion,2,',','');
                    //$resta=($resultado[0]['total_cajero']-$resultado[0]['TOTAL_SISTEMA']);

                    //$resta=number_format($resta,2,',','');
                    $resultado[0]['total_tickets_sistema']=0;
                    $resultado[0]['total_dev_efectivo']=0;
                    $resultado[0]['total_dev_tarjeta']=0;
                
                    if($resultado[0]['TOTAL_SISTEMA']!="0,00")
                    {
                        $resultado[0]['total_credito_sistema']=0;//para afectar lo menos posible el codigo de arqueo tpl.

                        if($resta==0)
                        {
                            echo "0_".$resultado[0]['total_cajero']."_".$total_sistema_formato."_".$resta."_".$resultado[0]['total_efectivo_sistema']."_".$resultado[0]['total_tj_sistema']."_".$resultado[0]['total_tickets_sistema']."_".($resultado[0]['total_dev_efectivo']+$resultado[0]['total_dev_tarjeta']."_".$resultado[0]['total_credito_sistema']."_".$resultado[0]['total_deposito_sistema']."_".$resultado[0]['total_cheque_sistema']."_".$iva12."_".$iva8."_".$ivaotro."_".$arraydepositos."_".$arraycheques."_".$tipoarray);
                            exit();
                        }
                        if($resta>0)
                        {
                            //sobrante
                            
                            echo "1_".$resultado[0]['total_cajero']."_".$total_sistema_formato."_".$resta."_".$resultado[0]['total_efectivo_sistema']."_".$resultado[0]['total_tj_sistema']."_".$resultado[0]['total_tickets_sistema']."_".($resultado[0]['total_dev_efectivo']+$resultado[0]['total_dev_tarjeta']."_".$resultado[0]['total_credito_sistema']."_".$resultado[0]['total_deposito_sistema']."_".$resultado[0]['total_cheque_sistema']."_".$iva12."_".$iva8."_".$ivaotro."_".$arraydepositos."_".$arraycheques."_".$tipoarray);
                            exit();
                        }
                        if($resta<0)
                        {
                            //faltante
                            echo "2_".$resultado[0]['total_cajero']."_".$total_sistema_formato."_".$resta."_".$resultado[0]['total_efectivo_sistema']."_".$resultado[0]['total_tj_sistema']."_".$resultado[0]['total_tickets_sistema']."_".($resultado[0]['total_dev_efectivo']+$resultado[0]['total_dev_tarjeta']."_".$resultado[0]['total_credito_sistema']."_".$resultado[0]['total_deposito_sistema']."_".$resultado[0]['total_cheque_sistema']."_".$iva12."_".$iva8."_".$ivaotro."_".$arraydepositos."_".$arraycheques."_".$tipoarray);
                            exit();
                        }
                    }
                    else
                    {
                        echo "333";
                        exit();
                    }

                }

                else
                {
                    echo "_4";
                    exit();
                }
            } //fin del if de ventas por el pyme
            
            $retiros_pendiente=$conn->ObtenerFilasBySqlSelect("Select id,
            sum(monedas) as monedas
             from retiro_efectivo where id_cajero='".$_POST['cajero']."' and estatus_retiro=0 and tipo_venta=1");

            $sql="select sum(a.valor*b.cantidad) as total from billetes a inner join billetes_retiro_efectivo b on a.id=b.id_billete where b.id_retiro_efectivo='".$retiros_pendiente[0]["id"]."'";

             $totalbilletesretiro=$conn->ObtenerFilasBySqlSelect($sql);

            $_POST['total_monedas']=$_POST['total_monedas']+($retiros_pendiente[0]["monedas"]);

            //calculamos el total de todo lo que hizo el cajero
            for($i=0; $i<count($_POST['valoresarray']); $i++)
            {
                $total_cajero+=($_POST['valoresarray'][$i]*$_POST['billetesarray'][$i]);
            }
            $total_cajero+=($_POST['total_monedas']+$_POST['total_tarjeta']+$_POST['total_ticket']+$totalbilletesretiro[0]['total']);
            if(!empty($fecha_inicio[0]['fecha_fin']))
            {
		$sql="SELECT people.NAME,
                sum(case payment when 'cash' then payments.TOTAL else 0 end) as total_efectivo_sistema,
                sum(case payment when 'magcard' then payments.TOTAL else 0 end) as total_tj_sistema,
                sum(case payment when 'paperin' then payments.TOTAL else 0 end) as total_tickets_sistema,
                sum(case payment when 'magcardrefund' then payments.TOTAL else 0 end) as total_dev_tarjeta,
                sum(case payment when 'cashrefund' then payments.TOTAL else 0 end) as total_dev_efectivo
                
                FROM ".POS.".payments INNER JOIN ".POS.".receipts ON receipts.ID = payments.RECEIPT 
                INNER JOIN ".POS.".tickets ON tickets.ID = receipts.ID 
                INNER JOIN ".POS.".people ON tickets.PERSON = people.ID 
                WHERE DATENEW BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now() and people.id='".$_POST['cajero']."' group by people.NAME";

                $resultado=$conn->ObtenerFilasBySqlSelect($sql);
                /********************Nuevo***************************
                    /Se busca las ventas por productos, para el ingreso
                    */
                    $sql="select c.cod_departamento as coddepartamento, SUM( (b.PRICE * b.UNITS) ) AS totalingreso FROM  ".POS.".tickets as a, ".POS.".ticketlines as b, ".DB_SELECTRA_FAC.".item as c, ".POS.".taxes as d where a.ID=b.TICKET  and b.TAXID=d.ID 
                        and b.PRODUCT=c.itempos
                        and a.PERSON='".$_POST['cajero']."'
                        and b.DATENEW BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now()
                        group by c.cod_departamento ";


                    $tipo_ingresos=json_encode($conn->ObtenerFilasBySqlSelect($sql));
                    
                    if($tipo_ingresos!='')
                    {
                        $tipoarray=$tipo_ingresos;
                    }
                    else
                    {
                        $tipoarray=0;
                    }
                //buscamos los iva que generan estas ventas
                $sql="SELECT 
                                    taxes.rate, 
                                    people.NAME,
                                    #sum(payments.total-taxlines.base) as iva,
                                    sum(taxlines.amount) as iva

                                    FROM ".POS.".payments INNER JOIN ".POS.".receipts ON receipts.ID = payments.RECEIPT 
                                    INNER JOIN ".POS.".tickets ON tickets.ID = receipts.ID 
                                    INNER JOIN ".POS.".people ON tickets.PERSON = people.ID 
                                    INNER JOIN ".POS.".taxlines ON taxlines.receipt = receipts.ID 
                                    INNER JOIN ".POS.".taxes ON taxes.id = taxlines.taxid 
                                    WHERE DATENEW BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now() 
                                     and people.id='".$_POST['cajero']."' 
                                     group by taxes.rate";
               //sum(payments.total-taxlines.base) as iva
                $resultado_iva=$conn->ObtenerFilasBySqlSelect($sql);
                $sql="Select sum(payments.total) as total , taxes.rate,  sum(taxlines.amount) as iva
                    FROM 
                    ".POS.".payments 
                    INNER JOIN ".POS.".receipts ON receipts.ID = payments.RECEIPT 
                    INNER JOIN ".POS.".tickets ON tickets.ID = receipts.ID 
                    INNER JOIN ".POS.".people ON tickets.PERSON = people.ID 
                    INNER JOIN ".POS.".taxlines ON taxlines.receipt = receipts.ID 
                    INNER JOIN ".POS.".taxes ON taxes.id = taxlines.taxid 
                    WHERE DATENEW BETWEEN '".$fecha_inicio[0]['fecha_fin']."' 
                    AND now() and people.id='".$_POST['cajero']."' 
                    and payments.id in 
                    (select payments.id from ".POS.".payments INNER JOIN ".POS.".receipts ON receipts.ID = payments.RECEIPT WHERE between DATENEW '".$fecha_inicio[0]['fecha_fin']."' AND now()  and payment in ('magcardrefund', 'cashrefund'))

                    ";

                //ivas de la devolucion
                $resultado_iva_devolucion=$conn->ObtenerFilasBySqlSelect($sql);
                 
                //si es diferente de null podemos hacer el  foreach
                $iva8=0;
                $iva12=0;
                $ivaotro=0;
                if($resultado_iva!=null)
                {
                    foreach ($resultado_iva as $key => $value) 
                    {
                        if($value['rate']=='0.08')
                        {
                            $iva8+=$value['iva'];
                        }
                        if($value['rate']=='0.12')
                        {
                            $iva12+=$value['iva'];
                        }
                        if($value['rate']!='0.12' && $value['rate']!='0.08' && $value['rate']!='0')
                        {
                            $ivaotro+=$value['iva'];
                        }
                    }
                };


                if($resultado_iva_devolucion!=null)
                {
                    foreach ($resultado_iva_devolucion as $key => $value) 
                    {
                        if($value['rate']=='0.08')
                        {
                            $iva8-=$value['iva'];
                        }
                        if($value['rate']=='0.12')
                        {
                            $iva12-=$value['iva'];
                        }
                        if($value['rate']!='0.12' && $value['rate']!='0.08' && $value['rate']!='0')
                        {
                            $ivaotro-=$value['iva'];
                        }
                    }
                };

                //fin de agregar el iva
                

                $devoluciones=$conn->ObtenerFilasBySqlSelect("Select sum(payments.total) as total FROM ".POS.".payments INNER JOIN ".POS.".receipts ON receipts.ID = payments.RECEIPT 
                INNER JOIN ".POS.".tickets ON tickets.ID = receipts.ID 
                INNER JOIN ".POS.".people ON tickets.PERSON = people.ID
                WHERE DATENEW BETWEEN '".$fecha_inicio[0]['fecha_fin']."' 
                AND now() and people.id='".$_POST['cajero']."' 
                and payments.id in 
                (select payments.id from  ".POS.".payments INNER JOIN ".POS.".receipts ON receipts.ID = payments.RECEIPT  
                 WHERE DATENEW BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now() 
                 and payment in ('magcardrefund', 'cashrefund'))");

                
                $resultado[0]['total_cajero']= number_format($total_cajero,2,',','');
                $resultado[0]['TOTAL_SISTEMA']= $resultado[0]['total_efectivo_sistema']+$resultado[0]['total_tj_sistema']+$resultado[0]['total_tickets_sistema'];
                $resta=$total_cajero-$resultado[0]['TOTAL_SISTEMA'];
                $total_sistema_formato=$resultado[0]['TOTAL_SISTEMA'];
                $devolucion= ($devoluciones[0]['total']*-1);
                $resultado[0]['TOTAL_SISTEMA']= number_format($resultado[0]['TOTAL_SISTEMA']-$devolucion,2,',','');
                
                //$resta=($resultado[0]['total_cajero']-$resultado[0]['TOTAL_SISTEMA']);
                //$resta=number_format($resta,2,',','');
               
                if($resultado[0]['TOTAL_SISTEMA']!="0,00")
                {
                    //verificamos 
                    //orden 0=codigo,1=total_cajero,2=total_sistema,3=resta,4=total_efectivo_sistema,5=total_tj_sistema,6=total_tickets_sistema,7=total_devoluciones
                    if($resta==0)
                    {
                        echo "0_".$resultado[0]['total_cajero']."_".$total_sistema_formato."_".$resta."_".$resultado[0]['total_efectivo_sistema']."_".$resultado[0]['total_tj_sistema']."_".$resultado[0]['total_tickets_sistema']."_".($resultado[0]['total_dev_efectivo']+$resultado[0]['total_dev_tarjeta']."_0"."_0"."_0_".$iva12."_".$iva8."_".$ivaotro."_0_0_".$tipoarray);
                    }
                    if($resta>0)
                    {
                        //sobrante
                        echo "1_".$resultado[0]['total_cajero']."_".$total_sistema_formato."_".$resta."_".$resultado[0]['total_efectivo_sistema']."_".$resultado[0]['total_tj_sistema']."_".$resultado[0]['total_tickets_sistema']."_".($resultado[0]['total_dev_efectivo']+$resultado[0]['total_dev_tarjeta']."_0"."_0"."_0_".$iva12."_".$iva8."_".$ivaotro."_0_0_".$tipoarray);
                    }
                    if($resta<0)
                    {
                        //faltante
                        echo "2_".$resultado[0]['total_cajero']."_".$total_sistema_formato."_".$resta."_".$resultado[0]['total_efectivo_sistema']."_".$resultado[0]['total_tj_sistema']."_".$resultado[0]['total_tickets_sistema']."_".($resultado[0]['total_dev_efectivo']+$resultado[0]['total_dev_tarjeta']."_0"."_0"."_0_".$iva12."_".$iva8."_".$ivaotro."_0_0_".$tipoarray);
                    }

                }// fin del if
                else
                {
                    echo "333";
                }
            
            }
            else
            {
                echo "_4";
            }

        break;
        


        case "guardar_arqueo_retiro" :
            if(isset($_POST['tipo_venta_pyme']))
            {
                $tipo_venta_pyme=$_POST['tipo_venta_pyme'];
            }
            else
            {
                $tipo_venta_pyme=1;
            }
            $conn->BeginTrans();
            $total_cajero="";
            //calculamos el total de todo lo que hizo el cajero
            for($i=0; $i<count($_POST['valoresarray']); $i++)
            {
                $total_cajero+=($_POST['valoresarray'][$i]*$_POST['billetesarray'][$i]);
            }
            $total_cajero+=($_POST['total_monedas']);
            //insertamos en el maestro de retiro_efectivo
            $insert=$conn->Execute2
            ("INSERT INTO retiro_efectivo(id_cajero, monedas, efectivo, cod_usuario, tipo_venta) 
            VALUES
            (   '".$_POST['cajero']."',
                ".$_POST['total_monedas'].",
                ".$total_cajero.",
                '".$_SESSION["cod_usuario"]."',
                '".$tipo_venta_pyme."'
            )
            ");
             //Obtener id del arqueo cajero retiro
            $idarqueo=$conn->ObtenerFilasBySqlSelect("SELECT LAST_INSERT_ID()");
            //recorremos los arreglos de los billetes para guardar en la tabla billetes-retiro_efectivo
            for($i=0; $i<count($_POST['valoresarray']); $i++)
            {
                $idbillete=$conn->ObtenerFilasBySqlSelect("Select id from billetes where valor=".$_POST['billetesarray'][$i]);
                $insertar=$conn->Execute2('insert into billetes_retiro_efectivo (id_retiro_efectivo, id_billete, cantidad) 
                    values
                    ('.$idarqueo[0]['LAST_INSERT_ID()'].', '.$idbillete[0]['id'].', '.$_POST['valoresarray'][$i].')'); 
            }

            $conn->CommitTrans(1);
            $retiro=1;
            if($retiro==1)
            {
                echo 1;
            }
            else
            {
                echo 2;
            }
        break;



        case "formulario_arqueo_cajero_retiro" :
            //buscamos los billetes que esten habilitados
            $billetes=$conn->ObtenerFilasBySqlSelect("select * from billetes where estatus=1");
            echo '
                <tr class="edocuenta_detalle">
                    <td colspan="8">
                        <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                            <table >
            ';
            $billetes_validos="";//variable para guardar los billetes y validarlos por el javascript
            foreach ($billetes as $key => $value) 
            {
                echo 
                '
                    <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px; align:left;">
                            Cantidad Billetes De '.$value['valor'].' '.$value['denominacion'].':
                        </th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">
                            <input type="text" name="'.$value['valor'] .'" id="'.$value['valor'] .'"  class="form-text serialSec " style="width:100px; align:center">
                        </th>
                    </thead>
                ';
                //se agregan los billetes validos, para validar en el javascript
                $billetesvalidos.=$value['valor'].",";

            } 
            //eliminado ultima coma
            $billetesvalidos=substr($billetesvalidos, 0, -1);
            echo 
            '
                        <thead>
                            <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                Total Monedas:
                            </th>
                            <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                <input type="text" name="total_monedas" id="total_monedas"  class="form-text serialSec " style="width:100px; align:center">
                            </th>
                        </thead>
                        <thead>
                            <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                Total Tarjeta:
                            </th>
                            <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                <input type="text" name="total_tarjeta" id="total_tarjeta"  class="form-text serialSec " style="width:100px; align:center">
                            </th>
                        </thead>
                        <thead>
                                <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                    Total Tickets:
                                </th>
                                <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                    <input type="text" name="total_ticket" id="total_ticket"  class="form-text serialSec " style="width:100px; align:center"><input type="hidden" name="arqueo" id="arqueo" value="1">
                                    <input type="hidden" name="cajero" id="cajero" value='.$_POST['cajero'].' />
                                    <input type="hidden" name="billetesvalidos" id="billetesvalidos" value='.$billetesvalidos.' />
                                </th>
                        </thead>
                    </table>
                </div>
            </td>
        </tr>
            ';

        break;





        case "verificar_cajero_pyme":

        $obtener_fecha_cajero=$conn->ObtenerFilasBySqlSelect("SELECT fecha_fin, id_cajero from control_cierre_cajero where id_cajero='".$_POST['cajero']."' and tipo=0 order by secuencia desc limit 1");
        if($obtener_fecha_cajero[0]['fecha_fin']==""){
            echo "-1"; //no existe cajero en la tabla tenemos que abrir el formulari de registro por primera vez
        }else{
           
        echo 1;

        }


        break;




        case "calcular_pyme":
        $sql="select date(a.fecha_fin) as DATEEND, money from closedcash_pyme as a 
where a.money not in  (select  money from libro_ventas) and date(fecha_fin)>(select min(fecha) from fechas_minimas) and a.nombre_caja='".$_POST['caja']."' group by nombre_caja order by nombre_caja, fecha_fin  asc";
        $fechas_secuencia=$conn->ObtenerFilasBySqlSelect($sql);

        $sql="SELECT sum(totalizar_base_imponible) as base, sum(totalizar_total_general) as bruto, sum(totalizar_monto_iva) as iva, sum(case when totalizar_monto_iva=0 then totalizar_base_imponible else 0 end) as exento  FROM `factura` WHERE date(`fecha_creacion`)='".$_POST[fecha]."' and money='".$fechas_secuencia[0]['money']."' and impresora_serial='".$_POST['serial']."'";
        $calcular=$conn->ObtenerFilasBySqlSelect($sql);

        
        //$calcular=$conn->ObtenerFilasBySqlSelect("SELECT sum(c.base) as base, sum(c.amount) as iva, sum(c.base+c.amount) as bruto, sum(case when c.amount=0 then c.base else 0 end) as exento FROM ".POS.".closedcash as a, ".POS.".receipts as b, ".POS.".taxlines as c where a.money=b.money and b.id=c.receipt and date(a.dateend)='".$_POST[fecha]."' and a.MONEY='".$fechas_secuencia[0]['money']."' and a.HOST='".$_POST['caja']."' ORDER BY a.HOSTSEQUENCE ASC"); 
        if($calcular[0]['bruto']>0){
            echo $calcular[0]['base']." ".$calcular[0]['bruto']." ".$calcular[0]['exento']." ".$calcular[0]['iva'];

        }else{
            echo -1;
        }

        break;



        case "formulario_libro_ventas_pyme":
        $serial =$conn->ObtenerFilasBySqlSelect("Select serial_impresora from caja_impresora where caja_host='".$_POST['caja']."'");
        $ultimo_z=$conn->ObtenerFilasBySqlSelect("select numero_z_usuario+1 as z from libro_ventas where serial_impresora='".$serial[0]['serial_impresora']."' order by numero_z_usuario desc limit 1");
        
        //$ultimo_z=$conn->ObtenerFilasBySqlSelect("select numero_z_usuario+1 as z, money from libro_ventas where serial_impresora='".$serial[0]['serial_impresora']."' order by numero_z_usuario desc limit 1");
        $fecha_venta=$conn->ObtenerFilasBySqlSelect("select  money from $bd_pyme.closedcash_pyme as a 
where a.money not in  (select  money from $bd_pyme.libro_ventas) and date(fecha_fin)>=(select min(fecha) from $bd_pyme.fechas_minimas) and a.nombre_caja='".$_POST['caja']."' group by nombre_caja order by nombre_caja, fecha_fin  asc");
        //echo "select  money from $bd_pyme.closedcash_pyme as a 
//where a.money not in  (select  money from $bd_pyme.libro_ventas) and date(fecha_fin)>(select min(fecha) from $bd_pyme.fechas_minimas) and a.nombre_caja='".$_POST['caja']."' group by nombre_caja order by nombre_caja, fecha_fin  asc"; exit();
        $numero_facturas=$conn->ObtenerFilasBySqlSelect("select count(a.id_factura) as total_factura from $bd_pyme.factura as a where a.money='".$fecha_venta[0]['money']."'");
        $sql="select date(fecha_creacion) as fecha from $bd_pyme.factura where money='".$fecha_venta[0]['money']."'";
        $obtener_fecha_ultima_venta=$conn->ObtenerFilasBySqlSelect($sql);


         if(!isset($ultimo_z[0]['z'])){
            $ultimo_z[0]['z']="";
            
            }else{
                $ultimo_z[0]['z']=$ultimo_z[0]['z'];}
         echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px; align:left;">Serial Impresora</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">Nro. Z</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">Ultima Factura</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">Nro. Facturas</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;" value=0>Ultima Nota Credito</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;" value=0>Nro. Notas De Credito</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Fecha</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Monto Bruto</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Monto Exento</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Base Imponible</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">IVA</th>
                    </thead>

                    <tbody>
                    <td align="center" ><input type="text" name="serial" id="serial"  class="form-text serialSec " required style="width:100px;" value='.$serial[0]['serial_impresora'].' readonly></td>
                    <td align="center" ><input  type="hidden" name="z" id="z"  class="form-text serialSec " style="width:40px;" value='.$ultimo_z[0]['z'].'><input  type="text" name="z_usuario" id="z_usuario"  class="form-text serialSec " style="width:40px;" value='.$ultimo_z[0]['z'].'></td>
                    <th align="center" ><input type="text" name="ultima_factura" id="ultima_factura"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name="nro_facturas" id="nro_facturas"  class="form-text serialSec" value='.$numero_facturas[0]['total_factura'].' style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name="ultima_nc" id="ultima_nc"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name="nro_ncs" id="nro_ncs"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name=fecha id="fecha" value='.$obtener_fecha_ultima_venta[0]['fecha'].' readonly="readonly" class="form-text serialSec " required  style="width:80px; align:center"></th>

                    <script type="text/javascript">//<![CDATA[
                        var cal = Calendar.setup({
                            onSelect: function(cal) {
                                cal.hide();
                            }
                        });
                        cal.manageFields("fecha", "fecha", "%Y-%m-%d");
                    //]]>
                    </script>
                    
                
                    <th align="center" ><input type="hidden" name="monto_bruto" id="monto_bruto" class="form-text serialSec " style="width:100px; align:center"><input type="text" name="monto_bruto_usuario" id="monto_bruto_usuario" onfocus=calcular_pyme();  class="form-text serialSec " style="width:100px; align:center"></th>
                    <th align="center" ><input type="hidden" name="monto_exento" id="monto_exento"  class="form-text serialSec " style="width:100px; align:center"><input type="text" name="monto_exento_usuario" id="monto_exento_usuario"  class="form-text serialSec " style="width:100px; align:center"></th>
                    <th align="center" ><input type="hidden" name="base_imponible" id="base_imponible"  class="form-text serialSec " style="width:100px; align:center"><input type="text" name="base_imponible_usuario" id="base_imponible_usuario"  class="form-text serialSec " style="width:100px; align:center"></th>
                    <th align="center" ><input type="hidden" name="iva" id="iva"  class="form-text serialSec " style="width:100px; align:center"><input type="text" name="iva_usuario" id="iva_usuario"  class="form-text serialSec " style="width:100px; align:center">
                    <input type="hidden" name="caja" id="caja"  value="'.$_POST['caja'].'"/>
                    <input type="hidden" name="tipo_venta" id="tipo_venta"  value="'.$_POST['tipo'].'"/>
                    <input type="hidden" name="cierres" id="cierres"  value="'.$_POST['cierres'].'"/>
                    <input type="hidden" name="money" id="money"  value="'.$fecha_venta[0]['money'].'"/>
                    </th>
                    </tbody>';



        break;

        case "comprobar_cierre_caja_pyme":
 
        $comprobar_cierre_caja=$conn->ObtenerFilasBySqlSelect("SELECT (max(a.secuencia)) as max_pyme, max(b.secuencia) as max_venta_pyme FROM cierre_caja_control_pyme as a, closedcash_pyme as b WHERE a.serial_cajas=b.serial_caja and b.nombre_caja='".$_POST['caja']."' and b.fecha_fin is not  null");
        //echo "SELECT max(a.secuencia) as max_pyme, max(b.secuencia) as max_venta_pyme FROM cierre_caja_control_pyme as a, closedcash_pyme as b WHERE a.serial_cajas=b.serial_caja and b.nombre_caja='".$_POST['caja']."' and b.fecha_fin is not null"; exit();       
       
        if(($comprobar_cierre_caja[0]['max_pyme']+1)==$comprobar_cierre_caja[0]['max_venta_pyme']){
            echo 1; //caja de cierre correcto y no posee mas cierre al dia
        }else{

             if($comprobar_cierre_caja[0]['max_pyme']==$comprobar_cierre_caja[0]['max_venta_pyme']){
                    echo -1; //error no se ha cerrado la caja
             }else{
                if(($comprobar_cierre_caja[0]['max_pyme']+1)<$comprobar_cierre_caja[0]['max_venta_pyme']){
                    echo 2; //cierre de caja correcto, pero han habido varios cierres.
                }
             }

        }
       

        break;


         case "verificar_tipo_venta":

        $obtener_tipo_venta=$conn->ObtenerFilasBySqlSelect("select venta_pyme from parametros_generales");
        if($obtener_tipo_venta[0]['venta_pyme']=="2"){
            echo "1"; //no existe cajero en la tabla tenemos que abrir el formulari de registro por primera vez
        }else{
           
        echo 2;

        }




        break;


        case "pendiente_producto":
        $obtener=$conn->ObtenerFilasBySqlSelect("select count(id) as total from sincronizacion_productos_detalle where id<".$_POST['id_det_sincro']." and codigo_barra='".$_POST['codigo_barra']."' and estatus=1");
        //echo "select count(id) as total from sincronizacion_productos_detalle where id<".$_POST['id_det_sincro']." and codigo_barra='".$_POST['codigo_barra']."' and estatus=1"; exit();       
        if($obtener[0]['total']>0){
            echo 0;
        }else{
            echo 1;
        }

        break;

        case "insertar_cajas_operativas":
        $obtener_id=$conn->ObtenerFilasBySqlSelect("Select id from operaciones where libro_venta=-1 limit 1");
        $conn->Execute2("update operaciones set libro_venta='".$_POST['cajas_operativas']."' where id=".$obtener_id[0]['id']."");

        break;

        case "insertar_cajeros_activos":
        $obtener_id=$conn->ObtenerFilasBySqlSelect("Select id from operaciones where cierre_cajero=-1 limit 1");
        $conn->Execute2("update operaciones set cierre_cajero='".$_POST['cajeros_activos']."' where id=".$obtener_id[0]['id']);
        echo "1";
        break;


    case "abrir_cajeros":

// si es -1 nunca entro a libro de ventas, si es un numero distinto de 0 es que falta cerrar cajero y si es 0 todo fue bien
//pregunto si es -1 si es correcto es primera entrada a cajero  debo preguntar cuantos cajeros abriran
        $verificar = $conn->ObtenerFilasBySqlSelect("Select max(fecha) as fecha from operaciones where cierre_cajero=-1");
        if(isset($verificar[0]['fecha'])){


         echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                        <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:200px; align:left;">¿Indique La Cantidad De Cajeros A Cerrar?:</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;"><input type="text" name="cajeros_activos" id="cajeros_activos"  class="form-text serialSec " style="width:100px; align:center"></th>
                        </thead>
                       
                    '; 
                }else{
                    // si no es -1 quiere decir q ya declaro cuantas cajas operativas y debe ir al proceso normal.
                    echo "1";
                }

        break;
        case "verificar_cataporte":
        $existe_cataporte =$conn->ObtenerFilasBySqlSelect("SELECT count(*) as resultado FROM $bd_pyme.cataporte WHERE nro_cataporte = '".$_POST['val']."'");
        if($existe_cataporte[0]['resultado'] == 0){
                echo "<span style='font-weight:bold;color:green;'>Nro de Cataporte Disponible.</span>
                <input hidden='hidden' type='text' name='cat_val' value=2 >";
            }else{
                echo "<span style='font-weight:bold;color:red;'>El Nro de Cataporte ya existe.</span>
                <input hidden='hidden' type='text' name='cat_val' value=1 >";
            }

        break;

        case "abrir_cajas":

// si es -1 nunca entro a libro de ventas, si es un numero distinto de 0 es que falta cerrar libro y si es 0 todo fue bien
//pregunto si es -1 si es correcto es primera entrada a libro de venta debo preguntar cuantas cajas operativas
        $verificar = $conn->ObtenerFilasBySqlSelect("Select max(fecha) as fecha from operaciones where libro_venta=-1");
        if(isset($verificar[0]['fecha'])){


         echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                        <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:200px; align:left;">¿Indique La Cantidad De Cajas A Cerrar?:</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;"><input type="text" name="cajas_operativas" id="cajas_operativas"  class="form-text serialSec " style="width:100px; align:center"></th>
                        </thead>
                       
                    '; 
                }else{
                    // si no es -1 quiere decir q ya declaro cuantas cajas operativas y debe ir al proceso normal.
                    echo "1";
                }

        break;

        case "cambiar_estatus_cataporte":
        require_once("../../../libs/php/clases/login.php");
            $login = new Login();
            //buscamos los datos del deposito
            $nro_cataporte=$conn->ObtenerFilasBySqlSelect("select * from cataporte where id=".$_POST['id']."");
            $existe_ingreso=$conn->ObtenerFilasBySqlSelect("select count(id) as id from ingresos_xenviar where nro_cataporte='".$nro_cataporte[0]['nro_cataporte']."'");
            //si existe se le hace update
            if($existe_ingreso[0]['id']>0)
            {
                $update=$conn->Execute2("update ingresos_xenviar set fecha_retiro='".date('Y-m-d H:i:s')."' where nro_cataporte='".$nro_cataporte[0]['nro_cataporte']."'");
            }
            else
            {
            //caso contrario se le hace un insert por lo que hay q buscar los valores del deposito
            $deposito=$conn->ObtenerFilasBySqlSelect("select * from caja_principal where id_cataporte='".$nro_cataporte[0]['nro_cataporte']."'");
                foreach ($deposito as $deposito1) 
                {
                    $ingre_insert=$conn->ExecuteTrans(
                        "insert into ingresos_xenviar 
                        (nro_deposito, fecha_deposito, monto_deposito, cuenta_banco, usuario_creacion, nro_cataporte, fecha_cataporte, fecha_retiro, usuario_creacion_cataporte)
                    VALUES
                    ('".$deposito1['nro_deposito']."', '".$deposito1['fecha_deposito']."', '".$deposito1['monto']."', '".$deposito1['cod_banco']."', '".$deposito1['usuario_creacion']."', '".$nro_cataporte[0]['nro_cataporte']."', '".$nro_cataporte[0]['fecha']."', '".date('Y-m-d H:i:s')."', '".$login->getNombreApellidoUSuario()."')");
                }
            }


            $cambiando=$conn->Execute2("update cataporte set retirado=now() where id=".$_POST['id']);
            if($cambiando)
            {
                echo 1;
            }else
            {
                echo 2;
            }

        break;

        case "cambiar_estatus_nota_entrega":

        $cambiando=$conn->Execute2("update nota_entrega set cod_estatus=2 where cod_nota_entrega=".$_POST['id']);
        if($cambiando){
            echo 1;
        }else{
            echo 2;
        }
        break;


       case "calcular_monto_cataporte":
        $_POST['arreglo']=str_replace("\\", "", $_POST['arreglo']);

        //$deposito=$conn->ObtenerFilasBySqlSelect("Select * from deposito where nro_deposito in (".$_POST['arreglo'].")");
        $deposito=$conn->ObtenerFilasBySqlSelect("Select * from caja_principal where nro_deposito in (".$_POST['arreglo'].")");
//echo "Select * from deposito where nro_deposito in (".$_POST['arreglo'].")"; 

        //creando formulario
        echo "<table border=1 width='25%'>
        <thead class='tb-head'>
        <tr>
            <th colspan=2><center>Cataporte</center></th>
        </tr>
        </thead>
        <tr>
            <td width='40%' align='center'><b>Nro Deposito</b></td>
            <td width='40%' align='center'><b>Monto</b></td>
        </tr>
        ";
        $total=0;
        //$total_format=0;
        foreach ($deposito as $key ) {
            if(isset($key['monto_acumulado'])){
            $total+=$key['monto_acumulado'];
            $monto_format=number_format($key['monto_acumulado'],2,'.', '');//se cambio monto acumulado
            $total_format=number_format($total,2,'.', '');
            
       echo "
        <tr>
            <td width='40%' align='center'><input type='hidden' name=nro_depositos[] value=".$key['nro_deposito']." />".$key['nro_deposito']."</td>
            <td width='40%' align='right'>".$monto_format."</td>
        </tr>";
       }} echo"
            <tr>
        <th><center><b>TOTAL</b><center></th>
        <td align='right'><b>".$total_format."</b></th>
        </tr>
        </table>
        <br>
        <table cellspacing=2>
        <tr>
        <td align='right'><b>Nro Cataporte:</b></td><td><input type='text' name='nro_cataporte' id='nro_cataporte' size=20 onkeyup='aMays(event, this)' onBlur='comprobar(this.id)'/></td>
        <div id='resultado2' width='50%' align='center'></div>
        </tr>
        <tr>
        <td colspan=2><p>&nbsp;</p></td>
        </tr>
        <br>
        <tr>
        <td align='right'><b>Bolsas Cataporte:</b></td><td> <input type='text' name='cantidad' id='cantidad' value='' onkeyup='crearCampos(this.value);' size=3/></center></td>
        </tr>
        <tr id='monto_usuario_tr22' style='visibility:hidden'>
            <td align='center' colspan='2'>&nbsp;</td>
            
        </tr>
        <tr id='monto_usuario_tr' >
            <td align='center'><b>Monto Usuario </b></td>
            <td align='center'>
                <input type='text'  name='monto_usuario' id='monto_usuario' /> 
                <input type='hidden'  name='montosistema' id='montosistema' value=".$total_format." />
            </td>
            
        </tr>
        <tr id='monto_usuario_tr1' >
            <td align='center'><b>Observacion </b></td>
            <td align='center'><input type='text'  name='observacion' id='observacion' /> </td>
        </tr>
        
        </table>
        <br><div id='campos_dinamicos'></div>
        <br>
        <table border=0 width='25%' cellspacing=2>
        <tr>
        <!--<td align='center' colspan=2><button type='button' onClick='modificar()' id='boton_env'>Modificar Monto</button></td>-->
        <td align='center' colspan=2><button type='button' onClick='enviar()' id='boton_env'>Confirmar Cataporte</button></td>  
        </tr>
        </table>";
        break;


       case "calcular_monto_deposito":
        //si es pos
        if($_POST['venta_pyme']==1)
        {
        $cajeros=$conn->ObtenerFilasBySqlSelect("Select a.total_efectivo_sistema as total_efectivo_sistema, a.efectivo as efectivo, a.tarjeta as tarjeta, a.tickets as tickets, a.total_sistema as total_sistema, b.NAME as NAME, a.fecha_venta_fin as fecha_venta_fin, a.id as id  from arqueo_cajero as a, ".POS.".people as b where a.id in (".$_POST['arreglo'].") and a.id_cajero=b.id");
        }

        //si es pyme
        if($_POST['venta_pyme']==0)
        {
        $cajeros=$conn->ObtenerFilasBySqlSelect("Select a.total_efectivo_sistema as total_efectivo_sistema, a.efectivo as efectivo, a.tarjeta as tarjeta, a.tickets as tickets, a.total_sistema as total_sistema, b.nombreyapellido as NAME, a.fecha_venta_fin as fecha_venta_fin, a.id as id  from arqueo_cajero as a, usuarios as b where a.id in (".$_POST['arreglo'].") and a.id_cajero=b.cod_usuario");
        }

        //si es ambas
         if($_POST['venta_pyme']==2)
        {
           
        $cajeros=$conn->ObtenerFilasBySqlSelect("
                (Select a.total_efectivo_sistema as total_efectivo_sistema, a.efectivo as efectivo, a.tarjeta as tarjeta, a.tickets as tickets, a.total_sistema as total_sistema, b.nombreyapellido as NAME, a.fecha_venta_fin as fecha_venta_fin, a.id as id  from arqueo_cajero as a, usuarios as b where a.id in (".$_POST['arreglo'].") and a.id_cajero=b.cod_usuario)
                union all
                (Select a.total_efectivo_sistema as total_efectivo_sistema, a.efectivo as efectivo, a.tarjeta as tarjeta, a.tickets as tickets, a.total_sistema as total_sistema, b.NAME as NAME, a.fecha_venta_fin as fecha_venta_fin, a.id as id  from arqueo_cajero as a, ".POS.".people as b where a.id in (".$_POST['arreglo'].") and a.id_cajero=b.id)
                ");

        }



     //  echo "Select a.efectivo as efectivo, a.tarjeta as tarjeta, a.tickets as tickets, a.total_sistema as total_sistema, b.NAME as NAME, a.fecha_venta_fin as fecha_venta_fin, a.id as id * from arqueo_cajero as a, ".POS.".people as b where a.id in (".$_POST['arreglo'].") and a.id_cajero=b.id";
        echo "<table border=1 width='25%'>
        <thead class='tb-head'>
        <tr>
            <th colspan=3><center>Detalle</center></th>
        </tr>
        </thead>
        <tr>
            <td width='40%' align='center'><b>Cajero</b></td>
            <td width='20%' align='center'><b>Fecha Final</b></td>
            <td width='40%' align='center'><b>Monto</b></td>
        </tr>
        <tr>";
        $total="";
        foreach ($cajeros as $key ) {
            if($key['efectivo']<$key['total_efectivo_sistema'] || $key['efectivo']==$key['total_efectivo_sistema'] )
                $total+=$key['efectivo'];
            else//($key['efectivo']>$key['total_efectivo_sistema'])
            $total+=$key['total_efectivo_sistema'];
            
            $sobrante+=$key['efectivo']-$key['total_efectivo_sistema'];//+$key['tarjeta']+$key['tickets'])-$key['total_sistema'];
            
        echo "
            <td width='40%' align='center'>".$key['NAME']."</td>
            <td width='20%' align='center'>".$key['fecha_venta_fin']."</td>
            <td width='40%' align='right'>".$key['efectivo']."<input type='hidden' name=arqueos_id[] value=".$key['id']." /></td>

        </tr>";

        }
        echo "
        <tr>
        <th colspan=2><center><b>TOTAL</b><center></th>
        <td align='right'><b>".number_format($total,2)."</b></th></tr><tr>
        ";if($sobrante>0){ echo "
        <th colspan=2><center><b>TOTAL DIFERENCIA</b><center></th>
        <td align='right'><b>".number_format($sobrante,2)."</b></th> ";} echo "
        </tr>
        </table>
        
        <br>";
       
        $bancos=$conn->ObtenerFilasBySqlSelect("SELECT b.nro_cuenta, b.descripcion as cuenta, a.descripcion as banco FROM banco as a, cuentas_contables as b, parametros_generales as c where a.cod_banco=b.banco and c.cta_captadora=b.nro_cuenta");
        $cta_sobrante=$conn->ObtenerFilasBySqlSelect("SELECT b.nro_cuenta, b.descripcion as cuenta, a.descripcion as banco FROM banco as a, cuentas_contables as b, parametros_generales as c where a.cod_banco=b.banco and c.cta_sobrante=b.nro_cuenta");

        echo"
        <table cellspacing=2>
        <tr>
        <th style='visibility:hidden'><center><b>SELECCIONE CUENTA CAPTABLE</b></center></th>
        </tr>
        <td>
            <select name='banco' id='banco' style='visibility:hidden'>";
            foreach ($bancos as $key ) {
                echo "             
                <option value=".$key['nro_cuenta'].">".$key['cuenta']."- Banco: ".$key['banco']."</option>
               ";}
                echo"
                 </select>
                 <input type='hidden' name='monto' id='monto' value=".$total." />
                 <input type='hidden' name='sobrante' id='sobrante' value=".$sobrante." />
        </td>
        </tr>
        <tr>
        ";
        if ($sobrante>0){ echo "
        <th style='visibility:hidden'><center><b>SELECCIONE CUENTA SOBRANTE</b></center></th>
        </tr>
         <td>
            <select name='sobrante' id='sobrante' style='visibility:hidden'>";
            foreach ($cta_sobrante as $key ) 
            {
                echo "             
                <option value=".$key['nro_cuenta'].">".$key['cuenta']."- Banco: ".$key['banco']."</option>
               ";
           }
                echo"
                 </select>
                 <input type='hidden' name='monto' id='monto' value=".$total." />
                 <input type='hidden' name='cta_sobrante' id='cta_sobrante' value=".$sobrante." />
        </td>
        </table>
        ";} echo "
        <br>
        <table border=0 width='25%' cellspacing=2>
        <tr>        
        <td align='center' colspan=2><button type='button' onClick='enviar()'>Confirmar Transferencia</button></td>  
        </tr>
        </table>";
        exit();

        break;


        case "Validarnombre_caja":
        
        $verificar=$conn->ObtenerFilasBySqlSelect("select count(caja_host) as existe from caja_impresora where caja_host='".$_GET['v1']."'");
        if($verificar[0]['existe']==0){
            echo 1;
        }else{
            echo -1;
        }
        break;

        case "Validarserial_caja":
        $verificar=$conn->ObtenerFilasBySqlSelect("select count(serial_impresora) as existe from caja_impresora where serial_impresora='".$_GET['v1']."'");
        if($verificar[0]['existe']==0){
            echo 1;
        }else{
            echo -1;
        }
        break;

        case "insertar_libro_ventas":
        
         $obtener_serial=$conn->ObtenerFilasBySqlSelect("SELECT count(serial_impresora) as seriales from caja_impresora where serial_impresora='".$_POST['serial']."'");
         if($obtener_serial['0']['seriales']==0){
            echo -4;
            exit();
         }



        // $obtenr_nuevo_money=$conn->ObtenerFilasBySqlSelect("select money as money from ".POS.".closedcash where host='".$_POST['caja']."'  and dateend is null");
       
        $conn->BeginTrans();

        if(!isset($_POST['tipoventa'])){
            

        $obtener_secuencia=$conn->ObtenerFilasBySqlSelect("SELECT max(b.HOSTSEQUENCE) as max_pos FROM ".POS.".closedcash as b WHERE b.HOST='".$_POST['caja']."' and b.dateend is not null order by dateend desc limit 1");
        $campos2=$conn->ExecuteTrans("update cierre_caja_control set secuencia='".$obtener_secuencia[0]['max_pos']."', estatus_cierre=0 where cajas='".$_POST['caja']."' ");
        if($_POST['ultima_nc']==""){
        $_POST['ultima_nc']='NULL';
            }
        if($_POST['nro_ncs']==""){
        $_POST['nro_ncs']='NULL';
        }
        
       /* $campos = $conn->ExecuteTrans("INSERT INTO libro_ventas(
                serial_impresora,
                numero_z,
                numero_z_usuario,
               
              
                money
                ) 
            VALUES
                (
                '".$_POST['serial']."',
                '".($_POST['z']+1)."',
                '".($_POST['z_usuario']+1)."',
                '".$obtenr_nuevo_money[0]['money']."'
                )
                ");*/
        $campos = $conn->ExecuteTrans("INSERT INTO libro_ventas(
                
                serial_impresora,
                numero_z,
                numero_z_usuario,
                ultima_factura,
                numeros_facturas,
                ultima_nc,
                numeros_ncs,
                fecha,
                monto_bruto,
                monto_exento,
                base_imponible,
                iva,
                monto_bruto_usuario,
                monto_exento_usuario,
                base_imponible_usuario,
                iva_usuario,
                fecha_emision,
                 money,
                id_usuario_creacion
               

                ) 
            VALUES
                (
                '".$_POST['serial']."',
                '".($_POST['z'])."',
                '".($_POST['z_usuario'])."',
                '".$_POST['ultima_factura']."',
                '".$_POST['nro_facturas']."',
                ".$_POST['ultima_nc'].",
                ".$_POST['nro_ncs'].",
                '".$_POST['fecha']."',
                '".$_POST['monto_bruto']."',
                '".$_POST['monto_exento']."',
                '".$_POST['base_imponible']."',
                '".$_POST['iva']."',
                '".$_POST['monto_bruto_usuario']."',
                '".$_POST['monto_exento_usuario']."',
                '".$_POST['base_imponible_usuario']."',
                '".$_POST['iva_usuario']."',
                now(),
                '".$_POST['money']."',
                '".$_SESSION['cod_usuario']."'

                )
                ");

/*echo 
"INSERT INTO libro_ventas(
                
                serial_impresora,
                numero_z,
                numero_z_usuario,
                ultima_factura,
                numeros_facturas,
                ultima_nc,
                numeros_ncs,
                fecha,
                monto_bruto,
                monto_exento,
                base_imponible,
                iva,
                monto_bruto_usuario,
                monto_exento_usuario,
                base_imponible_usuario,
                iva_usuario,
                fecha_emision,
                 money,
                id_usuario_creacion
               

                ) 
            VALUES
                (
                '".$_POST['serial']."',
                '".($_POST['z'])."',
                '".($_POST['z_usuario'])."',
                '".$_POST['ultima_factura']."',
                '".$_POST['nro_facturas']."',
                ".$_POST['ultima_nc'].",
                ".$_POST['nro_ncs'].",
                '".$_POST['fecha']."',
                '".$_POST['monto_bruto']."',
                '".$_POST['monto_exento']."',
                '".$_POST['base_imponible']."',
                '".$_POST['iva']."',
                '".$_POST['monto_bruto_usuario']."',
                '".$_POST['monto_exento_usuario']."',
                '".$_POST['base_imponible_usuario']."',
                '".$_POST['iva_usuario']."',
                now(),
                '".$_POST['money']."',
                '".$_SESSION['cod_usuario']."'

                )
                "; exit();*/

            /*$update = $conn->ExecuteTrans("
            update libro_ventas set
           
            ultima_factura= '".$_POST['ultima_factura']."',
            numeros_facturas='".$_POST['nro_facturas']."',
            numero_z_usuario='".$_POST['z_usuario']."',
            ultima_nc= ".$_POST['ultima_nc'].",
            numeros_ncs= ".$_POST['nro_ncs'].",
            fecha= '".$_POST['fecha']."',
            monto_bruto='".$_POST['monto_bruto']."',
            monto_exento= '".$_POST['monto_exento']."',
            base_imponible='".$_POST['base_imponible']."',
            iva=  '".$_POST['iva']."',
            monto_bruto_usuario='".$_POST['monto_bruto_usuario']."',
            monto_exento_usuario='".$_POST['monto_exento_usuario']."',
            base_imponible_usuario='".$_POST['base_imponible_usuario']."',
            iva_usuario='".$_POST['iva_usuario']."',
            fecha_emision= now(),
            id_usuario_creacion= '".$_SESSION['cod_usuario']."',
            secuencia= '".($obtener_secuencia[0]['max_pos']-1)."' 
            where money = '".$_POST['money']."'");*/



        //insertar en libro_ventas_xenviar
    
        $insert_enviar=$conn->ExecuteTrans("
           INSERT INTO libroventas_xenviar(serial_impresora, numero_z, ultima_factura, numeros_facturas, ultima_nc, numeros_ncs, fecha, monto_bruto, monto_exento, base_imponible, iva, fecha_emision, id_usuario_creacion, secuencia, numero_z_usuario, monto_bruto_usuario, monto_exento_usuario, base_imponible_usuario, iva_usuario, money)
           SELECT serial_impresora, numero_z, ultima_factura, numeros_facturas, ultima_nc, numeros_ncs, fecha, monto_bruto, monto_exento, base_imponible, iva, fecha_emision, '".$login->getNombreApellidoUSuario()."', secuencia, numero_z_usuario, monto_bruto_usuario, monto_exento_usuario, base_imponible_usuario, iva_usuario, money FROM libro_ventas WHERE money= '".$_POST['money']."'" 
           );

        }else{


            if($_POST['ultima_nc']==""){
        $_POST['ultima_nc']='NULL';
        }
        if($_POST['nro_ncs']==""){
        $_POST['nro_ncs']='NULL';
        }
        
        $campos = $conn->ExecuteTrans("INSERT INTO libro_ventas(
                
                serial_impresora,
                numero_z,
                numero_z_usuario,
                ultima_factura,
                numeros_facturas,
                ultima_nc,
                numeros_ncs,
                fecha,
                monto_bruto,
                monto_exento,
                base_imponible,
                iva,
                monto_bruto_usuario,
                monto_exento_usuario,
                base_imponible_usuario,
                iva_usuario,
                fecha_emision,
                money,
                id_usuario_creacion,
                tipo_venta

                ) 
            VALUES
                (
                '".$_POST['serial']."',
                '".($_POST['z'])."',
                '".($_POST['z_usuario'])."',
                '".$_POST['ultima_factura']."',
                '".$_POST['nro_facturas']."',
                ".$_POST['ultima_nc'].",
                ".$_POST['nro_ncs'].",
                '".$_POST['fecha']."',
                '".$_POST['monto_bruto']."',
                '".$_POST['monto_exento']."',
                '".$_POST['base_imponible']."',
                '".$_POST['iva']."',
                '".$_POST['monto_bruto_usuario']."',
                '".$_POST['monto_exento_usuario']."',
                '".$_POST['base_imponible_usuario']."',
                '".$_POST['iva_usuario']."',
                now(),
                '".$_POST['money']."',
                '".$_SESSION['cod_usuario']."',
                '0'

                )
                ");



        $obtener_secuencia=$conn->ObtenerFilasBySqlSelect("SELECT max(b.secuencia) as max_pyme_secuencia FROM closedcash_pyme as b WHERE b.nombre_caja='".$_POST['caja']."' and b.fecha_fin is not null");
        $campos2=$conn->ExecuteTrans("update cierre_caja_control_pyme set secuencia='".$obtener_secuencia[0]['max_pyme_secuencia']."', estatus_cierre=0 where nombre_cajas='".$_POST['caja']."' ");


//insertar en libro_ventas_xenviar
        $insert_enviar=$conn->ExecuteTrans("
           INSERT INTO libroventas_xenviar(serial_impresora, numero_z, ultima_factura, numeros_facturas, ultima_nc, numeros_ncs, fecha, monto_bruto, monto_exento, base_imponible, iva, fecha_emision, id_usuario_creacion, secuencia, numero_z_usuario, monto_bruto_usuario, monto_exento_usuario, base_imponible_usuario, iva_usuario, tipo_venta, money)
           SELECT serial_impresora, numero_z, ultima_factura, numeros_facturas, ultima_nc, numeros_ncs, fecha, monto_bruto, monto_exento, base_imponible, iva, fecha_emision, '".$login->getNombreApellidoUSuario()."', secuencia, numero_z_usuario, monto_bruto_usuario, monto_exento_usuario, base_imponible_usuario, iva_usuario, tipo_venta, money FROM libro_ventas WHERE fecha= '".$_POST['fecha']."' and serial_impresora='".$_POST['serial']."'" 
           );


            //operaciones
           // $verificar_limite=$conn->ObtenerFilasBySqlSelect("select id, libro_venta-1 as libros from operaciones order by fecha desc limit 1");
           // if($verificar_limite[0]['libros']<0){
            //    echo "5"; exit();
            //}
            //$operaciones=$conn->ExecuteTrans(
              //  "UPDATE operaciones SET libro_venta = libro_venta-1 where id='".$verificar_limite[0]['id']."'"
               // );

            
        }


       
       $conn->CommitTrans(1);
       
 
       $verificar=1;
        if($verificar==1){
            echo 1; 
        }else{
            echo -1;
        };
        


        break;


        
case "guardar_arqueo":

            if(isset($_POST['tipo_venta_pyme']) && $_POST['tipo_venta_pyme']!=1 )
            {
                $tipo_venta_pyme=$_POST['tipo_venta_pyme'];
            }
            else
            {
                $tipo_venta_pyme=1;
            }


            $retiros_pendiente=$conn->ObtenerFilasBySqlSelect("Select id, sum(monedas) as monedas
                    from retiro_efectivo where id_cajero='".$_POST['cajero']."' and estatus_retiro=0 and tipo_venta='".$tipo_venta_pyme."'");
            $totalbilletesretiro=$conn->ObtenerFilasBySqlSelect("select sum(a.valor*b.cantidad) as total from billetes a inner join billetes_retiro_efectivo b on a.id=b.id_billete where b.id_retiro_efectivo='".$retiros_pendiente[0]["id"]."'");

            $_POST['total_monedas']=$_POST['total_monedas']+($retiros_pendiente[0]["monedas"]);
            $_POST['efectivo']=0;
            //calculamos el total de todo lo que hizo el cajero
            for($i=0; $i<count($_POST['valoresarray']); $i++)
            {
                $total_cajero+=($_POST['valoresarray'][$i]*$_POST['billetesarray'][$i]);
            }
            $total_cajero+=($_POST['total_monedas']+$totalbilletesretiro[0]['total']);
            $_POST['efectivo']=$total_cajero;
            $observacion=$_POST['resultado'];
                   
            $conn->BeginTrans();
            //".$_POST['total_monedas'].",            
            $obtener_fechas=$conn->ObtenerFilasBySqlSelect("select fecha_inicio, fecha_fin, secuencia from control_cierre_cajero where id_cajero='".$_POST['cajero']."' order by secuencia desc limit 1");
            $obtener_apertura=$conn->ObtenerFilasBySqlSelect("select max(id_apertura) as id_apertura from apertura_tienda");
            
            $sql="INSERT INTO arqueo_cajero(id_cajero, monedas, efectivo, tarjeta, credito, deposito, cheque, tickets, total_sistema, total_efectivo_sistema, total_tj_sistema, total_credito_sistema, total_deposito_sistema, total_cheque_sistema, total_tickets_sistema,total_devolucion, iva1, iva2, iva3, fecha_arqueo, fecha_venta_ini, fecha_venta_fin, observaciones, cod_usuario, tipo_venta, id_apertura) 
                VALUES
                (   '".$_POST['cajero']."',
                    '0.00',
                    '".$_POST['efectivo']."',
                    '".$_POST['total_tarjeta']."',
                    '".$_POST['total_credito']."',
                    '".$_POST['total_deposito']."',
                    '".$_POST['total_cheque']."',
                    '".$_POST['total_ticket']."',
                    '".$_POST['sistema']."',
                    '".$_POST['total_efectivo_sistema']."',
                    '".$_POST['total_tj_sistema']."',
                    '".$_POST['total_credito_sistema']."',
                    '".$_POST['total_deposito_sistema']."',
                    '".$_POST['total_cheque_sistema']."',
                    '".$_POST['total_tickets_sistema']."',
                    '".$_POST['total_devolucion']."',
                    '".$_POST['total_iva1']."',
                    '".$_POST['total_iva2']."',
                    '".$_POST['total_iva3']."',
                    now(),
                    '".$obtener_fechas[0]['fecha_fin']."',
                    now(),
                    '".$observacion."',
                    '".$_SESSION["cod_usuario"]."',
                    '".$tipo_venta_pyme."',
                    ".$obtener_apertura[0]['id_apertura']."
                    )
                ";
            $insert=$conn->Execute2($sql);

            //Obtener id del arqueo cajero
            $idarqueo=$conn->ObtenerFilasBySqlSelect("SELECT LAST_INSERT_ID()");

            //primero guardamos el los depositos, pues se deben guardar por separado.
            if(is_array(json_decode($_POST['array_depositos'])))
            {
                foreach (json_decode($_POST['array_depositos']) as $key => $value) 
                {
                    foreach ($value as $key => $value) 
                    {
                        $sql_insert=$conn->Execute2("insert into arqueo_depositos (id_arqueo, monto_deposito) values('".$idarqueo[0]['LAST_INSERT_ID()']."', '".$value."')");
                    }
                }
            }
            
            //primero guardamos el los depositos, pues se deben guardar por separado.
            if(is_array(json_decode($_POST['array_cheques'])))
            {
                foreach (json_decode($_POST['array_cheques']) as $key => $value) 
                {
                    foreach ($value as $key => $value) 
                    {
                        $sql_insert=$conn->Execute2("insert into arqueo_cheques (id_arqueo, monto_cheques) values('".$idarqueo[0]['LAST_INSERT_ID()']."', '".$value."')");
                    }
                }
            }
            $max_perdida=$conn->ObtenerFilasBySqlSelect("select fina_limite_max from parametros_generales");
            //proceso de ingresar en detalle contable
            $sql_contable=" insert into comprobante_detalle 
                            (ingreso, iva1, iva2, iva3, perdida, cxc, otros_ingresos, caja, cajero, fecha, id_usuario, tipo_venta)
                            
                           (select (total_sistema-(iva1+iva2+iva3)) as ingreso,
                            iva1, iva2, iva3, 
                            case 
                                when observaciones < 0 and (observaciones*-1) <= ".$max_perdida[0]['fina_limite_max']." then (observaciones*-1)
                                when observaciones < 0 and (observaciones*-1) > ".$max_perdida[0]['fina_limite_max']." then '0'
                                when observaciones > 0 then '0'
                                    
                            END as perdida,

                            case 
                                when observaciones < 0 and (observaciones*-1) <= ".$max_perdida[0]['fina_limite_max']." then '0'
                                when observaciones < 0 and (observaciones*-1) > ".$max_perdida[0]['fina_limite_max']." then (observaciones*-1)
                                when observaciones > 0 then '0'
                            END as cxc,
                            case
                                when observaciones >= 0 then observaciones
                                else
                                '0'
                            END as otros_ingresos,  
                            (monedas+efectivo+tarjeta+deposito+cheque+tickets) as caja,
                            id_cajero, now() as fecha, ".$_SESSION["cod_usuario"]." as id_usuario, '".$tipo_venta_pyme."'
                            from arqueo_cajero
                            where id='".$idarqueo[0]['LAST_INSERT_ID()']."')
                            ";

            $insert_contable=$conn->Execute2($sql_contable);
             //Obtener id del comprobante
            $idcomprobante=$conn->ObtenerFilasBySqlSelect("SELECT LAST_INSERT_ID()");
            //insertar en ingreso detalle
            //primero guardamos el los depositos, pues se deben guardar por separado.
            if(is_array(json_decode($_POST['tipoarray'])))
            {
                foreach (json_decode($_POST['tipoarray']) as $key => $value) 
                {
                        $sql="insert into ingresos_detalles (id_comprobante_detalle, tipo_ingreso, monto) values('".$idcomprobante[0]['LAST_INSERT_ID()']."', '".$value->coddepartamento."','".$value->totalingreso."')";
                       // echo $sql; exit();
                        $sql_insert=$conn->Execute2($sql);
                }
            }
            //recorremos los arreglos de los billetes para guardar en la tabla billetes-arqueo
            for($i=0; $i<count($_POST['valoresarray']); $i++)
            {
                $idbillete=$conn->ObtenerFilasBySqlSelect("Select id from billetes where valor=".$_POST['billetesarray'][$i]);
                //hay que sumar la cantidad de retiro al arqueo 
                $retiroscantidad=$conn->ObtenerFilasBySqlSelect("Select cantidad
                    from billetes_retiro_efectivo where id_billete='".$idbillete[0]['id']."' and id_retiro_efectivo='".$retiros_pendiente[0]["id"]."'");
               
                $retiroscantidad[0]['cantidad']=$retiroscantidad[0]['cantidad']=="" ? 0 : $retiroscantidad[0]['cantidad'];
                //fin del retiro billete pendiente

                $insertar=$conn->Execute2('insert into billetes_arqueo (id_arqueo, id_billete, cantidad) 
                    values
                    ('.$idarqueo[0]['LAST_INSERT_ID()'].', '.$idbillete[0]['id'].', '.($_POST['valoresarray'][$i]+$retiroscantidad[0]['cantidad']).')'); 
            }
            //cambiar_estatus
            $id_retiros=$conn->ObtenerFilasBySqlSelect("select id from retiro_efectivo where id_cajero='".$_POST['cajero']."' and estatus_retiro=0 and tipo_venta='".$tipo_venta_pyme."'");
            foreach ($id_retiros as $key ) 
            {
                $retiro_efectivo_cambiar=$conn->Execute2(
                "update retiro_efectivo set estatus_retiro=1 where id='".$key['id']."'"
                );
            }
            
            $obtenerid=$conn->ObtenerFilasBySqlSelect("SELECT LAST_INSERT_ID()");
            
            if($tipo_venta_pyme==1)
            {
                $sql="update  ".POS.".people set visible=0 where id='".$_POST['cajero']."'";
                $cambiar_estatus=$conn->Execute2($sql);
                $sql="select * from ".POS.".people_caja where id_people='".$_POST['cajero']."'";
                $existe=$conn->ObtenerFilasBySqlSelect($sql);
                if(empty($existe[0]['id']))
                {
                    $inserta_visible=$conn->Execute2("insert into ".POS.".people_caja (id_people) values('".$_POST['cajero']."')");
                }
            }else{
                $sql="update  usuarios set visible_vendedor=0 where cod_usuario='".$_POST['cajero']."'";
                $cambiar_estatus=$conn->Execute2($sql);
            }
        
            $inserta_tabla_control=$conn->Execute2("insert into control_cierre_cajero (id_cajero,secuencia,fecha_inicio,fecha_fin, tipo)
             values('".$_POST['cajero']."', ".($obtener_fechas[0]['secuencia']+1).", '".$obtener_fechas[0]['fecha_fin']."', now(), '".$tipo_venta_pyme."')");
            

            //operaciones
            $verificar_limite=$conn->ObtenerFilasBySqlSelect("(select max(fecha) fecha from operaciones)");
                
            $operaciones=$conn->ExecuteTrans(
                    "UPDATE operaciones SET cierre_cajero = cierre_cajero+1 where fecha='".$verificar_limite[0]['fecha']."'"
                );

            $conn->CommitTrans(1);
            $verificar=1;
            if($verificar==1)
            {
                
                echo "1"."_".$idarqueo[0]['LAST_INSERT_ID()'];
            }
            else
            {
                echo 2;
            }
        
        break;

          case "formulario_arqueo_cajero":

                //buscamos los billetes que esten habilitados
                $billetes=$conn->ObtenerFilasBySqlSelect("select * from billetes where estatus=1 order by valor desc");
                echo '
                    <tr class="edocuenta_detalle">
                        <td colspan="8">
                            <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                                <table >
                ';
                $billetes_validos="";//variable para guardar los billetes y validarlos por el javascript
                foreach ($billetes as $key => $value) 
                {
                    echo '
                                    <thead>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px; align:left;">
                                            Cantidad Billetes De '.$value['valor'].' '.$value['denominacion'].':
                                        </th>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">
                                            <input type="text" name="'.$value['valor'] .'" id="'.$value['valor'] .'" value="0" class="form-text serialSec " style="width:100px; align:center">
                                        </th>
                                    </thead>';
                //se agregan los billetes validos, para validar en el javascript
                $billetesvalidos.=$value['valor'].",";
                } 
                //buscando depositos y cheques
                $sql="Select fecha_fin from control_cierre_cajero where id_cajero='".$_POST['cajero']."' order by secuencia desc limit 1";

                $fecha_inicio=$conn->ObtenerFilasBySqlSelect($sql);
                $resultado[0]['total_deposito_sistema']=0;
                $resultado[0]['total_cheque_sistema']=0;
                if(!empty($fecha_inicio[0]['fecha_fin']))
                {
                    //sum(totalizar_monto_credito2) as total_credito_sistema,
                    $sql="SELECT 
                        
                        sum(totalizar_monto_deposito) as total_deposito_sistema,
                        sum(totalizar_monto_cheque) as total_cheque_sistema
                        FROM `factura_detalle_formapago` as a, factura as b WHERE a.id_factura=b.id_factura 
                        and b.cod_vendedor='".$_POST['cajero']."'
                        and b.fecha_creacion BETWEEN '".$fecha_inicio[0]['fecha_fin']."' AND now()";
                    $resultado=$conn->ObtenerFilasBySqlSelect($sql);
                }
                if($resultado[0]['total_deposito_sistema']<1)
                {
                    $resultado[0]['total_deposito_sistema']=0;
                }
                if($resultado[0]['total_cheque_sistema']<1)
                {
                    $resultado[0]['total_cheque_sistema']=0;
                }

                //eliminado ultima coma
                $billetesvalidos=substr($billetesvalidos, 0, -1);
                                    echo '
                                    <thead>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            Total Monedas:
                                        </th>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            <input type="text" name="total_monedas" id="total_monedas" value="0" class="form-text serialSec " style="width:100px; align:center">
                                        </th>
                                    </thead>
                                    <thead>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            Total Tarjeta:
                                        </th>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            <input type="text" name="total_tarjeta" id="total_tarjeta" value="0" class="form-text serialSec " style="width:100px; align:center">
                                        </th>
                                    </thead>
                                    <!--<thead>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            Total Crédito:
                                        </th>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            <input type="text" name="total_credito" id="total_credito" value="0" class="form-text serialSec " style="width:100px; align:center">
                                        </th>
                                    </thead>-->
                                    <thead>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                                Total Tickets:
                                            </th>
                                            <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                                <input type="text" name="total_ticket" id="total_ticket" value="0" class="form-text serialSec " style="width:100px; align:center"><input type="hidden" name="arqueo" id="arqueo" value="1">
                                                <input type="hidden" name="cajero" id="cajero" value='.$_POST['cajero'].' />
                                                <input type="hidden" name="billetesvalidos" id="billetesvalidos" value='.$billetesvalidos.' />
                                            </th>
                                    </thead>
                                     <thead>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            Total Deposito:
                                        </th>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            <input type="text" name="total_deposito" id="total_deposito" value="'.$resultado[0]['total_deposito_sistema'].'"  class="form-text serialSec " style="width:100px; align:center" readonly>
                                        </th>
                                    </thead>
                                     <thead>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            Total Cheque:
                                        </th>
                                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">
                                            <input type="text" name="total_cheque" id="total_cheque" value="'.$resultado[0]['total_cheque_sistema'].'"  class="form-text serialSec " style="width:100px; align:center" readonly>
                                        </th>
                                    </thead>
                                </table>
                            </div>
                        </td>
                    </tr>
                ';
           
            break;

        case "insertar_registro_inicial":
        if(isset($_GET['tipo_venta_pyme'])){
            $tipo_venta=0;
        }else{
            $tipo_venta="";
        }
        $sql=$conn->Execute2("insert into control_cierre_cajero (id_cajero, secuencia, fecha_inicio, fecha_fin, tipo) values ('".$_POST['cajero']."', 1, '".$_POST['fecha_inicio']."','".$_POST['fecha_fin']."', '".$tipo_venta."')");
        if($sql==1){
            echo 1;
        }else{
            -1;
        }
        break;

        case "registro_inicial":

                echo '<tr class="edocuenta_detalle">
                  <td colspan="8">
                    <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                        <table >
                            <thead>
                                
                                <th align="center" style="border-bottom: 1px solid #949494;width:50px;">Fecha Inicial De Venta</th>
                                
                            </thead>

                            <tbody>
                            
                           
                            <th align="center" ><input type="text" name=fecha_fin id="fecha_fin" readonly="readonly" class="form-text serialSec " required  style="width:80px; align:center"></th>

                            
                                <script type="text/javascript">//<![CDATA[
                                           
                                            $("#fecha_fin").datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                showOtherMonths:true,
                                                selectOtherMonths: true,
                                                //numberOfMonths: 1,
                                                //yearRange: "-100:+100",
                                                dateFormat: "yy-mm-dd",
                                                //timeFormat: "HH:mm:ss",
                                                showOn: "both",//button,
                                                onClose: function( selectedDate ) {
                                                    //$( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
                                                    $( "#fecha_fin" ).datetimepicker("option", "minDate", selectedDate);
                                                }
                                            });
                                            //]]>
                                            </script>
                            
                            <input type="hidden" name="cajero" id="cajero"  value="'.$_POST['cajero'].'"/>
                            <input type="hidden" name="registro_inicial" id="registro_inicial"  value=1 />

                            </th>
                            </tbody>';


                break;

        case "verificar_cajero":

        $obtener_fecha_cajero=$conn->ObtenerFilasBySqlSelect("SELECT fecha_fin, id_cajero from control_cierre_cajero where id_cajero='".$_POST['cajero']."' order by secuencia desc limit 1");
        if($obtener_fecha_cajero[0]['fecha_fin']==""){
            echo "-1"; //no existe cajero en la tabla tenemos que abrir el formulari de registro por primera vez
        }else{
           
        $obtener_visible=$conn->ObtenerFilasBySqlSelect("SELECT NAME from ".POS.".people where id='".$obtener_fecha_cajero[0]['id_cajero']."' and VISIBLE=1");
        print_r($obtener_visible);
        if($obtener_visible[0]['NAME']==""){
            echo "-2";
        }else{
          echo  1;
        }

        }


        break;

        case "calcular":
         $bd_pos=POS;
        $bd_pyme=DB_SELECTRA_FAC;
       // $existencia_money=$conn->ObtenerFilasBySqlSelect("select money from libro_ventas as a, caja_impresora as b where b.caja_host='".$_POST['caja']."' and a.serial_impresora=b.serial_impresora order by numero_z desc limit 1");
        //echo "select money from libro_ventas as a, caja_impresora as b where a.serial_impresora=b.serial_impresora and b.HOST='".$_POST['caja']."' order by numero_z desc limit 1"; exit();
         //$existencia_money=$conn->ObtenerFilasBySqlSelect(
        $selec=""; $tabla="";$filtro=""; $money="";
        if(isset($existencia_money[0]['money'])){
            $money="and a.MONEY='".$existencia_money[0]['money']."'";
            $selec=", c.money as money";
            $tabla=", libro_ventas as c";
            $filtro=" and b.MONEY=c.money";
        }

        //$fechas_secuencia=$conn->ObtenerFilasBySqlSelect("SELECT date(datestart) as inicio, date(dateend) as fin from cierre_caja_control as a, ".POS.".closedcash as b where a.cajas=b.host and b.host='".$_POST['caja']."' and b.HOSTSEQUENCE=(a.secuencia+1)");
     //   $fechas_secuencia=$conn->ObtenerFilasBySqlSelect("SELECT date(datestart) as inicio, date(dateend) as fin ".$selec." from cierre_caja_control as a, ".POS.".closedcash as b ".$tabla." where a.cajas=b.host and b.host='".$_POST['caja']."' and b.HOSTSEQUENCE=(a.secuencia+1) ".$filtro."");
//echo "SELECT date(datestart) as inicio, date(dateend) as fin ".$selec." from cierre_caja_control as a, ".POS.".closedcash as b ".$tabla." where a.cajas=b.host and b.host='".$_POST['caja']."' and b.HOSTSEQUENCE=(a.secuencia+1) ".$filtro.""; exit();
       //if(date($fechas_secuencia[0]['fin'])==$_POST['fecha']){

        $fechas_secuencia=$conn->ObtenerFilasBySqlSelect("select date(a.DATEEND) as DATEEND, money from $bd_pos.closedcash as a 
where a.money not in  (select  money from $bd_pyme.libro_ventas) and date(dateend)>=(select min(fecha) from $bd_pyme.fechas_minimas) and a.HOST='".$_POST['caja']."' group by host order by host, dateend asc");
        //echo "select date(a.DATEEND) as DATEEND, money from $bd_pos.closedcash as a where a.money not in  (select  money from $bd_pyme.libro_ventas) and date(dateend)>=(select min(fecha) from $bd_pyme.fechas_minimas) and a.HOST='".$_POST['caja']."' group by host order by host, dateend asc"; exit();
       // $numero_facturas=$conn->ObtenerFilasBySqlSelect("select count(b.id) as total_factura from $bd_pos.receipts as a, $bd_pos.taxlines as b where a.money='".$fecha_venta[0]['money']."' and a.id=b.receipt ");



        $calcular=$conn->ObtenerFilasBySqlSelect("SELECT sum(c.base) as base, sum(c.amount) as iva, sum(c.base+c.amount) as bruto, sum(case when c.amount=0 then c.base else 0 end) as exento FROM ".POS.".closedcash as a, ".POS.".receipts as b, ".POS.".taxlines as c where a.money=b.money and b.id=c.receipt and date(a.dateend)>='".$_POST[fecha]."' and a.MONEY='".$fechas_secuencia[0]['money']."' and a.HOST='".$_POST['caja']."' ORDER BY a.HOSTSEQUENCE ASC"); 
        //echo "SELECT sum(c.base) as base, sum(c.amount) as iva, sum(c.base+c.amount) as bruto, sum(case when c.amount=0 then c.base else 0 end) as exento FROM ".POS.".closedcash as a, ".POS.".receipts as b, ".POS.".taxlines as c where a.money=b.money and b.id=c.receipt and date(a.dateend)='".$_POST[fecha]."' and a.MONEY='".$fechas_secuencia[0]['money']."' and a.HOST='".$_POST['caja']."' ORDER BY a.HOSTSEQUENCE ASC"; exit();
        
        if($calcular[0]['bruto']>0){
            echo $calcular[0]['base']." ".$calcular[0]['bruto']." ".$calcular[0]['exento']." ".$calcular[0]['iva'];

        }else{
            echo -1;
        }

       
        break;

        case "comprobar_cierre_caja":
 
        $comprobar_cierre_caja=$conn->ObtenerFilasBySqlSelect("SELECT max(a.secuencia) as max_pyme, max(b.HOSTSEQUENCE) as max_pos FROM cierre_caja_control as a, ".POS.".closedcash as b WHERE a.cajas=b.host and a.cajas='".$_POST['caja']."' and b.dateend is not null");
//echo "SELECT max(a.secuencia) as max_pyme, max(b.HOSTSEQUENCE) as max_pos FROM cierre_caja_control as a, ".POS.".closedcash as b WHERE a.cajas=b.host and a.cajas='".$_POST['caja']."' and b.dateend is not null"; exit();       
       
        if(($comprobar_cierre_caja[0]['max_pyme']+1)==$comprobar_cierre_caja[0]['max_pos']){
            echo 1; //caja de cierre correcto y no posee mas cierre al dia
        }else{

             if($comprobar_cierre_caja[0]['max_pyme']==$comprobar_cierre_caja[0]['max_pos']){
                    echo -1; //error no se ha cerrado la caja
             }else{
                if(($comprobar_cierre_caja[0]['max_pyme']+1)<$comprobar_cierre_caja[0]['max_pos']){
                    echo 2; //cierre de caja correcto, pero han habido varios cierres.
                }
             }

        }
       

        break;

        case "verificar_caja":

        $campos = $conn->ObtenerFilasBySqlSelect("SELECT count(serial_impresora) as existe FROM caja_impresora where caja_host='".$_POST['caja']."'");
        //echo "SELECT count(serial_impresora) as existe FROM caja_impresora  where caja_host='".$_POST['caja']."'";
        
        if($campos[0]['existe']==0){
            echo -1; 
        };
        if($campos[0]['existe']>0){
            echo 1;
        };


        break;

        case "formulario_libro_ventas":
        $bd_pos=POS;
        $bd_pyme=DB_SELECTRA_FAC;
        $serial =$conn->ObtenerFilasBySqlSelect("Select serial_impresora from caja_impresora where caja_host='".$_POST['caja']."'");
        $ultimo_z=$conn->ObtenerFilasBySqlSelect("select numero_z_usuario+1 as z, money from libro_ventas where serial_impresora='".$serial[0]['serial_impresora']."' order by numero_z_usuario desc limit 1");
        $fecha_venta=$conn->ObtenerFilasBySqlSelect("select  money from $bd_pos.closedcash as a 
where a.money not in  (select  money from $bd_pyme.libro_ventas) and date(dateend)>=(select min(fecha) from $bd_pyme.fechas_minimas) and a.HOST='".$_POST['caja']."' group by host order by host, dateend   asc");
        $numero_facturas=$conn->ObtenerFilasBySqlSelect("select count(b.id) as total_factura from $bd_pos.receipts as a, $bd_pos.taxlines as b where a.money='".$fecha_venta[0]['money']."' and a.id=b.receipt ");
        $obtener_fecha_ultima_venta=$conn->ObtenerFilasBySqlSelect("select date(datenew) as fecha from $bd_pos.receipts where money='".$fecha_venta[0]['money']."'");
        //echo "select date(datenew) as fecha from receipts where money='".$fecha_venta[0]['money']."'";

         if(!isset($ultimo_z[0]['z'])){
            $ultimo_z[0]['z']="";
            $ultimo_z[0]['money']="";
            }else{
                $ultimo_z[0]['z']=$ultimo_z[0]['z'];}
         echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#A9A9F5; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px; align:left;">Serial Impresora</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">Nro. Z</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">Ultima Factura</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;">Nro. Facturas</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:40px;" value=0>Ultima Nota Credito</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;" value=0>Nro. Notas De Credito</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Fecha</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Monto Bruto</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Monto Exento</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">Base Imponible</th>
                        <th align="center" style="border-bottom: 1px solid #949494;width:100px;">IVA</th>
                    </thead>

                    <tbody>
                    <td align="center" ><input type="text" name="serial" id="serial"  class="form-text serialSec " required style="width:100px;" value='.$serial[0]['serial_impresora'].' readonly></td>
                    <td align="center" ><input  type="hidden" name="z" id="z"  class="form-text serialSec " style="width:40px;" value='.$ultimo_z[0]['z'].'><input  type="text" name="z_usuario" id="z_usuario"  class="form-text serialSec " style="width:40px;" value='.$ultimo_z[0]['z'].'></td>
                    <th align="center" ><input type="text" name="ultima_factura" id="ultima_factura"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name="nro_facturas" id="nro_facturas" value="'.$numero_facturas[0]['total_factura'].'"  class="form-text serialSec " style="width:40px; align:center"/></th>
                    <th align="center" ><input type="text" name="ultima_nc" id="ultima_nc"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name="nro_ncs" id="nro_ncs"  class="form-text serialSec " style="width:40px; align:center"></th>
                    <th align="center" ><input type="text" name=fecha id="fecha" readonly="readonly" value="'.$obtener_fecha_ultima_venta[0]['fecha'].'" class="form-text serialSec " required  style="width:80px; align:center"></th>

                   
                
                    <th align="center" ><input type="hidden" name="monto_bruto" id="monto_bruto" class="form-text serialSec " style="width:100px; align:center"><input type="text" name="monto_bruto_usuario" id="monto_bruto_usuario" onfocus=calcular();  class="form-text serialSec " style="width:100px; align:center"></th>
                    <th align="center" ><input type="hidden" name="monto_exento" id="monto_exento"  class="form-text serialSec " style="width:100px; align:center"><input type="text" name="monto_exento_usuario" id="monto_exento_usuario"  class="form-text serialSec " style="width:100px; align:center"></th>
                    <th align="center" ><input type="hidden" name="base_imponible" id="base_imponible"  class="form-text serialSec " style="width:100px; align:center"><input type="text" name="base_imponible_usuario" id="base_imponible_usuario"  class="form-text serialSec " style="width:100px; align:center"></th>
                    <th align="center" ><input type="hidden" name="iva" id="iva"  class="form-text serialSec " style="width:100px; align:center"><input type="text" name="iva_usuario" id="iva_usuario"  class="form-text serialSec " style="width:100px; align:center">
                    <input type="hidden" name="caja" id="caja"  value="'.$_POST['caja'].'"/>
                    <input type="hidden" name="money" id="money"  value="'.$fecha_venta[0]['money'].'"/>
                    <input type="hidden" name="cierres" id="cierres"  value="'.$_POST['cierres'].'"/>
                    </th>
                    </tbody>';



        break;





        case "eliminar_asientoCXP":
            $instruccion = "SELECT * FROM cxp_edocuenta_detalle WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);

            $instruccion = "delete from tabla_impuestos WHERE numero_control_factura = '" . $campos[0]["numero"] . "' and tipo_documento='c' and totalizar_monto_retencion='" . $campos[0]["monto"] . "'";
            $conn->Execute2($instruccion);

//,fecha_anulacion='".$_GET["fecha"]."',observacion_anulado='".$_GET["motivoAnulacion"]."'
            $instruccion = "update cxp_edocuenta_detalle set marca='',estado = '0',fecha_anulacion='" . $_GET["fecha"] . "',observacion_anulado='" . $_GET["motivoAnulacion"] . "' WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $conn->Execute2($instruccion);

            $instruccion = "update cxp_edocuenta set marca = '' WHERE cod_edocuenta = " . $campos[0]["cod_edocuenta"];
            $conn->Execute2($instruccion);

            $instruccion = "delete from cxp_factura_pago WHERE cxp_edocuenta_detalle_fk = '" . $_GET["cod"] . "'";
            $conn->Execute2($instruccion);

            $instruccion = "delete from cxp_edocuenta_formapago WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $conn->Execute2($instruccion);
//echo $instruccion;
            break;
        case "eliminar_asientoCXC":

            $instruccion = "SELECT * FROM cxc_edocuenta_detalle WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);

            $instruccion = "delete from cxc_edocuenta_detalle WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            echo $conn->Execute2($instruccion);

            $instruccion = "delete from tabla_impuestos WHERE numero_control_factura = '" . $campos[0]["numero"] . "' and tipo_documento='f' and totalizar_monto_retencion='" . $campos[0]["monto"] . "'";
            echo $conn->Execute2($instruccion);

            $instruccion = "update cxc_edocuenta set marca = '' WHERE cod_edocuenta = " . $campos[0]["cod_edocuenta"];
            echo $instruccion;
            $conn->Execute2($instruccion);

            break;
        case "impuestos":
            $instruccion = "SELECT * FROM lista_impuestos as li
            left join formulacion_impuestos as fi on li.cod_formula=fi.cod_formula
            WHERE cod_impuesto= '" . $_GET["cod_impuesto"] . "'";
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);
            $PORCENTAJE = $campos[0]["alicuota"];
            $PAGOMAYORA = $campos[0]["pago_mayor_a"];
            $MONTOSUSTRACCION = $campos[0]["monto_sustraccion"];
            $MONTOBASE = $_GET["monto_base"];
            $formula = $campos[0]["formula"];
            $resultado = eval($formula);

            $calculo = $_GET["monto_islr"] * $porcentaje;
            echo "[{'total_retencion':'" . $MONTO . "','porcentaje':'" . $campos[0]["alicuota"] . "','formula':'" . $campos[0]["formula"] . "','resultado':'" . $MONTO . "','codigo_impuesto':'" . $campos[0]["cod_impuesto"] . "','cod_tipo_impuesto':'" . $campos[0]["cod_tipo_impuesto"] . "'}]";
            break;
        case "impuesto_iva":
            $instruccion = "SELECT * FROM impuesto_iva WHERE cod_impuesto_iva = " . $_GET["cod_impuesto_iva"];
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);
            $calculo = $_GET["montoiva"] * ($campos[0]["porcentaje"] / 100);
            echo "[{'total_iva':'" . ($calculo) . "','porcentaje':'" . $campos[0]["porcentaje"] . "'}]";
            break;
        case "ValidarCodigoitem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE cod_item = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
            
        case "filtroProveedores":  
            /**
             * Procedimiento de busqueda de filtroProveedores
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      levieraf@gmail.com
             *
             */

            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {

                // Filtro para los proveedores
                $id_proveedor = (isset($_POST["id_proveedor"])) ? $_POST["id_proveedor"] : "";
                $rif = (isset($_POST["ciruc"])) ? $_POST["ciruc"] : "";
                $cod_proveedor = (!isset($_POST["cod_proveedor"])) ? "" : $_POST["cod_proveedor"];
                $nombre = (!isset($_POST["proveedor"])) ? "" : $_POST["proveedor"];

                if ($cod_proveedor != "") {
                    $andWHERE .= " and upper(cod_proveedor) like upper('%" . $cod_proveedor . "%')";
                }

                if ($nombre != "") {
                    if ($cod_proveedor != "") {
                        $andWHERE .= " and ";
                    } else {
                        $andWHERE = " and ";
                    }
                    $andWHERE .= " upper(descripcion) like upper('%" . $nombre . "%')";
                }

                $sql = "SELECT * FROM proveedores WHERE estatus = 'A' " . $andWHERE;

                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);

                $sql = "SELECT * FROM proveedores WHERE estatus = 'A' " . $andWHERE . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);

            } else {
                $sql = "SELECT * FROM proveedores WHERE estatus = 'A'";
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT * FROM proveedores WHERE estatus = 'A' limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            }

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes1),
                "data" => $campos_comunes
            ));
            break;


       case "filtroClientes":  
            /**
             * Procedimiento de busqueda de Clientes
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      levieraf@gmail.com
             *
             */

            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {

                // Filtro para los cliente
                $id_cliente = (isset($_POST["id_cliente"])) ? $_POST["id_cliente"] : "";
                $rif = (isset($_POST["ciruc"])) ? $_POST["ciruc"] : "";
                $cod_cliente = (!isset($_POST["cod_cliente"])) ? "" : $_POST["cod_cliente"];
                $nombre = (!isset($_POST["cliente"])) ? "" : $_POST["cliente"];

                $andWHERE = " and ";
                if ($rif != "") {
                    $andWHERE .= " upper(rif) like upper('%" . $rif . "%')";
                }

                if ($cod_cliente != "") {
                    $andWHERE .= " upper(cod_cliente) like upper('%" . $cod_cliente . "%')";
                }

                if ($nombre != "") {
                    if ($cod_cliente != "") {
                        $andWHERE .= " and ";
                    } else {
                        $andWHERE = " and ";
                    }
                    $andWHERE .= " upper(nombre) like upper('%" . $nombre . "%')";
                }

                if ($rif != "") {
                    if ($nombre != "" || $cod_cliente != "") {
                        $andWHERE .= " and ";
                    } else {
                        $andWHERE = " and ";
                    }
                    $andWHERE .= " upper(rif) like upper('%" . $rif . "%')";
                }

                if ($rif == "" && $cod_cliente == "" && $nombre == "") {
                    $andWHERE = "";
                }

                $sql = "SELECT * FROM clientes WHERE estado = 'A' " . $andWHERE;
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);

                $sql = "SELECT * FROM clientes WHERE estado = 'A' " . $andWHERE . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);

            } else {
                $sql = "SELECT * FROM clientes WHERE estado = 'A'";
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT * FROM clientes WHERE estado = 'A' limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            }

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes1),
                "data" => $campos_comunes
            ));
            break;
        case "DetalleCliente":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM clientes WHERE id_cliente = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : json_encode($campos);
            break;
        case "Detalleproveedor":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM proveedor WHERE id_proveedor = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : json_encode($campos);
            break;
        case "ValidarCodigoCliente":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM clientes WHERE cod_cliente = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "ItemTrasladoTemp":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item_traslado_temp WHERE id_item = '" . $_GET["item"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "ValidarCodigoVendedor":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vendedor WHERE cod_vendedor = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "ValidarNombreUsuario":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM usuarios WHERE usuario = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "Selectitem":
#$campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` AS i INNER JOIN `item_existencia_almacen` AS ie ON i.id_item = ie.id_item WHERE i.cod_item_forma` = '" . $_GET["v1"] . "' AND i.estatus = 'A' AND ie.cantidad>0");
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `cod_item_forma` = '" . $_GET["v1"] . "' and estatus = 'A' order by descripcion1 asc");
//SELECT * FROM `item` as i left join compra as c on c.id_proveedor=6 left join compra_detalle as cd on c.id_compra=cd.id_compra WHERE i.cod_item_forma = 1 and i.id_item)
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                $i=0;
                foreach ($campos as $key => $value) {
                   $campos[$i]["descripcion1"]= utf8_encode($value["descripcion1"]);
                   $i++;
                }
                echo json_encode($campos);
            }
            break;
        case "Selectitemporproveedor":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `cod_item_forma` = '" . $_GET["v1"] . "' and estatus = 'A' order by descripcion1 asc");
//SELECT * FROM `item` as i left join compra as c on c.id_proveedor=6 left join compra_detalle as cd on c.id_compra=cd.id_compra WHERE i.cod_item_forma = 1 and i.id_item)
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "DetalleSelectitem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `id_item` = '" . $_GET["v1"] . "'");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "CargarAlmacenesDisponiblesByIdItem":
        $codAlmacen= $_GET["codAlmacen"];
         $ubicacion= $_GET["ubicacion"];        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_existenciabyalmacen WHERE id_item = '" . $_GET["v1"] . "' and cantidad > 0 AND cod_alamcen = '" . $_GET["codAlmacen"] . "' AND id_ubicacion= '" . $_GET["ubicacion"] . "' order by cod_almacen");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
              case "CargarAlmacenes":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM almacen order by cod_almacen");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
              } else {
                echo json_encode($campos);
            }
            break;

        case "ExistenciaProductoAlmacenDefaultByIdItem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT cod_grupo from item WHERE id_item='" . $_GET["v1"] . "'");
            if($campos[0]['cod_grupo']==172){
                echo "[{id:'-2'}]";
            }else{
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT almaexi.* FROM vw_existenciabyalmacen almaexi JOIN parametros_generales pg ON pg.cod_almacen = almaexi.cod_almacen WHERE id_item = '" . $_GET["v1"] . "' and cantidad > 0");
                if (count($campos) == 0) {
                    echo "[{id:'-1'}]";
                } else {
                    echo json_encode($campos);
                }
            }
        break;

        case "verificarExistenciaItemByAlmacen":
            //echo $var ="SELECT * FROM vw_existenciabyalmacen WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."'";
            $sql="SELECT descripcion FROM ubicacion WHERE id=".$_GET["ubicacion"]."";
            $ubicacion_pro=$conn->ObtenerFilasBySqlSelect($sql);

            if($ubicacion_pro[0]['descripcion']!='PISO DE VENTA')
            {

                $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_existenciabyalmacen WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."' and lote='". $_GET["lote"]."' and id_proveedor='". $_GET["id_proveedor"]."'");
            
           }elseif($ubicacion_pro[0]['descripcion']=='PISO DE VENTA')
           {
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_item_pisoventa WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."'");
           }
            
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
        break;

        case "DatosExistenciaItemByAlmacen":
            //echo $var ="SELECT * FROM vw_existenciabyalmacen WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."'";
            $sql="SELECT descripcion FROM ubicacion WHERE id=".$_GET["ubicacion"]."";
            $ubicacion_pro=$conn->ObtenerFilasBySqlSelect($sql);

            if($ubicacion_pro[0]['descripcion']!='PISO DE VENTA')
            {

                $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_existenciabyalmacen  WHERE cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."'  and id_proveedor='". $_GET["cliente"]."'");

            
           }elseif($ubicacion_pro[0]['descripcion']=='PISO DE VENTA')
           {
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_item_pisoventa WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."'");
           }
            
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                $sql="select codigo_barras, descripcion1 from item where id_item=".$campos[0]["id_item"];
                $producto=$conn->ObtenerFilasBySqlSelect($sql);
                echo json_encode($campos);
            }
        break;
        case "productoiddescripcion":
            $sql="select * from item where id_item=".$_GET["v1"];
            $producto=$conn->ObtenerFilasBySqlSelect($sql);
            if (count($producto) == 0) 
            {
                echo "[{id:'-1'}]";
            }
            else
            {
                echo json_encode($producto);
            }
            
        break;
        case "verificarExistenciaItemByAlmacen2":
            /*$campos = $conn->ObtenerFilasBySqlSelect("SELECT `st`.`PRODUCT` AS `Producto`,`i`.`cod_item` AS `cod_item`,`i`.`descripcion1` AS `descripcion1`,(`st`.`UNITS` - (select ifnull(sum(`item_precompromiso`.`cantidad`),0) AS `ifnull(sum(cantidad),0)`
            FROM `pyme_tiendaprueba1`.`item_precompromiso`)) AS `cantidad`
            FROM (`stockcurrent` `st` join `pyme_tiendaprueba1`.`item` `i` on((`i`.`itempos` = `st`.`PRODUCT`)))
            ORDER BY `cantidad` DESC");*/
            $sql="SELECT descripcion FROM ubicacion WHERE id=".$_GET["ubicacion"]."";
            $ubicacion_pro=$conn->ObtenerFilasBySqlSelect($sql);

            if($ubicacion_pro[0]['descripcion']!='PISO DE VENTA'){

            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_existenciabyalmacen WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."'");
            
           }elseif($ubicacion_pro[0]['descripcion']=='PISO DE VENTA'){
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_item_pisoventa WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."'");
           }
            
           
            if (count($campos) == 0 || empty($campos)) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
        break;
        case "precomprometeritem":

            if(SERVICIO=="si")
            {
                $sql = "INSERT INTO item_precompromiso (
                        `id_item_precomiso`, `cod_item_precompromiso`, `id_item`, `cantidad`, `usuario_creacion`,
                        `fecha_creacion`, `idSessionActualphp`, `almacen`,`ubicacion`)
                        VALUES (
                        NULL , '" . $_GET["codprecompromiso"] . "','" . $_GET["v1"] . "', '" . $_GET["cpedido"] . "', '" . $login->getUsuario() . "',
                        CURRENT_TIMESTAMP , '" . $login->getIdSessionActual() . "','" . $_GET["codalmacen"] . "','" . $_GET["ubicacion"] . "');";
                $conn->Execute2($sql);
                echo "[{'id':'1','observacion':''}]"; exit();   
            }
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT cod_grupo from item WHERE id_item='" . $_GET["v1"] . "'");
            if($campos[0]['cod_grupo']==172){
                 echo "[{'id':'1','observacion':''}]";
                exit();
            }
            
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT grupo.cantidad_rest, grupo.dias_rest, grupo.cod_grupo, departamentos.cod_departamento, item.referencia FROM
                    grupo INNER JOIN item ON grupo.cod_grupo = item.cod_grupO INNER JOIN departamentos ON grupo.id_rubro = departamentos.cod_departamentoWHERE item.id_item = '".$_GET["v1"]."' ");

            if((CASAEQUIPADA=="SI") && ($campos[0]["cod_departamento"]==2))
            {
                $cliente = $conn->ObtenerFilasBySqlSelect("SELECT clientes.id_cliente, clientes.rif FROM clientes WHERE id_cliente = '".$_GET["cliente"]."'");
                $cantidad = $campos[0]["cantidad_rest"];
                $dias = $campos[0]["dias_rest"];
                $grupo = $campos[0]["cod_grupo"];
                $fecha = date('Y-m-d');
                $nuevafecha = strtotime ( "-".$dias." day" , strtotime ( $fecha ) ) ;
                $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
                $cliente =  $cliente[0][rif];

        
        //echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura join item on item.id_item=factura_detalle.id_item WHERE item.cod_grupo = '".$grupo."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between  '$nuevafecha' and '$fecha'";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT ifnull(count(codigo),0) as cantidad FROM mcbe  WHERE codigo = '".$campos[0]['referencia']."' and cedula = '$cliente' ");
                $cantidadComprada = $campos[0]["cantidad"];

               // echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura WHERE id_item = '".$_GET["v1"]."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between '$nuevafecha' and '$fecha' ";
               // exit;

                if($cantidadComprada>=$cantidad)
                {
                    echo "[{'id':'-98','observacion':'Este cliente a alcazado el limite de compra por persona.'}]";
                    exit;
                }
            }

            if(RESTRICCIONES=="SI")
            {
		            
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT grupo.cantidad_rest, grupo.dias_rest, grupo.cod_grupo FROM grupo INNER JOIN item ON grupo.cod_grupo = item.cod_grupo WHERE restringido='1' and item.id_item = '".$_GET["v1"]."' ");
               if(count($campos)>0){
                    $cantidad = $campos[0]["cantidad_rest"];
                    $cantidadPedidad = $_GET["cpedido"];
                    $dias = $campos[0]["dias_rest"];
                    $grupo = $campos[0]["cod_grupo"];
                    $fecha = date('Y-m-d');
                    $nuevafecha = strtotime ( "-".$dias." day" , strtotime ( $fecha ) ) ;
                    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
                    $id=$login->getIdSessionActual();
                    $id_item=$_GET["v1"];

                     $preco = $conn->ObtenerFilasBySqlSelect(" SELECT count(*) as cant FROM `item_precompromiso` WHERE `idSessionActualphp`='".$id."' and `id_item`='".$id_item."'");
                   $cant=$preco[0]["cant"];
                      $canTotal=$cant + $cantidadPedidad;  
                    //echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura join item on item.id_item=factura_detalle.id_item WHERE item.cod_grupo = '".$grupo."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between  '$nuevafecha' and '$fecha'";
                  
                    $campos = $conn->ObtenerFilasBySqlSelect("SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura join item on item.id_item=factura_detalle.id_item WHERE item.cod_grupo = '".$grupo."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between  '$nuevafecha' and '$fecha'");
                    if(count($campos[0]["cantidad"])>0 || $campos[0]["cantidad"]!="NULL"){
                        $cantidadComprada = $campos[0]["cantidad"];
                    // echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura WHERE id_item = '".$_GET["v1"]."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between '$nuevafecha' and '$fecha' ";
                    // exit;

                        if($cantidadComprada>=$cantidad || $cantidadPedidad > $cantidad || $canTotal > $cantidad )
                        {
                            echo "[{'id':'-98','observacion':'Este cliente a alcazado el limite de compra por persona.'}]";
                            exit;
                        }

                    }
                  
                }
                
            }
          
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_existenciabyalmacen
            WHERE id_item = '" . $_GET["v1"] . "' and cod_almacen = '" . $_GET["codalmacen"] . "' and id_ubicacion='". $_GET["ubicacion"]."'");

            $cantidadExistenteOLD = $campos[0]["cantidad"];
            $cantidadPedidad = $_GET["cpedido"];


            $cantidadExistenteNEW = $cantidadExistenteOLD - $cantidadPedidad;
            if ($cantidadExistenteNEW < 0) {
                echo "[{'id':'-99','observacion':'La cantidad Pedida es mayor a la existente.'}]";
                exit;
            }
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE id_item = " . $_GET["v1"] . " and cod_item_forma = 1"); // 1: item producto
            if (count($campos) > 0) {
//if(strcmp($_GET["tipo_transaccion"],"presupuesto")){
#echo $_GET["tipo_transaccion"];exit;
                $sql = "INSERT INTO item_precompromiso (
                        `id_item_precomiso`, `cod_item_precompromiso`, `id_item`, `cantidad`, `usuario_creacion`,
                        `fecha_creacion`, `idSessionActualphp`, `almacen`,`ubicacion`)
                        VALUES (
                        NULL , '" . $_GET["codprecompromiso"] . "','" . $_GET["v1"] . "', '" . $_GET["cpedido"] . "', '" . $login->getUsuario() . "',
                        CURRENT_TIMESTAMP , '" . $login->getIdSessionActual() . "','" . $_GET["codalmacen"] . "','" . $_GET["ubicacion"] . "');";
                $conn->Execute2($sql);
                echo "[{'id':'1','observacion':''}]";
//}
            } else {
                echo "[{'id':'-1','observacion':''}]";
            }
            break;
        case 'seleccionarPedidoPendiente':
            header("Content-Type: text/plain");
            $sql = "SELECT * FROM pedido_detalle pd inner join item i on i.id_item = pd.id_item WHERE id_pedido = {$_GET["id_pedido"]};";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
            break;
        case 'seleccionarNotaEntregaPendiente':
            header("Content-Type: text/plain");
            $sql = "SELECT * FROM nota_entrega_detalle WHERE id_nota_entrega = {$_GET["id_nota"]};";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
            break;
        case 'seleccionarCotizacionPendiente':
            header("Content-Type: text/plain");
            $sql = "SELECT * FROM cotizacion_presupuesto_detalle WHERE id_cotizacion = {$_GET["id_cotizacion"]};";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
            break;
        /* case 'generarCuotas':

          header("Content-Type: text/plain");

          $correlativos = new Correlativos();
          $cuotas = $_POST["cuota"];
          $cuota_precios = $_POST["precio"];
          $cuota_anios = $_POST["anio"];
          $cuota_meses = $_POST["mes"];

          $conn->Execute2("UPDATE `cuota` SET `estatus` = 1 WHERE `id` = {$_POST["id_cuota"]};");

          foreach ($cuotas as $key => $cuota_descripcion) {//Pendiente de quitar el 1 para `cuota_cuota_id` y eliminar dicho campo de la tabla

          $codigo = $correlativos->getUltimoCorrelativo("cod_producto", 1, "si", "C");
          $conn->ExecuteTrans("UPDATE correlativos SET contador = contador + 1 WHERE campo = 'cod_producto';");

          $cuota = $conn->ObtenerFilasBySqlSelect("SELECT descripcion FROM cuota WHERE id = {$_POST["id_cuota"]};");

          //$cuota_descripcion = $cuota[0]["descripcion"] . " " . $cuota_anios[$key] . "-" . ($cuota_meses[$key] < 10 ? "0" . $cuota_meses[$key] : $cuota_meses[$key]);

          $instruccion = "INSERT INTO `item`(
          `cod_item`, `costo_actual`, `descripcion1`,
          `precio1`, `utilidad1`, `coniva1`, `precio2`, `utilidad2`,
          `coniva2`, `precio3`, `utilidad3`, `coniva3`, `existencia_min`,
          `existencia_max`, `monto_exento`, `iva`,
          `estatus`,`usuario_creacion`, `fecha_creacion`, `cod_item_forma`, unidad_empaque,
          costo_promedio, costo_anterior)
          VALUES(
          '{$codigo}', '{$cuota_precios[$key]}', '{$cuota_descripcion}',
          '{$cuota_precios[$key]}', '0', '{$cuota_precios[$key]}', '{$cuota_precios[$key]}',
          '0', '{$cuota_precios[$key]}', '{$cuota_precios[$key]}', '0',
          '{$cuota_precios[$key]}', '0',  '0', '0', '0',
          'I', '" . $login->getUsuario() . "', CURRENT_TIMESTAMP, 4, NULL,
          '{$cuota_precios[$key]}', '{$cuota_precios[$key]}');";

          $conn->ExecuteTrans($instruccion);

          $id_item = $conn->getInsertID();

          $instruccion = "INSERT INTO `cuota_mes` (`id`, `cuota_id`, `cuota_cuota_id`, `id_item`, `precio`, `mes`, `anio`)
          VALUES (NULL, {$_POST["id_cuota"]}, 1, {$id_item}, {$cuota_precios[$key]}, $cuota_meses[$key], {$cuota_anios[$key]});";
          $campos = $conn->Execute2($instruccion);
          //echo (count($campos) == 0) ? "1" : "-1";
          }
          break; */
        case 'ponerCuotaPagada':
            header("Content-Type: text/plain");
            /* Aqui debo recibir un array con todas las cuotas seleeccionadas en la pantalla de facturacion */
            $cuotas = json_decode($_POST["cuotas"]);
            $cliente = json_decode($_POST["cliente"]);
            foreach ($cuotas as $cuota) {
#$conn->Execute2("UPDATE `cuota_cliente` SET estatus = 1 WHERE id = {$cuota};");
                $seleccionadas .= $cuota . ",";
            }
            $seleccionadas = trim($seleccionadas, ",");
//$campos = $conn->ObtenerFilasBySqlSelect("SELECT cc.id, cc.id_cuota_generada, cc.id_cliente, cm.cuota_id, cm.precio AS precio, cm.mes AS mes, cm.anio AS anio, c.descripcion FROM cuota_cliente AS cc INNER JOIN cuota_mes AS cm ON cc.id_cuota_generada = cm.id INNER JOIN cuota AS c ON cm.cuota_id = c.id WHERE cc.id_cliente = {$cliente} AND cc.estatus = 0 AND cc.id IN ({$seleccionadas});");
            $campos = $conn->ObtenerFilasBySqlSelect("
                    SELECT i.cod_item, cc.id, cc.id_cuota_generada, cc.id_cliente, cm.cuota_id, cm.id_item, cm.precio AS precio, cm.mes AS mes, cm.anio AS anio, c.descripcion
                    FROM cuota_cliente AS cc
                    INNER JOIN cuota_mes AS cm ON cc.id_cuota_generada = cm.id
                    INNER JOIN cuota AS c ON cm.cuota_id = c.id
                    INNER JOIN item AS i ON i.id_item = cm.id_item
                    WHERE cc.id_cliente = {$cliente} AND cc.estatus = 0 AND cc.id IN ({$seleccionadas});");
            echo json_encode($campos);
            break;
        /* case "asignarCuotas":
          $clientes_existentes = $conn->ObtenerFilasBySqlSelect("SELECT id_cliente, fdeuda FROM clientes WHERE cod_tipo_cliente = 1 --Contadores;");
          $datacuota = $conn->ObtenerFilasBySqlSelect("SELECT id, mes, anio FROM cuota_mes WHERE cuota_id = {$_GET["id_cuota"]} AND estado = 0 -- No asignada");

          $cant = count($datacuota);

          foreach ($clientes_existentes as $cli) {
          $fdeuda = explode("-", $cli["fdeuda"]);
          if ($cli["fdeuda"] != "0000-00-00") {
          $asignar = false;
          foreach ($datacuota as $cuota) {
          if ($fdeuda[0] == $cuota["anio"] && $fdeuda[1] == $cuota["mes"]) {
          $asignar = true;
          }
          if ($asignar) {
          $conn->Execute2("INSERT INTO `cuota_cliente` (`id_cuota_generada`, `id_cliente`) VALUES ({$cuota["id"]}, {$cli["id_cliente"]});");
          }
          }
          }
          $conn->ExecuteTrans("UPDATE cuota_mes SET estado = 1 WHERE cuota_id = {$_GET["id_cuota"]};");
          }
          //$cuotas = $conn->Execute2("UPDATE `cuota` SET `estatus` = 2 WHERE `id` = {$_GET["id_cuota"]};");
          echo json_encode(array(
          "success" => true,
          "total" => $cant, //count($cuotas),
          "data" => $cuotas
          ));
          break; */
        case 'tipoFacturacion':
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT tipo_facuracion FROM parametros_generales");
            echo json_encode($campos);
            break;
        case "delete_precomprometeritem":
            $sql = "delete from item_precompromiso
                    WHERE cod_item_precompromiso = '" . $_GET["codprecompromiso"] . "'  and
                    idSessionActualphp = '" . $login->getIdSessionActual() . "'      and
                    usuario_creacion = '" . $login->getUsuario() . "' and id_item = '" . $_GET["v1"] . "'";
            $conn->Execute2($sql);
            break;
        case "det_edocuentacxp":
            $data_parametros = $conn->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
            foreach ($data_parametros as $key => $lista) {
                $valueSELECT[] = $lista["cod_empresa"];
                $outputidfiscalSELECT[] = $lista["moneda"];
            }
            $campos = $conn->ObtenerFilasBySqlSelect("
                SELECT *, vw_cxp.numero AS num_cdet, cxp_edocuenta.vencimiento_persona_contacto, cxp_edocuenta.vencimiento_telefono, cxp_edocuenta.vencimiento_descripcion
                FROM vw_cxp
                INNER JOIN cxp_edocuenta ON cxp_edocuenta.cod_edocuenta = vw_cxp.cod_edocuenta
                WHERE vw_cxp.cod_edocuenta = " . $_GET["cod_edocuenta"]);
            if (count($campos) == 0) {
                exit;
            }
            echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Documento</th>
                        <th style="border-bottom: 1px solid #949494;">N&uacute;mero</th>
                        <th style="border-bottom: 1px solid #949494;width:120px;">Fecha Emisi&oacute;n</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">Descripci&oacute;n</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Abonos/Pagos</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Deuda</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Opt</th>
                    </thead>
                    <tbody>';

            $acuDebitos = 0;
            $acuCreditos = 0;
            foreach ($campos as $key => $item) {
                if ($item["estado"] <> '0') {
                    echo '
                        <tr>
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">' . $item["cod_edocuenta_detalle"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:110px;">' . $item["documento_cdet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;">' . $item["num_cdet"] . '</td>
                            <td align="center" style="border-bottom: 1px solid #949494;width:120px;">' . $item["fecha_emision_edodet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:300px;">' . $item["descripcion"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;width:110px;">' . number_format($item['debito'], 2, ",", ".") . ' ' . $lista["moneda"] . ' </td>
                            <td align="right" style="border-bottom: 1px solid #949494;">' . number_format($item['credito'], 2, ",", ".") . ' ' . $lista["moneda"] . ' </td>
                            <td align="right" style="border-bottom: 1px solid #949494;">';

                    if ($key > 0) {
                        echo "<input type='hidden' id='detalle_asiento' name='detalle_asiento' value='" . $item["cod_edocuenta_detalle"] . "'>";
                        echo '<img onclick="javascript: guardarr(' . $item["cod_edocuenta_detalle"] . ')" style="cursor:pointer;" title="Eliminar Asiento" src="../../libs/imagenes/cancel.gif">';
                    }

                    echo '</td>
        </tr>';
                }
                $acuDebitos += $item['debito'];
                $acuCreditos += $item['credito'];
            }
            echo '
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;width:300px;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Total Pagos,Abonos/Deudas:</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuDebitos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuCreditos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Saldo Pendiente:</b></td>
                            <td colspan="2"align="right" style="border-bottom: 1px solid #949494;"><b style="color:red;">' . number_format($acuCreditos - $acuDebitos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;">

                            </td>
                        </tr>
    ';

            if ($campos[0]["marca"] != "X") {
                echo '

                        <tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=89&opt_seccion=88&opt_subseccion=pagoabonoCXP&cod=' . $_GET["codigo_proveedor"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Agregar Pago/Abono</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
			<td colspan="7" style="text-align: left; border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=89&opt_seccion=88&opt_subseccion=facturasCXP&cod=' . $_GET["codigo_proveedor"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Facturas/Notas de Credito</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
        ';
            }
            if ($campos[0]["marca"] == "X") {
                echo '

                        <tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;">
                        </td>
			<td colspan="7" style="text-align: left; border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=85&opt_seccion=88&opt_subseccion=facturasCXP&cod=' . $_GET["codigo_proveedor"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Facturas/Notas de Credito</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
        ';
            }


            echo
            '</tbody>
    </table>
    </div>
    </td>
    </tr>';




            break;

        case "det_edocuenta":
            $data_parametros = $conn->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
            foreach ($data_parametros as $key => $lista) {
                $valueSELECT[] = $lista["cod_empresa"];
                $outputidfiscalSELECT[] = $lista["moneda"];
            }
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT *
,vw_cxc.numero as num_cdet
,cxc_edocuenta.vencimiento_persona_contacto,
cxc_edocuenta.vencimiento_telefono,
cxc_edocuenta.vencimiento_descripcion from vw_cxc
 inner join cxc_edocuenta on cxc_edocuenta.cod_edocuenta = vw_cxc.cod_edocuenta
WHERE vw_cxc.cod_edocuenta = " . $_GET["cod_edocuenta"]);
            if (count($campos) == 0) {
                exit;
            }
            echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div  style=" background-color:#fdfdfd; border: 1px solid #ededed;-moz-border-radius: 7px;padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px;  font-size: 13px; ">
                <table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Documento</th>
                        <th style="border-bottom: 1px solid #949494;">Numero</th>
                        <th style="border-bottom: 1px solid #949494;width:120px;">Fecha EmisiÃ³n</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">DescripciÃ³n</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Deuda</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Pago/Abono</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Opt</th>
                    </thead>
                    <tbody>';


            $acuDebitos = 0;
            $acuCreditos = 0;
            foreach ($campos as $key => $item) {
                echo '
                        <tr>
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">' . $item["cod_edocuenta_detalle"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:110px;">' . $item["documento_cdet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;">' . $item["num_cdet"] . '</td>
                            <td align="center" style="border-bottom: 1px solid #949494;width:120px;">' . $item["fecha_emision_edodet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:300px;">' . $item["descripcion"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">' . number_format($item['debito'], 2, ",", ".") . ' ' . $lista["moneda"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">' . number_format($item['credito'], 2, ",", ".") . ' ' . $lista["moneda"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">';
//if($item['debito']=="0.00"){
                if ($key > 0) {
                    echo '<img class="eliminarAsiento"  style="cursor:pointer;" title="Eliminar Asiento" src="../../libs/imagenes/cancel.gif">';
                    echo "<input type='hidden' id='detalle_asiento' name='detalle_asiento' value='" . $item["cod_edocuenta_detalle"] . "'>";
                }

                echo '</td>
        </tr>';

                $acuDebitos += $item['debito'];
                $acuCreditos += $item['credito'];
            }
            echo '
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;width:300px;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Total Deudas,Pagos/Abonos:</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuDebitos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuCreditos, 2, ",", ".") . '  ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Saldo Pendiente:</b></td>
                            <td colspan="2"align="right" style="border-bottom: 1px solid #949494;"><b style="color:red;">' . number_format($acuDebitos - $acuCreditos, 2, ",", ".") . '  ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="7" align="right" style="border-bottom: 1px solid #949494;width:300px;">

                            </td>
                        </tr>
    ';


            if ($campos[0]["marca"] != "X") {
                echo '<tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;width:110px;">
                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=5&opt_seccion=59&opt_subseccion=pagooabono&cod=' . $_GET["codigo_cliente"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Agregar Pago/Abono</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                            <br>
                            <img src="../../libs/imagenes/ico_user.gif"> Persona de Contacto: ' . $campos[0]["vencimiento_persona_contacto"] . '<br>
                            <img src="../../libs/imagenes/ico_cel.gif"> Telefono de Contacto: ' . $campos[0]["vencimiento_telefono"] . '<br>
                            <img src="../../libs/imagenes/ico_view.gif"> ObservaciÃ³n: ' . $campos[0]["vencimiento_descripcion"] . '<br>
                            <img src="../../libs/imagenes/ew_calendar.gif"> Fecha de Vencimiento: ' . $campos[0]["vencimiento_fecha"] . '<br>

                        </td>
                        </tr>
        ';
            }
            echo '</tbody></table></div></td></tr>';
            break;
        

        case "det_items_calidad3":
       $campos= $conn->ObtenerFilasBySqlSelect("select c.codigo_barras, c.descripcion1, m.marca, c.pesoxunidad, d.nombre_unidad, b.cantidad, b.observacion from calidad_almacen as a, calidad_almacen_detalle as b LEFT JOIN marca m ON b.id_marca= m.id, item as c, unidad_medida as d where b.id_item=c.id_item and a.id_transaccion=b.id_transaccion and c.unidadxpeso=d.id and a.id_transaccion=".$_GET['id_transaccion']);

 
        echo '<tr class="detalle_items">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
               <table >
                    <thead>
                        
                       <th style="width:150px; font-weight: bold;">Codigo Barras</th>
                       <th style="width:350px; font-weight: bold;">Nombre</th>
                        <th style="width:150px; font-weight: bold;">Cantidad</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Observación</th>
                    </thead>
                   <tbody>';
          foreach ($campos as $key => $item) {
                //if(empty($item["marca"])
                  //      $item["marca"]='SIN MARCA';
                  echo '
                       <tr>
                        <td style="width:150px; padding-left:10px;">' . $item["codigo_barras"] . '</td>
                        <td style="width:110px; text-align: right; padding-right:10px;">' .$item["descripcion1"].'- '.$item["marca"].' '.$item["pesoxunidad"].' '.$item["nombre_unidad"].'</td>
                        <td style="width:150px; padding-left:10px;">' . $item["cantidad"] . '</td>
                        <td style="width:150px; padding-left:10px;">' . $item["observacion"] . '</td>
                        </tr>';
                   }
      break;
      
        case "det_items":
            if ($_GET["id_tipo_movimiento_almacen"] == '3' || $_GET["id_tipo_movimiento_almacen"] == '1') {
                $operacion = "Entrada";

                $campos = $conn->ObtenerFilasBySqlSelect("SELECT ite.*,m.*,kad.cantidad AS cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
        FROM kardex_almacen_detalle AS kad
        JOIN kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion
        LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen
        LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id
        LEFT JOIN item AS ite ON kad.id_item=ite.id_item
        LEFT JOIN marca m ON m.id = ite.id_marca
        LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
        WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            } else if ($_GET["id_tipo_movimiento_almacen"] == '4' || $_GET["id_tipo_movimiento_almacen"] == '2' || $_GET["id_tipo_movimiento_almacen"] == '8') {
                $operacion = "Salida";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item, kad.peso, ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
        FROM kardex_almacen_detalle AS kad
        join kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion
        LEFT JOIN marca m ON m.id = kad.id_marca
        LEFT JOIN almacen AS alm ON kad.id_almacen_salida=alm.cod_almacen
        LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id
        LEFT JOIN item AS ite ON kad.id_item=ite.id_item
        LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
        WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            } else if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                $operacion = "Traslado";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
        FROM kardex_almacen_detalle AS kad
        JOIN kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion
        LEFT JOIN marca m ON m.id = kad.id_marca
        LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen
        LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id
        LEFT JOIN item AS ite ON kad.id_item=ite.id_item
        LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
        WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
                $campos1 = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            }else if ($_GET["id_tipo_movimiento_almacen"] == '9') {
                $operacion = " Ajuste +";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
        FROM kardex_almacen_detalle AS kad
        LEFT JOIN kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion
        LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen
        LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id
        LEFT JOIN item AS ite ON kad.id_item=ite.id_item
        LEFT JOIN marca m ON m.id = ite.id_marca
        LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
        WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
                $campos1 = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            }else if ($_GET["id_tipo_movimiento_almacen"] == '10') {
                $operacion = " Ajuste -";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
        FROM kardex_almacen_detalle AS kad
        LEFT JOIN kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion
        LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen
        LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id
        LEFT JOIN item AS ite ON kad.id_item=ite.id_item
        LEFT JOIN marca m ON m.id = ite.id_marca
        LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
        WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
                $campos1 = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            }else if ($_GET["id_tipo_movimiento_almacen"] == '14') 
            {
                $operacion = "Tunel Descargo";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item, kad.peso, ubi.descripcion as ubicacion, alm.descripcion as almacen, um.nombre_unidad
        FROM kardex_almacen_detalle AS kad
        join kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion
        LEFT JOIN marca m ON m.id = kad.id_marca
        LEFT JOIN almacen AS alm ON kad.id_almacen_salida=alm.cod_almacen
        LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id
        LEFT JOIN item AS ite ON kad.id_item=ite.id_item
        LEFT JOIN unidad_medida um ON ite.unidadxpeso = um.id
        WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
                }
//$campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
//from kardex_almacen_detalle as kad left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item WHERE id_transaccion = ".$_GET["id_transaccion"]);
//echo $campos;
            if (count($campos) == 0) {
                exit;
            }

            if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                echo '<tr class="detalle_items">
                <input type="hidden" name="desplegado" value="true"/>
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="width:110px; font-weight: bold; text-align: center;">C&oacute;digo</th>
                        <th style="width:150px; font-weight: bold;">Almac&eacute;n Entrada</th>
                        <th style="width:150px; font-weight: bold;">Ubicaci&oacute;n Entrada</th>
                        <th style="width:150px; font-weight: bold;">Almac&eacute;n Salida</th>
                         <th style="width:150px; font-weight: bold;">Ubicaci&oacute;n Salida</th>
                        <th style="width:300px; font-weight: bold;">Item</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                    </thead>
                    <tbody>';
            } else {
                echo '<tr class="detalle_items">
                <input type="hidden" name="desplegado" value="true"/>
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="width:110px; font-weight: bold; text-align: center;">C&oacute;digo</th>
                        
                        <th style="width:300px; font-weight: bold;">Item</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Peso</th>
                    </thead>
                    <tbody>';
            }

            foreach ($campos as $key => $item) {
                if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["codigo_barras"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["almacen"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["ubicacion"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["almacen"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["ubicacion"] . '</td>
                            <td style="width:300px;">' . $item["descripcion1"] ." - ". $item["marca"]." ". $item["pesoxunidad"]."". $item["nombre_unidad"]. '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                        </tr>';
                } else {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["codigo_barras"] . '</td>
                            
                            <td style="width:300px; padding-left:10px;">' . $item["descripcion1"] ." - ". $item["marca"] . " ". $item["pesoxunidad"]."". $item["nombre_unidad"].'</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['peso'] . '</td>
                        </tr>';
                }
            }

            if ($campos[0]["estado"] == "Pendiente") {
                echo '<tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;width:110px;">
<br/><!--form>
<label for="fecha">Fecha</label><input type="text" name="fecha">
<label for="control">Nro. Control</label><input type="text" name="control">
<label for="factura">Nro. Factura</label><input type="text" name="factura"-->
<table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=3&opt_seccion=109&opt_subseccion=add&cod=' . $_GET["id_transaccion"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <!--<td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                     <td class="btn_bg" nowrap style="padding: 0px 1px;">Realizar Entrada</td> -->
                                    <!-- <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>-->
                                </tr>
                            </table>
                            <!--/form-->
                        </td>
                        </tr>';
            }
            echo
            '</tbody>
    </table>
    </div>
    </td>
    </tr>';

            break;

        case "getAlmacen":
            if(isset($_GET['cliente']) && $_GET['cliente']!=null)
            {
                $sql="select b.descripcion, b.cod_almacen from item_existencia_almacen as a inner join almacen as b on a.cod_almacen=b.cod_almacen where a.id_proveedor='".$_GET['cliente']."' group by a.cod_almacen order by b.cod_almacen";
                $campos = $conn->ObtenerFilasBySqlSelect($sql);
            }
            else
            {
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM almacen");    
            }
            

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;

            case "gettipo_uso":

            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM tipo_uso");

            if (count($campos) == 0) {
               echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
           }
            break;

            case "getPuntos":
            $estado=$_GET["estados"];
            $sql="SELECT nombre_punto,codigo_siga_punto as siga  from puntos_venta where estatus='A'";
            if($estado!=9999){
                $sql.=" AND codigo_estado_punto=$estado";
            } 
            $campos = $conn->ObtenerFilasBySqlSelect($sql);

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }

            break;

            case "getInstalaciones":
            $estado=$_GET["estados"];
            $id_cliente=$_GET["cliente"];
            $sql="SELECT direccion as nombre_punto,codigo_sica as siga  from instalacion_clientes where id_cliente='".$id_cliente."'";
            if($estado!=9999){
                $sql.=" AND estado=$estado";
            } 
            $campos = $conn->ObtenerFilasBySqlSelect($sql);

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            //echo $sql; 
            break;

        case 'listaCXPpendientes':
            header("Content-Type: text/plain");
            $groupByBeneficiario = isset($_POST["groupBene"]) ? 'si' : 'no';
            if ($groupByBeneficiario == "no") {
                $sql_ = "SELECT   pro.id_proveedor, cxpd. * , pro.descripcion AS beneficiario, pro.rif, cxpd.monto as monto_pagar, (
    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'd'
    ) AS sum_debito, (

    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'c'
    ) AS sum_credito, (
    (

    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'c'
    ) - (
    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'd' )
    ) AS monto_pendiente
    FROM cxp_edocuenta_detalle cxpd
    INNER JOIN cxp_edocuenta cxp ON cxpd.cod_edocuenta = cxp.cod_edocuenta
    INNER JOIN proveedores pro ON pro.id_proveedor = cxp.id_proveedor
    WHERE cxpd.marca = 'P' and cxpd.documento<>'PAGOxCOM'
    ";

                if (isset($_POST["id_proveedor"])) {
                    $sql_ .= " and  pro.id_proveedor = " . $_POST["id_proveedor"];
                }


                $campos = $conn->ObtenerFilasBySqlSelect($sql_);



                $start = isset($_POST['start']) ? $_POST['start'] : 0; //posiciÃ³n a iniciar
                $limit = isset($_POST['limit']) ? $_POST['limit'] : 30; //nÃºmero de registros a mostrar

                echo json_encode(array(
                    "success" => true,
                    "total" => count($campos),
                    "data" => array_splice($campos, $start, $limit)
                ));
            }

            if ($groupByBeneficiario == "si") {
                $sql_ = "SELECT  distinct  pro.id_proveedor, pro.descripcion AS beneficiario
FROM cxp_edocuenta_detalle cxpd
INNER JOIN cxp_edocuenta cxp ON cxpd.cod_edocuenta = cxp.cod_edocuenta
INNER JOIN proveedores pro ON pro.id_proveedor = cxp.id_proveedor
WHERE cxpd.marca = 'P' and cxpd.documento<>'PAGOxCOM'";


                $campos = $conn->ObtenerFilasBySqlSelect($sql_);

                echo json_encode(array(
                    "success" => true,
                    "total" => count($campos),
                    "data" => $campos
                ));
            }





            break;
        case "convertiraLetras":

            header("Content-Type: text/plain");

            $n = new numerosALetras();
            $numero = $_GET["monto"];
            $num_letras = $n->convertir($numero);

            $array = array(
                "success" => true,
                "monto" => $num_letras
            );
            echo json_encode($array);
            break;
        case "tesodetasientos":
            header("Content-Type: text/plain");
            $cod_cheque = $_POST["cod_cheque"];
            $sql_ = "
                SELECT cod_cheque_bauchedet, cod_cheque, descripcion, cuenta_contable,
                CASE tipo WHEN  'd' THEN monto ELSE  '' END AS debito,
                CASE tipo WHEN  'c' THEN monto ELSE  '' END AS credito
                FROM `cheque_bache_det` WHERE cod_cheque = {$cod_cheque} ORDER BY tipo DESC;";
            /* SELECT
              cod_cheque_bauchedet,
              cod_cheque,
              descripcion,
              cuenta_contable,
              case tipo when 'd' then monto else '' end as debito,
              case tipo when 'c' then monto else '' end as credito
              FROM `cheque_bache_det` WHERE cod_cheque = " . $cod_cheque . " order by tipo desc
              "; */
            $campos = $conn->ObtenerFilasBySqlSelect($sql_);
            echo json_encode(array(
                "success" => true,
                "total" => count($campos),
                "data" => $campos
            ));
            break;
        case "store_cuContable":
            header("Content-Type: text/plain");
// CONSULTA DE CUENTAS CONTABLES
            $global = new bd(SELECTRA_CONF_PYME);

            if (isset($_POST["query"])) {
                /* if ($_POST["query"] == "") {
                  $cuentalike = " order by cuenta";
                  } else {
                  $cuentalike = " and upper(concat(cuenta,' .-',Descrip)) like upper('%" . $_POST["query"] . "%') order by cuenta";
                  } */
                $cuentalike = ($_POST["query"] == "") ? " ORDER BY cuenta" : " AND UPPER (CONCAT(cuenta,' .-',Descrip)) LIKE UPPER('%{$_POST["query"]}%') ORDER BY cuenta";
            }
            $sentencia = "SELECT * FROM nomempresa WHERE bd = '{$_SESSION['EmpresaFacturacion']}';";
            $contabilidad = $global->query($sentencia);
            $fila = $contabilidad->fetch_assoc();
            $campos_cuentas_cont = $conn->ObtenerFilasBySqlSelect("SELECT CONCAT(cuenta,' .-',Descrip) AS descripcion, cuenta FROM {$fila['bd_contabilidad']}.cwconcue WHERE Tipo = 'P'" . $cuentalike);
//echo "select cuenta as descripcion, cuenta from ".$fila['bd_contabilidad'].".cwconcue WHERE Tipo='P'".$cuentalike." order Cuenta";
            $campos_cuentas_cant = $conn->ObtenerFilasBySqlSelect("SELECT cuenta AS descripcion, cuenta FROM {$fila['bd_contabilidad']}.cwconcue WHERE Tipo = 'P'" . $cuentalike);

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_cuentas_cant),
                "data" => $campos_cuentas_cont
            ));
            break;
        case "store_vendedores":
            $campos_comunes = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vendedor ORDER BY nombre");
#$campos_comunes = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vendedor WHERE nombre = '".$_GET["nombre_vendedor"]."'");
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));
            break;
        case "store_tipoCuenta":
            $campos_comunes = $conn->ObtenerFilasBySqlSelect("SELECT * FROM tipo_cuenta_banco");
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));
            break;
        case "aCheBaucheDetCRUP":

            if ($_POST["cod_cheque_bauchedet"] != "" && $_POST["in_deleted"] != 1) {//UPDATIAR
                $sql = "
            update cheque_bache_det set
                        `monto` = " . $_POST["monto"] . ",
                        `tipo` = '" . (($_POST["tipo_a"] == "Debito") ? 'd' : 'c') . "',
                        `descripcion` = '" . $_POST["descripcion"] . "',
                        cuenta_contable = '" . $_POST["cuenta_contable"] . "'
           WHERE cod_cheque_bauchedet = " . $_POST["cod_cheque_bauchedet"];
                $conn->Execute2($sql);
            } elseif ($_POST["in_deleted"] == "1") {

                $sql = "delete from cheque_bache_det WHERE cod_cheque_bauchedet = " . $_POST["cod_cheque_bauchedet"];
                $conn->Execute2($sql);
            } else {//NUEVO ASIENTO CHEQUE BAUCHE DET
                $sql = "
            INSERT INTO `cheque_bache_det` (
                        `cod_cheque`,
                        `monto`,
                        `tipo`,
                        `fecha`,
                        `descripcion`,
                        `fecha_creacion`,
                        `usuario_creacion`,cuenta_contable)
                        VALUES (
                            " . $_POST["cod_cheque"] . ",
                            " . $_POST["monto"] . ",
                            '" . (($_POST["tipo_a"] == "Debito") ? 'd' : 'c') . "',
                            '" . date("Y-m-d") . "',
                            '" . $_POST["descripcion"] . "',
                            CURRENT_TIMESTAMP,
                            '" . $_SESSION['usuario'] . "',
                            '" . $_POST["cuenta_contable"] . "');";
                $conn->Execute2($sql);
            }

            echo json_encode(array(
                "success" => true,
                "msg" => "Asiento registrado exitosamente."
            ));


            break;
        case "listaProveedores":
            $campos_comunes = $conn->ObtenerFilasBySqlSelect("
    select
        id_proveedor,
        cod_proveedor,
        descripcion as  beneficiario,
        direccion,
        telefonos,
        fax,
        email,
        rif,
        nit
    from
        proveedores
	WHERE
	estatus='A'");
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));
            break;
        case 'movimientos_bancarios_conciliar':

            list($dia1, $mes1, $anio1) = explode("/", $_POST["fecha1_"]);
            list($dia2, $mes2, $anio2) = explode("/", $_POST["fecha2_"]);
            $fecha1 = $anio1 . "-" . $mes1 . "-" . $dia1;
            $fecha2 = $anio2 . "-" . $mes2 . "-" . $dia2;
            $cod_cuenta = $_POST["cod_cuenta"];
            $sql = "
SELECT
mb.cod_movimiento_ban,
mb.cod_tesor_bancodet,
tm.descripcion as tipo_movimiento_desc,
mb.numero_movimiento,
mb.fecha_movimiento,
mb.concepto,
case when mb.tipo_movimiento =  3 or mb.tipo_movimiento =  4 then mb.monto  else 0 end debe,
case when mb.tipo_movimiento  =  1 or mb.tipo_movimiento =  2 then mb.monto  else 0 end haber,
mb.tipo_movimiento,
mb.estado,
mb.cod_conciliacion,
'false' as conciliar
 FROM `movimientos_bancarios` mb inner join tipo_movimientos_ban tm
 on tm.cod_tipo_movimientos_ban = mb.tipo_movimiento
 WHERE mb.fecha_movimiento between '" . $fecha1 . "' and '" . $fecha2 . "'
 and mb.cod_tesor_bancodet = " . $cod_cuenta . "  and mb.cod_conciliacion is null
order by mb.cod_movimiento_ban";
            $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));

            break;

        case 'cxpIvaFactura':

            $MONTOBASE = $_GET[montoBase];
            $codIva = $_GET[codIva];

            $ivas = $conn->ObtenerFilasBySqlSelect("select li.alicuota, fi.formula from lista_impuestos li join formulacion_impuestos fi on (li.cod_formula=fi.cod_formula) WHERE li.cod_impuesto=$codIva");
            $PORCENTAJE = $ivas[0][alicuota];
            eval($ivas[0][formula]);
            echo $cad = $PORCENTAJE . '-' . $MONTO;
            break;

        case 'cxpRetIslrFactura':
            $par1 = $conn->ObtenerFilasBySqlSelect("select unidad_tributaria from parametros_generales");
            $id_item = $_GET[servicio];
            $cod_entidad = $_GET[entidad];
            $item_totalsiniva = $_GET[montoBase];
            $islr = $conn->ObtenerFilasBySqlSelect("select si.cod_lista_impuesto, fi.formula, li.alicuota, li.pago_mayor_a, li.monto_sustraccion, li.descripcion, li.cod_impuesto from servicios_islr si join lista_impuestos li on (si.cod_lista_impuesto=li.cod_impuesto) join formulacion_impuestos fi on (fi.cod_formula=li.cod_formula) WHERE si.cod_item=$id_item and li.cod_entidad=$cod_entidad and li.pago_mayor_a<$item_totalsiniva");
            if ($islr[0]) {
                $UT = $par1[0]["unidad_tributaria"];
                $FACTORSUST = $islr[0]["monto_sustraccion"];
                $FACTORM = $islr[0]["pago_mayor_a"];
                $PORCENTAJE = $islr[0]["alicuota"];
                $MONTOBASE = $item_totalsiniva;
                $formula = $islr[0]["formula"];
                eval($formula);

                echo number_format($MONTO, 2, ".", "");
            }
            else
                echo $cad = 0;
            break;

        case 'retencionesFactura':

            $codFacs = $_GET["facs"];

            $retenciones = $conn->ObtenerFilasBySqlSelect("SELECT cpf.cod_impuesto, li.descripcion, sum(cpf.monto_iva) as base, porcentaje_iva_retenido, sum(cpf.monto_retenido) as montoRet, sum(cpf.monto_exento) as exento, li.cod_tipo_impuesto FROM cxp_factura cpf JOIN lista_impuestos li ON ( li.cod_impuesto = cpf.cod_impuesto ) WHERE id_factura in ($codFacs) group by cpf.cod_impuesto");
            $reg = '';
            $i = 0;
            foreach ($retenciones as $key => $campos) {
                if ($campos[montoRet] > 0) {
                    $reg.="<tr><TD><input type='hidden' name='codImp$i' id='codImp$i' value='$campos[cod_impuesto]'><input type='hidden' name='exento$i' id='exento$i' value='$campos[exento]'><input type='hidden' name='tipoImp$i' id='tipoImp$i' value='$campos[cod_tipo_impuesto]'>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='base$i' id='base$i' value='$campos[base]'></TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='porcen$i' id='porcen$i' value='$campos[porcentaje_iva_retenido]'></TD><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montoRet$i' id='montoRet$i' value='$campos[montoRet]'></TD></tr>";
                    $i++;
                }
            }

            $retenciones2 = $conn->ObtenerFilasBySqlSelect("SELECT cpfd.cod_impuesto, li.descripcion, sum(cpfd.monto_base) as base, porcentaje_retenido, sum(cpfd.monto_retenido) as montoRet, li.cod_tipo_impuesto FROM cxp_factura_detalle cpfd JOIN lista_impuestos li ON ( li.cod_impuesto = cpfd.cod_impuesto ) WHERE id_factura_fk in ($codFacs) group by cpfd.cod_impuesto");
            foreach ($retenciones2 as $key => $campos) {
                $reg.="<tr><TD><input type='hidden' name='codImp$i' id='codImp$i' value='$campos[cod_impuesto]'><input type='hidden' name='exento$i' id='exento$i' value='$campos[exento]'><input type='hidden' name='tipoImp$i' id='tipoImp$i' value='$campos[cod_tipo_impuesto]'>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='base$i' id='base$i' value='$campos[base]'></TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='porcen$i' id='porcen$i' value='$campos[porcentaje_retenido]'></TD><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montoRet$i' id='montoRet$i' value='$campos[montoRet]'></TD></tr>";
                $i++;
            }
            $reg.="*l*l*l*" . $i;
            echo $reg;
            break;

// 	case 'retencionesFactura':
//
// 		$codFacs=$_GET["facs"];
// 		$retenciones2=$conn->ObtenerFilasBySqlSelect("SELECT cpfd.cod_impuesto, li.descripcion, sum(cpfd.monto_base) as base, porcentaje_retenido, sum(cpfd.monto_retenido) as montoRet, li.cod_tipo_impuesto FROM cxp_factura_detalle cpfd JOIN lista_impuestos li ON ( li.cod_impuesto = cpfd.cod_impuesto ) WHERE id_factura_fk in ($codFacs) group by cpfd.cod_impuesto");
// 		foreach($retenciones2 as $key => $campos)
// 		{
// 			$reg.="<tr><TD><input type='hidden' name='codImp$i' id='codImp$i' value='$campos[cod_impuesto]'><input type='hidden' name='exento$i' id='exento$i' value='$campos[exento]'><input type='hidden' name='tipoImp$i' id='tipoImp$i' value='$campos[cod_tipo_impuesto]'>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='base$i' id='base$i' value='$campos[base]'></TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='porcen$i' id='porcen$i' value='$campos[porcentaje_retenido]'></TD><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montoRet$i' id='montoRet$i' value='$campos[montoRet]'></TD></tr>";
// 			$i++;
// 		}
// 		$reg.="*l*l*l*".$i;
// 		echo $reg;
// 	break;

        case 'anticipos':

            $edoCta = $_GET["edoCta"];
            $retenciones2 = $conn->ObtenerFilasBySqlSelect("SELECT * FROM cxp_edocuenta_detalle WHERE cod_edocuenta=$edoCta AND tipo='d' and cod_edocuenta_detalle not in (select cxp_edocuenta_detalle_fk from cxp_factura_pago) and marca in ('P','X')");
            $reg = '';
            $i = 0;
            foreach ($retenciones2 as $key => $campos) {
                $reg.="<tr><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='numero$i' id='numero$i' value='$campos[numero]'></TD><TD>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='monto$i' id='monto$i' value='$campos[monto]'></TD><TD><input name='optAnticipo{$i}' id='optAnticipo{$i}' type='checkbox' onchange='javascript:totalAnticipos();' value='{$i}'></TD></tr>";
                $i++;
            }
            $reg.="*l*l*l*" . $i;
            echo $reg;
            break;

        case 'cambiarClave';
            $clave = $_GET["clave1"];
            $clave2 = $_GET["claveOLD"];

            $usuario = $login->getIdUsuario();
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM usuarios WHERE cod_usuario = '" . $login->getIdUsuario() . "' and
		 clave='" . $_GET["claveOLD"] . "'");

//echo "SELECT * FROM usuarios WHERE cod_usuario = '".$login->getIdUsuario()."' and
// clave='".$_GET["claveOLD"]."'";
//count($campos);
            if (count($campos) == 0) {
                echo "1";
            } else {
                /* echo "update usuarios set
                  `clave` = '".$_GET["clave1"]."'
                  WHERE cod_usuario = ".$login->getIdUsuario();
                 */
                $sql = "UPDATE usuarios SET `clave` = '" . $_GET["clave1"] . "' WHERE cod_usuario ='{$usuario}'";
                $conn->Execute2($sql);
//echo "-1";
            }
            break;
        case 'anularFactura';
            $idFac = $_GET["idFac"];
            $sql = "UPDATE cxp_factura SET cod_estatus = 2 WHERE id_factura = {$idFac};";
            $conn->Execute2($sql);
            break;
        case "eliminar_ordenCXP":
            $instruccion = "UPDATE cxp_edocuenta SET marca='A', fecha_anulacion='{$_GET["fechaOrden"]}', observacion_anulado='{$_GET ["motivoAnulacionOrden"]}' WHERE cod_edocuenta = '{$_GET["cod"]}'";
            $conn->Execute2($instruccion);
            /*
             * Modificado por: Charli Vivenes
             * Objetivo: incluir la eliminacion de las entradas en el inventario cuando se cancela la compra.
             * Desccripcion: se creo la tabla 'compra_kardex' para mantener la relacion entre el kardex y la compra
             *
             */
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT id_kardex FROM compra_kardex WHERE id_compra = {$_GET["cod"]};");
            $campos2 = $conn->ObtenerFilasBySqlSelect("SELECT estado FROM kardex_almacen WHERE id_transaccion = {$campos[0]["id_kardex"]};");
            $campos3 = $conn->ObtenerFilasBySqlSelect("SELECT * FROM kardex_almacen_detalle WHERE id_transaccion = {$campos[0]["id_kardex"]};");
            $instruccion = "INSERT INTO kardex_almacen (tipo_movimiento_almacen, autorizado_por, observacion, fecha, usuario_creacion, fecha_creacion, estado, fecha_ejecucion)
                VALUES (8, 'Nadie', 'Salida por Devolucion Compra', '{$_GET["fechaOrden"]}', '{$_SESSION["usuario"]}', CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP);";
            $conn->ExecuteTrans($instruccion);
            $id_kardex_almacen = $conn->getInsertID();
            /*
             * En este punto decidi registrar el detalle de la devolución.
             * Por ello está comentada la condición que fue relegada al interior del foreach.
             */
#if ($campos2[0]["estado"] == "Entregado") {
            foreach ($campos3 as $key => $kardex_almacen_detalle) {
                $conn->ExecuteTrans("INSERT INTO kardex_almacen_detalle (id_transaccion_detalle, id_transaccion, id_almacen_entrada, id_almacen_salida, id_item, cantidad)
                    VALUES (NULL, '{$id_kardex_almacen}', '0', '{$kardex_almacen_detalle["id_almacen_entrada"]}', '{$kardex_almacen_detalle["id_item"]}', '{$kardex_almacen_detalle["cantidad"]}');");
                if ($campos2[0]["estado"] == "Entregado") {
                    $conn->ExecuteTrans("UPDATE item_existencia_almacen SET cantidad = cantidad - '{$kardex_almacen_detalle["cantidad"]}'
                    WHERE id_item  = '{$kardex_almacen_detalle["id_item"]}' AND cod_almacen = '{$kardex_almacen_detalle["id_almacen_entrada"]}';");
                }
            }
#}
            if ($campos2[0]["estado"] == "Pendiente") {
                $conn->ExecuteTrans("UPDATE kardex_almacen SET estado = 'Cancelado' WHERE id_transaccion = {$campos[0]["id_kardex"]};");
            }
            break;
        case 'movimiento':
            $cliente = $_GET["cliente"];
//$movimiento=$conn->ObtenerFilasBySqlSelect("SELECT * FROM movimientos_bancarios  WHERE id_cliente=$cliente AND monto<>monto_aplicado");
            $movimiento = $conn->ObtenerFilasBySqlSelect("SELECT cod_movimiento_ban, numero_movimiento, concepto, (monto-(ifnull(monto_aplicado,0.00))) as monto, fecha_movimiento FROM movimientos_bancarios  WHERE id_cliente=$cliente and estatus IS NULL");
            $reg = '';
            $i = 0;
            if ($movimiento) {
                foreach ($movimiento as $key => $campos) {
                    $reg.="<tr><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='numerom$i' id='numerom$i' value='$campos[numero_movimiento]'><input type='hidden' name='codmov$i' id='codmov$i' value='$campos[cod_movimiento_ban]'></TD><TD>$campos[concepto]</TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='fechamov$i' id='fechamov$i' value='" . fecha($campos[fecha_movimiento]) . "'></TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montosss$i' id='montosss$i' value='$campos[monto]'></TD><TD><input name='optMov{$i}' id='optMov{$i}' type='checkbox' onchange='javascript:totalPagos();' value='{$i}'></TD></tr>";
                    $i++;
                }
            }
            $reg.="*l*l*l*" . $i;
            echo $reg;
            break;
				case "filtroItemByRCCB":
            /**
             * Procedimiento de busqueda de productos/servicios
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      lviera@armadillotec.com
             *      levieraf@gmail.com
             *
             */
            $tipo_item = (isset($_POST["cmb_tipo_item"])) ? $_POST["cmb_tipo_item"] : 1;

            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {
                //filtro para productos
                if ($tipo_item == 1) {
                    $codigo = (string)((isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "");
                    $andWHERE = "UPPER(i.referencia) = UPPER('".$codigo."') or UPPER(i.cod_item) like UPPER('%".$codigo."%') or UPPER(i.codigo_barras) like UPPER('%".$codigo."%')";
                }

                $sql = "SELECT i.* FROM item i WHERE i.cod_item_forma = " . $tipo_item . " and (" . $andWHERE . ") ";

                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
  
                $sql = "SELECT i.* FROM item i WHERE i.cod_item_forma = " . $tipo_item . " and " . $andWHERE . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            } else {
                $sql = "SELECT i.* FROM item i WHERE i.cod_item_forma = " . $tipo_item;
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT i.* FROM item i WHERE i.cod_item_forma = " . $tipo_item . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            }
            
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes1),
                "data" => $campos_comunes
            ));

            break;
            case "cargarRegion":
                //cargar la region 
                    $idEstado = $_POST["idEstado"];
                    
                     $campos = $conn->ObtenerFilasBySqlSelect("SELECT a.id_region,b.descripcion FROM estado_region a, region b where a.id_estado=".$idEstado." and a.id_region=b.id limit 1;");
                     echo $campos[0]["descripcion"];
            break;
            case "cargarSubrubro":
              // cargar el select dependiente de subrubro
                 $tipoSql = $_POST["tipoSql"];
                 switch ($tipoSql) {
                    case '0':
                       $id_rubro = $_POST["idCarga"];
                        $campos = $conn->ObtenerFilasBySqlSelect("select * from grupo where id_rubro = ".$id_rubro);
                        foreach ($campos as $filas){
                        ?>
                        <option value= "<?php echo $filas['cod_grupo']; ?>"><?php echo $filas['descripcion']; ?> </option>

                        <?php }
                    break;
                    case '1':
                        $id_rubro = $_POST["idCarga"];
                        $id=$_POST["id"];
                        $campo = $conn->ObtenerFilasBySqlSelect("select * from item where id_item = ".$id);
                        $campos = $conn->ObtenerFilasBySqlSelect("select * from grupo where id_rubro = ".$id_rubro);
                       
                        foreach ($campos as $filas){
                              if($campo[0]["cod_grupo"]==$filas["cod_grupo"]){
                               ?>
                                 <option value= "<?php echo $filas['cod_grupo']; ?>" selected ><?php echo $filas['descripcion'];  ?> </option>

                            <?php } else{
                            ?>
                                <option value= "<?php echo $filas['cod_grupo']; ?>"><?php echo $filas['descripcion']; ?> </option>

                            <?php }
                        } 

                    break;
                  }
            break;
        case "filtroItem":
        
        		
             $comunes = new Comunes();
            /**
             * Procedimiento de busqueda de productos/servicios
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      lviera@armadillotec.com
             *      levieraf@gmail.com
             *
             */
            $tipo_item = (isset($_POST["cmb_tipo_item"])) ? $_POST["cmb_tipo_item"] : 1;
			if(isset($_POST["codigoProducto"]))
			{
			    $campos = $conn->ObtenerFilasBySqlSelect("SELECT cod_item_forma FROM item  WHERE id_item = '" . $_POST["codigoProducto"]. "'");
                if($campos!=null && $campos[0]['cod_item_forma']!=null)
                {
                    $tipo_item=$campos[0]['cod_item_forma'];
                }
			}
            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {
                //filtro para productos
                if ($tipo_item == 1) {
                    $codigo = (isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "";
                    $codigo_barras = (isset($_POST["codigoBarrasProducto"])) ? $_POST["codigoBarrasProducto"] : "";
                    $descripcion = (!isset($_POST["descripcionProducto"])) ? "" : $_POST["descripcionProducto"];
                    $referencia = (!isset($_POST["referencia"])) ? "" : $_POST["referencia"];

                    $andWHERE = " and ";
                    if ($codigo != "") {
                        $andWHERE .= " ( cod_item = '" . $codigo . "' or id_item  = '".$codigo."') ";
                        $entrada_codigo=true;
                    }

################################################################################
                    if ($codigo_barras != "") {
                        if ($codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }

                        $andWHERE .= " upper(codigo_barras) like upper('%" . $codigo_barras . "%')";
                    }

                    if ($referencia != "") {
                        if ($codigo_barras != "" || $codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(referencia) like upper('%" . $referencia . "%')";
                    }

################################################################################
                    if ($descripcion != "") {
                        if ($codigo_barras != "" || $referencia != "" || $codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(descripcion1) like upper('%" . $descripcion . "%')";
                    }
                    if ($codigo == "" && $descripcion == "" && $codigo_barras == "" && $referencia == "") {
                        $andWHERE = "";
                    }
                	//echo "HOLA".$andWHERE;
                	//exit;
                }

                //filtro para productos
                if ($tipo_item == 2) {
                    $codigo = (isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "";
                    $descripcion = (!isset($_POST["descripcionProducto"])) ? "" : $_POST["descripcionProducto"];

                    $andWHERE = " and ";
                    if ($codigo != "") {
                        $andWHERE .= " (upper(cod_item) = upper('" . $codigo . "') or id_item  = '".$codigo."') ";
                    }
                    if ($descripcion != "") {
                        if ($codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(descripcion1) like upper('%" . $descripcion . "%')";
                    }
                    if ($codigo == "" && $descripcion == "") {
                        $andWHERE = "";
                    }
                }
				//echo $sql; exit();
                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item . " " . $andWHERE . "";
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
					
                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item . " " . $andWHERE . " limit $start,$limit";
                //echo $sql; exit();
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            } else {
                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item;
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item . " limit $start,$limit";
                $campos_comunes = $comunes->ObtenerFilasBySqlSelect($sql);
            }
            	
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes1),
                "data" => $campos_comunes
            ));

            break;
        case "agregar_factura":
            /**
             * Procedimiento de registro de facturas sin generacion de inventario
             *
             * Realizado por:
             * Charli J. Vivenes Rengel
             *
             * Correo:
             *      cvivenes@asys.com.ve
             *      cjvrinf@gmail.com
             *
             */
#$compra = new Compra();
            $correlativos = new Correlativos();

#$compra->BeginTrans();
            $nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 1, "si");

            $sql = "INSERT INTO `compra` (
              `id_compra`, `cod_compra`, `id_proveedor`, `cod_vendedor`,
              `fechacompra`, `montoItemscompra`, `ivaTotalcompra`, `TotalTotalcompra`, `monto_excento`,
              `cantidad_items`, `cod_estatus`, `fecha_creacion`, `usuario_creacion`,
              `responsable`, `centrocosto`, `num_factura_compra`, `num_cont_factura`)
              VALUES (
              NULL , '" . $nro_compra . "', '" . $_GET["id_proveedor"] . "', '',
              '" . $_GET["fecha_emision"] . "', '" . $_GET["subtotal_factura"] . "', '" . $_GET["iva_factura"] . "', '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "', '" . $_GET["exento_factura"] . "',
              '0', '1', CURRENT_TIMESTAMP , '" . $_GET["usuario"] . "',
              '" . $_GET["responsable"] . "', '', '" . $_GET["num_factura"] . "', '" . $_GET["num_control"] . "');";

#$compra->ExecuteTrans($sql);
            $conn->ExecuteTrans($sql);

            $sql_cxp = "INSERT INTO cxp_edocuenta (
		`cod_edocuenta`, `id_proveedor`, `documento`,
		`numero`, `monto`, `fecha_emision`,
		`observacion`, `vencimiento_fecha`, `vencimiento_persona_contacto`,
		`vencimiento_telefono`, `vencimiento_descripcion`,
		`usuario_creacion`, `fecha_creacion`, `marca`)
                VALUES (
		NULL, '" . $_GET["id_proveedor"] . "', 'FACxCOM',
		'" . $nro_compra . "', '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "', '" . $_GET["fecha_emision"] . "',
		'Compra " . $nro_compra . "', '" . $_GET["fecha_vence"] . "', '',
		'', '' , '" . $_GET["usuario"] . "', '" . $_GET["fecha_emision"] . "', 'P');";

#$compra->ExecuteTrans($sql_cxp);
            $conn->ExecuteTrans($sql_cxp);
            $id_cxp = $conn->getInsertID();

            $SQL_cxp_DET = "INSERT INTO cxp_edocuenta_detalle (
		`cod_edocuenta_detalle`, `cod_edocuenta`, `documento`,
		`numero`, `descripcion`, `tipo`,
		`monto`, `usuario_creacion`, `fecha_creacion`,
		`fecha_emision_edodet`, `marca`)
                VALUES (
		NULL ,'" . $id_cxp . "','PAGOxCOM',
                '" . $nro_compra . "R','compra " . $nro_compra . "','c',
                '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "','" . $_GET["usuario"] . "', CURRENT_TIMESTAMP,
		'" . $_GET["fecha_emision"] . "','P');";
# Se inserta el detalle de la cxp en este caso el asiento del DEBITO.
#$compra->ExecuteTrans($SQL_cxp_DET);
            $conn->ExecuteTrans($SQL_cxp_DET);
            $nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 1, "no");
            $conn->ExecuteTrans("UPDATE correlativos SET contador = '" . $nro_compra . "' WHERE campo LIKE 'cod_compra'");

            $cod_impuesto = $alicuota = $monto_retenido = 0;
            if ($_GET["retencion_iva"]) {
                $cod_impuesto = $_GET["cod_impuesto"];
                $alicuota = $_GET["alicuota"];
                $monto_retenido = $_GET["iva_factura"] * $alicuota / 100;
            }
#$sql_tipo_impuesto;
//responsable='+responsable+'&num_factura='++'&='+num_control+'&='+exento_factura+'&subtotal_factura='+subtotal_factura+'&='+base_factura+'&iva_factura='+iva_factura+'&='+fecha_emision+'&fecha_vence='+fecha_vence+'&id_proveedor='+id_proveedor+'&usuario='+usuario,
            $sql_cxp_factura = "INSERT INTO cxp_factura (
                    id_factura, cod_factura, cod_cont_factura, id_cxp_edocta, fecha_factura, fecha_recepcion,
                    monto_base, monto_exento, anticipo, monto_total_con_iva, monto_total_sin_iva,
                    cod_impuesto, porcentaje_iva_mayor, monto_iva, porcentaje_iva_retenido, monto_retenido,
                    total_a_pagar, cod_estatus, fecha_pago, fecha_creacion, usuario_creacion,
                    tipo, factura_afectada, libro_compras, cod_correlativo_iva, cod_correlativo_islr)
                VALUES (
                    NULL, '" . $_GET["num_factura"] . "', '" . $_GET["num_control"] . "', '" . $id_cxp . "', '" . $_GET["fecha_emision"] . "', '" . $_GET["fecha_emision"] . "',
                    '" . $_GET["base_factura"] . "', '" . $_GET["exento_factura"] . "', '0', '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "', '" . $_GET["subtotal_factura"] . "',
                    '" . $cod_impuesto . "', 12, '" . $_GET["iva_factura"] . "', " . $alicuota . ", " . $monto_retenido . ",
                    '" . ($_GET["subtotal_factura"] + $_GET["iva_factura"] - $monto_retenido) . "', '1', '', CURRENT_TIMESTAMP, '" . $_GET["usuario"] . "',
                    'FAC', '" . $_GET["num_factura"] . "', '{$_GET["libro_compras"]}', '', '')";
            $conn->ExecuteTrans($sql_cxp_factura);

            $id_cxp_factura = $conn->getInsertID();
            $sql_cxp_factura_det = "INSERT INTO cxp_factura_detalle (
                    id_factura, id_factura_fk, monto_base, porcentaje_retenido, cod_impuesto, monto_retenido, id_item)
                VALUES (
                    NULL, '" . $id_cxp_factura . "', '" . $_GET["base_factura"] . "', '" . $_GET["alicuota"] . "', '', '')";
            $conn->ExecuteTrans($sql_cxp_factura_det);
            break;
            
            
        case "cambioPrecio":
            
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT *
							from item
							WHERE id_item between $_GET[itemini] and $_GET[itemfin]");
            if (count($campos) == 0) {
                exit;
            }
            echo '
            		<table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Cod</th>
                        <th style="border-bottom: 1px solid #949494;width:200px;">Descripcion</th>
                        <th style="border-bottom: 1px solid #949494;width:200px;">Precio1&nbsp;<input type="checkbox" name="precio1" id="precio1" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio2&nbsp;<input type="checkbox" name="precio2" id="precio2" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio3&nbsp;<input type="checkbox" name="precio3" id="precio3" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio4&nbsp;<input type="checkbox" name="precio4" id="precio4" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio5&nbsp;<input type="checkbox" name="precio5" id="precio5" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio6&nbsp;<input type="checkbox" name="precio6" id="precio6" value="1"></th>

                    </thead>
                    <tbody>';


            $acuDebitos = 0;
            $acuCreditos = 0;
            foreach ($campos as $key => $item) {
                echo '
                        <tr>
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">' . $item["cod_item"] . '
									<input type="hidden" id="id_item[]" name="id_item[]" value='.$item["id_item"].'>
                            </td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:200px;">' . $item["descripcion1"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva1" name="coniva1[]" value=' . $item["coniva1"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva2" name="coniva2[]" value=' . $item["coniva2"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva3" name="coniva3[]" value=' . $item["coniva3"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva4" name="coniva4[]" value=' . $item["coniva4"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva5" name="coniva5[]" value=' . $item["coniva5"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva6" name="coniva6[]" value=' . $item["coniva6"] . '></td>
                            
                        </tr>';
            }
            echo '</tbody></table>';
            break;
            case "ValidarSerial":
                //ajax para validar los seriales
            $serial=$_POST["serial"];
            $num=$_POST["num"];
            $idItem=$_POST["idItem"];
            if($serial==""){
                $campo[0]["serial"]="";
            }else{
               $campo = $conn->ObtenerFilasBySqlSelect("select * from item_serial where id_producto=".$idItem." and estado=1 and serial ='".$serial."'");
            }
           
            if($campo[0]["serial"]==""){
                echo "<div><img src='../../libs/imagenes/ico_est6.gif' width='16' height='16' /></div>";
                echo "<input class='oculto' type='hidden' name=h".$num." id=h".$num." value=1 />";
            }else{
                echo "<div><img src='../../libs/imagenes/ico_est2.gif' width='16' height='16' /></div>";
                echo "<input class='oculto' type='hidden' name=h".$num." id=h".$num." value=0 />";
            }

           


            break;
            
            case "actualizar_precio_producto":
            $pos=POS;
            $prod=$_GET[prod];
            $precio=$_GET[precio];
            $trans=$_GET[trans];
            $sql="UPDATE item SET precio1=".$precio.", coniva1=".$precio.", precio2=".$precio.", coniva2=".$precio.", precio3=".$precio.", coniva3=".$precio." WHERE codigo_barras='".$prod."'";
            $conn->Execute2($sql);
            $sql="UPDATE $pos.products SET PRICEBUY=".$precio.", PRICESELL=".$precio." WHERE CODE='".$prod."'";
            $conn->Execute2($sql);
            $sql_estatus="UPDATE sincronizacion_productos_detalle SET estatus=0, usuario_ejecucion='".$login->getUsuario()."', fecha_ejecucion=CURRENT_TIMESTAMP WHERE codigo_barra='".$prod."' and id_sincro=".$trans."";
            $conn->Execute2($sql_estatus);
                                

            break;

            case "actualizar_precio_producto2":
            $comunes = new Comunes();
            $pos=POS;
            $prod=$_GET[prod];
            $precio=$_GET[precio];
            $trans=$_GET[trans];
            //$comunes=$_GET[id_cone];
            $sql="UPDATE item SET precio1=".$precio.", coniva1=".$precio.", precio2=".$precio.", coniva2=".$precio.", precio3=".$precio.", coniva3=".$precio." WHERE codigo_barras='".$prod."'";
            
            $comunes->Execute2($sql);
            $sql="UPDATE $pos.products SET PRICEBUY=".$precio.", PRICESELL=".$precio." WHERE CODE='".$prod."'";
            $comunes->Execute2($sql);
            $sql_estatus="UPDATE sincronizacion_productos_detalle SET estatus=0, usuario_ejecucion='".$login->getUsuario()."', fecha_ejecucion=CURRENT_TIMESTAMP WHERE codigo_barra='".$prod."' and id_sincro=".$trans."";
            $comunes->Execute2($sql_estatus);
            break;

            case "BuscarSerial":
                $idItem=$_POST["idItem"];               
                $campo = $conn->ObtenerFilasBySqlSelect("select * from item_serial where  estado=1 and id_producto=".$idItem);
                echo "<option value='0'>Seleccione un serial</option>";                
                foreach ($campo as $key ) {
                  echo "<option value='".$key["serial"]."'>".$key["serial"]."</option>";
                }

            break;
            
            case "cantidadSeriales":
                $id=$_GET[cod];
	            $cant=$_GET[cant];	            
	            $campos = $conn->ObtenerFilasBySqlSelect("SELECT *	from item WHERE id_item ='$id'");
	            if($campos[0][seriales]==1)
	            {
	            	
		            echo '<form id="seriales" name="seriales"><table>';
		            echo '<tr>
		                   <td colspan="2"><input type="hidden" name="item" id="item" value="'.$id.'"></td>
		                   </tr>';
                    echo "<div style='margin: 25px 0px 0px 40px;'><input type='text' id='iSerialGen' style='float:left' class='form-text'></div>";     
                    echo "<div id='gSerial' style='background:#abcee4;px;padding:5px 5px;margin:3px 0px 10px 10px;float:left;color:white;cursor:pointer'>Generar</div>" ;      
		            echo "<div style='clear:both'></div>";
                    for($i=0;$i<$cant;$i++)
		            {
		                   echo '<tr>
		                   <td>Serial '.($i+1).'</td>
		                   <td><input type="text" name="serial'.$i.'" id="serial'.$i.'" class="form-text serialSec "></td>
		                   </tr>';
		                   
		            }
		        		echo '</table></form>';
		        	}
		        	else
		        	{
		        		echo "-1";
		        	}
            break;
            
            case "agregarSeriales":
            
            	$form=array();
                    //echo $_GET["formulario"];
                    $form=json_decode($_GET["formulario"],true);
                    $ii=0;
                    foreach ($form as $i => $input)
                    {
                        if($input[name]=="item")
                            $item=$input[value];
                                            
                        $cad="serial".$ii;
                
                        if($input[name]==$cad) 
                        {
                            $ii++;
                            //$tallas[]=$input[value];
                            $ser=$input[value];
                            
                            $conn->ExecuteTrans("insert into item_serial_temp (id_producto, serial, estado, usuario_creacion,idsessionactual) values ('$item', '$ser', '1', '".$login->getUsuario()."', '".$login->getIdSessionActual()."')");
                        }
                    }      
                 /*$id=$_GET[cod];
                $cant=$_GET[cant];
                
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *  from item WHERE id_item ='$id'");
                if($campos[0][seriales]==1)
                {
                    
                    echo '<form id="seriales" name="seriales"><table>';
                    for($i=0;$i<=$cant;$i++)
                    {
                           echo '<tr>
                           <td>Serial '.$i.'</td>
                           <td><input type="text" name="serial[]" id="serial[]" class="form-text"></td>
                           </tr>';
                           
                    }
                        echo '</table></form>';
                    }
                    else
                    {
                        echo "-1";
                    }*/


            break;
            case"cargarAlmacen":
                  $comunes = new Comunes();
                  $id_localidad=$_POST["idLocalidad"];
                  $campo = $comunes->ObtenerFilasBySqlSelect("select * from almacen where id_localidad=".$id_localidad);
                  $filas=$comunes->getFilas();
                  $mensaje=$comunes->Notificacion();
                  $cabecera=array("Codigo","Descripción");
                  // boton agregar almacen
                  echo "<div style='margin-bottom: 10px;'>";
                     echo "<table style='cursor: pointer; 'class='btn_bg' onclick=\"javascript:window.location='?opt_menu=3&amp;opt_seccion=55&amp;opt_subseccion=add&amp;idLocalidad=".$id_localidad."&amp;loc=1';\">";
                         echo "<tr>";
                            echo " <td><img src='../../../includes/imagenes/bt_left.gif' style='border-width: 0px; width: 4px; height: 21px;' /></td>
                                   <td><img src='../../../includes/imagenes/add.gif' width='16' height='16' /></td>";
                            echo " <td style='padding: 0px 4px;'>Agregar</td>
                                   <td><img src='../../../includes/imagenes/bt_right.gif' style='border-width: 0px; width: 4px; height: 21px;' /></td>" ;      
                        echo "</tr>";
                     echo "</table>";
                echo "</div>";
                  echo "</div style='clear_both'>";
                   //fin del boton agregar almacen                 
                  echo " <table class='seleccionLista'>";
                    echo "<tr class='tb-head'>";
                        foreach ( $cabecera as $key => $value) {
                           echo" <td><b>".$value."</b></td>";
                        }
                       echo"<td colspan='3' style='text-align:center;'><b>Opciones</b></td>" ;
                    echo "</tr >";
                    if ($filas==0) {
                      echo " <tr><td colspan='3'>".$mensaje."</td></tr>";
                    }else{
                        foreach ($campo as $key => $value) {
                        echo "<tr style='background:#cacacf;'>";
                           echo "<td >".$value["cod_almacen"]."</td>";
                           echo "<td >".$value["descripcion"]."</td>";
                           echo "<td style='cursor: pointer; width: 30px; text-align:center'>
                                <img class='editar' onclick=\"javascript: window.location.href='?opt_menu=3&amp;opt_seccion=55&amp;opt_subseccion=edit&amp;cod=".$value["cod_almacen"]."&amp;idLocalidad=".$id_localidad."&amp;loc=1'\" title='Editar' src='../../../includes/imagenes/edit.gif'/>
                                 </td>";
                           echo "<td style='cursor: pointer; width: 30px; text-align:center'>
                                <img class='eliminar' onclick=\"javascript: window.location.href='?opt_menu=3&amp;opt_seccion=55&amp;opt_subseccion=delete&amp;cod=".$value["cod_almacen"]."&amp;idLocalidad=".$id_localidad."&amp;loc=1'\" title='Eliminar' src='../../../includes/imagenes/delete.gif'/> 
                                </td>";   
                            echo "<td style='cursor: pointer; width: 30px; text-align:center'>
                                <img class='eliminar' onclick=\"javascript: window.location.href='?opt_menu=3&amp;opt_seccion=55&amp;opt_subseccion=ubicacion&amp;cod=".$value["cod_almacen"]."&amp;idLocalidad=".$id_localidad."&amp;loc=1'\" title='agregar ubicacion' src='../../../includes/imagenes/add.gif'/> 
                            </td>";       
                        echo "</tr>";
                        }
                    }
                  echo " </table>";
            break;
            case "cargaUbicacion":
               $almacen=$_POST["idAlmacen"];
               $cliente=$_POST["cliente"];
               if(isset($_POST['cliente']) && $_POST['cliente']!=null)
                {
                    $sql="select a.id, a.descripcion, a.orden, b.lote, b.cantidad, b.peso,  (select etiqueta from kardex_almacen_detalle where id_ubi_entrada=a.id order by id_transaccion_detalle DESC LIMIT 1) as etiqueta from ubicacion as a inner join item_existencia_almacen as b on a.id=b.id_ubicacion  where a.id_almacen='".$almacen."' and b.id_proveedor='".$cliente."'";
                    $campos = $conn->ObtenerFilasBySqlSelect($sql);
                }
                else
                {
                    $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id_almacen='".$almacen."' and ocupado = 1 ");
                }
               
                if (count($campos) == 0) 
                {
                    echo "[{band:'-1'}]";
                } 
                else 
                {
                    echo json_encode($campos);
                }
            break;
            case "cargaUbicacionCliente":
                       $almacen=$_POST["idAlmacen"];
                       $cliente=$_POST["cliente"];
                       $sql="SELECT a.* FROM ubicacion as a inner join item_existencia_almacen as b on a.id=b.id_ubicacion 
                        WHERE a.id_almacen='".$almacen."' and a.ocupado = 1 and b.id_proveedor='".$cliente."' ";
                        //echo $sql; exit();
                       $campos = $conn->ObtenerFilasBySqlSelect($sql);
                        if (count($campos) == 0) {
                            echo "[{band:'-1'}]";
                        } else {
                            echo json_encode($campos);
                        }
            break;
            case "cargaUbicacionTraslado":
                       $almacen=$_POST["idAlmacen"];
                       $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id_almacen='".$almacen."' and ocupado = 0 ");
                        if (count($campos) == 0) 
                        {
                            echo "[{band:'-1'}]";
                        } else {
                            echo json_encode($campos);
                        }
            break;

            case "cargaUbicacion2":
                       $almacen=$_POST["idAlmacen"];
                       $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id_almacen='".$almacen."' and descripcion<>'PISO DE VENTA'");
                        if (count($campos) == 0) {
                            echo "[{band:'-1'}]";
                        } else {
                            echo json_encode($campos);
                        }
            break;
             case "VerificarSerial":
                     $item= $_GET["item"];
                     $cant=$_GET["cant"];   
                     $arr2=array();                 
                       for ($i=0; $i < $cant; $i++) { 
                        
                            $serial=$_GET["serial".$i];                         
                            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item_serial WHERE id_producto='".$item."' and serial='".$serial."'");
                            $campos1 = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item_serial_temp WHERE id_producto='".$item."' and serial='".$serial."'");
                         
                            if(count($campos)!=0 || count($campos1)!=0){
                               
                                $arr2[]=array("idSerial"=>"serial".$i);
                            }

                       }      
                       if(count($arr2)==0){
                              $arr2[]=array("idSerial"=>"0");
                       } 
                       
                               
                        header('Content-type: application/json; charset=utf-8');
                        // $arr = array(
                        //     array("idSerial"=>"serial0"),
                        //     array("idSerial"=>"serial1")
                        // );
                        // $arr=array();
                        // $arr[]=array("idSerial"=>"serial0");
                        // $arr[]=array("idSerial"=>"serial1");
                        // print_r($arr);
                        // print_r($arr2);
                      // echo json_encode($arr);
                        echo json_encode($arr2);
                      
                       
                       // $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id_almacen='".$almacen."'");
                       //  if (count($campos) == 0) {
                       //      echo "[{band:'-1'}]";
                       //  } else {
                       //      echo json_encode($campos);
                       //  }
            break;
             case "cargaProductoCodigo":
                       $codigoBarra=$_POST["codigoBarra"];
                     $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `codigo_barras`='".$codigoBarra."'");
                       if (count($campos) == 0) {
                            echo "[{band:'-1'}]";
                        } else {
                            echo json_encode($campos);
                        }
            break;
              case "estatusImpresora":
                    
                    $status=Tfhka::CheckFprinter1();                                                   
                    echo json_encode($status);
                       
            break;
            case "eliminarserial":
               $id_producto=$_POST["idproduc_serial"];
                // $sql="delete  item_serial_temp where id_producto='".$id_producto."' and usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."'";
                $conn->ExecuteTrans("delete from item_serial_temp where id_producto='".$id_producto."' and usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."'");
                // return  $sql;
            break;
             case "reporte_productoCentral":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $BD_central=DB_REPORTE_CENTRAL;
                    $sql=" SELECT v.CODE,v.REFERENCE,v.nombre_producto,SUM(v.UNITS) as UNITS,SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate FROM $BD_central.ventas v
                           INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                           INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                           WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'"; 
                    
                    if($punto!=0){
                        $sql.=" and v.codigo_siga='$punto'";
                    }      
                    if($estado!=0){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                    }      

                    $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    
                 
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                    if(count($reporte)>0){
                    ?>  
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">generar excel</div>
            
                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="14" align="center"><b><font color="white">REPORTE DE VENTAS POR PRODUCTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">REFERENCIA</font></b></td>
                            <td width="30%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="5%" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white" >PRECIO UND SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white" >PRECIO UND CON IVA</font></b></td>
                            <td width="5%" align="center"><b><font color="white" >IVA</font></b></td>                
                        </tr>

                    <?php 
                        foreach ($reporte as $lista ) {
                            $totalUni+=$lista["UNITS"];
                            $totalSinIva+=$lista["TOTALSINIVA"];
                            $totalConIva+=$lista["TOTALCONIVA"];
                            
                     ?>      
                        <tr>
                            <td width="10%"><?php echo $lista["CODE"] ?></td>
                            <td width="10%" align="center"><?php echo $lista["REFERENCE"] ?></td>
                            <td width="30%" align="center"><?php echo utf8_encode($lista["nombre_producto"])  ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["TOTALSINIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["TOTALCONIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["PRICESELL"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["PRECIOIVA"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php echo $lista["rate"] ?></td>                
                        </tr>  

                    <?php     
                        }
                    ?>  
                     <tr>
                            <td width="10%"></td>
                            <td width="10%" align="center"></td>
                            <td width="30%" align="center"><b>TOTALES</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalSinIva, 2, ',', '.');  ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalConIva, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>                
                    </tr>  
                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?> 
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {
                                      
                                //     },
                                //     success: function(data) {   
                                //         $("#contenido_reporte").empty();
  
                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax  
                                window.location.href = "imprimirReporteProducto.php";  
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            break;

            case "CambioPrecios1":
                    $comunes = new Comunes();
                    $mensaje=$comunes->Notificacion();
                    $id_producto=$_POST["id_producto"];                    
                   // $campo = $comunes->ObtenerFilasBySqlSelect("SELECT i.*, r.descripcion as region, t.descripcion as tipo FROM item_precio i , region r,tipo_precio_item t where i.id_producto =".$id_producto." and i.id_region=r.id and i.tipo_precio=t.id order by id_region,tipo_precio ;");
                    $campo=$comunes->ObtenerFilasBySqlSelect("select * from item where id_item='".$id_producto."'");
                    $pos=POS;
                    $campopos=$comunes->ObtenerFilasBySqlSelect("SELECT * FROM $pos.products WHERE id='".$campo[0]['itempos']."'");
                    
                    $filas=$comunes->getFilas();
                    $reg=0;
                    $cont=0;
                    // $cabecera=array("REGION","TIPO DE PRECIO","PRECIO");
                    $cabecera=array("ID","PRODUCTO","CODIGO DE BARRAS","PRECIO PYME", "PRECIO POS");
                    //echo "<tr><td>".$campopos[0]["ID"]."SELECT * FROM $pos.products WHERE id='".$campo[0]['itempos']."'</td></tr>";
                        echo "<tr class='tb-head' >";
                        foreach ($cabecera as $key => $value) {
                           echo" <td width='20%'><b>".$value."</b></td>";
                        }                      
                        echo "</tr >";
                         if ($filas==0) {
                            echo " <tr><td colspan='1' width='10%' VALIGN='MIDDLE' ALIGN='CENTER'>".$mensaje."</td></tr>";
                        }else{
                            foreach ($campo as $key => $value) {
                                if($reg!=$value["id_item"]){
                                    if($cont!=3 && $cont!=0 ){
                                        while ($cont<=3) {
                                             echo "<td width='10%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>";
                                             $cont++;
                                        }
                                       
                                    }
                                    $cont=1;
                                    echo "<tr style='background:#cacacf;'><td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>".$value["id_item"]."</td>";  
                                    $reg=$value["id_item"]; 
                                                                
                                }
                                    if($cont==1){
                                        if(!empty($value["descripcion1"])){
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>".$value["descripcion1"]."<input type=hidden readonly name='nombre_producto_c' id='nombre_producto_c' value='".$value["descripcion1"]."'/></td>";   
                                        }else{
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>";   
                                             $cont++;
                                        }
                                    }
                                    if($cont>=1){
                                        if($value["codigo_barras"]!=""){
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>".$value["codigo_barras"]."<input type=hidden readonly name='codigo_barras_c' id='codigo_barras_c' value='".$value["codigo_barras"]."'/></td>";   
                                        }else{
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>"; 
                                             $cont++;  
                                        }
                                    }
                                    if($cont>=1){
                                        if($value["coniva1"]!=""){
                                            echo "<td width='15%' VALIGN='MIDDLE' ALIGN='CENTER'>".$value["coniva1"]."</td>";   
                                        }else{
                                            echo "<td width='15%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>";  
                                            echo " </tr>";
                                        }
                                    }
                                    $cont++;
                                    if($cont>=1 && !empty($campopos[0]['PRICESELL'])){
                                        if($campopos[0]['PRICESELL']!=""){
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>".number_format($campopos[0]['PRICESELL'],2,".",",")."<input type=hidden readonly name='pricesell' id='pricesell' value='".number_format($campopos[0]['PRICESELL'],2,".",",")."'/></td>";   
                                        }else{
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>";  
                                            echo " </tr>";
                                        }
                                    
                                       
                                        
                                        
                                        }
                                    $cont++;
                                    
                                 

                            }    
                          
                        }
                    echo "</table>" ;   
                    

            break;
            case 'CambioPrecios2':
                $comunes = new Comunes();  
                $i=0;         
                $producto= $_POST["producto"];
                $region= $_POST["region"];
                $tipoPrecio= $_POST["tipoPrecio"];  
                $tipoPrecio=explode(",", $tipoPrecio);                      
                echo "<div>";
                echo "<form id='formulario'>";
                foreach ($tipoPrecio as $value) {
                    $campo = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item_precio , tipo_precio_item a where id_region=".$region." and id_producto=".$producto." and tipo_precio=".$tipoPrecio[$i]." and a.id=tipo_precio ;");
                    if($value==1)
                        $tipo="Red Comercial";
                     if($value==2)
                        $tipo="PAE";
                     if($value==3)
                        $tipo="Pdvalito";                        

                   echo "<div style='margin-left: 116px'><div style='float:left;width:100px'>".$tipo." </div><input class='form-text precioV' id='".$value."' type='text' value='".$campo[0]["precio"]."' onkeypress='if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;'><div style='clear:both'></div></div>";
                   echo "<input type='hidden' value='".$campo[0]["precio"]."' name='precioh".$value."' id='precioh".$value."' >" ;
                   echo "<input type='hidden' name='".$value."' value='".$value."'>";
                   $i++;
                  
                }
                echo "</div>";   
                echo "<input type='hidden' id='cantPrecio' value='".$i."'>";
                echo "<input type='hidden' id='producto' value='".$producto."'>";
                echo "<input type='hidden' id='region' value='".$region."'>"; 
                echo "<div id='agregarP' style='width: 90px;margin: 20px 0px 0px 186px;cursor:pointer ;padding: 10px; background: #bdd1dc;cursor:pointer'>Agregar precios</div>";          
               echo "</form>";
               

            break;
            case 'CambioPrecios3':
               $comunes = new Comunes();  
                $cantidad=$_POST["cantidad"];
                $producto=$_POST["producto"];
                $region=$_POST["region"];
                if(isset($_POST["1"])){
                    $campo = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item_precio where id_region=".$region." and id_producto=".$producto." and tipo_precio=1");
                    $filas=$comunes->getFilas();
                    if( $filas!=0){
                         echo   $sql = "UPDATE item_precio SET precio =".$_POST["precioh1"]."  WHERE id = {$campo[0]['id']};";
                            $comunes->Execute2($sql);
                    }else{
                             $instruccion = "INSERT INTO item_precio (id_region, id_producto, tipo_precio, precio)
                             VALUES ( '$region', '$producto', '1', ".$_POST["precioh1"].");";
                            $comunes->ExecuteTrans($instruccion); 
                    }
                }
                if(isset($_POST["2"])){
                    $campo = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item_precio where id_region=".$region." and id_producto=".$producto." and tipo_precio=2");
                    $filas=$comunes->getFilas();
                    if( $filas!=0){
                         echo   $sql = "UPDATE item_precio SET precio =".$_POST["precioh2"]."  WHERE id = {$campo[0]['id']};";
                            $comunes->Execute2($sql);
                    }else{
                             $instruccion = "INSERT INTO item_precio (id_region, id_producto, tipo_precio, precio)
                             VALUES ( '$region', '$producto', '2', ".$_POST["precioh2"].");";
                            $comunes->ExecuteTrans($instruccion); 
                    }
                }
                 if(isset($_POST["3"])){
                    $campo = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item_precio where id_region=".$region." and id_producto=".$producto." and tipo_precio=3");
                    $filas=$comunes->getFilas();
                    if( $filas!=0){
                         echo   $sql = "UPDATE item_precio SET precio =".$_POST["precioh3"]."  WHERE id = {$campo[0]['id']};";
                            $comunes->Execute2($sql);
                    }else{
                             $instruccion = "INSERT INTO item_precio (id_region, id_producto, tipo_precio, precio)
                             VALUES ( '$region', '$producto', '3', ".$_POST["precioh3"].");";
                            $comunes->ExecuteTrans($instruccion); 
                    }
                }


                  
              
            break;
            
            case 'cargarCantidadPOS_1':
            $pos=POS;
                
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE id_item = '".$_GET["v2"]."'");
            $campopos=$conn->ObtenerFilasBySqlSelect("SELECT * FROM $pos.stockcurrent WHERE PRODUCT='" . $campos[0]['itempos']."'");
           // echo "SELECT * FROM $pos.stockcurrent WHERE PRODUCT=" . $campos[0]['itempos'];
            //echo $campopos[0]["PRODUCT"];
            if (count($campopos) == 0) {
                echo "[{id:'-1'}]";
            } else { 
                echo json_encode($campopos);
            }
            break;
            
            case 'ver_ubicacion':
                           
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id = '".$_GET["ubicacion"]."'");
          
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else { 
                echo json_encode($campos);
            }
            break;
            
            
            
            
         

    }
}
function InsertarDatoArchivo($arreglo, $id_mov, $toma)
            {
                $conn = new ConexionComun();
                $codigo_barras=$arreglo[0];
                $cantidadpor=$arreglo[1];
                $cantidadsum=0;
                if(isset($arreglo[2]))
                {
                    $cantidadsum=$arreglo[2];
                }
                
                //seleccionamos los almacenes

                $almacenes=$conn->ObtenerFilasBySqlSelect("select id_ubicacion, id_almacen from tomas_fisicas where id=".$id_mov);
                //filtro para saber si es piso de venta, de ser piso de venta debe consultarse es la vista
                if($almacenes[0]['ubicacion']==2)
                {
                    $tabla_ubicacion="vw_item_pisoventa";
                }
                else
                {
                 $tabla_ubicacion="item_existencia_almacen";
                }
                
                //buscamos los datos de los productos en el almacen
                $producto_almacen=$conn->ObtenerFilasBySqlSelect("select a.descripcion1, a.codigo_barras, a.cantidad_bulto, b.cantidad from item as a, ".$tabla_ubicacion." as b where a.id_item=b.id_item and cod_almacen=".$almacenes[0]['id_almacen']." and id_ubicacion=".$almacenes[0]['id_ubicacion']." and a.codigo_barras='".$codigo_barras."'");

                $codigo_barras_item=$producto_almacen[0]['codigo_barras'];
                $cantidad_item=$producto_almacen[0]['cantidad'];
                //si el producto no existe se debe insertar
                if($producto_almacen[0]['codigo_barras']=="")
                {   
                    if($almacenes[0]['id_ubicacion']==2)
                    {   //buscar el codigo en el pos
                        $codigo_producto=$conn->ObtenerFilasBySqlSelect("select id from ".POS.".products where CODE='".$codigo_barras."'");
                        if(count($codigo_producto)!=0)
                        {
                            //buscamos el producto en el stockcurrent para ver si existe
                            $existe_stock=$conn->ObtenerFilasBySqlSelect("select PRODUCT from ".POS.".stockcurrent where PRODUCT='".$codigo_producto[0]['id']."'");
                            //si existe se le hace update esto se hace debido a que la vista no me trae todos los productos
                            if(count($existe_stock)!=0)
                            {
                                $sql_insert=$conn->Execute2("update ".POS.".stockcurrent set UNITS=0 where PRODUCT='".$existe_stock[0]['PRODUCT']."'");
                            }
                            else //caso contrario se inserta
                            {
                            $sql_insert=$conn->Execute2("insert into ".POS.".stockcurrent (LOCATION, PRODUCT, UNITS) values (0, '".$codigo_producto[0]['id']."', 0)");
                            }

                        }
                        else
                        {   //producto no existe
                            $conn->cerrar();
                            return 3;
                        }
                    }
                    else
                    {   
                        $id_producto=$conn->ObtenerFilasBySqlSelect("select id_item from item where codigo_barras='".$codigo_barras."'");
                        if(count($id_producto)!=0)
                        {
                            $sql_insert=$conn->Execute2("insert into ".$tabla_ubicacion." (cod_almacen, id_item, cantidad, id_ubicacion) values (".$almacenes[0]['id_almacen'].", ".$id_producto[0]['id_item'].", 0, ".$almacenes[0]['id_ubicacion'].")");
                        }
                        else
                        {
                            //codigo 3 producto no existe
                            $conn->cerrar();
                            return 3;
                        }
                    }
                
                }
                //actualizamos o insertamos las tomas de acuerdo al caso
                $producto_almacen=$conn->ObtenerFilasBySqlSelect("select a.descripcion1, a.codigo_barras, a.cantidad_bulto, b.cantidad from item as a, ".$tabla_ubicacion." as b where a.id_item=b.id_item and cod_almacen=".$almacenes[0]['id_almacen']." and id_ubicacion=".$almacenes[0]['id_ubicacion']." and a.codigo_barras='".$codigo_barras."'");
                if(!isset($producto_almacen[0]['cantidad']))
                {
                    $producto_almacen[0]['cantidad']=0;
                    $cantidad_bulto=$conn->ObtenerFilasBySqlSelect("select cantidad_bulto from item where codigo_barras='".$codigo_barras."'");

                }
                
               
                    $producto_almacen[0]['cantidad_bulto']=$cantidad_bulto[0]['cantidad_bulto'];
                //ver si existe en detalle
                $existencia=$conn->ObtenerFilasBySqlSelect("select id from tomas_fisicas_detalle where id_mov=".$id_mov." and cod_bar='".$codigo_barras."'");
                //echo "cantidapor=".$cantidadpor."cantidadsum=".$cantidadsum."presentacion=".$producto_almacen[0]['cantidad_bulto']."cantidad_almacen=".$producto_almacen[0]['cantidad']; exit();
                $cantidad_llenar=(($cantidadpor*$producto_almacen[0]['cantidad_bulto'])+$cantidadsum); 

                if($existencia[0]['id']!="")
                {
                $filtrosuma="";
                    if($toma=='tomadef')
                    { 
                        $filtrosuma=",mov_sugerido=".(($cantidad_llenar)-$producto_almacen[0]['cantidad']);
                    }
                    //echo "update tomas_fisicas_detalle set id_llenado=1, ".$toma."=".$cantidad_llenar.$filtrosuma." where id_mov=".$id_mov." and cod_bar='".$codigo_barras."'"; exit();
                $insert=$conn->Execute2("update tomas_fisicas_detalle set id_llenado=1, ".$toma."=".$cantidad_llenar.$filtrosuma." where id_mov=".$id_mov." and cod_bar='".$codigo_barras."'");
                }
                else
                {
                    //insertar el detalle de producto
                    $filtro="";
                    $filtro1="";
                    if($toma=='toma2')
                    {
                        $filtro=",toma1";
                        $filtro1=" ,0";
                    }
                    if($toma=='tomadef')
                    {
                        $filtro=",toma1,toma2";
                        $filtro1=" ,0,0";
                    }

                $insert=$conn->Execute2("insert into tomas_fisicas_detalle(id_mov,cod_bar,inv_sistema,".$toma.$filtro.", mov_sugerido, id_llenado) values (".$id_mov.", '".$codigo_barras."', ".$producto_almacen[0]['cantidad'].", ".$cantidad_llenar.$filtro1.",0, 1)");
                }
            $conn->cerrar();
            return 1;
            }//fin de la funcion
?>
