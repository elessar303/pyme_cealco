<?php

include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $array_facturas_devueltas;

    function Header() {

        $this->Image($this->datosgenerales[0]["img_der"] ? "../../includes/imagenes/" . $this->datosgenerales[0]["img_der"] : "../../includes/imagenes/" . $this->datosgenerales[0]["img_izq"], 10, 8, 50, 10);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        #$width = 5;
        #$this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        #$this->SetFillColor(10, 50, 100);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode("Fecha EmisiÃƒÂ³n: ") . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetX(14);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("LIBRO DE VENTAS - " . strtoupper(mesaletras(date_format(date_create($_GET["fecha"] . "-01"), $format = "m"))) . " " . date_format(date_create($_GET["fecha"] . "-01"), $format = "Y")), 0, 0, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 6);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(10, 15, 50, 16, 18, 12, 15, 18, 18, 18, 18, 18, 18, 18, 18, 18));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
        #$this->SetFillColor(232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232, 232);
        $this->Row(array('Nro.', 'Fecha', utf8_decode('Nombre/RazÃƒÂ³n Social del Cliente'), 'CI/RIF', 'Serial Imp.', 'Rep. Z', 'Factura',
            /* 'Nro. Control',  'Fact. Anulada', */ utf8_decode('Nota DÃƒÂ©bito'), utf8_decode('Nota CrÃƒÂ©dito'), utf8_decode('Total Operac.'), /* 'Tipo Trans.',
              'Nro. Fac. Afectada', */ 'Ventas NG', 'Base Imponible', '% ' . $this->datosgenerales[0]["nombre_impuesto_principal"], $this->datosgenerales[0]["nombre_impuesto_principal"], $this->datosgenerales[0]["nombre_impuesto_principal"] . ' Retenido'), 1);
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

    function ChapterBody() {

        $totalDebito = $totalCredito = $totalVentasConIva = $totalVentasNoGravadas = $totalBaseImponible = $totalIva = $totalIvaRet = $totalExento = $totalGravado = 0;
        $i = 0;

        $comunes = new ConexionComun();
        $tipo_facturacion = $this->datosgenerales[0]["tipo_facturacion"];

        while ($this->array_factura[$i]) {

            /* $porc = $this->array_factura[$i]["ivaTotalFactura"] * 100 / $this->array_factura[$i]["montoItemsFactura"];
              if ($porc >= 11.9 && $porc < 12.5)
              $porc = 12;
             */

            $array_factura_detalles = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_detalle WHERE id_factura = {$this->array_factura[$i]["id_factura"]};");

            $j = $exento = $gravado = 0;
            $flag = FALSE;
            while ($array_factura_detalles[$j]) {
                if ($array_factura_detalles[$j]["_item_piva"] == 0) {
                    $exento+=$array_factura_detalles[$j]["_item_totalsiniva"];
                } else {
                    $gravado+=$array_factura_detalles[$j]["_item_totalsiniva"] * $array_factura_detalles[$j]["_item_piva"] / 100;
                    $porc = $array_factura_detalles[$j]["_item_piva"];
                    $flag = TRUE;
                }
                $j++;
            }

            #$fecha = DateTime::createFromFormat($format = "d-m-Y", $time = $this->array_factura[$i]["fechaFactura"]);# PHP 5 >= 5.3.0
            $fecha = new DateTime($this->array_factura[$i]["fechaFactura"]); # PHP 5 >= 5.2.0
            #$base_imponible = $this->array_factura[$i]["cod_estatus"] == 3 ? (-1) * $this->array_factura[$i]["totalizar_base_imponible"] : $bi $this->array_factura[$i]["totalizar_base_imponible"];
            $iva_factura = $this->array_factura[$i]["cod_estatus"] == 3 ? (-1) * $this->array_factura[$i]["ivaTotalFactura"] : $this->array_factura[$i]["ivaTotalFactura"];
            $retencion = $this->array_factura[$i]["cod_estatus"] == 3 ? (-1) * $this->array_factura[$i]["totalizar_total_retencion"] : $this->array_factura[$i]["totalizar_total_retencion"];
            $total_factura = $this->array_factura[$i]["cod_estatus"] == 3 ? (-1) * $this->array_factura[$i]["totalizar_total_general"] : $this->array_factura[$i]["totalizar_total_general"];
            #$tipo_facturacion = $this->datosgenerales[0]["tipo_facturacion"];
            $bi = $this->array_factura[$i]["totalizar_total_general"] - $this->array_factura[$i]["totalizar_monto_iva"] - $exento;
            $base_imponible = $this->array_factura[$i]["cod_estatus"] == 3 ? (-1) * $bi : $bi;
            $exento = $this->array_factura[$i]["cod_estatus"] == 3 ? (-1) * $exento : $exento;
            $gravado = $this->array_factura[$i]["cod_estatus"] == 3 ? (-1) * $gravado : $gravado;

            $width = 5;
            $this->SetFont('Arial', '', 6);
            $this->SetLineWidth(0.1);
            $this->SetWidths(array(10, 15, 50, 16, 18, 12, 15, 18, 18, 18, 18, 18, 18, 18, 18));
            $this->SetAligns(array("C", "C", "L", "C", "C", "C", "C", "C", "C", "R", "R", "R", "R", "R", "R"));

            $this->Row(array(($i + 1),
                $fecha->format($format = "d-m-Y"),
                $this->array_factura[$i]["nombre"],
                $this->array_factura[$i]["rif"],
                $tipo_facturacion == 1 ? $this->array_factura[$i]["impresora_serial"] : "-",
                $tipo_facturacion == 1 ? $this->array_factura[$i]["nroz"] : "-",
                $tipo_facturacion == 1 ? $this->array_factura[$i]["cod_factura_fiscal"] : $this->array_factura[$i]["cod_factura"]/* $this->array_factura[$i]["cod_factura_fiscal"] */,
                "-",
                //$this->array_factura[$i]["cod_estatus"] == 3 ? ($this->array_facturas_devueltas[$this->getFacturaDevuelta($this->array_factura[$i]["cod_factura"])]["cod_devolucion_fiscal"]) : "-",
                $this->array_factura[$i]["cod_estatus"] == 3 ? ($tipo_facturacion == 1 ? $this->array_facturas_devueltas[$this->getFacturaDevuelta($this->array_factura[$i]["cod_factura"])]["cod_devolucion_fiscal"] : $this->array_facturas_devueltas[$this->getFacturaDevuelta($this->array_factura[$i]["cod_factura"])]["cod_devolucion"]) : "-",
                number_format($total_factura, 2, ',', '.'),
                number_format($exento, 2, ',', '.'),
                number_format($base_imponible, 2, ',', '.'),
                number_format($flag ? $porc : 0, 2, ',', '.'),
                number_format($iva_factura, 2, ',', '.'),
                number_format($retencion, 2, ',', '.')), 1); #$this->array_factura[$i]["montoItemsFactura"] + $this->array_factura[$i]["ivaTotalFactura"]
            $totalDebito+=0;
            $totalCredito+=0;
            $totalVentasConIva+= $total_factura;
            $totalVentasNoGravadas+=0;
            $totalBaseImponible+=$base_imponible;
            $totalIva+=$iva_factura;
            $totalIvaRet+=$retencion;
            $totalExento+=$exento;
            $totalGravado+=$gravado;
            $i++;
        }
        /* #$totalDebito = number_format($totalDebito, 2, ',', '.');
          #$totalCredito = number_format($totalCredito, 2, ',', '.');
          $totalVentasConIva = number_format($totalVentasConIva, 2, ',', '.');
          $totalVentasNoGravadas = number_format($totalExento, 2, ',', '.');
          $totalBaseImponible = number_format($totalBaseImponible, 2, ',', '.');
          $totalGravado = number_format($totalGravado, 2, ',', '.');
          $totalIva = number_format($totalIva, 2, ',', '.');
          $totalIvaRet = number_format($totalIvaRet, 2, ',', '.'); */

        $this->Ln(1);
        $width = 5;
        $this->SetFont('Arial', 'B', 6);
        $this->SetWidths(array(172, /* 18, */18, 18, 18, 18, 18, 18));
        $this->SetAligns(array("C", /* "R", "R", */ "R", "R", "R", "R", "R", "R"));
        $this->Row(array("T O T A L E S", number_format($totalVentasConIva, 2, ',', '.'), number_format($totalExento, 2, ',', '.'), number_format($totalBaseImponible, 2, ',', '.'), "N/A", number_format($totalIva, 2, ',', '.'), number_format($totalIvaRet, 2, ',', '.')), 1);

        $this->AddPage();
        $this->totalizar($totalExento, $totalBaseImponible, $totalIva, $totalBaseM, $totalivaM, $totalBase8, $totaliva8);

        unset($totalDebito, $totalCredito, $totalVentasConIva, $totalVentasNoGravadas, $totalBaseImponible, $totalIva, $totalIvaRet, $totalExento, $totalGravado, $i);
        unset($bi, $porc, $flag, $base_imponible, $iva_factura, $retencion, $total_factura, $tipo_facturacion, $exento, $gravado, $j);
        unset($this->array_factura, $this->array_facturas_devueltas, $this->datosgenerales, $array_factura_detalles);
    }

    function totalizar($totalcomprassiniva, $totalBase12, $totaliva12, $totalBaseM = "0.00", $totalivaM = "0.00", $totalBase8 = "0.00", $totaliva8 = "0.00") {
        $this->Ln(10);
        $this->SetWidths(array(100, 103, 32, 32));
        $this->SetAligns(array("C", "L", "R", "R"));
        $this->SetCeldas(array(0, 1, 1, 1));
        $this->SetFont('Arial', 'B', 6);
        $this->Row(array("", "DEBITOS FISCALES", "BASE IMPONIBLE", "DEBITO FISCAL"));
        $this->SetFont('Arial', '', 6);
        $this->Row(array("", "Total Ventas no gravadas y/o sin derecho a credito fiscal", number_format($totalcomprassiniva, 2, ',', '.'), "0,00"));
        $this->Row(array("", "Total de las Importaciones gravadas por alicuota general", "0,00", "0,00"));
        $this->Row(array("", "Total de las Importaciones gravadas por alicuota general mas adicional", "0,00", "0,00"));
        $this->Row(array("", "Total de las Importaciones gravadas por alicuota reducida", "0,00", "0,00"));
        $this->Row(array("", "Total Ventas internas gravadas solo por alicuita general", number_format($totalBase12, 2, ',', '.'), number_format($totaliva12, 2, ',', '.')));
        $this->Row(array("", "Total Ventas internas gravadas solo por alicuita general mas adicional", number_format($totalBaseM, 2, ',', '.'), number_format($totalivaM, 2, ',', '.')));
        $this->Row(array("", "Total Ventas internas gravadas solo por alicuita reducida.", number_format($totalBase8, 2, ',', '.'), number_format($totaliva8, 2, ',', '.')));
        $this->SetFont('Arial', 'B', 6);
        $this->Row(array("", "Total Ventas y Debito Fiscal p/Efectos de determinacion", number_format(($totalcomprassiniva + $totalBase12 + $totalBaseM + $totalBase8), 2, ',', '.'), number_format(($totaliva12 + $totalivaM + $totaliva8), 2, ',', '.'), ""));
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, $label, 0, 1, 'L', 1);
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

    function ArrayFacturasDevueltas($array) {
        $this->array_facturas_devueltas = $array;
    }

    function getFacturaDevuelta($cod_factura) {
        $i = 0;
        while ($factura_devuelta = $this->array_facturas_devueltas[$i]) {
            if ($this->array_facturas_devueltas[$i]["cod_factura"] == $cod_factura) {
                return $i;
            }
            $i++;
        }
    }

}

$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");

/*
  $fecha = @$_GET["fecha"] . "-01";
  $array_factura = $comunes->ObtenerFilasBySqlSelect(
  "SELECT f.*, c.rif, c.nombre, c.direccion, c.nit, c.cod_cliente, c.telefonos, c.direccion
  FROM factura f
  INNER JOIN clientes c ON c.id_cliente = f.id_cliente
  WHERE month(f.fechaFactura) = month('{$fecha}') AND year(f.fechaFactura) = year('{$fecha}')
  ORDER BY f.id_factura;"); */

$desde = @$_GET["fecha"];
$hasta = @$_GET["fecha2"];
#echo $desde . "->" . $hasta;exit;
$array_factura = $comunes->ObtenerFilasBySqlSelect(
        "SELECT f.*, c.rif, c.nombre, c.direccion, c.nit, c.cod_cliente, c.telefonos, c.direccion
         FROM factura f
         INNER JOIN clientes c ON c.id_cliente = f.id_cliente
         WHERE f.fechaFactura BETWEEN '{$desde}' AND '{$hasta}'
         ORDER BY f.id_factura");

$array_facturas_devueltas = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_devolucion");

#$mes = mesaletras(substr($fecha, 5, 2));

$pdf = new PDF('L', 'mm', 'A4');
$title = 'Libro de Ventas.';

$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->ArrayFacturasDevueltas($array_facturas_devueltas);

$pdf->SetTitle($title);
$pdf->AliasNbPages();
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
