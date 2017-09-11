<?php 
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdf.php';


class PDF extends FPDF
{
var $tipopdf;
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
     	$this->Ln(10);
     	
	$date1=date('d/m/Y');
	$date2=date('h:i a');
	
	$this->Cell(158,8,"FECHA: ",0,0,"R");
	$this->Cell(30,8,$date1,0,1,"R");
	$this->Cell(158,8,"HORA: ",0,0,"R");
	$this->Cell(30,8,$date2,0,1,"R");

}




var $idi;
var $cod;



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

//variable global para tipo de orden
var $tipo;


//Pie de página
function Footer()
{
   
    	//Posición: a  cm del final
	$this->SetY(-15);
	$conexion=conexion();	
    	$this->SetFont('Arial','I',8);
    	$this->Cell(0,5,'Elaborado Por: '.$_SESSION['nombre'],0,1,'L');
	$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');

}
function tabla_proveedor($pagina,$num_paginas,$var_sql_con,$var_sql_tipo,$pdf)
{
	  $cantidad_registros=19;
	
		
		$this->Ln(2);
		if($pdf->tipo==false){
			$this->SetFont("Arial","B",10);
			$this->Cell(188,7,'LISTADO DE PROVEEDORES',0,1,'C');
			$this->SetFont("Arial","I",9);
			$this->Cell(20,7,utf8_decode('Código'),'LTB',0,'C');
			$this->Cell(80,7,utf8_decode('Compañia'),'LTB',0,'C');
			$this->Cell(44,7,"Siglas",'LTB',0,'C');
			$this->Cell(20,7,"Tipo",'LTB',0,'C');
			$this->Cell(24,7,'R.I.F.','LTBR',0,'C');
			$this->Ln();
			$var_sql_conn="select * from proveedores where compania <> '' Order by ".$var_sql_con;
		}else{
			$tipo= $var_sql_tipo; 
			if($tipo=='P'){$tipopdf= "Proveedor";}elseif($tipo=='C'){$tipopdf="Contratista";}elseif($tipo=='I'){$tipopdf= 'Cooperativa';}elseif($tipo=='F'){$tipopdf='Fundación';}elseif($tipo=='O'){$tipopdf='Otros';}
			$this->SetFont("Arial","B",10);
			$this->Cell(188,7,'LISTADO DE PROVEEDORES POR TIPO ( '.$tipopdf.' )',0,1,'C');
			$this->SetFont("Arial","I",9);
			$this->Cell(20,7,utf8_decode('Código'),'LTB',0,'C');
			$this->Cell(90,7,utf8_decode('Compañia'),'LTB',0,'C');
			$this->Cell(54,7,"Siglas",'LTB',0,'C');
			$this->Cell(24,7,'R.I.F.','LTBR',0,'C');
			$this->Ln();
			$var_sql_conn="select * from proveedores where compania <> '' AND tipo_compania='".$var_sql_tipo."' Order by compania";
			
			
		}
		
		
	
		
	
		$conexion=conexion();
		 
	  	$rs = query($var_sql_conn,$conexion);
		$totalwhile=num_rows($rs);	
		$contar=1;
		$cantidad_registros=19;
		$cont=1;
		while ($totalwhile>=$contar) 
		{ 
			
			$conexion=conexion();
			$row_rs = fetch_array($rs);
			

			

			$var_codigo=$row_rs['cod_proveedor'];
			$var_compania=$row_rs['compania'];
			$var_siglas=$row_rs['siglas'];
			$var_rif=$row_rs['rif'];
			
			if($pdf->tipo==false){
				$tipo= $row_rs['tipo_compania']; 
				if($tipo=='P'){$tipopdf= "Proveedor";}elseif($tipo=='C'){$tipopdf="Contratista";}elseif($tipo=='I'){$tipopdf= 'Cooperativa';}elseif($tipo=='F'){$tipopdf='Fundación';}elseif($tipo=='O'){$tipopdf='Otros';}
				$this->SetFont("Arial","I",8);
				$this->SetWidths(array(20,80,44,20,24,));
				$this->SetAligns(array('C','L','L','C','L'));
				$this->Setceldas(array(0,0,0,0,0));
				$this->Setancho(array(7,7,7,7,7));
				$this->Row(array($var_codigo,trim(utf8_decode($var_compania)),trim(utf8_decode($var_siglas)),$tipopdf,$var_rif));
			}
			else{
				$this->SetFont("Arial","I",8);
				$this->SetWidths(array(20,90,54,24,));
				$this->SetAligns(array('C','L','L','L'));
				$this->Setceldas(array(0,0,0,0));
				$this->Setancho(array(7,7,7,7));
				$this->Row(array($var_codigo,trim(utf8_decode($var_compania)),trim(utf8_decode($var_siglas)),$var_rif));
			}
			if($cont==$cantidad_registros)
			{
				if ($contar!=$totalwhile){
					$this->Ln(100);
					if($pdf->tipo==false){
						$this->SetFont("Arial","B",10);
						$this->Cell(188,7,'LISTADO DE PROVEEDORES',0,1,'C');
						$this->SetFont("Arial","I",9);
						$this->Cell(20,7,utf8_decode('Código'),'LTB',0,'C');
						$this->Cell(80,7,utf8_decode('Compañia'),'LTB',0,'C');
						$this->Cell(44,7,"Siglas",'LTB',0,'C');
						$this->Cell(20,7,"Tipo",'LTB',0,'C');
						$this->Cell(24,7,'R.I.F.','LTBR',0,'C');
						$this->Ln();
						$var_sql_conn="select * from proveedores where compania <> '' Order by ".$var_sql_con;
					}else{
						$this->SetFont("Arial","B",10);
						$this->Cell(188,7,'LISTADO DE PROVEEDORES POR TIPO ( '.$tipopdf.' )',0,1,'C');
						$this->SetFont("Arial","I",9);
						$this->Cell(20,7,utf8_decode('Código'),'LTB',0,'C');
						$this->Cell(90,7,utf8_decode('Compañia'),'LTB',0,'C');
						$this->Cell(54,7,"Siglas",'LTB',0,'C');
						$this->Cell(24,7,'R.I.F.','LTBR',0,'C');
						$this->Ln();
						$var_sql_conn="select * from proveedores where compania <> '' AND tipo_compania='".$var_sql_tipo."' Order by compania";
						
					}
					
					
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
	
}
// contenido de la tabla

}


//Creación del objeto de la clase heredada
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$cantidad_registros=19;
$var_sql_con=$_GET['buscar'];
$var_sql_tipo=$_GET['tipo'];
$conexion=conexion();
if(isset($var_sql_tipo)){
	$pdf->tipo=true;
	$var_sql_conn="select * from proveedores where compania <> '' AND tipo_compania='".$var_sql_tipo."' Order by compania"; 
	$rs = query($var_sql_conn,$conexion);
	$num_paginas=obtener_num_paginas($var_sql_conn,$cantidad_registros);
	$pagina=obtener_pagina_actual($pagina,$num_paginas);
}
else{
	$pdf->tipo=false;
	$var_sql_conn="select * from proveedores where compania <> '' Order by ".$var_sql_con;
	$rs = query($var_sql_conn,$conexion);
	$num_paginas=obtener_num_paginas($var_sql_conn,$cantidad_registros);
	$pagina=obtener_pagina_actual($pagina,$num_paginas);
}


$pdf->tabla_proveedor($pagina,$num_paginas,$var_sql_con,$var_sql_tipo,$pdf);
$pdf->Output();
?>