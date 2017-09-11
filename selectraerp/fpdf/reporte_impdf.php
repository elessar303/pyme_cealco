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
	$var_encabezado1=utf8_decode($row_rs['encabezado1']);
	$var_encabezado2=utf8_decode($row_rs['encabezado2']);
	$var_encabezado3=utf8_decode($row_rs['encabezado3']);
	$var_encabezado4=utf8_decode($row_rs['encabezado4']);
	$var_imagen_izq=$row_rs['imagen_izq'];
	$var_imagen_der=$row_rs['imagen_der'];	
	$var_sql="select codigo,nomemp,departamento,presidente,periodo,cargo,nivel,desislr,ctaisrl,desiva,ctaiva,por_isv,compra,servicio,rif,nit,direccion,telefono,por_im,por_bomberos,lugar,sobregirop,autorizacionodp,claveodp,contrato,gas_dir from parametros";
	$rsu = query($var_sql,$Conn);
	$row_rsu = fetch_array($rsu);
	$var_nomemp=$row_rsu['nomemp'];

	cerrar_conexion($Conn);

        $this->SetFont("Arial","B",12);
     	$this->Image($var_imagen_izq,10,8,33);
     	$this->Ln(15);
     	$this->Cell(45);
        
     	$this->Cell(100,8,$var_encabezado1,0,0,"C");
     	$this->Image($var_imagen_der,170,15,33);
     	$this->Ln(10);
     	$this->Cell(35);
     	$this->Cell(120,8,$var_encabezado2,0,0,"C");
     	$this->Ln(10);
     	$this->Cell(10);
     	$this->Cell(170,8,$var_encabezado3,0,0,"C");
     	$this->Ln(10);
     	

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

function imprimir_datos($codigo,$chequera,$cuenta)
{
	$Conn=conexion_conf();
	$var_sql = "SELECT * FROM parametros";
	$rs = query($var_sql, $Conn);
	$row_rs = fetch_array($rs);
	$nombreAgente = $row_rs['nomemp'];
	$rifAgente = $row_rs['rif'];
	$ciudadAgente = $row_rs['ciudad'];
	$estadoAgente = $row_rs['estado'];
	$direccionAgente = $row_rs['direccion'];
	
	cerrar_conexion($Conn);

	$conexion = conexion();
	$consulta1 = "SELECT * FROM ordenes_pago WHERE cheque='".$codigo."' and chequera='".$chequera."' and cuenta='".$cuenta."'";
	$resultado1 = query($consulta1, $conexion);
	$fila1 = fetch_array($resultado1);
	$nombreContribuyente = $fila1['bene'];
	$monto = $fila1['monto'];
	$nroCuenta = $fila1['cuenta'];
	$nroFactura = $fila1['no_fac'];
	$concepto = $fila1['concepto'];
	$ordenPago = $fila1['numero_odp'];
	$montoPago = $fila1['montopago'];
	
		
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

	
	cerrar_conexion($conexion);

	
	$this->SetFont('Arial','B',10);
	$this->Cell(188,8,utf8_decode('*** COMPROBANTE DE RETENCIÓN DE IMPUESTOS MUNICIPALES ***'),0,1,"C");
	$this->Cell(188,8,utf8_decode('Decreto N° 1.808 del 23 de abril de 1997 - Art. 9,'),0,1,'C');
	$this->Cell(188,8,utf8_decode('Numeral 1 al 21. Gaceta Oficial N° 36.203'),0,1,'C');
	$this->Cell(188,8,utf8_decode('extraordinaria de fecha de mayo de 1997'),0,1,'C');


	$conexion=conexion();
	$consulta="select * from cheques where cheque=$codigo";
	$rc=query($consulta,$conexion);
	$rrc=fetch_array($rc);
	

	if($rrc['consecutivo_IM']!=0){
		$consecutivo=$rrc['consecutivo_IM'];
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
		$consecutivo=$rrp['consecutivo_IM']+1;
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
		$update="update parametros set consecutivo_IM=$consecutivo";
		$ru=query($update,$Conn);
		$conexion=conexion();
		$update="update cheques set consecutivo_IM=$consecutivo where cheque=$codigo";
		$ru=query($update,$conexion);
	}

	$this->Cell(188,5,utf8_decode('Nº Comprobante: ').$consefinal,0,1,'R');






	$this->SetFont('Arial','B',10);
	$this->Cell(30,7,"Lugar: ",0,0,'R');
	$this->SetFont('Arial','I',10);
	$this->Cell(30,7,$ciudadAgente,0,0,'L');
	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,"Estado: ",0,0,'R');
	$this->SetFont('Arial','I',10);
	$this->Cell(30,7,$estadoAgente,0,0,'L');
	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,"Fecha: ",0,0,'R');
	$this->SetFont('Arial','I',10);
	$this->Cell(30,7,date('d/m/Y'),0,1,'L');

	$this->SetFont('Arial','B',12);
	$this->Cell(188,8,utf8_decode('DATOS DEL AGENTE DE RETENCIÓN '),1,1,"C");

	$this->SetFont('Arial','I',10);
        $this->SetWidths(array(78,110));
	$this->SetAligns(array('L','L'));
        $this->Setceldas(array(0,0));
	$this->Setancho(array(5,5));
        $this->Row(array(utf8_decode('Nombre o Razón Social del Agente de Retención:'),$nombreAgente));
	$this->Row(array(utf8_decode('Registro de Información Fiscal del Agente de Retención:'),$rifAgente));
	$this->Row(array(utf8_decode('Dirección Fiscal del Agente de Retención:'),$direccionAgente));

	

	$this->SetFont('Arial','B',12);
	$this->Cell(188,8,utf8_decode('DATOS DEL BENEFICIARIO '),1,1,"C");

	$this->SetFont('Arial','I',10);
        $this->SetWidths(array(78,110));
	$this->SetAligns(array('L','L'));
        $this->Setceldas(array(0,0));
	$this->Setancho(array(5,5));
        $this->Row(array(utf8_decode('Hemos Recibido de:'),$nombreContribuyente));
	$this->Row(array(utf8_decode('R.I.F. / C.I.:'),$rifContribuyente));
	$this->Row(array(utf8_decode('Dirección:'),utf8_decode($direccionContribuyente).". Municipio ".$municipioContribuyente.", Estado ".$estadoContribuyente));
	$this->Row(array(utf8_decode('La cantidad de:'),$monto1=number_format($monto,2,',','.')));
	$this->Row(array(utf8_decode('Por Concepto de:'),utf8_decode($concepto)));
	

	$this->SetFont('Arial','B',12);
	$this->Cell(188,8,utf8_decode('INFORMACIÓN RELACIONADA AL IMPUESTO RETENIDO '),1,1,"C");


}
function imprimir_tabla ($pdf,$codigo,$chequera,$cuenta)
{
	

	$cantidad_registros=10;
	$conexion=conexion();

	$consulta1 = "SELECT * FROM ordenes_pago WHERE cheque='".$codigo."' and chequera='".$chequera."' and cuenta='".$cuenta."'";
	$resultado1 = query($consulta1, $conexion);
	$fila1 = fetch_array($resultado1);
	$num_odp = $fila1['numero_odp'];
	$montoPago = $fila1['montopago'];

	$consulta = "SELECT * FROM facturas WHERE numord=$num_odp";
	$rs = query($consulta, $conexion);
	$num_paginas=obtener_num_paginas($consulta,$cantidad_registros);
	$total= num_rows($rs);

	$this->SetFont('Arial','B',10);
	//Cabecera
	$this->Cell(31,7,'Orden de Pago',0,0,'C');
	$this->Cell(31,7,'Monto Obj. Ret.',0,0,'C');
	$this->Cell(31,7,'Monto Retenido',0,0,'C');
	$this->Cell(31,7,'Porcentaje',0,0,'C');
	$this->Cell(31,7,'Monto Pago',0,0,'C');
	$this->Cell(31,7,'Fecha Pago',0,1,'C');
	$this->Ln(2);
	
			
	//Datos

	$cont=1;
	$contar=1;
	$pagina=1;
	$totalfinal=0;
	while ($total>=$contar)
	{
		$row_rs = fetch_array($rs);
		
		$objetoRetenido =$row_rs['montobase'];
		$porcentaje = $row_rs['porcentaje_im'];
		$montoRetenido = $row_rs['montoim'];
		$fechaFactura = $row_rs['fec_fac'];
	
		$totalobjeto = $totalobjeto + $objetoRetenido;
		$totalmonto = $totalmonto + $montoRetenido;
		
		$totalobjeto_float  = ((real) $totalobjeto);		
		$totalobjeto_float_format  = number_format($totalobjeto_float,2,',','.');		
		$totalobjeto_float_format  = ((string)$totalobjeto_float_format);
		
		$totalmonto_float  = ((real) $totalmonto);		
		$totalmonto_float_format  = number_format($totalmonto_float,2,',','.');
		$totalmonto_float_format  = ((string)$totalmonto_float_format);
	
		// llamado para hacer multilinea sin que haga salto de linea
		$this->SetFont('Arial','I',10);
		$this->SetWidths(array(31,31,31,31,31,31));
		$this->Setancho(array(5,5,5,5,5,5));
		$this->SetAligns(array('C','C','C','C','C'));
		$this->Row(array($num_odp,number_format($objetoRetenido,2,',','.'),number_format($montoRetenido,2,',','.'),"0,01",number_format($montoPago,2,',','.'),fecha($fechaFactura)));
		
	// fin
	//echo $cont;
		if($cont==$cantidad_registros)
		{	
			if ($pagina!=$num_paginas){
				$this->Ln(60);
				$pagina++;
				$pdf->imprimir_datos($pagina,$num_paginas,$cod_centro,$var_rows,$var_sql,$centro,$tipo_orden,$situacion);
				//Cabecera
				//Cabecera
				$this->Cell(31,7,'Orden de Pago',0,0,'C');
				$this->Cell(31,7,'Monto Obj. Ret.',0,0,'C');
				$this->Cell(31,7,'Monto Retenido',0,0,'C');
				$this->Cell(31,7,'Porcentaje',0,0,'C');
				$this->Cell(31,7,'Monto Pago',0,0,'C');
				$this->Cell(31,7,'Fecha Pago',0,1,'C');
				$this->Ln(2);				
				$cont=1;
			}
		}else{$cont++;}
		//echo $contar;
		$contar++;
		
		}
	$this->Ln(2);	
	$this->Cell(31,7,'Totales:','LTB',0,'R');
	$this->Cell(31,7, $totalobjeto_float_format,'TB',0,'C');
	$this->Cell(31,7,$totalmonto_float_format,'TB',0,'C');
	$this->Cell(93,7,'','TRB',1,'C');
	
}
function pago($codigo){
	$conexion=conexion();
	$consulta1 = "SELECT * FROM ordenes_pago WHERE cheque=".$codigo."";
	$resultado1 = query($consulta1, $conexion);
	$fila1 = fetch_array($resultado1);
	//$nombreContribuyente = $fila1['bene'];
	$nroCuenta = $fila1['cuenta'];
	//$nro_odp= $fila1['numero_odp'];

	$this->Ln(5);
	$this->SetFont('Arial','B',10);
	$this->Cell(80,7,'Datos relacionados con el Pago: ',0,0,'L');
	$this->SetFont('Arial','I',10);
	$this->Cell(100,7,utf8_decode('Nº de Cheque: ').$codigo,0,1,'L');
	$this->Cell(80,7,'',0,0,'L');
	$this->Cell(100,7,'Cuenta Bancaria:'.$nroCuenta,0,1,'L');
	

}
function Footer(){
	$this->SetY(-50);
	$this->SetFont('Arial','B',10);
	$this->Cell(94,7,"Firma del Beneficiario ",0,0,'C');
	$this->Cell(94,7,utf8_decode('Firma y Sello Agente de Retención'),0,1,'C');
	$this->Cell(94,7,'',0,0,'C');
	$this->Cell(94,7,'',0,1,'C');
	$this->Cell(94,7,'',0,0,'C');
	$this->Cell(94,7,'',0,1,'C');
	$this->Cell(94,7,'_________________________________',0,0,'C');
	$this->Cell(94,7,'_________________________________',0,1,'C');
	
}

}


//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

	

	$codigo = @$_GET['codigo'];
	$chequera = @$_GET['chequera'];
	$cuenta=@$_GET["cuenta"];

	
	

$pdf-> imprimir_datos($codigo,$chequera,$cuenta);
$pdf->imprimir_tabla ($pdf,$codigo,$chequera,$cuenta);
$pdf->pago($codigo);





$pdf->Output();
?>