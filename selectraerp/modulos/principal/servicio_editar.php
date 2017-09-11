<?php

include("../../libs/php/clases/producto.php");

include("../../../menu_sistemas/lib/common.php") ;

$productos = new Producto();
$campos_almacen = $productos->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("campos_almacen" , $campos_almacen );


if(isset($_POST["aceptar"])){
	$instruccion =  "
	update item set
	cod_item = '".$_POST["cod_item"]."',
	descripcion1 = '".$_POST["descripcion1"]."',
	descripcion2 = '".$_POST["descripcion2"]."',
	descripcion3 = '".$_POST["descripcion3"]."',
	referencia = '".$_POST["referencia"]."',
	precio1 = '".$_POST["precio1"]."',
	utilidad1 = '".$_POST["utilidad1"]."',
	coniva1 = '".$_POST["coniva1"]."',
	precio2 = '".$_POST["precio2"]."',
	utilidad2 = '".$_POST["utilidad2"]."',
	coniva2 = '".$_POST["coniva2"]."',
	precio3 = '".$_POST["precio3"]."',
	utilidad3 = '".$_POST["utilidad3"]."',
	coniva3 = '".$_POST["coniva3"]."',
	monto_exento = '".$_POST["monto_exento"]."',
	iva = '".$_POST["iva"]."',
	cod_departamento = '".$_POST["cod_departamento"]."',
	cod_grupo = '".$_POST["cod_grupo"]."',
	cod_linea = '".$_POST["cod_linea"]."',
	estatus = '".$_POST["estatus"]."',
	cuenta_contable1 = '".$_POST["cuenta_contable1"]."',
	cuenta_contable2 = '".$_POST["cuenta_contable2"]."'
	where cod_item = '".$_POST["cod_item"]."'";
	
	$productos->Execute2($instruccion);

	$instruccion="delete from servicios_islr where cod_item='".$_POST["id_item"]."'";
	$productos->Execute2($instruccion);

	$islr = $productos->ObtenerFilasBySqlSelect("select * from lista_impuestos where cod_tipo_impuesto=2");
	$i=0;
	$cad='';
	while($islr[$i])
	{
		$nombre=$islr[$i][cod_impuesto];
		if(isset($_POST[$nombre]))
		{
			$instruccion="INSERT INTO servicios_islr values ('','".$_POST["id_item"]."','".$_POST[$nombre]."')";
			$productos->Execute2($instruccion);
		}
		$i++;
	}
	
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);

}


$campos_item = $productos->ObtenerFilasBySqlSelect("select * from item where id_item = ".$_GET["cod"]);
$smarty->assign("campos_item",$campos_item);

// Cargando departamentos en combo select
$campos_comunes = $productos->ObtenerFilasBySqlSelect("select * from departamentos");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_departamento"];
}
$smarty->assign("option_values_departamentos",$arraySelectOption);
$smarty->assign("option_output_departamentos",$arraySelectoutPut);
$smarty->assign("option_select_departamentos",$campos_item[0]["cod_departamento"]);

// Cargando grupo en combo select
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("select * from grupo");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_grupo"];
}
$smarty->assign("option_values_grupo",$arraySelectOption);
$smarty->assign("option_output_grupo",$arraySelectoutPut);
$smarty->assign("option_select_grupo",$campos_item[0]["cod_grupo"]);

// Cargando Linea en combo select
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("select * from linea");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_linea"];
}
$smarty->assign("option_values_linea",$arraySelectOption);
$smarty->assign("option_output_linea",$arraySelectoutPut);
$smarty->assign("option_select_linea",$campos_item[0]["cod_linea"]);

//Cargar % I.V.A de la tabla de parametros generales.
$parametros_generales = $productos->ObtenerFilasBySqlSelect("select * from parametros_generales");
$smarty->assign("parametros_generales",$parametros_generales );

// CONSULTA DE CUENTAS CONTABLES

$global=new bd(SELECTRA_CONF_PYME);
$sentencia="select * from nomempresa where bd='".$_SESSION['EmpresaFacturacion']."'";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();

$valueSELECT = "";
$outputSELECT =  "";
$contabilidad = $productos->ObtenerFilasBySqlSelect("select * from ".$fila['bd_contabilidad'].".cwconcue where Tipo='P'");
foreach($contabilidad as $key => $cuenta){
    $valueSELECT[] = $cuenta["Cuenta"];
    $outputSELECT[] = $cuenta["Cuenta"]." - ".$cuenta["Descrip"];
}
$smarty->assign("option_values_cuenta",$valueSELECT);
$smarty->assign("option_output_cuenta",$outputSELECT);
$smarty->assign("option_selected_cuenta1",$campos_item[0]["cuenta_contable1"]);
$smarty->assign("option_selected_cuenta2",$campos_item[0]["cuenta_contable2"]);


//Cargar Almacenes
$almacenes= $productos->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("almacenes",$almacenes );

$islr = $productos->ObtenerFilasBySqlSelect("select * from lista_impuestos where cod_tipo_impuesto=2");
$smarty->assign("islr",$islr);

$islrItem = $productos->ObtenerFilasBySqlSelect("select * from servicios_islr where cod_item=".$_GET["cod"]);
foreach($islrItem as $key => $item){
    $islrArray[] = $item["cod_lista_impuesto"];
}
$smarty->assign("islrItem",$islrArray);
$xxx=0;
$smarty->assign("xxx",$xxx);
?>
