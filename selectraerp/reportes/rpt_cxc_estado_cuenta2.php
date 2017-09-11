<?php
/** 
 * @author Charli Vivenes
 * @filesource rpt_cxc_estado_cuenta2.php
 * e-mail: cvivenes@asys.com.ve - cjvrinf@gmail.com
 **/

include('config_reportes.php');
include('fpdf.php');
#include('../fpdf/fpdfselectra.php');
include('../../menu_sistemas/lib/common.php');

class PDF extends FPDF {

	public $title;
	public $conexion;
	public $datosgenerales;
	public $array_factura;
	public $fecha,$fecha2;

	function Header(){
		$comunes = new ConexionComun();
		$cliente = $comunes->ObtenerFilasBySqlSelect("select * from clientes where id_cliente =".$_GET["id_cliente"]);
		$parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
		#$this->Image('../imagenes/logo.jpg', 18 ,10, 45, 15);
		$this->Image($parametros_generales[0]['imagen_izq'], 18 ,10, 45, 15);
		$this->SetLeftMargin(18);
		$this->SetFont('Arial','B',8);
		$this->Ln(3);$this->Cell(0, 0, utf8_decode($parametros_generales[0]['nombre_empresa']), 0, 0, "C");
		$this->Ln(3);$this->Cell(0, 0, utf8_decode($parametros_generales[0]['direccion']), 0, 0, "C");
		$this->Ln(3);$this->Cell(0, 0, $parametros_generales[0]['id_fiscal'] . ": " . utf8_decode($parametros_generales[0]['rif']) . " - Telefonos: " . utf8_decode($parametros_generales[0]['telefonos']), 0, 0, "C");
		$this->SetFont('Arial','B',10);
		$this->Ln(10);$this->Cell(0, 0, 'E S T A D O  D E  C U E N T A  A L  '.date("d-m-Y"), 0, 0, "C");
		#$this->SetY(30);
		$this->SetLeftMargin(18);$this->Ln(10);$this->SetFont('Arial','B',9);
		#$this->Ln(10);$this->Cell(92,0,'Cliente: '.utf8_decode($cliente[0]['nombre']),0,0,'L');
		#$this->Ln(3);$this->Cell(92,0,'CI/RIF: '.utf8_decode($cliente[0]['rif']),0,0,'L');
		$this->SetWidths(array(20,100,20,35));$this->SetAligns(array("L","L","L","R"));
		$this->Row(array('Cliente:',utf8_decode($cliente[0]['nombre']),"CI/RIF:",utf8_decode($cliente[0]['rif'])));
		$this->SetWidths(array(20,100,20,35/*20,50,20,85*/));$this->SetAligns(array("L","L","L","R"));
		$this->Row(array("Direccion:",utf8_decode($cliente[0]['direccion']),'Telefonos:',$cliente[0]['telefonos']));
		$this->Ln(3);
	}

	function Footer() {
		$this->SetY(-15);
		$this->SetFont('Arial','I',10);
		$this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
	}

	function dwawCell($title,$data) {
		$width = 8;
		$this->SetFont('Arial','B',12);
		$y =  $this->getY() * 20;
		$x =  $this->getX();
		$this->SetFillColor(206,230,100);
		$this->MultiCell(175,8,$title,0,1,'L',0);
		$this->SetY($y);
		$this->SetFont('Arial','',12);
		$this->SetFillColor(206,230,172);
		$w=$this->GetStringWidth($title)+3;
		$this->SetX($x+$w);
		$this->SetFillColor(206,230,172);
		$this->MultiCell(175,8,$data,0,1,'J',0);
	}

	function ChapterBody() {
		$this->SetLeftMargin(18);
		$this->SetFont('Arial','B',9);
		$this->SetWidths(array(30,25,25,20,25,25,25));
		$this->SetAligns(array("C","C","C","C","C","C","C"));		
		$this->Row(array('Tipo','Emisión','Vencimiento','Número','Débito', 'Crédito', 'Saldo'),1);
		$this->SetWidths(array(30,25,25,20,25,25,25));
		
		$totalDebito=$totalCredito=$suma_saldo=$i=0;

		while($this->array_factura[$i]){
			$this->SetFont('Arial','',9);

			$fechaActual = date("Y-m-d");
			$diass = antiguedad($this->array_factura[$i]["fecha_autorizado"],$fechaActual,'D');

			$saldo=$this->array_factura[$i]["debito"]>0?$this->array_factura[$i]["debito"]:0;
				
			$this->SetAligns(array($this->array_factura[$i]["debito"]>0?"L":"R","C","C","L","R","R","R"));
			$this->Row(array(
			$this->array_factura[$i]["debito"]>0?$this->array_factura[$i]["documento_cc"]:$this->array_factura[$i]["documento_cdet"],
			$this->array_factura[$i]["fecha_emision"],
			$this->array_factura[$i]["vencimiento_fecha"],
			$this->array_factura[$i]["debito"]>0?$this->array_factura[$i]["numero_cc"]:$this->array_factura[$i]["numero"],
			$this->array_factura[$i]["debito"]>0?number_format($this->array_factura[$i]["debito"], 2, ',', '.'):"",
			$this->array_factura[$i]["credito"]>0?number_format($this->array_factura[$i]["credito"], 2, ',', '.'):"",
			number_format($saldo, 2, ',', '.')));

			$totalDebito+=$this->array_factura[$i]["debito"];
			$totalCredito+=$this->array_factura[$i]["credito"];
			$suma_saldo+=$saldo;
			$i++;
		}
		$this->SetFont('Arial','B',9);
		$this->SetWidths(array(100,25,25,25));$this->SetAligns(array("L","R","R","R"));
		$this->Row(array('Saldo Movimiento:',number_format($totalDebito,2,',','.'), number_format($totalCredito,2,',','.'), number_format($saldo_cliente = $totalDebito-$totalCredito,2,',','.')),1);
		$this->SetWidths(array(125,50));$this->SetAligns(array("L","R"));
		$this->Row(array('Anticipo:',number_format($anticipo,2,',','.')),1);
		$this->SetWidths(array(125,50));$this->SetAligns(array("L","R"));
		$this->Row(array('Saldo Cliente:',number_format($saldo_cliente-$anticipo,2,',','.')),1);
	}

	function ChapterTitle($num,$label) {
		$this->SetFont('Arial','',10);
		$this->SetFillColor(200,220,255);
		$this->Cell(0,6,"$label",0,1,'L',1);
		$this->Ln(8);
	}

	function SetTitle($title) {
		$this->title   = $title;
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

fecha_sql($fecha = @$_GET["fecha"]);
fecha_sql($fecha2= @$_GET["fecha2"]);

//echo $cliente;

$comunes = new ConexionComun();

//echo ("select * from clientes where id_cliente =".$cliente);
//WHERE fecha_emision BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha2"]."' GROUP BY cod_edocuenta asc

$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM vw_cxc WHERE id_cliente=".$_GET["id_cliente"]);
$pdf=new PDF('P','mm','A4');
$title='Estado de Cuenta .';
$pdf->fecha=$fecha;
$pdf->fecha2=$fecha2;
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();

?>