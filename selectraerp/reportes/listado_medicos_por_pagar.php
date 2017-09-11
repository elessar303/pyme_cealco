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
        
    
	$this->SetLeftMargin(10);
        $width = 5;
        $this->SetX(5);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(10,50,100);
	$this->Image('../imagenes/SiSalud.jpg', 10 ,10, 25, 15,'JPG', '');
        //$this->Cell(80,0, "LIBRO DE VENTASSSS".$mes." ".substr($fecha,0,4),0,0,'C');
        $this->Cell(269,0,utf8_decode('ESTADO DE CUENTA MÉDICO RESUMEN'),0,0,'C');
	$this->Ln(6);
        $this->Cell(258,0,"Periodo: ".date('d-m-Y', strtotime($this->fecha))." Hasta " .date('d-m-Y', strtotime($this->fecha2)),0,0,'C');
        $this->Ln(10);
	
	$this->SetWidths(array(65,20,40,25,40,25,25));
        $this->SetAligns(array("C","C","C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232,232,232);
		
	$this->SetLeftMargin(15);
        $width = 8;
        $this->SetX(8);
        $this->SetFont('Arial','B',6);
		
        $this->Row(array('Medico','R.I.F.','Facturas. Cobradas','Monto','Facturas. Por Cobrar', 'Monto', 'TOTAL'),1);
	$this->SetX(8);
	$this->SetAligns(array("L","L","C","R","C","R","R"));
	$this->SetWidths(array(65,20,40,25,40,25,25));
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
		
		
	$this->SetWidths(array(65,20,40,25,40,25,25));
        $this->SetAligns(array("L","L","C","R","C","R","R"));
        $this->SetFillColor(10,10,10,10,10,10,10);

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
            $this->SetLeftMargin(15);
            $width = 5;
            $this->SetX(8);
            $this->SetFont('Arial','',6);


        $comunes2 = new ConexionComun();
        $sql = $comunes2->ObtenerFilasBySqlSelect("select prov.*, medic.*,count(factura_fk) as facturass, sum(monto) as montooo FROM proveedores prov INNER JOIN cxp_factura_medico medic ON prov.id_proveedor = medic.medico_fk where medic.estatus='1' and fecha_fac BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."'  and cxp_edocta_fk = 0 and medico_fk='".$this->array_factura[$i]["medico_fk"]."' GROUP BY prov.descripcion asc");

            $total = $this->array_factura[$i]["montoo"]+$sql[0]["montooo"];
            $this->Row(
                    array(  
                    $this->array_factura[$i]["descripcion"],
                    $this->array_factura[$i]["rif"],
		    $this->array_factura[$i]["facturas"],
		    number_format($this->array_factura[$i]["montoo"], 2, ',', '.'),
		    $sql[0]["facturass"],
                    number_format($sql[0]["montooo"], 2, ',', '.'),
                    number_format($total, 2, ',', '.')),1);
		     

	$totalfacturas+=$this->array_factura[$i]["facturas"];    
	$totalmonto+=$this->array_factura[$i]["montoo"];   
	$totalfacturas2+=$sql[0]["facturass"];
  	$totalmonto2+=$sql[0]["montooo"];
	$Ttotal+=$total;
	$i++;
}
     $this->Ln(3);
        
	$this->SetX(8);
		$this->SetFont('Arial','B',8);
		$this->SetCeldas(array(0,1,1,1,1,1,1,1,1));
		$this->Row(array( '', 'TOTAL', 
		$totalfacturas, 
		 number_format($totalmonto, 2, ',', '.'),
		$totalfacturas2,
		 number_format($totalmonto2, 2, ',', '.'),
		  number_format($Ttotal, 2, ',', '.'),
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
$fecha2= @$_GET['fecha_hasta'];


$comunes = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
//$fechaz=$fecha."-01";
/*
echo "SELECT prov.*, medic.* FROM proveedores prov INNER JOIN cxp_factura_medico medic ON prov.id_proveedor = medic.medico_fk WHERE fecha_fac BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."'";*/


$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT prov.*, medic.*, COUNT(medic.factura_fk) AS facturas, SUM(medic.monto) AS montoo FROM proveedores prov INNER JOIN cxp_factura_medico medic ON prov.id_proveedor = medic.medico_fk WHERE  fecha_fac BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."' and cxp_edocta_fk = 0 GROUP BY prov.descripcion asc");

//echo "SELECT prov.*, medic.*, COUNT(medic.factura_fk) AS facturas, SUM(medic.monto) AS montoo FROM proveedores prov INNER JOIN cxp_factura_medico medic ON prov.id_proveedor = medic.medico_fk WHERE  fecha_fac BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."' and cxp_edocta_fk = 0 GROUP BY prov.descripcion asc";





/*
$array_factura2 = $comunes->ObtenerFilasBySqlSelect("SELECT prov.*, medic.*, COUNT(medic.factura_fk) AS facturas, SUM(medic.monto) AS montoo FROM proveedores prov INNER JOIN cxp_factura_medico medic ON prov.id_proveedor = medic.medico_fk WHERE  fecha_fac BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."' and cxp_edocta_fk = 0 GROUP BY prov.descripcion asc");
*/

//$mes=mesaletras(substr($fecha,5,2));

$pdf=new PDF('L','mm','A4');
$title='Detalle de Cobranzas Realizadas';
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


