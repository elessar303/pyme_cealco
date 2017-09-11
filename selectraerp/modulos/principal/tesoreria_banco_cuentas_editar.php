<?php

$comunes = new Comunes();

if (isset($_POST["descripcion"])) {

    list($dia, $mes, $anio) = explode("/", $_POST["fechaapertura"]);
    $fe_apertura = $anio . "-" . $mes . "-" . $dia;

    $query = "UPDATE tesor_bancodet
              SET descripcion = '" . $_POST["descripcion"] . "', nro_cuenta = '" . $_POST["nrocuenta"] . "',
                cod_tipo_cuenta_banco = '" . $_POST["cod_tipo_cuenta_banco"] . "', cuenta_contable = '" . $_POST["cuenta_contable"] . "',
                comision_tarjeta_debito = '" . $_POST["com_debito"] . "', comision_tarjeta_credito = '" . $_POST["com_credito"] . "',
                comision_idb = '" . $_POST["com_idb"] . "', retencion_islr = '" . $_POST["com_islr"] . "',
                fecha_apertura = '" . $fe_apertura . "', monto_apertura = '" . $_POST["montoapertura"] . "',
                monto_disponible = '" . $_POST["montodisponible"] . "'
              WHERE cod_tesor_bandodet = " . $_POST["codCuenta"];

    $comunes->Execute2($query);

    header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"] . "&&opt_subseccion=viewcuentasByBanco&cod=" . $_POST["codBanco"]);

    exit;
}

$campos_cuentas = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM tesor_bancodet WHERE cod_tesor_bandodet = " . $_GET["cod_cuenta"]);

$smarty->assign("campos_cuentas", $campos_cuentas);
?>
