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
        //echo $periodo;
        $fecha = @$_GET["fecha"];
	$this->SetLeftMargin(10);
        $width = 5;
        $this->SetX(5);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(10,50,100);
	$this->Image('../imagenes/SiSalud.jpg', 10 ,10, 35, 20,'JPG', '');
        //$this->Cell(80,0, "LIBRO DE VENTASSSS".$mes." ".substr($fecha,0,4),0,0,'C');
        $this->Cell(269,0,utf8_decode($this->datosgenerales[0]["nombre_empresa"]),0,0,'C');
        $this->Cell(1,0,utf8_decode("Fecha Emisión: ").date('d-m-Y'),0,0,'R');
	$this->Ln(6);
	$this->Cell(258,0,utf8_decode('Res. Men. I.S.L.R. (Retención)'),0,0,'C');
	$this->Ln(6);
        $this->Cell(258,0,utf8_decode("Periodo: ").date('m-Y', strtotime($this->periodo)),0,0,'C');
        $this->Ln(10);
	
	$this->SetWidths(array(50,20,20,35,30,30,30,30,30,10,30));
        $this->SetAligns(array("C","C","C","C","C","C","C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232,232,232,232,232,232,232);
		
	$this->SetLeftMargin(15);
        $width = 8;
        $this->SetX(8);
        $this->SetFont('Arial','B',6);
		
        $this->Row(array('Nombre Proveedor o Razon Social','R.I.F.','Factura','Fecha Pago/Abono','Cheque/Abono', 'Monto Pagado/Abonado','Monto objeto de retencion','Tasa %','Retension I.S.L.R.'),1);
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
		
		
	$this->SetWidths(array(50,20,20,35,30,30,30,30,30,10,30));
        $this->SetAligns(array("L","C","C","C","C","C","C","C","C"));
        $this->SetFillColor(10,10,10,10,10,10,10,10,10);

$totalmonto=0;
$totalobjeto=0;
$totalretenido=0;
$i=0;


$concepto='';
while($this->array_factura[$i])
{		
	    if ($concepto==''){
	        $this->Ln(3);        	
		$this->SetX(8);
		$this->SetFont('Arial','B',8);
	    $concepto=$this->array_factura[$i]["descli"]; 
	    $this->Row(
                    array(  
                    $this->array_factura[$i]["descli"],
                    '',
		    '',
                    '',
		    '',
		    '',
	            '',
                    '',
                    ''),0);
	    }
	  
	/*$this->Row( array(
	  $this->array_factura[$i]["descli"]),1);*/

		/*if ($concepto==$this->array_factura[$i]["descli"]){
		    $totalmonto+=$this->array_factura[0]["monto"];
	  	    $totalobjeto+=$this->array_factura[0]["monto_objeto_retencion"];
	 	    $totalretenido+=$this->array_factura[0]["monto_retenido"];  
		
	         } */
	    
	    if ($concepto != $this->array_factura[$i]["descli"]){
		
		$totalgmonto+=$totalmonto;
		$totalgobjeto+=$totalobjeto;
		$totalgretenido+=$totalretenido;

		$concepto=$this->array_factura[$i]["descli"]; 	        
		$this->Ln(3);        	
		$this->SetX(8);
		$this->SetFont('Arial','B',8);
		$this->SetCeldas(array(0,0,0,0,1,1,1,1,1,0,1));
		$this->Row(array( '', '', '', 
		 '',
		 'TOTAL: ',
		 number_format($totalmonto, 2, ',', '.'),
		 number_format($totalobjeto, 0, ',', '.'),
		 '',
         	  number_format($totalretenido, 2, ',', '.'),
		));

			$this->Ln(5);
		   	$this->SetX(8);
			$this->Row(
                    array(  
                    $this->array_factura[$i]["descli"]),0);
		
		$totalmonto = '';  
		$totalobjeto = '';      
		$totalretenido = ''; 
	    }

	    
            $this->SetLeftMargin(15);
            $width = 5;
            $this->SetX(8);
            $this->SetFont('Arial','',6);
            
            $fecha_pago = date('d-m-Y', strtotime($this->array_factura[$i]["fecha"]));
	
            //$total = $sql3[0]["montoo"]+$sql[0]["montooEntregar"]+$sql2[0]["montooEntregado"]+$sql4[0]["montooPagado"];
               
            $this->Row(
                    array(  
                    $this->array_factura[$i]["prodesc"],
                    $this->array_factura[$i]["prorif"],
		    $this->array_factura[$i]["cod_factura"],
                    $fecha_pago,
		    $this->array_factura[$i]["cheque"],
		    $this->array_factura[$i]["monto_cheque"],
	            $this->array_factura[$i]["monto_objeto_retencion"],
                    $this->array_factura[$i]["porcentaje_retenido"],
                    $this->array_factura[$i]["monto_retenido"]),1);
		     

		    $totalmonto+=$this->array_factura[$i]["monto_cheque"];
	  	    $totalobjeto+=$this->array_factura[$i]["monto_objeto_retencion"];
	 	    $totalretenido+=$this->array_factura[$i]["monto_retenido"];  



	
	$Ttotal+=$total;
	$i++;

 }
		//$totalmonto+=$this->array_factura[0]["monto"];
	  	//$totalobjeto+=$this->array_factura[0]["monto_objeto_retencion"];
	 	//$totalretenido+=$this->array_factura[0]["monto_retenido"];    

		$this->Ln(3);    
        	$this->SetX(8);
		$this->SetFont('Arial','B',8);
		$this->SetCeldas(array(0,0,0,0,1,1,1,1,1,0,1));
		$this->Row(array( '', '', '', 
		 '',
		 'TOTAL: ',
		 number_format($totalmonto, 2, ',', '.'),
		 number_format($totalobjeto, 0, ',', '.'),
		 '',
         	  number_format($totalretenido, 2, ',', '.'),
		));


		$totalgmonto+=$totalmonto;
		$totalgobjeto+=$totalobjeto;
		$totalgretenido+=$totalretenido;

		$this->Ln(3);    
        	$this->SetX(8);
		$this->SetFont('Arial','B',8);
		$this->SetCeldas(array(0,0,0,0,1,1,1,1,1,0,1));
		$this->Row(array( '', '', '', 
		 '',
		 'TOTAL GENERAL: ',
		 number_format($totalgmonto, 2, ',', '.'),
		 number_format($totalgobjeto, 0, ',', '.'),
		 '',
         	  number_format($totalgretenido, 2, ',', '.'),
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
$fecha2= @$_GET['fecha_hasta'];



//$fechaz=$fecha."-01";
/*
echo "SELECT prov.*, medic.* FROM proveedores prov INNER JOIN cxp_factura_medico medic ON prov.id_proveedor = medic.medico_fk WHERE fecha_fac BETWEEN '".$_GET["fecha"]."' and '".$_GET["fecha_hasta"]."'";*/

	$comunes = new ConexionComun();
	$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

        $fecha = @$_GET["fecha"];

//echo $fecha;

$mes=mesaletras(substr($fecha,0,2));
$anio=substr($fecha,3,4);
$diaIni=$anio."-".substr($fecha,0,2)."-01";
$diaFin=$anio."-".substr($fecha,0,2)."-".date("t",mktime(0, 0, 0, substr($fecha,0,2), 1,$anio));
$cad=$mes." ".$anio;
$periodo=$anio.substr($fecha,0,2);
$comunes = new ConexionComun();


$fechaz=$fecha."-01";

$datosgenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

/*
echo ("SELECT cxpf.*, pro.rif as prorif , che.*,cxpfd.monto_base AS monto_objeto_retencion, cxpfd.cod_impuesto AS cod_isrl, pro.descripcion as prodesc, cxpfd.*, cxpedod.*, li.codificacion_impuesto, li.descripcion as descli  FROM cxp_factura cxpf JOIN cxp_edocuenta cxpe ON ( cxpf.id_cxp_edocta = cxpe.cod_edocuenta) JOIN proveedores pro ON ( pro.id_proveedor = cxpe.id_proveedor ) JOIN cxp_factura_detalle cxpfd ON (cxpfd.id_factura_fk=cxpf.id_factura) JOIN lista_impuestos li ON (li.cod_impuesto=cxpfd.cod_impuesto) INNER JOIN cxp_edocuenta_detalle cxpedod ON (cxpedod.cod_edocuenta  = cxpe.cod_edocuenta) INNER JOIN cheque che ON (cxpedod.numero = che.ref) where fecha_recepcion BETWEEN '".$diaIni."' and '".$diaFin."' GROUP BY cod_isrl ORDER BY descli,fecha_factura");
*/

$array_factura =   $comunes->ObtenerFilasBySqlSelect("SELECT cxpf. * , pro.rif AS prorif, che. * , cxpfd.monto_base AS monto_objeto_retencion, che.monto AS monto_cheque, cxpfd.cod_impuesto AS cod_isrl, pro.descripcion AS prodesc, cxpfd. * , cxpedod. * , li.codificacion_impuesto, li.descripcion AS descli
FROM cxp_factura cxpf JOIN cxp_edocuenta cxpe ON ( cxpf.id_cxp_edocta = cxpe.cod_edocuenta ) JOIN proveedores pro ON ( pro.id_proveedor = cxpe.id_proveedor )
JOIN cxp_factura_detalle cxpfd ON ( cxpfd.id_factura_fk = cxpf.id_factura ) JOIN lista_impuestos li ON ( li.cod_impuesto = cxpfd.cod_impuesto ) INNER JOIN cxp_edocuenta_detalle cxpedod ON ( cxpedod.cod_edocuenta = cxpe.cod_edocuenta ) INNER JOIN cheque che ON ( cxpedod.numero = che.ref ) where fecha_recepcion BETWEEN '".$diaIni."' and '".$diaFin."' ORDER BY descli");








//$mes=mesaletras(substr($fecha,5,2));

$pdf=new PDF('L','mm','A4');
$title='LISTADO DE ANALITICOS';
//$fecha=mesaletras(substr($fecha,5,2))
$pdf->fecha=$fecha;
$pdf->periodo=$periodo;
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();

?>


