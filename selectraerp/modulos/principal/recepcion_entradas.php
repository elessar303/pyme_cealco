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
    $smarty->assign("doc_siscol", $doc_siscol);
    $smarty->assign("documento", $documento);
    $smarty->assign("rif_proveedor", $rif);
    $smarty->assign("nombre_proveedor", $nombre_proveedor);
    $smarty->assign("guia", $guia);
    $smarty->assign("descripcion", $descripcion);
    $smarty->assign("codigo_barras", $codigo_barras);


    $date = new DateTime($_POST['fecha']);
    $_POST['fecha']=$date->format('Y-m-d');
    $date2 = new DateTime($_POST['fecha2']);
    $_POST['fecha2']=$date2->format('Y-m-d');
    //se comienza a ordenar filtros
    if($estados==0)
    {
        $estados=""; 
    }
    else
    {
        $estados=" and c.codigo_estado='".$estados."' ";
    }
    if(!isset($punto) || trim($punto)=="" || $punto=='0' )
    {
        $punto=""; 

    }
    else
    {
        
        $punto=" and pv.codigo_siga_punto='".$punto."' ";
    }
    if(!isset($doc_siscol) || trim($doc_siscol)=="")
    {
        $doc_siscol="";
    }
    else
    {
        $doc_siscol=" and a.cod_movimiento like '%".$doc_siscol."%' ";
    }
    if(!isset($documento) || trim($documento)=="")
    {
        $documento="";
    }
    else
    {
        $documento=" and a.id_documento like '%".$documento."%' ";
    }
    if(!isset($rif) || trim($rif)=="")
    {
        $rif="";
    }
    else
    {
        $rif=" and a.rif like '%".$rif."%' ";
    }
    if(!isset($guia) || trim($guia)=="")
    {
        $guia="";
    }
    else
    {
        $guia=" and a.guia_sunagro like '%".$guia."%' ";
    }
    if(!isset($nombre_proveedor) || trim($nombre_proveedor)=="")
    {
        $nombre_proveedor="";
    }
    else
    {
        $nombre_proveedor=" and nombre_proveedor like '%".$nombre_proveedor."%' ";
    }
    if(!isset($descripcion) || trim($descripcion)=="")
    {
        $descripcion="";
    }
    else
    {
        $descripcion=" and observacion_cabecera like '%".$descripcion."%' ";
    }
    if(!isset($codigo_barras) || trim($codigo_barras)=="")
    {
        $codigo_barras="";
    }
    else
    {
        $codigo_barras=" and a.codigo_barras like '%".$codigo_barras."%' ";
    }

    $instruccion=" select a.cod_movimiento, a.fecha, a.id_documento, a.rif, a.guia_sunagro, a.nombre_proveedor, pv.nombre_punto, a.tipo_movimiento, a.observacion_cabecera  from ".DB_SELECTRA_PYMEPP.".kardex_13_17 a, ".DB_SELECTRA_PYMEPP.".puntos_venta pv, ".DB_SELECTRA_PYMEPP.".estados as c
        where pv.codigo_estado_punto=c.codigo_estado and a.codigo_siga=SUBSTRING(pv.codigo_siga_punto, 4) and fecha between '".$_POST['fecha']."' and '".$_POST['fecha2']."' 
        ".$estados.$punto.$doc_siscol.$documento.$rif.$guia.$nombre_proveedor.$descripcion.$codigo_barras. " and a.tipo_movimiento='Cargo' group by a.cod_movimiento order by fecha, cod_movimiento desc";


    /*if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];
        $busqueda = $_POST['busqueda'];
    }
    $join = "";
    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta($tabla, $des, $busqueda);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas($tabla, $des, $busqueda);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera($tabla, $des, $busqueda);
            break;
    }
    
    $instruccion = $instruccion . " and tipo_movimiento='Cargo' and k.codigo_siga=SUBSTRING(pv.codigo_siga_punto,4) group by cod_movimiento order by fecha desc";
    
    //exit(0);*/
    //echo $instruccion; 
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
$smarty->assign("cabecera", array("Doc. Siscol", "Fecha", "Descripci&oacute;n", "RIF Proveedor","Nombre Proveedor", "Documento"));
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
$smarty->assign("option_values", array("cod_movimiento", "fecha", "id_documento", "rif", "guia_sunagro","nombre_proveedor","nombre_punto"));
$smarty->assign("option_output", array("Doc. Siscol", "Fecha", "Documento", "Rif Proveedor", "Guia Sunagro", "Nombre Proveedor", "Almacen"));
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
