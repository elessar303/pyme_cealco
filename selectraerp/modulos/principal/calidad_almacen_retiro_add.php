<?php

include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");

$almacen = new Almacen();
$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());


if (isset($_POST["input_cantidad_items"]))
{ // si el usuario hizo post

    for ($j = 0; $j < (int) $_POST["input_cantidad_items"]; $j++){

    $cadena_item[$j]=$_POST["_id_item"][$j];
    $cadena_cantidad[$j]=$_POST["_cantidad"][$j];
        if($cadena_cantidad[$j]<0){
            echo "Traslado con Cantidad en Negativo, Verificar Datos";
            exit();
        }

    }

    $res = array_diff($cadena_item, array_diff(array_unique($cadena_item), array_diff_assoc($cadena_item, array_unique($cadena_item))));
    if($res[0]!=''){
    foreach(array_unique($res) as $v) {
        $sql="SELECT * FROM item WHERE id_item=".$v."";
        $item = $almacen->ObtenerFilasBySqlSelect($sql);
        echo "Producto Duplicado Durante la Operacion: ".$v." ".$item[0]['descripcion1']."<br/>";
        }
    exit();  
    }


    $codigo_siga=$almacen->ObtenerFilasBySqlSelect("select codigo_siga from parametros_generales");
    $almacen->BeginTrans();
    $pos=POS;
    $kardex_almacen_instruccion = "
    INSERT INTO calidad_almacen (
    `id_transaccion` ,
    `tipo_movimiento_almacen` ,
    `autorizado_por` ,
    `observacion` ,
    `fecha` ,
    `usuario_creacion`,
    `fecha_creacion`,
    `tipo_acta`
    )
    VALUES (
    NULL ,
    '5',
    '" . $_POST["autorizado_por"] . "',
    '" . $_POST["observaciones"] . "',
    '" . $_POST["input_fechacompra"] . "',
    '" . $login->getUsuario() . "',
    CURRENT_TIMESTAMP,
    2
    );";

    $almacen->ExecuteTrans($kardex_almacen_instruccion);

    $id_transaccion = $almacen->getInsertID();

    $cod_calidad="update calidad_almacen set cod_acta_calidad='CR-".$codigo_siga[0]['codigo_siga']."-{$id_transaccion}' where id_transaccion={$id_transaccion}";
    $almacen->ExecuteTrans($cod_calidad);

    for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++)
    {

        $kardex_almacen_detalle_instruccion = "
        INSERT INTO calidad_almacen_detalle (
        `id_transaccion_detalle` ,
        `id_transaccion` ,
        `id_almacen_salida` ,
        `id_item` ,
        `cantidad`,
        `id_ubi_salida`,
        `vencimiento`,
        `lote`, 
        `observacion`,
        `tipo_uso`
        
        )
        VALUES (
        NULL ,
        '" . $id_transaccion . "',
        '" . $_POST["_id_almacen"][$i] . "',
        '" . $_POST["_id_item"][$i] . "',
        '" . $_POST["_cantidad"][$i] . "',
        '" . $_POST["_id_ubicacion"][$i]. "',
        '" . $_POST["_vencimiento"][$i]. "',
        '" . $_POST["_lote"][$i]. "',
        '" . $_POST["_observacion1"][$i]. "',
        '" . $_POST["_tipo_uso"][$i]. "'
        
        );";

        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

        
                    
                     }
                 
    
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
