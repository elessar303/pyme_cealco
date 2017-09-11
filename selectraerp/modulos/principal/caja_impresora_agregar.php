<?php
include("../../../generalp.config.inc.php");
include("../../libs/php/clases/usuarios.php");

$usuarios = new Usuarios();
/*
$campos_comunes = $usuarios->ObtenerFilasBySqlSelect("SELECT serial_impresora, caja_host FROM caja_impresora");

$arraySelectOption = array();
$arraySelectOutPut = array();
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["serial_impresora"];
    $arraySelectOutPut[] = $item["caja_host"];
}

$smarty->assign("option_values_departamentos", $arraySelectOption);
$smarty->assign("option_output_departamentos", $arraySelectOutPut);
*/
$arrayCodModulo = array();
$arrayNomModulo = array();

$modulos = $usuarios->ObtenerFilasBySqlSelect("SELECT cod_modulo, nom_menu FROM modulos WHERE cod_modulo_padre IS NULL AND visible = 1 AND cod_modulo != 54 ORDER BY orden");
//echo "Select distinct(host) from ".POS.".closedcash where host not in (select caja_host from pyme_caja_impresora)"; exit();
$cajas= $usuarios->ObtenerFilasBySqlSelect("Select distinct(host) from ".POS.".closedcash where host not in (select caja_host from caja_impresora)");
//print_r($cajas); exit();
$smarty->assign("modulos", $modulos);
$tipo_venta= $usuarios->ObtenerFilasBySqlSelect("Select venta_pyme from parametros_generales");
$tipo_venta=$tipo_venta[0]["venta_pyme"];

$smarty->assign("tipo_venta", $tipo_venta);
foreach ($cajas as $key => $item) {
    $arraySelectOption[] = $item["host"];
    $arraySelectOutPut[] = $item["host"];
}
$smarty->assign("option_values_cajas", $arraySelectOption);
$smarty->assign("option_output_cajas", $arraySelectOutPut);

$arraySelectOption_tipo=array(0,1);
$arraySelectOutPut_tipo=array('PYME','POS');
//$arraySelectOutPut_tipo[1]=['POS'];

$smarty->assign("option_values_cajas_tipo", $arraySelectOption_tipo);
$smarty->assign("option_output_cajas_tipo", $arraySelectOutPut_tipo);

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

    $instruccion = "
    INSERT INTO caja_impresora (caja_host, serial_impresora, ip, caja_tipo)
    VALUES ('" . $_POST["nombre_caja"]. "', '" . $_POST["serial"] . "','" . $_POST["ip"] . "','" . $_POST["tipo_caja"] . "');";

    $usuarios->Execute2($instruccion);


    $pyme=$usuarios->ObtenerFilasBySqlSelect("select venta_pyme from parametros_generales");
if($pyme[0]['venta_pyme']==1){
  $instruccion = "
    INSERT INTO cierre_caja_control (cajas, secuencia, estatus_cierre) (SELECT host, max(hostsequence)-1, 1 from  ".POS.".closedcash where host='".$_POST["nombre_caja"]."');";    
    $usuarios->Execute2($instruccion);

$sql="SELECT numero_z+1 as numero_z FROM libro_ventas WHERE serial_impresora='".$_POST["serial"]."' order by id desc limit 1";

$nro_z_insert= $usuarios->ObtenerFilasBySqlSelect($sql);

$filas_nro_z=$usuarios->getFilas($nro_z_insert);


if($filas_nro_z==0){
$z=0;
}else{
$z=$nro_z_insert[0]['numero_z'];
}

}else{

$instruccion = "
    INSERT INTO `closedcash_pyme`(nombre_caja, serial_caja, money, fecha_inicio, fecha_fin, secuencia) VALUES ('".$_POST['nombre_caja']."', '".$_POST['serial']."', '".$_POST['serial'].$_POST['ip'].date('Y-m-d_H:i:s')."',  now(), null, 1)";
    $usuarios->Execute2($instruccion);
    $instruccion = "
    INSERT INTO cierre_caja_control_pyme (serial_cajas, nombre_cajas, secuencia, estatus_cierre) (SELECT  serial_caja, nombre_caja, max(secuencia)-1, 1 from  closedcash_pyme where nombre_caja='".$_POST["nombre_caja"]."' and serial_caja='".$_POST['serial']."');";    
    $usuarios->Execute2($instruccion);


}


    //////////////////////////////////////////////////////////////////////////
    
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

       echo "<script type=\"text/javascript\">
           history.go(-1);
       </script>";
        exit;
}
?>
