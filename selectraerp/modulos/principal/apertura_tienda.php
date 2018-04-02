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

$ruta_server=$_SERVER['DOCUMENT_ROOT'];

$findme='wamp';
$loc_aper = strpos($ruta_server, $findme);
$ipserver=DB_HOST;

if ($loc_aper!=0 && $servidor==1) {
echo '<script language="javascript" type="text/JavaScript">';
echo 'alert("Debe realizar la Apertura desde la IP del Servidor, click en aceptar para redireccionar y proceda a realizar nuevamente la apertura");';
echo 'window.location.href="http://'.$ipserver.'/pyme/entrada/index.php"'; 
echo '</script>';
exit();
}

$ruta_master=$_SESSION['ROOT_PROYECTO']."/selectraerp/uploads";

$path_inventario=$ruta_master."/inventario";
$path_kardex=$ruta_master."/kardex";
$path_ventas=$ruta_master."/ventas";
$path_ventas_pyme=$ruta_master."/ventas_pyme";
$path_descarga=$ruta_master."/descarga_ventas";
$path_libros=$ruta_master."/libro_venta";
$path_ingresos=$ruta_master."/control_ingresos";
$path_cierre_pos=$ruta_master."/cierre_pos";
$path_comprobantes=$ruta_master."/comprobantes";
$path_despacho=$ruta_master."/despacho";

$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");

$sql="SELECT contador  FROM $pyme.correlativos WHERE campo='data_generada'";
$array_contador=$almacen->ObtenerFilasBySqlSelect($sql);
foreach ($array_contador as $key => $value) {
$contador=$value['contador'];
}

$formato_contador=str_pad($contador, 4, "0", STR_PAD_LEFT);

$nombre_inventario="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_inventario.csv";
$nombre_kardex="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_kardex.csv";
$nombre_libros="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_libro.csv";
$nombre_ingresos="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_ingresos.csv";
$nomb="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_ventas.csv";
$nomb_pyme="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_pyme.csv";
$nombre_cierre_pos="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_cierre_pos.csv";
$nombre_comprobante_cabecera="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_comprobante_cabecera.csv";
$nombre_comprobante_detalle="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_comprobante_detalle.csv";
$nombre_ingresos_detalle="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_ingresos_detalle.csv";
$nombre_despacho="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_despacho.json";

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
        $sql="UPDATE $pyme.modulos SET visible=0 WHERE cod_modulo in (1,3,5,7,225)"; 
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
                $sql="UPDATE modulos SET visible=0 WHERE cod_modulo in (1,3,5,7,225)";
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
}else{
$almacen->BeginTrans();

//Habilitando los modulos 
$sql="UPDATE modulos SET visible=1 WHERE cod_modulo in (1,3,5,7,54,89,225)";
$almacen->ExecuteTrans($sql);

//Habilitando Cajeros POS
$sql="UPDATE $pos.people SET VISIBLE=1 WHERE ID IN (SELECT id_people FROM $pos.people_caja)";
$almacen->ExecuteTrans($sql);

//Habilitando Cajeros PYME
$sql="UPDATE usuarios SET visible_vendedor=1 WHERE visible_vendedor=0";
$almacen->ExecuteTrans($sql);

//obteniedno el ultimo registro
$sql="select apertura_date from apertura_tienda order by id_apertura desc limit 1";
$fecha_ultima_sincronizacion=$almacen->ObtenerFilasBySqlSelect($sql);
//Insertando registro de la apertura
$sql="INSERT INTO `apertura_tienda`(`apertura`, `apertura_date`, `id_user_apertura`) VALUES (CURRENT_TIMESTAMP,'$hoy', '$cod_usuario')";
$almacen->ExecuteTrans($sql);

$sql="INSERT INTO `operaciones`(`fecha` , `libro_venta` , `cierre_cajero`) VALUES ('$hoy', -1, 0)";
$almacen->ExecuteTrans($sql);

//Actualizando la fecha de apertura para las cajas
$sql="UPDATE $pos.store SET OPENING_DATE=CURRENT_TIMESTAMP";

$almacen->ExecuteTrans($sql);

//Select del archivo de Inventario

$sql_pyme="SELECT REPLACE(v.descripcion1,',','.') as descripcion1, v.cantidad, v.descripcion,v.ubicacion, i.codigo_barras
FROM $pyme.vw_existenciabyalmacen v, $pyme.item i
WHERE i.id_item = v.id_item
AND v.cantidad >0
AND i.cod_departamento in (1,2)
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
//si tiene varios dias sin aperturar se debe igualmente crear el archivo para esos dias
$datetime1 = new DateTime($fecha_ultima_sincronizacion[0]['apertura_date']);
$datetime2 = new DateTime($ano."-".$mes."-".$dia);
$interval = $datetime1->diff($datetime2);
$cantidad=$interval->d;
$i=0;
while($cantidad!=$i)
{
    //guardo formato de fecha
    $fecha22=date_format($datetime1, 'dmy');
    //registro el nombre del archivo a crear
    $nombre_inventario_retrasados="000".$sucursal.'_'.$fecha22."_v".$version."_inventario.csv";
    $pf_inv_retrasados=fopen($path_inventario."/".$nombre_inventario_retrasados,"w+");
    fwrite($pf_inv_retrasados, utf8_decode($contenido_inventario));
    fclose($pf_inv_retrasados);
    //sumo un dia a la fecha de referencia, esto se repetirá la cantidad de veces necesaria
    date_add($datetime1, date_interval_create_from_date_string('1 days'));
    $i++;
}
//fin de agregar archivos inventario

//agregando cierre pos
$sql="select * from cierre_pos_xenviar";
$array_cierre_pos=$almacen->ObtenerFilasBySqlSelect($sql);
if($array_cierre_pos!=null)
{
    
    foreach ($array_cierre_pos as $key => $value) 
    {
        $contenido_cierre.=$value['id'].','.$value['id_original'].','.$value['siga'].','.$value['fecha'].','.$value['banco'].','.$value['cuenta'].','.$value['afiliacion_credito'].','.$value['terminal_credito'].','.$value['lote_credito'].','.$value['visa'].','.$value['master'].','.$value['total_credito'].','.$value['afiliacion_debito'].','.$value['terminal_debito'].','.$value['lote_debito'].','.$value['debito'].','.$value['alimentacion'].','.$value['total_debito'].','.$value['usuario'].','.$value['fecha_creacion'].'("\r\n")';
    }
    $pf_cierrepos=fopen($path_cierre_pos."/".$nombre_cierre_pos,"w+");
    fwrite($pf_cierrepos, utf8_decode($contenido_cierre));
    fclose($pf_cierrepos);
    $almacen->Execute2("truncate cierre_pos_xenviar");
}
//fin de agregar cierre pos


$sql_ultimo_kardex="SELECT max(id_transaccion) as id from kardex_almacen";
$array_kardex = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_kardex);
$ultimo_kardex = $array_kardex[0]['id'];

$sql_ultimo_kardex_control="SELECT id_kardex from kardex_control"; 
$array_kardex_control = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_kardex_control);
$ultimo_kardex_control = $array_kardex_control[0]['id_kardex'];


$sql_kardex="SELECT ka.autorizado_por, ka.estado, ka.fecha, REPLACE(ka.id_documento, ',','.') as id_documento, tma.descripcion, REPLACE(ka.observacion, ',','.') as observacion_cabecera, it.codigo_barras, kad.cantidad, kad.c_esperada, REPLACE(kad.observacion, ',','.') as observacion_detalle, alme.descripcion as almacen_entrada, alms.descripcion as almacen_salida, ubie.descripcion as ubicacion_entrada, ubis.descripcion as ubicacion_salida, ka.cod_movimiento as cod_movimiento, kad.vencimiento, kad.lote, prove.rif , REPLACE(REPLACE(prove.descripcion, ',','.'), char(9), '') as nombre_proveedor, ka.guia_sunagro
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
AND ka.id_transaccion<=$ultimo_kardex
AND cod_movimiento<>''";

//echo $sql_kardex; exit();
$array_kardex=$almacen->ObtenerFilasBySqlSelect($sql_kardex);

//Se crea el archivo de Kardex para subir
$filas_kardex=$almacen->getFilas();
$contenido_kardex="";

if($filas_kardex==0){
    
    //no se genera el archivo
    $contenido_kardex="";
}else{
    foreach ($array_kardex as $key => $value) {
        if($value['descripcion']=='Cargo'){
            $value['ubicacion_salida']='No Posee';
        }
		$contenido_kardex.=$value['autorizado_por'].','.$value['estado'].','.$value['fecha'].','.$value['id_documento'].','.$value['descripcion'].','.$value['observacion_cabecera'].','.$value['codigo_barras'].','.$value['cantidad'].','.$value['c_esperada'].','.$value['observacion_detalle'].','.$value['almacen_entrada'].','.$value['almacen_salida'].','.$value['ubicacion_entrada'].','.$value['ubicacion_salida'].','.$value['cod_movimiento'].','.$value['vencimiento'].','.$value['lote'].','.$value['rif'].','.trim($value['nombre_proveedor']).','.$value['guia_sunagro'].("\r\n");
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

//Se crea el archivo de Ingresos para subir
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

//Ventas del POS
$sql_ultimo_ticket="SELECT id from $pos.ticketsnum";
$array_ticket = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_ticket);
$ultimo_ticket = $array_ticket[0]['id'];

$sql_ultimo_ticket="SELECT ticket_id from $pos.ticket_control";
$array_ticket_control = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_ticket);
$ultimo_ticket_control = $array_ticket_control[0]['ticket_id'];

//Select del archivo de Ventas
$sql="SELECT a.ID as id_tickets ,  a.TICKETTYPE ,  a.TICKETID ,  a.PERSON ,  a.CUSTOMER ,  a.STATUS,    b.TICKET  ,   b.LINE  ,   b.PRODUCT  ,   b.ATTRIBUTESETINSTANCE_ID  ,   b.UNITS  ,   b.PRICE  ,   b.TAXID as taxid_tikestline  ,      b.DATENEW as datenew_ticketlines,    c.ID as id_products  ,   c.REFERENCE  ,   c.CODE  ,   c.CODETYPE  ,   REPLACE(c.NAME ,',',' ') as nombre_producto  ,   c.PRICEBUY  ,   c.PRICESELL  ,   c.CATEGORY  ,   c.TAXCAT  ,   c.ATTRIBUTESET_ID  ,  c.STOCKCOST  ,   c.STOCKVOLUME  ,    cast(c.ISCOM as unsigned) as ISCOM  ,   cast(c.ISSCALE as unsigned) as ISSCALE,  c.QUANTITY_MAX  ,   c.TIME_FOR_TRY,    d.ID as id_gente  ,   d.SEARCHKEY  ,   d.TAXID  ,   REPLACE(d.NAME ,',',' ') as name_persona  ,   d.TAXCATEGORY  ,   d.CARD  ,   d.MAXDEBT  ,   d.ADDRESS  ,   d.ADDRESS2  ,   d.POSTAL  ,   d.CITY  ,   d.REGION  ,   d.COUNTRY  ,  REPLACE(d.FIRSTNAME ,',',' ') as FIRSTNAME,  REPLACE(d.LASTNAME ,',',' ') as LASTNAME,   d.EMAIL  ,   d.PHONE  ,   d.PHONE2  ,   d.FAX  ,   d.NOTES  ,    cast(d.VISIBLE as unsigned) as visible  ,   d.CURDATE  ,   d.CURDEBT  ,     e.ID as id_receipts  ,   e.MONEY as money_receipts  ,   e.DATENEW  ,   f.MONEY  ,   f.HOST  ,   f.HOSTSEQUENCE  ,   f.DATESTART  ,   f.DATEEND
      from $pos.tickets as a,
      $pos.ticketlines as b,
      $pos.products as c,
      $pos.customers as d,
      $pos.receipts as e,
      $pos.closedcash as f
      where a.id=b.ticket and b.product=c.id
      and a.customer=d.id and a.id=e.id and e.money=f.money
      AND b.SOLD=1
      AND a.ticketid>$ultimo_ticket_control
      AND a.ticketid<=$ultimo_ticket";

$array_venta = $almacen->ObtenerFilasBySqlSelect($sql);

//Se crea el archivo de Ventas para subir
$filas=$almacen->getFilas();
if($filas==0){
    $contenido="";
    }else{ 
        $cont=0;
        foreach ($array_venta as $value) {
            foreach( $value as $key1){                     
                     if($cont==60){
                        $contenido.=$key1.",".$sucursal.("\r\n");
                        $cont=0;
                        }else{                      
                        $contenido.=$key1.",";
                        $cont++; 
                        }    
                }
            }       
        $pf=fopen($path_ventas."/".$nomb,"w+");
        fwrite($pf, utf8_decode($contenido));
        
        fclose($pf);
        chmod($path_ventas."/".$nomb,  0777);
}
$instruccion2="UPDATE ".$pos.".ticket_control SET ticket_id='".$ultimo_ticket."'";
$almacen->Execute2($instruccion2);

//VENTAS PYME
$sql_ultimo_ticket_pyme="SELECT max(id_factura) as id from $pyme.factura";
$array_ticket_pyme = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_ticket_pyme);
$ultimo_ticket_pyme = $array_ticket_pyme[0]['id'];
if ($ultimo_ticket_pyme==''){
$ultimo_ticket_pyme=0;
}

$sql_ultimo_ticket_pyme="SELECT ticket_id from $pyme.ticket_control_pyme"; 
$array_ticket_control = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_ticket_pyme);
$ultimo_ticket_control_pyme = $array_ticket_control[0]['ticket_id'];
//Select del archivo de Ventas
$sql="SELECT REPLACE(a.nombre,',',' ') as nombre, a.telefonos, a.email, REPLACE(a.direccion,',',' ') as direccion, a.rif, a.nit, b.id_factura, b.cod_factura_fiscal, b.impresora_serial, b.fechaFactura, b.subtotal, b.descuentosItemFactura, b.montoItemsFactura, b.ivaTotalFactura, b.TotalTotalFactura, b.cantidad_items, b.totalizar_total_general, b.formapago, b.fecha_creacion, REPLACE(f.nombreyapellido,',',' ') as nombreyapellido, b.usuario_creacion, d.codigo_barras, REPLACE(c._item_descripcion,',',' ') as _item_descripcion, c._item_cantidad, c._item_totalsiniva, c._item_totalconiva, e.totalizar_monto_efectivo as monto_efectivo, e.totalizar_monto_cheque as monto_cheque, e.totalizar_nro_cheque as nro_cheque, e.totalizar_nombre_banco as banco_cheque, e.totalizar_monto_tarjeta as monto_tarjeta, e.totalizar_monto_deposito as monto_deposito, e.totalizar_nro_deposito as nro_deposito, e.totalizar_banco_deposito as banco_deposito, e.totalizar_monto_credito2 as monto_credito, c.id_detalle_factura
    FROM $pyme.factura_detalle c
    LEFT JOIN $pyme.factura b ON b.id_factura = c.id_factura 
    LEFT JOIN $pyme.clientes a ON  a.id_cliente = b.id_cliente 
    LEFT JOIN $pyme.item d ON c.id_item = d.id_item 
    LEFT JOIN $pyme.factura_detalle_formapago e ON c.id_factura = e.id_factura
    LEFT JOIN $pyme.usuarios f ON b.cod_vendedor = f.cod_usuario
    WHERE b.id_factura>$ultimo_ticket_control_pyme
    AND b.id_factura<=$ultimo_ticket_pyme"; 
$array_venta = $almacen->ObtenerFilasBySqlSelect($sql);
//Se crea el archivo de Ventas Pyme para subir
$filas=$almacen->getFilas();
if($filas==0){
  $contenido_pyme="";
  }else{ 
    $cont=0;
foreach ($array_venta as $key => $value) {
            $contenido_pyme.=$value['nombre'].';'.$value['telefonos'].';'.$value['email'].';'.$value['direccion'].';'.$value['rif'].';'.$value['nit'].';'.$value['id_factura'].';'.$value['cod_factura_fiscal'].';'.$value['impresora_serial'].';'.$value['fechaFactura'].';'.$value['subtotal'].';'.$value['descuentosItemFactura'].';'.$value['montoItemsFactura'].';'.$value['ivaTotalFactura'].';'.$value['TotalTotalFactura'].';'.$value['cantidad_items'].';'.$value['totalizar_total_general'].';'.$value['formapago'].';'.$value['fecha_creacion'].';'.$value['nombreyapellido'].';'.$value['usuario_creacion'].';'.$value['codigo_barras'].';'.$value['_item_descripcion'].';'.$value['_item_cantidad'].';'.trim($value['_item_totalsiniva']).';'.$value['_item_totalconiva'].';'.$sucursal.';'.trim($value['monto_efectivo']).';'.trim($value['monto_cheque']).';'.trim($value['nro_cheque']).';'.trim($value['banco_cheque']).';'.trim($value['monto_tarjeta']).';'.trim($value['monto_deposito']).';'.trim($value['nro_deposito']).';'.trim($value['banco_deposito']).';'.trim($value['monto_credito']).';'.$value['id_detalle_factura'].("\r\n");
                } 


        $pf=fopen($path_ventas_pyme."/".$nomb_pyme,"w+");
        fwrite($pf, utf8_decode($contenido_pyme));
        
        fclose($pf);
        chmod($path_ventas_pyme."/".$nomb_pyme,  0777);
}
$instruccion2="UPDATE ticket_control_pyme SET ticket_id=".$ultimo_ticket_pyme."";
$almacen->Execute2($instruccion2);

//Comprobantes de Ingreso
$sql_ultimo_comprobante="SELECT max(id) as id from comprobante";
$array_comprobante = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_comprobante);
$ultimo_comprobante = $array_comprobante[0]['id'];
if ($ultimo_comprobante=='') {
    $ultimo_comprobante=0;
}

$sql_ultimo_comprobante_control="SELECT id_comprobante from comprobante_control"; 
$array_comprobante_control = $almacen->ObtenerFilasBySqlSelect($sql_ultimo_comprobante_control);
$ultimo_comprobante_control = $array_comprobante_control[0]['id_comprobante'];
if ($ultimo_comprobante_control=='') {
    $ultimo_comprobante_control=0;
}

//Cabecera
$sql_cabecera_comprobante="SELECT id,ingreso, iva1, iva2, iva3, perdida, cxc, otros_ingresos, caja, banco, tipo_mov, id_usuario, fecha, observacion,nombreyapellido FROM comprobante, usuarios WHERE id>".$ultimo_comprobante_control." and id_usuario=cod_usuario";
$array_cabecera_comprobante = $almacen->ObtenerFilasBySqlSelect($sql_cabecera_comprobante);
//Se crea el archivo de Cabecera para subir
$filas=$almacen->getFilas();
if($filas==0){
  $contenido_comprobante="";
  }else{
    $cont=0;
foreach ($array_cabecera_comprobante as $key => $value) {
            $contenido_comprobante.=$value['id'].';'.$value['ingreso'].';'.$value['iva1'].';'.$value['iva2'].';'.$value['iva3'].';'.$value['perdida'].';'.$value['cxc'].';'.$value['otros_ingresos'].';'.$value['caja'].';'.$value['banco'].';'.$value['tip_mov'].';'.$value['id_usuario'].';'.$value['fecha'].';'.$value['observacion'].';'.$value['nombreyapellido'].';'.$sucursal.("\r\n");
                } 


        $pf=fopen($path_comprobantes."/".$nombre_comprobante_cabecera,"w+");
        fwrite($pf, utf8_decode($contenido_comprobante));
        
        fclose($pf);
        chmod($path_comprobantes."/".$nombre_comprobante_cabecera,  0777);
}

//Comprobante Detalle
$sql_comprobante_detalle="SELECT a.id, id_comprobante, ingreso, cajero, iva1, iva2, iva3, perdida, cxc, otros_ingresos, caja, id_usuario, fecha, tipo_venta, nombreyapellido, NAME  FROM comprobante_detalle a
LEFT JOIN usuarios b ON a.cajero=b.cod_usuario
LEFT JOIN $pos.people c ON a.cajero=c.ID
WHERE id_comprobante>".$ultimo_comprobante_control."";
$array_comprobante_detalle = $almacen->ObtenerFilasBySqlSelect($sql_comprobante_detalle);
//Se crea el archivo de Detalle para subir
$filas=$almacen->getFilas();
if($filas==0){
  $contenido_comprobante_detalle="";
  }else{
    $cont=0;
foreach ($array_comprobante_detalle as $key => $value) {
            $contenido_comprobante_detalle.=$value['id'].';'.$value['id_comprobante'].';'.$value['ingreso'].';'.$value['cajero'].';'.$value['iva1'].';'.$value['iva2'].';'.$value['iva3'].';'.$value['perdida'].';'.$value['cxc'].';'.$value['otros_ingresos'].';'.$value['caja'].';'.$value['id_usuario'].';'.$value['fecha'].';'.$value['tipo_venta'].';'.$value['nombreyapellido'].';'.$value['NAME'].';'.$sucursal.("\r\n");
                } 


        $pf=fopen($path_comprobantes."/".$nombre_comprobante_detalle,"w+");
        fwrite($pf, utf8_decode($contenido_comprobante_detalle));
        
        fclose($pf);
        chmod($path_comprobantes."/".$nombre_comprobante_detalle,  0777);
}

//Ingresos Detalles
$sql_ingresos_detalle="SELECT id, id_comprobante_detalle, tipo_ingreso, monto, id_comprobante FROM ingresos_detalles WHERE id_comprobante>".$ultimo_comprobante_control."";
$array_ingresos_detalle = $almacen->ObtenerFilasBySqlSelect($sql_ingresos_detalle);
//Se crea el archivo de Detalle para subir
$filas=$almacen->getFilas();
if($filas==0){
  $contenido_ingresos_detalle="";
  }else{
    $cont=0;
foreach ($array_ingresos_detalle as $key => $value) {
            $contenido_ingresos_detalle.=$value['id'].';'.$value['id_comprobante_detalle'].';'.$value['tipo_ingreso'].';'.$value['monto'].';'.$value['id_comprobante'].';'.$sucursal.("\r\n");
                } 


        $pf=fopen($path_comprobantes."/".$nombre_ingresos_detalle,"w+");
        fwrite($pf, utf8_decode($contenido_ingresos_detalle));
        
        fclose($pf);
        chmod($path_comprobantes."/".$nombre_ingresos_detalle,  0777);
}

$instruccion2="UPDATE comprobante_control SET id_comprobante=".$ultimo_comprobante."";
$almacen->Execute2($instruccion2);


//Nueva Sincronizacion JSON

//ids de control
$sql="SELECT id_control FROM $pyme.control_json WHERE tabla_control='despacho_new'";
$array_control=$almacen->ObtenerFilasBySqlSelect($sql);
//Pedidos del Cliente
$sql="SELECT $sucursal as codigo_tienda, `id_factura`, `cod_factura`, `cod_factura_fiscal`, `nroz`, `impresora_serial`, `id_cliente`, `cod_vendedor`, `fechaFactura`, `subtotal`, `descuentosItemFactura`, `montoItemsFactura`, `ivaTotalFactura`, `TotalTotalFactura`, `cantidad_items`, `totalizar_sub_total`, `totalizar_descuento_parcial`, `totalizar_total_operacion`, `totalizar_pdescuento_global`, `totalizar_descuento_global`, `totalizar_base_imponible`, `totalizar_monto_iva`, `totalizar_total_general`, `totalizar_total_retencion`, `formapago`, `cod_estatus`, `fecha_pago`, `fecha_creacion`, `usuario_creacion`, `cesta_clap`, `money`, `facturacion`, `id_pagos_consolidados` FROM `despacho_new` WHERE id_factura>".$array_control[0]['id_control']."";

$array_despacho=$almacen->ObtenerFilasBySqlSelect($sql);

$sql="SELECT $sucursal as codigo_tienda, `id_detalle_factura`, `id_factura`, `id_item`, `_item_almacen`, `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva`, `_item_descuento`, `_item_montodescuento`, `_item_piva`, `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion`, `fecha_creacion` FROM `despacho_new_detalle` WHERE id_factura>".$array_control[0]['id_control']."";
$array_despacho_detalle=$almacen->ObtenerFilasBySqlSelect($sql);

$sql="SELECT id_control FROM $pyme.control_json WHERE tabla_control='factura'";
$array_control=$almacen->ObtenerFilasBySqlSelect($sql);
//Facturas del Cliente
$sql="SELECT $sucursal as codigo_tienda, `id_factura`, `cod_factura`, `cod_factura_fiscal`, `nroz`, `impresora_serial`, `id_cliente`, `cod_vendedor`, `fechaFactura`, `subtotal`, `descuentosItemFactura`, `montoItemsFactura`, `ivaTotalFactura`, `TotalTotalFactura`, `cantidad_items`, `totalizar_sub_total`, `totalizar_descuento_parcial`, `totalizar_total_operacion`, `totalizar_pdescuento_global`, `totalizar_descuento_global`, `totalizar_base_imponible`, `totalizar_monto_iva`, `totalizar_total_general`, `totalizar_total_retencion`, `formapago`, `cod_estatus`, `fecha_pago`, `fecha_creacion`, `usuario_creacion`, `cesta_clap`, `money`, `facturacion` FROM `factura` WHERE id_factura>".$array_control[0]['id_control']."";
$array_factura=$almacen->ObtenerFilasBySqlSelect($sql);

$sql="SELECT $sucursal as codigo_tienda, `id_detalle_factura`, `id_factura`, `id_item`, `_item_almacen`, `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva`, `_item_descuento`, `_item_montodescuento`, `_item_piva`, `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion`, `fecha_creacion` FROM `factura_detalle` WHERE id_factura>".$array_control[0]['id_control']."";
$array_factura_detalle=$almacen->ObtenerFilasBySqlSelect($sql);

$json = array('despacho_new' => $array_despacho,'despacho_new_detalle' => $array_despacho_detalle,'factura' => $array_factura, 'factura_detalle' => $array_factura_detalle);
$json = json_encode($json);

$pf_inv=fopen($path_despacho."/".$nombre_despacho,"w+");
fwrite($pf_inv, $json);
fclose($pf_inv);

//Actualizando los ids control de json
$sql="SELECT MAX(a.id_factura) as max_despacho, MAX(b.id_factura) as max_factura FROM despacho_new a, factura b";
$update_control=$almacen->ObtenerFilasBySqlSelect($sql);

$instruccion_update="
UPDATE $pyme.control_json SET id_control=".$update_control[0]['max_despacho']." WHERE tabla_control = 'despacho_new';
UPDATE $pyme.control_json SET id_control=".$update_control[0]['max_factura']." WHERE tabla_control = 'factura';
";
$almacen->Execute2($instruccion_update);

if ($loc_aper!=0 && $servidor==0) {
echo '<script language="javascript" type="text/JavaScript">';
echo 'alert("Esta realizando la Apertura desde esta PC, descargar el archivo Sincronizacion Data desde este mismo Equipo");';
echo 'window.location.href="index.php"'; 
echo '</script>';
exit();
}else{
    header("Location: index.php");
}
}