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

	
}

function Footer(){

	$this->SetY(-20);
	$this->Cell(130,5,'_________________________',0,0,'C');
	$this->Cell(130,5,'_________________________',0,1,'C');
	$this->Cell(130,5,'Firma y Sello',0,0,'C');
	$this->Cell(130,5,'Firma y Sello',0,1,'C');
	$this->Cell(130,5,utf8_decode('Agente de Retención'),0,0,'C');
	$this->Cell(130,5,'Proveedor',0,1,'C');
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
var $orden;
function imprimir_datos($codigo)
{
	
	$this->SetFont('Arial','B',12);
        $this->Cell(260,5,utf8_decode('COMPROBANTE DE RETENCIÓN DE RESPONSABILIDAD SOCIAL'),0,1,'C');
 	$this->Ln(7);
	$conexion=conexion();
	$consulta="select * from cheques where cheque=$codigo";
	$rc=query($consulta,$conexion);
	$rrc=fetch_array($rc);
	$this->orden=$rrc['orden'];


	if($rrc['consecutivo_RRS']!=0){
		$consecutivo=$rrc['consecutivo_RRS'];
		if(strlen($consecutivo) == 1)
			$consefinal = "00000".$consecutivo;
		elseif(strlen($consecutivo) == 2)
			$consefinal = "0000".$consecutivo;
		elseif(strlen($ivasc) == 3)
			$consefinal = "000".$consecutivo;
		elseif(strlen($ivasc) == 4)
			$consefinal = "00".$consecutivo;
		elseif(strlen($ivasc) == 5)
			$consefinal = "0".$consecutivo;
		$consefinal = date("Y").date("m").$consefinal;
	}else{
		//saco el consecutivo desde parametros
		$Conn=conexion_conf();
		$consulta="select * from parametros";
		$rp=query($consulta,$Conn);
		$rrp=fetch_array($rp);
		$consecutivo=$rrp['consecutivo_RRS']+1;
		if(strlen($consecutivo) == 1)
			$consefinal = "00000".$consecutivo;
		elseif(strlen($consecutivo) == 2)
			$consefinal = "0000".$consecutivo;
		elseif(strlen($ivasc) == 3)
			$consefinal = "000".$consecutivo;
		elseif(strlen($ivasc) == 4)
			$consefinal = "00".$consecutivo;
		elseif(strlen($ivasc) == 5)
			$consefinal = "0".$consecutivo;

		
		$consefinal = date("Y").date("m").$consefinal;
		// modifico el consecutivo desde parametros
		$update="update parametros set consecutivo_RRS=$consecutivo";
		$ru=query($update,$Conn);
		$conexion=conexion();
		$update="update cheques set consecutivo_RRS=$consecutivo where cheque=$codigo";
		$ru=query($update,$conexion);
	}
	

	//sacamos la fecha y el periodo fiscal
	list($anio,$mes,$dia)=explode("-",$rrc['fecha']);
   	$fecha= $dia."/".$mes."/".$anio; 


	$this->SetWidths(array(86,87,87));
	$this->SetAligns(array('C','C','C'));
	$this->Setceldas(array(1,1,1));
	$this->Setancho(array(5,5,5));
	$this->SetFont('Arial','B',10);
        $this->Row(array(utf8_decode('Nº de Comprobante'),utf8_decode('Fecha'),utf8_decode('Periodo Fiscal')));
	$this->SetFont('Arial','I',10);
	$this->Row(array($consefinal,$fecha,$anio));

	$this->Ln(5);

	//datos agente de retencion
	$Conn=conexion_conf();
	$consulta="select * from parametros";
	$rp=query($consulta,$Conn);
	$rrp=fetch_array($rp);
	
	$this->SetWidths(array(130,130));
	$this->SetAligns(array('C','C'));
	$this->Setceldas(array(1,1));
	$this->Setancho(array(5,5));
	$this->SetFont('Arial','B',10);
	$this->Row(array(utf8_decode('Nombre o Razón Social del Agente de Retención'),utf8_decode('Registro de Información Fiscal del Agente de Retención')));
	$this->SetFont('Arial','I',9);
	$this->Row(array($rrp['nomemp'],$rrp['rif']));
	
	$this->Ln(5);
	$this->SetFont('Arial','B',10);
	$this->Cell(260,5,utf8_decode('Dirección Fiscal del Agente de Retención'),1,1,'C');
	$this->SetFont('Arial','I',10);
	$this->MultiCell(260,5,$rrp['direccion'],1,'L');
	
	$this->Ln(5);

	$this->SetWidths(array(130,130));
	$this->SetAligns(array('C','C'));
	$this->Setceldas(array(1,1));
	$this->Setancho(array(5,5));
	$this->SetFont('Arial','B',10);
	$this->Row(array(utf8_decode('Nombre o Razón Social del Sujeto Retenido'),utf8_decode('Registro de Información Fiscal del Sujeto Retenido')));
	$this->SetFont('Arial','I',10);
	$this->Row(array($rrc['beneficiario'],$rrc['cedula']));
        
        return $fecha;


}



function imprimir_tabla($codigo,$fecha,$pdf){
	$cantidad_registros=13;
	$conexion=conexion();
	
	$consulta = "SELECT * FROM facturas WHERE numord='".$pdf->orden."'";
	$rs = query($consulta, $conexion);
	$num_paginas = obtener_num_paginas($consulta,$cantidad_registros);
	$total= num_rows($rs);
	$this->Ln(2);
	//Cabecera
	$this->SetWidths(array(27,27,27,50,32,33,32,32));
	$this->SetAligns(array('C','C','C','C','C','C','C','C'));
	$this->Setancho(array(10,5,5,5,10,10,10,10));
	$this->Setceldas(array(1,1,1,1,1,1,1,1));
	$this->Row(array(utf8_decode('Fecha de Pago'),utf8_decode('Nº de Documento'),utf8_decode('Nº Control de Documento'),utf8_decode('Total Cantidad Pagada o Abonada en Cuenta'),utf8_decode('Total Imponible'),utf8_decode('Base Imponible'),'% Alicuota',utf8_decode('Impuesto Retenido')));
	//Datos
	$cont=1;
	$contar=1;
	$pagina=1;
	$reg=0;
	$totalRet=0;
	while ($total>=$contar)
	{
	
		$fila = fetch_array($rs);
		$reg++;
	 // llamado para hacer multilinea sin que haga salto de linea
		//%aporte
		$aporte=($fila['porcentaje_aporte']/100)*$fila['montobase'];
		$this->SetAligns(array('C','C','C','C','C','C','C','C'));
		$this->Setancho(array(5,5,5,5,5,5,5,5));
		$this->Row(array($fecha,$fila['no_fac'],$fila['control_fac'],number_format($fila['montofactiva'],2,',','.'),number_format($fila['montobase'],2,',','.'),number_format($fila['montobase'],2,',','.'),number_format($fila['porcentaje_aporte'],2,',','.').'%',number_format($aporte,2,',','.')));
		
	
	// fin
	//echo $cont;
	if($cont==$cantidad_registros)
	{	
		if ($pagina!=$num_paginas){
			$this->Ln(60);
			$pagina++;
			$pdf->imprimir_datos($codigo);
			//Cabecera
			$this->SetWidths(array(32,32,33,32,32,33,32,32));
			$this->SetAligns(array('C','C','C','C','C','C','C','C'));
			$this->Setancho(array(5,5,5,5,5,5,5,5));
			$this->Setceldas(array(1,1,1,1,1,1,1,1));
			$this->Row(array(utf8_decode('Fecha de Pago'),utf8_decode('Nº de Documento'),utf8_decode('Nº Control de Documento'),utf8_decode('Total Cantidad Pagada o Abonada en Cuenta'),utf8_decode('Total Imponible'),utf8_decode('Base Imponible'),'% Alicuota',utf8_decode('Impuesto Retenido')));
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
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$codigo=$_GET['codigo'];
$chequera=$_GET['chequera'];
$banco=$_GET['banco'];
$cuenta=$_GET['cuenta'];
$fecha=$pdf->imprimir_datos($codigo);
$pdf->imprimir_tabla($codigo,$fecha,$pdf);
$pdf->Output();
?>