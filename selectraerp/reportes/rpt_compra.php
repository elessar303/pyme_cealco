<?php

include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_compra;

    function Header() {

        #$width = 10;
        //$this->Image('../imagenes/banner_superior.jpg',10,5,190);
        #$this->SetY(5);
        $this->Image($this->datosgenerales[0]["img_der"] ? $this->datosgenerales[0]["img_der"] : $this->datosgenerales[0]["img_izq"], 10, 8, 50, 10);
        $this->SetFont('Arial', 'B', 8);
        //$this->SetFillColor(239,239,239);
        #$this->SetFont('Arial','B',6);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);

        $this->SetFont('Arial', '', 8);
        $this->Cell(173, 0, "Nro. compra: ", 0, 0, 'R', 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(22, 0, $this->array_compra[0]["cod_compra"], 0, 0, 'R');
        $this->Ln(4);

        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 0, utf8_decode("Fecha de Creación: " . fecha($this->array_compra[0]["fechacompra"])), 0, 0, 'R');
        $this->Ln(3);

        $this->SetX(14);
        $this->SetFont('Arial', '', 6);
        $this->Cell(0, 0, "Proveedor: " . utf8_decode($this->array_compra[0]["nproveedor"]), 0, 0, 'L');
        $this->Ln(3);
        $this->SetX(14);
        $this->Cell(0, 0, utf8_decode("Dirección: " . $this->array_compra[0]["direccionproveedor"]), 0, 0, 'L');
        $this->Ln(3);
        $this->SetX(14);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["id_fiscal"]) . ": " . $this->array_compra[0]["rifproveedor"], 0, 0, 'L');
        $this->Ln(3);

        $this->SetX(14);
        $this->Cell(0, 0, utf8_decode("Teléfonos: ") . $this->array_compra[0]["telefonosproveedor"], 0, 0, 'L');
        $this->Ln(3);

        $this->SetX(14);
        $this->Cell(0, 0, "Reponsable: " . utf8_decode($this->array_compra[0]["responsable"]), 0, 0, 'L');
        $this->Ln(3);

        $this->SetX(14);
        $this->Cell(0, 0, "Fac. Nro de Compra: " . $this->array_compra[0]["num_factura_compra"], 0, 0, 'L');
        $this->Ln(3);

        $this->SetX(14);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 0, "ORDEN DE COMPRAS", 0, 0, 'C');
        $this->Ln(6);

        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(5 + 11);
        $this->SetFont('Arial', '', 6);
        $this->SetFillColor(10, 10, 10, 10, 10, 10, 10, 10, 10);
        #$this->Cell(11, $width, 'Codigo', 1, 0, "C", 0);
        $this->Cell(80, $width, utf8_decode('Código - Descripción'), 1, 0, "C", 0);
        $this->Cell(11, $width, 'Cantidad', 1, 0, "C", 0);
        $this->Cell(15, $width, 'Precio', 1, 0, "C", 0);
        $this->Cell(20, $width, utf8_decode('Almacén'), 1, 0, "C", 0);
        $this->Cell(15, $width, utf8_decode($this->datosgenerales[0]["nombre_impuesto_principal"]), 1, 0, "C", 0);
        $this->Cell(20, $width, utf8_decode('Total Sin ' . $this->datosgenerales[0]["nombre_impuesto_principal"]), 1, 0, "R", 0);
        $this->Cell(11, $width, utf8_decode('% ' . $this->datosgenerales[0]["nombre_impuesto_principal"]), 1, 0, "R", 0);
        $this->Cell(20, $width, utf8_decode('Total con ' . $this->datosgenerales[0]["nombre_impuesto_principal"]), 1, 0, "R", 0);
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);

        $this->Cell(0, 10, 'P�gina ' . $this->PageNo(), 0, 0, 'C');
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

        $this->SetWidths(array(/* 11, */80, 11, 15, 20, 15, 20, 11, 20));
        $this->SetAligns(array(/* "C", */"J", "C", "C", "C", "R", "R", "R", "R"));
        $this->SetFillColor(/* 232, */232, 232, 232, 232, 232, 232, 232, 232);

        $subtotal = 0;
        for ($i = 0; $i < $this->array_compra[0]["cantidad_items"]; $i++) {
            $this->SetLeftMargin(30);
            $width = 5;
            $this->SetX(5 + 11);
            $this->SetFont('Arial', '', 6);

            $subtotal += $this->array_compra[$i]["_item_cantidad"] * $this->array_compra[$i]["_item_preciosiniva"];
            $this->Row(array(
                $this->array_compra[$i]["cod_item"] . " - " . $this->array_compra[$i]["descripcion1"],
                $this->array_compra[$i]["_item_cantidad"],
                number_format($this->array_compra[$i]["_item_preciosiniva"], 2, ',', '.'),
                $this->array_compra[$i]["descripcion"],
                number_format($this->array_compra[$i]["_tiva"], 2, ',', '.'),
                number_format($this->array_compra[$i]["_item_totalsiniva"], 2, ',', '.'),
                number_format($this->array_compra[$i]["piva"], 2, ',', '.'),
                number_format($this->array_compra[$i]["_item_totalconiva"], 2, ',', '.')), 1);
        }

        $this->Ln(2);
        //$this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(173 - 16);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(31, $width, 'Sub-Total', 1, 0, "R", 0);
        $this->Cell(20, $width, $this->datosgenerales[0]["moneda"] . " " . number_format($subtotal, 2, ',', '.'), 1, 0, "R", 0);
        $this->Ln(5);
        $this->SetX(173 - 16);
        $this->Cell(31, $width, $this->datosgenerales[0]["nombre_impuesto_principal"], 1, 0, "R", 0);
        $this->Cell(20, $width, $this->datosgenerales[0]["moneda"] . " " . number_format($this->array_compra[0]["ivaTotalcompra"], 2, ',', '.'), 1, 0, "R", 0);
        $this->Ln(5);
        $this->SetX(173 - 16);
        $this->Cell(31, $width, 'Total Compra', 1, 0, "R", 0);
        $this->Cell(20, $width, $this->datosgenerales[0]["moneda"] . " " . number_format($this->array_compra[0]["TotalTotalcompra"], 2, ',', '.'), 1, 0, "R", 0);
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

    function Arraycompra($array) {
        $this->array_compra = $array;
    }

}

$nro_compra = @$_GET["codigo"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

$array_compra = $comunes->ObtenerFilasBySqlSelect("
SELECT *, p.descripcion AS nproveedor,
p.direccion AS direccionproveedor,
p.telefonos AS telefonosproveedor,
p.rif AS rifproveedor
FROM compra c INNER JOIN compra_detalle cd ON cd.id_compra = c.id_compra
    INNER JOIN proveedores p on p.id_proveedor = c.id_proveedor
    LEFT JOIN almacen a ON a.cod_almacen = cd._item_almacen
    INNER JOIN item i ON i.id_item = cd.id_item
    WHERE c.cod_compra = '" . $nro_compra . "'");

if (count($array_compra) == 0) {
    echo "no se encontraron registros.";
    exit;
}

$pdf = new PDF('P', 'mm', 'letter');
$title = 'Detalle de Compra';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->Arraycompra($array_compra);

$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
