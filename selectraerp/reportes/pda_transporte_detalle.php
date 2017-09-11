<?php
ob_start();
include('config_reportes.php');
include('fpdf.php');

ob_end_clean();    header("Content-Encoding: None", true);
class PDF extends FPDF 
{
    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_compra;
    function Header() 
    {

        $width = 10;

       // $this->Image('../../includes/imagenes/banner_central.png',10,5,190);
        $this->SetY(5);
        $this->SetFont('Arial','',6);
        $this->Ln(3);
        $this->SetFont('Arial','',8);
        $this->SetX(160);
        $this->Cell(0,0, "Nro Documento: ",0,0,'L');
        $this->Cell(0,0, $this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["nro_orden"],0,0,'R');
        $this->Ln(10);
        $this->SetX(14);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]),0,0,'L');
        $this->Ln(3);
        $this->SetX(14);
        $this->Cell(0,0, utf8_decode($this->datosgenerales[0]["direccion"]),0,0,'L');
        $this->Ln(3);
        $this->SetX(14);
        $this->Cell(0,0,$this->datosgenerales[0]["id_fiscal2"].": ".$this->datosgenerales[0]["nit"]." - Telefonos: ".$this->datosgenerales[0]["telefonos"],0,0,'L');
        $this->Ln(3);
        $this->SetX(14);
        $this->SetFont('Arial','B',12);
        $this->Cell(0,0, "TIPO (".strtoupper($this->array_movimiento[0]['tipo_accion']).")",0,0,'C');
        $this->Ln(10);
        $this->SetX(14);
        $this->SetFont('Arial','',9);
        $this->Cell(0,0, utf8_decode("Fecha Planificación :"),0,0,'L');
        $this->SetX(44);
        $this->Cell(0,0,$this->array_movimiento[0]["fecha_planificacion"] ,0,0,'L');
        $this->SetX(80);
        $this->Cell(0,0, "Placa Vehiculo :",0,0,'L');
        $this->SetX(104);
        $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["placa"]) ,0,0,'L');
        $this->Ln(5);
        $this->SetX(14);
        $this->Cell(0,0, "Nombre Conductor :",0,0,'L');
        $this->SetX(43);
        $this->Cell(0,0, utf8_decode($this->array_movimiento[0]["nombres"]),0,0,'L');
        $this->SetX(80);
        $this->Cell(0,0, "Unidad Vehiculo :",0,0,'L');
        $this->SetX(105);
        $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["unidad"]) ,0,0,'L');
        $this->SetX(150);
        $this->Cell(0,0, "Nro. Orden:",0,0,'L');
        $this->SetFont('Arial','',9);
        $this->SetX(180);
        $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["nro_orden"]) ,0,0,'L');
        $this->SetFont('Arial','',8);
        $this->Ln(5);
        $this->SetX(14);
        $this->Cell(0,0, "Telefono Conductor :",0,0,'L');
        $this->SetX(42);
        $this->Cell(0,0,$this->array_movimiento[0]["telefono"] ,0,0,'L');
        $this->SetX(80);
        $this->Cell(0,0, "Proveedor :",0,0,'L');
        $this->SetX(96);
        $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["proveedor"]) ,0,0,'L');
        $this->Ln(5);
        $this->SetX(14);
        $this->Cell(0,0, "Cedula Conductor:",0,0,'L');
        $this->SetX(38);
        $this->Cell(0,0, utf8_decode($this->array_movimiento[0]["cedula"]),0,0,'L');
        $this->SetX(80);
        $this->Cell(0,0, utf8_decode("Telefono Proveedor :"),0,0,'L');
        $this->SetX(108);
        $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["telefono_proveedor"]) ,0,0,'L');
        $this->Ln(5);
        
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
        $this->SetLeftMargin(1000);
        $width = 5;
        $this->SetX(14);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(10,10,10,10,10);
        $this->Cell(30,$width,'Codigo Barras',1,0,"C",0);
        $this->Cell(90,$width,utf8_decode('Descripción'),1,0,"C",0);
        $this->Cell(30,$width,utf8_decode('Cantidad Unitaria'),1,0,"C",0);
        $this->Cell(30,$width,utf8_decode('Cantidad TN'),1,0,"C",0);
        $this->Cell(30,$width,utf8_decode('Punto Destino'),1,0,"C",0);
        $this->Ln(5);
    }

    function Footer() 
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',10);

        $this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
    }

    function dwawCell($title,$data) 
    {
        $width = 8;
        $this->SetFont('Arial','B',12);
        $y =  $this->getY() * 20;
        $x =  $this->getX();
        $this->SetFillColor(10,10,10);
        $this->MultiCell(175,8,$title,0,1,'L',0);
        $this->SetY($y);
        $this->SetFont('Arial','',12);
        $this->SetFillColor(10,10,10);
        $w=$this->GetStringWidth($title)+3;
        $this->SetX($x+$w);
        $this->SetFillColor(10,10,10);
        $this->MultiCell(175,8,$data,0,1,'J',0);

    }


    function ChapterBody() 
    {
        $this->SetWidths(array(30,90,30,30,30));
        $this->SetAligns(array("C","J","C","C","C","C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232,232,232,232,232,232);
        foreach ($this->array_movimiento as $key => $value) 
        {
            $this->SetLeftMargin(30);
            $width = 5;
            $this->SetX(14);
            $this->SetFont('Arial','',8);
            $this->Row(
                    array($value["codigo_barras"],
                    $value["nombre_producto"],
                    number_format($value["cantidad_unitaria"], 2, '.', ' '),
                    number_format($value["cantidad_tn"], 2, '.', ' '),
                    $value["punto_destino"]
                    ),1);

        }
    }


    function ChapterTitle($num,$label) 
    {
        $this->SetFont('Arial','',10);
        $this->SetFillColor(232,232,232,232,232);
        $this->Cell(0,6,"$label",0,1,'L',1);
        $this->Ln(8);
    }

    function SetTitle($title) 
    {
        $this->title   = $title;
    }

    function PrintChapter() 
    {
        $this->AddPage();
        $this->ChapterBody();
    }

    function DatosGenerales($array) 
    {
        $this->datosgenerales = $array;
    }

    function Arraymovimiento($array) 
    {
        $this->array_movimiento = $array;
    }

    function Arraymovimiento2($array) 
    {
        $this->array_movimiento2 = $array;
    }


};


$id_transaccion = @$_GET["id_transaccion"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");


$array_movimiento = $comunes->ObtenerFilasBySqlSelect("SELECT c.cedula, c.nombres, c.apellidos,c.telefono, b.unidad, b.placa, h.descripcion as marca, 
i.descripcion_modelo as modelo, f.tipo as tipo_accion, f.orden_compra as nro_orden, date_format
(e.fecha_inicio,'%d/%m/%Y') as fecha_planificacion, g.codigo_barras, concat(g.descripcion1,'--',
j.marca,'--',g.pesoxunidad,'--',k.nombre_unidad) as nombre_producto, ((a.cantidad*g.cantidad_bulto)/1000) as cantidad_tn, a.cantidad as cantidad_unitaria, l.nombre_punto as punto_destino, m.descripcion as proveedor, m.telefonos as telefono_proveedor
from 
distribucion_pda as a, 
transporte_camion as b, 
transporte_conductores as c,
".DB_SELECTRA_PYMEPP.".estados as d,
pda_detalle as e,
pda_maestro as f left join proveedores as m on f.id_proveedor=m.id_proveedor,
item as g,
transporte_marca as h,
transporte_modelo i,
marca as j,
unidad_medida as k,
".DB_SELECTRA_PYMEPP.".puntos_venta as l

where 
    b.flota_asignada=d.codigo_estado 
    and a.destino_logistica=l.codigo_siga_punto
    and a.id_transporte_camion=b.id 
    and a.id_transporte_chofer=c.id 
    and e.id=a.id_pda_detalle
    and e.id_pda_maestro=f.id
    and e.id_producto=g.id_item
    and b.marca=h.id
    and b.modelo=i.id
    and j.id=g.id_marca
    and g.unidadxpeso=k.id
    and a.id=".$id_transaccion);


if(count($array_movimiento)==0)
{
    echo "no se encontraron registros.";
    exit;
}



$pdf=new PDF('L','mm','letter');
$title='Detalle de Movimiento de Almacen';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->Arraymovimiento($array_movimiento);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
?>
