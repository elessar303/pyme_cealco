<?
function validar_firma($string){
	$conexionconf=conexion_conf();
	$consulta="select * from firmas where cod_reporte='".$string."'ORDER BY orden_reporte";
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
function requisiciones($pdf){
	//Posici贸n: a 1,5 cm del final
    $pdf->SetY(-60);
    //Arial italic 8
    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(63,10,'Unidad Solicitante','LT',0,'C');
    $string=utf8_decode('Administraci贸n');
    $pdf->Cell(63,10,$string,'LT',0,'C');
	if($pdf->tipo=="Compras"){
    		$pdf->Cell(63,10,'Activos y Suministros','LTR',1,'C');
	}
	else{
		$pdf->Cell(63,10,'Servicios Generales','LTR',1,'C');
	}
    $pdf->Cell(63,20,'','L',0);
    $pdf->Cell(63,20,'','L',0);
    $pdf->Cell(63,20,'','LR',1);
    $pdf->Cell(63,10,'Firma y Sello','LB',0,'C');
    $pdf->Cell(63,10,'Firma y Sello','LB',0,'C');
    $pdf->Cell(63,10,'Firma y Sello','LBR',1,'C');
}
//predefinido en la alcaldia de san francisco
function ordenes_print($pdf){
	
	// Firmantes
    	$pdf->SetFont('Arial','I',6);
    	$pdf->SetWidths(array(47,47,47,47));
	$pdf->SetAligns(array('C','C','C','C'));
        $pdf->Setceldas(array('1','1','1','1'));
	$pdf->Setancho(array(5,5,5,5));
        $pdf->Row(array('REGISTRADO','APROBADO','APROBADO','RECIBIDO'));

	$pdf->Cell(47,10,'','LT',0);
	$pdf->Cell(47,10,'','LT',0);
	$pdf->Cell(47,10,'','LT',0);
	$pdf->Cell(47,10,'','LTR',1);
	$string=utf8_decode('Gerente General de Administraci贸n');
    // llamado para hacer multilinea sin que haga salto de linea
        $pdf->SetWidths(array(47,47,47,47));
	$pdf->SetAligns(array('C','C','C','C'));
        $pdf->Setceldas(array('1','1','1','1'));
	$pdf->Setancho(array(5,5,5,5));
        $pdf->Row(array('PRESUPUESTO','GERENTE','ALCALDE','PROVEEDOR(FIRMA Y SELLO)'));
	// fin
	
}
//predefinido en el CSB
function ordenes_print2($pdf){
	
	// Firmantes
	$pdf->SetFont('Arial','I',6);
	$pdf->Cell(47,10,'','LT',0);
	$pdf->Cell(47,10,'','LT',0);
	$pdf->Cell(47,10,'','LT',0);
	$pdf->Cell(47,10,'','LTR',1);
	$string=utf8_decode('Gerente General de Administraci贸n');
    // llamado para hacer multilinea sin que haga salto de linea
        $pdf->SetWidths(array(47,47,47,47));
	$pdf->SetAligns(array('C','C','C','C'));
        $pdf->Setceldas(array('1','1','1','1'));
	$pdf->Setancho(array(5,5,5,5));
	if($pdf->tipopdf=="Compras"){
    		$pdf->Row(array('Jefe Div. de Activos y Suministros','Gerente de Servicios Administrativos',$string,'Presidente'));
	}
	else{
		$pdf->Row(array('Jefe Div. Servicios Generales','Gerente de Servicios Administrativos',$string,'Presidente'));
	}
        
}
function odp_reporte ($pdf){
	 // Firmantes
	$pdf->SetFont('Arial','I',6);
	$pdf->SetWidths(array(74,38,38,38));
	$pdf->SetAligns(array('C','C','C','C',));
        $pdf->Setceldas(array('1','1','1','1'));
	$pdf->Setancho(array(5,5,5,5));
        $pdf->Row(array('REVISADO Y REGISTRADO','CONFORME','APROBADO','ENTREGADO'));

	$pdf->Cell(37,10,'','LT',0);
	$pdf->Cell(37,10,'','LT',0);
	$pdf->Cell(38,10,'','LT',0);
	$pdf->Cell(38,10,'','LT',0);
	$pdf->Cell(38,10,'','LTR',1);
    
    // llamado para hacer multilinea sin que haga salto de linea
        $pdf->SetWidths(array(37,37,38,38,38));
	$pdf->SetAligns(array('C','C','C','C','C'));
        $pdf->Setceldas(array('1','1','1','1','1'));
	$pdf->Setancho(array(5,5,5,5,5));
        $pdf->Row(array('CONTABILIDAD','PRESUPUESTO','GERENTE','ALCALDE','TESORERO'));
	
	
	// fin
	$this->SetFont('Arial','I',8);
    	//N煤mero de p谩gina
   	$this->Cell(188,5,utf8_decode('P谩gina ').$this->PageNo().'/{nb}',0,0,'C');
    	$this->SetFont('Arial','I',8);
	$this->Ln();
    	$this->Cell(188,5,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');
	

}
function nota_entrega($pdf){
	//Arial italic 8
    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(63,10,'Unidad Solicitante','LT',0,'C');
    $string=utf8_decode('Entregado Por');
    $pdf->Cell(63,10,$string,'LT',0,'C');
    $pdf->Cell(63,10,'Revisado Por','LTR',1,'C');
    $pdf->Cell(63,20,'','L',0);
    $pdf->Cell(63,20,'','L',0);
    $pdf->Cell(63,20,'','LR',1);
    $pdf->Cell(63,10,'Firma y Sello','LB',0,'C');
    $pdf->Cell(63,10,'Firma y Sello','LB',0,'C');
    $pdf->Cell(63,10,'Firma y Sello','LBR',1,'C');
}
function actas_almacen($pdf){

    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(63,10,'Proveedor','LT',0,'C');
    $string=utf8_decode('Entregado Por');
    $pdf->Cell(63,10,$string,'LT',0,'C');
    $pdf->Cell(63,10,'Revisado Por','LTR',1,'C');
    $pdf->Cell(63,20,'','L',0);
    $pdf->Cell(63,20,'','L',0);
    $pdf->Cell(63,20,'','LR',1);
    $pdf->Cell(63,10,'Firma y Sello','LB',0,'C');
    $pdf->Cell(63,10,'Firma y Sello','LB',0,'C');
    $pdf->Cell(63,10,'Firma y Sello','LBR',1,'C');
}
function odp($pdf){
	// Firmantes
	$pdf->SetFont('Arial','I',6);
	
	$pdf->Cell(37,10,'','LT',0);
	$pdf->Cell(37,10,'','LT',0);
	$pdf->Cell(37,10,'','LT',0);
	$pdf->Cell(37,10,'','LT',0);
	$pdf->Cell(38,10,'','LTR',1);
	
    // llamado para hacer multilinea sin que haga salto de linea
    	$pdf->SetWidths(array(37,37,37,37,38));
	$pdf->SetAligns(array('C','C','C','C','C'));
        $pdf->Setceldas(array('1','1','1','1','1'));
	$pdf->Setancho(array(5,5,5,5,5));
        $pdf->Row(array(utf8_decode('Jefe de Divisi贸n'),'Gerente','Gerente General','Div. de Egresos','Gerente'));
	$pdf->Cell(111,8,'Unidad Solicitante',1,0,'C');
	$pdf->Cell(75,8,'Gerencia de Finanzas',1,0,'C');
	$pdf->Ln();
	// fin
}
function cheque($pdf){

	$pdf->SetFont('Arial','B',8);
    	$pdf->Cell(188,5,'Gerencia de Finanzas',1,0,'C');
	$pdf->Ln();

	// Firmantes
	$pdf->SetFont('Arial','I',6);
	
	$pdf->Cell(63,20,'','LT',0);
	$pdf->Cell(63,20,'','LT',0);
	$pdf->Cell(62,20,'','LTR',0);
	$pdf->Ln();
	
    // llamado para hacer multilinea sin que haga salto de linea
        $pdf->SetWidths(array(63,63,62));
	$pdf->SetAligns(array('C','C','C'));
        $pdf->Setceldas(array('1','1','1'));
	$pdf->Setancho(array(5,5,5));
        $pdf->Row(array('Gerente de Finanzas','Div. Egresos Administrativo','Departamento de Caja'));
	// fin

}

function precomprometerpdf($pdf){
	
	
	
    // llamado para hacer multilinea sin que haga salto de linea
        $pdf->SetWidths(array(94,94));
	$pdf->SetAligns(array('C','C'));
        $pdf->Setceldas(array('1','1'));
	$pdf->Setancho(array(5,5));
	$pdf->Row(array('Realizado por: ','Autorizado por: '));
	
	$pdf->SetFont('Arial','I',6);
	$pdf->Cell(94,10,'',1,0);
	$pdf->Cell(94,10,'',1,1);
	
        
}
function decretos($pdf){
    //Posici髇: a 1,5 cm del final
    $pdf->SetY(-60);
    //Arial italic 8
    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(63,10,'Analista','LT',0,'C');
    $string=utf8_decode('Gerente General OPP');
    $pdf->Cell(63,10,$string,'LT',0,'C');
    $pdf->Cell(63,10,'Presidente','LT',0,'C');
    $pdf->Cell(63,20,'','L',0);
    $pdf->Cell(63,20,'','L',0);
    $pdf->Cell(63,20,'','LR',1);
    $pdf->Cell(63,10,'Firma y Sello','LB',0,'C');
    $pdf->Cell(63,10,'Firma y Sello','LB',0,'C');
    $pdf->Cell(63,10,'Firma y Sello','LBR',1,'C');
}

?>