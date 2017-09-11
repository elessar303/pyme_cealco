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

function imprimir_datos($nro_odp,$fila_odp, $moneda,$pdf,$forma_pago)
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

	$conOCS = "SELECT * FROM ordenes WHERE codigo='".$nro_ocs."'";
	$resOCS = query($conOCS,$conexion);
	$filaOCS = fetch_array($resOCS);
	$orden = $filaOCS['codigo_ref'];
	$tipo=$row_rs['tipo'];


	$conTipo = "SELECT * FROM ordenes_tipos WHERE cod_orden_tipo='".$tipo."'";
	$resTipo = query($conTipo, $conexion);
	$filaTipo = fetch_array($resTipo);
	$this->tipo = $filaTipo['descripcion'];

	if($fila_odp['estado']=="Anulada"){
		$this->Image("../imagenes/anulado.gif",10,60,180);
		$this->SetY(30);
	}
	
	
	
	$cod_prov = $fila_odp['codigo_cliente'];
	$consulta_prove="SELECT * FROM proveedores WHERE cod_proveedor='".$cod_prov."'";
	$resultado_prove=query($consulta_prove,$conexion);
	$fila_prove=fetch_array($resultado_prove);
	$direccion=$fila_prove['direccion1'].' '.$fila_prove['direccion2'];
	$correo=$fila_prove['email'];
	$cuenta=$fila_prove['cuenta_bancaria'];
	//$banco=$fila_prove['cuenta_bancaria'];

	
	
	
// 	$this->SetFont('Arial','B',10);
// 	$this->Cell(150,8,'ORDEN DE PAGO No.: '.$nro_odp,0,0,"C");
// 	$this->cell(38,8,'Fecha: '.fecha($fecha),0,0,'C');
// 	$this->Ln();
// 	$this->SetFont('Arial','B',8);
// 	$this->Cell(188,5,'BENEFICIARIO ',1,0,"C");
// 	$this->Ln();
// 	// llamado para hacer multilinea sin que haga salto de linea
// 	$this->SetFont('Arial','I',8);
//         $this->SetWidths(array(130,58));
// 	$this->SetAligns(array('L','L'));
//         $this->Setceldas(array(0,0));
// 	$this->Setancho(array(5,5));
// 	$cam=utf8_decode('Nombre o Razón Social:  ');
//         $this->Row(array($cam.utf8_decode($beneficiario),'R.I.F.:  '.utf8_decode($rif)));
// 	$cam=utf8_decode('Dirección Fiscal:   ');
// 	$cam2=utf8_decode('Correo Electrónico:   ');
// 	$this->Row(array($cam.utf8_decode($direccion),$cam2.utf8_decode($correo)));
// 	$this->Row(array('Cuenta Bancaria: '.$cuenta,'Banco: '));
// 
// 	$this->SetFont('Arial','B',8);
// 	$this->Cell(188,5,'DATOS DE PAGO ',1,0,"C");
// 	$this->Ln();
// 	$this->SetFont('Arial','I',8);
// 	$n = new numerosALetras();
// 	$montoLetras=$n->convertir($montoPago);
// 
// 	
	
	$n = new numerosALetras();
	$montoLetras=$n->convertir($montoPago);
	$conexion=conexion();	
	
	$var_sql="select * from ordenes where codigo=".$nro_ocs." and estado <> 'Anulado'";
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
	
	
	$this->SetFont('SanserifB','',10);	

	$this->Cell(158,8,'',0,0);
	$this->Cell(30,8,$nro_odp,0,1,'C');
	$this->Cell(38,8,'',0,0);
	$this->Cell(40,8,date('Y'),0,1,'C');
	$this->Ln(2);
	$this->Cell(160,3,'',0,0);
	$this->Cell(38,3,fecha($fecha),0,1,'R');
	$this->SetFont('SanserifB','',9);	
	$this->Ln(1);
	$this->Cell(38,4,'',0,0);
	$this->Cell(150,4,utf8_decode($var_nom_und),0,1,'C');
	
	// tipo de financiamiento
	$this->Cell(38,4,'',0,0);
	$this->Cell(150,4,utf8_decode('Presupuesto ').date('Y'),0,1,'C');
	$this->SetFont('SanserifB','',8);
	$this->Ln(11);
	$this->SetWidths(array(38,38,78,38));
	$this->SetAligns(array('C','C','C','C'));
        $this->Setceldas(array(0,0,0,0));
 	$this->Setancho(array(5,5,5,5));
	$this->Row(array(utf8_decode($rif),$forma_pago ,utf8_decode(substr($beneficiario,0,30)),number_format($montoPago,2,',','.')));
	$this->Cell(38,5,'',0,0);
	$this->MultiCell(150,5,strtoupper($montoLetras),0,'J');
	$lineas=strlen($montoLetras)/58;
// 	$this->Cell(188,5,$lineas,0,1);
	if($lineas>=0 && $lineas<=1){
		$this->Ln(5);
	}
	$this->SetFont('SanserifB','',6);
	$this->Cell(38,5,'',0,0);
	$this->MultiCell(150,5,utf8_decode($concepto),0,'J');
	
	$lineas=strlen($concepto)/90;
// 	$this->Cell(188,5,$lineas,0,1);
	if($lineas>=0 && $lineas<=1){
		$this->Ln(6);
	}
	
	$this->Ln(10);


// 	$this->MultiCell(188,5,'Unidad Solicitante:  '.$var_nom_und);
// 	$this->MultiCell(188,5,'Concepto:  '.utf8_decode($concepto));
// 	//inicio
// 	$this->SetFont('Arial','I',8);
//         $this->SetWidths(array(63,63,62));
// 	$this->SetAligns(array('L','L','L'));
//         $this->Setceldas(array(0,0,0));
// 	$this->Setancho(array(5,5,5));
//         $this->Row(array(utf8_decode('Nº Factura:  ').$nro_factura,utf8_decode('Nº Control Orden:     ').$nro_ocs,utf8_decode('Nº Referencia Orden:   ').$orden));
// 	//fin
// 	$this->MultiCell(188,5,'Observaciones:  '.utf8_decode($observaciones));
// 
// 	$this->SetWidths(array(130,58));
// 	$cam=utf8_decode('Monto en Números: ');
// 	$this->Row(array('La cantidad de: '.strtoupper($montoLetras),$cam.number_format($montoPago, 2, ',', '.').' '.$moneda));
// 	
	

}

function totales($fila_odp,$moneda){

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
	$fondoAS = $fila_odp['monto_aporte'];
	$retencionIVA = $fila_odp['monto_retiva'];
	$beneficiario = $fila_odp['bene'];
	$rif = $fila_odp['rif'];

	$this->SetFont('SanserifB','',10);
	$this->SetWidths(array(5,76,51,61));
	$this->SetAligns(array('L','L','R','R'));
        $this->Setceldas(array(0,0,0,0));
 	$this->Setancho(array(5,5,5,5));
	//orden de compra
	$this->Row(array('',utf8_decode('Orden de Compra / Servicio o Valuación'), number_format($valuacion,2,",","."),''));
	$this->Row(array('',utf8_decode( $fila_odp['con1'].'% I.V.A.'),number_format($iva,2,",",".") ,''));
	$this->Row(array('',utf8_decode('Monto Exento'),number_format($montoExento,2,",",".") ,''));
	$this->Row(array('',utf8_decode('Sub-Total'),number_format($valuacion+$iva,2,",",".") ,''));

	//retenciones
	$this->Row(array('',utf8_decode('Amortización Anticipo Recibido'),'',number_format($amortizacion,2,",",".") ));
	$this->Row(array('',utf8_decode('Retención Laboral'),'',number_format($retencionLab,2,",",".") ));
	$this->Row(array('',utf8_decode('Retención Fiel Cumplimiento'),'',number_format($retencionFiel,2,",",".") ));
	$this->Row(array('',utf8_decode('Deducción'),'',number_format($deduccion,2,",",".") ));
	if($fila_odp['porce_reten']!=0){
		$string=$fila_odp['porce_reten'];
	}
	$this->Row(array('',$string.'%'.utf8_decode('Retención ISLR'),'',number_format($retencionISLR,2,",",".") ));
	$this->Row(array('','Timbre Fiscal','',number_format($timbre,2,",",".") ));
	$this->Row(array('','Retencion por Aporte Social','',number_format($fondoAS,2,",",".") ));	
	$this->Row(array('',$fila_odp['con2'].utf8_decode('% Retención I.V.A.'),'',number_format($retencionIVA,2,",",".") ));	

 	$this->Ln(4);
	$this->Row(array('','','',number_format($montoPago,2,",",".") ));

	
	$this->Ln(16);


	
	

}

function datos_partidas($nro_odp,$periodo,$nro_odp,$fila_odp, $moneda,$pdf)
{
// 		$this->Ln(1);
		$conexion=conexion();
		$this->SetFont('SanserifB','',7);
		$rs = query("SELECT * FROM cwpreeje where RecNoOrders = '".$nro_odp."'",$conexion);
		$totalwhile=num_rows($rs);
		$contar=1;
		$cantidad_registros=6;
		while ($cantidad_registros!=0) 
		{ 
			$conexion=conexion();
			$row_rs = fetch_array($rs);
			if($row_rs!=null){
				$cont2=$cont2+1;
				$var_sector=$row_rs['Sector'];
				$var_programa=$row_rs['Programa'];
				$var_actividad=$row_rs['Actividad'];
				$var_partida=$row_rs['Partida'];
				$var_monto3=$row_rs['Monto'];
				$var_ordinal=$row_rs['ordinal'];
			
			


				// llamado para hacer multilinea sin que haga salto de linea
				$this->SetWidths(array(5,18,18,18,20,18,18,20,27,20,20));
				$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));
				$this->Setceldas(array(0,0,0,0,0,0,0,0,0,0,0));
				$this->Setancho(array(3,3,3,3,3,3,3,3,3,3,3));
				
				list($partida,$generica,$especifica,$subespecifica,$auxiliar)=explode(".",$var_partida);
				
				
				$this->Row(array('',date('Y'),$var_sector,$var_programa,'0',$var_actividad,$partida.'.'.$generica,$especifica,$subespecifica,$auxiliar,number_format($var_monto3,2,',','.')));
				
				
			}
			$cantidad_registros-=1;

			
		}//fin del while

	
}





}


//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AddFont('Sanserif','','TEACPSS_.php');
$pdf->AddFont('SanserifB','','TEACPSSB.php');
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
	$pdf->imprimir_datos($nro_odp,$fila_odp, $moneda,$pdf,$fila_odp['forma_pago']);
	

	$pdf->totales($fila_odp,$moneda);
	$pdf->datos_partidas($nro_odp,$periodo,$nro_odp,$fila_odp, $moneda,$pdf);
	$pdf->odp=$fila_odp;






$pdf->Output();
?>