<?php
include("../../../generalp.config.inc.php");
$comunes = new Comunes();
$tabla = $name_form = "pda_detalle";
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = @$_GET['busqueda'];
$punto = $comunes->ObtenerFilasBySqlSelect("SELECT `nombre_punto`,codigo_siga_punto as siga  from ".DB_SELECTRA_PYMEPP.".puntos_venta where estatus='A'");
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["siga"];
    $arraySelectOutPut1[] = $puntos["nombre_punto"];
}

$punto = $comunes->ObtenerFilasBySqlSelect("SELECT nombre_estado as nombre, codigo_estado as id  from ".DB_SELECTRA_PYMEPP.".estados");
unset($arraySelectOption);
unset($arraySelectOutPut1);
foreach ($punto as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["nombre"]);
}

$smarty->assign("option_values_estado", $arraySelectOption);
$smarty->assign("option_output_estado", $arraySelectOutPut1);

//camiones
$camiones = $comunes->ObtenerFilasBySqlSelect("SELECT id, concat(placa,' -- ',serial_carroceria) as nombre from transporte_camion");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($camiones as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["nombre"]);
}

$smarty->assign("option_values_camion", $arraySelectOption);
$smarty->assign("option_output_camion", $arraySelectOutPut1);
//camiones
$conductores = $comunes->ObtenerFilasBySqlSelect("SELECT id, concat(cedula,' -- ',nombres) as nombre from transporte_conductores");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($conductores as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["nombre"]);
}

$smarty->assign("option_values_conductor", $arraySelectOption);
$smarty->assign("option_output_conductor", $arraySelectOutPut1);
if(isset($_POST['buscar']) || $tipob!=NULL){
    if(!$tipob){
        $tipob=$_POST['palabra'];
        $des=$_POST['buscar'];
        $busqueda = $_POST['busqueda'];
    }

    switch($tipob){
        case "exacta":
            $instruccion=$comunes->buscar_exacta($tabla,$des,$busqueda);
            break;
        case "todas":
            $instruccion=$comunes->buscar_todas($tabla,$des,$busqueda);
            break;
        case "cualquiera":
            $instruccion=$comunes->buscar_cualquiera($tabla,$des,$busqueda);
            break;
    }
}else{
         $instruccion = "SELECT b.id as id, b.id_pda_maestro, a.orden_compra, d.descripcion1, e.marca, d.pesoxunidad, f.nombre_unidad, date_format(b.fecha_inicio, '%d/%m%Y') as fecha_planificacion, date_format(b.fecha_fin, '%d/%m%Y') as fecha_planificacion_fin, c.descripcion, b.cantida_x_kilo as cantidad FROM pda_maestro as a, pda_detalle as b, proveedores as c, item as d, marca as e, unidad_medida as f where a.id=b.id_pda_maestro and b.id_producto=d.id_item and a.id_proveedor=c.id_proveedor and d.id_marca=e.id and d.unidadxpeso=f.id and a.id=".$_GET['id']."

        union

        SELECT b.id as id, b.id_pda_maestro, a.orden_compra, d.descripcion1,e.marca, d.pesoxunidad, f.nombre_unidad, date_format(b.fecha_inicio, '%d/%m%Y') as fecha_planificacion,date_format(b.fecha_fin, '%d/%m%Y') as fecha_planificacion_fin, c.nombre_punto as descripcion, b.cantida_x_kilo as cantidad FROM pda_maestro as a, pda_detalle as b, ".DB_SELECTRA_PYMEPP.".puntos_venta as c, item as d, marca as e, unidad_medida as f where a.id=b.id_pda_maestro and b.id_producto=d.id_item and b.id_instalacion_origen=c.codigo_siga_punto and d.id_marca=e.id and d.unidadxpeso=f.id and a.id=".$_GET['id']

    ;
}



$num_paginas=$comunes->obtener_num_paginas($instruccion);
$pagina=$comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos=$comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros",$campos);
$smarty->assign("cabecera",array("OC","Fecha","Producto", "Proveedor"));
$smarty->assign("limitePaginacion",$comunes->LimitePaginaciones);
$smarty->assign("num_paginas",$num_paginas);
$smarty->assign("pagina",$pagina);


$smarty->assign("busqueda",$busqueda);
$smarty->assign("des",$des);
$smarty->assign("tipo",$tipob);
$smarty->assign("cantidadFilas",$comunes->getFilas());


$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= ".$_GET["opt_seccion"]);
$smarty->assign("campo_seccion",$campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("id","tipo"));
$smarty->assign("option_output", array("ID","Nombre Tipo"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//**************************************************************************
//**************************************************************************

//**************************************************************************
//Nombre del Formulario****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************


$smarty->assign("mensaje",$comunes->Notificacion());


?>
