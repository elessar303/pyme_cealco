<?php



$comunes = new Comunes();
$agregarMoneda=$_GET['agregarMoneda'];
$tabla = "divisas";
$name_form = "divisas";
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
    $instruccion = "SELECT * FROM divisas";
}



$num_paginas=$comunes->obtener_num_paginas($instruccion);
$pagina=$comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos=$comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros",$campos);
$smarty->assign("cabecera",array("Codigo","Divisa"));
$smarty->assign("limitePaginacion",$comunes->LimitePaginaciones);
$smarty->assign("num_paginas",$num_paginas);
$smarty->assign("pagina",$pagina);


$smarty->assign("agregarMoneda",$agregarMoneda);
$smarty->assign("busqueda",$busqueda);
$smarty->assign("des",$des);
$smarty->assign("tipo",$tipob);
$smarty->assign("cantidadFilas",$comunes->getFilas());


//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("id_divisa","Nombre"));
$smarty->assign("option_output", array("Cod. Divisa","Nombre Divisa"));
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
