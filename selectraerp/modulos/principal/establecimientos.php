<?php
//prueba
$pymeC='selectrapyme_central';
$comunes = new Comunes();
$tabla = $name_form = $pymeC.".puntos_venta a, ".$pymeC.".estados b, ".$pymeC.".puntos_tipo c";
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

if(isset($_POST['buscar']) || $tipob!=NULL){
    if(!$tipob){
        $tipob=$_POST['palabra'];
        $des=$_POST['buscar'];  
        $tabla=$pymeC.".puntos_venta a, ".$pymeC.".estados b, ".$pymeC.".puntos_tipo c";
        $busqueda ="a.codigo_estado_punto =b.codigo_estado AND a.id_tipo=c.id_tipo and ".$_POST['busqueda'];
        $columnas="*";
    }

    switch($tipob){
        case "exacta":
            $instruccion=$comunes->buscar_exacta($tabla, $des, $busqueda,"",$columnas);
            break;
        case "todas":
            $instruccion=$comunes->buscar_todas($tabla, $des, $busqueda,"",$columnas);
            break;
        case "cualquiera":
            $instruccion=$comunes->buscar_cualquiera($tabla, $des, $busqueda,"",$columnas);
            break;
    }
}else{
   $instruccion = "SELECT * from $tabla WHERE a.codigo_estado_punto =b.codigo_estado AND a.id_tipo=c.id_tipo ORDER BY codigo_estado_punto, nombre_punto"; 
}

$num_paginas=$comunes->obtener_num_paginas($instruccion);
$pagina=$comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos=$comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros",$campos);
$smarty->assign("cabecera",array("Tipo Punto","Codigo Siga","Punto de Venta","Estado","IP","Sincronizacion","Status"));
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
// $smarty->assign("option_values", array("id","descripcion","estado","estado_atiende"));
$smarty->assign("option_values", array("a.codigo_siga_punto","a.nombre_punto","b.nombre_estado"));
$smarty->assign("option_output", array("Codigo Siga","Nombre Punto","Estado"));
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