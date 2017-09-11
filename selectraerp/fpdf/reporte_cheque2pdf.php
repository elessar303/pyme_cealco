<?
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdf.php';
include('../lib/numerosALetras.class.php');

class PDF extends FPDF
{

VAR $pdff;
var $usuario;


function Cheque($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano){


	//sacar ciudad
	$Conn=conexion_conf();
	$consulta="select * from parametros";
	$rc=query($consulta,$Conn);
	$rrc=fetch_array($rc);
	$ciudad=$rrc['ciudad'];

	$this->SetFont('Arial','B',8);
	$this->Cell(188,7,'','',0);
	$this->Ln();
	$this->Cell(178,7,number_format($monto,2,',','.'),0,0,'R');
	$this->Ln(20);
	$this->Cell(50,7,'',0,0);
	$this->Cell(138,7,$nombreContribuyente,0,0,'L');
	$this->Ln(8);
	$this->Cell(50,7,'',0);
	$this->MultiCell(100,7,$montoLetras,0,'L');
	$this->Ln(7);
	$this->Cell(80,7,"        $ciudad, ".$dia." de ".mesaletras($mes),0,0);
	$this->Cell(108,7,'        '.$ano,0,0);
	$this->Ln(10);
	$this->Cell(168,7,'*** NO ENDOSABLE ***',0,0,'C');
	$this->Ln();
	$this->Cell(168,7,' CADUCA A LOS 90 DIAS ',0,0,'C');
	$this->Ln(15);
	$this->Cell(188,7,'','');

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

function datos_cheque($fila3,$oc,$odp,$fecha,$fA){
	$conexion = conexion();
	//Banco
	$idbanco=$fila3['banco'];
	$consulta1 = "SELECT * FROM bancos WHERE codigo='".$idbanco."'";
	$resultado1 = query($consulta1, $conexion);
	$fila1 = fetch_array($resultado1);
	$nombreBanco=$fila1['descripcion'];
	$cuenta=$fila1['cuenta'];
	$numero=$fila3['cheque'];
	$fechaemi=$fila3['fecha'];
	$concepto=$fila3['concepto'];
	
	$this->Ln();
	$this->SetFont('Arial','B',8);
	$this->Cell(60,5,'Banco: ','LTR',0);
	$this->Cell(60,5,utf8_decode('Nº de Cuenta : '),'LTR',0);
	$this->Cell(40,5,utf8_decode('Nº de Cheque : '),'LTR',0);
	$this->Cell(28,5,utf8_decode('Fecha de Emisión:  '),'LTR',0);
	$this->Ln();
	$this->SetFont('Arial','I',8);
	// llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(60,60,40,28));
	$this->SetAligns(array('C','C','C','C'));
        $this->Setceldas(array('LRB','LRB','LRB','LRB'));
	$this->Setancho(array(5,5,5,5));
        $this->Row(array($nombreBanco,$cuenta,$numero,$fechaemi));
	//fin
	
	$this->SetFont('Arial','B',8);
	$this->Cell(188,5,'SOLICITUD DE PAGO ',1,0,'C');
	$this->Ln();
	$this->Cell(120,5,'Unidad de Origen ',1,0,'C');
	$this->Cell(68,5,'Div. Egresos Administrativos ',1,0,'C');
	$this->Ln();
	$this->SetFont('Arial','I',8);
	$this->Cell(40,5,utf8_decode('Nº Orden : ').$oc,1,0,'L');
	$this->Cell(40,5,utf8_decode('Nº Solicitud : ').$odp,1,0,'L');
	
	list($anio,$mes,$dia)=explode("-",$fecha);
   	$fecha= $dia."/".$mes."/".$anio; 
	
	$this->Cell(40,5,'Fecha : '.$fecha,1,0,'L');

	list($anio,$mes,$dia)=explode("-",$fA);
   	$fA= $dia."/".$mes."/".$anio;

	$this->Cell(68,5,$fA,1,0,'C');
	$this->Ln();
	$this->SetFont('Arial','I',8);
	$this->MultiCell(188,10,'CONCEPTO: '.utf8_decode($concepto),1,'L');
	
	
	

}
//Pie de página
function Footer()
{
   
    	//Posición: a  cm del final
   	$this->SetY(-65);

	$bool=validar_firma("TESORERIA");
	if ($bool==true){
		firma_dinamica("TESORERIA",$this->pdff,8,15);
	}else{
		cheque($this->pdff);
	}
	
 
	$this->SetWidths(array(63,63,62));
	$this->SetAligns(array('C','C','C'));
        $this->Setceldas(array('1','1','1'));
	$this->Setancho(array(5,5,5));

	$this->SetFont('Arial','B',8);
    	$this->Cell(188,5,'Beneficiario',1,0,'C');
	$this->Ln();
	
	$this->SetFont('Arial','I',8);
	$this->SetAligns(array('L','L','L'));
	$this->Setceldas(array('LT','LT','LTR'));
	$this->Setancho(array(7,7,7));
	$this->Row(array('Nombre: ','R.I.F. o C.I. : ',''));
	$this->SetFont('Arial','I',8);
	$this->Cell(63,5,'','LB',0);
	$this->Cell(63,5,'','LB',0);
	$this->Cell(62,5,'Firma:________________________________','LBR',0);
	$this->Ln();
	

   	$this->SetFont('Arial','I',8);
    	$this->Cell(0,5,'Elaborado Por: '.$this->usuario,0,1,'L');
	$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');
	
    
}

function reg_contable($codigo,$fila3,$pdf){
	$indices=array("4","5","6","7");
	$banco=@$_GET["banco"];
	$chequera=@$_GET["chequera"];
	$cuenta=@$_GET["cuenta"];
	
	$this->pdff=$pdf;
	
	$conexion=conexion();
	$consulta= "select * from bauche_det where cheque='".$codigo."' and banco='".$banco."' and chequera='".$chequera."' and ctaban='".$cuenta."'";
	
	$resultado=query($consulta,$conexion);
	
	$consu= "select * from bauche_det where cheque='".$codigo."' and banco='".$banco."' and chequera='".$chequera."' and ctaban='".$cuenta."'";
	$resul=query($consu,$conexion);
	
	$consulta2 = "SELECT * FROM bancos WHERE codigo='".$fila3['banco']."'";
	$resultado2 = query($consulta2, $conexion);
	$fila2 = fetch_array($resultado2);
	$banco = $fila2['descripcion'];
	
	$cantidad_registros=13;
	while($filabd=fetch_array($resul))
	{
		$debitos=$debitos+$filabd['debitos'];
		$creditos=$creditos+$filabd['creditos'];
	}
	
	if(mysql_num_rows($resultado)<=0)
	{	$this->Cell(188,7,'No existen registro con la busqueda especificada',0,0);
		
	}else{
		$i=0; 
		//cabecera
			
			$this->SetFont('Arial','B',8);
			$this->Cell(40,7,'Codigo Contable',1,0,'C');
			$this->Cell(68,7,utf8_decode('Descripción'),1,0,'C');
			$this->Cell(40,7,'Debito',1,0,'C');
			$this->Cell(40,7,'Credito',1,0,'C');
			$this->Ln();
		$totalwhile=num_rows($resultado);
		$contar=1;
		while($totalwhile>=$contar){
			$fila=mysql_fetch_array($resultado);
		$i++;
			$cuenta=$fila['cuenta'];
			$descue=utf8_decode($fila['descue']);
			$debito=$fila['debitos'];
			$credito=$fila['creditos'];

			
			// llamado para hacer multilinea sin que haga salto de linea
			$this->SetFont('Arial','I',8);
			$this->SetWidths(array(40,68,40,40));
			$this->SetAligns(array('L','L','C','C'));
			$this->Setceldas(array(0,0,0,0));
			$this->Setancho(array(5,5,5,5));
			if($debito!=Null){
				$this->Row(array($cuenta,$descue,number_format($debito, 2, ',', '.'),''));
			}else{
			   	$this->Row(array($cuenta,$descue,'',number_format($credito, 2, ',', '.')));
				
			}
			//fin
			if($cont==$cantidad_registros){
					
				//cabecera
				
				
				$this->Ln(80);
				$pdf->datos_cheque($fila3);
				$this->SetFont('Arial','B',8);
				$this->Cell(40,7,'Codigo Contable',1,0,'C');
				$this->Cell(68,7,utf8_decode('Descripción'),1,0,'C');
				$this->Cell(40,7,'Debito',1,0,'C');
				$this->Cell(40,7,'Credito',1,0,'C');
				$this->Ln();
				
				$this->SetFont('Arial','I',8);
				$cont=1;
			}else
			{
			$cont++;
			//echo $cont;
			}
		$contar++;
		}
		$this->SetFont('Arial','B',10);
		$this->Cell(40,7,'',0,0);
		$this->Cell(68,7,'Total:  ',0,0,'R');
		$this->SetFont('Arial','B',10);	
		$this->Cell(40,7,number_format($debitos, 2, ',', '.'),0,0,'C');
		$this->Cell(40,7,number_format($creditos, 2, ',', '.'),0,0,'C');
		$this->Ln();
	}
}
}


//Creación del objeto de la clase heredada

$pdf=new PDF();
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
	$oc=$fila1['numero_ocs'];
	$fecha=$fila1['fecha'];
	$fA=$fila1['fechaAutorizada'];


	$retencionIVA = $fila1['monto_retiva'];
		
	$consulta3="select * from cheques where cheque='".$codigo."' and chequera='".$chequera."'";
	$resultado3 = query($consulta3, $conexion);
	$fila3=fetch_array($resultado3);
	$nombreContribuyente = $fila3['beneficiario'];
	$pdf->usuario=$fila3['log_usr'];
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
	
	$pdf->Cheque($monto,$nombreContribuyente,$montoLetras,$dia,$mes,$ano);
	$pdf->datos_cheque($fila3,$oc,$odp,$fecha,$fA);
	$pdf->reg_contable($codigo,$fila3,$pdf);


	//cerrar_conexion($conexion);

$pdf->Output();

?>