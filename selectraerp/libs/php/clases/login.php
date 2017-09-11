<?php

class Login extends ConexionComun {

    function __construct() {
        parent::__construct();
    }

    function validarAcceso($usuario, $contrasena) {

        $this->usuario = $usuario;
        $this->contrasena = md5($contrasena);
        $this->instruccion = "SELECT * FROM usuarios  where usuario = '" . $this->usuario . "'  and clave   = '" . $this->contrasena . "'";
        $this->rs = $this->ObtenerFilasBySqlSelect($this->instruccion);

        if (!$this->rs[0]) {
            //$this->logout();
            return false;
        } else {

            $this->rCampos = $this->rs;
            $this->runSession();
            return true;
        }
        $this->db->Close();
        $rs->Close();
    }

    private function runSession() {
        $_SESSION["idSession"] = session_id();
        $_SESSION['islogin'] = 1;
        foreach ($this->rCampos as $clave => $valor) {
            $_SESSION['cod_usuario'] = $valor['cod_usuario'];
            $_SESSION['usuario'] = $valor['usuario'];
            $_SESSION['clave'] = $valor['clave'];
            $_SESSION['nombreyapellido'] = $valor['nombreyapellido'];
            $_SESSION['ultimo_login'] = $valor['ultima_sesion'];
            $_SESSION['estatus'] = $valor['estatus'];
            $_SESSION['anular_usuario'] = $valor['anular_pedido'];
        }

        $this->instruccion = "update usuarios set ultima_sesion = CURRENT_TIMESTAMP where cod_usuario = " . $_SESSION['cod_usuario'];
        $this->Execute2($this->instruccion);
    }

    function validarLoginON() {
        if ($_SESSION['islogin'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    function getIdUsuario() {
        return $_SESSION['cod_usuario'];
    }

    function getUsuario() {
        return $_SESSION['usuario'];
    }

    function getNombreApellidoUSuario() {
        return $_SESSION['nombreyapellido'];
    }

    function getClaveUsuario() {
        return $_SESSION['clave'];
    }

    function getUltimoLogin() {
        return $_SESSION['ultimo_login'];
    }

    function getIdSessionActual() {
        return $_SESSION["idSession"];
    }

    function getStatusUsuario() {
        return $_SESSION["estatus"];
    }
    function anular_usuario() {
        return $_SESSION["anular_usuario"];
    }

    function logout() {
        session_unset();
        session_destroy();
    }

}

?>
