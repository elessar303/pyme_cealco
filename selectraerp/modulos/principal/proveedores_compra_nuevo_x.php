<?php

include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/compra.php");
include("../../libs/php/clases/correlativos.php");
include("../../libs/php/clases/almacen.php");

$proveedores = new Proveedores();
$compra = new Compra();
$almacen = new Almacen();
$correlativos = new Correlativos();

if (isset($_POST["Datosproveedor"])) { // si el usuario hizo post
    $compra->BeginTrans();
    # Verificamos si la compra fue pagada completa
    $marca = "P";
    $cod_estatus = "1"; # cod_estatus = 1 indicada que esta en Proceso.
    # Obtener correlativo: obtenemos el correlativo de la compra
    $nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 1, "si");
    $formateo_nro_compra = $nro_compra;
    $sql = "INSERT INTO `compra` (
		`id_compra`, `cod_compra`, `id_proveedor`, `cod_vendedor`,
		`fechacompra`, `montoItemscompra`, `ivaTotalcompra`, `TotalTotalcompra`,
		`cantidad_items`, `cod_estatus`, `fecha_creacion`, `usuario_creacion`,
		`responsable`, `centrocosto`, `num_factura_compra`, `num_cont_factura`)
	VALUES (
		NULL , '" . $nro_compra . "', '" . $_POST["id_proveedor"] . "', '" . $_POST["cod_vendedor"] . "',
    	'" . $_POST["input_fechacompra"] . "', '" . $_POST["input_tsiniva"] . "', '" . $_POST["input_tiva"] . "',
        '" . $_POST["input_tciniva"] . "', '" . $_POST["input_cantidad_items"] . "', '" . $cod_estatus . "',
		CURRENT_TIMESTAMP , '" . $login->getUsuario() . "',
    	'" . $_POST["responsable"] . "', '" . $_POST["centrocosto"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_cont_factura"] . "');";

    $compra->ExecuteTrans($sql);

    $_POST["fecha_vencimiento"] = ($_POST["fecha_vencimiento"] == '') ? '0000-00-00' : $_POST["fecha_vencimiento"];

    $id_compraTrans = $compra->getInsertID();
    /*
      $SQLdetalle_formapago = "
      INSERT INTO compra_detalle_formapago (
      `cod_compra_detalle_formapago` ,
      `id_compra` ,
      `totalizar_monto_cancelar` ,
      `totalizar_saldo_pendiente` ,
      `totalizar_cambio` ,
      `totalizar_monto_efectivo` ,
      `opt_cheque` ,
      `totalizar_monto_cheque` ,
      `totalizar_nro_cheque` ,
      `totalizar_nombre_banco` ,
      `opt_tarjeta` ,
      `totalizar_monto_tarjeta` ,
      `totalizar_nro_tarjeta` ,
      `totalizar_tipo_tarjeta` ,
      `opt_deposito` ,
      `totalizar_monto_deposito` ,
      `totalizar_nro_deposito` ,
      `totalizar_banco_deposito` ,

      `opt_otrodocumento` ,
      `totalizar_monto_otrodocumento` ,
      `totalizar_nro_otrodocumento` ,
      `totalizar_banco_otrodocumento` ,

      `fecha_vencimiento` ,
      `observacion` ,
      `persona_contacto` ,
      `telefono` ,
      `fecha_creacion` ,
      `usuario_creacion`
      )
      VALUES (
      NULL , '".$id_compraTrans."',
      ".$_POST["input_totalizar_monto_cancelar"].",
      ".$_POST["input_totalizar_saldo_pendiente"].",
      ".$_POST["input_totalizar_cambio"].",
      ".$_POST["input_totalizar_monto_efectivo"].",
      '".$_POST["opt_cheque"]."',
      ".$_POST["input_totalizar_monto_tarjeta"].",
      '".$_POST["input_totalizar_nro_cheque"]."',
      '".$_POST["input_totalizar_nombre_banco"]."',
      '".$_POST["opt_tarjeta"]."',
      '".$_POST["input_totalizar_monto_tarjeta"]."',
      '".$_POST["input_totalizar_nro_tarjeta"]."',
      '".$_POST["input_totalizar_tipo_tarjeta"]."',
      '".$_POST["opt_deposito"]."',
      '".$_POST["input_totalizar_banco_deposito"]."',
      ".$_POST["input_totalizar_nro_deposito"].",
      '".$_POST["input_totalizar_banco_deposito"]."',

      '".$_POST["opt_otrodocumento"]."',
      '".$_POST["totalizar_monto_otrodocumento"]."',
      '".$_POST["totalizar_nro_otrodocumento"]."',
      '".$_POST["totalizar_banco_otrodocumento"]."',


      '".$_POST["fecha_vencimiento"]."',
      '".$_POST["observacion"]."',
      '".$_POST["persona_contacto"]."',
      '".$_POST["telefono"]."',
      CURRENT_TIMESTAMP ,
      '".$login->getUsuario()."'
      );";



      $compra->ExecuteTrans($SQLdetalle_formapago);

      $_POST["totalizar_base_retencion"]=($_POST["totalizar_base_retencion"]=='') ? '0.00': $_POST["totalizar_base_retencion"];
      $_POST["totalizar_pbase_retencion"]=($_POST["totalizar_pbase_retencion"]=='') ? '0.00': $_POST["totalizar_pbase_retencion"];
      $_POST["cod_impuesto_iva"]=($_POST["cod_impuesto_iva"]=='') ? '0': $_POST["cod_impuesto_iva"];
      $_POST["totalizar_descripcion_base_retencion"]=($_POST["totalizar_descripcion_base_retencion"]=='') ? '0': $_POST["totalizar_descripcion_base_retencion"];
      $_POST["totalizar_monto_iva2"]=($_POST["totalizar_monto_iva2"]=='') ? '0': $_POST["totalizar_monto_iva2"];
      $_POST["totalizar_monto_1x1000"]=($_POST["totalizar_monto_1x1000"]=='') ? '0': $_POST["totalizar_monto_1x1000"];


      $consulta = "
      INSERT INTO  compra_impuestos  (
      `id_compra_impuestos` ,
      `id_compra` ,
      `totalizar_base_retencion` ,
      `totalizar_pbase_retencion` ,
      `totalizar_descripcion_base_retencion` ,
      `cod_impuesto_iva` ,
      `totalizar_monto_iva2` ,
      `totalizar_monto_1x1000`,
      `usuario_creacion`,
      `fecha_creacion`
      )
      VALUES (
      NULL , '".$id_compraTrans."', '".$_POST["totalizar_base_retencion"]."', '".$_POST["totalizar_pbase_retencion"]."',
      '".$_POST["totalizar_descripcion_base_retencion"]."', '".$_POST["cod_impuesto_iva"]."',
      '".$_POST["totalizar_monto_iva2"]."', '".$_POST["totalizar_monto_1x1000"]."'
      ,'".$login->getUsuario()."', CURRENT_TIMESTAMP
      );";
      $compra->ExecuteTrans($consulta);

     */

    # Insertamos en la tabla de cuentas por cobrar la cabecera del asiento
    $SQL_cxp = "INSERT INTO cxp_edocuenta (
		`cod_edocuenta`, `id_proveedor`, `documento` ,
		`numero`, `monto`, `fecha_emision`,
		`observacion`, `vencimiento_fecha`, `vencimiento_persona_contacto` ,
		`vencimiento_telefono`, `vencimiento_descripcion` ,
		`usuario_creacion`, `fecha_creacion`, `marca`)
	VALUES (
		NULL, '" . $_POST["id_proveedor"] . "', 'FACxCOM',
		'" . $nro_compra . "', '" . $_POST["input_tciniva"] . "', '" . date("Y-m-d") . "',
		'Compra " . $nro_compra . "', '" . $_POST["fecha_vencimiento"] . "', '" . $_POST["persona_contacto"] . "' ,
		'" . $_POST["telefono"] . "', '" . $_POST["observacion"] . "' ,
		'" . $login->getUsuario() . "', CURRENT_TIMESTAMP, '" . $marca . "');";

    $compra->ExecuteTrans($SQL_cxp);
    $id_cxp = $compra->getInsertID();

    $SQL_cxp_DET = "INSERT INTO cxp_edocuenta_detalle (
		`cod_edocuenta_detalle` ,`cod_edocuenta` ,`documento` ,
		`numero` ,`descripcion` ,`tipo` ,
		`monto` ,`usuario_creacion` ,`fecha_creacion`,
		`fecha_emision_edodet`, `marca`)
	VALUES (
		NULL ,'" . $id_cxp . "','PAGOxCOM',
    	'" . $nro_compra . "R','compra " . $nro_compra . "','c',
    	'" . $_POST["input_tciniva"] . "','" . $login->getUsuario() . "',CURRENT_TIMESTAMP,
		'" . date("Y-m-d") . "','" . $marca . "');";
    # Se inserta el detalle de la cxp en este caso el asiento del DEBITO.
    $compra->ExecuteTrans($SQL_cxp_DET);
    $cod_edocuenta_detalle = $compra->getInsertID();

    /**
     * Aumentamos el valor del correlativo del Pago o Abono de compra.

      $compra->ExecuteTrans("update correlativos set contador = '".$correlativos->getUltimoCorrelativo("cod_pago_o_abono",1)."' where campo = 'cod_pago_o_abono'");
     */
    /**
     * Obtenemos el siguiente numero de correlativo de Pago x Abono a compra.

      $cod_pago_o_abono = $correlativos->getUltimoCorrelativo("cod_pago_o_abono",0,"si","");
     */
    /**
     * Verificamos el pago fue completo, un abono ó fue un credito.

      if($_POST["totalizar_monto_cancelar"]>0&&$_POST["totalizar_monto_cancelar"]<=$_POST["input_tciniva"]){


      $SQL_cxp_DET = "INSERT INTO cxp_edocuenta_detalle (
      `cod_edocuenta_detalle` ,
      `cod_edocuenta` ,
      `documento` ,
      `numero` ,
      `descripcion` ,
      `tipo` ,
      `monto` ,
      `usuario_creacion` ,
      `fecha_creacion`
      )
      VALUES (
      NULL , '".$id_cxp."', 'PAGOxCOM', '".$nro_compra."R', 'Pago compra ".$nro_compra."',
      'd', '".$_POST["totalizar_monto_cancelar"]."', '".$login->getUsuario()."',
      CURRENT_TIMESTAMP
      );";
      /**
     * Se inserta el detalle de la cxp en este caso el asiento del CREDITO.

      $compra->ExecuteTrans($SQL_cxp_DET);

      }/*else{
      $SQL_cxp_DET = "INSERT INTO cxp_edocuenta_detalle (
      `cod_edocuenta_detalle` ,
      `cod_edocuenta` ,
      `documento` ,
      `numero` ,
      `descripcion` ,
      `tipo` ,
      `monto` ,
      `usuario_creacion` ,
      `fecha_creacion`
      )
      VALUES (
      NULL , '".$id_cxp."', 'PAGOxCOM', '".$nro_compra."R', 'Pago compra ".$nro_compra."',
      'd', '".$_POST["input_tciniva"]."', '".$login->getUsuario()."',
      CURRENT_TIMESTAMP
      );";
      /**
     * Se inserta el detalle de la cxp en este caso el asiento del CREDITO.

      $compra->ExecuteTrans($SQL_cxp_DET);

      } */
    /**
     * SQL para generar el detalle de forma pago en la tabla de cxp_edocuenta_formapago.

      $SQL_cxp_formapago = "
      INSERT INTO cxp_edocuenta_formapago (
      `cod_cxp_edocuenta_formapago` ,
      `cod_edocuenta_detalle` ,
      `totalizar_monto_cancelar` ,
      `totalizar_saldo_pendiente` ,
      `totalizar_cambio` ,
      `totalizar_monto_efectivo` ,
      `opt_cheque` ,
      `totalizar_monto_cheque` ,
      `totalizar_nro_cheque` ,
      `totalizar_nombre_banco` ,
      `opt_tarjeta` ,
      `totalizar_monto_tarjeta` ,
      `totalizar_nro_tarjeta` ,
      `totalizar_tipo_tarjeta` ,
      `opt_deposito` ,
      `totalizar_monto_deposito` ,
      `totalizar_nro_deposito` ,
      `totalizar_banco_deposito` ,

      `opt_otrodocumento`,
      `totalizar_monto_otrodocumento`,
      `totalizar_nro_otrodocumento`,
      `totalizar_banco_otrodocumento`,


      `fecha_creacion` ,
      `usuario_creacion`
      )
      VALUES (
      NULL ,
      '".$cod_edocuenta_detalle."',
      ".$_POST["input_totalizar_monto_cancelar"].",
      ".$_POST["input_totalizar_saldo_pendiente"].",
      ".$_POST["input_totalizar_cambio"].",
      ".$_POST["input_totalizar_monto_efectivo"].",
      '".$_POST["opt_cheque"]."',
      ".$_POST["input_totalizar_monto_tarjeta"].",
      '".$_POST["input_totalizar_nro_cheque"]."',
      '".$_POST["input_totalizar_nombre_banco"]."',
      '".$_POST["opt_tarjeta"]."',
      ".$_POST["input_totalizar_monto_tarjeta"].",
      ".$_POST["input_totalizar_nro_tarjeta"].",
      '".$_POST["input_totalizar_tipo_tarjeta"]."',
      '".$_POST["opt_deposito"]."',
      '".$_POST["input_totalizar_banco_deposito"]."',
      ".$_POST["input_totalizar_nro_deposito"].",
      '".$_POST["input_totalizar_banco_deposito"]."',

      '".$_POST["opt_otrodocumento"]."',
      '".$_POST["totalizar_banco_otrodocumento"]."',
      '".$_POST["totalizar_nro_otrodocumento"]."',
      '".$_POST["totalizar_banco_otrodocumento"]."',

      CURRENT_TIMESTAMP , '".$login->getUsuario()."'
      );
      ";
      $compra->ExecuteTrans($SQL_cxp_formapago);
     */
    $observacion = "Entrada " . (!strcmp($_POST["estado_entrega"], "Entregado") ? "por Compras" : "Pendiente por Entrega");
    $kardex_almacen_instruccion = "INSERT INTO kardex_almacen (
		`id_transaccion`,`tipo_movimiento_almacen`,`autorizado_por`,
		`observacion`,`fecha`,`usuario_creacion`,
		`fecha_creacion`,`estado`,`fecha_ejecucion`,`id_documento`)
	VALUES (
		NULL,'1','" . $_POST["responsable"] . "',
		'$observacion','" . $_POST["input_fechacompra"] . "','" . $login->getUsuario() . "',
		CURRENT_TIMESTAMP,'" . $_POST["estado_entrega"] . "','" . $_POST["input_fechacompra"] . "','" . $id_compraTrans . "');";

    $almacen->ExecuteTrans($kardex_almacen_instruccion);
    $id_transaccion = $almacen->getInsertID();

    for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) {
        $detalle_item_instruccion = "INSERT INTO compra_detalle (
    		`id_detalle_compra`,`id_compra`,`id_item`,
    		`_item_almacen`,`_item_cantidad`,`_item_preciosiniva`,
    		`_item_totalsiniva`,`_item_totalconiva`,`usuario_creacion`,
    		`fecha_creacion`,`codfabricante`,`piva`,`_tiva`)
    	VALUES (
    		NULL,'" . $id_compraTrans . "','" . $_POST["_id_item"][$i] . "',
    		'" . $_POST["_id_almacen"][$i] . "','" . $_POST["_cantidad"][$i] . "','" . $_POST["_precio"][$i] . "',
    		'" . $_POST["_ttotalsiniva"][$i] . "','" . ($_POST["_ttotalsiniva"][$i] + $_POST["_tiva"][$i]) . "',
    		'" . $login->getUsuario() . "',CURRENT_TIMESTAMP,'" . $_POST["_codfabricante"][$i] . "',
    		'" . $_POST["_iva"][$i] . "','" . $_POST["_tiva"][$i] . "');";

        #if($_POST["estado_entrega"]=='Entregado'){
        $kardex_almacen_detalle_instruccion =
                "INSERT INTO kardex_almacen_detalle (
        	`id_transaccion_detalle`,`id_transaccion`,`id_almacen_entrada`,
        	`id_almacen_salida`,`id_item`,`cantidad`)
        VALUES (
        	NULL,'" . $id_transaccion . "','" . $_POST["_id_almacen"][$i] . "',
        	'','" . $_POST["_id_item"][$i] . "','" . $_POST["_cantidad"][$i] . "');";

        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
        #}

        $compra->ExecuteTrans($detalle_item_instruccion);

        $instruccion = "SELECT SUM(e.cantidad) AS cantidad_inventario,costo_promedio,utilidad1,utilidad2,utilidad3,iva
                        FROM item AS i
                        INNER JOIN item_existencia_almacen AS e ON i.id_item=e.id_item
                        WHERE i.id_item='" . $_POST["_id_item"][$i] . "'
                        GROUP BY e.cantidad";

        $costopromedio = $compra->ObtenerFilasBySqlSelect($instruccion);

        $costo_promedio = ($costopromedio[0]["cantidad_inventario"] * $costopromedio[0]["costo_promedio"]) + ($_POST["_precio"][$i] * $_POST["_cantidad"][$i]);

        if ($_POST["estado_entrega"] == 'Entregado') {
            $sql_iea = "SELECT * FROM item_existencia_almacen
                    WHERE id_item  = '" . $_POST["_id_item"][$i] . "' AND cod_almacen = '" . $_POST["_id_almacen"][$i] . "'";
            $campos = $compra->ObtenerFilasBySqlSelect($sql_iea);
            if (count($campos) > 0) {
                $cantidadExistente = $campos[0]["cantidad"];
                $compra->ExecuteTrans("UPDATE item_existencia_almacen SET cantidad = '" . ($cantidadExistente + $_POST["_cantidad"][$i]) . "'
         	WHERE id_item  = '" . $_POST["_id_item"][$i] . "' AND cod_almacen = '" . $_POST["_id_almacen"][$i] . "'");
            } else {
                $instruccion = "INSERT INTO item_existencia_almacen(`cod_almacen`,`id_item`,`cantidad`)
                        VALUES ('" . $_POST["_id_almacen"][$i] . "','" . $_POST["_id_item"][$i] . "','" . $_POST["_cantidad"][$i] . "');";
                $compra->ExecuteTrans($instruccion);
            }
        }
        # Verificar si fue proporcionado un nro de factura y/o nro de control de factura
        if ($_POST["num_factura"] != "" && $_POST["num_cont_factura"] != "") {
            $sql_cxp_factura = "INSERT INTO cxp_factura (
                    cod_factura, cod_cont_factura, id_cxp_edocta, fecha_factura, fecha_recepcion,
                    monto_base, monto_exento, anticipo, monto_total_con_iva, monto_total_sin_iva,
                    cod_impuesto, porcentaje_iva_mayor, monto_iva, porcentaje_iva_retenido, monto_retenido,
                    total_a_pagar, cod_estatus, fecha_pago, fecha_creacion, usuario_creacion,
                    tipo, factura_afectada, libro_compras, cod_correlativo_iva, cod_correlativo_islr
                )
                VALUES (
                    '" . $_POST["num_factura"] . "', '" . $_POST["num_cont_factura"] . "', '" . $id_cxp . "', '" . $_POST["input_fechacompra"] . "', '" . $_POST["input_fechacompra"] . "',
                    '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "',
                    '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "',
                    '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "',
                    '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "'
                )";
            $compra->ExecuteTrans($sql_cxp_factura);

            for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) {
                $sql_cxp_factura_det = "INSERT INTO cxp_factura_detalle (
                    id_factura_fk, monto_base, porcentaje_retenido, cod_impuesto, monto_retenido, id_item)
                VALUES (
                    '" . $compra->getInsertID() . "', '" . $_POST["num_cont_factura"] . "', '" . $id_cxp . "', '" . $_POST["num_factura"] . "', '" . $_POST["num_factura"] . "', '" . $_POST["_id_item"][$i] . "')";
                $compra->ExecuteTrans($sql_cxp_factura_det);
            }
        }
        $utilidad1 = ($_POST["_precio"][$i] * $costopromedio[0]["utilidad1"]) / 100;
        $precio1 = $_POST["_precio"][$i] + $utilidad1;
        $coniva1 = $precio1 + (($precio1 * $costopromedio[0]["iva"]) / 100);
        $utilidad2 = ($_POST["_precio"][$i] * $costopromedio[0]["utilidad2"]) / 100;
        $precio2 = $_POST["_precio"][$i] + $utilidad2;
        $coniva2 = $precio2 + (($precio2 * $costopromedio[0]["iva"]) / 100);
        $utilidad3 = ($_POST["_precio"][$i] * $costopromedio[0]["utilidad3"]) / 100;
        $precio3 = $_POST["_precio"][$i] + $utilidad3;
        $coniva3 = $precio3 + (($precio3 * $costopromedio[0]["iva"]) / 100);

        $instruccion = "select sum(cantidad) as cantidad_inventario from item_existencia_almacen where id_item='" . $_POST["_id_item"][$i] . "'";
        $existencia = $compra->ObtenerFilasBySqlSelect($instruccion);

        $existenciaactual = $existencia[0]["cantidad_inventario"];

        $costo_promedio_actual = $costo_promedio / $existenciaactual;

        $instruccion = "
	update item set costo_anterior=costo_actual, costo_promedio='" . $costo_promedio_actual . "',costo_actual= '" . $_POST["_precio"][$i] . "' ,
        precio1='" . $precio1 . "',coniva1='" . $coniva1 . "',precio2='" . $precio2 . "',coniva2='" . $coniva2 . "',precio3='" . $precio3 . "',coniva3='" . $coniva3 . "'
        where id_item='" . $_POST["_id_item"][$i] . "'";
        $compra->ExecuteTrans($instruccion);
    }

    $nro_compraOLD = $correlativos->getUltimoCorrelativo("cod_compra", 1, "no");
    $nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 1, "no");
    $compra->ExecuteTrans("update correlativos set contador = '" . $nro_compra . "' where campo = 'cod_compra'");
    $nro_compra -= 1;
    if ($compra->errorTransaccion == 1) {
        Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> compra Generada Exitosamente con el <b>Nro. " . $formateo_nro_compra . "</b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total: " . number_format($_POST["input_tciniva"], 2, ",", ".") . "</b><br><img src=\"../../libs/imagenes/cancelar.png\"> <b>Monto Por Pagar: " . number_format($_POST["input_totalizar_monto_cancelar"], 2, ",", ".") . "</b><br>Para imprimir la compra <a href=\"#\" onclick=\"window.open(\'../../reportes/rpt_compra.php?codigo=$formateo_nro_compra\');\">haga click aqui</a></span>");
    }
    if ($compra->errorTransaccion == 0) {
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la compra.</span>");
    }
    $compra->CommitTrans($compra->errorTransaccion);
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
    exit;
} else {

    if (!isset($_GET["cod"])) {
        header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"]);
        exit;
    }

    $compra->Execute2("delete from item_precompromiso where
        idSessionActualphp = '" . $login->getIdSessionActual() . "' and
        usuario_creacion = '" . $login->getUsuario() . "'");

    $nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 0, "si");
    $smarty->assign("nro_compra", $nro_compra);

    $dataproveedor = $proveedores->ObtenerFilasBySqlSelect("select * from proveedores where id_proveedor = " . $_GET["cod"]);


    if (count($dataproveedor) == 0) {
        $pagina .= "<html>";
        $pagina .= "<body style=\"background-color:#f8f8f8\">";
        $pagina .= "<div  style=\"background-color:#dcdedb; border: 1px solid black;-moz-border-radius: 8px;padding:5px; margin-left: 20%;margin-right: 20%;margin-top:5%;   font-size: 13px; \">";
        $pagina .= "<img src=\"../../libs/imagenes/configuracion.png\"> <b>Disculpe esta operacion esta permitida:</b> <br>
        <span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> Verifique que el proveedor al que desea comprar exista.</span><br>
            ";
        $pagina .= "<hr><span style=\"color:#1e6602\">Para mas información contacte al administrador.</span>";
        if (count($campos) > 0)
            $pagina .= "<br><span style=\"color:red\"><img style=\"border:none;\" src=\"../../libs/imagenes/ico_list.gif\"> Detalle del error:</span><br><b style=\"padding-left:30px;\"><img src=\"../../libs/imagenes/ico_search.gif\"> Modulo:</b> " . $campos[0]["descripcion_optmenu"] . " - <b>Sección:</b> " . $campos[0]["descripcion_optseccion"] . " >> <b>" . $campos[0]["opt_subseccion"] . ":</b> " . $campos[0]["descripcion"];
        $pagina .= "<hr><br><br><a style=\"text-decoration:none;\" href='?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"] . "'><img style=\"border:none;\" src=\"../../libs/imagenes/ico_back.gif\"> Volver</a>";
        $pagina .= "</div>";
        $pagina .= "</body>";
        $pagina .= "</html>";
        echo utf8_decode($pagina);
        exit;
    }

    //CARGAMOS EL COMBO cod_vendedor
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $proveedores->ObtenerFilasBySqlSelect("select * from vendedor");
    foreach ($tprecio as $key => $item) {
        $valueSELECT[] = $item["cod_vendedor"];
        $outputSELECT[] = $item["nombre"];
    }
    $smarty->assign("option_values_vendedor", $valueSELECT);
    $smarty->assign("option_output_vendedor", $outputSELECT);
    $smarty->assign("option_selected_vendedor", $dataproveedor[0]["cod_vendedor"]);

    //CARGAMOS EL COMBO cod_zona
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $proveedores->ObtenerFilasBySqlSelect("select * from zonas");
    foreach ($tprecio as $key => $item) {
        $valueSELECT[] = $item["cod_zona"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_zona", $valueSELECT);
    $smarty->assign("option_output_zona", $outputSELECT);
    $smarty->assign("option_selected_zona", $dataproveedor[0]["cod_zona"]);



    //CARGAMOS EL COMBO COD_TIPO_proveedor
    $valueSELECT = "";
    $outputSELECT = "";
    $tproveedor = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_proveedor");
    foreach ($tproveedor as $key => $item) {
        $valueSELECT[] = $item["cod_tipo_proveedor"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_tipo_proveedor", $valueSELECT);
    $smarty->assign("option_output_tipo_proveedor", $outputSELECT);
    $smarty->assign("option_selected_tipo_proveedor", $dataproveedor[0]["cod_tipo_proveedor"]);


    //CARGAMOS EL COMBO COD_TIPO_PRECIO
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_precio");
    foreach ($tprecio as $key => $item) {
        $valueSELECT[] = $item["cod_tipo_precio"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_tipo_precio", $valueSELECT);
    $smarty->assign("option_output_tipo_precio", $outputSELECT);
    $smarty->assign("option_selected_tipo_precio", $dataproveedor[0]["cod_tipo_precio"]);

    //CARGAMOS EL COMBO contribuyente_especial
    $smarty->assign("option_values_contribuyente_especial", array(0, 1));
    $smarty->assign("option_output_contribuyente_especial", array("No", "Si"));
    $smarty->assign("option_selected_contribuyente_especial", $dataproveedor[0]["contribuyente_especial"]);

    //CARGAMOS EL COMBO calc_reten_impuesto_islr
    $smarty->assign("option_values_calc_reten_impuesto_islr", array(0, 1));
    $smarty->assign("option_output_calc_reten_impuesto_islr", array("No", "Si"));
    $smarty->assign("option_selected_calc_reten_impuesto_islr", $dataproveedor[0]["calc_reten_impuesto_islr"]);

    //CARGAMOS EL COMBO permitecredito
    $smarty->assign("option_values_permitecredito", array(0, 1));
    $smarty->assign("option_output_permitecredito", array("No", "Si"));
    $smarty->assign("option_selected_permitecredito", $dataproveedor[0]["permitecredito"]);

    $smarty->assign("dataproveedor", $dataproveedor);

    $datos_almacen = $proveedores->ObtenerFilasBySqlSelect("select * from almacen");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_almacen as $key => $item) {
        $valueSELECT[] = $item["cod_almacen"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_almacen", $valueSELECT);
    $smarty->assign("option_output_almacen", $outputSELECT);
    $datos_item_forma = $proveedores->ObtenerFilasBySqlSelect("select * from item_forma where cod_item_forma in (1,2)");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_item_forma as $key => $item) {
        $valueSELECT[] = $item["cod_item_forma"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_item_forma", $valueSELECT);
    $smarty->assign("option_output_item_forma", $outputSELECT);

    $datos_banco = $proveedores->ObtenerFilasBySqlSelect("select * from banco order by descripcion");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_banco as $key => $item) {
        $valueSELECT[] = $item["cod_banco"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_banco", $valueSELECT);
    $smarty->assign("option_output_banco", $outputSELECT);

    $datos_instrumento_pago = $proveedores->ObtenerFilasBySqlSelect("select * from instrumentopago_formapago where cod_funcioninstrumento in ( 1,2) order by descripcion");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_instrumento_pago as $key => $item) {
        $valueSELECT[] = $item["cod_formapago"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_instrumento_pago_tarjeta", $valueSELECT);
    $smarty->assign("option_output_instrumento_pago_tarjeta", $outputSELECT);


    $datos_tipodocumento = $proveedores->ObtenerFilasBySqlSelect("select * from instrumentopago_formapago  order by descripcion");
    $valueSELECT = "";
    $outputSELECT = "";
    foreach ($datos_tipodocumento as $key => $item) {
        $valueSELECT[] = $item["cod_formapago"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_tipo_otrodocumento", $valueSELECT);
    $smarty->assign("option_output_tipo_otrodocumento", $outputSELECT);
}

$smarty->assign("idpreciolibre", codTipoPrecioLibre);
$smarty->assign("idprecio1", codTipoPrecio1);
$smarty->assign("idprecio2", codTipoPrecio2);
$smarty->assign("idprecio3", codTipoPrecio3);

$smarty->assign("responsable", utf8_decode($login->getNombreApellidoUSuario()));
$smarty->assign("usuario", $login->getUsuario());
?>
