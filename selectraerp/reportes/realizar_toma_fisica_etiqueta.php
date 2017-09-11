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

/*
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
        $this->Ln(3);

        $this->SetX(14);
        $this->SetFont('Arial','B',12);
        
        $this->Cell(0,0, "REALIZAR TOMA FISICA DE ALMACEN  #".$this->array_movimiento[0]['almacen'],0,0,'C');
        
        $this->Ln(10);

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
       /* $this->ln(10);

      

          
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(14);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(10,10,10,10,10,10,10,10,10);
        $this->Cell(20,$width,'Almacen',1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('Ubicacion'),1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('Cod. Barras'),1,0,"C",0);
        $this->Cell(70,$width,utf8_decode('Producto'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Toma 1'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Toma 2'),1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('Toma 3'),1,0,"C",0);
        $this->Ln(5);
*/


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
        $this->SetWidths(array(20,20,20,70,18,18,20));
        $this->SetAligns(array("C","J","C","C","C"));
        $this->SetFillColor(232,232,232,232,232);
        // $cantidaditems = $this->array_movimiento[0]["numero_item"];

        $subtotal = 0;
        $bandera = 0;
        foreach ($this->array_movimiento as $key => $value) 
        {
            //$this->SetLeftMargin(30);
            //$width = 5;
            //$this->SetX(14);
            if($bandera == 0)
            {
                for ($i=3; $i > 0; $i--) 
                { 
                    $this->SetFont('Arial','',7);

                    $this->Cell(20,10, $i."er. CONTEO",1,0,'C');
                    //$this->Cell(30,5, "","LBR",0,'C');
                    $this->Cell(10,5, "",0,0,'C');
                    $this->Cell(50,5, "TARJETAS DE CONTEO FISICO ","B",0,'C');
                    $this->Cell(10,5, "",0,0,'C');
                    $this->Cell(20,10, $i."er. CONTEO",1,1,'C');
                    //$this->Cell(30,5, "CONTEO","LBR",1,'C');

                    $this->Ln(3);
                    $this->Cell(30,5, "INVENTARIO FISICO AL ",0,0,'L');
                    $this->Cell(90,5, date("d/m/Y"),"B",1,'L');

                    $this->Cell(25,5, "ESTABLECIMIENTO ",0,0,'L');
                    $this->Cell(95,5, utf8_decode($this->datosgenerales[0]["nombre_empresa"]),"B",1,'L');

                    $this->Cell(40,5, "DESCRIPCION DEL PRODUCTO",0,0,'L');
                    $this->Cell(80,5, utf8_decode($value["nombre_producto"]),"B",1,'L');

                    $this->Cell(25,5, "PRESENTACION",0,0,'L');
                    $this->Cell(95,5, $value["pesoxunidad"]." ".$value["unidad"],"B",1,'L');

                    $this->Cell(25,5, "CODIGO BARRA",0,0,'L');
                    $this->Cell(95,5, utf8_decode($value["codigo_barras"]),"B",1,'L');

                    $this->Ln(2);

                    $this->Cell(15,10,"",1,0,'C');
                    $this->Cell(8,10, "X",0,0,'C');
                    $this->Cell(15,10,"",1,0,'C');
                    $this->Cell(8,10, "=",0,0,'C');
                    $this->Cell(15,10,"",1,0,'C');
                    $this->Cell(8,10, "+",0,0,'C');
                    $this->Cell(15,10,"",1,0,'C');
                    $this->Cell(8,10, "=",0,0,'C');
                    $this->Cell(15,10,"",1,1,'C');

                    $this->Cell(15,5,"Bultos",0,0,'C');
                    $this->Cell(8,5, "",0,0,'C');
                    $this->Cell(15,5,"Presentacion",0,0,'C');
                    $this->Cell(8,5, "",0,0,'C');
                    $this->Cell(15,5,"",0,0,'C');
                    $this->Cell(8,5, "",0,0,'C');
                    $this->Cell(15,5,"Unidades Sueltas",0,0,'C');
                    $this->Cell(8,5, "",0,0,'C');
                    $this->Cell(15,5,"",0,0,'C');


                    $this->Ln(10);
                }
                $bandera = 1;
            }
            else
            {
                $y1=10;
                $y2=23;
                $y3=28;
                $y4=33;
                $y5=38;
                $y6=43;
                $y7=48;
                $y8=53;
                for ($i=3; $i > 0; $i--) 
                { 
                    if($i == 3)
                    {
                        $y1 = $y1;
                        $y2 = $y2;
                        $y3 = $y3;
                        $y4 = $y4;
                        $y5 = $y5;
                        $y6 = $y6;
                        $y7 = $y7;
                        $y8 = $y8;
                    }
                    elseif(($i == 2) || ($i == 1))
                    {
                        $y1 = $y1 + 60;
                        $y2 = $y2 + 60;
                        $y3 = $y3 + 60;
                        $y4 = $y4 + 60;
                        $y5 = $y5 + 60;
                        $y6 = $y6 + 60;
                        $y7 = $y7 + 60;
                        $y8 = $y8 + 60;
                    }
                    /*elseif($i == 1)
                    {
                        $y1 = $y1 + 48;
                        $y2 = $y2 + 48;
                        $y3 = $y3 + 48;
                        $y4 = $y4 + 48;
                        $y5 = $y5 + 48;
                        $y6 = $y6 + 48;
                        $y7 = $y7 + 48;
                        $y8 = $y8 + 48;
                    }*/
                    
                    $x = 140;
                    $this->SetFont('Arial','',7);
                    $this->SetXY($x,$y1);
                    $this->Cell(20,10, $i."er. CONTEO",1,0,'C');
                    //$this->Cell(30,5, "","LBR",0,'C');
                    $this->Cell(10,5, "",0,0,'C');
                    $this->Cell(50,5, "TARJETAS DE CONTEO FISICO ","B",0,'C');
                    $this->Cell(10,5, "",0,0,'C');
                    $this->Cell(20,10, $i."er. CONTEO",1,1,'C');
                    //$this->Cell(30,5, "CONTEO","LBR",1,'C');

                    $this->Ln(3);
                    $this->SetXY($x,$y2);
                    $this->Cell(30,5, "INVENTARIO FISICO AL ",0,0,'L');
                    $this->Cell(90,5, date("d/m/Y"),"B",1,'L');

                    $this->SetX($x,$y3);
                    $this->Cell(25,5, "ESTABLECIMIENTO ",0,0,'L');
                    $this->Cell(95,5, utf8_decode($this->datosgenerales[0]["nombre_empresa"]),"B",1,'L');

                    $this->SetX($x,$y4);
                    $this->Cell(40,5, "DESCRIPCION DEL PRODUCTO",0,0,'L');
                    $this->Cell(80,5, utf8_decode($value["nombre_producto"]),"B",1,'L');

                    $this->SetX($x,$y5);
                    $this->Cell(25,5, "PRESENTACION",0,0,'L');
                    $this->Cell(95,5, $value["pesoxunidad"]." ".$value["unidad"],"B",1,'L');

                    $this->SetX($x,$y6);
                    $this->Cell(25,5, "CODIGO BARRA",0,0,'L');
                    $this->Cell(95,5, utf8_decode($value["codigo_barras"]),"B",1,'L');

                    $this->Ln(2);

                    $this->SetX($x,$y7);
                    $this->Cell(15,10,"",1,0,'C');
                    $this->Cell(8,10, "X",0,0,'C');
                    $this->Cell(15,10,"",1,0,'C');
                    $this->Cell(8,10, "=",0,0,'C');
                    $this->Cell(15,10,"",1,0,'C');
                    $this->Cell(8,10, "+",0,0,'C');
                    $this->Cell(15,10,"",1,0,'C');
                    $this->Cell(8,10, "=",0,0,'C');
                    $this->Cell(15,10,"",1,1,'C');

                    $this->SetX($x,$y8);
                    $this->Cell(15,5,"Bultos",0,0,'C');
                    $this->Cell(8,5, "",0,0,'C');
                    $this->Cell(15,5,"Presentacion",0,0,'C');
                    $this->Cell(8,5, "",0,0,'C');
                    $this->Cell(15,5,"",0,0,'C');
                    $this->Cell(8,5, "",0,0,'C');
                    $this->Cell(15,5,"Unidades Sueltas",0,0,'C');
                    $this->Cell(8,5, "",0,0,'C');
                    $this->Cell(15,5,"",0,0,'C');
                }
                $bandera = 0;
                $this->AddPage();
            }
            /*$this->SetFont('Arial','',7);

            $this->Row(
                    array(  
                            $value["almacen"],
                            $value["ubicacion"],
                            $value["codigo_barras"],
                            $value["nombre_producto"],
                            '',
                            '',
                            ''
                            ),1);
            $this->SetX(14);*/
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
"SELECT b.codigo_barras, d.descripcion as ubicacion, c.descripcion as almacen, b.descripcion1 as nombre_producto, um.nombre_unidad as  unidad, b.pesoxunidad from
vw_existenciabyalmacen a,
item b left join unidad_medida um on (b.unidadxpeso = um.id), almacen as c,
ubicacion as d
WHERE 
a.id_item=b.id_item 
and c.cod_almacen=d.id_almacen 
and a.id_ubicacion= ".$ubicacion."
and a.cod_almacen= ".$almacen."
and c.cod_almacen= ".$almacen."
and d.id= ".$ubicacion;
    


    

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
