<?php

require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
$pos = POS;

$sql="SELECT codigo_siga, servidor FROM $pyme.parametros_generales";
$array_sucursal=$almacen->ObtenerFilasBySqlSelect($sql);
foreach ($array_sucursal as $key => $value) {
$sucursal=$value['codigo_siga']; 
$servidor=$value['servidor']; 
}

$opt_menu=$_GET['opt_menu'];
$opt_seccion=$_GET['opt_seccion'];
$observacion=$_POST['observaciones'];
$almacen_destino=$_POST["puntodeventa"];
$fecha_ejecucion=$_POST["input_fechacompra"];
$placa=$_POST["placa"];
$color=$_POST["color"];
$marca=$_POST["marca"];
$prescintos=$_POST["prescintos"];
$id_transaccion=$_POST["nro_pedido"];
$id_seguridad=$_POST["id_seguridad"];
$id_aprobado=$_POST["id_aprobado"];
$id_despachador=$_POST["id_despachador"];

$cod_movimiento='S-'.$sucursal.'-'.$id_transaccion;

$id_conductor = $almacen->ObtenerFilasBySqlSelect("SELECT id_conductor FROM conductores WHERE
                    cedula_conductor  = '{$_POST["nacionalidad_conductor"]}{$_POST["cedula_conductor"]} limit 1';");
$conductor = $almacen->getFilas($id_conductor);
        if ($conductor == 0) {
            $instruccion = "INSERT INTO conductores ( `nombre_conductor`,`cedula_conductor`)
                    VALUES ('{$_POST["conductor"]}','{$_POST["nacionalidad_conductor"]}{$_POST["cedula_conductor"]}');";
            $almacen->ExecuteTrans($instruccion); 
            //Luego de insertar el nuevo conductor en la BD capturo su ID par la tabla de Kardex Almacen
            $id_conductor=$almacen->ObtenerFilasBySqlSelect("SELECT id_conductor FROM conductores WHERE
                    cedula_conductor  = '{$_POST["nacionalidad_conductor"]}{$_POST["cedula_conductor"]}' limit 1;");
        }
if (isset($_POST["aceptar"])) { 
$login=new Login();
$usuario=$login->getUsuario();
$sql="UPDATE kardex_almacen SET observacion_despacho='".$observacion."', fecha_ejecucion=CURRENT_TIMESTAMP, placa='".$placa."', color='".$color."', marca='".$marca."', prescintos='".$prescintos."', id_tipo_despacho='".$_POST['id_tipo_despacho']."', id_jornada='".$_POST['id_jornada']."', id_conductor=".$id_conductor[0]['id_conductor'].", cod_movimiento='".$cod_movimiento."', estado='Despachado', usuario_despacho='".$usuario."',id_seguridad=".$id_seguridad.",id_aprobado=".$id_aprobado.",id_despachador=".$id_despachador."   WHERE id_transaccion=".$id_transaccion.""; 
//echo $sql; exit();
$despacho = $almacen->ExecuteTrans($sql);
$almacen->CommitTrans($despacho);
echo '<script language="javascript" type="text/JavaScript">';
                echo 'alert("Pedido Despachado Satisfactoriamente");';
                echo 'window.location.href="index.php?opt_menu'.$opt_menu.'"'; 
                echo '</script>';
}

if (isset($_POST["anular"])) { 
$login=new Login();
$usuario=$login->getUsuario();
$sql="SELECT * from kardex_almacen_detalle WHERE id_transaccion = ".$id_transaccion."";
$productos=$almacen->ObtenerFilasBySqlSelect($sql);
foreach ($productos as $value) {
    $sql="SELECT * from  item_existencia_almacen WHERE id_item = ".$value['id_item']." and  id_ubicacion=".$value['id_ubi_salida']." and cod_almacen=".$value['id_almacen_salida']."";
    $exitencia=$almacen->ObtenerFilasBySqlSelect($sql);
    $sql="UPDATE item_existencia_almacen SET cantidad=".$exitencia[0]['cantidad']." + ".$value['cantidad']."
WHERE id_item = ".$value['id_item']." and id_ubicacion=".$value['id_ubi_salida']." and cod_almacen=".$value['id_almacen_salida']."";
    $update=$almacen->ExecuteTrans($sql);
}
$sql="UPDATE kardex_almacen SET observacion='Pedido Anulado', usuario_anulacion='".$usuario."' WHERE id_transaccion = ".$id_transaccion."";
//echo $sql; exit();
$update2=$almacen->ExecuteTrans($sql);
$almacen->CommitTrans($despacho);
if ($almacen==1){
echo '<script language="javascript" type="text/JavaScript">';
                echo 'alert("Pedido Anulado Satisfactoriamente");';
                echo 'window.location.href="index.php?opt_menu'.$opt_menu.'"'; 
                echo '</script>';
}
}
?>