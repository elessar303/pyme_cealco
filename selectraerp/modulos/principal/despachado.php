<?php
//

$comunes = new Comunes();
$tabla = $name_form = "despacho";
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];		
		$tabla="despacho des,factura fac, clientes cli";
		$busqueda ="des.id_factura=fac.id_factura and cli.id_cliente=fac.id_cliente and des.estatus=1 and ".$_POST['busqueda'];
        $columnas="des.*, fac.cod_factura, cli.rif as rif_cliente";
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
    $instruccion = "SELECT des.*, fac.cod_factura, cli.rif as rif_cliente FROM despacho des,factura fac, clientes cli where des.id_factura=fac.id_factura and cli.id_cliente=fac.id_cliente and des.estatus=1";
}



$num_paginas=$comunes->obtener_num_paginas($instruccion);
$pagina=$comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos=$comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros",$campos);
$smarty->assign("cabecera",array("RIF","Codigo","Factura","Fecha Creacion","Estatus"));
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
$smarty->assign("option_values", array("rif","cod_despacho","cod_factura","fecha_creacion"));
$smarty->assign("option_output", array("RIF","Codigo","Factura","Fecha Creacion"));
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
