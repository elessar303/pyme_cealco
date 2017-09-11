<?php
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');

require_once '../lib/config.php';
require_once '../lib/pdfcommon.php';
require_once '../lib/common.php';
//include ("../selectra/header.php");


$cantidad_registros=20;

$conexion=conexion();



class PDF extends FPDF
{
var $cod_cotizacion;
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
     	$this->Image($var_imagen_der,8,6,25);
     	$this->Ln();
     	
     	

}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $conexion=conexion();
    $cod_cotizacion=$this->cod_cotizacion;
    $consulta="select * from analisis_cotizaciones where cod_cotizacion=$cod_cotizacion";
    $ra=query($consulta,$conexion);
   // $this->Ln(50);
    if($raf=fetch_array($ra)){
	$this->SetY(-30);
	$this->SetTextColor(0);
	$this->SetFont('Arial','I',10);
	$this->MultiCell(260,7,"OBSERVACIONES:  ".$raf['observaciones'],1,'L');
	
    	$this->SetFont('Arial','I',8);
    //$_SESSION['nombre']
    	$this->Cell(100,10,'Elaborado Por: '.$raf['usuario'],0,0,'L');
    }else{
	$this->SetY(-30);
	$this->SetTextColor(0);
	$this->SetFont('Arial','I',10);
	$this->MultiCell(260,7,"OBSERVACIONES:  ",1,'L');
	
    	$this->SetFont('Arial','I',8);
    //$_SESSION['nombre']
    	$this->Cell(100,10,'Elaborado Por: ',0,0,'L');
	}
    $this->Cell(40,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');

    //Número de página
   // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
//Hacer que sea multilinea sin que haga un salto de linea
var $widths;
var $aligns;
var $celdas;
var $ancho;
var $nro_ocs;
var $negrita;
function SetNegrita($n)
{
    //Set the array of column widths
    $this->negrita=$n;
} 
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
	
	if($this->negrita[$i]==0){
		$this->SetTextColor(80);
	
	}else{	$this->SetTextColor(1);
	
	}
	
        $this->MultiCell($w,$this->ancho[$i],$data[$i],$this->celdas[$i],$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
//fin
function encabezado($cod_requisicion){
	$conexion=conexion();
	
	$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and (estatus='Revisar' or estatus ='Seleccionada') ORDER BY estatus ";
	$cotiza=query($consulta,$conexion);
	$this->SetFont('Arial','I',8);
	$this->SetWidths(array(25,18,60,52,52,53));
	$this->SetAligns(array('C','C','L','C','C','C'));
	$this->Setancho(array(5,5,5,5,5,5));
	$this->Setceldas(array(0,0,0,"LTR","LTR","LTR"));
	$this->Row(array("","","",utf8_decode("Proveedor Nº 1"),utf8_decode("Proveedor Nº 2"),utf8_decode("Proveedor Nº 3")));
	$this->SetFont('Arial','I',7);
	$true1=0;
	$true2=0;
	$true3=0;
	// nombre proveedor 1
	$cot=fetch_array($cotiza);
	$cod_provee=$cot['cod_proveedor'];
	if($cod_provee!=null){
		$consulta="select * from proveedores where cod_proveedor=$cod_provee";
		$provee=query($consulta,$conexion);
		$proveedor1=fetch_array($provee); 
		if($cot['estatus']=='Revisar'){
			$true1=1;
			$this->cod_cotizacion=$cot['codigo'];
			
		}
	}
	//nombre proveedor2
	$cot=fetch_array($cotiza);
	$cod_provee=$cot['cod_proveedor'];
	if($cod_provee!=null){
		$consulta="select * from proveedores where cod_proveedor=$cod_provee";
		$provee=query($consulta,$conexion);
		$proveedor2=fetch_array($provee);
		if($cot['estatus']=='Revisar'){
			$true2=1;
			$this->cod_cotizacion=$cot['codigo'];
		}
	}
	//nombre proveedor 3
	$cot=fetch_array($cotiza);
	$cod_provee=$cot['cod_proveedor'];
	if($cod_provee!=null){
		$consulta="select * from proveedores where cod_proveedor=$cod_provee";
		$provee=query($consulta,$conexion);
		$proveedor3=fetch_array($provee);
		if($cot['estatus']=='Revisar'){
			$true3=1;
			$this->cod_cotizacion=$cot['codigo'];
		}
	}
	
	$this->Setceldas(array(0,0,0,"LBR","LBR","LBR"));
	$this->SetNegrita(array(0,0,0,$true1,$true2,$true3));
	$this->Row(array("","","",utf8_decode($proveedor1['compania']),utf8_decode($proveedor2['compania']),utf8_decode($proveedor3['compania'])));
	$true1=0;
	$true2=0;
	$true3=0;
	$this->SetWidths(array(25,18,60,26,26,26,26,26,27));
	$this->SetAligns(array('C','C','L','C','C','C','C','C','C'));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5));
	$this->Setceldas(array(1,1,1,1,1,1,1,1,1));
	$this->SetFont('Arial','B',8);
	$this->Row(array(utf8_decode("Código"),"Cantidad",utf8_decode("Descripción"),"Precio Unitario","Precio Total","Precio Unitario","Precio Total","Precio Unitario","Precio Total"));
	$this->Ln(5);
}

function imprimir_analisis($cod_requisicion,$des_tipo,$pdf){

	$conexion=conexion();
	$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and (estatus='Revisar' or estatus ='Seleccionada') ORDER BY estatus ";
	$cotiza1=query($consulta,$conexion);
	$cot1=fetch_array($cotiza1);
	$cod_cotizacion=$cot1['codigo'];
	
	$consulta2="select * from analisis_cotizaciones where cod_cotizacion=$cod_cotizacion";
	$ra=query($consulta2,$conexion);
	$raf=fetch_array($ra);

	$this->Cell(35);
        $this->Cell(170,7,utf8_decode("ANALISIS DE COTIZACIONES"),0,0,"C");
     	$this->Cell(24,7,"Fecha: ".fecha($raf['fecha_analisis']),0,0);
     	$this->Ln(5);		
	$consulta="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,
			r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro,r.tipo FROM requisiciones r,centros c WHERE r.cod_requisicion=".$cod_requisicion." and r.cod_centro=c.cod_centro";
	$req=query($consulta,$conexion);
	$requisicion=fetch_array($req);

	$this->Ln(20);
	$this->SetFont('Arial','I',12);
	$this->MultiCell(260,7,"Material Requerido por:    ".$requisicion['des_centro'],0,"L");
	$this->Cell(260,7,utf8_decode("Requisición Nº:    ").$requisicion['cod_requisicion'],0,1,"L");

	$pdf->encabezado($cod_requisicion);
	
	//-- consulta de los materiales de esta requisicion-->
	$consulta="select * from  requisiciones_det where cod_requisicion=".$cod_requisicion." ORDER BY cod_requisicion_det ";
	$matereq=query($consulta,$conexion);
	$cantidad_registros=18;
	$num_paginas = obtener_num_paginas($consulta,$cantidad_registros);
	$total= num_rows($matereq);
	//comparar material cotizado con material requisicion e imprimir
	$boolmaterial=false;
	
	$cont=1;
	$contar=1;
	$conse=0;
	$sub_1=0;
	$sub_2=0;
	$sub_3=0;
	$iva_1=0;
	$iva_2=0;
	$iva_3=0;
	while ($total>=$contar)
	{
		$conexion=conexion();
		$materialreq=fetch_array($matereq);
		$consulta="select * from  cotizaciones where cod_requisicion=".$cod_requisicion." and (estatus='Seleccionada' or estatus='Revisar') ORDER BY estatus";
		$cotiza=query($consulta,$conexion);
		
		
		$contproveedor=0;
		$estatus="";
		$disable=false;
		
		$cot=fetch_array($cotiza);
		
		$cod_cot=$cot['codigo'];
		$imprimir1="";
		$imprimir2="";
		$imprimir3="";
		$true1=0;
		$true2=0;
		$true3=0;
		
		if($cod_cot!=null){
			$resul_1=$cot['total'];
			$consulta="select * from cotizaciones_detalles where cod_cotizacion=$cod_cot and cod_producto='".$materialreq['cod_material']."' and consecutivo=$conse";
			$cotiza_deta=query($consulta,$conexion);
			if (num_rows($cotiza_deta)!=0){
				$boolmaterial=true;
				$cotiza_detalles=fetch_array($cotiza_deta);
				$contproveedor+=1;
				$val=$cotiza_detalles['precio']*$cotiza_detalles['cantidad'];
				$de=$cotiza_detalles['precio'];
				$sub_1+=$val;
				$iva_1+=$cotiza_detalles['iva'];
				$imprimir11="$de";
				$imprimir12="$val";
				if ($cotiza_detalles['estatus']=="Revisar"){ $true1=1;}
			} else{ 
				$imprimir11="";
				$imprimir12="";
			} 
			if($cotiza_detalles['estatus']=='Revisar'){	$estatus="Revisar"; }
			$disable=false;
			if($cot['estatus']=='Revisar'){	$disable=true;	}
		}else{
			
				$imprimir11="";
				$imprimir12="";
		}
		$cot=fetch_array($cotiza);
		$cod_cot=$cot['codigo'];
		
		if($cod_cot!=null){
			$resul_2=$cot['total'];
			$consulta="select * from cotizaciones_detalles where cod_cotizacion=$cod_cot and cod_producto='".$materialreq['cod_material']."' and consecutivo=$conse";
			$cotiza_deta=query($consulta,$conexion);

			
			if (num_rows($cotiza_deta)!=0){
				$boolmaterial=true;
				$cotiza_detalles=fetch_array($cotiza_deta);
				$contproveedor+=1;
				$val=$cotiza_detalles['precio']*$cotiza_detalles['cantidad'];
				$de=$cotiza_detalles['precio'];
				$sub_2+=$val;
				$iva_2+=$cotiza_detalles['iva'];
				$imprimir21="$de";
				$imprimir22="$val";
				if ($cotiza_detalles['estatus']=="Revisar"){ $true2=1;}
			} else{ 
				$imprimir21="";
				$imprimir22="";
				
			} 
			
			if($cotiza_detalles['estatus']=='Revisar'){	$estatus="Revisar"; }
			$disable=false;
			if($cot['estatus']=='Revisar'){	$disable=true;	}
		}else{
			
				$imprimir21="";
				$imprimir22="";
		}
		$cot=fetch_array($cotiza);
		$cod_cot=$cot['codigo'];
		if($cod_cot!=null){
			$resul_3=$cot['total'];
			$consulta="select * from cotizaciones_detalles where cod_cotizacion=$cod_cot and cod_producto='".$materialreq['cod_material']."' and consecutivo=$conse";
			$cotiza_deta=query($consulta,$conexion);
			if (num_rows($cotiza_deta)!=0){
				$boolmaterial=true;
				$cotiza_detalles=fetch_array($cotiza_deta);
				$contproveedor+=1;
				$val=$cotiza_detalles['precio']*$cotiza_detalles['cantidad'];
				$de=$cotiza_detalles['precio'];
				$sub_3+=$val;
				$iva_3+=$cotiza_detalles['iva'];
				$imprimir31="$de";
				$imprimir32="$val";
				if ($cotiza_detalles['estatus']=="Revisar"){ $true3=1;}
			} else{ 
				$imprimir31="";
				$imprimir32="";
			} 
			if($cotiza_detalles['estatus']=='Revisar'){	$estatus="Revisar"; }
			$disable=false;
			if($cot['estatus']=='Revisar'){	$disable=true;	}
		}else{
	
				$imprimir31="";
				$imprimir32="";
		}
		$conse+=1;
	$this->SetNegrita(array(0,0,0,$true1,$true1,$true2,$true2,$true3,$true3));
	$this->SetWidths(array(25,18,60,26,26,26,26,26,27));
	$this->SetAligns(array('L','R','L','R','R','R','R','R','R'));
	$this->Setancho(array(5,5,5,5,5,5,5,5,5));
	$this->Setceldas(array(0,0,0,0,0,0,0,0,0));
	$this->SetFont('Arial','I',8);
	
	$this->Row(array(utf8_decode($materialreq['cod_material']),$materialreq['cantidad'],utf8_decode($materialreq['descripcion']),$imprimir11,number_format($imprimir12,2,',','.'),$imprimir21,number_format($imprimir22,2,',','.'),$imprimir31,number_format($imprimir32,2,',','.')));
	mysql_free_result($cotiza);
	$true1=0;
	$true2=0;
	$true3=0;
	//maximizar lineas
	if (strlen($materialreq['descripcion'])>50 && strlen($materialreq['descripcion'])<60){
		$cantidad_registros-=1;
		if($cont>$cantidad_registros){
			$cantidad_registros=$cont;
		}
		
	}
	if (strlen($materialreq['descripcion'])>60 ){
		$cantidad_registros-=2;
		if($cont>$cantidad_registros){
			$cantidad_registros=$cont;
		}
		
	}
		if($cont==$cantidad_registros)
		{		$cantidad_registros=18;
				$this->Ln(50);
				$this->Cell(35);
				$this->SetFont('Arial','B',12);
				$this->Cell(170,7,utf8_decode("ANALISIS DE COTIZACIONES"),0,0,"C");
				$this->Cell(24,7,"Fecha: ".date("d/m/Y"),0,0);
				$this->Ln(5);	
				$conexion=conexion();	
				$consulta="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,
						r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro,r.tipo FROM requisiciones r,centros c WHERE r.cod_requisicion=".$cod_requisicion." and r.cod_centro=c.cod_centro";
				$req=query($consulta,$conexion);
				$requisicion=fetch_array($req);
			
				$this->Ln(10);
				$this->SetFont('Arial','I',12);
				$this->MultiCell(260,7,"Material Requerido por:    ".$requisicion['des_centro'],0,"L");
				$this->Cell(260,7,utf8_decode("Requisición Nº:    ").$requisicion['cod_requisicion'],0,1,"L");
				
				$pdf->encabezado($cod_requisicion);
				$cont=1;

		
		}else{$cont++;}
		$contar++;
		
		
		
	}
	$this->Setceldas(array(0,0,0,'T','T','T','T','T','T'));
	$this->Row(array('','','','Sub-Total',number_format($sub_1,2,',','.'),'Sub-Total',number_format($sub_2,2,',','.'),'Sub-Total',number_format($sub_3,2,',','.')));
	$this->Setceldas(array(0,0,0,0,0,0,0,0,0));
	$this->Row(array('','','','Iva',number_format($iva_1,2,',','.'),'Iva',number_format($iva_2,2,',','.'),'Iva',number_format($iva_3,2,',','.')));
	$this->Row(array('','','','Total',number_format($resul_1,2,',','.'),'Sub-Total',number_format($resul_2,2,',','.'),'Sub-Total',number_format($resul_3,2,',','.')));
}




}


//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$conexion=conexion();
$cod_requisicion = @$_GET['cod_requisicion'];
$des_tipo = @$_GET['des_tipo'];


$pdf->imprimir_analisis($cod_requisicion,$des_tipo,$pdf);

//$pdf->imprimir_datos($nombreAgente,$nombreContribuyente,$rifAgente,$rifContribuyente,$codiva_nuevo,$periodo,$direccionAgente);

//$pdf->imprimir_tabla($pdf,$fila1);

$pdf->Output();
?>