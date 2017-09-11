<?php
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');

require_once '../lib/config.php';
require_once '../lib/pdfcommon.php';
require_once '../lib/common.php';
require_once '../lib/pdf.php';
//include ("../selectra/header.php");

$cantidad_registros=13;

$conexion=conexion();
$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro'];
$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];

$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
$rs = query($consulta_req,$conexion);
$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);

function cantidad_registro($concep){
	
	if(strlen($concep)<=75)
	{
		return 24;
	}elseif(strlen($concep)>75){
		return 21;
	}
		
}


class PDF extends FPDF
{
var $usuario;
var $tipo;
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
		$this->Cell(100,20,'REQUISICIONES',0,0,'C');
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
//variable global para firmas dinamicas
var $pdff;
//Pie de página
function Footer()
{
    $this->SetY(-48);
    $bool=validar_firma("REQUISICIONES");
	
    if ($bool==true){
	firma_dinamica("REQUISICIONES",$this->pdff,8,10);
    }else{
	requisiciones($this->pdff);
    }
    $this->Cell(188,5,'Elaborado Por: '.$this->usuario,0,0,'L');
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

function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows,$pdf)
{
		$conexion=conexion();
		$var_sql="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,
		r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro,r.tipo FROM requisiciones r,centros c WHERE r.cod_requisicion=".$id." and r.cod_centro=c.cod_centro";
		$rs = query($var_sql,$conexion);
		$row_rs = fetch_array($rs);
		$var_fecha=$row_rs['fecha'];
		$var_nom_centro=utf8_decode($row_rs['des_centro']);
		$var_des=utf8_decode($row_rs['descripcion']);
		$var_unidad=$row_rs['unidad'];
		$var_centro=$row_rs['cod_centro'];
		$var_concepto_req=utf8_decode($row_rs['concepto']);
		$var_tipo=$row_rs['tipo'];
		$var_situacion=$row_rs['situacion'];
		
		$this->usuario=$row_rs['estacion'];
		//$rs->close();
		
		$var_sql="SELECT descripcion FROM unidades WHERE cod_unidad=$var_unidad";
		$rsu = query($var_sql,$conexion);
		$row_rsu = fetch_array($rsu);
		$var_nom_und=$row_rsu['descripcion'];
		
		$var_sql="SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = $var_tipo";
		$rsu = query($var_sql,$conexion);
		$row_rsu = fetch_array($rsu);
		$var_nom_tipo=$row_rsu['descripcion'];
		//$rsu->close();
	if($var_situacion=="Anulado"){
		$this->Image("../imagenes/anulado.gif",10,60,180);
		$this->SetY(30);
	}
	$fech=fecha($var_fecha);
	$this->SetFont('Arial','B',12);

	$this->concep=$var_concepto_req;

	$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
	
	$cantidad_registros=cantidad_registro($this->concep);
	$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);

	$this->Ln(5);
     	$this->Cell(10);
        $string=utf8_decode('REQUISICIÓN ');
	$string1=utf8_decode(' Número: ');
 	$this->Cell(150,10,$string.$var_nom_tipo.$string1.$id,0,0,'C');
	$pdf->tipo=$var_nom_tipo;
	$string1=utf8_decode('Pág.: ');
   	$this->Cell(50,10,$string1.$pagina.'/'.$num_paginas,0,0,'L');
    	
	$this->SetFont('Arial','I',10);
	$this->Ln(15);
	$string=utf8_decode('Fecha de Emisión');
	$this->Cell(50,10,$string,0,0,'C');
	$this->Cell(70,10,'Unidad Solicitante',0,0,'C');
	$this->Cell(70,10,'Centro de Costo',0,0,'C');
        $this->Ln(8);
        // llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(50,70,70));
        $this->Setceldas(array(0,0,0));
	$this->Setancho(array(5,5,5));

        $this->Row(array($fech,$var_unidad.' - '.utf8_decode($var_nom_und),$var_centro.' - '.utf8_decode($var_nom_centro)));
	// fin
       $this->Ln(2);
	$this->SetFont('Arial','I',8);
	$string=utf8_decode('Concepto de la Requisición: ');
	$this->MultiCell(189,5,$string.utf8_decode($var_concepto_req),1,'L');
	$this->Ln(2);
	
	
	


}



function imprimir_tabla($id,$pdf,$pagina,$num_paginas,$cod_centro,$var_rows){
	$this->pdff=$pdf;
	$cantidad_registros=cantidad_registro($this->concep);
	$conexion=conexion();
	$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
	$rs = query($consulta_req,$conexion);
	$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
	$total= num_rows($rs);
	//Cabecera
	$this->Cell(40,7,'Cantidad','LTB',0,'C');
	$this->Cell(40,7,'Unidad','TB',0,'C');
	$string=utf8_decode('Descripción');
	$this->Cell(109,7,$string,'TRB',0,'C');
	$this->Ln();
	//Datos
	$cont=1;
	$contar=1;
	$pagina=1;
	while ($total>=$contar)
	{
	$row_rs = fetch_array($rs);
	 // llamado para hacer multilinea sin que haga salto de linea
        $this->Setceldas(array(0,0,0));
	$this->Setancho(array(5,5,5));

        $this->SetWidths(array(40,40,109));
	$this->SetAligns(array('C','C','L'));
        $this->Row(array($row_rs['cantidad'],utf8_decode($row_rs['medida']),utf8_decode($row_rs['descripcion'])));
	
	//maximizar lineas
	if (strlen($row_rs['descripcion'])>55){
		$cantidad_registros-=1;
		if($cont>$cantidad_registros){
			$cantidad_registros=$cont;
		}
		
	}
	
	if($cont==$cantidad_registros)
	{	
		if ($pagina!=$num_paginas){
			$this->Ln(300);
			$pagina++;
			$cantidad_registros=cantidad_registro($this->concep);
			$pdf->imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows,$pdf);
			//Cabecera
			$this->Cell(40,7,'Cantidad','LTB');
			$this->Cell(40,7,'Unidad','TB');
			$string=utf8_decode('Descripción');
			$this->Cell(109,7,$string,'TRB');
			$this->Ln();
			$cont=1;
		}
	}else{$cont++;}
        //echo $contar;
	 $contar++;
	
	}
	


}
}


//Creación del objeto de la clase heredada
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
//for($i=1;$i<=40;$i++)
  //  $pdf->Cell(0,10,'Imprimiendo línea número '.$i,0,1);
//
$conexion=conexion();
$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro'];
$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];

$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
$rs = query($consulta_req,$conexion);
$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);

$pagina=obtener_pagina_actual($pagina, $num_paginas);

//

$pdf->imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$pdf);

$pdf->imprimir_tabla($id,$pdf,$pagina,$num_paginas,$cod_centro,$var_rows);

$pdf->Output();
?>