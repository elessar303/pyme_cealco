<?php

include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/cxp.php");

$proveedores = new Proveedores();
$cxp = new CXP();

if (!isset($_GET["cod"])) {
    header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"]);
    exit;
}

$datacliente = $proveedores->ObtenerFilasBySqlSelect("select * from proveedores where id_proveedor = " . $_GET["cod"]);

$data_retencion = $proveedores->ObtenerFilasBySqlSelect("SELECT cod_impuesto, alicuota FROM lista_impuestos WHERE cod_impuesto = {$datacliente[0]["cod_impuesto_proveedor"]}");
$smarty->assign("data_retencion", $data_retencion);

if (count($datacliente) == 0) {
    $pagina .= "<html>";
    $pagina .= "<body style=\"background-color:#f8f8f8\">";
    $pagina .= "<div  style=\"background-color:#dcdedb; border: 1px solid black;-moz-border-radius: 8px;padding:5px; margin-left: 20%;margin-right: 20%;margin-top:5%;   font-size: 13px; \">";
    $pagina .= "<img src=\"../../libs/imagenes/configuracion.png\"> <b>Disculpe esta operacion esta permitida:</b> <br>
        <span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> Verifique que el proveedor</span><br>";
    $pagina .= "<hr><span style=\"color:#1e6602\">Para mas información contacte al administrador.</span>";
    if (count($campos) > 0)
        $pagina .= "<br><span style=\"color:red\"><img style=\"border:none;\" src=\"../../libs/imagenes/ico_list.gif\"> Detalle del error:</span><br><b style=\"padding-left:30px;\"><img src=\"../../libs/imagenes/ico_search.gif\"> Modulo:</b> " . $campos[0]["descripcion_optmenu"] . " - <b>Sección:</b> " . $campos[0]["descripcion_optseccion"] . " >> <b>" . $campos[0]["opt_subseccion"] . ":</b> " . $campos[0]["descripcion"];
    $pagina .= "<hr><br><br><a style=\"text-decoration:none;\" href='?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"] . "'><img style=\"border:none;\" src=\"../../libs/imagenes/ico_back.gif\"> Volver</a>";
    $pagina .= "</div>";
    $pagina .= "</body>";
    $pagina .= "</html>";
    echo utf8_decode($pagina);
    exit;
}

$smarty->assign("cabecera", array("Detalle", "Documento", "N&uacute;mero", "Emisi&oacute;n", "Vencimiento", "Descripci&oacute;n", "Total Compra", "Estado", "Opciones"));

/**
 * Cabecera del Estado de Cuenta.
 */
$cadena = "select
ifnull(sum(debito),0.00) as debito,
ifnull(sum(credito),0.00) as credito,
ifnull(sum(credito) - sum(debito) ,0.00) as saldo_pendiente,
(
    select count(cod_edocuenta) from cxp_edocuenta c
    where c.marca = 'X' and c.marca <> 'A' and c.documento = 'FACxCOM' and c.id_proveedor = vw_cxp.id_proveedor
)
as facturas_pagadas,
(
    select count(cod_edocuenta) from cxp_edocuenta c
    where c.marca <> 'X'  and c.marca <> 'A' and c.documento = 'FACxCOM' and c.id_proveedor = vw_cxp.id_proveedor
)
as facturas_pendientes,
(
    select  count(cod_edocuenta) from cxp_edocuenta c
    where c.documento = 'FACxCOM' and c.marca <> 'A' and c.id_proveedor = vw_cxp.id_proveedor
)
as total_facturas
FROM vw_cxp where id_proveedor =  '" . $_GET['cod'] . "' and marca <> 'A' and estado=1 group by (id_proveedor)";

$cabecera_estadodecuenta = $cxp->ObtenerFilasBySqlSelect($cadena);
$smarty->assign("cabecera_estadodecuenta", $cabecera_estadodecuenta);
/**
 * Detalle del Estado de Cuenta
 */
$smarty->assign("datacliente", $datacliente);

$sql_cxp = "select cod_edocuenta, id_proveedor, documento, numero, monto,
            case marca when 'X' then 'Pagada' when 'A' then 'Anulada' else 'Pendiente' end as estado,
            observacion, fecha_emision,
            vencimiento_fecha, vencimiento_persona_contacto,
            vencimiento_telefono, vencimiento_descripcion,
            ifnull((select sum(monto) from cxp_edocuenta_detalle where cod_edocuenta=cxpe.cod_edocuenta and tipo='d' and estado='1'),0) as montodet
            from cxp_edocuenta cxpe
            where id_proveedor  = " . $_GET["cod"] . " order by numero, estado";
$filas_estadodecuenta = $cxp->ObtenerFilasBySqlSelect($sql_cxp);
$smarty->assign("registros", $filas_estadodecuenta);
$smarty->assign("cantidadFilas", $cxp->getFilas());

/* $mensaje = "";
  if ($cxp->getFilas() == 0) {
  $mensaje = "<span style=\"color:red\"><b>No se encontraron filas.</b></span>";
  } */
$smarty->assign("mensaje", $cxp->getFilas() == 0 ? "<span style=\"color:red\"><b>No se encontraron filas.</b></span>" : "");

$datosempresa = $cxp->ObtenerFilasBySqlSelect("select * from parametros_generales");
$smarty->assign("empresa", $datosempresa);

$smarty->assign("responsable", utf8_decode($login->getNombreApellidoUSuario()));
$smarty->assign("usuario", $login->getUsuario());
?>
