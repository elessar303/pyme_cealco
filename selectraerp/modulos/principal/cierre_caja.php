<?php
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
$producto = new Producto();
$bd_pos=POS;

//Cajas
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos = $producto->ObtenerFilasBySqlSelect("SELECT HOST FROM $bd_pos.closedcash group by HOST order by HOST");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["HOST"];
    $arraySelectOutPut[] = $item["HOST"];
}
$smarty->assign("option_values_cajas", $arraySelectOption);
$smarty->assign("option_output_cajas", $arraySelectOutPut);

        /*Para poder mostrar los datos en el TPL debemos mandarlos por ciertas variables por el PHP y poder mostrar las consultas
        entre otras cosas*/
        $aceptar=$_POST['aceptar']; //es lo que viene por el POST del formulario
        $smarty->assign('aceptar', $aceptar);
        $smarty->assign('datos', $datos); // la variable en la que guardamos el array de la consulta

$campos = $producto->cerrar();
        //echo "</table>";
?>
                
