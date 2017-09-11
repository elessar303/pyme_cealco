<?php
include("../../libs/php/clases/correlativos.php");

$comunes = new Comunes();
$formateo_nro_cheque = new Correlativos();

$nrochequera = @$_GET["cod_chequera"];


$campos = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM `chequera` where cod_chequera = ".$_GET["cod_chequera"]);

$verificarCreacionCheques = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM `cheque` where cod_chequera = ".$_GET["cod_chequera"]);

/**
 * verificamos que  count($verificarCreacionCheques)==0,
 * si es 0: esto indica que es posible generar los cheques.
 * de lo contrario: no realizar accion.
 */
if(count($verificarCreacionCheques)==0){
    if(count($campos)>0){

        $cantidadCheques = $campos[0]["cantidad"];
        $nroPrimerCheque = $campos[0]["inicio"];
	$inicio=$nroPrimerCheque;
        for($i=0;$i<$cantidadCheques;$i++){
		
		$nro_chequeString = $formateo_nro_cheque->FormatCorrelativo("000000000", $inicio);
		$insertarCheque = "

		INSERT INTO `cheque` (
		`nro_cheque` ,
		`cod_chequera` ,
		`cheque` ,
		`situacion` ,
		`fecha` ,
		`fecha_creacion` ,
		`usuario_creacion`
		)
		VALUES (
		 '".$nro_chequeString."',  '".$nrochequera."' , '".$inicio."', 'D', '0000-00-00',
		CURRENT_TIMESTAMP , '".$login->getUsuario()."'
		);
		";

		$inicio+=1;
		$comunes->Execute2($insertarCheque);
            



        }//for($i=$nroPrimerCheque;$i<=($nroPrimerCheque+$cantidadCheques);$i++){


   

    }//if(count($campos)>0){

}//if(count($verificarCreacionCheques)==0){


header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]."&opt_subseccion=listaChequeraCuentaByBanco&cod=".$_GET["cod"]."&cod_cuenta=".$_GET["cod_cuenta"]);





?>
