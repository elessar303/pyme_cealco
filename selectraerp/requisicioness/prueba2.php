<?


require_once '../lib/common.php';
$conexion=conexion();



$consulta = "SELECT descripcion,existencia FROM materiales WHERE cod_material = '".$_GET['cod']."'";
$resultado = query($consulta,$conexion);

$fetch = fetch_array($resultado);
$a = $fetch['existencia'];
$b = $_GET['cant'];
$total = $a - $b;

if ( $fetch['existencia'] < $_GET['cant'])
{
	
	//ript language="JavaScript" type="text/javascript">
	printf("NO TIENE ESTE MATERIAL EN EXISTENCIA");
	//</script>
	
}
elseif ( $fetch['existencia'] == $_GET['cant'])
{
	printf("SE QUEDARA SIN EXISTENCIA");
}
else
{
	echo "LE QUEDAN ".($fetch['existencia']- $_GET['cant'])." EN EXISTENCIA DE $fetch[descripcion] ";
}

?>


