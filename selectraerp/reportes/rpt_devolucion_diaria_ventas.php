<?php

include('config_reportes.php');
#require('mem_image.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;

    function Header() {
        #global $fecha;
        $width = 10;
        $this->SetY(15);
        $this->SetLeftMargin(15);
        #$width = 5;$this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        #$this->SetFillColor(10, 50, 100);

        /* $pdf = new PDF_MemImage();
          $comunes = new ConexionComun();
          $array_factura = $comunes->ObtenerFilasBySqlSelect("select * from archivos");
          $img = file_get_contents($array_factura[0]["contenido"]);
          $pdf->MemImage($img, 50, 30);
          ##$this->Image($array_factura[0]["contenido"] , 10, 8, 50, 10); */

        /* $comunes = new ConexionComun();
          $fila = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM archivos");
          header("Content-type: application/pdf");
          ##$this->Image($fila[0]["contenido"], 10, 8, 50, 10);
          header("Content-Disposition: inline; filename=hello.pdf");
          print $fila[0][contenido]; */

        $this->Image('../../includes/imagenes/'.$this->datosgenerales["img_izq"], 10, 8, 50, 20);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode("Fecha de Emision: ") . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetX(14);
        $this->SetFont('Arial', 'B', 14);
        $fecha = new DateTime(@$_GET["fecha"]);
        $this->Cell(0, 0, utf8_decode("REPORTE DE DEVOLUCIONES DEL DÍA ") . $fecha->format("d") . " DE " . strtoupper(mesaletras($fecha->format("m"))) . " DE " . $fecha->format("Y"), 0, 0, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
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

    function getFacturaDevuelta($cod_factura) {
        $i = 0;
        while ($factura_devuelta = $this->array_facturas_devueltas[$i]) {
            if ($this->array_facturas_devueltas[$i]["cod_factura"] == $cod_factura) {
                return $i;
            }
            $i++;
        }
    }

    function ChapterBody() {

        $this->SetFont('Arial', 'B', 8);
        $this->SetLineWidth(0.1);
        $this->SetLeftMargin(20);
        $this->SetWidths(array(10, 80, 25, 30, 30, 20, 15, 25, /* 20, 20, 20, 20, 20, 20, */ 25));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", /* "C", "C", "C", "C", "C", "C", */ "C"));
        $this->Row(array('Nro.', 'Nombre o Razon Social del Cliente', 'CI / RIF', 'Factura Afectada', utf8_decode('Nro. Nota Crédito'), 'Subtotal', '% ' . $this->datosgenerales[0]["nombre_impuesto_principal"], 'Subtotal ' . $this->datosgenerales[0]["nombre_impuesto_principal"], /* utf8_decode('Débito/Crédito'), utf8_decode('Depósito'), 'Cheque', 'Otros', 'Efectivo', utf8_decode('Retención'), */ 'Total Factura'), 1);
        $this->SetWidths(array(10, 80, 25, 30, 30, 20, 15, 25, /* 20, 20, 20, 20, 20, 20, */ 25));
        $this->SetAligns(array("C", "L", "L", "C", "C", "R", "R", "R", /* "R", "R", "R", "R", "R", "R", */ "R"));

        $totalDebitoCredito = $totalCheque = $totalOtros = $totalEfectivo = $totalDeposito = $totalVentasConIva = $totalVentasNoGravadas = $totalBaseImponible = $totalIva = $totalIvaRet = $totalTotalFactura = 0;
        $i = 0;
        while ($this->array_factura[$i]) {
            $porc = ($this->array_factura[$i]["ivaTotalFactura"] * 100) / $this->array_factura[$i]["montoItemsFactura"];
            if (($porc >= 11.9) && ($porc < 12.5))
                $porc = 12;

            $this->SetFont('Arial', '', 8);
            #$this->SetLeftMargin(20);

            $totalFactura = $this->array_factura[$i]["montoItemsFactura"] + $this->array_factura[$i]["ivaTotalFactura"];

            $this->Row(
                    array($i + 1,
                #$this->array_factura[$i]["fechaFactura"],
                $this->array_factura[$i]["nombre"],
                $this->array_factura[$i]["rif"],
                $this->array_factura[$i]["cod_factura_fiscal"],
                $this->array_facturas_devueltas[$this->getFacturaDevuelta($this->array_factura[$i]["cod_factura"])]["cod_devolucion_fiscal"],
                number_format($this->array_factura[$i]["totalizar_base_imponible"], 2, ',', '.'),
                number_format($porc, 2, ',', '.'),
                number_format($this->array_factura[$i]["ivaTotalFactura"], 2, ',', '.'),
                /* number_format($this->array_factura[$i]["totalizar_monto_tarjeta"], 2, ',', '.'),
                  number_format($this->array_factura[$i]["totalizar_monto_deposito"], 2, ',', '.'),
                  number_format($this->array_factura[$i]["totalizar_monto_cheque"], 2, ',', '.'),
                  number_format($this->array_factura[$i]["totalizar_monto_otrodocumento"], 2, ',', '.'),
                  number_format($this->array_factura[$i]["totalizar_monto_efectivo"], 2, ',', '.'),
                  number_format($this->array_factura[$i]["totalizar_total_retencion"], 2, ',', '.'), */
                number_format($totalFactura, 2, ',', '.')), 1);
            /* $totalDebitoCredito+=$this->array_factura[$i]["totalizar_monto_tarjeta"];
              $totalEfectivo+=$this->array_factura[$i]["totalizar_monto_efectivo"];
              $totalDeposito+=$this->array_factura[$i]["totalizar_monto_deposito"];
              $totalOtros+=$this->array_factura[$i]["totalizar_monto_otrodocumento"];
              $totalIvaRet+=$this->array_factura[$i]["totalizar_total_retencion"];
              $totalCheque+=$this->array_factura[$i]["totalizar_monto_cheque"]; */
            $totalVentasConIva+=$totalFactura;
            $totalVentasNoGravadas+=0;
            $totalBaseImponible+=$this->array_factura[$i]["totalizar_base_imponible"];
            $totalIva+=$this->array_factura[$i]["ivaTotalFactura"];
            $i++;
        }

        $totalBaseImponible = number_format($totalBaseImponible, 2, ',', '.');
        /* $totalEfectivo = number_format($totalEfectivo, 2, ',', '.');
          $totalOtros = number_format($totalOtros, 2, ',', '.');
          $totalDeposito = number_format($totalDeposito, 2, ',', '.');
          $totalDebitoCredito = number_format($totalDebitoCredito, 2, ',', '.');
          $totalIvaRet = number_format($totalIvaRet, 2, ',', '.');
          $totalCheque = number_format($totalCheque, 2, ',', '.'); */
        $totalVentasConIva = number_format($totalVentasConIva, 2, ',', '.');
        $totalIva = number_format($totalIva, 2, ',', '.');
        $this->Ln(1);

        $this->SetFont('Arial', 'B', 12);
        #$this->SetLeftMargin(20);
        $this->SetWidths(array(175, 20, 15, 25, /* 20, 20, 20, 20, 20, 20, */ 25));
        $this->SetAligns(array("C", "R", "R", "R", /* "R", "R", "R", "R", "R", "R", */ "R"));
        $this->Row(array("T O T A L E S", $totalBaseImponible, "N/A", $totalIva, /* $totalDebitoCredito, $totalDeposito, $totalCheque, $totalOtros, $totalEfectivo, $totalIvaRet, */ $totalVentasConIva), 1);
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, "$label", 0, 1, 'L', 1);
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

    function ArrayFacturasDevueltas($array) {
        $this->array_facturas_devueltas = $array;
    }

}

$fecha = @$_GET["fecha"];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

$array_factura = $comunes->ObtenerFilasBySqlSelect("
    SELECT f.*, c.nombre, c.rif
    FROM factura f
    INNER JOIN clientes c ON c.id_cliente = f.id_cliente
    WHERE f.fechaFactura LIKE '{$fecha}' AND f.cod_estatus = 3
    ORDER BY f.id_factura");
#$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT f.*, c.nombre, fd.*, c.rif FROM factura f inner join clientes c on c.id_cliente = f.id_cliente inner join factura_detalle_formapago fd on fd.id_factura = f.id_factura WHERE year(f.fechaFactura) = year('" . $fecha . "') AND month(f.fechaFactura) = month('" . $fecha . "') AND day(f.fechaFactura) = day('" . $fecha . "') AND cod_estatus = 3 ORDER BY f.id_factura");
$array_facturas_devueltas = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_devolucion");

$mes = mesaletras(substr($fecha, 5, 2));

$pdf = new PDF('L', 'mm', 'A4');
$title = 'Libro de Ventas.';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->ArrayFacturasDevueltas($array_facturas_devueltas);

$pdf->SetTitle($title);
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
