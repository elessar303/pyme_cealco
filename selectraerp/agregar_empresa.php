<html>
<head>
<title>:: Selectra ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="estilos.css" rel="stylesheet" type="text/css" />
</head>
<body class="fondo">

<?php
include("lib/commone.php") ;
$baseDatosAdm =$_GET['baseDatosAdm'];
$baseDatosCon =$_GET['baseDatosCon'];
$baseDatosNom =$_GET['baseDatosNom'];
$nombre =$_GET['nombre'];

$conexion=new bd(SELECTRA_CONF_PYME);
$query ="insert into nomempresa (nombre,bd,bd_contabilidad,bd_nomina) values ('".$nombre."','".$baseDatosAdm."','".$baseDatosCon."','".$baseDatosNom."') ";
$resultado= $conexion->query($query);

$archivo=file("administracion.sql", FILE_SKIP_EMPTY_LINES);

if (isset($baseDatosAdm))
{

    $conexion->create_database($baseDatosAdm);
    $conexion2=new bd($baseDatosAdm);
    foreach($archivo as $lineas){
	//$temp=split(" ",$lineas);
	$consulta.=$lineas;
	if(substr($lineas, -2, 1)==";"){
		$conexion2->query($consulta);
		$consulta="";
	}
    }
}

$archivo=file("selectrafacturacionpymesfunciones.sql", FILE_SKIP_EMPTY_LINES);

if (isset($baseDatosAdm))
{
    $conexion3=new bd($baseDatosAdm);
    foreach($archivo as $lineas){
	//$temp=split(" ",$lineas);
	$consulta.=$lineas;
	if(substr($lineas, -2, 1)=="$"){
		$conexion3->query(str_replace('$','', $consulta));
		$consulta="";
	}
    }
}

$conexion2->query("update parametros_generales set nombre_empresa='".$nombre."',direccion='".$nombre."' where cod_empresa= 1  ");

$archivo=file("contabilidad.sql", FILE_SKIP_EMPTY_LINES);

if (isset($baseDatosCon))
{
    $conexion->create_database($baseDatosCon);
    $conexion5=new bd($baseDatosCon);
    foreach($archivo as $lineas){
	//$temp=split(" ",$lineas);
	$consulta.=$lineas;
	if(substr($lineas, -2, 1)==";"){
		$conexion5->query($consulta);
		$consulta="";
	}
    }
}

$conexion5->query("update cwconemp set Nomemp='".$nombre."'");

$archivo=file("nomina.sql", FILE_SKIP_EMPTY_LINES);


if (isset($baseDatosNom))
{

$conexion->create_database($baseDatosNom);
$conexion7=new bd($baseDatosNom);

foreach($archivo as $lineas){

	//$temp=split(" ",$lineas);
	$consulta.=$lineas;
	if(substr($lineas, -2, 1)==";"){

		$conexion7->query($consulta);

		$consulta="";
	}
}


}

//$conexion7->query("update cwconemp set Nomemp='".$nombre."'   ");

$empresas=new bd(SELECTRA_CONF_PYME);
$query ="select * from nomempresa ";

$resultado= $empresas->query($query);

$listado= '
<select class="seleccionEmpresa" multiple="multiple" name="empresaSeleccionada">


';


while ($fila = $resultado->fetch_assoc())
{

$listado.= '<option value="'.$fila['codigo'].'">'.$fila['nombre'].'</option>';

}


$listado.=
'
</select> ';

$listado = urlencode($listado);


?>



<script>


var aux = parent.document.getElementById('listado');
//aux.innerHTML = parent.urldecode('<? echo $listado ?>');
parent.ocultarProcesando();
alert('Empresa <? echo $nombre; ?> creada.' );
parent.location="../selectraerp";

</script>

</BODY>
</html>
