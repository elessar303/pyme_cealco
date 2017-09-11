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

    function Header() 
    {
        $comunes1= new ConexionComun();
        if($this->array_factura[0]['tipo_venta']==0)
        {
            $obtener_nombre_cajero=$comunes1->ObtenerFilasBySqlSelect("select nombreyapellido from usuarios where cod_usuario='".$this->array_factura[0]['id_cajero']."'");
            $this->array_factura[0]['NAME']=$obtener_nombre_cajero[0]['nombreyapellido'];
        }
        if(!isset($_POST['original']))
        {
            $this->SetFont('Arial','B',50);
            $this->SetTextColor(255,192,203);
            $this->RotatedText(35,150,'Consolidado Arqueos',45);
        }

        $this->Image('../../includes/imagenes/'.$this->datosgenerales[0]["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(0,0,0);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode("Fecha Emision: ") . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetX(14);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("CONSOLIDADOS ARQUEOS:"), 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, utf8_decode("FECHA DE ARQUEOS:  ".$_POST['fecha']." - ".$_POST['fecha2']), 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(150));
        $this->SetAligns(array("C"));
        $this->SetFillColor(150, 150, 150);
        $this->cell(150,5,'Efectivos Y Monedas',1,1,'C',1);
        $this->SetWidths(array(50,50,50));
        $this->SetAligns(array("C","C","C"));
        $this->Row(array(utf8_decode('Denominación En Billetes'),'Cantidad','Total'), 1);

    }
    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

    function Footer() 
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function dwawCell($title, $data) 
    {
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
        $ids="";
        $totales="";
        while ($this->array_factura[$i]) 
        {
            //convertimos en string los id de arqueos para agregarlos en el in del where(cambio necesario billetes dinamicos)
            foreach ($this->idarqueos as $key => $value) 
            {
                $ids.="'".$value['id']."', ";
            }
            //eliminando la coma y el espacio
            $ids=substr($ids, 0,-2);
            //consulta donde se obtiene billetes y valor
            $billetesValores=$comunes1->ObtenerFilasBySqlSelect("select sum(a.cantidad) as cantidad, b.valor, b.denominacion from billetes_arqueo a inner join billetes b on a.id_billete=b.id  where id_arqueo  in (".$ids.") group by b.valor");
            $date = date_create($this->array_factura[$i]["fecha_emision"]);
            $fecha=date_format($date, 'd-m-Y'); 
            $width = 5;
            $this->SetFont('Arial', '', 8);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(50, 50, 50,50));
            $this->SetAligns(array("C", "C", "C","C"));
            $this->SetFillColor(0, 0, 255);
           //se recorre los valores de los billetes
            foreach ($billetesValores as $key => $value) 
            {
                $this->Row(array($value['valor']." ".$value['denominacion'],number_format($value['cantidad'],'0'),number_format($value['cantidad']*$value['valor'],'2',',','.')),1);
                $totales+=($value['cantidad']*$value['valor']);
            }
            $total_arqueo=$totales+$this->array_factura[$i]['monedas']+$this->array_factura[$i]['tarjeta']+$this->array_factura[$i]['tickets']+$this->array_factura[$i]['credito'];
            $this->SetWidths(array(100,50));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                    'Monedas',number_format($this->array_factura[$i]['monedas'],'2',',','.')),1);
            $this->SetAligns(array("L", "C", "C","C"));
            $this->SetWidths(array(100,50));
            $this->SetAligns(array("C", "C"));
            $this->SetFillColor(100,50);
            $this->Row(array('Tarjeta: ',number_format($this->array_factura[$i]['tarjeta'],'2',',','.')),1);
            $this->SetWidths(array(100,50));
            $this->SetAligns(array("C", "C"));
            $this->SetFillColor(100,50);
            $this->Row(array('Credito: ',number_format($this->array_factura[$i]['credito'],'2',',','.')),1);
            $this->SetWidths(array(100,50));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                    'Tickets Alimentacion: ',number_format($this->array_factura[$i]['tickets'],'2',',','.')),1);
            $this->SetWidths(array(100,50));
            $this->SetAligns(array("C", "C"));
            $this->Row(array(
                    'Total: ',number_format($total_arqueo,'2',',','.')),1);
            $i++;
        }//fin del while
    } //end function

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
    //agregando id arqueos
    function IdArqueo($array) 
    {
        $this->idarqueos = $array;
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
//sql para obtener el total de moneas, efectivo y tarjetas
$sql="SELECT  sum(`monedas`) as `monedas`, sum(`efectivo`) as `efectivo`, sum(`tarjeta`) as `tarjeta`, sum(`tickets`) as `tickets`, sum(`credito`) as  `credito`
FROM `arqueo_cajero`
WHERE `fecha_arqueo` between '".$_POST['fecha']." 00:00:00' and '".$_POST['fecha2']." 23:59:59'";
//sql para obtener los ids de arqueos necesarios para la busqueda de los billetes
$idarqueos=$comunes->ObtenerFilasBySqlSelect("Select id FROM `arqueo_cajero`
WHERE `fecha_arqueo` between '".$_POST['fecha']." 00:00:00' and '".$_POST['fecha2']." 23:59:59'");
$array_factura = $comunes->ObtenerFilasBySqlSelect($sql);
$pdf = new PDF('P', 'mm', 'A4');
$title = 'Consolidado Arqueos';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->IdArqueo($idarqueos);
$pdf->ArrayFactura($array_factura);
$pdf->SetTitle($title);
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output();
ob_end_flush();
$comunes->cerrar();
?>