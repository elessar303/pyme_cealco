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
include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");
//include("../../../general.config.inc.php");

$pyme='selectrapyme_central';

$almacen = new Almacen();
$pendiente = false;
$pos=POS;
if (isset($_GET["cod"]) /* && isset($_GET["cod2"]) */) {
	
    // Prueba para tomar los productos
    $sql2 = "SELECT im.*,rbm.* FROM investigacion_mercado im, rubros_estudio_mercado rbm";
    $productos_estudio_mercado = $almacen->ObtenerFilasBySqlSelect($sql2);
    $smarty->assign("productos_estudio_mercado",$productos_estudio_mercado);
    $smarty->assign("cod", $_GET["cod"]);
    $pendiente = !$pendiente;
    // fin de las pruebas

    /*$sql = "SELECT kd.id_item, kd.cantidad, kd.id_almacen_entrada, kd.id_ubi_entrada, i.descripcion1, i.codigo_barras FROM kardex_almacen_detalle AS kd, item AS i WHERE i.id_item = kd.id_item AND kd.id_transaccion = {$_GET["cod"]};";
    $productos_pendientes_entrada = $almacen->ObtenerFilasBySqlSelect($sql);
    $detalles_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT autorizado_por, observacion, id_documento FROM kardex_almacen WHERE id_transaccion = {$_GET["cod"]};");
    $detalles_compra_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT num_factura_compra, num_cont_factura FROM compra WHERE id_compra = {$detalles_pendiente[0]["id_documento"]};");
    $smarty->assign("detalles_pendiente", $detalles_pendiente);
    $smarty->assign("productos_pendientes_entrada", $productos_pendientes_entrada);
    $smarty->assign("datos_factura", $detalles_compra_pendiente);
    $smarty->assign("cod", $_GET["cod"]);
    #$smarty->assign("cod2", $_GET["cod2"]);
    $pendiente = !$pendiente;*/
}

$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());
//cargando select de proveedores
/*$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $almacen->ObtenerFilasBySqlSelect("SELECT * FROM proveedores order by descripcion");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $nombre_proveedor=$item["descripcion"]."-".$item["rif"];
    $arraySelectoutPut[] = utf8_encode($nombre_proveedor);
}
$smarty->assign("option_values_proveedor", $arraySelectOption);
$smarty->assign("option_output_proveedor", $arraySelectoutPut);*/

//Ingreso de SELECT para la nueva seccion
//Estados
$arraySelectOption = "";
$arraySelectOutPut1 = "";
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $pyme.estados");

foreach ($ubica as $key => $item) {
    $arraySelectOption[] = $item["codigo_estado"];
    $arraySelectOutPut1[] = $item["nombre_estado"];
}
$smarty->assign("option_values_id_estado", $arraySelectOption);
$smarty->assign("option_output_nombre_estado", $arraySelectOutPut1);

//Fin de los SELECTS para la nueva seccion

// Establecimientos //
$arraySelectOption = "";
$arraySelectOutPut1 = "";
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $pyme.establecimientos");
foreach ($ubica as $key => $item) {
    $arraySelectOption[] = $item["id_establecimiento"];
    $arraySelectOutPut1[] = $item["nombre_establecimiento"];
}
$smarty->assign("option_values_id_establecimiento", $arraySelectOption);
$smarty->assign("option_output_nombre_establecimiento", $arraySelectOutPut1);

// Productos de estudio de mercado //
$arraySelectOption = "";
$arraySelectOutPut1 = "";
$ubicacion=new Almacen();
//$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $pyme.rubros_estudio_mercado");
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from $pyme.rubros_estudio_mercado");
foreach ($ubica as $key => $item) {
    $arraySelectOption[] = $item["id_rubro"];
    $arraySelectOutPut1[] = $item["nombre_rubro"];
}
$smarty->assign("option_values_id_rubro", $arraySelectOption);
$smarty->assign("option_output_nombre_rubro", $arraySelectOutPut1);

#################################################################################
if (isset($_POST["input_cantidad_items"])) { 
// si el usuario hizo post
    
    $almacen->BeginTrans();
    $establecimientos = $_POST["_establecimiento"];
    // print_r($establecimientos); exit();
    $productos = $_POST["_producto"];
    $precios = $_POST["_precio"];

    $ingreso_productos_estudio = "INSERT INTO $pyme.investigacion_mercado (
        `autorizado_por`, `fecha`, `observacion`, `estado`)
        VALUES('{$_POST["creado_por"]}', '{$_POST["input_fechacompra"]}',
            '{$_POST["observaciones"]}', '{$_POST["estado"]}');";

    $almacen->ExecuteTrans($ingreso_productos_estudio);
    $id_estudio = $almacen->getInsertID();

    for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) {

        $ingreso_detalle_estudio = "INSERT INTO $pyme.investigacion_mercado_detalle (
            `id_establecimiento`,`id_estudio`, `id_producto`, `precio`)
            VALUES('{$establecimientos[$i]}','{$id_estudio}', '{$productos[$i]}', '{$precios[$i]}');";
        //echo "<br>";
        $almacen->ExecuteTrans($ingreso_detalle_estudio);
    }
    
    //$almacen->CommitTrans();

    //$almacen->ExecuteTrans($ingreso_productos_estudio);
    //$id_transaccion = $almacen->getInsertID();

    /*if(!$pendiente){
        $almacen->BeginTrans();
        $ingreso_productos_estudio = "INSERT INTO investigacion_mercado (
        `autorizado_por`, `fecha`, `establecimiento`, `producto`, `precio`, `observacion`, `estado`)
        VALUES('{$_POST["creado_por"]}', '{$_POST["input_fechacompra"]}', '{$_POST["establecimiento"]}', '{$_POST["rubros"]}',
         '{$_POST["precio"]}', '{$_POST["observaciones"]}', '{$_POST["estado"]}');";
    }else{
        echo "No se registraron los datos...!!!"
    }*/


    /*if (!$pendiente) 
    {
        # Verificar que no se trata de una compra con estatus de entrega de productos "Pendiente"
        #list($dia, $mes, $anio) = explode('-', $_POST["input_fechacompra"]);

        $almacen->BeginTrans();
        $ingreso_productos_estudio = "INSERT INTO investigacion_mercado (
            `autorizado_por`, `fecha`, `observacion`, `estado`)
        VALUES(
            '{$_POST["creado_por"]}', '{$_POST["input_fechacompra"]}', '{$_POST["observaciones"]}', '{$_POST["estado"]}');";
        $kardex_almacen_instruccion = "INSERT INTO kardex_almacen (
            `id_transaccion` , `tipo_movimiento_almacen`, `creado_por`,
            `observacion`, `fecha`, `usuario_creacion`,
            `fecha_creacion`, `estado`, `fecha_ejecucion`, `empresa_transporte`, 
            `id_conductor`, `placa`, `guia_sunagro`, `orden_despacho`, `almacen_procedencia`)
        VALUES (
            NULL , '3', '{$_POST["creado_por"]}',
            '{$_POST["observaciones"]}', '{$_POST["input_fechacompra"]}', '{$login->getUsuario()}', 
            CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP, '{$_POST["empresa_transporte"]}',
             '{$id_conductor[0]["id_conductor"]}', '{$_POST["placa"]}', '{$_POST["codigo_sica"]}',
              '{$_POST["orden_despacho"]}', '{$_POST["puntodeventa"]}');";

        $almacen->ExecuteTrans($ingreso_productos_estudio);
        $id_transaccion = $almacen->getInsertID();

        for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) {
            $ingreso_productos_estudio2 = "INSERT INTO investigacion_mercado (
                    `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`,`id_ubi_entrada`, `vencimiento`,`lote`, `c_esperada`,`observacion`)
                VALUES (
                    NULL, '{$id_transaccion}', '{$_POST["_id_almacen"][$i]}',
                    '', '{$_POST["_id_item"][$i]}', '{$_POST["_cantidad"][$i]}','{$_POST["_ubicacion"][$i]}',
                    '{$_POST["_vencimineto"][$i]}','{$_POST["_lote"][$i]}','{$_POST["_c_esperada"][$i]}',
                    '{$_POST["_observacion"][$i]}');";
            $kardex_almacen_detalle_instruccion = "INSERT INTO kardex_almacen_detalle (
                    `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`,`id_ubi_entrada`, `vencimiento`,`lote`, `c_esperada`,`observacion`)
                VALUES (
                    NULL, '{$id_transaccion}', '{$_POST["_id_almacen"][$i]}',
                    '', '{$_POST["_id_item"][$i]}', '{$_POST["_cantidad"][$i]}','{$_POST["_ubicacion"][$i]}',
                    '{$_POST["_vencimineto"][$i]}','{$_POST["_lote"][$i]}','{$_POST["_c_esperada"][$i]}',
                    '{$_POST["_observacion"][$i]}');";

            $almacen->ExecuteTrans($ingreso_productos_estudio2);

            $campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM item_existencia_almacen WHERE
                    id_item  = '{$_POST["_id_item"][$i]}' AND id_ubicacion = '{$_POST["_ubicacion"][$i]}';");

            if (count($campos) > 0) {
                $cantidadExistente = $campos[0]["cantidad"];
                $almacen->ExecuteTrans("UPDATE item_existencia_almacen 
                    SET cantidad = '" . ($cantidadExistente + $_POST["_cantidad"][$i]) . "'
                    WHERE id_item  = '{$_POST["_id_item"][$i]}' AND id_ubicacion = '{$_POST["_ubicacion"][$i]}';");
            } else {
                $instruccion = "INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`,`id_ubicacion`)
                    VALUES ('{$_POST["_id_almacen"][$i]}', '{$_POST["_id_item"][$i]}', '{$_POST["_cantidad"][$i]}' , '{$_POST["_ubicacion"][$i]}');";
                $almacen->ExecuteTrans($instruccion);
            }

				$ubi=$_POST["_ubicacion"][$i];  		
				$ubis = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion  WHERE id='$ubi'");  				
  				$puedev=$ubis[0]["puede_vender"];
				if($pos!='')
				{						
					if($puedev==1) 
					{
	  				
						$itemm = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM item join grupo on (item.cod_grupo=grupo.cod_grupo) WHERE
		                    id_item  = '{$_POST["_id_item"][$i]}' ");  				
		  				$itempos=$itemm[0]["itempos"];		  			
		  				$itemmpos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM $pos.products WHERE ID='".$itempos."'");
		  				$filas=$almacen->getFilas();
                        // if($itemmpos[0]["ID"]!="")
                         if($filas!=0)
		  				{
                             
                             
                             
                              $verificar=$almacen->ObtenerFilasBySqlSelect("select * from $pos.stockcurrent where product='$itempos' "); 
                        if($verificar[0]["PRODUCT"]!=""){
                          $instruccion = "update $pos.stockcurrent set UNITS=(UNITS+{$_POST["_cantidad"][$i]}) WHERE PRODUCT='$itempos'";
                        }else{
                        $instruccion = "insert into  $pos.stockcurrent values('0','$itempos',null,'".$_POST["_cantidad"][$i]."')"; 
                //echo "insert into  $pos.stockcurrent values('0','$itempos','null','".$_POST["_cantidad"][$i]."')"; exit();
                        }
                $almacen->ExecuteTrans($instruccion);               
                                 }//fin del if de verificar stockcurrent
                             
                             
                             
                             
                               // $instruccion = "update $pos.stockcurrent set UNITS=(UNITS+{$_POST["_cantidad"][$i]}) WHERE PRODUCT='$itempos'";
		            	//$almacen->ExecuteTrans($instruccion);
		              //}
		        		else
		        		{
		        			$iva=($itemm[0]['iva'])/100;

		        			$imp = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM $pos.taxes WHERE RATE='".$iva."' ");
		        			                 
                            if($itemm[0]["referencia"]=='')
                            {
                                $itemm[0]["referencia"]=$itemm[0]["cod_item"];
                            }
                           $instruccion = "INSERT INTO  $pos.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISCOM, ISSCALE, ATTRIBUTES, QUANTITY_MAX, TIME_FOR_TRY) VALUES ('$itempos', '".$itemm[0]["referencia"]."', '".$itemm[0]["codigo_barras"]."', '".$itemm[0]["descripcion1"]."', '".$itemm[0]["coniva1"]."', '".$itemm[0]["coniva1"]."', '".$itemm[0]["grupopos"]."', '".$imp[0]["CATEGORY"]."', 0, 0, null, '".$itemm[0]["cantidad_rest"]."', '".$itemm[0]["dias_rest"]."')";
		            	
                        $almacen->ExecuteTrans($instruccion);
		            	
		            	$instruccion = "INSERT INTO  $pos.stockcurrent (LOCATION, PRODUCT, UNITS) VALUES ('0','$itempos', '{$_POST["_cantidad"][$i]}')";
		            	$almacen->ExecuteTrans($instruccion);
		            	
		            	$instruccion = "INSERT INTO  $pos.products_cat ( PRODUCT) VALUES ('$itempos')";
	   					// $itemCat->ExecuteTrans($instruccion);
                        $almacen->ExecuteTrans($instruccion);
						} 
					} 				      
        		}
        
        } // Fin del For
    } else {
        #################################################################################
        # Se cambia el estado del movimiento a 'Entregado'
        $almacen->ExecuteTrans(
                "UPDATE kardex_almacen
          SET estado = 'Entregado', autorizado_por = '{$_POST["autorizado_por"]}',
          observacion = 'Entrada por Compra', `fecha_ejecucion` = CURRENT_TIMESTAMP
          WHERE id_transaccion = {$_GET["cod"]};");

        $cant_productos_pendientes = count($productos_pendientes_entrada);

        $producto_x = new Almacen();
        $producto_aux = new Almacen();

        for ($i = 0; $i < $cant_productos_pendientes; $i++) {

            $existente = $producto_aux->ObtenerFilasBySqlSelect("SELECT cantidad FROM item_existencia_almacen WHERE id_item = '{$productos_pendientes_entrada[$i]["id_item"]}' AND cod_almacen = {$productos_pendientes_entrada[$i]["id_almacen_entrada"]} AND id_ubicacion={$productos_pendientes_entrada[$i]["id_ubi_entrada"]};");
            $cantidad_aditar = $producto_x->ObtenerFilasBySqlSelect("SELECT id_item, cantidad, id_almacen_entrada,id_ubi_entrada FROM kardex_almacen_detalle WHERE id_transaccion = '{$_GET["cod"]}' AND id_item = '{$productos_pendientes_entrada[$i]["id_item"]}';");
            $producto_pendiente_compra = $almacen->ObtenerFilasBySqlSelect("SELECT _item_preciosiniva, _item_cantidad FROM compra_detalle WHERE id_compra = '{$detalles_pendiente[0]["id_documento"]}' AND id_item = '{$productos_pendientes_entrada[$i]["id_item"]}';");

            if (count($existente) > 0) {
                $sql = "SELECT SUM(e.cantidad) AS cantidad_inventario, costo_promedio, utilidad1, utilidad2, utilidad3, iva
                  FROM item AS i
                  INNER JOIN item_existencia_almacen AS e
                  ON i.id_item = e.id_item
                  WHERE i.id_item = {$productos_pendientes_entrada[$i]["id_item"]};";

                $item_pendiente = $almacen->ObtenerFilasBySqlSelect($sql);
                
                // * Actualizar los precios (2013-05-03)
                // * Codigo incluido para la actualizaciÃÂŗn de los precios despuÃÂŠs de hacer 
                // * entrada de una compra con entrega pendiente no considerado originalmente.
                // * Se actualizaban los precios independientemente del status de la compra.
                

                // echo "</br>cantidad_inventario:" . $item_pendiente[0]["cantidad_inventario"];
                // echo "</br>costo_promedio:" . $item_pendiente[0]["costo_promedio"];
                // echo "</br>_item_preciosiniva:" . $producto_pendiente_compra[0]["_item_preciosiniva"];
                // echo "</br>_item_cantidad:" . $producto_pendiente_compra[0]["_item_cantidad"];

                $costo_promedio = $item_pendiente[0]["cantidad_inventario"] * $item_pendiente[0]["costo_promedio"] + $producto_pendiente_compra[0]["_item_preciosiniva"] * $producto_pendiente_compra[0]["_item_cantidad"];
                $utilidad1 = $producto_pendiente_compra[0]["_item_preciosiniva"] * $item_pendiente[0]["utilidad1"] / 100;
                $precio1 = $producto_pendiente_compra[0]["_item_preciosiniva"] + $utilidad1;
                $coniva1 = $precio1 + $precio1 * $item_pendiente[0]["iva"] / 100;
                $utilidad2 = $producto_pendiente_compra[0]["_item_preciosiniva"] * $item_pendiente[0]["utilidad2"] / 100;
                $precio2 = $producto_pendiente_compra[0]["_item_preciosiniva"] + $utilidad2;
                $coniva2 = $precio2 + $precio2 * $item_pendiente[0]["iva"] / 100;
                $utilidad3 = $producto_pendiente_compra[0]["_item_preciosiniva"] * $item_pendiente[0]["utilidad3"] / 100;
                $precio3 = $producto_pendiente_compra[0]["_item_preciosiniva"] + $utilidad3;
                $coniva3 = $precio3 + (($precio3 * $item_pendiente[0]["iva"]) / 100);

                $sql2 = "SELECT SUM(cantidad) AS cantidad_inventario FROM item_existencia_almacen WHERE id_item = '{$productos_pendientes_entrada[$i]["id_item"]}';";
                $existencia = $almacen->ObtenerFilasBySqlSelect($sql2);

                $costo_promedio_actual = $costo_promedio / $existencia[0]["cantidad_inventario"];

                $sql3 = "UPDATE item
                  SET costo_anterior = costo_actual, costo_promedio = '{$costo_promedio_actual}', costo_actual = '{$producto_pendiente_compra[0]["_item_preciosiniva"]}' ,
                  precio1 = '{$precio1}', coniva1 = '{$coniva1}', precio2 = '{$precio2}', coniva2 = '{$coniva2}', precio3 = '{$precio3}', coniva3 = '{$coniva3}'
                  WHERE id_item = '{$productos_pendientes_entrada[$i]["id_item"]}';";
                $almacen->ExecuteTrans($sql3);
                /// Fin cÃÂŗdigo de actualizaciÃÂŗn de precios

                /// Originalmente la siguiente era la ÃÂēnica instrucciÃÂŗn en este bloque
                $almacen->ExecuteTrans("UPDATE item_existencia_almacen SET cantidad = " . ($existente[0]['cantidad'] + $cantidad_aditar[0]['cantidad']) . " WHERE id_item = '{$productos_pendientes_entrada[$i]["id_item"]}' AND cod_almacen = '{$productos_pendientes_entrada[$i]["id_almacen_entrada"]}' AND id_ubicacion = '{$productos_pendientes_entrada[$i]["id_ubi_entrada"]}';");
            } else {
                $almacen->ExecuteTrans("INSERT INTO item_existencia_almacen (`cantidad`, `id_item`, `cod_almacen`,`id_ubicacion`) VALUES ('" . $cantidad_aditar[0]['cantidad'] . "', '" . $cantidad_aditar[0]['id_item'] . "', '" . $cantidad_aditar[0]['id_almacen_entrada'] . "', '" . $cantidad_aditar[0]['id_ubi_entrada'] . "');");
            }
        }
        #################################################################################
    }*/
	
    /*$sql = "INSERT INTO item_serial (id_producto,serial,estado) (select id_producto,serial,estado from item_serial_temp where usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."')";  
	$almacen->ExecuteTrans($sql);
    
	$almacen->ExecuteTrans("delete from item_serial_temp where usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."'");*/

    if ($almacen->errorTransaccion == 1)
    {    
        //echo "paso";   
        Msg::setMessage("<span style=\"color:#62875f;\">Entrada Generada Exitosamente </span>");
    }
    elseif($almacen->errorTransaccion == 0)
    {
        //echo "no paso";
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de cargar la entrada, por favor intente nuevamente.</span>");
    }
    $almacen->CommitTrans($almacen->errorTransaccion);

   header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
//$almacen->ExecuteTrans("truncate table item_serial_temp"); 
//forma vieja de borrar la tabla, ahora se le agrega que solo borre la que el usuario creo
/*$almacen->ExecuteTrans("delete from item_serial_temp where usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."'");*/
?>
