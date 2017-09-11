<?php

include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");
$almacen = new Almacen();
$pos = POS;

$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());
$smarty->assign("anular", $login->anular_usuario());

$datos_almacen = $almacen->ObtenerFilasBySqlSelect("select * from almacen");
$valueSELECT = "";
$outputSELECT = "";
foreach ($datos_almacen as $key => $item) {
    $valueSELECT[] = $item["cod_almacen"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_almacen", $valueSELECT);
$smarty->assign("option_output_almacen", $outputSELECT);

$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from estados_puntos");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["nombre_estado"];
    $arrayubi[] = $item["codigo_estado"];
    
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

// punto de ventas
$arraySelectOption = "";
$arraySelectoutPut1 = "";
$cliente = new Almacen();
mysql_set_charset('utf8');
$punto = $cliente->ObtenerFilasBySqlSelect("SELECT `nombre_punto`,codigo_siga_punto as siga  from puntos_venta where estatus='A'");
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["siga"];
    $arraySelectOutPut1[] = $puntos["nombre_punto"];
}

$smarty->assign("option_values_punto", $arraySelectOption);
$smarty->assign("option_output_punto", $arraySelectOutPut1);

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT * from roles_firma where descripcion_rol=5");
foreach ($punto as $key => $puntos) {
    $arraySelectOption2[] = $puntos["id_rol"];
    $arraySelectOutPut2[] = "C.I: ".$puntos["cedula_persona"]." - ".$puntos["nombre_persona"];
}



$smarty->assign("option_values_aprobado", $arraySelectOption2);
$smarty->assign("option_output_aprobado", $arraySelectOutPut2);

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT * from roles_firma where descripcion_rol=2");
foreach ($punto as $key => $puntos) {
    $arraySelectOption3[] = $puntos["id_rol"];
    $arraySelectOutPut3[] = "C.I: ".$puntos["cedula_persona"]." - ".$puntos["nombre_persona"];
}

$smarty->assign("option_values_receptor", $arraySelectOption3);
$smarty->assign("option_output_receptor", $arraySelectOutPut3);

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT * from roles_firma where descripcion_rol=4");
foreach ($punto as $key => $puntos) {
    $arraySelectOption4[] = $puntos["id_rol"];
    $arraySelectOutPut4[] = "C.I: ".$puntos["cedula_persona"]." - ".$puntos["nombre_persona"];
}
$smarty->assign("option_values_seguridad", $arraySelectOption4);
$smarty->assign("option_output_seguridad", $arraySelectOutPut4);

$sql="SELECT a.id_cliente,a.nombre, k.estado, k.almacen_destino, k.observacion  from clientes a, kardex_almacen k where k.id_cliente=a.id_cliente and k.id_transaccion=".$_GET['cod']."";
//echo $sql; exit();
$punto = $cliente->ObtenerFilasBySqlSelect($sql);
$estado_pedido=$punto[0]['estado'];
$cliente_id=$punto[0]['id_cliente'];
$cliente_nombre=$punto[0]['nombre'];
$almacen_destino=$punto[0]['almacen_destino'];
$observacion_kardex=$punto[0]['observacion'];
if($cliente_id==0){
    $cliente_nombre='PDVAL';
}
$despacho = $cliente->ObtenerFilasBySqlSelect("SELECT * from tipo_despacho");
foreach ($despacho as $key => $puntos) 
{
    $arraySelectOption5[] = $puntos["id"];
    $arraySelectOutPut5[] = $puntos["descripcion"];
}
$smarty->assign("option_values_tipodespacho", $arraySelectOption5);
$smarty->assign("option_output_tipodespacho", $arraySelectOutPut5);

$smarty->assign("cliente_id", $cliente_id);
$smarty->assign("cliente_nombre", $cliente_nombre);
$smarty->assign("estado_pedido", $estado_pedido);
$smarty->assign("almacen_destino", $almacen_destino);
$smarty->assign("observacion_kardex", $observacion_kardex);
$id_transaccion=$_POST["nro_pedido"];
$opt_menu=$_GET['opt_menu'];
$opt_seccion=$_GET['opt_seccion'];

if (isset($_POST["aceptar"])) 
{  // si el usuario hizo post
    
   $sql="UPDATE kardex_almacen SET id_tipo_despacho='".$_POST['id_tipo_despacho']."' WHERE id_transaccion=".$id_transaccion; 
    $despacho = $almacen->ExecuteTrans($sql);
    $almacen->CommitTrans($despacho);
    echo '<script language="javascript" type="text/JavaScript">';
                    echo 'alert("Pedido Despachado Satisfactoriamente");';
                    echo 'window.location.href="index.php?opt_menu='.$opt_menu.'&opt_seccion='.$opt_seccion.'"'; 
                    echo '</script>';
}

?>
