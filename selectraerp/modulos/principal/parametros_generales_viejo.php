<?php

include("../../libs/php/clases/banco.php");
include("../../../menu_sistemas/lib/common.php");
$banco = new Banco();

if (isset($_POST["aceptar"])) {
    if ($_POST["tipo_facturacion"] != "1") {
        $_POST["impresora_marca"] = $_POST["impresora_modelo"] = $_POST["impresora_serial"] = "";
    }
    $impresora_marca = isset($_POST["impresora_marca_spooler"]) ? "{$_POST["impresora_marca"]}:{$_POST["impresora_marca_spooler"]}" : $_POST["impresora_marca"];
    $img_izq = ""; //$_FILES['img_izq']['name'];
    if ($_FILES['img_izq']['error'] == UPLOAD_ERR_OK) {
        $uploadfile = "../../../includes/imagenes/" . basename($_FILES["img_izq"]["name"]);
        /* $_FILES["img_izq"]["name"];
          $_FILES["img_izq"]["type"];
          $_FILES["img_izq"]["tmp_name"];
          $_FILES["img_izq"]["size"]; */
        if (copy($_FILES['img_izq']['tmp_name'], $uploadfile)) {
            chmod($uploadfile, 0777);
        }
        $img_izq = basename($_FILES["img_izq"]["name"]);
    }
    $alamacen=$_POST["cod_almacen"];
     $instruccion = "UPDATE parametros_generales SET
        nombre_empresa = '{$_POST["nombre_empresa"]}',
        direccion = '{$_POST["direccion"]}',
        telefonos = '{$_POST["telefonos"]}',
        id_fiscal = '{$_POST["id_fiscal"]}',
        rif = '{$_POST["rif"]}',
        id_fiscal2 = '{$_POST["id_fiscal2"]}',
        nit = '{$_POST["nit"]}',
        ciudad = '{$_POST["ciudad"]}',
        moneda_base = '{$_POST["moneda_base"]}',
        moneda = '{$_POST["moneda"]}',
        titulo_precio1 = '{$_POST["titulo_precio1"]}',
        titulo_precio2 = '{$_POST["titulo_precio2"]}',
        titulo_precio3 = '{$_POST["titulo_precio3"]}',
        precio_menor = '{$_POST["precio_menor"]}',
        unidad_tributaria = '{$_POST["unidad_tributaria"]}',
        nombre_impuesto_principal = '{$_POST["nombre_impuesto_principal"]}',
        porcentaje_impuesto_principal = '{$_POST["porcentaje_impuesto_principal"]}',
        iva_a = '{$_POST["iva_a"]}', iva_b = '{$_POST["iva_b"]}', iva_c = '{$_POST["iva_c"]}',
        string_clasificador_inventario1 = '{$_POST["string_clasificador_inventario1"]}',
        string_clasificador_inventario2 = '{$_POST["string_clasificador_inventario2"]}',
        string_clasificador_inventario3 = '{$_POST["string_clasificador_inventario3"]}',
        tipo_facturacion = '{$_POST["tipo_facturacion"]}',
        swterceroimp = '{$_POST["swterceroimp"]}',
        impresora_marca = '{$impresora_marca}',
        impresora_modelo = '{$_POST["impresora_modelo"]}',
        impresora_serial = '{$_POST["impresora_serial"]}',
        servicio_fk = '{$_POST["serv"]}',
        cuenta_credito_fiscal = '{$_POST["cuenta_credito_fiscal"]}',
        cuenta_debito_fiscal = '{$_POST["cuenta_debito_fiscal"]}',
        cuenta_retencion_iva= '{$_POST["cuenta_retencion_iva"]}',
        cuenta_retencion_islr= '{$_POST["cuenta_retencion_islr"]}',
        cuenta_retencion_tf= '{$_POST["cuenta_retencion_tf"]}',
        cuenta_retencion_im= '{$_POST["cuenta_retencion_im"]}',
        id_ubicacion= '{$_POST["id_ubicacion"]}',
        codigo_siga= '{$_POST["codigo_siga"]}',
        sincronizacion_inv= '{$_POST["sincronizacionInv"]}',
        cod_almacen= '{$_POST["cod_almacen"]}'";
    $instruccion .= $img_izq != "" ? ", img_izq = '{$img_izq}', img_der = '{$img_izq}' " : ", img_izq = img_izq, img_der = img_der ";
    $instruccion .= "WHERE cod_empresa = '{$_POST["cod_empresa"]}';";

    $parametrosgenerales->Execute2($instruccion);
    $parametrosgenerales->TriggerActualizarStrintTipoPrecio($_POST["titulo_precio1"], codTipoPrecio1);
    $parametrosgenerales->TriggerActualizarStrintTipoPrecio($_POST["titulo_precio2"], codTipoPrecio2);
    $parametrosgenerales->TriggerActualizarStrintTipoPrecio($_POST["titulo_precio3"], codTipoPrecio3);
    
    if($_POST["cod_almacen"]=! ''){
        $ubicacion = $banco->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE devolucion = '1';");
        $filas=$banco->getFilas();
        if($filas==0){
            $instruccion = "INSERT INTO `ubicacion` (`descripcion`,`id_almacen`,`devolucion`)
            VALUES (
            'DEVOLUCION', '".$_POST["cod_almacen"]."','1');";
           
        }else{

          echo  $instruccion = "UPDATE ubicacion  SET id_almacen = '".$alamacen."' WHERE devolucion='1';";
         
        }
    $banco->Execute2($instruccion);
    }

    #$fp = fopen($_FILES['img_izq']['tmp_name'], "rb");
    #$contenido = fread($fp, $_FILES['img_izq']['size']);
    #$contenido = addslashes($contenido);
    #fclose($fp);
    #$parametrosgenerales->Execute2("insert into archivos values(NULL,'{$_FILES['img_izq']['name']}','imagen_x','{$contenido}','{$_FILES['img_izq']['type']}')");

    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&msg=¡Modificacion exitosa!");
}
$campos2 = $banco->ObtenerFilasBySqlSelect("SELECT * FROM divisas, parametros_generales WHERE id_divisa = moneda_base;");
$monedaActual = "<option value='" . $campos2[0]['moneda_base'] . "'> " . $campos2[0]['Nombre'] . "</option>";

$divisas = $banco->ObtenerFilasBySqlSelect("SELECT * FROM divisas;");
$smarty->assign("monedaActual", $monedaActual);
$smarty->assign("divisas", $divisas);

$servicios = $banco->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE cod_item_forma = 2;");
$valueSELECT = "";
$outputSELECT = "";
foreach ($servicios as $serv) {
    $valueSELECT[] = $serv["id_item"];
    $outputSELECT[] = $serv["descripcion1"];
}
$smarty->assign("option_values_servicio", $valueSELECT);
$smarty->assign("option_output_servicio", $outputSELECT);
$smarty->assign("option_selected_servicio", $campos2[0]['servicio_fk']);

$params = $banco->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales;");

$smarty->assign("swterceroimp", $params[0]["swterceroimp"]);
 

$values = array("1", "2", "3");
$outputs = array("Precio 1", "Precio 2", "Precio 3");

$valueSELECT = "";
$outputSELECT = "";
foreach ($values as $vals) {
    $valueSELECT[] = $vals;
}
foreach ($outputs as $vals) {
    $outputSELECT[] = $vals;
}
$smarty->assign("option_values_precio", $valueSELECT);
$smarty->assign("option_output_precio", $outputSELECT);
$smarty->assign("option_selected_precio", $params[0]['precio_menor']);

$values = array("0", "1", "2");
$outputs = array("Sistema (PDF)", "Impresora Fiscal", "Formato Libre");

$valueSELECT = "";
$outputSELECT = "";
foreach ($values as $vals) {
    $valueSELECT[] = $vals;
}
foreach ($outputs as $vals) {
    $outputSELECT[] = $vals;
}
$smarty->assign("option_values_facturacion", $valueSELECT);
$smarty->assign("option_output_facturacion", $outputSELECT);
$smarty->assign("option_selected_facturacion", $params[0]['tipo_facturacion']);

$opciones = array("ninguna" => "Seleccione Marca", "spooler" => "S&uacute;per Spooler Fiscal", "bixolon" => "Bixolon", "hka112" => "HKA112", "dascon" => "Tally Dascon", "hasar" => "Hasar", "vmax" => "Vmax");

$valueSELECT = "";
$outputSELECT = "";
foreach ($opciones as $values => $output) {
    $valueSELECT[] = $values;
    $outputSELECT[] = $output;
}
$select = explode(":", $params[0]['impresora_marca']);
$smarty->assign("option_values_impresora_marca", $valueSELECT);
$smarty->assign("option_output_impresora_marca", $outputSELECT);
$smarty->assign("option_selected_impresora_marca", $select[0]);

// CONSULTA DE CUENTAS CONTABLES
$global = new bd(SELECTRA_CONF_PYME);
$sentencia = "SELECT * FROM nomempresa WHERE bd='{$_SESSION['EmpresaFacturacion']}';";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();

$valueSELECT = "";
$outputSELECT = "";
$contabilidad = $banco->ObtenerFilasBySqlSelect("SELECT * FROM {$fila['bd_contabilidad']}.cwconcue WHERE Tipo='P';");
if(is_array($contabilidad)){
    foreach ($contabilidad as $cuenta) {
        $valueSELECT[] = $cuenta["Cuenta"];
        $outputSELECT[] = $cuenta["Cuenta"] . " - " . $cuenta["Descrip"];
    }
}
$smarty->assign("option_values_cuenta", $valueSELECT);
$smarty->assign("option_output_cuenta", $outputSELECT);

$valueSELECT = "";
$outputSELECT = "";
$valueSELECT[] = "";
$outputSELECT[] = "Seleccione...";
$almacenes = $banco->ObtenerFilasBySqlSelect("SELECT * FROM almacen order by descripcion;");
foreach ($almacenes as $almacen) {
    $valueSELECT[] = $almacen["cod_almacen"];
    $outputSELECT[] = $almacen["descripcion"];
}
$smarty->assign("option_values_almacen", $valueSELECT);
$smarty->assign("option_output_almacen", $outputSELECT);

$smarty->assign("option_selected_almacen",  $params[0]['cod_almacen']);
$valueSELECT = "";
$outputSELECT = "";
$ubicacion = $banco->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion where id = ".$params[0]['id_ubicacion']." ;");
foreach ($ubicacion as $ubicacion1) {
    $valueSELECT[] = $ubicacion1["id"];
    $outputSELECT[] = $ubicacion1["descripcion"];
}
$smarty->assign("option_values_ubicacion", $valueSELECT);
$smarty->assign("option_output_ubicacion", $outputSELECT);
$smarty->assign("option_selected_ubicacion",  $params[0]['id_ubicacion']);


$smarty->assign("option_selected_cuenta_retencion_im", $params[0]["cuenta_retencion_im"]);
$smarty->assign("option_selected_cuenta_credito_fiscal", $params[0]["cuenta_credito_fiscal"]);
$smarty->assign("option_selected_cuenta_debito_fiscal", $params[0]["cuenta_debito_fiscal"]);
$smarty->assign("option_selected_cuenta_retencion_iva", $params[0]["cuenta_retencion_iva"]);
$smarty->assign("option_selected_cuenta_retencion_islr", $params[0]["cuenta_retencion_islr"]);
$smarty->assign("option_selected_cuenta_retencion_tf", $params[0]["cuenta_retencion_tf"]);

$smarty->assign("name_form", "parametros_generales");

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);
?>
