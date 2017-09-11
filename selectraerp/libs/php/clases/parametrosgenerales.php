<?php

class ParametrosGenerales extends ConexionComun{
    function __construct(){
        parent::__construct();
    }


    function TriggerActualizarStrintTipoPrecio($NEW_precio,$codTipoPrecio){
/*
 * Verificamos en la tabla tipo_precio; si fue cambiada la descripcion
 * tipo de precio actualizamos el campo descripcion de dicha tabla.
 */

$campo_precio = $this->ObtenerFilasBySqlSelect("select * from
                                                                tipo_precio
                                                                   where cod_tipo_precio = ".$codTipoPrecio);
    if($campo_precio!=""){
        $OLD_precio = $campo_precio[0]["descripcion"];
        if($NEW_precio!=$OLD_precio){
            $this->Execute2("update tipo_precio set descripcion = '".$NEW_precio."' where cod_tipo_precio = ".$codTipoPrecio);
        }
    }


        
    }
}

?>