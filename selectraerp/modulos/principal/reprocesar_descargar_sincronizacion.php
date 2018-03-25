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
if (isset($_POST["descargar"])) {

	$fecha_movimientos=$_POST["fecha_movimientos"];
	$fecha_ventas_pyme=$_POST["fecha_ventas_pyme"];
	$fecha_ventas_pos=$_POST["fecha_ventas_pos"];
	$fecha_comprobantes=$_POST["fecha_comprobantes"];
	$pyme=DB_SELECTRA_FAC;
	$pos=POS;
	$dia=date("d");
	$mes=date("m");
	$ano=date("y");
	$hora=date("H");
	$min=date("i");
	$seg=date("s");

	$almacen = new Almacen();
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

	$ruta_master=$_SESSION['ROOT_PROYECTO']."/selectraerp/uploads";

	$path_kardex=$ruta_master."/kardex";
	$path_ventas=$ruta_master."/ventas";
	$path_ventas_pyme=$ruta_master."/ventas_pyme";
	$path_descarga=$ruta_master."/descarga_ventas";
	$path_comprobantes=$ruta_master."/comprobantes";
	$path_despacho=$ruta_master."/despacho";

	$nombre_kardex="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_kardex.csv";
	$nomb="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_ventas.csv";
	$nomb_pyme="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_pyme.csv";
	$nombre_comprobante_cabecera="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_comprobante_cabecera.csv";
	$nombre_comprobante_detalle="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_comprobante_detalle.csv";
	$nombre_ingresos_detalle="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_ingresos_detalle.csv";
	$nombre_despacho="000".$sucursal.'_'.$dia.$mes.$ano."_v".$version."_despacho.json";

	//Agregando Facturas y Despachos (Nuevo)

	//Pedidos del Cliente
	$sql="SELECT $sucursal as codigo_tienda, `id_factura`, `cod_factura`, `cod_factura_fiscal`, `nroz`, `impresora_serial`, `id_cliente`, `cod_vendedor`, `fechaFactura`, `subtotal`, `descuentosItemFactura`, `montoItemsFactura`, `ivaTotalFactura`, `TotalTotalFactura`, `cantidad_items`, `totalizar_sub_total`, `totalizar_descuento_parcial`, `totalizar_total_operacion`, `totalizar_pdescuento_global`, `totalizar_descuento_global`, `totalizar_base_imponible`, `totalizar_monto_iva`, `totalizar_total_general`, `totalizar_total_retencion`, `formapago`, `cod_estatus`, `fecha_pago`, `fecha_creacion`, `usuario_creacion`, `cesta_clap`, `money`, `facturacion`, `id_pagos_consolidados` FROM `despacho_new`";
	$array_despacho=$almacen->ObtenerFilasBySqlSelect($sql);

	$sql="SELECT $sucursal as codigo_tienda, `id_detalle_factura`, `id_factura`, `id_item`, `_item_almacen`, `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva`, `_item_descuento`, `_item_montodescuento`, `_item_piva`, `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion`, `fecha_creacion` FROM `despacho_new_detalle`";
	$array_despacho_detalle=$almacen->ObtenerFilasBySqlSelect($sql);

	//Facturas del Cliente
	$sql="SELECT $sucursal as codigo_tienda, `id_factura`, `cod_factura`, `cod_factura_fiscal`, `nroz`, `impresora_serial`, `id_cliente`, `cod_vendedor`, `fechaFactura`, `subtotal`, `descuentosItemFactura`, `montoItemsFactura`, `ivaTotalFactura`, `TotalTotalFactura`, `cantidad_items`, `totalizar_sub_total`, `totalizar_descuento_parcial`, `totalizar_total_operacion`, `totalizar_pdescuento_global`, `totalizar_descuento_global`, `totalizar_base_imponible`, `totalizar_monto_iva`, `totalizar_total_general`, `totalizar_total_retencion`, `formapago`, `cod_estatus`, `fecha_pago`, `fecha_creacion`, `usuario_creacion`, `cesta_clap`, `money`, `facturacion` FROM `factura`";
	$array_factura=$almacen->ObtenerFilasBySqlSelect($sql);

	$sql="SELECT $sucursal as codigo_tienda, `id_detalle_factura`, `id_factura`, `id_item`, `_item_almacen`, `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva`, `_item_descuento`, `_item_montodescuento`, `_item_piva`, `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion`, `fecha_creacion` FROM `factura_detalle`";
	$array_factura_detalle=$almacen->ObtenerFilasBySqlSelect($sql);

	$json = array('despacho_new' => $array_despacho,'despacho_new_detalle' => $array_despacho_detalle,'factura' => $array_factura, 'factura_detalle' => $array_factura_detalle);
	$json = json_encode($json);

	$pf_inv=fopen($path_despacho."/".$nombre_despacho,"w+");
	fwrite($pf_inv, $json);
	fclose($pf_inv);
	
	//Reprocesando Kardex
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
		WHERE ka.fecha_creacion>='".$fecha_movimientos." 00:00:00'
		AND cod_movimiento<>''";
		//echo $sql_kardex; exit();

		$array_kardex=$almacen->ObtenerFilasBySqlSelect($sql_kardex);

		//Creando el archivo de kardex
		$filas_kardex=$almacen->getFilas();
		$contenido_kardex="";
		if($filas_kardex!=0){
		    foreach ($array_kardex as $key => $value) {
		        if($value['descripcion']=='Cargo'){
		            $value['ubicacion_salida']='No Posee';
		        	}
					$contenido_kardex.=$value['autorizado_por'].','.$value['estado'].','.$value['fecha'].','.$value['id_documento'].','.$value['descripcion'].','.$value['observacion_cabecera'].','.$value['codigo_barras'].','.$value['cantidad'].','.$value['c_esperada'].','.$value['observacion_detalle'].','.$value['almacen_entrada'].','.$value['almacen_salida'].','.$value['ubicacion_entrada'].','.$value['ubicacion_salida'].','.$value['cod_movimiento'].','.$value['vencimiento'].','.$value['lote'].','.$value['rif'].','.trim($value['nombre_proveedor']).','.$value['guia_sunagro'].("\r\n");
				}
		        $pf_kar=fopen($path_kardex."/".$nombre_kardex,"w+");
		        fwrite($pf_kar, utf8_decode($contenido_kardex));
		        fclose($pf_kar);
		        //Actualizamos el kardex_control con el ultimo registro de kardex
		        $instruccion="UPDATE $pyme.kardex_control SET id_kardex=(SELECT max(id_transaccion) FROM $pyme.kardex_almacen)";
		        $almacen->Execute2($instruccion);		    
		}
		//Fin Reprocesar Kardex

		//Reprocesando Ventas Pyme
		$sql_pyme="SELECT REPLACE(a.nombre,',',' ') as nombre, a.telefonos, a.email, REPLACE(a.direccion,',',' ') as direccion, a.rif, a.nit, b.id_factura, b.cod_factura_fiscal, b.impresora_serial, b.fechaFactura, b.subtotal, b.descuentosItemFactura, b.montoItemsFactura, b.ivaTotalFactura, b.TotalTotalFactura, b.cantidad_items, b.totalizar_total_general, b.formapago, b.fecha_creacion, REPLACE(f.nombreyapellido,',',' ') as nombreyapellido, b.usuario_creacion, d.codigo_barras, REPLACE(c._item_descripcion,',',' ') as _item_descripcion, c._item_cantidad, c._item_totalsiniva, c._item_totalconiva, e.totalizar_monto_efectivo as monto_efectivo, e.totalizar_monto_cheque as monto_cheque, e.totalizar_nro_cheque as nro_cheque, e.totalizar_nombre_banco as banco_cheque, e.totalizar_monto_tarjeta as monto_tarjeta, e.totalizar_monto_deposito as monto_deposito, e.totalizar_nro_deposito as nro_deposito, e.totalizar_banco_deposito as banco_deposito, e.totalizar_monto_credito2 as monto_credito, c.id_detalle_factura
			FROM $pyme.factura_detalle c
			LEFT JOIN $pyme.factura b ON b.id_factura = c.id_factura 
			LEFT JOIN $pyme.clientes a ON  a.id_cliente = b.id_cliente 
			LEFT JOIN $pyme.item d ON c.id_item = d.id_item 
			LEFT JOIN $pyme.factura_detalle_formapago e ON c.id_factura = e.id_factura
			LEFT JOIN $pyme.usuarios f ON b.cod_vendedor = f.cod_usuario
			WHERE b.fecha_creacion>='".$fecha_ventas_pyme." 00:00:00'";

	    //echo $sql_pyme; exit();
		$array_venta = $almacen->ObtenerFilasBySqlSelect($sql_pyme);

		//Creando el archivo de ventas pyme
		$filas_pyme=$almacen->getFilas();
		$contenido_pyme="";
		if($filas_pyme!=0){
		    foreach ($array_venta as $key => $value) {
			$contenido_pyme.=$value['nombre'].';'.$value['telefonos'].';'.$value['email'].';'.$value['direccion'].';'.$value['rif'].';'.$value['nit'].';'.$value['id_factura'].';'.$value['cod_factura_fiscal'].';'.$value['impresora_serial'].';'.$value['fechaFactura'].';'.$value['subtotal'].';'.$value['descuentosItemFactura'].';'.$value['montoItemsFactura'].';'.$value['ivaTotalFactura'].';'.$value['TotalTotalFactura'].';'.$value['cantidad_items'].';'.$value['totalizar_total_general'].';'.$value['formapago'].';'.$value['fecha_creacion'].';'.$value['nombreyapellido'].';'.$value['usuario_creacion'].';'.$value['codigo_barras'].';'.$value['_item_descripcion'].';'.$value['_item_cantidad'].';'.trim($value['_item_totalsiniva']).';'.$value['_item_totalconiva'].';'.$sucursal.';'.trim($value['monto_efectivo']).';'.trim($value['monto_cheque']).';'.trim($value['nro_cheque']).';'.trim($value['banco_cheque']).';'.trim($value['monto_tarjeta']).';'.trim($value['monto_deposito']).';'.trim($value['nro_deposito']).';'.trim($value['banco_deposito']).';'.trim($value['monto_credito']).';'.$value['id_detalle_factura'].("\r\n");
				}
		        $pf_pyme=fopen($path_ventas_pyme."/".$nomb_pyme,"w+");
		        fwrite($pf_pyme, utf8_decode($contenido_pyme));
		        fclose($pf_pyme);
		        //Actualizamos el ticket_control_pyme con el ultimo registro de kardex
		        $instruccion="UPDATE $pyme.ticket_control_pyme SET ticket_id=(SELECT max(id_factura) FROM $pyme.factura)";
		        $almacen->Execute2($instruccion);		    
		}
		//Fin Reprocesar ventas pyme

		//Reprocesando Ventas POS

		$sql_pos="SELECT a.ID as id_tickets ,  a.TICKETTYPE ,  a.TICKETID ,  a.PERSON ,  a.CUSTOMER ,  a.STATUS,    b.TICKET  ,   b.LINE  ,   b.PRODUCT  ,   b.ATTRIBUTESETINSTANCE_ID  ,   b.UNITS  ,   b.PRICE  ,   b.TAXID as taxid_tikestline  ,      b.DATENEW as datenew_ticketlines,    c.ID as id_products  ,   c.REFERENCE  ,   c.CODE  ,   c.CODETYPE  ,   REPLACE(c.NAME ,',',' ') as nombre_producto  ,   c.PRICEBUY  ,   c.PRICESELL  ,   c.CATEGORY  ,   c.TAXCAT  ,   c.ATTRIBUTESET_ID  ,  c.STOCKCOST  ,   c.STOCKVOLUME  ,    cast(c.ISCOM as unsigned) as ISCOM  ,   cast(c.ISSCALE as unsigned) as ISSCALE,  c.QUANTITY_MAX  ,   c.TIME_FOR_TRY,    d.ID as id_gente  ,   d.SEARCHKEY  ,   d.TAXID  ,   REPLACE(d.NAME ,',',' ') as name_persona  ,   d.TAXCATEGORY  ,   d.CARD  ,   d.MAXDEBT  ,   d.ADDRESS  ,   d.ADDRESS2  ,   d.POSTAL  ,   d.CITY  ,   d.REGION  ,   d.COUNTRY  ,  REPLACE(d.FIRSTNAME ,',',' ') as FIRSTNAME,  REPLACE(d.LASTNAME ,',',' ') as LASTNAME,   d.EMAIL  ,   d.PHONE  ,   d.PHONE2  ,   d.FAX  ,   d.NOTES  ,    cast(d.VISIBLE as unsigned) as visible  ,   d.CURDATE  ,   d.CURDEBT  ,     e.ID as id_receipts  ,   e.MONEY as money_receipts  ,   e.DATENEW  ,   f.MONEY  ,   f.HOST  ,   f.HOSTSEQUENCE  ,   f.DATESTART  ,   f.DATEEND
      		from $pos.tickets as a,
      		$pos.ticketlines as b,
      		$pos.products as c,
      		$pos.customers as d,
      		$pos.receipts as e,
      		$pos.closedcash as f
      		WHERE a.id=b.ticket and b.product=c.id
      		AND a.customer=d.id and a.id=e.id and e.money=f.money
      		AND b.SOLD=1
      		AND b.DATENEW>='".$fecha_ventas_pos." 00:00:00'";

      		$array_venta = $almacen->ObtenerFilasBySqlSelect($sql_pos);

			//Se crea el archivo de Ventas para subir
			$filas_pos=$almacen->getFilas();
			if($filas_pos==0){
			    $contenido_pos="";
			    }else{ 
			        $cont=0;
			        foreach ($array_venta as $value) {
			            foreach( $value as $key1){
			                     if($cont==60){
			                        $contenido_pos.=$key1.",".$sucursal.("\r\n");
			                        $cont=0;
			                        }else{
			                        $contenido_pos.=$key1.",";
			                        $cont++;
			                        }
			                }
			            }
			        $pf=fopen($path_ventas."/".$nomb,"w+");
			        fwrite($pf, utf8_decode($contenido_pos));
			        $instruccion="TRUNCATE $pos.ticket_control; INSERT INTO $pos.ticket_control (ticket_id) SELECT max(TICKETID) FROM $pos.tickets;";
			        //echo $instruccion; exit();
					$almacen->Execute2($instruccion);
			        fclose($pf);
			        chmod($path_ventas."/".$nomb,  0777);
			}

			$almacen2= new Almacen();
			//Comprobantes de Ingreso
			$sql_ultimo_comprobante="SELECT max(id) as id from $pyme.comprobante";
			$array_comprobante = $almacen2->ObtenerFilasBySqlSelect($sql_ultimo_comprobante);
			$ultimo_comprobante = $array_comprobante[0]['id'];
			if ($ultimo_comprobante=='') {
			    $ultimo_comprobante=0;
			}

			$sql_ultimo_comprobante_control="SELECT id from $pyme.comprobante WHERE fecha>='".$fecha_comprobantes."'";
			$array_comprobante_control = $almacen2->ObtenerFilasBySqlSelect($sql_ultimo_comprobante_control);
			$ultimo_comprobante_control = $array_comprobante_control[0]['id'];

			if ($ultimo_comprobante_control!='') {
			
			//Reporcesar Comprobantes de ingresos
			//Cabecera
			$sql_cabecera_comprobante="SELECT id,ingreso, iva1, iva2, iva3, perdida, cxc, otros_ingresos, caja, banco, tipo_mov, id_usuario, fecha, observacion,nombreyapellido FROM $pyme.comprobante, $pyme.usuarios WHERE id>=".$ultimo_comprobante_control." and id_usuario=cod_usuario";
			$array_cabecera_comprobante = $almacen2->ObtenerFilasBySqlSelect($sql_cabecera_comprobante);
			//Se crea el archivo de Cabecera para subir
			$filas=$almacen2->getFilas();
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
			$sql_comprobante_detalle="SELECT a.id, id_comprobante, ingreso, cajero, iva1, iva2, iva3, perdida, cxc, otros_ingresos, caja, id_usuario, fecha, tipo_venta, nombreyapellido, NAME  FROM $pyme.comprobante_detalle a
			LEFT JOIN $pyme.usuarios b ON a.cajero=b.cod_usuario
			LEFT JOIN $pos.people c ON a.cajero=c.ID
			WHERE id_comprobante>=".$ultimo_comprobante_control."";
			$array_comprobante_detalle = $almacen2->ObtenerFilasBySqlSelect($sql_comprobante_detalle);
			//Se crea el archivo de Detalle para subir
			$filas=$almacen2->getFilas();
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
			$sql_ingresos_detalle="SELECT id, id_comprobante_detalle, tipo_ingreso, monto, id_comprobante FROM $pyme.ingresos_detalles WHERE id_comprobante>=".$ultimo_comprobante_control."";
			$array_ingresos_detalle = $almacen2->ObtenerFilasBySqlSelect($sql_ingresos_detalle);
			//Se crea el archivo de Detalle para subir
			$filas=$almacen2->getFilas();
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
			}
			$instruccion2="UPDATE $pyme.comprobante_control SET id_comprobante=".$ultimo_comprobante."";
			$almacen2->Execute2($instruccion2);
}
?>