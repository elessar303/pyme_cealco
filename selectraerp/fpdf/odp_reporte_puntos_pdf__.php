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
    
    
    

    
    
    
//  $this->SetFont('Arial','B',10);
//  $this->Cell(150,8,'ORDEN DE PAGO No.: '.$nro_odp,0,0,"C");
//  $this->cell(38,8,'Fecha: '.fecha($fecha),0,0,'C');
//  $this->Ln();
//  $this->SetFont('Arial','B',8);
//  $this->Cell(188,5,'BENEFICIARIO ',1,0,"C");
//  $this->Ln();
//  // llamado para hacer multilinea sin que haga salto de linea
//  $this->SetFont('Arial','I',8);
//         $this->SetWidths(array(130,58));
//  $this->SetAligns(array('L','L'));
//         $this->Setceldas(array(0,0));
//  $this->Setancho(array(5,5));
//  $cam=utf8_decode('Nombre o Razón Social:  ');
//         $this->Row(array($cam.utf8_decode($beneficiario),'R.I.F.:  '.utf8_decode($rif)));
//  $cam=utf8_decode('Dirección Fiscal:   ');
//  $cam2=utf8_decode('Correo Electrónico:   ');
//  $this->Row(array($cam.utf8_decode($direccion),$cam2.utf8_decode($correo)));
//  $this->Row(array('Cuenta Bancaria: '.$cuenta,'Banco: '));
// 
//  $this->SetFont('Arial','B',8);
//  $this->Cell(188,5,'DATOS DE PAGO ',1,0,"C");
//  $this->Ln();
//  $this->SetFont('Arial','I',8);
//  $n = new numerosALetras();
//  $montoLetras=$n->convertir($montoPago);
// 
//  
    
    
    $conexion=conexion();   
    
    
    $this->Ln(5);
    

    $this->SetFont('SanserifB','',8);  
	//esto es para el orden a Terceros.-
    if($fila_odp['estado']=='Terceros'){
	$this->Cell(108,5,'',0,0);
	$this->Cell(30,5,'',0,0,'C');
	$this->Cell(30,5,$fila_odp['calculo16'],0,1,'C');
	$this->Ln(5);
    }else{
    
   		 $this->Ln(10);
	}
    if($fila_odp['tipo']=='Directas Pe' ){
		$this->Cell(108,5,'',0,0);
		$this->Cell(30,5,'DIRECTA',0,0,'C');
		$this->Cell(30,5,'1',0,1,'C');
	}else{
		if($fila_odp['tipo']=='Nomina' ){
			$this->Cell(108,5,'',0,0);
			$this->Cell(30,5,'DIRECTA',0,0,'C');
			$this->Cell(30,5,'1',0,1,'C');
			
		}else{
    			
			$this->Cell(108,5,'',0,0);
			$this->Cell(30,5,'AVANCE/S/IMP',0,0,'C');
			$this->Cell(30,5,'3',0,1,'C');
		}
	}
   $this->Ln(2);
    if($fila_odp['tipo']=='Especial' || $fila_odp['tipo']=='Directas Pe' ){
	$this->Cell(108,5,'',0,0);
	$this->Cell(30,5,'ESPECIAL',0,0,'C');
	$this->Cell(30,5,'2',0,1,'C');
	$this->Ln(5);
    }else{
	if($fila_odp['tipo']=='Permanente'){
		$this->Cell(108,5,'',0,0);
		$this->Cell(30,5,'PERMANENTE',0,0,'C');
		$this->Cell(30,5,'',0,1,'C');
		$this->Ln(5);
	}
	if($fila_odp['tipo']=='Nomina' ){
			$this->Cell(108,5,'',0,0);
			$this->Cell(30,5,'ESPECIAL',0,0,'C');
			$this->Cell(30,5,'2',0,1,'C');
			$this->Ln(5);
	}

    }
    $this->Cell(108,5,'',0,0);
    $this->Cell(30,5,fecha($fecha),0,0,'C');
    $this->Cell(30,5,'',0,1,'C');


    $this->Ln(3);

    $this->MultiCell(188,8,utf8_decode($beneficiario),0,'L');


    
    $this->SetFont('SanserifB','',7); 
    $this->Ln(12);
    $this->SetWidths(array(96,92));
    $this->SetAligns(array('L','C'));
    $this->Setceldas(array(0,0));
    $this->Setancho(array(5,5));
    $this->Row(array(utf8_decode($beneficiario),utf8_decode($rif)));

	
    $this->SetFont('SanserifB','',6); 
	if($fila_odp['calculo16']!=0){
		if($fila_odp['frecuencia']==15){
			$frecuencia='QUINCENAL';
			
		}else{
			$frecuencia='MENSUAL';
			
		}
	}
	if($fila_odp['tipo']=='Especial' || $fila_odp['tipo']=='Directas Pe' || $fila_odp['tipo']=='Nomina'){	
		if(strlen($beneficiario)>42){
			$this->Ln(55);
		}else{
   		 	$this->Ln(63);
		}
	}else{
		if($fila_odp['tipo']=='Permanente'){
			$this->Ln(20);
			$monto_2=$fila_odp['monto']/$fila_odp['calculo16'];
			$this->SetWidths(array(25,32,37,50,34));
			$this->SetAligns(array('L','L','C','C','C'));
			$this->Setceldas(array(0,0,0,0,0,0,0));
			$this->Setancho(array(5,5,5,5,5,5,5));
			$this->Row(array('','',$fila_odp['calculo16'],$frecuencia,'02'));
			$this->Ln(11);
			$this->SetX(3);
			$fecha1=split("-",$fila_odp['feccau']);
			$fecha2=split("-",$fila_odp['fecaudi']);
		
			$this->Row(array($fecha1[2].' '.$fecha1[1].'  '.$fecha1[0],$fecha2[2].' '.$fecha2[1].'  '.$fecha2[0],'Monto '.$frecuencia.' Bs.',number_format($monto_2,2,',','.'),''));
	
			$this->Ln(21);
		}
	}



    $conexion=conexion();
	if($fila_odp['tipo']=='Especial' || $fila_odp['tipo']=='Permanente'){
		$consulta_partida="select * from odp_tercero_preejc where RecOdp=$nro_odp order by Sector,Programa,Actividad";
		$query_partida=query($consulta_partida,$conexion);
		$cantidad=num_rows($query_partida);
		$this->SetFont('SanserifB','',6); 
		$montoPago=0;
		while(($fila_partida=fetch_array($query_partida))!=NULL){
			$this->SetWidths(array(20,20,20,20,20,20,19,18,23));
			$this->SetAligns(array('L','L','L','L','C','C','C','C','R'));
			$this->Setceldas(array(0,0,0,0,0,0,0,0,0));
			$this->Setancho(array(5,5,5,5,5,5,5,5,5));
			$this->Row(array($fila_partida['Sector'],$fila_partida['Programa'],$fila_partida['Actividad'],$fila_partida['Partida'],'00','00','00','',number_format($fila_partida['Monto'],2,',','.')));
			$montoPago+=$fila_partida['Monto'];
		}
	}else{
		if($fila_odp['tipo']=='Nomina' ){
// 			echo 'hola'.$fila_odp['calculo16'];
			if($fila_odp['calculo16']!=null && $fila_odp['calculo16']!=' '){
				$cod_nomina=$fila_odp['calculo16'];
			}else{
				$cod_nomina=$filaOCS['entrega'];
			}
			$consulta_tipo="select tipnom from nom_movimientos_nomina where nom_movimientos_nomina.codnom=$cod_nomina ";
			$query_tipo=query($consulta_tipo,$conexion);
			$resul_tipo=fetch_array($query_tipo);
			//echo $resul_tipo['tipnom'];

			if(($resul_tipo['tipnom']==4 || $resul_tipo['tipnom']==5)&& $fila_odp['cuenta']!=4){
			//if($resul_tipo['tipnom']==4 || $resul_tipo['tipnom']==5){
				//tipo de nomina jubilado y pensionado.
				$consulta_partida="select * from cwpreeje where RecNoOrders=$nro_odp order by Sector,Programa,Actividad";
	 			$query_partida=query($consulta_partida,$conexion);
	 			$cantidad=num_rows($query_partida);
	 			$this->SetFont('SanserifB','',6); 
	 			$montoPago=0;
	 			while(($fila_partida=fetch_array($query_partida))!=NULL){
	 				$this->SetWidths(array(20,20,20,20,20,20,19,18,23));
	 				$this->SetAligns(array('L','L','L','L','C','C','C','C','R'));
	 				$this->Setceldas(array(0,0,0,0,0,0,0,0,0));
	 				$this->Setancho(array(5,5,5,5,5,5,5,5,5));
	 				//echo $fila_partida['Partida'];
	 				$partida=explode('.',$fila_partida['Partida']);
					$this->Row(array($fila_partida['Sector'],$fila_partida['Programa'],$fila_partida['Actividad'],$partida[0].'.'.$partida[1],$partida[2],$partida[3],$partida[4],'',number_format($fila_partida['Monto'],2,',','.')));
	 				$montoPago+=$fila_partida['Monto'];
	 			}
			}else{
				$consulta_persona="select * from nom_movimientos_nomina where nom_movimientos_nomina.codnom=$cod_nomina  group by ficha,tipnom";
				$query_persona=query($consulta_persona,$conexion);
				$valores=mysql_num_rows($query_persona);
				if($valores==1){
				
					$consulta_partida="select * from cwpreeje where RecNoOrders=$nro_odp order by Sector,Programa,Actividad";
					$query_partida=query($consulta_partida,$conexion);
					$cantidad=num_rows($query_partida);
					$this->SetFont('SanserifB','',6); 
					$montoPago=0;
					while(($fila_partida=fetch_array($query_partida))!=NULL){
						$this->SetWidths(array(20,20,20,20,20,20,19,18,23));
						$this->SetAligns(array('L','L','L','L','C','C','C','C','R'));
						$this->Setceldas(array(0,0,0,0,0,0,0,0,0));
						$this->Setancho(array(5,5,5,5,5,5,5,5,5));
						//echo $fila_partida['Partida'];
						$partida=explode('.',$fila_partida['Partida']);
						$this->Row(array($fila_partida['Sector'],$fila_partida['Programa'],$fila_partida['Actividad'],$partida[0].'.'.$partida[1],$partida[2],$partida[3],$partida[4],'',number_format($fila_partida['Monto'],2,',','.')));
						$montoPago+=$fila_partida['Monto'];
					}
				}else{
					$consulta_partida="select sum(Monto) as monto from cwpreeje where RecNoOrders=$nro_odp ";
					$query_partida=query($consulta_partida,$conexion);
					$cantidad=num_rows($query_partida);
					$this->SetFont('SanserifB','',6); 
					$montoPago=0;
					while(($fila_partida=fetch_array($query_partida))!=NULL){
						$this->SetWidths(array(20,20,20,20,20,20,19,18,23));
						$this->SetAligns(array('L','L','L','L','C','C','C','C','R'));
						$this->Setceldas(array(0,0,0,0,0,0,0,0,0));
						$this->Setancho(array(5,5,5,5,5,5,5,5,5));
						$this->Row(array('','','','4.01.','00','00','00','',number_format($fila_partida['monto'],2,',','.')));
						$montoPago+=$fila_partida['monto'];
					}
				}
			}
		}else{
			if($fila_odp['tipo']=='Directas Pe' ){
				$consulta_partida="select * from cwpreeje where RecNoOrders=$nro_odp order by Sector,Programa,Actividad";
				$query_partida=query($consulta_partida,$conexion);
				$cantidad=num_rows($query_partida);
				$this->SetFont('SanserifB','',6); 
				$montoPago=0;
				while(($fila_partida=fetch_array($query_partida))!=NULL){
					$this->SetWidths(array(20,20,20,20,20,20,19,18,23));
					$this->SetAligns(array('L','L','L','L','C','C','C','C','R'));
					$this->Setceldas(array(0,0,0,0,0,0,0,0,0));
					$this->Setancho(array(5,5,5,5,5,5,5,5,5));
					//echo $fila_partida['Partida'];
					$partida=explode('.',$fila_partida['Partida']);
					$this->Row(array($fila_partida['Sector'],$fila_partida['Programa'],$fila_partida['Actividad'],$partida[0].'.'.$partida[1],$partida[2],$partida[3],$partida[4],'',number_format($fila_partida['Monto'],2,',','.')));
					$montoPago+=$fila_partida['Monto'];
				}
			}else{
				$consulta_partida="select * from cwpreeje where RecNoOrders=$nro_odp order by Sector,Programa,Actividad";
				$query_partida=query($consulta_partida,$conexion);
				$cantidad=num_rows($query_partida);
				$this->SetFont('SanserifB','',6); 
				$montoPago=0;
				while(($fila_partida=fetch_array($query_partida))!=NULL){
					$this->SetWidths(array(20,20,20,20,20,20,19,18,23));
					$this->SetAligns(array('L','L','L','L','C','C','C','C','R'));
					$this->Setceldas(array(0,0,0,0,0,0,0,0,0));
					$this->Setancho(array(5,5,5,5,5,5,5,5,5));
					$this->Row(array($fila_partida['Sector'],$fila_partida['Programa'],$fila_partida['Actividad'],$fila_partida['Partida'],'00','00','00','',number_format($fila_partida['Monto'],2,',','.')));
					$montoPago+=$fila_partida['Monto'];

				}

			}
		}

	}

    $n = new numerosALetras();
    $montoLetras=$n->convertir($montoPago);

    switch($cantidad){
	case 1: $this->Ln(25);break;
	case 2: $this->Ln(20);break;
	case 3: $this->Ln(15);break;
	case 4: $this->Ln(10);break;
	case 5: $this->Ln(5);break;
	
    }
    
    $this->Ln(2);
    $this->SetWidths(array(157,23));
    $this->SetAligns(array('L','R'));
    $this->Setceldas(array(0,0));
    $this->Setancho(array(5,5));
    $this->Row(array(strtoupper($montoLetras),number_format($montoPago,2,',','.')));
    if($fila_odp['tipo']=='Nomina' ){
		$this->Ln(8);
	}ELSE{
    		$this->Ln(15);
	}

    $this->MultiCell(188,5,utf8_decode($concepto),0,'J');
    if($fila_odp['tipo']=='Nomina' ){
	$conexion=conexion();
	//validar con tipo de nomina las deducciones si es vacaciones o no
	$consulta="select sum(monto) as monto,nomconceptos.descrip from nom_movimientos_nomina left join nomconceptos  on nomconceptos.codcon=nom_movimientos_nomina.codcon  where nom_movimientos_nomina.codnom=$cod_nomina and nom_movimientos_nomina.codcon>=2400 and nom_movimientos_nomina.codcon<=2499 group by nom_movimientos_nomina.codcon";
	$query_con=query($consulta,$conexion);
	$this->SetWidths(array(64,30,64,30));
	$this->SetAligns(array('L','R','L','R'));
	$this->Setceldas(array(0,0,0,0));
	$this->Setancho(array(5,5,5,5));
	$this->SetFont('SanserifB','',5); 
	$monto_ret=0;
	while($concepto=fetch_array($query_con)){
		if($concepto1=fetch_array($query_con)){
			$this->Row(array($concepto['descrip'],'- '.number_format($concepto['monto'],2,',','.').'   ',$concepto1['descrip'],'- '.number_format($concepto1['monto'],2,',','.').'   '));
			$monto_ret+=$concepto1['monto'];
		}else{
			$this->Row(array($concepto['descrip'],'- '.number_format($concepto['monto'],2,',','.'),'','').'   ','','');
		}
		$monto_ret+=$concepto['monto'];
	}
	$this->Row(array('','','TOTAL DE RETENCIONES','- '.number_format($monto_ret,2,',','.').'   '));
	$this->Row(array('','','MONTO NETO',number_format($montoPago-$monto_ret,2,',','.').'   '));
    }//elseif ($filaOCS['tipo']==17){
	elseif ($filaOCS['tipo']==17 || $filaOCS['tipo']==2){
        if($filaOCS['tipo']==17){
            $monto_total=$filaOCS['monto_TotalOBR'];
        }else{
            $monto_total=$filaOCS['monto_orden'];
            $montoPago=$fila_odp['montopago'];
        }
		$monto_total=$filaOCS['monto_TotalOBR'];
		$monto_deducible=$filaOCS['monto_DeducibleOBR'];
		$this->SetWidths(array(60,30));
		$this->SetAligns(array('L','R'));
		$this->Setceldas(array(0,0));
		$this->Setancho(array(5,5));
		$this->SetFont('SanserifB','',5); 

		$this->Row(array('MONTO VAL.',number_format($monto_total,2,',','.')));    //ESTO NO ESTA MOSTRANDO EL MONTO attm GIAN
		$this->Row(array($fila_odp['calculo11'],number_format($fila_odp['monto_igv'],2,',','.')));
		$this->Row(array($fila_odp['calculo12'],'-'.number_format($fila_odp['monto_retiva'],2,',','.')));
		$this->Row(array($fila_odp['calculo17'],'-'.number_format($fila_odp['monto_islr'],2,',','.')));
		$this->Row(array('RET. TIMBRE FISCAL','-'.number_format($fila_odp['calculo7'],2,',','.')));
		$this->Row(array('AMORTZ. ANT. ','-'.number_format($monto_deducible,2,',','.')));
		$this->Row(array('Monto Neto',number_format($montoPago,2,',','.')));
		
		
	}elseif( $fila_odp['estado']=='Terceros'){
		$this->SetWidths(array(70,30));
		$this->SetAligns(array('L','R'));
		$this->Setceldas(array(0,0));
		$this->Setancho(array(5,5));
		$this->SetFont('SanserifB','',5); 
		$this->Row(array('MONTO :',number_format($fila_odp['valuacion'],2,',','.')));
		$this->Row(array($fila_odp['calculo11'],number_format($fila_odp['monto_igv'],2,',','.')));
		$this->Row(array($fila_odp['calculo12'],'-'.number_format($fila_odp['monto_retiva'],2,',','.')));
		$this->Row(array($fila_odp['calculo6'],'-'.number_format($fila_odp['calculo10'],2,',','.')));
		//$this->Row(array('RET. TIMBRE FISCAL','-'.number_format($fila_odp['calculo7'],2,',','.')));
		//$this->Row(array('AMORTZ. ANT. ','-'.number_format($monto_deducible,2,',','.')));
		$this->Row(array('Monto Neto',number_format($fila_odp['saldo'],2,',','.')));

	}
// 	echo $filaOCS['tipo'];

    


//  $this->MultiCell(188,5,'Unidad Solicitante:  '.$var_nom_und);
//  $this->MultiCell(188,5,'Concepto:  '.utf8_decode($concepto));
//  //inicio
//  $this->SetFont('Arial','I',8);
//         $this->SetWidths(array(63,63,62));
//  $this->SetAligns(array('L','L','L'));
//         $this->Setceldas(array(0,0,0));
//  $this->Setancho(array(5,5,5));
//         $this->Row(array(utf8_decode('Nº Factura:  ').$nro_factura,utf8_decode('Nº Control Orden:     ').$nro_ocs,utf8_decode('Nº Referencia Orden:   ').$orden));
//  //fin
//  $this->MultiCell(188,5,'Observaciones:  '.utf8_decode($observaciones));
// 
//  $this->SetWidths(array(130,58));
//  $cam=utf8_decode('Monto en Números: ');
//  $this->Row(array('La cantidad de: '.strtoupper($montoLetras),$cam.number_format($montoPago, 2, ',', '.').' '.$moneda));
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
//      $this->Ln(1);
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
    

    //$pdf->totales($fila_odp,$moneda);
    //$pdf->datos_partidas($nro_odp,$periodo,$nro_odp,$fila_odp, $moneda,$pdf);
    $pdf->odp=$fila_odp;






$pdf->Output();
?>
