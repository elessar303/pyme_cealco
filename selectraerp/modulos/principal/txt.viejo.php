<?php

class txt {

    private $temp, $retorno;

    public function completar($cad, $long, $fill=" ") {
        $this->temp = $cad;
        $tam_cad = strlen($cad);
        for ($i = 1; $i <= $long - $tam_cad; $i++) {
            $this->temp = $fill . $this->temp;
        }
        return $this->temp;
    }

    public function formatear_monto($monto) {
        $this->temp = explode(".", $monto);
        $this->retorno = $this->temp[0];
        $this->retorno.=$this->temp[1];
        return $this->retorno;
    }

    public function formatearLineasDetallesPago($cad1="", $cad2="", $tam=29) {
        $t1 = strlen($cad1);
        $t2 = strlen($cad2);
        if (($t1 + $t2) < $tam) {
            $whitepaces = abs($t1 + $t2 - $tam);
            $retorno = $cad1;
            for ($i = 0; $i < $whitepaces; $i++) {
                $retorno.=" ";
            }
            $retorno.=$cad2;
            return $retorno;
        }
    }

    public function formatearCantidadDecimales($cad) {
        if (strpos($cad, ".") === false && strpos($cad, ",") === false) {
            $cad.=",00";
        } elseif (strpos($cad, ".") === true) {
            $cad.=str_replace(".", ",", $cad);
        }
        return $cad;
    }

    public function formatearDatosCabecera($dato=" ", $cformat=".", $espacio=35) {
        $tam = strlen($dato);
        if ($tam <= $espacio) {
            if (!strcmp($dato, " ")) {
                $dato = $cformat;
            }
            $temp.=$dato;
            for ($i = 0; $i < ($espacio - $tam); $i++) {
                $temp.=$cformat;
            }
            $dato = $temp;
        }
        return $dato;
    }

}

?>
