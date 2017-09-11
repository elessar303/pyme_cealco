<?php 
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');
require_once '../lib/config.php';
require_once '../lib/common.php';
include('../lib/numerosALetras.class.php');
require_once '../lib/pdf.php';


class PDF extends FPDF
{
var $usuario;
var $pdff;
var $odp;
var $tipo;
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
		$this->Cell(100,20,'ORDENES PAGO',0,0,'C');
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
	$this->pdff=$pdf;
	
	$conexion=conexion();
	$rif = $fila_odp['rif'];
	$beneficiario = $fila_odp['bene'];
	$nro_odp = $fila_odp['numero_odp'];
	$this->odp=$fila_odp['numero_odp'];
	$montoPago = $fila_odp['montopago'];
	$monto = $fila_odp["monto"];
	$fecha = $fila_odp["fecha"];
	$concepto = $fila_odp['concepto'];
	$nro_ocs = $fila_odp['numero_ocs'];
	$estado_ocs = $fila_odp['estado'];
	$iva = $fila_odp['monto_igv'];
	
	$consulta_odp="SELECT * FROM facturas WHERE numord='".$nro_odp."'";
	$resultado_odp=query($consulta_odp,$conexion);
	$fila_factura=fetch_array($resultado_odp);
	$nro_factura = $fila_factura['no_fac'];
	
	
	$observaciones = $fila_odp['observacion'];

	$conOCS = "SELECT * FROM ordenes WHERE codigo='".$this->odp."'";
	$resOCS = query($conOCS,$conexion);
	$filaOCS = fetch_array($resOCS);
	$orden = $filaOCS['codigo_ref'];
	$tipo=$row_rs['tipo'];


	$conTipo = "SELECT * FROM ordenes_tipos WHERE cod_orden_tipo='".$tipo."'";
	$resTipo = query($conTipo, $conexion);
	$filaTipo = fetch_array($resTipo);
	$this->tipo = $filaTipo['descripcion'];


	
	
	
	$cod_prov = $fila_odp['codigo_cliente'];
	$consulta_prove="SELECT * FROM proveedores WHERE cod_proveedor='".$cod_prov."'";
	$resultado_prove=query($consulta_prove,$conexion);
	$fila_prove=fetch_array($resultado_prove);
	$direccion=$fila_prove['direccion1'].' '.$fila_prove['direccion2'];
	$correo=$fila_prove['email'];
	$cuenta=$fila_prove['cuenta_bancaria'];
	//$banco=$fila_prove['cuenta_bancaria'];
	$this->SetFont('Arial','B',10);
	$this->Ln(10);
	$this->Cell(150,8,'SOLICITUD DE PAGO A TERCEROS No.: '.$nro_odp,0,0,"C");
	$this->cell(38,8,'Fecha: '.fecha($fecha),0,0,'C');
	$this->Ln(10);
	$this->SetFont('Arial','B',8);
	$this->Cell(188,5,'BENEFICIARIO ',1,0,"C");
	$this->Ln(10);
	// llamado para hacer multilinea sin que haga salto de linea
	$this->SetFont('Arial','I',8);
        $this->SetWidths(array(130,58));
	$this->SetAligns(array('L','L'));
        $this->Setceldas(array(0,0));
	$this->Setancho(array(5,5));
	$cam=utf8_decode('Nombre o Razón Social:  ');
	
        $this->Row(array($cam.utf8_decode($beneficiario),'R.I.F.:  '.utf8_decode($rif)));
	$this->Ln(5);
	//$cam=utf8_decode('Dirección Fiscal:   ');
	//$cam2=utf8_decode('Correo Electrónico:   ');
	//$this->Row(array($cam.utf8_decode($direccion),$cam2.utf8_decode($correo)));
	//$this->Row(array('Cuenta Bancaria: '.$cuenta,'Banco: '));

	$this->SetFont('Arial','B',8);
	$this->Cell(188,5,'DATOS DE PAGO ',1,0,"C");
	$this->Ln();
	$this->SetFont('Arial','I',8);
	$n = new numerosALetras();
	$montoLetras=$n->convertir($montoPago);

	
	

	$conexion=conexion();	
	
	$var_sql="select * from ordenes where codigo=".$this->odp." and estado <> 'Anulado'";
	$rs = query($var_sql,$conexion);
	if (num_rows ($rs)!=0){
		$row_rs = fetch_array($rs);
		$var_unidad=$row_rs['unidad'];
		$var_concepto=$row_rs['concepto'];
		$var_sql="SELECT descripcion FROM unidades WHERE cod_unidad=".$var_unidad;
		$rsu = query($var_sql,$conexion);		
		$row_rsu = fetch_array($rsu);
	}
	$var_nom_und=$row_rsu['descripcion'];
	
	//$this->MultiCell(188,5,'Unidad Solicitante:  '.$var_nom_und);
	$this->Ln(5);
	$this->MultiCell(188,5,'Concepto:  '.utf8_decode($concepto));
	//inicio
	$this->Ln(2);
	$this->SetFont('Arial','I',8);
        $this->SetWidths(array(63,63,62));
	$this->SetAligns(array('L','L','L'));
        $this->Setceldas(array(0,0,0));
	$this->Setancho(array(5,5,5));
        //$this->Row(array(utf8_decode('Nº Factura:  ').$nro_factura,utf8_decode('Nº Control:     ').$nro_ocs,utf8_decode('Nº Referencia:   ').$orden));
	//fin
	//$this->MultiCell(188,5,'Observaciones:  '.utf8_decode($observaciones));

	$this->SetWidths(array(130,58));
	$cam=utf8_decode('Monto en Números: ');
	$this->Row(array('La cantidad de: '.strtoupper($montoLetras),$cam.number_format($montoPago, 2, ',', '.').' '.$moneda));
	
	

}

function Firmas_terceros($fila_odp,$moneda){

	$valuacion = $fila_odp['valuacion'];
	$montoPago = $fila_odp['montopago'];
	$iva=$fila_odp['monto_igv'];
	$montoExento = $fila_odp['mont_exe'];
	$amortizacion = $fila_odp['anticipo'];
	$retencionLab = $fila_odp['laboral'];
	$retencionFiel = $fila_odp['fiel'];
	$deduccion = $fila_odp['multa'];
	$retencionISLR = $fila_odp['monto_islr'];
	$timbre = $fila_odp['monto_estam'];
	$fondoAS = $fila_odp[''];
	$retencionIVA = $fila_odp['monto_retiva'];
	$beneficiario = $fila_odp['bene'];
	$rif = $fila_odp['rif'];

	$this->Ln(30);
	$this->SetFont('Arial','B',8);
	$this->Cell(30);
	$this->Cell(28,5,'Firma:',0,0,'R');
	$this->SetFont('Arial','I',8);
	$this->Cell(90,5,'__________________________',0,1,'C');
	$this->Cell(58);
	$this->Cell(90,5,utf8_decode('Recibí Conforme'),0,1,'C');
	$this->SetFont('Arial','B',8);
	$this->Ln(2);
	$this->Cell(58);
	$this->MultiCell(90,5,'Nombre:  '.$beneficiario,0,'L');
	$this->SetFont('Arial','B',8);
	$this->Ln(2);
	$this->Cell(58);
	$this->Cell(90,5,'C.I. / R.I.F.: '.$rif,0,1,'L');
	$this->Ln(2);
	$this->SetFont('Arial','B',8);
	$this->Cell(58);
	$this->Cell(20,5,'Fecha:',0,0,'R');
	$this->Cell(70,5,' _____ / _____ / _________',0,1);
	$this->Ln();
	

	
	

}

function datos_partidas($nro_odp,$periodo,$nro_odp,$fila_odp, $moneda,$pdf)
{
	$cantidad_registros=10;
	if ($cont+5>$cantidad_registros){
		$this->Ln(30);
	}
		$this->SetFont("Arial","B",8);
		$this->Ln(2);
		$this->Cell(188,5,utf8_decode('CODIFICACIÓN PRESUPUESTARIA '),1,0,'C');
		$this->Ln();
	
	
		$this->SetFont("Arial","I",8);
		$string=utf8_decode('Programática');
		$this->Cell(30,5,$string,'LTB',0,'C');
		$this->Cell(30,5,'Cuenta','LTB',0,'C');
		$this->Cell(20,5,'Ordinal','LTB',0,'C');
		$string=utf8_decode('Descripción de la Cuenta');
		$this->Cell(80,5,$string,'LTB',0,'C');
		$this->Cell(28,5,'Monto Bs.F','LTBR',0,'C');
		$this->Ln();
	
		$conexion=conexion();
		$rs = query("SELECT * FROM cwpreeje where RecNoOrders = '".$nro_odp."'",$conexion);
		$totalwhile=num_rows($rs);
		$contar=1;
		$cantidad_registros=10;
		while ($totalwhile>=$contar) 
		{ 
			$conexion=conexion();
			$row_rs = fetch_array($rs);
			$cont2=$cont2+1;
			$var_sector=$row_rs['Sector'];
			$var_programa=$row_rs['Programa'];
			$var_actividad=$row_rs['Actividad'];
			$var_partida=$row_rs['Partida'];
			$var_monto3=$row_rs['Monto'];
			$var_ordinal=$row_rs['ordinal'];
			$contador++;
			$rso = query("SELECT Denominacion FROM cwprecue where CodCue = '$var_partida'",$conexion);
			$row_rso = fetch_array($rso);
			$var_descripcion=$row_rso['Denominacion'];
			$monto_3  = number_format($var_monto3,2,',','.');
			$this->SetFont("Arial","I",9);
			// llamado para hacer multilinea sin que haga salto de linea
			$this->SetWidths(array(30,30,20,80,28,));
			$this->SetAligns(array('C','C','C','L','R'));
			$this->Setceldas(array(0,0,0,0,0));
			$this->Setancho(array(7,7,7,5,7));
			if($var_sector!='99'){
				$this->Row(array($var_sector.".".$var_programa.".".$var_actividad,$var_partida,$var_ordinal,utf8_decode($var_descripcion),$monto_3));
			}else{
				$this->Row(array($var_programa.".".$var_actividad,$var_partida,$var_ordinal,utf8_decode($var_descripcion),$monto_3));
			}
			//maximizar lineas
			if (strlen($var_descripcion)>50){
				$cantidad_registros-=1;
				if($cont>$cantidad_registros){
					$cantidad_registros=$cont;
				}
				
			}
			if($cont==$cantidad_registros)
			{
				$this->SetFont('Arial','I',8);
    				
				$this->Ln(70);
				$cantidad_registros=20;
				$pdf->imprimir_datos($nro_odp,$fila_odp, $moneda,$pdf);
				
				$this->SetFont("Arial","B",8);
				$this->Ln(2);
				$this->Cell(188,5,utf8_decode('CODIFICACIÓN PRESUPUESTARIA '),1,0,'C');
				$this->Ln();
			
			
				$this->SetFont("Arial","I",8);
				$string=utf8_decode('Programática');
				$this->Cell(30,5,$string,'LTB',0,'C');
				$this->Cell(30,5,'Cuenta','LTB',0,'C');
				$this->Cell(20,5,'Ordinal','LTB',0,'C');
				$string=utf8_decode('Descripción de la Cuenta');
				$this->Cell(80,5,$string,'LTB',0,'C');
				$this->Cell(28,5,'Monto Bs.F','LTBR',0,'C');
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
   	$this->SetY(-47);
    	
	

	//reajustar posicion tabla
	//$x=$this->GetX();
        //$y=$this->GetY();
        
	//$this->SetXY($x,$y-8);
	$bool=validar_firma("ODP".$this->tipo);
	if ($bool==true){
		firma_dinamica("ODP".$this->tipo,$this->pdff,6,10);
	}else{
		odp($this->pdff);
	}

    	$this->SetFont('Arial','I',8);
    	$this->Cell(0,5,'Elaborado Por: '.$this->usuario,0,1,'L');
	$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');
}




}


//Creación del objeto de la clase heredada
$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$conexion=conexion();	
$nro_odp=@$_GET['codigo'];
$consulta_odp="SELECT * FROM ordenes_pago WHERE numero_odp='".$nro_odp."'";
$resultado_odp=query($consulta_odp,$conexion);
$fila_odp=fetch_array($resultado_odp);
$pdf->usuario=$fila_odp['log_usr'];
$Conn=conexion_conf();
	
	$var_sql="select moneda,periodo from parametros";
	$rsu = query($var_sql,$Conn);
	$row_rsu = fetch_array($rsu);
	$moneda=$row_rsu['moneda'];
	$periodo=$row_rsu['periodo'];
	cerrar_conexion($Conn);
	$pdf->imprimir_datos($nro_odp,$fila_odp, $moneda,$pdf);
	

	$pdf->Firmas_terceros($fila_odp,$moneda);
	//$pdf->datos_partidas($nro_odp,$periodo,$nro_odp,$fila_odp, $moneda,$pdf);
	$pdf->odp=$fila_odp;






$pdf->Output();
?>