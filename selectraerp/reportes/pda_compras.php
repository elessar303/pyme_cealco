<?php
ob_start();
include("../../../generalp.config.inc.php");
include('config_reportes.php');
include('fpdf.php');

ob_end_clean();    header("Content-Encoding: None", true);
class PDF extends FPDF {
    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_compra;
    function Header() {

        $width = 10;

        //$this->Image('../imagenes/banner_superior.jpg',10,5,190);


        $this->SetY(5);
        $this->SetFont('Arial','',6);
        //$this->SetFillColor(239,239,239);
        
        //$this->Cell(0,0, utf8_decode("Fecha de Creación: ".$this->array_movimiento[0]["fecha"]),0,0,'R');
        $this->Ln(3);

        $this->SetFont('Arial','',8);
        $this->SetX(180);
        $this->Cell(0,0, "OC # ",0,0,'L');

        $this->Cell(0,0, $this->array_movimiento[0]['orden_compra'],0,0,'R');
      

        $this->Ln(10);
        $this->SetX(160);
        
        $this->SetFont("Arial", 'B', 10);

        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){
        $this->Cell(0,0, "E-".$this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["id_transaccion"],0,0,'R');
        }
        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
        $this->Cell(0,0, "S-".$this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["id_transaccion"],0,0,'R');
        }
        $this->SetFont("Arial",'', 9);
        $this->Ln(3);

        $this->SetX(14);
        $this->SetFont('Arial','',6);
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
        
        $this->Cell(0,0, "PDA LOGISTICA ".$this->array_movimiento[0]['orden_compra'],0,0,'C');
        
        $this->Ln(10);

        $this->SetX(14);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, "Fecha Planificacion:",0,0,'L');

        $this->SetX(40);
        $this->Cell(0,0,$this->array_movimiento[0]["fecha_planificacion"] ,0,0,'L');

        $this->ln(10);

//+++++++++++++++++++++++++++++++++++++++++++++++++++++
        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(12);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(10,10,10,10,10,10,10,10,10);
        $this->Cell(42,$width,'Proveedor',1,0,"C",0);
        $this->Cell(45,$width,utf8_decode('Direccion'),1,0,"C",0);
        $this->Cell(50,$width,utf8_decode('Producto'),1,0,"C",0);
        $this->Cell(12,$width,utf8_decode('Cantidad'),1,0,"C",0);
        $this->Cell(12,$width,utf8_decode('Total TM'),1,0,"C",0);
        $this->Ln(5);


    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',10);

        $this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
    }

    function dwawCell($title,$data) {
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


    function ChapterBody() {

        //aqui metemos los parametros del contenido
        $this->SetWidths(array(42,45,50,12,12));
        $this->SetAligns(array("C","J","C","C","C"));
        $this->SetFillColor(232,232,232,232,232);
        $subtotal = 0;
        foreach ($this->array_movimiento as $key => $value) {
            $this->SetLeftMargin(30);
            $width = 5;
            $this->SetX(12);
            $this->SetFont('Arial','',7);
            if($value["estatus"]==1)
            {
                $value["estatus"]="Aprobado";
            }
            else
            {
                $value["estatus"]="No Aprobado";
            }
            $this->Row(
                    array(
                        utf8_decode($value["descripcion"]),
                        utf8_decode($value["direccion"]),
                        utf8_decode($value["descripcion1"]),
                        $value["cantida_x_kilo"],
                        $value["total"]
                        ),1);
            $this->SetX(12);
            $this->Cell(161,5,'Observacion : '.utf8_decode($value["observaciones"]),1,1,'L');
            if(isset($value['estado_proveedor']))
            {
            $this->SetX(12);
            $this->Cell(161,5,'Estado Origen : '.utf8_decode($value["estado_proveedor"]),1,1,'L');
            }
                 
        }

    }

    function ChapterTitle($num,$label) {
        $this->SetFont('Arial','',10);
        $this->SetFillColor(232,232,232,232,232);
        $this->Cell(0,6,"$label",0,1,'L',1);
        $this->Ln(8);
    }

    function SetTitle($title) {
        $this->title   = $title;
    }

    function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }

    function DatosGenerales($array) {
        $this->datosgenerales = $array;
    }

    function Arraymovimiento($array) {
        $this->array_movimiento = $array;
    }

     function Arraymovimiento2($array) {
        $this->array_movimiento2 = $array;
    }


}


$id_transaccion = @$_GET["id_transaccion"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

$operacion="Entrada";
//se realiza busqueda para saber si se posee instalacion
$proveedor=$comunes->ObtenerFilasBySqlSelect("Select b.id_instalacion_origen  from pda_maestro a, pda_detalle as b where a.id=b.id_pda_maestro and a.id=".$id_transaccion);
    if($proveedor[0]['id_instalacion_origen']==0)
    {
        $array_movimiento = $comunes->ObtenerFilasBySqlSelect("SELECT a.id, a.orden_compra,  date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_planificacion, d.descripcion, d.direccion, e.descripcion1, b.cantida_x_kilo, b.observaciones, format(((e.cantidad_bulto*b.cantida_x_kilo)/1000), 2) as total  FROM pda_maestro as a, pda_detalle as b, proveedores as d, item as e
            where
            a.id=b.id_pda_maestro
            and
            b.id_producto=e.id_item
            and
            a.id_proveedor=d.id_proveedor
            and a.id=".$id_transaccion."

            union

            SELECT a.id, a.orden_compra,  date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_planificacion, d.nombre_punto as descripcion, d.direccion_punto as direccion, e.descripcion1, b.cantida_x_kilo, b.observaciones, format(((e.cantidad_bulto*b.cantida_x_kilo)/1000), 2) as total  FROM pda_maestro as a, pda_detalle as b, ".DB_SELECTRA_PYMEPP.".puntos_venta as d, item as e
            where
            a.id=b.id_pda_maestro
            and
            b.id_producto=e.id_item
            and
            b.id_instalacion_origen=d.codigo_siga_punto
            and a.id=".$id_transaccion
            );
    }
    else
    {
     $array_movimiento = $comunes->ObtenerFilasBySqlSelect("SELECT a.id, a.orden_compra,  date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_planificacion, d.descripcion, f.direccion, g.nombre_estado as estado_proveedor,  e.descripcion1, b.cantida_x_kilo, b.observaciones, format(((e.cantidad_bulto*b.cantida_x_kilo)/1000), 2) as total  FROM pda_maestro as a, pda_detalle as b, proveedores as d, item as e, instalacion_proveedores as f, ".DB_SELECTRA_PYMEPP.".estados as g
            where
            a.id=b.id_pda_maestro
            and
            b.id_producto=e.id_item
            and
            b.id_instalacion_origen=f.codigo_sica
            and
            f.estado=g.id
            and
            f.codigo_sica=b.id_instalacion_origen
            and
            a.id_proveedor=d.id_proveedor
            and a.id=".$id_transaccion."

            union

            SELECT a.id, a.orden_compra,  date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_planificacion, d.nombre_punto as descripcion, d.direccion_punto as direccion, g.nombre_estado as estado_proveedor,  e.descripcion1, b.cantida_x_kilo, b.observaciones, format(((e.cantidad_bulto*b.cantida_x_kilo)/1000), 2) as total  FROM pda_maestro as a, pda_detalle as b, ".DB_SELECTRA_PYMEPP.".puntos_venta as d, item as e,  ".DB_SELECTRA_PYMEPP.".estados as g
            where
            a.id=b.id_pda_maestro
            and
            b.id_producto=e.id_item
            and
            b.id_instalacion_origen=d.codigo_siga_punto
            and
            d.codigo_estado_punto=g.codigo_estado
            and a.id=".$id_transaccion

            );   
    }

if(count($array_movimiento)==0){
    echo "no se encontraron registros.";
    exit;
}

//print_r($array_movimiento); exit();
$pdf=new PDF('P','mm','letter');
$title='Detalle de Movimiento de Almacen';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->Arraymovimiento($array_movimiento);
$pdf->Arraymovimiento2($array_movimiento);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
?>
