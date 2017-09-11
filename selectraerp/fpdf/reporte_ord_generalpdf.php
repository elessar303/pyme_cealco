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

        $this->SetFont("Arial","B",12);
     	$this->Image($var_imagen_izq,10,8,33);
     	$this->Ln(20);
     	$this->Cell(45);
        
     	$this->Cell(100,8,utf8_decode($var_encabezado1),0,0,"C");
     	$this->Image($var_imagen_der,170,15,33);
     	$this->Ln(10);
     	$this->Cell(35);
     	$this->Cell(120,8,utf8_decode($var_encabezado2),0,0,"C");
     	$this->Ln(10);
     	$this->Cell(10);
     	$this->Cell(170,8,utf8_decode($var_encabezado3),0,0,"C");
     	$this->Ln(10);
     	$this->Cell(10);
     	$this->Cell(170,8,utf8_decode($var_encabezado4),0,0,"C");
	$this->Ln(8);

}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    

    $this->Cell(0,10,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');

    //Número de página
   // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
//Hacer que sea multilinea sin que haga un salto de linea
var $widths;
var $aligns;

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
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
/// fin

function imprimir_datos($pagina,$num_paginas,$cod_centro,$var_rows,$var_sql,$centro,$tipo_orden,$situacion)
{
	$conexion=conexion();	
	$var_tipo_orden=$_GET['cod_orden_tipo'];
	$var_proveedor=$_GET['proveedor'];
	$var_estado=$_GET['estado'];
	$var_centro=$_GET['centro'];	
		//echo "CodCentro: ".$cod_centro." Centro: ".$centro;
		$rso = query($var_sql,$conexion);
   	  	while($row_rso = fetch_array($rso))
		{ 
	  		$var_codigo=$row_rso['cod_requi'];
			$var_fecha=fecha($row_rso['fecha']);
			$var_unidad=$row_rso['unidad'];
			$var_centro_costo=$row_rso['centro_costo'];
			$var_situacion=$row_rso['situacion'];
			$var_cod_provee=$row_rso['cod_provee'];
			$var_cod_requi=$row_rso['cod_requi'];
			$var_tipo=$row_rso['tipo'];
			
		}	
		//echo "Cod_Req: ".$var_codigo."<br> Consulta".$var_sql;
		$rs = query("SELECT descripcion FROM centros where cod_centro = '$var_centro_costo'",$conexion);
		
		while ($row_rs = fetch_array($rs)) 
		{		
			$var_nom_centro=$row_rs['descripcion'];		
		}
		//echo "Nomcentro".$var_nom_centro;
		//exit(0);	
		$var_sql="SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = '".$var_tipo."' ";
		$rsu = query($var_sql,$conexion);		
		$row_rsu = fetch_array($rsu);
		$var_nom_tipo=$row_rsu['descripcion'];		
		//echo "Centro".$centro;
		if($cod_centro==0){$var_centro_costo="TODOS";}else {$var_centro_costo=$var_centro_costo." - ".$var_nom_centro;} 

		if($tipo_orden<>"0"){$var_nom_orden=$var_nom_tipo;}else{$var_nom_orden="TODOS";}


	$this->SetFont('Arial','B',12);

	$this->Ln(5);
     	$this->Cell(10);
        $string=utf8_decode('LISTADO DE ORDENES DE '.$var_nom_tipo );
	
 	$this->Cell(150,10,$string,0,0,'C');
	$string1=utf8_decode('Pág.: ');
   	$this->Cell(50,10,$string1.$pagina.'/'.$num_paginas,0,0,'L');
    	


	$this->SetFont('Arial','I',10);
	$this->Ln(15);
	
	$this->Cell(50,10,'Centro de Costos',0,0,'C');
	$this->Cell(70,10,utf8_decode('Tipo de Requisición'),0,0,'C');
	$this->Cell(70,10,'Estado',0,0,'C');
        $this->Ln(8);
        // llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(50,70,70));
        $this->Row(array($var_centro_costo,$var_nom_orden,$var_estado));
	// fin

        $this->Ln(10);
	


}



function imprimir_tabla ($pagina,$num_paginas,$cod_centro,$var_rows,$var_sql,$centro,$tipo_orden,$situacion)
{
	$cantidad_registros=13;
	$conexion=conexion();
	
	$rs = query($var_sql,$conexion);

	$num_paginas=obtener_num_paginas($var_sql,$cantidad_registros);
	$total= num_rows($rs);


	//Cabecera
	$this->Cell(20,7,utf8_decode('Nº Control'),'LTB',0,'C');
	$this->Cell(20,7,'Numero','TB',0,'C');
	$this->Cell(20,7,'Fecha','TB',0,'C');
	$this->Cell(70,7,'Proveedor','TB',0,'C');
	$this->Cell(28,7,'Estado','TB',0,'C');
	$this->Cell(30,7,'Monto Orden','TRB',0,'C');
	$this->Ln();
	//Datos

	$cont=1;
	$contar=1;
	$pagina=1;
	$totalfinal=0;
	while ($total>=$contar)
	{
		$row_rs = fetch_array($rs);
		$rso = query("SELECT compania FROM proveedores where cod_proveedor = '".$row_rs['cod_provee']."'", $conexion);
		$row_rso = fetch_array($rso); 
		$var_nom_compania=$row_rso['compania'];		
		$var_centro=$row_rs['centro_costo'];
		$rsu = query("SELECT descripcion FROM centros where cod_centro = '".$var_centro."'",$conexion);
		$row_rsu = fetch_array($rsu); 
		$var_nom_centro=$row_rsu['descripcion'];		
		$var_tipo=$row_rs['tipo'];
		$rsa = query("SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = $var_tipo",$conexion);		
		$row_rsa = fetch_array($rsa);
		$var_nom_tipo=$row_rsa['descripcion'];

	
	 // llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(20,20,20,70,28,30));
	$this->SetAligns(array('C','C','C','L','C'));
        $this->Row(array( $row_rs['codigo_ref'],$row_rs['codigo'],fecha($row_rs['fecha']),$var_nom_compania,$row_rs['estado'],number_format($row_rs['monto_orden'],2,',','.')));
	$totalfinal+=$row_rs['monto_orden'];
	// fin
	//echo $cont;
	if($cont==$cantidad_registros)
	{	
		if ($pagina!=$num_paginas){
			$this->Ln(60);
			$pagina++;
			$pdf->imprimir_datos($pagina,$num_paginas,$cod_centro,$var_rows,$var_sql,$centro,$tipo_orden,$situacion);
			//Cabecera
			$this->Cell(20,7,utf8_decode('Nº Control'),'LTB',0,'C');
			$this->Cell(20,7,'Numero','TB',0,'C');
			$this->Cell(20,7,'Fecha','TB',0,'C');
			$this->Cell(70,7,'Proveedor','TB',0,'C');
			$this->Cell(28,7,'Estado','TB',0,'C');
			$this->Cell(30,7,'Monto Orden','TRB',0,'C');
			$this->Ln();
			$cont=1;
		}
	}else{$cont++;}
        //echo $contar;
	 $contar++;
	
	}
	$this->SetFont('Arial','B',8);
	$this->Cell(188,8,'Monto Total: '.number_format($totalfinal,2,',','.'),0,0,'R');
	$this->Ln();
	$this->SetFont('Arial','I',8);
	


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
$var_tipo_orden=$_GET['cod_orden_tipo'];
$var_proveedor=$_GET['proveedor'];
$var_estado=$_GET['estado'];
$var_centro=$_GET['centro'];
$var_bandera = false;

if (isset($var_proveedor) && isset($var_tipo_orden) && $var_proveedor<>'')
{
	if ($var_proveedor<>'0' && $var_proveedor<>'')
	{
		$rs = query("SELECT cod_proveedor,compania FROM proveedores  where cod_proveedor = '$var_proveedor' ",$conexion);
		while ($row_rs = fetch_array($rs)) 
		{
			$var_proveedor=$row_rs['compania'];
		}
		//$rs->close();
		if ($var_estado=='Todas')
		{
			$var_sql="select * from ordenes where cod_provee = '$var_proveedor' and tipo= '$var_tipo_orden' order by codigo_ref";			
		}
		else
		{
			$var_sql="select * from ordenes where cod_provee = '$var_proveedor' and tipo= '$var_tipo_orden' and estado = '$var_estado' order by codigo_ref";			
		}
	}
	else
	{
		if ($var_estado=='Todas')
		{
			$var_sql="select * from ordenes  where tipo= '$var_tipo_orden' order by codigo";
		}
		else
		{
			$var_sql="select * from ordenes  where estado = '$var_estado' and tipo= '$var_tipo_orden' order by codigo";
		}
		$var_proveedor="TODOS";
	}	
}
else
{
	if ($var_estado=='Todas' && $var_centro=='0')
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne   order by codigo";		
		$var_bandera=true;
	}
	if ($var_estado=='Todas' && $var_centro<>'0')
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne  where centro_costo = '$var_centro' order by codigo";		
		$var_bandera=true;
	}
	if ($var_estado<>'Todas' && $var_centro=='0')
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne where estado = '$var_estado' order by codigo";		
		$var_bandera=true;
	}
	if ($var_estado<>'Todas' && $var_centro<>'0')
	{
		$var_sql="select distinct(codigo),cod_req,fecha,tipo,centro_costo,unidad,estado from ordenes_ne where estado = '$var_estado' and centro_costo = '$var_centro' order by codigo";		
		$var_bandera=true;
	}
}

$rs = query($var_sql,$conexion);
$num_paginas=obtener_num_paginas($var_sql,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);

//

$pdf->imprimir_datos($pagina,$num_paginas,$cod_centro,$var_rows,$var_sql,$centro,$tipo_orden,$situacion);

$pdf->imprimir_tabla($pagina,$num_paginas,$cod_centro,$var_rows,$var_sql,$centro,$tipo_orden,$situacion);

$pdf->Output();
?>