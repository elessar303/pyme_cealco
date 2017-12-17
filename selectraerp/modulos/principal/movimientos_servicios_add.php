<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
$login = new Login();
if(isset($_GET['idmovimiento']) || isset($_POST['idmovimiento']))
{
	$id=isset($_GET['idmovimiento']) ? $_GET['idmovimiento'] : $_POST['idmovimiento'];
}
else
{
	$id=null;
}

$smarty->assign("idmovimiento", $id);

if(isset($_POST["aceptar"]))
{
	if( (!isset($_POST['idmovimiento']) || $_POST['idmovimiento']==null) || (!isset($_POST['filtro_codigo']) || $_POST['filtro_codigo']==null) )
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
	
	$sql="select id from movimiento_almacen_servicio where id_movimiento_almacen='".$_POST['idmovimiento']."' and id_servicio='".$obtenerid[0]['id_item']."'";
	$unico=$almacen->ObtenerFilasBySqlSelect($sql);
    if($unico!=null)
	{
		echo 
		"	<script type='text/javascript'>
				alert('Error, No se puede agregar dos veces el mismo servicio al mismo movimiento');
				history.back(-1);
			</script>
			";
		exit();
	}
	
	$instruccion = 
	"
		INSERT INTO `movimiento_almacen_servicio` (
		`id_movimiento_almacen`,
		`id_servicio`,
		`estatus`,
		`usuario_creacion`
		)
		VALUES 
		(
			{$_POST['idmovimiento']}, {$obtenerid[0]['id_item']}, '1', {$login->getIdUsuario()}
		);
	";
	
	$almacen->Execute2($instruccion);
	if(isset($_GET["loc"]))
	{
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=movimientoservicio&idmovimiento=".$id);
	}
	else
	{
		header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]."&opt_subseccion=movimientoservicio&idmovimiento=".$id);
	}

}



?>
