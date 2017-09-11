<?php

set_time_limit(0);

include("../../menu_sistemas/lib/common.php");

#$proveedores = new Proveedores();
#$$correlativos = new Correlativos();

$conexion = new bd("pyme_administracion");

$db = dbase_open('C:\Users\pc\Downloads\SISADMIX\CLIENTES.dbf', 0) or die("Error! No se pudo abrir el archivo de base de datos dbase");

function FormatCorrelativo($formato, $contador) {
    $char = "0";
    $lenCantidadFormato = substr_count($formato, $char);
    $lenContador = strlen($contador);
    $CantidadCharArepetir = $lenCantidadFormato - $lenContador;
    $stringChars = "";
    for ($i = 1; $i <= $CantidadCharArepetir; $i++) {
        $stringChars .= $char;
    }
    $stringChars .= $contador;
    return $stringChars;
}

function getUltimoCorrelativo($campo, $increment = 0, $formatear = "no", $cadena = "") {
    #$conexion = new bd("pyme_administracion");
    $c = "";
    $numero = -1;
    if ($campo == "") {
        return -1;
    }

    switch ($campo) {
        case "cod_factura": $c = "cod_factura";
            break;
        case "cod_cotizacion": $c = "cod_cotizacion";
            break;
        case "cod_pedido": $c = "cod_pedido";
            break;
        case "cod_nota": $c = "cod_nota";
            break;
        case "cod_boleto": $c = "cod_boleto";
            break;
        case "cod_factura_boleto": $c = "cod_factura_boleto";
            break;
        case "cod_producto": $c = "cod_producto";
            break;
        case "cod_servicio": $c = "cod_servicio";
            break;
        case "cod_pago_o_abono": $c = "cod_pago_o_abono";
            break;
        case "cod_codebar": $c = "cod_codebar";
            break;
        case "cod_cliente": $c = "cod_cliente";
            break;
        case "cod_proveedor": $c = "cod_proveedor";
            break;
        case "cod_compra": $c = "cod_compra";
            break;
        case "cod_pago_o_abonoCXP": $c = "cod_pago_o_abonoCXP";
            break;
        case "cod_devolucion": $c = "cod_devolucion";
            break;
        default: $c = -1;
            break;
    }

    $link = mysql_connect('localhost', 'root', 'selectra') or die('No se pudo conectar: ' . mysql_error());
    mysql_select_db('pyme_administracion') or die('No se pudo seleccionar la base de datos');

    $instruccion = "select contador, formato from correlativos where campo = '" . $c . "'";
    #$campo = $conexion->query($instruccion);
    #$formato = $campo[0]["formato"];

    $result = mysql_query($instruccion, $link) or die('Consulta fallida: ' . mysql_error());
    $f = mysql_fetch_assoc($result);
    $formato = $f["formato"];
    if (count($campo) > 0) {

        #$numero = $campo[0]["contador"];
        $numero = $f["contador"];

        if ($increment == 1) {
            $numero += 1;
        }

        if ($formatear == "si") {
            $numero = FormatCorrelativo($formato, $numero);
        } else {
            return $numero;
        }
        return $cadena . $numero;
    }
}

$array = array();
#echo "T1: " . date("h:i:s") . "<br/>";

if ($db) {
    $num_registros = dbase_numrecords($db);
    $total = $num_registros - 5;
    #Dejo 5 registros sin parsear para ense�ar con ellos al usuari a cargar la data via sistema
    #Aqui comienzo por el registro 2 xq hay un cliente CONTADO que no voy a parsear
    for ($i = 2; $i <= $total; $i++) {
        $fila = dbase_get_record_with_names($db, $i);
        $array['EMPRESA'][$i] = trim($fila['EMPRESA']);
        $array['DIRECCION'][$i] = trim($fila['DIRECC']) . ". " . trim($fila['DIRECC1']) . ". " . trim($fila['CIUDAD']) . ", " . trim($fila['ESTADO']);
        $array['TELEFONO'][$i] = trim($fila['TELEFONO']) . ", " . trim($fila['TELEFONO2']);
        $array['NOMBRE'][$i] = trim($fila['NOMBRE']);
        $array['RIF'][$i] = trim($fila['RIF']);
        $array['EMAIL'][$i] = trim($fila['EMAIL']);
        $array['EMPRESA2'][$i] = trim($fila['EMPRESA2']);
        $array['LIMITE'][$i] = trim($fila['LIMITE']);
        $array['DIASCRED'][$i] = trim($fila['DIASCRED']);
    }
}
dbase_close($db);

#$proveedores->BeginTrans();

for ($i = 2; $i <= $total; $i++) {
    if (!empty($array['RIF'][$i])) {
        $nro_cliente = getUltimoCorrelativo("cod_cliente", 1, "si");
        $sql = "INSERT INTO clientes (
        `id_cliente`, `cod_cliente`, `nombre`,
        `direccion`, `telefonos`,
        `permitecredito`, `limite`, `dias`, `tolerancia`,
        `porc_descuento_global`, `calc_reten_impuesto_islr`, `calc_reten_impuesto_iva`,
        `cod_vendedor`, `cod_zona`, `rif`, `contribuyente_especial`, `retenido_por_cliente`,
        `cod_tipo_cliente`, `cod_entidad`, `calc_reten_impuesto_1x1000`,
        `estado`, `cod_mediq`
        )
        VALUES (NULL, '" . $nro_cliente . "', '" . str_replace("'", '', $array['EMPRESA'][$i]) . "',
                '" . $array['DIRECCION'][$i] . "', '" . $array['TELEFONO'][$i] . "',
                0, '" . $array['LIMITE'][$i] . "', '" . $array['DIASCRED'][$i] . "', 0,
                '0', '0', '0',
                1, 1, '" . $array['RIF'][$i] . "', 0, '0',
                1, 1, '0', 'A', 0)";
        #echo $sql, "<br/>";
        $conexion->query($sql);
        #$proveedores->ExecuteTrans($sql);
        $conexion->query("update correlativos set contador = '" . $nro_cliente . "' where campo = 'cod_cliente'");
    }
}
?>