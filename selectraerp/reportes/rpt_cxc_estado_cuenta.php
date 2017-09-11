<?php
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

//date("Y-m-d");
//$diass = antiguedad($fecha,$fecha2,D);

class PDF extends FPDF {
	
	public $title;
	public $conexion;
	public $datosgenerales;
	public $array_factura;
	public $fecha,$fecha2;
	
	function Header() {
		$width = 10;
		//$this->Image('../imagenes/banner_superior.jpg',10,5,190);

		$this->SetY(15);

		$comunes = new ConexionComun();

		$cliente = $_GET["cod_tipo_cliente"];
		$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from tipo_cliente where cod_tipo_cliente=".$cliente);

		$this->SetLeftMargin(15);
		$width = 5;
		#$this->SetX(55);
		$this->SetFont('Arial','B',10);
		$this->SetFillColor(10,50,100);
		//$this->Image('../imagenes/logo_selectra.jpg', 6 ,10, 45, 28,'JPG', '');
		$this->Cell(135,0,'ESTADO DE CUENTA',0,0,'L');
		$this->Ln(6);
		#$this->SetX(55);
		$this->Cell(92,0,'Tipo de Clientes: '.utf8_decode($array_parametros_generales[0]['descripcion']),0,0,'L');
		$this->Ln(6);
		#$this->SetX(55);
		$this->Cell(86,0,'Al: '.date("Y-m-d"),0,0,'L');
		$this->Ln(6);
		#$this->SetX(55);
		$this->Cell(143,0,"Periodo: ".fecha($this->fecha)." Hasta " .fecha($this->fecha2),0,0,'L');
		$this->Ln(10);
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

		$this->SetWidths(array(20,20,20,15, 10,25,20,20,20,15,15,15,15,15,15));
		$this->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C","C","C","C","C"));
		$this->SetFillColor(232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232);

		$this->SetLeftMargin(18);
		$width = 5;
		$this->SetX(8);
		$this->SetFont('Arial','B',6);

		$this->Row(array('Emision.','Presentacion','Vencimiento','Factura','Serie', 'Clave', 'Monto', 'Pagado','Saldo','Dias', '0 a 30', '31 a 45 Dias', '46 a 60 Dias ', '61 a 90 Dias', 'Mayores a 90 Dias'),1);
			


		$this->SetWidths(array(20,20,20,15, 10,25,20,20,20,15,15,15,15,15,15));
		$this->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C","C","C","C","C"));
		$this->SetFillColor(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10);

		$totalDebito=0;
		$totalCredito=0;
		$totalVentasConIva=0;
		$totalVentasNoGravadas=0;
		$totalBaseImponible=0;
		$totalIva=0;
		$totalIvaRet=0;
		$i=0;

		while($this->array_factura[$i])
		{

			$this->SetLeftMargin(18);
			$width = 5;
			$this->SetX(8);
			$this->SetFont('Arial','',6);

			$fechaActual = date("Y-m-d");
			$diass = antiguedad($this->array_factura[$i]["fecha_autorizado"],$fechaActual,'D');

			$this->Row(
			array(
			$this->array_factura[$i]["fecha_emision"],
			$this->array_factura[$i]["fechaFactura"],
			$this->array_factura[$i]["vencimiento_fecha"],
			$this->array_factura[$i]["numero"],
			$this->array_factura[$i]["serie"],
			$this->array_factura[$i]["clave"],
			number_format($this->array_factura[$i]["monto"], 2, ',', '.'),
			number_format($this->array_factura[$i]["monto_iva"], 2, ',', '.'),
			number_format($this->array_factura[$i]["0,00"], 2, ',', '.'),
			$diass,
			number_format($this->array_factura[$i][""], 2, ',', '.'),
			number_format($this->array_factura[$i][""], 2, ',', '.'),
			number_format($this->array_factura[$i]["montoItemsFactura"]+$this->array_factura[$i]["ivaTotalFactura"], 2, ',', '.'),
			number_format($this->array_factura[$i][""], 2, ',', '.'),
			number_format($this->array_factura[$i]["0"], 2, ',', '.')),1);

			$totalDebito+=0;
			$totalCredito+=0;
			$totalVentasConIva+=($this->array_factura[$i][montoItemsFactura]+$this->array_factura[$i][ivaTotalFactura]);
			$totalVentasNoGravadas+=0;
			$totalBaseImponible+=$this->array_factura[$i][totalizar_base_imponible];
			$totalIva+=$this->array_factura[$i][ivaTotalFactura];
			$totalIvaRet+=$this->array_factura[$i][totalizar_total_retencion];
			$i++;
		}
		$this->Ln(1);


		//:::::::::::::::::::::::::::::::::::::::::::AQUI VA TOTAL::::::::::::::::::::::::::::::::::::::::::::::::::::::

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

//echo $diass;
//WHERE fecha_emision BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha2"]."' GROUP BY cod_edocuenta asc
$comunes = new ConexionComun();
$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * from cxc_edocuenta WHERE fecha_autorizado BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha2"]."' GROUP BY cod_edocuenta asc ");

$pdf=new PDF('L','mm','A4');
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


