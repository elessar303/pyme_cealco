<?php
ini_set("memory_limit","1024M");
ini_set("upload_max_filesize","2000M");

session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
include("../../../generalp.config.inc.php");
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/comunes.php");

$comunes = new ConexionComun();
$correlativos = new Correlativos();
$comun = new Comunes();
$login = new Login();

$select_grupo="SELECT * FROM grupo group by descripcion";

$grupos = $comunes->ObtenerFilasBySqlSelect($select_grupo);

foreach ($grupos as $key => $value) {

$idpos=$comun->codigo_pos($value["descripcion"]);
$instruccion = "
INSERT INTO grupo_punto (`cod_grupo`,
`descripcion`,
`id_rubro`,
`restringido`,
`cantidad_rest`,
`dias_rest`,
grupopos
)
VALUES (".$value["cod_grupo"].", 
 '".$value["descripcion"]."','".$value["id_rubro"]."' ,'".$value["restringido"]."','".$value["cantidad_rest"]."','".$value["dias_rest"]."', '$idpos'
);
";
$comunes->Execute2($instruccion);

	if($value["restringido_grupo"]==1) 
	{
		$instruccion = "INSERT INTO categories_punto (ID, NAME, QUANTITYMAX, TIMEFORTRY) VALUES ('$idpos','".$value["descripcion"]."','".$value["cantidad_rest"]."','".$value["dias_rest"]."')";
	}
	else
	{
		$instruccion = "INSERT INTO $pos.categories_punto (ID, NAME) VALUES ('$idpos','".$value["descripcion"]."')";
	}
	$comunes->Execute2($instruccion);

}

?>