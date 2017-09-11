<?php
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
$producto = new Producto();
$bd_pos=POS;
$bd_pyme=DB_SELECTRA_FAC;

//verificar  ubicacion de venta
$ubicacion=$producto->ObtenerFilasBySqlSelect("select venta_pyme from parametros_generales");
$ubicacion=$ubicacion[0]["venta_pyme"];


$smarty->assign("ubicacion_venta", $ubicacion);


//Cajas
$arraySelectOption = "";
$arraySelectoutPut = "";
$sql="select HOST  from $bd_pos.closedcash as a, $bd_pyme.caja_impresora as b
where a.host=b.caja_host and  a.money not in  (select  money from $bd_pyme.libro_ventas) 
and a.money in (select money from $bd_pos.receipts)
and date(dateend)>=(select min(fecha) from $bd_pyme.fechas_minimas)  
group by host order by host, dateend asc"; 
$campos = $producto->ObtenerFilasBySqlSelect($sql);

$campos_pyme = $producto->ObtenerFilasBySqlSelect("select nombre_caja  from $bd_pyme.closedcash_pyme as a, $bd_pyme.caja_impresora as b
where a.nombre_caja=b.caja_host and  a.money not in  (select  money from $bd_pyme.libro_ventas) and date(fecha_fin)>=(select min(fecha) from $bd_pyme.fechas_minimas)  and a.money in (select money from $bd_pyme.factura) group by nombre_caja order by nombre_caja, fecha_fin   asc");

//echo "select nombre_caja  from $bd_pyme.closedcash_pyme as a, $bd_pyme.caja_impresora as b
//where a.nombre_caja=b.caja_host and  a.money not in  (select  money from $bd_pyme.libro_ventas) and date(fecha_fin)>=(select min(fecha) from $bd_pyme.fechas_minimas)  group by nombre_caja order by nombre_caja, fecha_fin   asc"; exit();



//echo "select HOST  from $bd_pos.closedcash as a, $bd_pyme.caja_impresora as b
// where a.host=b.caja_host and  a.money not in  (select  money from $bd_pyme.libro_ventas) and date(dateend)>(select min(fecha) from $bd_pyme.fechas_minimas)  group by host order by host, dateend   asc"; exit();

 
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["HOST"];
    $arraySelectOutPut[] = $item["HOST"];
}

foreach ($campos_pyme as $key => $item) {
    $arraySelectOption_pyme[] = $item["nombre_caja"];
    $arraySelectOutPut_pyme[] = $item["nombre_caja"];
}

//print_r($campos); exit();
$smarty->assign("option_values_cajas", $arraySelectOption);
$smarty->assign("option_output_cajas", $arraySelectOutPut);
$smarty->assign("option_values_cajas_pyme", $arraySelectOption_pyme);
$smarty->assign("option_output_cajas_pyme", $arraySelectOutPut_pyme);

        /*Para poder mostrar los datos en el TPL debemos mandarlos por ciertas variables por el PHP y poder mostrar las consultas
        entre otras cosas*/
        $aceptar=$_POST['aceptar']; //es lo que viene por el POST del formulario
        $smarty->assign('aceptar', $aceptar);
        $smarty->assign('datos', $datos); // la variable en la que guardamos el array de la consulta

$campos = $producto->cerrar();
        //echo "</table>";
?>
                
