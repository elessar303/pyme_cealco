<?php

if (!isset($_SESSION)) {
    session_start();
}
require('fpdfselectra.php');

class PDF extends FPDFSelectra {
	function tHead(){
		$this->SetFont("Arial", "B", 9);
		$this->Cell(30, 7, utf8_decode('Codigo'), 'LTB', 0, 'C');
		$this->Cell(80, 7, utf8_decode('Descripcion'), 'LTB', 0, 'C');
		$this->Cell(30, 7, utf8_decode('Existencias'), 'LTB', 0, 'C');
		$this->Cell(30, 7, 'Conteo', '1LTBR', 0, 'C');
		$this->Ln();
	}
    function imprimir_datos($nro_odp, $fila_odp, $moneda, $pdf) {
        $cantidad_registros =40;
        if (($cont + 3) > $cantidad_registros) {
            $this->Ln(60);
        }
        $this->tHead();
        $conexion = conexion();
        $rs = query("SELECT * FROM item i,item_existencia_almacen a WHERE i.id_item=a.id_item AND cod_item_forma=1 GROUP BY cod_item, cod_departamento", $conexion);
        $totalwhile = num_rows($rs);
        if ($totalwhile == 0) {
            $this->SetY(-75);
            $this->Cell(188, 7, 'No hay materiales', 0, 0, 'C');
        }

        $contar = 1;
        $cantidad_registros = 40; #27
        while ($totalwhile >= $contar) {
            $conexion = conexion();
            $row_rs = fetch_array($rs);
            $cont2 = $cont2 + 1;
            //$var_snc=$row_rs[4];
            $var_codigo = $row_rs['cod_item']; #0
            $var_descrip = utf8_decode($row_rs[2]);
            $var_exi = number_format($row_rs['cantidad'], 0, ',', '.');
            $contador++;

            //$monto_3  = number_format($var_monto3,2,',','.');
            $this->SetFont("Arial", "I", 9);
            // llamado para hacer multilinea sin que haga salto de linea
            $this->SetWidths(array(30, 80, 30, 30));
            $this->SetAligns(array('C', 'L', 'R', 'C'));
            $this->Setceldas(array(0, 0, 0, 0));
            $this->Setancho(array(5, 5, 5, 5));
            $this->Row(array($var_codigo, $var_descrip, $var_exi, "___________"), 1);

            if ($cont == $cantidad_registros) {
                $this->Ln(80);
                $this->tHead();
                $cont = 1;
            } else {
                $cont++;
            }
            $contar++;
        }//fin del while
    }

    //Pie de página
    function Footer() {
        //Posición: a  cm del final
        $this->SetY(-15);
        // fin
        $this->SetFont('Arial', 'I', 8);
        //Número de página
        $this->Cell(188, 5, utf8_decode('Pagina ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetFont('Arial', 'I', 8);
        $this->Ln();
        //$this->Cell(188,5,'Elaborado Por: '.$valor['usuario'],0,0,'L');
        //Número de página
        // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

}

//Creación del objeto de la clase heredada
$pdf = new PDF();

$pdf->setTituloReporte('T O M A  D E  I N V E N T A R I O  F I S I C O');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

$conexion = conexion();

$tabla = "item";
$consulta = "select * from item where cod_item_forma=1";
$resultado = query($consulta, $conexion);
$codigo_snc = $_GET['codigo_snc'];

//$url="materiales_list";
//$modulo="Materiales";
//$titulos=array("Código","Descripción","Unidad","I.V.A.");
//$indices=array("0","1","2","13");

$Conn = conexion_conf();

$var_sql = "select moneda,periodo from parametros";
$rsu = query($var_sql, $Conn);
$row_rsu = fetch_array($rsu);
$moneda = $row_rsu['moneda'];
$periodo = $row_rsu['periodo'];
cerrar_conexion($Conn);

$pdf->imprimir_datos($nro_odp, $fila_odp, $moneda, $pdf);
$pdf->Output();
?>
