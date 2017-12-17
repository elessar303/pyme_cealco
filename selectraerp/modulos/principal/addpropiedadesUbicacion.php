<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
$login = new Login();
if(isset($_GET['id_ubicacion']) || isset($_POST['id_ubicacion']))
{
	$id=isset($_GET['id_ubicacion']) ? $_GET['id_ubicacion'] : $_POST['id_ubicacion'];
}
else
{
	$id=null;
}
if(isset($_GET['cod']) || isset($_POST['cod']))
{
	$cod=isset($_GET['cod']) ? $_GET['cod'] : $_POST['cod'];
}
else
{
	$cod=null;
}

$smarty->assign("id_ubicacion", $id);
$smarty->assign("cod", $cod);
if(isset($_POST["aceptar"]))
{
	if( (!isset($_POST['id_ubicacion']) || $_POST['id_ubicacion']==null) || (!isset($_POST['filtro_codigo']) || $_POST['filtro_codigo']==null) )
	{
		echo 
		"	<script type='text/javascript'>
				alert('Error Faltan Datos');
				history.back(-1);
			</script>
			";
		exit();
	}
	$sql="select id_item from item where codigo_barras='".$_POST['filtro_codigo']."' limit 1";
	$obtenerid=$almacen->ObtenerFilasBySqlSelect($sql);
	$sql="select id from ubicacion_servicio where id_ubicacion='".$_POST['id_ubicacion']."' and id_servicio='".$obtenerid[0]['id_item']."'";
	$unico=$almacen->ObtenerFilasBySqlSelect($sql);
    if($unico!=null)
	{
		echo 
		"	<script type='text/javascript'>
				alert('Error, No se puede agregar dos veces el mismo servicio a la misma ubicacion');
				history.back(-1);
			</script>
			";
		exit();
	}
	
	$instruccion = 
	"
		INSERT INTO `ubicacion_servicio` (
		`id_ubicacion`,
		`id_servicio`,
		`estatus`,
		`usuario_creacion`
		)
		VALUES 
		(
			{$_POST['id_ubicacion']}, {$obtenerid[0]['id_item']}, '1', {$login->getIdUsuario()}
		);
	";
	
	$almacen->Execute2($instruccion);
	if(isset($_GET["loc"]))
	{
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=propiedadesUbicacion&cod=".$cod."&id=".$id);
	}
	else
	{
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=propiedadesUbicacion&cod=".$cod."&id=".$id);
	}

}



?>
