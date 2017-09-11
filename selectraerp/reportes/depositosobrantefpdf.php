<?php
require_once("../libs/php/adodb5/adodb.inc.php");
include("../../general.config.inc.php");
include("../../generalp.config.inc.php");
require_once("../libs/php/configuracion/config.php");
require_once("../libs/php/clases/ConexionComun.php");
require_once("../libs/php/configuracion/config.php");
include("../../menu_sistemas/lib/common.php");
include("../libs/php/clases/producto.php");
require('../fpdf/fpdf.php');
ob_end_clean();    header("Content-Encoding: None", true);
session_start();
$bd_pyme=DB_SELECTRA_FAC;
$bd_pos=POS;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;
$con = mysql_connect($host,$user,$pass);
mysql_select_db($bd_pyme) or die('No se pudo seleccionar la base de datos');


$nro_deposito=$_GET['nro_deposito'];
$nro_cuenta=$_GET['nro_cuenta'];

// se genera los archivos no se tocará.
$nomb=$codigo_siga.'_'.$ano.$mes.$dia.'_'.$hora.$min.$seg.'.csv';
$contenido_ingresos="";

$contenido_ingresos.=$codigo_siga.",".$fecha2.",".$monto_dep.",".$nro_deposito.",".$banco.",DEPE".("\r\n");


$pf_inv=fopen($path_ingresos."/".$nomb,"w+");
fwrite($pf_inv, utf8_decode($contenido_ingresos));
fclose($pf_inv);

$sql="SELECT * FROM parametros, parametros_generales";
$consulta=mysql_query($sql);
$datosgenerales=mysql_fetch_array($consulta);

$sql="SELECT nro_deposito, monto, fecha_deposito, c.nro_cuenta as cuenta, banco.descripcion as banco FROM deposito, banco, cuentas_contables as c WHERE c.banco=banco.cod_banco AND deposito.cod_banco = c.nro_cuenta AND nro_deposito='".$nro_deposito."' and deposito.cod_banco='".$nro_cuenta."'";
//echo $sql; exit();
$consulta=mysql_query($sql);
$datosdeposito=mysql_fetch_array($consulta);

$sql="SELECT b.monto as monto_caja,  c.NAME as host, a.fecha_venta_fin as fecha FROM arqueo_cajero a, deposito b, ".POS.".people as c WHERE a.id_deposito2 = b.nro_deposito AND a.id_deposito2='".$nro_deposito."' and a.id_cajero=c.ID";
$consulta2=mysql_query($sql);



class PDF extends FPDF
{

function Header()
	{

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
        $this->Cell(0, 0, utf8_decode("PLANILLA DE DEPÓSITO"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(40, 75, 20 ,40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->Row(array( utf8_decode('Nro Depósito'), 'Cuenta - Banco', 'Monto','Fecha'), 1);        
		$this->SetWidths(array(40, 75, 20, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->Row(array($this->datoscampos["nro_deposito"], $this->datoscampos["cuenta"]."-".$this->datoscampos["banco"], number_format($this->datoscampos["monto"],2,'.', ''), $this->datoscampos["fecha_deposito"]), 1);
        //$this->Ln(10);
        /*$this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("DETALLE DEL DEPÓSITO SOBRANTE"), 0, 0, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(45, 50, 40, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->Row(array( 'Caja', 'Secuencia Cierre','Fecha', 'Monto'), 1);*/
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
}

$pdf = new PDF();
$pdf->DatosGenerales($datosgenerales);
$pdf->DatosCampos($datosdeposito);
$pdf->DatosDetalle($datosdetalle);

$pdf->SetTitle('Planilla de Deposito'); 
$pdf->AliasNbPages();
$pdf->PrintChapter();
while($resultado = mysql_fetch_array($consulta2)){
$monto_caja_format=number_format($resultado['monto_caja'],2,'.', '');
$pdf->SetFont('Arial', '', 10);
//$pdf->Cell(45,5,$resultado['host'],1,0,'C');
//$pdf->Cell(50,5,$resultado['secuencia_cierre'],1,0,'C');
//$pdf->Cell(40,5,$resultado['fecha'],1,0,'C');
//$pdf->Cell(40,5,$monto_caja_format,1,0,'R');
$monto_dep_format+=$monto_caja_format;
//$pdf->Ln(); 
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(135,5,'TOTAL ',1,0,'C');
$pdf->Cell(40,5,number_format($monto_caja_format,'2'),1,0,'R');
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output('Planilla de Deposito.pdf','I');
ob_end_flush();
?>