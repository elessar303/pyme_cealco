<?php
$comunes = new Comunes();
$tabla = "vw_tesor_bancodet";
$name_form = "tesor_bancodet";
$tipob=@$_GET['tipo'];
$des=@$_GET['des'];
$pagina=@$_GET['pagina'];
$busqueda = @$_GET['busqueda'];


if(isset ($_POST["cod_movimiento_banArray"])){

$cod_movimientos = explode(",",$_POST["cod_movimiento_banArray"]);

list($dia,$mes,$anio) = explode("/",$_POST["fecha1_"]);
$fecha_inicial = $anio."-".$mes."-".$dia;

list($dia,$mes,$anio) = explode("/",$_POST["fecha2_"]);
$fecha_final = $anio."-".$mes."-".$dia;



$sql_verificar = "select * from conciliacion_bancaria where cod_tesor_bancodet ='".$_POST["cod_cuenta"]."'
 and fecha_inicial='".$fecha_inicial."' and fecha_final = '".$fecha_inicial."'
";


$campos = $comunes->ObtenerFilasBySqlSelect($sql_verificar);

if(count($campos)==0){

$sql = "
INSERT INTO `conciliacion_bancaria` (
    `fecha_inicial`,    
    `fecha_final`,
    `saldo_inicial`,
    `saldo_final`,
    `saldo_libros`,
    `mon_xcon_depo`,
    `mon_xcon_cheque`,
    `mon_xcon_nc`,
    `mon_xcon_nd`,
    `cant_tran_cheque_`,
    `cant_tran_depo_`,
    `cant_tran_nc_`,
    `cant_tran_nd_`,
    `mon_tran_cheque_`,
    `mon_tran_depo_`,
    `mon_tran_nc_`,
    `mon_tran_nd_`,
    `cant_xcon_cheque`,
    `cant_xcon_depo`,
    `cant_xcon_nc`,
    `cant_xcon_nd`,
    `monto_total`,
    `cod_tesor_bancodet`,
    `usuario`,
    `fecha_realizado`,
    `estado`

    )
    VALUES(
                '".$fecha_inicial."',
                '".$fecha_final."',
                '".$_POST["monto_total"]."',
                '".$_POST["monto_total"]."',
                '".$_POST["saldo_libros"]."',
                '".$_POST["mon_xcon_depo"]."',
                '".$_POST["mon_xcon_cheque"]."',
                '".$_POST["mon_xcon_nc"]."',
                '".$_POST["mon_xcon_nd"]."',
                '".$_POST["cant_tran_cheque_"]."',
                '".$_POST["cant_tran_depo_"]."',
                '".$_POST["cant_tran_nc_"]."',
                '".$_POST["cant_tran_nd_"]."',
                '".$_POST["mon_tran_cheque_"]."',
                '".$_POST["mon_tran_depo_"]."', 
                '".$_POST["mon_tran_nc_"]."',
                '".$_POST["mon_tran_nd_"]."',
                '".$_POST["cant_xcon_cheque"]."',
                '".$_POST["cant_xcon_depo"]."',
                '".$_POST["cant_xcon_nc"]."',
                '".$_POST["cant_xcon_nd"]."',
                '".$_POST["monto_total"]."',
                '".$_POST["cod_cuenta"]."',
                '".$login->getUsuario()."',
                '".date("Y-m-d")."',
                'Emitida'
    );
";
$comunes->Execute2($sql);
$codigo = $comunes->getInsertID();
foreach($cod_movimientos as $codigo_movimiento){
    $sql2 = "
            update movimientos_bancarios
            set
             cod_conciliacion = '".$codigo."',
             estado='Conciliado'
             where cod_movimiento_ban = '".$codigo_movimiento."'
        ";
        $comunes->Execute2($sql2);
}
    echo json_encode(array(
       "success" => true,
       "msg"     => "Conciliación Registrada exitosamente. N° ".$codigo
    ));

}else{
    echo json_encode(array(
       "success" => false,
       "msg"     => "Disculpe, el periodo.".$_POST["fecha1_"]." / ".$_POST["fecha2_"]." ya fue conciliado."
    ));
}

    exit;
}


if(isset($_GET["cod"])){
    $campos = $comunes->ObtenerFilasBySqlSelect("select *
                from banco  WHERE cod_banco = ".$_GET["cod"]);
$smarty->assign("datos_banco",$campos);
}



if(isset($_POST['buscar']) || $tipob!=NULL){
	if(!$tipob){
		$tipob=$_POST['palabra'];
		$des=$_POST['buscar'];
		$busqueda = $_POST['busqueda'];
	}

	switch($tipob){
		case "exacta":
			$instruccion=$comunes->buscar_exacta($tabla,$des,$busqueda);
			break;
		case "todas":
			$instruccion=$comunes->buscar_todas($tabla,$des,$busqueda);
			break;
		case "cualquiera":
			$instruccion=$comunes->buscar_cualquiera($tabla,$des,$busqueda);
			break;
	}
        $instruccion .= " and cod_banco = ".$_GET["cod"];

}else{
    $instruccion = "SELECT * FROM  ".$tabla." where cod_banco = ".@$_GET["cod"];

}


list($dia1, $mes1,$anio1) = explode("/",$_POST["fecha1_"]);
list($dia2, $mes2,$anio2) = explode("/",$_POST["fecha2_"]);
$fecha1 = $anio1."-".$mes1."-".$dia1;
$fecha2 = $anio2."-".$mes2."-".$dia2;





$sql_verificar = "select * from conciliacion_bancaria where cod_tesor_bancodet ='".$_GET["cod_cuenta"]."'
 and fecha_inicial='".$fecha1."' and fecha_final = '".$fecha2."'
";

$campos_verificarBanco = $comunes->ObtenerFilasBySqlSelect($sql_verificar);



$SQLdata_transito = "SELECT
cod_tipo_movimientos_ban,
descripcion,
(
SELECT ifnull(sum(monto),0)
 FROM `movimientos_bancarios`
 where  cod_tesor_bancodet = '".$_GET["cod_cuenta"]."' and
    tipo_movimiento = tipo_movimientos_ban.cod_tipo_movimientos_ban
 and `fecha_movimiento` <=  '".$fecha2."' and estado is null
) as monto,
(
SELECT count(*)
 FROM `movimientos_bancarios`
 where  cod_tesor_bancodet = '".$_GET["cod_cuenta"]."' and
    tipo_movimiento = tipo_movimientos_ban.cod_tipo_movimientos_ban
 and `fecha_movimiento` <= '".$fecha2."' and estado is null
) as cantidad
 FROM tipo_movimientos_ban";

$data_transito = $comunes->ObtenerFilasBySqlSelect($SQLdata_transito);


$smarty->assign("data_transito",$data_transito);


$num_paginas=$comunes->obtener_num_paginas($instruccion);
$pagina=$comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos=$comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros",$campos);
$smarty->assign("cabecera",array("Codigo","Descripción","Nro. Cuenta","Tipo",""));
$smarty->assign("limitePaginacion",$comunes->LimitePaginaciones);
$smarty->assign("num_paginas",$num_paginas);
$smarty->assign("pagina",$pagina);


$smarty->assign("busqueda",$busqueda);
$smarty->assign("des",$des);
$smarty->assign("tipo",$tipob);
$smarty->assign("cantidadFilas",$comunes->getFilas());

$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= ".$_GET["opt_seccion"]);
$smarty->assign("campo_seccion",$campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("cod_tesor_bandodet","descripcion","nro_cuenta","tipo_cuenta"));
$smarty->assign("option_output", array("Codigo","Descripción","Nro. Cuenta","Tipo"));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//**************************************************************************
//**************************************************************************

//**************************************************************************
//Nombre del Formulario****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************


$smarty->assign("verificarCuentaBanco",count($campos_verificarBanco));

$smarty->assign("mensaje",$comunes->Notificacion());


?>
