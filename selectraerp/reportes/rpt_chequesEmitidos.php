<?php
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');




class PDF extends FPDF {
	public $title;
	public $conexion;
	public $datosgenerales;
	public $array_cheques;
	public $fecha,$fecha2;
	function Header() 
	{
		$width = 10;

		$this->SetY(15);

		$this->SetLeftMargin(15);
		$width = 5;
		$this->SetX(5);
		$this->SetFont('Arial','B',10);
		$this->SetFillColor(10,50,100);
		$this->Image('../imagenes/SiSalud.jpg', 6 ,10, 45, 28,'JPG', '');
		$this->Cell(280,0,$this->title,0,0,'C');
		$this->Ln(6);
		$this->Cell(260,0,"Periodo: ".$this->fecha." Hasta " .$this->fecha2,0,0,'C');
		$this->Ln(20);
		
		$this->SetWidths(array(20,20,30,40,40,25,25,25,25));
		$this->SetAligns(array("C","C","C","C","C","C","C","C","C"));
		//$this->SetFillColor(232,232,232,232,232,232,232,232);
		
		$this->SetLeftMargin(18);
		$width = 5;
		$this->SetFont('Arial','B',6);
		
		$this->Row(array('CHEQUE','CHEQUERA','CTA.','BANCO','PROVEEDOR','RIF','FECHA','CONCEPTO','MONTO'),1);
	}

	function Footer() 
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',10);
		
		$this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
	}

	function dwawCell($title,$data) 
	{
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

	function ChapterBody() 
	{
		$this->SetWidths(array(20,20,30,40,40,25,25,25,25));
		$this->SetAligns(array("C","C","C","C","C","C","C","C","C"));
		
		$total=0;
		$i=0;
		while($this->array_factura[$i])
		{
			$this->SetFont('Arial','',6);
		
			
			$this->Row(
			array($this->array_factura[$i]["cheque"],
			$this->array_factura[$i]["cod_chequera"],
			$this->array_factura[$i]["descta"],
			$this->array_factura[$i]["descban"],
			$this->array_factura[$i]["descripcion"],
			$this->array_factura[$i]["rif"],
			fecha($this->array_factura[$i]["fecha"]),
			$this->array_factura[$i]["concepto"],
			number_format($this->array_factura[$i]["monto"], 2, ',', '.')),1);
			$total+=$this->array_factura[$i]["monto"];
			$i++;
		}
		$this->Ln(1);
		$this->SetFont('Arial','B',7);
		$this->Row(
		array(
		'',
		'',
		'',
		'',
		'',
		'',
		'TOTAL CH.: '.$i,
		'',
		number_format($total, 2, ',', '.')));
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

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

if(($_GET["fecha"]=='') || ($_GET["fecha2"]==''))
{
	$array_cheques = $comunes->ObtenerFilasBySqlSelect("SELECT ch.*, pro.descripcion, pro.rif, ban.descripcion as descban, codt.descripcion as descta FROM cheque ch join proveedores pro on (pro.id_proveedor=ch.id_proveedor) join chequera chq on (ch.cod_chequera=chq.cod_chequera) join tesor_bancodet codt on (codt.cod_tesor_bandodet=chq.cod_tesor_bandodet) join banco ban on (codt.cod_banco=ban.cod_banco)
");
}
else
{
	$fecha =fecha_sql($_GET["fecha"]);
	$fecha2 =fecha_sql($_GET["fecha2"]);
	$array_cheques = $comunes->ObtenerFilasBySqlSelect("SELECT ch.*, pro.descripcion, pro.rif, ban.descripcion as descban, codt.descripcion as descta FROM cheque ch join proveedores pro on (pro.id_proveedor=ch.id_proveedor) join chequera chq on (ch.cod_chequera=chq.cod_chequera) join tesor_bancodet codt on (codt.cod_tesor_bandodet=chq.cod_tesor_bandodet) join banco ban on (codt.cod_banco=ban.cod_banco) WHERE fecha BETWEEN '$fecha' and '$fecha2'");
}
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

$pdf=new PDF('L','mm','LETTER');
$title='LISTADO DE CHEQUES EMITIDOS';
$pdf->fecha=$_GET["fecha"];
$pdf->fecha2=$_GET["fecha2"];
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_cheques);

$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();

?>