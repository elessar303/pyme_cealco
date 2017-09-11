<?php
ob_start();
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

        
        $this->SetY(5);
        $this->SetFont('Arial','',6);
        
        $this->Ln(3);

        $this->SetFont('Arial','',8);
        $this->SetX(180);
        $this->Cell(0,0, "Acta # ",0,0,'L');

        $this->Cell(0,0, $this->array_movimiento[0]['cod_acta_calidad'],0,0,'R');
      

        $this->Ln(10);
        $this->SetX(160);
        $this->AddFont('New','','free3of9.php');
        $this->SetFont("New", '', 25);

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
        
        $this->Cell(0,0, "ACTA DE CALIDAD ".$this->array_movimiento[0]['cod_acta_calidad'],0,0,'C');
        
        $this->Ln(10);

//agregado 21/02/2014 el almacen la ubicacion y autorizado por 
        $this->SetX(14);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, "Fecha :",0,0,'L');

        $this->SetX(25);
        $this->Cell(0,0,$this->array_movimiento[0]["fecha_creacion"] ,0,0,'L');

        $this->SetX(80);
        $this->Cell(0,0, "Inspector De Calidad :",0,0,'L');

        $this->SetX(109);
        $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["autorizado_por"]) ,0,0,'L');

        
        
        

        $this->ln(10);

      

          
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(14);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(10,10,10,10,10,10,10,10,10);
        
        $this->Cell(20,$width,'Codigo',1,0,"C",0);
        
        $this->Cell(90,$width,utf8_decode('Descripción'),1,0,"C",0);
        $this->Cell(25,$width,utf8_decode('Cantidad Unitaria'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Lote'),1,0,"C",0);
        $this->Cell(30,$width,utf8_decode('Tipo uso'),1,0,"C",0);
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
        $this->SetWidths(array(20,90,25,18,30));
        $this->SetAligns(array("C","J","C","C","C"));
        $this->SetFillColor(232,232,232,232,232);
        // $cantidaditems = $this->array_movimiento[0]["numero_item"];

        $subtotal = 0;
        
        foreach ($this->array_movimiento as $key => $value) {
            $this->SetLeftMargin(30);
            $width = 5;
            $this->SetX(14);
            $this->SetFont('Arial','',7);
            if($value["estatus"]==1){
                $value["estatus"]="Aprobado";
            }
            else{
                if($value["estatus"]==0)
                $value["estatus"]="No Aprobado";
                else
                $value["estatus"]="Pendiente";
            }
            $this->Row(
                    array(  $value["codigo_barras"],
                            $value["descripcion1"],
                            $value["cantidad"],
                            $value["lote"],
                            $value["tipo_uso"]),1);
             $this->SetX(14);
             
                 $this->Cell(183,5,'Observacion : '.$value["observacion"],1,1,'L');
                 
                 $this->SetX(14);
                 $this->SetFont('Arial','B',8);
                 $this->Cell(183,5,'Almacen De Inspeccion : '.$value["almacen"],1,1,'L');
                 $this->SetX(14);
                 $this->Cell(183,5,'Ubicacion De Inspeccion : '.$value["nombre_punto"],1,1,'L');

                 //$this->ln(2);
             
           

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

$array_movimiento = $comunes->ObtenerFilasBySqlSelect("select f.tipo as tipo_uso, a.cod_acta_calidad, b.estatus, a.id_transaccion as id_transaccion, date_format(a.fecha_creacion,'%d/%m/%Y') as fecha_creacion, autorizado_por, d.descripcion as nombre_punto, c.descripcion as almacen, e.codigo_barras, e.descripcion1, b.cantidad, b.lote, b.observacion 
    from calidad_almacen as a, calidad_almacen_detalle as b, almacen as c, ubicacion as d, item as e, tipo_uso as f 
    where b.tipo_uso=f.id and b.id_item=e.id_item and b.id_almacen_salida=c.cod_almacen and a.id_transaccion=b.id_transaccion and b.id_ubi_salida=d.id and a.id_transaccion=".$id_transaccion);
//echo "select a.cod_acta_calidad, b.estatus, a.id_transaccion as id_transaccion, a.fecha_creacion as fecha_creacion, autorizado_por, d.descripcion as nombre_punto, c.descripcion as almacen, e.codigo_barras, e.descripcion1, b.cantidad, b.lote, b.observacion from calidad_almacen as a, calidad_almacen_detalle as b, almacen as c, ubicacion as d, item as e where b.id_item=e.id_item and b.id_almacen_salida=c.cod_almacen and a.id_transaccion=b.id_transaccion and b.id_ubi_salida=d.id and a.id_transaccion=".$id_transaccion; exit();
if(count($array_movimiento)==0){
    echo "no se encontraron registros.";
    exit;
}


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
