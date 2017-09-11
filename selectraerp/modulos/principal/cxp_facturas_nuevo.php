<?php
/*
 * Actualizaciones y mejoras:  jm
 * e-mail: jmpulgar@asys.com.ve
 */
include("../../libs/php/clases/cxp.php");
include("../../../menu_sistemas/lib/common.php");

$cxp = new CXP();
$edoCta = @$_GET['cod2'];
$cod = @$_GET['cod'];

if (isset($_POST["num_fac"])) {
// 	if(isset($_POST[optNc]))
// 		$_POST[optNc]="NC";
// 	else
// 		$_POST[optNc]="FAC";

    $_POST["libroCompras"] = isset($_POST["libroCompras"]) ? 1 : 0;

    $instruccion = "INSERT INTO cxp_factura (id_factura, cod_factura, cod_cont_factura, id_cxp_edocta, fecha_factura, fecha_recepcion, monto_base, monto_exento,  anticipo, monto_total_con_iva, monto_total_sin_iva, cod_impuesto, porcentaje_iva_mayor, monto_iva, porcentaje_iva_retenido, monto_retenido,  total_a_pagar,  cod_estatus, fecha_creacion, usuario_creacion,tipo,factura_afectada,libro_compras,id_proveedor) VALUES ('','$_POST[num_fac]','$_POST[num_cont]','$_POST[edoCta]','" . fecha_sql($_POST[fecha_fac]) . "','" . fecha_sql($_POST[fecha_fac_rec]) . "','$_POST[montoBase]','$_POST[montoExento]','$_POST[anticipo]','$_POST[montoConIva]','$_POST[montoSinIva]','$_POST[ivas]','$_POST[porcentajeIva]','$_POST[montoIva]','$_POST[porcentajeRetIva]','$_POST[montoRetenido]','$_POST[totalPagar]','1',CURRENT_TIMESTAMP,'" . $login->getUsuario() . "','$_POST[optNc]','$_POST[facturaAfectada]',$_POST[libroCompras],$cod)";
    $cxp->Execute2($instruccion);

    $id = $cxp->ObtenerFilasBySqlSelect("select max(id_factura) as fac from cxp_factura");
    $fac = $id[0][fac];
    $i = 0;
    while ($i <= $_POST[cantidad]) {
        if ($_POST['montoServ' . $i]) {
            $instruccion = "INSERT INTO cxp_factura_detalle (id_factura_detalle, id_factura_fk, monto_base, porcentaje_retenido, cod_impuesto, monto_retenido, id_item) VALUES ('','$fac','" . $_POST['montoServ' . $i] . "','" . $_POST['porcentajeRetIslr' . $i] . "','" . $_POST['codIslr' . $i] . "','" . $_POST['montoRetenidoIslr' . $i] . "','" . $_POST['codServ' . $i] . "')";
            $cxp->Execute2($instruccion);
        }
        $i++;
    }

    if ($cxp->errorTransaccion == 1) {
        Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> Factura cargada exitosamente<br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Sin Iva: " . number_format($_POST["montoSinIva"], 2, ",", ".") . " </b><br><img src=\"../../libs/imagenes/cancelar.png\"> <b>Monto Iva: " . number_format($_POST["montoIva"], 2, ",", ".") . " </b><br><img src=\"../../libs/imagenes/monto.png\"> <b>Monto Total: " . number_format($_POST["totalPagar"], 2, ",", ".") . " </b><br></span>");
    }
    elseif ($cxp->errorTransaccion == 0) {
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear la factura de compra.</span>");
    }

    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"] . "&opt_subseccion=facturasCXP&cod=" . $_POST["cod"] . "&cod2=" . $_POST["edoCta"]);
    exit;
}

$smarty->assign("edoCta", $edoCta);
$smarty->assign("cod", $cod);
// echo "select comdet.*, i.monto_exento, pro.cod_entidad, i.descripcion1 as descrip from cxp_edocuenta cp join compra com on (cp.numero=com.cod_compra) join compra_detalle comdet ON (com.id_compra=comdet.id_compra) join item i on (i.id_item=comdet.id_item) join proveedores pro on (cp.id_proveedor=pro.id_proveedor) where cp.cod_edocuenta=$edoCta";
// exit;
$compraDet = $cxp->ObtenerFilasBySqlSelect("select comdet.*, i.monto_exento, pro.cod_entidad, i.descripcion1 as descrip from cxp_edocuenta cp join compra com on (cp.numero=com.cod_compra) join compra_detalle comdet ON (com.id_compra=comdet.id_compra) join item i on (i.id_item=comdet.id_item) join proveedores pro on (cp.id_proveedor=pro.id_proveedor) where cp.cod_edocuenta=$edoCta");

$par1 = $cxp->ObtenerFilasBySqlSelect("select unidad_tributaria from parametros_generales");
$totalBase = 0;
$totalExc = "0.00";
$totalSinIva = 0;
$totalConIva = 0;
$mayorPiva = 0;
$montoIslr = 0;
$i = 0;
foreach ($compraDet as $comp_det => $item) {
    if ($item["monto_exento"] == 1) {
        $totalExc+=$item["_item_totalsiniva"];
        $totalBase = $totalBase;
    }
    else
    $totalBase+=$item["_item_totalsiniva"];
    $totalConIva+=$item["_item_totalconiva"];
    $totalSinIva+=$item["_item_totalsiniva"];

    if ($item["piva"] > $mayorPiva)
        $mayorPiva = $item["piva"];
    $islr = $cxp->ObtenerFilasBySqlSelect("select si.cod_lista_impuesto, fi.formula, li.alicuota, li.pago_mayor_a, li.monto_sustraccion, li.descripcion, li.cod_impuesto from servicios_islr si join lista_impuestos li on (si.cod_lista_impuesto=li.cod_impuesto) join formulacion_impuestos fi on (fi.cod_formula=li.cod_formula) where si.cod_item={$item["id_item"]} and li.cod_entidad={$item["cod_entidad"]}");
//echo "select si.cod_lista_impuesto, fi.formula, li.alicuota, li.pago_mayor_a, li.monto_sustraccion, li.descripcion, li.cod_impuesto from servicios_islr si join lista_impuestos li on (si.cod_lista_impuesto=li.cod_impuesto) join formulacion_impuestos fi on (fi.cod_formula=li.cod_formula) where si.cod_item=$item[id_item] and li.cod_entidad=$item[cod_entidad]";

    if ($islr[0]) {
        $UT = $par1[0]["unidad_tributaria"];
        $FACTORSUST = $islr[0]["monto_sustraccion"];
        $FACTORM = $islr[0]["pago_mayor_a"];
        $PORCENTAJE = $islr[0]["alicuota"];
        $MONTOBASE = $totalBase;
        $formula = $islr[0]["formula"];
        eval($formula);
        $retIslr[$i][0] = $MONTO;
        $retIslr[$i][1] = $islr[0]["descripcion"];
        $retIslr[$i][2] = $PORCENTAJE;
        $retIslr[$i][3] = $islr[0]["cod_impuesto"];
        $retIslr[$i][4] = $item["descrip"];
        $retIslr[$i][5] = $item["id_item"];
        $retIslr[$i][6] = $MONTOBASE;
        $retIslr[$i][7] = $item["cod_entidad"];
        $i++;
    }
}
$smarty->assign("hoy", date("d/m/Y"));
$smarty->assign("totalBase", $totalBase);
$smarty->assign("totalExc", $totalExc);
$smarty->assign("totalSinIva", $totalSinIva);
$smarty->assign("totalConIva", $totalConIva);
$smarty->assign("mayorPiva", $mayorPiva);
$smarty->assign("montoIva", ($totalConIva - $totalSinIva));
$smarty->assign("retIslr", $retIslr);
$smarty->assign("idCompra", $item["id_compra"]);

if (!$compraDet) {

    $prove = $cxp->ObtenerFilasBySqlSelect("select prov.cod_entidad from proveedores prov join cxp_edocuenta cxp on (prov.id_proveedor=cxp.id_proveedor) where cxp.cod_edocuenta=$edoCta");

    $par = $cxp->ObtenerFilasBySqlSelect("select servicio_fk, descripcion1, unidad_tributaria  from parametros_generales par inner join item i on (par.servicio_fk=i.id_item)");

    $cxp_edocta = $cxp->ObtenerFilasBySqlSelect("select * from  cxp_edocuenta  where cod_edocuenta=$edoCta");

    $totalBase = $cxp_edocta[0][monto];

// 	echo "select si.cod_lista_impuesto, fi.formula, li.alicuota, li.pago_mayor_a, li.monto_sustraccion, li.descripcion, li.cod_impuesto from servicios_islr si join lista_impuestos li on (si.cod_lista_impuesto=li.cod_impuesto) join formulacion_impuestos fi on (fi.cod_formula=li.cod_formula) where si.cod_item='".$par[0]["servicio_fk"]."' and li.cod_entidad='".$prove[0]["cod_entidad"]."'";
// 	exit;
// 		exit;
    $islr = $cxp->ObtenerFilasBySqlSelect("select si.cod_lista_impuesto, fi.formula, li.alicuota, li.pago_mayor_a, li.monto_sustraccion, li.descripcion, li.cod_impuesto from servicios_islr si join lista_impuestos li on (si.cod_lista_impuesto=li.cod_impuesto) join formulacion_impuestos fi on (fi.cod_formula=li.cod_formula) where si.cod_item='" . $par[0]["servicio_fk"] . "' and li.cod_entidad='" . $prove[0]["cod_entidad"] . "'");
    if ($islr[0]) {

        $UT = $par[0]["unidad_tributaria"];
        $FACTORSUST = $islr[0]["monto_sustraccion"];
        $FACTORM = $islr[0]["pago_mayor_a"];
        $PORCENTAJE = $islr[0]["alicuota"];
        $MONTOBASE = $cxp_edocta[0][monto];
        $formula = $islr[0]["formula"];
        eval($formula);
        $retIslr[$i][0] = $MONTO;
        $retIslr[$i][1] = $islr[0]["descripcion"];
        $retIslr[$i][2] = $PORCENTAJE;
        $retIslr[$i][3] = $islr[0]["cod_impuesto"];
        $retIslr[$i][4] = $par[0]["descripcion1"];
        $retIslr[$i][5] = $par[0]["servicio_fk"];
        $retIslr[$i][6] = $MONTOBASE;
        $retIslr[$i][7] = $prove[0]["cod_entidad"];
        if ($MONTO == 0)
            unset($retIslr);
    }

    $smarty->assign("hoy", date("d/m/Y"));
    $smarty->assign("totalBase", $totalBase);
    $smarty->assign("totalExc", $totalExc);
    $smarty->assign("totalSinIva", $totalBase);
    $smarty->assign("totalConIva", $totalBase);
    $smarty->assign("mayorPiva", $mayorPiva);
    $smarty->assign("montoIva", ($totalConIva - $totalSinIva));
    $smarty->assign("retIslr", $retIslr);
    $smarty->assign("idCompra", $item["id_compra"]);
}

$ivas = $cxp->ObtenerFilasBySqlSelect("select cod_impuesto, descripcion from lista_impuestos where cod_tipo_impuesto = 1");
$valueSELECT = "";
$outputSELECT = "";
foreach ($ivas as $key => $iva) {
    $valueSELECT[] = $iva["cod_impuesto"];
    $outputSELECT[] = $iva["descripcion"];
}
$smarty->assign("option_values_iva", $valueSELECT);
$smarty->assign("option_output_iva", $outputSELECT);
////////////////
$islrs = $cxp->ObtenerFilasBySqlSelect("select cod_impuesto, descripcion from lista_impuestos where cod_tipo_impuesto = 2");
$valueSELECT = "";
$outputSELECT = "";
foreach ($islrs as $key => $islr) {
    $valueSELECT[] = $islr["cod_impuesto"];
    $outputSELECT[] = $islr["descripcion"];
}
$smarty->assign("option_values_islr", $valueSELECT);
$smarty->assign("option_output_islr", $outputSELECT);
////////////////////
$facs = $cxp->ObtenerFilasBySqlSelect("SELECT * FROM cxp_factura WHERE id_cxp_edocta = ".$edoCta);
$valueSELECT = "";
$outputSELECT = "";
foreach ($facs as $key => $fac) {
    $valueSELECT[] = $fac["id_factura"];
    $outputSELECT[] = $fac["cod_factura"] . '--' . $fac["cod_cont_factura"];
}
$smarty->assign("option_values_fac", $valueSELECT);
$smarty->assign("option_output_fac", $outputSELECT);
?>