<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Libro_de_compras.xls");
include('config_reportes.php');
include('../../menu_sistemas/lib/common.php');

$fecha = @$_GET["fecha"];
$comunes = new ConexionComun();

$datosGenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
$fechaz = $fecha . "-01";
/* $arrayFacturas = $comunes->ObtenerFilasBySqlSelect("
  SELECT c*, p*, f.*, FROM compra c inner join proveedor p.id_proveedor= c.id_proveedor
  where month(c.fechacompra) = month('" . $fechaz . "') and year(c.fechacompra) = year('" . $fechaz . "')"); */
$sql = "select F.fecha_factura,P.rif,P.descripcion,F.cod_factura,
            F.factura_afectada,F.cod_cont_factura,F.monto_total_con_iva,
            F.monto_exento,F.monto_base,F.porcentaje_iva_mayor,F.monto_iva,
            F.monto_retenido, F.tipo, F.monto_total_sin_iva,F.anticipo
        from cxp_factura as F, proveedores as P, cxp_edocuenta as C
        where F.id_cxp_edocta=C.cod_edocuenta and C.id_proveedor=P.id_proveedor
            and month(F.fecha_recepcion) = month('" . $fechaz . "')
                and year(F.fecha_recepcion) = year('" . $fechaz . "') and libro_compras=1";
$arrayFacturas = $comunes->ObtenerFilasBySqlSelect($sql);
$mes = mesaletras(substr($fecha, 5, 2));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
    </head>
    <body>
        <table border="1">
            <tr style="font-weight: bold;">
                <td align='center' colspan="8" >RELACION DE COMPRAS <?php echo strtoupper($mes) . " " . substr($fecha, 0, 4); ?></td>
                <td colspan="5">EMPRESA: <?php echo $datosGenerales[0]["nombre_empresa"]; ?></td>
                <td colspan="4">RIF: <?php echo $datosGenerales[0]["rif"]; ?></td>
            </tr>
            <tr style="Font-weight: bold;">
                <td>Oper. Nro.</td>
                <td>Fecha Factura</td>
                <td>R.I.F.</td>
                <td>Nombre o Razon social del Proveedor</td>
                <td>Tipo Proveedor</td>
                <td>Nro. Factura</td>
                <td>Nro. Control Factura</td>
                <td>Nota de Debito</td>
                <td>Nota de Credito</td>
                <td>Tipo de Trans.</td>
                <td>Nro. Fac. Afectada</td>
                <td>Total de Compras con IVA</td>
                <td>Anticipo</td>
                <td>Compras S/D Cr&eacute;dito Fiscal</td>
                <td>Monto Exento</td>
                <td>Base Imponible</td>
                <td>%</td>
                <td>IVA</td>
                <td>IVA Retenido por Comprador</td>
            </tr>
            <tr>
                <?php
                $totalComprasConIva = 0.0;
                $totalComprasSDcreditoFiscal = 0.0;
                $totalMontoExento = 0.0;
                $TotalBaseImponible = 0.0;
                $totalIva = 0.0;
                $totalIvaRet = 0.0;
                $i = 0;
                while ($arrayFacturas[$i]) {
                    $porc = ($arrayFacturas[$i]['monto_iva'] * 100) / $arrayFacturas[$i]['monto_total_sin_iva'];
                    if (($porc >= 11.9) && ($porc < 12))
                        $porc = 12;
                    echo "<td style=\"text-align:center\">" . ($i + 1) . "</td>
                    <td style=\"text-align:center\">" . $arrayFacturas[$i]['fecha_factura'] . "</td>
                    <td style=\"text-align:right\">" . $arrayFacturas[$i]['rif'] . "</td>
                    <td>" . $arrayFacturas[$i]['descripcion'] . "</td>
                    <td style=\"text-align:right\">" . ($arrayFacturas[$i]['rif'][0] == "V" ? "N" : "Juridico") . "</td>
                    <td style=\"text-align:right\">" . $arrayFacturas[$i]['cod_factura'] . "</td>
                    <td style=\"text-align:right\">" . $arrayFacturas[$i]['cod_cont_factura'] . "</td>
                    <td>0,00</td>
                    <td>0,00</td>
                    <td></td>
                    <td></td>
                    <td style=\"text-align:right\">" . ($arrayFacturas[$i]['monto_total_sin_iva'] + $arrayFacturas[$i]['monto_iva']) . "</td>
                    <td style=\"text-align:right\">" . $arrayFacturas[$i]['anticipo'] . "</td>
                    <td style=\"text-align:right\">" . $arrayFacturas[$i]['cod_factura'] . "</td>
                    <td style=\"text-align:right\">" . $arrayFacturas[$i]['monto_exento'] . "</td>
                    <td style=\"text-align:right\">" . $arrayFacturas[$i]['monto_base'] . "</td>
                    <td style=\"text-align:right\">" . $porc . "</td>
                    <td style=\"text-align:right\">" . $arrayFacturas[$i]['monto_iva'] . "</td>
                    <td style=\"text-align:right\">" . $arrayFacturas[$i]['monto_retenido'] . "</td></tr>";
                    $totalDebito+=0;
                    $totalCredito+=0;
                    $totalComprasConIva+=($arrayFacturas[$i]['monto_total_sin_iva'] + $arrayFacturas[$i]['monto_iva']);
                    $totalVentasNoGravadas+=$arrayFacturas[$i]['monto_exento'];
                    $totalBaseImponible+=$arrayFacturas[$i]['monto_base'];
                    $totalIva+=$arrayFacturas[$i]['monto_iva'];
                    $totalIvaRet+=$arrayFacturas[$i]['monto_retenido'];
                    $i++;
                }
                echo "<tr>
            <td colspan='11'> TOTAL: </td>
            <td style=\"text-align:right; decimal\">" . $totalComprasConIva . "</td>
            <td></td>
            <td style=\"text-align:right\">" . $totalComprasSDcreditoFiscal . "</td>
            <td style=\"text-align:right\">" . $totalVentasNoGravadas . "</td>
            <td style=\"text-align:right\">" . $totalBaseImponible . "</td>
            <td></td>
            <td style=\"text-align:right\">" . $totalIva . "</td>
            <td style=\"text-align:right\">" . $totalIvaRe . "</td></tr>";
                ?>
        </table>
    </body>
</html>