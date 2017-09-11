<?php

class Cheque extends ConexionComun{

function __construct(){
        parent::__construct();
}

/**
 * Permite cambiar el estado del Cheque
 * a: Activo (A) , Deposito (D), Dañado (Da), Anulado (An).
 * Tambien permite verificar si es posible el cambio, ya que
 * si el cheque fue impreso no puede ser activado.
 */

function CambiarSituacionChequeByNroCheque(){
$situacion = "";
$parametros = func_get_args();
foreach($parametros as $campos){

    $cheque         = $campos["cheque"];
    $action_cheque  = $campos["action_cheque"];
    $cod_chequera      = $campos["cod_chequera"];

    switch($action_cheque){
        case "A":   $situacion = "A";   break;
        case "D":   $situacion = "D";   break;
        case "An":  $situacion = "An";  break;
        case "Da":  $situacion = "Da";   break;
        case "Im":  $situacion = "Im";  break;
        case "En":  $situacion = "En";  break;
        default: $situacion="Err";
    }


    $this->Execute2("update cheque set
        situacion = '".$situacion."'
        where
        cheque = ".$cheque." and
        cod_chequera = ".$cod_chequera."
    ");



    
    
    
}



}//function CambiarSituacionChequeByNroCheque(){




    
}


?>
