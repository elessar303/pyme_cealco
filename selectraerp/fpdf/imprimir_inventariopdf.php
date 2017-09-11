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

        $this->SetFont("Arial","B",12);
	
if($row_rsu['rif']=='G200081643'){
		
		$this->Image($var_imagen_izq,5,12,80,15);
		$this->Image($var_imagen_der,175,12,20,13);
		$this->Cell(65);
		$this->Cell(100,20,'LISTADO DE MATERIALES',0,0,'C');
		$this->Ln();
	}else{
		
		$this->Image($var_imagen_izq,10,8,23);
		$this->Ln();
		$this->Cell(45);
		$this->Cell(100,8,utf8_decode($var_encabezado1),0,0,"C");
		$this->Image($var_imagen_der,170,10,30);
		$this->Ln(6);
		$this->Cell(35);
		$this->Cell(120,8,utf8_decode($var_encabezado2),0,0,"C");
		$this->Ln(6);
		$this->Cell(10);
		$this->Cell(170,8,utf8_decode($var_encabezado3),0,0,"C");
		$this->Ln(7);
	
	}
	
	
     	

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

function imprimir_datos($nro_odp,$fila_odp, $moneda,$pdf)
{
	$cantidad_registros=27;
	if ($cont+3>$cantidad_registros){
		$this->Ln(60);
	}
		
	
	
		$this->SetFont("Arial","I",9);
		$string=utf8_decode('Código SNC');
		$this->Cell(24,7,$string,'LTB',0,'C');
		$string=utf8_decode('Código');
		$this->Cell(29,7,$string,'LTB',0,'C');
		$string=utf8_decode('Descripción');
		$this->Cell(77,7,$string,'LTB',0,'C');
		$string=utf8_decode('Cántidad Existente');
		$this->Cell(28,7,$string,'LTB',0,'C');
		$this->Cell(30,7,'Unidad de Med.','LTBR',0,'C');
		//$this->Cell(18,7,utf8_decode('Unidad Salida'),'LTBR',0,'C');
		$this->Ln();
	
		$conexion=conexion();
		$rs = query("SELECT * FROM materiales where existencia>0",$conexion);
		$totalwhile=num_rows($rs);
		if ($totalwhile==0){
			$this->SetY(-75);
			$this->Cell(188,7,'No hay materiales',0,0,'C');
		}
			
		$contar=1;
		$cantidad_registros=27;
		while ($totalwhile>=$contar) 
		{ 
			$conexion=conexion();
			$row_rs = fetch_array($rs);
			$cont2=$cont2+1;
			$var_snc=$row_rs[4];
			$var_codigo=$row_rs[0];
			$var_descrip=utf8_decode($row_rs[1]);
			$var_exi=number_format($row_rs[6],0,',','.');
			//$var_min=number_format($row_rs[7],0,',','.');
			//$var_max=number_format($row_rs[8],0,',','.');
			$unidad_medida=$row_rs['unidad'];
			$unidad_salida=$row_rs['unidad_salida'];
			$contador++;
			
			//$monto_3  = number_format($var_monto3,2,',','.');
			$this->SetFont("Arial","I",9);
			// llamado para hacer multilinea sin que haga salto de linea
			$this->SetWidths(array(24,29,77,28,30));
			$this->SetAligns(array('L','L','L','C','C'));
			$this->Setceldas(array(0,0,0,0,0));
			$this->Setancho(array(5,5,5,5,5));
			$this->Row(array($var_snc,$var_codigo,$var_descrip,$var_exi,$unidad_medida));
	
			if($cont==$cantidad_registros)
			{
				
				$this->Ln(100);
				$this->SetFont("Arial","I",9);
				$string=utf8_decode('Código SNC');
				$this->Cell(24,7,$string,'LTB',0,'C');
				$string=utf8_decode('Código');
				$this->Cell(29,7,$string,'LTB',0,'C');
				$string=utf8_decode('Descripción');
				$this->Cell(77,7,$string,'LTB',0,'C');
				$string=utf8_decode('Cántidad Existente');
				$this->Cell(28,7,$string,'LTB',0,'C');
				$this->Cell(30,7,'Unidad de Med.','LTBR',0,'C');
				//$this->Cell(18,7,utf8_decode('Unidad Salida'),'LTBR',0,'C');
				$this->Ln();
				$cont=1;
		
			}
			else
			{
				$cont++;
				//echo $cont;
			}
			$contar++;
		}//fin del while

	

}


//Pie de página
function Footer()
{
   
    	//Posición: a  cm del final
   	$this->SetY(-15);
	// fin
	$this->SetFont('Arial','I',8);
    	//Número de página
   	$this->Cell(188,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    	$this->SetFont('Arial','I',8);
	$this->Ln();
    	$this->Cell(188,5,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');
	

    //Número de página
   // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}




}


//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$conexion=conexion();	

$tabla="materiales";
$consulta="select * from ".$tabla;
$resultado=query($consulta,$conexion);
$codigo_snc = $_GET['codigo_snc'];

//$url="materiales_list";
//$modulo="Materiales";
//$titulos=array("Código","Descripción","Unidad","I.V.A.");
//$indices=array("0","1","2","13");

$Conn=conexion_conf();
	
	$var_sql="select moneda,periodo from parametros";
	$rsu = query($var_sql,$Conn);
	$row_rsu = fetch_array($rsu);
	$moneda=$row_rsu['moneda'];
	$periodo=$row_rsu['periodo'];
	cerrar_conexion($Conn);

	$pdf->imprimir_datos($nro_odp,$fila_odp, $moneda,$pdf);
	$pdf->Output();
?>