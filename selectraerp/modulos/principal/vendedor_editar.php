<?php

include("../../libs/php/clases/vendedor.php");

$vendedor = new Vendedor();

if (isset($_POST["cod_vendedor"])) {
    $instruccion = "UPDATE vendedor SET
        cod_vendedor = '{$_POST["cod_vendedor"]}', nombre = '{$_POST["nombre"]}',
        direccion1  = '{$_POST["direccion1"]}', direccion2 = '{$_POST["direccion2"]}',
        telefonos = '{$_POST["telefonos"]}', fax = '{$_POST["fax"]}',
        email = '{$_POST["email"]}', clase = '{$_POST["clase"]}',
        venta_x_debajo_minimo = '{$_POST["venta_x_debajo_minimo"]}',
        venta_a_precio1 = '{$_POST["venta_a_precio1"]}',
        venta_a_precio2 = '" . $_POST["venta_a_precio2"] . "',
        venta_a_precio3 = '" . $_POST["venta_a_precio3"] . "',
        venta_x_servicio = '" . $_POST["venta_x_servicio"] . "',
        venta_gerericos = '" . $_POST["venta_gerericos"] . "',
        comision_x_debajo_minimo= '" . $_POST["comision_x_debajo_minimo"] . "',
        comision_a_precio1= '" . $_POST["comision_a_precio1"] . "',
        comision_a_precio2= '" . $_POST["comision_a_precio2"] . "',
        comision_a_precio3= '" . $_POST["comision_a_precio3"] . "',
        comision_x_servicio= '" . $_POST["comision_x_servicio"] . "',
        comision_gerericos= '" . $_POST["comision_gerericos"] . "',
        comision_tabla_de_cobros= '" . $_POST["comision_tabla_de_cobros"] . "',
        tipo_comision= '" . $_POST["tipo_comision"] . "',
        rancoshasta1= '" . $_POST["rancoshasta1"] . "',
  rancosdesde1= '" . $_POST["rancosdesde1"] . "',
  rancosdesde2= '" . $_POST["rancosdesde2"] . "',
  rancoshasta2= '" . $_POST["rancoshasta2"] . "',
  rancosdesde3= '" . $_POST["rancosdesde3"] . "',
  rancoshasta3= '" . $_POST["rancoshasta3"] . "',
  rancosdesde4= '" . $_POST["rancosdesde4"] . "',
  rancoshasta4= '" . $_POST["rancoshasta4"] . "',
  rancosdesde5= '" . $_POST["rancosdesde5"] . "',
  rancoshasta5= '" . $_POST["rancoshasta5"] . "',
  factor1= '" . $_POST["factor1"] . "',
  factor2= '" . $_POST["factor2"] . "',
  factor3= '" . $_POST["factor3"] . "',
  factor4= '" . $_POST["factor4"] . "',
  factor5= '" . $_POST["factor5"] . "',
  comision_tabla_de_cobrosven= '" . $_POST["comision_tabla_de_cobrosven"] . "',
  tipo_comisionven= '" . $_POST["tipo_comisionven"] . "',
  ranvenhasta1= '" . $_POST["ranvenhasta1"] . "',
  ranvendesde1= '" . $_POST["ranvendesde1"] . "',
  ranvendesde2= '" . $_POST["ranvendesde2"] . "',
  ranvenhasta2= '" . $_POST["ranvenhasta2"] . "',
  ranvendesde3= '" . $_POST["ranvendesde3"] . "',
  ranvenhasta3= '" . $_POST["ranvenhasta3"] . "',
  ranvendesde4= '" . $_POST["ranvendesde4"] . "',
  ranvenhasta4= '" . $_POST["ranvenhasta4"] . "',
  ranvendesde5= '" . $_POST["ranvendesde5"] . "',
  ranvenhasta5= '" . $_POST["ranvenhasta5"] . "',
  factor1ven= '" . $_POST["factor1ven"] . "',
  factor2ven= '" . $_POST["factor2ven"] . "',
  factor3ven= '" . $_POST["factor3ven"] . "',
  factor4ven= '" . $_POST["factor4ven"] . "',
  factor5ven= '" . $_POST["factor5ven"] . "' WHERE cod_vendedor = '{$_POST["id_vendedor"]}';";

    $vendedor->Execute2($instruccion);

    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
if (isset($_GET["cod"])) {
    $campos = $vendedor->ObtenerFilasBySqlSelect("SELECT * FROM vendedor WHERE cod_vendedor = {$_GET["cod"]};");
    if ($campos != "") {
        $smarty->assign("datosvendedor", $campos);
    }
}
?>
