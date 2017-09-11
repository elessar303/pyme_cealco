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

        $this->Image('../../includes/imagenes/'.$this->datosgenerales["img_izq"], 10, 8, 50, 20);
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
        $this->Cell(0, 0, utf8_decode("REPORTE DE VENDEDOR"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->Cell(0, 0, @$_GET["fecha"] != @$_GET["fecha2"] ? "Desde {$fecha_ini} Hasta {$fecha_fin}" : $fecha_ini, 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        /*$this->SetWidths(array(20, 95, 15, 20, 20, 20));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C"));
        $this->Row(array('Codigo', 'Descripcion', 'Unid.', 'Sub-Total', $this->datosgenerales[0]["nombre_impuesto_principal"], 'Total'), 1);
        */
        $this->SetWidths(array(30, 20, 80, 20, 20,20));
        $this->SetAligns(array("C", "C", "C", "C", "C","C"));
        $this->Row(array( 'NOMBRE','EFECTIVO', 'TARJETA', 'CT', 'DEV','TOTAL'), 1);
        
		  $this->SetWidths(array(30, 20, 80, 20, 20,20));
        $this->SetAligns(array("C", "C", "L", "R", "R","C"));
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
        $unid =0;
        $i=0;
        foreach ($this->array_factura as $id => $reg) {
           
    if($i==0)
        {
            $nom=$reg[NAME];
            
        }
        
        if($nom!=$reg[NAME])
        {
            $total=$efect+$tj+$ct-$dev;
            $this->SetFont('Arial', '', 10);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(30, 20, 80, 20, 20,20));
            $this->SetAligns(array("C","C", "L", "R", "R"));
            $this->Row(array($reg["NAME"],$efect,$tj,$ct,$dev,$total),1);
            $tefect+=$efect;
            $ttj+=$tj;
            $tct+=$ct;
      $tdev+=$dev;
            $ttotal+=$total;
            
            $efect=0;
            $tj=0;
            $ct=0;
        $dev=0;
            $total=0;
            $nom=$reg[NAME];

        }//fin del if




          if($reg[PAYMENT]=="cash")
        {
             $efect=$reg[TOTAL];
        }
        if($reg[PAYMENT]=="magcard")
        {
             $tj=$reg[TOTAL];
        }
       if($reg[PAYMENT]=="paperin")
        {
             $ct=$reg[TOTAL];
        }
      if(($reg[PAYMENT]=="magcardrefund")||($reg[PAYMENT]=="cashrefund"))
      {
       $dev=$reg[TOTAL]*(-1);
      }



            /*$this->SetFont('Arial', '', 10);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(30, 20, 80, 20, 20,20));
            $this->SetAligns(array("C","C", "L", "R", "R"));
            $this->Row(array($reg["NAME"],$efect,$tj,$ct,$dev,$total),1);*/
            $precio += $reg["PRICESELL"];
            $unid += $reg["UNITS"];
            $i++;
            #$total_venta += $item_totales[$id];
        }
        
        #$total_venta = number_format($total_venta, 2, ',', '.');
        
        $precios = number_format($precio, 2, ',', '.');
        $unids = number_format($unid, 2, ',', '.');

        $this->Ln(1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetWidths(array(40, 20, 90, 20, 20));
        $this->SetAligns(array("C","C", "R", "R", "R"));
        $this->Row(array($i,"", "T O T A L E S", $precios, $unids), 1);

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
 $array_factura = $comunes->ObtenerFilasBySqlSelect(" SELECT people.NAME,
      payments.PAYMENT,
      sum(payments.TOTAL) as TOTAL
    FROM $pos.payments INNER JOIN
      $pos.receipts ON receipts.ID = payments.RECEIPT INNER JOIN
      $pos.tickets ON tickets.ID = receipts.ID INNER JOIN
      $pos.people ON tickets.PERSON = people.ID       
    WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
    GROUP BY people.NAME, payments.PAYMENT  
    ORDER BY NAME");

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
