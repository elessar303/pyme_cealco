<?php 
if (!isset($_SESSION)) {
  session_start();
}
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");


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
        
     	$this->Cell(100,8,$var_encabezado1,0,0,"C");
     	$this->Image($var_imagen_der,170,15,33);
     	$this->Ln(10);
     	$this->Cell(35);
     	$this->Cell(120,8,$var_encabezado2,0,0,"C");
     	$this->Ln(10);
     	$this->Cell(10);
     	$this->Cell(170,8,$var_encabezado3,0,0,"C");
     	$this->Ln(10);
     	$this->Cell(10);
     	$this->Cell(170,8,$var_encabezado4,0,0,"C");
	$this->Ln(8);

}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-60);
    //Arial italic 8
    $this->SetFont('Arial','I',12);
    $this->Cell(63,10,'Unidad Solicitante','LT',0,'C');
    $string=utf8_decode('Administración');
    $this->Cell(63,10,$string,'LT',0,'C');
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
}


//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);


$pdf->Output();
?>