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
$conn = new Almacen();
$pendiente = false;
$pos=POS;
$login = new Login();
if (isset($_GET["cod"]) /* && isset($_GET["cod2"]) */) 
{
	
    $sql = "SELECT kd.id_item, kd.cantidad, kd.id_almacen_entrada, kd.id_ubi_entrada, i.descripcion1, i.codigo_barras FROM calidad_almacen_detalle AS kd, item AS i WHERE i.id_item = kd.id_item AND kd.id_transaccion = {$_GET["cod"]};";
    $productos_pendientes_entrada = $almacen->ObtenerFilasBySqlSelect($sql);
    $detalles_pendiente = $almacen->ObtenerFilasBySqlSelect("SELECT autorizado_por, observacion, id_documento FROM calidad_almacen WHERE id_transaccion = {$_GET["cod"]};");
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

//rubro
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes= $almacen->ObtenerFilasBySqlSelect("SELECT * FROM item where produccion not in (0,'') order by descripcion1");
foreach ($campos_comunes as $key => $item) 
{
    $arraySelectOption[] = $item["id_item"];
    $nombre_proveedor=$item["descripcion1"];
    $arraySelectoutPut[] = utf8_encode($nombre_proveedor);
}
$smarty->assign("option_values_rubro", $arraySelectOption);
$smarty->assign("option_output_rubro", $arraySelectoutPut);
//fin rubro

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

//Fin de los SELECTS para la nueva seccion

#################################################################################
if (isset($_POST["input_cantidad_items"])) 
{

    for ($j = 0; $j < (int) $_POST["input_cantidad_items"]; $j++)
    {
        $cadena_item[$j]=$_POST["_id_item"][$j];
        $cadena_cantidad[$j]=$_POST["_cantidad"][$j];
        if($cadena_cantidad[$j]<0)
        {
            echo "Entrada con Cantidad en Negativo, Verificar Datos";
            exit();
        }

    }
    
// si el usuario hizo post
    if (!$pendiente) 
    {
        if($_POST['rubro']=="")
        {
            echo "Error, rubro no puede estar vacio";
            exit();
        }

        $ubicacion= $conn->ObtenerFilasBySqlSelect("Select cod_almacen, id_ubicacion from parametros_generales");
        # Verificar que no se trata de una compra con estatus de entrega de productos "Pendiente"
        #list($dia, $mes, $anio) = explode('-', $_POST["input_fechacompra"]);
        $codigo_siga=$almacen->ObtenerFilasBySqlSelect("select codigo_siga from parametros_generales");
        $almacen->BeginTrans();
        $kardex_almacen_instruccion = "INSERT INTO transformaciones (`producto_id`, `cantidad`, `usuario_id`, `codigo_siga`)
        VALUES (
           '{$_POST["rubro"]}',
            '{$_POST["cantidad"]}', '{$login->getUsuario()}', '".$codigo_siga[0]['codigo_siga']."');";
        
        $almacen->ExecuteTrans($kardex_almacen_instruccion);
        
        
        $id_transaccion_transformacion = $almacen->getInsertID();


        //kardex salida
        $kardex_almacen_instruccion = "
        INSERT INTO kardex_almacen (
        `id_transaccion`, `tipo_movimiento_almacen`, `autorizado_por`,  `usuario_creacion`, `fecha_creacion`
        )
        VALUES (
        NULL, '12', '" . $_POST["autorizado_por"] . "', '" . $login->getUsuario() . "', CURRENT_TIMESTAMP);";

        $almacen->ExecuteTrans($kardex_almacen_instruccion);

        $id_transaccion_salida = $almacen->getInsertID();

        $update_codmov="UPDATE kardex_almacen SET cod_movimiento='TS-".$sucursal."-".$id_transaccion_salida."' WHERE `id_transaccion`=".$id_transaccion_salida."";

        $almacen->ExecuteTrans($update_codmov);


        //fin del kardex salida

        //kardex entrada
        $kardex_almacen_instruccion = "
        INSERT INTO kardex_almacen (
        `id_transaccion`, `tipo_movimiento_almacen`, `autorizado_por`,  `usuario_creacion`, `fecha_creacion`, referencia_salida
        )
        VALUES (
        NULL, '11', '" . $_POST["autorizado_por"] . "', '" . $login->getUsuario() . "', CURRENT_TIMESTAMP, '".$id_transaccion_salida."');";

        $almacen->ExecuteTrans($kardex_almacen_instruccion);

        $id_transaccion_entrada = $almacen->getInsertID();

        $update_codmov="UPDATE kardex_almacen SET cod_movimiento='TE-".$sucursal."-".$id_transaccion_entrada."' WHERE `id_transaccion`=".$id_transaccion_entrada."";

        $almacen->ExecuteTrans($update_codmov);
        //fin del kardex entrada
        
        for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) 
        {
            
            //kardex detalle salida
            $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$_POST["_id_item"][$i]}'";
            $precio_actual=$almacen->ObtenerFilasBySqlSelect($sql);

            $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);

            $kardex_almacen_detalle_instruccion = "
                INSERT INTO kardex_almacen_detalle (
                `id_transaccion_detalle`, `id_transaccion`, `id_almacen_entrada`, `id_almacen_salida`, `id_item`, `cantidad`,`id_ubi_salida`, `precio`)
                VALUES (
                NULL, '" . $id_transaccion_salida . "', '', '" . $ubicacion[0]['cod_almacen'] . "', '" . $_POST["_id_item"][$i] . "', '" . $_POST["_cantidad"][$i] . "', '" . $ubicacion[0]['id_ubicacion'] . "', ".$precioconiva.");";

                $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

                $pvender = $almacen->ObtenerFilasBySqlSelect("
                        select puede_vender from ubicacion
                        where id  = '".$ubicacion[0]['id_ubicacion']."'");
            //si se puede vender entonces debemos restar en piso de venta esa salida
            if($pvender[0]["puede_vender"]==1)
            {
                if(POS) 
                {
                    //obtenemos el itempos
                    $campoitempos = $almacen->ObtenerFilasBySqlSelect("
                               select itempos from item
                               where id_item  = '" . $_POST["_id_item"][$i] . "'");
                    if(count($campoitempos)>0)
                    {
                       // echo $campoitempos[0]["itempos"]; exit();
                        $campopos = $almacen->ObtenerFilasBySqlSelect("
                               select * from $pos.stockcurrent
                               where product  = '" .$campoitempos[0]["itempos"]. "'");
                        if(count($campopos)>0)
                        {
                            $campomodpos= $almacen->ExecuteTrans("update $pos.stockcurrent set UNITS=UNITS-{$_POST["_cantidad"][$i]} where product='".$campoitempos[0]["itempos"]."'");
                            
                        }
                        
                    }
                
                }
                
            }
            //item existencia
            $campos = $almacen->ObtenerFilasBySqlSelect("
                        select * from item_existencia_almacen
                        where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $ubicacion[0]['cod_almacen'] . "' and id_ubicacion ='". $ubicacion[0]['id_ubicacion'] . "'");

            if (count($campos) > 0) 
            {
                
                $cantidadExistente = $campos[0]["cantidad"];
                $almacen->ExecuteTrans("update item_existencia_almacen set cantidad = '" . ($cantidadExistente - $_POST["_cantidad"][$i]) . "'
                                        where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $ubicacion[0]['cod_almacen'] . "' and id_ubicacion='". $ubicacion[0]['id_ubicacion'] . "'");
            
            } else 
            {
                $instruccion = "INSERT INTO item_existencia_almacen(`cod_almacen`, `id_item`, `cantidad`,`id_ubicacion`) VALUES (
                        '" . $ubicacion[0]['cod_almacen'] . "', '" . $_POST["_id_item"][$i] . "', '" . $_POST["_cantidad"][$i] . "', '" . $ubicacion[0]['id_ubicacion'] . "');";
                $almacen->ExecuteTrans($instruccion);
            
            }
            //fin del item existencia
            
        } //fin del for

        //entrada detalle kardex
        $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$_POST["rubro"]}'";
            $precio_actual=$almacen->ObtenerFilasBySqlSelect($sql);

        $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);
        $kardex_almacen_detalle_instruccion = "INSERT INTO kardex_almacen_detalle (
                    `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`,`id_ubi_entrada`, `precio`)
                VALUES (
                    NULL, '{$id_transaccion_entrada}', '{$ubicacion[0]['cod_almacen']}',
                    '', '{$_POST["rubro"]}', '{$_POST["cantidad"]}','{$ubicacion[0]['id_ubicacion']}', ".$precioconiva.");";

        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

        $campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM item_existencia_almacen WHERE
                id_item  = '{$_POST["rubro"]}' AND id_ubicacion = '{$ubicacion[0]['id_ubicacion']}';");


        if (count($campos) > 0) 
        {
            $cantidadExistente = $campos[0]["cantidad"];
            $almacen->ExecuteTrans("UPDATE item_existencia_almacen 
                SET cantidad = '" . ($cantidadExistente + $_POST["cantidad"]) . "'
                WHERE id_item  = '{$_POST["rubro"]}' AND id_ubicacion = '{$ubicacion[0]['id_ubicacion']}';");

        } else 
        {
            $instruccion = "INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`,`id_ubicacion`)
                VALUES ('{$ubicacion[0]['cod_almacen']}', '{$_POST["rubro"]}', '{$_POST["cantidad"]}' , '{$ubicacion[0]['id_ubicacion']}');";
            $almacen->ExecuteTrans($instruccion);
        }

        $ubi=$ubicacion[0]['id_ubicacion'];
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
                if($filas!=0)
                {
                    $verificar=$almacen->ObtenerFilasBySqlSelect("select * from $pos.stockcurrent where product='$itempos' "); 
                    if($verificar[0]["PRODUCT"]!="")
                    {
                      $instruccion = "update $pos.stockcurrent set UNITS=(UNITS+{$_POST["cantidad"]}) WHERE PRODUCT='$itempos'";
                    }else
                    {
                        $instruccion = "insert into  $pos.stockcurrent values('0','$itempos',null,'".$_POST["cantidad"]."')"; 
                    }
                    
                    $almacen->ExecuteTrans($instruccion);

                    $instruccion="update $pos.products set PRICESELL=".$itemm[0]["precio1"]." where ID = '".$itemm[0]["itempos"]."'";

                    $almacen->ExecuteTrans($instruccion);

                }//fin del if de verificar stockcurrent
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
                
                    $instruccion = "INSERT INTO  $pos.stockcurrent (LOCATION, PRODUCT, UNITS) VALUES ('0','$itempos', '{$_POST["cantidad"]}')";
                    $almacen->ExecuteTrans($instruccion);
                
                    $instruccion = "INSERT INTO  $pos.products_cat ( PRODUCT) VALUES ('$itempos')";
                    $almacen->ExecuteTrans($instruccion);
                }
            }                     
        }
        //fin de entrada detalle kardex
        //relacion transformacion kardex
        $kardex_almacen_detalle_instruccion = "INSERT INTO transformacion_kardex (
                    `id` , `kardex_id` ,`transformacion_id`)
                VALUES (
                    NULL, '{$id_transaccion_salida}', '{$id_transaccion_transformacion}');";
        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
        
        $kardex_almacen_detalle_instruccion = "INSERT INTO transformacion_kardex (
                    `id` , `kardex_id` ,`transformacion_id`)
                VALUES (
                    NULL, '{$id_transaccion_entrada}', '{$id_transaccion_transformacion}');";

        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

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

        Msg::setMessage("<span style=\"color:#62875f;\">Entrada Generada Exitosamente </span>");
        header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
    }
}


?>
