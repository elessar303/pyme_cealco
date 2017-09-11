<?php 
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');
require_once '../lib/config.php';
require_once '../lib/common.php';
include('../lib/numerosALetras.class.php');


class PDF extends FPDF
{
var $sector;
var $programa;
var $actividad;

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

	$date1=date('d/m/Y');
	$date2=date('h:i a');
	$this->SetFont("Arial","B",10);
	$this->Cell(260,5,'FECHA: '.$date1,0,1,'R');
	$this->Cell(260,5,'HORA: '.$date2,0,1,'R');
	$this->Cell(260,5,utf8_decode('REPORTE DE CHEQUES EMITIDOS'),0,1,'C');
	$this->Cell(260,5,utf8_decode('Desde: ').fecha($_GET['fechaDesde']).' Hasta: '.fecha($_GET['fechaHasta']),0,1,'C');
	
       
    


	
}



//Hacer que sea multilinea sin que haga un salto de linea
var $widths;
var $aligns;
var $celdas;
var $ancho;
var $nro_ocs;
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
// Marco de la celda
function Setceldas($cc)
{
   
    $this->celdas=$cc;
} 
// Ancho de la celda
function Setancho($aa)
{
    $this->ancho=$aa;
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
        $this->MultiCell($w,$this->ancho[$i],$data[$i],$this->celdas[$i],$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
//fin


function imprimir_tabla ($pdf)
{
	$cantidad_registros=22;
	$conexion=conexion();
	$fechaDesde=$_GET['fechaDesde'];
	$fechaHasta=$_GET['fechaHasta'];
	
	if($_GET['banco']==0){
		$consulta = "SELECT * FROM cheques WHERE  Fecha BETWEEN '".$fechaDesde."' AND '".$fechaHasta."'  ORDER BY consecutivo_E";
	}
	else{
		$consultaBan = "SELECT * FROM bancos WHERE codigo='".$_GET['banco']."'";
		$resultadoB = query($consultaBan, $conexion);
		$filaB = fetch_array($resultadoB);
		$cuentaBanco = $filaB['cuenta'];
		$consulta = "SELECT * FROM cheques WHERE cuenta='".$cuentaBanco."' AND Fecha BETWEEN '".$fechaDesde."' AND '".$fechaHasta."'";
	}
	
	
        $rs = query($consulta, $conexion);
	$num_paginas=obtener_num_paginas($consulta,$cantidad_registros);
	$total= num_rows($rs);

	$this->SetFont('Arial','B',10);
	$this->Ln(10);
	//Cabecera
	$this->Cell(20,7,utf8_decode('Egreso'),1,0,'C');
	$this->Cell(20,7,utf8_decode('Orden'),1,0,'C');
	$this->Cell(30,7,utf8_decode('Banco'),1,0,'C');
	$this->Cell(45,7,utf8_decode('N° de Cuenta'),1,0,'C');
	$this->Cell(30,7,utf8_decode('N° del Cheque'),1,0,'C');
	$this->Cell(60,7,utf8_decode('Beneficiario'),1,0,'C');
	$this->Cell(30,7,'Fecha Emision',1,0,'C');
	$this->Cell(30,7,'Monto',1,1,'C');
	
	
			
	//Datos

	$cont=1;
	$contar=1;
	$pagina=1;
	$totalfinal=0;
	while ($total>=$contar)
	{
		$fila = fetch_array($rs);
		$cuenta=$fila['cuenta'];
		$conexion=conexion();
		$conbanco="select * from bancos where cuenta='$cuenta'";
		$querybanco=query($conbanco,$conexion);
		$banco=fetch_array($querybanco);
		
		
		$lineas=strlen($fila['beneficiario'])/28;
		//$this->Cell(100,5,$lineas,0,1);
		if($lineas>1){
			$cantidad_registros-=round($lineas);
			if($cont>=$cantidad_registros){
				$this->Ln(200);
				//Cabecera
				$this->Cell(20,7,utf8_decode('Egreso'),1,0,'C');
				$this->Cell(20,7,utf8_decode('Orden'),1,0,'C');
				$this->Cell(30,7,utf8_decode('Banco'),1,0,'C');
				$this->Cell(45,7,utf8_decode('N° de Cuenta'),1,0,'C');
				$this->Cell(30,7,utf8_decode('N° del Cheque'),1,0,'C');
				$this->Cell(60,7,utf8_decode('Beneficiario'),1,0,'C');
				$this->Cell(30,7,'Fecha Emision',1,0,'C');
				$this->Cell(30,7,'Monto',1,1,'C');
				
				$cont=1;
				$cantidad_registros=22;
			}
				
		}
		
		$bb=substr( $banco['descripcion'], 0, 10 );
		// llamado para hacer multilinea sin que haga salto de linea
		$this->SetFont('Arial','I',10);
		$this->SetWidths(array(20,20,30,45,30,60,30,30));
		$this->Setancho(array(5,5,5,5,5,5,5,5));
		$this->SetAligns(array('C','C','L','C','C','J','R','R'));
		if($fila[status]=='Da'){
			$this->Row(array($fila['consecutivo_E'],$fila['orden'],$bb,$cuenta,$fila['cheque'],$fila['beneficiario'],fecha($fila['fecha']),utf8_decode('Dañado')));
		}else{
			if($fila[status]=='An'){
				$this->Row(array($fila['consecutivo_E'],$fila['orden'],$bb,$cuenta,$fila['cheque'],$fila['beneficiario'],fecha($fila['fecha']),'Anulado'));
			}else{
				$this->Row(array($fila['consecutivo_E'],$fila['orden'],$bb,$cuenta,$fila['cheque'],$fila['beneficiario'],fecha($fila['fecha']),number_format($fila['monto'],2,',','.')));
				$totalfinal+=$fila['monto'];
			}

		}
		
		$cheque++;
	// fin
	//echo $cont;
		if($cont>=$cantidad_registros)
		{	
			if ($pagina!=$num_paginas){
				$this->Ln(50);
				$pagina++;
				
				//Cabecera
				$this->Cell(20,7,utf8_decode('Egreso'),1,0,'C');
				$this->Cell(20,7,utf8_decode('Orden'),1,0,'C');
				$this->Cell(30,7,utf8_decode('Banco'),1,0,'C');
				$this->Cell(45,7,utf8_decode('N° de Cuenta'),1,0,'C');
				$this->Cell(30,7,utf8_decode('N° del Cheque'),1,0,'C');
				$this->Cell(60,7,utf8_decode('Beneficiario'),1,0,'C');
				$this->Cell(30,7,'Fecha Emision',1,0,'C');
				$this->Cell(30,7,'Monto',1,1,'C');		
				$cont=1;
				$cantidad_registros=22;
			}
		}else{$cont++;}
		//echo $contar;
		$contar++;
		
	}
	$this->Ln(10);	

	$this->SetFont('Arial','B',10);
	$this->SetWidths(array(50,185,30));
	$this->Setancho(array(5,5,5));
	$this->SetAligns(array('C','C','R'));
	$this->Row(array(' Cantidad Cheques= '.$cheque,' TOTAL:',number_format($totalfinal,2,',','.')));
	$this->Ln();
}
function Footer()
{
	$this->SetY(-15);
	$this->SetFont('Arial','I',8);
    	$this->Cell(0,5,'Elaborado Por: '.$_SESSION['nombre'],0,1,'L');
	$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');

}
}


//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);



$pdf->imprimir_tabla($pdf);

$pdf->Output();
?>