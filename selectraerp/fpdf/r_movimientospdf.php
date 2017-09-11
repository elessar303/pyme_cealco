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
		$this->Cell(100,20,'MOVIMIENTOS BANCARIOS',0,0,'C');
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

function imprimir_tabla($banco,$fDesde,$fHasta,$pdf)
{
	$conexion=conexion();
	$consulta1= "SELECT fecha_apertura, monto_apertura,cuenta,descripcion FROM bancos WHERE codigo = $banco";
	$resultado1= query($consulta1,$conexion);
	$aperturac=fetch_array($resultado1);
	$saldo_ant1=$saldo_ant=$aperturac['monto_apertura'];
	$cuenta=$aperturac['cuenta'];
	$descripcion=$aperturac['descripcion'];
	

	$this->SetFont('Arial','B',10);
	$string="Relación de Movimientos Bancarios de la Cuenta Nro ".$cuenta. "del :".$fDesde." al ".$fHasta;
	
	$this->Cell(188,8,utf8_decode($string),0,0,"C");
	//$this->cell(38,8,'Fecha: '.fecha($fecha),0,0,'C');
	$this->Ln();
	$this->SetFont('Arial','B',10);
	$string="Banco ".$descripcion;
	$this->Cell(100,7,$string,0,"C");
	$this->Cell(48,7,'',0,"C");
	$date1=date('d/m/Y');
	$this->Cell(40,7,"Fecha Consulta:".$date1,0,"C");
	$this->Ln();
}
function imprimir_datos($fDesde,$fHasta,$banco,$tipomov,$pdf)
{
	$cantidad_registros=35;
	if ($cont+3>$cantidad_registros){
		$this->Ln(30);
	}
	
		$this->SetFont("Arial","I",9);
		$this->Cell(18,7,'Fecha','LTB',0,'C');
		$this->Cell(18,7,'Tipo Mov','LTB',0,'C');
		$this->Cell(18,7,'Nro Mov','LTB',0,'C');
		$this->Cell(72,7,'Beneficiario / Concepto','LTB',0,'C');
		$this->Cell(21,7,'Debe.','LTB',0,'C');
		$this->Cell(21,7,'Haber.','LTB',0,'C');
		$this->Cell(23,7,'Saldo.','LTBR',0,'C');
		$this->Ln();
	
		$conexion=conexion();
		if($tipomov==0)
		{
			$consulta= "SELECT fecha, tipo, numero, monto,codigo, concepto FROM movimientos_bancarios WHERE codigo =".$banco." AND fecha BETWEEN '".$fDesde."' AND '".$fHasta."' order by fecha";
		}else{
			$consulta= "SELECT fecha, tipo, numero, monto,codigo, concepto FROM movimientos_bancarios WHERE codigo =".$banco." AND tipo='".$tipomov."' AND fecha BETWEEN '".$fDesde."' AND '".$fHasta."' order by fecha";
		}
		$resultado= query($consulta, $conexion);
		$totalwhile=num_rows($resultado);
		if ($totalwhile==0){
			$this->SetY(-75);
			$this->Cell(188,7,'No hay Movimientos',0,0,'C');
		}
			
		$contar=1;
		$cantidad_registros=35;

		$cheques=0;
		$monto_cheques=0;
		$depositos=0;
		$monto_depositos=0;
		$notas_deb=0;
		$monto_notas_deb=0;
		$notas_cre=0;
		$monto_notas_cre=0;
		$i=1;
		$saldo_act=0;
		$debe=0;
		$haber=0;

		while ($totalwhile>=$contar) 
		{ 
			$conexion=conexion();
			$contenido= fetch_array($resultado);
			$contador++;
			$conCq = "SELECT * FROM cheques WHERE cheque='".$contenido['numero']."' and banco=".$contenido['codigo']." and fecha='".$contenido['fecha']."'";
			$resCq = query($conCq, $conexion);
			$filaCq = fetch_array($resCq);
			$Beneficiaro = $filaCq['beneficiario'];
			if(($contenido['tipo']=="Cheque")||($contenido['tipo']=="Debito"))
			{
				$saldo_act=$saldo_ant+0-$contenido['monto'];
				$saldo_ant=$saldo_act;	
				$haber=$haber+$contenido['monto'];
				$monto=number_format($contenido['monto'], 2, ',', '.');
				$saldo=number_format($saldo_act, 2, ',', '.');
				$this->SetFont("Arial","I",9);
			// llamado para hacer multilinea sin que haga salto de linea
				$this->SetWidths(array(18,18,18,72,21,21,23));
				$this->SetAligns(array('C','C','C','L','R','R','R'));
				$this->Setceldas(array(0,0,0,0,0,0,0));
				$this->Setancho(array(5,5,5,5,5,5,5));
				if($contenido['tipo']=="Cheque")
				{
					if($contenido['monto']==0)
					{
					$this->Row(array(fecha($contenido['fecha']),$contenido['tipo'],$contenido['numero'],utf8_decode($Beneficiaro." ".$contenido['concepto']),$monto,'0.00',$saldo));
					}else
					{
					$this->Row(array(fecha($contenido['fecha']),$contenido['tipo'],$contenido['numero'],utf8_decode($Beneficiaro),$monto,'0.00',$saldo));
					}
				
				}else
				{
				$this->Row(array(fecha($contenido['fecha']),$contenido['tipo'],$contenido['numero'],utf8_decode($contenido['concepto']),$monto,'0.00',$saldo));
				}
			}
			else
			{
				$saldo_act=$saldo_ant+$contenido['monto']-0;
				$saldo_ant=$saldo_act;
				$debe=$debe+$contenido['monto'];
				$monto=number_format($contenido['monto'], 2, ',', '.');
				$saldo=number_format($saldo_act, 2, ',', '.');
				$this->SetFont("Arial","I",9);
			// llamado para hacer multilinea sin que haga salto de linea
				$this->SetWidths(array(18,18,18,72,21,21,23));
				$this->SetAligns(array('C','C','C','L','R','R','R'));
				$this->Setceldas(array(0,0,0,0,0,0,0));
				$this->Setancho(array(5,5,5,5,5,5,5));
				$this->Row(array(fecha($contenido['fecha']),$contenido['tipo'],$contenido['numero'],utf8_decode($contenido['concepto']),'0.00',$monto,$saldo));
			}
			$contador++;
			if($contenido['tipo']=="Cheque")
			{
				$cheques=$cheques+1;
				$monto_cheques=$monto_cheques+$contenido['monto'];	
			}	
			elseif($contenido['tipo']=="Deposito")
			{
				$depositos=$depositos+1;
				$monto_depositos=$monto_depositos+$contenido['monto'];
			}
			elseif($contenido['tipo']=="Debito")
			{
				$notas_deb=$notas_deb+1;
				$monto_notas_deb=$monto_notas_deb+$contenido['monto'];
			}
			elseif($contenido['tipo']=="Credito")
			{
				$notas_cre=$notas_cre+1;
				$monto_notas_cre=$monto_notas_cre+$contenido['monto'];
			}
	
			if($cont>=$cantidad_registros)
			{
				
				$this->Ln(100);
				$pdf->imprimir_tabla($banco,$fDesde,$fHasta,$pdf);
				$this->SetFont("Arial","I",9);
				//$string=utf8_decode();
				$this->Cell(18,7,'Fecha','LTB',0,'C');
				$this->Cell(18,7,'Tipo Mov','LTB',0,'C');
				$this->Cell(18,7,'Nro Mov','LTB',0,'C');
				$this->Cell(72,7,'Beneficiario / Concepto','LTB',0,'C');
				$this->Cell(21,7,'Debe.','LTB',0,'C');
				$this->Cell(21,7,'Haber.','LTB',0,'C');
				$this->Cell(23,7,'Saldo.','LTBR',0,'C');
				$this->Ln();
				$cont=1;
		
			}
			else
			{
				$cont++;
				//echo $cont;
			}
			//$contar=$contar+$m;
			$contar++;
		}//fin del while
		$this->SetFont("Arial","I",9);
		$this->Cell(188,7,' TOTALES','',0,'C');
		$this->Ln();
		$this->Cell(49,7,'','',0,'C');
		$this->Cell(30,7,utf8_decode('Descripción'),'',0,'C');
		$this->Cell(30,7,'Cantidad','',0,'C');
		$this->Cell(30,7,'Monto','',0,'C');
		$this->Cell(49,7,'','',0,'C');
		$this->Ln();
		// llamado para hacer multilinea sin que haga salto de line
		$this->SetFont("Arial","I",9);
		$this->SetWidths(array(49,30,30,30,49));
		$this->SetAligns(array('C','C','C','C','R'));
		$this->Setceldas(array(0,0,0,0,0));
		$this->Setancho(array(5,5,5,5,5));
		$this->Row(array('','Depositos',$depositos,$monto_depositos,''));
		$this->Row(array('','Cheques',$cheques,$monto_cheques,''));
		$this->Row(array('','Nota Cred',$notas_cre,$monto_notas_cre,''));
		$this->Row(array('','Nota Deb',$notas_deb,$monto_notas_deb,''));

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

$fDesde = fecha_sql($_GET['fechaDesde']);
$fHasta = fecha_sql($_GET['fechaHasta']);
$banco = $_GET['banco'];
$tipomov = $_GET['tipomov'];


$consulta= "SELECT fecha, tipo, numero, monto, concepto FROM movimientos_bancarios WHERE codigo =".$banco." AND tipo='".$tipomov."' AND fecha BETWEEN '".$fDesde."' AND '".$fHasta."'";
$resultado= query($consulta, $conexion);
$filas = num_rows($resultado);

$pdf->imprimir_tabla($banco,$fDesde,$fHasta,$pdf);
$pdf->imprimir_datos($fDesde,$fHasta,$banco,$tipomov,$pdf);
$pdf->Output();
?>