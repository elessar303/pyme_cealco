<?php
include("../../libs/php/clases/clientes.php");
include("../../libs/php/clases/cxc.php");
include("../../../menu_sistemas/lib/common.php");

$clientes = new Clientes();
$cxc = new cxc();


/*guardar
*/
echo $_POST["pagoRecibido"];

echo @$_POST[codigomov];
echo "hola";
if($_POST["cantidad"]!=0 && $_POST["banco"]!="" && $_POST["trans"]!=0 && $_POST["pagoRecibido"]!=0)
{
	$cxc->BeginTrans();

// 	$INSERT="insert into cxc_pago values ('',".$_POST["montoBase"].",".$_POST["totaliva"].",".$_POST["totalRetiva"].",".$_POST["totalRetislr"].",".$_POST["totalim"].",".$_POST["montoTotal"].",".$_POST["banco"].",'".$_POST["tipo_trans"]."','".$_POST["trans"]."' ,'".$_POST["fecha"]."')";
// 	$cxc->ExecuteTrans($INSERT);
	//$pago = $cxc->ObtenerFilasBySqlSelect("select max(id_pago) as id from cxc_pago");
// 	$id=$cxc->getInsertID();

	$i=0;
	while($i<=$_POST[cantidad_item])
	{
		$cad="optAgregar".$i;
		if($_POST[$cad]==$i)
		{
			$con = $cxc->ObtenerFilasBySqlSelect("select cod_edocuenta from cxc_edocuenta where numero='".$_POST["numero".$i]."'");
			$cod_edo=$con[0][cod_edocuenta];
			echo $INSERT="insert into cxc_edocuenta_detalle (cod_edocuenta_detalle,cod_edocuenta,documento,numero,descripcion,fecha_emision_edodet,tipo,monto,estado,usuario_creacion,fecha_creacion,marca,cod_impuesto_porc_fk,monto_retenido) values ('',$cod_edo,'PAGOxFAC','".$_POST["codigomov"]."','"."FACTURA ".$_POST["numero".$i]."','".fecha_sql($_POST[fechad])."','c','".$_POST["montopag".$i]."','1','".$login->getUsuario()."',CURRENT_TIMESTAMP,'','".$_POST["islr".$i]."','".$_POST["im".$i]."')";
			$cxc->ExecuteTrans($INSERT);
			
			echo $UPDATE="update cxp_factura_medico set estatus='0' where factura_fk='".$_POST["numero".$i]."'";
			$cxc->ExecuteTrans($UPDATE);
			echo "aa".$_POST["saldo".$i]."bb";
			if($_POST["saldo".$i]==0)
			{
				echo $UPDATE="update cxc_edocuenta set marca='X' where cod_edocuenta=$cod_edo";
				$cxc->ExecuteTrans($UPDATE);
			}
		}
		$i++;
	}

	if($_POST[montoTotal]==$_POST[pagoRecibido])
	{
		echo $UPDATE="UPDATE movimientos_bancarios SET estatus='X', monto_aplicado='$_POST[pagoRecibido]' WHERE cod_movimiento_ban=$_POST[codigomov]";
		$cxc->ExecuteTrans($UPDATE);
	}
	elseif($_POST[montoTotal]<$_POST[pagoRecibido])
	{
		echo $UPDATE="UPDATE movimientos_bancarios SET  monto_aplicado='$_POST[montoTotal]' WHERE cod_movimiento_ban=$_POST[codigomov]";
		$cxc->ExecuteTrans($UPDATE);
	}


	if($cxc->errorTransaccion==1)
	{
		Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Pago/Abono por Compra fue generado exitosamente bajo el <b>Nro. ".$cod_pago_o_abono."</b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total: ".number_format($_POST["montoPago"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/cancelar.png\"> <b>Monto Cancelado: ".number_format($_POST["montoPago"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total Retencion: ".number_format($_POST["totalRet"], 2, ",", ".") ." </b><br><img src=\"../../libs/imagenes/ico_view.gif\"> <b><span style=\"color:red;\">Monto Pendiente: ".number_format($_POST["montoPendiente"], 2, ",", ".") ." </span></b><br></span>");
	}
	if($cxc->errorTransaccion==0)
	{
		Msg::setMessage("<span style=\"color:red;\">Error al tratar de cargar el pago.</span>");
	}
	$cxc->CommitTrans($cxc->errorTransaccion);
	exit;
}
// exit;


/**
 * Cabecera del Estado de Cuenta.
 */
 
$datacliente = $clientes->ObtenerFilasBySqlSelect("select * from clientes where id_cliente = ".$_GET["cod"]);
if(count($datacliente)==0){

    $pagina .= "<html>";
    $pagina .= "<body style=\"background-color:#f8f8f8\">";
    $pagina .= "<div  style=\"background-color:#dcdedb; border: 1px solid black;-moz-border-radius: 8px;padding:5px; margin-left: 20%;margin-right: 20%;margin-top:5%;   font-size: 13px; \">";
    $pagina .= "<img src=\"../../libs/imagenes/configuracion.png\"> <b>Disculpe esta operacion esta permitida:</b> <br>
        <span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> Verifique que el cliente al que desea facturar exista.</span><br>";
    $pagina .= "<hr><span style=\"color:#1e6602\">Para mas información contacte al administrador.</span>";
if(count($campos)>0) $pagina .= "<br><span style=\"color:red\"><img style=\"border:none;\" src=\"../../libs/imagenes/ico_list.gif\"> Detalle del error:</span><br><b style=\"padding-left:30px;\"><img src=\"../../libs/imagenes/ico_search.gif\"> Modulo:</b> ".$campos[0]["descripcion_optmenu"]." - <b>Sección:</b> ".$campos[0]["descripcion_optseccion"]." >> <b>".$campos[0]["opt_subseccion"].":</b> ".$campos[0]["descripcion"];
    $pagina .= "<hr><br><br><a style=\"text-decoration:none;\" href='?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]."'><img style=\"border:none;\" src=\"../../libs/imagenes/ico_back.gif\"> Volver</a>";
    $pagina .= "</div>";
    $pagina .= "</body>";
    $pagina .= "</html>";
    echo utf8_decode($pagina);
    exit;
}
$facs = $cxc->ObtenerFilasBySqlSelect("select *, (monto-(select ifnull(sum(monto),0.00) from cxc_edocuenta_detalle where cod_edocuenta=cxc_edocuenta.cod_edocuenta and tipo='c')) as saldo from cxc_edocuenta where id_cliente=".$_GET["cod"]." and marca='A'");

$total = $cxc->ObtenerFilasBySqlSelect("select sum(monto) as monto, sum(monto_base) as monto_base, sum(monto_iva) as monto_iva from cxc_edocuenta where id_cliente=".$_GET["cod"]." and marca='A'");

$datos_tipodocumento = $cxc->ObtenerFilasBySqlSelect("select * from lista_impuestos where cod_tipo_impuesto=1");
$valueSELECT="";
$outputSELECT="";
foreach($datos_tipodocumento as $key => $item){
    $valueSELECT[] = $item["cod_impuesto"]."-".$item["alicuota"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_iva",$valueSELECT);
$smarty->assign("option_output_tipo_iva",$outputSELECT);




$datos_tipodocumento = $cxc->ObtenerFilasBySqlSelect("select * from lista_impuestos where cod_tipo_impuesto=2");
$valueSELECT="";
$outputSELECT="";
foreach($datos_tipodocumento as $key => $item){
    $valueSELECT[] =  $item["cod_impuesto"]."-".$item["alicuota"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_islr",$valueSELECT);
$smarty->assign("option_output_tipo_islr",$outputSELECT);


$datos_tipodocumento = $cxc->ObtenerFilasBySqlSelect("select * from banco");
$valueSELECT="";
$outputSELECT="";
foreach($datos_tipodocumento as $key => $item){
    $valueSELECT[] = $item["cod_banco"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_banco",$valueSELECT);
$smarty->assign("option_output_banco",$outputSELECT);


$smarty->assign("fac",$facs);
$smarty->assign("datacliente",$datacliente);
$smarty->assign("total",$total);
?>