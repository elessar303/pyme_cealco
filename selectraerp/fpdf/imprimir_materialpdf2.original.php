<?php
if (!isset($_SESSION)) {
	session_start();
}

require('fpdfselectra.php');

class PDF extends FPDFSelectra
{
	function tHead() {
		$this->SetFont("Arial","I",9);
		$this->Cell(23, 7, utf8_decode('C�digo'), 'LTB', 0, 'C');
		$this->Cell(77, 7, utf8_decode('Descripci�n'), 'LTB', 0, 'C');
		$this->Cell(28, 7, 'Existencia', 'LTB', 0, 'C');
		$this->Cell(18, 7, utf8_decode('M�n.'), 'LTB', 0, 'C');
		$this->Cell(18, 7, utf8_decode('M�x.'), 'LTBR', 0, 'C');
		$this->Cell(18, 7, 'Precio', 'LTBR', 0, 'C');
		$this->Ln();
	}
	function imprimir_datos($nro_odp,$fila_odp, $moneda,$pdf)
	{
		$cantidad_registros=45;
		if (($cont+3)>$cantidad_registros){
			$this->Ln(60);
		}

		$this->tHead();
		/*$string=utf8_decode('C�digo');
		 $this->Cell(23,7,$string,'LTB',0,'C');
		$string=utf8_decode('Descripci�n');
		$this->Cell(77,7,$string,'LTB',0,'C');
		$string=utf8_decode('Cantidad Existente');
		$this->Cell(28,7,$string,'LTB',0,'C');
		$this->Cell(18,7,'Monto M�n.','LTB',0,'C');
		$this->Cell(18,7,utf8_decode('Monto M�x.'),'LTBR',0,'C');
		$this->Cell(18,7,utf8_decode('Precio.'),'LTBR',0,'C');*/

		$conexion=conexion();
		$rs = query("SELECT * FROM item i,item_existencia_almacen a WHERE i.id_item=a.id_item AND cod_item_forma=1 AND a.cantidad>0",$conexion);
		$totalwhile=num_rows($rs);
		if ($totalwhile==0){
			$this->SetY(-75);
			$this->Cell(188,7,'No hay materiales',0,0,'C');
		}

		$contar=1;
		$cantidad_registros=40;
		while ($totalwhile>=$contar)
		{
			$conexion=conexion();
			$row_rs = fetch_array($rs);
			$cont2=$cont2+1;
			//$var_snc=$row_rs[4];
			$var_codigo=$row_rs['cod_item'];#0
			$var_descrip=utf8_decode($row_rs['descripcion1']);
			$var_exi=number_format($row_rs['cantidad'],0,',','.');
			$var_min=number_format($row_rs[42],0,',','.');
			$var_max=number_format($row_rs[43],0,',','.');
			$var_precio=number_format($row_rs[29],0,',','.');
			$contador++;

			//$monto_3  = number_format($var_monto3,2,',','.');
			$this->SetFont("Arial","I",9);
			// llamado para hacer multilinea sin que haga salto de linea
			$this->SetWidths(array(0,23,77,28,18,18,18));
			$this->SetAligns(array('R','L','L','R','R','R','R'));
			$this->Setceldas(array(0,0,0,0,0));
			$this->Setancho(array(0,5,5,5,5,5,5));
			$this->Row(array($var_snc,$var_codigo,$var_descrip,$var_exi,$var_min,$var_max,$var_precio));

			if($cont==$cantidad_registros)
			{
				$this->Ln(70);
				/*$string=utf8_decode('C�digo');
				 $this->Cell(23,7,$string,'LTB',0,'C');
				$string=utf8_decode('Descripci�n');
				$this->Cell(77,7,$string,'LTB',0,'C');
				$string=utf8_decode('Cantidad Existente');
				$this->Cell(28,7,$string,'LTB',0,'C');
				$this->Cell(18,7,'Monto M�n.','LTB',0,'C');
				$this->Cell(18,7,utf8_decode('Monto M�x.'),'LTBR',0,'C');
				$this->Cell(18,7,utf8_decode('Precio.'),'LTBR',0,'C');*/
				$this->tHead();
				$cont=1;
			}
			else
			{
				$cont++;
				//echo $cont;
			}
			$contar++;
		}//fin del while
	}

	//Pie de página
	function Footer()
	{
		//Posición: a  cm del final
		$this->SetY(-15);
		// fin
		$this->SetFont('Arial','I',8);
		//Número de página
		$this->Cell(188,5,utf8_decode('P�gina ').$this->PageNo().'/{nb}',0,0,'C');
		$this->SetFont('Arial','I',8);
		$this->Ln();
		//$this->Cell(188,5,'Elaborado Por: '.$valor['usuario'],0,0,'L');
		//Número de página
		// $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
//Creación del objeto de la clase heredada
$pdf=new PDF();

$pdf->setTituloReporte('L I S T A D O  D E  E X I S T E N C I A S');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$conexion=conexion();

$tabla="item";
$consulta="select * from item where cod_item_forma=1";
$resultado=query($consulta,$conexion);
$codigo_snc = $_GET['codigo_snc'];

//$url="materiales_list";
//$modulo="Materiales";
//$titulos=array("Código","Descripción","Unidad","I.V.A.");
//$indices=array("0","1","2","13");

$Conn=conexion_conf();

$var_sql="select moneda,periodo from parametros";
$rsu = query($var_sql,$Conn);
$row_rsu = fetch_array($rsu);
$moneda=$row_rsu['moneda'];
$periodo=$row_rsu['periodo'];
cerrar_conexion($Conn);

$pdf->imprimir_datos($nro_odp,$fila_odp, $moneda,$pdf);
$pdf->Output();
?>
