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
        $this->SetFont('Arial', 'B', 8);
        $this->Image($this->datosgenerales[0]["img_der"] ? $this->datosgenerales[0]["img_der"] : $this->datosgenerales[0]["img_izq"], 10, 8, 50, 10);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 0, utf8_decode("Presupuesto Nro. " . $this->array_factura[0]["cod_cotizacion"]), 0, 0, 'R');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode("Fecha de Creación: " . fecha($this->array_factura[0]["fecha_cotizacion"])), 0, 0, 'R');
        $this->Ln(3);
        $this->SetX(14);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 0, utf8_decode("P R E S U P U E S T O"), 0, 0, 'C');

        $this->SetFont('Arial', '', 6);

        $this->Ln(3);
        $this->SetX(14);
        $this->Cell(0, 0, "CLIENTE: " . utf8_decode($this->array_factura[0]["nombre"]), 0, 0, 'L');

        $this->Ln(3);
        $this->SetX(14);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["id_fiscal"] . ": " . $this->array_factura[0]["rif"]), 0, 0, 'L');

        $this->Ln(3);
        $this->SetX(14);
        $this->Cell(0, 0, utf8_decode("DIRECCIÓN: " . $this->array_factura[0]["direccion"]), 0, 0, 'L');

        $this->Ln(3);
        $this->SetX(14);
        $this->Cell(0, 0, utf8_decode("TELEFONOS: " . $this->array_factura[0]["telefonos"]), 0, 0, 'L');

        $moneda = rtrim($this->datosgenerales[0]["moneda"], ".");

        $this->Ln(3);
        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(15);
        $this->SetFont('Arial', 'B', 6);
        #$this->SetFillColor(10, 10, 10, 10, 10, 10, 10, 10, 10);
        #$this->Cell(11, $width, utf8_decode('Código'), 1, 0, "C", 0);
        $this->Cell(81, $width, utf8_decode('Código - Descripción'), 1, 0, "C", 0);
        $this->Cell(11, $width, utf8_decode('Cant.'), 1, 0, "C", 0);
        $this->Cell(15, $width, utf8_decode('Precio (' . $moneda . ')'), 1, 0, "C", 0);
        $this->Cell(20, $width, utf8_decode('Dto. (%)'), 1, 0, "C", 0);
        $this->Cell(15, $width, utf8_decode('Dto. (' . $moneda . ')'), 1, 0, "C", 0);
        $this->Cell(20, $width, utf8_decode('Total Sin ' . $this->datosgenerales[0]["nombre_impuesto_principal"] . ' (' . $moneda . ')'), 1, 0, "C", 0);
        $this->Cell(11, $width, utf8_decode('% ' . $this->datosgenerales[0]["nombre_impuesto_principal"]), 1, 0, "C", 0);
        $this->Cell(20, $width, utf8_decode('Total con ' . $this->datosgenerales[0]["nombre_impuesto_principal"] . ' (' . $moneda . ')'), 1, 0, "C", 0);
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
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
        //$conn = new rp_Connect();
        //$conn->SQL("select * from esquema.almacen_ubicacion");
        $this->SetFont('Arial', '', 6);

        $subtotal = 0;
        $cantidaditems = $this->array_factura[0]["cantidad_items"];

        for ($i = 0; $i < $cantidaditems; $i++) {

            $this->SetX(15);
            $this->Cell(81, 5, $this->array_factura[$i]["cod_item"] . " - " . utf8_decode($this->array_factura[$i]["_item_descripcion"]), $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "J");
            $this->Cell(11, 5, $this->array_factura[$i]["_item_cantidad"], $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "R");
            $this->Cell(15, 5, number_format($this->array_factura[$i]["_item_preciosiniva"], 2, ',', '.'), $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "R");
            $this->Cell(20, 5, number_format($this->array_factura[$i]["_item_descuento"], 2, ',', '.'), $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "R");
            $this->Cell(15, 5, number_format($this->array_factura[$i]["_item_montodescuento"], 2, ',', '.'), $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "R");
            $this->Cell(20, 5, number_format($this->array_factura[$i]["_item_totalsiniva"], 2, ',', '.'), $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "R");
            $this->Cell(11, 5, number_format($this->array_factura[$i]["_item_piva"], 2, ',', '.'), $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "R");
            $this->Cell(20, 5, number_format($this->array_factura[$i]["_item_totalconiva"], 2, ',', '.'), $i < ($cantidaditems - 1) ? "LR" : "LRB", 1, "R");

            $subtotal += $this->array_factura[$i]["_item_cantidad"] * $this->array_factura[$i]["_item_preciosiniva"];
        }

        $this->Ln(2);
        $width = 5;
        $this->SetX(157);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(31, $width, utf8_decode('Sub-Total'), 1, 0, "R", 0);
        $this->Cell(20, $width, number_format($subtotal, 2, ',', '.'), 1, 0, "R", 0);
        $this->Ln(5);
        $this->SetX(157);
        $this->Cell(31, $width, utf8_decode('Descuento'), 1, 0, "R", 0);
        $this->Cell(20, $width, number_format($this->array_factura[0]["descuentosItemCotizacion"], 2, ',', '.'), 1, 0, "R", 0);
        $this->Ln(5);
        $this->SetX(157);
        $this->Cell(31, $width, utf8_decode($this->datosgenerales[0]["nombre_impuesto_principal"]), 1, 0, "R", 0);
        $this->Cell(20, $width, number_format($this->array_factura[0]["ivaTotalCotizacion"], 2, ',', '.'), 1, 0, "R", 0);
        $this->Ln(5);
        $this->SetX(157);
        $this->Cell(31, $width, utf8_decode('Retenciones'), 1, 0, "R", 0);
        $this->Cell(20, $width, number_format($this->array_factura[0]["totalizar_total_retencion"], 2, ',', '.'), 1, 0, "R", 0);
        $this->Ln(5);

        $this->SetX(157);
        $this->Cell(31, $width, utf8_decode('Total a Pagar'), 1, 0, "R", 0);
        $this->Cell(20, $width, number_format($this->array_factura[0]["TotalTotalCotizacion"], 2, ',', '.'), 1, 0, "R", 0);
        $this->Ln(5);
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

}

$nro_factura = @$_GET["codigo"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM  parametros_generales");

$array_factura = $comunes->ObtenerFilasBySqlSelect("
SELECT f.*, c.nombre, c.direccion, c.rif,c.cod_cliente, c.telefonos,c.direccion, v.nombre as nom_vendedor
, fd.*, i.cod_item, ifor.descripcion as tipo_item_
FROM cotizacion_presupuesto f inner join clientes c on c.id_cliente = f.id_cliente
inner join cotizacion_presupuesto_detalle fd on fd.id_cotizacion = f.id_cotizacion
inner join vendedor v on v.cod_vendedor = f.cod_vendedor
 inner join item i on i.id_item = fd.id_item
 inner join item_forma ifor on ifor.cod_item_forma = i.cod_item_forma
    where f.cod_cotizacion = '" . $nro_factura . "'");


$pdf = new PDF('P', 'mm', 'letter');
$title = 'Cotización Nro.';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
