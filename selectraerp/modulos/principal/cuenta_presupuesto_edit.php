<?php
include("../../libs/php/clases/usuarios.php");
$usuarios = new Usuarios();
if (isset($_GET["cod"])) 
{
    $campos = $usuarios->ObtenerFilasBySqlSelect("SELECT * FROM cuenta_presupuestaria WHERE id = ".$_GET['cod']);
    $smarty->assign("datos_usuarios", $campos);

    $campos_comunes = $usuarios->ObtenerFilasBySqlSelect("SELECT * FROM tipo_cuenta" );
    foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = $item["tipo_cuenta"];
}
$smarty->assign("option_values_tipo_cuenta", $arraySelectOption);
$smarty->assign("option_output_tipo_cuenta", $arraySelectoutPut);
$smarty->assign("option_selected_tipo_cuenta", $campos[0]["tipo"]);
}

if (isset($_POST["aceptar"]) && isset($_POST["id"]))
{
  $instruccion = "update cuenta_presupuestaria set cuenta= '" . $_POST["cuenta"] . "', tipo= '" . $_POST["tipo"] . "' where id = " . $_POST["id"];
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
