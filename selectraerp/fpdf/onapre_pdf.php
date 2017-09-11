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
var $ano;


//Cabecera de página
function Header()
{
	 $Conn=conexion_conf();
	$var_sql="select encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der,periodo from parametros";
	$rs = query($var_sql,$Conn);
	$row_rs = fetch_array($rs);
	$var_encabezado1=$row_rs['encabezado1'];
	$var_encabezado2=$row_rs['encabezado2'];
	$var_encabezado3=$row_rs['encabezado3'];
	$var_encabezado4=$row_rs['encabezado4'];
	$var_imagen_izq=$row_rs['imagen_izq'];
	$var_imagen_der=$row_rs['imagen_der'];	
	$this->ano=$row_rs['periodo'];
	
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
		
	$this->Cell(300,5,utf8_decode('EJECUCION PRESUPUETARIA'),0,0,'C');
	$this->Cell(30,5,'FECHA: '.$date1,0,1,'R');
	$this->Cell(300,5,utf8_decode('AÑO').$this->ano,0,0,'C');
	$this->Cell(30,5,'HORA: '.$date2,0,1,'R');
	$this->Cell(300,5,utf8_decode('(Expresado en Bolivares Fuertes)'),0,1,'C');
       $this->Ln(5);
    

	

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
	$consulta = "SELECT * FROM cwprecue";
	
        $rs = query($consulta, $conexion);
	
	$total= num_rows($rs);

	$this->SetFont('Arial','I',10);
	
	//Cabecera
	
	$this->Cell(30,7,'Partida',1,0,'C');
	$this->Cell(80,7,utf8_decode('Denominación'),1,0,'C');
	$this->Cell(54,7,'1er Trimestre',1,0,'C');
	$this->Cell(54,7,'2do Trimestre',1,0,'C');
	$this->Cell(54,7,'3er Trimestre',1,0,'C');
	$this->Cell(54,7,'4to Trimestre',1,1,'C');
	$this->Cell(30,7,'',0,0,'C');
	$this->Cell(80,7,'',0,0,'C');
	$this->Cell(27,7,'Estimado','LRB',0,'C');
	$this->Cell(27,7,'Real','LRB',0,'C');
	$this->Cell(27,7,'Estimado','LRB',0,'C');
	$this->Cell(27,7,'Real','LRB',0,'C');
	$this->Cell(27,7,'Estimado','LRB',0,'C');
	$this->Cell(27,7,'Real','LRB',0,'C');
	$this->Cell(27,7,'Estimado','LRB',0,'C');
	$this->Cell(27,7,'Real','LRB',1,'C');
			
	//Datos

	$cont=1;
	$contar=1;
	$pagina=1;
	$totalfinal=0;
	while ($total>=$contar)
	{
		$fila = fetch_array($rs);
		$conexion=conexion();
		$consulta2 = "SELECT SUM(Monto) as Monto FROM cwprepar WHERE Codigo = '".$fila['CodCue']."' ";
		$resultado2 = query($consulta2,$conexion);
		$fetch = fetch_array($resultado2);
		if($fetch['Monto']!=0)
		{
			$consulta3 = " SELECT SUM(Monto) AS comprometido1 FROM cwpreejc WHERE Partida LIKE '%".$fila['CodCue']."%' AND Fecha BETWEEN '".$this->ano."-01-01' 	AND '".$this->ano."-03-31' ";
			$resultado3 = query($consulta3,$conexion);
			$fetch3 = fetch_array($resultado3);
			
			$consulta4 = " SELECT SUM(Monto) AS comprometido2 FROM cwpreejc WHERE Partida LIKE '%".$fila['CodCue']."%' AND Fecha BETWEEN '".$this->ano."-04-01' 	AND '".$this->ano."-06-30' ";
			$resultado4 = query($consulta4,$conexion);
			$fetch4 = fetch_array($resultado4);
			
			$consulta5 = " SELECT SUM(Monto) AS comprometido3 FROM cwpreejc WHERE Partida LIKE '%".$fila['CodCue']."%' AND Fecha BETWEEN '".$this->ano."-07-01' 	AND '".$this->ano."-09-30' ";
			$resultado5 = query($consulta5,$conexion);
			$fetch5 = fetch_array($resultado5);
			
			$consulta6 = " SELECT SUM(Monto) AS comprometido4 FROM cwpreejc WHERE Partida LIKE '%".$fila['CodCue']."%' AND Fecha BETWEEN '".$this->ano."-10-01' 	AND '".$this->ano."-12-31' ";
			$resultado6 = query($consulta6,$conexion);
			$fetch6 = fetch_array($resultado6);


			$codigo = $fila['CodCue'];
			$conexion=conexion();

			$montoAct = $fetch['Monto'] / 4;
			$montoActualizado = number_format($montoAct,2,',','.');
			
			$comp1 = number_format($fetch3['comprometido1'],2,',','.');
			$comp2 = number_format($fetch4['comprometido2'],2,',','.');
			$comp3 = number_format($fetch5['comprometido3'],2,',','.');
			$comp4 = number_format($fetch6['comprometido4'],2,',','.');	
			$descripcion = $fila['Denominacion'];

			if($fila['Tipocta']==4){
				$totMS+=$montoAct;
				$totalMS = number_format($totMS,2,',','.');
				$totMS1+=$fetch3['comprometido1'];
				$totalMS1 = number_format($totMS1,2,',','.');
				$totMS2+=$fetch4['comprometido2'];
				$totalMS2 = number_format($totMS2,2,',','.'); //Monto Aumento
				$totMS3+=$fetch5['comprometido3'];
				$totalMS3 = number_format($totMS3,2,',','.');//Monto
				$totMS4+=$fetch6['comprometido4'];
				$totalMS4 = number_format($totMS4,2,',','.');//Monto Comprometido
			}
		
		$lineas=strlen($descripcion)/46;
			//$this->Cell(100,5,$lineas,0,1);
			if($lineas>1){
				$cantidad_registros-=round($lineas);
				
				if($cont>=$cantidad_registros){
					$this->Ln(200);
					//$pagina++;
					//$this->Cell(100,5,$cont.' -'.$cantidad_registros,0,1);
					//Cabecera
					$this->Cell(30,7,'Partida',1,0,'C');
					$this->Cell(80,7,utf8_decode('Denominación'),1,0,'C');
					$this->Cell(54,7,'1er Trimestre',1,0,'C');
					$this->Cell(54,7,'2do Trimestre',1,0,'C');
					$this->Cell(54,7,'3er Trimestre',1,0,'C');
					$this->Cell(54,7,'4to Trimestre',1,1,'C');
					$this->Cell(30,7,'',0,0,'C');
					$this->Cell(80,7,'',0,0,'C');
					$this->Cell(27,7,'Estimado','LRB',0,'C');
					$this->Cell(27,7,'Real','LRB',0,'C');
					$this->Cell(27,7,'Estimado','LRB',0,'C');
					$this->Cell(27,7,'Real','LRB',0,'C');
					$this->Cell(27,7,'Estimado','LRB',0,'C');
					$this->Cell(27,7,'Real','LRB',0,'C');
					$this->Cell(27,7,'Estimado','LRB',0,'C');
					$this->Cell(27,7,'Real','LRB',1,'C');
					$this->Ln(2);			
					$cont=1;
					$cantidad_registros=23;
				}
				
			}
			// llamado para hacer multilinea sin que haga salto de linea
			$this->SetFont('Arial','I',10);
			$this->SetWidths(array(30,80,27,27,27,27,27,27,27,27));
			$this->Setancho(array(5,5,5,5,5,5,5,5,5,5));
			$this->SetAligns(array('L','L','R','R','R','R','R','R','R','R'));
			$this->Row(array($codigo,utf8_encode($descripcion),$montoActualizado,$comp1,$montoActualizado,$comp2,$montoActualizado,$comp3,$montoActualizado,$comp4));
			
			$cont++;
		}
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
				$this->Cell(80,7,utf8_decode('Denominación'),1,0,'C');
				$this->Cell(54,7,'1er Trimestre',1,0,'C');
				$this->Cell(54,7,'2do Trimestre',1,0,'C');
				$this->Cell(54,7,'3er Trimestre',1,0,'C');
				$this->Cell(54,7,'4to Trimestre',1,1,'C');
				$this->Cell(30,7,'',0,0,'C');
				$this->Cell(80,7,'',0,0,'C');
				$this->Cell(27,7,'Estimado','LRB',0,'C');
				$this->Cell(27,7,'Real','LRB',0,'C');
				$this->Cell(27,7,'Estimado','LRB',0,'C');
				$this->Cell(27,7,'Real','LRB',0,'C');
				$this->Cell(27,7,'Estimado','LRB',0,'C');
				$this->Cell(27,7,'Real','LRB',0,'C');
				$this->Cell(27,7,'Estimado','LRB',0,'C');
				$this->Cell(27,7,'Real','LRB',1,'C');
				$this->Ln(2);			
				$cont=1;
				$cantidad_registros=23;
			
		}
		//echo $contar;
		$contar++;
		
	}
	$this->Ln(10);	

	$this->SetFont('Arial','B',10);
	$this->SetWidths(array(110,27,27,27,27,27,27,27,27));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5,5));
	$this->SetAligns(array('L','L','R','R','R','R','R','R','R','R'));
	$this->Row(array('TOTAL ',$totalMS,$totalMS1,$totalMS,$totalMS2,$totalMS,$totalMS3,$totalMS,$totalMS4));
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