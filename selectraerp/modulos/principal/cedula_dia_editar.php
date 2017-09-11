<?php
session_start();
include('../../../menu_sistemas/lib/common.php');
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/comunes.php");
require_once("../../libs/php/clases/login.php");
$comunes = new Comunes();
//$comunes2 = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");         
$pos=POS;
$message = $_GET["cod"];
//echo "<script type='text/javascript'>alert('$message');</script>";
if (isset($_GET["cod"])) {
    $campos = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM $pos.days_id WHERE DAY = " . $_GET["cod"]);
    
    //echo "<script type='text/javascript'>alert('$campos');</script>";
    $smarty->assign("days_id", $campos);
}

if (isset($_POST["aceptar"])) {
    
    $instruccion = "update $pos.days_id set MIN = '" . $_POST["minimo"] . "', MAX = '" . $_POST["maximo"] . "' where DAY = " . $_POST["day"];    
    $comunes->Execute2($instruccion);
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
?>
