<?php
include("../../libs/php/clases/clientes.php");
include("../../libs/php/clases/correlativos.php");
include("../../../menu_sistemas/lib/common.php") ;
$clientes = new Clientes();

if(isset($_POST["aceptar"])){ // si el usuario iso post




$clientes->BeginTrans();


$login = new Login();

$codigo_siga=$clientes->ObtenerFilasBySqlSelect("select codigo_siga from parametros_generales");
$instruccion = "INSERT INTO `calidad_visitas` 
	(`id`,
	`tipo_visita`, 
	`usuario`, 
	`almacen_visita`, 
	`ubicacion_visita`, 
	`fecha_visita`
	)
    VALUES 
    (
	NULL,
    '".$_POST['tipo_visita']."',
    '{$login->getIdUsuario()}',
    '".$_POST['estado']."' ,
    '".$_POST['puntodeventa']."',
    '".$_POST['fecha_visita']."'
    );";

$clientes->ExecuteTrans($instruccion);

$id_transaccion = $clientes->getInsertID();
    if(isset($_POST['observacion']))
    {
        $total=count($_POST['observacion']);
        $observaciones=$_POST['observacion'];
        $recomendacion=$_POST['recomendacion'];
        $i=0;
        while($i<$total)
        {
        $clientes->ExecuteTrans("insert into visita_observaciones (cod_visita, observacion, recomendacion) values (".$id_transaccion.", '".$observaciones[$i]."', '".$recomendacion[$i]."')");
        $i++;
        }
    }

$cod_calidad="update calidad_visitas set cod_acta_visita='CV-".$codigo_siga[0]['codigo_siga']."-{$id_transaccion}' where id={$id_transaccion}";
$clientes->ExecuteTrans($cod_calidad);



if($clientes->errorTransaccion==1){
    $nombre=$clientes->ObtenerFilasBySqlSelect("Select nombreyapellido from usuarios where cod_usuario='{$login->getIdUsuario()}'");
    Msg::setMessage("<span style=\"color:#62875f;\"><img src=\"../../libs/imagenes/ico_ok.gif\"> La Visita De  ".$nombre[0]["nombreyapellido"]." fue creado exitosamente.</span>");

    }
if($clientes->errorTransaccion==0){Msg::setMessage("<span style=\"color:red;\">Error contacte al administrador.</span>");}
$clientes->CommitTrans($clientes->errorTransaccion);


header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}else{



$ubica=$clientes->ObtenerFilasBySqlSelect("select * from almacen");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["descripcion"];
    $arrayubi[] = $item["cod_almacen"];
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

// punto de ventas
$arraySelectOption = "";
$arraySelectoutPut1 = "";

$arraySelectOption2 = "";
$arraySelectoutPut2 = "";
$tipo_visita=$clientes->ObtenerFilasBySqlSelect("select id, descripcion_visita as nombre from tipo_visitas");

foreach ($tipo_visita as $key => $value) {
	$arraySelectOption2[] = $value["id"];
    $arraySelectOutPut2[] = $value["nombre"];
}

$smarty->assign("option_values_tipo", $arraySelectOption2);
$smarty->assign("option_output_tipo2", $arraySelectOutPut2);
$login = new Login();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());




//CONSULTA DE ID FISCAL EN PARAMETROS
$valueSELECT = "";
$outputSELECT =  "";
$data_parametros  = $clientes->ObtenerFilasBySqlSelect("select * from parametros_generales");
foreach($data_parametros as $key => $item){
    $valueSELECT[] = $item["cod_empresa"];
    $outputidfiscalSELECT[] = $item["id_fiscal"];
    $outputidfiscal2SELECT[] = $item["id_fiscal2"];
}
$smarty->assign("option_values_parametros",$valueSELECT);
$smarty->assign("option_output_idfiscal",$outputidfiscalSELECT);
$smarty->assign("option_output_idfiscal2",$outputidfiscal2SELECT);


// CONSULTA DE CUENTAS CONTABLES
$global=new bd(SELECTRA_CONF_PYME);
$sentencia="select * from nomempresa where bd='".$_SESSION['EmpresaFacturacion']."'";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();

$valueSELECT = "";
$outputSELECT =  "";
$contabilidad = $clientes->ObtenerFilasBySqlSelect("select * from ".$fila['bd_contabilidad'].".cwconcue where Tipo='P'");
foreach($contabilidad as $key => $cuenta){
    $valueSELECT[] = $cuenta["Cuenta"];
    $outputSELECT[] = $cuenta["Cuenta"]." - ".$cuenta["Descrip"];
}
$smarty->assign("option_values_cuenta",$valueSELECT);
$smarty->assign("option_output_cuenta",$outputSELECT);


//select de distrito
$valueSELECT = "";
$outputSELECT =  "";
$valueSELECT[] = "";
$outputSELECT[] = "Seleccione...";
$distrito  = $clientes->ObtenerFilasBySqlSelect("select * from distrito_escolar");
foreach($distrito as $key => $item){
    $valueSELECT[] = $item["id"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_distrito",$valueSELECT);
$smarty->assign("option_output_distrito",$outputSELECT);

}

?>
