<?php
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');

require_once '../selectra/lib/config.php';
require_once '../selectra/lib/pdfcommon.php';
require_once '../selectra/lib/common.php';
//include ("../selectra/header.php");
$cantidad_registros=25;


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
     	$this->Image("../selectra/imagenes/".$var_imagen_izq,10,8,33);
     	$this->Ln(20);
     	$this->Cell(45);
     	$this->Cell(100,8,$var_encabezado1,0,0,"C");
     	$this->Image("../selectra/imagenes/".$var_imagen_der,170,15,33);
     	$this->Ln(10);
     	$this->Cell(35);
     	$this->Cell(120,8,$var_encabezado2,0,0,"C");
     	$this->Ln(10);
     	$this->Cell(10);
     	$this->Cell(170,8,$var_encabezado3,0,0,"C");
     	$this->Ln(10);
     	$this->Cell(10);
     	$this->Cell(170,8,$var_encabezado4,0,0,"C");


}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-60);
    //Arial italic 8
    $this->SetFont('Arial','I',12);
    $this->Cell(63,10,'Unidad Solicitante','LT',0,'C');
    $this->Cell(63,10,'Administraci&oacute;n','LT',0,'C');
    $this->Cell(63,10,'Compras y Suministros','LTR',1,'C');
    $this->Cell(63,20,'','L',0);
    $this->Cell(63,20,'','L',0);
    $this->Cell(63,20,'','LR',1);
    $this->Cell(63,10,'Firma y Sello','LB',0,'C');
    $this->Cell(63,10,'Firma y Sello','LB',0,'C');
    $this->Cell(63,10,'Firma y Sello','LBR',1,'C');

    

    $this->Cell(0,10,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');

    //Número de página
   // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
//
function imprimir_datos($pagina,$num_paginas,$cod_centro,$id,$var_rows)
{
		$conexion=conexion();
		$var_sql="SELECT r.cod_requisicion,r.agregada_fecha,r.agregada_hora,r.estacion,
		r.descripcion,r.situacion,r.unidad,r.cod_centro,r.concepto,r.fecha,c.descripcion as des_centro,r.tipo FROM requisiciones r,centros c WHERE r.cod_requisicion=".$id." and r.cod_centro=c.cod_centro";
		$rs = query($var_sql,$conexion);
		$row_rs = fetch_array($rs);
		$var_fecha=$row_rs['fecha'];
		$var_nom_centro=$row_rs['des_centro'];
		$var_des=$row_rs['descripcion'];
		$var_unidad=$row_rs['unidad'];
		$var_centro=$row_rs['cod_centro'];
		$var_concepto_req=$row_rs['concepto'];
		$var_tipo=$row_rs['tipo'];
		$var_situacion=$row_rs['situacion'];
		//$rs->close();
		
		$var_sql="SELECT descripcion FROM unidades WHERE cod_unidad=$var_unidad";
		$rsu = query($var_sql,$conexion);
		$row_rsu = fetch_array($rsu);
		$var_nom_und=$row_rsu['descripcion'];
		
		$var_sql="SELECT descripcion FROM ordenes_tipos where cod_orden_tipo = $var_tipo";
		$rsu = query($var_sql,$conexion);
		$row_rsu = fetch_array($rsu);
		$var_nom_tipo=$row_rsu['descripcion'];
		//$rsu->close();
		
	$fech=fecha($var_fecha);
	$this->SetFont('Arial','B',12);
	$this->Ln(10);
     	$this->Cell(10);
 	$this->Cell(170,10,'REQUISICIÓN '.$var_nom_tipo.' Número: '.$id,1,'C');
   	$this->Cell(170,10,'Pág.: '.$pagina.'/'.$num_paginas,1,'C');
    	
	$this->SetFont('Arial','I',12);
	$this->Ln(10);
	$this->Cell(50,10,'Fecha de Emisión',0,'C');
	$this->Cell(50,10,'Unidad Solicitante',0,'C');
	$this->Cell(50,10,'Centro de Costo',1,'C');
	$this->Cell(50,10,$fech,0,'C');
	$this->Cell(50,10,$var_unidad.' - '.$var_nom_und,0,'C');
	$this->Cell(50,10,$var_centro.' - '.$var_nom_centro,1,'C');
   
	$this->Cell(170,10,'Concepto de la Requisici&oacute;n'.$var_concepto_req,1,1,'C');


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
$cod_centro = (empty($_REQUEST['cod_centro'])) ? '' : $_REQUEST['cod_centro'];
$id = (empty($_REQUEST['id'])) ? '' : $_REQUEST['id'];
$var_rows = (empty($_REQUEST['rows'])) ? '' : $_REQUEST['rows'];

$consulta_req="SELECT * FROM requisiciones_det  WHERE cod_requisicion = '$id'  ORDER BY cod_requisicion_det";
$rs = query($consulta_req,$conexion);
$num_paginas=obtener_num_paginas($consulta_req,$cantidad_registros);
$pagina=obtener_pagina_actual($pagina, $num_paginas);

//

$pdf->imprimir_datos($pagina++,$num_paginas,$cod_centro,$id,$var_rows);


$pdf->Output();
?>