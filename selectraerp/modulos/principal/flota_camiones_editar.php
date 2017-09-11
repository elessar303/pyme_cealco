<?php
include("../../libs/php/clases/vendedor.php");
$vendedor = new Vendedor();
$comunes = new Comunes();

if(isset($_GET['cod']))
{
	$sql="select * from transporte_camion where id=".$_GET['cod'];
	$datos=$comunes->ObtenerFilasBySqlSelect($sql);
	$smarty->assign("datos", $datos);
	$smarty->assign("id", $_GET['cod']);
}

$punto = $comunes->ObtenerFilasBySqlSelect("SELECT nombre_estado as nombre, codigo_estado as id  from ".DB_SELECTRA_PYMEPP.".estados");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($punto as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["nombre"]);
}


$smarty->assign("option_values_estado", $arraySelectOption);
$smarty->assign("option_output_estado", $arraySelectOutPut1);
$smarty->assign("estado", $datos[0]['flota_asignada']);

//booleanos
$booleanosOption[]=1;
$booleanosOption[]=0;
$booleanosOuput[]="SI";
$booleanosOuput[]="NO";
$smarty->assign("option_values_booleano", $booleanosOption);
$smarty->assign("option_output_booleano", $booleanosOuput);
$smarty->assign("caucho", $datos[0]['posee_caucho_repuesto']);
$smarty->assign("herramientas", $datos[0]['posee_herramientas']);
$smarty->assign("recuperado", $datos[0]['vehiculo_recuperado']);
$smarty->assign("propio", $datos[0]['vehiculo_propio']);

//estatus Vehiculo
$vehiculo_estatus = $comunes->ObtenerFilasBySqlSelect("SELECT id, descripcion  from estatus_vehiculo");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($vehiculo_estatus as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["descripcion"]);
}

$smarty->assign("option_values_estatus_vehiculo", $arraySelectOption);
$smarty->assign("option_output_estatus_vehiculo", $arraySelectOutPut1);
$smarty->assign("estatus", $datos[0]['estatus_vehiculo']);
//marca
$vehiculo_marca = $comunes->ObtenerFilasBySqlSelect("SELECT id, descripcion  from transporte_marca");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($vehiculo_marca as $key => $puntos)
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["descripcion"]);
}

$smarty->assign("option_values_marca_vehiculo", $arraySelectOption);
$smarty->assign("option_output_marca_vehiculo", $arraySelectOutPut1);
$smarty->assign("marca", $datos[0]['marca']);
//modelo
//marca
$vehiculo_marca = $comunes->ObtenerFilasBySqlSelect("SELECT id, descripcion_modelo  from transporte_modelo where id_marca=".$datos[0]['marca']);
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($vehiculo_marca as $key => $puntos)
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["descripcion_modelo"]);
}

$smarty->assign("option_values_modelo_vehiculo", $arraySelectOption);
$smarty->assign("option_output_modelo_vehiculo", $arraySelectOutPut1);
$smarty->assign("modelo", $datos[0]['modelo']);
//tipo vehiculo
$vehiculo_tipo = $comunes->ObtenerFilasBySqlSelect("SELECT id, descripcion  from tipo_vehiculo");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($vehiculo_tipo as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["descripcion"]);
}

$smarty->assign("option_values_tipo_vehiculo", $arraySelectOption);
$smarty->assign("option_output_tipo_vehiculo", $arraySelectOutPut1);
$smarty->assign("tipo", $datos[0]['tipo_vehiculo']);
//obtenemos la fecha, si hubo cambio en el kilometraje cambiamos la fecha
$hoy=getdate();
$hoy=$hoy['year']."-".$hoy['mon']."-".$hoy['mday'];
if($_POST['ultimok']!=$_POST['ultimo_kilometraje'])
{
	$_POST['fecha_ultimok']=$hoy;
}

if(isset($_POST["placa"])){
	$date = new DateTime($_POST['fecha_kilometraje']);
	
	$instruccion = "
	update  `transporte_camion`
	set 
	`unidad`='".$_POST["unidad"]."',
	`placa`=	'".$_POST["placa"]."',
	`serial_motor`=	'".$_POST["serial_motor"]."',
	`serial_carroceria`=	'".$_POST["serial_carroceria"]."',
	`marca`= '".$_POST["marca"]."',
	`modelo`= '".$_POST["modelo"]."',
	`anio_vehiculo`= '".$_POST["anio_vehiculo"]."',
	`cantidad_ejes`= '".$_POST["cantidad_ejes"]."',
	`posee_caucho_repuesto`=	'".$_POST["posee_caucho"]."',
	`posee_herramientas`=	'".$_POST["herramientas"]."',
	`ultimo_kilometraje`=	'".$_POST["ultimo_kilometraje"]."',
	`fecha_kilometraje`=	'".$_POST['fecha_ultimok']."',
	`serial_gps`=	'".$_POST["serial_gps"]."',
	`alias_gps`=	'".$_POST["alias_gps"]."',
	`estatus_vehiculo`=	'".$_POST["estatus_vehiculo"]."',
	`flota_asignada`=	'".$_POST["flota_asignada"]."',
	`tipo_vehiculo`=	'".$_POST["tipo_vehiculo"]."',
	`capacidad_carga_ton`=	'".$_POST["capacidad"]."',
	`vehiculo_recuperado`=	'".$_POST["vehiculo_recuperado"]."',
	`vehiculo_propio`=	'".$_POST["vehiculo_propio"]."'
	where
	id=".$_POST['id'];
	    
	$comunes->Execute2($instruccion);
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

?>
