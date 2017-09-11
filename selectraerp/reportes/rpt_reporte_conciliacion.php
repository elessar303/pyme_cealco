<?php

include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
session_start();

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $cod_cheque;

    function Header() {

        #$this->SetFillColor(239,239,239);
        $this->SetFont('Arial', '', 8);
        $this->Image($this->datosgenerales[0]["imagen_der"] ? $this->datosgenerales[0]["imagen_der"] : $this->datosgenerales[0]["imagen_izq"], 10, 8, 50, 10);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . utf8_decode(" - Teléfonos: ") . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
        $this->Ln(3);

        $comunes = new ConexionComun();
        $sql = "select * from `vw_lista_conciliacion` where cod_conciliacion = " . $_GET["cod_conciliacion"];
        $registros = $comunes->ObtenerFilasBySqlSelect($sql);

        $this->Ln(4);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 0, "BANCO: " . $registros[0]["banco"], 0, 0, 'L');
        $this->Ln(4);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 0, "CUENTA: " . $registros[0]["nro_cuenta"], 0, 0, 'L');
        $this->Ln(4);
        $this->SetFont('Arial', '', 8);
        list($anio, $mes, $dia) = explode("-", $registros[0]["fecha_inicial"]);
        $fecha1 = $dia . "/" . $mes . "/" . $anio;
        list($anio, $mes, $dia) = explode("-", $registros[0]["fecha_final"]);
        $fecha2 = $dia . "/" . $mes . "/" . $anio;
        $this->Cell(0, 0, "FECHA INICIO: " . $fecha1 . " / FECHA FIN: " . $fecha2, 0, 0, 'L');
        $this->Ln(4);
        $this->Cell(0, 0, "SALDO INICIAL: " . number_format($registros[0]["saldo_inicial"], 2, ",", ".") . "      SALDO FINAL: " . number_format($registros[0]["saldo_final"], 2, ",", "."), 0, 0, 'L');
        $this->Ln(4);
        $this->Cell(0, 0, "SALDO LIBRO: " . number_format($registros[0]["saldo_libros"], 2, ",", "."), 0, 0, 'L');
        $this->Ln(4);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 0, utf8_decode('REPORTE DE CONCILIACIÓN'), '', 0, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(0, 0, utf8_decode('Fecha de Impresión: ') . date("d/m/Y"), '', 0, 'R');
        $this->Ln(5);
        $this->Cell(0, 0, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
        $this->Ln(6);
    }

    function Footer() {
//
//    //Posición: a  cm del final
        $this->SetY(-25);
        $this->Ln();

        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 5, 'Elaborado Por: ' . $_SESSION['usuario'], 0, 1, 'L');
        $this->Cell(0, 5, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 1, 'C');
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
        $comunes = new ConexionComun();
        $sql = "SELECT tipo_movimiento,
(
SELECT descripcion
FROM `tipo_movimientos_ban` where   	cod_tipo_movimientos_ban = movimientos_bancarios.tipo_movimiento
) as descripcion
 , sum(monto) as monto FROM `movimientos_bancarios` where cod_conciliacion = " . $_GET["cod_conciliacion"] . "
group by tipo_movimiento";
        $totales_sumaByTipo = $comunes->ObtenerFilasBySqlSelect($sql);

        $sql = "SELECT movimientos_bancarios.*, tipo_movimientos_ban.descripcion as tipo_descripcion  FROM `movimientos_bancarios` inner join tipo_movimientos_ban on
            tipo_movimientos_ban.cod_tipo_movimientos_ban = movimientos_bancarios.tipo_movimiento
            where cod_conciliacion =  " . $_GET["cod_conciliacion"] . " order by movimientos_bancarios.fecha_movimiento";
        $movimientos = $comunes->ObtenerFilasBySqlSelect($sql);





        $sumGeneral = 0;
        $cantidadGeneral = 0;
        foreach ($totales_sumaByTipo as $agrupamiento) {
            $this->SetWidths(array(188));
            $this->SetAligns(array('L'));
            $this->Setceldas(array('TLRB'));
            $this->SetFont('Arial', 'I', 8);
            $this->Row(array(
                "Tipo de Movimiento: " . $agrupamiento["descripcion"]
            ));

            $this->SetWidths(array(10, 30, 18, 105, 25));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C'));
            $this->Setceldas(array('TLRB', 'TLRB', 'TLRB', 'TLRB', 'TLRB', 'TLRB'));
            $this->SetFont('Arial', 'B', 8);
            $this->SetFillColor(201, 199, 199);
            $this->Row(array(
                "ID",
                "Fecha Movimiento",
                "Referencia",
                "Concepto",
                "Monto"
                    ), 1);

            $sumByTipo = 0;
            $cantidadByTipo = 0;
            foreach ($movimientos as $result) {
                if ($agrupamiento["tipo_movimiento"] == $result["tipo_movimiento"]) {
                    $this->SetFont('Arial', '', 8);
                    list($anio, $mes, $dia) = explode("-", $result["fecha_movimiento"]);
                    $fecha = $dia . "/" . $mes . "/" . $anio;
                    $this->SetAligns(array('C', 'C', 'C', 'L', 'R'));
                    $this->Row(array(
                        $result["cod_movimiento_ban"],
                        $fecha,
                        $result["numero_movimiento"],
                        $result["concepto"],
                        number_format($result["monto"], 2, ",", ".")
                    ));
                    $sumByTipo+=$result["monto"];
                    $cantidadByTipo++;
                }
            }


            $this->SetWidths(array(163, 25));
            $this->SetFont('Arial', 'B', 8);
            $this->SetAligns(array('R', 'R'));
            $this->Setceldas(array('TLRB', 'TLRB'));
            $this->SetFillColor(201, 199, 199);
            $this->Row(array(
                "Total",
                number_format($sumByTipo, 2, ",", ".")
                    ), 1);
            $this->Row(array(
                "Cantidad",
                $cantidadByTipo
                    ), 1);

            $sumGeneral+=$sumByTipo;
            $cantidadGeneral+=$cantidadByTipo;
        }
        $this->Ln(5);
        $this->SetFillColor(201, 199, 199);
        $this->Row(array(
            "Total General",
            number_format($sumGeneral, 2, ",", ".")
                ), 1);
        $this->Row(array(
            "Cantidad Total de Movimientos",
            $cantidadGeneral
                ), 1);
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

}

$comunes = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros, parametros_generales");

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->DatosGenerales($array_parametros_generales);
$pdf->SetTitle("Reporte de conciliacion Bancaria");
$pdf->PrintChapter();
$pdf->Output();
?>
