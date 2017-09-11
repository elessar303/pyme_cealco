<?php


include("../../libs/php/clases/correlativos.php");


$comunes = new Comunes();
$formateo_nro_cheque = new Correlativos();

$nrochequera = @$_GET["cod_chequera"];


$campos = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM `chequera` where cod_chequera = ".$_GET["cod_chequera"]);

$verificarCreacionCheques = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM `cheque` where cod_chequera = ".$_GET["cod_chequera"]);

if(count($campos)>0){
    if(count($verificarCreacionCheques)>0){

        $comunes->BeginTrans();
        
            $activarChequera = "
                    update chequera chra
                        inner join cheque che on che.cod_chequera = chra.cod_chequera
                    set chra.situacion = 'D' , che.situacion = 'D' where chra.cod_chequera = ".$nrochequera."
                     and che.situacion not in ('En','Da','Im', 'An')";
            $comunes->ExecuteTrans($activarChequera);
    ;

        $comunes->CommitTrans($comunes->errorTransaccion);

    }//if(count($verificarCreacionCheques)==0){
}//if(count($campos)>0){


header("Location: ?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]."&opt_subseccion=listaChequeraCuentaByBanco&cod=".$_GET["cod"]."&cod_cuenta=".$_GET["cod_cuenta"]);





?>