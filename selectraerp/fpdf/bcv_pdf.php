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
var $desde;
var $hasta;


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
	$ano=$row_rs['periodo'];
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
	
	$periodo = $_GET['periodo'];
	if ($periodo == 1)
	{
		$desde = $ano.'-01-01';
		$desde2 = '01/01/'.$ano;
		$hasta = $ano.'-03-31';
		$hasta2 = '31/03/'.$ano;
		$frec = "1er. Trimestre";	
	}
	elseif ($periodo == 2)
	{
		$desde = $ano.'-04-01';
		$desde2 = '01/04/'.$ano;
		$hasta = $ano.'-06-30';
		$hasta2 = '30/06/'.$ano;
		$frec = "2do. Trimestre";
	}
	elseif ($periodo == 3)
	{
		$desde = $ano.'-07-01';
		$desde2 = '01/07/'.$ano;
		$hasta = $ano.'-09-30';
		$hasta2 = '30/09/'.$ano;
		$frec = "3er. Trimestre";
	}
	elseif ($periodo == 4)
	{
		$desde = $ano.'-10-01';
		$desde2 = '01/10/'.$ano;
		$hasta = $ano.'-12-31';	
		$hasta2 = '31/12/'.$ano;
		$frec = "4to. Trimestre";
	}
	$this->desde=$desde;
	$this->hasta=$hasta;

	$this->Cell(300,5,utf8_decode('EJECUCION PRESUPUETARIA'),0,0,'C');
	$this->Cell(30,5,'FECHA: '.$date1,0,1,'R');
	$this->Cell(300,5,utf8_decode('AÑO').$this->ano,0,0,'C');
	$this->Cell(30,5,'HORA: '.$date2,0,1,'R');
	$this->Cell(300,5,utf8_decode('Frecuencia : '.$frec.' Desde: '.$desde2.' Hasta: '.$hasta2),0,1,'C');
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
	
	$consulta="select * from cwprecue where Tipocta=1";
	
        $rs = query($consulta, $conexion);
	
	$total= num_rows($rs);

	//Cabecera
	
	$this->SetFont('Arial','B',10);
	$this->Setceldas(array(1,1,1,1,1,1,1,1,1));
	$this->SetWidths(array(30,80,31,31,31,31,31,30,31));
	$this->Setancho(array(10,10,5,10,5,10,5,5,10));
	$this->SetAligns(array('L','L','R','R','R','R','R','R','R'));
	$this->Row(array('Partida',utf8_decode('Denominación'),'Asignacion Original','Modificacion','Asinacion Ajustada','Compromisos','Saldo para Comprometer','Gastos Causados','Gastos Pagados'));
			
	//Datos

	$cont=1;
	$contar=1;
	$pagina=1;
	$totalfinal=0;
	while ($total>=$contar)
	{
		$fila = fetch_array($rs);
		$conexion=conexion();
		$consulta2 = "SELECT SUM(Inicial) as Inicial,SUM(Monto) as Monto,SUM(aumento ) as aumento,SUM(disminucion) as disminucion,SUM(Acucom) as comp, SUM(Acucau) as causado, SUM(Acupag) as pagado, SUM(Dispo) as dispo FROM cwprepar WHERE Codigo = '".$fila['CodCue']."' ";
		$resultado2 = query($consulta2,$conexion);
		$fetch = fetch_array($resultado2);
		
		$consulta3 = "SELECT SUM(Monto) AS Monto1 FROM cwpreeje WHERE Partida= '".$fila['CodCue']."' AND  Fecha BETWEEN '".$this->desde."' AND '".$this->hasta."' ";
		$resultado3 = query($consulta3,$conexion);
		$fetch3 = fetch_array($resultado3);
		
		$consulta4 = "SELECT SUM(Monto) AS Monto FROM cwpreejc WHERE Partida = '".$fila['CodCue']."' AND  Fecha BETWEEN '".$this->desde."' AND '".$this->hasta."' ";
		$resultado4 = query($consulta4,$conexion);
		$fetch4 = fetch_array($resultado4);
		
		$consulta7 = "SELECT SUM(Monto) AS Monto FROM cwpreeje WHERE Partida = '".$fila['CodCue']."' AND  Fecha BETWEEN '".$this->desde."' AND '".$this->hasta."' AND Cheque <> '0'  ";
		$resultado7 = query($consulta7,$conexion);
		$fetch7 = fetch_array($resultado7);
		
		
		$consulta5 = "SELECT Monto, RecNoOrders  FROM cwpreeje WHERE Partida = '".$fila['CodCue']."' AND  Fecha BETWEEN '".$this->desde."' AND '".$this->hasta."' AND Cheque = 0 ";
		$resultado5 = query($consulta5,$conexion);

		while ($fetch5 = fetch_array($resultado5))
		{
			$consulta6 = "SELECT creache FROM ordenes_pago WHERE numero_odp = '".$fetch5['RecNoOrders']."' ";
			$resultado6 = query($consulta6,$conexion);
			$fetch6 = fetch_array($resultado6);
			
			if ($fetch6['creache'] == 'N')
			{
				$fetch7['Monto'] = $fetch7['Monto'] + $fetch5['Monto'];
			}
		}

		$codigo = $fila['CodCue'];
		$conexion=conexion();
		$montoIni = $fetch['Inicial'];
		$montoInicial = number_format($montoIni,2,',','.');
		$montoCom = $fetch4['Monto'];
		$montoComprometido = number_format($montoCom,2,',','.');
		$causado = $fetch3['Monto1'];
		$montoCausado = number_format($causado,2,',','.');
		$pagado = $fetch7['Monto'];
		$montoPagado = number_format($pagado,2,',','.');
		$montoDispo = $fetch['dispo'];
		$montoDisponible = number_format($montoDispo,2,',','.');

		$descripcion = $fila['Denominacion'];
		$aumento = $fetch['aumento'];
		$aumentos = number_format($aumento,2,',','.');
		$disminucion = $fetch['disminucion'];
		$disminuciones = number_format($disminucion,2,',','.');
		
		
		$totMontoIni+=$montoIni;
		$totMontoInicial = number_format($totMontoIni,2,',','.');
		$totAumen+=$aumento;
		$totAumentos = number_format($totAumen,2,',','.');
		$totDismi+=$disminucion;
		$totDisminuciones = number_format($totDismi,2,',','.');

		$totMontoCompro+=$montoCom;
		$totMontoComprometido = number_format($totMontoCompro,2,',','.');
		$totMontoCau+=$causado;
		$totMontoCausado = number_format($totMontoCau,2,',','.');
		$totMontoPag+=$pagado;
		$totMontoPagado = number_format($totMontoPag,2,',','.');
		$totMontoDispo+=$montoDispo;
		$totMontoDisponible = number_format($totMontoDispo,2,',','.');

		$valor='';
		if ($aumento != 0)
		{
			$valor=$aumentos.'+';
			$a = $a + $aumento;
			$actualizado = $montoIni + $aumento;
		}
		elseif ($disminucion != 0)
		{
			$valor=$disminuciones.'-';
			$b = $b + $disminucion;
			$actualizado = $montoIni - $disminucion;
		}
		elseif (($aumento != 0) && ($disminucion != 0))
		{
			$modif = $aumento - $disminucion;
			$valor=$modif;
			if ($modif >= 0)
			{
				$valor.='+';
				$a = $a + $modif;
				$actualizado = $montoIni + $modif;
			}
			else
			{
				$valor.='-';
				$b = $b + $modif;
				$actualizado = $montoIni - $modif;
			}
		}
		else
		{
			$valor=0;
			$actualizado = $montoIni;
		}

		$totMontoAct += $actualizado;
		$totMontoActualizado = number_format($totMontoAct,2,',','.');
		
		
		$lineas=strlen($descripcion)/46;
			//$this->Cell(100,5,$lineas,0,1);
		if($lineas>1){
			$cantidad_registros-=round($lineas);
			if($cont>=$cantidad_registros){
				$this->Ln(200);
				//$pagina++;
				//$this->Cell(100,5,$cont.' -'.$cantidad_registros,0,1);
				//Cabecera
				$this->SetFont('Arial','B',10);
				$this->Setceldas(array(1,1,1,1,1,1,1,1,1));
				$this->SetWidths(array(30,80,31,31,31,31,31,30,31));
				$this->Setancho(array(5,5,5,5,5,5,5,5,5));
				$this->SetAligns(array('L','L','R','R','R','R','R','R','R'));
				$this->Row(array('Partida',utf8_decode('Denominación'),'Asignacion Original','Modificacion','Asinacion Ajustada','Compromisos','Saldo para Comprometer','Gastos Causados','Gastos Pagados'));
				$this->Ln(2);			
				$cont=1;
				$cantidad_registros=23;
			}
				
		}
		// llamado para hacer multilinea sin que haga salto de linea
		$this->SetFont('Arial','I',10);
		$this->Setceldas(array(0,0,0,0,0,0,0,0,0));
		$this->SetWidths(array(30,80,31,31,31,31,31,30,31));
		$this->Setancho(array(5,5,5,5,5,5,5,5,5));
		$this->SetAligns(array('L','L','R','R','R','R','R','R','R'));
		$this->Row(array($codigo,utf8_encode($descripcion),$montoInicial,$valor,number_format($actualizado,2,',','.'),$montoComprometido,$montoDisponible,$montoCausado,$montoPagado));
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
				$this->SetFont('Arial','B',10);
				$this->Setceldas(array(1,1,1,1,1,1,1,1,1));
				$this->SetWidths(array(30,80,31,31,31,31,31,30,31));
				$this->Setancho(array(5,5,5,5,5,5,5,5,5));
				$this->SetAligns(array('L','L','R','R','R','R','R','R','R'));
				$this->Row(array('Partida',utf8_decode('Denominación'),'Asignacion Original','Modificacion','Asinacion Ajustada','Compromisos','Saldo para Comprometer','Gastos Causados','Gastos Pagados'));
				$this->Ln(2);			
				$cont=1;
				$cantidad_registros=23;
			
		}
		//echo $contar;
		$contar++;
		
	}
	$this->Ln(10);	

	$this->SetFont('Arial','B',10);
	$this->SetWidths(array(110,31,31,31,31,31,30,31));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5));
	$this->SetAligns(array('L','L','R','R','R','R','R','R','R'));
	$this->Row(array('TOTAL ',$totMontoInicial,number_format($a - $b,2,',','.'),$totMontoActualizado,$totMontoComprometido,$totMontoDisponible,$totMontoCausado,$totMontoPagado));
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