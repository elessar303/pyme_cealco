<?php

include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/correlativos.php");
include("../../../menu_sistemas/lib/common.php");

$proveedores = new Proveedores();
$correlativos = new Correlativos();
$pymeC="selectrapyme_central";

if(isset($_GET["cod"])){
$sql="select * from $pymeC.puntos_venta WHERE codigo_siga_punto='".$_GET["cod"]."'";
$campos = $proveedores->ObtenerFilasBySqlSelect($sql);
$smarty->assign("datos_punto",$campos);


$smarty->assign("option_selected_estados", $campos[0]["codigo_estado_punto"]);
$smarty->assign("option_selected_descripcion_tipo", $campos[0]["id_tipo"]);
$smarty->assign("option_selected_tipo_servidor", $campos[0]["tipo_servidor"]);
$smarty->assign("option_selected_tipo_conexion", $campos[0]["tipo_conexion"]);
$smarty->assign("option_selected_nombre_velocidad", $campos[0]["velocidad_conexion"]);
$smarty->assign("option_selected_nombre_proveedor", $campos[0]["proveedor_conexion"]);
$smarty->assign("option_selected_municipio", $campos[0]["municipio_id"]);
$smarty->assign("option_selected_parroquia", $campos[0]["parroquia_id"]);
$smarty->assign("option_selected_ciudad", $campos[0]["id_ciudad"]);
}

if (isset($_POST["aceptar"])) {

    if($num==0){
    $instruccion = "UPDATE $pymeC.puntos_venta SET `codigo_siga_punto`='".$_POST["codigo_siga"]."', `codigo_sica_punto`='".$_POST["codigo_sica"]."', `nombre_punto`='".$_POST["nombre_punto"]."', `direccion_punto`='".$_POST["direccion"]."', `codigo_estado_punto`='".$_POST["estados"]."', `tipo_sincro`=".$_POST["tipo_sincro"].", `estatus`='".$_POST["estatus"]."', `ip_punto_venta`='".$_POST["ip_punto"]."', `capacidad_frio`=".$_POST["frio"].", `capacidad_seco`=".$_POST["seco"].", `id_tipo`=".$_POST["tipo_punto"].", `numero_cajas`=".$_POST["cajas"].", `numero_servidores`=".$_POST["servidores"].", `tipo_servidor`='".$_POST["tipo_servidor"]."', `tipo_conexion`='".$_POST["tipo_conexion"]."', `velocidad_conexion`='".$_POST["velocidad"]."', `proveedor_conexion`='".$_POST["proveedor_conexion"]."', id_ciudad='".$_POST["ciudad"]."', municipio_id='".$_POST["municipio"]."', parroquia_id='".$_POST["parroquia"]."', nro_conexion='".$_POST["nro_conexion"]."', lineas_afiliadas='".$_POST["lineas_afiliadas"]."' WHERE id=".$_POST["id_punto"]."";

    //echo $instruccion; exit();

    $proveedores->ExecuteTrans($instruccion);

    $proveedores->CommitTrans(1);
    Msg::setMessage("<span style=\"color:#62875f;\">Punto de Venta Modificado exitosamente</span>");
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
    exit;
    
    }
    
}

//CONSULTA DE ID FISCAL EN PARAMETROS
$valueSELECT = "";
$outputSELECT = "";
$data_parametros = $proveedores->ObtenerFilasBySqlSelect("select * from parametros_generales");
foreach ($data_parametros as $key => $item) {
    $valueSELECT[] = $item["cod_empresa"];
    $outputidfiscalSELECT[] = $item["id_fiscal"];
    $outputidfiscal2SELECT[] = $item["id_fiscal2"];
}
$smarty->assign("option_values_parametros", $valueSELECT);
$smarty->assign("option_output_idfiscal", $outputidfiscalSELECT);
$smarty->assign("option_output_idfiscal2", $outputidfiscal2SELECT);

// Cargando tipo_comercio en combo select
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_comercio");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["cod_tipo_comercio"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_comercio", $arraySelectOption);
$smarty->assign("option_output_tipo_comercio", $arraySelectoutPut);

//Municipio
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from selectrapyme_central.municipio, selectrapyme_central.estados WHERE municipio.estado_fk=estados.codigo_estado");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = utf8_decode($item["nombre"]." - ".$item["nombre_estado"]);
    $arraySelectoutPut[] = $item["municipio_id"];
}
$smarty->assign("option_values_municipio", $arraySelectoutPut);
$smarty->assign("option_output_municipio", $arraySelectOption);



//Parroquia
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select *,  parroquia.nombre as nombre_parroquia, municipio.nombre as nombre_mun from selectrapyme_central.parroquia, selectrapyme_central.municipio, selectrapyme_central.estados WHERE parroquia.municipio_fk=municipio.municipio_id and municipio.estado_fk=estados.codigo_estado");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = utf8_decode ($item["nombre_parroquia"]. " - ".$item["nombre_mun"]." - ".$item["nombre_estado"]);
    $arraySelectoutPut[] = $item["parroquia_id"];
}
$smarty->assign("option_values_parroquia", $arraySelectoutPut);
$smarty->assign("option_output_parroquia", $arraySelectOption);


//Ciudades
$arraySelectOption="";
$arraySelectoutPut="";
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from selectrapyme_central.ciudades, selectrapyme_central.estados WHERE ciudades.id_estado=estados.codigo_estado");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = utf8_decode ($item["nombre_ciudad"]. " - ".$item["nombre_estado"]);
    $arraySelectoutPut[] = $item["id_ciudad"];
}
$smarty->assign("option_values_ciudades", $arraySelectoutPut);
$smarty->assign("option_output_ciudades", $arraySelectOption);

//Clasificicacion
$valueSELECT = "";
$outputSELECT = "";
$tprecio = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_proveedor_clasif order by clasificacion ASC");
foreach ($tprecio as $key => $item) {
    $valueSELECT[] = $item["id_pclasif"];
    $outputSELECT[] = $item["clasificacion"];
}
$smarty->assign("option_values_clasi", $valueSELECT);
$smarty->assign("option_output_clasi", $outputSELECT);
$smarty->assign("option_selected_clasi", $datacliente[0]["id_pclasif"]);

// Cargando tipo_proveedor en combo select
$arraySelectOption = $arraySelectoutPut = "";
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_proveedor");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["cod_tipo_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_proveedor", $arraySelectOption);
$smarty->assign("option_output_tipo_proveedor", $arraySelectoutPut);

// Cargando tipo_proveedor en combo select
$arraySelectOption = $arraySelectoutPut = "";
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_proveedor");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["cod_tipo_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_proveedor", $arraySelectoutPut);
$smarty->assign("option_output_tipo_proveedor", $arraySelectoutPut);

// Cargando tipo_comercio en combo select
$arraySelectOption = $arraySelectoutPut = "";
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_origen_proveedor");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["cod_tipo_origen_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_origen_proveedor", $arraySelectOption);
$smarty->assign("option_output_tipo_origen_proveedor", $arraySelectoutPut);

//TIPO DE ENTIDAD
$valueSELECT = "";
$outputSELECT = "";
$tprecio = $proveedores->ObtenerFilasBySqlSelect("select * from entidades");
foreach ($tprecio as $key => $item) {
    $valueSELECT[] = $item["cod_entidad"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_entidad", $valueSELECT);
$smarty->assign("option_output_entidad", $outputSELECT);
$smarty->assign("option_selected_entidad", $datacliente[0]["cod_entidad"]);

//ESPECIALIDAD
$valueSELECT = "";
$outputSELECT = "";
$tprecio = $proveedores->ObtenerFilasBySqlSelect("select * from especialidades_proveedor order by especialidad ASC");
foreach ($tprecio as $key => $item) {
    $valueSELECT[] = $item["cod_especialidad"];
    $outputSELECT[] = $item["especialidad"];
}
$smarty->assign("option_values_especialidad", $valueSELECT);
$smarty->assign("option_output_especialidad", $outputSELECT);
$smarty->assign("option_selected_especialidad", $datacliente[0]["cod_especialidad"]);

// RETENCION DEL I.V.A.
$valueSELECT = "";
$outputSELECT = "";
$tprecio = $proveedores->ObtenerFilasBySqlSelect("select * from lista_impuestos where cod_tipo_impuesto = 1");
foreach ($tprecio as $key => $item) {
    $valueSELECT[] = $item["cod_impuesto"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_impuesto", $valueSELECT);
$smarty->assign("option_output_impuesto", $outputSELECT);
$smarty->assign("option_selected_impuesto", $datacliente[0]["cod_impuesto_proveedor"]);

//Cargar % I.V.A de la tabla de parametros generales.
$parametros_generales = $proveedores->ObtenerFilasBySqlSelect("select * from parametros_generales");
$smarty->assign("parametros_generales", $parametros_generales);

//Cargar Almacenes
$almacenes = $proveedores->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("almacenes", $almacenes);

$ubica=$proveedores->ObtenerFilasBySqlSelect("select * from selectrapyme_central.estados");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["nombre_estado"];
    $arrayubi[] = $item["codigo_estado"];
    
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

$ubica=$proveedores->ObtenerFilasBySqlSelect("select * from selectrapyme_central.puntos_tipo");

foreach ($ubica as $key => $item) {
    $arrayubiNi[] = $item["descripcion_tipo"];
    $arrayubii[] = $item["id_tipo"];
    
}
$smarty->assign("option_values_descripcion_tipo", $arrayubiNi);
$smarty->assign("option_values_id_tipo", $arrayubii);

$arrayubiNi="";
$arrayubii="";
$ubica=$proveedores->ObtenerFilasBySqlSelect("select * from selectrapyme_central.proveedor_conexion");

foreach ($ubica as $key => $item) {
    $arrayubiNi[] = $item["nombre_proveedor"];
    $arrayubii[] = $item["id_proveedor_conexion"];
    
}
$smarty->assign("option_values_nombre_proveedor", $arrayubiNi);
$smarty->assign("option_values_id_proveedor_conexion", $arrayubii);

$arrayubiNi="";
$arrayubii="";
$ubica=$proveedores->ObtenerFilasBySqlSelect("select * from selectrapyme_central.velocidad_conexion");

foreach ($ubica as $key => $item) {
    $arrayubiNi[] = $item["nombre_velocidad"];
    $arrayubii[] = $item["id_velocidad"];
    
}
$smarty->assign("option_values_nombre_velocidad", $arrayubiNi);
$smarty->assign("option_values_id_velocidad", $arrayubii);

$arrayubiNi="";
$arrayubii="";
$ubica=$proveedores->ObtenerFilasBySqlSelect("select * from selectrapyme_central.tipo_conexion");

foreach ($ubica as $key => $item) {
    $arrayubiNi[] = $item["nombre_conexion"];
    $arrayubii[] = $item["id_tipo_conexion"];
    
}
$smarty->assign("option_values_nombre_conexion", $arrayubiNi);
$smarty->assign("option_values_id_tipo_conexion", $arrayubii);

$arrayubiNi="";
$arrayubii="";
$ubica=$proveedores->ObtenerFilasBySqlSelect("select * from selectrapyme_central.conexion_voz");

foreach ($ubica as $key => $item) {
    $arrayubiNi[] = $item["conexion_voz"];
    $arrayubii[] = $item["id_voz"];
    
}
$smarty->assign("option_values_voz_conexion", $arrayubiNi);
$smarty->assign("option_values_id_voz_conexion", $arrayubii);

$arrayubiNi="";
$arrayubii="";
$ubica=$proveedores->ObtenerFilasBySqlSelect("select * from selectrapyme_central.tipo_servidor");

foreach ($ubica as $key => $item) {
    $arrayubiNi[] = $item["nombre_servidor"];
    $arrayubii[] = $item["id_tipo_servidor"];
    
}
$smarty->assign("option_values_nombre_servidor", $arrayubiNi);
$smarty->assign("option_values_id_tipo_servidor", $arrayubii);
// CONSULTA DE CUENTAS CONTABLES
$global = new bd(SELECTRA_CONF_PYME);
$sentencia = "select * from nomempresa where bd='" . $_SESSION['EmpresaFacturacion'] . "'";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();

$valueSELECT = "";
$outputSELECT = "";
$contabilidad = $proveedores->ObtenerFilasBySqlSelect("select * from " . $fila['bd_contabilidad'] . ".cwconcue where Tipo='P'");
foreach ($contabilidad as $key => $cuenta) {
    $valueSELECT[] = $cuenta["Cuenta"];
    $outputSELECT[] = $cuenta["Cuenta"] . " - " . $cuenta["Descrip"];
}
$smarty->assign("option_values_cuenta", $valueSELECT);
$smarty->assign("option_output_cuenta", $outputSELECT);
?>
