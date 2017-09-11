<?php

include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");

$almacen = new Almacen();
$pendiente = false;
$pos=POS;
if (isset($_GET["cod"])) {

    $pendiente = !$pendiente;
}

$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());
//cargando select de proveedores
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $almacen->ObtenerFilasBySqlSelect("SELECT * FROM proveedores order by descripcion");

foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $nombre_proveedor=$item["descripcion"]."-".$item["rif"];
    $arraySelectoutPut[] = utf8_encode($nombre_proveedor);
}
$smarty->assign("option_values_proveedor", $arraySelectOption);
$smarty->assign("option_output_proveedor", $arraySelectoutPut);

$obtener_valores=$almacen->ObtenerFilasBySqlSelect("select * from pda_maestro where id=".$_GET['cod']);
$smarty->assign("maestro", $obtener_valores);

//Estados
$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * ".DB_SELECTRA_PYMEPP.".from estados_puntos");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["nombre_estado"];
    $arrayubi[] = $item["codigo_estado"];
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

// punto de ventas
$arraySelectOption = "";
$arraySelectoutPut1 = "";
$cliente = new Almacen();

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT `nombre_punto`,codigo_siga_punto as siga  from ".DB_SELECTRA_PYMEPP.".puntos_venta where estatus='A'");
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["siga"];
    $arraySelectOutPut1[] = $puntos["nombre_punto"];
}

$smarty->assign("option_values_punto", $arraySelectOption);
$smarty->assign("option_output_punto", $arraySelectOutPut1);
//instalacion
$sql="SELECT a.codigo_sica, UPPER(concat(b.nombre,' --- ',a.direccion)) as instalacion from instalacion_proveedores as a, estados as b where a.estado=b.id";
$punto = $cliente->ObtenerFilasBySqlSelect($sql);
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["codigo_sica"];
    $arraySelectOutPut1[] = $puntos["instalacion"];
}

$smarty->assign("option_values_instalacion", $arraySelectOption);
$smarty->assign("option_output_instalacion", $arraySelectOutPut1);



//Fin de los SELECTS para la nueva seccion

#################################################################################
if (isset($_POST["aceptar"])) { 

   $orden_compra=(isset($_GET["nro_documento"])) ? $_GET["nro_documento"] : $_POST["nro_documento"];
   if($orden_compra==null){

    echo "<script language='JavaScript' type='text/JavaScript'>
        alert('Error, Debe Indicar la orden de compra');
        history.back(-1);
        </script>
        ";exit();
   }
   // $almacen->BeginTrans();
    $almacen->Execute2("update pda_maestro set orden_compra='".$orden_compra."', id_usuario_compras='".$login->getUsuario()."' where id=".$_POST['id_maestro']);

     if ($almacen->errorTransaccion == 1)
    {    
        //echo "paso";   
        Msg::setMessage("<span style=\"color:#62875f;\">Entrada Generada Exitosamente </span>");
    }
    elseif($almacen->errorTransaccion == 0)
    {
        //echo "no paso";
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de cargar la entrada, por favor intente nuevamente.</span>");
    }

    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
   

}




?>
