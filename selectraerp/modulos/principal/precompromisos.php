<?php
 
require_once("../../config.ini.php");
include("../../libs/php/clases/compra.php");
$compra = new Compra();
$comunes = new ConexionComun();
if ($_GET["generar"] == "si") {
    
    $compra->Execute2("TRUNCATE TABLE  item_precompromiso");
     
    header("Location: index.php?opt_menu=106");
}
?>
