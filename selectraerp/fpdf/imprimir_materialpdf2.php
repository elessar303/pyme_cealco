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
        $this->Cell(30, 7, utf8_decode('Proveedor'),1, 0, 'C');
        $this->Cell(15, 7, utf8_decode('Producto'), 1, 0, 'C');
        $this->Cell(90, 7, utf8_decode('Descripcion'),1, 0, 'C');
        $this->Cell(15, 7, 'Cant. PL', 1, 0, 'C');
        $this->Cell(15, 7, 'Peso', 1, 0, 'C');
        $this->Cell(10, 7, 'Lote', 1, 1, 'C');
    }

    function imprimir_datos() {

        $cantidad_registros = 40;
        if (($cont + 3) > $cantidad_registros) {
            // $this->Ln(60);
        }

        $conexion = conexion();
        $rs = query("SELECT moneda FROM parametros_generales;", $conexion);
        $fila = fetch_array($rs);        
        //$rs = query("SELECT * FROM item i, item_existencia_almacen a WHERE i.id_item = a.id_item AND cod_item_forma = 1 AND a.cantidad>0;",$conexion);
        // $rs = query("SELECT * FROM item i, item_existencia_almacen a WHERE i.id_item = a.id_item AND cod_item_forma = 1 AND a.cantidad>0 AND i.cod_departamento = '" . $dpto["cod_departamento"] . "' ORDER BY descripcion1;", $conexion);

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
       $rs = query("select v.*,i.precio1,  i.codigo_barras, i.iva, pro.nombre as nombre_proveedor FROM vw_existenciabyalmacen v, item i, clientes pro
        where i.id_item=v.id_item 
        AND v.id_proveedor=pro.id_cliente
        AND v.cantidad>0 
        AND ubicacion!='PISO DE VENTA'
        ".$filtros."
        GROUP BY i.codigo_barras, ubicacion, lote
        ORDER BY v.cod_almacen,ubicacion, nombre_proveedor,descripcion, lote
        ", $conexion); 

        $totalwhile = num_rows($rs);
        if ($totalwhile == 0) {
            $this->SetFont("Arial", "B", 20);
            $this->SetY(-100);
            $this->Cell(0, 7, 'S I N  E X I S T E N C I A S.', 0, 0, 'C');
        }

        $contar = 1;
        $cantidad_registros = 40;
        $subtotal_dpto = 0;
        $alma="";
        $ubi="";
        while ($totalwhile >= $contar) {
            $conexion = conexion();
            $row_rs = fetch_array($rs);
            $cont2++;
            //$var_snc=$row_rs[4];           

            if ($alma != $row_rs['descripcion'])  {   
                 $this->SetFont("Arial", "B", 9);
                 $this->SetTextColor(255,0,0);
                 $this->Cell(0, 5, "Almacen: " . strtoupper( utf8_decode($row_rs['descripcion'])), 0, 1, 'C');
                 $this->Cell(0, 1, "------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 1, 'C');            
                 $alma=$row_rs['descripcion'];
                 $this->SetTextColor(0,0,0);
            }
            $preciosiva=$row_rs['precio1'];
            $preciociva=$row_rs['precio1']+$row_rs['precio1']*($row_rs['iva']/100);
            $var_codigo = $row_rs['codigo_barras'];
            $var_descrip = utf8_decode($row_rs['descripcion1']);
            $var_exi = number_format($row_rs['cantidad'], 2, ',', '.');
            $var_peso = number_format($row_rs['peso'], 2, ',', '.');
            $lote = $row_rs['lote'];
            $var_preciosiva = number_format($preciosiva, 2, ',', '.');
            $var_preciociva = number_format($preciociva, 2, ',', '.');
            $precio_sub = $row_rs['cantidad'] * $preciosiva;
            $precio_total = $row_rs['cantidad'] * $preciociva;
            $var_precio_sub = number_format($precio_sub, 2, ',', '.');
            $var_precio_total = number_format($precio_total, 2, ',', '.');
            $iva=number_format($row_rs['iva'], 0, ',', '.');
            $subtotal_dpto += $precio_sub;
            $nombre_proveedor=utf8_decode($row_rs['nombre_proveedor']);
            $ubicacion=utf8_decode($row_rs['ubicacion']);
            $contador++;

            $this->SetFont("Arial", "I", 9);
            // llamado para hacer multilinea sin que haga salto de linea
            $this->SetWidths(array(20,30, 15,90,15,15, 10));
            $this->SetAligns(array('C','L', 'L','L','C', 'C', 'C'));
            $this->Setceldas(array(0,0, 0, 0, 0, 0, 0));
            $this->Setancho(array(5,5, 5, 5, 5, 5, 5, 5,5,5));
            $this->Row(array($ubicacion,$nombre_proveedor,$var_codigo,$var_descrip, $var_exi, $var_peso,$lote));
            $contar++;

        }//fin del while
    }

    function imprimir_datos2() {
        $conexion = conexion();
        $rs2 = query("select sum(v.cantidad) as total_cantidad, sum(v.peso) as total_peso, i.descripcion1 as nombre_item, v.lote as lote  FROM vw_existenciabyalmacen v, item i, clientes pro
        where i.id_item=v.id_item 
        AND v.id_proveedor=pro.id_cliente
        AND v.cantidad>0 
        AND ubicacion!='PISO DE VENTA'
        ".$filtros."
        GROUP BY i.codigo_barras, lote
        ", $conexion);

        $this->SetFont("Arial", "B", 9);
        $this->SetWidths(array(178));
        $this->SetAligns(array('R'));
        $this->Setceldas(array(0));
        $this->Setancho(array(5));
        $this->SetFont("Arial", "I", 9);
        $this->Ln(5);

        while($row = fetch_array($rs2)){

            $peso = number_format($row['total_cantidad'], 2, ',', '.');
            $cantidad = number_format($row['total_peso'], 2, ',', '.');
            $producto = utf8_decode($row['nombre_item']);

            $this->Cell(80, 7, $producto, 0, 0, 'L');
            $this->Cell(20, 7, $cantidad,0, 0, 'C');
            $this->Cell(15, 7, utf8_decode('UM'), 0, 0, 'C');
            $this->Cell(20, 7, $peso,0, 0, 'C');
            $this->Cell(20, 7, $row['lote'], 0, 0, 'C');
            $this->Cell(40, 7, 'Fecha de Vencimiento', 0, 1, 'C');
        }

    }


    //Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(188, 5, utf8_decode('Pagina ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Ln();
    }

    function Header($num) {
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
        $this->Image('../../includes/imagenes/pdval-logo.gif',10, 8, 20, 20);
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
        if($num==1){
        $this->SetFont("Arial", "B", 9);
        $this->Cell(20, 7, utf8_decode('Ubicacion'), 1, 0, 'C');
        $this->Cell(30, 7, utf8_decode('Proveedor'),1, 0, 'C');
        $this->Cell(15, 7, utf8_decode('Producto'), 1, 0, 'C');
        $this->Cell(90, 7, utf8_decode('Descripcion'),1, 0, 'C');
        $this->Cell(15, 7, 'Cant. PL', 1, 0, 'C');
        $this->Cell(15, 7, 'Peso', 1, 0, 'C');
        $this->Cell(10, 7, 'Lote', 1, 1, 'C');
        }
        if($num==2){
        $this->SetFont("Arial", "B", 9);
        $this->Cell(80, 7, utf8_decode('Producto'), 1, 0, 'C');
        $this->Cell(20, 7, utf8_decode('Cantidad'),1, 0, 'C');
        $this->Cell(15, 7, utf8_decode('UM'), 1, 0, 'C');
        $this->Cell(20, 7, utf8_decode('Peso Neto'),1, 0, 'C');
        $this->Cell(20, 7, 'Lote', 1, 0, 'C');
        $this->Cell(40, 7, 'Fecha de Vencimiento', 1, 1, 'C');
        }
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
$pdf->Header(1);
$subtotal_dptos[] = $pdf->imprimir_datos();
$pdf->AddPage();
$pdf->Header(2);
$subtotal_dptos[] = $pdf->imprimir_datos2();
$lista_dptos[] = $fila["descripcion"];
$fila = "";

ob_end_clean();
$pdf->Output();
?>
