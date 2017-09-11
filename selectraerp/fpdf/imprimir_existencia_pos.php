<?php

if (!isset($_SESSION)) {
    session_start();
}
require('fpdfselectra.php');
class PDF extends FPDFSelectra {
    function tHead($dpto, $moneda) {
        $this->SetFont("Arial", "B", 9);
        $this->Cell(0, 7, "RUBRO: " . strtoupper($dpto), 0, 0, 'L');
        $this->Ln();
        $this->Cell(15, 7, utf8_decode('Codigo'), 'LTB', 0, 'C');
        $this->Cell(82, 7, utf8_decode('Descripcion'), 'LTB', 0, 'C');
        // $this->Cell(15, 7, utf8_decode('Min.'), 'LTB', 0, 'C');
        // $this->Cell(15, 7, utf8_decode('Max.'), 'LTBR', 0, 'C');
        $this->Cell(15, 7, 'Exist.', 'LTB', 0, 'C');
        // $this->Cell(25, 7, "Precio ({$moneda})", 'LTBR', 0, 'C');
        // $this->Cell(25, 7, "Subtotal ({$moneda})", 'LTBR', 0, 'C');
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
        $this->tHead($dpto["descripcion"], $fila["moneda"]);
        $pos=POS;
        //$rs = query("SELECT * FROM item i, item_existencia_almacen a WHERE i.id_item = a.id_item AND cod_item_forma = 1 AND a.cantidad>0;",$conexion);
        // $rs = query("SELECT * FROM item i, item_existencia_almacen a WHERE i.id_item = a.id_item AND cod_item_forma = 1 AND a.cantidad>0 AND i.cod_departamento = '" . $dpto["cod_departamento"] . "' ORDER BY descripcion1;", $conexion);
       $rs = query("SELECT * FROM $pos.stockcurrent AS a, $pos.products AS b WHERE a.product = b.id AND a.units <>0", $conexion); 

        $totalwhile = num_rows($rs);
        if ($totalwhile == 0) {
            $this->SetFont("Arial", "B", 20);
            $this->SetY(-150);
            $this->Cell(188, 7, 'S I N  E X I S T E N C I A S.', 0, 0, 'C');
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

          
                    
            $var_codigo = $row_rs['cod_item'];
            $var_descrip = utf8_decode($row_rs['NAME']);
            $var_exi = number_format($row_rs['UNITS'], 0, ',', '.');
            // $var_min = number_format($row_rs['existencia_min'], 0, ',', '.');
            // $var_max = number_format($row_rs['existencia_min'], 0, ',', '.');
            // $var_precio = number_format($row_rs['coniva1'], 2, ',', '.');
            // $precio_sub = $var_exi * $row_rs['coniva1'];
            // $var_precio_sub = number_format($precio_sub, 2, ',', '.');
            // $subtotal_dpto += $precio_sub;
            $contador++;

            $this->SetFont("Arial", "I", 9);
            // llamado para hacer multilinea sin que haga salto de linea
            $this->SetWidths(array(15, 82, 15, 15, 15, 25, 25));
            $this->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R'));
            $this->Setceldas(array(0, 0, 0, 0, 0));
            $this->Setancho(array(5, 5, 5, 5, 5, 5, 5));
            $this->Row(array($var_codigo, $var_descrip, $var_exi));

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
$pdf = new PDF();

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
$pdf->Cell(28, 7, "Bs. " . number_format($total, 2, ',', '.'), "LTBR", 1, 'R');
$pdf->Output();
?>
