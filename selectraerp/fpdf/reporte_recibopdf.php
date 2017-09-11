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
		$this->Cell(100,20,'RECIBO DE PAGO',0,0,'C');
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




function detalle(){

	$codigo=$_GET['codigo'];
	$chequera=$_GET['chequera'];
	$cuenta=$_GET['cuenta'];
	$conexion = conexion();
	$consulta1 = "SELECT * FROM ordenes_pago WHERE cheque='".$codigo."' and chequera='".$chequera."' and cuenta='".$cuenta."'";
	$resultado1 = query($consulta1, $conexion);
	$fila1 = fetch_array($resultado1);
	$nombre = $fila1['bene'];
	$monto = $fila1['monto'];
	$concepto = $fila1['concepto'];
	$rif = $fila1['rif'];
	
	
	list($anio,$mes,$dia)=explode("-",$fechaini);
   	$fechaini= $dia."/".$mes."/".$anio; 
	$n = new numerosALetras();
	$cantidad=$n->convertir($monto);

	
	$salario=number_format($monto,2,',','.');
	
	$conexion=conexion();
	$consulta="select * from cheques where cheque=$codigo";
	$rc=query($consulta,$conexion);
	$rrc=fetch_array($rc);
	

	if($rrc['consecutivo_RP']!=0){
		$consecutivo=$rrc['consecutivo_RP'];
		if(strlen($consecutivo) == 1)
			$consefinal = "00000".$consecutivo;
		elseif(strlen($consecutivo) == 2)
			$consefinal = "0000".$consecutivo;
		elseif(strlen($ivasc) == 3)
			$consefinal = "000".$consecutivo;
		elseif(strlen($ivasc) == 4)
			$consefinal = "00".$consecutivo;
		elseif(strlen($ivasc) == 5)
			$consefinal = "0".$consecutivo;
		$consefinal = date("Y").date("m").$consefinal;
	}else{
		//saco el consecutivo desde parametros
		$Conn=conexion_conf();
		$consulta="select * from parametros";
		$rp=query($consulta,$Conn);
		$rrp=fetch_array($rp);
		$consecutivo=$rrp['consecutivo_RP']+1;
		if(strlen($consecutivo) == 1)
			$consefinal = "00000".$consecutivo;
		elseif(strlen($consecutivo) == 2)
			$consefinal = "0000".$consecutivo;
		elseif(strlen($ivasc) == 3)
			$consefinal = "000".$consecutivo;
		elseif(strlen($ivasc) == 4)
			$consefinal = "00".$consecutivo;
		elseif(strlen($ivasc) == 5)
			$consefinal = "0".$consecutivo;

		
		$consefinal = date("Y").date("m").$consefinal;
		// modifico el consecutivo desde parametros
		$update="update parametros set consecutivo_RP=$consecutivo";
		$ru=query($update,$Conn);
		$conexion=conexion();
		$update="update cheques set consecutivo_RP=$consecutivo where cheque=$codigo";
		$ru=query($update,$conexion);
	}

	$this->Cell(188,5,utf8_decode('Nº Comprobante: ').$consefinal,0,1,'R');



	
	$this->Ln(10);
	$this->SetFont('Arial','B',12);
	$this->Cell(188,7,'RECIBO DE PAGO',0,0,'C');
	$this->Ln(20);
	$this->SetFont('Arial','I',12);

	

	$contenido='     Yo, '.$nombre.' hago constar por medio del presente que he recibido de la Administración de esta Organización la cantidad de '.$cantidad.' (Bs.F.'.$salario.'), por concepto de: '.$concepto;

	$this->Cell(10,7,'',0,0);
	$this->MultiCell(168,10,utf8_decode($contenido),0,'J');
	$this->Ln(20);
	$this->Cell(50);
	$this->Cell(28,5,'Firma:',0,0,'R');
	$this->Ln();
	$this->Cell(78);
	$this->Cell(58,5,'','B');
	$this->Ln();
	$this->Cell(78);
	$this->Cell(58,5,utf8_decode('Recibí Conforme'),0,0,'C');
	$this->Ln(10);
	$this->Cell(50);
	$this->MultiCell(100,5,'Nombre:  '.$nombre,0,'L');
	$this->Ln(5);
	$this->Cell(50);
	$this->Cell(78,5,'C.I. / R.I.F.: '.$rif,0,0,'L');
	$this->Ln(10);
	$this->Cell(50);
	$this->Cell(20,5,'Fecha:',0,0,'R');
	$this->Cell(38,5,' _____ / _____ / _________',0,0);

	

	
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