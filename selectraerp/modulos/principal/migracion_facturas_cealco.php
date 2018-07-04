<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']=$_SERVER['DOCUMENT_ROOT']."/cealco";
ini_set("display_errors", 1);

require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
require_once("../../libs/php/clases/clase_db.inc.php");
require_once("../../../general.config.inc.php");

error_reporting(-1);
$bd=DB_SELECTRA_FAC;
$conn_pyme = new ConexionComun();
$sql_facturas="SELECT f.*, p.rif, sum(f.TotalTotalFactura) as TotalGroup, pg.codigo_siga as codigo_tienda FROM $bd.factura f
INNER JOIN $bd.clientes p ON f.id_cliente=p.id_cliente
JOIN $bd.parametros_generales pg
GROUP BY id_factura, id_cliente
ORDER BY id_factura";

$select_cab = $conn_pyme->ObtenerFilasBySqlSelect($sql_facturas);
foreach ($select_cab as $cab) {//Cabecera de la factura

	$ref=$cab['codigo_tienda'].str_pad($cab['id_factura'],5, "0", STR_PAD_LEFT);
	$chars = array("-", " ");
    $rif = trim(strtoupper(str_replace($chars, "", $cab['rif'])));

	$inser_cab_siga="INSERT INTO fafacturpro(reffac, fecfac, codcli, desfac, tipref,codconpag, monfac,status) VALUES 
    	('".$ref."', '".$cab['fechaFactura']."','".$rif."','Factura numero ".str_pad($cab['id_factura'],8, "0", STR_PAD_LEFT)." de la Planta ".$cab['codigo_tienda']."','V',6,'".$cab['TotalGroup']."','A');";
    	echo $inser_cab_siga."<br>";	
}

$sql_facturas_detalle="SELECT f.*, i.descripcion1, i.codigo_barras, sum(f._item_cantidad) AS cantidad_total, sum(f._item_totalsiniva) as cantidad_total_siv, sum(f._item_totalconiva) AS cantidad_total_civa, f._item_preciosiniva AS precio, pg.codigo_siga as codigo_tienda FROM $bd.factura_detalle f
INNER JOIN $bd.item i ON f.id_item=i.id_item
JOIN $bd.parametros_generales pg
GROUP BY id_factura,id_item
ORDER BY id_factura";
$select_det = $conn_pyme->ObtenerFilasBySqlSelect($sql_facturas_detalle);

foreach ($select_det as $det) {//Detalle de la factura

$iva=$det['cantidad_total_civa']-$det['cantidad_total_siv'];

$total_siniva=$det['precio']*$det['cantidad_total'];

$ref=$det['codigo_tienda'].str_pad($det['id_factura'],5, "0", STR_PAD_LEFT);

$inser_det_siga="INSERT INTO faartfacpro(reffac, codart, desart, codref, cantot, precio, monrgo, mondes, totart, estatus) 
            VALUES ('".$ref."','".$det['codigo_barras']."','".$det['descripcion1']."','".$ref."', '".$det['cantidad_total']."','".number_format($det['precio'],3,'.','')."',0,'0.00','".$total_siniva."', 'A');";
            echo $inser_det_siga."<br>";
}