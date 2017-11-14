<?php
include("../../libs/php/clases/vendedor.php");
$vendedor = new Vendedor();
$comunes = new Comunes();
$punto = $comunes->ObtenerFilasBySqlSelect("SELECT nombre_estado as nombre, codigo_estado as id  from ".DB_SELECTRA_PYMEPP.".estados");
$arraySelectOption="";
$arraySelectOutPut1="";
foreach ($punto as $key => $puntos) 
{
    $arraySelectOption[] = $puntos["id"];
    $arraySelectOutPut1[] = utf8_encode($puntos["nombre"]);
}

$smarty->assign("option_values_estado", $arraySelectOption);
$smarty->assign("option_output_estado", $arraySelectOutPut1);

$conductores = $comunes->ObtenerFilasBySqlSelect("SELECT *  from transporte_conductores order by nombres");
$arraySelectOption2="";
$arraySelectOutPut2="";

foreach ($conductores as $key => $conductor) 
{
    $arraySelectOption2[] = $conductor["id"];
    $arraySelectOutPut2[] = utf8_encode($conductor["cedula"]." - ".$conductor["nombres"]." ".$conductor["apellidos"]);
}

$smarty->assign("option_values_conductores", $arraySelectOption2);
$smarty->assign("option_output_conductores", $arraySelectOutPut2);

if($_POST['posee_vehiculo']!=1)
{
	$_POST['vehiculos']=NULL;
}
if(isset($_POST["aceptar"])){

	$instruccion = "UPDATE `tickets_entrada_salida`	SET `hora_salida`='".$_POST["fecha_salida"]."', `peso_salida`='".$_POST["peso_salida"]."' WHERE id='".$_POST["id_ticket"]."';";

	if ($comunes->Execute2($instruccion)) {
		$id_ticket = $comunes->getInsertID();
        Msg::setMessage("<span style=\"color:#62875f;\">Ticket  Nro. " . $_POST["id_ticket"] . " Actualizado Exitosamente</span>");
    }else{
        Msg::setMessage("<span style=\"color:red;\">Error al Actualizar el Ticket.</span>");
    }
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
	exit();
}


if (isset($_GET["cod"])) {
	$sql="select * from tickets_entrada_salida where id = " . $_GET["cod"]."";
    $campos = $comunes->ObtenerFilasBySqlSelect($sql);
    $smarty->assign("datos_ticket", $campos);
}


$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= ".$_GET["opt_seccion"]);
$smarty->assign("campo_seccion",$campos);
?>
