<?php

include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $fecha, $fecha2;

    function Header() {

        $width = 10;
        //$this->Image('../imagenes/banner_superior.jpg',10,5,190);
        $comunes = new ConexionComun();
        $array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM tipo_cliente WHERE cod_tipo_cliente =" . $_GET["cod_tipo_cliente"]);
        $this->SetY(15);
        $this->SetLeftMargin(15);
        $width = 5;
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(10, 50, 100);
        #$this->Image('../imagenes/SiSalud.jpg', 6, 10, 35, 19, 'JPG', '');
        $this->Cell(130, 0, 'LISTADO DE CLIENTES', 0, 0, 'R');
        $this->Ln(7);
        $this->SetFont('Arial', 'B', 11);
        //$this->Cell(0,8,'Tipo de Cliente',0,0,"C");
        $this->Cell(0, 8, utf8_decode($array_parametros_generales[0]["descripcion"]), 0, 0, "C");
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);

        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }

    function dwawCell($title, $data) {
        $width = 8;
        $this->SetFont('Arial', 'B', 12);
        $y = $this->getY() * 20;
        $x = $this->getX();
        $this->SetFillColor(206, 230, 100);
        $this->MultiCell(175, 8, $title, 0, 1, 'L', 0);
        $this->SetY($y);
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(206, 230, 172);
        $w = $this->GetStringWidth($title) + 3;
        $this->SetX($x + $w);
        $this->SetFillColor(206, 230, 172);
        $this->MultiCell(175, 8, $data, 0, 1, 'J', 0);
    }

    function ChapterBody() {

        $this->SetWidths(array(50, 30, 40));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetFillColor(232, 232, 232);

        $this->SetLeftMargin(35);
        $width = 5;
        $this->SetX(45);
        $this->SetFont('Arial', 'B', 8);

        $this->Row(array('Nombre', 'R.I.F.', 'Telefono'), 1);





        $this->SetWidths(array(50, 30, 40));
        $this->SetAligns(array("L", "C", "C"));
        $this->SetFillColor(10, 10, 10);

        $totalDebito = 0;
        $totalCredito = 0;
        $totalVentasConIva = 0;
        $totalVentasNoGravadas = 0;
        $totalBaseImponible = 0;
        $totalIva = 0;
        $totalIvaRet = 0;
        $i = 0;
        while ($this->array_factura[$i]) {

            $this->SetLeftMargin(35);
            $width = 5;
            $this->SetX(45);
            $this->SetFont('Arial', '', 6);


            $this->Row(
                    array(
                $this->array_factura[$i]["nombre"],
                $this->array_factura[$i]["rif"],
                $this->array_factura[$i]["telefonos"],), 1);

            $totalDebito+=0;
            $totalCredito+=0;
            $totalVentasConIva+= ( $this->array_factura[$i][montoItemsFactura] + $this->array_factura[$i][ivaTotalFactura]);
            $totalVentasNoGravadas+=0;
            $totalBaseImponible+=$this->array_factura[$i][totalizar_base_imponible];
            $totalIva+=$this->array_factura[$i][ivaTotalFactura];
            $totalIvaRet+=$this->array_factura[$i][totalizar_total_retencion];
            $i++;
        }
        $this->Ln(1);


//:::::::::::::::::::::::::::::::::::::::::::AQUI VA TOTAL::::::::::::::::::::::::::::::::::::::::::::::::::::::
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, "$label", 0, 1, 'L', 1);
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

    function ArrayFactura($array) {
        $this->array_factura = $array;
    }

}

$codigo = @$_GET["codigo"]; // Se recibe el codigo del cliente por GET
$fecha2 = @$_GET['fecha2'];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");


//echo ("SELECT * FROM clientes where cod_tipo_cliente =".$_GET["cod_tipo_cliente"]);

$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM clientes where cod_tipo_cliente =" . $_GET["cod_tipo_cliente"]);

//$fechaz=$fecha."-01";


/* $array_factura =   $comunes->ObtenerFilasBySqlSelect("
  SELECT f.*, c.nombre, c.direccion, c.nit,c.cod_cliente, c.telefonos,c.direccion, fd.*, c.rif FROM factura f inner join clientes c on c.id_cliente = f.id_cliente inner join factura_detalle fd on fd.id_factura = f.id_factura   where month(f.fechaFactura) = month('".$fechaz."') and year(f.fechaFactura) = year('".$fechaz."')"); */

//$mes=mesaletras(substr($fecha,5,2));

$pdf = new PDF('P', 'mm', 'A4');
$title = 'Listado de Clientes.';
//$fecha=mesaletras(substr($fecha,5,2))
$pdf->fecha = $fecha;
$pdf->fecha2 = $fecha2;
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>


