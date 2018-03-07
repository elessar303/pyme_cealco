<?php
include('../reportes/config_reportes.php');
require('../fpdf/rotacion.php');
$nro=isset($_GET["id"]) ? $_GET["id"] : null;
$comunes = new ConexionComun();

$datosgenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
$sql = 
"
    select c.codigo_barras, c.descripcion1, b.etiqueta,  d.nombre as proveedor, date_format(a.fecha_creacion, '%d/%m/%Y') as fecha_recepcion,
    b.id_transaccion_detalle as nro_recepcion, b.lote, b.cantidad, b.peso, b.observacion, m.marca
    from kardex_almacen as a
    inner join kardex_almacen_detalle as b on a.id_transaccion=b.id_transaccion
    left join marca as m on m.id=b.id_marca
    inner join item as c on c.id_item=b.id_item
    inner join clientes as d  on a.id_proveedor=d.id_cliente
    where b.id_transaccion_detalle=".$nro;

$datoscampos=$comunes->ObtenerFilasBySqlSelect($sql);

?>
<p align="center"><?= $datosgenerales[0]['nombre_empresa'];?></p>
<p align="center"><?= $datosgenerales[0]["id_fiscal"] . ": " . $datosgenerales[0]["rif"] . " - Telefonos: " . $datosgenerales[0]["telefonos"]; ?></p>
<p align="center"><?= $datosgenerales[0]['direccion'];?></p>
<table align="center">
    <tr>
        <td>
            Codigo:
        </td>
        <td colspan="2">
            <?= $datoscampos[0]['codigo_barras']; ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            Producto:
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <?= $datoscampos[0]['descripcion1']." - ".$datoscampos[0]['marca']; ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <hr>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center">
            Etiq. :
        
            <?=$datoscampos[0]['etiqueta'] ?>
        </td>
    </tr>
    <tr>
        <td>
           
        </td>
        
        <td colspan="2">
            <? echo '<img src="font/barcode.php?text='.$datoscampos[0]['codigo_barras'].'&size=40&print=true"/>'; ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            Cliente: <?= $datoscampos[0]['proveedor'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <hr>
        </td>
    </tr>
    <tr>
        <td >
            <p>Fecha de recepcion : </p>
        </td>
        <td>
            
        </td>
        <td>
            <p># Nro. Recepcion:</p>
        </td>
    </tr>
    <tr>
        <td>
            <p><?= $datoscampos[0]['fecha_recepcion']; ?> </p>
        </td>
        <td>
        </td>
        <td>
            <p><?= $datoscampos[0]['nro_recepcion']; ?></p>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <hr>
        </td>
    </tr>
    <tr>
        <td>
            <p>Lote: </p>
        </td>
        <td>
            <p>Cantidad U/M: </p>
        </td>
        
        <td align="center">
            <p>Peso:</p>
        </td>
    </tr>
    <tr>
        <td>
            <p><?= $datoscampos[0]['lote']; ?> </p>
        </td>
        <td>
            <p><?= $datoscampos[0]['cantidad']; ?></p>
        </td>
        <td align="center">
            <p><?= $datoscampos[0]['peso']; ?></p>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <hr>
        </td>
    </tr>
    <tr>
        <td colspna="3">
            Observaciones:
            <p><?= $datoscampos[0]['observacion']; ?></p>
        </td>
        
    </tr>
    <tr>
        <td colspan="3">
            <hr>
        </td>
    </tr>
</table>
