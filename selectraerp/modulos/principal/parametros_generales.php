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
        cta_captadora = '{$_POST["cuenta_captadora"]}',
        cta_sobrante = '{$_POST["cuenta_sobrante"]}',
        unidad_tributaria = '{$_POST["unidad_tributaria"]}',
        nombre_impuesto_principal = '{$_POST["nombre_impuesto_principal"]}',
        porcentaje_impuesto_principal = '{$_POST["porcentaje_impuesto_principal"]}',
        iva_a = '{$_POST["iva_a"]}', iva_b = '{$_POST["iva_b"]}', iva_c = '{$_POST["iva_c"]}',
        string_clasificador_inventario1 = '{$_POST["string_clasificador_inventario1"]}',
        string_clasificador_inventario2 = '{$_POST["string_clasificador_inventario2"]}',
        string_clasificador_inventario3 = '{$_POST["string_clasificador_inventario3"]}',
        tipo_facturacion = '{$_POST["tipo_facturacion"]}',
        swterceroimp = '{$_POST["swterceroimp"]}',
        impresora_marca = '{$_POST["impresora_marca"]}',
        impresora_modelo = '{$_POST["impresora_modelo"]}',
        impresora_serial = '{$_POST["impresora_serial"]}',
        venta_pyme = {$_POST["ubicacion_venta"]},
        servicio_fk = '{$_POST["serv"]}',
        cuenta_credito_fiscal = '{$_POST["cuenta_credito_fiscal"]}',
        cuenta_debito_fiscal = '{$_POST["cuenta_debito_fiscal"]}',
        cuenta_retencion_iva= '{$_POST["cuenta_retencion_iva"]}',
        cuenta_retencion_islr= '{$_POST["cuenta_retencion_islr"]}',
        cuenta_retencion_tf= '{$_POST["cuenta_retencion_tf"]}',
        cuenta_retencion_im= '{$_POST["cuenta_retencion_im"]}',
        cxc= '{$_POST["cuenta_cxc"]}',
        id_ubicacion= '{$_POST["id_ubicacion"]}',
        codigo_siga= '{$_POST["codigo_siga"]}',
        servidor= '{$_POST["servidor"]}',
        sincronizacion_inv= '{$_POST["sincronizacionInv"]}',
        codigo_kardex= '{$_POST["codigokardex"]}',
        max_caja_principal= '{$_POST["max_caja_principal"]}',
        fina_limite_max= '{$_POST["fina_limite_max"]}',
        fortinet= '{$_POST["fortinet"]}',
        cod_almacen= '{$_POST["cod_almacen"]}',
        estado= '{$_POST["estado"]}'";
    $instruccion .= $img_izq != "" ? ", img_izq = '{$img_izq}', img_der = '{$img_izq}' " : ", img_izq = img_izq, img_der = img_der ";
    $instruccion .= "WHERE cod_empresa = '{$_POST["cod_empresa"]}';";

    $parametrosgenerales->Execute2($instruccion);
    $parametrosgenerales->TriggerActualizarStrintTipoPrecio($_POST["titulo_precio1"], codTipoPrecio1);
    $parametrosgenerales->TriggerActualizarStrintTipoPrecio($_POST["titulo_precio2"], codTipoPrecio2);
    $parametrosgenerales->TriggerActualizarStrintTipoPrecio($_POST["titulo_precio3"], codTipoPrecio3);
    
    if($_POST["cod_almacen"]=! ''){
        // $ubicacion = $banco->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE devolucion = '1';");
        // $filas=$banco->getFilas();
        // if($filas==0){
        //     $instruccion = "INSERT INTO `ubicacion` (`descripcion`,`id_almacen`,`devolucion`)
        //     VALUES (
        //     'DEVOLUCION', '".$_POST["cod_almacen"]."','1');";
           
        // }else{

        //   echo  $instruccion = "UPDATE ubicacion  SET id_almacen = '".$alamacen."' WHERE devolucion='1';";
         
        // }
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

$ubica=$banco->ObtenerFilasBySqlSelect("select * from estados_puntos");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["nombre_estado"];
    $arrayubi[] = $item["codigo_estado"];
    
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

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

//cuentas pyme
$cuentas_contables = $banco->ObtenerFilasBySqlSelect("SELECT a.nro_cuenta, a.descripcion as descripcion, b.descripcion as descripcion_banco FROM cuentas_contables as a, banco as b where a.banco=cod_banco");
$valueCUENTAS = "";
$outputCUENTAS = "";
foreach ($cuentas_contables as $cuen) {
    $valueCUENTAS[] = $cuen["nro_cuenta"];
    $outputCUENTAS[] = $cuen["descripcion"]." -Banco:".$cuen["descripcion_banco"];
}
$smarty->assign("option_values_cuentas", $valueCUENTAS);
$smarty->assign("option_output_cuentas", $outputCUENTAS);
$smarty->assign("option_selected_cuentas", $params[0]['cta_captadora']);
$smarty->assign("option_selected_cuentas_sobrantes", $params[0]['cta_sobrante']);

//ubicacion de venta del punto*********************
$ubicacion_venta=array(array("id"=>"1", "nombre"=>"POS"),array("id"=>"0", "nombre"=>"PYME"), array("id" => "2", "nombre" => "AMBAS"));

foreach ($ubicacion_venta as $key1)  {
      $valueVENTAS_TIPO[] = $key1["id"];
    $outputVENTAS_TIPO[] = $key1["nombre"];
}
$smarty->assign("option_values_tipoventas", $valueVENTAS_TIPO);
$smarty->assign("option_output_tipoventas", $outputVENTAS_TIPO);
$smarty->assign("option_selected_tipoventas", $params[0]['venta_pyme']);

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
//cuentas contables
$valueSELECTingresos = "";
$outputSELECTingresos = "";
$valueSELECTiva1 = "";
$outputSELECTiva1 = "";
$valueSELECTiva2 = "";
$outputSELECTiva2 = "";
$valueSELECTiva3 = "";
$outputSELECTiva3 = "";
$valueSELECTotrosingresos = "";
$outputSELECTotrosingresos = "";
$valueSELECTperdida = "";
$outputSELECTperdida = "";
$valueSELECTcxc = "";
$outputSELECTcxc = "";

$cuentas_ppresupuestarias = $banco->ObtenerFilasBySqlSelect("SELECT * FROM cuenta_presupuestaria;");
foreach ($cuentas_ppresupuestarias as $cuentas) 
{
    if($cuentas['tipo']==1)
    {
        $valueSELECTingresos[] = $cuentas["id"];
        $outputSELECTingresos[] = $cuentas["cuenta"]."-".$cuentas["descripcion"];
    }
    elseif ($cuentas['tipo']==2) 
    {
        $valueSELECTiva1[] = $cuentas["id"];
        $outputSELECTiva1[] = $cuentas["cuenta"]."-".$cuentas["descripcion"];
    }
    elseif ($cuentas['tipo']==3) {
        $valueSELECTiva2[] = $cuentas["id"];
        $outputSELECTiva2[] = $cuentas["cuenta"]."-".$cuentas["descripcion"];
    }
    elseif ($cuentas['tipo']==4) {
        $valueSELECTiva3[] = $cuentas["id"];
        $outputSELECTiva3[] = $cuentas["cuenta"]."-".$cuentas["descripcion"];
    }
    elseif ($cuentas['tipo']==5) 
    {
        $valueSELECTotrosingresos[] = $cuentas["id"];
        $outputSELECTotrosingresos[] = $cuentas["cuenta"]."-".$cuentas["descripcion"];
        
    }
    elseif ($cuentas['tipo']==6) {
        $valueSELECTperdida[] = $cuentas["id"];
        $outputSELECTperdida[] = $cuentas["cuenta"]."-".$cuentas["descripcion"];
    }
    else
    {
        $valueSELECTcxc[] = $cuentas["id"];
        $outputSELECTcxc[] = $cuentas["cuenta"]."-".$cuentas["descripcion"];
    }
    
}

$smarty->assign("option_values_cuenta_ingreso", $valueSELECTingresos);
$smarty->assign("option_output_cuenta_ingreso", $outputSELECTingresos);
$smarty->assign("option_selected_cuenta_ingreso",  $params[0]['cuenta_credito_fiscal']);

$smarty->assign("option_values_cuenta_iva1", $valueSELECTiva1);
$smarty->assign("option_output_cuenta_iva1", $outputSELECTiva1);
$smarty->assign("option_selected_cuenta_iva1",  $params[0]['cuenta_debito_fiscal']);

$smarty->assign("option_values_cuenta_iva2", $valueSELECTiva2);
$smarty->assign("option_output_cuenta_iva2", $outputSELECTiva2);
$smarty->assign("option_selected_cuenta_iva2",  $params[0]['cuenta_retencion_iva']);

$smarty->assign("option_values_cuenta_iva3", $valueSELECTiva3);
$smarty->assign("option_output_cuenta_iva3", $outputSELECTiva3);
$smarty->assign("option_selected_cuenta_iva3",  $params[0]['cuenta_retencion_islr']);

$smarty->assign("option_values_cuenta_otrosingresos", $valueSELECTotrosingresos);
$smarty->assign("option_output_cuenta_otrosingresos", $outputSELECTotrosingresos);
$smarty->assign("option_selected_cuenta_otrosingresos",  $params[0]['cuenta_retencion_tf']);

$smarty->assign("option_values_cuenta_perdida", $valueSELECTperdida);
$smarty->assign("option_output_cuenta_perdida", $outputSELECTperdida);
$smarty->assign("option_selected_cuenta_perdida",  $params[0]['cuenta_retencion_im']);

$smarty->assign("option_values_cuenta_cxc", $valueSELECTcxc);
$smarty->assign("option_output_cuenta_cxc", $outputSELECTcxc);
$smarty->assign("option_selected_cuenta_cxc",  $params[0]['cxc']);
//fin de cuentas

/*$smarty->assign("option_selected_cuenta_retencion_im", $params[0]["cuenta_retencion_im"]);
$smarty->assign("option_selected_cuenta_credito_fiscal", $params[0]["cuenta_credito_fiscal"]);
$smarty->assign("option_selected_cuenta_debito_fiscal", $params[0]["cuenta_debito_fiscal"]);
$smarty->assign("option_selected_cuenta_retencion_iva", $params[0]["cuenta_retencion_iva"]);
$smarty->assign("option_selected_cuenta_retencion_islr", $params[0]["cuenta_retencion_islr"]);
$smarty->assign("option_selected_cuenta_retencion_tf", $params[0]["cuenta_retencion_tf"]);*/

$smarty->assign("name_form", "parametros_generales");

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);
?>
