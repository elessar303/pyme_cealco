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

	
	$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
    
	$this->SetLeftMargin(15);
        $width = 5;
        $this->SetX(55);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(10,50,100);
	$this->Image('../imagenes/SiSalud.jpg', 6 ,10, 45, 28,'JPG', '');
        //$this->Cell(80,0, "LIBRO DE VENTASSSS".$mes." ".substr($fecha,0,4),0,0,'C');
        $this->Cell(135,0,'ESTADO DE CUENTAS DE MEDICOS',0,0,'L');
	$this->Ln(6);	
	$this->SetX(55);
	$this->Cell(119,0,utf8_decode($this->datosgenerales[0]["nombre_empresa"]),0,0,'L');
	$this->Ln(6);
	$this->SetX(55);	
	 $this->Cell(86,0,"Desde: ".date('d-m-Y', strtotime($this->fecha))." Hasta " .date('d-m-Y', strtotime($this->fecha2)),0,0,'L');
	//." Hasta " .date('d-m-Y', strtotime($this->fecha2))
        $this->Ln(15);
	   $this->SetWidths(array(15,15,15,25,15,15,20,20,15,25));
        $this->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232,232,232,232,232,232,232);
		
		$this->SetLeftMargin(18);
		$width = 5;
		$this->SetX(8);
		$this->SetFont('Arial','B',6);
		
		$this->Row(array('Emision.','Factura','Serie', 'Monto','Dias', '0 a 30', '31 a 45 Dias', '46 a 60 Dias ', '61 a 90 Dias', 'Mayores a 90 Dias'),1);
		$this->SetX(8);	


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

     
		
		
		$this->SetWidths(array(15,15,15,25,15,15,20,20,15,25));
        $this->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C"));
        $this->SetFillColor(10,10,10,10,10,10,10,10,10,10,10,10);

$totalDebito=0;
$totalCredito=0;
$totalVentasConIva=0;
$totalVentasNoGravadas=0;
$totalBaseImponible=0;
$totalIva=0;
$totalIvaRet=0;
$i=0;

$comunes2 = new ConexionComun();
$sql = $comunes2->ObtenerFilasBySqlSelect("select * from proveedores where id_proveedor in (select medico_fk from cxp_factura_medico where cxp_edocta_fk=0) and proveedores.clase_proveedor = 2");

$medico='';
$totalglobal=0;
$totalglobal30=0;
$totalglobal45=0;
$totalglobal60=0;
$totalglobal90=0;
$totalglobal100=0;

while($sql[$i])
{
		
		if($medico==''){
			$this->Cell(150,5,$sql[$i]['descripcion'],0,1);
			$medico=$sql[$i]['id_proveedor'];
		}
	
		if($medico!=$sql[$i]['id_proveedor']){
			$medico=$sql[$i]['id_proveedor'];
			$this->Ln(1);
			$this->Ln(3);
        	$this->SetX(8);
			$this->SetFont('Arial','B',8);
			$this->SetCeldas(array(0,0,1,1,0,1,1,1,1,1));
                 
			$this->Row(array( '', '', 
			 'TOTAL',
			 number_format($totalmonto, 2, ',', '.'),
			 '',
			 number_format($totalmonto30, 2, ',', '.'),
			 number_format($totalmonto45, 2, ',', '.'),
			 number_format($totalmonto60, 2, ',', '.'),
			 number_format($totalmonto90, 2, ',', '.'),
			 number_format($totalmonto100, 2, ',', '.'),
			));
			
			$this->Ln(1);
			$this->SetX(8);
			$this->SetFont('Arial','B',6);
			$this->Cell(150,5,$sql[$i]['descripcion'],0,1);
			
			
			$totalmonto='0';$totalmonto30=0;$totalmonto45=0;$totalmonto60=0;$totalmonto90=0;$totalmonto100=0;
		}
		$j=0;
		//echo "SELECT * FROM cxp_factura_medico WHERE (`fecha_fac`>='".$this->fecha."' and `fecha_fac`<= '".$this->fecha2."' )  AND cxp_edocta_fk = 0 AND medico_fk='".$medico."' order by fecha_fac desc";
		$facturas=$comunes2->ObtenerFilasBySqlSelect("SELECT * FROM cxp_factura_medico WHERE (`fecha_fac`>='".$this->fecha."' and `fecha_fac`<= '".$this->fecha2."' )  AND cxp_edocta_fk = 0 AND medico_fk='".$medico."' order by fecha_fac desc");
		while($facturas[$j])
		{
			$this->SetLeftMargin(18);
			$width = 5;
			$this->SetX(8);
			$this->SetFont('Arial','',6);
			$monto30='';
			$monto45='';
			$monto60='';
			$monto90='';
			$monto100='';
			$montototal=$facturas[$j]["monto"];
			$totalmonto+=$montototal;
			$fechaActual = date("Y-m-d");
			$diass = antiguedad($facturas[$j]["fecha_fac"],$fechaActual,'D');
			if ($diass>0 && $diass<=30)
			{
				$monto30=$facturas[$j]["monto"];
				$totalmonto30+=$monto30; 
			}
			elseif ($diass>30 && $diass<=45)
			{
				$monto45=$facturas[$j]["monto"];
				$totalmonto45+=$monto45; 
			}
			elseif ($diass>45 && $diass<=60)
			{
				$monto60=$facturas[$j]["monto"];
				$totalmonto60+=$monto60; 
			}
			elseif ($diass>60 && $diass<=90)
			{
				$monto90=$facturas[$j]["monto"];
				$totalmonto90+=$monto90; 
			}
			else
			{
				$monto100=$facturas[$j]["monto"];
				$totalmonto100+=$monto100;
			}
				
			$this->Row(
				array(  
				$facturas[$j]["fecha_fac"],
				$facturas[$j]["factura_fk"],
				$facturas[$j]["serie"],
				number_format($facturas[$j]["monto"], 2, ',', '.'),          
				$diass,
				$monto30,
				$monto45,
				$monto60,
				$monto90,
				$monto100),1);
			
			$totalglobal+=$facturas[$j]["monto"];
			$totalglobal30+=$monto30;
			$totalglobal45+=$monto45;
			$totalglobal60+=$monto60;
			$totalglobal90+=$monto90;
			$totalglobal100+=$monto100;
			$j++;
		}
		//$this->Cell(150,5,$j,0,1);
	$i++;
}
  //$this->Cell(150,5,$i,0,1);
 $this->Ln(3);

$this->SetX(8);
$this->SetFont('Arial','B',8);
$this->SetCeldas(array(0,0,1,1,0,1,1,1,1,1));
		 
 $this->Row(array( '', '', 
 'TOTAL',
 number_format($totalmonto, 2, ',', '.'),
 '',
 number_format($totalmonto30, 2, ',', '.'),
 number_format($totalmonto45, 2, ',', '.'),
 number_format($totalmonto60, 2, ',', '.'),
 number_format($totalmonto90, 2, ',', '.'),
 number_format($totalmonto100, 2, ',', '.'),
));

$this->Ln(1);
$this->SetX(6);
$this->Row(array( '', '', 
 'TOTALx',
 number_format($totalglobal, 2, ',', '.'),
 '',
 number_format($totalglobal30, 2, ',', '.'),
 number_format($totalglobal45, 2, ',', '.'),
 number_format($totalglobal60, 2, ',', '.'),
 number_format($totalglobal90, 2, ',', '.'),
 number_format($totalglobal100, 2, ',', '.'),
));

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
fecha_sql($fecha2= @$_GET["fecha_hasta"]);


$comunes = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

//echo ("SELECT * cxp_factura_medico WHERE medico_fk =".$proveedor);
//where fecha_autorizado BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha2"]."' GROUP BY cod_edocuenta asc 

//$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM cxp_factura_medico WHERE fecha_fac BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."' AND cxp_edocta_fk = 0 order by fecha_fac desc");


$pdf=new PDF('P','mm','A4');
$title='Estado de Cuenta .';
$pdf->fecha=$_GET["fecha"];
$pdf->fecha2=$_GET["fecha_hasta"];
$pdf->DatosGenerales($array_parametros_generales);
//$pdf->ArrayFactura($array_factura);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();

?>


