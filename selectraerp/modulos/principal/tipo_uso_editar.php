<?php

include("../../libs/php/clases/usuarios.php");
$usuarios = new Usuarios();


if (isset($_GET["cod"])) {
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT * FROM tipo_uso WHERE id = ".$_GET['cod']);
    $smarty->assign("datos_usuarios", $campos);
}





if (isset($_POST["aceptar"])) {
    $instruccion = "update tipo_uso set tipo= '" . $_POST["tipo_uso"] . "' where id = " . $_POST["id"];
    $usuarios->Execute2($instruccion);

    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
      echo "<script type=\"text/javascript\">
           history.go(-2);
       </script>";
        exit;
}
?>
