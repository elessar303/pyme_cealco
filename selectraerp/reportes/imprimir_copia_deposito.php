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
$id_deposito=$_GET['codigo'];
$sql="SELECT * FROM $bd_pyme.parametros, $bd_pyme.parametros_generales";
$consulta=mysql_query($sql) or die (mysql_error());
$datosgenerales=mysql_fetch_array($consulta);

$sql="SELECT SUM(monto) as monto FROM $bd_pyme.caja_principal WHERE id_deposito='".$id_deposito."'";
$consulta1=mysql_query($sql);
$regs1=mysql_fetch_array($consulta1);
$monto_dep=$regs1[0];
$monto_dep_format=number_format($monto_dep,2,'.', '');


$sql=
    "SELECT nro_deposito, monto, monto_acumulado, fecha_deposito, c.nro_cuenta as cuenta, banco.descripcion as banco 
    FROM $bd_pyme.caja_principal, $bd_pyme.banco, $bd_pyme.cuentas_contables as c 
    WHERE c.banco=banco.cod_banco AND caja_principal.cod_banco = c.nro_cuenta AND id_deposito='".$id_deposito."'";

$consulta=mysql_query($sql);
$datosdeposito=mysql_fetch_array($consulta);

$sql=
    "(SELECT a.efectivo as monto_caja, b.monto as monto_efectivo,  c.NAME as host, a.fecha_venta_fin as fecha 
    FROM $bd_pyme.arqueo_cajero a, $bd_pyme.caja_principal b, ".POS.".people as c 
    WHERE  (a.id_deposito = b.nro_deposito or a.id_deposito2 = b.nro_deposito) AND b.id_deposito='".$id_deposito."' and a.id_cajero=c.ID)

    union all

    (SELECT a.efectivo as monto_caja, b.monto as monto_efectivo,  c.nombreyapellido as host, a.fecha_venta_fin as fecha 
    FROM $bd_pyme.arqueo_cajero a, $bd_pyme.caja_principal b, $bd_pyme.usuarios as c 
    WHERE  (a.id_deposito = b.nro_deposito or a.id_deposito2 = b.nro_deposito) AND b.id_deposito='".$id_deposito."' and a.id_cajero=c.cod_usuario)
    ";
$consulta2=mysql_query($sql);
require('../fpdf/rotacion.php');
class PDF extends PDF_Rotate
{

    function Header()
	{
        //Put the watermark
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        $this->RotatedText(20,200,'Copia de Planilla Transferencia',45);
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
        $this->Cell(0, 0, utf8_decode("TRANSFERENCIA A CAJA PRINCIPAL"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(40, 75, 20 ,40));
        $this->SetAligns(array("C", "C", "C", "C"));
         $this->Row(array( utf8_decode('Nro Transferencia'), 'Monto', 'Monto Acumulado','Fecha'), 1);       
		$this->SetWidths(array(40, 75, 20, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->Row(array($this->datoscampos["nro_deposito"], number_format($this->datoscampos["monto"],2,'.', ''),number_format($this->datoscampos["monto_acumulado"],2,'.', ''), date_format(date_create($this->datoscampos["fecha_deposito"]), 'd-m-Y')), 1);
        
	}

	function ChapterBody() {


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
    function RotatedText($x, $y, $txt, $angle){
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
    }
}

$pdf = new PDF();
$pdf->DatosGenerales($datosgenerales);
$pdf->DatosCampos($datosdeposito);
$pdf->DatosDetalle($datosdetalle);
$pdf->SetTitle('Planilla de Transferencia'); 
$pdf->AliasNbPages();
$pdf->PrintChapter();
while($resultado = mysql_fetch_array($consulta2))
{
    $monto_caja_format=number_format($resultado['monto_efectivo'],2,'.', '');
    $pdf->SetFont('Arial', '', 10);
    $monto_dep_format+=$monto_caja_format;
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(135,5,'TOTAL',1,0,'C');
$pdf->Cell(40,5,$monto_caja_format,1,0,'R');
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output('Copia Planilla de Transferencia.pdf','I');
ob_end_flush();
?>