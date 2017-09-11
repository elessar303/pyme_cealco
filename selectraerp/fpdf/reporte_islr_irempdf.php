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
	$this->SetFont('Arial','B',12);
        if($row_rsu['rif']=='G200081643'){
		
		$this->Image($var_imagen_izq,5,12,80,15);
		$this->Image($var_imagen_der,175,12,20,13);
		$this->Cell(65);
		$this->Cell(100,20,'I.S.L.R.',0,0,'C');
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

function imprimir_datos($pagina,$num_paginas,$codigo,$nombreAgente,$rifAgente,$ciudadAgente,$estadoAgente,$direccionAgente,$chequera,$cuenta,$telfAgente)
{
	$conexion=conexion();
	$consulta1 = "SELECT * FROM ordenes_pago WHERE cheque='".$codigo."' and chequera='".$chequera."' and cuenta='".$cuenta."'";
	$resultado1 = query($consulta1, $conexion);
	$fila1 = fetch_array($resultado1);
	$nombreContribuyente = $fila1['bene'];
	$nroCuenta = $fila1['cuenta'];
	$nro_odp= $fila1['numero_odp'];
	//echo $consulta1." odp:".$nro_odp;
			
	$consulta2 = "SELECT * FROM proveedores WHERE compania LIKE '%".$nombreContribuyente."%'";
	$resultado2 = query($consulta2, $conexion);
	$fila2 = fetch_array($resultado2);
	$rifContribuyente = $fila2['rif'];
	$direccionContribuyente = $fila2['direccion1'];
	$codEstado = $fila2['cod_estado'];
	$codMunicipio = $fila2['cod_municipio'];
	$telf = $fila2['rep_apellidos'];

	$consulta3 = "SELECT * FROM estados WHERE cod_estado='".$codEstado."'";
	$resultado3 = query($consulta3, $conexion);
	$fila3 = fetch_array($resultado3);
	$estadoContribuyente = $fila3['nombre'];

	$consulta4 = "SELECT * FROM municipios WHERE cod_municipio='".$codMunicipio."'";
	$resultado4 = query($consulta4, $conexion);
	$fila4 = fetch_array($resultado4);
	$municipioContribuyente = $fila4['nombre'];
	$this->SetFont('Arial','B',10);
	$this->Cell(188,8,utf8_decode('COMPROBANTE RETENCIÓN '),0,1,"C");
	$this->SetFont('Arial','I',10);
	$this->Cell(188,8,'Impuesto Sobre la Renta (ISLR)',0,1,"C");
	$this->Ln(5);

	$conexion=conexion();
	$consulta="select * from cheques where cheque=$codigo";
	$rc=query($consulta,$conexion);
	$rrc=fetch_array($rc);
	

	if($rrc['consecutivo_ISLR']!=0){
		$consecutivo=$rrc['consecutivo_ISLR'];
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
		$consecutivo=$rrp['consecutivo_ISLR']+1;
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
		$update="update parametros set consecutivo_ISLR=$consecutivo";
		$ru=query($update,$Conn);
		$conexion=conexion();
		$update="update cheques set consecutivo_ISLR=$consecutivo where cheque=$codigo";
		$ru=query($update,$conexion);
	}

	$this->Cell(188,5,utf8_decode('Nº Comprobante: ').$consefinal,0,1,'R');


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
	$this->Row(array(utf8_decode('Teléfonos:'),$telfAgente));
	

	$this->SetFont('Arial','B',12);
	$this->Cell(188,8,utf8_decode('DATOS DEL CONTRIBUYENTE '),1,1,"C");

	$this->SetFont('Arial','I',10);
        $this->SetWidths(array(78,110));
	$this->SetAligns(array('L','L'));
        $this->Setceldas(array(0,0));
	$this->Setancho(array(5,5));
        $this->Row(array(utf8_decode('Nombre o Razón Social:'),$nombreContribuyente));
	$this->Row(array(utf8_decode('R.I.F. / C.I.:'),$rifContribuyente));
	$this->Row(array(utf8_decode('Dirección:'),$direccionContribuyente.". Municipio ".$municipioContribuyente.", Estado ".$estadoContribuyente));
	$this->Row(array(utf8_decode('Teléfonos:'),$telf));
	$this->SetFont('Arial','B',8);
	$this->Row(array("",'Valor de la unidad tributaria: 46.00'));

	$this->SetFont('Arial','B',12);
	$this->Cell(188,8,utf8_decode('INFORMACIÓN RELACIONADA AL IMPUESTO RETENIDO '),1,1,"C");
return $nro_odp;

}
function imprimir_tabla ($pdf,$num_odp)
{
	$cantidad_registros=10;
	$conexion=conexion();
	$consulta = "SELECT * FROM facturas WHERE numord=$num_odp";
	$rs = query($consulta, $conexion);
	$num_paginas=obtener_num_paginas($consulta,$cantidad_registros);
	$total= num_rows($rs);

	$this->SetFont('Arial','B',10);
	//Cabecera
	$this->Cell(22,7,'Factura',0,0,'C');
	$this->Cell(22,7,'Monto total',0,0,'C');
	$this->Cell(65,7,'Concepto',0,0,'C');
	$this->Cell(25,7,'Base imponible',0,0,'C');
	$this->Cell(24,7,'Porc.%',0,0,'C');
	$this->Cell(30,7,'Monto Retenido',0,1,'C');
	$this->Ln(2);
	
			
	//Datos

	$cont=1;
	$contar=1;
	$pagina=1;
	$totalfinal=0;
	while ($total>=$contar)
	{
		$row_rs = fetch_array($rs);
		
		$fechaFactura = $row_rs['fec_fac'];
		$nroFactura = $row_rs['no_fac'];
		$montofactiva = $row_rs['montofactiva'];
		$objetoRetenido =$row_rs['montobase'];
		$porcentaje = $row_rs['porcentaje_islr'];
		$montoRetenido = $row_rs['montoislr'];

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
		$this->SetWidths(array(22,22,65,25,24,30));
		$this->Setancho(array(5,5,5,5,5,5));
		$this->SetAligns(array('C','C','L','C','C'));
		$this->Row(array($nroFactura,number_format($montofactiva,2,',','.'),'',number_format($objetoRetenido,2,',','.'),number_format($porcentaje,2,',','.'),number_format($montoRetenido,2,',','.')));
		
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
				//Cabecera
				$this->Cell(22,7,'Factura',0,0,'C');
				$this->Cell(22,7,'Monto total',0,0,'C');
				$this->Cell(65,7,'Concepto',0,0,'C');
				$this->Cell(25,7,'Base imponible',0,0,'C');
				$this->Cell(24,7,'Porc.%',0,0,'C');
				$this->Cell(30,7,'Monto Retenido',0,1,'C');
				$this->Ln(2);				
				$cont=1;
			}
		}else{$cont++;}
		//echo $contar;
		$contar++;
		
		}
	$this->Ln(2);	
	$this->Cell(109,7,'Totales:','LTB',0,'R');
	$this->Cell(25,7, $totalobjeto_float_format,'TB',0,'C');
	$this->Cell(24,7,'','TB',0,'C');
	$this->Cell(30,7,$totalmonto_float_format,'TRB',0,'C');
	$this->Ln();
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
function Footer1($ciudadAgente,$estadoAgente){
	$this->SetY(-50);
	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,"Lugar: ",0,0,'R');
	$this->SetFont('Arial','I',10);
	$this->Cell(30,7,$ciudadAgente,0,0,'L');
	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,"Estado: ",0,0,'R');
	$this->SetFont('Arial','I',10);
	$this->Cell(30,7,$estadoAgente,0,0,'L');
	$this->SetFont('Arial','B',10);
	$this->Cell(88,7,utf8_decode('Firma y Sello Agente de Retención'),0,1,'C');
	$this->Cell(20,7,'Fecha:',0,0,'R');
	$this->SetFont('Arial','I',10);
	$this->Cell(30,7,date('d/m/Y'),0,1,'L');
	$this->SetFont('Arial','B',10);
	$this->Cell(100,7,'',0,0);
	$this->Cell(88,7,'_________________________________',0,1,'C');
	
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

	$Conn=conexion_conf();
	$var_sql = "SELECT * FROM parametros";
	$rs = query($var_sql, $Conn);
	$row_rs = fetch_array($rs);
	$nombreAgente = $row_rs['nomemp'];
	$rifAgente = $row_rs['rif'];
	$ciudadAgente = $row_rs['ciudad'];
	$estadoAgente = $row_rs['estado'];
	$direccionAgente = $row_rs['direccion'];
	$telfAgente = $row_rs['telefono'];
	cerrar_conexion($Conn);
$num_odp=$pdf->imprimir_datos($pagina,$num_paginas,$codigo,$nombreAgente,$rifAgente,$ciudadAgente,$estadoAgente,$direccionAgente,$chequera,$cuenta,$telfAgente);
$pdf->imprimir_tabla ($pdf,$num_odp);
$pdf->pago($codigo);
$pdf->Footer1($ciudadAgente,$estadoAgente);




$pdf->Output();
?>