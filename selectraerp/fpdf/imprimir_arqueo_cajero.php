<?php

include('config_reportes.php');
require('../fpdf/rotacion.php');
//include('fpdf.php');
ob_end_clean();    header("Content-Encoding: None", true);
include('../../menu_sistemas/lib/common.php');
include('../../menu_sistemas/lib/NumberToLetterConverter.php');
//include('../../menu_sistemas/lib/numerosAletras.class.php');

class PDF extends PDF_Rotate {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $array_facturas_devueltas;

    function Header() {
        if(!isset($_POST['original'])){
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        $this->RotatedText(35,150,'Copia de Arqueo',45);
        }
        $this->Image('../../includes/imagenes/'.$this->datosgenerales[0]["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        #$width = 5;
        #$this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
         $this->SetTextColor(0,0,0);
        #$this->SetFillColor(10, 50, 100);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode("Fecha Emision: ") . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetX(14);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("ARQUEO DE CAJERO:  - ".$this->array_factura[0]['NAME']), 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, utf8_decode("FECHA DE ARQUEO:  ".$this->array_factura[0]['fecha_arqueo']), 0, 0, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', '', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(150));
        $this->SetAligns(array("C"));
        $this->SetFillColor(150, 150, 150);
        #$this->SetFillColor(232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232);
        
        $this->cell(150,5,'Efectivos Y Monedas',1,1,'C',1);

        $this->SetWidths(array(50,50,50));
        $this->SetAligns(array("C","C","C"));
        #$this->SetFillColor(232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232);
        $this->Row(array(utf8_decode('Denominación En Billetes'),'Cantidad','Total'), 1);

    }
    function RotatedText($x, $y, $txt, $angle){
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
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

    function ChapterBody() {

    $i=0;        
        while ($this->array_factura[$i]) {
            //print_r($this->array_factura); exit();
            $date = date_create($this->array_factura[$i]["fecha_emision"]);
            $fecha=date_format($date, 'd-m-Y'); 
            $width = 5;
            $this->SetFont('Arial', '', 8);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(50, 50, 50,50));
            $this->SetAligns(array("C", "C", "C","C"));
            //$total_efectivo_cajero=($this->array_factura[$i]['billetes_100']*100)
            $this->SetFillColor(0, 0, 255);
            $this->Row(array(
                '100',number_format($this->array_factura[$i]['billetes_100'],'0'),number_format($this->array_factura[$i]['billetes_100']*100,'2',',','.'),
            utf8_decode('Efectivo Según Sistema')),1);

            $this->Row(array(
                '50',number_format($this->array_factura[$i]['billetes_50'],'0'),number_format($this->array_factura[$i]['billetes_50']*50,'2',',','.'),$this->array_factura[$i]['total_efectivo_sistema']),1);
            $this->Row(array(
                '20',number_format($this->array_factura[$i]['billetes_20'],'0'),number_format($this->array_factura[$i]['billetes_20']*20,'2',',','.'),'Diferencia'),1);
            $this->Row(array(
                '10',number_format($this->array_factura[$i]['billetes_10'],'0'),number_format($this->array_factura[$i]['billetes_10']*10,'2',',','.'),$this->array_factura[$i]['efectivo']-$this->array_factura[$i]['total_efectivo_sistema']),1);
            $this->Row(array(
                '5',number_format($this->array_factura[$i]['billetes_5'],'0'),number_format($this->array_factura[$i]['billetes_5']*5,'2',',','.')),1);
            $this->Row(array(
                '2',number_format($this->array_factura[$i]['billetes_2'],'0'),number_format($this->array_factura[$i]['billetes_2']*2,'2',',','.')),1);
            $this->Row(array(
                'Total Monedas',number_format($this->array_factura[$i]['monedas'],'0'),number_format($this->array_factura[$i]['monedas'],'2',',','.')),1);
            $this->SetAligns(array("L", "C", "C","C"));
            $this->Row(array(
                '','Total',$this->array_factura[$i]['efectivo']),1);
            $this->SetWidths(array(50,50,50,50));
            $this->SetAligns(array("C", "C","C","C"));
              $this->SetFillColor(150, 150, 150);
             $this->Row(array(
                'Tarjeta Recibido: ',$this->array_factura[$i]['tarjeta'],'Tarjeta Segun Sistema:',$this->array_factura[0]['total_tj_sistema']),1);
            $this->SetWidths(array(100,100));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                'Diferencia Tarjeta ',number_format($this->array_factura[$i]['tarjeta']-$this->array_factura[$i]['total_tj_sistema'],2)),1);
             $this->SetWidths(array(50,50,50,50));
            $this->SetAligns(array("C", "C","C","C"));
             $this->Row(array(
                'Tickets Alimentacino Recibido: ',$this->array_factura[$i]['tickets'],'Tickets Alimentacion Segun Reporte:',$this->array_factura[0]['total_tickets_sistema']),1);
            $this->SetWidths(array(100,100));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                'Diferencia Tickets ',number_format($this->array_factura[$i]['tickets']-$this->array_factura[$i]['total_tickets_sistema'],2)),1);
            $this->Row(array(
                'Cierre Cajero '.$this->array_factura[$i]['NAME'],'PDVAL'),1);
            $this->SetWidths(array(50,50,50,50));
            $this->SetAligns(array("C", "C","C","C"));
             $this->Row(array(
                'Nombre: ',$this->array_factura[$i]['NAME'],'Nombre:',$this->array_factura[0]['nombreyapellido']),1);
             $this->Row(array(
                'C:I: ','','C.I:',''),1);
             $this->SetWidths(array(100,50,50));
             $this->Row(array(
                'Conformes ','Total Recibido',number_format($this->array_factura[$i]['efectivo']+$this->array_factura[$i]['tickets']+$this->array_factura[$i]['tarjeta'],2)),1);
              $this->SetWidths(array(50,50,50,50));
             $this->Row(array(
                'Cajero Operativo','Finanzas','Total Ventas Segun Sistema',number_format(($this->array_factura[$i]['total_efectivo_sistema']+$this->array_factura[$i]['total_tj_sistema']+$this->array_factura[$i]['total_tickets_sistema'])-$this->array_factura[$i]['total_devolucion'],2)),1);
             $this->Row(array(
                ' ',' ','Diferencia Total',number_format(($this->array_factura[$i]['efectivo']+$this->array_factura[$i]['tickets']+$this->array_factura[$i]['tarjeta'])-(($this->array_factura[$i]['total_efectivo_sistema']+$this->array_factura[$i]['total_tj_sistema']+$this->array_factura[$i]['total_tickets_sistema'])-($this->array_factura[$i]['total_devolucion'])),2)),1);
             $this->SetWidths(array(200));
             $this->SetAligns(array("L"));
             //$letras= new numerosAletras();
             //$letras=new NumberToLetterConverter(); //convertNumber($number, $miMoneda
             $this->Row(array(
                //'Cantidad en Letras:'.numtoletras(number_format(($this->array_factura[$i]['total_efectivo_sistema']+$this->array_factura[$i]['total_tj_sistema']+$this->array_factura[$i]['total_tickets_sistema'])-$this->array_factura[$i]['total_devolucion'],2))),1);
               'Cantidad en Letras:'.numtoletras(number_format($this->array_factura[$i]['efectivo']+$this->array_factura[$i]['tarjeta']+$this->array_factura[$i]['tickets']+$this->array_factura[$i]['monedas'],2,'.',''))),1);
             $this->Row(array(
                utf8_decode('Observación:')),1);
                //$this->array_factura[$i]["numero_z"],
                //$this->array_factura[$i]["ultima_factura"],
                //$this->array_factura[$i]["numeros_facturas"],
                //$this->array_factura[$i]["ultima_nc"],
                //$this->array_factura[$i]["numeros_ncs"],
                //$this->array_factura[$i]["fecha"],
                //number_format($this->array_factura[$i]["monto_bruto"], 2, ',', '.'),
                //number_format($this->array_factura[$i]["monto_exento"], 2, ',', '.'),
                //number_format($this->array_factura[$i]["base_imponible"], 2, ',', '.'),
                //number_format($this->array_factura[$i]["iva"], 2, ',', '.'),
                //$this->array_factura[$i]["usuario"],
                //$this->array_factura[$i]["caja_host"],$fecha),1);
            
            $i++;
        }
        /* #$totalDebito = number_format($totalDebito, 2, ',', '.');
          #$totalCredito = number_format($totalCredito, 2, ',', '.');
          $totalVentasConIva = number_format($totalVentasConIva, 2, ',', '.');
          $totalVentasNoGravadas = number_format($totalExento, 2, ',', '.');
          $totalBaseImponible = number_format($totalBaseImponible, 2, ',', '.');
          $totalGravado = number_format($totalGravado, 2, ',', '.');
          $totalIva = number_format($totalIva, 2, ',', '.');
          $totalIvaRet = number_format($totalIvaRet, 2, ',', '.'); */

        
        
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

    function ArrayFacturasDevueltas($array) {
        $this->array_facturas_devueltas = $array;
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

}

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

/*
  $fecha = @$_GET["fecha"] . "-01";
  $array_factura = $comunes->ObtenerFilasBySqlSelect(
  "SELECT f.*, c.rif, c.nombre, c.direccion, c.nit, c.cod_cliente, c.telefonos, c.direccion
  FROM factura f
  INNER JOIN clientes c ON c.id_cliente = f.id_cliente
  WHERE month(f.fechaFactura) = month('{$fecha}') AND year(f.fechaFactura) = year('{$fecha}')
  ORDER BY f.id_factura;"); */

if(isset($_POST['id'])){
     $_GET['id']=$_POST['id'];
}
#echo $desde . "->" . $hasta;exit;
$array_factura = $comunes->ObtenerFilasBySqlSelect(
        "select * from arqueo_cajero as a, ".POS.".people as b, usuarios as c where a.cod_usuario=c.cod_usuario and a.id_cajero=b.id 
         and a.id='".$_GET['id']."'");


$array_facturas_devueltas = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_devolucion");

#$mes = mesaletras(substr($fecha, 5, 2));

$pdf = new PDF('L', 'mm', 'A4');
$title = 'Arqueo De Cajero #'.$_GET['id']."Cajero:".$array_factura[0]['NAME'];

$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->ArrayFacturasDevueltas($array_facturas_devueltas);

$pdf->SetTitle($title);
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
$comunes->cerrar();
?>
