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
        $this->Cell(0,0, "Cod. Estudio: ".$this->array_mercadeo[0]["id_estudio"],0,0,'R');
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
        $this->Cell(0,0, "COTIZACION DE MERCADO",0,0,'C');
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

//agregado 11/04/2016 autorizado por, fecha y estado del mercadeo 
        $this->SetX(14);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, "Fecha :",0,0,'L');

        $this->SetX(25);
        $this->Cell(0,0,$this->array_mercadeo[0]["fecha"] ,0,0,'L');

        $this->SetX(60);
        $this->Cell(0,0, "Autorizado por :",0,0,'L');

        $this->SetX(82);
        $this->Cell(0,0,utf8_decode($this->array_mercadeo[0]["autorizado_por"]) ,0,0,'L');

        $this->SetX(130);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, "Proveedor :",0,0,'L');

        $this->SetX(147);
        $this->Cell(0,0,utf8_decode($this->array_mercadeo[0]["proveedor"]) ,0,0,'L');

        $this->SetX(190);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, "RIF :",0,0,'L');

        $this->SetX(197);
        $this->Cell(0,0,utf8_decode($this->array_mercadeo[0]["rif"]) ,0,0,'L');

        /*$this->Ln(4); Colocando esto antes de cada bloque de codigo, podremos saltar en las lineas del PDF
         y colocarlo donde se necesite
        HZ*/
        $this->SetX(158);
        $this->Cell(0,0,$this->array_mercadeo[0]["estado"] ,0,0,'L');

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
        $this->Ln(4);
        $this->SetX(14);
        $this->SetFont('Arial','',9);
        $this->SetFillColor(10,10,10,10,10,10,10,10,10);
        //$this->Cell(25,$width,'Fecha',1,0,"C",0);
        //$this->Cell(25,$width,utf8_decode('Id del Estudio'),1,0,"C",0);
        $this->Cell(10,$width,utf8_decode('ID'),1,0,"C",0);
        $this->Cell(30,$width,utf8_decode('Código de Barras'),1,0,"C",0);
        $this->Cell(22,$width,utf8_decode('Descripcion'),1,0,"C",0);
        $this->Cell(22,$width,utf8_decode('Costo Unitario'),1,0,"C",0);
        $this->Cell(25,$width,utf8_decode('Costo Operativo'),1,0,"C",0);
        $this->Cell(21,$width,utf8_decode('Costo sin IVA'),1,0,"C",0);
        $this->Cell(23,$width,utf8_decode('Costo con IVA'),1,0,"C",0);
        $this->Cell(25,$width,utf8_decode('Precio Sugerido'),1,0,"C",0);
        $this->Cell(32,$width,utf8_decode('Margen de Ganancia'),1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('PVP'),1,0,"C",0);
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



        //$conn = new rp_Connect();
        //$conn->SQL("select * from esquema.almacen_ubicacion");




        //aqui metemos los parametros del contenido
        //$this->SetWidths(array(25,25,30,22,40,20));
        $this->SetWidths(array(10,30,22,22,25,21,23,25,32,20));
        //$this->SetAligns(array("C","C","C","C","C","C"));
        $this->SetAligns(array("C","C","C","C","C","C","C","C","C","C"));
        //$this->SetFillColor(232,232,232,232,232,232);
        $this->SetFillColor(232,232,232,232,232,232,232,232,232,232);
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
        $i = 0;
        foreach ($this->array_mercadeo as $key => $value) {
            $i++;
            $this->SetLeftMargin(30);
            $width = 7;
            $this->SetX(14);
            $this->SetFont('Arial','',9);
            $this->Row(
                    array(//$value["fecha"],
                    $i,
                    $value["COD"],
                    $value["nombre"],
                    //$value["precio"],
                    number_format($value["costo_unitario"], 2, ',', ''),
                    $value["costo_operativo"],
                    //number_format($value["costo_operativo"], 2, ',', ''),
                    number_format($value["costo_sin_iva"], 2, ',', ''),
                    //$value["costo_iva"],
                    number_format($value["costo_iva"], 2, ',', ''),
                    //$value["precio_sugerido"],
                    number_format($value["precio_sugerido"], 2, ',', ''),
                    $value["margen_ganancia"],
                    //number_format($value["margen_ganancia"], 2, ',', ''),
                    //$value["pvp"]),
                    number_format($value["pvp"], 2, ',', '')),
                    1);
            //$this->SetX(14);
             /*if( $value["cantidad_item"] != $value["c_esperada"] && $this->array_mercadeo[0]["tipo_movimiento_almacen"]==3){
                 $this->Cell(196,5,'Observacion : '.$value["observacion_dif"],1,1,'L');
             }*/
           

        }

    }

    function ChapterTitle($num,$label) {
        $this->SetFont('Arial','',20);
        $this->SetFillColor(232,232,232,232,232);
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

    function Arraymercadeo($array){
        $this->array_mercadeo = $array;
    }


}


$id_estudio = @$_GET["id_estudio"];
$comunes = new ConexionComun();
$bdCentral = "selectrapyme_central";
$bdpyme = "selectrapyme";

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

$sql="SELECT cm.fecha,cm.id_estudio,cm.autorizado_por,cmd.id_detalle_producto AS ID,i.codigo_barras AS COD,
CONCAT(i.descripcion1,' ',m.marca) AS nombre,cmd._precio_unitario AS costo_unitario,
CONCAT(cmd._operatividad,' ','(',cmd._porcentaje_operatividad,'%',')') AS costo_operativo,
cmd.costo_sin_iva AS costo_sin_iva,cmd._costo_iva AS costo_iva,cmd._precio_sugerido AS precio_sugerido,
CONCAT(cmd._margen_ganancia,' ','(',_porcentaje_ganancia,'%',')') AS margen_ganancia,cmd._pvp AS pvp,
pv.descripcion AS proveedor, pv.rif AS rif
FROM $bdCentral.cotizacion_mercado_detalle cmd
INNER JOIN $bdCentral.cotizacion_mercado cm ON cm.id_estudio = cmd.id_estudio
INNER JOIN $bdpyme.item i ON i.id_item = cmd.id_producto
INNER JOIN $bdpyme.marca m ON m.id=i.id_marca
INNER JOIN $bdpyme.proveedores pv ON pv.id_proveedor = cm.proveedores
WHERE cmd.id_estudio =" .$id_estudio;

/*$sql="SELECT imd.id_detalle_producto AS ID,i.descripcion1 AS nombre,imd._precio_sugerido AS precio_sugerido,
                imd.precio AS precio,
                imd._costo_iva AS costo_iva,
                imd._margen_ganancia AS margen_ganancia,
                imd._pvp AS pvp
                FROM $bdCentral.cotizacion_mercado_detalle imd
                INNER JOIN $bdCentral.cotizacion_mercado im ON im.id_estudio = imd.id_estudio
                INNER JOIN $bdSelectrapyme.item i ON i.id_item  = imd.id_producto
                WHERE imd.id_estudio =" .$_GET["id_estudio"]);*/

$sql.=" ORDER BY nombre,ID";

$array_mercadeo = $comunes->ObtenerFilasBySqlSelect($sql);
if(count($array_mercadeo)==0){
    echo "no se encontraron registros.";
    exit;
}

//$pdf=new PDF('P','mm','letter');
$pdf = new PDF('L', 'mm', 'A4');
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
