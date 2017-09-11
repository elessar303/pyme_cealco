<?php
$comunes = new Comunes();
$tabla = $name_form = "tipo_impuesto";
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
			$instruccion=$comunes->buscar_exacta($tabla,$des,$busqueda,"","*, (
SELECT count(*) FROM `formulacion_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos,
(
SELECT count(*) FROM `lista_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos2");
			break;
		case "todas":
			$instruccion=$comunes->buscar_todas($tabla,$des,$busqueda,"","*, (
SELECT count(*) FROM `formulacion_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos,
(
SELECT count(*) FROM `lista_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos2");
			break;
		case "cualquiera":
			$instruccion=$comunes->buscar_cualquiera($tabla,$des,$busqueda,"","*, (
SELECT count(*) FROM `formulacion_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos,
(
SELECT count(*) FROM `lista_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos2");
			break;
	}
}else{
    $instruccion = "SELECT *, (
SELECT count(*) FROM `formulacion_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos,
(
SELECT count(*) FROM `lista_impuestos` where cod_tipo_impuesto = tipo_impuesto.cod_tipo_impuesto
) as hijos2 FROM ".$tabla;
}


$num_paginas=$comunes->obtener_num_paginas($instruccion);
$pagina=$comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos=$comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros",$campos);
$smarty->assign("cabecera",array("C&oacute;d. Tipo Impuesto","Nombre Impuesto","Cuenta contable"));
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
$smarty->assign("option_values", array("cod_tipo_impuesto","descripcion"));
$smarty->assign("option_output", array("Cod. Tipo Impuesto","Nombre Impuesto"));
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
