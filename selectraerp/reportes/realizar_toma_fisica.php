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

        //$this->Image('../imagenes/banner_superior.jpg',10,5,190);


        $this->SetY(5);
        $this->SetFont('Arial','',6);
        //$this->SetFillColor(239,239,239);
        
        //$this->Cell(0,0, utf8_decode("Fecha de Creación: ".$this->array_movimiento[0]["fecha"]),0,0,'R');
        $this->Ln(3);

        $this->SetFont('Arial','',8);
        $this->SetX(180);
        $this->Cell(0,0, "Realizar Toma Fisica ",0,0,'L');

        $this->Cell(0,0, $this->array_movimiento[0]['id'],0,0,'R');
      

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
        $this->Ln(5);

        $this->SetX(14);
        $this->SetFont('Arial','B',12);
        
        $this->Cell(0,0, "REALIZAR TOMA FISICA DE ALMACEN ".$this->array_movimiento[0]['almacen']." EN LA UBICACION ".$this->array_movimiento[0]['ubicacion'],0,0,'C');
        
        $this->Ln(5);

//agregado 21/02/2014 el almacen la ubicacion y autorizado por 
        $this->SetX(14);
        $this->SetFont('Arial','',8);
        //$this->Cell(0,0, "Fecha :",0,0,'L');

        //$this->SetX(25);
        //$this->Cell(0,0,$this->array_movimiento[0]["fecha_apertura"] ,0,0,'L');

        
        
        /*$this->ln(5);
        $this->SetX(14);
        $this->Cell(0,0, "Almacen Visita :",0,0,'L');

         $this->SetX(37);
        $this->Cell(0,0, utf8_decode($this->array_movimiento[0]["almacen"]),0,0,'L');

         

        
        
        $this->ln(5);
        $this->SetX(14);
        $this->Cell(0,0, "Ubicacion Visita :",0,0,'L');
        $this->SetFont('Arial','',8);
        $this->SetX(37);
        $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["ubicacion"]) ,0,0,'L');
*/
        $this->ln(10);

      

          
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(14);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(10,10,10,10,10,10,10,10,10);
        $this->Cell(20,$width,utf8_decode('Cod. Barras'),1,0,"C",0);
        $this->Cell(55,$width,utf8_decode('Producto'),1,0,"C",0);
        $this->Cell(20,$width,'Presentacion',1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('Unid. por Bultos'),1,0,"C",0);
        $this->Cell(20,$width,'Cant. por Bultos',1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('Unid. Sueltas'),1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('Vencimiento'),1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('Nro Lote'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Toma 1'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Toma 2'),1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('Toma 3'),1,0,"C",0);
        $this->Ln(5);

        $this->SetX(14);
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
        $this->SetWidths(array(20,55,20,20,20,20,20,20,18,18,20));
        $this->SetAligns(array("C","J","C","C","C"));
        $this->SetFillColor(232,232,232,232,232);
        // $cantidaditems = $this->array_movimiento[0]["numero_item"];

        $subtotal = 0;
       
        foreach ($this->array_movimiento as $key => $value) {
            $this->SetLeftMargin(30);
            $width = 5;
            $this->SetX(14);
            $this->SetFont('Arial','',7);
            
            
            $this->Row(
                    array(  
                            $value["codigo_barras"],
                            $value["nombre_producto"],
                            $value["nombre_unidad"],
                            $value["cantidad_bulto"],
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            ''
                            ),1);
             $this->SetX(14);
             

             
           

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


$ubicacion = $_POST["ubicacion"];
$almacen = $_POST["almacen_entrada"];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

$operacion="Entrada";


  
$query=
"SELECT b.codigo_barras, d.descripcion as ubicacion, c.descripcion as almacen, a.descripcion1 as nombre_producto, b.cantidad_bulto, e.nombre_unidad from
vw_existenciabyalmacen a,
item b, almacen as c,
ubicacion as d, unidad_empaque as e
WHERE 
a.id_item=b.id_item
and b.unidad_venta=e.id
and c.cod_almacen=d.id_almacen 
and a.cantidad <>0
and a.id_ubicacion= ".$ubicacion."
and a.cod_almacen= ".$almacen."
and c.cod_almacen= ".$almacen."
and d.id= ".$ubicacion."
ORDER BY a.descripcion1";
    
$array_movimiento = $comunes->ObtenerFilasBySqlSelect($query);
//echo "select a.id, date_format(date(a.fecha_apertura),'%d/%m/%Y') as fecha_apertura, c.descripcion1, b.cod_bar, b.toma2, b.tomadef, b.mov_sugerido from tomas_fisicas as a, tomas_fisicas_detalle as b, item as c where a.id=b.id_mov and b.cod_bar=c.codigo_barras
 //   and a.id=".$id_transaccion; exiT();
if(count($array_movimiento)==0){
    echo "no se encontraron registros.";
    exit;
}


$pdf=new PDF('L','mm','letter');
$title='Toma Fisica Inventario';
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
