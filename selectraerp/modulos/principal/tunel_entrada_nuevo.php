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


$almacen = new Almacen();
$pendiente = false;
$pos=POS;
if (isset($_GET["cod"]) /* && isset($_GET["cod2"]) */) {
	
    $sql = "SELECT kd.id_item, kd.cantidad, kd.id_almacen_entrada, kd.id_ubi_entrada, i.descripcion1, i.codigo_barras FROM kardex_almacen_detalle AS kd, item AS i WHERE i.id_item = kd.id_item AND kd.id_transaccion = {$_GET["cod"]};";
    $productos_pendientes_entrada = $almacen->ObtenerFilasBySqlSelect($sql);
    $detalles_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT autorizado_por, observacion, id_documento FROM kardex_almacen WHERE id_transaccion = {$_GET["cod"]};");
    $detalles_compra_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT num_factura_compra, num_cont_factura FROM compra WHERE id_compra = {$detalles_pendiente[0]["id_documento"]};");
    $smarty->assign("detalles_pendiente", $detalles_pendiente);
    $smarty->assign("productos_pendientes_entrada", $productos_pendientes_entrada);
    $smarty->assign("datos_factura", $detalles_compra_pendiente);
    $smarty->assign("cod", $_GET["cod"]);
    #$smarty->assign("cod2", $_GET["cod2"]);
    $pendiente = !$pendiente;
}

$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());
//cargando select de proveedores
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $almacen->ObtenerFilasBySqlSelect("SELECT * FROM proveedores order by descripcion");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $nombre_proveedor=$item["descripcion"]."-".$item["rif"];
    $arraySelectoutPut[] = utf8_encode($nombre_proveedor);
}
$smarty->assign("option_values_proveedor", $arraySelectOption);
$smarty->assign("option_output_proveedor", $arraySelectoutPut);

//Ingreso de SELECT para la nueva seccion
//Estados
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


mysql_set_charset('utf8');
$punto = $cliente->ObtenerFilasBySqlSelect("SELECT * from roles_firma where descripcion_rol=5");
foreach ($punto as $key => $puntos) {
    $arraySelectOption2[] = $puntos["id_rol"];
    $arraySelectOutPut2[] = "C.I: ".$puntos["cedula_persona"]." - ".$puntos["nombre_persona"];
}

$smarty->assign("option_values_aprobado", $arraySelectOption2);
$smarty->assign("option_output_aprobado", $arraySelectOutPut2);

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT * from roles_firma where descripcion_rol=3");
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


//Fin de los SELECTS para la nueva seccion

#################################################################################
if (isset($_POST["input_cantidad_items"])) { 

    $sql="SELECT codigo_siga from parametros_generales limit 1";
    $codigosiga= $almacen->ObtenerFilasBySqlSelect($sql);
    $sucursal=$codigosiga[0]['codigo_siga'];

    for ($j = 0; $j < (int) $_POST["input_cantidad_items"]; $j++){

    $cadena_item[$j]=$_POST["_id_item"][$j];
    $cadena_cantidad[$j]=$_POST["_cantidad"][$j];
        if($cadena_cantidad[$j]<0){
            echo "Entrada con Cantidad en Negativo, Verificar Datos";
            exit();
        }

    }

    
    //en cealco será normal que se repitan productos
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
    }*/
// si el usuario hizo post
    if (!$pendiente) 
    {# Verificar que no se trata de una compra con estatus de entrega de productos "Pendiente"
        #list($dia, $mes, $anio) = explode('-', $_POST["input_fechacompra"]);

        //Validamos si el conductor existe en la BD, de no existir lo insertamos
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

        $almacen->BeginTrans();
        $kardex_almacen_instruccion = "INSERT INTO kardex_almacen (
            `id_transaccion` , `tipo_movimiento_almacen`, `autorizado_por`,
            `observacion`, `fecha`, `usuario_creacion`,
            `fecha_creacion`, `estado`, `fecha_ejecucion`, `id_documento`, `empresa_transporte`, `id_conductor`, `placa`, `guia_sunagro`, `orden_despacho`, `almacen_procedencia`, `id_proveedor`,  `id_seguridad`, `id_aprobado`, `id_receptor`, `nro_contenedor`)
        VALUES (
            NULL , '3', '{$_POST["autorizado_por"]}',
            '{$_POST["observaciones"]}', '{$_POST["input_fechacompra"]}', '{$login->getUsuario()}', 
            CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP, '{$_POST["nro_documento"]}', '{$_POST["empresa_transporte"]}', '{$id_conductor[0]["id_conductor"]}', '{$_POST["placa"]}', '{$_POST["codigo_sica"]}', '{$_POST["orden_despacho"]}', '{$_POST["puntodeventa"]}', '{$_POST["id_proveedor"]}','{$_POST["id_seguridad"]}','{$_POST["id_aprobado"]}','{$_POST["id_receptor"]}', '{$_POST["nro_contenedor"]}');";
        
        $almacen->ExecuteTrans($kardex_almacen_instruccion);
        $id_transaccion = $almacen->getInsertID();

        $update_codmov="UPDATE kardex_almacen SET cod_movimiento='E-".$sucursal."-".$id_transaccion."' WHERE `id_transaccion`=".$id_transaccion."";

        $almacen->ExecuteTrans($update_codmov);

        $id_proveedor=$_POST["id_proveedor"];
        $observacion1=$_POST["observacion1"];
        $tipo='E';
        $status='R';
        if ($id_proveedor==1){
            $tipo='I';
        }
        if ($observacion1!=''){
            $status='RD';
        }

        //no se de quien es esto XD
        $entrada_control="INSERT INTO entrada_control (id_transaccion, ref_salida, fecha_salida, tipo, status, conciliado)
        VALUES ()
        ";

        for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) {

            
            //Se consulta el precio actual para dejar el historico en kardex (Junior)
            $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$_POST["_id_item"][$i]}'";
            $precio_actual=$almacen->ObtenerFilasBySqlSelect($sql);

            $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);

            $kardex_almacen_detalle_instruccion = "INSERT INTO kardex_almacen_detalle (
                    `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`,`id_ubi_entrada`, `vencimiento`,`elaboracion`,`lote`, `c_esperada`,`observacion`, `precio`)
                VALUES (
                    NULL, '{$id_transaccion}', '{$_POST["_id_almacen"][$i]}',
                    '', '{$_POST["_id_item"][$i]}', '{$_POST["_cantidad"][$i]}','{$_POST["_ubicacion"][$i]}','{$_POST["_vencimineto"][$i]}','{$_POST["_elaboracion"][$i]}','{$_POST["_lote"][$i]}','{$_POST["_c_esperada"][$i]}','{$_POST["_observacion"][$i]}', ".$precioconiva.");";

            $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

            $campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM item_existencia_almacen WHERE
                    id_item  = '{$_POST["_id_item"][$i]}' AND id_ubicacion = '{$_POST["_ubicacion"][$i]}' AND lote='{$_POST["_lote"][$i]}' AND id_proveedor='{$_POST["id_proveedor"]}';");

            if (count($campos) > 0) {
                $cantidadExistente = $campos[0]["cantidad"];
                $almacen->ExecuteTrans("UPDATE item_existencia_almacen 
                    SET cantidad = '" . ($cantidadExistente + $_POST["_cantidad"][$i]) . "'
                    WHERE id_item  = '{$_POST["_id_item"][$i]}' AND id_ubicacion = '{$_POST["_ubicacion"][$i]}' and lote='{$_POST["_lote"][$i]}' and id_proveedor='{$_POST["id_proveedor"]}';");
            } else {
                $instruccion = "INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`,`id_ubicacion`, `lote`, `id_proveedor`)
                    VALUES ('{$_POST["_id_almacen"][$i]}', '{$_POST["_id_item"][$i]}', '{$_POST["_cantidad"][$i]}' , '{$_POST["_ubicacion"][$i]}','{$_POST["_lote"][$i]}', '{$_POST["id_proveedor"]}');";
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

                $instruccion="update $pos.products set PRICESELL=".$itemm[0]["precio1"]." where ID = '".$itemm[0]["itempos"]."'";

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
                /*
                 * Actualizar los precios (2013-05-03)
                 * Codigo incluido para la actualizaciÃÂŗn de los precios despuÃÂŠs de hacer 
                 * entrada de una compra con entrega pendiente no considerado originalmente.
                 * Se actualizaban los precios independientemente del status de la compra.
                 */

                /* echo "</br>cantidad_inventario:" . $item_pendiente[0]["cantidad_inventario"];
                  echo "</br>costo_promedio:" . $item_pendiente[0]["costo_promedio"];
                  echo "</br>_item_preciosiniva:" . $producto_pendiente_compra[0]["_item_preciosiniva"];
                  echo "</br>_item_cantidad:" . $producto_pendiente_compra[0]["_item_cantidad"]; */

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
                /* Fin cÃÂŗdigo de actualizaciÃÂŗn de precios */

                /* Originalmente la siguiente era la ÃÂēnica instrucciÃÂŗn en este bloque */
                $almacen->ExecuteTrans("UPDATE item_existencia_almacen SET cantidad = " . ($existente[0]['cantidad'] + $cantidad_aditar[0]['cantidad']) . " WHERE id_item = '{$productos_pendientes_entrada[$i]["id_item"]}' AND cod_almacen = '{$productos_pendientes_entrada[$i]["id_almacen_entrada"]}' AND id_ubicacion = '{$productos_pendientes_entrada[$i]["id_ubi_entrada"]}';");
            } else {
                $almacen->ExecuteTrans("INSERT INTO item_existencia_almacen (`cantidad`, `id_item`, `cod_almacen`,`id_ubicacion`) VALUES ('" . $cantidad_aditar[0]['cantidad'] . "', '" . $cantidad_aditar[0]['id_item'] . "', '" . $cantidad_aditar[0]['id_almacen_entrada'] . "', '" . $cantidad_aditar[0]['id_ubi_entrada'] . "');");
            }
        }
        #################################################################################
    }


    if ($almacen->errorTransaccion == 1)
    {    
        //echo "paso";   
        Msg::setMessage("<span style=\"color:#62875f;\">Entrada Tunel Generada Exitosamente </span>");
    }
    elseif($almacen->errorTransaccion == 0)
    {
        //echo "no paso";
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de cargar la entrada Tunel, por favor intente nuevamente.</span>");
    }
    $almacen->CommitTrans($almacen->errorTransaccion);

   header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
//$almacen->ExecuteTrans("truncate table item_serial_temp"); 
//forma vieja de borrar la tabla, ahora se le agrega que solo borre la que el usuario creo
$almacen->ExecuteTrans("delete from item_serial_temp where usuario_creacion='".$login->getUsuario()."' and idsessionactual='".$login->getIdSessionActual()."'");
?>
