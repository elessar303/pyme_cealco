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
        $this->Cell(0, 0, utf8_decode("Detalle Cierre POS"), 0, 0, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 9);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(12, 15,40, 35, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
        $this->Row(array(utf8_decode('SIGA'), 'Fecha', 'Banco', 'Nro. Cuenta', utf8_decode('Afiliación Cred.'), 'Terminal Cred.', 'Lote Cred.', 'Visa', 'Master', 'Total Cred.', utf8_decode('Afiliación Deb.'), 'Terminal Deb.', 'Lote Deb.', 'Debito', utf8_decode('Alimentación'), 'Total Deb.'), 1);
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

            $date = date_create($this->array_factura[$i]["fecha"]);
            $fecha=date_format($date, 'd-m-Y'); 
            $width = 5;
            $this->SetFont('Arial', '', 10);
            $this->SetLineWidth(0.1);
           $this->SetWidths(array(12, 15,40, 35, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
            $this->Row(array(
                $this->array_factura[$i]["siga"],
                $this->array_factura[$i]["fecha"],
                $this->array_factura[$i]["banco"],
                $this->array_factura[$i]["nro_cuenta"],
                $this->array_factura[$i]["afiliacion_credito"],
                $this->array_factura[$i]["terminal_credito"],
                $this->array_factura[$i]["lote_credito"],
                $this->array_factura[$i]["visa"],
                $this->array_factura[$i]["master"],
                $this->array_factura[$i]["total_credito"],
                $this->array_factura[$i]["afiliacion_debito"],
                $this->array_factura[$i]["terminal_debito"],
                $this->array_factura[$i]["lote_debito"],
                $this->array_factura[$i]["debito"],
                $this->array_factura[$i]["alimentacion"],
                $this->array_factura[$i]["total_debito"],
                ),1);
            
            $i++;
        }
        
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

$array_factura = $comunes->ObtenerFilasBySqlSelect(
        "SELECT a.siga, date_format(a.fecha, '%d-%m-%Y') as fecha, c.descripcion as banco, b.nro_cuenta, a.afiliacion_credito, a.terminal_credito, a.lote_credito, a.visa, a.master, a.total_credito, a.afiliacion_debito, a.terminal_debito, a.lote_debito, a.debito, a.alimentacion, a.total_debito, d.nombreyapellido, date_format(fecha_creacion, '%d-%m-%Y') as fecha_creacion FROM cierre_pos as a inner join cuentas_contables as b on a.cuenta=b.id inner join banco as c on a.banco=c.cod_banco inner join usuarios as d on a.usuario=d.cod_usuario 
            where a.id=".$_GET['id']);
        

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
