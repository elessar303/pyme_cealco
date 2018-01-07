<?php

include("../../libs/php/clases/usuarios.php");

$usuarios = new Usuarios();

$campos_comunes = $usuarios->ObtenerFilasBySqlSelect("SELECT descripcion, cod_unidad FROM unidades");

$arraySelectOption = array();
$arraySelectOutPut = array();
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectOutPut[] = $item["cod_unidad"];
}
$smarty->assign("option_values_departamentos", $arraySelectOption);
$smarty->assign("option_output_departamentos", $arraySelectOutPut);

$arrayCodModulo = array();
$arrayNomModulo = array();

$modulos = $usuarios->ObtenerFilasBySqlSelect("SELECT cod_modulo, nom_menu FROM modulos WHERE cod_modulo_padre IS NULL AND visible = 1 AND cod_modulo != 54 ORDER BY orden");
$smarty->assign("modulos", $modulos);

$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= ".$_GET["opt_seccion"]);
$smarty->assign("campo_seccion",$campos);

if (isset($_POST["aceptar"])) {

/////////////////////////////////////////////////////////////////////////
    /*
      //Departamento
      $valueSELECT = "";
      $outputSELECT =  "";
      $tprecio  = $usuarios->ObtenerFilasBySqlSelect("select * from centros");
      foreach($tprecio as $key => $item){
      $valueSELECT[] = $item["cod_centro"];
      $outputSELECT[] = $item["descripcion"];
      }
      $smarty->assign("option_values_centro",$valueSELECT);
      $smarty->assign("option_output_centro",$outputSELECT);
      $smarty->assign("option_selected_centro",$datacliente[0]["cod_centro"]);
     */
//////////////////////////////////////////////////////////////////////////
$cajero=0;
if(isset($_POST['cajero']))
  $cajero=1;

    $instruccion = "
    INSERT INTO usuarios (`usuario`, `nombreyapellido`, `clave`, `departamento`, vendedor)
    VALUES ('" . $_POST["usuario"] . "', '" . $_POST["nombreyapellido"] . "', '" . $_POST["clave1"] . "', '" . $_POST["cod_unidad"] . "', ".$cajero.");";

    $usuarios->Execute2($instruccion);

    $codNewUsuario = $usuarios->getInsertID();
    //////////////////////////////////////////////////////////////////////////
    $modulo = $_POST["valor_modulo"];
    foreach ($modulo as $valor_modulo){
        $usuarios->Execute2("INSERT INTO `modulo_usuario` (`cod_usuario`, `cod_modulo`) VALUES ({$codNewUsuario},  {$valor_modulo});");
    }
    //////////////////////////////////////////////////////////////////////////

    /*$instruccion = "INSERT INTO `modulo_usuario` (`cod_usuario`, `cod_modulo`)
    VALUES
    ( " . $codNewUsuario . ",  1),
    ( " . $codNewUsuario . ",  2),
    ( " . $codNewUsuario . ",  3),
    ( " . $codNewUsuario . ",  5),
    ( " . $codNewUsuario . ",  6),
    ( " . $codNewUsuario . ",  7);";

    $usuarios->Execute2($instruccion);*/

    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}
?>
