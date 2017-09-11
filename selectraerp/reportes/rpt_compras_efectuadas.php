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


        $this->SetY(20);
        
    
	$this->SetLeftMargin(10);
        $width = 5;
        $this->SetX(5);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(10,50,100);
	$this->Image('../imagenes/SiSalud.jpg', 10 ,10, 35, 20,'JPG', '');
        //$this->Cell(80,0, "LIBRO DE VENTASSSS".$mes." ".substr($fecha,0,4),0,0,'C');
	$this->Cell(219,0,utf8_decode($this->datosgenerales[0]["nombre_empresa"]),0,0,'C');	
	$this->Ln(6);
        $this->Cell(219,0,'COMPRAS EFECTUADAS POR SERVICIO/PRODUCTO',0,0,'C');
	$this->Ln(6);
        $this->Cell(208,0,"Periodo: ".date('d-m-Y', strtotime($this->fecha))." Hasta " .date('d-m-Y', strtotime($this->fecha2)),0,0,'C');
        $this->Ln(10);
	
	$this->SetWidths(array(30,30,60,30,35));
        $this->SetAligns(array("C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232);
		
	$this->SetLeftMargin(15);
        $width = 8;
        $this->SetX(8);
        $this->SetFont('Arial','B',6);
		
        $this->Row(array('Codigo','Linea','Descripcion','Cantidad','Total sin IVA'),1);
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

    /*    $this->SetWidths(array(50,20,40,25,40,25,25));
        $this->SetAligns(array("C","C","C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232,232,232);
		
	$this->SetLeftMargin(15);
        $width = 8;
        $this->SetX(8);
        $this->SetFont('Arial','B',6);
		
		$this->Row(array('Medico','R.I.F.','Cant. Fact. Pendientes por Pagar','Monto','Cant. Fact. Pendientes por Cobrar', 'Monto', 'TOTAL'),1);
		
		
	*/	
		
		
$this->SetWidths(array(30,30,60,30,35));
$this->SetAligns(array("L","C","C","C","C"));
$this->SetFillColor(232,232,232,232,232);

$totalDebito=0;
$totalCredito=0;
$totalVentasConIva=0;
$totalVentasNoGravadas=0;
$totalBaseImponible=0;
$totalIva=0;
$totalIvaRet=0;
$i=0;
 $this->Ln(3);
$this->SetFont('Arial','B',8);	
$this->Row(
    array(  
    'PRODUCTOS',
    '',
    '',
    '',
    ''),0);
 $this->Ln(3);	   
	  
	
$this->SetWidths(array(30,30,60,30,35));
$this->SetAligns(array("L","L","L","L","R"));
$this->SetFillColor(232,232,232,232,232);

while($this->array_factura[$i])
{		
            $this->SetLeftMargin(15);
            $width = 5;
            $this->SetX(8);
            $this->SetFont('Arial','',6);
			$this->SetAligns(array("L","L","L","L","R"));
            $this->Row(
                    array(  
                    $this->array_factura[$i]["cod_compra"],
                    $this->array_factura[$i]["descripcion"],
		    $this->array_factura[$i]["descripcion1"],
                    $this->array_factura[$i]["cantidadTotal"],
                    number_format($this->array_factura[$i]["montoTotal"], 2, ',', '.')),1);
		     

	$totalfacturas2+=$sql[0]["facturasEntregar"];
  	$totalmonto2+=$sql[0]["montooEntregar"];
 	$totalfacturas+=$sql3[0]["facturas"];    
	$totalmonto+=$sql3[0]["montoo"];			
	$totalfacturas3+=$sql2[0]["facturasEntregadas"];
  	$totalmonto3+=$sql2[0]["montooEntregado"];
	$totalfacturas4+=$sql4[0]["facturasPagadas"];
  	$totalmonto4+=$sql4[0]["montooPagado"];
	
	$totalproductos+=$this->array_factura[$i]["montoTotal"];
	$i++;
}
     $this->Ln(3);
        
	$this->SetX(8);
		$this->SetFont('Arial','B',8);
		$this->SetCeldas(array(0,0,0,0,1));
		$this->Row(array( '', '', 
		'TOTAL DE PRODUCTOS:', 
		 '',
		 number_format($totalproductos, 2, ',', '.'),
		));
		$this->Ln(10);

$this->SetWidths(array(30,30,60,30,35));
$this->SetAligns(array("L","C","C","C","C"));
$this->SetFillColor(232,232,232,232,232);
$this->SetFont('Arial','B',8);	
$this->Row(
    array(  
    'SERVICIOS'),0);
 $this->Ln(3);
$i=0;

while($this->array_factura2[$i])
{		
            $this->SetLeftMargin(15);
            $width = 5;
            $this->SetX(8);
            $this->SetFont('Arial','',6);
			$this->SetAligns(array("L","L","L","L","R"));
            $this->Row(
                    array(  
                    $this->array_factura2[$i]["cod_item"],
                    $this->array_factura2[$i]["descripcion"],
		    $this->array_factura2[$i]["descripcion1"],
                    $this->array_factura2[$i]["cantidadTotal"],
                    number_format($this->array_factura2[$i]["montoTotal"], 2, ',', '.')),1);
		     

	$totalservicios+=$this->array_factura2[$i]["montoTotal"];
	$i++;
}   
 $this->Ln(3);
        
	$this->SetX(8);
		$this->SetFont('Arial','B',8);
		$this->SetCeldas(array(0,0,0,0,1));
		$this->Row(array( '', '', 
		'TOTAL DE SERVICIOS:', 
		 '',
		 number_format($totalservicios, 2, ',', '.'),
		));
		$this->Ln(10);

		 $this->Ln(3);
        
	$this->SetX(8);
		$this->SetFont('Arial','B',8);
		$this->SetCeldas(array(0,0,0,0,1));
		$this->Row(array( '', '', 
		'TOTAL FINAL:', 
		 '',
		 number_format($totalservicios+$totalproductos, 2, ',', '.'),
		));
		$this->Ln(10);
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

   function ArrayFactura2($array) {
        $this->array_factura2 = $array;
    }

}


fecha_sql($fecha = @$_GET["fecha"]);
$fecha2= @$_GET['fecha_hasta'];


$comunes = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");




$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT com. * , comdet . * , SUM( comdet._item_cantidad ) AS cantidadTotal, SUM( comdet._item_totalsiniva ) AS montoTotal, it. * , li . * FROM compra com JOIN compra_detalle comdet ON ( com.id_compra = comdet.id_compra ) JOIN item it ON ( comdet.id_item = it.id_item ) JOIN linea li ON ( it.cod_linea = li.cod_linea ) WHERE it.cod_item_forma <> 2 and cod_estatus <> 3 and fechacompra BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."' GROUP BY descripcion1");

$array_factura2 = $comunes->ObtenerFilasBySqlSelect("SELECT com. * , comdet . * , SUM( comdet._item_cantidad ) AS cantidadTotal, SUM( comdet._item_totalsiniva ) AS montoTotal, it. * , li . * FROM compra com JOIN compra_detalle comdet ON ( com.id_compra = comdet.id_compra ) JOIN item it ON ( comdet.id_item = it.id_item ) JOIN linea li ON ( it.cod_linea = li.cod_linea ) WHERE it.cod_item_forma =2 and cod_estatus <> 3 and fechacompra BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."' GROUP BY descripcion1");


$pdf=new PDF('P','mm','A4');
$title='LISTADO DE ANALITICOS';
//$fecha=mesaletras(substr($fecha,5,2))
$pdf->fecha=$fecha;
$pdf->fecha2=$fecha2;
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->ArrayFactura2($array_factura2);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();

?>


