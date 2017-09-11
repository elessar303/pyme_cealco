<?php
ob_start();
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
ob_end_clean();    header("Content-Encoding: None", true);
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

        $this->Image($this->datosgenerales[0]["img_der"] ? "../../includes/imagenes/" . $this->datosgenerales[0]["img_der"] : "../../includes/imagenes/" . $this->datosgenerales[0]["img_izq"], 10, 8, 50, 10);
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
        $this->Cell(0, 0, utf8_decode("REPORTE DE VENTAS POR VENDEDOR"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->Cell(0, 0, @$_GET["fecha"] != @$_GET["fecha2"] ? "Desde {$fecha_ini} Hasta {$fecha_fin}" : $fecha_ini, 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(20, 80, 30, 30, 30));
        $this->SetAligns(array("C", "C", "C", "C", "C"));
        $this->Row(array('Codigo', 'Nombre del Vendedor', 'Porcentaje', 'Base Imponible', 'Comision'), 1);
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

    function ChapterBody() {

        $i = 0;
        #$vendedor_cod = array();
        $vendedor_nombre = array();
        $porcentaje = array();
        $base_imponible = array();

        while ($this->array_factura[$i]) {
            $vendedor_nombre[$this->array_factura[$i]["cod_vendedor"]] = $this->array_factura[$i]["nombre"];
            #$vendedor_cod[$this->array_factura[$i]["cod_vendedor"]] = $this->array_factura[$i]["cod_vendedor"];
            $porcentaje[$this->array_factura[$i]["cod_vendedor"]] = $this->array_factura[$i]["venta_a_precio1"];
            $base_imponible[$this->array_factura[$i]["cod_vendedor"]] += $this->array_factura[$i]["totalizar_base_imponible"];
            $i++;
        }

        foreach ($vendedor_nombre as $id => $vendedor) {
            $this->SetFont('Arial', '', 10);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(20, 80, 30, 30, 30));
            $this->SetAligns(array("R", "L", "R", "R", "R"));
            $this->Row(array(
                /* $vendedor_cod[$id] . "->" . */ $id,
                $vendedor,
                number_format($porcentaje[$id], 2, ',', '.'),
                number_format($base_imponible[$id], 2, ',', '.'),
                number_format($base_imponible[$id] * $porcentaje[$id] / 100, 2, ',', '.')), 1);
            $subtotal += $base_imponible[$id] * $porcentaje[$id] / 100;
        }
        $subtotal = number_format($subtotal, 2, ',', '.');

        $this->Ln(1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetWidths(array(160, 30));
        $this->SetAligns(array("C", "R"));
        $this->Row(array("T O T A L E S", $subtotal), 1);

        unset($i, $subtotal, $vendedor, /* $vendedor_cod, */ $vendedor_nombre, $porcentaje, $base_imponible);
        unset($this->array_factura, $this->datosgenerales);
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, $label, 0, 1, 'L', 1);
        $this->Ln(8);
    }

    function SetTitle($title) {
        $this->title = $title;
    }

    function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }

    function DatosGenerales($array) {
        $this->datosgenerales = $array;
    }

    function ArrayFactura($array) {
        $this->array_factura = $array;
    }

}

$inicio = @$_GET["fecha"];
$final = @$_GET["fecha2"];
#$filtro = @$_GET["filtrado_por"];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

$array_factura = $comunes->ObtenerFilasBySqlSelect("
        SELECT f.totalizar_base_imponible, v.cod_vendedor, v.nombre, v.venta_a_precio1, v.comision_a_precio1, v.comision_a_precio2, v.comision_a_precio3
        FROM factura f
        INNER JOIN vendedor v ON v.cod_vendedor = f.cod_vendedor
        WHERE f.cod_estatus = 2
        AND " . ($inicio != $final ? "f.fechaFactura >= '{$inicio}' AND f.fechaFactura <= '{$final}'" : "f.fechaFactura = '{$inicio}'") . ";");

$pdf = new PDF('P', 'mm', 'A4');

$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle('Libro de Ventas.');
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
?>
