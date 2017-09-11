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
    public $consulta,$cantFact;
    public $array_explode; 
    public $comunes;

    function Header() {

        $width = 10;

        //$this->Image('../imagenes/banner_superior.jpg',10,5,190);


        $this->SetY(15);
        
    	$comunes = new ConexionComun();
	$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM cheque where cod_cheque =".$_GET["cod_cheque"]);
	$this->SetLeftMargin(15);
        $width = 5;
        $this->SetX(5);
        $this->SetFont('Arial','B',12);
        $this->SetFillColor(10,50,100);
	//$this->Image('../imagenes/SiSalud.jpg', 6 ,10, 35, 15,'JPG', '');
        $this->Cell(135,0,'Facturas Asociadas al Cheque Nro '.utf8_decode($array_parametros_generales[0]["cheque"]),0,0,'R');
	$this->Ln(6);	
	$this->Cell(113,0,'Fecha del Pago: '.date('d-m-Y', strtotime($array_parametros_generales[0]["fecha"])),0,0,'R');
				

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

        $this->SetWidths(array(50,40,40));
        $this->SetAligns(array("C","C","C"));
        $this->SetFillColor(232,232,232);
		
	$this->SetLeftMargin(35);
        $width = 5;
        $this->SetX(39);
        $this->SetFont('Arial','B',8);
		
		$this->Row(array('Nro.Factura','Nro Control de Factura','Monto Pago'),1);
		
		
		
		
		
	$this->SetWidths(array(50,40,40));
        $this->SetAligns(array("C","C","C"));
        $this->SetFillColor(10,10,10);


$i=0;
/////////////////////////////////////////////////////////

$comunes4 = new ConexionComun();
$sql3 = $comunes4->ObtenerFilasBySqlSelect("SELECT * FROM cheque WHERE cod_cheque =".$_GET["cod_cheque"]);
$facturass = explode("/",$sql3[0]["ref"]);
$cantFact = count($facturass);
//echo $cantFact;
$ii=0;
for ($ii = 0; $ii < $cantFact; $ii++) {
$facturass[$ii];
$comunes3 = new ConexionComun();



$sql4 =   $comunes3->ObtenerFilasBySqlSelect("SELECT edodet . * , fac . * , edo . *, che.* FROM cxp_edocuenta_detalle edodet INNER JOIN cxp_factura fac ON ( edodet.cod_edocuenta = fac.id_cxp_edocta ) INNER JOIN cxp_edocuenta edo ON ( edodet.cod_edocuenta = edo.cod_edocuenta ) INNER JOIN cheque che ON (che.id_proveedor = edo.id_proveedor) WHERE edodet.numero ='".$facturass[$ii]."' and che.id_proveedor=".$_GET["id_proveedor"]);


	   	    $this->SetLeftMargin(35);
		    $width = 5;
		    $this->SetX(39);
		    $this->SetFont('Arial','',6);		    
            	    $this->Row(
                    array(  
                    $sql4[0]["cod_factura"],
		    $sql4[0]["cod_cont_factura"],
                    number_format($sql4[0]["monto_total_con_iva"], 2, ',', '.')),1);
  
		    $totalfacturas+=$sql4[0]["monto_total_con_iva"];  

}
	


     $this->Ln(2);
        

$this->SetX(39);
		$this->SetFont('Arial','B',8);
		$this->SetCeldas(array(0,0,1));
		$this->Row(array( '', 'MONTO TOTAL', 
		number_format($totalfacturas, 0, ',', '.'), 
		));
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

        function explode($array) {
        $this->array_explode = $array;
    }

    function ArrayFactura($array) {
        $this->array_factura = $array;	

    }






}


fecha_sql($fecha = @$_GET["fecha"]);
$fecha2= @$_GET['fecha2'];



//echo ("SELECT * FROM CHEQUE WHERE cod_cheque = ".$_GET["cod_cheque"]);



/*$i=0;
for ($i = 0; $i <= $cantFAct; $i++) {
}*/

/*
$comunes = new ConexionComun();
$array_factura =   $comunes->ObtenerFilasBySqlSelect("SELECT che.* , edodet.* , fac.* FROM cheque che INNER JOIN cxp_edocuenta_detalle edodet ON che.ref = edodet.numero INNER JOIN cxp_factura fac ON edodet.cod_edocuenta = fac.id_cxp_edocta WHERE che.cod_cheque =".$_GET["cod_cheque"]);
*/

/*
echo ("SELECT che.* , edodet.* , fac.* FROM cheque che INNER JOIN cxp_edocuenta_detalle edodet ON che.ref = edodet.numero INNER JOIN cxp_factura fac ON edodet.cod_edocuenta = fac.id_cxp_edocta WHERE che.cod_cheque =".$_GET["cod_cheque"]);
*/
//$mes=mesaletras(substr($fecha,5,2));

$pdf=new PDF('P','mm','A4');
$title='DETALLE DE PAGO .';
//$fecha=mesaletras(substr($fecha,5,2))
$pdf->fecha=$fecha;
$pdf->fecha2=$fecha2;
$pdf->explode($array_explode);
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);



$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();

?>


