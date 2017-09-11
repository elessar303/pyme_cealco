<?php
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
$producto = new Producto();
$bd_pos=POS;

//verificar  ubicacion de venta
$ubicacion=$producto->ObtenerFilasBySqlSelect("select venta_pyme from parametros_generales");
$ubicacion=$ubicacion[0]["venta_pyme"];


$smarty->assign("ubicacion_venta", $ubicacion);


//Cajas
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos = $producto->ObtenerFilasBySqlSelect("SELECT a.ID, a.NAME FROM $bd_pos.people as a, $bd_pos.log_trans as b  where a.VISIBLE=1 and a.id=b.user and date(DATE)>=(select fecha from operaciones order by fecha desc limit 1) and b.DESCRIPTION like '%VENTA RECIBO%' group by b.user order by NAME");
//echo "SELECT a.ID, a.NAME FROM $bd_pos.people as a, $bd_pos.log_trans as b  where a.VISIBLE=1 and a.id=b.user and date(DATE)=(select fecha from operaciones order by fecha desc limit 1) and b.DESCRIPTION like '%VENTA RECIBO%' group by b.user order by NAME"; exit();
$cajero_pyme=$producto->ObtenerFilasBySqlSelect("select cod_usuario, usuario from usuarios as a, factura as b where a.cod_usuario=b.cod_vendedor  and a.vendedor=1 and a.visible_vendedor=1 and date(b.fecha_creacion)=(select fecha from operaciones order by fecha desc limit 1) group by cod_usuario");
//echo "select cod_usuario, usuario from usuarios as a, factura as b where a.cod_usuario=b.cod_vendedor and a.vendedor=1 and b.fechaFactura=(select fecha from operaciones order by fecha desc limit 1) group by cod_usuario"; exit();
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["ID"];
    $arraySelectOutPut[] = $item["NAME"];
}

foreach($cajero_pyme as $key => $cajeros){
	$arraySelectOption_1[] = $cajeros["cod_usuario"];
    $arraySelectOutPut_1[] = $cajeros["usuario"];

}


$smarty->assign("option_values_cajas", $arraySelectOption);
$smarty->assign("option_output_cajas", $arraySelectOutPut);

$smarty->assign("option_values_cajas_pyme", $arraySelectOption_1);
$smarty->assign("option_output_cajas_pyme", $arraySelectOutPut_1);

        /*Para poder mostrar los datos en el TPL debemos mandarlos por ciertas variables por el PHP y poder mostrar las consultas
        entre otras cosas*/
        $aceptar=$_POST['aceptar']; //es lo que viene por el POST del formulario
        $smarty->assign('aceptar', $aceptar);
        $smarty->assign('datos', $datos); // la variable en la que guardamos el array de la consulta

$campos = $producto->cerrar();
        //echo "</table>";
?>
                