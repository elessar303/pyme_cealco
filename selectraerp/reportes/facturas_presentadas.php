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

	$comunes = new ConexionComun();

	$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM clientes where id_cliente =".$_GET["id_cliente"]);

	
	$this->SetY(15);        
    
	$this->SetLeftMargin(15);
        $width = 5;
        $this->SetX(5);
        $this->SetFont('Arial','B',12);
        $this->SetFillColor(10,50,100);
	$this->Image('../imagenes/SiSalud.jpg', 6 ,10, 25, 18,'JPG', '');
        //$this->Cell(80,0, "LIBRO DE VENTASSSS".$mes." ".substr($fecha,0,4),0,0,'C');
        $this->Cell(165,0,'FACTURAS PRESENTADAS',0,0,'C');
	$this->Ln(7);
        $this->SetFont('Arial','B',11);
	//$this->Cell(0,8,'Tipo de Cliente',0,0,"C");
	$this->Cell(150,0,'Cliente: '.utf8_decode($array_parametros_generales[0]["nombre"]),0,0,"C");
	//$this->Cell(143,0,"Periodo: ".$this->fecha." Hasta " .$this->fecha2,0,0,'C');
        $this->Ln(15);
	


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

 	 $this->SetWidths(array(30,30,15,50));
        $this->SetAligns(array("C","C","C","C"));
        $this->SetFillColor(232,232,232,232);
		
	$this->SetLeftMargin(35);
        $width = 5;
          $this->SetX(35);
        $this->SetFont('Arial','B',8);
		
		$this->Row(array('Factura','Monto','Clave','Fecha de Autorizacion'),1);
		
		
		
		
		
        $this->SetWidths(array(30,30,15,50));
        $this->SetAligns(array("C","C","C","C"));
        $this->SetFillColor(232,232,232,232);

$i=0;
while($this->array_factura[$i])
{

	//$fecha_vec= date('d-m-Y', strtotime($this->array_factura[$i]["vencimiento_fecha"]));
$fecha_vec=fecha_sql($this->array_factura[$i]["vencimiento_fecha"]);
	
       	    $this->SetLeftMargin(35);
            $width = 5;
             $this->SetX(35);
            $this->SetFont('Arial','',6);

            
            $this->Row(
                    array(  
                    $this->array_factura[$i]["numero"],
                    number_format($this->array_factura[$i]["monto"], 2, ',', '.'),
		    $this->array_factura[$i]["clave"],
                    date_format(date_create($this->array_factura[$i]["fecha_autorizado"]),'d-m-Y'),),1);


	$total+=$this->array_factura[$i][monto];
	$i++;
}
        $this->Ln(1);
        
	    
 	    $this->SetLeftMargin(35);
            $width = 5;
           $this->SetX(35);
            $this->SetFont('Arial','B',6);

            $this->SetCeldas(array(1,1,0,0));
            $this->Row(
                    array(  
                    'MONTO TOTAL ',
                    number_format($total, 2, ',', '.'),
		    ),1);



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
//$fechaz=$fecha."-01";

$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM cxc_edocuenta where id_cliente ='".$_GET["id_cliente"]."' and fecha_registro ='".$_GET["fecha"]."'");

//echo ("SELECT * FROM cxc_edocuenta where id_cliente ='".$_GET["id_cliente"]."' and fecha_registro ='".$_GET["fecha"]."'");

fecha_sql($fecha = @$_GET["fecha"]);
$fecha2= @$_GET['fecha2'];
//$mes=mesaletras(substr($fecha,5,2));

$pdf=new PDF('P','mm','A4');
$title='DETALLE DE PAGO .';
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


