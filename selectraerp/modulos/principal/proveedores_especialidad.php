<?php

$comunes = new Comunes();
$tabla = $name_form = "especialidades_proveedor";
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

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
    $instruccion = "SELECT * FROM ".$tabla;
}
$num_paginas=$comunes->obtener_num_paginas($instruccion);
$pagina=$comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos=$comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros",$campos);
$smarty->assign("cabecera",array("C&oacute;d. Especialidad","Especialidad"));
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
$smarty->assign("option_values", array("cod_especialidad","especialidad"));
$smarty->assign("option_output", array("Cod. Especialidad","Especialidad"));
$smarty->assign("option_selected", $busqueda);
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
