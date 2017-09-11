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
        #global $fecha;
        $fecha = $_GET['fecha'];
        $cantidad_registros = 35;
        $this->tHead();
        $conexion = conexion();
#$rs = query("SELECT * FROM item i INNER JOIN kardex_almacen_detalle kd ON i.id_item = kd.id_item INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion WHERE k.fecha_ejecucion<=$fecha",$conexion);
#$rs = query("SELECT * FROM item i INNER JOIN kardex_almacen_detalle kd ON i.id_item = kd.id_item INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion WHERE i.precio1 > 0 AND MONTH(k.fecha_ejecucion) = MONTH('{$fecha}') AND YEAR(k.fecha_ejecucion) = YEAR('{$fecha}') ORDER BY i.id_item", $conexion);
#$rsd = query("SELECT DISTINCT kd.id_item FROM kardex_almacen_detalle kd INNER JOIN kardex_almacen k ON k.id_transaccion=kd.id_transaccion WHERE MONTH(k.fecha_ejecucion) = MONTH('{$fecha}') AND YEAR(k.fecha_ejecucion) = YEAR('{$fecha}') ORDER BY kd.id_item", $conexion);
#$rsd = query("SELECT DISTINCT kd.id_item FROM kardex_almacen_detalle kd INNER JOIN kardex_almacen k ON k.id_transaccion=kd.id_transaccion WHERE k.tipo_movimiento_almacen = 2 AND MONTH(k.fecha_ejecucion) = MONTH('{$fecha}') AND YEAR(k.fecha_ejecucion) = YEAR('{$fecha}') ORDER BY kd.id_item", $conexion);
        $sql = "SELECT DISTINCT id_item
                FROM kardex_almacen_detalle
                WHERE id_item IN (
                    SELECT id_item
                    FROM kardex_almacen_detalle kd
                        INNER JOIN kardex_almacen k ON k.id_transaccion = kd.id_transaccion
                        WHERE tipo_movimiento_almacen =1
                        AND estado LIKE 'Entregado'
                        AND MONTH( k.fecha_ejecucion ) = MONTH( '{$fecha}' )
                        AND YEAR( k.fecha_ejecucion ) = YEAR( '{$fecha}' ))
                    OR id_item IN (
                    SELECT id_item
                    FROM kardex_almacen_detalle kd
                        INNER JOIN kardex_almacen k ON k.id_transaccion = kd.id_transaccion
                        WHERE tipo_movimiento_almacen =2
                        AND estado LIKE 'Entregado'
                        AND MONTH( k.fecha_ejecucion ) = MONTH( '{$fecha}' )
                        AND YEAR( k.fecha_ejecucion ) = YEAR( '{$fecha}' ))
                ORDER BY id_item;";
        $rsd = query($sql, $conexion);
        $i = 0;
        while ($fila = fetch_array($rsd)) {
            $datos_v = $datos_c = "";
#Obtener la cantidad vendida de los productos con movimiento de inventario en el mes
            $sqlv = "SELECT SUM(kd.cantidad) AS salidas FROM item i
                    INNER JOIN kardex_almacen_detalle kd ON i.id_item = kd.id_item
                    INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion
                    WHERE tipo_movimiento_almacen = 2
                    AND MONTH(k.fecha_ejecucion) = MONTH('{$fecha}')
                        AND YEAR(k.fecha_ejecucion) = YEAR('{$fecha}')
                            AND kd.id_item = '{$fila['id_item']}';";
            $rsv = query($sqlv, $conexion);
            $datos_v = fetch_array($rsv);
            $cantidades_item_venta[$i] = $datos_v['salidas'];
#Obtener la cantidad de los productos comprados con movimiento de inventario en el mes
            $sqlc = "SELECT SUM(kd.cantidad) AS entradas FROM item i
                    INNER JOIN kardex_almacen_detalle kd ON i.id_item = kd.id_item
                    INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion
                    WHERE tipo_movimiento_almacen = 1
                    AND estado LIKE 'Entregado'
                    AND MONTH(k.fecha_ejecucion) = MONTH('{$fecha}')
                        AND YEAR(k.fecha_ejecucion) = YEAR('{$fecha}')
                            AND kd.id_item = '" . $fila['id_item'] . "'";
            $rsc = query($sqlc, $conexion);
            $datos_c = fetch_array($rsc);
            $cantidades_item_compra[$i] = $datos_c['entradas'];
#echo $i.":".$datos_v['salidas']."/".$datos_c['entradas']."<br>";
            $id_item[$i] = $fila['id_item'];
#echo $id_item[$i]." : ".($cantidades_item_venta[$i]?$cantidades_item_venta[$i]:"0")." - ".($cantidades_item_compra[$i]?$cantidades_item_compra[$i]:"0")."<br>".
            $fila = "";
            $i++;
        }

        $totalwhile = num_rows($rsd);

        if ($totalwhile == 0) {
            $this->SetY(-150);
            $this->Cell(188, 7, 'No existen movimientos de inventario para el mes solicitado.', 0, 0, 'C');
        } else {
            $contar = 1;
            $i = 0;
            $cantidad_registros = 35;
            while ($totalwhile >= $contar) {
                $conexion = conexion();
                /* $sql = "SELECT * FROM item i
                  INNER JOIN kardex_almacen_detalle kd ON i.id_item = kd.id_item
                  INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion
                  WHERE MONTH(k.fecha_ejecucion) = MONTH('{$fecha}')
                  AND YEAR(k.fecha_ejecucion) = YEAR('{$fecha}')
                  AND kd.id_item = '" . $id_item[$i] . "'"; */
                $sql = "SELECT * FROM item WHERE id_item = {$id_item[$i]};";
                $rs = query($sql, $conexion);
                $row_rs = fetch_array($rs);
                $cont2 = $cont2 + 1;
#$var_snc=$row_rs[4];

                $var_codigo = $row_rs['cod_item'];
                $var_descrip = $row_rs['descripcion1']; #$row_rs[2];
                $var_precio = $row_rs['precio1'];

                $fecha_mes_anterior = new DateTime($fecha);

                $salidas_mes_anterior = $entradas_mes_anterior = 0;

                do {
                    #$fecha_mes_anterior->modify('-1 month');
                    $this->myModify($fecha_mes_anterior, -1);
                    #echo $fecha."-".$fecha_mes_anterior->format("Y-m-d")."<br>";
                    $sql_salidas = "SELECT SUM(kd.cantidad) AS cantidad FROM kardex_almacen_detalle kd
                        INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion
                        WHERE kd.id_item = '{$id_item[$i]}'
                            AND (k.tipo_movimiento_almacen = 2 OR k.tipo_movimiento_almacen = 4)
                            AND MONTH(k.fecha_ejecucion) = '" . $fecha_mes_anterior->format("m") . "'
                                AND YEAR(k.fecha_ejecucion) = '" . $fecha_mes_anterior->format("Y") . "'";
                    $rs_salidas = query($sql_salidas, $conexion);
                    #$salidas = 0;
                    $row_rs_salidas = fetch_array($rs_salidas);
                    #echo "<br>salidas ".$fecha_mes_anterior->format("Y-m-d") .": ".
                    $salidas_mes_anterior += $row_rs_salidas["cantidad"] ? $row_rs_salidas["cantidad"] : 0;

                    $sql_entradas = "SELECT SUM(kd.cantidad) AS cantidad FROM kardex_almacen_detalle kd
                        INNER JOIN kardex_almacen k ON kd.id_transaccion = k.id_transaccion
                        WHERE kd.id_item = '" . $id_item[$i] . "'
                            AND (k.tipo_movimiento_almacen = 1 OR k.tipo_movimiento_almacen = 3)
                            AND MONTH(k.fecha_ejecucion) = '" . $fecha_mes_anterior->format("m") . "'
                                AND YEAR(k.fecha_ejecucion) = '" . $fecha_mes_anterior->format("Y") . "'";
                    $rs_entradas = query($sql_entradas, $conexion);
                    $row_rs_entradas = fetch_array($rs_entradas);
                    #echo "<br>entradas ".$fecha_mes_anterior->format("Y-m-d") .": ";
                    $entradas_mes_anterior += $row_rs_entradas["cantidad"] ? $row_rs_entradas["cantidad"] : 0;
                } while ($fecha_mes_anterior->format('m') != 1);
                #echo "<br>inicial $fecha: ".
                $inicial = abs($entradas_mes_anterior - $salidas_mes_anterior);
                #exit;
                $contador++;

                $entradas = $cantidades_item_compra[$i];
                $salidas = $cantidades_item_venta[$i];
                $final = $inicial + $entradas - $salidas;
                $this->SetFont("Arial", "I", 6);
                #llamado para hacer multilinea sin que haga salto de linea
                $this->SetWidths(array(15, 50, 15, 10, 15, 10, 15, 10, 15, 10, 15));
                $this->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
                $this->Setceldas(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
                $this->Setancho(array(5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5));
                $this->Row(
                        array(
                    $var_codigo,
                    utf8_decode($var_descrip),
                    number_format($var_precio, 2, ',', '.'),
                    $inicial != "" ? $inicial : 0, number_format($inicial * $var_precio, 2, ',', '.'), #$row_rs['cantidad'], number_format($row_rs['cantidad'] * $var_precio, 2, ',', '.'),
                    $entradas != "" ? $entradas : 0, number_format($entradas * $var_precio, 2, ',', '.'),
                    $salidas != "" ? $salidas : 0, number_format($salidas * $var_precio, 2, ',', '.'),
                    $final != "" ? $final : 0, number_format($final * $var_precio, 2, ',', '.')), 1);

                if ($cont == $cantidad_registros) {
                    $this->Ln(70);
                    #$this->Cell(24,7,$string,'LTB',0,'C');
                    $this->tHead();
                    $cont = 1;
                } else {
                    $cont++;
                }
                $contar++;
                $i++;
            }
        }
    }

    function myModify($date, $months) {

        $init = clone $date;
        $modifier = $months . ' months';
        $back_modifier = -$months . ' months';

        $date->modify($modifier);
        $back_to_init = clone $date;
        $back_to_init->modify($back_modifier);

        while ($init->format('m') != $back_to_init->format('m')) {
            $date->modify('-1 day');
            $back_to_init = clone $date;
            $back_to_init->modify($back_modifier);
        }
    }

}

$fecha = new DateTime($_GET['fecha']);
#echo mesaletras($fecha->format('m')). " DE " . $fecha->format('Y');
#$array_fecha = explode("-", $fecha);

$pdf = new PDF();

$pdf->setTituloReporte('MOVIMIENTOS DE INVENTARIO - ' . strtoupper($fecha->format('M')) . " DE " . $fecha->format('Y'));
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

$conexion = conexion();

$tabla = "item";
$consulta = "select * from item where cod_item_forma=1";
$resultado = query($consulta, $conexion);
$codigo_snc = $_GET['codigo_snc'];

$Conn = conexion_conf();

$var_sql = "select moneda,periodo from parametros;";
$rsu = query($var_sql, $Conn);
$row_rsu = fetch_array($rsu);
$moneda = $row_rsu['moneda'];
$periodo = $row_rsu['periodo'];
cerrar_conexion($Conn);

$pdf->imprimir_datos($nro_odp, $fila_odp, $moneda, $pdf);
$pdf->Output();
?>
