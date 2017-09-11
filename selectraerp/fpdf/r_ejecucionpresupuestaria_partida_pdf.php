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
	$this->Cell(300,5,utf8_decode('Ejecución Presupuestaria hasta el :'.date('d/m/Y')),0,1,'C');

	$sector = $_GET['sector'];
	$programa = $_GET['programa'];
	$actividad = $_GET['actividad'];
	$conexion=conexion();

	$conSector = "SELECT * FROM cwsector WHERE RecNo='".$sector."'";
	$resSector = query($conSector, $conexion);
	$filaSector = fetch_array($resSector);
	$this->sector = $filaSector['Sec'];
	$desSector = $filaSector['Denominacion'];
	
	$conPrograma = "SELECT * FROM cwprogra WHERE RecNo='".$programa."'";
	$resPrograma = query($conPrograma, $conexion);
	$filaPrograma = fetch_array($resPrograma);
	$this->programa = $filaPrograma['Programa'];
	$desPrograma = $filaPrograma['Denominacion'];
	
	$conActividad = "SELECT * FROM cwpreact WHERE RecNo='".$actividad."'";
	$resActividad = query($conActividad, $conexion);
	$filaActividad = fetch_array($resActividad);
	$this->actividad = $filaActividad['Obr'];
	$desActividad = $filaActividad['Denominacion'];
	if($sector!=0){
		$this->Cell(300,5,utf8_decode('Sector: '.$desSector),0,1,'L');
		$this->Cell(300,5,utf8_decode('Programa: '.$desPrograma),0,1,'L');
		$this->Cell(300,5,utf8_decode('Actividad: '.$desActividad),0,1,'L');
	}

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
	$codSector=$this->sector;
	$codPrograma=$this->programa;
	$codActividad=$this->actividad;
	$fecha2 = $_GET['fechah'];
	$fecha = fecha_sql($_GET['fechah']);
	if($codSector==0){
		$consulta = "SELECT * FROM cwprepar LEFT JOIN cwprecue ON cwprepar.Codigo=cwprecue.CodCue WHERE cwprecue.Tipocta='".$nivel."' ORDER BY cwprepar.Codigo";
	}else if ($codPrograma==0){
		$consulta = "SELECT * FROM cwprepar LEFT JOIN cwprecue ON cwprepar.Codigo=cwprecue.CodCue WHERE cwprecue.Tipocta='".$nivel."' AND cwprepar.Sector='".$codSector."'  ORDER BY cwprepar.Codigo";
	}else if ($codActividad==0){
		$consulta = "SELECT * FROM cwprepar LEFT JOIN cwprecue ON cwprepar.Codigo=cwprecue.CodCue WHERE cwprecue.Tipocta='".$nivel."' AND cwprepar.Sector='".$codSector."' AND cwprepar.Programa='".$codPrograma."'  ORDER BY cwprepar.Codigo";
	}else{
		$consulta = "SELECT * FROM cwprepar LEFT JOIN cwprecue ON cwprepar.Codigo=cwprecue.CodCue WHERE cwprecue.Tipocta='".$nivel."' AND cwprepar.Sector='".$codSector."' AND cwprepar.Programa='".$codPrograma."' AND cwprepar.Actividad='".$codActividad."' ORDER BY cwprepar.Codigo";
	}
	
        $rs = query($consulta, $conexion);
	
	$total= num_rows($rs);

	$this->SetFont('Arial','B',10);
	
	//Cabecera
	$this->Cell(15,7,'Progr.',1,0,'C');
	$this->Cell(30,7,'Partida',1,0,'C');
	$this->Cell(70,7,utf8_decode('Descripción'),1,0,'C');
	$this->Cell(28,7,'Monto Inicial',1,0,'C');
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
	if($codSector==0)
		$todos=1;
	while ($total>=$contar)
	{
		$fila = fetch_array($rs);
		$codigo = $fila['Codigo'];
		$codigo2 = $codigo[0].$codigo[1].$codigo[2].$codigo[3].$codigo[4];
		$conexion=conexion();
		$montoIni = $fila['Inicial'];
		
		if($todos==1){
			$codSector=$fila['Sector'];
			$codPrograma=$fila['Programa'];
			$codActividad=$fila['Actividad'];
		}
		if ($nivel == 1)
		{
			
			$consulta3 = "SELECT SUM(Monto) as mto FROM cwpreejc WHERE SUBSTRING(Partida,-13,5)='".$codigo2."' AND Fecha BETWEEN '".$ano."' AND '".$fecha."'  AND Sector = '".$codSector."' AND Programa = '".$codPrograma."' AND Actividad = '".$codActividad."' AND Marca='X'";
			$resultado3 = query($consulta3, $conexion);
			$fetch3 = fetch_array($resultado3);
			

			$consulta4 = "SELECT SUM(Monto) as mto2 FROM cwpreeje WHERE SUBSTRING(Partida,-13,5) = '".$codigo2."' AND Fecha BETWEEN '".$ano."' AND '".$fecha."'  AND Sector = '".$codSector."' AND Programa = '".$codPrograma."' AND Actividad = '".$codActividad."' AND Marca='X' ";
			$resultado4 = query($consulta4, $conexion);
			$fetch4 = fetch_array($resultado4);
			
			$consulta5 = "SELECT Monto,RecNoOrders,Cheque FROM cwpreeje WHERE SUBSTRING(Partida,-13,5) = '".$codigo2."' AND Fecche BETWEEN '".$ano."' AND '".$fecha."'  AND Sector = '".$codSector."' AND Programa = '".$codPrograma."' AND Actividad = '".$codActividad."' AND Marca='X' ";
			$resultado5 = query($consulta5, $conexion);
									
			$mto_pag = 0;
			while($fetch5 = fetch_array($resultado5))
			{
				//echo $fetch5['RecNoOrders'];
				//echo '<br>';
				if ($fetch5['Cheque'])
				{
					$mto_pag = $mto_pag + $fetch5['Monto'];
				}
				else
				{
					$consulta6 = "SELECT creache FROM ordenes_pago WHERE numero_ocs = '".$fetch5['RecNoOrders']."' ";
					$resultado6 = query($consulta6, $conexion);
					$fetch6 = fetch_array($resultado6);
					if($fetch6['creache'] == "N")
					{
						$mto_pag = $mto_pag + $fetch5['Monto'];
					}
				}
				//echo "aaa";
			}
			
		}
		elseif ($nivel == 4)
		{
			$consulta3 = "SELECT SUM(Monto) as mto FROM cwpreejc WHERE Partida = '".$fila['Codigo']."' AND Fecha BETWEEN '".$ano."' AND '".$fecha."'  AND Sector = '".$codSector."' AND Programa = '".$codPrograma."' AND Actividad = '".$codActividad."' AND Marca='X' ";
			$resultado3 = query($consulta3, $conexion);
			$fetch3 = fetch_array($resultado3);

			$consulta4 = "SELECT SUM(Monto) as mto2 FROM cwpreeje WHERE Partida = '".$fila['Codigo']."' AND Fecha BETWEEN '".$ano."' AND '".$fecha."'  AND Sector = '".$codSector."' AND Programa = '".$codPrograma."' AND Actividad = '".$codActividad."' AND Marca='X' ";
			$resultado4 = query($consulta4, $conexion);
			$fetch4 = fetch_array($resultado4);
			
			
			$consulta5 = "SELECT Monto,RecNoOrders,Cheque FROM cwpreeje WHERE Partida = '".$fila['Codigo']."' AND Fecche BETWEEN '".$ano."' AND '".$fecha."'  AND Sector = '".$codSector."' AND Programa = '".$codPrograma."' AND Actividad = '".$codActividad."' AND Marca='X' ";
			$resultado5 = query($consulta5, $conexion);
									
			$mto_pag = 0;
			while($fetch5 = fetch_array($resultado5))
			{
				//echo $fetch5['RecNoOrders'];
				//echo '<br>';
				if ($fetch5['Cheque'])
				{
					$mto_pag = $mto_pag + $fetch5['Monto'];
				}
				else
				{
					$consulta6 = "SELECT creache FROM ordenes_pago WHERE numero_ocs = '".$fetch5['RecNoOrders']."' ";
					$resultado6 = query($consulta6, $conexion);
					$fetch6 = fetch_array($resultado6);
					if($fetch6['creache'] == "N")
					{
						$mto_pag = $mto_pag + $fetch5['Monto'];
					}
				}
				//echo "aaa";
			}
			
			
		}
		$montoInicial = number_format($montoIni,2,',','.');
		$montoAct = $fila['Monto'];
		$montoActualizado = number_format($montoAct,2,',','.');
		$montoCom = $fetch3['mto'];//$fila['AcuCom'];
		$montoComprometido = number_format($montoCom,2,',','.');
		$causado = $fila['AcuCau'];
		$montoCausado = number_format($causado,2,',','.');
		$pagado = $mto_pag;
		$montoPagado = number_format($pagado,2,',','.');
		$montoDispo = $fila['Dispo'];
		$montoDisponible = number_format($montoDispo,2,',','.');
		$conDes = "SELECT * FROM cwprecue WHERE CodCue='".$codigo."'";
		$resDes = query($conDes, $conexion);
		$filaDes = fetch_array($resDes);
		$descripcion = $filaDes['Denominacion'];
		$aumento = $fila['aumento'];
		$aumentos = number_format($aumento,2,',','.');
		$disminucion = $fila['disminucion'];
		$disminuciones = number_format($disminucion,2,',','.');
		
		$totMontoIni+=$montoIni;
		$totMontoInicial = number_format($totMontoIni,2,',','.');
		$totAumen+=$aumento;
		$totAumentos = number_format($totAumen,2,',','.');
		$totDismi+=$disminucion;
		$totDisminuciones = number_format($totDismi,2,',','.');
		$totMontoAct+=$montoAct;
		$totMontoActualizado = number_format($totMontoAct,2,',','.');
		$totMontoCompro+=$montoCom;
		$totMontoComprometido = number_format($totMontoCompro,2,',','.');
		$totMontoCau+=$causado;
		$totMontoCausado = number_format($totMontoCau,2,',','.');
		$totMontoPag+=$pagado;
		$totMontoPagado = number_format($totMontoPag,2,',','.');
		$totMontoDispo+=$montoDispo;
		$totMontoDisponible = number_format($totMontoDispo,2,',','.');
			

		$lineas=strlen($descripcion)/40;
		//$this->Cell(100,5,$lineas,0,1);
		if($lineas>1){
			$cantidad_registros-=round($lineas);
			if($cont>=$cantidad_registros){
				$this->Ln(200);
				//$pagina++;
				//$this->Cell(100,5,$cont.' -'.$cantidad_registros,0,1);
				//Cabecera
				$this->Cell(15,7,'Prog.',1,0,'C');
				$this->Cell(30,7,'Partida',1,0,'C');
				$this->Cell(70,7,utf8_decode('Descripción'),1,0,'C');
				$this->Cell(28,7,'Monto Inicial',1,0,'C');
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
		$this->SetWidths(array(15,30,70,28,28,28,28,28,28,28,28));
		$this->Setancho(array(5,5,5,5,5,5,5,5,5,5,5));
		$this->SetAligns(array('L','L','L','R','R','R','R','R','R','R','R'));
		$this->Row(array($codSector.$codPrograma.$codActividad,$codigo,utf8_encode($descripcion),$montoInicial,$aumentos,$disminuciones,$montoActualizado,$montoComprometido,$montoCausado,$montoPagado,$montoDisponible));
		
		$cont++;
	
	// fin
	//echo $cont;
		
		if($cont>=$cantidad_registros)
		{	
				//$this->Footer();
				$this->Ln(100);
				//$pagina++;
				//$this->Cell(100,5,$cont.' -'.$cantidad_registros,0,1);
				//Cabecera
				$this->Cell(15,7,'Prog.',1,0,'C');
				$this->Cell(30,7,'Partida',1,0,'C');
				$this->Cell(70,7,utf8_decode('Descripción'),1,0,'C');
				$this->Cell(28,7,'Monto Inicial',1,0,'C');
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
	$this->SetWidths(array(115,28,28,28,28,28,28,28,28,28));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5,5));
	$this->SetAligns(array('C','R','R','R','R','R','R','R','R','R'));
	$this->Row(array('TOTAL PRESUPUESTO:',$totMontoInicial,$totAumentos,$totDisminuciones,$totMontoActualizado,$totMontoComprometido,$totMontoCausado,$totMontoPagado,$totMontoDisponible));
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