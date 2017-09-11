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

$conexion=conexion();



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
	if($row_rsu['rif']=='G200081643'){
		
		$this->Image($var_imagen_izq,8,12,80,15);
		$this->Image($var_imagen_der,230,12,20,13);
		$this->Cell(65);
		$this->Cell(150,20,'I.V.A',0,0,'C');
		$this->Ln();
	}else{

		$this->Image($var_imagen_izq,8,10,25,15);
		$this->Ln();
		$this->Cell(35);
		
		$this->Cell(270,7,utf8_decode($var_encabezado1),0,0,"C");
		$this->Image($var_imagen_der,310,10,25,15);
		$this->Ln(5);
		$this->Cell(35);
		$this->Cell(270,7,utf8_decode($var_encabezado2),0,0,"C");
		$this->Ln(5);
		$this->Cell(35);
		$this->Cell(270,7,utf8_decode($var_encabezado3),0,0,"C");
		$this->Ln(5);
		$this->Cell(35);
		$this->Cell(270,7,utf8_decode($var_encabezado4),0,0,"C");
		$this->Ln(5);
	}
	$this->Cell(290,5,'BALANCE DEL PRESUPUESTO AL '.date('d/m/Y'),0,0,'C');
	$this->Cell(30,5,'Fecha'.date('d/m/Y'),0,1,'R');
}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
   
     $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');

    //Número de página
   $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
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





function imprimir_tabla(){
	$cantidad_registros=25;
	$conexion=conexion();
	$consulta = "SELECT * FROM cwprecue as P inner join cwprepar as C on P.CodCue=C.Codigo and P.Tipocta=4 order by Sector,Programa,Actividad,Codigo ";
	$rs = query($consulta, $conexion);
	$num_paginas = obtener_num_paginas($consulta,$cantidad_registros);
	$total= num_rows($rs);
	$this->Ln(10);
	//Cabecera
	$this->SetWidths(array(33,33,33,33,33,33,33,33,33,33));
	$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C'));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5,5));
	$this->Setceldas(array(1,1,1,1,1,1,1,1,1,1));
	$this->Row(array(utf8_decode('Codigo'),'Inicial',utf8_decode('Aumento'),utf8_decode('Disminución'),utf8_decode('Actual'),utf8_decode('Comprometido'),'Causado',utf8_decode('Pagado'),utf8_decode('Disponibilidad'),utf8_decode('Deuda')));
	//Datos
	$cont=1;
	$contar=1;
	$pagina=1;
	$reg=0;
	$totalRet=0;
	$s='';$p='';$a='';
	while ($total>=$contar)
	{
		$conexion=conexion();
		$fila = fetch_array($rs);
		$sector=$fila['Sector'];$programa=$fila['Programa'];$actividad=$fila['Actividad'];
		if($s!=$sector ||$p!=$programa || $a!=$actividad){
			if($s!=''){
				$cont++;
				$this->Setceldas(array(0,0,0,0,0,0,0,0,0,0));
				 $this->SetFont("Arial","B",12);
				

				$final = "SELECT SUM(Inicial) as Inicial,SUM(Monto) as Monto,SUM(aumento) as aumento,SUM(disminucion) as disminucion,SUM(Acucom) as comp, SUM(Acucau) as causado, SUM(Acupag) as pagado, SUM(Dispo) as dispo, SUM(Deuda) as deuda FROM cwprepar as P inner join cwprecue as C on C.CodCue=P.Codigo and C.Tipocta=4 WHERE Sector='$s' and Programa='$p' and Actividad='$a'  ";
				
				$query_final=query($final,$conexion);
				$fet_final=fetch_array($query_final);
				$this->Row(array('TOTALES-->',number_format($fet_final['Inicial'],2,',','.'),number_format($fet_final['aumento'],2,',','.'),number_format($fet_final['disminucion'],2,',','.'),number_format($fet_final['Monto'],2,',','.'),number_format($fet_final['comp'],2,',','.'),number_format($fet_final['causado'],2,',','.'),number_format($fet_final['pagado'],2,',','.'),number_format($fet_final['dispo'],2,',','.'),number_format($fet_final['deuda'],2,',','.')));
				 $this->SetFont("Arial","I",10);
				//$this->MultiCell(188,5,$final,0,'C');
			}
			$s=$sector;
			$p=$programa;
			$a=$actividad;
			$cont++;
			$this->Cell(188,5,'Programatica '.$s.' - '.$p.' - '.$a,0,1,'L');
		}
		
		$reg++;
		$conexion=conexion();
	 // llamado para hacer multilinea sin que haga salto de linea
        	//$consulta2 = "SELECT SUM(Inicial) as Inicial,SUM(Monto) as Monto,SUM(aumento ) as aumento,SUM(disminucion) as disminucion,SUM(Acucom) as comp, SUM(Acucau) as causado, SUM(Acupag) as pagado, SUM(Dispo) as dispo, SUM(Deuda) as deuda FROM cwprepar WHERE Codigo = '".$fila['Codigo']."' ";
		//$resultado2 = query($consulta2,$conexion);
		//$fetch = fetch_array($resultado2);
		$this->SetAligns(array('L','R','R','R','R','R','R','R','R','R'));
		$this->Setancho(array(5,5,5,5,5,5,5,5,5,5,5,5,5,5));
		$this->Setceldas(array(0,0,0,0,0,0,0,0,0,0));
        	if($fila['Monto']!=0 )
		{
			$codigo = $fila['Codigo'];
			$conexion=conexion();
			$montoIni = $fila['Inicial'];
			$montoInicial = number_format($montoIni,2,',','.');
			$montoActualizado = number_format($fila['Monto'],2,',','.');
			$montoComprometido = number_format($fila['AcuCom'],2,',','.');
			$montoCausado = number_format($fila['AcuCau'],2,',','.');
			$montoPagado = number_format($fila['AcuPag'],2,',','.');
			$montoDisponible = number_format($fila['Dispo'],2,',','.');
			$aumentos = number_format($fila['aumento'],2,',','.');
			$disminuciones = number_format($fila['disminucion'],2,',','.');
			$this->Row(array($codigo,$montoInicial,$aumentos,$disminuciones,$montoActualizado,$montoComprometido,$montoCausado,$montoPagado,$montoDisponible,$fila['Deuda']));
			$cont++;
		}
	// fin
	//echo $cont;
		if($cont>=$cantidad_registros)
		{	
			if ($pagina!=$num_paginas){
				$this->Ln(100);
				$pagina++;
				
				//Cabecera
				$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C'));
				$this->Setancho(array(5,5,5,5,5,5,5,5,5,5));
				$this->Setceldas(array(1,1,1,1,1,1,1,1,1,1));
				$this->Row(array(utf8_decode('Codigo'),'Inicial',utf8_decode('Aumento'),utf8_decode('Disminución'),utf8_decode('Actual'),utf8_decode('Comprometido'),'Causado',utf8_decode('Pagado'),utf8_decode('Disponibilidad'),utf8_decode('Deuda')));$cantidad_registros=25;
				$cont=1;
			}
		}
        //echo $contar;
	 $contar++;
	
	}
	

	


}
}


//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);



$conexion = conexion();

//

$pdf->imprimir_tabla();

$pdf->Output();
?>