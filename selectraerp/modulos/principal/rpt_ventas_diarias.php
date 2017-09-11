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

        list($anio, $mes, $dia) = explode("-", @$_GET["fecha"]);
        $fecha = strtoupper($dia . " de " . mesaletras($mes) . " de " . $anio);
        //$width = 10;
        $this->Image($this->datosgenerales[0]["imagen_der"] ? $this->datosgenerales[0]["imagen_der"] : $this->datosgenerales[0]["imagen_izq"], 10, 8, 50, 10);
        $this->SetY(15);
        $this->SetLeftMargin(15);
        $this->SetFont('Arial', 'B', 8);
        #$this->SetFillColor(10, 50, 100);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode("Fecha Emisión: ") . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("REPORTE DE VENTAS DEL ") . $fecha, 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 6);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(7, 50, 20, 15, 20, 10, 15, 25, 20, 20, 20, /* 20, */ 20, 20));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", /* "C", */ "C", "C"));
        $this->Row(array('Nro.', 'Nombre o Razon Social del Cliente', 'RIF', 'Factura', 'Subtotal', '% ' . $this->datosgenerales[0]["nombre_impuesto_principal"], $this->datosgenerales[0]["nombre_impuesto_principal"], utf8_decode('Débito/Crédito'), utf8_decode('Depósito'), 'Cheque', 'Otros', /* 'Efectivo', */ utf8_decode('Retención'), 'Total Factura'), 1);
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

        $totalDebitoCredito = $totalCheque = $totalOtros = $totalEfectivo = $totalDeposito = $totalVentasConIva = $totalVentasNoGravadas = $totalBaseImponible = $totalIva = $totalIvaRet = $totalTotalFactura = 0;
        $i = 0;

        while ($this->array_factura[$i]) {
            $porc = ($this->array_factura[$i]["ivaTotalFactura"] * 100) / $this->array_factura[$i]["montoItemsFactura"];

            if (($porc >= 11.9) && ($porc < 12.5)) {
                $porc = 12;
            }

            $totalFactura = $this->array_factura[$i]["montoItemsFactura"] + $this->array_factura[$i]["ivaTotalFactura"];
            $nro_factura = $this->datosgenerales[0]["tipo_facturacion"] != 1 ? $this->array_factura[$i]["cod_factura"] : $this->array_factura[$i]["cod_factura_fiscal"];

            #$width = 5;
            $this->SetFont('Arial', '', 6);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(7, 50, 20, 15, 20, 10, 15, 25, 20, 20, 20, /* 20, */ 20, 20));
            $this->SetAligns(array("C", "L", "C", "C", "R", "R", "R", "R", "R", "R", "R", /* "R", */ "R", "R"));
            $this->Row(array($i + 1,
                $this->array_factura[$i]["nombre"],
                $this->array_factura[$i]["rif"],
                $nro_factura,
                number_format($this->array_factura[$i]["totalizar_base_imponible"], 2, ',', '.'),
                number_format($porc, 2, ',', '.'),
                number_format($this->array_factura[$i]["ivaTotalFactura"], 2, ',', '.'),
                number_format($this->array_factura[$i]["totalizar_monto_tarjeta"], 2, ',', '.'),
                number_format($this->array_factura[$i]["totalizar_monto_deposito"], 2, ',', '.'),
                number_format($this->array_factura[$i]["totalizar_monto_cheque"], 2, ',', '.'),
                number_format($this->array_factura[$i]["totalizar_monto_otrodocumento"], 2, ',', '.'),
                #number_format($this->array_factura[$i]["totalizar_monto_efectivo"], 2, ',', '.'),
                number_format($this->array_factura[$i]["totalizar_total_retencion"], 2, ',', '.'),
                number_format($totalFactura, 2, ',', '.')), 1);

            $totalDebitoCredito+=$this->array_factura[$i]["totalizar_monto_tarjeta"];
            #$totalEfectivo+=$this->array_factura[$i]["totalizar_monto_efectivo"];
            $totalDeposito+=$this->array_factura[$i]["totalizar_monto_deposito"];
            $totalOtros+=$this->array_factura[$i]["totalizar_monto_otrodocumento"];
            $totalVentasConIva+=$totalFactura;
            $totalVentasNoGravadas+=0;
            $totalBaseImponible+=$this->array_factura[$i]["totalizar_base_imponible"];
            $totalIva+=$this->array_factura[$i]["ivaTotalFactura"];
            $totalIvaRet+=$this->array_factura[$i]["totalizar_total_retencion"];
            $totalCheque+=$this->array_factura[$i]["totalizar_monto_cheque"];
            $i++;
        }

        #$totalEfectivo = number_format($totalEfectivo, 2, ',', '.');
        $totalBaseImponible = number_format($totalBaseImponible, 2, ',', '.');
        $totalOtros = number_format($totalOtros, 2, ',', '.');
        $totalDeposito = number_format($totalDeposito, 2, ',', '.');
        $totalDebitoCredito = number_format($totalDebitoCredito, 2, ',', '.');
        $totalIvaRet = number_format($totalIvaRet, 2, ',', '.');
        $totalVentasConIva = number_format($totalVentasConIva, 2, ',', '.');
        $totalIva = number_format($totalIva, 2, ',', '.');
        $totalCheque = number_format($totalCheque, 2, ',', '.');

        $this->Ln(1);
        $this->SetFont('Arial', 'B', 8); #$this->SetFont('Arial', 'B', 8);
        $this->SetWidths(array(92, 20, 10, 15, 25, 20, 20, 20, /* 20, */ 20, 20));
        $this->SetAligns(array("C", "R", "C", "R", "R", "R", "R", "R", /* "R", */ "R", "R"));
        $this->Row(array("T O T A L E S", $totalBaseImponible, "N/A", $totalIva, $totalDebitoCredito, $totalDeposito, $totalCheque, $totalOtros, /* $totalEfectivo, */ $totalIvaRet, $totalVentasConIva), 1);

        unset($totalDebitoCredito, $totalCheque, $totalOtros, $totalEfectivo, $totalDeposito, $totalVentasConIva, $totalVentasNoGravadas, $totalBaseImponible, $totalIva, $totalIvaRet, $totalTotalFactura, $i);
        unset($nro_factura, $totalFactura);
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

$fecha = @$_GET["fecha"];
list($anio, $mes, $dia) = explode("-", $fecha);

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

#$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT f.*, c.nombre, fd.*, c.rif FROM factura f inner join clientes c on c.id_cliente = f.id_cliente inner join factura_detalle_formapago fd on fd.id_factura = f.id_factura WHERE f.cod_estatus=2 AND year(f.fechaFactura) = year('" . $fecha . "') AND month(f.fechaFactura) = month('" . $fecha . "') AND day(f.fechaFactura) = day('" . $fecha . "') ORDER BY f.id_factura");
$array_factura = $comunes->ObtenerFilasBySqlSelect(
        "SELECT f.*, c.nombre, fd.*, c.rif
        FROM factura f
        INNER JOIN clientes c ON c.id_cliente = f.id_cliente
        INNER JOIN factura_detalle_formapago fd ON fd.id_factura = f.id_factura
        WHERE f.cod_estatus=2 AND year(f.fechaFactura) = {$anio} AND month(f.fechaFactura) = {$mes} AND day(f.fechaFactura) = {$dia} ORDER BY f.id_factura");

//$tmp_fecha = strtoupper($dia . " de " . mesaletras($mes) . " de " . $anio);
$pdf = new PDF('L', 'mm', 'A4');
$title = 'Libro de Ventas.';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle($title);
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
