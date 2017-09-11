<?php
ob_start();
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
ob_end_clean();    header("Content-Encoding: None", true);
class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $array_facturas_devueltas;

    function Header() {

        $this->Image('../../includes/imagenes/'.$this->datosgenerales[0]["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        #$width = 5;
        #$this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        #$this->SetFillColor(10, 50, 100);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode("Fecha Emision: ") . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetX(14);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("LIBRO DE VENTAS - " . strtoupper(mesaletras(date_format(date_create($_GET["fecha"] . "-01"), $format = "m"))) . " " . date_format(date_create($_GET["fecha"] . "-01"), $format = "Y")), 0, 0, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 6);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(20, 10, 20, 20, 10, 20, 15, 18, 18, 18, 10, 18, 18, 18));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
        #$this->SetFillColor(232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232);
        $this->Row(array('Serial Impresora.', 'Z', 'Ultima Factura', 'Numero De Facturas', 'Ultima NC.', 'Numero De NCS', 'Fecha',
            'Monto Bruto', 'Monto Exento', utf8_decode('Base Imponible.'),
              'IVA', 'Usuario', 'Caja','Fecha Elaboracion'), 1);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function dwawCell($title, $data) {
        $width = 8;
        $this->SetFont('Arial', 'B', 12);
        $y = $this->getY() * 20;
        $x = $this->getX();
        $this->SetFillColor(206, 230, 100);
        $this->MultiCell(175, 8, $title, 0, 1, 'L', 0);
        $this->SetY($y);
        $this->SetFont('Arial', '', 12);
        $this->SetFillColor(206, 230, 172);
        $w = $this->GetStringWidth($title) + 3;
        $this->SetX($x + $w);
        $this->SetFillColor(206, 230, 172);
        $this->MultiCell(175, 8, $data, 0, 1, 'J', 0);
    }

    function ChapterBody() {

    $i=0;
    $total_bruto="0";
    $total_exento="0";
    $total_imponible="0";
    $total_iva="0";        
        while ($this->array_factura[$i]) {

            $date = date_create($this->array_factura[$i]["fecha_emision"]);
            $fecha=date_format($date, 'd-m-Y'); 
            $width = 5;
            $this->SetFont('Arial', '', 6);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(20, 10, 20, 20, 10, 20, 15, 18, 18, 18, 10, 18, 18, 18));
            $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
$total_bruto+=$this->array_factura[$i]["monto_bruto_usuario"];
    $total_exento+=$this->array_factura[$i]["monto_exento_usuario"];
    $total_imponible+=$this->array_factura[$i]["base_imponible_usuario"];
    $total_iva+=$this->array_factura[$i]["iva_usuario"];
            $this->Row(array(
    
                $this->array_factura[$i]["serial_impresora"],
                $this->array_factura[$i]["numero_z_usuario"],
                $this->array_factura[$i]["ultima_factura"],
                $this->array_factura[$i]["numeros_facturas"],
                $this->array_factura[$i]["ultima_nc"],
                $this->array_factura[$i]["numeros_ncs"],
                $this->array_factura[$i]["fecha"],
                number_format($this->array_factura[$i]["monto_bruto_usuario"], 2, ',', '.'),
                number_format($this->array_factura[$i]["monto_exento_usuario"], 2, ',', '.'),
                number_format($this->array_factura[$i]["base_imponible_usuario"], 2, ',', '.'),
                number_format($this->array_factura[$i]["iva_usuario"], 2, ',', '.'),
                $this->array_factura[$i]["usuario"],
                 $this->array_factura[$i]["caja_host"],$fecha),1);
            
            $i++;
        }
        
        $this->SetWidths(array(115, 18, 18, 18, 10));
        $this->SetAligns(array("C", "C", "C", "C", "C"));
         $this->Row(array('TOTALES',$total_bruto,$total_exento,$total_imponible,$total_iva),1);
        /* #$totalDebito = number_format($totalDebito, 2, ',', '.');
          #$totalCredito = number_format($totalCredito, 2, ',', '.');
          $totalVentasConIva = number_format($totalVentasConIva, 2, ',', '.');
          $totalVentasNoGravadas = number_format($totalExento, 2, ',', '.');
          $totalBaseImponible = number_format($totalBaseImponible, 2, ',', '.');
          $totalGravado = number_format($totalGravado, 2, ',', '.');
          $totalIva = number_format($totalIva, 2, ',', '.');
          $totalIvaRet = number_format($totalIvaRet, 2, ',', '.'); */

        
        
    }

    

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, $label, 0, 1, 'L', 1);
        $this->Ln(8);
    }

    function SetTitle($title) {
        $this->title = $title;
    }

    function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }

    function DatosGenerales($array) {
        $this->datosgenerales = $array;
    }

    function ArrayFactura($array) {
        $this->array_factura = $array;
    }

    function ArrayFacturasDevueltas($array) {
        $this->array_facturas_devueltas = $array;
    }

    function getFacturaDevuelta($cod_factura) {
        $i = 0;
        while ($factura_devuelta = $this->array_facturas_devueltas[$i]) {
            if ($this->array_facturas_devueltas[$i]["cod_factura"] == $cod_factura) {
                return $i;
            }
            $i++;
        }
    }

}

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

/*
  $fecha = @$_GET["fecha"] . "-01";
  $array_factura = $comunes->ObtenerFilasBySqlSelect(
  "SELECT f.*, c.rif, c.nombre, c.direccion, c.nit, c.cod_cliente, c.telefonos, c.direccion
  FROM factura f
  INNER JOIN clientes c ON c.id_cliente = f.id_cliente
  WHERE month(f.fechaFactura) = month('{$fecha}') AND year(f.fechaFactura) = year('{$fecha}')
  ORDER BY f.id_factura;"); */

#echo $desde . "->" . $hasta;exit;
$array_factura = $comunes->ObtenerFilasBySqlSelect(
        "SELECT *
         FROM libro_ventas as a, usuarios as b, caja_impresora as c
         
         WHERE a.id_usuario_creacion=b.cod_usuario and a.serial_impresora=c.serial_impresora   and a.fecha between '".$_POST['fecha']."' and '".$_POST['fecha2']."'");
        

$array_facturas_devueltas = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_devolucion");

#$mes = mesaletras(substr($fecha, 5, 2));

$pdf = new PDF('L', 'mm', 'A4');
$title = 'Libro de Ventas Numero #'.$_GET['id'];

$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->ArrayFacturasDevueltas($array_facturas_devueltas);

$pdf->SetTitle($title);
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
$comunes->cerrar();
?>
