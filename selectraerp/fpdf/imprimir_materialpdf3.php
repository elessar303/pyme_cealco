<?php

if (!isset($_SESSION)) {
    session_start();
}
require('fpdfselectra.php');
ob_end_clean();    header("Content-Encoding: None", true);
class PDF extends FPDFSelectra {
    function tHead($dpto, $moneda) {
        $this->SetFont("Arial", "B", 9);
        $this->Cell(20, 7, utf8_decode('Ubicacion'), 1, 0, 'C');
        $this->Cell(30, 7, utf8_decode('Cliente'),1, 0, 'C');
        $this->Cell(15, 7, utf8_decode('Producto'), 1, 0, 'C');
        $this->Cell(90, 7, utf8_decode('Descripcion'),1, 0, 'C');
        $this->Cell(15, 7, 'Cant. PL', 1, 0, 'C');
        $this->Cell(15, 7, 'Peso', 1, 0, 'C');
        $this->Cell(10, 7, 'Lote', 1, 1, 'C');
    }

    function imprimir_datos2() {
        $conexion = conexion();

        $filtros="";

        if($_GET['almacen']!=0){
            $filtros.=" AND v.cod_almacen=".$_GET['almacen']." ";
        }
        if($_GET['ubicacion']!=0){
            $filtros.=" AND v.id_ubicacion=".$_GET['ubicacion']." ";
        }
        if($_GET['cliente']!=0){
            $filtros.=" AND v.id_proveedor=".$_GET['cliente']." ";
        }
        if($_GET['item']!=0){
            $filtros.=" AND v.id_item=".$_GET['item']." ";
        }
        $rs2 = query("select sum(v.cantidad) as total_cantidad, sum(v.peso) as total_peso, i.descripcion1 as nombre_item, v.lote as lote, v.id_proveedor, v.id_ubicacion, v.id_item  FROM vw_existenciabyalmacen v, item i, clientes pro
        where i.id_item=v.id_item 
        AND v.id_proveedor=pro.id_cliente
        AND v.cantidad>0 
        AND ubicacion!='PISO DE VENTA'
        ".$filtros."
        GROUP BY i.codigo_barras, lote
        ORDER BY i.descripcion1, lote
        ", $conexion);

        $this->SetFont("Arial", "B", 9);
        $this->SetWidths(array(178));
        $this->SetAligns(array('R'));
        $this->Setceldas(array(0));
        $this->Setancho(array(5));
        $this->SetFont("Arial", "I", 9);

        while($row = fetch_array($rs2)){

            $conexion2 = conexion();
            $rs_fecha= query("SELECT vencimiento FROM kardex_almacen_detalle WHERE id_ubi_entrada=".$row['id_ubicacion']." AND lote=".$row['lote']." AND id_item=".$row['id_item']." GROUP BY id_ubi_entrada", $conexion2);

            $res_fecha=fetch_array($rs_fecha);

            $peso = number_format($row['total_cantidad'], 2, ',', '.');
            $cantidad = number_format($row['total_peso'], 2, ',', '.');
            $producto = utf8_decode($row['nombre_item']);

            if($res_fecha[0]==''){
                $vencimiento='Sin Fecha';
            }else{
                $vencimiento=date_create($res_fecha[0]);
            }
            $this->Cell(80, 7, $producto, 0, 0, 'L');
            $this->Cell(20, 7, $cantidad,0, 0, 'C');
            $this->Cell(15, 7, utf8_decode('UM'), 0, 0, 'C');
            $this->Cell(20, 7, $peso,0, 0, 'C');
            $this->Cell(20, 7, $row['lote'], 0, 0, 'C');
            if($res_fecha[0]!=''){
                $this->Cell(40, 7, date_format($vencimiento, 'd-m-Y'), 0, 1, 'C');
            }else{
                $this->Cell(40, 7,'Sin Fecha', 0, 1, 'C');
            }
        }

    }


    //Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(188, 5, utf8_decode('Pagina ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Ln();
    }

    function Header() {
        $Conn = conexion_conf();
        $var_sql = "SELECT * FROM parametros_generales";
        $rs = query($var_sql, $Conn);
        $row_rs = fetch_array($rs);

        $var_imagen_izq = "../../includes/imagenes/" . $row_rs['img_izq'];
        $var_imagen_der = "../../includes/imagenes/" . $row_rs['img_der'];
        $var_nomemp = $row_rs['nombre_empresa'];

        cerrar_conexion($Conn);
        $this->SetTextColor(0,0,0);
        $this->SetY(15);
        $this->SetLeftMargin(15);
        $this->SetFont("Arial", "B", 8);
        //$this->Image('../../includes/imagenes/pdval-logo.gif',10, 8, 20, 20);
        $this->Cell(0, 0, utf8_decode($var_nomemp), 0, 0, "C");
        $this->Ln(7);
        $this->Cell(0, 0, utf8_decode($row_rs['direccion']), 0, 0, "C");

        $this->Ln(3);
        $this->Cell(0, 0, $row_rs['id_fiscal'] . ": " . utf8_decode($row_rs['rif']) . " - Telefonos: " . utf8_decode($row_rs['telefonos']), 0, 0, "C");

        $this->SetFont("Arial", "I", 8);
        $this->Ln(3);
        $this->Cell(0, 0, 'Fecha de Emision: ' . date('d-m-Y'), 0, 0, "R");
        //$this->SetFont("Arial", "B", 8);
        $this->Ln(5);
        $this->SetX(14);
        $this->SetFont('Arial', 'B', 14);
        $this->Ln(1);
        $this->Cell(0, 0, $this->getTituloReporte(), 0, 0, "C");
        $this->SetLineWidth(0.1);
        $this->Ln(5);
        $this->SetFont("Arial", "B", 9);
        $this->Cell(80, 7, utf8_decode('Producto'), 1, 0, 'C');
        $this->Cell(20, 7, utf8_decode('Cantidad'),1, 0, 'C');
        $this->Cell(15, 7, utf8_decode('UM'), 1, 0, 'C');
        $this->Cell(20, 7, utf8_decode('Peso Neto'),1, 0, 'C');
        $this->Cell(20, 7, 'Lote', 1, 0, 'C');
        $this->Cell(40, 7, 'Fecha de Vencimiento', 1, 1, 'C');
        $this->Ln(1);
    }

}

//Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');

$pdf->setTituloReporte('L I S T A D O  D E  E X I S T E N C I A S');
$pdf->AliasNbPages();
$conexion = conexion();

$tabla = "item";
$consulta = "SELECT * FROM item WHERE cod_item_forma = 1;";
$resultado = query($consulta, $conexion);
$codigo_snc = $_GET['codigo_snc'];

$sql = "SELECT * FROM departamentos;";
$rs = query($sql, $conexion);

$lista_dptos = array();
$subtotal_dptos = array();
$pdf->AddPage();
$subtotal_dptos[] = $pdf->imprimir_datos2();
$lista_dptos[] = $fila["descripcion"];
$fila = "";

ob_end_clean();
$date=date('d-m-Y');
$pdf->Output('Existencia por Producto_'.$date.'.pdf', 'I');
?>
