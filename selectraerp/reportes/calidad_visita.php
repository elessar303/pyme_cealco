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
        $this->Cell(0,0, "Acta # ",0,0,'L');

        $this->Cell(0,0, $this->array_movimiento[0]['cod_acta_visita'],0,0,'R');
      

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
        
        $this->Cell(0,0, "ACTA DE CALIDAD ".$this->array_movimiento[0]['cod_acta_visita'],0,0,'C');
        
        $this->Ln(10);

//agregado 21/02/2014 el almacen la ubicacion y autorizado por 
        $this->SetX(14);
        $this->SetFont('Arial','',8);
        $this->Cell(0,0, "Fecha :",0,0,'L');

        $this->SetX(25);
        $this->Cell(0,0,$this->array_movimiento[0]["fecha_visita"] ,0,0,'L');

        
        
        $this->ln(5);
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

        $this->ln(10);

      

          
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
        $this->SetLeftMargin(50);
        $width = 5;
        $this->SetX(14);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(10,10,10,10,10,10,10,10,10);
        $this->Cell(20,$width,'Codigo',1,0,"C",0);
        $this->Cell(65,$width,utf8_decode('Nombre'),1,0,"C",0);
        
        $this->Cell(18,$width,utf8_decode('Telefono'),1,0,"C",0);
        
        $this->Cell(78,$width,utf8_decode('Tipo Visita'),1,0,"C",0);
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
        $this->SetWidths(array(20,65,18,78));
        $this->SetAligns(array("C","J","C","C"));
        $this->SetFillColor(232,232,232,232,232);
        // $cantidaditems = $this->array_movimiento[0]["numero_item"];

        $subtotal = 0;
        
        foreach ($this->array_movimiento as $key => $value) {
            $this->SetLeftMargin(30);
            $width = 5;
            $this->SetX(14);
            $this->SetFont('Arial','',7);
            
            $this->Row(
                    array(  $value["cod_acta_visita"],
                            $value["nombre"],
                            $value["lote"],
                            
                            $value["visita"]),1);
            $comunes=new ConexionComun();
            $datos=$comunes->ObtenerFilasBySqlSelect("select observacion, recomendacion from visita_observaciones where cod_visita=".$value['id']."");
            
           if(count($datos)>0)
           {
            $i=1;
            foreach ($datos as $key => $value1) 
                {
                    $this->SetX(14);
                    $this->SetFont('Times','B',6);
                    $this->Cell(20,5,'Observacion '.$i.' :',1,0,'L');
                    $this->SetFont('Arial','',7);
                    $this->Cell(161,5,$value1["observacion"],1,1,'L');
                    $this->SetX(14);
                    $this->SetFont('Times','B',6);
                    $this->Cell(20,5,'Recomendacion '.$i.' :',1,0,'L');
                    $this->SetFont('Arial','',7);
                    $this->Cell(161,5,$value1["recomendacion"],1,1,'L');
                $i++;
                }
            }
            else
            {
                    $this->SetX(14);
                    $this->SetFont('Times','B',6);
                    $this->Cell(20,5,'Observacion :',1,0,'L');
                    $this->SetFont('Arial','',7);
                    $this->Cell(161,5,'Sin Observaciones',1,1,'L');
                    $this->SetX(14);
                    $this->SetFont('Times','B',6);
                    $this->Cell(20,5,'Recomendacion :',1,0,'L');
                    $this->SetFont('Arial','',7);
                    $this->Cell(161,5,'Sin Recomendaciones',1,1,'L');
            }
            $comunes->cerrar();
             
           

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



//if(@$_GET["id"]==''){

$consulta="and a.cod_acta_visita='".@$_GET["cod_acta"]."'";
//}else{
//$id_transaccion = @$_GET["id"];
//$consulta="and a.id=".@$_GET["id"];
//}
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
$operacion="Entrada";

$array_movimiento = $comunes->ObtenerFilasBySqlSelect("select a.id, e.nombreyapellido as nombre, a.cod_acta_visita, c.descripcion as almacen, d.descripcion as ubicacion, b.descripcion_visita as visita, date_format(date(a.fecha_visita),'%d/%m/%Y') as fecha_visita  from calidad_visitas as a, tipo_visitas as b, almacen as c, ubicacion as d, usuarios as e
    where a.usuario=e.cod_usuario and a.tipo_visita=b.id and a.almacen_visita=c.cod_almacen  and a.ubicacion_visita=d.id ".$consulta);
//echo "select a.id, e.nombreyapellido as nombre, a.cod_acta_visita, c.descripcion as almacen, d.descripcion as ubicacion, b.descripcion_visita as visita, date_format(date(a.fecha_visita),'%d/%m/%Y') as fecha_visita  from calidad_visitas as a, tipo_visitas as b, almacen as c, ubicacion as d, usuarios as e
  //  where a.usuario=e.cod_usuario and a.tipo_visita=b.id and a.almacen_visita=c.cod_almacen  and a.ubicacion_visita=d.id ".$consulta; exiT();
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
