<?php
session_start();
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/comunes.php");
require_once("../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");

$almacen = new Almacen();
$login = new Login();
$pos=POS;

if ($_GET["generar"] == "si") {
	$usuario = $login->getUsuario();
	
	$desde=date("Y-m-d")." 00:00:00";
	$hasta=date("Y-m-d")." 23:59:00";
	
	//$desde="2014-02-26 00:00:00";
	//$hasta="2014-02-26 23:59:00";
	$almacen->BeginTrans();
	//"SELECT PRODUCT, sum(UNITS) as CANT, PRICE FROM $pos.stockdiary WHERE DATENEW BETWEEN '$desde' and '$hasta' GROUP BY PRODUCT";
		$movposs = $almacen->ObtenerFilasBySqlSelect("SELECT PRODUCT, sum(UNITS) as CANT, PRICE FROM $pos.stockdiary WHERE DATENEW BETWEEN '$desde' and '$hasta' GROUP BY PRODUCT");
	if(count($movposs)>0)
	{
		$sql="INSERT INTO kardex_almacen (tipo_movimiento_almacen, autorizado_por, observacion, fecha, usuario_creacion, fecha_creacion, estado, fecha_ejecucion, id_documento) VALUES ('2', '$usuario', 'Salida por Ventas', '".date("Y-m-d")."', '$usuario', '".date("Y-m-d H:i:s")."', 'Entregado', '".date("Y-m-d H:i:s")."','0')";
		$almacen->ExecuteTrans($sql);
		$id_kardex = $almacen->getInsertID();
		foreach ($movposs as $key => $movpos)
		{
			$itemm = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE  itempos='".$movpos[PRODUCT]."'");  				
  			$item=$itemm[0]["id_item"];			
  			
  			$ubic = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE  puede_vender='1'");  				
  			$idUbic=$ubic[0]["id"];
			$canti=$movpos[CANT]*(-1);
			$sql="INSERT INTO kardex_almacen_detalle (id_transaccion, id_almacen_salida, id_item, cantidad, id_ubi_salida, precio) VALUES ('$id_kardex', '1', '$item', '".$canti."', '".$idUbic."', '".$movpos[PRICE]."')";
			$almacen->ExecuteTrans($sql);

			//echo "<br>";

			$campos = $almacen->ObtenerFilasBySqlSelect("select * from item_existencia_almacen where id_item  = '$item' and id_ubicacion  = '$idUbic' ");
			$cantidadExistente = $campos[0][cantidad];
			$codAlmacen = $campos[0][cod_almacen];

			$almacen->ExecuteTrans("update item_existencia_almacen set cantidad = '" . ($cantidadExistente - $canti) . "' where id_item  = '$item' and cod_almacen = '$codAlmacen' and id_ubicacion='$idUbic'");
		}
	}

	if ($almacen->errorTransaccion == 1) {
        
        echo "paso";
        
    } else if ($almacen->errorTransaccion == 0) {
        echo "no paso";
    }
    $almacen->CommitTrans($almacen->errorTransaccion);



   header("Location: index.php?opt_menu=106");
   exit;
}
?>
