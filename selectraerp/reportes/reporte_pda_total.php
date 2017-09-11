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
        $this->Cell(0, 0, utf8_decode("PDA LOGISTICA - " . strtoupper(mesaletras(date_format(date_create($_POST["fecha"] . "-01"), $format = "m"))) . " " . date_format(date_create($_POST["fecha"] . "-01"), $format = "Y")." A ".strtoupper(mesaletras(date_format(date_create($_POST["fecha2"] . "-01"), $format = "m"))) . " " . date_format(date_create($_POST["fecha2"] . "-01"), $format = "Y") ), 0, 0, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 12);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(18, 20, 23, 27, 35, 40, 15, 11, 18, 18, 20, 35));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
        
        $this->Row(array('Tipo', 'Fecha Pl.', 'Proveedor', utf8_decode('Dirección'), 'Destino', 'Rubro', 'Cantidad', 'TM.',
            'Fecha Inicio', 'Fecha Fin','Transporte',utf8_decode('Observacion')), 1);
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
            $this->SetFont('Arial', '', 9);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(18, 20, 23, 27, 35, 40, 15, 11, 18, 18, 20, 35));
            $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
            $total_cantidad+=$this->array_factura[$i]["cantidad_destino"];
            $total_tm+=$this->array_factura[$i]["total"];
            
            $this->Row(array(
    
                $this->array_factura[$i]["tipo"],
                $this->array_factura[$i]["fecha_planificacion"],
                utf8_decode($this->array_factura[$i]["descripcion"]),
                utf8_decode($this->array_factura[$i]["direccion"]),
                utf8_decode($this->array_factura[$i]["direccion_punto"]),
                utf8_decode($this->array_factura[$i]["rubro"]),
                $this->array_factura[$i]["cantidad_destino"],
                $this->array_factura[$i]["total"],
                $this->array_factura[$i]["fecha_inicio"],
                $this->array_factura[$i]["fecha_fin"],
                $this->array_factura[$i]["transporte"],
                $this->array_factura[$i]["observaciones_logistica"]),1);
            
            $i++;
        }
        
        $this->SetWidths(array(163, 15, 11,));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetFont('Arial', 'B', 9);
         $this->Row(array('TOTALES',$total_cantidad,$total_tm),1);
        

        
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


$inicio = @$_POST["fecha"];
$date = new DateTime($inicio);
$inicio=$date->format('Y-m-d');
$fin = @$_POST["fecha2"];
$date = new DateTime($fin);
$fin=$date->format('Y-m-d');
$estado = @$_POST["estado"];
$rubro = @$_POST["rubro"];
$tipo = @$_POST["tipo"];
$departamento= @$_POST["alimento"];
$filtroBarras="";
$filtroProveedor=@$_POST["proveedores"];
$banderaproveedor="";

    //filtro proveedores
    if($filtroProveedor==999)
    {
        $filtroProveedor="";
    }
    else
    {
        $filtroProveedor=" and d.id_proveedor='".$filtroProveedor."' ";
        //las transferencias no tiene proveedores
        $banderaproveedor=" and c2.codigo_siga_punto=9999 ";
    }
    //filtro codigo barras
    if(isset($_POST['codigoBarra']) && !empty($_POST['codigoBarra']))
    {
        $filtroBarras="and e.codigo_barras='".$_POST['codigoBarra']."' ";
    }

    //filtro tipo
    if($tipo==999)
    {
        $filtrot="";
    }
    else
    {
        $filtrot=" and a.tipo='".$tipo."'";
    }

    //filtro departamento de rubro
    if($departamento=='999')
    {
        $filtroDepartamento="";
    }
    else
    {
        $filtroDepartamento=" and e.cod_departamento='".$departamento."' ";
    }


$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");



    //fitlro estado
    if($estado==000)
    {
        $filtro="";
    }
    else
    {
        $filtro=" and g.codigo_estado=".$estado;
    }
    //filtro rubro
    if($rubro==999)
    {
        $filtro2="";
    }
    else
    {
        $filtro2=" and h.cod_grupo=".$rubro;
    }

    
$array_factura = $comunes->ObtenerFilasBySqlSelect(
        "SELECT a.tipo, h.descripcion as rubro, f.cantidad as cantidad_destino, g.nombre_estado as estado, 
        a.id, a.orden_compra, date_format(f.fecha_inicio_logistica,'%d/%m/%Y') as fecha_inicio, date_format(f.fecha_fin_logistica,'%d/%m/%Y') as fecha_fin, date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_planificacion, d.descripcion, d.direccion, c.direccion_punto, e.descripcion1, b.cantida_x_kilo, format(((e.cantidad_bulto*f.cantidad)/1000), 3) as total, f.observaciones_logistica, a.transporte 
        FROM pda_maestro as a, pda_detalle as b, distribucion_pda as f, ".DB_SELECTRA_PYMEPP.".puntos_venta as c, proveedores as d, item as e, ".DB_SELECTRA_PYMEPP.".estados as g, grupo as h 
        where a.id=b.id_pda_maestro
        and e.cod_grupo=h.cod_grupo
        and b.id_producto=e.id_item 
        and b.id=f.id_pda_detalle
        and b.id_instalacion_origen='0'
        and f.destino_logistica=c.codigo_siga_punto
        and c.codigo_estado_punto=g.codigo_estado
        and a.id_proveedor=d.id_proveedor
        and f.fecha_inicio_logistica between '".$inicio."' and '".$fin."'
        ".$filtro.$filtro2.$filtrot.$filtroBarras.$filtroDepartamento.$filtroProveedor."
        
        UNION ALL

        SELECT a.tipo, h.descripcion as rubro, f.cantidad as cantidad_destino, g.nombre_estado as estado, 
        a.id, a.orden_compra, date_format(f.fecha_inicio_logistica,'%d/%m/%Y') as fecha_inicio, date_format(f.fecha_fin_logistica,'%d/%m/%Y') as fecha_fin, date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_planificacion, d.descripcion, i.direccion, c.direccion_punto, e.descripcion1, b.cantida_x_kilo, format(((e.cantidad_bulto*f.cantidad)/1000), 3) as total, f.observaciones_logistica, a.transporte 
        FROM pda_maestro as a, pda_detalle as b, distribucion_pda as f, ".DB_SELECTRA_PYMEPP.".puntos_venta as c, proveedores as d, item as e, ".DB_SELECTRA_PYMEPP.".estados as g, grupo as h , instalacion_proveedores as i
        where a.id=b.id_pda_maestro
        and e.cod_grupo=h.cod_grupo
        and b.id_producto=e.id_item 
        and b.id_instalacion_origen=i.codigo_sica
        and d.id_proveedor=i.id_proveedor
        and b.id=f.id_pda_detalle
        and f.destino_logistica=c.codigo_siga_punto
        and c.codigo_estado_punto=g.codigo_estado
        and a.id_proveedor=d.id_proveedor
        and f.fecha_inicio_logistica between '".$inicio."' and '".$fin."'
        ".$filtro.$filtro2.$filtrot.$filtroBarras.$filtroDepartamento.$filtroProveedor."

        UNION ALL

       SELECT 
        a.tipo, h.descripcion as rubro,
        f.cantidad as cantidad_destino,
        g.nombre_estado as estado,
        a.id,
        a.orden_compra,
        date_format(f.fecha_inicio_logistica,'%d/%m/%Y') as fecha_inicio,
        date_format(f.fecha_fin_logistica,'%d/%m/%Y') as fecha_fin,
        date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_planificacion,
        c.nombre_punto as descripcion, c.direccion_punto, c2.direccion_punto,
        e.descripcion1,
        b.cantida_x_kilo,
        format(((e.cantidad_bulto*f.cantidad)/1000), 3) as total, f.observaciones_logistica, a.transporte 
        FROM pda_maestro as a, pda_detalle as b, distribucion_pda as f, ".DB_SELECTRA_PYMEPP.".puntos_venta as c, item as e, ".DB_SELECTRA_PYMEPP.".estados as g, grupo as h, ".DB_SELECTRA_PYMEPP.".puntos_venta as c2
        where 
        a.id=b.id_pda_maestro 
        and e.cod_grupo=h.cod_grupo 
        and b.id_producto=e.id_item 
        and b.id_instalacion_origen=c2.codigo_siga_punto 
        and b.id=f.id_pda_detalle 
        and c.codigo_estado_punto=g.codigo_estado 
        and f.destino_logistica=c.codigo_siga_punto
        and f.fecha_inicio_logistica between '".$inicio."' and '".$fin."'
        ".$filtro.$filtro2.$filtrot.$filtroBarras.$filtroDepartamento.$banderaproveedor);
 
$array_facturas_devueltas = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_devolucion");
//print_r($array_factura); exit();
if(count($array_factura)==0){
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
