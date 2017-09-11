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
INSERT INTO `chequera` (
`cod_chequera` ,
`cantidad` ,
`inicio` ,
`situacion` ,
`cod_tesor_bandodet` ,
`fecha_creacion` ,
`usuario_creacion`
)
VALUES (
NULL , '".$_POST["cantidad"]."', '".$_POST["inicio"]."', 'D', '".$_POST["codCuenta"]."',
CURRENT_TIMESTAMP , '".$login->getUsuario()."'
);
");
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&&opt_subseccion=listaChequeraCuentaByBanco&cod=".$_POST["codBanco"]."&cod_cuenta=".$_POST["codCuenta"]);
exit;

}




?>