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

    function Header() {

        $fecha_ini = new DateTime(@$_GET["fecha"]); # PHP 5 >= 5.2.0;
        $fecha_fin = new DateTime(@$_GET["fecha2"]);

        $fecha_ini = $fecha_ini->format("d-m-Y");
        $fecha_fin = $fecha_fin->format("d-m-Y");

        $this->Image('../../includes/imagenes/'.$this->datosgenerales[0]["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        #$this->SetFillColor(10, 50, 100);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 0, utf8_decode("Fecha Emisión: ") . date("d-m-Y"), 0, 0, 'R');
        $this->SetFont('Arial', 'B', 8);
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("REPORTE DE MOVIMIENTOS DE CALIDAD"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->Cell(0, 0, @$_GET["fecha"] != @$_GET["fecha2"] ? "Desde {$fecha_ini} Hasta {$fecha_fin}" : $fecha_ini, 0, 0, 'C');
        $this->Ln(10);

        
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(26, 20, 18, 18, 18, 18,55, 15));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C"));
        $this->Row(array(utf8_decode('Inspector'), utf8_decode('Tipo Movimiento'), 'Almacen Ent', 'almacen salida', 'Ubicacion entrada','Ubicacion Salida', 'Observacion', 'Estatus'), 1);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
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

        $i = $iva = $total_iva = $subtotal = $total_venta = 0;
        $item_descripcion = array();
        $item_cod = array();
        $item_unidades = array();
        $item_iva = array();
        $item_subtotales = array();
        $item_totales = array();
      
          

            
            // aqui se imprime los datos en la tabla
          $this->Ln(3);
        foreach ($this->array_factura as $value) {
           
           if($value["tipo_acta"]==1){
            $tipo="Entrada";
            $almacen_entrada=$value["descripcion_salida"];
            $almacen_salida="---";

            $ubicacion_entrada="CALIDAD";
            $ubicacion_salida="---";
           }else{

            if($value["tipo_acta"]==2){
            $tipo="Salida";
            $almacen_salida=$value["descripcion_salida"];
            $almacen_entrada="---";
            $ubicacion_entrada="---";
            $ubicacion_salida=$value["descripcion_ubi"];
            $value['estatus']='---';
        }else{
            $tipo="Visita";
            $almacen_entrada=$value["descripcion_salida"];
            $ubicacion_entrada=$value["descripcion_ubi"];
            $almacen_salida="---";
            $ubicacion_salida="---";
        }


           }

        if($value["estatus"]=='1'){
            $value["estatus"]='Aprobado';}
        if($value["estatus"]=='0'){
            
            $value["estatus"]='No Aprobado';}
        if($value["estatus"]=='3'){
            $value["estatus"]='Pendiente';}

            $this->SetFont('Arial', '', 8);
                $this->SetLineWidth(0.1);
                $this->SetWidths(array(26, 20, 18, 18, 18, 18,55, 15));
                $this->SetAligns(array("C", "C", "C", "C", "C","C", "C", "C"));
                $this->Row(array(
                    $value["autorizado_por"],
                    $tipo,
                    ucfirst(strtolower($almacen_entrada)),
                    ucfirst(strtolower($almacen_salida)),
                    ucfirst(strtolower($ubicacion_entrada)),
                    ucfirst(strtolower($ubicacion_salida)),
                    utf8_decode($value["observacion"]),
                    $value["estatus"]
                    
                   ));
        }
            
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, $label, 0, 1, 'L', 1);
        $this->Ln(8);
    }

    function SetTitle($title, $isUTF8 = false) {
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

}

$inicio = @$_POST["fecha"];
$final = @$_POST["fecha2"];
$estatus = @$_POST["estatus"];
$tipo_acta = @$_POST["tipo_mov"];
$inspector = @$_POST["inspector"];
$filtro2="";
if($estatus!=999 ){
$filtro.=" and b.estatus=".$estatus." ";
$filtro2.=" and a.estatus=".$estatus." ";
}
if($tipo_acta!=999){
$filtro.=" and a.tipo_acta=".$tipo_acta;
if($tipo_acta==1 || $tipo_acta==2)
$filtro2.=" and a.cod_acta_visita=-1";
}
if($inspector!=999){
$filtro.=" and a.autorizado_por='".$inspector."'";    
$filtro2.=" and b.nombreyapellido='".$inspector."'";
}
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");


// aqui se coloca la sql 

$sql = "select a.autorizado_por, a.tipo_acta, b.observacion, b.estatus, c.descripcion as descripcion_ubi, d.descripcion as descripcion_salida 
from calidad_almacen as a, calidad_almacen_detalle as b left join ubicacion as c on b.id_ubi_salida=c.id, almacen as d 
where (b.id_almacen_salida=d.cod_almacen or b.id_almacen_entrada=d.cod_almacen) and 
  a.id_transaccion=b.id_transaccion and
a.fecha BETWEEN '{$inicio}' AND '{$final}' 
{$filtro}

UNION

select b.nombreyapellido as autorizado_por, a.cod_acta_visita as tipo_acta, a.observacion, '---' as estatus, c.descripcion as descripcion_ubi, d.descripcion as descripcion_salida
from calidad_visitas as a, usuarios as b, ubicacion as c, almacen as d
where a.usuario=b.cod_usuario and a.almacen_visita=d.cod_almacen and a.ubicacion_visita=c.id
and 
date(fecha_visita) BETWEEN '{$inicio}' AND '{$final}' 
{$filtro2}
";

// echo $sql; exit();


$array_factura = $comunes->ObtenerFilasBySqlSelect($sql);
$pdf = new PDF('P', 'mm', 'A4');

$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle('Reportes Calidad');
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
?>
