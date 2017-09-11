<?php

$comunes = new Comunes();
$tabla = $name_form = DB_SELECTRA_PYMEPP."kardex_13_17 k, ".DB_SELECTRA_PYMEPP.".puntos_venta pv";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];


if(!isset($_POST['fecha']))
{   
    $fecha_desde="01-01-2017";
    $_POST['fecha']=date('Y-m-d');
    $_POST['fecha2']=date('Y-m-d');
    $smarty->assign("fecha_desde", $fecha_desde);
}
if (isset($_POST['buscar']) || $tipob != NULL || isset($_POST['aceptar'])) 
{

    $smarty->assign("fecha_desde", $fecha_desde);
    
    //echo $_POST['punto'];
    $estados=$_POST['estados'];
    $punto=$_POST['punto'];
    $doc_siscol=$_POST['doc_siscol'];
    $documento=$_POST['documento'];
    $rif=$_POST['rif_proveedor'];
    $nombre_proveedor=$_POST['nombre_proveedor'];
    $guia=$_POST['guia'];
    $descripcion=$_POST['descripcion'];
    $codigo_barras=$_POST['codigo_barras'];

    $smarty->assign("fecha_desde", $_POST['fecha']);
    $smarty->assign("estados", $estados);
    $smarty->assign("punto", $punto);
    //$smarty->assign("doc_siscol", $doc_siscol);
    //$smarty->assign("documento", $documento);
    //$smarty->assign("rif_proveedor", $rif);
    //$smarty->assign("nombre_proveedor", $nombre_proveedor);
    //$smarty->assign("guia", $guia);
    //$smarty->assign("descripcion", $descripcion);
    $smarty->assign("codigo_barras", $codigo_barras);


    $date = new DateTime($_POST['fecha']);
    $_POST['fecha']=$date->format('Y-m-d');
    $date2 = new DateTime($_POST['fecha2']);
    $_POST['fecha2']=$date2->format('Y-m-d');
    $fecha1=$_POST['fecha'];
    $fecha2=$_POST['fecha2'];
    //se comienza a ordenar filtros
    if($estados==0)
    {
        $estados=""; 
    }
    else
    {
        $estados=" and codigo_estado='".$estados."' ";
    }
    if(!isset($punto) || trim($punto)=="" || $punto=='0' )
    {
        $punto=""; 

    }
    else
    {
        $punto=intval($punto);
        $punto=" and codigo_siga='".$punto."' ";
    }
    if(!isset($codigo_barras) || trim($codigo_barras)=="")
    {
        $codigo_barras="";
    }
    else
    {
        $codigo_barras=" and codigo_barras like '%".$codigo_barras."%' ";
    }

    /*$instruccion=" select a.cod_movimiento, a.fecha, a.id_documento, a.rif, a.guia_sunagro, a.nombre_proveedor, pv.nombre_punto, a.tipo_movimiento, a.observacion_cabecera  from ".DB_SELECTRA_PYMEPP.".kardex_13_17_s a, ".DB_SELECTRA_PYMEPP.".puntos_venta pv, ".DB_SELECTRA_PYMEPP.".estados as c
        where pv.codigo_estado_punto=c.codigo_estado and a.codigo_siga=SUBSTRING(pv.codigo_siga_punto, 4) and fecha between '".$_POST['fecha']."' and '".$_POST['fecha2']."' 
        ".$estados.$punto.$doc_siscol.$documento.$rif.$guia.$nombre_proveedor.$descripcion.$codigo_barras. " and a.tipo_movimiento='Descargo' group by a.cod_movimiento order by fecha, cod_movimiento desc";
    */
        
    $instruccion="SELECT fecha, codigo_barras, tipo_movimiento, nombre_punto AS establecimiento, rif, nombre_proveedor, CASE tipo_Movimiento WHEN  'Cargo' THEN SUM( cantidad ) END AS entrada, CASE tipo_Movimiento WHEN  'Descargo' THEN SUM( cantidad ) END AS salida FROM  ".DB_SELECTRA_PYMEPP.".kardex_13_17_e_s 
    WHERE fecha between '".$fecha1."' and '".$fecha2."' ".$estados." ".$punto." ".$codigo_barras."  
    GROUP BY codigo_barras, tipo_movimiento, fecha, establecimiento ORDER BY fecha, codigo_barras, establecimiento, tipo_movimiento";
//echo $instruccion; exit;

//filtro formulario

} else 
{
    if($_GET['instruccion']!=NULL)
    {

        $instruccion=$_GET['instruccion'];
    }
    else
    {
        $instruccion = "";    
    }

    
}
//echo $instruccion; exit();
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);
//Estados
$ubica=$comunes->ObtenerFilasBySqlSelect("select * from ".DB_SELECTRA_PYMEPP.".estados");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["nombre_estado"];
    $arrayubi[] = $item["codigo_estado"];
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

$smarty->assign("registros", $campos);
$smarty->assign("instruccion", $instruccion);
$smarty->assign("cabecera", array("Fecha", "Codigo de Barra", "Tipo Movimiento", "Establecimiento",  "RIF Proveedor","Nombre Proveedor", "Entrada", "Salida"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);


$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());


$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= " . $_GET["opt_seccion"]);
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("fecha", "codigo_barras", "tipo_movimiento", "establecimiento", "rif", "nombre_proveedor","entrada","salida"));
$smarty->assign("option_output", array("fecha", "codigo_barras", "tipo_movimiento", "establecimiento", "rif", "nombre_proveedor","entrada","salida"));
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


$smarty->assign("mensaje", "Ingrese los datos a buscar");

?>
