<?php

//include("../../libs/php/clases/ConexionComun.php");
include("../../libs/php/clases/clientes.php");
include("../../../menu_sistemas/lib/common.php");

$clientes = new Clientes();

if (isset($_POST["aceptar"])) { // si el usuario iso post
    $instruccion = "UPDATE clientes SET
        cod_cliente = '{$_POST["cod_cliente"]}', nombre = '" . strtoupper($_POST["nombre"]) . "', fnacimiento = '{$_POST["fnacimiento"]}',
        representante = '" . strtoupper($_POST["representante"]) . "', direccion = '" . strtoupper($_POST["direccion"]) . "',
        altena = '" . strtoupper($_POST["altena"]) . "', alterna2 = '" . strtoupper($_POST["alterna2"]) . "',
        telefonos = '{$_POST["telefonos"]}', fax = '{$_POST["fax"]}', email = '{$_POST["email"]}',
        permitecredito = '{$_POST["permitecredito"]}', limite = '{$_POST["limite"]}', dias = '{$_POST["dias"]}',
        tolerancia = '{$_POST["tolerancia"]}', porc_parcial = '{$_POST["porc_parcial"]}', porc_descuento_global = '{$_POST["porc_descuento_global"]}',
        calc_reten_impuesto_islr = '{$_POST["calc_reten_impuesto_islr"]}', calc_reten_impuesto_iva = '{$_POST["calc_reten_impuesto_iva"]}',
        calc_reten_impuesto_1x1000 = '{$_POST["calc_reten_impuesto_1x1000"]}', cod_vendedor = '{$_POST["cod_vendedor"]}',
        cod_zona = '{$_POST["cod_zona"]}', rif = '" . strtoupper($_POST["rif"]) . "', nit = '" . strtoupper($_POST["nit"]) . "',
        contribuyente_especial = '{$_POST["contribuyente_especial"]}', retenido_por_cliente = '{$_POST["retenido_por_cliente"]}',
        cod_tipo_cliente = '{$_POST["cod_tipo_cliente"]}', cod_entidad = '{$_POST["cod_entidad"]}',
        cod_tipo_precio = '{$_POST["cod_tipo_precio"]}', clase = '{$_POST["clase"]}', estado = '{$_POST["estado"]}',
        cuenta_contable = '{$_POST["cuenta_contable"]}'
    WHERE id_cliente = '{$_POST["id_cliente"]}'";
    $clientes->Execute2($instruccion);

    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
    exit;
} else {

    if (!isset($_GET["cod"])) {
        header("Location: ?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"]);
    }
    $datacliente = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM clientes WHERE id_cliente = {$_GET["cod"]};");

//CONSULTA DE ID FISCAL EN PARAMETROS
    $valueSELECT = "";
    $outputSELECT = "";
    $data_parametros = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
    foreach ($data_parametros as $item) {
        $valueSELECT[] = $item["cod_empresa"];
        $outputidfiscalSELECT[] = $item["id_fiscal"];
        $outputidfiscal2SELECT[] = $item["id_fiscal2"];
    }
    $smarty->assign("option_values_parametros", $valueSELECT);
    $smarty->assign("option_output_idfiscal", $outputidfiscalSELECT);
    $smarty->assign("option_output_idfiscal2", $outputidfiscal2SELECT);
//$smarty->assign("option_selected_vendedor",$datacliente[0]["cod_vendedor"]);
//CARGAMOS EL COMBO cod_vendedor
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM vendedor");
    foreach ($tprecio as $item) {
        $valueSELECT[] = $item["cod_vendedor"];
        $outputSELECT[] = $item["nombre"];
    }
    $smarty->assign("option_values_vendedor", $valueSELECT);
    $smarty->assign("option_output_vendedor", $outputSELECT);
    $smarty->assign("option_selected_vendedor", $datacliente[0]["cod_vendedor"]);

//CARGAMOS EL COMBO cod_zona
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM zonas");
    foreach ($tprecio as $item) {
        $valueSELECT[] = $item["cod_zona"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_zona", $valueSELECT);
    $smarty->assign("option_output_zona", $outputSELECT);
    $smarty->assign("option_selected_zona", $datacliente[0]["cod_zona"]);



//CARGAMOS EL COMBO COD_TIPO_CLIENTE
    $valueSELECT = "";
    $outputSELECT = "";
    $tcliente = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM tipo_cliente");
    foreach ($tcliente as $item) {
        $valueSELECT[] = $item["cod_tipo_cliente"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_tipo_cliente", $valueSELECT);
    $smarty->assign("option_output_tipo_cliente", $outputSELECT);
    $smarty->assign("option_selected_tipo_cliente", $datacliente[0]["cod_tipo_cliente"]);


//CARGAMOS EL COMBO COD_TIPO_PRECIO
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM tipo_precio");
    foreach ($tprecio as $item) {
        $valueSELECT[] = $item["cod_tipo_precio"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_tipo_precio", $valueSELECT);
    $smarty->assign("option_output_tipo_precio", $outputSELECT);
    $smarty->assign("option_selected_tipo_precio", $datacliente[0]["cod_tipo_precio"]);

//CARGAMOS EL COMBO contribuyente_especial
    $smarty->assign("option_values_contribuyente_especial", array(0, 1));
    $smarty->assign("option_output_contribuyente_especial", array("No", "Si"));
    $smarty->assign("option_selected_contribuyente_especial", $datacliente[0]["contribuyente_especial"]);

//CARGAMOS EL COMBO calc_reten_impuesto_islr
    $smarty->assign("option_values_calc_reten_impuesto_islr", array(0, 1));
    $smarty->assign("option_output_calc_reten_impuesto_islr", array("No", "Si"));
    $smarty->assign("option_selected_calc_reten_impuesto_islr", $datacliente[0]["calc_reten_impuesto_islr"]);

//CARGAMOS EL COMBO calc_reten_impuesto_iva
    $smarty->assign("option_values_calc_reten_impuesto_iva", array(0, 1));
    $smarty->assign("option_output_calc_reten_impuesto_iva", array("No", "Si"));
    $smarty->assign("option_selected_calc_reten_impuesto_iva", $datacliente[0]["calc_reten_impuesto_iva"]);

//CARGAMOS EL COMBO calc_reten_impuesto_1x1000
    $smarty->assign("option_values_calc_reten_impuesto_1x1000", array(0, 1));
    $smarty->assign("option_output_calc_reten_impuesto_1x1000", array("No", "Si"));
    $smarty->assign("option_selected_calc_reten_impuesto_1x1000", $datacliente[0]["calc_reten_impuesto_1x1000"]);


//CARGAMOS EL COMBO permitecredito
    $smarty->assign("option_values_permitecredito", array(0, 1));
    $smarty->assign("option_output_permitecredito", array("No", "Si"));
    $smarty->assign("option_selected_permitecredito", $datacliente[0]["permitecredito"]);

    $smarty->assign("datacliente", $datacliente);

//TIPO DE ENTIDAD
    $valueSELECT = "";
    $outputSELECT = "";
    $tprecio = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM entidades");
    foreach ($tprecio as $item) {
        $valueSELECT[] = $item["cod_entidad"];
        $outputSELECT[] = $item["descripcion"];
    }
    $smarty->assign("option_values_entidad", $valueSELECT);
    $smarty->assign("option_output_entidad", $outputSELECT);
    $smarty->assign("option_selected_entidad", $datacliente[0]["cod_entidad"]);

//CARGAMOS EL COMBO Estado del Cliente
    $smarty->assign("option_values_estado", array(A, B));
    $smarty->assign("option_output_estado", array("Activo", "Bloqueado"));
    $smarty->assign("option_selected_estado", $datacliente[0]["estado"]);


// CONSULTA DE CUENTAS CONTABLES
    $global = new bd(SELECTRA_CONF_PYME);
    $sentencia = "SELECT * FROM nomempresa WHERE bd='" . $_SESSION['EmpresaFacturacion'] . "'";
    $contabilidad = $global->query($sentencia);
    $fila = $contabilidad->fetch_assoc();

    $valueSELECT = "";
    $outputSELECT = "";
    $contabilidad = $clientes->ObtenerFilasBySqlSelect("SELECT * FROM " . $fila['bd_contabilidad'] . ".cwconcue WHERE Tipo='P'");
    foreach ($contabilidad as $cuenta) {
        $valueSELECT[] = $cuenta["Cuenta"];
        $outputSELECT[] = $cuenta["Cuenta"] . " - " . $cuenta["Descrip"];
    }
    $smarty->assign("option_values_cuenta", $valueSELECT);
    $smarty->assign("option_output_cuenta", $outputSELECT);
    $smarty->assign("option_selected_cuenta", $datacliente[0]["cuenta_contable"]);
}
?>
