<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=PDA.xls");
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');


$inicio = @$_GET["fecha"];
$final = @$_GET["fecha2"];
$orden = @$_GET["ordenado_por"];
$producto=@$_GET["producto"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales"); 
        

$inicio = @$_POST["fecha"];
$date = new DateTime($inicio);
$inicio=$date->format('Y-m-d');
$fin = @$_POST["fecha2"];
$date = new DateTime($fin);
$fin=$date->format('Y-m-d');
$estado = @$_POST["estado"];
$rubro = @$_POST["rubro"];
$tipo = @$_POST["tipo"];
$departamento= @$_POST["alimento"];
$filtroBarras="";
    if(isset($_POST['codigoBarra']) && !empty($_POST['codigoBarra']))
    {
        $filtroBarras="and e.codigo_barras='".$_POST['codigoBarra']."' ";
    }
    if($tipo==999)
    {
        $filtrot="";
    }
    else
    {
        $filtrot=" and a.tipo='".$tipo."'";
    }
    if($departamento=='999')
    {
        $filtroDepartamento="";
    }
    else
    {
        $filtroDepartamento=" and e.cod_departamento='".$departamento."' ";
    }
    if($estado==000)
    {
        $filtro="";
    }
    else
    {
        $filtro=" and g.codigo_estado=".$estado;
    }
    if($rubro==999)
    {
        $filtro2="";
    }
    else
    {
        $filtro2=" and h.cod_grupo=".$rubro;
    }



$array_factura = $comunes->ObtenerFilasBySqlSelect(
        "SELECT a.tipo, h.descripcion as rubro, f.cantidad as cantidad_destino, g.nombre_estado as estado, 
        a.id, a.orden_compra, date_format(f.fecha_inicio_logistica,'%d/%m/%Y') as fecha_inicio, date_format(f.fecha_fin_logistica,'%d/%m/%Y') as fecha_fin, date_format(a.fecha_planificacion, '%d/%m/%Y') as fecha_planificacion, d.descripcion, d.direccion, c.direccion_punto, e.descripcion1, b.cantida_x_kilo, format(((e.cantidad_bulto*f.cantidad)/1000), 3) as total, f.observaciones_logistica, a.transporte 
        FROM pda_maestro as a, pda_detalle as b, distribucion_pda as f, ".DB_SELECTRA_PYMEPP.".puntos_venta as c, proveedores as d, item as e, ".DB_SELECTRA_PYMEPP.".estados as g, grupo as h 
        where a.id=b.id_pda_maestro
        and e.cod_grupo=h.cod_grupo
        and b.id_producto=e.id_item 
        and b.id=f.id_pda_detalle
        and a.id_instalacion_origen='0'
        and f.destino_logistica=c.codigo_siga_punto
        and c.codigo_estado_punto=g.codigo_estado
        and a.id_proveedor=d.id_proveedor
        and a.fecha_planificacion between '".$inicio."' and '".$fin."'
        ".$filtro.$filtro2.$filtrot.$filtroBarras.$filtroDepartamento."

        UNION ALL

        SELECT a.tipo, h.descripcion as rubro, f.cantidad as cantidad_destino, g.nombre_estado as estado, 
        a.id, a.orden_compra, date_format(f.fecha_inicio_logistica,'%d/%m/%Y') as fecha_inicio, date_format(f.fecha_fin_logistica,'%d/%m/%Y') as fecha_fin, date_format(a.fecha_planificacion, '%d/%m/%Y') as fecha_planificacion, d.descripcion, i.direccion, c.direccion_punto, e.descripcion1, b.cantida_x_kilo, format(((e.cantidad_bulto*f.cantidad)/1000), 3) as total, f.observaciones_logistica, a.transporte 
        FROM pda_maestro as a, pda_detalle as b, distribucion_pda as f, ".DB_SELECTRA_PYMEPP.".puntos_venta as c, proveedores as d, item as e, ".DB_SELECTRA_PYMEPP.".estados as g, grupo as h , instalacion_proveedores as i
        where a.id=b.id_pda_maestro
        and e.cod_grupo=h.cod_grupo
        and b.id_producto=e.id_item 
        and a.id_instalacion_origen=i.codigo_sica
        and d.id_proveedor=i.id_proveedor
        and b.id=f.id_pda_detalle
        and f.destino_logistica=c.codigo_siga_punto
        and c.codigo_estado_punto=g.codigo_estado
        and a.id_proveedor=d.id_proveedor
        and f.fecha_inicio_logistica between '".$inicio."' and '".$fin."'
        ".$filtro.$filtro2.$filtrot.$filtroBarras.$filtroDepartamento
        );

?>
<table>
    <tr>
        <td colspan="12" align="center"><?php echo "<b>".utf8_decode("REPORTE PDA")."</b>"; ?></td>
    </tr>
    <tr>
        <td colspan="12" align="center"><?php echo "<b>Desde {$inicio} Hasta {$fin}</b>"; ?></td>
    </tr>
    <tr>
        <td align="center"><b>Tipo</b>
        </td>
        <td align="center"><b>Fecha Pl.</b>
        </td>
        <td align="center"><b>Proveedor</b>
        </td>
        <td align="center"><b>Direccion</b>
        </td>
        <td align="center"><b>Destino</b>
        </td>
        <td align="center"><b>Rubro</b>
        </td>
        <td align="center"><b>Cantidad</b>
        </td>
        <td align="center"><b>TM.</b>
        </td>
        <td align="center"><b>Fecha Inicio</b>
        </td>
        <td align="center"><b>Fecha Fin</b>
        </td>
        <td align="center"><b>Transporte</b>
        </td>
        <td align="center"><b>Observacion</b>
        </td>
    </tr>
<?php    

  foreach ($array_factura as $id => $reg) 
    {
    $total_cantidad+=$reg["cantidad_destino"];
    $total_tm+=$reg["total"];
    echo "  <tr>
                <td align='center'>".$reg["tipo"]."</td>
                <td style=mso-number-format:'@'; align='center'>".$reg["fecha_planificacion"]."</td>
                <td align='center'>".utf8_decode($reg["descripcion"])."</td>
                <td align='center'>".utf8_decode($reg["direccion"])."</td>
                <td align='center'>".utf8_decode($reg["direccion_punto"])."</td>
                <td align='center'>".$reg["rubro"]."</td>
                <td align='center'>".number_format($reg["cantidad_destino"], 2, ',', '.')."</td>
                <td align='center'>".number_format($reg['total'], 3, ',', '.')."</td>
                <td align='center'>".$reg["fecha_inicio"]."</td>
                <td align='center'>".$reg["fecha_fin"]."</td>
                <td align='center'>".$reg["transporte"]."</td>
                <td align='center'>".utf8_decode($reg["observaciones_logistica"])."</td>
            </tr>";
		
    }
  
  
  
    echo "  <tr>
                <td colspan='6' align='right'>T O T A L E S"."</td><td align='center'>".$total_cantidad."</td><td>".$total_tm."</td>
            </tr>";
    ?>
  </table>