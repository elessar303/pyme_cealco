<?php
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');

require_once '../lib/config.php';
require_once '../lib/pdfcommon.php';
require_once '../lib/common.php';
//include ("../selectra/header.php");

$cantidad_registros=13;

$conexion=conexion();
$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro'];
$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];

$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
$rs = query($consulta_req,$conexion);
$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);




class PDF extends FPDF
{
var $usuario;
var $id;
var $obser;
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
	//$var_imagen_der=$row_rs['imagen_der'];	
	$var_sql="select codigo,nomemp,departamento,presidente,periodo,cargo,nivel,desislr,ctaisrl,desiva,ctaiva,por_isv,compra,servicio,rif,nit,direccion,telefono,por_im,por_bomberos,lugar,sobregirop,autorizacionodp,claveodp,contrato,gas_dir from parametros";
	$rsu = query($var_sql,$Conn);
	$row_rsu = fetch_array($rsu);
	$var_nomemp=$row_rsu['nomemp'];

	cerrar_conexion($Conn);

        $this->SetFont("Arial","B",12);
     	$this->Image($var_imagen_izq,10,8,23);
     	$this->Ln();
     	$this->Cell(45);
        $this->Cell(100,8,utf8_decode($var_encabezado1),0,0,"C");
     	//$this->Image($var_imagen_der,170,10,30);
     	$this->Ln(6);
     	$this->Cell(35);
     	$this->Cell(120,8,utf8_decode($var_encabezado2),0,0,"C");
     	$this->Ln(6);
     	$this->Cell(10);
     	$this->Cell(170,8,utf8_decode($var_encabezado3),0,0,"C");
     	$this->Ln(7);
     	

}

//Pie de página
function Footer()
{
	$conexion=conexion();
	$coti_con="select * from cotizaciones where codigo=".$this->id;
	$rc=query($coti_con,$conexion);
	$rr=fetch_array($rc);
	$consulta_cot="SELECT * FROM cotizaciones_detalles  WHERE cod_cotizacion = '".$this->id."'  ORDER BY cod_producto";
	$rs = query($consulta_cot,$conexion);
	$subtotal=0;
	$iva=0;
	while($row_rscot = fetch_array($rs)){
		$subtotal+=$row_rscot['precio']*$row_rscot['cantidad'];
		$iva+=$row_rscot['iva'];
	}
	
    //Posición: a 1,5 cm del final

    $this->SetY(-57);
    //Arial italic 8
    $this->SetFont('Arial','B',10);
    $this->Cell(188,5,'Sub-Total:   '.number_format($subtotal,2,',','.'),0,1,'R');
    $this->Cell(188,5,'Total Iva:   '.number_format($iva,2,',','.'),0,1,'R');
    //$t=$iva+$subtotal;
    $t=$rr[8];
    
    $this->Cell(188,5,utf8_decode('Total Cotización:   ').number_format($t,2,',','.'),0,1,'R');

    $this->SetFont('Arial','I',9);
    $this->MultiCell(189,5,"Observaciones:   ".utf8_decode($this->obser),0,"L");
    //$this->Cell(0,9,'Elaborado Por: '.$this->usuario,0,0,'L');

    //Número de página
   // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
//Hacer que sea multilinea sin que haga un salto de linea
var $widths;
var $aligns;
var $celdas;
var $ancho;
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

function imprimir_datos($pagina,$num_paginas,$cod_requisicion,$id,$cod_centro)
{
		
		$conexion=conexion();
		$var_cotizaciones="SELECT * FROM cotizaciones WHERE codigo=".$id."";
		$rscot = query($var_cotizaciones,$conexion);
		$row_rscot = fetch_array($rscot);
		$this->usuario=$row_rscot['usuario'];
		$cod_proveedor=$row_rscot['cod_proveedor'];
		$this->id=$id;
		$this->obser=$row_rscot['observaciones'];
		$var_sql="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,
		r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro,r.tipo FROM requisiciones r,centros c WHERE r.cod_requisicion=".$cod_requisicion." and r.cod_centro=c.cod_centro";
		$rs = query($var_sql,$conexion);
		$row_rs = fetch_array($rs);

		
		$var_fecha=$row_rscot['fecha'];
		$var_concepto_req=$row_rs['concepto'];
		$var_tipo=$row_rs['tipo'];
		$var_situacion=$row_rs['situacion'];
		//$rs->close();
		
		$var_sqlu="SELECT * FROM proveedores WHERE cod_proveedor=$cod_proveedor";
		$rsu = query($var_sqlu,$conexion);		
		$row_rsu = fetch_array($rsu);
		$var_compania=$row_rsu['compania'];
		$var_direccion=$row_rsu['direccion'].$row_rsu['direccion2'];
		$var_rif=$row_rsu['rif'];
		$var_telefono=$row_rsu['telefonos'];
		$var_email=$row_rsu['email'];
		
	$fech=fecha($var_fecha);
	$this->SetFont('Arial','B',12);

	$this->Ln(5);
     	$this->Cell(10);
        $string=utf8_decode('COTIZACIÓN ');
	$string1=utf8_decode(' Número: ');
 	$this->Cell(150,10,$string.$var_nom_tipo.$string1.$id,0,0,'C');
	$string1=utf8_decode('Pág.: ');
   	$this->Cell(50,10,$string1.$pagina.'/'.$num_paginas,0,0,'L');
    	$this->Ln(10);
	$this->SetFont('Arial','B',10);
	$this->Cell(60,5,utf8_decode('Fecha de Emisión: '),1,0,"C");
	$this->Cell(70,5,utf8_decode('Nº Requisición: '),1,0,"C");
	$this->Cell(60,5,"Fecha: ",1,1,"C");
	$this->SetFont('Arial','I',10);
        // llamado para hacer multilinea sin que haga salto de linea
	$this->SetWidths(array(60,70,60));
	$this->SetAligns(array("C","C","C"));
        $this->Setceldas(array(1,1,1));
	$this->Setancho(array(5,5,5));
        $this->Row(array($fech,$cod_requisicion,date("d/m/Y")));
	// fin
	$this->SetFont('Arial','B',10);
	$this->Cell(100,5,' Proveedor o Suplidor ',1,0,"C");
	$this->Cell(45,5,' No. R.I.F. ',1,0,"C");
	$this->Cell(45,5,utf8_decode(' Teléfonos: '),1,1,"C");
	$this->SetFont('Arial','I',10);
	// llamado para hacer multilinea sin que haga salto de linea
	$this->SetWidths(array(100,45,45));
	$this->SetAligns(array("C","C","C"));
        $this->Setceldas(array(1,1,1));
	$this->Setancho(array(5,5,5));
        $this->Row(array(utf8_decode($var_compania),utf8_decode($var_rif),$var_telefono));
	// fin
	$this->SetFont('Arial','B',10);
	$this->Cell(110,5,utf8_decode(' Dirección: '),1,0,"C");
	$this->Cell(80,5,' Correo: ',1,1,"C");
	$this->SetFont('Arial','I',8);
	// llamado para hacer multilinea sin que haga salto de linea
	$this->SetWidths(array(110,80));
	$this->SetAligns(array("L","C"));
        $this->Setceldas(array(1,1));
	$this->Setancho(array(5,5));
        $this->Row(array(utf8_decode($var_direccion),utf8_decode($var_email)));
	// fin
	$this->SetFont('Arial','I',10);
	$string=utf8_decode('Concepto de la Requisición: ');
	$this->MultiCell(189,7,$string.utf8_decode($var_concepto_req),0,"L");
	$this->Ln(5);


}



function imprimir_tabla($id,$pdf,$pagina,$num_paginas,$cod_centro,$cod_requisicion){
	$cantidad_registros=16;
	$conexion=conexion();
	$consulta_cot="SELECT * FROM cotizaciones_detalles  WHERE cod_cotizacion = '$id'  ORDER BY cod_producto";
	$rs = query($consulta_cot,$conexion);
	$num_paginas=obtener_num_paginas($consulta_cot,$cantidad_registros);
	$total= num_rows($rs);
		

	
	
	//Cabecera
	$string=utf8_decode('Descripción');
	$this->Cell(93,7,$string,1,0,'C');
	$this->Cell(25,7,'Unidad',1,0,'C');
	$this->Cell(20,7,'Cantidad',1,0,'C');
	$this->Cell(25,7,'Precio',1,0,'C');
	$this->Cell(27,7,'Total',1,1,'C');
	//Datos
	$cont=1;
	$contar=1;
	$pagina=1;
	while ($total>=$contar)
	{
		$row_rs = fetch_array($rs);
		$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion =$cod_requisicion and cod_item='$row_rs[cod_producto]'";
		$rsreq = query($consulta_req,$conexion);
		$row_rsreq = fetch_array($rsreq);

	 // llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(93,25,20,25,27));
	$this->SetAligns(array('L','C','C','R','R'));
        $this->Setceldas(array(0,0,0,0,0));
	$this->Setancho(array(5,5,5,5,5));
	
	$t=$row_rsreq['cantidad']*$row_rs['precio'];
        $this->Row(array(utf8_decode($row_rsreq['descripcion']),utf8_decode($row_rsreq['medida']),$row_rsreq['cantidad'],number_format($row_rs['precio'],2,',','.'),number_format($t,2,',','.')));
	// fin
	//echo $cont;
	if($cont==$cantidad_registros)
	{	
		if ($pagina!=$num_paginas){
			$this->Ln(60);
			$pagina++;
			$pdf->imprimir_datos($pagina,$num_paginas,$cod_requisicion,$id,$cod_centro);
			//Cabecera
			$string=utf8_decode('Descripción');
			$this->Cell(93,7,$string,'TB',0,'C');
			$this->Cell(25,7,'Unidad','TB',0,'C');
			$this->Cell(20,7,'Cantidad','LTB',0,'C');
			$this->Cell(25,7,'Precio','TB',0,'C');
			$this->Cell(27,7,'Total','TRB',1,'C');
			$cont=1;
		}
	}else{$cont++;}
        //echo $contar;
	 $contar++;
	
	}
	


}
}


//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
//for($i=1;$i<=40;$i++)
  //  $pdf->Cell(0,10,'Imprimiendo línea número '.$i,0,1);
//

$conexion=conexion();
$cod_requisicion = (empty($_REQUEST['cod_requisicion'])) ? '' : $_REQUEST['cod_requisicion'];
$id = (empty($_REQUEST['codigo'])) ? '' : $_REQUEST['codigo'];
//$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];

$consulta_req="SELECT * FROM cotizaciones  WHERE cod_requisicion = '$cod_requisicion'  ORDER BY codigo";
$rs = query($consulta_req,$conexion);
$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);

$pagina=obtener_pagina_actual($pagina, $num_paginas);

//
$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$cod_requisicion'  ORDER BY cod_requisicion_det";
$rs = query($consulta_req,$conexion);
$row_rs = fetch_array($rs);
$cod_centro=$row_rs['cod_centro'];
$pdf->imprimir_datos($pagina++,$num_paginas,$cod_requisicion,$id,$cod_centro);

$pdf->imprimir_tabla($id,$pdf,$pagina,$num_paginas,$cod_centro,$cod_requisicion);

$pdf->Output();
?>
