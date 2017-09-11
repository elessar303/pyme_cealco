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

        $this->Image($this->datosgenerales[0]["img_der"] ? "../../includes/imagenes/" . $this->datosgenerales[0]["img_der"] : "../../includes/imagenes/" . $this->datosgenerales[0]["img_izq"], 10, 8, 50, 10);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        
        $this->SetFont('Arial', 'B', 10);
        
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
        $this->Cell(0, 0, utf8_decode("Transporte Distribución - " . strtoupper(mesaletras(date_format(date_create($_POST["fecha"] . "-01"), $format = "m"))) . " " . date_format(date_create($_POST["fecha"] . "-01"), $format = "Y")." A ".strtoupper(mesaletras(date_format(date_create($_POST["fecha2"] . "-01"), $format = "m"))) . " " . date_format(date_create($_POST["fecha2"] . "-01"), $format = "Y") ), 0, 0, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(38, 38,38,38,38,38,38));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C"));
        
        $this->Row(array('Numero Documento / OC', 'Placa Vehiculo', 'Nombre Conductor', 'Cedula Conductor', 'Estado Destino', 'Fecha Inicio Logistica', 'Fecha Fin Logistica'), 1);
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
            $this->SetFont('Arial', '', 7);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(38,38,38,38,38,38,38));
            $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C"));

            $this->Row(array(
    
                $this->array_factura[$i]["numero_documento"],
                utf8_decode($this->array_factura[$i]["placa"]),
                utf8_decode($this->array_factura[$i]["nombres"]),
                $this->array_factura[$i]["cedula"],
                utf8_decode($this->array_factura[$i]["nombre_estado"]),
                utf8_decode($this->array_factura[$i]["fecha_inicio_logistica"]),
                utf8_decode($this->array_factura[$i]["fecha_fin_logistica"])

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


$placa = @$_POST["placa"];
$cedula = @$_POST["cedula"];
$flota = @$_POST["estado"];

if($flota=='0000')
{
    $flota="";
}
else
{
    $flota=" and g.codigo_estado='".$flota."'";
}
if($placa=="")
{
    $placa="";
}
else
{
    $placa=" and d.placa like '%".$placa."%'";
}


if($cedula=="")
{
    $cedula="";
}
else
{
    $cedula=" and e.cedula like '%".$cedula."%'";
}

    

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

$array_factura = $comunes->ObtenerFilasBySqlSelect("select a.orden_compra as numero_documento,d.placa as placa, e.nombres, e.cedula, g.nombre_estado, date_format(c.fecha_inicio_logistica,'%d/%m/%Y') AS fecha_inicio_logistica, date_format(c.fecha_fin_logistica,'%d/%m/%Y') AS fecha_fin_logistica from pda_maestro a, pda_detalle as b, distribucion_pda as c, transporte_camion d, transporte_conductores as e, ".DB_SELECTRA_PYMEPP.".puntos_venta as f, ".DB_SELECTRA_PYMEPP.".estados as g where a.id=b.id_pda_maestro and b.id=c.id_pda_detalle and c.id_transporte_camion=d.id and c.id_transporte_chofer=e.id and c.destino_logistica=f.codigo_siga_punto and g.codigo_estado=f.codigo_estado_punto".
    $flota.$placa.$cedula);

$array_facturas_devueltas = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_devolucion");

if(count($array_factura)==0)
{
    echo "no se encontraron registros.";
    exit;
}

$pdf = new PDF('L', 'mm', 'A4');
$title = 'Reporte PDA';

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
