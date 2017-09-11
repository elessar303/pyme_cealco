<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();


if ($_GET['mensaje']=='true')
{
$mensaje = "Su configuración de moneda requiere que ingrese la tasa de cambio para el día de hoy. ";
$fechaActual = date('d/m/Y');
}

$moneda_base = $banco->ObtenerFilasBySqlSelect("select id_divisa, Nombre, Abreviatura,moneda_base from parametros_generales,divisas where id_divisa = moneda_base ");
$aux = $parametrosgenerales->ObtenerFilasBySqlSelect("select id_divisa, Nombre, Abreviatura from moneda,divisas where id_divisa = moneda_actual ");


if(isset($_POST["aceptar"])){



$instruccion = "
INSERT INTO tasas_cambio (
divisa , 
fecha ,
tasa,
monedabase
)
VALUES ('".$aux[0]["id_divisa"]."',  str_to_date('".$_POST["fecha"]."','%d/%m/%Y'), '".$_POST["tasa"]."','".$moneda_base[0]['moneda_base']."' );
";
$banco->Execute2($instruccion);

header("Location: ?");
exit;
}


$mensajeDetalle= "Especifique la tasa de cambio de 1 ".$aux[0]['Abreviatura']."  (".$aux[0]['Nombre'].") en su moneda Base (".$moneda_base[0]['Abreviatura'].") ";

$smarty->assign("mensajeDetalle",$mensajeDetalle);
$smarty->assign("mensaje",$mensaje);
$smarty->assign("fechaActual",$fechaActual);


?>
