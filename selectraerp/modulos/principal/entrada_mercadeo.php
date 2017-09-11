<?php
include('config_reportes.php');
include('fpdf.php');

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
        $this->Cell(0,0, "Cod. Movimiento: ".$this->array_mercadeo[0]["id_estudio"],0,0,'R');
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
        //3 es igual a entrada
            //if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){
        $this->Cell(0,0, "REPORTE ESTUDIO DE MERCADO",0,0,'C');
             //}
                //2 4 es igual a salida
            if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
        $this->Cell(0,0, "REPORTE DETALLE DE SALIDA",0,0,'C');
             }
                //5  es igual a traslado
            if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==5){
        $this->Cell(0,0, "REPORTE DETALLE DE TRASLADO",0,0,'C');
             }
        $this->Ln(10);

//agregado 21/02/2014 el almacen la ubicacion y autorizado por 
        $this->SetX(14);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, "Fecha :",0,0,'L');

        $this->SetX(25);
        $this->Cell(0,0,$this->array_mercadeo[0]["fecha"] ,0,0,'L');

        $this->SetX(80);
        $this->Cell(0,0, "Autorizado por :",0,0,'L');

        $this->SetX(105);
        $this->Cell(0,0,utf8_decode($this->array_mercadeo[0]["autorizado_por"]) ,0,0,'L');

        $this->Ln(5);
    if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==5 ){
        $this->SetX(14);
        $this->Cell(0,0, "Almacen entrada :",0,0,'L');

         $this->SetX(37);
        $this->Cell(0,0, utf8_decode($this->array_movimiento[0]["almacen"]),0,0,'L');

         

        $this->SetX(80);
        $this->Cell(0,0, "ubicacion entrada :",0,0,'L');

         $this->SetX(105);
         $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["ubicacion"]) ,0,0,'L');

          $this->Ln(5);
      }
          if($this->array_movimiento[0]["tipo_movimiento_almacen"]==5 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
            $this->SetX(14);
            $this->Cell(0,0, "Almacen Salida :",0,0,'L');

            $this->SetX(37);
            $this->Cell(0,0, utf8_decode($this->array_movimiento2[0]["almacen"]),0,0,'L');         

            $this->SetX(80);
            $this->Cell(0,0, "ubicacion Salida :",0,0,'L');

            $this->SetX(105);
            $this->Cell(0,0,utf8_decode($this->array_movimiento2[0]["ubicacion"]) ,0,0,'L');

            $this->Ln(5);
          }
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(14);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(10,10,10,10,10,10,10,10,10);
        $this->Cell(20,$width,'Fecha',1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Id del Estudio'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Realizado por'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Id del Rubro'),1,0,"C",0);
        $this->Cell(25,$width,utf8_decode('Nombre del Rubro'),1,0,"C",0);
        $this->Cell(25,$width,utf8_decode('precio'),1,0,"C",0);
        $this->Ln(6);


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



        //$conn = new rp_Connect();
        //$conn->SQL("select * from esquema.almacen_ubicacion");




        //aqui metemos los parametros del contenido
        $this->SetWidths(array(20,122,18,18,18));
        $this->SetAligns(array("C","J","C","C","C"));
        $this->SetFillColor(232,232,232,232,232);
        // $cantidaditems = $this->array_movimiento[0]["numero_item"];

        $subtotal = 0;
        // for($i=0;$i<$cantidaditems;$i++) {
        //     $this->SetLeftMargin(30);
        //     $width = 5;
        //     $this->SetX(14);
        //     $this->SetFont('Arial','',7);

        //     $this->Row(
        //             array(  $this->array_movimiento[$i]["cod_item"],
        //             $this->array_movimiento[$i]["descripcion1"],
        //             $this->array_movimiento[$i]["cantidad_item"]),1);

        // }
        foreach ($this->array_mercadeo as $key => $value) {
            $this->SetLeftMargin(30);
            $width = 6;
            $this->SetX(14);
            $this->SetFont('Arial','',7);
            $this->Row(
                    array($value["fecha"],
                    $value["id_estudio"],
                    $value["autorizado_por"],
                    $value["ID"],
                    $value["nombre"],
                    $value["precio"]),1);
            //$this->SetX(14);
             /*if( $value["cantidad_item"] != $value["c_esperada"] && $this->array_mercadeo[0]["tipo_movimiento_almacen"]==3){
                 $this->Cell(196,5,'Observacion : '.$value["observacion_dif"],1,1,'L');
             }*/
           

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

    function Arraymercadeo($array){
        $this->array_mercadeo = $array;
    }


}


$id_estudio = @$_GET["id_estudio"];
$comunes = new ConexionComun();
$bdCentral = "selectrapyme_central";

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
/*
$operacion="Entrada";
$array_movimiento = $comunes->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,k.fecha,alm.descripcion as almacen,ubi.descripcion as ubicacion,kad.observacion as observacion_dif
    from kardex_almacen_detalle as kad  left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen  left join ubicacion as ubi on kad.id_ubi_entrada=ubi.id left join kardex_almacen as k on k.id_transaccion=kad.id_transaccion left join item as ite on kad.id_item=ite.id_item where kad.id_transaccion=".$id_transaccion);

if(count($array_movimiento)==0){
    echo "no se encontraron registros.";
    exit;
}

$array_movimiento2 = $comunes->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,k.fecha,alm.descripcion as almacen,ubi.descripcion as ubicacion
    from kardex_almacen_detalle as kad  left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen  left join ubicacion as ubi on kad.id_ubi_salida=ubi.id left join kardex_almacen as k on k.id_transaccion=kad.id_transaccion left join item as ite on kad.id_item=ite.id_item where kad.id_transaccion =".$id_transaccion);
if(count($array_movimiento2)==0){
    echo "no se encontraron registros.";
    exit;
}*/

$sql="SELECT im.fecha,im.id_estudio,im.autorizado_por,imd.id_detalle_producto AS ID,
                rem.nombre_rubro AS nombre,imd.precio AS precio
                FROM $bdCentral.investigacion_mercado_detalle imd
                JOIN $bdCentral.investigacion_mercado im ON im.id_estudio = imd.id_estudio
                LEFT JOIN $bdCentral.rubros_estudio_mercado rem ON rem.id_rubro = imd.id_producto
                WHERE imd.id_estudio =" .$id_estudio;

$array_mercadeo = $comunes->ObtenerFilasBySqlSelect($sql);
if(count($array_mercadeo)==0){
    echo "no se encontraron registros.";
    exit;
}

$pdf=new PDF('P','mm','letter');
$title='Detalle de Movimiento de Almacen';
$pdf->DatosGenerales($array_parametros_generales);
/*$pdf->Arraymovimiento($array_movimiento);
$pdf->Arraymovimiento2($array_movimiento2);*/
$pdf->Arraymercadeo($array_mercadeo);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();

?>
