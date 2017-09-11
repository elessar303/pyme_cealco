<?php
//prueba

$comunes = new Comunes();
$tabla = $name_form = "localidad";
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];	
		$tabla="localidad  a ,estados b , estados c";
		$busqueda ="a.estado=b.id and a.estado_atiende=c.id and ".$_POST['busqueda'];
        $columnas="a.id,a.descripcion, b.nombre, c.nombre as nombreA";
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
    // $instruccion = "SELECT * FROM $tabla ";
   $instruccion = "SELECT a.id,a.descripcion, b.nombre, c.nombre as nombreA FROM localidad  a ,estados b , estados c where  a.estado=b.id and
 a.estado_atiende=c.id";
    
}



$num_paginas=$comunes->obtener_num_paginas($instruccion);
$pagina=$comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos=$comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros",$campos);
$smarty->assign("cabecera",array("Codigo","Descripción","Estado","Estado que atiende"));
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
$smarty->assign("option_values", array("a.id","a.descripcion","b.nombre","c.nombre"));
$smarty->assign("option_output", array("Id","Descripción","estado","estado que atiende"));
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
