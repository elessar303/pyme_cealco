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

}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
   
     $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');

    //Número de página
   // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
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

function imprimir_datos($nombreAgente,$nombreContribuyente,$rifAgente,$rifContribuyente,$codiva_nuevo,$periodo,$direccionAgente)
{
	
	

	$this->SetFont('Arial','B',12);
        $string=utf8_decode('COMPROBANTE DE RETENCIÓN DE IMPUESTO AL VALOR AGREGADO');
 	$this->Cell(260,8,$string,0,1,'C');
	$this->SetFont('Arial','I',10);
	$string1=utf8_decode('(Ley IVA - Art. 11: "Serán responsables del pago del impuesto en calidad de agente  de retención, los compradores o adquirientes de determinados bienes muebles y los y los receptores de ciertos servicios, a quienes la Administración Tributaria designe como tal.)');
    	$this->MultiCell(260,5,$string1,0,'C');
	$this->Ln(7);
	$this->SetWidths(array(85,100,75));
	$this->SetAligns(array('L','C','C'));
	$this->Setceldas(array(1,1,1));
	$this->Setancho(array(5,5,5));
	$this->SetFont('Arial','B',10);
        $this->Row(array(utf8_decode('Nombre o Razón Social del Agente de Retención'),utf8_decode('Registro de Información Fiscal del Agente de Retención'),utf8_decode('Nro. de Comprobante')));
	$this->Setceldas(array(0,0,0));
	$this->SetFont('Arial','I',10);
	$this->Ln(2);
	$this->Row(array($nombreAgente,$rifAgente,$codiva_nuevo));
	$this->Ln(2);
	$this->Setceldas(array(1,1,1));
	$this->SetFont('Arial','B',10);
	$this->Row(array(utf8_decode('Dirección Fiscal del Agente de Retención'),utf8_decode('Período Fiscal'),utf8_decode('Fecha')));
	$this->Setceldas(array(0,0,0));
	$this->SetFont('Arial','I',10);
	$this->Ln(2);
	$this->Row(array($direccionAgente,$periodo,date('d/m/Y')));
	$this->Ln(2);
	$this->Setceldas(array(1,1,1));
	$this->SetFont('Arial','B',10);
	$this->Row(array(utf8_decode('Nombre o Razón Social del Sujeto Retenido'),utf8_decode('Registro de Información Fiscal del Sujeto Retenido (RIF)'),''));
	$this->Setceldas(array(0,0,0));
	$this->SetFont('Arial','I',10);
	$this->Ln(2);
	$this->Row(array($nombreContribuyente,$rifContribuyente,''));
        
        


}



function imprimir_tabla($pdf,$fila1){
	$cantidad_registros=13;
	$conexion=conexion();
	$consulta = "SELECT * FROM facturas WHERE numord='".$fila1['numero_odp']."'";
	$rs = query($consulta, $conexion);
	$num_paginas = obtener_num_paginas($consulta,$cantidad_registros);
	$total= num_rows($rs);
	$this->Ln(10);
	//Cabecera
	$this->SetWidths(array(15,20,15,20,15,15,18,19,25,30,18,16,17,17));
	$this->SetAligns(array('L','L','L','L','L','L','L','L','L','L','L','L','L','L'));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5,5,5,5,5,5));
	$this->Setceldas(array(1,1,1,1,1,1,1,1,1,1,1,1,1,1));
	$this->Row(array(utf8_decode('Oper. Nº'),'Fecha Factura',utf8_decode('Nº Factura'),utf8_decode('Nº Control Factura'),utf8_decode('Nº Nota Deb.'),utf8_decode('Nº Nota Cred.'),'Tipo de Transacc.',utf8_decode('Nº de Fac. Afect.'),utf8_decode('Total Compras IVA'),utf8_decode('Monto Exento del Comprobante'),utf8_decode('Base Imponible'),utf8_decode('% Alicuota'),utf8_decode('Impuesto IVA'),'IVA Retenido'));
	//Datos
	$cont=1;
	$contar=1;
	$pagina=1;
	$reg=0;
	$totalRet=0;
	while ($total>=$contar)
	{
	
		$fila5 = fetch_array($rs);
		$reg++;
	 // llamado para hacer multilinea sin que haga salto de linea
        
	$this->SetAligns(array('C','L','C','C','C','C','C','C','C','C','C','C','C','C'));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5,5,5,5,5,5));
        $this->Row(array($reg,fecha($fila5['fec_fac']),$fila5['no_fac'],$fila5['control_fac'],"-","-","-","-",number_format($fila5['montofactiva'], 2, ',', '.'),number_format($fila5['montoexento'], 2, ',', '.'),number_format($fila5['montobase'], 2, ',', '.'),number_format($fila5['porcentajeiva'], 2, ',', '.'),number_format($fila5['montoiva'], 2, ',', '.'),number_format($fila5['montoretenido'], 2, ',', '.')));
	
	
	// fin
	//echo $cont;
	if($cont==$cantidad_registros)
	{	
		if ($pagina!=$num_paginas){
			$this->Ln(60);
			$pagina++;
			$pdf->imprimir_datos($pagina,$num_paginas,$fechaDesde,$fechaHasta);
			//Cabecera
			$this->Cell(18,7,utf8_decode('Oper. Nº'),'LTB',0,'L');
			$this->Cell(18,7,'Fecha Factura','TB',0,'C');
			$this->Cell(18,7,utf8_decode('Nº Factura'),'TB',0,'C');
			$this->Cell(18,7,utf8_decode('Nº Control Factura'),'TB',0,'C');
			$this->Cell(18,7,utf8_decode('Nº Nota Deb.'),'TB',0,'C');
			$this->Cell(18,7,utf8_decode('Nº Nota Cred.'),'TB',0,'C');
			$this->Cell(18,7,'Tipo de Transacc.','TB',0,'C');
			$this->Cell(18,7,utf8_decode('Nº de Fac. Afect.'),'TB',0,'C');
			$this->Cell(18,7,utf8_decode('Total Compras IVA'),'TB',0,'C');
			$this->Cell(18,7,utf8_decode('Monto Exento del Comprobante'),'TB',0,'C');
			$this->Cell(18,7,utf8_decode('Base Imponible'),'TB',0,'C');
			$this->Cell(18,7,utf8_decode('% Alicuota'),'TB',0,'C');
			$this->Cell(18,7,utf8_decode('Impuesto IVA'),'TB',0,'C');
			$this->Cell(18,7,'IVA Retenido','TRB',0,'C');
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

$conexion=conexion();
	$codigo = @$_GET['codigo'];
	$chequera = @$_GET['chequera'];
	$cuenta=@$_GET["cuenta"];

	$Conn=conexion_conf();
	$var_sql = "SELECT * FROM parametros";
	$rs = query($var_sql, $Conn);
	$row_rs = fetch_array($rs);
	$nombreAgente = $row_rs['nomemp'];
	$rifAgente = $row_rs['rif'];
	$ciudadAgente = $row_rs['ciudad'];
	$estadoAgente = $row_rs['estado'];
	$periodo = $row_rs['periodo'];
	$direccionAgente = $row_rs['direccion'];
	$ivaasc = $row_rs['consecutivo_iva'];
	cerrar_conexion($Conn);
$conexion = conexion();
	$consulta1 = "SELECT * FROM ordenes_pago WHERE cheque='".$codigo."' and chequera='".$chequera."' and cuenta='".$cuenta."'";
	$resultado1 = query($consulta1, $conexion);
	$fila1 = fetch_array($resultado1);
	$nombreContribuyente = $fila1['bene'];
	$nroCuenta = $fila1['cuenta'];
	
	
		
	$consulta2 = "SELECT * FROM proveedores WHERE compania LIKE '%".$nombreContribuyente."%'";
	$resultado2 = query($consulta2, $conexion);
	$fila2 = fetch_array($resultado2);
	$rifContribuyente = $fila2['rif'];
	$direccionContribuyente = $fila2['direccion1'];
	$codEstado = $fila2['cod_estado'];
	$codMunicipio = $fila2['cod_municipio'];

	$consulta3 = "SELECT * FROM estados WHERE cod_estado='".$codEstado."'";
	$resultado3 = query($consulta3, $conexion);
	$fila3 = fetch_array($resultado3);
	$estadoContribuyente = $fila3['nombre'];

	$consulta4 = "SELECT * FROM municipios WHERE cod_municipio='".$codMunicipio."'";
	$resultado4 = query($consulta4, $conexion);
	$fila4 = fetch_array($resultado4);
	$municipioContribuyente = $fila4['nombre'];

	
	
	$consulta33 = "SELECT consecutivo_iva FROM cheques WHERE cheque='".$codigo."' and chequera='".$chequera."' and cuenta='".$cuenta."'";
	$resultado33 = query($consulta33, $conexion);
	$fila33 = fetch_array($resultado33);
	$codiva = $fila1['consecutivo_iva'];
	
	if($codiva!=0){
		$consecutivo=$fila1['consecutivo_iva'];
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
		$consecutivo=$rrp['consecutivo_iva']+1;
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
		$update="update parametros set consecutivo_iva=$consecutivo";
		$ru=query($update,$Conn);
		$conexion=conexion();
		$update="update cheques set consecutivo_iva=$consecutivo where cheque=$codigo";
		$ru=query($update,$conexion);
	}


	
	
	
	
	
	

//

$pdf->imprimir_datos($nombreAgente,$nombreContribuyente,$rifAgente,$rifContribuyente,$consefinal,$periodo,$direccionAgente);

$pdf->imprimir_tabla($pdf,$fila1);

$pdf->Output();
?>