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
        $this->RotatedText(35,190,'Copia de Planilla Cataporte',45);
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
        $this->Cell(0, 0, utf8_decode("PLANILLA DE CATAPORTE (R)"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(40,40, 25, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetX(25);
        $this->Row(array( utf8_decode('Nro Cataporte'), 'Cant. Bolsas', 'Monto', 'Fecha'), 1);        
		$this->SetWidths(array(40,40, 25, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->SetX(25);
        $this->Row(array($this->datoscampos["nro_cataporte_usuario"], $this->datoscampos["cant_bolsas"], number_format($this->datoscampos["monto_usuario"],2,'.', ''), date_format(date_create($this->datoscampos["fecha_modificacion"]), 'd-m-Y')), 1);
        $this->Ln(10);
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

$j=1;
while($resultado = mysql_fetch_array($consulta1)){
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(25);
$pdf->Cell(45,5,$j,1,0,'C');
$pdf->Cell(100,5,$resultado['nro_bolsa'],1,0,'C');
$pdf->Ln(); 
$j++;
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetX(17);
//$pdf->Cell(0, 0, utf8_decode("DEPÓSITOS ASOCIADOS"), 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(25);
$pdf->SetWidths(array(35, 70 ,40));
$pdf->SetAligns(array("C", "C", "C"));
//$pdf->Row(array( utf8_decode('Nro Depósito'), 'Banco', 'Fecha'), 1);
//$sql="SELECT * FROM $bd_pyme.deposito, $bd_pyme.banco WHERE deposito.cod_banco=banco.cod_banco AND id_cataporte='".$nro."'";
$sql="SELECT nro_deposito, nro_cuenta, fecha_deposito, cuentas_contables.descripcion as descripcion, banco.descripcion as banco FROM $bd_pyme.deposito, $bd_pyme.banco, $bd_pyme.cuentas_contables, $bd_pyme.cataporte  WHERE deposito.cod_banco=cuentas_contables.nro_cuenta and banco.cod_banco=cuentas_contables.banco AND cataporte.id='".$_GET['codigo']."' and id_cataporte=nro_cataporte";
//echo $sql; exit();
$consulta3=mysql_query($sql) or die (mysql_error());
$j=1;
/*while($resultado = mysql_fetch_array($consulta3))
{
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(25);
$pdf->Cell(35,5,$resultado['nro_deposito'],1,0,'C');
$pdf->Cell(70,5,$resultado['descripcion']."-".$resultado['banco'],1,0,'C');
$pdf->Cell(40,5,$resultado['fecha_deposito'],1,0,'C');
$pdf->Ln(); 
$j++;
}*/

$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output('Planilla de Cataporte.pdf','I');
ob_end_flush();
?>
?>