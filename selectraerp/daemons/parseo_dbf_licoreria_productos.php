<?php

set_time_limit(0);

include("../../menu_sistemas/lib/common.php");

#$proveedores = new Proveedores();
#$$correlativos = new Correlativos();

$conexion = new bd("pyme_administracion");

$db = dbase_open('C:\Users\pc\Downloads\SISADMIX\PRODUCTO.dbf', 0) or die("Error! No se pudo abrir el archivo de base de datos dbase");

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
    $total = $num_registros;
    #Dejo 5 registros sin parsear para enseï¿½ar con ellos al usuari a cargar la data via sistema
    #Aqui comienzo por el registro 2 xq hay un cliente CONTADO que no voy a parsear
    for ($i = 1; $i <= $total; $i++) {
        $fila = dbase_get_record_with_names($db, $i);
        $array['NOMBRE'][$i] = trim($fila['NOMBRE']);
        $array['CODIGO_BARRAS'][$i] = trim(empty($fila['CODPRO']) ? "NULL" : $fila['CODPRO']);
        $TIPO = trim($fila['TIPO']);
        $array['TIPO'][$i] = ($TIPO == "N" || $TIPO = "n") ? 0 : 1;
        $array['COSTO'][$i] = trim($fila['COSTO']);
        $array['PVP'][$i] = trim($fila['PVP']);
        $array['PVP1'][$i] = trim($fila['PVP1']);
        $array['PVP2'][$i] = trim($fila['PVP2']);
        $array['DEPARTA'][$i] = trim($fila['DEPARTA']);
    }
}
dbase_close($db);

#$proveedores->BeginTrans();
$j = 0;
for ($i = 1; $i <= $total; $i++) {
    if (!empty($array['NOMBRE'][$i]) && $array['NOMBRE'][$i] != '?') {
        $nro_cliente = getUltimoCorrelativo("cod_producto", 1, "si");
        $sql = "INSERT INTO item (
            `id_item`, `cod_item`, `codigo_barras`, `descripcion1`,
            `tipo_item`, `ultimo_costo`,
            `precio1`, `precio2`, `precio3`,
            `costo_actual`, `costo_promedio`, `costo_anterior`,
            `iva`, `cod_departamento`, `cod_item_forma`, `estatus`, `usuario_creacion`, `fecha_creacion`
        )
        VALUES (NULL, '" . $nro_cliente . "', '" . $array['CODIGO_BARRAS'][$i] . "', '" . str_replace("'", '', $array['NOMBRE'][$i]) . "',
                '" . $array['TIPO'][$i] . "', '" . $array['COSTO'][$i] . "',
                '" . $array['PVP'][$i] . "', '" . $array['PVP1'][$i] . "', '" . $array['PVP2'][$i] . "',
                '" . $array['COSTO'][$i] . "', '" . $array['COSTO'][$i] . "', '" . $array['COSTO'][$i] . "',
                12, '" . $array['DEPARTA'][$i] . "', 1, 'A', 'asys', CURRENT_TIMESTAMP)";
        $conexion->query($sql);
        $conexion->query("UPDATE correlativos SET contador = '" . $nro_cliente . "' WHERE campo = 'cod_producto'");
        $j++;
    }
    echo ((empty($array['NOMBRE'][$i]) || $array['NOMBRE'][$i] == '?') ? "<h1 style=\"color:red\">" . $i . "</h1>" : "<h1 style=\"color:green\">" . $i . "</h1>"), ": ", $sql, "<br/><hr/><br/>";
}
echo "<h1 style=\"color:green\">REGISTROS INSERTADOS: ", $j, "</h1><br/><h1 style=\"color:red\">REGISTROS NO INSERTADOS: ", ($i - $j), "</h1>";
/*$conexion->query("UPDATE `item` SET cod_departamento=12 WHERE cod_departamento=14");
$conexion->query("UPDATE `item` SET cod_departamento=13 WHERE cod_departamento=15");
$conexion->query("UPDATE `item` SET cod_departamento=14 WHERE cod_departamento=16");
$conexion->query("UPDATE `item` SET cod_departamento=15 WHERE cod_departamento=18");
$conexion->query("UPDATE `item` SET cod_departamento=16 WHERE cod_departamento=12");
$conexion->query("UPDATE `item` SET cod_departamento=17 WHERE cod_departamento=13");
$conexion->query("UPDATE `item` SET cod_departamento=18 WHERE cod_departamento=17");
#$conexion->query("UPDATE `item` SET descripcion1=REPLACE(descripcion1,'¥','N')");
$conexion->query("UPDATE `item` SET `coniva1`=`precio1`,`coniva2`=`precio2`,`coniva3`=`precio3`");*/
?>
