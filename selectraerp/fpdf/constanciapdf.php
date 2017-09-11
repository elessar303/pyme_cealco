<?php 
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');
require_once '../lib/config.php';
require_once '../lib/common.php';

include('../lib/numerosALetras.class.php');

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
     	$this->Ln(15);
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
     	

}




function detalle(){
	$persona='JASMIN IBARRA ORTEGA';
	$cedula='14.534.003';
	$fecha='18/08/04';
	$cargo='ANALISTA DE RECURSOS HUMANOS II';
	$gerencia='GERENCIA GENERAL DE RECURSOS HUMANOS';
	$salarioletras='UN MIL SEISCIENTOS SESENTA Y CINCO BOLIVARES CON DOS CENTIMOS';
	$salario='1.665,02';	
	$this->Ln(10);
	$this->SetFont('Arial','B',12);
	$this->Cell(188,7,'CONSTANCIA',0,0,'C');
	$this->Ln(20);
	$this->SetFont('Arial','I',12);
	$contenido='     Quien suscribe, Gerente General de Recursos Humanos, hace constar por medio de la presente que la cuidadana '.$persona.' titular de la Cédula de Identidad Nº '.$cedula.' presta sus servicios para esta Empresa desde '.$fecha.', desempeña actualmente el cargo de '.$cargo.', adscrita a la'.$gerencia.', devengando un salario mensual de '.$salarioletras.' (Bs. F. '.$salario.').';

	$n = new numerosALetras();
	$dialetra=$n->convertirdia(date('d'));
	$añoletra=$n->convertirdia(date('Y'));

	$subcontenido='     Constancia que se expide a petición de la parte interesada, en Caracas a los '.$dialetra.' ('.date('d').') días del mes de '.mesaletras(date('m')).' del año '.$añoletra.' ('.date('Y').').';
	$this->Cell(10,7,'',0,0);
	$this->MultiCell(168,10,utf8_decode($contenido),0,'J');
	$this->Ln(10);
	$this->Cell(10,7,'',0,0);
	$this->MultiCell(168,10,utf8_decode($subcontenido),0,'J');
	$this->SetFont('Arial','B',12);
	$this->Ln(35);
	$this->Cell(188,7,'ABOG. NATHALY ROJAS M.',0,0,'C');
	$this->Ln();
	$this->SetY(-38);
	$this->SetFont('Arial','I',8);
	$this->Cell(188,10,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');
	
}
}
//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

	$pdf->detalle();
$pdf->Output();
?>