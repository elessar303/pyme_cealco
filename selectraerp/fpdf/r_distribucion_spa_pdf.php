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
	$this->Cell(260,5,utf8_decode('DISTRIBUCION POR SECTOR, PROGRAMA Y ACTIVIDAD (SUB-ESPECIFICAS)'),0,1,'C');
	
       
    
	$conexion=conexion();
        
        $conSector = "SELECT * FROM cwsector WHERE RecNo='".$_GET['sector']."'";
	//echo $conSector;
	$resSector = query($conSector, $conexion);
	$filaSector = fetch_array($resSector);
	$desSector = $filaSector['Denominacion'];
	$codSector = $filaSector['Sec'];
        $this->sector=$codSector;
    
	$conPrograma = "SELECT * FROM cwprogra WHERE RecNo='".$_GET['programa']."'";
	//echo $conPrograma;
	$resPrograma = query($conPrograma, $conexion);
	$filaPrograma = fetch_array($resPrograma);
	$desPrograma = $filaPrograma['Denominacion'];
	$codPrograma = $filaPrograma['Programa'];
	$this->programa=$codPrograma;
	
	$conActividad = "SELECT * FROM cwpreact WHERE RecNo='".$_GET['actividad']."'";
	//echo $conActividad;
	$resActividad = query($conActividad, $conexion);
	$filaActividad = fetch_array($resActividad);
	$desActividad = $filaActividad['Denominacion'];
	$codActividad = $filaActividad['Obr'];
	$this->actividad=$codActividad;


	$this->Ln(5);
	$this->SetFont('Arial','I',10);
        $this->MultiCell(250,5,utf8_decode('Sector: '.$codSector.'-'.$desSector),0,'L');
	$this->MultiCell(250,5,utf8_decode('Programa: '.$codPrograma.'-'.$desPrograma),0,'L');
	$this->MultiCell(250,5,utf8_decode('Actividad: '.$codActividad.'-'.$desActividad),0,'L');
     	

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
	$cantidad_registros=13;
	$conexion=conexion();
	$consulta = "SELECT * FROM cwprepar LEFT JOIN cwprecue ON cwprepar.Codigo=cwprecue.CodCue WHERE cwprecue.Tipocta='".$nivel."' AND cwprepar.Sector='".$pdf->sector."' AND cwprepar.Programa='".$pdf->programa."' AND cwprepar.Actividad='".$pdf->actividad."' ORDER BY cwprepar.Codigo";
	
        $rs = query($consulta, $conexion);
	$num_paginas=obtener_num_paginas($consulta,$cantidad_registros);
	$total= num_rows($rs);

	$this->SetFont('Arial','B',10);
	$this->Ln(10);
	//Cabecera
	$this->Cell(30,7,utf8_decode('Programática'),1,0,'C');
	$this->Cell(25,7,'Partida',1,0,'C');
	$this->Cell(70,7,utf8_decode('Descripción'),1,0,'C');
	$this->Cell(27,7,'Monto Inicial',1,0,'C');
	$this->Cell(27,7,'Modificaciones',1,0,'C');
	$this->Cell(27,7,'Actualizado',1,0,'C');
	$this->Cell(27,7,'Comprometido',1,0,'C');
	$this->Cell(27,7,'Disponible',1,1,'C');
	$this->Ln(2);
	
			
	//Datos

	$cont=1;
	$contar=1;
	$pagina=1;
	$totalfinal=0;
	while ($total>=$contar)
	{
		$fila = fetch_array($rs);
		$codigo = $fila['Codigo'];
		$conexion=conexion();
		$montoIni = $fila['Inicial'];
		$montoInicial = number_format($montoIni,2,',','.');
		$montoAct = $fila['Monto'];
		$montoActualizado = number_format($montoAct,2,',','.');
		$montoCom = $fila['AcuCom'];
		$montoComprometido = number_format($montoCom,2,',','.');
		$montoDispo = $fila['Dispo'];
		$montoDisponible = number_format($montoDispo,2,',','.');
		$conDes = "SELECT * FROM cwprecue WHERE CodCue='".$codigo."'";
		$resDes = query($conDes, $conexion);
		$filaDes = fetch_array($resDes);
		$descripcion = $filaDes['Denominacion'];
		$credito = $fila['aumento'];
		$debito = $fila['disminucion'];
		$modificac = $credito-$debito;
		$modificaciones = number_format($modificac,2,',','.');
		$totMontoIni+=$montoIni;
		$totMontoInicial = number_format($totMontoIni,2,',','.');
		$totModif+=$modificac;
		$totModificaciones = number_format($totModif,2,',','.');
		$totMontoAct+=$montoAct;
		$totMontoActualizado = number_format($totMontoAct,2,',','.');
		$totMontoCompro+=$montoCom;
		$totMontoComprometido = number_format($totMontoCompro,2,',','.');
		$totDispo+=$montoDispo;
		$totMontoDisponible = number_format($totDispo,2,',','.');
	
		// llamado para hacer multilinea sin que haga salto de linea
		$this->SetFont('Arial','I',10);
		$this->SetWidths(array(30,25,70,27,27,27,27,27));
		$this->Setancho(array(5,5,5,5,5,5,5,5));
		$this->SetAligns(array('C','L','L','R','R','R','R','R'));
		$this->Row(array($pdf->sector.$pdf->programa.$pdf->actividad,$codigo,utf8_decode($descripcion),$montoInicial,$modificaciones,$montoActualizado,$montoComprometido,$montoDisponible));
		
	// fin
	//echo $cont;
		if($cont==$cantidad_registros)
		{	
			if ($pagina!=$num_paginas){
				$this->Ln(50);
				$pagina++;
				
				//Cabecera
				$this->Cell(30,7,utf8_decode('Programática'),1,0,'C');
				$this->Cell(25,7,'Partida',1,0,'C');
				$this->Cell(70,7,utf8_decode('Descripción'),1,0,'C');
				$this->Cell(27,7,'Monto Inicial',1,0,'C');
				$this->Cell(27,7,'Modificaciones',1,0,'C');
				$this->Cell(27,7,'Actualizado',1,0,'C');
				$this->Cell(27,7,'Comprometido',1,0,'C');
				$this->Cell(27,7,'Disponible',1,1,'C');
				$this->Ln(2);			
				$cont=1;
			}
		}else{$cont++;}
		//echo $contar;
		$contar++;
		
		}
	$this->Ln(10);	

	$this->SetFont('Arial','B',10);
	$this->SetWidths(array(125,27,27,27,27,27));
	$this->Setancho(array(5,5,5,5,5,5));
	$this->SetAligns(array('C','R','R','R','R','R'));
	$this->Row(array('TOTAL PRESUPUESTO:',$totMontoInicial,$totModificaciones,$totMontoActualizado,$totMontoComprometido,$totMontoDisponible));
	$this->Ln();
}

}


//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$sector = $_GET['sector'];
$programa = $_GET['programa'];
$actividad = $_GET['actividad'];
$nivel = $_GET['nivel'];

$pdf->imprimir_tabla($pdf,$nivel);

$pdf->Output();
?>