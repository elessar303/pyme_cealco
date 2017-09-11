<?php
session_start();
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/comunes.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
require_once("../../config.ini.php");
require_once('../../../generalp.config.inc.php');
require_once("../../../general.config.inc.php");
require_once('../../../includes/clases/BDControlador.php');



$pyme=DB_SELECTRA_FAC;
$pos=POS;

$almacen = new Almacen();
$login = new Login();
$cod_usuario=$login->getIdUsuario();
$hoy=date('Y-m-d');
$sql="SELECT codigo_siga, servidor FROM $pyme.parametros_generales";
$array_sucursal=$almacen->ObtenerFilasBySqlSelect($sql);
foreach ($array_sucursal as $key => $value) {
$sucursal=$value['codigo_siga']; 
$servidor=$value['servidor']; 
}

$sql="SELECT numero_version FROM $pyme.version_pyme order by id desc limit 1";
$array_version=$almacen->ObtenerFilasBySqlSelect($sql);

foreach ($array_version as $key => $value) {
$version=$value['numero_version'];
}


if($servidor==0){
//Rutas Windows
$path_inventario="c:/wamp/www/pyme/selectraerp/uploads/inventario";
$path_kardex="c:/wamp/www/pyme/selectraerp/uploads/kardex";
$path_libros="c:/wamp/www/pyme/selectraerp/uploads/libro_venta";
$path_ingresos="c:/wamp/www/pyme/selectraerp/uploads/control_ingresos";
}elseif($servidor==1){
//Rutas Linux
$path_inventario="/var/www/pyme/selectraerp/uploads/inventario";
$path_kardex="/var/www/pyme/selectraerp/uploads/kardex";
$path_libros="/var/www/pyme/selectraerp/uploads/libro_venta";
$path_ingresos="/var/www/pyme/selectraerp/uploads/control_ingresos";
}else{
$path_inventario="/var/www/pyme/selectraerp/uploads/inventario";
$path_kardex="/var/www/pyme/selectraerp/uploads/kardex";
$path_libros="/var/www/pyme/selectraerp/uploads/libro_venta";
$path_ingresos="/var/www/pyme/selectraerp/uploads/control_ingresos";
}

$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");

$nombre_inventario="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_inventario.csv";
$nombre_kardex="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_kardex.csv";
$nombre_libros="000".$sucursal.'_'.$dia.$mes.$ano.$hora.$min."_v".$version."_libro.csv";
$nombre_ingresos="000".$sucursal.'_'.$dia.$mes.$ano.$hora.$min."_v".$version."_ingresos.csv";

$operacion_cajero=$almacen->ObtenerFilasBySqlSelect("SELECT * FROM `operaciones_apertura` WHERE `operacion`='Cierre de Cajero'");
$operacion_libroventa=$almacen->ObtenerFilasBySqlSelect("SELECT * FROM `operaciones_apertura` WHERE `operacion`='Libro de Venta'");

$verificar_cierre="SELECT * from operaciones order by id desc limit 1";

$verificar_libros_pendientes_pos="select HOST  from $pos.closedcash as a, $pyme.caja_impresora as b
where a.host=b.caja_host and  a.money not in  (select  money from $pyme.libro_ventas) and a.money in (select money from $pos.receipts) and date(dateend)>=(select min(fecha) from $pyme.fechas_minimas) group by host order by host, dateend asc";
$array_verificar_libros_pos=$almacen->ObtenerFilasBySqlSelect($verificar_libros_pendientes_pos);
$libros_pos=$almacen->getFilas($array_verificar_libros_pos);
//echo $verificar_libros_pendientes_pos; exit();
$verificar_libros_pendientes_pyme="select nombre_caja  from $pyme.closedcash_pyme as a, $pyme.caja_impresora as b
where a.nombre_caja=b.caja_host and  a.money not in  (select  money from $pyme.libro_ventas) and date(fecha_fin)>=(select min(fecha) from $pyme.fechas_minimas)  group by nombre_caja order by nombre_caja, fecha_fin asc";
$array_verificar_libros_pos=$almacen->ObtenerFilasBySqlSelect($verificar_libros_pendientes_pyme);
$libros_pyme=$almacen->getFilas($array_verificar_libros_pyme);


$array_verificar_cierre=$almacen->ObtenerFilasBySqlSelect($verificar_cierre);

$libro_venta=$array_verificar_cierre[0]['libro_venta'];
$cierres_cajeros=$array_verificar_cierre[0]['cierre_cajero'];
$fecha_ult_operacion=$array_verificar_cierre[0]['fecha'];
$id_libro=$array_verificar_cierre[0]['id'];

$verificar_cajeros_pos="SELECT a.ID, a.NAME FROM $pos.people as a, $pos.log_trans as b  where a.VISIBLE=1 and a.id=b.user and date(DATE)=(select fecha from operaciones order by fecha desc limit 1) and b.DESCRIPTION like '%VENTA RECIBO%' group by b.user order by NAME";

$array_verificar_cajeros_pos=$almacen->ObtenerFilasBySqlSelect($verificar_cajeros_pos);
$filas_verificar_cajeros_pos=$almacen->getFilas($array_verificar_cajeros_pos);

$verificar_cajeros_pyme="select cod_usuario, usuario from $pyme.usuarios as a, $pyme.factura as b where a.cod_usuario=b.cod_vendedor  and a.vendedor=1 and a.visible_vendedor=1 and date(b.fecha_creacion)=(select fecha from $pyme.operaciones order by fecha desc limit 1) group by cod_usuario";
$array_verificar_cajeros_pyme=$almacen->ObtenerFilasBySqlSelect($verificar_cajeros_pyme);
$filas_verificar_cajeros_pyme=$almacen->getFilas($array_verificar_cajeros_pyme);

if(($filas_verificar_cajeros_pyme>0 || $filas_verificar_cajeros_pos>0) && $operacion_cajero[0]['status']=='1'){


        $array_cajeros_cerrar_pos=$almacen->ObtenerFilasBySqlSelect($verificar_cajeros_pos);
        $array_cajeros_cerrar_pyme=$almacen->ObtenerFilasBySqlSelect($verificar_cajeros_pyme);

        foreach ($array_cajeros_cerrar_pos as $key => $value) {
                $array_cajeros_pos.=$value['NAME'].' , '; 
            }

        foreach ($array_cajeros_cerrar_pyme as $key => $value) {
                $array_cajeros_pyme.=$value['usuario'].' , '; 
            }

        $array_cajeros_pos = substr ($array_cajeros_pos, 0, strlen($array_cajeros_pos) - 2);
        $array_cajeros_pyme = substr ($array_cajeros_pyme, 0, strlen($array_cajeros_pyme) - 2);
        $almacen->BeginTrans();
        $sql="UPDATE $pyme.modulos SET visible=0 WHERE cod_modulo in (1,3,5,7,106,225)"; 
        $almacen->ExecuteTrans($sql);
        $almacen->CommitTrans(1);
        echo '<script language="javascript" type="text/JavaScript">';
        echo 'alert("Debe realizar los Cierres de Cajeros Pendientes, Solo se encuentra habilitado el Modulo Cajas y Bancos. \\n Cajeros Pendientes por Cerrar: \\n POS: '.$array_cajeros_pos.' \\n PYME: '.$array_cajeros_pyme.'");';
        echo 'window.location.href="index.php"'; 
        echo '</script>';
        exit();
}
//echo $libros_pos."-".$libros_pyme; exit();
if(($libros_pos>0 || $libros_pyme>0) && $operacion_libroventa[0]['status']==1){

                $verificar_ventas="SELECT * from $pos.ticketlines where DATENEW BETWEEN '".$fecha_ult_operacion." 00:00:00' and '".$fecha_ult_operacion." 23:00:00' limit 1";
                $array_verificar_ventas=$almacen->ObtenerFilasBySqlSelect($verificar_ventas);
                $filas_verificar_ventas=$almacen->getFilas($array_verificar_ventas);
                if($libros_pos>0 || $libros_pyme>0){
                echo '<script language="javascript" type="text/JavaScript">';
                echo 'alert("Debe realizar los Libros de Venta Pendientes, Solo se encuentra habilitado el Modulo Cajas y Bancos");';
                echo 'window.location.href="index.php"'; 
                echo '</script>';
                $almacen->BeginTrans();
                $sql="UPDATE modulos SET visible=0 WHERE cod_modulo in (1,3,5,7,106,225)";
                $almacen->ExecuteTrans($sql);
                $almacen->CommitTrans(1);
                }elseif($filas_verificar_ventas==0){
                $almacen->BeginTrans();
                $sql="UPDATE operaciones SET libro_venta=0 WHERE id=$id_libro";
                $almacen->ExecuteTrans($sql);
                $almacen->CommitTrans(1);
                echo '<script language="javascript" type="text/JavaScript">';
                echo 'alert("Ultima Apertura Sin ventas, Inicie nuevamente para realizar la Apertura del dia de Hoy");';
                echo 'window.location.href="../../../entrada/index.php"'; 
                echo '</script>';
                exit();
                }

//header("Location: index.php");
}else{
$almacen->BeginTrans();

//Habilitando los modulos 
$sql="UPDATE modulos SET visible=1 WHERE cod_modulo in (1,3,5,7,54,89,106,225,239)";
$almacen->ExecuteTrans($sql);

//Habilitando Cajeros POS
$sql="UPDATE $pos.people SET VISIBLE=1 WHERE ID IN (SELECT id_people FROM $pos.people_caja)";
$almacen->ExecuteTrans($sql);

//Habilitando Cajeros PYME
$sql="UPDATE usuarios SET visible_vendedor=1 WHERE visible_vendedor=0";
$almacen->ExecuteTrans($sql);

//Insertando registro de la apertura
$sql="INSERT INTO `apertura_tienda`(`apertura`, `apertura_date`, `id_user_apertura`) VALUES (CURRENT_TIMESTAMP,'$hoy', '$cod_usuario')";
$almacen->ExecuteTrans($sql);

$sql="INSERT INTO `operaciones`(`fecha` , `libro_venta` , `cierre_cajero`) VALUES ('$hoy', -1, 0)";
$almacen->ExecuteTrans($sql);

//Select del archivo de Inventario

$sql_pyme="SELECT REPLACE(v.descripcion1,',','.') as descripcion1, v.cantidad, v.descripcion,v.ubicacion, i.codigo_barras
FROM $pyme.vw_existenciabyalmacen v, $pyme.item i
WHERE i.id_item = v.id_item
AND v.cantidad >0
AND i.cod_departamento = '1'
AND v.ubicacion<>'PISO DE VENTA'
ORDER BY descripcion, ubicacion";
$array_inventario=$almacen->ObtenerFilasBySqlSelect($sql_pyme);

$filas_inventario=$almacen->getFilas($array_inventario);
 
$sql_pos="SELECT REPLACE(i.name, ',','.') as descripcion1, v.units as cantidad, 'descripcion', 'PISO DE VENTA', i.code
FROM $pos.stockcurrent v, $pos.products i
WHERE i.id = v.product
AND v.units>0
ORDER BY descripcion1";
$array_inventario_pos=$almacen->ObtenerFilasBySqlSelect($sql_pos);

$filas_inventario_pos=$almacen->getFilas($array_inventario_pos);

$almacen->CommitTrans(1);
//Se crea el archivo de Inventario para subir
$contenido_inventario="";

if($filas_inventario==0){
    //no se genera el archivo
    $contenido_inventario.="";
}else{
    foreach ($array_inventario as $key => $value) {
		$contenido_inventario.=$value['descripcion1'].','.$value['cantidad'].','.$value['descripcion'].','.$value['ubicacion'].','.$value['codigo_barras'].("\r\n");
	}
}// fin del else de inventario pyme

if($filas_inventario_pos==0){
    
    //no se genera el archivo
    $contenido_inventario.="";
}else{

    foreach ($array_inventario_pos as $key => $value) {
        $contenido_inventario.=$value['descripcion1'].','.$value['cantidad'].','.$value['descripcion'].','.$value['PISO DE VENTA'].','.$value['code'].("\r\n");
    }   
    
}// fin del else de inventario pos
    
$pf_inv=fopen($path_inventario."/".$nombre_inventario,"w+");
fwrite($pf_inv, utf8_decode($contenido_inventario));
fclose($pf_inv);   


$sql_ultimo_kardex="SELECT max(id_transaccion) as id from kardex_almacen";
$array_kardex = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_kardex);
$ultimo_kardex = $array_kardex[0]['id'];

$sql_ultimo_kardex_control="SELECT id_kardex from kardex_control"; 
$array_kardex_control = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_kardex_control);
$ultimo_kardex_control = $array_kardex_control[0]['id_kardex'];


$sql_kardex="SELECT ka.autorizado_por, ka.estado, ka.fecha, ka.id_documento, tma.descripcion, REPLACE(ka.observacion, ',','.') as observacion_cabecera, it.codigo_barras, kad.cantidad, kad.c_esperada, REPLACE(kad.observacion, ',','.') as observacion_detalle, alme.descripcion as almacen_entrada, alms.descripcion as almacen_salida, ubie.descripcion as ubicacion_entrada, ubis.descripcion as ubicacion_salida, ka.cod_movimiento as cod_movimiento, kad.vencimiento, kad.lote, prove.rif , REPLACE(prove.descripcion, ',','.') as nombre_proveedor, ka.guia_sunagro
FROM $pyme.kardex_almacen_detalle kad
LEFT JOIN $pyme.kardex_almacen ka ON kad.id_transaccion=ka.id_transaccion
LEFT JOIN $pyme.item it ON kad.id_item=it.id_item
LEFT JOIN $pyme.tipo_movimiento_almacen tma ON ka.tipo_movimiento_almacen=tma.id_tipo_movimiento_almacen
LEFT JOIN $pyme.almacen alme ON kad.id_almacen_entrada=alme.cod_almacen
LEFT JOIN $pyme.almacen alms ON kad.id_almacen_salida=alms.cod_almacen
LEFT JOIN $pyme.ubicacion ubie ON kad.id_ubi_entrada=ubie.id
LEFT JOIN $pyme.ubicacion ubis ON kad.id_ubi_salida=ubis.id
LEFT JOIN $pyme.proveedores prove ON ka.id_proveedor=prove.id_proveedor
WHERE ka.id_transaccion>$ultimo_kardex_control
AND ka.id_transaccion<=$ultimo_kardex";

//echo $sql_kardex; exit();
$array_kardex=$almacen->ObtenerFilasBySqlSelect($sql_kardex);

//Se crea el archivo de Kardex para subir
$filas_kardex=$almacen->getFilas();
$contenido_kardex="";

if($filas_inventario==0){
    
    //no se genera el archivo
    $contenido_kardex="";
}else{
    foreach ($array_kardex as $key => $value) {
        if($value['descripcion']=='Cargo'){
            $value['ubicacion_salida']='No Posee';
        }
		$contenido_kardex.=$value['autorizado_por'].','.$value['estado'].','.$value['fecha'].','.$value['id_documento'].','.$value['descripcion'].','.$value['observacion_cabecera'].','.$value['codigo_barras'].','.$value['cantidad'].','.$value['c_esperada'].','.$value['observacion_detalle'].','.$value['almacen_entrada'].','.$value['almacen_salida'].','.$value['ubicacion_entrada'].','.$value['ubicacion_salida'].','.$value['cod_movimiento'].','.$value['vencimiento'].','.$value['lote'].','.$value['rif'].','.$value['nombre_proveedor'].','.$value['guia_sunagro'].("\r\n");
	}       
        $pf_kar=fopen($path_kardex."/".$nombre_kardex,"w+");
        fwrite($pf_kar, utf8_decode($contenido_kardex));
        fclose($pf_kar);
        $instruccion2="UPDATE kardex_control SET id_kardex=".$ultimo_kardex."";
        $almacen->Execute2($instruccion2);
    
}// fin del else de Kardex 


//Libros de Ventas por enviar
$sql_libros="SELECT * FROM libroventas_xenviar";
$array_libros=$almacen->ObtenerFilasBySqlSelect($sql_libros);

//Se crea el archivo de Libros para subir
$filas_libros=$almacen->getFilas();
$contenido_libros="";

if($filas_libros==0){
    
    //no se genera el archivo
    $contenido_libros="";
}else{
    foreach ($array_libros as $key => $value) {
        $contenido_libros.=$value['serial_impresora'].','.$value['numero_z'].','.$value['ultima_factura'].','.$value['numeros_facturas'].','.$value['ultima_nc'].','.$value['numeros_ncs'].','.$value['fecha'].','.$value['monto_bruto'].','.$value['monto_exento'].','.$value['base_imponible'].','.$value['iva'].','.$value['fecha_emision'].','.$value['id_usuario_creacion'].','.$value['secuencia'].','.$value['numero_z_usuario'].','.$value['monto_bruto_usuario'].','.$value['monto_exento_usuario'].','.$value['base_imponible_usuario'].','.$value['iva_usuario'].','.$value['money'].','.$value['tipo_venta'].','.$sucursal.("\r\n");
    }       
        $pf_lib=fopen($path_libros."/".$nombre_libros,"w+");
        fwrite($pf_lib, utf8_decode($contenido_libros));
        
        fclose($pf_lib);      
    
}// fin del else de Libros 

$sql_truncate='TRUNCATE TABLE libroventas_xenviar';
$truncate_producto=$almacen->Execute2($sql_truncate);

$sql_ingresos="SELECT * FROM ingresos_xenviar";
$array_ingresos=$almacen->ObtenerFilasBySqlSelect($sql_ingresos);

//Se crea el archivo de Libros para subir
$filas_ingresos=$almacen->getFilas();
$contenido_ingresos="";

if($filas_ingresos==0){
    
    //no se genera el archivo
    $contenido_ingresos="";
}else{
    foreach ($array_ingresos as $key => $value) {
        $contenido_ingresos.=$value['nro_deposito'].','.$value['fecha_deposito'].','.$value['monto_deposito'].','.$value['cuenta_banco'].','.$value['usuario_creacion'].','.$value['nro_cataporte'].','.$value['fecha_cataporte'].','.$value['fecha_retiro'].','.$value['usuario_creacion_cataporte'].','.$sucursal.("\r\n");
    }       
        $pf_ing=fopen($path_ingresos."/".$nombre_ingresos,"w+");
        fwrite($pf_ing, utf8_decode($contenido_ingresos));
        
        fclose($pf_ing);      
    
}// fin del else de Libros 

$sql_truncate='TRUNCATE TABLE ingresos_xenviar';
$truncate_producto=$almacen->Execute2($sql_truncate);

header("Location: index.php");
}