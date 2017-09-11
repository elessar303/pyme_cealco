<?php
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');

require_once '../lib/config.php';
require_once '../lib/pdfcommon.php';
require_once '../lib/common.php';
//include ("../selectra/header.php");


$cantidad_registros=13;
$fechaDesde = $_GET['fechaDesde'];
$fechaHasta = $_GET['fechaHasta'];

$conexion=conexion();


$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
$rs = query($consulta_req,$conexion);
$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);




class PDF extends FPDF
{
//Cabecera de página
function Header()
{
        $Conn=conexion_conf();
	$var_sql="select encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der from parametros";
	$rs = query($var_sql,$Conn);
	$row_rs = fetch_array($rs);
	$var_encabezado1=$row_rs['encabezado1'];
	$var_encabezado2=$row_rs['encabezado2'];
	$var_encabezado3=$row_rs['encabezado3'];
	$var_encabezado4=$row_rs['encabezado4'];
	$var_imagen_izq=$row_rs['imagen_izq'];
	$var_imagen_der=$row_rs['imagen_der'];	
	$var_sql="select codigo,nomemp,departamento,presidente,periodo,cargo,nivel,desislr,ctaisrl,desiva,ctaiva,por_isv,compra,servicio,rif,nit,direccion,telefono,por_im,por_bomberos,lugar,sobregirop,autorizacionodp,claveodp,contrato,gas_dir from parametros";
	$rsu = query($var_sql,$Conn);
	$row_rsu = fetch_array($rsu);
	$var_nomemp=$row_rsu['nomemp'];

	cerrar_conexion($Conn);

        $this->SetFont("Arial","B",10);
     	$this->Image($var_imagen_izq,8,6,25);
     	$this->Ln();
     	$this->Cell(35);
        
     	$this->Cell(170,7,utf8_decode($var_encabezado1),0,0,"C");
     	$this->Image($var_imagen_der,230,10,33);
     	$this->Ln(5);
     	$this->Cell(35);
     	$this->Cell(170,7,utf8_decode($var_encabezado2),0,0,"C");
     	$this->Ln(5);
     	$this->Cell(35);
     	$this->Cell(170,7,utf8_decode($var_encabezado3),0,0,"C");
     	$this->Ln(5);
     	$this->Cell(35);
     	$this->Cell(170,7,utf8_decode($var_encabezado4),0,0,"C");
	$this->Ln(5);

}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-28);
    //Arial italic 8
    $this->SetFont('Arial','I',10);
    $this->Cell(87,7,utf8_decode('Dirección General'),'LT',0,'C');
    $string=utf8_decode('Administración');
    $this->Cell(87,7,$string,'LT',0,'C');
    $this->Cell(86,7,'Unidad Solicitante','LTR',1,'C');
    $this->Cell(87,5,'','L',0);
    $this->Cell(87,5,'','L',0);
    $this->Cell(86,5,'','LR',1);
    $this->Cell(87,7,'Firma y Sello','LB',0,'C');
    $this->Cell(87,7,'Firma y Sello','LB',0,'C');
    $this->Cell(86,7,'Firma y Sello','LBR',1,'C');

    
     $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');

    //Número de página
   // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
//Hacer que sea multilinea sin que haga un salto de linea
var $widths;
var $aligns;

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
} 
function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
} 
function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
} 
function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
} 

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        //$this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
/// fin

function imprimir_datos($pagina,$num_paginas,$fechaDesde,$fechaHasta)
{
	
	
	$fech=fecha($var_fecha);
	$this->SetFont('Arial','B',10);
	$this->Cell(230,7,"Fecha:  ".date('d/m/Y'),0,0,'R');
	$this->Cell(30,7,"Hora:  ".date('h:i a'),0,1,'L');
	$this->SetFont('Arial','B',12);
     	$this->Cell(35);
        $string=utf8_decode('LISTADO DE RETENCIÓN DE I.V.A. ');
 	$this->Cell(170,8,$string.$var_nom_tipo,0,0,'C');
	$string1=utf8_decode('Pág.: ');
   	$this->Cell(50,8,$string1.$pagina.'/'.$num_paginas,0,1,'L');
    	
	$this->SetFont('Arial','I',10);
	$this->Cell(35);
	
	$this->Cell(170,8,"Desde: ".fecha($fechaDesde)."      Hasta: ".fecha($fechaHasta),0,1,'C');
	
        $this->Ln();
        
        


}



function imprimir_tabla($pdf,$pagina,$num_paginas,$fechaDesde,$fechaHasta){
	$cantidad_registros=13;
	$conexion=conexion();
	$consulta = "SELECT * FROM ordenes_pago WHERE fecche BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' AND estado<> 'Anulada' AND monto_igv<>'0' AND con1<>'0' AND cheque<>0 ORDER BY fecche";
	$rs = query($consulta,$conexion);
	$num_paginas = obtener_num_paginas($consulta,$cantidad_registros);
	$total= num_rows($rs);
	//Cabecera
	$this->Cell(60,7,'Beneficiario','LTB',0,'L');
	$this->Cell(25,7,'RIF','TB',0,'C');
	$this->Cell(25,7,'Fecha ch.','TB',0,'C');
	$this->Cell(25,7,'Cheque','TB',0,'C');
	$this->Cell(25,7,'Base','TB',0,'C');
	$this->Cell(25,7,'Monto Pagado','TB',0,'C');
	$this->Cell(25,7,'Monto IVA','TB',0,'C');
	$this->Cell(25,7,'Monto Retenido','TB',0,'C');
	$this->Cell(25,7,'% Ret.','TRB',0,'C');
	$this->Ln();
	//Datos
	$cont=1;
	$contar=1;
	$pagina=1;
	$reg=0;
	$totalRet=0;
	while ($total>=$contar)
	{
	
	$row_rs = fetch_array($rs);
	$reg++;
	 // llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(60,25,25,25,25,25,25,25,25));
	$this->SetAligns(array('L','C','C','C','C','C','C','C','C'));
        $this->Row(array($row_rs['bene'],$row_rs['rif'],fecha($row_rs['fecche']),$row_rs['cheque'],number_format($row_rs['monto'], 2, ',', '.'),number_format($row_rs['montopago'], 2, ',', '.'),number_format($row_rs['monto_igv'], 2, ',', '.'),number_format($row_rs['monto_retiva'], 2, ',', '.'),number_format($row_rs['con1'], 2, ',', '.')));
	$totalMonto += $row_rs['monto'];
	$totalMontoPago += $row_rs['montopago'];
	$totalIVA += $row_rs['monto_igv'];
	$totalRet += $row_rs['monto_retiva'];
	
	// fin
	//echo $cont;
	if($cont==$cantidad_registros)
	{	
		if ($pagina!=$num_paginas){
			$this->Ln(60);
			$pagina++;
			$pdf->imprimir_datos($pagina,$num_paginas,$fechaDesde,$fechaHasta);
			//Cabecera
			$this->Cell(50,7,'Beneficiario','LTB',0,'L');
			$this->Cell(25,7,'RIF','TB',0,'C');
			$this->Cell(25,7,'Fecha ch.','TB',0,'C');
			$this->Cell(25,7,'Cheque','TB',0,'C');
			$this->Cell(25,7,'Base','TB',0,'C');
			$this->Cell(25,7,'Monto Pagado','TB',0,'C');
			$this->Cell(25,7,'Monto IVA','TB',0,'C');
			$this->Cell(25,7,'Monto Retenido','TB',0,'C');
			$this->Cell(25,7,'% Ret.','TRB',0,'C');
			$this->Ln();
			$cont=1;
		}
	}else{$cont++;}
        //echo $contar;
	 $contar++;
	
	}
	if($cont<=$cantidad_registros)
	{
		$this->Cell(135,7,'TOTALES GENERALES Bs.--->',0,0,'L');
		$this->Cell(25,7,number_format($totalMonto, 2, ',', '.'),0,0,'C');
		$this->Cell(25,7,number_format($totalMontoPago, 2, ',', '.'),0,0,'C');
		$this->Cell(25,7,number_format($totalIVA, 2, ',', '.'),0,0,'C');
		$this->Cell(25,7,number_format($totalRet, 2, ',', '.'),0,1,'C');
		
		$this->Cell(188,7,utf8_decode('Número de Retenciones:').$reg,0,1,'L');

	
	}

	


}
}


//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
//for($i=1;$i<=40;$i++)
  //  $pdf->Cell(0,10,'Imprimiendo línea número '.$i,0,1);
//
$conexion=conexion();
$fechaDesde = (empty($_REQUEST['fechaDesde'])) ? '' : $_REQUEST['fechaDesde'];
$fechaHasta = (empty($_REQUEST['fechaHasta'])) ? '' : $_REQUEST['fechaHasta'];

$consulta = "SELECT * FROM ordenes_pago WHERE fecche BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' AND estado<> 'Anulada' AND monto_igv<>'0' AND con1<>'0' AND cheque<>0 ORDER BY fecche";
$rs = query($consulta,$conexion);
$num_paginas = obtener_num_paginas($consulta,$cantidad_registros);
$pagina = obtener_pagina_actual($pagina, $num_paginas);

//

$pdf->imprimir_datos($pagina,$num_paginas,$fechaDesde,$fechaHasta);

$pdf->imprimir_tabla($pdf,$pagina,$num_paginas,$fechaDesde,$fechaHasta);

$pdf->Output();
?>