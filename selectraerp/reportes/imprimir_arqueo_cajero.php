<?php
ob_start();
include('config_reportes.php');
require('../fpdf/rotacion.php');
//include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
include('../../menu_sistemas/lib/NumberToLetterConverter.php');
//include('../../menu_sistemas/lib/numerosAletras.class.php');
ob_end_clean();    header("Content-Encoding: None", true);
class PDF extends PDF_Rotate {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $array_facturas_devueltas;

    function Header() {
        $comunes1= new ConexionComun();
        if($this->array_factura[0]['tipo_venta']==0){
        $obtener_nombre_cajero=$comunes1->ObtenerFilasBySqlSelect("select nombreyapellido from usuarios where cod_usuario='".$this->array_factura[0]['id_cajero']."'");
        $this->array_factura[0]['NAME']=$obtener_nombre_cajero[0]['nombreyapellido'];
    }
        if(!isset($_POST['original'])){
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        $this->RotatedText(35,150,'Copia de Arqueo',45);
        }
        $this->Image('../../includes/imagenes/'.$this->datosgenerales[0]["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        #$width = 5;
        #$this->SetX(5);
        $this->SetFont('Arial', 'B', 10);
         $this->SetTextColor(0,0,0);
        #$this->SetFillColor(10, 50, 100);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode("Fecha Emision: ") . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(7);
        $this->SetX(14);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 0, utf8_decode("ARQUEO DE CAJERO:  - ".$this->array_factura[0]['NAME']), 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, utf8_decode("FECHA DE ARQUEO:  ".$this->array_factura[0]['fecha_arqueo']), 0, 0, 'C');
        $this->Ln(7);

        $this->SetFont('Arial', '', 14);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(150));
        $this->SetAligns(array("C"));
        $this->SetFillColor(150, 150, 150);
        #$this->SetFillColor(232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232);
        
        $this->cell(150,5,'Efectivo Y Monedas',1,1,'C',1);

        $this->SetWidths(array(50,50,50));
        $this->SetAligns(array("C","C","C"));
        #$this->SetFillColor(232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232);
        $this->Row(array(utf8_decode('Denominación En Billetes'),'Cantidad','Total'), 1);

    }
    function RotatedText($x, $y, $txt, $angle){
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function dwawCell($title, $data) {
        $width = 8;
        $this->SetFont('Arial', 'B', 12);
        $y = $this->getY() * 20;
        $x = $this->getX();
        $this->SetFillColor(206, 230, 100);
        $this->MultiCell(175, 8, $title, 0, 1, 'L', 0);
        $this->SetY($y);
        $this->SetFont('Arial', '', 12);
        $this->SetFillColor(206, 230, 172);
        $w = $this->GetStringWidth($title) + 3;
        $this->SetX($x + $w);
        $this->SetFillColor(206, 230, 172);
        $this->MultiCell(175, 8, $data, 0, 1, 'J', 0);
    }

    function ChapterBody() 
    {
        $comunes1= new ConexionComun();
        if($this->array_factura[0]['tipo_venta']==0)
        {
            $obtener_nombre_cajero=$comunes1->ObtenerFilasBySqlSelect("select nombreyapellido from usuarios where cod_usuario='".$this->array_factura[0]['id_cajero']."'");
            $this->array_factura[0]['NAME']=$obtener_nombre_cajero[0]['nombreyapellido'];
        }
        $i=0;
        $sql="select a.cantidad, b.valor, b.denominacion from billetes_arqueo a inner join billetes b on a.id_billete=b.id  where id_arqueo=".$this->array_factura[0]['id'];
        $billetesValores=$comunes1->ObtenerFilasBySqlSelect($sql);

        while ($this->array_factura[$i]) 
        {
            $date = date_create($this->array_factura[$i]["fecha_emision"]);
            $fecha=date_format($date, 'd-m-Y'); 
            $width = 5;
            $this->SetFont('Arial', '', 12);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(50, 50, 50,50));
            $this->SetAligns(array("C", "C", "C","C"));
            $this->SetFillColor(0, 0, 255);
            $contador=0;
            //se recorre los valores de los billetes
            foreach ($billetesValores as $key => $value) 
            {
              
                if($contador==0)
                {
                     $this->Row(array($value['valor']." ".$value['denominacion'],number_format($value['cantidad'],'0'),number_format($value['cantidad']*$value['valor'],'2',',','.'),utf8_decode('Efectivo Según Sistema')),1);
                }
               
                if($contador==1)
                {
                    $this->Row(array($value['valor']." ".$value['denominacion'],number_format($value['cantidad'],'0'),number_format($value['cantidad']*$value['valor'],'2',',','.'),$this->array_factura[$i]['total_efectivo_sistema']),1);
                }
                if($contador==2)
                {
                    $this->Row(array($value['valor']." ".$value['denominacion'],number_format($value['cantidad'],'0'),number_format($value['cantidad']*$value['valor'],'2',',','.'),utf8_decode('Diferencia')),1);
                }
                if($contador==3)
                {
                    $this->Row(array($value['valor']." ".$value['denominacion'],number_format($value['cantidad'],'0'),number_format($value['cantidad']*$value['valor'],'2',',','.'),$this->array_factura[$i]['efectivo']-$this->array_factura[$i]['total_efectivo_sistema']),1);
                }

                if($contador==4)
                {
                    $this->Row(array($value['valor']." ".$value['denominacion'],number_format($value['cantidad'],'0'),number_format($value['cantidad']*$value['valor'],'2',',','.'),'Ingreso'),1);
                }

                if($contador==5)
                {
                    $this->Row(array($value['valor']." ".$value['denominacion'],number_format($value['cantidad'],'0'),number_format($value['cantidad']*$value['valor'],'2',',','.'), number_format(($this->array_factura[$i]['total_efectivo_sistema']+$this->array_factura[$i]['total_tj_sistema']+$this->array_factura[$i]['total_tickets_sistema']+$this->array_factura[$i]['total_credito_sistema']+$this->array_factura[$i]['total_deposito_sistema']+$this->array_factura[$i]['total_cheque_sistema'])-($this->array_factura[$i]['iva1']+$this->array_factura[$i]['iva2']+$this->array_factura[$i]['iva3']),2)),1);
                }

                if($contador!=0 &&  $contador!=1 && $contador!=2 && $contador!=3 && $contador!=4 && $contador!=5)
                {
                    $this->Row(array($value['valor']." ".$value['denominacion'],number_format($value['cantidad'],'0'),number_format($value['cantidad']*$value['valor'],'2',',','.')),1);
                }

                $contador++;
            }
           
            $this->Row(array(
                'Total Monedas',number_format($this->array_factura[$i]['monedas'],'0'),number_format($this->array_factura[$i]['monedas'],'2',',','.')),1);
            $this->SetAligns(array("L", "C", "C","C"));
            $this->Row(array(
                '','Total',$this->array_factura[$i]['efectivo']),1);
            $this->SetWidths(array(50,50,50,50));
            $this->SetAligns(array("C", "C","C","C"));
              $this->SetFillColor(150, 150, 150);
             $this->Row(array(
                'Tarjeta Recibido: ',$this->array_factura[$i]['tarjeta'],'Tarjeta Segun Sistema:',$this->array_factura[0]['total_tj_sistema']),1);
            $this->SetWidths(array(100,100));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                'Diferencia Tarjeta ',number_format($this->array_factura[$i]['tarjeta']-$this->array_factura[$i]['total_tj_sistema'],2)),1);
            //credito
           /* $this->SetWidths(array(50,50,50,50));
            $this->SetAligns(array("C", "C","C","C"));
              $this->SetFillColor(150, 150, 150);
             $this->Row(array(
                'Credito Recibido: ',$this->array_factura[$i]['credito'],'Credito Segun Sistema:',$this->array_factura[0]['total_credito_sistema']),1);
            $this->SetWidths(array(100,100));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                'Diferencia Credito ',number_format($this->array_factura[$i]['credito']-$this->array_factura[$i]['total_credito_sistema'],2)),1);*/
            //fin credito
            $this->SetWidths(array(50,50,50,50));
            $this->SetAligns(array("C", "C","C","C"));
            $this->Row(array(
                utf8_decode('Tickets Alimentación Recibido: '),$this->array_factura[$i]['tickets'],'Tickets Alimentacion Segun Sistema:',$this->array_factura[0]['total_tickets_sistema']),1);
            $this->SetWidths(array(100,100));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                'Diferencia Tickets ',number_format($this->array_factura[$i]['tickets']-$this->array_factura[$i]['total_tickets_sistema'],2)),1);
            //deposito
            $this->SetWidths(array(50,50,50,50));
            $this->SetAligns(array("C", "C","C","C"));
            $this->Row(array(
                utf8_decode('Depósito Recibido: '),$this->array_factura[$i]['deposito'],utf8_decode('Depósito Según Sistema:'),$this->array_factura[0]['total_deposito_sistema']),1);
            $this->SetWidths(array(100,100));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                utf8_decode('Diferencia Depósito'),number_format($this->array_factura[$i]['deposito']-$this->array_factura[$i]['total_deposito_sistema'],2)),1);
            //fin deposito
            //cheque
            $this->SetWidths(array(50,50,50,50));
            $this->SetAligns(array("C", "C","C","C"));
            $this->Row(array(
                utf8_decode('Cheque Recibido: '),$this->array_factura[$i]['cheque'],utf8_decode('Cheque Según Sistema:'),$this->array_factura[0]['total_cheque_sistema']),1);
            $this->SetWidths(array(100,100));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                utf8_decode('Diferencia Cheque'),number_format($this->array_factura[$i]['cheque']-$this->array_factura[$i]['total_cheque_sistema'],2)),1);
            //fin cheque
            $this->Row(array(
                'Cierre Cajero '.$this->array_factura[$i]['NAME'],'PDVAL'),1);
            $this->SetWidths(array(50,50,50,50));
            $this->SetAligns(array("C", "C","C","C"));
             $this->Row(array(
                'Nombre: ',$this->array_factura[$i]['NAME'],'Nombre:',$this->array_factura[0]['nombreyapellido']),1);
             $this->Row(array(
                'C:I: ','','C.I:',''),1);
             $this->SetWidths(array(100,50,50));
             //+$this->array_factura[$i]['credito'] se quita credito, + $this->array_factura[$i]['total_credito_sistema'] 02-07-2017
             $this->Row(array(
                'Conformes ','Total Recibido',number_format($this->array_factura[$i]['efectivo']+$this->array_factura[$i]['tickets']+$this->array_factura[$i]['tarjeta']+$this->array_factura[$i]['deposito']+$this->array_factura[$i]['cheque'],2)),1);
              $this->SetWidths(array(50,50,50,50));
             $this->Row(array(
                'Cajero Operativo','Finanzas','Ventas Segun Sistema',number_format(($this->array_factura[$i]['total_efectivo_sistema']+$this->array_factura[$i]['total_tj_sistema']+$this->array_factura[$i]['total_tickets_sistema']+$this->array_factura[$i]['total_deposito_sistema']+$this->array_factura[$i]['total_cheque_sistema'])-$this->array_factura[$i]['total_devolucion'],2)),1);
             $this->Row(array(
                ' ',' ','Diferencia Total',number_format(($this->array_factura[$i]['efectivo']+$this->array_factura[$i]['tickets']+$this->array_factura[$i]['tarjeta']+$this->array_factura[$i]['deposito']+$this->array_factura[$i]['cheque'])-(($this->array_factura[$i]['total_efectivo_sistema']+$this->array_factura[$i]['total_tj_sistema']+$this->array_factura[$i]['total_tickets_sistema']+$this->array_factura[$i]['total_deposito_sistema']+$this->array_factura[$i]['total_cheque_sistema'])-($this->array_factura[$i]['total_devolucion'])),2)),1);
             $this->SetWidths(array(200));
             $this->SetAligns(array("L"));
            
             $this->Row(array(
                'Cantidad en Letras:'.numtoletras(number_format($this->array_factura[$i]['efectivo']+$this->array_factura[$i]['tarjeta']+$this->array_factura[$i]['tickets']+$this->array_factura[$i]['deposito']+$this->array_factura[$i]['cheque'],2,'.',''))),1);
             $this->Row(array(
                utf8_decode('Observación:')),1);
                           
            $i++;
        }
    }

    function ChapterTitle($num, $label) 
    {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, $label, 0, 1, 'L', 1);
        $this->Ln(8);
    }

    function SetTitle($title) 
    {
        $this->title = $title;
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

    function ArrayFactura($array) 
    {
        $this->array_factura = $array;
    }

    function ArrayFacturasDevueltas($array) 
    {
        $this->array_facturas_devueltas = $array;
    }

    function getFacturaDevuelta($cod_factura) 
    {
        $i = 0;
        while ($factura_devuelta = $this->array_facturas_devueltas[$i]) 
        {
            if ($this->array_facturas_devueltas[$i]["cod_factura"] == $cod_factura) 
            {
                return $i;
            }
            $i++;
        }
    }

}

$comunes = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

if(isset($_POST['id']))
{
     $_GET['id']=$_POST['id'];
}
$consulta_tipo_venta=$comunes->ObtenerFilasBySqlSelect("select tipo_venta from arqueo_cajero where id='".$_GET['id']."'");
if($consulta_tipo_venta[0]['tipo_venta']==1)
{
    $array_factura = $comunes->ObtenerFilasBySqlSelect(
        "select * from arqueo_cajero as a, ".POS.".people as b, usuarios as c where a.cod_usuario=c.cod_usuario and a.id_cajero=b.id 
        and a.id='".$_GET['id']."'");
}
else
{
    $array_factura = $comunes->ObtenerFilasBySqlSelect(
        "select * from arqueo_cajero as a, usuarios as c where a.cod_usuario=c.cod_usuario  
        and a.id='".$_GET['id']."'");   
}

$array_facturas_devueltas = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_devolucion");
$pdf = new PDF('L', 'mm', 'A3');
$title = 'Arqueo De Cajero #'.$_GET['id']."Cajero:".$array_factura[0]['NAME'];
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->ArrayFacturasDevueltas($array_facturas_devueltas);
$pdf->SetTitle($title);
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
$comunes->cerrar();
?>
