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

    function Header() {

        if ($this->array_factura[0]["cod_estatus"] == 3) {
            $this->Image('../imagenes/anulado.gif', 10, 60, 190);
        }

        #$numerosALetras = new numerosALetras();
        #$moneda = rtrim($this->datosgenerales[0]["moneda"], ".");
        list($anio, $mes, $dia) = explode("-", $this->array_factura[0]["fechaFactura"]);
        $meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
        $this->SetY(20);
        #$this->SetFont('Arial', 'B', 10);
        #$this->Cell(120, 10, "", 0, 0);
        #$this->Cell(20, 10, "", 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell(195, 1, ""/* $this->array_factura[0]["TotalTotalFactura"] */, 0, 1, "C");
        #$this->Cell(120, 10, "", 0, 0);
        #$this->Cell(20, 10, "", 0, 0);
        $this->Cell(195, 1, "FACTURA {$this->array_factura[0]["cod_factura"]}", 0, 1, "R");
        #$this->Cell(120, 10, "", 0, 0);
        #$this->Cell(20, 10, "", 0, 0);
        #$this->Cell(55, 10, "Nro. " . $this->array_factura[0]["cod_factura"], 0, 1, "C");
        $this->SetFont('Arial', '', 10);
        $this->Ln(1);
        $this->Cell(195, 5, utf8_decode("{$this->datosgenerales[0]["ciudad"]}, {$dia} de {$meses[((int) $mes) - 1]} de {$anio}"), 0, 1, "R");
        /* $this->Cell(10, 5, $dia, "B", 0, "C");
          $this->Cell(10, 5, " de ", 0, 0, "C");
          $this->Cell(35, 5, $meses[((int) $mes) - 1], "B", 0, "C");
          $this->Cell(10, 5, " de ", 0, 0, "C");
          $this->Cell(10, 5, $anio, "B", 1, "C"); */
       // $this->Ln(1);
        $this->SetFont('Arial', '', 10);
        $this->Cell(50, 6, "NOMBRE O RAZON SOCIAL: ", 0, 0, "L");
        $this->SetFont('Arial', '', 10);
        $this->Cell(145, 6, utf8_decode($this->array_factura[0]["nombre"]), 0, 1, "L");
        $this->SetFont('Arial', '', 10);
        $this->Cell(42, 7, utf8_decode($this->datosgenerales[0]["id_fiscal"] . ", C.I. Ó PASAPORTE: "), 0, 0, "L");
        $this->SetFont('Arial', '', 10);
        $this->Cell(153, 7, $this->array_factura[0]["rif"], 0, 1, "L");
        $this->SetFont('Arial', '', 10);
        $this->Cell(35, 7, "DOMICILIO FISCAL: ", 0, 0, "L");
        $this->SetFont('Arial', '', 10);
        $this->Cell(160, 7, $this->array_factura[0]["direccion"], 0, 1, "L");
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 7, "TELEFONO (S): ", 0, 0, "L");
        $this->SetFont('Arial', '', 10);
        $this->Cell(165, 7, $this->array_factura[0]["telefonos"], 0, 1, "L");
        
        $this->Cell(40, 7, "VENDEDOR: ".$this->array_factura[0]["contador"], 0, 0, "L");
      
        $this->Cell(190, 7, $this->array_factura[0]["nom_vendedor"], 0, 1, "L");
        
        #$this->Cell(40, 7, "LA CANTIDAD DE {$moneda}: ", 0, 0, "L");
        #$this->Cell(155, 7, strtoupper($numerosALetras->numerosALetras($this->array_factura[0]["TotalTotalFactura"])), "B", 1, "L");
        #$this->Cell(0, 7, "POR CONCEPTO DE:", 0, 1, "L");
        $this->Ln(1);
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
        $this->Cell(130, $height, utf8_decode("DESCRIPCIÓN"), 0, 0, "L"); #"TBL"
        $this->Cell(15, $height, "CANT.", 0, 0, "R"); #"TBL"
        $this->Cell(25, $height, "PRECIO", 0, 0, "R"); #"TBL"
        $this->Cell(25, $height, "MONTO", 0, 1, "R"); #"TRBL"
        $this->SetFont('Arial', '', 10);

        $height = 5;
        $lineas = 5;

        for ($i = 0; $i < $lineas; $i++) {
            if ($i < $cantidaditems) {
                $this->Cell(120, $height, utf8_decode($this->array_factura[$i]["_item_descripcion"]), 0, 0, "L"); #"TBL"
                $this->Cell(10, $height, $this->array_factura[$i]["_item_piva"] == 0 && $this->array_factura[$i]["_item_descripcion"] != "" ? "( E )" : ($this->array_factura[$i]["_item_descripcion"] != "" ? "( G )" : ""), 0, 0, "C"); #"TB"
                $this->Cell(15, $height, $this->array_factura[$i]["_item_cantidad"], 0, 0, "R"); #"TBL"
                $this->Cell(25, $height, number_format($this->array_factura[$i]["_item_preciosiniva"], 2, ',', '.'), 0, 0, "R"); #1
                #$this->Cell(20, $height, number_format($this->array_factura[$i]["_item_descuento"], 2, ',', '.'), $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "R");
                #$this->Cell(15, $height, number_format($this->array_factura[$i]["_item_montodescuento"], 2, ',', '.'), $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "R");
                $this->Cell(25, $height, number_format($this->array_factura[$i]["_item_totalsiniva"], 2, ',', '.'), 0, 1, "R"); #1
                #$this->Cell(11, $height, number_format($this->array_factura[$i]["_item_piva"], 2, ',', '.'), $i < ($cantidaditems - 1) ? "LR" : "LRB", 0, "R");
                #$this->Cell(25, $height, number_format($this->array_factura[$i]["_item_cantidad"] * $this->array_factura[$i]["_item_preciosiniva"], 2, ',', '.'), 1, 1, "R");
                $subtotal += $this->array_factura[$i]["_item_cantidad"] * $this->array_factura[$i]["_item_preciosiniva"];
                if ($this->array_factura[$i]["_item_piva"] == 0) {
                    $exento+=$this->array_factura[$i]["_item_totalsiniva"];
                } else {
                    $base_imponible+=$this->array_factura[$i]["_item_totalsiniva"];
                }
            }
            /* else {
              $this->Cell(120, $height, "", 1, 0);
              $this->Cell(10, $height, "", 1, 0);
              $this->Cell(15, $height, "", 1, 0);
              $this->Cell(25, $height, "", 1, 0);
              $this->Cell(25, $height, "", 1, 1);
              } */
        }
        $height = 4;
        $this->SetY(90);
        $this->Ln(5);
        $this->SetFont('Arial', '', 10);
        $this->Cell(165, $height, "EXENTO {$moneda}:", 0, 0, "R");
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, $height, number_format($exento, 2, ',', '.'), 0, 1, "R");
       # $this->Ln(1);
        $this->SetFont('Arial', '', 10);
        $this->Cell(165, $height, "B.I. G. 12,00 % {$moneda}:", 0, 0, "R");
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, $height, number_format($base_imponible, 2, ',', '.'), 0, 1, "R");
       # $this->Ln(1);
        $this->SetFont('Arial', '', 10);
        $this->Cell(165, $height, "I.V.A. G. 12,00 % {$moneda}:", 0, 0, "R");
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, $height, number_format($this->array_factura[0]["ivaTotalFactura"], 2, ',', '.'), 0, 1, "R");
       # $this->Ln(1);
        $this->SetFont('Arial', '', 10);
        $this->Cell(165, $height, "TOTAL {$moneda}:", 0, 0, "R");
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, $height, number_format($this->array_factura[0]["totalizar_total_general"], 2, ',', '.'), 0, 1, "R");
      #  $this->Ln(1);
        $this->SetFont('Arial', '', 10);
        $this->Cell(195, $height, "F O R M A  D E  P A G O", 0, 1, "R");
       # $this->Ln(1);
        if ($this->array_factura[0]["totalizar_monto_efectivo"] != 0) {
            $this->Cell(165, $height, "EFECTIVO {$moneda}:", 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, $height, number_format($this->array_factura[0]["totalizar_monto_efectivo"], 2, ',', '.'), 0, 1, "R");
            $this->SetFont('Arial', '', 10);
        }
        if ($this->array_factura[0]["totalizar_monto_tarjeta"] != 0) {
            $this->Cell(165, $height, utf8_decode("TARJETA DE DÉBITO {$moneda}:"), 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, $height, number_format($this->array_factura[0]["totalizar_monto_tarjeta"], 2, ',', '.'), 0, 1, "R");
            $this->SetFont('Arial', '', 10);
        }
        if ($this->array_factura[0]["totalizar_monto_cheque"] != 0) {
            $banco = $comunes->ObtenerFilasBySqlSelect("SELECT descripcion FROM banco AS b INNER JOIN factura_detalle_formapago fp ON b.cod_banco = fp.totalizar_nombre_banco WHERE b.cod_banco = {$this->array_factura[0]["totalizar_nombre_banco"]};");
            $this->Cell(35, $height, "CHEQUE Nro. ", 0, 0, "L");
            $this->SetFont('Arial', '', 10);
            $this->Cell(50, $height, $this->array_factura[0]["totalizar_nro_cheque"], 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(20, $height, "BANCO:", 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(40, $height, utf8_decode(strtoupper($banco[0]["descripcion"])), 0, 0, "L");
            $this->SetFont('Arial', '', 10);
            $this->Cell(20, $height, "MONTO {$moneda}:", 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, $height, number_format($this->array_factura[0]["totalizar_monto_cheque"], 2, ',', '.'), 0, 1, "R");
            $this->SetFont('Arial', '', 10);
        }
        if ($this->array_factura[0]["totalizar_monto_deposito"] != 0) {
            $banco = $comunes->ObtenerFilasBySqlSelect("SELECT descripcion FROM banco AS b INNER JOIN factura_detalle_formapago fp ON b.cod_banco = fp.totalizar_banco_deposito WHERE b.cod_banco = {$this->array_factura[0]["totalizar_banco_deposito"]};");
            $this->Cell(35, $height, "DEPOSITO Nro.", 0, 0, "L");
            $this->SetFont('Arial', '', 10);
            $this->Cell(50, $height, $this->array_factura[0]["totalizar_nro_deposito"], 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(20, $height, "BANCO:", 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(40, $height, utf8_decode(strtoupper($banco[0]["descripcion"])), 0, 0, "L");
            $this->SetFont('Arial', '', 10);
            $this->Cell(20, $height, "MONTO {$moneda}:", 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, $height, $this->array_factura[0]["totalizar_monto_deposito"], 0, 1, "R");
            $this->SetFont('Arial', '', 10);
        }
        if ($this->array_factura[0]["totalizar_monto_otrodocumento"] != 0) {
            $banco = $comunes->ObtenerFilasBySqlSelect("SELECT descripcion FROM banco AS b INNER JOIN factura_detalle_formapago fp ON b.cod_banco = fp.totalizar_banco_otrodocumento WHERE b.cod_banco = {$this->array_factura[0]["totalizar_banco_otrodocumento"]};");
            $this->Cell(35, $height, "TRANSFERENCIA Nro.", 0, 0, "L");
            $this->SetFont('Arial', '', 10);
            $this->Cell(50, $height, $this->array_factura[0]["totalizar_nro_otrodocumento"], 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(20, $height, "BANCO:", 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(40, $height, utf8_decode(strtoupper($banco[0]["descripcion"])), 0, 0, "L");
            $this->SetFont('Arial', '', 10);
            $this->Cell(20, $height, "MONTO {$moneda}:", 0, 0, "R");
            $this->SetFont('Arial', '', 10);
            $this->Cell(30, $height, $this->array_factura[0]["totalizar_monto_otrodocumento"], 0, 1, "R");
        }
        $this->Ln(5);
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

$nro_factura = @$_GET["codigo"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

$array_factura = $comunes->ObtenerFilasBySqlSelect("
SELECT f . * , c.nombre, c.direccion, c.nit, c.cod_cliente, c.rif, c.telefonos, c.direccion, v.nombre AS nom_vendedor, v.cod_vendedor AS contador, v.cod_vendedor, fd . * , fp . * , i.cod_item, ifor.descripcion AS tipo_item_
FROM factura f
INNER JOIN clientes c ON c.id_cliente = f.id_cliente
INNER JOIN factura_detalle fd ON fd.id_factura = f.id_factura
INNER JOIN vendedor v ON v.cod_vendedor = f.cod_vendedor
INNER JOIN factura_detalle_formapago fp ON f.id_factura = fp.id_factura
INNER JOIN item i ON i.id_item = fd.id_item
INNER JOIN item_forma ifor ON ifor.cod_item_forma = i.cod_item_forma
WHERE f.cod_factura = {$nro_factura}
LIMIT 0 , 30;");

$pdf = new PDF('P', 'mm', 'mcarta');
$title = 'Factura Nro.';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
