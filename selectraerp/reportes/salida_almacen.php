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
        $this->SetX(160);
        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){
        $this->Cell(0,0, utf8_decode('N° CONTROL: ')."E-".$this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["id_transaccion"],0,0,'R');
        }
        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
        $this->Cell(0,0, utf8_decode('N° CONTROL: ')."S-".$this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["id_transaccion"],0,0,'R');
        }

        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==8){
        $this->Cell(0,0, utf8_decode('N° CONTROL: ')."S-".$this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["id_transaccion"],0,0,'R');
        }

        $this->Ln(10);
        $this->SetX(160);
        $this->AddFont('New','','free3of9.php');
        $this->SetFont("New", '', 35);

        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){
        $this->Cell(0,0, "E-".$this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["id_transaccion"],0,0,'R');
        }
        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
        $this->Cell(0,0, "S-".$this->datosgenerales[0]["codigo_siga"]."-".$this->array_movimiento[0]["id_transaccion"],0,0,'R');
        }
        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==8){
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
        //3 es igual a entrada
            if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){
        $this->Cell(0,0, "ENTRADA DE ALMACEN",0,0,'C');
             }
             if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==8){
        $this->Cell(0,0, "GUIA DE DESPACHO (PEDIDO)",0,0,'C');
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
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, "Fecha :",0,0,'L');

        $this->SetX(25);
        $this->Cell(0,0,$this->array_movimiento[0]["fecha_creacion"] ,0,0,'L');

        /*
        $this->SetX(80);
        $this->Cell(0,0, "Elaborado por :",0,0,'L');

        $this->SetX(102);
        $this->Cell(0,0,utf8_decode($this->array_movimiento[0]["autorizado_por"]) ,0,0,'L');
        */

        if($this->array_movimiento[0]["id_cliente"]!=0){

        $this->SetFont('Arial','',8);
         $this->SetX(55);
         $this->Cell(0,0,utf8_decode("Cliente: ".$this->array_movimiento[0]["nombre_cliente"]."         Rif: ".$this->array_movimiento[0]["rif_cliente"]."         Direccion: ".$this->array_movimiento[0]["direccion_cliente"]) ,0,0,'L');
         $this->SetFont('Arial','',8);
          $this->Ln(5);

        }
    if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==5 ){
        $this->SetX(14);
        $this->Cell(0,0, "Almacen entrada: ".utf8_decode($this->array_movimiento[0]["almacen"])." Consignatario:".utf8_decode($this->array_movimiento[0]["almacen_procedencia"]."-".$this->array_movimiento[0]["nombre_punto_rep2"]),0,0,'L');
         $this->SetFont('Arial','',8);
          $this->Ln(5);


      }
if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3 || $this->array_movimiento[0]["tipo_movimiento_almacen"]== 4 || $this->array_movimiento[0]["tipo_movimiento_almacen"]== 8){
    $this->SetX(14);
      $this->Cell(0,0, "Conductor :".utf8_decode($this->array_movimiento7[0]["nombres"]." ".$this->array_movimiento7[0]["apellidos"])."     Cedula Conductor:".$this->array_movimiento7[0]["cedula"]."        ".utf8_decode("Teléfono:").$this->array_movimiento7[0]["telefono"]."      Ticket Entrada:".$this->array_movimiento7[0]["id_ticket"],0,0,'L');

         $this->Ln(5);
         $this->SetX(14);
         $this->Cell(0,0, "Hora Entrada :".$this->array_movimiento7[0]["hora_entrada"]."     Hora Salida:".$this->array_movimiento7[0]["hora_salida"],0,0,'L');
         $this->Ln(5);
         $this->SetX(14);
        $this->Cell(0,0, "Peso Entrada :".$this->array_movimiento7[0]["peso_entrada"]."Kg.     Peso Salida:".$this->array_movimiento7[0]["peso_salida"]."Kg.",0,0,'L');
         if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){

         if( $this->array_movimiento[0]["nombre_proveedor"]!=''){
            $proveedor="            Cliente :".$this->array_movimiento[0]["rif"]." - ".$this->array_movimiento[0]["nombre_proveedor"];
        }
        $this->Ln(5);
        $this->SetX(14);
        $this->Cell(0,0, "Nro Guia SUNAGRO :".utf8_decode($this->array_movimiento[0]["guia_sunagro"])."",0,0,'L');
        }
        $this->Ln(5);
        if( $this->array_movimiento[0]["tipo_movimiento_almacen"]==4 || $this->array_movimiento[0]["tipo_movimiento_almacen"]== 8){
         $this->SetX(14);
        $this->Cell(0,0, "Marca :".utf8_decode($this->array_movimiento[0]["marca_vehiculo"])."          Color:".utf8_decode($this->array_movimiento[0]["color"])."          Prescinto".utf8_decode($this->array_movimiento[0]["prescintos"])."           Jornada:".utf8_decode($this->array_movimiento[0]["id_jornada"]),0,0,'L');
        }
          $this->Ln(5);
      }

          if($this->array_movimiento[0]["tipo_movimiento_almacen"]==5 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==2 || $this->array_movimiento[0]["tipo_movimiento_almacen"]==4){
            $this->SetX(14);
            $this->Cell(0,0, "Almacen Salida :".utf8_decode($this->array_movimiento2[0]["almacen"])."           Ubicacion Salida :".utf8_decode($this->array_movimiento2[0]["ubicacion"])."          Almacen Destino:".utf8_decode($this->array_movimiento[0]["almacen_destino"])." - ".utf8_decode($this->array_movimiento[0]["nombre_punto_rep1"])."",0,0,'L');
            $this->Ln(5);
          }


        if( $this->array_movimiento[0]["id_documento"]!=''){
        $this->SetX(14);
        $this->Cell(0,0, "Nota de Entrega / Orden de Despacho / Factura :".$this->array_movimiento[0]["id_documento"],0,0,'L');
        $this->Ln(5);
        }

        $this->SetX(14);
        $this->Cell(0,0, "Observacion :".utf8_decode($this->array_movimiento[0]["observacion_cabecera"]),0,0,'L');

        $this->Ln(5);
        if($this->array_movimiento[0]["tipo_movimiento_almacen"]== 8)
        {
            $this->SetX(14);
            $this->Cell(0,0, "Tipo Despacho:".utf8_decode($this->array_movimiento[0]["tipo_despacho"]),0,0,'L');
             $this->Ln(3);
        }
        
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(14);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(10,10,10,10,10,10,10,10,10,10,10,10,10);
        $this->Cell(10,$width,utf8_decode('N°'),1,0,"C",0);
        $this->Cell(20,$width,'Codigo',1,0,"C",0);
        $this->Cell(90,$width,utf8_decode('Descripción'),1,0,"C",0);
        $this->Cell(20,$width,utf8_decode('Etiqueta'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Cantidad'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Peso'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Toneladas'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Ubicación Cava'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Precio C/Iva'),1,0,"C",0);
        $this->Cell(18,$width,utf8_decode('Precio Total'),1,0,"C",0);
        $this->Ln(5);
        $this->SetX(14);


    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',10);

        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
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

        $this->SetWidths(array(10,20,90,20,18,18,18,18,18,18));
        $this->SetAligns(array("C","J","C","C","C","C","C","C","C"));
        $this->SetFillColor(232,232,232,232,232,232,232,232,232);

        $subtotal = 0;
        $total_ton=0;
        $total_cantidad=0;
        $total_peso=0;
        $contador=1;
        foreach ($this->array_movimiento as $key => $value) {

            if ($value["precio_hist"]==0){

                $value["precio_hist"]=$value["coniva1"];

            }

            $precio_total=$value["precio_hist"];
            if($value["unidadxpeso"]==1 || $value["unidadxpeso"]==3){
                $transf=1000;
            }elseif($value["unidadxpeso"]==2 || $value["unidadxpeso"]==4){
                $transf=1;
            }else{
                $transf=0;
            }
            $gramos_prod=$value["pesoxunidad"]*$transf;

            if($value["cantidad_bulto"]==0){
                $bultos=0;
            }elseif($value["cantidad_item"]<$value["cantidad_bulto"]){
            $bultos=0;
            }else{
            $bultos=floor($value["cantidad_item"]/$value["cantidad_bulto"]);
            }

            $unidades_sueltas=$value["cantidad_item"]-number_format($bultos, 0, '.', '')*$value["cantidad_bulto"];
            $unidades_sueltas; 
            $this->SetLeftMargin(30);
            $width = 5;
            $this->SetX(14);
            $this->SetFont('Arial','',7);
            $this->Row(
                    array(
                    $contador,
                    $value["codigo_barras"],
                    $value["descripcion1"],
                    $value["etiqueta"],
                    $value["cantidad_item"],
                    number_format($value["peso"], 2, '.', ' ')."Kg",
                    number_format(($value["peso"])/1000, 2, '.', ' '),
                    $value["ubicacion"],
                    number_format($precio_total, 2, '.', ' '),
                    number_format($precio_total*$value["cantidad_item"], 2, '.', ' ')),1);
             if( $value["observacion_limite"] != '' && $this->array_movimiento[0]["tipo_movimiento_almacen"]==3){
                $this->SetX(14);
                $this->Cell(120,5,utf8_decode('OBSERVACIÓN : ').$value["observacion_limite"],1,1,'L');
             }
           $total_bolivares=$total_bolivares+$precio_total*$value["cantidad_item"];
           $total_ton=$total_ton+($value["peso"]/1000);
           $total_cantidad=$total_cantidad+$value["cantidad_item"];
           $total_peso=$total_peso+$value["peso"];
           $contador++;

        }
        $this->SetX(14);
        $this->Cell(140,$width,utf8_decode('TOTALES'),1,0,"C",0);
        $this->Cell(18,$width,number_format($total_cantidad, 2, '.', ' '),1,0,"C",0);
        $this->Cell(18,$width,number_format($total_peso, 2, '.', ' ')."Kg",1,0,"C",0);
        $this->Cell(18,$width,number_format($total_ton, 2, '.', ' '),1,0,"C",0);
        $this->Cell(18,$width,'',1,0,"C",0);
        $this->Cell(18,$width,number_format($total_bolivares, 2, '.', ' '),1,0,"C",0);
        $this->Ln(15);

        $this->SetX(14);
        $this->Cell(63.75,$width,utf8_decode('Elaborado Por / Transcriptor:'),1,0,"C",0);
        $this->Cell(63.75,$width,utf8_decode('Aprobado Por:'),1,0,"C",0);
        $this->Cell(63.75,$width,utf8_decode('Seguridad:'),1,0,"C",0);
        if( $this->array_movimiento5[0]['nombre_persona']!=''){
        $this->Cell(63.75,$width,utf8_decode('Despachador:'),1,0,"C",0);
        }
        if( $this->array_movimiento6[0]['nombre_persona']!=''){
        $this->Cell(63.75,$width,utf8_decode('Receptor:'),1,0,"C",0);
        }
        if( $this->array_movimiento6[0]['nombre_persona']=='' && $this->array_movimiento5[0]['nombre_persona']==''){
        $this->Cell(63.75,$width,utf8_decode('Conductor:'),1,0,"C",0);
        }

        $this->Ln(5);
        $this->SetX(14);
        $this->Cell(63.75,$width,utf8_decode('Nombre y Apellido: '.$this->array_movimiento[0]["autorizado_por"]),0,0,"J",0);
        $this->SetX(14);
        $this->Cell(63.75,25,'',1,0,"J",0);
        $this->Cell(63.75,$width,utf8_decode('Nombre y Apellido: '.$this->array_movimiento4[0]['nombre_persona']),0,0,"J",0);
        $this->SetX(77.75);
        $this->Cell(63.75,25,'',1,0,"J",0);
        $this->Cell(63.75,$width,utf8_decode('Nombre y Apellido: '.$this->array_movimiento3[0]['nombre_persona']),0,0,"J",0);
        $this->SetX(141.5);
        $this->Cell(63.75,25,'',1,0,"J",0);
        if( $this->array_movimiento5[0]['nombre_persona']!=''){
        $this->Cell(63.75,$width,utf8_decode('Nombre y Apellido: '.$this->array_movimiento5[0]['nombre_persona']),0,0,"J",0);
        }
        if( $this->array_movimiento6[0]['nombre_persona']!=''){
        $this->Cell(63.75,$width,utf8_decode('Nombre y Apellido: '.$this->array_movimiento6[0]['nombre_persona']),0,0,"J",0);
        }
        $this->Cell(63.75,$width,utf8_decode('Nombre y Apellido: '.utf8_decode($this->array_movimiento7[0]["nombres"]." ".$this->array_movimiento7[0]["apellidos"])),0,0,"J",0);
        $this->SetX(205.25);
        $this->Cell(63.75,25,'',1,0,"J",0);
        $this->Ln(5);
        $this->SetX(14);
        $this->Cell(63.75,$width,utf8_decode('C.I: '),0,0,"J",0);
        $this->Cell(63.75,$width,utf8_decode('C.I: '.$this->array_movimiento4[0]['cedula_persona']),0,0,"J",0);
        $this->Cell(63.75,$width,utf8_decode('C.I: '.$this->array_movimiento3[0]['cedula_persona']),0,0,"J",0);
         if( $this->array_movimiento5[0]['nombre_persona']!=''){
        $this->Cell(63.75,$width,utf8_decode('C.I: '.$this->array_movimiento5[0]['cedula_persona']),0,0,"J",0);
        }
        if( $this->array_movimiento6[0]['nombre_persona']!=''){
        $this->Cell(63.75,$width,utf8_decode('C.I: '.$this->array_movimiento6[0]['cedula_persona']),0,0,"J",0);
        }
        $this->Cell(63.75,$width,utf8_decode('C.I: '.$this->array_movimiento7[0]['cedula']),0,0,"J",0);
        $this->Ln(5);
        $this->SetX(14);
        $this->Cell(63.75,$width,utf8_decode('Cargo: '),0,0,"J",0);
        $this->Cell(63.75,$width,utf8_decode('Cargo: '.$this->array_movimiento4[0]['cargo']),0,0,"J",0);
         $this->Cell(63.75,$width,utf8_decode('Cargo: '.$this->array_movimiento3[0]['cargo']),0,0,"J",0);
         if( $this->array_movimiento5[0]['nombre_persona']!=''){
        $this->Cell(63.75,$width,utf8_decode('Cargo: '.$this->array_movimiento5[0]['cargo']),0,0,"J",0);
        }
        if( $this->array_movimiento6[0]['nombre_persona']!=''){
        $this->Cell(63.75,$width,utf8_decode('Cargo: '.$this->array_movimiento6[0]['cargo']),0,0,"J",0);
        }
        $this->Ln(5);
        $this->SetX(14);
        $this->Cell(63.75,$width,utf8_decode('Firma:_______________________________________'),0,0,"J",0);
        $this->Cell(63.75,$width,utf8_decode('Firma:_______________________________________'),0,0,"J",0);
        $this->Cell(63.75,$width,utf8_decode('Firma:_______________________________________'),0,0,"J",0);
        $this->Cell(63.75,$width,utf8_decode('Firma:_______________________________________'),0,0,"J",0);



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
    function Arraymovimiento3($array) {
        $this->array_movimiento3 = $array;
    }
    function Arraymovimiento4($array) {
        $this->array_movimiento4 = $array;
    }
    function Arraymovimiento5($array) {
        $this->array_movimiento5 = $array;
    }
    function Arraymovimiento6($array) {
        $this->array_movimiento6 = $array;
    }
    function Arraymovimiento7($array) {
        $this->array_movimiento7 = $array;
    }


}


$id_transaccion = @$_GET["id_transaccion"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

$operacion="Entrada";
$sql="SELECT *, REPLACE(REPLACE(pv1.nombre_punto, 'PUNTO DE VENTA - ', ''), 'CENTRO DE DISTRIBUCION -','') as nombre_punto_rep1, REPLACE(REPLACE(pv2.nombre_punto, 'PUNTO DE VENTA - ', ''), 'CENTRO DE DISTRIBUCION -','') as nombre_punto_rep2, kad.cantidad as cantidad_item,k.fecha,alm.descripcion as almacen,ubi.descripcion as ubicacion,kad.observacion as observacion_dif, cli.nombre as nombre_cliente, cli.rif as rif_cliente, cli.direccion as direccion_cliente, kad.precio as precio_hist, ite.iva as iva, k.marca as marca_vehiculo, prove.nombre as nombre_proveedor,
    concat(ite.descripcion1,' - ',m.marca,' ',ite.pesoxunidad,um.nombre_unidad) AS descripcion1, k.observacion as observacion_cabecera, k.fecha_creacion, tp.descripcion as tipo_despacho
    from kardex_almacen_detalle as kad  
    left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen  
    left join ubicacion as ubi on kad.id_ubi_entrada=ubi.id 
    left join kardex_almacen as k on k.id_transaccion=kad.id_transaccion 
    left join item as ite on kad.id_item=ite.id_item 
    left join conductores AS con ON k.id_conductor = con.id_conductor
    left join puntos_venta AS pv1 ON k.almacen_destino = pv1.codigo_siga_punto
    left join puntos_venta AS pv2 ON k.almacen_procedencia = pv2.codigo_siga_punto
    left join clientes AS cli ON k.id_cliente = cli.id_cliente
    left join marca m on m.id = ite.id_marca 
    left join unidad_medida um on um.id = ite.unidadxpeso
    left join clientes as prove on k.id_cliente=prove.id_cliente
    left join tipo_despacho as tp on k.id_tipo_despacho=tp.id
    where k.id_transaccion=".$id_transaccion;
$array_movimiento = $comunes->ObtenerFilasBySqlSelect($sql);

if(count($array_movimiento)==0){
    echo "no se encontraron registros.";
    exit;
}

$array_movimiento2 = $comunes->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,k.fecha,alm.descripcion as almacen,ubi.descripcion as ubicacion, kad.precio as precio_hist, ite.iva as iva, k.fecha_creacion, tp.descripcion as tipo_despacho
    from kardex_almacen_detalle as kad  
    left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen  
    left join ubicacion as ubi on kad.id_ubi_salida=ubi.id 
    left join kardex_almacen as k on k.id_transaccion=kad.id_transaccion 
    left join item as ite on kad.id_item=ite.id_item 
    left join tipo_despacho as tp on k.id_tipo_despacho=tp.id
    where k.id_transaccion =".$id_transaccion);

$seguridad = $comunes->ObtenerFilasBySqlSelect("select cedula_persona, nombre_persona, descripcion_rol, cargo FROM roles_firma a
LEFT JOIN kardex_almacen b on b.id_seguridad=a.id_rol
WHERE b.id_transaccion=".$id_transaccion);

$aprobado = $comunes->ObtenerFilasBySqlSelect("select cedula_persona, nombre_persona, descripcion_rol, cargo FROM roles_firma a
LEFT JOIN kardex_almacen b on b.id_aprobado=a.id_rol
WHERE b.id_transaccion=".$id_transaccion);

$despachador = $comunes->ObtenerFilasBySqlSelect("select cedula_persona, nombre_persona, descripcion_rol, cargo FROM roles_firma a
LEFT JOIN kardex_almacen b on b.id_despachador=a.id_rol
WHERE b.id_transaccion=".$id_transaccion);

$receptor = $comunes->ObtenerFilasBySqlSelect("select cedula_persona, nombre_persona, descripcion_rol, cargo FROM roles_firma a
LEFT JOIN kardex_almacen b on b.id_receptor=a.id_rol
WHERE b.id_transaccion=".$id_transaccion);

$sql="select *, b.id as id_ticket from transporte_conductores a, tickets_entrada_salida b where a.id=b.id_conductor and a.id in (select id_conductor from tickets_entrada_salida where id=".$array_movimiento[0]['ticket_entrada'].") and b.id=".$array_movimiento[0]['ticket_entrada'];

$conductor=$comunes->ObtenerFilasBySqlSelect($sql);

if(count($array_movimiento2)==0){
    echo "no se encontraron registros.";
    exit;
}
$pdf=new PDF('L','mm','letter');
$pdf->AliasNbPages();
$title='Detalle de Movimiento de Almacen';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->Arraymovimiento($array_movimiento);
$pdf->Arraymovimiento2($array_movimiento2);
$pdf->Arraymovimiento3($seguridad);
$pdf->Arraymovimiento4($aprobado);
$pdf->Arraymovimiento7($conductor);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
?>
