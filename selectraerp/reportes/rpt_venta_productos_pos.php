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
        $this->Cell(0, 0, utf8_decode("REPORTE DE PRODUCTOS VENDIDOS"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->Cell(0, 0, @$_GET["fecha"] != @$_GET["fecha2"] ? "Desde {$fecha_ini} Hasta {$fecha_fin}" : $fecha_ini, 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetLineWidth(0.1);
        /*$this->SetWidths(array(20, 95, 15, 20, 20, 20));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C"));
        $this->Row(array('Codigo', 'Descripcion', 'Unid.', 'Sub-Total', $this->datosgenerales[0]["nombre_impuesto_principal"], 'Total'), 1);
        */
        $this->SetWidths(array(35, 90, 20, 20, 20,20,20,20));
        $this->SetAligns(array("C", "C", "C", "C", "C","C","C", "C"));
        $this->Row(array( 'Cod. Barras', 'Descripcion', 'U. Vendidas','Precio S/Iva', 'IVA', 'Precio C/Iva', 'Subtotal','Total'), 1);
        
		  $this->SetWidths(array(35, 90, 20, 20, 20,20,20,20));
        $this->SetAligns(array("C", "L", "R", "R","C","R","R","R"));
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
        $this->SetFont('Arial', '', 8);
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

       /* while ($this->array_factura[$i]) {
            #$item_descripcion[$this->array_factura[$i]["id_item"]] = $this->array_factura[$i]["descripcion1"];
            $item_descripcion[$this->array_factura[$i]["id_item"]] = $this->array_factura[$i]["NAME"];
            $item_cod[$this->array_factura[$i]["id_item"]] = $this->array_factura[$i]["REFERENCE"];
            #$item_unidades[$this->array_factura[$i]["id_item"]] += $this->array_factura[$i]["_item_cantidad"];
            $item_unidades[$this->array_factura[$i]["id_item"]] += $this->array_factura[$i]["UNITS"];
            #$item_totales[$this->array_factura[$i]["id_item"]] += $this->array_factura[$i]["_item_piva"]+$this->array_factura[$i]["_item_totalsiniva"];
           // $item_iva[$this->array_factura[$i]["id_item"]] += $this->array_factura[$i]["totalconiva"] > 0 ? $this->array_factura[$i]["totalconiva"] - $this->array_factura[$i]["totalsiniva"] : $this->array_factura[$i]["totalconiva"];
            //$item_subtotales[$this->array_factura[$i]["id_item"]] += $this->array_factura[$i]["totalsiniva"];
            #$item_totales[$this->array_factura[$i]["id_item"]] += $this->array_factura[$i]["totaliva"]+$this->array_factura[$i]["totalsiniva"];
            $i++;
        }

        foreach ($item_descripcion as $id => $descripcion) {
            $this->SetFont('Arial', '', 10);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(20, 95, 15, 20, 20, 20));
            $this->SetAligns(array("C", "L", "R", "R", "R", "R"));
            $this->Row(array(
                $item_cod[$id],
                $descripcion,
                $item_unidades[$id]),1);//,
               // number_format($item_subtotales[$id], 2, ',', '.'),
               // number_format($item_iva[$id], 2, ',', '.'),
                #number_format($item_totales[$id], 2, ',', '.')), 1);
               // number_format($item_subtotales[$id] + $item_iva[$id], 2, ',', '.')), 1);
            $total_iva += $item_iva[$id];
            $subtotal += $item_subtotales[$id];
            #$total_venta += $item_totales[$id];
        }*/
        $precio =0;
        $precio2 =0;
        $subtotal =0;
        $total =0;
        $unid =0;
        $i=0;
        foreach ($this->array_factura as $id => $reg) {
            $coniva=$reg["PRICESELL"]+$reg["RATE"]*$reg["PRICESELL"];
            $iva=$reg["RATE"]*100;
            $this->SetFont('Arial', '', 8);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(35, 90, 20, 20, 20,20,20,20));
            $this->SetAligns(array("C", "L", "R", "R","C","R","R","R"));
            $this->Row(array($reg["CODE"],$reg["NAME"],number_format($reg["UNITS"], 2, ',', '.'),number_format($reg["PRICESELL"], 2, ',', '.'),$iva."%" ,number_format($coniva, 2, ',', '.'),number_format($reg["PRICESELL"]*$reg["UNITS"], 2, ',', '.'),number_format($coniva*$reg["UNITS"], 2, ',', '.') ),1);
            $precio += $reg["PRICESELL"];
            $precio2 += $coniva;
            $unid += $reg["UNITS"];
            $subtotal+=$reg["PRICESELL"]*$reg["UNITS"];
            $total+=$coniva*$reg["UNITS"];
            $i++;
            #$total_venta += $item_totales[$id];
        }
        #$total_venta = number_format($total_venta, 2, ',', '.');
        
        $precios = number_format($precio, 2, ',', '.');
        $precios2 = number_format($precio2, 2, ',', '.');
        $subtotal = number_format($subtotal, 2, ',', '.');
        $total = number_format($total, 2, ',', '.');

        $unids = number_format($unid, 2, ',', '.');

        $this->Ln(1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetWidths(array(35,90, 20, 20,20, 20,20, 20));
        $this->SetAligns(array("C","C", "R", "R","R", "R","R", "R"));
        $this->Row(array($i, "T O T A L E S", $unids, $precios, "",$precios2,$subtotal,$total), 1);

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
//$filtro = @$_GET["filtrado_por"];
$orden = @$_GET["ordenado_por"];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

    /*$array_factura = $comunes->ObtenerFilasBySqlSelect("
        SELECT i.id_item, i.cod_item, i.descripcion1, fd.*
        FROM factura f
        INNER JOIN factura_detalle fd ON fd.id_factura = f.id_factura
        INNER JOIN item i ON i.id_item = fd.id_item
        WHERE f.cod_estatus = 2 AND f.fechaFactura >= '{$inicio}' AND f.fechaFactura <= '{$final}'
        ORDER BY {$filtro};");*/



    /*$array_factura = $comunes->ObtenerFilasBySqlSelect("
        SELECT i.id_item, i.cod_item, i.descripcion1 AS descripcion, SUM(fd._item_cantidad) AS totalunidades, SUM(fd._item_totalsiniva) AS totalsiniva, SUM(fd._item_totalconiva) AS totalconiva
        FROM factura f
        INNER JOIN factura_detalle fd ON fd.id_factura = f.id_factura
        INNER JOIN item i ON i.id_item = fd.id_item
        WHERE f.cod_estatus = 2 AND " . ($inicio != $final ? "f.fechaFactura >= '{$inicio}' AND f.fechaFactura <= '{$final}'" : "f.fechaFactura = '{$inicio}'") . " GROUP BY i.id_item
        ORDER BY {$filtro};");*/
        
$pos=POS;
/*echo "SELECT products.REFERENCE,
  products.NAME,
  sum(ticketlines.UNITS) as UNITS
FROM $pos.products INNER JOIN
  $pos.ticketlines ON products.ID = ticketlines.PRODUCT INNER JOIN
  $pos.tickets ON ticketlines.TICKET = tickets.ID       
WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
GROUP BY products.NAME  
ORDER BY NAME";
*/
$sql="SELECT products.REFERENCE,
  products.NAME,
  products.CODE,
  ticketlines.PRICE AS PRICESELL,
  sum(ticketlines.UNITS) as UNITS,
  taxes.RATE as RATE
FROM $pos.products 
INNER JOIN  $pos.ticketlines ON products.ID = ticketlines.PRODUCT 
INNER JOIN  $pos.tickets ON ticketlines.TICKET = tickets.ID
LEFT JOIN  $pos.taxes ON ticketlines.TAXID = taxes.ID
WHERE DATENEW BETWEEN '{$inicio} 00:00:00' AND '{$final} 23:59:59'
AND ticketlines.SOLD=1
GROUP BY products.CODE,ticketlines.PRICE, ticketlines.TAXID
ORDER BY NAME";
//echo $sql; exit();
$array_factura = $comunes->ObtenerFilasBySqlSelect($sql);

$pdf = new PDF('L', 'mm', 'A4');

$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle('Ventas por Productos (POS)');
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
?>
