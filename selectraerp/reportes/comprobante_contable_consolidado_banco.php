<?php
ob_start();

include('config_reportes.php');
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
        $this->Cell(0, 0, utf8_decode("Comprobante Contable ")." #".$this->datoscampos[0]['id']." fecha:  ". $this->datoscampos[0]['fecha1']  , 0, 0, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(60, 35, 35, 35));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetX(25);
        $this->Row(array( utf8_decode('Cuenta'), 'DEBE', 'HABER', 'MOV.'), 1);
        $this->SetWidths(array(60, 35, 35, 35));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->SetX(25);
       $totalcaja=0; $totalbanco=0;
       foreach ($this->datoscampos as $key => $value) 
       {
            if(($value["caja"])>0)
            {
                $this->Row(array('CAJA', '', number_format($value["caja"],2,',', '.'), $value["tipo_mov"]),1);
                $this->SetX(25);
                $totalcaja+=$value["caja"];
            }
            if(($value["banco"])>0)
            {
                $this->Row(array('BANCO', number_format($value["banco"],2,',', '.'), '', $value["tipo_mov"]),1);
                $this->SetX(25);
                $totalbanco+=$value["banco"];
            }
            
       }
        

        $this->SetFont('Arial', 'B', 10);
        $this->SetX(25);
        $this->SetWidths(array(60, 35, 35));
        $this->SetAligns(array("C", "C", "C"));
        $this->Row(array('', 'Total', 'Total' ), 1);
        $this->SetWidths(array(60, 35, 35, 35));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetX(25);
        $this->Row(array('', number_format($totalbanco, '2', ',', '.'),number_format($totalcaja, '2', ',', '.')  ), 1);
        $this->SetX(25);
        $this->SetWidths(array(130));
        $this->SetAligns(array("L"));
        /*$this->datoscampos[0]['observacion']=str_replace('<br>', " -- ", $this->datoscampos[0]['observacion']);
        $this->datoscampos[0]['observacion']=str_replace(':', " CXC : ", $this->datoscampos[0]['observacion']);
        $this->Row(array(utf8_decode('Observación: '.$this->datoscampos[0]['observacion']) ), 1);*/
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
$inicio=date_format(date_create(@$_POST["fecha"]), 'Y-m-d');
$nro=isset($_POST["comprobante"]) ? $_POST["comprobante"] : null;
//$id_transaccion = @$_GET["id_transaccion"];
if($nro!=null)
{
    $nro=" and a.id=".$nro;
    $inicio=null;
}
else
{
    $inicio=" and date(a.fecha) between '".$inicio."' and  '".$inicio."'";
}
$comunes = new ConexionComun();
$datosgenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

//obtener la fecha de apertura
/*$fecha=$comunes->ObtenerFilasBySqlSelect("select apertura_date as fecha from apertura_tienda  order by id_apertura desc limit 1");

*/
$datoscampos=$comunes->ObtenerFilasBySqlSelect("select  date_format( a.fecha, '%d-%m-%Y' ) as fecha1, a.* from comprobante as a  where (a.banco is not null || a.banco > 0) ".$inicio." ".$nro);


$datoscamposingresos=$comunes->ObtenerFilasBySqlSelect("select * from comprobante as a where   (a.banco is not null || a.banco > 0)  ".$inicio." ".$nro." ");

//$datoscomprobante=$comunes->ObtenerFilasBySqlSelect($consulta);
$pdf = new PDF();
$pdf->DatosGenerales($datosgenerales);
$pdf->DatosCampos($datoscampos);
$pdf->DatosCamposIngresos($datoscamposingresos);
//$pdf->DatosDetalle($datosdetalle);
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
