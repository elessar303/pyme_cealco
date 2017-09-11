<?php
class Msg{
    static function setMessage($string_msg){
        $_SESSION["MensajeNotificacion"] = $string_msg;
    }

    static function getMessage(){
        return $_SESSION["MensajeNotificacion"];
    }

}//class Clientes{




?>