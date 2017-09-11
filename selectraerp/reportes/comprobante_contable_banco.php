<?php
ob_start();
include('config_reportes.php');
ob_end_clean();    header("Content-Encoding: None", true);
require('../fpdf/rotacion.php');
$inicio=date_format(date_create(@$_POST["fecha"]), 'Y-m-d');
$nro=isset($_POST["comprobante"]) ? $_POST["comprobante"] : null;
if($nro!=null)
{
    $nro=" and id=".$nro;
    $inicio=null;
}
else
{
    $inicio=" and date(fecha) between '".$inicio."' and  '".$inicio."'";
}

class PDF extends PDF_Rotate
{

    public $datosgenerales; public $datoscampos;
    function Header()
    {    
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(0,0,0);
        $this->Image('../../includes/imagenes/'.$this->datosgenerales[0]["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(10, 50, 100);
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, "Fecha Emision: " . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 12);
        $this->SetX(17);
        $this->Cell(0, 0, utf8_decode("Movimiento Banco #"). $this->datoscampos[0]['id']. " del ". $this->datoscampos[0]['fecha'], 0, 0, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(53, 53, 53));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetX(25);
        $this->Row(array( utf8_decode('CAJA'), 'BANCO', 'Tipo Mov.'), 1);
        $this->SetWidths(array(53, 53, 53));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->SetX(25);
        $this->Row(array(number_format($this->datoscampos[0]["caja"], '2', ',', '.'), number_format($this->datoscampos[0]["banco"], '2', ',', '.'), $this->datoscampos[0]["tipo_mov"]), 1);
        
        $this->Ln(10);
    }
    
    function RotatedText($x, $y, $txt, $angle)
    {
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

        function DatosDetalle($array) {
        $this->datosdetalle = $array;
    }
        function PrintChapter() {
        $this->AddPage();
    }
}


$comunes = new ConexionComun();
$datosgenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

$datoscampos=$comunes->ObtenerFilasBySqlSelect("select id, caja as caja, banco as banco, tipo_mov,  date_format( fecha, '%d-%m-%Y' ) as fecha from comprobante where banco is not null order by id desc limit 1");


if($datoscampos[0]['id']==null)
{
    echo 
    " 
        <script type='text/javascript'>
        alert('No se encontraron resultados');
        window.history.back(-1);
        </script>

    "; 
    exit();
}
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
$pdf->Output('Comprobante_Contable'.$fecha[0]['fecha'].'.pdf','I');
ob_end_flush();
?>
?>