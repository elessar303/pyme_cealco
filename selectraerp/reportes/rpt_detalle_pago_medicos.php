<?php
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');




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
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM proveedores where id_proveedor=".$_GET["id_proveedor"]);


		$this->SetLeftMargin(15);
        $width = 5;
        $this->SetX(5);
        $this->SetFont('Arial','B',12);
        $this->SetFillColor(10,20,100);
	$this->Image('../imagenes/SiSalud.jpg', 10 ,10, 30, 15,'JPG', '');
        //$this->Cell(80,0, "LIBRO DE VENTASSSS".$mes." ".substr($fecha,0,4),0,0,'C');
        $this->Cell(135,0,'Relacion de Facturas/Servicios a Cancelar',0,0,'R');
	$this->Ln(6);	
	 $this->SetFont('Arial','B',8);
	$this->Cell(330,0,'Emitido el:'.date("d-m-Y"),0,0,'C');
	$this->Ln(8);	
	 $this->SetFont('Arial','B',10);
	$this->Cell(330,0,utf8_decode('MÉDICO: ').utf8_decode($array_parametros_generales[0]["descripcion"]),0,0,'L');
	
	//$this->Cell(143,0,"Periodo: ".$this->fecha." Hasta " .$this->fecha2,0,0,'C');
        $this->Ln(5);
	


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
        $this->SetFont('Arial','',10);
        $this->SetFillColor(206,230,172);
        $w=$this->GetStringWidth($title)+3;
        $this->SetX($x+$w);
        $this->SetFillColor(206,230,172);
        $this->MultiCell(175,8,$data,0,1,'J',0);

    }


					
					
    function ChapterBody() {

        $this->SetWidths(array(10,25,30,30,65,25));
        $this->SetAligns(array("C","C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232,232);
		
	$this->SetLeftMargin(25);
        $width = 5;
        $this->SetX(10);
        $this->SetFont('Arial','B',8);
		
		$this->Row(array('Serie','Factura','Fecha','Monto','Paciente', '# De Servicio'),1);
		
		
		
		
		
		$this->SetWidths(array(10,25,30,30,65,25));
        $this->SetAligns(array("C","C","C","C","L","C"));
        $this->SetFillColor(10,10,10,10,10,10);

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
            $this->SetX(10);
            $this->SetFont('Arial','',8);

            
            $this->Row(
                    array(  
		    $this->array_factura[$i]["serie"],
                    $this->array_factura[$i]["factura_fk"],
                    $this->array_factura[$i]["fecha_fac"],
		    $this->array_factura[$i]["monto"],
                     $this->array_factura[$i]["paciente"],
                     $this->array_factura[$i]["servicio"]),1);

   	$totalDebito+=0;
	$totalCredito+=0;
	$totalVentasConIva+=($this->array_factura[$i][montoItemsFactura]+$this->array_factura[$i][ivaTotalFactura]);
	$totalVentasNoGravadas+=0;
	$totalBaseImponible+=$this->array_factura[$i][totalizar_base_imponible];
	$totalIva+=$this->array_factura[$i][monto];
	$totalIvaRet+=$this->array_factura[$i][totalizar_total_retencion];
	$i++;
	


}
        $this->Ln(3);
        
	$this->SetX(10);
		$this->SetFont('Arial','B',8);
		$this->SetCeldas(array(0,0,0,1,1,1,0,1,1));
		$this->Row(array( '', '','MONTO TOTAL:', number_format($totalIva, 2, ',', '.')));
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
$fecha2= @$_GET['fecha2'];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM cxp_factura_medico where cxp_edocta_fk =".$_GET["cod_edocuenta"]." and cxp_edocta_fk <> '' and medico_fk =".$_GET["id_proveedor"]);
//$fechaz=$fecha."-01";



$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM cxp_factura_medico where cxp_edocta_fk =".$_GET["cod_edocuenta"]." and cxp_edocta_fk <> '' and medico_fk =".$_GET["id_proveedor"]);



//$mes=mesaletras(substr($fecha,5,2));

$pdf=new PDF('P','mm','A4');
$title='RELACION DE CONVENIO.';
//$fecha=mesaletras(substr($fecha,5,2))
$pdf->fecha=$fecha;
$pdf->fecha2=$fecha2;
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();

?>


