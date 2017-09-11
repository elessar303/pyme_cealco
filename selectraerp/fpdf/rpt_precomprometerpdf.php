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
		return 26;
	}elseif(strlen($concep)>75){
		return 22;
	}
		
}

class PDF extends FPDF
{
var $tipopdf;
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
	$this->SetFont('Arial','B',12);
        if($row_rsu['rif']=='G200081643'){
		
		$this->Image($var_imagen_izq,5,12,80,15);
		$this->Image($var_imagen_der,175,12,20,13);
		$this->Cell(65);
		$this->Cell(100,20,'ORDENES',0,0,'C');
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





var $idi;
var $cod;


// DATOS DEL PROVEEDOR


function tabla_datosheader($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows){
	
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
	$tipo=$row_rs['tipo'];
	
	$var_sql="select * from ordenes_tipos where cod_orden_tipo=".$tipo." ";
	$rss = query($var_sql,$conexion);
	$row_tipo = fetch_array($rss);


	$this->idi=$row_rs['usuario'];
	
	if($row_rs['estado']=="Anulado"){
		$this->Image("../imagenes/anulado.gif",10,60,180);
		$this->SetY(30);
	}
		
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
	$this->SetFont("Arial","I",10);
	$ss=utf8_decode('Número de control N° ');
	$this->Cell(188,8,'ORDEN '.$row_tipo['descripcion'].' No.: '.$var_codigo.'          ESTADO: '.utf8_decode(strtoupper($desTipo)).'                 '.$ss.$id,1,0,"C");
	$pdf->tipopdf=$desTipo;
	$this->tipo=strtoupper($desTipo);
	$this->Ln();
	$this->SetFont('Arial','B',8);
	$this->Cell(70,5,' Proveedor o Suplidor ',1,0,"C");
	$this->Cell(63,5,' Unidad Solicitante ',1,0,"C");
	$this->Cell(35,5,' No. R.I.F. ',1,0,"C");
	$this->Cell(20,5,' Fecha Req.',1,1,"C");
	$this->SetFont('Arial','I',7);
	// llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(70,63,35,20));
	$this->SetAligns(array('L','C','C','C'));
        $this->Setceldas(array(1,0,1,1));
	$this->Setancho(array(10,5,10,10));
        $this->Row(array(utf8_decode($var_compania),utf8_decode($var_nom_und),$var_rif,fecha($var_fechar_req)));
	
	// fin
	
        $this->Ln(5);
	$this->SetFont('Arial','B',8);
	$this->Cell(128,5,utf8_decode('Dirección: '),1,0,"C");
	$ss=utf8_decode('Requisición No.');
	$this->Cell(30,5,$ss,1,0,"C");
	$ss=utf8_decode('Fecha de Emisión');
	$this->Cell(30,5,$ss,1,0,"C");
	$this->Ln();
	//
	
	$this->SetFont('Arial','I',8);
	
	// llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(128,30,30));
	$this->SetAligns(array('L','C','C'));
        $this->Setceldas(array(1,1,1));
	$this->Setancho(array(5,5,5));
        $this->Row(array(utf8_decode($var_direccion),$var_cod_requisicion,fecha($var_fecha)));
	
	// fin

	$this->Ln();

	$this->MultiCell(188,5,'Concepto: '.utf8_decode($var_concepto),0,'L');
	$this->concep=$var_concepto;
	
	

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


//Pie de página
function Footer()
{
   
    	//Posición: a  cm del final
	$this->SetY(-35);
	precomprometerpdf($this->pdff);
    	$this->SetFont('Arial','I',8);
    	$this->Cell(0,5,'Elaborado Por: '.$this->idi,0,1,'L');
	$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');

}
function datos_partidas($id,$cont,$pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf)
{
	$cantidad_registros=16;
	$this->pdff=$pdf;
	if ($cont+3>$cantidad_registros){
		$this->Ln(100);
		$pdf->tabla_datosheader($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf);
	}
		$this->SetFont("Arial","B",10);
		$this->Ln(2);
		$this->Cell(191,7,'PARTIDAS PRESUPUESTARIAS',1,0,'C');
		$this->Ln();
	
	
		$this->SetFont("Arial","I",9);
		$string=utf8_decode('Programática');
		$this->Cell(20,7,$string,'LTB',0,'C');
		$this->Cell(25,7,'Cuenta','LTB',0,'C');
		$this->Cell(15,7,'Ordinal','LTB',0,'C');
		$string=utf8_decode('Descripción de la Cuenta');
		$this->Cell(62,7,$string,'LTB',0,'C');
		$this->Cell(24,7,'Monto Bs.F','LTB',0,'C');
		$this->Cell(20,7,'Analista','LTBR',0,'C');
		$this->Cell(25,7,'Fecha','LTBR',1,'C');
		
	
		$conexion=conexion();
		IF($desTipo=='PreComprometida')
		{	
			$rs = query("SELECT * FROM cwpreejc where RecNoOrders = $id and Marca='C'",$conexion);
		}else if($desTipo=='Comprometida')
		{
			$rs = query("SELECT * FROM cwpreejc where RecNoOrders = $id and Marca='X'",$conexion);
		}
		//$rs = query("SELECT * FROM cwpreejc where RecNoOrders = $id and Marca='C'",$conexion);
		$totalwhile=num_rows($rs);	
		$contar=1;
		$cantidad_registros=16;
		$cont=1;
		while ($totalwhile>=$contar) 
		{ 
			
			$conexion=conexion();
			$row_rs = fetch_array($rs);
			$cont2=$cont2+1;
			$var_sector=$row_rs['Sector'];
			$var_programa=$row_rs['Programa'];
			$var_actividad=$row_rs['Actividad'];
			$var_partida=$row_rs['Partida'];
			$var_monto3=$row_rs['Monto'];
			$var_usuario=$row_rs['usuario'];
			$var_ordinal=$row_rs['ordinal'];
			$var_fecha=$row_rs['Fecha'];
			$contador++;
			$rso = query("SELECT Denominacion FROM cwprecue where CodCue = '$var_partida'",$conexion);
			$row_rso = fetch_array($rso);
			$var_descripcion=$row_rso['Denominacion'];
			$monto_3  = number_format($var_monto3,2,',','.');
			
			$Conn=conexion_conf();
			$usu="select * from usuarios where LOG_USR='$var_usuario'";
			$ru=query($usu,$Conn);
			$Conn=cerrar_conexion($Conn);
			$rusr=fetch_array($ru);
			$this->SetFont("Arial","I",9);
			// llamado para hacer multilinea sin que haga salto de linea
			$this->SetWidths(array(20,25,15,62,24,25,25));
			$this->SetAligns(array('C','C','C','L','R','C','L'));
			$this->Setceldas(array(0,0,0,0,0,0,0));
			$this->Setancho(array(7,7,7,5,7,5,5));
			if($var_sector!='99'){
				$this->Row(array($var_sector.".".$var_programa.".".$var_actividad,$var_partida,$var_ordinal,utf8_decode($var_descripcion),$monto_3,$rusr['NOM_USU'],fecha($var_fecha)));
			}else{
				$this->Row(array($var_programa.".".$var_actividad,$var_partida,$var_ordinal,utf8_decode($var_descripcion),$monto_3,$rusr['NOM_USU'],fecha($var_fecha)));
			}
	
			if($cont==$cantidad_registros)
			{
				if ($contar!=$totalwhile){
					$this->Ln(100);
					$pdf->tabla_datosheader($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows);
					$this->SetFont("Arial","B",10);
					$this->Ln(2);
					$this->Cell(188,7,'PARTIDAS PRESUPUESTARIAS',1,0,'C');
					$this->Ln();
				
				
					$this->SetFont("Arial","I",9);
					$string=utf8_decode('Programática');
					$this->Cell(20,7,$string,'LTB',0,'C');
					$this->Cell(25,7,'Cuenta','LTB',0,'C');
					$this->Cell(15,7,'Ordinal','LTB',0,'C');
					$string=utf8_decode('Descripción de la Cuenta');
					$this->Cell(62,7,$string,'LTB',0,'C');
					$this->Cell(24,7,'Monto Bs.F','LTB',0,'C');
					$this->Cell(20,7,'Analista','LTBR',0,'C');
					$this->Cell(25,7,'Fecha','LTBR',1,'C');
					$cont=1;
				}
		
			}
			else
			{
				$cont++;
				//echo $cont;
			}
			$contar++;
		}//fin del while
	
}
// contenido de la tabla
function tabla_datos($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf)
{
	$this->pdff=$pdf;
	$cantidad_registros=cantidad_registro($this->concep);
	$cont=1;
	$conexion=conexion();
	$rs = query("SELECT * FROM ordenes_detalles  WHERE cod_ord = $id",$conexion);
	$this->Ln(2);
	$this->SetFont("Arial","B",10);
	$this->Cell(188,7,'DETALLES MATERIALES',1,0,'C');
	$this->Ln();

	$this->SetFont("Arial","I",9);
	$string=utf8_decode('Descripción del Item');
	$this->Cell(75,7,$string,'LTB',0,'C');
	$this->Cell(15,7,'Cantidad','LTB',0,'C');
	$this->Cell(16,7,'Unidad','LTB',0,'C');
	$this->Cell(24,7,'Precio Unitario','LTB',0,'C');
	$this->Cell(19,7,'Sub Total','LTB',0,'C');
	$this->Cell(19,7,'I.V.A ','LTB',0,'C');
	$this->Cell(20,7,'Total',1,0,'C');
	$this->Ln();
	$subtotal=0;
	$totaliva=0;
	$totalexcento=0;
	$totalwhile=num_rows($rs);
	$contar=1;
   	while ($totalwhile>=$contar) 
	{
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
		$this->SetFont("Arial","I",7);
		// llamado para hacer multilinea sin que haga salto de linea
       		 $this->SetWidths(array(75,15,16,24,19,19,20));
		$this->SetAligns(array('L','C','C','C','C','C','R'));
        	$this->Setceldas(array(0,0,0,0,0,0,0));
		$this->Setancho(array(7,7,7,7,7,7,7));
        	$this->Row(array(utf8_decode($var_descripcion_materiales),$cantidad,$var_unidad_materiales,$precio,$total,$iva,$total_mat));
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
			if($cont>$cantidad_registros){
				$cantidad_registros=$cont;
			}
			
		}
		
		if($cont==$cantidad_registros)
		{

			$this->SetY(-75);
			$cantidad_registros=cantidad_registro($this->concep);
			$this->Ln(70);
			$pdf->tabla_datosheader($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf);
			$this->SetFont("Arial","B",10);
			$this->Cell(188,7,'DETALLES MATERIALES',1,0,'C');
			$this->Ln();
			
			$this->SetFont("Arial","I",9);
			$string=utf8_decode('Descripción del Item');
			$this->Cell(75,7,$string,'LTB',0,'C');
			$this->Cell(15,7,'Cantidad','LTB',0,'C');
			$this->Cell(16,7,'Unidad','LTB',0,'C');
			$this->Cell(24,7,'Precio Unitario','LTB',0,'C');
			$this->Cell(19,7,'Sub Total','LTB',0,'C');
			$this->Cell(19,7,'I.V.A ','LTB',0,'C');
			$this->Cell(20,7,'Total',1,0,'C');
			$this->Ln();
			$cont=1;
	
		}
		else
		{
			$cont++;
			//echo $cont;
		}
		$contar++;
		
	}
	if($cont<=$cantidad_registros)
	{
		$this->Ln(2);
		$this->SetFont("Arial","I",9);
		$this->Cell(130,7,'Sub-Total   ',0,0,"R");
		$subtotal1=number_format($subtotal,2,",",".");
		$this->Cell(24,7,$subtotal1,0,1,"R");
      		$this->Cell(130,7,'Total I.V.A.  ',0,0,"R");
		$totaliva1=number_format($totaliva,2,",",".");
		$this->Cell(24,7,$totaliva1,0,1,"R");
		$this->Cell(130,7,'Total Excento  ',0,0,"R");
		$totalexcento1=number_format($totalexcento,2,",",".");
		$this->Cell(24,7,$totalexcento1,0,1,"R");
		$this->SetFont("Arial","B",9);
		$this->Cell(130,7,'Total General  Bs.F  ',0,0,"R");
		$totalfactura1=number_format($totalfactura,2,",",".");
		$this->Cell(24,7,$totalfactura1,0,1,"R");
		$cont+=5;
    
      	}
    	$pdf->datos_partidas($id,$cont,$pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf);	


}

}


//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$cantidad_registros=10;
$conexion=conexion();
	$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro'];
	$desTipo = (empty($_REQUEST['desTipo'])) ? '' : $_REQUEST['desTipo'];
	$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
	$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];
	
	$consulta_req="SELECT * FROM ordenes_detalles  WHERE cod_ord =".$id;
	$rs = query($consulta_req,$conexion);
	
	$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
	$pagina=obtener_pagina_actual($pagina,$num_paginas);
$pdf->cod=$id;

$pdf->tabla_datosheader($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows);
$pdf->tabla_datos($pagina,$num_paginas,$cod_centro,$id,$desTipo, $var_rows,$pdf);

$pdf->Output();
?>