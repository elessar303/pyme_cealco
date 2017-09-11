<?php


$comunes = new Comunes();


if(isset($_GET["cod_cuenta"])){
    $campos = $comunes->ObtenerFilasBySqlSelect("
select
tb.cod_tesor_bandodet,
tb.cod_banco,
tb.nro_cuenta,
b.descripcion as descripcion_banco,
tb.descripcion as descripcion_cuenta
from tesor_bancodet tb
inner join banco b on b.cod_banco = tb.cod_banco
 where tb.cod_tesor_bandodet = ".$_GET["cod_cuenta"]);

$smarty->assign("datos_banco",$campos);
}




if(isset ($_POST["inicio"])){
    $comunes->Execute2("
update `chequera` set 
`cantidad`  = '".$_POST["cantidad"]."' ,
`inicio` = '".$_POST["inicio"]."' where
  cod_chequera = ".$_POST["codChequera"]."

");
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&&opt_subseccion=listaChequeraCuentaByBanco&cod=".$_POST["codBanco"]."&cod_cuenta=".$_POST["codCuenta"]);
exit;

}




$smarty->assign("datos_chequera",$comunes->ObtenerFilasBySqlSelect("select * from chequera where  cod_chequera = ".$_GET["cod_chequera"]));




?>