<?php

if (!isset($_SESSION)) {
    session_start();
}

require('fpdfselectra.php');
ob_end_clean();    header("Content-Encoding: None", true);
/*
  require('fpdf.php');
  require_once '../lib/config.php';
  require_once '../lib/common.php';
  require_once '../libs/php/clases/ConexionComun.php';
  require_once '../libs/php/clases/login.php';
  include('../lib/numerosALetras.class.php');
 */

class PDF extends FPDFSelectra {

    function tHead($moneda) {
        $this->SetFont("Arial", "I", 9);
        $this->Cell(15, 7, utf8_decode('Codigo'), 'LTB', 0, 'C');
        $this->Cell(25, 7, utf8_decode('Cod. Barras'), 'LTB', 0, 'C');
        $this->Cell(85, 7, utf8_decode('Descripcion'), 'LTB', 0, 'C');
        $this->Cell(20, 7, 'Existencia', 'LTB', 0, 'C');
        $this->Cell(10, 7, utf8_decode('Min.'), 'LTB', 0, 'C');
        $this->Cell(10, 7, utf8_decode('Max.'), 'LTBR', 0, 'C');
        $this->Cell(18, 7, "Precio ({$moneda})", 'LTBR', 0, 'C');
        $this->Ln();
    }

    function imprimir_datos($nro_odp, $fila_odp, $moneda, $pdf) {
        $cantidad_registros = 40;
        if (floor($this->GetY())>265) {
            $this->Ln(60);
        }
        $this->tHead($moneda);

        $conexion = conexion();
        $rs = query("SELECT * FROM item ORDER BY descripcion1", $conexion);

        $totalwhile = num_rows($rs);
        if ($totalwhile == 0) {
            $this->SetY(-75);
            $this->Cell(188, 7, 'No hay materiales', 0, 0, 'C');
        }

        $contar = 1;
        $cantidad_registros = 40;
        $totalExi=0;
        while ($totalwhile >= $contar) {
            $conexion = conexion();
            $row_rs = fetch_array($rs);
            $cont2 = $cont2 + 1;
            //$var_snc=$row_rs[0];
            //$var_codigo = $row_rs['cod_item'];#0
            //$var_descrip = utf8_decode($row_rs[2]);
            $busq = query("SELECT sum(a.cantidad) as cantidad FROM item i, item_existencia_almacen a WHERE i.id_item = a.id_item AND i.id_item = {$row_rs["id_item"]}", $conexion);
            if (num_rows($busq) != 0) {
                $row_rs_busq = fetch_array($busq);
                $aux = $row_rs_busq['cantidad'];
                $var_exi = number_format($row_rs_busq['cantidad'], 0, ',', '.');
            } else {
                $var_exi = 0;
                $aux = 0;
            }
            
           $precio=$row_rs["coniva1"];
            if ($precio == 0) {
               $var_precio=$row_rs["coniva1"]; 
            } else {
               $var_precio=$row_rs["precio1"];
            }
            
            $var_min = number_format($row_rs["existencia_min"], 0, ',', '.');
            $var_max = number_format($row_rs["existencia_max"], 0, ',', '.');
            $var_precio = number_format($row_rs["coniva1"], 2, ',', '.'); #29
            $contador++;

            //$monto_3  = number_format($var_monto3,2,',','.');
            $this->SetFont("Arial", "I", 8);
            // llamado para hacer multilinea sin que haga salto de linea
            $this->SetWidths(array(15, 25, 85, 20, 10, 10, 18));
            $this->SetAligns(array('C', 'L', 'L', 'R', 'R', 'R', 'R'));
            $this->Setceldas(array(0, 0, 0, 0, 0, 0));
            $this->Setancho(array(5, 5, 5, 5, 5, 5, 5));
            $this->Row(array($row_rs['cod_item'], $row_rs["codigo_barras"], utf8_decode($row_rs["descripcion1"]), $var_exi, $var_min, $var_max, $row_rs["precio1"]));
				$totalExi+=$aux;
            //if ($cont == $cantidad_registros) {
            	if (floor($this->GetY())>265) {
                $this->Ln(70);
                $this->SetFont("Arial", "I", 9);
                $this->tHead($moneda);
                //$this->Ln();
                $cont = 1;
            } else {
                $cont++;
                //echo $cont;
            }
            $contar++;
        }//fin del while
        $this->SetFont("Arial", "B", 8);
            // llamado para hacer multilinea sin que haga salto de linea
            $this->SetWidths(array(15, 25, 85, 20, 10, 10, 18));
            $this->SetAligns(array('C', 'L', 'L', 'R', 'R', 'R', 'R'));
            $this->Setceldas(array(0, 0, 0, 0, 0, 0));
            $this->Setancho(array(5, 5, 5, 5, 5, 5, 5));
            $this->Row(array("","", "Totales", $totalExi, "","",""));
    }

    //Pie de página
    function Footer() {

        //Posición: a  cm del final
        $this->SetY(-15);
        // fin
        $this->SetFont('Arial', 'I', 8);
        //Número de página
        $this->Cell(188, 5, utf8_decode('Pagina ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetFont('Arial', 'I', 8);
        $this->Ln();
    }

}

//Creación del objeto de la clase heredada
$pdf = new PDF();

$pdf->setTituloReporte('L I S T A D O  D E  P R O D U C T O S');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

$conexion = conexion();

$tabla = "item";
$consulta = "SELECT * FROM item WHERE cod_item_forma = 1;";
$resultado = query($consulta, $conexion);
$codigo_snc = $_GET['codigo_snc'];

//$url="materiales_list";
//$modulo="Materiales";
//$titulos=array("Código","Descripción","Unidad","I.V.A.");
//$indices=array("0","1","2","13");

$Conn = conexion();
$var_sql = "SELECT moneda FROM parametros_generales;";
$rsu = query($var_sql, $Conn);
$row_rsu = fetch_array($rsu);
$moneda = $row_rsu['moneda'];
#$periodo = $row_rsu['periodo'];
cerrar_conexion($Conn);

$pdf->imprimir_datos($nro_odp, $fila_odp, $moneda, $pdf);
ob_end_clean();
$pdf->Output();
?>