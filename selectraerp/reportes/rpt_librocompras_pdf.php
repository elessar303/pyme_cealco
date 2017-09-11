<?php

include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $fecha;

    function Header() {

        $this->SetFont('Arial', 'B', 8);
        //$this->SetFillColor(10,50,100);
        $this->Cell(80, 5, "LIBRO DE COMPRAS", 0, 0, 'L');
        $this->Ln();
        $this->SetFont('Arial', 'B', 7);
        $this->SetWidths(array(90, 10, 100, 10, 40));
        $this->SetAligns(array("L", "L", "L", "L", "L"));
        $this->SetCeldas(array(1, 0, 1, 0, 1));
        $this->Row(array("1. NOMBRE O RAZON SOCIAL DEL AGENTE DE RETENCION:", "", "2. REGISTRO DE INFORMACION FISCAL DEL AGENTE DE RETENCION:", "", "4. PERIODO FISCAL:"));
        $this->SetWidths(array(90, 10, 100, 10, 20, 20));
        $this->SetCeldas(array(1, 0, 1, 0, 1, 1));
        $this->SetFont('Arial', 'I', 7);
        $fecha = @$_GET["fecha"];
        //echo $fecha;
       
        
        $this->Row(array($this->datosgenerales[0]["nombre_empresa"], "", $this->datosgenerales[0]["rif"], "", utf8_decode("AÑO:") . substr($fecha, 0, 4), "MES: " . substr($fecha, 6, 2)));

        /* $this->Cell(120,0, "EMPRESA: ".$this->datosgenerales[0]["nombre_empresa"],0,0,'R');
          $this->Cell(80,0, "RIF: ".$this->datosgenerales[0]["rif"],0,0,'C'); */
        $this->Ln(2);
        $this->SetFont('Arial', 'B', 7);
        $this->SetCeldas(array(0, 0, 0, 0, 0, 0));
        $this->Cell(200, 5, "3. DIRECCION DEL AGENTE DE RETENCION:", 1, 1, 'L');
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(200, 5, $this->datosgenerales[0]["direccion"], 1, 1, 'J');
        $this->Ln(2);
        //.$mes." ".
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);

        $this->Cell(0, 10, 'P�gina ' . $this->PageNo(), 0, 0, 'C');
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

        $this->SetWidths(array(10, 15, 17, 50, 15, 14, 15, 14, 14, 12, 14, 18, 18, 14, 10, 12, 18));
        $this->SetAligns(array("C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C", "C"));
        $this->SetFillColor(10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10);

        $this->SetLeftMargin(18);
        $width = 5;
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(221, 5, "");
        $this->Cell(36, 5, "Compras Internas", 1, 1, "C");
        $this->SetX(8);
        $this->Row(array('Nro.', 'Fecha Factura', 'CI/RIF/Pas', 'Nombre o Razon Social', 'Tipo Proveedor', 'Nro. Factura', 'Nro. Control Factura', utf8_decode('Nota Debito'), utf8_decode('Nota Crédito'), 'Tipo Trans.', 'Nro. Fac. Afectada', 'Total Compra con ' . $this->datosgenerales[0]["nombre_impuesto_principal"], utf8_decode('Compras S/D Crédito Fiscal'), 'Base Imponible', '%', $this->datosgenerales[0]["nombre_impuesto_principal"], $this->datosgenerales[0]["nombre_impuesto_principal"] . ' Retenido por Comprador'), 1);
        $this->SetAligns(array("C", "C", "C", "L", "C", "R", "R", "C", "C", "C", "C", "R", "R", "R", "C", "R", "R"));

        $totalcompras = $totalcomprassiniva = $totalBaseImponible = $totalIva = $totalIvaRet = 0;
        $i = 0;
        while ($this->array_facturas[$i]) {
            $this->SetLeftMargin(18);
            $width = 5;
            $this->SetX(8);
            $this->SetFont('Arial', '', 6);

            if ($this->array_facturas[$i]["tipo"] == 'NC') {
                $factura = "";
                $nota_credito = $this->array_facturas[$i]["cod_factura"];
                $comunes = new ConexionComun();
                $array_sql = $comunes->ObtenerFilasBySqlSelect("SELECT cod_factura FROM cxp_factura WHERE id_factura=" . $this->array_facturas[$i]["factura_afectada"]);
                $afectada = $array_sql[0]["cod_factura"];
            } else {
                $factura = $this->array_facturas[$i]["cod_factura"];
                $nota_credito = "";
                $afectada = "";
            }

            if ($this->array_facturas[$i]["porcentaje_iva_mayor"] == 0) {
               $comprassiniva=$this->array_facturas[$i]["monto_total_sin_iva"];
               $comprasconiva=0;
            } else {
               $comprasconiva=$this->array_facturas[$i]["monto_total_con_iva"];
               $comprassiniva=0;
            }
            $this->Row(
                    array($i + 1,
                        $this->array_facturas[$i]["fecha_factura"],
                        $this->array_facturas[$i]["rif"],
                        $this->array_facturas[$i]["descripcion"],
                        $this->array_facturas[$i]["rif"][0] == "V" ? "N" : "Juridico",
                        $factura,
                        $this->array_facturas[$i]["cod_cont_factura"], "", $nota_credito, "01-reg", $afectada,
                        number_format($comprasconiva, 2, ',', '.'),
                        number_format($comprassiniva, 2, ',', '.'),
                        number_format($this->array_facturas[$i]["monto_base"], 2, ',', '.'),
                        $this->array_facturas[$i]["porcentaje_iva_mayor"],
                        number_format($this->array_facturas[$i]["monto_iva"], 2, ',', '.'),
                        number_format($this->array_facturas[$i]["monto_retenido"], 2, ',', '.')
            ));

            $totalcompras+=$this->array_facturas[$i]["monto_total_con_iva"];
            if ($this->array_facturas[$i]["porcentaje_iva_mayor"] == 0) {
               $totalcomprassiniva+=$this->array_facturas[$i]["monto_total_sin_iva"];
            }   
            $totalBaseImponible+=$this->array_facturas[$i]["monto_base"];
            $totalIva+=$this->array_facturas[$i]["monto_iva"];
            $totalIvaRet+=$this->array_facturas[$i]["monto_retenido"];
            //$i++;
            $iva =$this->array_facturas[$i]["porcentaje_iva_mayor"];
            if ($this->array_facturas[$i]["porcentaje_iva_mayor"] == 12) {
                $totalBase12+=$this->array_facturas[$i]["monto_base"];
                $totaliva12+=$this->array_facturas[$i]["monto_iva"];
            } else if ($this->array_facturas[$i]["porcentaje_iva_mayor"] > 12) {
                $totalBaseM+=$this->array_facturas[$i]["monto_base"];
                $totalivaM+=$this->array_facturas[$i]["monto_iva"];
            } else if ($this->array_facturas[$i]["porcentaje_iva_mayor"] == 8) {
                $totalBase8+=$this->array_facturas[$i]["monto_base"];
                $totaliva8+=$this->array_facturas[$i]["monto_iva"];
            }
            $i++;
        }

        $this->SetX(8);
        //$this->SetCeldas(array());
        $this->SetCeldas(array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 1, 1));
        $this->Row(array('', '', '', '', '', '', '', '', '', '', '', number_format($totalcompras, 2, ',', '.'), number_format($totalcomprassiniva, 2, ',', '.'), number_format($totalBaseImponible, 2, ',', '.'), '', number_format($totalIva, 2, ',', '.'), number_format($totalIvaRet, 2, ',', '.')));
        $this->Ln(1);

        $this->totalizar($totalcomprassiniva, $totalBase12, $totaliva12, $totalBaseM, $totalivaM, $totalBase8, $totaliva8);
        if ($i > 17) {
            $this->Ln(250);
        }
        #:::::::::::::::::::::::::::::::::::::::::::AQUI VA TOTAL::::::::::::::::::::::::::::::::::::::::::::::::::::::
    }

    function totalizar($totalcomprassiniva, $totalBase12, $totaliva12, $totalBaseM, $totalivaM, $totalBase8, $totaliva8) {

        $this->Ln(10);
        $this->SetWidths(array(100, 103, 10, 22, 10, 22));
        $this->SetAligns(array("C", "L", "C", "R", "C", "R"));
        $this->SetCeldas(array(0, 1, 0, 1, 0, 1, 0));
        $this->Row(array("", "CREDITOS FISCALES", "", "BASE IMPONIBLE", "", "CREDITO FISCAL"));
        $this->Row(array("", "Total Compras no gravadas y/o sin derecho a credito fiscal", "", number_format($totalcomprassiniva, 2, ',', '.'), "", ""));
        $this->Row(array("", "Total de las Importaciones gravadas por alicuota general", "", 0, "", ""));
        $this->Row(array("", "Total de las Importaciones gravadas por alicuota general mas adicional", "", 0, "", ""));
        $this->Row(array("", "Total de las Importaciones gravadas por alicuota reducida", "", 0, "", ""));
        $this->Row(array("", "Total Compras internas gravadas solo por alicuota general", "", number_format($totalBase12, 2, ',', '.'), "", number_format($totaliva12, 2, ',', '.')));
        $this->Row(array("", "Total Compras internas gravadas solo por alicuota general mas adicional", "", number_format($totalBaseM, 2, ',', '.'), "", number_format($totalivaM, 2, ',', '.')));
        $this->Row(array("", "Total compras internas gravadas solo por alicuota reducida.", "", number_format($totalBase8, 2, ',', '.'), "", number_format($totaliva8, 2, ',', '.')));
        $this->Row(array("", "", "", number_format(($totalcomprassiniva + $totalBase12 + $totalBaseM + $totalBase8), 2, ',', '.'), "", number_format(($totaliva12 + $totalivaM + $totaliva8), 2, ',', '.'), ""));
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
        $this->array_facturas = $array;
    }

    function Fecha($fecha) {
        $this->fecha = $fechaz;
    }

}

$fecha = @$_GET["fecha"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros, parametros_generales");
$fechaz = $fecha . "-01";

$sql = "select F.fecha_factura,P.rif,P.descripcion,F.cod_factura,
            F.factura_afectada,F.cod_cont_factura,F.monto_total_con_iva,F.monto_total_sin_iva,
            F.monto_exento,F.monto_base,F.porcentaje_iva_mayor,F.monto_iva,
            F.monto_retenido, F.tipo
        from cxp_factura as F, proveedores as P, cxp_edocuenta as C
        where F.id_cxp_edocta=C.cod_edocuenta and C.id_proveedor=P.id_proveedor
            and month(F.fecha_recepcion) = month('" . $fechaz . "')
                and year(F.fecha_recepcion) = year('" . $fechaz . "') and libro_compras=1";
$array_factura = $comunes->ObtenerFilasBySqlSelect($sql);

$mes = mesaletras(substr($fecha, 5, 2));

$pdf = new PDF('L', 'mm', 'A4');
$title = 'Libro de Compras.';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);
$pdf->Fecha($fecha);
$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>