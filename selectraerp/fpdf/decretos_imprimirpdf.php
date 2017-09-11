<?php 
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdf.php';

function cantidad_registro($concep){
	
	if(strlen($concep)<=75)
	{
		return 18;
	}elseif(strlen($concep)>75){
		return 16;
	}
		
}

class PDF extends FPDF
{
var $tipopdf;
var $concep;
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
	$this->SetFont('Arial','B',12);
        if($row_rsu['rif']=='G200081643'){
		
		$this->Image($var_imagen_izq,5,12,80,15);
		$this->Image($var_imagen_der,175,12,20,13);
		$this->Cell(65);
		$this->Cell(100,20,'MODIFICACIONES PRESUPUESTARIAS',0,0,'C');
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




var $idi;
var $cod;


// DATOS DEL PROVEEDOR


function tabla_datosheader($pagina,$num_paginas,$cod_centro,$decreto,$desTipo, $var_rows,$pdf){
	
	$conexion=conexion();	
	
	$fecha = $var_rows['fecha'];
	$tipomov = $var_rows['tipo_movimiento'];
	$descripcion = $var_rows['descripcion_movimiento'];
	$orden = $var_rows['numero_ocs'];
	if($tipomov=='T'){
		$tipomovimiento="Traslado";
	}elseif($tipomov=='D'){
		$tipomovimiento= "Diminuci&oacute;n";
	}elseif($tipomov=='N'){
		$tipomovimiento="Nota de Cr&eacute;dito";
	}elseif($tipomov=='C'){
		$tipomovimiento="Cr&eacute;dito Adicional";
								}
	$this->SetFont('Arial','B',8);
	$this->Cell(188,8,'MODIFICACIONES PRESUPUESTARIAS',1,0,"C");
	$this->Ln();
	$this->SetFont('Arial','B',8);
	
	$this->Cell(20,5,' Dec/Res ',1,0,"C");
	$this->Cell(30,5,' Fecha ',1,0,"C");
	$this->Cell(35,5,' Tipo Movimiento ',1,0,"C");
	$this->Cell(83,5,utf8_decode(' Descripción'),1,0,"C");
	$this->Cell(20,5,' Nro de Orden',1,0,"C");
	$this->Ln();
	$this->SetFont('Arial','I',7);
	// llamado para hacer multilinea sin que haga salto de linea
	$this->SetWidths(array(20,30,35,83,20));
	$this->SetAligns(array('L','C','C','C','C'));
	$this->Setceldas(array(1,1,1,1,1));
	if (strlen($descripcion)>37){
		$var=5;
	}else{
		$var=10;
	}
	
	$this->Setancho(array(10,10,10,$var,10));

	$this->Row(array($decreto,fecha($fecha) ,$tipomovimiento,$descripcion,$orden));
	$this->Ln(5);
		// fin
}
//Hacer que sea multilinea sin que haga un salto de linea
var $widths;
var $aligns;
var $celdas;
var $ancho;
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
//variable global para firmas dinamicas
var $pdff;
//variable global para tipo de orden
var $tipo;


//Pie de página
function Footer()
{
   
	$bool=validar_firma("ORD".$this->tipo);
	if ($bool==false){
    	//Posición: a  cm del final
		$this->SetY(-76);
	}else{
		$this->SetY(-88);
	}
    	
	
	
	
	// fin
	$x=$this->GetX();
        $y=$this->GetY();
        
	$this->SetXY($x,$y-8);
	//firmas dinamicas
		
		$bool=validar_firma("DECRETOS");
		if ($bool==true){
			firma_dinamica("PRES".$this->tipo,$this->pdff,7,10);
		}else{
			decretos($this->pdff);
		}
	//

	// fin
    	$this->SetFont('Arial','I',8);
    	$this->Cell(0,5,'Elaborado Por: '.$this->idi,0,1,'L');
	$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');

}
function datos_partidas($id,$cont,$pagina,$num_paginas,$cod_centro,$decreto,$desTipo, $var_rows,$pdf)
{
	$this->pdff=$pdf;
	$cantidad_registros=16;
	if ($cont+3>$cantidad_registros){
		$this->Ln(120);
		$pdf->tabla_datosheader($pagina,$num_paginas,$cod_centro,$decreto,$desTipo, $var_rows,$pdf);
	}
		$this->SetFont("Arial","B",10);
		$this->Ln(2);
		$this->Cell(188,7,utf8_decode('Detalles del Decreto / Resolución'),1,0,'C');
		$this->Ln();
	
	
		$this->SetFont("Arial","I",9);
		$string=utf8_decode('Programática');
		$this->Cell(24,7,$string,'LTB',0,'C');
		$this->Cell(30,7,'Partida','LTB',0,'C');
		$this->Cell(16,7,'Ordinal','LTB',0,'C');
		$string=utf8_decode('Descripción de la Cuenta');
		$this->Cell(62,7,$string,'LTB',0,'C');
		$this->Cell(28,7,'Cedente','LTBR',0,'C');
		$this->Cell(28,7,'Receptora','LTBR',0,'C');
		$this->Ln();
	
		$conexion=conexion();
		$rs = query("SELECT * FROM modificacion_det where decreto = $decreto",$conexion);
		$totalwhile=num_rows($rs);	
		$contar=1;
		$cantidad_registros=16;
		$cont=1;
		while ($totalwhile>=$contar) 
		{ 
			
			$conexion=conexion();
			$row_rs = fetch_array($rs);
			$cont2=$cont2+1;
			$sec = $row_rs['sec'];
			$pro = $row_rs['pro'];
			$act = $row_rs['obr'];
			if($sec==99)
			{
				$programatica = $pro.".".$act;
			}else{	
				$programatica = $sec.".".$pro.".".$act;
			}
			$partida = $row_rs['codigo'];
			$ordinal = $row_rs['ordinal'];
			$monto = $row_rs['monto'];
			$monto_format=number_format($monto,2,",",".");
			$descrip = $row_rs['descripcion'];
			$tiporef = $row_rs['tipo'];
			$contador++;
			
			$this->SetFont("Arial","I",9);
			// llamado para hacer multilinea sin que haga salto de linea
			$this->SetWidths(array(24,30,16,62,28,28));
			$this->SetAligns(array('C','C','C','L','R','R'));
			$this->Setceldas(array(0,0,0,0,0,0));
			$this->Setancho(array(7,7,7,5,7,7));
			if($tiporef=='d'){
				$this->Row(array($programatica,$partida,$ordinal,$descrip,$monto_format,''));
				$debito+=$monto;
			}else{
				$this->Row(array($programatica,$partida,$ordinal,$descrip,'',$monto_format));
				$credito+=$monto;
			}
	
			if($cont==$cantidad_registros)
			{
				if ($contar!=$totalwhile){
					$this->Ln(100);
					$pdf->tabla_datosheader($pagina,$num_paginas,$cod_centro,$decreto,$desTipo, $var_rows,$pdf);
					$this->SetFont("Arial","B",10);
					$this->Ln(2);
					$this->Cell(188,7,utf8_decode('Detalles del Decreto / Resolución'),1,0,'C');
					$this->Ln();
				
				
					$this->SetFont("Arial","I",9);
					$string=utf8_decode('Programática');
					$this->Cell(24,7,$string,'LTB',0,'C');
					$this->Cell(30,7,'Partida','LTB',0,'C');
					$this->Cell(16,7,'Ordinal','LTB',0,'C');
					$string=utf8_decode('Descripción de la Cuenta');
					$this->Cell(62,7,$string,'LTB',0,'C');
					$this->Cell(28,7,'Cedente','LTBR',0,'C');
					$this->Cell(28,7,'Receptora','LTBR',0,'C');
					$this->Ln();
					$cont=1;
				}
		
			}
			else
			{
				$cont++;
				//echo $cont;
			}
			$contar++;
		}//fin del while
		
		$this->Ln(2);
		$this->SetFont("Arial","B",9);
		$this->Cell(132,7,'Totales  Bs.  ',0,0,"R");
		$totaldebito=number_format($debito,2,",",".");
		$totalcredito=number_format($credito,2,",",".");
		$this->Cell(28,7,$totaldebito,0,0,"R");
		$this->Cell(28,7,$totalcredito,0,0,"R");
		//$cont+=7;
  
	
}
// contenido de la tabla


}


//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$cantidad_registros=10;
$conexion=conexion();
	$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro'];
	$desTipo = (empty($_REQUEST['desTipo'])) ? '' : $_REQUEST['desTipo'];
	$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
	$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];
	
	
	$decreto = @$_GET['decreto'];
	$consulta = "SELECT * FROM modificacion WHERE decreto='".$decreto."'";
	$resultado = query($consulta, $conexion);
	$var_rows = fetch_array($resultado);
	//$consulta="select * from ordenes_tipos where cod_orden_tipo=$desTipo";
	//$rss=query($consulta,$conexion);
	//$rrs=fetch_array($rss);
	
	$num_paginas=obtener_num_paginas($consulta,$cantidad_registros);
	$pagina=obtener_pagina_actual($pagina,$num_paginas);
$pdf->cod=$decreto;

$pdf->tabla_datosheader($pagina,$num_paginas,$cod_centro,$decreto,$desTipo, $var_rows,$pdf);

$pdf->datos_partidas($id,$cont,$pagina,$num_paginas,$cod_centro,$decreto,$desTipo, $var_rows,$pdf);

$pdf->Output();
?>