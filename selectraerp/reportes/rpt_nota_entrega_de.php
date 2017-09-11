<?php

include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;

    function Header() {

        $fecha_ini = new DateTime(@$_GET["fecha"]); # PHP 5 >= 5.2.0;
        $fecha_fin = new DateTime(@$_GET["fecha2"]);

        $fecha_ini = $fecha_ini->format("d-m-Y");
        $fecha_fin = $fecha_fin->format("d-m-Y");

        $this->Image($this->datosgenerales[0]["imagen_der"] ? $this->datosgenerales[0]["imagen_der"] : $this->datosgenerales[0]["imagen_izq"], 10, 8, 50, 10);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        #$this->SetFillColor(10, 50, 100);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, "Fecha Emision: " . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("NOTAS DE ENTREGA POR ZONA ESCOLAR"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->Cell(0, 0, @$_GET["fecha"] != @$_GET["fecha2"] ? "Desde {$fecha_ini} Hasta {$fecha_fin}" : $fecha_ini, 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        /*$this->SetWidths(array(20, 95, 15, 20, 20, 20));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C"));
        $this->Row(array('Codigo', 'Descripcion', 'Unid.', 'Sub-Total', $this->datosgenerales[0]["nombre_impuesto_principal"], 'Total'), 1);
        */
        $this->SetWidths(array(40, 20, 90, 20, 20));
        $this->SetAligns(array("C", "C", "C", "C", "C"));
        $this->Row(array( 'Cliente','Cod N.E.', 'Item', 'Cantidad', 'Total C/iva'), 1);
        
		  $this->SetWidths(array(40, 20, 90, 20, 20));
        $this->SetAligns(array("C", "C", "L", "R", "R"));
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
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

    function ChapterBody() {

        $i = $total_iva = $subtotal = $total_venta = 0;
        $item_descripcion = array();
        $item_cod = array();
        $item_unidades = array();
        $item_iva = array();
        $item_subtotales = array();
        $item_totales = array();

       
        $precio =0;
        $unid =0;
        $i=0;
        $zn="";
        foreach ($this->array_factura as $id => $reg)
        {
        		if($i==0)
        		{
        			$this->SetFont('Arial', '', 10);
	            $this->SetLineWidth(0.1);
	            $this->SetWidths(array(0));
	            $this->SetAligns(array("L"));
	            $this->Row(array($reg["descripcion"]),1);
 			    	$zn=$reg["descripcion"];   	
				}
				elseif($zn!=$reg["descripcion"])
				{
					$this->Ln(300);
					$this->SetFont('Arial', '', 10);
	            $this->SetLineWidth(0.1);
	            $this->SetWidths(array(0));
	            $this->SetAligns(array("L"));
	            $this->Row(array($reg["descripcion"]),1);
 			    	$zn=$reg["descripcion"];
				}        	
        		else
        		{
        			$this->SetFont('Arial', '', 10);
	            $this->SetLineWidth(0.1);
	            $this->SetWidths(array(0));
	            $this->SetAligns(array("L"));
	            $this->Row(array($reg["descripcion"]),1);
 			    	$zn=$reg["descripcion"];   	
				}
            $this->SetFont('Arial', '', 10);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(40, 20, 90, 20, 20));
            $this->SetAligns(array("C","C", "L", "R", "R"));
            $this->Row(array($reg["nombre"],$reg["cod_nota_entrega"],$reg["descripcion1"],number_format($reg["_item_totalconiva"], 2, ',', '.'),number_format($reg["_item_cantidad"], 2, ',', '.')),1);
            $precio += $reg["PRICESELL"];
            $unid += $reg["UNITS"];
            $i++;
            #$total_venta += $item_totales[$id];
        }
        #$total_venta = number_format($total_venta, 2, ',', '.');
        
        $precios = number_format($precio, 2, ',', '.');
        $unids = number_format($unid, 2, ',', '.');

        $this->Ln(1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetWidths(array(40, 20, 90, 20, 20));
        $this->SetAligns(array("C","C", "R", "R", "R"));
        $this->Row(array($i,"", "T O T A L E S", $precios, $unids), 1);

        unset($i, $subtotal, $total_iva, $total_venta, $item_descripcion, $item_cod, $item_unidades, $item_subtotales, $item_iva, $item_totales);
        unset($this->array_factura, $this->datosgenerales);
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, $label, 0, 1, 'L', 1);
        $this->Ln(8);
    }

    function SetTitle($title, $isUTF8 = false) {
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

$inicio = @$_GET["fecha"];
$final = @$_GET["fecha2"];
//$filtro = @$_GET["filtrado_por"];
$orden = @$_GET["ordenado_por"];

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

        
$pos=POS;

 $array_factura = $comunes->ObtenerFilasBySqlSelect("SELECT
     clientes.nombre,
     nota_entrega.cod_nota_entrega,
     item.descripcion1,
     nota_entrega_detalle._item_cantidad,
     nota_entrega_detalle._item_totalconiva,
     distrito_escolar.descripcion
FROM
     nota_entrega_detalle INNER JOIN nota_entrega ON nota_entrega_detalle.id_nota_entrega = nota_entrega.id_nota_entrega
     INNER JOIN clientes ON nota_entrega.id_cliente = clientes.id_cliente
     INNER JOIN distrito_escolar ON clientes.id_distrito = distrito_escolar.id
     INNER JOIN item ON nota_entrega_detalle.id_item = item.id_item
     WHERE fechaNotaEntrega BETWEEN '{$inicio}' AND '{$final}'");

$pdf = new PDF('P', 'mm', 'A4');

$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle('Libro de Ventas.');
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
