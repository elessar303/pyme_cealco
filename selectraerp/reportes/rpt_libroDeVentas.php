<?php

include('config_reportes.php');
include('../../menu_sistemas/lib/common.php');

$comunes = new ConexionComun();

$datosGenerales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");

/* $fecha = @$_GET["fecha"] . "-01";
  $arrayFacturas = $comunes->ObtenerFilasBySqlSelect(
  "SELECT f.*, c.rif, c.nombre, c.direccion, c.nit, c.cod_cliente, c.telefonos, c.direccion
  FROM factura f
  INNER JOIN clientes c ON c.id_cliente = f.id_cliente
  WHERE month(f.fechaFactura) = month('{$fecha}') AND year(f.fechaFactura) = year('{$fecha}')
  ORDER BY f.id_factura;");
 */
$desde = @$_GET["fecha"];
$hasta = @$_GET["fecha2"];

$arrayFacturas = $comunes->ObtenerFilasBySqlSelect(
        "SELECT f.*, c.rif, c.nombre, c.direccion, c.nit, c.cod_cliente, c.telefonos, c.direccion
         FROM factura f
         INNER JOIN clientes c ON c.id_cliente = f.id_cliente
         WHERE f.fechaFactura BETWEEN '{$desde}' AND '{$hasta}'
         ORDER BY f.id_factura");

class FacturaDevuelta {

    public $array_facturas_devueltas;

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

$arrayFactDev = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_devolucion");

$fd = new FacturaDevuelta();
$fd->ArrayFacturasDevueltas($arrayFactDev);

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=LIBRO DE VENTAS " . strtoupper(mesaletras(substr($desde, 5, 2))) . "-" . substr($desde, 0, 4) . ".xls"); #date("Ym")

?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <table border="1">
            <tr style="font-weight: bold;">
                <td colspan="8" style="text-align:center;">RELACI&Oacute;N DE VENTAS <?php echo strtoupper(mesaletras(substr($desde, 5, 2))) . "-" . substr($desde, 0, 4) ?></td>
                <td colspan="5">EMPRESA: <?php echo $datosGenerales[0]["nombre_empresa"] ?></td>
                <td colspan="3">RIF: <?php echo $datosGenerales[0]["rif"] ?></td>
            </tr>
            <tr style="font-weight: bold;">
                <td>Oper. Nro.</td><td>Fecha Factura</td><td>R.I.F.</td><td>Nombre o Raz&oacute;n Social del Cliente</td><td>Nro. Factura</td><!--td>N Control Factura</td--><td>Nota de Debito</td><td>Nota de Cr&eacute;dito</td><td>Tipo de Trans.</td><td>Nro. Fac. Afectada</td><td>Total de Ventas con IVA</td><td>Ventas no gravadas</td><td>Base Imponible</td><td>%</td><td>IVA</td><td>IVA Retenido</td><td>Nro. de Comprobante de Retenci&oacute;n</td>
            </tr>
            <?php
            $tipo_facturacion = $arrayFacturas[0]["tipo_facturacion"];

            $totalDebito = $totalCredito = $totalVentasConIva = $totalVentasNoGravadas = $totalBaseImponible = $totalIva = $totalIvaRet = $totalExento = $totalGravado = 0;
            $i = 0;
            while ($arrayFacturas[$i]) {
                /* $porc = ($arrayFacturas[$i][ivaTotalFactura] * 100) / $arrayFacturas[$i][montoItemsFactura];
                  if (($porc >= 11.9) && ($porc < 12))
                  $porc = 12; */
                $array_factura_detalles = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM factura_detalle WHERE id_factura = {$arrayFacturas[$i]["id_factura"]};");

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

                $iva_factura = $arrayFacturas[$i]["cod_estatus"] == 3 ? (-1) * $arrayFacturas[$i]["ivaTotalFactura"] : $arrayFacturas[$i]["ivaTotalFactura"];
                $retencion = $arrayFacturas[$i]["cod_estatus"] == 3 ? (-1) * $arrayFacturas[$i]["totalizar_total_retencion"] : $arrayFacturas[$i]["totalizar_total_retencion"];
                $total_factura = $arrayFacturas[$i]["cod_estatus"] == 3 ? (-1) * $arrayFacturas[$i]["totalizar_total_general"] : $arrayFacturas[$i]["totalizar_total_general"];
                $bi = $arrayFacturas[$i]["totalizar_total_general"] - $arrayFacturas[$i]["totalizar_monto_iva"] - $exento;
                $base_imponible = $arrayFacturas[$i]["cod_estatus"] == 3 ? (-1) * $bi : $bi;
                $exento = $arrayFacturas[$i]["cod_estatus"] == 3 ? (-1) * $exento : $exento;
                $gravado = $arrayFacturas[$i]["cod_estatus"] == 3 ? (-1) * $gravado : $gravado;
                ?>
                <!--Los &nbsp; son un truco para que Excel respete el format en que envio los datos-->
                <tr>
                    <td><?php echo $i + 1 ?></td><!--td>Oper. Nro.</td-->
                    <td><?php echo $arrayFacturas[$i]["fechaFactura"] ?></td><!--td>Fecha Factura</td-->
                    <td style="text-align: center;"><?php echo $arrayFacturas[$i]["rif"] ?></td><!--td>R.I.F.</td-->
                    <td><?php echo $arrayFacturas[$i]["nombre"] ?></td><!--td>Nombre o Raz&oacute;n Social del Cliente</td-->
                    <td style="text-align: center;">&nbsp;<?php echo $tipo_facturacion == 1 ? $arrayFacturas[$i]["cod_factura_fiscal"] : $arrayFacturas[$i]["cod_factura"] ?></td><!--td>Nro. Factura</td-->
                    <!--td style="text-align: center;">&nbsp;<?php #echo $arrayFacturas[$i]["cod_factura"]      ?></td--><!--td>N Control Factura</td-->
                    <td style="text-align: center;">-</td><!--td>Nota de Debito</td-->
                    <td style="text-align: center;">&nbsp;<?php echo $arrayFacturas[$i]["cod_estatus"] == 3 ? ($tipo_facturacion == 1 ? $fd->array_facturas_devueltas[$fd->getFacturaDevuelta($arrayFacturas[$i]["cod_factura"])]["cod_devolucion_fiscal"] : $fd->array_facturas_devueltas[$fd->getFacturaDevuelta($arrayFacturas[$i]["cod_factura"])]["cod_devolucion"]) : "-" ?></td><!--td>Nota de Cr&eacute;dito</td-->
                    <td></td><!--td>Tipo de Trans.</td-->
                    <td></td><!--td>Nro. Fac. Afectada</td-->
                    <td style="mso-number-format:'0.00';"><?php echo number_format($total_factura, 2, ',', '.')/* ($arrayFacturas[$i]["montoItemsFactura"] + $arrayFacturas[$i]["ivaTotalFactura"]) */ ?></td><!--td>Total de Ventas con IVA</td-->
                    <td style="mso-number-format:'0.00';"><?php echo number_format($exento, 2, ',', '.') ?></td><!--td>Ventas no gravadas</td-->
                    <td style="mso-number-format:'0.00';"><?php echo number_format($base_imponible, 2, ',', '.')/* $arrayFacturas[$i]["totalizar_base_imponible"] */ ?></td><!--td>Base Imponible</td-->
                    <td style="mso-number-format:'0.00';"><?php echo number_format($flag ? $porc : 0, 2, ',', '.')/* $porc */ ?></td><!--td>%</td-->
                    <td style="mso-number-format:'0.00';"><?php echo number_format($iva_factura, 2, ',', '.')/* $arrayFacturas[$i]["ivaTotalFactura"] */ ?></td><!--td>IVA</td-->
                    <td style="mso-number-format:'0.00';"><?php echo number_format($retencion, 2, ',', '.')/* $arrayFacturas[$i]["totalizar_total_retencion"] */ ?></td><!--td>IVA Retenido</td-->
                    <td style="mso-number-format:'0.00';"><?php #echo number_format(0, 2, ',', '.')      ?></td><!--td>Nro. de Comprobante de Retenci&oacute;n</td-->
                </tr>
                <?php
                /* $totalDebito+=0;
                  $totalCredito+=0;
                  $totalVentasConIva+=($arrayFacturas[$i][montoItemsFactura] + $arrayFacturas[$i][ivaTotalFactura]);
                  $totalVentasNoGravadas+=0;
                  $totalBaseImponible+=$arrayFacturas[$i][totalizar_base_imponible];
                  $totalIva+=$arrayFacturas[$i][ivaTotalFactura];
                  $totalIvaRet+=$arrayFacturas[$i][totalizar_total_retencion]; */
                $totalVentasConIva+= $total_factura;
                $totalVentasNoGravadas+=0;
                $totalBaseImponible+=$base_imponible;
                $totalIva+=$iva_factura;
                $totalIvaRet+=$retencion;
                $totalExento+=$exento;
                $totalGravado+=$gravado;
                $i++;
            }
            ?>
            <tr style="font-weight: bold;">
                <td colspan="9"> TOTAL: </td>
                <td style="mso-number-format:'0.00';"><?php echo number_format($totalVentasConIva, 2, ',', '.') ?></td>
                <td style="mso-number-format:'0.00';"><?php echo number_format($totalExento, 2, ',', '.') ?></td>
                <td style="mso-number-format:'0.00';"><?php echo number_format($totalBaseImponible, 2, ',', '.') ?></td>
                <td style="mso-number-format:'0.00';"></td>
                <td style="mso-number-format:'0.00';"><?php echo number_format($totalIva, 2, ',', '.') ?></td>
                <td style="mso-number-format:'0.00';"><?php echo number_format($totalIvaRet, 2, ',', '.') ?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="16" style="border:0;"></td>
            </tr>
        </table>
        <table border="1">
            <thead>
                <tr style="font-weight: bold;">
                    <td colspan="10" style="border: 0;"></td>
                    <td colspan="4" style="text-align: center;"><?php echo "DEBITOS FISCALES" ?></td>
                    <td style="text-align: center;"><?php echo "BASE IMPONIBLE" ?></td>
                    <td style="text-align: center;"><?php echo "DEBITO FISCAL" ?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="10" style="border: 0;"></td>
                    <td colspan="4"><?php echo "Total Ventas no gravadas y/o sin derecho a credito fiscal" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo number_format($totalExento, 2, ',', '.') ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo "0,00" ?></td>
                </tr>
                <tr>
                    <td colspan="10" style="border: 0;"></td>
                    <td colspan="4"><?php echo "Total de las Importaciones gravadas por alicuota general" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo "0,00" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo "0,00" ?></td>
                </tr>
                <tr>
                    <td colspan="10" style="border: 0;"></td>
                    <td colspan="4"><?php echo "Total de las Importaciones gravadas por alicuota general mas adicional" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo "0,00" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo "0,00" ?></td>
                </tr>
                <tr>
                    <td colspan="10" style="border: 0;"></td>
                    <td colspan="4"><?php echo "Total de las Importaciones gravadas por alicuota reducida" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo "0,00" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo "0,00" ?></td>
                </tr>
                <tr>
                    <td colspan="10" style="border: 0;"></td>
                    <td colspan="4"><?php echo "Total Ventas internas gravadas solo por alicuita general" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo number_format($totalBaseImponible, 2, ',', '.') ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo number_format($totalIva, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="10" style="border: 0;"></td>
                    <td colspan="4"><?php echo "Total Ventas internas gravadas solo por alicuita general mas adicional" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo number_format($totalBaseM = 0, 2, ',', '.') ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo number_format($totalivaM = 0, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="10" style="border: 0;"></td>
                    <td colspan="4"><?php echo "Total Ventas internas gravadas solo por alicuita reducida" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo number_format($totalBase8 = 0, 2, ',', '.') ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo number_format($totaliva8 = 0, 2, ',', '.') ?></td>
                </tr>
                <tr style="font-weight: bold">
                    <td colspan="10" style="border: 0;"></td>
                    <td colspan="4"><?php echo "Total Ventas y Debito Fiscal p/Efectos de determinacion" ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo number_format($totalExento + $totalBaseImponible + $totalBaseM + $totalBase8, 2, ',', '.') ?></td>
                    <td style="mso-number-format:'0.00';"><?php echo number_format($totalIva + $totalivaM + $totaliva8, 2, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>