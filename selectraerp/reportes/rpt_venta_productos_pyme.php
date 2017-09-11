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

        $this->Image('../../includes/imagenes/'.$this->datosgenerales[0]["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, "Fecha Emision: " . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("REPORTE DE PRODUCTOS VENDIDOS"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->Cell(0, 0, @$_GET["fecha"] != @$_GET["fecha2"] ? "Desde {$fecha_ini} Hasta {$fecha_fin}" : $fecha_ini, 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(30, 150, 20, 20, 20, 25));
        $this->SetAligns(array("C", "C", "C", "C", "C","C"));
        $this->Row(array( 'Cod. Barras', 'Descripcion','IVA', 'Precio Unit.', 'Unid. Vendidas', 'Total'), 1);
        
		  $this->SetWidths(array(50, 150, 20, 20, 20));
        $this->SetAligns(array("C", "L", "R", "R"));
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

        $i = $total_iva = $subtotal = $total_venta = 0;
        $item_descripcion = array();
        $item_cod = array();
        $item_unidades = array();
        $item_iva = array();
        $item_subtotales = array();
        $item_totales = array();
        $precio =0;
        $unid =0;
        $i=0;
        $total_venta=0;
        foreach ($this->array_factura as $id => $reg) {
            $total=($reg["PRICESELL"]+$reg["PRICESELL"]*($reg["_item_piva"]/100))*$reg["UNITS"];
            $this->SetFont('Arial', '', 10);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(30, 150, 20, 20, 20, 25));
            $this->SetAligns(array("C", "L", "R", "R", "R", "R"));
            $this->Row(array($reg["CODE"],$reg["NAME"],number_format($reg["_item_piva"], 0, ',', '.')."%",number_format($reg["PRICESELL"], 2, ',', '.'),number_format($reg["UNITS"], 2, ',', '.'),number_format($total, 2, ',', '.')),1);
            $precio += $reg["PRICESELL"];
            $unid += $reg["UNITS"];
            $i++;
            $total_venta = $total_venta+$total;
        }        
        $precios = number_format($precio, 2, ',', '.');
        $unids = number_format($unid, 2, ',', '.');
        $total_venta = number_format($total_venta, 2, ',', '.');

        $this->Ln(1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetWidths(array(30,170, 20, 20, 25));
        $this->SetAligns(array("C","R", "R", "R", "R"));
        $this->Row(array($i, "T O T A L E S", $precios, $unids, $total_venta), 1);

        unset($i, $subtotal, $total_iva, $total_venta, $item_descripcion, $item_cod, $item_unidades, $item_subtotales, $item_iva, $item_totales);
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

    function ArrayFactura($array) {
        $this->array_factura = $array;
    }

}

$inicio = @$_GET["fecha"];
$final = @$_GET["fecha2"];
$orden = @$_GET["ordenado_por"];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");
       
$pos=POS;

$sql="SELECT item.descripcion1 as NAME,
  item.codigo_barras as CODE,
  factura_detalle._item_preciosiniva AS PRICESELL,
  sum(factura_detalle._item_cantidad) as UNITS,
  factura.fecha_creacion as DATENEW,
  factura_detalle._item_piva
FROM item 
INNER JOIN
  factura_detalle ON item.id_item = factura_detalle.id_item 
INNER JOIN
  factura ON factura_detalle.id_factura = factura.id_factura   
WHERE factura.fecha_creacion BETWEEN '{$inicio} 00:00:00' AND '{$final} 23:59:59'
GROUP BY item.codigo_barras,factura_detalle._item_preciosiniva
ORDER BY item.descripcion1";
//echo $sql; exit();
$array_factura = $comunes->ObtenerFilasBySqlSelect($sql);

$pdf = new PDF('L', 'mm', 'A4');

$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle('Ventas por Productos (PYME)');
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
?>
