<?
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');
require_once '../lib/config.php';
require_once '../lib/common.php';

include('../lib/numerosALetras.class.php');

class PDF extends FPDF
{



function ChequeIndustrial($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano){
	$this->SetY(-158);
	//sacar ciudad
	$Conn=conexion_conf();
	$consulta="select * from parametros";
	$rc=query($consulta,$Conn);
	$rrc=fetch_array($rc);
	$ciudad=$rrc['ciudad'];

	$this->SetFont('Arial','B',8);
	$this->Cell(45);
	$this->Cell(188,7,'',0,0);
	$this->Ln();
	$this->SetY(-144);
	$this->Cell(67);
	$this->Cell(178,7,"** Bs. ".number_format($monto,2,',','.')." **",0,0,'R');
	$this->Ln(16);
	$this->Cell(45);
	$this->Cell(75,7,'',0,0);
	$this->Cell(138,7,$nombreContribuyente,0,0,'L');
	$this->Ln(8);
	$this->Cell(45);
	$this->Cell(75,7,'',0);
	$this->MultiCell(110,7,"** ".$montoLetras." **",0,'L');
	$this->Ln(4);
	$this->Cell(100);
	$this->Cell(68,7,"        $ciudad, ".$dia." de ".mesaletras($mes),0,0);
	$this->Cell(30,7,"       ".$ano,0,0);
	$this->Ln(22);
	$this->Cell(70);
	$this->Cell(168,7,'*** NO ENDOSABLE *** CADUCA A LOS 90 DIAS ',0,0,'R');
	
	$this->Ln(15);
	$this->Cell(45);
	$this->Cell(188,7,'',0);

}
function ChequeGenerico($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano){
	$this->SetY(-158);
	//sacar ciudad
	$Conn=conexion_conf();
	$consulta="select * from parametros";
	$rc=query($consulta,$Conn);
	$rrc=fetch_array($rc);
	$ciudad=$rrc['ciudad'];

	$this->SetFont('Arial','B',8);
	$this->Cell(45);
	$this->Cell(188,7,'',0,0);
	$this->Ln();
	$this->SetY(-144);
	$this->Cell(70);
	$this->Cell(200,7,"  ** Bs. ".number_format($monto,2,',','.')." **",0,0,'R');
	$this->Ln(18);
	$this->Cell(45);
	$this->Cell(76,7,'',0,0);
	$this->Cell(137,7,"      ".$nombreContribuyente,0,0,'L');
	$this->Ln(8);
	$this->Cell(45);
	$this->Cell(78,7,'',0);
	$this->MultiCell(122,7,"       ** ".$montoLetras." **",0,'L');
	$this->Ln(5);
	$this->Cell(110);
	$this->Cell(67,7,"        $ciudad, ".$dia." de ".mesaletras($mes),0,0);
	$this->Cell(30,7,"         ".$ano,0,0);
	$this->Ln(22);
	$this->Cell(70);
	$this->Cell(168,7,'*** NO ENDOSABLE *** CADUCA A LOS 90 DIAS ',0,0,'R');
	
	$this->Ln(15);
	$this->Cell(45);
	$this->Cell(188,7,'',0);

}
function ChequeBanesco($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano){
	$this->SetY(-158);
	//sacar ciudad
	$Conn=conexion_conf();
	$consulta="select * from parametros";
	$rc=query($consulta,$Conn);
	$rrc=fetch_array($rc);
	$ciudad=$rrc['ciudad'];

	$this->SetFont('Arial','B',8);
	$this->Cell(45);
	$this->Cell(188,7,'',0,0);
	$this->Ln();
	$this->SetY(-144);
	$this->Cell(67);
	$this->Cell(178,7,"** Bs. ".number_format($monto,2,',','.')." **",0,0,'R');
	$this->Ln(16);
	$this->Cell(45);
	$this->Cell(75,7,'',0,0);
	$this->Cell(138,7,$nombreContribuyente,0,0,'L');
	$this->Ln(6);
	$this->Cell(45);
	$this->Cell(75,7,'',0);
	$this->MultiCell(110,7,"** ".$montoLetras." **",0,'L');
	$this->Ln(6);
	$this->Cell(100);
	$this->Cell(68,7,"        $ciudad, ".$dia." de ".mesaletras($mes),0,0);
	$this->Cell(30,7,"       ".$ano,0,0);
	$this->Ln(22);
	$this->Cell(70);
	$this->Cell(168,7,'*** NO ENDOSABLE *** CADUCA A LOS 90 DIAS ',0,0,'R');
	
	$this->Ln(15);
	$this->Cell(45);
	$this->Cell(188,7,'',0);

}
	
function ChequeProvincial($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano){
	$this->SetY(-158);
	//sacar ciudad
	$Conn=conexion_conf();
	$consulta="select * from parametros";
	$rc=query($consulta,$Conn);
	$rrc=fetch_array($rc);
	$ciudad=$rrc['ciudad'];

	$this->SetFont('Arial','B',8);
	$this->Cell(45);
	$this->Cell(188,7,'',0,0);
	$this->Ln();
	$this->SetY(-144);
	$this->Cell(67);
	$this->Cell(195,7,"    ** Bs. ".number_format($monto,2,',','.')." **",0,0,'R');
	$this->Ln(19);
	$this->Cell(45);
	$this->Cell(75,7,'',0,0);
	$this->Cell(138,7,$nombreContribuyente,0,0,'L');
	$this->Ln(7);
	$this->Cell(45);
	$this->Cell(75,7,'',0);
	$this->MultiCell(110,7,"** ".$montoLetras." **",0,'L');
	$this->Ln(7);
	$this->Cell(100);
	$this->Cell(68,7,"        $ciudad, ".$dia." de ".mesaletras($mes),0,0);
	$this->Cell(30,7,"  ".$ano,0,0);
	$this->Ln(22);
	$this->Cell(70);
	$this->Cell(168,7,'*** NO ENDOSABLE *** CADUCA A LOS 90 DIAS ',0,0,'R');
	
	$this->Ln(15);
	$this->Cell(45);
	$this->Cell(188,7,'',0);

}
}


//Creación del objeto de la clase heredada

$pdf=new PDF('L','mm','Letter');
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',16);
//$pdf->Cell(40,10,'¡Hola, Mundo!');


$codigo = @$_GET['codigo'];
$chequera=@$_GET["chequera"];
$cuenta=@$_GET["cuenta"];
$banco=@$_GET["banco"];

	$conexion = conexion();

	$cosuls="update cheques set status='Im' WHERE banco='".$banco."' AND cheque='".$codigo."' AND chequera='".$chequera."'";
	$resuls = query($cosuls, $conexion);
	
	$consulta1 = "SELECT * FROM ordenes_pago WHERE cheque='".$codigo."' and chequera='".$chequera."'";
	$resultado1 = query($consulta1, $conexion);
	$fila1 = fetch_array($resultado1);

	$nroFactura = $fila1['no_fac'];
	$odp = $fila1['numero_odp'];
	$fechaFactura = $fila1['fec_fac'];
	$fechaCheque = $fila1['fecche'];
	$rif = $fila1['rif'];
	$persona = $fila1['persona'];

	$retencionIVA = $fila1['monto_retiva'];
		
	$consulta3="select * from cheques where cheque='".$codigo."' and chequera='".$chequera."'";
	$resultado3 = query($consulta3, $conexion);
	$fila3=fetch_array($resultado3);
	$nombreContribuyente = $fila3['beneficiario'];
	$fecha=$fila3['fecha'];
	$temp=split('-',$fecha);
	$dia=$temp[2];
	$mes=$temp[1];
	$ano=$temp[0];
	$monto=$fila3['monto'];
	$concepto = $fila3['concepto'];
	$nroCuenta = $fila3['cuenta'];
	
	$consulta5 = "SELECT * FROM facturas WHERE no_fac='".$nroFactura."'";
	$resultado5 = query($consulta5, $conexion);
	$fila5 = fetch_array($resultado5);
	$objetoRetenido = $fila5['montobase'];
	$porcentaje = $fila5['porcentajeretenido'];
	$montoRetenido = $fila5['montoretenido'];


	$n = new numerosALetras();
	$montoLetras=$n->convertir($monto);
	
	if($banco>=1 && $banco<=6){
		
		$pdf->ChequeIndustrial($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano);
	}elseif($banco>=7 && $banco<=11){
		$pdf->ChequeBanesco($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano);
	}elseif($banco==16){
		$pdf->ChequeProvincial($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano);
	}else{
		$pdf->ChequeGenerico($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano);
	}


$pdf->Output();

?> 