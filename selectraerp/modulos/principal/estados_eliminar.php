<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();

if(isset($_POST["eliminar"])){

	$campos2 = $almacen->ObtenerFilasBySqlSelect("select * from estado_region where id = ".$_POST["id"]);

	$instruccion = "delete from estado_region WHERE id = ".$_POST["id"];
	$almacen->Execute2($instruccion);

    $instruccion3 = "UPDATE estados set
   `estatus` = 0 WHERE id = ".$campos2[0]["id_estado"];
    $almacen->Execute2($instruccion3);

    header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=listEst&id=".$_GET["id"]);
}
  

if(isset($_GET["cod"])){
$campos = $almacen->ObtenerFilasBySqlSelect("select a.id ,a.id_region, a.id_estado, b.nombre from estado_region as a, estados as b where a.id = ".$_GET["cod"]." and a.id_estado=b.id");
$smarty->assign("datos_region",$campos);
}

?>