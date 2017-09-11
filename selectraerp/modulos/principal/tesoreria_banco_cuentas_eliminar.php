<?php

$comunes = new Comunes();

if(isset($_POST["codCuenta"])){
    $query = "delete from  tesor_bancodet where cod_tesor_bandodet = ".$_POST["codCuenta"];
    $comunes->Execute2($query);
    header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]."&&opt_subseccion=viewcuentasByBanco&cod=".$_POST["codBanco"]);        exit;
exit;
}



$campos_cuentas = $comunes->ObtenerFilasBySqlSelect("select * from tesor_bancodet
                where cod_tesor_bandodet = ".$_GET["cod_cuenta"]);
$smarty->assign("campos_cuentas",$campos_cuentas);
/**
 * Verificamos si es permitido eliminar la chequera deseada.
 */
$PermiteEliminar = $comunes->ObtenerFilasBySqlSelect("select * from chequera
                where cod_tesor_bandodet = ".$_GET["cod_cuenta"]);
if(count($PermiteEliminar)>0){
       $smarty->assign("eliminar","no");
       $smarty->assign("mensaje","Disculpe, existe registro de chequeras asociadas a esta cuenta, por tal motivo no puede eliminar esta cuenta.");
       
}else{
       $smarty->assign("eliminar","si");
       $smarty->assign("mensaje","");


}








?>
