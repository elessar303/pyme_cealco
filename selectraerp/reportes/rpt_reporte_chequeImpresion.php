<?php
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
session_start();

ini_set("display_errors",1);
class PDF extends FPDF {
    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $cod_cheque;
    function Header() {

    }

    function Footer() {
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
        $comunes = new ConexionComun();
        $data_cheque = $comunes->ObtenerFilasBySqlSelect("select *,(select descripcion from proveedores where id_proveedor =  cheque.id_proveedor) as beneficiario from cheque where cod_cheque = ".$_GET["cod_cheque"]);
        $objNumLetra = new numerosALetras();

        $data_pg = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
	$ciudad= $data_pg[0]["ciudad"];
        $monto = $data_cheque[0]["monto"];
    
        $nombreContribuyente = $data_cheque[0]["beneficiario"];
        $montoLetra = $objNumLetra->numerosALetras($monto);
        list($ano,$mes,$dia) = explode("-",$data_cheque[0]["fecha"]);


	$this->SetFont('Arial','B',8);
	$this->Cell(45);
	$this->Cell(188,7,'',0,0);
	$this->Ln();
	$this->SetY(-144);
	$this->Cell(67);
	$this->Cell(178,7,"** Bs. ".number_format($monto,2,',','.')." **",0,0,'R');
	$this->Ln(16);
	$this->Cell(45);
	$this->Cell(75,7,'',0,0);
	$this->Cell(138,7,$nombreContribuyente,0,0,'L');
	$this->Ln(8);
	$this->Cell(45);
	$this->Cell(75,7,'',0);
	$this->MultiCell(110,7,"** ".$montoLetra." **",0,'L');
	$this->Ln(4);
	$this->Cell(100);
	$this->Cell(68,7,"        $ciudad, ".$dia." de ".mesaletras($mes),0,0);
	$this->Cell(30,7,"       ".$ano,0,0);
	$this->Ln(22);
	$this->Cell(70);
	$this->Cell(168,7,'*** NO ENDOSABLE *** CADUCA A LOS 90 DIAS ',0,0,'R');

	$this->Ln(15);
	$this->Cell(45);
	$this->Cell(188,7,'',0);

        $comunes->Execute2("update cheque set situacion = 'Im' where cod_cheque = ".$_GET["cod_cheque"]);

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

    function setCodCheque($codCheque){
        $this->cod_cheque = $codCheque;
    }


}


$codCheque = @$_GET["cod_cheque"];
$comunes = new ConexionComun();

$pdf=new PDF('L','mm','Letter');
$pdf->PrintChapter();
$pdf->Output();

?>
