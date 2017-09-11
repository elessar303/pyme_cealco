<?
function validar_firma($string){
	$conexionconf=conexion_conf();
	$consulta="select * from firmas where cod_reporte='".$string."' and modulo='Recursos Humanos'ORDER BY orden_reporte";
	$fir=query($consulta,$conexionconf);
	$can=num_rows($fir);
	if($can!=0){
		return true;
	}else{
		return false;
	}
}
function firma_dinamica($string,$pdf,$tamletra,$anblan){
	$conexionconf=conexion_conf();
	$consulta="select * from firmas where cod_reporte='".$string."'ORDER BY orden_reporte";
	$fir=query($consulta,$conexionconf);
	$can=num_rows($fir);
	$div=188/$can;
	
	$espacio=array($can);
	$aligns=array($can);
	$mlbr=array($can);
	$mlr=array($can);
	$mlbr=array($can);
	$sangria=array($can);
	$sangria2=array($can);
	
	for ($i=0;$i<$can;$i++){
		$espacio[$i]=$div;
		$aligns[$i]="C";
		$mltr[$i]="LTR";
		$mlr[$i]="LR";
		$mlbr[$i]="LBR";
		$sangria[$i]="5";
		$sangria2[$i]=$anblan;
	}
	
	$pdf->SetWidths($espacio);
	$pdf->SetAligns($aligns);
      	$pdf->Setancho($sangria);

	$pdf->Setceldas($mltr);
	$pdf->Setceldas($mlr);
	$pdf->Setceldas($mlbr);

	$pdf->SetFont('Arial','I',$tamletra);
	$v=0;
	while ($firma=fetch_array($fir)){
			$descripcion[$v]=utf8_decode($firma['descripcion']);
		$cargo[$v]=utf8_decode($firma['cargo_persona']);
		$nombre[$v]=utf8_decode($firma['nombre_persona']);
		$blanco[$v]=" ";
		$v++;
	}
	$pdf->Setceldas($mltr);
	$pdf->Row($descripcion);
	$pdf->SetFont('Arial','I',$tamletra);
	$pdf->Setceldas($mlr);
	$pdf->Setancho($sangria2);
	$pdf->Row($blanco);
	$pdf->Ln();
	$pdf->Ln(-5);
	$pdf->Setancho($sangria);
	$pdf->Setceldas($mltr);
	$pdf->Row($cargo);
	$pdf->Setceldas($mlbr);
	$pdf->Row($nombre);
	
	
}
function patronales($pdf){
	//Posición: a 1,5 cm del final
    $pdf->SetY(-50);
    //Arial italic 8
    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(63,5,'','LT',0,'C');
    $string=utf8_decode('Dir. General Administración');
    $pdf->Cell(63,5,'','LT',0,'C');
    $pdf->Cell(63,5,'','LTR',1,'C');
    $pdf->Cell(63,10,'','LR',0);
    $pdf->Cell(63,10,'','LR',0);
    $pdf->Cell(63,10,'','LR',1);
    $pdf->Cell(63,5,'Recursos Humanos','LBR',0,'C');
    $pdf->Cell(63,5,$string,'LBR',0,'C');
    $pdf->Cell(63,5,'Dir. General de Despacho','LBR',1,'C');
}

?>