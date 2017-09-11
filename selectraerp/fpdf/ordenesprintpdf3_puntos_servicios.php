<?php 
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdf.php';

function cantidad_registro($concep){
	
	if(strlen($concep)<=75)
	{
		return 18;
	}elseif(strlen($concep)>75){
		return 16;
	}
		
}

class PDF extends FPDF
{
var $tipopdf;
var $concep;

function calculo($cantidad){
	
	switch($cantidad){
		case 1:  return 185; break;
		case 2: return 180; break;
		case 3: return 175; break;
		case 4: return 170; break;
		case 5: return 165; break;
		case 6: return 160; break;
		case 7: return 155; break;
		case 8: return 150; break;
		case 9: return 145; break;
		case 10: return 140; break;
		case 11: return 135; break;
		case 12: return 130; break;
		case 13: return 125; break;
		case 14: return 120; break;
		case 15: return 115; break;
		case 16: return 110; break;
		case 17: return 105; break;
		case 18: return 100; break;
		case 19: return 95; break;
		case 20: return 90; break;
	}
}





var $idi;
var $cod;


// DATOS DEL PROVEEDOR


function tabla_datosheader($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf,$sal){
	

	$conexion=conexion();	
	$var_sql="select * from ordenes where codigo=".$id." ";
	$rs = query($var_sql,$conexion);
	$row_rs = fetch_array($rs);
	//$var_fecha=$row_rs['fecha'];
	$var_centro=$row_rs['centro_costo'];
	$var_concepto=$row_rs['concepto'];
	$var_unidad=$row_rs['unidad'];
	$var_centro=$row_rs['cod_centro'];
	$var_cod_requisicion=$row_rs['cod_requi'];
	$formapago=$row_rs['formapago'];
	$condicioncompra=$row_rs['condicioncompra'];
	$entrega=$row_rs['entrega'];
	$diasentrega=$row_rs['diasentrega'];
	$this->idi=$row_rs['usuario'];
	$contrato=$row_rs['numero_contrato'];
	$var_firma=$row_rs['fecha_firma'];
	$var_inicio=$row_rs['fecha_incio'];
	$var_terminacion=$row_rs['fecha_terminacion'];
	$var_paralizacion=$row_rs['fecha_paralizacion'];
	$var_reinicio=$row_rs['fecha_reinicio'];
	$var_prorroga=$row_rs['prorroga'];
	
	
	if($row_rs['estado']=="Anulado"){
		$this->Image("../imagenes/anulado.gif",10,60,180);
		$this->SetY(30);
	}
	$this->Ln(15);	
	$var_monto_orden=$row_rs['monto_orden'];
	$var_dias_credito=$row_rs['dias_credito'];
	$var_imponible=$row_rs['imponible'];
	$var_monto_iva=$row_rs['monto_iva'];
	$var_monto_excento=$row_rs['monto_excento'];
	$var_fecha=$row_rs['fecha'];
	$var_funcion=$row_rs['funcion'];
	$var_codigo=$row_rs['codigo_ref'];
	$var_tipo=$row_rs['tipo'];
	$var_estado=$row_rs['estado'];
	$var_cod_proveedor=$row_rs['cod_provee'];
	
	$var_sql="select * from requisiciones where cod_requisicion=".$var_cod_requisicion;
	$rq = query($var_sql,$conexion);
	$row_rq = fetch_array($rq);
	$var_fechar_req=$row_rq['fecha'];

		
	$var_sqlu="SELECT * FROM proveedores WHERE cod_proveedor=$var_cod_proveedor";
	$rsu = query($var_sqlu,$conexion);		
	$row_rsu = fetch_array($rsu);
	$var_compania=$row_rsu['compania'];
        $var_direccion=$row_rsu['direccion1'].$row_rsu['direccion2'];
	$var_rif=$row_rsu['rif'];
	$var_telefono=$row_rsu['rep_apellidos'];
	
	$var_sql="SELECT descripcion FROM unidades WHERE cod_unidad=".$var_unidad;
	$rsu = query($var_sql,$conexion);		
	$row_rsu = fetch_array($rsu);
	$var_nom_und=$row_rsu['descripcion'];
	 
	$rs = query("select descripcion from ordenes_tipos where cod_orden_tipo = '$var_tipo' ",$conexion);
	while ($row_rs = fetch_array($rs)) 
	{	
		//echo "Pase por aqui";	
		$var_nom_tipo=$row_rs['descripcion'];
	}
		//echo "Tipo de orden".$x_cod_orden_tipo_req;

	$monto_orden  = number_format($var_monto_orden,2,',','.');
	$this->SetFont('SanserifB','',7);
// 	$tope=$this->SetY-1;
// 	$this->SetY=$tope;
	//$this->Ln(2);
	
	$this->Ln(5);
	$this->Cell(188,5,'',0,1);
//	$this->Cell(30,5,$id,0,1);	
	//IDDOC
	list($ano,$mes,$dia)=split("-",$var_fecha,3);
	$this->Cell(157,5,'',0,0);
	$this->Cell(15,5,$dia,0,0,'C');
	$this->Cell(15,5,$mes,0,0,'R');
	$this->Cell(30,5,$ano,0,1,'C');
	//IDDOCFIS
	$this->Cell(158,5,'',0,0);
	$this->Cell(30,5,'',0,1);
	
	//fecha
// 	$this->SetFont('SanserifB','',8);
// 	$this->Cell(178,5,'',0,0);
// 	$this->Cell(20,5,fecha($var_fecha),0,1,'L');
	$this->SetY(40);

	$this->Ln(10);
	$this->Cell(89,5,'',0,0);
	$this->Cell(30,5,utf8_decode($var_rif),0,1,'L');
	
	$this->Cell(22,4,'',0,0);
	$this->Cell(150,4,utf8_decode($var_nom_und),0,1);
	$this->Ln(5);
	$this->SetFont('Sanserif','',8);
	$this->Cell(22,4,'',0,0);
	$this->Cell(158,4,utf8_decode($var_compania),0,1);
	
	

	$this->Cell(12,4,'',0,0);
	$this->SetFont('Sanserif','',6);
	$this->Cell(152,4,'Direccion: '.utf8_decode($var_direccion),0,0);
	$this->Cell(12,4,$var_cod_requisicion,0,1);
	$this->SetFont('Sanserif','',6);
	$this->Cell(10,5,'',0,0);
	$this->Cell(32,5,'Telefono: '.utf8_decode($var_telefono),0,0,'L');
	$this->SetFont('SanserifB','',7);
			
	$this->Ln(15);

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
//variable global para firmas dinamicas
var $pdff;
//variable global para tipo de orden
var $tipo;



function datos_partidas($id,$cont,$pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf)
{
		
		$this->SetFont('SanserifB','',8);
		$this->SetY(320);
		$conexion=conexion();
		$rs = query("SELECT * FROM cwpreejc where RecNoOrders = $id and Marca='X'",$conexion);
		$totalwhile=num_rows($rs);	
		$contar=1;
		$cantidad_registros=6;
		$cont=1;
		while ($totalwhile>=$contar ) 
		{ 
			$this->SetX(1);
			if($cantidad_registros>=0){
				$conexion=conexion();
				$row_rs = fetch_array($rs);
				$var_sector=$row_rs['Sector'];
				$var_programa=$row_rs['Programa'];
				$var_actividad=$row_rs['Actividad'];
				$var_partida=$row_rs['Partida'];
				$var_monto3=$row_rs['Monto'];
				$var_ordinal=$row_rs['ordinal'];
				$cont2=$cont2+1;
				$monto_3  = number_format($var_monto3,2,',','.');
				
				$this->SetX=$le;
				$this->SetWidths(array(15,26,23,30,26,23,55));
				$this->SetAligns(array('C','C','C','C','C','C','R'));
				$this->Setceldas(array(0,0,0,0,0,0,0));
				$this->Setancho(array(5,5,5,5,5,5,5));
				
				list($partida,$generica,$especifica,$subespecifica,$auxiliar)=explode(".",$var_partida);
				$this->Row(array($var_sector,$var_programa.' - '.$var_actividad,$partida.'.'.$generica,$especifica,$subespecifica,$auxiliar,$monto_3));
			}
			$cantidad_registros-=1;
			$contar++;
	
		}//fin del while
	
}
// contenido de la tabla
function tabla_datos($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf)
{
	$this->pdff=$pdf;
	$cantidad_registros=20;
	
	$cont=1;
	$conexion=conexion();
	$rs = query("SELECT * FROM ordenes_detalles  WHERE cod_ord = $id",$conexion);
	
	
	$subtotal=0;
	$totaliva=0;
	$totalexcento=0;
	$totalwhile=num_rows($rs);
	$contar=1;
	$this->SetFont('Sanserif','',8);
	$final=0;
   	while ($totalwhile>=$contar && $final==0) 
	{
		$this->SetX(1);
		$row_rs = fetch_array($rs);
		$conexion=conexion();
	  	$var_cod_productos=$row_rs['cod_pro'];
		
		$rsu = query("SELECT unidad,descripcion FROM materiales WHERE cod_material = '$var_cod_productos' ",$conexion);
		$row_rsu = fetch_array($rsu);
		$var_unidad_materiales=$row_rsu['unidad'];
		$var_descripcion_materiales=$row_rsu['descripcion'];
		
		
		$rso = query("SELECT monto_excento,imponible,monto_orden,monto_iva FROM ordenes WHERE codigo = $id",$conexion);
		$row_rso = fetch_array($rso);
		$var_monto_excento=$row_rso['monto_excento'];
		$var_monto_imponible=$row_rso['imponible'];
		$var_monto_orden=$row_rso['monto_orden'];
		$var_monto_iva=$row_rso['monto_iva'];
		
		//echo "Imponible".$var_monto_imponible;

	  	$var_precio=$row_rs['precio'];
		$var_iva=$row_rs['iva'];
		$var_total=$row_rs['total'];
		$var_total_mat=$row_rs['total_gen'];

	  	//$rso->close();
		//$descripcion= $row_rs['descripcion'];
		$cantidad= $row_rs['cantidad_pedida'];

		$precio=number_format($var_precio,2,",",".");
		$total=number_format($var_total,2,",",".");
		$iva=number_format($var_iva,2,",",".");
		$total_mat=number_format($var_total_mat,2,",",".");
		
		$monto_excento=number_format($var_monto_excento,2,",",".");
		$monto_imponible=number_format($var_monto_imponible,2,",",".");
		$monto_orden=number_format($var_monto_orden,2,",",".");
		$monto_iva=number_format($var_monto_iva,2,",",".");
		



		// llamado para hacer multilinea sin que haga salto de linea
       		 $this->SetWidths(array(20,20,86,35,20,28));
		$this->SetAligns(array('C','L','L','R','R','R'));
        	$this->Setceldas(array(0,0,0,0,0,0));
		$this->Setancho(array(4,4,4,4,4,4));
        	$this->Row(array($cantidad,utf8_decode($var_unidad_materiales),utf8_decode($var_descripcion_materiales),$var_precio,$iva,$total_mat));
		
		
		// para sacar el subtotal
		$subtotal=$subtotal+$var_total;
		//
		//para sacar el subtotaliva
		$totaliva=$totaliva+$var_iva;
		// fin
		//para sacar el subtotaliva
		if ($var_iva==0){
			$totalexcento=$totalexcento+$var_total;
		}
		// fin
		//para sacar totalfactura
		$totalfactura=$totalfactura+$var_total_mat;
		

		//maximizar lineas
		if (strlen($var_descripcion_materiales)>55){
			$cantidad_registros-=1;
			$cont++;
			if($cont>$cantidad_registros){
				$cantidad_registros=$cont;
			}
			
		}


		if($cont==$cantidad_registros)
		{
			$final=1;
			$this->SetFont('SanserifB','',10);
			$this->MultiCell(188,5,'ESTA ORDEN DE COMPRA CONTINUA EN EL SIGUIENTE ANEXO',0,'C');
			/*
			$this->Cell(100,5,'Van ...',0,1,'C');
			$salto=$this->calculo($cantidad_registros);
 			$this->Ln($salto);
			//mando a imprimir las partidas presupuestarias
			$this->Ln(8);
			$pdf->datos_partidas($id,$cont,$pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf);	
			$this->Ln(100);
			$cantidad_registros=18;
			
			$pdf->tabla_datosheader($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf,1);
			$this->Cell(158,5,'Vienen ....',0,1,'C');*/
			//$cont=1;
	
		}
		else
		{
			$cont++;
			//echo $cont;
		}
		$contar++;
		
	}
	
	if($cont<=$cantidad_registros )
	{
		
		$consulta_orden="select * from ordenes where codigo=$id";
		$query=query($consulta_orden,$conexion);
		$orden=fetch_array($query);
 		$salto=$this->calculo($cont);
		$this->Ln($salto);
// 		echo $cont;
		$this->Ln(3);
		$this->SetFont('SanserifB','',8);
		$this->Cell(140,4,'SUB-TOTAL',0,0,'R');
		$this->Cell(40,5,'',0,0);
		$this->Cell(25,4,number_format($orden['imponible']+$orden['monto_excento'],2,",","."),0,1,"R");
		$this->Cell(140,4,'IVA',0,0,'R');
		$this->Cell(40,4,'',0,0);
		$this->Cell(25,4,number_format($orden['monto_iva'],2,",","."),0,1,"R");
		$this->Cell(140,4,'TOTAL',0,0,'R');
		$this->Cell(40,4,'',0,0);
		$this->Cell(25,4,number_format($orden['monto_orden'],2,",","."),0,1,"R");
		$this->SetFont('Sanserif','',7);
		
    
      	}
	$this->Ln(15);
    	$pdf->datos_partidas($id,$cont,$pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf);	


}

}


//Creación del objeto de la clase heredada
$pdf=new PDF('P','mm','legal');
$pdf->AddFont('Sanserif','','TEACPSS_.php');
$pdf->AddFont('SanserifB','','TEACPSSB.php');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$cantidad_registros=16;
$conexion=conexion();
	$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro'];
	$desTipo = (empty($_REQUEST['desTipo'])) ? '' : $_REQUEST['desTipo'];
	$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
	$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];
	
	$consulta_req="SELECT * FROM ordenes_detalles  WHERE cod_ord =".$id;
	$rs = query($consulta_req,$conexion);

	//$consulta="select * from ordenes_tipos where cod_orden_tipo=$desTipo";
	//$rss=query($consulta,$conexion);
	//$rrs=fetch_array($rss);
	
	$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
	$pagina=obtener_pagina_actual($pagina,$num_paginas);
$pdf->cod=$id;

$pdf->tabla_datosheader($pagina,$num_paginas,$cod_centro,$id,$rrs['descripcion'], $var_rows,$pdf,0);
$pdf->tabla_datos($pagina,$num_paginas,$cod_centro,$id,$rrs['descripcion'], $var_rows,$pdf);

$pdf->Output();
?>