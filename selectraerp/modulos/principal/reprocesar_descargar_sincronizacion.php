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

	$path_despacho=$ruta_master."/despacho";

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

	//Kardex Almacen
	$sql="SELECT $sucursal as codigo_tienda, `id_transaccion`, `id_transaccion_calidad`, `tipo_movimiento_almacen`, `autorizado_por`, `observacion`, `fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`, `id_documento`, `id_proveedor`, `empresa_transporte`, `id_conductor`, `placa`, `color`, `marca`, `guia_sunagro`, `orden_despacho`, `almacen_procedencia`, `almacen_destino`, `prescintos`, `cod_movimiento`, `usuario_despacho`, `observacion_despacho`, `id_cliente`, `nro_factura`, `usuario_anulacion`, `id_seguridad`, `id_aprobado`, `id_receptor`, `id_despachador`, `id_tipo_despacho`, `id_jornada`, `referencia_salida`, `nro_contenedor`, `facturado`, `ticket_entrada`, `costo_declarado`, `cierre_entrada` FROM `kardex_almacen`";
	$array_kardex=$almacen->ObtenerFilasBySqlSelect($sql);

	$sql="SELECT $sucursal as codigo_tienda, `id_transaccion_detalle`, `id_transaccion`, `id_almacen_entrada`, `id_almacen_salida`, `id_item`, `cantidad`, `peso`, `id_ubi_entrada`, `id_ubi_salida`, `vencimiento`, `elaboracion`, `lote`, `c_esperada`, `observacion`, `precio`, `etiqueta`, `costo_declarado`, `id_marca`, `observacion_limite` FROM `kardex_almacen_detalle`";
	$array_kardex_detalle=$almacen->ObtenerFilasBySqlSelect($sql);

	//Calidad Almacen
	$sql="SELECT $sucursal as codigo_tienda, `id_transaccion`, `tipo_movimiento_almacen`, `autorizado_por`, `observacion`, `fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`, `id_documento`, `id_proveedor`, `empresa_transporte`, `id_conductor`, `placa`, `guia_sunagro`, `orden_despacho`, `almacen_procedencia`, `almacen_destino`, `tipo_acta`, `cod_acta_calidad`, `prescintos`, `id_seguridad`, `id_aprobado`, `id_receptor`, `nro_contenedor`, `id_ticket_entrada` FROM `calidad_almacen`";
	$array_calidad=$almacen->ObtenerFilasBySqlSelect($sql);

	$sql="SELECT $sucursal as codigo_tienda, `id_transaccion_detalle`, `id_transaccion`, `id_almacen_entrada`, `id_almacen_salida`, `id_item`, `cantidad`, `id_ubi_entrada`, `id_ubi_salida`, `vencimiento`, `elaboracion`, `lote`, `c_esperada`, `observacion`, `precio`, `estatus`, `tipo_uso`, `costo_declarado`, `id_marca`, `cierre_entrada` FROM `calidad_almacen_detalle`";
	$array_calidad_detalle=$almacen->ObtenerFilasBySqlSelect($sql);

	$json = array('despacho_new' => $array_despacho,'despacho_new_detalle' => $array_despacho_detalle,'factura' => $array_factura, 'factura_detalle' => $array_factura_detalle, 'kardex' =>$array_kardex, 'kardex_detalle' => $array_kardex_detalle, 'calidad' => $array_calidad, 'calidad_detalle' => $array_calidad_detalle);
	$json = json_encode($json);

	$pf_inv=fopen($path_despacho."/".$nombre_despacho,"w+");
	fwrite($pf_inv, $json);
	fclose($pf_inv);

	echo '<script language="javascript" type="text/JavaScript">';
    echo 'alert("Archivos Generados Exitosamente");';
    echo '</script>';
}
?>