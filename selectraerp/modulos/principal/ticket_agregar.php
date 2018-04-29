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
if(isset($_POST["aceptar"]))
{
	if($_POST["tipo_ticket"]!=1)
	{
		$_POST["placa"]=NULL;
	}
	$instruccion = "
	INSERT INTO 
	`tickets_entrada_salida`
	(`id_conductor`, `hora_entrada`, `peso_entrada`, `tipo_ticket`, `placa`)
	VALUES
	(
	'".$_POST["conductores"]."',
	'".$_POST["fecha_entrada"]."',
	'".$_POST["peso_entrada"]."',
	'".$_POST["tipo_ticket"]."',
	'".$_POST["placa"]."'
	);";

	if ($comunes->Execute2($instruccion)) 
	{
		$id_ticket = $comunes->getInsertID();
        Msg::setMessage("<span style=\"color:#62875f;\">Ticket Generado Exitosamente con en Nro. " . $id_ticket . "</span>");
    }
    else
    {
        Msg::setMessage("<span style=\"color:red;\">Error al Registrar el Ticket.</span>");
    }
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
	exit();
}

$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= ".$_GET["opt_seccion"]);
$smarty->assign("campo_seccion",$campos);
?>
