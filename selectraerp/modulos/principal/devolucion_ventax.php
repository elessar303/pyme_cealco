<?php

include("../../libs/php/clases/DevolucionFactura.php");
include("../../libs/php/clases/correlativos.php");
include("../../libs/php/clases/clientes.php");
include("../../libs/php/clases/factura.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/spooler/SpoolerConfDB.php");
include("txt.php");

$parametros = new ParametrosGenerales();
$factura_devolucion = new DevolucionFactura();
$correlativos = new Correlativos();
$clientes = new Clientes();
$factura = new Factura();
$almacen = new Almacen();
$obj_txt = new txt();

$consulta = "
SELECT * FROM factura f
INNER JOIN factura_detalle_formapago fdet_formapago ON fdet_formapago.id_factura = f.id_factura
INNER JOIN clientes c ON c.id_cliente = f.id_cliente
WHERE f.cod_factura = '{$_GET["codigo"]}'";
#echo $_GET["codigo"];exit;

$dataDevolucion = $factura_devolucion->ObtenerFilasBySqlSelect($consulta);

/* $consulta = "select * from factura_detalle fdet
  inner join item i on i.id_item = fdet.id_item
  where  fdet.id_factura = '" . $dataDevolucion[0]["id_factura"] . "'"; */
$consulta = "SELECT * FROM factura_detalle fdet
  INNER JOIN factura_detalle_formapago fdet_formapago ON fdet_formapago.id_factura = fdet.id_factura
  INNER JOIN item i ON i.id_item = fdet.id_item
  WHERE fdet.id_factura = '{$dataDevolucion[0]["id_factura"]}'";

$dataDetalleFactura = $factura_devolucion->ObtenerFilasBySqlSelect($consulta);

$smarty->assign("dataDetalleFactura", $dataDetalleFactura);

$parametros_generales = $parametros->ObtenerFilasBySqlSelect("SELECT tipo_facturacion, swterceroimp, impresora_serial, porcentaje_impuesto_principal, iva_a, iva_b, iva_c, nombre_impuesto_principal FROM parametros_generales;");

#$smarty->assign("cabecera",array("Seleccion","Codigo","Descripcion","Cantidad","Precio","Descuento","%","Total Sin IVA","I.V.A","Total con I.V.A"));
$smarty->assign("cabecera", array("C&oacute;digo", "Descripci&oacute;n", "Cantidad", "Precio", "Descuento", "%", "Total Neto", $parametros_generales[0]["nombre_impuesto_principal"], "Monto Total"));
$smarty->assign("dataDevolucion", $dataDevolucion);

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

$smarty->assign("name_form", "devolucion_venta");

if (isset($_POST["anularFactura"])) {
    # si el usuario hizo post
    $factura_devolucion->BeginTrans();
    $factura->BeginTrans();
    $cod_estatus = "3";

    # obtenemos el correlativo de la devolucion
    $nro_devolucion = $correlativos->getUltimoCorrelativo("cod_devolucion", 0, "si");
    $formateo_nro_devolucion = $nro_devolucion;

    $consulta_devolucion = "INSERT INTO factura_devolucion (`cod_devolucion`,`cod_factura`,`fecha_devolucion`)
            VALUES('{$nro_devolucion}', '{$_GET['codigo']}', CURRENT_TIMESTAMP);";
    $factura_devolucion->ExecuteTrans($consulta_devolucion);
    $id_facturaTrans = $factura_devolucion->getInsertID();
    $factura->ExecuteTrans("UPDATE factura SET cod_estatus = '3' WHERE cod_factura = {$_GET['codigo']}");

    # Se cambia el status del pedido que se anulará en consecuencia
    $hayPedido = $factura->ObtenerFilasBySqlSelect("SELECT * FROM pedido WHERE id_factura = {$dataDevolucion[0]["id_factura"]};");
    if ($hayPedido) {
        $factura->ExecuteTrans("UPDATE pedido SET cod_estatus = '3' WHERE id_factura = {$dataDevolucion[0]["id_factura"]}");
    }

    #echo "nro. devol: ".$nro_devolucion."/".$detalles." id cliente: ".$_POST["id_cliente"];exit;
    //Eliminar datos de factura_detalle
    #$SQLfactura_detalle = "delete from factura_detalle where id_factura = '" . $dataDevolucion[0]["id_factura"] . "'";
    #$factura_devolucion->ExecuteTrans($SQLfactura_detalle);
    //Eliminar datos de factura_detalle_formapago
    #$SQLfactura_detalle_formapago = "delete from factura_detalle_formapago where id_factura = '" . $dataDevolucion[0]["id_factura"] . "'";
    #$factura_devolucion->ExecuteTrans($SQLfactura_detalle_formapago);
    //Eliminar datos de factura_impuestos
    #$SQLfactura_impuestos = "delete from factura_impuestos where id_factura = '" . $dataDevolucion[0]["id_factura"] . "'";
    #$factura_devolucion->ExecuteTrans($SQLfactura_impuestos);
    //Eliminar datos de cxc_edocuenta
    #$SQLcxc_edocuenta = "delete from cxc_edocuenta where numero = '" . $dataDevolucion[0]["cod_factura"] . "'";
    #$factura_devolucion->ExecuteTrans($SQLcxc_edocuenta);
    #echo "id: ".$dataDevolucion[0]["id_factura"]." cod: ".$dataDevolucion[0]["cod_factura"];exit;
    //Eliminar datos de cxc_edocuenta_detalle
    //$SQLcxc_edocuenta_detalle =  "delete from cxc_edocuenta_detalle  where numero = '".$dataDevolucion[0]["cod_factura"]."'";
    // $factura_devolucion->ExecuteTrans($SQLcxc_edocuenta_detalle);
    //Eliminar datos de cxc_edocuenta_formapago
    //$SQLcxc_edocuenta_formapago =  "delete from cxc_edocuenta_formapago  where numero = '".$dataDevolucion[0]["cod_factura"]."'";
    //$factura_devolucion->ExecuteTrans($SQLcxc_edocuenta_formapago);
    //Eliminar datos de tabla_impuestos
    #$SQLtabla_impuestos = "delete from tabla_impuestos where id_documento = '" . $dataDevolucion[0]["id_factura"] . "'";
    #$factura_devolucion->ExecuteTrans($SQLtabla_impuestos);
    /*
      $kardex_almacen_instruccion = "
      INSERT INTO kardex_almacen (
      `id_transaccion`, `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
      `fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`)
      VALUES (
      NULL, '2', '" . $login->getUsuario() . "', 'Entrada por Devolución', '"
      . $_POST["input_fechaFactura"] . "', '" . $login->getUsuario() . "', CURRENT_TIMESTAMP, '"
      . $_POST["estado_entrega"] . "', '" . $_POST["input_fechaFactura"] . "');";
     */
    $kardex_almacen_instruccion = "
		INSERT INTO kardex_almacen (
			`id_transaccion`, `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
			`fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`)
		VALUES (
			NULL, '3', '" . $login->getUsuario() . "', 'Entrada por Anulación de Factura', CURRENT_TIMESTAMP,
                        '" . $login->getUsuario() . "', CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP);";
    $almacen->ExecuteTrans($kardex_almacen_instruccion);
    $id_transaccion = $almacen->getInsertID();

    $cantItems = $factura_devolucion->getFilas();

    $lineas = array();

    $descuento = $dataDevolucion[0]["totalizar_pdescuento_global"];
    $neto = $dataDevolucion[0]["totalizar_base_imponible"];
    $total = $dataDevolucion[0]["totalizar_monto_cancelar"];
    $efectivo = $dataDevolucion[0]["totalizar_monto_efectivo"];
    $cheque = $dataDevolucion[0]["totalizar_monto_cheque"];
    $tarjeta = $dataDevolucion[0]["totalizar_monto_tarjeta"];
    $credito = $dataDevolucion[0]["input_totalizar_saldo_pendiente"];

    /* $descuento = number_format(($dataDevolucion[0]["totalizar_pdescuento_global"] > 0 ? $dataDevolucion[0]["totalizar_pdescuento_global"] : 0), 2, ",", "");
      $neto = number_format($dataDevolucion[0]["totalizar_base_imponible"], 2, ",", "");
      $total = number_format($dataDevolucion[0]["totalizar_monto_cancelar"], 2, ",", "");
      $efectivo = number_format($dataDevolucion[0]["totalizar_monto_efectivo"], 2, ",", "");
      $cheque = number_format($dataDevolucion[0]["totalizar_monto_cheque"], 2, ",", "");
      $tarjeta = number_format($dataDevolucion[0]["totalizar_monto_tarjeta"], 2, ",", "");
      $credito = number_format($dataDevolucion[0]["input_totalizar_saldo_pendiente"], 2, ",", ""); */

    for ($i = 0; $i < $cantItems; $i++) {

        $descrip_producto = strlen($dataDetalleFactura[$i]["_item_descripcion"]) < 39 ? str_pad($dataDetalleFactura[$i]["_item_descripcion"], 39) : substr($dataDetalleFactura[$i]["_item_descripcion"], 0, 39);
        $item = $factura->ObtenerFilasBySqlSelect("SELECT cod_item, descripcion2 FROM item WHERE id_item = {$dataDetalleFactura[$i]["id_item"]}");
        $codigo_item = $item[0]['cod_item'];
        $cantidad = number_format($dataDetalleFactura[$i]["_item_cantidad"], 2, ",", "");
        $precio = number_format($dataDetalleFactura[$i]["_item_preciosiniva"], 2, ",", "");
        $iva = number_format($dataDetalleFactura[$i]["_item_piva"], 2, ",", "");

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

        $kardex_almacen_detalle_instruccion = "
            INSERT INTO kardex_almacen_detalle (
                `id_transaccion`, `id_almacen_entrada`,
                `id_almacen_salida`, `id_item`, `cantidad`)
            VALUES (
                '{$id_transaccion}', '{$dataDetalleFactura[$i]["_item_almacen"]}', '{$dataDetalleFactura[$i]["_item_almacen"]}',
                '{$dataDetalleFactura[$i]["id_item"]}', '{$dataDetalleFactura[$i]["_item_cantidad"]}');";

        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

        #if ($_POST["estado_entrega"] == 'Entregado') {
        $campos = $factura_devolucion->ObtenerFilasBySqlSelect("
                        SELECT * FROM item_existencia_almacen
                        WHERE id_item  = '{$dataDetalleFactura[$i]["id_item"]}' AND cod_almacen = '{$dataDetalleFactura[$i]["_item_almacen"]}'");
        $cantidadExistente = $campos[0]["cantidad"];

        #if (!$hayPedido) {
        $cantidad_actualizada = $cantidadExistente + $dataDetalleFactura[$i]["_item_cantidad"];
        $factura_devolucion->ExecuteTrans("
            UPDATE item_existencia_almacen SET cantidad = '{$cantidad_actualizada}'
            WHERE id_item  = '{$dataDetalleFactura[$i]["id_item"]}' AND cod_almacen = '{$dataDetalleFactura[$i]["_item_almacen"]}'");
        #$_POST[""][$i]  $_POST["_item_codigo"][$i]  $_POST[""][$i]
        #}
        //$factura_devolucion->ExecuteTrans("delete from item_precompromiso where cod_item_precompromiso = '" . $_POST["_cod_item_precompromiso"][$i] . "'");
        #}
    }

    $datos_cliente = $factura->ObtenerFilasBySqlSelect("SELECT nombre, direccion, telefonos, rif FROM clientes WHERE id_cliente = {$_POST["id_cliente"]};");

    if ($parametros_generales[0]['tipo_facturacion'] == 1) {#Si el tipo de facturación es Fiscal
        if ($parametros_generales[0]['swterceroimp'] == 1) {#Si usa Spooler Fiscal
            /*
             * Comenzar a crear el archivo para el spooler:
             * Directorio para guardar el archivo
             */
            /* Directorio de prueba para ver el archivo generado antes de que sea accedido por la impresora fiscal */
            #$directorio = "spooler/";
            $directorio = "C:\FACTURAS\\"; # directorio de produccion en Windows
            $nombre_archivo_spooler = "Selectra.001";
            $ruta = $directorio . $nombre_archivo_spooler;
            $archivo_spooler = fopen($ruta, "w");
            chmod($directorio, 0777);
            chmod($ruta, 0777);

            $cabecera = "DEVOLUCION: " . str_pad($nro_devolucion, 8, "0", STR_PAD_LEFT) . "\n";
            $cabecera.="CLIENTE:    " . str_pad($datos_cliente[0]['nombre'], 35) . "\n";
            $cabecera.="DIRECCION1: " . str_pad($datos_cliente[0]['direccion'], 35) . "\n";
            $cabecera.="DIRECCION2:\n";
            $cabecera.="TELEFONO:   {$datos_cliente[0]['telefonos']}\n";
            $cabecera.="RIF/CI:     {$datos_cliente[0]['rif']}\n";
            $cabecera.="DESCRIPCION                             CODIGO                    CANT      PRECIO    IVA\n";

            fwrite($archivo_spooler, $cabecera);

            #fwrite($archivo_spooler, $linea_producto);
            $detalles = "";
            foreach ($lineas as &$linea) {
                $detalles = $linea["descripcion"] . " " . $linea["codigo"] . $linea["cantidad"] . str_pad($linea["precio"], 12, ' ', STR_PAD_LEFT) . str_pad($linea["iva"], 7, ' ', STR_PAD_LEFT) . "\n";
                fwrite($archivo_spooler, $detalles);
            }

            /* $descuento = number_format(($dataDevolucion[0]["totalizar_pdescuento_global"] > 0 ? $dataDevolucion[0]["totalizar_pdescuento_global"] : 0), 2, ",", "");
              $neto = number_format($dataDevolucion[0]["totalizar_base_imponible"], 2, ",", "");
              $cancelado = number_format($dataDevolucion[0]["totalizar_monto_cancelar"], 2, ",", "");
              $efectivo = number_format($dataDevolucion[0]["totalizar_monto_efectivo"], 2, ",", "");
              $cheque = number_format($dataDevolucion[0]["totalizar_monto_cheque"], 2, ",", "");
              $tarjeta = number_format($dataDevolucion[0]["totalizar_monto_tarjeta"], 2, ",", "");
              $credito = number_format($dataDevolucion[0]["input_totalizar_saldo_pendiente"], 2, ",", "");

              $linea_producto.=str_pad("DESCUENTO:", abs(strlen("DESCUENTO:") + strlen($descuento) - 29)) . "{$descuento} %\n";
              $linea_producto.=str_pad("TOTAL NETO:", abs(strlen("TOTAL NETO:") + strlen($neto) - 29)) . "{$neto}\n";
              $linea_producto.=str_pad("TOTAL CANCELADO:", abs(strlen("TOTAL CANCELADO:") + strlen($cancelado) - 29)) . "{$cancelado}\n";
              $linea_producto.=str_pad("EFECTIVO:", abs(strlen("EFECTIVO:") + strlen($efectivo) - 29)) . "{$efectivo}\n";
              $linea_producto.=str_pad("CHEQUES:", abs(strlen("CHEQUES:") + strlen($cheque) - 29)) . "{$cheque}\n";
              $linea_producto.=str_pad("TARJETA:", abs(strlen("TARJETA:") + strlen($tarjeta) - 29)) . "{$tarjeta}\n";
              $linea_producto.=str_pad("CREDITO:", abs(strlen("CREDITO:") + strlen($credito) - 29)) . "{$credito}\n"; */
            $resumen = $obj_txt->formatearLineasDetallesPago("DESCUENTO:", number_format(($dataDevolucion[0]["totalizar_pdescuento_global"] > 0 ? $dataDevolucion[0]["totalizar_pdescuento_global"] : 0), 2, ",", "")) . " %\n";
            $resumen.=$obj_txt->formatearLineasDetallesPago("TOTAL NETO:", number_format($dataDevolucion[0]["totalizar_base_imponible"], 2, ",", "")) . "\n";
            $resumen.=$obj_txt->formatearLineasDetallesPago("TOTAL CANCELADO:", number_format($dataDevolucion[0]["totalizar_monto_cancelar"], 2, ",", "")) . "\n";
            $resumen.=$obj_txt->formatearLineasDetallesPago("EFECTIVO:", number_format($dataDevolucion[0]["totalizar_monto_efectivo"], 2, ",", "")) . "\n";
            $resumen.=$obj_txt->formatearLineasDetallesPago("CHEQUES:", number_format($dataDevolucion[0]["totalizar_monto_cheque"], 2, ",", "")) . "\n";
            $resumen.=$obj_txt->formatearLineasDetallesPago("TARJETA:", number_format($dataDevolucion[0]["totalizar_monto_tarjeta"], 2, ",", "")) . "\n";
            $resumen.=$obj_txt->formatearLineasDetallesPago("CREDITO:", number_format($dataDevolucion[0]["input_totalizar_saldo_pendiente"], 2, ",", "")) . "\n";
            #$resumen.="USUARIOS:         " . $login->getUsuario() . "\n";
            $resumen.="COMENTARIO1:      NO SE ACEPTAN DEVOLUCIONES DESPUES DE 24 HORAS \n";
            #$resumen.="COMENTARIO2:      <ESCRIBA ALGO AQUI>\n";
            #$resumen.="COMENTARIO2:      <ESCRIBA ALGO AQUI>\n";
            $resumen.="DATOS PARA LAS  \"D E V O L U C I O N E S\"\n";
            $resumen.="FACTDEVOL:        {$dataDevolucion[0]['cod_factura']}\n";
            $resumen.="FECHADEVOL:       " . date("d/m/Y") . "\n";
            $resumen.="HORADEVOL:        " . date("h:i:s") . "\n";
            $resumen.="SERIALIMP:        {$parametros_generales[0]['impresora_serial']}\n";
            $resumen.="COO-BEMATECH:     \n";

            fwrite($archivo_spooler, $resumen);
            fclose($archivo_spooler);

            # En este punto comienza el parseo de la BD (DBF) del Spooler Fiscal
            # para obtener los datos fiscales de la factura y almacenarlos en la tabla
            # factura de la BD de Selectra.
            # Esperar un tiempo prudencial para que el Spooler registre la factura en su DB (BDF)
            #sleep($seconds = 10);
            $dbf = new SpoolerConfDB();
            $dbf->setDirDBF(); #$dirdbf = "/home/asys/Descargas/DBF_R14/"
            #$factura_fiscal = $dbf->obtenerUltimoRegistroDBF();
            #$factura->ExecuteTrans("UPDATE factura SET cod_factura_fiscal = '" . $factura_fiscal[1] . "' WHERE cod_factura = '" . $factura_fiscal[0] . "'");
            $factura->ExecuteTrans($sql = "UPDATE factura_devolucion SET cod_devolucion_fiscal = '{$factura_fiscal['NUMDOC']}', nroz = '{$factura_fiscal['NROZ']}', impresora_serial = '{$factura_fiscal['IMPSERIAL']}' WHERE id_devolucion = '{$id_facturaTrans}';");

            #fclose($archivo_spooler);
        } elseif ($parametros_generales[0]['swterceroimp'] == 0) {
            include ("../../libs/php/clases/tfhka/TfhkaPHP.php");
            $itObj = new Tfhka();

            $i0 = explode(".", $parametros_generales[0]['iva_c']);
            $index_0 = (string) $i0[0] . $i0[1];
            $i1 = explode(".", $parametros_generales[0]['iva_a']);
            $index_1 = (string) $i1[0] . $i1[1];
            $i2 = explode(".", $parametros_generales[0]['porcentaje_impuesto_principal']);
            $index_2 = (string) $i2[0] . $i2[1];
            $i3 = explode(".", $parametros_generales[0]['iva_b']);
            $index_3 = (string) $i3[0] . $i3[1];
            $tasa_impuesto = array(/* Exento */$index_0 => "d0", /* 8 */$index_1 => "d1", /* 12 */$index_2 => "d2", /* 22 */$index_3 => "d3");

            $archivo = "C:\IntTFHKA\ArchivoFactura.txt";
            $fp = fopen($archivo, "w");

            $string = "";
            $write = fputs($fp, $string);

            $nombre = strlen(trim($datos_cliente[0]['nombre'])) <= 40 ? trim($datos_cliente[0]['nombre']) : substr(trim($datos_cliente[0]['nombre']), 0, 40);
            $string = "i01NOMBRE: {$nombre}\n"; #$string = "iS*{$nombre}\n";
            $string .= "i02CI/RIF: {$datos_cliente[0]['rif']}\n"; #$string .= "iR*{$datos_cliente[0]['rif']}\n";
            $direccion = $datos_cliente[0]['direccion'] != "" ? strlen(trim($datos_cliente[0]['direccion'])) <= 40 ? strtoupper(trim($datos_cliente[0]['direccion'])) : strtoupper(substr(trim($datos_cliente[0]['direccion']), 0, 39))  : "";
            $string .= $direccion != "" ? "i03DIRECCION: {$direccion}\n" : "";
            $telefono = trim($_POST['telefonos']);
            $string .= $telefono != "" ? "i04TELEFONO: {$telefono}\n" : "";
            $string .= "i05FACTURA: {$dataDevolucion[0]["cod_factura_fiscal"]}\n";

            $write = fputs($fp, utf8_encode($string));

            foreach ($lineas as $linea) {

                $p = explode(",", $linea["precio"]);
                $precio = (string) $p[0] . $p[1];
                $precio = str_pad($precio, 10, "0", STR_PAD_LEFT);
                /* echo "precio: " . $p . "<br>";
                  echo "tam precio: " . strlen($p) . "<br>"; */

                $cantidad = explode(",", $linea["cantidad"]);
                $cantidad = str_pad((string) $cantidad[0], 5, "0", STR_PAD_LEFT) . str_pad((string) $cantidad[1], 3, "0", STR_PAD_RIGHT);
                $cantidad = str_pad($cantidad, 8 - strlen($cantidad), "0", STR_PAD_LEFT);
                /* echo "cantidad: " . $cantidad . "<br>"; */

                #$descripcion = strlen($linea["descripcion"]) <= 40 ? $linea["descripcion"] : substr($linea["descripcion"], 0, 39);
                $descripcion = trim($linea["descripcion"]);
                /* echo "descripcion: " . $descripcion . "<br>"; */

                $t = explode(",", $linea["iva"]);
                $tasa = (string) $t[0] . $t[1];

                $string = $tasa_impuesto[$tasa] . $precio . $cantidad . $descripcion . "\n";

                $write = fputs($fp, utf8_encode($string));
                #$itObj->sendCmdTcp(utf8_encode($tasa_impuesto[$tasa] . $precio . $cantidad . $descripcion));

                if ($linea["descuento_item"] > 0) {
                    $d = explode(",", $linea["descuento_item"]);
                    $descuento = (string) $d[0] . $d[1];
                    $descuento = str_pad($descuento, 4, "0", STR_PAD_LEFT);
                    $string = "p-{$descuento}\n";
                    $write = fputs($fp, utf8_encode($string));
                    #$itObj->sendCmdTcp(utf8_encode("p-{$descuento}"));
                }
            }
            if ($total <= $efectivo || $total <= $cheque || $total <= $tarjeta /* || $total <= $deposito || $total <= $otro */) {
                $medio_pago = "1"; /* Pago directo */
                if ($total <= $efectivo) {
                    $medio_pago .= "01" . "\n";
                } else if ($total <= $cheque) {
                    $medio_pago .= "05" . "\n";
                } else if ($total <= $tarjeta) {
                    $medio_pago .= "10" . "\n";
                } /* else if ($total <= $deposito) {

                  } else if ($total <= $otro) {

                  } */
                $string = $medio_pago;
            } else {
                /* Pago parcial */
                $medio_pago = "";
                if ($efectivo > 0) {
                    $efectivo = number_format($efectivo, 2, ",", "");
                    $efectivo = explode(",", $efectivo);
                    $efectivo = (string) $efectivo[0] . $efectivo[1];
                    $efectivo = str_pad($efectivo, 12, "0", STR_PAD_LEFT);
                    $medio_pago .= "2" . "01" . $efectivo . "\n";
                }
                if ($cheque > 0) {
                    $cheque = number_format($cheque, 2, ",", "");
                    $cheque = explode(",", $cheque);
                    $cheque = (string) $cheque[0] . $cheque[1];
                    $cheque = str_pad($cheque, 12, "0", STR_PAD_LEFT);
                    $medio_pago .= "2" . "05" . $cheque . "\n";
                }
                if ($tarjeta > 0) {
                    $tarjeta = number_format($tarjeta, 2, ",", "");
                    /* $deposito = $_POST["input_totalizar_monto_deposito"];#number_format($_POST["input_totalizar_monto_deposito"], 2, ",", "");
                      $otro = $_POST["opt_otrodocumento"];#number_format($_POST["opt_otrodocumento"], 2, ",", ""); */
                    $tarjeta = explode(",", $tarjeta);
                    $tarjeta = (string) $tarjeta[0] . $tarjeta[1];
                    $tarjeta = str_pad($tarjeta, 12, "0", STR_PAD_LEFT);
                    $medio_pago .= "2" . "10" . $tarjeta . "\n";
                }
                $string = $medio_pago;
            }
            #$string = "3\n" . $string;
            $write = fputs($fp, utf8_encode($string));
            fclose($fp);

            $lineas_escritas = $itObj->SendFileCmd($archivo);
            #$itObj->sendCmdTcp(utf8_encode($string));

            $itObj->UploadStatusCmd('S1');
            while (!file_exists("C:\IntTFHKA\Status.txt"));

            $stream = fopen("C:\IntTFHKA\Status.txt", "rb");
            $chunk = "";
            while (!feof($stream)) {
                $chunk .= fread($stream, 8192);
            }
            $nro_factura_fiscal = substr($chunk, 95, 103);
            $factura->ExecuteTrans($sql = "UPDATE factura_devolucion SET cod_devolucion_fiscal = '{$nro_factura_fiscal}' WHERE id_devolucion = '{$id_facturaTrans}';");
            unset($efectivo, $cheque, $tarjeta, /* $deposito, $otro, */ $lineas_escritas, $archivo, $write, $string, $fp, $medio_pago, $itObj, $lineas, $linea);
        }
    }
    #$nro_devolucionOLD = $correlativos->getUltimoCorrelativo("cod_devolucion", 1, "no");
    $nro_devolucion = $correlativos->getUltimoCorrelativo("cod_devolucion", 1, "no");
    $factura_devolucion->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_devolucion}' WHERE campo = 'cod_devolucion'");
    $nro_devolucion -= 1;
    if ($factura_devolucion->errorTransaccion == 1) {
        Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Factura Devuelta Exitosamente con el <b>Nro. " . $formateo_nro_devolucion . "</b></span>");
    }
    if ($factura_devolucion->errorTransaccion == 0) {
        Msg::setMessage("<span style=\"color:red;\">Error al devolver la factura.</span>");
    }
    $factura_devolucion->CommitTrans($factura->errorTransaccion);
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
    exit;
}

if (!isset($_GET["codigo"])) {
    header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"]);
    exit;
}
?>