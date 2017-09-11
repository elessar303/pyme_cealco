<?php
include("../../libs/php/clases/banco.php");
$banco = new Banco();



$campos = $banco->ObtenerFilasBySqlSelect("select * from moneda,divisas where id_divisa = moneda_actual ");
$campos2 = $banco->ObtenerFilasBySqlSelect("select id_divisa, Nombre, Abreviatura from parametros_generales,divisas where id_divisa = moneda_base ");
$campos3 = $banco->ObtenerFilasBySqlSelect("select cambio_unico from moneda");

if ($campos[0]['cambio_unico']=='1')
$seleccionCambio= "Si <input  type='radio'  name='cambio' checked value='1' onclick='mostrarTasa(1)'>  No <input  type='radio'  name='cambio' value='0' onclick='mostrarTasa(0)'> ";
else
$seleccionCambio= "Si <input  type='radio'  name='cambio'  value='1'  onclick='mostrarTasa(1)'>  No <input  type='radio'  name='cambio' checked value='0' onclick='mostrarTasa(0)'> ";






if(isset($_POST["guardarMoneda"])){


$instruccion = "UPDATE moneda set `moneda_actual` = '".$_POST["divisa"]."' ,`cambio_unico` = '".$_POST["cambio"]."' " ;
$banco->Execute2($instruccion);
header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
exit;
}



if ($campos3[0]['cambio_unico']!=1)
{
$javascript1= " <script>  document.valor='".$campos[0]['Cambio_unico']." ".$campos[0]['Abreviatura']."' </script>"  ;
$campos[0]['Cambio_unico'] = '<a href=\'?opt_menu=1&opt_seccion=105\' style=\'color:blue\'>Ver Matriz Tasas de Cambio </a> ';

}

if(isset($_GET["cod"])){
$campos = $banco->ObtenerFilasBySqlSelect("select * from moneda  ");
$smarty->assign("datos_banco",$campos);
}


$monedaActual = "
<option value='".$campos[0]['id_divisa']."'> ".$campos[0]['Nombre']."
  
</option>";


$smarty->assign("monedaActual",$monedaActual);
$smarty->assign("javascript1",$javascript1);
$smarty->assign("datos_moneda",$campos);
$smarty->assign("datos_moneda_base",$campos2);
$smarty->assign("seccionCambio",$seleccionCambio);

$campos = $banco->ObtenerFilasBySqlSelect("select * from divisas");
$smarty->assign("divisas",$campos);





?>
