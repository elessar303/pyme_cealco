<?php

$comunes = new Comunes();


$tabla = "vw_facturasxmedicos";
$name_form = "medicos";
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

/*	SELECT store_name, SUM(Sales)
FROM Store_Information
GROUP BY store_name*/

//echo "SELECT cod_edocuenta,id_proveedor,fecha_creacion,SUM(monto) as monto FROM ".$tabla." where medico_fk =".$_GET["id_proveedor"]." GROUP BY cod_edocuenta";

$instruccion = "SELECT cod_edocuenta,id_proveedor,descripcion,fecha_creacion,SUM(monto) as monto FROM ".$tabla." where medico_fk =".$_GET["id_proveedor"]." GROUP BY cod_edocuenta";

}
// as prov INNER JOIN tipo_proveedor_clasif as ti ON (prov.clase_proveedor = ti.id_pclasif)


$num_paginas=$comunes->obtener_num_paginas($instruccion);
$pagina=$comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos=$comunes->paginacion($pagina, $instruccion);

$data_parametros  = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
//$smarty->assign("idfiscal",$data_parametros);

foreach($data_parametros as $key => $item){
  $valueSELECT[] = $item["cod_empresa"];
  $outputidfiscalSELECT[] = $item["id_fiscal"];
}

$smarty->assign("registros",$campos);
$smarty->assign("cabecera",array("Cod.Edo Cuenta","Monto","Fecha/Hora de Emision","Descripcion",""));
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
$smarty->assign("option_values", array("cod_proveedor","descripcion"));
$smarty->assign("option_output", array("Cod. de Cuenta","Descripción"));
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
