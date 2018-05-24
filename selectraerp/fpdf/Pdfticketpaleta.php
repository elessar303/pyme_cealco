<?php
ob_start();

include('../reportes/config_reportes.php');
//require('fpdfselectra.php');
//include('fpdf.php');

ob_end_clean();    header("Content-Encoding: None", true);

require('../fpdf/rotacion.php');

class PDF extends PDF_Rotate
{

 public $datosgenerales; public $datoscampos;
function Header()
    {    
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        //$this->RotatedText(35,190,'Comprobante Contable ',45);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(0,0,0);
        //$this->Image('../../includes/imagenes/'.$this->datosgenerales[0]["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(10, 50, 100);
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . " " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        //$this->Cell(0, 0, "Fecha Emision: " . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 12);
        $this->SetX(17);
        $this->SetFont('Arial', '', 10);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(60, 35, 35));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetX(25);
        $this->SetWidths(array(60, 35, 35));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->SetX(25);
        $totalingreso=0;
        
        //cuerpo
        $this->SetFont('Arial', 'B', 10);
        $this->SetX(25);
        $this->SetWidths(array(40,110));
        $this->SetAligns(array("L", "L"));
        $this->Row(array('Codigo : ', $this->datoscampos[0]['codigo_barras']), 0);
        $this->SetX(25);
        $this->SetWidths(array(150));
        $this->SetAligns(array("L"));
        $this->Row(array('Producto : '), 0);
        $this->SetX(25);
        $this->SetWidths(array(150));
        $this->SetAligns(array("L"));
        $this->Row(array($this->datoscampos[0]['descripcion1']." - ".$this->datoscampos[0]['marca']), 0);
        $this->SetX(25);
        $this->Row(array('Temperatura : '.$this->datoscampos[0]['temperatura'].utf8_decode(' °C')), 0);
        $this->SetLineWidth(0.60);
        $this->Line($this->GetX()+10, $this->GetY(), 140, $this->GetY());
        $this->SetX(25);
        $this->SetWidths(array(40, 110));
        $this->SetAligns(array("C", "L"));
        $this->Row(array('Etiq.', $this->datoscampos[0]['etiqueta']), 0);
        $this->AddFont('New','','free3of9.php');
        $this->SetFont("New", '', 20);
        $this->SetX(25);
        $this->SetWidths(array(120));
        $this->SetAligns(array("C"));
        $this->Row(array("*".$this->datoscampos[0]['codigo_barras']."*"), 0);
        $this->SetFont('Arial', 'B', 10);
        $this->SetX(25);
        $this->SetWidths(array(150));
        $this->SetAligns(array("L"));
        $this->Row(array('Cliente : '. $this->datoscampos[0]['proveedor']), 0);
        //$this->Line($this->GetX()+10, $this->GetY(), 140, $this->GetY());
        $this->SetX(25);
        $this->Row(array('RIF : '. $this->datoscampos[0]['rif']), 0);
        $this->Line($this->GetX()+10, $this->GetY(), 140, $this->GetY());
        $this->SetX(25);
        $this->SetWidths(array(45, 45, 45));
        $this->SetAligns(array("L", "L", "L"));
        $this->Row(array('Fecha Recepcion : ','Fecha Vencimiento:', '# Recepcion: '), 0);
        $this->SetX(25);
        $this->SetWidths(array(45, 45, 45));
        $this->SetAligns(array("L", "L", "L"));
        $this->Row(array($this->datoscampos[0]['fecha_recepcion'], $this->datoscampos[0]['vencimiento'],  $this->datoscampos[0]['nro_recepcion'] ), 0);
        $this->Line($this->GetX()+10, $this->GetY(), 140, $this->GetY());
        $this->SetX(25);
        $this->SetWidths(array(50, 50, 50));
        $this->SetAligns(array("L", "L", "L"));
        $this->Row(array('Lote', 'Cantidad U/M', 'Peso' ), 0);
        $this->SetX(25);
        $this->SetWidths(array(50, 50, 50));
        $this->SetAligns(array("L", "L", "L"));
        $this->Row(array($this->datoscampos[0]['lote'], $this->datoscampos[0]['cantidad']." - ".$this->datoscampos[0]['presentacion'], $this->datoscampos[0]['peso'] ), 0);
        $this->Line($this->GetX()+10, $this->GetY(), 140, $this->GetY());
        $this->SetX(25);
        $this->SetWidths(array(150));
        $this->SetAligns(array("L"));
        $this->Row(array('Observaciones'), 0);
        $this->SetX(25);
        $this->SetWidths(array(150));
        $this->SetAligns(array("L"));
        $this->Row(array($this->datoscampos[0]['observacion']), 0);
        $this->Line($this->GetX()+10, $this->GetY(), 140, $this->GetY());
        $this->Ln(10);
    }

    function ChapterBody() {


    }
    
    function RotatedText($x, $y, $txt, $angle){
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
    }
    function Footer()
    {
    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial','I',8);
    // Print centered page number
    $this->Cell(0,10,'Nro '.$this->PageNo(),0,0,'C');
    }

    function DatosGenerales($array) {
        $this->datosgenerales = $array;
    }

    function DatosCampos($array) {
        $this->datoscampos = $array;
    }

    function DatosCamposIngresos($array) {
        $this->datoscamposingresos = $array;
    }

        function DatosDetalle($array) {
        $this->datosdetalle = $array;
    }
        function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }
}
$nro=isset($_GET["id"]) ? $_GET["id"] : null;

$comunes = new ConexionComun();
$datosgenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
//b.cantidad*pesoxunidad
$sql = 
"
    select d.rif, c.codigo_barras, c.descripcion1, b.etiqueta,  d.nombre as proveedor, date_format(a.fecha_creacion, '%d/%m/%Y') as fecha_recepcion, n.nombre_unidad as presentacion,
    b.id_transaccion_detalle as nro_recepcion, b.lote, b.cantidad, b.peso, b.observacion, m.marca, date_format(b.vencimiento, '%d/%m/%Y') as vencimiento, b.temperatura as temperatura
    from kardex_almacen as a
    inner join kardex_almacen_detalle as b on a.id_transaccion=b.id_transaccion
    left join marca as m on m.id=b.id_marca
    left join unidad_empaque as n on n.id=b.id_presentacion
    inner join item as c on c.id_item=b.id_item
    inner join clientes as d  on a.id_proveedor=d.id_cliente
    where b.id_transaccion_detalle=".$nro;
//echo $sql; exit();
$datoscampos=$comunes->ObtenerFilasBySqlSelect($sql);
$pdf = new PDF();
$pdf->DatosGenerales($datosgenerales);
$pdf->DatosCampos($datoscampos);
$pdf->SetTitle('Comprobante Contable'); 
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetX(17);


$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output('Comprobante_Contable'.$_POST["fecha"].'.pdf','I');
ob_end_flush();
?>
