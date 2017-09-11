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
     	$this->Image($var_imagen_izq,8,6,25,25);

     	$this->Cell(300,7,utf8_decode($var_encabezado1),0,1,"C");
     	$this->Image($var_imagen_der,300,6,25,25);
     	$this->Cell(300,7,utf8_decode($var_encabezado2),0,1,"C");
     	$this->Cell(300,7,utf8_decode($var_encabezado3),0,1,"C");
     	$this->Cell(300,7,utf8_decode($var_encabezado4),0,1,"C");
	
	$date1=date('d/m/Y');
	$date2=date('h:i a');
	$this->SetFont("Arial","B",10);
		
	$this->Cell(300,5,utf8_decode('LISTADO DE EJECUCIÓN DEL COMPROMISO'),0,0,'C');
	$this->Cell(30,5,'FECHA: '.$date1,0,1,'R');
	$this->Cell(300,5,utf8_decode('Desde :'.$_GET['fechad'].' Hasta: '.$_GET['fechad']),0,0,'C');
	$this->Cell(30,5,'HORA: '.$date2,0,1,'R');
	
	$sector = $_GET['sector'];
	$programa = $_GET['programa'];
	$actividad = $_GET['actividad'];
	$conexion=conexion();

	$conSector = "SELECT * FROM cwsector WHERE RecNo='".$sector."'";
	$resSector = query($conSector, $conexion);
	$filaSector = fetch_array($resSector);
	$this->sector = $filaSector['Sec'];
	$desSector = $filaSector['Denominacion'];
	
	$conPrograma = "SELECT * FROM cwprogra WHERE RecNo='".$programa."'";
	$resPrograma = query($conPrograma, $conexion);
	$filaPrograma = fetch_array($resPrograma);
	$this->programa = $filaPrograma['Programa'];
	$desPrograma = $filaPrograma['Denominacion'];
	
	$conActividad = "SELECT * FROM cwpreact WHERE RecNo='".$actividad."'";
	$resActividad = query($conActividad, $conexion);
	$filaActividad = fetch_array($resActividad);
	$this->actividad = $filaActividad['Obr'];
	$desActividad = $filaActividad['Denominacion'];

	if($sector!=0){
		$this->Cell(300,5,utf8_decode('Sector: '.$desSector),0,1,'L');
		$this->Cell(300,5,utf8_decode('Programa: '.$desPrograma),0,1,'L');
		$this->Cell(300,5,utf8_decode('Actividad: '.$desActividad),0,1,'L');
	}
       	$this->Ln(10);

	
    

	

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


function imprimir_tabla ($pdf,$nivel)
{
	$cantidad_registros=23;
	$conexion=conexion();

	$fechaD = fecha_sql($_GET['fechad']);
	$fechaH = fecha_sql($_GET['fechah']);
	$codSector=$this->sector;
	$codPrograma=$this->programa;
	$codActividad=$this->actividad;


	if($codSector==0){
		$consulta = "SELECT * FROM cwpreejc WHERE Fecha BETWEEN '".$fechaD."' AND '".$fechaH."' AND Marca='X' ORDER BY Partida";
	}else if($codPrograma==0){
		$consulta = "SELECT * FROM cwpreejc WHERE Sector='".$codSector."'  AND Fecha BETWEEN '".$fechaD."' AND '".$fechaH."' AND Marca='X' ORDER BY Partida";
		
	}else if ($codActividad==0){
		$consulta = "SELECT * FROM cwpreejc WHERE Sector='".$codSector."' AND Programa='".$codPrograma."'  AND Fecha BETWEEN '".$fechaD."' AND '".$fechaH."' AND Marca='X' ORDER BY Partida";
		
	}else{
		$consulta = "SELECT * FROM cwpreejc WHERE Sector='".$codSector."' AND Programa='".$codPrograma."' AND Actividad='".$codActividad."' AND Fecha BETWEEN '".$fechaD."' AND '".$fechaH."' AND Marca='X' ORDER BY Partida";
		
	}
	
	

        $rs = query($consulta, $conexion);
	
	$total= num_rows($rs);

	$this->SetFont('Arial','B',10);
	
	//Cabecera
	$this->Cell(30,7,'Partida',1,0,'C');
	$this->Cell(30,7,'Fecha',1,0,'C');
	$this->Cell(120,7,'Beneficiario',1,0,'C');
	$this->Cell(40,7,'Orden',1,0,'C');
	$this->Cell(40,7,'Monto',1,1,'C');
	$this->Ln(2);
	
			
	//Datos

	$cont=1;
	$contar=1;
	$pagina=1;
	$totalfinal=0;
	$partida='';
	while ($total>=$contar)
	{
		$fila = fetch_array($rs);
		
		if($partida==''){
			$partida=$fila['Partida'];
			$imp=$partida;
			
		}else{
			$imp='';
		}
		
		if($partida!=$fila['Partida']){
			$totMontomonto+=$totMonto;
			$totMontosaldo+=$totSaldo;
			$this->SetFont('Arial','B',10);
			$this->SetWidths(array(220,40,40));
			$this->Setancho(array(5,5,5));
			$this->SetAligns(array('C','R','R'));
			$this->Row(array('TOTAL COMPROMISOS POR PARTIDA:',$totMontoI));
			$this->Ln(200);
			$this->Cell(30,7,'Partida',1,0,'C');
			$this->Cell(30,7,'Fecha',1,0,'C');
			$this->Cell(120,7,'Beneficiario',1,0,'C');
			$this->Cell(40,7,'Orden',1,0,'C');
			$this->Cell(40,7,'Monto',1,1,'C');
			$this->Ln(2);			
			$cont=1;
			$cantidad_registros=23;
			$partida=$fila['Partida'];
			$imp=$partida;
		}else{
			if($cont!=1)
				$imp='';
		}


		$fecha = $fila['Fecha'];
		$orden = $fila['RecNoOrders'];
		$mont = $fila['Monto'];
		$sald = $fila['saldo'];
		$conexion=conexion();

		$conBen = "SELECT * FROM ordenes WHERE codigo='".$orden."'";
		$resBen = query($conBen, $conexion);
		$filaBen = fetch_array($resBen);


		$codbene = $filaBen['cod_provee'];
		$Ben = "SELECT * FROM proveedores WHERE cod_proveedor='".$codbene."'";
		$resBen2 = query($Ben, $conexion);
		$filaBen2 = fetch_array($resBen2);

		$monto = number_format($mont,2,',','.');
		$saldo = number_format($sald,2,',','.');
		
		$totMonto+=$mont;
		$totMontoI = number_format($totMonto,2,',','.');//Monto Inicial

		$totSaldo+=$sald;
		$totSaldoI = number_format($totSaldo,2,',','.'); //Monto Aumento
				
		$lineas=strlen($filaBen2[compania])/120;
		//$this->Cell(100,5,$lineas,0,1);
		if($lineas>1){
			$cantidad_registros-=round($lineas);
			if($cont>=$cantidad_registros){
				$this->Ln(200);
				//Cabecera
				$this->Cell(30,7,'Partida',1,0,'C');
				$this->Cell(30,7,'Fecha',1,0,'C');
				$this->Cell(120,7,'Beneficiario',1,0,'C');
				$this->Cell(40,7,'Orden',1,0,'C');
				$this->Cell(40,7,'Monto',1,1,'C');
				$this->Ln(2);			
				$cont=1;
				$cantidad_registros=23;
			}
				
		}
		// llamado para hacer multilinea sin que haga salto de linea
		$this->SetFont('Arial','I',10);
		$this->SetWidths(array(30,30,120,40,40,40));
		$this->Setancho(array(5,5,5,5,5,5));
		$this->SetAligns(array('C','C','L','R','R','R'));
		$this->Row(array($imp,fecha($fecha),utf8_decode($filaBen2['compania']),$orden,$monto));
		$cont++;
		
	// fin
	//echo $cont;
		
		if($cont>=$cantidad_registros)
		{	
				//$this->Footer();
				$this->Ln(100);
				//$pagina++;
				//$this->Cell(100,5,$cont.' -'.$cantidad_registros,0,1);
				//Cabecera
				$this->Cell(30,7,'Partida',1,0,'C');
				$this->Cell(30,7,'Fecha',1,0,'C');
				$this->Cell(120,7,'Beneficiario',1,0,'C');
				$this->Cell(40,7,'Orden',1,0,'C');
				$this->Cell(40,7,'Monto',1,1,'C');
				$this->Ln(2);			
				$cont=1;
				$cantidad_registros=23;
			
		}
		//echo $contar;
		$contar++;
		
	}
	$this->Ln(10);	

	$this->SetFont('Arial','B',10);
	$this->SetWidths(array(220,40,40));
	$this->Setancho(array(5,5,5));
	$this->SetAligns(array('C','R','R'));
	$this->Row(array('TOTAL COMPROMISOS:',$totMontomonto));
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
$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);


$nivel = $_GET['nivel'];

$pdf->imprimir_tabla($pdf,$nivel);

$pdf->Output();
?>