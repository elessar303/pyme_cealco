<?php

include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;

    function Header() {

        $fecha_ini = new DateTime(@$_GET["fecha"]); # PHP 5 >= 5.2.0;
        $fecha_fin = new DateTime(@$_GET["fecha2"]);

        $fecha_ini = $fecha_ini->format("d-m-Y");
        $fecha_fin = $fecha_fin->format("d-m-Y");

        $this->Image($this->datosgenerales[0]["imagen_der"] ? $this->datosgenerales[0]["imagen_der"] : $this->datosgenerales[0]["imagen_izq"], 10, 8, 50, 10);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        #$this->SetFillColor(10, 50, 100);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, "Fecha Emision: " . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("REPORTE DE CLIENTES CON REGISTRO BIOMETRICO"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->Cell(0, 0, "Al ".date("d/m/Y"), 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        /*$this->SetWidths(array(20, 95, 15, 20, 20, 20));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C"));
        $this->Row(array('Codigo', 'Descripcion', 'Unid.', 'Sub-Total', $this->datosgenerales[0]["nombre_impuesto_principal"], 'Total'), 1);
        */
        
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function dwawCell($title, $data) {
        $width = 8;
        $this->SetFont('Arial', 'B', 12);
        $y = $this->getY() * 20;
        $x = $this->getX();
        $this->SetFillColor(206, 230, 100);
        $this->MultiCell(175, 8, $title, 0, 1, 'L', 0);
        $this->SetY($y);
        $this->SetFont('Arial', '', 12);
        $this->SetFillColor(206, 230, 172);
        $w = $this->GetStringWidth($title) + 3;
        $this->SetX($x + $w);
        $this->SetFillColor(206, 230, 172);
        $this->MultiCell(175, 8, $data, 0, 1, 'J', 0);
    }

    function ChapterBody()
    {
        $this->SetWidths(array("190"));
        $this->SetAligns(array("C"));
        $this->Row(array("CANTIDAD DE CLIENTES REGISTRADOS CON BIOMETRICO (VEN): ".$this->arrayven[0][cantidad] ), 1);
        $this->Row(array("CANTIDAD DE CLIENTES REGISTRADOS CON BIOMETRICO (EXT): ".($this->arrayExt[0][cantidad] ? $this->arrayExt[0][cantidad] : '0') ), 1);
        $this->Row(array("TOTAL DE CLIENTES REGISTRADOS CON BIOMETRICO: ".($this->arrayven[0][cantidad]+$this->arrayExt[0][cantidad] ) ), 1);
        unset($this->array_factura, $this->datosgenerales);
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, $label, 0, 1, 'L', 1);
        $this->Ln(8);
    }

    function SetTitle($title, $isUTF8 = false) {
        $this->title = $title;
    }

    function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }

    function DatosGenerales($array) {
        $this->datosgenerales = $array;
    }

    function ArrayVen($array) {
        $this->arrayven = $array;
    }

    function ArrayExt($array) {
        $this->arrayExt = $array;
    }



}


$comunes = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");
$pos=POS;
$arrayven = $comunes->ObtenerFilasBySqlSelect("SELECT count(fingerprint) as cantidad FROM $pos.customers WHERE substring(TAXID,1,1)='V'");
$arrayext = $comunes->ObtenerFilasBySqlSelect("SELECT count(fingerprint) as cantidad FROM $pos.customers WHERE substring(TAXID,1,1)='E'");
$pdf = new PDF('P', 'mm', 'A4');
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayVen($arrayven);
$pdf->ArrayExt($arrayext);
$pdf->SetTitle('Registros Biometricos');
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
