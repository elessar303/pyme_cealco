<?php

require_once("../../config.ini.php");
include("../../libs/php/clases/banco.php");
$banco = new Banco();




$campos = $banco->ObtenerFilasBySqlSelect("select Abreviatura from divisas where id_divisa=".$_GET['miId']);


echo "


var aux = document.getElementById('moneda');
aux.value='".$campos[0]['Abreviatura']."';


";


?>
