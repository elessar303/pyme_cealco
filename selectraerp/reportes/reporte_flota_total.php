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
        $this->Cell(0, 0, utf8_decode("Transporte Flota Vehicular - " . strtoupper(mesaletras(date_format(date_create($_POST["fecha"] . "-01"), $format = "m"))) . " " . date_format(date_create($_POST["fecha"] . "-01"), $format = "Y")." A ".strtoupper(mesaletras(date_format(date_create($_POST["fecha2"] . "-01"), $format = "m"))) . " " . date_format(date_create($_POST["fecha2"] . "-01"), $format = "Y") ), 0, 0, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(18, 18, 18, 18, 18,15,18,18,18,18,18,18,18,18,15,18));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
        
        $this->Row(array('Unidad', 'Placa', 'Estado', 'Marca', 'Modelo', utf8_decode('Año'), 'Cant. Ejes', 'Caucho Rep.', 'Herramientas', 'Kilometraje', 'Serial GPS', 'Alias GPS', 'Vehiculo', 'Tipo Vehiculo', 'Propio', 'Refrigerado'), 1);
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
            $this->SetWidths(array(18, 18, 18, 18, 18,15,18,18,18,18,18,18,18,18,15,18));
            $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
            if($this->array_factura[$i]["posee_caucho_repuesto"]!=0)
            {
                $this->array_factura[$i]["posee_caucho_repuesto"]="SI";
            }
            else
            {
                $this->array_factura[$i]["posee_caucho_repuesto"]="NO";
            }

            if($this->array_factura[$i]["posee_herramientas"]!=0)
            {
                $this->array_factura[$i]["posee_herramientas"]="SI";
            }
            else
            {
                $this->array_factura[$i]["posee_herramientas"]="NO";
            }
            
            if($this->array_factura[$i]["vehiculo_propio"]!=0)
            {
                $this->array_factura[$i]["vehiculo_propio"]="SI";
            }
            else
            {
                $this->array_factura[$i]["vehiculo_propio"]="NO";
            }

            if($this->array_factura[$i]["vehiculo_recuperado"]!=0)
            {
                $this->array_factura[$i]["vehiculo_recuperado"]="SI";
            }
            else
            {
                $this->array_factura[$i]["vehiculo_recuperado"]="NO";
            }
            
            

            $this->Row(array(
    
                $this->array_factura[$i]["unidad"],
                utf8_decode($this->array_factura[$i]["placa"]),
                //utf8_decode($this->array_factura[$i]["serial_motor"]),
                $this->array_factura[$i]["nombre_estado"],
                utf8_decode($this->array_factura[$i]["marca_vehiculo"]),
                utf8_decode($this->array_factura[$i]["modelo_vehiculo"]),
                utf8_decode($this->array_factura[$i]["anio_vehiculo"]),
                utf8_decode($this->array_factura[$i]["cantidad_ejes"]),
                utf8_decode($this->array_factura[$i]["posee_caucho_repuesto"]),
                utf8_decode($this->array_factura[$i]["posee_herramientas"]),
                utf8_decode($this->array_factura[$i]["ultimo_kilometraje"]),
                utf8_decode($this->array_factura[$i]["serial_gps"]),
                utf8_decode($this->array_factura[$i]["alias_gps"]),
                utf8_decode($this->array_factura[$i]["vehiculo"]),
                utf8_decode($this->array_factura[$i]["estatus_vehiculo"]),
                utf8_decode($this->array_factura[$i]["vehiculo_propio"]),
                utf8_decode($this->array_factura[$i]["vehiculo_recuperado"])

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


$marca = @$_POST["marca"];
$modelo = @$_POST["modelo"];
$estatus = @$_POST["estatus_vehiculo"];
$refrigerado = @$_POST["refrigerado"];
$herramientas =@$_POST["herramientas"];

$flota = @$_POST["estado"];
$tipo_vehiculo = @$_POST["tipo_vehiculo"];

    if($tipo_vehiculo=='999')
    {
        $tipoVehiculoF="";
    }
    else
    {
        $tipoVehiculoF=" and c.id=".$tipo_vehiculo;
    }

    if($flota=='0000')
    {
        $flota="";
    }
    else
    {
        $flota=" and a.flota_asignada='".$flota."'";
    }

    if($marca=='999')
    {
        $marcafiltro="";
    }
    else
    {
        $marcafiltro=" and d.id=".$marca;
    }

    if($modelo=='999')
    {
        $modelofiltro="";
    }
    else
    {
        $modelofiltro=" and e.id=".$modelo;
    }

    if($estatus=='999')
    {
        $estatusfiltro="";
    }
    else
    {
        $estatusfiltro=" and f.id=".$estatus;
    }

    if($herramientas=="999")
    {
        $herramientas="";
        

    }
    else
    {
        $herramientas=" and a.posee_herramientas=".$herramientas;

    }


    if($refrigerado=="999")
    {
        $refrigerado="";
    }
    else
    {
        $refrigerado=" and a.vehiculo_recuperado=".$refrigerado;
    }

    

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

$array_factura = $comunes->ObtenerFilasBySqlSelect(
        "select a.*, b.nombre_estado, c.descripcion as vehiculo, d.descripcion as marca_vehiculo, e.descripcion_modelo as modelo_vehiculo, f.descripcion as estatus_vehiculo, b.nombre_estado
        from transporte_camion a inner join tipo_vehiculo c on a.tipo_vehiculo=c.id,
          ".DB_SELECTRA_PYMEPP.".estados as b,
          transporte_marca as d,
          transporte_modelo as e,
          estatus_vehiculo as f 
        where 
        a.flota_asignada=b.codigo_estado
        and a.marca=d.id
        and a.modelo=e.id
        and a.estatus_vehiculo=f.id
        ".$flota.$herramientas.$refrigerado.$estatusfiltro.$modelofiltro.$marcafiltro.$tipoVehiculoF);


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
