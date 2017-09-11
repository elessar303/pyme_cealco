<?php

include("../../libs/php/clases/usuarios.php");
$usuarios = new Usuarios();


if (isset($_GET["cod"])) {
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT * FROM tipo_visitas WHERE id = ".$_GET['cod']);
    $smarty->assign("datos_usuarios", $campos);
}





if (isset($_POST["aceptar"])) {
    $instruccion = "update tipo_visitas set descripcion_visita= '" . $_POST["tipo_visita"] . "' where id = " . $_POST["id"];
    $usuarios->Execute2($instruccion);

    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
      echo "<script type=\"text/javascript\">
           history.go(-2);
       </script>";
        exit;
}
?>
