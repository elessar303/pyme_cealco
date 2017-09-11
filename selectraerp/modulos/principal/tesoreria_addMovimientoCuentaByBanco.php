<?php

$comunes = new Comunes();

if (isset($_GET["cod_cuenta"])) {
    $campos = $comunes->ObtenerFilasBySqlSelect("
    select tb.cod_tesor_bandodet, tb.cod_banco, tb.nro_cuenta, b.descripcion as descripcion_banco, tb.descripcion as descripcion_cuenta
    from tesor_bancodet tb
    inner join banco b on b.cod_banco = tb.cod_banco
    where tb.cod_tesor_bandodet = " . $_GET["cod_cuenta"]);
    $smarty->assign("datos_banco", $campos);
}

if (isset($_POST["aceptar"])) {
    list($dia, $mes, $anio) = explode("/", $_POST["fecha"]);
    $_POST["fecha"] = $anio . "-" . $mes . "-" . $dia;
    $comunes->Execute2("
    INSERT INTO `movimientos_bancarios` (
    `cod_movimiento_ban`, `cod_tesor_bancodet`, `fecha_movimiento`,
    `tipo_movimiento`, `numero_movimiento`, `monto`, `concepto`, `fecha_creacion`, `usuario_creacion`)
    VALUES (
    NULL , '" . $_POST["codCuenta"] . "', '" . $_POST["fecha"] . "', '" . $_POST["cod_tipo_movimiento"] . "',
    '" . $_POST["numero_movimiento"] . "', '" . $_POST["monto"] . "', '" . $_POST["concepto"] . "', CURRENT_TIMESTAMP ,
    '" . $login->getUsuario() . "');");
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"] . "&opt_subseccion=movimientosCuentaByBanco&cod=" . $_POST["codBanco"] . "&cod_cuenta=" . $_POST["codCuenta"]);
    exit;
}

$valueSELECT = "";
$outputSELECT = "";
$campos = $comunes->ObtenerFilasBySqlSelect("select * from tipo_movimientos_ban");
foreach ($campos as $key => $item) {
    $valueSELECT[] = $item["cod_tipo_movimientos_ban"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_funciontipomovimiento", $valueSELECT);
$smarty->assign("option_output_funciontipomovimiento", $outputSELECT);
$smarty->assign("hoy", date("d/m/Y"));
?>