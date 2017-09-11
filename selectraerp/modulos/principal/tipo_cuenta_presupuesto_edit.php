<?php
include("../../libs/php/clases/usuarios.php");
$usuarios = new Usuarios();
if (isset($_GET["cod"])) 
{
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT * FROM tipo_cuenta WHERE id = ".$_GET['cod']);
    $smarty->assign("datos_usuarios", $campos);
}

if (isset($_POST["aceptar"]) && isset($_POST["id"]))
{
  $instruccion = "update tipo_cuenta set tipo_cuenta= '" . $_POST["tipo_cuenta"] . "' where id = " . $_POST["id"];
  $usuarios->Execute2($instruccion);
  echo 
  "
    <script type=\"text/javascript\">
    alert('Registro Editado');
    history.go(-2);
      </script>
  ";
  exit;
}
?>
