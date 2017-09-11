<?php

include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");

$almacen = new Almacen();
$pendiente = false;
$pos=POS;
$tipo_log=$_GET['tipo_log'];
$smarty->assign("tipo_log", $tipo_log);
if (isset($_GET["cod"])) {

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


//Estados
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from ".DB_SELECTRA_PYMEPP.".estados_puntos");

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

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT `nombre_punto`,codigo_siga_punto as siga  from ".DB_SELECTRA_PYMEPP.".puntos_venta where estatus='A'");
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

    $sql="SELECT codigo_siga from parametros_generales limit 1";
    $codigosiga= $almacen->ObtenerFilasBySqlSelect($sql);
    $sucursal=$codigosiga[0]['codigo_siga'];

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

// si el usuario hizo post
    if (!$pendiente) 
    {

        $almacen->BeginTrans();
        $kardex_almacen_instruccion = "
        INSERT INTO pda_maestro(tipo, orden_compra,  observacion, id_proveedor, estatus, usuario_logistica, transporte) 
        VALUES (
            '".$_POST['tipo_log']."' ,  '{$_POST["nro_documento"]}',
             '{$_POST["observaciones"]}',  
             '{$_POST["id_proveedor"]}', 0, '{$login->getUsuario()}', '{$_POST["transporte"]}');";
        
        $almacen->ExecuteTrans($kardex_almacen_instruccion);
        $id_transaccion = $almacen->getInsertID();

       
        for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++) 
        {
             $fecha_planificacion = date("Y-m-d", strtotime(str_replace('/', '-', $_POST["input_fechaplanificacion_inicio"][$i])));
            $fecha_planificacion_fin = date("Y-m-d", strtotime(str_replace('/', '-', $_POST["input_fechaplanificacion_fin"][$i])));
            $kardex_almacen_detalle_instruccion = "INSERT INTO pda_detalle(id_pda_maestro, id_producto, cantida_x_kilo, id_instalacion_origen, fecha_inicio, fecha_fin, observaciones) 
                VALUES (
                    '{$id_transaccion}','{$_POST["_id_item"][$i]}','{$_POST["_cantidad"][$i]}', '{$_POST["instalacion"][$i]}', '".$fecha_planificacion."', '".$fecha_planificacion_fin."','{$_POST["observacion_detalle"][$i]}' );";

            $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

        }//fin del ciclo
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

}

?>
