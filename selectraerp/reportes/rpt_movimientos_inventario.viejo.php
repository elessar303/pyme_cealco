<?php

if (!isset($_SESSION)) {
    session_start();
}

require('../fpdf/fpdfselectra.php');

class PDF extends FPDFSelectra {

    function tHead() {
        $this->SetFont("Arial", "I", 8);
        $this->Cell(65, 5, '', 'LTB', 0, 'C');
        $this->Cell(15, 5, 'Costo', 'LTR', 0, 'C');
        $this->Cell(25, 5, 'Inicial', 'LTBR', 0, 'C');
        $this->Cell(25, 5, 'Entradas', 'LTB', 0, 'C');
        $this->Cell(25, 5, 'Salidas', 'LTB', 0, 'C');
        $this->Cell(25, 5, 'Final', 'LTBR', 0, 'C');
        $this->Ln();
        $this->Cell(15, 5, utf8_decode('Código'), 'LTB', 0, 'C');
        $this->Cell(50, 5, utf8_decode('Descripción'), 'LTB', 0, 'C');
        $this->Cell(15, 5, 'Unitario', 'LBR', 0, 'C');
        $this->Cell(10, 5, 'Cant.', 'LTB', 0, 'C');
        $this->Cell(15, 5, 'Bs', 'LTB', 0, 'C');
        $this->Cell(10, 5, 'Cant.', 'LTB', 0, 'C');
        $this->Cell(15, 5, 'Bs', 'LTB', 0, 'C');
        $this->Cell(10, 5, 'Cant.', 'LTB', 0, 'C');
        $this->Cell(15, 5, 'Bs', 'LTB', 0, 'C');
        $this->Cell(10, 5, 'Cant.', 'LTB', 0, 'C');
        $this->Cell(15, 5, 'Bs', '1LTB', 0, 'C');
        $this->Ln();
    }

    function imprimir_datos($nro_odp, $fila_odp, $moneda, $pdf) {
        global $fecha;
        $cantidad_registros = 35;
        if (($cont + 3) > $cantidad_registros) {
            $this->Ln(60);
        }
        $this->tHead();
        $conexion = conexion();
        #$rs = query("SELECT * FROM item i INNER JOIN kardex_almacen_detalle kd ON i.id_item = kd.id_item INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion WHERE k.fecha_ejecucion<=$fecha",$conexion);
        #$rs = query("SELECT * FROM item i INNER JOIN kardex_almacen_detalle kd ON i.id_item = kd.id_item INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion WHERE i.precio1 > 0 AND month(k.fecha_ejecucion) = month('" . $fecha . "') AND year(k.fecha_ejecucion) = year('" . $fecha . "') ORDER BY i.id_item", $conexion);
        $rsd = query("SELECT DISTINCT kd.id_item FROM kardex_almacen_detalle kd INNER JOIN kardex_almacen k ON k.id_transaccion=kd.id_transaccion WHERE k.tipo_movimiento_almacen = 2 AND month(k.fecha_ejecucion) = month('" . $fecha . "') AND year(k.fecha_ejecucion) = year('" . $fecha . "') ORDER BY kd.id_item", $conexion);

        $i = 0;
        while ($fila = fetch_array($rsd)) {
            $datos = "";
            $rsc = query("SELECT SUM(kd.cantidad) AS salidas FROM item i INNER JOIN kardex_almacen_detalle kd ON i.id_item = kd.id_item INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion WHERE tipo_movimiento_almacen = 2 AND month(k.fecha_ejecucion) = month('" . $fecha . "') AND year(k.fecha_ejecucion) = year('" . $fecha . "') AND kd.id_item = '" . $fila['id_item'] . "'", $conexion);
            $datos = fetch_array($rsc);
            $cantidades_item_venta[$i] = $datos['salidas'];
            $id_item[$i] = $fila['id_item'];
            #echo $id_item[$i]." - ".$cantidades_item_venta[$i]."<br>".
            $fila = "";
            $i++;
        }
        #echo "registros: ".$i;exit;
        #$rsi = query("SELECT * FROM item i",$conexion);

        $totalwhile = num_rows($rsd);
        if ($totalwhile == 0) {
            #$this->SetY(-75);
            $this->Cell(188, 7, 'No hay materiales', 0, 0, 'C');
        }

        $contar = 1;
        $i = 0;
        $cantidad_registros = 35;
        while ($totalwhile >= $contar) {
            $conexion = conexion();
            $sql = "SELECT * FROM item i 
                INNER JOIN kardex_almacen_detalle kd ON i.id_item = kd.id_item 
                INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion 
                WHERE month(k.fecha_ejecucion) = month('" . $fecha . "') AND year(k.fecha_ejecucion) = year('" . $fecha . "') AND kd.id_item = '" . $id_item[$i] . "'";
            $rs = query($sql, $conexion);
            $row_rs = fetch_array($rs);
            $cont2 = $cont2 + 1;
            #$var_snc=$row_rs[4];

            $var_codigo = $row_rs['cod_item'];
            $var_descrip = utf8_decode($row_rs[2]);
            $var_precio = $row_rs['precio1'];

            $fecha_mes_anterior = new DateTime($fecha);
            $fecha_mes_anterior->modify('-1 month');
#SUM(kd.cantidad) AS cantidad
            $sql_salidas = "SELECT SUM(kd.cantidad) AS cantidad FROM kardex_almacen_detalle kd 
                INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion 
                WHERE kd.id_item = '" . $row_rs['id_item'] . "' AND k.tipo_movimiento_almacen = '2' AND month(k.fecha_ejecucion) = '" . $fecha_mes_anterior->format("m") . "' AND year(k.fecha_ejecucion) = '" . $fecha_mes_anterior->format("Y") . "' AND kd.id_item = '" . $id_item[$i] . "'";
            $rs_salidas = query($sql_salidas, $conexion);
            #$salidas = 0;
            $row_rs_salidas = fetch_array($rs_salidas);
            $salidas = $row_rs_salidas["cantidad"];
            #}

            $sql_entradas = "SELECT SUM(kd.cantidad) AS cantidad FROM kardex_almacen_detalle kd 
                INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion 
                WHERE kd.id_item = '" . $row_rs['id_item'] . "' AND (k.tipo_movimiento_almacen = '1' OR k.tipo_movimiento_almacen = '3') AND month(k.fecha_ejecucion) = '" . $fecha_mes_anterior->format("m") . "' AND year(k.fecha_ejecucion) = '" . $fecha_mes_anterior->format("Y") . "' AND kd.id_item = '" . $id_item[$i] . "'";
            $rs_entradas = query($sql_entradas, $conexion);
            $row_rs_entradas = fetch_array($rs_entradas);
            $entradas = $row_rs_entradas["cantidad"];

            $inicial = abs($entradas - $salidas);

            $contador++;

            $this->SetFont("Arial", "I", 6);
            // llamado para hacer multilinea sin que haga salto de linea
            $this->SetWidths(array(15, 50, 15, 10, 15, 10, 15, 10, 15, 10, 15));
            $this->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
            $this->Setceldas(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
            $this->Setancho(array(5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5));
            $this->Row(array($var_codigo, $var_descrip, number_format($var_precio, 2, ',', '.'), $inicial, number_format($inicial * $var_precio, 2, ',', '.'), $row_rs['cantidad'], number_format($row_rs['cantidad'] * $var_precio, 2, ',', '.'), $cantidades_item_venta[$i], number_format($cantidades_item_venta[$i] * $var_precio, 2, ',', '.'), ($row_rs['cantidad'] - $cantidades_item_venta[$i]), number_format(($row_rs['cantidad'] - $cantidades_item_venta[$i]) * $var_precio, 2, ',', '.')), 1);

            if ($cont == $cantidad_registros) {
                $this->Ln(70);
                //$this->Cell(24,7,$string,'LTB',0,'C');
                $this->tHead();
                $cont = 1;
            } else {
                $cont++;
            }
            $contar++;
            $i++;
        }//fin del while
    }

}

$fecha = $_GET['fecha'];

$array_fecha = explode("-", $fecha);

$pdf = new PDF();

$pdf->setTituloReporte('MOVIMIENTOS DE INVENTARIO AL ' . $array_fecha[2] . " DE " . strtoupper(mesaletras($array_fecha[1])) . " DE " . $array_fecha[0]);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

$conexion = conexion();

$tabla = "item";
$consulta = "select * from item where cod_item_forma=1";
$resultado = query($consulta, $conexion);
$codigo_snc = $_GET['codigo_snc'];

$Conn = conexion_conf();

$var_sql = "select moneda,periodo from parametros";
$rsu = query($var_sql, $Conn);
$row_rsu = fetch_array($rsu);
$moneda = $row_rsu['moneda'];
$periodo = $row_rsu['periodo'];
cerrar_conexion($Conn);

$pdf->imprimir_datos($nro_odp, $fila_odp, $moneda, $pdf);
$pdf->Output();
?>
