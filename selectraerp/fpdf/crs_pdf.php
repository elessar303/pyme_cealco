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
function Footer(){

	$this->SetFont('Arial','B',10);
	$this->Ln(35);
	$this->Cell(188,7,'Atentamente',0,0,'C');
	$this->Ln(10);

	
	$Conn=conexion_conf();
	$conOCS = 'select * from parametros';
	$resOCS = query($conOCS,$Conn);
	$filaOCS = fetch_array($resOCS);
	$nom_pers=$filaOCS['pers_adm'];
	$this->SetFont('Arial','I',10);
	$this->Ln();
	
	$this->Cell(188,5,$nom_pers,0,1,'C');
	$this->Cell(188,5,utf8_decode('Departamento Administración'),'',1,'C');
	$this->Cell(188,5,utf8_decode($filaOCS['nomemp']),'',1,'C');
	$this->SetY(-15);
	$this->SetFont('Arial','I',8);
	$this->Cell(188,10,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');

}

function detalle($nom_prove,$req,$tipo){

	$conexion=conexion();
	$n = new numerosALetras();
	$dialetra=$n->convertirdia(date('d'));
	$añoletra=$n->convertirdia(date('Y'));
	
	$Conn=conexion_conf();
	$conOCS = 'select * from parametros';
	$resOCS = query($conOCS,$Conn);
	$filaOCS = fetch_array($resOCS);

	$nom_empre=$filaOCS['nomemp'];
	$direccion=$filaOCS['direccion'];
	$ciudad=$filaOCS['ciudad'];
	$estado=$filaOCS['estado'];
	$telefono=$filaOCS['telefonofax'];
	$nom_pers=$filaOCS['pers_adm'];

	$this->Ln(5);
	$this->SetFont('Arial','B',10);
	$this->Cell(188,7,utf8_decode($ciudad.', '.date('d').' de '.mesaletras(date('m')).' del año '.date('Y').'.'),0,1,"R");//fecha
	$this->Cell(10,5,'',0,0);
	$this->Cell(178,7,utf8_decode('Señores:'),0,1,'L');
	$this->Cell(10,5,'',0,0);
	$this->Cell(10,5,'',0,0);
	$this->MultiCell(168,10,utf8_decode($nom_prove),0,'L');
	$this->Cell(10,5,'',0,0);
	$this->Cell(178,7,utf8_decode('Presente.-'),0,1,'L');
	$this->Ln(10);
	$this->SetFont('Arial','I',10);
	$contenido='     A través de la presente me dirijo a Ud., en la oportunidad de infomarle que este '.$nom_empre. ' remite a Ud., Requisición de '.$tipo.' Nº '.$req.', a los fines de su consideración para ofertar, en caso de estar interesado se le agradece enviar la propuesta correspondientes, la cual deberá cumplir con los parámetros exigidos en los artículos 45 y 74 de la Ley de Contrataciones Públicas, a las Oficinas ubicadas en la '.$direccion.', '.$ciudad.', '.$estado.'; o en su defecto mediante faz por el numero teléfonico '.$telefono.' a nombre '.$nom_pers.' - Jefe de Administración.';

	$subcontenido='     Así mismo, cabe destacar que para cumplir con el compromiso de responsabilidad social, exigible tanto en la referida Ley de Contrataciones como en el Decreto de Medidas Temporales para la promición y desarrollo de las Pequeñas y Medianas Industrias (PyMIS) Cooperativas y otras formas asociativas, será necesario un aporte en dinero equivalente al 2.5% del valor total de la oferta, excluyendo lo relativo al Impuesto al Valor Agregado (IVA), lo cual será destinado para los programas desarrollados por el Estado a Instituciones sin fines de lucro.';
	$this->Cell(10,5,'',0,0);
	$this->MultiCell(168,10,utf8_decode($contenido),0,'J');
	$this->Ln(5);
	$this->Cell(10,5,'',0,0);
	$this->MultiCell(168,10,utf8_decode($subcontenido),0,'J');
	
	
	
}
}
//Creación del objeto de la clase heredada

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',10);

$req=$_GET['req'];
$prov=$_GET['prov'];

$conexion=conexion();
$conOCS = 'select * from proveedores where cod_proveedor='.$prov;
$resOCS = query($conOCS,$conexion);
$filaOCS = fetch_array($resOCS);

$cont = 'select * from ordenes_tipos INNER JOIN requisiciones ON requisiciones.cod_requisicion='.$req.' and requisiciones.tipo=ordenes_tipos.cod_orden_tipo';
$rest = query($cont,$conexion);
$filat = fetch_array($rest);

$pdf->detalle($filaOCS['compania'],$req,$filat[1]);

$pdf->Output();
?>