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
var $sector;
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
     	$this->Image($var_imagen_izq,8,6,25,25);

     	$this->Cell(300,7,utf8_decode($var_encabezado1),0,1,"C");
     	$this->Image($var_imagen_der,300,6,25,25);
     	$this->Cell(300,7,utf8_decode($var_encabezado2),0,1,"C");
     	$this->Cell(300,7,utf8_decode($var_encabezado3),0,1,"C");
     	$this->Cell(300,7,utf8_decode($var_encabezado4),0,1,"C");
	
	$date1=date('d/m/Y');
	$date2=date('h:i a');
	$this->SetFont("Arial","B",10);
		
	$this->Cell(300,5,utf8_decode('CREDITOS PRESUPUESTARIOS DEL PROGRAMA Y SUS ACTIVIDADES'),0,0,'C');
	$this->Cell(30,5,'FECHA: '.$date1,0,1,'R');
	$this->Cell(300,5,utf8_decode('A NIVEL DE PARTIDAS Y SUBPARTIDAS (BOLIVARES)'),0,0,'C');
	$this->Cell(30,5,'HORA: '.$date2,0,1,'R');
	$this->Cell(300,5,utf8_decode('Hasta la Fecha :'.date('d/m/Y')),0,1,'C');
       $this->Ln(5);
    

	

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


function imprimir_tabla ($pdf,$nivel)
{
	$cantidad_registros=23;
	$conexion=conexion();
	//echo "2";
	$consulta = "SELECT * FROM cwprecue as P inner join cwprepar as C on P.CodCue=C.Codigo  order by Sector,Programa,Actividad,Codigo ";
	$resultado = query($consulta, $conexion);
	
	
        $rs = query($consulta, $conexion);
	
	$total= num_rows($rs);

	$this->SetFont('Arial','B',10);
	
	//Cabecera
	$this->Cell(30,7,'Partida',1,0,'C');	
	$this->Cell(82,7,utf8_decode('Denominación'),1,0,'C');
	$this->Cell(28,7,'Credito Presup.',1,0,'C');
	$this->Cell(28,7,'Aumentos',1,0,'C');
	$this->Cell(28,7,'Disminuciones',1,0,'C');
	$this->Cell(28,7,'Actualizado',1,0,'C');
	$this->Cell(28,7,'Comprometido',1,0,'C');
	$this->Cell(28,7,'Causado',1,0,'C');
	$this->Cell(28,7,'Pagado',1,0,'C');
	$this->Cell(28,7,'Disponible',1,1,'C');
	$this->Ln(2);
	
			
	//Datos

	$cont=1;
	$contar=1;
	$pagina=1;
	$totalfinal=0;
	$secIn='';
	$proIn='';
	$actIn='';
	while ($total>=$contar)
	{
		$fila = fetch_array($rs);
		if($fila[Sector]!=$secIn || $fila[Programa]!=$proIn || $fila[Actividad]!=$actIn){

			if($secIn!=''){
				$this->SetFont('Arial','B',10);
				$this->SetWidths(array(110,28,28,28,28,28,28,28,28,28));
				$this->Setancho(array(5,5,5,5,5,5,5,5,5,5));
				$this->SetAligns(array('C','R','R','R','R','R','R','R','R','R'));
				$this->Row(array('TOTAL PRESUPUESTO:',$totMontoInicialspa,$totMontoAumentospa,$totMontoDisminucionspa,$totMontoActualizadospa,$totMontoComprometidospa,$totMontoCausadospa,$totMontoPagadospa,$totMontoDisponiblespa));
				$this->Ln(300);
				$cont=1;
				$cantidad_registros=23;

			}
			$conexion=conexion();
			$consulta="SELECT * FROM cwsector WHERE Sec='$fila[Sector]'";
			$resultadoSec=query($consulta,$conexion);
			$fetchSec=fetch_array($resultadoSec);
			
			$consulta="SELECT * FROM cwprogra WHERE Programa='$fila[Programa]' AND RecNoSec='$fetchSec[RecNo]'";
			$resultadoPro=query($consulta,$conexion);
			$fetchPro=fetch_array($resultadoPro);
			
			
			$consulta="SELECT * FROM cwpreact WHERE RecNoPro='$fetchPro[RecNo]' AND Obr='$fila[Actividad]'";
			$resultadoAct=query($consulta,$conexion);
			$fetchAct=fetch_array($resultadoAct);
			$this->SetFont('Arial','B',10);
			$this->Cell(188,5,'Sector : '.$fila[Sector] .' '.utf8_decode($fetchSec['Denominacion']),0,1);
			$this->Cell(188,5,'Programa : '.$fila[Programa].' '.utf8_decode($fetchPro['Denominacion']),0,1);
			$this->Cell(188,5,'Actividad : '.$fila[Actividad].' '.$fetchAct['Denominacion'],0,1);
			$secIn=$fila[Sector];$proIn=$fila[Programa];$actIn=$fila['Actividad'];
			$cont+=3;
			$this->SetFont('Arial','',10);
			$totMontoInicialspa=0;
			$totMontoAumentospa=0;
			$totMontoDisminucionspa=0;
			$totMontoActualizadospa=0;
			$totMontoComprometidospa=0;
			$totMontoCausadospa=0;
			$totMontoPagadospa=0;
			$totMontoDisponiblespa=0;
		}

		$conexion=conexion();
		$consulta2 = "SELECT SUM(Inicial) as Inicial,SUM(Monto) as Monto,SUM(aumento ) as aumento,SUM(disminucion) as disminucion,SUM(AcuCom) as comp, SUM(AcuCau) as causado, SUM(AcuPag) as pagado, SUM(Dispo) as dispo FROM cwprepar WHERE Codigo = '".$fila['CodCue']."' and Sector='$fila[Sector]' and Programa='$fila[Programa]' and Actividad='$fila[Actividad]' ";
		$resultado2 = query($consulta2,$conexion);
		$fetch = fetch_array($resultado2);
		

		if($fetch['Monto']!=0)
		{
			
			$codigo = $fila['CodCue'];
			$conexion=conexion();
			$montoInicial = number_format($fetch['Inicial'],2,',','.');
			$montoActualizado = number_format($fetch['Monto'],2,',','.');
			$montoComprometido = number_format($fetch['comp'],2,',','.');
			$montoCausado = number_format($fetch['causado'],2,',','.');
			$montoPagado = number_format($fetch['pagado'],2,',','.');
			$montoDisponible = number_format($fetch['dispo'],2,',','.');
			$descripcion = utf8_encode($fila['Denominacion']);
			$aumentos = number_format($fetch['aumento'],2,',','.');
			$disminuciones = number_format($fetch['disminucion'],2,',','.');
			if(strlen($fila['CodCue'])==5){

				

				$totMontoIni+=$fetch['Inicial'];
				$totMontoInicial = number_format($totMontoIni,2,',','.');//Monto Inicial
				$totAumento+=$fetch['aumento'];
				$totMontoAumento = number_format($totAumento,2,',','.'); //Monto Aumento
				
				$totDismi+=$fetch['disminucion'];
				$totMontoDisminucion = number_format($totDismi,2,',','.'); //Monto Aumento
	
				$totMontoAct+=$fetch['Monto'];
				$totMontoActualizado = number_format($totMontoAct,2,',','.');//Monto
	
				$totMontoCompro+=$fetch['comp'];
				$totMontoComprometido = number_format($totMontoCompro,2,',','.');//Monto Comprometido
				$totMontoCau+=$fetch['causado'];
				$totMontoCausado = number_format($totMontoCau,2,',','.');//Monto Causado
				$totMontoPaga+=$fetch['pagado'];
				$totMontoPagado = number_format($totMontoPaga,2,',','.');//Monto Pagado
				$totDispo+=$fetch['dispo'];
				$totMontoDisponible = number_format($totDispo,2,',','.');//Monto Disponible

				$totMontoInicialspa+=$fetch['Inicial'];
				$totMontoAumentospa+=$fetch['aumento'];
				$totMontoDisminucionspa+=$fetch['disminucion'];
				$totMontoActualizadospa+=$fetch['Monto'];
				$totMontoComprometidospa+=$fetch['comp'];
				$totMontoCausadospa+=$fetch['causado'];
				$totMontoPagadospa+=$fetch['pagado'];
				$totMontoDisponiblespa+=$fetch['dispo'];


			}
			$lineas=strlen($descripcion)/50;
			//$this->Cell(100,5,$lineas,0,1);
			if($lineas>1){
				$cantidad_registros-=round($lineas);
				
				if($cont>=$cantidad_registros){
					$this->Ln(200);
					//$pagina++;
					//$this->Cell(100,5,$cont.' -'.$cantidad_registros,0,1);
					//Cabecera
					$this->Cell(30,7,'Partida',1,0,'C');
					$this->Cell(82,7,utf8_decode('Denominación'),1,0,'C');
					$this->Cell(28,7,'Credito Presup.',1,0,'C');
					$this->Cell(28,7,'Aumentos',1,0,'C');
					$this->Cell(28,7,'Disminuciones',1,0,'C');
					$this->Cell(28,7,'Actualizado',1,0,'C');
					$this->Cell(28,7,'Comprometido',1,0,'C');
					$this->Cell(28,7,'Causado',1,0,'C');
					$this->Cell(28,7,'Pagado',1,0,'C');
					$this->Cell(28,7,'Disponible',1,1,'C');
					$this->Ln(2);			
					$cont=1;
					$cantidad_registros=23;
				}
				
			}
			// llamado para hacer multilinea sin que haga salto de linea
			$this->SetFont('Arial','I',10);
			$this->SetWidths(array(30,82,28,28,28,28,28,28,28,28));
			$this->Setancho(array(5,5,5,5,5,5,5,5,5,5));
			$this->SetAligns(array('L','L','R','R','R','R','R','R','R','R'));
			if($fila[Tipocta]==1)
				$this->SetCeldas(array('B','B','B','B','B','B','B','B','B','B'));
			else
				$this->SetCeldas(array(0,0,0,0,0,0,0,0,0,0));
		
			$this->Row(array($codigo,utf8_decode($descripcion),$montoInicial,$aumentos,$disminuciones,$montoActualizado,$montoComprometido,$montoCausado,$montoPagado,$montoDisponible));

			/*if($fila[Ordinal]=='SI' and strlen($codigo)==13){
				$this->SetFont('Arial','I',10);
				$conexion=conexion();
				$conOr = "SELECT * FROM ordinales WHERE codigo='".$codigo."' and sec='$fila[Sector]' and pro='$fila[Programa]' and obr=$fila[Actividad] ";
				$qOr=query($conOr,$conexion);
				while(($filaOr=fetch_array($qOr))){
					$lineasOr=strlen($filaOr[des_ord])/40;
					if($lineasOr>1){
						$cantidad_registros-=round($lineas);
						$this->SetFont('Arial','I',8);
						if($cont>=$cantidad_registros){
							$this->Ln(200);
							$this->Cell(30,7,'Partida',1,0,'C');
							$this->Cell(82,7,utf8_decode('Denominación'),1,0,'C');
							$this->Cell(28,7,'Credito Presup.',1,0,'C');
							$this->Cell(28,7,'Aumentos',1,0,'C');
							$this->Cell(28,7,'Disminuciones',1,0,'C');
							$this->Cell(28,7,'Actualizado',1,0,'C');
							$this->Cell(28,7,'Comprometido',1,0,'C');
							$this->Cell(28,7,'Causado',1,0,'C');
							$this->Cell(28,7,'Pagado',1,0,'C');
							$this->Cell(28,7,'Disponible',1,1,'C');
							$this->Ln(2);			
							$cont=1;
							$cantidad_registros=23;
						}
					}
					
					$this->Row(array($filaOr[ordinal],utf8_decode($filaOr[des_ord]),$filaOr[monto],$filaOr[aumento],$filaOr[disminucion],$filaOr[montoact],$filaOr[compromiso],$filaOr[causado],$filaOr[pagado],$filaOr[disponible]));
					$cont++;

				}
			}*/
			
			$cont++;
		}
	// fin
	//echo $cont;
		
		if($cont>=$cantidad_registros)
		{	
				//$this->Footer();
				$this->Ln(100);
				//$pagina++;
				//$this->Cell(100,5,$cont.' -'.$cantidad_registros,0,1);
				//Cabecera
				$this->Cell(30,7,'Partida',1,0,'C');
				$this->Cell(82,7,utf8_decode('Denominación'),1,0,'C');
				$this->Cell(28,7,'Credito Presup.',1,0,'C');
				$this->Cell(28,7,'Aumentos',1,0,'C');
				$this->Cell(28,7,'Disminuciones',1,0,'C');
				$this->Cell(28,7,'Actualizado',1,0,'C');
				$this->Cell(28,7,'Comprometido',1,0,'C');
				$this->Cell(28,7,'Causado',1,0,'C');
				$this->Cell(28,7,'Pagado',1,0,'C');
				$this->Cell(28,7,'Disponible',1,1,'C');
				$this->Ln(2);			
				$cont=1;
				$cantidad_registros=23;
			
		}
		//echo $contar;
		$contar++;
		
	}
	$this->Ln(10);	

	$this->SetFont('Arial','B',10);
	$this->SetWidths(array(110,28,28,28,28,28,28,28,28,28));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5,5));
	$this->SetAligns(array('C','R','R','R','R','R','R','R','R','R'));
	$this->Row(array('TOTAL PRESUPUESTO:',$totMontoInicial,$totMontoAumento,$totMontoDisminucion,$totMontoActualizado,$totMontoComprometido,$totMontoCausado,$totMontoPagado,$totMontoDisponible));
	$this->Ln();
}
function Footer()
{
	$this->SetY(-15);
	$this->SetFont('Arial','I',8);
    	$this->Cell(0,5,'Elaborado Por: '.$_SESSION['nombre'],0,1,'L');
	$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');

}

}


//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);


$nivel = $_GET['nivel'];

$pdf->imprimir_tabla($pdf,$nivel);

$pdf->Output();
?>
