<?php
ob_start();

include('config_reportes.php');
//include('fpdf.php');

ob_end_clean();    header("Content-Encoding: None", true);

require('../fpdf/rotacion.php');

class PDF extends PDF_Rotate
{

 public $datosgenerales; public $datoscampos;
function Header()
    {    
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        //$this->RotatedText(35,190,'Comprobante Contable ',45);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(0,0,0);
        $this->Image('../../includes/imagenes/'.$this->datosgenerales[0]["img_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(10, 50, 100);
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, "Fecha Emision: " . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 12);
        $this->SetX(17);
        $this->Cell(0, 0, utf8_decode("Comprobante Contable ")." #".$this->datoscampos[0]['id']." fecha:  ". $this->datoscampos[0]['fecha1']  , 0, 0, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(60, 35, 35));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetX(25);
        $this->Row(array( utf8_decode('Cuenta'), 'DEBE', 'HABER'), 1);
        $this->SetWidths(array(60, 35, 35));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->SetX(25);
        $totalingreso=0;
        foreach ($this->datoscamposingresos as $key => $value) 
        {
            $this->Row(array('Ingreso ('.$value['tipo_venta'].')', '', $value['monto']), 1);
            $this->SetX(25);
            $totalingreso+=$value['monto'];
        }

        if(($this->datoscampos[0]["iva1"]+$this->datoscampos[0]["iva2"]+$this->datoscampos[0]["iva3"])>0)
        {
            $this->Row(array('IVA', '', number_format($this->datoscampos[0]["iva1"]+$this->datoscampos[0]["iva2"]+$this->datoscampos[0]["iva3"],2,',', '.')),1);
            $this->SetX(25);
        }
        if(($this->datoscampos[0]["otros_ingresos"])>0)
        {
            $this->Row(array('Otros Ingresos', '', number_format($this->datoscampos[0]["otros_ingresos"],2,',', '.')),1);
            $this->SetX(25);
        }
        if(($this->datoscampos[0]["perdida"])>0)
        {
            $this->Row(array('PERDIDA', number_format($this->datoscampos[0]["perdida"],2,',', '.'), ''),1);
            $this->SetX(25);
        }
        if(($this->datoscampos[0]["cxc"])>0)
        {
            $this->Row(array('CXC', number_format($this->datoscampos[0]["cxc"],2,',', '.'), ''),1);
            $this->SetX(25);
        }
        if(($this->datoscampos[0]["caja"])>0)
        {
            $this->Row(array('CAJA', number_format($this->datoscampos[0]["caja"],2,',', '.'), ''),1);
            $this->SetX(25);
        }

        $this->SetFont('Arial', 'B', 10);
        $this->SetX(25);
        $this->SetWidths(array(60, 35, 35));
        $this->SetAligns(array("C", "C", "C"));
        $this->Row(array('', 'Total', 'Total' ), 1);
        $this->SetWidths(array(60, 35, 35));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetX(25);
        $this->Row(array('', number_format(($this->datoscampos[0]["caja"]+($this->datoscampos[0]["perdida"]+$this->datoscampos[0]["cxc"])), '2', ',', '.'),number_format((($totalingreso+$this->datoscampos[0]["iva1"]+$this->datoscampos[0]["iva2"]+$this->datoscampos[0]["iva3"]+$this->datoscampos[0]["otros_ingresos"])), '2', ',', '.')  ), 1);
        $this->SetX(25);
        $this->SetWidths(array(130));
        $this->SetAligns(array("L"));
        $this->datoscampos[0]['observacion']=str_replace('<br>', " -- ", $this->datoscampos[0]['observacion']);
        $this->datoscampos[0]['observacion']=str_replace(':', " CXC : ", $this->datoscampos[0]['observacion']);
        $this->Row(array(utf8_decode('Observación: '.$this->datoscampos[0]['observacion']) ), 1);
        $this->Ln(10);
    }

    function ChapterBody() {


    }
    function RotatedText($x, $y, $txt, $angle){
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
    }
    function Footer()
    {
    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial','I',8);
    // Print centered page number
    $this->Cell(0,10,'Nro '.$this->PageNo(),0,0,'C');
    }

    function DatosGenerales($array) {
        $this->datosgenerales = $array;
    }

    function DatosCampos($array) {
        $this->datoscampos = $array;
    }

    function DatosCamposIngresos($array) {
        $this->datoscamposingresos = $array;
    }

        function DatosDetalle($array) {
        $this->datosdetalle = $array;
    }
        function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }
}
$inicio=date_format(date_create(@$_POST["fecha"]), 'Y-m-d');
$nro=isset($_POST["comprobante"]) ? $_POST["comprobante"] : null;
//$id_transaccion = @$_GET["id_transaccion"];
if($nro!=null)
{
    $nro=" and a.id=".$nro;
    $inicio=null;
}
else
{
    $inicio=" and date(a.fecha) between '".$inicio."' and  '".$inicio."'";
}
$comunes = new ConexionComun();
$datosgenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

//obtener la fecha de apertura
/*$fecha=$comunes->ObtenerFilasBySqlSelect("select apertura_date as fecha from apertura_tienda  order by id_apertura desc limit 1");

*/
$datoscampos=$comunes->ObtenerFilasBySqlSelect("select  date_format( a.fecha, '%d-%m-%Y' ) as fecha1, a.* from comprobante as a  where (a.banco is null || a.banco='0.00') ".$inicio." ".$nro);


$datoscamposingresos=$comunes->ObtenerFilasBySqlSelect("select sum(b.monto) as monto, c.descripcion as tipo_venta from comprobante as a inner join ingresos_detalles as b on a.id=b.id_comprobante inner join departamentos as c on b.tipo_ingreso=cod_departamento where   (a.banco is null || a.banco='0.00')  ".$inicio." ".$nro." group by cod_departamento");
//$datoscomprobante=$comunes->ObtenerFilasBySqlSelect($consulta);
$pdf = new PDF();
$pdf->DatosGenerales($datosgenerales);
$pdf->DatosCampos($datoscampos);
$pdf->DatosCamposIngresos($datoscamposingresos);
//$pdf->DatosDetalle($datosdetalle);
$pdf->SetTitle('Comprobante Contable'); 
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetX(17);


$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output('Comprobante_Contable'.$_POST["fecha"].'.pdf','I');
ob_end_flush();
?>
