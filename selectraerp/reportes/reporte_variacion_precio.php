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


        $this->SetY(10);
        $this->SetFont('Arial','',6);
        //$this->SetFillColor(239,239,239);

        $var_imagen_der = "../../includes/imagenes/pdval-logo.gif";
        $this->Image($var_imagen_der,12,5,50,30);
        
        //$this->Cell(0,0, utf8_decode("Fecha de Creación: ".$this->array_movimiento[0]["fecha"]),0,0,'R');
        $this->Ln(3);

        $this->SetFont('Arial','',8);
        $this->SetX(220);
        //$this->Cell(0,0, utf8_decode("Cod. Variación: "),0,0,'L');
        
        //prueba para cargar los datos generales
        $this->SetX(242);
        $this->Cell(0,0, "Codigo de la variacion: ".$this->array_movimiento2[0]["correlativo"],0,0,'');

        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){
        $this->Cell(0,0, "E-".$this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["id_transaccion"],0,0,'R');
        }
        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
        $this->Cell(0,0, "S-".$this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["id_transaccion"],0,0,'R');
        }

        $this->Ln(3);

        $this->SetX(14);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]),0,0,'C');
        $this->Ln(3);

        $this->SetX(14);
        $this->Cell(0,0, utf8_decode($this->datosgenerales[0]["direccion"]),0,0,'C');
        $this->Ln(3);

        $this->SetX(14);
        $this->Cell(0,0,$this->datosgenerales[0]["id_fiscal2"].": ".$this->datosgenerales[0]["nit"]." - Telefonos: ".$this->datosgenerales[0]["telefonos"],0,0,'C');
        $this->Ln(3);

        $this->SetX(14);
        $this->SetFont('Arial','B',12);
        //3 es igual a entrada
            if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){
        $this->Cell(0,0, "GUIA DE RECEPCION (ENTRADA)",0,0,'C');
             }
                //2 4 es igual a salida
            if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
        $this->Cell(0,0, "GUIA DE DESPACHO (SALIDA)",0,0,'C');
             }
                //5  es igual a traslado
            if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==5){
        $this->Cell(0,0, "REPORTE DETALLE DE TRASLADO",0,0,'C');
             }
        $this->Ln(10);

//agregado 21/02/2014 el almacen la ubicacion y autorizado por 
        $this->SetX(14);
        $this->SetFont('Arial','B',15);
        
        //$this->Cell(0,0, "Fecha :",0,0,'L');

        /*$this->SetX(25);
        $this->Cell(0,0,$this->array_movimiento[0]["fecha_ejecucion"] ,0,0,'L');*/

        $this->SetX(60);
        $this->Cell(0,0, "DETALLE DE LA ACTUALIZACION DE PRODUCTOS Y PRECIOS",0,0,'L');
        $this->Ln(10);

        $this->SetX(30);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0,"Realizado(a) por: ".utf8_decode($this->array_movimiento[0]["usuario"]),0,0,'L');
        $this->Ln(5);

        $this->SetX(30);
        $this->SetFont('Arial','',8);
        $var_date = date_format($this->array_movimiento[0]["fecha"], 'Y-m-d');
        $this->Cell(0,0,utf8_decode("Fecha de elaboración: ").utf8_decode($this->array_movimiento[0]["fecha"]),0,0,'L');
        $this->Ln(5);

        if($this->array_movimiento[0]["tipo_movimiento_almacen"]==5 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
        $this->SetX(130);
        $this->Cell(0,0, "Almacen Destino :",0,0,'L');

        $this->SetFont('Arial','',5);
         $this->SetX(159);
         $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["nombre_punto_rep1"]) ,0,0,'L');
         $this->SetFont('Arial','',8);
          $this->Ln(5);

        }

        $this->Ln(5);
    if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==5 ){
        $this->SetX(14);
        $this->Cell(0,0, "Almacen entrada :",0,0,'L');

         $this->SetX(37);
        $this->Cell(0,0, utf8_decode($this->array_movimiento[0]["almacen"]),0,0,'L');

         

        $this->SetX(80);
        $this->Cell(0,0, "Ubicacion entrada :",0,0,'L');

         $this->SetX(105);
         $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["ubicacion"]) ,0,0,'L');

         $this->SetX(150);
        $this->Cell(0,0, "Almacen Procedencia :",0,0,'L');

        $this->SetFont('Arial','',8);
         $this->SetX(180);
         $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["almacen_procedencia"]) ,0,0,'L');
         $this->SetFont('Arial','',8);
          $this->Ln(5);


      }
if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3 ){
    $this->SetX(14);
      $this->Cell(0,0, "Empresa Transporte :",0,0,'L');

        $this->SetX(42);
        $this->Cell(0,0,$this->array_movimiento[0]["empresa_transporte"] ,0,0,'L');

        $this->SetX(80);
        $this->Cell(0,0, "Nombre Conductor :",0,0,'L');

        $this->SetX(106);
        $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["nombre_conductor"]) ,0,0,'L');

        

        $this->Ln(5);
    
        $this->SetX(14);
        $this->Cell(0,0, "Cedula Conductor:",0,0,'L');

         $this->SetX(38);
        $this->Cell(0,0, utf8_decode($this->array_movimiento[0]["cedula_conductor"]),0,0,'L');

         

        $this->SetX(80);
        $this->Cell(0,0, "Placa :",0,0,'L');

         $this->SetX(90);
         $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["placa"]) ,0,0,'L');

         $this->SetX(140);
        $this->Cell(0,0, "Nro Guia SUNAGRO :",0,0,'L');

         $this->SetX(169);
         $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["guia_sunagro"]) ,0,0,'L');

          $this->Ln(5);
          
          
      }

          if($this->array_movimiento[0]["tipo_movimiento_almacen"]==5 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
            $this->SetX(14);
            $this->Cell(0,0, "Almacen Salida :",0,0,'L');

            $this->SetX(37);
            $this->Cell(0,0, utf8_decode($this->array_movimiento2[0]["almacen"]),0,0,'L');         

            $this->SetX(80);
            $this->Cell(0,0, "Ubicacion Salida :",0,0,'L');

            $this->SetX(105);
            $this->Cell(0,0,utf8_decode($this->array_movimiento2[0]["ubicacion"]) ,0,0,'L');

            $this->SetX(140);
            $this->Cell(0,0, "Almacen Destino:",0,0,'L');

         $this->SetX(169);
         $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["almacen_destino"]) ,0,0,'L');


            $this->Ln(5);
          }
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(30);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(10,10,10,10,10,10,10);
        $this->Cell(20,$width,'Codigo',1,0,"C",0);
        $this->Cell(70,$width,utf8_decode('Descripción'),1,0,"C",0);
        $this->Cell(25,$width,utf8_decode('Estado'),1,0,"C",0);
        $this->Cell(25,$width,utf8_decode('Motivo de la variación'),1,0,"C",0);
        $this->Cell(25,$width,utf8_decode('Observación'),1,0,"C",0);
        $this->Cell(25,$width,utf8_decode('Precio sin IVA'),1,0,"C",0);
        $this->Cell(25,$width,'IVA',1,0,"C",0);
        //$this->Cell(18,$width,utf8_decode('Lote'),1,0,"C",0);
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

        /*aqui metemos los parametros del contenido.
        Llenamos la tabla HZ
        */
        $this->SetWidths(array(20,70,25,25,25,25,25));
        $this->SetAligns(array("C","J","C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232,232,232);
        // $cantidaditems = $this->array_movimiento[0]["numero_item"];

        $subtotal = 0;
        
        foreach ($this->array_movimiento as $key => $value) {
            $this->SetLeftMargin(30);
            $width = 5;
            $this->SetX(30);
            $this->SetFont('Arial','',7);
            $this->Row(
                    array($value["codigo_barra"],
                    utf8_decode($value["sol"]),
                    $value["nombre_estado"],
                    $value["nombre_motivo"],
                    $value["observacion"],
                    $value["precio_sin_iva"],
                    $value["iva"]),
                    1);
            $this->SetX(14);
            if( $value["cantidad_item"] != $value["c_esperada"] && $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){
                $this->Cell(196,5,'Observacion : '.$value["observacion_dif"],1,1,'L');
            }
        }
    }

    function ChapterTitle($num,$label) {
        $this->SetFont('Arial','',10);
        $this->SetFillColor(232,232,232,232,232,232);
        $this->Cell(0,6,"$label",0,1,'L',1);
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

    function Arraymovimiento($array) {
        $this->array_movimiento = $array;
    }

     function Arraymovimiento2($array) {
        $this->array_movimiento2 = $array;
    }


}

$id_var_precio_cab = @$_GET["id_var_precio_cab"];
//$id_transaccion = @$_GET["id_transaccion"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

$operacion="Entrada";

$bdCentral= "selectrapyme_central";
$bdPyme = "selectrapyme";

$array_movimiento = $comunes->ObtenerFilasBySqlSelect("SELECT v.codigo_barra, CONCAT(i.descripcion1,' ',mc.marca,'  ',i.pesoxunidad,um.nombre_unidad) AS sol,e.nombre_estado,mc.marca,
    mv.nombre_motivo,v.observacion,v.precio_sin_iva,i.iva,vc.usuario,vc.fecha
FROM $bdPyme.item_variacion_precio v
INNER JOIN $bdPyme.item_variacion_precio_cabecera vc ON vc.id_var_precio_cab = v.id_var_precio_cab
INNER JOIN $bdPyme.item i ON i.codigo_barras = v.codigo_barra
INNER JOIN $bdPyme.motivo_varia_precio mv ON mv.id_motivo = v.id_motivo
INNER JOIN $bdCentral.estados e ON e.codigo_estado = v.id_estado
LEFT JOIN $bdPyme.marca mc ON mc.id =i.id_marca
LEFT JOIN $bdPyme.unidad_medida um ON um.id=i.unidadxpeso


WHERE vc.correlativo = ".$id_var_precio_cab);

if(count($array_movimiento)==0){
    echo "<h1>no se encontraron registros.</h1>";
    exit;
}

$array_movimiento2 = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item_variacion_precio_cabecera vc
WHERE vc.correlativo = ".$id_var_precio_cab);

if(count($array_movimiento2)==0){
    echo "no se encontraron registros.";
    exit;
}
$pdf=new PDF('L','mm','letter');
$title="DETALLE DE LA ACTUALIZACIÓN DE PRODUCTOS Y PRECIOS";
$pdf->DatosGenerales($array_parametros_generales);
$pdf->Arraymovimiento($array_movimiento);
$pdf->Arraymovimiento2($array_movimiento2);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
?>
