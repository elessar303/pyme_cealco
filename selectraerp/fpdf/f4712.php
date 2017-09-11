<?php
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');

require_once '../lib/config.php';
require_once '../lib/pdfcommon.php';
require_once '../lib/common.php';
require_once '../lib/pdf.php';
//include ("../selectra/header.php");

$cantidad_registros=22;



function cantidad_registro($concep){
	
	if(strlen($concep)<=75)
	{
		return 24;
	}elseif(strlen($concep)>75){
		return 21;
	}
		
}


class PDF extends FPDF
{
var $usuario;
var $tipo;
var $concep;
//Cabecera de página
function Header()
{
	
        $Conn=conexion_conf();
	
	
	$var_sql="select * from parametros";
	$rsu = query($var_sql,$Conn);
	$row_rsu = fetch_array($rsu);
	$var_nomemp=$row_rsu['nomemp'];
	
	cerrar_conexion($Conn);
	$this->SetFont('Arial','B',10);
        
		
	$this->Cell(260,5,utf8_decode("INSTITUCIÓN: ".$var_nomemp),'LTR',1,"L");
	$this->Cell(60,5,utf8_decode("PRESUPUESTO: ".date("Y")),'L',0,'L');
	$this->Cell(160,5,utf8_decode("PRESUPUESTO DE CAJA"),0,0,'C');
	$this->Cell(40,5,"FECHA: ".date("d/m/Y"),'R',1,'C');
	

	
	

}
function imprime_trime($pdf,$trimestre)
{
	$this->SetFont('Arial','B',10);
	$this->Cell(60,5,utf8_decode("TRIMESTRE N°: ".$trimestre),'LB',0,'L');
	$this->Cell(160,5,utf8_decode("(EN BOLÍVARES)"),'B',0,'C');
	$this->Cell(40,5,"",'BR',1,'C');
	
	
	$pdf->SetFont('Times','B',7);

	$this->Cell(25,4,'','LR',0,'C');
	$this->Cell(70,4,'','LR',0,'C');
	$this->Cell(20,4,'REAL AL FINAL','LR',0,'C');
	$this->Cell(40,4,'','LR',0,'C');
	$this->Cell(25,4,'','LR',0,'C');
	$this->Cell(60,4,'','LR',0,'C');
	$this->Cell(20,4,utf8_decode("PREVISIÓN"),'LR',1,'C');
	
	$this->Cell(25,4,utf8_decode('CÓDIGO'),'LR',0,'C');
	$this->Cell(70,4,utf8_decode('DENOMINACIÓN'),'LR',0,'C');
	$this->Cell(20,4,'DEL TRIMESTRE','LR',0,'C');
	$this->Cell(40,4,'HASTA EL TRIMESTRE','LR',0,'C');
	$this->Cell(25,4,utf8_decode('VARIACIÓN'),'LR',0,'C');
	$this->Cell(60,4,utf8_decode('REPROGRAMACIÓN'),1,0,'C');
	$this->Cell(20,4,'TOTAL','LR',1,'C');

	$this->Cell(25,4,'','LRB',0,'C');
	$this->Cell(70,4,'','LRB',0,'C');
	$this->Cell(20,4,'ANTERIOR','LRB',0,'C');
	$this->Cell(20,4,'PROGRAMADO',1,0,'C');
	$this->Cell(20,4,'EJECUTADO',1,0,'C');
	$this->Cell(17,4,'ABSOLUTA',1,0,'C');
	$this->Cell(8,4,'%',1,0,'C');
	$this->Cell(20,4,utf8_decode('TRIMESTRE N°'),1,0,'C');
	$this->Cell(20,4,utf8_decode('TRIMESTRE N°'),1,0,'C');
	$this->Cell(20,4,utf8_decode('TRIMESTRE N°'),1,0,'C');
	$this->Cell(20,4,utf8_decode('AÑO'),'LRB',1,'C');
	
				

}
//variable global para firmas dinamicas
var $pdff;
//Pie de página
function Footer()
{
    
//     $bool=validar_firma("BM1DG");
//     if ($bool==true){
// 	$this->SetY(-47);
// 	firma_dinamica("BM1DG",$this->pdff,8,10);
//     }else{
// 	$this->SetY(-48);
// 	bm1($this->pdff);
//     }
//     //$this->Cell(0,5,'Elaborado Por: '.$this->usuario,0,1,'L');
//     $this->Cell(100,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'L');
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



function imprimir_tabla($pdf,$pagina,$num_paginas,$trimestre){
	$cantidad_registros=22;
	$conexion=conexion();
	$consulta_req="select *,sum(Inicial) as suma from cwprepar inner join cwprecue on cwprecue.CodCue=cwprepar.Codigo and cwprepar.Inicial<>0 group by Codigo order by cwprepar.Codigo ";
	

	$rs = query($consulta_req,$conexion);
	$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
	$total= num_rows($rs);
	//Cabecera
	
	
	//Cabecera
	$pdf->SetFont('Times','B',8);

	$this->Cell(15,7,'','LR',0,'C');
	$this->Cell(70,7,'','LR',0,'C');
	$this->Cell(20,7,'UNIDAD','LR',0,'C');
	$this->Cell(40,7,'','LR',0,'C');
	$this->Cell(40,7,'','LR',0,'C');
	$this->Cell(50,7,utf8_decode('VARIACIÓN'),'LR',0,'C');
	$this->Cell(25,7,utf8_decode("PREVISIÓN"),'LR',1,'C');
	
	$this->Cell(15,7,'PROG.','LR',0,'C');
	$this->Cell(70,7,utf8_decode('DENOMINACIÓN'),'LR',0,'C');
	$this->Cell(20,7,'DE','LR',0,'C');
	$this->Cell(40,7,'PROGRAMADO','LR',0,'C');
	$this->Cell(40,7,'EJECUTADO','LR',0,'C');
	$this->Cell(25,7,'TRIMESTRE',1,0,'C');
	$this->Cell(25,7,'ACUMULADO',1,0,'C');
	$this->Cell(25,7,utf8_decode("PRÓXIMO"),'LR',1,'C');

	$this->Cell(15,7,'','LRB',0,'C');
	$this->Cell(70,7,'','LRB',0,'C');
	$this->Cell(20,7,'MEDIDA','LRB',0,'C');
	$this->Cell(20,7,'TRIMESTRE',1,0,'C');
	$this->Cell(20,7,'ACUMULADO',1,0,'C');
	$this->Cell(20,7,'TRIMESTRE',1,0,'C');
	$this->Cell(20,7,'ACUMULADO',1,0,'C');
	$this->Cell(17,7,'ABSOLUTA',1,0,'C');
	$this->Cell(8,7,'%',1,0,'C');
	$this->Cell(17,7,'ABSOLUTA',1,0,'C');
	$this->Cell(8,7,'%',1,0,'C');
	$this->Cell(25,7,'TRIMESTRE','LRB',1,'C');
				
	

	$pdf->SetFont('Times','',9);
	//Datos
	$cont=1;
	$contar=1;
	$pagina=1;
	$depen='';
	$primer=0;
	$totaI=0;
	$totaD=0;
	while ($total>=$contar)
	{
		$row_rs = fetch_array($rs);
		$var_partida=$row_rs['Codigo'];
		$descripcion=$row_rs['Denominacion'];
		$ano=date("Y");
		
		switch($trimestre){
			case 1:
				$conexion=conexion();
				// compromisos
				$concomp="select sum(Monto) as suma from cwpreejc where Partida='$var_partida' and Fecha>='$ano-01-01' and Fecha<='$ano-03-31' and Marca='X' group by Partida";
				$compro=query($concomp,$conexion);
				$comprofetch=fetch_array($compro);
				$compromiso=$comprofetch[0];
				//gastos
				$gascomp="select sum(Monto) as suma from cwpreeje where Partida='$var_partida' and Fecha>='$ano-01-01' and Fecha<='$ano-03-31' and Marca='X' group by Partida";
				$gast=query($gascomp,$conexion);
				$gas=fetch_array($gast);
				$gasto=$gas[0];
				//pagos
				$pagcomp="select sum(Monto) as suma from cwpreeje where Partida='$var_partida' and Fecha>='$ano-01-01' and Fecha<='$ano-03-31' and Marca='X' and Cheque<>0 group by Partida";
				$pa=query($pagcomp,$conexion);
				$pag=fetch_array($pa);
				$pago=$pag[0];

				break;
			case 2:
				$conexion=conexion();
				// compromisos
				$concomp="select sum(Monto) as suma from cwpreejc where Partida='$var_partida' and Fecha>='$ano-04-01' and Fecha<='$ano-06-30' and Marca='X' group by Partida";
				$compro=query($concomp,$conexion);
				$comprofetch=fetch_array($compro);
				$compromiso=$comprofetch[0];
				//gastos
				$gascomp="select sum(Monto) as suma from cwpreeje where Partida='$var_partida' and Fecha>='$ano-04-01' and Fecha<='$ano-06-30' and Marca='X' group by Partida";
				$gast=query($gascomp,$conexion);
				$gas=fetch_array($gast);
				$gasto=$gas[0];
				//pagos
				$pagcomp="select sum(Monto) as suma from cwpreeje where Partida='$var_partida' and Fecha>='$ano-04-01' and Fecha<='$ano-06-30' and Marca='X' and Cheque<>0 group by Partida";
				$pa=query($pagcomp,$conexion);
				$pag=fetch_array($pa);
				$pago=$pag[0];
				break;
			case 3:
				
				$conexion=conexion();
				// compromisos
				$concomp="select sum(Monto) as suma from cwpreejc where Partida='$var_partida' and Fecha>='$ano-07-01' and Fecha<='$ano-09-30' and Marca='X' group by Partida";
				$compro=query($concomp,$conexion);
				$comprofetch=fetch_array($compro);
				$compromiso=$comprofetch['suma'];
				//gastos
				$gascomp="select sum(Monto) as suma from cwpreeje where Partida='$var_partida' and Fecha>='$ano-07-01' and Fecha<='$ano-09-30' and Marca='X' group by Partida";
				$gast=query($gascomp,$conexion);
				$gas=fetch_array($gast);
				$gasto=$gas[0];
				//pagos
				$pagcomp="select sum(Monto) as suma from cwpreeje where Partida='$var_partida' and Fecha>='$ano-07-01' and Fecha<='$ano-09-30' and Marca='X' and Cheque<>0 group by Partida";
				$pa=query($pagcomp,$conexion);
				$pag=fetch_array($pa);
				$pago=$pag[0];
				break;
			case 4: 
				$conexion=conexion();
				// compromisos
				$concomp="select sum(Monto) as suma from cwpreejc where Partida='$var_partida' and Fecha>='$ano-10-01' and Fecha<='$ano-12-31' and Marca='X' group by Partida";
				$compro=query($concomp,$conexion);
				$comprofetch=fetch_array($compro);
				$compromiso=$comprofetch[0];
				//gastos
				$gascomp="select sum(Monto) as suma from cwpreeje where Partida='$var_partida' and Fecha>='$ano-10-01' and Fecha<='$ano-12-31' and Marca='X' group by Partida";
				$gast=query($gascomp,$conexion);
				$gas=fetch_array($gast);
				$gasto=$gas[0];
				//pagos
				$pagcomp="select sum(Monto) as suma from cwpreeje where Partida='$var_partida' and Fecha>='$ano-10-01' and Fecha<='$ano-12-31' and Marca='X' and Cheque<>0 group by Partida";
				$pa=query($pagcomp,$conexion);
				$pag=fetch_array($pa);
				$pago=$pag[0];
				break;

		}
		
	
	//maximizar lineas
	
	$cantida=strlen($descripcion)/20;
	if($cantida<1){
		$cantidad_registros-=1;
		$cantida=1;
	}else{
		$cantidad_registros-=round($cantida);
		$cantida=round($cantida);
	}
	
	//$this->Cell(10,5,5*$cantida,0,1);
		// llamado para hacer multilinea sin que haga salto de linea
		$this->Setceldas(array(1,1,1,1,1,1,1,1,1,1,1));

		$this->Setancho(array(5*$cantida,5*$cantida,5*$cantida,5*$cantida,5,5*$cantida,5*$cantida,5*$cantida,5*$cantida,5*$cantida,5*$cantida));

		$this->SetWidths(array(15,9,9,12,35,45,30,45,20,20,20));
		$this->SetAligns(array('C','C','C','C','L','R','R','R','R','R','R'));
		list($partida,$generica,$especifica,$subespecifica,$auxiliar)=explode(".",$var_partida);
		$this->Row(array($partida.'.'.$generica,$especifica,$subespecifica,$auxiliar,$descripcion,number_format($row_rs['suma'],2,',','.'),number_format($compromiso,2,',','.'),number_format($row_rs['suma']-$compromiso,2,',','.'),number_format($gasto,2,',','.'),number_format($pago,2,',','.'),number_format($gasto-$pago,2,',','.')));
		
	
	if($cantidad_registros<=0)
	{	
		
			
			$this->Ln(300);
			$pagina++;
			$cantidad_registros=22;
			
			$this->imprime_trime($pdf,$trimestre);
			//Cabecera
			$pdf->SetFont('Times','B',8);

			$this->Cell(15,7,'','LR',0,'C');
			$this->Cell(70,7,'','LR',0,'C');
			$this->Cell(20,7,'UNIDAD','LR',0,'C');
			$this->Cell(40,7,'','LR',0,'C');
			$this->Cell(40,7,'','LR',0,'C');
			$this->Cell(50,7,utf8_decode('VARIACIÓN'),'LR',0,'C');
			$this->Cell(25,7,utf8_decode("PREVISIÓN"),'LR',1,'C');
			
			$this->Cell(15,7,'PROG.','LR',0,'C');
			$this->Cell(70,7,utf8_decode('DENOMINACIÓN'),'LR',0,'C');
			$this->Cell(20,7,'DE','LR',0,'C');
			$this->Cell(40,7,'PROGRAMADO','LR',0,'C');
			$this->Cell(40,7,'EJECUTADO','LR',0,'C');
			$this->Cell(25,7,'TRIMESTRE',1,0,'C');
			$this->Cell(25,7,'ACUMULADO',1,0,'C');
			$this->Cell(25,7,utf8_decode("PRÓXIMO"),'LR',1,'C');
		
			$this->Cell(15,7,'','LRB',0,'C');
			$this->Cell(70,7,'','LRB',0,'C');
			$this->Cell(20,7,'MEDIDA','LRB',0,'C');
			$this->Cell(20,7,'TRIMESTRE',1,0,'C');
			$this->Cell(20,7,'ACUMULADO',1,0,'C');
			$this->Cell(20,7,'TRIMESTRE',1,0,'C');
			$this->Cell(20,7,'ACUMULADO',1,0,'C');
			$this->Cell(17,7,'ABSOLUTA',1,0,'C');
			$this->Cell(8,7,'%',1,0,'C');
			$this->Cell(17,7,'ABSOLUTA',1,0,'C');
			$this->Cell(8,7,'%',1,0,'C');
			$this->Cell(25,7,'TRIMESTRE','LRB',1,'C');
			
			$pdf->SetFont('Times','',9);
			
			$cont=1;
		}
        //echo $contar;
	 $contar++;
	
	}
	

}
}


//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',8);


//$pdf->imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$pdf);
$trimestre = (empty($_REQUEST['trime'])) ? '' : $_REQUEST['trime'];

$pdf->imprime_trime($pdf,$trimestre);
//$pdf->imprimir_tabla($pdf,$pagina,$num_paginas,$trimestre);

$pdf->Output();
?>
