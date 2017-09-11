<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
$login = new Login();

$usuario = $login->getUsuario();
if(isset($_POST["aceptar"])){
	
	// $kardex_almacen_instruccion = "INSERT INTO kardex_almacen (
 //            `tipo_movimiento_almacen`, `autorizado_por`, `observacion`,
 //            `fecha`, `usuario_creacion`, `fecha_creacion`, `estado`, `fecha_ejecucion`)
	//    VALUES (
 //            '2', '{$usuario}', 'Salida por Despacho',
 //            CURRENT_TIMESTAMP, '{$usuario}', CURRENT_TIMESTAMP,
 //            'Entregado', CURRENT_TIMESTAMP);";

    
 //        $almacen->ExecuteTrans($kardex_almacen_instruccion);
 //        $id_transaccion = $almacen->getInsertID();


	for ($i=0; $i <= $_POST["cant"] ; $i++) { 
		$instruccion = "UPDATE despacho_detalle set `serial` = '".$_POST["serial".$i]."' WHERE id = ".$_POST["id".$i];
	    $almacen->Execute2($instruccion);
	    
	    $instruccion = "UPDATE item_serial set estado=0 WHERE id_producto = '".$_POST["id_item".$i]."' and serial = '".$_POST["serial".$i]."'";
	    $almacen->Execute2($instruccion);

		// $kardex_almacen_detalle_instruccion = "
  //           INSERT INTO kardex_almacen_detalle (
  //               `id_transaccion` , `id_almacen_entrada` ,
  //               `id_almacen_salida` , `id_item` , `cantidad`)
  //           VALUES (
  //               '{$id_transaccion}', '', '1',
  //               '".$_POST["id_item".$i]."', '1');";

  //           $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);
	    
	    
	}
	
	$instruccion2 = "UPDATE despacho set `estatus` = '1' WHERE id = ".$_GET["cod"];
	    $almacen->Execute2($instruccion2);
	    $ruta="?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"];
	//header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
	
	 //$url = sprintf("Location: ../../reportes/rpt_despacho.php?codigo=%s",$_GET["cod"]);
	 ?>
	 <script type="text/javascript" language="Javascript">
	 if( window.open('../../reportes/rpt_despacho_html.php?codigo=<?php echo $_GET["cod"]?>')){
	 	 location.href="<?php echo $ruta ?>";
	 }
	 </script>
	 <?php
    // header($url);
    // exit;
	 // header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);

}
if(isset($_GET["cod"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select a.*,b.estatus from despacho_detalle a, despacho b where a.id_despacho=b.id and a.id_despacho = ".$_GET["cod"]);
$smarty->assign("datos_despacho",$campos);
}

?>
