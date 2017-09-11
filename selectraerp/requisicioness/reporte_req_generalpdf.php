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
	$this->SetFont('Arial','B',12);
        if($row_rsu['rif']=='G200081643'){
		
		$this->Image($var_imagen_izq,5,12,80,15);
		$this->Image($var_imagen_der,175,12,20,13);
		$this->Cell(65);
		$this->Cell(100,20,'REQUISICIONES',0,0,'C');
		$this->Ln();
	}else{
		
		$this->Image($var_imagen_izq,10,8,23);
		$this->Ln();
		$this->Cell(45);
		$this->Cell(100,8,utf8_decode($var_encabezado1),0,0,"C");
		$this->Image($var_imagen_der,170,10,30);
		$this->Ln(6);
		$this->Cell(35);
		$this->Cell(120,8,utf8_decode($var_encabezado2),0,0,"C");
		$this->Ln(6);
		$this->Cell(10);
		$this->Cell(170,8,utf8_decode($var_encabezado3),0,0,"C");
		$this->Ln(7);
	
	}

}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-60);
    //Arial italic 8
    $this->SetFont('Arial','I',12);
    $this->Cell(47,10,'Unidad Solicitante','LT',0,'C');
    $string=utf8_decode('Administración');
    $this->Cell(47,10,$string,'LT',0,'C');
    $this->Cell(47,10,'Compras y Suministros','LT',0,'C');
    $this->Cell(47,10,'Autorizado por:','LTR',1,'C');
    $this->Cell(47,20,'','L',0);
    $this->Cell(47,20,'','L',0);
    $this->Cell(47,20,'','L',0);
    $this->Cell(47,20,'','LR',1);
    $this->Cell(47,10,'Firma y Sello','LB',0,'C');
    $this->Cell(47,10,'Firma y Sello','LB',0,'C');
    $this->Cell(47,10,'Firma y Sello','LB',0,'C');
    $this->Cell(47,10,utf8_decode('Dirección General'),'LBR',1,'C');
    

    $this->Cell(0,10,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');

    //Número de página
   // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
//Hacer que sea multilinea sin que haga un salto de linea
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
/// fin
function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$centro,$tipo_orden,$situacion,$pdf)
{
	$conexion=conexion();
	$rso = query($var_sql,$conexion);
   	$row_rso = fetch_array($rso); 
	$var_codigo=$row_rso['cod_requisicion'];
	$var_fecha=fecha($row_rso['agregada_fecha']);
	$var_unidad=$row_rso['unidad'];
	$var_centro_costo=$row_rso['cod_centro'];
	$var_situacion=$row_rso['situacion'];
	$var_cod_provee=$row_rso['cod_provee'];
	$var_cod_requi=$row_rso['cod_requi'];
	$var_tipo=$row_rso['tipo'];
	$rs = query("SELECT descripcion FROM centros where cod_centro = '$var_centro_costo'",$conexion);
		
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_centro=$row_rs['descripcion'];		
	}
	$var_sql="SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = $var_tipo";
	$rsu = query($var_sql,$conexion);		
	$row_rsu = fetch_array($rsu);
	$var_nom_tipo=$row_rsu['descripcion'];		
	if($centro==0){$var_centro_costo="TODOS";}else {$var_centro_costo=$var_centro_costo." - ".$var_nom_centro;} 
	if($tipo_orden<>"0"){$var_nom_orden=$var_nom_tipo;}else{$var_nom_orden="TODOS";}
	if ($situacion =="Todas"){$var_estado="Todas";}else{$var_estado=$var_situacion;} 
	
	$fech=fecha($var_fecha);
	$this->SetFont('Arial','B',12);

	$this->Ln(5);
     	$this->Cell(10);
        $string=utf8_decode('LISTADO DE REQUISICIONES ');
	
 	$this->Cell(150,10,$string,0,0,'C');
	$string1=utf8_decode('Pág.: ');
   	$this->Cell(50,10,$string1.$pagina.'/'.$num_paginas,0,0,'L');
    	


	$this->SetFont('Arial','I',10);
	$this->Ln();
	
	$this->Cell(50,10,'Centro de Costos',0,0,'C');
	$this->Cell(70,10,utf8_decode('Tipo de Requisición'),0,0,'C');
	$this->Cell(70,10,'Estado',0,0,'C');
        $this->Ln();
        // llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(50,70,70));
        $this->Row(array($var_centro_costo,$var_nom_orden,$var_estado));
	// fin

        $this->Ln();
	


}



function imprimir_tabla ($pagina,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$centro,$tipo_orden,$situacion,$pdf)
{
	$cantidad_registros=13;
	$conexion=conexion();
	$var_tipo_orden=$_GET[tipo_orden];
	$var_estado=$_GET[estado];
	$var_centro=$_GET[centro];

	if ($var_tipo_orden<>"0" && $var_estado=="Todas" && $var_centro=="0")
	{
		$var_sql="select * from requisiciones where tipo = '$var_tipo_orden' order by cod_requisicion";	
	}
	if ($var_tipo_orden<>"0" && $var_estado<>"Todas" && $var_centro=="0")
	{
		$var_sql="select * from requisiciones where situacion = '$var_estado' and tipo = '$var_tipo_orden' order by cod_requisicion";	
	}
	if ($var_tipo_orden<>"0" && $var_estado<>"Todas" && $var_centro<>"0")
	{
		$var_sql="select * from requisiciones where cod_centro = '$var_centro' and situacion = '$var_estado' and tipo = '$var_tipo_orden' order by cod_requisicion";	
	}
	
	if ($var_tipo_orden<>"0" && $var_estado=="Todas" && $var_centro<>"0")
	{
		$var_sql="select * from requisiciones where cod_centro = '$var_centro' and tipo = '$var_tipo_orden' order by cod_requisicion";
	}
	
	if ($var_tipo_orden=="0" && $var_estado=="Todas" && $var_centro=="0")
	{
		$var_sql="select * from requisiciones order by cod_requisicion";
	}
	
	if ($var_tipo_orden=="0" && $var_estado=="Todas" && $var_centro<>"0")
	{
		$var_sql="select * from requisiciones where cod_centro = '$var_centro' order by cod_requisicion";		
	}
	
	if ($var_tipo_orden=="0" && $var_estado<>"Todas" && $var_centro=="0")
	{
		$var_sql="select * from requisiciones where situacion = '$var_estado' order by cod_requisicion";		
	}
	
	if ($var_tipo_orden=="0" && $var_estado<>"Todas" && $var_centro<>"0")
	{
		$var_sql="select * from requisiciones where situacion = '$var_estado' and cod_centro = '$var_centro' order by cod_requisicion";		
	}
	
	$rs = query($var_sql,$conexion);

	$num_paginas=obtener_num_paginas($var_sql,$cantidad_registros);
	$total= num_rows($rs);


	//Cabecera
	
	$this->Cell(7,7,utf8_decode('Nº'),'LTB',0,'C');
	$this->Cell(17,7,'Fecha','TB',0,'C');
	$this->Cell(15,7,'Tipo','TB',0,'C');
	$this->Cell(38,7,'Unidad','TB',0,'C');
	$this->Cell(28,7,'Centro de Costo','TB',0,'C');
	$this->Cell(73,7,'Concepto','TB',0,'C');
	$this->Cell(18,7,'Estado','TRB',0,'C');
	$this->Ln();
	//Datos

	$cont=35;
	$contar=1;
	$pagina=1;
	while ($total>=$contar)
	{
		$conexion=conexion();
		//echo $total;
		$row_rs = fetch_array($rs);
		$var_unidad=$row_rs['unidad'];
		$consulta_u="SELECT descripcion FROM unidades where cod_unidad = '".$var_unidad."'";
		$rso = query($consulta_u,$conexion);

		$row_rso = fetch_array($rso); 
		$var_nom_unidad=$row_rso['descripcion'];		
		
		$cod_centro=$row_rs['cod_centro'];
		$rsu = query("SELECT descripcion FROM centros where cod_centro='".$cod_centro."'",$conexion);
		$row_rsu = fetch_array($rsu); 
		$var_nom_centro=$row_rsu['descripcion'];
		
		$var_tipo=$row_rs['tipo'];
		$rsa = query("SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = '".$var_tipo."'",$conexion);		
		$row_rsa = fetch_array($rsa);
		$var_nom_tipo=$row_rsa['descripcion'];

	
	 // llamado para hacer multilinea sin que haga salto de linea
	
	
        $this->SetWidths(array(7,17,15,38,28,73,18));
	$this->Setceldas(array(0,0,0,0,0,0,0));
	$this->SetAligns(array('L','L','L','L','L','L'));
	$this->Setancho(array(5,5,5,5,5,5,5));
	
	$this->SetFont('Arial','I',8);
        
	$comparar=strlen($row_rs['concepto'])/35;
	$comparar=ceil($comparar);
	if(($comparar)>1){
		$cont-=$comparar;
	}
	if($cont>0){
		$this->Row(array($row_rs['cod_requisicion'],fecha($row_rs['agregada_fecha']),utf8_decode($var_nom_tipo),utf8_decode($var_nom_unidad),utf8_decode($var_nom_centro),utf8_decode($row_rs['concepto']),$row_rs['situacion']));
	}
	// fin
	//$this->Cell(50,7,$cont,0);
	//echo $cont;
	if($cont<=0)
	{	
			
			$this->Ln(300);
			
			$pdf->imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$var_centro,$var_tipo_orden,$var_estado,$pdf);
			//Cabecera
			$this->Cell(7,7,utf8_decode('Nº'),'LTB',0,'C');
			$this->Cell(17,7,'Fecha','TB',0,'C');
			$this->Cell(15,7,'Tipo','TB',0,'C');
			$this->Cell(38,7,'Unidad','TB',0,'C');
			$this->Cell(28,7,'Centro de Costo','TB',0,'C');
			$this->Cell(73,7,'Concepto','TB',0,'C');
			$this->Cell(18,7,'Estado','TRB',0,'C');
			$this->Ln();
			$cont=35;
		
	}else{$cont--;}
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

$var_tipo_orden=$_GET[tipo_orden];
$var_estado=$_GET[estado];
$var_centro=$_GET[centro];	

if ($var_centro<>'0')
{
	$rs = query("SELECT descripcion FROM centros where cod_centro = '".$var_centro."'",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_centro=$row_rs['descripcion'];		
	}
	//$rs->close();
}

if ($var_tipo_orden<>'TODOS')
{
	$rs = query("SELECT descripcion as nom_orden FROM ordenes_tipos where cod_orden_tipo = '".$var_tipo_orden."'",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{		
		$var_nom_orden=$row_rs['nom_orden'];		
	}
	//$rs->close();
}
	  

if ($var_tipo_orden<>"0" && $var_estado=="Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones where tipo = '$var_tipo_orden' order by cod_requisicion";	
}
if ($var_tipo_orden<>"0" && $var_estado<>"Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones where situacion = '$var_estado' and tipo = '$var_tipo_orden' order by cod_requisicion";	
}
if ($var_tipo_orden<>"0" && $var_estado<>"Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where cod_centro = '$var_centro' and situacion = '$var_estado' and tipo = '$var_tipo_orden' order by cod_requisicion";	
}

if ($var_tipo_orden<>"0" && $var_estado=="Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where cod_centro = '$var_centro' and tipo = '$var_tipo_orden' order by cod_requisicion";
}

if ($var_tipo_orden=="0" && $var_estado=="Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones order by cod_requisicion";
}

if ($var_tipo_orden=="0" && $var_estado=="Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where cod_centro = '$var_centro' order by cod_requisicion";		
}

if ($var_tipo_orden=="0" && $var_estado<>"Todas" && $var_centro=="0")
{
	$var_sql="select * from requisiciones where situacion = '$var_estado' order by cod_requisicion";		
}

if ($var_tipo_orden=="0" && $var_estado<>"Todas" && $var_centro<>"0")
{
	$var_sql="select * from requisiciones where situacion = '$var_estado' and cod_centro = '$var_centro' order by cod_requisicion";		
}

$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro']; 		
$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];

$rs = query($var_sql,$conexion);
$num_paginas=obtener_num_paginas($var_sql,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);

//

$pdf->imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$var_centro,$var_tipo_orden,$var_estado,$pdf);

$pdf->imprimir_tabla($pagina++,$num_paginas,$cod_centro,$id,$var_rows,$var_sql,$var_centro,$var_tipo_orden,$var_estado,$pdf);


$pdf->Output();
?>