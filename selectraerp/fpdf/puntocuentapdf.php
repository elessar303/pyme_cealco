<?php
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');

require_once '../lib/config.php';
require_once '../lib/pdfcommon.php';
require_once '../lib/common.php';
//include ("../selectra/header.php");
include('../lib/numerosALetras.class.php');

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

        $this->SetFont("Times","B",10);
	$this->Cell(60,5,utf8_decode($var_encabezado1),0,1,"L");
	$this->Image($var_imagen_izq,100,8,20);
     	$this->Cell(60,5,utf8_decode($var_encabezado2),0,1,"L");
     	$this->Cell(60,5,utf8_decode($var_encabezado3),0,1,"L");
	$this->Cell(188,5,"","B");
     	
     	$this->Ln(10);

}

//Pie de página
function Footer()
{
	$conexion=conexion();
    	$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
	$consulta_pc="SELECT * FROM punto_cuenta  WHERE cod_pc = $id  ORDER BY cod_pc";
	$rs = query($consulta_pc,$conexion);
	$pc=fetch_array($rs);
    //Posición: a 1,5 cm del final
    $this->SetY(-85);
    $this->Cell(148);
    $this->Cell(40,5,"","B",1);
    $this->Cell(148);
    $this->SetFont("Times","I",10);
    $this->Cell(40,5,"El Gerente Ejecutante",0,1,"C");
    $this->Ln(5);
    $this->SetFont("Times","B",11);
    $this->Cell(188,8,'Unidad Solicitante',1,1,'C');

    $this->Cell(63,10,'Aprobado','LT',0,'C');
    $this->Cell(63,10,'Negado','LT',0,'C');
    $this->Cell(62,10,'Diferido','LTR',1,'C');
    $this->Cell(63,10,'','L',0);
    $this->Cell(63,10,'','L',0);
    $this->Cell(62,10,'','LR',1);
    $this->Cell(63,5,'Firma y Sello','L',0,'C');
    $this->Cell(63,5,'Firma y Sello','L',0,'C');
    $this->Cell(62,5,'Firma y Sello','LR',1,'C');
    $this->SetFont("Times","I",10);
    if ($pc['estatus']=='Aprobado'){
	$this->Cell(63,5,'FECHA: '.fecha($pc['fecha_estatus']),'LB',0,'C');
    	$this->Cell(63,5,'FECHA:','LB',0,'C');
    	$this->Cell(62,5,'FECHA:','LBR',1,'C');
    }elseif($pc['estatus']=='Negado'){
	$this->Cell(63,5,'FECHA: ','LB',0,'C');
    	$this->Cell(63,5,'FECHA: '.fecha($pc['fecha_estatus']),'LB',0,'C');
    	$this->Cell(62,5,'FECHA: ','LBR',1,'C');
   	}elseif($pc['estatus']=='Diferido'){
		$this->Cell(63,5,'FECHA:','LB',0,'C');
    		$this->Cell(63,5,'FECHA:','LB',0,'C');
    		$this->Cell(62,5,'FECHA: '.fecha($pc['fecha_estatus']),'LBR',1,'C');
	}
	
    

    $this->Ln(5);
    // llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(63,63,62));
	$this->SetAligns(array('L','L','L'));
        $this->Setceldas(array(1,1,1));;
	$this->Setancho(array(5,5,5));
        $this->Row(array('VERIFICADO POR:','PREPARADO POR: '.$pc['usuario'],'ANEXOS: ' .$pc['anexos']));
	//fin


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

function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows)
{
	$pc=fetch_array($var_rows);
	$this->Cell(138);
	$this->SetFont('Times','B',10);
	$this->Cell(20,7,"Fecha:","LTB",0,"R");
	$this->SetFont('Times','I',10);
	$this->Cell(30,7,fecha($pc['fecha_creada']),"TBR",1,"C");
	$this->Ln(7);

	$this->SetFont('Times','B',12);
	$this->Cell(138,7,"PUNTO DE CUENTA",0,0,"C");
	$this->SetFont('Times','B',10);
	$this->Cell(20,7,"NRO.:","LTB",0,"R");
	$this->SetFont('Times','I',10);
	$this->Cell(30,7,$pc['cod_pc'],"TBR",1,"C");
	$this->Ln(7);

	$this->SetFont('Times','B',10);
	$this->Cell(20,7,"PARA:","LTB",0,"L");
	$this->SetFont('Times','I',10);
	$this->Cell(168,7,$pc['para_pc'],"TBR",1,"L");

	$cod_unidad=$pc['unidad'];
	$cod_centro=$pc['centro_costo'];
	$conexion=conexion();
	$consulta_u="select descripcion from unidades where cod_unidad=".$cod_unidad;
	$resultado_u=query($consulta_u,$conexion);
	$fila_u=fetch_array($resultado_u);
	$des_unidad=$fila_u['descripcion'];
	$descripcion_u=$des_unidad;
	
	$consulta_c="select descripcion from centros where cod_centro='".$cod_centro."'";
	$resultado_c=query($consulta_c,$conexion);
	$fila_c=fetch_array($resultado_c);
	$des_centro=$fila_c['descripcion'];
	$descripcion_c=$des_centro;

	$this->SetFont('Times','B',10);
	$this->Cell(20,7,"DE:","LTB",0,"L");
	$this->SetFont('Times','I',10);
	$this->Cell(168,7,$cod_unidad.". ".$descripcion_u." - ".$cod_centro.": ".$descripcion_c ,"TBR",1,"L");
	$this->Ln(5);
	
	$this->SetFont('Times','B',10);
	$this->Cell(188,7,"ASUNTO:","LTR",1,"L");
	$this->SetFont('Times','I',10);
	$this->MultiCell(188,5,$pc['asunto'],"LBR","L");
	$this->Ln(10);

	$this->SetFont('Times','B',10);
	$this->Cell(188,7,utf8_decode("JUSTIFICACIÓN:"),0,1,"L");
	$this->SetFont('Times','I',10);
	$this->MultiCell(188,5,$pc['justificacion'],0,"L");
	$this->Ln(5);


	$this->SetFont('Times','B',10);
	$this->Cell(188,7,utf8_decode("PROPOSICIÓN:"),0,1,"L");
	$this->SetFont('Times','I',10);
	$this->MultiCell(188,5,$pc['proposicion'],0,"L");
	$this->Ln(5);
	
	$n = new numerosALetras();
	$montoLetras=$n->convertir($pc['monto_pc']);

	$this->SetFont('Times','B',10);
	$this->Cell(188,7,utf8_decode("PRESUPUESTO ESTIMADO:"),0,1,"L");
	$this->SetFont('Times','B',11);
	$this->MultiCell(188,5,$montoLetras."   ( Bs. ".number_format($pc['monto_pc'],2,',','.')." )",0,"C");

	
}

}


//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
//for($i=1;$i<=40;$i++)
  //  $pdf->Cell(0,10,'Imprimiendo línea número '.$i,0,1);
//
$conexion=conexion();

$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
$consulta_pc="SELECT * FROM punto_cuenta  WHERE cod_pc = $id  ORDER BY cod_pc";
$rs = query($consulta_pc,$conexion);
$num_paginas=obtener_num_paginas($consulta_pc,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);

$rs = query($consulta_pc,$conexion);
$num_paginas=obtener_num_paginas($consulta_pc,$cantidad_registros);

$pagina=obtener_pagina_actual($pagina, $num_paginas);

//

$pdf->imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$rs);

//$pdf->imprimir_tabla($id,$pdf,$pagina,$num_paginas,$cod_centro,$var_rows);

$pdf->Output();
?>