<?php

if (!isset($_SESSION)) {
    session_start();
}
require('fpdfselectra.php');
ob_end_clean();    header("Content-Encoding: None", true);
class PDF extends FPDFSelectra {
    function tHead($dpto, $moneda) {
        $this->SetFont("Arial", "B", 9);
        $this->Cell(0, 7, "RUBRO: " . strtoupper($dpto), 0, 0, 'L');
        $this->Ln();
        $this->Cell(15, 7, utf8_decode('N'), 'LTB', 0, 'C');
        $this->Cell(25, 7, utf8_decode('Codigo Barra'), 'LTB', 0, 'C');
        $this->Cell(100, 7, utf8_decode('Descripcion'), 'LTB', 0, 'C');
        $this->Cell(15, 7, 'Exist.', 'LTB', 0, 'C');
        $this->Cell(10, 7, 'Lote', 'LTB', 0, 'C');
        $this->Cell(20, 7, "Precio S/Iva", 'LTBR', 0, 'C');
        $this->Cell(15, 7, "IVA (%)", 'LTBR', 0, 'C');
        $this->Cell(20, 7, "Precio C/Iva", 'LTBR', 0, 'C');
        $this->Cell(25, 7, "Subtotal", 'LTBR', 0, 'C');
        $this->Cell(25, 7, "Total", 'LTBR', 0, 'C');
        $this->Ln();
    }

    function tHead2($dpto, $moneda) {
        $this->SetFont("Arial", "B", 9);
        $this->Cell(0, 7, "" . strtoupper($dpto), 0, 0, 'L');
        $this->Ln();
        $this->Cell(15, 7, utf8_decode('N'), 'LTB', 0, 'C');
        $this->Cell(25, 7, utf8_decode('Codigo Barra'), 'LTB', 0, 'C');
        $this->Cell(100, 7, utf8_decode('Descripcion'), 'LTB', 0, 'C');
        $this->Cell(15, 7, 'Exist.', 'LTB', 0, 'C');
        $this->Cell(25, 7, "Precio ({$moneda})", 'LTBR', 0, 'C');
        $this->Cell(25, 7, "Subtotal ({$moneda})", 'LTBR', 0, 'C');
        $this->Ln();
    }

    function imprimir_datos(/* $nro_odp, $fila_odp, $moneda, $pdf */$dpto) {

        $cantidad_registros = 40;
        if (($cont + 3) > $cantidad_registros) {
            // $this->Ln(60);
        }

        $conexion = conexion();
        $rs = query("SELECT moneda FROM parametros_generales;", $conexion);
        $fila = fetch_array($rs);
        $this->Ln();
        $this->tHead($dpto["descripcion"], $fila["moneda"]);

        //$rs = query("SELECT * FROM item i, item_existencia_almacen a WHERE i.id_item = a.id_item AND cod_item_forma = 1 AND a.cantidad>0;",$conexion);
        // $rs = query("SELECT * FROM item i, item_existencia_almacen a WHERE i.id_item = a.id_item AND cod_item_forma = 1 AND a.cantidad>0 AND i.cod_departamento = '" . $dpto["cod_departamento"] . "' ORDER BY descripcion1;", $conexion);
       $rs = query("select v.*,i.precio1,  i.codigo_barras, i.iva FROM vw_existenciabyalmacen v, item i where i.id_item=v.id_item AND v.cantidad>0 AND i.cod_departamento = '" . $dpto["cod_departamento"] . "' AND ubicacion!='PISO DE VENTA' 
            GROUP BY i.codigo_barras, ubicacion, lote
            ORDER BY descripcion,ubicacion, v.cod_almacen
        ", $conexion); 

        $totalwhile = num_rows($rs);
        if ($totalwhile == 0) {
            $this->SetFont("Arial", "B", 20);
            $this->SetY(-100);
            $this->Cell(250, 7, 'S I N  E X I S T E N C I A S.', 0, 0, 'C');
        }

        $contar = 1;
        $cantidad_registros = 40;
        $subtotal_dpto = 0;
        $alma="";
        $ubi="";
        while ($totalwhile >= $contar) {
            $conexion = conexion();
            $row_rs = fetch_array($rs);
            $cont2++;
            //$var_snc=$row_rs[4];           

            if ($alma != $row_rs['descripcion'])  {   
                 $this->SetFont("Arial", "B", 9);
                 $this->SetTextColor(255,0,0);
                 $this->Cell(0, 7, "Almacen: " . strtoupper( utf8_decode($row_rs['descripcion'])), 0, 1, 'L');                
                 $alma=$row_rs['descripcion'];  
            }
              $this->SetTextColor(0,0,0);
            if ($ubi != $row_rs['ubicacion'])  {   
                  $this->SetFont("Arial", "B", 9);
                 $this->Cell(0, 7, "Ubicacion: " . strtoupper(utf8_decode($row_rs['ubicacion'])), 0, 1, 'L');                
                 $ubi=$row_rs['ubicacion'];  
           }
            $preciosiva=$row_rs['precio1'];
            $preciociva=$row_rs['precio1']+$row_rs['precio1']*($row_rs['iva']/100);
            $var_codigo = $row_rs['codigo_barras'];
            $var_descrip = utf8_decode($row_rs['descripcion1']);
            $var_exi = number_format($row_rs['cantidad'], 2, ',', '.');
            $lote = $row_rs['lote'];
            $var_preciosiva = number_format($preciosiva, 2, ',', '.');
            $var_preciociva = number_format($preciociva, 2, ',', '.');
            $precio_sub = $row_rs['cantidad'] * $preciosiva;
            $precio_total = $row_rs['cantidad'] * $preciociva;
            $var_precio_sub = number_format($precio_sub, 2, ',', '.');
            $var_precio_total = number_format($precio_total, 2, ',', '.');
            $iva=number_format($row_rs['iva'], 0, ',', '.');
            $subtotal_dpto += $precio_sub;
            $contador++;

            $this->SetFont("Arial", "I", 9);
            // llamado para hacer multilinea sin que haga salto de linea
            $this->SetWidths(array(15,25, 100,15, 10, 20, 15, 20,25,25));
            $this->SetAligns(array('C','C', 'L', 'R', 'R', 'R','R', 'R','R','R'));
            $this->Setceldas(array(0,0, 0, 0, 0, 0));
            $this->Setancho(array(5,5, 5, 5, 5, 5, 5, 5,5,5));
            $this->Row(array($contador,$var_codigo, $var_descrip, $var_exi, $lote,$var_preciosiva, $iva."%", $var_preciociva, $var_precio_sub,$var_precio_total));

            if ($cont == $cantidad_registros) {
                // $this->Ln(80);
                // $this->tHead($dpto["descripcion"], $fila["moneda"]);
                // $cont = 1;
            } else {
                // $cont++;
            }
            $contar++;
        }//fin del while
        $this->SetFont("Arial", "B", 9);
        //$this->Ln();
        //$this->Cell(60, 7, "TOTAL DEPARTAMENTO: " . number_format($subtotal_dpto, 2, ',', '.'), 0, 0, 'R');
        $this->SetWidths(array(178));
        $this->SetAligns(array('R'));
        $this->Setceldas(array(0));
        $this->Setancho(array(5));
        $this->Row(array("SUBTOTAL RUBRO: Bs. " . number_format($subtotal_dpto, 2, ',', '.')));

        return $subtotal_dpto;
    }

    function imprimir_datos2(/* $nro_odp, $fila_odp, $moneda, $pdf */$dpto) {

        $cantidad_registros = 40;
        if (($cont + 3) > $cantidad_registros) {
            // $this->Ln(60);
        }

        $conexion = conexion();
        $rs = query("SELECT moneda FROM parametros_generales;", $conexion);
        $fila = fetch_array($rs);
        $this->Ln();
        $this->tHead2('Pedidos', $fila["moneda"]);

        //$rs = query("SELECT * FROM item i, item_existencia_almacen a WHERE i.id_item = a.id_item AND cod_item_forma = 1 AND a.cantidad>0;",$conexion);
        // $rs = query("SELECT * FROM item i, item_existencia_almacen a WHERE i.id_item = a.id_item AND cod_item_forma = 1 AND a.cantidad>0 AND i.cod_departamento = '" . $dpto["cod_departamento"] . "' ORDER BY descripcion1;", $conexion);
       $rs = query("
            SELECT 1 AS cod_almacen, 1 AS id_ubicacion, it.id_item, it.cod_item, it.descripcion1 AS descripcion1, SUM( de.cantidad ) AS cantidad, 'Pedido' as descripcion, 'Pedido' as Ubicacion, it.coniva1, it.codigo_barras
            FROM kardex_almacen d, item it, kardex_almacen_detalle de
            WHERE it.id_item = de.id_item
            AND d.id_transaccion=de.id_transaccion
            AND d.estado = 'Pendiente'
            and d.observacion<>'Pedido Anulado'
            GROUP BY it.codigo_barras
            ORDER BY descripcion,ubicacion desc
        ", $conexion); 

        $totalwhile = num_rows($rs);
        if ($totalwhile == 0) {
            $this->SetFont("Arial", "B", 20);
            $this->SetY(-100);
            $this->Cell(250, 7, 'S I N  E X I S T E N C I A S.', 0, 0, 'C');
        }

        $contar = 1;
        $cantidad_registros = 40;
        $subtotal_dpto = 0;
        $alma="";
        $ubi="";
        while ($totalwhile >= $contar) {
            $conexion = conexion();
            $row_rs = fetch_array($rs);
            $cont2++;
            //$var_snc=$row_rs[4];           

            if ($alma != $row_rs['descripcion'])  {   
                 $this->SetFont("Arial", "B", 9);
                 $this->SetTextColor(255,0,0);
                 $this->Cell(0, 7, "Almacen: " . strtoupper( utf8_decode($row_rs['descripcion'])), 0, 1, 'L');                
                 $alma=$row_rs['descripcion'];  
            }
              $this->SetTextColor(0,0,0);
            if ($ubi != $row_rs['ubicacion'])  {   
                  $this->SetFont("Arial", "B", 9);
                 $this->Cell(0, 7, "Ubicacion: " . strtoupper(utf8_decode($row_rs['ubicacion'])), 0, 1, 'L');                
                 $ubi=$row_rs['ubicacion'];  
           }
                    
            $var_codigo = $row_rs['codigo_barras'];
            $var_descrip = utf8_decode($row_rs['descripcion1']);
            $var_exi = number_format($row_rs['cantidad'], 2, ',', '.');
            $var_precio = number_format($row_rs['coniva1'], 2, ',', '.');
            $precio_sub = $row_rs['cantidad'] * $row_rs['coniva1'];
            $var_precio_sub = number_format($precio_sub, 2, ',', '.');
            $subtotal_dpto += $precio_sub;
            $contador++;

            $this->SetFont("Arial", "I", 9);
            // llamado para hacer multilinea sin que haga salto de linea
            $this->SetWidths(array(15,25, 100, 15, 15, 15, 25, 25));
            $this->SetAligns(array('C','C', 'L', 'R', 'R', 'R'));
            $this->Setceldas(array(0,0, 0, 0, 0, 0));
            $this->Setancho(array(5,5, 5, 5, 5, 5, 5, 5));
            $this->Row(array($contador,$var_codigo, $var_descrip, $var_exi, $var_precio, $var_precio_sub));

            if ($cont == $cantidad_registros) {
                // $this->Ln(80);
                // $this->tHead($dpto["descripcion"], $fila["moneda"]);
                // $cont = 1;
            } else {
                // $cont++;
            }
            $contar++;
        }//fin del while
        $this->SetFont("Arial", "B", 9);
        //$this->Ln();
        //$this->Cell(60, 7, "TOTAL DEPARTAMENTO: " . number_format($subtotal_dpto, 2, ',', '.'), 0, 0, 'R');
        $this->SetWidths(array(178));
        $this->SetAligns(array('R'));
        $this->Setceldas(array(0));
        $this->Setancho(array(5));
        $this->Row(array("SUBTOTAL RUBRO: Bs. " . number_format($subtotal_dpto, 2, ',', '.')));

        return $subtotal_dpto;
    }


    //Pie de página
    function Footer() {
        //Posición: a  cm del final
        $this->SetY(-15);
        // fin
        $this->SetFont('Arial', 'I', 8);
        //Número de página
        $this->Cell(188, 5, utf8_decode('Pagina ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        //$this->SetFont('Arial', 'I', 8);
        $this->Ln();
        //$this->Cell(188,5,'Elaborado Por: '.$valor['usuario'],0,0,'L');
        //Número de página
        // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

}

//Creación del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'A4');

$pdf->setTituloReporte('L I S T A D O  D E  E X I S T E N C I A S');
$pdf->AliasNbPages();
//$pdf->SetFont('Times', '', 12);

$conexion = conexion();

$tabla = "item";
$consulta = "SELECT * FROM item WHERE cod_item_forma = 1;";
$resultado = query($consulta, $conexion);
$codigo_snc = $_GET['codigo_snc'];

$sql = "SELECT * FROM departamentos;";
$rs = query($sql, $conexion);

$lista_dptos = array();
$subtotal_dptos = array();

while ($fila = fetch_array($rs)) {
    $pdf->AddPage();
    $subtotal_dptos[] = $pdf->imprimir_datos(/* $nro_odp, $fila_odp, $moneda, $pdf */$fila);
    $lista_dptos[] = $fila["descripcion"];
    $fila = "";
}


    $pdf->AddPage();
    $subtotal_dptos[] = $pdf->imprimir_datos2(/* $nro_odp, $fila_odp, $moneda, $pdf */$fila);
    $lista_dptos[] = 'Pedidos';
    $fila = "";

$pdf->AddPage();
$pdf->SetFont("Arial", "B", 9);
$pdf->Cell(150, 7, "RUBRO", "LTB", 0, 'C');
$pdf->Cell(28, 7, "SUBTOTAL", "LTBR", 1, 'C');
$pdf->SetFont("Arial", "I", 9);

$total = 0;
foreach ($lista_dptos as $key => $dpto) {
    /* $pdf->SetWidths(array(150, 28));
      $pdf->SetAligns(array('L', 'R'));
      $pdf->Setceldas(array(0, 0));
      $pdf->Setancho(array(5, 5));
      $pdf->Row(array($dpto, number_format($subtotal_dptos[$key], 2, ',', '.'))); */
    $pdf->Cell(150, 7, $dpto, "RBL", 0, 'L');
    $pdf->Cell(28, 7, number_format($subtotal_dptos[$key], 2, ',', '.'), "RB", 1, 'R');
    $total += $subtotal_dptos[$key];
}
$pdf->SetFont("Arial", "B", 9);
$pdf->Cell(150, 7, "TOTAL EXISTENCIA", "LTB", 0, 'R');
$pdf->Cell(28, 7, "" . number_format($total, 2, ',', '.'), "LTBR", 1, 'R');
ob_end_clean();
$pdf->Output();
?>
