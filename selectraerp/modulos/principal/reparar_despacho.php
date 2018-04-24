
<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);

require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
require_once("../../libs/php/clases/clase_db.inc.php");
include("../../libs/php/clases/correlativos.php");
include("../../libs/php/clases/factura.php");
include("../../libs/php/clases/almacen.php");

$conex1=$conn = new ConexionComun();
$correlativos = new Correlativos();
$factura = new Factura();
$almacen = new Almacen();
$login = new Login();
//cliente
$despacho=$_GET['despacho'];
//impresora
$impresora_serial=impresora_serial;
//usuario que la realiza
$id_usuario=$login->getIdUsuario();
//money
$money=$almacen->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");
if (empty($money)) 
{

    $sql="INSERT INTO closedcash_pyme(serial_caja, money, fecha_inicio, fecha_fin) VALUES ('".impresora_serial."', '".$_POST['serial'].date('Y-m-d_H:i:s')."',  now(), null)";
    $insert_money=$almacen->Execute2($sql);
}

$money=$almacen->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");

$sql="select * from despacho_new where fecha_pago='0000-00-00' group by id_cliente order by id_factura";
$clientes=$almacen->ObtenerFilasBySqlSelect($sql);
//se buscan los clientes con pedidos pendientos.
if($clientes!=null) 
{
    $almacen->BeginTrans();
    foreach ($clientes as $key => $value) 
    {
        //se filtra por cliente
        $sql="select * from despacho_new where fecha_pago='0000-00-00' and id_cliente='".$value['id_cliente']."' and id_factura <> ".$value['id_factura'];
        
        $pedidos_pendientes=$almacen->ObtenerFilasBySqlSelect($sql);
        $sql="select * from kardex_almacen where nro_factura=".$value['cod_factura'];
        //nuevo
        $kardex_maestro=$almacen->ObtenerFilasBySqlSelect($sql);
        foreach ($pedidos_pendientes as $key => $value2) 
        {
           $sql="select * from kardex_almacen where nro_factura=".$value2['cod_factura'];
           //viejo
           $kardex=$almacen->ObtenerFilasBySqlSelect($sql);
           
           //se cambia los detalles por el id del cliente principal
            $sql="update kardex_almacen_detalle set id_transaccion='".$kardex_maestro[0]['id_transaccion'] ."' where id_transaccion='".$kardex[0]['id_transaccion'] ."'";
             $almacen->ExecuteTrans($sql);
            //$sql="select * from despacho_new_detalle where id_factura='".$value['id_factura'] ."'";
            $sql="update despacho_new_detalle set id_factura='".$value['id_factura'] ."' where id_factura='".$value2['id_factura'] ."'";
            $almacen->ExecuteTrans($sql);
            $sql="update despacho_new set fecha_pago='".date('Y-m-d'). "' where id_factura=".$value2['id_factura'];
            //Secho $sql; exit(); exit();
            $almacen->ExecuteTrans($sql);
        }
    }

    if ($almacen->errorTransaccion == 1)
    {    
        echo "paso";   
       
    }
    elseif($almacen->errorTransaccion == 0)
    {
        
    }
    $almacen->CommitTrans($almacen->errorTransaccion);
}
 


