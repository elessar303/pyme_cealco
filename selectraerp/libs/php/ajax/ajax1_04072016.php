<?php

session_start();
ini_set("display_errors", 1);

require_once("../../../libs/php/adodb5/adodb.inc.php");
require_once("../../../libs/php/configuracion/config.php");
require_once("../../../libs/php/clases/ConexionComun.php");
require_once("../../../libs/php/clases/comunes.php");
require_once("../../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../../libs/php/clases/correlativos.php");
require_once "../../../libs/php/clases/numerosALetras.class.php";
include("../../../../menu_sistemas/lib/common.php");
include("../../../../generalp.config.inc.php");
include("../../../../general.config.inc.php");


if (isset($_GET["opt"]) == true || isset($_POST["opt"]) == true) {
    $conn = new ConexionComun();
    $login = new Login();
    $opt = (isset($_GET["opt"])) ? $_GET["opt"] : $_POST["opt"];

    switch ($opt) {
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
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT almaexi.* FROM vw_existenciabyalmacen almaexi JOIN parametros_generales pg ON pg.cod_almacen = almaexi.cod_almacen WHERE id_item = '" . $_GET["v1"] . "' and cantidad > 0");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;

        case "verificarExistenciaItemByAlmacen":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_item_precomprometidos WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."'");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "precomprometeritem":
            
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
		
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT grupo.cantidad_rest, grupo.dias_rest, grupo.cod_grupo FROM grupo INNER JOIN item ON grupo.cod_grupo = item.cod_grupo WHERE item.id_item = '".$_GET["v1"]."' ");
                $cantidad = $campos[0]["cantidad_rest"];
                $dias = $campos[0]["dias_rest"];
		$grupo = $campos[0]["cod_grupo"];
                $fecha = date('Y-m-d');
                $nuevafecha = strtotime ( "-".$dias." day" , strtotime ( $fecha ) ) ;
                $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
		
		//echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura join item on item.id_item=factura_detalle.id_item WHERE item.cod_grupo = '".$grupo."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between  '$nuevafecha' and '$fecha'";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura join item on item.id_item=factura_detalle.id_item WHERE item.cod_grupo = '".$grupo."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between  '$nuevafecha' and '$fecha'");
                $cantidadComprada = $campos[0]["cantidad"];

               // echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura WHERE id_item = '".$_GET["v1"]."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between '$nuevafecha' and '$fecha' ";
               // exit;

                if($cantidadComprada>=$cantidad)
                {
                    echo "[{'id':'-98','observacion':'Este cliente a alcazado el limite de compra por persona.'}]";
                    exit;
                }
            }
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_item_precomprometidos
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
        case "det_items":
            if ($_GET["id_tipo_movimiento_almacen"] == '3' || $_GET["id_tipo_movimiento_almacen"] == '1') {
                $operacion = "Entrada";

                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad AS cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
        FROM kardex_almacen_detalle AS kad JOIN kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id LEFT JOIN item AS ite ON kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            } else if ($_GET["id_tipo_movimiento_almacen"] == '4' || $_GET["id_tipo_movimiento_almacen"] == '2' || $_GET["id_tipo_movimiento_almacen"] == '8') {
                $operacion = "Salida";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item, ubi.descripcion as ubicacion, alm.descripcion as almacen
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            } else if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                $operacion = "Traslado";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
        from kardex_almacen_detalle as kad left join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
                $campos1 = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            }
//$campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
//from kardex_almacen_detalle as kad left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item WHERE id_transaccion = ".$_GET["id_transaccion"]);
//echo $campos;
            if (count($campos) == 0) {
                exit;
            }

            if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                echo '<tr class="detalle_items">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="width:110px; font-weight: bold; text-align: center;">ID</th>
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
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="width:110px; font-weight: bold; text-align: center;">ID</th>
                        <th style="width:150px; font-weight: bold;">Almac&eacute;n ' . $operacion . '</th>
                        <th style="width:150px; font-weight: bold;">Ubicaci&oacute;n' . $operacion . '</th>
                        <th style="width:300px; font-weight: bold;">Item</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                    </thead>
                    <tbody>';
            }

            foreach ($campos as $key => $item) {
                if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["id_transaccion_detalle"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["almacen"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["ubicacion"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["almacen"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["ubicacion"] . '</td>
                            <td style="width:300px;">' . $item["descripcion1"] . '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                        </tr>';
                } else {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["id_transaccion_detalle"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["almacen"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["ubicacion"] . '</td>
                            <td style="width:300px; padding-left:10px;">' . $item["descripcion1"] . '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
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
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Realizar Entrada</td>
                                    <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
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

            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM almacen");

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
            case "getPuntos":
            $bdCentral=DB_REPORTE_CENTRAL;
            $estado=$_GET["estados"];
            $sql="SELECT `nombre_punto`,codigo_siga_punto as siga  from $bdCentral.puntos_venta where estatus='A'";
            if($estado!=0){
                $sql.=" AND codigo_estado_punto=$estado";
            } 
            $campos = $conn->ObtenerFilasBySqlSelect($sql);

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }

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
            case "reporte_productoCentral":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $producto = $_GET["producto"];
                    $categoria = $_GET["categoria"];                   
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $tipo_punto = $_GET["tipo_punto"];
                    $tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    $marca_producto = $_GET["marca_producto"];
                    
                    $anno_des=substr($_GET["desde"],2,2); 
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);
                   
                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{
                       
                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;




                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                   $sql="SELECT v.CODE,(i.pesoxunidad*um.transformar*SUM(v.UNITS))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto,
                   SUM(v.UNITS) as UNITS, SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,
                   SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                    INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    INNER JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    WHERE v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";

                    if($punto!="0"){
                        $sql.=" and v.codigo_siga='$punto'";
                        //$sql_cod_barras.=" and v.codigo_siga='$punto'";
                    }      
                    if($estado!="0"){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                        //$sql_cod_barras.=" and pv.codigo_estado_punto='$estado'";
                    }
                    if($producto!="0"){
                        $sql.=" and v.CODE='$producto'";
                        //$sql_cod_barras.=" and v.CODE='$producto'";
                    }
                    if($categoria!="0"){
                       
                        $sql.=" and v.category='$categoria'";
                        //$sql_cod_barras.=" and v.category='$categoria'";
                    }
                    if($tipo_punto!=""){
                       
                        $sql.=" and ptp.id_tipo ='$tipo_punto'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }   
                    if($tipo_almacenamiento!=""){
                       
                        $sql.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                        //$sql_cod_barras.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }
                    if($marca_producto!="0"){
                       
                        $sql.=" and mc.id ='$marca_producto'";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
   
                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto"; Para ver el query y luego probarlo en el PHPMYADMIN
                    $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    //$sql_cod_barras.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);

                    
                  
                    if(count($reporte)>0){
                    ?>  
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">generar excel</div>
                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="9" align="center"><b><font color="white">REPORTE DE VENTAS POR PRODUCTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="35%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="5%" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">PRECIO UND SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">PRECIO UND CON IVA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">IVA</font></b></td>
                        </tr>

                    <?php
                        $listaCodeBarras="";
                        
                        foreach ($reporte as $lista ) {
                            $totalUni+=$lista["UNITS"];
                            $totalTone+=$lista["TONELAJE"];
                            $totalSinIva+=$lista["TOTALSINIVA"];
                            $totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            $listaCodeBarras.=$lista["CODE"].",";

                            //$totalSeco+=$lista['CANTSECO'];
                            
                     ?>
                        <tr>
                            <td width="10%"><?php echo $lista["CODE"] ?></td>
                            <td width="30%" align="center"><?php echo utf8_encode($lista["nombre_producto"])  ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["TONELAJE"], 3, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["TOTALSINIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["TOTALCONIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["PRICESELL"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["PRECIOIVA"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php echo $lista["rate"] ?></td>                
                        </tr>  

                    <?php
                             
                        }
                        $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>  
                     <tr>
                            <td width="10%"></td>
                            <td width="30%" align="center"><b>TOTALES</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalTone, 3, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalSinIva, 2, ',', '.');  ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalConIva, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?/*php echo number_format($totalPdSinIva, 2, ',', '.'); */?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>                
                    </tr>
                    <tr>
                        <td colspan="9"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="30%" align="center"><b>TOTAL PRODUCTOS SECOS</b></td>
                        <td width="5%" align="center"><b>
                            <?php 


                                /*Este es el SCRIPT ORIGINAL....!!!!
                                $sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND pv.codigo_estado_punto='$estado'
                                AND tipo_almacenamiento = 'SECO'
                                ";*/
                                $sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND i.codigo_barras IN ($listaCodeBarras)
                                AND i.tipo_almacenamiento ='SECO'
                                ";

                                //$varcero = 0;
                                /*Se le concatena cada uno de los items y tomarlos en cuenta al momento de realizar
                                 consultas especificas*/
                                if($estado!="0"){
                                    $sql_seco.=" and pv.codigo_estado_punto='$estado'";
                                }
                                if($punto!="0"){
                                    $sql_seco.=" and v.codigo_siga='$punto'";
                                }
                                if($categoria!="0"){
                                    $sql_seco.=" and v.category='$categoria'";
                                }
                                if($producto!="0"){
                                    $sql_seco.=" and v.CODE='$producto'";
                                }
                                if($tipo_punto!=""){
                                    $sql_seco.=" and ptp.id_tipo ='$tipo_punto'";
                                }

                                //print_r($listaCodeBarras);
                                $sql_seco;
                                //exit();
                                /*Se coloca un COUNT que sera igual a 1 (uno) y sino esta vacio '' imprima la variable $valor*/
                                $valor = "0";
                                $reporteSeco = $conn->ObtenerFilasBySqlSelect($sql_seco);
                                $cantidadSeco = count($reporteSeco);
                                if($reporteSeco[0]['totalUnits']>0){

                                    echo $reporteSeco[0]['totalUnits'];

                                }elseif($reporteSeco[0]['totalUnits']==''){

                                    echo $valor;

                                }
                                //print("Hola");
                                /*al llenar un array con "ObtenerFilasBySqlSelect" lo llena de forma matricial
                                por lo que al imprimir el resultado hay que colocarlo [0]['campo_a_mostrar']*/
                                //$reporteSeco = $conn->ObtenerFilasBySqlSelect($sql_seco);
                                //echo $reporteSeco[0]['totalUnits'];
                               
                            ?>
                        </b></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="5%" align="center"></td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="30%" align="center"><b>TOTAL PRODUCTOS FRIOS</b></td>
                        <td width="5%" align="center"><b>
                            <?php 


                                /*Este es el SCRIPT ORIGINAL....!!!!
                                $sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND pv.codigo_estado_punto='$estado'
                                AND tipo_almacenamiento = 'SECO'
                                ";*/
                                $sql_frio = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND i.codigo_barras IN ($listaCodeBarras)
                                AND i.tipo_almacenamiento ='FRIO'
                                ";

                                //$varcero = 0;
                                /*Se le concatena cada uno de los items y tomarlos en cuenta al momento de realizar
                                 consultas especificas*/
                                if($estado!="0"){
                                    $sql_frio.=" and pv.codigo_estado_punto='$estado'";
                                }
                                if($punto!="0"){
                                    $sql_frio.=" and v.codigo_siga='$punto'";
                                }
                                if($categoria!="0"){
                                    $sql_frio.=" and v.category='$categoria'";
                                }
                                if($producto!="0"){
                                    $sql_frio.=" and v.CODE='$producto'";
                                }
                                if($tipo_punto!=""){
                                    $sql_frio.=" and ptp.id_tipo ='$tipo_punto'";
                                }

                                /*Se coloca un COUNT que sera igual a 1 (uno) y sino esta vacio '' imprima la variable $valor*/
                                $valor = "0";
                                $reporteFrio = $conn->ObtenerFilasBySqlSelect($sql_frio);
                                $cantidadFrio = count($reporteFrio);
                                if($reporteFrio[0]['totalUnits']>0){

                                    echo $reporteFrio[0]['totalUnits'];

                                }elseif($reporteFrio[0]['totalUnits']==''){

                                    echo $valor;

                                }
                               
                            ?>
                        </b></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="5%" align="center"></td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="30%" align="center"></td>
                        <td width="5%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
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

            }// termina el else de las fechas iguales

            break;

            case "reporte_productoCentral_acumulado":
                    $estado = $_GET["estados"];                  
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    
                    $anno_des=substr($_GET["desde"],2,2); 
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);
                   
                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{
                       
                    $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_xproducto";

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                   
                    $sql_estados="SELECT * FROM $BD_central.estados ";
                    if($estado!="0"){
                                    $sql_estados.=" WHERE codigo_estado='$estado'";
                    }
                    $sql_estados.="ORDER BY codigo_estado limit 25";



                    $reporte_estados = $conn->ObtenerFilasBySqlSelect($sql_estados);
                    
                    ?>
                     <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">generar excel</div>
                     <?php
                     ob_start();
                     ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="5" align="center"><b><font color="white">REPORTE DE VENTAS POR PRODUCTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="35%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                        </tr>
                    <?php
                    $i=1;
                    foreach ($reporte_estados as $lista_estado ) {

                    $sql="SELECT et.nombre_estado, (i.pesoxunidad*um.transformar*SUM(v.cantidad))/1000000 as TONELAJE,
                   	SUM(v.cantidad) as UNITS, SUM(v.precio*v.cantidad) as TOTALSINIVA,
                   	SUM((v.precio*(i.iva/100)+v.precio)*v.cantidad) as TOTALCONIVA             
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.punto_venta
                    INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                    INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    INNER JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    INNER JOIN $BD_central.estados et on et.codigo_estado = pv.codigo_estado_punto
                    WHERE v.fecha BETWEEN '$desde' and '$hasta'
                    ";

                    //echo $sql; exit();
 
                    if($tipo_almacenamiento!="0"){
                       
                        $sql.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }
                        $sql.=" and pv.codigo_estado_punto='".$lista_estado['codigo_estado']."'";

   					
                    $sql.=" GROUP BY v.cod_bar"; 


                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                    //if(count($reporte)>0){
                    ?>  
                      
            
                    <?php
                        
                    ?>
                    

                    <?php
                        $listaCodeBarras="";
                        
                        foreach ($reporte as $lista ) {
                            $totalTone+=$lista["TONELAJE"]; if(count($reporte)==0){$totalTone=0;}
                            $totalSinIva+=$lista["TOTALSINIVA"];if(count($reporte)==0){$totalSinIva=0;}
                            $totalConIva+=$lista["TOTALCONIVA"];if(count($reporte)==0){$totalConIva=0;}
                        }
                        $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>  
                     <tr>
                            <td width="5%" align="center"><b><?php echo $i; ?></b></td>
                            <td width="30%" align="center"><b><?php echo $lista_estado['nombre_estado'] ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalTone, 3, ',', '.');} ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalSinIva, 2, ',', '.');}  ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalConIva, 2, ',', '.');} ?></b></td>      
                    </tr>
                    
                    <?php
                    $totalTone_fin=$totalTone_fin+$totalTone;
                    $totalSinIva_fin=$totalSinIva_fin+$totalSinIva;
                    $totalConIva_fin=$totalConIva_fin+$totalConIva;
                    $totalTone='';
                    $totalSinIva='';
                    $totalConIva='';
                	//}
  
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
                                var titulo_reporte='Reporte Venta Productos (Acumulado)';
                                window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;   
                            });
                        </script>

                        <?php
                            $i++;

		              }	?>
                      <tr>
                        <td colspan="5"><hr color="black" size="2" /></td>
                    </tr>
                      <tr bgcolor="#D8D8D8">
                      <td align="center" width="5%"></td>
                    <td align="center"><b>TOTAL</b></td>
                    <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalTone_fin, 3, ',', '.');} ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalSinIva_fin, 2, ',', '.');}  ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalConIva_fin, 2, ',', '.');} ?></b></td>
                    </tr>

                    </table>
                    <?php
                                      $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
            }// termina el else de las fechas iguales

            break;

            case "reporte_establecimientoCentral_acumulado":
                    $estado = $_GET["estados"];                  
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    
                    $anno_des=substr($_GET["desde"],2,2); 
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);
                   
                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{
                       
                    $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_xproducto";
                    $ventas_mes_anno_cliente="ventas_".$mes_des."_".$anno_des."_cliente";

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                   //SELECCION DE LOS ESTADOS
                    $sql_estados="SELECT * FROM $BD_central.estados where codigo_estado<>'0010'";
                    if($estado!="0"){
                        $sql_estados.=" WHERE codigo_estado='$estado'";
                        $sql_puntos="SELECT * FROM $BD_central.puntos_venta WHERE codigo_estado_punto='$estado' AND estatus='A'
                        AND id_tipo!='7'";
                        $reporte_puntos = $conn->ObtenerFilasBySqlSelect($sql_puntos);
                    }
                    
                    $sql_estados.="ORDER BY codigo_estado limit 25";


                    $reporte_estados = $conn->ObtenerFilasBySqlSelect($sql_estados);

                    
                    ?>
                     <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">generar excel</div>
                     <?php
                     ob_start();
                     ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="7" align="center"><b><font color="white">REPORTE DE VENTAS POR ESTABLECIMIENTOS DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                       <!--  <tr bgcolor="#5084A9">
                            <td width="35%" align="center"><b><font color="white">ESTABLECIMIENTO</font></b></td>
                            <td width="35%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                        </tr> -->
                    <?php
                    if($estado==0){
                        ?>   <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="35%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CLIENTES ATENDIDOS</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CLIENTES AUTORIZADOS</font></b></td>
                        </tr>
                        <?php
                        $i=1;
                    foreach ($reporte_estados as $lista_estado ) {

                    /*$sql="SELECT pv.nombre_punto as nombre_punto,et.nombre_estado as nombre_estado,
                    (i.pesoxunidad*um.transformar*SUM(v.cantidad))/1000000 as TONELAJE,
                    SUM(v.cantidad) as UNITS, SUM(v.precio*v.cantidad) as TOTALSINIVA,
                    SUM((v.precio*(i.iva/100)+v.precio)*v.cantidad) as TOTALCONIVA
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.punto_venta
                    INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                    INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    INNER JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    INNER JOIN $BD_central.estados et on et.codigo_estado = pv.codigo_estado_punto
                    WHERE v.fecha BETWEEN '$desde' and '$hasta'
                    AND pv.estatus='A'
                    ";

                    //echo $sql; exit();
 
                    if($tipo_almacenamiento!="0"){
                       
                        $sql.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }
                    
                    $sql.=" and pv.codigo_estado_punto='".$lista_estado['codigo_estado']."'";
                    //$sql.=" pv.codigo_siga_punto='".$lista_estado['codigo_siga_punto']."'";

                    $sql.=" GROUP BY pv.nombre_punto ORDER BY et.nombre_estado";
                    //$sql.=" GROUP BY pv.nombre_punto ORDER BY pv.nombre_punto";
                    */

                    $sql="SELECT  T1.estado as nombre_estado, sum( T1.cantidadx ) as UNITS, sum( T1.TONELAJEx ) as TONELAJE, sum(T1.TOTALSINIVAx) as TOTALSINIVA, sum(T1.TOTALCONIVAx) as TOTALCONIVA
                            FROM ( SELECT est.nombre_estado as estado, i.cod_bar, ifnull(sum(i.cantidad),0) as cantidadx, 
                            ifnull((it.pesoxunidad*um.transformar*SUM(i.cantidad))/1000000,0) as TONELAJEx,
                            ifnull(SUM(i.precio*i.cantidad),0) as TOTALSINIVAx,
                            ifnull(SUM((i.precio*(it.iva/100)+i.precio)*i.cantidad),0) as TOTALCONIVAx
                        FROM $BD_central.$ventas_mes_anno i
                        LEFT JOIN $BD_central.puntos_venta pv ON i.punto_venta =  pv.codigo_siga_punto
                        LEFT JOIN $BD_selectrapyme.item it ON i.cod_bar = it.codigo_barras
                        LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                        LEFT JOIN $BD_selectrapyme.unidad_medida um on it.unidadxpeso = um.id
                        INNER JOIN $BD_central.estados est on est.codigo_estado = pv.codigo_estado_punto
                        WHERE i.fecha BETWEEN '$desde' and '$hasta' and pv.codigo_estado_punto ='$lista_estado[codigo_estado]' and pv.id_tipo<>'7' $cad1 $cad2 $cad3 
                        GROUP BY i.cod_bar
                        ) as T1
                        GROUP BY T1.estado 
                        ORDER BY nombre_estado asc ";


                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                    //if(count($reporte)>0){
                    ?>  
                      
            
                    <?php
                        $sql_clientes="SELECT sum(cliente) as clientes
                            FROM $BD_central.$ventas_mes_anno_cliente
                            WHERE fecha BETWEEN '$desde' and '$hasta'
                            AND codigo_siga IN 
                            (SELECT codigo_siga_punto FROM $BD_central.puntos_venta
                                WHERE estatus='A'
                                AND codigo_estado_punto='".$lista_estado['codigo_estado']."')";

                        $reporte_clientes = $conn->ObtenerFilasBySqlSelect($sql_clientes);

                        $totalClientes='0';
                        $totalClientesAutorizados='0';

                        foreach ($reporte_clientes as $key) {

                            $totalClientes+=$reporte_clientes[0]["clientes"];
                        }
                    ?>
                    

                    <?php
                        $listaCodeBarras="";
                        
                        //printf($reporte);
                        foreach ($reporte as $lista) {

                            /*$sql_clientes="SELECT sum(cliente) as clientes
                            FROM $BD_central.$ventas_mes_anno_cliente
                            WHERE fecha BETWEEN '$desde' and '$hasta'
                            AND codigo_siga IN 
                            (SELECT codigo_siga_punto FROM $BD_central.puntos_venta
                                WHERE codigo_estado_punto='".$lista_estado['codigo_estado']."')";*/

                            //$reporte_clientes = $conn->ObtenerFilasBySqlSelect($sql_clientes);
                            

                            $totalTone+=$lista["TONELAJE"];if(count($reporte)==0){$totalTone=0;}
                            $totalSinIva+=$lista["TOTALSINIVA"];if(count($reporte)==0){$totalSinIva=0;}
                            $totalConIva+=$lista["TOTALCONIVA"];if(count($reporte)==0){$totalConIva=0;}
                            //$totalClientes+=$reporte_clientes[0]["clientes"];
                        }
                        $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>  
                    <tr>
                        <td width="5%" align="center"><b><?php echo $i; ?></b></td>
                        <td width="30%" align="center"><b><?php echo $lista_estado['nombre_estado'] ?></b></td>
                        <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalTone, 3, ',', '.');} ?></b></td>
                        <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalSinIva, 2, ',', '.');}  ?></b></td>
                        <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalConIva, 2, ',', '.');} ?></b></td>
                        <td width="10%" align="center"><b><?php echo $totalClientes ?></b></td>
                        <td width="10%" align="center"><b><?php echo $totalClientesAutorizados ?></b></td>
                        
                    </tr>
                    
                    <?php
                    $totalTone_fin=$totalTone_fin+$totalTone;
                    $totalSinIva_fin=$totalSinIva_fin+$totalSinIva;
                    $totalConIva_fin=$totalConIva_fin+$totalConIva;
                    $totalCliente_fin=$totalCliente_fin+$totalClientes;
                    $totalTone='';
                    $totalSinIva='';
                    $totalConIva='';
                    $totalClientes='0';
                    //}
  
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
                                var titulo_reporte='Reporte Venta Productos (Acumulado)';
                                window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;   
                            });
                        </script>

                        <?php
                        $i++;
                      } ?>
                    <tr>
                        <td colspan="6"><hr color="black" size="2" /></td>
                    </tr>
                    <tr bgcolor="#D8D8D8">
                        <td width="5%"></td>
                        <td align="center"><b>TOTAL</b></td>
                        <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalTone_fin, 3, ',', '.');} ?></b></td>
                        <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalSinIva_fin, 2, ',', '.');}  ?></b></td>
                        <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalConIva_fin, 2, ',', '.');} ?></b></td>
                        <td width="10%" align="center"><b><?php echo number_format($totalCliente_fin, 0, ',', '.'); ?></b></td>
                    </tr>

                    </table>
                    <?php
                    }
                    if($estado!=0){
                         ?>   <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="35%" align="center"><b><font color="white">ESTABLECIMIENTO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CLIENTES ATENDIDOS</font></b></td>
                        </tr>
                        <?php
                        $j=1;
                        foreach ($reporte_puntos as $lista_estado ) {

                    $sql="SELECT pv.nombre_punto as nombre_punto,et.nombre_estado as nombre_estado,
                    (i.pesoxunidad*um.transformar*SUM(v.cantidad))/1000000 as TONELAJE,
                    SUM(v.cantidad) as UNITS, SUM(v.precio*v.cantidad) as TOTALSINIVA,
                    SUM((v.precio*(i.iva/100)+v.precio)*v.cantidad) as TOTALCONIVA,
                    pv.codigo_siga_punto as codigo_siga_punto
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.punto_venta
                    INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                    INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    INNER JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    INNER JOIN $BD_central.estados et on et.codigo_estado = pv.codigo_estado_punto
                    WHERE v.fecha BETWEEN '$desde' and '$hasta'
                    AND ptp.id_tipo!='7'
                    AND pv.estatus='A'
                    AND pv.codigo_siga_punto='".$lista_estado['codigo_siga_punto']."'
                    ";

                    //echo $sql; exit();
 
                    if($tipo_almacenamiento!="0"){
                       
                        $sql.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }
                    
                    //$sql.=" and pv.codigo_estado_punto='".$lista_estado['codigo_estado']."'";

                    
                   $sql.=" GROUP BY pv.nombre_punto ORDER BY et.nombre_estado";


                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                    //if(count($reporte)>0){
                    ?>  
                      
            
                    <?php
                        
                    ?>
                    

                    <?php
                        $listaCodeBarras="";
                        $totalClientes='0';
                       
                        foreach ($reporte as $lista ) {
                            $sql_clientes="SELECT sum(cliente) as clientes
                            FROM $BD_central.$ventas_mes_anno_cliente
                            WHERE fecha BETWEEN '$desde' and '$hasta'
                            AND codigo_siga='".$lista['codigo_siga_punto']."'
                            ";

                            $reporte_clientes = $conn->ObtenerFilasBySqlSelect($sql_clientes);

                            $totalTone+=$lista["TONELAJE"]; if(count($reporte)==0){$totalTone=0;}
                            $totalSinIva+=$lista["TOTALSINIVA"];if(count($reporte)==0){$totalSinIva=0;}
                            $totalConIva+=$lista["TOTALCONIVA"];if(count($reporte)==0){$totalConIva=0;}
                            $totalClientes+=$reporte_clientes[0]["clientes"];
                        }
                        $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>  
                    <tr>
                        <td width="5%" align="center"><b><?php echo $j; ?></b></td>
                        <td width="30%" align="center"><b><?php echo $lista_estado['nombre_punto'] ?></b></td>
                        <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalTone, 3, ',', '.');} ?></b></td>
                        <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalSinIva, 2, ',', '.');}  ?></b></td>
                        <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalConIva, 2, ',', '.');} ?></b></td>
                        <td width="10%" align="center"><b><?php echo $totalClientes ?></b></td>
                    </tr>
                    
                    <?php
                    $totalTone_fin=$totalTone_fin+$totalTone;
                    $totalSinIva_fin=$totalSinIva_fin+$totalSinIva;
                    $totalConIva_fin=$totalConIva_fin+$totalConIva;
                    $totalCliente_fin=$totalCliente_fin+$totalClientes;
                    $totalTone='';
                    $totalSinIva='';
                    $totalConIva='';
                    $totalClientes='0';
                    //}
  
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
                                var titulo_reporte='Reporte Venta Productos (Acumulado)';
                                window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;   
                            });
                        </script>

                        <?php
                        $j++;
                      } ?>
                    <tr>
                        <td colspan="6"><hr color="black" size="2" /></td>
                    </tr>
                    <tr bgcolor="#D8D8D8">
                        <td width="5%"></td>
                        <td align="center"><b>TOTAL</b></td>
                        <td width="10%" align="center"><b><?php echo number_format($totalTone_fin, 3, ',', '.'); ?></b></td>
                        <td width="10%" align="center"><b><?php echo number_format($totalSinIva_fin, 2, ',', '.');  ?></b></td>
                        <td width="10%" align="center"><b><?php echo number_format($totalConIva_fin, 2, ',', '.'); ?></b></td>
                        <td width="10%" align="center"><b><?php echo number_format($totalCliente_fin, 0, ',', '.'); ?></b></td>
                    </tr>
                    
                    </table>
                    <?php

                    }
                                      $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
            }// termina el else de las fechas iguales

            break;

            case "reporte_establecimientoCentral_acumulado2":
                    $estado = $_GET["estados"];                  
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    
                    $anno_des=substr($_GET["desde"],2,2); 
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);
                   
                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{
                       
                    $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_xproducto";
                    $ventas_mes_anno_cliente="ventas_".$mes_des."_".$anno_des."_cliente";

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                   //SELECCION DE LOS ESTADOS
                    $sql_estados="SELECT * FROM $BD_central.estados ";
                    if($estado!="0"){
                        $sql_estados.=" WHERE codigo_estado='$estado'";
                        $sql_puntos="SELECT * FROM $BD_central.puntos_venta WHERE codigo_estado_punto='$estado' AND estatus='A'
                        AND id_tipo!='7' order by codigo_estado_punto";
                        $reporte_puntos = $conn->ObtenerFilasBySqlSelect($sql_puntos);
                    }
                    else
                    {
                        //$sql_estados.=" WHERE codigo_estado='$estado'";
                        $sql_puntos="SELECT * FROM $BD_central.puntos_venta a, $BD_central.estados b WHERE  estatus='A'
                        AND id_tipo!='7' AND a.codigo_estado_punto=b.codigo_estado order by codigo_estado_punto";
                        $reporte_puntos = $conn->ObtenerFilasBySqlSelect($sql_puntos);
                        $estado = 3333;
                    }
                    
                    $sql_estados.="ORDER BY codigo_estado limit 25";


                    $reporte_estados = $conn->ObtenerFilasBySqlSelect($sql_estados);

                    
                    ?>
                     <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">generar excel</div>
                     <?php
                     ob_start();
                     ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="7" align="center"><b><font color="white">REPORTE DE VENTAS POR ESTABLECIMIENTOS DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                       <!--  <tr bgcolor="#5084A9">
                            <td width="35%" align="center"><b><font color="white">ESTABLECIMIENTO</font></b></td>
                            <td width="35%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                        </tr> -->
                    <?php
                    if($estado==9999)
                    {
                        ?>   <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="35%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CLIENTES ATENDIDOS</font></b></td>
                        </tr>
                        <?php
                        $i=1;
                        foreach ($reporte_estados as $lista_estado ) {

                        $sql="SELECT pv.nombre_punto as nombre_punto,et.nombre_estado as nombre_estado,
                        (i.pesoxunidad*um.transformar*SUM(v.cantidad))/1000000 as TONELAJE,
                        SUM(v.cantidad) as UNITS, SUM(v.precio*v.cantidad) as TOTALSINIVA,
                        SUM((v.precio*(i.iva/100)+v.precio)*v.cantidad) as TOTALCONIVA
                        FROM $BD_central.$ventas_mes_anno v
                        INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.punto_venta
                        INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                        INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                        INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                        INNER JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                        INNER JOIN $BD_central.estados et on et.codigo_estado = pv.codigo_estado_punto
                        WHERE v.fecha BETWEEN '$desde' and '$hasta'
                        AND pv.estatus='A'
                        ";

                        //echo $sql; exit();
     
                        if($tipo_almacenamiento!="0"){
                           
                            $sql.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                        }
                        
                        $sql.=" and pv.codigo_estado_punto='".$lista_estado['codigo_estado']."'";
                        //$sql.=" pv.codigo_siga_punto='".$lista_estado['codigo_siga_punto']."'";

                        $sql.=" GROUP BY pv.nombre_punto ORDER BY et.nombre_estado";
                        //$sql.=" GROUP BY pv.nombre_punto ORDER BY pv.nombre_punto";


                        $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                        //if(count($reporte)>0){
                        ?>  
                          
                
                        <?php
                            $sql_clientes="SELECT sum(cliente) as clientes
                                FROM $BD_central.$ventas_mes_anno_cliente
                                WHERE fecha BETWEEN '$desde' and '$hasta'
                                AND codigo_siga IN 
                                (SELECT codigo_siga_punto FROM $BD_central.puntos_venta
                                    WHERE estatus='A'
                                    AND codigo_estado_punto='".$lista_estado['codigo_estado']."')";

                            $reporte_clientes = $conn->ObtenerFilasBySqlSelect($sql_clientes);

                            $totalClientes='0';

                            foreach ($reporte_clientes as $key) {

                                $totalClientes+=$reporte_clientes[0]["clientes"];
                            }
                        ?>
                        

                        <?php
                            $listaCodeBarras="";
                            
                            //printf($reporte);
                            foreach ($reporte as $lista) {

                                /*$sql_clientes="SELECT sum(cliente) as clientes
                                FROM $BD_central.$ventas_mes_anno_cliente
                                WHERE fecha BETWEEN '$desde' and '$hasta'
                                AND codigo_siga IN 
                                (SELECT codigo_siga_punto FROM $BD_central.puntos_venta
                                    WHERE codigo_estado_punto='".$lista_estado['codigo_estado']."')";*/

                                //$reporte_clientes = $conn->ObtenerFilasBySqlSelect($sql_clientes);
                                

                                $totalTone+=$lista["TONELAJE"];if(count($reporte)==0){$totalTone=0;}
                                $totalSinIva+=$lista["TOTALSINIVA"];if(count($reporte)==0){$totalSinIva=0;}
                                $totalConIva+=$lista["TOTALCONIVA"];if(count($reporte)==0){$totalConIva=0;}
                                //$totalClientes+=$reporte_clientes[0]["clientes"];
                            }
                            $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                        ?>  
                        <tr>
                            <td width="5%" align="center"><b><?php echo $i; ?></b></td>
                            <td width="30%" align="center"><b><?php echo $lista_estado['nombre_estado'] ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalTone, 3, ',', '.');} ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalSinIva, 2, ',', '.');}  ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalConIva, 2, ',', '.');} ?></b></td>
                            <td width="10%" align="center"><b><?php echo $totalClientes ?></b></td>
                            
                        </tr>
                        
                        <?php
                        $totalTone_fin=$totalTone_fin+$totalTone;
                        $totalSinIva_fin=$totalSinIva_fin+$totalSinIva;
                        $totalConIva_fin=$totalConIva_fin+$totalConIva;
                        $totalCliente_fin=$totalCliente_fin+$totalClientes;
                        $totalTone='';
                        $totalSinIva='';
                        $totalConIva='';
                        $totalClientes='0';
                        //}
      
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
                                    var titulo_reporte='Reporte Venta Productos (Acumulado)';
                                    window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;   
                                });
                            </script>

                            <?php
                            $i++;
                          } ?>
                        <tr>
                            <td colspan="7"><hr color="black" size="2" /></td>
                        </tr>
                        <tr bgcolor="#D8D8D8">
                            <td width="5%"></td>
                            <td align="center"><b>TOTAL</b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalTone_fin, 3, ',', '.');} ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalSinIva_fin, 2, ',', '.');}  ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalConIva_fin, 2, ',', '.');} ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalCliente_fin, 0, ',', '.'); ?></b></td>
                        </tr>

                        </table>
                        <?php
                    }
                    if($estado!=0)
                    {
                         ?>   <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="5%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="35%" align="center"><b><font color="white">ESTABLECIMIENTO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CLIENTES ATENDIDOS</font></b></td>
                        </tr>
                        <?php
                        $j=1;
                        foreach ($reporte_puntos as $lista_estado ) {

                        /*$sql="SELECT pv.nombre_punto as nombre_punto,et.nombre_estado as nombre_estado,
                        (i.pesoxunidad*um.transformar*SUM(v.cantidad))/1000000 as TONELAJE,
                        SUM(v.cantidad) as UNITS, SUM(v.precio*v.cantidad) as TOTALSINIVA,
                        SUM((v.precio*(i.iva/100)+v.precio)*v.cantidad) as TOTALCONIVA,
                        pv.codigo_siga_punto as codigo_siga_punto
                        FROM $BD_central.$ventas_mes_anno v
                        INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.punto_venta
                        INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                        INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                        INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                        INNER JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                        INNER JOIN $BD_central.estados et on et.codigo_estado = pv.codigo_estado_punto
                        WHERE v.fecha BETWEEN '$desde' and '$hasta'
                        AND pv.estatus='A'
                        AND pv.codigo_siga_punto='".$lista_estado['codigo_siga_punto']."'
                        ";

                        //echo $sql; exit();
     
                        if($tipo_almacenamiento!="0"){
                           
                            $cad1 =" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                        }
                        
                        //$sql.=" and pv.codigo_estado_punto='".$lista_estado['codigo_estado']."'";

                        
                        $sql.=" GROUP BY pv.nombre_punto ORDER BY et.nombre_estado";*/


                        $sql="SELECT  T1.codigo_siga_puntox as codigo_siga_punto, T1.nombre_puntox as nombre_punto, T1.estado as nombre_estado, sum( T1.cantidadx ) as UNITS, sum( T1.TONELAJEx ) as TONELAJE, sum(T1.TOTALSINIVAx) as TOTALSINIVA, sum(T1.TOTALCONIVAx) as TOTALCONIVA
                            FROM ( SELECT pv.codigo_siga_punto as codigo_siga_puntox, pv.nombre_punto as nombre_puntox, est.nombre_estado as estado, i.cod_bar, ifnull(sum(i.cantidad),0) as cantidadx, 
                            ifnull((it.pesoxunidad*um.transformar*SUM(i.cantidad))/1000000,0) as TONELAJEx,
                            ifnull(SUM(i.precio*i.cantidad),0) as TOTALSINIVAx,
                            ifnull(SUM((i.precio*(it.iva/100)+i.precio)*i.cantidad),0) as TOTALCONIVAx
                        FROM $BD_central.$ventas_mes_anno i
                        LEFT JOIN $BD_central.puntos_venta pv ON i.punto_venta =  pv.codigo_siga_punto
                        LEFT JOIN $BD_selectrapyme.item it ON i.cod_bar = it.codigo_barras
                        LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                        LEFT JOIN $BD_selectrapyme.unidad_medida um on it.unidadxpeso = um.id
                        INNER JOIN $BD_central.estados est on est.codigo_estado = pv.codigo_estado_punto
                        WHERE i.fecha BETWEEN '$desde' and '$hasta' and pv.codigo_siga_punto ='$lista_estado[codigo_siga_punto]' $cad1  
                        GROUP BY i.cod_bar
                        ) as T1
                        GROUP BY T1.nombre_puntox 
                        ORDER BY nombre_estado asc ";


                        $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                        //if(count($reporte)>0){
                        ?>  
                          
                
                        <?php
                            
                        ?>
                        

                        <?php
                            $listaCodeBarras="";
                            $totalClientes='0';
                           
                            foreach ($reporte as $lista ) {
                                $sql_clientes="SELECT sum(cliente) as clientes
                                FROM $BD_central.$ventas_mes_anno_cliente
                                WHERE fecha BETWEEN '$desde' and '$hasta'
                                AND codigo_siga='".$lista['codigo_siga_punto']."'
                                ";

                                $reporte_clientes = $conn->ObtenerFilasBySqlSelect($sql_clientes);

                                $totalTone+=$lista["TONELAJE"]; if(count($reporte)==0){$totalTone=0;}
                                $totalSinIva+=$lista["TOTALSINIVA"];if(count($reporte)==0){$totalSinIva=0;}
                                $totalConIva+=$lista["TOTALCONIVA"];if(count($reporte)==0){$totalConIva=0;}
                                $totalClientes+=$reporte_clientes[0]["clientes"];
                            }
                            $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                        ?>  
                        <tr>
                            <td width="5%" align="center"><b><?php echo $j; ?></b></td>
                            <td width="20%" align="center"><b><?php echo $lista_estado['nombre_estado'] ?></b></td>
                            <td width="30%" align="center"><b><?php echo $lista_estado['nombre_punto'] ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalTone, 3, ',', '.');} ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalSinIva, 2, ',', '.');}  ?></b></td>
                            <td width="10%" align="center"><b><?php if(count($reporte)==0){echo "0,00";}else{echo number_format($totalConIva, 2, ',', '.');} ?></b></td>
                            <td width="10%" align="center"><b><?php echo $totalClientes ?></b></td>
                        </tr>
                        
                        <?php
                        $totalTone_fin=$totalTone_fin+$totalTone;
                        $totalSinIva_fin=$totalSinIva_fin+$totalSinIva;
                        $totalConIva_fin=$totalConIva_fin+$totalConIva;
                        $totalCliente_fin=$totalCliente_fin+$totalClientes;
                        $totalTone='';
                        $totalSinIva='';
                        $totalConIva='';
                        $totalClientes='0';
                        //}
      
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
                                    var titulo_reporte='Reporte Venta Productos (Acumulado)';
                                    window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;   
                                });
                            </script>

                            <?php
                            $j++;
                          } ?>
                        <tr>
                            <td colspan="7"><hr color="black" size="2" /></td>
                        </tr>
                        <tr bgcolor="#D8D8D8">
                            <td width="5%"></td>
                            <td width="5%"></td>
                            <td align="center"><b>TOTAL</b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalTone_fin, 3, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalSinIva_fin, 2, ',', '.');  ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalConIva_fin, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalCliente_fin, 0, ',', '.'); ?></b></td>
                        </tr>
                        
                        </table>
                        <?php

                        }
                                          $_SESSION['contenido'] = ob_get_contents();
                        ob_end_clean();

                        echo $_SESSION['contenido'];
            }// termina el else de las fechas iguales

            break;

            case "reporte_clienteCentral_acumulado":
                    $estado = $_GET["estados"];                  
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $punto = $_GET["punto"];
                    
                    $anno_des=substr($_GET["desde"],2,2); 
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);
                   
                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{
                       
                    $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_cliente";

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                   
                    $sql_estados="SELECT * FROM $BD_central.estados ";
                    if($estado!="0"){
                                    $sql_estados.=" WHERE codigo_estado='$estado'";
                    }
                    $sql_estados.="ORDER BY codigo_estado limit 25";
                    //echo $sql_estados;



                    $reporte_estados = $conn->ObtenerFilasBySqlSelect($sql_estados);
                    
                    ?>
                     <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">generar excel</div>
                     <?php
                     ob_start();
                     ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="3" align="center"><b><font color="white">REPORTE DE VENTAS POR CLIENTES ATENDIDOS DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="35%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CLIENTES</font></b></td>
                        </tr>
                    <?php
                    $i=1;
                    foreach ($reporte_estados as $lista_estado ) {

                    $sql="SELECT *, sum(cliente) as clientes
                    FROM $BD_central.$ventas_mes_anno v
                    LEFT JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    LEFT JOIN $BD_central.estados et on et.codigo_estado = pv.codigo_estado_punto
                    WHERE v.fecha BETWEEN '$desde' and '$hasta'
                    ";

                    //echo $sql; exit();
 
                       
                    $sql.=" and pv.codigo_estado_punto='".$lista_estado['codigo_estado']."'";
                    
                    
                    $sql.=" GROUP BY pv.codigo_estado_punto"; 

                    //echo $sql;

                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                    //if(count($reporte)>0){
                    ?>  
                    
                     <tr>
                        <td width="5%" align="center"><b><?php echo $i; ?></b></td>
                        <td width="30%" align="center"><b><?php echo $lista_estado['nombre_estado'] ?></b></td>
                        <td width="10%" align="center"><b><?php echo $reporte[0]['clientes'] ?></b></td>      
                    </tr>
                    
                    <?php
                    $total_clientes=$total_clientes+$reporte[0]['clientes'];
                    //}
  
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
                                var titulo_reporte='Reporte Venta Clientes Atendidos (Acumulado)';
                                window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;   
                            });
                        </script>

                        <?php
                        $i++;
                      } ?>
                      <tr>
                        <td colspan="3"><hr color="black" size="2" /></td>
                    </tr>
                    <tr bgcolor="#D8D8D8">
                        <td width="5%"></td>
                    <td align="center"><b>TOTAL</b></td>
                    <td width="10%" align="center"><b><?php echo $total_clientes ?></b></td>
                    </tr>
                            
                    </table>
                    <?php
                                      $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
            }// termina el else de las fechas iguales

            break;

            case "reporte_movimientoCentral":
                    $punto = $_GET["punto"];
                    //$estado = $_GET["estados"];
                    //$producto = $_GET["producto"];
                    $fecha = $_GET["fecha"];
                    $movimiento = $_GET["movimiento"];
                    $tipo_punto = $_GET["tipo_punto"];

                    $anno_des=substr($_GET["fecha"],2,2); 
                    $mes_des=substr($_GET["fecha"],5,2);
                    $des_punto = substr($_GET['punto'],3,3);

                    $kardex_mes_anno="kardex_".$mes_des."_".$anno_des;

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*$sql="SELECT  i.* ,p.descripcion1,pv.codigo_estado_punto,pv.nombre_punto
                    FROM $BD_central.inventario i
                    INNER JOIN $BD_selectrapyme.item p on  i.codigo_barra=p.codigo_barras
                    INNER JOIN $BD_central.puntos_venta pv on i.siga=substring(pv.codigo_siga_punto,4)
                    where fecha='$fecha'";*/

                    $sql="SELECT *,i.cantidad as can_2, i.codigo_barras as codigo,
                    mc.marca as marca, um.nombre_unidad as nombre_unidad, it.pesoxunidad as pesoxunidad
                    FROM $BD_central.$kardex_mes_anno i
                    LEFT JOIN $BD_central.puntos_venta pv ON i.codigo_siga = SUBSTRING( pv.codigo_siga_punto, 4 ) 
                    LEFT JOIN $BD_selectrapyme.item it ON i.codigo_barras = it.codigo_barras
                    LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    LEFT JOIN $BD_selectrapyme.unidad_medida um ON it.unidadxpeso = um.id
                    LEFT JOIN $BD_selectrapyme.marca mc ON mc.id = it.id_marca
                    WHERE i.fecha = '".$fecha."'
                    AND i.codigo_siga = ".$des_punto."";
                 
                    if($movimiento!="0"){
                        $sql.=" and i.tipo_movimiento='$movimiento'";
                    }
                    if($tipo_punto!="0"){
                        $sql.=" and ptp.id_tipo ='$tipo_punto'";
                    }

                    /*if($punto!="0"){
                        $sql.=" and i.siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                    }  
                    if($producto!=""){
                        $sql.=" and i.codigo_barra='$producto'";
                    }*/
                   
   

                    $sql.="  ORDER BY i.codigo_siga, it.descripcion1"; 
                    //echo $sql;
                              
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                    
                    if(count($reporte)>0){
                    ?>  
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">generar excel</div>
            
                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="8" align="center"><b><font color="white">REPORTE DE INVENTARIO DEL: <?php echo $fecha; ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="15%" align="center"><b><font color="white">AUTORIZADO POR</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CODIGO DE BARRAS</font></b></td>
                            <td width="30%" align="center"><b><font color="white">PRODUCTO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">UNIDADES</font></b></td>
                            <td width="10%" align="center"><b><font color="white">MOVIMIENTO</font></b></td>
                            <td width="15%" align="center"><b><font color="white">UBICACION ENTRADA</font></b></td>
                            <td width="15%" align="center"><b><font color="white">UBICACION SALIDA</font></b></td>                            
                        </tr>

                    <?php
                        $i=1;
                        foreach ($reporte as $lista ) {
                        //     $totalUni+=$lista["UNITS"];
                        //     $totalSinIva+=$lista["TOTALSINIVA"];
                        //     $totalConIva+=$lista["TOTALCONIVA"];
                            
                     ?>      
                        <tr>
                            <td align="center"><?php echo $i; ?></td>
                            <td><?php echo $lista["autorizador_por"] ?></td>
                            <td align="center"><?php echo $lista["codigo"] ?></td>
                            <td align="center">
                                <?php
                                echo utf8_encode($lista["descripcion1"]);
                                echo " - ".$lista["marca"]." ";
                                echo number_format($lista["pesoxunidad"]).$lista["nombre_unidad"];
                                ?>
                            </td>
                            <td align="center"><?php echo $lista["can_2"] ?></td>
                            <td align="center"><?php echo $lista["tipo_movimiento"] ?></td> 
                            <td align="center"><?php echo $lista["ubicacion_entrada"] ?></td> 
                            <td align="center"><?php echo $lista["ubicacion_salida"] ?></td>                           
                        </tr>  

                    <?php
                        $i++;
                        }
                    ?>                    
                    </table>
                    <?php
                    $_SESSION['inventario'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['inventario'];
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
                                window.location.href = "imprimirReporteInventario.php";  
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            break;

            case "reporte_productoSobreLimite": //Creado por Humberto 
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $unidades = $_GET["unidades"];
                    //$categoria = $_GET["categoria"];                   
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    
                    $anno_des=substr($_GET["desde"],2,2); 
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);
                   
                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{
                       
                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;




                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    $sql = "SELECT taxid, name_persona, CODE, nombre_producto, units as UNITS, price as PRICE,
                    datenew_ticketlines, codigo_siga, nombre_punto, nombre_estado
                    FROM $BD_central.$ventas_mes_anno
                    LEFT JOIN selectrapyme_central.grupo ON grupo.grupopos = $ventas_mes_anno.category,
                    selectrapyme_central.puntos_venta, selectrapyme_central.estados
                    WHERE codigo_estado_punto = codigo_estado
                    AND codigo_siga_punto = codigo_siga
                    AND datenew_ticketlines
                    BETWEEN  '$desde' and '$hasta'
                    ";

                    //ORIGINAL como ejemplo para el nuevo ajax
                   /*$sql=" SELECT v.CODE,v.REFERENCE,TRIM(v.nombre_producto) as nombre_producto ,SUM(v.UNITS) as UNITS,
                   SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                   FROM $BD_central.$ventas_mes_anno v 
                   INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID 
                   INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                   WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                   ";*/


                    if($punto!="0"){
                        $sql.=" and codigo_siga='$punto'";
                    }      
                    if($estado!="0"){
                        $sql.=" and codigo_estado_punto='$estado'";
                    }   
                    if($unidades!=""){
                        $sql.=" and units>='$unidades'";
                    }      
                    /*if($unidades!="0"){
                       
                        $sql.=" and v.category='$categoria'";
                    }*/     
   

                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    $sql.=" ORDER BY nombre_punto, taxid, nombre_producto, datenew_ticketlines";
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                    
                  
                    if(count($reporte)>0){
                    ?>  
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">generar excel</div>
            
                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="10" align="center"><b><font color="white">REPORTE DE VENTAS POR PRODUCTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <!--<td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>-->
                            <td width="5%" align="center"><b><font color="white">CEDULA</font></b></td>
                            <td width="15%" align="center"><b><font color="white">NOMBRE Y APELLIDO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="5%" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PRECIO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">FECHA Y HORA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PUNTO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">ESTADO</font></b></td>
                        </tr>

                    <?php 
                        foreach ($reporte as $lista ) {
                            $totalUni+=$lista["UNITS"];
                            $totalPrice+=$lista["PRICE"];
                            /*$totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            //$totalSeco+=$lista['CANTSECO'];*/
                            
                     ?>      
                        <tr>
                            <td width="5%"><?php echo $lista["taxid"] ?></td>
                            <td width="15%" align="center"><?php echo utf8_encode($lista["name_persona"]); ?></td>
                            <td width="5%" align="center"><?php echo $lista["CODE"] ?></td>
                            <td width="10%" align="center"><?php echo utf8_encode($lista["nombre_producto"]); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["PRICE"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo $lista["datenew_ticketlines"]; ?></td>
                            <td width="5%" align="center"><?php echo $lista["codigo_siga"]; ?></td>
                            <td width="10%" align="center"><?php echo utf8_encode($lista["nombre_punto"]); ?></td>
                            <td width="10%" align="center"><?php echo utf8_encode($lista["nombre_estado"]); ?></td>  
                        </tr>  

                    <?php     
                        }
                    ?>  
                     <tr>
                        <td colspan="10"><hr color="black" size="2" /></td>
                    </tr>
                     <tr>
                            <td width="5%"></td>
                            <td width="15%" align="center"></td>
                            <td width="5%" align="center"><b>TOTAL UNIDADES</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>                
                    </tr>
                    <tr>
                            <td width="5%"></td>
                            <td width="15%" align="center"></td>
                            <td width="5%" align="center"><b>TOTAL VENTAS</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalPrice, 2, ',', '.');  ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>                
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

            }// termina el else de las fechas iguales

            break;

            case "rep_trans_rango": //Creado por Junior 
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];            
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    
                    $anno_des=substr($_GET["desde"],2,2); 
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);
                    
                    
                    function diff_dte($date1, $date2){
                    if (!is_integer($date1)) $date1 = strtotime($date1);
                    if (!is_integer($date2)) $date2 = strtotime($date2);  
                    return floor(abs($date1 - $date2) / 60 / 60 / 24);
                    } 
                     
                    $dias = diff_dte($desde,$hasta);

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    $sql = "SELECT a.codigo_siga_punto, a.nombre_punto, b.nombre_estado
                    FROM $BD_central.puntos_venta a, $BD_central.estados b
                    WHERE a.codigo_estado_punto=b.codigo_estado
                    AND a.estatus='A'
                    AND id_tipo not in (7)";

                    if($punto!="0"){
                        $sql.=" and a.codigo_siga_punto='$punto'";
                    }      
                    if($estado!="0"){
                        $sql.=" and b.codigo_estado='$estado'";
                    } 

                    $sql.=" ORDER BY b.nombre_estado, a.codigo_siga_punto";

                    $puntos=$conn->ObtenerFilasBySqlSelect($sql);

                    if($dias>=6)
                    {
                        echo "Este Reporte se Descargará para su Mejor Visualización";
                        ?>
                        <script type="text/javascript">
                        window.open('../../reportes/reporte_trans_rango_xls.php?fecha1=<?php echo $desde;?>&fecha2=<?php echo $hasta;?>&punto=<?php echo $punto;?>&estado=<?php echo $estado;?>&dias=<?php echo $dias;?>');
                        </script>
                        <?php
                        exit();
                    }
                    
                    ?>
                    
                    </tr>
                    </table>

                    <div style="background-color:#5084A9;padding:10px;width:90px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">Generar Excel</div>
                    <?php
                     ob_start();
           
                    ?>

                        <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="11" align="center"><b><font color="white">REPORTE DE TRANSMISION DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="3%" align="center"><b><font color="white">N°</font></b></td>
                            <td width="5%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="15%" align="center"><b><font color="white">PUNTO</font></b></td>

                            <?php
                            $desde_while=$desde;
                            while ( $desde_while<=$hasta) {
                            ?>
                            <td width="5%" align="center"><b><font color="white"><?php echo $desde_while ?></font></b></td>
                            <?php
                            $desde_while = strtotime ( '+1 day' , strtotime ( $desde_while ) ) ;
                            $desde_while = date ( 'Y-m-d' , $desde_while );
                            }
                            ?>
                            <td width="5%" align="center"><b><font color="white">% de Transmisi&oacute;n</font></b></td>
                            
                        </tr>
                        <?php
                    $j=1;
                    $cont_total=0;
                    foreach ($puntos as $lista ) {
                    ?>
                    <tr>
                    <td align="center"><?php echo $j; ?></td>
                    <td align="center"><?php echo $lista["nombre_estado"]; ?></td>
                    <td align="center"><?php echo $lista["codigo_siga_punto"]; ?></td>
                    <td align="center"><?php echo $lista["nombre_punto"]; ?></td>

                    <?php
                    $desde_while=$desde;
                    $cont=0;

                    while ( $desde_while<=$hasta) {
                    $cont_total=$cont_total+1;
                    $sql_ver="SELECT codigo_siga FROM $BD_central.reporte_ventas_historico
                    WHERE codigo_siga='".$lista["codigo_siga_punto"]."' AND fecha='".$desde_while."'";
                    $puntos_ver = $conn->ObtenerFilasBySqlSelect($sql_ver);

                    if(count($puntos_ver)==0){
                    ?>

                    <td align="center">X</td>

                    <?php
                    }else{
                    $cont=$cont+1;
                    ?>

                    <td align="center">&#10004;</td>

                    <?php
                    }
                    $desde_while = strtotime ( '+1 day' , strtotime ( $desde_while ) ) ;
                    $desde_while = date ( 'Y-m-d' , $desde_while );
                    }

                    $port_rep=($cont*100)/($dias+1);
                    ?>
                    <td align="center"  width="5%"><?php echo number_format($port_rep,0, '.', '')."%" ?></td>

                    <?php
                    $cont=0;
                    $j=$j+1;
                    }
                    ?>
                    </tr>
                    <!-- TOTALES DE LOS DÍAS REPORTADOS -->
                    <tr>
                        <td colspan="4" align="center"><b>TOTALES</b></td>
                            <?php
                                $desde_while2=$desde;
                                $total=0;
                                while ( $desde_while2<=$hasta) {
                                $sql="SELECT count(*) as total FROM $BD_central.puntos_venta a,
                                $BD_central.estados b,
                                $BD_central.reporte_ventas_historico c
                                WHERE a.codigo_siga_punto=c.codigo_siga
                                and a.codigo_estado_punto=b.codigo_estado
                                and c.fecha='$desde_while2'
                                ";
                                if($punto!="0"){
                                    $sql.=" and a.codigo_siga_punto='$punto'";
                                }      
                                if($estado!="0"){
                                    $sql.=" and b.codigo_estado='$estado'";
                                } 

                                $sql.=" ORDER BY b.nombre_estado, a.codigo_siga_punto";

                                $puntos_total = $conn->ObtenerFilasBySqlSelect($sql);
                                $total=$total+$puntos_total[0]["total"];
                            ?>
                        
                        <td align="center"><?php echo $puntos_total[0]["total"]; ?></td>
                            <?php
                                $desde_while2 = strtotime ( '+1 day' , strtotime ( $desde_while2 ) ) ;
                                $desde_while2 = date ( 'Y-m-d' , $desde_while2 );
                            }
                                $port_rep=($total*100)/($cont_total);
                            ?>
                            <td align="center"><?php echo number_format($port_rep,0, '.', '')."%" ?></td>
                    </tr>

                    </table>
                    <?php
                    
                    //ORIGINAL como ejemplo para el nuevo ajax
                   /*$sql=" SELECT v.CODE,v.REFERENCE,TRIM(v.nombre_producto) as nombre_producto ,SUM(v.UNITS) as UNITS,
                   SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                   FROM $BD_central.$ventas_mes_anno v 
                   INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID 
                   INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                   WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                   ";*/


                    
                    /*if($unidades!="0"){
                       
                        $sql.=" and v.category='$categoria'";
                    }*/     
   

                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";

                    
                  
                   
                    ?>  

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
                                var titulo_reporte='Reporte Transmision Puntos (Rango)';
                                window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;  
                            });
                        </script>
                    <?php

            break;

            case "rep_trans_rango2": //Creado por Humberto 
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];            
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    
                    $anno_des=substr($_GET["desde"],2,2); 
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    $sql = "SELECT COUNT(c.codigo_siga) as TOTALESTADO, b.nombre_estado, b.codigo_estado as estado
                    FROM $BD_central.puntos_venta a, $BD_central.estados b,
                    $BD_central.reporte_ventas_historico c
                    WHERE c.fecha='$desde'
                    AND a.codigo_estado_punto=b.codigo_estado
                    AND c.codigo_siga=a.codigo_siga_punto

                    AND a.estatus='A'";

                    if($punto!="0"){
                        $sql.=" and a.codigo_siga_punto='$punto'";
                    }      
                    if($estado!="0"){
                        $sql.=" and b.codigo_estado='$estado'";
                    } 

                    $sql.=" GROUP BY b.nombre_estado ORDER BY b.codigo_estado";

                    $puntos=$conn->ObtenerFilasBySqlSelect($sql);
                    
                    ?>
                    
                    </tr>
                    </table>

                    <div style="background-color:#5084A9;padding:10px;width:90px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">Generar Excel</div>
                    <?php
                     ob_start();
           
                    ?>
                        <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="5" align="center"><b><font color="white">REPORTE DE TRANSMISIÓN DEL: <?php echo $desde; ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="3%" align="center"><b><font color="white">N°</font></b></td>
                            <td width="5%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="15%" align="center"><b><font color="white">PUNTOS REPORTADOS</font></b></td>
                            <td width="15%" align="center"><b><font color="white">PUNTOS SIN REPORTAR</font></b></td>
                            <td width="5%" align="center"><b><font color="white">% de Transmisi&oacute;n</font></b></td>
                        </tr>
                        <?php
                    $j=1;
                    $cont_total=0;
                    foreach ($puntos as $lista ) {
                        $total_reportado=$lista["TOTALESTADO"];
                        $total+=$lista["TOTALESTADO"];
                        $estados=$lista["estado"];
                    ?>
                    <tr>
                    <td align="center"><?php echo $j; ?></td>
                    <td align="center"><?php echo $lista["nombre_estado"]; ?></td>
                    <td align="center"><?php echo $total_reportado; ?></td>

                    <?php
                        $sql2="SELECT COUNT(b.nombre_estado) as total
                        FROM $BD_central.puntos_venta a, $BD_central.estados b
                        WHERE a.codigo_estado_punto=b.codigo_estado
                        AND a.estatus='A'
                        AND b.codigo_estado='$estados'
                        AND a.id_tipo <>7
                        ";
                        
                        $sql2.=" GROUP BY b.nombre_estado ORDER BY b.codigo_estado";

                        $puntos_total = $conn->ObtenerFilasBySqlSelect($sql2);

                        foreach ($puntos_total as $key) {
                            $pruebatotal=$key["total"];
                            $pruebatotales+=$key["total"];
                        }

                        //echo $key["total"];

                        $pruebatotal;//Total de puntos por estado
                        $total_reportado;//Total de puntos que reportaron por estado
                        $faltan_reportar = $pruebatotal - $total_reportado;//Los que faltan por reportar
                        $porcentaje_reportado = (($total_reportado*100)/$pruebatotal);//Porcentaje reportado por dia

                    ?>
                    
                    <td align="center"><?php echo $faltan_reportar; ?></td>
                    <td align="center"><?php echo number_format($porcentaje_reportado,0, '.', '')."%" ?></td>

                    <?php
                    $cont=0;
                    $j=$j+1;
                    }
                    ?>
                    </tr>
                    <!-- TOTALES REPORTADOS -->
                    <tr>
                        <td colspan="2" align="center"><b>TOTALES</b></td>
                        
                        <td align="center"><?php echo $total; ?></td>
                        <?php
                            $contador_faltantes=$pruebatotales-$total;
                        ?>
                        <td align="center"><?php echo $contador_faltantes; ?></td>
                        <?php
                            $porcentaje_total=(($total*100)/$pruebatotales);
                        ?>
                        <td align="center"><?php echo number_format($porcentaje_total,0, '.', '')."%" ?></td>
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
                                var titulo_reporte='Reporte Transmision Puntos (Rango)';
                                window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;  
                            });
                        </script>
                    <?php

            break;

            case "reporte_inventarioCentral":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $producto = $_GET["producto"];
                    $fecha = $_GET["fecha"];                   
                   
                    $BD_central=DB_REPORTE_CENTRAL;
                    $sql="SELECT  i.* ,p.descripcion1,pv.codigo_estado_punto,pv.nombre_punto FROM $BD_central.inventario i
                    INNER JOIN $BD_central.productos p on  i.codigo_barra=p.codigo_barras
                    INNER JOIN $BD_central.puntos_venta pv on i.siga=substring(pv.codigo_siga_punto,4)
                    where fecha='$fecha'"; 
                 
                    if($punto!="0"){
                        $sql.=" and i.siga='$punto'";
                    }      
                    if($estado!="0"){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                    }   
                    if($producto!="0"){
                        $sql.=" and i.codigo_barra='$producto'";
                    }      
                   
   

                    $sql.="  ORDER BY i.siga, p.descripcion1";    
                              
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                    
                    if(count($reporte)>0){
                    ?>  
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp">generar excel</div>
            
                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="14" align="center"><b><font color="white">REPORTE DE INVENTARIO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="30%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="30%" align="center"><b><font color="white">SIGA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">UBICACION</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CANTIDAD</font></b></td>
                            <td width="10%" align="center"><b><font color="white">FECHA</font></b></td>                            
                        </tr>

                    <?php 
                        foreach ($reporte as $lista ) {
                        //     $totalUni+=$lista["UNITS"];
                        //     $totalSinIva+=$lista["TOTALSINIVA"];
                        //     $totalConIva+=$lista["TOTALCONIVA"];
                            
                     ?>      
                        <tr>
                            <td width="10%"><?php echo $lista["codigo_barra"] ?></td>
                            <td width="30%" align="center"><?php echo $lista["descripcion1"] ?></td>
                            <td width="30%" align="center"><?php echo $lista["nombre_punto"]  ?></td>
                            <td width="10%" align="center"><?php echo $lista["ubicacion"] ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["cantidad"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo $lista["fecha"] ?></td>                           
                        </tr>  

                    <?php     
                        }
                    ?>                    
                    </table>
                    <?php
                    $_SESSION['inventario'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['inventario'];
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
                                window.location.href = "imprimirReporteInventario.php";  
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            break;
            case "reporte_cestaBasica_per_no_pert":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];                   
                    $cestaBasica = $_GET["cestaBasica"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $BD_central=DB_REPORTE_CENTRAL;
                    $sql=" SELECT v.CODE,v.REFERENCE,TRIM(v.nombre_producto) as nombre_producto ,SUM(v.UNITS) as UNITS,SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate FROM $BD_central.ventas v
                           INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                           INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                           INNER JOIN selectrapyme_central.productos p on p.codigo_barras=v.CODE
                           WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta' and p.regulado='$cestaBasica'"; 
                    
                    if($punto!="0"){
                        $sql.=" and v.codigo_siga='$punto'";
                    }      
                    if($estado!="0"){
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
                    $_SESSION['basicaPerNoPer'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['basicaPerNoPer'];
                    ?> 
                        <script >
                             $("#excelImp").click(function() {                               
                                window.location.href = "imprimirReportePerNoPer.php";  
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            break;
              case "imprimirReporteExcel":

                $titulo="Reporte Ventas Diarias";
                header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
                header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
                header ("Cache-Control: no-cache, must-revalidate");  
                header ("Pragma: no-cache");  
                header ("Content-type: application/x-msexcel");  
                header ("Content-Disposition: attachment; filename=\"".$titulo.".xls\"" );
                ?>
                <html>
                <div>hola</div>
                </html>
                <?php
             // echo $_SESSION['contenido'];

               
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
							$conn->ExecuteTrans("insert into item_serial_temp (id_producto, serial, estado) values ('$item', '$ser', '1' )");
						}
					}      
		         /*$id=$_GET[cod];
	            $cant=$_GET[cant];
	            
	            $campos = $conn->ObtenerFilasBySqlSelect("SELECT *	from item WHERE id_item ='$id'");
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
                       $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id_almacen='".$almacen."'");
                        if (count($campos) == 0) {
                            echo "[{band:'-1'}]";
                        } else {
                            echo json_encode($campos);
                        }
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
?>
