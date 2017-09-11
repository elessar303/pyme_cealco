<?php
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
    //date("Y-m-d");

// $diass = antiguedad($fecha,$fecha2,D);



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

	$proveedor = $_GET["id_proveedor"];
	$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from proveedores where id_proveedor=".$proveedor);
    
	$this->SetLeftMargin(15);
        $width = 5;
        $this->SetX(55);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(10,50,100);
	$this->Image('../imagenes/SiSalud.jpg', 6 ,10, 30, 18,'JPG', '');
        $this->SetX(38);
        $this->Cell(135,0,'ESTADO DE CUENTA DETALLADO',0,0,'L');
	$this->Ln(6);	
	$this->SetX(38);
	$this->Cell(92,0,utf8_decode('MÉDICO: ').utf8_decode($array_parametros_generales[0]['descripcion']),0,0,'L');
	$this->Ln(6);
	$this->SetX(38);	
	$this->Cell(86,0,'Al: '.date("Y-m-d"),0,0,'L');

        $this->Ln(7);
	   $this->SetWidths(array(15,20,8,25,8,25,25,25,25,25,25,25));
        $this->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232,232,232,232,232,232,232,232,232);
		
		$this->SetLeftMargin(18);
		$width = 5;
		$this->SetX(8);
		$this->SetFont('Arial','B',6);
		
		$this->Row(array('Fecha','Factura','Serie', 'Monto','Dias', '0 a 30', '31 a 45 Dias', '46 a 60 Dias ', '61 a 90 Dias', 'Mayores a 90 Dias','Estatus','F. Cobro'),1);
		$this->SetX(8);	
	$this->SetAligns(array("L","L","L","R","L","R","R","R","R","R","R"));

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

     
		
		
	$this->SetWidths(array(15,20,8,25,8,25,25,25,25,25,25,25));
        $this->SetAligns(array("L","L","L","R","L","R","R","R","R","R","R","R","R"));
        $this->SetFillColor(10,10,10,10,10,10,10,10,10,10,10,10,10,10);

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
	
	 
		$estatus='';
	
            $this->SetLeftMargin(18);
            $width = 5;
            $this->SetX(8);
            $this->SetFont('Arial','',6);
	    $monto30='';
	    $monto45='';
	    $monto60='';
	    $monto90='';
	    $monto100='';
	    $montototal=$this->array_factura[$i]["monto"];
	    $totalmonto+=$montototal;
            $fechaActual = date("Y-m-d");
	    $diass = antiguedad($this->array_factura[$i]["fecha_fac"],$fechaActual,'D');
	    if ($diass>0 && $diass<=30){
	    $monto30=$this->array_factura[$i]["monto"];
	    $totalmonto30+=$monto30; }
	    elseif ($diass>30 && $diass<=45){
            $monto45=$this->array_factura[$i]["monto"];
	    $totalmonto45+=$monto45; }
	    elseif ($diass>45 && $diass<=60){
 	    $monto60=$this->array_factura[$i]["monto"];
	    $totalmonto60+=$monto60; }
            elseif ($diass>60 && $diass<=90){
	    $monto90=$this->array_factura[$i]["monto"];
	    $totalmonto90+=$monto90; }
	    else
	    $monto100=$this->array_factura[$i]["monto"];
	    $totalmonto100+=$monto100;
           

	    if ($this->array_factura[$i]["estatus"]==1){
	    $estatus='Por Cobrar';
	     }
	    elseif ($this->array_factura[$i]["estatus"]==0) { 
	    $estatus='Cobrada';   
	    }     

	    $this->SetAligns(array("L","L","L","R","L","R","R","R","R","R","R"));
            $this->Row(
                    array(  
                    $this->array_factura[$i]["fecha_fac"],
		    $this->array_factura[$i]["factura_fk"],
 		    $this->array_factura[$i]["serie"],
                    number_format($this->array_factura[$i]["monto"], 2, ',', '.'),          
		    $diass,
                    $monto30,
                    $monto45,
		    $monto60,
		    $monto90,
                    $monto100,
		    $estatus),1);

	$estatus='';
	$i++;
}
        $this->Ln(1);
        $this->Ln(3);
        $estatus='';
	$this->SetX(1);
		$this->SetFont('Arial','B',8);
		$this->SetWidths(array(15,20,15,25,8,25,25,25,25,25,25,25));
		$this->SetAligns(array("L","L","L","R","L","R","R","R","R","R","R"));
		$this->SetFillColor(10,10,10,10,10,10,10,10,10,10,10,10);
		$this->SetCeldas(array(0,0,1,1,0,1,1,1,1,1,0,0));
                 
		 $this->Row(array( '', '', 
		 'TOTAL',
		 number_format($totalmonto, 2, ',', '.'),
		 '',
		 number_format($totalmonto30, 2, ',', '.'),
		 number_format($totalmonto45, 2, ',', '.'),
		 number_format($totalmonto60, 2, ',', '.'),
		 number_format($totalmonto90, 2, ',', '.'),
		 number_format($totalmonto100, 2, ',', '.'),
		'','',
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

    function ArrayFactura($array) {
        $this->array_factura = $array;
    }


}




fecha_sql($fecha = @$_GET["fecha"]);
fecha_sql($fecha2= @$_GET["fecha2"]);




$proveedor = $_GET["id_proveedor"];
//echo ("SELECT * cxp_factura_medico WHERE medico_fk =".$proveedor);
//where fecha_autorizado BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha2"]."' GROUP BY cod_edocuenta asc 
$comunes = new ConexionComun();
$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM cxp_factura_medico WHERE medico_fk ='".$proveedor."' order by fecha_fac desc");

//and cxp_edocta_fk = 0

$pdf=new PDF('L','mm','A4');
$title='Estado de Cuenta Medico .';
$pdf->fecha=$fecha;
$pdf->fecha2=$fecha2;
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();

?>


