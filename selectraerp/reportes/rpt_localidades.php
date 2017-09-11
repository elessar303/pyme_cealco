<?php

# Modificado el sabado, 28 de enero de 2012
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
#require_once("../libs/php/ajax/numerosALetras.class.php");

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $array_despacho;

    function Header() {

        if ($this->array_factura[0]["cod_estatus"] == 3) {
            $this->Image('../imagenes/anulado.gif', 10, 60, 190);
        }

        #$numerosALetras = new numerosALetras();
        #$moneda = rtrim($this->datosgenerales[0]["moneda"], ".");
        list($anio, $mes, $dia) = explode("-", date("Y-m-d"));
        $meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
        //$this->SetY(20);
        #$this->SetFont('Arial', 'B', 10);
        #$this->Cell(120, 10, "", 0, 0);
        #$this->Cell(20, 10, "", 0, 0);
        $this->SetFont('Arial', '', 10);
        //$this->Cell(195, 1, ""/* $this->array_factura[0]["TotalTotalFactura"] */, 0, 1, "C");
        #$this->Cell(120, 10, "", 0, 0);
        #$this->Cell(20, 10, "", 0, 0);
        //$this->Cell(195, 1, "DESPACHO {$this->array_despacho[0]["cod_despacho"]}", 0, 1, "R");
        #$this->Cell(120, 10, "", 0, 0);
        #$this->Cell(20, 10, "", 0, 0);
        #$this->Cell(55, 10, "Nro. " . $this->array_factura[0]["cod_factura"], 0, 1, "C");
        $this->SetFont('Arial', '', 10);
       // $this->Ln(1);
         $this->Cell(100, 5,"PDVAL", 0, 0, "L");
        $this->Cell(95, 5, utf8_decode("Caracas, {$dia} de {$meses[((int) $mes) - 1]} de {$anio}"), 0, 1, "R");
        $this->Cell(195, 6, "LOCALIDADES Y ALMACENES ", 0, 1, "C");
        
        $this->Ln(3);
          $this->SetFont('Arial', '', 10);
        $this->Cell(40, $height, utf8_decode("LOCALIDADES"), 0, 0, "L"); #"TBL"
        $this->Cell(40, $height, "ALMACENES", 0, 0, "L"); #"TBL"
        $this->Cell(40, $height, "UBICACIONES", 0, 1, "L"); #"TBL"
         $this->Ln(3);
    }

    function Footer() {
        #$this->SetY(-10);
        #$this->SetFont('Arial', 'I', 10);
        #$this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
        #$this->Cell(60, 10, utf8_decode("Secretaría de Finanzas"), "T", 0, "C");
        #$this->Cell(135, 10, "", 0, 0);
    }

    function dwawCell($title, $data) {
        $width = 8;
        $this->SetFont('Arial', '', 10);
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

        $comunes = new ConexionComun();
        $subtotal = $base_imponible = $exento = 0;
        $cantidaditems = $this->array_factura[0]["cantidad_items"];
        $moneda = $this->datosgenerales[0]["moneda"];
        $height = 4;

      
      
        $this->SetFont('Arial', '', 10);

        $height = 5;
        $lineas = 5;
        $ini=0;
        $localidad=0;
        $ubica=0;

        foreach ($this->array_factura as $campos) {
            if ($localidad!=$campos["id"]) {                
                $this->Cell(40, $height, utf8_decode($campos["descripcion"]), 0, 1, "L"); #"TBL"
                $ini=1;
                $localidad=$campos["id"];
            }     
             
             if ($almacen!=$campos["cod_almacen"]) {   
                $this->Cell(50, $height, "", 0, 0, "L"); #"TBL"                   
                $this->Cell(40, $height,utf8_decode($campos["descrip"]), 0, 1, "L"); #"TBL"
                $ini=1;
                $almacen=$campos["cod_almacen"];
             }
            $this->Cell(80, $height, "", 0, 0, "L"); #"TBL"
            $this->Cell(40, $height,utf8_decode($campos["nomb"]), 0, 1, "L"); #"TBL"

          
                
            }
           
        }
        //$this->Ln(5);
    

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, "$label", 0, 1, 'L', 1);
        $this->Ln(8);
    }

    function SetTitle($title, $isUTF8=false)
	{
		//Title of document
		if($isUTF8)
		$title=$this->_UTF8toUTF16($title);
		$this->title=$title;
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
	function ArrayDespacho($array) {
        $this->array_despacho = $array;
    }

}

$nro_despacho = @$_GET["codigo"];
$comunes = new ConexionComun();

$array_despacho = $comunes->ObtenerFilasBySqlSelect("select * from despacho where id='$nro_despacho'");


$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");
/*echo "
SELECT f . * , c.nombre, c.direccion, c.nit, c.cod_cliente, c.rif, c.telefonos, c.direccion, v.nombre AS nom_vendedor, v.cod_vendedor AS contador, v.cod_vendedor, fd . * , fp . * , i.cod_item, ifor.descripcion AS tipo_item_
FROM factura f
INNER JOIN clientes c ON c.id_cliente = f.id_cliente
INNER JOIN factura_detalle fd ON fd.id_factura = f.id_factura
INNER JOIN vendedor v ON v.cod_vendedor = f.cod_vendedor
INNER JOIN factura_detalle_formapago fp ON f.id_factura = fp.id_factura
INNER JOIN item i ON i.id_item = fd.id_item
INNER JOIN item_forma ifor ON ifor.cod_item_forma = i.cod_item_forma
WHERE f.cod_factura = '".$array_despacho[0][id_factura]."'
LIMIT 0 , 30;";*/
// $array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT a.* ,b.cod_almacen, b.descripcion  as descrip ,c.descripcion as ubi from localidad a,almacen b, ubicacion c where b.id_localidad=a.id  and c.id_almacen=b.cod_almacen order by a.id,c.id");
$array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT a.* ,b.cod_almacen, b.descripcion as descrip, c.descripcion as nomb FROM localidad AS a
INNER JOIN almacen AS b ON b.id_localidad=a.id
 left join ubicacion AS c ON c.id_almacen=b.cod_almacen order by a.id,b.cod_almacen");

$pdf = new PDF('P', 'mm', 'letter');
$title = 'Factura Nro.';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->ArrayDespacho($array_despacho);

$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
