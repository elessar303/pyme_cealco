<?php

include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/correlativos.php");
include("../../../menu_sistemas/lib/common.php") ;
$servicios = new Producto();
$correlativos = new Correlativos();
$campos_almacen = $servicios->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("campos_almacen" , $campos_almacen );
if(isset($_POST["aceptar"])){

	$servicios->BeginTrans();
	$nro_servicio = $correlativos->getUltimoCorrelativo("cod_servicio", 1,"si","S");
	$instruccion =  "
	INSERT INTO `item`
	(`cod_item`, `descripcion1`, `descripcion2`, `descripcion3`, `referencia`,
	`precio1`, `utilidad1`, `coniva1`, 
	`precio2`, `utilidad2`, `coniva2`, 
	`precio3`, `utilidad3`, `coniva3`, 
	`monto_exento`, `iva`, `cod_departamento`, 
	`cod_grupo`, `cod_linea`, 
	`estatus`,`usuario_creacion`,
	`fecha_creacion`, `cod_item_forma`,cuenta_contable1,
cuenta_contable2
	) VALUES
	(
	'".$nro_servicio."',
	'".$_POST["descripcion1"]."',
	'".$_POST["descripcion2"]."',
	'".$_POST["descripcion3"]."',
	'".$_POST["referencia"]."',
	'".$_POST["precio1"]."',
	'".$_POST["utilidad1"]."',
	'".$_POST["coniva1"]."',
	'".$_POST["precio2"]."',
	'".$_POST["utilidad2"]."',
	'".$_POST["coniva2"]."',
	'".$_POST["precio3"]."',
	'".$_POST["utilidad3"]."',
	'".$_POST["coniva3"]."',
	'".$_POST["monto_exento"]."',
	'".$_POST["iva"]."',
	
	'".$_POST["cod_departamento"]."',
	'".$_POST["cod_grupo"]."',
	'".$_POST["cod_linea"]."',
	
	'".$_POST["estatus"]."',
	'".$login->getUsuario()."',
	CURRENT_TIMESTAMP,
	2,
	'".$_POST["cuenta_contable1"]."',
	'".$_POST["cuenta_contable2"]."'
	);
	";
	
	
	$servicios->ExecuteTrans($instruccion);
	$numero_serv = $servicios->ObtenerFilasBySqlSelect("select max(id_item) as id_item from item where cod_item_forma=2");
	$numero_serv1=$numero_serv[0]["id_item"];
	$islr = $servicios->ObtenerFilasBySqlSelect("select * from lista_impuestos where cod_tipo_impuesto=2");
	$i=0;
	$cad='';
	while($islr[$i])
	{
		$nombre=$islr[$i][cod_impuesto];
		if($_POST[$nombre])
		{
			$instruccion="INSERT INTO servicios_islr values ('','".$numero_serv1."','".$_POST[$nombre]."')";
			$servicios->Execute2($instruccion);
		}
		$i++;
	}

	if($servicios->errorTransaccion==1){Msg::setMessage("<span style=\"color:#62875f;\">Servicio Generado Exitosamente con en Nro. ".$nro_servicio."</span>");}
	if($servicios->errorTransaccion==0){Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear el servicio.</span>");}
	$nro_servicio = $correlativos->getUltimoCorrelativo("cod_servicio", 1,"no","");
	$servicios->ExecuteTrans("update correlativos set contador = '".$nro_servicio."' where campo = 'cod_servicio'");
	$servicios->CommitTrans($servicios->errorTransaccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
	exit;
}

$smarty->assign("nro_servicioOLD",$correlativos->getUltimoCorrelativo("cod_servicio", 0,"si","S"));
$smarty->assign("nro_servicioNEW",$correlativos->getUltimoCorrelativo("cod_servicio", 1,"si","S"));


$ultimocodigo = $servicios->ObtenerFilasBySqlSelect("select cod_item from item  where cod_item_forma = 2 order by id_item desc limit 0,1");
$smarty->assign("ultimo_codigo",$ultimocodigo);

// Cargando departamentos en combo select
$campos_comunes = $servicios->ObtenerFilasBySqlSelect("select * from departamentos");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_departamento"];
}
$smarty->assign("option_values_departamentos",$arraySelectOption);
$smarty->assign("option_output_departamentos",$arraySelectoutPut);

// Cargando grupo en combo select
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $servicios->ObtenerFilasBySqlSelect("select * from grupo");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_grupo"];
}
$smarty->assign("option_values_grupo",$arraySelectOption);
$smarty->assign("option_output_grupo",$arraySelectoutPut);

// CONSULTA DE CUENTAS CONTABLES

$global=new bd(SELECTRA_CONF_PYME);
$sentencia="select * from nomempresa where bd='".$_SESSION['EmpresaFacturacion']."'";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();

$valueSELECT = "";
$outputSELECT =  "";
$contabilidad = $servicios->ObtenerFilasBySqlSelect("select * from ".$fila['bd_contabilidad'].".cwconcue where Tipo='P'");
foreach($contabilidad as $key => $cuenta){
    $valueSELECT[] = $cuenta["Cuenta"];
    $outputSELECT[] = $cuenta["Cuenta"]." - ".$cuenta["Descrip"];
}
$smarty->assign("option_values_cuenta",$valueSELECT);
$smarty->assign("option_output_cuenta",$outputSELECT);

// Cargando Linea en combo select
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $servicios->ObtenerFilasBySqlSelect("select * from linea");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_linea"];
}
$smarty->assign("option_values_linea",$arraySelectOption);
$smarty->assign("option_output_linea",$arraySelectoutPut);

//Cargar % I.V.A de la tabla de parametros generales.
$parametros_generales = $servicios->ObtenerFilasBySqlSelect("select * from parametros_generales");
$smarty->assign("parametros_generales",$parametros_generales );

//Cargar Almacenes
$almacenes= $servicios->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("almacenes",$almacenes );

$islr = $servicios->ObtenerFilasBySqlSelect("select * from lista_impuestos where cod_tipo_impuesto=2");
$smarty->assign("islr",$islr);

?>
