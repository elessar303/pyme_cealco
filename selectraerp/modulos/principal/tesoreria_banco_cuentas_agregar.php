<?php

$comunes = new Comunes();

if (isset($_POST["descripcion"])) {

    list($dia, $mes, $anio) = explode("/", $_POST["fechaapertura"]);//fecha_apertura
    $fe_apertura = $anio . "-" . $mes . "-" . $dia;

    $query = "INSERT INTO `tesor_bancodet` (
                `cod_tesor_bandodet`, `cod_banco`, `descripcion`,
                `nro_cuenta`, `cod_tipo_cuenta_banco`,
                `comision_tarjeta_debito`, `comision_tarjeta_credito`, `comision_idb`, `retencion_islr`,
                `monto_apertura`, `monto_disponible`, `cuenta_contable`,
                `fecha_apertura`, `usuario_creacion`, `fecha_creacion`
            )
            VALUES (
                NULL , '" . $_POST["codBanco"] . "', '" . $_POST["descripcion"] . "',
                '" . $_POST["nrocuenta"] . "', '" . $_POST["cod_tipo_cuenta_banco"] . "',
                '" . $_POST["com_debito"] . "', '" . $_POST["com_credito"] . "', '" . $_POST["com_idb"] . "', '" . $_POST["com_islr"] . "',
                '" . $_POST["montoapertura"] . "', '" . $_POST["montodisponible"] . "','" . $_POST["cuenta_contable"] . "',
                '" . $fe_apertura . "', '" . $login->getUsuario() . "', CURRENT_TIMESTAMP
            );";

    $comunes->Execute2($query);

    header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"] . "&&opt_subseccion=viewcuentasByBanco&cod=" . $_POST["codBanco"]);
    exit;
}

// Cargando tipo_comercio en combo select
$arraySelectOption = $arraySelectoutPut = "";
$campos_comunes = $comunes->ObtenerFilasBySqlSelect("select * from tipo_cuenta_banco");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["cod_tipo_cuenta_banco"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_cuenta_banco", $arraySelectOption);
$smarty->assign("option_output_tipo_cuenta_banco", $arraySelectoutPut);
?>
