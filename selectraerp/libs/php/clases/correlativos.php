<?php

class Correlativos extends ConexionComun {

    private $numero;

    function __construct() {
        parent::__construct();
    }

    /**
     * Función para el manejo de los correlativos.
     *
     * @param string $campo Especifica el campo con el cual se va a trabajar para el correlativo actual de dicho campo.
     * @param int $increment N Incrementa en valor N al campo pedido del parametro $campo.
     * @param string $formatear <si>/<no> Indica si se desea formatear o no el correlativo pedido.
     * @param string $cadena Indica la nomesclatura en caso de que el parametro $formatear sea <si>/<no>
     * @return string
     *
     * @author Luis Viera
     * @author lviera86@gmail.com
     *
     * Modificado por: Charli Vivenes
     * correo-e: cjvrinf@gmail.com / cvivenes@asys.com.ve
     */
    function getUltimoCorrelativo($campo, $increment = 0, $formatear = "no", $cadena = "") {
        $c = "";
        $this->numero = -1;
        if ($campo == "") {
            return -1;
        }

        switch ($campo) {
            case "cod_factura": $c = "cod_factura";
                break;
            case "cod_cotizacion": $c = "cod_cotizacion";
                break;
            case "cod_pedido": $c = "cod_pedido";
                break;
            case "cod_nota": $c = "cod_nota_entrega";
                break;
            case "cod_boleto": $c = "cod_boleto";
                break;
            case "cod_factura_boleto": $c = "cod_factura_boleto";
                break;
            case "cod_producto": $c = "cod_producto";
                break;
            case "cod_servicio": $c = "cod_servicio";
                break;
            case "cod_pago_o_abono": $c = "cod_pago_o_abono";
                break;
            case "cod_codebar": $c = "cod_codebar";
                break;
            case "cod_cliente": $c = "cod_cliente";
                break;
            case "cod_proveedor": $c = "cod_proveedor";
                break;
            case "cod_compra": $c = "cod_compra";
                break;
            case "cod_pago_o_abonoCXP": $c = "cod_pago_o_abonoCXP";
                break;
            case "cod_devolucion": $c = "cod_devolucion";
                break;
            case "cod_despacho": $c = "cod_despacho";
                break;
            default: $c = -1;
                break;
        }

        $instruccion = "SELECT contador, formato FROM correlativos WHERE campo = '{$c}';";
        $campo = $this->ObtenerFilasBySqlSelect($instruccion);
        $formato = $campo[0]["formato"];

        if (count($campo) > 0) {

            $this->numero = $campo[0]["contador"];

            if ($increment == 1) {
                $this->numero += 1;
            }

            /* if ($formatear == "si") {
              $this->numero = str_pad($this->numero, strlen($formato), "0", 0);
              #$this->numero = $this->FormatCorrelativo($formato, $this->numero);
              } else {
              return $this->numero;
              } */
            $this->numero = ($formatear == "si") ? str_pad($this->numero, strlen($formato), "0", 0) : $this->numero;
            
            return $cadena . $this->numero;
        }
    }

    /*
     * return Nro. Formateo
     */

    function FormatCorrelativo($formato, $contador) {
        $char = "0";
        $lenCantidadFormato = substr_count($formato, $char);
        $lenContador = strlen($contador);
        $CantidadCharArepetir = $lenCantidadFormato - $lenContador;
        $stringChars = "";
        for ($i = 1; $i <= $CantidadCharArepetir; $i++) {
            $stringChars .= $char;
        }
        $stringChars .= $contador;
        return $stringChars;
    }

}

?>
