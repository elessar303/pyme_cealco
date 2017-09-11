<?php
ob_start();
include("../../general.config.inc.php");
include("../../generalp.config.inc.php");
ob_end_clean();    header("Content-Encoding: None", true);
session_start();
$bd_pyme=DB_SELECTRA_FAC;
$bd_pos=POS;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;
$con = mysql_connect($host,$user,$pass);
mysql_select_db($bd_pyme) or die('No se pudo seleccionar la base de datos');
$nro_cataporte=$_GET['codigo'];
session_start();

if(strpos($nro_cataporte, '_')!== false)
{
    $nro_cataporte=substr($nro_cataporte, 0, -1);
}
$sql="SELECT * FROM $bd_pyme.parametros, $bd_pyme.parametros_generales";
$consulta=mysql_query($sql);
$datosgenerales=mysql_fetch_array($consulta);

$sql="SELECT * FROM $bd_pyme.cataporte WHERE id=".$nro_cataporte."";
$consulta=mysql_query($sql);
$datoscataporte=mysql_fetch_array($consulta);
$nro=$datoscataporte['nro_cataporte'];
$sql="SELECT * FROM $bd_pyme.bolsas_cataporte WHERE nro_cataporte='".$nro."'";
$consulta1=mysql_query($sql);

require('../fpdf/rotacion.php');

class PDF extends PDF_Rotate
{

function Header()
	{    
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        if(strpos($_GET['codigo'], '_')=== false)
        {
            $this->RotatedText(35,190,'Copia de Planilla Cataporte',45);
        }
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(0,0,0);
        $this->Image('../../includes/imagenes/'.$this->datosgenerales["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(10, 50, 100);
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales["id_fiscal"] . ": " . $this->datosgenerales["rif"] . " - Telefonos: " . $this->datosgenerales["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, "Fecha Emision: " . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(17);
        //si es efectivo o tickets
        if($this->datoscampos["tipo_cataporte"]==0 || $this->datoscampos["tipo_cataporte"]==1)
        {
            $this->Cell(0, 0, utf8_decode("PLANILLA DE CATAPORTE"), 0, 0, 'C');
        }
        else
        {
            $this->Cell(0, 0, utf8_decode("PLANILLA DE RETIRO"), 0, 0, 'C');   
        }
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(40,40, 25, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetX(25);
        //si es efectivo o tickets
        if($this->datoscampos["tipo_cataporte"]==0 || $this->datoscampos["tipo_cataporte"]==1)
        {
            $this->Row(array( utf8_decode('Nro Cataporte'), 'Cant. Bolsas', 'Monto', 'Fecha'), 1);
        }
        else
        {
            $this->Row(array( utf8_decode('Nro Retiro'), 'Monto', 'Fecha'), 1);   
        }
		$this->SetWidths(array(40,40, 25, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->SetX(25);
        if($this->datoscampos["monto_usuario"]!=0)
        {
            $this->datoscampos["monto"]=$this->datoscampos["monto_usuario"];
        }
        //si es efectivo o tickets
        if($this->datoscampos["tipo_cataporte"]==0 || $this->datoscampos["tipo_cataporte"]==1)
        {
            $this->Row(array($this->datoscampos["nro_cataporte"], $this->datoscampos["cant_bolsas"], number_format($this->datoscampos["monto"],2,'.', ''), date_format(date_create($this->datoscampos["fecha"]), 'd-m-Y')), 1);
        }
        else
        {
            $this->Row(array($this->datoscampos["nro_cataporte"],  number_format($this->datoscampos["monto"],2,'.', ''), date_format(date_create($this->datoscampos["fecha"]), 'd-m-Y')), 1);   
        }
        $this->Ln(10);
        if($this->datoscampos["tipo_cataporte"]==0 || $this->datoscampos["tipo_cataporte"]==1)
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 0, utf8_decode("DETALLE DEL CATAPORTE"), 0, 0, 'C');
            $this->Ln(5);
            $this->SetFont('Arial', 'B', 10);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(45, 100));
            $this->SetAligns(array("C", "C"));
            $this->SetX(25);
            $this->Row(array( 'Nro', 'Bolsa'), 1);
        }
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

        function DatosDetalle($array) {
        $this->datosdetalle = $array;
    }
        function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }
}

$pdf = new PDF();
$pdf->DatosGenerales($datosgenerales);
$pdf->DatosCampos($datoscataporte);
$pdf->DatosDetalle($datosdetalle);
$pdf->SetTitle('Planilla de Deposito'); 
$pdf->AliasNbPages();
$pdf->PrintChapter();
if($datoscampos["tipo_cataporte"]==0 || $datoscampos["tipo_cataporte"]==1)
{
    $j=1;
    while($resultado = mysql_fetch_array($consulta1))
    {
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetX(25);
        $pdf->Cell(45,5,$j,1,0,'C');
        $pdf->Cell(100,5,$resultado['nro_bolsa'],1,0,'C');
        $pdf->Ln(); 
        $j++;
    }
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetX(17);


$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output('Planilla de Cataporte.pdf','I');
ob_end_flush();
?>
?>