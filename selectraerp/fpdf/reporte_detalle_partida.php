<?php
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');


require_once '../lib/pdfcommon.php';
require_once '../lib/common.php';
require_once '../lib/pdf.php';
//include ("../selectra/header.php");

$cantidad_registros=22;

$sector1=$_GET['sector'];
$programa1=$_GET['programa'];
$actividad1=$_GET['actividad'];
$partida=$_GET['partida'];
$conexion=conexion();
$consulta="SELECT Sec FROM cwsector WHERE RecNo='$sector1'";
$resultadoSec=query($consulta,$conexion);
$fetchSec=fetch_array($resultadoSec);
$_GET['sector']=$sector=$fetchSec[Sec];
$consulta="SELECT Programa FROM cwprogra WHERE RecNo='$programa1' AND RecNoSec='$sector1'";
$resultadoPro=query($consulta,$conexion);
$fetchPro=fetch_array($resultadoPro);
$_GET['programa']=$programa=$fetchPro[Programa];

$consulta="SELECT Obr FROM cwpreact WHERE RecNoPro='$programa1' AND RecNo='$actividad1'";
$resultadoAct=query($consulta,$conexion);
$fetchAct=fetch_array($resultadoAct);
$_GET['actividad']=$actividad=$fetchAct[Obr];

class PDF extends FPDF
{
var $usuario;
var $tipo;
var $concep;
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
	$this->SetFont('Arial','B',10);
	
	$conexion=conexion();
	$partida=$_GET['partida'];
	$part=explode('.',$partida);
	$consulta="SELECT Denominacion FROM cwprecue where CodCue='$partida'";
	$resultP=query($consulta,$conexion);
	$fetchP=fetch_array($resultP);
	$this->Image($var_imagen_izq,10,8,23);
	$this->Ln();
	$this->Cell(70);
	$this->Cell(50,8,utf8_decode(""),0,0,"C");
	$this->SetFont('Arial','B',7);
	$this->Cell(10);
	$this->Cell(28,8,"",0,0,"R");
	$this->Cell(12,8,"Sector",'LTBR',0,"C");
	$this->Cell(12,8,"Programa",'TBR',0,"C");
	$this->Cell(12,8,"Actividad",'TBR',0,"C");
	$this->Cell(12,8,"Partida",'TBR',0,"C");
	$this->Cell(39,6,"Sub-partida",'TBR',0,"C");
	$this->Ln(6);
	$this->Cell(35);
	$this->SetFont('Arial','B',9);    
	$this->Cell(120,8,'',0,0,"L");
	$this->SetFont('Arial','B',7);
	$this->Cell(3,8,"",0,0,"R");
	$this->Cell(12,8,"",'LR',0,"C");
	$this->Cell(12,8,"",'R',0,"C");
	$this->Cell(12,8,"",'R',0,"C");
	$this->Cell(12,8,"",'R',0,"C");
	$this->Cell(12,6,"Gen.",'BR',0,"C");
	$this->Cell(12,6,"Esp.",'BR',0,"C");
	$this->Cell(15,6,"Sub-esp.",'BR',0,"C");
	$this->Ln(6);
	$this->SetFont('Arial','B',11);  
	$this->Cell(158,8,utf8_decode("                  REGISTRO DE LA EJECUCION FINANCIERA DEL PRESUPUESTO"),0,0,"C");
	$this->SetFont('Arial','B',7);  
	$this->Cell(12,8,$_GET[sector],'LBR',0,"C");
	$this->Cell(12,8,$_GET[programa],'BR',0,"C");
	$this->Cell(12,8,$_GET[actividad],'BR',0,"C");
	$this->Cell(12,8,"$part[0].$part[1]",'BR',0,"C");
	$this->Cell(12,8,"$part[2]",'BR',0,"C");
	$this->Cell(12,8,"$part[3]",'BR',0,"C");
	$this->Cell(15,8,"$part[4]",'BR',1,"C");

	$consulta="SELECT * FROM cwprepar WHERE Sector='$_GET[sector]' and Programa='$_GET[programa]' and Actividad='$_GET[actividad]' and Codigo='$_GET[partida]'";
	$resultado= query($consulta,$conexion);
	$fetch=fetch_array($resultado);
	//$this->Ln(6);
	$this->SetFont('Arial','B',8);
	$this->Cell(180,5,"                                   ".utf8_decode("Año: ".date("Y")." Partida: ").$fetchP['Denominacion'],0,1,"L");
	//$this->Ln(6);
	$this->SetFont('Arial','B',7);
	$this->Cell(150,5,"Inicial: ".number_format($fetch[Inicial],2,',','.')." Aumentos: ".number_format($fetch[aumento],2,',','.')." Disminuciones: ".number_format($fetch[disminucion],2,',','.')." Actualizado: ".number_format($fetch[Monto],2,',','.')." Acum. Comp.: ".number_format($fetch[AcuCom],2,',','.')." Acum. Cau.: ".number_format($fetch[AcuCau],2,',','.')." Acum. Pag.: ".number_format($fetch[AcuPag],2,',','.')." Disp.: ".number_format($fetch[Dispo],2,',','.'),0,1,"L");
	
}
//variable global para firmas dinamicas
var $pdff;
//Pie de página
function Footer()
{
   /* 
    $bool=validar_firma("BM1DG");
    if ($bool==true){
	$this->SetY(-47);
	firma_dinamica("BM1DG",$this->pdff,8,10);
    }else{
	$this->SetY(-48);
	bm1($this->pdff);
    }*/
    //$this->Cell(0,5,'Elaborado Por: '.$this->usuario,0,1,'L');
    $this->Cell(100,5,utf8_decode('Página ').$this->PageNo().'/{nb}'."                                            0012  CONTRALORIA",0,1,'L');
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

function imprimir_tabla($sector,$programa,$actividad,$partida)
{
	$inicial=0;
	$this->pdff=$pdf;
	$cantidad_registros=20;
	$conexion=conexion();
	$consulta="SELECT Inicial, Monto FROM cwprepar WHERE Sector='$sector' and Programa='$programa' and Actividad='$actividad' and Codigo='$partida'";
	$resultado= query($consulta,$conexion);
	$fetch=fetch_array($resultado);
	if($fetch)
	{
		if($fetch[Inicial]!=0)
		{
				$consulta_ins="INSERT INTO tmp_detalle_partida values ('','','SEGUN ORDENANZA DEL PRESUPUESTO','$fetch[Inicial]','par','','')";
				$resultado=query($consulta_ins,$conexion);
		}
	}
	$inicial=$fetch[Inicial];
	$consulta="SELECT par.Dispo, det.monto, det.tipo, det.decreto, modi.descripcion_movimiento, det.fecha from modificacion_det det left join modificacion modi on (det.decreto=modi.decreto) left join cwprepar par on (par.Codigo=det.codigo and par.Sector=det.sec and par.Programa=det.pro and par.Actividad=det.obr) where modi.marca='Afectado' and det.sec='$sector' and det.pro='$programa' and det.obr='$actividad' and det.codigo='$partida'";
	$resultMo=query($consulta,$conexion);
	while($fetchMo=fetch_array($resultMo))
	{
		if($fetchMo[tipo]=='c')
			$inicial+=$fetchMo[monto];
		if($fetchMo[tipo]=='d')
			$inicial-=$fetchMo[monto];
		$consulta_ins="INSERT INTO tmp_detalle_partida values ('','$fetchMo[fecha]','$fetchMo[descripcion_movimiento]','$fetchMo[monto]','mod','$fetchMo[decreto]','')";
		$resultado=query($consulta_ins,$conexion);
	}
	
	$consulta="SELECT ejc.RecNoOrders, ejc.Fecha, ejc.Monto, ejc.saldo, ord.concepto from cwpreejc ejc left join ordenes ord on (ord.codigo=ejc.RecNoOrders) WHERE ejc.Sector='$sector' and ejc.Programa='$programa' and ejc.Actividad='$actividad' and ejc.Partida='$partida' order by ejc.Fecha";
	$resultCo=query($consulta,$conexion);
	while($fetchCo=fetch_array($resultCo))
	{
		$consulta_ins="INSERT INTO tmp_detalle_partida values ('','$fetchCo[Fecha]','$fetchCo[concepto]','$fetchCo[Monto]','com','$fetchCo[RecNoOrders]','')";
		$resultado=query($consulta_ins,$conexion);
	}
	
	$consulta="SELECT eje.RecNoOrders, eje.Fecha, eje.Monto, odp.concepto, odp.numero_ocs from cwpreeje eje left join ordenes_pago odp on (odp.numero_odp=eje.RecNoOrders) WHERE eje.Sector='$sector' and eje.Programa='$programa' and eje.Actividad='$actividad' and eje.Partida='$partida' order by eje.Fecha";
	$resultCa=query($consulta,$conexion);
	while($fetchCa=fetch_array($resultCa))
	{
		$consulta_ins="INSERT INTO tmp_detalle_partida values ('','$fetchCa[Fecha]','$fetchCa[concepto]','$fetchCa[Monto]','cau','$fetchCa[numero_ocs]','$fetchCa[RecNoOrders]')";
		$resultado=query($consulta_ins,$conexion);
	}
	
	$consulta="SELECT eje.RecNoOrders, odp.Fecha, odp.fecche, eje.Monto, odp.concepto, odp.numero_ocs from cwpreeje eje left join ordenes_pago odp on (odp.numero_odp=eje.RecNoOrders) WHERE eje.Sector='$sector' and eje.Programa='$programa' and eje.Actividad='$actividad' and eje.Partida='$partida' and odp.estado='Pagada' order by odp.fecche,odp.Fecha";
	$resultPa=query($consulta,$conexion);
	while($fetchPa=fetch_array($resultPa))
	{
		$fecha=$fetchPa['fecche'];
		if($fecha=='0000-00-00')
		{
			$fecha=$fetchPa['Fecha'];
		}
		$consulta_ins="INSERT INTO tmp_detalle_partida values ('','$fecha','$fetchPa[concepto]','$fetchPa[Monto]','pag','$fetchPa[numero_ocs]','$fetchPa[RecNoOrders]')";
		$resultado=query($consulta_ins,$conexion);
	}
		
	
	$this->SetFont('Times','B',8);
	$this->Setceldas(array('LTR','TR','TR','TR','TR','TR','TR','TR','TR'));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5));
	$this->SetWidths(array(14,16,98,12,23,19,21,32,31));
	$this->SetAligns(array('C','C','C','C','C','C','C','C','C'));
    $this->Row(array("","","","","Credito ","","Saldo para","GASTOS","PAGOS"));
    $this->Setceldas(array('LR','LR','LR','LR','LR','LR','LR','LRB','RB'));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5));
	$this->SetWidths(array(14,16,98,12,23,19,21,32,31));
	$this->SetAligns(array('C','C','C','C','C','C','C','C','C'));
    $this->Row(array("","","","No. Del","Presupuestario","","Comprometer","CAUSADOS",""));
    $this->Setancho(array(5,5,5,5,5,5,5,5,5,5,5));
	$this->SetWidths(array(14,16,98,12,23,19,21,17,15,14,17));
	$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));
    $this->Setceldas(array('LR','LR','LR','LR','LR','LR','LR','LR','LR','LR','LR'));
    $this->Row(array("No. De","Fecha","Detalle","Com-","Monto","Compromisos","Del credito","No. Re-","","No. Re-",""));
    $this->Setceldas(array('LR','LR','LR','LR','LR','LR','LR','LR','LR','LR','LR'));
    $this->Row(array("Registro","","","promiso","Actualizado o","","Presupuestario","gistro Com-","MONTO","gistro","MONTO"));
    $this->Setceldas(array('LBR','BR','BR','BR','BR','BR','BR','BR','BR','BR','BR'));
    $this->Row(array("","","","","Ajustado","","","promiso","","Causado",""));
   	$this->Setceldas(array('LBR','BR','BR','BR','BR','BR','BR','BR','BR','BR','BR'));
    $this->Row(array("1","2","3","4","5","6","7","8","9","10","11"));
   
	$contL=0;
	
	$consulta="SELECT * FROM tmp_detalle_partida order by fecha, odc,odp";
	$resultadoTmp=query($consulta,$conexion);
	while ($fetchTmp=fetch_array($resultadoTmp))
	{
		$tipo=$fetchTmp[tipo];
		$fecha=$fetchTmp[fecha];
		if($fecha=='0000-00-00')
			$fecha='';
		$deno=utf8_decode($fetchTmp[denominacion]);
		$monto=$fetchTmp[monto];
		$odc=$fetchTmp[odc];
		$odp=$fetchTmp[odp];
		$this->SetFont('Times','',8);
		
		$k=$a=strlen($deno)/67;
		if($a<=1)
			$a=1;
		elseif(($a>1)&&($a<=2))
			$a=2;
		elseif(($a>2)&&($a<=3))
			$a=3;
		elseif(($a>3)&&($a<=4))
			$a=4;
		elseif(($a>4)&&($a<=5))
			$a=5;
		elseif(($a>5)&&($a<=6))
			$a=6;
		elseif(($a>6)&&($a<=7))
			$a=7;
		$b=$a*5;
		//$this->Cell(10,5,strlen($deno),0,1);
		$this->Setceldas(array('LBR','BR','BR','BR','BR','BR','BR','BR','BR','BR','BR'));
		$this->Setancho(array($b,$b,5,$b,$b,$b,$b,$b,$b,$b,$b));
		$this->SetWidths(array(14,16,98,12,23,19,21,17,15,14,17));
		$this->SetAligns(array('C','C','L','C','C','C','C','C','C','C','C'));
		if($tipo=='par')
		{
			$this->Row(array('',fecha($fecha),$deno,"",number_format($monto,2,',','.'),"",number_format($monto,2,',','.'),"","","",""));
		}
		elseif($tipo=='com')
		{	
			$this->Row(array($odc,fecha($fecha),$deno,$odc,"",number_format($monto,2,',','.'),"","","","",""));
		}
		elseif($tipo=='cau')
		{
			$this->Row(array($odp,fecha($fecha),$deno,"","","","",$odc,number_format($monto,2,',','.'),"",""));
		}
		elseif($tipo=='pag')
		{
			$this->Row(array($odp,fecha($fecha),$deno,"","","","","","",$odp,number_format($monto,2,',','.')));
		}	
		elseif($tipo=='mod')
		{
			$this->Row(array($odc,fecha($fecha),$deno,"",number_format($monto,2,',','.'),"",number_format($inicial,2,',','.'),"","","",""));
		}	
		if($contL>=$cantidad_registros)
		{	
			$this->SetFont('Arial','I',7);
			$this->Cell(100,5,utf8_decode('Página ').$this->PageNo().'/{nb}'."                                            0012  CONTRALORIA",0,0,'L');
			$this->Ln(300);
			$pagina++;
			$cantidad_registros=20;
			//$this->Ln(15);
			$this->SetFont('Times','B',8);
			$this->Setceldas(array('LTR','TR','TR','TR','TR','TR','TR','TR','TR'));
			$this->Setancho(array(5,5,5,5,5,5,5,5,5));
			$this->SetWidths(array(14,16,98,12,23,19,21,32,31));
			$this->SetAligns(array('C','C','C','C','C','C','C','C','C'));
		    $this->Row(array("","","","","Credito ","","Saldo para","GASTOS","PAGOS"));
		    $this->Setceldas(array('LR','LR','LR','LR','LR','LR','LR','LRB','RB'));
			$this->Setancho(array(5,5,5,5,5,5,5,5,5));
			$this->SetWidths(array(14,16,98,12,23,19,21,32,31));
			$this->SetAligns(array('C','C','C','C','C','C','C','C','C'));
		    $this->Row(array("","","","No. Del","Presupuestario","","Comprometer","CAUSADOS",""));
		    $this->Setancho(array(5,5,5,5,5,5,5,5,5,5,5));
			$this->SetWidths(array(14,16,98,12,23,19,21,17,15,14,17));
			$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C'));
		    $this->Setceldas(array('LR','LR','LR','LR','LR','LR','LR','LR','LR','LR','LR'));
		    $this->Row(array("No. De","Fecha","Detalle","Com-","Monto","Compromisos","Del credito","No. Re-","","No. Re-",""));
		    $this->Setceldas(array('LR','LR','LR','LR','LR','LR','LR','LR','LR','LR','LR'));
		    $this->Row(array("Registro","","","promiso","Actualizado o","","Presupuestario","gistro Com-","MONTO","gistro","MONTO"));
		    $this->Setceldas(array('LBR','BR','BR','BR','BR','BR','BR','BR','BR','BR','BR'));
		    $this->Row(array("","","","","Ajustado","","","promiso","","Causado",""));
		   	$this->Setceldas(array('LBR','BR','BR','BR','BR','BR','BR','BR','BR','BR','BR'));
		    $this->Row(array("1","2","3","4","5","6","7","8","9","10","11"));
			$contL=0;
		}
		$contL=$contL+$a;
		$i=$i+1;
	
	}
	$conexion=conexion();
	$consulta="TRUNCATE TABLE tmp_detalle_partida";
	$resultadoTmp=query($consulta,$conexion);
}
}


//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',10);
$pdf->imprimir_tabla($sector,$programa,$actividad,$partida);
$pdf->Output();
?>
