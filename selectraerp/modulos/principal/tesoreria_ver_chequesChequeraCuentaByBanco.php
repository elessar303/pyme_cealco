<?php

include("../../libs/php/clases/cheque.php");
include("../../libs/php/clases/correlativos.php");

$comunes = new Comunes();
$correlativos = new Correlativos();

$tabla = "vw_chequebycuentabeneficiario";
$name_form = "vw_chequeBycuentabeneficiario";
$cod_chequera = @$_GET["cod_chequera"];
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

if (isset($_GET["cod_cuenta"])) {
    $campos = $comunes->ObtenerFilasBySqlSelect("
        select tb.cod_tesor_bandodet, tb.cod_banco, tb.nro_cuenta, b.descripcion as descripcion_banco, tb.descripcion as descripcion_cuenta
        from tesor_bancodet tb
        inner join banco b on b.cod_banco = tb.cod_banco
        where tb.cod_tesor_bandodet = " . $_GET["cod_cuenta"]);
    $smarty->assign("datos_banco", $campos);
}

if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];
        $busqueda = $_POST['busqueda'];
    }

    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta($tabla, $des, $busqueda);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas($tabla, $des, $busqueda);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera($tabla, $des, $busqueda);
            break;
    }
    $instruccion .= " and cod_chequera = " . $cod_chequera . " order by cheque";
} else {
    $instruccion = "SELECT * FROM  " . $tabla . " where cod_chequera = " . $cod_chequera . " order by cheque";
}

$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$situacion_chequera = $comunes->ObtenerFilasBySqlSelect("
                        select situacion from chequera where cod_chequera = " . $campos[0]["cod_chequera"] . "
                        ");

$smarty->assign("situacion_chequera", $situacion_chequera[0]["situacion"]);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("Situaci&oacute;n", "N&uacute;mero", "Ref", "Beneficiario", "Monto", "Fecha Cheque", "Fecha Anulaci&oacute;n", "Fecha Da&ntilde;ado"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);


$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());

$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= " . $_GET["opt_seccion"]);
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("cheque", "situacion", "descripcion_proveedor"));
$smarty->assign("option_output", array("Nro. Cheque", "Situacion", "Beneficiario/Proveedor"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//**************************************************************************
//**************************************************************************
//**************************************************************************
//Nombre del Formulario****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************
$smarty->assign("mensaje", $comunes->Notificacion());
/**
 * veficamos si tiene se generaron los cheques. se haberce generado
 * no devuelve si para indicar en el tpl la opcion de !generar cheques.
 */
$sqlcheques_generados = "
select if(che.cod_chequera,'si','no') as cheques_generados,  vw.cod_chequera   from vista_chequera_lista vw left join cheque che
 on che.cod_chequera = vw.cod_chequera
where  vw.cod_chequera
group by vw.cod_chequera";
$cheques_generados = $comunes->ObtenerFilasBySqlSelect($sqlcheques_generados);
foreach ($cheques_generados as $i => $valor) {
    $validarChequesGenerados[$valor["cod_chequera"]] = $valor["cheques_generados"];
}

$smarty->assign("validarChequesGenerados", $validarChequesGenerados);
$smarty->assign("url_query", $_SERVER['REQUEST_URI']);


if (isset($_GET["cheque"]) == true && isset($_GET["action_cheque"]) == true) {
    $cheque = new Cheque();
    $datacheque = $comunes->ObtenerFilasBySqlSelect("select * from cheque where cod_cheque = " . $_GET["cod_cheque"]);
    $ref2 = $datacheque[0]["ref"];
    $cheque->CambiarSituacionChequeByNroCheque(
            array(
                "cheque" => $_GET["cheque"],
                "action_cheque" => $_GET["action_cheque"],
                "cod_chequera" => $_GET["cod_chequera"]
    ));



    if ($_GET["action_cheque"] == "Da" || $_GET["action_cheque"] == "An") {
        list($dia, $mes, $anio) = explode("/", $_GET["fecha"]);
        $fechaDA = $anio . "-" . $mes . "-" . $dia;
        $motivo = $_GET["motivo"];

        if ($_GET["action_cheque"] == "An") {
            $comunes->Execute2("update cheque set fecha_anulacion = '" . $fechaDA . "',observacion_anulado = '" . $motivo . "' where cod_cheque = " . $_GET["cod_cheque"]);

            $format = $comunes->ObtenerFilasBySqlSelect("select * from correlativos where campo = 'cod_pago_o_abonoCXP'");

            $ref3 = $correlativos->FormatCorrelativo($format[0][formato], $ref2);
            $update = "
			update cxp_edocuenta inner join cxp_edocuenta_detalle on
			cxp_edocuenta_detalle.cod_edocuenta = cxp_edocuenta.cod_edocuenta
			and cxp_edocuenta_detalle.marca = 'X'
			set
			cxp_edocuenta.marca = 'P',
			cxp_edocuenta_detalle.marca = 'P'
			where cxp_edocuenta.numero = '" . $ref3 . "'
			";
            $comunes->Execute2($update);
        } else {
            $comunes->Execute2("update cheque set fecha_danado = '" . $fechaDA . "', observacion_danado = '" . $motivo . "' where cod_cheque = " . $_GET["cod_cheque"]);

            $format = $comunes->ObtenerFilasBySqlSelect("select * from correlativos where campo = 'cod_pago_o_abonoCXP'");

            $ref3 = $correlativos->FormatCorrelativo($format[0][formato], $ref2);
            $update = "
			update cxp_edocuenta inner join cxp_edocuenta_detalle on
			cxp_edocuenta_detalle.cod_edocuenta = cxp_edocuenta.cod_edocuenta
			and cxp_edocuenta_detalle.marca = 'X'
			set
			cxp_edocuenta.marca = 'P',
			cxp_edocuenta_detalle.marca = 'P'
			where cxp_edocuenta.numero = '" . $ref3 . "'
			";
            $comunes->Execute2($update);
        }

        $detalle_banco = $comunes->ObtenerFilasBySqlSelect("
		SELECT
		che.*,
		prov.descripcion as beneficiario,
		terbandet.cod_tesor_bandodet,
		terbandet.nro_cuenta,
		terbandet.cuenta_contable FROM cheque che
		inner join  chequera on chequera.cod_chequera = che.cod_chequera
		inner join tesor_bancodet terbandet on terbandet.cod_tesor_bandodet=chequera.cod_tesor_bandodet
		inner join banco on banco.cod_banco = terbandet.cod_banco
		left join proveedores prov on prov.id_proveedor = che.id_proveedor
		where che.cod_cheque =  " . $_GET["cod_cheque"]);

        if ($detalle_banco[0]["situacion"] != "A" && $detalle_banco[0]["situacion"] != "D") {// Si el cheque esta actuvo, o en deposito, no es necesario grabar en conciliacion bancaria
            $num_cheque = $detalle_banco[0]["cheque"];
            $monto = $detalle_banco[0]["monto"];

            $codigo_tipo_movimiento = 3; //NCredito
            $sql = "
			INSERT INTO `movimientos_bancarios` (
			`cod_tesor_bancodet` ,
			`fecha_movimiento` ,
			`tipo_movimiento` ,
			`numero_movimiento` ,
			`monto` ,
			`concepto` ,
			`contab` ,
			`estado` ,
			`cod_conciliacion` ,
			`fecha_creacion` ,
			`usuario_creacion`
			)
			VALUES (
			'" . $detalle_banco[0]["cod_tesor_bandodet"] . "', '" . $fechaDA . "', '" . $codigo_tipo_movimiento . "', '" . $num_cheque . "', '" . $monto . "', '" . $motivo . "', 'no', NULL , NULL ,
			CURRENT_TIMESTAMP , '" . $login->getUsuario() . "'
			);
			";
            $comunes->Execute2($sql);
        }//fin de if($datacheque[0]["situacion"]!=""){
    }
    header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"] . "&opt_subseccion=" . $_GET["opt_subseccion"] . "&cod=" . $_GET["cod"] . "&cod_cuenta=" . $_GET["cod_cuenta"] . "&cod_chequera=" . $_GET["cod_chequera"]);
}
?>