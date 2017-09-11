<?php

include("../../libs/php/clases/clientes.php");
include("../../libs/php/clases/cxc.php");

$clientes = new Clientes();
$cxc = new CXC();

if(!isset($_GET["cod"])){
    header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
    exit;
}

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


$smarty->assign("cabecera",array("Detalle","Documento","Numero","Emisión","Vencimiento","Descripción","Total Factura","Estado","Opt"));

/**
 * Cabecera del Estado de Cuenta.
 */
$cabecera_estadodecuenta = $cxc->ObtenerFilasBySqlSelect('
select 
ifnull(sum(debito),0.00) as debito,
ifnull(sum(credito),0.00) as credito,
ifnull(sum(debito) -  sum(credito),0.00)  as saldo_pendiente,
(
select  count(cod_edocuenta) from cxc_edocuenta c where
c.marca = "X" and c.documento = "FAC" and c.id_cliente = vw_cxc.id_cliente
) as facturas_pagadas,
(
select  count(cod_edocuenta) from cxc_edocuenta c where c.marca <> "X" and c.documento = "FAC" and c.id_cliente = vw_cxc.id_cliente
) as facturas_pendientes,
(select  count(cod_edocuenta) from cxc_edocuenta c where
c.documento = "FAC" and c.id_cliente = vw_cxc.id_cliente) as total_facturas
 FROM vw_cxc where id_cliente =  '.$_GET['cod'].' group by (id_cliente)');
$smarty->assign("cabecera_estadodecuenta",$cabecera_estadodecuenta);


/**
 * Detalle del Estado de Cuenta
 */
$smarty->assign("datacliente",$datacliente);
$filas_estadodecuenta = $cxc->ObtenerFilasBySqlSelect("
   select
cod_edocuenta,
id_cliente,
documento,
numero,
monto,
case marca when 'X' then 'Pagada' else 'Pendiente' end as estado,
observacion,
fecha_emision,
vencimiento_fecha,
vencimiento_persona_contacto,
vencimiento_telefono,
vencimiento_descripcion
 from cxc_edocuenta
 where id_cliente  = ".$_GET["cod"]." order by numero, estado");
$smarty->assign("registros",$filas_estadodecuenta);
$smarty->assign("cantidadFilas",$cxc->getFilas());
$mensaje="";
if($cxc->getFilas()==0){
    $mensaje="<span style=\"color:red\"><b>No se encontraron filas.</b></span>";
}
$smarty->assign("mensaje",$mensaje);

$datosempresa = $cxc->ObtenerFilasBySqlSelect("select * from parametros_generales");
$smarty->assign("empresa",$datosempresa);


?>
